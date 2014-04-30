<?php 
ob_start();//print_vars($account);
$tab1style=$tab2style=$tab3style = ' style="display:none" ';
$tab1Sel= $tab2Sel=$tabSel3 = '';
if($tabId == 'change_password'){
	$tab3style = ' style="display:" ';
	$tab3Sel = ' class="sel" ';
}else if($tabId == 'upload_avatar'){
	$tab2style = ' style="display:" ';
	$tab2Sel = ' class="sel" ';
}else {
	$tab1style = ' style="display:" ';
	$tab1Sel = ' class="sel" ';
}
//英文名
$default_sur_name = '姓Surname';
$default_given_name = '名Given name';
$sur_name_style =$given_name_style ='style="color:#111"';
if(trim($account['customers_lastname']) == ''){
	$sur_name = $default_sur_name;
	$given_name = $default_given_name;
}else{
	$pos = strpos($account['customers_lastname'], ' ');
	$sur_name = tep_output_string(substr($account['customers_lastname'],0 ,$pos));
	$given_name = tep_output_string(trim(str_replace($sur_name,'',$account['customers_lastname'])));
}
if($sur_name == $default_sur_name) $sur_name_style ='style="color:#ccc"';
if($given_name == $default_given_name) $given_name_style ='style="color:#ccc"';
//print_r($account);
//性别
if($account['customers_gender'] == 'f'){
	$classf = 'gender genderSel';
	$classm = 'gender';
}elseif($account['customers_gender'] == 'm'){
	$classf = 'gender';
	$classm = 'gender genderSel';
}else{
	$classf = 'gender';
	$classm = 'gender';
}
//confirmphone
if($account['confirmphone']!=''){
	$phone_confirm = true;
	$mphone = $account['confirmphone'];
}else{
	$phone_confirm = false;
	$mphone = trim($account['customers_mobile_phone'])==''?$account['confirmphone']:$account['customers_mobile_phone'];
}
/**
 *旧账号可能出现 手机号变为 -1231232或者-的问题，暂时没找到原因，增加一个过滤
 *@author vincent 2011.11.10 {
 **/
$mphone = trim($mphone,"- \t\n\r\0\x0B");
$faxphone = array();
foreach(explode(',',$account['customers_fax']) as $fax){
	$fax1 = trim($fax,"- \t\n\r\0\x0B");
	if($fax1!='') $faxphone[] = $fax1;
}
$account['customers_fax'] = implode(",",$faxphone);
//}
?>
<div class="showRoute">
        <div class="tab">
          <a href="javascript:;"  <?php echo $tab1Sel?> onclick="jQuery('#ErrMsg').hide()">基本信息</a>
          <?php /*?><a href="javascript:;"  <?php echo $tab2Sel?>  onclick="jQuery('#ErrMsg').hide()">上传头像</a><?php */?>
          <a href="javascript:;"  <?php echo $tab3Sel?> onclick="jQuery('#ErrMsg').hide()">修改密码</a>
        </div>  
        <div id="ErrMsg"  class="msg"   style="display:none"></div> 
        <ul class="info"  <?php echo $tab1style?> >       
        <form name="editForm" action="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT,'','SSL')?>" method="post" >
            <li>
                <label>英文名：</label>
                <?php echo tep_draw_input_field('',$sur_name,'id="SurName"  class="text surName enTxt" '.$sur_name_style.' onblur="doLastname()" onfocus="focusLastname(this)" ')?>
                <?php echo tep_draw_input_field('',$given_name,'id="GivenName"  class="text givenName enTxt" '.$given_name_style.'  onblur="doLastname()" onfocus="focusLastname(this)" ')?>                
                <?php echo tep_draw_hidden_field('lastname' ,$account['customers_lastname'], 'id="customers_lastname" ' )?>
            </li>
            
