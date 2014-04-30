<?php
require('includes/application_top.php');
require('includes/classes/notebook.php');	//载入留言本的类文件
require('includes/classes/Assessment_Score.class.php');	//添加商务中心考核类
$Assessment_Score = new Assessment_Score();

$notebook = new notebook;
$admin_list = $notebook->admin_list();/*获取后台用户列表,用于显示列表及人员名字匹配*/
$action=$_GET['action'];
if($action==""){
	$action="search";
}

if($_POST['action']=="add"){
	$insert_id = $notebook->insert_or_update($_POST,'insert');
	if((int)$insert_id){
		tep_redirect('notebook.php');
	}
}

if($action=="reply" && $_POST['submit']!='' ){
	$notebook->reply($_POST);
	echo 'success';
	echo '<script language="javascript" type="text/javascript">window.opener.location.href=window.opener.location.href;window.close();</script>';
	exit();
}
if($action=="del" && $_POST['submit']!='' ){
	$notebook->delete($_POST['notebook_id']);
	echo 'success';
	echo '<script language="javascript" type="text/javascript">window.opener.location.href=window.opener.location.href;window.close();</script>';
	exit();
}
if($action=='next'){
	$json=array();
	if($notebook->changeNext($_GET['id'],$_GET['value'],$_GET['owner_id'])){
		$json['result'] = 'success';
		$json['score'] = $notebook->add_confirm_score($_GET['id'],$_GET['value'], $Assessment_Score); //+-分
	}
	if ($json) echo json_encode($json);
	exit();
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>----内部使用</title>
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
</style>
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
            <td class="pageHeading" width="30%"><?php echo db_to_html('留言本')?></td>
            <td align=""><a href="?action=add" style="font-weight:bolder; ">新增++</a></td>
          </tr>
        </table></td>
      </tr>
      <tr><td style="padding:5px;"></td></tr>
      <!-- 新增记事本模块开始//-->
      <?php if($action=="add"){?>
      <tr>
        <td>
        <fieldset><legend>新增留言</legend>
        <form name="form1" id="form1" action="?action=add" method="post" onSubmit="return checkForm1()">
		<input name="action" type="hidden" value="add">
		  <table>
			<!--
		    <tr><td>订单号:</td><td><?php echo tep_draw_input_field('orders_id','','id="orders_id_add" style="width:70px;ime-mode:disabled;"')?></td></tr>
			//-->
		    <tr>
			  <td>留言给:</td>
			  <td>
				<?php echo tep_draw_pull_down_menu('to_login_id',  $admin_list,'','id="to_login_id_add" '); ?>
			  </td>
			</tr>
			<tr>
				<td>订单号：</td>
				<td><input type="text" name="orders_id"/></td>
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
			  <td></td>
			  <td>
			  <input name="submit" type="submit" value="submit">
			  <input type="button" name="cancel" value="取消" onClick="history.go(-1);"/>
			  </td>
			</tr>
			
		  </table>
		</form>
		</fieldset>
       </td>
      </tr>
      <?php }?>
      <!-- 新增记事本模块结束//-->
      <!-- 查询模块开始//-->
      <?php if($action=="search"){?>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search condition </legend>
			<form name="form2" id="form2" action="?action=search" method="get">
			留言人:<?php echo tep_draw_pull_down_menu('sent_login_id', $admin_list,'','id="sent_login_id"',false); ?>
			留言对象:<?php echo tep_draw_pull_down_menu('to_login_id', $admin_list,'','id="to_login_id"',false); ?>
			解决人:<?php echo tep_draw_pull_down_menu('answer_login_id', $admin_list,'','id="answer_login_id"',false); ?>
			订单ID：<input type="text" name="orders_id" value="<?=$_GET['orders_id']?>" />
			状态 ：<?php echo tep_draw_pull_down_menu('status',array(array('id'=>'','text'=>'全部'),array('id'=>'1','text'=>'已解决'),array('id'=>'0','text'=>'未解决')),$_GET['status'])?>
			添加时间:<?php echo tep_draw_input_num_en_field('time_begin','','style="width:85px;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?> 
			-
			<?php echo tep_draw_input_num_en_field('time_end','','style="width:85px;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?> 
			留言跟进：
			<select name="next">
			<option value="">请选择</option>
			<option value="1" <?php if($next==1) echo 'selected';?>>已解决</option>
			<option value="2" <?php if($next==2) echo 'selected';?>>未解决</option>
			</select>
			<input type="submit" value="search">
			</form>
		  </fieldset>
		  <!--search form end-->
		</td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Search Results ----留言列表</legend>		
		
		  <table border="1" class="tbList" style="width:980px; table-layout:fixed; WIDTH: 400px; WORD-BREAK: break-all; WORD-WRAP: break-word">
			<tr>
			  <th width="60">编号</th><th width="60">订单ID</th><th width="60">紧急度</th><th width="80">留言时间</th><th width="60">留言人</th>
			  <th width="200">内容</th>
			  <th width="60">留言对象</th><th width="30">状态</th><th width="80">解决时间</th><th width="60">解决人</th><th width="200">解决答案</th>
			  <th width="280">留言跟进</th>
			  <th width="90"></th>
			</tr>
			<?php 
			$where='1';
			if($_GET['sent_login_id']!=''){$where .=' AND sent_login_id='.$_GET['sent_login_id'];}
			if($_GET['to_login_id']!=''){$where .=' AND to_login_id='.$_GET['to_login_id'];}
			if($_GET['answer_login_id']!=''){$where .=' AND answer_login_id='.$_GET['answer_login_id'];}
			if($_GET['time_begin']!=''){$where .=' AND add_date>="'.$_GET['time_begin'].' 00:00:00"';}
			if($_GET['time_end']!=''){$where .=' AND add_date<="'.$_GET['time_end'].' 23:59:59"';}
			if($_GET['status']!=''){$where .=' AND is_finished='.$_GET['status'];}
			(isset($_GET['next'])&&$_GET['next']!='')?$where.=' and next_status='.(int)$_GET['next']:'';
			(isset($_GET['orders_id'])&&$_GET['orders_id'])?$where.=' AND orders_id='.(int)$_GET['orders_id']:'';
			(isset($_GET['notebook_id'])&&$_GET['notebook_id'])?$where.=' AND notebook_id='.(int)$_GET['notebook_id']:'';
			$data = $notebook->lists('','*',$where,'',' ORDER BY is_replyed ASC,add_date DESC');
			$l=count($data);
			for($i=0;$i<$l-1;$i++){
			?>
			<tr <?php if(($i%2)==0){echo 'class="bc"';}?>>
			  <td><?php echo $data[$i]['notebook_id'];?></td>
			  <td><a href="edit_orders.php?oID=<?=$data[$i]['orders_id']?>" target="_blank"><?=$data[$i]['orders_id']?></a></td>
			  <td><?php 
			  switch($data[$i]['is_important']){
			  	case 2:echo '<span class="imp2">非常紧急</span>'; break;
			  	case 1:echo '<span class="imp1">紧急</span>'; break;
			  	default:echo'<span class="imp0">一般</span>';

			  };?></td>
			  <td><?php echo $data[$i]['add_date'];?></td>
			  <td><?php echo $notebook->get_admin_name($data[$i]['sent_login_id'],$admin_list);?></td>
			  <td><?php echo nl2br(tep_db_output($data[$i]['content']));?></td>
			  <td><?php echo $notebook->get_admin_name($data[$i]['to_login_id'],$admin_list);?></td>
			  <td><?php 
			  if($data[$i]['is_finished']=='1'){
			  	echo '<img src="images/icons/yes.gif"/>';
			  }else{
			  	if($data[$i]['is_replyed']==1){
			  		echo '<img src="images/icons/no.gif"/>';
			  	}elseif($data[$i]['is_replyed']==0){
			  		echo '';
			  	}
			  }?></td>
			  <td><?php if($data[$i]['is_replyed']=='1'){echo $data[$i]['answer_date'];}?></td>
			  <td><?php if($data[$i]['is_replyed']=='1'){echo $notebook->get_admin_name($data[$i]['answer_login_id'],$admin_list);}?></td>
			  
			  <td><?php 
			  if($data[$i]['is_replyed']=='1'){
			  	echo nl2br(tep_db_output($data[$i]['answer_content']));
			  }?></td>	
			  <td><span id="next_txt_<?=$data[$i]['notebook_id']?>"><?php if($data[$i]['next_status']==1)echo '<font color="red">已解决</font>';elseif($data[$i]['next_status']==2){ echo '未解决';}elseif($data[$i]['next_status']==3) echo '<font color="#00CCCC">待处理</font>';?></span>
			  <?php 
			  if(
($_SESSION['login_id']=='222'||$_SESSION['login_groups_id']==1)||($data[$i]['owner_click']==0&&$_SESSION['login_id']==$data[$i]['sent_login_id'])){
			  	//if($data[$i]['next_status']!=1||$_SESSION['login_groups_id']==1){
			  ?>
			  <select id="next_select_<?=$data[$i]['notebook_id']?>">
			  <option value="">请选择</option>
			  <?php if($data[$i]['answer_content']) {?><option value="1">已解决</option><?php }?>
			  <option value="2">未解决</option>
			  </select>
			  <input name="button" type="button" onClick="changeNextStatus(<?=$data[$i]['notebook_id']?>,this,<?=$data[$i]['sent_login_id']?>)" value="update" /></td>
			  <?php }//} ?>	  
			  <td>
			  <?php if($data[$i]['is_finished']!='1'){?>
			  [<a href="?action=reply&notebook_id=<?php echo $data[$i]['notebook_id'];?>" target="_blank">回复</a>]
			  <?php }?>
			  <?php // if($login_id==$data[$i]['sent_login_id']){?>
			  <!--[<a href="?action=del&notebook_id=<?php //echo $data[$i]['notebook_id'];?>" target="_blank">删除</a>]//-->
			  <?php// }?>			  </td>
			</tr>
			<?php }?>	
			<tr><td colspan="7"><?php echo $data['splitPages']['count'];?></td>
			<td colspan="5"><?php echo $data['splitPages']['links'];?></td></tr>		
		  </table>
			
	
		</fieldset>		
	    </td>
      </tr>
      <?php }?>
      <!-- 查询模块结束//-->
      <!-- 回复模块开始//-->
      <?php if($action=='reply'){?>
      <?php 
      $notebook_id=$_GET['notebook_id'];
      $nb=$notebook->getnote($notebook_id);
      if(count($nb[0])<1){echo 'no data found';}
      else {
      ?>
      <tr>
        <td>
        <form name="form4" id="form4" action="?action=reply" method="post">
        <input type="hidden" name="notebook_id" value="<?php echo $notebook_id;?>"/>                
        <table class="tbList">
          <tr>
            <th width="100" align="right">紧急程度:</th>
            <td><?php switch($nb[0]['is_important']){
            	case 2:echo "非常紧急"; break;
            	case 1:echo "紧急";break;
            	case 0:echo "一般";break;
            	default:echo "一般"; break;
            }?></td>
          </tr>
          <tr><th align="right">留言时间:</th><td><?php echo $nb[0]['add_date'];?></td></tr>
          <tr><th align="right">留言人:</th><td><?php echo $notebook->get_admin_name($nb[0]['sent_login_id'],$admin_list);?></td></tr>          
          <tr><th align="right">内容:</th><td><?php echo nl2br(tep_db_output($nb[0]['content']));?></td></tr>
          <tr><th align="right">留言对象:</th><td><?php echo $notebook->get_admin_name($nb[0]['to_login_id'],$admin_list);?></td></tr>
          <tr><td align="right">解决时间:</td><td><?php if($nb[0]['is_replyed']==1){echo $nb[0]['answer_date'];}?></td></tr>
          <tr><td align="right">解决人:</td>
            <td>
            <?php 
            if($nb[0]['is_replied']!=0){echo $notebook->get_admin_name($nb[0]['answer_login_id'],$admin_list);}
            else{
            	echo $notebook->get_admin_name($login_id,$admin_list);;
            }?>
            </td>
          </tr>
          <tr>
            <td align="right">解决答案:</td>
            <td><?php echo $nb[0]['answer_content']?>
			<?php if($nb[0]['is_finished']!=1){?>
			<br/>追加:
            <textarea name="answer_content" rows="3" cols="50"></textarea>
			<?php }?>
            </td>
          </tr>
          <tr>
            <td align="right">是否已解决:</td>
            <td>
              <label style="color:#0000FF"><input type="radio" name="is_finished" value="1" id="is_finished_1" <?php if($nb[0]['is_finished']==1){?>checked="checked"<?php }?>/>是,已经解决</label>
              <label style="color:#FF0000"><input type="radio" name="is_finished" value="0" id="is_finished_0" <?php if($nb[0]['is_finished']==0){?>checked="checked"<?php }?>/>否,还未解决(或未完全解决)</label>
            </td>
          </tr>
		  <?php if($nb[0]['is_finished']!=1){?>
          <tr><td></td><td><input type="submit" name="submit" value="回复"/> <input type="button" name="cancel" onClick="window.close();" value="取消"/></td></tr>
		  <?php }?>
          </table>
        </form>
        </td>
      </tr>
	  <tr>
	  <td>
	  回复历史记录：
	  <table class="tbList">
	  <tr>
		  <th>回复时间</th>
		  <th>回复人</th>
		  <th>回复内容</th>
	  </tr>
	  <?php foreach($notebook->getHistoryReplay($notebook_id) as $value){?>
	  <tr>
	  	<td><?=$value['replay_time']?></td>
		<td><?=$value['admin_name']?></td>
		<td><?=$value['replay_content']?></td>
		<?php }?>
	  </tr>
	  </table>
	  </td>
	  </tr>
      <?php 
      }
      }?>
      <!-- 回复模块结束//-->
      <!-- 删除模块开始//-->
     <?php if($action=='del'){?>
      <?php 
      $notebook_id=$_GET['notebook_id'];
      $nb=$notebook->getnote($notebook_id);
      if(count($nb[0])<1){echo 'no data found';}
      else {
      ?>
      <tr>
        <td>
        <form name="form5" id="form5" action="?action=del" method="post">
        <input type="hidden" name="notebook_id" value="<?php echo $notebook_id;?>"/>                
        <table class="tbList">
          <tr>
            <th width="100" align="right">紧急程度:</th>
            <td><?php switch($nb[0]['is_important']){
            	case 2:echo "非常紧急"; break;
            	case 1:echo "紧急";break;
            	case 0:echo "一般";break;
            	default:echo "一般"; break;
            }?></td>
          </tr>
          <tr><th align="right">留言时间:</th><td><?php echo $nb[0]['add_date'];?></td></tr>
          <tr><th align="right">订单号:</th><td><?php echo $nb[0]['orders_id'];?></td></tr>
          <tr><th align="right">留言人:</th><td><?php echo $notebook->get_admin_name($nb[0]['sent_login_id'],$admin_list);?></td></tr>          
          <tr><th align="right">内容:</th><td><?php echo nl2br(tep_db_output($nb[0]['content']));?></td></tr>
          <tr><th align="right">留言对象:</th><td><?php echo $notebook->get_admin_name($nb[0]['to_login_id'],$admin_list);?></td></tr>
          <tr><td align="right">解决时间:</td><td><?php if($nb[0]['is_replyed']==1){echo $nb[0]['answer_date'];}?></td></tr>
          <tr><td align="right">解决人:</td>
            <td>
            <?php 
            if($nb[0]['is_replied']!=0){echo $notebook->get_admin_name($nb[0]['answer_login_id'],$admin_list);}
            else{
            	echo $notebook->get_admin_name($login_id,$admin_list);;
            }?>
            </td>
          </tr>
          <tr>
            <td align="right">解决答案:</td>
            <td>
            <pre><?php echo nl2br(tep_db_output($nb[0]['answer_content']));?></pre>
            </td>
          </tr>
          <tr>
            <td align="right">是否已解决:</td>
            <td>
              <?php if($nb[0]['is_finished']==1){?><label style="color:#0000FF">是,已经解决</label>
              <?php }
              else{?>
              <label style="color:#FF0000">否,还未解决(或未完全解决)</label>
              <?php }?>
            </td>
          </tr>
          <tr><td></td><td><input type="submit" name="submit" value="确认删除"/> <input type="button" name="cancel" onClick="window.close();" value="取消"/></td></tr>
          </table>
        </form>
        </td>
      </tr>
      <?php 
      }
      }?>
      <!-- 删除模块结束//-->
    </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<script language="javascript" type="text/javascript">
function changeNextStatus(id,doc_this,owner_id){
	doc_this.disabled=true;
	var myid=document.getElementById('next_select_'+id);
	if(myid.value!='')
	$.ajax({
		url: '/admin/notebook.php?action=next&&id='+id+'&&owner_id='+owner_id+'&&value='+myid.value,
		type: 'GET',
		timeout: 1000,
		error: function(){
		doc_this.disabled=false;
			//alert('wait !!!');
		},
		dataType:'json',
		success: function(json){
		doc_this.disabled=false;
			if(json['result']=='success'){
				if(myid.value==1){
					document.getElementById('next_txt_'+id).innerHTML='<font color="red">'+myid.options[myid.selectedIndex].text+'</font>';
				}else{
					document.getElementById('next_txt_'+id).innerHTML=myid.options[myid.selectedIndex].text;
				}
				if(typeof(json['score'])!='undefined' && json['score']!=''){
					alert('成功添加'+ json['score'] +'积分！');
				}
			}else{
				alert('出错了，找管理员解决！');
			}
		}
	});
}
</script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>