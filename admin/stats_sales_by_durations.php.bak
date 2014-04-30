<?php
/*
  $Id: stats_products_purchased.php,v 1.1.1.1 2004/03/04 23:38:59 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('stats_sales_by_durations');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_sales_by_durations');
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
		  	<td colspan="2">
			<table width="52%" align="right" cellpadding="2" cellspacing="0" border="0">
				<?php  echo tep_draw_form('search', FILENAME_STATS_SALES_BY_DURATIONS, '', 'get'); ?>
				
				<tr>
				<?php
				if(!isset($HTTP_GET_VARS['action']) && !isset($HTTP_GET_VARS['order_date_purchased_from']) && !isset($HTTP_GET_VARS['order_date_purchased_to'])){		
				  $_GET['order_date_purchased_from'] = $HTTP_GET_VARS['order_date_purchased_from'] = date('m/01/Y');	  
				}
				//get date difference
					$duration_date_from = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']);
					if(!isset($HTTP_GET_VARS['order_date_purchased_to']) || $HTTP_GET_VARS['order_date_purchased_to'] == ''){
						$duration_date_to = date("Y-m-d h:i:s");
					}else{
						$duration_date_to = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']);
					}
					$difference_time = strtotime($duration_date_to) - strtotime($duration_date_from);
					//line graph
					$difference_days = floor($difference_time/(60*60*24));
				?>
				<script type="text/javascript">
//var order_date_purchased_start = new ctlSpiffyCalendarBox("order_date_purchased_start", "search", "order_date_purchased_from","btnDate3","<?php echo tep_get_date_disp($_GET['order_date_purchased_from']); ?>",scBTNMODE_CUSTOMBLUE);
//var order_date_purchased_end = new ctlSpiffyCalendarBox("order_date_purchased_end", "search", "order_date_purchased_to","btnDate4","<?php echo tep_get_date_disp($_GET['order_date_purchased_to']); ?>",scBTNMODE_CUSTOMBLUE);
</script>
				<?php //echo tep_draw_form('search', FILENAME_TOUR_GROSS_PROFIT_REPORT, '', 'get'); ?>
					<td class="smallText">Order Date Start</td>
					<td class="smallText" >From:
					<?php echo tep_draw_input_field('order_date_purchased_from', tep_get_date_disp($_GET['order_date_purchased_from']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
					<script type="text/javascript">//order_date_purchased_start.writeControl(); order_date_purchased_start.dateFormat="MM/dd/yyyy";</script> To:
					<?php echo tep_draw_input_field('order_date_purchased_to', tep_get_date_disp($_GET['order_date_purchased_to']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
						<script type="text/javascript">//order_date_purchased_end.writeControl(); order_date_purchased_end.dateFormat="MM/dd/yyyy";</script>
					</td>
					<td valign="middle">&nbsp;<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?></td>
				</tr>
				<?php echo '</form>'; ?>
			</table>			
			</td>
		  </tr>
		  <tr>
            <td width="48%" align="left" class="main" id="piechart_images">
				# of orders:<br>
				<img id="piechart_iframe"><br>
				Revenue:<br>
				<img id="revenue_piechart_iframe">
			</td>
			<td valign="top" align="right" width="52%">
			
			<table border="0" width="100%" cellspacing="0" cellpadding="3">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_DURATION; ?></td>				
                <td class="dataTableHeadingContent" align="right" width="30%"><?php echo TABLE_HEADING_NO_OF_ORDERS; ?></td>
				<td class="dataTableHeadingContent" align="right" width="30%"><?php echo TABLE_HEADING_GROSS_PRICE; ?></td>
				<?php
				if($login_groups_id == 1){
				?>
				<td class="dataTableHeadingContent" align="right" width="20%"><?php echo TABLE_HEADING_GROSS; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS_PROFIT; ?></td>
				<td class="dataTableHeadingContent" align="right" width="20%"><?php echo TABLE_HEADING_GROSS_PROFIT_PERCENTAGE; ?></td>
				<?php
				}
				?>
              </tr>
<?php
	  if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) 
						  {
						  
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']). " 00:00:00";					 
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']). " 00:00:00";
						  $where_date .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (!isset($HTTP_GET_VARS['order_date_purchased_to']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']) . " 00:00:00";
						  
						  $where_date .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['order_date_purchased_from']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']) . " 00:00:00";
						  
						 $where_date .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						 
						 }
						 
/*
0-1		less than 1 day
1-1		1 day
2-2		2 days
2-3		2 to 3 days
3-3		3 days
3-4		3 to 4 days
4-4		4 days
4-		more than 4 days
5-		more than 5 days
*/						 
$piechart_querystring_data = '';
$piechart_querystring_label = '';

