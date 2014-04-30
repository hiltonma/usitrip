<form action="" method="post" name="CompanionForm" id="CompanionForm" onSubmit="Submit_Companion('CompanionForm'); return false" >
<div>
	<div class="jb_fb_tc_bt">
		<h3><?php echo db_to_html("结伴同游信息")?></h3><label>&nbsp;<?= db_to_html("（以下信息必填）");?></label>
      <?php
	  if(!(int)$categories_id){
	  	$categories_id = (int)$categories;
	  }
	  if(!(int)$categories_id){
	  	$categories_id = (int)$cPathOnly;
	  }
	  //结伴同游BBS中的发帖
	  if (isset($TcPath_array) && count($TcPath_array) ) {
	  	$categories_id = $TcPath_array[count($TcPath_array)-1];
	  }
	  ?>
	 <input name="categories_id" type="hidden" id="categories_id" value="<?= $categories_id?>" />
	<?php
	if((int)$categories_id){
		//echo db_to_html(preg_replace('/ .+/','',tep_get_categories_name($categories_id,1))). ' &gt;&gt; ';
	}
	?>
	<input name="products_id" type="hidden" id="products_id" value="<?= $products_id?>" />
	<?php
	if((int)$products_id){
		$products_name = tep_get_products_name($products_id, 1);
		//echo '<span title="'.db_to_html($products_name).'">'.cutword(db_to_html($products_name),12).'</span> &gt;&gt; ';
	}
	?>

		<span style="float:right"><button type="button" title="<?php echo db_to_html('关闭');?>" onclick="javascript:closePopup(&quot;CreateNewCompanion&quot;)" class="icon_fb_bt"/></button></span>
		</div>
	<div class="jb_fb_tc_tab">
		<table>
			<tr>
				<td><span><?php echo db_to_html('结伴类型：');?></span></td>
				 <td>
				 <p class="partner_sty"><?php if(!(int)$products_id){ ?><label class="par_pinfang"><input checked type="radio" name="type_jieban" id="type_jieban1" value="1" onclick="MychangeWay(this)"/> <?php echo db_to_html('结伴拼房');?> </label><label><input type="radio" name="type_jieban" id="type_jieban2" value="2" onclick="MychangeWay(this)"/> <?php echo db_to_html('自由行 ');?> </label><label><input type="radio" name="type_jieban" id="type_jieban3" value="3" onclick="MychangeWay(this)"/> <?php echo db_to_html('自驾游');?> </label><?php }else{?><label class="par_pinfang"><input checked type="radio" name="type_jieban" id="type_jieban1" value="1" onclick="MychangeWay(this)" style="display:none"/> <?php echo db_to_html('结伴拼房');?> </label><?php }?></p>
				</td>
			</tr>
			<tr>
				<td><span><?php echo db_to_html("发布内容：")?></span></td>
				<td>
					<?php
					$title_and_value = '请为活动输入发布内容';
					if(!tep_not_null($t_companion_title)){
						$t_companion_title = db_to_html($title_and_value);
					}
					echo tep_draw_input_field('t_companion_title','','  class="required text_fb_bt" title="'.db_to_html($title_and_value).'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" style="color:#bbbbbb; " ');
					?>
				</td>
			</tr>
				<?php
		$temp = '';
			if((int)$products_id){
				$t_companion_content1 = db_to_html(tep_get_products_name((int)$products_id));
				$temp = $t_companion_content_content;
			}
			//$title_and_value = '请输入你希望去的景点或路线';
			if(!tep_not_null($t_companion_content_content)){
				//$t_companion_content_content = db_to_html($title_and_value); // 如果没有结伴线路 则需要提供下拉筛选
				$t_companion_content1 = tep_simple_drop_down('area1', db_to_html(array(
					'0' => '请选择区域',
					'25' => '美东',
					'24' => '美西',
					'33' => '夏威夷',
					'54' => '加拿大',
					'157' => '欧洲',
					'298' => '海外游学'
				)),db_to_html('请选择区域'),'id="area" onchange="getCity(this)"');
				$t_companion_content1 .= db_to_html('城市:');
				$t_companion_content1 .= tep_simple_drop_down('start_city', array('0' => db_to_html('请选择')),'','id="start_city"');
			//echo $t_companion_content1;
			$temp = '';
			$t_companion_content1=$t_companion_content_content='';
			if((int)$products_id){
				$t_companion_content_content = db_to_html(tep_get_products_name((int)$products_id));
				$temp = $t_companion_content_content;
			}
			//$title_and_value = '请输入你希望去的景点或路线';
			if(!tep_not_null($t_companion_content_content)){
				//$t_companion_content_content = db_to_html($title_and_value); // 如果没有结伴线路 则需要提供下拉筛选
				$t_companion_content1 = tep_simple_drop_down('area', db_to_html(array(
					'0' => '请选择区域',
					'25' => '美东',
					'24' => '美西',
					'33' => '夏威夷',
					'54' => '加拿大',
					'157' => '欧洲',
					'298' => '海外游学'
				)),db_to_html('请选择区域'),'id="area" onchange="getCity(this)"');
				$t_companion_content1 .= db_to_html('出发地城市:');
				$t_companion_content1 .= tep_simple_drop_down('start_city', array('0' => db_to_html('请选择')),'','id="start_city" onchange="getProducts()"');
				
				$t_companion_content_content .= $t_companion_content1.db_to_html('目的地:');
				$t_companion_content_content .= tep_simple_drop_down('end_city', array('0'=>db_to_html('请选择')),'','id="end_city" onchange="getProducts()"');
				$t_companion_content_content .= '<br/>' . tep_simple_drop_down('products_names', array('0'=>db_to_html('请选择旅游线路')),'','id="products_select" style="width:435px;overflow:hidden" onchange="set_products_id(this)"');
				/*$t_companion_content_content = tep_draw_pull_down_menu('area',array(
						array('text'=>'请选择区域'), 
						array('id' => '25', 'text' => '美东'), 
						array('id' => '24', 'text' => '美西'),
						array('id' => '33', 'text' => '夏威夷'),
						array('id' => '4', 'text' => '加拿大'),
						array('id' => '5', 'text' => '欧洲'),

				), '请选择区域');*/
			}
			$textarea_class = "required textarea_fb_bt";
			$textarea_readonly = "";
			$textarea_display = ' style="border:1px solid #d5d5d5; width:429px; padding:3px; margin:0; height:30px;" ';
			if((int)$products_id){
				$textarea_class = "";
				$textarea_readonly = ' readonly="true" ';
				$textarea_display = ' style="display:none"; ';
				$t_companion_content_content .= $t_companion_content_content;
			}
			$t_companion_content_content .= '<span style="display:none">';
			$t_companion_content_content .= tep_draw_textarea_field('t_companion_content', 'soft', '', '',$temp,' class="'.$textarea_class.'" id="t_companion_content" title="'.db_to_html($title_and_value).'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" '.$textarea_readonly.$textarea_display);
			$t_companion_content_content .= '</span>';
			//unset($t_companion_content_content);
			$t_companion_content1.='<div class="dest_box">'.tep_draw_hidden_field("end_place",'','id=destinationVal').'<div class="destination" id="my_destination"><ul></ul>'.tep_draw_input_field('end_place_tmp','','  id="dest_text" onkeyup="this.value = traditionalized(this.value);getMyCity(this);" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ').'<div style="clear:both;"></div></div><ul class="dest_list"></ul></div>';
			}
			?>
			<tr class="p_sty2"><td><span id="my_span"><?php echo db_to_html("旅游线路：")?></span></td><td id="my_td">
			<?=$t_companion_content_content?>
			</td>
			</tr>
			<tr ><td><span><?php echo db_to_html("出行时间：")?></span></td><td>
			<?php
			if((int)$products_id && tep_not_null($avaliabledate)){
				echo '<select class="validate-selection-blank sel3" name="hope_departure_date" title="'.TEXT_SELECT_VALID_DEPARTURE_DATE.'" >
						'.$avaliabledate.'
					</select>';
			}else{
			?>
		<table><tr><td style="width:120px; padding-top: 0px;">
	<?php
	$use_new_window_date = true;
	if($use_new_window_date != true){
	?>
	<script type="text/javascript">
	var Dep_Date = new ctlSpiffyCalendarBox("Dep_Date", "CompanionForm", "hope_departure_date","btnDate3CF","<?php echo ($hope_departure_date); ?>",scBTNMODE_CUSTOMBLUE);
	Dep_Date.writeControl(); Dep_Date.dateFormat="yyyy-MM-dd";
	</script>
	<?php
	}else{
	?> 
	 <?php echo tep_draw_input_field('hope_departure_date','', 'onClick="GeCalendar.SetUnlimited(0); GeCalendar.SetDate(this);" class="text_time" id="hope_departure_date"');?>
	<?php
	}
	?> 
	 </td><td valign="middle" style="padding-top: 0px;">&nbsp;-</td><td style="padding-top: 0px;"> 
	<?php
	if($use_new_window_date != true){
	?>
	<script type="text/javascript">
	var Dep_Date_End = new ctlSpiffyCalendarBox("Dep_Date_End", "CompanionForm", "hope_departure_date_end","btnDate4CF","<?php echo ($hope_departure_date_end); ?>",scBTNMODE_CUSTOMBLUE);
	Dep_Date_End.writeControl(); Dep_Date_End.dateFormat="yyyy-MM-dd";
	</script>
	<?php
	}else{
	?>
	 <?php echo tep_draw_input_field('hope_departure_date_end','', 'onClick="GeCalendar.SetUnlimited(0); GeCalendar.SetDate(this);" class="text_time" id="hope_departure_date_end"');?>
	<?php 	
	}
	?>
		</td></tr></table>	
			<?php
			}
			?>
			</td></tr>
			<tr><td nowrap="nowrap"><span><?php echo db_to_html("现有人数：")?></span></td>
			<td>
			<?php
			/*if(!tep_not_null($now_people_man)){$now_people_man ="0"; }
			if(!tep_not_null($now_people_woman)){$now_people_woman ="0"; }
			if(!tep_not_null($now_people_child)){$now_people_child ="0"; }
			echo tep_draw_hidden_field('now_people_man');
			echo tep_draw_hidden_field('now_people_woman');
			echo tep_draw_hidden_field('now_people_child');
			switch($now_people_man){
				case '0': $now_people_man_0_class = $now_people_man_1_class = $now_people_man_2_class = "a_sex_fav"; break;
				case '1': $now_people_man_0_class = "a_sex_del"; $now_people_man_1_class = $now_people_man_2_class = "a_sex_fav"; break;
				case '2': $now_people_man_1_class = "a_sex_del"; $now_people_man_0_class = $now_people_man_2_class = "a_sex_fav"; break;
				case '3': $now_people_man_2_class = "a_sex_del"; $now_people_man_1_class = $now_people_man_2_class = "a_sex_fav"; break;
			}
			switch($now_people_woman){
				case '0': $now_people_woman_0_class = $now_people_woman_1_class = $now_people_woman_2_class = "a_sex_fav"; break;
				case '1': $now_people_woman_0_class = "a_sex_del"; $now_people_woman_1_class = $now_people_woman_2_class = "a_sex_fav"; break;
				case '2': $now_people_woman_1_class = "a_sex_del"; $now_people_woman_0_class = $now_people_woman_2_class = "a_sex_fav"; break;
				case '3': $now_people_woman_2_class = "a_sex_del"; $now_people_woman_1_class = $now_people_woman_2_class = "a_sex_fav"; break;
			}
			switch($now_people_child){
				case '0': $now_people_child_0_class = $now_people_child_1_class = $now_people_child_2_class = "a_sex_fav"; break;
				case '1': $now_people_child_0_class = "a_sex_del"; $now_people_child_1_class = $now_people_child_2_class = "a_sex_fav"; break;
				case '2': $now_people_child_1_class = "a_sex_del"; $now_people_child_0_class = $now_people_child_2_class = "a_sex_fav"; break;
				case '3': $now_people_child_2_class = "a_sex_del"; $now_people_child_1_class = $now_people_child_2_class = "a_sex_fav"; break;
			}
			?>
			
			<label><?php echo db_to_html("男")?></label><a id="now_people_man_0" class="<?=$now_people_man_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1">1</a><a id="now_people_man_1" class="<?=$now_people_man_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="2">2</a><a id="now_people_man_2" class="<?=$now_people_man_2_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="3">3</a><label class="m_l"><?php echo db_to_html("女")?></label><a id="now_people_woman_0" class="<?=$now_people_woman_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1">1</a><a id="now_people_woman_1" class="<?=$now_people_woman_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="2">2</a><a id="now_people_woman_2" class="<?=$now_people_woman_2_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="3">3</a><label class="m_l"><?php echo db_to_html("小孩")?></label><a id="now_people_child_0" class="<?=$now_people_child_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1">1</a><a id="now_people_child_1" class="<?=$now_people_child_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="2">2</a><a id="now_people_child_2" class="<?=$now_people_child_2_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="3">3</a>*/?>
            
            
            <?php #echo tep_draw_input_en_num_field('now_people_man','', ' id="now_people_man" class="text_fb_bt" style="width:80px;"') . db_to_html("人");
			echo tep_draw_pull_down_menu('now_people_man',array(array('id'=>'1','text'=>'1'),array('id'=>'2','text'=>'2'),array('id'=>'3','text'=>'3')),'1','id="now_people_man"') . db_to_html('人');
			echo tep_draw_hidden_field('now_people_woman');
			echo tep_draw_hidden_field('now_people_child');
			?><span style="padding-left:30px;"><?php echo db_to_html("期望伴友：")?></span><?php
			#echo tep_draw_input_field('hope_people_man','', ' id="hope_people_man" class="text_fb_bt" style="width:80px;"') . db_to_html("人");
			echo tep_draw_pull_down_menu('hope_people_man',array(array('id'=>'1','text'=>'1'),array('id'=>'2','text'=>'2'),array('id'=>'3','text'=>'3')),'1','id="hope_people_man"') . db_to_html('人');
			echo tep_draw_hidden_field('hope_people_woman');
			echo tep_draw_hidden_field('hope_people_child');
			?><script type="text/javascript">
			jQuery(document).ready(function(e) {
				jQuery('#now_people_man').change(function(e) {
                    var now_people = jQuery('#now_people_man').val();
					
					var hope_people = jQuery('#hope_people_man');
					hope_people.empty();
					var tmp = '';
					for( var i=1, len = 4 - now_people; i <= len; i++) {
						tmp += '<option value="' + i + '">' + i + '</option>';
					}
					hope_people.append(tmp);
                });	
			});
			</script>
			</td></tr>
            <?php /*原来的期望伴友 功能很好 不建议删除
			<tr><td>
			<span><?php echo db_to_html("期望伴友：")?></span></td><td>

			<?php
			if(!tep_not_null($hope_people_man)){$hope_people_man ="0"; }
			if(!tep_not_null($hope_people_woman)){$hope_people_woman ="0"; }
			if(!tep_not_null($hope_people_child)){$hope_people_child ="0"; }
			echo tep_draw_hidden_field('hope_people_man');
			echo tep_draw_hidden_field('hope_people_woman');
			echo tep_draw_hidden_field('hope_people_child');
			switch($hope_people_man){
				case '0': $hope_people_man_0_class = $hope_people_man_1_class = $hope_people_man_2_class = "a_sex_fav"; break;
				case '1': $hope_people_man_0_class = "a_sex_del"; $hope_people_man_1_class = $hope_people_man_2_class = "a_sex_fav"; break;
				case '2': $hope_people_man_1_class = "a_sex_del"; $hope_people_man_0_class = $hope_people_man_2_class = "a_sex_fav"; break;
				case '3': $hope_people_man_2_class = "a_sex_del"; $hope_people_man_1_class = $hope_people_man_2_class = "a_sex_fav"; break;
			}
			switch($hope_people_woman){
				case '0': $hope_people_woman_0_class = $hope_people_woman_1_class = $hope_people_woman_2_class = "a_sex_fav"; break;
				case '1': $hope_people_woman_0_class = "a_sex_del"; $hope_people_woman_1_class = $hope_people_woman_2_class = "a_sex_fav"; break;
				case '2': $hope_people_woman_1_class = "a_sex_del"; $hope_people_woman_0_class = $hope_people_woman_2_class = "a_sex_fav"; break;
				case '3': $hope_people_woman_2_class = "a_sex_del"; $hope_people_woman_1_class = $hope_people_woman_2_class = "a_sex_fav"; break;
			}
			switch($hope_people_child){
				case '0': $hope_people_child_0_class = $hope_people_child_1_class = $hope_people_child_2_class = "a_sex_fav"; break;
				case '1': $hope_people_child_0_class = "a_sex_del"; $hope_people_child_1_class = $hope_people_child_2_class = "a_sex_fav"; break;
				case '2': $hope_people_child_1_class = "a_sex_del"; $hope_people_child_0_class = $hope_people_child_2_class = "a_sex_fav"; break;
				case '3': $hope_people_child_2_class = "a_sex_del"; $hope_people_child_1_class = $hope_people_child_2_class = "a_sex_fav"; break;
			}
			?>
			
			<label><?php echo db_to_html("男")?></label><a id="hope_people_man_0" class="<?=$hope_people_man_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1">1</a><a id="hope_people_man_1" class="<?=$hope_people_man_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="2">2</a><a id="hope_people_man_2" class="<?=$hope_people_man_2_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="3">3</a><label class="m_l"><?php echo db_to_html("女")?></label><a id="hope_people_woman_0" class="<?=$hope_people_woman_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1">1</a><a id="hope_people_woman_1" class="<?=$hope_people_woman_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="2">2</a><a id="hope_people_woman_2" class="<?=$hope_people_woman_2_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="3">3</a><label class="m_l"><?php echo db_to_html("小孩")?></label><a id="hope_people_child_0" class="<?=$hope_people_child_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1">1</a><a id="hope_people_child_1" class="<?=$hope_people_child_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="2">2</a><a  id="hope_people_child_2" class="<?=$hope_people_child_2_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="3">3</a>
			</td></tr>
             */ ?>
			<tr><td></td><td>
			<?php
			if(!isset($open_ended)){
				$open_ended = "1";
			}
			echo tep_draw_checkbox_field('open_ended', '1');
			?>
			<span class="more_jb"><?php echo db_to_html(" 欢迎更多人申请结伴。")?></span>
				
				<!--<a href="javascript:void(0)" style="position: relative;" class="tipslayer sp3"><img src="image/icons/question.gif" style="z-index:6" /><span><?php echo db_to_html("勾选后，当申请人数超过期望人数，“申请结伴”不会失效，仍然可以申请。")?></span></a>-->
				</td></tr>
				<tr class="p_sty1" id="my_plane" style="display:none">
					<td><span><?php echo db_to_html('行程计划：'); ?></span></td>
					<td>
						<?php echo tep_draw_textarea_field('personal_plan', 'soft', '', '','',' title="'.db_to_html('请输入你的行程计划').'" class="textarea_fb_bt" id="personal_plan" '); 
						?>
					</td>
				</tr>
			 <tr style="display:none"><td>
			<?php
			if(!tep_not_null($who_payment)){ $who_payment ="0";	}
			echo tep_draw_hidden_field('who_payment','0');
			
			switch($who_payment){
				case '0': $who_payment_0_class = "a_sex_del";  $who_payment_1_class = "a_sex_fav"; break;
				case '1': $who_payment_0_class = "a_sex_fav";  $who_payment_1_class = "a_sex_del"; break;
			}
			?>
			<span><?php echo db_to_html("支付方式：")?></span></td><td><a id="who_payment_0" class="<?=$who_payment_0_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="0"><?php echo db_to_html("AA制")?></a><a id="who_payment_1" class="<?=$who_payment_1_class?>" href="javascript:void(0)" onclick="set_hidden_field_val(this);" title="1"><?php echo db_to_html("我支付")?></a>
			</td></tr>
			</table>
		</div>
	<div class="jb_fb_tc_bt">
		<h3><?php echo db_to_html("我的基本信息")?></h3><label><?= db_to_html("（以下信息非必填）");?></label>
		</div>

	<div class="jb_fb_tc_tab">
		
		<table>
			<tr><td><span><?php echo db_to_html("姓名：")?></span></td><td><label><?php echo tep_draw_input_field('customers_name',db_to_html($customer_first_name),'  class="text_fb_bt" style="width: 160px;" title="'.db_to_html('请输入姓名').'"') ?></label></td></tr>
			<tr>
				<td><span><?php echo db_to_html('性别：')?></span></td>
				<td>
	<?php
	$t_gender = tep_customer_gender($customer_id);
	if($t_gender=='m'){ $t_gender = '1';}
	if($t_gender=='f'){ $t_gender = '2';}
	echo tep_draw_radio_field('t_gender', '1','',' title="'.db_to_html('请选择您的性别').'"').db_to_html(' 男');
	echo '&nbsp;&nbsp;';
	echo tep_draw_radio_field('t_gender', '2','',' title="'.db_to_html('请选择您的性别').'"').db_to_html(' 女');
	//echo '&nbsp;&nbsp;';
	//echo tep_draw_radio_field('t_gender', '0','','class="required" title="'.db_to_html('请选择您的性别').'"').db_to_html(' 保密');
	?>
				</td>
			</tr>
		<?php if(!(int)$customer_id){//no login
		$mail_notes = "请输入您登录走四方网的账号";
		?>

			<tr><td><span><?php echo db_to_html('账号：')?></span></td><td>
			<?php echo tep_draw_input_field('email_address',db_to_html($mail_notes),'class="required text_fb_bt" style="width: 160px; color:#bbbbbb" title="'.db_to_html($mail_notes).'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ') ; ?>
			<?php
			echo tep_draw_checkbox_field('not_show_email', '1','style="display:none"');
			echo db_to_html(' <label>保密</label> 勾选后不公开邮箱地址');
			?>
			

			</td></tr>
			<tr>
				<td><span><?php echo db_to_html('密码：')?></span></td>
				<td><input name="password" type="password" class="required text_fb_bt" id="password" title="<?php echo db_to_html('请输入正确的密码')?>" style="width: 160px;" /> <?php echo db_to_html('新用户请 <a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');?></td>
			</tr>
		<?php
		}else{ //loging
			if(!tep_not_null($email_address)){
				$email_address = $customer_email_address;
			}
		?>
		  <tr>
			<td><span><?php echo db_to_html('邮箱：')?></span></td>
			<td align="left" valign="top"><?php echo tep_draw_input_field('email_address','',' class="required text_fb_bt" style="width: 160px;" title="'.db_to_html('请输入您的电子邮箱').'"'); ?>
			<?php
			$not_show_email = tep_get_customers_show_email($customer_id);
			//echo tep_draw_checkbox_field('not_show_email', '1');
			//echo db_to_html(' <label>保密</label> 勾选后不公开邮箱地址');
			?>
			</td>
		  </tr>
		<?php
		}
		?>
			<tr><td><span><?php echo db_to_html("电话：")?></span></td><td>
			<?php echo tep_draw_input_field('customers_phone','','  class="text_fb_bt" style="width: 160px;" ') ?>
			
			<?php
			$not_show_phone = tep_get_customers_show_phone($customer_id);
			//echo tep_draw_checkbox_field('not_show_phone', '1');
			//echo db_to_html(' <label>保密</label> 勾选后不公开电话号码');
			?>
			
			</td></tr>
			<tr><td><span><?php echo db_to_html("个人介绍：")?></span></td><td>
			<?php
			$title_and_value = '请输入你的兴趣爱好或对结伴同游者的期望';
			if(!tep_not_null($personal_introduction)){
				$personal_introduction = db_to_html($title_and_value);
			}
			echo tep_draw_textarea_field('personal_introduction', 'soft', '', '','',' title="'.db_to_html($title_and_value).'" class="textarea_fb_bt" id="personal_introduction" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');
			?>

			</td>
			</tr>
            <!--积分置顶选择隐藏 by _Afei-->
            <!--
		  <tr>
			<td colspan="2">
			<span><label><?php echo tep_draw_checkbox_field('set_top_box','',false,' onClick="not_set_top(this)" ').' '.db_to_html('设置结伴帖置顶显示')?></label></span>
			
			<?php
			echo '<span id="set_top_radio" style="display:none;">';
			echo db_to_html('(将扣除50积分/天) ');
			echo tep_draw_radio_field('t_top_day', '1','','class="" ').db_to_html(' 1天');
			echo '&nbsp;&nbsp;';
			echo tep_draw_radio_field('t_top_day', '2','','class="" ').db_to_html(' 2天');
			echo '&nbsp;&nbsp;';
			echo tep_draw_radio_field('t_top_day', '3','','class="" ').db_to_html(' 3天');
			echo '</span>';
			?>
			</td>
		  </tr>
          -->
          <tr><td colspan="2" align="left" style="color:red"><?php echo db_to_html('温馨提示：发布成功后请注意登录您的帐户，在【我的结伴同游】-【我收到的信息】中查看回复信息。');?></td></tr>
			<tr><td colspan="2" align="center"><button type="submit" class="jb_fb_all" id="submit_button"><?php echo db_to_html("发布")?></button></td></tr>
			</table>
		</div>

</div>

</form>

