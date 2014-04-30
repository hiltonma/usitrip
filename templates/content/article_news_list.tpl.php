<DIV class="product_content22" style="margin-top:5px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="74%"><div style="background:url(image/news_bg_bunch.gif); width:95%; height:25px;margin:10px 0px 8px 15px; "><div style=" background:url(image/news_title_icon.gif); height:25px; width:7px;float:left;"></div>&nbsp;<div style="font-size:14px; float:left;  font-weight:bold; margin-top:-8px; margin-left:6px; width:120px;"><?php echo db_to_html(tep_db_output($class1_row['class_name']));?></div><div style="width:100px;margin-left:550px; float:left; display:inline; margin-top:-15px;"><?php echo db_to_html('全部文章列表')?></div></div>
     <div style="margin-top:5px; width:95%; margin-left:15px; display:inline; float:left; padding-bottom:5px;">
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr><td style="height:5px;"></td></tr>
       
        <?php
		//取得新闻标题
		
		$news_sql_str = 'SELECT * FROM `seo_news` n , `seo_news_to_class` ntc WHERE n.news_id=ntc.news_id AND ntc.class_id="'.(int)$class1_row['class_id'].'" AND news_state="1" order by n.news_id DESC';
		$news_split = new splitPageResults($news_sql_str, 100);
    	$news_query = tep_db_query($news_split->sql_query);
		$news_row_cnts = 0;
		while($news_rows = tep_db_fetch_array($news_query)){
			$style_str ='';
			$news_row_cnts++;
			if($news_row_cnts%5==0){
				$style_str =' style="border-bottom:1px dashed #6f6f6f;" ';
			}
		?>
	   <tr><td width="2%" height="24" <?=$style_str?>>&#8231;</td>
         <td width="83%" class="dazi" <?=$style_str?>><a href="<?php echo tep_href_link('article_news_content.php','news_id='.(int)$news_rows['news_id'])?>" class="text" title="<?php echo db_to_html(tep_db_output($news_rows['news_title']))?>"><?php echo db_to_html(tep_db_output($news_rows['news_title']))?></a></td>
         <td width="15%" align="right" class="dazi" <?=$style_str?>><?php echo preg_replace('/[[:space:]]+.+$/','',$news_rows['news_add_date'])?></td>
       </tr>
	   <?php
	   }
	   ?>
       
       <tr><td style="height:5px;"></td></tr>
     </table>    
     </div>
    <div class="jianyi_title_content page_link" ><?php echo TEXT_RESULT_PAGE . ' ' . $news_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
	</div>
	
	</td>
    <td width="26%" valign="top" bgcolor="#EDF8FE">
	
	<?php /* 右边广告 
	<div style="float:left; margin-left:10px; margin-top:10px; width:225px;">这里全是广告。宽是225px，想要更多直接复制此容器，具体放什么印尼为不确定，我就不好做了，主张在考上的部分放个图片，之后放文字。
 </div><div style="float:left; margin-left:10px; margin-top:10px; width:217px; padding:4px; background:#fff; line-height:16px;">这里全是广告。宽是225px，文字的背景是白色的。还有4图元的内边距,行距是16px
 </div>
 	*/?>
 </td>
  </tr>
</table>

</DIV>
