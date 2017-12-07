<?php
/**
 * Class ilObjDhbwTrainingFacadeInterface
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtObjectFacadeInterface {

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
	 * @return \ILIAS\DI\Container
	 */
	public function dic();


	/**
	 * @return ilTemplate
	 */
	public function ui();


	/**
	 * @return ilObjUser
	 */
	public function user();


	/**
	 * @return ilCtrl
	 */
	public function ctrl();


	/**
	 * @return ilDhbwTrainingPlugin
	 */
	public function pl();


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
}