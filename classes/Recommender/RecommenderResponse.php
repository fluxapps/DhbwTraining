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

	const RESPONSE_TYPE = [1 => 'MESSAGE_PAGE',
		                   2 => 'QUESTION_IN_PROGRESS',
		                   3 => 'NEXT_QUESTION',
		                   4 => 'TEST_IS_FINISHED'];

	/**
	 * @var string
	 */
	protected $status = "";
	/**
	 * @var int
	 */
	protected $response_type = 0;
	/**
	 * @var int
	 */
	protected $question_id = 0;
	/**
	 * @var string
	 */
	protected $message = "";


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
	public function getQuestionId(): string {
		return $this->question_id;
	}


	/**
	 * @param string $question_id
	 */
	public function setQuestionId(string $question_id) {
		$this->question_id = $question_id;
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


}