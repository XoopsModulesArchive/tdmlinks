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
class tdmlinks_cat extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar("cat_cid",XOBJ_DTYPE_INT,null,false,5);
        $this->initVar("cat_pid",XOBJ_DTYPE_INT,null,false,5);
        $this->initVar("cat_title",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("cat_imgurl",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("cat_description_main",XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar("cat_weight",XOBJ_DTYPE_INT,null,false,11);
    }
    function tdmlinks_cat()
    {
        $this->__construct();
    }
    function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();
        return $new_enreg;
    }
    function getForm($action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

        //nom du formulaire selon l'action (editer ou ajouter):
        $title = $this->isNew() ? sprintf(_AM_TDMLINKS_FORMADD) : sprintf(_AM_TDMLINKS_FORMEDIT);

        //cr�ation du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new XoopsFormText(_AM_TDMLINKS_FORMTITLE, 'cat_title', 50, 255, $this->getVar('cat_title')), true);
        //editeur
      $editor_configs=array();
        $editor_configs["name"] ="cat_description_main";
        $editor_configs["value"] = $this->getVar('cat_description_main', 'e');
        $editor_configs["rows"] = 20;
        $editor_configs["cols"] = 160;
        $editor_configs["width"] = "100%";
        $editor_configs["height"] = "400px";
        $editor_configs["editor"] = $xoopsModuleConfig['editor'];
        $form->addElement( new XoopsFormEditor(_AM_TDMLINKS_FORMTEXT, "cat_description_main", $editor_configs), false);
        //image
        $linkscat_img = $this->getVar('cat_imgurl') ? $this->getVar('cat_imgurl') : 'blank.gif';
        $uploadirectory='/uploads/tdmlinks/images/cats';
        $imgtray = new XoopsFormElementTray(_AM_TDMLINKS_FORMIMG,'<br />');
        $imgpath=sprintf(_AM_TDMLINKS_FORMPATH, $uploadirectory );
        $imageselect= new XoopsFormSelect($imgpath, 'linkscat_img',$linkscat_img);
           $topics_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . $uploadirectory );
        foreach( $topics_array as $image ) {
               $imageselect->addOption("$image", $image);
        }
        $imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"linkscat_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $imgtray->addElement($imageselect,false);
        $imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $linkscat_img . "' name='image3' id='image3' alt='' />" ) );
        $fileseltray= new XoopsFormElementTray('','<br />');
        $fileseltray->addElement(new XoopsFormFile(_AM_TDMLINKS_FORMUPLOAD , 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
        $fileseltray->addElement(new XoopsFormLabel('' ), false);
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        // Pour faire une sous-cat�gorie
        $linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'tdmlinks');
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $linkscat_arr = $linkscat_Handler->getall($criteria);
        $mytree = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
        $form->addElement(new XoopsFormLabel(_AM_TDMLINKS_FORMINCAT, $mytree->makeSelBox('cat_pid', 'cat_title','--',$this->getVar('cat_pid'),true)));
        //poids de la cat�gorie
        $form->addElement(new XoopsFormText(_AM_TDMLINKS_FORMWEIGHT, 'cat_weight', 5, 5, $this->getVar('cat_weight', 'e')), true);

        //permissions
        $member_handler = & xoops_gethandler('member');
        $group_list = &$member_handler->getGroupList();
        $gperm_handler = &xoops_gethandler('groupperm');
        $full_list = array_keys($group_list);
        global $xoopsModule;
        if(!$this->isNew()) {
            $groups_ids_view = $gperm_handler->getGroupIds('tdmlinks_view', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_submit = $gperm_handler->getGroupIds('tdmlinks_submit', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_link = $gperm_handler->getGroupIds('tdmlinks_link', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_view = array_values($groups_ids_view);
            $groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_PERM_VIEW_DSC, 'groups_view[]', $groups_ids_view);
            $groups_ids_submit = array_values($groups_ids_submit);
            $groups_news_can_submit_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_PERM_SUBMIT_DSC, 'groups_submit[]', $groups_ids_submit);
            $groups_ids_link = array_values($groups_ids_link);
            $groups_news_can_link_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_PERM_LINK_DSC, 'groups_link[]', $groups_ids_link);
        } else {
            $groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_PERM_VIEW_DSC, 'groups_view[]', $full_list);
            $groups_news_can_submit_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_PERM_SUBMIT_DSC, 'groups_submit[]', $full_list);
            $groups_news_can_link_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_PERM_LINK_DSC, 'groups_link[]', $full_list);
        }
        // pour voir
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_view_checkbox);
        // pour editer
        $groups_news_can_submit_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_submit_checkbox);
        // pour t�l�charger
        if ($xoopsModuleConfig['permission_link'] == 1) {
            $groups_news_can_link_checkbox->addOptionArray($group_list);
            $form->addElement($groups_news_can_link_checkbox);
        }
  
        // pour passer "cid" si on modifie la cat�gorie
        if (!$this->isNew()) {
            $form->addElement(new XoopsFormHidden('cat_cid', $this->getVar('cat_cid')));
            $form->addElement(new XoopsFormHidden('categorie_modified', true));
        }
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save_cat'));
        //boutton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        return $form;
    }
}

class tdmlinkstdmlinks_catHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "tdmlinks_cat", 'tdmlinks_cat', 'cat_cid', 'cat_title');
    }
}
?>
