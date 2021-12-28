<?php

/*
 * This file is part of seat-connector and provides user synchronization between both SeAT and third party platform
 *
 * Copyright (C) 2019 to 2022 Loïc Leuilliot <loic.leuilliot@gmail.com>
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
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Warlof\Seat\Connector\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Corporation\CorporationTitle;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Squads\Squad;
use Warlof\Seat\Connector\Models\Set;

/**
 * Class LookupController.
 */
class LookupController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTitles(Request $request)
    {
        $titles = CorporationTitle::where('corporation_id', $request->query('corporation_id'))
            ->where('name', 'like', '%' . $request->query('q', '') . '%')
            ->get()
            ->map(function ($title, $key) {
                return [
                    'id'   => $title->id,
                    'text' => strip_tags($title->name),
                ];
            });

        return response()->json([
            'results' => $titles,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles(Request $request)
    {
        $roles = Role::where('title', 'like', '%' . $request->query('q', '') . '%')
                    ->get()
                    ->map(function ($role, $key) {
                        return [
                            'id'   => $role->id,
                            'text' => $role->title,
                        ];
                    });

        return response()->json([
            'results' => $roles,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSquads(Request $request)
    {
        $squads = Squad::where('name', 'like', '%' . $request->query('q', '') . '%')
                    ->get()
                    ->map(function ($squad, $key) {
                        return [
                            'id'   => $squad->id,
                            'text' => $squad->name,
                        ];
                    });

        return response()->json([
            'results' => $squads,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSets(Request $request)
    {
        $sets = Set::where('name', 'like', '%' . $request->query('q', '') . '%')
                   ->where('connector_type', $request->query('driver', ''))
                   ->get()
                   ->map(function ($group, $key) {
                       return [
                           'id' => $group->id,
                           'text' => $group->name,
                       ];
                   });

        return response()->json([
            'results' => $sets,
        ]);
    }
}
