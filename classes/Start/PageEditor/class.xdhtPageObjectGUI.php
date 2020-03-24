<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtPageObjectGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author            Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy xdhtPageObjectGUI: xdhtStartGUI
 * @ilCtrl_Calls      xdhtPageObjectGUI: ilPageEditorGUI, ilEditClipboardGUI, ilMediaPoolTargetSelector
 * @ilCtrl_Calls      xdhtPageObjectGUI: ilPublicUserProfileGUI, ilPageObjectGUI
 */
class xdhtPageObjectGUI extends ilPageObjectGUI
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    /**
     * @var xdhtObjectFacadeInterface
     */
    protected $facade;


    /**
     * xdhtPageObjectGUI constructor
     *
     * @param xdhtObjectFacadeInterface $facade
     */
    public function __construct(xdhtObjectFacadeInterface $facade)
    {
        $this->facade = $facade;

        $this->checkAndAddCOPageDefinition();

        parent::__construct(xdhtPageObject::PARENT_TYPE, $this->facade->objectId());
    }


    /**
     *
     */
    protected function checkAndAddCOPageDefinition()/*:void*/
    {
        // for some reason the entry in copg_pobj_def gets deleted from time to time, so we check and add it everytime now
        $sql_query = self::dic()->database()->queryF('SELECT * FROM copg_pobj_def WHERE parent_type = %s', [ilDBConstants::T_TEXT], [xdhtPageObject::PARENT_TYPE]);
        if (self::dic()->database()->numRows($sql_query) === 0) {
            self::dic()->database()->insert('copg_pobj_def', [
                'parent_type' => [ilDBConstants::T_TEXT, xdhtPageObject::PARENT_TYPE],
                'class_name'  => [ilDBConstants::T_TEXT, xdhtPageObject::class],
                'directory'   => [ilDBConstants::T_TEXT, 'classes/Start/PageEditor'],
                'component'   => [ilDBConstants::T_TEXT, self::plugin()->directory()]
            ]);
        }

        // we always need a page object - create on demand
        if (!xdhtPageObject::_exists(xdhtPageObject::PARENT_TYPE, $this->facade->objectId())) {
            $page_obj = new xdhtPageObject();
            $page_obj->setId($this->facade->objectId());
            $page_obj->setParentId($this->facade->objectId());
            $page_obj->create();
        }
    }


    /**
     * @inheritDoc
     */
    public function executeCommand()/*:void*/
    {
        self::dic()->tabs()->activateSubTab(xdhtStartGUI::TAB_EDIT_PAGE);

        if (!self::dic()->access()->checkAccess("write", "", $this->facade->refId())) {
            ilUtil::sendFailure(self::plugin()->translate('permission_denied'));

            self::output()->output("", true);

            return;
        }

        $html = strval(parent::executeCommand());

        $this->fixTabs();

        self::output()->output($html, true);
    }


    /**
     *
     */
    protected function fixTabs()/*:void*/
    {
        // these are automatically rendered by the pageobject gui
        self::dic()->tabs()->removeTab('edit');
        self::dic()->tabs()->removeTab('history');
        self::dic()->tabs()->removeTab('clipboard');
        self::dic()->tabs()->removeTab('pg');
    }


    /**
     * @inheritDoc
     */
    public function getHTML() : string
    {
        $html = strval(parent::getHTML());

        $this->fixTabs();

        return $html;
    }
}
