<?php
/*
	$calendar_year = intval(date('Y',time()));
	$calendar_month = intval(date('m',time()));
	$mode = 'auto';
	if(isset($HTTP_GET_VARS['reqdate'])&& is_numeric($HTTP_GET_VARS['reqdate'])){
		$req_date_raw = trim($HTTP_GET_VARS['reqdate']);
		if(strlen($req_date_raw) == 6){
			$year_raw = intval(substr($req_date_raw,0,4));
			$month_raw = intval(substr($req_date_raw,4,2));
			//echo $year_raw."-".$month_raw."<br><br>";
			if($year_raw > 2010 && $year_raw<2030 && $month_raw >=1 && $month_raw<=12){
				$calendar_year =  $year_raw;
				$calendar_month =  $month_raw;
				$mode = 'select';
			}
		}
	}
	echo(int)$HTTP_GET_VARS['products_id'];
	$reqdate = $calendar_year.'-'.$calendar_month;
	$q = tep_db_query("SELECT * FROM products_remaining_seats where products_id = '".(int)$HTTP_GET_VARS['products_id']."'AND departure_date LIKE '$reqdate%' ");
	$total = tep_db_num_rows($q);
	if($total == 0 && $mode == 'auto'){
		//如果本月没有数据则查询次月
		
		$q = tep_db_query("SELECT * FROM products_remaining_seats where products_id = '".(int)$HTTP_GET_VARS['products_id']."'AND departure_date LIKE '$reqdate%' ");
	}
	
	$week_start_day = 2 ;//周一是每周开始 周日 0 周1 1 ....周6 6
	$month_first_day = mktime (0, 0, 0, $calendar_month, 1, $calendar_year);
	$month_first_day_week = date('w',$month_first_day) + 1;
	$month_last_day = mktime (0, 0, 0, $calendar_month+1, 0, $calendar_year);
	$month_last_day_week = date('w',$month_last_day) + 1;
	
	$offset_first_day = $month_first_day_week - $week_start_day;
	
	if($offset_first_day > 0)
		 $calendar_first_day = $month_first_day - $offset_first_day * 86400;
	else if($offset_day < 0) 
		$calendar_first_day = $month_first_day -  86400*7 - 86400*$offset_first_day;
	else 
		$calendar_first_day = $month_first_day;
		
*/
function parse_irregular_date($str){
	$date_parts = explode('-',$str);
	$date_parts_len = count($date_parts);
	$current_year = date('Y',time());
	if(($date_parts_len == 3 && trim($date_parts[2]) =='')||$date_parts_len == 2){
		$date_parts[2] = $current_year;
	}
	return mktime(0,0,0,intval($date_parts[0]) ,intval($date_parts[1]),intval($date_parts[2]));
}

if($product_info['products_stock_status']=='0' || count($product_info['operate'])<1){
		// echo '<div class="route"><div class="boxSeat" id="ajax_msg">  <h3>'.db_to_html('该团已卖完').'</h3>';
		 $show_yellow_table_notes = false;
}

