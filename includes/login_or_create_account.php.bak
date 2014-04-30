<?php
if($content=='create_account'){
	$radio_c_display = ' ';
	$radio_c_checked = ' checked="checked" ';
	$radio_l_display = ' display:none; ';
	$radio_l_checked = ' ';
}else{
	$radio_c_display = ' display:none; ';
	$radio_c_checked = ' ';
	$radio_l_display = ' ';
	$radio_l_checked = ' checked="checked" ';
}
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<?php
//login account

?>
<tr><td align="center"><table width="82%" border="0" cellspacing="0" cellpadding="0"><tr><td align="left" style="font-size:14px;">
<label for="radio_l"><input name="radio_l_c" type="radio" id="radio_l" onclick="sel_log_cre()" value="0" <?php echo $radio_l_checked;?> />
<b><?php echo db_to_html('已有帐号')?></b></label>
</td></tr></table></td></tr>

<tr>
	<td align="left">
		<div id="login_box" class="infoBox_outer" style="width:82%;  <?php echo $radio_l_display;?>">
			<table border="0" width="100%" align="center" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>" class="infoBox_new">
			<tr><td style="padding:10px;" class="denglu_bg">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="main" align="center">

				<?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process&version=old', 'SSL')); ?>
				<table border="0" width="100%"   cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">


				<?php
				if ($messageStack->size('login') > 0) {
				?>
				<tr>
				<td align="left">
				<?php echo $messageStack->output('login'); 	?>
				</td>
				</tr>


				<?php
				}

				/*取消购物车提示
				if ($cart->count_contents() > 0) {
				?>
					<tr>
					<td class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
					</tr>
					<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
					</tr>
				<?php
				}
				?>
				<?php
				if (PWA_ON == 'false') {

				require(DIR_FS_INCLUDES . FILENAME_PWA_ACC_LOGIN);
				} else {
				require(DIR_FS_INCLUDES . FILENAME_PWA_PWA_LOGIN);
				}*/
				?>
				<tr>
				<td align="left">

					<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%" id="AutoNumber1">

					  <tr>
						<td class="main" style="padding-left:10px;">

					<table border="0" cellspacing="0" cellpadding="0" align="left">
					  <tr>
						<td align="right" class="create_rows" height="30"><?php echo db_to_html("账号:") ?></td>
						<td>						
						<?php $value=db_to_html("请输入您的注册邮箱"); 
						echo tep_draw_input_field(
						'email_address',
						$value,
						' class="sign_in_box" onkeydown="this.style.color=\'#111\'" onBlur="if(this.value==\'\'){this.value=\''.$value.'\';this.style.color=\'#ccc\'}" onfocus="if(this.value!=\''.$value.'\'){this.style.color=\'#111\'}else{this.value=\'\';this.style.color=\'#111\'}"   style="color: #ccc;"') ?><span class="inputRequirement">*</span><span class="create_default" id="email_confirmphone_login"><?php echo db_to_html('请使用您的注册邮箱登录')?></span></td>
                                                
					  </tr>
					  <tr>
						<td align="right" class="create_rows" height="30"><?php echo ENTRY_PASSWORD ?></td>
                                                <td><?php echo tep_draw_password_field('password','',' class="sign_in_box"')?></td>
					  </tr>
					  <tr>
					    <td class="main" height="30">&nbsp;</td>
					    <td><span class="smalltext"><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '" style="text-decoration:underline;">' . TEXT_PASSWORD_FORGOTTEN . '</a>' ?></span></td>
					    </tr>
					  <tr>
					    <td class="main" height="30">&nbsp;</td>
					    <td><span class="smalltext"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) ?></span></td>
					    </tr>
					</table>
					    </td>
					  </tr>
					</table>
				</td>
				</tr>
				</table></form>


				</td>
			  </tr>
			</table>

			</td>
			</tr>
			</table>

		</div>
	</td>
</tr>

<?php
//login account end
?>

