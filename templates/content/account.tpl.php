 <?php 
$has_point = tep_get_shopping_points($customer_id);
//1.我的信息(5未读) start
$my_sms_sql = tep_db_query('SELECT  COUNT(*) AS total FROM `site_inner_sms` WHERE owner_id ="'.$customer_id.'" AND has_read=\'0\'');
$my_sms_row = tep_db_fetch_array($my_sms_sql);
$my_sms = intval($my_sms_row['total']);
//2.我发布的结伴帖部分 
$my_sent_sql = tep_db_query('SELECT COUNT(*) AS total FROM `travel_companion` tc ,`travel_companion_application` tca  WHERE tc.t_companion_id=tca.t_companion_id AND tc.customers_id="'.$customer_id.'"  AND tca.tca_verify_status = \'0\' ');
$my_sent_row = tep_db_fetch_array($my_sent_sql);
$my_sent = intval($my_sent_row ['total']);
//3.我申请的结伴帖部分 start 计算 我申请了 楼主没有同意也没有拒绝的  1同意2拒绝
$app_sql = tep_db_query('SELECT COUNT(*) AS total FROM `travel_companion_application` WHERE customers_id="'.(int)$customer_id.'" AND tca_verify_status <> \'0\'');
$my_app_row = tep_db_fetch_array($app_sql);
$my_app = intval($my_app_row['total']);
//最近订单
 $order_history_query =tep_db_query( "SELECT
  o.orders_id, o.date_purchased,
  o.delivery_name, o.billing_name, ot.text as order_total,orders_status, s.orders_status_name, o.customers_name 
 FROM  
 	" . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s 
 WHERE
 	 o.customers_id = '" . (int)$customer_id . "' 
 	 AND o.orders_id = ot.orders_id 
 	 AND ot.class = 'ot_total' 
 	 AND o.orders_status = s.orders_status_id 
 	 AND s.language_id = '" . (int)$languages_id . "' 
 	 ORDER BY orders_id DESC LIMIT 2") ;
$lastOrders = array();
while($row = tep_db_fetch_array($order_history_query)){
	$lastOrders[] = $row ;
}
//获取用户信息判断用户信息的完成度
$finish_percent = 0;
$closePic1 = false;$closePic2=false;
$customerinfo = tep_get_customers_info_fix($customer_id);
if($customerinfo['customers_firstname'] != ''){
	$finish_percent += 25;
}
if($customerinfo['customers_validation'] == 1 ){
	$finish_percent += 25;
}

if($customerinfo['customers_face'] !='' ){
	$finish_percent += 25;
	$closePic2 = true;
}
//如果填完全部资料 则100%
if($customerinfo['customers_lastname'] != '' && $customerinfo['customers_gender']!=''&&$customerinfo['customers_dob']!=''&&$customerinfo['customers_mobile_phone']!=''&&$customerinfo['customers_fax']!=''&&$customerinfo['entry_street_address']!=''&&$customerinfo['entry_postcode']!=''){
	$finish_percent += 25;	
}
if($closePic2 == false &&  $finish_percent == 75){
	$closePic1=true;
	$finish_percent_dsp_2 = '<h3>完成度'. $finish_percent.'%</h3>';
}else{
	$finish_percent_dsp_1 = '<h3>完成度'. $finish_percent.'%</h3>';
}



//待处理的订单 订单状态除了 “charge capture （100006）; cancelled （6） ;Credit Issued （100080）;Refunded （100005）”外，其他状态下均记录为“订单待处理”。
//待付款的订单 100094 payment pending - 1 pending

//$row = tep_db_fetch_array(tep_db_query("SELECT COUNT(*) AS total FROM ".TABLE_ORDERS." WHERE customers_id = ".intval($customer_id).' AND orders_status  IN(100094,1) '));
//$needpay_total = $row['total'];
// 还未付款和还未付完款的订单总数 用来提示用户 还有多少订单未付款 by lwkai add 2012-05-11 start
$order_no_pay = tep_db_query("SELECT count( o.orders_id ) AS t FROM orders o, `orders_products` op WHERE op.orders_id = o.orders_id AND op.orders_products_payment_status <>1 AND `customers_id` =" . (int)$customer_id . " group by o.orders_id ");
$needpay_total = tep_db_num_rows($order_no_pay);
// 未付款统计 结束 end by lwkai 2012-05-11 

// 有几个电子参团凭证还没查看提示。 by lwkai add 2012-05-11
$order_view_eticket = tep_db_query("SELECT COUNT(ope.orders_eticket_id) as t FROM " . TABLE_ORDERS_PRODUCTS_ETICKET . " ope,orders o WHERE ope.confirmed = 1 and o.orders_id = ope.orders_id and ope.is_read = 0 and o.customers_id ='" . (int)$customer_id . "'");
$eticket_view_arr = tep_db_fetch_array($order_view_eticket);
$new_eticket = $eticket_view_arr['t'];
// 电子参团凭证提示结束 by lwkai 2012-05-11


//SELECT COUNT(ope.orders_eticket_id) as t FROM orders_product_eticket  ope,orders o WHERE ope.confirmed = 1 and o.orders_id=ope.orders_id and ope.is_read = 0 and o.customers_id = 60001

//订单总数
$row = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "'"));
$order_total= $row['total'];
//消费总额 只是除掉cancelled （6） Refunded （100005）
 $row = tep_db_fetch_array(tep_db_query("select SUM(ot.value) as total from " . TABLE_ORDERS . " o , ".TABLE_ORDERS_TOTAL." ot  where o.orders_id = ot.orders_id
    AND o.customers_id = '" . (int)$customer_id . "' AND class='ot_total' AND o.orders_status  NOT IN(6,100005)"));
$order_cost_total= $row['total'];


 ?>
 <?php ob_start();?>
 <?php if($_COOKIE['uiCloseIndextip'] != 1 && $finish_percent!=100) {
 //这个模块暂时隐藏 start { by _Afei
 /*
 ?>
  <div class="indexTip">
            <div class="top">
                <div class="topLeft"><em></em>花点时间完善一下个人信息吧，这样会让您订购行程更加简单，并将额外获得100积分。</div>
                <div class="topMid">
                    <input type="checkbox" onclick="setCookie('uiCloseIndextip','1',3600*24*365);jQuery('.indexTip').fadeOut('slow');">知道了，以后不再显示
                </div>
                <div class="topRight" onclick="jQuery('.indexTip').fadeOut('slow')"><em></em>关闭</div>
            </div>
<?php if(!$closePic1){?>
            <dl>
                <dt><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT,'','SSL')?>"><span></span>填写我的基本信息<?php #<img src="image/mytours_btn_write.gif">?></a></dt>
                <dd><?php echo $finish_percent_dsp_1?>验证邮箱后，确保准时收到参团凭证、发票、优惠活动等信息，并获100积分。</dd>
            </dl>
<?php }?>
<?php if(!$closePic2){?>
            <dl>
                <dt><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT,'','SSL')?>?action=upload_avatar"><span></span>上传我的个人头像<?php #<img src="image/mytours_btn_up.gif">?></a></dt>
                <dd><p><?php echo $finish_percent_dsp_2?>上传头像后将会在旅客评论、结伴同游等处显示您的头像，彰显您的个性。</p></dd>
            </dl>
<?php }?>
        </div>
<?php */
 //这个模块暂时隐藏 end } by _Afei

}?>
      <div class="mytoursCon ui-clearfix">
        <!--暂时隐藏-->
        <?php 
        
        // by lwkai start {
        /* 原来的老版布局  有部分数据新版没用上，后期可能用到 所以请不要删掉 
        
        <h2><b>您好，<a href="<?php echo tep_href_link('individual_space.php')?>"><?php echo $customer_first_name?></a> 欢迎您回来！</b><?php if(tep_not_null($customer_lastlogin)){?><span>您上一次登录时间：<?php echo $customer_lastlogin?></span><?php }?></h2>
        <ul>
          <li>
            <div class="row1">总积分：<b><?php echo number_format($has_point,POINTS_DECIMAL_PLACES);//$my_score_sum;?></b></div>
            <div class="row2">价值：<b><?php echo $currencies->format(tep_calc_shopping_pvalue($has_point));?></b><a href="<?php echo tep_href_link(FILENAME_POINTS_ACTIONS_HISTORY)?>">积分历史记录</a> <a href="<?php echo tep_href_link(FILENAME_REWARDS4FUN_TERMS)?>">积分规则</a></div>
            <div class="row3">订购时可用积分按比例抵扣现金。</div>
          </li>
          <li>
            <div class="row1">订单总数：<b><?php echo $order_total?></b></div>
            <div class="row2">总消费额：<b><?php echo $currencies->format($order_cost_total);?></b></div>
            <div class="row3"></div>
          </li>
        </ul>
        <div class="tip">消息提示：
          <a href="<?php echo tep_href_link('my_travel_companion.php')?>"><span><?php echo $my_sms?></span>条未读信息(结伴同游)</a>
          <a href="<?php echo tep_href_link('my_travel_companion.php')?>#my_sent"><span><?php echo $my_sent?></span>条我发布的结伴同游待处理</a>
          <a href="<?php echo tep_href_link('my_travel_companion.php' )?>#my_applied"><span><?php echo $my_app?></span>条我申请的结伴同游待处理</a>
          <?php if($needpay_total>0){?>
		  <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY,'','SSL')?>"><span><?php echo $needpay_total?></span>条订单未付款</a>
		  <?php }?>
        </div>
        <div class="link">你现在还可以做的：<a href="<?php echo tep_href_link('index.php')?>" class="blue">返回首页</a></div>
		*/
        //by lwkai end } ?>
        <!--暂时隐藏-->
        <div class="ui-fl ui-user-avatar">
        <?php
		$gender = tep_customer_gender($customer_id);
		$head_img = "touxiang_no-sex.gif";
		if(strtolower($gender)=='m' || $gender=='1'){ $head_img = "touxiang_boy.gif"; }
		if(strtolower($gender)=='f' || $gender=='2'){ $head_img = "touxiang_girl.gif"; }
		$head_img = 'image/'.$head_img;
		$head_img = tep_customers_face($customer_id, $head_img);
		?>
            <a href="<?php echo tep_href_link('account_edit.php','','SSL');?>"><img src="<?php echo $head_img?>" alt="用户头像" width="103" height="97" /></a>
        </div>
        <ul class="ui-fl ui-user-infoList">
            <li class="ui-bb-d">
                <a href="<?php echo tep_href_link('account_edit.php','','SSL');?>" class="ui-fr ui-edit">编辑个人资料</a>
                <em>
                    <strong class="ui-user-name"><?php echo $customer_first_name?></strong><span class="ui-user-login-tips">,&nbsp;欢迎您！<?php if(tep_not_null($customer_lastlogin)){?>上一次登录时间：<?php echo $customer_lastlogin?><?php }?></span>
                </em>
            </li>
            <li class="ui-bb-d">
                <span class="ui-item-l"><span class="ui-item-name">总消费额：</span><em class="ui-user-data"><?php echo $currencies->format($order_cost_total);?></em></span>
                <span class="ui-item-m"><span class="ui-item-name">订单总数：</span><em class="ui-user-data"><?php echo $order_total?></em></span>
                <span class="ui-item-r"><span class="ui-item-name">我的积分：</span><em class="ui-user-data"><?php echo number_format($has_point,POINTS_DECIMAL_PLACES);//$my_score_sum;?></em>&nbsp;&nbsp;价值：<?php echo $currencies->format(tep_calc_shopping_pvalue($has_point));?><a href="<?php echo tep_href_link(FILENAME_POINTS_ACTIONS_HISTORY)?>">积分历史记录</a> <a href="<?php echo tep_href_link(FILENAME_REWARDS4FUN_TERMS)?>"> (怎么得积分？)</a></span>
            
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;将要过期的积分 <b style="color:#FF0000">0</b>（一个月内）
			</li>
            <li class="ui-hide">
                <span class="ui-item-l"><span class="ui-item-name">折 扣 券：</span><em class="ui-user-data">0</em></span>
                <span class="ui-item-m"><span class="ui-item-name">我的佣金：</span><em class="ui-user-data">0</em></span>
            </li>
        </ul>
        <!--用户信息模块，暂时放置的静态内容 end-->
      </div>
<?php if(count($lastOrders) > 0){?>
       <!--最近订单 start-->
       <div class="ui-order-header">
            <div class="ui-fr ui-order-tips">订单提醒：<span><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY,'','SSL')?>">待支付订单</a><em>(<?php echo (int)$needpay_total;?>)</em></span>&nbsp;&nbsp;<span><a href="<?php echo tep_href_link('eticket_list.php','','SSL')?>">电子通知票</a><em>(<?php echo $new_eticket;?>)</em></span></div>
            <h3>最新订单</h3>
        </div>
       <?php foreach($lastOrders as $order){	
		 $result_echo_ss= trim(tep_get_orders_status_name($order['orders_id']));
		 /*
		 $sepPos = strrpos($result_echo_ss,' ',0);
		 $result_echo_ss = tep_get_orders_status_name($order['orders_id']);
		 if($sepPos !== false) $result_echo_ss = substr($result_echo_ss, 0 ,$sepPos);
		 */
		 //print_r($order);
		 $order_type = tep_is_companion_order($order['orders_id']) ? '结伴同游订单' : '普通订单';
		 
		if (tep_not_null($history['delivery_name'])) {
		      $order_name_title = '&nbsp;&nbsp; 出货给：';
		      $order_name = $order['delivery_name'];
		} else {
		      $order_name_title = '账单寄至：';
		     $order_name = $order['customers_name'];
		}
		 
?> 
       <div class="orderRef">
          <div class="tit "><?php echo $order_type;?>&nbsp;&nbsp;订单编号：<?php echo $order['orders_id']?>&nbsp;&nbsp;&nbsp;&nbsp;下单时间：<?php echo html_to_db(tep_date_long($order['date_purchased']));?></div>
        
            <table cellspacing="1" cellpadding="0" border="0" bgcolor="#dcdcdc" width="100%">
              <tbody>
                  <tr>
                    <td align="center" background="/image/nav/user_bg11.gif" height="33"><strong class="color_blue">产品名称</strong></td>
                    <td align="center" background="/image/nav/user_bg11.gif" width="100"><strong class="color_blue">订购人姓名</strong> </td>
                    <td align="center" background="/image/nav/user_bg11.gif" width="102"><strong class="color_blue">订单金额</strong></td>
                    <td align="center" background="/image/nav/user_bg11.gif" width="84"><strong class="color_blue">支付状态</strong></td>
                    <td align="center" background="/image/nav/user_bg11.gif" width="84"><strong class="color_blue">状态</strong></td>
                    <td align="center" background="/image/nav/user_bg11.gif" width="118"><strong class="color_blue">操作</strong></td>
                  </tr>
        
                  <tr>
                	<td bgcolor="#FFFFFF" class="padding8">

                    	<table cellspacing="0" cellpadding="0" border="0">
                           <tbody>
                           <?php 
							  $prows = tep_get_products_by_order($order['orders_id']);
							  $flightButtonUrl = '';
							  $i = 1 ;
							  $len = count($prows);
							  $is_pay = true; // 是否所有产品都付完款
							  $payment_status = array();
							  foreach($prows as $prow){
								$eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$order['order_id'] . "' and products_id=".$prow['products_id']." ");
								$eticket_result = tep_db_fetch_array($eticket_query);
								
								if ((int)$prow['orders_products_payment_status'] != 1) {
									$is_pay = false;
								}
								$payment_status[] = tep_get_orders_products_payment_status_name($prow['orders_products_payment_status']);
								
								if($eticket_result['confirmed']==1){
									//已出电子参团凭证
								}else{
									//判断是否需要显示 填写航班信息按钮
									if($flightButtonUrl == '' && $prow['is_hotel'] == '0' &&$prow['is_transfer'] == '0' ){
										//echo "select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$order['orders_id'] . "' and products_id=".$prow['products_id']." ";
										$flightInfoQuery = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$order['orders_id'] . "' and products_id=".$prow['products_id']." ");
										if(tep_db_num_rows($flightInfoQuery) > 0){
											$flightButtonUrl = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id='.$order['orders_id'].'&pid='.$prow['products_id'].'&i='.$i.'&edit=true','SSL');
										}			
									}
								}
								
							  	if($prow['is_hide'] == 0){
							  ?>
                              <tr>
                            	<td style=" <?php if ($i == 1 && $len > 2) {?>
                               		padding-bottom:15px;border-bottom:1px solid #dcdcdc;
									<?php } elseif($i > 1 && $i < $len) {
										?>padding-top:15px;padding-bottom:15px;border-bottom:1px solid #dcdcdc;<?php 
									}elseif($i>1 && $i == $len){?>
										padding-top:15px;
									<?php }?>"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$prow['products_id'])?>"><?php echo tep_db_output($prow['products_name'])?></a></td>
                              </tr>
                              <?php $i++;
								}}?>
                  			</tbody>
                  		</table>         
                    </td>
                    <td align="center" bgcolor="#FFFFFF"><?php echo $order_name?></td>
                    <td align="center" bgcolor="#FFFFFF"><?php echo strip_tags($order['order_total'])?></td>
                    <td align="center" bgcolor="#FFFFFF"><?php echo implode('<br />',array_unique($payment_status));?></td>
                    <td align="center" bgcolor="#FFFFFF"><?php echo $result_echo_ss?></td>
                    <td align="center" bgcolor="#FFFFFF"><?php
                    if ($is_pay == true){
						?><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$order['orders_id'],'SSL')?>" class="btn">订单详情</a><?php
					} else {
						?>
						<a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$order['orders_id'],'SSL')?>" class="btn">详情/去付款</a>
                        <?php
					}?>
                    <?php if($flightButtonUrl != ''){?><p class="inputs2"><a class="btn btnGrey" href="<?php echo $flightButtonUrl?>"><button>填写航班信息</button></a></p><?php }?></td>
              	 </tr>
			</tbody>
          </table>
  	  </div>
 <?php }//订单列表结束?>     
  <!--最近订单 end-->
  

	  <!--最近订单信息注释 start-->
	  <?php //原来的老版面 start { by lwkai
	  /*
      <div class="title titleSmall">
        <b></b><span></span>
        <h3>最近的订单</h3>
      </div>
       <div class="myOrder">
        <div class="top">
          <div class="left">订单信息</div>
          <div class="mid">订单行程</div>
          <div class="right">操作</div>
        </div>
<?php foreach($lastOrders as $order){	
		 $result_echo_ss= trim(tep_get_orders_status_name($order['orders_id']));
		 /*
		 $sepPos = strrpos($result_echo_ss,' ',0);
		 $result_echo_ss = tep_get_orders_status_name($order['orders_id']);
		 if($sepPos !== false) $result_echo_ss = substr($result_echo_ss, 0 ,$sepPos);
		 * /
		 $order_type = tep_is_companion_order($order['orders_id'])?'结伴同游订单':'普通订单';
		if (tep_not_null($history['delivery_name'])) {
		      $order_name_title = '&nbsp;&nbsp; 出货给：';
		      $order_name = $order['delivery_name'];
		} else {
		      $order_name_title = '账单寄至：';
		     $order_name = $order['billing_name'];
		}
		 
?>  
        <div class="con">
          <ul class="left">
            <li><label>订单类型：</label><?php echo $order_type;?></li>
            <li><label>订单编号：</label><?php echo $order['orders_id']?></li>
            <li><label>订单状态：</label><?php echo $result_echo_ss?></li>
            <li><label>订单时间：</label><?php echo html_to_db(tep_date_long($order['date_purchased']));?></li>
            <li><label>现金总额：</label><?php echo strip_tags($order['order_total'])?></li>
            <li><label><?php echo $order_name_title?></label><?php echo $order_name?></li>
          </ul>
          <ul class="mid">
          <?php 
          $prows = tep_get_products_by_order($order['orders_id']);
          $flightButtonUrl = '';
          $i = 1 ;
          foreach($prows as $prow){
          	$eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$order['order_id'] . "' and products_id=".$prow['products_id']." ");
 			$eticket_result = tep_db_fetch_array($eticket_query);
 			
 			
 			
			if($eticket_result['confirmed']==1){
				//已出电子参团凭证
			}else{
				//判断是否需要显示 填写航班信息按钮
	          	if($flightButtonUrl == '' && $prow['is_hotel'] == '0' &&$prow['is_transfer'] == '0' ){
	          		//echo "select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$order['orders_id'] . "' and products_id=".$prow['products_id']." ";
		          	$flightInfoQuery = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$order['orders_id'] . "' and products_id=".$prow['products_id']." ");
		          	if(tep_db_num_rows($flightInfoQuery) > 0){
		          		$flightButtonUrl = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id='.$order['orders_id'].'&pid='.$prow['products_id'].'&i='.$i.'&edit=true','SSL');
		          	}			
	          	}
			}
          	$i++;
          ?>
          <li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$prow['products_id'])?>"><?php echo tep_db_output($prow['products_name'])?></a></li>
          <?php }?>
          </ul>
          <div class="right">
            <div><a class="btn btnGrey" href="javascript:;" onclick="location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO , 'order_id='.$order['orders_id'],'SSL')?>';"><button>订单详情</button></a></div>
            <?php if($flightButtonUrl != ''){?><div><a class="btn btnGrey" href="javascript:;" onclick="location.href='<?php echo $flightButtonUrl?>';"><button>填写航班信息</button></a></div><?php }?>
            
            <!--最近订单信息注释 end-->
            <!--{<div><a class="btn btnGrey" href="javascript:;"><button>填写酒店延住</button></a></div>}-->
            <!--最近订单信息注释 start-->
             
          </div>
        </div>
<?php }//订单列表结束?>
        <div class="bot">
          <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY)?>">查看更多订单&gt;&gt;</a>
        </div>
       
      </div>
       */ 
       // 原来的最近订单 end } ?>
      <!--最近订单信息注释 end-->
       

