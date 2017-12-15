<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Participant/class.xdhtParticipant.php');
require_once('./Services/Table/classes/class.ilTable2GUI.php');
require_once('./Services/Tracking/classes/class.ilLPStatus.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/LearningProgress/class.LearningProgressStatusRepresentation.php');
require_once('./Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php');

/**
 * Class xdhtParticipantTableGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtParticipantTableGUI extends ilTable2GUI {
	use xdhtDIC;

	const TBL_ID = 'tbl_xdht_participants';
	/**
	 * @var array
	 */
	protected $filter = [];
	/**
	 * @var xdhtParticipantGUI
	 */
	protected $parent_obj;
	/**
	 * @var xdhtParticipant
	 */
	public $xdht_participant;
	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected $facade;


	/**
	 * ilLocationDataTableGUI constructor.
	 *
	 * @param xdhtParticipantGUI $a_parent_obj
	 */
	function __construct($a_parent_obj, $a_parent_cmd, xdhtObjectFacadeInterface $facade) {
		$this->parent_obj = $a_parent_obj;
		$this->facade = $facade;

		$this->setId(self::TBL_ID);
		$this->setPrefix(self::TBL_ID);
		$this->setFormName(self::TBL_ID);
		$this->ctrl()->saveParameter($a_parent_obj, $this->getNavParameter());
		$this->xdht_participant = new xdhtParticipant($_GET[xdhtParticipantGUI::PARTICIPANT_IDENTIFIER]);

		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setRowTemplate("tpl.participants.html", "Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining");

		$this->setFormAction($this->ctrl()->getFormActionByClass(xdhtParticipantGUI::class));
		$this->setExternalSorting(true);

		$this->setDefaultOrderField("full_name");
		$this->setDefaultOrderDirection("asc");
		$this->setExternalSegmentation(true);
		$this->setEnableHeader(true);

		$this->initColums();
		$this->addFilterItems();
		$this->parseData();
	}


	protected function addFilterItems() {
		$participant_name = new ilTextInputGUI($this->pl()->txt('participant_name'), 'full_name');
		$this->addAndReadFilterItem($participant_name);

		$usr_name = new ilTextInputGUI($this->pl()->txt('usr_name'), 'login');
		$this->addAndReadFilterItem($usr_name);

		include_once("./Services/Form/classes/class.ilSelectInputGUI.php");
/*		$option[0] = $this->pl()->txt('not_attempted');
		$option[1] = $this->pl()->txt('in_progress');
		$option[2] = $this->pl()->txt('edited');*/

		$status = new ilSelectInputGUI($this->pl()->txt("learning_progress"), "status");
		//$status->setOptions($option);
		$status->setOptions(LearningProgressStatusRepresentation::getDropdownDataLocalized($this->pl()));
		$this->addAndReadFilterItem($status);
	}


	/**
	 * @param $item
	 */
	protected function addAndReadFilterItem(ilFormPropertyGUI $item) {
		$this->addFilterItem($item);
		$item->readFromSession();
		if ($item instanceof ilCheckboxInputGUI) {
			$this->filter[$item->getPostVar()] = $item->getChecked();
		} else {
			$this->filter[$item->getPostVar()] = $item->getValue();
		}
	}

	protected function getUserDataById($usr_id) {
		global $ilDB;
		$q = "SELECT * FROM usr_data WHERE usr_id = " . $ilDB->quote($usr_id, "integer");
		$usr_set = $ilDB->query($q);
		$usr_rec = $ilDB->fetchAssoc($usr_set);

		return $usr_rec;
	}

	/**
	 * @param array $a_set
	 */
	public function fillRow($a_set) {
		/**
		 * @var xdhtParticipant $xdhtParticipant
		 */
		$xdhtParticipant = xdhtParticipant::find($a_set['id']);

		$usr_data = $this->getUserDataById($xdhtParticipant->getUsrId());

		if ($this->isColumnSelected('full_name')) {
			$this->tpl->setCurrentBlock("PARTICIPANT_NAME");
			$this->tpl->setVariable('PARTICIPANT_NAME', $usr_data['firstname'] . " " . $usr_data['lastname']);
			$this->tpl->parseCurrentBlock();
		}

		if ($this->isColumnSelected('login')) {
			$this->tpl->setCurrentBlock("USR_NAME");
			$this->tpl->setVariable('USR_NAME', $usr_data['login']);
			$this->tpl->parseCurrentBlock();
		}
		if ($this->isColumnSelected('status')) {
			$this->tpl->setCurrentBlock("LEARNING_PROGRESS");
			$this->tpl->setVariable('LP_STATUS_ALT', LearningProgressStatusRepresentation::statusToRepr($xdhtParticipant->getStatus()));
			$this->tpl->setVariable('LP_STATUS_PATH', LearningProgressStatusRepresentation::getStatusImage($xdhtParticipant->getStatus()));
			$this->tpl->parseCurrentBlock();
		}

		if ($this->isColumnSelected('created')) {
			$this->tpl->setCurrentBlock("CREATED");
			$this->tpl->setVariable('CREATED', ilDatePresentation::formatDate(new ilDateTime($xdhtParticipant->getCreated(),IL_CAL_DATETIME)));
			$this->tpl->parseCurrentBlock();
		}

		if ($this->isColumnSelected('last_access')) {
			$this->tpl->setCurrentBlock("LAST_ACCESS");
			$this->tpl->setVariable('LAST_ACCESS', ilDatePresentation::formatDate(new ilDateTime($xdhtParticipant->getLastAccess(),IL_CAL_DATETIME)));
			$this->tpl->parseCurrentBlock();
		}
	}

	protected function initColums() {

		$number_of_selected_columns = count($this->getSelectedColumns());
		$column_width = 100 / $number_of_selected_columns . '%';

		$all_cols = $this->getSelectableColumns();
		foreach ($this->getSelectedColumns() as $col) {

			$this->addColumn($all_cols[$col]['txt'], "$col", $column_width);

		}
	}

	protected function parseData() {
		$this->determineOffsetAndOrder();
		$this->determineLimit();

		$collection = xdhtParticipant::getCollection();
		$collection->where(array( 'training_obj_id' => $this->facade->settings()->getDhbwTrainingObjectId() ));
		$collection->leftjoin('usr_data', 'usr_id', 'usr_id');

		$sorting_column = $this->getOrderField() ? $this->getOrderField() : 'full_name';
		$offset = $this->getOffset() ? $this->getOffset() : 0;

		$sorting_direction = $this->getOrderDirection();
		$num = $this->getLimit();

		$collection->orderBy($sorting_column, $sorting_direction);
		$collection->limit($offset, $num);

		foreach ($this->filter as $filter_key => $filter_value) {
			switch ($filter_key) {
				case 'full_name':
				case 'login':
					$collection->where(array( $filter_key => '%' . $filter_value . '%' ), 'LIKE');
					break;
				case 'status':
					if (!empty($filter_value)) {
						$filter_value = LearningProgressStatusRepresentation::mappProgrStatusToLPStatus($filter_value);
						$collection->where(array( $filter_key => $filter_value ), '=');
						break;
					}
			}
		}
		$this->setData($collection->getArray());
	}


	public function getSelectableColumns() {
		$cols["full_name"] = array(
			"txt" => $this->pl()->txt("participant_name"),
			"default" => true
		);
		$cols["login"] = array(
			"txt" => $this->pl()->txt("usr_name"),
			"default" => true
		);
		$cols["status"] = array(
			"txt" => $this->pl()->txt("learning_progress"),
			"default" => true
		);
		$cols["created"] = array(
			"txt" => $this->pl()->txt("first_access"),
			"default" => true
		);
		$cols["last_access"] = array(
			"txt" => $this->pl()->txt("last_access"),
			"default" => true
		);

		return $cols;
	}

}