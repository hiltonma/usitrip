<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
include 'includes/application_top.php';
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('expert_group');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

//批量添加
if ($_GET['action'] == 'copy_products') {
	if (!$can_expert_group_edit) {
		echo '<script type="text/javascript">alert("您没权进行此操作!");location.href="?";</script>';
		echo '{"state":1,"error":"您没权进行此操作!"}';
	} else {
		$pid = isset($_POST['pid']) ? $_POST['pid'] : '';
		$expert_ids  = isset($_POST['val']) ? $_POST['val'] : '';
		$oldpid = isset($_POST['oldpid']) ? $_POST['oldpid'] : '';
		$diff = array_diff(explode(',',$oldpid),explode(',',$pic));
		
		$rtn = 'addExpertGroup:' . $expert_ids . ',';
		
		if (tep_not_null($pid) && tep_not_null($expert_ids)) {
			//$products_ids = explode(',',$pid);
			$expert_ids = explode(',',$expert_ids);
			$sql = "select products_id,expert_ids from products where products_id in (" . $pid . ")";
			//echo $sql;
			$result = tep_db_query($sql);
			
			while($row = tep_db_fetch_array($result)) {
				if (tep_not_null($row['expert_ids'])) {
					$epids = explode(',',$row['expert_ids']);
					$epids_temp = array_merge($epids,$expert_ids);
				} else {
					$epids_temp = $expert_ids;
				}
				$epids_temp = array_unique($epids_temp);
				$epids_temp = join(',',$epids_temp);
				tep_db_fast_update('products',"products_id='" . $row['products_id'] . "'",array('expert_ids'=>$epids_temp));
				$rtn .= 'products_id:' . $row['products_id'] . ',expert_ids:' . $epids_temp . ',';
			}
			if (count($diff)>0) {
				$sql = "select products_id,expert_ids from products where products_id in (" . implode(',',$diff) . ")";
				//echo $sql;
				$result = tep_db_query($sql);
				
				while($row = tep_db_fetch_array($result)) {
					if (tep_not_null($row['expert_ids'])) {
						$epids = explode(',',$row['expert_ids']);
						$epids_temp = array_diff($epids,$expert_ids);
					} 
					$epids_temp = join(',',$epids_temp);
					tep_db_fast_update('products',"products_id='" . $row['products_id'] . "'",array('expert_ids'=>$epids_temp));
					$rtn .= 'products_id:' . $row['products_id'] . ',remove_expert_ids:' . $epids_temp . ',';
					
				}
			}
			echo '{"state":0,"error":"' . $rtn . '"}';
		} else {
			echo '{"state":1,"error":"数据不全"}';
		}
	}
	exit;
}
if ($_GET['action'] == 'copy_agency_product') {
	$agency_id = isset($_POST['agency_id']) ? intval($_POST['agency_id']) : 0;
	$expert_group_id = isset($_POST['val']) ? $_POST['val'] : '';
	if (tep_not_null($agency_id) && tep_not_null($expert_group_id)) {
		$expert_group_id = explode(',',$expert_group_id);
		$sql = "select products_id,agency_id,expert_ids from products where agency_id='" . $agency_id . "'";
		$result = tep_db_query($sql);
		while($row = tep_db_fetch_array($result)){
			if (tep_not_null($row['expert_ids'])){
				$expert_ids = explode(',',$row['expert_ids']);
				$expert_ids = array_merge($expert_ids,$expert_group_id);
			} else {
				$expert_ids = $expert_group_id;
			}
			$expert_ids = array_unique($expert_ids);
			$expert_ids = join(',',$expert_ids);
			tep_db_fast_update('products',"products_id='" . $row['products_id'] . "'",array('expert_ids'=>$expert_ids));
			$rtn .= 'products_id:' . $row['products_id'] . ',expert_ids:' . $expert_ids . ',';
		}
		echo '{"state":0,"error":"' . $rtn . '"}';
	} else {
		echo '{"state":1,"error":"数据不全"}';
	}
	exit;
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
body,td{font-size:12px;}
.tbList { border:1px solid #CCCCCC; border-collapse:collapse;}
.tbList th{ background-color:#006699; color:#FFFFFF; font-weight:bolder; font-size:90%; border:1px solid #CCCCCC; padding:3px;}
.tbList td{ border:1px solid #CCCCCC; padding:3px; font-size:12px;}
.tbList td span.imp2{color:#FF0000; font-weight:bolder;}
.tbList td span.imp1{color:#FF0000; font-weight:normal;}
.tbList td span.imp0{color:#000000; font-weight:normal;}
.tbList tr.bc{ background-color:#EEEEEE}
.tbList .finish{color:#0000FF;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); 
 $agency_sql = "select agency_id,agency_name from tour_travel_agency order by agency_id desc";
			$agency_result = tep_db_query($agency_sql);
			$data = array();
			while ($agency_rs = tep_db_fetch_array($agency_result)) {
				$data[] = array('id'=>$agency_rs['agency_id'],'text'=>$agency_rs['agency_name']);
			}
?>
<!-- header_eof //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('expert_group');
$list = $listrs->showRemark();
?>
<fieldset>
<legend>搜索区域</legend>
<form action="" method="get">
	地接商编号：
	<?php echo tep_draw_pull_down_menu('agency_id', $data, tep_db_input($_GET['agency_id']), ' id="agency_id"');//$rs['agency_id']?>
	姓名：
	<input type="text" value="<?php echo tep_db_input($_GET['expert_name'])?>" name="expert_name" id="expert_name" />
	<input type="submit" value="搜索" />
	<input type="hidden" name="action" value="search" />
</form>
</fieldset>
<br/>
<?php
$action = $_GET['action'];
switch($action) {
	
	case 'edit':
		
		$expert_id = $_GET['editid'];
		$sql = "select * from expert_group where expert_id='" . intval($expert_id) . "'";
		$result = tep_db_query($sql);
		$rs = tep_db_fetch_array($result);
		if (tep_not_null($rs)) {
		?>
<fieldset>
<legend>编辑专家</legend>

<?php
if (!$can_expert_group_edit) { //检测编辑权限
	echo '<div style="background:red;color:#fff;">注意！您没权进行编辑操作!</div>';
} else {
?>
<form action="?action=saveedit" method="post" enctype="multipart/form-data">
<?php }?>
<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr class="tbList">
			<th>专家ID</th>
			<td><?php echo $rs['expert_id']?>
				<input type="hidden" name="expert_id" value="<?php echo $rs['expert_id']?>" /></td>
		</tr>
		<tr class="tbList">
			<th width="10%">姓名：</th>
			<td><input type="text" value="<?php echo $rs['expert_name']?>" name="expert_name" id="expert_name" /></td>
		</tr>
		<tr class="tbList">
			<th>图像:</th>
			<td><img src="<?php echo $rs['expert_img']?>" /><input type="file" name="file" id="file"/><input type="hidden" name="old_img" value="<?php echo $rs['expert_img']?>"/>图片尺寸（宽202px，高170px）</td>
		</tr>
		<tr class="tbList">
			<th>头衔/职称：</th>
			<td><input type="text" style="width:800px;" value="<?php echo $rs['expert_job_title']?>" name="expert_job_title" id="expert_job_title" /></td>
		</tr>
		<tr class="tbList">
			<th>旅游口号：</th>
			<td><input type="text" style="width:800px;"  value="<?php echo $rs['expert_title']?>" name="expert_title" id="expert_title" /></td>
		</tr>
		<tr class="tbList">
			<th>地接商编号：</th>
			
			<td><?php echo tep_draw_pull_down_menu('agency_id', $data, $rs['agency_id'], '');//$rs['agency_id']?></td>
		</tr>
		<tr class="tbList">
			<th>其它描述：</th>
			<td><textarea  name="expert_detail" id="expert_detail" style="width:800px;height:300px;"><?php echo $rs['expert_detail']?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="center"> <input type="submit" value="保存" /> <input type="button" value="返回列表" onClick="history.go(-1);"  /></td>
		</tr>
	</tbody>
</table>
<?php
if (!$can_expert_group_edit) { //检测编辑权限
	echo '<div style="background:red;color:#fff;">注意！您没权进行编辑操作!</div>';
} else {
?>
</form>
<?php }?>

</fieldset>
<?php
		} else {
		?>
您要编辑的专家已经被删除！<input type="button" onClick="history.go(-1);" value="返回列表" />
<?php
		}
		break;
	case "saveedit":
		if (!$can_expert_group_edit) { //检测编辑权限
			echo '<script type="text/javascript">alert("对不起！您没权进行编辑!");location.href="?";</script>';
			exit;
		}
		$expert_id = isset($_POST['expert_id']) ? intval($_POST['expert_id']) : 0;
		$expert_name = isset($_POST['expert_name']) ? $_POST['expert_name'] : '';
		$expert_job_title = isset($_POST['expert_job_title']) ? $_POST['expert_job_title'] : '';
		$expert_title = isset($_POST['expert_title']) ? $_POST['expert_title'] : '';
		$agency_id = isset($_POST['agency_id']) ? intval($_POST['agency_id']) : 0;
		$expert_detail = isset($_POST['expert_detail']) ? $_POST['expert_detail'] : '';
		$data = array();
		$data['expert_name'] = $expert_name;
		$data['expert_job_title'] = $expert_job_title;
		$data['expert_title'] = $expert_title;
		$data['agency_id'] = $agency_id;
		$data['expert_detail'] = $expert_detail;
		if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
			if (!file_exists(DIR_FS_CATALOG_IMAGES . 'expert_group')) {
				mkdir(DIR_FS_CATALOG_IMAGES . 'expert_group');
			}
			$ext = $_FILES['file']['name'];
			$ext = strtolower(substr($ext,strrpos($ext,'.') + 1));
			$name = microtime(true) . '.' . $ext;
			move_uploaded_file($_FILES['file']['tmp_name'], DIR_FS_CATALOG_IMAGES . 'expert_group' . DIRECTORY_SEPARATOR . $name);
			$data['expert_img'] = DIR_WS_CATALOG_IMAGES . 'expert_group/' . $name;
			if (isset($_POST['old_img']) && tep_not_null($_POST['old_img'])) {
				$delimg = $_POST['old_img'];
				$imgname = strtolower(substr($delimg,strrpos($delimg,'/') + 1));
				$delimg = DIR_FS_CATALOG_IMAGES . 'expert_group' . DIRECTORY_SEPARATOR . $imgname;
				if (file_exists($delimg)) {
					unlink(DIR_FS_CATALOG_IMAGES . 'expert_group' . DIRECTORY_SEPARATOR . $imgname);
				}
			}
		}
		tep_db_fast_update("expert_group", "expert_id='" . $expert_id . "'", $data);
		echo '<script type="text/javascript">location.href="?";</script>';
		exit;
		break;
	case 'add':
	?>
<fieldset>
<legend>添加专家</legend>
<?php
if (!$can_expert_group_add) { //检测添加权限
	echo '<div style="background:red;color:#fff;">注意！您没权进行添加操作!</div>';
} else {
?>
<form action="?action=addsave" method="post" enctype="multipart/form-data">
<?php } ?>
<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr class="tbList">
			<th width="10%">姓名：</th>
			<td><input type="text" value="" name="expert_name" id="expert_name" /></td>
		</tr>
		<tr class="tbList">
			<th>图像:</th>
			<td><input type="file" name="file" id="file"/>图片尺寸（宽202px，高170px）</td>
		</tr>
		<tr class="tbList">
			<th>头衔/职称：</th>
			<td><input type="text" style="width:800px;" value="" name="expert_job_title" id="expert_job_title" /></td>
		</tr>
		<tr class="tbList">
			<th>旅游口号：</th>
			<td><input type="text" style="width:800px;"  value="" name="expert_title" id="expert_title" /></td>
		</tr>
		<tr class="tbList">
			<th>地接商编号：</th>
			<td><?php echo tep_draw_pull_down_menu('agency_id', $data, '', '');//$rs['agency_id']?></td>
		</tr>
		<tr class="tbList">
			<th>其它描述：</th>
			<td><textarea  name="expert_detail" id="expert_detail" style="width:800px;height:300px;"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="center"> 
			<?php if ($can_expert_group_add) { // 如果可以添加?>
			<input type="submit" value="保存" />
			<?php }?> <input type="button" value="返回列表" onClick="history.go(-1);"  /></td>
		</tr>
	</tbody>
</table>
<?php
if (!$can_expert_group_add) { //检测添加权限
	echo '<div style="background:red;color:#fff;">注意！您没权进行添加操作!</div>';
} else {
?>
</form>
<?php }?>
</fieldset>
	<?php
		break;
	case 'addsave':
		if ($can_expert_group_add) { // 如果可以添加
			$expert_name = isset($_POST['expert_name']) ? $_POST['expert_name'] : '';
			$expert_job_title = isset($_POST['expert_job_title']) ? $_POST['expert_job_title'] : '';
			$expert_title = isset($_POST['expert_title']) ? $_POST['expert_title'] : '';
			$agency_id = isset($_POST['agency_id']) ? intval($_POST['agency_id']) : 0;
			$expert_detail = isset($_POST['expert_detail']) ? $_POST['expert_detail'] : '';
			$data = array();
			$data['expert_name'] = $expert_name;
			$data['expert_job_title'] = $expert_job_title;
			$data['expert_title'] = $expert_title;
			$data['agency_id'] = $agency_id;
			$data['expert_detail'] = $expert_detail;
			if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
				if (!file_exists(DIR_FS_CATALOG_IMAGES . 'expert_group')) {
					mkdir(DIR_FS_CATALOG_IMAGES . 'expert_group');
				}
				$ext = $_FILES['file']['name'];
				$ext = strtolower(substr($ext,strrpos($ext,'.') + 1));
				$name = microtime(true) . '.' . $ext;
				move_uploaded_file($_FILES['file']['tmp_name'], DIR_FS_CATALOG_IMAGES . 'expert_group' . DIRECTORY_SEPARATOR . $name);
				$data['expert_img'] = DIR_WS_CATALOG_IMAGES . 'expert_group/' . $name;
			}
			tep_db_fast_insert("expert_group", $data);
			echo '<script type="text/javascript">location.href="?";</script>';
		} else {
			echo '<script type="text/javascript">alert("您没有权限添加专家团!");location.href="?";</script>';
		}
		exit;
		break;
	case 'search':
		?>
<fieldset>
<legend>搜索列表</legend>
<input type="button" value="添加新专家" onClick="location.href='?action=add'"/>&nbsp;<input type="button" value="返回列表" onClick="location.href='?'"/>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tbody>
		<tr class="tbList">
			<th>ID</th>
			<th><input type="checkbox" onClick="if(this.checked == true){jQuery('input[name=\'chkbox\']').attr('checked',true);}else{jQuery('input[name=\'chkbox\']').attr('checked',false);}" /></th>
			<th>姓名</th>
			<th>头衔/职称</th>
			<th>图像</th>
			<th>地接商编号</th>
			<th>可用状态</th>
			<th>使用的产品</th>
			<th>相关操作</th>
		</tr>
		<?php
		$pageMaxRowsNum = 10; //每页显示10条记录
		$sql = 'select eg.*,tta.agency_name from expert_group eg,tour_travel_agency tta where tta.agency_id=eg.agency_id ';
		$sql .= isset($_GET['agency_id']) && tep_not_null($_GET['agency_id']) ? " and tta.agency_name like '%" . tep_db_input($_GET['agency_id']) . "%' " : "";
		$sql .= isset($_GET['expert_name']) && tep_not_null($_GET['expert_name']) ? " and eg.expert_name like '%" . tep_db_input($_GET['expert_name']) . "%' " : "";
		$keywords_query_numrows = 0;
		$_split = new splitPageResults($_GET['page'], $pageMaxRowsNum, $sql, $keywords_query_numrows);
		$rs = tep_db_query($sql);
		while($row = tep_db_fetch_array($rs)) {
		?>
		<tr class="tbList">
			<td align="center"><?php echo $row['expert_id']?></td>
			<td align="center"><input type="checkbox" name="chkbox" value="<?php echo $row['expert_id']?>" /></td>
			<td><?php echo $row['expert_name']?></td>
			<td><?php echo $row['expert_job_title']?></td>
			<td><img src="<?php echo $row['expert_img']?>" /></td>
			<td><?php echo $row['agency_name']?></td>
			<td><?php if ($row['expert_disabled'] == 1) {
					echo $can_expert_group_open ? '<a href="?action=open_expert&status=0&expert_id=' .$row['expert_id']  . '" style="display:inline-block;padding:5px 10px 5px 5px;"><img width="10" height="10" border="0" title="点击启用此专家" alt="点击启用此专家" src="images/icon_status_green_light.gif"></a>' : '<img style="margin:0px 10px 0px 5px;" width="10" height="10" border="0" alt="Inactive" src="images/icon_status_green_light.gif" title="你无权进行此操作！"/>';
					echo '<img width="10" height="10" border="0" title="此专家当前已禁用" alt="Inactive" src="images/icon_status_red.gif"/>';
				} else {
					echo '<img width="10" height="10" border="0" title="此专家已经启用" alt="此专家已经启用" src="images/icon_status_green.gif"/>';
					
					echo $can_expert_group_open ? '<a href="?action=open_expert&status=1&expert_id=' .$row['expert_id']  . '" style="display:inline-block;padding:5px 5px 5px 10px;"><img width="10" height="10" border="0" title="点击禁用此专家" alt="点击禁用此专家" src="images/icon_status_red_light.gif"></a>' : '<img style="margin:0px 10px 0px 5px;" width="10" height="10" border="0" alt="Set Active" src="images/icon_status_red_light.gif" title="你无权进行此操作！">';
					
					
				} ?></td>
			<td><?php 
			$pro_sql = "select products_id from products where find_in_set('" . $row['expert_id'] . "',expert_ids)";
			$pro_result = tep_db_query($pro_sql);
			$pro_ids = '';
			while($pro_rs = tep_db_fetch_array($pro_result)){
				$pro_ids .= $pro_rs['products_id'] . ',';
			}
			$pro_ids = trim($pro_ids,',');
			?><input type="text" value="<?php echo $pro_ids?>" name="products" id="products_<?php echo $row['expert_id']?>" onKeyUp="this.value = this.value.replace(/[^0-9\,]/g,'')" /><input type="hidden" value="<?php echo $pro_ids?>" id="oldProducts_<?php echo $row['expert_id']?>" /><input type="button" onClick="updateProduct(document.getElementById('products_<?php echo $row['expert_id']?>'),'<?php echo $row['expert_id']?>','oldProducts_<?php echo $row['expert_id']?>')" value="更新到产品" title="点此按钮把当前专家设置到您填写的产品中" /></td>
			<td><a href="?action=edit&editid=<?php echo $row['expert_id']?>">编辑</a>&nbsp;<?php if ($can_expert_group_del) { ?><a href="?action=del&editid=<?php echo $row['expert_id']?>" onClick="if(confirm('您确认要删除此专家吗？该操作不可逆！')){} else {return false}">删除</a><?php }?></td>
		</tr>
		<?php 
		}?>
		<tr>
			<td></td>
			<td align="center"><input type="checkbox" onClick="if(this.checked == true){jQuery('input[name=\'chkbox\']').attr('checked',true);}else{jQuery('input[name=\'chkbox\']').attr('checked',false);}" />全选</td>
			<td colspan="7"><input type="button" value="反选" onClick="unselect()"/> 产品(多个请用英文逗号隔开)：<input type="text" value="" onKeyUp="this.value = this.value.replace(/[^0-9\,]/g,'')" id="more_products" /> <input type="button" value="更新选中的到产品" onClick="updateProduct(document.getElementById('more_products'),'chkbox')" />&nbsp;&nbsp;地接商：<?php 
			echo tep_draw_pull_down_menu('agency_id', $data, '', ' id="agency_id"');//$rs['agency_id']?>&nbsp;&nbsp;<input type="button" value="更新选中的到指定地接" onClick="updateProductByAgency('agency_id')" /></td>
		</tr>
		<tr>
			<td colspan="9" align="center"><?php echo $_split->display_links($_split->query_num_rows,$pageMaxRowsNum,6, $_GET['page'],tep_get_all_get_params_fix('','page'))?></td>
		</tr>
	</tbody>
</table>
</fieldset>
<?php
		break;
	case 'del':
		if (!$can_expert_group_del) {
			echo '<script type="text/javascript">alert("您无权删除专家！");location.href="?";</script>';
		} else {
			$delid = isset($_GET['editid']) ? intval($_GET['editid']) : 0;
			if (!$delid) {
				echo '<span style="color:#f00">需要删除的对象丢失！<a href="javascript:history.go(-1)">返回</a>重试!</span>';
			} else {
				$result = tep_db_query("select expert_img from expert_group where expert_id='" . $delid . "'");
				$rs = tep_db_fetch_array($result);
				$delimg = $rs['expert_img'];
				$imgname = strtolower(substr($delimg,strrpos($delimg,'/') + 1));
				$delimg = DIR_FS_CATALOG_IMAGES . 'expert_group' . DIRECTORY_SEPARATOR . $imgname;
				if (file_exists($delimg)) {
					unlink(DIR_FS_CATALOG_IMAGES . 'expert_group' . DIRECTORY_SEPARATOR . $imgname);
				}
				tep_db_query("delete from expert_group where expert_id='" . $delid . "'");
				echo '<script>alert("删除成功!");location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
			}
		}
		break;
	case 'open_expert':
		if (!$can_expert_group_open) {
			echo '<script type="text/javascript">alert("你无权启用/禁止专家！");location.href="?";</script>';
		} else {
			$status = isset($_GET['status']) ? intval($_GET['status']) : 1;
			$expert_id = isset($_GET['expert_id']) ? intval($_GET['expert_id']) : 0;
			if ($expert_id == 0) {
				echo '<script type="text/javascript">alert("操作ID丢失！");history.go(-1);</script>';
			} else {
				$data = array('expert_disabled'=>$status);
				tep_db_fast_update('expert_group',"expert_id='" . $expert_id . "'",$data);
				echo '<script type="text/javascript">location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
			}
		}
	break;
	default:
?>
<fieldset>
<legend>列表</legend>
<input type="button" value="添加新专家" onClick="location.href='?action=add'"/>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tbody>
		<tr class="tbList">
			<th>ID</th>
			<th><input type="checkbox" onClick="if(this.checked == true){jQuery('input[name=\'chkbox\']').attr('checked',true);}else{jQuery('input[name=\'chkbox\']').attr('checked',false);}" /></th>
			<th>姓名</th>
			<th>头衔/职称</th>
			<th>图像</th>
			<th>地接商编号</th>
			<th>可用状态</th>
			<th>使用的产品</th>
			<th>相关操作</th>
		</tr>
		<?php
		$pageMaxRowsNum = 10; //每页显示10条记录
		$sql = 'select eg.*,tta.agency_name from expert_group eg,tour_travel_agency tta where tta.agency_id=eg.agency_id ';
		$keywords_query_numrows = 0;
		$_split = new splitPageResults($_GET['page'], $pageMaxRowsNum, $sql, $keywords_query_numrows);
		$rs = tep_db_query($sql);
		while($row = tep_db_fetch_array($rs)) {
		?>
		<tr class="tbList">
			<td align="center"><?php echo $row['expert_id']?></td>
			<td align="center"><input type="checkbox" name="chkbox" value="<?php echo $row['expert_id']?>" /></td>
			<td><?php echo $row['expert_name']?></td>
			<td><?php echo $row['expert_job_title']?></td>
			<td><img src="<?php echo $row['expert_img']?>" /></td>
			<td><?php echo $row['agency_name']?></td>
			<td style="padding-left:10px;"><?php if ($row['expert_disabled'] == 1) {
					echo $can_expert_group_open ? '<a href="?action=open_expert&status=0&expert_id=' .$row['expert_id']  . '" style="display:inline-block;padding:5px 10px 5px 5px;"><img width="10" height="10" border="0" title="点击启用此专家" alt="点击启用此专家" src="images/icon_status_green_light.gif"></a>' : '<img style="margin:0px 10px 0px 5px;" width="10" height="10" border="0" alt="Inactive" src="images/icon_status_green_light.gif" title="你无权进行此操作！"/>';
					echo '<img width="10" height="10" border="0" title="此专家当前已禁用" alt="Inactive" src="images/icon_status_red.gif"/>';
				} else {
					echo '<img width="10" height="10" border="0" title="此专家已经启用" alt="此专家已经启用" src="images/icon_status_green.gif"/>';
					
					echo $can_expert_group_open ? '<a href="?action=open_expert&status=1&expert_id=' .$row['expert_id']  . '" style="display:inline-block;padding:5px 5px 5px 10px;"><img width="10" height="10" border="0" title="点击禁用此专家" alt="点击禁用此专家" src="images/icon_status_red_light.gif"></a>' : '<img style="margin:0px 10px 0px 5px;" width="10" height="10" border="0" alt="Set Active" src="images/icon_status_red_light.gif" title="你无权进行此操作！">';
					
					
				} ?></td>
			<td><?php 
			$pro_sql = "select products_id from products where find_in_set('" . $row['expert_id'] . "',expert_ids)";
			$pro_result = tep_db_query($pro_sql);
			$pro_ids = '';
			while($pro_rs = tep_db_fetch_array($pro_result)){
				$pro_ids .= $pro_rs['products_id'] . ',';
			}
			$pro_ids = trim($pro_ids,',');
			?><input type="text" name="products" id="products_<?php echo $row['expert_id']?>" value="<?php echo $pro_ids?>"  onkeyup="this.value = this.value.replace(/[^0-9\,]/g,'')"/><input type="hidden" value="<?php echo $pro_ids?>" id="oldProducts_<?php echo $row['expert_id']?>" /><input type="button" value="更新到产品" title="点此按钮把当前专家设置到您填写的产品中"  onClick="updateProduct(document.getElementById('products_<?php echo $row['expert_id']?>'),'<?php echo $row['expert_id']?>','oldProducts_<?php echo $row['expert_id']?>')" /></td>
			<td><a href="?action=edit&editid=<?php echo $row['expert_id']?>">编辑</a>&nbsp;<?php if ($can_expert_group_del) { ?><a href="?action=del&editid=<?php echo $row['expert_id']?>" onClick="if(confirm('您确认要删除此专家吗？该操作不可逆！')){} else {return false}">删除</a><?php }?></td>
		</tr>
		<?php 
		}?>
		<tr>
			<td></td>
			<td align="center"><input type="checkbox" onClick="if(this.checked == true){jQuery('input[name=\'chkbox\']').attr('checked',true);}else{jQuery('input[name=\'chkbox\']').attr('checked',false);}" />全选</td>
			<td colspan="7"><input type="button" value="反选" onClick="unselect()"/> 产品(多个请用英文逗号隔开)：<input type="text" value="" onKeyUp="this.value = this.value.replace(/[^0-9\,]/g,'')" id="more_products" /> <input type="button" value="更新选中的到产品" onClick="updateProduct(document.getElementById('more_products'),'chkbox')" />&nbsp;&nbsp;地接商：<?php 
			echo tep_draw_pull_down_menu('agency_id', $data, '', ' id="agency_id"');//$rs['agency_id']?>&nbsp;&nbsp;<input type="button" value="更新选中的到指定地接" onClick="updateProductByAgency('agency_id')" /></td>
		</tr>
		<tr>
			<td colspan="9" align="center"><?php echo $_split->display_links($_split->query_num_rows,$pageMaxRowsNum,6, $_GET['page'])?></td>
		</tr>
	</tbody>
</table>
</fieldset>
<?php
	break;

}?>
<script language="javascript" type="text/javascript">
function updateProduct(products_ids,val,oldProductObj){
	if (products_ids.value == '') {
		alert('您还没填写产品ID呢!');
		products_ids.focus();
		return;
	}
	if (val == 'chkbox') {
		var temp='';
		jQuery.each(jQuery('input[name=\'chkbox\']:checked'),function(){
			temp += jQuery(this).val() + ',';
		});
		temp = temp.substr(0,temp.length-1);
		//var val = jQuery('input[name=\'chkbox\']:checked').attr('checked');
		val = temp;
	}
	var oldProductsId = '';
	var cj = []; // 差集
	if (oldProductObj != '' && typeof oldProductObj != 'undefined' && typeof oldProductObj == 'string') {
		oldProductsId = document.getElementById(oldProductObj).value;
		var arr1 = oldProductsId.split(',');
		var arr2 = products_ids.value.split(',');
		console.log(arr1);
		console.log(arr2);
		for(var i=0; i < arr1.length; i++){   
	        var flag = true;   
    	    for(var j=0; j < arr2.length; j++){   
        	    if(arr1[i] == arr2[j])   
            		flag = false;   
        	}   
        	if(flag)   
        		cj.push(arr1[i]);   
    	}
	}
	var msg = "你确定要设置专家到这些产品(" + products_ids.value + ")中";
	if (cj.length > 0) {
		msg += ",并且去掉产品(" + cj.join(',') + ")中的专家ID(" + val + ")";
	}
	msg += '?';
	if(confirm(msg)) {
		jQuery.post('expert_group.php?action=copy_products&ajax=true',{'pid':products_ids.value,'val':val,'oldpid':oldProductsId},function(r){
			if (r.state == 0) {
				//parentObj.find('input[id=\'products_id_copy\']').val('');
				alert('更新成功！');
			} else {
				alert(r.error);
			}
		},'json');
	}
	return false;
}

function updateProductByAgency(slt){
	var agency_id = $('select[name="agency_id"] option:selected').val();
	var temp='';
	jQuery.each(jQuery('input[name=\'chkbox\']:checked'),function(){
		temp += jQuery(this).val() + ',';
	});
	temp = temp.substr(0,temp.length-1);
	//var val = jQuery('input[name=\'chkbox\']:checked').attr('checked');
	val = temp;
	var msg = "你确定要设置专家(" + val + ")到地接商ID为(" + agency_id + ")的所有产品中";
	msg += '?';
	if(confirm(msg)) {
		jQuery.post('expert_group.php?action=copy_agency_product&ajax=true',{'val':val,'agency_id':agency_id},function(r){
			if (r.state == 0) {
				//parentObj.find('input[id=\'products_id_copy\']').val('');
				alert('更新成功！');
			} else {
				alert(r.error);
			}
		},'json');
	}
	
}

function unselect(){
	jQuery('input[name=\'chkbox\']').each(function(){
		if($(this).attr('checked')){
			$(this).attr('checked',false);
		} else {
			$(this).attr('checked',true);
		}
	});
}
</script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
