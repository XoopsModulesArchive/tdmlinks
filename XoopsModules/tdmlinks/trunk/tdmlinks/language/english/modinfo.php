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

// Nom du module
define("_MI_TDMLINKS_NAME","TDMLinks");

// Description du module
define("_MI_TDMLINKS_DESC","Creates a links section where users can link/submit/rate various links.");

// Bloc
define("_MI_TDMLINKS_BNAME1","Recent links");
define("_MI_TDMLINKS_BNAMEDSC1","Display Recent links");
define("_MI_TDMLINKS_BNAME2","Top link");
define("_MI_TDMLINKS_BNAMEDSC2","Display Top links");
define("_MI_TDMLINKS_BNAME3","Top Rated link");
define("_MI_TDMLINKS_BNAMEDSC3","Display Top Rated links");
define("_MI_TDMLINKS_BNAME4","Random links");
define("_MI_TDMLINKS_BNAMEDSC4","Display linked files randomly");
define("_MI_TDMLINKS_BNAME5","Search links");
define("_MI_TDMLINKS_BNAMEDSC5","Search form link");

// Sous menu
define("_MI_TDMLINKS_SMNAME1","Suggest");
define("_MI_TDMLINKS_SMNAME2","Links List");

// Menu administration
define("_MI_TDMLINKS_ADMENU1","Index");
define("_MI_TDMLINKS_ADMENU2","Categories Management");
define("_MI_TDMLINKS_ADMENU3","Links Management");
define("_MI_TDMLINKS_ADMENU4","Broken links");
define("_MI_TDMLINKS_ADMENU5","Modified links");
define("_MI_TDMLINKS_ADMENU6","Extra Fields Management");
define("_MI_TDMLINKS_ADMENU7","Import");
define("_MI_TDMLINKS_ADMENU8","Permissions");
define("_MI_TDMLINKS_ADMENU9","About");

