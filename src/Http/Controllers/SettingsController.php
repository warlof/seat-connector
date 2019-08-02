<?php


namespace Warlof\Seat\Connector\Http\Controllers;

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
}
