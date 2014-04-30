<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php'); 

$sql = tep_db_query("select * from `reviews` where  reviews_type='".$_POST['reviews_type']."' AND reviews_type_id='".$_POST['reviews_type_id']."' order by reviews_id DESC ");
$rows =  tep_db_fetch_array($sql);

/*/统计评论总数
$sql_total = tep_db_query("select count(*) as c_num from `reviews` where  reviews_type='".$_POST['reviews_type']."' AND reviews_type_id='".$_POST['reviews_type_id']."' ");
$row_total =  tep_db_fetch_array($sql_total);
$totalRows = (int)$row_total['c_num'];*/

$sql_total = tep_db_query("select * from `".$_POST['reviews_type']."` where  ".$_POST['reviews_type']."_id='".$_POST['reviews_type_id']."' ");
$row_total =  tep_db_fetch_array($sql_total);
$totalRows = (int)$row_total['reviews_total'];
$now_name = iconv("gb2312","utf-8",tep_db_output($row_total[$_POST['reviews_type'].'_name']));

$fbpl=iconv("gb2312","utf-8","[发表评论]");
$close=iconv("gb2312","utf-8","[×]");
$move =iconv("gb2312","utf-8","移动");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr>
	<td width="3%" align="left" valign="middle" bgcolor="#3399FF"><img onclick="move_action('Layer_look_reviews_<?php echo $_POST['reviews_type_id']?>')" style="margin: 0px;" src="move.gif" alt="<?php echo $move ?>" width="25" height="21" /></td>
	<td width="2%" align="left" valign="middle" nowrap="nowrap" bgcolor="#3399FF">&nbsp;<a href="JavaScript:OpenWriteReviews('Layer_reviews_<?php echo $reviews_type_id ?>')"><?php echo $fbpl?></a></td>
	<td width="94%" align="left" valign="middle" bgcolor="#3399FF" style="padding-left:3px; color:#E8E7FE"><?php echo $now_name ?></td>
	<td width="1%" align="right" valign="middle" nowrap="nowrap" bgcolor="#3399FF"><a onclick="document.getElementById('re_total_<?php echo $_POST['reviews_type_id']?>').innerHTML='<?php echo $totalRows?>'; document.getElementById('re_msn_<?php echo $_POST['reviews_type_id']?>').style.display='';" href="JavaScript:CloseReviews('Layer_look_reviews_<?php echo $reviews_type_id ?>')" title="close"><?php echo $close ?></a></td>
  </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC">
  
  <?php do{?>
  
  <tr>
    <td align="left" valign="bottom" class="reviews_list"><?php echo iconv("gb2312","utf-8",nl2br(tep_db_output($rows['reviews_content']))); ?></td>
    <td align="right" valign="bottom" nowrap="nowrap" class="reviews_list"><?php echo iconv("gb2312","utf-8","&nbsp;&nbsp;".tep_db_output($rows['reviews_people_name']))." ".$rows['reviews_time']; ?></td>
  </tr>
  <?php }while($rows =  tep_db_fetch_array($sql));?>
</table>

