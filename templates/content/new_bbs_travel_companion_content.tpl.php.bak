
<script type="text/javascript"> 
document.oncontextmenu=function(e){return false;} 
document.onselectstart=function(e){return false;} 
document.oncopy=function(e){return false;}
</script> 
<?php 
//　原来的左边一块　start {
	/*
<div class="item_left">
    <div class="jb_left">
	
      <?php
		//载入Face
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_face.php');
		$t_gender_str = '';
		if($rows['t_gender']=="1"){ $t_gender_str = '先生'; }
		if($rows['t_gender']=="2"){ $t_gender_str = '女士'; }
		
		get_travel_companion_face((int)$rows['customers_id'], $rows['customers_name'], $t_gender_str);

	  ?>
    
	</div>
    <div class="item_left1">
      
	  <?php
	  if(defined('TRAVEL_COMPANION_RECOMMEND_CATEGORIES') && tep_not_null(TRAVEL_COMPANION_RECOMMEND_CATEGORIES)){
	  	$categories_ids = explode(',',TRAVEL_COMPANION_RECOMMEND_CATEGORIES);
	  ?>
	  <div class="box2">
	 	<img src="image/box2_bg_l.jpg" class="box_f_l">
        <h3><?php echo db_to_html('推荐结伴同游景点')?></h3>
		<img src="image/box2_bg_r.jpg" class="box_f_r">
      </div>
      <div class="item_left_sec" style="width:183px; border:1px solid #c5e6f9; border-top:none;">
        <ul>
          <?php for($i=0; $i<count($categories_ids); $i++){?>
		  <li><a href="<?php echo tep_href_link('new_travel_companion_index.php','TcPath='.tep_get_category_patch((int)$categories_ids[$i]));?>"><?php echo db_to_html(tep_get_category_name((int)$categories_ids[$i]));?></a></li>
		  <?php
		  }
		  ?>

        </ul>
      </div>
	  <?php
	  }
	  ?>
    
	</div>
	
</div>
*/
// 原来的左边一块　end } by lwkai 
?>
<div class="jb_right_lwkai">

