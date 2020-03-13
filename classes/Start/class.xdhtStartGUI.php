<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtStartGUI
 *
 * @ilCtrl_Calls      xdhtStartGUI: ilAssQuestionPageGUI
 *
 * @author            : Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtStartGUI {

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
	const CMD_STANDARD = "index";
	const CMD_START = "start";
	const CMD_ANSWER = 'answer';
	const CMD_PROCEED = 'proceed';
	const CMD_SENDRATING = 'sendRating';
	const CMD_NEW_START = 'newStart';
	const CMD_CANCEL = 'cancel';
    const TAB_EDIT_PAGE = 'edit_page';
	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;
    /**
     * @var RecommenderResponse
     */
	protected $response;


	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
			->findOrCreateParticipantByUsrAndTrainingObjectId(self::dic()->user()
				->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM);
		$this->response = new RecommenderResponse();

		self::dic()->ui()->mainTemplate()->addCss("./Services/COPage/css/content.css");

	}


	public function executeCommand() {
        self::dic()->tabs()->addSubTab(ilObjDhbwTrainingGUI::TAB_START, self::plugin()->translate('start'), self::dic()->ctrl()->getLinkTarget($this, self::CMD_STANDARD));
        if (self::dic()->access()->checkAccess("write", "", $this->facade->refId())) {
            self::dic()->tabs()
                ->addSubTab(self::TAB_EDIT_PAGE, self::dic()->language()->txt(self::TAB_EDIT_PAGE),
                    self::dic()->ctrl()->getLinkTargetByClass(xdhtPageObjectGUI::class, 'edit'));
        }
		$nextClass = self::dic()->ctrl()->getNextClass();
		switch ($nextClass) {
            case strtolower(xdhtPageObjectGUI::class):
                self::dic()->ctrl()->forwardCommand(new xdhtPageObjectGUI($this->facade));
                break;
			default:
				$this->performCommand();
		}
	}


	protected function performCommand() {
        self::dic()->tabs()->activateSubTab(ilObjDhbwTrainingGUI::TAB_START);
		$cmd = self::dic()->ctrl()->getCmd(self::CMD_STANDARD);
		switch ($cmd) {
			case self::CMD_STANDARD:
			case self::CMD_START:
			case self::CMD_PROCEED:
			case self::CMD_NEW_START:
			case self::CMD_ANSWER:
			case self::CMD_SENDRATING:
				if (ilObjDhbwTrainingAccess::hasReadAccess()) {
					$this->{$cmd}();
					break;
				} else {
                    ilUtil::sendFailure(ilDhbwTrainingPlugin::getInstance()->txt('permission_denied'), true);
                    break;
				}
		}

        $this->response->sendMessages();
        self::dic()->ui()->mainTemplate()->show();
	}


	public function index() {
		$start_training_link = self::dic()->ctrl()->getLinkTarget($this, self::CMD_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption(self::plugin()->translate("start_training"), false);
		$ilLinkButton->setUrl($start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		self::dic()->toolbar()->addButtonInstance($ilLinkButton);
		self::dic()->ui()->mainTemplate()->setContent(self::output()->getHTML(new xdhtPageObjectGUI($this->facade)));
	}


	/**
	 * @param array $question
	 *
	 * @throws ilTemplateException
	 */
	protected function initQuestionForm($question) {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", self::dic()->ctrl()->getLinkTarget($this, self::CMD_ANSWER));

		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);

		if (!is_object($q_gui)) {
            $this->response->addSendError(self::plugin()->translate("error_no_question_id", "", [$question['question_id']]));
			return;
		}

		$previewSession = new ilAssQuestionPreviewSession(self::dic()->user()->getId(), $question['question_id']);


		$previewSession->init();
		$q_gui->setPreviewSession($previewSession);

		/**
		 * Shuffle!
		 */
		$shuffler = new ilArrayElementShuffler();
		$shuffler->setSeed($q_gui->object->getId() + self::dic()->user()->getId());
		$q_gui->object->setShuffle(1);
		$q_gui->object->setShuffler($shuffler);

		$q_gui->setPreviousSolutionPrefilled(true);
		$tpl->setCurrentBlock('question');
		$tpl->setVariable('TITLE', $q_gui->object->getTitle());
		$tpl->setVariable('QUESTION', $q_gui->getPreview());
		$tpl->parseCurrentBlock();
		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', self::plugin()->translate('interrupt'));
		$tpl->setVariable('NEXT_BTN_VALUE', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', self::plugin()->translate('submit_answer'));
		$tpl->setVariable('QUESTION_ID', $question['question_id']);
		$tpl->setVariable('RECOMANDER_ID', $question['recomander_id']);






		self::dic()->ui()->mainTemplate()->setContent($tpl->get());
	}


	/**
	 * @param array $question
	 *
	 * @throws ilTemplateException
	 */
	protected function initAnsweredQuestionForm($question) {
		$tpl = new ilTemplate('tpl.questions_answered_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", self::dic()->ctrl()->getLinkTarget($this, self::CMD_SENDRATING));
		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);

		if (!is_object($q_gui)) {
            $this->response->addSendError(self::plugin()->translate("error_no_question_id", "", [$question['question_id']]));
			return;
		}
		$previewSession = new ilAssQuestionPreviewSession(self::dic()->user()->getId(), $question['question_id']);
		$q_gui->setPreviewSession($previewSession);

		/**
		 * shuffle like before
		 */
		$shuffler = new ilArrayElementShuffler();
		$shuffler->setSeed($q_gui->object->getId() + self::dic()->user()->getId());
		$q_gui->object->setShuffle(1);
		$q_gui->object->setShuffler($shuffler);

		$tpl->setCurrentBlock('question');
		$tpl->setVariable('TITLE', $q_gui->object->getTitle());
		$tpl->setVariable('QUESTION', $q_gui->getPreview());

		$tpl->parseCurrentBlock();


		$tpl->setVariable('DIFFICULTY', self::plugin()->translate('difficulty'));
		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', self::plugin()->translate('interrupt'));
		$tpl->setVariable('BTN_QST_LEVEL1_VALUE', '1');
		$tpl->setVariable('BTN_QST_LEVEL1_TEXT', self::plugin()->translate('level1'));
		$tpl->setVariable('BTN_QST_LEVEL2_VALUE', '2');
		$tpl->setVariable('BTN_QST_LEVEL2_TEXT', self::plugin()->translate('level2'));	$tpl->setVariable('BTN_QST_LEVEL3_VALUE', '3');
		$tpl->setVariable('BTN_QST_LEVEL3_TEXT', self::plugin()->translate('level3'));	$tpl->setVariable('BTN_QST_LEVEL4_VALUE', '4');
		$tpl->setVariable('BTN_QST_LEVEL4_TEXT', self::plugin()->translate('level4'));


		$tpl->setVariable('QUESTION_ID', $question['question_id']);
		$tpl->setVariable('RECOMANDER_ID', $question['recomander_id']);


		self::dic()->ui()->mainTemplate()->setContent($tpl->get());
	}


	/**
	 * @param integer $question
	 */
	protected function initSeparatorForm() {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", self::dic()->ctrl()->getLinkTarget($this, self::CMD_PROCEED));
		$tpl->setVariable('CANCEL_BTN_VALUE', 'cancel');
		$tpl->setVariable('CANCEL_BTN_TEXT', self::plugin()->translate('interrupt'));
		$tpl->setVariable('NEXT_BTN_VALUE', 'next');
		$tpl->setVariable('PROCEED_BTN_TEXT', self::plugin()->translate('submit_answer'));
		self::dic()->ui()->mainTemplate()->setContent($tpl->get());
	}




	public function start() {
		//Remove the Session
		$_SESSION['answered_questions'] = array();
		$recommender = new RecommenderCurl($this->facade, $this->response);
		$recommender->start();
		$this->proceedWithReturnOfRecommender();
	}


	/**
     * @param array $question
	 */
	public function showQuestion($question) {

		$this->initQuestionForm($question);
		$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
			->findOrCreateParticipantByUsrAndTrainingObjectId(self::dic()->user()
				->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);
	}


	protected function createLogEntry($question_id, $answer_id) {
		self::dic()->logger()->root()->info("user_id: " . self::dic()->user()->getId() . " question_id: " . $question_id . " answer_id: " . $answer_id);
	}


	public function answer() {
		if ($_POST['submitted'] == 'cancel') {
			self::dic()->ctrl()->redirect($this, self::CMD_STANDARD);
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

			$recommender = new RecommenderCurl($this->facade, $this->response);
			$recommender->answer($_POST['recomander_id'], $question['question_type_fi'], $answertext);

			$this->proceedWithReturnOfRecommender();

		}
	}

	public function sendRating() {
		if ($_POST['submitted'] == 'cancel') {
			self::dic()->ctrl()->redirect($this, self::CMD_STANDARD);
		} else {
			$recommender = new RecommenderCurl($this->facade, $this->response);
			$recommender->sendRating($_POST['recomander_id'], $_POST['submitted']);
			$this->proceedWithReturnOfRecommender();
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

		$previewSession = new ilAssQuestionPreviewSession(self::dic()->user()->getId(), $question['question_id']);

		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);
		assQuestion::_includeClass($q_gui->getQuestionType(), 1);
		$question_type_gui = assQuestion::getGuiClassNameByQuestionType($q_gui->getQuestionType());

		$ass_question = new $question_type_gui($question['question_id']);
		$ass_question->object->persistPreviewState($previewSession);
	}


	/**
	 *
	 */
	public function proceedWithReturnOfRecommender() {

		switch ($this->response->getStatus()) {
			case RecommenderResponse::STATUS_SUCCESS:
				if ($this->response->getAnswerResponse()) {
					$formatter = new ilAssSelfAssessmentQuestionFormatter();
                    $this->response->addSendInfo($formatter->format($this->response->getAnswerResponse()));
				}

				if ($this->response->getMessage()) {
                    $this->response->addSendInfo($this->response->getMessage());
				}

				if ($this->response->getAnswerResponse()) {
					$question = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($_POST['recomander_id']);
					$this->initAnsweredQuestionForm($question);
					$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()
						->findOrCreateParticipantByUsrAndTrainingObjectId(self::dic()->user()
							->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);

					break;
				}

				switch ($this->response->getResponseType()) {
					case RecommenderResponse::RESPONSE_TYPE_NEXT_QUESTION:
						$question = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($this->response->getRecomanderId());
						$this->showQuestion($question);
						break;
					case RecommenderResponse::RESPONSE_TYPE_IN_PROGRESS:
						$this->initSeparatorForm();
						break;
					case RecommenderResponse::RESPONSE_TYPE_PAGE:
						$this->initSeparatorForm();
						break;
					case RecommenderResponse::RESPONSE_TYPE_TEST_IS_FINISHED:
						$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()->findOrCreateParticipantByUsrAndTrainingObjectId(self::dic()->user()->getId(), $this->facade->objectId()),
							ilLPStatus::LP_STATUS_COMPLETED_NUM);
						$this->response->sendMessages();
						self::dic()->ctrl()->redirect($this, self::CMD_STANDARD);
						break;
					default:
						$this->initSeparatorForm();
						break;
				}
				break;

			case RecommenderResponse::STATUS_ERROR:
			    if($this->facade->settings()->getLog()) {
                $this->response->addSendError(self::plugin()->translate("error_recommender_system", "", [$this->facade->settings()->getLog() ? $this->response->getMessage() : ""]));
                }
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