<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingAccess.php');
require_once('./Modules/TestQuestionPool/classes/class.assQuestionGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Traits/trait.xdhtDIC.php');

/**
 * Class xdhtStartGUI
 *
 * @ilCtrl_Calls      xdhtStartGUI: ilAssQuestionPageGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtStartGUI {
	use xdhtDIC;

	const CMD_STANDARD = "index";
	const CMD_START= "start";
	const CMD_PROCEED = 'proceed';
	const CMD_NEW_START = 'newStart';
	const CMD_CANCEL = 'cancel';
	//const QUESTION_IDENTIFIER = 'question_id';

	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;
	/**
	 * @var array of questions
	 */
	protected $questions;


	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
		$this->questions = $this->facade->xdhtQuestionFactory()->getAllQuestionsByQuestionPoolId($this->facade->settings()->getQuestionPoolId());
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM);
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
			case self::CMD_START:
			case self::CMD_PROCEED:
			case self::CMD_NEW_START:
				if ($this->access()->hasReadAccess()) {
					$this->{$cmd}();
					break;
				} else {
					ilUtil::sendFailure(ilAssistedExercisePlugin::getInstance()->txt('permission_denied'), true);
					break;
				}
		}
	}

	public function index() {
		ilUtil::sendInfo($this->pl()->txt('info_start_training'));
		$start_training_link = $this->ctrl()->getLinkTarget($this, self::CMD_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl()->txt("start_training"), false);
		$ilLinkButton->setUrl($start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic()->toolbar()->addButtonInstance($ilLinkButton);

		$continue_training_link = $this->ctrl()->getLinkTarget($this, self::CMD_PROCEED);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl()->txt("continue_training"), false);
		$ilLinkButton->setUrl($continue_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic()->toolbar()->addButtonInstance($ilLinkButton);

		$new_start_training_link = $this->ctrl()->getLinkTarget($this, self::CMD_NEW_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl()->txt("start_new_training"), false);
		$ilLinkButton->setUrl($new_start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic()->toolbar()->addButtonInstance($ilLinkButton);

		$this->tpl()->show();
	}


	/**
	 * @param integer $question
	 */
	protected function initQuestionForm($question) {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->ctrl()->getLinkTarget($this, self::CMD_PROCEED));
		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);
		// $q_gui->setRenderPurpose(assQuestionGUI::RENDER_PURPOSE_PLAYBACK);
		//$a_html = $q_gui->getPreview();

		$tpl->setCurrentBlock('question');
		$tpl->setVariable('QUESTION', $q_gui->getPreview());
		$tpl->parseCurrentBlock();

		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', $this->pl()->txt('cancel'));

		$tpl->setVariable('NEXT_BTN_VALUE', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', $this->pl()->txt('next_question'));

		$tpl->setVariable('QUESTION_ID', $question['question_id']);

		//$this->ctrl()->setParameter($this, self::QUESTION_IDENTIFIER, $question['question_id']);

		$this->tpl()->setContent( $tpl->get());

		$this->tpl()->show();
	}

	public function start() {
		$this->questions = $this->facade->xdhtQuestionFactory()->getAllQuestionsByQuestionPoolId($this->facade->settings()->getQuestionPoolId());
		$this->initQuestionForm($this->questions[0]);
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);
	}

	protected function createLogEntry($question_id, $answer_id) {
		$this->dic()->logger()->root()->info("user_id: ". $this->user()->getId() . " question_id: " . $question_id . " answer_id: " . $answer_id);
	}

	public function proceed() {
		if($_POST['submitted'] == 'cancel') {
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		} else {
			if(!array_key_exists('answered_questions', $_SESSION) || !in_array($_POST['question_id'], $_SESSION['answered_questions'])) {
				$_SESSION['answered_questions'][] = $_POST['question_id'];
			}
			//TODO replace hard coded second argument
			$this->createLogEntry($_POST['question_id'], 1);
			$question_ids = $this->facade->xdhtQuestionFactory()->getQuestionIds($this->questions);
			$session_question_ids = [];
			foreach($_SESSION['answered_questions'] as $answered_question) {
				$session_question_ids[] = $answered_question;
			}
			$not_answered_questions_ids = array_diff($question_ids, $session_question_ids);

			$not_answered_questions = $this->facade->xdhtQuestionFactory()->getNotAnsweredQuestionsByIds($not_answered_questions_ids, $this->facade->settings()->getQuestionPoolId());

			if(!empty($not_answered_questions)) {
				$this->initQuestionForm($not_answered_questions[0]);
			} else {
				$this->ctrl()->redirect($this, self::CMD_STANDARD);
			}
		}
	}

}