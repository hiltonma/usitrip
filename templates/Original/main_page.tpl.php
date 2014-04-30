<?php
//Howard fixed by 2013-04-02 销售也可以进入用户中心了
if($is_my_account){
	if($Admin->check_allow_my_account() != true){
		echo db_to_html("您没有权限进入用户中心！");
		exit;
	}
	$can_views_pages = $Admin->can_views_page($_COOKIE['login_id']);
	if($can_views_pages && !in_array($content, (array)$can_views_pages)){
		exit(db_to_html("您没权限查看这个页面！"));
	}
}
function findFromUrl($url){
	$arr_tmp=parse_url($_SERVER['HTTP_REFERER']);
	$return=preg_match('/'.preg_quote($url).'$/', $arr_tmp['host']);
	return $return;
	
// 	return (count(explode($url, $_SERVER['HTTP_REFERER']))>1);
}
if(isset($_SERVER['HTTP_REFERER'])){
	if(!(findFromUrl('53kf.com')||findFromUrl('usitrip.com')||findFromUrl('qq.com')||findFromUrl('mytff.com'))){
	setcookie('url_from',$_SERVER['HTTP_REFERER'],time()+24*3600*30);
	}}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<?php if($content=='index_default'){?>
<html xmlns:wb="http://open.weibo.com/wb">
<?php }?>
<head>
<?php if($content=='index_default'){?>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
<?php }?>
<?php
/*我的走四方 - 新样式- 关闭导航显示隐藏日期选择层 -vincent */
if( $is_my_account){
	$BreadOff=true;
	$HideDateSelectLayer = true;
}
?>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

<?php


if ( file_exists(DIR_FS_INCLUDES . 'header_tags.php') ) {
  require(DIR_FS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE ?></title>
<?php
}

?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />

<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME.'/homepage-2012-1123-min.css?d=201306281339';?>" rel="stylesheet" type="text/css" />
<?php
$dont_use_merger =array(
	'account','account_edit','product_info','product_info_vegas_show','index_products','advanced_search_result','tours-experts'
);
/*if(USE_OLD_VERSION) { unset($dont_use_merger[0]);unset($dont_use_merger[1]);}*/
if(!in_array($content,$dont_use_merger ) && !defined('ONLY_USE_NEW_CSS')){	//产品详细页不显示旧css主文件
?>
	<link href="<?php echo TEMPLATE_STYLE;?>" rel="stylesheet" type="text/css" />
<?php }?>

<?php 
//我的走四方系列使用的CSS-vincent  @_@
//我的走四方 部分的所有页面都需要使用新的样式
/*
$myusitrip_filelist = array('account','account_edit','account_password','my_credits','account_history','new_orders','orders_travel_companion',
'address_book','eticket_list','orders_ask','my_favorites','my_coupon','my_points','refer_friends','points_actions_history','feedback_approval',
'my_points_help','points_terms','i_sent_bbs','i_reply_bbs','latest_bbs','affiliate_summary','affiliate_details','affiliate_details','affiliate_banners',
'affiliate_sales','affiliate_payment','affiliate_contact','affiliate_faq','account_newsletters','account_notifications
);
if(in_array($content , $myusitrip_filelist)){*/
if($is_my_account){?>
<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME.'/page_css/mytours.css'?>" rel="stylesheet" type="text/css" />
<?php }?>

<?php

if(strtolower(CHARSET)=='gb2312' && $content!="affiliate"){
?>
<style type="text/css"> body{font-family:Tahoma,SimSun,Arial,Helvetica,sans-serif;} </style>
<?php
}
?>
<?php
//将网站首页变为黑白
/*if($content=='index_default' && date('Y-m-d') < '2010-04-22' && CHARSET=='gb2312'){
	echo '<style>  html { filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); } </style> '; 
}*/


if(tep_not_null($other_css_base_name) && file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$other_css_base_name.'.css')){
	$_css_file = $other_css_base_name;
	if(file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$_css_file.'-min.css')){
		$_css_file .= '-min';
	}
?>
<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$_css_file.'.css?d=20131028'?>" rel="stylesheet" type="text/css" />
<?php
	$twcss = DIR_FS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$_css_file.'.tw.css';
	$wstwcss = DIR_WS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$_css_file.'.tw.css';
	if(strtolower(CHARSET)=="big5" && file_exists($twcss)){
		echo '<link href="'.$wstwcss.'" rel="stylesheet" type="text/css" />';
	}
}
?>

