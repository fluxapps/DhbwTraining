<?php

/**
 * Class RecommenderResponse
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class QuestionAnswers {

	/**
	 * @var string
	 */
	protected $question_type;
	/**
	 * @var int
	 */
	protected $questions_id;
	/**
	 * @var QuestionAnswer[]
	 */
	protected $answers;

	/**
	 * QuestionAnswers constructor.
	 *
	 * @param string $question_type
	 */
	public function __construct($question_type, $questions_id) {
		$this->question_type = $question_type;
		$this->questions_id = $questions_id;

		$this->read();
	}


	private function read() {

		switch($this->question_type) {
			case 'assSingleChoice':
				global $DIC;
				$sql = "SELECT * FROM qpl_a_sc where question_fi = $this->questions_id";
				$set = $DIC->database()->query($sql);
				$arr_question_answers = array();
				while ($row = $DIC->database()->fetchAssoc($set)) {
					$question_answer = new QuestionAnswer();
					$question_answer->setQuestionId($row['question_fi']);
					$question_answer->setAnswerId($row['answer_id']);
					$question_answer->setAnswertext($row['answertext']);
					$question_answer->setAOrder($row['aorder']);
					$arr_question_answers[$row['aorder']] = $question_answer;
				}
				$this->setAnswers($arr_question_answers);
				return;
				break;
			case 'assMultipleChoice':
				global $DIC;
				$sql = "SELECT * FROM qpl_a_mc where question_fi = $this->questions_id";
				$set = $DIC->database()->query($sql);
				$arr_question_answers = array();
				while ($row = $DIC->database()->fetchAssoc($set)) {
					$question_answer = new QuestionAnswer();
					$question_answer->setQuestionId($row['question_fi']);
					$question_answer->setAnswerId($row['answer_id']);
					$question_answer->setAnswertext($row['answertext']);
					$question_answer->setAOrder($row['aorder']);
					$arr_question_answers[$row['aorder']] = $question_answer;
				}
				$this->setAnswers($arr_question_answers);
				return;
				break;
			case 'assClozeTest':
				global $DIC;
				$sql = "SELECT * FROM qpl_a_cloze where question_fi = $this->questions_id";

				$set = $DIC->database()->query($sql);
				$arr_question_answers = array();
				while ($row = $DIC->database()->fetchAssoc($set)) {
					$question_answer = new QuestionAnswer();
					$question_answer->setQuestionId($row['question_fi']);
					$question_answer->setAnswerId($row['answer_id']);
					$question_answer->setAnswertext($row['answertext']);
					$question_answer->setAOrder($row['aorder']);
					$question_answer->setClozeType($row['cloze_type']);
					$arr_question_answers[$row['gap_id']]['cloze_type'] = $row['cloze_type'];
					$arr_question_answers[$row['gap_id']][$row['aorder']] = $question_answer;
				}
				$this->setAnswers($arr_question_answers);
				return;
				break;
		}

		$this->setAnswers(array());
	}


	/**
	 * @return string
	 */
	public function getQuestionType(): string {
		return $this->question_type;
	}


	/**
	 * @param string $question_type
	 */
	public function setQuestionType(string $question_type) {
		$this->question_type = $question_type;
	}


	/**
	 * @return int
	 */
	public function getQuestionsId(): int {
		return $this->questions_id;
	}


	/**
	 * @param int $questions_id
	 */
	public function setQuestionsId(int $questions_id) {
		$this->questions_id = $questions_id;
	}


	/**
	 * @return QuestionAnswer[]
	 */
	public function getAnswers(): array {
		return $this->answers;
	}


	/**
	 * @param QuestionAnswer[] $answers
	 */
	public function setAnswers(array $answers) {
		$this->answers = $answers;
	}
}