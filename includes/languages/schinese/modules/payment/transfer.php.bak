<?php
$tmp_var = '
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="25" colspan="3" align="left" bgcolor="#FFFFFF">&nbsp;走四方在中国的账号信息如下：</td>
  </tr>
  <tr>
    <td height="22" align="left" valign="middle" bgcolor="#FFFFFF" >&nbsp;开户行</td>
    <td align="left" valign="middle" bgcolor="#FFFFFF" >&nbsp;银行账号</td>
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
    <td colspan="3" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;<b>特别提示：</b><span style="color:#6F6F6F;">请尽量在汇款信息备注中留下您的订单号，姓名等信息！以便我们的财务部核对是该订单的汇款。 完成付款后请及时联系我们的在线客服告知付款完毕。一切相关银行手续费由您自行承担！</span></td>
  </tr>
</table>';


////////////////////////////////////////////////////////////////////////////////////////
  define('MODULE_PAYMENT_TRANSFER_TEXT_TITLE', '银行转账(中国)');


  define('MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION',$tmp_var);

//BANK_ACCOUNT_NUM是zhh添加的，用于指定银行账户的个数可在configuration表中找到它
if(defined('BANK_ACCOUNT_NUM') && BANK_ACCOUNT_NUM >0){      
	$tmp_var1 =  
	"走四方在中国的账号信息如下：". 
  "\n\n开户行: " . db_to_html(MODULE_PAYMENT_TRANSFER_BANK).
  "&nbsp;&nbsp;&nbsp;&nbsp;银行账号: " . db_to_html(MODULE_PAYMENT_TRANSFER_ACCOUNT) .
  "&nbsp;&nbsp;&nbsp;&nbsp;收款人: " . db_to_html(MODULE_PAYMENT_TRANSFER_PAYTO);

	for($ii=0;$ii<BANK_ACCOUNT_NUM; $ii++){
		if(defined('MODULE_PAYMENT_TRANSFER_BANK'.$ii) && defined('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$ii) && defined('MODULE_PAYMENT_TRANSFER_PAYTO'.$ii) ){
			if(constant('MODULE_PAYMENT_TRANSFER_BANK'.$ii)!="" && constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$ii)!="" && constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$ii)!=""){
		
				$tmp_var1 .=
				"\n\n开户行: " . db_to_html(constant('MODULE_PAYMENT_TRANSFER_BANK'.$ii)).
				"&nbsp;&nbsp;&nbsp;&nbsp;银行账号: " . db_to_html(constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$ii)) .
				"&nbsp;&nbsp;&nbsp;&nbsp;收款人: " . db_to_html(constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$ii));
			}
		}
	}

	$tmp_var1 .= "\n\n" . '<b>特别提示：</b>请尽量在汇款信息备注中留下您的订单号，姓名等信息！以便我们的财务部核对是该订单的汇款。 完成付款后请及时联系我们的在线客服告知付款完毕。一切相关银行手续费由您自行承担！';
	
	define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', preg_replace('/[[:space:]]+/',' ',$tmp_var));	//$tmp_var1
  
}else{  
   define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', 
  "走四方在中国的账号信息如下:". 
  "\n\n开户行: " . db_to_html(MODULE_PAYMENT_TRANSFER_BANK) .
  "&nbsp;&nbsp;&nbsp;&nbsp;银行账号: " . db_to_html(MODULE_PAYMENT_TRANSFER_ACCOUNT) .
  "&nbsp;&nbsp;&nbsp;&nbsp;收款人: " . db_to_html(MODULE_PAYMENT_TRANSFER_PAYTO) . 
  "\n\n" . '<b>特别提示：</b>请尽量在汇款信息备注中留下您的订单号，姓名等信息！以便我们的财务部核对是该订单的汇款。 完成付款后请及时联系我们的在线客服告知付款完毕。一切相关银行手续费由您自行承担！');
}
?>