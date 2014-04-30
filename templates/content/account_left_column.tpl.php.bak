<?php 
	$favorites_num_query_str = 'SELECT cf.customers_id FROM `customers_favorites` cf, '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd WHERE cf.customers_id ='.(int)$customer_id.'  and pd.products_id = cf.products_id and cf.products_id=p.products_id and p.products_status="1" and pd.language_id="'.(int)$languages_id.'" Group BY p.products_id  ';
  	$favorites_num_query = tep_db_query($favorites_num_query_str);
	$favorites = tep_db_num_rows($favorites_num_query);
	
	$favoritesTotal = intval($favorites);
	
	$itemDiv = array();
	$itemDiv["OrdersAdmin"] = "OrdersAdmin";
	$itemDiv["PersonalInformationManagement"] = "PersonalInformationManagement";
	$itemDiv["MyPointsAndCoupons"] = "MyPointsAndCoupons";
	$itemDiv["MyBuddies"] = "MyBuddies";
	$itemDiv["MyReferrals"] = "MyReferrals";
	$itemDiv["EmailNotifications"] = "EmailNotifications";
?><div class="mytoursLeft">
      <div class="title titleBig">
        <b></b><span></span>
        <h3><?php echo db_to_html('用户中心');?></h3>
      </div>
      <div id="<?= $itemDiv["OrdersAdmin"]?>" class="item">
        <h4><em></em><?php echo db_to_html("订单管理")?></h4><ul>
        <!--  <li><a href="<?php echo tep_href_link('new_orders.php', '', 'SSL') ?>" ><?php echo NEW_ORDERS ?></a></li> -->
        <!--  <li><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART) ?>" ><?php echo HEADER_TITLE_CART_CONTENTS.' ['.$cart_sum.']'; ?></a></li> -->
        <li><em></em><a id="<?= basename(FILENAME_ACCOUNT_HISTORY,".php"); ?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') ?>" ><?php echo db_to_html("我的订单")?></a></li>
        <li><em></em><a id="<?= basename('orders_travel_companion.php',".php"); ?>" href="<?php echo tep_href_link('orders_travel_companion.php', '', 'SSL') ?>"><?php echo MY_TRAVEL_COMPANION_ORDERS ?></a></li>
        <li><em></em><a id="<?= basename('eticket_list.php',".php"); ?>" href="<?php echo tep_href_link('eticket_list.php', '', 'SSL') ?>"><?php echo MY_ETICKET; ?></a></li>
		<li><em></em><a href="<?php echo tep_href_link('visa.php', 'action=viewMyVisaOrder', 'NONSSL') ?>" target='_blank'><?php echo db_to_html("签证订单"); ?></a></li>
		<?php /* 我的联系资料不要 我的咨询移到个人信息里面去了 
		<li><em></em><a id="<?= basename(FILENAME_ADDRESS_BOOK,".php"); ?>" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') ?>" ><?php echo db_to_html("我的联络资料") ?></a></li>
        <li><em></em><a id="<?= basename('orders_ask.php',".php"); ?>" href="<?php echo tep_href_link('orders_ask.php', '', 'SSL') ?>" ><?php echo ORDERS_ASK; ?></a></li>*/?>
      	</ul>
      </div>
      <!-- // Points/Rewards Module V2.1rc2a points_system_box_eof //-->
       <div id="<?= $itemDiv["MyBuddies"]?>" class="item">
        <h4><em></em><?php echo db_to_html('我的结伴同游')?></h4><ul>
        <li><em></em><a id="<?= basename('my_travel_companion.php',".php"); ?>" href="<?php echo tep_href_link('my_travel_companion.php', '', 'NONSSL') ?>"><?php echo db_to_html('我收到的信息')?></a></li>
        <li><em></em><a id="<?= basename('my_travel_companion.php',".php"); ?>" href="<?php echo tep_href_link('my_travel_companion.php', '', 'NONSSL') ?>#my_sent"><?php echo db_to_html('我发起的结伴同游')?></a></li>
        <!-- <li><a href="<?php echo tep_href_link('i_sent_bbs.php', '', 'SSL') ?>" ><?php echo I_SENT_TRAVEL_COMPANION_BBS; ?></a></li> -->
        <li><em></em><a id="<?= basename('my_travel_companion.php',".php"); ?>" href="<?php echo tep_href_link('my_travel_companion.php', '', 'NONSSL') ?>#my_applied"><?php echo db_to_html('我申请的结伴同游')?></a></li>
		<li><em></em><a id="<?= basename('i_reply_bbs.php',".php"); ?>" href="<?php echo tep_href_link('i_reply_bbs.php', '', 'SSL') ?>"><?php echo db_to_html('我回复的结伴同游')?></a></li>
		<!--  <li><a href="<?php echo tep_href_link('latest_bbs.php', '', 'SSL') ?>" ><?php echo LATEST_TRAVEL_COMPANION_BBS; ?></a></li> -->
      	</ul>
      </div>
      <div id="<?= $itemDiv["PersonalInformationManagement"]?>" class="item">
        <h4><em></em><?php echo db_to_html("个人信息管理")?></h4><ul>
        <li><em></em><a id="<?= basename(FILENAME_ACCOUNT_EDIT,".php"); ?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') ?>" ><?php echo db_to_html("基本信息") ?></a></li>
        <?php /*?><li><em></em><a id="<?= basename(FILENAME_ACCOUNT_EDIT,".php"); ?>_upload_avatar" href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, 'action=upload_avatar', 'SSL') ?>" ><?php echo db_to_html("上传头像") ?></a></li><?php */?>        
        <li><em></em><a id="<?= basename(FILENAME_ACCOUNT_EDIT,".php"); ?>_change_password" href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, 'action=change_password', 'SSL') ?>" ><?php echo db_to_html("修改密码") ?></a></li>
        <?php /*?><li><em></em><a id="<?= basename('orders_ask.php',".php"); ?>" href="<?php echo tep_href_link('orders_ask.php', '', 'SSL') ?>" ><?php echo ORDERS_ASK; ?></a></li> <?php */?>
     	<?php /*我的信用暂时不要?>
		<li><em></em><a id="<?= basename(FILENAME_MY_CREDITS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_MY_CREDITS, '', 'SSL') ?>" ><?php echo MY_ACC_LEFT_MNU_CREDITS; ?></a></li>
		<?php */?>
        <li><em></em><a id="<?= basename('my_favorites.php',".php"); ?>" href="<?php echo tep_href_link('my_favorites.php', '', 'SSL'); ?>"  ><?php echo db_to_html("我的收藏夹");if($favoritesTotal > 0) echo '['.$favoritesTotal.']';?></a></li>
        
        <li><em></em><a id="<?= basename(FILENAME_MY_COUPONS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_MY_COUPONS, '', 'SSL') ?>"><?php echo MY_COUPONS_MENU ?></a></li>
        
    	</ul>
    </div>
