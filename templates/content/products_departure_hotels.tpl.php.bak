<?php
//处理js和非js的快速切换条件
if($_GET['mnu']=='departure' || !tep_not_null($_GET['mnu'])){
	$mnu = $_GET['mnu'] = "departure";
	$departure_class = 'class="s"';
	$hotel_class = '';
	$left_departure_class = 'class="dazi cu left_menu_s"';
	$left_hotel_class = 'class="dazi left_menu_n"';

}elseif($_GET['mnu']=='hotel'){
	$mnu = $_GET['mnu'];
	$departure_class = '';
	$hotel_class = 'class="s"';
	$left_departure_class = 'class="dazi left_menu_n"';
	$left_hotel_class = 'class="dazi cu left_menu_s"';
}

//JS快速切换页面开关

$display_fast = false;
if(defined('USE_JS_SHOW_PRODUCT_DETAIL_CONTENT') && USE_JS_SHOW_PRODUCT_DETAIL_CONTENT=='true'){
	$display_fast = true;
}
$c_departure_display="";
$c_hotel_display="";
if($display_fast == true){
	if($mnu != 'departure'){
		$c_departure_display = ' style="display:none" ';
		$left_departure_other_display = ' style="display:none" ';
	}
	if($mnu != 'hotel'){
		$c_hotel_display = ' style="display:none" ';
		$left_hotel_other_display = ' style="display:none" ';
	}
}

//公共部分的出發地點
if($product_info['departure_city_id'] == ''){
	$product_info['departure_city_id'] = 0;
}
$city_class_departure_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_city_id'] . ") order by c.city ");
$final_departure_address='';
while($city_class_departure_at = tep_db_fetch_array($city_class_departure_at_query)) {
	$final_departure_address .= db_to_html($city_class_departure_at['city']).', '.$city_class_departure_at['zone_code'].', '.$city_class_departure_at['countries_iso_code_3'].'<br />';
}																					

?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="padding-top:5px;">
  <TBODY>
  
  <TR>
    <TD class="mainbodybackground"><!-- content main body start -->
	  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="94%" colspan=2><!-- content main body start -->
            <TABLE cellSpacing=0 cellPadding=0 width="90%" border=0 style="margin-left:40px; margin-top:10px;">
              <TBODY>
              <TR>
                <TD 
                vAlign=bottom  ></TD>
                </TR>
              <TR>
                <TD vAlign=top >
		
<?php
//左边的模块start
?>
  <div class="nei-leftside nei-leftside2" >

		  <div class="nei-leftside-top nei-leftside2" ><b></b><span></span></div>
		   <div class="leftside-box" style="width:206px;">
		   <h3><?php echo db_to_html('信息分类')?></h3>
	        <div>
	          <ul style="float:left; padding-left:10px; padding-top:10px; padding-bottom:5px; width:90%">
	            
				<?php if($display_fast != true){	//传统左边菜单?>
				<li <?=$left_departure_class?> ><a href="<?=tep_href_link('products_departure_hotels.php','products_id='.(int)$products_id.'&mnu=departure')?>"><?php echo db_to_html('出发地点交通指南')?></a></li>
           		<hr size="1" noshade color="#D4D4D4" style="margin-top:5px; margin-bottom:5px;">
				<li <?=$left_hotel_class?> ><a href="<?=tep_href_link('products_departure_hotels.php','products_id='.(int)$products_id.'&mnu=hotel')?>"><?php echo db_to_html('接送地酒店指南')?></a></li>
				
				<?php }elseif($display_fast == true){	//js快速切换左边菜单?>
				<li id="left_departure" <?=$left_departure_class?> ><a href="JavaScript:void(0);" onClick="shows_detail_content('c_departure')" id="h_departure"><?php echo db_to_html('出发地点交通指南')?></a></li>
				<hr size="1" noshade color="#D4D4D4" style="margin-top:5px; margin-bottom:5px;">
           		<li id="left_hotel" <?=$left_hotel_class?> ><a href="JavaScript:void(0);" onClick="shows_detail_content('c_hotel')" id="h_hotel"><?php echo db_to_html('接送地酒店指南')?></a></li>
				<?php }?>
				
           </ul>
            </div>
              <div class="citycity"> </div>
            </div>
		    <div class="nei-leftside-bottom nei-leftside2"><b></b><span></span></div>
		  
		  <div class="clear"></div>
	<?php //接送点左边附加内容?>
	<?php if($mnu == 'departure' || $display_fast==true){ //start of departure other?>
	<div id="left_departure_other" <?=$left_departure_other_display?>>
	</div>
	<?php }//end of departure other?>

	<?php //酒店左边附加内容?>
	<?php if($mnu == 'hotel' || $display_fast==true){ //start of hotel other?>
	<div id="left_hotel_other" <?=$left_hotel_other_display?>>
	<p style="padding-top:10px;"><a href="<?=tep_href_link('booking.php');?>" target="_blank"><img src="image/more-hotel-banner.gif"></a></p>
	</div>
	<?php }//end of hotel other?>
 </div>
