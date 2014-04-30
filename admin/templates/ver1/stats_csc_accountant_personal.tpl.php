<table class="tableList" >
<caption><h4><?php echo $LIST_TITLE?> </h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th width="20%">ĞÕÃû</th>
<th width="20%">Charge Capture Report Updates </th>
<!--  <th width="20%">Uncharged Report Updates </th>-->
<th width="20%">Payment History Report (Current) Updates </th>
<th width="20%">Order Status Updates </th>
</tr>
<?php foreach ($records as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo $record['userName']?></td>
	<td><?php echo $record[1]?></td>
	<!-- <td><?php echo $record[2]?></td>	 -->
	<td><?php echo $record[3]?></td>	
	<td><?php echo $record['os']?></td>
</tr>
<?php }?>
</table>