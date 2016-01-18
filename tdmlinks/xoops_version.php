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

//defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

$moduleDirName = basename(__DIR__);

// ------------------- Informations ------------------- //
$modversion = array(
    'name'                => _MI_TDMLINKS_NAME,
    'description'         => _MI_TDMLINKS_DESC,
    'official'            => 0, //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'G. Mage',
    'nickname'            => 'Mage',
    'author_mail'         => 'author-email',
    'author_website_url'  => 'http://xoops.org',
    'author_website_name' => 'XOOPS',
    'credits'             => 'XOOPS Development Team, G. Mage, Mamba, Wishcraft, Timgno',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    //
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . "/modules/$moduleDirName/docs/changelog file",
    //
    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . "/modules/$moduleDirName/docs/install.txt",
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.7.2',
    'min_admin'           => '1.1',
    'min_db'              => array('mysql' => '5.0.7', 'mysqli' => '5.0.7'),
    // images
    'image'               => 'assets/images/module_logo.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    //Frameworks
    'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
    'sysicons16'          => 'Frameworks/moduleclasses/icons/16',
    'sysicons32'          => 'Frameworks/moduleclasses/icons/32',
    // Local path icons
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    //About
    'version'             => 1.62,
    'module_status'       => 'Beta 2',
    'release_date'        => '2015/08/08', //yyyy/mm/dd
    //    'release'             => '2015-04-04',
    'demo_site_url'       => 'http://www.xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'http://xoops.org/modules/newbb',
    'support_name'        => 'Support Forum',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // paypal
    //    'paypal' => array(
    //        'business'      => 'XXX@email.com',
    //        'item_name'     => 'Donation : ' . _AM_MODULE_DESC,
    //        'amount'        => 0,
    //        'currency_code' => 'USD'),

    // Admin system menu
    'system_menu'         => 1,
    // Admin menu
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // Main menu
    'hasMain'             => 1,
    //Search & Comments
    'hasSearch'           => 1,
    'search'              => array(
        'file' => 'include/search.inc.php',
        'func' => $moduleDirName . '_search'
    ),
    'hasComments'         => 1,
    'comments'            => array(
        'pageName'    => 'singlefile.php',
        'itemName'    => 'lid',
        'extraParams' => array(
            'cid',
            'callbackFile' => 'include/comment_functions.php',
            'callback'     => array(
                'approve' => $moduleDirName . '_com_approve',
                'update'  => $moduleDirName . '_com_update'
            )
        )
    ),
    // Install/Update
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php'
    //  'onUninstall'         => 'include/onuninstall.php'

);

// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'] = array(
    $moduleDirName . '_' . 'broken',
    $moduleDirName . '_' . 'cat',
    $moduleDirName . '_' . 'links',
    $moduleDirName . '_' . 'mod',
    $moduleDirName . '_' . 'votedata',
    $moduleDirName . '_' . 'field',
    $moduleDirName . '_' . 'fielddata',
    $moduleDirName . '_' . 'modfielddata',
    $moduleDirName . '_' . 'linklimit',
    $moduleDirName . '_' . 'purchase'
);

// ------------------- Blocks ------------------- //
//blocks should or don't have have hardcoded numbers?

$modversion['blocks'][] = array(
    'file'        => 'tdmlinks_top.php',
    'name'        => _MI_TDMLINKS_BNAME1,
    'description' => _MI_TDMLINKS_BNAMEDSC1,
    'show_func'   => 'b_tdmlinks_top_show',
    'edit_func'   => 'b_tdmlinks_top_edit',
    'template'    => 'tdmlinks_block_new.tpl',
    'can_clone'   => true,
    'options'     => 'date|10|19|1|1|1|left|90|0'
);

$modversion['blocks'][] = array(
    'file'        => 'tdmlinks_top.php',
    'name'        => _MI_TDMLINKS_BNAME2,
    'description' => _MI_TDMLINKS_BNAMEDSC2,
    'show_func'   => 'b_tdmlinks_top_show',
    'edit_func'   => 'b_tdmlinks_top_edit',
    'template'    => 'tdmlinks_block_top.tpl',
    'can_clone'   => true,
    'options'     => 'hits|10|19|1|1|1|left|90|0'
);

