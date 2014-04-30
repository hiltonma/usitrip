<?php
if($_SESSION['hawaii_self']){
	//print_r($_SESSION['hawaii_self']['free_combination_day']);
	//exit;
}
/*
夏威夷自助页面

本页面的内容如果有$_SESSION['hawaii_self']，将优先引用$_SESSION['hawaii_self']内各项的值，在选择酒店和清除酒店行程的时候都需要重新整理一次该值
tours_day
room-0-adult-total
room-0-child-total

hotel_ids
tour_ids
start_dates
end_dates
*/

?>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
<script type="text/javascript"> 
//开始自由行的提交
function free_combination_day_html(){
	var from = document.getElementById('cart_quantity');
	var day_num = from.elements['tours_day'].value;
	var html_code = '';
	for(i=0; i<day_num; i++){
		class_name = 'daybyday daybyday_ou';
		if(i%2==0){
			class_name = 'daybyday';
		}
		if(i==0){
			class_name = ' daybyday s ';
		}
		if(i%5==0 && i>0){
			//html_code += '</div><div>';
		}
		html_code += '<div class="'+class_name+'" id="free_'+i+'"><div class="day_box" id="free_box_'+i+'"><p class="day_title" id="free_p_'+i+'"><span><?php echo db_to_html("第 '+(i+1)+' 天")?> </span><a id="EmptyButton_'+i+'" href="javascript:void(0)" onClick="ClearHasBeenSelectedHotels(this)"><?php echo db_to_html('清空')?></a></p><div id="free_img_'+i+'"><img src="image/example_tu.gif" width="93" height="60" ></div><p class="day_des" id="free_hotel_name_'+i+'"></p><p class="day_des2" id="free_des_text_'+i+'"></p></div></div>';
	}
	
	var free_combination_day = document.getElementById('free_combination_day');
	var free_combination_step2 = document.getElementById('free_combination_step2');
	free_combination_day.innerHTML = html_code;
	free_combination_step2.style.display = '';
	var Hotel_Cearch_Results = document.getElementById('hotel_cearch_results');
	Hotel_Cearch_Results.innerHTML = '';
	EmptySubMitTours();

	hc_f = document.getElementById('hotel_cearch_form');
	hc_f.elements['filtration'].value = '';
	
	var tmp_box = document.getElementById('tmp_box');
	tmp_box.innerHTML = '';
	
}

