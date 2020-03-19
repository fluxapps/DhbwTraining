<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2005 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/

/**
 * Class xdhtSettingsXmlWriter
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtSettingsXmlWriter extends ilXmlWriter
{

    var $il_obj_dhbw_training = null;


    /**
     * constructor
     *
     * @param string    xml version
     * @param string    output encoding
     * @param string    input encoding
     *
     * @access    public
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Set file target directories
     *
     * @param string    relative file target directory
     * @param string    absolute file target directory
     */
    function setFileTargetDirectories($a_rel, $a_abs)
    {
        $this->target_dir_relative = $a_rel;
        $this->target_dir_absolute = $a_abs;
    }


    function start()
    {
        global $ilDB;

        //ilUtil::makeDir($this->target_dir_absolute."/training");
        $this->il_obj_dhbw_training = ilObject2::_lookupObjectId($_GET['ref_id']);
        $query = 'SELECT * FROM rep_robj_xdht_settings 
			WHERE dhbw_training_object_id = ' . $ilDB->quote($this->il_obj_dhbw_training, 'integer');

        $res = $ilDB->query($query);

        while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
            $this->xmlStartTag("xdhtSettings", null);

            $this->xmlElement("Id", null, (int) $row->id);
            $this->xmlElement("dhbw_training_object_id", null, (int) $row->dhbw_training_object_id);
            $this->xmlElement("question_pool_id", null, $row->question_pool_id);
            $this->xmlElement("is_online", null, $row->is_online);

            $this->xmlEndTag("xdhtSettings");
        }

        return true;
    }


    function getXML()
    {
        // Replace ascii code 11 characters because of problems with xml sax parser
        return str_replace('&#11;', '', $this->xmlDumpMem(false));
    }
}

?>