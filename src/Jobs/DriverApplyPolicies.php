<?php

namespace Warlof\Seat\Connector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Warlof\Seat\Connector\Drivers\IUser;
use Warlof\Seat\Connector\Models\User;

/**
 * Class DriverApplyPolicies.
 *
 * @package Warlof\Seat\Connector\Jobs
 */
class DriverApplyPolicies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * @throws \Warlof\Seat\Connector\Jobs\MissingDriverClientException
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

            $this->applyPolicy($user);

        }
    }

    /**
     * @param \Warlof\Seat\Connector\Drivers\IUser $user
     * @throws \Seat\Services\Exceptions\SettingException
     */
    private function applyPolicy(IUser $user)
    {
        $sets          = null;
        $new_nickname  = null;
        $pending_drops = collect();
        $pending_adds  = collect();
        $profile       = User::where('connector_type', $this->driver)
                             ->where('connector_id', $user->getClientId())
                             ->first();

        // in case the user is unknown of SeAT; skip the process
        if (is_null($profile))
            return;

        // determine which nickname should be used by the user
        $expected_nickname = $this->buildConnectorNickname($profile);
        if ($user->getName() !== $expected_nickname)
            $new_nickname = $expected_nickname;

        // collect all sets which are assigned to the user and determine if they are valid
        foreach ($user->getSets() as $set) {
            if ($this->terminator || ! $profile->isAllowedSet($set->getId()))
                $pending_drops->push($set->getId());
        }

        // if the process is not running in terminator mode, retrieve all valid sets for the current user
        if (! $this->terminator) {
            $sets = $profile->allowedSets();

            foreach ($sets as $set_id) {
                if (! in_array($set_id, $user->getSets()))
                    $pending_adds->push($set_id);
            }
        }

        // check if there is a set to update
        $are_sets_outdated = $pending_adds->isNotEmpty() || $pending_drops->isNotEmpty();

        if ($are_sets_outdated) {

            // drop all sets which have been marked for a removal
            foreach ($pending_drops as $set_id) {
                $set = $this->client->getSet($set_id);
                $user->removeSet($set);
            }

            // add all sets which have been marked for an addition
            foreach ($pending_adds as $set_id) {
                $set = $this->client->getSet($set_id);
                $user->addSet($set);
            }
        }
        
        if (! is_null($new_nickname)) {
            $user->setName($new_nickname);
        }
    }

    /**
     * @param \Warlof\Seat\Connector\Models\User $user
     * @return string
     * @throws \Seat\Services\Exceptions\SettingException
     */
    private function buildConnectorNickname(User $user): string
    {
        $character = $user->group->main_character;
        if (is_null($character))
            $character = $user->group->users->first()->character;

        $nickname = $character->name;

        if (setting('seat-connector.ticker', true)) {
            $corporation = CorporationInfo::find($character->corporation_id);
            $format = setting('seat-connector.format', true) ?: '[%s] %s';

            if (! is_null($corporation))
                $nickname = sprintf($format, $corporation->ticker, $nickname);
        }

        return Str::limit($nickname, $this->client->getNicknameMaxSize(), '');
    }
}
