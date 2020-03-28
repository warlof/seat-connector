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

namespace Warlof\Seat\Connector\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Warlof\Seat\Connector\Drivers\IUser;
use Warlof\Seat\Connector\Events\EventLogger;
use Warlof\Seat\Connector\Exceptions\MissingDriverClientException;
use Warlof\Seat\Connector\Models\User;
use Warlof\Seat\Connector\Traits\ConnectorPolicyManagement;

/**
 * Class DriverApplyPolicies.
 *
 * @package Warlof\Seat\Connector\Jobs
 */
class DriverApplyPolicies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ConnectorPolicyManagement;

    /**
     * @var \Warlof\Seat\Connector\Drivers\IClient
     */
    private $client;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var bool
     */
    protected $terminator;

    /**
     * @var array
     */
    protected $tags = [
        'connector',
    ];

    /**
     * DriverApplyPolicies constructor.
     *
     * @param string $driver
     */
    public function __construct(string $driver, bool $terminator = false)
    {
        $this->driver     = $driver;
        $this->terminator = $terminator;
        $this->tags       = array_merge($this->tags, [$driver]);

        if ($terminator) {
            $this->tags = array_merge($this->tags, ['terminator']);
        }
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return $this->tags;
    }

    /**
     * Process the job.
     *
     * @throws \Warlof\Seat\Connector\Exceptions\DriverException
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function handle()
    {
        $config_key = sprintf('seat-connector.drivers.%s.client', $this->driver);
        $client = config($config_key);

        if (is_null($config_key) || ! class_exists($client))
            throw new MissingDriverClientException(sprintf('The client for driver %s is missing.', $this->driver));

        $this->client = $client::getInstance();

        $this->client->getSets();

        // collect all users from the active driver
        $users = $this->client->getUsers();

        // loop over each entity and apply policy
        foreach ($users as $user) {

            try {

                $this->applyPolicy($user);

            } catch (Exception $e) {

                event(new EventLogger($this->driver, 'error', 'policy',
                    sprintf('Unable to update the user %s. %s',
                        $user->getName(), $e->getMessage())));

            }

        }
    }

    /**
     * @param \Warlof\Seat\Connector\Drivers\IUser $user
     * @throws \Seat\Services\Exceptions\SettingException
     * @throws \Warlof\Seat\Connector\Exceptions\DriverException
     */
    private function applyPolicy(IUser $user)
    {
        $profile = User::where('connector_type', $this->driver)
            ->where('connector_id', $user->getClientId())
            ->first();

        // in case the user is unknown of SeAT; skip the process
        if (is_null($profile))
            return;

        $this->handleSetsUpdate($profile, $user);

        $this->handleNicknameUpdate($profile, $user);
    }
}
