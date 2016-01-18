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

include_once 'header.php';
// SAR 19/08/2011
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['baseurl'] . '/index' . $GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI']) && $GLOBALS['xoopsModuleConfig']['htaccess'] === true && empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $url);
    exit(0);
}
// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_index.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);
// pour les permissions
$categories = TDMLinks_MygetItemIds('tdmlinks_view', 'tdmlinks');

//tableau des t�l�chargements
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
$links_arr = $links_Handler->getall($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMLINKS_INDEX_THEREARE, count($links_arr)));

//tableau des cat�gories
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$linkscat_arr = $linkscat_Handler->getall($criteria);
$mytree       = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');

//affichage des cat�gories
$xoopsTpl->assign('nb_catcol', $xoopsModuleConfig['nb_catcol']);
$count    = 1;
$keywords = '';
foreach (array_keys($linkscat_arr) as $i) {
    if ($linkscat_arr[$i]->getVar('cat_pid') === 0) {
        $totallinks        = TDMLinks_NumbersOfEntries($mytree, $categories, $links_arr, $linkscat_arr[$i]->getVar('cat_cid'));
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
        $xoopsTpl->append('categories', array(
            'image'            => $uploadurl . $linkscat_arr[$i]->getVar('cat_imgurl'),
            'id'               => $linkscat_arr[$i]->getVar('cat_cid'),
            'title'            => $linkscat_arr[$i]->getVar('cat_title'),
            'description_main' => $linkscat_arr[$i]->getVar('cat_description_main'),
            'subcategories'    => $subcategories,
            'totallinks'       => $totallinks,
            'count'            => $count
        ));
        $count++;
    }
}

//pour afficher les r�sum�s
//----------------------------------------------------------------------------------------------------------------------------------------------------
//t�l�chargements r�cents
if ($xoopsModuleConfig['bldate'] === 1) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $links_arr_date = $links_Handler->getall($criteria);
    foreach (array_keys($links_arr_date) as $i) {
        $title = $links_arr_date[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . "...";
        }
        $date = formatTimestamp($links_arr_date[$i]->getVar('date'), "s");
        $xoopsTpl->append('bl_date', array('id' => $links_arr_date[$i]->getVar('lid'), 'cid' => $links_arr_date[$i]->getVar('cid'), 'date' => $date, 'title' => $title));
    }
}
//plus t�l�charg�s
if ($xoopsModuleConfig['blpop'] === 1) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $links_arr_hits = $links_Handler->getall($criteria);
    foreach (array_keys($links_arr_hits) as $i) {
        $title = $links_arr_hits[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . "...";
        }
        $xoopsTpl->append('bl_pop', array('id' => $links_arr_hits[$i]->getVar('lid'), 'cid' => $links_arr_hits[$i]->getVar('cid'), 'hits' => $links_arr_hits[$i]->getVar('hits'), 'title' => $title));
    }
}
//mieux not�s
if ($xoopsModuleConfig['blrating'] === 1) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $links_arr_rating = $links_Handler->getall($criteria);
    foreach (array_keys($links_arr_rating) as $i) {
        $title = $links_arr_rating[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . "...";
        }
        $rating = number_format($links_arr_rating[$i]->getVar('rating'), 1);
        $xoopsTpl->append('bl_rating', array('id' => $links_arr_rating[$i]->getVar('lid'), 'cid' => $links_arr_rating[$i]->getVar('cid'), 'rating' => $rating, 'title' => $title));
    }
}
if ($xoopsModuleConfig['bldate'] === 0 and $xoopsModuleConfig['blpop'] === 0 and $xoopsModuleConfig['blrating'] === 0) {
    $bl_affichage = 0;
} else {
    $bl_affichage = 1;
}
$xoopsTpl->assign('bl_affichage', $bl_affichage);

//----------------------------------------------------------------------------------------------------------------------------------------------------

// affichage des t�l�chargements
if ($xoopsModuleConfig['newlinks'] > 0) {
    $xoopsTpl->assign('nb_dowcol', $xoopsModuleConfig['nb_dowcol']);
    //Utilisation d'une copie d'�cran avec la largeur selon les pr�f�rences
    if ($xoopsModuleConfig['useshots'] === 1) {
        $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('img_float', $xoopsModuleConfig['img_float']);
    }
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('status', 0, '!='));
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setLimit($xoopsModuleConfig['newlinks']);
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
    $sort        = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
    $order       = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
    $criteria->setSort($tblsort[$sort]);
    $criteria->setOrder($tblorder[$order]);
    $links_arr  = $links_Handler->getall($criteria);
    $categories = TDMLinks_MygetItemIds('tdmlinks_link', 'tdmlinks');
    $item       = TDMLinks_MygetItemIds('tdmlinks_link_item', 'tdmlinks');
    $count      = 1;
    foreach (array_keys($links_arr) as $i) {
        if ($links_arr[$i]->getVar('logourl') === 'blank.gif') {
            $logourl = '';
        } else {
            $logourl = $links_arr[$i]->getVar('logourl');
            $logourl = $uploadurl_shots . $logourl;
        }
        $datetime    = formatTimestamp($links_arr[$i]->getVar('date'), 's');
        $submitter   = XoopsUser::getUnameFromId($links_arr[$i]->getVar('submitter'));
        $description = $links_arr[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (strpos($description, '[pagebreak]') === false) {
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
        if ($xoopsModuleConfig['permission_link'] === 1) {
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
            'count'             => $count
        ));
        //pour les mots clef
        $keywords .= $links_arr[$i]->getVar('title') . ',';
        $count++;
    }
}
// r�f�rencement
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);

include XOOPS_ROOT_PATH . '/footer.php';
