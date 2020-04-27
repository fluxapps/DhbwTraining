<?php

use srag\CustomInputGUIs\DhbwTraining\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\DIC\DhbwTraining\DICTrait;
use srag\Plugins\DhbwTraining\Config\Config;

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

        if (Config::getField(Config::KEY_LEARNING_PROGRESS)) {
            $item = new ilCheckboxInputGUI(self::plugin()->translate("learning_progress"), 'learning_progress');
            $item->setValue("1");
            $this->addItem($item);
        }

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
            $recommender_system_server_built_in_debug = new ilRadioOption(self::plugin()->translate("recommender_system_server_built_in_debug"),
                xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG);
            $recommender_system_server_built_in_debug->setInfo(nl2br(str_replace("\\n", "\n", self::plugin()
                ->translate("recommender_system_server_built_in_debug_info", "", ["[[question_id]]", "recomander_id", self::plugin()->directory() . "/classes/Recommender/debug/api/v1"])), false));
            $recommender_system_server->addOption($recommender_system_server_built_in_debug);

            $recommender_system_server_built_in_debug->addSubItem($this->getCompetencesInputGUI());

            $recommender_system_server_built_in_debug->addSubItem($this->getProgressmeterInputGUI());
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
        $values['recommender_system_server_built_in_debug_competences'] = $this->facade->settings()->getRecommenderSystemServerBuiltInDebugCompetences();
        $values['recommender_system_server_built_in_debug_progressmeters'] = $this->facade->settings()->getRecommenderSystemServerBuiltInDebugProgressmeters();
        if (Config::getField(Config::KEY_LEARNING_PROGRESS)) {
            $values['learning_progress'] = $this->facade->settings()->getLearningProgress();
        }
        $this->setValuesByArray($values);
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
        $this->facade->settings()->setRecommenderSystemServer((int) $this->getInput('recommender_system'));
        $this->facade->settings()->setRecommenderSystemServerBuiltInDebugCompetences((array) $this->getInput('recommender_system_server_built_in_debug_competences'));

        $this->facade->settings()->setRecommenderSystemServerBuiltInDebugProgressmeters((array) $this->getInput('recommender_system_server_built_in_debug_progressmeters'));

        if (Config::getField(Config::KEY_LEARNING_PROGRESS)) {
            $this->facade->settings()->setLearningProgress((int) $this->getInput('learning_progress'));
        }

        return true;
    }


    protected function getProgressmeterInputGUI() : MultiLineNewInputGUI
    {
        $recommender_system_server_built_in_debug_progressmeters = new MultiLineNewInputGUI("progressmeters", "recommender_system_server_built_in_debug_progressmeters");
        $recommender_system_server_built_in_debug_progressmeters->setShowSort(false);
        $recommender_system_server_built_in_debug_progressmeters->setInfo(self::plugin()->translate("recommender_system_server_built_in_debug_progressmeters_info", "",
            [
                "progressmeter_type",
                "max_width_in_pixel",
                "title",
                "max_reachable_points",
                "required_score",
                "required_score_label",
                "primary_reached_score",
                "primary_reached_score_label",
                "secondary_reached_score",
                "secondary_reached_score_label"
            ]));

        $type = new ilSelectInputGUI('progressmeter_type', 'progressmeter_type');
        $type->setOptions(['Standard', 'Mini']);
        $recommender_system_server_built_in_debug_progressmeters->addInput($type);

        $title = new ilTextInputGUI('title', 'title');
        $recommender_system_server_built_in_debug_progressmeters->addInput($title);

        $max_width_in_pixel = new ilNumberInputGUI('max_width_in_pixel', 'max_width_in_pixel');
        $recommender_system_server_built_in_debug_progressmeters->addInput($max_width_in_pixel);


        $max_reachable_score = new ilNumberInputGUI('max_reachable_score', 'max_reachable_score');
        $recommender_system_server_built_in_debug_progressmeters->addInput($max_reachable_score);

        $required_score = new ilNumberInputGUI('required_score', 'required_score');
        $recommender_system_server_built_in_debug_progressmeters->addInput($required_score);

        $required_score_label = new ilTextInputGUI('required_score_label', 'required_score_label');
        $recommender_system_server_built_in_debug_progressmeters->addInput($required_score_label);

        $primary_reached_score = new ilNumberInputGUI('primary_reached_score', 'primary_reached_score');
        $recommender_system_server_built_in_debug_progressmeters->addInput($primary_reached_score);

        $primary_reached_score_label = new ilTextInputGUI('primary_reached_score_label', 'primary_reached_score_label');
        $recommender_system_server_built_in_debug_progressmeters->addInput($primary_reached_score_label);

        $secondary_reached_score = new ilNumberInputGUI('secondary_reached_score', 'secondary_reached_score');
        $recommender_system_server_built_in_debug_progressmeters->addInput($secondary_reached_score);

        $secondary_reached_score_label = new ilTextInputGUI('secondary_reached_score_label', 'secondary_reached_score_label');
        $recommender_system_server_built_in_debug_progressmeters->addInput($secondary_reached_score_label);

        return $recommender_system_server_built_in_debug_progressmeters;
    }


    protected function getCompetencesInputGUI() : MultiLineNewInputGUI
    {
        $recommender_system_server_built_in_debug_competences = new MultiLineNewInputGUI("competences", "recommender_system_server_built_in_debug_competences");
        $recommender_system_server_built_in_debug_competences->setShowSort(false);
        $recommender_system_server_built_in_debug_competences->setInfo(self::plugin()->translate("recommender_system_server_built_in_debug_competences_info", "", ["competence_id", "skill_id"]));
        $recommender_system_server_built_in_debug_competences_competence_id = new ilNumberInputGUI("competence_id", "competence_id");
        $recommender_system_server_built_in_debug_competences_competence_id->setRequired(true);
        $recommender_system_server_built_in_debug_competences->addInput($recommender_system_server_built_in_debug_competences_competence_id);
        $recommender_system_server_built_in_debug_competences_skill_id = new ilNumberInputGUI("skill_id", "skill_id");
        $recommender_system_server_built_in_debug_competences_skill_id->setRequired(true);
        $recommender_system_server_built_in_debug_competences->addInput($recommender_system_server_built_in_debug_competences_skill_id);
        return $recommender_system_server_built_in_debug_competences;
    }
}