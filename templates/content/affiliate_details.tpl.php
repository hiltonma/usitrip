<div class="myReward">
<?php
if ($messageStack->size('affiliate_details') > 0) {
?>
	<div id="msn_affiliate_details" class="msg">
		<span class="successTip">
	<?php echo $messageStack->output('affiliate_details','text'); ?>
	</span>
	</div>
	<script type="text/javascript">
	jQuery('#msn_affiliate_details').fadeOut(15000);
	</script>

<?php	
}
?>
<?php ob_start();?>


        <h2>
		接收佣金的方式<a href="javascript:void(0);" onclick="_showForm(1)">修改</a>
	</h2>
    <?php //echo PAGE_HEADING_SUB_EDIT;?>
		<?php echo tep_draw_form('affiliate_details', tep_href_link(FILENAME_AFFILIATE_DETAILS, '', 'SSL'), 'post', 'id="affiliate_details_form" onsubmit="return checksubmit()"') . tep_draw_hidden_field('action', 'process'); ?>

		<ul class="method" id="MethodChoose">
		<li><?= tep_draw_radio_field('affiliate_default_payment_method','Paypal', '','onClick="_checkMethod(this.value);"').$_str_paypal;?></li>
		<li><?= tep_draw_radio_field('affiliate_default_payment_method','Bank', '','onClick="_checkMethod(this.value);"').$_str_bank;?></li>
		<li><?= tep_draw_radio_field('affiliate_default_payment_method','Alipay', '','onClick="_checkMethod(this.value);"').$_str_alipay;?></li>
	</ul>

	<ul class="methodCon" id="Paypal_Ul">
		<li><label><?= $_str_paypal_check_name?></label><?= tep_draw_input_field('affiliate_payment_check','','class="text"');?></li>
		<li><label><?= $_str_paypal_check_email?></label><?= tep_draw_input_num_en_field('affiliate_payment_paypal','','class="text"');?></li>
	</ul>
	<ul class="methodCon" id="Alipay_Ul">
		<li><label><?= $_str_paypal_check_name?></label><?= tep_draw_input_field('affiliate_payment_alipay_name','','class="text"');?></li>
		<li><label><?= $_str_alipay_check_email?></label><?= tep_draw_input_num_en_field('affiliate_payment_alipay','','class="text"');?></li>
		<?php /*?><li><label><?= $_str_telphone ?></label><?= tep_draw_input_field('affiliate_mobile','','class="text"');?></li> <?php */?>
	</ul>
	<ul class="methodCon" id="Bank_Ul">
		<li><label><?= $_str_bank_check_name?></label><?= tep_draw_input_field('affiliate_payment_bank_account_name','','class="text"');?></li>
		<li><label><?= $_str_bank_name?></label><?= tep_draw_input_field('affiliate_payment_bank_name','','class="text"');?></li>
		<li><label><?= $_str_bank_number?></label><?= tep_draw_input_num_en_field('affiliate_payment_bank_account_number','','class="text"');?></li>
		<?php /*?><li><label><?= $_str_telphone ?></label><?= tep_draw_input_field('affiliate_mobile','','class="text"');?></li><?php */?>
	</ul>
	<ul class="methodCon" style="display:block;margin:0 0 0 10px;"><li><label><?= $_str_telphone ?></label><?= tep_draw_input_field('affiliate_mobile','','class="text"');?></li></ul>



	<div class="submit">
		<label class="btn btnOrange"><button type="submit">保 存</button></label>
	</div>
	</form>

	<div id="affiliate_details_info">
		<ul class="method">
			<li class="on">
			<?= $method_string;?>
			&nbsp;
			</li>
		</ul>
		<ul class="methodCon" style="display: block;">
		<?= $account_string;?>
		&nbsp;		
        </ul>
	</div>

	<div class="attention">
		<h3>Paypal支付注意事项：</h3>
		<p>1、走四方网提供的paypal支付方式暂时只适用于除中国内地以外的客户。</p>
		<p>2、PayPal系统中的每个电子邮件地址都是唯一的，并表示一个唯一标识符(类似银行账号)。</p>
		<p>3、如果您的交易涉及币种兑换，将按PayPal从金融机构获得的汇率完成兑换，该汇率将依据市场情况进行定期调整。</p>
		<p>4、走四方网提供的paypal支付方式暂时只适用于除中国内地以外的客户。</p>
	</div>
