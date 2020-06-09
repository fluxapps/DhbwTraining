<?php

use ILIAS\DI\Container;

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Class ilObjDhbwTrainingAccess
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class ilObjDhbwTrainingAccess extends ilObjectPluginAccess
{

    /**
     * @param null $ref_id
     * @param null $user_id
     *
     * @return bool
     */
    public static function hasReadAccess($ref_id = null, $user_id = null)
    {

        return (new self)->hasAccess('read', $ref_id, $user_id);
    }


    protected function hasAccess($permission, $ref_id = null, $user_id = null)
    {
        global $ilUser, $ilAccess;
        /**
         * @var $ilAccess ilAccessHandler
         */
        $ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
        $user_id = $user_id ? $user_id : $ilUser->getId();

        return $ilAccess->checkAccessOfUser($user_id, $permission, '', $ref_id);
    }


    /**
     * @param null $ref_id
     * @param null $user_id
     *
     * @return bool
     */
    public static function hasWriteAccess($ref_id = null, $user_id = null)
    {

        return (new self)->hasAccess('write', $ref_id, $user_id);
    }


    /**
     * @param null $ref_id
     * @param null $user_id
     *
     * @return bool
     */
    public static function hasDeleteAccess($ref_id = null, $user_id = null)
    {
        return (new self)->hasAccess('delete', $ref_id, $user_id);
    }


    /**
     * Checks wether a user may invoke a command or not
     * (this method is called by ilAccessHandler::checkAccess)
     *
     * Please do not check any preconditions handled by
     * ilConditionHandler here. Also don't do usual RBAC checks.
     *
     * @param string $a_cmd        command (not permission!)
     * @param string $a_permission permission
     * @param int    $a_ref_id     reference id
     * @param int    $a_obj_id     object id
     * @param int    $a_user_id    user id (if not provided, current user is taken)
     *
     * @return    boolean        true, if everything is ok
     */
    public function checkAccess($a_cmd, $a_permission, $a_ref_id = 0, $a_obj_id = 0, $a_user_id = '')
    {
        /**
         * @var Container
         */
        global $DIC;

        if ($a_user_id == '') {
            $a_user_id = $DIC->user()->getId();
        }
        if ($a_obj_id === null) {
            $a_obj_id = ilObject2::_lookupObjId($a_ref_id);
        }

        switch ($a_permission) {
            case 'read':
                if (!self::checkOnline($a_ref_id) && !$DIC->access()->checkAccessOfUser($a_user_id, 'read', '', $a_ref_id)) {
                    return false;
                }
                break;
        }

        return true;
    }


    /**
     * @param $a_id
     *
     * @return bool
     */
    public static function checkOnline($a_id)
    {
        $xdhtSettings = xdhtSettings::where(array("id" => $a_id))->first();
        if (!empty($xdhtSettings)) {
            return (boolean) $xdhtSettings->getIsOnline();
        }
    }
}