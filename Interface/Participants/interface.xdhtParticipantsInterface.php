<?php
/**
 * Class xdhtParticipantsInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtParticipantsInterface {

	const TABLE_NAME = 'xdht_participants';


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
	public function getTrainingObjId();


	/**
	 * @param int $training_obj_id
	 */
	public function setTrainingObjId($training_obj_id);


	/**
	 * @return int
	 */
	public function getUsrId();


	/**
	 * @param int $usr_id
	 */
	public function setUsrId($usr_id);


	/**
	 * @return int
	 */
	public function getStatus();


	/**
	 * @param int $status
	 */
	public function setStatus($status);


	/**
	 * @return bool
	 */
	public function hasStatusChanged();


	/**
	 * @return string
	 */
	public function getCreated();


	/**
	 * @param string $created
	 */
	public function setCreated($created);


	/**
	 * @return string
	 */
	public function getUpdated();


	/**
	 * @param string $updated
	 */
	public function setUpdated($updated);


	/**
	 * @return int
	 */
	public function getCreatedUsrId();


	/**
	 * @param int $created_usr_id
	 */
	public function setCreatedUsrId($created_usr_id);


	/**
	 * @return int
	 */
	public function getUpdatedUsrId();


	/**
	 * @param int $updated_usr_id
	 */
	public function setUpdatedUsrId($updated_usr_id);


	/**
	 * @return bool
	 */
	public function isStatusChanged();


	/**
	 * @param bool $status_changed
	 */
	public function setStatusChanged($status_changed);


	/**
	 * @return int
	 */
	public function getOldStatus();

	/**
	 * @param int $old_status
	 */
	public function setOldStatus($old_status);

}