<div class="tdmlinks">   <!-- link logo-->	<div class="tdmlinks-logo center marg10">		<a title="<{$smarty.const._MD_TDMLINKS_LINK}>" href="<{$xoops_url}>/modules/tdmlinks/index.php"><img src="<{$xoops_url}>/modules/tdmlinks/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMLINKS_LINK}>" /></a>	</div>	<!-- Category path -->	<div class="bold marg1 pad1"><{$navigation}></div>		<!-- Submit helps -->	<div class="tdmlinks-tips">		<ul>
			<li><{$smarty.const._MD_TDMLINKS_RATELINK_VOTEONCE}></li>
			<li><{$smarty.const._MD_TDMLINKS_RATELINK_RATINGSCALE}></li>
			<li><{$smarty.const._MD_TDMLINKS_RATELINK_BEOBJECTIVE}></li>
			<li><{$smarty.const._MD_TDMLINKS_RATELINK_DONOTVOTE}></li>
		</ul>	</div>		<{if $message_erreur != ''}>	<!-- Error message-->
	<div class="errorMsg"><{$message_erreur}></div>   
	<{/if}>		<!-- Submit form -->
	<div class="tdmlinks-submitform"><{$themeForm}></div>	</div>
