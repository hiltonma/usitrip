<?php
$no_show_cat_city_and_tours = false;

if(preg_match('/^24_/',$cPath)){	//西海岸下的子景点
	$no_show_cat_city_and_tours = true;
	$sql_query = tep_db_query('SELECT c.categories_id,cd.categories_name FROM `categories` c, `categories_description` cd WHERE c.parent_id = "24" AND c.categories_id =cd.categories_id  AND c.categories_status="1" order by c.categories_id ');
	while($rows=tep_db_fetch_array($sql_query)){
?>
	<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.$rows['categories_id']);?>"><?php echo db_to_html(preg_replace('/ .+/','',$rows['categories_name']).'旅游')?></a>
<?php
	}
}
?>

<?php
if(preg_match('/^25_/',$cPath)){	//东海岸下的子景点
	$no_show_cat_city_and_tours = true;
	$sql_query = tep_db_query('SELECT c.categories_id,cd.categories_name FROM `categories` c, `categories_description` cd WHERE c.parent_id = "25" AND c.categories_id =cd.categories_id  AND c.categories_status="1" order by c.categories_id ');
	while($rows=tep_db_fetch_array($sql_query)){
?>
	<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.$rows['categories_id']);?>"><?php echo db_to_html(preg_replace('/ .+/','',$rows['categories_name']).'旅游')?></a>
<?php
	}
}
?>

<?php
if(preg_match('/^54_/',$cPath)){	//加拿大的子景点
	$no_show_cat_city_and_tours = true;
	$sql_query = tep_db_query('SELECT c.categories_id,cd.categories_name FROM `categories` c, `categories_description` cd WHERE c.parent_id = "54" AND c.categories_id =cd.categories_id  AND c.categories_status="1" order by c.categories_id ');
	while($rows=tep_db_fetch_array($sql_query)){
?>
	<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.$rows['categories_id']);?>"><?php echo db_to_html(preg_replace('/ .+/','',$rows['categories_name']).'旅游')?></a>
<?php
	}
}
?>


<?php
if($no_show_cat_city_and_tours==false && (int)$cPath>0 || $content=='index_default'){//首页和目录页
?>
<span style="color:#627FAF"><?php echo db_to_html('出发城市：')?></span>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=29');?>"><?php echo db_to_html('洛杉矶旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=30');?>"><?php echo db_to_html('旧金山旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=32');?>"><?php echo db_to_html('拉斯维加斯旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=55');?>"><?php echo db_to_html('纽约旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=52');?>"><?php echo db_to_html('华盛顿旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=59');?>"><?php echo db_to_html('波士顿旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=148');?>"><?php echo db_to_html('温哥华旅游')?></a>

<?php
}//首页end
?>

<br>


<?php
if($no_show_cat_city_and_tours==false && (int)$cPath>0 || $content=='index_default'){//首页和目录页
?>

<span style="color:#627FAF"><?php echo db_to_html('热门景点：')?></span>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=35');?>"><?php echo db_to_html('黄石公园旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=142');?>"><?php echo db_to_html('大峡谷旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=48');?>"><?php echo db_to_html('优胜美地旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=41');?>"><?php echo db_to_html('主题公园旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=57');?>"><?php echo db_to_html('尼亚加拉瀑布旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=151');?>"><?php echo db_to_html('落基山旅游')?></a>
<a style="color:#627FAF" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>"><?php echo db_to_html('夏威夷旅游')?></a>
<?php
	if($content=='index_default'){	//只有首页才显示
?>
<br>
<span style="color:#627FAF"><?php echo db_to_html('合作链接：')?></span>
<a style="color:#627FAF" href="<?=tep_href_link('links.php', 'cId=1');?>"><?php echo db_to_html('合作1')?></a>
<a style="color:#627FAF" href="<?=tep_href_link('links.php', 'cId=2');?>"><?php echo db_to_html('合作2')?></a>
<a style="color:#627FAF" href="<?=tep_href_link('links.php', 'cId=3');?>"><?php echo db_to_html('合作3')?></a>
<a style="color:#627FAF" href="<?=tep_href_link('links.php', 'cId=4');?>"><?php echo db_to_html('合作4')?></a>
<a style="color:#627FAF" href="<?=tep_href_link('links.php', 'cId=5');?>"><?php echo db_to_html('合作5')?></a>

<?php
	}
}//首页end
?>

