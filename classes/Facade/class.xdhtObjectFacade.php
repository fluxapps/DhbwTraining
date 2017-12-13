<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilDhbwTrainingPlugin.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTraining.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettings.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/Facade/interface.xdhtObjectFacadeInterface.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettingFactory.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/QuestionPool/class.xdhtQuestionPoolFactory.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Question/class.xdhtQuestionFactory.php');

/**
 * Class ilObjDhbwTrainingFacade
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtObjectFacade implements xdhtObjectFacadeInterface {

	/**
	 * @var ilObjDhbwTraining
	 */
	public $training_object;
	/**
	 * @var ilDhbwTrainingPlugin
	 */
	private $pl;
	/**
	 * @var xdhtSettingsInterface
	 */
	private $settings;
	/**
	 * @var \ILIAS\DI\Container
	 */
	private $dic;
	/**
	 * @var xdhtObjectFacadeInterface
	 */
	protected static $instance;
	/**
	 * @var int
	 */
	protected $object_id;
	/**
	 * @var int
	 */
	protected $ref_id;
	/**
	 * @var xdhtSettingFactoryInterface
	 */
	protected $xdht_settings_factory;
	/**
	 * @var xdhtQuestionPoolFactoryInterface
	 */
	protected $xdht_question_pool_factory;
	/**
	 * @var xdhtQuestionFactoryInterface
	 */
	protected $xdht_question_factory;
	/**
	 * @var ilObjDhbwTrainingAccess
	 */
	protected $access;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;


	/**
	 * ilObjDhbwTrainingFacade constructor.
	 *
	 * @param ilObjDhbwTraining $object
	 */
	public function __construct(ilObjDhbwTraining $object) {
		global $DIC;

		$this->ref_id = $object->getRefId();
		$this->object_id = $object->getId();

		if (!$this->object_id || !$this->ref_id) {
			throw new LogicException("We need a ref_id and object_id of the ilObjDhbwTraining");
		}

		$this->pl = ilDhbwTrainingPlugin::getInstance();
		$this->dic = $DIC;
		$this->tpl = $this->dic['tpl'];
		$this->access = new ilObjDhbwTrainingAccess();

		$this->settings = (new xdhtSettingFactory())->findOrGetInstanceByObjId($this->object_id);
		$this->training_object = $object;

		$this->xdht_settings_factory = new xdhtSettingFactory();
		$this->xdht_question_pool_factory = new xdhtQuestionPoolFactory();
		$this->xdht_question_factory = new xdhtQuestionFactory();
	}


	/**
	 * @inheritdoc
	 */
	public static function getInstance($ref_id) {
		if (!isset(self::$instance)) {
			// $object = ilObjectFactory::getInstanceByRefId($ref_id);

			if (ilObject::_lookupType($ref_id, true) == ilDhbwTrainingPlugin::PLUGIN_PREFIX) {
					$il_object = new ilObjDhbwTraining($ref_id);
			} else {
				$il_object = new ilObjDhbwTraining();
			}

			self::$instance = new self($il_object);
		}

		return self::$instance;
	}


	/**
	 * @inheritdoc
	 */
	public function settings() {
		return $this->settings;
	}


	/**
	 * @inheritdoc
	 */
	public function dic() {
		return $this->dic;
	}


	/**
	 * @inheritdoc
	 */
	public function ui() {
		return $this->dic()->ui()->mainTemplate();
	}


	/**
	 * @inheritdoc
	 */
	public function user() {
		return $this->dic()->user();
	}


	/**
	 * @inheritdoc
	 */
	public function ctrl() {
		return $this->dic()->ctrl();
	}


	/**
	 * @inheritdoc
	 */
	public function pl() {
		return $this->pl;
	}


	/**
	 * @inheritdoc
	 */
	public function objectId() {
		return $this->object_id;
	}


	/**
	 * @inheritdoc
	 */
	public function refId() {
		return $this->ref_id;
	}


	/**
	 * @inheritdoc
	 */
	public function training_object() {
		return $this->training_object;
	}


	/**
	 * @inheritdoc
	 */
	public function xdhtSettingsFactory() {
		return $this->xdht_settings_factory;
	}


	/**
	 * @inheritdoc
	 */
	public function xdhtQuestionPoolFactory() {
		return $this->xdht_question_pool_factory;
	}


	/**
	 * @inheritdoc
	 */
	public function xdhtQuestionFactory() {
		return $this->xdht_question_factory;
	}


	/**
	 * @inheritdoc
	 */
	public function access() {
		return $this->access;
	}


	public function tpl() {
		return $this->tpl;
	}
}