// Préférences
define("_MI_TDMLINKS_PREFERENCE_BREAK_GENERAL", "General");
define("_MI_TDMLINKS_POPULAR", "Number of hits for linkable items to be marked as popular");
define("_MI_TDMLINKS_AUTO_SUMMARY","Automatic Summary ?");
define("_MI_TDMLINKS_SHOW_UPDATED","Show the 'updated' and 'new' picture ?");
define("_MI_TDMLINKS_USESHOTS", "Use logo ?");
define("_MI_TDMLINKS_IMGFLOAT", "Image float");
define("_MI_TDMLINKS_IMGFLOAT_LEFT", "Left");
define("_MI_TDMLINKS_IMGFLOAT_RIGHT", "Right");
define("_MI_TDMLINKS_SHOTWIDTH", "Logo height");
define("_MI_TDMLINKS_PLATEFORM","Platforms");
define("_MI_TDMLINKS_PLATEFORM_DSC","Enter the authorized platforms separated by a |");
define("_MI_TDMLINKS_USETELLAFRIEND", "Use Tellafriend module with the link tell a friend?");
define("_MI_TDMLINKS_USETELLAFRIENDDSC", "You have to install Tellafriend module in order to use this option");
define("_MI_TDMLINKS_USETAG", "Use TAG module to generate tags");
define("_MI_TDMLINKS_USETAGDSC", "You have to install TAG module in order to use this option");
define("_MI_TDMLINKS_FORM_OPTIONS","Editor");
define("_MI_TDMLINKS_PREFERENCE_BREAK_USER", "User");
define("_MI_TDMLINKS_PERPAGE", "Number of items per page");
define("_MI_TDMLINKS_NBDOWCOL","This option allows you to choose the number of columns of links");
define("_MI_TDMLINKS_NEWDLS", "Number of new files in the Home Page");
define("_MI_TDMLINKS_TOPORDER","How to display items on the index page?");
define("_MI_TDMLINKS_TOPORDER1","Date (DESC)");
define("_MI_TDMLINKS_TOPORDER2","Date (ASC)");
define("_MI_TDMLINKS_TOPORDER3","hits (DESC)");
define("_MI_TDMLINKS_TOPORDER4","hits (ASC)");
define("_MI_TDMLINKS_TOPORDER5","Votes (DESC)");
define("_MI_TDMLINKS_TOPORDER6","Votes (ASC)");
define("_MI_TDMLINKS_TOPORDER7","Title (DESC)");
define("_MI_TDMLINKS_TOPORDER8","Title (ASC)");
define("_MI_TDMLINKS_PERPAGELISTE", "links displayed on the files list");
define("_MI_TDMLINKS_SEARCHORDER","How to display items on the files list?");
define("_MI_TDMLINKS_SUBCATPARENT", "Number of Sub-Categories to display in the main Category");
define("_MI_TDMLINKS_NBCATCOL","This option allows you to choose the number of columns of categories");
define("_MI_TDMLINKS_BLDATE", "Display recent links in home page and categories (Summary)?");
define("_MI_TDMLINKS_BLPOP", "Display popular links in home page and categories (Summary)?");
define("_MI_TDMLINKS_BLRATING", "Display top rated links in home page and categories (Summary)?");
define("_MI_TDMLINKS_NBBL", "Number of link to display in summary?");
define("_MI_TDMLINKS_LONGBL", "Title lenght in Summary");
define("_MI_TDMLINKS_BOOKMARK", "Bookmark");
define("_MI_TDMLINKS_BOOKMARK_DSC", "Show/hide bookmark Icons");
define("_MI_TDMLINKS_SOCIAL", "Social Networks");
define("_MI_TDMLINKS_SOCIAL_DSC", "Show/hide social network Icons");
define("_MI_TDMLINKS_linkFLOAT", "link page float");
define("_MI_TDMLINKS_LINKFLOAT_DSC", "<ul><li>Left to Right : Show link description in left side and info column in right side</li><li>Right to Left : Show link description in right side and info column in left side</li></ul>");
define("_MI_TDMLINKS_LINKFLOAT_LTR", "Left to Right");
define("_MI_TDMLINKS_LINKFLOAT_RTL", "Right to Left");
define("_MI_TDMLINKS_PREFERENCE_BREAK_ADMIN", "Administration");
define("_MI_TDMLINKS_PERPAGEADMIN", "Number of items per page in the administration");
define("_MI_TDMLINKS_PREFERENCE_BREAK_LINKS", "links");
define("_MI_TDMLINKS_PERMISSIONlink","Select the type of permission for  'link Permission'");
define("_MI_TDMLINKS_PERMISSIONlink1","Permission by category");
define("_MI_TDMLINKS_PERMISSIONlink2","Permission by file");
define("_MI_TDMLINKS_LINK_NAME", "Rename files uploaded?");
define("_MI_TDMLINKS_LINK_NAMEDSC", "If the option is no; and you uploaded a file with a name already exists on the server it will be overwritten");
define("_MI_TDMLINKS_LINK_PREFIX", "Prefix of files uploaded");
define("_MI_TDMLINKS_LINK_PREFIXDSC", "Only valid if the option to rename the uploaded files is yes");
define("_MI_TDMLINKS_MAXUPLOAD_SIZE","Max uploaded files size");
define("_MI_TDMLINKS_MIMETYPE","authorized mime types ");
define("_MI_TDMLINKS_MIMETYPE_DSC","Enter the authorized mime types separated by a |");
define("_MI_TDMLINKS_CHECKHOST", "Disallow direct link linking (leeching) ?");
define("_MI_TDMLINKS_REFERERS", "This Sites can directly link you files.Separate each one with |");
define("_MI_TDMLINKS_DOWNLIMIT", "link limit");
define("_MI_TDMLINKS_DOWNLIMITDSC", "Use link limit option");
define("_MI_TDMLINKS_LIMITGLOBAL", "Number of links in 24 hours");
define("_MI_TDMLINKS_LIMITGLOBALDSC", "Number of link for each user in 24 hours. Select 0 for no limited.");
define("_MI_TDMLINKS_LIMITLID", "Number of dowanload for each file in 24 hours");
define("_MI_TDMLINKS_LIMITLIDDSC", "Number of link for each file in 24 hours by each user. Select 0 for no limited.");
define("_MI_TDMLINKS_PREFERENCE_BREAK_PAYPAL", "Paypal");
define("_MI_TDMLINKS_USEPAYPAL","Use button 'Donate' of Paypal ");
define("_MI_TDMLINKS_CURRENCYPAYPAL","Currency Donation");
define("_MI_TDMLINKS_IMAGEPAYPAL","Image for the button 'Make a Donation'");
define("_MI_TDMLINKS_IMAGEPAYPALDSC","Please enter the address of the image");
define("_MI_TDMLINKS_PREFERENCE_BREAK_RSS", "RSS");
define("_MI_TDMLINKS_PERPAGERSS", "RSS number of links");
define("_MI_TDMLINKS_PERPAGERSSDSCC", "Number of links displayed on RSS pages");
define("_MI_TDMLINKS_TIMECACHERSS", "RSS cache time");
define("_MI_TDMLINKS_TIMECACHERSSDSC", "Cache time for RSS pages in minutes");
define("_MI_TDMLINKS_LOGORSS", "Site logo for RSS pages");
define("_MI_TDMLINKS_PREFERENCE_BREAK_COMNOTI", "Comments and notifications");

