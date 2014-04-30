<?php
/*require('includes/application_top.php');
if($customer_id!="xmzhh2000@126.com"){
	print_r($_SESSION);
	die('Plx use xmzhh2000@126.com to Login !');
}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
</head>
<body>
*/?>
<?php
// get cus data
$where_exc = ' ';
//$where_exc .= ' AND ( customers_email_address ="xmzhh2000@gmail.com" || customers_email_address ="veraeland@gmail.com") ';
if((int)$customer_id){
	$sql = tep_db_query('SELECT customers_id,customers_firstname, customers_email_address FROM `customers` WHERE customers_id = "'.(int)$customer_id.$where_exc.'" Order By customers_id limit 1');
}
/*else{
	$sql = tep_db_query('SELECT customers_id,customers_firstname, customers_email_address FROM `customers` WHERE customers_id > 0 '.$where_exc.' Order By customers_id limit 1');
}*/

$row = tep_db_fetch_array($sql);
if(!(int)$row['customers_id']){
	die();
}

$email_text = '
<div style="clear: both; width: 600px; height:1000px; margin: 0px; padding:0px; background-color:#FFFFFF; font-size:12px; color:#223C6A; " ><form id="form1" name="form1" method="post" action="'.HTTP_SERVER.'/customers-feedback.php" target="_blank"><input name="customers_email_address" type="hidden" id="customers_email_address" value="'.$row['customers_email_address'].'" /><div style="width: 600px; float: none; margin-right: auto; margin-left: auto; clear: both; display: inline;"><div style="float:left; width:600px;"> <img src="'.HTTP_SERVER.'/image/newsletter2_r2_c2.jpg" width="600" height="13"/></div><div style="background-color:#223C6A; float :left; width:600px;"><div style="margin-bottom:4px; margin-left:3px; margin-top:4px; float:left"><img src="'.HTTP_SERVER.'/image/logo.gif" width="230" height="54" /></div></div></div><table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#108BCD" style="margin-top:1px; color:#FFFFFF; float:left; font-size:12px;"><tr><td width="391" style="padding-left:3px;" ><p style="margin:0px;"><b>尊敬的'.$row['customers_firstname'].'您好：</b></p><p style="margin:0px;">为了感谢您对走四方网（'.$_SERVER['HTTP_HOST'].'）的支持，同时不断地提<br />高走四方网的服务水平和经营质量，我们特地推出新春客户调查回馈计划。 </p></td><td width="209" ><img src="'.HTTP_SERVER.'/image/banner_huikui.jpg" width="209" height="71" /></td></tr></table><div style="font-family:\'宋体\'; font-size: 12px; font-weight: bold; text-decoration: none; background-color: # FDD01B; width: 597px; padding: 5px 0px 3px 3px; float: left; margin-top:5px; color:#223C6A; margin-bottom: 4px;">只要参加我们的调查回馈计划，您就可以获得走四方网提供的20美元的旅游代金券。 </div><p style="width:100%; float:left; line-height:16px; color:#223C6A; margin:0px; padding:3px 0px 3px 0px;">此代金券可以在您预订我们网站产品的时候使用。当您在预订产品的时候，您可以填写我们给您提供的代金券代码，该代金券价值<strong>20</strong>美元。 <br />  此调查持续时间为<strong>2008年12月8日</strong>至<strong>2009年2月8日</strong>。 </p><table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%; float:left; margin-bottom:5px;">   <tr>     <td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; padding:4px 0px 4px 3px; color:#223C6A; font-size:12px;"><b>详细调查如下：</b></td>   </tr>   <tr>     <td style="border:1px #FFCC00 solid; border-top:0px; padding:2px 0px 2px 3px;">     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">   <tr>     <td width="12%" height="32" style="font-size:12px; color:#223C6A;">1、您的年龄</td>     <td width="4%" valign="middle">       <label>         <input type="radio" name="tours_age" id="tours_age0" value="18" />         </label> </td>     <td width="10%" style="font-size:12px; color:#223C6A;">18岁以下</td>     <td width="4%">       <label>         <input type="radio" name="tours_age" id="tours_age1" value="18-30" />         </label>     </td>     <td width="8%" style="font-size:12px; color:#223C6A;">18-30</td>     <td width="4%">       <label>         <input type="radio" name="tours_age" id="tours_age2" value="31-45" />         </label>     </td>     <td width="8%" style="font-size:12px; color:#223C6A;">31-45</td>     <td width="4%">       <label>         <input type="radio" name="tours_age" id="tours_age3" value="45-60" />         </label>     </td>     <td width="8%" style="font-size:12px; color:#223C6A;">45-60</td>     <td width="4%">       <label>         <input type="radio" name="tours_age" id="tours_age4" value="60" />         </label>     </td>     <td width="34%" style="font-size:12px; color:#223C6A;">60以上</td>   </tr></table>  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr bgcolor="#FFFBEB">
<td width="12%" height="32" style="font-size:12px; color:#223C6A;">2、您的性别</td>
<td width="4%">
<label>
<input type="radio" name="tours_gender" id="tours_gender0" value="m" />
</label>

