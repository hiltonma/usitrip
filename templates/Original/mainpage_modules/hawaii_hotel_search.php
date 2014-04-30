<script type="text/javascript">
function cearch_hotel_top(){
	var from = document.getElementById('hawaii_search_form');
	if(from.elements['date_free_start'].value.search(/^\d{4}-\d{2}-\d{2}$/) == -1){
		alert('<?php echo db_to_html('请选择正确的入住日期')?>');
		return false;
	}
	if(from.elements['date_frees_end'].value.search(/^\d{4}-\d{2}-\d{2}$/) == -1){
		alert('<?php echo db_to_html('请选择正确的离店日期')?>');
		return false;
	}
	
	var Hotel_Cearch_Results = document.getElementById('hawaii_hotel_results');
	Hotel_Cearch_Results.innerHTML = '<img alt="Please wait..." src="image/loading.gif" />';
	
	var url = url_ssl("<?php echo tep_href_link_noseo('ajax_hawaii_hotel_search_results.php','action=process') ?>");
	var aparams=new Array();

	for(i=0; i<from.length; i++){
		var sparam=encodeURIComponent(from.elements[i].name);
		sparam+="=";
		if(from.elements[i].type=="radio"){
			var a = a;
			if(from.elements[i].checked){
				a = from.elements[i].value;
			}
			sparam+=encodeURIComponent(a); 
		}else{
			sparam+=encodeURIComponent(from.elements[i].value);
		}
		aparams.push(sparam);
	}
	var post_str = aparams.join("&");
	post_str += "&ajax=true";
	
	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			//alert(ajax.responseText);
			Hotel_Cearch_Results.innerHTML = ajax.responseText;
		}
	}
}

</script>
<link rel="stylesheet" type="text/css" href="spiffyCal/spiffyCal_v2_1.20100728.min.css" />
<script type="text/javascript" src="spiffyCal/spiffyCal-v2-1-2009-05-11-min.js"></script>
<div class="hawaii_hotel_search">
<form action="" method="post" id="hawaii_search_form" name="hawaii_search_form" onSubmit="cearch_hotel_top(); return false;">
<input name="search_categories_id" type="hidden" value="<?php echo $cPathOnly?>">
<div class="hawaii_hotel_search_title dazi" ><?php echo db_to_html('夏威夷<br>酒店搜索')?></div>
<div class="hawaii_search_input">
<p><?php echo db_to_html('入住日期')?></p>
<script type="text/javascript">
<!--
	var DateFreeStartTop = new ctlSpiffyCalendarBox("DateFreeStartTop", "hawaii_search_form", "date_free_start","btnDateFreeSearch1","",scBTNMODE_CUSTOMBLUE);
	DateFreeStartTop.writeControl(); DateFreeStartTop.dateFormat="yyyy-MM-dd";
-->
</script>
</div>

<div class="hawaii_search_input"><p><?php echo db_to_html('离店时间')?></p>
<script type="text/javascript">
<!--
	var DateFreeEndTop = new ctlSpiffyCalendarBox("DateFreeEndTop", "hawaii_search_form", "date_frees_end","btnDateFreeSearch2","",scBTNMODE_CUSTOMBLUE);
	DateFreeEndTop.writeControl(); DateFreeEndTop.dateFormat="yyyy-MM-dd";
-->
</script>
</div>
<div class="hawaii_search_input" >
<p><?php echo db_to_html('价格')?></p>
  <?php
  $hotel_price_array = array();
  $hotel_price_array[] = array('id'=>'0','text'=>db_to_html('不限'));
  $hotel_price_array[] = array('id'=>'1,100','text'=>db_to_html('$100以下'));
  $hotel_price_array[] = array('id'=>'100,200','text'=>db_to_html('$100至$200'));
  $hotel_price_array[] = array('id'=>'200,400','text'=>db_to_html('$200至$400'));
  $hotel_price_array[] = array('id'=>'400,600','text'=>db_to_html('$400至$600'));
  $hotel_price_array[] = array('id'=>'600,10000','text'=>db_to_html('$600以上'));
  echo tep_draw_pull_down_menu('hotel_price_range',$hotel_price_array,'',' style="width:110px;"');
  ?>
</div><div class="hawaii_search_input" style="padding:23px 0px 0px 15px;">
<?php echo tep_image_submit('hawaii_hotel_search_button.gif', db_to_html('搜索酒店'),' style="border:0px; width:80px; height:20px;" ')?>
</div>
</form>
</div>
<div id="hawaii_hotel_results">
</div>