<?php
define('INCLUDES_DIR',preg_replace('@/includes/.*@','',dirname(__FILE__))."/includes/");
if(file_exists(INCLUDES_DIR."configure_local.php")){
	require_once(INCLUDES_DIR."configure_local.php");
}else{
	require_once(INCLUDES_DIR."configure.php");
	#require_once("E:/my_www/tff/trunk/includes/configure_local.php");
}

require_once(INCLUDES_DIR."functions/database.php");
#require_once("E:/my_www/tff/trunk/includes/functions/database.php");
tep_db_connect() or die('Unable to connect to database server!');
//取得订单产品总人数
$str_sql=tep_db_query('select total_room_adult_child_info from orders_products where orders_id='.(int)$_GET['order_id']);
$human_num=0;
while($rows = tep_db_fetch_array($str_sql)){
	$get_human_num=getHumanNum($rows['total_room_adult_child_info']);
	$human_num+=$get_human_num;
}
function getHumanNum($str){
	$num=0;
	$tmp1=explode('###', $str);
	unset($tmp1[0]);
	foreach($tmp1 as $value){
		$tmp2=explode('!!', $value);
		$tmp3=$tmp2[0]+$tmp2[1];
		$num+=$tmp3;
	}
	return $num;
}
$human_num=$human_num?$human_num:1;
//取得产品总人数

//取得订单总价等信息
$orderTotalInfo = false;
$sql = tep_db_query('SELECT title,text,value,class FROM `orders_total` WHERE orders_id="'.(int)$_GET['order_id'].'" ORDER BY sort_order ASC ');

while($rows = tep_db_fetch_array($sql)){
	$orderTotalInfo[] = $rows;
}

/************************************************************
This is the main web page for the DoDirectPayment sample.
This page allows the user to enter name, address, amount,
and credit card information. It also accept input variable
paymentType which becomes the value of the PAYMENTACTION
parameter.

When the user clicks the Submit button, DoDirectPaymentReceipt.php
is called.

Called by index.html.

Calls DoDirectPaymentReceipt.php.

************************************************************/
// clearing the session before starting new API Call
require_once("constants.php");
session_unset();
$paymentType = 'Sale'; //$_REQUEST['paymentType'];

//==取得订单资料========================================================={
$_totals = tep_get_need_pay_value((int)$_GET['order_id']);
$total_fee = $_totals[0];	//0为美元1为人民币

$extra_common_param = '';
//添加结伴同游支付参数数组{
if(isset($_GET['travelCompanionPay'])){
	$travelCompanionPayStr = base64_decode($_GET['travelCompanionPay']);
	$extra_common_param = $travelCompanionPayStr;
	$travelCompanionPay = json_decode($travelCompanionPayStr,true);
	$i_need_pay =$travelCompanionPay['i_need_pay'];
	$total_fee = $i_need_pay;
	$body = '结伴同游订单';
}
//$total_fee = 0.11;
//添加结伴同游支付参数数组}

$_POST['amount'] = number_format($total_fee,2,'.','');	//支付金额
$_POST['remark2'] = $_GET['travelCompanionPay']; //结伴同游的数组串
//==取得订单资料=========================================================}

//计算该支付的最小金额
$str_sql='select allow_pay_min_money from orders where orders_id='.(int)$_GET['order_id'].' and allow_pay_min_money_deadline>"'.date('Y-m-d H:i:s').'" and allow_pay_min_money>0';
$sql = tep_db_query($str_sql);

$rows = tep_db_fetch_array($sql);
if(is_array($rows)){
	$down_need=$rows['allow_pay_min_money'];
}

//计算该支付的最小金额
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh-CN"><head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<meta name="robots" content="noindex,nofollow">
<title>信用卡在线结账</title>
<link href="global.css" rel="stylesheet" type="text/css">
</head>

