<#1>
<?php

use srag\DIC\DhbwTraining\DICStatic;
use srag\Plugins\DhbwTraining\Config\Config;

xdhtSettings::updateDB();
xdhtParticipant::updateDB();
?>
<#2>
<?php
xdhtSettings::updateDB();
?>
<#3>
<?php
xdhtSettings::updateDB();
?>
<#4>
<?php
xdhtSettings::updateDB();
?>
<#5>
<?php
DICStatic::dic()->database()->modifyTableColumn('copg_pobj_def', 'component', ['length' => 120]);
?>
<#6>
<?php
xdhtSettings::updateDB();
?>
<#7>
<?php
xdhtSettings::updateDB();
Config::updateDB();
Config::initDefaultSalt();
?>
