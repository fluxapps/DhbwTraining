<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtSettingsFormGUI
 *
 * @author            : Benjamin Seglias   <bs@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy xdhtSettingsFormGUI: ilObjPluginDispatchGUI
 * @ilCtrl_Calls      xdhtSettingsFormGUI: ilFormPropertyDispatchGUI
 */
class xdhtSettingsFormGUI extends ilPropertyFormGUI
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
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
    public function __construct($parent_gui, xdhtObjectFacadeInterface $facade)
    {
        $this->parent_gui = $parent_gui;
        $this->facade = $facade;
        //stores the default mode settings if the user previously created the object in the repository
        /*		if ($this->is_creation_mode) {

                }*/
        parent::__construct();

        $this->initForm();
    }


    public function initForm()
    {
        $this->setTarget('_top');
        $this->setId('xdht_settings_form');
        $this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent_gui));
        $this->setTitle(self::plugin()->translate('general_settings'));

        $ti = new ilTextInputGUI(self::plugin()->translate('title'), 'title');
        $ti->setRequired(true);
        $this->addItem($ti);

        $ta = new ilTextAreaInputGUI(self::plugin()->translate('description'), 'desc');
        $ta->setRows(10);
        $this->addItem($ta);

        $item = new ilFormSectionHeaderGUI();
        $item->setTitle(self::plugin()->translate('availability'));
        $this->addItem($item);
        self::dic()->ui()->mainTemplate()->addJavaScript('./Services/Form/js/date_duration.js');

        $item = new ilCheckboxInputGUI(self::dic()->language()->txt('online'), 'online');
        $item->setValue("1");
        $item->setInfo(self::plugin()->translate('online_info'));

        $this->addItem($item);

        $item = new ilFormSectionHeaderGUI();
        $item->setTitle(self::plugin()->translate('recommender_system'));
        $this->addItem($item);

        $ti = new ilTextInputGUI(self::plugin()->translate("installation_key"), 'installation_key');
        $ti->setRequired(true);
        $this->addItem($ti);

        $ti = new ilTextInputGUI(self::plugin()->translate("secret"), 'secret');
        $ti->setRequired(true);
        $this->addItem($ti);

        $recommender_system_server = new ilRadioGroupInputGUI(self::plugin()->translate("recommender_system_server"), "recommender_system");
        $this->addItem($recommender_system_server);

        $recommender_system_server_external = new ilRadioOption(self::plugin()->translate("recommender_system_server_external"), xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_EXTERNAL);
        $recommender_system_server->addOption($recommender_system_server_external);

        if (intval(DEVMODE) === 1) {
            $recommender_system_server_built_in_debug = new ilRadioOption(self::plugin()->translate("recommender_system_server_built_in_debug"), xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG);
            $recommender_system_server_built_in_debug->setInfo(nl2br(str_replace("\\n", "\n", self::plugin()->translate("recommender_system_server_built_in_debug_info", "", ["[[question_id]]", "recomander_id", self::plugin()->directory() . "/classes/Recommender/debug/api/v1"])), false));
            $recommender_system_server->addOption($recommender_system_server_built_in_debug);
        }

        $ti = new ilTextInputGUI(self::plugin()->translate("url"), 'url');
        $ti->setRequired(true);
        $recommender_system_server_external->addSubItem($ti);

        $item = new ilCheckboxInputGUI(self::plugin()->translate("log"), 'log');
        $item->setInfo(self::plugin()->translate("log_info"));
        $item->setValue("1");
        $this->addItem($item);

        /*
                $rep_sel_input = new DhbwRepositorySelectorInputGUI(self::plugin()->translate('select_question_pool'), 'question_pool_selection', $this->facade);
                $rep_sel_input->setParent($this->parent_gui);
                $rep_sel_input->setSelectText(self::plugin()->translate('please_select_question_pool'));
                $rep_sel_input->readFromSession();
                $rep_sel_input->setHeaderMessage('header message');
                $rep_sel_input->setClickableTypes(array("qpl"));
                $this->addItem($rep_sel_input);*/

        /*		$sel_qpl = new ilSelectInputGUI(self::plugin()->translate('select_question_pool'), 'question_pool_selection');
                $sel_qpl->setOptions(array());
                $sel_qpl->setRequired(true);
                $this->addItem($sel_qpl);*/
        /*
                $item = new ilFormSectionHeaderGUI();
                $item->setTitle(self::plugin()->translate('question_pool'));
                $this->addItem($item);
                $rep_sel_input = new ilSelectInputGUI(self::plugin()->translate('select_question_pool'), 'question_pool_selection');
                $rep_sel_input->setRequired(true);
                $question_pools_array = $this->facade->xdhtQuestionPoolFactory()->getSelectOptionsArray();
                $question_pools_array_2 = array(null => self::plugin()->translate('please_choose')) + $question_pools_array;
                $rep_sel_input->setOptions($question_pools_array_2);
                $this->addItem($rep_sel_input);*/

        $this->addCommandButton(ilObjDhbwTrainingGUI::CMD_UPDATE, self::plugin()->translate('save'));
        $this->addCommandButton(ilObjDhbwTrainingGUI::CMD_STANDARD, self::plugin()->translate("cancel"));
    }


    public function fillForm()
    {
        $values['title'] = $this->facade->training_object()->getTitle();
        $values['desc'] = $this->facade->training_object()->getDescription();
        /*$value_from_session = unserialize($_SESSION["form_".ilObjDhbwTrainingGUI::class]['question_pool_selection']);
        $values['question_pool_selection'] = $value_from_session;*/
        $values['question_pool_selection'] = $this->facade->settings()->getQuestionPoolId();
        $values['online'] = $this->facade->settings()->getIsOnline();
        $values['installation_key'] = $this->facade->settings()->getInstallationKey();
        $values['secret'] = $this->facade->settings()->getSecret();
        $values['url'] = $this->facade->settings()->getUrl();
        $values['log'] = $this->facade->settings()->getLog();
        $values['recommender_system'] = $this->facade->settings()->getRecommenderSystemServer();
        $this->setValuesByArray($values);
    }


    public function fillObject()
    {
        if (!$this->checkInput()) {
            return false;
        }

        $this->facade->training_object()->setTitle($this->getInput('title'));
        $this->facade->training_object()->setDescription($this->getInput('desc'));
        $this->facade->settings()->setQuestionPoolId($this->getInput('question_pool_selection'));
        $this->facade->settings()->setIsOnline($this->getInput('online'));

        $this->facade->settings()->setInstallationKey($this->getInput('installation_key'));
        $this->facade->settings()->setSecret($this->getInput('secret'));
        $this->facade->settings()->setUrl($this->getInput('url'));
        $this->facade->settings()->setLog($this->getInput('log'));
        $this->facade->settings()->setRecommenderSystemServer($this->getInput('recommender_system'));

        return true;
    }


    /**
     * @return bool|string
     */
    public function updateObject()
    {
        if (!$this->fillObject()) {
            return false;
        }
        //$this->facade->training_object()->store();
        $this->facade->settings()->store();

        return true;
    }
}