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

use Warlof\Seat\Connector\Http\DataTables\LogsDataTable;
use Warlof\Seat\Connector\Http\DataTables\Scopes\LogsDataTableScope;

/**
 * Class LogsController.
 *
 * @package Warlof\Seat\Connector\Http\Controllers
 */
class LogsController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(LogsDataTable $datatable)
    {
        // retrieve all registered SeAT Connector drivers
        $available_drivers = config('seat-connector.drivers', []);

        // init the driver using either the query parameter or the first available driver
        $driver = request()->query('driver') ?: array_get(array_last($available_drivers), 'name');

        return $datatable
            ->addScope(new LogsDataTableScope($driver))
            ->render('seat-connector::logs.list');
    }
}
