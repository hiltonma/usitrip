<?php
require_once('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
$TimeAndLocaltion = ajax_to_general_string($_POST['TimeAndLocaltion']);
$QuFaDate = ajax_to_general_string($_POST['QuFaDate']);
$ProductsOptions = ajax_to_general_string($_POST['ProductsOptions']);
//选统计出日期和属性价格
$sql = tep_db_query('SELECT products_price FROM `products` WHERE `products_id` = "'.(int)$_POST['p_id'].'" LIMIT 1');
$row = tep_db_fetch_array($sql);
$old_products_price = $row['products_price'];
//属性价格
$option_products_price = 0;
for($i=0; $i<count($ProductsOptions_array); $i++){
	//+$
	if(preg_match('/([\-]*\$\d+[\.\d+]*)/',$ProductsOptions_array[$i],$m)){
		//echo $m[1];
		//exit;
		$tmp=str_replace('$','',$m[1]);
		$option_products_price += $tmp;
	}
}


$ProductsOptions_array = explode('<::>',$ProductsOptions);
$TimeAndLocaltion_array = explode('<::>',$TimeAndLocaltion);
//print_r($TimeAndLocaltion_array);

//所有可用的出发日期
$AllQuFaDate = ajax_to_general_string($_POST['AllQuFaDate']);
$AllQuFaDate_array = explode('<::>',$AllQuFaDate);
$only_all_Ymd_dates = array();
for($i=0; $i<count($AllQuFaDate_array); $i++){
	if(preg_match('/^(\d{4,4}-\d{2,2}-\d{2,2})/',$AllQuFaDate_array[$i],$M)){
		$only_all_Ymd_dates[] = $M[1];	//YYYY-MM-DD格式的可用日期组
	}
}
//选中的出发日期
$departure_date = preg_replace('/::.*/','',$QuFaDate);
//最大的出发日期
$max_end_date = preg_replace('/::.*/','',$AllQuFaDate_array[(count($AllQuFaDate_array)-1-1)]);

//拆分时间、地点和指定的日期区间

$date_time_price_address = array();
for($i=0; $i<count($TimeAndLocaltion_array); $i++){
	if(tep_not_null($TimeAndLocaltion_array[$i])){
		preg_match('/(.+)::::([^\(]+)\('.db_to_html('仅').'(.+)'.db_to_html('的').'(.+)\)/',$TimeAndLocaltion_array[$i],$m);
		//$m[1]时间，$m[2]地址，$m[3]时间段，$m[4]星期段
		//echo $m[0]."==".$m[1]."==".$m[2]."==".$m[3]."==".$m[4]."\n";
		if(!tep_not_null($m[0])||!tep_not_null($m[1])||!tep_not_null($m[2])||!tep_not_null($m[3])||!tep_not_null($m[4])){
			die("TimeAndAddressFormatError");	//格式不对时返回该错误
		}
		
		$tmp_dates = explode('-',$m[3]);
		$start_date = str_replace('/','-',$tmp_dates[0]);
		$start_date = preg_replace('/-(\d)-/','-0$1-',$start_date);
		$start_date = preg_replace('/-(\d)$/','-0$1',$start_date);
		$end_date = str_replace('/','-',$tmp_dates[1]);
		$end_date = preg_replace('/-(\d)-/','-0$1-',$end_date);
		$end_date = preg_replace('/-(\d)$/','-0$1',$end_date);
		//echo $end_date."\n";
		if($end_date>=$departure_date){	//只选择>=出发日期的值
			$start_date_num = strtotime($start_date);
			$end_date_num = strtotime($end_date);
			do { 
				$date_week = explode(',',date('Y-m-d,D', $start_date_num));
				$date = $date_week[0];
				//日期必须在出团日期的范围之内 start
				if(in_array($date, $only_all_Ymd_dates)){
					$week = en_to_china_weeks($date_week[1]);
					$week_array = explode('/',$m[4]);
					if($date>=$departure_date && (in_array($week, $week_array) || !tep_not_null($m[4]))){
						//echo $m[4]."==>".$week."<br>";
						
						$products_price = $old_products_price+$option_products_price;
						//日期价格
						for($n=0; $n<count($AllQuFaDate_array); $n++){
							if(tep_not_null($AllQuFaDate_array[$n]) && preg_match('/^'.$date.'::/',$AllQuFaDate_array[$n] )){
								//2010-08-29::( ## $10.00)!!!64781
								//2010-09-01::(-## $5.00)!!!65391
								preg_match('/::\((.+)\)!!!/',$AllQuFaDate_array[$n],$mm);
								$date_price=str_replace(array('#','$',' '),'',$mm[1]);
								if(tep_not_null($date_price)){
									//echo $date_price.PHP_EOL;
									$products_price += $date_price;
								}
								break;
							}
						}
						$date_time_price_address[str_replace('-','',$date)][] = array('all'=>$m[0],'date'=>$date,'week'=>$week,'time'=>$m[1],'price'=>$products_price,'address'=>$m[2]);
						
					}
				}
				//日期必须在出团日期的范围之内 end
				
			} while (($start_date_num += 86400) <= $end_date_num && $start_date_num<=strtotime($max_end_date));

		}
	}
}
ksort($date_time_price_address);//根据日期键名排序
//print_r($date_time_price_address);
//exit;

$h3_title = "请选择演出时间";
$onClick = " closeDiv('timePop') ";
$onclike_and_ondblclick = ' onclick="selected_radio(this); onclick_set_p_class(this);" ondblclick="ConfirmDeparturelocation();" ';
$page_split_exc = "";

if($_GET['parameter']=="only_show"){
//只显示不操作的表格
	$h3_title = "演出时间";
	$onClick = " DivSetVisible() ";
	$onclike_and_ondblclick = "";
	$page_split_exc = "OnlyShow";
}
?>
	<?php if($page_split_exc=='OnlyShow'){?>
	<h6>
		<b><?= db_to_html($h3_title);?></b>
		<span onClick="<?= $onClick?>"><img src="image/popup_close.jpg"  /></span>
	</h6>
	<?php
		}
		?>
	<table cellpadding="0" cellspacing="0" class="timePopConTable" id="date_address_list">
		<thead>
			<tr><td colspan="2">
			<select id="CanUseMonth" name="CanUseMonth" onchange="GetMonthList<?= $page_split_exc;?>(this.value)">
				<?php 
				//列出能用的所有月份
				$_old_month = "";
				foreach($date_time_price_address as $key => $val){
					$_month = substr($key,0,6);
					if($_month!=$_old_month){
						$_old_month = $_month;
						
						echo '<option value="'.$_month.'">'.substr($_month,0,4).db_to_html("年").preg_replace('/^0/','',substr($_month,-2)).db_to_html("月").'</option>';
					}
				}
				?>
				
			  </select>
			
			</td></tr>
		</thead>
		<tr>
			<td><span class="th timeL"><?= db_to_html("日期")?></span></td>
			<td><span class="th timeS"><?= db_to_html("时间")?></span><span class="th price"><?= db_to_html("价格")?></span><span class="th place"><?= db_to_html("地点")?></span></td>
		</tr>
		<?php
		  $start_month = "";
		  foreach($date_time_price_address as $key => $val){
			if(!tep_not_null($start_month)){
				$start_month = substr($key,0,6);
			}
			$tr_style = "";
			if(tep_not_null($start_month) && substr($key,0,6)!=$start_month){
				$tr_style = "display:none;";
			}
		  ?>
		<tr id="<?= $key?>" style="<?= $tr_style?>">
			<td class="tdLeft"><?php echo $val[0]['date'].' '.$val[0]['week']?><!--9/1/2010 周三--></td>
			<td>
			<?php
			//根据时间值对$val的值重新排序
			for($i=0; $i<count($val); $i++){
			?>
			  <p <?= $onclike_and_ondblclick?> onmouseover="jQuery(this).addClass('pHover');" onmouseout="jQuery(this).removeClass('pHover');" >
				<span class="timeS">
				<?php if(tep_not_null($onclike_and_ondblclick)){?>
				<input name="SelTimeRadio" type="radio" title="<?= $val[0]['date'].' '.$val[0]['week'].' '.$val[$i]['time'].' '.$val[$i]['address'];?>" value="<?= $val[$i]['all']?>" />
				<?php
				}
				?>
				<em><?= $val[$i]['time']?></em></span><span class="price">$<?= $val[$i]['price']?></span><span class="place"><?= $val[$i]['address']?></span>
			  </p>
			<?php
			}
			?>
			  
			  
			</td>
		</tr>
		<?php
		  }
		  ?>
		  
	</table>
	<div>
		<a href="javascript:void(0);" onclick="GetPreviousDate<?= $page_split_exc;?>()" id="chooseTimePopPreButton" class="pagePre" title="<?= db_to_html("上个月");?>"><?= db_to_html("上个月");?></a>
		<a href="javascript:void(0);" onclick="GetNextDate<?= $page_split_exc;?>()" id="chooseTimePopNextButton" class="pageNext" title="<?= db_to_html("下个月");?>"><?= db_to_html("下个月");?></a>
	</div>
	<?php if(!tep_not_null($_GET['parameter'])){?>
	<div class="submit">
		<a href="javascript:void(0);" onclick="ConfirmDeparturelocation()" title="<?= db_to_html("提示：快速双击单选按钮即可选择演出时间");?>" class="btn btnOrange"><button type="button"><?= db_to_html("确 定");?></button></a>
	</div>
	<?php
		}
		?>
