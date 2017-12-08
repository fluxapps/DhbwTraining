<?php
/**
 * Class xdhtSettingsInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtSettingsInterface {

	const TABLE_NAME = 'rep_robj_xdht_settings';

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

}