</td>
<td width="10%" style="font-size:12px; color:#223C6A;">男</td>
<td width="4%">
<label>
<input type="radio" name="tours_gender" id="tours_gender1" value="f" />
</label>

</td>
<td width="70%" style="font-size:12px; color:#223C6A;">女</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr>
<td width="13%" height="26" style="font-size:12px; color:#223C6A;">3、您的职业</td>
<td width="87%">
<label>
<select name="tours_job" id="tours_job" style="font-size:12px; color:#223C6A;">
<option value="0">自由职业</option>
<option value="1">互联网</option>
<option value="2">IT</option>
<option value="3">管理</option>
<option value="4">学生</option>
<option value="5">机械</option>
<option value="6">化工</option>
<option value="7">能源</option>
<option value="8">医疗</option>
<option value="9">教育出版</option>
<option value="10">其他</option>
</select>
</label>

</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr bgcolor="#FFFBEB">
<td width="17%" height="32" style="font-size:12px; color:#223C6A;">4、您的旅游爱好</td>
<td width="83%">
<label>
<select name="tours_like" id="tours_like" style="font-size:12px; color:#223C6A;">
<option value="0">自由行</option>
<option value="1">自驾游</option>
<option value="2">跟团</option>
<option value="3">直升机游</option>
<option value="4">飞机游</option>
<option value="5">邮轮</option>
<option value="6">城市游</option>
<option value="7">自然景观</option>
<option value="8">其他</option>
</select>
</label> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%; ">
<tr>
<td height="32" colspan="13" style="font-size:12px; color:#223C6A;">5、您是通过何种方式知道本网站的？ </td>
</tr>
<tr>
<td width="3%" height="28" style="font-size:12px; color:#223C6A;">&nbsp;</td>
<td width="4%" valign="middle">
<label>
<input type="radio" name="tours_from" id="tours_from0" value="google" />
</label>
</td>
<td width="13%" style="font-size:12px; color:#223C6A;">Google谷歌</td>
<td width="4%">
<label>
<input type="radio" name="tours_from" id="tours_from1" value="baidu" />
</label>
</td>
<td width="13%" style="font-size:12px; color:#223C6A;">Baidu百度</td>
<td width="4%">
<label>
<input type="radio" name="tours_from" id="tours_from2" value="yahoo" />
</label>
</td>
<td width="12%" style="font-size:12px; color:#223C6A;">Yahoo雅虎</td>
<td width="4%">
<label>
<input type="radio" name="tours_from" id="tours_from3" value="bbs" />
</label>
</td>
<td width="11%" style="font-size:12px; color:#223C6A;">论坛</td>
<td width="4%">
<label>
<input type="radio" name="tours_from" id="tours_from4" value="friend" />
</label>
</td>
<td width="12%" style="font-size:12px; color:#223C6A;">朋友推荐</td>
<td width="4%" style="font-size:12px; color:#223C6A;">
<label>
<input type="radio" name="tours_from" id="tours_from4" value="other" />
</label>
</td>
<td width="12%" style="font-size:12px; color:#223C6A;">其他</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr bgcolor="#FFFBEB">
<td width="22%" height="32" style="font-size:12px; color:#223C6A;">6、您对本站服务的评价</td>
<td width="4%" valign="middle">
<label>
<input type="radio" name="ServicesEval" id="ServicesEval0" value="0" />
</label>
</td>
<td width="8%" style="font-size:12px; color:#223C6A;">优秀</td>
<td width="4%">
<label>
<input type="radio" name="ServicesEval" id="ServicesEval1" value="1" />
</label>
</td>
<td width="8%" style="font-size:12px; color:#223C6A;">良好</td>
<td width="4%">
<label>
<input type="radio" name="ServicesEval" id="ServicesEval2" value="2" />
</label>
</td>
<td width="9%" style="font-size:12px; color:#223C6A;">中等</td>
<td width="4%">
<label>
<input type="radio" name="ServicesEval" id="ServicesEval3" value="3" />
</label>
</td>
<td width="8%" style="font-size:12px; color:#223C6A;">及格</td>
<td width="4%">
<label>
<input type="radio" name="ServicesEval" id="ServicesEval4" value="4" />
</label>
</td>
<td width="7%" style="font-size:12px; color:#223C6A;">差</td>
<td width="4%" style="font-size:12px; color:#223C6A;">
<label>
<input type="radio" name="ServicesEval" id="ServicesEval5" value="5" />
</label>
</td>
<td width="14%" style="font-size:12px; color:#223C6A;">很差</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr>
<td width="34%" height="26" style="font-size:12px; color:#223C6A;">7、您最近几年平均每年出行几次？ </td>
<td width="66%">
<label>
<select name="tours_number" id="tours_number" style="font-size:12px; color:#223C6A;">
<option value="0">0</option>
<option value="0-1">0~1次</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5次以上</option>
</select>
</label>

