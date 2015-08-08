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
$op  = TDMLinks_CleanVars($_REQUEST, 'op', 'liste', 'string');
$lid = TDMLinks_CleanVars($_REQUEST, 'lid', 0, 'int');
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['baseurl'] . '/rate,' . $op . ',' . $lid . $GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI']) && $GLOBALS['xoopsModuleConfig']['htaccess'] == true && empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $url);
    exit(0);
}
// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_ratelink.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);
//On recupere la valeur de l'argument op dans l'URL$

//redirection si pas de permission de vote
if ($perm_vote == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

$view_links = $links_Handler->get($lid);
// redirection si le t�l�chargement n'existe pas ou n'est pas activ�
if (count($view_links) == 0 || $view_links->getVar('status') == 0) {
    redirect_header('index.php', 3, _MD_TDMLINKS_SINGLELINK_NONEXISTENT);
    exit();
}

//redirection si pas de permission (cat)
$categories = TDMLinks_MygetItemIds('tdmlinks_view', 'tdmlinks');
if (!in_array($view_links->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case "liste":
        //tableau des cat�gories
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        $linkscat_arr = $linkscat_Handler->getall($criteria);
        $mytree       = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = tdmlinks_PathTreeUrl($mytree, $view_links->getVar('cid'), $linkscat_arr, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC', true);
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> <a title="' . $view_links->getVar('title') . '" href="singlelink.php?lid=' . $view_links->getVar('lid') . '">' . $view_links->getVar('title') . '</a>';
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ' . _MD_TDMLINKS_SINGLELINK_RATHLINK;
        $xoopsTpl->assign('navigation', $navigation);
        // r�f�rencement
        // titre de la page
        $pagetitle = _MD_TDMLINKS_SINGLELINK_RATHLINK . ' - ' . $view_links->getVar('title') . ' - ';
        $pagetitle .= tdmlinks_PathTreeUrl($mytree, $view_links->getVar('cid'), $linkscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMLINKS_SINGLELINK_RATHLINK . ' (' . $view_links->getVar('title') . ')'));
        //Affichage du formulaire de notation des t�l�chargements
        $obj  =& $linksvotedata_Handler->create();
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
        break;

    // save
    case "save":
        $obj =& $linksvotedata_Handler->create();
        if (empty($xoopsUser)) {
            $ratinguser = 0;
        } else {
            $ratinguser = $xoopsUser->getVar('uid');
        }
        // si c'est un membre on v�rifie qu'il ne vote pas pour son fichier
        if ($ratinguser != 0) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $links_arr = $links_Handler->getall($criteria);
            foreach (array_keys($links_arr) as $i) {
                if ($links_arr[$i]->getVar('submitter') == $ratinguser) {
                    redirect_header('singlelink.php?lid=' . (int)($_REQUEST['lid']), 2, _MD_TDMLINKS_RATELINK_CANTVOTEOWN);
                    exit();
                }
            }
            // si c'est un membre on v�rifie qu'il ne vote pas 2 fois
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $linksvotes_arr = $linksvotedata_Handler->getall($criteria);
            foreach (array_keys($linksvotes_arr) as $i) {
                if ($linksvotes_arr[$i]->getVar('ratinguser') == $ratinguser) {
                    redirect_header('singlelink.php?lid=' . (int)($_REQUEST['lid']), 2, _MD_TDMLINKS_RATELINK_VOTEONCE);
                    exit();
                }
            }
        } else {
            // si c'est un utilisateur anonyme on v�rifie qu'il ne vote pas 2 fois par jour
            $yesterday = (time() - 86400);
            $criteria  = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $criteria->add(new Criteria('ratinguser', 0));
            $criteria->add(new Criteria('ratinghostname', getenv("REMOTE_ADDR")));
            $criteria->add(new Criteria('ratingtimestamp', $yesterday, '>'));
            if ($linksvotedata_Handler->getCount($criteria) >= 1) {
                redirect_header('singlelink.php?lid=' . (int)($_REQUEST['lid']), 2, _MD_TDMLINKS_RATELINK_VOTEONCE);
                exit();
            }
        }
        $erreur         = false;
        $message_erreur = '';
        // Test avant la validation
        $rating = (int)($_POST['rating']);
        if ($rating < 0 || $rating > 10) {
            $message_erreur .= _MD_TDMLINKS_RATELINK_NORATING . '<br>';
            $erreur = true;
        }
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur = true;
        }
        $obj->setVar('lid', $lid);
        $obj->setVar('ratinguser', $ratinguser);
        $obj->setVar('rating', $rating);
        $obj->setVar('ratinghostname', getenv("REMOTE_ADDR"));
        $obj->setVar('ratingtimestamp', time());
        if ($erreur == true) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            if ($linksvotedata_Handler->insert($obj)) {
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $lid));
                $linksvotes_arr = $linksvotedata_Handler->getall($criteria);
                $total_vote     = $linksvotedata_Handler->getCount($criteria);
                $total_rating   = 0;
                foreach (array_keys($linksvotes_arr) as $i) {
                    $total_rating += $linksvotes_arr[$i]->getVar('rating');
                }
                $rating   = $total_rating / $total_vote;
                $objlinks =& $links_Handler->get($lid);
                $objlinks->setVar('rating', number_format($rating, 1));
                $objlinks->setVar('votes', $total_vote);
                if ($links_Handler->insert($objlinks)) {
                    redirect_header('singlelink.php?lid=' . $lid, 2, _MD_TDMLINKS_RATELINK_VOTEOK);
                }
                echo $objlinks->getHtmlErrors();
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des t�l�chargements
        $form =& $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
