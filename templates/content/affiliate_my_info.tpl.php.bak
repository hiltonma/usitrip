<?php
ob_start();
// howard added display affiliate_my_info msn
if ($messageStack->size('affiliate_my_info') > 0){
	$classTip = "successTip";
	if($messageStack->messages[0]['type']=="error") $classTip = "errorTip";
?>
	<div id="msn_affiliate_my_info" class="msg">
	<span class="<?= $classTip?>">
	<?php echo $messageStack->output('affiliate_my_info','text'); ?>
	</span>
	</div>
	<script type="text/javascript">
	jQuery('#msn_affiliate_my_info').fadeOut(2100);
	</script>
<?php
}
// howard added display affiliate_my_info end
?>
<div class="mainbox">
<?php 
if(!(int)$affiliate_verified || $_GET['action']=='edit'){
//未通过验证或编辑页面 start {
?>
<div class="unionAccount">
<form action="" method="post" enctype="multipart/form-data" name="affiliateForm" id="affiliateForm" onsubmit="return false;">
        <h2>基本信息<span><a href="<?= tep_href_link("account_edit.php");?>">[修改个人基本信息]</a></span></h2>
        <ul>
              <li><label>中文姓名：</label>
			  <?php
			  echo tep_db_output($affiliate_firstname); 
			  echo tep_draw_hidden_field('affiliate_firstname');
			  //tep_draw_input_field('affiliate_firstname','','class="required text" title="请输入您的中文姓名" ');?>&nbsp;</li>
              <li><label>英文名：</label>
				<?php
				echo tep_db_output($surName." ".$givenName);
				echo tep_draw_hidden_field('surName','',$surNameParameter);
				echo tep_draw_hidden_field('givenName','',$givenNameParameter);
				//tep_draw_input_num_en_field('surName','',$surNameParameter);
				//tep_draw_input_num_en_field('givenName','',$givenNameParameter);
				?>
				&nbsp;
			  </li>
              <li><label>邮箱：</label>
			  <?php
			  $EmailTipsClass = "";
			  $EmailTipsText = "请立即验证邮箱，确保能准确收到网站联盟相关信息。";
			  $verifyBtnText = "验证邮箱";
			  $verifyBtnJs = "javascript:_verifyEmail(1);";
			  $affiliateEmailAddressStyle = "";
			  $bindedEmailStyle = "display:none;";
			  if($affiliate_email_address_verified=="1"){	//已验证
				  $EmailTipsClass = "normalTip";
				  $EmailTipsText = "邮箱已通过验证";
				  $verifyBtnText = "取消验证";
				  $verifyBtnJs = "javascript:_verifyEmail(0);";
				  $affiliateEmailAddressStyle = "display:none;";
				  $bindedEmailStyle = "";
			  }elseif(tep_not_null($affiliate_email_address_verifi_code)){	//已发验证码但还没验证
				  $EmailTipsClass = "";
				  $EmailTipsText = "验证邮件已经发到您的邮箱，但您未查收。";
				  $verifyBtnText = "重发验证邮件";
				  $verifyBtnJs = "javascript:_verifyEmail(1);";
				  $affiliateEmailAddressStyle = "";
				  $bindedEmailStyle = "display:none;";
			  }
			  ?>
			  <b style="<?= $bindedEmailStyle?>" id="bindedEmail"><?= $affiliate_email_address;?></b>
			  <?php
			  echo tep_db_output($affiliate_email_address);
			  echo tep_draw_hidden_field('affiliate_email_address');
			  //tep_draw_input_num_en_field('affiliate_email_address','',' readonly="true" class="required validate-email text email" style="'.$affiliateEmailAddressStyle.'" ');
			  ?>
			  <?= tep_draw_hidden_field('old_affiliate_email_address',$affiliate_email_address,' id="old_affiliate_email_address" ');?>
			  &nbsp;
			  <?php //隐藏验证按钮?>
			  <a style="display:none" id="verifyBtn" class="btn btnGrey" href="javascript:;"><button type="button" onclick="<?= $verifyBtnJs?>"><?= $verifyBtnText?></button></a>
			  <span style="display:none" id="EmailTips" class="<?= $EmailTipsClass?>"><?= $EmailTipsText?></span>
			  
			  </li>
              <li><label>手机：</label>
			  <?php
			  echo tep_db_output($affiliate_mobile);
			  echo tep_draw_hidden_field('affiliate_mobile');
			  //echo tep_draw_input_num_en_field('affiliate_mobile','','class="required text" title="请输入您的手机号码" ');
			  ?>
			  &nbsp;
			  </li>
              <li><label>其他电话：</label>
			  <?php
			  echo $affiliate_telephone;
			  echo tep_draw_hidden_field('affiliate_telephone');
			  //echo tep_draw_input_num_en_field('affiliate_telephone','','class="text"');
			  ?>
			  &nbsp;
			  </li>
              <li><label>QQ：</label><?= tep_draw_input_num_en_field('affiliate_qq','','class="text" id="J-qq"');?><span>QQ和MSN必填一项</span></li>
              <script type="text/javascript">
			  var qqLab = document.getElementById('J-qq');
			  qqLab.onkeyup = function(){
				   //alert(this.value);
				   this.value = this.value.replace(/[^\d]/g,'');  
			  }
			  qqLab.onpaste = function(){
				   return false;  
			  }
			  </script>
              <li><label>MSN：</label><?= tep_draw_input_num_en_field('affiliate_msn','','class="text"');?></li>
        </ul>
        
        <h2>推广信息</h2>
        <ul>
              <li><label>网站名称：</label><?= tep_draw_input_field('affiliate_homepage_name','','class="text"');?>&nbsp;<i>您公司的网站名称</i></li>
              <li><label>网址：</label><?= tep_draw_input_num_en_field('affiliate_homepage','','class="text"');?>&nbsp;<i>http://</i></li>
              <li><label>网站类型：</label>
                <div class="chooseRadio">
                    <?= $siteTypeRadios;?>
                </div>
              </li>
              <li><label>网站简介：</label>
			  <?= tep_draw_textarea_field('affiliate_site_profile','wrap', 100,5,'','class="textarea"');?>
			  </li>
        </ul>
		
		<?php 
		
		if((int)$affiliate_verified){		//未申请通过不提供收款信息填写?>
		<h2>收款信息<span><a href="<?= tep_href_link("affiliate_details.php","action=edit");?>">[修改收款信息]</a></span></h2>
		<ul>
			<?php if ($affiliate_default_payment_method == 'Paypal') { ?>
			<li>
			  	<label><b>Paypal</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</li>
			<li>
				<label>收款人姓名：</label>
				<?= tep_db_output($affiliate_payment_check);?>
			  	<?= tep_draw_hidden_field('affiliate_payment_check');?>
			  &nbsp;
			</li>
            <li>
			  <label>Paypal账号：</label>
			  <?= tep_db_output($affiliate_payment_paypal);?>
			  <?= tep_draw_hidden_field('affiliate_payment_paypal');?>
			  &nbsp;
			</li>
			<?php }
			if ($affiliateInfo['affiliate_default_payment_method'] == 'Alipay') { ?>
			<li>
			  	<label><b>支付宝</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</li>
			<li>
				<label>收款人姓名：</label>
				<?= tep_db_output($affiliate_payment_alipay_name);?>
			  	<?= tep_draw_hidden_field('affiliate_payment_alipay_name');?>
			  &nbsp;
			</li>
            <li>
			  <label>支付宝账号：</label>
			  <?= tep_db_output($affiliate_payment_alipay);?>
			  <?= tep_draw_hidden_field('affiliate_payment_alipay');?>
			  &nbsp;
			</li>
			<?php }
			if ($affiliateInfo['affiliate_default_payment_method'] == 'Bank') { ?>
            <li>
			  <label><b>银行转账收款</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  </li>
			  <li>
			  <label>收款人姓名：</label>
			  <?= tep_db_output($affiliate_payment_bank_account_name);?>
			  <?= tep_draw_hidden_field('affiliate_payment_bank_account_name');?>
			  &nbsp;
			  </li>
              <li>
			  <label>开户银行：</label>
			  <?= tep_db_output($affiliate_payment_bank_name);?>
			  <?= tep_draw_hidden_field('affiliate_payment_bank_name');?>
			  </li>
              <li>
			  <label>银行账号：</label>
			  <?= tep_db_output($affiliate_payment_bank_account_number);?>
			  <?= tep_draw_hidden_field('affiliate_payment_bank_account_number');?>
			  &nbsp;
			  </li>
			  <?php 
			}
			?>
        </ul>
		<?php }else{ //申请时需要列出 请阅读并接受合作联盟协议?>
		<ul>
		<li>
		<label>&nbsp;&nbsp;</label>
		<input id="agree_terms" type="checkbox" value="1" /> 同意<a target="_blank" href="<?= tep_href_link('affiliate_agreement.php');?>">《走四方销售联盟协议》</a>
		</li>
		</ul>
		<?php }?>
		<div class="submit">
        <button type="submit" class="save_info"><?= $submitBtnText?></button>
		</div>
</form>
</div>
<script type="text/javascript">
function _submit(Obj){
	if(document.getElementById("agree_terms")!=null && jQuery("#agree_terms").attr('checked')!=true){
		alert("请阅读并接受《走四方销售联盟协议》");
		return false;
	}
	var form_id = Obj.id;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('affiliate_my_info.php','action=SubmitMyInfo')) ?>");
	_disabledAllowBtn(form_id,"disabled");
	ajax_post_submit(url,form_id);
	return false;
}

