<div class="proLeft">
<?php
//结伴同游
if($cat_mnu_sel== 'vcpackages')$tabname='vp';
if($content=='index_products' || $content=='index_nested'){
	//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'attractions.php');
	//上面这个已经移到页面右边的筛选栏去了。
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'destinations.php');//按旅游景点查看
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'departures.php');//按出发城市查看
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'special.php');//本类特价
	
	$display_all_departure_city = true;//更多出发城市开关
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'departures.php');//更多出发城市
	//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_box.php'); 
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'advantages.php');//我们的优势
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'contact_us.php');//联系我们

}else{
	if($content=='advanced_search_result'){
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'tours_theme.php');
		$display_all_departure_city = true;//更多出发城市开关
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'departures.php');//更多出发城市
	}
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_box.php');
	
	
	//获得更多走四方超值产品
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'email.php');
	//销售排行榜
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'my_tours.php');
	
	//for other page
	
	//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'search2.php');
	//旅美常识 
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'tours_faq.php');
	//您浏览过的团 notOK
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'view_history.php');
	//特价团
	//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'special.php');
	//什么是达人？如何成为达人？达人的好处（快速注册连接）
	//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'create_account.php');
	
	//美国旅游须知
	//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'tours_info.php');
	//我们的优势
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'advantages.php');
	//走四方积分调查
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'vote_system.php');
	//联系我们
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'contact_us.php');
}

//小广告栏
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');
?>
</div>