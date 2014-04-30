<?php
require_once('includes/application_top.php');
//结伴同游子订单编辑更新系统

//付款模块
require_once(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment;
$selection = $payment_modules->selection();


//ajax 操作结果
if($_GET['ajax_action']=='1'){
//echo $_GET['ajax_action'];exit;

	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	require(DIR_WS_INCLUDES . 'ajax_encoding_control.php');
	require_once(DIR_WS_CLASSES . 'currencies.php');
	$currencies = new currencies();
	$ajax = 'true';
	if($_POST['ajax_send']=='true'){
		foreach($_POST['orders_travel_companion_id'] as $key => $val){
			$sql_date_array = array();
			if(isset($_POST['guest_name'])){
				$sql_date_array['guest_name'] = ajax_to_general_string($_POST['guest_name'][$val]);
			}
			if(isset($_POST['date_of_birth'])){
				$sql_date_array['date_of_birth'] = ajax_to_general_string($_POST['date_of_birth'][$val]);
			}
			if(isset($_POST['orders_travel_companion_status'])){
				$sql_date_array['orders_travel_companion_status'] = ajax_to_general_string($_POST['orders_travel_companion_status'][$val]);
			}
			if(isset($_POST['payables'])){
				$sql_date_array['payables'] = ajax_to_general_string($_POST['payables'][$val]);
			}
			if(isset($_POST['paid'])){
				$sql_date_array['paid'] = ajax_to_general_string($_POST['paid'][$val]);
				//如果已经部分付款就修改状态为已经部分付款
				if($sql_date_array['paid']>0 && $sql_date_array['paid'] < $sql_date_array['payables']){
					$sql_date_array['orders_travel_companion_status'] = '3';	//部分付款
				}elseif($sql_date_array['paid'] >= $sql_date_array['payables']){
					$sql_date_array['orders_travel_companion_status'] = '2';	//已付款
				}
			}
			if(isset($_POST['payment_description'])){
				$sql_date_array['payment_description'] = ajax_to_general_string($_POST['payment_description'][$val]);
			}
			
			if(isset($_POST['payment'])){
				$sql_date_array['payment'] = ajax_to_general_string($_POST['payment'][$val]);
				
				for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
					if($selection[$i]['id']==$_POST['payment'][$val]){
						$payment_names = strip_tags($selection[$i]['module']);
						$payment_names = str_replace('&nbsp;','',$payment_names);
						$payment_names = preg_replace('/（.*|\(.*/','',$payment_names);

						$sql_date_array['payment_name'] = $payment_names;
					}
				}
				
				if($sql_date_array['payment']==''){
					$sql_date_array['payment_name'] = '未知';
				}
			}
			
			if(isset($_POST['products_id'])){
				$sql_date_array['products_id'] = ajax_to_general_string($_POST['products_id'][$val]);
			}
			
			$sql_date_array['last_modified'] = date('Y-m-d H:i:s');
			
			//更新结伴同游子订单
			tep_db_perform('orders_travel_companion', $sql_date_array,'update',' orders_travel_companion_id="'.(int)$val.'" ');
			
		}
		
		//更新orders_product_eticket表
		$guest_names = array();
		foreach((array)$_POST['products_id'] as $key => $val){
			if(isset($_POST['guest_name']) ){
				foreach($_POST['guest_name'] as $gkey => $gval){
					if($gkey==$key){
						$c_date_of_birth ="";
						if(isset($_POST['date_of_birth'][$gkey]) && $_POST['date_of_birth'][$gkey]!=""){
							$c_date_of_birth = '||'.trim($_POST['date_of_birth'][$gkey]);
						}
						$guest_names[$val].= ajax_to_general_string($gval).$c_date_of_birth.'<::>';
					}
					
				}
			}
		}
		foreach((array)$guest_names as $key => $val){
			if(!tep_not_null(str_replace('<::>','',$val))){ $val="无客人姓名orders_travel_comp.php [".(int)$login_id."]<::>";}
			tep_db_query('update orders_product_eticket set guest_name="'.$val.'" where orders_id="'.(int)$oID.'" AND products_id="'.(int)$key.'" ');
		
		}
		//更新orders_product_eticket表 end
		
		//给结伴同游成员发邮件
		if($_POST['send_mail_travel']=='1'){
			send_travel_pay_staus_mail($oID);
		}

		
	}

	$oID = (int)$_GET['oID'];
	$ajax_otc_sql = tep_db_query('SELECT * FROM `orders_travel_companion` WHERE orders_id="'.(int)$oID.'" ORDER BY products_id ');
	$ajax_otc_rows = tep_db_fetch_array($ajax_otc_sql);
	if((int)$ajax_otc_rows['orders_travel_companion_id']){

?>
<!--<div id="orders_travel_comp_show">-->
	<table border="1" cellspacing="0" cellpadding="5">
      <tr>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('团号')?></b></td>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('旅客帐号')?></b></td>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('旅客姓名')?></b></td>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('应付款')?></b></td>
        <td class="tab_t tab_line1">已付</td>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('状态')?></b></td>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('付款方式')?></b></td>
        <td class="tab_t tab_line1"><b><?php echo db_to_html('备注')?></b></td>
      </tr>
      <?php
	  $n_pid='';
	  $_paid = 0;
	  do{
	  	$_paid += $ajax_otc_rows['paid'];
	  ?>
	  <tr>
		<td nowrap="nowrap" class="tab_line1 p_t">
		<?php
		if($n_pid != $ajax_otc_rows['products_id']){
			echo tep_get_products_model($ajax_otc_rows['products_id']);
		}
		?>
&nbsp;		</td>
		<td nowrap="nowrap" class="tab_line1 p_t"><?php echo tep_get_customers_email((int)$ajax_otc_rows['customers_id'])?>&nbsp;</td>
		<td nowrap="nowrap" class="tab_line1 p_t">
		<?php
		echo db_to_html(tep_db_output($ajax_otc_rows['guest_name']));
		//如果是儿童则应显示出生日期
		if($ajax_otc_rows['is_child']=='true'){
			echo '<br>'.db_to_html(tep_db_output($ajax_otc_rows['date_of_birth']));
		}
		?>		</td>
        <td nowrap="nowrap" class="tab_line1 p_t"><?php echo $currencies->format($ajax_otc_rows['payables'], true, 'USD', 1)?></td>
        <td nowrap="nowrap" class="tab_line1 p_t"><?php echo $currencies->format($ajax_otc_rows['paid'], true, 'USD', 1)?></td>
        <td nowrap="nowrap" class="tab_line1 p_t"><?php echo get_travel_companion_status($ajax_otc_rows['orders_travel_companion_status'])?></td>
        <td nowrap="nowrap" class="tab_line1 p_t">
		<?php
		echo $ajax_otc_rows['payment_name'];
		?>
		&nbsp;</td>
        <td class="tab_line1 p_t">
		<?php
		echo nl2br($ajax_otc_rows['payment_description']);
		?>&nbsp;</td>
	  </tr>
	  <?php
	  	$n_pid = $ajax_otc_rows['products_id'];
	  }while($ajax_otc_rows = tep_db_fetch_array($ajax_otc_sql));
	  ?>
	  <tr>
	  <td colspan="4" align="right">合计：</td>
	  <td colspan="4" align="left">&nbsp;<b><?php echo $currencies->format($_paid, true, 'USD', 1);?></b>(仅作参考用)</td>
	  </tr>
    </table>
	<!--</div>-->
<?php
	}
	exit;
}
//ajax 操作结果

