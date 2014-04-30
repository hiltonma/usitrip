<script type="text/javascript">
var data = <?php echo tep_get_product_month_price_datas((int)$products_id); //调入大日历框所需要的日期和价格数据；?>;
</script>
<?php
	//产品图片和基本信息
	//include('product_info_module1.php');
	$shows_BigImageString = "";
	$shows_small_img_list = "";
	$img_n = 0;
	
	$ext_img_exist = tep_db_query("select prod_extra_image_id, product_image_name from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$product_info['products_id']."' order by image_sort_order ");
	
	if(tep_db_num_rows($ext_img_exist)>0){
		if($product_info['products_image_med']!=''){
			$product_info['products_image_med'] = ((stripos($product_info['products_image_med'],'http://')===false) ? 'images/':'').$product_info['products_image_med'];
			
			$shows_BigImageString .= '<a id="lightBoxImg_'.$img_n.'" '.$a_big_img_style.' href="'.$product_info['products_image_med'].'" title="'.addslashes(db_to_html($product_info['products_name'])).'"><img src="'.$product_info['products_image_med'].'" /></a>';
			$shows_small_img_list .= '<li><img src="'.get_thumbnails_fast($product_info['products_image_med']).'" '.$small_class.' /></li>';
		}
		
		while($extra_images_rows = tep_db_fetch_array($ext_img_exist)){
			$img_n++;
			$url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
			if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
				$url_product_image_name = $extra_images_rows['product_image_name'];
			}
			$shows_BigImageString .= '<a id="lightBoxImg_'.$img_n.'" '.$a_big_img_style.' href="'.$url_product_image_name.'" title="'.addslashes(db_to_html($product_info['products_name'])).'"><img src="'. $url_product_image_name.'"  alt="'.db_to_html('点击查看大图').'" title="'.db_to_html('点击查看大图').'"/></a>';
			$shows_small_img_list .= '<li><img src="'.get_thumbnails_fast($url_product_image_name).'" '.$small_class.' /></li>';
		}
			
	}else{
		if ($product_info['products_image_med']!='') {
			$new_image = $product_info['products_image_med'];
		} else {
			$new_image = $product_info['products_image'];
		}
		
		if(!tep_not_null($new_image)){
			$new_image = 'noimage_large.jpg';
		}
		$new_image = ((stripos($new_image,'http://')===false) ? 'images/':'').$new_image;
		$shows_BigImageString .= '<a id="lightBoxImg_1" title="'.addslashes(db_to_html($product_info['products_name'])).'" href="'.$new_image.'"><img src="images/'.$new_image.'"  alt="'.db_to_html('点击查看大图').'" title="'.db_to_html('点击查看大图').'"/></a>';
		$shows_small_img_list .= '<li><img src="'.get_thumbnails_fast($new_image).'" /></li>';
	}
	//=====酒店的图片信息{
	if($isHotels){
		
		$imagesInfos = getHotelImagesInfos($product_info['hotel_id']);
		if($imagesInfos!=false){
			$img_n = max($img_n,1);
			for($i=0, $n=sizeof($imagesInfos); $i<$n; $i++){
				$img_n++;
				$shows_BigImageString .= '<a id="lightBoxImg_'.$img_n.'" title="'.addslashes(db_to_html($imagesInfos[$i]['alt'])).'" href="'.$imagesInfos[$i]['src'].'"><img src="'.$imagesInfos[$i]['src'].'"  alt="'.db_to_html('点击查看大图').'" title="'.db_to_html('点击查看大图').'"/></a>';
				$shows_small_img_list .= '<li><img src="'.get_thumbnails_fast($imagesInfos[$i]['src']).'" /></li>';
			}
		}
	}
	//=====酒店的图片信息}


	//弹出层
