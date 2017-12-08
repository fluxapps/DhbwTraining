<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingAccess.php');
require_once('./Modules/TestQuestionPool/classes/class.assQuestionGUI.php');

/**
 * Class xdhtStartGUI
 *
 * @ilCtrl_Calls      xdhtStartGUI: ilAssQuestionPageGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtStartGUI {

	const CMD_STANDARD = "index";
	const CMD_START= "start";
	const CMD_CONTINUE = 'continue';
	const CMD_NEW_START = 'newStart';

	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;


	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
	}

	public function executeCommand() {
		$nextClass = $this->facade->ctrl()->getNextClass();
		switch ($nextClass) {
			default:
				$this->performCommand();
		}
	}


	protected function performCommand() {
		$cmd = $this->facade->ctrl()->getCmd(self::CMD_STANDARD);
		switch ($cmd) {
			case self::CMD_STANDARD:
			case self::CMD_START:
			case self::CMD_CONTINUE:
			case self::CMD_NEW_START:
				if ($this->facade->access()->hasReadAccess()) {
					$this->{$cmd}();
					break;
				} else {
					ilUtil::sendFailure(ilAssistedExercisePlugin::getInstance()->txt('permission_denied'), true);
					break;
				}
		}
	}

	public function index() {
		ilUtil::sendInfo($this->facade->pl()->txt('info_start_training'));
		$start_training_link = $this->facade->ctrl()->getLinkTarget($this, self::CMD_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->facade->pl()->txt("start_training"), false);
		$ilLinkButton->setUrl($start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->facade->dic()->toolbar()->addButtonInstance($ilLinkButton);

		$continue_training_link = $this->facade->ctrl()->getLinkTarget($this, self::CMD_CONTINUE);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->facade->pl()->txt("continue_training"), false);
		$ilLinkButton->setUrl($continue_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->facade->dic()->toolbar()->addButtonInstance($ilLinkButton);

		$new_start_training_link = $this->facade->ctrl()->getLinkTarget($this, self::CMD_NEW_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->facade->pl()->txt("start_new_training"), false);
		$ilLinkButton->setUrl($new_start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->facade->dic()->toolbar()->addButtonInstance($ilLinkButton);


		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->facade->ctrl()->getLinkTarget($this, self::CMD_START));
		$q_gui = assQuestionGUI::_getQuestionGUI("", 16);
		// $q_gui->setRenderPurpose(assQuestionGUI::RENDER_PURPOSE_PLAYBACK);
		//$a_html = $q_gui->getPreview();

		$tpl->setCurrentBlock('question');
		$tpl->setVariable('QUESTION', $q_gui->getPreview());
		$tpl->parseCurrentBlock();

		$tpl->setVariable('SUBMIT_BTN_NAME', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', $this->facade->pl()->txt('cancel'));

		$tpl->setVariable('SUBMIT_BTN_NAME', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', $this->facade->pl()->txt('next_question'));

		$this->facade->tpl()->setContent( $tpl->get());

		$this->facade->tpl()->show();
	}


	/**
	 * 1) get all questions from question pool (probably in a new gui class)
	 * 2) click on continue button -> increase the index variable of the question pool array
	 *  a) save log entry
	 *  b) show next question
	 */
	public function start() {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/templates/default/');
	}

}