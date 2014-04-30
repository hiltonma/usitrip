<table class="tableList" >
<caption><h4><?php echo $LIST_TITLE?> </h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th width="20%">пуцШ</th>
<th width="20%">ORDER STATUS </th>
<th width="20%">QA </th>
<th width="20%">LEADS </th>
<th width="20%">PA </th>
</tr>
<?php foreach ($records as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo $record['userName']?></td>
	<td><?php echo $record['os']?></td>
	<td><?php echo $record['qa']?></td>
	<td><?php echo $record['leads']?></td>	
	<td><?php echo $record['pa']?></td>
</tr>
<?php }?>
</table>