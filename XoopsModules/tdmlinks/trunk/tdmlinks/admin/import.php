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
xoops_cp_header();
if (TDMLinks_checkModuleAdmin()) {
    $import_admin = new ModuleAdmin();
    echo $import_admin->addNavigation('import.php');
}
//Action dans switch
if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'index';
}

// import depuis mylinks
function Import_mylinks($path = '', $imgurl = '')
{
    $ok = isset($_POST['ok']) ? (int)($_POST['ok']) : 0;
    global $xoopsDB;
    if ($ok == 1) {
        //Vider les tables
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_broken"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_cat"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_links"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_fielddata"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_modfielddata"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_votedata"));
        //Inserer les donn�es des cat�gories
        $query_topic = $xoopsDB->query("SELECT cid, pid, title, imgurl FROM " . $xoopsDB->prefix("mylinks_cat"));
        while ($donnees = $xoopsDB->fetchArray($query_topic)) {
            if ($donnees['imgurl'] == "") {
                $img = "blank.gif";
            } else {
                $img = substr_replace($donnees['imgurl'], '', 0, strlen($imgurl));
                @copy($path . $img, XOOPS_ROOT_PATH . "/uploads/tdmlinks/images/cats/" . $img);
            }

            $title  = $donnees['title'];
            $insert = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix("tdmlinks_cat") . " (cat_cid, cat_pid, cat_title, cat_imgurl, cat_description_main, cat_weight ) VALUES ('" . $donnees['cid'] . "', '" . $donnees['pid'] . "', '" . $title . "', '" . $img . "', '', '0')");
            if (!$insert) {
                echo "<font color='red'>" . _AM_TDMLINKS_IMPORT_ERROR_DATA . ": </font> " . $donnees['title'] . "<br>";
            }
            echo sprintf(_AM_TDMLINKS_IMPORT_CAT_IMP . '<br/>', $donnees['title']);
        }
        echo '<br/>';

        //Inserer les donnees des t�l�chargemnts
        $query_links = $xoopsDB->query("SELECT lid, cid, title, url, logourl, submitter, status, date, hits, rating, votes, comments FROM " . $xoopsDB->prefix("mylinks_links"));
        while ($donnees = $xoopsDB->fetchArray($query_links)) {
            //On recupere le title
            $requete = $xoopsDB->queryF("SELECT title FROM " . $xoopsDB->prefix("mylinks_title") . " WHERE lid = '" . $donnees['lid'] . "'");
            list($description) = $xoopsDB->fetchRow($requete);
            $insert = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix("tdmlinks_links") . " (
            lid, cid, title, url, logourl, submitter, status, date, hits, rating, votes, comments) VALUES
            ('" . $donnees['lid'] . "', '" . $donnees['cid'] . "', '" . $donnees['title'] . "', '" . $donnees['url'] . "', '" . $donnees['logourl'] . "', '" . $donnees['submitter'] . "', '" . $donnees['status'] . "', '" . $donnees['date'] . "', '" . $donnees['hits'] . "', '" . $donnees['rating'] . "', '" . $donnees['votes'] . "', '" . $donnees['comments'] . "')");
            if (!$insert) {
                echo "<font color='red'>" . _AM_TDMLINKS_IMPORT_ERROR_DATA . ": </font> " . $donnees['title'] . "<br>";
            }
            echo sprintf(_AM_TDMLINKS_IMPORT_LINKS_IMP . '<br/>', $donnees['title']);
            @copy($path . $donnees['logourl'], XOOPS_ROOT_PATH . "/uploads/tdmlinks/images/shots/" . $donnees['logourl']);
        }
        echo '<br/>';
        //Inserer les donnees des votes
        $query_vote = $xoopsDB->query("SELECT ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp FROM " . $xoopsDB->prefix("mylinks_votedata"));
        while ($donnees = $xoopsDB->fetchArray($query_vote)) {
            $insert = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix("tdmlinks_votedata") . " (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp ) VALUES ('" . $donnees['ratingid'] . "', '" . $donnees['lid'] . "', '" . $donnees['ratinguser'] . "', '" . $donnees['rating'] . "', '" . $donnees['ratinghostname'] . "', '" . $donnees['ratingtimestamp'] . "')");
            if (!$insert) {
                echo "<font color='red'>" . _AM_TDMLINKS_IMPORT_ERROR_DATA . ": </font> " . $donnees['ratingid'] . "<br>";
            }
            echo sprintf(_AM_TDMLINKS_IMPORT_VOTE_IMP . '<br/>', $donnees['ratingid']);
        }
        echo "<br><br>";
        echo "<div class='errorMsg'>";
        echo _AM_TDMLINKS_IMPORT_OK;
        echo "</div>";
    } else {
        xoops_confirm(array('op' => 'import_mylinks', 'ok' => 1, 'path' => $path, 'imgurl' => $imgurl), 'import.php', _AM_TDMLINKS_IMPORT_CONF_MYLINKS . '<br>');
    }
}

