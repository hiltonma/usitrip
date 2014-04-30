     <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">

 <?php
 // BOF: Lango Added for template MOD
 if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
 $header_text = '&nbsp;'
 //EOF: Lango Added for template MOD
 ?>
       <tr>

         <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
           <tr>
                         <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
 		<td class="pageHeading" align="right">

 <?php echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>


 </td>
           </tr>
 	          </table></td>
       </tr>
 <?php
 // BOF: Lango Added for template MOD
 }else{
 $header_text = HEADING_TITLE;
 }
 // EOF: Lango Added for template MOD
?>
 </td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>


	<tr>
		<td>


<?php
// create column list
$define_list = array(
	'NEWSDESK_DATE_AVAILABLE' => NEWSDESK_DATE_AVAILABLE,
	'NEWSDESK_ARTICLE_DESCRIPTION' => NEWSDESK_ARTICLE_DESCRIPTION,
	'NEWSDESK_ARTICLE_SHORTTEXT' => NEWSDESK_ARTICLE_SHORTTEXT,
	'NEWSDESK_ARTICLE_NAME' => NEWSDESK_ARTICLE_NAME,
);

asort($define_list);

$column_list = array();
reset($define_list);
while (list($column, $value) = each($define_list)) {
	if ($value) $column_list[] = $column;
}

$select_column_list = '';

for ($col=0; $col<sizeof($column_list); $col++) {
	if ( ($column_list[$col] == 'NEWSDESK_DATE_AVAILABLE') || ($column_list[$col] == 'NEWSDESK_ARTICLE_NAME') ) {
		continue;
	}

	if ($select_column_list != '') {
		$select_column_list .= ', ';
	}

	switch ($column_list[$col]) {
	case 'NEWSDESK_DATE_AVAILABLE': $select_column_list .= 'p.newsdesk_date_added';
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION': $select_column_list .= 'pd.newsdesk_article_description';
		break;
	case 'NEWSDESK_ARTICLE_SHORTTEXT': $select_column_list .= 'pd.newsdesk_article_shorttext';
		break;
	case 'NEWSDESK_ARTICLE_NAME': $select_column_list .= 'pd.newsdesk_article_name';
		break;
	}
}

if ($select_column_list != '') {
	$select_column_list .= ', ';
}

$select_str = "select distinct " . $select_column_list . " p.newsdesk_id, p.newsdesk_date_added, pd.newsdesk_article_name,
pd.newsdesk_article_description, pd.newsdesk_article_shorttext ";

$from_str = "from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd, "
. TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_TO_CATEGORIES . " p2c";

$where_str = " where p.newsdesk_status = '1' and p.newsdesk_id = pd.newsdesk_id and pd.language_id = '" . $languages_id . "' and
p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = c.categories_id ";

if ($HTTP_GET_VARS['categories_id']) {
	if ($HTTP_GET_VARS['inc_subcat'] == "1") {
		$subcategories_array = array();
		newsdesk_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);
		$where_str .= " and p2c.newsdesk_id = p.newsdesk_id and p2c.newsdesk_id = pd.newsdesk_id and (p2c.categories_id = '"
		. $HTTP_GET_VARS['categories_id'] . "'";

		for ($i=0; $i<sizeof($subcategories_array); $i++ ) {
			$where_str .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
		}
		$where_str .= ")";
	} else {
		$where_str .= " and p2c.newsdesk_id = p.newsdesk_id and p2c.newsdesk_id = pd.newsdesk_id and pd.language_id = '"
		. $languages_id . "' and p2c.categories_id = '" . $HTTP_GET_VARS['categories_id'] . "'";
	}
}

if ($HTTP_GET_VARS['keywords']) {
	if (tep_parse_search_string( StripSlashes($HTTP_GET_VARS['keywords']), $search_keywords)) {
		$where_str .= " and (";
		for ($i=0; $i<sizeof($search_keywords); $i++ ) {
			switch ($search_keywords[$i]) {
				case '(':
				case ')':
				case 'and':
				case 'or':
				$where_str .= " " . $search_keywords[$i] . " ";
			break;
			default:
$where_str .= "
(pd.newsdesk_article_name like '%" . AddSlashes($search_keywords[$i]) . "%' or
pd.newsdesk_article_shorttext like '%" . AddSlashes($search_keywords[$i]) . "%' or
pd.newsdesk_article_description like '%" . AddSlashes($search_keywords[$i]) . "%'";
				if ($HTTP_GET_VARS['search_in_description']) $where_str .= "
				or pd.newsdesk_article_description like '%" . AddSlashes($search_keywords[$i]) . "%'";
				$where_str .= ')';
			break;
			}
		}
		$where_str .= " )";
	}
}

if ($HTTP_GET_VARS['dfrom'] && $HTTP_GET_VARS['dfrom'] != DOB_FORMAT_STRING) {
	$where_str .= " and p.newsdesk_date_added >= '" . tep_date_raw($dfrom_to_check) . "'";
}

if ($HTTP_GET_VARS['dto'] && $HTTP_GET_VARS['dto'] != DOB_FORMAT_STRING) {
	$where_str .= " and p.newsdesk_date_added <= '" . tep_date_raw($dto_to_check) . "'";
}


if ( (!$HTTP_GET_VARS['sort']) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'],0,1) > sizeof($column_list)) ) {
	for ($col=0; $col<sizeof($column_list); $col++) {
		if ($column_list[$col] == 'NEWSDESK_ARTICLE_NAME') {
			$HTTP_GET_VARS['sort'] = $col+1 . 'a';
			$order_str .= " order by pd.newsdesk_article_name";
			break;
		}
	}
} else {
	$sort_col = substr($HTTP_GET_VARS['sort'], 0, 1);
	$sort_order = substr($HTTP_GET_VARS['sort'], 1);
	$order_str .= ' order by ';
	switch ($column_list[$sort_col-1]) {
	case 'NEWSDESK_DATE_AVAILABLE': $order_str .= "p.newsdesk_date_added " . ($sort_order == 'd' ? "desc" : "") . ", pd.newsdesk_article_name";
		break;
	case 'NEWSDESK_ARTICLE_NAME': $order_str .= "pd.newsdesk_article_name " . ($sort_order == 'd' ? "desc" : "");
		break;
	case 'NEWSDESK_ARTICLE_SHORTTEXT': $order_str .= "pd.newsdesk_article_shorttext " . ($sort_order == 'd' ? "desc" : "") . ", pd.newsdesk_article_name";
		break;
	case 'NEWSDESK_ARTICLE_DESCRIPTION': $order_str .= "pd.newsdesk_article_description " . ($sort_order == 'd' ? "desc" : "") . ", pd.newsdesk_article_name";
		break;
	}
}

$listing_sql = $select_str . $from_str . $where_str . $order_str;

require(DIR_FS_MODULES . FILENAME_NEWSDESK_LISTING);
?>


		</td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
	<!--	<td class="main">
<?php
//FILENAME_NEWSDESK_SEARCH
echo '<a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, tep_get_all_get_params(array('sort', 'page', 'x', 'y')), 'NONSSL', true, false)
. '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
		</td>-->
<?php


// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="5"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

<?php echo '<td align="right" class="main"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=1&') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>' . tep_draw_separator('pixel_trans.gif', '10', '1') . '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></td>';
// EOF Wolfen added code for back button ?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
//}
?>
</table>
<!-- body_text_eof //-->
<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:		NewsDesk
	version:		1.48.2
	date:			17-07-2004 (dd/mm/yyyy)
	author:			Wolfen aka 241
	web site:		www..com

*/
?>
