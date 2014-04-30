function addEvent()
{
var ni = document.getElementById('myDiv');
var numi = document.getElementById('theValue');
var num = (document.getElementById("theValue").value -1)+ 2;
numi.value = num;
var divIdName = "my"+num+"Div";
var newdiv = document.createElement('div');
newdiv.setAttribute("id",divIdName);
newdiv.innerHTML = "<table width='100%'  border='0' cellspacing='3' cellpadding='3'><tr><td valign='top'><input type='file' name='image_introfile[]'></td><td ><textarea name='cat_intro_description_introfile[]' rows='8' cols='28' ></textarea></td><td valign='top'><textarea name='cat_intro_alt_introfile[]' rows='8' cols='28' ></textarea></td><td  valign='top'><input type='text' name='cat_intro_sort_order[]' size='10'></td><td valign='top'><a href=\"javascript:;\" onclick=\"removeEvent(\'"+divIdName+"\')\">Remove</a></td></tr></table>";
ni.appendChild(newdiv);
}
function addEventExtra(city_ids)
{
	var ni = document.getElementById('myDiv');
	var numi = document.getElementById('theValue');
	var num = (document.getElementById("theValue").value -1)+ 2;
	numi.value = num;
	var divIdName = "my"+num+"Div";
	var newdiv = document.createElement('div');
	newdiv.setAttribute("id",divIdName);
	newdiv.innerHTML = "<table width='90%'  border='0' cellspacing='3' cellpadding='3'><tr><td valign='top' class='main' width='350'><img src='' width='100' id='preview_introfile_"+ num +"'><br/>Image URL<br /><input type='input' name='previouse_image_introfile[]' size='41' id='previouse_image_introfile_"+ num +"' title='双击可以打开图片选择器'  ondblclick='open_picture_db(this,&quot;detail&quot;,&quot;"+ city_ids +"&quot;,&quot;preview_introfile_"+ num +"&quot;);'><br />Or Upload<br /><input type='file' name='image_introfile[]'></td><td  valign='top'  width='150'><input type='text' value='1' name='cat_intro_sort_order[]' size='10'></td><td valign='top'  width='150'><a href=\"javascript:;\" onclick=\"removeEvent(\'"+divIdName+"\')\">Remove</a></td></tr></table>";
	ni.appendChild(newdiv);
}

function removeEvent(divNum)
{
var d = document.getElementById('myDiv');
var olddiv = document.getElementById(divNum);
d.removeChild(olddiv);
}

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150');
}
function popupWindowAvailableTour(url) {
  window.open(url,'popupWindowAvailableTour','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=800,height=600,screenX=50,screenY=50,top=50,left=50');
}

function chkallregion(TheForm,re,col)
{
	var i = 0 ;
	var j = 0;
	for ( i= 0 ; i < TheForm.elements.length ; i ++)
	{
	
		if(TheForm.elements[i].type == "checkbox" && TheForm.elements[i].id == col)
		{
		
			if (re)
				TheForm.elements[i].checked= true ;
			else	
				TheForm.elements[i].checked= false;
		}
	}

}



 function cleardeparturerow(departid)
						  {	
								
								var originaldatadepart = document.getElementById("div_id_departure").innerHTML;
								var tableidshow = document.getElementById("table_id_departure"+departid).innerHTML;
								var stringfinal = replaceAll(originaldatadepart,tableidshow,"",true);
								document.getElementById("div_id_departure").innerHTML = stringfinal;
								
								
						  return false;
						  }
							 function add_departure()
							 {
							 
								depart_region = document.new_product.region.value ;
								depart_time = document.new_product.depart_time.value ;
								depart_add = document.new_product.departure_address.value ;
								depart_full_add = document.new_product.departure_full_address.value ;
								products_hotels_id = document.new_product.products_hotels_ids.value ;
								depart_map_path = document.new_product.departure_map_path.value;
								depart_tips = document.new_product.departure_tips.value;
								if(depart_time == "")
								{
									alert("Please enter the time");
									document.new_product.depart_time.focus();
									return false;
								}else if(depart_time.length != 7 || (depart_time.search(/am$/) ==-1 && depart_time.search(/pm$/) ==-1 )){
									alert("Please enter the time. format HH:MMam or HH:MMpm e.g 08:45am or 11:00pm");
									document.new_product.depart_time.focus();
									return false;
								}
								
								if(depart_add == "")
								{
									alert("Please enter the address");
									document.new_product.departure_address.focus();
									return false;
								}
								if(depart_full_add == "")
								{
									alert("Please enter the full address");
									document.new_product.departure_full_address.focus();
									return false;
								}
								
								noofdaparture =  document.new_product.numberofdaparture.value;
								
								if(noofdaparture%2 == 0)
								{
								class_tr_style_depart = 'attributes-even' ;
								}else
								{
								class_tr_style_depart = 'attributes-odd' ;
								}
								
								c = "<table id='table_id_departure"+noofdaparture+"' width='100%' cellpadding=2 cellspacing=2><tr class='"+class_tr_style_depart+"'><td width=110 class=dataTableContent valign=top nowrap><input class=buttonstyle checked type=checkbox name=regioninsertid_"+noofdaparture+"  value='"+noofdaparture+"'>"+noofdaparture+".<input type=text name=depart_region_"+noofdaparture+" value=\""+depart_region+"\" size=12 readonly></td><td width=100 class=dataTableContent valign=top><input type=text name=depart_time_"+noofdaparture+" value='"+depart_time+"' size=12></td><td class=dataTableContent width=60 valign=top><input type=text size=20 name=departure_address_"+noofdaparture+" value=\""+depart_add+"\"></td><td class=dataTableContent width=60 valign=top><input type=text size=30 name=departure_full_address_"+noofdaparture+" value=\""+depart_full_add+"\"><br><input type=text size=30 name=products_hotels_ids_"+noofdaparture+" value=\""+products_hotels_id+"\"></td><td class=dataTableContent width=70 valign=top><input type=text size=30 name=departure_map_path_"+noofdaparture+" value=\""+depart_map_path+"\"></td><td width=100 valign=top ><textarea rows=3 cols=20 name=departure_tips_"+noofdaparture+">"+depart_tips+"</textarea></td></tr></table>";

								document.getElementById("div_id_departure").innerHTML = document.getElementById("div_id_departure").innerHTML  + c ; 
								document.new_product.depart_time.value = "";
								document.new_product.departure_address.value = "";
								document.new_product.departure_full_address.value = "";
								document.new_product.departure_map_path.value = "";
								document.new_product.departure_tips.value = "";
								
								document.new_product.depart_time.focus;
								document.new_product.numberofdaparture.value = (parseFloat(noofdaparture) + parseFloat(1));
								return false;
}
								


