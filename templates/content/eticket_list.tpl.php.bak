<?php
$no_eticket = true;
include_once(DIR_FS_CLASSES . 'order.php');
?>
<?php
//我的电子参团凭证
//ope.confirmed=1 AND
$eticket_sql_str = 'SELECT ope.orders_eticket_id, ope.orders_id, ope.products_id , ope.orders_products_id, ope.confirmed, ope.sent_time FROM  `orders` o , `orders_product_eticket` ope WHERE  o.customers_id ="'.(int)$customer_id.'" AND o.orders_id=ope.orders_id Group By ope.orders_eticket_id Order By ope.orders_eticket_id DESC';
$eticket_split = new splitPageResults($eticket_sql_str, 20);
$eticket_query = tep_db_query($eticket_split->sql_query);
$eticket_rows =tep_db_fetch_array($eticket_query);
if((int)$eticket_rows['orders_eticket_id']){
	$no_eticket = false;
?>

<div class="orderRef">
  <div class="tit"><?php echo db_to_html("普通订单电子参团凭证");?></div>
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#dcdcdc" style=" margin-bottom:10px;">
                    <tr>
                    <td width="10%" height="33" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("订单号");?></strong></td>
                      <td width="50%" height="33" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("产品名称");?></strong></td>
                      <td width="17%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("电子参团凭证状态");?></strong> </td>
                      <td width="12%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("发送时间");?></strong></td>
                      <td width="12%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("操 作");?></strong></td>
                    </tr>
                    <tr style="line-height:20px;">
<?php /*
<table width="99%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="infoBox">
  <tr class="infoBoxContents" style="background-color:#FFFFFF">
    <td>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="productListing-heading">
    <td class="productListing-heading" style="line-height:25px;">&nbsp;<?php echo ORDERS_ID?></td>
    <td class="productListing-heading">&nbsp;<?php echo PROD_NAME?></td>
    <td class="productListing-heading">&nbsp;<?php echo ACTION?></td>
  </tr>*/?>
<?php
	do{
	//hotel-extension {
	$order = new order((int)$eticket_rows['orders_id']);
	$rowcount_i = 0;
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {		
		if(isset($eticket_rows['orders_products_id']) && $eticket_rows['orders_products_id'] != ''){
			if($order->products[$i]['orders_products_id']==$eticket_rows['orders_products_id']){
				$rowcount_i = $i;
			}
		}else{
			if($order->products[$i]['id']==$eticket_rows['products_id']){
				$rowcount_i = $i;
			}
		}
		/*if($order->products[$i]['id'] == ){
			$rowcount_i = $i;
		}*/
	}//}hotel-extension
?>
<td bgcolor="#FFFFFF" class="padding8" align="center"><a href="<?php echo tep_href_link('account_history_info.php','order_id='.(int)$eticket_rows['orders_id'],'SSL')?>"><?php echo $eticket_rows['orders_id']?></a></td>
                      <td class="padding8" bgcolor="#FFFFFF" title="<?php echo db_to_html(tep_get_products_name($eticket_rows['products_id']));?>"><?php echo cutword(db_to_html(tep_get_products_name($eticket_rows['products_id'])),80)?></td>
                      <td align="center" bgcolor="#FFFFFF" class="padding8 tdem"><?php echo db_to_html(($eticket_rows['confirmed']=="1" ? '已发送' : '确认中'))?><a href="javascript:void(0);"><div><?php echo db_to_html(($eticket_rows['confirmed']=="1" ? '此产品已经确认电子参团凭证已经发送到您的邮箱，请前往查看，或直接点击打印电子参团凭证按钮，在这里自助取回电子参团凭证。' : '本公司会在您选定线路并收到相应款项之日起1-4个工作日内，将最终的参团凭证发送到您的邮箱。'))?></div></a></td>
                      <td class="padding8" align="center" bgcolor="#FFFFFF"><?php echo $eticket_rows['confirmed']=="1" ? substr($eticket_rows['sent_time'],0,10) : '&nbsp;';?></td>
                      <td class="padding8" align="center" bgcolor="#FFFFFF"><?php if($eticket_rows['confirmed']=="1"){?>
                      	<a href="<?php echo tep_href_link('eticket.php','order_id='.(int)$eticket_rows['orders_id'].'&pid='.(int)$eticket_rows['products_id'].'&i='.$rowcount_i, 'NONSSL');?>" target="_blank" class="print_eticket_btn"><?php echo db_to_html('打印电子参团凭证')?></a>
						<?php }else{ ?>
                        <a href="javascript:void(0);" class="print_eticket_btn desable"><?php echo db_to_html("打印电子参团凭证");?></a>
	
	<?php }?></td>
                    </tr>
                    
<?php /*  <tr>
    <td class="main" style="line-height:25px;">&nbsp;<a href="<?php echo tep_href_link('account_history_info.php','order_id='.(int)$eticket_rows['orders_id'],'SSL')?>"><?php echo $eticket_rows['orders_id']?></a></td>
    <td class="main" title="<?php echo db_to_html(tep_get_products_name($eticket_rows['products_id']));?>">&nbsp;<?php echo cutword(db_to_html(tep_get_products_name($eticket_rows['products_id'])),80)?></td>
    <td class="main">
	<?php if($eticket_rows['confirmed']=="1"){?>
	&nbsp;<a href="<?php echo tep_href_link('eticket.php','order_id='.(int)$eticket_rows['orders_id'].'&pid='.(int)$eticket_rows['products_id'].'&i='.$rowcount_i, 'NONSSL');?>" target="_blank"><?php echo db_to_html("已发送")?></a>
	<?php }else{ echo db_to_html("确认中");?>
	
	<?php }?>
	</td>
  </tr>*/?>
<?php
	}while($eticket_rows =tep_db_fetch_array($eticket_query));
?>

</table>
</div>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $eticket_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $eticket_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
</table>

<?php
}
?>

