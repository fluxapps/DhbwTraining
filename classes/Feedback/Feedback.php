<?php

/**
 * Class Feedback
 *
 *
 * @author Sebastian Wankerl <sebastian.wankerl@mosbach.dhbw.de>
 */
class Feedback
{
    /**
     * @var string
     */
    protected $feedback;

    /**
     * @var string
     */
    protected $recomander_id;

    /**
     * @var bool
     */
    protected $correct;

    /**
     * @var xdhtObjectFacadeInterface
     */
    protected $facade;

    /**
     * Feedback constructor.
     * @param string $feedback
     * @param string $recomander_id
     * @param bool $correct
     * @param xdhtObjectFacadeInterface $facade
     */
    public function __construct($feedback, $recomander_id, $correct, $facade)
    {
        $this->feedback = $feedback;
        $this->recomander_id = $recomander_id;
        $this->correct = $correct;
        $this->facade = $facade;
    }

    public function getFeedback()
    {
        global $ilDB;
        $question_data = $this->facade->xdhtQuestionFactory()->getQuestionByRecomanderId($this->recomander_id);
        $question_id = $question_data['question_id'];
        $question_type = $question_data['question_type_fi'];
        if (is_numeric($this->feedback)) {
            $sql = "SELECT feedback FROM qpl_fb_specific WHERE question_fi = $question_id AND answer = $this->feedback";
            $set = $ilDB->query($sql);

            $row = $ilDB->fetchAssoc($set);
            $feedback = $row["feedback"];
            if (!empty($feedback)) {
                return $feedback;
            }

            $cdb = (int)$this->correct;
            $sql = "SELECT feedback FROM qpl_fb_generic WHERE question_fi = $question_id AND correctness = $cdb";
            $set = $ilDB->query($sql);

            $row = $ilDB->fetchAssoc($set);
            $feedback = $row["feedback"];
            if (!empty($feedback)) {
                return $feedback;
            }
        }

        if (!empty($this->feedback) and !is_numeric($this->feedback)) {
            return $this->feedback;
        }
        elseif ($this->correct) {
            return "<strong>Ihre Antwort ist korrekt!</strong>";
        }
        else {
            return $this->getCorrectAnswer($question_id, $question_type);
        }
    }

    public function getFeedbackType()
    {
        if ($this->correct) {
            return ilGlobalTemplate::MESSAGE_TYPE_SUCCESS;
        }
        else {
            return ilGlobalTemplate::MESSAGE_TYPE_FAILURE;
        }
    }

    private function getCorrectAnswer($question, $question_type) {
        global $ilDB;
        if ($question_type == 1) {
            $sql = "select answertext from qpl_a_sc where question_fi = $question and points > 0";
            $set = $ilDB->query($sql);
            $correct_answer = $ilDB->fetchAssoc($set)["answertext"];
        }
        elseif ($question_type == 2) {
            $sql = "select answertext from qpl_a_mc where question_fi = $question and points > 0";
            $set = $ilDB->query($sql);
            $answers = array_column($ilDB->fetchAll($set),"answertext");
            $correct_answer = "<ul> <li>" . implode("</li><li>", $answers) . "</li> </ul>";
        }
        elseif ($question_type == 3) {
            $sql = "select answertext from qpl_a_cloze where question_fi = $question order by gap_id";
            $set = $ilDB->query($sql);
            $answers = array_column($ilDB->fetchAll($set), "answertext");
            $correct_answer = "<ul> <li>" . implode("</li><li>", $answers) . "</li> </ul>";
        }

        return "<strong><em>Ihre Antwort ist nicht korrekt.<br>Lösung:</em></strong><br>$correct_answer";
    }
}