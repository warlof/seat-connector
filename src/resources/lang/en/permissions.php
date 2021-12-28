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
    'view_label'       => 'View',
    'view_description' => 'Users with this permission can see a connector entry into the sidebar. They can also see available platforms and register to them.',

    'security_label'       => 'Security',
    'security_description' => 'Users with this permissions can manage binding between SeAT and platforms. This include users management and access mapping.',

    'logs_review_label'       => 'Logs Review',
    'logs_review_description' => 'Users with this permissions are able to show SeAT Connector logs.',
];
