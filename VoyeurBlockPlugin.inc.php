<?php

/**
 * @file VoyeurBlockPlugin.inc.php
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class VoyeurBlockPlugin
 * @ingroup plugins_generic_voyeur
 *
 * @brief Class for block component of the Voyeur plugin.
 */

/**
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software, which
 * was originally created and maintained by John Willinsky. Thank you.
 */


import('plugins.BlockPlugin');

class VoyeurBlockPlugin extends BlockPlugin {
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return string 
	 * 		The name of the plugin.
	 */
	function getName() {
		return 'VoyeurBlockPlugin';
	}

	/**
	 * Get the display name of this plugin.
	 * @return string
	 */
	function getDisplayName() {
		return Locale::translate('plugins.generic.voyeur.displayName');
	}

	/**
	 * Get a description of the plugin.
	 * @return string
	 */
	function getDescription() {
		return Locale::translate('plugins.generic.voyeur.description');
	}

	/**
	 * Get the supported contexts (e.g. BLOCK_CONTEXT_...) for this block.
	 * @return array
	 */
	function getSupportedContexts() {
		return array(BLOCK_CONTEXT_LEFT_SIDEBAR, BLOCK_CONTEXT_RIGHT_SIDEBAR);
	}

	/**
	 * Get the Voyeur plugin
	 * @return object
	 */
	function &getVoyeurPlugin() {
		$plugin =& PluginRegistry::getPlugin('generic', 'VoyeurPlugin');
		return $plugin;
	}

	/**
	 * Override the builtin to get the correct plugin path.
	 * @return string
	 */
	function getPluginPath() {
		$plugin =& $this->getVoyeurPlugin();
		return $plugin->getPluginPath();
	}

	/**
	 * Override the builtin to get the correct template path.
	 * @return string
	 */
	function getTemplatePath() {
		$plugin =& $this->getVoyeurPlugin();
		return $plugin->getTemplatePath() . 'templates/';
	}

	/**
	 * Get the HTML contents for this block.
	 * @param $templateMgr object
	 * @return $string
	 */
	function getContents(&$templateManager) {
		$journal =& Request::getJournal();
		if (!$journal) return '';

		$plugin =& $this->getVoyeurPlugin();
		$displayPage = $plugin->getSetting($journal->getJournalId(), 'displayPage');
		$requestedPage = Request::getRequestedPage();
		$issueDao = &DAORegistry::getDAO('IssueDAO');
		$currentIssue =& $issueDao->getCurrentIssue($journal->getJournalId());
		
		// Define vars to be used in block.tpl.
		$templateManager->assign('voyeurWidth', $plugin->getSetting($journal->getJournalId(), 'voyeurWidth'));
		$templateManager->assign('voyeurHeight', $plugin->getSetting($journal->getJournalId(), 'voyeurHeight'));

		if (!isset($displayPage)) // If admin has not set where to display Voyeur, make a default of showing it everywhere.
			$displayPage = 'all';

		if (($currentIssue) && (($displayPage == 'all') || ($displayPage == 'homepage' && (empty($requestedPage) || $requestedPage == 'index' || $requestedPage == 'issue')) || ($displayPage == 'issue' && $displayPage == $requestedPage)) ) { 
			return parent::getContents($templateManager);
		} else {
			return '';
		}
	} // end getContents()
} // end VoyeurBlock class

?>