</td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="width:100%;">
<tr>
<td height="26" nowrap="nowrap" style="font-size:12px; color:#223C6A;">8、您觉得我们需要增加哪些方面的业务/服务？ </td>
<td><label>
<input type="radio" name="tours_add_server" value="0" />机票 
</label></td>
<td>
<input type="radio" name="tours_add_server" value="1" />签证 
</td>
<td>
<input type="radio" name="tours_add_server" value="2" />租车
</td>
<td><input type="radio" name="tours_add_server" value="3" />其他</td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFBEB" style="width:100%; ">
<tr>
<td height="32" colspan="11" style="font-size:12px; color:#223C6A;">9、您推荐本站产品给其他朋友没？
</td>
</tr>
<tr>
<td width="4%" height="20" style="font-size:12px; color:#223C6A;">&nbsp;</td>
<td width="3%" valign="middle"><span style="margin:0px;">
<input type="radio" name="tours_i_rec" id="tours_i_rec0" value="0" />
</span></td>
<td width="6%" style="font-size:12px; color:#223C6A;">没有</td>
<td width="4%"><span style="margin:0px;">
<input type="radio" name="tours_i_rec" id="tours_i_rec1" value="1" />
</span></td>
<td width="11%" style="font-size:12px; color:#223C6A;">准备推荐</td>
<td width="4%"><span style="margin:0px;">
<input type="radio" name="tours_i_rec" id="tours_i_rec2" value="2" />
</span></td>
<td width="12%" style="font-size:12px; color:#223C6A;">推荐了1位</td>
<td width="4%"><span style="margin:0px;">
<input type="radio" name="tours_i_rec" id="tours_i_rec3" value="3" />
</span></td>
<td width="12%" style="font-size:12px; color:#223C6A;">推荐了2位</td>
<td width="4%"><span style="margin:0px;">
<input type="radio" name="tours_i_rec" id="tours_i_rec4" value="4" />
</span></td>
<td width="36%" style="font-size:12px; color:#223C6A;">推荐了3位及以上</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr>
<td height="26" style="font-size:12px; color:#223C6A;">10、您一般花费多少在一次旅游行程上呢？ </td>
<td>
<span style="font-size:12px; color:#223C6A;">
<input type="radio" name="tours_consumer" value="0" />$100以下
</span></td>
<td><span style="font-size:12px; color:#223C6A;">
<input type="radio" name="tours_consumer" value="1" />$100-$500
</span></td>
<td><span style="font-size:12px; color:#223C6A;">
<input type="radio" name="tours_consumer" value="2" />
$500-$1000</span></td>
<td><span style="font-size:12px; color:#223C6A;">
<input type="radio" name="tours_consumer" value="3" />$1000以上
</span></td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFBEB" style="width:100%; ">
<tr>
<td height="32" colspan="11" style="font-size:12px; color:#223C6A;">11、您在旅游过程中，更在乎下面中的哪一样呢？ </td>
</tr>
<tr>
<td width="4%" height="20" style="font-size:12px; color:#223C6A;">&nbsp;</td>
<td width="4%" valign="middle"><span style="margin:0px;">
<input type="radio" name="tours_focus" id="radio9" value="0" />
</span></td>
<td width="7%" style="font-size:12px; color:#223C6A;">酒店</td>
<td width="3%"><span style="margin:0px;">
<input type="radio" name="tours_focus" id="radio10" value="1" />
</span></td>
<td width="8%" style="font-size:12px; color:#223C6A;">行程</td>
<td width="4%"><span style="margin:0px;">
<input type="radio" name="tours_focus" id="radio11" value="2" />
</span></td>
<td width="7%" style="font-size:12px; color:#223C6A;">价格</td>
<td width="3%"><span style="margin:0px;">
<input type="radio" name="tours_focus" id="radio12" value="3" />
</span></td>
<td width="7%" style="font-size:12px; color:#223C6A;">服务</td>
<td width="4%"><span style="margin:0px;">
<input type="radio" name="tours_focus" id="radio13" value="4" />
</span></td>
<td width="49%" style="font-size:12px; color:#223C6A;">机票</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%; ">
<tr>
<td height="32" colspan="15" style="font-size:12px; color:#223C6A;">12、你希望本站除了专业的旅游产品外，还可以添加哪些方面内容呢？ </td>
</tr>
<tr>
<td width="1%" height="20" style="font-size:12px; color:#223C6A;">&nbsp;</td>
<td width="3%" valign="middle">
<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="1" />
</label>
</td>
<td width="10%" style="font-size:12px; color:#223C6A;">签证常识</td>
<td width="4%">
<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="2" />
</label>
</td>
<td width="10%" style="font-size:12px; color:#223C6A;">旅游常识</td>
<td width="4%"><span style="margin:0px;">

