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
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['baseurl'] . '/search' . $GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI']) && $GLOBALS['xoopsModuleConfig']['htaccess'] == true && empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $url);
    exit(0);
}
// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_liste.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);

$categories = TDMLinks_MygetItemIds('tdmlinks_view', 'tdmlinks');

if (isset($_REQUEST['title'])) {
    $_REQUEST['title'] != '' ? $title = $_REQUEST['title'] : $title = '';
} else {
    $title = '';
}

if (isset($_REQUEST['cat'])) {
    $_REQUEST['cat'] != 0 ? $cat = $_REQUEST['cat'] : $cat = 0;
} else {
    $cat = 0;
}
// tableau ------
$criteria_2 = new CriteriaCompo();
$criteria_2->add(new Criteria('status', 0, '!='));
$criteria_2->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
// ------
//formulaire de recherche
$form = new XoopsThemeForm(_MD_TDMLINKS_SEARCH, "search", "search.php", 'post');
$form->setExtra('enctype="multipart/form-data"');
//recherche par titre
$form->addElement(new XoopsFormText(_MD_TDMLINKS_SEARCH_TITLE, 'title', 25, 255, $title));
//recherche par cat�gorie
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
/*$cat_select = new XoopsFormSelect(_MD_TDMLINKS_SEARCH_CATEGORIES . ' ', 'cat', $cat);
$cat_select->addOption(0,_MD_TDMLINKS_SEARCH_ALL2);
$cat_select->addOptionArray($linkscat_Handler->getList($criteria ));
$form->addElement($cat_select);*/
$linkscat_arr = $linkscat_Handler->getall($criteria);
$mytree       = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
$form->addElement(new XoopsFormLabel(_AM_TDMLINKS_FORMINCAT, $mytree->makeSelBox('cat', 'cat_title', '--', $cat, true)));

//recherche champ sup.
$linksfield_Handler =& xoops_getModuleHandler('tdmlinks_field', 'tdmlinks');
$criteria           = new CriteriaCompo();
$criteria->add(new Criteria('search', 1));
$criteria->add(new Criteria('status', 1));
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$links_field = $linksfield_Handler->getall($criteria);

