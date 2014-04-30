<?php /*echo tep_get_design_body_header(HEADING_TITLE,1);*/ ?>
<table border="0" width="99%" cellspacing="0" cellpadding="0">
<?php
//amit added to affiliate page navigation start
require('includes/affiliate_page_navi.php');
//amit added to affiliate page navigation end
?>
</table>
<div class="f-mytours-msgwrap">
	<div class="f-mytours-msghead">
    	<p><?= PAGE_HEADER_TEXT?></p>
    </div>
    <div class="f-mytours-msginfo">
    	<div id="J_Msgbox" class="f-mytours-msgtext">
            <h3><?= TEXT_AFFILATE_SUMMERRY_COMMISSION_INFO?></h3>
            <p><?= TEXT_AFFILATE_SUMMERRY_LI1?></p>
            <p><?= TEXT_AFFILATE_SUMMERRY_LI2?></p>
        </div>
        <div class="f-pullback-wrap">
        	<i id="J_pullback" class="f-pullback">收缩</i>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery('#J_pullback').toggle(function(){
	jQuery('#J_Msgbox').hide();
	jQuery(this).html('<?= db_to_html("展开")?>');	
},function(){
	jQuery('#J_Msgbox').show();
	jQuery(this).html('<?= db_to_html("收缩")?>');	
});
</script>
<div class="f-myunion-wrap">
	<div class="f-myunion-account">
    	<span><?= TEXT_GREETING?><strong><?= db_to_html(tep_customers_name($affiliate_id));?></strong></span><span><?= TEXT_AFFILIATE_ID?><strong><?= $affiliate_id;?></strong></span>
    </div>
    <div class="f-myunion-info">
    	<h3><a id="helpInfo" href="javascript:showPopup('popupInfo','popupConInfo','fixedTop','off','helpInfo','200','500');"><?php echo TEXT_SUMMARY; ?></a><?php echo TEXT_SUMMARY_TITLE; ?></h3>
        <div class="f-myunion-total">
        	<ul class="f-myunion-list">
            	<li><span><?= TEXT_IMPRESSIONS?></span> <?= $AffiliateInfo['affiliate_impressions']; ?></li>
                <li><span><?= TEXT_VISITS?></span> <?= $AffiliateInfo['affiliate_clickthroughs']; ?></li>
                <li><span><?= TEXT_TRANSACTIONS?></span> <?= $AffiliateInfo['affiliate_transactions'];?></li>
                <li><span><?= TEXT_CONVERSION?></span> <?= $AffiliateInfo['affiliate_conversions'];?></li>
                <li><span><?= TEXT_AMOUNT?></span> <?= $currencies->display_price($AffiliateInfo['affiliate_amount'], ''); ?></li>
                <li><span><?= TEXT_AVERAGE?></span> <?= $currencies->display_price($AffiliateInfo['affiliate_average'], ''); ?></li>
                <li class="last"><span><?= TEXT_COMMISSION_RATE?></span> <?= tep_round($AffiliateInfo['affiliate_percent'], 2). '%'; ?></li>
                <li class="last"><span><?= TEXT_COMMISSION_A; ?></span> <?= $currencies->display_price($AffiliateInfo['affiliate_commission'], ''); ?></li>
            </ul>
        </div>
    </div>
    <?php
		if(0){	//暂时隐藏
		 //推荐记录列表{?>
		<h2><?= TEXT_AFFILATE_SUMMERRY_YOUR_REFERRALS?></h2>
        
        <div class="salesHistory">
            <div class="title titleSmall">
                <b></b><span></span>
                <div class="col1"><?= TEXT_AFFILATE_SUMMERRY_REFERRAL_DATE;?></div>
                <div class="col2"><?= TEXT_AFFILATE_SUMMERRY_REFERRAL_EMAIL;?></div>
                <div class="col3"><?= TEXT_AFFILATE_SUMMERRY_SIGNUP;?></div>
                <div class="col4"><?= TEXT_AFFILATE_SUMMERRY_MADE_PURCHASE;?></div>
            </div>
            <div class="con">
            <ul>
		<?php
		
			$customer_referral_query_row = "select * from " . TABLE_REBATES_REFERRALS_INFO . " where customers_id=".$customer_id."  order by referrals_date desc" ;
			//$customer_referral_query_row = "select * from " . TABLE_REBATES_REFERRALS_INFO . " where 1  order by referrals_date desc" ;
			
			$products_new_split = new splitPageResults($customer_referral_query_row, MAX_DISPLAY_AFFILIATE_REFERRALS_REPORT);
			
			if ($products_new_split->number_of_rows > 0) {
				$customer_referral_query = tep_db_query($products_new_split->sql_query);
				
				while ($customer_referral = tep_db_fetch_array($customer_referral_query)) {
				
					$referrals_email = $customer_referral['referrals_email'];//推荐注册的邮箱
					// 从用户表中查找 推荐的用户 是否 注册
					$check_customer_query = tep_db_query("select customers_id, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($referrals_email) . "' ");
					if (tep_db_num_rows($check_customer_query) > 0 ) {
						$issignup = 'Y'; // 记录已注册状态
						if ($customer_check_done = tep_db_fetch_array($check_customer_query)) {
							$refferalid = $customer_check_done['customers_id'];	//得到注册后的用户ID			
						}
						// 如果这个用户已经注册，并且已经下了订单。
						$select_purchase_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS ." as o, " . TABLE_AFFILIATE_SALES . " as s where s.affiliate_orders_id = o.orders_id  and o.customers_id='".(int) $refferalid."'");
						if (tep_db_num_rows($select_purchase_query) > 0 ) {
							// 下单状态
							$ispurchase = 'Y';	
						}		
					}else{
						$issignup = 'N';
						$ispurchase = 'N';
					}
		?>			
				<li>
                    <div class="col1"><?php echo tep_date_short($customer_referral['referrals_date']); ?></div>
                    <div class="col2"><?php echo tep_db_output($customer_referral['referrals_email']); ?></div>
                    <div class="col3"><?php echo $issignup; ?></div>
                    <div class="col4"><?php echo $ispurchase; ?></div>
                </li>
		<?php 
				}
			} //end of if loop
		?>
            </ul>
            <div class="page" style="width:680px;">
		<?php
		  //分页
		  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
		  	echo TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links_2011(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')));
		  }
		?>
			
			</div>
            </div>
            </div>
        <?php
		 //推荐记录列表}
		}
		?> 