<!-- // Points/Rewards Module V2.1rc2a points_system_box_bof //-->
<?php 	
if($is_my_account == true && $customer_id != ''){	?>
     <div id="<?= $itemDiv["MyPointsAndCoupons"]?>" class="item">
        <h4><em></em><?php echo db_to_html("我的积分")?></h4><ul>
        <li><em></em><a id="<?= basename(FILENAME_MY_POINTS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_MY_POINTS, '', 'SSL') ?>"><?php echo db_to_html("我的积分概要")?></a></li>
        <li><em></em><a id="<?= basename('points_actions_history.php',".php"); ?>" href="<?php echo tep_href_link('points_actions_history.php', '', 'SSL') ?>" ><?php echo db_to_html("积分记录明细")?></a></li>
		<li><em></em><a id="<?= basename(FILENAME_REWARDS4FUN_TERMS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_REWARDS4FUN_TERMS, '', 'NONSSL') ?>"><?php echo db_to_html("积分规则")?></a></li>
        <li><em></em><a id="<?= basename(FILENAME_MY_POINTS_HELP,".php"); ?>" href="<?php echo tep_href_link(FILENAME_MY_POINTS_HELP, '', 'NONSSL') ?>"><?php echo MY_POINTS_VIEW_HELP ?></a></li>
		<!--<li><a id="<?= basename('points_actions_history.php',".php"); ?>" href="<?php echo tep_href_link('points_actions_history.php', '', 'NONSSL') ?>"><?php echo db_to_html("我已兑换的礼品")?></a></li>
		<li><em></em><a id="<?= basename('points_actions_description.php',".php"); ?>" href="<?php echo tep_href_link('points_actions_description.php')?>"><?php echo db_to_html("积分活动说明")?></a></li>
		<?php /*<li><em></em><a id="<?= basename(FILENAME_REFER_A_FRIEND,".php"); ?>_rewards4fun_true" href="<?php echo tep_href_link(FILENAME_REFER_A_FRIEND, 'rewards4fun=true', 'SSL') ?>"><?php echo REWARDS4FUN_REFER_FRIENDS ?></a></li> */?>
		<li><em></em><a id="<?= basename(FILENAME_FEEDBACK_APPROVAL,".php"); ?>" href="<?php echo tep_href_link(FILENAME_FEEDBACK_APPROVAL, '', 'SSL') ?>"><?php echo REWARDS4FUN_FEEDBACK_APPROVAL ?></a></li>-->
        <?php /*?><li><em></em><a id="<?= basename(FILENAME_MY_COUPONS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_MY_COUPONS, '', 'SSL') ?>"><?php echo MY_COUPONS_MENU ?></a></li><?php */?>
      	</ul>
      </div>
 <?php

 }?>

   
     <?php
	// AFFILIATE_SWITCH 数据库配置常量 网站联盟开关 'True' | 'False' by lwkai add
	 if ( strtolower(AFFILIATE_SWITCH) === 'true') { ?>
    <div id="<?= $itemDiv["MyReferrals"]?>" class="item">
		<h4><em></em><?php echo MY_REFERRALS_TITLE; ?></h4>
		<ul> <?php #网站联盟首页 老连接页面 partner.php ?>
		<li><em></em><a id="<?= basename('affiliate_my_info.php',".php"); ?>" href="<?php echo tep_href_link('affiliate_my_info.php', '', 'SSL') ?>" ><?php echo db_to_html("联盟账号信息") ?></a></li>
	    <li><em></em><a id="<?= basename(FILENAME_AFFILIATE_BANNERS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'NONSSL') ?>" ><?php echo BOX_AFFILIATE_BANNERS ?></a></li>
	    <!--
		<li><em></em><a id="<?= basename(FILENAME_AFFILIATE_SALES,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL') ?>" ><?php echo BOX_AFFILIATE_SALES ?></a></li>
	    -->
		<li><em></em><a id="<?= basename(FILENAME_AFFILIATE_PAYMENT,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL') ?>" ><?php echo db_to_html("结算中心") ?><?php //echo BOX_AFFILIATE_PAYMENT ?></a></li>
		
		<li><em></em><a id="<?= basename(FILENAME_AFFILIATE_FAQ,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_FAQ, '', 'NONSSL') ?>" ><?php echo BOX_AFFILIATE_FAQ ?></a></li>
		
		<!--
		<li><em></em><a id="<?= basename('affiliate.php',".php"); ?>" href="<?php echo tep_href_link('affiliate.php', '', 'NONSSL') ?>" ><?php echo db_to_html("网站联盟首页") ?></a></li>
		<li><em></em><a id="<?= basename(FILENAME_AFFILIATE_SUMMARY,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') ?>" ><?php echo BOX_AFFILIATE_SUMMARY ?></a></li>
		<li><em></em><a id="<?= basename(FILENAME_AFFILIATE_ACCOUNT,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL') ?>" ><?php echo MY_AFFILIATE_ACCOUNT_MAIN ?></a></li>
		<li><em></em><a id="<?= basename(FILENAME_REFER_A_FRIEND,".php"); ?>" href="<?php echo tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL') ?>" ><?php echo BOX_AFFILIATE_REFER_FRIENDS ?></a></li>
		
		
		<li><em></em><a id="<?= basename(FILENAME_AFFILIATE_CONTACT,".php"); ?>" href="<?php echo tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'SSL') ?>" ><?php echo BOX_AFFILIATE_CONTACT ?></a></li>
		<li><a  href="<?php //echo tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL') ?>" ><?php //echo BOX_AFFILIATE_CLICKRATE ?></a></li>-->
		<!--<li><a  href="<?php //echo tep_href_link(FILENAME_AFFILIATE_LOGOUT, '', 'SSL') ?>" ><?php //echo BOX_AFFILIATE_LOGOUT ?></a></li>-->
		<?php /*暂时取消 培训指导 在线答疑
		<li><a href="#" >培训指导</a></li>
		<li><a href="#" >在线答疑</a></li>
		*/?>
		</ul>
	</div>    <?php 
	}
	/* 按SOFIA 要求 暂不显示订阅管理 
	?>
     <div id="<?= $itemDiv["EmailNotifications"]?>" class="item">
		<h4><em></em><?php echo db_to_html('走四方信息订阅管理'); ?></h4><ul>
		<li><em></em><a id="<?= basename(FILENAME_ACCOUNT_NEWSLETTERS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') ?>" ><?php echo db_to_html("订阅走四方资讯") ?></a></li>
	    <li><em></em><a id="<?= basename(FILENAME_ACCOUNT_NOTIFICATIONS,".php"); ?>" href="<?php echo tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') ?>" ><?php echo EMAIL_NOTIFICATIONS_PRODUCTS ?></a></li>
		</ul>
	</div>
<?php */?>
  </div>
