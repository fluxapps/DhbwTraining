<?php
/**
 * Class ilObjDhbwTrainingFacadeInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtObjectFacadeInterface
{

    /**
     * @param $ref_id
     *
     * @return xdhtObjectFacadeInterface
     */
    public static function getInstance($ref_id);


    /**
     * @return xdhtSettingsInterface
     */
    public function settings();


    /**
     * @return int
     */

    public function objectId();


    /**
     * @return int
     */
    public function refId();


    /**
     * @return ilObjDhbwTraining
     */
    public function training_object();


    /**
     * @return xdhtSettingFactoryInterface
     */
    public function xdhtSettingsFactory();


    /**
     * @return xdhtQuestionPoolFactoryInterface
     */
    public function xdhtQuestionPoolFactory();


    /**
     * @return xdhtQuestionFactoryInterface
     */
    public function xdhtQuestionFactory();


    /**
     * @return xdhtParticipantFactoryInterface
     */
    public function xdhtParticipantFactory();
}