<?php 
	$favorites_num_query_str = 'SELECT count(*) AS total FROM `customers_favorites` cf, '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd WHERE customers_id ="'.(int)$customer_id.'" and pd.products_id = cf.products_id and cf.products_id=p.products_id and p.products_status="1" and pd.language_id="'.(int)$languages_id.'" Group BY p.products_id  ';
  	$favorites_num_query = tep_db_query($favorites_num_query_str);
	$favorites = tep_db_fetch_array($favorites_num_query);
	$favoritesTotal = intval($favorites['total']);
?>
<div class="bg3_left"><div class="my_zhanghu">
        <h3><?php echo db_to_html('帐户管理')?></h3><div  class="my_zhanghu_nav"><div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><b><?php echo MY_ACCOUNT_TITLE; ?></b></div>
        <ul>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') ?>" class="lanzi4"><?php echo MY_ACCOUNT_INFORMATION ?></a></li>
           
		   
		   <?php /* 暂时取消昵称
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="#" class="">昵称<span class="huise">(修改)</span></a></li>
		   */?>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') ?>" class=""><?php echo MY_ACCOUNT_PASSWORD ?></a></li>
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_MY_CREDITS, '', 'SSL') ?>" class=""><?php echo MY_ACC_LEFT_MNU_CREDITS; ?></a></li>
           </ul>
         </div>
         <div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><b><?php echo MY_ORDERS_TITLE; ?></b></div>
        <ul>
           <?php /*暂时取消积分查询
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="#" class="lanzi4">积分查询</a></li>
           */?>
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') ?>" class="lanzi4"><?php echo MY_ORDERS_VIEW ?></a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('new_orders.php', '', 'SSL') ?>" class="lanzi4"><?php echo NEW_ORDERS ?></a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('orders_travel_companion.php', '', 'SSL') ?>" class="lanzi4"><?php echo MY_TRAVEL_COMPANION_ORDERS ?><img src="image/new_icon.gif" /></a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') ?>" class="lanzi4"><?php echo MY_ACCOUNT_ADDRESS_BOOK ?></a></li>
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART) ?>" class="lanzi4"><?php echo HEADER_TITLE_CART_CONTENTS.' ['.$cart_sum.']'; ?></a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('eticket_list.php', '', 'SSL') ?>" class="lanzi4"><?php echo MY_ETICKET; ?></a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('orders_ask.php', '', 'SSL') ?>" class="lanzi4"><?php echo ORDERS_ASK; ?></a></li>
		   
           </ul>
         </div>
         
		 <?php //收藏夹?>
		 <div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><?php echo db_to_html("我的收藏")?></div>
        <ul>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('my_favorites.php', '', 'SSL'); ?>"  class="lanzi4"><?php echo db_to_html("行程收藏");if($favoritesTotal > 0) echo '['.$favoritesTotal.']';?></a>
		   </li>
           
           </ul>
         </div>

		 <!-- // Points/Rewards Module V2.1rc2a points_system_box_bof //-->
		<?php 	
			if($is_my_account == true && $customer_id != ''){
		?>
		 <div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><b><?php echo MY_POINTS_TITLE.'/'.MY_COUPONS; ?></b></div>
			<ul>
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_MY_COUPONS, '', 'SSL') ?>" class="lanzi4"><?php echo MY_COUPONS_MENU ?><img src="image/new_icon.gif" /></a></li>
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_MY_POINTS, '', 'SSL') ?>" class="lanzi4"><?php echo MY_POINTS_VIEW ?></a></li>			   			   
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_MY_POINTS, '', 'SSL') ?>" class="lanzi4"><?php echo REWARDS4FUN_ACTIONS_DESCRIPTION ?></a></li>			   
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_REFER_A_FRIEND, 'rewards4fun=true', 'SSL') ?>" class="lanzi4"><?php echo REWARDS4FUN_REFER_FRIENDS ?></a></li>			   
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_POINTS_ACTIONS_HISTORY, '', 'SSL') ?>" class="lanzi4"><?php echo REWARDS4FUN_ACTIONS_HISTORY ?></a></li>			   
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_FEEDBACK_APPROVAL, '', 'SSL') ?>" class="lanzi4"><?php echo REWARDS4FUN_FEEDBACK_APPROVAL ?></a></li>			   
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL') ?>" class="lanzi4"><?php echo MY_POINTS_VIEW_HELP ?></a></li>			   
			   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_REWARDS4FUN_TERMS, '', 'NONSSL') ?>" class="lanzi4"><?php echo REWARDS4FUN_TERMS_NAVI ?></a></li>
			</ul>
         </div>
 	   <?php 
		  }
		?>
		<!-- // Points/Rewards Module V2.1rc2a points_system_box_eof //-->

		 <div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><b><?php echo MY_TRAVEL_COMPANION; ?></b></div>
        <ul>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('my_travel_companion.php', '', 'NONSSL') ?>" class="lanzi4"><?php echo MY_TRAVEL_COMPANION; ?>

