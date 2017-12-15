<?php

/**
 * Trait xdhtDIC
 *
 *
 */
trait xdhtDIC {

	/**
	 * @return \ILIAS\DI\Container
	 */
	public function dic() {
		return $GLOBALS['DIC'];
	}


	/**
	 * @return ilDhbwTrainingPlugin
	 */
	public function pl() {
		return ilDhbwTrainingPlugin::getInstance();
	}


	/**
	 * @return ilCtrl
	 */
	public function ctrl() {
		return $this->dic()->ctrl();
	}


	/**
	 * @return ilTemplate
	 */
	public function ui() {
		return $this->dic()->ui()->mainTemplate();
	}


	/**
	 * @return ilObjDhbwTrainingAccess
	 */
	public function access() {
		return new ilObjDhbwTrainingAccess();
	}


	/**
	 * @return ilLanguage
	 */
	public function tabs() {
		return $this->dic()->tabs();
	}


	/**
	 * @return ilTemplate
	 */
	public function tpl() {
		return $this->dic()['tpl'];
	}


	/**
	 * @return ilObjUser
	 */
	public function user() {
		return $this->dic()->user();
	}

	public function language() {
		return $this->dic()->language();
	}


}