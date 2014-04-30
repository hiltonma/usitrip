<?php
//百度cpc分时统计模块
//默认值设置
if(!isset($_GET['report'])){
	$report = $_GET['report'] = 5;
}
if(tep_not_null($_GET['start_date'])){
	switch($report){
		case "5": $start_date = $_GET['start_date'] = date("Y-m-d", mktime(0,0,0, 1,1, date("Y",strtotime($_GET['start_date']) ) )); break;
		case "4": $start_date = $_GET['start_date'] = date("Y-m-d", mktime(0,0,0, date("m",strtotime($_GET['start_date']) ),1, date("Y",strtotime($_GET['start_date']) ) )); break;
		case "3": $start_date = $_GET['start_date'] = date("Y-m-d", strtotime($_GET['start_date'].' -'.date("w",strtotime($_GET['start_date'])).' day ' ) );
		break;
	}
}
if(tep_not_null($_GET['end_date'])){
	switch($report){
		case "5": $end_date = $_GET['end_date'] = date("Y-m-d", mktime(0,0,0, 13,0, date("Y",strtotime($_GET['end_date']) ) )); break;
		case "4": $end_date = $_GET['end_date'] = date("Y-m-d", mktime(0,0,0, (date("m",strtotime($_GET['end_date']) )+1),0, date("Y",strtotime($_GET['end_date']) ) )); break;
		case "3": 
		$add_day = 6 - date("w",strtotime($_GET['end_date']));
		$end_date = $_GET['end_date'] = date("Y-m-d", strtotime($_GET['end_date'].' +'.$add_day.' day ' ) );
		break;
	}
}


//取得客户来源数组
$ref_type_sql = tep_db_query('SELECT * FROM `customers_ref_type` ORDER BY `sort_num` ASC, `customers_ref_type_id` ASC ');
$ref_type_rows = tep_db_fetch_array($ref_type_sql);
$ref_type_array = array();
$ref_type_array[] = array('id'=> '0' , 'text'=> 'All');
do{
	$ref_type_array[] = array('id'=> $ref_type_rows['customers_ref_type_id'] , 'text'=> $ref_type_rows['customers_ref_type_name']);
}while($ref_type_rows = tep_db_fetch_array($ref_type_sql));
//取得时段数组
$time_mode = array();
$time_mode[] = array('id'=>1,'text'=>'小时');
$time_mode[] = array('id'=>2,'text'=>'日');
$time_mode[] = array('id'=>3,'text'=>'星期');
$time_mode[] = array('id'=>4,'text'=>'月');
$time_mode[] = array('id'=>5,'text'=>'年');

//根据时段取得$rows_array值
$rows_array = array();
switch($_GET['report']){
	case "1":
		for($i=0; $i<24; $i++){
			$v = $i;
			if($v<10){ $v = "0".$v;}
			$rows_array[$i] = array('id'=>$v,'text'=>$v.'点');
		}
		$sql_like_format = "____-__-__ %s:__:__";
	break;
	case "2":
		for($i=0; $i<31; $i++){
			$v = $i+1;
			if($v<10){ $v = "0".$v;}
			$rows_array[$i] = array('id'=>$v,'text'=>$v.'日');
		}
		$sql_like_format = "____-__-%s __:__:__";
	break;
	case "3"://week 
		for($i=0; $i<7; $i++){
			$v = $i;
			$p = array('0','1','2','3','4','5','6');
			$r = array('日','一','二','三','四','五','六');
			$rows_array[$i] = array('id'=>$v,'text'=>'星期'.str_replace($p,$r,$v));
		}
		//$sql_like_format = " WEEKDATE( )";
	break;
	case "4":
		for($i=0; $i<12; $i++){
			$v = $i+1;
			if($v<10){ $v = "0".$v;}
			$p = array('01','02','03','04','05','06','07','08','09','10','11','12');
			
			$r = array('一','二','三','四','五','六','七','八','九','十','十一','十二');
			$rows_array[$i] = array('id'=>$v,'text'=>str_replace($p,$r,$v).'月');
		}
		$sql_like_format = "____-%s-__ __:__:__";
	break;
	case "5":
		$min_year = 2006;
		$max_year = date('Y')+1;
		
		for($i=$min_year; $i<$max_year; $i++){
			$rows_array[] = array('id'=>$i,'text'=>$i.'年');
		}
		$sql_like_format = "%s-__-__ __:__:__";
	break;
}

