<?php
/**
 * TDMlink
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   Gregory Mage (Aka Mage) and Hossein Azizabadi (Aka voltan)
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage) and Hossein Azizabadi (Aka voltan)
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class tdmlinks_linklimit extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->XoopsObject();
        $this->initVar("linklimit_id", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("linklimit_lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("linklimit_uid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("linklimit_hostname", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("linklimit_date", XOBJ_DTYPE_INT, null, false, 10);
    }

    public function tdmlinks_linklimit()
    {
        $this->__construct();
    }
}

class tdmlinkstdmlinks_linklimitHandler extends XoopsPersistableObjectHandler
{
    public function __construct(&$db)
    {
        parent::__construct($db, "tdmlinks_linklimit", 'tdmlinks_linklimit', 'linklimit_id', 'linklimit_lid');
    }
}
