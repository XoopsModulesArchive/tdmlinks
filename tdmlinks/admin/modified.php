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
            $modified_admin = new ModuleAdmin();
            echo $modified_admin->addNavigation('modified.php');
        }
        $criteria = new CriteriaCompo();
        if (isset($_REQUEST['limit'])) {
             $criteria->setLimit($_REQUEST['limit']);
             $limit = $_REQUEST['limit'];
         } else {
             $criteria->setLimit($xoopsModuleConfig['perpageadmin']);
             $limit = $xoopsModuleConfig['perpageadmin'];
         }
        if (isset($_REQUEST['start'])) {
            $criteria->setStart($_REQUEST['start']);
            $start = $_REQUEST['start'];
        } else {
            $criteria->setStart(0);
             $start = 0;
         }
        $criteria->setSort('requestid');
        $criteria->setOrder('ASC');
        $linksmod_arr = $linksmod_Handler->getall($criteria);
        $numrows = $linksmod_Handler->getCount($criteria);
        if ( $numrows > $limit ) {
            $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=liste&limit=' . $limit);
             $pagenav = $pagenav->renderNav(4);
         } else {
             $pagenav = '';
         }
        //Affichage du tableau des téléchargements modifiés
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center">' . _AM_TDMLINKS_FORMTITLE . '</th>';
            echo '<th align="center" width="20%">' . _AM_TDMLINKS_BROKEN_SENDER . '</th>';
            echo '<th align="center" width="15%">'._AM_TDMLINKS_FORMACTION.'</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($linksmod_arr) as $i) {
                $class = ($class == 'even') ? 'odd' : 'even';
                $links_lid = $linksmod_arr[$i]->getVar('lid');
                $links_requestid = $linksmod_arr[$i]->getVar('requestid');
                $links =& $links_Handler->get($linksmod_arr[$i]->getVar('lid'));
                // pour savoir si le fichier est nouveau
                $links_url = $links->getVar('url');
                $modlinks_url = $linksmod_arr[$i]->getVar('url');
                $new_file = ($links_url == $modlinks_url ? false : true);
                 echo '<tr class="' . $class . '">';
                echo '<td align="center">' . $links->getVar('title') . '</td>';
                echo '<td align="center"><b>' . XoopsUser::getUnameFromId($linksmod_arr[$i]->getVar('modifysubmitter')) . '</b></td>';
                echo '<td align="center" width="15%">';
                echo '<a href="modified.php?op=view_links&links_lid=' . $links_lid . '&mod_id=' . $links_requestid . '"><img src="../images/icon/view_mini.png" alt="' . _AM_TDMLINKS_FORMDISPLAY . '" title="' . _AM_TDMLINKS_FORMDISPLAY . '"></a> ';
                echo '<a href="modified.php?op=del_modlinks&mod_id=' . $links_requestid . '&new_file=' . $new_file . '"><img src="../images/icon/ignore_mini.png" alt="' . _AM_TDMLINKS_FORMIGNORE . '" title="' . _AM_TDMLINKS_FORMIGNORE . '"></a>';
                echo '</td>';
             }
             echo '</table><br />';
             echo '<br /><div align=right>' . $pagenav . '</div><br />';
        }else{
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_TDMLINKS_ERREUR_NOBMODLINKS . '</div>';
        }
    break;

    // Affiche la comparaison de fichier
    case "view_links":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMLinks_checkModuleAdmin()){
            $modified_admin = new ModuleAdmin();
            echo $modified_admin->addNavigation('modified.php');
            $modified_admin->addItemButton(_MI_TDMLINKS_ADMENU5, 'modified.php', 'list');
            echo $modified_admin->renderButton();
        }
        //information du téléchargement
        $view_links = $links_Handler->get($_REQUEST['links_lid']);
        //information du téléchargement modifié
        $view_modlinks = $linksmod_Handler->get($_REQUEST['mod_id']);

        // original
        $links_title = $view_links->getVar('title');
        $links_url = $view_links->getVar('url');
        //catégorie
        $view_categorie = $linkscat_Handler->get($view_links->getVar('cid'));
        $links_categorie = $view_categorie->getVar('cat_title');
        $links_homepage = $view_links->getVar('homepage');
        $links_version = $view_links->getVar('version');
        $links_size = $view_links->getVar('size');
        $links_platform = $view_links->getVar('platform');
        $links_description = $view_links->getVar('description');
        $links_logourl = $view_links->getVar('logourl');
        // modifié
        $modlinks_title = $view_modlinks->getVar('title');
        $modlinks_url = $view_modlinks->getVar('url');
        //catégorie
        $view_categorie = $linkscat_Handler->get($view_modlinks->getVar('cid'));
        $modlinks_categorie = $view_categorie->getVar('cat_title');
        $modlinks_homepage = $view_modlinks->getVar('homepage');
        $modlinks_version = $view_modlinks->getVar('version');
        $modlinks_size = $view_modlinks->getVar('size');
        $modlinks_platform = $view_modlinks->getVar('platform');
        $modlinks_description = $view_modlinks->getVar('description');
        $modlinks_logourl = $view_modlinks->getVar('logourl');
        echo "<style type=\"text/css\">\n";
        echo ".style_dif {color: #FF0000; font-weight: bold;}\n";
        echo ".style_ide {color: #009966; font-weight: bold;}\n";
        echo "</style>\n";
        //originale
        echo '<table width="100%" border="0" cellspacing="1" class="outer"><tr class="odd"><td>';
        echo '<table border="1" cellpadding="5" cellspacing="0" align="center"><tr><td>';
        echo '<h4>' . _AM_TDMLINKS_MODIFIED_ORIGINAL . '</h4>';
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td valign="top" width="50%"><small><span class="' . ($links_title == $modlinks_title ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMTITLE . '</span>: ' . $links_title . '</small></td>';
        echo '<td valign="top" rowspan="14"><small><span class="' . ($links_description == $modlinks_description ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMTEXT . '</span>:<br />' . $links_description . '</small></td>';
        echo '</tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_url == $modlinks_url ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMURL . '</span>:<br />' . $links_url . '</small></td></tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_categorie == $modlinks_categorie ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMCAT . '</span>: ' . $links_categorie . '</small></td></tr>';
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('status', 1));
        $links_field = $linksfield_Handler->getall($criteria);
        foreach (array_keys($links_field) as $i) {
            if ($links_field[$i]->getVar('status_def') == 1){
                if ($links_field[$i]->getVar('fid') == 1){
                    //page d'accueil
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_homepage == $modlinks_homepage ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMHOMEPAGE . '</span>: <a href="' . $links_homepage . '">' . $links_homepage . '</a></small></td></tr>';
                }
                if ($links_field[$i]->getVar('fid') == 2){
                    //version
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_version == $modlinks_version ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMVERSION . '</span>: ' . $links_version . '</small></td></tr>';
                }
                if ($links_field[$i]->getVar('fid') == 3){
                    //taille du fichier
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_size == $modlinks_size ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMSIZE . '</span>: ' . $links_size  . '</small></td></tr>';
                }
                if ($links_field[$i]->getVar('fid') == 4){
                    //plateforme
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_platform == $modlinks_platform ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMPLATFORM . '</span>: ' . $links_platform  . '</small></td></tr>';
                }
            }else{
                //original
                $contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $_REQUEST['links_lid']));
                $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
                $linksfielddata = $linksfielddata_Handler->getall($criteria);
                foreach (array_keys($linksfielddata) as $j) {
                    $contenu = $linksfielddata[$j]->getVar('data');
                }
                //proposé
                $mod_contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $_REQUEST['mod_id']));
                $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
                $linksfieldmoddata = $linksfieldmoddata_Handler->getall($criteria);
                foreach (array_keys($linksfieldmoddata) as $j) {
                    $mod_contenu = $linksfieldmoddata[$j]->getVar('moddata');
                }
                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $mod_contenu ? 'style_ide' : 'style_dif') . '">' . $links_field[$i]->getVar('title') . '</span>: ' . $contenu  . '</small></td></tr>';
            }
        }
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_logourl == $modlinks_logourl ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMIMG . '</span>:<br /> <img src="' . $uploadurl_shots . $links_logourl . '" alt="" title=""></small></td></tr>';
        echo '</table>';
        //proposé
        echo '</td></tr><tr><td>';
        echo '<h4>' . _AM_TDMLINKS_MODIFIED_MOD . '</h4>';
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td valign="top" width="40%"><small><span class="' . ($links_title == $modlinks_title ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMTITLE . '</span>: ' . $modlinks_title . '</small></td>';
        echo '<td valign="top" rowspan="14"><small><span class="' . ($links_description == $modlinks_description ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMTEXT . '</span>:<br />' . $modlinks_description . '</small></td>';
        echo '</tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_url == $modlinks_url ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMURL . '</span>:<br />' . $modlinks_url . '</small></td></tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_categorie == $modlinks_categorie ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMCAT . '</span>: ' . $modlinks_categorie . '</small></td></tr>';
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('status', 1));
        $links_field = $linksfield_Handler->getall($criteria);
        foreach (array_keys($links_field) as $i) {
            if ($links_field[$i]->getVar('status_def') == 1){
                if ($links_field[$i]->getVar('fid') == 1){
                    //page d'accueil
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_homepage == $modlinks_homepage ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMHOMEPAGE . '</span>: <a href="' . $modlinks_homepage . '">' . $modlinks_homepage . '</a></small></td></tr>';
                }
                if ($links_field[$i]->getVar('fid') == 2){
                    //version
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_version == $modlinks_version ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMVERSION . '</span>: ' . $modlinks_version . '</small></td></tr>';
                }
                if ($links_field[$i]->getVar('fid') == 3){
                    //taille du fichier
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_size == $modlinks_size ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMSIZE . '</span>: ' . $modlinks_size  . '</small></td></tr>';
                }
                if ($links_field[$i]->getVar('fid') == 4){
                    //plateforme
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_platform == $modlinks_platform ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMPLATFORM . '</span>: ' . $modlinks_platform  . '</small></td></tr>';
                }
            }else{
                //original
                $contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $_REQUEST['links_lid']));
                $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
                $linksfielddata = $linksfielddata_Handler->getall($criteria);
                foreach (array_keys($linksfielddata) as $j) {
                    $contenu = $linksfielddata[$j]->getVar('data');
                }
                //proposé
                $mod_contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $_REQUEST['mod_id']));
                $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
                $linksfieldmoddata = $linksfieldmoddata_Handler->getall($criteria);
                foreach (array_keys($linksfieldmoddata) as $j) {
                    $mod_contenu = $linksfieldmoddata[$j]->getVar('moddata');
                }
                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $mod_contenu ? 'style_ide' : 'style_dif') . '">' . $links_field[$i]->getVar('title') . '</span>: ' . $mod_contenu  . '</small></td></tr>';
            }
        }
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($links_logourl == $modlinks_logourl ? 'style_ide' : 'style_dif') . '">' . _AM_TDMLINKS_FORMIMG . '</span>:<br /> <img src="' . $uploadurl_shots . $modlinks_logourl . '" alt="" title=""></small></td></tr>';
        echo '</table>';
        echo '</table>';
        echo '</td></tr></table>';
        //permet de savoir si le fichier est nouveau
        $new_file = ($links_url == $modlinks_url ? false : true);
        echo '<table><tr><td>';
        echo myTextForm('modified.php?op=approve&mod_id=' . $_REQUEST['mod_id'] . '&new_file=' . $new_file , _AM_TDMLINKS_FORMAPPROVE);
        echo '</td><td>';
        echo myTextForm('links.php?op=edit_links&links_lid=' . $_REQUEST['links_lid'], _AM_TDMLINKS_FORMEDIT);
        echo '</td><td>';
        echo myTextForm('modified.php?op=del_modlinks&mod_id=' . $_REQUEST['mod_id'] . '&new_file=' . $new_file, _AM_TDMLINKS_FORMIGNORE);
        echo '</td></tr></table>';
    break;

    // permet de suprimmer le téléchargment modifié
    case "del_modlinks":
        $obj =& $linksmod_Handler->get($_REQUEST['mod_id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('links.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($_REQUEST['new_file']==true){
                $urlfile = substr_replace($obj->getVar('url'),'',0,strlen($uploadurl_links));
                // permet de donner le chemin du fichier
                $urlfile = $uploaddir_links . $urlfile;
                // si le fichier est sur le serveur il es détruit
                if (is_file($urlfile)){
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
            }
            // supression des data des champs sup
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $_REQUEST['mod_id']));
            $links_fielddata = $linksfieldmoddata_Handler->getall( $criteria );
            foreach (array_keys($links_fielddata) as $i) {
                $objfielddata =& $linksfieldmoddata_Handler->get($links_fielddata[$i]->getVar('modiddata'));
                $linksfieldmoddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
            }
            if ($linksmod_Handler->delete($obj)) {
                redirect_header('modified.php', 1, _AM_TDMLINKS_REDIRECT_DELOK);
            }
            echo $objvotedata->getHtmlErrors();
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMLinks_checkModuleAdmin()){
                $modified_admin = new ModuleAdmin();
                $modified_admin->addItemButton(_MI_TDMLINKS_ADMENU5, 'modified.php', 'list');
                echo $modified_admin->renderButton();
            }
            xoops_confirm(array('ok' => 1, 'mod_id' => $_REQUEST['mod_id'], 'new_file' => $_REQUEST['new_file'], 'op' => 'del_modlinks'), $_SERVER['REQUEST_URI'], _AM_TDMLINKS_MODIFIED_SURDEL . '<br>');
        }
    break;

    // permet d'accépter la modification
    case "approve":
        // choix du téléchargement:
        $view_modlinks = $linksmod_Handler->get($_REQUEST['mod_id']);
        $obj =& $links_Handler->get($view_modlinks->getVar('lid'));
        // permet d'effacer le fichier actuel si un nouveau fichier proposé est accepté.
        if ($_REQUEST['new_file']==true){
            $urlfile = substr_replace($obj->getVar('url'),'',0,strlen($uploadurl_links));
            // permet de donner le chemin du fichier
            $urlfile = $uploaddir_links . $urlfile;
            // si le fichier est sur le serveur il es détruit
            if (is_file($urlfile)){
                chmod($urlfile, 0777);
                unlink($urlfile);
            }
        }
        // mise à jour:
        $obj->setVar('title', $view_modlinks->getVar('title'));
        $obj->setVar('url', $view_modlinks->getVar('url'));
        $obj->setVar('cid', $view_modlinks->getVar('cid'));
        $obj->setVar('homepage', $view_modlinks->getVar('homepage'));
        $obj->setVar('version', $view_modlinks->getVar('version'));
        $obj->setVar('size', $view_modlinks->getVar('size'));
        $obj->setVar('platform', $view_modlinks->getVar('platform'));
        $obj->setVar('description', $view_modlinks->getVar('description'));
        $obj->setVar('logourl', $view_modlinks->getVar('logourl'));
        $obj->setVar('date', time());
        $obj->setVar('status', 2);
        // Récupération des champs supplémentaires:
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $links_field = $linksfield_Handler->getall($criteria);
        foreach (array_keys($links_field) as $i) {
            $contenu = '';
            $iddata = 0;
            if ($links_field[$i]->getVar('status_def') == 0){
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $view_modlinks->getVar('requestid')));
                $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
                $linksfieldmoddata = $linksfieldmoddata_Handler->getall($criteria);
                foreach (array_keys($linksfieldmoddata) as $j) {
                    $contenu = $linksfieldmoddata[$j]->getVar('moddata');
                }
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $view_modlinks->getVar('lid')));
                $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
                $linksfielddata = $linksfielddata_Handler->getall($criteria);
                foreach (array_keys($linksfielddata) as $j) {
                    $iddata = $linksfielddata[$j]->getVar('iddata');
                }
                if ($iddata == 0){
                    $objdata =& $linksfielddata_Handler->create();
                    $objdata->setVar('fid', $links_field[$i]->getVar('fid'));
                    $objdata->setVar('lid', $view_modlinks->getVar('lid'));
                }else{
                    $objdata =& $linksfielddata_Handler->get($iddata);
                }
                $objdata->setVar('data', $contenu);
                $linksfielddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
            }
        }
        // supression du rapport de modification
        $objmod =& $linksmod_Handler->get($_REQUEST['mod_id']);
        $linksmod_Handler->delete($objmod);
        // supression des data des champs sup
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('lid', $_REQUEST['mod_id']));
        $links_fielddata = $linksfieldmoddata_Handler->getall( $criteria );
        foreach (array_keys($links_fielddata) as $i) {
            $objfielddata =& $linksfieldmoddata_Handler->get($links_fielddata[$i]->getVar('modiddata'));
            $linksfieldmoddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
        }
        // enregistrement
        if ($links_Handler->insert($obj)){
            redirect_header('modified.php', 1, _AM_TDMLINKS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
?>