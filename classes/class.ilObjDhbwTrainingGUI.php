<?php

/**
 * Class ilObjDhbwTrainingGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

require_once('./Services/Repository/classes/class.ilObjectPluginGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingAccess.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Start/class.xdhtStartGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettings.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilDhbwTrainingPlugin.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettingsFormGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Facade/class.xdhtObjectFacade.php');
//require_once('./Services/AccessControl/classes/class.ilPermissionGUI.php');


/**
 * User Interface class for dhbw training repository object.
 *
 * @author            Benjamin Seglias <bs@studer-raimann.ch>
 *
 * @version           1.0.00
 *
 * Integration into control structure:
 * - The GUI class is called by ilRepositoryGUI
 * - GUI classes used by this class are ilPermissionGUI (provides the rbac
 *   screens) and ilInfoScreenGUI (handles the info screen).
 *
 * The most complicated thing is the control-flow.
 *
 *
 * @ilCtrl_isCalledBy ilObjDhbwTrainingGUI: ilRepositoryGUI
 * @ilCtrl_isCalledBy ilObjDhbwTrainingGUI: ilObjPluginDispatchGUI
 * @ilCtrl_isCalledBy ilObjDhbwTrainingGUI: ilAdministrationGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilPermissionGUI,ilInfoScreenGUI, ilPropertyFormGUI, ilRepositorySelectorInputGUI, DhbwRepositorySelectorInputGUI, ilFormPropertyDispatchGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilObjectCopyGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtStartGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtSettingsGUI
 */

class ilObjDhbwTrainingGUI extends ilObjectPluginGUI {

	const CMD_STANDARD = "index";
	const CMD_EDIT = "edit";
	const CMD_UPDATE = "update";
	const CMD_AFTER_SAVE = "afterSave";
	const TAB_START = "start";
	const TAB_SETTINGS = "settings";
	const TAB_COMPETENCE = "competence";
	const TAB_PARTICIPANTS = "participants";
	const TAB_LEARNING_PROGRESS = "learning_progress";
	const TAB_EXPORT = "export";
	const TAB_PERMISSIONS = "permissions";

	/**
	 * @var ilObjDhbwTraining
	 */
	public $object;
	/**
	 * @var ilPropertyFormGUI
	 */
	protected $form;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;

	protected function afterConstructor() {
		global $DIC;

		$this->dic = $DIC;
		$this->access = new ilObjDhbwTrainingAccess();
		$this->pl = ilDhbwTrainingPlugin::getInstance();
		$this->tabs = $DIC->tabs();
		$this->ctrl = $DIC->ctrl();
		$this->tpl = $DIC['tpl'];
		$this->facade = xdhtObjectFacade::getInstance($_GET['ref_id']);
	}

	public function executeCommand() {

		$this->setTitleAndDescription();
		if (!$this->getCreationMode()) {
			$this->tpl->setTitleIcon(ilObject::_getIcon($this->object->getId()));
		} else {
			$this->tpl->setTitleIcon(ilObject::_getIcon(ilObject::_lookupObjId($_GET['ref_id']), 'big'), $this->facade->pl()->txt('obj_'
				. ilObject::_lookupType($_GET['ref_id'], true)));
		}

		$next_class = $this->facade->dic()->ctrl()->getNextClass($this);

		$cmd = $this->facade->dic()->ctrl()->getCmd(xdhtStartGUI::CMD_STANDARD);

		$this->facade = xdhtObjectFacade::getInstance($_GET['ref_id']);

		switch ($next_class) {
			case strtolower(xdhtQuestionGUI::class):
				$this->setTabs();
				$this->setLocator();
				$this->tabs->activateTab(self::TAB_START);
				//has to be called because in this case parent::executeCommand is not executed(contains getStandardTempplate and Show)
				//Show Method has to be called in the corresponding methods
				$this->tpl->getStandardTemplate();
				$xdhtQuestionGUI = new xdhtQuestionGUI($this->facade);
				$this->ctrl->forwardCommand($xdhtQuestionGUI);
				break;

/*			case 'ilpropertyformgui':
				$ilPropertyFormGUI = new xdhtSettingsFormGUI($this, $this->facade);
				$this->ctrl->forwardCommand($ilPropertyFormGUI);
				break;*/

/*			case strtolower(DhbwRepositorySelectorInputGUI::class):
				$this->tpl->getStandardTemplate();
				$dhbwrepositoryselectorinputgui = new DhbwRepositorySelectorInputGUI($this->facade->pl()->txt('select_question_pool'), 'question_pool_selection', $this->facade);
				$dhbwrepositoryselectorinputgui->setParent($this);
				$dhbwrepositoryselectorinputgui->setSelectText($this->facade->pl()->txt('please_select_question_pool'));
				$dhbwrepositoryselectorinputgui->setClickableTypes(array('qpl'));
				$dhbwrepositoryselectorinputgui->readFromSession();
				$this->ctrl->setReturn($this, self::CMD_EDIT);
				$this->ctrl->forwardCommand($dhbwrepositoryselectorinputgui);
				$this->tpl->show();
				break;*/

			case strtolower(xdhtStartGUI::class):
				$this->setTabs();
				$this->setLocator();
				$this->tabs->activateTab(self::TAB_START);
				$this->tpl->getStandardTemplate();
				$xdhtStartGUI = new xdhtStartGUI($this->facade);
				$this->ctrl->forwardCommand($xdhtStartGUI);
				break;

			default:
				return parent::executeCommand();
				break;
		}
	}


