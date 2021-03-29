<?php

/**
 * Class RecommenderResponse
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class QuestionAnswer
{

    /**
     * @var int
     */
    protected $answer_id;
    /**
     * @var int
     */
    protected $question_id;
    /**
     * @var int
     */
    protected $a_order;
    /**
     * @var string
     */
    protected $answertext;
    /**
     * @var int
     */
    //Todo separate Class er Question!
    protected $cloze_type;
    /**
     * @var float
     */
    protected $points;


    /**
     * @return int
     */
    public function getAnswerId() : int
    {
        return $this->answer_id;
    }


    /**
     * @param int $answer_id
     */
    public function setAnswerId(int $answer_id)
    {
        $this->answer_id = $answer_id;
    }


    /**
     * @return int
     */
    public function getQuestionId() : int
    {
        return $this->question_id;
    }


    /**
     * @param int $question_id
     */
    public function setQuestionId(int $question_id)
    {
        $this->question_id = $question_id;
    }


    /**
     * @return int
     */
    public function getAOrder() : int
    {
        return $this->a_order;
    }


    /**
     * @param int $a_order
     */
    public function setAOrder(int $a_order)
    {
        $this->a_order = $a_order;
    }


    /**
     * @return string
     */
    public function getAnswertext() : string
    {
        return $this->answertext;
    }


    /**
     * @param string $answertext
     */
    public function setAnswertext(string $answertext)
    {
        $this->answertext = str_replace(array(' ', ','), array('', '.'), $answertext);
    }


    /**
     * @return int
     */
    public function getClozeType() : int
    {
        return $this->cloze_type;
    }


    /**
     * @param int $cloze_type
     */
    public function setClozeType(int $cloze_type)
    {
        $this->cloze_type = $cloze_type;
    }

    /**
     * @return int
     */
    public function getPoints() : float
    {
        return $this->points;
    }


    /**
     * @param float $points
     */
    public function setPoints(float $points)
    {
        $this->points = $points;
    }
}