<body scroll="auto" style="position:static;" bgcolor="#FFFFFF">
<style type="text/css">
/*跳转*/
#jump{	width:860px; position:absolute;left:50%;top:35%; margin:-229px 0 0 -430px;text-align:center; padding-top:20px; border:1px solid #e9e9e9;}
#jump h1{ display:inline;}
#jump .usitrip,#jump .usitrip h1{ font-size:24px; font-family:"微软雅黑",Arial, Helvetica, sans-serif; }
.wait{ padding:0 40px; margin:10px auto;} 
.pop_copyright{ background:#f9f9f9; padding:20px 0; line-height:24px; color:#888;}
.wait .s_1{ padding:12px 0;}
.wait .s_2{ background:#ececec; height:16px; font-size:0; line-height:0; margin-bottom:7px;}
.wait .s_2 img{ width:243px; height:16px;}

</style>

<div id="jump">
	<div class="usitrip color_orange">Paypal信用卡在线付款</div>
    <div class="wait">
    	<p class="s_1" style="text-align:center;"><img src="images/pay1.jpg" alt="Paypal信用卡在线付款"></p>
    	<p class="s_3">
		<strong class="color_green font_bold">USITRIP走四方，伴您一起走四方！</strong>
		</p>
    </div>
	<div style="padding:0 10px">

<form method="post" action="DoDirectPaymentReceipt.php" name="DoDirectPaymentForm" autocomplete="off">
<!--Payment type is <?=$paymentType?><br> -->
<input type="hidden" name="paymentType" value="<?php echo $paymentType?>">
<table>
	<tr>
		<td align="right">First Name(名):</td>
		<td align="left"><input type="text" size="30" maxlength="32" name="firstName" value=""></td>
	</tr>
	<tr>
		<td align="right">Last Name(姓):</td>
		<td align="left"><input type="text" size="30" maxlength="32" name="lastName" value=""></td>
	</tr>
	<tr>
		<td align="right">Card Type(信用卡类型):</td>
		<td align="left">
			<select name="creditCardType">
				<option value="Visa" selected="selected">Visa</option>
				<option value="MasterCard">MasterCard</option>
				<option value="Discover">Discover</option>
				<option value="Amex">American Express</option>
			</select>		</td>
	</tr>
	<tr>
		<td align="right">Card Number(卡号):</td>
		<td align="left"><input type="text" size="19" maxlength="19" name="creditCardNumber"></td>
	</tr>
	<tr>
		<td align="right">Expiration Date(有效期至):</td>
		<td align="left"><p>
			<select name="expDateMonth">
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
			<select name="expDateYear">
				<?php for($i=date("Y"); $i<=date("Y")+10; $i++){?>
				<option value="<?php echo $i;?>"><?php echo $i;?></option>
				<?php }?>
			</select>
		</p></td>
	</tr>
	<tr>
		<td align="right">Card Verification Number(信用卡认证号码):</td>
		<td align="left"><input type="text" size="3" maxlength="4" name="cvv2Number" value=""></td>
	</tr>
	<tr>
		<td align="right"><br>
			<b>Billing Address(账单地址)</b></td>
		<td>
			<input name="order_id" type="hidden" value="<?php echo (int)$_GET['order_id']?>">
			<input name="remark2" type="hidden" id="remark2" value="<?php echo $_POST['remark2']?>">			</td>
	</tr>
	<tr>
		<td align="right">Address 1(街道地址):</td>
		<td align="left"><input type="text" size="25" maxlength="100" name="address1" value=""></td>
	</tr>
	<tr>
		<td align="right">Address 2:</td>
		<td align="left"><input type="text"  size="25" maxlength="100" name="address2">(optional)</td>
	</tr>
	<tr>
		<td align="right">City(城市):</td>
		<td align="left"><input type="text" size="25" maxlength="40" name="city" value=""></td>
	</tr>
	<tr>
		<td align="right">(US/CA) State(州省[美加]):</td>
		<td align="left">
			<select id="state" name="state">
				<option value=""></option>
				<option value="AK">AK</option>
				<option value="AL">AL</option>
				<option value="AR">AR</option>
				<option value="AZ">AZ</option>
				<option value="CA">CA</option>
				<option value="CO">CO</option>
				<option value="CT">CT</option>
				<option value="DC">DC</option>
				<option value="DE">DE</option>
				<option value="FL">FL</option>
				<option value="GA">GA</option>
				<option value="HI">HI</option>
				<option value="IA">IA</option>
				<option value="ID">ID</option>
				<option value="IL">IL</option>
				<option value="IN">IN</option>
				<option value="KS">KS</option>
				<option value="KY">KY</option>
				<option value="LA">LA</option>
				<option value="MA">MA</option>
				<option value="MD">MD</option>
				<option value="ME">ME</option>
				<option value="MI">MI</option>
				<option value="MN">MN</option>
				<option value="MO">MO</option>
				<option value="MS">MS</option>
				<option value="MT">MT</option>
				<option value="NC">NC</option>
				<option value="ND">ND</option>
				<option value="NE">NE</option>
				<option value="NH">NH</option>
				<option value="NJ">NJ</option>
				<option value="NM">NM</option>
				<option value="NV">NV</option>
				<option value="NY">NY</option>
				<option value="OH">OH</option>
				<option value="OK">OK</option>
				<option value="OR">OR</option>
				<option value="PA">PA</option>
				<option value="RI">RI</option>
				<option value="SC">SC</option>
				<option value="SD">SD</option>
				<option value="TN">TN</option>
				<option value="TX">TX</option>
				<option value="UT">UT</option>
				<option value="VA">VA</option>
				<option value="VT">VT</option>
				<option value="WA">WA</option>
				<option value="WI">WI</option>
				<option value="WV">WV</option>
				<option value="WY">WY</option>
				<option value="AA">AA</option>
				<option value="AE">AE</option>
				<option value="AP">AP</option>
				<option value="AS">AS</option>
				<option value="FM">FM</option>
				<option value="GU">GU</option>
				<option value="MH">MH</option>
				<option value="MP">MP</option>
				<option value="PR">PR</option>
				<option value="PW">PW</option>
				<option value="VI">VI</option>
			</select>		</td>
	</tr>
	<tr>
		<td align="right">(Non-US/CA) State/Province(其它国家州/省):</td>
		<td align="left"><input type="text" size="25" maxlength="40" name="state1" value=""></td>
	</tr>
	<tr>
		<td align="right">ZIP Code(邮编):</td>
		<td align="left"><input type="text" size="10" maxlength="10" name="zip" value="">(5 or 9 digits)</td>
	</tr>
	<tr>
		<td align="right">Country(国家):</td>
		<td align="left">
		<?php
		$_countrycode = array("CN"=>"中国", "HK"=>"中国香港", "TW"=>"中国台湾", "AF"=>"AFGHANISTAN", "AX"=>"ALANDISLANDS", "AL"=>"ALBANIA", "DZ"=>"ALGERIA", "AS"=>"AMERICANSAMOA", "AD"=>"ANDORRA", "AO"=>"ANGOLA", "AI"=>"ANGUILLA", "AQ"=>"ANTARCTICA", "AG"=>"ANTIGUAANDBARBUDA", "AR"=>"ARGENTINA", "AM"=>"ARMENIA", "AW"=>"ARUBA", "AU"=>"AUSTRALIA", "AT"=>"AUSTRIA", "AZ"=>"AZERBAIJAN", "BS"=>"BAHAMAS", "BH"=>"BAHRAIN", "BD"=>"BANGLADESH", "BB"=>"BARBADOS", "BY"=>"BELARUS", "BE"=>"BELGIUM", "BZ"=>"BELIZE", "BJ"=>"BENIN", "BM"=>"BERMUDA", "BT"=>"BHUTAN", "BO"=>"BOLIVIA", "BA"=>"BOSNIAANDHERZEGOVINA", "BW"=>"BOTSWANA", "BV"=>"BOUVET ISLAND", "BR"=>"BRAZIL", "IO"=>"BRITISH INDIAN OCEAN TERRITORY", "BN"=>"BRUNEI DARUSSALAM", "BG"=>"BULGARIA", "BF"=>"BURKINA FASO", "BI"=>"BURUNDI", "KH"=>"CAMBODIA", "CM"=>"CAMEROON", "CA"=>"CANADA", "CV"=>"CAPE VERDE", "KY"=>"CAYMAN ISLANDS", "CF"=>"CENTRAL AFRICAN REPUBLIC", "TD"=>"CHAD", "CL"=>"CHILE", "CX"=>"CHRISTMAS ISLAND", "CC"=>"COCOS KEELING ISLANDS", "CO"=>"COLOMBIA", "KM"=>"COMOROS", "CG"=>"CONGO", "CD"=>"CONGO, THE DEMOCRATIC REPUBLIC OF THE", "CK"=>"COOK ISLANDS", "CR"=>"COSTA RICA", "CI"=>"COTE DIVOIRE", "HR"=>"CROATIA", "CU"=>"CUBA", "CY"=>"CYPRUS", "CZ"=>"CZECH REPUBLIC", "DK"=>"DENMARK", "DJ"=>"DJIBOUTI", "DM"=>"DOMINICA", "DO"=>"DOMINICAN REPUBLIC", "EC"=>"ECUADOR", "EG"=>"EGYPT", "SV"=>"EL SALVADOR", "GQ"=>"EQUATORIAL GUINEA", "ER"=>"ERITREA", "EE"=>"ESTONIA", "ET"=>"ETHIOPIA", "FK"=>"FALKLAND ISLANDS MALVINAS", "FO"=>"FAROE ISLANDS", "FJ"=>"FIJI", "FI"=>"FINLAND", "FR"=>"FRANCE", "GF"=>"FRENCH GUIANA", "PF"=>"FRENCH POLYNESIA", "TF"=>"FRENCH SOUTHERN TERRITORIES", "GA"=>"GABON", "GM"=>"GAMBIA", "GE"=>"GEORGIA", "DE"=>"GERMANY", "GH"=>"GHANA", "GI"=>"GIBRALTAR", "GR"=>"GREECE", "GL"=>"GREENLAND", "GD"=>"GRENADA", "GP"=>"GUADELOUPE", "GU"=>"GUAM", "GT"=>"GUATEMALA", "GG"=>"GUERNSEY", "GN"=>"GUINEA", "GW"=>"GUINEA-BISSAU", "GY"=>"GUYANA", "HT"=>"HAITI", "HM"=>"HEARD ISLAND AND MCDONALD ISLANDS", "VA"=>"HOLY SEE VATICAN CITY STATE", "HN"=>"HONDURAS", "HU"=>"HUNGARY", "IS"=>"ICELAND", "IN"=>"INDIA", "ID"=>"INDONESIA", "IR"=>"IRAN, ISLAMIC REPUBLIC OF", "IQ"=>"IRAQ", "IE"=>"IRELAND", "IM"=>"ISLE OF MAN", "IL"=>"ISRAEL", "IT"=>"ITALY", "JM"=>"JAMAICA", "JP"=>"JAPAN", "JE"=>"JERSEY", "JO"=>"JORDAN", "KZ"=>"KAZAKHSTAN", "KE"=>"KENYA", "KI"=>"KIRIBATI", "KP"=>"KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF", "KR"=>"KOREA, REPUBLIC OF", "KW"=>"KUWAIT", "KG"=>"KYRGYZSTAN", "LA"=>"LAO PEOPLE'S DEMOCRATIC REPUBLIC", "LV"=>"LATVIA", "LB"=>"LEBANON", "LS"=>"LESOTHO", "LR"=>"LIBERIA", "LY"=>"LIBYAN ARAB JAMAHIRIYA", "LI"=>"LIECHTENSTEIN", "LT"=>"LITHUANIA", "LU"=>"LUXEMBOURG", "MO"=>"MACAO", "MK"=>"MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF", "MG"=>"MADAGASCAR", "MW"=>"MALAWI", "MY"=>"MALAYSIA", "MV"=>"MALDIVES", "ML"=>"MALI", "MT"=>"MALTA", "MH"=>"MARSHALL ISLANDS", "MQ"=>"MARTINIQUE", "MR"=>"MAURITANIA", "MU"=>"MAURITIUS", "YT"=>"MAYOTTE", "MX"=>"MEXICO", "FM"=>"MICRONESIA, FEDERATED STATES OF", "MD"=>"MOLDOVA, REPUBLIC OF", "MC"=>"MONACO", "MN"=>"MONGOLIA", "MS"=>"MONTSERRAT", "MA"=>"MOROCCO", "MZ"=>"MOZAMBIQUE", "MM"=>"MYANMAR", "NA"=>"NAMIBIA", "NR"=>"NAURU", "NP"=>"NEPAL", "NL"=>"NETHERLANDS", "AN"=>"NETHERLANDS ANTILLES", "NC"=>"NEW CALEDONIA", "NZ"=>"NEW ZEALAND", "NI"=>"NICARAGUA", "NE"=>"NIGER", "NG"=>"NIGERIA", "NU"=>"NIUE", "NF"=>"NORFOLK ISLAND", "MP"=>"NORTHERN MARIANA ISLANDS", "NO"=>"NORWAY", "OM"=>"OMAN", "PK"=>"PAKISTAN", "PW"=>"PALAU", "PS"=>"PALESTINIAN TERRITORY, OCCUPIED", "PA"=>"PANAMA", "PG"=>"PAPUA NEW GUINEA", "PY"=>"PARAGUAY", "PE"=>"PERU", "PH"=>"PHILIPPINES", "PN"=>"PITCAIRN", "PL"=>"POLAND", "PT"=>"PORTUGAL", "PR"=>"PUERTO RICO", "QA"=>"QATAR", "RE"=>"REUNION", "RO"=>"ROMANIA", "RU"=>"RUSSIAN FEDERATION", "RW"=>"RWANDA", "SH"=>"SAINT HELENA", "KN"=>"SAINT KITTS AND NEVIS", "LC"=>"SAINT LUCIA", "PM"=>"SAINT PIERRE AND MIQUELON", "VC"=>"SAINT VINCENT AND THE GRENADINES", "WS"=>"SAMOA", "SM"=>"SAN MARINO", "ST"=>"SAO TOME AND PRINCIPE", "SA"=>"SAUDI ARABIA", "SN"=>"SENEGAL", "CS"=>"SERBIA AND MONTENEGRO", "SC"=>"SEYCHELLES", "SL"=>"SIERRA LEONE", "SG"=>"SINGAPORE", "SK"=>"SLOVAKIA", "SI"=>"SLOVENIA", "SB"=>"SOLOMON ISLANDS", "SO"=>"SOMALIA", "ZA"=>"SOUTH AFRICA", "GS"=>"SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS", "ES"=>"SPAIN", "LK"=>"SRI LANKA", "SD"=>"SUDAN", "SR"=>"SURINAME", "SJ"=>"SVALBARD AND JAN MAYEN", "SZ"=>"SWAZILAND", "SE"=>"SWEDEN", "CH"=>"SWITZERLAND", "SY"=>"SYRIAN ARAB REPUBLIC", "TJ"=>"TAJIKISTAN", "TZ"=>"TANZANIA, UNITED REPUBLIC OF", "TH"=>"THAILAND", "TL"=>"TIMOR-LESTE", "TG"=>"TOGO", "TK"=>"TOKELAU", "TO"=>"TONGA", "TT"=>"TRINIDAD AND TOBAGO", "TN"=>"TUNISIA", "TR"=>"TURKEY", "TM"=>"TURKMENISTAN", "TC"=>"TURKS AND CAICOS ISLANDS", "TV"=>"TUVALU", "UG"=>"UGANDA", "UA"=>"UKRAINE", "AE"=>"UNITED ARAB EMIRATES", "GB"=>"UNITED KINGDOM", "US"=>"UNITED STATES", "UM"=>"UNITED STATES MINOR OUTLYING ISLANDS", "UY"=>"URUGUAY", "UZ"=>"UZBEKISTAN", "VU"=>"VANUATU", "VE"=>"VENEZUELA", "VN"=>"VIET NAM", "VG"=>"VIRGIN ISLANDS, BRITISH", "VI"=>"VIRGIN ISLANDS, U.S.", "WF"=>"WALLIS AND FUTUNA", "EH"=>"WESTERN SAHARA", "YE"=>"YEMEN", "ZM"=>"ZAMBIA", "ZW"=>"ZIMBABWE");
		?>
		<select id="countrycode" name="countrycode">
		<?php
		foreach($_countrycode as $key => $val){
			$_str = ucwords(strtolower($val));
			$selected = '';
			if($key=="US"){ $selected = 'selected'; }
		?>
		<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $_str;?></option>
		<?php }?>
		</select>		</td>
	</tr>
	<tr>
		<td align="right"><br><b>订单信息:</b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right">订单编号：</td>
		<td align="left"><?php echo (int)$_GET['order_id'];?><?php if($body!=""){ echo '('.$body.')';}?></td>
	</tr>
	<?php
	for($i=0, $n=sizeof($orderTotalInfo); $i<$n; $i++){
		if($orderTotalInfo[$i]['class']=='ot_total'){
			$ot_total=$orderTotalInfo[$i]['value'];
		}
	?>
	<tr>
		<td align="right"><?php echo $orderTotalInfo[$i]['title']?></td>
		<td align="left"><?php echo $orderTotalInfo[$i]['text']?></td>
	</tr>
	<?php }?>
	<tr>
		<td align="right">支付金额：</td>
		<td align="left"><input name="amount" type="text" value="<?php echo $_POST['amount'];?>" size="7" maxlength="30" style="ime-mode: disabled; " onkeyup="checkValue(this.value)"> USD</td><!-- onkeyup="checkValue(this.value)" -->
	</tr>
	<tr>
	<td />
		<td align="left"><input type="button" value="确定付款" onClick="this.form.submit(); this.disabled=true; this.value=&quot;正在付款，请稍后&hellip;&hellip;&quot;" id="confirm_button" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center" ><span class="font_size14"><strong><font color="#FF0000">付款后请耐心等待不要刷新页面，以免重复支付；付款完成后，请不要即时关掉付款完成页面，停留一分钟后再关闭</font></strong></span>	</td>	</tr>
	<?php if($_COOKIE['login_id']){?>
	<tr>
	<td colspan="2" align="center">
	<iframe style="display:block; border:none;" width="720" height="85" src="/auto_login_url.php?order_id=<?php echo (int)$_GET['order_id'];?>"></iframe>
	</td>
	</tr>
	<?php }?>
</table>
</form>
	</div>
</div>
<script type="text/javascript" language="javascript">
function checkValue(value){
	if(parseFloat(value)>=<?php printf("%1\$.2f",isset($down_need)?$down_need:($ot_total/$human_num));?>){
		document.getElementById('confirm_button').disabled=false;
	}else{
		document.getElementById('confirm_button').disabled=true;
		}
}

</script>
</body>
</html>