<?php // 结伴同游发起贴 start { ?>
  <div class="jb_right_b bor1">
  <?php //用户图像信息 start { ?>
  <div class="jb_left">
	
      <?php
		//载入Face
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_face.php');
		$t_gender_str = '';
		if($rows['t_gender']=="1"){ $t_gender_str = '先生'; }
		if($rows['t_gender']=="2"){ $t_gender_str = '女士'; }
		
		get_travel_companion_face((int)$rows['customers_id'], $rows['customers_name'], $t_gender_str,$rows['t_companion_id']);

	  /* ?><div style="padding-left:20px;line-height:25px;">
       <?php echo db_to_html('我的基本信息：')?><br/> 
       <?php echo db_to_html('姓名：');?><?php echo db_to_html(tep_db_output($rows['customers_name']));?><br/>
       <?php echo db_to_html('性别：');?><?php echo db_to_html($t_gender_str);?><br/>
  
		<?php
		if(tep_not_null($rows['email_address']) && (int)$rows['t_show_email']){
		?>
		<?php echo db_to_html('Email：');?><?php echo tep_db_output($rows['email_address']);?><br/>
		<?php 
		}
		?>
		<?php
		if(tep_not_null($rows['customers_phone']) && (int)$rows['t_show_phone']){
		?>
		<?php echo db_to_html('电话：');?><?php echo tep_db_output($rows['customers_phone']);?><br/>
		<?php
		}
		?>
          <div><?php echo db_to_html('备注：');?></div>
          <div><?php echo nl2br(db_to_html(tep_db_output($rows['personal_introduction'])));?>
          </div>
          </div>
          <?php */ ?>
	</div>
  <?php // 用户图像信息　end } ?>
  <div class="right">
  <div class="travel_title"><span style="float:left"><?php echo db_to_html('发表于：') . $rows['add_time'];?></span><div class="fr">
  <?php
		$check_travel_companion_app = (int)tep_check_travel_companion_app($customer_id, $rows['t_companion_id']);
		if(((int)$customer_id && $rows['customers_id']==$customer_id) || $has_filled == true || (int)$check_travel_companion_app || $rows['has_expired']=="1"){
			$alert_str = '你是楼主，不用申请。';
			if($has_filled == true){ $alert_str = '名额已满！不能申请了，看看别的吧。'; }
			if((int)$check_travel_companion_app){
				$alert_str = '您已经申请该结伴，请勿重复申请！';
			}
			if($rows['has_expired']=="1"){
				$alert_str = '已过期！';
			}
		?>
		 <span class="jb_fb_all_s" onclick="style_alert('<?php echo db_to_html($alert_str)?>')"><?php echo db_to_html('申请结伴')?></span>
		<?php	
		}else{
		?>
		  <span class="ren"><?php echo db_to_html('已有<b>'.$apped_num.'</b>人申请结伴'); ?></span>
		  <span class="jb_fb_all"  onclick="showDiv('travel_companion_tips_2064');"><?php echo db_to_html('申请结伴')?></span>
		<?php
		}
		?>
  	<?php /*?><span class="jb_fb_all_s" type="button" onclick="style_alert('<?php echo db_to_html($alert_str)?>')"><?php echo db_to_html('申请结伴')?></span><?php */?>
    <span onclick="show_and_hidden('CompanionFormReply',0,'<?=$div_jb_fb_tc?>');"><?php echo db_to_html('回复');?></span>
   </div></div>
    <div class="jb_right_xx">
      <h3><?php 
	  //print_r($rows);
	  echo db_to_html(tep_db_output($rows['t_companion_title']))?></h3>
      <ul>

		<li>
		<?php if($rows['_type']==1){?>
          <div class="jb_r_tab"><?php echo db_to_html('旅游线路：')?></div>
          <div class="jb_r_tab1">
		  <?php if((int)$rows['products_id']){?>
		  <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$rows['products_id']);?>" title="<?php echo (tep_not_null($old_content) ? db_to_html($old_content):db_to_html($p_name));?>" target="_blank" class="t_c" id="root_tiezi_post"><?php echo cutword(db_to_html($p_name),60)?></a>
		  <?php
		  }else{
			 //如果有走四方网址则加连接
			 $p_name = auto_add_tff_links($p_name);
			 echo '<p id="root_tiezi_post">'.db_to_html($p_name).'</p>';
		  }
		  ?>
		  </div>
		  <?php }else{?>
		  <div class="jb_r_tab"><?php echo db_to_html('目的地：')?></div>
          <div class="jb_r_tab1">
		<?php 
			echo db_to_html(getPlace($rows['end_place']));
		?>
		  </div>
		  </li>
		  <li>
		  <div class="jb_r_tab"><?php echo db_to_html('行程计划：')?></div>
          <div class="jb_r_tab1">
		<?php 
			echo db_to_html($rows['travel_plan']);
		?>
		  </div>
		  <?php }?>
        </li>

		<?php 
		if($rows['hope_departure_date']!="" && (int)$rows['hope_departure_date']){
		?>
		<li>
          <div class="jb_r_tab"><?php echo db_to_html('出行时间：')?></div>
          <div class="jb_r_tab1"><?php echo $rows['hope_departure_date'];?></div>
        </li>
		<?php
		}
		?>

		<?php if((int)$rows['now_people_man'] || (int)$rows['now_people_woman'] || (int)$rows['now_people_child']){?>
		<li>
          <div class="jb_r_tab">
		  <?php
		  echo db_to_html('现有人数：');
		  ?>
		  </div>
		  
		  <div class="jb_r_tab1">
		  <?php
		  $now_pep_str = "";
		  if($rows['now_people_man']>0){
		  	$now_pep_str.= db_to_html((int)$rows['now_people_man'].'人 '); 
		  }
		  if($rows['now_people_woman']>0){
		  	$now_pep_str.= db_to_html('女'.(int)$rows['now_people_woman'].'人 '); 
		  }
		  if($rows['now_people_child']>0){
		  	$now_pep_str.= db_to_html('小孩'.(int)$rows['now_people_child'].'人 '); 
		  }
		  echo $now_pep_str;
		  ?>
          </div>
        </li>
		<?php
		}
		?>
		
		<?php
		$hope_pep_str ="";
		if((int)$rows['hope_people_man'] || (int)$rows['hope_people_woman'] || (int)$rows['hope_people_child']){
		
		?>
        <li>
          <div class="jb_r_tab"><?php echo db_to_html('期待结伴：')?></div>
          <div class="jb_r_tab1">
		  <?php
		  if($rows['hope_people_man']>0){
		  	$hope_pep_str .= db_to_html((int)$rows['hope_people_man'].'人 '); 
		  }
		  if($rows['hope_people_woman']>0){
		  	$hope_pep_str .= db_to_html('女'.(int)$rows['hope_people_woman'].'人 '); 
		  }
		  if($rows['hope_people_child']>0){
		  	$hope_pep_str .= db_to_html('小孩'.(int)$rows['hope_people_child'].'人 '); 
		  }
		  echo $hope_pep_str;
			if($rows['open_ended']=="1"){
				echo db_to_html('<span class="col_1">欢迎更多人申请结伴</span>');
			}
		  ?>
		  
		  </div>
        </li>
		<?php
		}
		?>
		
		<?php /*　按Sofia的意思　先隐藏该支付方式　以后可能需要用到　{
        <li>
          <div class="jb_r_tab"><?php echo db_to_html('支付方式：');?></div>
          <div class="jb_r_tab1">
		  <?php
		  if($rows['who_payment']=="1"){
		  	echo db_to_html('我支付');
		  }else{
		  	echo db_to_html('AA制');
		  }
		  ?>
		  </div>
        </li>
		  // 按Sofia的意思　先隐藏该支付方式　以后可能需要用到　}
		*/?>
        <li class="pl28"><br/><?php echo db_to_html('我的基本信息：')?></li> 
        <li>
          <div class="jb_r_tab"><?php echo db_to_html('姓名：');?></div>
          <div class="jb_r_tab1"><?php echo db_to_html(tep_db_output($rows['customers_name']));?></div>
        </li>
        <li>
          <div class="jb_r_tab"><?php echo db_to_html('性别：');?></div>
          <div class="jb_r_tab1"><?php echo db_to_html($t_gender_str);?></div>
        </li>
        
		<?php
		/*
		if(tep_not_null($rows['email_address']) && (int)$rows['t_show_email']){
		?>
		<li>
          <div class="jb_r_tab"><?php echo db_to_html('Email：');?></div>
          <div class="jb_r_tab1"><a href="mailto:<?php echo tep_db_output($rows['email_address']);?>" class="t_c"><?php echo tep_db_output($rows['email_address']);?></a></div>
        </li>
		<?php 
		}
		?>
		<?php
		if(tep_not_null($rows['customers_phone']) && (int)$rows['t_show_phone']){
		?>
		<li>
          <div class="jb_r_tab"><?php echo db_to_html('电话：');?></div>
          <div class="jb_r_tab1"><?php echo tep_db_output($rows['customers_phone']);?></div>
        </li>
		<?php
		}*/
		?>
        
        <li>
          <div class="jb_r_tab"><?php echo db_to_html('备注：');?></div>
          <div class="jb_r_tab1"><?php echo nl2br(db_to_html(tep_db_output($rows['personal_introduction'])));?>
          </div>
        </li>
      </ul>
    </div>
    
    
