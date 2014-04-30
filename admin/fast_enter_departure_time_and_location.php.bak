<?php
/*
某个团的所有上车地址信息
*/
//本程序已经关闭，请看tour_provider_regions.php即可。
die("close");


require('includes/application_top.php');

$error_msn = '';

switch($_GET['action']){
	case 'add_or_update':
		$products_id = (int)$_POST['products_id'];
		$departure_region = tep_db_prepare_input($_POST['departure_region']);
		$departure_address = tep_db_prepare_input($_POST['departure_address']);
		$departure_time = tep_db_prepare_input($_POST['departure_time']);
		$map_path = tep_db_prepare_input($_POST['map_path']);
		$departure_full_address = tep_db_prepare_input($_POST['departure_full_address']);
		$departure_tips = tep_db_prepare_input($_POST['departure_tips']);
		$products_hotels_ids = tep_db_prepare_input($_POST['products_hotels_ids']);
		$data_array = array('products_id'=>$products_id,
							'departure_region'=>ajax_to_general_string($departure_region),
							'departure_address'=>ajax_to_general_string($departure_address),
							'departure_time'=>$departure_time,
							'map_path'=>$map_path,
							'departure_full_address'=>ajax_to_general_string($departure_full_address),
							'departure_tips'=>ajax_to_general_string($departure_tips),
							'products_hotels_ids'=>$products_hotels_ids);
		if((int)$_POST['departure_id']){	//更新
			tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $data_array, 'update', 'departure_id='.(int)$_POST['departure_id']);
			$js_str = '[JS]';
			//$js_str.= 'alert("'.general_to_ajax_string("数据更新成功！").'");';
			$js_str .= 'show_updatd_table_tr("tr_'.$departure_id.'","'.tep_db_output($departure_region).'","'.$departure_time.'","'.tep_db_output($departure_address).'","'.tep_db_output($departure_full_address).'","'.tep_db_output($products_hotels_ids).'","'.tep_db_output($map_path).'","'.tep_db_output($departure_tips).'");';
			$js_str.= '[/JS]';
			
		}else{
			//增加
			tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $data_array);
			$departure_id = tep_db_insert_id();
			$js_str = '[JS]';
			$js_str .= 'add_table_tr("tr_'.$departure_id.'","'.tep_db_output($departure_region).'","'.$departure_time.'","'.tep_db_output($departure_address).'","'.tep_db_output($departure_full_address).'","'.tep_db_output($products_hotels_ids).'","'.tep_db_output($map_path).'","'.tep_db_output($departure_tips).'");';
			$js_str .= '$("#msn").html("<span style=color:#0C0;>'.general_to_ajax_string("数据添加成功！").'</span>"); ';
			$js_str .= '$("#msn").hide(0); ';
			$js_str .= '$("#msn").fadeIn(500); ';
			$js_str .= 'window.setTimeout(\'$("#msn").fadeOut(1000);\',3000);';
			$js_str .= '$(\'#form_add input[type="text"]\').val("");';	//清除表单旧数据
			$js_str .= '$(\'#form_add textarea\').val("");';	//清除表单旧数据
			$js_str.= '[/JS]';
			
		}
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo ajax_to_general_string($js_str);
		exit;
		
	break;
	case 'DelConfirmed':
		tep_db_query('DELETE FROM '.TABLE_PRODUCTS_DEPARTURE.' WHERE departure_id="'.(int)$_GET['departure_id'].'" ');
		exit;
	break;
	
}

//设置选择菜单的默认内容 start
$title_name = "Fast enter Departure Time and Location:";
$prod_sql = tep_db_query('SELECT products_id, products_model, agency_id FROM `products` WHERE products_id="'.(int)$pID.'" ');
$prod_row = tep_db_fetch_array($prod_sql);
$title_name .= '<a href="'.tep_href_link('categories.php','cPath='.$cPath.'&pID='.$prod_row['products_id'].'&action=new_product').'">['.$prod_row['products_model'].']</a>';
$options_region = '';
$region_name_display = '';
$regionquery = 'select * from '.TABLE_TOUR_PROVIDER_REGIONS.' where FIND_IN_SET("'.(int)$prod_row['agency_id'].'", agency_ids) order by region , departure_time ';
$regionrow = tep_db_query($regionquery);

