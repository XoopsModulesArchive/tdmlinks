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

function xoops_module_update_tdmlinks()
{
    $db  =& Database::getInstance();
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_cat') . "` CHANGE `cid` `cat_cid` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT ;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_cat') . "` CHANGE `pid` `cat_pid` INT( 5 ) UNSIGNED NOT NULL DEFAULT '0' ;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_cat') . "` CHANGE `title` `cat_title` VARCHAR( 255 ) NOT NULL ;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_cat') . "` CHANGE `imgurl` `cat_imgurl` VARCHAR( 255 ) NOT NULL ;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_cat') . "` CHANGE `description_main` `cat_description_main` TEXT NOT NULL ;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_cat') . "` CHANGE `weight` `cat_weight` INT( 11 ) NOT NULL DEFAULT '0' ;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_LINKS') . "` ADD `paypal` VARCHAR( 255 ) NOT NULL;";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_LINKS') . "` ADD `price` DECIMAL( 10,2 ) DEFAULT '0';";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_LINKS') . "` ADD `currency` VARCHAR( 3 ) DEFAULT 'USD';";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_LINKS') . "` ADD `catelogue_number` VARCHAR( 10 ) DEFAULT '';";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_LINKS') . "` CHANGE `size` `size` VARCHAR( 15 ) NOT NULL DEFAULT '';";
    $db->query($sql);
    $sql = "ALTER TABLE `" . $db->prefix('tdmlinks_mod') . "` CHANGE `size` `size` VARCHAR( 15 ) NOT NULL DEFAULT '';";
    $db->query($sql);
    $sql = "CREATE TABLE `" . $db->prefix('tdmlinks_downlimit') . "` (downlimit_id int(11) unsigned NOT NULL auto_increment, downlimit_lid int(11) unsigned NOT NULL default '0',
           downlimit_uid int(11) NOT NULL default '0', downlimit_hostname varchar(60) NOT NULL default '', downlimit_date int(10) NOT NULL default '0', PRIMARY KEY  (downlimit_id)
           ) TYPE=MyISAM";
    $db->query($sql);
    // SAR 18/08/2011
    $sql = "CREATE TABLE `" . $db->prefix('tdmlinks_purchase') . "` ( `purchaseid` int(13) unsigned NOT NULL AUTO_INCREMENT,   `mode` enum('paid','unpaid','cancelled') DEFAULT 'unpaid',
		  `lid` int(11) unsigned DEFAULT '0',  `purchaseuid` int(13) unsigned DEFAULT '0',  `purchaselinks` int(11) unsigned DEFAULT '0',  `purchaseip` varchar(128) DEFAULT NULL,
		  `purchasehostname` varchar(500) DEFAULT NULL,  `purchaseinvoiced` int(12) unsigned DEFAULT '0', `purchasepaid` int(12) unsigned DEFAULT '0',  PRIMARY KEY (`purchaseid`)
		  ) ENGINE=MyISAM";
    $db->query($sql);

    return true;
}
