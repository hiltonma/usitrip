<!--header strat-->

 <div id="headTop">
      <div class="logo">
        <h3><a href="<?php echo tep_href_link('index.php') ?>" title="<?=db_to_html('走四方美国旅游网-伴你一起走四方');?>" class="logoLink"><!--<img id="LogoImg" src="<?php echo $logoArray['logo'];?>" alt="<?php echo db_to_html("走四方网"); ?>" title="<?php echo db_to_html("走四方网"); ?>"/>-->走四方网络</a></h3>
        <h4><?php #echo db_to_html('伴您一起<br />走四方...');?></h4>
      </div>
      <?php ob_start() ?>
      <div style="float: left; background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/a_authentication.gif) no-repeat; line-height:19px; margin:35px 0 0 8px; height: 42px; width:204px; ">
	  <?php $top_h1 = ''; if($content=="index_default"){ $top_h1 = '<h1>'; }?>
    <?php echo $top_h1;?>
	<a target="_blank" href="http://www.bbb.org/baton-rouge/business-reviews/travel-agencies-and-bureaus/unitedstars-international-ltd-in-baton-rouge-la-90012303" style=" line-height:18px; display: block; margin-left: 55px;">全球华人首选美国旅游网站<br>美国BBB认证最高商誉评级</a>
	<?php echo str_replace('<','</',$top_h1);?>
</div>
<?php echo db_to_html(ob_get_clean()) ?>
      <div class="search">
