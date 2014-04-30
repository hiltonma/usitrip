<?php

if ( ($category_depth == 'products') ) {

define('HEADING_TITLE', 'News Articles');

define('TABLE_HEADING_IMAGE', 'Image');
define('TABLE_HEADING_ARTICLE_NAME', 'Headline');
define('TABLE_HEADING_ARTICLE_SHORTTEXT', 'Summary');
define('TABLE_HEADING_ARTICLE_DESCRIPTION', 'Content');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_DATE_AVAILABLE', 'Date');
define('TABLE_HEADING_ARTRICLE_URL', 'URL to outside resource');
define('TABLE_HEADING_ARTRICLE_URL_NAME', 'URL Name');

define('TEXT_NO_ARTICLES', 'There are no news articles in this category.');

define('TEXT_NUMBER_OF_ARTICLES', 'Number of Articles: ');
define('TEXT_SHOW', '<b>Show:</b>');

} elseif ($category_depth == 'top') {

define('HEADING_TITLE', 'What\'s New Here?');

} elseif ($category_depth == 'nested') {

define('HEADING_TITLE', 'News Article Categories');

}

/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:    	    	NewsDesk
	version:        		1.48.2
	date:       			06-05-2004 (dd/mm/yyyy)
        original author:		Carsten aka moyashi
        web site:			www..com
	modified code by:		Wolfen aka 241
*/
?>