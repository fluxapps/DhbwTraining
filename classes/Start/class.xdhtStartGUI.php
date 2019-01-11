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
		$ilLinkButton->setCaption("Zum Training", false);
		$ilLinkButton->setUrl($start_training_link);
		/** @var $ilToolbar ilToolbarGUI */
		$this->dic()->toolbar()->addButtonInstance($ilLinkButton);

		/*$continue_training_link = $this->ctrl()->getLinkTarget($this, self::CMD_PROCEED);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl()->txt("continue_training"), false);
		$ilLinkButton->setUrl($continue_training_link);*/
		/** @var $ilToolbar ilToolbarGUI */
		//$this->dic()->toolbar()->addButtonInstance($ilLinkButton);

		/*$new_start_training_link = $this->ctrl()->getLinkTarget($this, self::CMD_NEW_START);
		$ilLinkButton = ilLinkButton::getInstance();
		$ilLinkButton->setCaption($this->pl()->txt("start_new_training"), false);
		$ilLinkButton->setUrl($new_start_training_link);*/
		/** @var $ilToolbar ilToolbarGUI */
		//$this->dic()->toolbar()->addButtonInstance($ilLinkButton);

		$this->tpl()->show();
	}


	/**
	 * @param integer $question
	 */
	protected function initQuestionForm($question,$response) {
		$tpl = new ilTemplate('tpl.questions_form.html', true, true, 'Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining');
		$tpl->setVariable("ACTION", $this->ctrl()->getLinkTarget($this, self::CMD_PROCEED));
		$q_gui = assQuestionGUI::_getQuestionGUI("", $question['question_id']);
		// $q_gui->setRenderPurpose(assQuestionGUI::RENDER_PURPOSE_PLAYBACK);
		//$a_html = $q_gui->getPreview();

		if(!is_object($q_gui)) {
			ilUtil::sendFailure("Es ist ein Fehler aufgetreten - Frage wurde nicht gefunden Fragen ID".$question['question_id'].print_r($response,true),true);
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		}


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


		//$this->ctrl()->setParameter($this, self::QUESTION_IDENTIFIER, $question['question_id']);

		$this->tpl()->setContent( $tpl->get());

		$this->tpl()->show();
	}

	public function start() {
		//Remove the Session
		$_SESSION['answered_questions'] = array();

		$this->questions = $this->facade->xdhtQuestionFactory()->getAllQuestionsByQuestionPoolId($this->facade->settings()->getQuestionPoolId());


		$recommender = new RecommenderCurl();
		$response = $recommender->start($this->facade->settings());

		$this->proceedWithReturnOfRecommender($response);




		/*
		if($response->getStatus() == RecommenderResponse::STATUS_SUCCESS && $response->getQuestionId() > 0) {

			$this->initQuestionForm($this->questions[$response->getQuestionId()]);
			$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);
		} else {echo "sdfsdf";
			print_r($response);exit;
			throw new BadFunctionCallException('Can\'t perform start without question_id');
		}*/
	}

	protected function createLogEntry($question_id, $answer_id) {
		$this->dic()->logger()->root()->info("user_id: ". $this->user()->getId() . " question_id: " . $question_id . " answer_id: " . $answer_id);
	}

	public function proceed() {
		if($_POST['submitted'] == 'cancel') {
			$this->ctrl()->redirect($this, self::CMD_STANDARD);
		} else {

			$question = $this->questions[$_POST['question_id']];
			$question_answers = new QuestionAnswers($question['type_tag'],$_POST['question_id']);

			switch($question['type_tag']) {
				case 'assSingleChoice':
					/**
					 * @var QuestionAnswer $question_answer
					 */
					$question_answer = $question_answers->getAnswers()[$_POST['multiple_choice_result'.$_POST['question_id'].'ID']];
					$answertext = ["answertext" => base64_encode($question_answer->getAnswertext())];
				break;
				case 'assMultipleChoice':
					$answertext = array();
					foreach($_POST as $key => $value) {
						if(strpos($key, 'multiple_choice_result') !== false) {
							$question_answer = $question_answers->getAnswers()[$value];
							$answertext[] = ["aorder" =>  base64_encode($question_answer->getAnswertext())];
						}
					}
					break;
				case 'assClozeTest':
					$answertext = array();
					foreach($_POST as $key => $value) {
						if(strpos($key, 'gap_') !== false) {
							$arr_splitted_gap = explode('gap_',$key);
							$question_answer = $question_answers->getAnswers()[$arr_splitted_gap[1]];

							if($question_answer->getClozeType() == xdhtQuestionFactory::CLOZE_TYPE_TEXT) {
								$answertext[] = ["gap_id" => $arr_splitted_gap[1], 'cloze_type'=> 2, 'answertext' => base64_encode($value)];
							} else {

								$answertext[] = ["gap_id" => $arr_splitted_gap[1], 'cloze_type'=> 2, 'answertext' => base64_encode($question_answer->getAnswertext())];
							}

						}
					}
					break;
			}

			$recommender = new RecommenderCurl();
			$response = $recommender->answer($question['question_id'],$question['question_type_fi'],$answertext,$this->facade->settings());

			$this->proceedWithReturnOfRecommender($response);
		}
	}


	/**
	 * @param RecommenderResponse $response
	 */
	public function proceedWithReturnOfRecommender(RecommenderResponse $response) {
		switch($response->getStatus()) {
			case RecommenderResponse::STATUS_SUCCESS:
				if($response->getAnswerResponse()) {
					ilUtil::sendInfo("Rückmeldung zur Antwort: ".$response->getAnswerResponse(),true);
				}

				if($response->getMessage()) {
					ilUtil::sendInfo($response->getMessage(),true);
				}



				switch($response->getResponseType()) {
					case RecommenderResponse::RESPONSE_TYPE_NEXT_QUESTION:
						$this->initQuestionForm($this->questions[$response->getQuestionId()],$response);
						$this->facade->xdhtParticipantFactory()->updateStatus($this->facade->xdhtParticipantFactory()->findOrCreateParticipantByUsrAndTrainingObjectId($this->user()->getId(), $this->facade->objectId()), ilLPStatus::LP_STATUS_IN_PROGRESS_NUM);
						break;
					case RecommenderResponse::RESPONSE_TYPE_IN_PROGRESS:
						$this->initSeparatorForm();
						break;
					case RecommenderResponse::RESPONSE_TYPE_PAGE:
						$this->initSeparatorForm();
						break;
					case RecommenderResponse::RESPONSE_TYPE_TEST_IS_FINISHED:
						$this->ctrl()->redirect($this, self::CMD_STANDARD);
						break;
					default:
						$this->initSeparatorForm();
						break;
				}
				break;

			case RecommenderResponse::STATUS_ERROR:
				ilUtil::sendFailure("Das Recommender System hat einen Fehler ausgegeben".$response->getMessage(),true);
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