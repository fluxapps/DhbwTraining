<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtParticipant
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtParticipant extends ActiveRecord implements xdhtParticipantInterface
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     * @db_is_primary   true
     * @db_sequence     true
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
    protected $training_obj_id;
    /**
     * @var int
     *
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     4
     * @db_is_notnull true
     */
    protected $usr_id;
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $status = ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM;
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $created;
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $updated_status;
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $last_access;
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $created_usr_id = 0;
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $updated_usr_id = 0;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       8
     */
    protected $full_name;
    /**
     * @var bool
     */
    protected $status_changed = false;
    /**
     * @var int
     */
    protected $old_status;


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


    public function create()
    {
        global $ilUser;

        $this->created = date('Y-m-d H:i:s');
        $this->updated_status = date('Y-m-d H:i:s');
        $this->created_usr_id = $ilUser->getId();
        $this->updated_usr_id = $ilUser->getId();
        $this->full_name = $ilUser->getFirstname() . " " . $ilUser->getLastName();
        //$this->status = ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM;
        parent::create();
    }


    public function update()
    {
        global $ilUser;

        $this->updated_status = date('Y-m-d H:i:s');
        $this->updated_usr_id = $ilUser->getId();
        parent::update();

        if ($this->hasStatusChanged()) {
            ilLPStatusWrapper::_updateStatus($this->training_obj_id, $this->usr_id);
            ilChangeEvent::_recordReadEvent(ilDhbwTrainingPlugin::PLUGIN_PREFIX, intval(filter_input(INPUT_GET, 'ref_id')), $this->training_obj_id, self::dic()->user()->getId());
        }
    }

    //public function updateLPStatus() {
    /** @var xaliSetting $xaliSetting */
    /*$xaliSetting = xaliSetting::find($this->attendancelist_id);
    if ($this->getReachedPercentage() >= $xaliSetting->getMinimumAttendance()) {
        $this->setStatus(ilLPStatus::LP_STATUS_COMPLETED_NUM);                      //COMPLETED: minimum attendance is reached
    } elseif ((time()-(60*60*24)) > strtotime($xaliSetting->getActivationTo())) {
        $this->setStatus(ilLPStatus::LP_STATUS_FAILED_NUM);                         //FAILED: minimum attendance not reached and time is up
    } else {
        $this->setStatus(ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);                    //IN PROGR: minimum attendance not reached, time not yet up
    }
}*/

    /**
     * @param $training_obj_id
     */
    /*	public static function updateParticipantsStatuses($training_obj_id) {
            foreach (self::getAllParticipantsByObjId($training_obj_id) as $participant_id) {
                $participant = self::getInstance($participant_id, $training_obj_id);
                $participant->updateLPStatus();
                $participant->store();
            }
        }*/

    /*	public static function getAllParticipantsByObjId($training_obj_id) {
            return xdhtParticipant::where(array('training_obj_id' => $training_obj_id))->get();
        }*/

    /**
     * @param $participant_id
     * @param $training_obj_id
     *
     * @return ActiveRecord|xdhtParticipant
     */
    /*	public static function getInstance($participant_id, $training_obj_id) {
            $xdhtParticipant = xdhtParticipant::where(array('participant_id' => $participant_id, 'training_obj_id' => $training_obj_id))->first();
            if (!$xdhtParticipant) {
                $xdhtParticipant = new self();
                $xdhtParticipant->setUsrId($participant_id);
                $xdhtParticipant->setTrainingObjId($training_obj_id);
            }
            return $xdhtParticipant;
        }*/


    /**
     * @return bool
     */
    public function hasStatusChanged()
    {
        return $this->status_changed;
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
    public function getTrainingObjId()
    {
        return $this->training_obj_id;
    }


    /**
     * @param int $training_obj_id
     */
    public function setTrainingObjId($training_obj_id)
    {
        $this->training_obj_id = $training_obj_id;
    }


    /**
     * @return int
     */
    public function getUsrId()
    {
        return $this->usr_id;
    }


    /**
     * @param int $usr_id
     */
    public function setUsrId($usr_id)
    {
        $this->usr_id = $usr_id;
    }


    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        if ($status != $this->status) {
            $this->old_status = $this->status;
            $this->status_changed = true;
        }
        $this->status = $status;
    }


    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }


    /**
     * @return string
     */
    public function getUpdatedStatus()
    {
        return $this->updated_status;
    }


    /**
     * @param string $updated_status
     */
    public function setUpdatedStatus($updated_status)
    {
        $this->updated_status = $updated_status;
    }


    /**
     * @return string
     */
    public function getLastAccess()
    {
        return $this->last_access;
    }


    /**
     * @param string $last_access
     */
    public function setLastAccess($last_access)
    {
        $this->last_access = $last_access;
    }


    /**
     * @return int
     */
    public function getCreatedUsrId()
    {
        return $this->created_usr_id;
    }


    /**
     * @param int $created_usr_id
     */
    public function setCreatedUsrId($created_usr_id)
    {
        $this->created_usr_id = $created_usr_id;
    }


    /**
     * @return int
     */
    public function getUpdatedUsrId()
    {
        return $this->updated_usr_id;
    }


    /**
     * @param int $updated_usr_id
     */
    public function setUpdatedUsrId($updated_usr_id)
    {
        $this->updated_usr_id = $updated_usr_id;
    }


    /**
     * @return bool
     */
    public function isStatusChanged()
    {
        return $this->status_changed;
    }


    /**
     * @param bool $status_changed
     */
    public function setStatusChanged($status_changed)
    {
        $this->status_changed = $status_changed;
    }


    /**
     * @return int
     */
    public function getOldStatus()
    {
        return $this->old_status;
    }


    /**
     * @param int $old_status
     */
    public function setOldStatus($old_status)
    {
        $this->old_status = $old_status;
    }


    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }


    /**
     * @param string $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }
}