function _disabledAllowBtn(formId,action){
	var submitBtn = jQuery("#"+formId+" button[type='submit']");
	if(action=="disabled"){
		jQuery(submitBtn).html("<?= $submitBtnText1?>");
		jQuery(submitBtn).attr('disabled',true);
	}else{
		jQuery(submitBtn).html("<?= $submitBtnText?>");
		jQuery(submitBtn).attr('disabled',false);
	}
}

var CanSendVerify = 1;
function _verifyEmail(num){
	var emailBox = document.getElementById("affiliateForm").elements['affiliate_email_address'];
	if(num==1){
		if(CanSendVerify == 1){
			var confirmUp = true;
			var oldVal = jQuery("#old_affiliate_email_address").val();
			if(emailBox.value!=oldVal){
				if(confirm("“我的走四方”注册邮箱也会同步更新，确认修改？")!=true){
					confirmUp = false;
				}
			}
			if(confirmUp==true){
				var email_adderss = emailBox.value;
				var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('affiliate_my_info.php','action=verify_email')) ?>");
				url += "&email_adderss="+email_adderss;
				jQuery("#EmailTips").html('邮件发送中请稍后……');
				jQuery("#EmailTips").removeClass();
				CanSendVerify = 0;
				ajax_get_submit(url);
			}
		}else{
			alert("邮件发送中请稍后……");
		}
	}else{
		jQuery("#bindedEmail").hide(0);
		jQuery(emailBox).show(0);
		jQuery("#verifyBtn button[type='button']").html('验证邮箱');
		
		jQuery("#verifyBtn button[type='button']").attr('onclick','');
		jQuery("#verifyBtn button[type='button']").bind('click',function() {
		  _verifyEmail(1);
		});

		
		jQuery("#EmailTips").html('请立即验证邮箱，确保能准确收到网站联盟相关信息。');
		jQuery("#EmailTips").removeClass();
	}
}