//清空按钮
function ClearHasBeenSelectedHotels(obj){
	var num = Number(obj.id.replace(/EmptyButton_/,''));
	var free_img = document.getElementById('free_img_'+num);
	var need_re_load = false;
	//判断是否已经被填充
	if(free_img!=null){
		var img = free_img.getElementsByTagName('img');
		if(img[0].src.indexOf("example_tu.gif")>-1){	//空的日程移除
			var free = document.getElementById('free_'+num);
			var obj_f = document.getElementById('free_combination_day');
			var div = obj_f.getElementsByTagName('div');
			var rm_all = false;
			if(rm_all==true){		//当天及以后的所有天将均会被删除
				if(window.confirm("<?php echo db_to_html("当天及以后的所有天将均会被删除，要继续吗？\t")?>")==true){
					for(h=0; h<div.length; h++){
						if(div[h].id.search(/^free_[0-9]+$/)>-1 && Number(div[h].id.replace(/free_/,'')) >= num ){
							var rm_div = div[h];
							rm_div.parentNode.removeChild(rm_div);
							h--;
						}
					}
					need_re_load = true;
				}
			}else{	//只删除当前选的天
				free.parentNode.removeChild(free);
				need_re_load = true;
			}


		}else if(img[0].src.length>1){
			if(window.confirm("<?php echo db_to_html("您真的要清空该酒店及其行程吗？\t")?>")==true){
				//取得当前删除点开始点和结束点
				var free_des_text = document.getElementById('free_des_text_'+num);
				
				if(free_des_text.innerHTML.length > 2){	//开始和结尾的删除点处理
					var input = free_des_text.getElementsByTagName('input');
					if(input.length>0){//如果当前删除点是此行程段的最后一点，则从后往前删
						for(i=num; i>=0; i--){
							var stop_action = false;
							if(document.getElementById('free_des_text_'+i).innerHTML.length > 2 && num!=i){
								 stop_action = true;
							}
							var rm_obj = document.getElementById('free_'+i);
							rm_obj.parentNode.removeChild(rm_obj);
							if(stop_action==true){
								break;
							}
						}
					}else{	//如果当前删除点是此行程段的第一点，则从前往后删
						for(i=num; i<1000; i++){
							var stop_action = false;
							if(document.getElementById('free_des_text_'+i).innerHTML.length > 2 && num!=i){
								 stop_action = true;
							}
							var rm_obj = document.getElementById('free_'+i);
							rm_obj.parentNode.removeChild(rm_obj);
							if(stop_action==true){
								break;
							}
						}
					}
				}else{	//中间的删除点处理
					for(i=num; i<1000; i++){
						var stop_action = false;
						if(document.getElementById('free_des_text_'+i).innerHTML.length > 2 ){
							 stop_action = true;
						}
						var rm_obj = document.getElementById('free_'+i);
						rm_obj.parentNode.removeChild(rm_obj);
						if(stop_action==true){
							break;
						}
					}
					for(i=num; i>=0; i--){
						var stop_action = false;
						var rm_obj = document.getElementById('free_'+i);
						if(rm_obj!=null){
							if(document.getElementById('free_des_text_'+i).innerHTML.length > 2 ){
								 stop_action = true;
							}
							rm_obj.parentNode.removeChild(rm_obj);
							if(stop_action==true){
								break;
							}
						}
					}
				}
				
				need_re_load = true;
			}
		}
	}
	
	if(need_re_load==true){
		//清空后不再显示已选择的行程内容
		var Hotel_Cearch_Results = document.getElementById('hotel_cearch_results');
		var hotel_div = Hotel_Cearch_Results.getElementsByTagName('div');
		for(i=0; i<hotel_div.length; i++){
			if(hotel_div[i].className =="hotel_single_slect"){
				Hotel_Cearch_Results.innerHTML = '';
				break;
			}
		}
		
		//删除后需要重新整理行程列表
		var free_sum_num = get_free_num();
		var tmp_box = document.getElementById('tmp_box');
		var free_combination_day = document.getElementById('free_combination_day');
		tmp_box.innerHTML = free_combination_day.innerHTML;
		free_combination_day.innerHTML = '';
		//重新组建free_combination_day的子对象
		for(i=0; i<free_sum_num; i++){
			var free_ = document.createElement('div');
			var class_ = 'daybyday daybyday_ou';
			if(i%2==0){
				class_ = 'daybyday';
			}
			if(document.all){	//ie
				free_.className = class_;
				free_.id = 'free_'+i;
			}else{
				free_.setAttribute("class",class_);
				free_.setAttribute("id",'free_'+i);
			}
			
			//取得tmp_box.free_对应的子代码
			var free_tmp = tmp_box.getElementsByTagName('div');
			var free_no = 0;
			var tmp_html_code = '';
			for(j=0; j<free_tmp.length; j++){
				if(free_tmp[j].id.search(/^free_[0-9]+$/)>-1){
					free_no++;
					if((free_no-1)==i){
						tmp_html_code = free_tmp[j].innerHTML.replace(/(free_box_)([0-9])+/g,'$1'+i);
						tmp_html_code = tmp_html_code.replace(/(free_p_)([0-9])+/g,'$1'+i);
						tmp_html_code = tmp_html_code.replace(/(free_img_)([0-9])+/g,'$1'+i);
						tmp_html_code = tmp_html_code.replace(/(free_hotel_name_)([0-9])+/g,'$1'+i);
						tmp_html_code = tmp_html_code.replace(/(free_des_text_)([0-9])+/g,'$1'+i);
						tmp_html_code = tmp_html_code.replace(/(EmptyButton_)([0-9])+/g,'$1'+i);
						
						tmp_html_code = tmp_html_code.replace(/\<span\><?php echo db_to_html('第 [0-9]+ 天 ')?>\<\/span\>/i, '<span><?php echo db_to_html("第 '+(i+1)+' 天 ")?></span>');
						//处理show_hotel_tours(1139,1040, 2,1);等之类的函数,后一个参数必须处理
						tmp_html_code = tmp_html_code.replace(/show_hotel_tours\(([0-9]+),([0-9]+), ([0-9]+),([0-9]+)\)/,'show_hotel_tours($1,$2, $3,'+i+')');
						
					}
				}
			}
			free_.innerHTML = tmp_html_code;
			free_combination_day.appendChild(free_);
			//alert(free_sum_num);
		}
		tmp_box.innerHTML = '';
		
		if(document.all){	//ie 需要重新添加free_下面的 onClick事件 show_hotel_tours(1137,1040, 2,1); s_free_class(this.parentNode.parentNode.parentNode.id)
			var free_img_re = free_combination_day.getElementsByTagName('div');
			
			var hid = 0;
			var tid = 0;
			var no_day = 0;		//当前是目前行程中的第几天？
			var all_day = 0;	//当前是总天数中的第几天？
			
			for(r=Number(free_img_re.length-1); r>=0; r--){
				if(free_img_re[r].id.indexOf('free_img_')>-1){
					var img_r = free_img_re[r].getElementsByTagName('img');
					if(img_r.length==1 && img_r[0].src.indexOf('example_tu.gif')==-1){
						var now_array_id = img_r[0].parentNode.id.replace(/free_img_/,'');
						var free_des_text_input = document.getElementById('free_des_text_'+now_array_id);
						if(free_des_text_input==null){
							alert('No obj free_des_text_input_XX');
						}
						var input_box = free_des_text_input.getElementsByTagName('input');
						if(input_box.length>0){
							for(loop=0; loop<input_box.length; loop++){
								switch(input_box[loop].name){
									case 'hotel_ids[]': hid=input_box[loop].value; break;
									case 'tour_ids[]': tid=input_box[loop].value; break;
								}
							}
							
							no_day = free_des_text_input.innerHTML.replace(/.+\<span .+\>([0-9]+)<?php echo db_to_html('日')?>\<\/span\>.+/i,'$1');	
							no_day = Number(no_day);
							//alert(no_day);								
						}
						
						var span_obj = document.getElementById('free_p_'+now_array_id).getElementsByTagName('span');
						all_day = span_obj[0].innerHTML.replace(/<?php echo db_to_html("(.+)([0-9]+)(.+)")?>/,'$2');
						all_day = Number(all_day)-1;
						img_r[0].onclick = Function('show_hotel_tours('+ hid +','+ tid +', '+ no_day +','+ all_day +'); s_free_class(this.parentNode.parentNode.parentNode.id) ');						
						no_day--;
						
					}
				}
			}
			
		}
		// ie 处理end
		
		//如果行程框free_combination_day中已经被全部清空，则需要把free_combination_step2和SubMitToursDiv隐藏
		if(free_combination_day.innerHTML.length<1){
			if(document.getElementById('free_combination_step2')!=null){
				document.getElementById('free_combination_step2').style.display = 'none';
			}
			if(document.getElementById('SubMitToursDiv')!=null){
				document.getElementById('SubMitToursDiv').style.display = 'none';
			}
		}
		
		//清空后需要重新记录SESSION值
		write_coockie();		
	}
	
}

