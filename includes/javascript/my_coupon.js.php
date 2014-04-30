<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--
<?php
}
?>
$().ready(function() {
	<?php //检查提交激活优惠券表单?>
	$("#active_coupon").submit(function(){
		var CouponCode = this.elements["coupon_code"];
		var error_msn = "";
		if(typeof(CouponCode)!="undefined" && CouponCode.value==""){
			error_msn = "<?= db_to_html("请输入优惠券编号！")?>";
		}else if(typeof(CouponCode)!="undefined"){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('my_coupon.php','action=do_active')) ?>");
			ajax_post_submit(url,this.id,"","", "");
		}else{
			error_msn = "<?= db_to_html("优惠券编号输入框不存在！");?>";
		}
		if(error_msn!=""){
			$("#active_coupon_msn").html("<span style=color:#F00;>&nbsp;"+error_msn+"</span>");
			$("#active_coupon_msn").fadeIn(200);
			$("#active_coupon_msn").fadeOut(2000);
			//alert(error_msn);
		}
		return false;
	});
});

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>