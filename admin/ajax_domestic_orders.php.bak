<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
require_once('includes/application_top.php');
require_once(DIR_WS_FUNCTIONS . 'timezone.php');
header("Content-type: text/html; charset=" . CHARSET);

//电子邮件的签名信息
//$login_publicity_name = ucfirst(get_login_publicity_name());
$login_publicity_name = tep_get_admin_customer_name($login_id);
$phones = tep_get_us_contact_phone();
$EmailSignature = sprintf(EMAIL_FOOTER_SIGNATURE, $login_publicity_name, $phones[0]['name'].$phones[0]['phone'].'  '.$phones[2]['name'].$phones[2]['phone']);

//
$oID = $orders_id = ((int)$_POST['orders_id']) ? (int)$_POST['orders_id'] : (int)$_GET['orders_id'];
$check_orders_query = tep_db_query("select * from  orders  where orders_id = '" . (int)$oID . "'");
$check_orders = tep_db_fetch_array($check_orders_query);


//当订单支付方式更改为其他支付方式时，将订单从转账支付系统中移出去 start
if($_GET['orders_payment_method_changed_check']=="1"){
	$sql = tep_db_query('SELECT orders_id FROM `domestic_orders` ');
	while($rows=tep_db_fetch_array($sql)){
		$order_sql = tep_db_query('SELECT payment_method FROM `orders` WHERE orders_id="'.$rows['orders_id'].'" ');
		$order_row = tep_db_fetch_array($order_sql);
		if($order_row['payment_method']!="银行转账(中国)"){
			tep_db_query('DELETE FROM `domestic_orders` WHERE `orders_id` = "'.$rows['orders_id'].'" ');
			tep_db_query('DELETE FROM `domestic_orders_history` WHERE `orders_id` = "'.$rows['orders_id'].'" ');
			echo "remove ".$rows['orders_id']." ok"."\n";
		}
	}
	exit;
}
//当订单支付方式更改为其他支付方式时，将订单从转账支付系统中移出去 end


$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
$currency_query = tep_db_query("select * from  currencies where code='CNY'");
$currency_rows = tep_db_fetch_array($currency_query);
$new_rate = $currency_rows['value'];