//搜索过滤
$where_search = '';
$where_search_for_orders = '';
if((int)$_GET['referer']){
	$where_search .= ' AND c.customers_referer_type ="'.(int)$_GET['referer'].'" ';
	
	$customers_sql = tep_db_query('SELECT c.customers_id FROM '.TABLE_CUSTOMERS.' c WHERE c.customers_referer_type="'.(int)$_GET['referer'].'" ');
	while($customers_rows = tep_db_fetch_array($customers_sql)){
		$where_search_for_orders .= $customers_rows['customers_id'].',';
	}
	$where_search_for_orders = substr($where_search_for_orders,0,(strlen($where_search_for_orders)-1) );
	if($where_search_for_orders!=''){
		$where_search_for_orders = ' AND o.customers_id IN('.$where_search_for_orders.') ';
	}else{
		$where_search_for_orders = ' AND o.customers_id IN(0) ';
	}

}
if(tep_not_null($_GET['start_date'])){
	$where_search .= ' AND ci.customers_info_date_account_created >="'.$_GET['start_date'].' 00:00:00" ';
	$where_search_for_orders .= ' AND o.date_purchased >="'.$_GET['start_date'].' 00:00:00" ';
}
if(tep_not_null($_GET['end_date'])){
	$where_search .= ' AND ci.customers_info_date_account_created <="'.$_GET['end_date'].' 23:59:59" ';
	$where_search_for_orders .= ' AND o.date_purchased <="'.$_GET['end_date'].' 23:59:59" ';
}

$info = array();
for($i=0;$i<count($rows_array); $i++){
	
	//取得注册人数
	$like_signup_string = " and ci.customers_info_date_account_created Like '".str_replace('%s',$rows_array[$i]['id'],$sql_like_format)."' ";
	$like_orders_string = " and o.date_purchased Like '".str_replace('%s',$rows_array[$i]['id'],$sql_like_format)."' ";
	if($_GET['report']=="3"){
		$mysql_week = ($rows_array[$i]['id']-1);
		if($mysql_week<0){ $mysql_week = 6; }
		$like_signup_string = " and WeekDay(ci.customers_info_date_account_created) =  '".$mysql_week."' ";
		$like_orders_string = " and WeekDay(o.date_purchased) = '".$mysql_week."' ";
	}
	$report_signups_query = tep_db_query("select count(c.customers_id) as signup from ".TABLE_CUSTOMERS." c, ".TABLE_CUSTOMERS_INFO." ci where c.customers_id = ci.customers_info_id  ". $where_search. $like_signup_string);
	$report_signups = tep_db_fetch_array($report_signups_query);
	$info[$i]['signup'] = $report_signups['signup'];
	//取得订单数和订单总额
    $tmp_query = "SELECT sum(ot.value) as value, sum(o.order_cost) as costval, avg(ot.value) as avg, count(ot.value) as count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o WHERE ot.orders_id = o.orders_id and ot.class = 'ot_total' and o.orders_status !='6' ".$where_search_for_orders. $like_orders_string;
	$report_query = tep_db_query($tmp_query);
	$reports = tep_db_fetch_array($report_query);
	$info[$i]['cost'] = $reports['costval'];	//总成本
	$info[$i]['sum'] = $reports['value'];	//订单总额
	$info[$i]['avg'] = $reports['avg'];	//订单平均额
	$info[$i]['count'] = $reports['count'];	//订单总数
}

?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript"><!--
//var Start_Date = new ctlSpiffyCalendarBox("Start_Date", "referer_from", "start_date","btnDate3","<?php echo ($start_date); ?>",scBTNMODE_CUSTOMBLUE);
//var End_Date = new ctlSpiffyCalendarBox("End_Date", "referer_from", "end_date","btnDate4","<?php echo ($end_date); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<div id="spiffycalendar" class="text"></div>

