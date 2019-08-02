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
     * Field constructor.
     *
     * @param array $field
     */
    public function __construct(array $field)
    {
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
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
}