//填充行程和酒店到行程框以及记录数据到目标表单以便提交组合数据
function FillTravel(num_value,hotel_id,tour_id){
	var top_obj = document.getElementById('free_combination_day');
	var p = top_obj.getElementsByTagName('p');
	var i_max = p.length-1;
	var hc_f = document.getElementById('hotel_cearch_form');
	var target_from = document.getElementById('SubMitToursForm');

	//alert('选中'+num_value+'日行程');
	var for_loop = 1;
	for(i = i_max; i>-1; i--){
		//第一天 or 其它天
		if((p[i].id.indexOf('free_des_text_0')> -1 && p[i].innerHTML=="") || (p[i].id.indexOf('free_des_text_')>-1 && p[i].innerHTML!="")){
			//开始填充
			var tmp_num = p[i].id.replace(/free_des_text_/,'');
			var patrn=/^[0-9]{1,20}$/; 
			if (patrn.test(tmp_num)!= false){
				var int_tmp_num = Number(tmp_num);
				var free_des_text_obj_start = document.getElementById('free_des_text_'+ String(int_tmp_num));
				var free_hotel_name_start = document.getElementById('free_hotel_name_'+ String(int_tmp_num));
				var free_hotel_name_end = document.getElementById('free_hotel_name_'+ String(int_tmp_num+num_value-1));
				var free_img = document.getElementById('free_img_'+ String(int_tmp_num));
				var free_des_text_obj_end = document.getElementById('free_des_text_'+ String(int_tmp_num+num_value-1));
				var sum_num = String(int_tmp_num+num_value);
				var sum_num_min = String(int_tmp_num);

				if(int_tmp_num>0){
					free_des_text_obj_start = document.getElementById('free_des_text_'+ String(int_tmp_num+1));
					free_hotel_name_start = document.getElementById('free_hotel_name_'+ String(int_tmp_num+1));
					free_hotel_name_end = document.getElementById('free_hotel_name_'+ String(int_tmp_num+num_value));
					free_img = document.getElementById('free_img_'+ String(int_tmp_num+1));
					free_des_text_obj_end = document.getElementById('free_des_text_'+ String(int_tmp_num+num_value));
					//alert(String(int_tmp_num+num_value));
					sum_num = String(int_tmp_num+num_value+1);
					sum_num_min = String(int_tmp_num+1);
				}
				
				if(free_des_text_obj_start==null){
					//alert('no '+ ('free_des_text_'+ String(int_tmp_num)) );
				}
				//补日期 start
				var add_date_tab = true;
				if(free_des_text_obj_end==null && add_date_tab == false){
					alert('<?php echo db_to_html('剩下的日期不足！');?>');
				}
				if(free_des_text_obj_start!=null && free_des_text_obj_end==null && add_date_tab == true){
					if(window.confirm('<?php echo db_to_html('剩下的日期不足！需要增加旅行日期吗？')?>')==true){
						
						//删除最后时间段的空对象
						var rmd_div = top_obj.getElementsByTagName('div');
						for(uu=0; uu<rmd_div.length; uu++){
							if(rmd_div[uu].id.search(/^free_[0-9]+$/)>-1 && Number(rmd_div[uu].id.replace(/free_/,'')) >= sum_num_min ){
								var rm_div = rmd_div[uu];
								rm_div.parentNode.removeChild(rm_div);
								uu--;
							}
						}
						//循环添加free_X对象，直到满足为止
						var class_f = 'daybyday daybyday_ou';
						//alert(sum_num_min+'  '+sum_num);
						for(a=Number(sum_num_min); a<Number(sum_num); a++){
							//alert(a);
							var newfree =  document.createElement('div');
							if(document.all){	//ie
								newfree.className = class_f;
								newfree.id = 'free_'+a;
							}else{
								newfree.setAttribute("class",class_f);
								newfree.setAttribute("id",'free_'+a);
							}
							newfree.innerHTML = '<div class="day_box" id="free_box_'+a+'"><p class="day_title" id="free_p_'+a+'"><span><?php echo db_to_html("第 '+(Number(a)+1)+' 天")?> </span><a id="EmptyButton_'+a+'" href="javascript:void(0)" onClick="ClearHasBeenSelectedHotels(this)"><?php echo db_to_html('清空')?></a></p><div id="free_img_'+a+'"><img src="image/example_tu.gif" width="93" height="60" ></div><p class="day_des" id="free_hotel_name_'+a+'"></p><p class="day_des2" id="free_des_text_'+a+'"></p></div>';
							top_obj.appendChild(newfree);
							
						}
						
						//alert('<?php echo db_to_html('添加成功！')?>');
						//setTimeout("alert(1)",1000);
						var free_des_text_obj_end = document.getElementById('free_des_text_'+ String(sum_num-1));
						var free_des_text_obj_start = document.getElementById('free_des_text_'+ String(sum_num_min));
						var free_hotel_name_start = document.getElementById('free_hotel_name_'+ String(sum_num_min));
						var free_hotel_name_end = document.getElementById('free_hotel_name_'+ String(sum_num-1));
						var free_img = document.getElementById('free_img_'+ String(sum_num_min));
					}
				}
				//补日期 end
				
				
				if(free_des_text_obj_start!=null && free_des_text_obj_end!=null){
					if(free_des_text_obj_start!=null){
						free_des_text_obj_start.innerHTML = '<?php echo db_to_html("匹配<span style=\"color:#F58610\">' + num_value + '日</span>行程开始")?>';
						for(b=0; b<hc_f.length; b++){
							if(hc_f[b].type=="radio" && hc_f[b].checked==true){
								
								if(hc_f[b].id.indexOf('hotel_ids_')>-1){	//酒店
									var fv = hc_f[b].value;
									free_hotel_name_start.innerHTML = free_hotel_name_end.innerHTML = hc_f.elements['hotel_name_'+ fv ].value;
									//target_from.elements['hotel_id'].value += fv+'<::>';

								}
								
								if(hc_f[b].id.indexOf('tours_ids_')>-1){	//团
									/*如果客户选择的行程超日期超出了酒店的离店日期，则以行程结束日为准；如果行程结束日比酒店离店日小，则需要作进一步处理（把剩余日期的酒店当作单独的产品处理，开始日期是行程结束日期，离店日期就是原定的离店日期，这样可能出现多个同一酒店不同的入住日期而导致购物车只记录其中一个，其它的丢失），目前暂以行程结束日期为准。*/
									var ftv = hc_f[b].value;
									//target_from.elements['tours_id'].value += ftv+'<::>';
									//target_from.elements['start_date'].value += hc_f.elements['date_free_start'].value+'<::>';
									var start_date = hc_f.elements['date_free_start'].value;
									hc_f.elements['date_free_start'].value = hc_f.elements['tmp_end_date_'+fv+'_'+ftv].value;
									hc_f.elements['date_frees_end'].value = '';
									//target_from.elements['end_date'].value += hc_f.elements['date_free_start'].value+'<::>';
									var end_date = hc_f.elements['date_free_start'].value;
								}
								
							}
						}

						free_img_num = free_img.id.replace(/free_img_/,'');
						var free_p_id = free_img_num;
						free_img.innerHTML = '';
						//创建图片对象
						var src_v = hc_f.elements['hotel_image_'+ fv ].value;
						var newimg = document.createElement('img');
						newimg.setAttribute("src",src_v);
						newimg.setAttribute("width",'93');
						newimg.setAttribute("height",'60');
						newimg.setAttribute("title",free_hotel_name_start.innerHTML);
					
						if(document.all){	//ie
							newimg.onclick = Function('show_hotel_tours('+ fv +','+ ftv +', '+ for_loop +','+ free_p_id +'); s_free_class(this.parentNode.parentNode.parentNode.id) ');
							newimg.style.cursor = 'pointer';
						}else{	//火狐
							newimg.setAttribute("onclick",'show_hotel_tours('+ fv +','+ ftv +', '+ for_loop +','+ free_p_id +'); s_free_class(this.parentNode.parentNode.parentNode.id) ');
							newimg.setAttribute("style",'cursor:pointer');
							
						}	
						
						free_img.appendChild(newimg);
						for_loop++;
						
					}
					if(free_des_text_obj_end!=null){
						free_des_text_obj_end.innerHTML = '<?php echo db_to_html("匹配<span style=\"color:#F58610\">' + num_value + '日</span>行程结束")?>';
						free_des_text_obj_end.innerHTML += '<input type="hidden" name="hotel_ids[]" value="'+hotel_id+'">';
						free_des_text_obj_end.innerHTML += '<input type="hidden" name="tour_ids[]" value="'+tour_id+'">';
						free_des_text_obj_end.innerHTML += '<input type="hidden" name="start_dates[]" value="'+start_date+'">';
						free_des_text_obj_end.innerHTML += '<input type="hidden" name="end_dates[]" value="'+end_date+'">';
						
						//填充完后自动跳转到下一个空白的天数（且为选定的状态）
						//alert(free_des_text_obj_end.id);
						var free_l = document.getElementById('free_'+ String(Number(free_des_text_obj_end.id.replace(/free_des_text_/,'')) +1 ));
						if(free_l!=null){
							//free_l.className = ' daybyday s ';
							s_free_class(free_l.id);
						}

					}
					
					var cart_quantity = document.getElementById('cart_quantity');
					//target_from.elements['date_num'].value = cart_quantity.elements['tours_day'].value;
					target_from.elements['adult_num'].value = cart_quantity.elements['room-0-adult-total'].value;
					target_from.elements['child_num'].value = cart_quantity.elements['room-0-child-total'].value;
					
					//清空列表结果框 hotel_cearch_results
					var Hotel_Cearch_Results = document.getElementById('hotel_cearch_results');
					Hotel_Cearch_Results.innerHTML = '';
					
				}
				
				break;
			}
		}
	}
	
	//把free_des_text_obj_start和free_des_text_obj_end之间的框框也填充 start
	if(free_des_text_obj_start !=null && free_des_text_obj_end !=null ){
		var start_number = Number(free_des_text_obj_start.id.replace(/free_des_text_/,''));
		var end_number = Number(free_des_text_obj_end.id.replace(/free_des_text_/,''));
		for(j=(start_number+1); j<(end_number+1); j++){
			var free_img_c = document.getElementById('free_img_'+ j);
			//var free_img_o = document.getElementById('free_img_'+ start_number);
			//free_img_c.innerHTML = free_img_o.innerHTML;
			//alert(j);
			free_img_c.innerHTML = '';
			//第几天？
			var new_c_img = document.createElement('img');
			new_c_img.setAttribute("src",src_v);
			new_c_img.setAttribute("width",'93');
			new_c_img.setAttribute("height",'60');
			new_c_img.setAttribute("title",free_hotel_name_start.innerHTML);
			if(document.all){	//ie
				new_c_img.onclick = Function('show_hotel_tours('+ fv +','+ ftv +', '+ for_loop +','+ j +'); s_free_class(this.parentNode.parentNode.parentNode.id) ');
				new_c_img.style.cursor = 'pointer';
			}else{	//火狐
				new_c_img.setAttribute("onclick",'show_hotel_tours('+ fv +','+ ftv +', '+ for_loop +','+ j +'); s_free_class(this.parentNode.parentNode.parentNode.id) ');
				new_c_img.setAttribute("style",'cursor:pointer');
				
			}	
			free_img_c.appendChild(new_c_img);
			for_loop++;
		}
	}
	//把free_des_text_obj_start和free_des_text_obj_end之间的框框也填充 end
	

}

