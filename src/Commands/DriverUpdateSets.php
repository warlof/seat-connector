<?php

/**
 * This file is part of seat-connector and provides user synchronization between both SeAT and third party platform
 *
 * Copyright (C) 2019, 2020  Loïc Leuilliot <loic.leuilliot@gmail.com>
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

namespace Warlof\Seat\Connector\Commands;

use Illuminate\Console\Command;
use Warlof\Seat\Connector\Exceptions\MissingDriverException;
use Warlof\Seat\Connector\Exceptions\UnknownDriverException;

/**
 * Class DriverUpdateSets.
 *
 * @package Warlof\Seat\Connector\Commands
 */
class DriverUpdateSets extends Command
{
    /**
     * @var string
     */
    protected $signature = 'seat-connector:sync:sets
                            {--driver=* : The specific driver name for which you want synchronize Sets}';

    /**
     * @var string
     */
    protected $description = 'Enqueue jobs which will pull information regarding all installed Driver Sets.';

    /**
     * @throws \Warlof\Seat\Connector\Exceptions\MissingDriverException
     * @throws \Warlof\Seat\Connector\Exceptions\UnknownDriverException
     */
    public function handle()
    {
        $drivers_parameter = $this->option('driver');
        $drivers = collect(array_keys(config('seat-connector.drivers')));

        if ($drivers->isEmpty())
            throw new MissingDriverException('No SeAT Connector drivers has been found.' . PHP_EOL .
                'Please install at least one driver in order to be able to use this command.');

        // request user confirmation before queuing jobs
        if (empty($drivers_parameter)) {
            if (app()->runningInConsole()) {
                if (!$this->confirm('Sets from all installed drivers will be synchronized. Do you wish to continue?', true))
                    return;
            }
        } else {
            if (app()->runningInConsole()) {
                if (!$this->confirm(
                    sprintf('Sets from %s driver(s) will be synchronized. Do you wish to continue?',
                        implode(',', $drivers_parameter)), true))
                    return;
            }

            // ensure all provided drivers are valid
            if ($drivers->intersect($drivers_parameter)->count() != count($drivers_parameter))
                throw new UnknownDriverException();
        }

        foreach ($drivers as $driver) {

            // in case a driver has been specified and the current driver does not match, skip it
            if (! empty($drivers_parameter) && ! in_array($driver, $drivers_parameter))
                continue;

            // enqueue a job for the specified driver
            dispatch(new \Warlof\Seat\Connector\Jobs\DriverUpdateSets($driver))->onQueue('high');
            $this->info(sprintf('A new Sets synchronization job has been enqueue for driver %s', $driver));
        }
    }
}
