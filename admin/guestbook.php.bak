<?php
require('includes/application_top.php');
require('includes/classes/guestbook.php');	//载入留言本的类文件
$guestbooks = new guestbook;

if($_POST['action']=="1"){
	$insert_id = $guestbooks->insert_or_update($_POST,'insert');
	if((int)$insert_id){
		tep_redirect('guestbook.php');
	}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript" type="text/javascript">
function checkForm1()
{
  var toid=$("#to_login_id_add").val(); if(toid.length==0){ alert("请选择留言对象!"); return false;}
  var scontent=$("#content_add").val(); if(scontent.length<5){ alert("留言内容至少要5个字"); return false;}
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo db_to_html('留言本')?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        <fieldset><legend>新增留言</legend>
        <form name="form1" id="form1" action="" method="post" onSubmit="return checkForm1()">
		<input name="action" type="hidden" value="1">
		  <table>
		    <tr><td>订单号:</td><td><?php echo tep_draw_input_field('orders_id','','id="orders_id_add" style="width:70px;ime-mode:disabled;"')?></td></tr>
		    <tr>
			  <td>留言给:</td>
			  <td>
				<?php echo tep_draw_pull_down_menu('to_login_id',  guestbook::adminList(),'','id="to_login_id_add" '); ?>
			  </td>
			</tr>
			<tr>
			 <td>紧急程度:</td>
			 <td>
			 <label style="color:#FF0000; font-weight:bolder;font-size:120%;" id="Isimportant2"><?php echo tep_draw_radio_field('is_important','2','','','id=Isimportant2') ?> 非常紧急</label>			 
             <label style="color:#FF0000; font-weight:bolder;" for="Isimportant1"><?php echo tep_draw_radio_field('is_important','1','','','id=Isimportant1') ?>紧急</label>
             <label>
			 <?php
			 $_checked = false;
			 if(!isset($_REQUEST['is_important'])){ $_checked = true; }
			 echo tep_draw_radio_field('is_important','0',$_checked,'','id=Isimportant0');
			 ?> 普通</label>
			 </td>
			</tr>
			<tr>
			  <td>留言内容:</td>
			  <td>			  
			  <?php echo tep_draw_textarea_field('content','','100','3','','id="content_add"')?>
			  </td>
			</tr>
			<tr>
			  <td></td><td><input name="submit" type="submit" value="submit"></td>
			</tr>
			
		  </table>
		</form>
		</fieldset>
       </td>
      </tr>	  
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
			<form name="form2" id="form2" action="" method="get">
			留言人:<?php echo tep_draw_pull_down_menu('sent_login_id',  guestbook::adminList(),'','id="sent_login_id"',false); ?>
			留言对象:<?php echo tep_draw_pull_down_menu('to_login_id',  guestbook::adminList(),'','id="sent_login_id"',false); ?>
			解决人:<?php echo tep_draw_pull_down_menu('answer_login_id',  guestbook::adminList(),'','id="sent_login_id"',false); ?>
			时间:<?php echo tep_draw_input_num_en_field('time_begin','','style="width:85px;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?> 
			-
			<?php echo tep_draw_input_num_en_field('time_end','','style="width:85px;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?> 
			订单号:<input name="orders_id" type="text" style="width:70px;ime-mode:disabled;"><input name="submit" type="submit" value="QUERY">
			</form>
		  </fieldset>
		  <!--search form end-->
		</td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Search Results </legend>
		  留言列表
		<form action="" method="post" enctype="multipart/form-data" name="form3">
		  <table border="1">
			<tr>
			  <th>编号</th><th>重要</th><th>添加时间</th><th>订单号</th><th>发起人</th><th>内容</th>
			  <th>接收人</th><th>回复时间</th><th>回复人</th><th>回复内容</th>
			</tr>
			<?php
			$data = $guestbooks->lists('','*');
			$l=count($data);
			for($i=0;$i<$l;$i++)
			{
				$td = '<tr>';
				$td .='<td>'.$data[$i]['notebook_id'].'</td>';
				$td .='<td>'.$data[$i]['is_important'].'</td>';
				$td .='<td>'.$data[$i]['add_date'].'</td>';
				$td .='<td>'.$data[$i]['orders_id'].'</td>';
				$td .='<td>'.$data[$i]['sent_login_id'].'</td>';
				$td .='<td>'.$data[$i]['content'].'</td>';
				$td .='<td>'.$data[$i]['to_login_id'].'</td>';
				$td .='<td>'.$data[$i]['answer_date'].'</td>';
				$td .='<td>'.$data[$i]['answer_login_id'].'</td>';	
				$td .='<td>'.$data[$i]['answer_content'].'</td>';
				//$td = $td.'<td>'.$data[$i][''].'</td>';	
				echo $td;		
			}
			?>		
			<tr><td colspan="6"><?php echo $data['splitPages']['count'];?></td><td colspan="4"><?php echo $data['splitPages']['links'];?></td></tr>		
		  </table>
			<input name="action" type="hidden" id="action" value="1">
		</form>
		</fieldset>		
	    </td>
      </tr>
      <tr>
            
			<td>&nbsp;</td>
          </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>