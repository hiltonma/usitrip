<?php
$tmp_var = '
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="25" colspan="3" align="left" bgcolor="#FFFFFF">&nbsp;走四方在中國的帳號資訊如下：</td>
  </tr>
  <tr>
    <td height="22" align="left" valign="middle" bgcolor="#FFFFFF" >&nbsp;開戶行</td>
    <td align="left" valign="middle" bgcolor="#FFFFFF" >&nbsp;銀行帳號</td>
    <td align="left" valign="middle" bgcolor="#FFFFFF" >&nbsp;收款人</td>
  </tr>
  <tr>
    <td height="22" align="left" valign="middle" bgcolor="#f0f0f0" >&nbsp;<b>'.db_to_html(MODULE_PAYMENT_TRANSFER_BANK).'</b></td>
    <td align="left" valign="middle" bgcolor="#f0f0f0" >&nbsp;<b>'.db_to_html(MODULE_PAYMENT_TRANSFER_ACCOUNT).'</b></td>
    <td align="left" valign="middle" bgcolor="#f0f0f0" >&nbsp;<b>'.db_to_html(MODULE_PAYMENT_TRANSFER_PAYTO).'</b></td>
  </tr>
';


for($j=0; $j<BANK_ACCOUNT_NUM; $j++){
	if(defined('MODULE_PAYMENT_TRANSFER_BANK'.$j) && defined('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$j) && defined('MODULE_PAYMENT_TRANSFER_PAYTO'.$j) ){
		if(constant('MODULE_PAYMENT_TRANSFER_BANK'.$j)!="" && constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$j)!="" && constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$j)!=""){
			//#f0f0f0 #FFFFFF
			$_bgcolor = '#FFFFFF';
			if($j%2==0){ $_bgcolor = '#f0f0f0'; }
			$tmp_var .= '
			  <tr>
				<td height="22" align="left" valign="middle" bgcolor="'.$_bgcolor.'" >&nbsp;<b>'.db_to_html(constant('MODULE_PAYMENT_TRANSFER_BANK'.$j)). '</b></td>
				<td align="left" valign="middle" bgcolor="'.$_bgcolor.'" >&nbsp;<b>'.db_to_html(constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$j)). '</b></td>
				<td align="left" valign="middle" bgcolor="'.$_bgcolor.'" >&nbsp;<b>'.db_to_html(constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$j)). '</b></td>
			  </tr>
			';
			
		}
	}
}


$tmp_var .= '  <tr>
    <td colspan="3" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;<b>特別提示：</b><span style="color:#6F6F6F;">請儘量在匯款資訊備註中留下您的訂單號，姓名等資訊！以便我們的財務部核對是該訂單的匯款。 完成付款後請及時聯繫我們的線上客服告知付款完畢。一切相關銀行手續費由您自行承擔！</span></td>
  </tr>
</table>';


////////////////////////////////////////////////////////////////////////////////////////
  define('MODULE_PAYMENT_TRANSFER_TEXT_TITLE', '銀行轉賬(中國)');


  define('MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION',$tmp_var);

//BANK_ACCOUNT_NUM是zhh添加的，用於指定銀行帳戶的個數可在configuration表中找到它
if(defined('BANK_ACCOUNT_NUM') && BANK_ACCOUNT_NUM >0){      
	$tmp_var1 =  
	"走四方在中國的帳號資訊如下：". 
  "\n\n開戶行: " . db_to_html(MODULE_PAYMENT_TRANSFER_BANK).
  "&nbsp;&nbsp;&nbsp;&nbsp;銀行帳號: " . db_to_html(MODULE_PAYMENT_TRANSFER_ACCOUNT) .
  "&nbsp;&nbsp;&nbsp;&nbsp;收款人: " . db_to_html(MODULE_PAYMENT_TRANSFER_PAYTO);

	for($ii=0;$ii<BANK_ACCOUNT_NUM; $ii++){
		if(defined('MODULE_PAYMENT_TRANSFER_BANK'.$ii) && defined('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$ii) && defined('MODULE_PAYMENT_TRANSFER_PAYTO'.$ii) ){
			if(constant('MODULE_PAYMENT_TRANSFER_BANK'.$ii)!="" && constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$ii)!="" && constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$ii)!=""){
		
				$tmp_var1 .=
				"\n\n開戶行: " . db_to_html(constant('MODULE_PAYMENT_TRANSFER_BANK'.$ii)).
				"&nbsp;&nbsp;&nbsp;&nbsp;銀行帳號: " . db_to_html(constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$ii)) .
				"&nbsp;&nbsp;&nbsp;&nbsp;收款人: " . db_to_html(constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$ii));
			}
		}
	}

	$tmp_var1 .= "\n\n" . '特別提示：請儘量在匯款資訊備註中留下您的訂單號，姓名等資訊！';
	
	define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', preg_replace('/[[:space:]]+/',' ',$tmp_var));	//$tmp_var1
  
}else{  
   define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', 
  "走四方在中國的帳號資訊如下:". 
  "\n\n開戶行: " . db_to_html(MODULE_PAYMENT_TRANSFER_BANK) .
  "&nbsp;&nbsp;&nbsp;&nbsp;銀行帳號: " . db_to_html(MODULE_PAYMENT_TRANSFER_ACCOUNT) .
  "&nbsp;&nbsp;&nbsp;&nbsp;收款人: " . db_to_html(MODULE_PAYMENT_TRANSFER_PAYTO) . 
  "\n\n" . '<b>特別提示：</b>請儘量在匯款資訊備註中留下您的訂單號，姓名等資訊！以便我們的財務部核對是該訂單的匯款。 完成付款後請及時聯繫我們的線上客服告知付款完畢。一切相關銀行手續費由您自行承擔！');
}
?>