<?php } //订单列表?>
      <!--行程tab注释 start-->
      <div class="ui-route-tab">
        <div class="ui-route-tabmenu">
            <a href="javascript:;" class="ui-current">您可能感兴趣的行程</a>
            <a href="javascript:;">热销行程</a>
            <a href="javascript:;">特价行程</a>
        </div>
        <div class="ui-route-content ui-clearfix">
            <div class="subdiv">
                <ul class="ui-route-list">
                <?php //您可能感兴趣的产品
			//暂时以我们推荐的产品为基础
			$products_sql = tep_db_query("select op.products_id, p.agency_id, p.products_image, p.products_price, p.products_tax_class_id, ta.agency_name, op.products_model, pd.products_name, sum(op.products_quantity) as quantitysum FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta, " . TABLE_PRODUCTS_DESCRIPTION . " as pd WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id and op.products_id=pd.products_id  group by op.products_id order by quantitysum DESC, op.products_model limit 10,4 ");
			while($products_rows = tep_db_fetch_array($products_sql)){
				$products_rows['productsImage'] = (stripos($products_rows['products_image'],'http://') === false ? 'images/':'') . $products_rows['products_image'];
				if($_SERVER['SERVER_PORT']=='443'){
					$products_rows['productsImage'] = preg_replace('/^http:/i','https:',$products_rows['productsImage']);
				}
			
			?>
			<li>
            	<div class="pic"><?php 
				// 如果文件不存在，并且出现SPAN 则图片上的ALT文字显示不全。 by lwkai add 2012-04-05
				if (file_exists(DIR_FS_CATALOG .$products_rows['productsImage'])) {?>
                	<span></span><?php 
				}?><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" title="<?php echo tep_db_output($products_rows['products_name'])?>"><img src="<?php echo $products_rows['productsImage'];?>" width="154" height="108" alt="<?php echo tep_db_output($products_rows['products_name'])?>"/></a></div>
				<p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>"><?php
				if(tep_not_null($products_rows['products_name'])){ 
					$lwk_title = explode('**',tep_db_output($products_rows['products_name']));
					//print_r($lwk_title);
					if (count($lwk_title) > 1) {
						$subtitle = $lwk_title[1];
					} else {
						$subtitle = '';
					}
					echo cutword($lwk_title[0],46);
				}?></a>
				<p><?php if(tep_not_null($subtitle)){
					echo cutword($subtitle,20);
				}?></p>
                <p class="ui-price">价格：<em><?php echo $currencies->display_price($products_rows['products_price'],tep_get_tax_rate($products_rows['products_tax_class_id']))?></em></p>
				<?php /*?><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>"><?php echo cutword(tep_db_output($products_rows['products_name']),50) ?></a><?php */?></li>
			<?php }?>
                </ul>
            </div>
            <div class="subdiv ui-hide">
                <ul class="ui-route-list">
                <?php //最热销产品
			$products_sql = tep_db_query("select op.products_id, p.agency_id, p.products_image, p.products_price, p.products_tax_class_id, ta.agency_name, op.products_model, pd.products_name, sum(op.products_quantity) as quantitysum FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta, " . TABLE_PRODUCTS_DESCRIPTION . " as pd WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id and op.products_id=pd.products_id  group by op.products_id order by quantitysum DESC, op.products_model limit 0,4 ");
			while($products_rows = tep_db_fetch_array($products_sql)){
				$products_rows['productsImage'] = (stripos($products_rows['products_image'],'http://') === false ? 'images/':'') . $products_rows['products_image'];
				if($_SERVER['SERVER_PORT']=='443'){
					$products_rows['productsImage'] = preg_replace('/^http:/','https:',$products_rows['productsImage']);
				}
			?>			
			<li><div class="pic"><?php
            if(file_exists(DIR_FS_CATALOG . $products_rows['productsImage'])) { ?>
            	<span></span>
			<?php }?><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" title="<?php echo tep_db_output($products_rows['products_name'])?>"><img src="<?php echo $products_rows['productsImage'];?>" width="154" height="108" alt="<?php echo tep_db_output($products_rows['products_name'])?>"/></a></div>
            <p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" title="<?php echo tep_db_output($products_rows['products_name'])?>"><?php
			if(tep_not_null($products_rows['products_name'])) {
				$lwk_title = explode('**',tep_db_output($products_rows['products_name']));
				if (count($lwk_title) > 1) {
					$subtitle = $lwk_title[1];
				}else{
					$subtitle = '';
				}
				echo cutword($lwk_title[0],46);
			}?></a>
            <p><?php if (tep_not_null($subtitle)){
				echo cutword($subtitle,20);	
			}?></p>
            <p class="ui-price">价格：<em><?php echo $currencies->display_price($products_rows['products_price'],tep_get_tax_rate($products_rows['products_tax_class_id']))?></em></p>
            </li>			
			<?php }?>
                </ul>
            </div>
            <div class="subdiv ui-hide">
                
        <ul class="ui-route-list">
       <?php //特价产品
			$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_image, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id from `specials` as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd where s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 limit 4 ");
			while($specials_rows = tep_db_fetch_array($specials_sql)){
				$specials_rows['productsImage'] = (stripos($specials_rows['products_image'],'http://') === false ? 'images/':'') . $specials_rows['products_image'];
				if($_SERVER['SERVER_PORT']=='443'){
					$specials_rows['productsImage'] = preg_replace('/^http:/','https:',$specials_rows['productsImage']);
				}
			?>
			<li><div class="pic"><?php
            if(file_exists(DIR_FS_CATALOG . 'images/' . $specials_rows['products_image'])) { ?>
            	<span></span>
			<?php }?><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo tep_db_output($specials_rows['products_name'])?>"><img src="<?php echo $specials_rows['productsImage'];?>" width="154" height="108" alt="<?php echo tep_db_output($specials_rows['products_name'])?>"/></a></div>
            <p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo tep_db_output($specials_rows['products_name'])?>"><?php
			if(tep_not_null($specials_rows['products_name'])) {
				$lwk_title = explode('**',tep_db_output($specials_rows['products_name']));
				if (count($lwk_title) > 1) {
					$subtitle = $lwk_title[1];
				}else{
					$subtitle = '';
				}
				echo cutword($lwk_title[0],46);
			}?></a>
            <p><?php if (tep_not_null($subtitle)){
				echo cutword($subtitle,20);	
			}?></p>
            <p class="ui-price">价格：<em><?php echo $currencies->display_price($specials_rows['specials_new_products_price'],tep_get_tax_rate($specials_rows['products_tax_class_id']))?></em>&nbsp;&nbsp;<del><?php echo $currencies->display_price($specials_rows['products_price'], tep_get_tax_rate($specials_rows['products_tax_class_id']))?></del></p>
            </li>
			<?php }?>
                </ul>
            </div>
        </div>
    </div>
      <?php 
	  // by lwkai 2012-04-05
	  //原来的老HTML start {
      /*
      <div class="showRoute">
        <div class="tab">
          <a href="javascript:;" class="sel">您可能感兴趣的行程</a>
          <a href="javascript:;">最热销行程</a>
          <a href="javascript:;">特价行程</a>
        </div>
        <ul class="list">
			<?php //您可能感兴趣的产品
			//暂时以我们推荐的产品为基础
			$products_sql = tep_db_query("select op.products_id, p.agency_id, p.products_price, p.products_tax_class_id, ta.agency_name, op.products_model, op.products_name, sum(op.products_quantity) as quantitysum, sum(op.final_price*op.products_quantity)as gross FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id  group by op.products_id order by quantitysum DESC, op.products_model limit 10,5 ");
			while($products_rows = tep_db_fetch_array($products_sql)){
			?>
			<li><span><?php echo $currencies->display_price($products_rows['products_price'],tep_get_tax_rate($products_rows['products_tax_class_id']))?></span><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>"><?php echo cutword(tep_db_output($products_rows['products_name']),50) ?></a></li>
			<?php }?>
		</ul>          
        <ul class="list" style="display:none;">
         <?php //最热销产品
			$products_sql = tep_db_query("select op.products_id, p.agency_id, p.products_price, p.products_tax_class_id, ta.agency_name, op.products_model, op.products_name, sum(op.products_quantity) as quantitysum, sum(op.final_price*op.products_quantity)as gross FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id  group by op.products_id order by quantitysum DESC, op.products_model limit 5 ");
			while($products_rows = tep_db_fetch_array($products_sql)){
			?>			
			<li><span><?php echo $currencies->display_price($products_rows['products_price'],tep_get_tax_rate($products_rows['products_tax_class_id']))?></span><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" ><?php echo cutword(tep_db_output($products_rows['products_name']),50) ?></a></li>			
			<?php }?>
        </ul>
        <ul class="list" style="display:none;">
          <?php //特价产品
			$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id from `specials` as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd where s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 limit 5 ");
			while($specials_rows = tep_db_fetch_array($specials_sql)){
			?>
			<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo tep_db_output($specials_rows['products_name'])?>" class="text"><?php echo cutword(tep_db_output($specials_rows['products_name']),70)?></a>  <span  style="color:#BE391A"><?php echo $currencies->display_price($specials_rows['specials_new_products_price'],tep_get_tax_rate($specials_rows['products_tax_class_id']))?>&nbsp;</span>&nbsp;<span class="off_sale2" style="color:#999999"><?php echo $currencies->display_price($specials_rows['products_price'], tep_get_tax_rate($specials_rows['products_tax_class_id']))?>&nbsp;</span></li>
			<?php }?>
        </ul>
      </div>
      */
      // } 原来的老HTML end ?>
      <!--行程tab注释 end-->


      
 <?php echo db_to_html(ob_get_clean());?>
 
<script type="text/javascript">
//add by _Afei
jQuery(document).ready(function(e) {
    jQuery(".ui-route-tabmenu a").click(function (){
		jQuery(".ui-route-tabmenu a").removeClass();
		jQuery(this).addClass("ui-current");
		jQuery(".ui-route-content").find(".subdiv").hide();
		jQuery(".ui-route-content").find(".subdiv").eq(jQuery(".ui-route-tabmenu a").index(this)).show();
	});
 
});


function setCookie(name , value , expire){
	if(typeof(expire) == 'undefined'){
		document.cookie = name + "="+ escape (value);
	}else{
		var exp  = new Date();
		exp.setTime(exp.getTime() +expire*1000);
		document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
	}
}

function getCookie(name){
	var strcookie = document.cookie ;
	var value = '';
	arrcookie = strcookie.split(";") ;
	for(var i=0;i<arrcookie.length;i++){
		var arr=arrcookie[i].split("=");
		if(name==arr[0]){
			value=unescape(arr[1]);
			 break;
		}
	}
	return value ;
}
</script>
