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
            $this->settings->push(new Field($this->name, $field));
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
            $this->checkPropertyStructure($structure, $attribute);
        }

        $this->checkSettingsStructure($structure['settings']);
    }

    /**
     * @param array $structure
     * @param string $property
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverException
     */
    private function checkPropertyStructure(array $structure, string $property)
    {
        if (! array_key_exists($property, $structure))
            throw new InvalidDriverException(sprintf('Driver configuration must have a %s field', $property));

        if (is_null($structure[$property]) || empty($structure[$property]))
            throw new InvalidDriverException(sprintf('Driver configuration %s field is mandatory', $property));

        switch ($property) {
            case 'client':
                if (! class_exists($structure[$property]))
                    throw new InvalidDriverException(sprintf('Driver configuration %s field must refer to an existing class', $property));
                break;
            case 'settings':
                if (! is_array($structure[$property]))
                    throw new InvalidDriverException(sprintf('Driver configuration %s field must be of array type', $property));
                break;
            default:
                if (! is_string($structure[$property]))
                    throw new InvalidDriverException(sprintf('Driver configuration %s field must be of string type', $property));
        }
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

            if (! is_array($field))
                throw new InvalidDriverSettingsException('Driver configuration settings fields must be of array type');

            $this->checkSettingsFieldStructure($field);
        }
    }

    /**
     * @param array $field
     * @throws \Warlof\Seat\Connector\Exceptions\InvalidDriverSettingsException
     */
    private function checkSettingsFieldStructure(array $field)
    {
        foreach (['name', 'label', 'type'] as $attribute) {
            if (! array_key_exists($attribute, $field))
                throw new InvalidDriverSettingsException(sprintf('Driver configuration settings fields must have a %s field', $attribute));

            if (! is_string($field['name']))
                throw new InvalidDriverSettingsException(sprintf('Driver configuration settings fields %s field must be of string type', $attribute));
        }
    }
}