$durations_array = array();
$durations_array[] = '0-1';
$durations_array[] = '1-2';
$durations_array[] = '2-3';
$durations_array[] = '3-4';
$durations_array[] = '4-5';
$durations_array[] = '5-6';
$durations_array[] = '6-7';
$durations_array[] = '7-';

//line graph
$durations_stats = array();
$grand_total_orders = 0;
foreach($durations_array as $key=>$val){
	$where_duration = '';
	if($val == '0-1'){
		$where_duration .= " and (products_durations_type in ('1','2') or (products_durations_type ='0' and p.products_durations >= '0' and p.products_durations <= '1'))";
		$duration = '1';
	}else{
		$products_durations = explode('-',$val);
		$products_durations1 = $products_durations[0];
		$products_durations2 = $products_durations[1];
		$where_duration .= " and products_durations_type ='0' and p.products_durations > '" . $products_durations1 . "' ";
		$duration = $products_durations2;
		if($products_durations2 != ''){
			$where_duration .= " and p.products_durations <= '" . $products_durations2 . "' ";
		}else{
			$duration = $products_durations1 . '+';
		}
	}

 $products_query_raw = "select op.products_id, p.products_durations, op.products_name, count(distinct(o.orders_id)) as total_orders, sum(op.final_price*op.products_quantity)as gross, sum(op.final_price_cost*op.products_quantity) as gross_cost, sum(op.final_price*op.products_quantity) as gross_price, o.date_purchased FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p WHERE o.orders_id = op.orders_id and op.products_id = p.products_id ".$where_duration . $where_date." GROUP BY o.date_purchased"; // p.products_durations  order by ".$sortorder." 
 
//less than one day
// and (products_durations_type in ('1','2') or (products_durations_type ='0' and p.products_durations >= '0' and p.products_durations <= '1'))

//else
// and (p.products_durations >= '" . $products_durations1 . "' and p.products_durations <= '" . $products_durations2 . "'

  $products_query = tep_db_query($products_query_raw);  
  $total_orders = 0;
  $gross_revenue = 0;
  $gross_cost = 0;
  $gross_price = 0;

  while($products = tep_db_fetch_array($products_query)){  	
	  $total_orders = $total_orders + $products['total_orders'];
	  $gross_revenue = $gross_revenue + $products['gross'];
	  $gross_price = $gross_price + $products['gross_price'];
	  $gross_cost = $gross_cost + $products['gross_cost'];
	
	
	  //line graph
	  $durations_stats[$key][] = array(date('dm',strtotime($products['date_purchased'])), $products['total_orders'], $products['gross']);
  }
  ?>
  <tr class="dataTableRow">
	  <td class="dataTableContent"><?php echo $duration; ?></td>
	  <td class="dataTableContent" align="right" height="16"><?php echo $total_orders; ?>
	  <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); 
	  if($total_orders > 0){
	  ?> 
	  <a href="javascript: popupWindow('linegraph.php?type=order&duration_key=<?php echo $key; ?>');" title="statistics"><img src="images/icons/statistics.gif" border="0" alt="statistics"></a>
	  <?php
	  }else{
	  echo tep_draw_separator('pixel_trans.gif', '20', '1'); 
	  }
	  ?>
  	  </td>
	  <td class="dataTableContent" align="right">$<?php echo sprintf('%01.2f', $gross_revenue); ?>
	  <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); 
	  if($total_orders > 0){
	  ?> 
	  <a href="javascript: popupWindow('linegraph.php?type=revenue&duration_key=<?php echo $key; ?>');" title="statistics"><img src="images/icons/statistics.gif" border="0" alt="statistics"></a>
	  <?php
	  }else{
	  echo tep_draw_separator('pixel_trans.gif', '20', '1'); 
	  }
	  ?>
	  </td>
	  <?php
	  if($login_groups_id == 1){
		$gross_profit = sprintf('%01.2f', ($gross_price - $gross_cost));
		if($gross_price != 0){	
			$margin = tep_round((((($gross_price)-($gross_cost))/($gross_price))*100), 0);
		}else{
			$margin = 0;
		}
	  ?>
	  <td class="dataTableContent" align="right">$<?php echo sprintf('%01.2f', $gross_cost); ?>&nbsp;</td>
	  <td class="dataTableContent" align="right">$<?php echo $gross_profit;?></td>
	  <td class="dataTableContent" align="right"><?php echo $margin . '%';?></td>
	  <?php
	  }
	  ?>
  </tr>
  <?php
  //?data=25*25*25*25&label=Denmark*Germany*USA*Sweden
  $grand_total_orders = $grand_total_orders + $total_orders;
  $piechart_querystring_data .= $total_orders.'*';
  $piechart_querystring_data_revenue .= $gross_revenue.'*';
  $piechart_querystring_label .= $duration.' day(s)*';
  
  }// end foreach
  
  if($grand_total_orders == 0){
  	
	?>
	<script type="text/javascript">
		document.getElementById('piechart_images').innerHTML = '';
	</script>
	<?php
  }else{
  include(DIR_WS_CLASSES . 'phplot.php');
  $piechart_querystring = 'data='.substr($piechart_querystring_data,0,-1) .'&label='. substr($piechart_querystring_label,0,-1);
  $piechart_querystring_revenue = 'data='.substr($piechart_querystring_data_revenue,0,-1) .'&label='. substr($piechart_querystring_label,0,-1);
  ?>
  <script type="text/javascript">
  	document.getElementById('piechart_iframe').src = "piechart.php?<?php echo $piechart_querystring; ?>";
	document.getElementById('revenue_piechart_iframe').src = "piechart.php?<?php echo $piechart_querystring_revenue; ?>";
  </script>
  <?php
  }
  ?>
  
            </table></td>
			
          </tr>
		  
		  <?php
		  //line graph
		  if($total_orders > 0){
		  $loop_incrementor = (24*60*60);
		  
		  
		  $loop_start = strtotime($duration_date_from);
		  $loop_end = strtotime($duration_date_to);
		  
		  
			$stats = array();
			foreach($durations_array as $key1=>$val1){
			 $count = 0; 
			 $loop_total = $loop_start;
			  for($i=$loop_start; $i<=$loop_end; $i=$i+$loop_incrementor){
				$duration_array_key = date("dm", $i);
				
				$stats[$key1][$count][0] = date("m/d", $i);
				$stats[$key1][$count][1] = 0;
				$stats[$key1][$count][2] = 0;
				
				//$stats[$key][$duration_array_key] = array
					if(is_array($durations_stats[$key1])){
					foreach($durations_stats[$key1] as $k=>$v){
						if($v[0] == $duration_array_key){
							$stats[$key1][$count][1] = $stats[$key1][$count][1] + $v[1];
							$stats[$key1][$count][2] = $stats[$key1][$count][2] + $v[2];
						}
					}
					
					}
				  $count++;
			  }
			  $duration_stats = array();
			  $duration_revenues = array();
			  if($difference_days > 10 && $difference_days <= 20){ 
			  	foreach($stats[$key1] as $date=>$orders){
					$duration_stats[$key1][0][0] = $stats[$key1][0][0];
					$duration_stats[$key1][0][1] = $stats[$key1][0][1];
					//$duration_stats[$key1][0][2] = $stats[$key1][0][2];
					$duration_revenues[$key1][0][0] = $stats[$key1][0][0];
					$duration_revenues[$key1][0][1] = $stats[$key1][0][2];
					$total_loop = (sizeof($stats[$key1])-1);
					$i = 1;
					for($d=1; $d<$total_loop; $d=$d+2){
						$duration_stats[$key1][$i][0] = $stats[$key1][$d+1][0];
						$duration_stats[$key1][$i][1] = $stats[$key1][$d][1] + $stats[$key1][$d+1][1];
						//$duration_stats[$key1][$i][2] = $stats[$key1][$d][2] + $stats[$key1][$d+1][2];
						$duration_revenues[$key1][$i][0] = $stats[$key1][$d+1][0];
						$duration_revenues[$key1][$i][1] = $stats[$key1][$d][1] + $stats[$key1][$d+1][2];
						$i++;
					}
					
				}
				if(($difference_days%2) > 0){
					$duration_stats[$key1][$i][0] = $stats[$key1][$d][0];
					$duration_stats[$key1][$i][1] = $stats[$key1][$d][1];
					//$duration_stats[$key1][$i][2] = $stats[$key1][$d][2];					
					$duration_revenues[$key1][$i][0] = $stats[$key1][$d][0];
					$duration_revenues[$key1][$i][1] = $stats[$key1][$d][2];
					}
			  }else if($difference_days > 20 && $difference_days <= 60){ 
			  	foreach($stats[$key1] as $date=>$orders){
					$duration_stats[$key1][0][0] = $stats[$key1][0][0];
					$duration_stats[$key1][0][1] = $stats[$key1][0][1];
					//$duration_stats[$key1][0][2] = $stats[$key1][0][2];
					$duration_revenues[$key1][0][0] = $stats[$key1][0][0];
					$duration_revenues[$key1][0][1] = $stats[$key1][0][2];
					$total_loop = (sizeof($stats[$key1])-2);
					$i = 1;
					for($d=1; $d<$total_loop; $d=$d+7){
						$total_orders = 0;
						$total_revenue = 0;
						for($j=0;$j<7;$j++){
							$total_orders = $total_orders + $stats[$key1][$d+$j][1];
							$total_revenue = $total_revenue + $stats[$key1][$d+$j][2];
						}
						if(($d+6)>$total_loop){
						$duration_stats[$key1][$i][0] = $stats[$key1][$total_loop][0];
						$duration_revenues[$key1][$i][0] = $stats[$key1][$total_loop][0];
						}else{
						$duration_stats[$key1][$i][0] = $stats[$key1][$d+6][0];
						$duration_revenues[$key1][$i][0] = $stats[$key1][$d+6][0];
						}
						$duration_stats[$key1][$i][1] = $total_orders;
						//$duration_stats[$key1][$i][2] = $total_revenue;
						$duration_revenues[$key1][$i][1] = $total_revenue;
						
						$i++;						
					}
					
				}
				if(($difference_days%7) > 0){
					$extra_loop = $difference_days - ($d-7);
					$extra_total_orders = 0;
					$extra_total_revenue = 0;
					for($m=$d;$m<=$extra_loop;$m++){
						$extra_total_orders = $extra_total_orders + $stats[$key1][$m][1];						
						$extra_total_revenue = $extra_total_revenue + $stats[$key1][$m][2];						
					}
					$duration_stats[$key1][$i][0] = $stats[$key1][(sizeof($stats[$key1])-1)][0];
					$duration_stats[$key1][$i][1] = $extra_total_orders;
					//$duration_stats[$key1][$i][2] = $extra_total_revenue;
					$duration_revenues[$key1][$i][0] = $stats[$key1][(sizeof($stats[$key1])-1)][0];
					$duration_revenues[$key1][$i][1] = $extra_total_revenue;
					}
			  }else if($difference_days > 60){ 
			  	foreach($stats[$key1] as $date=>$orders){
					$duration_stats[$key1][0][0] = $stats[$key1][0][0];
					$duration_stats[$key1][0][1] = $stats[$key1][0][1];
					//$duration_stats[$key1][0][2] = $stats[$key1][0][2];
					$duration_revenues[$key1][0][0] = $stats[$key1][0][0];
					$duration_revenues[$key1][0][1] = $stats[$key1][0][2];
					$total_loop = (sizeof($stats[$key1])-1);
					$i = 1;
					for($d=1; $d<$total_loop; $d=$d+30){
						$total_orders = 0;
						$total_revenue = 0;
						for($j=0;$j<30;$j++){
							$total_orders = $total_orders + $stats[$key1][$d+$j][1];
							$total_revenue = $total_revenue + $stats[$key1][$d+$j][2];
						}
						if(($d+29)>$total_loop){
						$duration_stats[$key1][$i][0] = $stats[$key1][$total_loop][0];
						$duration_revenues[$key1][$i][0] = $stats[$key1][$total_loop][0];
						}else{
						$duration_stats[$key1][$i][0] = $stats[$key1][$d+29][0];
						$duration_revenues[$key1][$i][0] = $stats[$key1][$d+29][0];
						}
						$duration_stats[$key1][$i][1] = $total_orders;
						//$duration_stats[$key1][$i][2] = $total_revenue;
						$duration_revenues[$key1][$i][1] = $total_revenue;
						$i++;						
					}
					
				}
				
			  }else{
			  	//$duration_stats[$key1] = $stats[$key1];
				$n = sizeof($stats[$key1]);
				for($r=0;$r<$n;$r++){
					$duration_stats[$key1][$r][0] = $stats[$key1][$r][0];
					$duration_stats[$key1][$r][1] = $stats[$key1][$r][1];
					$duration_revenues[$key1][$r][0] = $stats[$key1][$r][0];
					$duration_revenues[$key1][$r][1] = $stats[$key1][$r][2];
				}
			  }
			 
			  $extension = tep_banner_image_extension();			
			  $graph = new PHPlot(600, 350, 'images/graphs/duration_graph_' . $key1 . '.' . $extension);			
			  $graph->SetFileFormat($extension);
			  $graph->SetIsInline(1);
			  $graph->SetPrintImage(0);			  
			  $graph->SetYLabel('# of Orders');
			  $graph->SetXLabel('Dates');			
			  $graph->SetSkipBottomTick(1);
			  $graph->SetDrawYGrid(1);
			  $graph->SetPrecisionY(0);
			  $graph->SetPlotType('linepoints');			
			  $graph->SetPlotBorderType('left');
			  $graph->SetTitleFontSize('4');
			  //$graph->SetTitle(sprintf(TEXT_BANNERS_DAILY_STATISTICS, $banner['banners_title'], strftime('%B', mktime(0,0,0,$month)), $year));			
			  $graph->SetBackgroundColor('white');			
			  $graph->SetVertTickPosition('plotleft');
			  $graph->SetDataValues($duration_stats[$key1]);
			  $graph->SetDataColors(array('blue','white'),array('blue', 'white'));			
			  $graph->DrawGraph();			
			  $graph->PrintImage();		
			  
			  
			  $graph1 = new PHPlot(600, 350, 'images/graphs/duration_graph_revenue_' . $key1 . '.' . $extension);			
			  $graph1->SetFileFormat($extension);
			  $graph1->SetIsInline(1);
			  $graph1->SetPrintImage(0);			  
			  $graph1->SetYLabel('Revenue(in $)');
			  $graph1->SetXLabel('Dates');			
			  $graph1->SetSkipBottomTick(1);
			  $graph1->SetDrawYGrid(1);
			  $graph1->SetPrecisionY(0);
			  $graph1->SetPlotType('linepoints');			
			  $graph1->SetPlotBorderType('left');
			  $graph1->SetTitleFontSize('4');
			  //$graph->SetTitle(sprintf(TEXT_BANNERS_DAILY_STATISTICS, $banner['banners_title'], strftime('%B', mktime(0,0,0,$month)), $year));			
			  $graph1->SetBackgroundColor('white');			
			  $graph1->SetVertTickPosition('plotleft');
			  $graph1->SetDataValues($duration_revenues[$key1]);
			  $graph1->SetDataColors(array('red'),array('red'));			
			  $graph1->DrawGraph();			
			  $graph1->PrintImage();		  
		  	}
			
			}//end if total orders
		  ?>
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
</body>
</html>
<script type="text/javascript">
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=600,height=350,screenX=150,screenY=150,top=250,left=300')
}
</script>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>