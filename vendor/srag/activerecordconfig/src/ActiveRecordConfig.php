<?php

namespace srag\ActiveRecordConfig\DhbwTraining;

use arConnector;
use srag\ActiveRecordConfig\DhbwTraining\Config\AbstractFactory;
use srag\ActiveRecordConfig\DhbwTraining\Config\AbstractRepository;
use srag\ActiveRecordConfig\DhbwTraining\Config\Config;
use srag\ActiveRecordConfig\DhbwTraining\Exception\ActiveRecordConfigException;

/**
 * Class ActiveRecordConfig
 *
 * @package    srag\ActiveRecordConfig\DhbwTraining
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Please use AbstractRepository instead
 */
class ActiveRecordConfig extends Config
{

    /**
     * @var string
     *
     * @abstract
     *
     * @deprecated
     */
    const TABLE_NAME = "";
    /**
     * @var array
     *
     * @abstract
     *
     * @deprecated
     */
    protected static $fields = [];


    /**
     * ActiveRecordConfig constructor
     *
     * @param string|null      $primary_name_value
     * @param arConnector|null $connector
     *
     * @deprecated
     */
    public function __construct(/*?string*/ $primary_name_value = null, /*?*/ arConnector $connector = null)
    {
        self::config();

        parent::__construct($primary_name_value, $connector);
    }


    /**
     * @param string $name
     *
     * @return mixed
     *
     * @deprecated
     */
    public static function getField(string $name)
    {
        return self::config()->getValue($name);
    }


    /**
     * @return ActiveRecordConfigRepository
     *
     * @deprecated
     */
    protected static function config() : ActiveRecordConfigRepository
    {
        return ActiveRecordConfigRepository::getInstance(static::TABLE_NAME, static::$fields);
    }


    /**
     * Get all values
     *
     * @return array [ [ "name" => value ], ... ]
     *
     * @deprecated
     */
    public static function getFields() : array
    {
        return self::config()->getValues();
    }


    /**
     * Set all values
     *
     * @param array $fields        [ [ "name" => value ], ... ]
     * @param bool  $remove_exists Delete all exists name before
     *
     * @deprecated
     */
    public static function setFields(array $fields, bool $remove_exists = false)/*: void*/
    {
        self::config()->setValues($fields, $remove_exists);
    }


    /**
     * Remove a field
     *
     * @param string $name Name
     *
     * @deprecated
     */
    public static function removeField(string $name)/*: void*/
    {
        self::config()->removeValue($name);
    }


    /**
     * @param string $name
     * @param mixed  $value
     *
     * @deprecated
     */
    public static function setField(string $name, $value)/*: void*/
    {
        self::config()->setValue($name, $value);
    }


    /**
     * @param string $name
     * @param int    $type
     * @param mixed  $default_value
     *
     * @return mixed
     *
     * @throws ActiveRecordConfigException
     *
     * @deprecated
     */
    protected static final function getDefaultValue(string $name, int $type, $default_value)
    {
        throw new ActiveRecordConfigException("getDefaultValue is not supported anymore - please try to use the second parameter in the fields array instead!",
            ActiveRecordConfigException::CODE_INVALID_FIELD);
    }
}

/**
 * Class ActiveRecordConfigRepository
 *
 * @package    srag\ActiveRecordConfig\DhbwTraining
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Do not use - only used for be compatible with old version
 */
final class ActiveRecordConfigRepository extends AbstractRepository
{

    /**
     * @var self
     *
     * @deprecated
     */
    protected static $instance = null;
    /**
     * @var string
     *
     * @deprecated
     */
    protected $table_name;
    /**
     * @var array
     *
     * @deprecated
     */
    protected $fields;


    /**
     * ActiveRecordConfigRepository constructor
     *
     * @param string $table_name
     * @param array  $fields
     *
     * @deprecated
     */
    protected function __construct(string $table_name, array $fields)
    {
        $this->table_name = $table_name;
        $this->fields = $fields;

        parent::__construct();
    }


    /**
     * @param string $table_name
     * @param array  $fields
     *
     * @return self
     *
     * @deprecated
     */
    public static function getInstance(string $table_name, array $fields) : self
    {
        if (self::$instance === null) {
            self::$instance = new self($table_name, $fields);
        }

        return self::$instance;
    }


    /**
     * @inheritDoc
     *
     * @return ActiveRecordConfigFactory
     *
     * @deprecated
     */
    public function factory() : AbstractFactory
    {
        return ActiveRecordConfigFactory::getInstance();
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    protected function getTableName() : string
    {
        return $this->table_name;
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    protected function getFields() : array
    {
        return $this->fields;
    }
}

/**
 * Class ActiveRecordConfigFactory
 *
 * @package    srag\ActiveRecordConfig\DhbwTraining
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Do not use - only used for be compatible with old version
 */
final class ActiveRecordConfigFactory extends AbstractFactory
{

    /**
     * @var self
     *
     * @deprecated
     */
    protected static $instance = null;


    /**
     * ActiveRecordConfigFactory constructor
     *
     * @deprecated
     */
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @return self
     *
     * @deprecated
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