while($products_departure_result = tep_db_fetch_array($regionrow))
{
	if($region_name_display != $products_departure_result['region'])
	{
	$region_name_display = $products_departure_result['region'];
	$options_region .= '<option value="'.$region_name_display.'">'.$region_name_display.'</option>';
	}

}
$select_region = '<select name="departure_region">'.$options_region.'</select>';
//设置选择菜单的默认内容 end

//搜索
$where_exc = '';
//sql  
$sql_str = 'SELECT * FROM `products_departure` WHERE products_id="'.$pID.'" '.$where_exc.' Order By departure_region asc, departure_time asc, departure_address asc, departure_id DESC ';
//echo $sql_str;
//载入分页类
$news_query_numrows = 0;
$news_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $news_query_numrows);

$news_query = tep_db_query($sql_str);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
<script type="text/javascript">
<!--
function DelInfo(t_id){
	if(t_id<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这个记录吗？请谨慎操作。\t")==true){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link('fast_enter_departure_time_and_location.php','action=DelConfirmed'))?>&ajax=true&departure_id=")+t_id;
		$.get(url,'',function(data){
			$("#tr_"+t_id).fadeOut(500,back_function);
		});
		return false;
	}
}
function back_function(){
	//删除成功后的动作。
	//alert("数据删除成功！");
}

//在表格中新增加一行
function add_table_tr(tr_id,region,time,address,full_address,hotels_ids, map,tips){
	var d = new Date();
	var id = d.getTime();
	tr_id = tr_id;
	num_id = tr_id.replace(/tr_/,'');
	str ='';
	str+= '<tr class="dataTableRow" id="'+tr_id+'">';
	str+= '  <td class="dataTableContent" id="Region_'+num_id+'">'+region+'</td>';
	str+= '  <td height="25" class="dataTableContent" id="Time_'+num_id+'">'+time+'</td>';
	str+= '  <td class="dataTableContent" id="Location_'+num_id+'">'+address+'</td>';
	str+= '  <td class="dataTableContent" id="Address_'+num_id+'">'+full_address+'</td>';
	str+= '  <td class="dataTableContent" id="hotels_'+num_id+'">'+hotels_ids+'</td>';
	str+= '  <td class="dataTableContent" id="Map_'+num_id+'">'+map+'</td>';
	str+= '  <td class="dataTableContent" id="tips_'+num_id+'">'+tips+'</td>';
	str+= '  <td nowrap class="dataTableContent">';
	str+= '  [<a href="JavaScript:void(0);" onClick="ChangeTrContent(&quot;'+tr_id+'&quot;)">编辑</a>]&nbsp;&nbsp;[<a href="JavaScript:void(0);" onClick="DelInfo('+num_id+'); return false;">删除</a>]';
	str+= '  </td>';
	str+= '  </tr>';
	//str = "<tr id = '"+tr_id+"'><td width='30%'>re1</td><td width='30%'>re2</td><td width='30%'>re3</td></tr>";
	$('#tr_title').after(str);
	$('#'+tr_id).fadeOut(0);
	$('#'+tr_id).fadeIn(800);
	
}
//显示编辑成功后的表格行
function show_updatd_table_tr(tr_id,region,time,address,full_address,hotels_ids, map,tips){
	tr_tds_cache ="";
	tr_id = tr_id;
	num_id = tr_id.replace(/tr_/,'');
	str ='';
	str+= '  <td class="dataTableContent" id="Region_'+num_id+'">'+region+'</td>';
	str+= '  <td height="25" class="dataTableContent" id="Time_'+num_id+'">'+time+'</td>';
	str+= '  <td class="dataTableContent" id="Location_'+num_id+'">'+address+'</td>';
	str+= '  <td class="dataTableContent" id="Address_'+num_id+'">'+full_address+'</td>';
	str+= '  <td class="dataTableContent" id="hotels_'+num_id+'">'+hotels_ids+'</td>';
	str+= '  <td class="dataTableContent" id="Map_'+num_id+'">'+map+'</td>';
	str+= '  <td class="dataTableContent" id="tips_'+num_id+'">'+tips+'</td>';
	str+= '  <td nowrap class="dataTableContent">';
	str+= '  [<a href="JavaScript:void(0);" onClick="ChangeTrContent(&quot;'+tr_id+'&quot;)">编辑</a>]&nbsp;&nbsp;[<a href="JavaScript:void(0);" onClick="DelInfo('+num_id+'); return false;">删除</a>]';
	str+= '  </td>';
	$('#'+tr_id).html(str);
	$('#'+tr_id).fadeOut(0);
	$('#'+tr_id).fadeIn(800);
}