function weekday_prefix_check_irregular(index)
						  {						  	
						    if(document.all["weekday_prefix"+index].value != '' && document.all["weekday_prefix"+index].value != '+' && document.all["weekday_prefix"+index].value != '-')
							{
								alert("Please enter the value +/- for the prefix");
								document.all["weekday_prefix"+index].value = '';
								document.all["weekday_prefix"+index].focus();
								return false;
							} 
							return true;
						  }
						   
						   function weekday_price_check_irregular(index)
						  {
						    if(document.all["weekday_price"+index].value != '' && isNaN(document.all["weekday_price"+index].value))
							{
								alert("Please enter the numeric value for the Price");
								document.all["weekday_price"+index].value = '';
								document.all["weekday_price"+index].focus();
								return false;
							} 
							return true;
						  }
						    
						function weekday_sort_order_check_irregular(index)
						{
						    if(document.all["weekday_sort_order"+index].value != '' && isNaN(document.all["weekday_sort_order"+index].value))
							{
								alert("Please enter the numeric value for the Sort Order");
								document.all["weekday_sort_order"+index].value = '';
								document.all["weekday_sort_order"+index].focus();
								return false;
							} 
							return true;
						  } 

 						function clearrow_irregular(divno,dayid)
						  {	
													
								if(dayid%2 == 0)
								{
								class_tr_style = 'attributes-even' ;
								}else
								{
								class_tr_style = 'attributes-odd' ;
								}
								
								var originaldata = document.getElementById("div_id_avaliable_day_date"+divno).innerHTML;
								var tableirshow = document.getElementById('table_id_irregular'+divno+'_'+dayid).innerHTML;
								var stringfinal = replaceAll(originaldata,tableirshow,"",true);
								document.getElementById("div_id_avaliable_day_date"+divno).innerHTML = stringfinal;
								
								
						  return false;
						  }
						  
							
function change_region_state_list(country_id,pId,regions_id)
				{	
					try{
							http1.open('get', 'add_itenerary_hotel.php?country_id='+country_id+'&pId='+pId+'&regions_id='+regions_id+'&action_attributes=star_city_filter');
							http1.onreadystatechange = hendleInfo_change_start_cities_list;
							http1.send(null);
					}catch(e){ }
				}
				
				function change_region_state_list_edit(country_id,pId,regions_id)
				{	
					try{
							http1.open('get', 'add_itenerary_hotel.php?country_id='+country_id+'&pId='+pId+'&regions_id='+regions_id+'&action_attributes=star_city_filter&edit=true');
							http1.onreadystatechange = hendleInfo_change_start_cities_list;
							http1.send(null);
					}catch(e){ }
				}
				
				function change_region_county_state_list(state_search_id,pId,regions_id){				
					try{
							http1.open('get', 'add_itenerary_hotel.php?state_search_id='+state_search_id+'&pId='+pId+'&regions_id='+regions_id+'&action_attributes=star_city_filter');
							http1.onreadystatechange = hendleInfo_change_start_cities_list;
							http1.send(null);
					}catch(e){ }
				
				}
				function change_region_county_state_list_edit(state_search_id,pId,regions_id){				
					try{
							http1.open('get', 'add_itenerary_hotel.php?state_search_id='+state_search_id+'&pId='+pId+'&regions_id='+regions_id+'&action_attributes=star_city_filter&edit=true');
							http1.onreadystatechange = hendleInfo_change_start_cities_list;
							http1.send(null);
					}catch(e){ }
				
				}

				function hendleInfo_change_start_cities_list()
					{
						
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 document.getElementById("country_state_start_city_id").innerHTML = response1;
						 
						}
					}
				
				function change_tour_attri_list(product_id,agency_id)
				{				
					try{
							http1.open('get', 'add_itenerary_hotel.php?product_id='+product_id+'&agency_id='+agency_id+'&action_attributes=true');
							http1.onreadystatechange = hendleInfo_change_attributes_list;
							http1.send(null);
					}catch(e){ }
				}
				function hendleInfo_change_attributes_list()
					{
						
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;						
						 document.getElementById("tour_attribute_list").innerHTML = response1;
						 
						}
					}
				
				function change_itenerary_hotel(products_id)
				{
					products_durations = document.new_product.products_durations.value;
					products_durations_type = document.new_product.products_durations_type.value;				
					try{
					
						if(products_durations_type==0 && products_durations!=0 && products_durations>0)
						{			
							
							http.open('get', 'add_itenerary_hotel.php?count='+products_durations+'&products_id='+products_id);
							http.onreadystatechange = hendleInfo_change_itenerary_hotel;
							http.send(null);
							http1.open('get', 'add_itenerary_hotel.php?count='+products_durations+'&products_id='+products_id+'&itinerary=1');
							http1.onreadystatechange = hendleInfo_change_itenerary_hotel1;
							http1.send(null);
						}
						else if(products_durations_type!=0)
						{
							http.open('get', 'add_itenerary_hotel.php?count=1&products_id='+products_id);
							http.onreadystatechange = hendleInfo_change_itenerary_hotel;
							http.send(null);
							http1.open('get', 'add_itenerary_hotel.php?count=1&products_id='+products_id+'&itinerary=1');
							http1.onreadystatechange = hendleInfo_change_itenerary_hotel1;
							http1.send(null);
						}						
					}catch(e){ }	
					function hendleInfo_change_itenerary_hotel()
					{
						
						if(http.readyState == 4)
						{
						 var response = http.responseText;						 
						 document.getElementById("eticket_div").innerHTML = response;
						 
						}
					}
					function hendleInfo_change_itenerary_hotel1()
					{
						
						if(http1.readyState == 4)
						{
						 var response = http1.responseText;						 
						 document.getElementById("products_travel").innerHTML = response;
						 
						}
					}
}
								
								

var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);
			
			function addOption(theSel, theText, theValue)
			{
			  var newOpt = new Option(theText, theValue);
			  var selLength = theSel.length;
			  theSel.options[selLength] = newOpt;
			}
			
			function deleteOption(theSel, theIndex)
			{ 
			  var selLength = theSel.length;
			  if(selLength>0)
			  {
				theSel.options[theIndex] = null;
			  }
			}
			
			function moveOptions(theSelFrom, theSelTo)
			{
			  
			  var selLength = theSelFrom.length;
			  var selectedText = new Array();
			  var selectedValues = new Array();
			  var selectedCount = 0;
			  
			  var i;
			  
			
			  for(i=selLength-1; i>=0; i--)
			  {
				if(theSelFrom.options[i].selected)
				{
				  selectedText[selectedCount] = theSelFrom.options[i].text;
				  selectedValues[selectedCount] = theSelFrom.options[i].value;
				  deleteOption(theSelFrom, i);
				  selectedCount++;
				}
			  }
			  
			
			  for(i=selectedCount-1; i>=0; i--)
			  {
				addOption(theSelTo, selectedText[i], selectedValues[i]);
			  }
			  
			  if(NS4) history.go(0);
}
			
