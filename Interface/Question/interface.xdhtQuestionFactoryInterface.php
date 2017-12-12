<?php
/**
 * Class xdhtQuestionFactoryInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtQuestionFactoryInterface {

	/**
	 * @param integer $question_pool_id
	 *
	 * @return array of questions
	 */
	public function getAllQuestionsByQuestionPoolId($question_pool_id);

}