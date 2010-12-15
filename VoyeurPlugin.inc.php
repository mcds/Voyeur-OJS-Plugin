<?php

/**
 * @file VoyeurPlugin.inc.php
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class VoyeurPlugin
 * @ingroup plugins_generic_voyeur
 *
 * @brief Main class of the Voyeur plugin.
 */

/**
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software. This work
 * was originally created and maintained by John Willinsky. Thank you.
 */


import('classes.plugins.GenericPlugin');

class VoyeurPlugin extends GenericPlugin {
	/**
	 * Get the symbolic name of this plugin.
	 * @return string
	 */
	function getName() {
		return 'VoyeurPlugin';
	}

	/**
	 * Get the display name of this plugin.
	 * @return string
	 */
	function getDisplayName() {
		return Locale::translate('plugins.generic.voyeur.displayName');
	}

	/**
	 * Get the description of this plugin.
	 * @return string
	 */
	function getDescription() {
		return Locale::translate('plugins.generic.voyeur.description');
	}   

	/**
	 * Register callbacks for certain hooks.
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register('TemplateManager::display',array(&$this, 'callbackAddLinks'));
				HookRegistry::register('PluginRegistry::loadCategory', array(&$this, 'callbackLoadCategory'));
			}
			$this->addLocaleData();
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Determine whether or not this plugin is enabled.
	 * @return boolean
	 */
	function getEnabled() {
		$journal = &Request::getJournal();
		if (!$journal) return FALSE;
		return $this->getSetting($journal->getJournalId(), 'enabled');
	}
	