$close_time_num =  count($PopupObj);
$question_done_tip = "PopupTransferServiceRequest";
$question_done_con_id = "PopupTransferServiceRequestCon";
$con_contents = db_to_html('		
			<div class="popupCon" id="PopupTransferServiceRequestCon" >
			<div id="CSR_SUCCESS" style="display:none">
				 <div class="popupConTop" id="drag">
	                <h3><b>您的自定义服务已经成功提交</b> </h3>
	                <span><a href="javascript:closePopup(\'PopupTransferServiceRequest\')"><img src="'.DIR_WS_ICONS.'icon_x.gif"></a></span>
	            </div>      
				<div class="successTip" >
					<div class="img"><img src="'.DIR_WS_TEMPLATE_IMAGES.'success.jpg"></div>
					<div class="words">您需要的服务请求已经成功提交，我们将会尽快与您联系！</div>
				</div>
				 <div class="btnCenter">
                 	<a href="javascript:;" class="btn btnOrange" onclick="closePopup(\'PopupTransferServiceRequest\')"><button type="button" >确定</button></a>            	
           		 </div>
			</div>
			<div id="CSR_FORM">
	            <div class="popupConTop" >
	                <h3><b>我需要的服务</b>(以下信息必填) </h3>
	                <span><a href="javascript:closePopup(\'PopupTransferServiceRequest\')"><img src="'.DIR_WS_ICONS.'icon_x.gif"></a></span>
	            </div>           
				<div id="CSR_LOADING" style="display:none;text-align:center;line-height:50px;height:50px;" >
					<img src="image/loading_16x16.gif" align="absmiddle" /> 正在提交，请稍候
				</div>	
				'.tep_draw_form('customer_service', tep_href_link('ajax_customer_service_request.php'),'post','id="CustomerService" onsubmit="return false"').'					
	            <ul class="emailCon popupShuttleCon">
	                <li><label>姓名:</label>'.tep_draw_input_num_en_field('name' , '','class="text name required "  title="请输入您的姓名"').'</li>
	                <li><label>&nbsp;</label><span>请使用姓名的拼音。</span></li>
	                <li><label>手机:</label>'.tep_draw_input_field('mobile_phone' , '','class="text required " title="请输入您的手机号码"').'</li>
	                <li><label>邮箱:</label>'.tep_draw_input_field('email' , '','class="text required validate-email" title="请输入您的邮箱"').'</li>
	                <li><label>留言:</label>'.tep_draw_textarea_field('comment', false, '60', '3','请详细描述需要我们为您提供的服务，包括准确的时间、地点等相关信息，我们会第一时间与您取得联系。','onBlur="if(this.value==\'\'){this.value=\'请详细描述需要我们为您提供的服务，包括准确的时间、地点等相关信息，我们会第一时间与您取得联系。\';this.style.color=\'#999\';}" onFocus="if(this.value==\'请详细描述需要我们为您提供的服务，包括准确的时间、地点等相关信息，我们会第一时间与您取得联系。\'){this.value=\'\';this.style.color=\'#111\';}" style="color:#999;" class="textarea required "').'</li>
	            </ul>    				
	            <div class="btnCenter">            
	            	'.tep_draw_hidden_field('from_url' , '','id="CSR_FromUrl"').tep_draw_hidden_field('action' ,'add_service_request').'
	                 <a href="javascript:;" class="btn btnOrange"  id="csrbtn1" onclick=""><button type="submit" >提 交</button></a>
	            	<a href="javascript:;" class="btn btnGrey"  style="display:none"  id="csrbtn2" onclick="javascript:;"><button type="button" id="csrbtn">请稍候</button></a>
	             </div>
				 </form>
             </div>
	     </div>
	     <script type="text/javascript">
		valid = new Validation(\'CustomerService\', {immediate : true,useTitles:true, onFormValidate : submit_csrform});	
		function submit_csrform(result, form){
			if(result == true) {
				jQuery(\'#csrbtn1\').hide();jQuery(\'#csrbtn2\').show();jQuery(\'#CSR_FromUrl\').val(location.href);
				ajax_submit(\'CustomerService\');
			}
			return false;
		}
		</script>
	     ');
$h4_contents = db_to_html("提交需要的服务");
$PopupObj[] = tep_popup_alert($question_done_tip, $question_done_con_id, "470", $h4_contents, $con_contents );

$close_time_num = count($PopupObj);
$question_done_tip = "PopupNotice";
$question_done_con_id = "PopupNoticeCon";
$con_contents = db_to_html('
	<div id="PopupNoticeConCompare" class="popupCon">
			<div class="addSuccess">
				<div class="popContent">
					<div class="popContentA">
						<div class="popContentWords" id="PopupNoticeWords">
							<p> 信息提醒。</p>
						</div>
					</div>
				</div>
				<div class="btnCenter">
					<a class="btn btnOrange" onclick="closePopup(\'PopupNotice\');" href="javascript:;"><button type="button">关闭</button></a>
				</div>
			</div>
	</div>');
$h4_contents = db_to_html("信息提醒");
$PopupObj[] = tep_popup_alert($question_done_tip, $question_done_con_id, "400", $h4_contents, $con_contents );

	
	 //显示特价标签，按买2送2、买2送1、双人特价、普通特价的优先次序处理
	 $specials_num = 0;
	 $special_str = '';
	 $is_buy2get2 = check_buy_two_get_one($product_info['products_id'],'4');
	 $is_buy2get1 = check_buy_two_get_one($product_info['products_id'],'3');
	 $is_double_special = double_room_preferences($product_info['products_id']);
	 $is_special = check_is_specials($product_info['products_id'],true,true);
	 //tour_type_icon
	 /*
	 $tour_type_icon_sql = tep_db_query("select tour_type_icon from " . TABLE_PRODUCTS . " where products_id= '".$product_info['products_id']."' ");
	 $tour_type_icon_row = tep_db_fetch_array($tour_type_icon_sql);*/
	$tour_type_icon_row = array('tour_type_icon'=>$product_info['tour_type_icon']);					
	if((int)$is_special || preg_match('/\bspecil\-jia\b/i',$tour_type_icon_row['tour_type_icon'])){
		$specials_num++;
		$special_str = '特价';
	}
	if((int)$is_double_special || preg_match('/\b2\-pepole\-spe\b/i',$tour_type_icon_row['tour_type_icon'])){
		$specials_num++;
		$special_str = '双人折扣';
	}
	if(($listing['products_class_id']=='4' && ($is_buy2get1=='1' || $is_buy2get1=='2') || preg_match('/\bbuy2\-get\-1\b/i',$tour_type_icon_row['tour_type_icon'])) ){
		$specials_num++;
		$special_str = '买2送1';
	}
	if(($listing['products_class_id']=='4' && ($is_buy2get2=='1' || $is_buy2get2=='3')) || preg_match('/\bbuy2\-get\-2\b/i',$tour_type_icon_row['tour_type_icon'])){
		$specials_num++;
		$special_str = '买2送2';
	}
	if($special_str!= '')$special_str = '<b>'.db_to_html($special_str).'</b>';
	$te_jia_on_list_div = '';
	if(tep_not_null($special_str)){
		$te_jia_on_list_div = '<div class="tag"  >'.$special_str.'</div> ';						
	}
?>


<div id="product_info_content">
	<?php
	if ($messageStack->size('product_info') > 0) {
	?>
		<div><?php echo $messageStack->output('product_info'); ?></div>
	<?php
	}
	?>
	<?php 
//快速登录弹窗 用于review question begin
//@author vincent
$popupTip = "CommonFastLoginPopup";
$popupConCompare = "CommonFastLoginPopupConCompare";
function get_common_fast_login_popup(){
	global $customer_id, $popupTip, $popupConCompare;
	if(!(int)$customer_id)
		$con_contents ='<script language="javascript">fastlogin_success = false;</script>';
	else
		$con_contents='<script language="javascript">fastlogin_success = true;</script>';	

	$con_contents .= '<div class="login">'.tep_draw_form($popupTip.'_form','','post', ' id="'.$popupTip.'_form" ').'
        <ul>
            <li><label>'.db_to_html('电子邮箱:').'</label>'.tep_draw_input_field('email_address','','class="required validate-email text username" title="'.db_to_html('请输入您的电子邮箱').'"').'</li>
            <li><label>'.db_to_html('密码:').'</label><input name="password" type="password" class="required text password" title="'.db_to_html('请输入正确的密码').'" /></li>
            <li><label>&nbsp;</label><input type="submit" class="loginBtn" value="'.db_to_html('&nbsp;登&nbsp;录').' "></li>
            <li><label>&nbsp;</label><a href="'.tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL').'">'.db_to_html('忘记密码?').'</a>'.db_to_html(sprintf('&nbsp;&nbsp;新用户请&nbsp;<a href="%s">注册</a>',tep_href_link("create_account.php","", "SSL"))).'</li>
        </ul></form>
    </div>';
	$h4_contents = db_to_html('<b>请先登录</b>');
	$PopupHtml = tep_popup($popupTip, $popupConCompare, "480", $h4_contents, $con_contents );
	return $PopupHtml;
}
$PopupObj[] = get_common_fast_login_popup();
//快速登录弹窗 用于review question end
	?>
	<?php
	  if ($product_check['total'] < 1) {
	?>
	 <table width="786"  border="0" cellspacing="0" cellpadding="0">
	 <tr>
        <td ><?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
      </tr>
      <tr>
        <td ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td ><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
	<?php
		} else { 
	  
		//howard added 加入购物车窗口 start
		$add_cart_msn = "add_cart_msn";
		$add_cart_msn_con_id = "add_cart_msn_con";
		$add_cart_msn_h4_contents = db_to_html("该团已成功添加到购物车！");
		$add_cart_msn_contents = '
		
		<div class="successTip">
            	<div class="img"><img src="'.DIR_WS_TEMPLATE_IMAGES.'success.jpg"></div>
				<div class="words">
<p>'.db_to_html('行程“').' <a href="'.tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn'))).'">'.db_to_html($products_name).'</a>'.db_to_html('”已经放入购物车。').'</p>
<p>'.db_to_html('购物车共 <span>[Cart_Sum] </span>个团').'&nbsp;&nbsp;&nbsp;&nbsp;'.db_to_html('合计：<span>[Cart_Total]</span>').'</p>
				</div>
            </div>
		<div class="btnCenter">
			<a class="btn btnOrange" href="' . tep_href_link('shopping_cart.php') . '"><button type="button">'. db_to_html('查看购物车').'</button></a>&nbsp;&nbsp;
			<a class="btn btnGrey" href="javascript:;" onclick="closePopup(&quot;'.$add_cart_msn.'&quot;)"><button type="button">'. db_to_html('继续购物').'</button></a>
		</div>
		';
		$PopupObj[] = tep_popup_notop($add_cart_msn, $add_cart_msn_con_id, "440", $add_cart_msn_h4_contents, $add_cart_msn_contents );
		//howard added 加入购物车窗口 end
		?>
	<?php 
				  //浏览全部咨询/评论 相片的时候 为产品名添加链接 	
				  $num_question = get_product_question_num($product_info['products_id']);
				  $num_review = get_product_reviews_num($product_info['products_id']);
				  $num_photo = get_traveler_photos_num($product_info['products_id']);
				  $textPhoto = db_to_html('进行了照片分享');
				  $textReview =db_to_html('进行了游客评论');
				  $textQuestion = db_to_html('进行了问题咨询');
				  $num = 0;
				  if($mnu == 'photos'){
				  		$num =  $num_photo;				  		
				  		$text = $textPhoto;
				 }elseif($mnu == 'reviews'){
				  		$num =  $num_review;				  	
				  		$text = $textReview;
				}elseif($mnu == 'qanda'){
				  		$num =  $num_question;				  		
				  		$text = $textQuestion;
				}
 ?>
 <script type="text/javascript">
function updateTitle(type){
	if(type == "question"){
		jQuery("#view_all_counter1").html("<?php echo $num_question?>");
		jQuery("#view_all_title").html("<?php echo $textQuestion?>");
	}else if(type == "review"){
		jQuery("#view_all_counter1").html("<?php echo $num_review?>");
		jQuery("#view_all_title").html("<?php echo $textReview?>");
	}else if(type == "photo"){
		jQuery("#view_all_counter1").html("<?php echo $num_photo?>");
		jQuery("#view_all_title").html("<?php echo $textPhoto?>");
	}
}
</script>
			<div class="title titleDetailTop"><b></b><span></span></div>
			<div class="proDes">
				<?php 
				//销售的网址复制信息框 start {
				echo db_to_html(servers_sales_track::output_url_box($products_id));
				//销售的网址复制信息框 end }
				?>
				<div class="topTitle">
				  <h1>
				  <?php  if($_GET['seeAll']){?> 
				  <span><?php echo db_to_html(sprintf('有<span id="view_all_counter1">%d</span>位游客在',$num)) ?> </span>&quot;<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn')));?>"><?php echo db_to_html($products_name); ?></a> &quot;<span id="view_all_title" ><?php echo $text ?></span>
				  <?php }
				  else	  echo db_to_html($products_name); 
				  ?></h1>
				  <h2><?php echo db_to_html(str_replace('**','',$products_name1)); ?>&nbsp;</h2>
                  <em></em>
				  <?php
				  include(DIR_FS_MODULES.'product_info/share_to_friend.php');
				  ?>
				</div>
				
				<?php
				if((int)$product_info['GroupBuyType']){
				?>
				<div id="groupBuyDiv" class="groupBuy">
					<a href="<?= tep_href_link('group_buys.php');?>" class="groupTag"><?php echo db_to_html('团购') ?></a>
					<div class="countdown">
						<label><?= db_to_html('距离本次团购结束还有:')?></label>
						<span id="CountDown<?= $product_info['products_id']?>"></span>
					</div>
					
					<div class="countdown orderNum">
						<span><?= db_to_html($product_info['orderNumMsn'])?></span>
					</div>
				</div>
				<script type="text/javascript">
				GruopBuyCountdown(<?= $product_info['products_id']?>, <?= $product_info['CountdownEndTime']?>,'CountDown<?= $product_info['products_id']?>','groupBuyDiv');
				</script>
				<?php
				}
				?>
				<div class="box">
				<div class="proDesDetail">			            
<div class="left">
			<?php echo 	$te_jia_on_list_div;?>
<div class="slider">
<div class="bigImage" id="BigImage">
<?php echo $shows_BigImageString;?>
</div>
<div class="scrollBar">
    <a href="javascript:;" class="goLeft" onfocus="this.blur()" id="PreBtn"></a>
    <div class="scroll" id="Scroll">
        <ul>
             <?php echo $shows_small_img_list;?>
        </ul>
    </div>
    <a href="javascript:;" class="goRight" onfocus="this.blur()" id="NextBtn" ></a>
</div>
            
</div>
		  
        </div>

		<div class="lwkClass">
				<!--团基本资料 start-->
				<div><ul class="mid">
				  <li>
					<label><?php 
					if($product_info['is_hotel']) {
						echo db_to_html('酒店编号：');
					} else {
						echo TEXT_TOUR_CODE;
					}?></label>
					<p><?php echo $product_info['products_model'];?></p>
				  </li>
				  <li>
					<label>
					<?php 
					if($product_info['is_hotel']==1){
						$HEDING_TEXT_TOUR_DEPARS_AT = db_to_html("所在区域：");
					}elseif($product_info['is_transfer']==1){
						$HEDING_TEXT_TOUR_DEPARS_AT = db_to_html("服务区域：");
					}elseif($product_info['is_cruises']==1){
						$HEDING_TEXT_TOUR_DEPARS_AT = db_to_html("出发港口：");
					}else{
						$HEDING_TEXT_TOUR_DEPARS_AT = db_to_html('出 发 地：');//HEDING_TEXT_TOUR_DEPARS_AT;
					}
					echo $HEDING_TEXT_TOUR_DEPARS_AT;
				?>
					
					</label>
					<p>
						<?php 
						if($product_info['departure_city_id'] == '')$product_info['departure_city_id'] = 0;
						$city_class_departure_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_city_id'] . ") order by c.city ");
						while($city_class_departure_at = tep_db_fetch_array($city_class_departure_at_query)) {
							$product_info['departure_city_name'] = $city_class_departure_at['city'];
							echo  db_to_html($city_class_departure_at['city']).', '.$city_class_departure_at['zone_code'].', '.$city_class_departure_at['countries_iso_code_3'].'&nbsp;';
						}
						
						//　酒店所属区域 start
						if((int)$product_info['approximate_location_id']){
							echo db_to_html(getHotelApproximateLocation($product_info['approximate_location_id']));
						}
						//　酒店所属区域　end 
						?>
                        
					</p>
				  </li>
				  
				<?php if($product_info['is_hotel']==0 && $product_info['is_transfer'] == '0'){
					$HEDING_TEXT_TOUR_DEPARS_ENDS_AT = HEDING_TEXT_TOUR_DEPARS_ENDS_AT;
					if($product_info['is_cruises']==1){
						$HEDING_TEXT_TOUR_DEPARS_ENDS_AT = db_to_html('抵达港口：');
					}
				?>
				  <li>
					<label><?php echo $HEDING_TEXT_TOUR_DEPARS_ENDS_AT;?></label>
					<p>
						<?php
						if($product_info['departure_end_city_id'] == '')$product_info['departure_end_city_id'] = 0;
						$city_class_departure_end_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_end_city_id'] . ")");
						while($city_class_departure_end_at = tep_db_fetch_array($city_class_departure_end_at_query)) {
							echo  db_to_html($city_class_departure_end_at['city']).', '.$city_class_departure_end_at['zone_code'].', '.$city_class_departure_end_at['countries_iso_code_3'].'<br />';
						}																						
						?>
					</p>
				  </li>
				  <?php }?>
				  <?php if($product_info['is_hotel'] == 0   ){ //hotel-extension 酒店产品部显示出发时间和持续时间?>
				  <li>
					<label><?php 
							if($product_info['is_transfer'] == '1'){
								$TEXT_OPERATE = db_to_html("服务日期：");
							}elseif($product_info['is_cruises']==1){
								$TEXT_OPERATE = db_to_html("开航日期：");
							}else{
								$TEXT_OPERATE = TEXT_OPERATE;
							} 
							echo $TEXT_OPERATE; ?>
					</label>
					<p id="operate_info">
					<?php  
					$date_arr = explode('、',$product_info['operate'][0]);
					if (count($date_arr) > 3) {
						$show=true;
						$before = array_splice($date_arr,0,3);
						echo join('、',$before);
						if (count($product_info['operate']) == 1){
							echo '<span class="more" onmouseover="jQuery(\'#MoreCon1\').show();" onmouseout="jQuery(\'#MoreCon1\').hide();">'; 
							echo '<a href="javascript:;" >' . db_to_html('查看全部') . '</a>'; 
							echo '<span id="MoreCon1" style="display:none" class="MoreCon"><span class="topArrow"></span><span class="con">';
							echo join('、',$date_arr);
							echo '</span><span id="tipBg"></span> 
									</span> 
								</span>';
						}
						
					} else {
						echo join('、',$date_arr);
					}
					
					
					//$product_info['operate'][0];?>
						<?php if(count($product_info['operate'])>1){
								//110815-2_订单详细页“查看全部”优化
								$all_operate = $product_info['operate'];
								$irregdate = trim($all_operate[count($all_operate) -1 ]);
								if(strpos($irregdate,'- ') === false){
									unset($all_operate[count($all_operate) -1 ]);
								}else{
									$irregdate = '';
								}
								$regdate = implode("<br />",$all_operate );								

							?>
							<span class="more" onmouseover="jQuery('#MoreCon').show();" onmouseout="jQuery('#MoreCon').hide();"> 
								<a href="javascript:;" ><?php echo db_to_html('查看全部');?></a> 
								<span id="MoreCon"> 
									<span class="topArrow"></span><span class="con">									
									<?php 
									if ($show == true) {
										echo join('、',$date_arr);
									}
									$index = 1 ;
									if(trim($regdate) != '') {
										echo db_to_html('<strong>'.$index.'、常规日期</strong><br/>');
										echo $regdate;
										$index++;
									}
									if(trim($irregdate) != '') {
										echo db_to_html('<br/><strong>'.$index.'、特殊日期</strong><br/>');
										echo  $irregdate;
									}

								//echo  implode("<br/>" ,$product_info['operate']);?></span><span id="tipBg"></span> 
								</span> 
							</span> 
                       <?php }?>
					</p>
				  </li>
				  <li style="display:none">
					<label><?php echo db_to_html('持续时间：'); ?></label>
					<p>
					<?php
					$h_or_d = "天";
					if($product_info['products_durations_type']=="1"){
						$h_or_d = "小时";
					}
					echo $product_info['products_durations'].db_to_html($h_or_d);
					?>
					</p>
				  </li>
				  <?php }elseif($product_info['is_hotel']){
					  //hotel-extension
					  //$product_info['is_hotel']
				  
				  ?>
				  		<li><label><?php echo db_to_html('酒店星级：');?></label><p class="stars<?= (int)$product_info['hotel_stars']?>"><?php echo db_to_html($product_info['hotel_stars']."星");?></p></li>
				  <?php
					  if((int)$product_info['meals_id']){
					  ?>
				 		<li><label><?php echo db_to_html('餐饮服务：');?></label><p><?php echo db_to_html(getHotelMealsOptions($product_info['meals_id']));?></p></li>
					  <?php
					  }
					  if((int)$product_info['internet_id']){
					  ?>
				 		<li><label><?php echo db_to_html('网络服务：');?></label><p><?php echo db_to_html(getHotelInternetOptions($product_info['internet_id']));?></p></li>
					  <?php
					  }
					  if((int)$product_info['approximate_location_id']){
					  ?>
				 		<li style="display:none"><label><?php echo db_to_html('所属区域：');?></label><p><?php echo db_to_html(getHotelApproximateLocation($product_info['approximate_location_id']));?></p></li>
					  <?php
					  }
					  if(tep_not_null($product_info['hotel_address'])){
					  ?>
				 		<li><label><?php echo db_to_html('酒店地址：');?></label><p><?php echo db_to_html($product_info['hotel_address']);?></p></li>
					  <?php
					  }
					  /*if(tep_not_null($product_info['hotel_phone'])){
					  ?>
				 		<li><label><?php echo db_to_html('酒店电话：');?></label><p><?php echo db_to_html($product_info['hotel_phone']);?></p></li>
					  <?php
					  }*/
				  }
				  ?>
				  <li>				<?php 
					//计算标签
					$tags = array();
					//if($product_info['upgrade_to_product_id']!='')$tags['recommend'] = db_to_html("强烈推荐") ;//走四方推荐
					if(($product_info['products_class_id']=='4' && ($is_buy2get1=='1' || $is_buy2get1=='2' )) || preg_match('/\bbuy2\-get\-1\b/i',$product_info['tour_type_icon']))
						$tags['buy2get1'] = array('title'=>db_to_html('买二送一'), 'tips'=>db_to_html('买二送一：只需支付2人（同住一房）的团费和相应的附加费，即可3人（同住一房）参团。')) ;//买二送一
											
					$is_buy2get2 = (int)check_buy_two_get_one($product_info['products_id'],'4');
					if(($product_info['products_class_id']=='4' && ($is_buy2get2=='3' || $is_buy2get2=='1')) || preg_match('/\bbuy2\-get\-2\b/i',$product_info['tour_type_icon']))
						$tags['buy2get2'] = array('title'=>db_to_html('买二送二'), 'tips'=>db_to_html('买二送二：只需支付2人（同住一房）的团费，即可4人（同住一房）参团。')) ;//买二送二团	
										
					if(double_room_preferences($product_info['products_id']) || preg_match('/\b2\-pepole\-spe\b/i',$product_info['tour_type_icon'])) 
						$tags['2peplespe']= array('title'=>db_to_html('双人折扣'), 'tips'=>db_to_html('双人折扣'));//双人折扣团
						
					if(check_is_specials($product_info['products_id'],true,true) || preg_match('/\bspecil\-jia\b/i',$product_info['tour_type_icon']))
						$tags['specil'] = array('title'=>db_to_html('特价'),'tips'=>db_to_html('特价团：5%折扣')) ;//特价团
						
					if($product_info['products_stock_status']=='0') 
						$tags['saleover'] = array('title'=>db_to_html('已经卖完'),'tips'=>db_to_html('目前此行程暂时卖完！')) ;//已经卖完		
					
					//低价保证
					if(defined('LOW_PRICE_GUARANTEE_PRODUCTS') && tep_not_null(LOW_PRICE_GUARANTEE_PRODUCTS)){
			        	$tmp_array = explode(',',LOW_PRICE_GUARANTEE_PRODUCTS);
			            for($i=0; $i<sizeof($tmp_array); $i++){
							if(trim($tmp_array[$i])==(int)$product_info['products_id']){
								$tags['lowprice'] = array('title'=>db_to_html('低价保证'),'tips'=>db_to_html('保证全网最低价格！')) ;											
								break;
							}
						}
			        }
					if (count($tags) > 0) {
					?>
					<ul class="tags">
                    <li class="caption"><?php echo db_to_html('促销活动：')?></li>
					<?php $i=0 ;foreach($tags as $tag){	$class = $i%2 == 0 ? 'blue':'green';?>					
                        <li class="tooltip <?php echo $class?>" tooltip="<?php echo $tag['tips'];?>"><?php echo $tag['title'];?></li>
                    <?php $i++;}  ?> 
					</ul>
					<?php 
					}
					/* old 满意度 <label><?php echo db_to_html('满 意 度：')?></label>
					<p><img src="image/icons/icon_face.gif" /> <span id="comment_bai_fen_bi">99%</span></p> */ ?>
					</li>
				  </ul>
				<!--团价格 start-->
				<div class="right">
				  <?php 
				  //print_r($products_price);
				  echo $products_price;
				  ?>
					
					<?php if($display_fast==true){?>
						<?php /*<p><a href="javascript:void(0)" onClick="setProductTab('one',2,5); scroll(0,jQuery('#one2').offset().top)"><?php echo db_to_html('价格明细')?></a></p>*/?>
                        <?php if($product_info['display_room_option']=="1"){?>
						<p class="qi_p"><a href="javascript:void(0)" id="a1" class="icon_help"><?php echo db_to_html('起价说明')?><span><strong><i></i></strong><font><?php 
						if($product_info['is_hotel']) {
							echo db_to_html('起价是指两位客人入住同一房间每人所支付的价格，如需入住第三人和第四人，价格请参照“价格明细”。');
						} elseif((in_array($product_info['agency_id'], array("212","219")) && !in_array($product_info['products_id'], array('2782','2783','2784','2785','2786','2787','2788','2789'))) || in_array($product_info['products_id'],array(107,108,111,199,110,103,112,203,105,109,104,201,100,2681,2680,2679,2678,2677,2676,2732,2677,2733,2851,2676,2678,2852,2883,3041,3040,2754,2755,2756,2757,2758,2759,2760,2952,2953,2954,2955,2956,3081,3082,3083,3084,308,3199,3201,3203,3205,3207,3209,3211))) {
							echo db_to_html('三人同住一个标准房间每人所需支付的价格，房间以两张Full size床为主，第三人及儿童不再加床。');
						} else{
							echo db_to_html('本起价是指四人同住一个标准间每个人所支付的价格，房间以两张Full size床为主，第三，第四人及儿童不再加床。');
						}
							?></font><label></label></span></a></p>
						<?php }?>
					<?php
					}else{
						echo '<p><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=prices&'.tep_get_all_get_params(array('info','mnu','rn'))).'">'.db_to_html('价格明细').'</a></p>';
					}
					// 积分 start {
					
					   // Points/Rewards system V2.1rc2a BOF
						if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) {
							if(!in_array($product_info['products_id'], array_trim(explode(',',NOT_GIFT_POINTS_PRODUCTS)))){
								if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
									$products_price_points = tep_display_points($new_price, tep_get_tax_rate($product_info['products_tax_class_id']));
								} else {
									$products_price_points = tep_display_points($product_info['products_price'],tep_get_tax_rate($product_info['products_tax_class_id']));
								}
								$products_points = tep_calc_products_price_points($products_price_points);
								$products_points = get_n_multiple_points($products_points , $product_info['products_id']);							
								//$products_points_value = tep_calc_price_pvalue($products_points);
								
								if ((USE_POINTS_FOR_SPECIALS == 'true') || $new_price == false) {
									echo db_to_html(sprintf('<p>赠 %s <a href="'.tep_href_link('points.php').'">积分</a></p>' , number_format($products_points,POINTS_DECIMAL_PLACES))) ;//old TEXT_PRODUCT_POINTS
								}
							}else{
								echo db_to_html(sprintf('<p>赠 %s <a href="'.tep_href_link('points.php').'" title="特殊线路！已是最低优惠，不再赠送积分，谢谢!">积分</a></p>' , '0')) ;
							}
						}
					// Points/Rewards system V2.1rc2a EOF 
					//} 积分 end

					//团购 group buy start
					if(GROUP_BUY_ON==true){	//团购
				
						$discount_percentage = auto_get_group_buy_discount($product_info['products_id']);
						if($discount_percentage>0){
							echo '<div class="groupOrder">
                        <div class="tip" onmouseover="jQuery(\'#groupCon\').show();" onmouseout="jQuery(\'#groupCon\').hide();">
                            <img src="image/icons/green_right.gif"><div style=" display:none;" id="groupCon"><span class="botArrow"></span><span class="tipCon">'.db_to_html(GROUP_MIN_GUEST_NUM.'人及以上参加行程为两天或以上的团即可享受团体预定'.($discount_percentage*100).'%的优惠。').'</span></div>
                        </div>
                       <a target="_blank" href="'.tep_href_link('landing-page.php','landingpagename=group_buy').'">'.db_to_html("团体预订").'</a>
                    </div>';							
						}
					 }
					?>
					<?php
					if($product_info['upgrade_to_product_id']!=''){
						echo '<div class="recommended"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$product_info['upgrade_to_product_id']).'">'.tep_template_image_button('recommended.gif', '', '', '', 'align="absmiddle" ').'</a></div>';#
					}
					?>				
				<?php 
				//计算标签
				/* 按Sofia 的意思 移到上面去了 	$tags = array();
					//if($product_info['upgrade_to_product_id']!='')$tags['recommend'] = db_to_html("强烈推荐") ;//走四方推荐
					if(($product_info['products_class_id']=='4' && ($is_buy2get1=='1' || $is_buy2get1=='2' )) || preg_match('/\bbuy2\-get\-1\b/i',$product_info['tour_type_icon']))
						$tags['buy2get1'] = db_to_html('买二送一') ;//买二送一
											
					$is_buy2get2 = (int)check_buy_two_get_one($product_info['products_id'],'4');
					if(($product_info['products_class_id']=='4' && ($is_buy2get2=='3' || $is_buy2get2=='1')) || preg_match('/\bbuy2\-get\-2\b/i',$product_info['tour_type_icon']))
						$tags['buy2get2'] = db_to_html('买二送二') ;//买二送二团	
										
					if(double_room_preferences($product_info['products_id']) || preg_match('/\b2\-pepole\-spe\b/i',$product_info['tour_type_icon'])) 
						$tags['2peplespe']=db_to_html('双人折扣');//双人折扣团
						
					if(check_is_specials($product_info['products_id'],true,true) || preg_match('/\bspecil\-jia\b/i',$product_info['tour_type_icon']))
						$tags['specil'] = db_to_html('特价') ;//特价团
						
					if($product_info['products_stock_status']=='0') 
						$tags['saleover'] = db_to_html('已经卖完') ;//已经卖完		
					
					//低价保证
					if(defined('LOW_PRICE_GUARANTEE_PRODUCTS') && tep_not_null(LOW_PRICE_GUARANTEE_PRODUCTS)){
			                    	$tmp_array = explode(',',LOW_PRICE_GUARANTEE_PRODUCTS);
			                    for($i=0; $i<sizeof($tmp_array); $i++){
										if(trim($tmp_array[$i])==(int)$product_info['products_id']){
											$tags['lowprice'] = db_to_html('低价保证') ;											
											break;
										}
									}
			                    }
					?>
					<ul class="tags">
					<?php $i=0 ;foreach($tags as $tag){	$class = $i%2 == 0 ? 'blue':'green';?>					
                        <li class="<?php echo $class?>"><?php echo $tag;?></li>
                    <?php $i++;}  ?> 
					</ul> */ ?>
                    <?php echo db_to_html('满意度：')?><img src="image/icons/icon_face.gif" /> <span id="comment_bai_fen_bi">99%</span>		
				</div>
                <div class="del_float"></div>
				</div>
				<div class="wenzi">  
				  <ul class="mid">
				  <li>
				<?php
				$TEXT_HIGHLIGHTS = TEXT_HIGHLIGHTS; 
				if($isHotels){
					$TEXT_HIGHLIGHTS = db_to_html("酒店特色："); 
				}
				?>
				<label><?php echo $TEXT_HIGHLIGHTS;?></label><?php // 行程特色  ?>
				<div class="xcts">
				<?php
				/**
				 * 检查products_small_description 的字符串长度如果超出规定的长度 只显示前部分后部分隐藏
				 * 如果长度未超出则不进行截取
				 * @var $displyNum 显示的前段字符串长度
				 */
				$products_small_description = $product_info['products_small_description'];
				$displyNum = 190 ;
				if(strlen($products_small_description) > $displyNum ) {
					$products_small_description = strip_tags($products_small_description);
					$products_small_description_first = cutword($products_small_description , 200,"");
						$products_small_description_last = str_replace($products_small_description_first, "", $products_small_description);
						echo stripslashes2(db_to_html($products_small_description_first));
						echo '<span id="morecon" style="display:none;">'.stripslashes2(db_to_html($products_small_description_last)).'</span><span id="MainPointMore">... <span class="more"><a href="javascript:;" >'.db_to_html('查看全部') .'</a></span></span>';
				}else {
						echo stripslashes2(db_to_html($products_small_description));
				}
				
				?>
				</div>
				<script type="text/javascript"> 
				jQuery("#MainPointMore").toggle(function(){
					jQuery("#morecon").show();
					jQuery(this).html("<span class='more less'><a href='javascript:;' ><?php  echo db_to_html('隐藏') ?></a></span>");
				},function(){
					jQuery("#morecon").hide();
					jQuery(this).html("... <span class='more'><a href='javascript:;' ><?php  echo db_to_html('查看全部') ?></a></span>");
				}); 
				</script> 

				
				<?php
				//查看景点地图
				$maps_file = DIR_FS_CATALOG."products_swf_maps/".$product_info['products_id'].".swf";
				if(file_exists($maps_file)){
				?>
				<style>
				.ckmap{ padding-left:20px; padding-top:2px; padding-bottom:2px; background:url(image/icons/map.gif) no-repeat; background-position:2px 0px;}
				.ckmapLink{ color:#3180F6; text-decoration:none; font-size:12px;}
				.ckmapLink:hover{ text-decoration:underline;}
				</style>
				<span class="ckmap" id="view_attractions_swf_maps"><a target="_blank" href="<?php echo tep_href_link('product_info_maps.php','products_id='.$product_info['products_id'])?>" class="ckmapLink"><?php echo db_to_html("查看地图");?></a></span>
				<?php
				}else{ echo "&nbsp;";}
				?>								
				</li>
				</ul>
				
		<!--照片,map和评论统计 start-->
		  <div class="routeInfo">
            <ul>
              <li><a    href="javascript:void(0);" onClick="setProductTab('two',2,4); scroll(0,jQuery('#two2').offset().top);updateTitle('question');" ><?php echo sprintf(db_to_html('问题咨询(<span>%d</span>)'), $num_question);?></a></li>
                <li><a href="<?=tep_href_link('new_travel_companion_index.php');?>" ><?php echo sprintf(db_to_html('结伴同游(<span>%d</span>)'), get_product_companion_post_num($product_info['products_id']));?></a></li>
              <li style="display:none;"><a href="javascript:void(0);" onClick="setProductTab('two',3,4); scroll(0,jQuery('#two3').offset().top); lazyload({defObj: '#reviews_photos_ul'});updateTitle('photo');" ><?php echo sprintf(db_to_html('照片分享(<span>%d</span>)'), $num_photo);?></a></li>
			  <li class="comment"><a href="javascript:void(0);" onClick="setProductTab('two',1,4); scroll(0,jQuery('#two1').offset().top);updateTitle('review');" ><?php echo sprintf(db_to_html('用户点评(<span>%d</span>)'), $num_review);?></a> 
		</li>
		  <script type="text/javascript">
		  var myscroll = window.scroll;
			jQuery(document).ready(function(){
				if(jQuery("#comment_bai_fen_bi_h2").html()!=null){
					jQuery("#comment_bai_fen_bi").html(jQuery("#comment_bai_fen_bi_h2").html());
				}
			});
			</script>
			  <?php
				if($product_info['products_map'] != ''){
					$new_image_map = $product_info['products_map'];
					echo '<li><a href="'.DIR_WS_IMAGES . $new_image_map.'"  rel="lightbox" target="_blank">'.db_to_html("行程地图").'</a></li>';
				}
				?>
              
            </ul>
          </div>
		  <!--照片,map和评论统计 end-->

				</div>
		</div>		
				<div class="clear"></div>
                </div>
          </div>      
				<!--团基本资料 end-->
				<?php
				//购买模块
				include('product_info_module_right_1.php');
				?>

			</div>
			<?php /*<div class="title titleDetailBot"><b></b><span></span></div>*/ ?>
		<div class="proDetailLeft">
			
			<?php
			//接送服务套餐预订
			if($product_info['is_transfer']!= 1 && $product_info['recommend_transfer_id'] > 0){				 
			?>
			    <div class="shuttle" id="RecommendShuttleDiv">
		        <h3><?php echo db_to_html('优惠套餐推荐'); ?></h3>
		        <div class="shuttleLeft">
		            <dl>
		                <dt>
		                <?php 
		                $myurl = tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$products_id);
		                echo  '<a href="'.$myurl.'">'.substr(trim($shows_small_img_list) , 4, strpos($shows_small_img_list,'</li>')-4).'</a>'; 
		                ?>
		                </dt>
		                <dd>
		                <h4><a href="<?php echo $myurl;?>"><?php echo db_to_html(cutword($products_name,60,'')); ?></a></h4>
		                <h5><?php echo db_to_html(cutword($products_name1,60,'')); ?></h5>		                   
		                </dd>
		            </dl>
		            <div class="midLine">+</div>
		            <?php 
		                $tpid = intval($product_info['recommend_transfer_id']);
		                $query = tep_db_query("SELECT  p.products_image,pd.products_name FROM  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
		                 WHERE p.products_id = '" .   $tpid . "' AND pd.products_id = p.products_id 
		                  AND pd.language_id = '" . (int) $languages_id . "'");
		                $tpinfo = tep_db_fetch_array($query);		                
		                $tpinfo['products_name1']=strstr($tpinfo['products_name'], '**');
						if($tpinfo['products_name1']!='' && $tpinfo['products_name1']!==false)$tpinfo['products_name']=str_replace($tpinfo['products_name1'],'',$tpinfo['products_name']);
						$tpurl = tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$tpid);
		                ?>
		            <dl>
		                <dt><a href="<?php echo $tpurl;?>"><?php echo tep_image(DIR_WS_IMAGES . $tpinfo['products_image'], $tpinfo['products_name'].$tpinfo['products_name1'], 60, 33) ;?></a></dt>
		                <dd>
		                    <h4><a href="<?php echo $tpurl;?>"><?php echo db_to_html(cutword($tpinfo['products_name'],60,'')); ?></a></h4>
		                     <h5><?php echo db_to_html(cutword($tpinfo['products_name1'],60,'')); ?></h5>
		                </dd>
		            </dl>
		        </div>
		        <div class="shuttleRight">
		            <p></p>
		            <p><a href="javascript:;" class="btn btnOrange" onclick="jQuery('#ShuttleOption').show();jQuery('#RecommendShuttleDiv').hide()"><button><?php echo db_to_html("立即预订")?></button></a></p>
		        </div>
		    </div>
			<?php }?>
			<?php
			//产品行程信息多子菜单
			include('product_info_module2_2011.php');
			?>			
		</div>
		
		<div class="proDetailRight">
		<?php
		//right column 右栏目
		include('product_info_module_right_2011.php');
		?>
		</div>
</div>
  

		<?php
	    
		}
		?>   	
		  
		  
					

<script type="text/javascript">
    <!--
<?php /*快速计算并显示当前房间的价格*/?>
function calculation_room_price(){
	auto_update_budget();
}
	
	
    function formCallback(result, form) {
        window.status = "valiation callback for form '" + form.id + "': result = " + result;
    }
    var check = true;
<?php
if ($departuredate_true == "in") {
?>
		var set2 = document.cart_quantity._1_H_address; 
		if(typeof(set2)=="undefined" || set2.value != ''){
            var check = false;    
        }    
<?php
}
?>
    var valid = new Validation('cart_quantity', {immediate : true,useTitles:true, onFormValidate : formCallback});
    Validation.add('validate-select-custom-pickup', '', function(v) {
        return ((v != "" && check == true) && (v != "<?php echo TEXT_SELECT_NOTHING; ?>" && check == true) && (v != "<?php echo TEXT_HEADING_NONE_AVAILABLE ?>" && check == true) && (v != 'Please make a selection...') && (v.length != 0) && (v != "0" && check == true));
    });

    /*房间信息选项 start*/
    var textRooms="<?php echo TEXT_ROOMS; ?>";
    /* 是否是美东，并且是买二送二 */
    var isMeiDongMaiErSongEr = <?php echo checkIsEastBuyTwoGetTwo($HTTP_POST_VARS['regions_id'],$HTTP_POST_VARS['tour_type_icon']) ? 'true' : 'false'?>;
    var textAdults= isMeiDongMaiErSongEr ? "<?php echo db_to_html('人数')?>" : "<?php echo TEXT_ADULTS; ?>";
    var textChildren="<?php echo TEXT_CHILDREN; ?>";				 
    var textRoomX="<?php echo TEXT_ROOMS; ?> ?:";
    var textChildX="<?php echo TEXT_CHILDREN; ?> ?:";
    /* 为一日团增加"结伴同游"选项 */
    var textTravelCompanion = "<?php echo db_to_html('结伴同游') ?>";
    
    refresh();		   

    function refresh() {
        var cart_quantity = document.getElementById("cart_quantity");
        maxChildren = 0;

        for (var i = 0; i < numRooms; i++) {
            if (childrenPerRoom[i] > maxChildren) {
                maxChildren = childrenPerRoom[i];
            }
            if(numRooms==16){
                break;
            }
        }
		<?php
		if($product_info['display_room_option'] == 1){
			$h6_str = "请选择酒店房间";
		}else{
			$h6_str = "请选择参团人员";
		}
		?>
        var x = '';//'<h6><b><?php echo db_to_html($h6_str);?></b><span onclick="jQuery(&quot;#hot-search-params&quot;).hide();"><img src="image/icons/icon_x.gif"></span></h6>';
        if(typeof(adultHelp)!="undefined"){
			if (adultHelp.length > 0) {
            	x += adultHelp + "<p>\n";
        	}
		}
        if (numRooms > 17) {
            x += textRooms;
            x += renderRoomSelect();

        } else {
            x += '<table cellspacing="0" cellpadding="0" width="400" class="roomPopTable" >\n';
			<?php
			if ($product_info['display_room_option'] == 1) {
				$colspan = 5;
				//if ($product_info['agency_id'] == "2") { $colspan = 4; }
			?>
            var _tmp_checked = '';
			if(jQuery('#_checkboxTravelCompanion').attr('checked') == true){ _tmp_checked = ' checked="checked" '; }
			
			
			x += '<thead><tr><td colspan="<?=$colspan?>"><span>'+textRooms+pad+'</span>'+renderRoomSelect()+' <label><input type="checkbox" '+ _tmp_checked +' id="_checkboxTravelCompanion" name="_checkboxTravelCompanion" onclick="fastSelectTravelCompanion(this);" /> <?php echo db_to_html('结伴同游');?></label></td></tr></thead>';	//蓝色标标行和房间总数选择菜单
			<?php
			}else{
			    //by panda  为一日团增加“结伴同游选项”{
			if(TRAVEL_COMPANION_OFF_ON=='true'){
			    //$colspan = 4; 
			
			?>     
			//x += '<thead><tr><td colspan="<?=$colspan?>"><span>'+textTravelCompanion+pad+'</span>'+renderRoomSelectForTravelCompanion()+'</td></tr></thead>';	//蓝色标标行和结伴同游选择菜单
			<?php 
			}
			//by panda  为一日团增加“结伴同游选项”}
			}
			?>
			x += '<tr>';
            //if (numRooms > 1 && numRooms < 16 ) {
                x += '<td>&nbsp;</td>';
            //}

            var title_bed_td = '';
            var min_num_guest = 1 ;
<?php
//海鸥团：增加选择床型这些选项。
//if ($product_info['agency_id'] == "2") {
/* 海鸥供应商的一日团中，删除床型的选择，因为不会入住酒店就不应该存在床型的选择 By Panda */
if ($product_info['agency_id'] == "2" && $product_info['products_durations'] != "1") {
?>
                var options_array = new Array();    
                options_array[0] = new Array(0,'<?php echo TEXT_BED_STANDARD; ?>');
                options_array[1] = new Array(1,'<?php echo TEXT_BED_KING; ?>');
                options_array[2] = new Array(2,'<?php echo TEXT_BED_QUEEN; ?>');
                title_bed_td = '<td><label>'+ '<?= db_to_html('床型'); ?>' +'</label></td>';
    
<?php
}
//vincent {USLA54-102,USLA2-1650,USLA2-1651,USLA52-1665,USLA54-1350
if(in_array($product_info['products_id'],array(102,1650,1651,1665,1350))){
	$mng = intval($product_info['min_num_guest']);
	if($mng > 1 ){
		echo 'min_num_guest='.intval($product_info['min_num_guest']);
	}
}
//
?>

            //房间标题行
			x += '<td><label>'+textAdults+pad+'</label></td><td><label ' + (isMeiDongMaiErSongEr ? 'style="display:none"' : '') + '>'+textChildren+pad+'</label></td>'+ title_bed_td +'<td><label id="room_price_title">&nbsp;</label></td></tr>\n';   
            for (var i = 0; i < numRooms; i++) {

                /*去除左边的那个空白列
				x += '<tr><td>';
                    x += '&nbsp;';
                x += '</td>';
                */
				//if (numRooms > 1 && numRooms < 16 ) {
				    RoomLeftTitle = getValueChinese(textRoomX, i+1)+pad;				 
					if(numRooms<=0 ){ RoomLeftTitle = ""; }
					if(numRooms==16){ RoomLeftTitle = "<?= db_to_html("结伴拼房")?>"; }	
					<?php if($h6_str =="请选择参团人员") { ?>RoomLeftTitle = "";<?php }?>
									
				x += '<td class="left" id="room-' + i + '-left-title"><nobr>&nbsp;'+ RoomLeftTitle + '</nobr></td>';
                //}
                x += '<td width="70">';               
                x += buildSelect('room-' + i + '-adult-total', 'setNumAdults(' + i + ', this.options[this.selectedIndex].value); calculation_room_price();', min_num_guest, 20, adultsPerRoom[i]);
                x += '</td><td width="70"><span ' + (isMeiDongMaiErSongEr ? 'style="display:none"' : '') + '>';
                <?php if($product_info['products_kids'] != '0.00' || $isCruises == true){ //fix vincent ,修正当产品的小孩价格设置为0 禁止选择小孩数量，邮轮团除外(howard)?>
                x += buildSelect('room-' + i + '-child-total', 'setNumChildren(' + i + ', this.options[this.selectedIndex].value); calculation_room_price();', 0, 10, childrenPerRoom[i]);//setNumChildren(' + i + ', this.options[this.selectedIndex].value)
				<?php }else{?>
				  x += '<select name="'+'room-' + i + '-child-total'+'" disabled><option value="0">0</option></select>'
				<?php }?>
				x += '</span></td>';

                if(title_bed_td!=''){	//床型选择框
                    var max_n = 1;
                    var sel_n = 0;
                    if( (Number(adultsPerRoom[i])+Number(childrenPerRoom[i])) == 2 ){ max_n = options_array.length; sel_n = 1;}
                    if(cart_quantity.elements['room-'+ i +'-bed']===undefined){
                    }else{
                        sel_n = cart_quantity.elements['room-'+ i +'-bed'].value;
                    }
                    x += '<td width="160">'+ buildStrSelect('room-' + i + '-bed', '', options_array, max_n , sel_n).replace(/class="sel2"/,'class="bedS"') +'</td>';
                }else{
                    x += '<td id="room-price-'+ i +'" width="160">&nbsp;</td>';
				}

                x += '</tr>\n';

                var travel_comp = document.getElementById("travel_comp");
                if(travel_comp!=null){
                    travel_comp.value = '0';
                }
                if(numRooms==16){
                    if(travel_comp!=null){
                        travel_comp.value = '1';
                    }
                    break;
                }
            }


<?php
//单人配房选项 start {
if ($product_info['products_single_pu'] > 0) {
    $checkbox_checked = '';
    $agree_single_occupancy_pair_up = $cart->contents[$_GET['products_id']]['roomattributes'][6];
    if ((int) $agree_single_occupancy_pair_up) {
        $checkbox_checked = ' checked ';
    }
    $dan_ren_pei_fang_str = "接受男性单人配房";
    if (1 || (defined('SEXES_ROOM_PROD_IDS') && SEXES_ROOM_PROD_IDS != "")) {
        $both_ids = explode(',', str_replace(' ', '', SEXES_ROOM_PROD_IDS));
        if (1 || in_array($_GET['products_id'], $both_ids)) {
            $dan_ren_pei_fang_str = "接受单人配房（与同性别同团客人同住一房）。";
        }
    }
?>
			x += '<tr><td>&nbsp;</td><td colspan="<?= ($colspan-1);?>" id="div_agree_single_occupancy_pair_up"><label><input name="agree_single_occupancy_pair_up" type="checkbox" id="agree_single_occupancy_pair_up" value="1" <?= $checkbox_checked ?>> <?php echo db_to_html($dan_ren_pei_fang_str); ?></label></td></tr>';/*<span onmouseout="jQuery(&quot;#RoomTipCon&quot;).hide();" onmouseover="jQuery(&quot;#RoomTipCon&quot;).show();" class="roomTip">[?]<span id="RoomTipCon" style="display: none;"><?php echo TEXT_TOUR_SINGLE_PU_OCC_TIPS; ?></span></span>*/
<?php
}
//单人配房选项 end }
?>

            x += '</table>\n';
			/*x += '<div class="submit btnCenter"><a class="btn btnOrange" href="javascript:;" onClick="SetShowSteps3();"><button type="button"><?= db_to_html("确 定");?></button></a><a class="btn btnGrey" href="javascript:void(0);"><button type="button"><?= db_to_html("取 消");?></button></a></div>';	//确定按钮*/
			

            /*var didHeader = false;
            if (didHeader) {
                x += '</table>\n';
            }*/
        }

        if(document.getElementById("hot-search-params-room"))document.getElementById("hot-search-params-room").innerHTML = x;
        //自动去掉多余的opction选项。
        sub_rooms_people_num();
        set_child_option();
    }

    /*房间信息选项 end*/	

    //-->
</script>


<?php
if($departuredate_true != "in"){
?>
<script type="text/javascript">
function validate(){					
	/*if(document.cart_quantity.availabletourdate.value==""){
	alert("<?php echo TEXT_SELECT_VALID_DEPARTURE_DATE;?>")
	return false
	}*/
	return true
}

				

</script>
<?php } ?>
<script type="text/javascript">
function createRequestObject(){
	var request_;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_ = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_ = new XMLHttpRequest();
	}
	return request_;
}

			//var http = createRequestObject();
			var http1 = createRequestObject();
		
				function calculate_price(products_id,availabletourdate,numberOfRooms)
				{
					if(numberOfRooms > 0){
						var querystring = '';
						//alert(numberOfRooms);

						for(i=0;i<numberOfRooms;i++){
						//alert(adultsPerRoom[i]);
						//alert(childrenPerRoom[i]);
						querystring = querystring + '&room-'+i+'-adult-total='+adultsPerRoom[i]+'&room-'+i+'-child-total='+childrenPerRoom[i]+'';
						}
					
					}
					try{
							http1.open('get', 'budget_calculation_ajax.php?products_id='+products_id+'&availabletourdate='+availabletourdate+'&numberOfRooms='+numberOfRooms+querystring+'&action_calculate_price=true');
							http1.onreadystatechange = hendleInfo_change_attributes_list;
							http1.send(null);
					}catch(e){ 
						//alert(e);
					}
				}
				
				function hendleInfo_change_attributes_list()
					{
						
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 document.getElementById("price_ajax_response").innerHTML = response1;
						}
					}