//提交表单时检查表单
function check_form(form_Obj){
	var error = false;
	if(form_Obj.elements['departure_time'].value.search(/^\d{2,2}:\d{2,2}(a|p)m$/) ==-1 ){
		error = true;
		alert("Please enter the time. format HH:MMam or HH:MMpm e.g 08:45am or 11:00pm");
		form_Obj.elements['departure_time'].focus();
		return false;
	}
	if(form_Obj.elements['departure_address'].value.length<2){
		error = true;
		alert("Please enter the Location address");
		form_Obj.elements['departure_address'].focus();
		return false;
	}
	if(form_Obj.elements['departure_full_address'].value.length<2){
		error = true;
		alert("Please enter the full address");
		form_Obj.elements['departure_full_address'].focus();
		return false;
	}
	if(form_Obj.elements['products_hotels_ids'].value!="" && form_Obj.elements['products_hotels_ids'].value.search(/^\d+(,\d+)*$/)==-1){
		error = true;
		alert('附近的酒店：可输入接送地酒店id，多个id用英文","号隔开如：45,713');
		form_Obj.elements['products_hotels_ids'].focus();
		return false;
	}
	if(error == false){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('fast_enter_departure_time_and_location.php','action=add_or_update')) ?>");
		var form_id = form_Obj.id;
		var success_msm = "";
		ajax_post_submit(url,form_id,success_msm,"", "");
	}
}

//删除表格行
function remove_tr(tr_id){
	$("#"+tr_id).remove();
}

var tr_tds_cache = "";
function ChangeTrContent(tr_id){	//点击编辑按钮时改变表格的内容
	if(tr_tds_cache!=""){
		alert("系统发现还有其它行在编辑，请先完成那些编辑操作再来编辑此行吧。");
		return false;
	}
	var idnum = tr_id.replace(/tr_/,'');
	new_html = '';
	new_html+= '  <td class="dataTableContent"><?= $select_region?></td>';
	new_html+= '  <td height="25" class="dataTableContent"><?= tep_draw_input_num_en_field('departure_time','','size="8"');?></td>';
	new_html+= '  <td class="dataTableContent"><?= tep_draw_input_field('departure_address','','size="30"');?></td>';
	new_html+= '  <td class="dataTableContent"><?= tep_draw_input_field('departure_full_address','','size="30"');?></td>';
	new_html+= '  <td class="dataTableContent"><?= tep_draw_input_num_en_field('products_hotels_ids','','size="30"');?></td>';
	new_html+= '  <td class="dataTableContent"><?= tep_draw_input_num_en_field('map_path','','size="30"');?></td>';
	new_html+= '  <td class="dataTableContent"><?= tep_draw_textarea_field('departure_tips','virtual',20,3);?></td>';
	new_html+= '  <td nowrap class="dataTableContent">';
	new_html+= '  <input name="products_id" type="hidden" value="<?= (int)$pID;?>"><input name="departure_id" type="hidden" value="'+idnum+'"><button type="submit">确定</button>&nbsp;<button name="button" type="button" onclick="cancel_edit(\''+tr_id+'\')">取消</button>';
	new_html+= '  </td>';
	
	departure_time_val = $('#Time_'+idnum).html();
	if(departure_time_val.search(/^\d{2,2}:\d{2,2}(a|p)m$/) ==-1 ){ departure_time_val='0'+departure_time_val; }
	departure_address_val = $('#Location_'+idnum).html();
	departure_full_address_val = $('#Address_'+idnum).html();
	products_hotels_ids_val = $('#hotels_'+idnum).html();
	map_path_val = $('#Map_'+idnum).html();
	departure_tips_val = $('#tips_'+idnum).html();
	departure_region_val = $('#Region_'+idnum).text();
	tr_tds_cache = $("#"+tr_id).html();
	$("#"+tr_id).html(new_html);
	
	$('#form_edit select[name="departure_region"]').val(departure_region_val);
	$('#form_edit input[name="departure_time"]').val(departure_time_val);
	$('#form_edit input[name="departure_address"]').val(departure_address_val);
	$('#form_edit input[name="departure_full_address"]').val(departure_full_address_val);
	$('#form_edit input[name="products_hotels_ids"]').val(products_hotels_ids_val);
	$('#form_edit input[name="map_path"]').val(map_path_val);
	$('#form_edit textarea[name="departure_tips"]').val(departure_tips_val);
}
function cancel_edit(tr_id){
	$("#"+tr_id).html(tr_tds_cache);
	tr_tds_cache = "";
}