//ajax不刷新页面重新加载每个订单的详情
if ($_GET['action'] == 'reload_orders_details' && $error == false) {
    $orders_id = (int) $_GET['orders_id'];
    //j检查该订单是不是已经是100006状态，如果是不进行任何操作。
    $check_db = tep_db_query('SELECT * FROM `orders` WHERE orders_id = "' . $orders_id . '"');
    $check_rows = tep_db_fetch_array($check_db);

    //查银行
    $bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' . $orders_id . '"');
    $bank_rows = tep_db_fetch_array($bank_query);
    //判断该订单是否已经编辑过状态,如果该订单已经在domestic_orders表则，orders_status数据从domestic_orders里取。
    $orders_status_now = '';
    $ajax_checked = '';
    //查人民币收款的
   $rmb_query=tep_db_query('select text from orders_total where orders_id="'.$orders_id.'" and class="ot_total"');
   $rmb_rows = tep_db_fetch_array($rmb_query);
   $rmb_text = $rmb_rows['text'];
    if ((int) $bank_rows['orders_id'] && $check_rows['orders_status'] != '100006') {

	//查询改订单的操作历史
    $operation_history_query = tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . $orders_id . '"ORDER BY `update_time` ASC');
    //$operation_history_rows_1 = tep_db_fetch_array($operation_history_query);
    $admin_is_see_query = tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . $rows['orders_id'] . '" AND admin_id ="' . $login_admin_id . '"ORDER BY `update_time` ASC');
    //$admin_is_see_rows = tep_db_fetch_array($admin_is_see_query);

    $checked_sms = '';

    //$bank_rows_realcharge_total =' ';
    /*
    if($bank_rows['money_type']=='美元'){
        if($bank_rows['real_charge']!=NULL&&$bank_rows['real_charge']!='0'){
            $bank_rows_realcharge_total ='$'.number_format($bank_rows['real_charge'],2,'.','');
            $bank_rows_realcharge_total_table =number_format($bank_rows['real_charge'],2,'.','');
        }else{
            $bank_rows_realcharge_total = $bank_rows['real_charge'];
        }
    }else if($bank_rows['money_type']=='人民币'){
        if($bank_rows['RMB']!=NULL&&$bank_rows['RMB']!='0'){
            $bank_rows_realcharge_total  = '￥'.number_format($bank_rows['RMB'],2,'.','');
            $bank_rows_realcharge_total_table = number_format($bank_rows['RMB'],2,'.','');
        }else{
             $bank_rows_realcharge_total =$bank_rows['RMB'];
        }
    }*/
    if($bank_rows['real_charge']!=NULL&&$bank_rows['real_charge']!='0'){
        $bank_rows_realcharge_total_dollar ='$'.number_format($bank_rows['real_charge'],2,'.','');
        $bank_rows_realcharge_total_table_dollar =number_format($bank_rows['real_charge'],2,'.','');
    }else{
        $bank_rows_realcharge_total_dollar = $bank_rows['real_charge'];
    }
    if($bank_rows['RMB']!=NULL&&$bank_rows['RMB']!='0'){
        $bank_rows_realcharge_total_rmb  = '￥'.number_format($bank_rows['RMB'],2,'.','');
        $bank_rows_realcharge_total_table_rmb = number_format($bank_rows['RMB'],2,'.','');
    }else{
         $bank_rows_realcharge_total_rmb =$bank_rows['RMB'];
    }
    
    
     $yingShouRmb ='';
     $yingShouRmb_rows ='';
        if($bank_rows['value_rmb']!=NULL&&$bank_rows['value_rmb']!='0'){
              $yingShouRmb = number_format($bank_rows['value_rmb'],2,'.','');
               $yingShouRmb_rows = '￥'.$yingShouRmb;
        }else{
            $yingShouRmb = $bank_rows['value_rmb'];
            $yingShouRmb_rows =$yingShouRmb;
        }
      $charge_date_rows = '';
      if(substr($bank_rows['charge_date'],0,10)!='0000-00-00'&&substr($bank_rows['charge_date'],0,10)!=null){
          $charge_date_rows =  substr($bank_rows['charge_date'],0,10);
      }

    //$bank_rows_dransfer_total_rmb = $bank_rows['dransfer_total_rmb'];
    //if ($bank_rows['dransfer_total'] != NULL && $bank_rows['dransfer_total'] != '0' && (int) $bank_rows['orders_id']) {
       // $bank_rows_dransfer_total = '$' . $bank_rows['dransfer_total'];
   // }
   
	//如果美元支付，应收总金额和实收金额比较是否一致，如果人民币支付，应收人民币和实收金额比较不符，实收金额是红的
	if(tep_not_null($bank_rows_realcharge_total_dollar) || tep_not_null($bank_rows_realcharge_total_rmb)){
		$yingShouUsdTotal = '$' . number_format($bank_rows['value'],2,'.','');
		if($bank_rows_realcharge_total_rmb!=$yingShouRmb_rows && $bank_rows_realcharge_total_dollar!=$yingShouUsdTotal){
			//$bank_rows_realcharge_total = '<span style=color:#F00>'.$bank_rows_realcharge_total.'</span>';
             $bank_rows_realcharge_total_str = '<span style=\'color:#F00\'>';
            if ($bank_rows_realcharge_total_dollar != '$0.00' ){
                $bank_rows_realcharge_total_str .= $bank_rows_realcharge_total_dollar;
            }                                           
            if ($bank_rows_realcharge_total_rmb != '￥0.00'){
                $bank_rows_realcharge_total_str .= '('.$bank_rows_realcharge_total_rmb.')';
            }
            $bank_rows_realcharge_total_str .= '</span>';
		}
	}
    
	$disabled_submit = '';


    if ((int) $bank_rows['orders_status'] == '100054') {
        $ajax_checked = 'var radioId = document.getElementById("paid_status_1_' . $orders_id . '"); radioId.setAttribute("checked","checked");';

        //$ajax_checked = '$("#orders_edit_'.$orders_id.':input[type=radio]").attr("checked","unpaid_0"); ';
    }
    if ((int) $bank_rows['orders_status'] == '100027') {
        $ajax_checked = 'var radioId = document.getElementById("paid_status_3_' . $orders_id . '"); radioId.setAttribute("checked","checked");';


        //$ajax_checked = '$("#orders_edit_'.$orders_id.':input[@type=radio]").attr("checked","paid_1"); ';
    }
    if ((int) $bank_rows['orders_status'] == '100071') {

        // $ajax_checked = '$("#orders_edit_'.$orders_id.':input[@type=radio]").attr("checked","receiving_3"); ';
        $ajax_checked = 'var radioId = document.getElementById("paid_status_2_' . $orders_id . '"); radioId.setAttribute("checked","checked");';
    }
   $ajax_url = preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=autoEmail'));
    if ((int) $bank_rows['orders_id'] && (int) tep_db_num_rows($operation_history_query)) {
        $replace_table = "<td colspan='5'  id='table_style_$orders_id' class='TabFg'>";
    } else {
        $replace_table = "<td colspan='5'  id='table_style_$orders_id' style='display:none' class='TabFg'>";
    }
    $replace_table.="<table class='DdTabXxCaozuo' border='0'  cellspacing='0' cellpadding='0'>
                                                                                             <tr>
                                                                                                <th>操作人</th>
                                                                                                <th>更新状态</th>
                                                                                               
                                                                                                <th>实际收款</th>
                                                                                                <th>更新时间</th>
                                                                                                <th>备注</th>
                                                                                                <th>邮件状态</th>
                                                                                                <th style='display:none'>ID</th>
                                                                                            </tr>";
    while ($operation_history_rows = tep_db_fetch_array($operation_history_query)) {
        $replace_table.="<tr>";
        $replace_table.="<td>" . ajax_to_general_string(tep_get_admin_customer_name($operation_history_rows['admin_id'])) . "</td>";
        $replace_table.="<td>" . ajax_to_general_string($operation_history_rows['manager_history']) . "</td>";
       
        $replace_table.="<td>" . ajax_to_general_string($operation_history_rows['collected']) . "</td>";
        $replace_table.="<td>" . ajax_to_general_string($operation_history_rows['update_time']) . "</td>";
        $replace_table.="<td>" . ajax_to_general_string($operation_history_rows['notes']) . "</td>";
         if((int)$operation_history_rows['email_sented']){
             $replace_table.="<td class='send-email-notcic'>已发送邮件</td>";
        }else{
                                    $replace_table.="<td id='td_email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."'><input type='button' class='AllbuttonHui' style='padding-left:5px; padding-right:5px; ' value='未发送邮件'  id='email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."'><script type='text/javascript'>$('#email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."').bind('click',function(){ $('#notic_id_pop_" . $orders_id . "').trigger('click');});</script></td>";

        }
                /*$replace_table.="<td style='display:none'><input type='text' value='".$operation_history_rows['history_id']."' id='history_id_".$operation_history_rows['history_id']."_".$operation_history_rows['orders_id']."'><script type='text/javascript'>$('#email_sented_".$operation_history_rows['orders_id']."').bind('click',function(){ $('#notic_id_pop_" . $orders_id . "').trigger('click');});</script></td>";*/
               $replace_table.="<td style='display:none'><input type='text' value='".$operation_history_rows['history_id']."' id='history_id_".$operation_history_rows['history_id']."_".$operation_history_rows['orders_id']."'></td>";

        $replace_table.="</tr>";
    }
    $replace_table.="</table></td>";
    // $replace_table. = '<td colspan="5" style="' . $history_table_style . '" id="table_style_' . $rows['orders_id'] . '><table  border="0" width="60%" cellspacing="1" cellpadding="2"><tr class="dataTableHeadingRow"><td class="dataTableContent" nowrap="nowrap">操作人</td><td class="dataTableContent" nowrap="nowrap">更新状态</td><td class="dataTableContent" nowrap="nowrap">转账金额</td><td class="dataTableContent" nowrap="nowrap">实际收款</td><td class="dataTableContent" nowrap="nowrap">更新时间</td><td class="dataTableContent" nowrap="nowrap">备注</td></tr></td>';
    //$replace_table .= "<td colspan='5'>132</>";
  $orders_status_name='';
  $orders_status_name =tep_get_orders_status_name($bank_rows['orders_status']);
   
  // $replace_update = "<input id='orders_status_submit_id_$orders_id'  name='orders_status_submit_name'  type='button' value='".ajax_to_general_string('更新')."'  style='margin-top:10px;' class='Allbutton_tom'>";
   $sms_admin='';
  if ($admin_type != 'service' && $admin_type != false){
      $sms_admin .=  'form_id.elements["actual_collection_dollar"].value ="' .$bank_rows_realcharge_total_table_dollar . '";';
      $sms_admin .=  'form_id.elements["actual_collection_rmb"].value ="' .$bank_rows_realcharge_total_table_rmb . '";';
      //$sms_admin .=' form_id.elements["money_type"].value ="' . tep_db_output(html_to_db(ajax_to_general_string($bank_rows['money_type']))) . '";';
  }
    
	if($bank_rows['orders_status']=="1" || $bank_rows['orders_status']=="100052" || $bank_rows['orders_status']=="100054" || $bank_rows['orders_status']=="100071" || $bank_rows['orders_status']=="100027"){
		$to_html_orders_status_name = str_replace('（Chinese Account）','',$orders_status_name);
	}else{
		$to_html_orders_status_name = '<span style=color:#CCCCCC>'.$orders_status_name.'</span>';
	}
	
	$sms = '$("#dransfer_total_row_' . $orders_id . '").html("' .$bank_rows_realcharge_total_str. '");';
	
	if(tep_not_null($yingShouRmb_rows)){
		$sms.= '$("#dransfer_rmb_row_' . $orders_id . '").html("' .$yingShouRmb_rows. '");';
	}
	
	$sms.= '$("#dransfer_bank_row_' . $orders_id . '").html("' . tep_db_output(html_to_db(ajax_to_general_string($bank_rows['bank']))) . '");
			/*$("#orders_status_row_' . $orders_id . '").html("' . html_to_db(ajax_to_general_string($to_html_orders_status_name)) . '");*/
			$("#charge_date_' . $orders_id . '").html("' .$charge_date_rows . '");
			var form_id =  document.getElementById("orders_edit_' . $orders_id . '");
			form_id.elements["payer_name"].value ="' .$bank_rows['payer'] . '";
			
			form_id.elements["select_collection_time"].value ="' . tep_db_output(html_to_db(ajax_to_general_string($charge_date_rows))) . '";
			form_id.elements["bank_type"].value ="' . tep_db_output(html_to_db(ajax_to_general_string($bank_rows['bank']))) . '";
			form_id.elements["status_type"].value ="'.tep_db_output(html_to_db(ajax_to_general_string($orders_status_name))) .'";
			$("#table_style_' . $orders_id . '").replaceWith("' . $replace_table . '");
			$("#tom_status_' . $orders_id . '").val("0");
			$("#notic_status_' . $orders_id . '").hide();
			$("#rows_bk_id").val("'.$orders_id.'");
			$("#notes_' . $orders_id . '").val="";
			$("#orders_status_bk_' . $orders_id . '").val("'.$bank_rows['orders_status'].'");
		 ';
    if ($bank_rows['emergency_order'] == '1') {
        $checked_sms = ' form_id.elements["emergency_order"].setAttribute("checked","checked");';
    }
    $rmb_sms ='';
    if(preg_match("/(&#65509;|￥)/",$rmb_text)){
        $rmb_sms ='if(form_id.elements["dransfer_total_rmb"].value<1){ form_id.elements["dransfer_total_rmb"].value ="' .number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($rmb_text))),2,'.','') . '"; }';
    }else{
        $rmb_sms ='if(form_id.elements["dransfer_total_rmb"].value<1){ form_id.elements["dransfer_total_rmb"].value ="' .$yingShouRmb . '"; }';
    }
    $sms.= $checked_sms.$rmb_sms.$sms_admin;
    $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
    echo db_to_html($sms);
    exit;
    }
     $rmb_sms='';
    //$rmb_sms ='var form_id =  document.getElementById("orders_edit_' . $orders_id . '");';
    if(preg_match("/(&#65509;|￥)/",$rmb_text)){
        $rmb_sms.='if(form_id.elements["dransfer_total_rmb"].value<1){ form_id.elements["dransfer_total_rmb"].value ="' .number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($rmb_text))),2,'.','') . '"; }';
    }else{
        $rmb_sms.='if(form_id.elements["dransfer_total_rmb"].value<1){ form_id.elements["dransfer_total_rmb"].value ="' .$yingShouRmb . '"; }';
    }
   
    $sms ='$("#tom_'. $orders_id . '").val("tom_'. $orders_id . '"); $("#rows_bk_id").val("'.$orders_id.'"); var form_id =  document.getElementById("orders_edit_' . $orders_id . '");';
     $sms.=$rmb_sms;
     $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
     echo db_to_html($sms);
    exit;
}
//更新订单状态
if ($_GET['action'] == 'submit_orders_edit' && $error == false) {
    $money_type =ajax_to_general_string(tep_db_output($_POST['money_type']));
    $real_charge ='';
    $real_charge_rmb_usd ='';
    $RMB='';
    // panda 拆分可分别填写 美元 人民币 start
    /*  
    if($_POST['actual_collection']!=''){
        if($money_type=='美元'){
            $real_charge=$_POST['actual_collection'];
            $real_charge_rmb_usd = '$'.number_format($_POST['actual_collection'],2,'.','');
        }else{
             if($money_type=='人民币'){
              $RMB=$_POST['actual_collection'];
              $real_charge_rmb_usd = '￥'.number_format($_POST['actual_collection'],2,'.','');
            }
        }
    }else{
        $real_charge_rmb_usd='';
    } */
    if (tep_not_null($_POST['actual_collection_dollar'])){
        $real_charge=$_POST['actual_collection_dollar'];
        $real_charge_rmb_usd_dollar = '$'.number_format($_POST['actual_collection_dollar'],2,'.','');
    }
    if (tep_not_null($_POST['actual_collection_rmb'])){
        $RMB=$_POST['actual_collection_rmb'];
        $real_charge_rmb_usd_rmb = '￥'.number_format($_POST['actual_collection_rmb'],2,'.','');
    }
    $real_charge_rmb_usd = $real_charge_rmb_usd_dollar .'('. $real_charge_rmb_usd_rmb .')';
    // panda 拆分可分别填写 美元 人民币 end
    $actual_collection = $_POST['actual_collection'];
    $admin_id = (int) $_POST['admin_id'];
    $bank = tep_db_output($_POST['bank_type']);
    if($bank=='nobank'){$bank='';}
    $customers_id = (int) $_POST['customers_id'];
    $customers_name = tep_db_output($_POST['customers_name']);
    $dransfer_name = tep_db_output($_POST['customers_name']);
    $dransfer_customers = tep_db_output($_POST['dransfer_customers']);
    $dransfer_total = $_POST['dransfer_total'];
    $emergency_order = (int) $_POST['emergency_order'];
    $orders_id = (int) $_POST['orders_id'];
    $value = $_POST['orders_total'];
    $value_rmb = $_POST['dransfer_total_rmb'];
    $update_orders = (int) $_POST['update_orders'];
    $charge_date = $_POST['select_collection_time'];
    $notes = tep_db_output($_POST['notes']);
    $payer = ajax_to_general_string(tep_db_output($_POST['payer_name']));
    //$notes = tep_db_output($_POST['notes']);
    $products_departure_date = tep_db_output($_POST['products_departure_date']);
    $products_name = tep_db_output($_POST['products_name']);
    //$select_dransfer_date = tep_db_output($_POST['select_dransfer_date']);
    $date_purchased = tep_db_output($_POST['date_purchased']);
    //附加费用记录
    $hotel_costs = substr($_POST['hotel_value'], '1');
    $pickup_costs = substr($_POST['pickup_value'], '1');
    $leave_costs = substr($_POST['leave_value'], '1');
    //团费
    $tours_costs = substr($_POST['tours_value'], '1');
    //未处理前的订单状态
    $orders_status_bk = (int) $_POST['orders_status_bk'];
    //处理人民币
    //$dransfer_total_rmb = (int) $_POST['dransfer_total_rmb'];

    //处理orders_status状态
     $orders_status='';
    $status_type = ajax_to_general_string(tep_db_output($_POST['status_type']));
    if ($status_type=='payment pending') {
         // if($orders_status_bk=='1'||$orders_status_bk=='100054'){$orders_status =$orders_status_bk;}else{ $orders_status='1'; }
          $orders_status_pending ="payment pending";
          $orders_status='100054';
        //if($orders_status_bk=='1'||$orders_status_bk=='100054'){ $orders_status='2'; }
    }
    if ($status_type=='Need Check Bank Account') {
           $orders_status ='100071';
    }
    if ($status_type=='Payment Received') {
           $orders_status ='100027';
    }
    if ($status_type=='Partial Payment Received') {
           $orders_status ='100052';
    }
   
    //先插入操作记录数据库
    $check_id_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' . $orders_id . '"');
    $check_id_rows = tep_db_fetch_array($check_id_query);
	$sql_data_array = array('orders_id' => $orders_id,
		'customers_id' => $customers_id,
		'customers_name' => ajax_to_general_string($customers_name),
		'products_name' => ajax_to_general_string($products_name),
		'date_purchased' => $date_purchased,
		'products_departure_date' => $products_departure_date,
		'bank' => ajax_to_general_string($bank),
		'orders_status' => $orders_status,
		'value' => $value,
		'admin_id' => $admin_id,
		'notes' => ajax_to_general_string($notes),
		'real_charge' => $real_charge,
		'update_time' => date('Y-m-d H:i:s'),
		'dransfer_date' => $select_dransfer_date,
		'hotel_costs' => $hotel_costs,
		'pickup_costs' => $pickup_costs,
		'leave_costs' => $leave_costs,
		'tours_costs' => $tours_costs,
		'dransfer_total' => $dransfer_total,
		'emergency_order' => $emergency_order,
		'dransfer_name' => ajax_to_general_string($dransfer_name),
		'RMB' => $RMB,
		'charge_date'=>$charge_date,
		'payer'=>$payer,
		'value_rmb'=>$value_rmb,
		'money_type'=>$money_type);
    if ((int) $check_id_rows['orders_id']) {	//更新
        tep_db_perform('domestic_orders', $sql_data_array, 'update', 'orders_id="' . $orders_id . '"');
		$oid = $orders_id;
    } else {	//插入
        tep_db_perform('domestic_orders', $sql_data_array);
		$oid = tep_db_insert_id();
    }
	//把订单表的emergency_order也设置为1
	//vincent ,会计通过“转账支付系统”提供更新订单后，需要订单页面记录是发出更新动作会计账号的名字。
	//将当前操作的用户ID记录到 订单的最后操作人员栏位-
	tep_db_query('UPDATE orders SET emergency_order = "'.(int)$emergency_order.'",admin_id_orders='.$login_id.' WHERE orders_id ="' . $oid . '"');
	
    $real_value_rmb ='';
    if($value_rmb!=null&&$value_rmb!='0'){
        $real_value_rmb ='￥'.number_format($value_rmb,2,'.','');
    }
    //if($orders_status=='100027'){
       // $email_sented ='1';
  //  }else{
        //$email_sented ='0';
  //  }
    //写操作记录表
    $manager_history = '';
    $now_status='';
     if ($status_type=='payment pending') {
         $now_status="payment pending";
         $bk_status_name ="payment pending";
        //if($orders_status_bk=='1'||$orders_status_bk=='100054'){ $orders_status='2'; }
    }else{
        $now_status=tep_get_orders_status_name(tep_db_output($orders_status));
    }
    if ($orders_status_bk != $orders_status) {
        if($orders_status_bk=='1'){
            $bk_status_name ="payment pending";
            $manager_history =  $bk_status_name . '->' . $now_status;
        }else{
             $manager_history = tep_get_orders_status_name(tep_db_output($orders_status_bk)) . '->' . $now_status;
        }
       
    } else if($status_type=='payment pending'){
               $manager_history =  $now_status;
    }else{
                $manager_history = tep_get_orders_status_name(tep_db_output($orders_status_bk));
    }

    $sql_data_array_history = array(
        'admin_id' => $admin_id,
        'manager_history' => $manager_history,
        'update_time' => date('Y-m-d H:i:s'),
        'notes' => ajax_to_general_string($notes),
        'orders_id' => $orders_id,
        'email_sented'=>$email_sented,
		'collected'=>$real_charge_rmb_usd
    );
    tep_db_perform('domestic_orders_history', $sql_data_array_history);
    //panda 更新订单页面的收款记录 start
    if ($orders_status == '100027'){        
        $settlement_final_date = GetTime(5, false, true);  //timezone , settlemetnt_time formate, daytimesaving  -- PST TIME
        $settlement_time = GetTime(5, true, true);				
        if((int)$settlement_time >= 0 && (int)$settlement_time <= 170000){					
            $settlement_final_date = $settlement_final_date;
        }else{
            $settlement_final_date = date('Y-m-d H:i:s', strtotime($settlement_final_date . ' + 1 day'));
        }
        $bank = ajax_to_general_string($bank);
        switch($bank){
            case '中国建设银行':
                $orders_payment_method = 'Cash Deposit to China Bank(CCB: 2113)';
                break;
            case '中国银行':
                $orders_payment_method = 'Cash Deposit to China Bank(BOC: 192)';
                break;
            case '中国工商银行':
                $orders_payment_method = 'Cash Deposit to China Bank(ICBC: 924)';
                break;
            case '中国招商银行':
                $orders_payment_method = 'Cash Deposit to China Bank(CMB: 8015)';
                break;
        }     
        
        $reference_comments =  '实收金额：'.$real_charge_rmb_usd;    
        //if ($money_type == '美元'){
        //Amount,默认自动输入美金   
        $order_value = $real_charge;
        //}else if ($money_type == '人民币'){
          //  $order_value = $RMB;
        //}        
        $sql_data_settlement_array = array(		
                                            'orders_id' =>  $orders_id,
                                            'order_value' => $order_value,			
                                            'orders_payment_method' => $orders_payment_method,
                                            'reference_comments' => $reference_comments,	
                                            'settlement_date' => $settlement_final_date,	
                                            'settlement_date_short' => substr($settlement_final_date, 0, 10),
                                            'date_added' => GetTime(5, false, true),
                                            'updated_by' => $admin_id									
                                            );									
        //tep_db_perform(TABLE_ORDERS_SETTLEMENT_INFORMATION, $sql_data_settlement_array, 'update', "orders_id = '" . (int)$oID . "'");
        //print_r($sql_data_settlement_array);
        tep_db_perform(TABLE_ORDERS_SETTLEMENT_INFORMATION, $sql_data_settlement_array);
     }
     
     //panda 更新订单页面的收款记录 end
    //更新orders表
    //if($orders_status_bk != $orders_status) {
	if(tep_not_null($orders_status)) {
      $auto_update_to_peding = false;
	  $old_starus_sql = tep_db_query('select orders_status from orders where `orders_id` = "' . $orders_id . '" ');
	  $old_starus_row = tep_db_fetch_array($old_starus_sql);
	  if($old_starus_row['orders_status']!=$orders_status){	 
          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
			(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)
			values ('" . $orders_id . "', '" . $orders_status . "', now(), 0, '" . tep_db_input(ajax_to_general_string($notes))  . "','".$login_id."')");
         // 更新为Payment Received状态后自动更新为pending状态
         tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
			(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)
			values ('" . $orders_id . "', '1', now(), 0, 'Auto set to pending','".$login_id."')");
         // 更新订单状态历史状态为Charge Captured （I）
         $charge_capturednotes_notes = $orders_payment_method . '<br/>' . $real_charge_rmb_usd .'<br/>' . tep_db_input(ajax_to_general_string($notes));
         tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
                (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)
                values ('" . $orders_id . "', '100006', now(), 0, '" . $charge_capturednotes_notes  . "','".$login_id."')"); 
      
	  if($orders_status=='100027'){
			$auto_update_to_peding = true;            
		}
	  }
	  //更新了状态之后需要更新订单历史
	  
	  tep_db_query('UPDATE orders SET orders_status ="' . $orders_status . '", last_modified=now() WHERE `orders_id` = "' . $orders_id . '" ');
	  //更新到payment received后，再将订单页面的状态自动更新为pending 状态，根据状态同步的原则，转账系统中的最新状态也同时改成pending；
	  if($auto_update_to_peding == true){
	  	//发送短信告知客户
	  	global $cpunc;
	  	//根据订单号取得手机号码
		$phone_sql = tep_db_query('SELECT c.customers_cellphone, c.customers_mobile_phone, c.customers_telephone FROM `orders` o,`customers` c WHERE o.orders_id ="'.(int)$orders_id.'" AND o.customers_id = c.customers_id Limit 1 ');
		$phone_row = tep_db_fetch_array($phone_sql);
		$strMobile = '';
		$result_phone = check_phone($phone_row['customers_cellphone']);
		if (!empty($result_phone))$strMobile = $result_phone[0];
		else {
			$result_phone = check_phone($phone_row['customers_mobile_phone']);
			if (!empty($result_phone))$strMobile = $result_phone[0];
			else {
				$result_phone = check_phone($phone_row['customers_telephone']);
				if (!empty($result_phone))$strMobile = $result_phone[0];
			}
		}
	  	if(tep_not_null($strMobile) && preg_match('/'.preg_quote('[收款确认通知]').'/',CPUNC_USE_RANGE)){
			$content = "您的订单（".$orders_id."）款项（".$real_charge_rmb_usd."）已确认，我们将于2个工作日内向您通报订单最新情况。详情请登陆“用户中心”或您的邮箱查看。祝您愉快！";
			$cpunc->SendSMS($strMobile,$content);
		}
		if(tep_not_null($strMobile) && preg_match('/'.preg_quote('[旅游保险提醒]').'/',CPUNC_USE_RANGE)){
			$content = "温馨提示：建议您在预订旅行的同时，也购买旅行保险！当因不可抗力或突发事件造成航班延误、旅行取消时，旅行保险能维护您的权益。";
			$cpunc->SendSMS($strMobile,$content);
		}
	  	//更改状态
	  	tep_db_query('UPDATE orders SET orders_status ="1", last_modified=now() WHERE `orders_id` = "' . $orders_id . '" ');
	  	tep_db_query('UPDATE domestic_orders SET orders_status ="1", admin_id ="'.$login_id.'" WHERE `orders_id` = "' . $orders_id . '" ');
		tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
			(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)
			values ('" . $orders_id . "', '1', now(), 0, 'Auto set to pending','".$login_id."')");
	  }
    }

    $notice_sms = db_to_html('你已经完成了此次操作，并已发短信通知客户，现请通知客服');
    $replace_table = '';
    //查银行
    $bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' . $orders_id . '"');
    $bank_rows = tep_db_fetch_array($bank_query);
    //打印订单操作历史表格
    //查询改订单的操作历史
       $ajax_url = preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=autoEmail'));

    $operation_history_query = tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . $orders_id . '"ORDER BY `update_time` ASC');
    if ((int) $bank_rows['orders_id'] && (int) tep_db_num_rows($operation_history_query)) {
        $replace_table = "<td colspan='5'  id='table_style_$orders_id' class='TabFg'>";
    } else {
        $replace_table = "<td colspan='5'  id='table_style_$orders_id' style='display:none' class='TabFg'>";
    }
    $replace_table.="<table class='DdTabXxCaozuo' border='0'  cellspacing='0' cellpadding='0'>
                                                                                             <tr>
                                                                                                <th>操作人</th>
                                                                                                <th>更新状态</th>
                                                                                                
                                                                                                <th>实际收款</th>
                                                                                                <th>更新时间</th>
                                                                                                <th>备注</th>
                                                                                                <th>邮件状态</th>
                                                                                                <th style='display:none'>ID</th>
                                                                                            </tr>";
    while ($operation_history_rows = tep_db_fetch_array($operation_history_query)) {
        $replace_table.="<tr>";
        $replace_table.="<td>" . tep_get_admin_customer_name($operation_history_rows['admin_id']) . "</td>";
        $replace_table.="<td>" . $operation_history_rows['manager_history'] . "</td>";
       
        $replace_table.="<td>" . $operation_history_rows['collected']. "</td>";
        $replace_table.="<td>" . $operation_history_rows['update_time'] . "</td>";
        $replace_table.="<td>" . $operation_history_rows['notes'] . "</td>";
        if((int)$operation_history_rows['email_sented']){
             $replace_table.="<td class='send-email-notcic'>已发送邮件</td>";
        }else{
                                    $replace_table.="<td id='td_email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."'><input type='button' class='AllbuttonHui' style='padding-left:5px; padding-right:5px; ' value='未发送邮件'  id='email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."'><script type='text/javascript'>$('#email_no_sented_".$operation_history_rows['orders_id']."_".$operation_history_rows['history_id']."').bind('click',function(){ $('#notic_id_pop_" . $orders_id . "').trigger('click');});</script></td>";

        }
        $replace_table.="<td style='display:none'><input type='text' value='".$operation_history_rows['history_id']."' id='history_id_".$operation_history_rows['history_id']."_".$operation_history_rows['orders_id']."'></td>";
        $replace_table.="</tr>";
    }
    $replace_table.="</table></td>";
 
  //$replace_update = "<input id='orders_status_submit_id_$orders_id'  name='orders_status_submit_name'  type='button' value='".db_to_html('更新')."'  style='margin-top:10px;' class='Allbutton_tom'>";
   $odp='';
    $sms = 'alert("' . $notice_sms . '"); $("#table_style_' . $orders_id . '").replaceWith("' . $replace_table . '");
            $("#charge_date_' . $orders_id . '").html("' . substr($charge_date,0,10) . '");
            $("#dransfer_total_row_' . $orders_id . '").html("' . $real_charge_rmb_usd. '");
            $("#dransfer_rmb_row_' . $orders_id . '").html("' .$real_value_rmb. '");
			$("#orders_status_bk_' . $orders_id . '").val("'.$bank_rows['orders_status'].'");
			$("#notes_' . $orders_id . '").val="";
			$("#dransfer_bank_row_' . $orders_id . '").html("' . tep_db_output(html_to_db(ajax_to_general_string($bank))) . '");
			$("#tom_status_' . $orders_id . '").val("0");
			$("#notic_status_' . $orders_id . '").hide();
			var orders_edit_submit_id_obj = document.getElementById("orders_edit_submit_id_'.$orders_id.'");
			if(orders_edit_submit_id_obj!=null){
				orders_edit_submit_id_obj.disabled = false;
				orders_edit_submit_id_obj.value = "确定";
				orders_edit_submit_id_obj.className = "Allbutton";
			}
        ';
    if ($status_type=='payment pending') {
          //if($orders_status_bk=='1'||$orders_status_bk=='100054'){$orders_status =$orders_status_bk;}else{ $orders_status='1'; }
          $orders_status_pending ="payment pending";
          $odp='$("#orders_status_row_' . $orders_id . '").html("' . $orders_status_pending. '");';
        //if($orders_status_bk=='1'||$orders_status_bk=='100054'){ $orders_status='2'; }
    }else{
        $odp =' $("#orders_status_row_' . $orders_id . '").html("' . tep_get_orders_status_name($orders_status) . '");';
    }
    $sms.=$odp;
    if($orders_status=='100027' || $orders_status=='100054' ||  $orders_status=='100052' ||  $orders_status=='100071'){	//即时显示发送邮件层
        $trigger_sms = '$("#notic_id_pop_' . $orders_id . '").trigger("click");';
         $sms.=$trigger_sms;
    }
    $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
    echo db_to_html('ggggggggggg'.$sms);
    exit;
}
//更新订单状态 end

