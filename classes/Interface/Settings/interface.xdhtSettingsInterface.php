<?php
/**
 * Class xdhtSettingsInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtSettingsInterface {

	const TABLE_NAME = 'rep_robj_xdht_settings';
    const RECOMMENDER_SYSTEM_SERVER_EXTERNAL = 1;
    const RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG = 2;

	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @param int $id
	 */
	public function setId($id);

	/**
	 * @return int
	 */
	public function getDhbwTrainingObjectId();

	/**
	 * @param int $dhbw_training_object_id
	 */
	public function setDhbwTrainingObjectId($dhbw_training_object_id);

	/**
	 * @return int
	 */
	public function getQuestionPoolId();

	/**
	 * @param int $question_pool_id
	 */
	public function setQuestionPoolId($question_pool_id);

	/**
	 * @return int
	 */
	public function getIsOnline();

	/**
	 * @param int $is_online
	 */
	public function setIsOnline($is_online);

	/**
	 * @return string
	 */
	public function getInstallationKey();


	/**
	 * @param string $installation_key
	 */
	public function setInstallationKey($installation_key);


	/**
	 * @return string
	 */
	public function getSecret();


	/**
	 * @param string $secret
	 */
	public function setSecret($secret) ;

	/**
	 * @return string
	 */
	public function getUrl();


	/**
	 * @param string $url
	 */
	public function setUrl($url);

	/**
	 * @return int
	 */
	public function getLog();


	/**
	 * @param int $log
	 */
	public function setLog($log);


    /**
     * @return int
     */
    public function getRecommenderSystemServer() : int;


    /**
     * @param int $recommender_system_server
     */
    public function setRecommenderSystemServer(int $recommender_system_server)/*:void*/ ;


    /**
     * @return int
     */
    public function getLearningProgress() : int;


    /**
     * @param int $learning_progress
     */
    public function setLearningProgress(int $learning_progress)/*:void*/;

}