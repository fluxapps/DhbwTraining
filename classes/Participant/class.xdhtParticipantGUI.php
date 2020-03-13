<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtParticipantsGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtParticipantGUI {

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;

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

		$nextClass = self::dic()->ctrl()->getNextClass();
		switch ($nextClass) {
			default:
				$this->performCommand();
		}
	}


	protected function performCommand() {
		$cmd = self::dic()->ctrl()->getCmd(self::CMD_STANDARD);
		switch ($cmd) {
			case self::CMD_STANDARD:
			case self::CMD_APPLY_FILTER:
			case self::CMD_RESET_FILTER:
				if (ilObjDhbwTrainingAccess::hasReadAccess()) {
					$this->{$cmd}();
					break;
				} else {
					ilUtil::sendFailure(ilDhbwTrainingPlugin::getInstance()->txt('permission_denied'), true);
					break;
				}
		}
	}


	public function index() {
		self::dic()->ctrl()->saveParameterByClass(xdhtParticipantTableGUI::class, self::PARTICIPANT_IDENTIFIER);
		$xdhtParticipantTableGUI = new xdhtParticipantTableGUI($this, self::CMD_STANDARD, $this->facade);
		self::dic()->ui()->mainTemplate()->setContent($xdhtParticipantTableGUI->getHTML());
		self::dic()->ui()->mainTemplate()->show();
	}


	protected function applyFilter() {
		$xdhtParticipantTableGUI = new xdhtParticipantTableGUI($this, self::CMD_APPLY_FILTER, $this->facade);
		$xdhtParticipantTableGUI->writeFilterToSession();
		self::dic()->ctrl()->redirect($this, self::CMD_STANDARD);
	}


	protected function resetFilter() {
		$xdhtParticipantTableGUI = new xdhtParticipantTableGUI($this, self::CMD_RESET_FILTER, $this->facade);
		$xdhtParticipantTableGUI->resetFilter();
		$xdhtParticipantTableGUI->resetOffset();
		self::dic()->ctrl()->redirect($this, self::CMD_STANDARD);
	}

}