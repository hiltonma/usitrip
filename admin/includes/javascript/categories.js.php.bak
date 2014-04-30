<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
//ajax 提交GET数据
function ajax_get_submit(url,success_msm,success_go_to,replace_id){
	var url = url;
	ajax.open("GET", url, true);
	ajax.send(null); 
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(ajax.responseText.replace(error_regxp,''));
			}else if(ajax.responseText.search(/(\[JS\].+\[\/JS\])/g)!=-1){
				eval(ajax.responseText.replace(/(.*\[JS\])|(\[\/JS\].*[:space:]*.*)/g, ''));
			}else if(ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){		
				if(success_msm!=""){
					alert(success_msm);
				}
				if(success_go_to!=""){
					location = success_go_to;
				}
			}else if(typeof(replace_id)!="undefined"){
				var Replace_ID = document.getElementById(replace_id);
				if(Replace_ID!=null){
					Replace_ID.innerHTML = ajax.responseText;
				}
			}
		}
	}
}

</script>
<script type="text/javascript"><!--

 <?php  if($access_full_edit == 'true') {?>
  function add_irregular(divno)
							 {
							
								//alert("in add irr");
								 day_date =document.new_product.elements['avaliable_date_'+divno].value;
								 day_prefix =document.new_product.elements['avaliable_day_prefix'+divno].value;
								 
								
								 day_sort_order = document.new_product.elements['avaliable_day_sort_order'+divno].value;
								
								
								 day_price = document.new_product.elements['avaliable_day_price'+divno].value;
								 day_single_price = document.new_product.elements['avaliable_single_price'+divno].value;
								 day_single_pu_price = document.new_product.elements['avaliable_single_pu_price'+divno].value;
								 day_double_price = document.new_product.elements['avaliable_double_price'+divno].value;
								 day_triple_price = document.new_product.elements['avaliable_triple_price'+divno].value;
								 day_quadruple_price = document.new_product.elements['avaliable_quadruple_price'+divno].value;
								 day_kids_price = document.new_product.elements['avaliable_kids_price'+divno].value;
								 
								 
								 day_price_cost = document.new_product.elements['avaliable_day_price_cost'+divno].value;
								 day_single_price_cost = document.new_product.elements['avaliable_single_price_cost'+divno].value;
								 day_single_pu_price_cost = document.new_product.elements['avaliable_single_pu_price_cost'+divno].value;
								 day_double_price_cost = document.new_product.elements['avaliable_double_price_cost'+divno].value;
								 day_triple_price_cost = document.new_product.elements['avaliable_triple_price_cost'+divno].value;
								 day_quadruple_price_cost = document.new_product.elements['avaliable_quadruple_price_cost'+divno].value;
								 day_kids_price_cost = document.new_product.elements['avaliable_kids_price_cost'+divno].value;
								 
						
								
								if(day_date == "")
								{
									alert("Please enter the date");
									document.new_product.elements['avaliable_date_'+divno].focus();
									return false;
								}
								
								if(day_prefix != "" )
								{
									if(day_prefix != '-' && day_prefix != '+')
									{
										alert("Please enter the prefix +/-");
										document.new_product.elements['avaliable_day_prefix'+divno].focus();
										return false;
									}
								}
								
								
								
								if(day_price != "" )
								{
									
									 if(isNaN(day_price)) 
									{
										alert("Please enter the price in digit");
										document.new_product.elements['avaliable_day_price'+divno].focus();
										return false;
									}
								}
								
								
							/*noofdates =  document.new_product.numberofdates1.value;*/
								noofdates =  document.new_product.elements['numberofdates'+divno].value
								
								if(noofdates%2 == 0)
								{
								class_tr_style = 'attributes-even' ;
								}else
								{
								class_tr_style = 'attributes-odd' ;
								}	
							if(document.getElementById('extra_price_'+divno).style.display!='none')
							{	
										if(day_single_price != "" )
										{
											
											 if(isNaN(day_single_price)) 
											{
												alert("Please enter the Single price in digit");
												document.new_product.elements['avaliable_single_price'+divno].focus();
												return false;
											}
										}
										
										if(day_single_pu_price != "" )
										{
											
											 if(isNaN(day_single_pu_price)) 
											{
												alert("Please enter the Single Pair Up price in digit");
												document.new_product.elements['avaliable_single_pu_price'+divno].focus();
												return false;
											}
										}
										
										if(day_double_price != "" )
										{
											
											 if(isNaN(day_double_price)) 
											{
												alert("Please enter the Double price in digit");
												document.new_product.elements['avaliable_double_price'+divno].focus();
												return false;
											}
										}
										if(day_triple_price != "" )
										{
											
											 if(isNaN(day_triple_price)) 
											{
												alert("Please enter the Triple price in digit");
												document.new_product.elements['avaliable_triple_price'+divno].focus();
												return false;
											}
										}
										if(day_quadruple_price != "" )
										{
											
											 if(isNaN(day_quadruple_price)) 
											{
												alert("Please enter the quadruple price in digit");
												document.new_product.elements['avaliable_quadruple_price'+divno].focus();
												return false;
											}
										}
										if(day_kids_price != "" )
										{
											
											 if(isNaN(day_kids_price)) 
											{
												alert("Please enter the kids price in digit");
												document.new_product.elements['avaliable_kids_price'+divno].focus();
												return false;
											}
										}
										
										
								extra_spec_price = '<td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_single_price_'+divno+'_'+noofdates+'" size="7" value="'+day_single_price+'" ><br><input type="text" name="avaliable_single_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_single_price_cost+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_single_pu_price_'+divno+'_'+noofdates+'" size="7" value="'+day_single_pu_price+'" ><br><input type="text" name="avaliable_single_pu_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_single_pu_price_cost+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_double_price_'+divno+'_'+noofdates+'" size="7" value="'+day_double_price+'" ><br><input type="text" name="avaliable_double_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_double_price_cost+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_triple_price_'+divno+'_'+noofdates+'" size="7" value="'+day_triple_price+'" ><br><input type="text" name="avaliable_triple_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_triple_price_cost+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_quadruple_price_'+divno+'_'+noofdates+'" size="7" value="'+day_quadruple_price+'" ><br><input type="text" name="avaliable_quadruple_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_quadruple_price_cost+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_kids_price_'+divno+'_'+noofdates+'" size="7" value="'+day_kids_price+'" ><br><input type="text" name="avaliable_kids_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_kids_price_cost+'" ></td>';		
									
									
								}
								else
								{
									extra_spec_price='<td class="dataTableContent" width="40%" colspan="4" align="center">&nbsp;</td>';
								}				
								if(day_sort_order != "")
								{
									 if(isNaN(day_sort_order))
									{
										alert("Please enter the Sort Order in digit");
										document.new_product.elements['avaliable_day_sort_order'+divno].focus();
										return false;
									}
								}
								
								
										
										
																
								b = '<table id="table_id_irregular'+divno+'_'+noofdates+'" width="100%" cellpadding="2" cellspacing="2"><tr class="'+class_tr_style+'"><td class=dataTableContent width="15%">'+noofdates+'. <input type="text" name="avaliable_day_date_'+divno+'_'+noofdates+'" size="12" value="'+day_date+'"></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_day_prefix_'+divno+'_'+noofdates+'" size="2" value="'+day_prefix+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_day_price_'+divno+'_'+noofdates+'" size="7" value="'+day_price+'"  ><br><input type="text" name="avaliable_day_price_cost_'+divno+'_'+noofdates+'" size="7" value="'+day_price_cost+'"  ></td>'+extra_spec_price+'<td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_day_sort_order_'+divno+'_'+noofdates+'" size="7" value="'+day_sort_order+'" ></td><td width="10%" align="center"><input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_delete.gif" name="delete_'+divno+'_'+noofdates+'" onClick="return clearrow_irregular('+divno+','+noofdates+')" value="delete"></td><td width="10%" class="dataTableContent"><?php echo tep_draw_separator("pixel_trans.gif", "55", "15")?></td></tr></table>';
								
								
								document.getElementById("div_id_avaliable_day_date"+divno).innerHTML = document.getElementById("div_id_avaliable_day_date"+divno).innerHTML  + b ; 
								
							
								if(day_price=='' || day_price=='0.00')
								{
									document.new_product.elements['avaliable_day_price_'+divno+'_'+noofdates].disabled = true;
								}
								else
								{
									document.new_product.elements['avaliable_day_price_'+divno+'_'+noofdates].disabled = false;
								}
								
							//	document.new_product.elements['avaliable_day_price_'+divno+'_'+noofdates].disabled = dis_price;
								
								
								if(document.getElementById('optionyes').style.visibility!='visible')
								{
									if(document.getElementById('optionno').style.visibility=='visible' || option_value_yn==1)
									{
										try {
										
										document.new_product.elements['avaliable_double_price_'+divno+'_'+noofdates].value = "0.00";
										document.new_product.elements['avaliable_quadruple_price_'+divno+'_'+noofdates].value = "0.00";
										document.new_product.elements['avaliable_triple_price_'+divno+'_'+noofdates].value='0.00';
										
										document.new_product.elements['avaliable_double_price_'+divno+'_'+noofdates].disabled=true;
										document.new_product.elements['avaliable_quadruple_price_'+divno+'_'+noofdates].disabled=true;
										document.new_product.elements['avaliable_triple_price_'+divno+'_'+noofdates].disabled=true;
										
										document.new_product.elements['avaliable_single_pu_price_'+divno+'_'+noofdates].value='0.00';
										document.new_product.elements['avaliable_single_pu_price_'+divno+'_'+noofdates].disabled=true;
										
										}catch(e){
										 //alert(e)
										}
										
									}
								}		
								
								
								document.new_product.elements['avaliable_date_'+divno].value = "";
								document.new_product.elements['avaliable_day_prefix'+divno].value = "";
								
								document.new_product.elements['avaliable_day_price'+divno].value = "";
								document.new_product.elements['avaliable_single_price'+divno].value = "";
								document.new_product.elements['avaliable_single_pu_price'+divno].value = "";
								document.new_product.elements['avaliable_double_price'+divno].value = "";
								document.new_product.elements['avaliable_triple_price'+divno].value = "";
								document.new_product.elements['avaliable_quadruple_price'+divno].value = "";
								document.new_product.elements['avaliable_kids_price'+divno].value = "";
								
								document.new_product.elements['avaliable_day_price_cost'+divno].value = "";
								document.new_product.elements['avaliable_single_price_cost'+divno].value = "";
								document.new_product.elements['avaliable_single_pu_price_cost'+divno].value = "";
								document.new_product.elements['avaliable_double_price_cost'+divno].value = "";
								document.new_product.elements['avaliable_triple_price_cost'+divno].value = "";
								document.new_product.elements['avaliable_quadruple_price_cost'+divno].value = "";
								document.new_product.elements['avaliable_kids_price_cost'+divno].value = "";
								
								
								document.new_product.elements['avaliable_day_sort_order'+divno].value = "";
								document.new_product.elements['avaliable_date_'+divno].focus();
								document.new_product.elements['numberofdates'+divno].value = (parseFloat(noofdates+'_'+divno) + parseFloat(1));
								
								return false;
							 }
								 
<?php }else{ ?>
 function add_irregular(divno)
							 {
							
								//alert("in add irr");
								 day_date =document.new_product.elements['avaliable_date_'+divno].value;
								 day_prefix =document.new_product.elements['avaliable_day_prefix'+divno].value;
								 
								
								 day_sort_order = document.new_product.elements['avaliable_day_sort_order'+divno].value;
								
								
								 day_price = document.new_product.elements['avaliable_day_price'+divno].value;
								 day_single_price = document.new_product.elements['avaliable_single_price'+divno].value;
								 day_single_pu_price = document.new_product.elements['avaliable_single_pu_price'+divno].value;
								 day_double_price = document.new_product.elements['avaliable_double_price'+divno].value;
								 day_triple_price = document.new_product.elements['avaliable_triple_price'+divno].value;
								 day_quadruple_price = document.new_product.elements['avaliable_quadruple_price'+divno].value;
								 day_kids_price = document.new_product.elements['avaliable_kids_price'+divno].value;
								 
								 
						
								
								if(day_date == "")
								{
									alert("Please enter the date");
									document.new_product.elements['avaliable_date_'+divno].focus();
									return false;
								}
								
								if(day_prefix != "" )
								{
									if(day_prefix != '-' && day_prefix != '+')
									{
										alert("Please enter the prefix +/-");
										document.new_product.elements['avaliable_day_prefix'+divno].focus();
										return false;
									}
								}
								
								
								
								if(day_price != "" )
								{
									
									 if(isNaN(day_price)) 
									{
										alert("Please enter the price in digit");
										document.new_product.elements['avaliable_day_price'+divno].focus();
										return false;
									}
								}
								
								
							/*noofdates =  document.new_product.numberofdates1.value;*/
								noofdates =  document.new_product.elements['numberofdates'+divno].value
								
								if(noofdates%2 == 0)
								{
								class_tr_style = 'attributes-even' ;
								}else
								{
								class_tr_style = 'attributes-odd' ;
								}	
							if(document.getElementById('extra_price_'+divno).style.display!='none')
							{	
										if(day_single_price != "" )
										{
											
											 if(isNaN(day_single_price)) 
											{
												alert("Please enter the Single price in digit");
												document.new_product.elements['avaliable_single_price'+divno].focus();
												return false;
											}
										}
										if(day_single_pu_price != "" )
										{
											
											 if(isNaN(day_single_pu_price)) 
											{
												alert("Please enter the Single Pair Up price in digit");
												document.new_product.elements['avaliable_single_pu_price'+divno].focus();
												return false;
											}
										}
										
										if(day_double_price != "" )
										{
											
											 if(isNaN(day_double_price)) 
											{
												alert("Please enter the Double price in digit");
												document.new_product.elements['avaliable_double_price'+divno].focus();
												return false;
											}
										}
										if(day_triple_price != "" )
										{
											
											 if(isNaN(day_triple_price)) 
											{
												alert("Please enter the Triple price in digit");
												document.new_product.elements['avaliable_triple_price'+divno].focus();
												return false;
											}
										}
										if(day_quadruple_price != "" )
										{
											
											 if(isNaN(day_quadruple_price)) 
											{
												alert("Please enter the quadruple price in digit");
												document.new_product.elements['avaliable_quadruple_price'+divno].focus();
												return false;
											}
										}
										if(day_kids_price != "" )
										{
											
											 if(isNaN(day_kids_price)) 
											{
												alert("Please enter the kids price in digit");
												document.new_product.elements['avaliable_kids_price'+divno].focus();
												return false;
											}
										}
										
										
								extra_spec_price = '<td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_single_price_'+divno+'_'+noofdates+'" size="7" value="'+day_single_price+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_single_pu_price_'+divno+'_'+noofdates+'" size="7" value="'+day_single_pu_price+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_double_price_'+divno+'_'+noofdates+'" size="7" value="'+day_double_price+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_triple_price_'+divno+'_'+noofdates+'" size="7" value="'+day_triple_price+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_quadruple_price_'+divno+'_'+noofdates+'" size="7" value="'+day_quadruple_price+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_kids_price_'+divno+'_'+noofdates+'" size="7" value="'+day_kids_price+'" ></td>';		
									
									
								}
								else
								{
									extra_spec_price='<td class="dataTableContent" width="40%" colspan="4" align="center">&nbsp;</td>';
								}				
								if(day_sort_order != "")
								{
									 if(isNaN(day_sort_order))
									{
										alert("Please enter the Sort Order in digit");
										document.new_product.elements['avaliable_day_sort_order'+divno].focus();
										return false;
									}
								}
								
								
										
										
																
								b = '<table id="table_id_irregular'+divno+'_'+noofdates+'" width="100%" cellpadding="2" cellspacing="2"><tr class="'+class_tr_style+'"><td class=dataTableContent width="15%">'+noofdates+'. <input type="text" name="avaliable_day_date_'+divno+'_'+noofdates+'" size="12" value="'+day_date+'"></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_day_prefix_'+divno+'_'+noofdates+'" size="2" value="'+day_prefix+'" ></td><td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_day_price_'+divno+'_'+noofdates+'" size="7" value="'+day_price+'"  ></td>'+extra_spec_price+'<td class="dataTableContent" width="10%" align="center"><input type="text" name="avaliable_day_sort_order_'+divno+'_'+noofdates+'" size="7" value="'+day_sort_order+'" ></td><td width="10%" align="center"><input type="image" src="includes/languages/<?php echo $language;?>/images/buttons/button_delete.gif" name="delete_'+divno+'_'+noofdates+'" onClick="return clearrow_irregular('+divno+','+noofdates+')" value="delete"></td><td width="10%" class="dataTableContent"><?php echo tep_draw_separator("pixel_trans.gif", "55", "15")?></td></tr></table>';
								
								
								document.getElementById("div_id_avaliable_day_date"+divno).innerHTML = document.getElementById("div_id_avaliable_day_date"+divno).innerHTML  + b ; 
								
							
								if(day_price=='' || day_price=='0.00')
								{
									document.new_product.elements['avaliable_day_price_'+divno+'_'+noofdates].disabled = true;
								}
								else
								{
									document.new_product.elements['avaliable_day_price_'+divno+'_'+noofdates].disabled = false;
								}
								
							//	document.new_product.elements['avaliable_day_price_'+divno+'_'+noofdates].disabled = dis_price;
								
								
								if(document.getElementById('optionyes').style.visibility!='visible')
								{
									if(document.getElementById('optionno').style.visibility=='visible' || option_value_yn==1)
									{
										try {
										
										document.new_product.elements['avaliable_double_price_'+divno+'_'+noofdates].value = "0.00";
										document.new_product.elements['avaliable_quadruple_price_'+divno+'_'+noofdates].value = "0.00";
										document.new_product.elements['avaliable_triple_price_'+divno+'_'+noofdates].value='0.00';
										
										document.new_product.elements['avaliable_double_price_'+divno+'_'+noofdates].disabled=true;
										document.new_product.elements['avaliable_quadruple_price_'+divno+'_'+noofdates].disabled=true;
										document.new_product.elements['avaliable_triple_price_'+divno+'_'+noofdates].disabled=true;
										
										document.new_product.elements['avaliable_single_pu_price_'+divno+'_'+noofdates].value='0.00';
										document.new_product.elements['avaliable_single_pu_price_'+divno+'_'+noofdates].disabled=true;
										
										}catch(e){
										 //alert(e)
										}
										
									}
								}		
								
								
								document.new_product.elements['avaliable_date_'+divno].value = "";
								document.new_product.elements['avaliable_day_prefix'+divno].value = "";
								
								document.new_product.elements['avaliable_day_price'+divno].value = "";
								document.new_product.elements['avaliable_single_price'+divno].value = "";
								document.new_product.elements['avaliable_single_pu_price'+divno].value = "";
								document.new_product.elements['avaliable_double_price'+divno].value = "";
								document.new_product.elements['avaliable_triple_price'+divno].value = "";
								document.new_product.elements['avaliable_quadruple_price'+divno].value = "";
								document.new_product.elements['avaliable_kids_price'+divno].value = "";
								
								
								document.new_product.elements['avaliable_day_sort_order'+divno].value = "";
								document.new_product.elements['avaliable_date_'+divno].focus();
								document.new_product.elements['numberofdates'+divno].value = (parseFloat(noofdates+'_'+divno) + parseFloat(1));
								
								return false;
							 }
<?php } ?>

