<div class="tdmlinks">      <!-- link logo-->	<div class="tdmlinks-logo center marg10">		<a title="<{$smarty.const._MD_TDMLINKS_LINK}>" href="<{$xoops_url}>/modules/tdmlinks/index.php"><img src="<{$xoops_url}>/modules/tdmlinks/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMLINKS_LINK}>" /></a>	</div>   <{if count($categories) gt 0}>   <!-- Start Show categories information -->   <div class="tdmlinks-categories">	<table>
		<tr>	   <{foreach item=category from=$categories}>			<td>			   <div class="tdmlinks-data">			      <div class="tdmlinks-title">			         <div class="floatleft title"><h2><a title="<{$category.title}>" href="<{$xoops_url}>/modules/tdmlinks/viewcat.php?cid=<{$category.id}>"><{$category.title}></a></h2></div>				      <div class="floatright total xo-pagact"><a title="<{$category.title}>" href="<{$xoops_url}>/modules/tdmlinks/viewcat.php?cid=<{$category.id}>"><{$category.totallinks}></a></div>				      <div class="endline"></div>			      </div>			      <div class="tdmlinks-body justify">				      <{if $category.image != ""}>	      					      <a class="marg1 pad1" title="<{$category.title}>" href="<{$xoops_url}>/modules/tdmlinks/viewcat.php?cid=<{$category.id}>"><img class="<{$img_float}>" src="<{$category.image}>" alt="<{$category.title}>" /></a>
			         <{/if}>				      <{$category.description_main}>				      <div class="endline"></div>			      </div>			      <{if $category.subcategories != ""}>			      <div class="tdmlinks-subtitle"><{$smarty.const._MD_TDMLINKS_INDEX_SCAT}><ul><{$category.subcategories}></ul></div>			      <{/if}>		      </div>			</td>      <{if $category.count is div by $nb_catcol}>
      </tr>
      <tr>
      <{/if}>
      <{/foreach}>
     </tr>
	</table>   </div>   <!-- End Show categories information -->      <!-- RSS logo -->   <div class="tdmlinks-rss">	   <a title="<{$smarty.const._MD_TDMLINKS_RSS}>" href="<{$xoops_url}>/modules/tdmlinks/rss.php?cid=0"><img src="assets/images/rss.gif" alt="<{$smarty.const._MD_TDMLINKS_RSS}>" /></a>   </div>   	<{if $bl_affichage==1}>	<!-- Start Summary informations -->
	<div class="tdmlinks-linetitle"><{$smarty.const._MD_TDMLINKS_INDEX_BLNAME}></div>
	<table class="mrag2 pad2 tdmlinks-summary">
	   <tr>		   <{if $bl_date != ""}>		   <td class="width33 top">			   <div class="bold mrag2 pad2">				   <img src="<{$xoops_url}>/modules/tdmlinks/assets/images/icon/date.png" alt="<{$smarty.const._MD_TDMLINKS_INDEX_BLDATE}>" /><{$smarty.const._MD_TDMLINKS_INDEX_BLDATE}>			   </div>			   <div class="mrag2 pad2">				   <ul>
		            <{foreach item=bl_date from=$bl_date}>
		            <li><a title="<{$bl_date.title}>" href="<{$xoops_url}>/modules/tdmlinks/singlelink.php?cid=<{$bl_date.cid}>&amp;lid=<{$bl_date.id}>"><{$bl_date.title}></a> (<{$bl_date.date}>)</li>
		            <{/foreach}>
	            </ul>            </div>		   </td>
	      <{/if}>    	      <{if $bl_pop != ""}>		   <td class="width33 top">			   <div class="bold mrag2 pad2">				   <img src="<{$xoops_url}>/modules/tdmlinks/assets/images/icon/hits.png" alt="<{$smarty.const._MD_TDMLINKS_INDEX_BLPOP}>" /><{$smarty.const._MD_TDMLINKS_INDEX_BLPOP}>			   </div>			   <div class="mrag2 pad2">				   <ul>
		            <{foreach item=bl_pop from=$bl_pop}>
		            <li><a title="<{$bl_pop.title}>" href="<{$xoops_url}>/modules/tdmlinks/singlelink.php?cid=<{$bl_pop.cid}>&amp;lid=<{$bl_pop.id}>"><{$bl_pop.title}></a> (<{$bl_pop.hits}>)</li>
		            <{/foreach}>
	            </ul>            </div>		   </td>
	      <{/if}>    	      <{if $bl_rating != ""}>		   <td class="width33 top">			   <div class="bold mrag2 pad2">				   <img src="<{$xoops_url}>/modules/tdmlinks/assets/images/icon/votes.png" alt="<{$smarty.const._MD_TDMLINKS_INDEX_BLRATING}>" /><{$smarty.const._MD_TDMLINKS_INDEX_BLRATING}>			   </div>			   <div class="mrag2 pad2">				   <ul>
		            <{foreach item=bl_rating from=$bl_rating}>
		            <li><a title="<{$bl_rating.title}>" href="<{$xoops_url}>/modules/tdmlinks/singlelink.php?cid=<{$bl_rating.cid}>&amp;lid=<{$bl_rating.id}>"><{$bl_rating.title}></a> (<{$bl_rating.rating}>)</li>
		            <{/foreach}>
	            </ul>            </div>		   </td>
	      <{/if}>    
		</tr>
	</table>	<!-- End Summary informations -->
	<{/if}>      <div class="tdmlinks-thereare"><{$lang_thereare}></div>   <{/if}>   	<{if $file != ""}>	<!-- Start Show new files in index -->
   <div class="tdmlinks-linetitle"><{$smarty.const._MD_TDMLINKS_INDEX_LATESTLIST}></div>
   <table>
	   <tr>
      <!-- Start new link loop -->
      <{section name=i loop=$file}>
	      <td class="col_width<{$nb_dowcol}> top center">
	      <{include file="db:tdmlinks_link.tpl" down=$file[i]}>
	      </td>
      <{if $file[i].count is div by $nb_dowcol}>
      </tr>
      <tr>
      <{/if}>
      <{/section}>
      <!-- End new link loop -->
	   </tr>
   </table>   <!-- End Show new files in index -->
	<{/if}>	<{include file="db:system_notification_select.html"}></div>
