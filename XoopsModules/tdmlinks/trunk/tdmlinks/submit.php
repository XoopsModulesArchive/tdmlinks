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
$op  = TDMLinks_CleanVars($_REQUEST, 'op', 'list', 'string');
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['baseurl'] . '/submit,' . $op . $GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI']) && $GLOBALS['xoopsModuleConfig']['htaccess'] == true && empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $url);
    exit(0);
}
// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_submit.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);
//On recupere la valeur de l'argument op dans l'URL$

// redirection si pas de droit pour poster
if ($perm_submit == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case "list":
        //navigation
        $navigation = _MD_TDMLINKS_SUBMIT_PROPOSER;
        $xoopsTpl->assign('navigation', $navigation);
        // r�f�rencement
        // titre de la page
        $titre = _MD_TDMLINKS_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
        $titre .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $titre);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMLINKS_SUBMIT_PROPOSER));

        //Affichage du formulaire de notation des t�l�chargements
        $obj  =& $links_Handler->create();
        $form = $obj->getForm($donnee = array(), false);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case "save_links":
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $obj            =& $links_Handler->create();
        $erreur         = false;
        $message_erreur = '';
        $donnee         = array();
        $obj->setVar('title', $_POST['title']);
        $donnee['title'] = $_POST['title'];
        $obj->setVar('cid', $_POST['cid']);
        $donnee['cid'] = $_POST['cid'];

        $obj->setVar('paypal', $_POST['paypal']);
        // SAR 19-08-2011
        $obj->setVar('price', $_POST['price']);
        $obj->setVar('currency', $_POST['currency']);
        $obj->setVar('catelogue_number', $_POST['catelogue_number']);
        if (isset($_POST['platform'])) {
            $obj->setVar('platform', implode('|', $_POST['platform']));
        }
        $obj->setVar('description', $_POST['description']);
        if (isset($_POST['submitter'])) {
            $obj->setVar('submitter', $_POST['submitter']);
            $donnee['submitter'] = $_POST['submitter'];
        } else {
            $obj->setVar('submitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
            $donnee['submitter'] = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
        }
        $obj->setVar('date', time());
        if ($perm_autoapprove == true) {
            $obj->setVar('status', 1);
        } else {
            $obj->setVar('status', 0);
        }
        if ($xoopsUser) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                if (isset($_POST['status'])) {
                    $obj->setVar('status', $_POST['status']);
                    $donnee['status'] = $_POST['status'];
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            }
        }
        $donnee['date_update'] = 0;
        // erreur si la cat�gorie est vide
        if (isset($_REQUEST['cid'])) {
            if ($_REQUEST['cid'] == 0) {
                $erreur = true;
                $message_erreur .= _MD_TDMLINKS_ERREUR_NOCAT . '<br>';
            }
        }
        // erreur si le captcha est faux
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur = true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $links_field = $linksfield_Handler->getall($criteria);
        foreach (array_keys($links_field) as $i) {
            if ($links_field[$i]->getVar('status_def') == 0) {
                $nom_champ          = 'champ' . $links_field[$i]->getVar('fid');
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }
        }
        // enregistrement temporaire des tags
        if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))) {
            $donnee['TAG'] = $_POST['tag'];
        }
        if ($erreur == true) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            $obj->setVar('size', $_POST['size'] . ' ' . $_POST['type_size']);
            if ($links_Handler->insert($obj)) {
                $lid_links = $obj->get_new_enreg();
                //tags
                if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))) {
                    $tag_handler = xoops_getmodulehandler('tag', 'tag');
                    $tag_handler->updateByItem($_POST['tag'], $lid_links, $xoopsModule->getVar('dirname'), 0);
                }
                // R�cup�ration des champs suppl�mentaires:
                $criteria = new CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $links_field = $linksfield_Handler->getall($criteria);
                foreach (array_keys($links_field) as $i) {
                    if ($links_field[$i]->getVar('status_def') == 0) {
                        $objdata   =& $linksfielddata_Handler->create();
                        $nom_champ = 'champ' . $links_field[$i]->getVar('fid');
                        $objdata->setVar('data', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lid_links);
                        $objdata->setVar('fid', $links_field[$i]->getVar('fid'));
                        $linksfielddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
                    }
                }
                if ($xoopsUser) {
                    if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                        //permission pour t�l�charger
                        if ($xoopsModuleConfig['permission_link'] == 1) {
                            $gperm_handler = &xoops_gethandler('groupperm');
                            $criteria      = new CriteriaCompo();
                            $criteria->add(new Criteria('gperm_itemid', $lid_links, '='));
                            $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                            $criteria->add(new Criteria('gperm_name', 'tdmlinks_link_item', '='));
                            $gperm_handler->deleteAll($criteria);
                            if (isset($_POST['item_link'])) {
                                foreach ($_POST['item_link'] as $onegroup_id) {
                                    $gperm_handler->addRight('tdmlinks_LINK_item', $lid_links, $onegroup_id, $xoopsModule->getVar('mid'));
                                }
                            }
                        }
                    }
                }
                $notification_handler  =& xoops_gethandler('notification');
                $tags                  = array();
                $tags['LINK_NAME']     = $donnee['title'];
                $tags['LINK_URL']      = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $donnee['cid'] . '&lid=' . $lid_links;
                $linkscat_cat          = $linkscat_Handler->get($donnee['cid']);
                $tags['CATEGORY_NAME'] = $linkscat_cat->getVar('cat_title');
                $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $donnee['cid'];

                if ($perm_autoapprove == true) {
                    $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
                    $notification_handler->triggerEvent('category', $donnee['cid'], 'new_file', $tags);
                    redirect_header('index.php', 2, _MD_TDMLINKS_SUBMIT_RECEIVED . '<br />' . _MD_TDMLINKS_SUBMIT_ISAPPROVED . '');
                    exit;
                } else {
                    $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewlinks';
                    $notification_handler->triggerEvent('global', 0, 'file_submit', $tags);
                    $notification_handler->triggerEvent('category', $donnee['cid'], 'file_submit', $tags);
                    redirect_header('index.php', 2, _MD_TDMLINKS_SUBMIT_RECEIVED);
                    exit;
                }
            }
            echo $obj->getHtmlErrors();
        }
        $form =& $obj->getForm($donnee, true);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
