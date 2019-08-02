<?php

namespace Warlof\Seat\Connector\Drivers;

/**
 * Class Field.
 *
 * @package Warlof\Seat\Connector\Drivers
 */
class Field
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $driver;

    /**
     * Field constructor.
     *
     * @param array $field
     */
    public function __construct(string $driver, array $field)
    {
        $this->driver = $driver;

        $this->name  = $field['name'];
        $this->label = $field['label'];
        $this->type  = $field['type'];
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed|null
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getValue()
    {
        $name     = $this->name;
        $settings = setting(sprintf('seat-connector.drivers.%s', $this->driver), true);

        if (is_null($settings))
            return null;

        if (! is_object($settings))
            return null;

        if (! property_exists($settings, $this->name))
            return null;

        return $settings->$name;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $method = sprintf('get%s', ucfirst($name));

        if (property_exists($this, $name))
            return $this->$name;

        if (method_exists($this, $method))
            return $this->$method();

        return null;
    }
}
