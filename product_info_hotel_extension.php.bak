<?php ob_start();?>
            <div class="hotelExtendActive" id="HotelExtendActive">
                <a href="javascript:void(0);" class="hotelExtendTitle" >加订酒店延住</a>
                <!--<p class="hotelExtendTip">为提前到达和延后离开的旅客提供酒店服务。</p>-->
            </div>          
            <div class="hotelExtendCon" id="HotelExtendCon" style="display:none;" >
            
                <div id="HotelExtendCon_1" style="display:none" >
                <p><span class="hotelExtendTitle">参团前加订酒店</span><a href="javascript:;" id="HotelExtendTitle_1" class="blue"  onclick="clearHotelExtension('early');">不预订</a></p>
                <ul>
                    <li>
                        <!--<div class="num"></div>-->
                        <div class="con" style="width:100%">
                            <!--<div class="conTitle" id="ConTitle_5"><h2>请选择入住日期：</h2></div>--> 
                            <!-- hotel sel pop begin-->
                            <div class="conTitle" id="ConTitle_HotelExtendPop_1" style="height:0;padding:0;">
                                <h2 style="display:none">请选择酒店：<a href="javascript:;" id="ConTitleA_HotelExtendPop_1" onclick="updateDepartureAddress();">可选择</a></h2>
								
								<div class="close close2 hotelExtendPopClose hotelExtendPopClose2" id="Close_HotelExtendPop_1"><a href="javascript:void(0);" class="add_hotel_pup"></a></div>
                                <div class="choosePop choosePop2 hotelExtendPop" id="HotelExtendPop_1"  >
                                    <div class="hotelExtendPopCon">
                                        <h4 id="heDepartureAddressDiv">参团前酒店：<label id="heDepartureAddress"></label></h4>
                                        <div class="comUl hotelList">
                                            <div class="header"><div class="col1">推荐酒店</div><!-- <div class="col2">距离/交通方式/大约时间等情况介绍</div>--></div>
                                            <ul>
                                            <?php //查询提前延住酒店
                                            if($product_info['hotels_for_early_arrival']!=""){
                                            $query_hotel = tep_db_query(
                                            "SELECT DISTINCT h.*, p.products_id,pd.products_name, pd.products_hotel_nearby_attractions,p.products_urlname, p.products_price
                                             FROM ".TABLE_PRODUCTS." p  LEFT JOIN  hotel h ON  p.products_id = h.products_id," . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                             WHERE p.products_status = '1'  AND pd.products_id = p.products_id 	AND  p.products_id IN (".$product_info['hotels_for_early_arrival'].") 	AND pd.language_id = '" . (int) $languages_id . "' AND p.is_hotel ='1' Group By p.products_id "
                                            );
                                            $hotel_detail_html = '';
                                            while($hotel = tep_db_fetch_array($query_hotel)){
                                                if(!is_numeric($hotel['hotel_id'])){
                                                    $hotel_detail_html.='<div id="heHotelDesc_'.$hotel['products_id'].'"></div>';
                                                }else{ 
                                                    $hotel_detail_html.= '<div id="heHotelDesc_'.$hotel['products_id'].'" style="display:none"><h5><b>'.$hotel['products_name'].'</b><a  target="_blank" href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $hotel['products_id']).'">查看酒店详情&gt;&gt;</a></h5>'
                                                    .'<ul class="hotelDetail"><li><label>酒店星级：</label>'.$hotel['hotel_stars'].'星</li>
                                                    <li><label>地址：</label>'.$hotel['hotel_address'].'</li>
                                                    <li><label>电话：</label>'.$hotel['hotel_phone'].'</li></ul>'
                                                    .$hotel['products_hotel_nearby_attractions'].'</div>';
                                                }
                                            ?>
                                                <li onclick="jQuery('.hotelInfo div').hide();jQuery('#TextBox_HotelExtendPop_1').html('<?php echo tep_output_string($hotel['products_name'])?>');jQuery('#heHotelDesc_<?php echo $hotel['products_id']?>').fadeIn('fast');jQuery('#ConTitleA_HotelExtendPop_1').html('可修改');"><div class="col1"><input type="radio" name="early_arrival_hotels"   value="<?php echo $hotel['products_id']?>"/><?php echo $hotel['products_name']?></div></li>
                                            <?php 
                                                }
                                            }?>							
                                            </ul>
                                        </div>
                                        <div class="hotelInfo"><?php echo $hotel_detail_html;?></div>
                                    </div>
                                    <div class="btnCenter"><a class="btn btnOrange" href="javascript:;"  onclick="hotelExtensionBudget();"><button type="button">确 定</button></a><a class="btn btnGrey" href="javascript:void(0);"><button type="button">取 消</button></a></div>
                                </div>
								</div>
								<!-- hotel sel pop end-->
								<div style="float:left;line-height:20px;color:#777777;">Hotel:</div><div class="place" id="TextBox_HotelExtendPop_1" onclick="SetPopBox('HotelExtendPop_1');" style="float:left;width:227px;margin-bottom:5px;">&nbsp;</div>                           
                            <div style=" float:left; line-height:22px;">
                             <div class="checkDatePanel">
							<label>Check in:</label>
							<div class="checkDateCal">
							<script type="text/javascript">
								var checkInDate = new ctlSpiffyCalendarBox("checkInDate", "cart_quantity", "early_hotel_checkin_date","btnDate","",scBTNMODE_CUSTOMBLUE);
								checkInDate.useDateRange = true;
								checkInDate.focusClick=true;		
								checkInDate.readonly=true;						
								checkInDate.JStoRunOnSelect = 'hotelExtensionBudget()';
								checkInDate.minDate =new Date('<?php echo date('Y-m-d',time()+(6*86400)); //酒店延住的最小日期设置?>');
								checkInDate.dateFormat="MM/dd/yyyy";
								checkInDate.writeControl();								 
							</script></div>							
							<label>Check out:</label>
							<span id="EarlyHotelCheckoutDateDiv"></span>
							<input type="hidden" id="EarlyHotelCheckoutDate" name="early_hotel_checkout_date" value="" />
							<?php echo tep_draw_hidden_field('h_e_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', 1); ?></div>                            
                            </div>
                                                    
                        </div>
                    </li>
                </ul>
                </div>
                <p><a href="javascript:;" class="blue" onclick="jQuery(this).hide(); jQuery('#HotelExtendTitle_1').show(); jQuery('#HotelExtendCon_1').show();" id="HotelExtendTitle_12" style="display:none;">参团前加订酒店</a></p>
                
                <!--延后延住酒店选择开始-->
                <div id="HotelExtendCon_2"  style="display:none" >
                <p><span class="hotelExtendTitle">参团后加订酒店</span><a href="javascript:;" id="HotelExtendTitle_2" class="blue"  onclick="clearHotelExtension('late')">不预订</a></p>
                <ul>
                    <li class="bot">
                        <!--<div class="num"></div>-->
                        <div class="con" style="width:100%">
						<!--<div class="conTitle" style="display:none"><h2>请选择离店日期：</h2></div>-->                            
                        <div   style=" float:left; line-height:22px;">
                        <!-- hotel sel pop begin-->
							<div class="conTitle" id="ConTitle_HotelExtendPop_2" style="height:0;padding:0;"><h2 style="display:none">请选择酒店：<a href="javascript:;" id="ConTitleA_HotelExtendPop_2">选择</a></h2>
							
							<div class="close close2 hotelExtendPopClose hotelExtendPopClose2" id="Close_HotelExtendPop_2"><a href="javascript:void(0);" class="add_hotel_pup"></a></div>
							<div class="choosePop choosePop2 hotelExtendPop" id="HotelExtendPop_2"  >
									<div class="hotelExtendPopCon">
										<div class="comUl hotelList">
											<div class="header"><div class="col1">推荐酒店</div><!--  <div class="col2">距离/交通方式/大约时间等情况介绍</div>--></div>
											<ul>
											<?php //查询延后延住酒店
											if($product_info['hotels_for_late_departure']!=""){
												$query_hotel = tep_db_query(
												"SELECT h.*, p.products_id,pd.products_name, pd.products_hotel_nearby_attractions,p.products_urlname, p.products_price
												 FROM ".TABLE_PRODUCTS." p  LEFT JOIN  hotel h ON  p.products_id = h.products_id," . TABLE_PRODUCTS_DESCRIPTION . " pd 
												 WHERE p.products_status = '1' AND  pd.products_id = p.products_id 	AND  p.products_id IN (".$product_info['hotels_for_late_departure'].") 	AND pd.language_id = '" . (int) $languages_id . "' AND p.is_hotel ='1' Group By p.products_id "
												);
												$hotel_detail_html = '';
												while($hotel = tep_db_fetch_array($query_hotel)){
													if(empty($hotel['hotel_id'])){
														$hotel_detail_html.='<div id="heHotelDesc2_'.$hotel['products_id'].'"></div>';
													}else{ 
														$hotel_detail_html.= '<div id="heHotelDesc2_'.$hotel['products_id'].'" style="display:none"><h5><b>'.$hotel['products_name'].'</b><a  target="_blank" href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $hotel['products_id']).'">查看酒店详情&gt;&gt;</a></h5>'
														.'<ul class="hotelDetail"><li><label>酒店星级：</label>'.$hotel['hotel_stars'].'星</li>
														<li><label>地址：</label>'.$hotel['hotel_address'].'</li>
														<li><label>电话：</label>'.$hotel['hotel_phone'].'</li></ul>'
														.$hotel['products_hotel_nearby_attractions'].'</div>';
													}
												?>
												<li onclick="jQuery('.hotelInfo div').hide();jQuery('#TextBox_HotelExtendPop_2').html('<?php echo tep_output_string($hotel['products_name'])?>');jQuery('#heHotelDesc2_<?php echo $hotel['products_id']?>').fadeIn('fast');jQuery('#ConTitleA_HotelExtendPop_2').html('可修改');"><div class="col1"><input type="radio" name="staying_late_hotels"   value="<?php echo $hotel['products_id']?>"/><?php echo $hotel['products_name']?></div><!-- <div class="col2"></div> --></li>
												<?php }
												}?>
											</ul>
										</div>										
										<div class="hotelInfo">	<?php echo $hotel_detail_html;?>	</div>	
									</div>									
									<div class="btnCenter"><a class="btn btnOrange" href="javascript:;" onclick="hotelExtensionBudget();"><button type="button">确 定</button></a><a class="btn btnGrey" href="javascript:void(0);"><button type="button">取 消</button></a></div>
								</div>
							</div>
							
							<div style="float:left;line-height:20px;color:#777777;">Hotel:</div><div class="place" id="TextBox_HotelExtendPop_2" onclick="SetPopBox('HotelExtendPop_2');" style="float:left;width:227px;margin-bottom:5px;">&nbsp;</div> 
                        <div class="checkDatePanel">
							<label>Check in:</label>
							<div><span id="LateHotelCheckinDateDiv">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> 
							<input id="LateHotelCheckinDate" type="hidden" name="late_hotel_checkin_date" value="" />
							<?php echo tep_draw_hidden_field('h_l_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', 2); ?>
							</div>
							<label>Check out:</label>
							<div class="checkDateCal">
							<script type="text/javascript">
								var checkOutDate = new ctlSpiffyCalendarBox("checkOutDate", "cart_quantity", "late_hotel_checkout_date","btnDate2","",scBTNMODE_CUSTOMBLUE);
								 checkOutDate.dateFormat="MM/dd/yyyy";	
								 checkOutDate.readonly=true;										 				 
								checkOutDate.useDateRange = true;
								checkOutDate.focusClick=true;					
								checkOutDate.JStoRunOnSelect = 'hotelExtensionBudget()';
								checkOutDate.writeControl();			
								
							</script></div>
							</div>
                                                       
						</div>
                    </li>
                </ul>
                </div>
                <p><a href="javascript:;" class="blue" onclick="jQuery(this).hide(); jQuery('#HotelExtendTitle_2').show(); jQuery('#HotelExtendCon_2').show();" id="HotelExtendTitle_22" style="display:none;">参团后加订酒店</a></p>
            </div>

