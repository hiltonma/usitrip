<?php
//结伴同游电子参团凭证
$eticket_travel_comp_sql_str = 'SELECT otc.orders_id, otc.products_id, ope.orders_eticket_id, ope.confirmed FROM `orders_travel_companion` otc, `orders_product_eticket` ope WHERE otc.customers_id="'.(int)$customer_id.'" AND ope.orders_id = otc.orders_id AND ope.products_id = otc.products_id AND ope.confirmed=1 Group By otc.orders_travel_companion_id Order By otc.orders_travel_companion_id DESC ';
$eticket_travel_comp_split = new splitPageResults($eticket_travel_comp_sql_str, MAX_DISPLAY_ORDER_HISTORY);
$eticket_travel_comp_query = tep_db_query($eticket_travel_comp_split->sql_query);
$eticket_travel_comp_rows =tep_db_fetch_array($eticket_travel_comp_query);

?>
<?php /*?><table width="99%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
  <tr>
    <td>
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td><a href="<?php echo tep_href_link('eticket_list.php','','SSL');?>" class="sp1"><?php echo db_to_html("我的订单电子参团凭证")?></a></td>
		<td>&nbsp;</td>
        <!--
		<td><b><?php echo db_to_html("我的结伴同游电子参团凭证")?></b></td>
        -->
	  </tr>
	</table>
</td>
  </tr>
</table><?php */?>

<!--所有结伴同游电子参团凭证更新 start-->
<div class="orderRef">
	<div class="tit"><?php echo db_to_html('我的结伴同游电子参团凭证')?></div>
    <table width="100%" cellspacing="1" cellpadding="0" border="0" bgcolor="#dcdcdc" style=" margin-bottom:10px;">
        <tbody>
            <tr>
                <td align="center" width="7%" height="33" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('订单号')?></strong></td>
                <td align="center" width="50%" height="33" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('产品名称')?></strong></td>
                <td align="center" width="15%" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('电子参团凭证状态')?></strong> </td>
                <td align="center" width="15%" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('发送时间')?></strong></td>
                <td align="center" width="13%" background="/image/nav/user_bg11.gif"><strong class="color_blue"><?php echo db_to_html('操 作')?></strong></td>
            </tr>
            <?php
if((int)$eticket_travel_comp_rows['orders_eticket_id']){
	do{
?>
            <tr style="line-height:20px;">
                <td bgcolor="#FFFFFF" class="padding8" align="center"><a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.(int)$eticket_travel_comp_rows['orders_id'],'SSL')?>"><?php echo $eticket_travel_comp_rows['orders_id']?></a></td>
                      <td class="padding8" bgcolor="#FFFFFF" title="<?php echo db_to_html(tep_get_products_name($eticket_travel_comp_rows['products_id']));?>"><?php echo cutword(db_to_html(tep_get_products_name($eticket_travel_comp_rows['products_id'])),80)?></td>
                      <td align="center" bgcolor="#FFFFFF" class="padding8 tdem"><?php echo db_to_html(($eticket_travel_comp_rows['confirmed']=="1" ? '已发送' : '确认中'))?><a href="javascript:void(0);"><div><?php echo db_to_html(($eticket_travel_comp_rows['confirmed']=="1" ? '此产品已经确认电子参团凭证已经发送到您的邮箱，请前往查看，或直接点击打印电子参团凭证按钮，在这里自助取回电子参团凭证。' : '本公司会在您选定线路并收到相应款项之日起3-4个工作日内将最终的参团凭证发到您的邮箱。'))?></div></a></td>
                      <td class="padding8" align="center" bgcolor="#FFFFFF"><?php echo $eticket_travel_comp_rows['confirmed'] == "1" ? substr($eticket_travel_comp_rows['sent_time'],0,10) : '&nbsp;';?></td>
                      <td class="padding8" align="center" bgcolor="#FFFFFF"><?php if($eticket_travel_comp_rows['confirmed']=="1"){?>
                      	<a href="<?php echo tep_href_link('eticket.php','order_id='.(int)$eticket_travel_comp_rows['orders_id'].'&pid='.(int)$eticket_travel_comp_rows['products_id'].'&i='.$rowcount_i , 'NONSSL');?>" target="_blank" class="print_eticket_btn"><?php echo db_to_html('打印电子参团凭证')?></a>
						<?php }else{ ?>
                        <a href="javascript:void(0);" class="print_eticket_btn desable"><?php echo db_to_html("打印电子参团凭证");?></a>
	
	<?php }?></td>
                    </tr>
<?php
	}while($eticket_travel_comp_rows =tep_db_fetch_array($eticket_travel_comp_query));
}else{
?>
            <tr>
                <td align="center" bgcolor="#FFFFFF" class="padding8" colspan="5"><?php echo db_to_html('您暂时没有任何电子参团凭证信息！')?></td>
            </tr>
<?php
}
?> 
        </tbody>
    </table>
</div>
<!--所有结伴同游电子参团凭证更新 start-->


<?php /*?><!--原所有结伴同游电子参团凭证页面 start-->

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
  </tr>
<?php
if((int)$eticket_travel_comp_rows['orders_eticket_id']){
	do{
?>
  <tr>
    <td class="main" style="line-height:25px;">&nbsp;<a href="<?php echo tep_href_link('orders_travel_companion_info.php','order_id='.(int)$eticket_travel_comp_rows['orders_id'],'SSL')?>"><?php echo $eticket_travel_comp_rows['orders_id']?></a></td>
    <td class="main" title="<?php echo db_to_html(tep_get_products_name($eticket_travel_comp_rows['products_id']));?>">&nbsp;<?php echo cutword(db_to_html(tep_get_products_name($eticket_travel_comp_rows['products_id'])),80)?></td>
    <td class="main">&nbsp;<a href="<?php echo tep_href_link('eticket.php','order_id='.(int)$eticket_travel_comp_rows['orders_id'].'&pid='.(int)$eticket_travel_comp_rows['products_id'].'&i=0', 'NONSSL');?>" target="_blank"><?php echo VIEW_ETICKET?></a></td>
  </tr>
<?php
	}while($eticket_travel_comp_rows =tep_db_fetch_array($eticket_travel_comp_query));
}else{
?>
  <tr>
    <td colspan="3"><?php echo db_to_html('您暂时没有任何电子参团凭证信息！')?></td>
  </tr>
<?php
}
?>
</table>
	</td>
  </tr>
</table>

<!--原所有结伴同游电子参团凭证页面 end--><?php */?>



<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $eticket_travel_comp_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $eticket_travel_comp_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
</table>
	</td>
  </tr>
</table>
