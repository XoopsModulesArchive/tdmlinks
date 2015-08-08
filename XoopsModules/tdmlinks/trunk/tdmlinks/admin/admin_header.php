<?php
/**
 * TDMLink
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

$moduleDirName = basename(dirname(__DIR__));

// Include xoops admin header
include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
include_once $GLOBALS['xoops']->path('www/include/cp_header.php');

include_once(XOOPS_ROOT_PATH . '/kernel/module.php');
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

include_once('../include/functions.php');

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    exit();
}

// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', $xoopsModule->getVar('dirname', 'e'));
xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname', 'e'));

//parameters:
// for images of categories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/tdmlinks/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/tdmlinks/images/cats/';
// for links
$uploaddir_links = XOOPS_ROOT_PATH . '/uploads/tdmlinks/links/';
$uploadurl_links = XOOPS_URL . '/uploads/tdmlinks/links/';
// for screenshots
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/tdmlinks/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/tdmlinks/images/shots/';
// for field images:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/tdmlinks/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/tdmlinks/images/field/';
/////////////

//class handlers
$linkscat_Handler          =& xoops_getModuleHandler('tdmlinks_cat', $moduleDirName);
$links_Handler             =& xoops_getModuleHandler('tdmlinks_links', $moduleDirName);
$linksvotedata_Handler     =& xoops_getModuleHandler('tdmlinks_votedata', $moduleDirName);
$linksfield_Handler        =& xoops_getModuleHandler('tdmlinks_field', $moduleDirName);
$linksfielddata_Handler    =& xoops_getModuleHandler('tdmlinks_fielddata', $moduleDirName);
$linksbroken_Handler       =& xoops_getModuleHandler('tdmlinks_broken', $moduleDirName);
$linksmod_Handler          =& xoops_getModuleHandler('tdmlinks_mod', $moduleDirName);
$linksfieldmoddata_Handler =& xoops_getModuleHandler('tdmlinks_modfielddata', $moduleDirName);
