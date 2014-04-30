      <div class="pathLinks">
        <?php
			$currPageName='';
			$curr_schtle = '';
			$category_breadcrumb = '';
			$category_breadcrumb_seo = '';
			for ($ib=0, $nb=sizeof($breadcrumb->_trail); $ib<$nb; $ib++) {
				$link_class = '';
				if(($nb-1) != $ib){
					$link_class = ' class="breadlink_gray"';
					$category_breadcrumb .= '&nbsp;<a href="' . $breadcrumb->_trail[$ib]['link'] . '" '.$link_class.'>' . preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']) . '</a>&nbsp;';
					$category_breadcrumb .= '&gt;';
					if($ib > 0){
						$category_breadcrumb_seo .= preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']);
					}
				}else{
					$category_breadcrumb .=  '&nbsp;<span>' . trim(preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title'])) . '</span>';
					$curr_schtle = trim(preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']));
					$currPageName = preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']);
					$category_breadcrumb_seo .= preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']);
				}
			}
			if(html_to_db($currPageName)=="酒店"){
				$currPageName = db_to_html("度假酒店");
			}
			echo '<p>'.$category_breadcrumb.'</p>';
		?>
      </div>

<?php
/*$isCruises 在此页面用于判断邮轮*/
?>
  	<div class="proLeft">
<?php
/*

if($cat_mnu_sel== 'vcpackages')$tabname='vp';
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'destinations.php');//按旅游景点（目的地）查看
?>
<?php
//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'departures.php');//按出发城市查看
//=================Start = =====================================================
//按出发城市查看 start {
if(tep_not_null($top_cate_ids)){
	$left_datanum=ceil(count($categories_more_city)/2);
	$HEADING_DEPARTURE_CITIES = HEADING_DEPARTURE_CITIES; //按出发城市查看
	$dlClass = "route";
	if($isHotels){
		$HEADING_DEPARTURE_CITIES = "";
		$dlClass = "route newNav";
	}
	if($isCruises){
		$HEADING_DEPARTURE_CITIES = db_to_html("按出发港口查看");
		$dlClass = "place placeBot";
	}
?>
<dl class="<?= $dlClass?>">
	<?php if($HEADING_DEPARTURE_CITIES){?>
	<dt><?php echo $HEADING_DEPARTURE_CITIES; ?></dt>
	<?php }?>
<?php if(!$isCruises){?>
<dd>
<?php }?>
<?php
if(!is_array($categories_more_city_link)){
	$_mnu = $cat_mnu_sel;
	if($cat_mnu_sel!="tours" && $cat_mnu_sel!="vcpackages"){
		$_mnu = 'vcpackages';
	}
	$categories_more_city_link=tep_href_link(FILENAME_DEFAULT,'cPath='.preg_replace('/\_.*$/','',$cPath).'&mnu='.$_mnu);
	$categories_more_city_link = explode('?',$categories_more_city_link);
}
foreach($categories_more_city as $key=>$row){
	if($key==$left_datanum && !$isHotels && !$isCruises) { echo '</dd><dd class="right">';}
	$link_class = ($row['city_id'] == $fc)?"selected":"";
	$href = $categories_more_city_link[0].'fc-'.$row['city_id'].'/'.($categories_more_city_link[1]?'?'.$categories_more_city_link[1]:'');
	$cityname= db_to_html(preg_replace('/ .+/','',$row['city']));
	if($isCruises){
		echo '<dd><a href="'.$href.'" class="'.$link_class.'" val="'.$row['city_id'].'">'.$cityname.'</a></dd>';
	}else{
		echo '<a href="'.$href.'" class="'.$link_class.'" val="'.$row['city_id'].'">'.$cityname.'</a>';
	}
}
?>
<?php if(!$isCruises){?>
</dd>
<?php }?>
</dl>
<?php
}
//按出发城市查看 end }
*/
?>
<?php
/*
//秀的广告 start {
if($current_category_id=="32"){?>
<div><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath=32&mnu=show');?>"><img src="image/las_vegas_show_banner.gif" /></a></div>		
<?php 
}
//秀的广告 end }
*/
//=================End = =====================================================
?>

<?php
	//搜索栏
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'search.php');
	ob_start();?>
<div class="newYork_warp border_1  margin_b10">
      <!--纽约旅游-->
      <div class="title_1">
        <h3>{美东}旅游</h3>
      </div>
      <div class="cont">
        <p class="color_blue">
		USITRIP走四方旅游网作为最知名美国华人旅行社,竭诚为您量身定制去{纽约}旅游线路行程,{纽约}旅游景点攻略,{纽约}旅行团自由行,更提供中文签证及{纽约}结伴同游,游学移民,酒店预订,打折机票等旅游服务.最低价格保障,最高商誉评级!尽在---走四方旅游网!
		</p>
      </div>
    </div> 
    
	<?php 
	$_string = ob_get_clean();
	$_replaces = array('{美东}' => html_to_db(strip_tags($category_breadcrumb_seo)), '{纽约}' => preg_replace('/ .+/','',$categories_name_for_seo) );
	$_string = strtr($_string, $_replaces);
	
	echo db_to_html($_string);
	echo $BreadTopLinksHtml;
	//最新专题广告栏
	$banner_name = 'product_list_new_special_topic';
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');
	//海外游学相关知识
	if($cPathOnly == "298"){
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'overseas_study_fqa.php');
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'overseas_study_download.php');
	}
	//小广告栏
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'special.php');//本类特价
	//结伴同游
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_box.php');
	$banner_name = 'product_list_medium_info';
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');
/*	if(!$isHotels && !$isCruises){	//酒店、邮轮列表取消左侧更多出发城市栏
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'departuresall.php');//更多出发城市
	}
*/	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'advantages_product_list.php');//我们的优势
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'contact_us.php');//联系我们
	//小广告栏
	$banner_name='product_list_contact_us_down 250px';
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');