// 改变free_ 的div的className
function s_free_class(free_obj_id){
	var top_divs = document.getElementById('free_combination_day').getElementsByTagName("div");
	for(i=0; i<top_divs.length; i++){
		if(top_divs[i].id.search(/^free_\d+$/)>-1){
			//alert(top_divs[i].id);
			var tmp_num = Number(top_divs[i].id.replace(/free_/,''));
			if(tmp_num%2==0){
				top_divs[i].className = 'daybyday';
			}else{
				top_divs[i].className = 'daybyday daybyday_ou';
			}
		}
	}
	
	var free_div = document.getElementById(free_obj_id);
	
	if(free_div!=null){
		free_div.className = ' daybyday s ';
	}
}

//取得行程列表的旅游总天数
function get_free_num(){
	var free_combination_day = document.getElementById('free_combination_day');
	var free_div = free_combination_day.getElementsByTagName('div');
	var num_ = 0;
	for(i=0; i<free_div.length; i++){
		if(free_div[i].id.search(/^free_[0-9]+$/)>-1){
			num_++;
		}
	}
	return num_;
}

//显示选择的酒店和行程 No_day 是指第N天，用来取得行程中的第N天内容


function show_hotel_tours(h_id, t_id, No_day, No_day_for_all){
	if(h_id<1 || t_id<1){ return false;}
	var Hotel_Cearch_Results = document.getElementById('hotel_cearch_results');
	Hotel_Cearch_Results.innerHTML = '<img alt="Please wait..." src="image/loading.gif" />';
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_free_show_hotel_tours.php','action=process')) ?>");
	var aparams=new Array();
	var post_str = aparams.join("&");
	post_str += "&ajax=true";
	post_str += "&h_id="+h_id;
	post_str += "&t_id="+t_id;
	post_str += "&No_day="+No_day;
	post_str += "&No_day_for_all="+No_day_for_all;
	
	var date_num_value = get_free_num();
	post_str += "&date_num="+date_num_value;

	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			Hotel_Cearch_Results.innerHTML = ajax.responseText;
			//过滤id=products_description中的内容
			FilterProductsDescription(No_day);
		}
	}
	
}

