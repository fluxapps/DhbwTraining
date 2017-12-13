<?php

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.DhbwRepositorySelectorInputGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Traits/trait.xdhtDIC.php');
/**
 * Class xdhtSettingsFormGUI
 *
 * @author            : Benjamin Seglias   <bs@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy xdhtSettingsFormGUI: ilObjPluginDispatchGUI
 * @ilCtrl_Calls      xdhtSettingsFormGUI: ilFormPropertyDispatchGUI
 */
class xdhtSettingsFormGUI extends ilPropertyFormGUI {

	use xdhtDIC;
	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;
	/**
	 * @var ilObjDhbwTrainingGUI
	 */
	protected $parent_gui;


	/**
	 * xdhtSettingsFormGUI constructor.
	 *
	 * @param                           $parent_gui
	 * @param xdhtObjectFacadeInterface $facade
	 */
	public function __construct($parent_gui, xdhtObjectFacadeInterface $facade) {
		$this->parent_gui = $parent_gui;
		$this->facade = $facade;
		//stores the default mode settings if the user previously created the object in the repository
		/*		if ($this->is_creation_mode) {

				}*/
		parent::__construct();

		$this->initForm();
	}


	public function initForm() {
		$this->setTarget('_top');
		$this->setId('xdht_settings_form');
		$this->setFormAction($this->facade->ctrl()->getFormAction($this->parent_gui));
		$this->setTitle($this->facade->pl()->txt('general_settings'));

		$ti = new ilTextInputGUI($this->facade->pl()->txt('title'), 'title');
		$ti->setRequired(true);
		$this->addItem($ti);

		$ta = new ilTextAreaInputGUI($this->facade->pl()->txt('description'), 'desc');
		$ta->setRows(10);
		$this->addItem($ta);
		/*
				$rep_sel_input = new DhbwRepositorySelectorInputGUI($this->facade->pl()->txt('select_question_pool'), 'question_pool_selection', $this->facade);
				$rep_sel_input->setParent($this->parent_gui);
				$rep_sel_input->setSelectText($this->facade->pl()->txt('please_select_question_pool'));
				$rep_sel_input->readFromSession();
				$rep_sel_input->setHeaderMessage('header message');
				$rep_sel_input->setClickableTypes(array("qpl"));
				$this->addItem($rep_sel_input);*/

		/*		$sel_qpl = new ilSelectInputGUI($this->facade->pl()->txt('select_question_pool'), 'question_pool_selection');
				$sel_qpl->setOptions(array());
				$sel_qpl->setRequired(true);
				$this->addItem($sel_qpl);*/

		$rep_sel_input = new ilSelectInputGUI($this->facade->pl()->txt('select_question_pool'), 'question_pool_selection');
		$rep_sel_input->setRequired(true);
		$question_pools_array = $this->facade->xdhtQuestionPoolFactory()->getSelectOptionsArray();
		$question_pools_array_2 = array(null => $this->pl()->txt('please_choose')) + $question_pools_array;
		$rep_sel_input->setOptions($question_pools_array_2);
		$this->addItem($rep_sel_input);

		$this->addCommandButton(ilObjDhbwTrainingGUI::CMD_UPDATE, $this->facade->pl()->txt('save'));
		$this->addCommandButton(ilObjDhbwTrainingGUI::CMD_STANDARD, $this->facade->pl()->txt("cancel"));
	}


	public function fillForm() {
		$values['title'] = $this->facade->training_object()->getTitle();
		$values['desc'] = $this->facade->training_object()->getDescription();
		/*$value_from_session = unserialize($_SESSION["form_".ilObjDhbwTrainingGUI::class]['question_pool_selection']);
		$values['question_pool_selection'] = $value_from_session;*/
		$values['question_pool_selection'] = $this->facade->settings()->getQuestionPoolId();
		$this->setValuesByArray($values);
	}


	public function fillObject() {
		if (!$this->checkInput()) {
			return false;
		}

		$this->facade->training_object()->setTitle($this->getInput('title'));
		$this->facade->training_object()->setDescription($this->getInput('desc'));
		$this->facade->settings()->setQuestionPoolId($this->getInput('question_pool_selection'));

		return true;
	}


	/**
	 * @return bool|string
	 */
	public function updateObject() {
		if (!$this->fillObject()) {
			return false;
		}
		//$this->facade->training_object()->store();
		$this->facade->settings()->store();

		return true;
	}
}