<script type="text/javascript">
// 酒店延住 start
function updateDepartureAddress(){
	var address = jQuery.trim(jQuery('#AllAddressBoxt').html());
	if(address == ''){
		address = jQuery.trim(jQuery('#TextBox_placePop').html());
	}
	if(address == ''){
		jQuery("#heDepartureAddressDiv").hide();
	}else{
		jQuery('#heDepartureAddress').html(address);
	}
}
jQuery('#HotelExtendActive').click(function(){
    jQuery(this).hide();
    jQuery("#HotelExtendCon").show();    
    jQuery("#HotelExtendTitle_1").show();
    jQuery("#HotelExtendTitle_12").hide();
   <?php  if(trim($product_info['hotels_for_early_arrival'])!='') echo '	jQuery("#HotelExtendCon_1").show();';?>
    jQuery("#HotelExtendTitle_2").show();
    jQuery("#HotelExtendTitle_22").hide();
    <?php  if(trim($product_info['hotels_for_late_departure'])!='') echo '	jQuery("#HotelExtendCon_2").show();';?>
});
jQuery('#HotelExtendTitle_1').click(function(){
    jQuery('#HotelExtendTitle_12').show();
    jQuery('#HotelExtendCon_1').hide();
    
    if(jQuery('#HotelExtendCon_1').css("display")=="none" && jQuery('#HotelExtendCon_2').css("display")=="none"){
        jQuery("#HotelExtendCon").hide();
        jQuery('#HotelExtendActive').show();
    }
});

