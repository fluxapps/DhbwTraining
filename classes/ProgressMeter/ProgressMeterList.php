<?php

class ProgressMeterList {

    /**
     * @var ProgressMeter[]
     */
    private $progress_meters = [];


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param null $json_data
     *
     * @return ProgressMeterList|null
     */
    public static function deserialize($json_data = null)
    {
        $arr_data_progress_meter = json_decode($json_data);

        $progress_meter_list = new ProgressMeterList();

        foreach ($arr_data_progress_meter as $arr_progress_meter) {
            /**
             * @var ProgressMeter $progress_meter
             */
            $progress_meter = ProgressMeter::newFromArray($arr_progress_meter);
            $progress_meter->deserialize($progress_meter);
            $progress_meter_list->add($progress_meter);
        }

        return $progress_meter_list;
    }


    public function add(ProgressMeter $progress_meter)
    {
        $this->progress_meters[] = $progress_meter;
    }


    public function getList() : array
    {
        return $this->progress_meters;
    }


    /**
     * @param ProgressMeterList $other
     *
     * @return bool
     */
    public function equals(AbstractValueObject $other) : bool
    {
        return !is_null($other)
            && count($this->progress_meters) === count($other->progress_meters)
            && $this->optionsAreEqual($other);
    }


    /**
     * @param ProgressMeterList $other
     *
     * @return bool
     */
    public function optionsAreEqual(ProgressMeterList $other) : bool
    {
        /** @var ProgressMeter $one_of_my_progress_meter */
        foreach ($this->progress_meters as $one_of_my_progress_meter) {
            $found = false;

            /** @var ProgressMeter $one_of_other_progress_meter */
            foreach ($other->progress_meters as $one_of_other_progress_meter) {

                if (is_null($one_of_other_progress_meter)) {
                    continue;
                }

                if ($one_of_my_progress_meter->equals($one_of_other_progress_meter)
                ) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return false;
            }
        }

        return true;
    }


    public function setList(ValueObjectList $int_value_object)
    {
        // TODO: Implement setList() method.
    }


    public function append(ValueObject $value_object)
    {
        // TODO: Implement append() method.
    }


    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}