<?php

namespace Warlof\Seat\Connector\Drivers;

use Warlof\Seat\Connector\Exceptions\InvalidDriverException;
use Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException;

/**
 * Class Driver.
 *
 * @package Warlof\Seat\Connector\Drivers
 */
class Driver
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $client;

    /**
     * @var \Warlof\Seat\Connector\Drivers\Field[]
     */
    private $settings;

    /**
     * Driver constructor.
     *
     * @param array $config
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverException
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException
     */
    public function __construct(array $config)
    {
        $this->checkStructure($config);

        $this->name     =  $config['name'];
        $this->icon     =  $config['icon'];
        $this->client   =  $config['client'];
        $this->settings = collect();

        foreach ($config['settings'] as $field)
            $this->settings->push(new Field($field));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @return \Warlof\Seat\Connector\Drivers\Field[]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @param array $structure
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverException
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException
     */
    private function checkStructure(array $structure)
    {
        foreach (['name', 'icon', 'client', 'settings'] as $attribute) {
            if (! array_key_exists($attribute, $structure))
                throw new InvalidDriverException(sprintf('Driver configuration must have a %s field', $attribute));
        }

        if (! is_string($structure['name']))
            throw new InvalidDriverException('Driver configuration name field must be of string type');

        if (! is_string($structure['icon']))
            throw new InvalidDriverException('Driver configuration icon field must be of string type');

        if (! is_string($structure['client']))
            throw new InvalidDriverException('Driver configuration client field must be of string type');

        if (! class_exists($structure['client']))
            throw new InvalidDriverException('Driver configuration client field must refer to an existing class');

        if (! is_array($structure['settings']))
            throw new InvalidDriverException('Driver configuration settings field must be of array type');

        if (is_null($structure['name']) || empty($structure['name']))
            throw new InvalidDriverException('Driver configuration name field is mandatory');

        if (is_null($structure['icon']) || empty($structure['icon']))
            throw new InvalidDriverException('Driver configuration icon field is mandatory');

        if (is_null($structure['client']) || empty($structure['client']))
            throw new InvalidDriverException('Driver configuration client field is mandatory');

        if (is_null($structure['settings']))
            throw new InvalidDriverException('Driver configuration settings field is mandatory');

        $this->checkSettingsStructure($structure['settings']);
    }

    /**
     * @param array $settings
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverException
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException
     */
    private function checkSettingsStructure(array $settings)
    {
        if (count($settings) == 0)
            throw new InvalidDriverException('Driver configuration settings field must have at least one field definition');

        foreach ($settings as $field) {
            $this->checkSettingsFieldStructure($field);
        }
    }

    /**
     * @param $field
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException
     */
    private function checkSettingsFieldStructure($field)
    {
        if (! is_array($field))
            throw new InvalidDriverSettingsException('Driver configuration settings fields must be of array type');

        foreach (['name', 'label', 'type'] as $attribute) {
            if (! array_key_exists($attribute, $field))
                throw new InvalidDriverSettingsException(sprintf('Driver configuration settings fields must have a %s field', $attribute));

            if (! is_string($field['name']))
                throw new InvalidDriverSettingsException(sprintf('Driver configuration settings fields %s field must be of string type', $attribute));
        }
    }
}
