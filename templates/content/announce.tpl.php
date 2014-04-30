<?php
ob_start();
if ( tep_not_null($topic['topics_heading_title']) ) {
	$titletagstr = $topic['topics_heading_title'];
} else {
	$titletagstr = '公告';//HEADING_TITLE;
}
 
?>
<style type="text/css">
.noticeList li {
    border-bottom: 1px dotted #CCCCCC;
    overflow: hidden;
    padding: 7px 0;
}
.noticeList li span {
    color: #777777;
    float: right;
}
.articleBox{padding:0 20px 20px;}
.articleH1{font-size:16px;font-weight:bold;color:#000;text-align:center;line-height:20px;padding:20px 0 10px;}
.articleInfo{padding:10px 0;text-align:center;border-bottom:1px dotted #ccc;color:#777;}
.articleContent{line-height:200%;min-height:300px;_height:300px;}
.articleContent p{margin-top:1.5em;} 
</style>
<?php
if (0 == $announce_id)
{
	echo tep_get_design_body_header($titletagstr); 
?>
<ul class="noticeList">
	<?php 
	for($i=0, $n=count($announce); $i<$n; $i++)
	{
	?>
	<li><a href="announce.php?id=<?php echo $announce[$i]['articles_id'];?>"><?php echo $announce[$i]['articles_name'];?></a><span><?php echo $announce[$i]['articles_date_added'];?></span></li>
	<?php 
	}
	?>	
</ul>
<?php
}
else
{
	if(is_array($announce_detail))
	{
?>
<div class="articleBox">
	<h1 class="articleH1"><?php echo $announce_detail['articles_name'];?></h1>
	<div class="articleInfo">发布日期：<?php echo $announce_detail['articles_date_added'];?></div>
	<div class="articleContent">
	<?php echo $announce_detail['articles_description'];?>
	</div>
</div>
<?php
	}

}
echo db_to_html(ob_get_clean());
?>

<?php echo tep_get_design_body_footer();?>