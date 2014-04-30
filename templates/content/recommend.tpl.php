<?php
ob_start();
?>
<div id="abouts">
<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/about_left.php');
		?>
        <div class="abouts_right">
        	<div class="aboutsTit">
            	<ul>
                	<li>销售推荐</li>
                </ul>
        </div>
            <div class="aboutsCont ">
                <div class="about8_1">
                	<ul>
                    	<li class="s_1">好消息，共分享！</li>
                    	<li class="s_2">快告诉您的同事及朋友我们的旅行线路吧， 除了您的同事及朋友可<br />
获得购买折扣外，您也能获得消费额<strong class="color_orange">3%</strong>的推荐拥金。</li>
                    	<li class="s_3">免费加盟， 立即做生意</li>
                    	<li class="s_4"><strong class="font_size14">仅1分钟:</strong><a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT)?>"><em></em>立即注册加盟</a></li>
                    </ul>
                </div>
                <div class="about8_2">
                	<h4><strong class="color_blue font_size14">怎样推荐?</strong></h4>
                  <p>1． 首先您在我们网站免费注册成会， 仅需一分钟。<br />
2． 会员注册成功后您可以在您的会员帐号里获得一个折扣号（Coupon Code）和邮件推荐及广告链接link等。<br />
3． 您可以通过电话推荐，告诉任何朋友你的Coupon Code, 在您的朋友购买我们的旅游产品时，输入此Coupon Code，即可获得优惠折扣。同时，通过此Coupon Code所产生的购买佣金会记在您的帐户里。<br />
4． 用邮件推荐时，可在会员帐户内直接发送给多个朋友。 您的朋友通过邮件link进入我们网站所产生的购买，佣金也会记在您的帐户里。<br />
5． 同时您也可以在自己的网站，blog及各大论坛里推荐我们的网站及旅游线路，放上给您的link，每个通过此link进入我们网站所产生的购买，佣金也会记在您的帐户里。<br />
6． 您可以随时进入自己的帐户查看自己的佣金额度及历史记录, 所有佣金每月以check方式或paypal转账方式结算一次（每次大于$50）.</p>
                </div>
              
			  <?php if(!tep_not_null($customer_id)){?>
			  <form name="logform" id="logform"  action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')?>"  method="POST" enctype="application/x-www-form-urlencoded" onsubmit="return validateFormData();">
			  <div class="about8_3">
           	  <h4><strong class="color_blue font_size14">会员登录</strong></h4>
                    <p>会员请登陆,在您的账户里可以使用推荐Link,以便系统记录您的佣金.非会员可点此<a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT)?>" class="color_orange underline">免费注册加盟.</a></p>
                    <div class="logins">
                    	<dl>
                        	<dt>登陆ID：</dt>
                            <dd><?php echo tep_draw_input_field('email_address',$account_value,' tabindex="1" class="box1 inset_shadow"  autocomplete="off" id="email" '.$input_style)?><span class="color_orange">*</span></dd>
                        </dl>
                        <dl>
                        	<dt>登录密码：</dt>
                            <dd><?php echo tep_draw_password_field('password','','tabindex="2" id="password" class="box1 inset_shadow"  ')?><span class="color_orange">*</span></dd>
                        </dl>
                        <dl>
						<dt>&nbsp;</dt>	
						<dd>					
						<button type="submit" class="refer"><em></em><t>提 交</t></button>
						</dd>
						</dl>
                    </div>
              </div>
			  </form>
			  <?php }?>
			  
   	      	</div>
        </div>
    </div>
    <?php echo db_to_html(ob_get_clean());?>