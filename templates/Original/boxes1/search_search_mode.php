<?php
$resetSearchOptionUrl = tep_href_link('advanced_search_result.php','');

// 如无任何筛选选项则根据NowCookie的值来判断是否显示筛选项{ 
//if(!(int)$fc && !(int)$tc && !(int)$vc && !(int)$d && !(int)$m && !(int)$of ){
?>
<script type="text/javascript">
/*jQuery().ready(function() {
	var NowCookie = getCookie("<?php echo $ajaxTypename;?>_option");
	if(NowCookie=="hide"){
		var _search_option=jQuery('#'+'<?php echo $ajaxTypename;?>'+'_option');
		var a = jQuery('#'+'<?php echo $ajaxTypename;?>'+'_option_btnshow');
		_search_option.attr('isHidden','true');
		_search_option.hide();
		a.addClass('hidden');
		jQuery("#MoreOptsA").hide();
	}
});*/
</script>
<?php
//}
// 如无任何筛选选项则根据NowCookie的值来判断是否显示筛选项 }
?>
<div class="chooseCon">
  <h2>
	<b  id="<?php echo $ajaxTypename;?>_option_btnshow" class="<?= $option_btnshow_class;?>"><?php 
	/*
	echo db_to_html('您选择了'); 
	if (tep_not_null($_GET['fcw']) && $_GET['fcw'] != iconv('gb2312',CHARSET,'输入出发地')) {
		echo db_to_html('出发地<span>') . $_GET['fcw'] . '</span>&nbsp;';
	}
	
	if (tep_not_null($_GET['tcw']) && $_GET['tcw'] != iconv('gb2312',CHARSET,'输入目的地')) {
		echo db_to_html('目的地<span>') . $_GET['tcw'] . '</span>'; 	
	}
	
	?> --- <?php echo db_to_html('继续筛选')*/?>
	
	</b><?php /*onclick="jQuery_ChangeOption('<?php echo $ajaxTypename;?>');"*/?>
	
	<span><a href="<?php echo $resetSearchOptionUrl;?>"><?php echo db_to_html('重置筛选条件')?></a></span>
  </h2>
  
  <ul id="<?php echo $ajaxTypename;?>_option">
