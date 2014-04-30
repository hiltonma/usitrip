<script type="text/javascript"><!--
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

function DeleteBlog(b_id){
	var url = url_ssl("<?php echo tep_href_link_noseo('my-space-write-blog.php','action=delete_confirmation')?>")+'&blog_id=' +b_id;
	var truthBeTold = window.confirm("<?php echo db_to_html('真的删除该日志')?>");
	if (!truthBeTold){
	}else{
		document.location = url;
	}
}

//踩脚印
function FootPrints(){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('footprints_ajax.php','action=add_confirmation&blog_id='.(int)$blog_id))?>");
	
	ajax.open("GET", url, true);
	ajax.send(null); 
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			document.getElementById("FootprintsList").innerHTML = ajax.responseText;
		}
	}
}
--></script>


<?php
if($cser_row['customers_id']==$customer_id && $customer_id){
	$title = "我";
}elseif(tep_not_null($cser_row['user_nickname'])){
	$title = $cser_row['user_nickname'];
}elseif(tep_not_null($cser_row['customers_firstname'])){
	$title = $cser_row['customers_firstname'];
}
?>
<div class="kongjian_title_1"><span class="nav_kongjian margen_l">	
<?php echo db_to_html($title) ?> </span> <?php echo db_to_html('的空间')?></div>
<div class="fenlei_geren"><div class="fenlei_geren_head2"> <div class="geren_content_nav2"> 
          
           <span class=" dazi normal_text cu"> <?php echo db_to_html($title.'的日志') ?></span>
          
 </div>
 </div>
 <div class="rizhi_head_t">

<?php if($cser_row['customers_id']==$customer_id && $customer_id){?> 
 <a href="<?php echo tep_href_link('my-space-logs.php') ?>"><?php echo db_to_html($title.' 的全部日志')?></a> | <a href="<?php echo tep_href_link('my-space.php') ?>"><?php echo db_to_html('返回 '.$title.' 的空间')?></a>
 <?php }else{?>
 <a href="<?php echo tep_href_link('user-space-logs.php','cser='.(int)$cser_row['customers_id']) ?>"><?php echo db_to_html($title.' 的全部日志')?></a> | <a href="<?php echo tep_href_link('user-space.php','cser='.(int)$cser_row['customers_id']) ?>"><?php echo db_to_html('浏览 '.$title.' 的空间')?></a>
 <?php }?>
 
 </div>
 <div class="rizhi_timu3"><span class=" dazi cu"><a><?php echo db_to_html(tep_db_output($blog_row['blog_title'])) ?>
</a></span><br />
<span class="huise">

<?php if($cser_row['customers_id']==$customer_id && $customer_id){ // if customers_id is me show edit and delete button?>
<a href="<?php echo tep_href_link('my-space-write-blog.php','blog_id='.(int)$blog_row['blog_id']) ?>" ><?php echo db_to_html("编辑日志")  ?></a> | 
<a href="javascript:DeleteBlog('<?php echo (int)$blog_row['blog_id'] ?>')" class="huise_di"><?php echo db_to_html('删除')?></a>  |
<?php }?>

<?php echo db_to_html("（{$blog_row['blog_up_date']}）")  ?></span></div>
<div class="rizhi_center huise2"><?php echo nl2br(db_to_html(tep_db_output($blog_row['blog_description']))) ?></div>
</div>
<div class="rizhi_head_t2"><div class="bg3_right_title_l">

<span class="huise">
<?php
//显示阅读数或评论数
if($cser_row['customers_id']==$customer_id && $customer_id){
	echo db_to_html("阅读（{$blog_row['blog_clicks']}）| ");
}
echo  db_to_html("评论（{$blog_row['blog_comments']}）");
?>
</span>

</div><div class="bg3_right_title_r4"><div class="fenxiang"><a href="javascript:showDiv('popDiv')" class="fenxiang_zi"><?php echo db_to_html('分享')?></a></div></div>
</div>

<!--脚印-->
<div class="rizhi_timu3"><span class="cu"><?php echo db_to_html('脚印')?></span> </div>
	
<div class="jiaoying">
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top">
        <div id="FootprintsList" style="overflow: auto; width:420px; height:100px;">
		<?php
		//脚印
		$include = true;
		include('footprints_ajax.php');
		?>
		</div>
	  </td> 
	 
	  <?php //if($cser_row['customers_id']!=$customer_id){?>
	  
	  <td width="1%">&nbsp;</td>
	  <td width="16%" align="right" valign="top">
	    <a href="javascript:FootPrints()"><img src="image/buttons/tchinese/jiaoying_icon.gif" alt="<?php echo db_to_html('留脚印')?>" width="75" height="46" /></a>	  </td>
	  <?php //}?>
	</tr>
  </table>
