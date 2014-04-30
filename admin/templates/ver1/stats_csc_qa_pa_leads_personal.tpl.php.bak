<table class="tableList" >
<caption><h4><?php echo $LIST_TITLE?> </h4><small><?php echo $start_date ?> - <?php echo $end_date ?></small></caption>
<tr class="heading">
<th width="16%">ĞÕÃû</th>
<th width="12%">Order Status(avg) </th>
<th width="12%">QA </th>
<th width="12%">LEADS </th>
<th width="12%">PA </th>
<th width="12%">E-Mail Answer </th>
<th width="12%">IP Phone(in/out) </th>
<th width="12%">ÆôÍ¨±¦ (in/out)</th>
</tr>
<?php foreach ($records as $record){?>
<tr onmouseover="this.style.background='#ffc'" onmouseout="this.style.background=''" >
	<td><?php echo $record['userName']?></td>
	<td><?php echo $record['os'] ?></td>
	<td><?php echo $record['qa']?></td>
	<td><?php echo $record['leads']?></td>	
	<td><?php echo $record['pa']?></td>	
	<td><?php echo $record['email']?></td>
	<td><?php echo $record['phone_callin']?> / <?php echo $record['phone_callout']?></td>
	<td><?php echo $record['qtb_callin']?> / <?php echo $record['qtb_callout']?></td>
</tr>
<?php }?>
</table>