<?php
//左边的模块end
?> 
 
 <div class="rightcontent" style=" width:620px; margin-left:20px;"><table width="100%" border="0" cellpadding="0" cellspacing="0">
     <tr><td width="72%" align="left" valign="top" style="line-height:20px;">
	 <h1><?php echo db_to_html($product_info['products_name'])?></h1>
      
	  <?php
	  $hotel_num = count($hotel_ids);
	  if((int)$hotel_num){
	  	echo db_to_html('本行程有 <b>'.(int)$totaldipacount.'</b> 个接送地,在出发地和结束地附近总共有 <b>'.$hotel_num.'</b> 家相关的酒店');
	  }else{
	  	echo db_to_html('本行程有 <b>'.(int)$totaldipacount.'</b> 个接送地。');
	  }
	  ?> 
	   
	   
	   </td>
       <td width="28%" align="right" valign="top">
	   
<?php echo db_to_html('查看行程出行时间');?><br>
<?php
/////////////////////////////产品日期选择框
$array_avaliabledate_store = get_avaliabledate($products_id);
$avaliabledate = '<option value="">'.db_to_html('该团所有出团时间').'</option>';
if(is_array($array_avaliabledate_store)){
	array_multisort($array_avaliabledate_store,SORT_ASC);					
	foreach($array_avaliabledate_store as $avaliabledate_key=>$avaliabledate_val){
		if (eregi('('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')', $avaliabledate_val)) {
			$dis_red_style_dep = " style='color:#F1740E;' ";
		}else{
			$dis_red_style_dep = "";
		}	
		$date_split = substr($avaliabledate_val,0,10);
		$availabledate_val1 = tep_get_date_disp($date_split);
		$availabledate_val2 = en_to_china_weeks(substr($avaliabledate_val,10));
		$avaliabledate .= '<option '.$dis_red_style_dep.' value="'.$avaliabledate_key.'">'.db_to_html($availabledate_val1).$availabledate_val2.'</option>';	
													
	}						
}
?>
	   <?php
	   /* 在日期JS日历修改好之前先隐藏 
	   <input autocomplete="off" type="text" style="width: 196px; height: 16px; border: 1px solid #999999; font-size: 12px; text-decoration: none; color:#223D6A;  margin-top:3px; padding:2px 0px 2px 5px; background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>time-selction.gif) no-repeat right center;" name="time1" id="time1" onclick="MyCalendar.SetDate(this)" value="<?php echo db_to_html('请选择您的出发日期')?>"/>
	   */
	   ?>
	   
	   <select name="availabletourdate" id="availabletourdate" class="input_search">
         <?php echo $avaliabledate?>
	   </select>
	   
	   </td>
     </tr></table>
	<DIV class="tab_prod_b" style=" width:620px; margin-top:10px;">
	<DIV class="tab_prod" >

		<UL>
		<?php
		
		if($display_fast != true){	//传统菜单
		?>
			<LI <?=$departure_class?> style="WIDTH: 146px"><a href="<?=tep_href_link('products_departure_hotels.php','products_id='.(int)$products_id.'&mnu=departure')?>"><?php echo db_to_html('出发地点交通指南')?></a></LI>
			<LI <?=$hotel_class?> style="WIDTH: 146px"><a href="<?=tep_href_link('products_departure_hotels.php','products_id='.(int)$products_id.'&mnu=hotel')?>"><?php echo db_to_html('接送地酒店指南')?></a> </LI>
		<?php
		}elseif($display_fast == true){	//js快速切换菜单
		?>
			<LI id="l_departure" <?=$departure_class?> style="WIDTH: 146px"><a href="JavaScript:void(0);" onClick="shows_detail_content('c_departure')" id="h_departure"><?php echo db_to_html('出发地点交通指南')?></a></LI>
			<LI id="l_hotel" <?=$hotel_class?> style="WIDTH: 146px"><a href="JavaScript:void(0);" onClick="shows_detail_content('c_hotel')" id="h_hotel"><?php echo db_to_html('接送地酒店指南')?></A> </LI>
		
			<script type="text/javascript">
				function shows_detail_content(show_id){
					var l_departure = document.getElementById('l_departure');
					var l_hotel = document.getElementById('l_hotel');
					
					var c_departure = document.getElementById('c_departure');
					var c_hotel = document.getElementById('c_hotel');
					
					var left_departure = document.getElementById('left_departure');
					var left_hotel = document.getElementById('left_hotel');
					
					var left_departure_other = document.getElementById('left_departure_other');
					var left_hotel_other = document.getElementById('left_hotel_other');

					l_departure.className="";
					l_hotel.className="";
					
					c_departure.style.display="none";
					c_hotel.style.display="none";
					
					left_departure_other.style.display="none";
					left_hotel_other.style.display="none";
					
					left_departure.className="dazi left_menu_n";
					left_hotel.className="dazi left_menu_n";

					var show_obj = document.getElementById(show_id);
					show_obj.style.display="";
					
					document.getElementById(show_id.replace(/c\_/g,'l_')).className="s";
					document.getElementById(show_id.replace(/c\_/g,'left_')).className="dazi cu left_menu_s";
					document.getElementById(show_id.replace(/c\_/g,'left_')+'_other').style.display="";
					
				}
			</script>
		<?php
		}
		//JS快速切换菜单 end
		?>
		
		</UL>

	</DIV>
	</DIV>
        
		<?php if($mnu == 'departure' || $display_fast==true){ //start of departure ?>
		<!--出发地交通指南start -->
		<div id="c_departure" <?=$c_departure_display?>>
		<p style="padding:5px 0px 5px 5px; float:left; width:30%; color:#676767;">
		<?php 
		echo db_to_html('出发地点为：').$final_departure_address;
		?>
		</p>
        <p style="padding:5px 0px 0px 5px; text-align:center; background:#FEEBDA; float:left; width:99%; height:20px; border:1px solid #FEDCC0; border-left:0px; border-right:0px; color:#F1740E;">
		<?php
		foreach($final_departure_time_array_result as $key => $val){
			echo $val.'&nbsp;&nbsp;&nbsp;';
		}
		
		?>
		</p>
       
	    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px; clear:both; border:1px solid #D5D5D5;">
          <tr><td width="15%" height="26" valign="middle" class="biaoge-bottom"><span class="dazi cu" style="padding-left:5px; color:#353535"><?php echo db_to_html('时间')?></span></td>
            <td width="69%" class="biaoge-bottom"><span class="dazi cu" style=" color:#353535"><?php echo db_to_html('接送地点/概述')?></span></td>
            <td width="16%" class="biaoge-bottom"><span class="dazi cu" style="color:#353535"><?php echo db_to_html('地图')?></span></td>
          </tr>
          <?php
		  $tr_row = 0;
		  $page_max_rows = 10;//每页10条
		  foreach($final_departure_array_result as $key => $val){
		  	$dis_none ='';
			if($tr_row >= $page_max_rows){
				$dis_none = 'none';
			}
		  	$valarray = explode("##",$val);
			$departure_query_address = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$products_id." and departure_id = ".(int)$valarray[1]." ";
			$departure_row_address = tep_db_query($departure_query_address);
			while($departure_result_address = tep_db_fetch_array($departure_row_address)){
		  ?>
		  
		  <tr id="departure_tr_<?= $tr_row?>" style="display:<?php echo $dis_none?>">
            <td align="center" class="biaoge-bottom"><span class="cu"><?php echo $valarray[0];?></span></td>
            <td class="biaoge-bottom" style="padding-bottom:10px;">
			
			<?php
			echo db_to_html($departure_result_address['departure_address'].', '.$departure_result_address['departure_full_address']);
			$departure_tips = $departure_result_address['departure_tips'];
			if(tep_not_null($departure_tips)){
				echo '<p style="color:#6F6F6F;">'.db_to_html($departure_tips).'</p>';	
			}
			?>
			
			</td>
            <td class="biaoge-bottom">
			<!--地图的连接-->
			<?php 
			if(tep_not_null($departure_result_address['map_path'])){
				echo '<iframe width="92" height="92" style="width:92px;height:92px;"  frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$departure_result_address['map_path'].'&output=embed"></iframe><br><a href='.$departure_result_address['map_path'].' target=_blank>'.db_to_html('查看大图').'</a>';
				//echo '<a href='.$departure_result_address['map_path'].' target=_blank><img alt="'.$departure_result_address['map_path'].'" src="image/map-example.gif" align="absMiddle" border="0" style="padding:5px 0px 5px 0px" /></a>';
			}
			?> 
			
			</td>
          </tr>
		  
          <?php
				$tr_row++;
			}
		  }
		  ?>
		</table>
		
		<div id="departure_split_page" class="tab1_2" style="margin:0px;">
	   
	   <div class="tab1_2_1">
	   <a href="javascript:void(0);" class="pageResults" onclick="split_page(0, 9, <?=(int)$page_max_rows?>, <?=(int)$departure_count_rows?>, 'c_departure', 'tr', 'departure_tr_', 'departure_split_page');" title="前一页"><u>&lt;&lt; 上一页</u></a>&nbsp;&nbsp;&nbsp;
	   <a href="javascript:void(0);" class="pageResults" onclick="split_page(0, 9, <?=(int)$page_max_rows?>, <?=(int)$departure_count_rows?>, 'c_departure', 'tr', 'departure_tr_', 'departure_split_page');" title="第1页"><u>1</u></a>&nbsp;&nbsp;
	   <b>2</b>&nbsp;&nbsp;
	   <a href="javascript:void(0);" class="pageResults" onclick="split_page(20, 29, <?=(int)$page_max_rows?>, <?=(int)$departure_count_rows?>, 'c_departure', 'tr', 'departure_tr_', 'departure_split_page');" title="第3页"><u>3</u></a>&nbsp;&nbsp;
	   <a href="javascript:void(0);" class="pageResults" onclick="split_page(20, 29, <?=(int)$page_max_rows?>, <?=(int)$departure_count_rows?>, 'c_departure', 'tr', 'departure_tr_', 'departure_split_page');" title="下一页"><u>下一页 &gt;&gt;</u></a>
		 </div>
		 <div class="tab1_2_2">第<b>1</b>页 (共<b>1</b>页)</div>
		 
		 </div>


		<script type="text/javascript">
		var departure_split_page = document.getElementById('departure_split_page');
		<?php
		$departure_count_rows = count($final_departure_array_result);
		if($departure_count_rows<=$page_max_rows){
		?>
			departure_split_page.style.display='none';
		<?php 
		}
		?>
		
		<?php
		if($departure_count_rows>$page_max_rows){	//自动到第1页
			echo 'split_page(0, '.($page_max_rows-1).', '.(int)$page_max_rows.', '.(int)$departure_count_rows.', "c_departure", "tr", "departure_tr_", "departure_split_page");';
		}
		?>
		
		</script>
		
		</div>
		<!--出发地交通指南end -->
		<?php } //end of departure ?>
		
		<?php if($mnu == 'hotel' || $display_fast==true){ //start of hotel ?>
		<!--接送地酒店指南start -->
		<div id="c_hotel" <?=$c_hotel_display?>>
		<p style="padding:10px 0px 10px 5px; float:left; width:30%; color:#676767;"><?php echo db_to_html('出发地点为').$final_departure_address;?></p>
        <p style="padding:5px 0px 5px 5px; float:right; width:40%; color:#676767;">
		<a href="javascript:void(0)" onClick="SelDeparture(0);"><?php echo db_to_html('全部接送点')?></a>
		<?php
			$departure_address_list_option='<option value="0">'.db_to_html('选择接送点').'</option>';	//只显示有酒店的接送点信息
			foreach($final_departure_array_result as $key => $val){
				$valarray = explode("##",$val);
				$departure_query_address = "select departure_id,departure_address,departure_full_address from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$products_id." and departure_id = ".(int)$valarray[1]." and products_hotels_ids!='' ";
				$departure_row_address = tep_db_query($departure_query_address);
				while($departure_result_address = tep_db_fetch_array($departure_row_address)){
					$departure_address_list_option .= '<option value="'.$departure_result_address['departure_id'].'">'.$valarray[0].' '.db_to_html($departure_result_address['departure_address'].', '.$departure_result_address['departure_full_address']).'</option>';	
				}
			}
		?>
		<select name="departure_address_list" class="input_search" id="departure_address_list" style=" width:68%" onChange="SelDeparture(this.value);">
		  <?php echo $departure_address_list_option?>
		</select>
		</p>
       
	<?php 
	//接送地酒店信息列表
	foreach($hotel_ids as $key => $hotel_val){
		//取得酒店详细信息
		$hotel_sql = tep_db_query('SELECT * FROM `products` p, `products_description` pd, `products_hotels` ph WHERE p.products_id="'.(int)$hotel_val.'" AND language_id=1 AND pd.products_id=p.products_id AND ph.products_id = p.products_id ');
		$hotel_info = tep_db_fetch_array($hotel_sql);
		
		//取得税种
		$tax_rate_val_get = tep_get_tax_rate($hotel_info['products_tax_class_id']);
		
		if((int)$hotel_info['products_id']){
			//取得酒店的对应接送点
			$dep_sql = tep_db_query('select departure_id,departure_time,departure_address,departure_full_address,departure_tips,map_path  from '.TABLE_PRODUCTS_DEPARTURE.' where products_id = '.(int)$products_id.' and FIND_IN_SET("'.(int)$hotel_info['products_id'].'", products_hotels_ids ) ORDER BY `departure_time` ASC ');
			$departure_ids='';
			$final_departure_array = array();
			$n=0;
			while($dep_rows=tep_db_fetch_array($dep_sql)){
				$departure_ids.=$dep_rows['departure_id'].',';
				$final_departure_array[$n]['time'] = $dep_rows['departure_time'];
				$final_departure_array[$n]['address'] = $dep_rows['departure_address'] .', '. $dep_rows['departure_full_address'];
				$final_departure_array[$n]['departure_tips'] = $dep_rows['departure_tips'];
				$final_departure_array[$n]['map_path'] = $dep_rows['map_path'];
				
				$n++;
			}
			$departure_ids = substr($departure_ids,0,(strlen($departure_ids)-1));
	?>
		   <div id="hotel_list_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>" style="padding-top:10px;">
			<form action="<?php echo tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params(array('action','maxnumber')) . 'action=add_product').'&mnu=hotel'?>" method="post" name="hotel_form_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>" id="hotel_form_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>" onsubmit="return false;">
			
			<input name="products_id" type="hidden" value="<?php echo (int)$hotel_info['products_id']?>" />
			<input title="房间总数" name="numberOfRooms" type="hidden" value="1" />
			<div id="room_field_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>" style="display:none">
			<input title="第N个房间成人数" name="room-0-adult-total" type="hidden" value="1" />
			<input title="第N个房间儿童数" name="room-0-child-total" type="hidden" value="0" />
			</div>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:5px; margin-bottom:5px; border-top:1px solid #108BCD; clear:both;">
					  
					  <tr>
						<td width="45%" colspan="3">
						
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="28" align="left" bgcolor="#d9f0fd" style="padding-left:6px;"><span class="dazi cu"><?php echo db_to_html($hotel_info['products_name']);?></span></td>
                                <td bgcolor="#d9f0fd">&nbsp;</td>
                                <td align="right" bgcolor="#d9f0fd" style="padding-right:6px;">
								<span class="cu">
								<?php
								echo db_to_html('酒店星级：'.$hotel_info['hotel_star'].'星');
								?>
								</span>
								</td>
                              </tr>
                            </table>
						
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:5px;">
						  
						  <tr>
							<td valign="top" style="width:159px;" >
							<!--<div class="middle_img2"><a style="margin-left:0px;"><img src="image/shili.jpg" width="154" height="112"></a></div><div style="background:#B6DBF3; padding:2px 0px 2px 2px; float:left; width:160px;"><a href="#" class="huise_di2">+点击查看酒店图片</a></div>-->
							
   <div id="m_zhanshi_pic_<?php echo $hotel_info['products_id']?>" class="middle_img2">
   <?php
	  //check for extra images
	  $check_ext_img_exist = tep_db_query("select prod_extra_image_id from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$hotel_info['products_id']."'");
	  if(tep_db_num_rows($check_ext_img_exist)>0){
	  ?>
	 <div id="show_<?php echo $hotel_info['products_id']?>"> 
		 <?php
		   if($hotel_info['products_image_med']!=''){
		   ?>
		 <a style="margin-left:0px;" href="images/<?php echo $hotel_info['products_image_med']; ?>" title="<?php echo addslashes(db_to_html($hotel_info['products_name']))?>" target="_blank" rel="lightbox[roadtrip_<?php echo $hotel_info['products_id']?>]"><img  src="images/<?php echo $hotel_info['products_image_med']; ?>" name="showpic_<?php echo $hotel_info['products_id']?>" width="154" id="showpic_<?php echo $hotel_info['products_id']?>" /></a>
		 
		 <?php
		   }
		   else
		   {
			$extra_images_query = tep_db_query("select product_image_name from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$hotel_info['products_id']."' order by image_sort_order limit 1");
			$row_first_image = tep_db_fetch_array($extra_images_query);
			$url_product_image_name = 'images/'.$row_first_image['product_image_name'];
			if(preg_match('/^http:/',$row_first_image['product_image_name'])){
				$url_product_image_name = $row_first_image['product_image_name'];
			}
			?>
		 
		 <a href="<?php echo $url_product_image_name; ?>" title="<?php echo addslashes(db_to_html($hotel_info['products_name']))?>" target="_blank" rel="lightbox[roadtrip_<?php echo $hotel_info['products_id']?>]"><img width="154" src="<?php echo $url_product_image_name; ?>" id="showpic_<?php echo $hotel_info['products_id']?>" /></a>
		 
		 
		 
		 <?php
		   }
		   ?>
	 </div> 
<?php
	 }else{
?>
			<div id="show_<?php echo $hotel_info['products_id']?>">
		 <?php
					
			if ($hotel_info['products_image_med']!='') {
				$new_image = $hotel_info['products_image_med'];
			} else {
				$new_image = $hotel_info['products_image'];
			}
			
			if(!tep_not_null($new_image)){
				$new_image = 'noimage_large.jpg';
			}
		?>
		  
		<a style="margin-left:0px;" href="images/<?php echo $new_image; ?>" title="<?php echo addslashes(db_to_html($hotel_info['products_name']))?>" target="_blank" rel="lightbox"><img width="154" src="images/<?php echo $new_image; ?>" id="showpic_<?php echo $hotel_info['products_id']?>" /></a>			</div>
							
<?php
}
?>
						<!--看大图按钮-->
						<div style="background:#B6DBF3; padding:2px 0px 2px 2px; float:left; width:160px;">
						<?php
						if(tep_db_num_rows($check_ext_img_exist)>0){
							
							$extra_images_sql = tep_db_query("select product_image_name from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$hotel_info['products_id']."' order by image_sort_order ");
							
							if($hotel_info['products_image_med']!=''){
								//echo tep_template_image_button('image_enlarge_array.gif', TEXT_CLICK_TO_ENLARGE, 'style="cursor: pointer; width:70px; height:12px;" ');
								echo '<a target="_blank" href="images/'.$hotel_info['products_image_med'].'" rel="lightbox[roadtrip_'.$hotel_info['products_id'].']" title="'.addslashes(db_to_html($hotel_info['products_name'])).'"  class="huise_di2" style="border:0px">'.db_to_html("+ 点击查看酒店组图").'</a>';
								while($extra_images_rows = tep_db_fetch_array($extra_images_sql)){
									$url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
									if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
										$url_product_image_name = $extra_images_rows['product_image_name'];
									}
									echo '<a target="_blank" href="'.$url_product_image_name.'" rel="lightbox[roadtrip_'.$hotel_info['products_id'].']" title="'.addslashes(db_to_html($hotel_info['products_name'])).'"  style="display:none;"></a>';
								}
								
								
								
							}else{
								$tmp_num = 0;
								while($extra_images_rows = tep_db_fetch_array($extra_images_sql)){
									$tmp_num++;
									$tmp_str = '';
									if($tmp_num!=1){
										$tmp_str = ' style="display:none;" ';
									}
									$url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
									if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
										$url_product_image_name = $extra_images_rows['product_image_name'];
									}
									echo '<a target="_blank" href="'.$url_product_image_name.'" rel="lightbox[roadtrip_'. $hotel_info['products_id'].']" title="'.addslashes(db_to_html($hotel_info['products_name'])).'"  class="huise_di2" '.$tmp_str.'>'.db_to_html("+ 点击查看酒店组图").'</a>';
								}	
							}
						}else{
							if($new_image != 'noimage_large.jpg'){
								//echo tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE);
								echo '<a target="_blank" rel="lightbox" title="'.addslashes(db_to_html($hotel_info['products_name'])).'" href="images/'.$hotel_info['products_image_med'].'"  class="huise_di2" style="border:0px">'.db_to_html("+ 点击查看酒店图片").'</a>';
							}
						}
						?>
						
						</div>
						<!--看大图按钮 end-->
  </div>

							</td>
							<td width="2%">&nbsp;</td>
							<td width="73%" height="15" colspan="2" valign="top">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td><span class="cu"><?php echo db_to_html('简介')?></span></td>
								  </tr>
								  <tr>
								    <td><p style="line-height:18px;"><?php echo db_to_html($hotel_info['products_small_description']);?></p>									</td>
								    </tr>
								  <tr>
								    <td><p style="line-height:18px;"><b><?php echo db_to_html('地址：')?></b><?php echo db_to_html($hotel_info['hotel_address']);?></p></td>
								    </tr>
								</table>
								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:9px;">
								  <tr>
									<td colspan="2"><span class="cu"><?php echo db_to_html('适应的接送点')?></span></td>
								  </tr>
								  <?php
								  //取得对应的接送点信息
								  
								  for($i=0; $i<count($final_departure_array); $i++){
								  ?>
								  <tr>
								    <td width="41" valign="top"><?php echo $final_departure_array[$i]['time'];?></td>
								    <td valign="top" style="line-height:18px; float:left;">
									<p><?php echo db_to_html($final_departure_array[$i]['address']);?>
									
									<?php
									//交通提示start
									if(tep_not_null($final_departure_array[$i]['departure_tips'])){
									?>
									<a href="JavaScript:void(0);" class="tipslayer jifen_num"><span style="top:15px;"><?php echo db_to_html($final_departure_array[$i]['departure_tips'])?></span>[?]</a>
									<?php
									}
									//交通提示end
									?>
									<?php 
									//地图信息start
									if(tep_not_null($final_departure_array[$i]['map_path'])){
									?>
									<a target="_blank" title="<?php echo db_to_html('地图信息')?>" href="<?php echo $final_departure_array[$i]['map_path'];?>"><img align="absMiddle" src="images/maps.jpg"/></a>
									<?php
									}
									//地图信息end
									?>
									</p>
									
									</td>
								  </tr>
								  <?php
								  }	
								  ?>
								  
								  
								  
								</table>								</td>
						  </tr>
						  <tr>
						    <td valign="top" >&nbsp;</td>
						    <td>&nbsp;</td>
						    <td height="15" colspan="2" valign="top">&nbsp;</td>
						    </tr>
						</table></td>
						</tr>
					</table>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="49%"><span class="cu" style="color:#676767"><?php echo db_to_html('预订接送点附近酒店')?></span></td>
		<td width="51%" nowrap="nowrap"><div style="background: url(image/jiesong-hotel-bg.gif); background-repeat:repeat-x; height:24px; color:#fff; font-weight:bold; padding:4px 0px 0px 15px;">
<?php
$array_avaliabledate_store = get_avaliabledate($hotel_info['products_id']);
$avaliabledate = '<option value="">'.db_to_html('选择入住日期').'</option>';
if(is_array($array_avaliabledate_store)){
	array_multisort($array_avaliabledate_store,SORT_ASC);					
	foreach($array_avaliabledate_store as $avaliabledate_key=>$avaliabledate_val){
		if (eregi('('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')', $avaliabledate_val)) {
			$dis_red_style_dep = " style='color:#F1740E;' ";
		}else{
			$dis_red_style_dep = "";
		}	
		$date_split = substr($avaliabledate_val,0,10);
		$availabledate_val1 = tep_get_date_disp($date_split);
		$availabledate_val2 = en_to_china_weeks(substr($avaliabledate_val,10));
		$avaliabledate .= '<option '.$dis_red_style_dep.' value="'.$avaliabledate_key.'">'.db_to_html($availabledate_val1).$availabledate_val2.'</option>';	
													
	}						
}
echo db_to_html('入住日期');
?>
	<input autocomplete="off" type="text" style="display:<?php echo 'none'?>; width: 196px; height: 16px; border: 1px solid #999999; font-size: 12px; text-decoration: none; color:#223D6A;  margin-top:3px; padding:2px 0px 2px 5px; background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>time-selction.gif) no-repeat right center;" name="time1" onclick="MyCalendar.SetDate(this)" value="<?php echo db_to_html('请选择入住日期')?>" onBlur="document.getElementById(&quot;availabletourdate_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>&quot;).focus();" />
	<select class="validate-selection-blank" name="availabletourdate" id="availabletourdate_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>" title="<?php echo TEXT_SELECT_VALID_DEPARTURE_DATE?>" >
	<?php echo $avaliabledate?>
	</select>


<?php
					//show product options start
					
					//汇率附加费 等产品选项(这里主要是入住的天数)
						$dis_buy_steps_2_products_options_name =''; 
					
							$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$hotel_info['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
							$products_attributes = tep_db_fetch_array($products_attributes_query);
							if ($products_attributes['total'] > 0) {
						
							  $dis_buy_steps_2_products_options_name ='';
							  
							  $hotel_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$hotel_info['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
							  while ($hotel_options_name = tep_db_fetch_array($hotel_options_name_query)) {
								$hotel_options_array = array();
								$hotel_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price, pa.kids_values_price from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$hotel_info['products_id'] . "' and pa.options_id = '" . (int)$hotel_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
								while ($hotel_options = tep_db_fetch_array($hotel_options_query)) {
								  $hotel_options_array[] = array('id' => $hotel_options['products_options_values_id'], 'text' => db_to_html($hotel_options['products_options_values_name']));
								  if ($hotel_options['options_values_price'] != '0') {
								  	//amit modified to make sure price on usd	
										if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){									
										 $hotel_options['options_values_price'] = tep_get_tour_price_in_usd($hotel_options['options_values_price'],$tour_agency_opr_currency);
										}
									//amit modified to make sure price on usd									
									$hotel_options_array[sizeof($hotel_options_array)-1]['text'] .= ' (' . $hotel_options['price_prefix'] . $currencies->display_price($hotel_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
								  }
								  //amit added to show Holiday Surcharge -- for special price start
								  if($hotel_options['single_values_price'] > 0 || $hotel_options['double_values_price'] > 0 || $hotel_options['triple_values_price'] > 0 || $hotel_options['quadruple_values_price'] > 0 || $hotel_options['kids_values_price'] > 0){
								  $hotel_options_array[sizeof($hotel_options_array)-1]['text'] .= ' ('.TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE.') ';
								  $display_pricing_for_special_price_attribute = 'true';
								  }
								  //amit added to show Holiday Surcharge -- for special price end
								}
						
								if (isset($cart->contents[$hotel_info['products_id']]['attributes'][$hotel_options_name['products_options_id']])) {
								  $selected_attribute = db_to_html($cart->contents[$hotel_info['products_id']]['attributes'][$hotel_options_name['products_options_id']]);
								} else {
								  $selected_attribute = false;
								}
								
								/*$dis_buy_steps_2_products_options_name .= '<tr>
									 <td width="1">&nbsp;</td>';
								if($dis_buy_steps_2!=true){
									$dis_buy_steps_2_products_options_name .= '<td class="buy_steps_2" >&nbsp;</td>';
									$dis_buy_steps_2=true;
								}else{
									$dis_buy_steps_2_products_options_name .= '<td>&nbsp;</td>';
								}
								
								$dis_buy_steps_2_products_options_name .= '<td class="buy_options_title"><b>'.db_to_html($hotel_options_name['products_options_name']).'</b></td>
									</tr>
								  
									<tr>
									 <td width="1">&nbsp;</td>
									  <td>&nbsp;</td>
									  <td class="main">'.tep_draw_pull_down_menu('id[' . $hotel_options_name['products_options_id'] . ']', $hotel_options_array, $selected_attribute, 'class="sel3" style="width:240;"').'</td>
									</tr>';
									*/
								$repalce_string = false;	
								if($hotel_options_name['products_options_name']=='住宿' && $repalce_string==true){
									$dis_buy_steps_2_products_options_name .= tep_draw_pull_down_menu('id[' . $hotel_options_name['products_options_id'] . ']', $hotel_options_array, $selected_attribute, '');
								}else{
									$dis_buy_steps_2_products_options_name .= db_to_html($hotel_options_name['products_options_name']).tep_draw_pull_down_menu('id[' . $hotel_options_name['products_options_id'] . ']', $hotel_options_array, $selected_attribute, '');
								}

							  }
						
							}
					echo $dis_buy_steps_2_products_options_name;
					//汇率附加费 等产品选项 end						
					//show product options end 

?>		

		&nbsp;&nbsp;
		</div>
		</td>
	  </tr>
	  <tr>
		<td colspan="2">
		
		<?php
		//酒店房型以及价格信息 start
		if($hotel_info['display_room_option'] == '1'){
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="#ECECEC">
			<td width="51%" height="28" valign="middle"><span class="cu" style="color:#676767; padding-left:10px"><?php echo db_to_html('房型')?></span></td>
			<td width="14%"><span class="cu" style="color:#676767"><?php echo db_to_html('价格/间')?></span></td>
			<td width="12%"><span class="cu" style="color:#676767"><?php echo db_to_html('数量')?></span></td>
			<td width="13%" align="center">&nbsp;</td>
		  </tr>
		  
		  	<?php
			  $room_info_array = array();
			  
			  if($hotel_info['products_single'] > 0 ) {
				$products_single_sum = $hotel_info['products_single']*1;
				$room_info_array[count($room_info_array)] = array('room_type'=>'单人间', 'room_price'=>str_replace('/','<br />',$currencies->display_price($products_single_sum,$tax_rate_val_get)));
			  }
			  if($hotel_info['products_double'] > 0 ) {
				$products_double_sum = $hotel_info['products_double']*2;
				$room_info_array[count($room_info_array)] = array('room_type'=>'双人间', 'room_price'=>str_replace('/','<br />',$currencies->display_price($products_double_sum,$tax_rate_val_get)));
			  }
			  if($hotel_info['products_triple'] > 0 ) {
				$products_triple_sum = $hotel_info['products_triple']*3;
				$room_info_array[count($room_info_array)] = array('room_type'=>'三人间', 'room_price'=>str_replace('/','<br />',$currencies->display_price($products_triple_sum,$tax_rate_val_get)));
			  }
			  if($hotel_info['products_quadr'] > 0 ) {
				$products_quadr_sum = $hotel_info['products_quadr']*4;
				$room_info_array[count($room_info_array)] = array('room_type'=>'四人间', 'room_price'=>str_replace('/','<br />',$currencies->display_price($products_quadr_sum,$tax_rate_val_get)));
			  }
			  /* 儿童
			  if($hotel_info['products_kids'] > 0 ) {
				$room_info_array[count($room_info_array)] = array('room_type'=>'儿童', 'room_price'=>str_replace('/','<br />',$currencies->display_price($hotel_info['products_kids'],$tax_rate_val_get)));
			  }*/
			  
			  foreach($room_info_array as $key_num => $val_r){
		  	?>
		  <tr class="moduleRow" onmouseover="rowOverEffect(this,'room_id_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids.'_'.$key_num?>') " onmouseout="rowOutEffect(this)">
			<td height="28" valign="middle" style="color:#676767; padding-left:10px">
			<input id="room_id_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids.'_'.$key_num?>" name="room_id_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids?>" type="radio" value="<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids.'_'.$key_num?>" style="display:<?= 'none'?>" />
			<?php echo db_to_html($val_r['room_type'])?>
			</td>
			<td style="color:#676767"><?php echo db_to_html($val_r['room_price'])?></td>
			<td style="color:#676767"> 
			  
			  <select name="numberOfRooms_<?php echo (int)$hotel_info['products_id'].'##'.$departure_ids.'_'.$key_num?>" style="width:70px;">
			    <option value="1"> 1 <?php echo db_to_html('间')?></option>
			    <option value="2"> 2 <?php echo db_to_html('间')?></option>
			    <option value="3"> 3 <?php echo db_to_html('间')?></option>
			    <option value="4"> 4 <?php echo db_to_html('间')?></option>
			    <option value="5"> 5 <?php echo db_to_html('间')?></option>
			    <option value="6"> 6 <?php echo db_to_html('间')?></option>
			  </select>			  			  </td>
			<td align="center"><?php echo tep_template_image_button('jiesong-hotel-yuding.gif', db_to_html('定购'), ' name="book_button_'.(int)$hotel_info['products_id'].'##'.$departure_ids.'_'.$key_num.'" id="book_button_'.(int)$hotel_info['products_id'].'##'.$departure_ids.'_'.$key_num.'"  onClick="Booking_Hotel(document.getElementById(&quot;hotel_form_'.(int)$hotel_info['products_id'].'##'.$departure_ids.'&quot;))" '); ?></td>
		  </tr>
		  <?php
		  }
		  ?>
		  
		</table>
		<?php
		}
		//酒店房型以及价格信息 end
		?>
		
		</td>
	  </tr>
	</table>   
		   </form>
		   </div>
	<?php
		}
	}
	?>

           
		</div>
		<!--接送地酒店指南start -->
		<?php } //end of hotel ?>
		
   </div></TD>
              </TR>
              <TR>
                <TD vAlign=top >&nbsp;</TD>
              </TR>
              </TBODY></TABLE>
            <!-- content main body end --></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
