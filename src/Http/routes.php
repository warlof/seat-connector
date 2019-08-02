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

Route::group([
    'namespace' => 'Warlof\Seat\Connector\Http\Controllers',
    'prefix'    => 'seat-connector',
    'middleware' => ['web', 'auth', 'locale'],
], function () {

    Route::get('/identities', [
        'as'   => 'seat-connector.identities',
        'uses' => 'IdentitiesController@index',
    ]);

    Route::group([
        'middleware' => 'bouncer:superuser',
    ], function () {

        Route::get('/settings', [
            'as' => 'seat-connector.settings',
            'uses' => 'SettingsController@index',
        ]);

    });

    Route::group([
        'middleware' => 'bouncer:seat-connector.security',
    ], function () {

        Route::get('/logs', [
            'as'   => 'seat-connector.logs',
            'uses' => 'LogsController@index',
        ]);

        Route::get('/users', [
            'as'   => 'seat-connector.users',
            'uses' => 'UsersController@index',
        ]);

        Route::get('/access', [
            'as'   => 'seat-connector.acl',
            'uses' => 'AccessController@index',
        ]);

        Route::post('/access', [
            'as'   => 'seat-connector.acl.create',
            'uses' => 'AccessController@create',
        ]);

        Route::delete('/access', [
            'as' => 'seat-connector.acl.remove',
            'uses' => 'AccessController@remove',
        ]);

        Route::group([
            'prefix' => 'api',
        ], function () {

            Route::get('/roles', [
                'as'   => 'seat-connector.api.roles',
                'uses' => 'LookupController@getRoles',
            ]);

            Route::get('/titles', [
                'as'   => 'seat-connector.api.titles',
                'uses' => 'LookupController@getTitles',
            ]);

            Route::get('/sets', [
                'as'   => 'seat-connector.api.sets',
                'uses' => 'LookupController@getSets',
            ]);

        });

    });

});