<div class="menuBoxHeading">
	<?php
	if($_GET){
	?>
	<div class="selected-items">
		<div class="inner">
		<dl>
		<dt><?php echo db_to_html('您已选择：')?></dt>
		<?php
		foreach($_GET as $key => $val){
			if(tep_not_null($val) && $val!="0" && $key!="language"){
				switch($key){
					case "referer":
						$link_string = '客户来源：';
						for($i=0; $i<count($ref_type_array); $i++){
							if($ref_type_array[$i]['id']==$val){ $link_string .=$ref_type_array[$i]['text']; }
						}
					break;
					case "start_date":
						$link_string = '开始日期：'.$val;
					break;
					case "end_date":
						$link_string = '结束日期：'.$val;
					break;
					case "report":
						$link_string = '时段：';
						for($i=0; $i<count($time_mode); $i++){
							if($time_mode[$i]['id']==$val){ $link_string .=$time_mode[$i]['text']; }
						}
					break;
				}
		?>
		<dd><a href="<?= tep_href_link('cpc_report.php',tep_get_all_get_params(array('page', $key)))?>"><?php echo db_to_html($link_string);?><span class="close-icon"></span></a></dd>
		<?php
			}
		}
		?>

		</dl>
		</div>
	</div>
	<?php
	}
	?>
	<div  style="margin-bottom:10px;">
	<form enctype="application/x-www-form-urlencoded" method="get" name="referer_from">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align=right class="menuBoxHeading">
		<?php
		echo db_to_html('客户来源：')."&nbsp;".tep_draw_pull_down_menu('referer', $ref_type_array, '', ' onChange="this.form.submit();" ')."&nbsp;";
		
		$referer_get_str = '';
		if(tep_not_null($referer)){
			$referer_get_str = '&referer='.$referer;
		}
		?>
		
		</td>
		<td align=right class="menuBoxHeading">
		<?php
		echo db_to_html('&nbsp;开始日期：');
		?>
		<?php echo tep_draw_input_field('start_date', tep_get_date_disp($_GET['start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
		<script type="text/javascript">//Start_Date.writeControl(); Start_Date.dateFormat="yyyy-MM-dd";</script>
		<?php
		echo db_to_html('&nbsp;结束日期：');
		?>
		<?php echo tep_draw_input_field('end_date', tep_get_date_disp($_GET['end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
		<script type="text/javascript">//End_Date.writeControl(); End_Date.dateFormat="yyyy-MM-dd";</script>
		</td>
		<td align=right class="menuBoxHeading">
		<?php
		  echo db_to_html('&nbsp;时段：');
		  for($i=0; $i<count($time_mode); $i++){
		  	echo '<label>'.tep_draw_radio_field('report', $time_mode[$i]['id'], '','',' onClick="this.form.submit();" ').db_to_html($time_mode[$i]['text']).'</label>';
			if($i<(count($time_mode)-1)) echo " | ";
		  }
		?>
		</td>
		</tr>
	</table>
	</form>
	</div>

<?php if(count($rows_array)){?>
	<div>
	
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="left" valign="top"><table width="600" border="0" cellspacing="1">
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent" height="28">&nbsp;</td>
		<td align="center" class="dataTableHeadingContent">Customer Signup</td>
		<td align="center" class="dataTableHeadingContent"># of Orders</td>
		<td align="right" class="dataTableHeadingContent">Total Sales</td>
	</tr>
	<?php
	$signup_sum = 0;
	$of_orders_sum = 0;
	$total_sales_sum = 0;
	for($i=0; $i<count($rows_array); $i++){
	?>
	<tr id="data_<?= $i?>" class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'; return comparerowover(this.id)" onMouseOut="this.className='dataTableRow'; return comparerowout(this.id);">
		<td class="dataTableContent" height="25"><?php echo db_to_html($rows_array[$i]['text'])?></td>
		<td align="center" class="dataTableContent"><?php echo $info[$i]['signup']; $signup_sum +=$info[$i]['signup']?></td>
		<td align="center" class="dataTableContent"><?php echo $info[$i]['count']; $of_orders_sum +=$info[$i]['count']?></td>
		<td align="right" class="dataTableContent"><?php echo $currencies->format($info[$i]['sum']); $total_sales_sum +=$info[$i]['sum']?></td>
	</tr>
	<?php
	}
	?>
	<tr class="dataTableRow">
		<td class="dataTableContent" height="25"><b><?php echo db_to_html('合计')?></b></td>
		<td align="center" class="dataTableContent"><b><?php echo $signup_sum?></b></td>
		<td align="center" class="dataTableContent"><b><?php echo $of_orders_sum?></b></td>
		<td align="right" class="dataTableContent"><b><?php echo $currencies->format($total_sales_sum);?></b></td>
	</tr>
</table></td>
			<td width="20">&nbsp;</td>
			<td align="left" valign="middle">
				<?php
				$width = 30;
				if($report=='1'){
					$width = 20;
				}
				if($report=='2'){
					$width = 16;
				}
				?>
				<!--Customer Signup start-->
				<div style="margin-bottom:20px;">
				<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr>
				<?php
				for($i=0; $i<count($rows_array); $i++){
					$height = intval(($info[$i]['signup'] / (max(1,$signup_sum))) * 50 * count($rows_array));
					$height = max(1,$height);
				?>
				<td valign="BOTTOM" style="padding-right:2px;"><img height="<?=$height?>" width="<?= $width?>" alt="customer signup: 13, 27" src="images/0.gif" id="signup_<?= $i?>" class="RedBorder">
				</td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for($i=0; $i<count($rows_array); $i++){
				?>
				<td valign="BOTTOM" align="center"><font size="-3" face="Verdana,Arial,Helvetica"><i><?php echo db_to_html($rows_array[$i]['text'])?></i></font>
				</td>
				<?php
				}
				?>
				</tr>
				<tr>
					<td valign="BOTTOM" align="center" colspan="31">
					<table cellspacing="0" cellpadding="4" border="0"><tbody><tr><td><font size="-2" face="Verdana,Arial,Helvetica"><img hspace="3" border="1" src="images/0.gif">Customer Signup</font></td><td><font size="-2" face="Verdana,Arial,Helvetica"></font></td></tr></tbody></table>
					</td>
				</tr>
				
				</tbody>
				</table>
				</div>
				<!--Customer Signup end-->
				<!--# of Orders start-->
				<div>
				<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr>
				<?php
				for($i=0; $i<count($rows_array); $i++){
					$height = intval(($info[$i]['count'] / (max(1,$of_orders_sum))) * 50 * count($rows_array));
					$height = max(1,$height);
				?>
				<td valign="BOTTOM" style="padding-right:2px;"><img height="<?=$height?>" width="<?= $width?>" alt="customer signup: 13, 27" src="images/0.gif" id="of_orders_<?= $i?>" class="RedBorder">
				</td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for($i=0; $i<count($rows_array); $i++){
				?>
				<td valign="BOTTOM" align="center"><font size="-3" face="Verdana,Arial,Helvetica"><i><?php echo db_to_html($rows_array[$i]['text'])?></i></font>
				</td>
				<?php
				}
				?>
				</tr>
				<tr>
					<td valign="BOTTOM" align="center" colspan="31">
					<table cellspacing="0" cellpadding="4" border="0"><tbody><tr><td><font size="-2" face="Verdana,Arial,Helvetica"><img hspace="3" border="1" src="images/0.gif"># of Orders</font></td><td><font size="-2" face="Verdana,Arial,Helvetica"></font></td></tr></tbody></table>
					</td>
				</tr>
				
				</tbody>
				</table>
				</div>
				<!--# of Orders end-->
				<!--Total Sales start-->
				<div>
				<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr>
				<?php
				for($i=0; $i<count($rows_array); $i++){
					$height = intval(($info[$i]['sum'] / (max(1,$total_sales_sum))) * 50 * count($rows_array));
					$height = max(1,$height);
				?>
				<td valign="BOTTOM" style="padding-right:2px;"><img height="<?=$height?>" width="<?= $width?>" alt="customer signup: 13, 27" src="images/0.gif" id="total_sales_<?= $i?>" class="RedBorder">
				</td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for($i=0; $i<count($rows_array); $i++){
				?>
				<td valign="BOTTOM" align="center"><font size="-3" face="Verdana,Arial,Helvetica"><i><?php echo db_to_html($rows_array[$i]['text'])?></i></font>
				</td>
				<?php
				}
				?>
				</tr>
				<tr>
					<td valign="BOTTOM" align="center" colspan="31">
					<table cellspacing="0" cellpadding="4" border="0"><tbody><tr><td><font size="-2" face="Verdana,Arial,Helvetica"><img hspace="3" border="1" src="images/0.gif">Total Sales</font></td><td><font size="-2" face="Verdana,Arial,Helvetica"></font></td></tr></tbody></table>
					</td>
				</tr>
				
				</tbody>
				</table>
				</div>
				<!--Total Sales end-->
			</td>
		</tr>
	</table>
	</div>
<?php
}
?>

</div>

<script type="text/javascript">
function comparerowover(id){
	var subid = id.replace(/data_/,'');
	document.getElementById('signup_'+subid).className='GreenBorder';
	document.getElementById('of_orders_'+subid).className='GreenBorder';
	document.getElementById('total_sales_'+subid).className='GreenBorder';
}
function comparerowout(id){
	var subid = id.replace(/data_/,'');
	document.getElementById('signup_'+subid).className='RedBorder';
	document.getElementById('of_orders_'+subid).className='RedBorder';
	document.getElementById('total_sales_'+subid).className='RedBorder';
}
</script>