</script>

<?php
//自动添加产品点击量
$index_type = 'click';
auto_add_product_index($product_info['products_id'],$index_type );
//自动添加产品点击量end
?>
<?php 
//写评论的弹出框，已有新版。旧版取消
	//require_once('write_review_ajax.php');
?>

<?php
// 让走四方联系我弹出窗 start
echo db_to_html($TffContactMe->form_html());
echo db_to_html($TffContactMe->javascript());
// 让走四方联系我弹出窗 end
?>
<?php
	//check for featured deal - start
	$featured_first_departure_date = substr($featured_first_availabletourdate,0,10);
	$check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$product_info['products_id']."' and active_date <= '".date("Y-m-d")." 23:59:59' and expires_date >= '".date("Y-m-d")." 00:00:00'");
	if(check_is_featured_deal($product_info['products_id']) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
		$expected_price_people_array = tep_get_featured_expected_people_and_price($product_info['products_id']);
		$featured_deal_price_for_this_tour = $expected_price_people_array[1];
		//$products_price = $currencies->display_price($featured_deal_price_array[0]['text'], tep_get_tax_rate($product_info['products_tax_class_id']));
		?>
		<script type="text/javascript">
		if(document.getElementById('featured_deal_discount_txt')==null){ alert('Not find id "featured_deal_discount_txt"');}
		document.getElementById('featured_deal_discount_txt').innerHTML = '<?php echo sprintf(TXT_FEATURED_DEAL_DISC_INFO,'<strong>' . $currencies->display_price($featured_deal_price_for_this_tour, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</strong>') ; ?><br/><a href="<?php echo tep_href_link(FILENAME_FEATURED_DEALS, 'products_id=' . $product_info['products_id']); ?>");"><?php echo TXT_FEATURED_MORE_INFO; ?></a>';
		</script>
