<!--friend start-->
<?php
// get user friend list
if((int)$cser){ $tmp_cust_id = (int)$cser; }else{ $tmp_cust_id = (int)$customer_id; }
$user_friends_list = get_friends_list($tmp_cust_id, 10);
if(count($user_friends_list) && is_array($user_friends_list)){
?>
<div class="chanpin_list"><div  class="chanpin_list_title"><div class="geren_content_nav2"> 
        <ul>
           <li class="geren_content_nav_li4"><h3><a class="lanzi4"><?php echo db_to_html('好友列表')?></a></h3></li>
        </ul>
 </div></div><div class="chanpin_list_content">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
     
	 <?php
	 for($i=0; $i<count($user_friends_list); $i++){
	 	if($i>0 && $i%2==0){ echo '</tr><tr><td colspan="2" align="center">&nbsp;</td></tr><tr>';}
	 ?>
		<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td align="center" valign="middle">
			 
            <?php
	  $user_face = get_user_face($user_friends_list[$i]);
	  if(tep_not_null($user_face)){
	  	echo '<a class="huise" href="'.tep_href_link('user-space.php','cser='.(int)$user_friends_list[$i]).'" ><img src="images/face/'.$user_face.'" '.getimgHW3hw(DIR_FACE_FS_IMAGES.$user_face,60,60).' /></a>'; 
	  }else{?>
            <img src="image/touxiang.jpg" width="60" height="60" />
            <?php }?>
			 
			 </td>
           </tr>
           <tr>
             <td align="center" valign="middle"><a href="<?php echo tep_href_link('user-space.php','cser='.(int)$user_friends_list[$i]) ?>" class="huise"><?php echo db_to_html(get_user_nickname($user_friends_list[$i]))?></a></td>
           </tr>
         </table>
		</td>
     <?php }?>
     
	 </tr>
     <tr>
       <td colspan="2" align="center">&nbsp;</td>
      </tr>
   
   </table>
   </div>
</div>
<?php }?>
<!--friend end-->

<div class="chanpin_list"><div  class="chanpin_list_title"><div class="geren_content_nav2"> 
        <ul>
           <li class="geren_content_nav_li5"><h3><a href="#" class="lanzi4">周最热日志</a></h3></li>
        </ul>
 </div></div><div class="chanpin_list_content">
   <div class="chanpin_list_content_rizhi"><p><a href="#" class="cu" >美国的天空</a></span><br />
<span class="huise2">哈哈哈哈哈哈，哈哈哈哈哈哈哈</span><br />
<span class="huise">空中飞猪 | 阅读(100)</span></p></div>
<div class="chanpin_list_content_rizhi"><p><a href="#" class="cu" >美国的天空</a></span><br />
<span class="huise2">哈哈哈哈哈哈，哈哈哈哈哈哈哈</span><br />
<span class="huise">空中飞猪 | 阅读(100)</span></p></div>
<div class="chanpin_list_content_rizhi"><p><a href="#" class="cu" >美国的天空</a></span><br />
<span class="huise2">哈哈哈哈哈哈，哈哈哈哈哈哈哈</span><br />
<span class="huise">空中飞猪 | 阅读(100)</span></p></div>
 </div>
</div>
<div class="chanpin_list"><div  class="chanpin_list_title"><div class="geren_content_nav2"> 
        <ul>
           <li class="geren_content_nav_li6"><h3><a href="#" class="lanzi4">最新评论产品</a></h3></li>
        </ul>
 </div></div><div class="chanpin_list_content">
 <ul>
 <li>- <a href="#" class="lanzi4">美西12天的豪华经典游</a> <span class="sp1">￥20000</span></li>
 <li>- 美西12天的豪华经典游 <span class="jiage">￥20000</span></li>
 <li>- 美西12天的豪华经典游 <span class="jiage">￥20000</span></li>
 <li>- 美西12天的豪华经典游 <span class="jiage">￥20000</span></li>
 <li>- 美西12天的豪华经典游 <span class="jiage">￥20000</span></li>
 </ul>
 </div>
</div>
