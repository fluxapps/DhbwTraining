<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtExportGUI
 *
 * @author       : Benjamin Seglias   <bs@studer-raimann.ch>
 *
 * @ilCtrl_Calls xdhtExportGUI:
 */
class xdhtExportGUI extends ilExportGUI
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const CMD_INDEX = 'buildExportTableGUI';


    public function __construct($a_parent_gui, $a_main_obj = null)
    {
        global $ilPluginAdmin;

        parent::__construct($a_parent_gui, $a_main_obj);

        $this->addFormat('xml', $a_parent_gui->lng->txt('ass_create_export_file'));
        $pl_names = $ilPluginAdmin->getActivePluginsForSlot(IL_COMP_PLUGIN, 'xdht', 'xdhtexp');
        foreach ($pl_names as $pl) {
            /**
             * @var $plugin ilTestExportPlugin
             */
            $plugin = ilPluginAdmin::getPluginObject(IL_COMP_PLUGIN, 'DhbwTraining', 'xdht', $pl);
            $this->addFormat(
                $plugin->getFormat(),
                $plugin->getFormatLabel(),
                $plugin,
                'export'
            );
        }
    }


    public function executeCommand()
    {
        parent::executeCommand(); // TODO: Change the autogenerated stub
        self::dic()->ui()->mainTemplate()->printToStdout();
    }


    public function download()
    {
        /**
         * @var $lng    ilLanguage
         * @var $ilCtrl ilCtrl
         */
        global $lng, $ilCtrl;

        if (isset($_GET['file']) && $_GET['file']) {
            $_POST['file'] = array($_GET['file']);
        }

        if (!isset($_POST['file'])) {
            ilUtil::sendInfo($lng->txt('no_checkbox'), true);
            $ilCtrl->redirect($this, 'listExportFiles');
        }

        if (count($_POST['file']) > 1) {
            ilUtil::sendInfo($lng->txt('select_max_one_item'), true);
            $ilCtrl->redirect($this, 'listExportFiles');
        }

        $filename = basename($_POST["file"][0]);
        $exportFile = $this->getExportDirectory() . '/' . $filename;

        if (file_exists($exportFile)) {
            ilUtil::deliverFile($exportFile, $filename);
        }

        $ilCtrl->redirect($this, 'listExportFiles');
    }


    /**
     * Get the location of the export directory for the xdhtSetting
     *
     * @access    public
     */
    function getExportDirectory()
    {
        $export_dir = ilUtil::getDataDir() . "/xdht_data" . "/xdht_" . $this->obj->getId() . "/export";

        return $export_dir;
    }


    /**
     * Delete files
     */
    public function delete()
    {
        /**
         * @var $lng    ilLanguage
         * @var $ilCtrl ilCtrl
         */
        global $lng, $ilCtrl;

        $export_dir = $this->getExportDirectory();
        foreach ($_POST['file'] as $file) {
            $file = basename($file);
            $dir = substr($file, 0, strlen($file) - 4);

            if (!strlen($file) || !strlen($dir)) {
                continue;
            }

            $exp_file = $export_dir . '/' . $file;
            $exp_dir = $export_dir . '/' . $dir;
            if (@is_file($exp_file)) {
                unlink($exp_file);
            }
            if (@is_dir($exp_dir)) {
                ilUtil::delDir($exp_dir);
            }
        }
        ilUtil::sendSuccess($lng->txt('msg_deleted_export_files'), true);
        $ilCtrl->redirect($this, 'listExportFiles');
    }


    /**
     * @return xdhtExportTableGUI
     */
    protected function buildExportTableGUI()
    {
        $table = new xdhtExportTableGUI($this, 'listExportFiles', $this->obj);

        return $table;
    }
}