<!--点击弹出层-->
  <div class="jb_fb_tcAddXx" id="travel_companion_tips_2064" style="text-decoration:none; display:none">
  <?php echo tep_pop_div_add_table('top');?>
  <?php
  if(!(int)$customer_id){

	$replace_id = 'travel_companion_tips_2064';
	$next_file = 'ajax_shenqin_table.php';
	include('ajax_fast_login.php');
  }else{
        
  	include('ajax_shenqin_table.php');
  }
  ?>
  <?php echo tep_pop_div_add_table('foot');?>
 </div>
 <!--弹出结束-->
 
<!--点击弹出层-->
  <?php
  ////根据浏览器兼容ie8
  $div_jb_fb_tc='jb_fb_tc';
  if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox")||strpos($_SERVER["HTTP_USER_AGENT"],"Safari")||strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")||strpos($_SERVER["HTTP_USER_AGENT"],"Opera")){
      $div_jb_fb_tc = 'jb_fb_tcAddXx';
  }
  ?>
  <div class="<?=$div_jb_fb_tc?>" id="travel_companion_tips_2065" style="text-decoration:none; display:none">
  <?php echo tep_pop_div_add_table('top');?>
  <?php
  if(!(int)$customer_id){

	$replace_id = 'travel_companion_tips_2065';
	$next_file = 'top_window';
	include('ajax_fast_login.php');
  }else{
        
  }
  ?>

  <?php echo tep_pop_div_add_table('foot');?>
 </div>
 <!--弹出结束-->
 
 
<?php 
// 原来的右边 申请结伴等信息 暂隐藏里面已经有多少人申请 后期可能需要  start { 
/*?>    
    <div class="jb_right_jb">
      <div class="jb_right_jb_bt">
        <?php
		$check_travel_companion_app = (int)tep_check_travel_companion_app($customer_id, $rows['t_companion_id']);
		if(((int)$customer_id && $rows['customers_id']==$customer_id) || $has_filled == true || (int)$check_travel_companion_app || $rows['has_expired']=="1"){
			$alert_str = '你是楼主，不用申请。';
			if($has_filled == true){ $alert_str = '名额已满！不能申请了，看看别的吧。'; }
			if((int)$check_travel_companion_app){
				$alert_str = '您已经申请该结伴，请勿重复申请！';
			}
			if($rows['has_expired']=="1"){
				$alert_str = '已过期！';
			}
		?>
		 <button style="z-index: 5;" target="_blank" class="jb_fb_all_s" type="button" onclick="style_alert('<?php echo db_to_html($alert_str)?>')"><?php echo db_to_html('申请结伴')?></button>
		<?php	
		}else{
		?>
		 <button type="button" class="jb_fb_all" target="_blank" style="z-index:5" onclick="showDiv('travel_companion_tips_2064');"><?php echo db_to_html('申请结伴')?></button>
		<?php
		}
		?>

		 
      </div>
      <div class="jb_right_jb_bt1">
         <p class="col_1"><!--此结伴同游人数超过<br />8人将享受超低团购价--></p>
      </div>
      <div class="jb_right_jb_bt">
         
		 <p class="col_2">
		<?php
		if($has_filled == true){
		 	echo db_to_html('结伴同游已完成');
		}else{
		 	
			echo db_to_html('已有<b>'.$apped_num.'</b>人申请结伴');
		}
		?>
		 
		 </p>
		 
      </div>
      <div class="jb_right_jb_bt">
         <a href="JavaScript:void(0)" onclick="show_and_hidden('CompanionFormReply',0,'<?=$div_jb_fb_tc?>');" class="jb_fb_tc_bt_a"><?= db_to_html('回复');?></a>&nbsp;&nbsp;&nbsp;&nbsp;
		 <?php
		 //自己浏览自己发布的结伴帖时，去掉“给他发信息”
		 if($rows['customers_id']!=$customer_id){
		 ?>
		 <a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="show_site_inner_sms_layer(<?= $rows['customers_id']?>,'travel_companion', <?= $rows['t_companion_id']?>)"><?= db_to_html('给他发信息')?></a>
		 <?php
		 }
		 ?>
		 </div>
    </div>
    
    <?php 
	*/
	// 原来的右边 申请结伴等信息 暂隐藏里面已经有多少人申请 后期可能需要  end } ?>
    <div class="del_float"></div>