if ($_GET['action'] == 'unset_permit' && $error == false) {
    $get_admin_id = (int) $_GET['admin_id'];
    $check_db = tep_db_input($_GET['check_db']);
    if ($check_db == 'in_db') {
        tep_db_query('UPDATE admin_domestic_orders_manage SET permit_status = "0" WHERE `admin_id` = "' . $get_admin_id . '"');
    } else {
        tep_db_query('INSERT INTO admin_domestic_orders_manage(`admin_id` , `manager_group` , `permit_status` , `manager_history`) VALUES("' . $get_admin_id . '",NULL,"1",NULL) ');
    }

    $notic_sms=db_to_html('设置成功');
    $replace_td ="<td id='pop_permit_$get_admin_id'><a onclick='javascript:set_permit($get_admin_id);return false;' href='#'><img width='10' height='10' border='0' title=' Set Active ' alt='Set Active' src='images/icon_status_green_light.gif'></a>&nbsp;&nbsp;<img width='10' height='10' border='0' title=' Inactive ' alt='Inactive' src='images/icon_status_red.gif'></td>";
    $sms = '
		alert("' . $notic_sms . '");
                 $("#pop_permit_'.$get_admin_id.'").replaceWith("'.$replace_td.'");

		';
    $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
    echo db_to_html($sms);
    exit;
}
if ($_GET['action'] == 'set_permit' && $error == false) {
    $get_admin_id = (int) $_GET['admin_id'];
    $check_db = $_GET['check_db'];
    if ($check_db == 'in_db') {
        tep_db_query('UPDATE admin_domestic_orders_manage SET permit_status = "1" WHERE `admin_id` = "' . $get_admin_id . '"');
    } else {
        tep_db_query('INSERT INTO admin_domestic_orders_manage(`admin_id` , `manager_group` , `permit_status` , `manager_history`) VALUES("' . $get_admin_id . '",NULL,"1",NULL) ');
    }
    $notic_sms=db_to_html('设置成功');
    $replace_td="<td id='pop_permit_$get_admin_id'><img width='10' height='10'  border='0' title='Active' alt='Active' src='images/icon_status_green.gif'>&nbsp;&nbsp;<a href='#' onclick='javascript:unset_permit($get_admin_id);return false;'><img width='10' height='10' border='0' title='Set Inactive'  alt='Set Inactive' src='images/icon_status_red_light.gif'></a></td>";
    $replace_td_2="<td id='pop_group_$get_admin_id'><select id='active_type_$get_admin_id' name='admin_type'><option value=''></option><option value='1'>1</option><option selected='selected' value='2'>2</option><option value='3'>3</option></select>&nbsp;&nbsp;&nbsp;<input type='button' onclick='change_group($get_admin_id)' name='update_group' value='update'><input type='hidden' id='check_db_$get_admin_id' value='in_db' name='check_db'></td>";
    $sms = '
		alert("' . $notic_sms . '");
                $("#pop_permit_'.$get_admin_id.'").replaceWith("'.$replace_td.'");
                $("#pop_group_'.$get_admin_id.'").replaceWith("'.$replace_td_2.'");';
    $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
    echo db_to_html($sms);
    exit;
}
if ($_GET['action'] == 'set_group' && $error == false) {
    $get_admin_id = (int) $_GET['admin_id'];
    $get_group_num = (int) $_GET['group_num'];
    tep_db_query('UPDATE admin_domestic_orders_manage SET manager_group = "' . $get_group_num . '" WHERE `admin_id` = "' . $get_admin_id . '"');
    $notic_sms=db_to_html('设置成功');
    
    $sms = '
		alert("' . $notic_sms . '");
             

		';
    $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
    echo db_to_html($sms);
    exit;
}
if ($_GET['action'] == 'change_email' && $error == false) {
    $orders_id = (int) $_GET['orders_id'];
    $bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' .$orders_id. '"');
    $bank_rows = tep_db_fetch_array($bank_query);
    $dransfer_money ='';
    //if($bank_rows['money_type']=='美元'){
      $dransfer_money_dollar='$'.number_format($bank_rows['real_charge'],2,'.','');
    //}else if($bank_rows['money_type']=='人民币'){
        $dransfer_money_rmb='￥'.number_format($bank_rows['RMB'],2,'.','');
    //}
    $dransfer_money = $dransfer_money_dollar .'('.$dransfer_money_rmb.')';
    $text = '';
    $text_2 = '';
    $text_3 = '';
    if ($_GET['orders_status'] == '100071') {
         $text.='应收总金额: $'.number_format($bank_rows['value'],2,'.','').'\n\n汇款金额：'.$dransfer_money.'\n\n汇款银行：'.$bank_rows['bank'].'\n\n汇款人姓名：'.$bank_rows['dransfer_name'].'\n\n购买产品:'.$bank_rows['products_name'].'\n';
        $text_2.='Need Check Bank Account';
        $text_3.='陈总';
    }
    if ($_GET['orders_status'] == '100027') {
        $text.='我们确定收到您的团款'.$dransfer_money.'，正在进一步处理您的预订。\n谢谢您的合作。\n\nWe confirm receipt of your payment. We have proceeded with further processing your booking. \nThank you.\n\n' . $EmailSignature;
        $text_2 = '走四方网--Payment Received 您的汇款已收到- 订单#' . $orders_id;
        $text_3.='客户';
    }
    if ($_GET['orders_status'] == '100052') {
        $text.='我们确认已收到您的部分款额'.$dransfer_money.'，请您尽快将剩余款汇至我公司，以便我们进一步处理您的订单。\n\n请您注意我们在收到全额款后才会进一步处理您的订单。\n\n
谢谢您的理解！\n谢谢您的合作。\n' . $EmailSignature;
        $text_2 = '走四方网--Partial Payment Received 您的部分款额已收到-订单#' . $orders_id;
        $text_3.='客户';
    }
    if ($_GET['orders_status'] == '100054') {
        $text.='感谢您在走四方网预定您的行程，请注意在付款前您的座位未被保留。\n【请尽量在汇款信息中留下您的订单号，姓名等信息。如果您的汇款金额为整数，请您添加任意的角分以便我们能更快和更准确\n得为您查询】\n\nusitrip在中国的帐号资讯如下：\n招商银行： 6225 8802 8602 8015 陈锐【成都分行营门口支行】\n建设银行：6227 0038 1356 0195 055 陈锐【成都第8支行】\n工商银行：6222 0244 0200 3304 924 陈锐【成都市浆洗街支行】\n中国银行：6013 8231 0008 5238 061 陈锐 【成都小天北街分理处】长城电子借记卡
    }
\n\n请在汇款后第一时间通知走四方网中国区客服部确定您的订单号和汇款金额，以便及时操作您的订单。联系电\n话:4006-333-926, 办公时间：周一-周五（上午8：00am-下午6：00pm）周六（上班时间上午9：00am-下午6：00点）\n\n特别提示：在系统确认收到您的全额付款后将进行下一步订单处理,安排您的座位（预订）\n\n谢谢您的配合。\n' . $EmailSignature;
        $text_2.='Payment Pending（Chinese Account）支付待定（中国帐户）-订单#' . $orders_id;
        $text_3.='客户';
    }
   if($_GET['orders_status']=='1'){
      $text.='By:\n\n\n\n\n\n\n' . $EmailSignature;
      $text_2.='Pending 待定 - 订单#'.$orders_id;
   }
    
    $text_1 = '';
    $sms = '
           $("#coments_id").html("' . tep_db_output(html_to_db(ajax_to_general_string($text_1))) . '");
                $("#coments_id").hide();
                $("#coments_id").slideDown(600);
		                $("#coments_id").html("' . tep_db_output(html_to_db(ajax_to_general_string($text))) . '");
                                 $("#email_id").attr("value","' . tep_db_output(html_to_db(ajax_to_general_string($text_2))) . '");
                                     $("#notice_who").html("' . tep_db_output(html_to_db(ajax_to_general_string($text_3))) . '");

		';
    $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
    echo db_to_html($sms);
    exit;
}
if ($_GET['action'] == 'update_edit' && $error == false) {
    //只发邮件通知，此处不会更改订单状态 其中除了100071邮件，其他邮件都会通知到客户
    $emailSms = db_to_html('邮件发送成功!');
    $comment = '';
    $login_id = $_SESSION['login_id'];

	$comments_for_email = ajax_to_general_string($_POST['comments']);
    $customer_notified = '1';
    $emailTable = '';
    $bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' . $oID . '"');
    $bank_rows = tep_db_fetch_array($bank_query);
    if ((int) $bank_rows['orders_id']) {
        $emailTable.= '<tr>';
        $emailTable.= '<td class="main"><b>中国国内订单状态详情</b></td>';
        $emailTable.= '</tr><tr> <td class="main"><table border="1" cellspacing="0" cellpadding="5"><tr><td class="smallText" align="center"><b>订单号</b></td><td class="smallText" align="center"><b>操作人</b></td>';
        $emailTable.= ' <td class="smallText" align="center"><b>更新状态</b></td><td class="smallText" align="center"><b>应付金额</b></td> <td class="smallText" align="center"><b>实际收款</b></td> <td class="smallText" align="center"><b>转账人</b></td><td class="smallText" align="center"><b>转账银行</b></td><td class="smallText" align="center"><b>更新时间</b></td> <td class="smallText" align="center"><b>备注</b></td></tr>';
        $operation_history_query = tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . tep_db_input($oID) . '"ORDER BY `update_time` ASC');
        while ($operation_history_rows = tep_db_fetch_array($operation_history_query)) {
            $emailTable.= '<tr>';
            $emailTable.= '<td class="smallText" align="center">' .$oID . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . tep_get_admin_customer_name($operation_history_rows['admin_id']) . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['manager_history'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['collected']. '</td>';
            //if($bank_rows['money_type']=='美元'){
                 //$emailTable.= '<td class="smallText" align="center">' .'$'.number_format($bank_rows['real_charge'],2,'.','') . '</td>';
            //}else if($bank_rows['money_type']=='人民币'){
                $emailTable.= '<td class="smallText" align="center">' .'$'.number_format($bank_rows['real_charge'],2,'.','') .'(￥'.number_format($bank_rows['RMB'],2,'.','') . ')</td>';
            //}
            $emailTable.= '<td class="smallText" align="center">' . $bank_rows['dransfer_name'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' .$bank_rows['bank'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['update_time'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['notes'] . '</td>';
        }

        $emailTable.= '</table></td></tr>';
    }


    //$email_sent_subject =$_POST['email_subject'];
    if ((int) $_POST['status'] == '100071') {
        $customer_notified = '0';
        if (IS_LIVE_SITES ===true) {
            $to_email_address = 'payment@usitrip.com';
        } else {
            $to_email_address = 'xmzhh2000@hotmail.com';
            //$to_email_address = 'weiyi.li@usitrip.com';
        }
        $to_name = db_to_html('陈锐');
        if ($_POST['email_subject'] != '') {
            $email_sent_subject = ajax_to_general_string($_POST['email_subject']);
        } else {
            $email_sent_subject = 'Points Account Update .' . ' ' . date('Y-m-d H:i:s');
        }
        $email_sent_subject.='(' . $_POST['orders_id'] . ')';
        $user_lang_code = 'gb2312';
        $email_subject = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_sent_subject);
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments_for_email) . "\n\n";
        $email = '';
        $email.=
                '------------------------------------------------------' . "\n" .
                STORE_NAME . "\n" .
                '------------------------------------------------------' . "\n" .
                db_to_html('客户订单号：') . ' ' . $_POST['orders_id'] . "\n" .
                'Reservation Date:' . ' ' . tep_date_long($check_orders['date_purchased']) . "\n\n" .
                
				$notify_comments . sprintf('' . "\n", '中国国内首款查询');
        $comment = $email;
        $email.=$emailTable;
        //$comment = iconv(CHARSET, $user_lang_code . '//IGNORE', $comment);
        $email.= email_track_code('OrderStatus', $check_orders['customers_email_address'], $_POST['orders_id']);
        $email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email);
        //$email_text = $comments_for_email;
        $from_email_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
        //$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
        $from_email_address = 'service@usitrip.com ';
        $to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', $to_name);
        tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
        $email_history_query=tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . ($oID) . '"ORDER BY  `history_id` DESC  LIMIT 1');
        $email_history_rows = tep_db_fetch_array($email_history_query);
        $hisory_update = tep_db_query('UPDATE  `domestic_orders_history` SET  `email_sented` = 1  WHERE  `history_id` ="'.(int)$email_history_rows['history_id'].'"');


       // tep_db_query("insert into  orders_status_history(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by) values ('" . (int) $oID . "', '" . tep_db_input((int) $_POST['status']) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comment) . "','" . $login_id . "')");
          $relpace_email =db_to_html('已发送邮件');
          // $sms = '$("#'.$replace_id.'").replaceWith("<td class=send-email-notcic>'. $relpace_email.'</td>");
        $sms = '
            $("#td_email_no_sented_'.$email_history_rows['orders_id'].'_'. $email_history_rows['history_id'].'").replaceWith("<td class=send-email-notcic>'. $relpace_email.'</td>");
           alert("' . $emailSms . '");
           closePopup("popup");

		';
        $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
        echo db_to_html($sms);
    }
    if (($_POST['status'] == '100054' || $_POST['status'] == '100027' || $_POST['status'] == '1' || $_POST['status'] == '100052')&&$_POST['notify']=='on') {
        $notify_comments = '';
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments_for_email) . "\n\n";

        //$to_email_address = 'weiyi.li@usitrip.com';


        if ($_POST['email_subject'] != '') {
            $email_sent_subject = ajax_to_general_string($_POST['email_subject']);
        } else {
            $email_sent_subject = 'Points Account Update .' . ' ' . date('Y-m-d H:i:s');
        }

        //$email_sent_subject.='('.$_POST['orders_id'].')';
        $user_lang_code = customers_language_code($check_orders['customers_email_address']);

        $email_subject = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_sent_subject);
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments_for_email) . "\n\n";
        $email = '';
        //结伴同游邮件不用指名道姓
        $order_url = tep_catalog_href_link('orders_travel_companion_info.php', 'order_id=' . $oID, 'SSL');
        if (!isset($_POST['send_travel_companion_user']) || $_POST['send_travel_companion_user'] != '1') {
            $email.= EMAIL_TEXT_DEAR . db_to_html($check_orders['customers_name']) . "\n";
            $order_url = tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL');
        }
        //结伴同游邮件不用指名道姓 end
        $email.=
				EMAIL_TEXT_DEAR_A . "\n" .
				EMAIL_SEPARATOR . "\n" .
				STORE_NAME . "\n" .
				EMAIL_SEPARATOR . "\n" .
                EMAIL_TEXT_ORDER_NUMBER . ' ' . $_POST['orders_id'] . "\n" .
				//EMAIL_TEXT_STATUS_UPDATE. tep_get_order_status_name($_POST['status'])."\n". 
				EMAIL_TEXT_DATE_ORDERED." ".tep_date_long($check_orders['date_purchased']). "\n".
				EMAIL_TEXT_INVOICE_URL . ' ' . $order_url . "\n\n" .
				sprintf(EMAIL_TEXT_STATUS_UPDATE, 'Payment Pending（Chinese Account）支付待定')."\n\n". 
                $notify_comments . sprintf('' . "\n", 'Payment Pending（Chinese Account）支付待定（中国帐户）-订单#');
        $comment = $email;
        //$comment = iconv(CHARSET, $user_lang_code . '//IGNORE', $comment);
        $email.= email_track_code('OrderStatus', $check_orders['customers_email_address'], $_POST['orders_id']);
        $to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html($check_orders['customers_name']));
        $to_email_address = $check_orders['customers_email_address'];
        $email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email);
        //$email_text = $comments_for_email;
        $from_email_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
        //$from_email_address = STORE_OWNER_EMAGIL_ADDRESS;
        $from_email_address = 'automail@usitrip.com';
       //$to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', $to_name);
        tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
        if($_POST['status']=='100027' || $_POST['status']=='1' || $_POST['status']=='100054' || $_POST['status']=='100052'){
               $customer_notified ='1';
               tep_db_query("insert into  orders_status_history(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by) values ('" . (int) $oID . "', '" . tep_db_input((int) $_POST['status']) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comment) . "','" . $login_id . "')");
        }
        $email_history_query=tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . ($oID) . '"ORDER BY  `history_id` DESC  LIMIT 1');
        $email_history_rows = tep_db_fetch_array($email_history_query);
        $hisory_update = tep_db_query('UPDATE  `domestic_orders_history` SET  `email_sented` = 1  WHERE  `history_id` ="'.(int)$email_history_rows['history_id'].'"');


       // tep_db_query("insert into  orders_status_history(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by) values ('" . (int) $oID . "', '" . tep_db_input((int) $_POST['status']) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comment) . "','" . $login_id . "')");
          $relpace_email =db_to_html('已发送邮件');
          // $sms = '$("#'.$replace_id.'").replaceWith("<td class=send-email-notcic>'. $relpace_email.'</td>");
        $sms = '
            $("#td_email_no_sented_'.$email_history_rows['orders_id'].'_'. $email_history_rows['history_id'].'").replaceWith("<td class=send-email-notcic>'. $relpace_email.'</td>");
           alert("' . $emailSms . '");
               closePopup("popup");

		';
        $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
        echo db_to_html($sms);
    }

    exit;
}
?>
<?PHP if ($_GET['nyroModalSel'] == 'main') { ?>
    <div id="main" width="100%">






        <h1>管理员管理</h1>

    <?php
    //取得所有管理员
    $admin_sql = 'SELECT * FROM `admin`';
    $admin_query = tep_db_query('SELECT * FROM `admin`');
    include(domestic . '/' . DIR_WS_CLASSES . 'banking_statistics.php');
    ?>
    <table class="tablesorter" cellspacing="1">
        <thead>
            <tr>
                <th class="header">管理员ID</th>
                <th class="header">管理员级别</th>
                <th class="header">管理员姓名</th>
                <th class="header">管理员邮箱</th>
                <th class="header">后台权限</th>
                <th class="header">权限等级</th>
                <th class="header">设置等级</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $admin_array = array();
            $admin_array[0] = array('id' => ' ', 'text' => ' ');
            $admin_array[1] = array('id' => '1', 'text' => '1');
            $admin_array[2] = array('id' => '2', 'text' => '2');
            $admin_array[3] = array('id' => '3', 'text' => '3');
            $admin_array = db_to_html($admin_array);
            $Inactive = 'inactive';

            while ($admin_rows = tep_db_fetch_array($admin_query)) {
                $search_admin = tep_db_query('SELECT * FROM admin_domestic_orders_manage WHERE admin_id = "' . $admin_rows['admin_id'] . '"');
                $search_admin_rows = tep_db_fetch_array($search_admin);
            ?>
                <tr class="even">
                    <td><?= $admin_rows['admin_id'] ?></td>
                    <td><?= $admin_rows['admin_groups_id'] ?></td>
                    <td><?= $admin_rows['admin_firstname'] . $admin_rows['admin_lastname'] ?></td>

                    <td><?= $admin_rows['admin_email_address'] ?></td>
                    <td><?= admin_check($admin_rows['admin_id']) ?></td>
                <?php
                if ($search_admin_rows['permit_status'] == '1') {
                    echo '<td id="pop_permit_'.$admin_rows['admin_id'].'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="#" onclick="javascript:unset_permit(' . $admin_rows['admin_id'] . ');return false;">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a></td>';
                    echo '<td id="pop_group_'.$admin_rows['admin_id'].'">' . tep_draw_pull_down_menu('admin_type', $admin_array, $search_admin_rows['manager_group'], 'id=active_type_' . $admin_rows['admin_id'] . '') . '&nbsp;&nbsp;&nbsp;<input type="button" value="update" name="update_group" onclick="change_group(' . $admin_rows['admin_id'] . ')"/>';
                } else {
                    echo '<td id="pop_permit_'.$admin_rows['admin_id'].'"><a href="#" onclick="javascript:set_permit(' . $admin_rows['admin_id'] . ');return false;">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10) . '</td>';
                    echo '<td id="pop_group_'.$admin_rows['admin_id'].'">' . tep_draw_pull_down_menu('admin_type', $admin_array, '', 'id=inactive_type_' . $admin_rows['admin_id'] . '');
                }
                if ((int) $search_admin_rows) {
                    echo tep_draw_hidden_field('check_db', 'in_db', 'id=check_db_' . $admin_rows['admin_id'] . '') . '</td>';
                } else {
                    echo tep_draw_hidden_field('check_db', 'noin_db', 'id=check_db_' . $admin_rows['admin_id'] . '') . '</td>';
                }
                ?>
            </tr>
            <?php }
            ?>
            
        </tbody>
     
           
      
    </table>



</div>
<?php exit;
        } ?>