// import depuis WF-links
function Import_wflinks($shots = '', $catimg = '')
{
    $ok = isset($_POST['ok']) ? (int)($_POST['ok']) : 0;
    global $xoopsDB;
    if ($ok == 1) {
        //Vider les tables
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_broken"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_cat"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_links"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_fielddata"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_modfielddata"));
        $query = $xoopsDB->queryF("truncate table " . $xoopsDB->prefix("tdmlinks_votedata"));
        //Inserer les donn�es des cat�gories
        $query_topic = $xoopsDB->query("SELECT cid, pid, title, imgurl, description, total, summary, spotlighttop, spotlighthis, dohtml, dosmiley, doxcode, doimage, dobr, weight, formulize_fid FROM " . $xoopsDB->prefix("wflinks_cat"));
        while ($donnees = $xoopsDB->fetchArray($query_topic)) {
            if ($donnees['imgurl'] == "") {
                $img = "blank.gif";
            } else {
                $img = $donnees['imgurl'];
                @copy($catimg . $img, XOOPS_ROOT_PATH . "/uploads/tdmlinks/images/cats/" . $img);
            }
            $insert = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix("tdmlinks_cat") . " (cat_cid, cat_pid, cat_title, cat_imgurl, cat_description_main, cat_weight ) VALUES ('" . $donnees['cid'] . "', '" . $donnees['pid'] . "', '" . $donnees['title'] . "', '" . $img . "', '" . addcslashes($donnees['description'],
                    "'") . "', '" . $donnees['weight'] . "')");
            if (!$insert) {
                echo "<font color='red'>" . _AM_TDMLINKS_IMPORT_ERROR_DATA . ": </font> " . $donnees['title'] . "<br>";
            }
            echo sprintf(_AM_TDMLINKS_IMPORT_CAT_IMP . '<br/>', $donnees['title']);
        }
        echo '<br/>';

        //Inserer les donnees des t�l�chargemnts
        $query_links = $xoopsDB->query("SELECT lid, cid, title, url, filename, filetype, homepage, version, size, platform, screenshot, screenshot2, screenshot3, screenshot4, submitter, publisher, status, date, hits, rating, votes, comments, license, mirror, price, paypalemail, features, requirements, homepagetitle, forumid, limitations, versiontypes, dhistory, published, expired, updated, offline, summary, description, ipaddress, notifypub, formulize_idreq  FROM " . $xoopsDB->prefix("wflinks_links"));
        while ($donnees = $xoopsDB->fetchArray($query_links)) {
            if ($donnees['url'] == '') {
                $newurl = XOOPS_URL . '/uploads/' . $donnees['filename'];
            } else {
                $newurl = $donnees['url'];
            }
            $insert = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix("tdmlinks_links") . " (
            lid, cid, title, url, homepage, version, size, platform, description, logourl, submitter, status, date, hits, rating, votes, comments, top) VALUES
            ('" . $donnees['lid'] . "', '" . $donnees['cid'] . "', '" . $donnees['title'] . "', '" . $newurl . "', '" . $donnees['homepage'] . "', '" . $donnees['version'] . "', '" . $donnees['size'] . "', '" . $donnees['platform'] . "', '" . addcslashes($donnees['description'],
                    "'") . "',  '" . $donnees['screenshot'] . "', '" . $donnees['submitter'] . "', '" . $donnees['status'] . "', '" . $donnees['date'] . "', '" . $donnees['hits'] . "', '" . $donnees['rating'] . "', '" . $donnees['votes'] . "', '0', '0' )");

            if (!$insert) {
                echo "<font color='red'>" . _AM_TDMLINKS_IMPORT_ERROR_DATA . ": </font> " . $donnees['title'] . "<br>";
            }
            echo sprintf(_AM_TDMLINKS_IMPORT_LINKS_IMP . '<br/>', $donnees['title']);
            @copy($shots . $donnees['screenshot'], XOOPS_ROOT_PATH . "/uploads/tdmlinks/images/shots/" . $donnees['screenshot']);
        }
        echo '<br/>';

        //Inserer les donnees des votes
        $query_vote = $xoopsDB->query("SELECT ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp FROM " . $xoopsDB->prefix("wflinks_votedata"));
        while ($donnees = $xoopsDB->fetchArray($query_vote)) {
            $insert = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix("tdmlinks_votedata") . " (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp ) VALUES ('" . $donnees['ratingid'] . "', '" . $donnees['lid'] . "', '" . $donnees['ratinguser'] . "', '" . $donnees['rating'] . "', '" . $donnees['ratinghostname'] . "', '" . $donnees['ratingtimestamp'] . "')");
            if (!$insert) {
                echo "<font color='red'>" . _AM_TDMLINKS_IMPORT_ERROR_DATA . ": </font> " . $donnees['ratingid'] . "<br>";
            }
            echo sprintf(_AM_TDMLINKS_IMPORT_VOTE_IMP . '<br/>', $donnees['ratingid']);
        }
        echo "<br><br>";
        echo "<div class='errorMsg'>";
        echo _AM_TDMLINKS_IMPORT_OK;
        echo "</div>";
    } else {
        xoops_confirm(array('op' => 'import_wflinks', 'ok' => 1, 'shots' => $shots, 'catimg' => $catimg), 'import.php', _AM_TDMLINKS_IMPORT_CONF_WFLINKS . '<br>');
    }
}