<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="3" />
</label>

</span></td>
<td width="9%" style="font-size:12px; color:#223C6A;">旅游资讯</td>
<td width="4%"><span style="margin:0px;">

<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="4" />
</label>

</span></td>
<td width="10%" style="font-size:12px; color:#223C6A;">同行好友</td>
<td width="3%"><span style="margin:0px;">

<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="5" />
</label>

</span></td>
<td width="11%" style="font-size:12px; color:#223C6A;">更多图片</td>
<td width="4%" style="font-size:12px; color:#223C6A;">
<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="6" />
</label>
</td>
<td width="10%" style="font-size:12px; color:#223C6A;">更多视频</td>
<td width="4%" style="font-size:12px; color:#223C6A;">
<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="7" />
</label>
</td>
<td width="13%" style="font-size:12px; color:#223C6A;">开放评论</td>
</tr>
<tr>
<td height="20" style="font-size:12px; color:#223C6A;">&nbsp;</td>
<td valign="middle">
<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="8" />
</label>
</td>
<td style="font-size:12px; color:#223C6A;">自由行</td>
<td>
<label>
<input name="tours_add_prod[]" type="checkbox" id="tours_add_prod[]" value="9" />
</label>
</td>
<td style="font-size:12px; color:#223C6A;">其他</td>

