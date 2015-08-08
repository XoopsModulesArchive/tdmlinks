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
 * @copyright   Gregory Mage (Aka Mage)
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class tdmlinks_purchase extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->XoopsObject();
        $this->initVar("purchaseid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("mode", XOBJ_DTYPE_ENUM, 'unpaid', false, false, false, array('unpaid', 'paid', 'cancelled'));
        $this->initVar("lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("purchaseuid", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("purchaselinks", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("purchaseip", XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar("purchasehostname", XOBJ_DTYPE_TXTBOX, null, false, 500);
        $this->initVar("purchaseinvoiced", XOBJ_DTYPE_INT, null, false);
        $this->initVar("purchasepaid", XOBJ_DTYPE_INT, null, false);
    }

    public function tdmlinks_purchase()
    {
        $this->__construct();
    }
}

class tdmlinks_purchaseHandler extends XoopsPersistableObjectHandler
{
    public function __construct(&$db)
    {
        parent::__construct($db, "tdmlinks_purchase", 'tdmlinks_purchase', 'purchaseid', 'lid');
    }
}
