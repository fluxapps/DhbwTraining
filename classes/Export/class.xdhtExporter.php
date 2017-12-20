<?php
/**
 * Class xdhtExporter
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtExporter extends ilXmlExporter {

	public function getXmlRepresentation($a_entity, $a_schema_version, $a_id) {
		$id = explode(":", $a_id);
		$xdhtxml = new ilMD2XML($id[0], $id[1], $id[2]);
		$xdhtxml->setExportMode();
		$xdhtxml->startExport();

		return $xdhtxml->getXml();
	}


	public function init() {
		// TODO: Implement init() method.
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