function set_tpl_obj(obj){
	var show_hotel =  document.getElementById("show_hotel");
	var min_watch_year = document.getElementById("min_watch_year");
	if(obj.value=="product_info_vegas_show"){
		show_hotel.style.display = '';
		min_watch_year.style.display = '';
	}else{
		show_hotel.style.display = 'none';
		min_watch_year.style.display = 'none';
		document.new_product.elements['products_hotel_id'].value = "0";
		document.new_product.elements['min_watch_age'].value = "0";
	}
}

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

//自动取得产品的关键词长度
function auto_get_keyword(p_id){
	var form = document.getElementById('new_product');
	var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('categories_ajax_sections.php','action=auto_get_keyword')) ?>";
	var post_str = 'products_id='+p_id;
	post_str += "&ajax=true";

	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			alert(ajax.responseText);
			if(ajax.responseText.length>2){
				if(form.elements['products_head_keywords_tag[1]'].value.length>2){
					form.elements['products_head_keywords_tag[1]'].value += ' ,'+ ajax.responseText;
				}else{
					form.elements['products_head_keywords_tag[1]'].value = ajax.responseText;
				}
			}
		}
	}
}

//上车地点时间翻页函数
function split_page_for_edit_departure_data(page_id, products_id){
	if(page_id>0){
		var loading_img = document.getElementById('loading_img_categories_ajax_sections_edit_departure_data');
		if(loading_img!=null){
			loading_img.style.display="block";
		}
		var url ="<?php echo preg_replace($p,$r,tep_href_link_noseo('categories_ajax_sections_edit_departure_data.php','ajax_slip_page=1')) ?>"+"&products_id="+products_id+"&page_id="+page_id;
		ajax_get_submit(url,"","","div_id_departure");
	}
}