</div>
		 <?php
		 //新浪微博start
		 // 注释掉 微博 start {
		 //bshare
		 /*if((!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != 'on') ){
		 	$uuid = "60d7f710-7666-4897-a0cf-2c7b32c98388";
		 	if(strtolower(CHARSET)=="big5"){
		 		$uuid = "929efbd7-68d3-4cc2-b113-24c3cb6d957f";
			}
		 ?>
<style type="text/css">
.bshare{ position:relative; width:708px; height:22px; padding:5px 10px; background:#fbfbfb; border-top:1px dashed #DBDBDB; clear:both;}
.bshare label{ float:left; width:55px; line-height:24px; color:#777;}
.bshare-custom a{ float:left; width:16px; height:16px; margin:3px 2px 4px; padding:0;}
.bshare-custom a.bshare-more{ width:auto; font-size:12px; line-height:16px;}
.shareRenren{ display:none; position:absolute; left:87px; top:8px; width:16px; height:16px; background:url(http://static.bshare.cn/frame/images/logos/s3/renren.gif);}
.shareRenrenTr{ left:167px;}
.shareRenren:hover{ opacity:0.75;}

</style>
<div class="bshare">

<?php
	$class_renren_ft = "";
	if(strtolower(CHARSET)=="big5"){
		$class_renren_ft = "shareRenrenTr";
	}
	?>

<script type="text/javascript">
jQuery(function(){
	jQuery(".shareRenren").show();		
});
</script>
	<a href="javascript:u='http://share.xiaonei.com/share/buttonshare.do?link='+location.href+'&amp;title='+encodeURIComponent(document.title);window.open(u,'xiaonei','toolbar=0,resizable=1,scrollbars=yes,status=1,width=626,height=436');void(0)" class="shareRenren <?= $class_renren_ft?>"></a>

<div class="bshare-custom">
<label><?= db_to_html("分享到：");?></label>
<?php
if(strtolower(CHARSET)=="big5"){
	$share_pic = HTTP_SERVER.'/image/jieban_share_ft.jpg';
?>
	<a title="<?= db_to_html("分享到facebook");?>" class="bshare-facebook"></a>
	<a title="<?= db_to_html("分享到twitter");?>" class="bshare-twitter"></a>
	<a title="<?= db_to_html("分享到yahoo收藏");?>" class="bshare-byahoo"></a>
	<a title="<?= db_to_html("分享到plurk");?>" class="bshare-plurk"></a>
	<a title="<?= db_to_html("分享到新浪微博");?>" class="bshare-sinaminiblog"></a>
	<a class="bshareNull"></a>
	<a title="<?= db_to_html("分享到开心网");?>" class="bshare-kaixin001"></a>
	<a title="<?= db_to_html("分享到豆瓣");?>" class="bshare-douban"></a>
	<a title="<?= db_to_html("分享到qq空间");?>" class="bshare-qzone"></a>
<?php
}else{
	$share_pic = HTTP_SERVER.'/image/jieban_share.jpg';
?>
	<a class="bshare-sinaminiblog" title="<?= db_to_html("分享到新浪微博");?>"></a>
	<a class="bshareNull"></a>
	<a class="bshare-kaixin001" title="<?= db_to_html("分享到开心网");?>"></a>
	<a class="bshare-facebook" title="<?= db_to_html("分享到facebook");?>"></a>
	<a class="bshare-twitter" title="<?= db_to_html("分享到twitter");?>"></a>
	<a class="bshare-byahoo" title="<?= db_to_html("分享到yahoo收藏");?>"></a>
	<a class="bshare-douban" title="<?= db_to_html("分享到豆瓣");?>"></a>
	<a class="bshare-qzone" title="<?= db_to_html("分享到qq空间");?>"></a>
<?php
}
?>
	<a class="bshare-more" title="<?= db_to_html("更多平台");?>"><?= db_to_html("更多");?>&gt;&gt;</a>
</div>

<?php
$endShowCodes .= '<script type="text/javascript" src="http://www.bshare.cn/buttonLite.js#uuid='.$uuid.'&amp;style=-1"></script>
<script type="text/javascript" src="http://www.bshare.cn/bshareC1.js"></script>';
?>

<script type="text/javascript">
jQuery(function(){
	if(typeof(bShare)!='undefined'){
		bShare.addEntry({
			 title: "<?php
			echo db_to_html('我在@走四方网，希望&quot;'.tep_db_output($rows['t_companion_title'])).'&quot;';
			if($rows['hope_departure_date'] > "0000-00-00"){
				echo db_to_html('，出行时间').$rows['hope_departure_date'];
			}
			if(tep_not_null($now_pep_str)){
				echo db_to_html('，现有').$now_pep_str;
			}
			if(tep_not_null($hope_pep_str)){
				echo db_to_html('，期望伴友').$hope_pep_str;
			}
			 ?> ",
			 pic:"<?= $share_pic;?>"
		})
	}
});
</script>

<?php if($share_code==1){?>
<a href="javascript:(function(){window.open('http://v.t.sina.com.cn/share/share.php?appkey=1495418171&title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(location.href),'_blank','width=615,height=505');})()"><?= db_to_html("转发到新浪微博")?></a>
<?php }?>
</div>
		 <?php
		 //新浪微博end  
		 }
		 */
		 // 注释掉 微博 end }
		 
		 ?>
 
	
	