<?php if(tep_not_null($pointcards_id_string)){ ?>
            <li>
                <label>会员卡号:</label>
               <?php echo $pointcards_id_string;?>
            </li>
<?php }?>
            <li>
                <label>中文名：</label>
                <?php echo tep_draw_input_field('firstname', $account['customers_firstname'],' id="firstname" class="text textNameCn required validate-length-firstname"  ','text',false); ?></span>
            </li>
            <li>
                <label>注册邮箱：</label>
                <b id="bindedEmail" style="display:none;"><?php echo $account['customers_email_address'];?></b>
                <?php                 
				echo $text.tep_draw_input_field(
                'email_address', 
                $account['customers_email_address'],
                'id="email_address"  onFocus="jQuery(\'#verify_email_warp\').fadeOut(\'slow\');"  onkeyup="emailBtn.validate()" onBlur="if(jQuery.trim(jQuery(this).val()) == \'\')this.value=\''.$account['customers_email_address'].'\';else verifyEmail()" class="text  email required validate-email enTxt" style="width:180px;display:none"','text',false
                );
                ?>
                 <a class="btn btnGrey" id="btnEmailValidated" style="display:none;" href="javascript:;" ><button   style="color:#999" type="button" >已确定</button></a> <a class="btn btnGrey" href="javascript:;"><button id="ValidateEmailBtn"  type="button" ></button></a>				
				 <span id="EmailTips" class="tips"></span>
				  <div id="ValidatingTips" style="margin-left:66px;display:none;margin-right: 180px;">没收到邮件？也许邮件被系统误判定为垃圾或广告邮件，您可以检查您的垃圾箱或广告箱，或选择 <a href="javascript:sendValidateEmail()">重发验证邮件</a>。</div>
            </li>
           <li id="verify_email_warp"  style="display:none" class="even"><label>&nbsp;</label><span id="verify_email" ></span></li>
			 <li>
                <label>手机：</label>
                <b id="bindedPhone" ><?php echo $mphone?></b>
                <?php echo tep_draw_input_field('mobile_phone', $mphone,' onchange="phoneBtn.validate();jQuery(\'#verify_phone_warp\').fadeOut(\'slow\');"  id="mobile_phone" style="ime-mode:disabled;width:125px"  class="text enTxt"','text',false); 
				if(CPUNC_SWITCH === 'true') {
				?>
                 <a class="btn btnGrey" href="javascript:;"><button id="ValidatePhoneBtn"  type="button" ></button></a>
				 <?php } ?>
				 <span id="waitingText" style="color:#000;padding-left:10px;display:none;"></span>
				 <?php if(CPUNC_SWITCH === 'true') { ?>
				 <span id="PhoneTips" class="tips"></span>
				 <?php } ?>
           </li>
		   
		   <li id="verify_phone_warp"  style="display:none" class="even"><label>&nbsp;</label><span id="verify_phone" ></span></li>

		    <li style="position:relative;overflow:visible;">
                <label>国家/地区：</label>
                
                <div id="SelectCountry" class="selectyousCountry enTxt">
				<div class="selectCountryClose"  id="SelectCountryClose"><a href="javascript:void(0);" onclick="jQuery('#SelectCountry').toggle();fixIE6SelectShow();"></a></div>
                <div id="CountrySection1"></div>
                <div id="CountrySection2"></div>
                <div id="CountrySection3"></div>
                <div class="btnCenter">
                	<a onclick="setCountry();" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg21.gif);width:127px;height:30px;font-weight:normal;" href="javascript:;" class="btn btnOrange">确	定</a>
                	<a href="javascript:;" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg21.gif);width:127px;height:30px;font-weight:normal;" onclick="jQuery('#SelectCountry').hide();fixIE6SelectShow();" class="btn btnGrey">取 消</a>
                </div>				
              </div>
			  
				<div class="choose-country enTxt" onclick="jQuery('#SelectCountry').toggle(0,function(){if(jQuery(this).is(':hidden')){fixIE6SelectShow()}else{fixIE6SelectHide()}})" id="SelectCountryBox">
				<script type="text/javascript">
				function fixIE6SelectShow(){
					//国家地区弹出框关闭时，还原#wrap,#content的overflow:hidden
					jQuery('#wrap,#content').css('overflow','hidden');				
					var ie6 = jQuery.browser.msie && (jQuery.browser.version == "6.0") && !jQuery.support.style;
					if(!ie6){return;}
					var theFix = jQuery('.showRoute .info select');
					if(theFix.length > 0){
						theFix.css('visibility','visible');
					}					
				}
				function fixIE6SelectHide(){
					//国家地区弹出框弹出时，临时解除#wrap,#content的overflow:hidden
					jQuery('#wrap,#content').css('overflow','visible');	
					var ie6 = jQuery.browser.msie && (jQuery.browser.version == "6.0") && !jQuery.support.style;
					if(!ie6){return;}
					var theFix = jQuery('.showRoute .info select');
					if(theFix.length > 0){
						theFix.css('visibility','hidden');
					}					
				}
				</script>
				<?php
			  $countrystr = $account['entry_country_name'].(trim($account['entry_zone_name'])!='' ? ' , '.$account['entry_zone_name']:'').(trim($account['entry_city'])!='' ? ' , '.$account['entry_city']:'');
			  
			  if(trim($countrystr) == '') $countrystr = '请选择';
			  echo  $countrystr
			  ?>
				</div>
              <?php echo tep_draw_hidden_field('country',$account['entry_country_id'],' id="EntiryCountry"')?>
              <?php echo tep_draw_hidden_field('zone_id',$account['entry_zone_id'],' id="EntiryZone" ')?>
              <?php echo tep_draw_hidden_field('city',$account['entry_city'],' id="EntiryCity"')?>
              <?php echo tep_draw_hidden_field('state',$account['entry_state'],' id="EntiryState" ')?>
            </li>
            
            <li>
                <label>详细地址：</label>
                 <?php echo tep_draw_input_field('street_address', $account['entry_street_address'],' style=""  class="text address"','text',false); ?>
            </li>
            
            <li>
                <label>邮编：</label>
                <?php echo tep_draw_input_field('postcode', $account['entry_postcode'],' style=""  class="text postcode enTxt"','text',false); ?>
            </li>
            
            <li>
                <label>性别：</label>
				<label for="sex1"><input type="radio" name="gender" <?php if ($classm == "gender genderSel") { echo 'checked'; }?> value="m" id="sex1"/>男</label>
				<label for="sex2"><input type="radio" name="gender" <?php if ($classf == "gender genderSel") { echo 'checked'; }?>  value="f" id="sex2"/>女</label>
                <?php /*<a href="javascript:;" class="<?php echo $classm?>">男</a><a href="javascript:;" class="<?php echo $classf?>">女</a><input type="hidden" name="gender" value="<?php echo $account['customers_gender']?>" />*/ ?><span id="verify_gender"></span>
            </li>
            
            <li>
                <label>生日：</label>
 				<?php 
				if(trim($account['customers_dob']) == '' || $account['customers_dob'] =='NULL' || $account['customers_dob']=='00-00-00') {
					$account['customers_dob'] = '0-0-0';
				}
				echo tep_draw_date_input_adv('customers_dob', $account['customers_dob'],1920,date('Y',time())) ;?>
 				<?php 
 				$radio_dob_secret_0 = false;$radio_dob_secret_1 = false;$radio_dob_secret_2 = false;
 				if($account['dob_secret'] == 0) {
 					$radio_dob_secret_0 = true;
 				}else if($account['dob_secret'] == 1) { 
 					$radio_dob_secret_1 = true ;
 				}else if($account['dob_secret'] == 2) {
 					$radio_dob_secret_2 = true ;
 				}
 				?>
 				<?php echo tep_draw_radio_field('dob_secret','0',$radio_dob_secret_0,'class="radio"')?>完全公开
 				<?php echo tep_draw_radio_field('dob_secret','1',$radio_dob_secret_1,'class="radio"')?>保密
 				<?php echo tep_draw_radio_field('dob_secret','2',$radio_dob_secret_2,'class="radio"')?>只显示月/日
            </li>
            
            <li>
                <label>其他电话：</label>
                <?php echo tep_draw_input_field('fax', $account['customers_fax'],' style="ime-mode:disabled" id="OtherTele"  onBlur="addTelCode()" class="text otherTele enTxt"','text',false); ?>
               <span class="tips other-tel-tips">请加上城市代码和区号，多个电话请用逗号隔开。如：086-028-88888888,086-0839-66666666</span> 
            </li>
            <!-- <li class="newsletter">
                <label>&nbsp;</label>
                 <?php  if($account['customers_newsletter'] == '1') echo tep_draw_checkbox_field('newsletter', '1',true);else echo tep_draw_checkbox_field('newsletter', '1',false);?> <span><a href="<?php echo tep_href_link('account_newsletters.php')?>">订阅走四方资讯邮件</a></span>        
            </li> -->
            
           <!-- <li>
                <label>推荐人：</label>
                
                <?php echo tep_draw_input_field('Old_Customer_Testimonials', '',' style="ime-mode:disabled"  class="text people"'); ?> 填写推荐人后有意外惊喜哟。
            </li> -->
            <?php echo tep_draw_hidden_field('action','process')?>
         	<div class="btnCenter" >
         	<input type="submit" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg21.gif);width:127px;height:30px;line-height:28px;color:#4d4d4d;border:0;cursor:pointer;" value="保存基本信息"/>
         	</div>
         	
         	  </form>
			  
			  <div class="user_pic">
 <?php 		
		 include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_face.php');
		get_travel_companion_face((int)$customer_id, '', '', '', true);?>
	<div id="uploadStatusBar" class="uploadProcessBarSmall"><div>&nbsp;</div></div>
	
	<script src="includes/javascript/zip_upload/jquery-jtemplates.js" type="text/javascript"></script>
    <script src="includes/javascript/zip_upload/swfobject.js" type="text/javascript"></script>
	<script type="text/javascript">
         function showFlash(){
           var vars = {
                serverUrl: "/ajax_upload_customer_face.php",
                jsUpdateInfo: "jsUpdateInfo",
                imgWidth: 500,
                imgHeight: 500,
                imgQuality: 80
            }
            var vars1 = vars;
            vars1.flashID = "divAddPhoto";
            vars1.labelColor = "#000000";
            vars1.labelText = "上传头像";
            vars1.hasUnderLine = false;
            swfobject.embedSWF("includes/javascript/zip_upload/PhotoUploader.swf", "divAddPhoto", "80", "25", "10.0.0", "includes/javascript/zip_upload/expressInstall.swf", vars1, { wmode: "Transparent" });    
        }     
		var filesize = 0;
         function jsUpdateInfo(flashID, name, status, size, message) {     
			 if(status == 'loading'){
				 if(filesize != 0){
				 percent = Math.floor(Math.ceil(1000*size/filesize)/100)/10;
					 if(jQuery("#uploadStatusBar").css('display') != 'block'){
						jQuery("#uploadStatusBar").fadeIn('flow');
					 }
					 jQuery("#uploadStatusBar div").css('width',(percent*100)+"%");
				 }
			 }else if(status == 'selected'){                 
					swfobject.getObjectById(flashID).Load(name);
					filename=name;
					filesize = size;
             }else if(status == 'uploaded'){
				 jQuery("#uploadStatusBar div").css('width',"100%");
				 jQuery("#uploadStatusBar").fadeOut('slow');
				 				  
            	 msg = message.split("|");
            	 if(msg[0] =='0'){
                	 alert(msg[1]);
            	 }else{
                	 document.getElementById('img_customers_face').src = msg[5];
                 }
             }
         }
        </script>
        <a id="ALinkAddPhoto" class="btn btnGrey" href="javascript:;" style="margin-left:12px;margin-top:5px;text-align:center;" ><div id="divAddPhoto"></div></a><p style="line-height:20px;margin-top:5px;">支持jpg,gif,png格式的图片。</p>
	</div>
        </ul>      
 <?php //上传照片表单
 /*
 ?>           
         <ul class="info"  <?php echo $tab2style?>>
         
         </ul>
 <?php 
 */
 //修改密码表单?>       
           <ul class="info infoPassword"  <?php echo $tab3style?>>
         <?php echo tep_draw_form('account_password', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', ' id="account_password" ') ;
         echo tep_draw_hidden_field('action', 'change_password'); ?>
            <li>
                <label>当前密码：</label>
                <?php echo tep_draw_password_field('password_current','','id="password_current" class="text password required"')?>
            </li>
             <li class="even">
              <label>&nbsp;</label><span id="verify_password_current" style="display:none;"></span>
            </li> 
            <li>
                <label>新密码：</label>
                <?php echo tep_draw_password_field('password_new','','id="password_new" class="text password required"   onkeyup="verifyPassword();"  onfocus="jQuery(\'#password\').addClass(\'on\');" onblur="jQuery(\'#password\').removeClass(\'on\');"')?>&nbsp;5-12个字符，可以由数字、英文、符号组成。
            </li>
             <li class="even">
              <label>&nbsp;</label><span id="verify_password_new" style="display:none;"></span>
            </li>
            <li>
                <label>确认密码：</label>
                <?php echo tep_draw_password_field('password_confirmation','','id="password_confirmation" onblur="verifyConfirmation()" class="text password " ')?>&nbsp;请再输入一次新密码。
            </li>
             <li class="even">
              <label>&nbsp;</label><span id="verify_password_confirmation" style="display:none;"></span>
            </li>
            <div class="btnCenter" >
			<input type="submit" value="提&nbsp;&nbsp;交" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg21.gif);width:127px;height:30px;font-weight:normal; border:none; cursor:pointer;"/>
			<!--<a class="btn btnOrange" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg21.gif);width:127px;height:30px;font-weight:normal;"  href="javascript:;" onclick="jQuery('#account_password').submit()">提&nbsp;&nbsp;交</a>//-->
			</div>   
			
         	  </form>
         </ul>    
		
      </div>
