================================
=== OJS Voyeur Plugin
=== Version: $Id: $
=== Authors: Corey Slavnik <corey@coreyslavnik.com> and Stéfan Sinclair <sgs@mcmaster.ca>
================================

About
-----
This plugin for OJS 2 provides the functionality of Voyeur Tools for analysis of any OJS website's content.
Voyeur is a web-based text analysis environment. It is designed to be user-friendly, flexible and powerful.
Voyeur is part of the Hermeneuti.ca, a collaborative project to develop and theorize text analysis tools and text analysis rhetoric.

License
-------
This plugin is licensed under the GNU General Public License v2. See the file COPYING for the complete terms of this license.

System Requirements
-------------------
Same requirements as the OJS 2.2 core.

Installation
------------
To install the plugin:
 - Place the 'voyeur' folder in your OJS plugins folder. (/plugins/generic/)
 - Enable the plugin by going to:  Home > User > Journal Management > Plugin Management  and selecting "ENABLE" under "Voyeur Plugin"

Configuration
------------
The plugin can be configured to display a Voyeur analysis of OJS in the sidebar.
	- General settings:
		- Tool: Voyeur Tools offers many analysis tools. The default is 'Cirrus', which offers a word cloud type visualization.
		- Width & Height: Adjusts the dimensions of the Voyeur block in the sidebar.
		- "Voyeur launches automatically on page load": When a page is loaded, Voyeur will automatically perform an analysis and display it.
		-	"Allow users to choose Voyeur settings": When users click 'Reveal', they will be prompted with filtering options to perform their own analysis.
		- "Reveal only one file from each article": OJS offers the feature of uploading different file formats for a single article. This will only analyze one file per article.
	- Appearance settings:
		- Selects where the Voyeur block should be displayed within OJS.
	- Filter settings:
		- "Reveal items in entire journal": Performs an analysis on every item within the current journal.
		- "Reveal items in current published issue": Performs an analysis on every item within the current issue.
		- "__ most recent published items": Performs an analysis on a defined set of recently published items.
		- "Reveal items associated with current viewed page.": Performs an analysis on items associated with the current page viewed by a user.
		- "Filter by time": Filters by a certain time period.

Known Issues
------------
	- "Communication failure" will occur if Voyeur is interrupted whilst loading content.


Contact/Support
---------------
Please email the authors for support, bugfixes, or comments.
