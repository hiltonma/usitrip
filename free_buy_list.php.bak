<?php
require('includes/application_top.php');
//$_POST: start_date to array; end_date to array; date_num; hotel_id to array; tours_id to array; adult_num; child_num
if($_GET['action']=='process'){
	$start_dates = $_POST['start_dates'];
	$end_dates = $_POST['end_dates'];
	$hotel_ids = $_POST['hotel_ids'];
	$tours_ids = $_POST['tour_ids'];
	//print_r($_POST);
	//exit;
	if(count($start_dates)<1 || count($start_dates)!= count($end_dates)){
		echo 'Error:start_dates Or end_dates';
		exit;
	}
	if(count($hotel_ids)<1 || count($hotel_ids)!= count($tours_ids)){
		echo 'Error:hotel_ids Or tours_ids';
		exit;
	}
	if(count($start_dates)!=count($hotel_ids)){
		echo 'Error:start_dates != hotel_ids';
		exit;
	}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET?>">
<title><?php echo db_to_html('走四方网--批量购买');?></title>

<script type="text/javascript" src="global.js.php?<?php echo (isset($language) && $language!=''?'&language='.($language=='schinese'?'sc':'tw'):'').(isset($_GET['osCsid']) && $_GET['osCsid']!=''?'&osCsid='.$_GET['osCsid']:'')?>"></script>
<meta http-equiv="refresh" content="50;URL=<?php echo tep_href_link('shopping_cart.php');?>" />
</head>

<body><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle"><img src="image/ajax-loading.gif" alt="Landing..." width="31" height="31">&nbsp; <?php echo db_to_html('请在提交预定，请稍候……')?></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
	<?php
	for($i=0; $i<count($start_dates); $i++){
	?>
	<form action="<?php echo tep_href_link('shopping_cart.php','products_id='.$tours_ids[$i].'&action=add_product');?>" method="post" name="form_tours_<?php echo $i?>" id="form_tours_<?php echo $i?>">
	<?php 
	if($_GET['osCsid']){
		echo '<input name="osCsid" type="hidden" value="'.$_GET['osCsid'].'">';
	}
	?>

	  <input name="products_id" type="<?php echo "hidden"?>" value="<?php echo $tours_ids[$i]?>">
	  <input name="availabletourdate" type="<?php echo "hidden"?>" value="<?php echo $start_dates[$i]?>::##!!!">
	  <input name="numberOfRooms" type="<?php echo "hidden"?>" value="1">
      <input name="room-0-adult-total" type="<?php echo "hidden"?>" value="<?php echo (int)$_POST['adult_num'];?>">
	  <input name="room-0-child-total" type="<?php echo "hidden"?>" value="<?php echo (int)$_POST['child_num'];?>">
	  <?php //无结伴拼房?>
	  <input name="travel_comp" type="<?php echo "hidden"?>" value="0">
	  
	  <?php
	  //住什么酒店
	  $hotel_sql = tep_db_query('SELECT products_name FROM `products_description` WHERE `products_id` = "'.(int)$hotel_ids[$i].'" AND language_id="'.(int)$languages_id.'" Limit 1 ');
	  $hotel_row = tep_db_fetch_array($hotel_sql);
	  //echo $hotel_row['products_name'];
	  
	  $options_ids_str = '2,18,19,48,52,59';	//酒店
	  $prod_option_sql = tep_db_query('SELECT pa.options_id, pov.products_options_values_id FROM `products_attributes` pa, `products_options_values_to_products_options` po, `products_options_values` pov 
	  WHERE pa.products_id ="'.(int)$tours_ids[$i].'" AND pa.options_id in('.$options_ids_str.') AND po.products_options_id = pa.options_id AND pov.products_options_values_id = po.products_options_values_id  AND pov.products_options_values_name ="'.$hotel_row['products_name'].'" AND pov.language_id ="'.(int)$languages_id.'" 
	  Group By pov.products_options_values_id Limit 1');
	  
	  while($prod_option_rows = tep_db_fetch_array($prod_option_sql)){
		  echo '<input name="id['.$prod_option_rows['options_id'].']" type="hidden" value="'.$prod_option_rows['products_options_values_id'].'" >';
	  }
	  //id[52]
	  ?>
	  <input type="submit" name="Submit" value="Submit" style="display:<?= 'none';?>">
	</form>
    <?php
	}
	
	//检查是否还需要订酒店
	?>
	</td>
  </tr>
</table>


<script type="text/javascript">
var Cart_Sum = 0;

<?php
	$p=array('/&amp;/','/&quot;/');
	$r=array('&','"');
	for($i=0; $i<count($start_dates); $i++){
?>
var ajax_<?php echo $i?> = false;
if(window.XMLHttpRequest) {
	 ajax_<?php echo $i?> = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax_<?php echo $i?> = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax_<?php echo $i?> = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax_<?php echo $i?>) {
	window.alert("不能创建XMLHttpRequest对象实例.");
}


function form_tours_<?php echo $i?>_submit(){
	var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('shopping_cart.php','products_id='.$tours_ids[$i].'&action=add_product')) ?>";
	var from_<?php echo $i?> = document.getElementById('form_tours_<?php echo $i?>');
	var aparams=new Array();

	for(i=0; i<from_<?php echo $i?>.length; i++){
		var sparam=encodeURIComponent(from_<?php echo $i?>.elements[i].name);
		sparam+="=";
		if(from_<?php echo $i?>.elements[i].type=="radio"){
			var a = a;
			if(from_<?php echo $i?>.elements[i].checked){
				a = from_<?php echo $i?>.elements[i].value;
			}
			sparam+=encodeURIComponent(a); 
		}else{
			sparam+=encodeURIComponent(from_<?php echo $i?>.elements[i].value);
		}
		aparams.push(sparam);
	}
	var post_str = aparams.join("&");
	post_str += "&ajax=true";

	ajax_<?php echo $i?>.open("POST", url, true); 
	ajax_<?php echo $i?>.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax_<?php echo $i?>.send(post_str);

	ajax_<?php echo $i?>.onreadystatechange = function() { 
		if (ajax_<?php echo $i?>.readyState == 4 && ajax_<?php echo $i?>.status == 200 ) { 
			//alert(ajax_<?php echo $i?>.responseText);
			if(ajax_<?php echo $i?>.responseText.search(/(\[Cart_Sum\]\d+\[\/Cart_Sum\])/g)!=-1){
				Cart_Sum += ajax_<?php echo $i?>.responseText.replace(/(.*\[Cart_Sum\])|(\[\/Cart_Sum\].*[:space:]*.*)/g,'');
			}
		}
	}
}

form_tours_<?php echo $i?>_submit();

	<?php
	}
	?>

setTimeout("",0);
	alert("<?php echo db_to_html('OK，到购物车看看！')?>");
if(Cart_Sum>0){
	document.location = "<?php echo tep_href_link('shopping_cart.php');?>";
}

</script>


</body>
</html>
<?php
}
?>