<script type="text/javascript">
function focusLastname(obj){
	var dv = obj.id == 'SurName'?"<?php echo $default_sur_name?>":"<?php echo $default_given_name?>";
	if(obj.value == dv){
		obj.value="";
		obj.style.color = '#111';
	}
}

function doLastname(){
	var surname = "";
	var givenname = "";
	if(jQuery("#SurName").val() == ''){
		jQuery("#SurName").val("<?php echo $default_sur_name?>");
		jQuery("#SurName").css('color','#ccc');	
	}else if(jQuery("#SurName").val() == "<?php echo $default_sur_name?>"){	
		jQuery("#SurName").css('color','#ccc');
	}else{
		surname =jQuery("#SurName").val();
		jQuery("#SurName").css('color','#111');
	}

	if(jQuery("#GivenName").val() == ''){
		jQuery("#GivenName").val("<?php echo $default_given_name?>");
		jQuery("#GivenName").css('color','#ccc');	
	}else if(jQuery("#GivenName").val() == "<?php echo $default_given_name?>"){
		jQuery("#GivenName").css('color','#ccc');	
	}else{
		givenname =jQuery("#GivenName").val();
		jQuery("#GivenName").css('color','#111');		
	}
	jQuery("#customers_lastname").val(surname+" "+givenname );
}
function validateResult(msg){
    this.code = -1;
    this.rawmsg = msg;
    this.message = "null" ;
    if(typeof(msg) == "string"){
      var sepPos = msg.indexOf(",");
      this.code = parseInt(msg.substring(0,sepPos),10);
      this.message = msg.substring(sepPos+1,msg.length);
    }
   this.isSuccess = function(){return this.code == 0 ;}     
   this.toString=function(){return "CODE:"+this.code.toString()+" MESSAGE:"+this.message ; }      
}

