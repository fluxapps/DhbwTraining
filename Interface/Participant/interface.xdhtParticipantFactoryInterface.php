<?php

/**
 * Class xdhtParticipantFactoryInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtParticipantFactoryInterface {

	/**
	 * @param $usr_id integer
	 *
	 * @return xdhtParticipantInterface
	 */
	public function findOrCreateParticipantByUsrId($usr_id);


	/**
	 * @param xdhtParticipantInterface $xdht_participant
	 * @param integer $new_status
	 *
	 * @return void
	 */
	public function updateStatus($xdht_participant, $new_status);

}