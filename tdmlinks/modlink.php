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

include_once 'header.php';
// SAR - 19/08/2011
$op = TDMLinks_CleanVars($_REQUEST, 'op', 'list', 'string');
$lid = TDMLinks_CleanVars($_REQUEST, 'lid', 0, 'int');
$url = XOOPS_URL.'/'.$GLOBALS['xoopsModuleConfig']['baseurl'].'/mod,'.$op.','.$lid.$GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI'])&&$GLOBALS['xoopsModuleConfig']['htaccess']==true&&empty($_POST)) {
	header( "HTTP/1.1 301 Moved Permanently" ); 
	header('Location: '.$url);
	exit(0);
}
// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_modlink.html';
include_once XOOPS_ROOT_PATH.'/header.php';
$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null );
//On recupere la valeur de l'argument op dans l'URL$


// redirection si pas de droit pour poster
if ($perm_modif == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

//information du téléchargement
$view_links = $links_Handler->get($lid);

// redirection si le téléchargement n'existe pas ou n'est pas activé
if (count($view_links) == 0 || $view_links->getVar('status') == 0){
    redirect_header('index.php', 3, _MD_TDMLINKS_SINGLELINK_NONEXISTENT);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op)
{
    // Vue liste
    case "list":
        //navigation
        $view_categorie = $linkscat_Handler->get($view_links->getVar('cid'));
        $categories = tdmlinks_MygetItemIds('tdmlinks_view', 'tdmlinks');
        if(!in_array($view_links->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
            exit();
        }
        //tableau des catégories
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')','IN'));
        $linkscat_arr = $linkscat_Handler->getall($criteria);
        $mytree = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = tdmlinks_PathTreeUrl($mytree, $view_links->getVar('cid'), $linkscat_arr, 'cat_title', $prefix = ' <img src="images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC', true);
        $navigation .= ' <img src="images/deco/arrow.gif" alt="arrow" /> <a title="' . $view_links->getVar('title') . '" href="singlelink.php?lid=' . $view_links->getVar('lid') . '">' . $view_links->getVar('title') . '</a>';
        $navigation .= ' <img src="images/deco/arrow.gif" alt="arrow" /> ' . _MD_TDMLINKS_SINGLELINK_MODIFY;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $pagetitle = _MD_TDMLINKS_SINGLELINK_MODIFY . ' - ' . $view_links->getVar('title') . ' - ';
        $pagetitle .= tdmlinks_PathTreeUrl($mytree, $view_links->getVar('cid'), $linkscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta( 'meta', 'description', strip_tags(_MD_TDMLINKS_SINGLELINK_MODIFY . ' (' . $view_links->getVar('title') . ')'));

        //Affichage du formulaire de notation des téléchargements
        $obj =& $linksmod_Handler->create();
        $form = $obj->getForm($lid, false, $donnee = array());
        $xoopsTpl->assign('themeForm', $form->render());
    break;
    // save
    case "save":
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $obj =& $linksmod_Handler->create();
        $erreur = false;
        $message_erreur = '';
        $donnee = array();
        $obj->setVar('title', $_POST['title']);
        $donnee['title'] = $_POST['title'];
        $obj->setVar('cid', $_POST['cid']);
        $donnee['cid'] = $_POST['cid'];
        $obj->setVar('lid', $_POST['lid']);          
        
        $obj->setVar('description', $_POST["description"]);
        $donnee['description'] = $_POST["description"];
        $obj->setVar('modifysubmitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
        
        // erreur si la catégorie est vide
        if (isset($_REQUEST['cid'])){
            if ($_REQUEST['cid'] == 0){
                $erreur=true;
                $message_erreur .= _MD_TDMLINKS_ERREUR_NOCAT . '<br>';
            }
        }
        // erreur si le captcha est faux
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !$xoopsCaptcha->verify() ) {
            $message_erreur .=$xoopsCaptcha->getMessage().'<br>';
            $erreur=true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $links_field = $linksfield_Handler->getall($criteria);
        foreach (array_keys($links_field) as $i) {
            if ($links_field[$i]->getVar('status_def') == 0){
                $nom_champ = 'champ' . $links_field[$i]->getVar('fid');
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }
        }
        if ($erreur==true){
            $xoopsTpl->assign('message_erreur', $message_erreur);
        }else{
            $obj->setVar('size', $_POST['size'] . ' ' . $_POST['type_size']);                

            if ($linksmod_Handler->insert($obj)) {
                $lid_links = $obj->get_new_enreg();
                // Récupération des champs supplémentaires:
                $criteria = new CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $links_field = $linksfield_Handler->getall($criteria);
                foreach (array_keys($links_field) as $i) {
                    if ($links_field[$i]->getVar('status_def') == 0){
                        $objdata =& $linksfieldmoddata_Handler->create();
                        $nom_champ = 'champ' . $links_field[$i]->getVar('fid');
                        $objdata->setVar('moddata', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lid_links);
                        $objdata->setVar('fid', $links_field[$i]->getVar('fid'));
                        $linksfieldmoddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
                    }
                }
                $tags = array();
                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/modified.php';
                $notification_handler =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('singlelink.php?lid=' . intval($_REQUEST['lid']), 1, _MD_TDMLINKS_MODLINK_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form =& $obj->getForm(intval($_REQUEST['lid']), true, $donnee);
        $xoopsTpl->assign('themeForm', $form->render());

    break;
}
include XOOPS_ROOT_PATH.'/footer.php';
?> 