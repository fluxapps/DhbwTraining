<#1>
<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettings.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Participant/class.xdhtParticipant.php');
xdhtSettings::updateDB();
xdhtParticipant::updateDB();
?>
<#2>
<?php
require_once("./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php");

$xdht_type_id = ilDBUpdateNewObjectType::addNewType('xdht', 'copy');

$offering_admin = ilDBUpdateNewObjectType::addCustomRBACOperation( //$a_id, $a_title, $a_class, $a_pos
	'rep_robj_xdht_copy', 'copy', 'object', 280);
if($offering_admin) {
	ilDBUpdateNewObjectType::addRBACOperation($xdht_type_id, $offering_admin);
}

$offering_admin = ilDBUpdateNewObjectType::addCustomRBACOperation( //$a_id, $a_title, $a_class, $a_pos
	'rep_robj_xdht_view_learning_progress_other_users', 'view learning progress of other users', 'object', 280);
if($offering_admin) {
	ilDBUpdateNewObjectType::addRBACOperation($xdht_type_id, $offering_admin);
}

$offering_admin = ilDBUpdateNewObjectType::addCustomRBACOperation( //$a_id, $a_title, $a_class, $a_pos
	'rep_robj_xdht_edit_learning_progress_settings', 'edit learning progress settings', 'object', 280);
if($offering_admin) {
	ilDBUpdateNewObjectType::addRBACOperation($xdht_type_id, $offering_admin);
}

?>
