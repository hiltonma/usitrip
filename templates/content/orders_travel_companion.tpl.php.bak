
					 <!-- content main body start -->
					 		<table width="99%"  border="0" cellspacing="0" cellpadding="0">

							  <tr>
								<td class="main">
											<table border="0" width="100%" align="center" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>" style="margin-top:8px;">
<!--											  <tr>
												<td class="tdTitle"><?php echo db_to_html('结伴同游订单')?></td>
											  </tr>-->

												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_top(false, false, $header_text);
												}
												// EOF: Lango Added for template MOD
												?>
												<?php
												  if ($messageStack->size('account') > 0) {
												?>
													  <tr>
														<td><?php echo $messageStack->output('account'); ?></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
												<?php
												  }

												?>

													  <tr>
														<td>
                            <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#dcdcdc">
      <tr>
        <td width="10%" height="33" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('订单号')?></strong></td>
        <td width="30%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('行程')?></strong> </td>
        <td width="15%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('参团人姓名')?></strong></td>
        <td width="15%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('应付款')?></strong></td>
        <td width="10%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('付款状态')?></strong></td>
        <td width="10%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('订单状态')?></strong></td>
        <td width="10%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('操作')?></strong></td>
      </tr>



<!--<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td><strong><?php echo db_to_html('订单号')?></strong></td>
    <td><strong><?php echo db_to_html('行程')?></strong></td>
    <td><strong><?php echo db_to_html('参团人姓名')?></strong></td>
    <td><strong><?php echo db_to_html('应付款')?></strong></td>
    <td><strong><?php echo db_to_html('订单状态')?></strong></td>
    <td><strong><?php echo db_to_html('操作')?></strong></td>
  </tr>-->
<?php
//取得结伴同游订单
$sql_str = 'SELECT * FROM `orders_travel_companion` WHERE customers_id="'.(int)$customer_id.'" order by orders_travel_companion_id desc ';

$sql = tep_db_query($sql_str);
while($rows = tep_db_fetch_array($sql)){
	
	// 取得产品数据，判断是否每个产品都已经付款 
	$prows = tep_get_products_by_order($rows['orders_id']);
	$is_pay = true; // 保存是否已全部付款 by lwkai add 2012-04-23
	$payment_status = array();
	foreach($prows as $prow){
		if ((int)$prow['orders_products_payment_status'] != 1) {
			$is_pay = false;
		}
		$payment_status[] = tep_get_orders_products_payment_status_name($prow['orders_products_payment_status']);
	}
	
?>
  <tr>
    <td align="center" bgcolor="#FFFFFF" style="padding:10px 5px;"><?php echo $rows['orders_id']?></td>
    <td align="center" bgcolor="#FFFFFF"><a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.(int)$rows['orders_id'],'SSL')?>" title="<?php echo db_to_html(tep_get_products_name($rows['products_id']))?>"><?php echo cutword(db_to_html(tep_get_products_name($rows['products_id'])),46)?></a></td>
    <td align="center" bgcolor="#FFFFFF"><?php echo db_to_html(tep_filter_guest_chinese_name(tep_db_output($rows['guest_name'])))?></td>
    <td align="center" bgcolor="#FFFFFF"><?php echo $currencies->format(db_to_html(tep_db_output($rows['payables'])))?></td>
    <td align="center" bgcolor="#FFFFFF"><?php echo db_to_html(implode('<br />',array_unique($payment_status)));?></td>
    <td align="center" bgcolor="#FFFFFF">
	<?php
	$orders_sql = tep_db_query('SELECT os.orders_status_name FROM `orders` o , `orders_status` os WHERE orders_id ="'.(int)$rows['orders_id'].'" AND o.orders_status=os.orders_status_id ');
	$orders_row = tep_db_fetch_array($orders_sql);
	//tom added
    $result_echo_ss=tep_get_orders_status_name($rows['orders_id']);
    echo db_to_html($result_echo_ss);

	?>
	</td>
    <td align="center" bgcolor="#FFFFFF"><?php
    if ($is_pay == true ) {?><a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.(int)$rows['orders_id'],'SSL')?>"><?php echo db_to_html('详细')?></a><?php
	} else { ?>
    <a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.(int)$rows['orders_id'],'SSL')?>"><?php echo db_to_html('详情/去付款')?></a>
    <?php }?></td>
  </tr>

<?php
}
?>


														 <!-- amit added to refer friend start  -->


													  <!-- amit added to refer friend end  -->

												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_bottom();
												}
												// EOF: Lango Added for template MOD
												?>
							    </table>							 </td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  <tr>
							    <td height="15"></td>
						      </tr>
					 </table>
           </td>
           </tr>
           </table>
					 		<!-- content main body end -->