<?php
require('includes/application_top.php');
require('includes/classes/salestrack.php');	//载入销售跟踪的类文件
$salestrack = new salestrack;

if($_GET['loginid']==''){
	if(!($salestrack->viewall)){
		tep_redirect('salestrack_list.php?loginid='.$salestrack->login_id);
	}
}

$admin_list = $salestrack->admin_list();/*获取后台用户列表,用于显示列表及人员名字匹配*/
$action=$_GET['action'];

$loginid=(int)($_GET['loginid']);
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
.tbList th{ background-color:#006699; color:#FFFFFF; font-weight:bolder; font-size:80%; border:1px solid #CCCCCC; padding:3px;}
.tbList td{ border:1px solid #CCCCCC; padding:3px; font-size:80%;}
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
    <td><a href="salestrack_add.php" target="_blank">增加销售跟踪</a></td>
  </tr>
  <tr>
    <td>
		<form action="salestrack_list.php" method="get">
		<?php echo tep_draw_hidden_field('loginid',''.$loginid.'');?>
		<fieldset><legend>销售跟踪搜索</legend>
		<table class="tbList">
			<tr>
				<td width="200">key:<?php echo tep_draw_input_field('selectkey','','style=""')?></td>
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
       <table class="tbList" width="98%" style="table-layout:auto; word-break:break-all;">
        <tr> 
          <th width="90">日期</th>
          <th width="80">销售</th>
          <th width="80">客人姓名</th>
		  <th width="100">团号</th>
		  <th width="80">订单号</th>
          <th width="80">客人电话</th>
          <th width="80">手机</th>
          <th width="120">E-mail</th>
          <th width="140">QQ/MSN/SKYPE</th>
          <th width="80">计划参团</th>
		  <th width="80">下次联系时间</th>
          <th>客户咨询内容</th>
        </tr>
        <?php
        /**
         * 字符过滤
         */
        function escape($str) {
        	return htmlentities(trim($str), ENT_QUOTES);
        }
        
        /**
         * 字符过滤 与array_walk_recursive结合使用
         */
        function replaceArr(&$item) {
        	if (!get_magic_quotes_gpc()) {
        		$item = escape(stripslashes($item));
        	} else {
        		$item = escape($item);
        	}
        }
        /*
         * 销售跟踪管理者,可以查看/搜索所有人的销售跟踪,如果有传参数login_id,则只看某人的
         * 其他人员只能看自己的.
         * */
        if($salestrack->viewall)
        {
        	if($loginid>0){
        	   $where=' login_id='.$loginid;
        	}else{
        		$where=' 1 ';
        	}
        }
        else {
        	$where=' login_id='.$loginid;
        }
        replaceArr($_GET['selectkey']);
        
        if(tep_not_null($_GET['selectkey'])){
        	$where .=' AND ' . $_GET['selectm'] . ' LIKE \'%' . $_GET['selectkey'] . '%\'';
        }

        /*
        if($_GET['time_begin']!=''){$where .=' AND add_date>="'.$_GET['time_begin'].' 00:00:00"';}
        if($_GET['time_end']!=''){$where .=' AND add_date<="'.$_GET['time_end'].' 23:59:59"';}
        */
        $data = $salestrack->getlists('','*',$where,'',' ORDER BY add_date DESC');
        $l=count($data);
        //print_r($data);
        if($l<2){
        ?>
        <p style="color:#FF0000; font-size:20px; font-weight:bolder;">暂无记录..........</p>
        <?php        	
        }
        for($i=0;$i<$l-1;$i++){
        ?>
        <tr <?php if($i%2==1){echo 'class="bc"';}?> onMouseOver="c=this.style.backgroundColor;this.style.backgroundColor='#CCFF66'" onMouseOut="this.style.backgroundColor=c">
          <td><a href="salestrack_edit.php?salestrack_id=<?php echo $data[$i]['salestrack_id']?>" target="_blank"><?php echo $data[$i]['add_date'];?></a></td>
          <td><?php echo $salestrack->get_admin_name($data[$i]['login_id'],$admin_list);?></td>
          <td><?php echo $data[$i]['customer_name'];?></td>
          <td><?php echo $data[$i]['code'];?></td>
          <td><?php echo $data[$i]['orders_id'];?></td>
          <td><?php echo $data[$i]['customer_tel'];?></td>
          <td><?php echo $data[$i]['customer_mobile'];?></td>
          <td><?php echo $data[$i]['customer_email'];?></td>
          <td>
          <?php
          if(tep_not_null($data[$i]['customer_qq'])){ echo '<span style="color:#999999;">QQ</span>: '.$data[$i]['customer_qq'].'<br />'; }
          if(tep_not_null($data[$i]['customer_msn'])){ echo '<span style="color:#AAAAAA;">MSN</span>: '.$data[$i]['customer_msn'].'<br />'; }
          if(tep_not_null($data[$i]['customer_skype'])){echo '<span style="color:#888888;">SKYPE</span>: '.$data[$i]['customer_skype']; }
          ?>
          </td>
          <td><?php echo $data[$i]['customer_plan_tdate'];?></td>
          <td><?php echo $data[$i]['next_condate'];?></td>
          <td><?php echo nl2br(tep_db_output($data[$i]['customer_info']));?></td>
        </tr>
        <?php }?>
        <tr>
          <td colspan="7"><?php echo $data['splitPages']['count'];?></td>
		  <td colspan="5"><?php echo $data['splitPages']['links'];?></td>
		</tr>
      </table>
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