<?php
//我们与供应商交流的状态历史
$provider_status_historys = tep_get_provider_order_products_status_history($order->products[$i]['orders_products_id']);
?>
<table class="bor_tab table_td_p"  width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr class="bor_tab table_td_p">
		<td class="tab_t tab_line1" align="center">&nbsp;
		<?php echo $provider_status_historys['heardTitie'][0]?>
		</td>
		<td class="tab_t tab_line1" align="center"><?php echo $provider_status_historys['heardTitie'][1]?></td>
	</tr>
	<?php
	for($aphi=0, $an=sizeof($provider_status_historys)-1; $aphi<$an; $aphi++){
		//显示内容以我们发布的内容为主导，如果是供应商回复的内容应该跟到我们右边的单元格，如果是我们回复供应商的则要跳下一行左边单元格显示。
		if(tep_not_null($provider_status_historys[$aphi][0]) || tep_not_null($provider_status_historys[$aphi+1][1])){
	?>
	<tr>
		<td valign="bottom" style="width:50%; background-color:#FFF; padding:5px; border-bottom: 1px solid #F4CF91; border-right: 1px solid #F4CF91;"><?php if(tep_not_null($provider_status_historys[$aphi][0])){ echo $provider_status_historys[$aphi][0]['provider_order_status_name'].' - '.nl2br(tep_db_output($provider_status_historys[$aphi][0]['provider_comment'])).'<br><b>'.date('n/j/Y H:i:s',strtotime($provider_status_historys[$aphi][0]['provider_status_update_date']))." ".tep_get_admin_customer_name($provider_status_historys[$aphi][0]['popc_updated_by'], (int)$provider_status_historys[$aphi][0]['popc_user_type']).'</b>'; }?>&nbsp;</td>
		
		<td align="left" valign="bottom" style="width:50%; background-color:#FEFBED; padding:5px; border-bottom: 1px solid #F4CF91;"><?php if(tep_not_null($provider_status_historys[$aphi+1][1])){ echo tep_db_output($provider_status_historys[$aphi+1][1]['provider_order_status_name']).' - '.nl2br(tep_db_output($provider_status_historys[$aphi+1][1]['provider_comment'])).'<br><b>'.date('n/j/Y H:i:s',strtotime($provider_status_historys[$aphi+1][1]['provider_status_update_date']))." ".$provider_status_historys[$aphi+1][1]['popc_updated_by'].'</b>'; }?>&nbsp;</td>
	</tr>
	<?php }}?>
</table>
