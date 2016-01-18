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
// template d'affichage
// SAR - 19/08/2011
$op  = TDMLinks_CleanVars($_REQUEST, 'op', 'liste', 'string');
$lid = TDMLinks_CleanVars($_REQUEST, 'lid', 0, 'int');
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['baseurl'] . '/broken,' . $op . ',' . $lid . $GLOBALS['xoopsModuleConfig']['endofurl'];
if (!strpos($url, $_SERVER['REQUEST_URI']) && $GLOBALS['xoopsModuleConfig']['htaccess'] == true && empty($_POST)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $url);
    exit(0);
}
$xoopsOption['template_main'] = 'tdmlinks_brokenlink.tpl';
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
$categories = tdmlinks_MygetItemIds('tdmlinks_view', 'tdmlinks');
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
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow" /> ' . _MD_TDMLINKS_SINGLELINK_REPORTBROKEN;
        $xoopsTpl->assign('navigation', $navigation);
        // r�f�rencement
        // titre de la page
        $pagetitle = _MD_TDMLINKS_SINGLELINK_REPORTBROKEN . ' - ' . $view_links->getVar('title') . ' - ';
        $pagetitle .= TDMLinks_PathTreeUrl($mytree, $view_links->getVar('cid'), $linkscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMLINKS_SINGLELINK_REPORTBROKEN . ' (' . $view_links->getVar('title') . ')'));
        //Affichage du formulaire de fichier bris�*/
        $obj  =& $linksbroken_Handler->create();
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case "save":
        $obj =& $linksbroken_Handler->create();
        if (empty($xoopsUser)) {
            $ratinguser = 0;
        } else {
            $ratinguser = $xoopsUser->getVar('uid');
        }
        if ($ratinguser != 0) {
            // si c'est un membre on v�rifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $linksbroken_arr = $linksbroken_Handler->getall($criteria);
            foreach (array_keys($linksbroken_arr) as $i) {
                if ($linksbroken_arr[$i]->getVar('sender') == $ratinguser) {
                    redirect_header('singlelink.php?lid=' . $lid, 2, _MD_TDMLINKS_BROKENLINK_ALREADYREPORTED);
                    exit();
                }
            }
        } else {
            // si c'est un utilisateur anonyme on v�rifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $criteria->add(new Criteria('sender', 0));
            $criteria->add(new Criteria('ip', getenv("REMOTE_ADDR")));
            if ($linksbroken_Handler->getCount($criteria) >= 1) {
                redirect_header('singlelink.php?lid=' . $lid, 2, _MD_TDMLINKS_BROKENLINK_ALREADYREPORTED);
                exit();
            }
        }
        $erreur         = false;
        $message_erreur = '';
        // Test avant la validation
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur = true;
        }
        $obj->setVar('lid', $lid);
        $obj->setVar('sender', $ratinguser);
        $obj->setVar('ip', getenv("REMOTE_ADDR"));
        if ($erreur == true) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            if ($linksbroken_Handler->insert($obj)) {
                $tags                      = array();
                $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/broken.php';
                $notification_handler      =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'file_broken', $tags);
                redirect_header('singlelink.php?lid=' . $lid, 2, _MD_TDMLINKS_BROKENLINK_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des t�l�chargements
        $form =& $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
