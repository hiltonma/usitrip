<!-- newsdesk //-->
<?php


// set application wide parameters
// this query set is for NewsDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}


$newsdesk_var_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, 
pd.newsdesk_article_url, pd.newsdesk_article_url_name, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed, 
p.newsdesk_date_available, p.newsdesk_status, p.newsdesk_sticky from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' 
pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1 
and p.newsdesk_sticky = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . MAX_DISPLAY_NEWSDESK_NEWS
);


if (!tep_db_num_rows($newsdesk_var_query)) { // there is no news
	echo '<!-- ' . TEXT_NO_NEWSDESK_NEWS . ' -->';

} else {

	$row = 0;
	while ($newsdesk_var = tep_db_fetch_array($newsdesk_var_query)) {


if ( STICKY_IMAGE ) {
if ($newsdesk_var['newsdesk_image'] != '') {
$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$newsdesk_var['newsdesk_image'], '', '') . '</a>
		</td>
	</tr>
</table>
';
 }
 }
if ( STICKY_IMAGE_TWO ) {
if ($newsdesk_var['newsdesk_image_two'] != '') {
$insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$newsdesk_var['newsdesk_image_two'], '', '') . '</a>
		</td>
	</tr>
</table>
';
 }
 }
if ( STICKY_IMAGE_THREE ) {
if ($newsdesk_var['newsdesk_image_three'] != '') {
$insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 
$newsdesk_var['newsdesk_image_three'], '', '') . '</a>
		</td>
	</tr>
</table>
';
 }
 }

if ( STICKY_NEWSDESK_VIEWCOUNT ) {
$insert_viewcount = '<i>' . TEXT_NEWSDESK_VIEWED . $newsdesk_var['newsdesk_article_viewed'] . '</i>';
}

if ( STICKY_NEWSDESK_READMORE ) {
$insert_readmore = '
<tr>
	<td class="smallText">
<a class="smallText" href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_var['newsdesk_id']) . '">[' . TEXT_NEWSDESK_READMORE . 
']</a>
	</td>
</tr>
';
}

if ( STICKY_ARTICLE_SHORTTEXT ) {
$insert_summary = '
<tr>
	<td class="smallText">'. $newsdesk_var['newsdesk_article_shorttext'] . '</td>
</tr>
<tr>
	<td>' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
</tr>
';
}

if ( STICKY_ARTICLE_DESCRIPTION ) {
$insert_content = '
<tr>
	<td class="smallText">'. $newsdesk_var['newsdesk_article_description'] . '</td>
</tr>
';
}

if ( STICKY_ARTICLE_NAME ) {
$insert_headline = '<b>' . $newsdesk_var['newsdesk_article_name'] . '</b>';
}

if ( STICKY_DATE_ADDED ) {
$insert_date = '- <i>' . tep_date_long($newsdesk_var['newsdesk_date_added']) . '</i>';
}

if ( STICKY_ARTICLE_URL ) {
$insert_url = '
<tr>
	<td class="smallText"><i>' . $newsdesk_var['newsdesk_article_url'] . '</i></td>
</tr>
';
}

if ( STICKY_ARTICLE_URL_NAME ) {
$insert_url = '
<tr>
	<td class="smallText"><i>' . $newsdesk_var['newsdesk_article_url_name'] . '</i></td>
</tr>
';
}

echo '
<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="smallText">' . $insert_headline . $insert_date . '</td>
		<td class="smallText" align="right">' . $insert_viewcount . '</td>
	</tr>
	<tr>
		<td class="headerNavigation" colspan="2">' . tep_draw_separator('pixel_trans.gif', '100%', '1') . '</td>
	</tr>
	<tr>
		<td colspan="2">' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td valign="top" width="100%" colspan="2">


<table border="0" width="100%" cellspacing="3" cellpadding="0">
' . $insert_summary . '
' . $insert_content . '
' . $insert_url . '
	<tr>
		<td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
	</tr>
' . $insert_readmore . '
</table>


		</td>
		<td valign="top">
' . $insert_image . '
' . $insert_image_two . '
' . $insert_image_three . '
		</td>
	</tr>
	<tr>
		<td colspan="2">' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
	</tr>
</table>
';

$row++;

// WE need a better solution for my fudge code below ...
$insert_image = '';
$insert_image_two ='';
$insert_image_three = '';

	}
}

?>
<!-- newsdesk_eof //-->
<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.
	
	script name:			NewsDesk
	version:        		1.48.2
	date:       			22-06-2004 (dd/mm/yyyy)
	original author:		Carsten aka moyashi
	web site:       		www..com
	modified code by:		Wolfen aka 241
*/
?>