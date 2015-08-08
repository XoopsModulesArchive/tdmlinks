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
// SAR 19/08/2011
$cid = TDMLinks_CleanVars($_REQUEST, 'cid', 0, 'int');
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['baseurl'] . '/cat,' . $cid . $GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI']) && $GLOBALS['xoopsModuleConfig']['htaccess'] == true && empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $url);
    exit(0);
}

// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_viewcat.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);

// pour les permissions
$categories = TDMLinks_MygetItemIds('tdmlinks_view', 'tdmlinks');

// redirection si la cat�gorie n'existe pas
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_cid', $cid));
if ($linkscat_Handler->getCount($criteria) == 0 || $cid == 0) {
    redirect_header('index.php', 3, _MD_TDMLINKS_CAT_NONEXISTENT);
    exit();
}
// pour les permissions (si pas de droit, redirection)
if (!in_array((int)($cid), $categories)) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

//tableau des cat�gories
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$linkscat_arr = $linkscat_Handler->getall($criteria);
$mytree       = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');

//tableau des t�l�chargements
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
$links_arr = $links_Handler->getall($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMLINKS_INDEX_THEREARE, count($links_arr)));

//navigation
$nav_category = tdmlinks_PathTreeUrl($mytree, $cid, $linkscat_arr, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC');
$xoopsTpl->assign('category_path', $nav_category);

// info cat�gorie
$xoopsTpl->assign('category_id', $cid);
$cat_info = $linkscat_Handler->get($cid);
$xoopsTpl->assign('cat_description', $cat_info->getVar('cat_description_main'));

//affichage des cat�gories
$xoopsTpl->assign('nb_catcol', $xoopsModuleConfig['nb_catcol']);
$count    = 1;
$keywords = '';
foreach (array_keys($linkscat_arr) as $i) {
    if ($linkscat_arr[$i]->getVar('cat_pid') == $cid) {
        $totallinks        = tdmlinks_NumbersOfEntries($mytree, $categories, $links_arr, $linkscat_arr[$i]->getVar('cat_cid'));
        $subcategories_arr = $mytree->getFirstChild($linkscat_arr[$i]->getVar('cat_cid'));
        $chcount           = 0;
        $subcategories     = '';
        //pour les mots clef
        $keywords .= $linkscat_arr[$i]->getVar('cat_title') . ',';
        foreach (array_keys($subcategories_arr) as $j) {
            if ($chcount >= $xoopsModuleConfig['nbsouscat']) {
                $subcategories .= '<li>[<a href="' . XOOPS_URL . '/modules/tdmlinks/viewcat.php?cid=' . $linkscat_arr[$i]->getVar('cat_cid') . '">+</a>]</li>';
                break;
            }
            $subcategories .= '<li><a href="' . XOOPS_URL . '/modules/tdmlinks/viewcat.php?cid=' . $subcategories_arr[$j]->getVar('cat_cid') . '">' . $subcategories_arr[$j]->getVar('cat_title') . '</a></li>';
            $keywords .= $linkscat_arr[$i]->getVar('cat_title') . ',';
            $chcount++;
        }
        $xoopsTpl->append('subcategories', array(
            'image'            => $uploadurl . $linkscat_arr[$i]->getVar('cat_imgurl'),
            'id'               => $linkscat_arr[$i]->getVar('cat_cid'),
            'title'            => $linkscat_arr[$i]->getVar('cat_title'),
            'description_main' => $linkscat_arr[$i]->getVar('cat_description_main'),
            'infercategories'  => $subcategories,
            'totallinks'       => $totallinks,
            'count'            => $count
        ));
        $count++;
    }
}

//pour afficher les r�sum�s
//----------------------------------------------------------------------------------------------------------------------------------------------------
//t�l�chargements r�cents
if ($xoopsModuleConfig['bldate'] == 1) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)($_REQUEST['cid'])));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $links_arr = $links_Handler->getall($criteria);
    foreach (array_keys($links_arr) as $i) {
        $title = $links_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . "...";
        }
        $date = formatTimestamp($links_arr[$i]->getVar('date'), "s");
        $xoopsTpl->append('bl_date', array('id' => $links_arr[$i]->getVar('lid'), 'cid' => $links_arr[$i]->getVar('cid'), 'date' => $date, 'title' => $title));
    }
}
//plus t�l�charg�s
if ($xoopsModuleConfig['blpop'] == 1) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)($_REQUEST['cid'])));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $links_arr = $links_Handler->getall($criteria);
    foreach (array_keys($links_arr) as $i) {
        $title = $links_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . "...";
        }
        $xoopsTpl->append('bl_pop', array('id' => $links_arr[$i]->getVar('lid'), 'cid' => $links_arr[$i]->getVar('cid'), 'hits' => $links_arr[$i]->getVar('hits'), 'title' => $title));
    }
}
//mieux not�s
if ($xoopsModuleConfig['blrating'] == 1) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)($_REQUEST['cid'])));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $links_arr = $links_Handler->getall($criteria);
    foreach (array_keys($links_arr) as $i) {
        $title = $links_arr[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . "...";
        }
        $rating = number_format($links_arr[$i]->getVar('rating'), 1);
        $xoopsTpl->append('bl_rating', array('id' => $links_arr[$i]->getVar('lid'), 'cid' => $links_arr[$i]->getVar('cid'), 'rating' => $rating, 'title' => $title));
    }
}
// affichage du r�sum�
if ($xoopsModuleConfig['bldate'] == 0 and $xoopsModuleConfig['blpop'] == 0 and $xoopsModuleConfig['blrating'] == 0) {
    $bl_affichage = 0;
} else {
    $bl_affichage = 1;
}
//----------------------------------------------------------------------------------------------------------------------------------------------------

