<?php foreach ($records as $precord){?>
<table class="tableList"  onclick="if(jQuery('#tableList<?php echo $precord['userId']?>').css('display') == 'none')jQuery('#tableList<?php echo $precord['userId']?>').fadeIn('slow');else jQuery('#tableList<?php echo $precord['userId']?>').fadeOut('slow');">
<caption><h4><?php echo $precord['name']?> </h4> <small>&lt;<?php echo $start_date ?> - <?php echo $end_date ?>&gt;</small> <span id="total<?php echo $precord['userId']?>">合计:0</span></caption>
</table>
<table class="tableList"  id="tableList<?php echo $precord['userId']?>" style="display:none;">
<tr class="heading">
<th width="20%">状态编码 
<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=orders_status_id&order_type=ASC')) ?>"><img border="0" src="images/arrow_up.gif"></a> 
<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=orders_status_id&order_type=DESC')) ?>"><img border="0" src="images/arrow_down.gif"></a></th>
<th width="30%">状态名称 </th>
<th width="30%">人员名称 </th>
<th width="20%">更新次数<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=total&order_type=ASC')) ?>"><img border="0" src="images/arrow_up.gif"></a> 
<a href="<?php echo tep_href_link('stats_csc.php',tep_get_all_get_params_fix('order_field=total&order_type=DESC')) ?>"><img border="0" src="images/arrow_down.gif"></a></th>
</tr>
<?php $total= 0 ; foreach ($precord['records'] as $record){
$total+=  intval($record['total']);?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo $record['orders_status_id']?></td>
	<td><?php echo tep_get_orders_status_name($record['orders_status_id'])?></td>
	<td><?php echo $record['userName']?>(<?php echo $record['userId']?>)</td>
	<td><?php echo $record['total']?></td>
</tr>
<?php }?>
<tr class="total">
	<td></td>
	<td></td>
	<td>合计</td>
	<td><?php echo $total?></td>
</tr>
</table>
<script type="text/javascript">
jQuery("#total<?php echo $precord['userId']?>").html("合计:<?php echo $total?>");
</script>
<?php }?>