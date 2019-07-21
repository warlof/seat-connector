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

namespace Warlof\Seat\Connector\Http\DataTables;

use Warlof\Seat\Connector\Models\Log;
use Yajra\DataTables\Services\DataTable;

/**
 * Class LogsDataTable.
 *
 * @package Warlof\Seat\Connector\Http\DataTables
 */
class LogsDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $logs = Log::query()
            ->select(
                'created_at',
                'level',
                'category',
                'message'
            );

        return $logs;
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns());
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            [
                'name' => 'created_at',
                'data' => 'created_at',
                'title' => trans('seat-connector::seat.datetime'),
            ],
            [
                'name' => 'level',
                'data' => 'level',
                'title' => trans('seat-connector::seat.level'),
            ],
            [
                'name' => 'category',
                'data' => 'category',
                'title' => trans('seat-connector::seat.category'),
            ],
            [
                'name' => 'message',
                'data' => 'message',
                'title' => trans('seat-connector::seat.message'),
            ],
        ];
    }
}
