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

function tdmlinks_new($limit=0, $offset=0)
{
    global $xoopsDB;

    $myts =& MyTextSanitizer::getInstance();

    $URL_MOD = XOOPS_URL."/modules/tdmlinks";
    $sql = "SELECT lid, title, date, cid, submitter, hits, description FROM ".$xoopsDB->prefix("tdmlinks_LINKS")." WHERE status>0 ORDER BY date";

    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;
    $ret = array();

    while ($row = $xoopsDB->fetchArray($result)) {
        $lid = $row['lid'];
        $ret[$i]['link']     = $URL_MOD."/singlefile.php?lid=".$lid;
        $ret[$i]['cat_link'] = $URL_MOD."/viewcat.php?cid=".$row['cid'];

        $ret[$i]['title'] = $row['title'];
        $ret[$i]['time']  = $row['date'];

// atom feed
        $ret[$i]['id'] = $lid;
        $ret[$i]['description'] = $myts->makeTareaData4Show($row['description'], 0);    //no html

// category
        //$ret[$i]['cat_name'] = $row['ctitle'];

// counter
        $ret[$i]['hits'] = $row['hits'];

// this module dont show user name
        $ret[$i]['uid'] = $row['submitter'];

        $i++;
    }

    return $ret;
}

function tdmlinks_num()
{
    global $xoopsDB;

    $sql = "SELECT count(*) FROM ".$xoopsDB->prefix("tdmlinks_LINKS")." WHERE status>0 ORDER BY lid";
    $array = $xoopsDB->fetchRow($xoopsDB->query($sql));
    $num   = $array[0];
    if (empty($num)) {
        $num = 0;
    }

    return $num;
}

function tdmlinks_data($limit=0, $offset=0)
{
    global $xoopsDB;

    $sql = "SELECT lid, title, date FROM ".$xoopsDB->prefix("tdmlinks_LINKS")." WHERE status>0 ORDER BY lid";
    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;
    $ret = array();

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $id = $myrow['lid'];
        $ret[$i]['id']   = $id;
        $ret[$i]['link'] = XOOPS_URL."/modules/tdmlinks/singlefile.php?lid=".$id."";
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time']  = $myrow['date'];
        $i++;
    }

    return $ret;
}
