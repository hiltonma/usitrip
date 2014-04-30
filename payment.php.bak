<?php
/**
 * 支付方式说明页面
 * @package 
 * 判断如果某支付方式已经关闭就不再显示相关内容，西联汇款和上门付款除外
 */

require('includes/application_top.php');

// define our link functions
require(DIR_FS_FUNCTIONS . 'links.php');

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ABOUT_US);

//seo信息
$the_title = db_to_html('支付方式-走四方网');
$the_desc = db_to_html('　');
$the_key_words = db_to_html('　');
//seo信息 end

//载入所有支付模块
$travel_pay = true;	//这个变量不能少，否则在非登录的情况下将看不到支付方式
require(DIR_FS_CLASSES . 'payment.php');
$paymentModules = new payment;
$selection = $paymentModules->selection();
$_all_payments = array('USD'=>array(), 'CNY'=>array());
$_all_payments_ids = array();
for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
	$_p_name = html_to_db($selection[$i]['module']);
	$_p_name = preg_replace('/(（|\().+/','',$_p_name);
	$_width = ($selection[$i]['id']=='cashdeposit' ? 'width:105px;' : '' );
	$_all_payments[$selection[$i]['currency']][] = array('id'=> $selection[$i]['id'], 'name'=> $_p_name, 'width' => $_width); 
	$_all_payments_ids[] = $selection[$i]['id'];
}

//国内银行账号整理
$_transfers = array();
if(in_array('transfer', $_all_payments_ids)){
	$_banks = array('招商银行'=>array('href'=>'http://www.cmbchina.com/', 'img'=>'bank_cn_zs.gif'),
					'农业银行'=>array('href'=>'http://www.95599.cn/', 'img'=>'bank_cn_ny.jpg'),
					'建设银行'=>array('href'=>'http://www.ccb.com/', 'img'=>'bank_cn_js.jpg'),
					'工商银行'=>array('href'=>'http://www.icbc.com.cn/', 'img'=>'bank_cn_gs.gif'),
					'民生银行'=>array('href'=>'http://www.cmbc.com.cn/', 'img'=>'bank_cn_ms.jpg'),
					'中信银行'=>array('href'=>'http://bank.ecitic.com/', 'img'=>'bank_cn_zx.jpg'),
					'广发银行'=>array('href'=>'http://www.gdb.com.cn/', 'img'=>'bank_cn_gf.jpg'),
					'光大银行'=>array('href'=>'http://www.cebbank.com/', 'img'=>'bank_cn_guangda.jpg'),
					'中国银行'=>array('href'=>'http://www.boc.cn/', 'img'=>'bank_cn_zhongguo.jpg'),
					'浦东发展银行'=>array('href'=>'http://www.spdb.com.cn/', 'img'=>'bank_cn_pufa.jpg'),
					);
	
	for($j = -1; $j<BANK_ACCOUNT_NUM; $j++){
		$JJ = ($j>=0 ? $j : '');
		if(defined('MODULE_PAYMENT_TRANSFER_BANK'.$JJ) && defined('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$JJ) && defined('MODULE_PAYMENT_TRANSFER_PAYTO'.$JJ) ){
			$_href = $_img = '';
			$_bank_name = constant('MODULE_PAYMENT_TRANSFER_BANK'.$JJ);
			foreach($_banks as $key => $val){
				if(strpos($_bank_name, $key) !== false){
					$_href = $val['href'];
					$_img = $val['img'];
					break;
				}
			}
			$_transfers[]=array('city'=> '深圳', 
								'href'=>$_href,
								'img'=>$_img,
								'bank'=>$_bank_name,
								'account'=>constant('MODULE_PAYMENT_TRANSFER_ACCOUNT'.$JJ),
								'payto'=>constant('MODULE_PAYMENT_TRANSFER_PAYTO'.$JJ));
		}
	}
}

$add_div_footpage_obj = true;
$content = 'payment';
$breadcrumb->add(db_to_html('支付方式'), 'payment.php');

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