<link rel="shortcut icon" href="<?= IMAGES_HOST;?>/favicon.ico" type="image/x-icon" />
<?php
//注意：/affiliate_banners_public.php也用到此代码块 start {
$use_merger = true;
if($use_merger==true){// 合并后的JS文件
?>
<script type="text/javascript" src="<?= IMAGES_HOST;?>/jquery-1.3.2/merger/merger.min.js"></script>
<?php
}else{// 合并前的JS文件
?>
<script type="text/javascript" src="<?= IMAGES_HOST;?>/jquery-1.3.2/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?= IMAGES_HOST;?>/jquery-1.3.2/jquery.cookie.min.js"></script>
<script type="text/javascript" src="<?= IMAGES_HOST;?>/jquery-1.3.2/jquery.autocomplete.min.js"></script>
<?php
}
?>
<script type="text/javascript">
var serverDateTime = '<?php echo date('Y-m-d H:i:s');?>';
<?php /* 修正IE下选择下拉列表的Bugs */?>
jQuery().ready(function() {
	jQuery("select").change(function(){ document.body.focus(); });
});
<?php /* 修正IE下背景不缓存的问题 */?>
try {
  document.execCommand("BackgroundImageCache", false, true);
} catch(e) {}

</script>

<?php
//注意：/affiliate_banners_public.php也用到此代码块 end }

/*<script type="text/javascript" src="menujs-2008-04-15-min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-1.3.2/thickbox.min.css" />
*/
?>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>usitrip-tabs-2009-06-19-min.js?20120109" charset="gb2312"></script>
<script type="text/javascript" src="<?= IMAGES_HOST?>/spiffyCal/spiffyCal-v2-1-2009-05-11.js"></script>
<link rel="stylesheet" type="text/css" href="<?= IMAGES_HOST?>/spiffyCal/spiffyCal_v2_1.20100728.min.css" />
<?php if(preg_match('@usitrip.com.tw@',$_SERVER['HTTP_REFERER']) || $_GET['remove_html_id']){	//禁止usitrip.com.tw这个域名套框架(当前采取去头去尾操作)或如果url传递了remove_html_id参数就按删除此参数中提到的dom?>
<script type="text/javascript">
var __url = window.location.href;
if(window != parent){
	jQuery().ready(function() {
		<?php if(preg_match('@usitrip.com.tw@',$_SERVER['HTTP_REFERER'])){?>
		jQuery('#headTop,#footer').remove();
		<?php }?>
		<?php if($_GET['remove_html_id']){?>
		jQuery('#<?= str_replace(',',',#',strip_tags($_GET['remove_html_id']))?>').remove();
		<?php }?>
		<?php //给所有的a标签添加target="_blank"?>
		jQuery('a[href^="http:"]').attr('target',"_blank");
	});
	/* parent.navigate(__url); */
}

</script>
<?php }?>

<script type="text/javascript">
document.domain="<?php echo substr(HTTP_COOKIE_DOMAIN,1);?>";
</script>


<link rel="stylesheet" type="text/css" href="<?= IMAGES_HOST?>/jquery-1.3.2/jquery.autocomplete.min.css" />

<?php
$sc_tw = 'tw';
if($language=='schinese'){
	$sc_tw = 'sc';
}

?>

<?php
//require_once(DIR_FS_JAVASCRIPT.'global.js.php');
//echo $content;
//howard added Lightbox JS v2.0
if(!isset($Show_Calendar_JS)){
	$Show_Calendar_JS = "flase";
}

//产品详细页面用到的js文件
if($content=='product_info' || $content=='hotel' || $content=='products_departure_hotels' || $content=='product_info_vegas_show'){
	echo '<script language="JavaScript" src="'.IMAGES_HOST.'/jquery-1.3.2/scroll.js"  type="text/javascript"></script>'."\n";
	echo '<script language="JavaScript" src="'.IMAGES_HOST.'/jquery-1.3.2/jquery.lightbox-0.5.pack.js"  type="text/javascript"></script>'."\n";
	echo '<link type="text/css" rel="stylesheet" href="'.IMAGES_HOST.'/jquery-1.3.2/lightbox-0.5.css"/>'."\n";
	echo '<script language="JavaScript" src="'.IMAGES_HOST.'/includes/javascript/product_detail-min.js"  type="text/javascript"></script>'."\n";//2011-4-2 vincent
	$Show_Calendar_JS = "true";
}
if($content=='account_history_info'){
	$Show_Calendar_JS = "true";
}
//howard added Lightbox JS v2.0 end 
?>
<script type="text/javascript" src="<?= IMAGES_HOST?>/javascript.php?language=<?=$sc_tw?>&files=global&Show_Calendar_JS=<?= $Show_Calendar_JS;?>&customer_id=<?= $customer_id?>&cutofftime=20130608"></script>

<?php 
if (isset($javascript) && $javascript!='') {
	if(!preg_match('/\.js$/',$javascript)){
		$use_new_js = true;
	}
	if($use_new_js == true){
	  $js_url_parameters = NULL;
	  if(is_array($js_get_parameters)){
		$js_get_parameters[] = 'keyformatstring=md5md5md5';
		$js_url_parameters = '&parameters='.base64_encode(implode('&',$js_get_parameters));
	  }
	  
	  echo '<script type="text/javascript" src="'.IMAGES_HOST.'/javascript.php?language='.$sc_tw.'&files='.str_replace('.js.php','',basename($javascript)).$js_url_parameters.'&cutofftime=20130608"></script>'."\n";
	}else{
		require_once(DIR_FS_JAVASCRIPT . basename($javascript));
	}
}
?>
<?php if (isset($javascript_external) && file_exists(DIR_FS_JAVASCRIPT . basename($javascript_external))) { ?>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT.$javascript_external;?>"></script>
<?php } ?>
<script src="<?php echo DIR_WS_JAVASCRIPT;?>index-20130710-min.js" type="text/javascript"></script>
<?php
/* 表单验证代码 */
if ($validation_include_js=='true'){
	if($content=='account_edit_old' || $content=='account_password' || $content=='affiliate_contact'  || $content=='address_book_process'  || $content=='tour_question'  || $content=='checkout_payment' || $content=='checkout_info' || $content=='tour_lead_question' || $content=='orders_ask' || $validation_div_span =='span'){
		echo '<script type="text/javascript"> var validation_div_span = "span";</script>';
	}
?>
<script type="text/javascript">
var radio_error = "<?php echo RADIO_ERROR?>";
var select_option_error = "<?php echo SELECT_OPTION_ERROR?>";
</script>
<script type="text/javascript" charset="gb2312" src="<?php echo DIR_WS_JAVASCRIPT.VALIDATION_JS;?>"></script>
<?php
}

/*添加当前页独有的css文件*/
if(file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$content.'.css')){
	$_css_file = $content;
	if(file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$_css_file.'-min'.'.css')){
		$_css_file .= '-min';
	}
