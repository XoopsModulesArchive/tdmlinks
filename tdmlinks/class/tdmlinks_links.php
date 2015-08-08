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

class tdmlinks_links extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar("lid",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("cid",XOBJ_DTYPE_INT,null,false,5);
        $this->initVar("title",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("url",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("homepage",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("version",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("size",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("platform",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("description",XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar("logourl",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("submitter",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("status",XOBJ_DTYPE_INT,null,false,2);
        $this->initVar("date",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("hits",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("rating",XOBJ_DTYPE_OTHER,null,false,10);
        $this->initVar("votes",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("comments",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("top",XOBJ_DTYPE_INT,null,false,2);
        $this->initVar("paypal",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("price",XOBJ_DTYPE_DECIMAL,0,false);
		$this->initVar("currency",XOBJ_DTYPE_TXTBOX,'USD',false,3);
		$this->initVar("catelogue_number",XOBJ_DTYPE_TXTBOX,'',false,10);
		
        //pour les jointures:
        $this->initVar("cat_title",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("cat_imgurl",XOBJ_DTYPE_TXTBOX, null, false);
    }
    function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();
        return $new_enreg;
    }
    function tdmlinks_links()
    {
        $this->__construct();
    }
    function getForm($donnee = array(), $erreur = false, $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        //permission pour uploader
        $gperm_handler =& xoops_gethandler('groupperm');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        if ($xoopsUser) {
            if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
                $perm_upload = ($gperm_handler->checkRight('tdmlinks_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
            }else{
                $perm_upload = true;
            }
        }else{
            $perm_upload = ($gperm_handler->checkRight('tdmlinks_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
        }
        //nom du formulaire selon l'action (editer ou ajouter):
        $title = $this->isNew() ? sprintf(_AM_TDMLINKS_FORMADD) : sprintf(_AM_TDMLINKS_FORMEDIT);

        //création du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new XoopsFormText(_AM_TDMLINKS_FORMTITLE, 'title', 50, 255, $this->getVar('title')), true);
        // link        
        $url = $this->isNew() ? 'http://' : $this->getVar('url');
        $form->addElement( new XoopsFormText(_AM_TDMLINKS_FORMURL, 'url', 75, 255, $url), true);           

        //catégorie
        $linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'tdmlinks');
        $categories = tdmlinks_MygetItemIds('tdmlinks_submit', 'tdmlinks');
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        if ($xoopsUser) {
            if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
                $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')','IN'));
            }
        }else{
            $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')','IN'));
        }
        $linkscat_arr = $linkscat_Handler->getall($criteria);
        if (count($linkscat_arr) == 0){
            redirect_header('index.php', 2,  _NOPERM);
        }
        $mytree = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
        $form->addElement(new XoopsFormLabel(_AM_TDMLINKS_FORMINCAT, $mytree->makeSelBox('cid', 'cat_title','--',$this->getVar('cid'),true)), true);

        //description
        $editor_configs=array();
        $editor_configs["name"] ="description";
        $editor_configs["value"] = $this->getVar('description', 'e');
        $editor_configs["rows"] = 20;
        $editor_configs["cols"] = 100;
        $editor_configs["width"] = "100%";
        $editor_configs["height"] = "400px";
        $editor_configs["editor"] = $xoopsModuleConfig['editor'];
        $form->addElement( new XoopsFormEditor(_AM_TDMLINKS_FORMTEXTLINKS, "description", $editor_configs), true);
        //tag
        if (is_dir('../../tag') || is_dir('../tag')){
            $dir_tag_ok = True;
        }else{
           $dir_tag_ok = False;
        }
        if (($xoopsModuleConfig['usetag'] == 1) and $dir_tag_ok){
            $tagId = $this->isNew() ? 0 : $this->getVar('lid');
            if ($erreur == true) {
                $tagId = $donnee['TAG'];
            }
            require_once XOOPS_ROOT_PATH.'/modules/tag/include/formtag.php';
            $form->addElement(new XoopsFormTag('tag', 60, 255, $tagId, 0));
        }

        //image
        if ( $xoopsModuleConfig['useshots']){
            $uploaddir = XOOPS_ROOT_PATH . '/uploads/tdmlinks/images/shots/' . $this->getVar('logourl');
            $linkscat_img = $this->getVar('logourl') ? $this->getVar('logourl') : 'blank.gif';
            if (!is_file($uploaddir)){
                $linkscat_img = 'blank.gif';
            }
            $uploadirectory='/uploads/tdmlinks/images/shots';
            $imgtray = new XoopsFormElementTray(_AM_TDMLINKS_FORMIMG,'<br />');
            $imgpath=sprintf(_AM_TDMLINKS_FORMPATH, $uploadirectory );
            $imageselect= new XoopsFormSelect($imgpath, 'logo_img',$linkscat_img);
            $topics_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . $uploadirectory );
            foreach( $topics_array as $image ) {
                $imageselect->addOption("$image", $image);
            }
            $imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"logo_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
            $imgtray->addElement($imageselect,false);
            $imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $linkscat_img . "' name='image3' id='image3' alt='' />" ) );
            $fileseltray= new XoopsFormElementTray('','<br />');
            if ($perm_upload == true) {
                $fileseltray->addElement(new XoopsFormFile(_AM_TDMLINKS_FORMUPLOAD , 'attachedimage', $xoopsModuleConfig['maxuploadsize']), false);
            }
            $imgtray->addElement($fileseltray);
            $form->addElement($imgtray);
        }
        // pour changer de poster et pour ne pas mettre à jour la date:

        if ($xoopsUser) {
            if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
                // auteur
                if ($this->isNew()) {
                    $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
                    $donnee['date_update'] = 0;
                }else{
                    $submitter = $this->getVar('submitter');
                    $v_date = $this->getVar('date');
                }
                if ($erreur == true) {
                    $date_update = $donnee['date_update'];
                    $v_status = $donnee['status'];
                    $submitter = $donnee['submitter'];
                }else{
                    $date_update = 'N';
                    $v_status = 1;
                }
                $form->addElement(new XoopsFormSelectUser(_AM_TDMLINKS_FORMSUBMITTER, 'submitter', true, $submitter, 1, false), true);
                // date
                if (!$this->isNew()) {
                    $selection_date = new XoopsFormElementTray(_AM_TDMLINKS_FORMDATEUPDATE);
                    $date = new XoopsFormRadio('', 'date_update', $date_update);
                    $options = array('N' =>_AM_TDMLINKS_FORMDATEUPDATE_NO . ' (' . formatTimestamp($v_date,'s') . ')', 'Y' => _AM_TDMLINKS_FORMDATEUPDATE_YES);
                    $date->addOptionArray($options);
                    $selection_date->addElement($date);
                    $selection_date->addElement(new XoopsFormTextDateSelect('', 'date', '', strtotime(formatTimestamp(time()))));
                    $form->addElement($selection_date);
                }
                $status = new XoopsFormCheckBox(_AM_TDMLINKS_FORMSTATUS, 'status', $v_status);
                $status->addOption(1, _AM_TDMLINKS_FORMSTATUS_OK);
                $form->addElement($status);
                //permissions pour télécharger
                if ($xoopsModuleConfig['permission_link'] == 2) {
                    $member_handler = & xoops_gethandler('member');
                    $group_list = &$member_handler->getGroupList();
                    $gperm_handler = &xoops_gethandler('groupperm');
                    $full_list = array_keys($group_list);
                    global $xoopsModule;
                    if(!$this->isNew()) {
                        $item_ids_link = $gperm_handler->getGroupIds('tdmlinks_link_item', $this->getVar('lid'), $xoopsModule->getVar('mid'));
                        $item_ids_downloa = array_values($item_ids_link);
                        $item_news_can_link_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_FORMPERMLINK, 'item_link[]', $item_ids_link);
                    } else {
                        $item_news_can_link_checkbox = new XoopsFormCheckBox(_AM_TDMLINKS_FORMPERMLINK, 'item_link[]', $full_list);
                    }
                    $item_news_can_link_checkbox->addOptionArray($group_list);
                    $form->addElement($item_news_can_link_checkbox);
                }
            }
        }
        //paypal
        if ($xoopsModuleConfig['use_paypal'] == true && $xoopsModuleConfig['xpayment'] == false ){
            $form->addElement(new XoopsFormText(_AM_TDMLINKS_FORMPAYPAL, 'paypal', 50, 255, $this->getVar('paypal')), false);
        }else{
            $form->addElement(new XoopsFormHidden('paypal', ''));
        }
        if ($xoopsModuleConfig['xpayment'] == true && $xoopsModuleConfig['use_paypal'] == false ){
            $form->addElement(new XoopsFormText(_AM_TDMLINKS_PRICE, 'price', 25, 15, $this->getVar('price')), false);
            $form->addElement(new XoopsFormText(_AM_TDMLINKS_CURRENCY, 'currency', 3, 5, $this->getVar('currency')), false);
            $form->addElement(new XoopsFormText(_AM_TDMLINKS_CATELOGUENUMBER, 'catelogue_number', 25, 10, $this->getVar('catelogue_number')), true);
        }else{
            $form->addElement(new XoopsFormHidden('price', $this->getVar('price')), false);
            $form->addElement(new XoopsFormHidden('currency', $this->getVar('currency')), false);
            $form->addElement(new XoopsFormHidden('catelogue_number', $this->getVar('catelogue_number')), false);
        }
        
        // captcha
        $form->addElement(new XoopsFormCaptcha(), true);
        // pour passer "lid" si on modifie la catégorie
        if (!$this->isNew()) {
            $form->addElement(new XoopsFormHidden('lid', $this->getVar('lid')));
            $form->addElement(new XoopsFormHidden('links_modified', true));
        }
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save_links'));
        //bouton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        return $form;
    }
}

class tdmlinkstdmlinks_linksHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "tdmlinks_links", 'tdmlinks_links', 'lid', 'title');
    }
}
?>
