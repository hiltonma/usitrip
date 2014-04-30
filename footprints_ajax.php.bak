<?php
require_once('includes/application_top.php');

if(!(int)$_GET['blog_id']){ exit; }else{ $blog_id=$_GET['blog_id'];}
function ajax_str($str){
	global $include;
	if($include!=true){
		return iconv(CHARSET,'utf-8'.'//IGNORE',$str);
	}else{
		return $str;
	}
}

if($include!=true && $_GET['action']=='add_confirmation'){
	//留脚印，过滤重复的IP ，用户名重复的脚印
	if((int)$customer_id){
		$check_sql = tep_db_query('SELECT blog_footprints_id FROM `blog_footprints` WHERE blog_id = "'.(int)$blog_id.'" AND customers_id="'.(int)$customer_id.'" limit 1');
	}else{
		$check_sql = tep_db_query('SELECT blog_footprints_id FROM `blog_footprints` WHERE blog_id = "'.(int)$blog_id.'" AND client_ip="'.get_client_ip().'" limit 1');
	}
	$check_row = tep_db_fetch_array($check_sql);
	
	if(!(int)$check_row['blog_footprints_id']){
		tep_db_query('INSERT INTO `blog_footprints` ( `blog_id` , `customers_id` , `blog_footprints_date` , `client_ip` ) VALUES ("'.(int)$blog_id.'", "'.(int)$customer_id.'", NOW(), "'.get_client_ip().'");');
		echo ajax_str(db_to_html('<div class="bg3_right_title_l">谢谢踩踏，留印成功！</div>'));
	}else{
		echo ajax_str(db_to_html('<div class="bg3_right_title_l">谢谢踩踏，不可重复留印！</div>'));
	}
}

//脚印
$footprints_sql = tep_db_query('SELECT * FROM `blog_footprints` WHERE blog_id="'.(int)$blog_id.'" ORDER BY blog_footprints_date DESC ');
$footprints_rows = tep_db_fetch_array($footprints_sql);
if((int)$footprints_rows['blog_footprints_id']){?>
		
	  <table>
		<tr>
		<?php do{?>
        <td>
		<div class="img_xiangce">
          <table border="0" cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" >
            
            <?php
	  $user_face = get_user_face($footprints_rows['customers_id']);
	  if(tep_not_null($user_face)){
	  	echo '<a class="huise" href="'.tep_href_link('user-space.php','cser='.(int)$footprints_rows['customers_id']).'" ><img src="images/face/'.$user_face.'" '.getimgHW3hw(DIR_FACE_FS_IMAGES.$user_face,60,60).' /></a>'; 
	  }else{?>
            <img src="image/touxiang.jpg" width="60" height="60" />
            <?php }?>
            
  </td>
    </tr>
            <tr>
              <td align="center"  >
                <?php
	$user_nickname = '<a class="huise" href="'.tep_href_link('user-space.php','cser='.(int)$footprints_rows['customers_id']).'" >'.ajax_str(db_to_html(get_user_nickname($footprints_rows['customers_id']))).'</a> ';
	if(!tep_not_null(get_user_nickname($footprints_rows['customers_id']))){
		$user_nickname = ajax_str(db_to_html("过客"));
	}
	echo $user_nickname;
	 ?>              </td>
       </tr>
          </table>
     </div>     </td>
	 <?php }while($footprints_rows = tep_db_fetch_array($footprints_sql));?>
        </tr>
	  </table>
	<?php }?>
	  