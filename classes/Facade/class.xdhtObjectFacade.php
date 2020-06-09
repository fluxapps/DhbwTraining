<?php

/**
 * Class ilObjDhbwTrainingFacade
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtObjectFacade implements xdhtObjectFacadeInterface
{

    /**
     * @var xdhtObjectFacadeInterface
     */
    protected static $instance;
    /**
     * @var ilObjDhbwTraining
     */
    public $training_object;
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
     * @var xdhtParticipantFactoryInterface
     */
    protected $xdht_participant_factory;
    /**
     * @var ilDhbwTrainingPlugin
     */
    private $pl;
    /**
     * @var xdhtSettingsInterface
     */
    private $settings;


    /**
     * ilObjDhbwTrainingFacade constructor.
     *
     * @param ilObjDhbwTraining $object
     */
    public function __construct(ilObjDhbwTraining $object)
    {

        $this->ref_id = $object->getRefId();
        $this->object_id = $object->getId();

        if (!$this->object_id || !$this->ref_id) {
            throw new LogicException("We need a ref_id and object_id of the ilObjDhbwTraining");
        }

        $this->settings = (new xdhtSettingFactory())->findOrGetInstanceByObjId($this->object_id);
        $this->training_object = $object;

        $this->xdht_settings_factory = new xdhtSettingFactory();
        $this->xdht_question_pool_factory = new xdhtQuestionPoolFactory();
        $this->xdht_question_factory = new xdhtQuestionFactory();
        $this->xdht_participant_factory = new xdhtParticipantFactory();
    }


    /**
     * @inheritdoc
     */
    public static function getInstance($ref_id)
    {
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
    public function settings()
    {
        return $this->settings;
    }


    /**
     * @inheritdoc
     */
    public function objectId()
    {
        return $this->object_id;
    }


    /**
     * @inheritdoc
     */
    public function refId()
    {
        return $this->ref_id;
    }


    /**
     * @inheritdoc
     */
    public function training_object()
    {
        return $this->training_object;
    }


    /**
     * @inheritdoc
     */
    public function xdhtSettingsFactory()
    {
        return $this->xdht_settings_factory;
    }


    /**
     * @inheritdoc
     */
    public function xdhtQuestionPoolFactory()
    {
        return $this->xdht_question_pool_factory;
    }


    /**
     * @inheritdoc
     */
    public function xdhtQuestionFactory()
    {
        return $this->xdht_question_factory;
    }


    /**
     * @inheritdoc
     */
    public function xdhtParticipantFactory()
    {
        return $this->xdht_participant_factory;
    }
}