function formCallback(result, form) {
	if(result==true){
		var emailBox = document.getElementById("affiliateForm").elements['affiliate_email_address'];
		var oldVal = jQuery("#old_affiliate_email_address").val();
		
		if(emailBox.value!=oldVal){
			if(confirm("您的邮箱将由"+oldVal+"变更为"+ emailBox.value +"。\t\n\n“我的走四方”注册邮箱也会同步更新，确认修改？")!=true){
				return false;
			}
		}

		_submit(document.getElementById("affiliateForm")); 
	}
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
}
var valid = new Validation('affiliateForm', {immediate : true,useTitles:true, onFormValidate : formCallback});

</script>
<?php
//未通过验证或编辑页面 end }
}else{

//已经通过申请
	if((int)$post_verification_successful){
?> 

<script language="javascript" src="includes/javascript/affiliate.js"  type="text/javascript" charset="GB2312"></script>
	<div class="pass_msg">
    	<h2>恭喜！您的审核已通过！</h2>
        <p>欢迎成为走四方销售联盟会员！马上投放广告获取收益吧！</p>
    </div>
    <div class="pass_info">
    	<h3>请核对并牢记您的账户信息</h3>
        <p>佣金账户：<?= $affiliateInfo['affiliate_email_address']?></p>
        <p>佣金账户编号：<?= $affiliateInfo['affiliate_id']?></p>
        <p>默认收款方式：<?php if($affiliateInfo['affiliate_default_payment_method']=='PaypalAlipay') echo 'Paypal或支付宝'; if($affiliateInfo['affiliate_default_payment_method']=='Bank') echo '银行转账'; ?></p>
        <?php if($affiliateInfo['affiliate_default_payment_method']=='Bank'){?>
		<p>收款人姓名：<?= $affiliateInfo['affiliate_payment_bank_account_name']?></p>
        <p>开户银行：<?= $affiliateInfo['affiliate_payment_bank_name']?></p>
        <p>银行账号：<?= $affiliateInfo['affiliate_payment_bank_account_number']?></p>
        <?php }else if($affiliateInfo['affiliate_default_payment_method']=='PaypalAlipay'){?>
		<p>收款人姓名：<?= $affiliateInfo['affiliate_payment_check']?></p>
        <p>收款账号：<?= $affiliateInfo['affiliate_payment_paypal']?></p>
        <?php }?>
		<p><a href="<?= tep_href_link('affiliate_details.php');?>" class="change_info">去修改佣金收款方式信息&gt;&gt;</a></p>
		
    </div>
    <div class="sinfo">
        <div class="sinfo_article">
            <h3 class="sinfo_title">还等什么？选择适合您的推广方式，即刻开始赚取佣金——</h3>
            <ul class="sinfo_spread sfix">
                <li class="nomarg">
                    <a href="<?= tep_href_link('affiliate_banners.php', 'tag=promocode_tag', 'NONSSL')?>" class="bg1">优惠码</a>
                    <p>任何朋友使用你的Coupon Code，他获折扣你获佣金</p>
                </li>
                <li>
                    <a href="<?= tep_href_link('affiliate_banners.php', 'tag=imagetext_tag', 'NONSSL')?>" class="bg2">文本链接</a>
                    <p>线路/活动/城市/景点，任选推广内容，清晰直观</p>
                </li>
                <li>
                    <a href="<?= tep_href_link('affiliate_banners.php', 'tag=imagetext_tag', 'NONSSL')?>" class="bg3">图片链接</a>
                    <p>在您的网站上添加各类精美广告图片，更丰富灵活</p>
                </li>
                <li>
                    <a href="<?= tep_href_link('affiliate_banners.php', 'tag=search_tag', 'NONSSL')?>" class="bg4">搜索框嵌入</a>
                    <p>网站上嵌入搜索框，直达购物页面，方便快捷</p>
                </li>
                <li>
                    <a href="<?= tep_href_link('affiliate_banners.php', 'tag=custom_tag', 'NONSSL')?>" class="bg5">自定义链接</a>
                    <p>在邮件、论坛或博客中直接留下您自定义的链接</p>
                </li>
            </ul>
        </div>
			<?php
			//热销产品推荐 start {	
			$bestSellers = $affiliate->bestSellers(4);
			if(is_array($bestSellers)){
			?>
            <div class="sinfo_article sfix nbbd">
            	<h3 class="sinfo_title">热销产品推荐</h3>
                <ul class="sinfo_pro sfix">
                	<?php
					for($i=0, $n=sizeof($bestSellers); $i<$n; $i++){
						$_class = ($i==0) ? 'nomarg' : '';
					?>
					<li class="<?= $_class;?>">
                    	<div class="sinfo_propic">
                        	<a href="<?= tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$bestSellers[$i]['products_id']);?>"><img src="<?= $bestSellers[$i]['thumbImageSrc'];?>" /></a>
                            <div class="sinfo_profilter"></div>
                            <div class="sinfo_protext"><p><?= mb_substr($bestSellers[$i]['products_name'],0,21,'gb2312');?>...</p></div>
                        </div>
                        <p><a href="<?= tep_href_link('affiliate_banners.php', 'rProductsId='.$bestSellers[$i]['products_id']);?>" class="probtn">我要推广</a><em><?php echo str_replace('.00','',$currencies->display_price($bestSellers[$i]['products_price'],tep_get_tax_rate($bestSellers[$i]['products_tax_class_id'])))?></em></p>
                    </li>
					<?php }?>
                </ul>
            </div>
			<?php
			}
			//热销产品推荐 end }
			?>
    </div>       
<?php }else{
		$editUrl = tep_href_link('affiliate_my_info.php','action=edit');
?>
<div class="unionAccount">
        <h2>基本信息<span><a href="<?= tep_href_link("account_edit.php");?>">[修改个人基本信息]</a></span></h2>
		<ul>
              <li><label>中文姓名：</label><?= $affiliateInfo['affiliate_firstname']?>&nbsp;</li>
              <li><label>英文名：</label><?= $affiliateInfo['affiliate_lastname']?>&nbsp;</li>
              <li><label>邮箱：</label><?= $affiliateInfo['affiliate_email_address']?>&nbsp;</li>
              <li><label>手机：</label><?= $affiliateInfo['affiliate_mobile']?>&nbsp;</li>
              <li><label>其他电话：</label><?= $affiliateInfo['affiliate_telephone']?>&nbsp;</li>
              <li><label>QQ：</label><?= $affiliateInfo['affiliate_qq']?>&nbsp;</li>
              <li><label>MSN：</label><?= $affiliateInfo['affiliate_msn']?>&nbsp;</li>
        </ul>
        
        <h2>推广信息<span><a href="<?= $editUrl?>">[修改推广信息]</a></span></h2>
        <ul>
              <li><label>网站名称：</label><?= $affiliateInfo['affiliate_homepage_name']?>&nbsp;</li>
              <li><label>网址：</label><?= $affiliateInfo['affiliate_homepage']?>&nbsp;</li>
              <li><label>网站类型：</label>
                <div class="chooseRadio"><?= $affiliateInfo['siteTypeString']?>&nbsp;</div>
              </li>
              <li><label>网站简介：</label><div class="briefIntro"><?= nl2br($affiliateInfo['affiliate_site_profile']);?>&nbsp;</div></li>
        </ul>
		
		<h2>收款信息<span><a href="<?= tep_href_link("affiliate_details.php","action=edit");?>">[修改收款信息]</a></span></h2>
		<ul>
              <?php if ($affiliate_default_payment_method == 'Paypal') { ?>
			  <li><label><b>Paypal</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
			  <li><label>收款人姓名：</label><?= $affiliateInfo['affiliate_payment_check']?>&nbsp;</li>
              <li><label>Paypal账号：</label><?= $affiliateInfo['affiliate_payment_paypal']?>&nbsp;</li>
			  <?php }
			if ($affiliateInfo['affiliate_default_payment_method'] == 'Alipay') { ?>
			<li><label><b>支付宝</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
			  <li><label>收款人姓名：</label><?= $affiliateInfo['affiliate_payment_alipay_name']?>&nbsp;</li>
              <li><label>支付宝账号：</label><?= $affiliateInfo['affiliate_payment_alipay']?>&nbsp;</li>
			
			<?php }
			if ($affiliateInfo['affiliate_default_payment_method'] == 'Bank') { ?> 
              <li><label><b>银行转账收款</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
			  <li>
			  <label>收款人姓名：</label>
			  <?= $affiliateInfo['affiliate_payment_bank_account_name']?>
			  &nbsp;
			  </li>
              <li>
			  <label>开户银行：</label>
			  <?= $affiliateInfo['affiliate_payment_bank_name']?>
			  &nbsp;
			  </li>
              <li>
			  <label>银行账号：</label>
			  <?= $affiliateInfo['affiliate_payment_bank_account_number']?>
			  &nbsp;
			  </li>
			  <?php
			}?>
        </ul>
		
      </div>	

<?php
	}
}
?>
</div>
<?php
echo  db_to_html(ob_get_clean());
?>