<?php
//=====================================================================================
		if(count($_SEARCH_DATA['From_City'])){
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="fc">
	  <b><?php echo db_to_html('出发地点：')?></b>
	  <div>
	  <?php
      $_SEARCH_DATA['From_City'][0] = array('id'=>'','name'=>db_to_html('不限'));
	  ksort($_SEARCH_DATA['From_City']);
	  $_dftnum=6;
	  $show_tep=0;
      foreach($_SEARCH_DATA['From_City'] as $_sitem){
		  $fc && $_sitem['id']==$fc && $_schSlt['fc'] = $_sitem;
		  if(!$fcs && $show_tep==$_dftnum && $fc && !is_array($_schSlt['fc']) && $_SEARCH_DATA['From_City'][$fc]){
			  $_sitem = $_SEARCH_DATA['From_City'][$fc];
			  $_schSlt['fc'] = $_sitem;
		  }
          $href = makesearchUrl('fc',$_sitem['id']);
          echo '<a href="'.$href.'" class="'.($_sitem['id']!=$fc?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  if(!$fcs && $show_tep>=$_dftnum)break;
		  $show_tep++;
      }
	  if(count($_SEARCH_DATA['From_City'])>$_dftnum && !$fc){
		  if($fcs){
			  echo '<a href="'.makesearchUrl('fcs','').'" class="more" val="" mod="fcs">'.db_to_html("&lt;&lt;隐藏").'</a>';
		  }else{
			  echo '<a href="'.makesearchUrl('fcs',1).'" class="more" val="1" mod="fcs">'.db_to_html("查看更多&gt;&gt;").'</a>';
		  }
	  }
	  unset($show_tep,$_dftnum);
      ?>
	  </div>
	</li>
	<?php
	}
	if(tep_not_null(($_SEARCH_DATA['Via_City']))){
	?>
    <li id="<?php echo $ajaxTypename;?>_option_item" mod="vc">
	  <b><?php echo db_to_html('途经景点：')?></b>
	  <div>
	  <?php
      $_SEARCH_DATA['Via_City'][0] = array('id'=>'','name'=>db_to_html('不限'));
	  ksort($_SEARCH_DATA['Via_City']);
	  $_dftnum=6;
	  $show_tep=0;
      foreach($_SEARCH_DATA['Via_City'] as $_sitem){
		  $vc && $_sitem['id']==$vc && $_schSlt['vc'] = $_sitem;
		  if(!$svc && $show_tep==$_dftnum && $vc && !is_array($_schSlt['vc']) && $_SEARCH_DATA['Via_City'][$vc]){
			  $_sitem = $_SEARCH_DATA['Via_City'][$vc];
			  $_schSlt['vc'] = $_sitem;
		  }
          $href = makesearchUrl('vc',$_sitem['id']);
          echo '<a href="'.$href.'" class="'.($_sitem['id']!=$vc?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  if(!$svc && $show_tep>=$_dftnum)break;
		  $show_tep++;
      }
	  
	  if(count($_SEARCH_DATA['Via_City'])>$vc_dftnum && !$vc){
	  if($svc){
		  echo '<a href="'.makesearchUrl('svc','').'" class="more" val="" mod="svc">'.db_to_html("&lt;&lt;隐藏").'</a>';
	  }else{
		  echo '<a href="'.makesearchUrl('svc',1).'" class="more" val="1" mod="svc">'.db_to_html("查看更多&gt;&gt;").'</a>';
	  }
	  }
	  unset($show_tep,$_dftnum);
      ?>
	  </div>
	</li>
	<?php }
	
	if(count($_SEARCH_DATA['To_City'])){
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="tc">
	  <b><?php echo db_to_html('结束城市：')?></b>
	  <div>
	  <?php
      $_SEARCH_DATA['To_City'][0] = array('id'=>'','name'=>db_to_html('不限'));
	  ksort($_SEARCH_DATA['To_City']);
	  $_dftnum=6;
	  $show_tep=0;
      foreach($_SEARCH_DATA['To_City'] as $_sitem){
		  $tc && $_sitem['id']==$tc && $_schSlt['tc'] = $_sitem;
		  if(!$tcs && $show_tep==$_dftnum && $tc && !is_array($_schSlt['tc']) && $_SEARCH_DATA['To_City'][$tc]){
			  $_sitem = $_SEARCH_DATA['To_City'][$tc];
			  $_schSlt['tc'] = $_sitem;
		  }
          $href = makesearchUrl('tc',$_sitem['id']);
          echo '<a href="'.$href.'" class="'.($_sitem['id']!=$tc?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
		  if(!$tcs && $show_tep>=$_dftnum)break;
		  $show_tep++;
      }
	  
	  if(count($_SEARCH_DATA['To_City'])>$_dftnum && !$tc){
	  if($tcs){
		  echo '<a href="'.makesearchUrl('tcs','').'" class="more" val="" mod="tcs">'.db_to_html("&lt;&lt;隐藏").'</a>';
	  }else{
		  echo '<a href="'.makesearchUrl('tcs',1).'" class="more" val="1" mod="tcs">'.db_to_html("查看更多&gt;&gt;").'</a>';
	  }
	  }
	  unset($show_tep,$_dftnum);
      ?>
	  </div>
	</li>
    <?php }
	
	
	?>
	<li id="<?php echo $ajaxTypename;?>_option_item" mod="d">
	  <b><?php echo db_to_html('持续时间：')?></b>
	  <div>
	  <?php
	  $d && $_schSlt['d'] = $_SEARCH_DATA['DaysItem'][$d];
	  foreach($_SEARCH_DATA['DaysItem'] as $key=>$_sitem){
		  $href = makesearchUrl('d',$_sitem['id']);
		  echo '<a href="'.$href.'" class="'.($_sitem['id']!=$d?'':'selected_list').'" val="'.$_sitem['id'].'">'.$_sitem['name'].'</a>';
	  }
	  ?>
	   </div>
	</li>

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
<script type="text/javascript">
/*function moreOptsShowOrHide(){
	if(getCookie("moreOptsStatus") == "show"){
		jQuery(".moreOpt").show();
		jQuery("#MoreOptsA").html("<?php echo db_to_html('&lt;&lt;收起') ?>");
	}else{
		jQuery(".moreOpt").hide();
		jQuery("#MoreOptsA").html("<?php echo db_to_html('展开&gt;&gt;') ?>");
	}
}

jQuery(function(){
	moreOptsShowOrHide();
});*/
</script>
<?php 
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
<div class="moreOpt_div">
<div class="moreOpts"></div>
<div class="moreAdiv">
<a href="javascript:void(0);" id="bbbBtn"><?php echo db_to_html('展开&gt;&gt;'); ?></a>
</div>
</div>
<script type="text/javascript">
/*jQuery('#MoreOptsA').click(function(){
	if(jQuery("#MoreOptsA").html() == "<?php echo db_to_html('展开&gt;&gt;') ?>"){
		/* 写cookie记录操作状态 * /
		setCookie('moreOptsStatus', 'show', 1);
		moreOptsShowOrHide();
	}else{
		/* 写cookie记录操作状态 * /
		setCookie('moreOptsStatus', 'hide', 1);
		moreOptsShowOrHide();
	}
	
});*/

jQuery('#bbbBtn').click(function(){
	if(jQuery("#bbbBtn").html() == "<?php echo db_to_html('展开')?>"){
		/* 写cookie记录操作状态 */
		setCookie('moreOptsStatus', 'show', 1);
		moreOptsShowOrHide();
	}else{
		/* 写cookie记录操作状态 */
		setCookie('moreOptsStatus', 'hide', 1);
		moreOptsShowOrHide();
		/*jQuery('#_search_option').animate({'height': jQuery('.moreOpt').eq(0).offset().top - jQuery('#_search_option').offset().top});
		jQuery(this).html('展开');*/
	}
});
function moreOptsShowOrHide(){
	if(getCookie("moreOptsStatus") == "show"){
		//jQuery(".moreOpt").slideUp();//show();
		jQuery('#_s_search_option').animate({'height' : jQuery('#_s_search_option').attr('oldheight')});
		jQuery("#bbbBtn").html("<?php echo db_to_html('收起')?>").css('background-position','left -95px');
	}else{
		// jQuery(".moreOpt").stop(true,true).slideUp();//hide();
		jQuery('#_s_search_option').animate({'height': jQuery('.moreOpt').eq(0).offset().top - jQuery('#_s_search_option').offset().top});
		jQuery("#bbbBtn").html("<?php echo db_to_html('展开')?>").css('background-position','-109px -95px');
	}
}
jQuery(function(){
	jQuery('#_s_search_option').attr('oldheight',jQuery('#_s_search_option').outerHeight()).css('overflow','hidden');
	moreOptsShowOrHide();
}); 
</script>