// Notifications
define("_MI_TDMLINKS_GLOBAL_NOTIFY", "Global");
define("_MI_TDMLINKS_GLOBAL_NOTIFYDSC", "Global links notification options.");
define("_MI_TDMLINKS_CATEGORY_NOTIFY", "Category");
define("_MI_TDMLINKS_CATEGORY_NOTIFYDSC", "Notification options that apply to the current file category.");
define("_MI_TDMLINKS_FILE_NOTIFY", "File");
define("_MI_TDMLINKS_FILE_NOTIFYDSC", "Notification options that apply to the current file.");
define("_MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFY", "New Category");
define("_MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFYCAP", "Notify me when a new file category is created.");
define("_MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFYDSC", "Receive notification when a new file category is created");
define("_MI_TDMLINKS_GLOBAL_NEWCATEGORY_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New file category");
define("_MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFY", "Modify File Requested");
define("_MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFYCAP", "Notify me of any file modification request.");
define("_MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFYDSC", "Receive notification when any file modification request is submitted.");
define("_MI_TDMLINKS_GLOBAL_FILEMODIFY_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : File Modification Requested");
define("_MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFY", "Broken File Submitted");
define("_MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFYCAP", "Notify me of any broken file report.");
define("_MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFYDSC", "Receive notification when any broken file report is submitted.");
define("_MI_TDMLINKS_GLOBAL_FILEBROKEN_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : Broken File Reported");
define("_MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFY", "File Submitted");
define("_MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFYCAP", "Notify me when any new file is submitted (awaiting approval).");
define("_MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFYDSC", "Receive notification when any new file is submitted (awaiting approval).");
define("_MI_TDMLINKS_GLOBAL_FILESUBMIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} notification automatique : New file submitted");
define("_MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFY", "New File");
define("_MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFYCAP", "Notify me when any new file is posted.");
define("_MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFYDSC", "Receive notification when any new file is posted.");
define("_MI_TDMLINKS_GLOBAL_NEWFILE_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New file");
define("_MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFY", "File Submitted");
define("_MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFYCAP", "Notify me when a new file is submitted (awaiting approval) to the current category.");
define("_MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFYDSC", "Receive notification when a new file is submitted (awaiting approval) to the current category.");
define("_MI_TDMLINKS_CATEGORY_FILESUBMIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New file submitted in category");
define("_MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFY", "New File");
define("_MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFYCAP", "Notify me when a new file is posted to the current category.");
define("_MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFYDSC", "Receive notification when a new file is posted to the current category.");
define("_MI_TDMLINKS_CATEGORY_NEWFILE_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} New file in category");
define("_MI_TDMLINKS_FILE_APPROVE_NOTIFY", "File Approved");
define("_MI_TDMLINKS_FILE_APPROVE_NOTIFYCAP", "Notify me when this file is approved.");
define("_MI_TDMLINKS_FILE_APPROVE_NOTIFYDSC", "Receive notification when this file is approved.");
define("_MI_TDMLINKS_FILE_APPROVE_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : File Approved");

// Version 1.62 - SAR - 18/08/2011
// preferences
define('_MI_TDMLINKS_XPAYMENT', 'Support XPayment purchases of links.');
define('_MI_TDMLINKS_XPAYMENT_DESC', 'You need to have XPayment installed for this feature to work.');
define('_MI_TDMLINKS_PURCHASABLE_LINKS', 'Number of times a purchased link can be linked.');
define('_MI_TDMLINKS_PURCHASABLE_LINKS_DESC', 'Purchased link can be linked this many times.');
define('_MI_TDMLINKS_HTACCESS', 'Mod-Rewrite SEO with .htaccess');
define('_MI_TDMLINKS_HTACCESS_DESC', 'See /docs for .htaccess to add to your XOOPS ROOT PATH');
define('_MI_TDMLINKS_BASEURL', 'Mod-rewrite SEO Base URL');
define('_MI_TDMLINKS_BASEURL_DESC', 'Do not change this unless you have altered you .htaccess for it.');
define('_MI_TDMLINKS_ENDOFURL', 'Mod-rewrite SEO end of URL');
define('_MI_TDMLINKS_ENDOFURL_DESC', 'End of URL for standard HTML Files with SEO .htaccess enabled.');
define('_MI_TDMLINKS_ENDOFURL_RSS', 'Mod-rewrite SEO end of URL for RSS Data');
define('_MI_TDMLINKS_ENDOFURL_RSS_DESC', 'End of URL for standard RSS Data with SEO .htaccess enabled.');
?>