/* Start - add sold out dates */
function addSoldoutDates(){
	var ni = document.getElementById('mainSoldOutDiv');
	var numi = document.getElementById('mainSoldOutValue');
	var num = parseInt(numi.value) + 1;
	numi.value = num;
	var divIdName = "div_products_soldout_date_"+num;
	var newdiv = document.createElement('div');
	newdiv.setAttribute("id",divIdName);
	
	newdiv.innerHTML = '<div id="div_products_soldout_date_'+num+'" style="float:left; "><table width="110px" border="0" cellspacing="3" cellpadding="0"><tr><td align="left"><nobr><input type="text" name="products_soldout_date_'+num+'" size="10" id="products_soldout_date_'+num+'"><a href="javascript:NewCal(\'products_soldout_date_'+num+'\');" id="products_soldout_dt_lnk_'+num+'"><img src=\'<?php echo DIR_WS_IMAGES;?>cal.gif\' width=\'16\' height=\'16\' border=\'0\' alt=\'Pick a date\'></a>&nbsp;<a href="javascript:remove_date(\''+num+'\');" id="products_soldout_delete_lnk_'+num+'"><?php echo tep_image(DIR_WS_IMAGES."no.gif", TEXT_PRODUCTS_IMAGE_REMOVE_SHORT, 16, 16);?></a></nobr></td></tr></table></div>';
	ni.appendChild(newdiv);
}
function remove_date(cal_id){
	with(document){
		getElementById('products_soldout_date_'+cal_id).value="";
		getElementById('products_soldout_date_'+cal_id).style.display="none";
		getElementById('products_soldout_dt_lnk_'+cal_id).style.display="none";
		getElementById('products_soldout_delete_lnk_'+cal_id).style.display="none";
	}
}
/* End - add sold out dates */
function addDepartureDates(){
   var ni = document.getElementById('mainRemainingSeatsDiv');
   var numi = document.getElementById('mainRemainingSeatsValue');
   var num = parseInt(numi.value) + 1;
   numi.value = num;
   var divIdName = "div_products_remaining_seats_"+num;
   var newdiv = document.createElement("div");
   newdiv.setAttribute("id",divIdName);

   newdiv.innerHTML = '<div id="div_products_remaining_seats_'+num+'" style="float:left; "><table border="1" cellpadding="1" cellspacing="1"><tr><td width="210" valign="top" nowrap="nowrap">出团日期&nbsp;'+num+'<nobr><input type="text" name="products_remaining_seats_'+num+'" size="10" id="products_remaining_seats_'+num+'"><a href="javascript:NewCal(\'products_remaining_seats_'+num+'\');" id="products_remaining_seats_lnk_'+num+'"><img src=\'<?php echo DIR_WS_IMAGES;?>cal.gif\' width=\'16\' height=\'16\' border=\'0\' alt=\'Pick a date\'></a></td><td width="210" valign="top" nowrap="nowrap">余座位<input type="text" name="products_remaining_seats_num_'+num+'" size="1" style="ime-mode:disabled" id="products_remaining_seats_num_'+num+'">个<a href="javascript:remove_departure_date(\''+num+'\');" id="products_remaining_seats_num_delete_lnk_'+num+'"><?php echo tep_image(DIR_WS_IMAGES."no.gif", TEXT_PRODUCTS_IMAGE_REMOVE_SHORT, 16, 16);?></a></nobr></td></tr></table></div>';
   ni.appendChild(newdiv);

}

function remove_departure_date(cal_id){
    with(document){
        getElementById('products_remaining_seats_'+cal_id).value="";
		getElementById('products_remaining_seats_'+cal_id).style.display="none";
		getElementById('products_remaining_seats_lnk_'+cal_id).style.display="none";
		getElementById('products_remaining_seats_num_'+cal_id).value="";
		getElementById('products_remaining_seats_num_'+cal_id).style.display="none";
		getElementById('products_remaining_seats_num_delete_lnk_'+cal_id).style.display="none";
		getElementById('div_products_remaining_seats_'+cal_id).value="";
		getElementById('div_products_remaining_seats_'+cal_id).style.display="none";

	}
}


function get_hotel_info(hotel_id){
	var form = document.getElementById('new_product');
	var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('categories_ajax_sections.php','action=ajax_get_hotel_info')) ?>";
	var post_str = 'hotel_id='+hotel_id;
	post_str += "&ajax=true";

	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			//alert(ajax.responseText);
			var response = ajax.responseText;
			document.getElementById("div_hotel_info").innerHTML = response;
		}
	}
}


//--></script>