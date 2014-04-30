<?php
//添加google的销售成果跟踪, start{
if(IS_PROD_SITE=="1"){
$googleadservices = 'https://www.googleadservices.com';
if($_SERVER['SERVER_PORT']!='443'){
	$googleadservices = 'http://www.googleadservices.com';
}
?>
<!-- Google Code for USTrip20101230 Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1000794048;
var google_conversion_language = "zh_CN";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "O5rUCLjriQIQwM-b3QM";
var google_conversion_value = 666;
/* ]]> */
</script>
<script type="text/javascript" src="<?= $googleadservices;?>/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="<?= $googleadservices;?>/pagead/conversion/1000794048/?value=666&amp;label=O5rUCLjriQIQwM-b3QM&guid=ON&script=0"/>
</div>
</noscript>
<?php
}
//添加google的销售成果跟踪, end}
?>

	<?php /* 以下代码暂不知有什么用，屏蔽掉?>
<script type="text/javascript">
	var txtval = "<?php echo db_to_html($txtval);?>";

	var  signname;
signname = "\n\n";
	var prodsarray = new Array();
	<?php
	  $result = mysql_query("SELECT products.products_id,products_description.products_name  FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $languages_id . "' ORDER BY products_description.products_name");
   	  while($row = mysql_fetch_array($result)){
	  echo db_to_html('prodsarray['.$row['products_id'].'] = "'.tep_db_output($row['products_name']).'";')."\n";
	  }
	?>

	var catsarray = new Array();
	<?php
	  $result1 = mysql_query("SELECT categories.categories_id , categories_description.categories_name FROM categories, categories_description WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = '" . $languages_id . "' ORDER BY categories_description.categories_name");
      while($row1 = mysql_fetch_array($result1)){
	  echo db_to_html('catsarray['.$row1['categories_id'].'] = "'.$row1['categories_name'].'";')."\n";
	  }
	?>
</script>
	<?php */?>

<?php echo tep_get_design_body_header(''); #HEADING_TITLE?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">

   <tr>
        <td>
			<table border="0" width="100%" cellspacing="0" cellpadding="1">
			  <tr>
				<td valign="top" class="main" align="center"><?php // echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
					<!--<div align="center" class="pageHeading"><?php //echo $HEADING_TITLE; ?></div><br /><?php //echo $TEXT_SUCCESS; ?><br /><br />-->
				  <h3 style="color:#0a4aa4;font-size:16px;"><?php echo db_to_html('您已预订完成，您的订单号为：') . $_GET['order_id'];#TEXT_THANKS_FOR_SHOPPING; ?></h3></td>
			  </tr>
			  <tr>
			  	<td>
				<?php ob_start();?>
				<span style="color:red;font-size:18px;font-weight:bold">注意：如果您下的订单为结伴同游订单，同游客人不需再订购，只需登录账户，支付结伴同游订单即可。</span>
				<?php echo  db_to_html(ob_get_clean());?>
				</td>
			  </tr>
				<?php
			   if(tep_not_null($goUrl)){?>
				<a target="_blank" href="<?php echo $goUrl;?>"><?php echo db_to_html($goUrlText)?></a>   
			   <?php }else{?>
					  <tr>
                        <td height="20" colspan=2 class=main>&nbsp;</td>
                      </tr>
					  <tr>
                        <td height="20" colspan=2 class=main>
						<span style="color:#ff7302; font-size:12px;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/yellow_i.gif) no-repeat left 13%;padding-left:20px;display:block;">
						<?php echo db_to_html('温馨提示：感谢您预定美国走四方的旅游行程！请在1-2个工作日内完成支付，在完成付款前，您的预定未被保留。') ?></span>
						</td>
                      </tr>
			<?php }?>
					  <tr>
                        <td height="20" colspan=2 class=main>&nbsp;</td>
                      </tr>
					  <tr>
                        <td height="20" colspan=2 class=main><div style="border:1px solid #D4D4D4;"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #fff; background:#F5F5F5;">
                          <tr><td width="25%" align="center" valign="middle"><img src="image/shop_dot4.gif" width="59" height="50"></td>
                            <td width="75%" style="color:#6f6f6f"><p style="padding:10px 10px 10px 0px"><b><?php echo db_to_html('旅游保险')?></b><br>
 <?php echo db_to_html('游保险！目前走四方网提供三种旅游保险：<br>·基本险（Basic Limited Coverage）<br>·中等险（Plus Quality Coverage）<br>·高级险（Elite Superior Coverage）<br>请点击旅游保险，了解详情。')?>
