<?php
require 'includes/classes/OrderComplaints.class.php';
if (isset($_POST['ajax'])) {
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	require_once ('includes/application_top.php');
	header("Content-type: text/html; charset=" . CHARSET);
	$complaints = new OrderComplaints($_POST['mydata2']);
	switch ($_POST['ajax']) {
		case 'change_near'://修改个人供应商ID
			if($_POST['mydata1']){
			$complaints->changeAgencyComplaints($_POST['mydata2'], $_POST['mydata1']);
			$complaints->changeOne('supplier_id', $_POST['mydata1']);
			}
			exit;
			break;
		case 'add_one':
			//添加一个
			echo $complaints->addOne($_POST['mydata1']);
			
			exit;break;
		case 'drop_one'://删除
			$complaints->dropOne($_POST['mydata1']);
			
			exit;break;
		case 'change_step'://修改步骤
			$complaints->changeOne('complaints_step', iconv('utf-8', 'gb2312', $_POST['mydata1']));
			exit;break;
	}
}

$complaints = new OrderComplaints();
//ajax添加投诉
if(isset($_POST['complaints_id']))
	$complaints->doAdd();
//ajax添加投诉
$data_list = $complaints->getListIndex($_GET['oID']);
if ($data_list) {//当订单有投诉的时候{
	?>
<table cellpadding="2" cellspacing="0" class="order_head"
	style="padding: 10px; color: red" width="60%;">
			 <?php foreach($data_list as $value){?>
			  <tr id="<?php echo $value['complaints_id']?>" lang="<?php echo $value['complaints_id']?>">
		<td align="right">投诉：</td>
		<td><span class="col_b"><input type="radio" checked="checked" disabled/><input
				type="button" value="更新"
				onclick="addDeleteCom(jQuery(this).parents('tr').attr('id'),jQuery(this).parent())" /></span></td>
		<td align="right">相关供应商:</td>
		<td><span class="col_b"><select name="near_ngency" id="near_ngency"><?php echo $complaints->dreawAgencyOption($complaints->getAgency(),$value['supplier_id']);?></select><input
				type="button" value="更新"
				onclick="mySendAjax('change_near',jQuery(this).parent().find(':input').val(),jQuery(this).parents('tr').attr('id'))" /></span></td>
		<td align="right">处理进度：</td>
		<td><span class="col_b"><select name="step" id="c_step"><?php echo $complaints->dreawOption($complaints->step_array, $value['complaints_step']);?></select><input
				type="button" value="更新"
				onclick="mySendAjax('change_step',jQuery(this).parent().find(':input').val(),jQuery(this).parents('tr').attr('id'))" /></span></td>
		<td align="center"><input type="button" value="备注" onclick="showHide(jQuery(this).parent())"/><!-- showHide(jQuery(this).parent())-->
		<?php $complaints->showAdd($value);?></td>
	</tr>
			  <?php }?>
			  <tr>
		<td colspan="7" align="center"><input type="button" value="增加+++"
			onclick="addOneAdd(jQuery(this).parents('table.order_head'))" /></td>
	</tr>
</table>
<?php 
//当订单有投诉的时候}
}else{
//当订单没有投诉的时候{
?>

<table cellpadding="2" cellspacing="0" class="order_head"
	style="padding: 10px;" width="60%;">
	<tr>
		<td align="right">投诉：</td>
		<td><span class="col_b"><input type="radio" disabled/><input
				type="button" value="更新"
				onclick="addDeleteCom(<?php echo $_GET['oID']?>,jQuery(this).parent())" /></span></td>
		<td align="right">相关供应商:</td>
		<td><span class="col_b"><select name="near_ngency" id="near_ngency"><?php echo $complaints->dreawAgencyOption($complaints->getAgency());?></select><input
				type="button" value="更新"
				onclick="mySendAjax('change_near',jQuery(this).parent().find(':input').val(),jQuery(this).parents('tr').attr('id'))" /></span></td>
		<td align="right">处理进度：</td>
		<td><span class="col_b"><select name="step" id="c_step"><?php echo $complaints->dreawOption($complaints->step_array);?></select><input
				type="button" value="更新"
				onclick="mySendAjax('change_step',jQuery(this).parent().find(':input').val(),jQuery(this).parents('tr').attr('id'))" /></span></td>
		<td align="center"><input type="button" value="备注" onclick="showHide(jQuery(this).parent())"/><!--showHide(jQuery(this).parent()) --><?php $complaints->showAdd($_GET['oID']);?></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><input type="button" value="增加+++"
			onclick="addOneAdd(jQuery(this).parents('table.order_head'))" /></td>
	</tr>
</table>
<?php 
//当订单有投诉的时候}
}?>
<script type="text/javascript" language="javascript">

