<?php
/**
 * This file is part of seat-connector and provides user synchronization between both SeAT and third party platform
 *
 * Copyright (C) 2019  Loïc Leuilliot <loic.leuilliot@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Warlof\Seat\Connector\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;
use Warlof\Seat\Connector\Http\DataTables\AccessDataTable;
use Warlof\Seat\Connector\Http\DataTables\Scopes\AccessDataTableScope;
use Warlof\Seat\Connector\Models\PermissionGroup;

/**
 * Class AccessManagementController.
 *
 * @package Warlof\Seat\Connector\Http\Controllers
 */
class AccessController extends Controller
{
    /**
     * @param \Warlof\Seat\Connector\Http\DataTables\AccessDataTable $datatable
     * @return mixed
     */
    public function index(AccessDataTable $datatable)
    {
        // retrieve all registered SeAT Connector drivers
        $available_drivers = config('seat-connector.drivers', []);

        // init the driver using either the query parameter or the first available driver
        $driver = request()->query('driver', array_get(array_last($available_drivers), 'name'));

        // init the filter type using either the query parameter or public
        switch (request()->query('filter_type', 'user')) {
            case 'user':
                $filter_type = Group::class;
                break;
            case 'role':
                $filter_type = Role::class;
                break;
            case 'corporation':
                $filter_type = CorporationInfo::class;
                break;
            case 'alliance':
                $filter_type = Alliance::class;
                break;
        }

        return $datatable
            ->addScope(new AccessDataTableScope($filter_type, $driver))
            ->render('seat-connector::access.list');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Request $request)
    {
        $filter_type = [
            Group::class,
            Role::class,
            CorporationInfo::class,
            Alliance::class,
        ];

        $this->validate($request, [
            'entity_id'           => 'required|integer',
            'entity_type'         => 'required|in:' . implode(',', $filter_type),
            'permission_group_id' => 'required|exists:seat_connector_permission_groups,id',
        ]);

        $group = PermissionGroup::find($request->input('permission_group_id'));

        switch ($request->input('entity_type')) {
            case Group::class:
                $entity = Group::find($request->input('entity_id'));
                $group->groups($entity)->detach();
                break;
            case Role::class:
                $entity = Role::find($request->input('entity_id'));
                $group->roles($entity)->detach();
                break;
            case CorporationInfo::class:
                $entity = CorporationInfo::find($request->input('entity_id'));
                $group->corporations($entity)->detach();
                break;
            case Alliance::class:
                $entity = Alliance::find($request->input('entity_id'));
                $group->alliances($entity)->detach();
                break;
            default:
                throw new Exception('Unsupported entity type');
        }

        return redirect()
            ->back()
            ->with('success', 'The rule has been successfully removed.');
    }
}
