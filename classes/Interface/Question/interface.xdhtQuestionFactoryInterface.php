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
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function getQuestionById($id);

	/**
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function getQuestionByRecomanderId($id);


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