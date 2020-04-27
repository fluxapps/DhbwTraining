<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model;

use JsonSerializable;

/**
 * Class AbstractValueObject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractValueObject implements JsonSerializable
{

    const VAR_CLASSNAME = "avo_class_name";


    /**
     * AbstractValueObject constructor.
     */
    protected function __construct() { }


    /**
     * Compares if two nullable ValueObjects are equal and returns true if they are
     *
     * @param AbstractValueObject $first
     * @param AbstractValueObject $second
     *
     * @return bool
     */
    public static function isNullableEqual($first,$second) : bool
    {
        if ($first === null) {
            if ($second === null) {
                return true;
            } else {
                return false;
            }
        }

        if ($second === null) {
            return false;
        }

        return $first->equals($second);
    }


    /**
     * Compares ValueObjects to each other returns true if they are the same
     *
     * @param AbstractValueObject $other
     *
     * @return bool
     */
    function equals(AbstractValueObject $other) : bool
    {
        return $this->jsonSerialize() == $other->jsonSerialize();
    }


    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $json = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $var) {
            $json[$key] = $this->sleep($key, $var) ?: $var;
        }
        $json[self::VAR_CLASSNAME] = get_called_class();

        return $json;
    }


    /**
     * @param $field_name
     *
     * @param $field_value
     *
     * @return mixed
     */
    protected function sleep($field_name, $field_value)
    {
        return $field_value instanceof abstractValueObject ? $field_value->jsonSerialize() : null;
    }


    /**
     * @param string|null $data
     *
     * @return array|AbstractValueObject|null
     */
    public static function deserialize($data = null)
    {
        if ($data === null) {
            return null;
        }

        $data_array = json_decode($data, true);

        if ($data_array === null) {
            return null;
        }

        return self::newFromArray($data_array);
    }


    /**
     * @param array|null $data
     *
     * @return AbstractValueObject|null
     */
    public static function newFromArray(array $data = null)
    {
        if ($data === null) {
            return null;
        }

        if (array_key_exists(self::VAR_CLASSNAME, $data)) {
            /** @var AbstractValueObject $object */
            $object = new $data[self::VAR_CLASSNAME]();

            foreach ($data as $key => $value) {
                $object->$key = is_array($value) ? self::newFromArray($value) : $value;
            }

            return $object;
        } else {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = self::newFromArray($value);
                }
            }

            return $data;
        }
    }


    /**
     * @return string
     */
    public function serialize() : string
    {
        return json_encode($this->jsonSerialize());
    }
}