<?php echo db_to_html('请点击<a href="' . tep_href_link('insurance.php') . '" target="_blank">旅游保险</a>，了解详情。')?> </p></td>
                          </tr>
                          <tr>
                            <td align="center" valign="middle"><img src="image/shop_dot5.gif" width="74" height="53"></td>
                            <td style="color:#6f6f6f"><p style="padding:10px 10px 10px 0px"><b><?php echo db_to_html('旅美须知')?></b><br>
 <?php echo db_to_html('1.我们建议您在收到确认电子旅游票后再预订您的机票，在订购完机票后请回到本网站www.usitrip.com “我的账户”里面补填机票信息以便导游接机。<br>2. 按出发日不同,行程次序可能前后稍作调整。<br>3. 出发前请详细检查您的行李及确定旅行证件齐全。')?>
<br>
<?php echo db_to_html('更多信息,请查看')?><a href="<?php echo tep_href_link('tour_america_need.php') ?>" target="_blank"><?php echo db_to_html('帮助中心。')?></a></p></td>
                          </tr>
                        </table>
                        </div></td>
                      </tr>

                      <?php /*?><tr>
                        <td height="40" colspan=2 align="right" class=main><span class="huise"><?php echo db_to_html('打印订单信息包括订单号，团号，支付方式，金额，走四方网的联系信息及注意事项等')?></span></td>
                      </tr>
                      <tr>
                        <td height="10" colspan=0 align="right" class=main><span class="huise"><?php echo db_to_html('如有任何意见或建议请发送邮件至')?><a class="pageResults" style="cursor:default; text-decoration:none"><?php echo db_to_html('service#usitrip.com')?></a><?php echo db_to_html('(注：发邮件时，请把#号换成@)')?></span></td>
                      </tr><?php */?>
				<tr>
				<td  class="main" colspan="2"><?php  echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				</tr>
			</table>
		</td>
	</tr>