<script type="text/javascript">
function checkInputDftVal(objId,t){
	obj = jQuery("#"+objId);
	var val = jQuery.trim(obj.val());
	var defaultstr = obj.attr('title');
	if(!t){
		if(val==''||val==defaultstr){
			obj.addClass('texttip');
			obj.val(defaultstr);
		}
	}else{
		if(val==''||val==defaultstr){
			obj.val('');
		}
		obj.removeClass('texttip');
	}
	if(objId=="top_fcw" && val!="" && val!=defaultstr){
		jQuery("#top_tcw").attr('dataurl',"<?php echo tep_href_link_noseo('ajax_search.php', 'datatype=tcw')?>"+'&val0='+val);
		
	}
}
</script>
      	<form action="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false)?>" name="search" method="get">
        <div class="searchAdvanced" id="searchAdvanced">
          <div class="top1"><b></b><p></p><span></span></div>
          <div class="con">
            <h2><?php echo db_to_html('高级搜索');?></h2>
            <span><a href="javascript:;" onclick="document.getElementById('searchAdvanced').style.display='none' "><img src="<?= DIR_WS_ICONS;?>icon_x.gif" /></a></span>
            <div class="list">
              <b><?php echo db_to_html('出发城市:');?></b>
			  <?php
			  $fcw_class = 'texttip';
			  $fcw_tip = db_to_html('输入出发地');
			  $fcw_val = db_to_html($fcw);
			  if($fcw_val==''){
				    $fcw_val=$fcw_tip;
			  }else{
					$fcw_val!=$fcw_tip && $fcw_class = '';
			  }
			  echo tep_draw_input_field('fcw',$fcw_val , 'id="top_fcw" class="text text1 '.$fcw_class.'" dataurl="'.tep_href_link_noseo('ajax_search.php', 'datatype=fcw').'" title="'.$fcw_tip.'" onfocus="checkInputDftVal(this.id,1)" onBlur="checkInputDftVal(this.id,0)"','text',false);?>

              <b><?php echo db_to_html('目的景点:');?></b>
              <?php 
			  $tcw_class = 'texttip';
			  $tcw_tip = db_to_html('输入目的地');
			  $tcw_val = db_to_html($tcw);
			  $tcw_val=='' && $tcw_val=$tcw_tip;
			  if($tcw_val==''){
				    $tcw_val=$tcw_tip;
			  }else{
					$tcw_val!=$tcw_tip && $tcw_class = '';
			  }
			  echo  tep_draw_input_field('tcw', $tcw_val, 'id="top_tcw" class="text text1 '.$tcw_class.'" dataurl="'.tep_href_link_noseo('ajax_search.php', 'datatype=tcw').'" title="'.$tcw_tip.'" onfocus="checkInputDftVal(this.id,1)" onBlur="checkInputDftVal(this.id,0)"','text',false);?>
              
            </div>
            <div class="list">
              <b><?php echo db_to_html('持续天数:');?></b>
			  <select name="d" id="d1" class="select">
	<?php 
	foreach($_SEARCH_DATA['Days'] as $key=>$_sitem){
		echo '<option '.($_sitem['id']!=$d?'':'selected="selected"').' value="'.$_sitem['id'].'">'.$_sitem['name'].'</option>';
	}
	?>			
			  </select>
              <b><?php echo db_to_html('价格:');?></b><input name="ms" value="<?php echo $ms?>" type="text" class="text text2" /><i><?php echo db_to_html('—');?></i><input name="me" value="<?php echo $me?>" type="text" class="text text2" />

            </div>
            <div class="list">
              <b><?php echo db_to_html('关键词:');?></b>
          		<?php echo tep_draw_input_field('w', db_to_html($w), ' x-webkit-speech class="text keyword autocomplete_input" dataurl="'.tep_href_link_noseo('ajax_search.php', '').'" ','text',false);?>
            </div>
			<div class="list">
			<b><?php echo db_to_html('出发时间:');?></b>
			<?php echo tep_draw_input_num_en_field('dep_date0',$dep_date0, ' class="text_time" style="width:120px;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');?>
			</div>

            <div class="list">
              <input type="submit" class="submit" value=""/>
            </div>
          </div>
          <div class="bot"><b></b><p></p><span></span></div>
        </div>
        <?php echo (isset($language) && $language!=''?'<input type="hidden" name="language" value="'.($language=='schinese'?'sc':'tw').'">':'').(isset($_GET['osCsid']) && $_GET['osCsid']!=''?'<input type="hidden" name="osCsid" value="'.$_GET['osCsid'].'">':'')?>
        </form>

   
        <form name="TopSearchForm" id="TopSearchForm" action="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false)?>">
        <div class="searchLeft" id="searchLeft">
		   <?php
		   $keywords_title = $keywords_text = SEARCH_BOX_TIPS;
		   $style_val = "";
		   if(tep_not_null($w)){$keywords_text=db_to_html($w);}
		   if($keywords_title == $keywords_text){ $style_val =' color:#CCCCCC; '; }
          echo  tep_draw_input_field('w', $keywords_text, ' x-webkit-speech onFocus="Check_Onfocus(this)" title="'.SEARCH_BOX_TIPS.'" class="searchInput" id="searchInput" style="'.$style_val.'" dataurl="'.tep_href_link_noseo('ajax_search.php', '').'"','text',false,true);
		   ?>
		</div>
		<div class="searchRight">
		  <input class="searchSubmit" type="submit" value="" />
          <?php echo (isset($language) && $language!=''?'<input type="hidden" name="language" value="'.($language=='schinese'?'sc':'tw').'">':'').(isset($_GET['osCsid']) && $_GET['osCsid']!=''?'<input type="hidden" name="osCsid" value="'.$_GET['osCsid'].'">':'')?>
		</div>
		</form>
      </div>
      <?php /*
      <div class="advanced"><a href="javascript:;" onclick="document.getElementById('searchAdvanced').style.display='block';"><?php echo db_to_html('高级搜索');?></a></div>
	  */ ?>
      <ul class="servicePhone">
      	<!--<p>7×24<br />热 线</p>-->
        <!--
        <li>1-888-887-2816 <em>(<?php echo db_to_html('美加')?>)</em></li>
        <li>4006-333-926 <em>(<?php echo db_to_html('中国')?>)</em></li>
        -->
        <span>
            <li>1-888-887-2816 <em>(<?php echo db_to_html('美加')?>)</em></li>
            <li>4006-333-926 <em>(<?php echo db_to_html('中国')?>)</em></li>
        </span>
      </ul>
      <?php /*?><ul style="display:block; clear:both; float:right; width:175px;"><li><a target="_blank" rel="nofollow" href="http://www.bbb.org/baton-rouge/business-reviews/travel-agencies-and-bureaus/unitedstars-international-ltd-in-baton-rouge-la-90012303"><?php echo db_to_html("美国信用调查局鉴定为A+级网站");?></a></li></ul><?php */?>
    </div>
 <div class="headMenu">
   <ul>
     <li class="headMenuLeft"></li>
     <li id="dh1"><a href="<?php echo tep_href_link('index.php'); ?>"><span></span><i><?php echo HEADER_TITLE_TOP ?></i><em></em></a></li>
     <li id="dh3"><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=25');?>" ><span></span><i><?php echo db_to_html('美 东')?></i><em></em></a></li>
     <li id="dh4"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=24');?>"  ><span></span><i><?php echo db_to_html('美 西')?></i><em></em></a></li>
     <li id="dh5"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>" ><span></span><i><?php echo db_to_html('夏威夷')?></i><em></em></a></li>
     <?php /*<li id="dh17"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=34');?>" ><span></span><i><?php echo db_to_html('佛罗里达')?></i><em></em></a></li> */?>
     <li id="dh7"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=54');?>" ><span></span><i><?php echo db_to_html('加拿大')?></i><em></em></a></li>
     <li id="dh2"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=157');?>" ><span></span><i><?php echo db_to_html('欧 洲')?></i><em></em></a></li>
     <?php /*<li id="dh14"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=208');?>" ><span></span><i><?php echo db_to_html('拉丁美洲')?></i><em></em></a></li>
     <li id="dh13"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=193_195');?>" ><span></span><i><?php echo db_to_html('日 本')?></i><em></em></a></li>
     <li id="dh19"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=291');?>" ><span></span><i><?php echo db_to_html('中国国内出发')?></i><em></em></a></li>
     <li id="dh18" class="new"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=267');?>" ><span></span><i><?php echo db_to_html('邮轮')?></i><em></em></a><div class="newIcon"></div></li> */?>
     <li id="dh20"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=298');?>" ><span></span><i><?php echo db_to_html('海外游学')?></i><em></em></a></li>
     <li id="dh8"><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=182');?>" ><span></span><i><?php echo db_to_html('酒店预订')?></i><em></em></a></li>
     <li id="dh21"><a href="<?=tep_href_link('visa.php');?>" ><span></span><i><?php echo db_to_html('签证')?></i><em></em></a></li>
     <li id="dh12"><a href="<?=tep_href_link('new_travel_companion_index.php');?>" ><span></span><i><?php echo db_to_html('结伴同游')?></i><em></em></a></li>
     <?php /*<li id="dh16"><a href="<?=tep_href_link('group_buys.php');?>" ><span></span><i><?php echo db_to_html('团购')?></i><em></em></a></li>*/ ?>
     <li id="dh15"><a href="/web_action/show_and_plane/index.html" ><span></span><i><?php echo db_to_html('美国特色游')?></i><em></em></a></li>
     <li class="headMenuRight"></li>
     <?php if(GROUP_BUY_ON==true){?>
	<li class="group">
      <a target="_blank" href="<?php echo tep_href_link('landing-page.php','landingpagename=group_buy') ?>" ><span></span><i><?php echo db_to_html('团体预定')?></i><em></em></a></li>
    <?php
	 }
	?>
   </ul>
   
	 <script type="text/javascript">
     <?php
         //控制菜单标题Class
	/*
	美西默认为洛杉矶24_29
	美东默认为洛杉矶25_55
	加拿大默认为温哥华54_148
	
    */

          if($content == 'group_buys' || $content == 'group_buys_note' || $content == 'group_buys_expires' || (int)$product_info['GroupBuyType']){ /*echo 'document.getElementById("dh16").className="sel new"';*/}
		  elseif($content == 'destination_guide'){ echo 'document.getElementById("dh11").className="sel"';}
          elseif(preg_match('/^157/',$cPath)){ echo 'document.getElementById("dh2").className="sel"'; $cPathOnly = '157';}
          elseif(preg_match('/^196/',$cPath)){ echo 'document.getElementById("dh15").className="sel"'; $cPathOnly = '196';}
          elseif(preg_match('/^208/',$cPath)){ echo 'document.getElementById("dh14").className="sel"'; $cPathOnly = '208';}
          //elseif(preg_match('/^193_195/',$cPath)){ echo 'document.getElementById("dh13").className="sel"'; $cPathOnly = '195';}
          //elseif(preg_match('/^291/',$cPath)){ echo 'document.getElementById("dh19").className="sel"'; $cPathOnly = '291';}
          //elseif(preg_match('/^34/',$cPath) || $cPath=='34'){ echo 'document.getElementById("dh17").className="sel"'; $cPathOnly = '34';}
          elseif(preg_match('/^54/',$cPath) || $cPath=='54'){ echo 'document.getElementById("dh7").className="sel"';  $cPathOnly = '54';}
          elseif(preg_match('/^25/',$cPath)){ echo 'document.getElementById("dh3").className="sel"';  $cPathOnly = '25';}
          elseif(preg_match('/^24/',$cPath)){ echo 'document.getElementById("dh4").className="sel"';  $cPathOnly = '24';}
          elseif(preg_match('/^33/',$cPath)){ echo 'document.getElementById("dh5").className="sel"';  $cPathOnly = '33';}
          elseif(preg_match('/^182/',$cPath)){ echo 'document.getElementById("dh8").className="sel"';  $cPathOnly = '182';}
          elseif(preg_match('/^298/',$cPath)){ echo 'document.getElementById("dh20").className="sel"';  $cPathOnly = '298';}
          elseif(preg_match('/^299/',$cPath)){ echo 'document.getElementById("dh21").className="sel"';  $cPathOnly = '299';}
          elseif(preg_match('/^267/',$cPath)){ echo 'document.getElementById("dh18").className="sel"';  $cPathOnly = '267';}
          
		  //elseif( $content == 'booking'){ echo 'document.getElementById("dh8").className="sel"';}
          elseif( preg_match('/^new_travel_companion/',$content) || $is_travel_companion_bbs==true){ echo 'document.getElementById("dh12").className="sel"';}
          
		  else{  $cPathOnly = '0'; if($content=="index_default"){ echo 'document.getElementById("dh1").className="sel"';} }	
     ?>
     </script>

   
 </div>
 <div class="toolBar">
 