$modversion['blocks'][] = array(
    'file'        => 'tdmlinks_top.php',
    'name'        => _MI_TDMLINKS_BNAME3,
    'description' => _MI_TDMLINKS_BNAMEDSC3,
    'show_func'   => 'b_tdmlinks_top_show',
    'edit_func'   => 'b_tdmlinks_top_edit',
    'template'    => 'tdmlinks_block_rating.tpl',
    'can_clone'   => true,
    'options'     => 'rating|10|19|1|1|1|left|90|0'
);

$modversion['blocks'][] = array(
    'file'        => 'tdmlinks_top.php',
    'name'        => _MI_TDMLINKS_BNAME4,
    'description' => _MI_TDMLINKS_BNAMEDSC4,
    'show_func'   => 'b_tdmlinks_top_show',
    'edit_func'   => 'b_tdmlinks_top_edit',
    'template'    => 'tdmlinks_block_random.tpl',
    'can_clone'   => true,
    'options'     => 'random|10|19|1|1|1|left|90|0'
);

$modversion['blocks'][] = array(
    'file'        => 'tdmlinks_search.php',
    'name'        => _MI_TDMLINKS_BNAME5,
    'description' => _MI_TDMLINKS_BNAMEDSC5,
    'show_func'   => 'b_tdmlinks_search_show',
    'edit_func'   => '',
    'template'    => 'tdmlinks_block_search.tpl',
    'options'     => ''
);

// Menu
$modversion['sub'][] = array(
    array('name' => _MI_TDMLINKS_SMNAME1, 'url' => 'submit.php'),
    array('name' => _MI_TDMLINKS_SMNAME2, 'url' => 'search.php')
);

// ------------------- Templates ------------------- //
$modversion['templates'] = array(
    array('file' => 'tdmlinks_brokenlink.tpl', 'description' => ''),
    array('file' => 'tdmlinks_link.tpl', 'description' => ''),
    array('file' => 'tdmlinks_index.tpl', 'description' => ''),
    array('file' => 'tdmlinks_modlink.tpl', 'description' => ''),
    array('file' => 'tdmlinks_ratelink.tpl', 'description' => ''),
    array('file' => 'tdmlinks_singlelink.tpl', 'description' => ''),
    array('file' => 'tdmlinks_submit.tpl', 'description' => ''),
    array('file' => 'tdmlinks_viewcat.tpl', 'description' => ''),
    array('file' => 'tdmlinks_liste.tpl', 'description' => ''),
    array('file' => 'tdmlinks_rss.tpl', 'description' => '')
);

// ------------------- Config Options ------------------- //
//$modversion['config'][] = array(
//    'name'        => 'keywords',
//    'title'       => '_MI_THEMEADMIN_KEYWORDS',
//    'description' => '_MI_THEMEADMIN_KEYWORDS_DESC',
//    'formtype'    => 'textbox',
//    'valuetype'   => 'text',
//    'default'     => 'themeadmin, General, topmenu'
//);

