<DIV class="product_content22" style="margin-top:5px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>

<?php
//top class
$class_sql = tep_db_query('SELECT * FROM `seo_class` WHERE parent_id =0  Order By class_id LIMIT 3');
while($class_rows = tep_db_fetch_array($class_sql)){
?>
    
	<td width="33%" height="58" valign="top"><div style="width:96%; border:1px solid #89D5FF; margin:10px 0px 5px 6px;"><div style=" background:url(image/title_bg_news.gif); height:35px; background-repeat:repeat-x; text-align:center;"><h2 style="padding-top:8px;"><?php echo db_to_html(tep_db_output($class_rows['class_name']))?></h2></div>
	<?php
	//class 1
	$class1_sql = tep_db_query('SELECT * FROM `seo_class` WHERE parent_id ="'.(int)$class_rows['class_id'].'" Order By class_id LIMIT 10');
	while($class1_rows = tep_db_fetch_array($class1_sql)){
	?>
	<div style=" width:95%; margin-left:8px; padding-bottom:4px;">
    <div style="width:95%; margin: 0px 0px 5px 8px; border-bottom:1px dashed #6f6f6f; padding-bottom:5px;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr><td width="73%"><p style="font-size:14px; font-weight:bold; margin-top:8px;"><?php echo db_to_html(tep_db_output($class1_rows['class_name']))?></p></td>
        <td width="27%" align="center" valign="bottom"><a href="<?php echo tep_href_link('article_news_list.php','class_id='.$class1_rows['class_id'])?>" class="text2">+<?php echo db_to_html('更多')?></a></td>
      </tr></table></div>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <?php
		//取得新闻标题
		$news_sql = tep_db_query('SELECT * FROM `seo_news` n , `seo_news_to_class` ntc WHERE n.news_id=ntc.news_id AND ntc.class_id="'.(int)$class1_rows['class_id'].'"  AND news_state="1" order by n.news_id DESC limit 5');
		while($news_rows = tep_db_fetch_array($news_sql)){
		?>
		<tr><td width="7%" height="23">&#8231;</td>
          <td width="93%" class="dazi"><a href="<?php echo tep_href_link('article_news_content.php','news_id='.$news_rows['news_id'])?>" class="text"><?php echo db_to_html(tep_db_output($news_rows['news_title']))?></a></td>
        </tr>
        <?php }?>
      </table>
      <div style="margin:12px 0px 15px 0px;"><!--广告图<img name="a" width="278" height="58" id="a" style="background-color: #CCECFF">-->&nbsp;</div>
      </div>
	<?php }?>    
    
	</div></td>
<?php }?>    
    
  </tr>
</table>
</DIV>
