<table class="tableList" >
<caption><h4>订单历史操作总览</h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th>状态编码 
<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=orders_status_id&order_type=ASC')) ?>"><img border="0" src="images/arrow_up.gif"></a> 
<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=orders_status_id&order_type=DESC')) ?>"><img border="0" src="images/arrow_down.gif"></a></th>
<th>状态名称 </th>
<th>更新次数<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=total&order_type=ASC')) ?>"><img border="0" src="images/arrow_up.gif"></a> 
<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=total&order_type=DESC')) ?>"><img border="0" src="images/arrow_down.gif"></a></th>
</tr>
<?php $total=0 ; foreach ($records as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo $record['orders_status_id']?></td>
	<td><?php echo tep_get_orders_status_name($record['orders_status_id'])?></td>
	<td><?php echo $record['total']?></td>
</tr>
<?php $total+= intval($record['total']);}?>
<tr class="total" >
	<td></td>
	<td>合计</td>
	<td><?php echo $total?></td>
</tr>
</table>