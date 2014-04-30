<script type="text/javascript">
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
var old_show_id = "MyBlog";
function show_hidden(show_id){
	window.location.href = window.location.href.split("#")[0] + '#tag=' + show_id;
	//alert(window.location.href);
	
	if(document.getElementById(show_id)!=null){
		document.getElementById(old_show_id).style.display='none';
		document.getElementById(old_show_id+'Li').className='';
		old_show_id = show_id;
		document.getElementById(show_id).style.display='';
		document.getElementById(show_id+'Li').className='x';
	}else{
		show_hidden('MyBlog');
	}
}

function DeleteBlog(b_id){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('my-space-write-blog.php','action=delete_confirmation&blog_id=')) ?>") +b_id;
	var truthBeTold = window.confirm("<?php echo db_to_html('真的删除该日志')?>");
	if (!truthBeTold){
	}else{
		document.location = url;
	}
}

function DeleteFootPrints(f_id){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('my-space-logs-footprints-ajax.php','action=delete_confirmation&blog_footprints_id=')) ?>") +f_id;
	var truthBeTold = window.confirm("<?php echo db_to_html('真的删除该脚印')?>");
	if (!truthBeTold){
	}else{
		ajax.open("GET", url, true);
		ajax.send(null); 
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				document.getElementById("MyTrace").innerHTML = ajax.responseText;
			}
		}
	}
}

</script>


<?php
if ($messageStack->size('write_blog') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('write_blog').'</div>';
}
?>

<div class="geren_content_rizhi">   
  <div class="tab2" >
    <ul>
      <li id="MyBlogLi" class="x"><a href="javascript:show_hidden('MyBlog')"><?php echo db_to_html('我的日志')?></a></li>
        <li id="FriendNewBlogLi"><a href="javascript:show_hidden('FriendNewBlog')"><?php echo db_to_html('好友最新日志')?></a></li>
         <li id="MyTraceLi"><a href="javascript:show_hidden('MyTrace')" ><?php echo db_to_html('最近踩过的日志')?></a></li>
         <li id="MyDraftBoxLi"><a href="javascript:show_hidden('MyDraftBox')" ><?php echo db_to_html('草稿箱')?></a></li>
			  </ul>
	    </div>
        <div class="rizhi_content">

<?php
//get My Blog list
$MyBlog_sql = tep_db_query('SELECT * FROM `blog` WHERE customers_id="'.(int)$customer_id.'" AND blog_draft="0" Order By blog_up_date DESC ');
$MyBlogTotal = (int)tep_db_num_rows($MyBlog_sql);
?>	
	<!--My Blog-->	
		<div id="MyBlog" class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
          <?php echo db_to_html("共 $MyBlogTotal 篇日志")  ?>
          </div>
   <div  class="bg3_right_title_r3"><a href="<?php echo tep_href_link('my-space-write-blog.php') ?>" ><img src="image/buttons/tchinese/news_rizhi.gif" alt="<?php echo db_to_html("写新日志")  ?>" /></a></div></div>
  
  <?php while($MyBlog_rows = tep_db_fetch_array($MyBlog_sql)){?>
	  <div class="fenlei_geren_center"><p class="rizhi_timu"><span class=" dazi cu"><a href="<?php echo tep_href_link('my-space-write-blog.php','blog_id='.(int)$MyBlog_rows['blog_id']) ?>" ><?php echo tep_db_output($MyBlog_rows['blog_title']) ?></a></span><br />
	  <span class="huise"><a href="<?php echo tep_href_link('my-space-write-blog.php','blog_id='.(int)$MyBlog_rows['blog_id']) ?>" ><?php echo db_to_html("编辑日志")  ?></a> | <a href="javascript:DeleteBlog('<?php echo (int)$MyBlog_rows['blog_id'] ?>')" class="huise_di"><?php echo db_to_html('删除')?></a> |<?php echo db_to_html("（{$MyBlog_rows['blog_up_date']}）")  ?></span></p>
	  <div class="rizhi_neirong">
		
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
	  <td width="80%" ><p class="huise2"><?php echo cutword(strip_tags($MyBlog_rows['blog_description']),100)?></p>
	  <p><span class="huise"><a href="<?php echo tep_href_link('user-space-logs-details.php','cser='.(int)$customer_id.'&blog_id='.(int)$MyBlog_rows['blog_id'])?>"><?php echo db_to_html('阅读全文')?></a> | <a href="<?php echo tep_href_link('user-space-logs-details.php','cser='.(int)$customer_id.'&blog_id='.(int)$MyBlog_rows['blog_id'])?>"><?php echo db_to_html("评论（{$MyBlog_rows['blog_comments']}）")?></a> </span></p></td>
	  <td>
	<?php 
	if(tep_not_null($MyBlog_rows['blog_image'])){
		echo '<img src="images/blog/'.$MyBlog_rows['blog_image'].'" class="bg3_right_title_r3" '.getimgHW3hw(DIR_BLOG_FS_IMAGES.$MyBlog_rows['blog_image'],85,64).' />';
	}else{
		echo '&nbsp;';
	}
	?>

	  </td> </tr>
	  </table></div></div>
  <?php }?>
  </div>

	<!--My Blog end-->	

