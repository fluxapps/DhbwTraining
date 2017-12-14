<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Participant/class.xdhtParticipantTableGUI.php');

/**
 * Class xdhtParticipantsGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtParticipantGUI {

	use xdhtDIC;

	const PARTICIPANT_IDENTIFIER = 'participant_id';
	const CMD_STANDARD = 'index';
	const CMD_APPLY_FILTER = 'applyFilter';
	const CMD_RESET_FILTER = 'resetFilter';

	/**
	 * @var xdhtParticipant
	 */
	public $xdht_participant;

	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;

	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
	}

	public function executeCommand() {

		$nextClass = $this->ctrl()->getNextClass();
		switch ($nextClass) {
			default:
				$this->performCommand();
		}
	}


	protected function performCommand() {
		$cmd = $this->ctrl()->getCmd(self::CMD_STANDARD);
		switch ($cmd) {
			case self::CMD_STANDARD:
			case self::CMD_APPLY_FILTER:
			case self::CMD_RESET_FILTER:
				if (ilObjDhbwTrainingAccess::hasReadAccess()) {
					$this->{$cmd}();
					break;
				} else {
					ilUtil::sendFailure(ilAssistedExercisePlugin::getInstance()->txt('permission_denied'), true);
					break;
				}
		}
	}


	public function index() {
		$this->ctrl()->saveParameterByClass(xdhtParticipantTableGUI::class, self::PARTICIPANT_IDENTIFIER);
		$xdhtParticipantTableGUI = new xdhtParticipantTableGUI($this, self::CMD_STANDARD, $this->facade);
		$this->tpl()->setContent($xdhtParticipantTableGUI->getHTML());
		$this->tpl()->show();
	}


	protected function applyFilter() {
		$xdhtParticipantTableGUI = new xdhtParticipantTableGUI($this, self::CMD_APPLY_FILTER, $this->facade);
		$xdhtParticipantTableGUI->writeFilterToSession();
		$this->ctrl()->redirect($this, self::CMD_STANDARD);
	}


	protected function resetFilter() {
		$xdhtParticipantTableGUI = new xdhtParticipantTableGUI($this, self::CMD_RESET_FILTER, $this->facade);
		$xdhtParticipantTableGUI->resetFilter();
		$xdhtParticipantTableGUI->resetOffset();
		$this->ctrl()->redirect($this, self::CMD_STANDARD);
	}

}