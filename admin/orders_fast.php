<?php
/*
快速订单列表，订单列表的简化版
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $search_msn ='';
  
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) {$rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS_ADMIN - MAX_DISPLAY_SEARCH_RESULTS_ADMIN;}else{
  	$rows = 0;
  }

  //搜索
  $where_exc = ''; 
  
	  //以订单为中心
	  //订单添加的搜索
	  if($_GET['search_action']=='1'){
		if(tep_not_null($_GET['s_orders_id'])){
			$s_orders_id = trim(str_replace('%2C',',',$_GET['s_orders_id']));
			$s_orders_ids = explode(',', $s_orders_id);
			$where_exc .= ' AND o.orders_id in ('.implode(',', $s_orders_ids).') ';
			//$where_exc .= ' AND o.orders_id Like binary ("'.$s_orders_id.'%") ';
		}
		if(tep_not_null($_GET['buy_start_date'])){
			$buy_start_date = trim($_GET['buy_start_date']);
			$where_exc .= ' AND o.date_purchased >="'.$buy_start_date.' 00:00:00" ';
		}
		if(tep_not_null($_GET['buy_end_date'])){
			$buy_end_date = trim($_GET['buy_end_date']);
			$where_exc .= ' AND o.date_purchased <="'.$buy_end_date.' 23:59:59" ';
		}
		if((int)$_GET['s_orders_status']){
			$s_orders_status = (int)$_GET['s_orders_status'];
			$where_exc .= ' AND o.orders_status ="'.(int)$s_orders_status.'" ';
		}
	  }
	  
	  $orders_query_raw = "select o.customers_id, o.orders_id, o.date_purchased, o.orders_status, orders_cancelled_total, ot.text as order_total
	   FROM  " . TABLE_ORDERS . " o, ".TABLE_ORDERS_TOTAL." ot where ot.orders_id = o.orders_id AND ot.class='ot_total' ".$where_exc." Group By o.orders_id Order By o.orders_id DESC";
	//echo 'explain '.$orders_query_raw;
	  $orders_query_numrows = 0;
	  $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_query_raw, $orders_query_numrows);
	
	  $orders_query = tep_db_query($orders_query_raw);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>


<script language="javascript"><!--

var Date_Reg_start = new ctlSpiffyCalendarBox("Date_Reg_start", "form_search", "reg_start_date","btnDate1","<?php echo ($reg_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_Reg_end = new ctlSpiffyCalendarBox("Date_Reg_end", "form_search", "reg_end_date","btnDate2","<?php echo ($reg_end_date); ?>",scBTNMODE_CUSTOMBLUE);

var Date_Buy_start = new ctlSpiffyCalendarBox("Date_Buy_start", "form_search", "buy_start_date","btnDate3","<?php echo ($buy_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_Buy_end = new ctlSpiffyCalendarBox("Date_Buy_end", "form_search", "buy_end_date","btnDate4","<?php echo ($buy_end_date); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<div id="spiffycalendar" class="text"></div>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
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
            <td class="pageHeading">Orders Fast</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'orders_fast.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  <tr id="s_option">
                    <td height="30" align="right" nowrap class="main">Order ID</td>
                    <td class="main" align="left">&nbsp;<?php echo tep_draw_input_field('s_orders_id')?></td>
                    <td>&nbsp;</td>
                    </tr>

                  <tr>
                    <td class="main" align="right"><input name="search_action" type="hidden" id="search_action" value="1"></td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="Send" style="width:100px; height:30px; margin-top:10px;"></td>
                    <td>&nbsp;</td>
                    </tr>
                </table></td>
			  </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>

		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">Order ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Customers</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Date Purchased</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Order Total</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Order Status</td>
              </tr>
<?php
  while ($orders = tep_db_fetch_array($orders_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
	
	$bg_color = "#F0F0F0";
	if((int)$rows %2 ==0 && (int)$rows){
		$bg_color = "#ECFFEC";
	}
?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                <td height="25" class="dataTableContent"><a href="<?php echo tep_href_link('edit_orders.php', tep_get_all_get_params(array('oID', 'action')) . 'oID=' . (int)$orders['orders_id'] . '&action=edit')?>" target="_blank" style="color:#FF6600"><?php echo $orders['orders_id']; ?></a></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID='.(int)$orders['customers_id'].'&action=edit', 'NONSSL') . '">' . db_to_html(tep_customers_name((int)$orders['customers_id'])) . '</a>'; ?></td>
                
				<td class="dataTableContent"><?php echo $orders['date_purchased']?></td>
				<td class="dataTableContent">
				<?php
				echo $orders['order_total'];
				?>				</td>
                <td class="dataTableContent">
				<?php
				$status_sql = tep_db_query('SELECT * FROM `orders_status` WHERE orders_status_id="'.(int)$orders['orders_status'].'" AND language_id="'.(int)$languages_id.'" ');
				$status_rows = tep_db_fetch_array($status_sql);
				echo $status_rows['orders_status_name'];
				?>				</td>
              </tr>
			  
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
		
		</fieldset>
		</td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