$i                                       = 1;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_GENERAL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
$i++;
$modversion['config'][$i]['name']        = 'popular';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_POPULAR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 100;
$i++;
$modversion['config'][$i]['name']        = 'autosummary';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_AUTO_SUMMARY';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
$modversion['config'][$i]['name']        = 'showupdated';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_SHOW_UPDATED';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'useshots';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_USESHOTS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'shotwidth';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_SHOTWIDTH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 90;
$i++;
$modversion['config'][$i]['name']        = 'img_float';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_IMGFLOAT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'left';
$modversion['config'][$i]['options']     = array(_MI_TDMLINKS_IMGFLOAT_LEFT => 'left', _MI_TDMLINKS_IMGFLOAT_RIGHT => 'Aaright');
$i++;
$modversion['config'][$i]['name']        = 'platform';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PLATEFORM';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_PLATEFORM_DSC';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'None| Other';
$i++;
$modversion['config'][$i]['name']        = 'usetellafriend';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_USETELLAFRIEND';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_USETELLAFRIENDDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
$modversion['config'][$i]['name']        = 'usetag';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_USETAG';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_USETAGDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$modversion['config'][$i]['name']        = 'editor';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_FORM_OPTIONS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'dhtmltextarea';
$modversion['config'][$i]['options']     = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor');
$modversion['config'][$i]['category']    = 'global';
$i++;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_USER';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
$i++;
$modversion['config'][$i]['name']        = 'perpage';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PERPAGE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
$i++;
$modversion['config'][$i]['name']        = 'nb_dowcol';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_NBDOWCOL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5);
$i++;
$modversion['config'][$i]['name']        = 'newlinks';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_NEWDLS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
$i++;
$modversion['config'][$i]['name']        = 'toporder';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_TOPORDER';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = array(
    '_MI_TDMLINKS_TOPORDER1' => 1,
    '_MI_TDMLINKS_TOPORDER2' => 2,
    '_MI_TDMLINKS_TOPORDER3' => 3,
    '_MI_TDMLINKS_TOPORDER4' => 4,
    '_MI_TDMLINKS_TOPORDER5' => 5,
    '_MI_TDMLINKS_TOPORDER6' => 6,
    '_MI_TDMLINKS_TOPORDER7' => 7,
    '_MI_TDMLINKS_TOPORDER8' => 8
);
$i++;
$modversion['config'][$i]['name']        = 'perpageliste';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PERPAGELISTE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 15;
$i++;
$modversion['config'][$i]['name']        = 'searchorder';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_SEARCHORDER';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 8;
$modversion['config'][$i]['options']     = array(
    '_MI_TDMLINKS_TOPORDER1' => 1,
    '_MI_TDMLINKS_TOPORDER2' => 2,
    '_MI_TDMLINKS_TOPORDER3' => 3,
    '_MI_TDMLINKS_TOPORDER4' => 4,
    '_MI_TDMLINKS_TOPORDER5' => 5,
    '_MI_TDMLINKS_TOPORDER6' => 6,
    '_MI_TDMLINKS_TOPORDER7' => 7,
    '_MI_TDMLINKS_TOPORDER8' => 8
);
$i++;
$modversion['config'][$i]['name']        = 'nbsouscat';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_SUBCATPARENT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 5;
$i++;
$modversion['config'][$i]['name']        = 'nb_catcol';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_NBCATCOL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 3;
$modversion['config'][$i]['options']     = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5);
$i++;
$modversion['config'][$i]['name']        = 'bldate';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_BLDATE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'blpop';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_BLPOP';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'blrating';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_BLRATING';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'nbbl';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_NBBL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 5;
$i++;
$modversion['config'][$i]['name']        = 'longbl';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LONGBL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 20;
$i++;
$modversion['config'][$i]['name']        = 'shwo_bookmark';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_BOOKMARK';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_BOOKMARK_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'show_social';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_SOCIAL';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_SOCIAL_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'link_float';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LINKFLOAT';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_LINKFLOAT_DSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'ltr';
$modversion['config'][$i]['options']     = array(_MI_TDMLINKS_LINKFLOAT_LTR => 'ltr', _MI_TDMLINKS_LINKFLOAT_RTL => 'rtl');
$i++;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_ADMIN';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
$i++;
$modversion['config'][$i]['name']        = 'perpageadmin';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PERPAGEADMIN';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 15;
$i++;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_LINKS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
$i++;
$modversion['config'][$i]['name']        = 'permission_link';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PERMISSIONLINK';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = array('_MI_TDMLINKS_PERMISSIONLINK1' => 1, '_MI_TDMLINKS_PERMISSIONLINK2' => 2);
$i++;
$modversion['config'][$i]['name']        = 'newnamelink';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LINK_NAME';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_LINK_NAMEDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$i++;
$modversion['config'][$i]['name']        = 'prefixlinks';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LINK_PREFIX';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_LINK_PREFIXDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'links_';
$i++;
$modversion['config'][$i]['name']        = 'maxuploadsize';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_MAXUPLOAD_SIZE';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1048576;
$i++;
$modversion['config'][$i]['name']        = 'mimetype';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_MIMETYPE';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_MIMETYPE_DSC';
$modversion['config'][$i]['formtype']    = 'select_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['options']     = include_once XOOPS_ROOT_PATH . '/include/mimetypes.inc.php';
$modversion['config'][$i]['default']     = array('image/gif', 'image/jpeg', 'image/png');
$i++;
$modversion['config'][$i]['name']        = 'check_host';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_CHECKHOST';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
$xoops_url                               = parse_url(XOOPS_URL);
$modversion['config'][$i]['name']        = 'referers';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_REFERERS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = array($xoops_url['host']);
$i++;
$modversion['config'][$i]['name']        = 'downlimit';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_DOWNLIMIT';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_DOWNLIMITDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
$modversion['config'][$i]['name']        = 'limitglobal';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LIMITGLOBAL';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_LIMITGLOBALDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
$i++;
$modversion['config'][$i]['name']        = 'limitlid';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LIMITLID';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_LIMITLIDDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 2;
$i++;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_PAYPAL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
$i++;
$modversion['config'][$i]['name']        = 'use_paypal';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_USEPAYPAL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;
$i++;
$modversion['config'][$i]['name']        = 'currency_paypal';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_CURRENCYPAYPAL';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'EUR';
$modversion['config'][$i]['options']     = include_once XOOPS_ROOT_PATH . '/include/currency.php';
$i++;
$modversion['config'][$i]['name']        = 'image_paypal';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_IMAGEPAYPAL';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_IMAGEPAYPALDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'https://www.paypal.com/en_EN/EN/i/btn/btn_donateCC_LG.gif';
$i++;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_RSS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
$i++;
$modversion['config'][$i]['name']        = 'perpagerss';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PERPAGERSS';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_PERPAGERSSDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
$i++;
$modversion['config'][$i]['name']        = 'timecacherss';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_TIMECACHERSS';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_TIMECACHERSSDSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 60;
$i++;
$modversion['config'][$i]['name']        = 'logorss';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_LOGORSS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '/modules/tdmlinks/assets/images/mydl_slogo.png';
$i++;
$modversion['config'][$i]['name']        = 'break' . $i;
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PREFERENCE_BREAK_COMNOTI';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'line_break';
$modversion['config'][$i]['valuetype']   = 'textbox';
$modversion['config'][$i]['default']     = 'head';
// SAR - 18/08/2011
$i++;
$modversion['config'][$i]['name']        = 'xpayment';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_XPAYMENT';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_XPAYMENT_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = false;
$i++;
$modversion['config'][$i]['name']        = 'purchaseable_LINKS';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_PURCHASABLE_LINKS';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_PURCHASABLE_LINKS_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 15;
$i++;
$modversion['config'][$i]['name']        = 'htaccess';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_HTACCESS';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_HTACCESS_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = false;
$i++;
$modversion['config'][$i]['name']        = 'baseurl';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_BASEURL';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_BASEURL_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'directory';
$i++;
$modversion['config'][$i]['name']        = 'endofurl';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_ENDOFURL';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_ENDOFURL_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '.html';
$i++;
$modversion['config'][$i]['name']        = 'endofurl_rss';
$modversion['config'][$i]['title']       = '_MI_TDMLINKS_ENDOFURL_RSS';
$modversion['config'][$i]['description'] = '_MI_TDMLINKS_ENDOFURL_RSS_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '.rss';

