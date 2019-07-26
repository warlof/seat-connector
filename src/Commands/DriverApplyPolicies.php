<?php

namespace Warlof\Seat\Connector\Commands;

use Illuminate\Console\Command;
use Warlof\Seat\Connector\Exceptions\MissingDriverException;
use Warlof\Seat\Connector\Exceptions\UnknownDriverException;

/**
 * Class DriverApplyPolicies.
 *
 * @package Warlof\Seat\Connector\Commands
 */
class DriverApplyPolicies extends Command
{
    /**
     * @var string
     */
    protected $signature = 'seat-connector:apply:policies
                            {--driver=* : The specific driver name for which you want apply policy}
                            {--terminator : Revoke all Sets}';

    /**
     * @var string
     */
    protected $description = 'Enqueue jobs which will apply policy for all installed Drivers.';

    /**
     * @throws \Warlof\Seat\Connector\Exceptions\MissingDriverException
     * @throws \Warlof\Seat\Connector\Exceptions\UnknownDriverException
     */
    public function handle()
    {
        $drivers_parameter = $this->option('driver');
        $drivers = collect(array_keys(config('seat-connector.drivers')));
        $terminator = $this->option('terminator') ?: false;

        if ($drivers->isEmpty())
            throw new MissingDriverException('No SeAT Connector drivers has been found.' . PHP_EOL .
                'Please install at least one driver in order to be able to use this command.');

        // request user confirmation before queuing jobs
        if (is_null($drivers_parameter)) {
            if (! $this->confirm('Sets from all installed drivers will be synchronized. Do you wish to continue?', true))
                return;
        } else {
            if (! $this->confirm(
                sprintf('Sets from %s driver(s) will be synchronized. Do you wish to continue?',
                    implode(',', $drivers_parameter)), true))
                return;

            // ensure all provided drivers are valid
            if ($drivers->intersect($drivers_parameter)->count() != count($drivers_parameter))
                throw new UnknownDriverException();
        }

        foreach ($drivers as $driver) {

            if (! is_null($drivers_parameter) && ! in_array($driver, $drivers_parameter))
                continue;

            dispatch(new \Warlof\Seat\Connector\Jobs\DriverApplyPolicies($driver, $terminator))->onQueue('high');
            $this->info(sprintf('A new Policy job has been enqueue for driver %s', $driver));
        }
    }
}
