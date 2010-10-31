<?php

/**
 * @file index.php
 *
 * Copyright (c) 2010 Corey Slavnik and Stéfan Sinclair
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_voyeur
 *
 * @brief References the main Voyeur class.
 */

/**
 * On June 2nd, 2010, the Voyeur plugin for Open Journal Systems was created.
 * This program is distributed under GNU GPL v2, but is based off of the
 * 'WebFeedPlugin' distributed with Open Journal Systems software. This work
 * was
 */

require_once('VoyeurPlugin.inc.php');

return new VoyeurPlugin(); 

?> 