jQuery(document).ready(function(){
	//点击新增按钮时的动作
	$("#add_button").click( function(){
		//add_table_tr();
	});
});
//-->
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $title_name?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Append Module </legend>
		  <?php echo tep_draw_form('form_add', 'fast_enter_departure_time_and_location.php', tep_get_all_get_params(array('page','y','x', 'action')), 'post', 'id="form_add" onsubmit="check_form(this); return false;" '); ?>				
		  <div style="font-size:12px; margin:5px;">
          <label>
          <?php

			echo 'Region: '.$select_region;
			?>
          </label>
          <label>Departure Time: <?= tep_draw_input_num_en_field('departure_time','','size="8"');?></label>
          <label>Location: <?= tep_draw_input_field('departure_address','','size="30"');?></label>
          <label>Address: <?= tep_draw_input_field('departure_full_address','','size="40"');?></label>
          </div>
          <div style="font-size:12px; margin:5px;">
          <label>附近的酒店: <?= tep_draw_input_num_en_field('products_hotels_ids','','size="40"');?></label>
          <label>Map Path: <?= tep_draw_input_num_en_field('map_path','','size="40"');?></label>
          </div>
          <div style="font-size:12px; margin:5px;">
          <label>Tips: <?= tep_draw_textarea_field('departure_tips','virtual',40,3);?></label>
		  </div>
          <div style="font-size:12px; margin:5px;">
          <button type="submit">确认增加</button>&nbsp;&nbsp;<button type="reset">取消</button>
          <input name="products_id" type="hidden" value="<?= $pID?>">
          <span id="msn" style="display:none; font-size:12px;"></span>
          </div>
		  
		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>
		  
		  <form id="form_edit" name="form_edit" method="post" action="" onSubmit="check_form(this); return false;">
		  <table width="100%" border="0" cellspacing="1" cellpadding="0" id="list_table">
			  <tr id="tr_title" class="dataTableHeadingRow">
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Region</td>
				<td align="center" nowrap="nowrap" class="dataTableHeadingContent">Departure Time</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Location</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Address</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">附近的酒店</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Map Path</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Tips</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">操作</td>
			  </tr>
			<?php
            $tr_departure_region = "";
			while($news_rows = tep_db_fetch_array($news_query)){
				$show_region_string = '<span style="color:#CCCCCC;">'.tep_db_output($news_rows['departure_region']).'</span>';
				if($tr_departure_region!=$news_rows['departure_region']){
					$tr_departure_region = $news_rows['departure_region'];
					$show_region_string = '<b>'.tep_db_output($news_rows['departure_region']).'</b>';
				}
			?>  
			  <tr class="dataTableRow" id="tr_<?= $news_rows['departure_id']?>">
			    <td class="dataTableContent" id="Region_<?= $news_rows['departure_id']?>"><?php echo $show_region_string;?></td>
			    <td height="25" class="dataTableContent" id="Time_<?= $news_rows['departure_id']?>"><?php echo tep_db_output($news_rows['departure_time'])?></td>
		        <td class="dataTableContent" id="Location_<?= $news_rows['departure_id']?>"><?php echo tep_db_output($news_rows['departure_address'])?></td>
		        <td class="dataTableContent" id="Address_<?= $news_rows['departure_id']?>"><?php echo tep_db_output($news_rows['departure_full_address'])?></td>
		        <td class="dataTableContent" id="hotels_<?= $news_rows['departure_id']?>"><?php echo tep_db_output($news_rows['products_hotels_ids'])?></td>
			    <td class="dataTableContent" id="Map_<?= $news_rows['departure_id']?>"><?php echo tep_db_output($news_rows['map_path'])?></td>
			    <td class="dataTableContent" id="tips_<?= $news_rows['departure_id']?>"><?php echo tep_db_output($news_rows['departure_tips'])?></td>
			    <td nowrap class="dataTableContent">
			      
			      [<a href="JavaScript:void(0);" onClick="ChangeTrContent('tr_<?=(int)$news_rows['departure_id']?>')">编辑</a>]&nbsp;
			      [<a href="JavaScript:void(0);" onClick="DelInfo(<?php echo $news_rows['departure_id']?>); return false;">删除</a>]				</td>
			  </tr>
			  
			<?php }?>  
			</table>
			</form>
		</fieldset>		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $news_split->display_count($news_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $news_split->display_links($news_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
