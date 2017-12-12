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
	const CMD_PROCEED = 'proceed';
	const CMD_NEW_START = 'newStart';


	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;


	/**
	 * 1) get all questions for the used question pool
	 * 2) save in hidden input which questions are already answered
	 * 3) show only questions after a click on the next button if the question wasn't already answered
	 */
	protected $questions;


	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
		$this->questions = $this->facade->xdhtQuestionFactory()->getAllQuestionsByQuestionPoolId($this->facade->settings()->getQuestionPoolId());
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
			case self::CMD_PROCEED:
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

		$continue_training_link = $this->facade->ctrl()->getLinkTarget($this, self::CMD_PROCEED);
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

		$this->facade->tpl()->show();
	}


	/**
	 * @param integer $question
	 */
	protected function initQuestionForm($question, $questions_answers = null) {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->facade->ctrl()->getLinkTarget($this, self::CMD_PROCEED));
		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);
		// $q_gui->setRenderPurpose(assQuestionGUI::RENDER_PURPOSE_PLAYBACK);
		//$a_html = $q_gui->getPreview();

		$tpl->setCurrentBlock('question');
		$tpl->setVariable('QUESTION', $q_gui->getPreview());
		$tpl->parseCurrentBlock();

		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', $this->facade->pl()->txt('cancel'));

		$tpl->setVariable('NEXT_BTN_VALUE', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', $this->facade->pl()->txt('next_question'));

		$tpl->setVariable('QUESTION_ID', $question['question_id']);

		if(!empty($questions_answers)) {
			$tpl->setVariable('QUESTIONS_AND_ANSWERS_VALUE', json_encode($questions_answers));
		}

		$this->facade->tpl()->setContent( $tpl->get());

		$this->facade->tpl()->show();
	}

	public function start() {
		$this->initQuestionForm($this->questions[0]);
	}

	protected function createLogEntry($question_id, $answer_id) {
		$this->facade->dic()->logger()->root()->info("user_id: ". $this->facade->user()->getId() . " question_id: " . $question_id . " answer_id: " . $answer_id);
	}

	public function proceed() {
		if($_POST['submitted'] == 'cancel') {
			$this->facade->ctrl()->redirect($this, self::CMD_STANDARD);
		} else {
			$questions_and_answers = $_POST['questions_and_answers'];
			//the last element is the id of the submitted answer of the user
			$lastEl = end($questions_and_answers);
			$this->createLogEntry($lastEl->key(), $lastEl->value());
			$not_answered_questions = array_diff($this->questions, $questions_and_answers);
			if(!empty($not_answered_questions)) {
				$this->initQuestionForm($not_answered_questions[0], json_decode($questions_and_answers));
			}
		}
	}

}