<?php
	}							
	//check for featured deal - end
?>

<?php
/*zip_load 图片压缩上传工具 start*/
?>
    <script src="includes/javascript/zip_upload/jquery-jtemplates.js" type="text/javascript"></script>
    <script src="includes/javascript/zip_upload/swfobject.js" type="text/javascript"></script>
    <script type="text/javascript">
        var eAddPhoto;
        var eAppendPhoto;
        var jPhotoList;
        var jProcess;
        var allSize = 0;
        var doneCount = 0;
        var infos = [];
        var isInited = false;
        function showFlash() {
            jPhotoList = jQuery("#ulPhotoList");
            jSelect = jQuery("#divSelect");
            jProcess = jQuery("#divProcess");
            jDone = jQuery("#divDone");
            var vars = {
                serverUrl: "includes/javascript/zip_upload/upload_photos_share.php",
                jsUpdateInfo: "jsUpdateInfo",
                imgWidth: 800,
                imgHeight: 800,
                imgQuality: 80
            }
            var vars1 = vars;
            vars1.flashID = "divAddPhoto";
            vars1.labelColor = "#000000";
            vars1.labelText = "<?php echo db_to_html("上传照片");?>";
            vars1.hasUnderLine = false;
            swfobject.embedSWF("includes/javascript/zip_upload/PhotoUploader.swf", "divAddPhoto", "80", "20", "10.0.0", "includes/javascript/zip_upload/expressInstall.swf", vars1, { wmode: "Transparent" });
            var vars2 = vars;
            vars2.flashID = "divAppendPhoto";
            vars2.labelColor = "#0096FF";
            vars2.labelText = "<?php echo db_to_html("加入更多照片");?>";
            vars2.hasUnderLine = true;
            swfobject.embedSWF("includes/javascript/zip_upload/PhotoUploader.swf", "divAppendPhoto", "75", "20", "10.0.0", "includes/javascript/zip_upload/expressInstall.swf", vars2, { wmode: "Transparent" });
        }
        function upload() {
            if (infos.length == 0) {
                alert("<?php echo db_to_html("请先选择照片");?>");
                return;
            }
		
            if (doneCount >= infos.length) {
                jProcess.hide();
                jDone.show();
                
				jQuery("div a", jDone).click(function() { //去编辑标题和描述
					clickDone();
				});
				jQuery("#dragUpload span").click(function() {	//上传完成后点击关闭按钮时去编辑标题和描述
					clickDone();
					setTimeout('submit_photo_data(document.getElementById("form_photo"))',700);
				});
				
                return;
            }
            //
            var index;
            for (var i = 0; i < infos.length; i++) {
                var info = infos[i];
                if (info.status == "selected") {
                    index = i;
                    break;
                }
            }
            //
            if (doneCount == 0) {
                jQuery("#divAppendPhoto").height(0);
                updateProgress();
                jSelect.hide();
                jProcess.show();
            }
            jQuery(".selected", jQuery("li:nth-child(" + (infos+1) + ")", jPhotoList)).unbind("click");
            swfobject.getObjectById(infos[index].flashID).Load(infos[index].name);
        }
		
        function clickDone(){	//上传完成后打开修改标题和描述

			jQuery("#review_result_photo").html("");
			closePopup("popupUpload_Load");
			var boxs = jQuery("#uploaded_photos_box").val().split(';');
			var html_code = '<form id="form_photo" onsubmit="submit_photo_data(this); return false;">';
			
			<?php
			if(strtolower(CHARSET)=='gb2312'){
				$onblur = 'this.value = simplized(this.value);';
			}else{
				$onblur = 'this.value = traditionalized(this.value);';
			}
			?>
			
			for(var i=0; i<(boxs.length-1); i++){
				var tmp_boxs_array = boxs[i].split('|');
				html_code += '<li><div class="pic"><tr><td valign="middle" align="center"><a href="'+tmp_boxs_array[4]+'" target="_blank"><img title="<?= db_to_html('点击查看大图')?>" alt="<?= db_to_html('点击查看大图')?>" src="'+tmp_boxs_array[5]+'" /></a></div><p><label style="visibility:visible"><input type="hidden" name="image_name[]" value="'+tmp_boxs_array[1]+'"/><?php echo db_to_html("标题：");?></label><input onblur="<?= $onblur;?>" type="text" name="image_title[]" class="text" value=""/></p><p><label style="visibility:visible"><?php echo db_to_html("描述：");?></label><textarea onblur="<?= $onblur;?>"  name="image_desc[]" class="textarea"></textarea></p></li>';
			}
			
			html_code += '<div class="btnCenter"><a href="javascript:;" class="btn btnOrange"><button type="submit"><?php echo db_to_html("确定");?></button></a></div>';
			html_code += '</form>';
			jQuery("#review_result_photo").html(html_code);
			jQuery("#review_result_photo").removeClass("photoList");
			jQuery("#review_result_photo").addClass("photoUpload");
			
			
			//var jPhotoUpload = jQuery(".photoUpload");
			//jPhotoUpload.setTemplateElement("template1");
			//jPhotoUpload.processTemplate(infos);
			infos = [];
			// clear
        }
		function formatSize(size) {
            if (size > 1024 * 1024) {
                return Math.round(size * 100 / (1024 * 1024)) / 100 + "MB";
            } else {
                return Math.floor(size / 1024) + "KB";
            }
        }
        function updateProgress() {
            var allPersent = Math.floor(doneCount * 100 / infos.length) + "%";
            jQuery("div:nth-child(1) div:only-child", jProcess).width(allPersent);
            jQuery("span:nth-child(2)", jProcess).text(allPersent);
        }
        function updateSummary() {
            jQuery("#txtPhotoCount").text(infos.length);
            jQuery("#txtAllSize").text(formatSize(allSize));
        }
        function getIndexByName(name) {
            for (var i = 0; i < infos.length; i++) {
                if (infos[i].name == name) {
                    return i;
                }
            }
            return -1;
        }
        var uploading, percent;
        function jsUpdateInfo(flashID, name, status, size, message) {
            var index = (status == "selected" ? infos.length : getIndexByName(name));
            if (status == "selected") {
				if (infos.length == 0) {
                    allSize = 0;
                    doneCount = 0;
                    jPhotoList.children().remove();
                    showPopup('popupUpload_Load', 'popupConUpload');                    
                    jDone.hide();
                    jSelect.show();
                    jQuery("#divAppendPhoto").height(20);
                }
                if (getIndexByName(name) >= 0) {
                    alert(name + "<?php echo db_to_html("已存在!");?>");
                    return;
                }
				if(infos.length>=100){
                    //alert("<?php echo db_to_html("一次只能上传100张!");?>");
                    return;
				}else{
					infos.push({
						name: name,
						flashID: flashID,
						title: name.substr(0, name.lastIndexOf(".")),
						status: status
					});
					jPhotoList.append('<li><div class="name">' + name + '</div><div class="size">' + formatSize(size) + '</div><div class="status ' + status + '"></div><div class="process" style="width:0%;"></div></li>');
				   jQuery(".selected",jQuery("li:nth-child(" + infos.length + ")",jPhotoList)).click(function() {
						swfobject.getObjectById(flashID).Remove(name);
					});
					allSize += size;
					updateSummary();
				}
            } else {
                var jPhoto = jQuery("li:nth-child(" + (index + 1) + ")", jPhotoList);
                var jSize = jQuery("div.size", jPhoto);
                var jStatus = jQuery(".status", jPhoto);
                var jProgress = jQuery(".process", jPhoto);
                infos[index].status = status;
                if (status == "void") {
                    jPhoto.remove();
                    allSize -= size;
                    var temp = [];
                    for (i = 0; i < infos.length; i++) {
                        if (infos[i].name != name) {
                            temp.push(infos[i]);
                        }
                    }
                    infos = temp;
                    updateSummary();
                } else {
                    jStatus.removeClass();
                    jStatus.addClass("status " + status);
					switch (status) {
                        case "loading":
                            jProgress.width(message);
                            break;
                        case "loaded":
                            jSize.text(formatSize(size));
                            break;
                        case "notLoad":
                            alert(message);
                            break;
                        case "uploaded":
							if(message=="0"){
								alert(message);
							}else{
								jProgress.width("100%");
								++doneCount;
								updateProgress();
								upload();
								var tmp_array = message.split('|');
								if(tmp_array[0]=="1"){
									tmp_array[1];	//返回的文件名
									tmp_array[3];	//返回的完全路径
								}
								var tmp_var = jQuery("#uploaded_photos_box").val()+message+";";
								jQuery("#uploaded_photos_box").val(tmp_var);
								//alert(message);
							}
							
                            break;
                        case "notUpload":
                            jProgress.width("100%");
                            ++doneCount;
                            updateProgress();
                            alert(message);
                            upload();
                            break;
                    }
                }
            }
        }
		
		function submit_photo_data(from_obj){
			var inputObj = jQuery(from_obj).find(':input');
			for(var i=0; i<inputObj.length; i++){
			<?php
			if(strtolower(CHARSET)=='gb2312'){
				echo 'inputObj[i].value = simplized(inputObj[i].value); ';
			}else{
				echo 'inputObj[i].value = traditionalized(inputObj[i].value); ';
			}
			?>
			}
			var From = from_obj;
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('product_reviews_write.php','action=process_photos&products_id='.(int)$products_id)) ?>");
			var form_id = from_obj.id;
			ajax_post_submit(url,form_id);
			
		}

		//提交问题咨询		
		function submit_question_data(from_obj){
		
			var inputObj = jQuery('#'+from_obj).find(':input');			
			for(var i=0; i<inputObj.length; i++){
			<?php
			if(strtolower(CHARSET)=='gb2312'){
				echo 'inputObj[i].value = simplized(inputObj[i].value); ';
			}else{
				echo 'inputObj[i].value = traditionalized(inputObj[i].value); ';
			}
			?>
			}
			var From = from_obj;
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('tour_question_write.php','ajax=true&action=process&aryFormData=1&products_id='.(int)$products_id)) ?>");
			var form_id = from_obj;
			var success_msm="";
			var success_go_to="";
			var replace_id="";
			ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
		}
	</script>
