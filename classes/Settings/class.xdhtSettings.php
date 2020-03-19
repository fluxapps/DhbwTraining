<?php

use srag\Plugins\DhbwTraining\Config\Config;

/**
 * Class xdhtSettings
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtSettings extends ActiveRecord implements xdhtSettingsInterface {

	/**
	 * @return string
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}

	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}

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
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     1
     * @db_is_notnull true
     */
    protected $learning_progress = 1;






	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return int
	 */
	public function getDhbwTrainingObjectId() {
		return $this->dhbw_training_object_id;
	}


	/**
	 * @param int $dhbw_training_object_id
	 */
	public function setDhbwTrainingObjectId($dhbw_training_object_id) {
		$this->dhbw_training_object_id = $dhbw_training_object_id;
	}


	/**
	 * @return int
	 */
	public function getQuestionPoolId() {
		return $this->question_pool_id;
	}


	/**
	 * @param int $question_pool_id
	 */
	public function setQuestionPoolId($question_pool_id) {
		$this->question_pool_id = $question_pool_id;
	}


	/**
	 * @return int
	 */
	public function getIsOnline() {
		return $this->is_online;
	}


	/**
	 * @param int $is_online
	 */
	public function setIsOnline($is_online) {
		$this->is_online = $is_online;
	}


	/**
	 * @return string
	 */
	public function getInstallationKey() {
		return $this->installation_key;
	}


	/**
	 * @param string $installation_key
	 */
	public function setInstallationKey($installation_key) {
		$this->installation_key = $installation_key;
	}


	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}


	/**
	 * @param string $secret
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
	}


	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}


	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}


	/**
	 * @return int
	 */
	public function getLog() {
		return $this->log;
	}


	/**
	 * @param int $log
	 */
	public function setLog($log) {
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