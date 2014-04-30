
<?php
//结伴同游BBS右页面头部公共文件 start
?>
	 <ul class="daohang_nav">
     <li><a class="bai" target="_top" href="<?= HTTP_SERVER ?>"><?php echo db_to_html('首&nbsp;页')?></a></li>
     <li><a class="bai" target="_top" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=24');?>"><?php echo db_to_html('美&nbsp;西')?></a></li>
     <li><a class="bai" target="_top" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=25');?>"><?php echo db_to_html('美&nbsp;东')?></a></li>
     <li><a class="bai" target="_top" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>"><?php echo db_to_html('夏威夷')?></a></li>
     <!--<li><a class="bai" target="_top" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=34');?>"><?php echo db_to_html('佛州欢乐游')?></a></li>-->
     <li><a class="bai" target="_top" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=54');?>"><?php echo db_to_html('加拿大')?></a></li>
     <li><a class="bai" target="_top" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=157');?>"><?php echo db_to_html('欧&nbsp;洲')?></a></li>
     <li><a class="bai" target="_top" href="<?=tep_href_link('booking.php');?>"><?php echo db_to_html('酒店预订')?></a></li>
     
	 <li style="float:right; display:block; padding-right:10px;">
	 <?php
	 if(!preg_match('/checkout\_/',$_SERVER['PHP_SELF'])){
	 	$tmp_echo = str_replace('English','',LANGUAGE_BUTTON);
	 	$tmp_echo = str_replace('&nbsp;|&nbsp;','',$tmp_echo);
		echo $tmp_echo;
	 }
	 ?>
	  </li>
     </ul>
     <p class="under_bar"><span style="float:left; padding-left:10px;"><a href="<?=tep_href_link('points.php');?>" target="_blank"><?php echo db_to_html('积分奖励')?></a></span><span style="float:right; padding-right:10px;" class="cu">888-887-2816<?php echo db_to_html('(美加免费)')?> 1-626-898-7800<?php echo db_to_html('(国际)')?> 0086-4006-333-926<?php echo db_to_html('(中)')?></span></p>
     
	 <div class="kuai"><div class="logo_tc"><a href="<?=tep_href_link('bbs_travel_companion.php');?>" target="_top"><img src="image/logo_tufeng.gif" /></a></div>
	 <form action="<?=tep_href_link('bbs_travel_companion_rightindex.php');?>" method="get" name="form_search" id="form_search" target="_self">
	 <div class="jiansuo">
	 <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td nowrap="nowrap">
		<span><?php echo db_to_html('出行日期')?></span>
		<input name="date_step" type="hidden" id="date_step" value="5">
		<?php //echo tep_draw_hidden_field('TcPath') ?>
		</td>
		<td nowrap="nowrap">
	<script type="text/javascript">
	 var Date_Search = new ctlSpiffyCalendarBox("Date_Search", "form_search", "search_date","btnDate3","<?php echo ($search_date); ?>",scBTNMODE_CUSTOMBLUE);
	 </script>
	 <script language="javascript">Date_Search.writeControl(); Date_Search.dateFormat="yyyy-MM-dd";</script>
	 </td>
		<td nowrap="nowrap"><span>&nbsp;<?php echo db_to_html('关键字')?></span>
		<?php echo tep_draw_input_field('tc_keyword','',' class="input_search2 search_tc_x"') ?>
		<?php echo tep_template_image_submit('sousuo_tc.gif', db_to_html('搜索'), 'style="padding-top:2px; padding-left:4px;"'); ?>
		</td>
	  </tr>
	</table>
	 </div>
	 </form>
	 </div>
	 
     <div class="kuai mar-t">
	 <?php
	 //目录导航
	 if(tep_not_null($TcPath)){
		
		$output_nav = 
		'<span class="dazi cu">'.trim($breadcrumb->_trail[(sizeof($breadcrumb->_trail)-1)]['title']).' |</span> ';
		
		for ($jib=0, $jnb=sizeof($breadcrumb->_trail); $jib<$jnb; $jib++) {
				if(($jnb-1) != $jib){							
					$output_nav .=  '<a href="' . $breadcrumb->_trail[$jib]['link'] . '" >' . trim($breadcrumb->_trail[$jib]['title']) . '</a>  &gt;  ';						
				
				}else{
					$output_nav .= trim($breadcrumb->_trail[$jib]['title']);						
				
				}
		}
		
		if($content == 'bbs_travel_companion_content'){
			$output_nav = str_replace('bbs_travel_companion_rightindex','bbs_travel_companion',$output_nav);
		}				
	 
	 	echo $output_nav;
	 
	 }else{
	 	if(!tep_not_null($customers_id)){
	 		$title_nav_text = '结伴同游';
		}else{
			$title_nav_text = tep_customers_name($customers_id).' 的帖子';
		}
	 ?>
	 <span class="dazi cu"><?php echo db_to_html($title_nav_text)?> |</span> 
	 <?php echo db_to_html('结伴同游')?> 
	 <?php
	 }
	 ?>
	 
