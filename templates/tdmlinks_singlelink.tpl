<div class="tdmlinks">   <!-- link logo-->
    <div class="tdmlinks-logo center marg10"><a title="<{$smarty.const._MD_TDMLINKS_link}>" href="<{$xoops_url}>/modules/tdmlinks/index.php"><img src="<{$xoops_url}>/modules/tdmlinks/assets/images/logo-en.gif"
                                                                                                                                                  alt="<{$smarty.const._MD_TDMLINKS_link}>"/></a></div>
    <!-- Category path -->
    <div class="bold marg1 pad1"><{$navigation}></div>
    <!-- Start Donload body info -->
    <div class="item tdmlinks-item">
        <div class="itemHead"><span class="itemTitle"><h1><{$title}></h1></span></div> <{if $new || $pop}>
            <div class="itemInfo"><span class="itemPoster"><{$new}></span> <span class="itemPostDate"><{$pop}></span></div>
        <{/if}>
        <div class="itemBody">
            <div class="itemText tdmlinks-itemText <{$textfloat}>">
                <{if $show_screenshot == true}>
                    <{if $logourl != ''}>
                        <img class="<{$img_float}>" width="<{$shotwidth}>" src="<{$logourl}>" alt="<{$title}>"/>
                    <{/if}>
                <{/if}>
                <{$description}>
            </div>
            <div class="tdmlinks-downInfo <{$infofloat}>">
                <div class="tdmlinks-box" id="tdmlinks-box-1">
                    <div id="date"><{$smarty.const._MD_TDMLINKS_SINGLELINK_DATEPROP}>:<{$date}></div>
                    <div id="author"><{$smarty.const._MD_TDMLINKS_SINGLELINK_AUTHOR}>:<{$author}></div>
                    <div id="hits"><{$hits}></div>
                    <div id="rating"><{$smarty.const._MD_TDMLINKS_SINGLELINK_RATING}>:<{$rating}><{$votes}></div>
                    <{if $commentsnav != ''}>
                        <div id="comments"><{$nb_comments}></div>
                    <{/if}>                </div>
                <{if $sup_aff == true}>
                    <div class="tdmlinks-box" id="tdmlinks-box-2">
                        <{foreach item=champ from=$champ_sup}>
                            <div class="champ" style="background: url(<{$champ.image}>) no-repeat left;"><{$champ.data}></div>
                        <{/foreach}>                </div>
                <{/if}>
                <div class="tdmlinks-box" id="tdmlinks-box-3">
                    <{if $perm_vote != ""}>
                        <div id="torate"><a href="<{$xoops_url}>/modules/tdmlinks/ratelink.php?lid=<{$lid}>" title="<{$smarty.const._MD_TDMLINKS_SINGLELINK_RATHLINK}>"><{$smarty.const._MD_TDMLINKS_SINGLELINK_RATHLINK}></a></div>
                    <{/if}>
                    <{if $perm_modif != ""}>
                        <div id="tomodify"><a href="<{$xoops_url}>/modules/tdmlinks/modlink.php?lid=<{$lid}>" title="<{$smarty.const._MD_TDMLINKS_SINGLELINK_MODIFY}>"><{$smarty.const._MD_TDMLINKS_SINGLELINK_MODIFY}></a></div>
                    <{/if}>
                    <div id="toreport"><a href="<{$xoops_url}>/modules/tdmlinks/brokenlink.php?lid=<{$lid}>" title="<{$smarty.const._MD_TDMLINKS_SINGLELINK_REPORTBROKEN}>"><{$smarty.const._MD_TDMLINKS_SINGLELINK_REPORTBROKEN}></a></div>
                    <div id="totell"><{$tellafriend_texte}></div>
                </div> <{if $perm_link != ""}>
                    <div class="tdmlinks-box" id="tdmlinks-box-4">
                        <div id="link"><a href="visit.php?cid=<{$cid}>&amp;lid=<{$lid}>" rel="directory nofollow external"><{$smarty.const._MD_TDMLINKS_INDEX_DLNOW}></a></div>
                    </div>
                <{else}>
                    <div class="tdmlinks-box" id="tdmlinks-box-5">
                        <div id="noperm"><{$smarty.const._MD_TDMLINKS_SINGLELINK_NOPERM}></div>
                    </div>
                <{/if}>
                <{if $paypal}>
                    <div id="paypal">
                        <{$paypal}>
                    </div>
                <{/if}>            </div>
            <div class="endline"></div>
        </div> <{if $adminlink}>
            <div class="itemFoot"><span class="itemAdminLink"><{$adminlink}></span></div>
        <{/if}>    </div>
    <!-- End Donload body info --> <{if $tags}>   <!-- Tag bar-->
        <div class="tdmlinks-tag"><{include file="db:tag_bar.html"}></div>
    <{/if}>    <{if $show_social}>    <!-- Social Networks -->
        <div class="tdmlinks-socialnetwork">
            <ul>
                <li>
                    <div class="facebook">
                        <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                        <fb:like href="<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>" layout="button_count" show_faces="false"></fb:like>
                    </div>
                </li>
                <li>
                    <div class="twitter">
                        <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                        <a href="http://twitter.com/share/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>" class="twitter-share-button">Tweet</a></div>
                </li>
                <li>
                    <div class="buzz"><a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count"></a>
                        <script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>
                    </div>
                </li>
                <li>
                    <div class="google">
                        <script src="https://apis.google.com/js/plusone.js" type="text/javascript"></script>
                        <g:plusone size="medium" count="true"></g:plusone>
                    </div>
                </li>
            </ul>
        </div>
    <{/if}>        <{if $shwo_bookmark}>    <!-- Bookmarks -->
        <div class="tdmlinks-bookmarkme">
            <div class="head tdmlinks-bookmarkmetitle"><{$smarty.const._MD_TDMLINKS_BOOKMARK_ME}></div>
            <div class="tdmlinks-bookmarkmeitems">
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_BLINKLIST}>"
                   href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&Description=&Url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&Title=<{$links.title}>"> <img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_BLINKLIST}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/blinklist.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_DELICIOUS}>"
                   href="http://del.icio.us/post?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&title=<{$links.title}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_DELICIOUS}>"
                                                                                                                                                                           src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/delicious.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_DIGG}>" href="http://digg.com/submit?phase=2&url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_DIGG}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/diggman.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_FARK}>"
                   href="http://cgi.fark.com/cgi/fark/edit.pl?new_url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&new_comment=<{$links.title}>&new_link_other=<{$links.title}>&linktype=Misc"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_FARK}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/fark.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_FURL}>"
                   href="http://www.furl.net/storeIt.jsp?t=<{$links.title}>&u=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_FURL}>"
                                                                                                                                                                             src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/furl.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_NEWSVINE}>"
                   href="http://www.nwvine.com/_tools/seed&save?u=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&h=<{$links.title}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_NEWSVINE}>"
                                                                                                                                                                                    src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/newsvine.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_REDDIT}>"
                   href="http://reddit.com/submit?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&title=<{$links.title}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_REDDIT}>"
                                                                                                                                                                            src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/reddit.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_SIMPY}>"
                   href="http://www.simpy.com/simpy/LinkAdd.do?href=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&title=<{$links.title}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_SIMPY}>"
                                                                                                                                                                                          src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/simpy.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_SPURL}>"
                   href="http://www.spurl.net/spurl.php?title=<{$links.title}>&url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_SPURL}>"
                                                                                                                                                                                  src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/spurl.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_YAHOO}>"
                   href="http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<{$links.title}>&u=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_YAHOO}>"
                                                                                                                                                                                                  src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/yahoomyweb.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_BALATARIN}>" href="http://balatarin.com/links/submit?phase=2&amp;url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_BALATARIN}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/balatarin.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_FACEBOOK}>" href="http://www.facebook.com/share.php?u=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_FACEBOOK}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/facebook_share_icon.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_TWITTER}>" href="http://twitter.com/home?status=Browsing:%20<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_TWITTER}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/twitter_share_icon.gif"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_SCRIPSTYLE}>" href="http://scriptandstyle.com/submit?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_SCRIPSTYLE}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/scriptandstyle.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_STUMBLE}>" href="http://www.stumbleupon.com/submit?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_STUMBLE}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/stumbleupon.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_TECHNORATI}>" href="http://technorati.com/faves?add=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_TECHNORATI}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/technorati.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_MIXX}>" href="http://www.mixx.com/submit?page_url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_MIXX}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/mixx.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_MYSPACE}>" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_MYSPACE}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/myspace.jpg"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_DESIGNFLOAT}>" href="http://www.designfloat.com/submit.php?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_DESIGNFLOAT}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/designfloat.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_GOOLGEBUZZ}>" href="http://www.google.com/buzz/post?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_GOOLGEBUZZ}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/google_buzz_icon.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_GOOLGEREADER}>"
                   href="http://www.google.com/reader/link?url=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&amp;title=<{$links.title}>"><img alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_GOOLGEREADER}>"
                                                                                                                                                                                         src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/google-reader-icon.png"/></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_GOOLGEBOOKMARKS}>"
                   href="https://www.google.com/bookmarks/mark?op=add&amp;bkmk=<{$xoops_url}>/modules/<{$xoops_url}>/modules/tdmlinks/singlelink.php?lid=<{$links.lid}>&amp;title=<{$links.title}>"><img
                            alt="<{$smarty.const._MD_TDMLINKS_BOOKMARK_TO_GOOLGEBOOKMARKS}>" src="<{$xoops_url}>/modules/tdmlinks/assets/images/bookmarks/google-icon.png"/></a></div>
        </div>
    <{/if}>
    <div style="text-align: center; padding: 3px; margin:3px;">
        <{$commentsnav}>
        <{$lang_notice}>
    </div>
    <div style="margin:3px; padding: 3px;">
        <{if $comment_mode == "flat"}>
            <{include file="db:system_comments_flat.html"}>
        <{elseif $comment_mode == "thread"}>
            <{include file="db:system_comments_thread.html"}>
        <{elseif $comment_mode == "nest"}>
            <{include file="db:system_comments_nest.html"}>
        <{/if}>
    </div>
    <{include file="db:system_notification_select.html"}></div>
