<?php
//取得走四方精品推荐
$rec_sql = tep_db_query('SELECT c.categories_id,c.categories_image, cd.categories_name, cd.categories_description FROM `categories` c, `categories_description` cd WHERE c.categories_id=cd.categories_id AND c.categories_id ="139" ');
$rec_row = tep_db_fetch_array($rec_sql);
//取得属於精品推荐的产品
$rec_prod_sql = tep_db_query('SELECT pd.products_name,pd.products_id, p.products_model FROM `products` p,  `products_description` pd,`products_to_categories` ptc WHERE p.products_id = ptc.products_id AND p.products_id = pd.products_id AND ptc.categories_id="'.(int)$rec_row['categories_id'].'" AND p.products_status="1" ORDER BY `products_id` DESC limit 40;');
$rec_prod_rows = tep_db_fetch_array($rec_prod_sql);
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
		<div class="leftside"> 
		  <?php
		  	$left_bg = 'left_bg';
			if((int)$_GET['usa_tours_info_id']){ $left_bg = 'left_bg2'; }
		  ?>
		  <div class="<?= $left_bg?>" style="margin-top:0px;"><h1><?php echo db_to_html(tep_db_output($info_rows['usa_tours_info_type_name']))?></h1>
			
			<?php if((int)$_GET['usa_tours_info_id']){?>
            <?php //内容部分?>

			  <h2><?php echo db_to_html(tep_db_output($info_rows['usa_tours_info_title']))?></h2>
			  <div class="text_gjz"><span><?php //echo db_to_html('文章关键字：')?></span></div>
			  <div class="clear"></div>
			  <p style="font-size:14px; color:#003366;  padding-top: 10px; padding-bottom: 10px;">
					<?php echo db_to_html(nl2br($info_rows['usa_tours_info_description']))?>
			  </p>
			
				<div class="chanpin_xg" style="margin-top:40px;"><h4><?php echo db_to_html('相关文章')?></h4>
					<ul>
						<?php
						//载入相关文章
						$sql = tep_db_query('SELECT * FROM `usa_tours_info` uti, `usa_tours_info_to_type` utt, `usa_tours_info_type` utit WHERE uti.usa_tours_info_id!='.(int)$_GET['usa_tours_info_id'].' AND utt.usa_tours_info_type_id='.(int)$info_rows['usa_tours_info_type_id'].' AND uti.usa_tours_info_id=utt.usa_tours_info_id AND utt.usa_tours_info_type_id=utt.usa_tours_info_type_id AND utit.usa_tours_info_type_id=utt.usa_tours_info_type_id Group By utt.usa_tours_info_id limit 100 ');
						while($rows = tep_db_fetch_array($sql)){
						?>
						<li><a href="<?php echo tep_href_link('usa-tours-info.php','usa_tours_info_id='.(int)$rows['usa_tours_info_id']) ?>" class="lanzi4"><?php echo db_to_html(tep_db_output($rows['usa_tours_info_title']))?></a> <span></span></li>
						<?php
						}
						?>
					</ul>
				</div>
				
			<?php //内容部分end?>
			<?php }elseif((int)$_GET['usa_tours_info_type_id']){?>
			<?php //目录部分?>
			
				<div class="chanpin_xg4"><h4><?php echo db_to_html('推荐行程')?></h4>
				<ul>
				<?php do{?>
					  <li><a class="text" title="<?php echo db_to_html(tep_db_output($rec_prod_rows['products_name']))?>" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $rec_prod_rows['products_id']);?>"><?php echo cutword(db_to_html(tep_db_output($rec_prod_rows['products_name'])),24)?></a></li>
				<?php }while($rec_prod_rows = tep_db_fetch_array($rec_prod_sql));?>
				</ul>
				</div>
			
				<span class="fengqing"><img src="image/<?php echo $info_rows['usa_tours_info_type_image1'] ?>" width="409" height="115" /></span>
				
					<div class="fengqing">
						<ul>
						
						<?php
						do{
						?>
						<li><a href="<?php echo tep_href_link('usa-tours-info.php','usa_tours_info_id='.(int)$info_rows['usa_tours_info_id']) ?>"  class="text"><?php echo db_to_html(tep_db_output($info_rows['usa_tours_info_title']))?></a></li>
						<?php
						}while($info_rows = tep_db_fetch_array($info_sql));
						?>
						
						</ul>
						<div class="jianyi_title_content page_link">
					   <?php
					   //显示翻页按钮
					   echo db_to_html($page_split_display);	   
					   ?>
						</div>
					</div>
				<?php //目录部分end?>
				<?php }?>
		  </div>
		</div>
	</td>
    <td valign="top">
		<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_right.php'); ?>	</td>
  </tr>
</table>