function submitEditForm(){
	document.editForm.submit();
}
var v_email_warp  =jQuery('#verify_email_warp') ;
var v_email_content  = jQuery('#verify_email');
var  email_btn = jQuery('#ValidateEmailBtn');
var  email_input = jQuery('#email_address');
var  email_hidden = jQuery('#bindedEmail');
function EmailBtn() {
	this.str_validated = "邮箱已通过验证。";
	this.str_validating="验证邮件已发送,请注意查收！";
	this.str_validate = '请立即验证邮箱，确保能成功收到参团凭证、发票等走四方网的相关信息。';
	this.validate     = function(){jQuery("#btnEmailValidated").hide();this.hideEmailTips();email_hidden.hide();email_input.show();v_email_warp.hide();email_btn.unbind('click');	email_btn.html("验证邮箱");email_btn.bind("click",function(){sendValidateEmail();});}
	this.validating = function(){jQuery("#btnEmailValidated").hide();this.hideEmailTips();email_hidden.hide();email_input.show();v_email_warp.hide();email_btn.unbind('click');	email_btn.html("验证中"); jQuery('#ValidatingTips').show();}
	this.validated  = function(){this.hideEmailTips();jQuery("#btnEmailValidated").show();email_hidden.html(email_input.val());email_hidden.show();email_input.hide();v_email_warp.hide();email_hidden.show();email_input.hide();email_btn.unbind('click');email_btn.html("取消验证"); email_btn.bind("click",function(){if(confirm('确定要取消邮箱验证吗？')){unbindEmail();}});}
	this.setTips      = function(type,msg){jQuery("#EmailTips").hide().removeClass().addClass("tips").addClass(type+"Tip").html(msg).fadeIn("slow");}
	this.clearTips  = function(){	jQuery("#EmailTips").hide().html("");}
	this.hideEmailTips = function (){jQuery("#EmailTips").hide();jQuery("#ValidatingTips").hide();}
	this.showError = function(msg){v_email_warp.hide();v_email_content.html('<span class="errorTip">'+msg+'</span>');v_email_warp.fadeIn('slow')}
	this.hideError = function(msg){v_email_warp.fadeOut('slow');}
}
var v_p_tips = jQuery("#PhoneTips"),v_p_input = jQuery("#mobile_phone"),v_p_bindedText = jQuery("#bindedPhone");
var v_p_btn = jQuery("#ValidatePhoneBtn"),v_p_content  = jQuery('#verify_phone'),waitingText = jQuery('#waitingText');
var v_p_warp  = jQuery('#verify_phone_warp');
function PhoneBtn (){
	this.str_validated = "手机已绑定。";
	this.str_validate_code = '验证码:<?php  echo tep_draw_input_field('','','class="text" id="validate_code"')?><a  id="GetPasswordBtn" class="btn btnGrey" href="javascript:;"  onclick="checkSmsValidateCode()"><button type="button">验证</button></a>';
	this.str_validating="验证码已发送!如果30秒后您的手机还未收到请点击\"重发验证码\"。";
	this.str_sending="正在发送验证码。";
	this.str_validate = '绑定手机完全免费，确保能成功收到走四方网的相关短信和用手机号登陆等。';
	this.setTips      = function(type,msg){v_p_tips.hide().removeClass().addClass("tips").addClass(type+"Tip").html(msg).fadeIn("slow");}
	this.clearTips  = function(){	v_p_tips.hide().html("");}
	this.showError = function(msg){v_p_warp.hide();v_p_content.html('<span class="errorTip">'+msg+'</span>');v_p_warp.fadeIn('slow')}
	this.hideError = function(msg){v_p_warp.fadeOut('slow');}
	this.validated     = function(){
		v_p_bindedText.html(v_p_input.val());
		v_p_input.hide();v_p_bindedText.show();v_p_btn.html("取消绑定");
		v_p_btn.unbind('click'); v_p_btn.bind("click",function(){if(confirm('确定要取消绑定吗？\n取消之后您将不能使用该手机号登录。')){unbindPhone();}});
		this.setTips('normal',this.str_validated);
		v_p_bindedText.slideDown();
	}
	this.validating = function(){
		v_p_input.show();v_p_bindedText.hide();
		this.setTips('normal',this.str_sending);			
		v_p_btn.unbind('click');
		v_p_content.html(this.str_validate_code);
		v_p_btn.html("请稍候");	
	}
	this.validate = function(){
		v_p_bindedText.hide();v_p_input.show();
		v_p_btn.html("绑定手机");this.setTips('',this.str_validate);		
		v_p_btn.unbind('click');
		v_p_btn.bind("click",function(){if(jQuery.trim(v_p_input.val()) == ''){v_p_input.focus();return false;}phoneBtn.validating();sendValidateSMS();});
	}
}

