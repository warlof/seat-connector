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

namespace Warlof\Seat\Connector\Listeners;

use Warlof\Seat\Connector\Events\EventLogger;
use Warlof\Seat\Connector\Models\Log;

/**
 * Class LoggerListener.
 *
 * @package Warlof\Seat\Connector\Listeners
 */
class LoggerListener
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    const LEVELS = [
        'debug'     => self::DEBUG,
        'info'      => self::INFO,
        'notice'    => self::NOTICE,
        'warning'   => self::WARNING,
        'error'     => self::ERROR,
        'critical'  => self::CRITICAL,
        'alert'     => self::ALERT,
        'emergency' => self::EMERGENCY,
    ];

    /**
     * @param \Warlof\Seat\Connector\Events\EventLogger $event
     */
    public function handle(EventLogger $event)
    {
        if (! array_key_exists($event->level, self::LEVELS))
            return;

        if (! array_key_exists(config('seat-connector.config.logging.level', 'error'), self::LEVELS))
            return;

        if (self::LEVELS[$event->level] < self::LEVELS[config('seat-connector.config.logging.level', 'error')])
            return;

        Log::create([
            'connector_type' => $event->driver,
            'level'          => $event->level,
            'category'       => $event->category,
            'message'        => $event->message,
        ]);
    }
}