// affichage des t�l�chargements
if ($xoopsModuleConfig['perpage'] > 0) {
    $xoopsTpl->assign('nb_dowcol', $xoopsModuleConfig['nb_dowcol']);
    //Utilisation d'une copie d'�cran avec la largeur selon les pr�f�rences
    if ($xoopsModuleConfig['useshots'] == 1) {
        $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('img_float', $xoopsModuleConfig['img_float']);
    }
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new Criteria('cid', (int)($_REQUEST['cid'])));
    $numrows = $links_Handler->getCount($criteria);
    $xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMLINKS_CAT_THEREARE, $numrows));

    // Pour un affichage sur plusieurs pages
    if (isset($_REQUEST['limit'])) {
        $criteria->setLimit($_REQUEST['limit']);
        $limit = $_REQUEST['limit'];
    } else {
        $criteria->setLimit($xoopsModuleConfig['perpage']);
        $limit = $xoopsModuleConfig['perpage'];
    }
    if (isset($_REQUEST['start'])) {
        $criteria->setStart($_REQUEST['start']);
        $start = $_REQUEST['start'];
    } else {
        $criteria->setStart(0);
        $start = 0;
    }
    if (isset($_REQUEST['sort'])) {
        $criteria->setSort($_REQUEST['sort']);
        $sort = $_REQUEST['sort'];
    } else {
        $criteria->setSort('date');
        $sort = 'date';
    }
    if (isset($_REQUEST['order'])) {
        $criteria->setOrder($_REQUEST['order']);
        $order = $_REQUEST['order'];
    } else {
        $criteria->setOrder('DESC');
        $order = 'DESC';
    }

    $links_arr = $links_Handler->getall($criteria);
    if ($numrows > $limit) {
        $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'limit=' . $limit . '&cid=' . (int)($_REQUEST['cid']) . '&sort=' . $sort . '&order=' . $order);
        $pagenav = $pagenav->renderNav(4);
    } else {
        $pagenav = '';
    }
    $xoopsTpl->assign('pagenav', $pagenav);
    $summary    = '';
    $cpt        = 0;
    $categories = TDMLinks_MygetItemIds('tdmlinks_link', 'tdmlinks');
    $item       = TDMLinks_MygetItemIds('tdmlinks_LINK_item', 'tdmlinks');
    foreach (array_keys($links_arr) as $i) {
        if ($links_arr[$i]->getVar('logourl') == 'blank.gif') {
            $logourl = '';
        } else {
            $logourl = $links_arr[$i]->getVar('logourl');
            $logourl = $uploadurl_shots . $logourl;
        }
        $datetime    = formatTimestamp($links_arr[$i]->getVar('date'), 's');
        $submitter   = XoopsUser::getUnameFromId($links_arr[$i]->getVar('submitter'));
        $description = $links_arr[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (strpos($description, '[pagebreak]') == false) {
            $description_short = $description;
        } else {
            $description_short = substr($description, 0, strpos($description, '[pagebreak]'));
        }
        // pour les vignettes "new" et "mis � jour"
        $new = TDMLinks_Thumbnail($links_arr[$i]->getVar('date'), $links_arr[$i]->getVar('status'));
        $pop = TDMLinks_Popular($links_arr[$i]->getVar('hits'));

        // D�fini si la personne est un admin
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $adminlink = '<a href="' . XOOPS_URL . '/modules/tdmlinks/admin/links.php?op=view_links&amp;links_lid=' . $links_arr[$i]->getVar('lid') . '" title="' . _MD_TDMLINKS_EDITTHISDL . '"><img src="' . XOOPS_URL . '/modules/tdmlinks/assets/images/icon/edit.png" width="16px" height="16px" border="0" alt="' . _MD_TDMLINKS_EDITTHISDL . '" /></a>';
        } else {
            $adminlink = '';
        }
        //permission de t�l�charger
        if ($xoopsModuleConfig['permission_link'] == 1) {
            if (!in_array($links_arr[$i]->getVar('cid'), $categories)) {
                $perm_link = false;
            } else {
                $perm_link = true;
            }
        } else {
            if (!in_array($links_arr[$i]->getVar('lid'), $item)) {
                $perm_link = false;
            } else {
                $perm_link = true;
            }
        }
        // utilisation du sommaire
        $cpt++;
        $summary = $cpt . '- <a href="#l' . $cpt . '">' . $links_arr[$i]->getVar('title') . '</a><br />';
        $xoopsTpl->append('summary', array('title' => $summary, 'count' => $cpt));

        $xoopsTpl->append('file', array(
            'id'                => $links_arr[$i]->getVar('lid'),
            'cid'               => $links_arr[$i]->getVar('cid'),
            'title'             => $links_arr[$i]->getVar('title'),
            'new'               => $new,
            'pop'               => $pop,
            'logourl'           => $logourl,
            'updated'           => $datetime,
            'description_short' => $description_short,
            'adminlink'         => $adminlink,
            'submitter'         => $submitter,
            'perm_link'         => $perm_link,
            'count'             => $cpt
        ));
        //pour les mots clef
        $keywords .= $links_arr[$i]->getVar('title') . ',';
    }

    if ($numrows == 0) {
        $bl_affichage = 0;
    }
    $xoopsTpl->assign('bl_affichage', $bl_affichage);

    // affichage du sommaire
    if ($xoopsModuleConfig['autosummary']) {
        if ($numrows == 0) {
            $xoopsTpl->assign('aff_summary', false);
        } else {
            $xoopsTpl->assign('aff_summary', true);
        }
    } else {
        $xoopsTpl->assign('aff_summary', false);
    }

    // affichage du menu de tri
    if ($numrows > 1) {
        $xoopsTpl->assign('navigation', true);
        $sortorder = $sort . $order;
        if ($sortorder == "hitsASC") {
            $affichage_tri = _MD_TDMLINKS_CAT_POPULARITYLTOM;
        }
        if ($sortorder == "hitsDESC") {
            $affichage_tri = _MD_TDMLINKS_CAT_POPULARITYMTOL;
        }
        if ($sortorder == "titleASC") {
            $affichage_tri = _MD_TDMLINKS_CAT_TITLEATOZ;
        }
        if ($sortorder == "titleDESC") {
            $affichage_tri = _MD_TDMLINKS_CAT_TITLEZTOA;
        }
        if ($sortorder == "dateASC") {
            $affichage_tri = _MD_TDMLINKS_CAT_DATEOLD;
        }
        if ($sortorder == "dateDESC") {
            $affichage_tri = _MD_TDMLINKS_CAT_DATENEW;
        }
        if ($sortorder == "ratingASC") {
            $affichage_tri = _MD_TDMLINKS_CAT_RATINGLTOH;
        }
        if ($sortorder == "ratingDESC") {
            $affichage_tri = _MD_TDMLINKS_CAT_RATINGHTOL;
        }
        $xoopsTpl->assign('affichage_tri', sprintf(_MD_TDMLINKS_CAT_CURSORTBY, $affichage_tri));
    }
}
// r�f�rencement
// titre de la page
$pagetitle = TDMLinks_PathTreeUrl($mytree, $cid, $linkscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC');
$xoopsTpl->assign('xoops_pagetitle', $pagetitle);
//description
$xoTheme->addMeta('meta', 'description', strip_tags($linkscat_arr[$cid]->getVar('cat_description_main')));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);

include XOOPS_ROOT_PATH . '/footer.php';