?>
<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/page_css/'.$_css_file.'.css?d=20131028'?>" rel="stylesheet" type="text/css" />
<?php
}
/*添加当前页独有的js文件*/
if(file_exists(DIR_FS_JAVASCRIPT.$content.'.js')){
	$_js_file = $content;
	if(file_exists(DIR_FS_JAVASCRIPT.$content.'-min'.'.js')){
		$_js_file .= '-min';
	}
	?>
	
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT.$_js_file ?>.js" charset="utf-8"></script>
<?php
}
?>
<?php if(strtolower(CHARSET)=='gb2312'){?>
<script type="text/javascript" src="<?= IMAGES_HOST;?>/big5_gb-min.js"></script>
<?php }else{?>
<script type="text/javascript" src="<?= IMAGES_HOST;?>/gb_big5-min.js"></script>
<?php }
?>
</head>
<?php 
if(!isset($body_onload_code)) $body_onload_code = '';
if(basename($PHP_SELF)=='index.php' || basename($PHP_SELF) == 'advanced_search_result.php' || basename($PHP_SELF) == 'hot-tours.php'  || basename($PHP_SELF) == 'panorama-tours.php' ){
	$body_onload_code .="initTab_search('navg','cont','click');";
}
if(basename($PHP_SELF)=='product_info.php' || basename($PHP_SELF)=='account_edit.php'){
	$body_onload_code .="initTab('tab'); showFlash();";
}