<tr><td>&nbsp;</td></tr>

<?php
//create account
?>

<tr><td align="center"><table width="82%" border="0" cellspacing="0" cellpadding="0"><tr><td align="left" style="font-size:14px;">
<label for="radio_c"><input name="radio_l_c" type="radio" id="radio_c" onclick="sel_log_cre()" value="1" <?php echo $radio_c_checked;?> />
  <b><?php echo db_to_html('注册新帐号')?></b> <span style="color:#747474; font-size:12px;"><?php echo db_to_html('注册即得'.NEW_SIGNUP_POINT_AMOUNT.'积分！积分可当现金消费');?></span></label>
  </td>
</tr></table></td></tr>
<tr>
        <td align="left" >
        
		  <div id="create_box" class="infoBox_outer" style="width:82%;  <?php echo $radio_c_display;?> "> <?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'id="create_account" onSubmit="check_form(\'create_account\'); return false;"') . tep_draw_hidden_field('action', 'process'); //onSubmit="return check_form(create_account);" ?>

        <?php
		if(date('Ymd')<=20101231){
			echo db_to_html('<div style=" margin:0 auto; width:578px; font-size: 12px; padding: 8px; color: rgb(51, 51, 51); background: none repeat scroll 0% 0% rgb(255, 252, 235); border: 1px dotted rgb(238, 217, 124);"><p style="text-indent:24px;">凡在<b><font color="#b94708">12/01/2010--12/31/2010</font></b>期间注册的客户，有机会获赠由走四方网自行设计的精美周历一份。周历内附赠优惠券4张，您可以免费体验走四方网上的部分产品。周历包含美国东西两岸大部分景点及加拿大、南美部分景点的简单介绍和精美图片，可为您的出行提供参考和帮助。</p>
<p style="text-indent:24px;">礼品数量有限，送完为止。请尽快注册，因为投递需要时间，活动仅限国内注册用户。</p>
<p style="margin-top:8px;"><b style="line-height:18px;"><font color="#b94708">注册时请在姓名后面加星号（*）表示您同意接收来自走四方网的赠品。注册后请到个人帐户中，我的订单-&gt;查看或更改联系资料一栏里填写详细地址以确保您能收到赠品。</font></b></p></div>');
		}
		?>
			<table border="0" width="100%" align="center" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>" class="infoBox_new">
			<tr><td style="padding:10px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
              <tr>
                <td class="smallText"><?php echo TEXT_ORIGIN_LOGIN; //sprintf(, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
          </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
          </tr>
              <?php
  if ($messageStack->size('create_account') > 0) {
?>
              <tr>
                <td align="left"><?php echo $messageStack->output('create_account'); ?></td>
          </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
              <?php
  }
?>

              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="table_block">
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0">

                      <tr>
                        <td class="create_rows"><?php echo ENTRY_FIRST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('firstname','','id="firstname" class="required validate-length-firstname" onBlur="check_field(\'create_account\',\'firstname\', \'chk-firstname\');"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?> <span id="chk-firstname" class="create_default"><?php echo ENTRY_FIRST_NAME_ERROR?></span></td>
                    </tr>

                      <?php if (FULL_ADDRESS_AND_POSTCODE == 'true') {?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_LAST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('lastname','','id="lastname" class="required validate-length-lastname" title="'.ENTRY_LAST_NAME_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <?php }?>


                      <?php
  if (ACCOUNT_GENDER == 'true') {
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_GENDER; ?></td>
                    <td class="main"><?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <?php
  }
?>

                      <?php
  if (ACCOUNT_DOB == 'true') {
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('dob') . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <?php
  }
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                    <td class="main"><?php //echo tep_draw_input_field('email_address','','id="create_email_address" class="required validate-email" onblur="alert(\'hi\')" title="'.ENTRY_EMAIL_ADDRESS_CHECK_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?>

                      <input name="email_address" autocomplete="off"
				id="create_email_address" class="required validate-email" value="<?php echo $email_address ?>" type="text" style="ime-mode:disabled" onblur="check_field('create_account','email_address', 'chk-email_address'); " />&nbsp;<?php echo (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': '')?> <span id="chk-email_address" class="create_default"><?php echo ENTRY_EMAIL_ADDRESS_NOTE_DEFAULT?></span></td>
                    </tr>

                      <script type="text/javascript">

function check_account(email_address){
/*
	if(email_address!="" && email_address.search(/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/)!=-1){
		var url = url_ssl("check_new_account_ajax.php?email_address=") + email_address;
		ajax.open('GET', url, true);
		ajax.send(null);

		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4 && ajax.status == 200 ) {
				if(ajax.responseText.search(/2/)!=-1){

					var error_msn = "<?=ENTRY_EMAIL_ADDRESS_ERROR_EXISTS?>";
					if(document.getElementById("chk-email_address")){
						document.getElementById("chk-email_address").innerHTML=error_msn;
						document.getElementById("chk-email_address").className="validation-advice";
						document.getElementById("chk-email_address").style.display ="";
					}else{
						alert(email_address + error_msn);
					}

				}
			}

		}



	}
*/
}

function check_yanzhengma(yanzhengma,telephone){
/*
	var url="check_new_account_ajax.php?yanzhengma="+yanzhengma+"&telephone="+telephone;
	ajax.open('GET',url,true);
	ajax.send(null);
	ajax.onreadystatechange = function(){
			if (ajax.readyState == 4 && ajax.status == 200 ) {
				if(ajax.responseText.search(/2/)!=-1){

                                        var error_msn = "<?php echo db_to_html('验证码错误')?>";
					if(document.getElementById("chk-yanzhengma")){
						document.getElementById("chk-yanzhengma").innerHTML=error_msn;
						document.getElementById("chk-yanzhengma").className="validation-advice";
						document.getElementById("chk-yanzhengma").style.display ="";
					}else{
                        alert(yanzhengma + error_msn);
					}

				}
			}

	}
*/
}

function check_phone(cpunc_phone){
/*
	var msg_check = document.getElementById("check_div");
	var url = url_ssl("check_new_account_ajax.php?cpunc_phone=") + cpunc_phone;
		ajax.open('GET', url, true);
		ajax.send(null);
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 4 && ajax.status == 200 ) {
				msg_check.innerHTML = ajax.responseText;

			}
		}
*/
}
</script>

                      <?php
  if (ACCOUNT_EMAIL_CONFIRMATION == 'true') {
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_CONFIRM_EMAIL_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('c_email_address','','id="c_email_address" class="required validate-email-confirm" autocomplete="off" onpaste="return false" onBlur="check_field(\'create_account\',\'c_email_address\', \'chk-c_email_address\'); "') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?> <span id="chk-c_email_address" class="create_default">&nbsp;</span></td>
                    </tr>
                      <?php
  }
?>


                    </table></td>
              </tr>
                </table></td>
          </tr>

              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="table_block" >
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_PASSWORD; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password','','id="create_password" class="required validate-length-current-password" title="'.ENTRY_PASSWORD_ERROR.'" onBlur="check_field(\'create_account\',\'password\', \'chk-password\');"') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?> <span id="chk-password" class="create_default"><?php echo ENTRY_PASSWORD_ERROR?></span></td>
                    </tr>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('confirmation','','id="confirmation" class="required validate-password-confirm"  onBlur="check_field(\'create_account\',\'confirmation\', \'chk-confirmation\');"') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?> <span id="chk-confirmation" class="create_default">&nbsp;</span></td>
                    </tr>

					<?php
					if($Old_Customer_Testimonials_Action==true){
						$label_old_customer_email_address_style = '';
						$Old_Customer_Radio_Check0 = ' ';
						$Old_Customer_Radio_Check1 = ' checked="checked" ';

						if($Old_Customer_Testimonials!='1'){
							$label_old_customer_email_address_style = 'style="display:none"';
							$old_customer_email_address = '';
							$Old_Customer_Radio_Check0 = ' checked="checked" ';
							$Old_Customer_Radio_Check1 = ' ';
						}
					?>
					  <tr>
                        <td class="create_rows"><?php echo db_to_html('老客户推荐:')?></td>
                        <td class="main">
						<label><input name="Old_Customer_Testimonials" onClick="jQuery('#label_old_customer_email_address').fadeIn('slow')" type="radio" value="1" <?=$Old_Customer_Radio_Check1?> />&nbsp;<?php echo db_to_html('是')?></label>&nbsp;&nbsp;
						<label><input name="Old_Customer_Testimonials" onClick="jQuery('#label_old_customer_email_address').fadeOut('slow')" type="radio" value="0" <?=$Old_Customer_Radio_Check0?> />&nbsp;<?php echo db_to_html('否')?></label>&nbsp;&nbsp;
						<label id="label_old_customer_email_address" <?=$label_old_customer_email_address_style?>><?php echo db_to_html('推荐人注册邮箱:').tep_draw_input_field('old_customer_email_address','',' style="ime-mode: disabled;" autocomplete="off" ');?></label>
						</td>
                      </tr>
					<?php
					}
					?>
					  <tr>
                        <td class="create_rows"><?php echo db_to_html('验证码:')?></td>
                        <td class="main">
						<label><?php  echo tep_draw_input_field('visual_verify_code','',' class="required" style="width:80px;ime-mode: disabled;" autocomplete="off" ');?></label>
						<img src=""  onclick="updateVVC()" align="absmiddle" id="vvc" width="66" height="22"  alt="<?php echo db_to_html('看不清?点击换一张图。')?>" title="<?php echo db_to_html('看不清?点击换一张图。')?>"/><span><span class="inputRequirement">*</span><span id="chk-visual_verify_code" class="create_default"><?php echo db_to_html('请输入图片中显示的字符，不区分大小写。')?></span>
						</td>
                      </tr>

                    <?php if(0){?>
					  <tr>
                        <td class="create_rows"><?php echo db_to_html('活动通知:'); ?></td>
						<td class="main"><?php echo tep_draw_radio_field('customers_notice','1').'&nbsp';echo db_to_html('是').'&nbsp;&nbsp;&nbsp;';echo tep_draw_radio_field('customers_notice','0',true).'&nbsp';echo db_to_html('否')?></td>
                      </tr>
					<?php }?>
                    </table></td>
              </tr>
                </table></td>
          </tr>

              <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
              <tr>
                <td class="main"><b><?php echo CATEGORY_COMPANY; ?></b></td>
          </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" >
                  <tr class="infoBoxContents_new">
                    <td><table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_COMPANY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
                    <td class="main">&nbsp;</td>
                      </tr>
                    </table></td>
              </tr>
                </table></td>
          </tr>
              <?php
  }
?>

              <?php /* howard close
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_ADDRESS; ?></b></td>
      </tr>
*/?>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="table_block" style="border:none" >
                  <tr>
                    <td><table border="0" cellspacing="0" cellpadding="0">

                      <tr>
                        <td class="create_rows"><?php echo ENTRY_COUNTRY; ?></td>
                    <td class="main" nowrap="nowrap">
                      <?php
				if (FULL_ADDRESS_AND_POSTCODE == 'true') {
					echo (tep_get_country_list('country','',' id="country" class="required"  onchange=" check_field(\'create_account\',\'country\', \'chk-country\'); get_CountryTelCode(\'create_account\',this.value); get_state(this.value,create_account,\'state\');" ')) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': '');
				}else{
					echo (tep_get_country_list('country','',' id="country" class="required" onchange=" check_field(\'create_account\',\'country\', \'chk-country\'); get_CountryTelCode(\'create_account\',this.value)"')) . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': '');
				}
				?>				 <span id="chk-country" class="create_default">&nbsp;</span></td>
                    </tr>


                      <?php
  if (FULL_ADDRESS_AND_POSTCODE == 'true') {
  if (ACCOUNT_STATE == 'true') {
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_STATE; ?></td>
                    <td class="main" id="state_td">
                      <?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_array[] = array('id' => '', 'text' => 's');
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_code");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array,'id="state" class="validate-selection" title="'.ENTRY_STATE_ERROR_SELECT.'"');
      } else {
        echo tep_draw_input_field('state','','id="state" class="required validate-length-state" title="'.ENTRY_STATE_ERROR.'"');
      }
    } else {
      echo tep_draw_input_field('state','','id="state" class="required validate-length-state" title="'.ENTRY_STATE_ERROR.'"');
    }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT. '</span>';
?>                </td>
                    </tr>
                      <?php
  }
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_CITY; ?></td>
                    <td class="main" id="city_td"><?php echo tep_draw_input_field('city','','id="city" class="required validate-length-city" title="'.ENTRY_CITY_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <?php
  if (ACCOUNT_SUBURB == 'true') {
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_SUBURB; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <?php
  }
}
?>

                      <?php
  if (FULL_ADDRESS_AND_POSTCODE == 'true') {
?>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('street_address','','id="street_address" class="required validate-length-street" title="'.ENTRY_STREET_ADDRESS_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_POST_CODE; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('postcode','','id="postcode" class="required validate-length-postcode" title="'.ENTRY_POST_CODE_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
                    </tr>
                      <?php
  }
?>

                      <tr>
                        <td class="main">&nbsp;</td>
                    <td class="create_default">
                      <?php echo ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE;?>&nbsp;

					  <?php /*?>
					  <?php echo tep_draw_radio_field('phone_type', '1', '', ' id="phone_type_1" style="border:none"').' <label for="phone_type_1">'.OFFICE_PHONE.'</label>';?>
                      <?php echo tep_draw_radio_field('phone_type', '2', '', ' id="phone_type_0" style="border:none"').' <label for="phone_type_0">'.MOBILE_PHONE.'</label>';?>
                      <?php echo tep_draw_radio_field('phone_type', '3', '', ' id="phone_type_2" class="validate-one-required" style="border:none"').'  <label for="phone_type_2">'.HOME_PHONE.'</label>';?>
					  <?php */?>					  </td>
                    </tr>
                      <tr>
                        <td class="create_rows"><?php echo ENTRY_TELEPHONE_NUMBER_ON_CREATE_ACCOUNT; ?></td>
                    <td class="main">
                      <?php
					if(isset($_POST['telephone'])){
					   $telephone = tep_db_prepare_input($_POST['telephone']);
					   $telephonearray[1] =  $telephone;
					}
				 echo tep_draw_input_field('telephone_cc',$telephone_cc,'size=4 class="required" style="ime-mode:disabled" ').' - '.tep_draw_input_field('telephone',$telephonearray[1],'id="telephone" class="required validate-number validate-length-telephone" style="ime-mode:disabled" title="'.ENTRY_TELEPHONE_NUMBER_ERROR.'" onBlur="check_field(\'create_account\',\'telephone\', \'chk-telephone\');"') . '&nbsp;' .'<span class="inputRequirement">*</span>'; ?> <span id="chk-telephone" class="create_default"><?php echo db_to_html('如果您填写的是座机号码，请在前面添加区号')?></span></td>
                    </tr>
                      <!--
				 <tr>
                <td class="main"><?php echo ENTRY_CELLPHONE_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('cellphone'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
                <td class="main">
				<?php
				if(isset($_POST['fax'])){
				   $fax = tep_db_prepare_input($_POST['fax']);
				}
				echo ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE.tep_draw_input_field('fax_cc',$fax_cc,'size=4').' - '.tep_draw_input_field('fax') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': '');
				?>
				</td>
              </tr>
			  -->


                      </table>
                      <table border="0" cellspacing="0" cellpadding="0">
                      <tr><td id="check_div"></td></tr>
                      </table>
			    <table border="0" cellspacing="0" cellpadding="0">
			      <tr>
			        <!--<td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
                    <td class="main"><?php echo tep_draw_checkbox_field('newsletter', '1',true) . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?></td>-->

				<td class="main" style="padding-left:70px;">
				  &nbsp;&nbsp;<?php echo db_to_html('我已阅读并同意'); ?>
				  <a href="<?php echo tep_href_link('customer-agreement.php')?>" target="_blank"><?php echo db_to_html('走四方客户协议'); ?></a>				</td>
                    <td class="main">&nbsp;<?php echo tep_draw_checkbox_field('read_agreement', '1',true) . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?> <span id="chk-read_agreement" class="create_default">&nbsp;</span></td>
                  </tr>
			      </table>
			    <table border="0" width="100%" cellspacing="0" cellpadding="2">
			      <tr>
			        <td class="create_rows">&nbsp;</td>
                    <td height="50"><?php echo tep_template_image_submit('button_reg.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;<span id="submit_msn" style="color:#FF6600; display:<?php echo 'none';?>"><?php echo db_to_html("正在提交您的注册数据...");?></span></td>
                    </tr>
			      <tr>
			        <td class="create_rows">&nbsp;</td>
			        <td class="create_default"><?php echo ENTRY_NEWSLETTER; ?> <?php echo tep_draw_checkbox_field('newsletter', '1',true) . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?></td>
			        </tr>
			      </table>			    </td>
              </tr>
                </table></td>
          </tr>

              <?php /* howard close
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_PASSWORD; ?></b></td>
      </tr>
*/?>



              <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
              </table>
            </td></tr>
			</table>
			</form>


  <?php
//如果必须填写详细地址信息才显示这些JS代码
if(FULL_ADDRESS_AND_POSTCODE == 'true'){
	$p=array('/&amp;/','/&quot;/');
	$r=array('&','"');
?>

  <script type="text/javascript">
 
function get_state(country_id,form_name,state_obj_name){
	var form = form_name;
	var state = form.elements[state_obj_name];
	var country_id = parseInt(country_id);
	if(country_id<1){
		alert('<?php echo ENTRY_COUNTRY.ENTRY_COUNTRY_ERROR ?>');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_ajax.php', 'country_id='))?>") +country_id;
	ajax.open('GET', url, true);
	ajax.send(null);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200 ) {
			document.getElementById('state_td').innerHTML = ajax.responseText;
			document.getElementById('city_td').innerHTML ='<?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>';
		}
	}


}
function get_city(state_name,form_name,city_obj_name){
	var form = form_name;
	var city = form.elements[city_obj_name];
	var state_name = state_name;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('get_state_list_ajax.php')) ?>");
			var aparams=new Array();
			for(i=0; i<form.length; i++){
				var sparam=encodeURIComponent(form.elements[i].name);
				sparam+="=";
				sparam+=encodeURIComponent(form.elements[i].value);
				aparams.push(sparam);
			}
			var post_str=aparams.join("&");
			ajax.open("POST", url, true);
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send(post_str);
			ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200 ) {
			document.getElementById('city_td').innerHTML =ajax.responseText;
		}
	}
}

/*取得默认省份列表*/
get_state(<?php echo ($country) ? $country : '223'; ?>,document.getElementById('create_account'),"state");
</script>

  <?php
}
//如果必须填写详细地址信息才显示这些JS代码 end

?>
  <script type="text/javascript"><!--
	//自动取得当前国家代码 the funciton on create_account.js.php
	get_CountryTelCode('create_account',document.create_account.elements['country'].value);

  function updateVVC(){
	 	 var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT)) ?>");     
	  	jQuery.get(url,{"action":"updateVVC",'random':Math.random()},function(data){
	                 jQuery("#vvc").attr('src', data); 
	        });
	 }
	 updateVVC();
//--></script>
</div></td>
</tr>

<?php
//end account
?>
</table>