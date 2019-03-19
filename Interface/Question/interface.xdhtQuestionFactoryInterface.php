<?php
/**
 * Class xdhtQuestionFactoryInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtQuestionFactoryInterface {

	/**
	 *
	 * @return array of questions
	 */
	public function getAllQuestions();


	/**
	 * @param array $question_ids
	 * @param integer $question_pool_id
	 *
	 * @return array
	 */
	public function getNotAnsweredQuestionsByIds($question_ids, $question_pool_id);


	/**
	 * @param array $questions
	 *
	 * @return array of qestion ids
	 */
	public function getQuestionIds($questions);

}