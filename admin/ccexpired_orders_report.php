<?php

/*

  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('ccexpired_orders_report');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }


  require(DIR_WS_CLASSES . 'currencies.php');

  $currencies = new currencies();



  $orders_statuses = array();

  $orders_status_array = array();

  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");

  while ($orders_status = tep_db_fetch_array($orders_status_query)) {

    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],

                               'text' => $orders_status['orders_status_name']);

    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];

  }


if(!isset($start_date)){
$start_date  = $_GET['start_date'] = $HTTP_GET_VARS['start_date'] = date('m/d/Y');
}

if(!isset($end_date)){
$end_date = $_GET['end_date'] = $HTTP_GET_VARS['end_date'] = date('m/d/Y', strtotime('+60 days'));
}


  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');



  if (tep_not_null($action)) {

    switch ($action) {


        case 'accept_order':

            include(DIR_FS_CATALOG_MODULES.'payment/paypal/admin/AcceptOrder.inc.php');

            break;


    }

  }

//  include(DIR_WS_CLASSES . 'order.php');

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />





<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>



<div id="spiffycalendar" class="text"></div>

<script type="text/javascript"><!--

var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "date_range", "start_date","btnDate","<?php echo $start_date; ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "date_range", "end_date","btnDate1","<?php echo $end_date; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>



<script type="text/javascript" src="includes/menu.js"></script>

<script type="text/javascript" src="includes/general.js"></script>

<script type="text/javascript"><!--

function popupWindow(url) {

  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')

}

//--></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->

<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

<!-- header_eof //-->



<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('ccexpired_orders_report');
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

    <td width="100%" valign="top">
	
	
	<table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>

            <td align="right">
			<?php /*
			<table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr><?php echo tep_draw_form('orders', FILENAME_CCEXPIRED_ORDERS_REPORT, '', 'get'); ?>

                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>

              </form></tr>

              <tr><?php echo tep_draw_form('status', FILENAME_CCEXPIRED_ORDERS_REPORT, '', 'get'); ?>

                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'); ?></td>

              </form></tr>

            </table>
			*/ ?>
			</td>

          </tr>
		  
		  

        </table></td>

      </tr>
	    <tr><td>
	  <table>
<tr><td></td><td class="main">

<?php
/*
    echo tep_draw_form('date_range',FILENAME_CCEXPIRED_ORDERS_REPORT , '', 'get');
	
	echo '<br>';
    echo ENTRY_STARTDATE .  '&nbsp;'; //tep_draw_input_field('start_date', $start_date).
	?>
	<script type="text/javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="MM/dd/yyyy";</script>
	<?php
    echo '';
    echo ENTRY_TODATE .  '&nbsp;';//tep_draw_input_field('end_date', $end_date).
    ?>
	<script type="text/javascript">dateAvailable1.writeControl(); dateAvailable1.dateFormat="MM/dd/yyyy";</script>
	<?php	
    echo '<input type="submit" value="'. ENTRY_SUBMIT .'">';
	echo '<br>';
	echo '<br>';
    echo '</form>';

   */