//bottom part

	function show_go(pull_down_val)
	{
		
		if(pull_down_val==3)
		{
			document.getElementById("on_change_show").style.display="";
			document.getElementById("on_change_show_pro_type").style.display="none";
		}
		else
		{
			document.getElementById("on_change_show").style.display="none";
			document.getElementById("on_change_show_pro_type").style.display="block";
		}
	}

function show_hide_div(id)
{
	
	
	if(document.getElementById('extra_price_'+id).style.display=='none')
	{
		
		
		document.new_product.elements['avaliable_day_price'+id].value="0.00";
		document.new_product.elements['avaliable_day_price'+id].disabled=true;
		document.getElementById('extra_price_'+id).style.display='block'
		
		if(document.getElementById('optionyes').style.visibility!='visible')
		{
			if(document.getElementById('optionno').style.visibility=='visible' || option_value_yn==1)
			{
				
				document.new_product.elements['avaliable_double_price'+id].value = "0.00";
				document.new_product.elements['avaliable_quadruple_price'+id].value = "0.00";
				document.new_product.elements['avaliable_triple_price'+id].value='0.00';
				document.new_product.elements['avaliable_single_pu_price'+id].value='0.00';
				
				document.new_product.elements['avaliable_double_price'+id].disabled=true;
				document.new_product.elements['avaliable_quadruple_price'+id].disabled=true;
				document.new_product.elements['avaliable_triple_price'+id].disabled=true;
				document.new_product.elements['avaliable_single_pu_price'+id].disabled=true;
				
				
			}
		}
		else
		{
				document.new_product.elements['avaliable_double_price'+id].value = "0.00";
				document.new_product.elements['avaliable_quadruple_price'+id].value = "0.00";
				document.new_product.elements['avaliable_triple_price'+id].value='0.00';
				document.new_product.elements['avaliable_single_pu_price'+id].value='0.00';
				
				document.new_product.elements['avaliable_double_price'+id].disabled=false;
				document.new_product.elements['avaliable_quadruple_price'+id].disabled=false;
				document.new_product.elements['avaliable_triple_price'+id].disabled=false;
				document.new_product.elements['avaliable_single_pu_price'+id].disabled=false;
		}		
		
		
	}
	else
	{
		document.new_product.elements['avaliable_day_price'+id].disabled=false;
		document.getElementById('extra_price_'+id).style.display='none'
	}
}

function show_hide_regular_div(id,option_value_yn)
{
	
	
	if(document.getElementById('regular_extra_price_'+id).style.display=='none')
	{
		
		
		document.new_product.elements['weekday_price'+id].value="0.00";
		//document.new_product.elements['weekday_price'+id].disabled=true;
		document.getElementById('regular_extra_price_'+id).style.display='block'
		
		if(document.getElementById('optionyes').style.visibility!='visible')
		{
			if(document.getElementById('optionno').style.visibility=='visible' || option_value_yn==1)
			{
				
				document.new_product.elements['weekday_double_price'+id].value = "0.00";
				document.new_product.elements['weekday_quadruple_price'+id].value = "0.00";
				document.new_product.elements['weekday_triple_price'+id].value='0.00';
				document.new_product.elements['weekday_single_pu_price'+id].value='0.00';
				
				document.new_product.elements['weekday_double_price'+id].disabled=true;
				document.new_product.elements['weekday_quadruple_price'+id].disabled=true;
				document.new_product.elements['weekday_triple_price'+id].disabled=true;
				document.new_product.elements['weekday_single_pu_price'+id].disabled=true;
				
				
			}
		}
		else
		{
				document.new_product.elements['weekday_double_price'+id].value = "0.00";
				document.new_product.elements['weekday_quadruple_price'+id].value = "0.00";
				document.new_product.elements['weekday_triple_price'+id].value='0.00';
				document.new_product.elements['weekday_single_pu_price'+id].value='0.00';
				
				document.new_product.elements['weekday_double_price'+id].disabled=false;
				document.new_product.elements['weekday_quadruple_price'+id].disabled=false;
				document.new_product.elements['weekday_triple_price'+id].disabled=false;
				document.new_product.elements['weekday_single_pu_price'+id].disabled=false;
		}		
		
		
	}
	else
	{
		document.new_product.elements['weekday_price'+id].disabled=false;
		document.getElementById('regular_extra_price_'+id).style.display='none'
	}
}

function delete_spe_price(first,second)
{
	id = first+"_"+second;
	document.new_product.elements['avaliable_single_price_'+id].value = "0.00";
	document.new_product.elements['avaliable_single_pu_price_'+id].value = "0.00";
	document.new_product.elements['avaliable_double_price_'+id].value = "0.00";
	document.new_product.elements['avaliable_triple_price_'+id].value = "0.00";
	document.new_product.elements['avaliable_quadruple_price_'+id].value = "0.00";
	document.new_product.elements['avaliable_kids_price_'+id].value = "0.00";
	
	document.new_product.elements['avaliable_day_price_'+id].disabled=false;
	document.new_product.elements['avaliable_single_pu_price_'+id].disabled=true;
	document.new_product.elements['avaliable_double_price_'+id].disabled=true;
	document.new_product.elements['avaliable_triple_price_'+id].disabled=true;
	document.new_product.elements['avaliable_quadruple_price_'+id].disabled=true;
	document.new_product.elements['avaliable_kids_price_'+id].disabled=true;
}


function delete_spe_price_regular(first,access_level)
{
	id = first;
	document.new_product.elements['weekday_single_price'+id].value = "0.00";
	document.new_product.elements['weekday_single_pu_price'+id].value = "0.00";
	document.new_product.elements['weekday_double_price'+id].value = "0.00";
	document.new_product.elements['weekday_triple_price'+id].value = "0.00";
	document.new_product.elements['weekday_quadruple_price'+id].value = "0.00";
	document.new_product.elements['weekday_kids_price'+id].value = "0.00";
	
	document.new_product.elements['weekday_price'+id].disabled=false;
	document.new_product.elements['weekday_single_price'+id].disabled=true;
	document.new_product.elements['weekday_single_pu_price'+id].disabled=true;
	document.new_product.elements['weekday_double_price'+id].disabled=true;
	document.new_product.elements['weekday_triple_price'+id].disabled=true;
	document.new_product.elements['weekday_quadruple_price'+id].disabled=true;
	document.new_product.elements['weekday_kids_price'+id].disabled=true;

    if(access_level==1)
	{
		document.new_product.elements['weekday_single_price_cost'+id].value = "0.00";
		document.new_product.elements['weekday_single_pu_price_cost'+id].value = "0.00";
		document.new_product.elements['weekday_double_price_cost'+id].value = "0.00";
		document.new_product.elements['weekday_triple_price_cost'+id].value = "0.00";
		document.new_product.elements['weekday_quadruple_price_cost'+id].value = "0.00";
		document.new_product.elements['weekday_kids_price_cost'+id].value = "0.00";
		
		document.new_product.elements['weekday_price_cost'+id].disabled=false;
		document.new_product.elements['weekday_single_price_cost'+id].disabled=true;
		document.new_product.elements['weekday_single_pu_price_cost'+id].disabled=true;
		document.new_product.elements['weekday_double_price_cost'+id].disabled=true;
		document.new_product.elements['weekday_triple_price_cost'+id].disabled=true;
		document.new_product.elements['weekday_quadruple_price_cost'+id].disabled=true;
		document.new_product.elements['weekday_kids_price_cost'+id].disabled=true;
			
	}
 

}