</div>

<script type="text/javascript">
<?php //编辑表单{?>
function _checkMethod(value){
	if(value=="" || value=="Paypal"){
		jQuery("#Paypal_Ul").show();   
		jQuery("#Bank_Ul,#Alipay_Ul").hide();   
	}else if(value == 'Alipay'){
		jQuery('#Alipay_Ul').show();
		jQuery('#Bank_Ul,#Paypal_Ul').hide();
	}else{
		jQuery("#Bank_Ul").show();   
		jQuery("#Paypal_Ul,#Alipay_Ul").hide();   
	}
}
_checkMethod("<?= $affiliate_default_payment_method?>");
function checksubmit(){
	var type = jQuery('input[type="radio"]:checked').val();
	switch(type) {
	case 'Paypal':
		var apname = jQuery('input[name="affiliate_payment_check"]');
		var appaypal = jQuery('input[name="affiliate_payment_paypal"]');
		
		if (apname.val().trim() == "") {
			alert('<?php echo $_str_paypal_check_name?>未填写！');
			apname.focus();
			return false;
		} 
		if (appaypal.val().trim() == "") {
			alert('<?php echo $_str_paypal_check_email?>未填写！');
			appaypal.focus();
			return false;
		}
		if (!/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(appaypal.val())) {
			alert('<?php echo $_str_paypal_check_email?>填写不正确！');
			appaypal.focus();
			return false;
		}
		break;
	case 'Bank':
		var apbankaname = jQuery('input[name="affiliate_payment_bank_account_name"]');
		var apbname = jQuery('input[name="affiliate_payment_bank_name"]');
		var apbanumber = jQuery('input[name="affiliate_payment_bank_account_number"]');
		if (apbankaname.val().trim() == "") {
			alert('<?php echo $_str_bank_check_name?>未填写！');
			apbankaname.focus();
			return false;
		}
		if (apbname.val().trim() == "") {
			alert('<?php echo $_str_bank_name?>未填写！');
			apbname.focus();
			return false;
		}
		if (apbanumber.val().trim() == "") {
			alert('<?php echo $_str_bank_number?>未填写！');
			apbanumber.focus();
			return false;
		}
		break;
	case 'Alipay':
		var apaname = jQuery('input[name="affiliate_payment_alipay_name"]');
		var apalipay = jQuery('input[name="affiliate_payment_alipay"]');
		if (apaname.val().trim() == ""){
			alert('<?php echo $_str_paypal_check_name?>未填写！');
			apaname.focus();
			return false;
		}
		if (apalipay.val().trim() == ""){
			alert('<?php echo $_str_alipay_check_email?>未填写！');
			apalipay.focus();
			return false;
		}
		break;
	}
	var apmobile = jQuery('input[name="affiliate_mobile"]');
	if (apmobile.val().trim() == "") {
		alert('<?php echo $_str_telphone?>未填写！');
		apmobile.focus();
		return false;
	}
	return true;
}
<?php //编辑表单}?>

function _showForm(v){
	if(v==1){
		jQuery('#affiliate_details_form').show();
		jQuery('#affiliate_details_info').hide();
	}else{
		jQuery('#affiliate_details_form').hide();
		jQuery('#affiliate_details_info').show();
	}
}

<?php if($_GET['action']==='edit'){ //编辑状态?>
	_showForm(1);
<?php }else{?>
	_showForm(0);
<?php }?>
</script>

<?php echo  db_to_html(ob_get_clean());?>