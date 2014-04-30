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
						$shows_small_img_list .= '<li><img src="'.$product_info['products_image_med'].'" '.$small_class.' /></li>';
					}
					
					while($extra_images_rows = tep_db_fetch_array($ext_img_exist)){
						$img_n++;
						$url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
						if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
							$url_product_image_name = $extra_images_rows['product_image_name'];
						}
						if($img_n>1){ $a_big_img_style = 'style="display:none"; '; $small_class = '';}else{ $a_big_img_style = ''; $small_class = ' class="on" ';}
						$shows_BigImageString .= '<a id="lightBoxImg_'.$img_n.'" '.$a_big_img_style.' href="'.$url_product_image_name.'" title="'.addslashes(db_to_html($product_info['products_name'])).'"><img src="'. $url_product_image_name.'" /></a>';
						$shows_small_img_list .= '<li><img src="'.$url_product_image_name.'" '.$small_class.' name="'.$img_n.'"/></li>';
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
					$shows_BigImageString .= '<a id="lightBoxImg_1" title="'.addslashes(db_to_html($product_info['products_name'])).'" href="'.$new_image.'"><img src="'.$new_image.'" /></a>';
					$shows_small_img_list .= '<li><img src="'.$new_image.'" class="on" name="1"/></li>';
				}
				?>
				
				<?php
						 //显示特价标签，按买2送2、买2送1、双人特价、普通特价的优先次序处理
						 $specials_num = 0;
						 $special_str = '';
						 $is_buy2get2 = check_buy_two_get_one($product_info['products_id'],'4');
						 $is_buy2get1 = check_buy_two_get_one($product_info['products_id'],'3');
						 $is_double_special = double_room_preferences($product_info['products_id']);
						 $is_special = check_is_specials($product_info['products_id'],true,true);
						 //tour_type_icon
						 $tour_type_icon_sql = tep_db_query("select tour_type_icon from " . TABLE_PRODUCTS . " where products_id= '".$product_info['products_id']."' ");
						 $tour_type_icon_row = tep_db_fetch_array($tour_type_icon_sql);
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
	$h4_contents = db_to_html('<b>请先登录</b>');
	$con_contents.= tep_draw_form($popupTip.'_form','','post', ' id="'.$popupTip.'_form" ');	
	$con_contents.= '<div class="login">
        <ul>
            <li><label>'.db_to_html('电子邮箱:').'</label>'.tep_draw_input_field('email_address','','class="required validate-email text username" title="'.db_to_html('请输入您的电子邮箱').'"').'</li>
            <li><label>'.db_to_html('密码:').'</label><input name="password" type="password" class="required text password" title="'.db_to_html('请输入正确的密码').'" /></li>
            <li><label>&nbsp;</label><input type="submit" class="loginBtn" value="'.db_to_html('&nbsp;登&nbsp;录').' "></li>
            <li><label>&nbsp;</label><a href="'.tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL').'">'.db_to_html('忘记密码?').'</a>'.db_to_html(sprintf('&nbsp;&nbsp;新用户请&nbsp;<a href="%s">注册</a>',tep_href_link("create_account.php","", "SSL"))).'</li>
        </ul>
    </div>';
	$con_contents .= '</form>';
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
	  
	  ?>	 
		
		<div class="proDetailRight">