function enter_spec_price(first,second)
{
	id = first+"_"+second;
	if(document.getElementById('show_sub_div_d_'+id).style.display=='none')
	{
		document.new_product.elements['avaliable_day_price_'+id].value = "0.00";
		document.new_product.elements['avaliable_day_price_'+id].disabled=true;
		
		document.getElementById('show_sub_div_s_'+id).style.display='block'
		document.getElementById('hide_sub_div_s_'+id).style.display='none'
		
		document.getElementById('show_sub_div_sp_'+id).style.display='block'
		document.getElementById('hide_sub_div_sp_'+id).style.display='none'
		
		document.getElementById('show_sub_div_d_'+id).style.display='block'
		document.getElementById('hide_sub_div_d_'+id).style.display='none'
		
		document.getElementById('show_sub_div_t_'+id).style.display='block'
		document.getElementById('hide_sub_div_t_'+id).style.display='none'
		
		document.getElementById('show_sub_div_q_'+id).style.display='block'
		document.getElementById('hide_sub_div_q_'+id).style.display='none'
		
		document.getElementById('show_sub_div_k_'+id).style.display='block'
		document.getElementById('hide_sub_div_k_'+id).style.display='none'
		
		if(document.getElementById('optionyes').style.visibility!='visible')
		{
			if(document.getElementById('optionno').style.visibility=='visible' || option_value_yn==1)
			{
				
				document.new_product.elements['avaliable_double_price_'+id].value = "0.00";
				document.new_product.elements['avaliable_quadruple_price_'+id].value = "0.00";
				document.new_product.elements['avaliable_triple_price_'+id].value='0.00';
				document.new_product.elements['avaliable_single_pu_price_'+id].value = "0.00";
				
				document.new_product.elements['avaliable_double_price_'+id].disabled=true;
				document.new_product.elements['avaliable_quadruple_price_'+id].disabled=true;
				document.new_product.elements['avaliable_triple_price_'+id].disabled=true;
				document.new_product.elements['avaliable_single_pu_price_'+id].disabled=true;
				
				
			}
		}		
		
		
	}
	else
	{
	
		document.new_product.elements['avaliable_day_price_'+id].value = "0.00";
		document.new_product.elements['avaliable_day_price_'+id].disabled=false;
		
		document.getElementById('show_sub_div_s_'+id).style.display='none'
		document.getElementById('hide_sub_div_s_'+id).style.display='block'
		
		document.getElementById('show_sub_div_sp_'+id).style.display='none'
		document.getElementById('hide_sub_div_sp_'+id).style.display='block'
		
		document.getElementById('show_sub_div_d_'+id).style.display='none'
		document.getElementById('hide_sub_div_d_'+id).style.display='block'
		
		document.getElementById('show_sub_div_t_'+id).style.display='none'
		document.getElementById('hide_sub_div_t_'+id).style.display='block'
		
		document.getElementById('show_sub_div_q_'+id).style.display='none'
		document.getElementById('hide_sub_div_q_'+id).style.display='block'
		
		document.getElementById('show_sub_div_k_'+id).style.display='none'
		document.getElementById('hide_sub_div_k_'+id).style.display='block'
		
		if(document.getElementById('optionno').style.visibility!='visible')
		{
			document.new_product.elements['avaliable_double_price_'+id].value = "0.00";
			document.new_product.elements['avaliable_quadruple_price_'+id].value = "0.00";
			document.new_product.elements['avaliable_triple_price_'+id].value='0.00';
			
			document.new_product.elements['avaliable_double_price_'+id].disabled=false;
			document.new_product.elements['avaliable_quadruple_price_'+id].disabled=false;
			document.new_product.elements['avaliable_triple_price_'+id].disabled=false;
			
			document.new_product.elements['avaliable_single_pu_price_'+id].value='0.00';
			document.new_product.elements['avaliable_single_pu_price_'+id].disabled=true;
		}
	}	
}

