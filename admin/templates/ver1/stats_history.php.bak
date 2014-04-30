<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="css2/global.css" />
<link rel="stylesheet" type="text/css" href="css2/expert.css" />
<link rel="stylesheet" type="text/css" href="css2/calendar.css" />
<script type="text/javascript" src="js2/jquery.js"></script>
<script type="text/javascript" src="js2/global.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<title>index</title>
</head>
<body >
 
 <?php if(!empty($VIN_ERROR)){?>
 <div class="error"><?php echo $VIN_ERROR?></div>
 <?php }elseif (!empty($VIN_NOTICE)){?>
  <div class="notice"><?php echo $VIN_NOTICE?></div>
 <?php }
 
 $param = array('page','order','sort','timeStart','timeEnd','pointcards_id_start','pointcards_id_end','configuration_value');
 
 if(count($records) > 0 ){ ?>
    <div>
        <h2 class="conSwitch" id="ConSwitchH_2"><?php echo db_to_html($_GET['url'].'的访问历史记录'); ?></h2>
        <div class="minCon" id="ConSwitchCon_2">
            <div>
			<a href="<?php echo tep_href_link('stats_pageview.php','url='.urlencode($_GET['url']))?>"><?php echo db_to_html("返回")?></a>
<table class="expertTable" id="ExpertTable" border="0" cellpadding="0" cellspacing="0">
<tbody>
    <tr>
		<th>ID</th>
		<th>URL</th>
		<th>IP</th>
		<th><?php echo db_to_html('附加信息'); ?></th>
		<th><?php echo db_to_html('访问时间'); ?></th>
    </tr>
	<?php foreach($records as $record) { ?>
    <tr>
		<td><?php echo $record['history_id']?></td>
		<td>
		<a href="<?php echo db_to_html($record['url'])?>" target="_blank"><?php echo db_to_html($record['url'])?></a><br/>
		Refer:<a href="<?php echo db_to_html($record['refer_url'])?>" target="_blank"><?php echo db_to_html($record['refer_url'])?></a></td>
        <td><?php echo $record['ip']?></td>	
		<td><?php echo $record['extra_msg']?></td>
		<td><?php echo $record['created']?></td>
    </tr>
	<?php } //endforeach records display?>
	<tfoot>
<tr>
    <td bgcolor="#f5f5f5" colspan="7">
        <div class="pageNum"><?php echo $split_left_content?></div>
        <div class="page"><?php echo $split_right_content?></div>
    </td>
</tr>
</tfoot>
</tbody>
</table>
          </div>
 <?php }// endif  records check ?>



<script type="text/javascript">
$(function(){
    $("#ExpertTable>tbody>tr").each(function(index) {
        if((index+1)%2 == 0 ){
            $(this).addClass("trEven");
        }
    });
}); 
</script>
        </div>
    </div>
</div>
</body>
</html>