<?php
if($is_travel_companion_bbs!=true && $is_group_buy_page!==true){	//在非结伴同游的页面下才显示目录列表
	//头部菜单子级目录列表 start {
	//$headerCategorys[目录ID] = tours;
	if(!tep_not_null($headerCategorys)){
		$headerCategorys = tep_get_header_categorys($cPathOnly);
	}
	
?>
<ul class="fl sub_menu">
	<strong class="color_orange"><?php echo db_to_html("热门景点：")?></strong>
<?php		
		foreach((array)$headerCategorys as $cID => $mnu){
			$mnu_par = "";
			if(tep_not_null($mnu)){
				$mnu_par = "&mnu=".$mnu;
			}
			$hrefUrl = tep_href_link(FILENAME_DEFAULT, 'cPath='.$cID.$mnu_par);
			echo '<a href="'.$hrefUrl.'">'.db_to_html(tep_get_category_name($cID)).'</a>';	
		}
?>
	</ul>
<?php
	//头部菜单子级目录列表 end }
?>
  
  <?php
  }elseif($is_travel_companion_bbs==true){
	  echo '<dl class="subCategory"><dd><a href="'.tep_href_link('my_travel_companion.php', '', 'NONSSL').'" style="background:url('.DIR_WS_TEMPLATE_IMAGES.'companion.jpg) no-repeat center;color:#fff;display:block;text-align:center;width:105px;font-weight:bold;">'.db_to_html('我的结伴同游').'</a>';
	  /*&nbsp;|&nbsp;<a href="'.tep_href_link('individual_space.php', '', 'NONSSL').'">'.db_to_html('我的个人中心').'</a>*/
	  echo '</dd></dl>';
  }elseif($is_group_buy_page==true){
	  //暂时隐藏
	  //echo '<dl class="subCategory"><dd><a href="'.tep_href_link('group_buys.php', '', 'NONSSL').'">'.db_to_html('今日团购').'</a>&nbsp;|&nbsp;<a href="'.tep_href_link('group_buys.php', 'do=expires', 'NONSSL').'">'.db_to_html('往期团购').'</a></dd></dl>';
  }
  ?>
