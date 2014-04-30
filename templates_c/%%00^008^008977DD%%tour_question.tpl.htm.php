<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 02:11:12
         compiled from tour_question.tpl.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'tour_question.tpl.htm', 31, false),)), $this); ?>
<style type="text/css">
.message{ margin:0 auto; padding:20px 0; width:930px; }
.message h1{ margin:10px 10px 10px 0; height:30px; font-size:14px; line-height:30px; border-bottom:1px dashed #C6C6C6; }
.message h1 span{ margin-left:15px; font-weight:normal; font-size:12px; color:#777;}
.message h2{ width:920px; padding-bottom:5px; font-size:12px; font-weight:normal; }
.message h2 s{color:#777;}
.message h2 b{ padding-left:20px; color:#F7860F; font-family:Tahoma; }
.message .con{ margin:0 auto; padding-bottom:10px; width:800px; overflow:hidden;}
.message li{ float:left; width:100%; padding:5px 0; line-height:22px; color:#F7860F;}
.message li input{ float:left;}
.message li label{ float:left; width:65px; color:#777;}
.message li .username{ width:120px;}
.message li .email{ width:200px;}
.message li textarea{ float:left; width:550px; height:75px;}
.message li.info{ height:22px;}
.message li.info label{ width:auto;}
.message li.info input{ float:left; margin:2px; *margin:0 2px; display:inline;}
.message li.info span{ float:left; margin-right:6px; display:inline; color:#111;}
.message .submit{ float:left; margin-left:285px; display:inline; padding-top:15px; width:220px; height:27px; line-height:27px;}
.btn{ display:block; float:left;  height:23px;  white-space:nowrap; overflow:hidden; line-height:23px; *line-height:25px; }
.btn button{ float:left; padding:0 15px; width:auto; background:none; border:0; height:22px; font-size:12px; cursor:pointer; text-align:center; overflow:visible; }
*:lang(zh) .btn button{ padding:0 12px !important;}
.btn:hover{ background:url(/image/button_bg.gif) 0px -23px; text-decoration:none; border:1px solid #f8b709;}
/*³ÈÉ«button*/
.btnOrange{ background:url(/image/button_bg.gif); font-weight:bold; border:1px solid #f8b709; }
.btnOrange button{ font-weight:bold;}
</style>

<?php echo $this->_tpl_vars['form_head']; ?>

<div class="message">
  <?php if (((is_array($_tmp=$this->_tpl_vars['products_id'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")) > 1): ?>
  <h2><?php echo $this->_tpl_vars['shows']['products_name']; ?>
&nbsp;&nbsp;<b><?php echo $this->_tpl_vars['products_price']; ?>
</b></h2>
  <?php endif; ?>



  
   
<?php echo $this->_tpl_vars['shows']['message']; ?>

<?php if ($this->_tpl_vars['send'] == 'true'): ?>
 <div class="con">
 <ul>
  <?php echo $this->_tpl_vars['msgSuccess']; ?>
 <?php echo $this->_tpl_vars['shows']['link_back']; ?>

<?php else: ?>
  <div class="con">
 <ul>
	<h1><?php echo @TEXT_PRODUCTS_QUESTION_ANSWERS; ?>
<span><?php echo $this->_tpl_vars['shows']['all_need_input']; ?>
</span></h1>
      <li><label><?php echo @TEXT_YOUR_FNAME; ?>
</label><?php echo $this->_tpl_vars['input_fields']['customers_name']; ?>
</li>
      <li><label><?php echo @TEXT_YOUR_EMAIL; ?>
</label><?php echo $this->_tpl_vars['input_fields']['customers_email']; ?>
</li>
     <!--   <li><label><?php echo @TEXT_YOUR_EMAIL_CONFIRM; ?>
</label><?php echo $this->_tpl_vars['input_fields']['c_customers_email']; ?>
</li>-->
      <li><label><?php echo @TEXT_YOUR_QUESTION; ?>
</label><?php echo $this->_tpl_vars['textarea_fields']['question']; ?>
<input type="hidden" name="cPath" value="<?php echo $this->_tpl_vars['cPath']; ?>
"><input type="hidden" name="products_id" value="<?php echo $_GET['products_id']; ?>
"></li>
      <li class="info"><label><?php echo $this->_tpl_vars['shows']['agree_new_info']; ?>
</label><input name="accept_newsletter" type="radio" value="1" checked><span><?php echo $this->_tpl_vars['shows']['yes']; ?>
</span><input type="radio" name="accept_newsletter" value="0"> <span><?php echo $this->_tpl_vars['shows']['no']; ?>
</span></li>
	 <?php if ($this->_tpl_vars['useVisualVerifyCode'] == '1'): ?> 
	 <li><label><?php echo $this->_tpl_vars['RandomCodeText']; ?>
</label><?php echo $this->_tpl_vars['RandomImg']; ?>
<?php echo $this->_tpl_vars['input_fields']['visual_verify_code']; ?>
</li><?php endif; ?>
<?php endif; ?>
    </ul>
    <div class="submit"><?php echo $this->_tpl_vars['submit_button']; ?>
&nbsp;&nbsp;&nbsp;<?php if ($_GET['action'] != 'process'): ?> <?php else: ?><?php echo $this->_tpl_vars['shows']['link_history_go']; ?>
<?php endif; ?></div>
  </div>
</div>
<?php echo $this->_tpl_vars['form_bottom']; ?>


<script type="text/javascript">
function updateVVC(){
	 var url = url_ssl("<?php echo $this->_tpl_vars['vvcUrl']; ?>
");     
	jQuery.get(url,{"action":"updateVVC",'random':Math.random()},function(data){
               jQuery("#vvc").attr('src', data); 
      });
}
		function formCallback(result, form) {
			window.status = "valiation callback for form '" + form.id + "': result = " + result;
		}
		
		var valid = new Validation('frm_product_queston_write', {immediate : true,useTitles:true, onFormValidate : formCallback});
			
		Validation.add('validate-email-confirm-que', 'Your confirmation email does not match your first email, please try again.', function(v){
				return (v == $F('customers_email'));
			});
</script>