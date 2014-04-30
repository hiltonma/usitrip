<?php

$product_id = isset($_GET['pID']) ? intval($_GET['pID']) : 0;
if (!$product_id && !isset($_GET['ajax'])) {
	echo 'pid丢失！请刷新页面重试！';
}
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
require('includes/application_top.php');

if ($_GET['action'] == 'process') {
	$expert_group_ids_temp = isset($_POST['aryFormData']['expert_group']) ? $_POST['aryFormData']['expert_group'][0] : '';
	$data=array('expert_ids'=>$expert_group_ids_temp);
	tep_db_fast_update("products", "products_id='" . $product_id . "'", $data);
	$messageStack->add('成功更新该产品的专家团', 'success');
}

// by lwkai added 复制产品 start
if($_GET['section'] == 'copy_products') {
	$products_id = isset($_POST['pid']) ? $_POST['pid'] : '';
	$val = isset($_POST['val']) ? $_POST['val'] : '';
	if (tep_not_null($products_id) && tep_not_null($val)) {
		$products_arr = explode(',',$products_id);
		foreach($products_arr as $key=>$v){
			$data = array();
			$data['expert_ids'] = trim($val);
			tep_db_fast_update('products','products_id=' . intval($v),$data);
		}
		echo '{"state":0,"error":"' . $_POST['pid'] . '"}';
	} else {
		echo '{"state":1,"error":"数据不全"}';
	}
	exit;
}

$sql = "select products_id,expert_ids from products where products_id='" . $product_id . "'";
$result = tep_db_query($sql);
$rs = tep_db_fetch_array($result);
$expert_group_ids = $expert_group_ids_copy = $rs['expert_ids'];
$expert_group_ids = explode(',',$expert_group_ids);

$sql = "select eg.*,tta.agency_name from expert_group eg,tour_travel_agency tta where tta.agency_id=eg.agency_id ";
$result = tep_db_query($sql);
$data = array();
$selected_group_list = array();
while($row = tep_db_fetch_array($result)) {
	if (in_array($row['expert_id'],$expert_group_ids)) {
		$selected_group_list[] = array('id'=>$row['expert_id'],'text'=>$row['expert_name'] . ' - ' . $row['agency_name']);
	} else {
		$data[] = array('id' => $row['expert_id'],'text'=>$row['expert_name'] . ' - ' . $row['agency_name']);
	}
}

if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>

<form id="expert_group_form" action="" method="post">
<table border="0" cellspaceing="0" cellpadding="0">
<tbody>
<tr>
	<td>专家列表(专家名称 - 地接名称)</td>
	<td>&nbsp;</td>
	<td>已经选择的专家</td>
	<td>复制当前专家团到其他产品线路</td>
</tr>
<tr>
	<td><?php echo tep_draw_pull_down_menu("group_list", $data,'','id="early_arrival_hotels_temp" size="10" multiple="multiple" style="width:200px;"')?></td>
	<td style="padding:10px 30px 10px 0;">
	<input type="button" value="--&gt;&gt;" onclick="moveOptions(this.form.early_arrival_hotels_temp, this.form.early_arrival_hotels_temp1);"/><br/>
	<input type="button" value="&lt;&lt;--" onclick="moveOptions(this.form.early_arrival_hotels_temp1, this.form.early_arrival_hotels_temp);"/></td>
	<td><?php echo tep_draw_pull_down_menu("selected_group_list", $selected_group_list,'','id="early_arrival_hotels_temp1" size="10" multiple="multiple" style="width:200px;"');
	?><input type="hidden" name="expert_group" id="expert_group" value="">
	</td>
	<td>
		<fieldset>
			<legend>复制到其他产品</legend>产品ID：<br/>
			<input type="text" id="products_id_copy" />
			<input type="hidden" id="val_copy" value="<?php echo $expert_group_ids_copy ?>"/><br/>
			(英文逗号隔开产品id，并且不可有空格。如：14,789,3054)<br/>
			<button onclick="return copyhotel(this)">复制</button>
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="4" align="center">
	<?php 
	echo tep_image_button('button_update.gif', IMAGE_UPDATE, 'style="cursor:pointer" 
onclick="SelectAll_hotels(document.getElementById(\'early_arrival_hotels_temp1\'), document.getElementById(\'expert_group\')); 
 sendFormData(\'expert_group_form\',\''. tep_href_link('expert_group_ajax.php', 'action=process&pID=' . $_GET['pID'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $_GET['searchkey'] . '' : '') .(isset($_GET['agency']) ? '&agency=' . $_GET['agency'] . '' : '').(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '')).'\',\'countrydivcontainer\',\'false\',\'\',\'\',\'document.getElementById(\\\'product_ajax_edit_tabs\\\').scrollIntoView(true)\');" ');
	echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['pID'] . '&preview=true') . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
	?></td>
</tr>
</tbody>
</table>
</form>
<script language="text/javascript">
function copyhotel(obj){
	var parentObj = jQuery(obj).parent();
	var val = parentObj.find('input[id=\'val_copy\']').val();
	var pid = parentObj.find('input[id=\'products_id_copy\']').val();
	if(confirm("你确定要复制此信息到指定的线路？")) {
		jQuery.post('expert_group_ajax.php?section=copy_products&ajax=true',{'pid':pid,'val':val},function(r){
			if (r.state == 0) {
				parentObj.find('input[id=\'products_id_copy\']').val('');
				alert('复制成功！');
			} else {
				alert(r.error);
			}
		},'json');
	}
	return false;
}
</script>