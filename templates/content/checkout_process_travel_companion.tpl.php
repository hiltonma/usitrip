<div style="border:1px solid #AED5FF;margin-top:15px;padding:0 10px;">
	<table border="0" width="100%" cellspacing="0" cellpadding="1">
			  <tr>
				<td valign="top" class="main" align="center"><?php // echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
					<!--<div align="center" class="pageHeading"><?php //echo $HEADING_TITLE; ?></div><br /><?php //echo $TEXT_SUCCESS; ?><br /><br />-->
				  <h3 style="color:#0A4AA4;font-size:16px;"><?php echo db_to_html("您已预订完成，您的订单号为：") . $order_id;#TEXT_THANKS_FOR_SHOPPING; ?></h3></td>
			  </tr>
			   <?php
			   //信用卡方式信息
			   if(tep_get_travel_companion_paymentmethod($orders_travel_companion_ids)== 'authorizenet' ){
			   //if((db_to_html(tep_get_travel_companion_paymentmethod($orders_travel_companion_ids))== 'moneyorder' || db_to_html(tep_get_travel_companion_paymentmethod($orders_travel_companion_ids))== 'transfer' || db_to_html(tep_get_travel_companion_paymentmethod($orders_travel_companion_ids))== 'banktransfer' ) && isset($order_id) ){
			   ?>
			   
				<tr>
                        <td class=main colspan=2><div 
                        style="BORDER-RIGHT: #ffcf90 1px solid; PADDING-RIGHT: 5px; BORDER-TOP: #ffcf90 1px solid; MARGIN-TOP: 5px; PADDING-LEFT: 5px; BACKGROUND: #fff3e3; PADDING-BOTTOM: 5px; BORDER-LEFT: #ffcf90 1px solid; PADDING-TOP: 5px; BORDER-BOTTOM: #ffcf90 1px solid"> <div 
                        style="PADDING:10px 10px 10px 20px; BACKGROUND: #fff;"> 
                            <p ><?php echo db_to_html('走四方网（usitrip.com）获得美国Better Business Bureau（BBB)的A+优秀评级，确保您在本网站的预订是便捷和安全的。为了确保您在本网站支付的安全，')?></p>
                          <span 
                        style="COLOR: #f7860f"><?php echo db_to_html('如果您的消费符合以下任何一种情况，我们需要您向我们提供相关证明。')?></span>
                            <p style=" PADDING-BOTTOM:10px"><span 
                        style="COLOR: #f7860f">&#8226;<?php echo db_to_html('参与旅游的人不包括信用卡持卡人本人；')?><br>
                              &#8226; 
                              <?php echo db_to_html('您的信用卡账单地址（Billing Adress）与银行系统数据不符；')?><br>
                              &#8226; 
                              <?php echo db_to_html('单笔消费金额超过$2000.00美元。')?><br>
                            </span></p>
                         <?php echo db_to_html('需要的相关证明：')?> 
                            <div >1.<?php echo db_to_html('信用卡持卡人有效身份证件的复印件或扫描件(有效身份证件包括您的护照或由美国签发的带有本人签名的驾驶执照或由美国签发的带有本人签名的身份证)；')?><br>

2.<?php echo db_to_html('填写完整，并签署了信用卡持卡人签名和日期的')?><a href="<?php echo tep_href_link('acknowledgement_card_billing.php')?>" target=_blank><?php echo db_to_html('信用卡支付验证书。')?></a>  <br>

3.<?php echo db_to_html('如果信用卡持卡人不是参与旅行团的成员，请附上游客的护照复印件或扫描件； <br>
                              感谢您的支持和协助，请了解走四方网将在核实相关证明后才会寄送旅游行程电子参团凭证（E-Ticket）。 ')?></div>
                          </div>
                        </div></td>
                      </tr>

			   
			   
			   
                      <tr>
                        <td height="20" colspan=2 class=main>&nbsp;</td>
                      </tr>
					  <tr>
                        <td class=main colspan=2><div class=heading_bg 
                        style="FONT-WEIGHT: bold; FONT-SIZE: 12px"><?php echo db_to_html('您可以通过以下三种方式提供相关证明')?></div></td>
                      </tr>
                      <tr>
                        <td class=main colspan=2><div class=infoBox_outer>
                            <table class=infoBox_new width="100%">
                              <tbody>
                                <tr>
                                  <td><div 
                              style=" PADDING: 10px 0px 10px 20px;">
                                      <ul>
                                        <li><b><?php echo db_to_html('电子邮箱(首选):')?></b> 
                                          <br>
                                          <?php echo db_to_html('service@usitrip.com')?><br>
                                          <br>
                                        <li><?php echo db_to_html('如有问题请拨打客服400电话，谢谢！')?></li><br>
                                      </ul>
                                    
                                  </div></td>
                                </tr>
                              </tbody>
                            </table>
                        </div></td>
                      </tr>

			   <?php }else{?>
			   
					  <tr>
                        <td height="20" colspan=2 class=main>&nbsp;</td>
                      </tr>
					  <tr>
                        <td height="20" colspan=2 class=main>
						<span style="color:#ff7302; font-size:12px;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/yellow_i.gif) no-repeat left 13%;padding-left:20px;display:block;"><b>
						<?php echo db_to_html('温馨提示：感谢您预定美国走四方的旅游行程！请在1-2个工作日内完成支付，在完成付款前，您的预定未被保留。') ?>
						</b></span>
						</td>
                      </tr>

			   <?php }?> 				
					  <tr>
                        <td height="20" colspan=2 class=main>&nbsp;</td>
                      </tr>
					  <tr>
                        <td height="20" colspan=2 class=main><div style="border:1px solid #D4D4D4;"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #fff; background:#F5F5F5;">
                          <?php /*结伴同游成员暂无积分奖励 ?>
						  <tr><td width="25%" align="center" valign="middle"><img src="image/jifen-icon.jpg" width="54" height="52"></td>
                            <td width="75%" style="color:#6f6f6f"><p style="padding:10px 10px 10px 0px"><b><?php echo db_to_html('走四方积分奖励')?></b><br>
 <?php echo db_to_html('您的本次预定在确认后就会获得相当于您订单金额两倍的积分。您可以在下次预定我们的旅游产品时兑换积分，最高可获得8%的折扣！我们鼓励您在参加完我们的旅游行程后，分享您的旅行照片，旅游感受等，获取更多积分。您的积分永远不过期，任何时候使用均可。')?>
<?php echo db_to_html('请点击<a href="points.php">积分奖励</a>，了解活动详情。')?> </p></td>
                          </tr>
                          <?php */?>
						  <tr><td width="25%" align="center" valign="middle"><img src="image/shop_dot4.gif" width="59" height="50"></td>
                            <td width="75%" style="color:#6f6f6f"><p style="padding:10px 10px 10px 0px"><b><?php echo db_to_html('旅游保险')?></b><br>
 <?php echo db_to_html('游保险！目前走四方网提供三种旅游保险：<br>·基本险（Basic Limited Coverage）<br>·中等险（Plus Quality Coverage）<br>·高级险（Elite Superior Coverage）<br>请点击旅游保险，了解详情。')?>
<?php echo db_to_html('请点击<a href="' . tep_href_link('insurance.php') . '">旅游保险</a>，了解活动详情。')?> </p></td>
                          </tr>
						  <tr>
                            <td align="center" valign="middle"><img src="image/shop_dot5.gif" width="74" height="53"></td>
                            <td style="color:#6f6f6f"><p style="padding:10px 10px 10px 0px"><b><?php echo db_to_html('旅美须知')?></b><br>
 <?php echo db_to_html('1.我们建议您在收到确认电子旅游票后再预订您的机票，在订购完机票后请回到本网站www.usitrip.com “我的账户”里面补填机票信息以便导游接机。<br>2. 按出发日不同,行程次序可能前后稍作调整。<br>3. 出发前请详细检查您的行李及确定旅行证件齐全。')?>
<br>
<?php echo db_to_html('更多信息请查看')?><a href="<?php echo tep_href_link('tour_america_need.php')?>" target="_blank"><?php echo db_to_html('帮助中心')?></a><?php echo db_to_html('。')?></p></td>
                          </tr>
                        </table>
                        </div></td>
                      </tr>

				<tr>
				<td  class="main" colspan="2"><?php  echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
 				<tr>
				<td  class="main" align="center" colspan="2"><div style="padding-top:8px;"><?php echo '<a class="btn " style="width:100px;" href="' . tep_href_link('account_history_info.php', 'order_id='.$order_id, 'SSL') . '"><span></span>' . db_to_html('查看订单详情') . '</a>'; # . tep_template_image_button('button_continue_checkout.gif', '') .?></div></td>
				</tr>
 				<tr>
				<td  class="main" colspan="2"><?php  echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
			</table>

</div>