<#1>
<?php
\xdhtSettings::updateDB();
\xdhtParticipant::updateDB();
?>
<#2>
<?php
\xdhtSettings::updateDB();
?>
<#3>
<?php
\xdhtSettings::updateDB();
?>
<#4>
<?php
\xdhtSettings::updateDB();
?>
<#5>
<?php
\srag\DIC\DhbwTraining\DICStatic::dic()->database()->modifyTableColumn('copg_pobj_def', 'component', ['length' => 120]);
?>
<#6>
<?php
\xdhtSettings::updateDB();
?>
<#7>
<?php
\xdhtSettings::updateDB();
\srag\Plugins\DhbwTraining\Config\Config::updateDB();
\srag\Plugins\DhbwTraining\Config\Config::initDefaultSalt();
?>
<#8>
<?php
\xdhtSettings::updateDB();
?>
<#9>
<?php
\xdhtSettings::updateDB();
?>