<?php
if((int)$products_id){
	$products_name = tep_get_products_name($products_id, 1);
	echo '<div class="hot-jing mar-t cu" style="padding-left:10px;" title="'.db_to_html($products_name).'">'.cutword(db_to_html($products_name),860).'</div>';
}
?>

	 </div>

<?php 
//如果是在某人或产品的帖子列表则不显示以下内容 start
if(!tep_not_null($customers_id) && !(int)$products_id){
?>
	<?php
	//热点地区start
	//根据当前目录下面最热的贴子目录排序，选出最热的目录
	$max_strlen = 110;	//设置最多显示的字符数
	$now_strlen = 0;
	
	$top_c_id = preg_replace('/\_.*/','',$TcPath);
	$top_c_name = tep_get_categories_name($top_c_id);
	$hit_sql = tep_db_query('SELECT c.categories_id, count(tc.categories_id) as cate_cont FROM `categories` c, `travel_companion` tc WHERE tc.categories_id = c.categories_id AND c.parent_id="'.$top_c_id.'" Group By c.categories_id Order By cate_cont DESC Limit 20');
	$now_strlen+= strlen($top_c_name);
	$hit_rows=tep_db_fetch_array($hit_sql);
	if((int)$hit_rows['categories_id']){
	?>
			 <div class="kuai mar-t hot-jing"><p style="padding-left:10px"><span class="huise" style="padding-right:10px"><?php echo db_to_html($top_c_name.'结伴热点地区');?></span>
		<?php
		do{
			$link_text = preg_replace('/ .+/','',tep_get_categories_name($hit_rows['categories_id']));
			$now_strlen+= strlen($link_text)+1;	//是空格&nbsp;的占位数
			if($now_strlen<$max_strlen){
			$do_cate_patch = tep_get_category_patch($hit_rows['categories_id'])
		?>	 
			 <?php
			 if($do_cate_patch == $TcPath){
				echo db_to_html($link_text).'&nbsp;';
			 }else{
			 	$link_php_file = 'bbs_travel_companion_rightindex.php';
				if($content == 'bbs_travel_companion_content'){
					$link_php_file = 'bbs_travel_companion.php';
				}
			 ?>
				 <a href="<?=tep_href_link($link_php_file, 'TcPath='.tep_get_category_patch($hit_rows['categories_id']));?>"><?php
				 echo db_to_html($link_text);
				 ?></a>&nbsp;
			 <?php
			 }
			 ?>
		<?php
			}
		}while($hit_rows=tep_db_fetch_array($hit_sql));
		?>
			 
			 </p></div>
	<?php
	}
	//热点地区end
	?>
	
	<?php
	//目的地start
	$mudedi=false;
	if($mudedi==true){
	?>
		<div class="kuai mar-t all-mudidi "><div class="title_mudidid"><?php echo db_to_html('目<br />的<br />地')?></div>
			<div class="dise_all"><p style="padding:5px"><a>洛杉矶</a><b>拉斯维加斯</b><a>旧金山</a><a>黄石公园</a><a>大峡谷</a><a>西峡谷</a><a>优胜美地</a><a>主题乐园</a>  <a>迪斯尼乐园</a><a>环球影视城</a><a>海洋世界</a><a>那帕酒乡</a><a>17里海湾</a><a>萨克拉门托</a><a>鲍威尔湖</a><a>太浩湖</a><a>大提顿公园</a><a>拱门公园</a><a>死亡谷</a><a>总统巨石公园</a><a>雷诺</a><a>所夫昂</a><a>锡安公园</a>...</p><p class="more_mudidi"><a class="sp3">显示全部</a></p><div></div>
			</div>
		</div>
	
	<?php
	}
	//目的地end
	?>
<?php
}
//如果是在某人的帖子列表则不显示以下内容 end
?>
<?php
//结伴同游BBS右页面头部公共文件 end
?>