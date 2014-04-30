<!-- faqdesk //-->

	<tr>
		<td>

<?php
	$heading = array();
	$contents = array();

$heading[] = array(
	'text'  => BOX_HEADING_FAQDESK,
	'link'  => tep_href_link(FILENAME_FAQDESK, 'selected_box=faqdesk')
);

if ($selected_box == 'faqdesk' || $menu_dhtml == true) {
	$contents[] = array('text'  =>
//Admin begin
tep_admin_files_boxes(FILENAME_FAQDESK, BOX_FAQDESK) .
tep_admin_files_boxes(FILENAME_FAQDESK_REVIEWS, BOX_FAQDESK_REVIEWS) .
tep_admin_files_boxes_faqdesk('', '')
	);
//Admin end


}

	$box = new box;
	echo $box->menuBox($heading, $contents);
?>

		</td>
	</tr>

<!-- faqdesk_eof //-->


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
