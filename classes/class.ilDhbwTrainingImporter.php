<?php
/**
 * Class ilDhbwTrainingImporter
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class ilDhbwTrainingImporter extends ilXmlImporter
{

    public function importXmlRepresentation($a_entity, $a_id, $a_xml, $a_mapping)
    {
        $this->createTrainingSettingFromXMLString($a_xml);
    }


    /**
     * @param $data
     */
    protected function createTrainingSettingFromXMLString($data)
    {
        $this->createTrainingSettingFromXMLData(simplexml_load_string($data));
    }


    /**
     * @param SimpleXMLElement $data
     */
    protected function createTrainingSettingFromXMLData(SimpleXMLElement $data)
    {
        global $DIC;
        $tree = $DIC->repositoryTree();
        $ilObjDhbwTraining = new ilObjDhbwTraining();
        $old_training_data = $this->findTrainingObjectById($data->dhbw_training_object_id->__toString());
        $ilObjDhbwTraining->setType($old_training_data['type']);
        $ilObjDhbwTraining->setTitle($old_training_data['title']);
        $ilObjDhbwTraining->setDescription($old_training_data['description']);
        $ilObjDhbwTraining->setOwner($old_training_data['owner']);
        $ilObjDhbwTraining->create_date = $old_training_data['create_date'];
        $ilObjDhbwTraining->last_update = $old_training_data['last_update'];
        $new_training_id = $ilObjDhbwTraining->create();
        $ilObjDhbwTraining->createReference();
        $ilObjDhbwTraining->putInTree($_GET['ref_id']);
        $new_xdht_setting = new xdhtSettings();
        $new_xdht_setting->setDhbwTrainingObjectId($new_training_id);
        $new_xdht_setting->setQuestionPoolId($data->question_pool_id->__toString());
        $new_xdht_setting->setIsOnline($data->is_online->__toString());
        $new_xdht_setting->store();
        $DIC->ctrl()->redirectByClass(ilObjRootFolderGUI::class, 'render');
    }


    protected function findTrainingObjectById($dhbw_training_object_id)
    {
        global $ilDB;
        $q = "SELECT * FROM object_data WHERE obj_id = " . $ilDB->quote($dhbw_training_object_id, "integer");
        $obj_set = $ilDB->query($q);
        $obj_rec = $ilDB->fetchAssoc($obj_set);

        return $obj_rec;
    }
}