<#1>
<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettings.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Participant/class.xdhtParticipant.php');
xdhtSettings::updateDB();
xdhtParticipant::updateDB();
?>
