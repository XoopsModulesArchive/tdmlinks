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
$lid = TDMLinks_CleanVars($_REQUEST, 'lid', 0, 'int');

//information du téléchargement
$view_links = $links_Handler->get($lid);
if (is_object($view_links)) {
	$url = XOOPS_URL.'/'.$GLOBALS['xoopsModuleConfig']['baseurl'].'/'.urlencode(str_replace(array(' ', '_', '/'), '-', $view_links->getVar('title'))).'/'.$lid.$GLOBALS['xoopsModuleConfig']['endofurl'];
	if (!strpos($url, $_SERVER['REQUEST_URI'])&&$GLOBALS['xoopsModuleConfig']['htaccess']==true&&empty($_POST)) {
		header( "HTTP/1.1 301 Moved Permanently" ); 
		header('Location: '.$url);
		exit(0);
	}
}
// template d'affichage
$xoopsOption['template_main'] = 'tdmlinks_singlelink.html';
include_once XOOPS_ROOT_PATH.'/header.php';
$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null );


// redirection si le téléchargement n'existe pas ou n'est pas activé
if (count($view_links) == 0 || $view_links->getVar('status') == 0){
    redirect_header('index.php', 3, _MD_TDMLINKS_SINGLELINK_NONEXISTENT);
    exit();
}

// pour les permissions
$categories = TDMLinks_MygetItemIds('tdmlinks_view', 'TDMLinks');
if(!in_array($view_links->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
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
$navigation = $navigation . ' <img src="images/deco/arrow.gif" alt="arrow" /> ' . $view_links->getVar('title');
$xoopsTpl->assign('navigation', $navigation);

// sortie des informations
//Utilisation d'une copie d'écran avec la largeur selon les préférences
if ($xoopsModuleConfig['useshots'] == 1) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('show_screenshot', true);    $xoopsTpl->assign('img_float' , $xoopsModuleConfig['img_float']);
}if ($xoopsModuleConfig['link_float'] == 'ltr') {
	 $xoopsTpl->assign('textfloat', 'floatleft');	 $xoopsTpl->assign('infofloat', 'floatright');
} else {	 $xoopsTpl->assign('textfloat', 'floatright');	 $xoopsTpl->assign('infofloat', 'floatleft');}	
// sortie des informations
if ($view_links->getVar('logourl') == 'blank.gif'){
    $logourl = '';
}else{
    $logourl = $view_links->getVar('logourl');
    $logourl = $uploadurl_shots . $logourl;
}
// Défini si la personne est un admin
if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $adminlink = '<a href="' . XOOPS_URL . '/modules/TDMLinks/admin/links.php?op=view_links&amp;links_lid=' . $_REQUEST['lid'] . '" title="' . _MD_TDMLINKS_EDITTHISDL . '"><img src="' . XOOPS_URL . '/modules/TDMLinks/images/icon/edit.png" width="16px" height="16px" border="0" alt="' . _MD_TDMLINKS_EDITTHISDL . '" /></a>';
} else {
    $adminlink = '';
}

