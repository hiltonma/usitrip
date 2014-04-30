<?php
require_once(DIR_FS_CLASSES . 'order.php');
 
$field = $_GET['field'];
$keyword  =html_to_db($_GET['keyword']);
$sql = '';
 //修改前的sql $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_subtotal' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
if(in_array($field , array('orders_id' , 'products_name'))){
	if($keyword != ''){
		if($field == 'orders_id' && is_numeric($keyword)){			
			$sql = "select o.customers_id, o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, os.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " os where o.customers_id = '" . (int)$customer_id . "' and o.orders_id=".intval($keyword)." and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
		}else if($field == 'products_name'){
			$keywordstr = tep_db_prepare_input("%".$keyword."%");
			$sql = " SELECT  o.customers_id, o.orders_id,  o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, os.orders_status_name FROM " .TABLE_ORDERS." o , " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " os WHERE  o.customers_id=".(int)$customer_id." AND o.orders_id IN(SELECT orders_id FROM ".TABLE_ORDERS_PRODUCTS." WHERE products_name like '".$keywordstr."') and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
		}
	}
}

if($sql == '' ) 
$sql = "select o.customers_id, o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, os.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " os where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
 
$history_split = new splitPageResults($sql, MAX_DISPLAY_ORDER_HISTORY);   
ob_start();

?>

<!--<div class="title titleSmall">
        <b></b><span></span>
        <h3>订单查询</h3>
      </div>-->
      <div class="mySearch">
	  	<?php echo $old->output_from('ordersList','查看2012年10月14日之前的订单请点击这里','login_old_site');?>
		<div class="orderSearch">
        <a class="btn btnGrey searchBtn" href="javascript:void(0);" onclick="jQuery('#search_order').submit()"><button>搜 索</button></a>
        <label>订单查询：</label>
        <?php echo tep_draw_form("search_order", tep_href_link(FILENAME_ACCOUNT_HISTORY,'','SSL'),'get','id=\'search_order\'');?>
        <select name="field">
          <option value="orders_id" <?php if($field == 'orders_id') echo 'selected' ;?>>订单编号</option>
          <option value="products_name" <?php if($field == 'products_name') echo 'selected' ;?>>行程</option>
        </select>
        <?php echo tep_draw_input_field("keyword" ,$keyword ,'class="text" ')?>
       	<?php echo tep_draw_form_close("search_order");?>
		
      </div>
	  
	  </div>
