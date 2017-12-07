<?php
/**
 * Class xdhtQuestionGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtQuestionGUI {

	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;

	const CMD_STANDARD = 'edit';

	/**
	 * xdhtQuestionGUI constructor.
	 *
	 * @param xdhtObjectFacadeInterface $facade
	 */
	public function __construct(xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
	}

}