if($show_yellow_table_notes==true){		

	//Howard added 110815-1_黄石团座位表团购日期优化{
	$group_buy_dates = array();
	if($GroupBuyProductId = getGroupBuyProductIdFromRelated((int)$products_id)){
		$group_buy_avaliabledates = $mcache->product_departure_date($GroupBuyProductId);
		$group_buy_avaliabledates = remove_soldout_dates($GroupBuyProductId, (array)$group_buy_avaliabledates);
		array_multisort($group_buy_avaliabledates,SORT_ASC);
		foreach((array)$group_buy_avaliabledates as $val){
			$group_buy_dates[]=substr($val,0,10);
		}
	}
	//}
	
	//print_r(get_avaliabledate($products_id));
	//$sql = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_REG_IRREG_DATE.' WHERE ');
	$regu_irregular = regu_irregular_section_detail($products_id);
	$needCheck = array();
	$intNow = intval(date('Ymd',time()));
	$arrNow = getdate(time());
	//$intNow = 20110601;
	
	foreach($regu_irregular as $row){
		if($row!=""){
			$row['operate_start_date_fix'] = parse_irregular_date($row['operate_start_date']);
			$row['operate_end_date_fix'] = parse_irregular_date($row['operate_end_date']);			
			$intStart = intval(date('Ymd',$row['operate_start_date_fix']));
			$intEnd = intval(date('Ymd',$row['operate_end_date_fix']));
			$row['operate_start_date_int'] = $intStart ;
			$row['operate_end_date_int'] = $intEnd ;	
			//if($intNow >= $intStart && $intNow <= $intEnd){
			if($intNow <= $intEnd){
				$operator_query = tep_db_query("select products_start_day from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$products_id."  and  operate_start_date='".$row['operate_start_date']."' and  operate_end_date='".$row['operate_end_date']."'  order by products_start_day");
				$products_start_day =array();
				while($row1 = tep_db_fetch_array($operator_query)){
					$products_start_day[] = $row1['products_start_day'];
				}
				$row['products_start_day'] = $products_start_day;
				$needCheck[] = $row;
			}
		}
	}
	//为 $departure_date_prs 加上本月 已经过期的出发时间	
	$new_departure_date_prs = array();
	$current_month_days = intval(date('t',time()));
	$current_yearmonth = intval(date('Ym',time()));
	
	$current_day = intval(date('j',time()));
	for($i = 1;$i < $current_month_days ;$i++){
		$i_day = mktime(0,0,0,$arrNow['mon'],$i,$arrNow['year']);
		$i_day_int = intval(date('Ymd',$i_day));
		$i_week = intval(date('w' , $i_day))+1;
		foreach($needCheck as $nc){				
			if(in_array($i_week,$nc['products_start_day']) && ( $i_day_int >=$nc['operate_start_date_int'] && $i_day_int <=$nc['operate_end_date_int'] )){
				$new_dep_day = date("Y-m-d" , $i_day);
				if(!in_array($new_dep_day,$departure_date_prs)){
					$new_departure_date_prs[]  =$new_dep_day;
				}
			}
		}
	}
	//修正出发时间
	$added_departure_date_prs = array();
	foreach($needCheck as $nc){	
		$daysecond = 3600*24 ;
		for($i = $nc['operate_start_date_fix'] ; $i <= $nc['operate_end_date_fix'];$i=$i+ $daysecond){
				$i_week = intval(date('w' , $i))+1;
				$new_dep_day = date("Y-m-d" , $i);
				$new_dep_day_int = intval(date("Ym" , $i));
				if(in_array($i_week,$nc['products_start_day'])&& !in_array($new_dep_day ,$new_departure_date_prs)){
					if($new_dep_day_int >= $current_yearmonth)	$added_departure_date_prs[] = $new_dep_day ;
				}
		}
	}
	//修正出发时间结束...
	

	//print_r($added_departure_date_prs);
	//获取该团已设置为已售完的日期 fix 判断售完日期在本月之前则不显示了
	$sql = tep_db_query('SELECT products_soldout_date FROM `products_soldout_dates` WHERE products_id = '.intval($product_info['products_id']));
	$soldout =array() ;
	
	while($row = tep_db_fetch_array($sql)){
		/*$row_yearmonth = intval(date('Ym' ,strtotime($row['products_soldout_date']." 0:0:0")));
		if( $row_yearmonth >= $current_yearmonth){
			$soldout[] = $row['products_soldout_date'];
		}*/
		$soldout[] = $row['products_soldout_date'];
	}	
	//获取该团已设置为已售完的日期 结束
	$new_departure_date_prs = array_merge((array)$new_departure_date_prs,(array)$soldout,(array)$departure_date_prs,(array)$added_departure_date_prs );	 //将售完的团也加入列表
	sort($new_departure_date_prs);	
	$new_ajax_array = implode('|',$new_departure_date_prs);
	//print_r($departure_date_prs);
	//print_r($regu_irregular);
	//print_r($new_departure_date_prs);
	//$departure_date_prs 
	//echo ($products_id);
	//检查本月 是否全部已经售完 - 售完就转向下个月
	
?>
   <div class="route" style="display:none">
   <div class="boxSeat" id="ajax_msg">
     <h3><?php echo db_to_html('本团剩余座位情况:');?><em><?php echo db_to_html('(黄色选中为出团日期)');?></em></h3>

      <table cellpadding="0">
      <?php
      $arr = getdate();//获取当前日期
      $nowint = intval(date('Ymd',time()));
	  $target_month = time();
	  
      //$arr = getdate(strtotime("2011-6-1 0:0:0"));
      //查询本团座位情况的最后更新时间
      $qry_remaining_seats_lastupdate = "SELECT update_date FROM  `products_remaining_seats` where products_id = '".(int)$HTTP_GET_VARS['products_id']."' ORDER BY `update_date`  DESC LIMIT 1 ";
      $res_remaining_seats_lastupdate = tep_db_query($qry_remaining_seats_lastupdate);
      $row_remaining_seats_lastupdate = tep_db_fetch_array($res_remaining_seats_lastupdate);
      $arr_lastupdate = getdate(strtotime($row_remaining_seats_lastupdate['update_date']));
      //获得月份天数
      $daynum_onemonth = date('t',$target_month);
      //echo $daynum_onemonth;
      //查询该月的座位状况
      //$print_array[]=''; 
      for($i=0;$i<=$daynum_onemonth;$i++){
      	$print_array[$i]='';
      }

      $str_thismonth = date("Y-m",$target_month);
     // $str_thismonth  = '2011-06';
      //echo $str_thismonth;
       for($i=1;$i<=$daynum_onemonth;$i++){
      	$print_array[$i]=date('Y-m-d',strtotime($str_thismonth."-".$i));
      }
      $print_array_bk = $print_array;
	/*
       echo '<pre>';
      print_r($print_array_bk);
     echo '</pre>';*/

       
      //echo end($departure_date_prs);
      //$end_date = end($departure_date_prs);

      
     // echo $ajax_array;
       //echo '<pre>';
      // print_r($departure_date_prs);
      //echo '</pre>';
      $ajax_time = mktime();
      //if($departure_date_prs!=null){
     //print_r($departure_date_prs);	
      $lastday_onemonth = date('t',strtotime(end($new_departure_date_prs)));
	  
      //echo $lastday_onemonth;
      $str_last_day = date("Y-m",strtotime(end($new_departure_date_prs)));
	
      //echo $str_last_day;
      $str_last_day = $str_last_day.'-'.$lastday_onemonth.' '.'23:59:59';
      //echo $str_last_day;
      $products_last_date = strtotime($str_last_day);
      $ajax_time_last = date("Y-m-d",$ajax_time);
      
	  $ajax_time_next = strtotime("$ajax_time_last+1 month");
	  
	  $str_first_day = date("Y-m",strtotime($new_departure_date_prs[0]));
      $str_first_day = $str_first_day.'-'.'01'.' '.'00:00:00';
      // echo $str_first_day;
      $products_first_date = strtotime($str_first_day);
      //echo (int)$HTTP_GET_VARS['products_id'];
      //echo $str_thismonth;
      $qry_remaining_seats_details = "SELECT * FROM products_remaining_seats where products_id = '".(int)$HTTP_GET_VARS['products_id']."'AND departure_date LIKE '$str_thismonth%' ";
      //echo $qry_remaining_seats_details;
      $res_remaining_seats_details = tep_db_query($qry_remaining_seats_details);
      while($row_remaining_seats_details = tep_db_fetch_array($res_remaining_seats_details)){
      	    $print_arr = getdate(strtotime($row_remaining_seats_details['departure_date']));
      	    if($print_arr[mday]>$arr[mday]){
      	    	  if($row_remaining_seats_details['remaining_seats_num']>'0'){
      	              $print_array[$print_arr[mday]] = $row_remaining_seats_details['remaining_seats_num'];
      	    	  }else{
      	    	  	  $print_array[$print_arr[mday]] ='0';
      	    	  }
      	    }
      }   
      for($i=1;$i<=$daynum_onemonth;$i++){
       	  $print_week[$i] = get_chinese_week(date('l',strtotime($str_thismonth."-".$i)));
      }
      $print_week_len = count($print_week);
      $print_array_len = count($print_array);
      ?>

      <tr><td class="titleBar"><span><?php echo db_to_html('年月')?></span><span class="years">

      <a href="javascript:;"  alt="<?php echo db_to_html('上一个月');?>" title="<?php echo db_to_html('上一个月');?>"></a>

      <strong><?php echo $arr[year].db_to_html('年').$arr[mon].db_to_html('月'); ?></strong>
      <?php
	    if($ajax_time_next<$products_last_date){ 	    	
	    ?>
       <a href="javascript:;" onClick="get_last_month(<?php echo $ajax_time;?>,<?php echo (int)$HTTP_GET_VARS['products_id'];?>,'next',<?php echo $products_last_date; ?>,<?php echo $products_first_date; ?>,'<?php echo $new_ajax_array;?>')" class="arrowR" alt="<?php echo db_to_html('下一个月');?>" title="<?php echo db_to_html('下一个月');?>"></a>
        <?php }else{?>
      <a href="javascript:;" alt="<?php echo db_to_html('下一个月');?>" title="<?php echo db_to_html('下一个月');?>"></a>
	  <?php }?>
      </span><span class="update"><?php echo $arr_lastupdate[year].db_to_html('年').$arr_lastupdate[mon].db_to_html('月').$arr_lastupdate[mday].db_to_html('日').'10:00am'.db_to_html('更新');?></span></td></tr>
	<tr><td>
          <table class="surplusSeat" cellpadding="0">
              <tr>
	              <td  width="7%">

	                <table class="surplusSeat1" cellpadding="0" style="float:left; " border="0">
		              <tr class="date"><td><?php echo db_to_html('日期');?></td></tr>
		              <tr class="week"><td><?php echo db_to_html('星期');?></td></tr>
		              <tr class="seat"><td><?php echo db_to_html('余座');?></td></tr>
		              <tr class="date"><td><?php echo db_to_html('日期');?></td></tr>
		              <tr class="week"><td><?php echo db_to_html('星期');?></td></tr>
		              <tr class="seat"><td><?php echo db_to_html('余座');?></td></tr>
	                </table>
	              </td>
                  <td width="93%">
                   <table class="surplusSeat2" cellpadding="0" style="float:left; " border="0">

                      <tr class="date">
                      <?php
                       for($i=1;$i<=16;$i++){
                       	   if($print_array[$i]!=''&&in_array($print_array_bk[$i],$new_departure_date_prs))echo '<td class="selected">'.$i.'</td>';else echo '<td>'.$i.'</td>';
                       }?>
                       </tr>
                       <tr class="week"><?php   for($j=1;$j<=16;$j++) echo '<td>'.$print_week[$j].'</td>';  ?>
                       </tr>
                       <tr class="seat">
                       <?php                     
                        for($k=1;$k<=16;$k++){
                        	if(check_date_prs($print_array[$k])==false&&in_array($print_array_bk[$k],$new_departure_date_prs)){
                        		if(in_array($print_array_bk[$k],$soldout)){
									if(in_array($print_array_bk[$k],$group_buy_dates)){
										echo '<td '.$style.'><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.db_to_html('正在团购').'</a></td>';
									}else{
										echo '<td '.$style.'>'.db_to_html('售完').'</td>';
									}
                        		}else{
	                        		if($print_array[$k]>'0')							echo '<td '.$style.'>'.$print_array[$k].'</td>';
	                                elseif($print_array[$k]=='0') 				echo '<td '.$style.'>'.db_to_html('售完').'</td>';
	                                else 															echo '<td '.$style.'></td>';
                        		}
                        	}else if(check_date_prs($print_array[$k])==true&&in_array($print_array_bk[$k],$new_departure_date_prs) ){     
                        		if(in_array($print_array_bk[$k],$soldout)){
									if(in_array($print_array_bk[$k],$group_buy_dates)){
										echo '<td '.$style.'><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.db_to_html('正在团购').'</a></td>';
									}else{
										echo '<td '.$style.'>'.db_to_html('售完').'</td>';
									}
                        		}else{                   	
	                        		$kdateint = intval(date('Ymd' , strtotime($print_array[$k].' 0:0:0')));
		                        	if($kdateint <= $nowint)  echo '<td '.$style.'>'.db_to_html('售完').'</td>';
		                        	else	echo '<td '.$style.'>'.db_to_html('充足').'</td>';
                        		}
                             }else{ echo '<td '.$style.'></td>'; }
                        }
                       ?>
                       </tr>
                       <tr class="date">
                      <?php
                       for($i=17;$i<=32;$i++){                       		                		
                       	   if($print_array[$i]!=''&&in_array($print_array_bk[$i],$new_departure_date_prs)){
                       	   	   echo '<td class="selected">'.$i.'</td>';
                       	   }else if($i<$print_array_len){
                       	   	   echo '<td>'.$i.'</td>';
                       	   }else{
                       	   	  echo '<td></td>';
                       	   }
                       }?>
                       </tr>
                       <tr class="week"><?php   for($j=17;$j<=32;$j++) echo $j<=$print_week_len ? '<td>'.$print_week[$j].'</td>':'<td></td>';?>   </tr>
                       <tr class="seat">
                       <?php
                        for($k=17;$k<=32;$k++){                        	   
                        	if(check_date_prs($print_array[$k])==false&&in_array($print_array_bk[$k],$new_departure_date_prs)){
                        		if(in_array($print_array_bk[$k],$soldout)){
									if(in_array($print_array_bk[$k],$group_buy_dates)){
										echo '<td '.$style.'><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.db_to_html('正在团购').'</a></td>';
									}else{
										echo '<td '.$style.'>'.db_to_html('售完').'</td>';
									}
                        		}else{  
	                        		if($print_array[$k]>'0')							echo '<td '.$style.'>'.$print_array[$k].'</td>';
	                                elseif($print_array[$k]=='0') 				echo '<td '.$style.'>'.db_to_html('售完').'</td>';
	                                else 															echo '<td '.$style.'></td>';
                        		}
                        	}else if(check_date_prs($print_array[$k])==true&&in_array($print_array_bk[$k],$new_departure_date_prs) ){
                        		if(in_array($print_array_bk[$k],$soldout)){
									if(in_array($print_array_bk[$k],$group_buy_dates)){
										echo '<td '.$style.'><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.db_to_html('正在团购').'</a></td>';
									}else{
										echo '<td '.$style.'>'.db_to_html('售完').'</td>';
									}
                        		}else{
		                        	$kdateint = intval(date('Ymd' , strtotime($print_array[$k].' 0:0:0')));
		                        	if($kdateint <= $nowint ||in_array($print_array_bk[$k],$soldout))  echo '<td '.$style.'>'.db_to_html('售完').'</td>';
		                        	else	 echo '<td '.$style.'>'.db_to_html('充足').'</td>';
                        		}
                             }else{ echo '<td '.$style.'></td>'; }
                        }
                       ?>
                       </tr>

                   </table>

                   </td>
              </tr>
          </table>
	</td>
	</tr>
    </table>
     <em class="emstyle" id="notic_orders_foot_sms"><?php echo YELLOWSTONE_TABLE_NOTES;?></em>
</div>
	</div>
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
<script type="text/javascript"><!--

function get_last_month(this_month,product_id,action,products_last_date,products_first_date,ajax_array){
  var msg = document.getElementById("ajax_msg");
  var url ="get_nextandlast_remaining_seats.php?this_month="+this_month+"&product_id="+product_id+"&action="+action+"&products_last_date="+products_last_date+"&products_first_date="+products_first_date+"&ajax_array="+ajax_array;
  ajax.open('GET',url,true);
  ajax.onreadystatechange = function(){
     if (ajax.readyState == 4 && ajax.status == 200){
       msg.innerHTML = ajax.responseText;
     }
  }
  ajax.send(null);
}

<?php 
//检查本月是否还有空位
$str_thismonth = date('Y-m',time());
$qry_remaining_seats_details_sql = "SELECT * FROM products_remaining_seats where products_id = '".(int)$HTTP_GET_VARS['products_id']."'AND departure_date LIKE '$str_thismonth%'";
$qry_remaining_seats_details = tep_db_query($qry_remaining_seats_details_sql);
$remaining_seats = array();
while($row  = tep_db_fetch_array($qry_remaining_seats_details)){
	$remaining_seats[$row['departure_date']] = $row['remaining_seats_num'];
}
$dt = intval(date('t',time()));
$has_seat = false;  //检查此后的几天是否有出发的团,并检查是否有可预订的座位
for($i = $arr['mday'] ;$i <= $dt;$i++){
	$daystr = $arr['year'].'-'.str_pad($arr['mon'],2,'0',STR_PAD_LEFT).'-'.str_pad($i,2,'0',STR_PAD_LEFT) ;
	if(in_array($daystr ,$new_departure_date_prs)){
		if(in_array($daystr, $soldout)) continue;
		if(array_key_exists($daystr,$remaining_seats)){
			if(intval($remaining_seats[$daystr]) > 0  ){
				$has_seat = true ;
				break;
			}
		}else{
			$has_seat = true ;
			break;
		}
	}
}

if(!$has_seat){
?>
get_last_month(<?php echo $ajax_time;?>,<?php echo (int)$HTTP_GET_VARS['products_id'];?>,'next',<?php echo $products_last_date; ?>,<?php echo $products_first_date; ?>,'<?php echo $new_ajax_array;?>');
<?php 
}

/*echo "//first = ".date('Y-m-d' ,  $products_first_date);
echo "//last =".date('Y-m-d' ,  $products_last_date);
echo date('Y-m-d' ,  $ajax_time);
echo "\n";*/
//修复每月最后一天无法自动跳转到下月的bug vincent20110831
if(date('t',time()) == date('j',time()) &&  $has_seat){
?>
	get_last_month(<?php echo $ajax_time;?>,<?php echo (int)$HTTP_GET_VARS['products_id'];?>,'next',<?php echo $products_last_date; ?>,<?php echo $products_first_date; ?>,'<?php echo $new_ajax_array;?>');

<? }?>
//--></script>

<?php } ?>