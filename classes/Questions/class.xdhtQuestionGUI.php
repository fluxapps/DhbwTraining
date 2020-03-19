<?php
/**
 * Class xdhtQuestionGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtQuestionGUI
{

    const CMD_STANDARD = 'edit';
    /**
     * @var xdhtObjectFacadeInterface
     */
    protected $facade;


    /**
     * xdhtQuestionGUI constructor.
     *
     * @param xdhtObjectFacadeInterface $facade
     */
    public function __construct(xdhtObjectFacadeInterface $facade)
    {
        $this->facade = $facade;
    }
}