if(!isset($body_style)) $body_style = '';
if(in_array($content,array('checkout_confirmation','account_history_payment_checkout'))){
	$body_style = 'display:none';
}
?>
<body style=" <?php echo $body_style;?>" id="body" onload="<?php echo $body_onload_code ?>" >
<?php
$ClickTale = false;
if(IS_PROD_SITE=="1" && ( (basename($PHP_SELF)=='index.php' && $content=='index_default') || basename($PHP_SELF)=='create_account.php' || basename($PHP_SELF)=='login.php' || basename($PHP_SELF)=='new_travel_companion_index.php' ) ){
	//$ClickTale = true;
}
if($ClickTale == true){
//点击轨迹代码1 start
?>

<!-- ClickTale Top part -->
<script type="text/javascript">
var WRInitTime=(new Date()).getTime();
</script>
<!-- ClickTale end of Top part -->

<?php
}
//点击轨迹代码1 end
?>


<?php
 /*reg user google code*/ 
if($_SESSION['include_create_account_success']==1 && IS_PROD_SITE=="1"){
     echo '<iframe style="display:none;" src="'.tep_href_link('create_account_success.php').'">googlcode</iframe>';
     unset($_SESSION['include_create_account_success']);
}
?>

<?php //echo google_test_top(); ?>

<?php
$network_speed='fast';
?>
<?php if($network_speed=='slow'){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
<?php }?>
	
<div id="last_ajax_hash" style="display:none; height:0px; width:0px;"></div>
<?php require(DIR_FS_INCLUDES . 'warnings.php'); ?>

<!--ajax弹出层的背景
<table width="100%" id="bg" class="center_pop_bg" style="display:none;"><tr><td height="1000px">&nbsp;</td></tr></table>
-->
<!--弹出层的iframe--><!--此背景已经被禁用-->
<div style="display:none"><div id="popupBg" class="popupBg" style="z-index:9;"></div></div>

<?php
//普通提示层，全站适用
echo '<div id="GeneralNotes" class="center_pop_small" style="display:none; z-index:1000;">'.tep_pop_div_add_table('top').'<div><div id="GeneralNotesContent"></div></div>'.tep_pop_div_add_table('foot').'</div>';
//限时提示层，全站适用
echo '<div id="OutTimeNotes" class="center_pop_small" style="display:none; z-index:1000;">'.tep_pop_div_add_table('top').'<div id="OutTimeNotesContent"></div>'.tep_pop_div_add_table('foot').'</div>';

//Logo
$logoArray = load_logo();

?>
<div id="wrap" <?php echo $logoArray['wrap_style']; //请勿注释这句，否则假日背景无法显示！?>>