switch ($op) {
    case "index":
    default:
        echo "<br><br>";
        echo "<div class='errorMsg'>";
        echo _AM_TDMLINKS_IMPORT_WARNING;
        echo "</div>";
        echo "<br><br>";
        if (TDMLinks_checkModuleAdmin()) {
            $a_admin = new ModuleAdmin();
            $a_admin->addItemButton(_AM_TDMLINKS_IMPORT_MYLINKS, 'import.php?op=form_mylinks', 'add');
            $a_admin->addItemButton(_AM_TDMLINKS_IMPORT_WFLINKS, 'import.php?op=form_wflinks', 'add');
            echo $a_admin->renderButton("center");
        }
        break;

    // import Mylinks
    case "import_mylinks":
        if ($_REQUEST['path'] == '' || $_REQUEST['imgurl'] == '') {
            redirect_header('import.php?op=form_mylinks', 3, _AM_TDMLINKS_IMPORT_ERREUR);
        } else {
            Import_mylinks($_REQUEST['path'], $_REQUEST['imgurl']);
        }
        break;

    case "form_mylinks":
        echo "<br><br>";
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_TDMLINKS_IMPORT_NUMBER . "</legend>";
        global $xoopsDB;
        $query = $xoopsDB->query("SELECT COUNT(lid) as count FROM " . $xoopsDB->prefix("mylinks_links"));
        list($count_links) = $xoopsDB->fetchRow($query);
        if ($count_links < 1) {
            echo _AM_TDMLINKS_IMPORT_DONT_LINKS . "<br />";
        } else {
            echo sprintf(_AM_TDMLINKS_IMPORT_NB_LINKS, $count_links);
        }
        $query = $xoopsDB->query("SELECT COUNT(cid) as count FROM " . $xoopsDB->prefix("mylinks_cat"));
        list($count_topic) = $xoopsDB->fetchRow($query);
        if ($count_topic < 1) {
            echo "" . _AM_TDMLINKS_IMPORT_DONT_TOPIC . "<br>";
        } else {
            echo sprintf('<br/>' . _AM_TDMLINKS_IMPORT_NB_CAT, $count_topic);
        }
        echo "</fieldset>";
        echo "<br><br>";
        echo "<table width='100%' border='0'>
                <form action='import.php?op=import_mylinks' method=POST>
                <tr>
                    <td  class='even'>" . _AM_TDMLINKS_IMPORT_MYLINKS_PATH . "</td>
                    <td  class='odd'><input type='text' name='path' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/mylinks/images/shots/' /></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMLINKS_IMPORT_MYLINKS_URL . "</td>
                    <td  class='odd'><input type='text' name='imgurl' id='import_data' size='100' value='" . XOOPS_URL . "/modules/mylinks/images/shots/' /></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMLINKS_IMPORT_LINKS . "</td>
                    <td  class='odd'><input type='submit' name='button' id='import_data' value='" . _AM_TDMLINKS_IMPORT1 . "'></td>
                </tr>
                </form>
            </table>";
        break;

    // import WF-links
    case "import_wflinks":
        if ($_REQUEST['shots'] == '' || $_REQUEST['catimg'] == '') {
            redirect_header('import.php?op=form_wflinks', 3, _AM_TDMLINKS_IMPORT_ERREUR);
        } else {
            Import_wflinks($_REQUEST['shots'], $_REQUEST['catimg']);
        }
        break;

    case "form_wflinks":
        echo "<br><br>";
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_TDMLINKS_IMPORT_NUMBER . "</legend>";
        global $xoopsDB;
        $query = $xoopsDB->query("SELECT COUNT(lid) as count FROM " . $xoopsDB->prefix("wflinks_links"));
        list($count_links) = $xoopsDB->fetchRow($query);
        if ($count_links < 1) {
            echo _AM_TDMLINKS_IMPORT_DONT_LINKS . "<br />";
        } else {
            echo sprintf(_AM_TDMLINKS_IMPORT_NB_LINKS, $count_links);
        }
        $query = $xoopsDB->query("SELECT COUNT(cid) as count FROM " . $xoopsDB->prefix("wflinks_cat"));
        list($count_topic) = $xoopsDB->fetchRow($query);
        if ($count_topic < 1) {
            echo "" . _AM_TDMLINKS_IMPORT_DONT_TOPIC . "<br>";
        } else {
            echo sprintf('<br/>' . _AM_TDMLINKS_IMPORT_NB_CAT, $count_topic);
        }
        echo "</fieldset>";
        echo "<br><br>";
        echo "<table width='100%' border='0'>
                <form action='import.php?op=import_wflinks' method=POST>
                <tr>
                    <td  class='even'>" . _AM_TDMLINKS_IMPORT_WFLINKS_SHOTS . "</td>
                    <td  class='odd'><input type='text' name='shots' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/wflinks/images/screenshots/' /></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMLINKS_IMPORT_WFLINKS_CATIMG . "</td>
                    <td  class='odd'><input type='text' name='catimg' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/wflinks/images/category/' /></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMLINKS_IMPORT_LINKS . "</td>
                    <td  class='odd'><input type='submit' name='button' id='import_data' value='" . _AM_TDMLINKS_IMPORT1 . "'></td>
                </tr>
                </form>
            </table>";
        break;
}

xoops_cp_footer();
