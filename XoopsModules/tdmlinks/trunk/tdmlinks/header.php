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

include "../../mainfile.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/tree.php";
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/include/functions.php';
//permission
$gperm_handler =& xoops_gethandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
xoops_loadLanguage("admin", $xoopsModule->getVar("dirname", "e"));

$perm_submit = ($gperm_handler->checkRight('tdmlinks_ac', 4, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_modif = ($gperm_handler->checkRight('tdmlinks_ac', 8, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_vote = ($gperm_handler->checkRight('tdmlinks_ac', 16, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_upload = ($gperm_handler->checkRight('tdmlinks_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_autoapprove = ($gperm_handler->checkRight('tdmlinks_ac', 64, $groups, $xoopsModule->getVar('mid'))) ? true : false ;

//paramètres:
// pour les images des catégories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/'.$xoopsModule->getVar("dirname").'/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/'.$xoopsModule->getVar("dirname").'/images/cats/';
// pour les fichiers
$uploaddir_LINKS = XOOPS_ROOT_PATH . '/uploads/'.$xoopsModule->getVar("dirname").'/links/';
$uploadurl_LINKS = XOOPS_URL . '/uploads/'.$xoopsModule->getVar("dirname").'/links/';
// pour les logos
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/'.$xoopsModule->getVar("dirname").'/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/'.$xoopsModule->getVar("dirname").'/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/'.$xoopsModule->getVar("dirname").'/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/'.$xoopsModule->getVar("dirname").'/images/field/';
/////////////

//appel des class
$linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'TDMlinks');
$links_Handler =& xoops_getModuleHandler('tdmlinks_links', 'TDMlinks');
$linksvotedata_Handler =& xoops_getModuleHandler('tdmlinks_votedata', 'TDMlinks');
$linksmod_Handler =& xoops_getModuleHandler('tdmlinks_mod', 'TDMlinks');
$linksbroken_Handler =& xoops_getModuleHandler('tdmlinks_broken', 'TDMlinks');
$linksfield_Handler =& xoops_getModuleHandler('tdmlinks_field', 'TDMlinks');
$linksfielddata_Handler =& xoops_getModuleHandler('tdmlinks_fielddata', 'TDMlinks');
$linksfieldmoddata_Handler =& xoops_getModuleHandler('tdmlinks_modfielddata', 'TDMlinks');
$linkslimit_Handler =& xoops_getModuleHandler('tdmlinks_linklimit', 'TDMlinks');
?> 