<?php if ($_GET['nyroModalSel'] == 'notice') {
?>
            <div id="notice" width="100%">



    <?php
            //查最新状态
            $orders_id = (int) $_GET['orders_id'];
            $the_extra_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_email_address = '" .$check_orders['customers_email_address'] . "'");
           $the_extra = tep_db_fetch_array($the_extra_query);
           $the_customers_fax = $the_extra['customers_fax'];
            $the_customers_mobile_phone = $the_extra['customers_mobile_phone'];
            $the_customers_cellphone = $the_extra['customers_cellphone'];
            $bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' .$orders_id. '"');
            $bank_rows = tep_db_fetch_array($bank_query);
            $dransfer_money ='';
            //if($bank_rows['money_type']=='美元'){
              $dransfer_money_dollar='$'.number_format($bank_rows['real_charge'],2,'.','');
            //}else if($bank_rows['money_type']=='人民币'){
                $dransfer_money_rmb='￥'.number_format($bank_rows['RMB'],2,'.','');
            //}
            $dransfer_money = $dransfer_money_dollar . '('. $dransfer_money_rmb .')';
/*/domestic_orders.php页面的“折扣前订单总金额”是订单总额+2%折扣数。实际上ot_subtotal不能代表“折扣前的应付总金额”，如果遇到订单中有积分和优惠券的使用时会有问题。
			$rmb_query=tep_db_query('select text,value from orders_total where orders_id="'.$bank_rows['orders_id'].'" and class="ot_subtotal"');
			$rmb_rows = tep_db_fetch_array($rmb_query);
			$rmb_text = $rmb_rows['text'];
			$rmb_rows['value'];
*/			
			$rmb_query_1=tep_db_query('select title,text,value from orders_total where orders_id="'.$bank_rows['orders_id'].'" and class="ot_easy_discount"');
			$rmb_rows_1 = tep_db_fetch_array($rmb_query_1);
			 
            $text = '';
            $text_2 = '';
            $text_3 = '';
            $bk_status= $bank_rows['orders_status'];
            //人民币附加金额美元
            $rmb_query_2=tep_db_query('select value, text from orders_total where orders_id="'.$bank_rows['orders_id'].'" and class="ot_total"');
			$rmb_rows_2= tep_db_fetch_array($rmb_query_2);
			$rmb_text_2 = $rmb_rows_2['text'];
			if($check_orders['us_to_cny_rate']>0){
				$rate = $check_orders['us_to_cny_rate'];
			}else{
				$rate = $new_rate;
			}
			$rmb_rows['value'] = $rmb_rows_2['value']+abs($rmb_rows_1['value']);	//折扣前订单金额值
			$rmb_num_befor = number_format(round($rmb_rows['value']*$rate,0),2,'.','');
			$rmb_rows['text'] = '￥'.$rmb_num_befor;//折扣前订单金额的人民币值
			//$rmb_rows['text'] = '$'.number_format($rmb_rows['value'],2,'.','');//折扣前订单金额带前导符
			$rmb_text = $rmb_rows['text'];
			
			
			$rmb_num_after = number_format(round($bank_rows['value']*$rate,0),2,'.','');
			
			$rmb_usd_attach_1='$'.number_format($rmb_rows['value'],2,'.','').' ';
			$rmb_usd_attach_2 = '&nbsp;(￥'.$rmb_num_after.')';
			$rmb_usd_attach_3 ='-$'.number_format(abs($rmb_rows_1['value']),2,'.','');
			//$rmb_uds_attach_4 = '&nbsp;(-￥'.number_format(abs($rmb_rows_1['value'])*$rate,2,'.','').')';
			$rmb_uds_attach_4 = '&nbsp;(-￥'.number_format(($rmb_num_befor-$rmb_num_after),2,'.','').')';
			
			//如果有手工输入的应付人民币的值，在折扣后应付总金额的人民币值中就以手工输入的为准。
			//$usd_rmb_foot = '应付人民币金额:￥'.number_format($bank_rows['value_rmb'],2,'.','');
			$rmb_usd_attach_5 ='&nbsp;('.str_replace(',','',$rmb_text).')';
			$rmb_usd_attach= '';
			
			if(preg_match("/(&#65509;|￥)/",$rmb_text_2)){
				 $rmb_usd_attach_2 ='(￥'.number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($rmb_text_2))),2,'.','').')';
				 
				 $rmb_uds_attach_4 = '('.strip_tags($rmb_rows_1['text']).')';
				 $rmb_usd_attach_5 ='('.str_replace(',','',$rmb_text).')';
				 $usd_rmb_foot ='';
				 //$rmb_usd_attach_tours_cash = '$'.number_format(str_replace(',','',preg_replace('/(&#65509;|￥)/','',strip_tags($tours_cash)))/$rate,2,'.','').'('.$tours_cash.')';
			}
			
			if((int)$bank_rows['value_rmb']){//如果有手工输入的应付人民币的值，在折扣后应付总金额的人民币值中就以手工输入的为准。
				$rmb_usd_attach_2 ='(￥'.number_format($bank_rows['value_rmb'],2,'.','').')';
			}
			//echo $rmb_usd_attach_3;
            if ($bank_rows['orders_status'] == '100071') {
                 $text.='折扣前的应付总金额:'.$rmb_usd_attach_1.$rmb_usd_attach_5.'
'.$rmb_rows_1['title'].$rmb_usd_attach_3.' '.$rmb_uds_attach_4.'
折扣后应付总金额:$'.number_format($bank_rows['value'],2,'.','').' '.$rmb_usd_attach_2.'
'.$usd_rmb_foot.'

汇款金额：'.$dransfer_money.'

汇款银行：'.$bank_rows['bank'].'

汇款人姓名：'.$bank_rows['dransfer_name'].'

购买产品:'.$bank_rows['products_name'].'';
                $text_2.='Need Check Bank Account';
                $text_3.='payment@usitrip.com';
            }
            if ($bank_rows['orders_status'] == '100027' || $bank_rows['orders_status'] == '1') {
                $text.='我们确定收到您的团款，正在进一步处理您的预订。
谢谢您的合作。

您的付款信息如下:
---------------------------------------------------------------
转账银行:'.$bank_rows['bank'].'
收款时间:'.substr($bank_rows['charge_date'], 0, 10).'
转账金额:'.$dransfer_money.'
---------------------------------------------------------------
We confirm receipt of your payment. We have proceeded with further processing your booking.
Thank you.

' . $EmailSignature;
                $text_2 = '走四方网--Payment Received 您的汇款已收到- 订单#' . $orders_id;
                $text_3.=$check_orders['customers_email_address'];
                $text_3.='        手机号:'.$the_customers_cellphone ;
            }
            if ($bank_rows['orders_status'] == '100052') {
                $text.='我们确认已收到您的部分款额。
请您尽快将剩余款汇至我公司，以便我们进一步处理您的订单。
请您注意我们在收到全额款后才会进一步处理您的订单。
谢谢您的理解！

您的付款信息如下:
---------------------------------------------------------------
转账银行:'.$bank_rows['bank'].'
收款时间:'.substr($bank_rows['charge_date'], 0, 10).'
转账金额:'.$dransfer_money.'
---------------------------------------------------------------

您的应付款信息如下：
---------------------------------------------------------------
折扣前的应付总金额:'.$rmb_usd_attach_1.$rmb_usd_attach_5.'
'.$rmb_rows_1['title'].$rmb_usd_attach_3.' '.$rmb_uds_attach_4.'
折扣后应付总金额:$'.number_format($bank_rows['value'],2,'.','').' '.$rmb_usd_attach_2.'
'.$usd_rmb_foot.'
---------------------------------------------------------------

' . $EmailSignature;
                $text_2 = '走四方网--Partial Payment Received 您的部分款额已收到-订单#' . $orders_id;
                $text_3.=$check_orders['customers_email_address'];
                $text_3.='        手机号:'.$the_customers_cellphone ;
            }
            
			if ($bank_rows['orders_status'] == '100054') {
                $text.='感谢您在走四方网预定您的行程，请注意在付款前您的座位未被保留。
【请尽量在汇款信息中留下您的订单号，姓名等信息。如果您的汇款金额为整数，请您添加任意的角分以便我们能更快和更准确得为您查询】

您的付款信息如下:
=============================================================
折扣前的应付总金额:'.$rmb_usd_attach_1.$rmb_usd_attach_5.'
'.$rmb_rows_1['title'].$rmb_usd_attach_3.' '.$rmb_uds_attach_4.'
折扣后应付总金额:$'.number_format($bank_rows['value'],2,'.','').' '.$rmb_usd_attach_2.'
'.$usd_rmb_foot.'
=============================================================
usitrip在中国的帐号资讯如下：
招商银行： 6225 8802 8602 8015 陈锐【成都分行营门口支行】
建设银行：6227 0038 1356 0195 055 陈锐【成都第8支行】
工商银行：6222 0244 0200 3304 924 陈锐【成都市浆洗街支行】
中国银行：6013 8231 0008 5238 061 陈锐 【成都小天北街分理处】长城电子借记卡
            
请在汇款后第一时间通知走四方网中国区客服部确定您的订单号和汇款金额，以便及时操作您的订单。联系电
话:4006-333-926, 办公时间：办公时间：周一-周日（上午9：00am-下午6：00pm）

特别提示：在系统确认收到您的全额付款后将进行下一步订单处理,安排您的座位（预订）

谢谢您的配合。

' . $EmailSignature;
                $text_2.='Payment Pending（Chinese Account）支付待定（中国帐户）-订单#' . $orders_id;
                $text_3.=$check_orders['customers_email_address'];
                $text_3.='        手机号:'.$the_customers_cellphone ;
            }
			
            //echo tep_draw_form('status', 'ajax_domestic_orders.php', tep_get_all_get_params(array('action')) . 'action=update_order', 'get', ' id="notice_form" ');
            $orders_statuses = array();
            $orders_statuses[0]=array('id'=>'','text'=>' ');
            $orders_statuses[1] = array('id' => '100054', 'text' => 'Payment Pending');
            $orders_statuses[2] = array('id' => '100071', 'text' => 'Need Check Bank Account');
            $orders_statuses[3] = array('id' => '100027', 'text' => 'Payment Received');
            $orders_statuses[4] = array('id' => '100052', 'text' => 'Partial Payment Received');
            $orders_statuses[5] = array('id' => '1', 'text' => 'Pending');
    ?>
	<table border="0" cellspacing="0" cellpadding="2">
            <tr>
                <td class="main" valign="top" width="100"><span>Comments:</span></td>
				<td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '100', '20',$text, 'style="width:840px; border:1px solid #d5d5d5" id=coments_id'); ?></td>
            </tr>
			<tr>
              <td class="main"><span>Email Subject:</span></td>
              <td class="main"><?php echo tep_draw_input_field('email_subject',$text_2, 'class="textAll" style="width:834px;" id="email_id"'); ?></td>
            </tr>
			<tr>
			<tr>
			  <td class="main"><span><?php echo 'Status:'; ?></span></td>
			  <td class="smallText"><?php
								//echo $bk_status;
								$orders_statuse_option = array();
								for($i=0; $i<count($orders_statuses); $i++){
									if($orders_statuses[$i]['id']==$bk_status){
										$orders_statuse_option[] = array('id'=>$orders_statuses[$i]['id'], 'text'=>$orders_statuses[$i]['text']);
									}
								}
								echo tep_draw_pull_down_menu('status', $orders_statuse_option,$bk_status, 'id="select_nemu" onChange="changesubjectemail_new(' . $_GET['orders_id'] . ');"');
								?>
								<span style="color:#777777"><?php echo '请不要在这里变更status状态'; ?></span>
			 </td>
           </tr>
           <tr><td colspan="2"><?php echo tep_draw_hidden_field('orders_id', $_GET['orders_id'], 'id="hidden_orders_id"') ?></td></tr>
           <tr>
		     <td class="main"><span>发送对象:</span></td><td id="notice_who"  class="smallText"><?=$text_3?></td>
		  </tr>
          <tr>
		    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5').tep_get_orders_status_name($status_rows['orders_status']); ?></td>
	      </tr>
          <tr>
		    <td class="main" style="display: none"><span><?php echo '通知客户:'; ?></span> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
          </tr>
          <tr>
			<td class="main" align="center" height="40" colspan="2"> <input id="Update_Button" name="Update_Button" class="Allbutton" style="margin-right:50px;" type="button" value="<?php echo '发送';?>" onClick="Submit_Update();" >
                 <input class="AllbuttonHui" value="取消" type="button" onClick="closePopup(&quot;popup&quot;)"/>
				<?php //echo tep_image_submit('button_update.gif', IMAGE_UPDATE);  ?>
            </td>
	     </tr>
		</table><?php //echo tep_image_submit('button_update.gif', IMAGE_UPDATE);    ?>



    <?php
                                    //echo '<form>';
                                    //echo '<pre>';
                                    //print_r($_SESSION);
                                    //echo "</pre>";
    ?>

                                </div>
