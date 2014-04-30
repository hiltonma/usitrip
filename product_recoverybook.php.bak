<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
$ajaxPost = $_POST['ajaxPost'];
if(isset($_POST['ajaxPost']) && $ajaxPost==1){
	define('AJAX_MOD',1);
}
require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');



if(isset($_POST['ajaxPost']) && $ajaxPost==1){
	$product_id = intval($_POST['product_id']);
	
	$sql = 'select pd.products_name,p.products_stock_status from '.TABLE_PRODUCTS . ' p , '. TABLE_PRODUCTS_DESCRIPTION . ' pd where p.products_id = pd.products_id and pd.language_id = "' . (int)$languages_id .'" and p.products_status = "1" and p.products_id="'.$product_id.'"';
	$product = tep_db_get_one($sql);
	$product['operate'] = tep_get_display_operate_info($product_id,1);
	//未售完
	if(count($product['operate'])>0 && $product['products_stock_status']==1){
		if($_POST['action']=="fastsubmitemail"){ echo db_to_html('未售完，提交失败！'); exit;}
?>
		<div class="bookMark">
            <p><span class="center"><?php echo db_to_html('对不起，不能使用此功能。该行程并未售完！');?></span></p>
        </div>
        <div class="popupBtn">
          <a onclick="closePopup('popupEmail')" class="btn btnOrange" href="javascript:;"><button type="button"><?php echo db_to_html('关 闭');?></button></a>
        </div>
<?php
	}else{
	//已售完
		//已提交
		if(isset($_POST['product_soldout_email']) && is_CheckvalidEmail($_POST['product_soldout_email']) ){
			$product_soldout_email = $_POST['product_soldout_email'];
			$sql = "SELECT products_id FROM `products_soldout_email` WHERE `products_id`='{$product_id}' and `email`='{$product_soldout_email}'";
			$soldout_email = tep_db_get_one($sql);
			if(!$soldout_email){
				tep_db_query("INSERT INTO `products_soldout_email` SET `products_id`='{$product_id}',`email`='{$product_soldout_email}',`charset`='".CHARSET."';");	
			}
			if($_POST['action']=="fastsubmitemail"){ echo '1'; exit;}
?>
			<div class="bookMark">
                <p><span class="center"><?php echo db_to_html('操作成功，当恢复预订时我们会发邮件通知您！');?></span></p>
            </div>
            <div class="popupBtn">
              <a onclick="closePopup('popupEmail')" class="btn btnOrange" href="javascript:;"><button type="button"><?php echo db_to_html('关 闭');?></button></a>
            </div>
		<?php
		}else{
			if($_POST['action']=="fastsubmitemail"){ echo db_to_html('提交失败！'); exit;}
		//未提交
		?>
<form action="" method="post" id="product_soldout_email_form" name="product_soldout_email_form" onsubmit="return false;">
  <div class="bookMark">
      <p><?php echo db_to_html('行程:');?><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$product_id);?>" target="_blank"><?php echo db_to_html($product['products_name']);?></a></p>
      <p><?php echo db_to_html('请在下面输入您的E-mail地址，当恢复预订时我们会发邮件通知您！');?></p>
      <p><?php echo db_to_html('Email地址:');?><input type="text" class="required validate-email text" id="product_soldout_email" name="product_soldout_email" value="<?php echo $customer_email_address;?>"  title="<?php echo db_to_html('Email地址格式错误');?>"></p>
  </div>
  <div class="popupBtn">
    <a href="javascript:;" class="btn btnOrange"><button type="submit" id="product_soldout_email_submit"><?php echo db_to_html('确 定');?></button></a>
  </div>
</form>
<script type="text/javascript">
function formCallback(result, form) {
	if(result==true){
		jQuery("#product_soldout_email_submit").attr("disabled","disabled");
		var ajaxurl = '<?php echo tep_href_link('product_recoverybook.php','');?>';
		var postdata='ajaxPost=1&product_id=<?php echo $product_id;?>';
		postdata=postdata+'&product_soldout_email='+encodeURIComponent(jQuery('#product_soldout_email').val());
		jQuery.ajax({
			global: false,
			url: ajaxurl,
			type: 'POST',
			data: postdata,
			cache: false,
			dataType: 'html',
			success: function(data){
				jQuery('#popupEmail_Content').html(data);
				showPopup('popupEmail','popupConEmail','','440','100');
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert('Ajax Error!Refresh Please!');
			}
		});
	}
}
var valid1 = new Validation('product_soldout_email_form', {immediate : true,useTitles:true, onFormValidate : formCallback});
</script>
<?php
		}
	}
}else{
/*
?>
<div class="popup" id="popupEmail">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConEmail" style="width:400px;">
    <div class="popupConTop" id="dragEmail">
      <h3><b><?php echo db_to_html('恢复预订通知');?></b></h3><span onclick="closePopup('popupEmail')"><img src="image/icons/icon_x.gif"></a></span>
    </div>
    <div id="popupEmail_Content">
    </div>
  </div>
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<?php */ ?>
<script type="text/javascript">
new divDrag([GetIdObj('dragEmail'),GetIdObj('popupEmail')]);

function productSoldOut(product_id){
	var ajaxurl = '<?php echo tep_href_link('product_recoverybook.php','');?>';
	var postdata='ajaxPost=1&product_id='+product_id;
	jQuery.ajax({
        global: false,
        url: ajaxurl,
        type: 'POST',
        data: postdata,
        cache: false,
        dataType: 'html',
        success: function(data){
            jQuery('#popupEmail_Content').html(data);
            showPopup('popupEmail','popupConEmail','','440','100');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Ajax Error!Refresh Please!');
        }
    });
}
</script>
<?php }?>