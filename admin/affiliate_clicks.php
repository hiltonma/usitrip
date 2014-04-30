<?php
/*
  $Id: affiliate_clicks.php,v 1.1.1.1 2004/03/04 23:38:07 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('affiliate_clicks');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  //amit added to fileter 
  if(isset($HTTP_GET_VARS['aff_filter']) && $HTTP_GET_VARS['aff_filter'] != '' && $HTTP_GET_VARS['aff_filter'] != '0'){
  $extrawithwhere = "where a.affiliate_homepage != '' ";
  
  }

//amit added to delete click recored start  
  if(isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'delete'){
  
  $select_click_query = "select * from ". TABLE_AFFILIATE_CLICKTHROUGHS ." ";
  $select_click_row = tep_db_query($select_click_query);
  while ($select_click = tep_db_fetch_array($select_click_row)) {
    $affiliate_clickthrough_id = $select_click['affiliate_clickthrough_id']; 	
    $datefrom = $select_click['affiliate_clientdate'];	
	$dateto = date('Y-m-d H:i:s');
	$daydiffrence =  scs_datediff('d', $datefrom, $dateto, false);	 
		if($daydiffrence >= MAX_DISPLAY_AFFILIATE_DELETE_CLCIK){
		//delete all recored start
		$delete_query =  "delete from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_clickthrough_id = '" . (int)$affiliate_clickthrough_id . "'";
		tep_db_query($delete_query);       
		}
   }
  
  }
  
  
//amit added to delete click recored end
  if ($HTTP_GET_VARS['acID'] > 0) {
    $affiliate_clickthroughs_raw = "select ac.*, pd.products_name, a.affiliate_firstname, a.affiliate_lastname from " . TABLE_AFFILIATE_CLICKTHROUGHS . " ac left join " . TABLE_PRODUCTS . " p on (p.products_id = ac.affiliate_products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "') left join " . TABLE_AFFILIATE . " a  on (a.affiliate_id = ac.affiliate_id) where a.affiliate_id = '" . $HTTP_GET_VARS['acID'] . "' ORDER BY ac.affiliate_clientdate desc";
//	"select * from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id ='" . $HTTP_GET_VARS['acID'] . "' order by date desc";
    $affiliate_clickthroughs_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $affiliate_clickthroughs_raw, $affiliate_clickthroughs_numrows);
  } else {
    $affiliate_clickthroughs_raw = "select ac.*, pd.products_name, a.affiliate_firstname, a.affiliate_lastname from " . TABLE_AFFILIATE_CLICKTHROUGHS . " ac left join " . TABLE_PRODUCTS . " p on (p.products_id = ac.affiliate_products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "') left join " . TABLE_AFFILIATE . " a  on (a.affiliate_id = ac.affiliate_id) ".$extrawithwhere." ORDER BY ac.affiliate_clientdate desc";
    $affiliate_clickthroughs_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $affiliate_clickthroughs_raw, $affiliate_clickthroughs_numrows);
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('affiliate_clicks');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">联盟点击浏览统计</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
<?php 
  if ($HTTP_GET_VARS['acID'] > 0) {
?>
            <td class="pageHeading" align="right">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_STATISTICS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  } else {
?>
            <td class="pageHeading" align="right">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  }
?>
<td align="right">
	<table  cellpadding="0" cellspacing="0">
	<tr>
	<td class="main" nowrap></td>
	<td nowrap class="main	">
	&nbsp;<?php include("affiliate_filter_form.php"); ?>
	</td>
	</tr>
	</table>
</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">联盟成员 <?php echo TABLE_HEADING_AFFILIATE_USERNAME .'/<br>' . TABLE_HEADING_IPADDRESS; ?> 浏览者IP</td>
                <td class="dataTableHeadingContent" align="center">浏览日期 <?php echo TABLE_HEADING_ENTRY_DATE .'/<br>' . TABLE_HEADING_REFERRAL_URL; ?> 参考网址</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CLICKED_PRODUCT; ?> 被点击的产品</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BROWSER; ?> 浏览器信息</td>
              </tr>
<?php
  if ($affiliate_clickthroughs_numrows > 0) {
    $affiliate_clickthroughs_values = tep_db_query($affiliate_clickthroughs_raw);
    $number_of_clickthroughs = '0';
    while ($affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_values)) {
      $number_of_clickthroughs++;

      if ( ($number_of_clickthroughs / 2) == floor($number_of_clickthroughs / 2) ) {
        echo '                  <tr class="productListing-even">';
      } else {
        echo '                  <tr class="productListing-odd">';
      }
?>
                <td class="dataTableContent"><?php echo $affiliate_clickthroughs['affiliate_firstname'] . " " . $affiliate_clickthroughs['affiliate_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_date_short($affiliate_clickthroughs['affiliate_clientdate']); ?></td>
<?php
      if ($affiliate_clickthroughs['affiliate_products_id'] > 0) $link_to = '<a href="' . tep_catalog_href_link(FILENAME_CATALOG_PRODUCT_INFO, 'products_id=' . $affiliate_clickthroughs['affiliate_products_id']) . '" target="_blank">' . $affiliate_clickthroughs['products_name'] . '</a>';
      else $link_to = "Startpage";
?>
                <td class="dataTableContent"><?php echo $link_to; ?></td>
                <td class="dataTableContent" align="center"><?php echo $affiliate_clickthroughs['affiliate_clientbrowser']; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent"><?php echo $affiliate_clickthroughs['affiliate_clientip']; ?></td>
                <td class="dataTableContent" colspan="3"><?php  echo $affiliate_clickthroughs['affiliate_clientreferer']; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent" colspan="4"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
              </tr>
<?php
    }
  } else {
?>
              <tr class="productListing-odd">
                <td colspan="7" class="smallText"><?php echo TEXT_NO_CLICKS; ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="smallText" colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_clickthroughs_split->display_count($affiliate_clickthroughs_numrows,  MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CLICKS); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_clickthroughs_split->display_links($affiliate_clickthroughs_numrows,  MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
			  <!-- amit added to delete click reprot start -->
			  <tr>
			  <td colspan="7" align="right">
			  <?php
			  echo  ' <a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, 'action=delete').'">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>';
			  ?>
			  
			  </td>
			  </tr>
			  <!-- amit added to delete click reprot end -->
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
