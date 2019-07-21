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

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;
use Warlof\Seat\Connector\Http\DataTables\AccessDataTable;
use Warlof\Seat\Connector\Http\DataTables\Scopes\AccessDataTableScope;

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

    public function remove()
    {
        throw new \Symfony\Component\Intl\Exception\MethodNotImplementedException('remove');
    }
}