<!--插入结伴同游电子参团凭证前5条-->
<?php
//结伴同游电子参团凭证
//AND ope.confirmed=1 
$eticket_travel_comp_sql_str = 'SELECT otc.orders_id, otc.products_id, ope.orders_eticket_id, ope.orders_products_id, ope.confirmed, ope.sent_time  FROM `orders_travel_companion` otc, `orders_product_eticket` ope WHERE otc.customers_id="'.(int)$customer_id.'" AND ope.orders_id = otc.orders_id AND ope.products_id = otc.products_id Group By otc.orders_travel_companion_id Order By otc.orders_travel_companion_id DESC Limit 5';
$eticket_travel_comp_query = tep_db_query($eticket_travel_comp_sql_str);
$eticket_travel_comp_rows =tep_db_fetch_array($eticket_travel_comp_query);
if((int)$eticket_travel_comp_rows['orders_eticket_id']){
	$no_eticket = false;
?>
<div class="orderRef">
  <div class="tit"><div class="float_right"><a class="sp1" href="<?php echo tep_href_link('eticket_travel_companion_list.php');?>"><?php echo db_to_html('所有结伴同游电子参团凭证')?></a></div><?php echo db_to_html("我的结伴同游电子参团凭证")?></div>
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#dcdcdc" style=" margin-bottom:10px;">
                    <tr>
                    <td width="7%" height="33" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('订单号')?></strong></td>
                      <td width="50%" height="33" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("产品名称");?></strong></td>
                      <td width="15%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("电子参团凭证状态");?></strong> </td>
                      <td width="15%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("发送时间");?></strong></td>
                      <td width="13%" align="center" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html("操 作");?></strong></td>
                    </tr>
                    <tr style="line-height:20px;">
<?php
	do{
		$order = new order((int)$eticket_travel_comp_rows['orders_id']);
		$rowcount_i = 0;
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			if(isset($eticket_rows['orders_products_id']) && $eticket_travel_comp_rows['orders_products_id'] != ''){
				if($order->products[$i]['orders_products_id']==$eticket_travel_comp_rows['orders_products_id']){
					$rowcount_i = $i;
				}
			}else{
				if($order->products[$i]['id']==$eticket_travel_comp_rows['products_id']){
					$rowcount_i = $i;
				}
			}
			/*
			if($order->products[$i]['id'] == $eticket_travel_comp_rows['products_id']){
				$rowcount_i = $i;
			}
			*/
		}
?>
<td bgcolor="#FFFFFF" class="padding8" align="center"><a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.(int)$eticket_travel_comp_rows['orders_id'],'SSL')?>"><?php echo $eticket_travel_comp_rows['orders_id']?></a></td>
                      <td class="padding8" bgcolor="#FFFFFF" title="<?php echo db_to_html(tep_get_products_name($eticket_travel_comp_rows['products_id']));?>"><?php echo cutword(db_to_html(tep_get_products_name($eticket_travel_comp_rows['products_id'])),80)?></td>
                      <td align="center" bgcolor="#FFFFFF" class="padding8 tdem"><?php echo db_to_html(($eticket_travel_comp_rows['confirmed']=="1" ? '已发送' : '确认中'))?><a href="javascript:void(0);"><div><?php echo db_to_html(($eticket_travel_comp_rows['confirmed']=="1" ? '此产品已经确认电子参团凭证已经发送到您的邮箱，请前往查看，或直接点击打印电子参团凭证按钮，在这里自助取回电子参团凭证。' : '本公司会在您选定线路并收到相应款项之日起1-4个工作日内，将最终的参团凭证发送到您的邮箱。'))?></div></a></td>
                      <td class="padding8" align="center" bgcolor="#FFFFFF"><?php echo $eticket_travel_comp_rows['confirmed'] == "1" ? substr($eticket_travel_comp_rows['sent_time'],0,10) : '&nbsp;';?></td>
                      <td class="padding8" align="center" bgcolor="#FFFFFF"><?php if($eticket_travel_comp_rows['confirmed']=="1"){?>
                      	<a href="<?php echo tep_href_link('eticket.php','order_id='.(int)$eticket_travel_comp_rows['orders_id'].'&pid='.(int)$eticket_travel_comp_rows['products_id'].'&i='.$rowcount_i , 'NONSSL');?>" target="_blank" class="print_eticket_btn"><?php echo db_to_html('打印电子参团凭证')?></a>
						<?php }else{ ?>
                        <a href="javascript:void(0);" class="print_eticket_btn desable"><?php echo db_to_html("打印电子参团凭证");?></a>
	
	<?php }?></td>
                    </tr>
<?php
	}while($eticket_travel_comp_rows =tep_db_fetch_array($eticket_travel_comp_query));
?>
</table>
</div>
<?php
}
?>
<?php
if($no_eticket == true){
	echo db_to_html('您暂时没有任何电子参团凭证信息！');
}
?>
