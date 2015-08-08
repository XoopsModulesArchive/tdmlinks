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

include 'admin_header.php';
//On recupere la valeur de l'argument op dans l'URL$
$op = TDMLinks_CleanVars($_REQUEST, 'op', 'list', 'string');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op)
{
    // Vue liste
    case "list":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()){
            $field_admin = new ModuleAdmin();
            echo $field_admin->addNavigation('field.php');
            $field_admin->addItemButton(_AM_TDMLINKS_FIELD_NEW, 'field.php?op=new_field', 'add');
            $field_admin->addItemButton(_AM_TDMLINKS_FIELD_LIST, 'field.php?op=list', 'list');
            echo $field_admin->renderButton();
        }
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $links_field = $linksfield_Handler->getall($criteria);
        $numrows = count($links_field);
        //Affichage du tableau
        if ($numrows>0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="left">' . _AM_TDMLINKS_FORMTITLE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMLINKS_FORMIMAGE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMLINKS_FORMWEIGHT . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMLINKS_FORMAFFICHE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMLINKS_FORMAFFICHESEARCH . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMLINKS_FORMACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($links_field) as $i) {
                $linksfield_fid = $links_field[$i]->getVar('fid');
                echo '<tr class="'.$class.'">';
                echo '<td align="left">' . $links_field[$i]->getVar('title') . '</a></td>';
                echo '<td align="center" width="10%">';
                echo '<img src="' . $uploadurl_field . $links_field[$i]->getVar('img') . '" alt="" title="" height="16">';
                echo '</td>';
                echo '<td align="center" width="10%">' . $links_field[$i]->getVar('weight') . '</td>';

                echo '<td align="center" width="10%"><a href="field.php?op=update_status&fid=' . $linksfield_fid . '&aff=' . ($links_field[$i]->getVar('status') == 1 ? '0"><img src="../images/icon/on.png"></a>' : '1"><img src="../images/icon/off.png"></a>') . '</td>';
                echo '<td align="center" width="10%"><a href="field.php?op=update_search&fid=' . $linksfield_fid . '&aff=' . ($links_field[$i]->getVar('search') == 1 ? '0"><img src="../images/icon/on.png"></a>' : '1"><img src="../images/icon/off.png"></a>') . '</td>';
                echo '<td align="center" width="10%">';
                echo '<a href="field.php?op=edit_field&fid=' . $linksfield_fid . '"><img src="../images/icon/edit.png" alt="' . _AM_TDMLINKS_FORMEDIT . '" title="' . _AM_TDMLINKS_FORMEDIT . '"></a> ';
                if ($links_field[$i]->getVar('status_def') == 0){
                    echo '<a href="field.php?op=del_field&fid=' . $linksfield_fid . '"><img src="../images/icon/delete.png" alt="' . _AM_TDMLINKS_FORMDEL . '" title="' . _AM_TDMLINKS_FORMDEL . '"></a>';
                }
                echo '</td>';
                echo '</tr>';
                $class = ($class == 'even') ? 'odd' : 'even';
            }
            echo '</table>';
        }
    break;

    case "update_status":
        $obj =& $linksfield_Handler->get($_REQUEST['fid']);

        $obj->setVar('status', $_REQUEST["aff"]);
        if ($linksfield_Handler->insert($obj)) {
            redirect_header('field.php?op=list', 1, _AM_TDMLINKS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    break;

    case "update_search":
        $obj =& $linksfield_Handler->get($_REQUEST['fid']);

        $obj->setVar('search', $_REQUEST["aff"]);
        if ($linksfield_Handler->insert($obj)) {
            redirect_header('field.php?op=list', 1, _AM_TDMLINKS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    break;
    //

    // vue création
    case "new_field":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()){
            $field_admin = new ModuleAdmin();
            echo $field_admin->addNavigation('field.php');
            $field_admin->addItemButton(_AM_TDMLINKS_FIELD_LIST, 'field.php?op=list', 'list');
            echo $field_admin->renderButton();
        }
        //Affichage du formulaire de création des champs
        $obj =& $linksfield_Handler->create();
        $form = $obj->getForm();
        $form->display();
    break;

    // Pour éditer un champ
    case "edit_field":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()){
            $field_admin = new ModuleAdmin();
            echo $field_admin->addNavigation('field.php');
            $field_admin->addItemButton(_AM_TDMLINKS_FIELD_NEW, 'field.php?op=new_field', 'add');
            $field_admin->addItemButton(_AM_TDMLINKS_FIELD_LIST, 'field.php?op=list', 'list');
            echo $field_admin->renderButton();
        }
        //Affichage du formulaire de création des champs
        $obj = $linksfield_Handler->get($_REQUEST['fid']);
        $form = $obj->getForm();
        $form->display();
    break;

    // Pour supprimer un champ
    case "del_field":
        global $xoopsModule;
        $obj =& $linksfield_Handler->get($_REQUEST['fid']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // supression des entrée du champ
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('fid', $_REQUEST['fid']));
            $links_arr = $linksfielddata_Handler->getall( $criteria );
            foreach (array_keys($links_arr) as $i) {
                // supression de l'entrée
                $objlinksfielddata =& $linksfielddata_Handler->get($links_arr[$i]->getVar('iddata'));
                $linksfielddata_Handler->delete($objlinksfielddata) or $objlinks->getHtmlErrors();
            }
            if ($linksfield_Handler->delete($obj)) {
                redirect_header('field.php', 1, _AM_TDMLINKS_REDIRECT_DELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            $linksfield = $linksfield_Handler->get($_REQUEST['fid']);
            if ($linksfield->getVar('status_def') == 1){
                redirect_header('field.php', 2, _AM_TDMLINKS_REDIRECT_NODELFIELD);
            }
            $message = '';
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('fid', $_REQUEST['fid']));
            $links_arr = $linksfielddata_Handler->getall($criteria);
            if (count($links_arr) > 0) {
                $message .= _AM_TDMLINKS_DELDATA .'<br>';
                foreach (array_keys($links_arr) as $i) {
                    $message .= '<span style="color : Red">' . $links_arr[$i]->getVar('data') . '</span><br>';
                }
            }
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMLinks_checkModuleAdmin()){
                $field_admin = new ModuleAdmin();
                $field_admin->addItemButton(_AM_TDMLINKS_FIELD_NEW, 'field.php?op=new_field', 'add');
                $field_admin->addItemButton(_AM_TDMLINKS_FIELD_LIST, 'field.php?op=list', 'list');
                echo $field_admin->renderButton();
            }
            xoops_confirm(array('ok' => 1, 'fid' => $_REQUEST['fid'], 'op' => 'del_field'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMLINKS_FORMSUREDEL, $obj->getVar('title')) . '<br><br>' . $message);
        }

    break;

    // Pour sauver un champ
    case "save_field":
        if (!$GLOBALS['xoopsSecurity']->check()) {
           redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['fid'])) {
           $obj =& $linksfield_Handler->get($_REQUEST['fid']);
        } else {
           $obj =& $linksfield_Handler->create();
        }
        $erreur = false;
        $message_erreur = '';
        // Récupération des variables:
        // Pour l'image
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $uploader = new XoopsMediaUploader($uploaddir_field, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $xoopsModuleConfig['maxuploadsize'], 16, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('links_') ;
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header("javascript:history.go(-1)",3, $errors);
            } else {
                $obj->setVar('img', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('img', $_REQUEST['linksfield_img']);
        }
        // Pour les autres variables
        $obj->setVar('title', $_POST['title']);
        $obj->setVar('weight', $_POST["weight"]);
        $obj->setVar('status', $_POST["status"]);
        $obj->setVar('search', $_POST["search"]);
        $obj->setVar('status_def',  $_POST["status_def"]);

        if (intval($_REQUEST['weight'])==0 && $_REQUEST['weight'] != '0'){
            $erreur=true;
            $message_erreur = _AM_TDMLINKS_ERREUR_WEIGHT . '<br>';
        }
        if ($erreur==true){
            echo '<div class="errorMsg" style="text-align: left;">' . $message_erreur . '</div>';
        }else{
            if ($linksfield_Handler->insert($obj)) {
                redirect_header('field.php', 1, _AM_TDMLINKS_REDIRECT_SAVE);
            }
            echo $obj->getHtmlErrors();
        }
        $form =& $obj->getForm();
        $form->display();
    break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
?>