function FilterProductsDescription(num){
	var TopDiv = document.getElementById('products_description');
	var div = TopDiv.getElementsByTagName('div');
	var now_pl_1 = 0;
	for(i=0; i<div.length; i++){
		if(div[i].className =="pl_1"){
			now_pl_1++;
			if(now_pl_1==num ){
				TopDiv.innerHTML = div[i].innerHTML;
				break;
			}
		}
	}
	
	//再次整理TopDiv的内容
	var div_new = TopDiv.getElementsByTagName('div');
	for(j=0; j<div_new.length; j++){
		if(div_new[j].className =="p_p_img"){	//不要图
			div_new[j].innerHTML = "";
			div_new[j].style.display = "none";
		}
		if(div_new[j].className=="p_p_1"){	//去除样式
			div_new[j].className = "";
		}
		
	}
	//替换第N天字样
	var h5 = TopDiv.getElementsByTagName('h5');
	var day_for_all = document.getElementById('day_for_all');
	var span_tag = h5[0].getElementsByTagName('span');
	for(n=0; n<span_tag.length; n++){
		if(span_tag[n].innerHTML.search(/<?php echo db_to_html('第.*天')?>/)>-1){
			span_tag[n].innerHTML = day_for_all.innerHTML +'&nbsp;';
		}
	}

}

