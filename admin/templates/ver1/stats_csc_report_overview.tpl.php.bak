<table width="100%" border=0>
<tr><td width="50%" valign="top">
<table class="tableList" >
<caption><h4><?php echo $LIST_TITLE ?> </h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th width="40%">项目</th>
<th width="30%">总数</th>
<th width="30%">每天平均</th>
</tr>
<?php  foreach ($records as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo $record['statsName']?></td>
	<td><?php echo $record['total']?></td>
	<td><?php echo $record['avg']?></td>
</tr>
<?php }?>
</table>

<table class="tableList" >
<caption><h4>订单历史操作分布 </h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th width="50%">项目</th>
<th width="50%">总数</th>
</tr>
<?php $total = 0 ;  foreach ($records3 as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo tep_get_orders_status_name($record['orders_status_id'])?></td>
	<td><?php echo $record['total']?></td>
</tr>
<?php $total+= intval($record['total']);}?>
<tr class="total" >
	<td>合计</td>
	<td ><?php echo $total?></td>
</tr>
</table>

</td>

<td width="50%" valign="top">

<table class="tableList" >
<caption><h4>订单处理情况统计 </h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th width="50%">项目</th>
<th width="50%">总数</th>
</tr>
<?php $total=0; foreach ($records2 as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo tep_get_orders_status_name($record['orders_status'])?></td>
	<td><?php echo $record['total']?></td>
</tr>
<?php $total+= intval($record['total']);}?>
<tr class="total" >
	<td>订单总数</td>
	<td ><?php echo $total?></td>
</tr>
</table>
</td></tr></table>