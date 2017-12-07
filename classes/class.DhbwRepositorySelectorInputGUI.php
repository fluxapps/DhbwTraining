<?php

require_once('./Services/Form/classes/class.ilRepositorySelectorInputGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/Facade/interface.xdhtObjectFacadeInterface.php');

/**
 * Class DhbwRepositorySelectorInputGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 * @ilCtrl_IsCalledBy DhbwRepositorySelectorInputGUI: ilFormPropertyDispatchGUI
 */

class DhbwRepositorySelectorInputGUI extends ilRepositorySelectorInputGUI {

	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;


	/**
	 * DhbwRepositorySelectorInputGUI constructor.
	 *
	 * @param string                    $a_title
	 * @param string                    $a_postvar
	 * @param xdhtObjectFacadeInterface $facade
	 */
	public function __construct($a_title = "", $a_postvar = "", xdhtObjectFacadeInterface $facade) {
		$this->facade = $facade;
		$this->setHeaderMessage($this->facade->pl()->txt('choose_question_pool_info'));
		//parent::__construct();
	}


	/**
	 * overwrittes the ctrl flow
	 */
	function render($a_mode = "property_form")
	{
		global $lng, $ilCtrl, $ilObjDataCache, $tree;

		$tpl = new ilTemplate("tpl.prop_rep_select.html", true, true, "Services/Form");

		$tpl->setVariable("POST_VAR", $this->getPostVar());
		$tpl->setVariable("ID", $this->getFieldId());
		$tpl->setVariable("PROPERTY_VALUE", ilUtil::prepareFormOutput($this->getValue()));
		$tpl->setVariable("TXT_SELECT", $this->getSelectText());
		$tpl->setVariable("TXT_RESET", $lng->txt("reset"));
		switch ($a_mode)
		{
			case "property_form":
				$parent_gui = "xdhtsettingsformgui";
				break;

			case "table_filter":
				$parent_gui = get_class($this->getParent());
				break;
		}

		$ilCtrl->setParameterByClass("ilrepositoryselectorinputgui",
			"postvar", $this->getPostVar());
		$tpl->setVariable("HREF_SELECT",
			$ilCtrl->getLinkTargetByClass(array('ilobjdhbwtraininggui', "dhbwrepositoryselectorinputgui"),
				"showRepositorySelection"));
		$tpl->setVariable("HREF_RESET",
			$ilCtrl->getLinkTargetByClass(array($parent_gui, "ilformpropertydispatchgui", "ilrepositoryselectorinputgui"),
				"reset"));

		if ($this->getValue() > 0 && $this->getValue() != ROOT_FOLDER_ID)
		{
			$tpl->setVariable("TXT_ITEM",
				$ilObjDataCache->lookupTitle($ilObjDataCache->lookupObjId($this->getValue())));
		}
		else
		{
			$nd = $tree->getNodeData(ROOT_FOLDER_ID);
			$title = $nd["title"];
			if ($title == "ILIAS")
			{
				$title = $lng->txt("repository");
			}
			if (in_array($nd["type"], $this->getClickableTypes()))
			{
				$tpl->setVariable("TXT_ITEM", $title);
			}
		}
		return $tpl->get();
	}

	/**
	 * has to be overwritten to set only question pool repository objects on the white list
	 */
	function showRepositorySelection()
	{
		global $tpl, $lng, $ilCtrl, $tree, $ilUser;

		include_once 'Services/Repository/classes/class.ilRepositorySelectorExplorerGUI.php';
		$ilCtrl->setParameter($this, "postvar", $this->getPostVar());

		ilUtil::sendInfo($this->getHeaderMessage());

		$exp = new ilRepositorySelectorExplorerGUI($this, "showRepositorySelection",
			$this, "selectRepositoryItem", "root_id");
		$exp->setTypeWhiteList(array('qpl'));
		$exp->setClickableTypes($this->getClickableTypes());

		if($this->getValue())
		{
			$exp->setPathOpen($this->getValue());
			$exp->setHighlightedNode($this->getHighlightedNode());
		}

		if ($exp->handleCommand())
		{
			return;
		}
		// build html-output
		$tpl->setContent($exp->getHTML());
	}
}