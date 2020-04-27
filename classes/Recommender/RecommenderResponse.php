<?php

use srag\DIC\DhbwTraining\DICTrait;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;
use srag\Plugins\DhbwTraining\RecommenderSystem\RcSGateway;

/**
 * Class RecommenderResponse
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommenderResponse
{

    use DICTrait;

    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const STATUS_SUCCESS = "success";
    const STATUS_ERROR = "error";
    const RESPONSE_TYPE_PAGE = 1;
    const RESPONSE_TYPE_IN_PROGRESS = 2;
    const RESPONSE_TYPE_NEXT_QUESTION = 3;
    const RESPONSE_TYPE_TEST_IS_FINISHED = 4;
    const RESPONSE_TYPE
        = [
            'MESSAGE_PAGE'         => self::RESPONSE_TYPE_PAGE,
            'QUESTION_IN_PROGRESS' => self::RESPONSE_TYPE_IN_PROGRESS,
            'NEXT_QUESTION'        => self::RESPONSE_TYPE_NEXT_QUESTION,
            'TEST_IS_FINISHED'     => self::RESPONSE_TYPE_TEST_IS_FINISHED
        ];
    const MESSAGE_TYPE_SUCCESS = ilTemplate::MESSAGE_TYPE_SUCCESS;
    const MESSAGE_TYPE_INFO = ilTemplate::MESSAGE_TYPE_INFO;
    const MESSAGE_TYPE_QUESTION = ilTemplate::MESSAGE_TYPE_QUESTION;
    const MESSAGE_TYPE_FAILURE = ilTemplate::MESSAGE_TYPE_FAILURE;
    const LEARNING_PROGRESS_STATUS_NOT_ATTEMPTED = ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM;
    const LEARNING_PROGRESS_STATUS_IN_PROGRESS = ilLPStatus::LP_STATUS_IN_PROGRESS_NUM;
    const LEARNING_PROGRESS_STATUS_COMPLETED = ilLPStatus::LP_STATUS_COMPLETED_NUM;
    const LEARNING_PROGRESS_STATUS_FAILED = ilLPStatus::LP_STATUS_FAILED_NUM;
    /**
     * @var string
     */
    protected $status = "";
    /**
     * @var int
     */
    protected $response_type = 0;
    /**
     * @var string
     */
    protected $recomander_id = "";
    /**
     * @var string
     */
    protected $message = "";
    /**
     * @var string
     */
    protected $message_type = self::MESSAGE_TYPE_INFO;
    /**
     * @var string
     */
    protected $answer_response = "";
    /**
     * @var string
     */
    protected $answer_response_type = self::MESSAGE_TYPE_INFO;
    /**
     * @var float|null
     */
    protected $progress = null;
    /**
     * @var string
     */
    protected $progress_type = self::MESSAGE_TYPE_INFO;
    /**
     * @var int|null
     */
    protected $learning_progress_status = null;
    /**
     * @var array
     */
    protected $competences = [];
    /**
     * @var ProgressMeter[]
     */
    protected $progress_meters = [];
    /**
     * @var array
     */
    protected $send_success = [];
    /**
     * @var array
     */
    protected $send_info = [];
    /**
     * @var array
     */
    protected $send_question = [];
    /**
     * @var array
     */
    protected $send_failure = [];


    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->status;
    }


    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }


    /**
     * @return int
     */
    public function getResponseType() : int
    {
        return $this->response_type;
    }


    /**
     * @param int $response_type
     */
    public function setResponseType(int $response_type)
    {
        $this->response_type = $response_type;
    }


    /**
     * @return string
     */
    public function getRecomanderId() : string
    {
        return $this->recomander_id;
    }


    /**
     * @param string $recomander_id
     */
    public function setRecomanderId(string $recomander_id)
    {
        $this->recomander_id = $recomander_id;
    }


    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }


    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }


    /**
     * @return string
     */
    public function getMessageType() : string
    {
        return $this->message_type;
    }


    /**
     * @param string $message_type
     */
    public function setMessageType(string $message_type)/*:void*/
    {
        $this->message_type = $message_type;
    }


    /**
     * @return string
     */
    public function getAnswerResponse() : string
    {
        return $this->answer_response;
    }


    /**
     * @param string $answer_response
     */
    public function setAnswerResponse(string $answer_response)
    {
        $this->answer_response = $answer_response;
    }


    /**
     * @return ProgressMeter[]
     */
    public function getProgressmeters() : array
    {
        return $this->progress_meters;
    }


    /**
     * @param ProgressMeter[]
     */
    public function setProgressmeters($progress_meters)
    {
        $this->progress_meters = $progress_meters;
    }


    /**
     * @return string
     */
    public function getAnswerResponseType() : string
    {
        return $this->answer_response_type;
    }


    /**
     * @param string $answer_response_type
     */
    public function setAnswerResponseType(string $answer_response_type)/*:void*/
    {
        $this->answer_response_type = $answer_response_type;
    }


    /**
     * @return float|null
     */
    public function getProgress()/* :? float*/
    {
        return $this->progress;
    }


    /**
     * @param float|null $progress
     */
    public function setProgress(/*?*/ float $progress = null)/*:void*/
    {
        $this->progress = $progress;
    }


    /**
     * @return string
     */
    public function getProgressType() : string
    {
        return $this->progress_type;
    }


    /**
     * @param string $progress_type
     */
    public function setProgressType(string $progress_type)/*:void*/
    {
        $this->progress_type = $progress_type;
    }


    /**
     * @return int|null
     */
    public function getLearningProgressStatus()/* :? int*/
    {
        return $this->learning_progress_status;
    }


    /**
     * @param int|null $learning_progress_status
     */
    public function setLearningProgressStatus(/*?*/ int $learning_progress_status = null)/*:void*/
    {
        $this->learning_progress_status = $learning_progress_status;
    }


    /**
     * @return array
     */
    public function getCompetences() : array
    {
        return $this->competences;
    }


    /**
     * @param array $competences
     */
    public function setCompetences(array $competences)/*:void*/
    {
        $this->competences = $competences;
    }


    /**
     * @return array
     */
    public function getSendSuccess() : array
    {
        return $this->send_success;
    }


    /**
     * @param array $send_success
     */
    public function setSendSuccess(array $send_success)/*:void*/
    {
        $this->send_success = $send_success;
    }


    /**
     * @return array
     */
    public function getSendInfo() : array
    {
        return $this->send_info;
    }


    /**
     * @param array $send_info
     */
    public function setSendInfo(array $send_info)/*:void*/
    {
        $this->send_info = $send_info;
    }


    /**
     * @return array
     */
    public function getSendQuestion() : array
    {
        return $this->send_question;
    }


    /**
     * @param array $send_question
     */
    public function setSendQuestion(array $send_question)/*:void*/
    {
        $this->send_question = $send_question;
    }


    /**
     * @return array
     */
    public function getSendFailure() : array
    {
        return $this->send_failure;
    }


    /**
     * @param array $send_failure
     */
    public function setSendFailure(array $send_failure)/*:void*/
    {
        $this->send_failure = $send_failure;
    }


    /**
     * @param string $message
     * @param string $message_type
     */
    public function addSendMessage(string $message, string $message_type = self::MESSAGE_TYPE_INFO)/*:void*/
    {
        switch ($message_type) {
            case self::MESSAGE_TYPE_SUCCESS:
                $this->addSendSuccess($message);
                break;

            case self::MESSAGE_TYPE_QUESTION:
                $this->addSendQuestion($message);
                break;

            case self::MESSAGE_TYPE_FAILURE:
                $this->addSendFailure($message);
                break;

            case self::MESSAGE_TYPE_INFO:
            default:
                $this->addSendInfo($message);
                break;
        }
    }


    /**
     * @param string $send_success
     */
    public function addSendSuccess(string $send_success)/*:void*/
    {
        $this->send_success[] = $send_success;
    }


    /**
     * @param string $send_question
     */
    public function addSendQuestion(string $send_question)/*:void*/
    {
        $this->send_question[] = $send_question;
    }


    /**
     * @param string $send_failure
     */
    public function addSendFailure(string $send_failure)/*:void*/
    {
        $this->send_failure[] = $send_failure;
    }


    /**
     * @param string $send_info
     */
    public function addSendInfo(string $send_info)/*:void*/
    {
        $this->send_info[] = $send_info;
    }


    /**
     *
     */
    public function sendMessages()/*:void*/
    {
        if (!empty($this->send_success)) {
            ilUtil::sendSuccess(implode("<br><br>", $this->send_success), true);
        }

        if (!empty($this->send_info)) {
            ilUtil::sendInfo(implode("<br><br>", $this->send_info), true);
        }

        if (!empty($this->send_question)) {
            ilUtil::sendQuestion(implode("<br><br>", $this->send_question), true);
        }

        if (!empty($this->send_failure)) {
            ilUtil::sendFailure(implode("<br><br>", $this->send_failure), true);
        }
    }


    public function getProgressMetersHtml()
    {
        $progress_meter_html_list = [];
        if(count($this->progress_meters) > 0) {
            foreach($this->progress_meters as $progress_meter) {
                $progress_meter_html_list[] = $this->getProgressMeterHtml($progress_meter);
            }
        }

        // render
        return implode('', $progress_meter_html_list);
    }


    private function getProgressMeterHtml(ProgressMeter $progress_meter)
    {
        //$progress_meter_factory = new ProgressMeterFactory();
        $progress_meter_factory = self::dic()->ui()->factory()->chart()->progressMeter();
        switch ($progress_meter->getProgressmeterType()) {
            case ProgressMeter::PROGRESS_METER_TYPE_MINI:

                $ui_element = $progress_meter_factory->mini(
                    (int) $progress_meter->getMaxReachableScore(),
                    (int) $progress_meter->getPrimaryReachedScore(),
                    (int) $progress_meter->getRequiredScore()
                );
                break;
            default:
                $ui_element = $progress_meter_factory->standard(
                    (int) $progress_meter->getMaxReachableScore(),
                    (int) $progress_meter->getPrimaryReachedScore(),
                    (int) $progress_meter->getRequiredScore(),
                    (int) $progress_meter->getSecondaryReachedScore()
                );
                $ui_element->withMainText($progress_meter->getPrimaryReachedScoreLabel());
                $ui_element->withRequiredText($progress_meter->getRequiredScoreLabel());
                break;
        }

        $progress_meter_id = md5((string) $progress_meter->getTitle());
        $progress_meter_html = "<style>
        #" . $progress_meter_id . " {
            max-width: " . $progress_meter->getMaxWidthInPixel() . "px;
             margin-bottom: 20px;
        }
        </style>";
        $progress_meter_html .= '<div class="il_Block" datatable="0">';
        $progress_meter_html .= '<div class="ilBlockHeader ui-sortable-handle" style="cursor: move;">';
            $progress_meter_html .= '<div><h3 class="ilBlockHeader ui-sortable-handle" style="cursor: move;">' . $progress_meter->getTitle() . '</h3></div>';
        $progress_meter_html .= '</div>';

        $progress_meter_html .= '<div class="ilBlockRow1">';
            $progress_meter_html .= '<div id="' . $progress_meter_id . '">';
                $progress_meter_html .= self::dic()->ui()->renderer()->render($ui_element);
                $progress_meter_html .= '</div>';
            $progress_meter_html .= '</div>';
        $progress_meter_html .= '</div>';

        return $progress_meter_html;
    }


    /**
     * @return string
     */
    public function renderProgressBar() : string
    {
        if ($this->progress === null) {
            return "";
        }

        $progress_bar = ilProgressBar::getInstance();

        $progress_bar->setCurrent($this->progress * 100);

        switch ($this->progress_type) {
            case ilProgressBar::TYPE_SUCCESS:
                $progress_bar->setType(ilProgressBar::TYPE_SUCCESS);
                break;

            case ilProgressBar::TYPE_WARNING:
                $progress_bar->setType(ilProgressBar::TYPE_WARNING);
                break;

            case ilProgressBar::TYPE_DANGER:
                $progress_bar->setType(ilProgressBar::TYPE_DANGER);
                break;

            case ilProgressBar::TYPE_INFO:
            default:
                $progress_bar->setType(ilProgressBar::TYPE_INFO);
                break;
        }

        return self::output()->getHTML($progress_bar);
    }
}