//搜索酒店和匹配的行程提交
function cearch_hotel(){
	var from = document.getElementById('hotel_cearch_form');
	if(from.elements['date_free_start'].value.search(/^\d{4}-\d{2}-\d{2}$/) == -1){
		alert('<?php echo db_to_html('请选择正确的入住日期')?>');
		return false;
	}
	if(from.elements['date_frees_end'].value.search(/^\d{4}-\d{2}-\d{2}$/) == -1){
		alert('<?php echo db_to_html('请选择正确的离店日期')?>');
		return false;
	}
	
	var Hotel_Cearch_Results = document.getElementById('hotel_cearch_results');
	Hotel_Cearch_Results.innerHTML = '<img alt="Please wait..." src="image/loading.gif" />';
	
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_free_tours.php','action=process')) ?>");
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
	
	var cart_quantity = document.getElementById('cart_quantity');
	post_str += "&max_date="+ cart_quantity.elements['tours_day'].value;

	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			//alert(ajax.responseText);
			Hotel_Cearch_Results.innerHTML = ajax.responseText;
			var SubMitToursDiv = document.getElementById('SubMitToursDiv');
			SubMitToursDiv.style.display = '';
			//清空完全组合框内的值
			//EmptySubMitTours();
		}
	}
}

//显示或隐藏匹配行程
function show_hide_pipei_tours(id, show_or_hidden){
	if(show_or_hidden !="show" && show_or_hidden !="hidden"){
		if(document.getElementById(id).style.display=='none')
		{
			document.getElementById(id).style.display='';
		}else{
			document.getElementById(id).style.display='none'
		}
	}
	if(show_or_hidden =="show"){
		document.getElementById(id).style.display='';
	}
	if(show_or_hidden =="hidden"){
		document.getElementById(id).style.display='none';
	}
}

//点团的单选按钮时把其上级的酒店也选择
function sel_hotel(hotel_id){
	var from = document.getElementById('hotel_cearch_form');
	if(hotel_id>0){
		var hotel_ids = document.getElementById('hotel_ids_'+hotel_id);
		hotel_ids.checked = true;
		
		for(i=0; i<from.length; i++){
			if(from[i].type=="radio" && from[i].checked==true){
				
				if(from[i].id.indexOf('hotel_ids_')>-1){
					var date_num_hid = from[i].value;
				}	
				if(from[i].id.indexOf('tours_ids_')>-1){
					var date_num_tid = from[i].value;
				}
				
			}
		}
		var v_date_num = Number(from.elements['durations_'+ date_num_hid+'_'+date_num_tid].value);
		
		//把当前团id放到过滤框以便下次不再显示它
		from.elements['filtration'].value += date_num_tid+',';
		
		//alert(v_date_num);
		if(v_date_num>0 && v_date_num!='NaN'){
			var hotel_id = date_num_hid;
			var tour_id = date_num_tid;
			FillTravel(v_date_num,hotel_id,tour_id);
		}
		write_coockie();
	}	
}

function write_coockie(){
	var free_combination_day = document.getElementById('free_combination_day');
	var cart_quantity = document.getElementById('cart_quantity');
	var cookie_val = free_combination_day.innerHTML;
	var post_str = ''; 
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_free_tours.php','action=write_cookie')) ?>");
	post_str += "&free_combination_day="+cookie_val;
	post_str += "&adult_total="+cart_quantity.elements['room-0-adult-total'].value;
	post_str += "&child_total="+cart_quantity.elements['room-0-child-total'].value;
	
	var free_num_div = free_combination_day.getElementsByTagName("div");
	var tours_day = 0;
	for(i=0; i<free_num_div.length; i++){
		if(free_num_div[i].id.search(/^free_[0-9]+$/)>-1){
			tours_day++;
		}
	}
	post_str += "&tours_day="+tours_day;
	post_str += "&ajax=true";
	
	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			//alert(ajax.responseText);
		}
	}
}