<?php
//right column 左栏目
include('product_info_module_right_2011_for_vegas_show.php');
?>
</div>

		
		<?php
		//howard added 加入购物车窗口 start
		$add_cart_msn = "add_cart_msn";
		$add_cart_msn_con_id = "add_cart_msn_con";
		$add_cart_msn_h4_contents = db_to_html("该团已成功添加到购物车！");
		$add_cart_msn_contents = '
			<table style="float:left" cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr><td height="25" align="center" ><p style="font-weight:normal;">'.db_to_html('购物车共 <span class="tell_f_a"  style="text-decoration:none">[Cart_Sum] </span>个团').'&nbsp;&nbsp;&nbsp;&nbsp;'.db_to_html('合计：<SPAN class="tell_f_a" style="text-decoration:none">[Cart_Total]</SPAN>').'</P></td></tr>
					<tr><td height="25" align="center"><a href="' . tep_href_link('shopping_cart.php') . '">' . tep_template_image_button('go-to-basket.gif', db_to_html('查看购物车或结账')) . '</a>&nbsp;<a href="javascript:closePopup(&quot;'.$add_cart_msn.'&quot;)">'.tep_template_image_button('button_continue_shopping.gif', db_to_html('继续购物')).'</a></td>
					</tr>
				</table>
		';
		$PopupObj[] = tep_popup($add_cart_msn, $add_cart_msn_con_id, "460", $add_cart_msn_h4_contents, $add_cart_msn_contents );
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
	
		<div class="proDetailLeft">
		<div class="title titleDetailTop"><b></b><span></span></div>
		<div class="proDes">			
			<div class="topTitle">
				  <h1>
				  <?php  if($_GET['seeAll']){?> 
				  <span><?php echo db_to_html(sprintf('有<span id="view_all_counter1">%d</span>位游客在',$num)) ?> </span>&quot;<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn')));?>"><?php echo db_to_html($products_name); ?></a> &quot;<span id="view_all_title" ><?php echo $text ?></span>
				  <?php }
				  else	  echo db_to_html($products_name); 
				  ?></h1>
				  <h2><?php echo db_to_html($products_name1); ?></h2>
				  <?php
				  include(DIR_FS_MODULES.'product_info/share_to_friend.php');
				  ?>
				</div>
				
				<?php
				if((int)$product_info['GroupBuyType']){
				?>
				<div id="groupBuyDiv" class="groupBuy">
					<div class="groupTag"></div>
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
          <!--照片,map和评论统计 start-->
		 <div class="routeInfo">
            <ul>
              <li><a  name="anchor2"   href="javascript:void(0);" onClick="setProductTab('two',2,4); scroll(0,jQuery('#two2').offset().top);updateTitle('question');" ><?php echo sprintf(db_to_html('问题咨询(%d)'), get_product_question_num($product_info['products_id']));?></a></li>
                <li><a href="<?=tep_href_link('new_travel_companion_index.php');?>" ><?php echo sprintf(db_to_html('结伴同游(%d)'), get_product_companion_post_num($product_info['products_id']));?></a></li>
              <li><a href="javascript:void(0);" onClick="setProductTab('two',3,4); scroll(0,jQuery('#two3').offset().top); lazyload({defObj: '#reviews_photos_ul'});updateTitle('photo');" ><?php echo sprintf(db_to_html('照片分享(%d)'), get_traveler_photos_num($product_info['products_id']));?></a></li>
			  <li class="comment"><a href="javascript:void(0);" onClick="setProductTab('two',1,4); scroll(0,jQuery('#two1').offset().top);updateTitle('review');" ><?php echo sprintf(db_to_html('用户评论(%d)'), get_product_reviews_num($product_info['products_id']));?></a> 
		</li> </ul>
          </div>
          <script type="text/javascript">
			jQuery(document).ready(function(){
				if(jQuery("#comment_bai_fen_bi_h2").html()!=null){
					jQuery("#comment_bai_fen_bi").html(jQuery("#comment_bai_fen_bi_h2").html());
				}
			});
			</script>
			  <?php
				if($product_info['products_map'] != ''){	
				?>
				<?php
					$new_image_map = $product_info['products_map'];
					echo '<li><a href="'.DIR_WS_IMAGES . $new_image_map.'"  rel="lightbox" target="_blank">'.db_to_html("行程地图").'</a></li>';
				}
				?>
		  <!--照片,map和评论统计 end-->
        </div>
				<!--团基本资料 start-->
				<ul class="mid">
				  <li>
					<label><?php echo TEXT_TOUR_CODE;?></label>
					<p><?php echo $product_info['products_model'];?></p>
					<label><?php echo db_to_html('城市');?></label>
					<p>
<?php 
if($product_info['departure_city_id'] == ''){
$product_info['departure_city_id'] = 0;
}
$city_class_departure_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_city_id'] . ") order by c.city ");
while($city_class_departure_at = tep_db_fetch_array($city_class_departure_at_query)) {
	echo  db_to_html($city_class_departure_at['city']).', '.$city_class_departure_at['zone_code'].', '.$city_class_departure_at['countries_iso_code_3'].'<br />';
}																					
?>
					</p>
				  </li>
				<li>
				  <label><?php echo db_to_html('表演场地'); ?></label>
				  <p>
				<?php
				$hotel_sql = tep_db_query('SELECT products_hotel_id FROM `products_show` WHERE products_id="'.$product_info['products_id'].'" Limit 1');
				$hotel_row = tep_db_fetch_array($hotel_sql);
				echo db_to_html(tep_get_products_name($hotel_row['products_hotel_id']));
				?>
				</p>
				</li>
				<li>
				  <label><?php echo db_to_html('表演日期'); ?></label>
				  <p id="operate_info">
				<?php 
					$operate_info = tep_get_display_operate_info($product_info['products_id']);		
					if(strlen($operate_info) > 40){ ?>
					<?php 	echo cutword($operate_info , 50,""); ?>
					<span class="more" onmouseover="jQuery('#MoreCon').show();" onmouseout="jQuery('#MoreCon').hide();"> 
						<a href="javascript:;" ><?php echo db_to_html('查看全部');?></a> 
						<span id="MoreCon"> 
							<span class="topArrow"></span><span class="con"><?php echo $operate_info;?></span><span id="tipBg"></span> 
						</span> 
					</span> 
				<?php }else{ echo $operate_info; } ?>	
				</p>
				</li>

				  <li>
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
				  <li>
					<label><?php echo HEDING_TEXT_POINTS_MSN?></label>
					<p>
					<?php
					   // Points/Rewards system V2.1rc2a BOF
						if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) {
							if ($new_price = 
					
					tep_get_products_special_price($product_info['products_id'])) {
								$products_price_points = tep_display_points($new_price, 
					
					tep_get_tax_rate($product_info['products_tax_class_id']));
							} else {
								$products_price_points = 
					
					tep_display_points($product_info['products_price'], 
					
					tep_get_tax_rate($product_info['products_tax_class_id']));
							}
							$products_points = tep_calc_products_price_points($products_price_points);
							$products_points = get_n_multiple_points($products_points , $product_info['products_id']);
							
							//$products_points_value = tep_calc_price_pvalue($products_points);
							if ((USE_POINTS_FOR_SPECIALS == 'true') || $new_price == false) {
								echo sprintf(TEXT_PRODUCT_POINTS , number_format($products_points,POINTS_DECIMAL_PLACES)) ;
							}
						}
					// Points/Rewards system V2.1rc2a EOF
					?>
					</p>
				  </li>
				</ul>
				<!--团价格 start-->
				<div class="right">
				  <?php echo $products_price; ?>
				<?php
					if($product_info['upgrade_to_product_id']!=''){
						echo '<div class="recommended"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$product_info['upgrade_to_product_id']).'">'.tep_template_image_button('recommended.gif', '', '', '', 'align="absmiddle" ').'</a></div>';
					}
					?>
		  			<?php 
					//优惠标签显示
					?>
					
					
					 <?php 
				//计算标签
					$tags = array();
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
					</ul>		
					
					</div></div>
					<?php
					//优惠标签显示 end
					?>
				
				<h3>
				<?php echo db_to_html('SHOW介绍');?>
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
				</h3>
        		<div class="mainPoint">
				 <?php
				  /**
				   * 检查products_small_description 的字符串长度如果超出规定的长度 只显示前部分后部分隐藏
				   * 如果长度未超出则不进行截取
				   * @var $displyNum 显示的前段字符串长度
				   */
				  $products_small_description = $product_info['products_small_description'];
				  $displyNum = 280 ;
				  if(strlen($products_small_description) > $displyNum ) {
				  		 $products_small_description_first = cutword($products_small_description , 300,"");
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
				<!--团基本资料 end-->
				
				<?php
				//购买模块
				include('product_info_module_right_1.php');
				?>

			</div>
			<div class="title titleDetailBot"><b></b><span></span></div>
			<?php
			//产品行程信息多子菜单
			include('product_info_module2_2011.php');
			?>
		</div>
		
		<div class="guid" id="Guid">
		<h4><?php echo db_to_html('预订指南')?></h4>
		<p><a class="step1" href="<?= tep_href_link('tours-faq.php','information_id=42')?>#A"></a><a class="step2" href="<?= tep_href_link('tours-faq.php','information_id=42')?>#B"></a><a href="<?= tep_href_link('tours-faq.php','information_id=42')?>#C" class="step3"></a><a href="<?= tep_href_link('tours-faq.php','information_id=42')?>#D" class="step4"></a></p>
	  </div>
  </div>

<div id="QuickLinks" class=" quickLinks quickLinksRight">
    <div class="quickLinksCon" id="QuickLinksCon">
    <div class="left"></div>
    <div class="con">
        <span class="page">
            <a href="javascript:scroll(0,0)" ><?php echo db_to_html('返回顶部')?></a>
        </span>
        <?php if(!$_GET['seeAll']){?>
        <span>       
            <a onclick="setProductTab('one',1,5)" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor1"><?php echo db_to_html('行程介绍')?></a>
            <a onclick="setProductTab('one',2,5)" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor1" ><?php echo db_to_html('价格明细')?></a>
            <a onclick="setProductTab('one',3,5)" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor1"><?php echo db_to_html('出发时间/地点')?></a>
            <a onclick="setProductTab('one',4,5)" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor1" ><?php echo db_to_html('注意事项')?></a>

            <a onclick="setProductTab('one',5,5);" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor1" ><?php echo db_to_html('常见问题')?></a>
        </span>
        <?php }else{?>
        <span>
            <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn')));?>"> <?php echo db_to_html('&lt;&lt;返回产品详情')?></a>
        </span>
        <?php }?>
        <span>
            <a onclick="setProductTab('two',2,4)"  href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor2" ><?php echo db_to_html('问题咨询')?></a>
            <a onclick="setProductTab('two',1,4)" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor2"><?php echo db_to_html('游客评论')?></a>
            <a onclick="setProductTab('two',3,4); lazyload({defObj: '#reviews_photos_ul'});" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>#anchor2"><?php echo db_to_html('照片分享')?></a>
        </span>
    </div>
    <div class="close"><a href="javascript:;" onclick="jQuery('#QuickLinksCon').hide();"></a></div>
    </div>
</div>

<script type="text/javascript">
    <!--

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
    var textAdults="<?php echo TEXT_ADULTS; ?>";
    var textChildren="<?php echo TEXT_CHILDREN; ?>";				 
    var textRoomX="<?php echo TEXT_ROOMS; ?> ?:";
    var textChildX="<?php echo TEXT_CHILDREN; ?> ?:";

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
	$h6_str = "请选择购买人数";
}
?>
        var x = '';//'<h6><b><?php echo db_to_html($h6_str);?></b><span onclick="jQuery(&quot;#hot-search-params&quot;).hide();"><img src="image/icons/icon_x.gif"></span></h6>';
        if (adultHelp.length > 0) {
            x += adultHelp + "<p>\n";
        }
		
        if (numRooms > 17) {
            x += textRooms;
            x += renderRoomSelect();

        } else {
            x += '<table cellspacing="0" cellpadding="0" width="400" class="roomPopTable" >\n';
<?php
if ($product_info['display_room_option'] == 1) {
	$colspan = 4;
	//if ($product_info['agency_id'] == "2") { $colspan = 4; }
?>
            x += '<thead><tr><td colspan="<?=$colspan?>"><span>'+textRooms+pad+'</span>'+renderRoomSelect()+'</td></tr></thead>';	//蓝色标标行和房间总数选择菜单
<?php
}
?>
			x += '<tr>';
            //if (numRooms > 1 && numRooms < 16 ) {
                x += '<td>&nbsp;</td>';
            //}

            var title_bed_td = '';
<?php
//海鸥团：增加选择床型这些选项。
if ($product_info['agency_id'] == "2") {
?>
                var options_array = new Array();    
                options_array[0] = new Array(0,'<?php echo TEXT_BED_STANDARD; ?>');
                options_array[1] = new Array(1,'<?php echo TEXT_BED_KING; ?>');
                options_array[2] = new Array(2,'<?php echo TEXT_BED_QUEEN; ?>');
                title_bed_td = '<td><label>'+ '<?= db_to_html('床型'); ?>' +'</label></td>';
    
<?php
}
?>


            x += '<td><label>'+textAdults+pad+'</label></td><td><label>'+textChildren+pad+'</label></td>'+ title_bed_td +'</tr>\n';
            for (var i = 0; i < numRooms; i++) {

                /*去除左边的那个空白列
				x += '<tr><td>';
                    x += '&nbsp;';
                x += '</td>';
                */
				//if (numRooms > 1 && numRooms < 16 ) {
                    RoomLeftTitle = getValueChinese(textRoomX, i+1)+pad;
					if(numRooms<=1){ RoomLeftTitle = ""; }
					if(numRooms==16){ RoomLeftTitle = "<?= db_to_html("结伴拼房")?>"; }
					x += '<td class="left" id="room-' + i + '-left-title"><nobr>&nbsp;'+ RoomLeftTitle + '</nobr></td>';
                //}
                x += '<td width="70">';
               <?php                 
                if ($content == 'product_info_vegas_show'){
                ?>
                x += buildSelect('room-' + i + '-adult-total', 'setNumAdults(' + i + ', this.options[this.selectedIndex].value)', 1, 20, 1);
                x += '</td><td width="70">';
                x += buildSelect('room-' + i + '-child-total', 'setNumChildren(' + i + ', this.options[this.selectedIndex].value)', 0, 10, 0);
				<?php
                }else{
                ?>
                x += buildSelect('room-' + i + '-adult-total', 'setNumAdults(' + i + ', this.options[this.selectedIndex].value)', 1, 20, adultsPerRoom[0]);
                x += '</td><td width="70">';
                x += buildSelect('room-' + i + '-child-total', 'setNumChildren(' + i + ', this.options[this.selectedIndex].value)', 0, 10, childrenPerRoom[0]);
                <?php
                }
                ?>//setNumChildren(' + i + ', this.options[this.selectedIndex].value)

                x += '</td>';

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
                    x += '<td width="160">&nbsp;</td>';
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
			x += '<div class="submit btnCenter"><a class="btn btnOrange" href="javascript:;" onClick="SetShowSteps3();"><button type="button"><?= db_to_html("确 定");?></button></a></div>';	//确定按钮
			

            /*var didHeader = false;
            if (didHeader) {
                x += '</table>\n';
            }*/
        }
		
        document.getElementById("hot-search-params").innerHTML = x;
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

<script type="text/javascript">
var active_divobj = '';
var active_type = 0;
//点击修改
    jQuery(".conTitle a").click(function() {
		active_type = 1;
        var idName = jQuery(this).attr("id");
        var tempName = idName.substr(idName.indexOf("_")+1);
		active_divobj = tempName;                
        //jQuery(".conTitle").removeClass("conTitleActive");
        //jQuery(".choosePop").hide();
        //jQuery(".conTitle .close").hide();            
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
</script>
<script type="text/javascript">
function validate(){					
	//show检查演出时间
	var TmpShowTime = document.getElementById("tmp_show_time");
	var dateObj = document.cart_quantity.availabletourdate;
	/*if(dateObj.value==""){
		alert("<?php echo db_to_html("请选择 计划出发时间");?>")
		return false;
	}else if(TmpShowTime!=null){
		TmpShowTime.value = dateObj.value;
		if(TmpShowTime.value=="<?= db_to_html("选择演出时间")?>" || TmpShowTime.value==""){
			alert("<?php echo db_to_html("请选择 选择演出时间");?>")
			return false;
		}
	}*/
	return true;
}
//清除演出时间输入框的值
function ClearTmpShowTime(){
	var TmpShowTime = document.getElementById("tmp_show_time");
	if(TmpShowTime!=null){
		TmpShowTime.value="<?= db_to_html("选择演出时间")?>";
	}
}
</script>
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
						 //alert(response1);
						 document.getElementById("price_ajax_response").innerHTML = response1;
						 
						}
					}
					

//隐藏成人，小孩等字眼信息
function hidden_kids_info(){
	var hot_search_params = document.getElementById('hot-search-params');
	if(hot_search_params!=null){
		var tr = hot_search_params.getElementsByTagName('tr');
		if(tr.length==2){
			rm_tr = tr[0];
			rm_tr.parentNode.removeChild(rm_tr);
		}
		var select_ = hot_search_params.getElementsByTagName('select');
		for(i=0; i<select_.length; i++){
			if(select_[i].name =="room-0-child-total"){
				select_[i].style.display = "none";
			}
		}
	}
}
hidden_kids_info();

</script>

<?php
//自动添加产品点击量
$index_type = 'click';
auto_add_product_index($product_info['products_id'],$index_type );
//自动添加产品点击量end
?>

<?php
}
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
                imgQuality: 85
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
                
				jQuery("div a", jDone).click(function() {
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
//剔除被隐藏的菜单
?>
<?php
/*zip_load 图片压缩上传工具 end*/
?>