	protected function performCommand() {
		$cmd = $this->ctrl->getCmd(xdhtStartGUI::CMD_STANDARD);

		switch ($cmd) {
			case self::CMD_STANDARD:
				if ($this->facade->access()->hasReadAccess()) {
					$this->ctrl->redirect(new xdhtStartGUI($this->facade), xdhtStartGUI::CMD_STANDARD);
					break;
				} else {
					ilUtil::sendFailure(ilAssistedExercisePlugin::getInstance()->txt('permission_denied'), true);
					break;
			}
			case self::CMD_EDIT:
			case self::CMD_UPDATE:
			case self::CMD_AFTER_SAVE:
				if ($this->facade->access()->hasWriteAccess()) {
					$this->{$cmd}();
					break;
				} else {
					ilUtil::sendFailure($this->pl->txt('permission_denied'), true);
					break;
				}
		}
	}

	protected function setTabs() {
		if (strtolower($_GET['baseClass']) != 'iladministrationgui') {
			$this->tabs->addTab(self::TAB_START, $this->facade->pl()->txt('start'), $this->ctrl->getLinkTarget(new xdhtStartGUI($this->facade), xdhtStartGUI::CMD_STANDARD));
			if ($this->facade->access()->hasWriteAccess()) {
					$this->tabs->addTab(self::TAB_SETTINGS, $this->facade->pl()->txt('settings'), $this->ctrl->getLinkTarget($this, self::CMD_EDIT));
			}
			if ($this->checkPermissionBool('edit_permission')) {
				$this->tabs->addTab(self::TAB_PERMISSIONS, $this->facade->pl()->txt('permissions'), $this->ctrl->getLinkTargetByClass(array(
					strtolower(ilObjDhbwTrainingGUI::class),
					strtolower(ilPermissionGUI::class),
				), 'perm'));
			}
		} else {
			$this->addAdminLocatorItems();
			$this->tpl->setLocator();
			$this->setAdminTabs();
		}
	}


	function getType() {
		return ilDhbwTrainingPlugin::PLUGIN_PREFIX;
	}

	public function edit() {
		$this->tabs->activateTab(self::TAB_SETTINGS);
		$xdhtSettingsFormGUI = new xdhtSettingsFormGUI($this, $this->facade);
		$xdhtSettingsFormGUI->fillForm();
		$this->tpl->setContent($xdhtSettingsFormGUI->getHTML());
	}


	public function update() {
		$this->tabs->activateTab(self::TAB_SETTINGS);
		$xdhtSettingsFormGUI = new xdhtSettingsFormGUI($this, $this->facade);
		if ($xdhtSettingsFormGUI->updateObject() && $this->object->update()) {
			ilUtil::sendSuccess($this->facade->pl()->txt('changes_saved_success'), true);
		}
		$xdhtSettingsFormGUI->setValuesByPost();
		$this->tpl->setContent($xdhtSettingsFormGUI->getHTML());
	}


	/**
	 * Cmd that will be redirected to after creation of a new object.
	 */
	function getAfterCreationCmd() {
		// TODO: Implement getAfterCreationCmd() method.
	}


	function getStandardCmd() {
		return xdhtStartGUI::CMD_STANDARD;
	}

	protected function getSettings() {
		if (xdhtSettings::where([ 'dhbw_training_object_id' => intval($this->object->getId()) ])->hasSets()) {
			return xdhtSettings::where([ 'dhbw_training_object_id' => intval($this->object->getId()) ])->first();
		} else {
			return new xdhtSettings();
		}
	}


	public function getId() {
		return self::class;
	}
}