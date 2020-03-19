<?php

/**
 * Class xdhtParticipantFactoryInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtParticipantFactoryInterface
{

    /**
     * @param integer $usr_id
     *
     * @param integer $training_obj_id
     *
     * @return xdhtParticipantInterface
     */
    public function findOrCreateParticipantByUsrAndTrainingObjectId($usr_id, $training_obj_id);


    /**
     * @param xdhtParticipantInterface $xdht_participant
     * @param integer                  $new_status
     *
     * @return void
     */
    public function updateStatus($xdht_participant, $new_status);
}