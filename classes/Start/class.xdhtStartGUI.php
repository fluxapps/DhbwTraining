<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingAccess.php');

/**
 * Class xdhtStartGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtStartGUI {

	const CMD_STANDARD = "index";
	const CMD_START= "start";
	const CMD_CONTINUE = 'continue';
	const CMD_NEW_START = 'newStart';

	/**
	 * @var ilObjDhbwTraining
	 */
	public $dhbw_training;
	/**
	 * @var \ILIAS\DI\Container
	 */
	protected $dic;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilDhbwTrainingPlugin
	 */
	protected $pl;
	/**
	 * @var ilObjDhbwTrainingAccess
	 */
	protected $access;


	public function __construct(ilObjDhbwTraining $dhbw_training) {
		global $DIC;
		$this->dic = $DIC;
		$this->tpl = $this->dic['tpl'];
		$this->tabs = $DIC->tabs();
		$this->ctrl = $this->dic->ctrl();
		$this->access = new ilObjDhbwTrainingAccess();
		$this->pl = ilDhbwTrainingPlugin::getInstance();
		$this->dhbw_training = $dhbw_training;
		//parent::__construct();
	}

	public function executeCommand() {
		$nextClass = $this->ctrl->getNextClass();
		switch ($nextClass) {
			default:
				$this->performCommand();
		}
	}


	protected function performCommand() {
		$cmd = $this->ctrl->getCmd(self::CMD_STANDARD);
		switch ($cmd) {
			case self::CMD_STANDARD:
			case self::CMD_CONTINUE:
			case self::CMD_NEW_START:
				if ($this->access->hasReadAccess()) {
					$this->{$cmd}();
					break;
				} else {
					ilUtil::sendFailure(ilAssistedExercisePlugin::getInstance()->txt('permission_denied'), true);
					break;
				}
		}
	}

	public function index() {
		ilUtil::sendInfo($this->pl->txt('info_start_training'));
		$start_training_link = $this->ctrl->getLinkTarget($this, self::CMD_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl->txt("start_training"), false);
		$ilLinkButton->setUrl($start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic->toolbar()->addButtonInstance($ilLinkButton);

		$continue_training_link = $this->ctrl->getLinkTarget($this, self::CMD_CONTINUE);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl->txt("continue_training"), false);
		$ilLinkButton->setUrl($continue_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic->toolbar()->addButtonInstance($ilLinkButton);

		$new_start_training_link = $this->ctrl->getLinkTarget($this, self::CMD_NEW_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl->txt("start_new_training"), false);
		$ilLinkButton->setUrl($new_start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic->toolbar()->addButtonInstance($ilLinkButton);

		$this->tpl->show();
	}

}