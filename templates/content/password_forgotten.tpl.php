<div class="page-location"><?php echo HEADING_TITLE; ?></div>
<?php
ob_start();
echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL'));
?>
<div class="ui-checked-tab fix">
    <ul class="ui-checked-type">
        <li class="disabled" title="暂未开通手机验证平台！"><label for="mobile"><input type="radio" name="checktype" id="mobile" disabled>手机验证</label></li>
        <li class="checked"><label for="email"><input type="radio" name="checktype" id="email" checked="checked">邮箱验证</label></li>
    </ul>
    <div class="ui-checked-wrap">
    	<!--
    	<div id="J_Mobile">
        	<i class="role m"></i>
            <div class="m-box fix">
            	<p class="tips-text">您可以通过有效的绑定手机重置登录密码。</p>
                <div class="fp_form">
                	<form>
                    	<input type="text" class="fp_ipt" placeholder="请输入您绑定的手机号码" />
                        <input type="button" class="fp_btn" value="找回密码" />
                    </form>
                </div>
            </div>
        </div>
        -->
        <div id="J_Email">
        	<i class="role e"></i>
			<div class="e-box fix">
            <div id="ErrorMsg"></div>
            	<p class="tips-text">您可以通过有效的绑定邮箱重置登录密码。</p>
                <div class="fp_form">
						<?php  echo tep_draw_hidden_field('email_sms_post',"email",'id="email_sms_post_id"');?>
						<?php echo tep_draw_input_field('email_sms_input','','class="fp_ipt" placeholder="请输入您绑定的邮箱帐号" '); ?>
                        <input type="submit" class="fp_btn" value="找回密码" />
					
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<?php echo db_to_html(ob_get_clean());?>