function delete_spe_price_attri(first)
{
	id = first;
	document.new_product.elements['single_price['+id+']'].value = "0.0000";
	//document.new_product.elements['single_pu_price['+id+']'].value = "0.0000";
	document.new_product.elements['double_price['+id+']'].value = "0.0000";
	document.new_product.elements['triple_price['+id+']'].value = "0.0000";
	document.new_product.elements['quadruple_price['+id+']'].value = "0.0000";
	document.new_product.elements['kids_price['+id+']'].value = "0.0000";
	document.new_product.elements['price['+id+']'].disabled=false;
	document.new_product.elements['single_price['+id+']'].disabled=true;
	//document.new_product.elements['single_pu_price['+id+']'].disabled=true;
	document.new_product.elements['double_price['+id+']'].disabled=true;
	document.new_product.elements['triple_price['+id+']'].disabled=true;
	document.new_product.elements['quadruple_price['+id+']'].disabled=true;
	document.new_product.elements['kids_price['+id+']'].disabled=true;
	
	document.new_product.elements['single_price_cost['+id+']'].value = "0.0000";
	//document.new_product.elements['single_pu_price_cost['+id+']'].value = "0.0000";
	document.new_product.elements['double_price_cost['+id+']'].value = "0.0000";
	document.new_product.elements['triple_price_cost['+id+']'].value = "0.0000";
	document.new_product.elements['quadruple_price_cost['+id+']'].value = "0.0000";
	document.new_product.elements['kids_price_cost['+id+']'].value = "0.0000";
	
}
function enter_spec_price_attri(first)
{
	id = first;
	
	if(document.getElementById('show_sub_divattri_d_'+id).style.display=='none')
	{
		
		document.new_product.elements['price['+id+']'].value = "0.0000";
		//document.new_product.elements['price['+id+']'].disabled=true;
	
		
		document.getElementById('show_sub_divattri_s_'+id).style.display='block'
		document.getElementById('hide_sub_divattri_s_'+id).style.display='none'
		
		document.getElementById('show_sub_divattri_d_'+id).style.display='block'
		document.getElementById('hide_sub_divattri_d_'+id).style.display='none'
		
		document.getElementById('show_sub_divattri_t_'+id).style.display='block'
		document.getElementById('hide_sub_divattri_t_'+id).style.display='none'
		
		document.getElementById('show_sub_divattri_q_'+id).style.display='block'
		document.getElementById('hide_sub_divattri_q_'+id).style.display='none'
		
		document.getElementById('show_sub_divattri_k_'+id).style.display='block'
		document.getElementById('hide_sub_divattri_k_'+id).style.display='none'
		
		if(document.getElementById('optionyes').style.visibility!='visible')
		{
			
		
			if(document.getElementById('optionno').style.visibility=='visible' || option_value_yn==1)
			{	
				//document.new_product.elements['double_price['+id+']'].value = "0.0000";
				//document.new_product.elements['triple_price['+id+']'].value = "0.0000";
				//document.new_product.elements['quadruple_price['+id+']'].value = "0.0000";
				
				document.new_product.elements['double_price['+id+']'].disabled=true;
				document.new_product.elements['triple_price['+id+']'].disabled=true;
				document.new_product.elements['quadruple_price['+id+']'].disabled=true;
				
				//document.new_product.elements['single_pu_price['+id+']'].value = "0.0000";
				//document.new_product.elements['single_pu_price['+id+']'].disabled=true;
			}
		
		
		}
	}
	else
	{
		document.new_product.elements['price['+id+']'].disabled=false;
		document.new_product.elements['price['+id+']'].value = "0.0000";
		
		document.getElementById('show_sub_divattri_s_'+id).style.display='none'
		document.getElementById('hide_sub_divattri_s_'+id).style.display='block'
		
		document.getElementById('show_sub_divattri_d_'+id).style.display='none'
		document.getElementById('hide_sub_divattri_d_'+id).style.display='block'
		
		document.getElementById('show_sub_divattri_t_'+id).style.display='none'
		document.getElementById('hide_sub_divattri_t_'+id).style.display='block'
		
		document.getElementById('show_sub_divattri_q_'+id).style.display='none'
		document.getElementById('hide_sub_divattri_q_'+id).style.display='block'
		
		document.getElementById('show_sub_divattri_k_'+id).style.display='none'
		document.getElementById('hide_sub_divattri_k_'+id).style.display='block'
		if(document.getElementById('optionno').style.visibility!='visible')
		{	
			//document.new_product.elements['double_price['+id+']'].value = "0.0000";
			//document.new_product.elements['triple_price['+id+']'].value = "0.0000";
			//document.new_product.elements['quadruple_price['+id+']'].value = "0.0000";
			
			document.new_product.elements['double_price['+id+']'].disabled=false;
			document.new_product.elements['triple_price['+id+']'].disabled=false;
			document.new_product.elements['quadruple_price['+id+']'].disabled=false;
			
			//document.new_product.elements['single_pu_price['+id+']'].value = "0.0000";
			//document.new_product.elements['single_pu_price['+id+']'].disabled=true;
		}
	}
}

function getSelectedRadio(buttonGroup) {
  
   if (buttonGroup[0]) { 
      for (var i=0; i<buttonGroup.length; i++) {
         if (buttonGroup[i].checked) {
            return i
         }
      }
   } else {
      if (buttonGroup.checked) { return 0; } 
   }
  
   return -1;
} 

function getSelectedRadioValue(buttonGroup) {
  
   var i = getSelectedRadio(buttonGroup);
   if (i == -1) {
      return "";
   } else {
      if (buttonGroup[i]) { 
         return buttonGroup[i].value;
      } else { 
         return buttonGroup.value;
      }
   }
} 

function getSelectedCheckbox(buttonGroup) {
  
   var retArr = new Array();
   var lastElement = 0;
   if (buttonGroup[0]) { 
      for (var i=0; i<buttonGroup.length; i++) {
         if (buttonGroup[i].checked) {
            retArr.length = lastElement;
            retArr[lastElement] = i;
            lastElement++;
         }
      }
   } else { 
      if (buttonGroup.checked) { 
         retArr.length = lastElement;
         retArr[lastElement] = 0; 
      }
   }
   return retArr;
} 

function getSelectedCheckboxValue(buttonGroup) {
  
   var retArr = new Array(); 
   var selectedItems = getSelectedCheckbox(buttonGroup);
   if (selectedItems.length != 0) { 
      retArr.length = selectedItems.length;
      for (var i=0; i<selectedItems.length; i++) {
         if (buttonGroup[selectedItems[i]]) { 
            retArr[i] = buttonGroup[selectedItems[i]].value;
         } else { 
            retArr[i] = buttonGroup.value;
         }
      }
   }
   return retArr;
} 

function  change_title_single_adult(){
			var elm;
			elements = document.new_product.getElementsByTagName('div');			
				for( i=0, elm; elm=elements.item(i++); )
				{
					if (elm.getAttribute('id') == "label_single[]")
						{
							if(getSelectedCheckboxValue(document.new_product.display_room_option) == 0 ){
							elm.innerHTML = 'Adult';
							}else{
							elm.innerHTML = 'Single';
							}
							
						}							
				}		
}





/* ajax form js file code start */
var XMLHttpRequestObject = createXMLHttpRequestObject();
function createXMLHttpRequestObject()
{
  var XMLHttpRequestObject = false;
  
  try
  {
    XMLHttpRequestObject = new XMLHttpRequest();
  }
  catch(e)
  {
    var aryXmlHttp = new Array(
                               "MSXML2.XMLHTTP",
                               "Microsoft.XMLHTTP",
                               "MSXML2.XMLHTTP.6.0",
                               "MSXML2.XMLHTTP.5.0",
                               "MSXML2.XMLHTTP.4.0",
                               "MSXML2.XMLHTTP.3.0"
                               );
    for (var i=0; i<aryXmlHttp.length && !XMLHttpRequestObject; i++)
    {
      try
      {
        XMLHttpRequestObject = new ActiveXObject(aryXmlHttp[i]);
      } 
      catch(e){document.write("createXMLHttpRequestObject: XMLHttpRequestObject Error");}
    }
  }
  
  if (!XMLHttpRequestObject)
  {
    alert("Error: failed to create the XMLHttpRequest object.");
  }
  else 
  {
    return XMLHttpRequestObject;
  }
}

