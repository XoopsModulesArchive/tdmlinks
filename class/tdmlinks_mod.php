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

class tdmlinks_mod extends XoopsObject
{
    // constructor
    public function __construct()
    {
        parent::__construct();
        $this->initVar("requestid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("cid", XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar("title", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("url", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("logourl", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("description", XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar("modifysubmitter", XOBJ_DTYPE_INT, null, false, 11);
    }

    public function tdmlinks_mod($lid)
    {
        $this->__construct();
    }

    public function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();

        return $new_enreg;
    }

    public function getForm($lid, $erreur, $donnee = array(), $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gperm_handler =& xoops_gethandler('groupperm');
        $perm_upload   = ($gperm_handler->checkRight('tdmlinks_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false;
        //appel des class
        $links_Handler    =& xoops_getModuleHandler('tdmlinks_LINKS', 'tdmlinks');
        $linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'tdmlinks');

        $view_links = $links_Handler->get($lid);
        include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");

        // affectation des variables
        if ($erreur == true) {
            $d_title       = $donnee['title'];
            $d_cid         = $donnee['cid'];
            $d_description = $donnee['description'];
        } else {
            $d_title       = $view_links->getVar('title');
            $d_cid         = $view_links->getVar('cid');
            $d_description = $view_links->getVar('description', 'e');
        }

        //nom du formulaire
        $title = sprintf(_AM_TDMLINKS_FORMEDIT);

        //cr�ation du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new XoopsFormText(_AM_TDMLINKS_FORMTITLE, 'title', 50, 255, $d_title), true);
        $url     = $view_links->getVar('url');
        $formurl = new XoopsFormText(_AM_TDMLINKS_FORMURL, 'url', 75, 255, $url);
        $form->addElement($formurl);

        //cat�gorie
        $linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'tdmlinks');
        $categories       = tdmlinks_MygetItemIds('tdmlinks_submit', 'tdmlinks');
        $criteria         = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        if ($xoopsUser) {
            if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
            }
        } else {
            $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        }
        $linkscat_arr = $linkscat_Handler->getall($criteria);
        if (count($linkscat_arr) == 0) {
            redirect_header('index.php', 2, _NOPERM);
        }
        $mytree = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
        $form->addElement(new XoopsFormLabel(_AM_TDMLINKS_FORMINCAT, $mytree->makeSelBox('cid', 'cat_title', '--', $d_cid, true)), true);

        //description
        $editor_configs           = array();
        $editor_configs["name"]   = "description";
        $editor_configs["value"]  = $d_description;
        $editor_configs["rows"]   = 20;
        $editor_configs["cols"]   = 60;
        $editor_configs["width"]  = "100%";
        $editor_configs["height"] = "400px";
        $editor_configs["editor"] = $xoopsModuleConfig['editor'];
        $form->addElement(new XoopsFormEditor(_AM_TDMLINKS_FORMTEXTLINKS, "description", $editor_configs), true);
        //image
        if ($xoopsModuleConfig['useshots']) {
            $uploaddir      = XOOPS_ROOT_PATH . '/uploads/tdmlinks/images/shots/' . $view_links->getVar('logourl');
            $linkscat_img   = $view_links->getVar('logourl') ? $view_links->getVar('logourl') : 'blank.gif';
            $uploadirectory = '/uploads/tdmlinks/images/shots';
            if (!is_file($uploaddir)) {
                $linkscat_img = 'blank.gif';
            }
            $imgtray      = new XoopsFormElementTray(_AM_TDMLINKS_FORMIMG, '<br />');
            $imgpath      = sprintf(_AM_TDMLINKS_FORMPATH, $uploadirectory);
            $imageselect  = new XoopsFormSelect($imgpath, 'logo_img', $linkscat_img);
            $topics_array = XoopsLists:: getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);
            foreach ($topics_array as $image) {
                $imageselect->addOption("$image", $image);
            }
            $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"logo_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
            $imgtray->addElement($imageselect, false);
            $imgtray->addElement(new XoopsFormLabel('', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $linkscat_img . "' name='image3' id='image3' alt='' />"));
            $fileseltray = new XoopsFormElementTray('', '<br />');
            if ($perm_upload == true) {
                $fileseltray->addElement(new XoopsFormFile(_AM_TDMLINKS_FORMUPLOAD, 'attachedimage', $xoopsModuleConfig['maxuploadsize']), false);
            }
            $imgtray->addElement($fileseltray);
            $form->addElement($imgtray);
        }
        $form->addElement(new XoopsFormCaptcha(), true);
        $form->addElement(new XoopsFormHidden('lid', $lid));
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save'));
        //bouton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

class tdmlinkstdmlinks_modHandler extends XoopsPersistableObjectHandler
{
    public function __construct(&$db)
    {
        parent::__construct($db, "tdmlinks_mod", 'tdmlinks_mod', 'requestid', 'lid');
    }
}