?>
</td></tr>
</table></td></tr> 

      <tr>

        <td><table border="0" width="95%" cellspacing="0" cellpadding="0">

          <tr>

          <tr>

            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  $HEADING_CUSTOMERS = TABLE_HEADING_CUSTOMERS;

  $HEADING_CUSTOMERS .= '<br><a href="' . $_SERVER['PHP_SELF'] . '?sort=customer&order=ascending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_CUSTOMERS .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';

  $HEADING_CUSTOMERS .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=customer&order=decending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_CUSTOMERS .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';

  $HEADING_DATE_PURCHASED = TABLE_HEADING_DATE_PURCHASED;

  $HEADING_DATE_PURCHASED .= '<br><a href="' . $_SERVER['PHP_SELF'] . '?sort=date&order=ascending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_DATE_PURCHASED .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';

  $HEADING_DATE_PURCHASED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=date&order=decending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_DATE_PURCHASED .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';

  $HEADING_AD_SOURCE = 'Ad Source';

  $HEADING_DATE_OF_DEPARTURE ='Date of Departure' ;

  $HEADING_DATE_OF_DEPARTURE .= '<br><a href="' . $_SERVER['PHP_SELF'] . '?sort=departure_date&order=ascending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_DATE_OF_DEPARTURE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';

  $HEADING_DATE_OF_DEPARTURE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=departure_date&order=decending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_DATE_OF_DEPARTURE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
  
  $HEADING_DATE_OF_CC_EXPIRED ='CC Expired' ;

  $HEADING_DATE_OF_CC_EXPIRED .= '<br><a href="' . $_SERVER['PHP_SELF'] . '?sort=ccexpired&order=ascending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_DATE_OF_CC_EXPIRED .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';

  $HEADING_DATE_OF_CC_EXPIRED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=ccexpired&order=decending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').(isset($HTTP_GET_VARS['admin_id']) ? '&admin_id=' . $HTTP_GET_VARS['admin_id'] . '' : '') . (isset($HTTP_GET_VARS['start_date']) ? '&start_date=' . $HTTP_GET_VARS['start_date'] . '' : ''). (isset($HTTP_GET_VARS['end_date']) ? '&end_date=' . $HTTP_GET_VARS['end_date'] . '' : '').'">';

  $HEADING_DATE_OF_CC_EXPIRED .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';

 ?>

              <tr class="dataTableHeadingRow">

                <td class="dataTableHeadingContent"><?php echo HEADING_ORDER_ID; ?></td>
				
			    <td class="dataTableHeadingContent"><?php echo $HEADING_CUSTOMERS; ?></td>

				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>

                <td class="dataTableHeadingContent" align="center"><?php echo $HEADING_DATE_PURCHASED; ?></td>
				
				<td class="dataTableHeadingContent" align="center"><?php echo $HEADING_DATE_OF_DEPARTURE; ?></td>
				
				<td class="dataTableHeadingContent" align="center"><?php echo $HEADING_DATE_OF_CC_EXPIRED; ?></td>
				
                <td class="dataTableHeadingContent" ><?php echo TABLE_HEADING_STATUS; ?></td>

               

              </tr>

<?php

    $sortorder = 'order by ';

	$addedtable ='';
	
	
	
	
	/*
	if(isset($HTTP_GET_VARS['admin_id']) && (int)$HTTP_GET_VARS['admin_id'] > 0 ){
		$addextracondition = " o.admin_id_orders ='".$HTTP_GET_VARS['admin_id']."' and ";
	
	}else{
		$addextracondition = " o.admin_id_orders > 0 and ";
	}
	*/
	
	/*
	$addextracondition = " o.cc_expires != '' and ( ("; //cancelled, refunded, charge captured and charged
	
	if(isset($HTTP_GET_VARS['start_date']) && $HTTP_GET_VARS['start_date'] != ''){
	$start_date_slit_array = explode('/',$start_date);
	$get_cc_expire_start = $start_date_slit_array[2].'-'.$start_date_slit_array[0].'-01';
	$addextracondition .= "   CONCAT('".substr(date('Y'),0,2)."',MID(o.cc_expires,3,2),'-',MID(o.cc_expires,1,2),'-','01')   >='".$get_cc_expire_start."' and";
	}
	
	if(isset($HTTP_GET_VARS['end_date']) && $HTTP_GET_VARS['end_date'] != ''){
	$end_date_slit_array = explode('/',$end_date);
	$get_cc_expire_end = $end_date_slit_array[2].'-'.$end_date_slit_array[0].'-31';
	$addextracondition .= " CONCAT('".substr(date('Y'),0,2)."',MID(o.cc_expires,3,2),'-',MID(o.cc_expires,1,2),'-','31') <='".$get_cc_expire_end."' ";
	}
	
	$addextracondition .= " ) or (DATEDIFF(now(),o.date_purchased) >30) ) and o.orders_status not in(6,100005,100006,100048) and";

*/

 $addextracondition = " o.cc_expires != '' and  DATEDIFF(now(),o.date_purchased) >25 and o.orders_status not in(6,100005,100006,100048) and ";
	
    if($_GET["sort"] == 'customer') {

      if($_GET["order"] == 'ascending') {

        $sortorder .= 'o.customers_name  asc, ';

      } else {

        $sortorder .= 'o.customers_name desc, ';

      }

    } elseif($_GET["sort"] == 'date') {

      if($_GET["order"] == 'ascending') {

        $sortorder .= 'o.date_purchased  asc, ';

      } else {

        $sortorder .= 'o.date_purchased desc, ';

      }

    } elseif($_GET["sort"] == 'ccexpired') {

      if($_GET["order"] == 'ascending') {

        $sortorder .= 'o.cc_expires  asc, ';

      } else {

        $sortorder .= 'o.cc_expires desc, ';

      }

    }elseif($_GET["sort"] == 'departure_date') {

       if($_GET["order"] == 'ascending') {

        $sortorder .= 'opdatemin asc, ';

		 

      } else {

        $sortorder .= 'opdatemax desc, ';

		

      }

	 

    } else{
	
		//$sortorder .= 'o.cc_expires  asc, ';
		$sortorder .= 'opdatemin asc, ';
		
	}

	//overwirte old condition
	$addselect = " min(op.products_departure_date) AS opdatemin, ";
	
	$addselect .= " max(op.products_departure_date) AS opdatemax, ";
  
   $addedtable = ", " . TABLE_ORDERS_PRODUCTS . " as op ";

   $addextracondition .= " o.orders_id = op.orders_id  and ";

  $addgroupby = " group by  o.orders_id ";
	  

    $sortorder .= 'o.orders_id ASC';
	
	

