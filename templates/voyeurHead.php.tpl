{*
 * @file voyeurHead.php.tpl
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_voyeur
 *
 * @brief Adds necessary javascript information to the head of OJS.
 *}

{*
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software, which
 * was originally created and maintained by John Willinsky. Thank you.
 *}

{* Add js/css libraries. *}
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>-->
<script type="text/javascript" src="{$pluginURL}/js/jquery-1.3.2.min.js"></script>

{* Only load Thickbox if user has choice of options. *}
{if $allowUser eq 1}
	<script type="text/javascript" src="{$pluginURL}/js/thickbox/thickbox.js"></script>
	<link rel="stylesheet" href="{$pluginURL}/js/thickbox/thickbox.css" type="text/css" media="screen" />
{/if}

{literal}
<script type="text/javascript">

	jQuery(document).ready(function($) {	
		// $() will work as an alias for jQuery() inside of this function.

		// Create references to these elements for use outside of jQuery(document).ready.
		var voyeurUrlHolder = $('#voyeurUrlHolder');
		var voyeurLogo = $('#voyeurLogo');
		var voyeurIframe = $('#voyeurIframe');
		var voyeurMessageBox = $('#voyeurMessageBox');
		var voyeurAjaxUrl = '{/literal}{url page="gateway" op="plugin" path="VoyeurGatewayPlugin"|to_array:"voyeurAjax"}{literal}';
		voyeurAjaxUrl = voyeurAjaxUrl.replace(/&amp;/gi, "&"); // Replace encoded &'s so OJS can read URL properly.

		// Remove Thickbox attributes on 'Reveal' button if user cannot choose options.
		if ('{/literal}{$allowUser|escape}{literal}' != 1) {
			$('#voyeurReveal').removeClass('thickbox').attr('alt', '');
		}
		
		// Load Voyeur automatically if admin enabled it.
		if ('{/literal}{$allowAutoReveal}{literal}' == 1) {
			// If autoReveal is on and users cannot choose options, hide the 'Reveal' button.
			if ('{/literal}{$allowUser|escape}{literal}' != 1) {
				$('#voyeurReveal').attr('style', 'display:none;');
			}
			loadVoyeur(voyeurUrlHolder, voyeurLogo, voyeurIframe, voyeurMessageBox, voyeurAjaxUrl);
		}
		
		$('#voyeurReveal').click(function() {
			if ('{/literal}{$allowUser|escape}{literal}' == 1) {
				$('#voyeurOptionsSubmit').click(function() {
					// Send the user input vars to loadVoyeur() for processing and call loadVoyeur().
					loadVoyeur(voyeurUrlHolder, voyeurLogo, voyeurIframe, voyeurMessageBox, voyeurAjaxUrl, $('#userTool').val(), $('input[name*="userDisplayItems"]:checked').val(), $('#userRecentItems').val(), $('#userTime').val());
				});
			} else {
				loadVoyeur(voyeurUrlHolder, voyeurLogo, voyeurIframe, voyeurMessageBox, voyeurAjaxUrl);
			}
		});
	});

	/**
	* Loads, launches Voyeur, and passes appropriate params to the gateway.
	* displayItems =
	*		0 - reveal items in entire journal
	*		1 - reveal items in current issue
	*		2 - reveal by recent items
	*		3 - reveal by current page
	**/
	function loadVoyeur(voyeurUrlHolder, voyeurLogo, voyeurIframe, voyeurMessageBox, voyeurAjaxUrl, customTool, customDisplayItems, customRecentItems, customTime) {
		if ({/literal}{$uglyUrl}{literal} == 1) { // Find what kind of URL we're dealing with.
			loadUrl = voyeurAjaxUrl;
		} else {
			loadUrl = voyeurAjaxUrl + '/?';
		}
		
		// =================================
		// ==   ADD USER DEFINED PARAMS   ==
		// =================================

		if (typeof customTime != 'undefined') { // Add custom user time filter if set.
			loadUrl += '&voyeurTime=' + customTime;
		}
		// If user has chosen filter by journal or issue...
		if (typeof customDisplayItems != 'undefined' && (customDisplayItems == 0 || customDisplayItems == 1 || customDisplayItems == 3)) {
			loadUrl += '&displayItems=' + customDisplayItems;
		// If user has chosen custom recent items.
		} else if (typeof customDisplayItems != 'undefined' && customDisplayItems == 2) {
			loadUrl += '&displayItems=' + customDisplayItems + '&recentItems=' + customRecentItems;	
		}
		
		// ======================================
		// ==   ADD CURRENT PAGE INFO PARAMS   ==
		// ======================================
		
		// Add page info params if we're revealing by current page.
		if ('{/literal}{$displayItems}{literal}' == 'page' || (typeof customDisplayItems != 'undefined' && customDisplayItems == 3)) {
			loadUrl += '&currentPage=' + '{/literal}{$currentPage}{literal}';
			loadUrl += '&currentOp=' + '{/literal}{$currentOp}{literal}';
			loadUrl += '&issueNumber=' + '{/literal}{$issueNumber}{literal}';
		}

		voyeurUrlHolder.load( loadUrl, function(response) {
			if (response) { // If we get any URL back...
				// If user-defined tool, place it in the URL. If not, retrieve admin defined tool.
				if (typeof customTool != 'undefined') {
					var fullVoyeurUrl = 'http://voyeurtools.org/tool/' + customTool + '/?' + response;
				} else {
					var fullVoyeurUrl = 'http://voyeurtools.org/tool/{/literal}{$voyeurTool|escape}{literal}/?' + response;
				}
				voyeurLogo.attr('style', 'display:none;'); // Hide the Voyeur logo when user chooses options.
				// Change the iFrame link to the custom URL for Voyeur, and remove the iFrame from being hidden
				voyeurIframe.attr({
					// This is the URL to be sent to retrieve Voyeur information
					src: fullVoyeurUrl
				}).removeAttr('style'); // remove display:none
				voyeurMessageBox.html('<small><a href="'+ fullVoyeurUrl +'" target="_blank">{/literal}{translate key="plugins.generic.voyeur.settings.viewSeparate"}{literal}</a></small>');
			} else { // If no articles to reveal, just display message.
				voyeurMessageBox.html('<small><i>{/literal}{translate key="plugins.generic.voyeur.settings.noArticles"}{literal}</i></small>');
			}
		});
	} // end loadVoyeur()
</script>
{/literal}