<?php 
//网站自动更新脚本检测 start
$site_update_notice = site_will_update_check();
if(tep_not_null($site_update_notice)){
?>
	<div id="ServerSuspendTip">
		<div class="con">
			<?= db_to_html($site_update_notice);?>
			<div class="close" onclick="jQuery('#ServerSuspendTip').slideUp();"></div>
		</div>
	</div>
<?php
}
//网站自动更新脚本检测 end
?>
   <div id="topHandInfo" class="topHandInfo">
   <div class="headInfo">
	<div style="float:right;width:510px;">
	<ul class="info">
   	  <!--<li class="told"><?php echo $old->output_from('index',db_to_html('旧版入口'),'goto_old_site');?></li>-->
      <li class="helpC"><a href="<?=tep_href_link('faq_question.php')?>"><?php echo db_to_html('帮助中心')?></a></li>
	  <?php
	 //简繁体按钮
	 if(!preg_match('/checkout\_/',$_SERVER['PHP_SELF']) && !preg_match('/advanced_search_result/',$_SERVER['PHP_SELF'])){ echo LANGUAGE_BUTTON; }
	 ?>
	  
	</ul>
	<ul class="login">
	<li class="user"><a href="<?php echo tep_href_link(FILENAME_ACCOUNT,"", "SSL") ?>" class="cur"><?php echo db_to_html('用户中心')?></a>
            <div class="user_pop" style="display:none;">
              <p> <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') ?>"><?php echo db_to_html('我的订单')?></a> <!--<a href="#">积分查询</a>--> <a href="<?php echo tep_href_link('my_favorites.php', '', 'SSL'); ?>"><?php echo db_to_html('我的收藏')?></a></p>
            </div>
	  </li>
	 <?php //以下为实时检查登录状态的代码?>
	  <li id="TopMiniLoginBox"><script type="text/javascript">jQuery('#TopMiniLoginBox').load(url_ssl('<?= tep_href_link_noseo("login.php","ajax=true&action=TopMiniLoginBoxCheck", "SSL");?>')+'&randnumforajaxaction='+Math.random());</script></li>
	</ul>
	</div>
	<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(".user").hover(
	function(){
		jQuery(this).children().eq(1).fadeIn(100);
		jQuery(this).children().eq(0).addClass("hover")
	},
	function(){
		jQuery(this).children().eq(1).fadeOut(100);
		jQuery(this).children().eq(0).removeClass("hover")
	}
		
	)						   
});

	</script>
	<div style="float:left;width:470px;">
      <ul class="info2">
        <li class="t1"><?= db_to_html("USItrip走四方旅游网-Your Trip , Our Care !");?></li>
        <li class="t2">
		<!--<a target="_blank" href="http://e.weibo.com/3223551651/profile" title="<?= db_to_html("新浪微博");?>"><img src="image/swb.gif" /></a></li>-->
        <!--<li class="t3"><a target="_blank" href="http://t.qq.com/usItrip2012" title="<?= db_to_html("腾讯微博");?>"><img src="image/twb.gif" /></a></li>-->
     	<span style="width:55px; height:25px"><wb:follow-button uid="3223551651" type="red_2" width="136" height="24" ></wb:follow-button></span>
	  </ul>
      </div>
  </div>
  </div>
  <div class="mask"></div>

	<div id="head">
	<?php
	require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/header.php');
	?>
	</div>
	

    
	<?php 
	/*var_dump($_GET['cPath']);
	var_dump(basename($PHP_SELF));*/
	if(basename($PHP_SELF)=='index.php' && (int)$_GET['cPath']>1){ ?>
	<div id="content" class="content_category">
    <span class="Corner TL"></span><span class="Corner TR"></span>
	<?php }else{
		if (basename($PHP_SELF)=='index.php' && $_GET['cPath'] == NULL) {
			echo '<div id="content">';
		} else {	
			echo '<div id="content">';
			if($off_corner_tl_tr != true){	 
				echo '<span class="Corner TL"></span><span class="Corner TR"></span>';
			}
		}
	} ?>	
	
	
    
	
		<?php
		//面包屑start{
		if($is_my_account){/*有关我的帐户里面的面包屑*/
			$link = tep_href_link(FILENAME_ACCOUNT);
			$link2 =tep_href_link(FILENAME_ACCOUNT,'','SSL');
			if($breadcrumb->exists($link)){
				$breadcrumb->edit($link ,db_to_html('我的走四方'));
			}elseif($breadcrumb->exists($link2)){
				$breadcrumb->edit($link2 ,db_to_html('我的走四方'));
			}else{
				$breadcrumb->add(db_to_html('我的走四方'),$link2,2);
			}
			$BreadTopLinksHtml = $breadcrumb->trail(' &gt; ');
		}

		if(tep_not_null($BreadTopLinksHtml)){
			echo '<div id="BreadTop" class="pathLinks">'.$BreadTopLinksHtml;
			if($content == 'new_travel_companion_index'||$content=='new_bbs_travel_companion_content')
			//以下文字暂时隐藏
			echo db_to_html('<!--<span style="width:auto;position:absolute;right:0;top:0;display:inline-block;color:#f60;padding-right:20px;background:#fff">走四方结伴同游官方QQ群：<b>282972788</b></span>-->');
			echo '</div>';
		}
		//面包屑end}
		?>
	
		<?php if($HideDateSelectLayer != true ){ ?>
		<div id="date_select_layer">
			<div id="spiffycalendar" style="z-index:1000;margin-left:-102px; position:absolute; background-color:#fff;"></div>
		</div> 
		<?php }?>

		 <?php 
		if($cat_mnu_sel == 'maps'){
		 ?>
		<div id="bubble_tooltip" style="z-index:50;">
			<div class="bubble_top"><span></span></div>
			<div class="bubble_middle"><span id="bubble_tooltip_content"></span></div>
			<div class="bubble_bottom"></div>
		</div>
		<?php } ?>
		
		
	<?php

	if($is_my_account){/*有关我的帐户里面的布局*/
		$close53kf = true; //关闭53KF
		require("account_left_column.php");
		//echo '</td><td valign="top" class="main">';
		
		$customer_validation_on_off = false;
		if($customer_validation=='1' || $customer_validation_on_off == false){
			//已认证客户
			if (isset($content_template) && file_exists(DIR_FS_CONTENT . basename($content_template))) {
				require(DIR_FS_CONTENT . basename($content_template));
			}else{
				$smarty_tplname = $content . '.tpl.htm';
				$not_show_tab = array('account_edit','my_travel_companion','affiliate_banners','affiliate_sales','affiliate_payment');
				if(file_exists(_SMARTY_TPL_ . $smarty_tplname)){
					require(_SMARTY_ROOT_."write_smarty_vars.php");
					echo '<div class="mytoursRight">';
					if(!in_array($content,$not_show_tab)){
						echo '<div class="userTitle"><ul><li class="cur">'.NAVBAR_TITLE_2.'</li></ul></div>';
					}
					$smarty->display($smarty_tplname);
					echo '</div>';
				}else{
					echo '<div class="mytoursRight">';
					if(!in_array($content,$not_show_tab)){
						echo '<div class="userTitle"><ul><li class="cur">'.NAVBAR_TITLE_2.'</li></ul></div>';
					}
					require(DIR_FS_CONTENT . $content . '.tpl.php');
					echo '</div>';
				}
			}
		}else{
			//未认证客户
			require(DIR_FS_CONTENT .'customers_validation.tpl.php');
		}
		
	}elseif($is_my_space){/*我的空间*/
		/*my space left column*/
		echo '<div class="leftside4"><div class="left_bg4">';
			//my space left title
			echo '<div class="bg3_title2"><h3>'.MY_TOURS.'</h3> <div class="bg3_shumin">'.WELCOME_YOU.'<span class="cu">'.db_to_html($first_or_last_name).'</span></div><div class="bg3_shumin"><span class="cu">'.HAVE_POINTS.$my_score_sum.' <img src="image/help.gif" /></span></div></div>';
			//my space left's left
			echo '<div class="bg3_left">';
			require("my_space_left_column.php");
			echo '</div>';
			//my space left's right
			echo '<div class="bg3_right2">';
				//left's right top
				echo '<div class="bg3_right_title"><div class="bg3_right_title_l"><span class="huise">'.SKIP_TO.'</span><a href="'.tep_href_link('account.php','','SSL').'" class="lanzi4">'.ACCOUNT_MANAGEMENT.'</a></div><div class="bg3_right_title_r">	<span class="huise">'.DEFAULT_STRING.'</span> <span class="cu">'.ACCOUNT_MANAGEMENT.'</span> | <a href="'.tep_href_link('my-space.php').'" class="lanzi4">'.MY_SPACE.'</a>  <span class="huise">'.SET_FOR_HOME.'</span></div></div>';
				//Navigation menu
				require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/navigation_menu_for_my_space.php');

				//content
				require(DIR_FS_CONTENT . $content . '.tpl.php');
				
			echo '</div>';
		echo '</div></div>';
		/*my space left column end*/		
		/*my space right column*/
		echo '<div class="right_bg3">';
		require("my_space_right_column.php");
		echo '</div>';
		/*my space right column end*/
		
	}elseif($is_user_space){
		/*is user space*/
		require(dirname(__FILE__)."/user_space_main.tpl.php");
		
	}elseif (isset($content_template) && file_exists(DIR_FS_CONTENT . basename($content_template))) {
		require(DIR_FS_CONTENT . basename($content_template));
	} else {/*普通页面布局*/
		if($add_div_footpage_obj==true){
			echo '<div id="footpage">';
		}
		$smarty_tplname = $content . '.tpl.htm';

		if(file_exists(_SMARTY_TPL_ . $smarty_tplname)){

			require(_SMARTY_ROOT_."write_smarty_vars.php");
			$smarty->display($smarty_tplname);
		}else{		
	
			require(DIR_FS_CONTENT . $content . '.tpl.php');
		}
		if($add_div_footpage_obj==true){
			echo '</div>';
		}

	}

	?>



	<div class="clear"></div>

	<?php 
	if ($content == 'order_process') $close_foot_tips = true;
	if($close_foot_tips!=true){?>
		
		<?php
		if($content!='product_info_vegas_show'){	//如果是show则不显示预订指南
		?>
        <!--{
		<div class="guid" id="Guid">
		<h4><?php echo db_to_html('预订指南')?></h4>
		<p><a href="<?=tep_href_link('tours-faq.php','information_id=42');?>#A" class="step1"></a><a href="<?=tep_href_link('tours-faq.php','information_id=42');?>#B" class="step2"></a><a class="step3" href="<?=tep_href_link('tours-faq.php','information_id=42');?>#C"></a><a class="step4" href="<?=tep_href_link('tours-faq.php','information_id=42');?>#D"></a></p>
		</div>
        }-->
        <!--预订流程修改 start-->
        <?php ob_start() ?>
        <div class="order-step">
            <h3 class="order-step-title" id="Guid">订购流程</h3>
            <ul class="order-step-list">
                <li class="os-item1"><a href="<?php echo tep_href_link('order_process.php')?>#o0">网上预定</a></li>
                <li class="os-item2"><a href="<?php echo tep_href_link('order_process.php')?>#o1">需求确定</a></li>
                <li class="os-item3"><a href="<?php echo tep_href_link('order_process.php')?>#o2">付款/签约</a></li>
                <li class="os-item4"><a href="<?php echo tep_href_link('order_process.php')?>#o3">出团通知</a></li>
                <li class="os-item5"><a href="<?php echo tep_href_link('order_process.php')?>#o4">开心游玩</a></li>
                <li class="os-item6"><a href="<?php echo tep_href_link('order_process.php')?>#o5">归来点评</a></li>
            </ul>
        </div>
        
        <?php echo db_to_html(ob_get_clean())?>
        <!--预订流程修改 end-->
		<?php
		}
		?>
	<?php
	}
	?>