// Notifications
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'tdmlinks_notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_TDMLINKS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_TDMLINKS_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php', 'viewcat.php', 'singlefile.php');

$modversion['notification']['category'][2]['name']           = 'category';
$modversion['notification']['category'][2]['title']          = _MI_TDMLINKS_CATEGORY_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_TDMLINKS_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('viewcat.php', 'singlefile.php');
$modversion['notification']['category'][2]['item_name']      = 'cid';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name']           = 'file';
$modversion['notification']['category'][3]['title']          = _MI_TDMLINKS_FILE_NOTIFY;
$modversion['notification']['category'][3]['description']    = _MI_TDMLINKS_FILE_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = 'singlefile.php';
$modversion['notification']['category'][3]['item_name']      = 'lid';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name']          = 'new_category';
$modversion['notification']['event'][1]['category']      = 'global';
$modversion['notification']['event'][1]['title']         = _MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][1]['mail_subject']  = _MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFYSBJ;

$modversion['notification']['event'][2]['name']          = 'file_modify';
$modversion['notification']['event'][2]['category']      = 'global';
$modversion['notification']['event'][2]['admin_only']    = 1;
$modversion['notification']['event'][2]['title']         = _MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFYCAP;
$modversion['notification']['event'][2]['description']   = _MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'global_filemodify_notify';
$modversion['notification']['event'][2]['mail_subject']  = _MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFYSBJ;

