<?php

/**
 * @file VoyeurGatewayPlugin.inc.php
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class VoyeurGatewayPlugin
 * @ingroup plugins_generic_voyeur
 *
 * @brief Class for gateway component of the Voyeur plugin.
 */

/**
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software, which
 * was originally created and maintained by John Willinsky. Thank you.
 */


import('classes.plugins.GatewayPlugin');

class VoyeurGatewayPlugin extends GatewayPlugin {
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return string
	 *		The name of the plugin.
	 */
	function getName() {
		return 'VoyeurGatewayPlugin';
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
	 * Get the Voyeur plugin.
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
	 * Get whether or not this plugin is enabled. (Should always return TRUE, as the
	 * parent plugin will take care of loading this one when needed.)
	 * @return boolean
	 */
	function getEnabled() {
		$plugin =& $this->getAnnouncementFeedPlugin();
		return $plugin->getEnabled(); // Should always be TRUE anyway if this is loaded
	}

	/**
	 * Get the management verbs for this plugin (override to none so that the parent
	 * plugin can handle this).
	 * @return array
	 */
	function getManagementVerbs() {
		return array();
	}

	/**
	 * Handle fetch requests for this plugin.
	 * In other words, fetch URLs to article files to send to Voyeur for processing
	 * (We fetch the URL to be sent to Voyeur dynamically so when we load a normal
	 * page, the server is not bogged down processing article information for us.)
	 * @return boolean
	 */
	function fetch($args) {
		// Make sure we're within a Journal context.
		$journal =& Request::getJournal();
		if (!$journal) return FALSE;

		// Make sure there's a current issue for this journal.
		$issueDao =& DAORegistry::getDAO('IssueDAO');
		$issue =& $issueDao->getCurrentIssue($journal->getJournalId());
		if (!$issue) return FALSE;

		// Check to see if the Voyeur plugin is enabled.
		$voyeurPlugin =& $this->getVoyeurPlugin();
		if (!$voyeurPlugin->getEnabled()) return FALSE;

		// Make sure the page is specified and valid.
		$type = array_shift($args);
		$typeMap = array(
			'voyeurAjax' => 'voyeurAjax.php.tpl'
		);
		if (!isset($typeMap[$type])) return FALSE;

		// ============================
		// ==   FETCH ARTICLE URLs   ==
		// == (to be read by Voyeur) ==
		// ============================
		
		/**
			The rest of this function simply fetchs the article URL data
			to pass a final URL to Voyeur. (The URL used in the
			iframe within block.tpl.) Essentially, the article URLs contain
			input params to be sent to Voyeur, each linking to an
			article within the installation of OJS.
			
			We begin by reading in admin/user params pertaining to filtering
			We set up necessary variables,
			and continue to find articles within the bounds of the set
			filters/settings. We retrieve published articles under the
			filters, and begin extracting data from these publishedArticle
			objects to construct the URLs to the PDFs, DOCS, etc. that
			pertain to these articles. Again, these links are placed in our
			final URL that is sent to Voyeur for processing.
		*/

		// Get settings from Voyeur plugin settings.
		$displayItems = $voyeurPlugin->getSetting($journal->getJournalId(), 'displayItems');
		$recentItems = (int) $voyeurPlugin->getSetting($journal->getJournalId(), 'recentItems');
		$allowUser = $voyeurPlugin->getSetting($journal->getJournalId(), 'allowUser');
    $cleanGet['autoReveal'] = (int) Request::getUserVar('autoReveal'); // Figure out if we run admin settings or user params.
		$forceSingleFile = $voyeurPlugin->getSetting($journal->getJournalId(), 'forceSingleFile');
		$publishedArticleDao =& DAORegistry::getDAO('PublishedArticleDAO');
		$publishedArticleFiles = array();

		if (isset($allowUser) && $cleanGet['autoReveal'] == 0) { // If user can set settings, use $_GET to find their options.
			$cleanGet['displayItems'] = (int) Request::getUserVar('displayItems'); // Secure our input by only using integers.
			$cleanGet['voyeurTime'] = Request::getUserVar('voyeurTime');
			$cleanGet['recentItems'] = (int) abs(Request::getUserVar('recentItems')); // Make sure input is positive integer.
			if (!empty($cleanGet['displayItems'])) { // Set up displayItems & recentItems from a custom user option via Ajax.
				switch ($cleanGet['displayItems']) {
					case 1:
						$displayItems = 'issue';
						break;
					case 2:
						$displayItems = 'recent';
						if (!empty($cleanGet['recentItems'])) {
							$recentItems = $cleanGet['recentItems'];
						} else {
							$recentItems = 1; // If not set for some reason, set to 1.
						}
						break;
					case 3:
						$displayItems = 'page';
						break;
					default: 
						$displayItems = 'journal'; // If none match for some reason (or displayItems is journal), just filter by journal.
						break;
				}
			} else {
				$displayItems = 'journal'; // If $_GET not set for some reason, just assign 'journal'.
			}
			if (!empty($cleanGet['voyeurTime'])) { // Set custom time filter for use later.
				// Clean up time input by only taking specified values. (Month is assigned as default.)
				switch ($cleanGet['voyeurTime']) {
					case 'none': case 'day': case 'week': case '2weeks': case 'month': case '6months': case 'year':
						break;
					default:
						$cleanGet['voyeurTime'] = 'month';
				}
				$voyeurTime = $cleanGet['voyeurTime'];
			}
			unset($cleanGet);
		}

		// Find our time filter so we can filter articles. (A UNIX timestamp to compare to dates published.)
		if (isset($voyeurTime)) { // Send user time filter if its set previously.
			$timeFilter = $this->filterByTime($voyeurPlugin, $journal, $voyeurTime);
		} else {
			$timeFilter = $this->filterByTime($voyeurPlugin, $journal);
		}

		// If we're revealing by page, get the $_GET params
		// Note: We need to get the params via $_GET as if we were to
		// find them from VoyeurGatewayPlugin we would get 'gateway' and
		// other bad values for any $current var.
		if ($displayItems == 'page') {
			$currentPage = Request::getUserVar('currentPage');;
			$currentOp = Request::getUserVar('currentOp');
			$issueNumber = (int) abs(Request::getUserVar('issueNumber')); // Make sure input is positive integer.
			if ($currentPage == 'issue') {
				if ($currentOp == 'view') { // If we're viewing an individual issue, get the viewed issue object.
					$issue =& $issueDao->getIssueById($issueNumber);
					if (!isset($issue)) { // If not set for some reason, just use first issue.
						$issue =& $issueDao->getIssueById(1);
					}
				}
			}
		}
		
		// If admin has chosen to display entire journal, recent items OR current items on page.
		if ($displayItems == 'journal' || ($displayItems == 'recent' && $recentItems > 0) || ($displayItems == 'page' && ($currentPage != 'issue' || $currentOp == 'archive'))) {
			// Get all the published journal objects.
			$publishedArticleObjects =& $publishedArticleDao->getPublishedArticlesByJournalId($journal->getJournalId());

			// Create array from publishedArticleObjects for later sort by date published
			// ONLY if within bounds of time filter.
			while ($publishedArticle =& $publishedArticleObjects->next()) {
				// Only add the article object to $publishedArticleSort if it falls in bounds of time filter.
				if (strtotime($publishedArticle->_data['datePublished']) >= $timeFilter) {
					$publishedArticleSort[]['articleId'] = &$publishedArticle->_data['articleId'];
					$publishedArticleSort[array_pop(array_keys($publishedArticleSort))]['datePublished'] = &$publishedArticle->_data['datePublished'];
					unset($publishedArticle);
				}
			}
			
			if (!isset($publishedArticleSort)) return FALSE;

			// If recent items set, set our publishedArticles to only that amount.
			if ($displayItems == 'recent' && $recentItems > 0) {
				arsort($publishedArticleSort); // Sort it by reverse while mainting index association (this is to order by most recent).
				$publishedArticleSort = array_slice($publishedArticleSort, 0, $recentItems); // Trim off entries not within bounds of $recentItems.
			}
			
			$articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');

			for ($i = 0; $i < count($publishedArticleSort); $i++) { // Get all article files from our filtered article ids.
				$publishedArticleFiles[] = &$articleFileDao->getArticleFilesByArticle($publishedArticleSort[$i]['articleId']);
			}
			
		} else { // If admin has chosen to display all items from current issue.
			$publishedArticleObjects = &$publishedArticleDao->getPublishedArticlesInSections($issue->getIssueId());
			$articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');

			// Get all article files from our filtered article ids (NOTE: $i begins at 1 due to
			// method of array construction within getPublishedArticlesInSections)
			for ($i = 1; $i <= count($publishedArticleObjects); $i++) {
				for ($m = 0; $m < count($publishedArticleObjects[$i]['articles']); $m++) {
					// Check if the article's 'date published' is within bounds of our time filter.
					if (strtotime($publishedArticleObjects[$i]['articles'][$m]->_data['datePublished']) >= $timeFilter) {
						$publishedArticleFiles[] = &$articleFileDao->getArticleFilesByArticle($publishedArticleObjects[$i]['articles'][$m]->_data['articleId']);
					}
				}
			}
		}
		
		$galleyDao =& DAORegistry::getDAO('ArticleGalleyDAO');
		
		if (!$publishedArticleFiles) return FALSE;
		
		// Create the URLs to be passed to Voyeur (ex. 'input=http://localhost:8888/ojs/index.php/journal1/article/viewFile/3/6&')
		for ($i = 0; $i < count($publishedArticleFiles); $i++) {
			$lastArticleFileId = '';
			for ($m = 0; $m < count($publishedArticleFiles[$i]); $m++) {
				// Only find article files that are NOT duplicates if admin has chosen to do so.
				// (ie An article that has both DOC and PDF will only reveal the DOC.)
				if (!isset($forceSingleFile) || $publishedArticleFiles[$i][$m]->_data['articleId'] != $lastArticleFileId) {
					if ($publishedArticleFiles[$i][$m]->_data['type'] == 'public') { // Only observe public article files.
						$currentGalley = &$galleyDao->getGalleysByArticle($publishedArticleFiles[$i][$m]->_data['articleId']); // Need to get galley to complete URL.
						foreach ($currentGalley as $id) { // Find which galley we need by article file id.
							if ($id->_data['fileId'] == $publishedArticleFiles[$i][$m]->_data['fileId'])
								$correctGalley = $id->_data['galleyId'];
						}
						
						// Create the full URL to the article's file by using url().
						$articleFileUrl = Request::url($journal->getJournalInitials(), 'article', 'viewFile', array($publishedArticleFiles[$i][$m]->_data['articleId'], $correctGalley));
						
						// If there's an ugly URL, we need to encode all of the params.
						if (!Request::isPathInfoEnabled()) {
							$articleFileUrlChunks = explode('?', $articleFileUrl, 2);
							// Reconstruct the proper URL.
							$articleFileUrl = $articleFileUrlChunks[0] . '%3F' . rawurlencode(rawurldecode($articleFileUrlChunks[1]));
							unset($articleFileUrlChunks);
						}

						$fullArticleUrls[] = $articleFileUrl;
						$lastArticleFileId = $publishedArticleFiles[$i][$m]->_data['articleId']; // Create reference to last article to check if repeat exists.
            $allArticleIds[] = $publishedArticleFiles[$i][$m]->_data['articleId'];
            
            $unixTimestamps[] = strtotime($publishedArticleFiles[$i][$m]->_data['dateModified']);
					}
				}
			}
		}
		unset($lastArticleFileId);

		$toVoyeurUrl = '';
    if (isset($unixTimestamps) && isset($allArticleIds)) {
      // Add the unique corpus timestamp to the front of the params AND article IDs (using '_' to space them. For added uniqueness.)
    	// (This is the latest timestamp from the articles.)
			$toVoyeurUrl = max($unixTimestamps) . '_' . implode('', $allArticleIds) . '&'; 
		}
		
		// Only construct the URL params if there are any articles to construct.
		if (isset($fullArticleUrls)) {
			for ($i = 0; $i < count($fullArticleUrls); $i++) {
				$toVoyeurUrl .= 'input=' . $fullArticleUrls[$i];
				if ($i != (count($fullArticleUrls) - 1)) { // If we're at last file, do not add '&'
					$toVoyeurUrl .= '&';
				}
			}
		} else {
			$toVoyeurUrl = '';
		}

		// Finally, assign the final URL to our template.
		$templateManager = &TemplateManager::getManager();
		$templateManager->assign('toVoyeurUrl', $toVoyeurUrl);
		$templateManager->display($this->getTemplatePath() . $typeMap[$type]);

		return TRUE;
	} // end fetch()

	/**
	 * Determines a time filter from user / admin defined settings.
	 *
	 * @param $voyeurPlugin object
	 *		Instance of our VoyeurPlugin class (passed from fetch()).
	 * @param $journal object
	 *		The current journal we're dealing with (passed from fetch()).
	 * @param $voyeurTime string
	 *		The filter defined in user options.
	 *		(Will not be defined if users cannot choose filters.)
	 *
	 * @return int
	 *		The appropriate UNIX timestamp time filter.
	 */
	function filterByTime($voyeurPlugin, $journal, $voyeurTime = '') {
		// $publishedArticle->_data['datePublished']
		if ($voyeurTime == '') { // If time filter not set by custom user settings.
			$voyeurTime = $voyeurPlugin->getSetting($journal->getJournalId(), 'voyeurTime');
		}
		switch ($voyeurTime) {
			case 'none':
				$timeFilter = 0;
				break;
			case 'day':
				$timeFilter = time() - 86400;
				break;
			case 'week':
				$timeFilter = time() - 604800;
				break;
			case '2weeks':
				$timeFilter = time() - 1209600;
				break;
			case 'month':
				$timeFilter = time() - 2629744;
				break;
			case '6months':
				$timeFilter = time() - 15778463;
				break;
			default: // This is for year, or if silly input.
				$timeFilter = time() - 31556926;
		}

		return $timeFilter;
	} // end filterByTime()
}
?>