?>
	
<?php


//小广告栏
$banner_name = 'product_list_bottom 265px';
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');
?>
	
	</div>
    <div class="proRight">
<?php //include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'hawaii_advert.php');//夏威夷旅游专用广告?>
<?php //include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'right_topadvert.php');//右栏顶部广告?>
<?php
//今日特惠 start
include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/today_recommended_for_list.php');
//今日特惠 end
if($cPathOnly=="33"){	//夏威夷酒店搜索
	//include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/hawaii_hotel_search.php');	
}
?>
      <ul class="chooseTab">
        <?php
		if($cat_mnu_sel == 'show'){
			$sel_show_style = 'class="selected"';
		}else if($cat_mnu_sel == 'diy'){
			$sel_diy_style = 'class="selected"';
		}else if($cat_mnu_sel == 'introduction'){
			$sel_intoroduction_style = 'class="selected"';
		}else if($cat_mnu_sel == 'introductionCruises'){
			$sel_intoroduction_cruises_style = 'class="selected"';
		}else if($cat_mnu_sel == 'tours'){
			$sel_tours_style = 'class="selected"';
		}else if($cat_mnu_sel == 'vcpackages'){
			$sel_vcpackages_style = 'class="selected"';
		}else if($cat_mnu_sel == 'recommended'){
			$sel_recommended_style = 'class="selected"';
		}else if($cat_mnu_sel == 'special'){
			$sel_special_style = 'class="selected"';
		}else if($cat_mnu_sel == 'maps'){
			$sel_maps_style = 'class="selected"';
		}
		?>
			
		<?php
		$TEXT_TAB_VACATION_PACKAGES = TEXT_TAB_VACATION_PACKAGES;
		if($isCruises){
			$TEXT_TAB_VACATION_PACKAGES = db_to_html('邮轮行程');
		}
	 
		/* 如果是酒店列表则 取消：度假套餐，短期旅行，特价行程，景点介绍分类{ */
		if(!$isHotels){
			if($cPathOnly!="196"){		//特色美国游取消度假套餐Tag?>
            <li id="l_tech2" <?php echo $sel_vcpackages_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=vcpackages');?>" id="h_tech2"><?php echo $TEXT_TAB_VACATION_PACKAGES; ?></a><span></span></li>
        <?php
			}
		}
		
		$TEXT_TAB_TOURS = TEXT_TAB_TOURS;
		if($isHotels){ $TEXT_TAB_TOURS = db_to_html("行程酒店");}
		
		if(!$isCruises){
		?>
            
			<li id="l_study" <?php echo $sel_tours_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=tours');?>" id="h_study"><?php echo $TEXT_TAB_TOURS; ?></a><span></span></li>
		<?php
		}
		?>
		
		<?php
		if($isHotels){
			//$h_tech_href = tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=recommended');
			$h_tech_href = tep_href_link('booking.php'); //连接booking页面
		?>
		<?php /*?><li id="l_tech" <?php echo $sel_recommended_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo $h_tech_href;?>" id="h_tech"><?php echo db_to_html("推荐酒店"); ?></a><span></span></li><?php */?>
		<?php }?>
		<?php if(!$isHotels){ ?>	
			
			<?php if($cPathOnly!="33" && !$isCruises){	//夏威夷取消特价Tag，邮轮也取消?>
			<li id="l_tech" <?php echo $sel_special_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=special');?>" id="h_tech"><?php echo TEXT_TAB_SPECIAL; ?></a><span></span></li>
			<?php }?>
			
			<?php if($category['categories_map'] != ''){?>
			<li id="l_tech1" <?php echo $sel_maps_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=maps');?>" id="h_tech1"><?php echo TEXT_TAB_MAP; ?></a><span></span></li>
			<?php } ?>

			
		<?php } // } ?>
			
      </ul>
<?php
if($cat_mnu_sel=='introduction' || $cat_mnu_sel=='introductionCruises'){
	//景点介绍模块
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'products_introduction_mode.php');
}elseif($cat_mnu_sel == 'maps' && $category['categories_map'] != ''){
	//地图
?>
		<DIV style="width:100%; float:left;">								
			<?php echo db_to_html($category['categories_map']); ?>																							
		</DIV>
<?php
}elseif($cat_mnu_sel == 'diy' && $cPathOnly =='33'){
	//夏威夷自助游
	include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/free_tours.php');
//}elseif($cat_mnu_sel == 'show' && $cPath=='24_32'){
	//拉斯加斯秀
	//include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/vegas_show.php');
}else{
	//右栏顶部筛选模块
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'products_search_mode.php');
	//产品列表
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'products_list_mode.php');
}

//产品列表底部的目录列表
//include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/category_product_list_bottom_category.php');
?>
	</div>