$modversion['notification']['event'][3]['name']          = 'file_broken';
$modversion['notification']['event'][3]['category']      = 'global';
$modversion['notification']['event'][3]['admin_only']    = 1;
$modversion['notification']['event'][3]['title']         = _MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFY;
$modversion['notification']['event'][3]['caption']       = _MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFYCAP;
$modversion['notification']['event'][3]['description']   = _MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_filebroken_notify';
$modversion['notification']['event'][3]['mail_subject']  = _MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFYSBJ;

$modversion['notification']['event'][4]['name']          = 'file_submit';
$modversion['notification']['event'][4]['category']      = 'global';
$modversion['notification']['event'][4]['admin_only']    = 1;
$modversion['notification']['event'][4]['title']         = _MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFY;
$modversion['notification']['event'][4]['caption']       = _MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFYCAP;
$modversion['notification']['event'][4]['description']   = _MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'global_filesubmit_notify';
$modversion['notification']['event'][4]['mail_subject']  = _MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][5]['name']          = 'new_file';
$modversion['notification']['event'][5]['category']      = 'global';
$modversion['notification']['event'][5]['title']         = _MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFY;
$modversion['notification']['event'][5]['caption']       = _MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFYCAP;
$modversion['notification']['event'][5]['description']   = _MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'global_newfile_notify';
$modversion['notification']['event'][5]['mail_subject']  = _MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFYSBJ;

$modversion['notification']['event'][6]['name']          = 'file_submit';
$modversion['notification']['event'][6]['category']      = 'category';
$modversion['notification']['event'][6]['admin_only']    = 1;
$modversion['notification']['event'][6]['title']         = _MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFY;
$modversion['notification']['event'][6]['caption']       = _MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFYCAP;
$modversion['notification']['event'][6]['description']   = _MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFYDSC;
$modversion['notification']['event'][6]['mail_template'] = 'category_filesubmit_notify';
$modversion['notification']['event'][6]['mail_subject']  = _MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][7]['name']          = 'new_file';
$modversion['notification']['event'][7]['category']      = 'category';
$modversion['notification']['event'][7]['title']         = _MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFY;
$modversion['notification']['event'][7]['caption']       = _MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFYCAP;
$modversion['notification']['event'][7]['description']   = _MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFYDSC;
$modversion['notification']['event'][7]['mail_template'] = 'category_newfile_notify';
$modversion['notification']['event'][7]['mail_subject']  = _MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFYSBJ;

$modversion['notification']['event'][8]['name']          = 'approve';
$modversion['notification']['event'][8]['category']      = 'file';
$modversion['notification']['event'][8]['invisible']     = 1;
$modversion['notification']['event'][8]['title']         = _MI_TDMLINKS_FILE_APPROVE_NOTIFY;
$modversion['notification']['event'][8]['caption']       = _MI_TDMLINKS_FILE_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][8]['description']   = _MI_TDMLINKS_FILE_APPROVE_NOTIFYDSC;
$modversion['notification']['event'][8]['mail_template'] = 'file_approve_notify';
$modversion['notification']['event'][8]['mail_subject']  = _MI_TDMLINKS_FILE_APPROVE_NOTIFYSBJ;
