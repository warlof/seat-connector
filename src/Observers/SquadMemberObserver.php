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

namespace Warlof\Seat\Connector\Observers;

use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\User as SeatUser;
use Warlof\Seat\Connector\Jobs\NotifyDriver;

/**
 * Class SquadMemberObserver.
 *
 * @package Warlof\Seat\Connector\Observers
 */
class SquadMemberObserver
{

    /**
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    public function created(SquadMember $member)
    {
        $this->handle($member);
    }

    /**
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    public function updated(SquadMember $member)
    {
        $this->handle($member);
    }

    /**
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    public function deleted(SquadMember $member)
    {
        $this->handle($member);
    }

    /**
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    private function handle(SquadMember $member)
    {
        // attempt to retrieve attached user
        $user = SeatUser::standard()
            ->where('id', $member->user_id)
            ->first();

        if (! $user)
            return;

        dispatch(new NotifyDriver($user));
    }
}