//点击酒店单选按钮时把所有的团单击按钮取消
function ClearTours(i_id){
	var from = document.getElementById('hotel_cearch_form');
	for(i=0; i<from.length; i++){
		if(from.elements[i].type=="radio" && from.elements[i].id.indexOf('tours_ids_') >-1 ){
			from.elements[i].checked = false;
		}
	}
	//隐藏无关的团
	var Hotel_Cearch_Results = document.getElementById('hotel_cearch_results');
	var pipei_tours_tr = Hotel_Cearch_Results.getElementsByTagName("tr");
	for(j=0; j<pipei_tours_tr.length; j++){
		if(pipei_tours_tr[j].id.indexOf('pipei_tours_tr') >-1 ){
			pipei_tours_tr[j].style.display = 'none';
		}
	}
	show_hide_pipei_tours("pipei_tours_tr_"+i_id,'show');
}


//组合完成后，提交结果
function SubMitToursFormFun(){
	var Form = document.getElementById('SubMitToursForm');
	var have_hotel_ids = false;
	for(i=0; i<Form.length; i++){
		if(Form[i].name=="hotel_ids[]"){
			have_hotel_ids = true;
		}
	}
	if(have_hotel_ids==false || Form.elements['hotel_ids[]'].value==""){
		alert('<?php echo db_to_html("请选择酒店和行程！");?>');
	}else{
		//alert('<?php echo db_to_html("提交酒店");?>');
		Form.action = "<?php echo preg_replace($p,$r,tep_href_link_noseo('free_buy_list.php','action=process')) ?>";
		Form.submit();
	}
}

//
function EmptySubMitTours(){
	var target_from = document.getElementById('SubMitToursForm');
	target_from.elements['adult_num'].value = '';
	target_from.elements['child_num'].value = '';
}
</script>