<div class="del_float"></div>	
  </div>

<?php
// 结伴同游发起贴 end } 


//推荐线路 start {

//如果没有设定推荐线路则由系统自动提取 start
if(!tep_not_null($rows['recommend_products_ids']) && !(int)$rows['products_id']){
	//通过关键字搜索标题
	$full_string = strip_tags($rows['t_companion_title'].$p_name);
	$string_array = array_unique(get_thesaurus_string_array($full_string));	
	if(count($string_array)){
		$where_string = " WHERE p.products_status = '1' and p.products_id = pd.products_id and (";
		$return_find = false;
		for($i=0; $i<count($string_array); $i++){
			$return_find = true;
			$where_string .= ' products_name LIKE Binary "%'.$string_array[$i].'%" ||';
		}
		$where_string = substr($where_string,0,-2);
		$where_string .= ") ";
	}
	if($return_find == true){
		//$p_sql = tep_db_query('SELECT p.products_id, length(products_name) as name_length FROM `products` p , `products_description` pd '.$where_string.' Group By p.products_id Order By name_length DESC Limit 10');
		$p_sql = tep_db_query('SELECT p.products_id FROM `products` p , `products_description` pd '.$where_string.' Group By p.products_id Limit 5');
		while($p_rows = tep_db_fetch_array($p_sql)){
			$rows['recommend_products_ids'] .= $p_rows['products_id'].',';
		}
		$rows['recommend_products_ids'] = substr($rows['recommend_products_ids'],0,-1);
	}
}
//如果没有设定推荐线路则由系统自动提取 end

$recommend_products_ids_array = array();
if(tep_not_null($rows['recommend_products_ids'])){
	$recommend_products_ids_array = explode(',',strtr($rows['recommend_products_ids'],array(' '=>'')));
}
// 让推荐线路永远不要出来 by lwkai modify 12-03-29
$recommend_products_ids_array = array();
if(count($recommend_products_ids_array)){
?>
  <div class="jb_xx_tj" id="tit" onclick="showHideLyer(this,'tj_content','jb_xx_tj2')">
     <span class="jb_xx_tj_p" style="cursor:pointer;"><?php echo db_to_html('推荐线路');?></span>
  </div>
  <div  class="jb_xx_tjtc"  id="tj_content">
    <ul>
    <?php
	for($i=0; $i<count($recommend_products_ids_array); $i++){
		$p_products_name = db_to_html(tep_get_products_name($recommend_products_ids_array[$i])); 
	?>
	 <li>
       <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$recommend_products_ids_array[$i]);?>" class="t_c " title="<?= $p_products_name?>"><?php echo cutword($p_products_name,112,'');?></a>
	   
	   <p>
	   <?php
	   $prod_sql = tep_db_query("select departure_city_id, products_price, products_durations, products_durations_type, products_tax_class_id from " . TABLE_PRODUCTS. " where products_id = '" . (int)$recommend_products_ids_array[$i] . "' ");
	   $prod_row = tep_db_fetch_array($prod_sql);
	   if(!tep_not_null($prod_row['departure_city_id'])){ $prod_row['departure_city_id'] = "0"; }
		$display_str_departure_city = '';
		$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in (".$prod_row['departure_city_id'].")  order by city");
		while($cityclass = tep_db_fetch_array($cityquery)){
			$display_str_departure_city .= " " .$cityclass['city'] . ", ";
		}
		$display_str_departure_city = substr($display_str_departure_city, 0, -2);
	   
	    echo db_to_html('出发地点：').db_to_html($display_str_departure_city)."&nbsp;&nbsp;";
		
		$str_day = '';
		if($prod_row['products_durations_type'] == 0){
				$str_day =  '天';
		}else if($prod_row['products_durations_type'] == 1){
				$str_day =  '小时';
		}else if($prod_row['products_durations_type'] == 2){
				$str_day =  '分钟';
		}
		echo db_to_html('持续时间：').db_to_html($prod_row['products_durations'].$str_day)."&nbsp;&nbsp;";
		
		$tax_rate_val_get = tep_get_tax_rate($prod_row['products_tax_class_id']);
		if ($new_price = tep_get_products_special_price($prod_row['products_id'])) {
			echo db_to_html('价格：').'<span class="col_4">'.$currencies->display_price($new_price, $tax_rate_val_get).'</span>';
		}else{
			echo db_to_html('价格：').'<span class="col_4">'.$currencies->display_price($prod_row['products_price'], $tax_rate_val_get).'</span>';
		}
	   ?>
	   
	   </p>
     </li>
     <?php }?>
    </ul>
    
	<?php
	if((int)$rows['categories_id']){
	?>
	<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.tep_get_category_patch($rows['categories_id']));?>" class="t_c more_jb_xl"><?php echo db_to_html('更多线路')?>&gt;&gt;</a>
	<?php
	}
	?>
  </div>