$arguments = '';
foreach (array_keys($links_field) as $i) {
    $title_sup   = '';
    $contenu_arr = array();
    $lid_arr     = array();
    $nom_champ   = 'champ' . $links_field[$i]->getVar('fid');
    $criteria    = new CriteriaCompo();
    if (isset($_REQUEST[$nom_champ])) {
        $_REQUEST[$nom_champ] != 999 ? $champ_contenu[$links_field[$i]->getVar('fid')] = $_REQUEST[$nom_champ] : $champ_contenu[$links_field[$i]->getVar('fid')] = 999;
        $arguments .= $nom_champ . '=' . $_REQUEST[$nom_champ] . '&amp;';
    } else {
        $champ_contenu[$links_field[$i]->getVar('fid')] = 999;
        $arguments .= $nom_champ . '=&amp;';
    }
    if ($links_field[$i]->getVar('status_def') == 1) {
        $criteria->add(new Criteria('status', 0, '!='));
        if ($links_field[$i]->getVar('fid') == 1) {
            //page d'accueil
            $title_sup = _AM_TDMLINKS_FORMHOMEPAGE;
            $criteria->setSort('homepage');
            $nom_champ_base = 'homepage';
        }
        if ($links_field[$i]->getVar('fid') == 2) {
            //version
            $title_sup = _AM_TDMLINKS_FORMVERSION;
            $criteria->setSort('version');
            $nom_champ_base = 'version';
        }
        if ($links_field[$i]->getVar('fid') == 3) {
            //taille du fichier
            $title_sup = _AM_TDMLINKS_FORMSIZE;
            $criteria->setSort('size');
            $nom_champ_base = 'size';
        }
        if ($links_field[$i]->getVar('fid') == 4) {
            //platform
            $title_sup      = _AM_TDMLINKS_FORMPLATFORM;
            $platform_array = explode('|', $xoopsModuleConfig['plateform']);
            foreach ($platform_array as $platform) {
                $contenu_arr[$platform] = $platform;
            }
            if ($champ_contenu[$links_field[$i]->getVar('fid')] != 999) {
                $criteria_2->add(new Criteria('platform', '%' . $champ_contenu[$links_field[$i]->getVar('fid')] . '%', 'LIKE'));
            }
        } else {
            $criteria->setOrder('ASC');
            $tdmlinks_arr = $links_Handler->getall($criteria);
            foreach (array_keys($tdmlinks_arr) as $j) {
                $contenu_arr[$tdmlinks_arr[$j]->getVar($nom_champ_base)] = $tdmlinks_arr[$j]->getVar($nom_champ_base);
            }
            if ($champ_contenu[$links_field[$i]->getVar('fid')] != 999) {
                $criteria_2->add(new Criteria($nom_champ_base, $champ_contenu[$links_field[$i]->getVar('fid')]));
            }
        }
    } else {
        $title_sup = $links_field[$i]->getVar('title');
        $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
        $criteria->setSort('data');
        $criteria->setOrder('ASC');
        $tdmlinks_arr = $linksfielddata_Handler->getall($criteria);
        foreach (array_keys($tdmlinks_arr) as $j) {
            $contenu_arr[$tdmlinks_arr[$j]->getVar('data', 'n')] = $tdmlinks_arr[$j]->getVar('data');
        }
        if ($champ_contenu[$links_field[$i]->getVar('fid')] != '') {
            $criteria_1 = new CriteriaCompo();
            $criteria_1->add(new Criteria('data', $champ_contenu[$links_field[$i]->getVar('fid')]));
            $data_arr = $linksfielddata_Handler->getall($criteria_1);
            foreach (array_keys($data_arr) as $k) {
                $lid_arr[] = $data_arr[$k]->getVar('lid');
            }
        }
        $form->addElement($select_sup);
    }
    if (count($lid_arr) > 0) {
        $criteria_2->add(new Criteria('lid', '(' . implode(',', $lid_arr) . ')', 'IN'));
    }
    $select_sup = new XoopsFormSelect($title_sup, $nom_champ, $champ_contenu[$links_field[$i]->getVar('fid')]);
    $select_sup->addOption(999, _MD_TDMLINKS_SEARCH_ALL1);
    $select_sup->addOptionArray($contenu_arr);
    $form->addElement($select_sup);
    unset($select_sup);
    $xoopsTpl->append('field', $links_field[$i]->getVar('title'));
}

//bouton validation
$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormButton('', 'submit', _MD_TDMLINKS_SEARCH_BT, 'submit'));
$form->addElement($button_tray);

