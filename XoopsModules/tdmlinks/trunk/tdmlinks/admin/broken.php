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
            $broken_admin = new ModuleAdmin();
            echo $broken_admin->addNavigation('broken.php');
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
        $criteria->setSort('reportid');
        $criteria->setOrder('ASC');
        //pour faire une jointure de table
        $linksbroken_Handler->table_link = $linksbroken_Handler->db->prefix("tdmlinks_links"); // Nom de la table en jointure
        $linksbroken_Handler->field_link = "lid"; // champ de la table en jointure
        $linksbroken_Handler->field_object = "lid"; // champ de la table courante
        $linksbroken_arr = $linksbroken_Handler->getByLink($criteria);
        $numrows = $linksbroken_Handler->getCount($criteria);
        if ( $numrows > $limit ) {
            $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&limit=' . $limit);
             $pagenav = $pagenav->renderNav(4);
         } else {
             $pagenav = '';
         }
        //Affichage du tableau des téléchargements brisés
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center" width="10%">'._AM_TDMLINKS_FORMFILE.'</th>';
            echo '<th align="center">' . _AM_TDMLINKS_FORMTITLE . '</th>';
            echo '<th align="center" width="20%">' . _AM_TDMLINKS_BROKEN_SENDER . '</th>';
            echo '<th align="center" width="15%">'._AM_TDMLINKS_FORMACTION.'</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($linksbroken_arr) as $i) {
                $class = ($class == 'even') ? 'odd' : 'even';
                $links_lid = $linksbroken_arr[$i]->getVar('lid');
                $links_reportid = $linksbroken_arr[$i]->getVar('reportid');
                $links_title = $linksbroken_arr[$i]->getVar('title');
                $links_cid = $linksbroken_arr[$i]->getVar('cid');
                $links_poster = XoopsUser::getUnameFromId($linksbroken_arr[$i]->getVar('sender'));
                $links_poster_ip = $linksbroken_arr[$i]->getVar('ip');
                 echo '<tr class="'.$class.'">';
                echo '<td align="center">';
                echo '<a href="../visit.php?cid=' . $links_cid . '&lid=' . $links_lid . '" target="_blank"><img src="../images/icon/link.png" alt="link ' . $links_title . '" title="link ' . $links_title . '"></a>';
                echo '</td>';
                echo '<td align="center">' . $links_title . '</td>';
                echo '<td align="center"><b>' . $links_poster . '</b> (' . $links_poster_ip . ')</td>';
                echo '<td align="center" width="15%">';
                echo '<a href="links.php?op=view_links&links_lid=' . $links_lid . '"><img src="../images/icon/view_mini.png" alt="' . _AM_TDMLINKS_FORMDISPLAY . '" title="' . _AM_TDMLINKS_FORMDISPLAY . '"></a> ';
                echo '<a href="links.php?op=edit_links&links_lid=' . $links_lid . '"><img src="../images/icon/edit.png" alt="' . _AM_TDMLINKS_FORMEDIT . '" title="' . _AM_TDMLINKS_FORMEDIT . '"></a> ';
                echo '<a href="broken.php?op=del_brokenlinks&broken_id=' . $links_reportid . '"><img src="../images/icon/ignore_mini.png" alt="' . _AM_TDMLINKS_FORMIGNORE . '" title="' . _AM_TDMLINKS_FORMIGNORE . '"></a>';
                echo '</td>';
             }
             echo '</table><br />';
             echo '<br /><div align=right>' . $pagenav . '</div><br />';
        }else{
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_TDMLINKS_ERREUR_NOBROKENLINKS . '</div>';
        }
    break;

    // permet de suprimmer le rapport de téléchargment brisé
    case "del_brokenlinks":
        $obj =& $linksbroken_Handler->get($_REQUEST['broken_id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('links.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($linksbroken_Handler->delete($obj)) {
                redirect_header('broken.php', 1, _AM_TDMLINKS_REDIRECT_DELOK);
            }
            echo $objvotedata->getHtmlErrors();
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMLinks_checkModuleAdmin()){
                $broken_admin = new ModuleAdmin();
                $broken_admin->addItemButton(_MI_TDMLINKS_ADMENU4, 'broken.php', 'list');
                echo $broken_admin->renderButton();
            }
            xoops_confirm(array('ok' => 1, 'broken_id' => $_REQUEST['broken_id'], 'op' => 'del_brokenlinks'), $_SERVER['REQUEST_URI'], _AM_TDMLINKS_BROKEN_SURDEL . '<br>');
        }
    break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
?>