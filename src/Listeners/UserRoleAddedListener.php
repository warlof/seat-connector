<?php

/**
 * This file is part of seat-connector and provides user synchronization between both SeAT and third party platform
 *
 * Copyright (C) 2020  Loïc Leuilliot <loic.leuilliot@gmail.com>
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

use Illuminate\Contracts\Queue\ShouldQueue;
use Seat\Web\Events\UserRoleAdded;
use Seat\Web\Models\User;
use Warlof\Seat\Connector\Observers\AbstractIdentityObserver;

/**
 * Class UserRoleAddedListener.
 *
 * @package Warlof\Seat\Connector\Listeners
 */
class UserRoleAddedListener extends AbstractIdentityObserver implements ShouldQueue
{
    /**
     * @var int
     */
    public $delay = 60;

    /**
     * @var string
     */
    public $queue = 'high';

    /**
     * @param \Seat\Web\Events\UserRoleAdded $event
     */
    public function handle(UserRoleAdded $event)
    {
        $user = User::find($event->user_id);

        if (! $user)
            return;

        $this->notifyDrivers($user);
    }

    /**
     * @param \Seat\Web\Events\UserRoleAdded $event
     * @return bool
     */
    public function shouldQueue(UserRoleAdded $event)
    {
        return (User::find($event->user_id) != null);
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return [
            'connector',
        ];
    }
}