<?php /*   
   <div class="tool">
     <div class="myCart"><a rel="nofollow" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"><?= HEADER_TITLE_CART_CONTENTS?></a>(<span id="CarSumTop"><?=$cart_sum?></span>)</div>
     <div class="myTours" id="tit"><span><a href="<?= tep_href_link('account.php','', 'SSL');?>"><?= db_to_html("我的走四方");?></a></span>
         <ul class="myAnother" id="my_another_tours" style="display:none">
           <li><a href="<?php echo tep_href_link('new_orders.php', '', 'SSL'); ?>"><?= db_to_html("我的订单");?></a></li>
           <li><a href="<?php echo tep_href_link('my_favorites.php', '', 'SSL'); ?>"><?= db_to_html("我的收藏");?></a></li>
         </ul>
     </div>
     <div class="integral"><?php if((int)$customer_id){?><a rel="nofollow" href="<?=tep_href_link('my_points.php')?>"><?php echo TEXT_MENU_JOIN_REWARDS4FUN?></a><?php }else{?><a rel="nofollow" href="<?=tep_href_link('points.php')?>" target="_blank"><?php echo TEXT_MENU_JOIN_REWARDS4FUN?></a><?php }?></div>
   </div>
*/

//购物车小盒子 start {
if(!in_array($content, array('shopping_cart','login','create_account'))){
?>   
   <div class="tool">
     <div class="myCart"><a rel="nofollow" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"><span><?php echo db_to_html("购物车");?> <strong id="CarSumTop" class="color_blue"><?php echo $cart_sum;?></strong> <?php echo db_to_html("件");?></span></a><em><a rel="nofollow" href="javascript:void(0)"></a></em>
	 <!--购物车弹出-->
	 <div class="menu_shop_pop" style="display:none;">
         <?php
		 foreach($cart->contents as $_pid => $value){
			$_int_pid = tep_get_prid($_pid);
			$_image_src = tep_get_products_image($_pid);
			$_image_src = (stripos($_image_src,'http://') === false ? 'images/':'') . $_image_src;
			/*
			 * 如果启用 https后，由于添加的产品图，有可能是http://开头的地址，所以这里必须针对替换，否则HTTPS会提示警告
			 */
			if ($_SERVER['SERVER_PORT'] == '443') {
				$_image_src = preg_replace('/^http:/i', 'https:', $_image_src);
			}
		 ?>
		 <dl id="cartBoxList<?php echo $_int_pid;?>" class="pro">
			<dt><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_int_pid);?>"><img src="<?= $_image_src;?>" /></a></dt>
			<dd>
			<h4><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_int_pid);?>"><?php echo db_to_html(tep_get_products_name($_int_pid)); ?></a></h4>
			<p><em class="fr"><a href="javascript:void(0);" onclick="ajax_remove_shopping_cart(&quot;<?php echo $_pid;?>&quot;);"><?php echo db_to_html("删除");?></a></em><strong class="color_orange"><?php echo $currencies->format($value['roomattributes'][0]);?></strong></p>
			</dd>
		</dl>
		
		<?php }?>
		<div class="settlement">
		<p><?php echo db_to_html('共<strong id="CarSumTop1">'.$cart_sum."</strong>件商品 金额总计：");?><strong id="cartBoxTotal" class="color_orange"><?php echo $currencies->format($cart->show_total());?></strong></p>
		<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="goSettlement"><?php echo db_to_html("去购物车并结算");?></a>
		</div>
	</div>
	 </div>
	<div class="goSettlement"><a rel="nofollow" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"><?php echo db_to_html("去结算");?></a></div>