/*
    if (isset($HTTP_GET_VARS['cID'])) {

      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);

      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id,o.customers_advertiser,  o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, ot.value as order_total_value,  o.admin_id_orders from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s ".$addedtable." where ". $addextracondition." o.customers_id = '" . (int)$cID . "' and ot.orders_id = o.orders_id and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";

    } elseif (isset($HTTP_GET_VARS['status']) && (tep_not_null($HTTP_GET_VARS['status']))) {

      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);

      $orders_query_raw = "select o.orders_id, o.customers_name,o.customers_advertiser,  o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, ot.value as order_total_value, o.admin_id_orders from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s ".$addedtable." where  ". $addextracondition." o.orders_status = s.orders_status_id and ot.orders_id = o.orders_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC";

    } else {
	
	*/

	$orders_query_raw = "select o.orders_id, o.cc_expires, ".$addselect." o.customers_name,o.customers_advertiser,  o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, ot.value as order_total_value, o.admin_id_orders from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s ".$addedtable." where ". $addextracondition." o.orders_status = s.orders_status_id and ot.orders_id = o.orders_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' " . $addgroupby . $sortorder ;

   /* } */
	
    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_query_raw, $orders_query_numrows);

    $orders_query = tep_db_query($orders_query_raw);
	
	$grand_total_value = 0;
    $total_number_sales = 0;

    while ($orders = tep_db_fetch_array($orders_query)) {

    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders['orders_id']))) && !isset($oInfo)) {

        $oInfo = new objectInfo($orders);

      }



      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id) && $froce_hide == 'true') {

        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CCEXPIRED_ORDERS_REPORT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";

      } else {

        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CCEXPIRED_ORDERS_REPORT, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";

      }

?>

                <td class="dataTableContent" ><?php echo $orders['orders_id']; ?></td>
				
				
				<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID','sort','order', 'start_date', 'end_date' , 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>

               	<td class="dataTableContent" align="right"><?php echo $currencies->format($orders['order_total_value']); 
				
				$total_number_sales = $total_number_sales + $orders['order_total_value'];
				?></td>

                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
				
				<td class="dataTableContent" ><?php echo tep_get_date_of_departure($orders['orders_id']); ?></td>

 		<td class="dataTableContent" align="center"><?php echo $orders['cc_expires']; ?></td>
				
                <td class="dataTableContent" ><?php echo $orders['orders_status_name']; ?></td>

            
              </tr>

<?php

    }

?>

			<tr class="dataTableRowSelected">
			<td  class="dataTableContent"></td>
			
			<td  class="dataTableContent"></td>
			<td  class="dataTableContent" align="right"><b><?php echo $currencies->format($total_number_sales); ?></b></td>
			<td  class="dataTableContent"></td>
			<td  class="dataTableContent"></td>
			<td  class="dataTableContent"></td>
			<td  class="dataTableContent"></td>
			
			</tr>

              <tr>

                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">

                  <tr>

                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>

                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>

                  </tr>

                </table></td>

              </tr>

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

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>


