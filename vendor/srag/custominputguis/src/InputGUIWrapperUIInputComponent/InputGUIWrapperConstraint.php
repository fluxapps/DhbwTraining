<?php

namespace srag\CustomInputGUIs\DhbwTraining\InputGUIWrapperUIInputComponent;

use ILIAS\Refinery\Constraint;
use ILIAS\Refinery\Custom\Constraint as CustomConstraint;

/**
 * Class InputGUIWrapperConstraint
 *
 * @package srag\CustomInputGUIs\DhbwTraining\InputGUIWrapperUIInputComponent
 */
class InputGUIWrapperConstraint extends CustomConstraint implements Constraint
{

    use InputGUIWrapperConstraintTrait;
}
