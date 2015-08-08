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

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

//$path = dirname(dirname(dirname(__DIR__)));
//include_once $path . '/mainfile.php';

$moduleDirName = basename(dirname(__DIR__));

$moduleHandler = & xoops_gethandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$pathIcon32    = '../../' . $module->getInfo('sysicons32');
xoops_loadLanguage('modinfo', $module->dirname());

$xoopsModuleAdminPath = XOOPS_ROOT_PATH . '/' . $module->getInfo('dirmoduleadmin');
if (!file_exists($fileinc = $xoopsModuleAdminPath . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $xoopsModuleAdminPath . '/language/english/main.php';
}
include_once $fileinc;

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => $pathIcon32 . '/category.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU3,
    'link'  => 'admin/links.php',
    'icon'  => 'assets/images/admin/addlink.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU4,
    'link'  => 'admin/broken.php',
    'icon'  => 'assets/images/admin/broken.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU5,
    'link'  => 'admin/modified.php',
    'icon'  => 'assets/images/admin/modified.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU6,
    'link'  => 'admin/field.php',
    'icon'  => 'assets/images/admin/field.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU7,
    'link'  => 'admin/import.php',
    'icon'  => 'assets/images/admin/import.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMLINKS_ADMENU8,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32 . '/permissions.png'
);

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
);