	/**
	 * Set the enabled/disabled state of this plugin.
	 * @return boolean
	 */
	function setEnabled($enabled) {
		$journal = &Request::getJournal();
		if ($journal) {
			$this->updateSetting($journal->getJournalId(), 'enabled', $enabled ? TRUE : FALSE);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Register as a block plugin, even though this is a generic plugin.
	 * This will allow the plugin to behave as a block plugin, i.e. to
	 * have layout tasks performed on it.
	 * @param $hookName string
	 * @param $args array
	 */
	function callbackLoadCategory($hookName, $args) {
		$category =& $args[0];
		$plugins =& $args[1];
		switch ($category) {
			case 'blocks':
				$this->import('VoyeurBlockPlugin');
				$blockPlugin =& new VoyeurBlockPlugin();
				$plugins[$blockPlugin->getSeq()][$blockPlugin->getPluginPath()] =& $blockPlugin;
				break;
			case 'gateways':
				$this->import('VoyeurGatewayPlugin');
				$gatewayPlugin =& new VoyeurGatewayPlugin();
				$plugins[$gatewayPlugin->getSeq()][$gatewayPlugin->getPluginPath()] =& $gatewayPlugin;
				break;
		}
		return FALSE;
	}

	/**
	 * Add feed links to page <head> on select pages.
	 * @return boolean
	 */
	function callbackAddLinks($hookName, $args) {
		if ($this->getEnabled()) {
			$templateManager =& $args[0];
			$currentJournal =& $templateManager->get_template_vars('currentJournal');
			$requestedPage = Request::getRequestedPage();
			$displayItems = $this->getSetting($currentJournal->getJournalId(), 'displayItems');

			if ($currentJournal) {
				$issueDao = &DAORegistry::getDAO('IssueDAO');
				$currentIssue =& $issueDao->getCurrentIssue($currentJournal->getJournalId());
				$displayPage = $this->getSetting($currentJournal->getJournalId(), 'displayPage');
			}

			if ( ($currentIssue) && (($displayPage == 'all') || ($displayPage == 'homepage' && (empty($requestedPage) || $requestedPage == 'index' || $requestedPage == 'issue')) || ($displayPage == 'issue' && $displayPage == $requestedPage)) ) {
				// Assign vars for use in voyeurHead.php.tpl.
        $templateManager->assign('pageURLStrip', preg_replace('/[\W]/', '', $templateManager->get_template_vars('baseUrl')));
				$templateManager->assign('pluginURL', $templateManager->get_template_vars('baseUrl').'/plugins/generic/voyeur');
				$templateManager->assign('completeURL', Request::getCompleteUrl());
				$templateManager->assign('voyeurTool', $this->getSetting($currentJournal->getJournalId(), 'voyeurTool'));
				$templateManager->assign('voyeurWidth', $this->getSetting($currentJournal->getJournalId(), 'voyeurWidth'));
				$templateManager->assign('voyeurHeight', $this->getSetting($currentJournal->getJournalId(), 'voyeurHeight'));
				$templateManager->assign('displayItems', $this->getSetting($currentJournal->getJournalId(), 'displayItems'));
				$templateManager->assign('allowAutoReveal', $this->getSetting($currentJournal->getJournalId(), 'allowAutoReveal'));
				$templateManager->assign('allowUser', $this->getSetting($currentJournal->getJournalId(), 'allowUser'));
        $templateManager->assign('removeFuncWords', $this->getSetting($currentJournal->getJournalId(), 'removeFuncWords'));
        $templateManager->assign('voyeurLimit', $this->getSetting($currentJournal->getJournalId(), 'voyeurLimit'));
        $templateManager->assign('voyeurQuery', $this->getSetting($currentJournal->getJournalId(), 'voyeurQuery'));
				// Determine what kind of URL we're dealing with.
				if (!Request::isPathInfoEnabled()) {
					$templateManager->assign('uglyUrl', 1);
				} else {
					$templateManager->assign('uglyUrl', 0);
				}

				// These vars eventually passed to VoyeurGatewayPlugin to find current page.
					$currentPage = Request::getRequestedPage();
					$currentOp = Request::getRequestedOp();
					$templateManager->assign('currentPage', $currentPage);
					$templateManager->assign('currentOp', $currentOp);
					if ($currentPage == 'issue') {
						if ($currentOp == 'view') {
							$issueNumber = Request::getRequestedArgs();
							$templateManager->assign('issueNumber', $issueNumber[0]);
						}
					}

				$additionalHeadData = $templateManager->get_template_vars('additionalHeadData');
				$voyeurHead = $templateManager->fetch('../plugins/generic/voyeur/templates/voyeurHead.php.tpl');
				$templateManager->assign('additionalHeadData', $additionalHeadData."\n\t".$voyeurHead."\n\t");
			}
		}

		return FALSE;
	} // end callbackAddLinks()

	/**
	 * Display verbs for the management interface.
	 * @return array
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array(
				'disable',
				Locale::translate('manager.plugins.disable')
			);
			$verbs[] = array(
				'settings',
				Locale::translate('plugins.generic.voyeur.settings')
			);
		} else {
			$verbs[] = array(
				'enable',
				Locale::translate('manager.plugins.enable')
			);
		}
		return $verbs;
	}

	/**
	 * Perform management functions.
	 * @return boolean
	 */
	function manage($verb, $args) {
		$returner = TRUE;
		$journal =& Request::getJournal();

		switch ($verb) {
			case 'settings':
				$templateManager = &TemplateManager::getManager();
				$templateManager->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));

				$this->import('SettingsForm');
				$form =& new SettingsForm($this, $journal->getJournalId());

				if (Request::getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						Request::redirect(NULL, NULL, 'plugins');
					} else {
						$form->display();
					}
				} else {
					$form->initData();
					$form->display();
				}
				break;
			case 'enable':
				$this->setEnabled(TRUE);
				$this->import('SettingsForm');
				$form =& new SettingsForm($this, $journal->getJournalId());
				if ($form->checkEnabledClicked() == FALSE) { // We only want to initialize values ONCE, so only set them once
					$form->setDefaultSettings();
				}
				$returner = FALSE;
				break;
			case 'disable':
				$this->setEnabled(FALSE);
				$returner = FALSE;
				break;
		}

		return $returner;		
	} // end manage()
} // end VoyeurPlugin class
?>
