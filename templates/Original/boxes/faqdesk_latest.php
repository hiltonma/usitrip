<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	FaqDesk
	version:		1.2.5
	date:			2003-09-01
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>

<?php

// set application wide parameters
// this query set is for FAQDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}
if (DISPLAY_LATEST_FAQS_BOX >= 1) {
?>
<!-- faqdesk latest//-->
	<tr>
		<td>
<?php

// set application wide parameters
// this query set is for FAQDesk

$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_FAQDESK_CONFIGURATION . "");
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}


$latest_faq_var_query = tep_db_query(
'select p.faqdesk_id, pd.language_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, pd.faqdesk_extra_url,
p.faqdesk_image, p.faqdesk_date_added, p.faqdesk_last_modified,
p.faqdesk_date_available, p.faqdesk_status  from ' . TABLE_FAQDESK . ' p, ' . TABLE_FAQDESK_DESCRIPTION . '
pd WHERE pd.faqdesk_id = p.faqdesk_id and pd.language_id = "' . $languages_id . '" and faqdesk_status = 1 ORDER BY faqdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_FAQDESK_FAQS);



if (!tep_db_num_rows($latest_faq_var_query)) { // there is no news
	echo '<!-- ' . TEXT_NO_FAQDESK_NEWS . ' -->';

} else {

$info_box_contents = array();
$info_box_contents[] = array(
		'align' => 'left',
		'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FAQDESK_LATEST . '</font>'
	);

new infoBoxHeading($info_box_contents, false, false);

$latest_faq_string = '';

$row = 0;
while ($latest_faq = tep_db_fetch_array($latest_faq_var_query))  {
$latest_faq['faqdesk'] = array(
		'name' => $latest_faq['faqdesk_question'],
		'id' => $latest_faq['faqdesk_id'],
		'date' => $latest_faq['faqdesk_date_added'],
	);

$latest_faq_string .= '<a class="smallText" href="';
$latest_faq_string .= tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $latest_faq['faqdesk_id']);
$latest_faq_string .= '">';
$latest_faq_string .= $latest_faq['faqdesk_question'];
$latest_faq_string .= '</a>';
$latest_faq_string .= '<br>';

$info_box_contents = array();
$info_box_contents[] = array(
		'align' => 'left',
		'params' => 'class="smallText" valign="top"',
		'text'  => $latest_faq_string);

	$row++;
}

new infoBox($info_box_contents);
}
?>

<!-- faqdesk latest eof //-->
		</td>
	</tr>

<?php
}
?>