var emailBtn = new EmailBtn();
<?php if($account['customers_validation'] == 1){?>
emailBtn.validated();emailBtn.setTips('normal' ,emailBtn.str_validated);
/*已经验证邮箱则隐藏邮箱验证提示*/
//emailBtn.clearTips();
<?php }else if($account['customers_validation_code'] != ''){?>
emailBtn.validating();emailBtn.setTips('success',emailBtn.str_validating);
<?php }else{?>
emailBtn.validate();emailBtn.setTips('' ,emailBtn.str_validate);
<?php } ?>
var phoneBtn = new PhoneBtn();
<?php 
if($phone_confirm == true){
	echo 'phoneBtn.validated();';
	//echo 'phoneBtn.clearTips();';
}else{
	echo 'phoneBtn.validate();';
}
?>
function unbindPhone(){
	isPhoneBindOK = false;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>");    
	jQuery.get(url,{"action":'unbind_phone'},
             function(data){
            	 result = new validateResult(data); 
            	 if(result.isSuccess()){
            		 phoneBtn.validate();
            		 phoneBtn.setTips('' ,phoneBtn.str_validate);
            	 }else{
            		 phoneBtn.setTips('error',result.message); 
            	 }
  }	);
}
function unbindEmail(){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>");    
	jQuery.get(url,{"action":'unbind_email'},
             function(data){
            	 result = new validateResult(data); 
            	 if(result.isSuccess()){
            		 emailBtn.validate();
            		 emailBtn.setTips('' ,emailBtn.str_validate);
            	 }else{
            		 emailBtn.validated();
            		 emailBtn.setTips('error',result.message);
            	 }
  }	);
}

