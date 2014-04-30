<!-- newsdesk //-->

	<tr>
		<td>

<?php
	$heading = array();
	$contents = array();

	$heading[] = array(
		'text'  => BOX_HEADING_NEWSDESK,
		'link'  => tep_href_link(FILENAME_NEWSDESK, 'selected_box=newsdesk') );

if ($selected_box == 'newsdesk' || $menu_dhtml == true) {
	$contents[] = array('text'  =>
                                    tep_admin_files_boxes(FILENAME_NEWSDESK, BOX_NEWSDESK) .
                                    tep_admin_files_boxes(FILENAME_NEWSDESK_REVIEWS, BOX_NEWSDESK_REVIEWS) .
                                    tep_admin_files_boxes_newsdesk('', '')
                                    );
//Admin end
}
	$box = new box;
	echo $box->menuBox($heading, $contents);
?>

		</td>
	</tr>

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