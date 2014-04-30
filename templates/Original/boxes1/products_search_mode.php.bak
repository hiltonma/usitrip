<?php
$resetSearchOptionUrl = tep_href_link(FILENAME_DEFAULT, 'cPath='.$cPath.'&mnu='.$cat_mnu_sel);//$pageurl.'?'.$pagequery;
if($showSearchOptionUL){

// 如无任何筛选选项则根据NowCookie的值来判断是否显示筛选项{ 
//if(!(int)$fc && !(int)$tc && !(int)$vc && !(int)$d && !(int)$m && !(int)$of ){
?>
<script type="text/javascript">
jQuery().ready(function() {
	var NowCookie = getCookie("<?php echo $ajaxTypename;?>_option");
	if(NowCookie=="hide"){
		var _search_option=jQuery('#'+'<?php echo $ajaxTypename;?>'+'_option');
		var a = jQuery('#'+'<?php echo $ajaxTypename;?>'+'_option_btnshow');
		_search_option.attr('isHidden','true');
		_search_option.hide();
		a.addClass('hidden');
		jQuery("#MoreOptsA").hide();
	}
});
</script>
<?php
//}
// 如无任何筛选选项则根据NowCookie的值来判断是否显示筛选项 }
?>
<div class="chooseCon">

<?php if(tep_not_null($_SEARCH_DATA['Via_City'])){ //途经景点?>
<div class="city" id="<?php echo $ajaxTypename;?>_option_item" mod="vc">
	<h4><strong><?php echo db_to_html('想去哪儿：')#$currPageName; ?></strong></h4>
	<ul>
		<?php 
		$_SEARCH_DATA['Via_City'][0] = array('id'=>'','name'=>db_to_html('全部'));
	  	ksort($_SEARCH_DATA['Via_City']);
	  	$vc_dftnum=6;
	  	$show_vctep=0;
		$selected_ViaCity = $currPageName;
      foreach($_SEARCH_DATA['Via_City'] as $_sitem){
		  $vc && $_sitem['id']==$vc && $_schSlt['vc'] = $_sitem;
		  if(!$svc && $show_vctep==$vc_dftnum && $vc && !is_array($_schSlt['vc']) && $_SEARCH_DATA['Via_City'][$vc]){
			  $_sitem = $_SEARCH_DATA['Via_City'][$vc];
			  $_schSlt['vc'] = $_sitem;
		  }
          $href = makesearchUrl('vc',$_sitem['id']);
          
		  if($_sitem['id']==$vc){ $selected_ViaCity = $_sitem['name']; }
		  echo '<li class="'.($_sitem['id']!=$vc?'':'cur').'"><a href="'.$href.'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a></li>';
		  
		  if(!$svc && $show_vctep>=$vc_dftnum)break;
		  $show_vctep++;
      }
	  unset($show_vctep);
	  if(!$vc){
		  if($svc){
			  echo '<li><a href="'.makesearchUrl('svc','').'" class="more" val="" mod="svc">'.db_to_html("&lt;&lt;隐藏").'</a></li>';
		  }elseif($vc_dftnum < (sizeof($_SEARCH_DATA['Via_City'])-1) ){
			  echo '<li><a href="'.makesearchUrl('svc',1).'" class="more" val="1" mod="svc">'.db_to_html("查看更多&gt;&gt;").'</a></li>';
		  }
	  }
	  ?>
		<!--
		<li><a href="#">全部</a></li>
		<li class="cur"><a href="#">纽约</a></li>
		<li><a href="#">华盛顿</a></li>
		<li><a href="#">巴尔的摩</a></li>
		<li><a href="#">费城</a></li>
		<li><a href="#">波士顿</a></li>
		<li><a href="#">罗德岛</a></li>
		<li><a href="#">芝加哥</a></li>
		<li><a href="#">自由女神</a></li>
		<li><a href="#">耶鲁</a></li>
		<li><a href="#">赫氏古堡</a></li>
		<li><a href="#">凤凰城</a></li>
		<li><a href="#">哈佛</a></li>
		<li><a href="#">西点军校</a></li>
		<li><a href="#">奥兰多</a></li>
		<li><a href="#">伍德佰里直销</a></li>
		<li><a href="#">康宁玻璃中心</a></li>
		<li><a href="#">赫氏朱古力城</a></li>
		-->
		
	</ul>
    <div class="del_float"></div>
</div>
  <h2>
	<b <?php /* onclick="jQuery_ChangeOption('<?php echo $ajaxTypename;?>');"*/ ?> id="<?php echo $ajaxTypename;?>_option_btnshow"><?php echo db_to_html('您选择了') . '<span>' . $selected_ViaCity . '</span>'; ?> <?php echo db_to_html('－')?> <?php echo db_to_html('继续筛选：')?></b>
	<span><a href="<?php echo $resetSearchOptionUrl;?>"><?php echo db_to_html('重置筛选条件')?></a></span><em></em>
  </h2>
<?php }?>

  <ul id="<?php echo $ajaxTypename;?>_option">
<?php
if(isset($ajax) && $ajax=='true' && $openAjaxUrl){
	ob_end_clean();
	include(DIR_FS_INCLUDES . 'application_top_gzip.php');
}
?>
<?php
if($isCruises){	//邮轮需要添加“邮轮名称”的筛选项
	
?>

<li id="<?php echo $ajaxTypename;?>_option_item" mod="ic">
  <b><?php echo db_to_html("邮轮名称：")?></b>
  <div>
	  <?php
		$_SEARCH_DATA['Cruises'][0] = array('id'=>'','name'=>db_to_html('不限'));
		ksort($_SEARCH_DATA['Cruises']);
		foreach($_SEARCH_DATA['Cruises'] as $_sitem){
		  $ic && $_sitem['id']==$ic && $_schSlt['ic'] = $_sitem;
		  $href = makesearchUrl('ic',$_sitem['id']);
		  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$ic?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		}
	  ?>
  </div>
</li>
<?php } ?>

<?php
if($lasVegas){
	if(count($_SEARCH_DATA['SHotel'])){
	?>
    <li id="<?php echo $ajaxTypename;?>_option_item" mod="shid">
	  <b><?php echo db_to_html('表演场地：')?></b>
	  <div>
	  <?php
      $_SEARCH_DATA['SHotel'][0] = array('id'=>'','name'=>db_to_html('不限'));
	  ksort($_SEARCH_DATA['SHotel']);
	  $shid_dftnum=7;
	  $show_shidtep=0;
      foreach($_SEARCH_DATA['SHotel'] as $_sitem){
		  $shid && $_sitem['id']==$shid && $_schSlt['shid'] = $_sitem;
		  if(!$ssh && $show_shidtep==$shid_dftnum && $shid && !is_array($_schSlt['shid']) && $_SEARCH_DATA['SHotel'][$shid]){
			  $_sitem = $_SEARCH_DATA['SHotel'][$shid];
			  $_schSlt['shid'] = $_sitem;
		  }
		  $_sitem['name'] = explode('/',$_sitem['name']);
		  $_sitem['name'] = $_sitem['name'][0];
          $href = makesearchUrl('shid',$_sitem['id']);
          echo '<a href="'.$href.'" class="'.($_sitem['id']!=$shid?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  if(!$ssh && $show_shidtep>=$shid_dftnum)break;
		  $show_shidtep++;
      }
	  unset($show_shidtep);
	  if($ssh){
		  echo '<a href="'.makesearchUrl('ssh','').'" class="more" val="" mod="ssh">'.db_to_html("&lt;&lt;隐藏").'</a>';
	  }else{
		  echo '<a href="'.makesearchUrl('ssh',1).'" class="more" val="1" mod="ssh">'.db_to_html("查看更多&gt;&gt;").'</a>';
	  }
      ?>
	  </div>
	</li>
<?php }?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="m" class="moreOpt">
	  <b><?php echo db_to_html('价　　格：')?></b>
	  <div>
	  <?php
	  $m && $_schSlt['m'] = $_SEARCH_DATA['Price'][$m];
	  foreach($_SEARCH_DATA['Price'] as $key=>$_sitem){
		  $href = makesearchUrl('m',$_sitem['id']);
		  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$m?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
	  }
	  ?>
	   </div>
	</li>
    <!--<li id="<?php echo $ajaxTypename;?>_option_item" mod="w">
	  <b><?php echo db_to_html('搜　　索：')?></b>
	  <div>
      <?php
	  echo tep_draw_input_field('w',$w,' class="input_search" type="text" val="'.$w.'" onBlur="jQuery_Change_Word(this);"');
	  ?>
	  </div>
	</li>-->
<?php
}else{
	
	//if(!$isHotels){	//非酒店才能显示出发城市(Gavin的要求去掉)、结束城市 2012-4-17 以前的注释  // 按sofia的意思 显示所在城市 by lwkai 2012-04-17 18:34 注释掉了这行
	//出发城市
		//if($current_category_id!=$cPathOnly){//以出发城市查看时不显示  2012-4-17 以前的注释  // 按sofia的意思 显示所在城市 by lwkai 2012-04-17 18:34  注释了这行
			if(count($_SEARCH_DATA['From_City'])){
			//如果是酒店列表，将出发城市及结束城市筛选项合并更新为“所在城市”。
				$departureCityTitle = "出发城市：";
				if($isHotels){
					$departureCityTitle = "所在城市：";
				}
				if($isCruises){
					$departureCityTitle = "出发港口：";
				}
	?>
	
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="fc">
	  <b><?php echo db_to_html($departureCityTitle)?></b>
	  <div>
		  <?php
		  	$_SEARCH_DATA['From_City'][0] = array('id'=>'','name'=>db_to_html('不限'));
	      	ksort($_SEARCH_DATA['From_City']);
		 	foreach($_SEARCH_DATA['From_City'] as $_sitem){
			  $fc && $_sitem['id']==$fc && $_schSlt['fc'] = $_sitem;
			  $href = makesearchUrl('fc',$_sitem['id']);
			  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$fc?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  	}
			
		  ?>
	  </div>
	</li>
	
	<?php }
	/*}else{   // 按sofia的意思 显示所在城市 by lwkai 2012-04-17 18:34  当时的注释
			$_schSlt['fc'] = $_SEARCH_DATA['From_City'][$fc];
		}*/    
	
	// 按sofia的意思 显示所在城市 by lwkai 2012-04-17 18:34  这里加了  && !$isHotels
		if(count($_SEARCH_DATA['To_City']) && !$isCruises && !$isHotels){
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="tc">
	  <b><?php echo db_to_html('结束城市：')?></b>
	  <div>
		  <?php
		  $_SEARCH_DATA['To_City'][0] = array('id'=>'','name'=>db_to_html('不限'));
	      ksort($_SEARCH_DATA['To_City']);
		  foreach($_SEARCH_DATA['To_City'] as $_sitem){
			  $tc && $_sitem['id']==$tc && $_schSlt['tc'] = $_sitem;
			  $href = makesearchUrl('tc',$_sitem['id']);
			  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$tc?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  }
		  ?>
	  </div>
	</li>
	<?php }
	//}
	// 按sofia要求 先不显示酒店的筛选 by lwkai 2012-04-17 18:34
	$hotelsShow = false;
	if($isHotels && $hotelsShow){	//酒店{
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="hs">
	  <b><?php echo db_to_html('酒店级别：')?></b>
	  <div>
		  <?php
	      ksort($_SEARCH_DATA['Hotel_Stars']);
		  foreach($_SEARCH_DATA['Hotel_Stars'] as $_sitem){
			  $hs && $_sitem['id']==$hs && $_schSlt['hs'] = $_sitem;
			  $href = makesearchUrl('hs',$_sitem['id']);
			  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$hs?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  }
		  ?>
	  </div>
	</li>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="hm">
	  <b><?php echo db_to_html('配餐服务：')?></b>
	  <div>
		  <?php
	      ksort($_SEARCH_DATA['Hotel_Meals']);
		  foreach($_SEARCH_DATA['Hotel_Meals'] as $_sitem){
			  $hm && $_sitem['id']==$hm && $_schSlt['hm'] = $_sitem;
			  $href = makesearchUrl('hm',$_sitem['id']);
			  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$hm?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  }
		  ?>
	  </div>
	</li>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="hi">
	  <b><?php echo db_to_html('上网服务：')?></b>
	  <div>
		  <?php
	      ksort($_SEARCH_DATA['Hotel_Internet']);
		  foreach($_SEARCH_DATA['Hotel_Internet'] as $_sitem){
			  $hi && $_sitem['id']==$hi && $_schSlt['hi'] = $_sitem;
			  $href = makesearchUrl('hi',$_sitem['id']);
			  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$hi?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  }
		  ?>
	  </div>
	</li>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="hl">
	  <b><?php echo db_to_html('酒店位置：')?></b>
	  <div>
		  <?php
	      ksort($_SEARCH_DATA['Hotel_Locaction']);
		  foreach($_SEARCH_DATA['Hotel_Locaction'] as $_sitem){
			  $hl && $_sitem['id']==$hl && $_schSlt['hl'] = $_sitem;
			  $href = makesearchUrl('hl',$_sitem['id']);
			  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$hl?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  }
		  ?>
	  </div>
	</li>
	
	<?php
	//酒店end}
	}else{	//非酒店才能显示持续时间
	
	//按sofia的意思 先隐藏这块 by lwkai 2012-04-17 18:34
	if (!$isHotels) {
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="d">
	  <b><?php echo db_to_html('行程天数：')?></b>
	  <div>
	  <?php
	  
	  $d && $_schSlt['d'] = $_SEARCH_DATA['DaysItem'][$d];
	  foreach($_SEARCH_DATA['DaysItem'] as $key=>$_sitem){
		  $href = makesearchUrl('d',$_sitem['id']);
		  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$d?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
	  }
	  ?><?php /* <span id="MoreOptsA"><?php echo db_to_html('展开&gt;&gt;'); ?></span>*/?>
	   </div>
	</li>
	<?php
	}
	}
	//按 sofia 要求 在酒店页 不显示这块by lwkai 2012-04-17 18:34
	if (!$isHotels) {
	?>
	

	<li id="<?php echo $ajaxTypename;?>_option_item" mod="m" class="moreOpt">
	  <b><?php echo db_to_html('旅游预算：')?></b>
	  <div>
	  <?php
	  $m && $_schSlt['m'] = $_SEARCH_DATA['Price'][$m];
	  foreach($_SEARCH_DATA['Price'] as $key=>$_sitem){
		  $href = makesearchUrl('m',$_sitem['id']);
		  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$m?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
	  }
	  ?>
	   </div>
	</li>
	
	<?php
	}
	
	if(!$isHotels){	//非酒店才能显示优惠活动
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="of" class="moreOpt">
	  <b><?php echo db_to_html('优惠活动：')?></b>
	  <div>
	  <?php
	  $of && $_schSlt['of'] = $_SEARCH_DATA['Offer'][$of];
	  foreach($_SEARCH_DATA['Offer'] as $key=>$_sitem){
	  	  $href = makesearchUrl('of',$_sitem['id']);
		  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$of?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
	  }
	  ?>
	   </div>
	</li>
	<?php
	}
	?>
	
		<?php if($tc==3 &&  ($cPath=='24_32' || $cPath=='24_142')){$hreflstay = makesearchUrl();?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="ls" class="moreOpt">
	  <b><?php echo db_to_html('入住时长：');?></b>
	  <div>
	       <a href=<?php echo $hreflstay.'ls-0'?> class="<?php if($ls==0)echo 'selected_list'?>" val="0"><?php echo db_to_html('不限');?></a>
		   <a href=<?php echo $hreflstay.'ls-1'?> class="<?php if($ls==1)echo 'selected_list'?>" val="1"><?php echo db_to_html('1晚');?></a>
		   <a href=<?php echo $hreflstay.'ls-2'?> class="<?php if($ls==2)echo 'selected_list'?>" val="2"><?php echo db_to_html('2晚');?></a>
		   <a href=<?php echo $hreflstay.'ls-3'?> class="<?php if($ls==3)echo 'selected_list'?>" val="3"><?php echo db_to_html('3晚');?></a>		   
	  </div>
	</li>
<?php
		}
}


if(isset($ajax) && $ajax=='true' && $openAjaxUrl){
	$ajaxOutput['search_option'] = ob_get_contents();
}
?>
</ul>
<?php if (count($_schSlt) > 0) { ?>
<ul>
<li class="shuanze">
<b class="selt"><?php echo db_to_html('已选择：');?></b>
<div>
  <?php 
 if($cat_mnu_sel!="recommended"){
 foreach($_schSlt as $sltkey=>$sltval){
	  if(count($sltval)){
  ?>
	  <span class="<?php echo $ajaxTypename;?>_mod_<?php echo $sltkey?>"><i style="cursor:default"><?php echo $sltval['name']?></i><a href="<?php echo makesearchUrl($sltkey,'');?>" <?php if($openAjaxUrl){?>onclick="return jQuery_Search_click('<?php echo $ajaxTypename;?>',$(this));"<?php }?> mod='<?php echo $sltkey?>'><img src="image/nav/x_03.gif" /></a></span>
	  <?php /*<input type="hidden" name="<?php echo $sltkey?>" value="<?php echo $sltval['id']?>" />*/ ?>
  <?php }}
 }
  ?>
</div>
</li></ul>
<?php }?>
</div>
<div class="moreOpt_div"><div class="moreOpts"></div><div class="moreAdiv"><a href="javascript:void(0);" id="bbbBtn"><?php echo db_to_html('展开'); ?></a></div></div>
<script type="text/javascript">
/*
jQuery('#bbbBtn').click(function(e) {
   var _search_option=jQuery('#<?php echo $ajaxTypename;?>_option');
   if(_search_option.attr('isHidden') == "true"){
	   jQuery(this).html('<?php echo db_to_html('收起')?>');
   } else {
	   jQuery(this).html('<?php echo db_to_html('展开')?>');
   }
   jQuery_ChangeOption('<?php echo $ajaxTypename;?>');
   jQuery(this).blur();
});

function test(){
   var _search_option=jQuery('#<?php echo $ajaxTypename;?>_option');
   var a = jQuery('#<?php echo $ajaxTypename;?>_option_btnshow');
if (getCookie('<?php echo $ajaxTypename;?>_option') == 'show') {
        _search_option.slideDown();
        _search_option.attr('isHidden','');
        a.removeClass('hidden');
		/* 写cookie记录操作状态 * /
		setCookie('<?php echo $ajaxTypename;?>_option', 'show', 1);
		jQuery("#MoreOptsA").show();
		jQuery('#bbbBtn').html('<?php echo db_to_html('收起')?>');
}else{
        _search_option.attr('isHidden','true');
        _search_option.slideUp();
        a.addClass('hidden');
		/* 写cookie记录操作状态 * /
		setCookie('<?php echo $ajaxTypename;?>_option', 'hide', 1);
		jQuery("#MoreOptsA").hide();
};
}
test();*/
jQuery('#bbbBtn').click(function(){
if(jQuery('.moreOpt').length > 0){
	if(jQuery("#bbbBtn").html() == "<?php echo db_to_html('展开') ?>"){
		/* 写cookie记录操作状态 */
		setCookie('moreOptsStatus', 'show', 1);
		moreOptsShowOrHide();
		
		
	}else{
		/* 写cookie记录操作状态 */
		setCookie('moreOptsStatus', 'hide', 1);
		moreOptsShowOrHide();
		/*jQuery('#_search_option').animate({'height': jQuery('.moreOpt').eq(0).offset().top - jQuery('#_search_option').offset().top});
		jQuery(this).html('<?php echo db_to_html('展开') ?>');*/
	}
}
});

function moreOptsShowOrHide(){
	if(getCookie("moreOptsStatus") == "show"){
		//jQuery(".moreOpt").slideUp();//show();
		jQuery('#_search_option').animate({'height' : jQuery('#_search_option').attr('oldheight')});
		jQuery("#bbbBtn").html("<?php echo db_to_html('收起') ?>").css('background-position','left -95px');
	}else{
	//	jQuery(".moreOpt").stop(true,true).slideUp();//hide();
		jQuery('#_search_option').animate({'height': jQuery('.moreOpt').eq(0).offset().top - jQuery('#_search_option').offset().top});
		jQuery("#bbbBtn").html("<?php echo db_to_html('展开') ?>").css('background-position','-109px -95px');
	}
}

jQuery(function(){
if(jQuery('.moreOpt').length > 0){
	jQuery('#_search_option').attr('oldheight',jQuery('#_search_option').outerHeight()).css('overflow','hidden');
	moreOptsShowOrHide();
	}
});
</script>
<?php }?>