<div class="popup" id="popupUpload_Load">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">
 
  <div class="popupCon" id="popupConUpload" style="width:490px;">
    <div class="popupConTop" id="dragUpload">
      <h3><b><?php echo db_to_html("上传照片");?></b></h3><span onclick="infos=[];closePopup('popupUpload_Load');"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" alt="<?php echo db_to_html("关闭");?>" title="<?php echo db_to_html("关闭");?>" /></span>
    </div>
	
    <div class="photoUploading">
        <ul id="ulPhotoList">
        </ul>
        <ul>
            <li class="count">
                <div class="name"><?php echo db_to_html("总计：");?><b><b id="txtPhotoCount">0</b><?php echo db_to_html("张照片");?></b><a  style="position:absolute;top:4px;"><span id="divAppendPhoto"><?php echo db_to_html("加载中…");?></span></a></div>
                <div class="size"><?php echo db_to_html("总计：");?><b id="txtAllSize">0KB</b></div>
            </li>
        </ul>
        <div id="divSelect" class="btnCenter">
            <a href="javascript:;" class="btn btnOrange" onclick="upload()"><button type="submit"><?php echo db_to_html("上传照片");?></button></a>
        </div>        
        <div id="divProcess" class="allstatus" style="display:none">
            <div class="processBar">
                <div class="barCon" style="width:0%;"></div> 
            </div>
            <span>0%</span>
            <div class="wait"><?php echo db_to_html("正在上传，请稍候...");?></div>
        </div>
        <div id="divDone" class="allstatus" style="display:none">
            <div class="suc"><?php echo db_to_html("上传成功！");?><a href="javascript:void(0)"><?php echo db_to_html("现在就去修改标题和描述");?></a></div>
        </div>

    </div>
  </div>