<?php
//howard added close friend links
$friend_links_off = false;
if($friend_links_off==true){
?>
      <!-- amit added to invite friend links start  -->
	  <?php
require('includes/javascript/common.js.php');
//require('includes/javascript/refer_friends.js.php');
?>

	  <tr>
        <td  colspan="2" class="main">
		<?php // echo '<a href="' . tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'). '"><b>Interesting in this fun tour? Refer your friend and make 3% commission</b></a>' ; ?>
		<!--  main center box start -->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	    <tr>

        <td >
					<?php
					if($sendmsg == "sendture"){ ?>
					<table border="0" cellspacing="1" cellpadding="0" width="70%" align="center"><tbody>
					 <tr>
					<td class="main"><font color="#006633"><b><?php echo TEXT_MESSAGE_SENT_SUCCESS;?></b></font><br /><br /></td>
					</tr></tbody>
					</table>
					<?php }
					?>
					<!-- start of box content -->
						<table border="0" cellspacing="1" cellpadding="0" width="100%" align="center"><tbody>
					 <tr>
					<td class="main"><b><?php echo TEXT_PAGE_HEADING1;?> </b>
					<br /><br />
					</td>
					</tr></tbody>
					</table>
						   <?php echo tep_draw_form('theForm', tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'), 'post',' id="theForm"') . tep_draw_hidden_field('action', 'process'); // 'onsubmit="return register_refral_validator(this);"' ?>

									<table border="0" cellspacing="1" cellpadding="0" width="80%" class="automarginclass" align="center">

																												<tr>
																												<td>
																														<table border="0" width="100%" cellspacing="0" cellpadding="2">
																														  <tr>
																															<td class="main"><b><?php echo HEADING_REFEAR_A_FRIEND_RECOMMEND_CATGORY_OR_TOUR; ?></b></td>
																														   <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																												<tr>
																												<td>
																														  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
																														  <tr class="infoBoxContents">
																																<td>
																																			<table width="100%" >
																																			  <tr>
																																				<td class="main" width="28%" nowrap="nowrap" ><?php echo TEXT_RECOMMEND_CAT;?></td>
																																				<td class="main">
																																				<?php
																																				// write categories array

																																				echo tep_draw_pull_down_menu('cPath1', tep_get_category_tree(),$HTTP_GET_VARS['cPath'],'style="width:350px;" class="pr_b_text_per" onchange="cat_call();"');
																																				//write categories array
																																				?>
																																				</td>
																																				</tr>
																																				<tr>
																																				<td class="main" colspan="2" align="center" height="5" ><span class="sp1"><?PHP echo TEXT_OR;?></span></td>

																																				</tr>
																																				<tr>
																																				<td class="main" ><?PHP echo TEXT_RECOMMEND_PROD;?></td>
																																				<td class="main" >
																																				<?php
																																				//write product array start

																																				$products_array[] = array('id' => '0','text' => TEXT_TOP);
																																				$products_query_row = "SELECT p.products_id, pd.products_name FROM ".TABLE_PRODUCTS." as p , ".TABLE_PRODUCTS_DESCRIPTION." as pd WHERE p.products_id = pd.products_id and p.products_status='1' and pd.language_id = '" . $languages_id . "' ORDER BY pd.products_name";
																																				$products_query = tep_db_query($products_query_row);
																																				while ($products = tep_db_fetch_array($products_query)) {
																																					$products_array[] = array('id' => $products['products_id'],
																																												'text' => db_to_html($products['products_name']));
																																				}
																																				echo tep_draw_pull_down_menu('products1', $products_array,$HTTP_GET_VARS['products_id'],'style="width:350px;" class="pr_b_text_per"  onchange="prodcut_call();"');

																																				//write product array end
																																				?>
																																				</td>
																																				</tr>
																																			</table>

																																</td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																											  <tr>
																												<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?></td>
																											  </tr>




																												<tr>
																												<td>
																														<table border="0" width="100%" cellspacing="0" cellpadding="2">
																														  <tr>
																															<td class="main"><b><?php echo HEADING_REFEAR_A_FRIEND_YOUR_PERSONAME_DETAILS; ?></b></td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																												<tr>
																												<td>
																														  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
																														  <tr class="infoBoxContents">
																																<td>
																																			<table width="100%" >
																																			      <tr>
																																					<td class="main" width="28%"><?PHP echo TEXT_YOUR_EMAIL;?></td>
																																					<td class="main"><input name="email_address" id="email_address" class="required validate-email " title="<?php echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR; ?>"  size="27" value="<?php echo tep_get_customers_email($customer_id);?>" type="text" /><span class="sp1">&nbsp;*</span></td>
																																				  </tr>
																																				  <tr>
																																					<td class="main" colspan="2" height="5"></td>
																																				  </tr>
																																				  <tr>
																																					<td class="main"><?PHP echo TEXT_FULL_NAME;?></td>
																																					<td class="main">
<?php echo tep_draw_input_field("fname", db_to_html(tep_customers_name($customer_id)), ' id="fname" class="required" title="'.ENTRY_FIRSTNAME_ERROR.'" size="27" ');?>																																																																																																							<!--<input name="fname" size="27" class="pr_b_text_per" value="<?php echo db_to_html(tep_customers_name($customer_id));?>"  type="text">-->																																																																							<span class="sp1">&nbsp;*</span>
																																																																										</td>
																																				  </tr>

																																			</table>
																																</td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																											  <tr>
																												<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?></td>
																											  </tr>



																											  <tr>
																												<td>
																														<table border="0" width="100%" cellspacing="0" cellpadding="2">
																														  <tr>
																															<td class="main"><b><?php echo HEADING_REFEAR_A_FRIEND_EMAIL_ADDRESS; ?></b></td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																												<tr>
																												<td>
																														  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
																														  <tr class="infoBoxContents">
																																<td>
																																			<table width="100%"  cellpadding="0" cellspacing="0">
																																			 <tr>
																																					<td class="main" colspan="2" height="3"></td>
																																				  </tr>
																																			  <tr>
																																						<td  class="main" valign="top" nowrap="nowrap"><?php echo TEXT_FRIEND_EMAIL;?></td>
																																						<td>
																																								<table border="0" cellspacing="0"  cellpadding="0"   >
																																									 <tr>
																																									   <td  class="main" ><input class="required validate-email" id="refer_frd_email_1" title="<?php echo ENTRY_ATLEAST_1_EMAIL_ERROR; ?>"  name="refer_frd_email_1" size="27" type="text" /></td>
																																									  <td width="5"></td>
																																									   <td   class="main" ><input class="pr_b_text_per"  name="refer_frd_email_6" size="27" type="text" /><span class="sp1">&nbsp;*</span></td>
																																									  </tr>
																																									  <tr>
																																										<td class="main" colspan="2" height="5"></td>
																																									  </tr>
																																									  <tr>
																																									   <td  class="main" ><input class="pr_b_text_per"  name="refer_frd_email_2" size="27" type="text" /></td>
																																									  <td width="5"></td>
																																									   <td   class="main" ><input class="pr_b_text_per"  name="refer_frd_email_7" size="27" type="text" /></td>
																																									  </tr>
																																									   <tr>
																																										<td class="main" colspan="2" height="5"></td>
																																									  </tr>
																																									  <tr>
																																									   <td   class="main" ><input class="pr_b_text_per"  name="refer_frd_email_3" size="27" type="text" /></td>
																																									   <td width="5"></td>
																																									   <td   class="main" ><input class="pr_b_text_per"  name="refer_frd_email_8" size="27" type="text" /></td>
																																									  </tr>
																																									   <tr>
																																										<td class="main" colspan="2" height="5"></td>
																																									  </tr>
																																									  <tr>
																																									   <td  ><input class="pr_b_text_per"  name="refer_frd_email_4" size="27" type="text" /></td>
																																									  <td width="5"></td>
																																									   <td  ><input class="pr_b_text_per"  name="refer_frd_email_9" size="27" type="text" /></td>
																																									  </tr>
																																									   <tr>
																																										<td class="main" colspan="2" height="5"></td>
																																									  </tr>
																																									  <tr>
																																									   <td   class="main" ><input class="pr_b_text_per"  name="refer_frd_email_5" size="27" type="text" /></td>
																																									   <td width="5"></td>
																																									   <td   class="main" ><input class="pr_b_text_per"  name="refer_frd_email_10" size="27" type="text" /></td>
																																									  </tr>
																																									 </table>

																																						</td>
																																					  </tr>
																																					   <tr>
																																					<td class="main" colspan="2" height="3"></td>
																																				  </tr>

																																			</table>
																																</td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																											  <tr>
																												<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?></td>
																											  </tr>



																											  <tr>
																												<td>
																														<table border="0" width="100%" cellspacing="0" cellpadding="2">
																														  <tr>
																															<td class="main"><b><?php echo HEADING_REFEAR_A_FRIEND_A_MESSAGE_TO_FRIEND; ?></b></td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																												<tr>
																												<td>
																														  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
																														  <tr class="infoBoxContents">
																																<td>
																																			<table width="100%" >
																																			  <tr>
																																			  <td class="main" valign="top" width="28%" ><?PHP echo TEXT_MESSAGE_TO;?></td>
																																			  <td nowrap="nowrap"><?php echo tep_draw_textarea_field("msg_to_friends", '', '13', '10', '', 'class="pr_b_text_1_per"')?>
																																			  <!--<textarea rows="10" name="msg_to_friends" class="pr_b_text_1_per" cols="13"></textarea>-->
																																			  <span class="sp1">&nbsp;*</span></td>
																																			  </tr>

																																			</table>
																																</td>
																														  </tr>
																														</table>
																												</td>
																												</tr>
																											  <tr>
																												<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
																											  </tr>

																												<tr>
																												<td>

																														<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
																														  <tr class="infoBoxContents">
																															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
																															  <tr>
																																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																																<td   class="main"><?php echo '<a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_CATALOG . FILENAME_ORDERS_PRINTABLE) . '?' . (tep_get_all_get_params(array('order_id')) . 'order_id=' . $HTTP_GET_VARS['order_id']) . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></td>
																																<td  align="right" ><?php echo tep_template_image_submit('button_tellafriend.gif', 'Tell a Friend'); ?></td>

																																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																															  </tr>
																															</table></td>
																														  </tr>
																														</table>

																												</td>
																											  </tr>
																											</table>


					<!-- end of box content -->

			</td>

      </tr>

     </tbody></table>
	   <!--  main center box end-->

		 </td>
      </tr>

	  <!-- amit added to invite friend links end -->
<?php
//howard added close friend links end
}
?>


	<tr>
		<td>
			<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
			  <tr class="infoBoxContents">
				<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
				  <tr>
					<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
					<td   class="main">
                    <a class="btn" href="<?php echo tep_href_link('account_history.php', '', 'SSL')?>"><span></span><?php echo db_to_html('返回帐号') ?></a>
                    <?php #lwkaiRem Start?>
                    <!--<a href="<?php echo tep_href_link('account_history.php', '', 'SSL')?>"><?php echo tep_template_image_button('button_goto_account.gif', 'Continue'); ?></a>-->
                    <?php #lwkaiRem End ?></td>
					<td  align="right" >
					<a class="btn" href="/account_history_info.php?order_id=<?php echo $_GET['order_id']?>" style="width:85px;"><span></span><?php echo db_to_html('查看订单详情') ?></a>
					<?php 
					#echo '<a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_CATALOG . FILENAME_ORDERS_PRINTABLE) . '?' . (tep_get_all_get_params(array('order_id')) . 'order_id=' . $HTTP_GET_VARS['order_id']) . '\')" class="btn checkout"><span></span>' . db_to_html("打印订单") . '</a>';
					#lwkaiRem echo '<a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_CATALOG . FILENAME_ORDERS_PRINTABLE) . '?' . (tep_get_all_get_params(array('order_id')) . 'order_id=' . $HTTP_GET_VARS['order_id']) . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></td>

					<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				  </tr>
				</table></td>
			  </tr>
			</table>
		</td>
	</tr>

</table>

<?php echo tep_get_design_body_footer();?>
<?php
//自动到达付款页面
if(tep_not_null($goUrl)){
?>
<script type="text/javascript">
<!--
window.location = "<?php echo $goUrl;?>";
//-->
</script>
<?php
}
?>
	