function checkFormInput(keyEvent, dataSource, idForm)
{
  keyEvent = (keyEvent) ? keyEvent: window.event;
  input = (keyEvent.target) ? keyEvent.target : keyEvent.srcElement;
  
  if(keyEvent.type == "checkbox")
  {
    keyEvent.value = keyEvent.checked;
  }
  else if(keyEvent.type == "radio")
  {
    keyEvent.value = keyEvent.checked;
    if (keyEvent.value)
    {
      for(i=0; i<document.getElementById(idForm).elements.length - 1; i++)
      {
       
        if(document.getElementById(idForm).elements[i].name==keyEvent.name || document.getElementById(idForm).elements[i].checked)
        {
          /*alert(document.getElementById(idForm).elements[i].name+':'+document.getElementById(idForm).elements[i].value);*/
          document.getElementById(idForm).elements[i].value = document.getElementById(idForm).elements[i].checked;
        }
      }
    } /* End: if (keyEvent.value)*/
  } /* End: if (keyEvent.type == "change")*/
}

// Removes leading whitespaces
function LTrim( value ) {
	
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
	
}

// Removes ending whitespaces
function RTrim( value ) {
	
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
	
}

// Removes leading and ending whitespaces
function trim( value ) {
	
	return LTrim(RTrim(value));
	
}

function clearForm(formIdent) 
{ 
  var form, elements, i, elm; 
  form = document.getElementById 
    ? document.getElementById(formIdent) 
    : document.forms[formIdent]; 

	if (document.getElementsByTagName)
	{
		elements = form.getElementsByTagName('input');
		for( i=0, elm; elm=elements.item(i++); )
		{
			if (elm.getAttribute('type') == "text")
			{
				elm.value = '';
			}			
		}
		
		
		elements = form.getElementsByTagName('textarea');
		for( i=0, elm; elm=elements.item(i++); )
		{
			elm.innerHTML = '';
		}

	}

	// Actually looking through more elements here
	// but the result is the same.
	else
	{
		elements = form.elements;
		for( i=0, elm; elm=elements[i++]; )
		{
			if (elm.type == "text")
			{
				elm.value ='';
			}
		}
	}
}

function sendFormData(idForm, dataSource, divID, ifLoading, refreshtrue, refresh_url, Js_code)
{
  var postData='';
  var strReplaceTemp;
  
  if(XMLHttpRequestObject)
  {
    XMLHttpRequestObject.open("POST", dataSource);
    //XMLHttpRequestObject.setRequestHeader("Method", "POST " + dataSource + " HTTP/1.1");//24.0以上的火狐版用它会生产错误
	  XMLHttpRequestObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  
    XMLHttpRequestObject.onreadystatechange = function()
    {
      if (XMLHttpRequestObject.readyState == 4 &&
          XMLHttpRequestObject.status == 200)
      {
        try
        {
          var objDiv = document.getElementById(divID);
          objDiv.innerHTML = XMLHttpRequestObject.responseText;
		  if(Js_code!="" && typeof(Js_code)!="undefined"){
		  	eval(Js_code);
		  }
		  if(refreshtrue == 'refreshtrue'){
			Refresher(1, refresh_url);
		  }
        }
        catch(e){document.write("sendFormData: getElementById(divID) Error");}
      }
      else
      {
        if(ifLoading)
        {
          try
          {
            var objDiv = document.getElementById(divID);
            objDiv.innerHTML = "<span style='margin-left:15px;'><img src=images/loading.gif></span> <span>Requesting content...</span>";
          }
          catch(e){document.write("sendFormData->ifLoading: getElementById(divID) Error");}
        }
      }
    }  
    for(i=0; i<document.getElementById(idForm).elements.length ; i++)
    {
		 document.getElementById(idForm).elements[i].name = document.getElementById(idForm).elements[i].name.replace(/\[\]/g, "");
		 document.getElementById(idForm).elements[i].name = document.getElementById(idForm).elements[i].name.replace(/\[/g, "--leftbrack");
		 document.getElementById(idForm).elements[i].name = document.getElementById(idForm).elements[i].name.replace(/\]/g, "rightbrack--");
	
		if (document.getElementById(idForm).elements[i].type == "radio"  || document.getElementById(idForm).elements[i].type == "checkbox") {
               if (document.getElementById(idForm).elements[i].checked) {                
				 strReplaceTemp = document.getElementById(idForm).elements[i].name;
				 postData += "&aryFormData["+strReplaceTemp+"][]="+document.getElementById(idForm).elements[i].value;
	
			   }
        }else{
			 
			  strReplaceTemp = document.getElementById(idForm).elements[i].name.replace(/\[\]/i, "");
			// postData += "&aryFormData["+strReplaceTemp+"][]="+document.getElementById(idForm).elements[i].value.replace(/&/g, "@@amp;");
			 postData += "&aryFormData["+strReplaceTemp+"][]="+amptrim(document.getElementById(idForm).elements[i].value);
		}	
	
	}
    postData += "&parm="+new Date().getTime();
    try
    {
      XMLHttpRequestObject.send(postData);
     ifLoading = false;
    }
    catch(e){document.write("sendFormData: XMLHttpRequestObject.send Error");}
  }
}


 function setOpacity(id, level) {            
            var element = document.getElementById(id); 
            element.style.display = 'inline';           
            element.style.zoom = 1;
            element.style.opacity = level;
            element.style.MozOpacity = level;
            element.style.KhtmlOpacity = level;
            element.style.filter = "alpha(opacity=" + (level * 100) + ");";
        }

        function fadeIn(id, steps, duration, interval, fadeOutSteps, fadeOutDuration){  
            var fadeInComplete;      
            for (i = 0; i <= 1; i += (1 / steps)) {
              setTimeout("setOpacity('" + id + "', " + i + ")", i * duration); 
              fadeInComplete = i * duration;             
            }
                    
            setTimeout("fadeOut('" + id + "', " + fadeOutSteps + ", " + fadeOutDuration + ")", fadeInComplete + interval);           
        }

        function fadeOut(id, steps, duration) {         
            var fadeOutComplete;       
            for (i = 0; i <= 1; i += (1 / steps)) {
              setTimeout("setOpacity('" + id + "', "  + (1 - i) + ")", i * duration);
              fadeOutComplete = i * duration;
            }      
                  
            setTimeout("fadeHide('" + id + "')", fadeOutComplete);     
        }   

        function fadeHide(id){
            document.getElementById(id).style.display = 'none';     
        }
		

		function amptrim(str) {
		s = new String(str);
		var bull=String.fromCharCode(8226);
		var middot=String.fromCharCode(183);		
		s = s.replace(new RegExp(bull,"gi"),"&bull;");		   
		s = s.replace(new RegExp(middot,"gi"),"&bull;");
		s = s.replace(/&/g,"@@amp;");
		s = s.replace(/\+/g,"@@plush;");
		return s;
		} 

		
		
/* ajax form js file code end */