<script type="text/javascript">
<?php //显示或隐藏左边栏的项目并记录cookie{?>
jQuery(".item h4").click(function(){
	var parentId = jQuery(this).parent().attr('id');
	if(jQuery(".item ul:eq("+jQuery(".item h4").index(this)+")").css("display")=="none"){
		jQuery(this).removeClass("off");
		jQuery(".item ul:eq("+jQuery(".item h4").index(this)+")").slideDown();
		setCookie('AccountLeftColumnStatus_'+parentId, 'show', 100);
	}else{
		jQuery(this).addClass("off");
		jQuery(".item ul:eq("+jQuery(".item h4").index(this)+")").slideUp();
		setCookie('AccountLeftColumnStatus_'+parentId, 'hide', 100);
	}
});

<?php
foreach($itemDiv as $val){
?>
	if(getCookie('AccountLeftColumnStatus_<?= $val?>')=="hide"){
		jQuery("#<?= $val?> h4").addClass("off");
		jQuery("#<?= $val?> ul").slideUp(0);
	}
<?php
}
?>
<?php //显示或隐藏左边栏的项目并记录cookie}?>

<?php //自动将当前页面的菜单选项选中{ ?>
jQuery().ready(function(){
	<?php
	$_aid = basename($_SERVER['PHP_SELF'],".php");
	if($_aid == basename(FILENAME_ACCOUNT_EDIT,".php") && tep_not_null($_GET['action'])){
		$_aid.="_".$_GET['action'];
	}
	if($_aid == basename(FILENAME_REFER_A_FRIEND,".php") && tep_not_null($_GET['rewards4fun']) && $_GET['rewards4fun']=="true"){
		$_aid.="_rewards4fun_true";
	}
	?>
	var _aid = "<?= $_aid?>";
	jQuery("#"+_aid).addClass("selected");
	
});
<?php //自动将当前页面的菜单选项选中} ?>

	
</script>