<?php
}
//推荐线路 end }
?>  

  
 <?php /*?> <div class="jb_hf"><h3 class="bt"><?php echo db_to_html('网友回复<span style="font-weight:normal;">（'.(int)$rows['reply_num'].'条）</span>');?></h3></div><?php */?>
<?php
//跟贴部分

for($i=0; $i<count($dates); $i++){
?>
  <div class="jb_item_1 line1">
  
  <?php //用户图像信息 start { ?>
  <div class="jb_left">
	
      <?php
		//var_dump($dates[$i]['customers_id']);
		get_travel_companion_face((int)$dates[$i]['customers_id'],$dates[$i]['name']);

	  ?>
    
	</div>
  <?php // 用户图像信息　end } ?>
  
  
     <div class="bbs_right right">
     	<?php 
	$r_faca_img = 'tx_n_s.gif';
	$gender_str = "";
	if($dates[$i]['gender']=='1'){
		$gender_str = db_to_html(' 先生');
		$r_faca_img = 'tx_b_s.gif';
	}
	if($dates[$i]['gender']=='2'){
		$gender_str = db_to_html(' 女士');
		$r_faca_img = 'tx_g_s.gif';
	}
	$r_faca_img = 'image/'.$r_faca_img;
	$r_faca_img = tep_customers_face((int)$dates[$i]['customers_id'], $r_faca_img);
	if($rows['customers_id']==$dates[$i]['customers_id'] && (int)$dates[$i]['customers_id']){
		$gender_str .= '<span class="jifen_num">'.db_to_html('[楼主]').'</span>';
	}
	/*?>
	 <a class="t_c" id="bbs_customers_name_gender_<?php echo $dates[$i]['id']?>" href="<?= tep_href_link('individual_space.php','customers_id='.(int)$dates[$i]['customers_id'])?>" target="_blank">
	 <?php echo db_to_html(tep_db_output($dates[$i]['name']));?>
	 </a>
	 <?php*/
	 
	 ?>
     <div class="travel_title"><div style="float:left"><?php
     echo $gender_str . db_to_html('发表于：');
	 echo db_to_html(' '.substr($dates[$i]['time'],0,16))
	 ?>	
	 <?php if(!(int)$dates[$i]['only_top_can_see']){?>
	<?php
	//去掉“引用功能”start
	$used_quote = false;
	if($used_quote == true){
	?>
	<span class="yin"><a href="JavaScript:void(0)" onclick="quote_bbs('CompanionFormReply',<?php echo $dates[$i]['id']?>)"><?php echo db_to_html('引用')?></a></span>
	<?php
	}
	//去掉“引用功能”end
	
	// 去掉 回复 start
	$used_repay = false;
	if ($used_repay == true) {
	?>
	<span class="hui"><a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="show_and_hidden('CompanionFormReply', <?php echo $dates[$i]['id']?>,'<?=$div_jb_fb_tc?>')"><?php echo db_to_html('回复')?></a></span>
	 <?php
	}
	// 回复 隐藏 结束
	}
	 ?> 
	 <?php
	if(!(int)$dates[$i]['status']){
		echo db_to_html('<span class="status">[此信息目前审核中]</span>');
	}
	 ?>
     </div>
     <?php
	   //计算楼层
	   $current_floor = ($current_page * $row_max)+1+$i-$row_max;
	   echo '<span class="lou">' . db_to_html($current_floor.'楼') . '</span>';
	   ?>
       </div>
	 

	 <div class="tiezi_post" id="tiezi_post_<?php echo $dates[$i]['id']?>">
     	
			<?php if((int)$dates[$i]['only_top_can_see']){		
                                    if(((int)$customer_id && $rows['customers_id']==$customer_id)){?>
                                        <div id="bbs_content_<?php echo $dates[$i]['id']?>"><?php echo db_to_html(tep_db_output($dates[$i]['name'])).strip_tags($gender_str).db_to_html('给你说了句悄悄话').':'.nl2br(db_to_html(tep_db_output($dates[$i]['content'])));?></div>
                             <?php }else{?>
                                        <div id="bbs_content_<?php echo $dates[$i]['id']?>"><?php echo db_to_html(tep_db_output($dates[$i]['name'])).strip_tags($gender_str).db_to_html('给楼主说了句悄悄话');?></div>
                                 <?php }//只告诉楼主?>
			<?php
			}else{
			//载入父贴
			//echo (int)$dates[$i]['parent_id'].'&nbsp;&nbsp;'.(int)$dates[$i]['parent_type'];
			
			if((int)$dates[$i]['parent_id'] && !(int)$dates[$i]['parent_type'] ){
				$par_sql = tep_db_query('SELECT * FROM `travel_companion_reply` WHERE t_c_reply_id="'.(int)$dates[$i]['parent_id'].'" and `status`=1 limit 1');
				$par_row = tep_db_fetch_array($par_sql);
				if((int)$par_row['t_c_reply_id']){
			?>
			<div id="parent_bbs_<?php echo $dates[$i]['id']?>" class="yinyong">
				<?php echo db_to_html('[回复 '.tep_db_output($par_row['customers_name']).']');?><br/>
				<?php echo nl2br(db_to_html(auto_add_tff_links($par_row['t_c_reply_content'])));?>
			</div>
			<?php
				}
			}else{
				//载入引用贴
				echo db_to_html(get_all_parent_for_quote((int)$dates[$i]['parent_id']));
			}
			?>
			
			<div id="bbs_content_<?php echo $dates[$i]['id']?>"><?php echo nl2br(db_to_html(auto_add_tff_links($dates[$i]['content'])));?></div>
			<?php
			}
			?>
			
		</div>
	 </div>

     <?php /*?><div class="jb_item_1_r"><a href="<?= tep_href_link('individual_space.php','customers_id='.(int)$dates[$i]['customers_id'])?>"><img src="<?= $r_faca_img;?>" <?php echo getimgHW3hw($r_faca_img,50,50)?> /></a><p>
	   <?php
	   //计算楼层
	   $current_floor = ($current_page * $row_max)+1+$i-$row_max;
	   echo db_to_html($current_floor.'楼');
	   ?>
	 </p></div><?php */?>
     <div class="del_float"></div>
   </div> 
<?php
}
?>
     
   <div class="jb_fenye">
      <div class="jb_fenye_l">
  <?php 
  if($rows_count_pages>1){
  	echo $rows_page_links_code;
  }
  ?>
      </div>
      </div>
	
	<?php //快速回帖start?>
    <?php
	//回复框的默认值
	$used_center_float = false;
	if($used_center_float == false){
		$re_class = "jb_ft_hf";
		$re_display = "";
		$close_button = "display:none;";
	}else{
		$re_class = "center_pop_small";
		$re_display = "display:none;";
		$close_button = "";
	}
	?>
	<div id="CompanionDivReply" class="<?= $re_class;?>" style="<?= $re_display?>">
	
	<div class="jb_fb_tc_bt">
       <h3 id="action_h3" ><?= db_to_html("发表回复")?></h3><div id="ReWho" style="float:left">&nbsp;
	   <?php if((int)$customer_id){?>
	   <?php /*<a class="t_c" href="<?= tep_href_link('individual_space.php')?>"><?php echo db_to_html($customer_first_name)?></a> */ ?>
       <?php echo db_to_html($customer_first_name)?>
	   <?php }?>&nbsp;
	   </div>
	   <label style=" float:left;"><?php echo db_to_html($Today_date);?></label><div style="float:right; width:16px; margin:0"><button id="close_button" style="<?= $close_button?>" class="icon_fb_bt" onclick="re_close_button()" title="<?= db_to_html('关闭')?>" type="button"></button></div>
    </div>  
	
	<div class="jb_fb_tc_tab">
	<form action="" method="post" name="CompanionFormReply" id="CompanionFormReply" onsubmit="Submit_Companion_Re('CompanionFormReply'); return false">
	<div class="kuai mar-t quick_post">
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="float:left;">
	  <tr>
		<td>
		  <div id="reply_input_<?=(int)$rows['t_companion_id']?>">
			<table width="99%" border="0" cellspacing="0" cellpadding="0">
			  <tr style="display:none;">
				<td align="right" nowrap="nowrap"></td>
				<td id="QuoteReply">&nbsp;
				</td>
				</tr>
                <tr><td colspan="2" valign="top"><span class="caption"><?php echo db_to_html('内容：')?></span>
					<span class="lwktext"><?php
					$max_input_string_num = 100;
					$on_action = 'check_input_string_num(this, '.$max_input_string_num.', &quot;sms_info&quot;)';
					?>
										
				  <?php
                                     if(!(int)$customer_id){
                                        $onclick_onclick='onfocus=check_login("travel_companion_tips_2065",false) ';
                                     }else{
                                        $onclick_onclick='';
                                     }
                                    echo tep_draw_textarea_field('t_c_reply_content', 'soft', '', '6','',' class="required textarea_hf_bt" id="t_companion_content" title="'.db_to_html('请输入回帖内容').'" '.$onclick_onclick.' onblur="'.$on_action.'" onkeydown="'.$on_action.'" onkeyup="'.$on_action.'" onmouseout="'.$on_action.'" ');

                                     
                                  ?></span>
                  </td>
                  </tr>
			  <tr>
				  <td colspan="2" height="25" align="left" valign="top" class="title_line2"><button id="re_submit_button" type="submit" class="jb_fb_all huibtn" <?= $button_onclick;?> ><?php echo db_to_html('回复')?>
                  </button>
				  <span id="tell_only" class="tell_only" style="width:auto;">
				  <?php echo tep_draw_checkbox_field('only_top_can_see','1',false,' id="only_top_can_see" ').db_to_html(' 只告诉楼主');?>
				  </span><p class="in_text" id="sms_info" style="margin:0"><?php echo db_to_html('你还可以输入<span>'.$max_input_string_num.'</span>字')?></p>
				  </td>
				</tr>
			  <?php if(!(int)$customer_id){/*用户登录字段?>
			  <tr>
				<td height="25" align="right" style="display:none">&nbsp;</td>
				  <td id="Login_tr_<?=(int)$rows['t_companion_id']?>" style="display:;">
					  <table border="0" cellpadding="0" cellspacing="0" style="width:500px">
						  
						  <tr>
							<td height="25" align="left" valign="top" class="title_line">
							<?php echo db_to_html('账号');?>
							<?php
							$mail_notes = "请输入您的账号";
							echo tep_draw_input_field('email_address',db_to_html($mail_notes),'class="required validate-email" title="'.db_to_html($mail_notes).'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" style="color:#BBBBBB"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); 
							?>
							</td>
				
							<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('密码')?>&nbsp;<input name="password" type="password" class="required" id="password" title="<?php echo db_to_html('请输入正确的密码')?>" /><span class="inputRequirement"> * </span><?php echo db_to_html('新用户请 <a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');?></td>
						  </tr>
				  </table>							</td>
				</tr>
			  
			  <?php */}?>
			  <tr>
				<td align="right" style="display:none">&nbsp;</td>
				  <td height="25" align="left" valign="top" class="title_line">
					<input name="t_companion_id" type="hidden" id="t_companion_id" value="<?= (int)$rows['t_companion_id']?>" />
					<input name="parent_id" type="hidden" id="parent_id" value="0" />
					<input name="parent_type" type="hidden" id="parent_type" value="0">
