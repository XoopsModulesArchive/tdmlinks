<div class="item tdmlinks-item">
    <div class="itemHead"><span class="itemTitle">			<h2><a title="<{$down.title}>" href="<{$xoops_url}>/modules/tdmlinks/singlelink.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory"><{$down.title}></a></h2>		</span></div>
    <div class="itemInfo"> <{if $down.new}> <span class="itemNew"><{$down.new}></span> <{/if}> <{if $down.pop}> <span class="itemPop"><{$down.pop}></span> <{/if}> <{if $down.perm_link != ""}>
        <span class="itemlink"><a title="<{$smarty.const._MD_TDMLINKS_INDEX_DLNOW}>" href="visit.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory external"><img src="<{$xoops_url}>/modules/tdmlinks/assets/images/icon/link.png"
                                                                                                                                                                           alt="<{$smarty.const._MD_TDMLINKS_INDEX_DLNOW}>"/></a></span>
        <{/if}> <span class="itemPoster"><{$smarty.const._MD_TDMLINKS_INDEX_SUBMITDATE}><{$down.updated}></span> <span class="itemPostDate"><{$smarty.const._MD_TDMLINKS_INDEX_SUBMITTER}><{$down.submitter}></span></div>
    <div class="itemBody">
        <div class="itemText justify"> <{if $show_screenshot == true}>
            <{if $down.logourl != ''}>
            <img class="<{$img_float}>" width="<{$shotwidth}>" src="<{$down.logourl}>" alt="<{$down.title}>"/>
            <{/if}>
            <{/if}> <{$down.description_short}>
        </div>
        <div class="endline"></div>
    </div>
    <div class="itemFoot"><span class="itemAdminLink"><{$down.adminlink}></span> <span class="itemPermaLink"><a title="<{$down.title}>" href="<{$xoops_url}>/modules/tdmlinks/singlelink.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory"><{$smarty.const._MD_TDMLINKS_MOREDETAILS}></a></span>
    </div>
</div>