<?php
if ($history_split->number_of_rows > 0) {   
     $history_query = tep_db_query($history_split->sql_query);    
?>
    <!-- 具体列表 -->
<?php 
while ($history = tep_db_fetch_array($history_query)) {      
		 /*
		 $result_echo_ss= trim(get_customer_notified_tpl($history['orders_id'] ,$history['orders_status_name'] ));
		 $sepPos = strrpos($result_echo_ss,' ',0);
		 if($sepPos !== false) $result_echo_ss = substr($result_echo_ss, 0 ,$sepPos);
		 */
		 $result_echo_ss= trim(tep_get_orders_status_name($history['orders_id']));
		 
		 $order_type = tep_is_companion_order($history['orders_id'])?'结伴同游订单':'普通订单';
		if (tep_not_null($history['delivery_name'])) {
		      $order_name_title = '&nbsp;&nbsp; 出货给：';
		      $order_name = $history['delivery_name'];
		} else {
		      $order_name_title = '账单寄至：';
		     $order_name = $history['billing_name'];
		}
		/*
		$loyal_discount_query = "select orders_id,text from ".TABLE_ORDERS_TOTAL." where orders_id='".$history['orders_id']."' and class='ot_customer_discount'";
		$res_loyal_discount = tep_db_query($loyal_discount_query);
		if(tep_db_num_rows($res_loyal_discount)>0)
		{
			$row_loyal_discount = tep_db_fetch_array($res_loyal_discount);
			$final_cost_query = tep_db_query("select orders_id,text from ".TABLE_ORDERS_TOTAL." where orders_id='".$history['orders_id']."' and class='ot_total'");
			$row_final_cost = tep_db_fetch_array($final_cost_query);
			$total_cost_html = strip_tags($history['order_total']).'<br /><b>'.SUB_TITLE_LOYAL_CUSTOMER_DISCOUNT.' </b><span class="productSpecialPrice">'.strip_tags($row_loyal_discount['text']).'</span><br /><b>'.TEXT_ORDER_FINAL_COST.' </b><span class="sp3_no_decoration">'.strip_tags($row_final_cost['text']).'</span>'; 
		}else{
			$total_cost_html = $history['order_total'];
		}*/

?>
<div class="orderRef">
  <div class="tit <?php echo ($order_type == '结伴同游订单' ? 'jiebantongyou' : '')?>"><?php echo $order_type;?>&nbsp;&nbsp;订单编号：<span class="ui-order-number"><?php echo $history['orders_id']?></span>&nbsp;&nbsp;&nbsp;&nbsp;下单时间：<?php echo html_to_db(tep_date_long($history['date_purchased']));?></div>

    <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#dcdcdc">
      <tr>
        <td height="33" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue">产品名称</strong></td>
        <td width="100" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue">订购人姓名</strong> </td>
        <td width="102" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue">订单金额</strong></td>
        <td width="84" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue">支付状态</strong></td>
        <td width="84" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue">状态</strong></td>
        <td width="118" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue">操作</strong></td>
      </tr>

          <tr>
        <td bgcolor="#FFFFFF" class="padding8">
			<table cellpadding="0" cellspacing="0" border="0">
           <?php 
           	$prows = tep_get_products_by_order($history['orders_id']);
			$flightButtonUrl = '';
			$i = 0 ; 
			$len = count($prows); 
			
			$is_pay = true; // 保存是否已全部付款 by lwkai add 2012-04-23
			$payment_status = array();
			   
			foreach($prows as $prow){
		  		$eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$history['orders_id'] . "' and products_id=".$prow['products_id']." ");
				$eticket_result = tep_db_fetch_array($eticket_query);
				
				if ((int)$prow['orders_products_payment_status'] != 1) {
					$is_pay = false;
				}
				$payment_status[] = tep_get_orders_products_payment_status_name($prow['orders_products_payment_status']);
				
				if($eticket_result['confirmed']==1){
					//已出电子参团凭证
					//  出票后还能修改一次航班信息
					$check_modify_sql = "select modify_count from orders_product_flight where orders_id='" . (int)$history['orders_id'] . "' and products_id='" . $prow['products_id'] . "'";
					$check_modify_result = tep_db_query($check_modify_sql);
					$check_modify_result = tep_db_fetch_array($check_modify_result);
					if ($check_modify_result['modify_count'] == '0') {
						//判断是否需要显示 填写航班信息按钮
						if($flightButtonUrl == ''&& $prow['is_hotel'] == '0' && $prow['is_transfer'] == '0'){
							//echo "select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$order['orders_id'] . "' and products_id=".$prow['products_id']." ";
							$flightInfoQuery = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$history['orders_id'] . "' and products_id=".$prow['products_id']." ");
							if(tep_db_num_rows($flightInfoQuery) > 0){
								$flightButtonUrl = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id='.$history['orders_id'].'&pid='.$prow['products_id'].'&i=0&edit=true','SSL');
							}
						}
					}
				}else{
					//判断是否需要显示 填写航班信息按钮
					if($flightButtonUrl == ''&& $prow['is_hotel'] == '0' &&$prow['is_transfer'] == '0'){
						//echo "select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$order['orders_id'] . "' and products_id=".$prow['products_id']." ";
						$flightInfoQuery = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$history['orders_id'] . "' and products_id=".$prow['products_id']." ");
						if(tep_db_num_rows($flightInfoQuery) > 0){
							$flightButtonUrl = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id='.$history['orders_id'].'&pid='.$prow['products_id'].'&i=0&edit=true','SSL');
						}			
					}
				}
          		
				if($prow['is_hide'] == 0){
          		?>
          		<tr><td 
          		<?php
          		if ($len > 1 && $i < ($len - 1)) {
					echo ($i == 0 ? ' style="padding-bottom:15px;border-bottom:1px solid #dcdcdc;"' : '  style="padding-bottom:15px;padding-top:15px;border-bottom:1px solid #dcdcdc;"');
          		 } else if($i > 0 && ($i == $len - 1)) {
					 echo '  style="padding-top:15px;"';
				 }?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$prow['products_id'])?>"><?php echo tep_db_output($prow['products_name'])?></a></td></tr>
          		<?php 
		  		$i++;
				}}?> 
          </table>         


        </td>
        <td align="center" bgcolor="#FFFFFF"><?php echo strip_tags(tep_get_customer_name($history['customers_id']));?></td>
        <td align="center" bgcolor="#FFFFFF"><?php echo strip_tags($history['order_total'])?></td>
        <td align="center" bgcolor="#FFFFFF"><?php echo implode('<br />',array_unique($payment_status));?></td>
        <td align="center" bgcolor="#FFFFFF"><?php echo $result_echo_ss?></td>
        <td align="center" bgcolor="#FFFFFF"><?php
        /*if ($is_pay == true) {
			?><a class="btn" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$history['orders_id'],'SSL')?>" >订单详情</a><?php
		} else {*/
			/* 计算是否已经取消单 ，如果取消了 则不显示更新航班信息 */
			$order = new order((int)$history['orders_id']);
			/*for ($i_j=0, $n=sizeof($order->totals); $i_j<$n; $i_j++) {	
				if($order->totals[$i_j]['class']=="ot_total"){
					$otTotal = $order->totals[$i_j]['value'];
				}
			}
			
			$need_pay = $otTotal - $order->info['orders_paid'];
			
			if($need_pay > 0 && $order->info['orders_status_id'] != 6){
			// 不再判断是不是付款状态 ，只判断是否发了电子参团凭证
			*/

			//print_r($order->info);
			//echo($order->info['orders_status_id'] . '----' . $flightButtonUrl);
			if ($order->info['orders_status_id'] != 6 ) {
				if ($is_pay != true) {?>
				<a class="btn" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$history['orders_id'],'SSL')?>" >详情/去付款</a>
				<?php } else {?>
				<a class="btn" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$history['orders_id'],'SSL')?>" >订单详情</a>
				<?php }
				if (tep_not_null($flightButtonUrl) == true) {?>
				<p class="inputs2"><a class="btn btnGrey" href="javascript:;" onclick="location.href='<?php echo $flightButtonUrl?>';"><button type="button">填写航班信息</button></a></p>
				<?php
				}
			} else {?>
				<a class="btn" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$history['orders_id'],'SSL')?>" >订单详情</a>
			<?php }
			unset($need_pay);
			unset($otTotal);
			unset($order);
			// 航班修改按钮 结束?>
        	<?php //}?>
        </td>
      </tr>
        
     </table>
  </div>       
               
<?php } ?>
<div class="bot">
	<div  class="page">
		<?php echo html_to_db($history_split->display_links_2011(5, tep_get_all_get_params(array('page'))))?>
	</div>
 </div>

<!--</div>--><?php //myOrder element end?>
<?php /*check order total finish*/ } else {?>
	  <div class="con">
          <ul class="left">
		  <li>
		  没有找到订单记录		  
		  </li>
		  </ul>
      </div>
<?php }?>
<?php echo db_to_html(ob_get_clean());?>