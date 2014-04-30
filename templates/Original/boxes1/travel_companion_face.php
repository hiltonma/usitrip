<?php
/**
 * 取得用户结伴同游的头像信息
 *
 * @param int $customers_id 需要获取的用户ID
 * @param int $bbs_id  如果是同伴同游的发起贴，则必传该贴的ID过来，否则不需要传递
 * @param string $customers_name 用户姓名[可选]
 * @param string $gender 用户性别 eg:(先生 或 女士) [可选] 
 * @param bool $only_face 是否只显示头像 [可选] 
 */
function get_travel_companion_face( $customers_id,  $customers_name = '', $gender = '',$bbs_id = 0 , $only_face = false){
	global $content,$customer_id;
	// $customer_id 当前登录的用户ID
	// $customers_id 
	if($customers_name==''){
		$customers_name = tep_customers_name($customers_id,1);
	}
	//注册时间
	$created_sql = tep_db_query('SELECT customers_info_date_account_created FROM `customers_info` WHERE customers_info_id ="'.(int)$customers_id.'" ');
	$created_row = tep_db_fetch_array($created_sql);
	$account_created = '';
	if(strtr($created_row['customers_info_date_account_created'], array('-'=>'',' '=>'',':'=>'')) > 0){
		$account_created .= chardate($created_row['customers_info_date_account_created'], "D", "1");
		//$account_created .= '注册';
	}
	//在线状态
	$customers_online_status = '离线';
	$online_sql = tep_db_query("SELECT customer_id from " . TABLE_WHOS_ONLINE . " where customer_id = '".$customers_id."' ");
	$online_row = tep_db_fetch_array($online_sql);
	if((int)$online_row['customer_id']){
		$customers_online_status = '在线';
	}
	
	//头像
	if(!tep_not_null($gender)){
		$gender = tep_customer_gender($customers_id);
	}
	$head_img = "touxiang_no-sex.gif";
	if(strtolower($gender)=='m' || $gender=='1'){ $head_img = "touxiang_boy.gif"; }
	if(strtolower($gender)=='f' || $gender=='2'){ $head_img = "touxiang_girl.gif"; }
	$head_img = 'image/'.$head_img;
	$head_img = tep_customers_face($customers_id, $head_img);
	
	//个人中心的连接
	if($content != 'individual_space'){
		$individual_space_links = tep_href_link('individual_space.php','customers_id='.$customers_id);
		$onclick = "";
	}else{
		$individual_space_links = 'JavaScript:void(0)';
		$onclick = "showDiv('travel_companion_tips_2064');";
	}
	// 按Sofia的意思 先去掉 积分
	$has_point = tep_get_shopping_points($customers_id);
?>
      <div class="jb_ljjb_tx ">	  
	  <?php if($only_face == false){?>
	  <div class="user_name"><?php 
	  echo db_to_html(tep_db_output($customers_name));
	  ?>&nbsp;<span class="col_3"><?php echo db_to_html($customers_online_status); ?></span></div>
	  <?php }?>
	  
	 <?php /* 隐藏相册 <a href="<?= $individual_space_links?>" class="t_c" onclick="<?= $onclick?>">*/ ?><img id="img_customers_face" src="<?= $head_img?>" <?php echo getimgHW3hw($head_img,131,121)?>  /><?php #</a>?>
	  
        <?php 
		// 先隐藏 用户的 相册 start{
		/*<p>
		<a href="<?= $individual_space_links?>" class="t_c" onclick="<?= $onclick?>" id="CustomersNameGender">
		<?php
		echo db_to_html(tep_db_output($customers_name));
		echo db_to_html(tep_get_gender_string($gender,1));
		?>
		</a>
		 <span class="col_3"><?php echo db_to_html($customers_online_status);?></span></p>*/
		 // 隐藏 用户的相册 end }
		 ?>
        <?php if($only_face == false){?>
		<ul>
		
        <?php // 积分暂隐藏起来 按Sofia的意思  start { ?>
			
        <li><?php echo db_to_html('积分：') . number_format($has_point,POINTS_DECIMAL_PLACES);?></li>
		<?php
		// 积分隐藏结束 end  }
		if(tep_not_null($account_created)){
		?>
        <li><?php echo db_to_html('注册：' . $account_created);?></li>
        <?php
		}?>

		<?php 
		 //自己浏览自己发布的结伴帖时，去掉“给他发信息”
		 if($customers_id!=$customer_id && $bbs_id != 0 && (int)$customers_id && (int)$customer_id){
		 ?>
		 <li><a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="show_site_inner_sms_layer(<?php echo $customers_id?>,'travel_companion', <?php echo $bbs_id?>)"><?php echo db_to_html('给他发信息')?></a></li>
		 <?php
		 }
		 // 如果是登录的帐号就是本人 则显示个人中心
		 /*if($customers_id == $customer_id) {
		 ?>
        <li><a href="<?= tep_href_link('individual_space.php','customers_id='.$customers_id)?>" class="t_c"><?= db_to_html('个人中心')?></a></li>
        <?php } */?>
      </ul>
	  	<?php }?>
	  
	  </div>

<?php
}
?>