<?php if((int)$cart_sum){	//购物车为空时不显示列表{?> 
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(".tool .myCart").hover(function(){
		jQuery(this).children().eq(2).stop(false,true).fadeIn("slow").end();	
	},
	function(){
		jQuery(this).children().eq(2).stop(false,true).fadeOut("slow").end();
	}
	);
});

function ajax_remove_shopping_cart(pid){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('shopping_cart.php','ajax=true&shopping_cart_action=remove')) ?>") + "&products_id=" + pid;
	ajax_get_submit(url);
}
</script>
<?php
//购物车为空时不显示列表}
}
?>


     <?php
	  /*?>
	 <div class="myTours" id="tit"><span><a href="http://www.usitrip.com/account.php">我的走四方</a></span>
         <ul class="myAnother" id="my_another_tours" style="display:none">
           <li><a href="http://www.usitrip.com/new_orders.php">我的订单</a></li>
           <li><a href="http://www.usitrip.com/my_favorites.php">我的收藏</a></li>
         </ul>
     </div>
     <div class="integral"><a rel="nofollow" href="http://www.usitrip.com/points.php" target="_blank">积分奖励</a></div>
	 */?>

   </div>
<?php
}
//购物车小盒子 end }
?>
   <span class="Corner TL"></span> <span class="Corner TR"></span>
 </div>

 <!--header end-->

	<?php
	//导航路径
	$BreadTopLinksHtml = "";
	if(
		$BreadOff != true &&
		$content!='index_default' &&
		$content!='copy-right' &&
		$content!='customer-agreement' &&
		$content!='site-map' &&
		$content!='cancellation-and-refund-policy' &&
		$content!='allprods' &&
		$content!='download_acknowledgement_card_billing' &&
		$content!='guide_for_destination' &&
		$content!='payment-faq' &&
		$content!='shopping_cart' &&
        $content!='all_orders_products' &&
		$content!='checkout_payment' &&
		$content!='password_forgotten' &&
		$content!='checkout_success' &&
		$content!='affiliate_terms' &&
		$content!='checkout_confirmation' &&
		$content!='create_account_success' &&
		$content!='checkout_payment_address' &&
		$content!='join_rewards4fun' &&
		$content!='create_account' &&
		$content!='confirmation_newslleter_email' &&

		$content!='travel_insurance' &&
		$content!='vote_system' &&
		!preg_match('/^checkout\_/',$content)
	){
		$jnb=sizeof($breadcrumb->_trail);
		if((int)$jnb){
			for ($jib=0; $jib<$jnb; $jib++) {
					if(($jnb-1) != $jib){							
						$BreadTopLinksHtml.=  '<a href="' . $breadcrumb->_trail[$jib]['link'] . '" >' . trim($breadcrumb->_trail[$jib]['title']) . '</a> &gt; ';						
					
					}else{
						$BreadTopLinksHtml.=  '<span>' . trim($breadcrumb->_trail[$jib]['title']) . '</span>';						
					
					}
			}				
		}
	}
	//导航路径end
	?>
	
	<?php
	// howard added display global msn
	if ($messageStack->size('global') > 0){
	?>
		<div style="width:950px; margin:auto; padding-top:10px; padding-bottom:10px;"><?php echo $messageStack->output('global'); ?></div>
	<?php
	}
	// howard added display global end
	?>
	
	<!--header end-->