</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
	
<script type="text/javascript">
new divDrag([GetIdObj('dragUpload'),GetIdObj('popupUpload_Load')]);
</script>
<?php 
//浏览全部问题、评论、相片 -vincent
?>

<script type="text/javascript">

var active_divobj = '';
var active_type = 0;
//点击修改
    jQuery(".conTitle >h2> a").click(function() {
		active_type = 1;
        var idName = jQuery(this).attr("id");
        var tempName = idName.substr(idName.indexOf("_")+1);
		active_divobj = tempName;                
        //jQuery(".conTitle").removeClass("conTitleActive");
        //jQuery(".choosePop").hide();
        //jQuery(".conTitle .close").hide();    
		jQuery(".conTitle").removeClass("conTitleActive");
    	jQuery(".choosePop").hide();
        jQuery(".close").hide();
        jQuery("#ConTitle_"+tempName).addClass("conTitleActive");		
        jQuery("#"+tempName).show();		
        jQuery("#Close_"+tempName).show();  
    });

    //点击关闭
    
    jQuery(".btnGrey button").click(function() {        
        active_type = 0;
    	jQuery(".conTitle").removeClass("conTitleActive");
    	jQuery(".choosePop").hide();
        jQuery(".close").hide();
    });
    jQuery(".btnGrey").click(function() {        
        active_type = 0;
    	jQuery(".conTitle").removeClass("conTitleActive");
    	jQuery(".choosePop").hide();
        jQuery(".close").hide();
    });
    jQuery(".close").click(function() {    	
		active_type = 0;
    	jQuery(".conTitle").removeClass("conTitleActive");
    	jQuery(".choosePop").hide();
        jQuery(".close").hide();
    
    });
    jQuery(".btnOrange button").click(function() {
    	jQuery(".roomClose").hide(); 
		jQuery(".conTitle").removeClass("conTitleActive");
        jQuery(".choosePop").hide();
        jQuery(".conTitle .close").hide();       
    
    });
    jQuery(document).click(function(e){        
		if(active_type == 1 && active_divobj!=""){
			if(!jQuery(e.target).closest("#"+active_divobj).is("div") && !jQuery(e.target).closest("#ConTitleA_"+active_divobj).is("a") && !jQuery(e.target).closest("#TextBox_"+active_divobj).is("div") && !jQuery(e.target).closest("#Close_"+active_divobj).is("div")){
				if(active_divobj == 'ShuttleRoute1_Detail' || active_divobj == 'ShuttleRoute2_Detail') return ;
				if(jQuery("#PopupNoticeCon").css('display')!='none') return  ;//when popAlert active ,dont hide popbox
				jQuery("#"+active_divobj).hide();				
				jQuery("#Close_"+active_divobj).hide();
				active_type = 0;
			}
		}
    });

    
    


