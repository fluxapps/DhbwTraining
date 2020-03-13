<?php
/**
 * Class RecommenderResponse
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommenderResponse {

	const STATUS_SUCCESS = "success";
	const STATUS_ERROR = "error";

	const RESPONSE_TYPE_PAGE = 1;
	const RESPONSE_TYPE_IN_PROGRESS = 2;
	const RESPONSE_TYPE_NEXT_QUESTION = 3;
	const RESPONSE_TYPE_TEST_IS_FINISHED = 4;

	const RESPONSE_TYPE = ['MESSAGE_PAGE' => self::RESPONSE_TYPE_PAGE ,
		                   'QUESTION_IN_PROGRESS' => self::RESPONSE_TYPE_IN_PROGRESS,
		                   'NEXT_QUESTION' => self::RESPONSE_TYPE_NEXT_QUESTION,
							'TEST_IS_FINISHED' => self::RESPONSE_TYPE_TEST_IS_FINISHED];

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
	protected $answer_response = "";
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
    protected $send_warning = [];
    /**
     * @var array
     */
    protected $send_error = [];




	/**
	 * @return string
	 */
	public function getStatus(): string {
		return $this->status;
	}


	/**
	 * @param string $status
	 */
	public function setStatus(string $status) {
		$this->status = $status;
	}


	/**
	 * @return int
	 */
	public function getResponseType(): int {
		return $this->response_type;
	}


	/**
	 * @param int $response_type
	 */
	public function setResponseType(int $response_type) {
		$this->response_type = $response_type;
	}


	/**
	 * @return string
	 */
	public function getRecomanderId(): string {
		return $this->recomander_id;
	}


	/**
	 * @param string $recomander_id
	 */
	public function setRecomanderId(string $recomander_id) {
		$this->recomander_id = $recomander_id;
	}


	/**
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}


	/**
	 * @param string $message
	 */
	public function setMessage(string $message) {
		$this->message = $message;
	}


	/**
	 * @return string
	 */
	public function getAnswerResponse(): string {
		return $this->answer_response;
	}


	/**
	 * @param string $answer_response
	 */
	public function setAnswerResponse(string $answer_response) {
		$this->answer_response = $answer_response;
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
     * @param string $send_success
     */
    public function addSendSuccess(string $send_success)/*:void*/
    {
        $this->send_success[] = $send_success;
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
     * @param string $send_info
     */
    public function addSendInfo(string $send_info)/*:void*/
    {
        $this->send_info[] = $send_info;
    }


    /**
     * @return array
     */
    public function getSendWarning() : array
    {
        return $this->send_warning;
    }


    /**
     * @param array $send_warning
     */
    public function setSendWarning(array $send_warning)/*:void*/
    {
        $this->send_warning = $send_warning;
    }


    /**
     * @param string $send_warning
     */
    public function addSendWarning(string $send_warning)/*:void*/
    {
        $this->send_warning[] = $send_warning;
    }


    /**
     * @return array
     */
    public function getSendError() : array
    {
        return $this->send_error;
    }


    /**
     * @param array $send_error
     */
    public function setSendError(array $send_error)/*:void*/
    {
        $this->send_error = $send_error;
    }


    /**
     * @param string $send_error
     */
    public function addSendError(string $send_error)/*:void*/
    {
        $this->send_error[] = $send_error;
    }


    /**
     *
     */
    public function sendMessages()/*:void*/
    {
        if (!empty($this->send_success)) {
            ilUtil::sendInfo(implode("<br><br>", $this->send_success), true);
        }

        if (!empty($this->send_info)) {
            ilUtil::sendInfo(implode("<br><br>", $this->send_info), true);
        }

        if (!empty($this->send_warning)) {
            ilUtil::sendQuestion(implode("<br><br>", $this->send_warning), true);
        }

        if (!empty($this->send_error)) {
            ilUtil::sendFailure(implode("<br><br>", $this->send_error), true);
        }
    }

}