<?php exit;
                                } ?>

<?php
if($_GET['action']=='autoEmail'){
    $orders_id = (int)$_GET['orders_id'];
    $oID = (int) $_GET['orders_id'];
    $orders_status = (int)$_GET['orders_status'];

	$the_extra_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_email_address = '" .$check_orders['customers_email_address'] . "'");
	$the_extra = tep_db_fetch_array($the_extra_query);
	$the_customers_fax = $the_extra['customers_fax'];
	$the_customers_mobile_phone = $the_extra['customers_mobile_phone'];
	$the_customers_cellphone = $the_extra['customers_cellphone'];
	$bank_query = tep_db_query('SELECT * FROM `domestic_orders` WHERE orders_id = "' .$orders_id. '"');
	$bank_rows = tep_db_fetch_array($bank_query);
	$emailTable = '';
   
    if ((int) $bank_rows['orders_id']) {
        $emailTable.= '<tr>';
        $emailTable.= '<td class="main"><b>中国国内订单状态详情</b></td>';
        $emailTable.= '</tr><tr> <td class="main"><table border="1" cellspacing="0" cellpadding="5"><tr><td class="smallText" align="center"><b>订单号</b></td><td class="smallText" align="center"><b>操作人</b></td>';
        $emailTable.= ' <td class="smallText" align="center"><b>更新状态</b></td><td class="smallText" align="center"><b>应付金额</b></td> <td class="smallText" align="center"><b>实际收款</b></td> <td class="smallText" align="center"><b>转账人</b></td><td class="smallText" align="center"><b>转账银行</b></td><td class="smallText" align="center"><b>更新时间</b></td> <td class="smallText" align="center"><b>备注</b></td></tr>';
        $operation_history_query = tep_db_query('SELECT * FROM `domestic_orders_history` WHERE orders_id = "' . tep_db_input($oID) . '"ORDER BY `update_time` ASC');
        while ($operation_history_rows = tep_db_fetch_array($operation_history_query)) {
            $emailTable.= '<tr>';
            $emailTable.= '<td class="smallText" align="center">' .$oID . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . tep_get_admin_customer_name($operation_history_rows['admin_id']) . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['manager_history'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['collected'] . '</td>';
            //if($bank_rows['money_type']=='美元'){
                 //$emailTable.= '<td class="smallText" align="center">' .'$'.number_format($bank_rows['real_charge'],2,'.','') . '</td>';
            //}else if($bank_rows['money_type']=='人民币'){
                $emailTable.= '<td class="smallText" align="center">' . '$'.number_format($bank_rows['real_charge'],2,'.','') .'(￥'.number_format($bank_rows['RMB'],2,'.','') . ')</td>';
            //}
            $emailTable.= '<td class="smallText" align="center">' . $bank_rows['dransfer_name'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' .$bank_rows['bank'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['update_time'] . '</td>';
            $emailTable.= '<td class="smallText" align="center">' . $operation_history_rows['notes'] . '</td>';
        }

        $emailTable.= '</table></td></tr>';
    }
            $dransfer_money ='';
            //if($bank_rows['money_type']=='美元'){
              $dransfer_money_dollar='$'.number_format($bank_rows['real_charge'],2,'.','');
            //}else if($bank_rows['money_type']=='人民币'){
                $dransfer_money_rmb='￥'.number_format($bank_rows['RMB'],2,'.','');
            //}
            $dransfer_money  =   $dransfer_money_dollar . '('. $dransfer_money_rmb .')';    
            $text = '';
            $text_2 = '';
            $text_3 = '';
            if ($bank_rows['orders_status'] == '100071') {
                 $text.='应收总金额: $'.number_format($bank_rows['value'],2,'.','').'

汇款金额：'.$dransfer_money.'

汇款银行：'.$bank_rows['bank'].'

汇款人姓名：'.$bank_rows['dransfer_name'].'

购买产品:'.$bank_rows['products_name'].'';
                $text_2.='Need Check Bank Account';
                $text_3.='payment@usitrip.com';
         if (IS_LIVE_SITES ===true) {
            $to_email_address = 'payment@usitrip.com';
        } else {
            $to_email_address = 'xmzhh2000@hotmail.com';
            //$to_email_address = 'weiyi.li@usitrip.com';
        }
        $to_name = db_to_html('陈锐');
        if ($text_2!= '') {
            $email_sent_subject = $text_2;
        } else {
            $email_sent_subject = 'Points Account Update .' . ' ' . date('Y-m-d H:i:s');
        }
        $email_sent_subject.='(' . $_GET['orders_id'] . ')';
        $user_lang_code = 'gb2312';
        $email_subject = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_sent_subject);
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $text) . "\n\n";
        $email = '';
        $email.=
				EMAIL_TEXT_DEAR_A . "\n" .
				EMAIL_SEPARATOR . "\n" .
				STORE_NAME . "\n" .
				EMAIL_SEPARATOR . "\n" .
                EMAIL_TEXT_ORDER_NUMBER . ' ' . $_GET['orders_id'] . "\n" .
				EMAIL_TEXT_DATE_ORDERED." ".tep_date_long($check_orders['date_purchased']). "\n".
                $notify_comments . "\n".'中国国内首款查询';
        $comment = $email;
        $email.=$emailTable;
        //$comment = iconv(CHARSET, $user_lang_code . '//IGNORE', $comment);
        $email.= email_track_code('OrderStatus', $check_orders['customers_email_address'], $_GET['orders_id']);
        $email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email);
        //$email_text = $comments_for_email;
        $from_email_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
        //$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
        $from_email_address = 'service@usitrip.com ';
        $to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', $to_name);
        tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
            }
            if ($bank_rows['orders_status'] == '100027') {
                $text.='我们确定收到您的团款'.$dransfer_money.'，正在进一步处理您的预订。
谢谢您的合作。

We confirm receipt of your payment. We have proceeded with further processing your booking.
Thank you.

' . $EmailSignature;
                $text_2 = '走四方网--Payment Received 您的汇款已收到- 订单#' . $orders_id;
                $text_3.=$check_orders['customers_email_address'];
                $text_3.='        手机号:'.$the_customers_cellphone ;
                
        $notify_comments = '';
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $text) . "\n\n";

        //$to_email_address = 'weiyi.li@usitrip.com';


        if ($text_2 != '') {
            $email_sent_subject = $text_2;
        } else {
            $email_sent_subject = 'Points Account Update .' . ' ' . date('Y-m-d H:i:s');
        }

        //$email_sent_subject.='('.$_POST['orders_id'].')';
        $user_lang_code = customers_language_code($check_orders['customers_email_address']);

        $email_subject = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_sent_subject);
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $text) . "\n\n";
        $email = '';
        //结伴同游邮件不用指名道姓{
        $order_url = tep_catalog_href_link('orders_travel_companion_info.php', 'order_id=' . $oID, 'SSL');
        if (!isset($_POST['send_travel_companion_user']) || $_POST['send_travel_companion_user'] != '1') {
            $email.= EMAIL_TEXT_DEAR . db_to_html($check_orders['customers_name']) . "\n";
            $order_url = tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL');
        }
        //结伴同游邮件不用指名道姓 end }
        $email.=
				EMAIL_TEXT_DEAR_A . "\n" .
				EMAIL_SEPARATOR . "\n" .
				STORE_NAME . "\n" .
				EMAIL_SEPARATOR . "\n" .
                EMAIL_TEXT_ORDER_NUMBER . ' ' . $_GET['orders_id'] . "\n" .
				EMAIL_TEXT_DATE_ORDERED." ".tep_date_long($check_orders['date_purchased']). "\n".
				EMAIL_TEXT_INVOICE_URL . ' ' . $order_url . "\n\n" .
				sprintf(EMAIL_TEXT_STATUS_UPDATE, 'Payment Pending（Chinese Account）支付待定')."\n\n". 
                $notify_comments . sprintf('' . "\n", 'Payment Pending（Chinese Account）支付待定（中国帐户）-订单#');
        $comment = $email;
        //$comment = iconv(CHARSET, $user_lang_code . '//IGNORE', $comment);
        $email.= email_track_code('OrderStatus', $check_orders['customers_email_address'], $_POST['orders_id']);
        $to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html($check_orders['customers_name']));
        $to_email_address = $check_orders['customers_email_address'];
        $email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email);
        //$email_text = $comments_for_email;
        $from_email_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
        //$from_email_address = STORE_OWNER_EMAGIL_ADDRESS;
        $from_email_address = 'automail@usitrip.com';
       //$to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', $to_name);
        tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', $user_lang_code);
       
               $customer_notified ='1';
               tep_db_query("insert into  orders_status_history(orders_id, orders_status_id, date_added, customer_notified, comments, updated_by) values ('" . (int) $oID . "', '" . tep_db_input((int) $_POST['status']) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comment) . "','" . $login_id . "')");
        
            }
            if ($bank_rows['orders_status'] == '100054') {
                $text.='感谢您在走四方网预定您的行程，请注意在付款前您的座位未被保留。
【请尽量在汇款信息中留下您的订单号，姓名等信息。如果您的汇款金额为整数，请您添加任意的角分以便我们能更快和更准确得为您查询】
  你的应付美元金额:'.$bank_rows['value_rmb'].'

usitrip在中国的帐号资讯如下：
招商银行： 6225 8802 8602 8015 陈锐【成都分行营门口支行】
建设银行：6227 0038 1356 0195 055 陈锐【成都第8支行】
工商银行：6222 0244 0200 3304 924 陈锐【成都市浆洗街支行】
中国银行：6013 8231 0008 5238 061 陈锐 【成都小天北街分理处】长城电子借记卡

 请在汇款后第一时间通知走四方网中国区客服部确定您的订单号和汇款金额，以便及时操作您的订单。联系电
 话:4006-333-926, 办公时间：周一-周五（上午8：00am-下午6：00pm）周六（上班时间上午9：00am-下午6：00点）

特别提示：在系统确认收到您的全额付款后将进行下一步订单处理,安排您的座位（预订）

谢谢您的配合。

' . $EmailSignature;
                $text_2.='Payment Pending（Chinese Account）支付待定（中国帐户）-订单#' . $orders_id;
                $text_3.=$check_orders['customers_email_address'];
                $text_3.='        手机号:'.$the_customers_cellphone ;
                $notify_comments = '';
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $text) . "\n\n";

        //$to_email_address = 'weiyi.li@usitrip.com';


        if ($text_2 != '') {
            $email_sent_subject = $text_2;
        } else {
            $email_sent_subject = 'Points Account Update .' . ' ' . date('Y-m-d H:i:s');
        }

        //$email_sent_subject.='('.$_POST['orders_id'].')';
        $user_lang_code = customers_language_code($check_orders['customers_email_address']);

        $email_subject = iconv(CHARSET, $user_lang_code . '//IGNORE', $email_sent_subject);
        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $text) . "\n\n";
        $email = '';
        //结伴同游邮件不用指名道姓 {
        $order_url = tep_catalog_href_link('orders_travel_companion_info.php', 'order_id=' . $oID, 'SSL');
        if (!isset($_POST['send_travel_companion_user']) || $_POST['send_travel_companion_user'] != '1') {
            $email.= EMAIL_TEXT_DEAR . db_to_html($check_orders['customers_name']) . "\n";
            $order_url = tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL');
        }
        //结伴同游邮件不用指名道姓 end }
        $email.=
				EMAIL_TEXT_DEAR_A . "\n" .
				EMAIL_SEPARATOR . "\n" .
				STORE_NAME . "\n" .
				EMAIL_SEPARATOR . "\n" .
                EMAIL_TEXT_ORDER_NUMBER . ' ' . $_GET['orders_id'] . "\n" .
				EMAIL_TEXT_DATE_ORDERED." ".tep_date_long($check_orders['date_purchased']). "\n".
				EMAIL_TEXT_INVOICE_URL . ' ' . $order_url . "\n\n" .
				sprintf(EMAIL_TEXT_STATUS_UPDATE, 'Payment Pending（Chinese Account）支付待定')."\n\n". 
                $notify_comments . sprintf('' . "\n", 'Payment Pending（Chinese Account）支付待定（中国帐户）-订单#');
        $comment = $email;
        //$comment = iconv(CHARSET, $user_lang_code . '//IGNORE', $comment);
        $email.= email_track_code('OrderStatus', $check_orders['customers_email_address'], $_POST['orders_id']);
        $to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html($check_orders['customers_name']));
        $to_email_address = $check_orders['customers_email_address'];
        $email_text = iconv(CHARSET, $user_lang_code . '//IGNORE', $email);
        //$email_text = $comments_for_email;
        $from_email_name = iconv(CHARSET, $user_lang_code . '//IGNORE', db_to_html(STORE_OWNER));
        //$from_email_address = STORE_OWNER_EMAGIL_ADDRESS;
        $from_email_address = 'automail@usitrip.com';
       //$to_name = iconv(CHARSET, $user_lang_code . '//IGNORE', $to_name);
        tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', $user_lang_code);

            }
        $orders_history_query=tep_db_query('UPDATE  `domestic_orders_history` SET  `email_sented` = 1  WHERE  `history_id` ="'.(int)$_GET['history_id'].'"');
   $replace_id =$_GET['relpace_id'];
   $relpace_email =db_to_html('已发送邮件');
   $sms = '$("#'.$replace_id.'").replaceWith("<td class=send-email-notcic>'. $relpace_email.'</td>");
           
          

		';
        $sms = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $sms) . '[/JS]';
        echo db_to_html($sms);
        exit;
}

?>
