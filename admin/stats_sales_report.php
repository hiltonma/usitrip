<?php
/*
  $Id: stats_sales_report.php,v 1.3 2002/11/27 19:02:22 cwi Exp $

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('stats_sales_report');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

if(isset($HTTP_GET_VARS['active'])=="true" && isset($HTTP_GET_VARS['active']))
{	
	$allow_access_to_profit=false;
	if($login_groups_id == '1' || $login_groups_id == '10'){
	$allow_access_to_profit=true;
	}
  if(isset($HTTP_GET_VARS['text']) && $HTTP_GET_VARS['text'] != ''){
		$where .= " and o.date_purchased >='".$HTTP_GET_VARS['text']."T00:00:00'";
  }
  if(isset($HTTP_GET_VARS['dedate']) && $HTTP_GET_VARS['dedate'] != ''){
		$where .= " and o.date_purchased <='".$HTTP_GET_VARS['dedate']."T00:00:00' ";
  }
  if(isset($HTTP_GET_VARS['dedate']) && $HTTP_GET_VARS['dedate'] == ''){
  					$mo = date("m", strtotime($HTTP_GET_VARS['text']));
					$day = date("d", strtotime($HTTP_GET_VARS['text'])) + 1;
					if($day < 10){$day = '0'.$day;}
					$yea = date("Y", strtotime($HTTP_GET_VARS['text']));					
					$da = $yea.'-'.$mo.'-'.$day;
		$where .= " and o.date_purchased <='".$da."T00:00:00' ";
  }
  if(isset($HTTP_GET_VARS['keyword']) && $HTTP_GET_VARS['keyword'] != ''){
		$where .= " and (op.products_model like '%".$HTTP_GET_VARS['keyword']."%' or op.products_id like '%".$HTTP_GET_VARS['keyword']."%' or op.products_name like '%".$HTTP_GET_VARS['keyword']."%') ";
  }
 if(isset($HTTP_GET_VARS['provider']) && $HTTP_GET_VARS['provider'] != '')
 {
 	$where .= " and p.agency_id='".$HTTP_GET_VARS['provider']."'";
 }

 $product_query_raw_pop="select o.orders_id,o.date_purchased,op.products_id,op.orders_id,op.products_quantity, final_price, op.final_price_cost FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta, ".TABLE_ORDERS_TOTAL." as ot WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id and ot.orders_id = o.orders_id and ot.class = 'ot_total' AND ".$HTTP_GET_VARS['filters']." ".$where."";
	$products_query_raw_exe = tep_db_query($product_query_raw_pop);
	if(tep_db_num_rows($products_query_raw_exe)>0){
		?>
		<table cellpadding="2" style="border:1px solid #006666" bgcolor="#DDEEEB" cellspacing="2" class="dataTableContent" align="center">
			<tr style="background-color:#006666; color:#FFFFFF;">
				<td align="right" nowrap="nowrap"><strong>#Order ID</strong></td>
				
				<td align="right"><strong>Revenue</strong></td>
				<?php
				if($allow_access_to_profit == true){
				?>
				<td align="right"><strong>Gross Profit</strong></td>
				<td align="right" nowrap="nowrap"><strong>Gross Profit<br>(%)</strong></td>
				<?php } ?>
				<?php
					if($HTTP_GET_VARS['dedate'] != ''){					
					$mo = date("m", strtotime($HTTP_GET_VARS['dedate']));
					$day = date("d", strtotime($HTTP_GET_VARS['dedate'])) - 1;
					if($day < 10){$day = '0'.$day;}
					$yea = date("Y", strtotime($HTTP_GET_VARS['dedate']));					
					$d = date('m/d/Y',strtotime($HTTP_GET_VARS['text'])).' - '.$mo.'/'.$day.'/'.$yea;						
					}else{					
						$d = date('m/d/Y',strtotime($HTTP_GET_VARS['text']));
					}
				?>	
				<td align="right" colspan="2" ><?php echo tep_image(DIR_WS_IMAGES.'close_window.jpg','close',15,13,' onclick="javascript:close_div(\'show_info_popup_'.$d.'\',\'none\');"'); ?></td>
			</tr>
			<?php
			$sum_price = '';
			$running_final_price = 0;
			$running_final_price_cost = 0;
			$running_gross_profit = 0 ;
			$running_margin = 0 ;
			$items_sold_cnt =0;
			while($row_products = tep_db_fetch_array($products_query_raw_exe)){
			  $running_final_price += $row_products['final_price'];
			  $running_final_price_cost += $row_products['final_price_cost'];
			 $gross_profit = number_format( $row_products['final_price'] - $row_products['final_price_cost'], 2 );
			 
			  $running_gross_profit += $gross_profit;
			  if($row_products['final_price'] != 0){
			  $margin = tep_round((((($row_products['final_price'])-($row_products['final_price_cost']))/($row_products['final_price']))*100), 0);
			  }else{
			  $margin = 0;
			  }
			  $running_margin += $margin;
			?>
			<tr>
				<td align="right"><?php echo '<a class="col_a1" href='.tep_href_link(FILENAME_EDIT_ORDERS,'oID='.$row_products['orders_id']).' title="Edit" target="_blank"><b>'.$row_products['orders_id'].'</b></a>'; ?></td>
				
				<td align="right">$<?= number_format( $row_products['final_price'], 2 ); ?></td>
				<?php if($allow_access_to_profit == true){ ?>
				<td align="right"><?php if($gross_profit<=0){ echo '<span style="color:#FF0000;">'; } ?>$<?= $gross_profit;?></span></td>
				<td align="right"><?php echo $margin . '%';?></td>				
				<?php } ?>
				<td>&nbsp;</td>
			</tr>
			<?php
			$items_sold_cnt++;
			 } 
			 if($items_sold_cnt > 0) {		
			 ?>
			<tr style="background-color:#006666; color:#FFFFFF;">				
				<td colspan="1"  align="right"><strong>Total:</strong></td>
				<td align="right"><strong>$<?= number_format( $running_final_price, 2 ) ?></strong></td>				
				<?php if($allow_access_to_profit == true){ ?>
				<td align="right"><strong>$<?= number_format( $running_gross_profit, 2 ) ?></strong></td>
				<td align="right"><strong><?= tep_round((($running_gross_profit*100)/$running_final_price) , 0). '%'; ?></strong></td>
				<?php } ?>
				<td><?php echo tep_image(DIR_WS_IMAGES.'close_window.jpg','close',15,13,' onclick="javascript:close_div(\'show_info_popup_'.$d.'\',\'none\');"'); ?></td>
			</tr>
			<?php } ?>
            </table>
		<?php
	}
	echo $HTTP_FILES_VARS['text'];
	exit;
}
  // default view (daily)
  $sales_report_default_view = 2;
  // report views (1: hourly 2: daily 3: weekly 4: monthly 5: yearly)
  $sales_report_view = $sales_report_default_view;
  if ( ($HTTP_GET_VARS['report']) && (tep_not_null($HTTP_GET_VARS['report'])) ) {
    $sales_report_view = $HTTP_GET_VARS['report'];
  }
  if ($sales_report_view > 5) {
    $sales_report_view = $sales_report_default_view;
  }

  if ($sales_report_view == 2) {
    $report = 2;
  }

  if ($report == 1) {
    $summary1 = AVERAGE_HOURLY_TOTAL;
    $summary2 = TODAY_TO_DATE;
    $report_desc = REPORT_TYPE_HOURLY;
  } else if ($report == 2) {
    $summary1 = AVERAGE_DAILY_TOTAL;
    $summary2 = WEEK_TO_DATE;
    $report_desc = REPORT_TYPE_DAILY;
  } else if ($report == 3) {
    $summary1 = AVERAGE_WEEKLY_TOTAL;
    $summary2 = MONTH_TO_DATE;
    $report_desc = REPORT_TYPE_WEEKLY;
  } else if ($report == 4) {
    $summary1 = AVERAGE_MONTHLY_TOTAL;
    $summary2 = YEAR_TO_DATE;
    $report_desc = REPORT_TYPE_MONTHLY;
  } else if ($report == 5) {
    $summary1 = AVERAGE_YEARLY_TOTAL;
    $summary2 = YEARLY_TOTAL;
    $report_desc = REPORT_TYPE_YEARLY;
  }

  // check start and end Date
  $startDate = "";
  if ( ($HTTP_GET_VARS['startDate']) && (tep_not_null($HTTP_GET_VARS['startDate'])) ) {
    $startDate = $HTTP_GET_VARS['startDate'];
  }
  $endDate = "";
  if ( ($HTTP_GET_VARS['endDate']) && (tep_not_null($HTTP_GET_VARS['endDate'])) ) {
    $endDate = $HTTP_GET_VARS['endDate'];
  }

  // check filters
  if (($HTTP_GET_VARS['filter']) && (tep_not_null($HTTP_GET_VARS['filter']))) {
    $sales_report_filter = $HTTP_GET_VARS['filter'];
    $sales_report_filter_link = "&filter=$sales_report_filter";
  }

  $referer = $_GET['referer'];
  
  require(DIR_WS_CLASSES . 'sales_report.php');
  $report = new sales_report($sales_report_view, $startDate, $endDate, $sales_report_filter, $referer);
  

  if (strlen($sales_report_filter) == 0) {
    $sales_report_filter = $report->filter;
    $sales_report_filter_link = "";
  }

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<SCRIPT LANGUAGE="JavaScript1.2" SRC="jsgraph/graph.js"></SCRIPT>
<script type="text/javascript" src="includes/javascript/categories.js"></script>

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_sales_report');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
			<!-- left_navigation //-->
			<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
			<!-- left_navigation_eof //-->
	        </table>
		</td>
<!-- body_text //-->
		<td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
				<tr>
					<td colspan=2>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td class="pageHeading"><?php echo $report_desc . ' ' . HEADING_TITLE; ?></td>
								<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			    <tr>
					<td colspan=2>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
<td align=right class="menuBoxHeading">
<form enctype="multipart/form-data" method="get" name="referer_from">
<?php
$ref_type_sql = tep_db_query('SELECT * FROM `customers_ref_type2` ORDER BY `sort_num` ASC, `customers_ref_type_id` ASC ');
$ref_type_rows = tep_db_fetch_array($ref_type_sql);
$values_array = array();
$values_array[] = array('id'=> '0' , 'text'=> 'All');
do{
	$values_array[] = array('id'=> $ref_type_rows['customers_ref_type_id'] , 'text'=> $ref_type_rows['customers_ref_type_name']);
}while($ref_type_rows = tep_db_fetch_array($ref_type_sql));
echo TABLE_HEADING_CUSTOMERS_FROM."&nbsp;".tep_draw_pull_down_menu('referer', $values_array, '', ' onChange="this.form.submit();" ')."&nbsp;";

$referer_get_str = '';
if(tep_not_null($referer)){
	$referer_get_str = '&referer='.$referer;
}
?>

</form>
</td>
<td align=right class="menuBoxHeading">
<?php
  echo TABLE_HEADING_TIME_CLASS."&nbsp;";
  echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, 'report=1'. $sales_report_filter_link.$referer_get_str, 'NONSSL') . '">' . REPORT_TYPE_HOURLY .'</a> | ';
  echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, 'report=2'. $sales_report_filter_link.$referer_get_str, 'NONSSL') . '">' . REPORT_TYPE_DAILY .'</a> | ';
  echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, 'report=3'. $sales_report_filter_link.$referer_get_str, 'NONSSL') . '">' . REPORT_TYPE_WEEKLY . '</a> | ';
  if($login_groups_id == '1' || $login_groups_id == '4'){
   echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, 'report=4&startDate='.mktime(0, 0, 0, date("m")+1, date("d"), date("Y")-2).'&endDate='.mktime(0, 0, 0, date("m"), date("d"), date("Y")). $sales_report_filter_link.$referer_get_str, 'NONSSL') . '">' . REPORT_TYPE_MONTHLY . '</a> | ';
  }else{
  echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, 'report=4' . $sales_report_filter_link.$referer_get_str, 'NONSSL') . '">' . REPORT_TYPE_MONTHLY . '</a> | ';
  }
   
  echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, 'report=5' . $sales_report_filter_link.$referer_get_str, 'NONSSL') . '">' . REPORT_TYPE_YEARLY . '</a>';
?>
</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign=top width=200 align=center>
<?php 
if ($sales_report_view > 1) {
if ($report->size > 1) {
  echo tep_draw_separator('pixel_trans.gif', 250,10).'<br>';
  $last_value = 0;
  $order_cnt = 0;
  $sum = 0;
  $signup = 0;
  for ($i = 0; $i < $report->size; $i++) {
    if ($last_value != 0) {
      $percent = 100 * $report->info[$i]['sum'] / $last_value - 100;
    } else {
      $percent = "0";
    }
    $sum += $report->info[$i]['sum'];
    $avg += $report->info[$i]['avg'];
    $order_cnt += $report->info[$i]['count'];
    $last_value = $report->info[$i]['sum'];
	$signup += $report->info[$i]['signup'];
}
}
//define variables for graph
if ($report->size > 1) {
$scale_x = ($sum / $report->size) / 4;
$scale_y = $scale_x + 50;
$scale_z = $scale_y / 100;
$scale = round($scale_z) * 100;

$scale1_x = ($signup / $report->size) / 4;
$scale1_y = $scale1_x + 5;
$scale1_z = $scale1_y / 10;
$scale1 = round($scale1_z) * 10;
?>
<SCRIPT LANGUAGE="JavaScript1.2">
var g = new Graph(<?php 
if ($report->size > 2){
echo '200';
} else {
echo ($report->size * 50);} ?>,100,true);
g.addRow(<?php
  for ($i = 0; $i < $report->size; $i++) {
if ($report->info[$i]['sum'] == ""){
	echo '0';
	}else{
	echo $report->info[$i]['sum'] - $report->info[$i]['avg'];
}
	  if (($i+1) < $report->size) {
		echo ',';
	  }
	}
	echo ');';
	echo '
	';
?>
<?php  if ($sales_report_view == 2){
echo 'g.addRow(';
  for ($i = 0; $i < $report->size; $i++) {
if ($report->info[$i]['sum'] == ""){
	echo '0';
	}else{
	echo $report->info[$i]['avg'];
}
	  if (($i+1) < $report->size) {
		echo ',';
	  }
	}
	echo ');';
	echo '
	';
echo 'g.setLegend("daily total","avg. order");';
	echo '
	';
}?>
<?php
 echo 'g.setXScaleValues("';
  for ($i = 0; $i < $report->size; $i++) {
 if (($sales_report_view == 5) && ($report->size > 5)) {
 echo substr($report->info[$i]['text'] . $date_text[$i], 0,1);
  }else{
  if ($sales_report_view == 4){
 	if($i==0){
	  $first_year = substr($report->info[$i]['text'] . $date_text[$i], 3);
	}
  	$view_year = substr($report->info[$i]['text'] . $date_text[$i], 3);
  	if($first_year == $view_year){
		if($i==0 && ($login_groups_id == '1')){
	  		echo $report->info[$i]['text'] . $date_text[$i];
		}else{
	  		echo substr($report->info[$i]['text'] . $date_text[$i], 0,3);
		}
  	}else{
	echo $report->info[$i]['text'] . $date_text[$i];
	$first_year = substr($report->info[$i]['text'] . $date_text[$i], 3);
  	}
	  
	  
	 //echo substr($report->info[$i]['text'] . $date_text[$i], 0,3);
  }else{
   if ($report->size > 5) {
 echo substr($report->info[$i]['text'] . $date_text[$i], 3,2);
 } else {
 echo substr($report->info[$i]['text'] . $date_text[$i], 0,5);
 }
}
}
   if (($i+1) < $report->size) {
  echo '","';
   }
 }
 echo '");';
?>
g.scale = <?php echo $scale; ?>;
g.build();

var g = new Graph(200,100,true);
	<?php
	  echo 'g.addRow(';
	  for ($i = 0; $i < $report->size; $i++) {
		echo $report->info[$i]['signup'];
		  if (($i+1) < $report->size) {
			echo ',';
		  }
	  }
	  echo ');';
	?>
	g.setLegend("customer signup");
	
	<?php
	 echo 'g.setXScaleValues("';
	  for ($i = 0; $i < $report->size; $i++) {
	 if (($sales_report_view == 5) && ($report->size > 5)) {
	 echo substr($report->info[$i]['text'] . $date_text[$i], 0,1);
	  }else{
	  if ($sales_report_view == 4){
	  if($i==0){
	  $first_year = substr($report->info[$i]['text'] . $date_text[$i], 3);
	  }
	  $view_year = substr($report->info[$i]['text'] . $date_text[$i], 3);
	  if($first_year == $view_year){
		if($i==0 && ($login_groups_id == '1' || $login_groups_id == '11')){
		  echo $report->info[$i]['text'] . $date_text[$i];
		}else{
		  echo substr($report->info[$i]['text'] . $date_text[$i], 0,3);
		}
	  }else{
		echo $report->info[$i]['text'] . $date_text[$i];
		$first_year = substr($report->info[$i]['text'] . $date_text[$i], 3);
	  }
	  
	  
	 //echo substr($report->info[$i]['text'] . $date_text[$i], 0,3);
	  }else{
	   if ($report->size > 5) {
	 echo substr($report->info[$i]['text'] . $date_text[$i], 3,2);
	 } else {
	 echo substr($report->info[$i]['text'] . $date_text[$i], 0,5);
	 }
	}
	}
	   if (($i+1) < $report->size) {
	  echo '","';
	   }
	 }
	 echo '");';
	?>
	g.scale = <?php echo $scale1; ?>;
	g.build();

</SCRIPT>
<?php
}
}
?>
<br>
 <table align ="center" border="2"  color="black" width="200" cellspacing="0" cellpadding="2">
	 <tr class="dataTableRow">
	  <td class="dataTableContent" align="left"><?php echo '<b>'. FILTER_STATUS .'</b>' ?></td>
	  <td class="dataTableContent" align="left"><?php echo '<b>'. FILTER_VALUE .'</b>' ?></td>
	</tr>
<?php
if (($sales_report_filter) == 0) {
	for ($i = 0; $i < $report->status_available_size; $i++) {
	  if(substr($report->status_available[$i]['value'],0,9) == 'Cancelled'){
	  $sales_report_filter .= "1";
	  }else{
	  $sales_report_filter .= "0";
	  }
	}
}

for ($i = 0; $i < $report->status_available_size; $i++) {
?>
	<tr>
	  <td class="dataTableContent" align="left"><?php echo $report->status_available[$i]['value'] ?></a></td>
<?php
if (substr($sales_report_filter,$i,1) ==  "0") {
$tmp = substr($sales_report_filter, 0, $i) . "1" . substr($sales_report_filter, $i+1, $report->status_available_size - ($i + 1));

$tmp = tep_href_link(FILENAME_STATS_SALES_REPORT, $report->filter_link . "&filter=". $tmp, 'NONSSL');
?>
<td class="dataTableContent" width="100%" align="left"><?php echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) ?>&nbsp;<a href="<?php echo $tmp; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) ?></a></td>
<?php
} else {
$tmp = substr($sales_report_filter, 0, $i) . "0" . substr($sales_report_filter, $i+1);
$tmp = tep_href_link(FILENAME_STATS_SALES_REPORT, $report->filter_link . "&filter=". $tmp, 'NONSSL');
?>
<td class="dataTableContent" width="100%" align="left"><a href="<?php echo $tmp; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) ?></a>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) ?></td>
<?php
}
?>
	</tr>
<?php
}
?>
 </table>
					</td>
			        <td width=100% valign=top>
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr>
								<td valign="top">
									<table border="0" width="100%" cellspacing="0" cellpadding="2">
										<tr class="dataTableHeadingRow">
											<td class="dataTableHeadingContent" width="15%"></td>
											<td class="dataTableHeadingContent" width="15%" align=center><?php echo TABLE_HEADING_SIGNUPS ; ?></td>
											<td class="dataTableHeadingContent" width="10%" align=center><?php echo TABLE_HEADING_ORDERS; ?></td>
											<td class="dataTableHeadingContent" width="10%" align=right><?php echo TABLE_HEADING_CONV_PER_ORDER; ?></td>
											<td class="dataTableHeadingContent" width="10%" align=right><?php echo TABLE_HEADING_CONVERSION; ?></td>
											<?php if($login_groups_id == '1') { ?>	
											<td class="dataTableHeadingContent" width="10%" align=right><?php echo TABLE_HEADING_TOTAL_COST; ?></td>
											<td class="dataTableHeadingContent" width="10%" align=right><?php echo TABLE_HEADING_GROSS_PROFIT; ?></td>
											<td class="dataTableHeadingContent" width="10%" align=right><?php echo TABLE_HEADING_GROSS_PROFIT_PERCENTAGE; ?></td>
											<?php }else if($login_groups_id == '11'){
												?>	
												<td class="dataTableHeadingContent" width="10%" align=right><?php echo TABLE_HEADING_TOTAL_COST; ?></td>
												<?php											
												}
											    ?>
											<td class="dataTableHeadingContent" nowrap align=right><?php echo TABLE_HEADING_VARIANCE; ?></td>
										</tr>
<?php

  $last_value = 0;
  $sum = 0;
  for ($i = 0; $i < $report->size; $i++) {
    if ($last_value != 0) {
      $percent = 100 * $report->info[$i]['sum'] / $last_value - 100;
    } else {
      $percent = "0";
    }
    $sum += $report->info[$i]['sum'];
    $avg += $report->info[$i]['avg'];
    $last_value = $report->info[$i]['sum'];
	//echo substr($report->info[$i]['text'],0,3);
?>
										<tr class="dataTableRow" id="<?php echo substr($report->info[$i]['text'],0,3); ?>" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'; return comparerowover(this.id);" onMouseOut="this.className='dataTableRow'; return comparerowout(this.id);">
							                <td class="dataTableContent">
<?php
    if (strlen($report->info[$i]['link']) > 0 ) {
      echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, $report->info[$i]['link'], 'NONSSL') . '">';
    }
    echo $report->info[$i]['text'] . $date_text[$i];
    if (strlen($report->info[$i]['link']) > 0 ) {
      echo '</a>';
    }
?></td>
											<td class="dataTableContent" align=center><?php echo $report->info[$i]['signup']?></td>
											<td class="dataTableContent" align=center>
											<?php 											
											if(($sales_report_view == 2 || $sales_report_view == 3) && $report->info[$i]['count'] > 0 ){
											echo tep_draw_form('views_'.$report->info[$i]['text'], FILENAME_STATS_SALES_REPORT,'', 'get', 'id="views_'.$report->info[$i]['text'].'"'); ?><?php echo '<a href="javascript:close_div(\'show_info_popup_'.$report->info[$i]['text'].'\',\'\');" onClick=" sendFormData(\'views_'.$report->info[$i]['text'].'\', \''.tep_href_link(FILENAME_STATS_SALES_REPORT, tep_get_all_get_params(array()).'filters='.$report->info['filter'].'&dedate='.$report->info[$i]['dedate'].'&active=true&text='.$report->info[$i]['ddate']).'\', \'show_info_popup_'.$report->info[$i]['text'].'\',\'true\')" class="col_a1"><b>'.$report->info[$i]['count'].'</b></a>'?>&nbsp;</td><?php echo tep_draw_input_field('ajaxlast','ajaxCalled','','','hidden'); echo "</form>"; } else { echo $report->info[$i]['count']; }
											?>
											</td>
											<td class="dataTableContent"align=right><?php echo $currencies->format($report->info[$i]['avg'])?></td>
											<td class="dataTableContent" align=right><?php echo $currencies->format($report->info[$i]['sum'])?></td>
											<?php if($login_groups_id == '1') { ?>	
											<td class="dataTableContent" align=right><?php echo $currencies->format($report->info[$i]['cost'])?></td>
											<?php
											$gross_price = $report->info[$i]['sum'];
											$gross_cost = $report->info[$i]['cost'];
											$gross_profit = $currencies->format(($gross_price - $gross_cost));
											if($gross_price != 0){	
												$margin = tep_round((((($gross_price)-($gross_cost))/($gross_price))*100), 0);
											}else{
												$margin = 0;
											}
											?>
											<td class="dataTableContent" align="right"><?php echo $gross_profit;?></td>
										  	<td class="dataTableContent" align="right"><?php echo $margin . '%';?></td>
											<?php } else if($login_groups_id == '11'){
												?><td class="dataTableContent" align=right><?php echo $currencies->format($report->info[$i]['cost'])?></td><?php
											}											
											?>
											<td class="dataTableContent" align=right>
<?php
    if ($percent == 0){
      echo "---";
    } else {
      echo number_format($percent,0) . "%";
    }
?>
</td>
										</tr>
										<tr class="dataTableRow">
			  	<td class="dataTableContent"></td>
			  	<td colspan="8" align="center" class="dataTableContent"><?php echo '<div id=\'show_info_popup_'.$report->info[$i]['text'].'\'></div>'?></td></tr>
<?php
 }
?>

<?php
  if (strlen($report->previous . " " . $report->next) > 1) {
?>
										<tr>
											<td width=100% colspan=<?php if($login_groups_id == '1'){ echo '6'; }else{ echo '5';}?>	>
												<table width=100%>
													<tr>
														<td align=left>
<?php
    if (strlen($report->previous) > 0) {
      echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, $report->previous, 'NONSSL') . '">&lt;&lt;&nbsp;Previous</a>';
    }
?>
														</td>
										                <td align=right>
<?php
    if (strlen($report->next) > 0) {
      echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT, $report->next, 'NONSSL') . '">Next&nbsp;&gt;&gt;</a>';
      echo "";
    }
?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
<?php
  }
?>

                  </table>
                  <p>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php if ($order_cnt != 0){
?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent" width=100% align=right><?php echo '<b>'. AVERAGE_ORDER . ' </b>' ?></td>
                      <td class="dataTableContent" align=right><?php echo $currencies->format($sum / $order_cnt) ?></td>
                    </tr>
<?php } 
  if ($report->size != 0) {
?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent" width=100% align=right><?php echo '<b>'. $summary1 . ' </b>' ?></td>
                      <td class="dataTableContent" align=right><?php echo $currencies->format($sum / $report->size) ?></td>
                    </tr>
<?php } ?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent" width=100% align=right><?php echo '<b>'. $summary2 . ' </b>' ?></td>
                      <td class="dataTableContent" align=right><?php echo $currencies->format($sum) ?></td>
                    </tr>
                  </table>
                  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                 
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>

<script type="text/javascript">
function comparerowover(id){
	if(document.getElementById(id).className != 'dataTableRowOver'){
	document.getElementById(id).className='dataTableRowSelectedYellow';
	}
}
function comparerowout(id){
document.getElementById(id).className='dataTableRow';
}
function close_div(divid,displaytype){
		document.getElementById(divid).style.display=displaytype;
	}
</script>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
