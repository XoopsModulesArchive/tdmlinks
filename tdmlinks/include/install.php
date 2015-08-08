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

function xoops_module_install_tdmlinks() {
    global $xoopsModule, $xoopsConfig, $xoopsDB;

    $namemodule = "tdmlinks";
    if( file_exists(XOOPS_ROOT_PATH."/modules/".$namemodule."/language/".$xoopsConfig['language']."/admin.php") ) {
        include_once(XOOPS_ROOT_PATH."/modules/".$namemodule."/language/".$xoopsConfig['language']."/admin.php");
    } else {
        include_once(XOOPS_ROOT_PATH."/modules/".$namemodule."/language/english/admin.php");
    }

    //Creation du fichier ".$namemodule."/
    $dir = XOOPS_ROOT_PATH."/uploads/".$namemodule."";
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/
    $dir = XOOPS_ROOT_PATH."/uploads/".$namemodule."/images";
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/cat
    $dir = XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/cats";
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/shots
    $dir = XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/shots";
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/field
    $dir = XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/field";
    if(!is_dir($dir))
        mkdir($dir, 0777);
        chmod($dir, 0777);

    //Copie des index.html
    $indexFile = XOOPS_ROOT_PATH."/modules/".$namemodule."/include/index.html";
    copy($indexFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/index.html");
    copy($indexFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/index.html");
    copy($indexFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/cats/index.html");
    copy($indexFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/shots/index.html");
    copy($indexFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/field/index.html");

    //Copie des blank.gif
    $blankFile = XOOPS_ROOT_PATH."/modules/".$namemodule."/images/blank.gif";
    copy($blankFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/cats/blank.gif");
    copy($blankFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/shots/blank.gif");
    copy($blankFile, XOOPS_ROOT_PATH."/uploads/".$namemodule."/images/field/blank.gif");
    
    return true;
}
?>