function toggleBox_edit(szDivID,zzDivID) 
{
  if (document.layers) 
  {   
      document.layers[zzDivID].visibility = "hide";
      document.layers[zzDivID].display = "none";
      document.layers[szDivID].visibility = "show";
      document.layers[szDivID].display = "inline";
     
  } else if (document.getElementById) 
  { 
    var obj = document.getElementById(szDivID);
	var obj1 = document.getElementById(zzDivID);
      obj1.style.visibility = "hidden";
      obj1.style.display = "none";
      obj.style.visibility = "visible";
      obj.style.display = "inline";
    
  } else if (document.all) 
  { 
      document.all[zzDivID].style.visibility = "hidden";
      document.all[zzDivID].style.display = "none";
      document.all[szDivID].style.visibility = "visible";
      document.all[szDivID].style.display = "inline";
    
  }
}


function SelectAll_ajax(combo,departurecombo,departurestartcombo)
{
	
		for(var i=0;i<combo.options.length;i++)
		{
			 combo.options[i].selected=true;		
			 if(i==0){
				document.new_product.selectedcityid.value = document.new_product.sel2.options[i].value;
			 }else{
				document.new_product.selectedcityid.value = document.new_product.selectedcityid.value+"::"+document.new_product.sel2.options[i].value;
			 }			
		}
		
		if(document.new_product.selectedcityid.value == "")
		{
	        alert("Please enter the Destination City");	
			document.new_product.sel1.focus();
			return false;
		}
		

		

		for(var j=0;j<departurecombo.options.length;j++)
		{			
			 if(j==0){				
				document.new_product.departure_end_city_id.value = departurecombo.options[j].value;
			 }else{				 
				document.new_product.departure_end_city_id.value = document.new_product.departure_end_city_id.value+","+departurecombo.options[j].value;
			 }			
		}
		
		
		for(var jj=0;jj<departurestartcombo.options.length;jj++)
		{			
			 if(jj==0){				
				document.new_product.departure_city_id.value = departurestartcombo.options[jj].value;
			 }else{				 
				document.new_product.departure_city_id.value = document.new_product.departure_city_id.value+","+departurestartcombo.options[jj].value;
			 }			
		}
}

function calcRound(num) {
	result = num.toString();
	if(result.indexOf(".") > -1){
		results = result.split('.');
		result = results[0]+"."+results[1].substring(0,2);
	}
	
	/*
	if((num-Math.floor(num)) > 0){	
		result = (num - (num-Math.floor(num)))+1+".00";
	}*/
	return(result);
}

/**
function calculate_retail_price(frmmargin,frmcostprice,frmretailprice){
	
		objmargin = frmmargin.value;
		//objretailprice = parseFloat(frmcostprice.value);
		objcostprice = parseFloat(frmcostprice.value);
		
		if(isNaN(objmargin)){
		alert("Please enter integer number for Margin field");
		frmmargin.focus();
		return true;
		}		
		
		if(objcostprice == 'NaN' || objcostprice == '' || objcostprice == '0'){
		alert("Please enter valid valie for Cost Price field");
		frmcostprice.focus();
		return true;
		}
		
		//objretailprice = objcostprice + (objcostprice * (objmargin/100));
		
		objretailprice = objcostprice / (1-(objmargin/100));
		
		frmretailprice.value = calcRound(parseFloat(objretailprice));		
		
	return true;
}
**/
//*算底价功能
function calculate_retail_price(frmmargin,frmcostprice,frmretailprice){
	
		objmargin = frmmargin.value;
		frmretailprice = parseFloat(frmretailprice.value);
		
		if(isNaN(objmargin)){
			alert("Please enter integer number for Margin field");
		frmmargin.focus();
			return true;
		}		
		
		if(frmretailprice == 'NaN' || frmretailprice == '' || frmretailprice == '0'){
			alert("请输入卖价Retail");
		frmretailprice.focus();
			return true;
		}
		
		objcostprice = frmretailprice * (1 - objmargin/100);
		
		frmcostprice.value = calcRound(parseFloat(objcostprice));		
		
	return true;
}

/*categories tree javascript functions for related categories page*/

function addOptiontree(theSel, theText, theValue)
		{
		  var newOpt = new Option(theText, theValue);
		  var selLength = theSel.length;
		  theSel[selLength] = newOpt;
		}
		
function deleteOptiontree(theSel, theIndex)
		{ 
		//alert(theIndex);
		  var selLength = theSel.length;
		  if(selLength>0)
		  {
			theSel.options[theIndex] = null;
		  }
		}
		
function moveOptionstree(theSelFrom, theSelTo)
		{
		
		  var selLength = theSelFrom.length;
		  var selectedText = new Array();
		  var selectedValues = new Array();
		  var selectedCount = 0;
		  
		  var i;
		  
		
		  for(i=selLength-1; i>=0; i--)
		  {
			if(theSelFrom[i].checked)
			{
			  var splitstr = theSelFrom[i].value;
			  splitval = splitstr.split('!!###!!');
			  selectedText[selectedCount] = splitval[1];
			  selectedValues[selectedCount] = splitval[0];
			  //deleteOptiontree(theSelFrom, i);
			  selectedCount++;
			}
			else
			{
			
			}
		  }
		  
		
		  for(i=selectedCount-1; i>=0; i--)
		  {
			/*alert(selectedText[i]);
			alert(selectedValues[i]);
			alert(theSelTo.options[0].text);*/
			var is_exist = 0;
			var checklength = theSelTo.length;
			for(j=0;j<checklength;j++)
			{
				if(theSelTo.options[j].value==selectedValues[i]){
					is_exist=1;
				}
			}
			//alert(is_exist);
			if(is_exist!=1){
				addOptiontree(theSelTo, selectedText[i], selectedValues[i]);
			}
		  }
		  
			 // if(NS4) history.go(0);
		}


function moveOptionsRight(theSelFrom, theSelTo)
		{
				  
		  var selLength = theSelFrom.length;
		  var selectedText = new Array();
		  var selectedValues = new Array();
		  var selectedCount = 0;
		  
		  var i;
		  
		
		  for(i=selLength-1; i>=0; i--)
			  {
				if(theSelFrom.options[i].selected)
				{
				  selectedText[selectedCount] = theSelFrom.options[i].text;
				  selectedValues[selectedCount] = theSelFrom.options[i].value;
				  deleteOptiontree(theSelFrom, i);
				  var seltolength = theSelTo.length;
				  for(j=0;j<seltolength;j++){
				  	var splitstr = theSelTo[j].value;
					  splitval = splitstr.split('!!###!!');
					if(splitval[0]==selectedValues[selectedCount]){
						theSelTo[j].checked=true;
					}
				  }
				  selectedCount++;
				}
			  }
		  
		}
		
