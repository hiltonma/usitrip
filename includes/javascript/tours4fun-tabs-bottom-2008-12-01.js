function CheckForHash(){
			if(document.location.hash){
			
						var HashLocationName = document.location.hash;
						HashLocationName1 = HashLocationName.replace("#","");
						if((HashLocationName1 != document.getElementById('last_ajax_hash').innerHTML) && HashLocationName1 != "" ){
						
								var pagingquerystring = '';
								var vp_pagingquerystring = '';
								var page_pagingquerystring = '';
								var page1_pagingquerystring = '';
								var parameters_hash_array = new Array();							
								
								parameters_hash_array['p'] == '';
								parameters_hash_array['rn'] == '';
								parameters_hash_array['tt'] == '';
								parameters_hash_array['dn'] == '';
								parameters_hash_array['dc'] == '';
								parameters_hash_array['at'] == '';
								parameters_hash_array['s'] == 0;
								
								parameters_hash_array['vp'] == '';								
								parameters_hash_array['vtt'] == '';
								parameters_hash_array['vdn'] == '';
								parameters_hash_array['vdc'] == '';
								parameters_hash_array['vat'] == '';
								parameters_hash_array['vs'] == 0;
								
								
								var hashparameters = HashLocationName1.split("/");
								for(i=0;i<hashparameters.length;i++){
									parameters_hash_values = hashparameters[i].split("-");
									if(parameters_hash_values.length>2){
										var parameters_hash_array_value = hashparameters[i].replace(parameters_hash_values[0]+"-","");
										parameters_hash_array[parameters_hash_values[0]] = parameters_hash_array_value;
									}else{
										parameters_hash_array[parameters_hash_values[0]] = parameters_hash_values[1];
									}
								}
								
								if(parameters_hash_array['pid'] != '' && parameters_hash_array['pid'] != null){
									if(parameters_hash_array['rn'] != '' && parameters_hash_array['rn'] != null){
										page_pagingquerystring += '&rn='+parameters_hash_array['rn'];											
										sendFormData('frm_slippage_ajax_product_review_top', 'product_reviews_tabs_ajax.php?mnu=reviews&products_id='+parameters_hash_array['pid']+page_pagingquerystring+'', 'review_desc_body', true);																			
									}else{			
									page_pagingquerystring += '&page='+parameters_hash_array['p'];	
										sendFormData('frm_slippage_ajax_product_qas', 'question_info.php?mnu=qanda&products_id='+parameters_hash_array['pid']+page_pagingquerystring+'', 'c_tech', true);
									}
								
								}else if((parameters_hash_array['p'] != '' && parameters_hash_array['p'] != null)||(parameters_hash_array['tt'] != '' && parameters_hash_array['tt'] != null)||(parameters_hash_array['dn'] != '' && parameters_hash_array['dn'] != null)||(parameters_hash_array['dc'] != '' && parameters_hash_array['dc'] != null)||(parameters_hash_array['s'] != '' && parameters_hash_array['s'] != null)||(parameters_hash_array['at'] != '' && parameters_hash_array['at'] != null)){
								if(parameters_hash_array['p'] != '' && parameters_hash_array['p'] != null){
									page_pagingquerystring += '&page='+parameters_hash_array['p'];
								}
								
								if(parameters_hash_array['tt'] != '' && parameters_hash_array['tt'] != null){
									document.sort_order.tours_type.value = parameters_hash_array['tt'];
								}else{
									document.sort_order.tours_type.value = '';
								}
								if(parameters_hash_array['dn'] != '' && parameters_hash_array['dn'] != null){
									document.sort_order.products_durations.value = parameters_hash_array['dn'];				
									if(parameters_hash_array['dn']=='4-4'){
										document.getElementById('pd4').className = 'duration_label_sel';
									}else if(parameters_hash_array['dn']=='5-6'){
										document.getElementById('pd56').className = 'duration_label_sel';
									}else if(parameters_hash_array['dn']=='7-'){
										document.getElementById('pd7').className = 'duration_label_sel';
									}
								}else{
									document.sort_order.products_durations.value = '';
								}
								if(parameters_hash_array['dc'] != '' && parameters_hash_array['dc'] != null){
									document.sort_order.departure_city_id.value = parameters_hash_array['dc'];
								}else{
									document.sort_order.departure_city_id.value = '';
								}
								if(parameters_hash_array['at'] != '' && parameters_hash_array['at'] != null){
									document.sort_order.top_attractions.value = parameters_hash_array['at'];				
									//document.getElementById('left_topatt_city_'+parameters_hash_array['at']).className = 's';
									//checkeredioelemntofform('search_top_attractions',parameters_hash_array['at']);
								}else{
									document.sort_order.top_attractions.value = '';									
									//checkeredioelemntofform('search_top_attractions','');
								}
								if(parameters_hash_array['s'] != '' && parameters_hash_array['s'] != null && parameters_hash_array['s'] != 0){
									document.sort_order.sort.value = parameters_hash_array['s'];
									if(parameters_hash_array['s']=='3a'){
										document.getElementById('sort_label_asc').className = 'sort_label_asc_sel';
									}else if(parameters_hash_array['s']=='3d'){
										document.getElementById('sort_label_desc').className = 'sort_label_desc_sel';
									}
								}else{
									document.sort_order.sort.value = 0;
								}
								
								sendFormData('sort_order', 'product_listing_index_products_ajax.php?cPath='+parameters_hash_array['cp']+page_pagingquerystring+'', 'div_product_listing', true);
								
								
								}else if((parameters_hash_array['vp'] != '' && parameters_hash_array['vp'] != null)||(parameters_hash_array['vtt'] != '' && parameters_hash_array['vtt'] != null)||(parameters_hash_array['vdn'] != '' && parameters_hash_array['vdn'] != null)||(parameters_hash_array['vdc'] != '' && parameters_hash_array['vdc'] != null)||(parameters_hash_array['vs'] != '' && parameters_hash_array['vs'] != null)||(parameters_hash_array['vat'] != '' && parameters_hash_array['vat'] != null)){
								
								if(parameters_hash_array['vp'] != '' && parameters_hash_array['vp'] != null){
									page1_pagingquerystring += '&page1='+parameters_hash_array['vp'];
								}
								
								if(parameters_hash_array['vtt'] != '' && parameters_hash_array['vtt'] != null){
									document.sort_order_vacktion_package.tours_type1.value = parameters_hash_array['vtt'];
								}else{
									document.sort_order_vacktion_package.tours_type1.value = '';
								}
								if(parameters_hash_array['vdn'] != '' && parameters_hash_array['vdn'] != null){
									document.sort_order_vacktion_package.products_durations1.value = parameters_hash_array['vdn'];
									if(parameters_hash_array['vdn']=='4-4'){
										document.getElementById('pd4').className = 'duration_label_sel';
									}else if(parameters_hash_array['vdn']=='5-6'){
										document.getElementById('pd56').className = 'duration_label_sel';
									}else if(parameters_hash_array['vdn']=='7-'){
										document.getElementById('pd7').className = 'duration_label_sel';
									}
								}else{
									document.sort_order_vacktion_package.products_durations1.value = '';
								}
								if(parameters_hash_array['vdc'] != '' && parameters_hash_array['vdc'] != null){
									document.sort_order_vacktion_package.departure_city_id1.value = parameters_hash_array['vdc'];
								}else{
									document.sort_order_vacktion_package.departure_city_id1.value = '';
								}
								if(parameters_hash_array['vat'] != '' && parameters_hash_array['vat'] != null){
									//document.sort_order_vacktion_package.top_attractions1.value = parameters_hash_array['vat'];
									//document.getElementById('left_topatt_city_'+parameters_hash_array['vat']).className = 's';
									//checkeredioelemntofform('search_top_attractions',parameters_hash_array['vat']);
								}else{
									//document.sort_order_vacktion_package.top_attractions1.value = '';
									//checkeredioelemntofform('search_top_attractions','');
								}
								if(parameters_hash_array['vs'] != '' && parameters_hash_array['vs'] != null && parameters_hash_array['vs'] != 0){
									document.sort_order_vacktion_package.sort1.value = parameters_hash_array['vs'];
									if(parameters_hash_array['vs']=='3a'){
										document.getElementById('sort_label_asc').className = 'sort_label_asc_sel';
									}else if(parameters_hash_array['vs']=='3d'){
										document.getElementById('sort_label_desc').className = 'sort_label_desc_sel';
									}
								}else{
									document.sort_order_vacktion_package.sort1.value = 0;
								}
																
								sendFormData('sort_order_vacktion_package', 'product_listing_index_vackation_packages.php?cPath='+parameters_hash_array['cp']+page1_pagingquerystring+'', 'div_product_vackation_packages', true);
								
								}
								
														
								document.getElementById('last_ajax_hash').innerHTML = HashLocationName1;
								
								document.location.hash = HashLocationName1;
		
						}
		
						
			}
		}

var HashCheckInterval=setInterval("CheckForHash()",300);
try{
cssdropdown.startchrome("bottomcstnote");
}catch(e){}