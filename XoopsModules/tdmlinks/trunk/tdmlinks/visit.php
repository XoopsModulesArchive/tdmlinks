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

error_reporting(0);
include "header.php";

$lid = TDMLinks_CleanVars($_REQUEST, 'lid', 0, 'int');
$cid = TDMLinks_CleanVars($_REQUEST, 'cid', 0, 'int');
// redirection si le téléchargement n'existe pas
$view_links = $links_Handler->get($lid);
if (count($view_links) == 0){
    redirect_header('index.php', 3, _MD_TDMLINK_SINGLELINK_NONEXISTENT);
    exit();
}
//redirection si pas de permission (cat)
$categories = TDMLinks_MygetItemIds('tdmlinks_view', 'TDMlinks');
if(!in_array($view_links->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
    exit();
}
//redirection si pas de permission (télécharger)
if ($xoopsModuleConfig['permission_link'] == 2) {
    $item = TDMLinks_MygetItemIds('tdmlinks_link_item', 'TDMlinks');
    if(!in_array($view_links->getVar('lid'), $item)) {
        redirect_header('singlelink.php?lid=' . $view_links->getVar('lid'), 2, _MD_TDMLINK_SINGLELINK_NOPERMlink);
        exit();
    }
}else{
    $categories = TDMLinks_MygetItemIds('tdmlinks_link', 'TDMlinks');
    if(!in_array($view_links->getVar('cid'), $categories)) {
        redirect_header('singlelink.php?lid=' . $view_links->getVar('lid'), 2, _MD_TDMLINK_SINGLELINK_NOPERMlink);
        exit();
    }
}
//check link limit option
if ($xoopsModuleConfig['linklimit'] == 1&&$view_links->getVar('price')==0) {
    $limitlid = $xoopsModuleConfig['limitlid'];
    $limitglobal = $xoopsModuleConfig['limitglobal'];
    $yesterday = strtotime(formatTimestamp(time()-86400));
    if ($limitlid > 0) {
        $criteria = new CriteriaCompo();
        if ($xoopsUser) {
            $criteria->add(new Criteria('linklimit_uid', $xoopsUser->getVar('uid') , '='));
        }else{
            $criteria->add(new Criteria('linklimit_hostname', getenv("REMOTE_ADDR"), '='));
        }
        $criteria->add(new Criteria('linklimit_lid', $lid , '='));
        $criteria->add(new Criteria('linklimit_date', $yesterday , '>'));
        $numrows = $linkslimit_Handler->getCount($criteria);
        if($numrows >= $limitlid){
            redirect_header('singlelink.php?lid=' . $view_links->getVar('lid'), 5, sprintf(_MD_TDMLINK_SINGLELINK_LIMITLID, $numrows, $limitlid));
            exit();
        }
    }
    if ($limitglobal > 0) {
        $criteria = new CriteriaCompo();
        if ($xoopsUser) {
            $criteria->add(new Criteria('linklimit_uid', $xoopsUser->getVar('uid') , '='));
        }else{
            $criteria->add(new Criteria('linklimit_hostname', getenv("REMOTE_ADDR"), '='));
        }
        $criteria->add(new Criteria('linklimit_date', $yesterday , '>'));
        $numrows = $linkslimit_Handler->getCount($criteria);
        if($numrows >= $limitglobal){
            redirect_header('singlelink.php?lid=' . $view_links->getVar('lid'), 5, sprintf(_MD_TDMLINK_SINGLELINK_LIMITGLOBAL, $numrows, $limitglobal));
            exit();
        }
    }

    $obj =& $linkslimit_Handler->create();
    $obj->setVar('linklimit_lid', $lid);
    $obj->setVar('linklimit_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
    $obj->setVar('linklimit_hostname', getenv("REMOTE_ADDR"));
    $obj->setVar('linklimit_date', strtotime(formatTimestamp(time())));
    $linkslimit_Handler->insert($obj) or $obj->getHtmlErrors();
    // purge
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('linklimit_date', (time() - 172800) , '<'));
    $numrows = $linkslimit_Handler->getCount($criteria);
    echo 'a détruire: ' . $numrows . '<br/>';
    $linkslimit_Handler->deleteAll($criteria);
}

@$xoopsLogger->activated = false;
error_reporting(0);
if ( $xoopsModuleConfig['check_host'] ) {
    $goodhost      = 0;
    $referer       = parse_url(xoops_getenv('HTTP_REFERER'));
    $referer_host  = $referer['host'];
    foreach ( $xoopsModuleConfig['referers'] as $ref ) {
        if ( !empty($ref) && preg_match("/".$ref."/i", $referer_host) ) {
            $goodhost = "1";
            break;
        }
    }
    if (!$goodhost) {
        redirect_header(XOOPS_URL . "/modules/TDMLinks/singlelink.php?cid=$cid&amp;lid=$lid", 30, _MD_TDMLINK_NOPERMISETOLINK);
        exit();
    }
}

if ($view_links->getVar('price')>0&&strlen($view_links->getVar('currency'))==3&&$xoopsModuleConfig['xpayment'] == true) {
	$purchase_Handler =& xoops_getmodulehandler('tdmlinks_purchase', 'TDMlinks');
	$invoice_Handler =& xoops_getmodulehandler('invoice', 'xpayment');
		
	$criteria = new CriteriaCompo(new Criteria('purchaselinks', $xoopsModuleConfig['purchaseable_links'], '<'));
	$criteria->add(new Criteria('lid', $lid));
	$criteria->add(new Criteria('purchaseip', xoops_getenv('HTTP_REFERER')));
	$criteria->add(new Criteria('purchasehostname', gethostbyaddr(xoops_getenv('HTTP_REFERER'))));
	if (is_object($GLOBALS['xoopsUser']))
		$criteria->add(new Criteria('purchaseuid', $GLOBALS['xoopsUser']->getVar('uid')));
		
	$paid_criteria = new CriteriaCompo($criteria);
	$paid_criteria->add(new Criteria('`mode`', 'paid'));
	
	$unpaid_criteria = new CriteriaCompo($criteria);
	$unpaid_criteria->add(new Criteria('`mode`', 'unpaid'));
	
	if ($purchase_Handler->getCount($paid_criteria)>0) {
		foreach($purchase_Handler->getObjects($paid_criteria, true) as $purchaseid => $purchase) {
			$purchase->setVar('purchaselinks', $purchase->getVar('purchaselinks')+1);
			@$purchase_Handler->insert($purchase, true);
		}
		
		// ajout +1 pour les hits
		$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("tdmlinks_links"), $lid);
		$xoopsDB->queryF($sql);
		
		$url = $view_links->getVar('url', 'n');
		if (!preg_match("/^ed2k*:\/\//i", $url)) {
		    Header("Location: $url");
		}
		echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $url . "\"></meta></head><body></body></html>";
		exit();		
	} elseif ($purchase_Handler->getCount($unpaid_criteria)>0) {
		foreach($purchase_Handler->getObjects($unpaid_criteria, true) as $purchaseid => $purchase) {
			$criteria = new CriteriaCompo(new Criteria('plugin', 'tdmlinks'));
			$criteria->add(new Criteria('key', $purchaseid.'|'.$view_links->getVar('lid')));
			foreach($invoice_Handler->getObjects($criteria, true) as $invid => $invoice) {
			    redirect_header($invoice->getURL(), 5, _MD_TDMLINK_SINGLELINK_UNPAID_INVOICE);
		        exit();
			}
		}
	} else {
		$purchase = $purchase_Handler->create();
		$purchase->setVar('mode', 'unpaid');
		if (is_object($GLOBALS['xoopsUser']))
			$purchase->setVar('purchaseuid', $GLOBALS['xoopsUser']->getVar('uid'));
		else {
        	redirect_header(XOOPS_URL . "/modules/TDMLinks/singlelink.php?cid=$cid&amp;lid=$lid", 30, _MD_TDMLINK_NEEDTOBELOGGEDIN);
        	exit();
		}
		
		$purchase->setVar('lid', $lid);
		$purchase->setVar('purchaseip', xoops_getenv('HTTP_REFERER'));
		$purchase->setVar('purchasehostname', gethostbyaddr(xoops_getenv('HTTP_REFERER')));
		$purchase->setVar('purchaseinvoiced', time());
		if ($purchase_id = $purchase_Handler->insert($purchase, true)) {
			$param = array();
			$param['op'] = 'createinvoice';
			$param['plugin'] = 'tdmlinks';
			$param['drawfor'] = $GLOBALS['xoopsConfig']['sitename'];
			$param['drawto'] = (strlen($GLOBALS['xoopsUser']->getVar('name'))>0?$GLOBALS['xoopsUser']->getVar('name').' ('.$GLOBALS['xoopsUser']->getVar('uname').')':$GLOBALS['xoopsUser']->getVar('uname').' ('.$GLOBALS['xoopsUser']->getVar('uid').')');
			$param['drawto_email'] = $GLOBALS['xoopsUser']->getVar('email');
			$param['currency'] = $view_links->getVar('currency');
			$param['key'] = $purchase_id.'|'.$lid;
			$param['weight_unit'] = 'kgs';
			$param['item[A][cat]'] = $view_links->getVar('catelogue_number');
			$param['item[A][name]'] = $view_links->getVar('title');
			$param['item[A][amount]'] = $view_links->getVar('price');
			$param['item[A][quantity]'] = 1;
			$param['return'] = XOOPS_URL . "/modules/TDMLinks/visit.php?cid=$cid&amp;lid=$lid";
			$param['cancel'] = XOOPS_URL . "/modules/TDMLinks/singlelink.php?cid=$cid&amp;lid=$lid";
	
			$url = XOOPS_URL.'/modules/xpayment/?';
			foreach($param as $var => $value)
				$req .= '<input type="hidden" name="'.$var.'" value="'.$value.'" />';
				
			echo '<form action="'.$url.'" name="myform" method="post">';
			echo $req;
			echo '</form>';
			echo '<script type="text/javascript">';
			echo 'document.forms.myform.submit();';
			echo '</script>';
			exit(0);
		} else {
        	redirect_header(XOOPS_URL . "/modules/TDMLinks/singlelink.php?cid=$cid&amp;lid=$lid", 30, _MD_TDMLINK_NOPERMISETOLINK);
        	exit();
		}
	}
}

// ajout +1 pour les hits
$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("tdmlinks_links"), $lid);
$xoopsDB->queryF($sql);

$url = $view_links->getVar('url', 'n');
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    Header("Location: $url");
}
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $url . "\"></meta></head><body></body></html>";
exit();
?>