</a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('i_sent_bbs.php', '', 'SSL') ?>" class="lanzi4"><?php echo I_SENT_TRAVEL_COMPANION_BBS; ?>

</a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('i_reply_bbs.php', '', 'SSL') ?>" class="lanzi4"><?php echo I_REPLY_TRAVEL_COMPANION_BBS; ?>

</a></li>
           <!--<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('orders_travel_companion.php', '', 'SSL') ?>" class="lanzi4"><?php echo MY_TRAVEL_COMPANION_ORDERS ?><img src="image/new_icon.gif" /></a></li>-->
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link('latest_bbs.php', '', 'SSL') ?>" class="lanzi4"><?php echo LATEST_TRAVEL_COMPANION_BBS; ?>

</a></li>
          </ul>
         </div>
		 
          <div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><b><?php echo MY_REFERRALS_TITLE; ?></b></div>
        <ul>
           
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_SUMMARY ?>

</a></li>
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL') ?>" class="lanzi4"><?php echo MY_AFFILIATE_ACCOUNT_MAIN ?></a></li>
		   
		   <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_REFER_FRIENDS ?>

</a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'NONSSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_BANNERS ?>

</a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_SALES ?>
</a></li>

<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_PAYMENT ?>
</a></li>
<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'SSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_CONTACT ?>
</a></li>
<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_AFFILIATE_FAQ, '', 'NONSSL') ?>" class="lanzi4"><?php echo BOX_AFFILIATE_FAQ ?> 
</a></li><!--
<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php //echo tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL') ?>" class="lanzi4"><?php //echo BOX_AFFILIATE_CLICKRATE ?>
</a></li>
-->

<!--
<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php //echo tep_href_link(FILENAME_AFFILIATE_LOGOUT, '', 'SSL') ?>" class="lanzi4"><?php //echo BOX_AFFILIATE_LOGOUT ?>
</a></li>
-->

<?php /*暂时取消 培训指导 在线答疑
<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="#" class="lanzi4">培训指导
</a></li>
<li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="#" class="lanzi4">在线答疑
</a></li>
*/?>

           </ul>
         </div>
          
         
		 <div class="my_zhanghu_nav_fenlei"><div class="my_zhanghu_nav_1 cu"><b><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></b></div>
        <ul>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') ?>" class="lanzi4"><?php echo EMAIL_NOTIFICATIONS_NEWSLETTERS ?>

</a></li>
           <li><a onmouseout="this.className=''" onmouseover="this.className='left_nav_items_2'" href="<?php echo tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') ?>" class="lanzi4"><?php echo EMAIL_NOTIFICATIONS_PRODUCTS ?>

</a></li>
          </ul>
         </div>
		 
		 </div>
        
         </div>
           <div class="clear"></div></div>
