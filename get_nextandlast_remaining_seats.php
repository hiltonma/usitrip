<?php
//check new account



require_once('includes/application_top.php');

if(tep_not_null($_GET['this_month'])&&tep_not_null($_GET['product_id'])){
	//Howard added 110815-1_黄石团座位表团购日期优化{
	$group_buy_dates = array();
	if($GroupBuyProductId = getGroupBuyProductIdFromRelated((int)$_GET['product_id'])){
		$group_buy_avaliabledates = $mcache->product_departure_date($GroupBuyProductId);
		$group_buy_avaliabledates = remove_soldout_dates($GroupBuyProductId, (array)$group_buy_avaliabledates);
		array_multisort($group_buy_avaliabledates,SORT_ASC);
		foreach((array)$group_buy_avaliabledates as $val){
			$group_buy_dates[]=substr($val,0,10);
		}
	}
	//}
	
	 $nowint = intval(date('Ymd',time()));
      $ajax_time = date("Y-m-d",$_GET['this_month']);//获取传递过来的日期
	  if(preg_replace('/.*-/','',$ajax_time)>=30){
	  	$ajax_time = substr($ajax_time,0,8)."01";
	  }
	  switch($_GET['action']){
      	case 'last': 
			//$ajax_time = substr($ajax_time,0,8)."01";
			//echo $ajax_time;
			$daynum_onemonth = date('t',strtotime("$ajax_time-1 month"));
			$str_thismonth = date("Y-m",strtotime("$ajax_time-1 month"));
			$ajax_time_bk = strtotime("$ajax_time-1 month");
			$arr = getdate(strtotime("$ajax_time-1 month"));
			$ajax_time_now = date("Y-m-d",$ajax_time_bk);
			$ajax_time_last = strtotime("$ajax_time_now-1 month");
			$ajax_time_next =$_GET['this_month'];
		break;
      	case 'next': 
			//$ajax_time = substr($ajax_time,0,8)."01";
			$daynum_onemonth = date('t',strtotime("$ajax_time+1 month"));
			$str_thismonth = date("Y-m",strtotime("$ajax_time+1 month"));
			$ajax_time_bk = strtotime("$ajax_time+1 month");
			$arr = getdate(strtotime("$ajax_time+1 month"));
			$ajax_time_now = date("Y-m-d",$ajax_time_bk);
			$ajax_time_next = strtotime("$ajax_time_now+1 month");
			$ajax_time_last =$_GET['this_month'];
		break;
      }
      //get_general_to_ajax_string($_SESSION['language']);
      echo '<h3>'.general_to_ajax_string(db_to_html('本团剩余座位情况:')).'<em>'.general_to_ajax_string(db_to_html('(黄色选中为出团日期)')).'</em></h3>';
      echo '<table cellpadding="0">';
      $qry_remaining_seats_lastupdate = "SELECT update_date FROM  `products_remaining_seats` where products_id = '".(int)$_GET['product_id']."' ORDER BY `update_date`  DESC LIMIT 1 ";
      $res_remaining_seats_lastupdate = tep_db_query($qry_remaining_seats_lastupdate);
      $row_remaining_seats_lastupdate = tep_db_fetch_array($res_remaining_seats_lastupdate);
      $arr_lastupdate = getdate(strtotime($row_remaining_seats_lastupdate['update_date']));
      for($i=0;$i<=$daynum_onemonth;$i++){
      	$print_array[$i]='';
      }
      //echo $str_thismonth;
      for($i=1;$i<=$daynum_onemonth;$i++){
      	$print_array[$i]=date('Y-m-d',strtotime($str_thismonth."-".$i));
      }
      $print_array_bk = $print_array;
      $ajax_array = $_GET['ajax_array'];
      $departure_date_prs = explode('|', $_GET['ajax_array']);
       //获取该团已设置为已售完的日期
	$sql = tep_db_query('SELECT products_soldout_date FROM `products_soldout_dates` WHERE products_id = '.intval($_GET['product_id']));
	$soldout =array() ;while($row = tep_db_fetch_array($sql))$soldout[] = $row['products_soldout_date'];
	//获取该团已设置为已售完的日期 结束
	
      $qry_remaining_seats_details = "SELECT * FROM products_remaining_seats where products_id = '".(int)$_GET['product_id']."'AND departure_date LIKE '$str_thismonth%' ";
      $arr_today = mktime();
      $res_remaining_seats_details = tep_db_query($qry_remaining_seats_details);
      while($row_remaining_seats_details = tep_db_fetch_array($res_remaining_seats_details)){
      	    $print_arr = getdate(strtotime($row_remaining_seats_details['departure_date']));
      	    if(strtotime($row_remaining_seats_details['departure_date'])>$arr_today){
      	    	if($row_remaining_seats_details['remaining_seats_num']>'0'){
      	           $print_array[$print_arr[mday]] = $row_remaining_seats_details['remaining_seats_num'];
      	    	}else{
      	    	   $print_array[$print_arr[mday]] ='0';
      	    	}
      	    }
      }
     for($i=1;$i<=$daynum_onemonth;$i++){

       	  $print_week[$i] = general_to_ajax_string(get_chinese_week(date('l',strtotime($str_thismonth."-".$i))));
       	  
      }
	
     $ajax_php_1 = '<tr><td class="titleBar"><span>'.general_to_ajax_string(db_to_html('年月')).'</span><span class="years">';
     if($ajax_time_last>=$_GET['products_first_date']){
        $ajax_php_1.='<a href="javascript:;"onClick="get_last_month('.$ajax_time_bk.",".$_GET['product_id'].","."'last'".",".$_GET['products_last_date'].",".$_GET['products_first_date'].","."'$ajax_array'".')" class="arrowL" alt="'.general_to_ajax_string(db_to_html('上一个月')).'" title="'.general_to_ajax_string(db_to_html('上一个月')).'"></a>';
     }else{
      	$ajax_php_1.='<a href="javascript:;"  alt="'.general_to_ajax_string(db_to_html('上一个月')).'" title="'.general_to_ajax_string(db_to_html('上一个月')).'"></a>';
     }
      $ajax_php_1.='<strong>'.$arr[year].general_to_ajax_string(db_to_html('年')).$arr[mon].general_to_ajax_string(db_to_html('月')).'</strong>';
      if($ajax_time_next<$_GET['products_last_date']){
        $ajax_php_1.='<a href="javascript:;"onClick="get_last_month('.$ajax_time_bk.",".$_GET['product_id'].","."'next'".",".$_GET['products_last_date'].",".$_GET['products_first_date'].","."'$ajax_array'".')" class="arrowR" alt="'.general_to_ajax_string(db_to_html('下一个月')).'" title="'.general_to_ajax_string(db_to_html('下一个月')).'"></a></span>';

      }else{
      	$ajax_php_1.='<a href="javascript:;"  alt="'.general_to_ajax_string(db_to_html('下一个月')).'" title="'.general_to_ajax_string(db_to_html('下一个月')).'"></a></span>';

      }
      $ajax_php_1.='<span class="update">'.$arr_lastupdate[year].general_to_ajax_string(db_to_html('年')).$arr_lastupdate[mon].general_to_ajax_string(db_to_html('月')).$arr_lastupdate[mday].general_to_ajax_string(db_to_html('日')).'10:00am'.general_to_ajax_string(db_to_html('更新')).'</span></td></tr>';

      echo $ajax_php_1;
      echo '<table class="surplusSeat" cellpadding="0">';
      echo '<tr>';
      echo '<td  width="7%">';
      echo '<table class="surplusSeat1" cellpadding="0" style="float:left; ">';
      echo '<tr class="date"><td width="40">'.general_to_ajax_string(db_to_html('日期')).'</td></tr>';
      echo '<tr class="week"><td width="40">'.general_to_ajax_string(db_to_html('星期')).'</td></tr>';
      echo '<tr class="seat"><td width="40">'.general_to_ajax_string(db_to_html('余座')).'</td></tr>';
      echo '<tr class="date"><td width="40">'.general_to_ajax_string(db_to_html('日期')).'</td></tr>';
      echo '<tr class="week"><td width="40">'.general_to_ajax_string(db_to_html('星期')).'</td></tr>';
      echo '<tr class="seat"><td width="40">'.general_to_ajax_string(db_to_html('余座')).'</td></tr>';
      echo ' </table>';
      echo '</td>';
      echo '<td width="93%">';
      echo '<table class="surplusSeat2" cellpadding="0" style="float:left; ">';
      echo '<tr class="date">';
      for($i=1;$i<=16;$i++){
            if($print_array[$i]!=''&&in_array($print_array_bk[$i],$departure_date_prs)){
               echo '<td class="selected">'.$i.'</td>';
            }else{
               echo '<td>'.$i.'</td>';
            }
      }
      echo '</tr>';
      echo '<tr class="week">';
      for($j=1;$j<=16;$j++){
          echo '<td>'.$print_week[$j].'</td>';
      }
      echo '</tr>';
      echo '<tr class="seat">';
      for($k=1;$k<=16;$k++){
          if(check_date_prs($print_array[$k])==false&&in_array($print_array_bk[$k],$departure_date_prs)){
          	if(in_array($print_array_bk[$k],$soldout)){
          		if(in_array($print_array_bk[$k],$group_buy_dates)){
					echo '<td><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.general_to_ajax_string(db_to_html('正在团购')).'</a></td>';
				}else{
					echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
				}
          	}else{
             	if($print_array[$k]>'0'){
                       echo '<td>'.$print_array[$k].'</td>';
                  }elseif($print_array[$k]=='0'){
                       echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
                  }else{
                       echo '<td></td>';
                  }
          	}
          }else if(check_date_prs($print_array[$k])==true&&in_array($print_array_bk[$k],$departure_date_prs)){
		          	if(in_array($print_array_bk[$k],$soldout)){
						if(in_array($print_array_bk[$k],$group_buy_dates)){
							echo '<td><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.general_to_ajax_string(db_to_html('正在团购')).'</a></td>';
						}else{
							echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
						}
		          	}else{
	          			$kdateint = intval(date('Ymd' , strtotime($print_array[$k].' 0:0:0')));
		                if($kdateint <= $nowint)   echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
	                	else echo '<td>'.general_to_ajax_string(db_to_html('充足')).'</td>';
		          	}
               // echo '<td>'.general_to_ajax_string(db_to_html('充足')).'</td>';
         }else{
                  echo '<td></td>';
              }
      }
      echo '</tr>';
      echo '<tr class="date">';
      for($i=17;$i<=32;$i++){
           if($print_array[$i]!=''&&in_array($print_array_bk[$i],$departure_date_prs)){
                 echo '<td class="selected">'.$i.'</td>';
           }else if($i<count($print_array)){
                  echo '<td>'.$i.'</td>';
           }else{
              echo '<td></td>';
           }

      }
      echo '</tr>';
      echo '<tr class="week">';
      for($j=17;$j<=32;$j++){
            if($j<=count($print_week)){
               echo '<td>'.$print_week[$j].'</td>';
            }else{
               echo '<td></td>';
            }
      }
      echo '</tr>';
      echo '<tr class="seat">';
      for($k=17;$k<=32;$k++){
             if(check_date_prs($print_array[$k])==false&&in_array($print_array_bk[$k],$departure_date_prs)){             	
		             if(in_array($print_array_bk[$k],$soldout)){
						if(in_array($print_array_bk[$k],$group_buy_dates)){
							echo '<td><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.general_to_ajax_string(db_to_html('正在团购')).'</a></td>';
						}else{
							echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
						}
		          	}else{
	                  if($print_array[$k]>'0'){
	                         echo '<td>'.$print_array[$k].'</td>';
	                  }elseif($print_array[$k]=='0'){
	                         echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
	                  }else{
	                       echo '<td></td>';
	                  }
		          	}
                // echo '<td>'.$print_array[$k].'</td>';
             }else if(check_date_prs($print_array[$k])==true&&in_array($print_array_bk[$k],$departure_date_prs)){
              		if(in_array($print_array_bk[$k],$soldout)){
						if(in_array($print_array_bk[$k],$group_buy_dates)){
							echo '<td><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$GroupBuyProductId).'" target="_blank">'.general_to_ajax_string(db_to_html('正在团购')).'</a></td>';
						}else{
							echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
						}
		          	}else{		          	
	               		$kdateint = intval(date('Ymd' , strtotime($print_array[$k].' 0:0:0')));
		                if($kdateint <= $nowint)   echo '<td>'.general_to_ajax_string(db_to_html('售完')).'</td>';
	                	else echo '<td>'.general_to_ajax_string(db_to_html('充足')).'</td>';
		          	}
             }else{
                  echo '<td></td>';
             }
      }
      echo '</tr>';
      echo '</table>';
      echo '</td>';
      echo '</tr>';
      echo '</table>';
      echo '</table>';
      echo '<em class="emstyle" id="notic_orders_foot_sms">'.general_to_ajax_string(YELLOWSTONE_TABLE_NOTES).'</em>';




      //echo '</div>';



}

?>