//选择时间部分
  jQuery(".timePop p").hover(function() {
	  jQuery(this).addClass("pHover")
  },
  function() {
	  jQuery(this).removeClass("pHover")
  });
  
  jQuery(".timePop p input").click(function() {
	  jQuery(".timePop p").removeClass("pClick");
	  jQuery(this).parent().parent().addClass("pClick")
  });
  
//选择地点部分
  jQuery(".placePop tr:gt(0)").hover(function() {
	  jQuery(this).addClass("trHover")
  },
  function() {
	  jQuery(this).removeClass("trHover")
  });
  
  jQuery(".placePop tr td input").click(function() {
	  jQuery(".placePop tr").removeClass("trClick");
	  jQuery(this).parent().parent().parent().addClass("trClick")
  });
  
  <?php if($is_has_priority_attribute == 1){ ?>
function show_priority_mail_date(val){
	var unique_prod_option_value_id = "<?php echo PRIORITY_MAIL_PRODUCTS_OPTIONS_VALUES_ID; ?>";
	if(val == unique_prod_option_value_id){
		document.getElementById('div_priority_mail_date_field').style.display = '';
	}else{
		document.getElementById('div_priority_mail_date_field').style.display = 'none';
		document.cart_quantity.priority_mail_ticket_needed_date.value = '';
		document.cart_quantity.priority_mail_delivery_address.value = '';
		document.cart_quantity.priority_mail_recipient_name.value = '';
	}
}
function callajaxonprioritydate(){
	sendFormData('cart_quantity', '<?php echo tep_href_link("budget_calculation_ajax.php", "action_calculate_price=true&products_id=" . $products_id); ?>', 'price_ajax_response', 'true');
}
if(document.cart_quantity.elements["id[<?php echo PRIORITY_MAIL_PRODUCTS_OPTIONS_ID; ?>]"].value == <?php echo PRIORITY_MAIL_PRODUCTS_OPTIONS_VALUES_ID; ?>){
	document.getElementById('div_priority_mail_date_field').style.display = '';
}
<?php } ?>

