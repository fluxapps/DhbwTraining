<?php
/**
 * Class xdhtSettingsGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtSettingsGUI {

		/**
		 * @var xdhtObjectFacadeInterface
		 */
		protected $facade;

	const CMD_STANDARD = 'edit';

	/**
	 * xdhtSettingsGUI constructor.
	 *
	 * @param xdhtObjectFacadeInterface $facade
	 */
	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
	}
}