</div><!--脚印 end-->


<?php
//评论
$comments_sql = tep_db_query('SELECT * FROM `blog_comments` WHERE  blog_id="'.(int)$blog_id.'" ORDER BY `blog_comments_date` DESC ');
$comments_rows = tep_db_fetch_array($comments_sql); 
if((int)$comments_rows['blog_comments_id']){
?>
<!--评论-->
<div class="rizhi_timu3"><span class="cu"><?php echo db_to_html('评论')?></span> </div>
	<?php do{?>
<div class="fenlei_geren">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="float:left">
    <tr>
      <td width="12%" align="center" valign="top" style="padding-top:10px;">
	  <?php
	  $user_face = get_user_face($comments_rows['customers_id']);
	  if(tep_not_null($user_face)){
	  	echo '<img src="images/face/'.$user_face.'" '.getimgHW3hw(DIR_FACE_FS_IMAGES.$user_face,60,60).' />'; 
	  }else{?>
	  <img src="image/touxiang.jpg" width="60" height="60" />
	  <?php }?>
	  </td>
      <td width="1%">&nbsp;</td>
  
<td width="84%">
  <div class="fenlei_geren_center"><p class="rizhi_timu">
  <span class="huise">
  <?php
  $user_nickname = '<a href="'.tep_href_link('user-space-logs.php','cser='.(int)$comments_rows['customers_id']).'" >'.db_to_html(get_user_nickname($comments_rows['customers_id'])).'</a> ';
  if(!tep_not_null(get_user_nickname($comments_rows['customers_id']))){
  	$user_nickname = db_to_html("过客");
  }
  echo $user_nickname;
  ?>
  | <?php echo db_to_html("（{$comments_rows['blog_comments_date']}）")?></span></p>
  <div class="rizhi_neirong">
    
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td ><p><span class="huise2"><?php echo nl2br(db_to_html(tep_db_output($comments_rows['blog_comments_text']))) ?></span></p>  </td>
  </tr>
  </table>
  </div></div></td></tr></table>
</div>
	<?php }while($comments_rows = tep_db_fetch_array($comments_sql));?>
<!--评论 end -->
<?php }?>

<form action="" method="post" enctype="multipart/form-data" name="comments_form">
<div class="jiaoying2"><p><?php echo db_to_html('发表评论')?></p>
<?php
if ($messageStack->size('submit_comments') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('submit_comments').'</div>';
}
?>
  <p><?php echo tep_draw_textarea_field('blog_comments_text', 'virtual','45','5', '', 'class="textarea4"') ?></p>
  <p><?php echo tep_template_image_submit('pingluen_icon.gif', 'Submit'); ?>
    <input name="customers_id" type="hidden" id="customers_id" value="<?php echo (int)$customer_id?>">
    <input name="blog_id" type="hidden" id="blog_id" value="<?php echo (int)$blog_id?>">
    <input name="action" type="hidden" id="action" value="submit_comments">
  </p>
</div>
</form>

<!--分享的层-->
<div id="popDiv" class="center_pop" style="display:<?php echo 'none'?>;" >
<?php echo tep_pop_div_add_table('top');?>
 <form action="" method="get" name="ShareForm" id="ShareForm">
    <div class="geren_content_pop2"><div class="bg3_right_title_r"><a  href="javascript:closeDiv('popDiv')" class="huise_di">关闭</a></div></div>
    <p style="clear:both; margin-left:35px; margin-bottom:5px; margin-top:-15px;">分享到自己的空间</p>
   <P style="clear:both;">
  <div class="geren_content_tit_pop" style="margin-left:0px;" >描述</div>
   <textarea name="textarea" id="textarea" cols="45" class="textarea_pop" rows="5" style="margin-top:-5px; margin-left:5px;" >分享功能暂未开发</textarea>
   </p>

    <div class="geren_content_pop"><div class="bg3_right_title_l "><img  src="image/buttons/tchinese/queding.gif" style="margen_b"/> &nbsp;<span style=" font-weight:normal;"><a href="#" class="huise">取 消</a></span></div></div>
    <div class="geren_content_pop3">
<p> <a href="#" class="cu">职业乞丐（转）</a><br />
<a href="#">肖凌</a><br />
<span class="huise" >我拎著刚买的levi’s从茂业出来，站在门口等一个朋友。一个职业乞丐发现</span></P>
<p><a href="#">阅读全文</a></p></div>
 </form>
<?php echo tep_pop_div_add_table('foot');?>
</div>

<!--分享的层 end-->
