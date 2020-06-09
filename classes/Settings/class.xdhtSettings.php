<?php

use srag\Plugins\DhbwTraining\Config\Config;

/**
 * Class xdhtSettings
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtSettings extends ActiveRecord implements xdhtSettingsInterface
{

    /**
     * @var int
     *
     * @db_has_field        true
     * @db_fieldtype        integer
     * @db_length           8
     * @db_is_primary       true
     * @con_sequence        true
     */
    protected $id;
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     4
     * @db_is_notnull true
     */
    protected $dhbw_training_object_id;
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     4
     */
    protected $question_pool_id;
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     1
     * @db_is_notnull true
     */
    protected $is_online = 0;
    /**
     * @con_has_field        true
     * @con_fieldtype        text
     * @con_length           255
     * @con_is_notnull       true
     * @var string
     */
    protected $installation_key;
    /**
     * @con_has_field        true
     * @con_fieldtype        text
     * @con_length           255
     * @con_is_notnull       true
     * @var string
     */
    protected $secret;
    /**
     * @con_has_field        true
     * @con_fieldtype        text
     * @con_length           255
     * @con_is_notnull       true
     * @var string
     */
    protected $url;
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     1
     * @db_is_notnull true
     */
    protected $log = 0;
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     1
     * @db_is_notnull true
     */
    protected $recommender_system_server = self::RECOMMENDER_SYSTEM_SERVER_EXTERNAL;
    /**
     * @var array
     *
     * @db_has_field  true
     * @db_fieldtype  text
     * @db_is_notnull true
     */
    protected $rec_sys_ser_bui_in_deb_comp = [];
    /**
     * @var array
     *
     * @db_has_field  true
     * @db_fieldtype  text
     * @db_is_notnull true
     */
    protected $rec_sys_ser_bui_in_deb_progm = [];
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     1
     * @db_is_notnull true
     */
    protected $learning_progress = 1;


    /**
     * @return string
     */
    public static function returnDbTableName()
    {
        return self::TABLE_NAME;
    }


    /**
     * @return string
     */
    public function getConnectorContainerName()
    {
        return self::TABLE_NAME;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "rec_sys_ser_bui_in_deb_comp":
            case "rec_sys_ser_bui_in_deb_progm":
                return json_encode($field_value);

            default:
                return null;
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "rec_sys_ser_bui_in_deb_comp":
            case "rec_sys_ser_bui_in_deb_progm":
                return json_decode($field_value, true) ?? [];

            default:
                return null;
        }
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getDhbwTrainingObjectId()
    {
        return $this->dhbw_training_object_id;
    }


    /**
     * @param int $dhbw_training_object_id
     */
    public function setDhbwTrainingObjectId($dhbw_training_object_id)
    {
        $this->dhbw_training_object_id = $dhbw_training_object_id;
    }


    /**
     * @return int
     */
    public function getQuestionPoolId()
    {
        return $this->question_pool_id;
    }


    /**
     * @param int $question_pool_id
     */
    public function setQuestionPoolId($question_pool_id)
    {
        $this->question_pool_id = $question_pool_id;
    }


    /**
     * @return int
     */
    public function getIsOnline()
    {
        return $this->is_online;
    }


    /**
     * @param int $is_online
     */
    public function setIsOnline($is_online)
    {
        $this->is_online = $is_online;
    }


    /**
     * @return string
     */
    public function getInstallationKey()
    {
        return $this->installation_key;
    }


    /**
     * @param string $installation_key
     */
    public function setInstallationKey($installation_key)
    {
        $this->installation_key = $installation_key;
    }


    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }


    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }


    /**
     * @return int
     */
    public function getLog()
    {
        return $this->log;
    }


    /**
     * @param int $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }


    /**
     * @inheritDoc
     */
    public function getRecommenderSystemServer() : int
    {
        if (empty($this->recommender_system_server) || intval(DEVMODE) !== 1) {
            return self::RECOMMENDER_SYSTEM_SERVER_EXTERNAL;
        }

        return $this->recommender_system_server;
    }


    /**
     * @inheritDoc
     */
    public function setRecommenderSystemServer(int $recommender_system_server)/*:void*/
    {
        $this->recommender_system_server = $recommender_system_server;
    }


    /**
     * @inheritDoc
     */
    public function getRecommenderSystemServerBuiltInDebugCompetences() : array
    {
        return $this->rec_sys_ser_bui_in_deb_comp;
    }


    /**
     * @inheritDoc
     */
    public function setRecommenderSystemServerBuiltInDebugCompetences(array $recommender_system_server_built_in_debug_competences)/*:void*/
    {
        $this->rec_sys_ser_bui_in_deb_comp = $recommender_system_server_built_in_debug_competences;
    }


    public function getRecommenderSystemServerBuiltInDebugProgressmeters() : array
    {
        return $this->rec_sys_ser_bui_in_deb_progm;
    }


    public function setRecommenderSystemServerBuiltInDebugProgressmeters(array $recommender_system_server_built_in_debug_progressmeters)
    {
        $this->rec_sys_ser_bui_in_deb_progm = $recommender_system_server_built_in_debug_progressmeters;
    }


    /**
     * @inheritDoc
     */
    public function getLearningProgress() : int
    {
        if (!Config::getField(Config::KEY_LEARNING_PROGRESS)) {
            return false;
        }

        return $this->learning_progress;
    }


    /**
     * @inheritDoc
     */
    public function setLearningProgress(int $learning_progress)/*:void*/
    {
        $this->learning_progress = $learning_progress;
    }
}