/*categories tree javascript functions for related categories page*/
function all_attractions_submit(){
	
	var from_obj = document.getElementById('new_category')
	
	/*if(from_obj.elements['categories_top_attractions_tourtab_temp1']!= 'undefined' && from_obj.elements['categories_top_attractions_tourtab_temp1'].options.length > 10){
		alert('Day Trip Top Attractions should not be more than 10');
		from_obj.elements['categories_top_attractions_tourtab_temp1'].focus();
		//return false;
	}
	
	if(from_obj.elements['categories_top_attractions_temp1']!= 'undefined' && from_obj.elements['categories_top_attractions_temp1'].options.length > 10){
		alert('Multi-Day Top Attractions should not be more than 10');
		from_obj.elements['categories_top_attractions_temp1'].focus();
		//return false;
	}
	*/
	 
		SelectAll_ajax_attractions(from_obj.elements['categories_top_attractions_tourtab_temp1'], from_obj.elements['categories_top_attractions_tourtab']);
	 	SelectAll_ajax_attractions(from_obj.elements['categories_top_attractions_temp1'], from_obj.elements['categories_top_attractions']);
	 	//SelectAll_ajax_attractions(from_obj.elements['categories_more_dept_cities_temp1'], from_obj.elements['categories_more_dept_cities']);
	 	SelectAll_ajax_attractions(from_obj.elements['categories_destinations_temp1'], from_obj.elements['categories_destinations']);
	 
	// return true;
}
function SelectAll_ajax_attractions(combo, hiddenfield)
{
		var total_options = combo.options.length;
		/*if(total_options > 10){
			total_options = 10;
		}*/
			for(var i=0;i<total_options;i++)
			{
				 combo.options[i].selected=true;		
				 if(i==0){
					hiddenfield.value = combo.options[i].value;
				 }else{
					hiddenfield.value = hiddenfield.value+","+combo.options[i].value;
				 }			
			}
			
}
function Refresher(t, refresh_url) {
   if(t) refresh = setTimeout("document.location='"+refresh_url+"';", t*1000);
}
//hotel-extension begin
/* main tabs - add more */
function SelectAll_hotels(combo, hiddenfield)
{
		var total_options = combo.options.length;
			for(var i=0;i<total_options;i++)
			{
				 combo.options[i].selected=true;		
				 if(i==0){
					hiddenfield.value = combo.options[i].value;
				 }else{
					hiddenfield.value = hiddenfield.value+","+combo.options[i].value;
				 }			
			}
}
/* main tabs - add more */




function addEventExtraHotel()
{
	var ni = document.getElementById('myDivHotel');
	var numi = document.getElementById('theValueHotel');
	var num = (document.getElementById("theValueHotel").value -1)+ 2;
	numi.value = num;
	var divIdName = "my"+num+"DivHotel";
	var newdiv = document.createElement('div');
	newdiv.setAttribute("id",divIdName);
	newdiv.innerHTML = "<table width='70%' border='0' cellspacing='3' cellpadding='3'><tr><td valign='top' width='210' align='left'><input type='file' name='products_hotel_image_name[]'></td><td valign='top' width='160' align='left'><input type='text' name='products_hotel_image_title[]' value=''></td><td valign='top' width='200' align='left'><input type='text' name='hotel_image_sort_order[]' size='10' value='1'></td><td valign='top' width='150' align='left'><a href=\"javascript:;\" onclick=\"removeEventHotel(\'"+divIdName+"\')\">Remove</a></td></tr></table>";
	ni.appendChild(newdiv);
}
function removeEventHotel(divNum)
{
var d = document.getElementById('myDivHotel');
var olddiv = document.getElementById(divNum);
d.removeChild(olddiv);
}
//hotel-extension end

/**
 * 打开图片库列出对应景点的所有图片
 *
 * @param image_input_box_obj 存放图片路径的输入框
 * @param image_type 要取得的图片类型有'thumb'和'detail'可选
 * @param city_ids 景点城市ID串
 * @param preview_box_id 图片预览框ID 如果为空则不显示预览
 */
function open_picture_db(image_input_box_obj,image_type,city_ids,preview_box_id){
	//alert(image_input_box_obj.name+':'+image_type);
	if(image_input_box_obj.id !="" && city_ids!="" && typeof(city_ids)!='undefined'){
		var url = 'categories_ajax_sections.php?action=open_picture_db&image_input_box_id='+image_input_box_obj.id+'&image_type='+image_type+'&city_ids='+city_ids;
		if( typeof(preview_box_id)!='undefined' && preview_box_id!=""){
			url += '&preview_box_id='+preview_box_id;
		}
		ajax_get_submit(url);
	}else{
		if(image_input_box_obj.id==""){
			alert('图片输入框没有ID，请检查！');
		}
		if(typeof(city_ids)=='undefined' || city_ids==""){
			alert('城市景点为空，请检查！');
		}
	}
}

/**
 * 选择图片库的图片到图片输入框
 *
 * @param image_input_box_obj 存放图片路径的输入框
 * @param image_type 图片类型有'thumb'和'detail'可选
 * @param radio 图片单选框
 * @param preview_box_id 图片预览框ID 如果为空则不显示预览
 */
function get_picture_db_to_input_box(image_input_box_id,image_type,radio,preview_box_id){
	var id = '#'+image_input_box_id;
	if(image_type=='thumb'){
		$(id).val($(radio).attr('thumbsrc'));
	}
	if(image_type=='detail'){
		$(id).val($(radio).attr('detailsrc'));
	}
	if(typeof(preview_box_id)!='undefined' && preview_box_id!=""){
		var preview_id = '#'+preview_box_id;
		$(preview_id).attr('src', $(radio).attr('thumbsrc') );
	}
	document.getElementById('imageslayr_0').style.display='none';
}

//复制产品行程到其他产品
function copy_travel(btn, s_products_id, t_products_id, travel_index, cp_range){
	if(confirm('请确认您是否真的要用此行程内容替换？')){
		//检查t_products_id
		if(t_products_id.search(/^[0-9]+(,[0-9]+)*$/) == -1){
			alert('产品ID不符合要求！');
			return false;
		}
		//复制范围设置
		var copy_range = '';
		$(cp_range).each( function(n){
			if(this.value == 'all' && this.checked == true) {
				copy_range = 'all';
				return false;
			}else if(this.checked == true){
				copy_range += this.value +',';
			}
		});
		if(copy_range==''){
			alert('请选择复制范围！');
			return false;
		}
		if(s_products_id>1 && travel_index>0){
			$(btn).attr('disabled', true).html('正在复制……');
			var url = 'categories.php?action=copy_travel';
			$.post(url,{ 'source_products_id':s_products_id, 'target_products_id':t_products_id, 'travel_index':travel_index, 'copy_range':copy_range },function(text){
				if(text=='success'){
					$(btn).html('复制成功！').attr('disabled', false);
				}else{
					alert('出错了，找管理员！');
				}
			},'text');
		}
	}
}