?>

<?php

//(int)$oID;
$otc_sql = tep_db_query('SELECT * FROM `orders_travel_companion` WHERE orders_id="'.(int)$oID.'" ORDER BY products_id ');
$otc_rows = tep_db_fetch_array($otc_sql);
if((int)$otc_rows['orders_travel_companion_id']){
?>
<script type="text/javascript">
function show_or_edit(){
	var comp_show = document.getElementById('orders_travel_comp_show');
	var comp_edit = document.getElementById('orders_travel_comp_edit');
	if(comp_edit.style.display=="none"){
		comp_edit.style.display = "";
		comp_show.style.display = "none";
	}else{
		comp_edit.style.display = "none";
		comp_show.style.display = "";
	}
}

function SubmitTravelComp(){
	var TravelSubmit = document.getElementById('TravelSubmit');
	TravelSubmit.disabled = true;
	var loading_img = document.getElementById('loading_img');
	loading_img.style.display ="";
	var form_orders = document.getElementById('form_orders_travel_comp');
	var url = "<?php echo preg_replace($p,$r,tep_href_link('orders_travel_comp.php','ajax_action=1&oID='.(int)$oID)) ?>";
	var aparams=new Array();

	for(i=0; i<form_orders.elements.length; i++){
		
		if( ((form_orders.elements[i].type=='checkbox' || form_orders.elements[i].type=='radio') && form_orders.elements[i].checked==true) || (form_orders.elements[i].type!='checkbox' && form_orders.elements[i].type!='radio' )){	//处理复选框和单选的值
			var sparam=encodeURIComponent(form_orders.elements[i].name);
			sparam+="=";
			sparam+=encodeURIComponent(form_orders.elements[i].value);
			aparams.push(sparam);

		}else if(form_orders.elements[i].type!='checkbox' && form_orders.elements[i].type!='radio' ){

			var sparam=encodeURIComponent(form_orders.elements[i].name);  //取得表单元素名
			sparam+="=";     //名与值之间用"="号连接
			sparam+=encodeURIComponent(form_orders.elements[i].value);   //获得表单元素值
			aparams.push(sparam);   //push是把新元素添加到数组中去
		
		}
	}
	
	sparam+= '&ajax_send=true';
	aparams.push(sparam);
	
	var post_str=aparams.join("&");		//使用&将各个元素连接

	ajax.open("post", url, true);
	//定义传输的文件HTTP头信息
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str); 

	var comp_show = document.getElementById('orders_travel_comp_show');
	var comp_edit = document.getElementById('orders_travel_comp_edit');
	var send_mail_travel = document.getElementById('send_mail_travel');
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200) { 
			comp_show.innerHTML = ajax.responseText;
			comp_show.style.display = "";
			comp_edit.style.display = "none";
			loading_img.style.display = "none";
			send_mail_travel.checked = false;
			TravelSubmit.disabled = false;
		}
	}

}
</script>

