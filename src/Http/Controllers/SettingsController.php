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

use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Warlof\Seat\Connector\Drivers\Driver;

/**
 * Class SettingsController.
 *
 * @package Warlof\Seat\Connector\Http\Controllers
 */
class SettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverException
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException
     */
    public function index()
    {
        $drivers = collect();
        $available_drivers = config('seat-connector.drivers', []);

        foreach ($available_drivers as $driver => $metadata) {
            $drivers->put($driver, new Driver($metadata));
        }

        return view('seat-connector::settings.list', compact('drivers'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function update(Request $request)
    {
        $request->validate([
            'security-level' => 'required|in:strict,normal',
            'use-ticker'     => 'required|boolean',
            'prefix-format'  => 'required|string',
        ]);

        setting(['seat-connector.ticker', $request->input('use-ticker') == '1'], true);
        setting(['seat-connector.strict', $request->input('security-level') == 'strict'], true);
        setting(['seat-connector.format', $request->input('prefix-format')], true);

        return redirect()->back()
            ->with('success', 'SeAT Connector has been updated.');
    }
}
