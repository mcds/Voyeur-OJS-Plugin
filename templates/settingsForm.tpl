{*
 * @file settingsForm.tpl
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_voyeur
 *
 * @brief Outputs all the necessary information for the Voyeur settings menu.
 *}
{*
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software, which
 * was originally created and maintained by John Willinsky. Thank you.
 *}
{assign var="pageTitle" value="plugins.generic.voyeur.displayName"}
{include file="common/header.tpl"}

{translate key="plugins.generic.voyeur.description"}

<div class="separator">&nbsp;</div>

<h3>{translate key="plugins.generic.voyeur.settings"}</h3>

<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

<table width="100%" class="data" id="voyeurSettingsTable">
	<tr valign="top">
		<td align="left" width="100%" class="label" colspan="2">
			<h4>{translate key="plugins.generic.voyeur.settings.generalSettings"}</h4>
		</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><strong>Tool:</strong></td>
		<td width="85%">
			<select id="voyeurTool" name="voyeurTool" title="{translate key='plugins.generic.voyeur.settings.voyeurTool'}">
				<option value="Bubbles" {if $voyeurTool eq "Bubbles"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTool_bubbles"}</option>
				<option value="Cirrus" {if $voyeurTool eq "Cirrus"} selected="selected" {/if}>Cirrus</option>
				<option value="CorpusTypeFrequenciesGrid" {if $voyeurTool eq "CorpusTypeFrequenciesGrid"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTool_frequencyGrid"}</option>
				<option value="Links" {if $voyeurTool eq "Links"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTool_links"}</option>
				<option value="Reader" {if $voyeurTool eq "Reader"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTool_reader"}</option>
				<option value="CorpusSummary" {if $voyeurTool eq "CorpusSummary"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTool_summary"}</option>
				<option value="WordCountFountain" {if $voyeurTool eq "WordCountFountain"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTool_wordCountFountain"}</option>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<td width="15%"></td>
		<td width="85%" class="value">

		</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><strong>{translate key="plugins.generic.voyeur.settings.voyeurWidth"}</strong></td>
		<td width="85%"><input type="text" name="voyeurWidth" id="voyeurWidth" value="{$voyeurWidth|escape}" size="2" maxlength="3" class="textField" />
			&nbsp;%</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><strong>{translate key="plugins.generic.voyeur.settings.voyeurHeight"}</strong></td>
		<td width="85%"><input type="text" name="voyeurHeight" id="voyeurHeight" value="{$voyeurHeight|escape}" size="2" maxlength="3" class="textField" />
			&nbsp;px.</td>
	</tr>
	<tr valign="top">
		<td></td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="checkbox" name="allowAutoReveal" id="allowAutoReveal" value="1" {if $allowAutoReveal eq '1'}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.allowAutoReveal"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="checkbox" name="allowUser" id="allowUser" value="1" {if $allowUser eq '1'}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.allowUser"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="checkbox" name="forceSingleFile" id="forceSingleFile" value="1" {if $forceSingleFile eq '1'}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.forceSingleFile"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="checkbox" name="removeFuncWords" id="removeFuncWords" value="1" {if $removeFuncWords eq '1'}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.removeFuncWords"}</td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label" align="right" valign="bottom"><strong>{translate key="plugins.generic.voyeur.settings.limit"}</strong></td>
		<td width="80%" valign="bottom"><input type="text" name="voyeurLimit" id="voyeurLimit" value="{$voyeurLimit|escape}" size="2" maxlength="3" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label" align="right" valign="bottom"><strong>{translate key="plugins.generic.voyeur.settings.query"}</strong></td>
		<td width="80%" valign="bottom"><input type="text" name="voyeurQuery" id="voyeurQuery" value="{$voyeurQuery|escape}" size="15" class="textField" /></td>
	</tr>
	<tr>
		<td colspan="2"><div class="separator">&nbsp;</div></td>
	</tr>
	<tr valign="top">
		<td align="left" width="100%" class="label" colspan="2">
			<h4>{translate key="plugins.generic.voyeur.settings.appearanceSettings"}</h4>
		</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayPage" id="displayPage-all" value="all" {if $displayPage eq "all"}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.all"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayPage" id="displayPage-homepage" value="homepage" {if $displayPage eq "homepage"}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.homepage"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayPage" id="displayPage-issue" value="issue" {if $displayPage eq "issue"}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.issue"}</td>
	</tr>
	<tr>
		<td colspan="2"><div class="separator">&nbsp;</div></td>
	</tr>
	<tr valign="top">
		<td align="left" width="100%" class="label" colspan="2">
			<h4>{translate key="plugins.generic.voyeur.settings.filterSettings"}</h4>
		</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayItems" id="displayItems-journal" value="journal" {if $displayItems eq "journal"}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.entireJournal"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayItems" id="displayItems-issue" value="issue" {if $displayItems eq "issue"}checked="checked" {/if}/></td>
		<td width="85%" class="value">{translate key="plugins.generic.voyeur.settings.currentIssue"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayItems" id="displayItems-recent" value="recent" {if $displayItems eq "recent"}checked="checked" {/if}/></td>
		<td width="85%" class="value">
		<input type="text" name="recentItems" id="recentItems" value="{$recentItems|escape}" size="2" maxlength="90" class="textField" />
		{translate key="plugins.generic.voyeur.settings.recentArticles"}</td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><input type="radio" name="displayItems" id="displayItems-page" value="page" {if $displayItems eq "page"}checked="checked" {/if}/></td>
		<td width="85%" class="value">
			{translate key="plugins.generic.voyeur.settings.currentPage"}
		</td>
	</tr>
	<tr valign="top">
		<td></td>
	</tr>
	<tr valign="top">
		<td width="15%" class="label" align="right"><strong>{translate key="plugins.generic.voyeur.settings.voyeurTime"}</strong></td>
		<td width="85%" class="value">
			<select id="voyeurTime" name="voyeurTime" title="{translate key='plugins.generic.voyeur.settings.voyeurTime'}">
				<option value="none" {if $voyeurTime eq "none"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_none"}</option>
				<option value="day" {if $voyeurTime eq "day"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_day"}</option>
				<option value="week" {if $voyeurTime eq "week"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_week"}</option>
				<option value="2weeks" {if $voyeurTime eq "2weeks"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_2weeks"}</option>
				<option value="month" {if $voyeurTime eq "month"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_month"}</option>
				<option value="6months" {if $voyeurTime eq "6months"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_6months"}</option>
				<option value="year" {if $voyeurTime eq "year"} selected="selected" {/if}>{translate key="plugins.generic.voyeur.settings.voyeurTime_year"}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"><div class="separator">&nbsp;</div></td>
	</tr>
	<tr valign="top">
		<td>
		</td>
	</tr>
</table>

<br/>

<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/> <input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

{include file="common/footer.tpl"}
