<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/Participant/interface.xdhtParticipantFactoryInterface.php');

/**
 * Class xdhtParticipantFactory
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtParticipantFactory implements xdhtParticipantFactoryInterface {

	/**
	 * @inheritdoc
	 */
	public function findOrCreateParticipantByUsrAndTrainingObjectId($usr_id, $training_obj_id) {
		$xdht_participant = xdhtParticipant::where(array('usr_id' => $usr_id, 'training_obj_id' => $training_obj_id))->first();
		if(empty($xdht_participant)) {
			$xdht_participant = new xdhtParticipant();
			$xdht_participant->setTrainingObjId(ilObjectFactory::getInstanceByRefId($_GET['ref_id'])->getId());
			$xdht_participant->setUsrId($usr_id);
			$xdht_participant->create();
		}
		return $xdht_participant;
	}

	public function updateStatus($xdht_participant, $new_status) {
		/*
		 * update the status only if the new status is higher than the first one
		 * this makes sure that the status is not marked down if e.q. the user recalls the training object
		 */
		if($new_status > $xdht_participant->getStatus()) {
			$xdht_participant->setStatus($new_status);
		}
		$xdht_participant->setLastAccess(date('Y-m-d H:i:s'));
		$xdht_participant->update();
	}
}