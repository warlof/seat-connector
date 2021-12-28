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

return [
    'seat-connector' => [
        'name'          => 'Connector',
        'icon'          => 'fas fa-plug',
        'route_segment' => 'seat-connector',
        'entries'       => [
            [
                'name'       => 'Identities',
                'label'      => 'seat-connector::seat.identities',
                'plural'     => true,
                'icon'       => 'fas fa-address-card',
                'route'      => 'seat-connector.identities',
                'permission' => 'seat-connector.view',
            ],
            [
                'name'       => 'Logs',
                'label'      => 'web::seat.log',
                'plural'     => true,
                'icon'       => 'fas fa-list',
                'route'      => 'seat-connector.logs',
                'permission' => 'seat-connector.logs_review',
            ],
            [
                'name'       => 'User Mapping',
                'label'      => 'seat-connector::seat.user_mapping',
                'icon'       => 'fas fa-user-shield',
                'route'      => 'seat-connector.users',
                'permission' => 'seat-connector.security',
            ],
            [
                'name'       => 'Access Management',
                'label'      => 'seat-connector::seat.access_management',
                'icon'       => 'fas fa-shield-alt',
                'route'      => 'seat-connector.acl',
                'permission' => 'seat-connector.security',
            ],
            [
                'name'       => 'Settings',
                'label'      => 'seat-connector::seat.settings',
                'icon'       => 'fas fa-cogs',
                'route'      => 'seat-connector.settings',
                'permission' => 'global.superuser',
            ],
        ],
        'permission'    => 'seat-connector.view',
    ],
];
