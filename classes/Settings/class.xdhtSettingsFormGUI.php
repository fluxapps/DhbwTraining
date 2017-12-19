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
		$this->setFormAction($this->ctrl()->getFormAction($this->parent_gui));
		$this->setTitle($this->pl()->txt('general_settings'));

		$ti = new ilTextInputGUI($this->pl()->txt('title'), 'title');
		$ti->setRequired(true);
		$this->addItem($ti);

		$ta = new ilTextAreaInputGUI($this->pl()->txt('description'), 'desc');
		$ta->setRows(10);
		$this->addItem($ta);

		$item = new ilFormSectionHeaderGUI();
		$item->setTitle($this->pl()->txt('availability'));
		$this->addItem($item);
		$this->tpl()->addJavaScript('./Services/Form/js/date_duration.js');
		include_once "Services/Form/classes/class.ilDateDurationInputGUI.php";

		$item = new ilCheckboxInputGUI($this->language()->txt('online'), 'online');
		$item->setValue("1");
		$item->setInfo($this->pl()->txt('online_info'));

		$sub_item = new ilDateDurationInputGUI($this->pl()->txt("time_period"), "time_period");
		$sub_item->setShowTime(true);
		$sub_item->setStartText($this->pl()->txt('start_time'));
		$sub_item->setEndText($this->pl()->txt('finish_time'));
		$item->addSubItem($sub_item);

		$this->addItem($item);


		/*
				$rep_sel_input = new DhbwRepositorySelectorInputGUI($this->pl()->txt('select_question_pool'), 'question_pool_selection', $this->facade);
				$rep_sel_input->setParent($this->parent_gui);
				$rep_sel_input->setSelectText($this->pl()->txt('please_select_question_pool'));
				$rep_sel_input->readFromSession();
				$rep_sel_input->setHeaderMessage('header message');
				$rep_sel_input->setClickableTypes(array("qpl"));
				$this->addItem($rep_sel_input);*/

		/*		$sel_qpl = new ilSelectInputGUI($this->pl()->txt('select_question_pool'), 'question_pool_selection');
				$sel_qpl->setOptions(array());
				$sel_qpl->setRequired(true);
				$this->addItem($sel_qpl);*/

		$item = new ilFormSectionHeaderGUI();
		$item->setTitle($this->pl()->txt('question_pool'));
		$this->addItem($item);
		$rep_sel_input = new ilSelectInputGUI($this->pl()->txt('select_question_pool'), 'question_pool_selection');
		$rep_sel_input->setRequired(true);
		$question_pools_array = $this->facade->xdhtQuestionPoolFactory()->getSelectOptionsArray();
		$question_pools_array_2 = array(null => $this->pl()->txt('please_choose')) + $question_pools_array;
		$rep_sel_input->setOptions($question_pools_array_2);
		$this->addItem($rep_sel_input);

		$item = new ilFormSectionHeaderGUI();
		$item->setTitle($this->pl()->txt('proposal_system'));
		$this->addItem($item);
		$ti = new ilNonEditableValueGUI($this->pl()->txt('proposal_system'), 'proposal_system');
		$ti->setValue('Example Proposal System');
		$this->addItem($ti);

		$this->addCommandButton(ilObjDhbwTrainingGUI::CMD_UPDATE, $this->pl()->txt('save'));
		$this->addCommandButton(ilObjDhbwTrainingGUI::CMD_STANDARD, $this->pl()->txt("cancel"));
	}


	public function fillForm() {
		$values['title'] = $this->facade->training_object()->getTitle();
		$values['desc'] = $this->facade->training_object()->getDescription();
		/*$value_from_session = unserialize($_SESSION["form_".ilObjDhbwTrainingGUI::class]['question_pool_selection']);
		$values['question_pool_selection'] = $value_from_session;*/
		$values['question_pool_selection'] = $this->facade->settings()->getQuestionPoolId();
		$values['online'] = $this->facade->settings()->getisOnline();
		$values['time_limited'] = $this->facade->settings()->getisTimeLimited();
		$values['time_period']['start'] = $this->facade->settings()->getStartDate();
		$values['time_period']['end'] = $this->facade->settings()->getEndDate();
		$this->setValuesByArray($values);
	}


	public function fillObject() {
		if (!$this->checkInput()) {
			return false;
		}

		$this->facade->training_object()->setTitle($this->getInput('title'));
		$this->facade->training_object()->setDescription($this->getInput('desc'));
		$this->facade->settings()->setQuestionPoolId($this->getInput('question_pool_selection'));
		$this->facade->settings()->setIsOnline($this->getInput('online'));
		/**
		 * @var array $time_period
		 */
		$time_period = $this->getInput('time_period');
		foreach ($time_period as $key => $value) {

			$date_time = new ilDateTime($value, IL_CAL_DATETIME);
			/* $timestamp = $date_time->get(IL_CAL_UNIX);*/
			$time_period[$key] = $date_time;
		}

		$this->facade->settings()->setStartDate($time_period['start']);
		$this->facade->settings()->setEndDate($time_period['end']);

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