<td colspan="10">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFBEB" style="width:100%;">
<tr >
<td height="32" colspan="5" style="font-size:12px; color:#223C6A;">13、您觉得本站需要在哪些方面进行改进？</td>
</tr>
<tr >
<td height="32" style="font-size:12px; color:#223C6A;"><input type="radio" name="tours_site_improve" value="0" />
页面布局</td>
<td style="font-size:12px; color:#223C6A;"><input type="radio" name="tours_site_improve" value="1" />
导航设计</td>
<td style="font-size:12px; color:#223C6A;"><input type="radio" name="tours_site_improve" value="2" />
搜索引擎</td>
<td style="font-size:12px; color:#223C6A;"><input type="radio" name="tours_site_improve" value="3" />
客服质量</td>
<td style="font-size:12px; color:#223C6A;"><input type="radio" name="tours_site_improve" value="4" />
其他</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr >
<td height="32" style="font-size:12px; color:#223C6A;">14、您的电话号码：</td>
<td>
<label>
<input name="tours_phone" type="text" id="tours_phone" maxlength="11"  size="30" height="20px;" style="border:1px #E4E4E4 solid; font-size:12px; color:#223C6A;" />
</label> </td>
</tr>
<tr >
<td height="32" style="font-size:12px; color:#223C6A;">15、再次输入您的电话号码：</td>
<td><input name="tours_phone_re" type="text" id="tours_phone_re" maxlength="11"  size="30" height="20px;" style="border:1px #E4E4E4 solid; font-size:12px; color:#223C6A;" /></td>
</tr>

</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;">
<tr bgcolor="#FFFBEB" >
<td height="32" style="font-size:14px; font-weight: bold;"><input style="border: 1px solid #81A7E8; cursor:hand; font-size:14px; font-weight: bold;" alt="提交调查" title="提交调查" name="ToursSubmitBotton" type="submit" value="提交调查" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="border: 1px solid #81A7E8;" name="ToursReBotton" type="reset" id="ToursReBotton" value="重置" />
</td>
<td width="68%">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
<div style="float:left; width: 600px; background-color: #223C6A;"><p style="font-family: Tahoma; font-size: 11px; color: #FFF; text-decoration: none; text-align:right; line-height:16px;  margin-right:3px; margin-top:7px;"><a href="'.HTTP_SERVER.'" style="color:#fff; text-decoration:underline;">208.109.123.18 </a></p><img src="'.HTTP_SERVER.'/image/newsletter2_r2_c2_1.jpg" width="600" height="13" border="0" /></div>
</form>
</div>';

$to_email_address[0] = $row['customers_email_address'];
$to_name = db_to_html($row['customers_firstname']);
$email_text = db_to_html(preg_replace('[\n\r\t]',' ',$email_text));
$email_subject =db_to_html('usitrip 走四方网客户回馈调查！');
$from_email_name ='usitrip';
$from_email_address = 'service@usitrip.com';
for($i=0; $i<count($to_email_address); $i++){
	@tep_mail($to_name, $to_email_address[$i], $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
	//echo $to_email_address[$i].'<br />';
}

//echo $email_text;
?>
<?php /*
</body></html>
<script type="text/JavaScript">
<!--
location = 'send_customers_mail.php?language=sc&customers_id='+<?php echo $row['customers_id']?>;
//-->
</script>
*/
?>