<?php

class ProgressMeter
{

    const PROGRESS_METER_TYPE_STANDARD = "STANDARD";
    const PROGRESS_METER_TYPE_MINI = "MINI";
    /**
     * @var string
     */
    protected $progressmeter_type;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var int
     */
    protected $max_width_in_pixel;
    /**
     * @var int
     */
    protected $max_reachable_score;
    /**
     * @var int
     */
    protected $required_score;
    /**
     * @var null|string
     */
    protected $required_score_label = null;
    /**
     * @var int
     */
    protected $primary_reached_score;
    /**
     * @var null|string
     */
    protected $primary_reached_score_label = null;
    /**
     * @var int|null
     */
    protected $secondary_reached_score = null;
    /**
     * @var null|string
     */
    protected $secondary_reached_score_label = null;


    public static function newFromArray(array $arr_progress_meter = null) : ProgressMeter
    {

        $obj = new static();

        foreach ($arr_progress_meter as $property => $value) {
            if (property_exists($obj, $property)) {
                $obj->{$property} = $value;
            }
        }

        //Default-Values
        if ($obj->max_width_in_pixel == 0) {
            $obj->max_width_in_pixel = 200;
        }

        return $obj;
    }


    public static function newStandardProgressMeter(
        string $title,
        int $max_width_in_pixel,
        int $max_reachable_score,
        int $required_score,
        string $required_score_label,
        int $primary_reached_score,
        string $primary_reached_score_label,
        int $secondary_reached_score,
        string $secondary_reached_score_label
    ) : ProgressMeter {
        $obj = new static();
        $obj->title = $title;
        $obj->max_width_in_pixel = $max_width_in_pixel;
        $obj->progressmeter_type = static::PROGRESS_METER_TYPE_STANDARD;
        $obj->max_reachable_score = $max_reachable_score;
        $obj->required_score = $required_score;
        $obj->required_score_label = $required_score_label;
        $obj->primary_reached_score = $primary_reached_score;
        $obj->primary_reached_score_label = $primary_reached_score_label;
        $obj->secondary_reached_score = $secondary_reached_score;
        $obj->secondary_reached_score_label = $secondary_reached_score_label;

        return $obj;
    }


    /**
     * @param int      $max_reachable_points
     * @param int      $required_score
     * @param int|null $primary_reached_score
     *
     * @return ProgressMeter
     */
    public static function newMiniProgressMeter(
        string $title,
        int $max_width_in_pixel,
        int $max_reachable_points,
        int $required_score,
        int $primary_reached_score
    ) : ProgressMeter {
        $obj = new static();
        $obj->title = $title;
        $obj->max_width_in_pixel = $max_width_in_pixel;
        $obj->progressmeter_type = static::PROGRESS_METER_TYPE_MINI;
        $obj->max_reachable_score = $max_reachable_points;
        $obj->required_score = $required_score;
        $obj->primary_reached_score = $primary_reached_score;

        return $obj;
    }


    /**
     * @return string
     */
    public function getProgressmeterType() : string
    {
        return $this->progressmeter_type;
    }


    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }


    /**
     * @return int
     */
    public function getMaxReachableScore() : int
    {
        return $this->max_reachable_score;
    }


    /**
     * @return int
     */
    public function getRequiredScore() : int
    {
        return $this->required_score;
    }


    /**
     * @return null|string
     */
    public function getRequiredScoreLabel()
    {
        return $this->required_score_label;
    }


    /**
     * @return int
     */
    public function getPrimaryReachedScore() : int
    {
        return $this->primary_reached_score;
    }


    /**
     * @return null|int
     */
    public function getPrimaryReachedScoreLabel()
    {
        return $this->primary_reached_score_label;
    }


    /**
     * @return null|int
     */
    public function getSecondaryReachedScore()  /*:int*/
    {
        return $this->secondary_reached_score;
    }


    /**
     * @return null|string
     */
    public function getSecondaryReachedScoreLabel()
    {
        return $this->secondary_reached_score_label;
    }


    /**
     * @return int
     */
    public function getMaxWidthInPixel() : int
    {
        return $this->max_width_in_pixel;
    }
}