<!--FriendNewBlog-->  
<div id="FriendNewBlog" style="display:<?='none'?>" ><div class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
  共2篇日志
  </div>
   </div>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" style="float:left">
     <tr>
       <td width="12%" align="center" valign="top" style="padding-top:10px;"><img src="image/touxiang.jpg" width="60" height="60" /></td>
      <td width="1%">&nbsp;</td>
  
<td width="84%">
  <div class="fenlei_geren_center" style="display:<?='none'?>" ><p class="rizhi_timu"><span class=" dazi cu"><a href="#" >fgbdhdfgbdgb
  </a></span><br />
  <span class="huise">马爹利 | （08/09/20 15：40）</span></p>
  <div class="rizhi_neirong">
    
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td width="60%" ><p><span class="huise2">哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈</span></p>
  <p><span class="huise">阅读日志 | 	发表评论 </span></p></td>
  <td><img src="image/shili_pic.jpg" class="bg3_right_title_r3" /></td> </tr>
  </table></div></div></td></tr></table></div></div>
<!--FriendNewBlog end-->  

<!--My FootPrints-->            
<div id="MyTrace" style="display:<?='none'?>" >
<?php 
$include = true;
include('my-space-logs-footprints-ajax.php');
?>
</div>
<!--My FootPrints end -->		  
		  
<?php
//get My Draft list
$MyDraft_sql = tep_db_query('SELECT * FROM `blog` WHERE customers_id="'.(int)$customer_id.'" AND blog_draft="1" Order By blog_up_date DESC ');
$MyDraftTotal = (int)tep_db_num_rows($MyDraft_sql);
?>	
<!--My Draft-->
	<div id="MyDraftBox" style="display:<?='none'?>" ><div class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
	<?php echo db_to_html("共 $MyDraftTotal 篇草稿")?>
	</div>
	</div>
	
  <?php while($MyDraft_rows = tep_db_fetch_array($MyDraft_sql)){?>
	  <div class="fenlei_geren_center"><p class="rizhi_timu"><span class=" dazi cu"><a href="<?php echo tep_href_link('my-space-write-blog.php','blog_id='.(int)$MyDraft_rows['blog_id']) ?>" ><?php echo tep_db_output($MyDraft_rows['blog_title']) ?></a></span><br />
	  <span class="huise"><a href="<?php echo tep_href_link('my-space-write-blog.php','blog_id='.(int)$MyDraft_rows['blog_id']) ?>" ><?php echo db_to_html("编辑日志")  ?></a> | <a href="javascript:DeleteBlog('<?php echo (int)$MyDraft_rows['blog_id'] ?>')" class="huise_di"><?php echo db_to_html('删除')?></a> | <?php echo db_to_html("（{$MyDraft_rows['blog_up_date']}）")  ?></span></p>
	  <div class="rizhi_neirong">
		
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
	  <td width="80%" ><p class="huise2"><?php echo $MyDraft_rows['blog_description']?></p>
	  </td>
	  <td>
	<?php 
	if(tep_not_null($MyDraft_rows['blog_image'])){
		echo '<img src="images/blog/'.$MyDraft_rows['blog_image'].'" class="bg3_right_title_r3" '.getimgHW3hw(DIR_BLOG_FS_IMAGES.$MyDraft_rows['blog_image'],85,64).' />';
	}else{
		echo '&nbsp;';
	}
	?>

	  </td> </tr>
	  </table></div></div>
  <?php }?>
	
	</div></div>
<!--My Draft end-->

  </div>
</div>

<script type="text/javascript">
<?php
if(tep_not_null($tag)){
	echo 'show_hidden("'.$tag.'")';
}
?>

/* 根据不同的锚点显示相应的标签 start */
var LastHashLogs ="";
//var HashArray = new Array();
//var HashArrayKey = 0;

function CheckForHash_A(){
	if(document.location.hash){
		var HashLocationName = document.location.hash;
		HashLocationName1 = HashLocationName.replace("#","");
		if((HashLocationName1 != LastHashLogs) && HashLocationName1 != "" ){
										
				LastHashLogs = HashLocationName1;
				document.location.hash = HashLocationName1;
				//HashArray[HashArrayKey] = HashLocationName1;
				//HashArrayKey++;
				
				//alert(LastHashLogs);
				//if(LastHashLogs!=""){
					show_hidden(LastHashLogs.replace("tag=",""));
				//}
				//for(i=0; i<HashArray.length; i++){
					//alert(HashArray[i]);
				//}
		}
	}
}

var HashCheckInterval_A = setInterval("CheckForHash_A()", 20);
/* 根据不同的锚点显示相应的标签 end */

</script>