$description = $view_links->getVar('description');
$xoopsTpl->assign('description' , str_replace('[pagebreak]','',$description));
$xoopsTpl->assign('lid' , $lid);
$xoopsTpl->assign('cid' , $view_links->getVar('cid'));
$xoopsTpl->assign('logourl' , $logourl);
// pour les vignettes "new" et "mis à jour"
$new = tdmlinks_Thumbnail($view_links->getVar('date'), $view_links->getVar('status'));
$pop = tdmlinks_Popular($view_links->getVar('hits'));
$xoopsTpl->assign('title' , $view_links->getVar('title'));$xoopsTpl->assign('new' , $new);$xoopsTpl->assign('pop' , $pop);
$xoopsTpl->assign('adminlink' , $adminlink);
$xoopsTpl->assign('date' , formatTimestamp($view_links->getVar('date'),'s'));
$xoopsTpl->assign('author' , XoopsUser::getUnameFromId($view_links->getVar('submitter')));
$xoopsTpl->assign('hits', sprintf(_MD_TDMLINKS_SINGLELINK_NBTELECH,$view_links->getVar('hits')));
$xoopsTpl->assign('rating', number_format($view_links->getVar('rating'),1));
$xoopsTpl->assign('votes', sprintf(_MD_TDMLINKS_SINGLELINK_VOTES,$view_links->getVar('votes')));
$xoopsTpl->assign('nb_comments', sprintf(_MD_TDMLINKS_SINGLELINK_COMMENTS,$view_links->getVar('comments')));
$xoopsTpl->assign('shwo_bookmark' , $xoopsModuleConfig['shwo_bookmark']);
$xoopsTpl->assign('show_social' , $xoopsModuleConfig['show_social']);
//paypal
if( $view_links->getVar('paypal') != '' && $xoopsModuleConfig['use_paypal'] == true) {
    $paypal = '<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="'.$view_links->getVar('paypal').'">
    <input type="hidden" name="item_name" value="' . sprintf(_MD_TDMLINKS_SINGLELINK_PAYPAL, $view_links->getVar('title')) . ' (' . XoopsUser::getUnameFromId(!empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0) . ')">
    <input type="hidden" name="currency_code" value="' . $xoopsModuleConfig['currency_paypal'] . '">
    <input type="image" src="' . $xoopsModuleConfig['image_paypal'] . '" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
    </form>';
} else {
    $paypal = false;
}
$xoopsTpl->assign('paypal', $paypal);

// pour les champs supplémentaires
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('status', 1));
$links_field = $linksfield_Handler->getall($criteria);
$nb_champ = count($links_field);
$champ_sup ='';
$champ_sup_vide = 0;
foreach (array_keys($links_field) as $i) {
    if ($links_field[$i]->getVar('status_def') == 1){
        if ($links_field[$i]->getVar('fid') == 1){
            //page d'accueil
            if ($view_links->getVar('homepage') != ''){
                $champ_sup = '&nbsp;' . _AM_TDMLINKS_FORMHOMEPAGE . ':&nbsp;<a href="' . $view_links->getVar('homepage') . '">' . _MD_TDMLINKS_SINGLELINK_ICI . '</a>';
                $champ_sup_vide++;
            }
        }
        if ($links_field[$i]->getVar('fid') == 2){
            //version
            if ($view_links->getVar('version') != ''){
                $champ_sup = '&nbsp;' . _AM_TDMLINKS_FORMVERSION . ':&nbsp;' . $view_links->getVar('version');
                $champ_sup_vide++;
            }
        }
        if ($links_field[$i]->getVar('fid') == 3){
            //taille du fichier
            $size_value_arr = explode(' ', $view_links->getVar('size'));
            if ($size_value_arr[0] != ''){
                $champ_sup = '&nbsp;' . _AM_TDMLINKS_FORMSIZE . ':&nbsp;' . $view_links->getVar('size');
                $champ_sup_vide++;
            }
        }
        if ($links_field[$i]->getVar('fid') == 4){
            //plateforme
            if ($view_links->getVar('platform') != ''){
                $champ_sup = '&nbsp;' . _AM_TDMLINKS_FORMPLATFORM . ':&nbsp;' . $view_links->getVar('platform');
                $champ_sup_vide++;
            }
        }
    }else{
        $view_data = $linksfielddata_Handler->get();
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('lid', $_REQUEST['lid']));
        $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
        $linksfielddata = $linksfielddata_Handler->getall($criteria);
        $contenu = '';
        foreach (array_keys($linksfielddata) as $j) {
            $contenu = $linksfielddata[$j]->getVar('data', 'n');
        }
        if ($contenu != ''){
            $champ_sup = '&nbsp;' . $links_field[$i]->getVar('title') . ':&nbsp;' . $contenu;
            $champ_sup_vide++;
        }
    }
    if ($champ_sup != ''){
        $xoopsTpl->append('champ_sup', array('image' => $uploadurl_field . $links_field[$i]->getVar('img'), 'data' => $champ_sup));
    }
    $champ_sup ='';
}
if ($nb_champ > 0 && $champ_sup_vide > 0){
    $xoopsTpl->assign('sup_aff', true);
}else{
    $xoopsTpl->assign('sup_aff', false);
}
//permission
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_modif', $perm_modif);
$categories = TDMLinks_MygetItemIds('tdmlinks_link', 'TDMLinks');
$item = TDMLinks_MygetItemIds('tdmlinks_link_item', 'TDMLinks');
if ($xoopsModuleConfig['permission_link'] == 1) {
    if (!in_array($view_links->getVar('cid'), $categories)) {
        $xoopsTpl->assign('perm_link', false);
    }else{
        $xoopsTpl->assign('perm_link', true);
    }
}else{
    if (!in_array($view_links->getVar('lid'), $item)) {
        $xoopsTpl->assign('perm_link', false);
    }else{
        $xoopsTpl->assign('perm_link', true);
    }
}