if ($title != '') {
    $criteria_2->add(new Criteria('title', '%' . $title . '%', 'LIKE'));
    $arguments .= 'title=' . $title . '&amp;';
}
if ($cat != 0) {
    $criteria_2->add(new Criteria('cid', $cat));
    $arguments .= 'cat=' . $cat . '&amp;';
}
$tblsort     = array();
$tblsort[1]  = 'date';
$tblsort[2]  = 'date';
$tblsort[3]  = 'hits';
$tblsort[4]  = 'hits';
$tblsort[5]  = 'rating';
$tblsort[6]  = 'rating';
$tblsort[7]  = 'title';
$tblsort[8]  = 'title';
$tblorder    = array();
$tblorder[1] = 'DESC';
$tblorder[2] = 'ASC';
$tblorder[3] = 'DESC';
$tblorder[4] = 'ASC';
$tblorder[5] = 'DESC';
$tblorder[6] = 'ASC';
$tblorder[7] = 'DESC';
$tblorder[8] = 'ASC';
$sort        = isset($xoopsModuleConfig['searchorder']) ? $xoopsModuleConfig['searchorder'] : 1;
$order       = isset($xoopsModuleConfig['searchorder']) ? $xoopsModuleConfig['searchorder'] : 1;
$criteria_2->setSort($tblsort[$sort]);
$criteria_2->setOrder($tblorder[$order]);
$numrows = $links_Handler->getCount($criteria_2);
if (isset($_REQUEST['limit'])) {
    $criteria_2->setLimit($_REQUEST['limit']);
    $limit = $_REQUEST['limit'];
} else {
    $criteria_2->setLimit($xoopsModuleConfig['perpageliste']);
    $limit = $xoopsModuleConfig['perpageliste'];
}
if (isset($_REQUEST['start'])) {
    $criteria_2->setStart($_REQUEST['start']);
    $start = $_REQUEST['start'];
} else {
    $criteria_2->setStart(0);
    $start = 0;
}
//pour faire une jointure de table
$links_Handler->table_link   = $links_Handler->db->prefix("tdmlinks_cat"); // Nom de la table en jointure
$links_Handler->field_link   = "cat_cid"; // champ de la table en jointure
$links_Handler->field_object = "cid"; // champ de la table courante
$tdmlinks_arr                = $links_Handler->getByLink($criteria_2);
if ($numrows > $limit) {
    $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', $arguments);
    $pagenav = $pagenav->renderNav(4);
} else {
    $pagenav = '';
}
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMLINKS_SEARCH_THEREARE, $links_Handler->getCount($criteria_2)));
$xoopsTpl->assign('pagenav', $pagenav);
$keywords = '';
foreach (array_keys($tdmlinks_arr) as $i) {
    $tdmlinks_tab['lid']    = $tdmlinks_arr[$i]->getVar('lid');
    $tdmlinks_tab['cid']    = $tdmlinks_arr[$i]->getVar('cid');
    $tdmlinks_tab['title']  = $tdmlinks_arr[$i]->getVar('title');
    $tdmlinks_tab['cat']    = $tdmlinks_arr[$i]->getVar('cat_title');
    $tdmlinks_tab['imgurl'] = $uploadurl . $tdmlinks_arr[$i]->getVar('cat_imgurl');
    $tdmlinks_tab['date']   = formatTimeStamp($tdmlinks_arr[$i]->getVar('date'), 'd/m/Y');
    $tdmlinks_tab['rating'] = number_format($tdmlinks_arr[$i]->getVar('rating'), 0);
    $tdmlinks_tab['hits']   = $tdmlinks_arr[$i]->getVar('hits');
    $contenu                = '';
    foreach (array_keys($links_field) as $j) {
        if ($links_field[$j]->getVar('status_def') == 1) {
            if ($links_field[$j]->getVar('fid') == 1) {
                //page d'accueil
                $contenu = $tdmlinks_arr[$i]->getVar('homepage');
            }
            if ($links_field[$j]->getVar('fid') == 2) {
                //version
                $contenu = $tdmlinks_arr[$i]->getVar('version');
            }
            if ($links_field[$j]->getVar('fid') == 3) {
                //taille du fichier
                $contenu = trans_size($tdmlinks_arr[$i]->getVar('size'));
            }
            if ($links_field[$j]->getVar('fid') == 4) {
                //plateforme
                $contenu = $tdmlinks_arr[$i]->getVar('platform');
            }
        } else {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $tdmlinks_arr[$i]->getVar('lid')));
            $criteria->add(new Criteria('fid', $links_field[$j]->getVar('fid')));
            $linksfielddata = $linksfielddata_Handler->getall($criteria);
            if (count($linksfielddata) > 0) {
                foreach (array_keys($linksfielddata) as $k) {
                    $contenu = $linksfielddata[$k]->getVar('data', 'n');
                }
            } else {
                $contenu = '';
            }
        }
        $tdmlinks_tab['fielddata'][$j] = $contenu;
        unset($contenu);
    }
    $xoopsTpl->append('links', $tdmlinks_tab);
    $keywords .= $tdmlinks_arr[$i]->getVar('title') . ',';
}

$xoopsTpl->assign('searchForm', $form->render());
// r�f�rencement
// titre de la page
$titre = _MD_TDMLINKS_SEARCH_PAGETITLE . ' - ' . $xoopsModule->name();
$xoopsTpl->assign('xoops_pagetitle', $titre);
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', strip_tags($keywords));

include XOOPS_ROOT_PATH . '/footer.php';
