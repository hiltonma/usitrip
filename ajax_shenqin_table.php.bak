  <?php
  //收集个人信息
	$tca_cn_name = $tca_en_name = $tca_gender = $tca_email_address = $tca_phone = '';
	if((int)$customer_id){
		$customer_sql = tep_db_query('SELECT customers_gender, customers_firstname, customers_lastname, customers_email_address,customers_telephone,customers_mobile_phone,customers_cellphone FROM `customers` WHERE customers_id ="'.(int)$customer_id.'" ');
		$customer_row = tep_db_fetch_array($customer_sql);
		$tca_cn_name = db_to_html(tep_db_output($customer_row['customers_firstname']));
		$tca_en_name = db_to_html(tep_db_output($customer_row['customers_lastname']));
		$tca_gender = tep_db_output($customer_row['customers_gender']);
		if(strtolower($tca_gender)=='f'){ $tca_gender="2";}
		if(strtolower($tca_gender)=='m'){ $tca_gender="1";}
		$tca_email_address = tep_db_output($customer_row['customers_email_address']);
		$tca_phone = db_to_html(tep_db_output($customer_row['customers_cellphone']));
		if(!tep_not_null($tca_phone)){ $tca_phone = db_to_html(tep_db_output($customer_row['customers_mobile_phone']));}
		if(!tep_not_null($tca_phone)){ $tca_phone = db_to_html(tep_db_output($customer_row['customers_telephone']));}
		
	}
  ?>
  <form method="post" enctype="multipart/form-data" id="travel_companion_app_form" onsubmit="submit_travel_companion_app(); return false;">
  <input name="t_companion_id" type="hidden" value="<?= $rows['t_companion_id']?>" />
  <div>
     <div class="jb_fb_tc_bt">
       <h3><?php echo db_to_html('我的基本信息')?></h3>&nbsp;&nbsp;
	   <?php
	   if((int)$customer_id){
	   ?>
	   <a href="javascript:void(0);" onclick="display_filed('travel_companion_app_form','display_filed','show');" class="jb_fb_tc_bt_a"><?php echo db_to_html('可修改')?></a>
	   <?php
	   }
	   ?>
	   <button type="button" title="<?php echo db_to_html('关闭');?>" onclick="closeDiv('travel_companion_tips_2064')" class="icon_fb_bt"/></button>
    </div>
     <div class="jb_fb_tc_tab">
       <table style="padding:0 16px;clear:both;">
        <tr><td><span><?php echo db_to_html('姓名：')?></span></td><td>
		<?php
		if(tep_not_null($tca_cn_name)){
			echo tep_draw_input_field('tca_cn_name','','class="display_filed" style="font-size:14px; display:none;"');
		}else{
			echo tep_draw_input_field('tca_cn_name','','class="display_filed" style="font-size:14px;"');
		}
		?>
		
		<label id="tca_cn_name_label"><?= $tca_cn_name?></label></td><td><?php echo db_to_html('请采用真实姓名，将用于填写下单信息。')?></td></tr>
        <tr><td><span><?php echo db_to_html('英文名：')?></span></td><td>
		<?php
		if(tep_not_null($tca_en_name)){
			echo tep_draw_input_num_en_field('tca_en_name','','class="display_filed" style="font-size:14px; display:none;"');
		}else{
			echo tep_draw_input_num_en_field('tca_en_name','','class="display_filed" style="font-size:14px;"');
		}
		?>
		
		<label id="tca_en_name_label"><?= $tca_en_name?></label></td><td><?php echo db_to_html('请采用与护照一致的姓名，将用于填写下单信息。')?></td></tr>
        <tr><td><span><?php echo db_to_html('性别：')?></span></td><td>
		<?php
		$options = array();
		$options[] = array('id'=>1,'text'=>db_to_html('男'));
		$options[] = array('id'=>2,'text'=>db_to_html('女'));
		if(tep_not_null($tca_gender)){
			echo tep_draw_pull_down_menu('tca_gender',$options,$tca_gender,'class="display_filed" style="font-size:14px; display:none;"');
			
		}else{
			echo tep_draw_pull_down_menu('tca_gender',$options,$tca_gender,'class="display_filed" style="font-size:14px;"');
		}
		$tca_gender_str = '';
		if($tca_gender=='1'){ $tca_gender_str = db_to_html('男'); }
		if($tca_gender=='2'){ $tca_gender_str = db_to_html('女'); }
		?>
		<label id="tca_gender_label"><?= $tca_gender_str?></label></td><td></td></tr>

        <tr><td><span><?php echo db_to_html('邮箱：')?></span></td><td>
		<?php
		if(tep_not_null($tca_email_address)){
			$email_address = $tca_email_address;
			echo tep_draw_input_num_en_field('email_address','','class="display_filed" style="font-size:14px; display:none;"');
		}else{
			echo tep_draw_input_num_en_field('email_address','','class="display_filed" style="font-size:14px;"');
		}
		?>
		<label id="email_address_label"><?= $tca_email_address?></label></td><td>&nbsp;</td></tr>
		<?php
		if(!(int)$customer_id){
		?>
		<tr><td><span><?php echo db_to_html('密码：')?></span></td>
		<td><input name="password" type="password" style="font-size:14px;" id="password" title="<?php echo db_to_html('请输入正确的密码')?>" /></td></tr>
		<?php
		}
		?>

        <tr><td><span><?php echo db_to_html('电话：')?></span></td><td>
		<?php
		if(tep_not_null($tca_phone)){
			echo tep_draw_input_num_en_field('tca_phone','','class="display_filed" style="font-size:14px; display:none;"');
		}else{
			echo tep_draw_input_num_en_field('tca_phone','','class="display_filed" style="font-size:14px;"');
		}
		?>
		<label id="tca_phone_label"><?= $tca_phone?></label></td><td><?php echo db_to_html('非必填')?></td></tr>
        <tr><td><span><?php echo db_to_html('人数：')?></span></td><td><?php echo tep_draw_input_num_en_field('tca_people_num','','style="font-size:14px;" size="6" '); ?> </td><td><?php echo db_to_html('请输入预计结伴人数。')?></td></tr>

        <tr><td><span><?php echo db_to_html('留言：')?></span></td><td colspan="2"><?php echo tep_draw_textarea_field('tca_content','',30,5,db_to_html('请输入你的兴趣爱好或对结伴同游者的期望'),'title="'.db_to_html('请输入你的兴趣爱好或对结伴同游者的期望').'" class="textarea_fb_bt" style="font-size:14px;" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');?><br />
          <!--<p class="in_text">你还可以输入<span>100</span>字</p>--></td></tr>
          
          <tr><td colspan="3" align="center"><button type="submit" class="jb_fb_all" ><?php echo db_to_html('发布')?></button></td></tr>
          
       </table>
     </div>
</div>
</form>