// pour utiliser tellafriend.
if (($xoopsModuleConfig['usetellafriend'] == 1) and (is_dir('../tellafriend'))){
    $string = sprintf(_MD_TDMLINKS_SINGLELINK_INTLINKFOUND,$xoopsConfig['sitename'].':  '.XOOPS_URL.'/modules/TDMLinks/singlelink.php?lid=' . $_REQUEST['lid']);
    $subject = sprintf(_MD_TDMLINKS_SINGLELINK_INTLINKFOUND,$xoopsConfig['sitename']);
    if( stristr( $subject , '%' ) ) $subject = rawurldecode( $subject ) ;
    if( stristr( $string , '%3F' ) ) $string = rawurldecode( $string ) ;
    if( preg_match( '/('.preg_quote(XOOPS_URL,'/').'.*)$/i' , $string , $matches ) ) {
        $target_uri = str_replace( '&amp;' , '&' , $matches[1] ) ;
    } else {
        $target_uri = XOOPS_URL . $_SERVER['REQUEST_URI'] ;
    }
    $tellafriend_texte = '<a target="_top" href="' . XOOPS_URL . '/modules/tellafriend/index.php?target_uri=' . rawurlencode( $target_uri ) . '&amp;subject='.rawurlencode( $subject ) . '">' . _MD_TDMLINKS_SINGLELINK_TELLAFRIEND . '</a>';
}else{
    $tellafriend_texte = '<a target="_top" href="mailto:?subject=' . rawurlencode(sprintf(_MD_TDMLINKS_SINGLELINK_INTLINKFOUND,$xoopsConfig['sitename'])) . '&amp;body=' . rawurlencode(sprintf(_MD_TDMLINKS_SINGLELINK_INTLINKFOUND,$xoopsConfig['sitename']).':  ' . XOOPS_URL.'/modules/TDMLinks/singlelink.php?lid=' . $_REQUEST['lid']) . '">' . _MD_TDMLINKS_SINGLELINK_TELLAFRIEND . '</a>';
}
$xoopsTpl->assign('tellafriend_texte', $tellafriend_texte);

// référencement
// tags
if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))){
    require_once XOOPS_ROOT_PATH.'/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tags', true);
    $xoopsTpl->assign('tagbar', tagBar($_REQUEST['lid'], 0));
} else {
    $xoopsTpl->assign('tags', false);
}

// titre de la page
$pagetitle = $view_links->getVar('title') . ' - ';
$pagetitle .= TDMLinks_PathTreeUrl($mytree, $view_links->getVar('cid'), $linkscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
$xoopsTpl->assign('xoops_pagetitle', $pagetitle);
//description
if (strpos($description,'[pagebreak]')==false){
    $description_short = substr($description,0,400);
}else{
    $description_short = substr($description,0,strpos($description,'[pagebreak]'));
}
$xoTheme->addMeta( 'meta', 'description', strip_tags($description_short));
//keywords
/*$keywords = substr($keywords,0,-1);
$xoTheme->addMeta( 'meta', 'keywords', $keywords);*/
include XOOPS_ROOT_PATH.'/include/comment_view.php';
include XOOPS_ROOT_PATH.'/footer.php';
?>  