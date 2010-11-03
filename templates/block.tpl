{*
 * @file block.tpl
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_voyeur
 *
 * @brief Outputs all the necessary information for Voyeur to rest within the sidebar.
 *}

{*
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software, which
 * was originally created and maintained by John Willinsky. Thank you.
 *}
<br />
<div class="block" id="sidebarVoyeur" style="text-align: center;">
	<img id="voyeurLogo" src="{$baseUrl}/plugins/generic/voyeur/templates/images/voyeur.png" alt="{translate key="plugins.generic.voyeur.logo.altText"}" border="0" />
	<br />
	{** if values are set, echo them but if they're not use default values **}
	<iframe width="{if $voyeurWidth}{$voyeurWidth|escape}{else}95{/if}%" height="{if $voyeurHeight}{$voyeurHeight|escape}{else}250{/if}"
		src='' style="display:none;" id="voyeurIframe"><p>Your browser does not support iframes - Voyeur will not run.</p></iframe>
	<div id='voyeurMessageBox'><!-- 'View full page' link (or no articles message) placed dynamically here. --></div>
	<br />
	<input alt="#TB_inline?height=300&width=325&inlineId=voyeurControls" title="Voyeur - {translate key='plugins.generic.voyeur.settings.slogan'}" class="thickbox" type="button" value="Reveal" id="voyeurReveal" />
	<div style="display:none">
		<div id="voyeurControls">
			<br />
			<h3>{translate key="plugins.generic.voyeur.settings.whatShould"}</h3>
			<small>{translate key="plugins.generic.voyeur.settings.userExplain"}</small>
			<br />
			<br />
			<table width="100%" class="data">
				<tbody>
					<tr valign="middle">
						<td width="25%" class="label" align="right"><strong>{translate key="plugins.generic.voyeur.settings.voyeurTool"}</strong></td>
						<td width="75%" class="value">
							<select id="userTool" name="userTool" title="{translate key='plugins.generic.voyeur.settings.voyeurTool'}">
								<option value="Bubbles">{translate key="plugins.generic.voyeur.settings.voyeurTool_bubbles"}</option>
								<option value="Cirrus" selected="selected">Cirrus</option>
								<option value="CorpusTypeFrequenciesGrid">{translate key="plugins.generic.voyeur.settings.voyeurTool_frequencyGrid"}</option>
								<option value="Links">{translate key="plugins.generic.voyeur.settings.voyeurTool_links"}</option>
								<option value="Reader">{translate key="plugins.generic.voyeur.settings.voyeurTool_reader"}</option>
								<option value="CorpusSummary">{translate key="plugins.generic.voyeur.settings.voyeurTool_summary"}</option>
								<option value="WordCountFountain">{translate key="plugins.generic.voyeur.settings.voyeurTool_wordCountFountain"}</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<td width="25%" class="label" align="right"><input type="radio" name="userDisplayItems" id="userDisplayItems-journal" value="0" checked="checked" /></td>
						<td width="75%" class="value">{translate key="plugins.generic.voyeur.settings.entireJournal"}</td>
					</tr>
					<tr valign="top">
						<td width="25%" class="label" align="right"><input type="radio" name="userDisplayItems" id="userDisplayItems-issue" value="1" /></td>
						<td width="75%" class="value">{translate key="plugins.generic.voyeur.settings.currentIssue"}</td>
					</tr>
					<tr valign="top">
						<td width="25%" class="label" align="right"><input type="radio" name="userDisplayItems" id="userDisplayItems-recent" value="2" /></td>
						<td width="75%" class="value">
						<input type="text" name="recentItems" id="userRecentItems" size="2" maxlength="4" class="textField" />
							{translate key="plugins.generic.voyeur.settings.recentArticles"}</td>
					</tr>
					<tr valign="top">
						<td width="25%" class="label" align="right"><input type="radio" name="userDisplayItems" id="userDisplayItems-page" value="3" /></td>
						<td width="75%" class="value">
							{translate key="plugins.generic.voyeur.settings.currentPage"}
						</td>
				</tr>
					<tr valign="middle">
						<td width="25%" class="label" align="right"><strong>{translate key="plugins.generic.voyeur.settings.voyeurTime"}</strong></td>
						<td width="75%" class="value">
							<select id="userTime" name="userTime" title="{translate key='plugins.generic.voyeur.settings.voyeurTime'}">
								<option value="none">{translate key="plugins.generic.voyeur.settings.voyeurTime_none"}</option>
								<option value="day">{translate key="plugins.generic.voyeur.settings.voyeurTime_day"}</option>
								<option value="week">{translate key="plugins.generic.voyeur.settings.voyeurTime_week"}</option>
								<option value="2weeks">{translate key="plugins.generic.voyeur.settings.voyeurTime_2weeks"}</option>
								<option value="month" selected="selected">{translate key="plugins.generic.voyeur.settings.voyeurTime_month"}</option>
								<option value="6months">{translate key="plugins.generic.voyeur.settings.voyeurTime_6months"}</option>
								<option value="year">{translate key="plugins.generic.voyeur.settings.voyeurTime_year"}</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<input type="button" id="voyeurOptionsSubmit" value="Submit" onclick="parent.tb_remove();" />
		</div>
	</div>
</div>

<div id='voyeurUrlHolder' style='display:none'></div>