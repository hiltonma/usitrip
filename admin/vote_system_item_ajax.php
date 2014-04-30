<?php
require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'ajax_encoding_control.php');
$item_num = $_GET['item_num'];
?>


<div id="item_<?php echo $item_num?>">
	<div>
	顺序：
	<?php echo tep_draw_input_field('v_s_i_sort['.$item_num.']', (1 + $item_num),' size="4" ');?>
	<br>
	题目：
	<?php echo tep_draw_input_field('v_s_i_title['.$item_num.']');?>
	<a href="javascript:delele_item(item_<?php echo $item_num?>);"><?php echo tep_image_button('s_del_buttom.gif','删除选项','align="absmiddle"')?></a>
	<br>
	
	类型：<label><?php echo tep_draw_radio_field('v_s_i_type_'.$item_num.'_radio', '0','','','onClick="set_item_option(\'v_s_i_type_'.$item_num.'\',this.value)"').'单选</label> <label>'.tep_draw_radio_field('v_s_i_type_'.$item_num.'_radio', '1','','','onClick="set_item_option(\'v_s_i_type_'.$item_num.'\',this.value)"').'多选</label> <label>'.tep_draw_radio_field('v_s_i_type_'.$item_num.'_radio', '2','','','onClick="set_item_option(\'v_s_i_type_'.$item_num.'\',this.value)"').'文本';?></label>
	<?php echo tep_draw_hidden_field('v_s_i_type['.$item_num.']', '', 'size="2" id ="v_s_i_type_'.$item_num.'"');?>
	</div>
	<div>答案总数：<?php echo tep_draw_input_field('item_options_total['.$item_num.']', '', 'size="2" id ="item_options_total'.$item_num.'"');?><input type="button" name="Submit" value="确定" onClick="get_options_total('item_options_total<?php echo $item_num?>','v_s_i_type_<?php echo $item_num?>', 'item_options_<?php echo $item_num?>', <?php echo $item_num?>)"></div>
	<div id="item_options_<?php echo $item_num?>"></div>
<hr>
</div>