<?php //echo $cPath
	
	ob_start();?>

<!--  热门搜索 -->
   <style type="text/css">
		.search_hot_list{ position:absolute; width:570px; border-style:solid; border-color:#387CDE; border-width:4px 1px 1px; z-index:9999; background:#fff; z-index:9999; display:none; box-shadow:-1px 2px 2px #404040;}
		.search_hot_list h2{ font-size:14px; color:#000; background:#fff7e1; padding:5px 20px; position:relative;}
		.search_hot_list h2 span{ font-size:12px; font-weight:normal; color:#666;}
		.search_hot_list h2 label.close_hot{ display:block; position:absolute;  width:24px; text-align:center; height:24px; line-height:24px; font-size:18px; font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:normal; color:#1c59a3; top:0; right:0; cursor:pointer;}
		.search_hot_list li{ background:#F9FCFF;}
		.search_hot_list li label{ width:95px; display:inline-block; color:#555; font-size:14px; padding:15px 0 0 20px; vertical-align:top; font-weight:bold;}
		.search_hot_list li span{ width:425px; background:#fff; display:inline-block; padding:10px 15px; vertical-align:top; border-bottom:dashed 1px #e7e7e7;}
		.search_hot_list li span a{ color:#1c59a3; padding:1px 3px 2px 3px; margin:0 5px 5px 0; display:inline-block;}
		.search_hot_list li span a:hover{ color:#fff; background:#3983e2; text-decoration:none;}
   </style>
   <div class="search_hot_list">
		<h2>热门搜索<span>（您也可以输入想去的地方进行搜索）</span><label class="close_hot">x</label></h2>
		<ul>
			<li><label>美国东海岸</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=66');?>">纽约</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=71');?>">波士顿</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=104');?>">帝国大厦</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=97');?>">自由女神</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=106');?>">唐人街</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=571');?>">国会大厦</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=83');?>">白宫</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=65');?>">华盛顿</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=80');?>">越战纪念碑</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=85');?>">哈弗大学</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=90');?>">麻省理工学院</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=516');?>">耶鲁大学</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=517');?>">西点军校</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=67');?>">费城</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=93');?>">尼亚加拉瀑布</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=98');?>">华尔街</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=96');?>">康宁玻璃中心</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=78');?>">杰弗逊纪念堂</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=528');?>">赫氏朱古力城</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=615');?>">杜莎夫人蜡像馆</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=518');?>">伍德伯里直销工厂</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=103');?>">第五大道</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=100');?>">洛克菲勒中心</a></span></li>
			<li><label>美国西海岸</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=1');?>">洛杉矶</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=2');?>">旧金山</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=3');?>">拉斯维加斯</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=4');?>">环球影城</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=5');?>">迪士尼</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=23');?>">海洋世界</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=285');?>">好莱坞星光大道</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=288');?>">金门大桥</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=532');?>">斯坦福大学</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=51');?>">优胜美地</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=531');?>">硅谷</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=273');?>">西峡谷玻璃桥</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=8');?>">大峡谷</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=305');?>">胡佛水坝</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=9');?>">黄石公园</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=34');?>">大提顿国家公园</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=30');?>">布莱斯峡谷</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=29');?>">锡安国家公园</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=33');?>">总统巨石</a></span></li>
            <li><label>美国中部</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=477');?>">芝加哥</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=380');?>">新奥尔良</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=539');?>">亚特兰大</a></span></li>
            <li><label>夏威夷</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=162');?>">檀香山</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=163');?>">珍珠港</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=171');?>">小环岛</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=178');?>">茂宜岛</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=192');?>">夏威夷岛</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=510');?>">火山岛</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=174');?>">威基基海滩</a></span></li>
            <li><label>弗罗里达</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=290');?>">迈阿密</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=204');?>">奥兰多</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=598');?>">沼泽公园</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=599');?>">西锁岛</a></span></li>
            <li><label>加拿大西部</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=365');?>">班芙国家公园</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=296');?>">维多利亚</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=545');?>">落基山</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=270');?>">温哥华</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=364');?>">威士拿</a></span></li>
            <li><label>加拿大东部</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=138');?>">魁北克</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=122');?>">千岛湖</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=478');?>">多伦多</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=572');?>">尼亚加拉瀑布</a></span></li>
            <li><label>欧洲</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=331');?>">巴黎</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=416');?>">伦敦</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=325');?>">阿姆斯特丹</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=311');?>">法兰克福</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=319');?>">罗马</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=417');?>">米兰</a></span></li>
            <li><label>拉丁美洲</label><span><a href="<?= tep_href_link('advanced_search_result.php', 'vc=370');?>">墨西哥城</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=371');?>">坎昆</a><a href="<?= tep_href_link('advanced_search_result.php', 'vc=636');?>">瓜达卢佩圣母大教堂</a></span></li>
		</ul>
   </div>
   <script type="text/ecmascript">
   		jQuery(document).ready(function($){
			//$("input#searchInput").val('');
			function setPoint(){
				$('.search_hot_list').css({top:$('.searchInput').offset().top+26,left:$('.searchInput').offset().left});
			}
			setPoint();
			$(window).resize(function(){
				setPoint();
			});
			$('.searchInput').click(function(event){
				var e=window.event || event;
				if(e.stopPropagation){
					e.stopPropagation();
				}else{
					e.cancelBubble = true;
				} 
			});
			$('.searchInput').focus(function(){ 
				if($('.searchInput').val()=='' || $('.searchInput').val()=='请输入出发城市或想去的景点'){
					$('.search_hot_list').show();
				}
			}).keyup(function(){
				if($('.searchInput').val()==''){
					$('.search_hot_list').show();
				}else{
					$('.search_hot_list').hide();
				}
				if($('.recommend1').length == 0){
					$("input#searchInput").autocomplete('', {
						minChars: 1,
						resultsClass: "recommend1 recommend",
						selectFirst: false,
						matchContains: "word",
						autoFill: false,
						max: 10,
						scroll: true,
						scrollHeight: 280,
						inputDefaultsVal: function(row) {
							return row[0] + " (<strong>id: " + row[1] + "</strong>)";
						},
						formatResult:function(row) {
							return row[0].replace(/(<.+?>)/gi, '');
						}
					});
				}
			});
			$('.search_hot_list').click(function(event){
				var e=window.event || event;
				if(e.stopPropagation){
					e.stopPropagation();
				}else{
					e.cancelBubble = true;
				}
			});
			$(document).click(function (event){ $('.search_hot_list').hide(); });
			$('.search_hot_list .close_hot').click(function(){
				$('.search_hot_list').hide();
			});
		});
   </script>
<?php echo  db_to_html(ob_get_clean());?>