<?php
$button_onclick = '';
if(!(int)$customer_id){
	$button_onclick = 'onclick="check_login(\'travel_companion_tips_2065\',false);"';
}
?>
                  
				  </td>
				</tr>
			  </table>
			</div></td>
		</tr>
	</table>
	</div>
	</form>
    </div>
	<div class="del_float"></div>
	</div>
	<?php //快速回帖end?>

  </div>

<!--这个部分是在客户初次发帖后自己看到的-->
<?php
//取得出发日期与该贴子相近的10-15条贴子
if($send_done=='true' && $rows['hope_departure_date'] > '1970-01-01' ){
	//目录相近最好
	$similar_where .= ' AND hope_departure_date>"1970-01-01" ';
	$date_num = strtotime($rows['hope_departure_date']);
	$date_num_add = $date_num + (5 *24*60*60);
	$date_num_sub = $date_num - (5 *24*60*60);
	$add_final_date = date('Y-m-d', $date_num_add);
	$sub_final_date = date('Y-m-d', $date_num_sub);

	$similar_where .= ' AND hope_departure_date >= "'.$sub_final_date.'" AND  hope_departure_date <= "'.$add_final_date.'" ';
	
	$similar_sql = tep_db_query('SELECT * FROM `travel_companion` WHERE t_companion_id!="'.$rows['t_companion_id'].'" and `status`=1 '.$similar_where.' ORDER BY hope_departure_date LIMIT 10 ');
	$similar_row = tep_db_fetch_array($similar_sql);
	if((int)$similar_row['t_companion_id']){
		$total_sql = tep_db_query('SELECT count(*) as total FROM `travel_companion` WHERE t_companion_id!="'.$rows['t_companion_id'].'" and `status`=1 '.$similar_where.' LIMIT 10 ');
		$total_row = tep_db_fetch_array($total_sql);
?>
<div id="SimilarTcBBS" class="kuai mar-t commmend">
    <p class="cu"><?php echo db_to_html('与您期望出发日期相近的帖子')?>(<span style="color:#F1740E"><?= db_to_html($total_row['total']."个")?></span>)</p><!--条数我觉的就10~15条吧-->
<ul>
    <?php do{?>
	<li>
	<?php echo db_to_html('[出发日期 '. $similar_row['hope_departure_date'].'] '). '<a  href="'.tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$similar_row['t_companion_id'] .$TcPaStr).'" target="_blank">'.db_to_html(tep_db_output($similar_row['t_companion_title'])).'</a>';
	//目录
	$c_cate_name = tep_get_categories_name($similar_row['categories_id']);
	$c_cate_name = preg_replace('/ .+/','',$c_cate_name);
	if(tep_not_null($c_cate_name)){
		echo ' <span class="huise">['.db_to_html($c_cate_name).']</span>';
	}
	?>
	</li>
	<?php }while($similar_row = tep_db_fetch_array($similar_sql));?>                             
</ul> 
</div>
<?php
	}
}
?>


<?php
//载入发站内短信功能块
require_once('ajax_send_site_inner_sms.php');
?>