</div>
    


	<div id="foot">
    <div class="in_foot">
	<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/footer.php');	?>
    </div>
	</div>
	<?php //返回顶部和购买导航按钮?>
	<div id="GoToTop" title="<?= db_to_html("回顶部")?>" onclick="scroll(0,0)"></div>
<?php //</div> 此标签貌似多余，先注释看看?>

<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>usitrip-tabs-bottom-2008-12-01.js"></script>
<?php if($network_speed=='slow'){?>	
	</td>
  </tr>
</table>
<?php }?>

<script type="text/javascript">
function hideen_messageStack(){
	setTimeout(function(){ 
		if(document.getElementById("messageStack")!=null){
			document.getElementById("messageStack").style.display='none';
		}
	},2000);
}
hideen_messageStack();
</script>

<?php echo google_test_bottom(); ?>
<?php
//小吴叫我加的日本团analysis
if(IS_PROD_SITE=="1"){}
?>
<?php
//中间弹出层对象
if(tep_not_null($PopupObj)){
	for($i=0; $i<count($PopupObj); $i++){
		echo $PopupObj[$i];
	}
}
?>
<?php
/*底部的JS代码*/
if(tep_not_null($WordPressLogin)){
	echo $WordPressLogin;
	tep_session_unregister('WordPressLogin');
}
?>
<?php
//在最后才显示的JS代码，一般用于外部网站的JS连接
//@author Howard

