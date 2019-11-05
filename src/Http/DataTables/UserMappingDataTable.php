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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Models\UserSetting;
use Warlof\Seat\Connector\Models\User;
use Yajra\DataTables\Services\DataTable;

/**
 * Class UserMappingDataTable.
 *
 * @package Warlof\Seat\Connector\Http\DataTables
 */
class UserMappingDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->filterColumn('group_id', function ($query, $keyword) {
                $query->whereRaw('users.group_id LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('character_id', function ($query, $keyword) {
                $query->whereRaw('characters_infos.character_id LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw('value LIKE ?', ["%{$keyword}%"]);
            })
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $users = User::query()
            ->leftJoin((new UserSetting())->getTable(), function ($join) {
                $join->on((new User())->getTable() . '.group_id', '=', (new UserSetting())->getTable() . '.group_id')
                    ->where((new UserSetting())->getTable() . '.name', '=', 'main_character_id');
            })
            ->leftJoin((new CharacterInfo())->getTable(), 'character_id', '=', 'value')
            ->select(
                (new User())->getTable() . '.group_id',
                'connector_id',
                'connector_name',
                'character_id',
                (new CharacterInfo())->getTable() . '.name'
            );

        return $users;
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
    protected function getColumns()
    {
        return [
            [
                'data'  => 'group_id',
                'title' => trans('seat-connector::seat.group_id'),
            ],
            [
                'data'  => 'character_id',
                'title' => trans('seat-connector::seat.character_id'),
            ],
            [
                'data'  => 'name',
                'title' => trans('seat-connector::seat.character_name'),
            ],
            [
                'data'  => 'connector_id',
                'title' => trans('seat-connector::seat.connector_id'),
            ],
            [
                'data'  => 'connector_name',
                'title' => trans('seat-connector::seat.connector_name'),
            ],
        ];
    }
}