function verifyEmail(){
	var _action = true;
	var oldEmail = jQuery("#bindedEmail").text();
	var email_address = jQuery.trim(email_input.val());
	var affiliateVerified = "<?= $affiliate_verified?>";
	if(oldEmail!=email_address && affiliateVerified =="1" ){
		if(confirm("“联盟账号信息”中的邮箱也会同步更新，确认修改？")!=true){
			_action = false;
			email_input.val(oldEmail);
		}
	}
	
	if(_action==false){
		return false;
	}
	
	v_email_warp.hide();
	if(email_address==''){
		email_input.val('');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>");    
	 jQuery.get(url,{"action":"validate_email","email":email_address}
				,function(data){
					result = new validateResult(data);   
					if(result.code==999){
						emailBtn.validate();
						emailBtn.setTips('',emailBtn.str_validate);		
					} else if(result.code == 0 ){
						emailBtn.validate();
						emailBtn.setTips('',emailBtn.str_validate);						
					}else{   
						//emailBtn.validate();
						emailBtn.setTips('',emailBtn.str_validate);			
						emailBtn.showError(result.message);
					}
			});
}

function sendValidateEmail(){	
	var email_address = jQuery("#email_address").val();
	emailBtn.validating();
	emailBtn.setTips("normal","正在发送验证邮件，请稍候！");
	jQuery("#email_address").attr('readonly',true);
	jQuery("#email_address").css('color','#ccc');
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>");    
	jQuery.get(url ,{"action":"send_validate_email","email":email_address}
            ,function(data){
                var result = new validateResult(data);
               if(result.isSuccess()){
            	   emailBtn.validating();
            	   emailBtn.setTips('success',result.message);          
				   auto_send_session_mail();
               }else{
            	   emailBtn.validate();
            	   emailBtn.setTips('',emailBtn.str_validate);
            	   emailBtn.showError(result.message);
               }
			  jQuery("#email_address").removeAttr('readonly');
			  jQuery("#email_address").css('color','');
            }
   );
}
//验证手机号码
function verifyMobilePhone(){
	var phoneNumber = jQuery.trim(v_p_input.val());
	if(phoneNumber == '' || phoneNumber.length <6){		
		phoneBtn.showError('请输入有效的手机号码。');
		//jQuery('#validate_phone_warp').fadeIn('slow');这个元素存在吗?		
	}else{		
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>");    
		 jQuery.get(url,{"action":"validate_phone","phone":phoneNumber}
					,function(data){						
						result = new validateResult(data);   
						if(result.code==999){
						} else if(result.code == 0 ){
							phoneBtn.validate();
							phoneBtn.setTips('',phoneBtn.str_validate);
						}else{        				
							phoneBtn.showError(result.message);
						}
				});
	}
}


//等待手机验证码倒计时
var isPhoneBindOK = false;
function waiting_countdown(str){		
	var T = 30;
	var time_s;	
	if(!isPhoneBindOK){
		waitingText.show();
		v_p_input.attr('readonly','readonly');
		v_p_input.css('color','#ccc');
	}else{
		return;
	}
	var setcountdown = function(){
		if(isPhoneBindOK){
			waitingText.html('').hide();clearInterval(time_s);
			v_p_input.removeAttr('readonly');
			v_p_input.css('color','#000');
			return;
		}
		T--;
		if(T==0){			
			waitingText.html('').hide();clearInterval(time_s);
			v_p_btn.html('重发验证码');v_p_btn.bind('click',sendValidateSMS);
			v_p_input.removeAttr('readonly');
			v_p_input.css('color','#000');
		}		
		else{waitingText.html('还剩'+T+'秒可重新发送验证码。');}
	};	
	
	time_s = setInterval(setcountdown,1000);
	
}

function sendValidateSMS(){
	var phoneNumber = jQuery.trim(jQuery("#mobile_phone").val());		
	v_p_btn.unbind('click');
	v_p_btn.html("请稍后");		
	v_p_warp.hide();	
	 phoneBtn.setTips('normal' , phoneBtn.str_sending);	 
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>"); 
	jQuery.get(url,{"action":"send_validate_sms","phone":phoneNumber},function(data){
				result = new validateResult(data);
                if(result.isSuccess()){
					waiting_countdown();//启动倒计时
                    phoneBtn.setTips('' , phoneBtn.str_validating);
                	v_p_warp.fadeIn('slow'); 	
                	//v_p_btn.clearQueue().unbind('click').delay(30000).queue(function(){v_p_btn.html('重发验证码');v_p_btn.bind('click',sendValidateSMS)});
				}else{
					phoneBtn.validate();
					phoneBtn.showError(result.message);
					//v_p_warp.clearQueue().delay(10000).fadeOut("slow");
				}
			}
	);
}

function checkSmsValidateCode(){
	var phoneNumber = jQuery("#mobile_phone").val();
	var validateCode = jQuery("#validate_code").val();
	if(validateCode.length != 4) {		
		v_p_content.html('<span class="errorTip">请输入您手机收到的验证码！</span>').clearQueue().delay(1000).queue(function(){v_p_content.html(phoneBtn.str_validate_code);});
		return ;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')) ?>"); 
	jQuery.get(url,
			{"action":"check_sms_validate_code","phone":phoneNumber,"code":validateCode},function(data){
				result = new validateResult(data);
                if(result.isSuccess()){
                	v_p_content.clearQueue();
                	v_p_btn.clearQueue();
                	phoneBtn.setTips('right',"绑定成功!"); 
 					v_p_warp.fadeOut('slow');
 					phoneBtn.validated();
					isPhoneBindOK = true;					
				}else{
					v_p_content.html('<span class="errorTip">'+result.message+'</span>').clearQueue().delay(1000).queue(function(){v_p_content.html(phoneBtn.str_validate_code);v_p_content.clearQueue();});
				}
			}
			);
}

function verifyPassword(){     
	verified_password = false;
	var verify = jQuery('#verify_password_new') ;
	
	var password = document.getElementById('account_password').password_new.value;
	if(password.length < 5) return ;
	if(password.length < 5 || password.length > 12){
		verify.html('<span class="errorTip">请输入5-12个字符的密码。</span>');
    	verify.fadeIn("slow");
    	return false;
	}
	if(password.match(/^[0-9]{5,12}$/)){
		verify.html('<span class="normalTip">密码安全度：<b class="on">弱</b><b>中</b><b>强</b></span>');
		verify.show();
	}else if(password.match(/^[a-zA-Z0-9]{5,12}$/)){
		verify.html('<span class="normalTip">密码安全度：<b>弱</b><b  class="on">中</b><b>强</b></span>');
		verify.show();
	}else if(password.match(/^[a-zA-Z0-9@\?-_\$%&=\*\)\(]{5,12}$/)){
		verify.html('<span class="normalTip">密码安全度：<b>弱</b><b >中</b><b  class="on">强</b></span>');
		verify.show();
	}else{
		verify.html('<span class="normalTip">密码安全度：<b>弱</b><b  class="on">中</b><b >强</b></span>');
		verify.show();
	}
	verified_password = true;
}

function verifyConfirmation(){ 
	var password_new = document.getElementById('account_password').password_new.value;
	var password_confirmation = document.getElementById('account_password').password_confirmation.value;
    if(password_new == "") {
    	var verify = jQuery('#verify_password_new') ;
    	verify.html('<span class="alertTip">请输入5-12个字符的密码。</span>');
    	verify.fadeIn("slow");
    	return ;
    }
	verified_confirmation = false
	var verify = jQuery('#verify_password_confirmation') ;
    if(password_new == password_confirmation){
    	verify.html('<span class="rightTip">两次密码输入一致。</span>');
    	verify.fadeIn("slow");
    	verified_confirmation = true;
    }else {
    	verify.html('<span class="errorTip">两次密码输入不一致。</span>');
    	verify.fadeIn("slow");
    }
}

jQuery(".showRoute .tab a").click(function (){  
    jQuery(".showRoute .tab a").removeClass();   
    jQuery(this).addClass("sel");   
    jQuery(".showRoute .info").hide();   
    jQuery(".showRoute .info:eq("+jQuery(".showRoute .tab a").index(this)+")").show();      
});

jQuery(".gender").click(function (){  
	if(jQuery(this).html() == "男"){
		jQuery("input[name=gender]").val('m');
	}else jQuery("input[name=gender]").val('f');
    jQuery(".gender").removeClass("genderSel");   
    jQuery(this).addClass("genderSel"); 
});
<?php 
//提取国家
$countryJson = array();
$countryQuery   = tep_db_query("select countries_id, countries_name,countries_tel_code from " . TABLE_COUNTRIES . " WHERE 1 order by countries_name ASC");
while($row = tep_db_fetch_array($countryQuery)){
	$countryJson[] = '{id:"'.$row['countries_id'].'",name:"'.format_for_js($row['countries_name']).'",pinyin:"'.format_for_js($row['countries_name']).'",telcode:"'.format_for_js($row['countries_tel_code']).'"}';
}
echo "var countryJson = [".implode(",\n",$countryJson)."];\n";
?>
var maxLengthPerChar = 12;
function ArrayLink(){
	this.data = [];
	this.add = function(k,v){
		var pushed = false ;
		k = k.toUpperCase();
		addMulti = v.constructor == Array? true:false;
		for(var i=0;i<this.data.length;i++){
			if(k == this.data[i].key){
				if(addMulti){
					for(var kk=0;kk<v.length;kk++)this.data[i].value.push(v[kk]);
				}else{
					this.data[i].value.push(v);
				}
				pushed = true;
			break;}
		}
		if(pushed == false){
			var tmp = new Array();
			if(addMulti){
				for(var kk=0;kk<v.length;kk++)tmp.push(v[kk]);
			}else{
				tmp.push(v);
			}
			this.data.push({"key":k,"value":tmp});
		}
	}
	this.get = function(k){
		k = k.toUpperCase();
		for(var i=0;i<this.data.length;i++){
			if(k == this.data[i].key){return this.data[i].value;}
		}
		return null;
	}
	this.getArray = function(){
		return this.data;
	}
	this.toString=function(){
		var str ='';
		for(var i=0;i<this.data.length;i++){
			str+= this.data[i].key+"["+this.data[i].value.length+"] ,";
		}
		return str;
	}
}
function groupJson(src){
	var tmp = new ArrayLink();	
	for(var i=0;i < src.length;i++){		
		if(jQuery.trim(src[i].pinyin) == '' || src[i].pinyin=='NULL'){
			chr = src[i].name.substr(0,1);
		}else
			chr = src[i].pinyin.substr(0,1);
		tmp.add(chr,src[i]);
	}
	var result = new ArrayLink();
	var tmp3 = tmp.getArray();
	var start = -1;
	var currentSize = 0;
	for(var i=0;i<tmp3.length;i++){
		if(currentSize + tmp3[i].value.length  >  maxLengthPerChar){
				if(start == -1){
					result.add(tmp3[i].key,tmp3[i].value);
				}else{					
					var chr = start < i-1 ? tmp3[start].key+"-"+tmp3[i-1].key : tmp3[start].key;
					for(var jj=start;jj<i;jj++){result.add(chr,tmp3[jj].value);}
					start = i ;
					currentSize = tmp3[i].value.length;
				}
		}else {
			if(start == -1) start = i ;
			currentSize = currentSize + tmp3[i].value.length;
		}
	}
	if(start != -1){
		var chr = start < i-1 ? tmp3[start].key+"-"+tmp3[i-1].key : tmp3[start].key;
		for(var jj=start;jj<i;jj++){result.add(chr,tmp3[jj].value);}
	}
		
	
	return result;
}

function findIndex(classname , prefix){
	var nameArr = classname.split(" ");
	var myid = 0;
	for(var i=0;i<nameArr.length;i++){
		var idx = nameArr[i].indexOf(prefix);
		if(idx != -1){
			myid = nameArr[i].substr(prefix.length,nameArr[i].length-1);
			break;
		}
	}
	return myid;
}

function switchCountryTab(obj){
	jQuery(obj).parent().find("a").removeClass('selCountry');
	jQuery(obj).addClass("selCountry");	
	var myid = findIndex(jQuery(obj).attr('class') ,'countryTab_');
	var obj2 =jQuery(obj).parent().next();
	obj2.find("ul").hide();
	obj2.find(".countryCards_"+myid).show();
}
//var m = new ArrayLink();m.add('A',[{'id':1},{'id':9}]);m.add('A',{'id':2});m.add('B',{'id':1});m.add('C',{'id':1});m.add('B',{'id':5});alert(m);
//new country ui 
function makeSectionHtml(data , sectionid,title,clickfunc){	
	var result = groupJson(data),tabarr=[],ularr=[],liarr=[];
	arr = result.getArray();
	for(var i=0;i<arr.length;i++){		
		liarr=[];for(var kk=0 ;kk<arr[i].value.length;kk++)liarr.push('<li><input telcode="'+arr[i].value[kk].telcode+'"  txtname="'+arr[i].value[kk].name+'" type="radio" name="tmp_'+sectionid+'" onclick="'+clickfunc+'" value="'+arr[i].value[kk].id+'"/>'+arr[i].value[kk].name+'</li>');
		tabarr.push('<a href="javascript:;"   class="countryTab countryTab_'+i+'" onclick="switchCountryTab(this)">'+arr[i].key+'</a>');
		ularr.push('<ul style="display:none"  class="countryCards countryCards_'+i+'">'+liarr.join("")+"</ul>");
	}
	var html ='<h4>'+title+'<span>(按名称开头字母查看)</span></h4><div class="showCountry"><div class="tabCountry" id="CountryTab'+sectionid+'">'+ tabarr.join("")+'</div>';
	html+='<div id="sinfoCountry" class="infoCountry" >'+ularr.join("")+'<div class="clearBoth"></div></div>';
	html+='</div>';	
	jQuery('#SelectCountry').css('z-index','1000');
	/*jQuery('#SelectCountryClose').css('z-index','1001');*/
   return html;
}
function section_country(){
	jQuery("#CountrySection1").html(makeSectionHtml(countryJson,1,'请选择您居住的国家','section_state(this.value)'));
	jQuery("#CountrySection2").hide();jQuery("#CountrySection3").hide();
	jQuery("#CountrySection1").show();
	switchFirstTab(1);
}
function section_state(countryId ){
	jQuery("#CountrySection2").hide();jQuery("#CountrySection3").hide();
	var url = url_ssl("<?php echo tep_href_link('account_edit_ajax.php')?>");
	jQuery.get(url,{"action":"get_states_json","countryId":countryId},function(data){
		eval("var d1="+data);
		if(d1.length > 0) jQuery("#CountrySection2").show();
		jQuery("#CountrySection2").html(makeSectionHtml(d1,2,'请选择您居住的洲/省','section_city(this.value)'));
		switchFirstTab(2);
	});
}
function section_city(stateId ){
	jQuery("#CountrySection3").hide();
	var url = url_ssl("<?php echo tep_href_link('account_edit_ajax.php')?>");
	jQuery.get(url,{"action":"get_cities_json","zone_id":stateId},function(data){
		eval("var d1="+data);
		if(d1.length > 0) jQuery("#CountrySection3").show();
		jQuery("#CountrySection3").html(makeSectionHtml(d1,3,'请选择您居住的城市',''));
		switchFirstTab(3);
	});
}
function switchFirstTab(sectionId){
	jQuery("#CountrySection"+sectionId+" .countryTab").removeClass("selCountry");
	jQuery("#CountrySection"+sectionId+" .countryTab_0").addClass("selCountry");	
	jQuery("#CountrySection"+sectionId+" .countryCards").hide();
	jQuery("#CountrySection"+sectionId+" .countryCards_0").show();
}
section_country();

var telCode = '<?php echo $account['entry_full_telcode']?>';

function setCountry(){
	var str = [],telcode=[];
	jQuery("#EntiryCity").val("");jQuery("#EntiryState").val("");jQuery("#EntiryZone").val("");jQuery("#EntiryCountry").val("");
	jQuery("#CountrySection1 :radio").each(function(i,obj){
		if(jQuery(obj).attr('checked') == true){
			str.push(jQuery(obj).attr("txtname"));
			if(jQuery.trim(jQuery(obj).attr("telcode"))!="") telcode.push(jQuery(obj).attr("telcode")+"-");
			jQuery("#EntiryCountry").val(jQuery(obj).attr('value'));
		}
	});

	jQuery("#CountrySection2 :radio").each(function(i,obj){
		if(jQuery(obj).attr('checked') == true){
			str.push(jQuery(obj).attr("txtname"));
			jQuery("#EntiryZone").val(jQuery(obj).attr('value'));
			jQuery("#EntiryState").val(jQuery(obj).attr("txtname"));
		}
	});

	jQuery("#CountrySection3 :radio").each(function(i,obj){
		if(jQuery(obj).attr('checked') == true){
			str.push(jQuery(obj).attr("txtname"));
			if(jQuery.trim(jQuery(obj).attr("telcode"))!="") telcode.push(jQuery(obj).attr("telcode")+"-");
			jQuery("#EntiryCity").val(jQuery(obj).attr('txtname'));
		}
	});
	
	if(str == ""){
		alert("请选择 国家/地区 。");				
		return;
	}else{		
		telCode = telcode.join('');	
		jQuery("#SelectCountry").hide();
		addTelCode();
		fixIE6SelectShow();
	}
	jQuery('#SelectCountryBox').html(str.join("&nbsp;,&nbsp;"));
}

function addTelCode(){
	var tt = jQuery.trim(jQuery('#OtherTele').val()) ;	
	if(tt=="" || tt.substr(tt.length-1,1) == '-' ){
		jQuery('#OtherTele').val(telCode);
		return ;
	}
	var othertels = tt.split(",");
	var newOtherTel = [];
	for(var i=0;i<othertels.length;i++){
		tel = jQuery.trim(othertels[i]);
		if(tel!=""){
			var idx = tel.lastIndexOf("-");
			if(idx==-1){
				newOtherTel.push(telCode+tel);
			}else{
				newOtherTel.push(tel);
			}
		}
	}
	jQuery('#OtherTele').val(newOtherTel.join(","));	
}
/*
function checkRadioBox(sectionId , dv){
	var checked = false;
	jQuery("#CountrySection"+sectionId+" :radio").each(function(index,obj){
		if(jQuery(obj).val() == dv ){
			jQuery(obj).attr('checked',true);
			var myid = findIndex(jQuery(obj).parent().parent().attr("class"),"countryCards_");		
			jQuery("#CountrySection"+sectionId+" .countryTab").removeClass("selCountry");
			jQuery("#CountrySection"+sectionId+" .countryTab_"+myid).addClass("selCountry");	
			jQuery("#CountrySection"+sectionId+" .countryCards").hide();
			jQuery("#CountrySection"+sectionId+" .countryCards_"+myid).show();
			checked = true ;
			jQuery(obj).trigger('click');
		}else{
			jQuery(obj).removeAttr('checked');
		}
	});
	if(checked == false){
		switchFirstTab(sectionId);
	}
}*/

</script>
<?php echo db_to_html(ob_get_clean());?>
<?php if($messageStack->size('account_edit') > 0 ){
	echo $messageStack->output_newstyle('account_edit','ErrMsg');
	echo '<script type="text/javascript">jQuery("#ErrMsg").delay(5000).fadeOut("slow");</script>';
}?>