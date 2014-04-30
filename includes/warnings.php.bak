<?php

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }


// give the visitors a message that the website will be down at ... time
  if ( (WARN_BEFORE_DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE == 'false') ) {
       $messageStack->add('header', TEXT_BEFORE_DOWN_FOR_MAINTENANCE . PERIOD_BEFORE_DOWN_FOR_MAINTENANCE, 'warning');
  }


// this will let the admin know that the website is DOWN FOR MAINTENANCE to the public
  if ( (DOWN_FOR_MAINTENANCE == 'true') && (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE == getenv('REMOTE_ADDR')) ) {
       $messageStack->add('header', TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }

  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<script type="text/javascript">
/*    ErrInfo */
jQuery(document).ready(function(e) {
	//alert(document.documentElement.clientWidth);
	var docWidth = document.documentElement.clientWidth;
	var docHeight = document.documentElement.clientHeight;
  jQuery('#errDiv').css('top',(docHeight - jQuery('#errDiv').height()) / 2);
	jQuery('#errDiv').css('left',(docWidth - jQuery('#errDiv').width()) / 2);
	jQuery('#popupBg').css({'width':docWidth,'height':docHeight,'display':'block'});
	jQuery('#errCloseBtn').bind('click',function(e) {
    jQuery('#errDiv').css('display','none');
		jQuery('#popupBg').css('display','none');
  });
	if(navigator.userAgent.toLowerCase().match(/iPad/i) != "ipad"){
		function _AerrScroll() {
				//document.title += '-+';
				if (jQuery('#errDiv').css('display') != 'none'){
					var docHeight = document.documentElement.clientHeight;// + Math.max(document.documentElement.scrollTop, document.body.scrollTop);
					var docWidth = document.documentElement.clientWidth;
					jQuery('#errDiv').css('top',(docHeight - jQuery('#errDiv').height()) / 2 + Math.max(document.documentElement.scrollTop, document.body.scrollTop));
				jQuery('#errDiv').css('left',(docWidth - jQuery('#errDiv').width()) / 2);
				jQuery('#popupBg').css({'width':docWidth,'height':docHeight + Math.max(document.documentElement.scrollTop, document.body.scrollTop),'display':'block'});
				}
		}
		jQuery(document).bind('scroll',_AerrScroll);
		jQuery('body').bind('scroll',_AerrScroll);
	}
});
</script>
<div style="position:relative;width:100%;z-index:99;">
<div style="margin:0 auto;width:985px;">
<div id="errDiv" style="position:absolute;top:300px;width:400px;background:#fff;z-index:9999;border:1px solid #AED5FF;">
<div style="padding-left:5px; height:36px;line-height:36px;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/carts.gif) repeat-x  left -173px ;color:#f00;"><div style="float:right;width:30px;height:30px;cursor:pointer;text-align:center;color:#000;cursor:pointer;" id="errCloseBtn"><?php echo db_to_html('关闭')?></div><?php echo db_to_html('错误提示')?></div>
<div style="display:block; padding:5px;">
				<?php echo tep_htmlspecialchars(urldecode($HTTP_GET_VARS['error_message'])); ?>
				 
				
			
				<?php
			  //amit added to show authorized checkout error start
			  if(isset($HTTP_GET_VARS['addextra']) && $HTTP_GET_VARS['addextra'] == "true"){  
				//howard added to add extra error message start
				

				//$exp_error_cn = (db_to_html('重要提示：您的信用卡支付没有通过系统审核，支付失败！'))."\n";
				$exp_error_cn = (db_to_html('走四方网提供严格系统的验证操作保护客户信用卡支付的安全，您本次的支付失败有可能存在各种原因，如果您使用的是美国本土卡：'))."\n";
				$exp_error_cn .= (db_to_html('1. 请核对您的账单地址（Billing Address）是否正确；'))."\n";
				$exp_error_cn .= (db_to_html('2. 信用卡持有人和账单地址（Billing Address）的姓名必须为英文或拼音字母；'))."\n";
				$exp_error_cn .= (db_to_html('3. 请核对您的到期日（Expired Date）以及信用卡的CVV 号是否正确；'))."\n";
				$exp_error_cn .= (db_to_html('4. 请和银行联系确认您的信用卡是否超过每日消费限额或信用余额不足'))."\n";
				$exp_error_cn .= (db_to_html('如果您使用的是国际信用卡：'))."\n";
				$exp_error_cn .= (db_to_html('1. 请核对您的到期日（Expired Date）以及信用卡的CVV 号是否正确；'))."\n";
				$exp_error_cn .= (db_to_html('2. 信用卡持有人和账单地址（Billing Address）的姓名必须为英文或拼音字母；'))."\n";
				$exp_error_cn .= (db_to_html('3. 请和银行联系确认您的信用卡是否超过每日消费限额或信用余额不足。'))."\n";
				$exp_error_cn .= (db_to_html('如果您确信以上情况都无问题，请联系我们客户服务：'))."\n";
				$exp_error_cn .= (db_to_html('美国：1-626-898-7800 888-887-2816'))."\n";
				$exp_error_cn .= (db_to_html('中国：0086-4006-333-926'))."\n";
				$exp_error_cn .= (db_to_html('邮箱：service@usitrip.com'))."\n\n";
				$auth_extra_message = $exp_error_cn;
				//howard added to add extra error message end
				
				//amit added to add extra error message start 
				//$auth_extra_message .= "usitrip.com takes every precaution to protect your security. It looks like either you are using an International credit card or your billing address cannot be verified by our system. Please make sure the billing address you entered is your credit card billing address if you are using a U.S. issued credit card. If you keep getting this error message or you are using an International credit card, please choose Credit Card: International Credit Card option to proceed with your booking process. Thanks!";
				//amit added to add extra error message end
				

				//echo tep_htmlspecialchars(urldecode($auth_extra_message));
				echo nl2br(tep_db_output(($auth_extra_message)));
	}
				 //amit added to show authorized checkout error end
				?>


            </div>
</div>
</div>
</div>
<?php
  }




  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo tep_htmlspecialchars($HTTP_GET_VARS['info_message']); ?></td>
  </tr>
</table>
<?php
  }
?>
