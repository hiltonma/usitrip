<?php //echo tep_get_design_body_header(HEADING_TITLE,1); ?>
<!-- content main body start -->
<style type="text/css">
h2{ line-height:24px;}
.pointsIntro li{ list-style:disc inside none; padding-left:15px; color:#F7860F; line-height:18px;}
</style>
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3" style="border:1px solid #AED5FF">
  <tr>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <?php /* ?><tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo HEADING_TITLE; ?></b></td>
            <td class="main" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'money.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr><?php */ ?>
        <tr>
          <td><?php
		//require('includes/rewards4fun_page_navi.php');		
		?>
          </td>
        </tr>
        <?php
 //howard added rewards4fun banner
 //if(!(int)$has_point){
 	require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_JOIN_REWARDS4FUN);
 ?>
        <tr>
          <td><div>
              <?php if($customer_validation!='1'){?>
              <?php
		require(DIR_FS_CONTENT .'customers_validation.tpl.php');		
		?>
              <?php }?>
            </div>
            <?php /*<div><img src="image/banner_logo/<?php echo $language ?>/jifen_f.jpg" alt="Affiliate, travel" style="margin-top:10px;" /></div> */ //注释了这个广告 ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" padding:15px 0 10px; font-size:14px;">
              <tr>
                <?php
		$has_point = tep_get_shopping_points($customer_id);
		if ($has_point > 0){
		?>
                <td class="main" style=" font-size:14px;"><?php echo sprintf(MY_POINTS_CURRENT_BALANCE, number_format($has_point,POINTS_DECIMAL_PLACES),$currencies->format(tep_calc_shopping_pvalue($has_point))); ?>
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo db_to_html('将要过期的积分');?> <b style="color:#FF0000">0</b><?php echo db_to_html('（一个月内）');?>
				
				</td>
                <?php
		}else{
		?>
                <td class="main" style=" font-size:14px;"><b> <?php echo TEXT_NO_POINTS?> </b></td>
                <?php 
		}
		?>
              </tr>
            </table>
            <div class="title titleSmall"> <b></b><span></span>
              <h3><?php echo TEXT_INTRO_STRING_TOP; ?></h3>
            </div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><?php
				$pending_points_query = "select unique_id, orders_id, points_pending, points_comment, date_added, points_status, points_type, feedback_other_site_url, products_id, admin_id from " . TABLE_CUSTOMERS_POINTS_PENDING . " where customer_id = '" . (int)$customer_id . "' order by unique_id desc limit 5";
				//$pending_points_split = new splitPageResults($pending_points_query, MAX_DISPLAY_POINTS_RECORD);
				//$pending_points_query = tep_db_query($pending_points_split->sql_query);
				$pending_points_query = tep_db_query($pending_points_query);
				
				if (tep_db_num_rows($pending_points_query)) {
				?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox">
                    <tr class="productListing-heading">
                      <td class="productListing-heading"><?php echo HEADING_ORDER_DATE; ?></td>
                      <td class="productListing-heading" width="10%"><?php echo HEADING_ORDERS_NUMBER; ?></td>
                      <td class="productListing-heading"><?php echo HEADING_POINTS_COMMENT; ?></td>
                      <td class="productListing-heading"><?php echo HEADING_POINTS_STATUS; ?></td>
                      <td class="productListing-heading" align="right"><?php echo HEADING_POINTS_TOTAL; ?></td>
                    </tr>
                    <tr>
                      <?php
				while ($pending_points = tep_db_fetch_array($pending_points_query)) {
				$orders_status_query = tep_db_query("select o.orders_id, o.orders_status, s.orders_status_name_1 from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = '" . (int)$pending_points['orders_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'");
				$orders_status = tep_db_fetch_array($orders_status_query);
				
				if ($pending_points['points_status'] == '1') $points_status_name = TEXT_POINTS_PENDING;
				if ($pending_points['points_status'] == '2') $points_status_name = TEXT_POINTS_CONFIRMED;
				if ($pending_points['points_status'] == '3') $points_status_name = '<span class="pointWarning">' . TEXT_POINTS_CANCELLED . '</span>';
				if ($pending_points['points_status'] == '4') $points_status_name = '<span class="pointWarning">' . TEXT_POINTS_REDEEMED . '</span>';
				
				if ($orders_status['orders_status'] == 2 && $pending_points['points_status'] == 1 || $orders_status['orders_status'] == 3 && $pending_points['points_status'] == 1) {
				$points_status_name = TEXT_POINTS_PROCESSING;
				}
				
				if (tep_not_null($pending_points['points_comment']) && defined($pending_points['points_comment'])) {
					$pending_points['points_comment'] = constant($pending_points['points_comment']);
				}
				/*
				if (($pending_points['points_type'] == 'SP') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_COMMENT')) {
				$pending_points['points_comment'] = TEXT_DEFAULT_COMMENT;
				}
				if (($pending_points['points_type'] == 'TP') && ($pending_points['points_comment'] == 'USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT')) {
				$pending_points['points_comment'] = USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT;
				}
				
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REDEEMED') {
				$pending_points['points_comment'] = TEXT_DEFAULT_REDEEMED;
				}
				if ($pending_points['points_comment'] == 'TEXT_WELCOME_POINTS_COMMENT') {
				$pending_points['points_comment'] = TEXT_WELCOME_POINTS_COMMENT;
				}
				if ($pending_points['points_comment'] == 'TEXT_VALIDATION_ACCOUNT_POINT_COMMENT') {
				$pending_points['points_comment'] = TEXT_VALIDATION_ACCOUNT_POINT_COMMENT;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS_PHOTOS') {
				$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS_PHOTOS;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_FEEDBACK_APPROVAL') {
				$pending_points['points_comment'] = TEXT_DEFAULT_FEEDBACK_APPROVAL;
				}
				if ($pending_points['points_comment'] == 'TEXT_DEFAULT_ANSWER') {
				$pending_points['points_comment'] = TEXT_DEFAULT_ANSWER;
				}
				//会员积分卡 begin
				if ($pending_points['points_comment'] == 'TEXT_POINTCARD_REGISTER') {
				$pending_points['points_comment'] = TEXT_POINTCARD_REGISTER;
				}
				if ($pending_points['points_comment'] == 'TEXT_POINTCARD_PROFILE') {
				$pending_points['points_comment'] = TEXT_POINTCARD_PROFILE;
				}
				if ($pending_points['points_comment'] == 'TEXT_POINTCARD_LOGIN') {
				$pending_points['points_comment'] = TEXT_POINTCARD_LOGIN;
				}
				//会员积分卡 end
				*/
				$referred_customers_name = '';
				if ($pending_points['points_type'] == 'RF') {
					$referred_name_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$pending_points['orders_id'] . "' limit 1");
					$referred_name = tep_db_fetch_array($referred_name_query);
					/*if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REFERRAL') {
						$pending_points['points_comment'] = TEXT_DEFAULT_REFERRAL;
					}*/
					$referred_customers_name = $referred_name['customers_name'];
				}
				/*
				if (($pending_points['points_type'] == 'RV') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS')) {
				$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS;
				}
				if(($pending_points['points_type'] == 'VT') && ($pending_points['points_comment'] == 'TEXT_VOTE_POINTS_COMMENT')){
				$pending_points['points_comment'] = TEXT_VOTE_POINTS_COMMENT;
				}
				*/
				if (($pending_points['orders_id'] > '0') && (($pending_points['points_type'] == 'SP')||($pending_points['points_type'] == 'RD'))) {
				?>
                    <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $pending_points['orders_id'], 'SSL'); ?>'" title="<?php echo TEXT_ORDER_HISTORY .'&nbsp;' . $pending_points['orders_id']; ?>">
                      <?php
				}
				
				if ($pending_points['points_type'] == 'RV') {
				?>
                    <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pending_points['products_id'].'&mnu=reviews', 'NONSSL'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
                      <?php
				}
				
				if ($pending_points['points_type'] == 'PH') {
				?>
                    <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pending_points['products_id'].'&mnu=photos', 'NONSSL'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
                      <?php
				}
				if ($pending_points['points_type'] == 'FA') {
				?>
                    <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="window.open('<?php echo $pending_points['feedback_other_site_url']; ?>');" title="">
                      <?php
				}
				if ($pending_points['points_type'] == 'QA') {
				/*$get_products_id = tep_db_query("select products_id from ".TABLE_QUESTION." where que_id='".$pending_points['orders_id']."'");
				$row_products_id = tep_db_fetch_array($get_products_id);*/
				?>
                    <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pending_points['products_id'].'&mnu=qanda', 'NONSSL'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
                      <?php
				}
				
				if (($pending_points['orders_id'] == 0) || ($pending_points['points_type'] == 'RF') || ($pending_points['points_type'] == 'RV')  || ($pending_points['points_type'] == 'PH') || ($pending_points['points_type'] == 'QA')) {
				$orders_status['orders_status_name_1'] = '<span class="pointWarning">' . TEXT_STATUS_ADMINISTATION . '</span>';
				$pending_points['orders_id'] = '<span class="pointWarning">' . TEXT_ORDER_ADMINISTATION . '</span>';
				}
				?>
                      <td class="productListing-data"width="13%"><?php echo tep_date_short($pending_points['date_added']); ?></td>
                      <td class="productListing-data" nowrap="nowrap"><?php echo '#' . $pending_points['orders_id'] . '&nbsp;&nbsp;' . db_to_html($orders_status['orders_status_name_1']); ?></td>
                      <td class="productListing-data" nowrap="nowrap"><?php 
							if($pending_points['admin_id']!=0) {
								echo db_to_html(nl2br($pending_points['points_comment'])) .'&nbsp;' . db_to_html($referred_customers_name); 
							}else{
								echo nl2br($pending_points['points_comment']) .'&nbsp;' . db_to_html($referred_customers_name); 
							}
							
					  ?>
                      </td>
                      <td class="productListing-data"><?php echo $points_status_name; ?></td>
                      <td class="productListing-data" align="right"><?php echo number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES); ?></td>
                    </tr>
                    <?php
				}
				
				?>
                  </table></td>
              </tr>
            </table>
            
           <!-- <div style="line-height:24px;"><?php #echo TEXT_INTRO_STRING_BOTTOM; ?>
              <?php /* ?> <a href="#" class="sp3">Click here</a> to learn more. <?php */ ?>
            </div>-->
            <?php ob_start()?>
            <!--新增正确积分规则信息 by _Afei-->
            <!--<h5 style="line-height:32px;">走四方积分赚取明细</h5>
            <ul class="pointsIntro">
            	<li>新用户注册 (+100积分)</li>
                <li>用户验证注册邮箱 (+80积分)</li>
                <li>订购走四方旅游产品、线路行程、酒店等 (+1美元=1积分)</li>
                <li>点评旅游行程、分享旅途感受等（需验证通过） (+20积分/条)</li>
                <li>参与填写走四方旅游网不定期的问卷调查 (数额不等)</li>
                <li>各类精彩活动即将推出 (数额不等)</li>
                <li>审核确认用户所提出有效建议或发现走四方网bug (数额不等)</li>
            </ul>
            <h5 style="line-height:32px;">积分消费折扣限额</h5>
            <ol style="line-height:24px;">
            	<li>1.客户在首次订购产品时，只能使用200以上的积分部分。二次及以上的消费，则再无任何限制。</li>
                <li>2.走四方积分的兑换比例为：100积分=1美元，无上限，有多少兑多少，积分越高现金折扣越高，甚至可以免费换取旅行团，完全享受到最高100%的优惠！</li>
            </ol>-->
            <div class="ui_item">
                <h3 class="ui_rules_listitle">积分兑换方式</h3>
                <p>客户订购产品时，在付款结账页面会有对应的“兑换”的窗口，可以看到客户目前的积分总数、对应现金及本订单可使用的最高积分数等信息。点击“确定兑换”按钮后，系统自动为客户计算出折扣金额，展示积分优惠后订单实付的总价，确认抵换“去支付”后，积分即已兑换成现金并相应扣除，客户只需直接支付优惠折扣后的余额即可。</p>
            </div>
            <div class="ui_item">
                <h3 class="ui_rules_listitle">积分赚取规则</h3>
                <p>用户通过完成在走四方旅游网上的一系列操作后就可获得相应积分。大致规则如下：</p>
                <table class="ui_rules_table">
                    <thead>
                        <tr>
                            <th>用户行为</th>
                            <th>来源说明</th>
                            <th>积分累计</th>
                            <th>积分限定</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>注册</td>
                            <td>新用户注册</td>
                            <td>+<?= NEW_SIGNUP_POINT_AMOUNT?>积分</td>
                            <td>只记一次</td>
                        </tr>
                        <tr>
                            <td>验证邮箱</td>
                            <td>用户验证注册邮箱</td>
                            <td>+<?= VALIDATION_ACCOUNT_POINT_AMOUNT?>积分</td>
                            <td>只记一次</td>
                        </tr>
                        <tr>
                            <td>订购产品</td>
                            <td>订购走四方旅游产品、线路行程、酒店等</td>
                            <td>+1美元=<?= POINTS_PER_AMOUNT_PURCHASE;?>积分</td>
                            <td>无限制</td>
                        </tr>
                        <tr>
                            <td>点评</td>
                            <td>点评旅游行程、酒店住宿、分享旅途感受、景点评论等（需验证通过）</td>
                            <td>+<?= USE_POINTS_FOR_REVIEWS?>积分/条</td>
                            <td>每天上限<?= EVERY_DAY_MAX_NUM_FOR_ADD_POINTS_FOR_REVIEWS?>条</td>
                        </tr>
                        <tr>
                            <td>调查问卷</td>
                            <td>参与填写走四方旅游网不定期的问卷调查</td>
                            <td>数额不等</td>
                            <td>只记一次</td>
                        </tr>
                        <tr>
                            <td>不定期活动</td>
                            <td>各类精彩活动即将推出</td>
                            <td>数额不等</td>
                            <td>待定</td>
                        </tr>
                        <tr>
                            <td>建议&amp;bug发现</td>
                            <td>审核确认用户所提出有效建议或发现走四方网bug</td>
                            <td>数额不等</td>
                            <td>无限制</td>
                        </tr>
                    </tbody>
                </table>
                <p class="ui_rules_notice">一切刷积分行为都将受到正义的制裁！</p>
            </div>
            <div class="ui_item">
                <h3 class="ui_rules_listitle">积分消费折扣限额规则</h3>
                <p>1.客户在首次订购产品时，只能使用200以上的积分部分。二次及以上的消费，则再无任何限制。</p>
                <p>2.走四方积分的兑换比例为：100积分=1美元，无上限，有多少兑多少，积分越高现金折扣越高，甚至可以免费换取旅行团，完全享受到最高100%的优惠！</p>
            </div>
            <?php echo db_to_html(ob_get_clean()) ?>
            <?php
			//隐藏原错误的积分规则信息 by _Afei 20120530
			if (false) {?>
            <h2><?php echo TEXT_EARN_POINTS; ?></h2>
            <ul class="pointsIntro">
              <?php /* 积分现金折扣： 100积分=1美元（USD）
		<li><?php echo TEXT_POINT_TO_USD;?></li>
		*/?>
              <?php
		if(abs(NEW_SIGNUP_POINT_AMOUNT)>0){
		?>
              <li><?php echo TEXT_REG_POINTS; ?></li>
              <?php
		}
		?>
		<li><?php echo TEXT_IMPROVE_INFO_FOR_POINT ?></li>
              <?php
		if(abs(POINTS_PER_AMOUNT_PURCHASE)>0){
		?>
              <li><?php echo TEXT_TOUR_POINTS; ?></li>
              <?php
		}
		?>
              <?php
		if(abs(USE_REFERRAL_SYSTEM)>0){
		?>
              <li><?php echo TEXT_REFER_POINTS; ?></li>
              <?php
		}
		?>
              <?php
		if(abs(USE_POINTS_FOR_REVIEWS)>0){
		?>
              <li><?php echo TEXT_REVIEW_POINTS; ?></li>
              <?php
		}
		?>
              <?php
		if(abs(USE_POINTS_FOR_ANSWER)>0){
		?>
              <li><?php echo TEXT_ANS_POINTS; ?></li>
              <?php
		}
		?>
              <?php			
		if(abs(USE_POINTS_FOR_PHOTOS)>0){
		?>
              <li><?php echo TEXT_PHOTO_POINTS; ?></li>
              <?php
		}
		?>
              <?php
		if(abs(USE_POINTS_FOR_FEEDBACK_APPROVAL)>0){
		?>
              <li><?php echo TEXT_FEEDBACK_POINTS; ?> </li>
              <?php
		}
		?>
            </ul>
            <br/>
            <br/>
            <h2><?php echo TFF_POINTS_DESCRIPTION; ?><?php echo db_to_html('：');?></h2>
            <p><?php echo TFF_POINTS_DESCRIPTION_CONTENT;?><br/>
              <br/>
            </p>
            <h2><?php echo TEXT_HOW_SAVE; ?><?php echo db_to_html('：');?></h2>
            <p><?php echo TEXT_SAVINGS;?><br/>
              <br/>
            </p>
            <p><?php echo TEXT_MORE; ?></p>
            <?php }?></td>
        </tr>
        <?php
 //}
  //howard added rewards4fun banner end
 ?>
        <?php
  }
?>
        <?php /* close button_back
	  <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		        <td><a href="javascript:history.go(-1)"><?php echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	  close button_back end */ ?>
      </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>

<!-- body_eof //-->
<?php echo tep_get_design_body_footer();?>