<script type="text/javascript" src="<?= IMAGES_HOST;?>/providers/datetimepicker.js"></script>
<div id="CreateNewCompanion" class="popup" >
  <table  cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
			<td class="con">
			  <div class="popupCon" style="overflow:auto/9" id="CreateNewCompanionCon">
			  <?php
			  if(!(int)$customer_id){
				$replace_id = 'CreateNewCompanion';
				$next_file = 'travel_companion_tpl_tpl.php';
				require('ajax_fast_login.php');
			  }else{
				require('travel_companion_tpl_tpl.php');
			  }
			  ob_start();
			  ?>
			  <script type="text/javascript">
			  /* 发布期望结伴贴 下拉 筛选 线路JS 代码  by lwkai add */
				function set_products_id(obj){
					var id = obj.options[obj.selectedIndex].value;
					var text = obj.options[obj.selectedIndex].text;
					jQuery('#products_id').val(id);
					jQuery('#t_companion_content').val(text);
				}
				
				function getProducts(){
					var start_id = jQuery('#start_city').val();
					var end_id = jQuery('#end_city').val();
					var language = '<?php echo $language?>';
					var charset = '<?php echo strtolower(CHARSET)?>';
					start_id = (isNaN(start_id) ? '' : start_id);
					end_id = (isNaN(end_id) ? '' : end_id);
					if (start_id == '' && end_id == '') {
						return;
					}
					jQuery.post('<?php echo tep_href_link('ajax_travel_companion_city.php')?>',{'start_id' : start_id,'end_id' : end_id,'action' : 'products'},function(data){
						var html ='';
						if (data.length == 0) {
							html = '<option><?php echo db_to_html('暂无可发起结伴同游的线路')?></option>';
						}else{ 
							html = '<option><?php echo db_to_html('请选择旅游线路')?></option>';
							for(var i = 0, len = data.length; i < len; i++){
								if(language=='schinese' || charset=='gb2312'){
									html += '<option value="' + data[i].products_id + '">' + simplized(data[i].products_name) + "</option>";
								}else{
									html += '<option value="' + data[i].products_id + '">' + traditionalized(data[i].products_name) + "</option>";
								}
							}
						}
						jQuery('#products_select').html(html);
					},'json');
				}
			
				function getCity(obj){
					var language = '<?php echo $language?>';
					var charset = '<?php echo strtolower(CHARSET)?>';
					var id = obj.options[obj.selectedIndex].value;
					if (id === '0') return;
					jQuery.post('<?php echo tep_href_link('ajax_travel_companion_city.php'); ?>',{'id':id},function(data){
						var start_city = '<option><?php echo db_to_html('请选择')?></option>';
							for(var aa in data['start_city']){
								/*start_city += '<option value="' + aa + '">' + data['start_city'][aa] + '</option>';*/
								if(language=='schinese' || charset=='gb2312'){
									start_city += '<option value="' + aa + '">' + simplized(data['start_city'][aa]) + '</option>';
								}else{
									start_city += '<option value="' + aa + '">' + traditionalized(data['start_city'][aa]) + '</option>';
								}	
							}
						
						var end_city = '<option><?php echo db_to_html('请选择')?></option>';
						
							for(var aa in data['end_city']){
								if(language=='schinese' || charset=='gb2312'){
									end_city += '<option value="' + aa + '">' + simplized(data['end_city'][aa]) + '</option>';	
								}else{
									end_city += '<option value="' + aa + '">' + traditionalized(data['end_city'][aa]) + '</option>';	
								}
								
							}
						
						jQuery('#start_city').html(start_city);
						jQuery('#end_city').html(end_city);
					},'json');
				}
			</script>
			<?php
			//压缩输出
			$_html = ob_get_clean();
			echo preg_replace('/[[:space:]]+|(\/\*+[^\*]+\*?\*\/)/',' ', $_html);
			?>
			  </div>
		  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
</div>

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

ob_start();
?>

<script type="text/javascript">
var action_onmove = true;
function change_spiffycalendar_style(num){
	var rslt = navigator.appVersion.indexOf('MSIE 6.0');
	var date_select_layer = document.getElementById('date_select_layer');
	if(date_select_layer!=null){
		if(num=='YES' && action_onmove == true){
			if(rslt == -1){
				date_select_layer.innerHTML = '<div id="spiffycalendar" style="z-index:1000;margin-left:-102px; position:fixed;"></div>';
			}
			action_onmove = false;
		}else if(num=='NO' && action_onmove == false){
			/*alert(date_select_layer.innerHTML);*/
			date_select_layer.innerHTML = '<div id="spiffycalendar" style="z-index:1000;margin-left:-102px;"></div>';
			action_onmove = true;
		}
	}
}
function Submit_Companion(Form_id) {
	var From_ = document.getElementById(Form_id);
	var error_msn = '';
	var error = false;

	for(i=0; i<From_.length; i++){
		if(From_.elements[i]!=null){
			if((From_.elements[i].value.length < 1 || From_.elements[i].value==From_.elements[i].title) && From_.elements[i].className.search(/required/g)!= -1 ){
				error = true;
				error_msn +=  "* " + From_.elements[i].title + "\n\n";
			}
		}
	}
	
	/*检查期望伴友人数*/
	var per_num = (parseInt(From_.elements['hope_people_man'].value) + parseInt(From_.elements['hope_people_woman'].value));
	if(per_num==0){
		error = true;
		error_msn +=  "* " + "<?= db_to_html('请选择期望伴友人数！');?>" + "\n\n";
	}
	
	if(error==true){
		alert(error_msn);
		return false;
	}else{
		for(i=0; i<From_.length; i++){
			if(From_.elements[i].value==From_.elements[i].title){
				From_.elements[i].value = "";
			}
		}	
		var form = From_;
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_companion.php','action=process')) ?>");
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,Form_id,success_msm,success_go_to);

	}
}

function not_set_top(obj){
	var form = document.getElementById('CompanionForm');
	var Set_Top_Radio = document.getElementById('set_top_radio');
	if(obj.checked==true){
		Set_Top_Radio.style.display = '';
		form.elements['t_top_day'][0].checked = true;
	}else{
		Set_Top_Radio.style.display = 'none';
		for(i=0; i<3; i++){
			if(form.elements['t_top_day'][i]!=null){
				form.elements['t_top_day'][i].checked = false;
			}
		}
	}
}

/*2010-5-18 设置现有人数和期望伴友的值，同时改变样式*/
function set_hidden_field_val(obj){
	var form = document.getElementById('CompanionForm');
	var hidden_field_name = obj.id.replace(/_\d$/,'');
	var top_div = document.getElementById('CreateNewCompanion');
	var a_obj = top_div.getElementsByTagName("a");
	if(obj.className=="a_sex_fav"){
		form.elements[hidden_field_name].value = obj.title;
		for(i=0; i<a_obj.length; i++){
			if(a_obj[i].id.indexOf(hidden_field_name) >-1 ){
				a_obj[i].className = "a_sex_fav";
			}
		}
		obj.className = "a_sex_del";
	}else{
		form.elements[hidden_field_name].value = 0;
		for(i=0; i<a_obj.length; i++){
			if(a_obj[i].id.indexOf(hidden_field_name) >-1 ){
				a_obj[i].className = "a_sex_fav";
			}
		}
		obj.className = "a_sex_fav";
	}
}
</script>
<?php
//压缩输出
$_html = ob_get_clean();
echo preg_replace('/[[:space:]]+|(\/\*+[^\*]+\*?\*\/)/',' ', $_html);
?>
