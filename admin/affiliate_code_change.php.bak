<?php
/**
 * 个性化优惠码替换的SQL语句
 */
//要替换的新旧优惠码数组，记住：key是旧优惠码，value是新优惠码，切勿搞错！
$array = array(
	'AF-NjU4NDk'=>'COZYSHUTTLE',
	//'AF-NjU0NzU'=>'USITRIPXJY7'
);
//mashimaro11@msn.com    Angela    Angela    AF-NjQ3ODM    USITRIPAXY
/**
 * 根据数组提供的数据列出要修改的优惠码的SQL语句
 * @param array $code_array 优惠码数组array(['旧优惠码']=>'新优惠码')
 * @return sqlStr
 */
function output_sql_str(array $code_array){
	$format_str = 'update affiliate_affiliate set affiliate_coupon_code="[新代码]", changed="1" where affiliate_coupon_code="[旧代码]"; '.PHP_EOL;
	$format_str.= 'update coupons set coupon_code="[新代码]" where coupon_code="[旧代码]"; ';
	$sql_str = '';
	//列出新优惠码是否已经存在
	$check_code = '"'.implode('","', $code_array).'"';
	$sql_str.='（1）请先用以下代码检查是否有重复：'.PHP_EOL;
	$sql_str.='SELECT affiliate_coupon_code FROM affiliate_affiliate WHERE affiliate_coupon_code in('.$check_code.');'.PHP_EOL;
	$sql_str.='SELECT coupon_code FROM coupons WHERE coupon_code in('.$check_code.');'.PHP_EOL.PHP_EOL;
	$sql_str.='（2）若无问题就用以下代码：'.PHP_EOL;
	foreach ($code_array as $old_code => $new_code){
		$sql_str.= str_replace(array('[旧代码]','[新代码]'),array($old_code, $new_code),$format_str).PHP_EOL;
	}
	$sql_str .= '';
	return $sql_str;
}

echo output_sql_str($array);
?>