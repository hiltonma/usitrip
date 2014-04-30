<?php
// die("此页严重影响了服务器的稳定性，已经被禁止访问！");

/*
 * $Id: stats_ad_results.php, v 2.3 2006/03/22 Date range, sorting and number of
 * sales added by mr_absinthe, www.originalabsinthe.com osCommerce, Open Source
 * E-Commerce Solutions Copyright (c) 2002 osCommerce Released under the GNU
 * General Public License
 */

require ('includes/application_top.php');
// 备注添加删除
if ($_GET['ajax'] == "true") {
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_ad_results_details');
	$remark->checkAction($_GET['action'], $login_id); //添加删除动作，统一在方法里面处理了
}

require (DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
if (isset($HTTP_GET_VARS['start_date'])) {
	$start_date = $HTTP_GET_VARS['start_date'];
} else {
	// $start_date = '';
	$start_date = $HTTP_GET_VARS['start_date'] = $_GET['start_date'] = date("Y-m-01");
}

if (isset($HTTP_GET_VARS['end_date'])) {
	$end_date = $HTTP_GET_VARS['end_date'];
} else {
	//$end_date = '';
	$end_date = $HTTP_GET_VARS['end_date'] = $_GET['end_date'] = date("Y-m-d");
}
if ($start_date != '' && $end_date != '') {
	$extrawhere = " AND  clicks_date BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59'  ";
	$extrawhere_total = " AND  date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59'  ";
}

switch ($_GET['ajax']) {
	case 'one':
		$rows = $_GET['key'];
		echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFAF3">';
		$detailsand = " and customers_advertiser='" . tep_db_input($_GET['value']) . "' ";
		$ad_query_details_raw = "select *,count(*) as medium_count from " . TABLE_AD_SOURCE_CLICKS_STORES . " where utm_medium != ''  " . $extrawhere . " " . $detailsand . " group by utm_medium  ";
		$medium_rows = 0;
		$ad_query_details = tep_db_query($ad_query_details_raw);
		
		$total_cnt_details = tep_db_num_rows($ad_query_details);
		
		if ($total_cnt_details == 0) {
			
			echo '<tr><td height="16" class="dataTableContent" colspan="5">No Medium found.</td></tr>';
		} else {
			
			while ($ads_details = tep_db_fetch_array($ad_query_details)) {
				
				$medium_rows ++;
				?>
<tr>
	<td class="dataTableContent" width="30%" height="16">
		<DIV id="cnt_<?=$medium_rows.'_'.$rows;?>">
			<span>&nbsp;&nbsp;<A
				href="javascript:showHide('<?=$medium_rows.'_'.$rows;?>','two','<? echo $_GET['value'],'___',$ads_details['utm_medium']?>');"><IMG
					id="cnt_icon_<?=$medium_rows.'_'.$rows;?>" title="" height=11
					alt="" src="images/icon_plus.gif" width=11 border=0></A>&nbsp;<?php echo tep_db_output($ads_details['utm_medium']);?></span>
	
	</td>
	<td class="dataTableContent" width="15%" style="text-indent: 1px;">
							 <?php echo tep_db_output($ads_details['medium_count']);?>
							</td>
	<td class="dataTableContent" width="15%" style="text-indent: 3px;"><?php
				//amit added for individule query start
				$ad_query_raw_total_source = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", " . TABLE_AD_SOURCE_CLICKS_STORES . " as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' AND ascs.utm_medium = '" . tep_db_input($ads_details['utm_medium']) . "' and orders.customers_advertiser ='" . tep_db_input(tep_db_prepare_input($ads_details['customers_advertiser'])) . "' and orders.customers_advertiser = ascs.customers_advertiser   " . $extrawhere_total . " AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by orders.customers_advertiser,ascs.utm_medium ORDER BY orders.customers_advertiser";
				$ad_query_total_source = tep_db_query($ad_query_raw_total_source);
				$ads_total_source = tep_db_fetch_array($ad_query_total_source);
				//amit added for individule source end
				?>
							<?php
				echo (int) $ads_total_source['count'];
				?></td>
	<td class="dataTableContent" width="20%" align="right"><?php
				echo $currencies->format($ads_total_source['total_value']);
				?>&nbsp;</td>
	<td class="dataTableContent" width="20%" align="right"><?php
				if ($ads_details['medium_count'] > 0) {
					echo $currencies->format($ads_total_source['total_value'] / $ads_details['medium_count']);
				}
				?></td>
</tr>
<tr>
	<td colspan="5">
		<DIV id="cnt_desc_<?=$medium_rows.'_'.$rows;?>" style="DISPLAY: none"></div>
		</div>
	</td>
</tr>
<?php
			}
		}
		
		echo '</table>';
		
		exit();
		break;
	case 'two':
		?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#F1F5F5">
						   <?php
		$divedisplay_id = 'cnt_desc_' . $_GET['key'];
		
		$arr_tmp = explode('___', $_GET['value']);
		$utm_medium = $arr_tmp[1];
		$customers_advertiser = $arr_tmp[0];
		$key_limit = " ";
		$sortorder_all = ' order by key_cnt desc, utm_term asc ' . $key_limit;
		$ad_query_details_raw_all = "select *, count(*) as key_cnt from " . TABLE_AD_SOURCE_CLICKS_STORES . " where utm_medium = '" . tep_db_input($utm_medium) . "'  " . $extrawhere . " " . $detailsand . " group by utm_term " . $sortorder_all;
		$ad_query_details_all_array = tep_db_query($ad_query_details_raw_all);
		$keyword_count = 0;
		while ($ad_query_details_all = tep_db_fetch_array($ad_query_details_all_array)) {
			
			$keyword_count ++;
			?>
						  
						   <tr>
		<td class="dataTableContent" height="16" width="30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;<? //=$keyword_count;?><?php echo ($_SESSION['login_id'] == '19' ? tep_db_output($ad_query_details_all['utm_term']) : preg_replace('/\@.+/','...',tep_db_output($ad_query_details_all['utm_term']))); ?></td>
		<td class="dataTableContent" width="15%" style="text-indent: 1px;"><?php  echo tep_db_output($ad_query_details_all['key_cnt']); ?></td>
		<td class="dataTableContent" width="15%" style="text-indent: 3px;">
						   <?php
			//amit added for individule query start
			$ad_query_raw_total_source = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", " . TABLE_AD_SOURCE_CLICKS_STORES . " as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' and ascs.utm_term = '" . tep_db_input($ad_query_details_all['utm_term']) . "'  AND ascs.utm_medium = '" . tep_db_input($utm_medium) . "' and orders.customers_advertiser ='" . tep_db_input(tep_db_prepare_input($customers_advertiser)) . "' and orders.customers_advertiser = ascs.customers_advertiser   " . $extrawhere_total . " AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by ascs.utm_medium ORDER BY orders.customers_advertiser";
			$ad_query_total_source = tep_db_query($ad_query_raw_total_source);
			$ads_total_source = tep_db_fetch_array($ad_query_total_source);
			//amit added for individule source end
			//取得具体订单号start {
			$ad_query_raw_orders = tep_db_query("select orders.orders_id from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", " . TABLE_AD_SOURCE_CLICKS_STORES . " as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' and ascs.utm_term = '" . tep_db_input($ad_query_details_all['utm_term']) . "'  AND ascs.utm_medium = '" . tep_db_input($utm_medium) . "' and orders.customers_advertiser ='" . tep_db_input(tep_db_prepare_input($customers_advertiser)) . "' and orders.customers_advertiser = ascs.customers_advertiser   " . $extrawhere_total . " AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by orders.orders_id ");
			$_title = '';
			// 			echo $ad_query_raw_total_source;
			while ($_rows = tep_db_fetch_array($ad_query_raw_orders)) {
				$_title .= $_rows['orders_id'] . ',';
			}
			$_title = substr($_title, 0, -1);
			//取得具体订单号end }
			if ($_title) {
				?>
							<h6>
				<a target="_blank"
					href="<?php echo tep_href_link('orders_fast.php','&search_action=1&Send=Send&s_orders_id='.$_title);?>"
					title="<?php echo ($_title) ? '订单号：'.$_title : '';?>">
							<?php
				echo (int) $ads_total_source['count'];
				?>
							 </a>
			</h6>
						   <?php }else{ /*echo (int)$ads_total_source['count']*/;} ?>
						   <?php //echo tep_db_output($ad_query_details_all['utm_campaign']); ?></td>
		<td class="dataTableContent" width="20%" align="right"><?php
			echo $currencies->format($ads_total_source['total_value']);
			?>&nbsp;</td>
		<td class="dataTableContent" width="20%" align="right">
						     <?php
			if ($ad_query_details_all['key_cnt'] > 0) {
				echo $currencies->format($ads_total_source['total_value'] / $ad_query_details_all['key_cnt']);
			}
			?>
						   </td>
	</tr>						  
						   <?php
		}
		?>
						   
						   <?php if($_GET['show_all'] != 'true') {?>
						  <tr>
		<td class="dataTableContent" colspan="5" align="right">
						   <?php
			// 			//echo '<b><a  href="' . tep_href_link('stats_ad_results_details.php', 'show_all=true&cangeid='.$divedisplay_id.'&query='.$ad_query_details_raw_all.'&'. tep_get_all_get_params(array('action','show_all'))) . '">Show All Keywords</a></b>'; 
			// 			echo '<b><a  onclick="agent.call(\'' . tep_href_link('stats_ad_results_details_ajax.php', 'start_limit=50&customers_advertiser=' . tep_db_output(tep_db_prepare_input($customers_advertiser)) . '&utm_medium=' . tep_db_input($utm_medium) . '&utm_term=' . tep_db_input($ad_query_details_all['utm_term']) . '&cangeid=' . $divedisplay_id . '&' . tep_get_all_get_params(array (
			// 					'action',
			// 					'show_all' 
			// 			))) . '\',\'hello\',\'callback_hello\');return false;" href="#">Show More 50</a></b>';
			// 						?>
						  </td>
	</tr>
						   <?php } ?>
						   </table>
<?php
		
exit();
		break;
}
// AJAXAGENT: including the toolkit 
require ("agent.php");
$agent->init();
// AJAXAGENT: including the toolkit 


?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link rel="stylesheet" type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript"
	src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<script type="text/javascript"
	src="includes/javascript/jquery-1.4.1.min.js"></script>

<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript"><!--

//var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "date_range", "start_date","btnDate","<?php echo $start_date; ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "date_range", "end_date","btnDate1","<?php echo $end_date; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>

<SCRIPT language=javascript type=text/javascript>
<!--
	function getInfo(type,value,key){
	
	htmlobj=$.ajax({url:"stats_ad_results_details.php?ajax="+type+'&value='+value+'&&key='+key+'&&start_date=<?php echo $start_date;?>&&end_date=<?php echo $end_date; ?>',async:false});
  	return htmlobj.responseText;
	}
	function callback_hello(str) {
	  splt=str.split("#####");	  
	  document.getElementById(splt[0]).innerHTML=splt[1];
    }

  function showHide(elementID,type,value) {
  
    var desc = null;

    if (document.getElementById) {
      desc = document.getElementById("cnt_desc_" + elementID);
    } else if (document.all) {
      desc = document.all["cnt_desc_" + elementID];
    } else if (document.layers) {
      desc = document.layers["cnt_desc_" + elementID];
    }
    if (desc) {
	
      if (desc.style.display == 'none') {
    	  if(desc.innerHTML=='')
	  desc.innerHTML=getInfo(type,value,elementID);
        expand(elementID);
      } else {
        collapse(elementID);
      }
    }
  }

  function expand(elementID) {
    var cnt = null;
    var desc = null;
    var icon = null;

    if (document.getElementById) {
      cnt = document.getElementById("cnt_" + elementID);
      desc = document.getElementById("cnt_desc_" + elementID);
      icon = document.getElementById("cnt_icon_" + elementID);
    } else if (document.all) {
      cnt = document.all["cnt_" + elementID];
      desc = document.all["cnt_desc_" + elementID];
      icon = document.all["cnt_icon_" + elementID];
    } else if (document.layers) {
      cnt = document.layers["cnt_" + elementID];
      desc = document.layers["cnt_desc_" + elementID];
      icon = document.layers["cnt_icon_" + elementID];
    }

    if (desc.style.display == 'none') {
	/*
      cnt.style.backgroundColor = '#FFFAF3';
      cnt.style.border = '1px dotted #000000';
      cnt.style.padding = '5px';
      cnt.style.marginBottom = '5px';
	  */
	  cnt.style.backgroundColor = '';
      cnt.style.border = '';
      cnt.style.padding = '';
      cnt.style.marginBottom = '';
      desc.style.display = 'block';
      icon.src = "images/icon_minus.gif"
    }
  }

  function collapse(elementID) {
    var cnt = null;
    var desc = null;
    var icon = null;

    if (document.getElementById) {
      cnt = document.getElementById("cnt_" + elementID);
      desc = document.getElementById("cnt_desc_" + elementID);
      icon = document.getElementById("cnt_icon_" + elementID);
    } else if (document.all) {
      cnt = document.all["cnt_" + elementID];
      desc = document.all["cnt_desc_" + elementID];
      icon = document.all["cnt_icon_" + elementID];
    } else if (document.layers) {
      cnt = document.layers["cnt_" + elementID];
      desc = document.layers["cnt_desc_" + elementID];
      icon = document.layers["cnt_icon_" + elementID];
    }

    if (desc.style.display != 'none') {
      cnt.style.backgroundColor = '';
      cnt.style.border = '';
      cnt.style.padding = '';
      cnt.style.marginBottom = '';
      desc.style.display = 'none';
      icon.src = "images/icon_plus.gif"
    }
  }

  function expandAll() {
    var cnt = null;

    if (document.body.getElementsByTagName) {
      cnt = document.body.getElementsByTagName('DIV');
    } else if (document.body.all) {
      cnt = document.body.all.tags('DIV');
    }

    if (cnt) {
      for (var i=0; i<cnt.length; i++) {
        if (cnt[i].id.substring(0, 4) == 'cnt_') {
          if (cnt[i].id.substring(0, 5) != 'cnt_d') {
            expand(cnt[i].id.substring(4));
          }
        }
      }
    }
  }

  function collapseAll() {
    var cnt = null;

    if (document.body.getElementsByTagName) {
      cnt = document.body.getElementsByTagName('DIV');
    } else if (document.body.all) {
      cnt = document.body.all.tags('DIV');
    }

    if (cnt) {
      for (var i=0; i<cnt.length; i++) {
        if (cnt[i].id.substring(0, 4) == 'cnt_') {
          if (cnt[i].id.substring(0, 5) != 'cnt_d') {
            collapse(cnt[i].id.substring(4));
          }
        }
      }
    }
  }
//-->
</SCRIPT>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
	<!-- header //-->
<?php
if ($printable != 'on') {
	require (DIR_WS_INCLUDES . 'header.php');
}
;
?>
<!-- header_eof //-->

	<!-- body //-->
	<?php
	//echo $login_id;
	include DIR_FS_CLASSES . 'Remark.class.php';
	$listrs = new Remark('stats_ad_results_details');
	$list = $listrs->showRemark();
	?>
	<table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
  <?php
		if ($printable != 'on') {
			;
			?>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0"
					width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1"
					class="columnLeft">
					<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
				</table>
		<?php }; ?>
		</td>
			<!-- body_text //-->
			<td width="100%" valign="top"><table border="0" width="100%"
					cellspacing="0" cellpadding="2">
					<tr>
						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
								</tr>
							</table></td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td></td>
									<td class="main">
<?php
echo tep_draw_form('date_range', 'stats_ad_results_details.php', '', 'get');
echo ENTRY_STARTDATE . '&nbsp;'; //tep_draw_input_field('start_date', $start_date).
?>
<?php echo tep_draw_input_field('start_date', $start_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
	<script type="text/javascript">//dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script>
	<?php
	echo '';
	echo ENTRY_TODATE . '&nbsp;'; //tep_draw_input_field('end_date', $end_date).
	?>
	<?php echo tep_draw_input_field('end_date', $end_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
	<script type="text/javascript">//dateAvailable1.writeControl(); dateAvailable1.dateFormat="yyyy-MM-dd";</script>
	<?php
	//echo ENTRY_PRINTABLE . tep_draw_checkbox_field('printable', $print). '&nbsp;';
	// echo ENTRY_SORTVALUE . tep_draw_checkbox_field('total_value', $total_value). '&nbsp;&nbsp;';
	echo '<input type="submit" value="' . ENTRY_SUBMIT . '">';
	echo '</form>';
	
	$grand_total_value = 0;
	$total_number_sales = 0;
	?>
</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top"><table border="0" width="100%" cellspacing="2"
											cellpadding="2">
											<tr>
			<?php //echo tep_draw_form('ad_results', FILENAME_STATS_AD_RESULTS_DETAILS, 'action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
          </tr>
											<tr class="dataTableHeadingRow">
			  <?php
					$HEADING_SOURCE = TABLE_HEADING_SOURCE . " ";
					$HEADING_SOURCE .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array (
							'page',
							'sort',
							'order',
							'show_all',
							'action' 
					)) . 'sort=utm_source&order=ascending">';
					$HEADING_SOURCE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
					$HEADING_SOURCE .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array (
							'page',
							'sort',
							'order',
							'show_all',
							'action' 
					)) . 'sort=utm_source&order=decending">';
					$HEADING_SOURCE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
					?>
                <td nowrap class="dataTableHeadingContent" width="30%"><?php echo $HEADING_SOURCE; ?></td>
												<!-- <td class="dataTableHeadingContent">#Click</td>-->
												<td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_CLICKS; ?></td>

												<td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_NUMBER_OF_SALES; ?></td>
												<td class="dataTableHeadingContent" width="20%"
													align="right"><?php echo TABLE_HEADING_TOTAL_AMOUNT; ?>&nbsp;</td>
												<td class="dataTableHeadingContent" align="right"
													width="20%"><?php echo '$/visits'; ?></td>
												<!--<td class="dataTableHeadingContent"><?php echo $HEADING_MEDIUM; ?></td>
				 <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TERM; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONTENT; ?></td>
                <td class="dataTableHeadingContent" ><?php echo TABLE_HEADING_NAME; ?>&nbsp;</td>
				-->
											</tr>
<?php

switch ($_GET["sort"]) {
	case 'utm_medium':
		if ($_GET["order"] == "ascending") {
			$sortorder = ' order by t1.utm_medium asc ';
		} else {
			$sortorder = ' order by t1.utm_medium  desc ';
		}
		break;
	case 'utm_source':
		if ($_GET["order"] == "ascending") {
			$sortorder = ' order by t1.customers_advertiser asc ';
		} else {
			$sortorder = ' order by t1.customers_advertiser desc ';
		}
		break;
	default:
		$sortorder = ' order by t1.customers_advertiser  asc ';
		
		break;
}

$extragroupby = '  group by t1.customers_advertiser ';

//$ad_query_raw = "select customers_advertiser, count(*) as ad_count from " . TABLE_AD_SOURCE_CLICKS_STORES . " where 1  " . $extrawhere . " " . $extragroupby . " " . $sortorder;
$ad_query_raw = 'SELECT 
t1.customers_advertiser, 
COUNT(t1.clicks_id) as ad_count
FROM ad_source_clicks_stores t1 
WHERE 1 ' . $extrawhere . $extragroupby . $sortorder;
// echo $ad_query_raw,'<br />';
//echo $ad_query_raw;
define('MAX_DISPLAY_SEARCH_AD_RESULTS', '20');

require (DIR_WS_CLASSES . 'split_page_results_outer.php');

/*
 * $products_split = new splitPageResults($HTTP_GET_VARS['page'],
 * MAX_DISPLAY_SEARCH_AD_RESULTS, $ad_query_raw, $products_query_numrows);
 */

$products_split = new splitPageResults1($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_AD_RESULTS, $ad_query_raw, $products_query_numrows);

$ad_query = tep_db_query($ad_query_raw);

//  $products_query_numrows = tep_db_num_rows($ad_query) ;


while ($ads = tep_db_fetch_array($ad_query)) {
	$rows ++;
	
	if (strlen($rows) < 2) {
		$rows = '0' . $rows;
	}
	?>
				<DIV id="cnt_<?=$rows;?>">
												<tr class="dataTableRow">
													<td class="dataTableContent"><A
														href="javascript:showHide('<?=$rows;?>','one','<?=$ads['customers_advertiser']?>');"><IMG
															id="cnt_icon_<?=$rows;?>" title="" height=11 alt=""
															src="images/icon_plus.gif" width=11 border=0> <?php // echo $ads['customers_advertiser']; ?></A>
														<b><?php echo tep_db_output($ads['customers_advertiser']); ?></b></td>
													<td class="dataTableContent"><b>				
				<?php
	$ad_sun_sql = 'SELECT
orders.customers_advertiser AS customers_advertiser1,
COUNT(*) AS count,
SUM(value) AS total_value
FROM customers, orders, orders_total
WHERE orders.customers_advertiser <> \'\'
AND customers.customers_id = orders.customers_id
AND orders.orders_id = orders_total.orders_id
AND class = \'ot_subtotal\' ' . $extrawhere_total . '
AND orders.customers_advertiser="' . $ads['customers_advertiser'] . '"';
	$sun_query = tep_db_query($ad_sun_sql);
	$sun_ad = tep_db_fetch_array($sun_query);
	$grand_total_ad_count = $grand_total_ad_count + $ads['ad_count'];
	echo tep_db_output($ads['ad_count']);
	?></b></td>
													<td class="dataTableContent"><b><?php
	echo (int) $sun_ad['count'];
	?></b></td>
													<td class="dataTableContent" align="right"><b><?php
	echo $currencies->format($sun_ad['total_value']);
	?>&nbsp;</td>
													<td class="dataTableContent" align="right"><b><?php
	if ($ads['ad_count'] > 0) {
		echo $currencies->format($sun_ad['total_value'] / $ads['ad_count']);
	}
	?></b></td>
												</tr>
												<tr>
													<td colspan="5">
														<DIV id="cnt_desc_<?=$rows;?>" style="DISPLAY: none"></DIV>
											
											</DIV>
											</td>
											</tr>
											<!-- amit addded loop started  -->

											<!-- amit addded loop ended  -->
				
				
		 
	<?php
	$grand_total_value = $grand_total_value + $sun_ad['total_value'];
	$total_number_sales = $total_number_sales + $sun_ad['count'];
}
?>			
	 <!-- last row of each sections -->

											<tr bgcolor="#CDDADA">
												<td class="dataTableContent"><b><?php echo ENTRY_TOTAL; ?></b></td>
												<td class="dataTableContent"><b><?php echo $grand_total_ad_count;?></b></td>

												<td class="dataTableContent"><b><?php echo $total_number_sales; ?></b></td>
												<td class="dataTableContent" align="right"><b><?php echo $currencies->format($grand_total_value); ?></b>&nbsp;</td>
												<td class="dataTableContent" align="right"><b>
				<?php
				if ($grand_total_value > 0) {
					echo $currencies->format($grand_total_value / $grand_total_ad_count);
				}
				?>
				</b></td>
											</tr>

											<!-- last row of each sections -->
										</table></td>
								</tr>



								<tr>
									<td colspan="5">
										<table border="0" width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td class="smallText" valign="top"><?php echo $products_split->display_count1($products_query_numrows, MAX_DISPLAY_SEARCH_AD_RESULTS, $HTTP_GET_VARS['page'], ''); ?></td>
												<td class="smallText" align="right"><?php echo $products_split->display_links1($products_query_numrows, MAX_DISPLAY_SEARCH_AD_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page', 'oID', 'action'))); ?>&nbsp;</td>


											</tr>
										</table>
									</td>
								</tr>
							</table></td>
					</tr>
				</table></td>
			<!-- body_text_eof //-->
		</tr>
	</table>
	<!-- body_eof //-->

	<!-- footer //-->
<?php
if ($printable != 'on') {
	require (DIR_WS_INCLUDES . 'footer.php');
}
?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>