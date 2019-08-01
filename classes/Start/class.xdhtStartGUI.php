<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingAccess.php');
require_once('./Modules/TestQuestionPool/classes/class.assQuestionGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Traits/trait.xdhtDIC.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Recommender/RecommenderCurl.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Recommender/RecommenderResponse.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/QuestionAnswer/QuestionAnswers.php');

/**
 * Class xdhtStartGUI
 *
 * @ilCtrl_Calls      xdhtStartGUI: ilAssQuestionPageGUI
 *
 * @author            : Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtStartGUI {

	use xdhtDIC;
	const CMD_STANDARD = "index";
	const CMD_START = "start";
	const CMD_ANSWER = 'answer';
	const CMD_PROCEED = 'proceed';
	const CMD_SENDRATING = 'sendRating';
	const CMD_NEW_START = 'newStart';
	const CMD_CANCEL = 'cancel';
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
		//$this->questions = $this->facade->xdhtQuestionFactory()->getAllQuestions();
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
			->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()
				->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM);

		self::dic()->ui()->mainTemplate()->addCss("./Services/COPage/css/content.css");

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
			case self::CMD_ANSWER:
			case self::CMD_SENDRATING:
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
		$start_training_link = $this->ctrl()->getLinkTarget($this, self::CMD_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption("Zum Training", false);
		$ilLinkButton->setUrl($start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic()->toolbar()->addButtonInstance($ilLinkButton);
		$this->tpl()->show();
	}


	/**
	 * @param array $question
	 *
	 * @throws ilTemplateException
	 */
	protected function initQuestionForm($question) {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->ctrl()->getLinkTarget($this, self::CMD_ANSWER));

		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);

		if (!is_object($q_gui)) {
			ilUtil::sendFailure("Es ist ein Fehler aufgetreten - Frage wurde nicht gefunden Fragen ID" . $question['question_id'], true);
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		}

		$previewSession = new ilAssQuestionPreviewSession($this->user()->getId(), $question['question_id']);


		$previewSession->init();
		$q_gui->setPreviewSession($previewSession);

		/**
		 * Shuffle!
		 */
		require_once 'Services/Randomization/classes/class.ilArrayElementShuffler.php';
		$shuffler = new ilArrayElementShuffler();
		$shuffler->setSeed($q_gui->object->getId() + $this->user()->getId());
		$q_gui->object->setShuffle(1);
		$q_gui->object->setShuffler($shuffler);

		$q_gui->setPreviousSolutionPrefilled(true);
		$tpl->setCurrentBlock('question');
		$tpl->setVariable('TITLE', $q_gui->object->getTitle());
		$tpl->setVariable('QUESTION', $q_gui->getPreview());
		$tpl->parseCurrentBlock();
		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', $this->pl()->txt('cancel'));
		$tpl->setVariable('NEXT_BTN_VALUE', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', $this->pl()->txt('next_question'));
		$tpl->setVariable('QUESTION_ID', $question['question_id']);
		$tpl->setVariable('RECOMANDER_ID', $question['recomander_id']);






		$this->tpl()->setContent($tpl->get());
		$this->tpl()->show();
	}


	/**
	 * @param array $question
	 * @param RecommenderResponse $response
	 *
	 * @throws ilTemplateException
	 */
	protected function initAnsweredQuestionForm($question, $response) {
		$tpl = new ilTemplate('tpl.questions_answered_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->ctrl()->getLinkTarget($this, self::CMD_SENDRATING));
		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);

		if (!is_object($q_gui)) {
			ilUtil::sendFailure("Es ist ein Fehler aufgetreten - Frage wurde nicht gefunden Fragen ID" . $question['question_id']
				. print_r($response, true), true);
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		}
		$previewSession = new ilAssQuestionPreviewSession($this->user()->getId(), $question['question_id']);
		$q_gui->setPreviewSession($previewSession);

		if (!is_object($q_gui)) {
			ilUtil::sendFailure("Es ist ein Fehler aufgetreten - Frage wurde nicht gefunden Fragen ID" . $question['question_id']
				. print_r($response, true), true);
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		}

		/**
		 * shuffle like before
		 */
		require_once 'Services/Randomization/classes/class.ilArrayElementShuffler.php';
		$shuffler = new ilArrayElementShuffler();
		$shuffler->setSeed($q_gui->object->getId() + $this->user()->getId());
		$q_gui->object->setShuffle(1);
		$q_gui->object->setShuffler($shuffler);

		$tpl->setCurrentBlock('question');
		$tpl->setVariable('TITLE', $q_gui->object->getTitle());
		$tpl->setVariable('QUESTION', $q_gui->getPreview());

		$tpl->parseCurrentBlock();


		$tpl->setVariable('DIFFICULTY', $this->pl()->txt('difficulty'));
		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', $this->pl()->txt('cancel'));
		$tpl->setVariable('BTN_QST_LEVEL1_VALUE', '1');
		$tpl->setVariable('BTN_QST_LEVEL1_TEXT', $this->pl()->txt('level1'));
		$tpl->setVariable('BTN_QST_LEVEL2_VALUE', '2');
		$tpl->setVariable('BTN_QST_LEVEL2_TEXT', $this->pl()->txt('level2'));	$tpl->setVariable('BTN_QST_LEVEL3_VALUE', '3');
		$tpl->setVariable('BTN_QST_LEVEL3_TEXT', $this->pl()->txt('level3'));	$tpl->setVariable('BTN_QST_LEVEL4_VALUE', '4');
		$tpl->setVariable('BTN_QST_LEVEL4_TEXT', $this->pl()->txt('level4'));


		$tpl->setVariable('QUESTION_ID', $question['question_id']);
		$tpl->setVariable('RECOMANDER_ID', $question['recomander_id']);


		$this->tpl()->setContent($tpl->get());
		$this->tpl()->show();
	}


	/**
	 * @param integer $question
	 */
	protected function initSeparatorForm() {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->ctrl()->getLinkTarget($this, self::CMD_PROCEED));
		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', $this->pl()->txt('cancel'));
		$tpl->setVariable('NEXT_BTN_VALUE', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', $this->pl()->txt('next_question'));
		$this->tpl()->setContent($tpl->get());
		$this->tpl()->show();
	}




	public function start() {
		//Remove the Session
		$_SESSION['answered_questions'] = array();
		$recommender = new RecommenderCurl();
		$response = $recommender->start($this->facade->settings());
		$this->proceedWithReturnOfRecommender($response);
	}


	/**
	 * @param array $question
	 */
	public function showQuestion($question) {

		$this->initQuestionForm($question);
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
			->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()
				->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);
	}


	protected function createLogEntry($question_id, $answer_id) {
		$this->dic()->logger()->root()->info("user_id: " . $this->user()->getId() . " question_id: " . $question_id . " answer_id: " . $answer_id);
	}


	public function answer() {
		if ($_POST['submitted'] == 'cancel') {
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		} else {
			$question = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($_POST['recomander_id']);

			$question_answers = new QuestionAnswers($question['type_tag'], $question['question_id']);
			$answertext = array();
			$this->setAnsweredForPreviewSession($question);

			switch ($question['type_tag']) {
				case 'assSingleChoice':
					/**
					 * @var QuestionAnswer $question_answer
					 */
					$question_answer = $question_answers->getAnswers()[$_POST['multiple_choice_result' . $_POST['question_id'] . 'ID']];
					if (is_object($question_answer)) {
						$answertext = [ "answertext" => base64_encode($question_answer->getAnswertext()) ];
					} else {
						$answertext = [ "answertext" => "" ];
					}
					break;
				case 'assMultipleChoice':
					foreach ($_POST as $key => $value) {
						if (strpos($key, 'multiple_choice_result') !== false) {
							$question_answer = $question_answers->getAnswers()[$value];
							if (is_object($question_answer)) {
								$answertext[] = [ "aorder" => base64_encode($question_answer->getAnswertext()) ];
							} else {
								$answertext = [ "answertext" => "" ];
							}
						}
					}
					break;
				case 'assClozeTest':
					foreach ($_POST as $key => $value) {

						if (strpos($key, 'gap_') !== false) {
							$arr_splitted_gap = explode('gap_', $key);
							$question_answer = $question_answers->getAnswers();
							if (in_array($question_answer[$arr_splitted_gap[1]]['cloze_type'], [
								xdhtQuestionFactory::CLOZE_TYPE_TEXT,
								xdhtQuestionFactory::CLOZE_TYPE_NUMERIC
							])) {
								$answertext[] = [ "gap_id" => $arr_splitted_gap[1], 'cloze_type' => 2, 'answertext' => base64_encode($value) ];
							} else {
								if (is_object($question_answer[$arr_splitted_gap[1]][$value])) {
									$answertext[] = [
										"gap_id" => $arr_splitted_gap[1],
										'cloze_type' => $question_answer[$arr_splitted_gap[1]]['cloze_type'],
										'answertext' => base64_encode($question_answer[$arr_splitted_gap[1]][$value]->getAnswertext())
									];
								} else {
									$answertext[] = [
										"gap_id" => $arr_splitted_gap[1],
										'cloze_type' => $question_answer[$arr_splitted_gap[1]]['cloze_type'],
										'answertext' => ""
									];
								}
							}
						}
					}
					break;
			}

			//if(count($answertext) == 0) {
			//	$this->initQuestionForm($this->questions[$question['question_id']],NULL);
			//} else {
			$recommender = new RecommenderCurl();
			$response = $recommender->answer($_POST['recomander_id'], $question['question_type_fi'], $answertext, $this->facade->settings());

			$this->proceedWithReturnOfRecommender($response);
			//$this->debug();
			//}

		}
	}

	public function sendRating() {
		if ($_POST['submitted'] == 'cancel') {
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		} else {
			$recommender = new RecommenderCurl();
			$response = $recommender->sendRating($_POST['recomander_id'], $_POST['submitted'], $this->facade->settings());
			$this->proceedWithReturnOfRecommender($response);
		}
	}

	public function proceed() {
		$question = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($_POST['next_question_recomander_id']);
		$this->showQuestion($question);
	}


	/**
	 * @param array $question
	 */
	public function setAnsweredForPreviewSession($question) {

		$previewSession = new ilAssQuestionPreviewSession($this->user()->getId(), $question['question_id']);

		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);
		assQuestion::_includeClass($q_gui->getQuestionType(), 1);
		$question_type_gui = assQuestion::getGuiClassNameByQuestionType($q_gui->getQuestionType());

		$ass_question = new $question_type_gui($question['question_id']);
		$ass_question->object->persistPreviewState($previewSession);
	}


	public function debug() {

		$recommender = new RecommenderCurl();
		$response = $recommender->start($this->facade->settings());

		$this->initQuestionForm($this->questions[3]);
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
			->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()
				->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);
	}


	/**
	 * @param RecommenderResponse $response
	 */
	public function proceedWithReturnOfRecommender($response) {

		if (is_null($response)) {
			ilUtil::sendFailure("Das Recommender System konnte nicht erreicht werden", true);
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		}

		$send_info = array();
		switch ($response->getStatus()) {
			case RecommenderResponse::STATUS_SUCCESS:
				if ($response->getAnswerResponse()) {
					$formatter = new ilAssSelfAssessmentQuestionFormatter();
					$send_info[] = $formatter->format($response->getAnswerResponse());
				}

				if ($response->getMessage()) {
					$send_info[] = $response->getMessage();
				}

				if (count($send_info) > 0) {
					ilUtil::sendInfo(implode("<br><br>", $send_info), true);
				}

				if ($response->getAnswerResponse()) {
					//DEBUG
					//$_POST['recomander_id'] = "8585466cf11124c99c59ae1deb6ae0d0521d6db5";
					//
					$question = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($_POST['recomander_id']);
					$this->initAnsweredQuestionForm($question, $response);
					$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
						->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()
							->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);

					break;
				}

				switch ($response->getResponseType()) {
					case RecommenderResponse::RESPONSE_TYPE_NEXT_QUESTION:
						$question = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($response->getRecomanderId());
						$this->showQuestion($question);
						break;
					case RecommenderResponse::RESPONSE_TYPE_IN_PROGRESS:
						$this->initSeparatorForm();
						break;
					case RecommenderResponse::RESPONSE_TYPE_PAGE:
						$this->initSeparatorForm();
						break;
					case RecommenderResponse::RESPONSE_TYPE_TEST_IS_FINISHED:
						$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()->getId(), $this->facade->objectId()),
							ilLPStatus::LP_STATUS_COMPLETED_NUM);
						$this->ctrl()->redirect($this, self::CMD_STANDARD);
						break;
					default:
						$this->initSeparatorForm();
						break;
				}
				break;

			case RecommenderResponse::STATUS_ERROR:
				ilUtil::sendFailure("Das Recommender System hat einen Fehler ausgegeben" . $response->getMessage(), true);
				$this->ctrl()->redirect($this, self::CMD_STANDARD);
				break;
		}
	}


	//TODO MST added temporary function
	public function newStart() {
		//Remove the Session
		$_SESSION['answered_questions'] = array();

		$this->start();
	}
}