jQuery('#HotelExtendTitle_2').click(function(){
    jQuery('#HotelExtendTitle_22').show();
    jQuery('#HotelExtendCon_2').hide();
    
    if(jQuery('#HotelExtendCon_1').css("display")=="none" && jQuery('#HotelExtendCon_2').css("display")=="none"){
        jQuery("#HotelExtendCon").hide();
        jQuery('#HotelExtendActive').show();
    }
});

jQuery(".comUl li").click(function(){
    jQuery(".comUl li").removeClass("click");   
   //jQuery(this).parent().find("input[type=radio]").each(function(){jQuery(this).attr("checked",false);}); 
    jQuery(this).addClass("click");
    jQuery(this).find("input[type=radio]").attr("checked",true);
});

jQuery(".comUl li").hover(function(){
    jQuery(this).addClass("hover");
},function(){
    jQuery(this).removeClass("hover");
});
function hotelExtensionBudget(){
	if(jQuery.trim(jQuery("#final_dep_date_div").text()) !="" 
		&& jQuery.trim(jQuery("#ShowSteps3").text()) != "" 
		&& ( jQuery.trim(jQuery("#TextBox_HotelExtendPop_1").text())!=""||jQuery.trim(jQuery("#TextBox_HotelExtendPop_2").text())!="")){
		auto_update_budget();
	}
}
function clearHotelExtension(type){
	if(type == "early"){
		document.cart_quantity.early_hotel_checkin_date.value = '';
		jQuery("input[name='early_arrival_hotels']").attr('checked',false);
		jQuery('#ConTitleA_HotelExtendPop_1').html('选择');
		jQuery('#TextBox_HotelExtendPop_1').html("&nbsp");
		jQuery('#HotelExtendPop_1 li').removeClass("click");
		jQuery('#HotelExtendPop_1 .hotelInfo div').hide();
	}
	if(type == "late"){
		document.cart_quantity.late_hotel_checkout_date.value = '';
		jQuery("input[name='staying_late_hotels']").attr('checked',false);
		jQuery('#ConTitleA_HotelExtendPop_2').html('选择');
		jQuery('#TextBox_HotelExtendPop_2').html("&nbsp");
		jQuery('#HotelExtendPop_2 li').removeClass("click");
		jQuery('#HotelExtendPop_2 .hotelInfo div').hide();
	}
	auto_update_budget();
}
</script>
<?php echo db_to_html(ob_get_clean());?>