/**
 *大部分的ajax提交方法
 */
function mySendAjax(my_ajax,mydata1='',mydata2=''){
jQuery.post('edit_order_complaints.php',{ajax:my_ajax,mydata1:mydata1,mydata2:mydata2},function(data){if(data=='true')alert("更新成功");});
}
//投诉旁边的更新按钮点击事件
function addDeleteCom(id,doc){
	
	if(!doc.find(':radio:checked').val()){
		jQuery.post('edit_order_complaints.php',{ajax:'add_one',mydata1:id},function(data){doc.parent('td').parent('tr').attr("id",data);});
		doc.parent('td').parent('tr').css("color","red");
		doc.find(':radio').attr("checked",'checked');
		}else{
			var aa= window.confirm("单击“确定”删除。单击“取消”停止。");
			if (aa) {
				mySendAjax('drop_one',id);
				doc.parent('td').parent('tr').css("display","none");
			}else window.alert("你选了取消！");
			}
}
//添加点击事件
function addOneAdd(doc){
//console.log(doc);
	doc.append('<tr style="color:#000000"><td align="right">投诉：</td><td><span class="col_b"><input type="radio" disabled/><input type="button" value="更新"	onclick="addDeleteCom(<?php echo $_GET['oID']?>,jQuery(this).parent())" /></span></td><td align="right">相关供应商:</td><td><span class="col_b"><select name="near_ngency" id="near_ngency"><?php echo str_replace("'", "\'", $complaints->dreawAgencyOption($complaints->getAgency()));?></select><input	type="button" value="更新" onclick="mySendAjax(\'change_near\',jQuery(this).parent().find(\':input\').val(),jQuery(this).parents(\'tr\').attr(\'id\'))" /></span></td><td align="right">处理进度：</td><td><span class="col_b"><select name="step" id="c_step"><?php echo $complaints->dreawOption($complaints->step_array);?></select><input type="button" value="更新" onclick="mySendAjax(\'change_step\',jQuery(this).parent().find(\':input\').val(),jQuery(this).parents(\'tr\').attr(\'id\'))" /></span></td><td align="center"><input type="button" value="备注" onclick="showHide(jQuery(this).parent())"/><?php $complaints->showAdd($_GET['oID']);?></td></tr>');
}
//备注点击事件
function showHide(docName){
var doctr=docName.parent('tr').attr("id");
console.log(doctr);
if(doctr){
		docName=docName.find('table')
		window.open('order_complaints.php?action=change&&complaints_id='+doctr);
		//if(docName.is(":hidden")){
			//docName.show();
			//docName.find('#complaints_id').val(doctr);
			//docName.find('#complaints_id_h').val(doctr);
			//}
		//else{
			//docName.hide();
			//}
		}else{
		alert("请先更新投诉的状态");
		}
}
//当供应商改变的时候改变时间动作
function changeInTime(value,doc){

var arr=value.split('::');
jQuery(doc[0]).find('#my_u_time').val(arr[1]);
jQuery(doc[0]).find('#my_u_time_hide').val(arr[1]);
}
</script>