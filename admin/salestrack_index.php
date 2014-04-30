<?php
require('includes/application_top.php');
/*如果不是销售跟踪管理员,直接跳转到自己的页面*/
if($can_top_salestrack !== true){
	tep_redirect('salestrack_list.php?loginid='.$salestrack->login_id);
}

require('includes/classes/salestrack.php');	//载入销售跟踪的类文件
$salestrack = new salestrack();

$admin_list = $salestrack->admin_list();/*获取后台用户列表,用于显示列表及人员名字匹配*/
$action=$_GET['action'];
if($action==""){
	$action="search";
}

if(!tep_not_null($_GET['is_important'])){ $is_important='-1'; }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>----销售跟踪记录----内部使用</title>
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
<style type="text/css">
.tbList { border:1px solid #CCCCCC; border-collapse:collapse;}
.tbList th{ background-color:#006699; color:#FFFFFF; font-weight:bolder; font-size:90%; border:1px solid #CCCCCC; padding:3px;}
.tbList td{ border:1px solid #CCCCCC; padding:3px; font-size:90%;}
.tbList td span.imp2{color:#FF0000; font-weight:bolder;}
.tbList td span.imp1{color:#FF0000; font-weight:normal;}
.tbList td span.imp0{color:#000000; font-weight:normal;}
.tbList tr.bc{ background-color:#EEEEEE}
.tbList .finish{color:#0000FF;}

ul.admin_list{float:left;}
ul.admin_list li{float:left; width:180px; margin:5px 5px;}
ul.admin_list a,ul.admin_list a:visited{padding:3px;}
ul.admin_list a:hover{background-color:#0000FF; color:#FFFFFF;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top">
		<h4>销售跟踪之用户列表</h4>
		<ul class="admin_list">
		<?php 
		$n=count($admin_list);
		for($i=0;$i<$n;$i++){
			if($admin_list[$i]['id']!=''){
		?>
		  <li><a href="salestrack_list.php?loginid=<?php echo $admin_list[$i]['id']?>" target="_blank"><?php echo $admin_list[$i]['text'];?></a></li>
		<?php
			}
		}
		?>
		</ul>  
		</td>
<!-- body_text_eof //-->
  </tr>
  <tr>
    <td>
		<form action="salestrack_list.php" method="get">
		<fieldset><legend>销售跟踪搜索</legend>
		<table class="tbList">
			<tr>
				<td width="200">key:<?php echo tep_draw_input_field('selectkey','','style=""');?></td>
				<td width="150"><?php echo tep_draw_pull_down_menu('selectm', $salestrack->showKeyItem(),'','id="sent_login_id"',false);?></td>
				<td width="260">
				
				</td>
				<td><input type="submit" value="Query" /></td>
			</tr>
		</table>
		</fieldset>
		</form>
		</td>
  </tr>
  <tr>
    <td>
    <form action="salestrack_order_owner_search.php" method="get">
      <fieldset>
        <legend>订单归属查询---请输入完整的订单号</legend>
        订单号:<input type="text" name="orders_id" style="ime-mode:none;"/>		
         <input type="submit" value="Search"/>
		 <div style="padding:10px 5px;">
			<label style="color:#FF0000; font-size:12px;">
			<?php echo tep_draw_checkbox_field('refix','true');?>重新计算订单归属(不管现在有没有订单归属,都重新计算)
			</label>
		 </div>
		 <div style="font-size:14px; color:#999999; padding:5px 0;">&nbsp;如果该订单没有还没有归属,查询前会自动绑定然后再显示.</div>
      </fieldset>
    </form>
    </td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>