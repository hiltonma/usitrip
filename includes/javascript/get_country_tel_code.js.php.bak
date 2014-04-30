<?php
//全局的js代码，全站适用

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	//如果为false将以php的格式一行一行列到页面
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--

<?php
}
?>

var CountryTelCode = new Array();

<?php
//取得所有国家的对应国际电话区号
$country_sql = tep_db_query('SELECT countries_id,countries_tel_code FROM `countries` WHERE countries_tel_code !="" ');
$start_num = 0;
while($country_rows = tep_db_fetch_array($country_sql)){
	echo 'CountryTelCode['.$start_num++.'] = new Array("'.$country_rows['countries_id'].'", "'.$country_rows['countries_tel_code'].'");'."\n";
}
?>

function get_CountryTelCode(FormId,CountryId){
	var form_a = document.getElementById(FormId);
	for(i=0; i< CountryTelCode.length; i++){
		if(form_a.elements['telephone_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['telephone_cc'].value = CountryTelCode[i][1];
		}
		if(form_a.elements['fax_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['fax_cc'].value = CountryTelCode[i][1];
		}
		if(form_a.elements['cellphone_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['cellphone_cc'].value = CountryTelCode[i][1];
		}
		if(form_a.elements['mobile_phone_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['mobile_phone_cc'].value = CountryTelCode[i][1];
		}
		
	}
}

function get_ShipCountryTelCode(FormId,CountryId){
	var form_a = document.getElementById(FormId);
	for(i=0; i< CountryTelCode.length; i++){
		if(form_a.elements['telephone_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['telephone_cc'].value = CountryTelCode[i][1];
		}
		if(form_a.elements['fax_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['fax_cc'].value = CountryTelCode[i][1];
		}
		if(form_a.elements['cellphone_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['cellphone_cc'].value = CountryTelCode[i][1];
		}
		if(form_a.elements['mobile_phone_cc'] != null && CountryId==CountryTelCode[i][0]){
			form_a.elements['mobile_phone_cc'].value = CountryTelCode[i][1];
		}
		
	}
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>