</div>

<?php //网站联盟账户总表帮助弹出层{?>
<?php ob_start();?>
<div class="popup" id="popupInfo">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConInfo" style="width:500px;">
    <div class="popupConTop" id="dragInfo">
      <h3><b>网站联盟帮助</b></h3><span onclick="closePopup('popupInfo')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" alt="关闭" title="关闭" /></span>
    </div>
    
    <ul class="f-myunion-poplist" id="J_popList">
        <li><b>广告显示总次数:</b>在给定的时间内广告条幅和链接展示的总次数。</li>
        <li class="even"><b>广告点击总次数:</b>通过您的网站，广告条幅及链接获得的总点击数。</li>
        <li><b>交易订单总数:</b>通过您的销售代理账户为走四方网带来的成功交易的总订单数。</li>
        <li class="even"><b>转换率:</b>您所获得的点击成功转换成订单的比率。</li>
        <li><b>销售总金额:</b>您为走四方网带来的销售订单的总金额。</li>
        <li class="even"><b>平均销售额:</b>根据您所带来的总订单数及总金额计算出的平均销售额。</li>
        <li><b>佣金比例:</b>您每为走四方网带来一个订单可以赚取的佣金比例。</li>
        <li class="even"><b>佣金总额:</b>您在走四方网上通过参加此销售代理联盟所赚取的佣金总额。</li>
    </ul>
   
  </div>
<script type="text/javascript">
jQuery('#J_popList li').hover(function(){
	jQuery(this).addClass('hover').siblings('li').removeClass('hover');	
});
</script>  
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>

<script type="text/javascript">
//设置弹出层顶部拖曳 
new divDrag([GetIdObj('dragInfo'),GetIdObj('popupInfo')]); 
</script>
<?php echo  db_to_html(ob_get_clean());?>
<?php //网站联盟账户总表帮助弹出层}?>