<?php
$travels_layers

?>
<div class="popup" id="jbtyList" onClick="findLayer('jbtyList')" >
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
			<td class="con">
			  <div class="popupCon" id="jbtyList_popupCon" style="width:960px">
				<div class="popupConTop" id="jbtyList_drag" ondblclick="changeCon(document.getElementById('jbtyList_H_title_top'),'jbtyList','jbtyList_LayerBody');">
				  <h4><b><?php echo db_to_html('结伴同游子订单')?></b>
				  <?php if($can_edit_jiebantongyou_pay === true){?>
				  <a style="color:#FF3399" href="javascript:void(0);" onClick="show_or_edit();">[edit]</a>
				  <?php }?>
				  </h4>
				  <div class="popupClose" onClick="document.getElementById('jbtyList').style.display='none'"><img src="images/icons/icon_x.gif" alt="close"/></div>
				  <div class="popupChange" title="最小化/还原" id="jbtyList_H_title_top" onClick="changeCon(this,'jbtyList','jbtyList_LayerBody');">-</div>
				</div>
				<div id="jbtyList_LayerBody">
				
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main">&nbsp;</td>
					  </tr>
					  <tr>
						<td>
						
						<div id="orders_travel_comp_show">
						<table border="1" cellspacing="0" cellpadding="5">
						  <tr>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('团号')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('旅客帐号')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('旅客姓名')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('应付款')?></b></td>
							<td class="tab_t tab_line1">已付</td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('状态')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('付款方式')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('备注')?></b></td>
						  </tr>
						  <?php
						  $n_pid='';
						  $_paid = 0;
						  do{
							$_paid+=$otc_rows['paid'];
						  ?>
						  <tr>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							if($n_pid != $otc_rows['products_id']){
								echo tep_get_products_model($otc_rows['products_id']);
							}
							?>
					&nbsp;		</td>
							<td nowrap="nowrap" class="tab_line1 p_t"><?php echo tep_get_customers_email((int)$otc_rows['customers_id'])?>&nbsp;</td>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							echo db_to_html(tep_db_output($otc_rows['guest_name']));
							//如果是儿童则应显示出生日期
							if($otc_rows['is_child']=='true'){
								echo '<br>'.db_to_html(tep_db_output($otc_rows['date_of_birth']));
							}
							?>		</td>
							<td nowrap="nowrap" class="tab_line1 p_t"><?php echo $currencies->format($otc_rows['payables'], true, 'USD', 1)?></td>
							<td nowrap="nowrap" class="tab_line1 p_t"><?php echo $currencies->format($otc_rows['paid'], true, 'USD', 1)?></td>
							<td nowrap="nowrap" class="tab_line1 p_t"><?php echo get_travel_companion_status($otc_rows['orders_travel_companion_status'])?></td>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							echo $otc_rows['payment_name'];
							?>
							&nbsp;</td>
							<td class="tab_line1 p_t">
							<?php
							echo nl2br($otc_rows['payment_description']);
							?>
							&nbsp;</td>
						  </tr>
						  <?php
							$n_pid = $otc_rows['products_id'];
						  }while($otc_rows = tep_db_fetch_array($otc_sql));
						  ?>
						  <tr>
						  <td colspan="4" align="right">合计：</td>
						  <td colspan="4" align="left">&nbsp;<b><?php echo $currencies->format($_paid, true, 'USD', 1);?></b>(仅作参考用)</td>
						  </tr>

						</table>
						</div>
						
						<div id="orders_travel_comp_edit" style="display:<?php echo "none"?>;">
						<form method="post" name="form_orders_travel_comp" id="form_orders_travel_comp" onSubmit="SubmitTravelComp(); return false;">
						<table border="1" cellspacing="0" cellpadding="5">
						  <tr>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('团号')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('旅客帐号')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('旅客姓名')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('应付款')?></b></td>
							<td class="tab_t tab_line1">已付</td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('状态')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('付款方式')?></b></td>
							<td class="tab_t tab_line1"><b><?php echo db_to_html('备注')?></b></td>
						  </tr>
						  <?php
						  //$edit_otc_sql = tep_db_query('SELECT * FROM `orders_travel_companion` WHERE orders_id="'.(int)$oID.'" ORDER BY products_id ');
						  //內部移动指针到开头
						  if(tep_db_data_seek($otc_sql,0) == true){
						  
							  $edit_otc_rows = tep_db_fetch_array($otc_sql);
							  $n_pid='';
							  do{
								$Oinfo = new objectInfo($edit_otc_rows);
								$orders_travel_companion_id = $Oinfo ->orders_travel_companion_id;
								$products_id = $Oinfo ->products_id;
								$guest_name = $Oinfo ->guest_name;
								$customers_id = $Oinfo ->customers_id;
								$is_child = $Oinfo ->is_child;
								$date_of_birth = $Oinfo ->date_of_birth;
								$payables = $Oinfo ->payables;
								$paid = $Oinfo ->paid;
								$orders_travel_companion_status = $Oinfo ->orders_travel_companion_status;
								$products_id = $Oinfo ->products_id;
						  ?>
						  <tr>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							if($n_pid != $products_id){
								echo tep_get_products_model($products_id);
							}
							?>
							
							&nbsp;<input name="orders_travel_companion_id[<?= (int)$orders_travel_companion_id?>]" type="hidden" id="orders_travel_companion_id[<?= (int)$orders_travel_companion_id?>]" value="<?= (int)$orders_travel_companion_id?>">
							<input name="products_id[<?= (int)$orders_travel_companion_id?>]" type="hidden" id="products_id[<?= (int)$orders_travel_companion_id?>]" value="<?= (int)$products_id?>">		</td>
							<td nowrap="nowrap" class="tab_line1 p_t"><?php echo tep_get_customers_email((int)$customers_id)?>&nbsp;</td>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							echo tep_draw_input_field('guest_name['.(int)$orders_travel_companion_id.']',$guest_name ,'size="20"');
							if($is_child=='true'){
								echo tep_draw_input_field('date_of_birth['.(int)$orders_travel_companion_id.']',$date_of_birth ,'size="12" maxlength="10"');
							}
							?>		</td>
							<td nowrap="nowrap" class="tab_line1 p_t">
							$<?php echo tep_draw_input_field('payables['.(int)$orders_travel_companion_id.']',$payables ,'size="6" ');?>		</td>
							<td nowrap="nowrap" class="tab_line1 p_t">$<?php echo tep_draw_input_field('paid['.(int)$orders_travel_companion_id.']',$paid ,'size="6" ');?></td>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							$option_array = array();
							$option_array[0] = array('id'=>'0','text'=>'未付款');
							$option_array[1] = array('id'=>'1','text'=>'等待审核');
							$option_array[2] = array('id'=>'2','text'=>'付款完成');
							$option_array[3] = array('id'=>'3','text'=>'已部分付款');
							
							echo tep_draw_pull_down_menu('orders_travel_companion_status['.(int)$orders_travel_companion_id.']', $option_array, (int)$orders_travel_companion_status);
							?>		</td>
							<td nowrap="nowrap" class="tab_line1 p_t">
							<?php
							
							$option_array = array();
							$sel_payment = $edit_otc_rows['payment'];
							
							$option_array[0] = array('id'=>'','text'=>'未知');
							for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
								$payment_name = strip_tags($selection[$i]['module']);
								$payment_name = str_replace('&nbsp;','',$payment_name);
								//$payment_name = preg_replace('/（.*|\(.*/','',$payment_name);
					
								$option_array[($i+1)] = array('id'=>$selection[$i]['id'],'text'=>$payment_name);
							}
							
							//echo $edit_otc_rows['payment_name'];
							echo tep_draw_pull_down_menu('payment['.(int)$orders_travel_companion_id.']', $option_array, $sel_payment);
							?>
							&nbsp;</td>
							<td class="tab_line1 p_t">
							<?php
							echo tep_draw_textarea_field('payment_description['.(int)$orders_travel_companion_id.']', 'virtual', '30', '4', $edit_otc_rows['payment_description']);
							?>
							</td>
						  </tr>
						  <?php
								$n_pid = $products_id;
							  }while($edit_otc_rows = tep_db_fetch_array($otc_sql));
						  }
						  ?>
						  <tr>
							<td class="tab_line1 p_t">&nbsp;</td>
							<td class="tab_line1 p_t">&nbsp;</td>
							<td colspan="4" align="right" class="tab_line1 p_t"><img id="loading_img" style="display:<?php echo 'none'?>" src="images/loading.gif" align="absmiddle" />
							  <input type="submit" name="TravelSubmit" id="TravelSubmit" value="update">
							  <?php $send_mail_travel='';?>
							  <input name="send_mail_travel" type="checkbox" id="send_mail_travel" value="1" /><?php echo db_to_html("通知客户");?>
							  <input name="" type="reset" value="cancel" onClick="show_or_edit();">	
							  <div class="col_red_b">如果您修改了结伴同游成员的“应付款”，请不要忘记同样也要修改此订单的 总计。</div>
							  </td>
							<td class="tab_line1 p_t">&nbsp;</td>
							<td class="tab_line1 p_t">&nbsp;</td>
						  </tr>
						</table>
					
						</form>
						</div>
						</td>
					  </tr>
					</table>
				
				</div>
			 </div>
	 </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
	  <iframe class="iframeBg" frameborder="0"></iframe>
	</div>
<?php

}
?>