<DIV id="free_tours">
  <form action="" method="post" enctype="multipart/form-data" name="cart_quantity" id="cart_quantity" onSubmit="free_combination_day_html(); return false;">
  <input name="numberOfRooms" type="hidden" value="1">
  <div class="free_combination_step1">
    <div class="hawaii_hotel_search_title free_combination" style=" font-weight:normal; margin-top:10px; "><img src="image/free_tours_icon.gif"><br>
      <a><?php echo db_to_html('如何开始自助游?')?></a></div>
    <div class="hawaii_search_input hawaii_search_input2">
      <p><?php echo db_to_html('天数')?></p>
      <?php
	  $day_array = array();
	  //$day_array [] = array('id'=>1,'text'=>db_to_html('1天'));
	  //$day_array [] = array('id'=>2,'text'=>db_to_html('2天'));
	  $day_array [] = array('id'=>3,'text'=>db_to_html('3天'));
	  $day_array [] = array('id'=>4,'text'=>db_to_html('4天'));
	  $day_array [] = array('id'=>5,'text'=>db_to_html('5天'));
	  $day_array [] = array('id'=>6,'text'=>db_to_html('6天'));
	  $day_array [] = array('id'=>7,'text'=>db_to_html('7天'));
	  $day_array [] = array('id'=>8,'text'=>db_to_html('8天'));
	  $day_array [] = array('id'=>9,'text'=>db_to_html('9天'));
	  $day_array [] = array('id'=>10,'text'=>db_to_html('10天'));
	  if(tep_not_null($_SESSION['hawaii_self']['tours_day'])){
	  	$tours_day = $_SESSION['hawaii_self']['tours_day'];
		if($tours_day>10 || (int)$tours_day==1 || (int)$tours_day==2){
	  		$day_array [] = array('id'=>(int)$tours_day,'text'=>(int)$tours_day.db_to_html('天'));
		}
	  }
	  echo tep_draw_pull_down_menu('tours_day',$day_array);
	  ?>
    </div>
    <div class="hawaii_search_input hawaii_search_input2">
      <p><?php echo db_to_html('成人')?></p>
      <?php
	  $adult_array = array();
	  $adult_array [] = array('id'=>1,'text'=>db_to_html('1'));
	  $adult_array [] = array('id'=>2,'text'=>db_to_html('2'));
	  $adult_array [] = array('id'=>3,'text'=>db_to_html('3'));
	  if(tep_not_null($_SESSION['hawaii_self']['adult_total'])){
	  	$var_name = "room-0-adult-total";
		$$var_name = $adult_num = $_SESSION['hawaii_self']['adult_total'];
	  }
	  echo tep_draw_pull_down_menu('room-0-adult-total',$adult_array,'',' onChange="setNumAdults(0,this.options[this.selectedIndex].value)" ').db_to_html('人');
	  ?>
    </div>
    <div class="hawaii_search_input hawaii_search_input2">
      <p><?php echo db_to_html('儿童')?></p>
      <?php
	  $child_array = array();
	  $child_array [] = array('id'=>0,'text'=>db_to_html('0'));
	  $child_array [] = array('id'=>1,'text'=>db_to_html('1'));
	  if(tep_not_null($_SESSION['hawaii_self']['child_total'])){
	  	$var_name = "room-0-child-total";
		$$var_name = $child_num = $_SESSION['hawaii_self']['child_total'];
	  }
	  echo tep_draw_pull_down_menu('room-0-child-total',$child_array).db_to_html('人');
	  ?>
    </div>
    <div class="hawaii_search_input" style="padding:28px 0px 0px 15px;"><?php echo tep_image_submit('free_combination_button.gif', db_to_html('提交'),' style="border:0px; width:89px; height:20px;" ')?></div>
       <div class="free_combination_shadow"></div>
  </div>
  </form>
   
  <form action="<?php echo tep_href_link('free_buy_list.php','action=process');?>" method="post" name="SubMitToursForm" id="SubMitToursForm" onSubmit="SubMitToursFormFun(); return false;">
		<input name="adult_num" type="<?= 'hidden'?>" id="adult_num" value="<?= (int)$adult_num;?>">
		<input name="child_num" type="<?= 'hidden'?>" id="child_num" value="<?= (int)$child_num;?>">
		<input name="ajax" type="<?= 'hidden'?>" value="true">

  <div class="free_combination_day" id="free_combination_day">
  <?php
  $SubMitToursDiv_display = $free_combination_step2_display = 'none';
  if(tep_not_null($_SESSION['hawaii_self']['free_combination_day'])){
  	echo db_to_html($_SESSION['hawaii_self']['free_combination_day']);
	$SubMitToursDiv_display = $free_combination_step2_display = '';
  }
  ?>
  </div>
  
  <div id="tmp_box" style="display:"></div>
  
  </form>

  <div class="free_combination_step2" id="free_combination_step2" style="display:<?= $free_combination_step2_display?>">
  <p class="highline-txt" style="padding:5px 0px 15px 5px"><?php echo db_to_html('注：选择入住多天酒店，系统自动填充上方相应的行程天数空白框,匹配的行程可以不选择')?></p>
  <h2 style="text-align:center; padding-bottom:15px"><?php echo db_to_html('选定酒店，行程自动匹配')?></h2>
 <form action="" method="post" enctype="multipart/form-data" name="hotel_cearch_form" id="hotel_cearch_form" onSubmit="cearch_hotel(); return false;"> 
  <div class="step2_hotel">
	   <table border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><P>&nbsp;<?php echo db_to_html('入住日期')?>&nbsp;</P></td>
			<td>
			  <script type="text/javascript"><!--
				var DateFreeStart = new ctlSpiffyCalendarBox("DateFreeStart", "hotel_cearch_form", "date_free_start","btnDateFree1","",scBTNMODE_CUSTOMBLUE);
				DateFreeStart.writeControl(); DateFreeStart.dateFormat="yyyy-MM-dd";
			//--></script>
			</td>
		    <td style="padding-left:18px"><p><?php echo db_to_html('离店时间')?>&nbsp;</p></td>
		    <td>
			  <script type="text/javascript"><!--
				var DateFreeEnd = new ctlSpiffyCalendarBox("DateFreeEnd", "hotel_cearch_form", "date_frees_end","btnDateFree2","",scBTNMODE_CUSTOMBLUE);
				DateFreeEnd.writeControl(); DateFreeEnd.dateFormat="yyyy-MM-dd";
			//--></script>
			</td>
		    <td style="padding-left:18px"><p><?php echo db_to_html('价 格')?>&nbsp;</p></td>
		    <td>
			  <?php
			  $hotel_price_array = array();
			  $hotel_price_array [] = array('id'=>'0','text'=>db_to_html('不限'));
			  $hotel_price_array [] = array('id'=>'1,100','text'=>db_to_html('$100以下'));
			  $hotel_price_array [] = array('id'=>'100,200','text'=>db_to_html('$100至$200'));
			  $hotel_price_array [] = array('id'=>'200,400','text'=>db_to_html('$200至$400'));
			  $hotel_price_array [] = array('id'=>'400,600','text'=>db_to_html('$400至$600'));
			  $hotel_price_array [] = array('id'=>'600,10000','text'=>db_to_html('$600以上'));
			  echo tep_draw_pull_down_menu('hotel_price_range',$hotel_price_array);
			  ?>
			  &nbsp;
			</td>
		    <td>&nbsp;<input name="search_categories_id" type="<?= 'hidden'?>" value="<?php echo $cPathOnly?>"><input name="filtration" type="<?= 'hidden'?>" value=""><?php echo tep_image_submit('search_hotel_hawaii.gif', db_to_html('搜索酒店'),' style="border:0px; width:54px; height:20px;" ')?>&nbsp;</td>
		  </tr>
		</table>
  </div>
	
	<div id="hotel_cearch_results">
	</div>
	</form>	
  	

  
  </div>
  
</DIV>

	<div id="SubMitToursDiv" style="display:<?= $SubMitToursDiv_display?>">
		<?php echo tep_image_submit('combination_ok.gif', db_to_html('组合完成'),' onClick="SubMitToursFormFun();" style="margin-left:260px; margin-top:20px; border:0px; width:89px; height:29px;" ')?>
	</div>

