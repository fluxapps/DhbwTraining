<?php

/**
 * Class xdhtExporter
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class ilDhbwTrainingExporter extends ilXmlExporter {


	public function init() {
		// TODO: Implement init() method.
	}

	public function getXmlRepresentation($a_entity, $a_schema_version, $a_id) {

		$xml = '';

		$writer = new xdhtSettingsXmlWriter();
		//ilUtil::makeDirParents($this->getAbsoluteExportDirectory());
		//$writer->setFileTargetDirectories($this->getRelativeExportDirectory(), $this->getAbsoluteExportDirectory());
		$writer->start();
		$xml .= $writer->getXml();

		return $xml;
	}

	public function getValidSchemaVersions($a_entity) {
		return array (
			"5.2.0" => array(
				"namespace" => "http://www.ilias.de/Modules/HTMLLearningModule/htlm/5_2",
				"xsd_file" => "ilias_htlm_5_2.xsd",
				"uses_dataset" => true,
				"min" => "5.2.0",
				"max" => "")
		);
	}
}