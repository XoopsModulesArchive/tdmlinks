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

// Include xoops admin header
include_once '../../../include/cp_header.php';

include_once(XOOPS_ROOT_PATH."/class/xoopsmodule.php");
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/tree.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

include_once("../include/functions.php");

if ( $xoopsUser ) {
    $xoopsModule = XoopsModule::getByDirname("TDMLinks");
    if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
        redirect_header(XOOPS_URL."/",3,_NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL."/",3,_NOPERM);
    exit();
}

// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', $xoopsModule->getVar('dirname', 'e'));
xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname', 'e'));

//paramètres:
// pour les images des catégories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/TDMLinks/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/TDMLinks/images/cats/';
// pour les fichiers
$uploaddir_links = XOOPS_ROOT_PATH . '/uploads/TDMLinks/links/';
$uploadurl_links = XOOPS_URL . '/uploads/TDMLinks/links/';
// pour les captures d'écran fichiers
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/TDMLinks/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/TDMLinks/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/TDMLinks/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/TDMLinks/images/field/';
/////////////

//appel des class
$linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'TDMLinks');
$links_Handler =& xoops_getModuleHandler('tdmlinks_links', 'TDMLinks');
$linksvotedata_Handler =& xoops_getModuleHandler('tdmlinks_votedata', 'TDMLinks');
$linksfield_Handler =& xoops_getModuleHandler('tdmlinks_field', 'TDMLinks');
$linksfielddata_Handler =& xoops_getModuleHandler('tdmlinks_fielddata', 'TDMLinks');
$linksbroken_Handler =& xoops_getModuleHandler('tdmlinks_broken', 'TDMLinks');
$linksmod_Handler =& xoops_getModuleHandler('tdmlinks_mod', 'TDMLinks');
$linksfieldmoddata_Handler =& xoops_getModuleHandler('tdmlinks_modfielddata', 'TDMLinks');
?>