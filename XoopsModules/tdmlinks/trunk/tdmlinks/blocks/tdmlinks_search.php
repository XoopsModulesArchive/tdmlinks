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

function b_tdmlinks_search_show() {
    require_once XOOPS_ROOT_PATH."/modules/tdmlinks/include/functions.php";
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    include_once XOOPS_ROOT_PATH."/class/tree.php";
    //appel des class
    $linkscat_Handler =& xoops_getModuleHandler('tdmlinks_cat', 'TDMLinks');
    $links_Handler =& xoops_getModuleHandler('tdmlinks_links', 'TDMLinks');
    $linksfield_Handler =& xoops_getModuleHandler('tdmlinks_field', 'TDMLinks');
    $linksfielddata_Handler =& xoops_getModuleHandler('tdmlinks_fielddata', 'TDMLinks');
    //appel des fichiers de langues
    xoops_loadLanguage('main', 'TDMLinks');
    xoops_loadLanguage('admin', 'TDMLinks');

    $categories = tdmlinks_MygetItemIds('tdmlinks_view', 'TDMLinks');

    $block = array();

    //formulaire de recherche
    $form = new XoopsThemeForm(_MD_TDMLINKS_SEARCH, "search", XOOPS_URL . '/modules/TDMLinks/search.php', 'post');
    $form->setExtra('enctype="multipart/form-data"');
    //recherche par titre
    $form->addElement(new XoopsFormText(_MD_TDMLINKS_SEARCH_TITLE, 'title', 25, 255, ''));
    //recherche par catégorie
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')','IN'));
    $linkscat_arr = $linkscat_Handler->getall($criteria);
    $mytree = new XoopsObjectTree($linkscat_arr, 'cat_cid', 'cat_pid');
    $form->addElement(new XoopsFormLabel(_AM_TDMLINKS_FORMINCAT, $mytree->makeSelBox('cat', 'cat_title','--','', true)));
    //recherche champ sup.
    $linksfield_Handler =& xoops_getModuleHandler('tdmlinks_field', 'TDMLinks');
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('search', 1));
    $criteria->add(new Criteria('status', 1));
    $criteria->setSort('weight ASC, title');
    $criteria->setOrder('ASC');
    $links_field = $linksfield_Handler->getall($criteria);
    foreach (array_keys($links_field) as $i) {
        $title_sup = '';
        $contenu_arr = array();
        $lid_arr = array();
        $nom_champ = 'champ' . $links_field[$i]->getVar('fid');
        $criteria = new CriteriaCompo();
        $champ_contenu[$links_field[$i]->getVar('fid')] = 999;
        if ($links_field[$i]->getVar('status_def') == 1){
            $criteria->add(new Criteria('status', 0, '!='));
            if ($links_field[$i]->getVar('fid') == 1){
                //page d'accueil
                $title_sup = _AM_TDMLINKS_FORMHOMEPAGE;
                $criteria->setSort('homepage');
                $nom_champ_base = 'homepage';
            }
            if ($links_field[$i]->getVar('fid') == 2){
                //version
                $title_sup = _AM_TDMLINKS_FORMVERSION;
                $criteria->setSort('version');
                $nom_champ_base = 'version';
            }
            if ($links_field[$i]->getVar('fid') == 3){
                //taille du fichier
                $title_sup = _AM_TDMLINKS_FORMSIZE;
                $criteria->setSort('size');
                $nom_champ_base = 'size';
            }
            if ($links_field[$i]->getVar('fid') == 4){
                //platform
                $title_sup = _AM_TDMLINKS_FORMPLATFORM;
                $platform_array = explode('|',xoops_getModuleOption('platform', 'TDMLinks'));
                foreach( $platform_array as $platform ) {
                    $contenu_arr[$platform] = $platform;
                }
            }else{
                $criteria->setOrder('ASC');
                $tdmlinks_arr = $links_Handler->getall( $criteria );
                foreach (array_keys($tdmlinks_arr) as $j) {
                    $contenu_arr[$tdmlinks_arr[$j]->getVar($nom_champ_base)] = $tdmlinks_arr[$j]->getVar($nom_champ_base);
                }
            }
        }else{
            $title_sup = $links_field[$i]->getVar('title');
            $criteria->add(new Criteria('fid', $links_field[$i]->getVar('fid')));
            $criteria->setSort('data');
            $criteria->setOrder('ASC');
            $tdmlinks_arr = $linksfielddata_Handler->getall( $criteria );
            foreach (array_keys($tdmlinks_arr) as $j) {
                $contenu_arr[$tdmlinks_arr[$j]->getVar('data', 'n')] = $tdmlinks_arr[$j]->getVar('data');
            }
            if ($champ_contenu[$links_field[$i]->getVar('fid')] != ''){
                $criteria_1 = new CriteriaCompo();
                $criteria_1->add(new Criteria('data', $champ_contenu[$links_field[$i]->getVar('fid')]));
                $data_arr = $linksfielddata_Handler->getall($criteria_1);
                foreach (array_keys($data_arr) as $k) {
                    $lid_arr[] = $data_arr[$k]->getVar('lid');
                }

            }
            $form->addElement($select_sup);
        }
        $select_sup = new XoopsFormSelect($title_sup, $nom_champ, $champ_contenu[$links_field[$i]->getVar('fid')]);
        $select_sup->addOption(999,_MD_TDMLINKS_SEARCH_ALL1);
        $select_sup->addOptionArray($contenu_arr);
        $form->addElement($select_sup);
        unset ($select_sup);
    }
    //bouton validation
    $button_tray = new XoopsFormElementTray('' ,'');
    $button_tray->addElement(new XoopsFormButton('', 'submit', _MD_TDMLINKS_SEARCH_BT, 'submit'));
    $form->addElement($button_tray);
    $block['form'] = $form->render();
    return $block;
}
?>
