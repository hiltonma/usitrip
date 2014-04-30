<?php
require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'ajax_encoding_control.php');
//删除调查项目
if($_GET['action']=='del' && (int)$_GET['v_s_i_id']){
	if((int)$_GET['v_s_i_id']){
		$vsi_sql = tep_db_query('SELECT v_s_i_id FROM `vote_system_item` WHERE `v_s_i_id` = "'. (int)$_GET['v_s_i_id'].'" ');
		while($vsi_rows = tep_db_fetch_array($vsi_sql)){
			tep_db_query('DELETE FROM `vote_system_item_options` WHERE `v_s_i_id` = "'. (int)$vsi_rows['v_s_i_id'].'" ');
		} 
		tep_db_query('DELETE FROM `vote_system_item` WHERE `v_s_i_id` = "'. (int)$_GET['v_s_i_id'].'" ');
		tep_db_query('DELETE FROM `vote_system_results` WHERE `v_s_i_id` = "'. (int)$_GET['v_s_i_id'].'" ');
	}
	exit;
}

//编辑
if(!(int)$_GET['v_s_id']){ exit;}

//取得调查项目
$v_item_sql = tep_db_query('SELECT * FROM `vote_system_item` WHERE  v_s_id="'.(int)$_GET['v_s_id'].'" Order By  v_s_i_sort ASC, v_s_i_id ASC');
$item_num = 0;


while($v_item_rows = tep_db_fetch_array($v_item_sql)){
	//取得调查答案
	$v_options_sql = tep_db_query('SELECT * FROM `vote_system_item_options` WHERE v_s_i_id="'.(int)$v_item_rows['v_s_i_id'].'"  Order By v_s_i_o_id ');
	
?>


<div id="item_<?php echo $item_num?>">
	
	<div>
	顺序：
	<?php echo tep_draw_input_field('v_s_i_sort['.$item_num.']', $v_item_rows['v_s_i_sort'],' size="4" ');?>
	<br>
	题目：
	<?php echo tep_draw_input_field('v_s_i_title['.$item_num.']', $v_item_rows['v_s_i_title']);?>
	<?php echo tep_draw_hidden_field('v_s_i_id['.$item_num.']', $v_item_rows['v_s_i_id']);?>
	<a href="javascript:delele_item_ajax('item_<?php echo $item_num?>',<?php echo $v_item_rows['v_s_i_id']?>);"><?php echo tep_image_button('s_del_buttom.gif','删除选项','align="absmiddle"')?></a>
	<br>
	<?php
	$radio_checked = '';
	$checkbox_checked = '';
	$text_checked = '';
	if((int)$v_item_rows['v_s_i_type']=='0'){
		$radio_checked = ' checked ';
	}
	if((int)$v_item_rows['v_s_i_type']=='1'){
		$checkbox_checked = ' checked ';
	}
	if((int)$v_item_rows['v_s_i_type']=='2'){
		$text_checked = ' checked ';
	}
	?>
	类型：<label><?php echo tep_draw_radio_field('v_s_i_type_'.$item_num.'_radio', '0','','',$radio_checked.' disabled onClick="set_item_option(\'v_s_i_type_'.$item_num.'\',this.value)"').'单选</label> <label>'.tep_draw_radio_field('v_s_i_type_'.$item_num.'_radio', '1','','',$checkbox_checked.' disabled onClick="set_item_option(\'v_s_i_type_'.$item_num.'\',this.value)"').'多选</label> <label>'.tep_draw_radio_field('v_s_i_type_'.$item_num.'_radio', '2','','',$text_checked.' disabled onClick="set_item_option(\'v_s_i_type_'.$item_num.'\',this.value)"').'文本';?></label>
	<?php echo tep_draw_hidden_field('v_s_i_type['.$item_num.']', $v_item_rows['v_s_i_type'], 'size="2" id ="v_s_i_type_'.$item_num.'"');?>
	</div>
	
	<div id="item_options_<?php echo $item_num?>">
	
	<?php
	//列出答案选项
	while($v_options_rows = tep_db_fetch_array($v_options_sql)){
		echo '&nbsp;&nbsp;'.$v_options_rows['v_s_i_o_title'].'<br>';
	}	
	?>
	
	</div>

<hr>
</div>

<?php
$item_num ++;
}
?>