if(isset($endShowCodes)){
	echo $endShowCodes;
}

if(IS_PROD_SITE=="1"){
	/*谷歌Analytics和电子商务跟踪代码*/
	require(DIR_FS_INCLUDES . 'google_tracker_codes.php');
	//百度跟踪代码
?>
<div style="display:none">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F481c83edbcfea1442dc1221c1996da04' type='text/javascript'%3E%3C/script%3E"));
</script>
<?php
	//CNZZ跟踪代码 陈总叫加的，已经取消
	if(0 && $_SERVER['SERVER_PORT']!='443'){
?>
<script src="<?= $_SERVER['SERVER_PORT']=='443' ? 'https://' : 'http://';?>s95.cnzz.com/stat.php?id=4757044&web_id=4757044&show=pic1" type="text/javascript"></script>
<?php
	}
}
?>
</div>
</body>
<?php
//注意：以下53KF的位置不可变动要保持在</body>和</html>之间，否则会有弹不出自动询问框的问题！53的技术这样说的。
if(!preg_match('/^checkout_/',$content) && $close53kf != true && $_SERVER['SERVER_PORT']!='443' && IS_LIVE_SITES === true){ //载入53KF，由于53KF慢，所以在所有结账过程都不载入53KF
?>
<script src="http://chat.53kf.com/kf.php?arg=usitripkf&style=1" type="text/javascript"></script>
<?php }?>
<?php
if($is_my_account && in_array($Admin->get_groups_id($_COOKIE['login_id']), array(5,7,42,48))){//销售禁用所有按钮
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('input[type="submit"],input[type="button"],button').attr('disabled',true).attr('title',"当前禁止使用！");
	jQuery('input[type="text"],textarea').attr('readonly',true).attr('title',"当前禁止修改！");
});
</script>
<?php
}
?>
</html>