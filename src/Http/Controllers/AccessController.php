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
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;
use Warlof\Seat\Connector\Http\DataTables\AccessDataTable;
use Warlof\Seat\Connector\Http\DataTables\Scopes\AccessDataTableScope;
use Warlof\Seat\Connector\Http\Validations\AccessRuleValidation;
use Warlof\Seat\Connector\Models\Set;

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
     * @param \Warlof\Seat\Connector\Http\Validations\AccessRuleValidation $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create(AccessRuleValidation $request)
    {
        $group = Set::find($request->input('set_id'));

        switch ($request->input('entity_type')) {
            case 'group':
            case Group::class:
                $entity = Group::find($request->input('entity_id'));
                $group->groups()->save($entity);
                break;
            case 'role':
            case Role::class:
                $entity = Role::find($request->input('entity_id'));
                $group->roles()->save($entity);
                break;
            case 'corporation':
            case CorporationInfo::class:
                $entity = CorporationInfo::find($request->input('entity_id'));
                $group->corporations()->save($entity);
                break;
            case 'alliance':
            case Alliance::class:
                $entity = Alliance::find($request->input('entity_id'));
                $group->alliances()->save($entity);
                break;
            default:
                throw new Exception('Unsupported entity type');
        }

        return redirect()
            ->back()
            ->with('success', 'The rule has been successfully added.');
    }

    /**
     * @param \Warlof\Seat\Connector\Http\Validations\AccessRuleValidation $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(AccessRuleValidation $request)
    {
        $group = Set::find($request->input('set_id'));

        switch ($request->input('entity_type')) {
            case 'group':
            case Group::class:
                $entity = Group::find($request->input('entity_id'));
                $group->groups($entity)->detach();
                break;
            case 'role':
            case Role::class:
                $entity = Role::find($request->input('entity_id'));
                $group->roles($entity)->detach();
                break;
            case 'corporation':
            case CorporationInfo::class:
                $entity = CorporationInfo::find($request->input('entity_id'));
                $group->corporations($entity)->detach();
                break;
            case 'alliance':
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
