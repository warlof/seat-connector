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

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Models\UserSetting;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;
use Warlof\Seat\Connector\Models\Set;
use Yajra\DataTables\Services\DataTable;

/**
 * Class AccessDataTable.
 *
 * @package Warlof\Seat\Connector\Http\DataTables
 */
class AccessDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('action', function ($row) {
                return view('seat-connector::access.includes.buttons.remove', compact('row'));
            })
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->applyScopes($this->getCorporationQuery())
            ->union($this->applyScopes($this->getAllianceQuery()))
            ->union($this->applyScopes($this->getRoleQuery()))
            ->union($this->applyScopes($this->getGroupQuery()));
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction([
                'class' => 'text-right',
                'title' => '',
            ])
            ->ajax([
                'data' => 'function(d) { d.driver = $("#connector-driver").val(); d.filter_type = $("#connector-table-filters li.active").data("filter"); }',
            ]);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            'entity_name' => [
                'name'  => 'entity_name',
                'data'  => 'entity_name',
                'title' => trans('seat-connector::seat.entity_name'),
            ],
            'name' => [
                'name'  => 'name',
                'data'  => 'name',
                'title' => trans_choice('seat-connector::seat.sets', 0),
            ],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getCorporationQuery()
    {
        $query = Set::
            join('seat_connector_set_entity', 'set_id', 'id')
            ->join((new CorporationInfo())->getTable(), function ($join) {
                $join->on('entity_id', 'corporation_id');
                $join->where('entity_type', CorporationInfo::class);
            })
            ->select(
                'seat_connector_sets.id',
                'seat_connector_sets.connector_type',
                'seat_connector_sets.connector_id',
                'seat_connector_sets.name',
                'seat_connector_set_entity.entity_type',
                'seat_connector_set_entity.entity_id',
                (new CorporationInfo())->getTable() . '.name as entity_name');

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getAllianceQuery()
    {
        $query = Set::
            join('seat_connector_set_entity', 'set_id', 'id')
            ->join((new Alliance())->getTable(), function ($join) {
                $join->on('entity_id', 'alliance_id');
                $join->where('entity_type', Alliance::class);
            })
            ->select(
                'seat_connector_sets.id',
                'seat_connector_sets.connector_type',
                'seat_connector_sets.connector_id',
                'seat_connector_sets.name',
                'seat_connector_set_entity.entity_type',
                'seat_connector_set_entity.entity_id',
                (new Alliance())->getTable() . '.name as entity_name');

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getRoleQuery()
    {
        $query = Set::
            join('seat_connector_set_entity', 'set_id', 'id')
            ->join((new Role())->getTable(), function ($join) {
                $join->on('entity_id', (new Role())->getTable() . '.id');
                $join->where('entity_type', Role::class);
            })
            ->select(
                'seat_connector_sets.id',
                'seat_connector_sets.connector_type',
                'seat_connector_sets.connector_id',
                'seat_connector_sets.name',
                'seat_connector_set_entity.entity_type',
                'seat_connector_set_entity.entity_id',
                (new Role())->getTable() . '.title as entity_name');

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getGroupQuery()
    {
        $query = Set::
            join('seat_connector_set_entity', 'set_id', 'id')
            ->join((new Group())->getTable(), function ($join) {
                $join->on('entity_id', (new Group())->getTable() . '.id');
                $join->where('entity_type', Group::class);
            })
            ->join((new UserSetting())->getTable(), function ($join) {
                $join->on('group_id', (new Group())->getTable() . '.id');
                $join->where((new UserSetting())->getTable() . '.name', 'main_character_id');
            })
            ->join((new User())->getTable(), (new User())->getTable() . '.id', 'value')
            ->select(
                'seat_connector_sets.id',
                'seat_connector_sets.connector_type',
                'seat_connector_sets.connector_id',
                'seat_connector_sets.name',
                'seat_connector_set_entity.entity_type',
                'seat_connector_set_entity.entity_id',
                (new User())->getTable() . '.name as entity_name');

        return $query;
    }
}
