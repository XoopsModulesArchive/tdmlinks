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
switch ($op) {
    // Vue liste
    case "list":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()) {
            $category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('category.php');
            $category_admin->addItemButton(_AM_TDMLINKS_CAT_NEW, 'category.php?op=new_cat', 'add');
            echo $category_admin->renderButton();
        }
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $links_cat = $linkscat_Handler->getall($criteria);
        //Affichage du tableau
        if (count($links_cat) > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="left" width="25%">' . _AM_TDMLINKS_FORMTITLE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMLINKS_FORMIMG . '</th>';
            echo '<th align="center">' . _AM_TDMLINKS_FORMTEXT . '</th>';
            echo '<th align="center" width="3%">' . _AM_TDMLINKS_FORMWEIGHT . '</th>';
            echo '<th align="center" width="8%">' . _AM_TDMLINKS_FORMACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            include_once XOOPS_ROOT_PATH . "/modules/tdmlinks/class/tree.php";
            $mytree             = new TDMObjectTree($links_cat, 'cat_cid', 'cat_pid');
            $category_ArrayTree = $mytree->makeArrayTree('cat_title', '<img src="../assets/images/deco/arrow.gif">');
            foreach (array_keys($category_ArrayTree) as $i) {
                echo '<tr class="' . $class . '">';
                echo '<td align="left" ><a href="' . XOOPS_URL . '/modules/tdmlinks/viewcat.php?cid=' . $i . '">' . $category_ArrayTree[$i] . '</a></td>';
                echo '<td align="center">';
                echo '<img src="' . $uploadurl . $links_cat[$i]->getVar('cat_imgurl') . '" alt="" title="" height="60">';
                echo '</td>';
                echo '<td align="left">' . $links_cat[$i]->getVar('cat_description_main') . '</td>';
                echo '<td align="center">' . $links_cat[$i]->getVar('cat_weight') . '</td>';
                echo '<td align="center">';
                echo '<a href="category.php?op=edit_cat&linkscat_cid=' . $i . '"><img src="../assets/images/icon/edit.png" alt="' . _AM_TDMLINKS_FORMEDIT . '" title="' . _AM_TDMLINKS_FORMEDIT . '"></a> ';
                echo '<a href="category.php?op=del_cat&linkscat_cid=' . $i . '"><img src="../assets/images/icon/delete.png" alt="' . _AM_TDMLINKS_FORMDEL . '" title="' . _AM_TDMLINKS_FORMDEL . '"></a>';
                echo '</td>';
                echo '</tr>';
                $class = ($class == 'even') ? 'odd' : 'even';
            }
            echo '</table>';
        }
        break;

    // vue création
    case "new_cat":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()) {
            $category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('category.php');
            $category_admin->addItemButton(_AM_TDMLINKS_CAT_LIST, 'category.php?op=list', 'list');
            echo $category_admin->renderButton();
        }
        //Affichage du formulaire de création des catégories
        $obj  =& $linkscat_Handler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    // Pour éditer une catégorie
    case "edit_cat":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()) {
            $category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('category.php');
            $category_admin->addItemButton(_AM_TDMLINKS_CAT_NEW, 'category.php?op=new_cat', 'add');
            $category_admin->addItemButton(_AM_TDMLINKS_CAT_LIST, 'category.php?op=list', 'list');
            echo $category_admin->renderButton();
        }
        //Affichage du formulaire de création des catégories
        $linkscat_cid = TDMLinks_CleanVars($_REQUEST, 'linkscat_cid', 0, 'int');
        $obj          = $linkscat_Handler->get($linkscat_cid);
        $form         = $obj->getForm();
        $form->display();
        break;

    // Pour supprimer une catégorie
    case "del_cat":
        global $xoopsModule;
        $linkscat_cid = TDMLinks_CleanVars($_REQUEST, 'linkscat_cid', 0, 'int');
        $obj          =& $linkscat_Handler->get($linkscat_cid);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // supression des téléchargements de la catégorie
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('cid', $linkscat_cid));
            $links_arr = $links_Handler->getall($criteria);
            foreach (array_keys($links_arr) as $i) {
                // supression des votes
                $criteria_1 = new CriteriaCompo();
                $criteria_1->add(new Criteria('lid', $links_arr[$i]->getVar('lid')));
                $links_votedata = $linksvotedata_Handler->getall($criteria_1);
                foreach (array_keys($links_votedata) as $j) {
                    $objvotedata =& $linksvotedata_Handler->get($links_votedata[$j]->getVar('ratingid'));
                    $linksvotedata_Handler->delete($objvotedata) or $objvotedata->getHtmlErrors();
                }
                // supression des rapports de fichier brisé
                $criteria_2 = new CriteriaCompo();
                $criteria_2->add(new Criteria('lid', $links_arr[$i]->getVar('lid')));
                $links_broken = $linksbroken_Handler->getall($criteria_2);
                foreach (array_keys($links_broken) as $j) {
                    $objbroken =& $linksbroken_Handler->get($links_broken[$j]->getVar('reportid'));
                    $linksbroken_Handler->delete($objbroken) or $objbroken->getHtmlErrors();
                }
                // supression des data des champs sup.
                $criteria_3 = new CriteriaCompo();
                $criteria_3->add(new Criteria('lid', $links_arr[$i]->getVar('lid')));
                $links_fielddata = $linksfielddata_Handler->getall($criteria_3);
                if ($linksfielddata_Handler->getCount($criteria_3) > 0) {
                    foreach (array_keys($links_fielddata) as $j) {
                        $objfielddata =& $linksfielddata_Handler->get($links_fielddata[$j]->getVar('iddata'));
                        $linksfielddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
                    }
                }
                // supression des commentaires
                if ($links_arr[$i]->getVar('comments') > 0) {
                    xoops_comment_delete($xoopsModule->getVar('mid'), $links_arr[$i]->getVar('lid'));
                }
                //supression des tags
                if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))) {
                    $tag_handler = xoops_getmodulehandler('link', 'tag');
                    $criteria    = new CriteriaCompo();
                    $criteria->add(new Criteria('tag_itemid', $links_arr[$i]->getVar('lid')));
                    $links_tags = $tag_handler->getall($criteria);
                    if (count($links_tags) > 0) {
                        foreach (array_keys($links_tags) as $j) {
                            $objtags =& $tag_handler->get($links_tags[$j]->getVar('tl_id'));
                            $tag_handler->delete($objtags) or $objtags->getHtmlErrors();
                        }
                    }
                }
                // supression du fichier
                // pour extraire le nom du fichier
                $urlfile = substr_replace($links_arr[$i]->getVar('url'), '', 0, strlen($uploadurl_LINKS));
                // chemin du fichier
                $urlfile = $uploaddir_LINKS . $urlfile;
                if (is_file($urlfile)) {
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
                // supression du téléchargment
                $objlinks =& $links_Handler->get($links_arr[$i]->getVar('lid'));
                $links_Handler->delete($objlinks) or $objlinks->getHtmlErrors();
            }
            // supression des sous catégories avec leurs téléchargements
            $linkscat_arr   = $linkscat_Handler->getall();
            $mytree         = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
            $links_childcat = $mytree->getAllChild($linkscat_cid);
            foreach (array_keys($links_childcat) as $i) {
                // supression de la catégorie
                $objchild =& $linkscat_Handler->get($links_childcat[$i]->getVar('cat_cid'));
                $linkscat_Handler->delete($objchild) or $objchild->getHtmlErrors();
                // supression des téléchargements associés
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('cid', $links_childcat[$i]->getVar('cat_cid')));
                $links_arr = $links_Handler->getall($criteria);
                foreach (array_keys($links_arr) as $i) {
                    // supression des votes
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('lid', $links_arr[$i]->getVar('lid')));
                    $links_votedata = $linksvotedata_Handler->getall($criteria);
                    foreach (array_keys($links_votedata) as $j) {
                        $objvotedata =& $linksvotedata_Handler->get($links_votedata[$j]->getVar('ratingid'));
                        $linksvotedata_Handler->delete($objvotedata) or $objvotedata->getHtmlErrors();
                    }
                    // supression des rapports de fichier brisé
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('lid', $links_arr[$i]->getVar('lid')));
                    $links_broken = $linksbroken_Handler->getall($criteria);
                    foreach (array_keys($links_broken) as $j) {
                        $objbroken =& $linksbroken_Handler->get($links_broken[$j]->getVar('reportid'));
                        $linksbroken_Handler->delete($objbroken) or $objbroken->getHtmlErrors();
                    }
                    // supression des data des champs sup.
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('lid', $links_arr[$i]->getVar('lid')));
                    $links_fielddata = $linksfielddata_Handler->getall($criteria);
                    foreach (array_keys($links_fielddata) as $j) {
                        $objfielddata =& $linksfielddata_Handler->get($links_fielddata[$j]->getVar('iddata'));
                        $linksfielddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
                    }
                    // supression des commentaires
                    if ($links_arr[$i]->getVar('comments') > 0) {
                        xoops_comment_delete($xoopsModule->getVar('mid'), $links_arr[$i]->getVar('lid'));
                    }
                    //supression des tags
                    if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))) {
                        $tag_handler = xoops_getmodulehandler('link', 'tag');
                        $criteria    = new CriteriaCompo();
                        $criteria->add(new Criteria('tag_itemid', $links_arr[$i]->getVar('lid')));
                        $links_tags = $tag_handler->getall($criteria);
                        if (count($links_tags) > 0) {
                            foreach (array_keys($links_tags) as $j) {
                                $objtags =& $tag_handler->get($links_tags[$j]->getVar('tl_id'));
                                $tag_handler->delete($objtags) or $objtags->getHtmlErrors();
                            }
                        }
                    }
                    // supression du fichier
                    $urlfile = substr_replace($links_arr[$i]->getVar('url'), '', 0, strlen($uploadurl_LINKS)); // pour extraire le nom du fichier
                    $urlfile = $uploaddir_LINKS . $urlfile; // chemin du fichier
                    if (is_file($urlfile)) {
                        chmod($urlfile, 0777);
                        unlink($urlfile);
                    }
                    // supression du téléchargment
                    $objlinks =& $links_Handler->get($links_arr[$i]->getVar('lid'));
                    $links_Handler->delete($objlinks) or $objlinks->getHtmlErrors();
                }
            }
            if ($linkscat_Handler->delete($obj)) {
                redirect_header('category.php', 1, _AM_TDMLINKS_REDIRECT_DELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            $message  = '';
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('cid', $linkscat_cid));
            $links_arr = $links_Handler->getall($criteria);
            if (count($links_arr) > 0) {
                $message .= _AM_TDMLINKS_DELLINKS . '<br>';
                foreach (array_keys($links_arr) as $i) {
                    $message .= '<span style="color : Red">' . $links_arr[$i]->getVar('title') . '</span><br>';
                }
            }
            $linkscat_arr   = $linkscat_Handler->getall();
            $mytree         = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
            $links_childcat = $mytree->getAllChild($linkscat_cid);
            if (count($links_childcat) > 0) {
                $message .= _AM_TDMLINKS_DELSOUSCAT . ' <br><br>';
                foreach (array_keys($links_childcat) as $i) {
                    $message .= '<b><span style="color : Red">' . $links_childcat[$i]->getVar('cat_title') . '</span></b><br>';
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('cid', $links_childcat[$i]->getVar('cat_cid')));
                    $links_arr = $links_Handler->getall($criteria);
                    if (count($links_arr) > 0) {
                        $message .= _AM_TDMLINKS_DELLINKS . '<br>';
                        foreach (array_keys($links_arr) as $i) {
                            $message .= '<span style="color : Red">' . $links_arr[$i]->getVar('title') . '</span><br>';
                        }
                    }
                }
            } else {
                $message .= '';
            }
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMLinks_checkModuleAdmin()) {
                $category_admin = new ModuleAdmin();
                $category_admin->addItemButton(_AM_TDMLINKS_CAT_NEW, 'category.php?op=new_cat', 'add');
                $category_admin->addItemButton(_AM_TDMLINKS_CAT_LIST, 'category.php?op=list', 'list');
                echo $category_admin->renderButton();
            }
            xoops_confirm(array('ok' => 1, 'linkscat_cid' => $linkscat_cid, 'op' => 'del_cat'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMLINKS_FORMSUREDEL, $obj->getVar('cat_title')) . '<br><br>' . $message);
        }

        break;

    // Pour sauver une catégorie
    case "save_cat":
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $cat_cid = TDMLinks_CleanVars($_REQUEST, 'cat_cid', 0, 'int');
        if (isset($_REQUEST['cat_cid'])) {
            $obj =& $linkscat_Handler->get($cat_cid);
        } else {
            $obj =& $linkscat_Handler->create();
        }
        $erreur         = false;
        $message_erreur = '';
        // Récupération des variables:
        // Pour l'image
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new XoopsMediaUploader($uploaddir, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $xoopsModuleConfig['maxuploadsize'], null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('links_');
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header("javascript:history.go(-1)", 3, $errors);
            } else {
                $obj->setVar('cat_imgurl', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('cat_imgurl', $_REQUEST['linkscat_img']);
        }
        // Pour les autres variables
        $obj->setVar('cat_pid', $_POST['cat_pid']);
        $obj->setVar('cat_title', $_POST['cat_title']);
        $obj->setVar('cat_description_main', $_POST['cat_description_main']);
        $obj->setVar('cat_weight', $_POST["cat_weight"]);
        if ((int)($_REQUEST['cat_weight']) == 0 && $_REQUEST['cat_weight'] != '0') {
            $erreur         = true;
            $message_erreur = _AM_TDMLINKS_ERREUR_WEIGHT . '<br>';
        }
        if (isset($_REQUEST['cat_cid'])) {
            if ($cat_cid == $_REQUEST['cat_pid']) {
                $erreur = true;
                $message_erreur .= _AM_TDMLINKS_ERREUR_CAT;
            }
        }
        if ($erreur == true) {
            echo '<div class="errorMsg" style="text-align: left;">' . $message_erreur . '</div>';
        } else {
            if ($linkscat_Handler->insert($obj)) {
                $newcat_cid = $obj->get_new_enreg();
                //permission pour voir
                $perm_id       = isset($_REQUEST['cat_cid']) ? $cat_cid : $newcat_cid;
                $gperm_handler = &xoops_gethandler('groupperm');
                $criteria      = new CriteriaCompo();
                $criteria->add(new Criteria('gperm_itemid', $perm_id, '='));
                $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                $criteria->add(new Criteria('gperm_name', 'tdmlinks_view', '='));
                $gperm_handler->deleteAll($criteria);
                if (isset($_POST['groups_view'])) {
                    foreach ($_POST['groups_view'] as $onegroup_id) {
                        $gperm_handler->addRight('tdmlinks_view', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                    }
                }
                //permission pour editer
                $perm_id       = isset($_REQUEST['cat_cid']) ? $cat_cid : $newcat_cid;
                $gperm_handler = &xoops_gethandler('groupperm');
                $criteria      = new CriteriaCompo();
                $criteria->add(new Criteria('gperm_itemid', $perm_id, '='));
                $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                $criteria->add(new Criteria('gperm_name', 'TDMLinks_submit', '='));
                $gperm_handler->deleteAll($criteria);
                if (isset($_POST['groups_submit'])) {
                    foreach ($_POST['groups_submit'] as $onegroup_id) {
                        $gperm_handler->addRight('TDMLinks_submit', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                    }
                }
                //permission pour télécharger
                if ($xoopsModuleConfig['permission_link'] == 1) {
                    $perm_id       = isset($_REQUEST['cat_cid']) ? $cat_cid : $newcat_cid;
                    $gperm_handler = &xoops_gethandler('groupperm');
                    $criteria      = new CriteriaCompo();
                    $criteria->add(new Criteria('gperm_itemid', $perm_id, '='));
                    $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                    $criteria->add(new Criteria('gperm_name', 'TDMLinks_link', '='));
                    $gperm_handler->deleteAll($criteria);
                    if (isset($_POST['groups_link'])) {
                        foreach ($_POST['groups_link'] as $onegroup_id) {
                            $gperm_handler->addRight('TDMLinks_link', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                        }
                    }
                }
                //notification
                if (!isset($_REQUEST['categorie_modified'])) {
                    $tags                  = array();
                    $tags['CATEGORY_NAME'] = $_POST['cat_title'];
                    $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $newcat_cid;
                    $notification_handler  =& xoops_gethandler('notification');
                    $notification_handler->triggerEvent('global', 0, 'new_category', $tags);
                }
                redirect_header('category.php?op=list', 1, _AM_TDMLINKS_REDIRECT_SAVE);
            }
            echo $obj->getHtmlErrors();
        }
        $form =& $obj->getForm();
        $form->display();
        break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