</script>
<?php
/*zip_load 图片压缩上传工具 end*/
?>


<?php //产品收藏夹层{?>

<div class="popup popupCon " id="addToFavorites">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popupCon addSuccess" id="addToFavoritesPanel" style="width:400px; ">
            <div class="successTip">
            	<div class="img"><img src="<?= DIR_WS_TEMPLATE_IMAGES;?>success.jpg"></div>
				<div class="words">
					<p><?php echo db_to_html('行程“');?><a href="" id="Favorites_Pname"></a><?php echo db_to_html('”已经放入收藏夹。');?></p>
					<div id="Favorites_Content"></div>
				</div>
            </div>
			<div class="btnCenter">
				<a href="javascript:void(0);" class="btn btnOrange"><button onclick="window.location.href='<?php echo tep_href_link('my_favorites.php','');?>'" type="button"><?php echo db_to_html('进入收藏夹');?></button></a>
				<a href="javascript:void(0);" class="btn btnGrey" onclick="closePopup('addToFavorites');"><button type="button"><?php echo db_to_html('继续购物');?></button></a>
			</div>
          </div>
      </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</div>

<?php //产品收藏夹层}?>
<?php //恢复预订通知的弹出层{

	?>
<div class="popup" id="popupEmail">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConEmail" style="width:400px;">
    <div class="popupConTop" id="dragEmail">
      <h3><b><?php echo db_to_html('恢复预订通知');?></b></h3><span onclick="closePopup('popupEmail')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif"></a></span>
    </div>
    <div id="popupEmail_Content">
    </div>
  </div>
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<?php  //恢复预订通知的弹出层}?>
<script type="text/javascript" src="/includes/javascript/tips.js"></script>

<?php //购买导航按钮
if((int)$product_info['products_stock_status'] > 0 && count($product_info['operate'])>=1){ 
?>
	<div id="goBuyPanel" title="<?= db_to_html("去购买");?>" onclick="jQuery('html,body').animate({scrollTop:jQuery('#cart_quantity').position().top - 10});"></div>
<?php
}
?>
