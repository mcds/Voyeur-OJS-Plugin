<?php

/**
 * @file SettingsForm.inc.php
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SettingsForm
 * @ingroup plugins_generic_voyeur
 *
 * @brief Class for the settings form of Voyeur plugin
 */

/**
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software, which
 * was originally created and maintained by John Willinsky. Thank you.
 */


import('form.Form');

class SettingsForm extends Form {

	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor.
	 * @param $plugin object
	 * @param $journalId int
	 */
	function SettingsForm(&$plugin, $journalId) {
		$this->journalId = $journalId;
		$this->plugin = &$plugin;

		parent::Form($plugin->getTemplatePath() . 'templates/settingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin = &$this->plugin;

		$this->setData('voyeurWidth', $plugin->getSetting($journalId, 'voyeurWidth'));
		$this->setData('voyeurHeight', $plugin->getSetting($journalId, 'voyeurHeight'));
		$this->setData('displayPage', $plugin->getSetting($journalId, 'displayPage'));
		$this->setData('displayItems', $plugin->getSetting($journalId, 'displayItems'));
		$this->setData('recentItems', $plugin->getSetting($journalId, 'recentItems'));
		$this->setData('voyeurTime', $plugin->getSetting($journalId, 'voyeurTime'));
		$this->setData('voyeurTool', $plugin->getSetting($journalId, 'voyeurTool'));
		$this->setData('allowAutoReveal', $plugin->getSetting($journalId, 'allowAutoReveal'));
		$this->setData('allowUser', $plugin->getSetting($journalId, 'allowUser'));
		$this->setData('forceSingleFile', $plugin->getSetting($journalId, 'forceSingleFile'));
    $this->setData('removeFuncWords', $plugin->getSetting($journalId, 'removeFuncWords'));
    $this->setData('voyeurLimit', $plugin->getSetting($journalId, 'voyeurLimit'));
    $this->setData('voyeurQuery', $plugin->getSetting($journalId, 'voyeurQuery'));
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('displayPage','displayItems','recentItems', 'voyeurWidth', 'voyeurHeight', 'voyeurTime', 'voyeurTool', 'allowAutoReveal', 'allowUser', 'forceSingleFile', 'removeFuncWords', 'voyeurLimit', 'voyeurQuery'));

		// Check that recent items value is a positive integer
		if ((int) $this->getData('recentItems') <= 0) $this->setData('recentItems', '');

		// If recent items is selected, check that we have a value
		if ($this->getData('displayItems') == "recent") {
			$this->addCheck(new FormValidator($this, 'recentItems', 'required', 'plugins.generic.voyeur.settings.recentItemsRequired'));
		}

		// Check that voyeur width value is a positive integer
		if ((int) $this->getData('voyeurWidth') <= 0) {
			$this->setData('voyeurWidth', '');
		}
		
		// Check that voyeur height value is a positive integer
		if ((int) $this->getData('voyeurHeight') <= 0) {
			$this->setData('voyeurHeight', '');
		}

		// Check that the Voyeur tool is Cirrus and that voyeur limit value is a positive integer.
		if ($this->getData('voyeurTool') != 'Cirrus' || (int) $this->getData('voyeurLimit') <= 0) {
			$this->setData('voyeurLimit', '');
		}
		
		if ($this->getData('voyeurQuery') && $this->getData('voyeurTool') != 'CorpusTypeFrequenciesGrid') {
			$this->setData('voyeurQuery', '');
		}
	}
	

	/**
	 * Save settings. 
	 */
	function execute() {
		$plugin = &$this->plugin;
		$journalId = $this->journalId;

		$plugin->updateSetting($journalId, 'displayPage', $this->getData('displayPage'));
		$plugin->updateSetting($journalId, 'displayItems', $this->getData('displayItems'));
		$plugin->updateSetting($journalId, 'recentItems', $this->getData('recentItems'));
		$plugin->updateSetting($journalId, 'voyeurWidth', $this->getData('voyeurWidth'));
		$plugin->updateSetting($journalId, 'voyeurHeight', $this->getData('voyeurHeight'));
		$plugin->updateSetting($journalId, 'voyeurTime', $this->getData('voyeurTime'));
		$plugin->updateSetting($journalId, 'voyeurTool', $this->getData('voyeurTool'));
		$plugin->updateSetting($journalId, 'allowAutoReveal', $this->getData('allowAutoReveal'));
		$plugin->updateSetting($journalId, 'allowUser', $this->getData('allowUser'));
    $plugin->updateSetting($journalId, 'forceSingleFile', $this->getData('forceSingleFile'));
    $plugin->updateSetting($journalId, 'removeFuncWords', $this->getData('removeFuncWords'));
    $plugin->updateSetting($journalId, 'voyeurLimit', $this->getData('voyeurLimit'));
    $plugin->updateSetting($journalId, 'voyeurQuery', $this->getData('voyeurQuery'));
	}

	/**
	 * Save default settings when user clicks 'enable' for the first time
	 */
	function setDefaultSettings() {
		$plugin = &$this->plugin;
		$journalId = $this->journalId;

		$plugin->updateSetting($journalId, 'displayPage', 'all');
		$plugin->updateSetting($journalId, 'displayItems', 'issue');
		$plugin->updateSetting($journalId, 'recentItems', '');
		$plugin->updateSetting($journalId, 'voyeurWidth', '100');
		$plugin->updateSetting($journalId, 'voyeurHeight', '250');
		$plugin->updateSetting($journalId, 'voyeurTime', 'month');
		$plugin->updateSetting($journalId, 'voyeurTool', 'Cirrus');
		$plugin->updateSetting($journalId, 'allowAutoReveal', '1');
		$plugin->updateSetting($journalId, 'allowUser', NULL);
		$plugin->updateSetting($journalId, 'forceSingleFile', '1');
    $plugin->updateSetting($journalId, 'removeFuncWords', '1');
    $plugin->updateSetting($journalId, 'voyeurLimit', '');
    $plugin->updateSetting($journalId, 'voyeurQuery', '');
		
		$plugin->updateSetting($journalId, 'enabledClicked', true, 'bool'); // make sure setDefaultSettings does not run again
	}

	/**
	 * Checks if user has clicked enabled before. This is because we want to keep user settings if they
	 * disable and then re-enable.
	 */
	function checkEnabledClicked() {
		$plugin = &$this->plugin;
		$journalId = $this->journalId;
		
		if ($plugin->getSetting($journalId, 'enabledClicked')) return true;
		
		return false;
	} // end checkEnabledClick()
} // end SettingsForm class

?>
