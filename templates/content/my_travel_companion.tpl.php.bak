
<div class="item_left">
    <?php /*?><div class="jb_left">
	
      <?php
		//载入Face
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_face.php');
		get_travel_companion_face((int)$customer_id);

	  ?>
    
	</div>
    <div class="item_left1">
	<?php
		//载入访客信息
		include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'travel_companion_guest_history.php');
	?>

      </div>
	
</div>
<?php */?>
    <div class="jb_right">
        <?php /*?><div class="right_tb">
        <h3><?=$heading_h3?></h3>
      </div><?php */?>
        <div class="center-side1">
            <div class="myjb">
                <div id="TabPanel1" class="TabPanel_myjb">
                    <ul class="TabPanelGroup_myjb">
                        <li class="Tab_1_myjb" tabindex="0" title="my_information"><span>
                            <?=$tab_title1?>
                            </span></li>
                        <li class="Tab_1_myjb" tabindex="0" title="my_sent"><span>
                            <?=$tab_title2?>
                            </span></li>
                        <li class="Tab_1_myjb" tabindex="0" title="my_applied"><span>
                            <?=$tab_title3?>
                            </span></li>
                    </ul>
                    <div class="Tab_Content_myjb">
                        <?php //我的短信 start?>
                        <div class="Tab_Content11_myjb"> 
                            <script type="text/javascript">
						<!--
						function setChecked(id){
							jQuery('#my_msm_form input:checkbox').attr('checked',false);
							jQuery('#del'+id).attr('checked',true);
							if(jQuery('#del'+id+':checked').val() != undefined){
								remove_sms();
							}
						}
						
						function only_show_lwk(key,obj){
							var mo_d = jQuery('#right_button');
							
							for(j=0; j<mo_d.children().size(); j++){
								mo_d.children()[j].innerHTML = mo_d.children()[j].innerHTML.replace(/(\<b\>)|(\<\/b\>)/,'');
							}
							obj.innerHTML = "<b>"+obj.innerHTML+"</b>";
							if (key == 'all') {
								jQuery('#my_msm_form tr[title]').css('display','');
							} else {
								jQuery('#my_msm_form tr[title]').css('display','').not(jQuery('#my_msm_form tr[title='+key+']')).css('display','none');
							}
						}
							
						//-->
						</script>
                            <form action="" method="post" enctype="multipart/form-data" name="my_msm_form" id="my_msm_form">
                                <?php ob_start() ?>
                                <table class="ui-message-tb" style="table-layout:fixed;">
                                    <thead>
                                        <tr>
                                            <th class="ui-msg-icon">&nbsp;</th>
                                            <th class="ui-msg-icon">&nbsp;</th>
                                            <th class="ui-msg-type">结伴帖标题</th>
                                            <th class="ui-msg-title">消息内容</th>
                                            <th class="ui-msg-time">(发送/接收)人</th>
                                            <th class="ui-msg-time">时间</th>
                                            <th class="ui-msg-handle">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
					
							if((int)$my_sms['sis_id']){
						      	do{
						        	$icon_image = "News_receive.gif";
                					$icon_image_alt = "收到的信息";
                					$sent_receive = "receive";
                					$constyle = ' style="cursor:pointer;" ';
                					if($my_sms['customers_id']==$customer_id){
                						$icon_image = "News_send.gif";
                						$icon_image_alt = "发送的信息";
                						$sent_receive = "sent";
                						$constyle = ' style="cursor:pointer; font-weight:normal" ';
										$topeople = '发给：';

               						}
                					if($my_sms['to_customers_id']==$customer_id){
                						$icon_image = "News_receive.gif";
                						$icon_image_alt = "收到的信息";
                						$sent_receive = "receive";
										$topeople = '来至：';
									}
									//已阅读的图标
									if($my_sms['has_read']=="1" && $icon_image!="News_send.gif"){
											$icon_image = "News_read.gif";
											$icon_image_alt = "已阅读";
											$constyle = ' style="cursor:pointer; font-weight:normal" ';
									}
							        ?>
                                        <tr title="<?= $sent_receive?>" id="Tab_myjb_item1_sms_<?= $my_sms['sis_id']?>">
                                            <td class="ui-msg-icon"><?php echo tep_draw_checkbox_field('sis_ids[]',$my_sms['sis_id'],false,'id="del' . $my_sms['sis_id'] . '"')?></td>
                                            <td class="ui-msg-icon"><img id="icons_<?php echo $my_sms['sis_id']?>" src="image/icons/<?php echo $icon_image?>" title="<?= $icon_image_alt?>" alt="<?= $icon_image_alt?>"/></td>
                                            <td class="ui-msg-type"><p>
                                                    <?php
									if(tep_not_null($my_sms['type_name']) && (int)$my_sms['key_id']){
										$links_href = $links_str = "";
										switch($my_sms['type_name']){
										case "travel_companion": 
											$links_href = tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$my_sms['key_id']); 
											$links_str = tep_get_companion_title($my_sms['key_id']); 
											break;
										}
										?>
                                                    <a href="<?= $links_href?>" target="_blank">
                                                    <?= tep_db_output($links_str);?>
                                                    </a>
                                                    <?php
									}
									?>
                                                </p></td>
                                            <td class="ui-msg-title" style="word-wrap:break-word;word-break:break-all;"><p><a href="#" class="ui-msg-unread" style="display:none">系统已向您的邮箱发送电子通知票，请查收！</a>
                                                    <?php
                                    $sms_content = tep_db_output($my_sms['sms_content']);
                                    $system_sms = "";
                                    if($my_sms['sms_type']==100){
                                        $system_sms = '[走四方网通知]';
                                    }
                                    echo '<span style="color:#FF0000; font-weight:normal">'.$system_sms.'</span>';
									$ctext = cutword($sms_content, (60 - mb_strlen($system_sms)));
                                    echo '<span '.$constyle.' onclick="show_title(this,'.$my_sms['sis_id'].')" title="'.nl2br(preg_replace("/(\[[^\]]+\])/","<span style='color:#ff0000'>$1</span>",$sms_content)).'">' . preg_replace("/(\[[^\]]+\])/","<span style='color:#ff0000'>$1</span>",$ctext) . '</span>';
                                    ?>
                                                </p></td>
                                            <td class="ui-msg-time" width="15%"><?php 
											echo $topeople . tep_customers_name($my_sms['customers_id']); 
										?></td>
                                            <td class="ui-msg-time" width="15%"><p><?php echo $my_sms['add_date'];?></p></td>
                                            <td class="ui-msg-handle" width="15%"><p><a href="javascript:void(0);" onclick="setChecked(<?php echo $my_sms['sis_id']?>);" class="ui-msg-delete">删除</a></p></td>
                                        </tr>
                                        <?php
							    }while($my_sms = tep_db_fetch_array($my_sms_sql));
						    }
							?>
                                    </tbody>
                                </table>
                                <?php echo db_to_html(ob_get_clean()) ?>
                                <ul id="ul_sms">
                                    <li class="Tab_myjb_item1">
                                        <div class="Tab_myjb_item1_l">
                                            <label>
                                                <input type="checkbox" onclick="select_all_checkboxs('my_msm_form','sis_ids[]',this)" />
                                                <?= db_to_html('全选')?>
                                            </label>
                                            <a href="javascript:void(0)" onclick="remove_sms()" class="jb_fb_tc_bt_a">
                                            <?= db_to_html('删除')?>
                                            </a> <a href="javascript:void(0)" onclick="set_has_been_read()" class="jb_fb_tc_bt_a">
                                            <?= db_to_html('标记为已读')?>
                                            </a></div>
                                        <div id="right_button" class="Tab_myjb_item1_r"><a href="javascript:void(0)" onclick="only_show_lwk('all',this)" class="jb_fb_tc_bt_a">
                                            <?= db_to_html('全部')?>
                                            </a> <a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="only_show_lwk('receive',this)">
                                            <?= db_to_html('收到的信息')?>
                                            </a> <a href="javascript:void(0)" onclick="only_show_lwk('sent',this)" class="jb_fb_tc_bt_a">
                                            <?= db_to_html('发出的信息')?>
                                            </a></div>
                                    </li>
                                    <?php
									/* 原来的老程序代码 by lwkai 注释 start {
							if((int)$my_sms['sis_id']){
								do{
									$icon_image = "News_receive.gif";
									$icon_image_alt = "收到的信息";
									$sent_receive = "receive";
									$constyle = ' style="cursor:pointer;" ';
									if($my_sms['customers_id']==$customer_id){
										$icon_image = "News_send.gif";
										$icon_image_alt = "发送的信息";
										$sent_receive = "sent";
										$constyle = ' style="cursor:pointer; font-weight:normal" ';
									}
									if($my_sms['to_customers_id']==$customer_id){
										$icon_image = "News_receive.gif";
										$icon_image_alt = "收到的信息";
										$sent_receive = "receive";
									}
									//已阅读的图标
									if($my_sms['has_read']=="1" && $icon_image!="News_send.gif"){
										$icon_image = "News_read.gif";
										$icon_image_alt = "已阅读";
										$constyle = ' style="cursor:pointer; font-weight:normal" ';
									}
							?>
                                    <li class="Tab_myjb_item1" title="<?= $sent_receive?>" id="Tab_myjb_item1_sms_<?= $my_sms['sis_id']?>">
                                        <div class="Tab_myjb_item1_l"><?php echo tep_draw_checkbox_field('sis_ids[]',$my_sms['sis_id'])?><img id="icons_<?= $my_sms['sis_id']?>" src="image/icons/<?= $icon_image?>" title="<?= db_to_html($icon_image_alt)?>" alt="<?= db_to_html($icon_image_alt)?>" />
                                            <div class="myjb_items_b">
                                                <div>
                                                    <?php
									$sms_content = db_to_html(tep_db_output($my_sms['sms_content']));
									$system_sms = "";
									if($my_sms['sms_type']==100){
										$system_sms = db_to_html('[走四方网通知]');
									}
									echo '<span style="color:#FF0000; font-weight:normal">'.$system_sms.'</span>';
									echo '<label '.$constyle.' onclick="show_title(this,'.$my_sms['sis_id'].')" title="'.nl2br($sms_content).'">'.cutword($sms_content, (60 - mb_strlen($system_sms)) ).'</label>';
									?>
                                                </div>
                                                <div id="myjb_content_<?= $my_sms['sis_id']?>" class="myjb_content" style="margin-left:0px; padding-left:0px;" >
                                                    <?= db_to_html('信息来源：')?>
                                                    <?php
										if(tep_not_null($my_sms['type_name']) && (int)$my_sms['key_id']){
											$links_href = $links_str = "";
											switch($my_sms['type_name']){
												case "travel_companion": $links_href = tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$my_sms['key_id']); $links_str = tep_get_companion_title($my_sms['key_id']); break;
											}
										?>
                                                    <a href="<?= $links_href?>" target="_blank">
                                                    <?= db_to_html(tep_db_output($links_str));?>
                                                    </a>
                                                    <?php
										}
										?>
                                                </div>
                                            </div>
                                        </div>
                                        <div  class="Tab_myjb_item1_r"><a href="javascript:void(0)" onclick="show_site_inner_sms_layer(<?= $my_sms['customers_id']?>,'<?= $my_sms['type_name']?>', <?= $my_sms['key_id']?>)">
                                            <?= db_to_html(tep_customers_name($my_sms['customers_id']))?>
                                            </a>
                                            <?= db_to_html(tep_get_gender_string(tep_customer_gender($my_sms['customers_id']), 1));?>
                                            <span class="Tab_myjb_item1_b"><?php echo substr($my_sms['add_date'],0,16); //echo db_to_html(chardate($my_sms['add_date'], "I", "1"));?></span></div>
                                    </li>
                                    <?php
								}while($my_sms = tep_db_fetch_array($my_sms_sql));
							}
							
							//  } 原来的老程序代码 by lwkai 注释 end 
							*/
							?>
                                </ul>
                            </form>
                            <div class="clear"></div>
                        </div>
                        <?php //我的短信 end?>
                        <?php //我发布的结伴 start?>
                        <div class="Tab_Content11_myjb" style="width:745px;">
                            <?php
				 if((int)$my_sent['t_companion_id']){
					do{
						//取得申请贴资料
						$app_sql = tep_db_query('SELECT * FROM `travel_companion_application` WHERE t_companion_id="'.(int)$my_sent['t_companion_id'].'" ORDER BY tca_id DESC ');
						$app_num_rows = tep_db_num_rows($app_sql);
						//判断是否已下单
						  $can_booking = false;
						  $has_booked = false;
						  
						if((int)$my_sent['products_id']){
							$can_booking = true;
							$has_booked = false;
							$ord_sql = tep_db_query('SELECT * FROM `orders_travel_companion` WHERE products_id="'.(int)$my_sent['products_id'].'" and customers_id ="'.$customer_id.'" and orders_id ="'.$my_sent['orders_id'].'" limit 1');
							$ord_rows = tep_db_fetch_array($ord_sql);
							  if((int)$ord_rows['orders_id']){
								  $can_booking = false;
								  $has_booked = true;
								  if((int)$ord_rows['orders_travel_companion_id']){
									//$sq_group_items_text = "您已成功下单，请您尽快完成支付，开始快乐旅行吧。";
									$buttun_text = "已下单，去支付";
									if($ord_rows['orders_travel_companion_status']=="2"){
										//$sq_group_items_text = "您已成功下单，预祝我们的旅途愉快。";
										$buttun_text = "查看订单";
									}
								  }
								  
							  }
						  }
						  ob_start();
				 ?>
                            <div class="ui-event-wrap ui-clearfix">
                                <div class="ui-event-info">
                                    <div class="ui-event-title"><span class="ui-total"><?php echo date('Y-m-d',strtotime($my_sent['add_time'])) ?>发布 回复<em>（<?php echo (int)$my_sent['reply_num']?>）</em> 查看<em>（<?php echo (int)$my_sent['click_num']?>）</em></span>
                                        <h4><a href="<?php echo tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.$my_sent['t_companion_id'])?>"><?php echo tep_db_output($my_sent['t_companion_title'])?></a></h4>
                                    </div>
                                    <p>旅游线路：<?php echo $my_sent['t_companion_content']?></p>
                                </div>
                                <div class="ui-proposer-wrap">
									<?php
                                    //求本贴的已同意人数
                                    $verify_sql = tep_db_query('SELECT count(*) as total FROM `travel_companion_application` where t_companion_id="'.$my_sent['t_companion_id'].'" and  tca_verify_status ="1" ');
                                    $verify_row = tep_db_fetch_array($verify_sql);
                                    ?>
                                    <div class="ui-proposer-header">
                                    	<span id="set_expired_string_<?= $my_sent['t_companion_id']?>">
										<?php if($my_sent['has_expired']!="1"){?>
                                    		<a href="javascript:void(0)" class="ui-setting-loss" onclick="set_has_expired(<?= $my_sent['t_companion_id']?>,1)">设为过期帖</a>
                                        <?php
										}else{
										?>
                                            <a href="javascript:void(0)" class="ui-setting-loss" onclick="set_has_expired(<?= $my_sent['t_companion_id']?>,0)">重新开启结伴</a>
                                            <?php 
										}
										?></span>
                                         <span><strong>申请人：</strong>有(<?php echo $app_num_rows; ?>)人申请结伴，已经同意(<?php echo (int)$verify_row['total']?>)人</span> </div>
                                    <ul class="ui-proposer-list">
										<?php
                                        if((int)$app_num_rows){
                                        	while($app = tep_db_fetch_array($app_sql)){
												$img_src = "tx_b_s.gif";
												if($app['tca_gender']=="1"){  
													$img_src = "tx_b_s.gif"; 
													$xinbie = '男';
												}
												if($app['tca_gender']=="2"){  
													$img_src = "tx_g_s.gif"; 
													$xinbie = '女';
												}
												$img_src = "image/".$img_src;
												$img_src = tep_customers_face((int)$app['customers_id'], $img_src);
                                        	?>
                                        <li>
                                            <div class="ui-proposer-icon"><div class="ui-person-ico"><img src="<?php echo $img_src?>" <?php echo getimgHW3hw($img_src,76,76)?>/></div></div>
                                            <div class="ui-proposer-info">
                                                <div class="ui-handle-box"><span id="agree_box_<?=(int)$app['tca_id']?>">
                                                    <?php
													$close_botton_style = ' '; 
													$RefusalVerifyA_style = ' style="display:none" ';
													if($app['tca_verify_status']=="1"){?>
														<p><a class="ui-ongree" href="javascript:void(0)">已同意</a></p>
                                                        <?php
													}elseif($app['tca_verify_status']=="2"){?>
														<p><a class="ui-ongree" href="javascript:void(0)">已拒绝</a></p>
                                                        <?php
													}elseif($has_booked==false){	//未下单才能显示这些
													?>
                                                    <p><a href="javascript:void(0)" class="ui-gree-btn" onclick="agre_verify('<?php echo (int)$app['tca_id'] ?>')" >我同意</a></p>
                                                    <?php
														$RefusalVerifyA_style = ' ';
														$close_botton_style = ' style="display:none;" ';
													}
													?>
                                                    </span>
                                                    <?php if($has_booked==false){	//未下单才能显示这些?>
                                                		<p <?=$close_botton_style?> id="close_botton_<?= (int)$app['tca_id']?>"><a href="javascript:void(0)" onclick="agre_verify(<?= (int)$app['tca_id']?>,'close')" class="ui-ungree-btn"><span>取消</span></a></p>
                                                		<p <?=$RefusalVerifyA_style?> id="RefusalVerifyA_<?= (int)$app['tca_id']?>"><a href="javascript:void(0)" onclick="RefusalVerify(<?= (int)$app['tca_id']?>)">不同意</a></p>
                                                	<?php
													}
													?>
                                                    <div class="text_a"><a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="show_site_inner_sms_layer(<?= (int)$app['customers_id']?>,'travel_companion', <?= (int)$my_sent['t_companion_id']?>)">
                                                    给他发信息
                                                    </a></div>
                                                    <!--<p><a href="#" class="ui-gree-btn">我同意</a></p>
                                                    <p><a href="#">不同意</a></p>-->
                                                </div>
                                                <div class="ui-proposer-infolist">
                                                    <p><span>姓名：<em><?php echo tep_db_output($app['tca_cn_name'])?></em></span><span>性别：<em><?php echo $xinbie?></em></span></p>
                                                    <p><span>邮箱：<em><?php echo tep_db_output($app['tca_email_address'])?></em></span></p>
                                                    <p><span>电话：<em><?php echo tep_db_output($app['tca_phone'])?></em></span></p>
                                                    <p><span>人数：<em><?php echo (int)$app['tca_people_num']?>人</em></span></p>
                                                    <p><span>备注：<em><?php echo nl2br(tep_db_output($app['tca_content']))?></em></span></p>
                                                </div>
                                            </div>
                                        </li>
                                        	<?php
											}
										}
                                        ?>
                                        
                                    </ul>
                                    <div class="ui-order-buy">
                                        <!--<p>订购时，房间请选择“结伴拼房<a href="#" class="ui-order-buy-btn">去订购</a></p>-->
                                                                                    <?php
						if($has_booked == true){
							echo '<p><a class="ui-order-buy-btn" href="'. tep_href_link('orders_travel_companion_info.php','order_id='.(int)$ord_rows['orders_id']).'">'. $buttun_text .'</a></p>';
						}
						if($can_booking == true){
						?>
                                            <p>订购时，房间请选择“结伴拼房”<button class="ui-order-buy-btn" onclick="go_to_booking(<?= (int)$my_sent['products_id']?>);">去订购</button><!--<a href="javascript:void(0)" class="ui-order-buy-btn" onclick="go_to_booking(<?= (int)$my_sent['products_id']?>);">去订购</a>--></p>
                                            <?php
						}
						?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php echo db_to_html(ob_get_clean()); 
							/*
							//  原来的老版面HTML 代码 by lwkai 注释 start {
							?>
                            <ul>
                                <li class="Tab_myjb_item_jbt">
                                    <div class="Tab_myjb_item1_l_jbt">
                                        <p><a href="<?= tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.$my_sent['t_companion_id'])?>" class="t_c1">
                                            <?= db_to_html(tep_db_output($my_sent['t_companion_title']));?>
                                            </a><br />
                                            <?php
					   if((int)$my_sent['products_id']){
					   ?>
                                            <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $my_sent['products_id']);?>" class="col_6" target="_blank">
                                            <?= db_to_html(tep_db_output(tep_get_products_name($my_sent['products_id'])));?>
                                            </a>
                                            <?php
					   }
					   ?>
                                        </p>
                                    </div>
                                    <div class="Tab_myjb_item1_r">
                                        <p>
                                            <?= substr($my_sent['add_time'],0,10)?>
                                            <?= db_to_html('回复（'.$my_sent['reply_num'].'）查看（'.$my_sent['click_num'].'）');?>
                                        </p>
                                        <p class="myjb_shenqing" onclick="showHideLyer(this,'myjb_content_sq_<?=$my_sent['t_companion_id']?>','myjb_shenqing_s')">
                                            <?= db_to_html('申请人数（'.$app_num_rows.'）')?>
                                        </p>
                                    </div>
                                    <div id="myjb_content_sq_<?=$my_sent['t_companion_id']?>"  class="Tab_myjb_itemsq" style="display:none;">
                                        <?php
					  if((int)$app_num_rows){
						  //$app_sql = tep_db_query('SELECT * FROM `travel_companion_application` WHERE t_companion_id="'.(int)$my_sent['t_companion_id'].'" ORDER BY tca_id DESC '); // by lwkai add in test
						while($app = tep_db_fetch_array($app_sql)){
					  ?>
                                        <div class="myjb_content_sq">
                                            <?php
											$img_src = "tx_b_s.gif";
											if($app['tca_gender']=="1"){  
												$img_src = "tx_b_s.gif"; 
											}
											if($app['tca_gender']=="2"){  
												$img_src = "tx_g_s.gif"; 
											}
											$img_src = "image/".$img_src;
											$img_src = tep_customers_face((int)$app['customers_id'], $img_src);
											?>
                                            <img src="<?= $img_src;?>" <?= getimgHW3hw($img_src,50,50)?> />
                                            <ul class="sq_group">
                                                <li class="sq_group_items">
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('姓名：')?>
                                                    </div>
                                                    <div  class="sq_group_c1">
                                                        <?= db_to_html(tep_db_output($app['tca_cn_name']))?>
                                                    </div>
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('邮箱：')?>
                                                    </div>
                                                    <div  class="sq_group_c1"><a href="mailto:<?= db_to_html(tep_db_output($app['tca_email_address']))?>">
                                                        <?= db_to_html(tep_db_output($app['tca_email_address']))?>
                                                        </a></div>
                                                </li>
                                                <li class="sq_group_items">
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('英文名：')?>
                                                    </div>
                                                    <div  class="sq_group_c1">
                                                        <?= db_to_html(tep_db_output($app['tca_en_name']))?>
                                                    </div>
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('电话：')?>
                                                    </div>
                                                    <div  class="sq_group_c1">
                                                        <?= db_to_html(tep_db_output($app['tca_phone']))?>
                                                    </div>
                                                </li>
                                                <li class="sq_group_items">
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('性别：')?>
                                                    </div>
                                                    <div  class="sq_group_c1">
                                                        <?= db_to_html(tep_get_gender_string(tep_db_output($app['tca_gender'])))?>
                                                    </div>
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('人数：')?>
                                                    </div>
                                                    <div  class="sq_group_c1">
                                                        <?= (int)$app['tca_people_num']?>
                                                    </div>
                                                </li>
                                                <li class="sq_group_items">
                                                    <div class="sq_group_c">
                                                        <?= db_to_html('留言：')?>
                                                    </div>
                                                    <div  class="sq_group_c2">
                                                        <?= nl2br(db_to_html(tep_db_output($app['tca_content'])))?>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="sq_cz">
                                                <div class="sq_myjb_cz"><span class="agree" id="agree_box_<?=(int)$app['tca_id']?>">
                                                    <?php
							$close_botton_style = ' '; 
							$RefusalVerifyA_style = ' style="display:none" ';
							if($app['tca_verify_status']=="1"){
								echo db_to_html('已同意');
							}elseif($app['tca_verify_status']=="2"){
								echo db_to_html('已拒绝');
							}elseif($has_booked==false){	//未下单才能显示这些
								echo '<button type="button" class="jb_my_sqcz" onclick="agre_verify('.(int)$app['tca_id'].')" >'.db_to_html('同意').'</button>';
								$RefusalVerifyA_style = ' ';
								$close_botton_style = ' style="display:none;" ';
							}
							?>
                                                    </span> </div>
                                                <?php if($has_booked==false){	//未下单才能显示这些?>
                                                <div <?=$close_botton_style?> id="close_botton_<?= (int)$app['tca_id']?>" class="sq_myjb_sh text_a"><a href="javascript:void(0)" onclick="agre_verify(<?= (int)$app['tca_id']?>,'close')" class="jb_fb_tc_bt_a">
                                                    <?= db_to_html('取消')?>
                                                    </a></div>
                                                <div <?=$RefusalVerifyA_style?> class="sq_myjb_sh text_a" id="RefusalVerifyA_<?= (int)$app['tca_id']?>"><a href="javascript:void(0)" onclick="RefusalVerify(<?= (int)$app['tca_id']?>)" class="jb_fb_tc_bt_a">
                                                    <?= db_to_html("拒绝");?>
                                                    </a></div>
                                                <?php
							}
							?>
                                                <div class="text_a"><a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="show_site_inner_sms_layer(<?= (int)$app['customers_id']?>,'travel_companion', <?= (int)$my_sent['t_companion_id']?>)">
                                                    <?= db_to_html('给他发信息')?>
                                                    </a></div>
                                            </div>
                                        </div>
                                        <?php
						}
					  }
					  ?>
                                        <?php
					  //求本贴的已同意人数
					  $verify_sql = tep_db_query('SELECT count(*) as total FROM `travel_companion_application` where t_companion_id="'.$my_sent['t_companion_id'].'" and  tca_verify_status ="1" ');
					  $verify_row = tep_db_fetch_array($verify_sql);
					  ?>
                                        <div class="myjb_content_sq1">
                                            <p>
                                                <?= db_to_html('期望结伴人数'.($my_sent['hope_people_man']+$my_sent['hope_people_woman']+$my_sent['hope_people_child']).'人，申请人数'.$app_num_rows.'人，同意'.$verify_row['total'].'人。');?>
                                                <span id="set_expired_string_<?= $my_sent['t_companion_id']?>">
                                                <?php if($my_sent['has_expired']!="1"){?>
                                                <a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="set_has_expired(<?= $my_sent['t_companion_id']?>,1)">
                                                <?= db_to_html('设为过期贴')?>
                                                </a>
                                                <?php
						 }else{
						?>
                                                <a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="set_has_expired(<?= $my_sent['t_companion_id']?>,0)">
                                                <?= db_to_html('重新开启结伴')?>
                                                </a>
                                                <?php 
						}
						?>
                                                </span></p>
                                            <?php
						if($has_booked == true){
							echo '<button type="button" class="jb_fb_all myjb_content_sq1_button" onclick="location=\''. tep_href_link('orders_travel_companion_info.php','order_id='.(int)$ord_rows['orders_id']).'\'">'.db_to_html($buttun_text).'</button>';
						}
						if($can_booking == true){
						?>
                                            <button type="submit" class="jb_fb_all myjb_content_sq1_button" onclick="go_to_booking(<?= (int)$my_sent['products_id']?>);">
                                            <?= db_to_html('去订购')?>
                                            </button>
                                            <div class="notes_booking">
                                                <?= db_to_html('订购时，房间请选择“结伴拼房”')?>
                                            </div>
                                            <?php
						}
						?>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <?php 
							// 原来老版面的代码 end by lwkai 注释 }
							*/
					}while($my_sent = tep_db_fetch_array($my_sent_sql)); 
				 }
				 ?>
                            <div class="clear"></div>
                        </div>
                        <?php //我发布的结伴 end?>
                        <?php //我申请的结伴 start?>
                        <div class="Tab_Content11_myjb">
                        <?php
						ob_start();
						if((int)$my_app['tca_id']){
							$temp_i = 1;
							do{
							?>
                        <div class="initiate_bg">
                        <div class="initiate" id="Tab_myjb_item<?php echo $temp_i?>_app_<?php echo $my_app['tca_id']?>">
                            <div class="tit1">
                            	<dl>
                                	<dt>
                                    	<ul>
                                        	<li><h4><a href="<?php echo tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$my_app['t_companion_id'])?>" class="font_size14 font_bold" target="_blank"><?php echo tep_db_output($my_app['t_companion_title'])?></a></h4></li>
                                            <li>旅游线路：<?php 
                                            if (tep_not_null($my_app['products_id'])) {
												echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$my_app['products_id']) . '" title="' . tep_db_output($my_app['t_companion_content']) . '" target="_blank">' . tep_get_products_name((int)$my_app['products_id'],'',true) . '</a>';
											} else {
                                            	echo tep_db_output($my_app['t_companion_content']);
                                            }?></li>
                                        </ul>
                                    </dt>
                                    <dd class="color_b3b3b3"><?php echo date('Y-m-d',strtotime($my_app['add_time']))?>发布  回复<strong class="color_orange">（<?php echo (int)$my_app['reply_num']?>）</strong>查看<strong class="color_orange">（<?php echo (int)$my_app['click_num'] ;?>）</strong></dd>
                                    <div class="del_float"></div>
                                </dl>
                            </div>
                            <div class="tit2"><span class="fr"><!--改变注意了？<a href="#">发短信息给他！</a>-->
							<?php
							// 判断状态
								$pay_true = '';  //同意申请后 需要箭头绿色的 ClassName 保存在此
								$pay_class = 'gray'; // 订购产品文字颜色CSS
								$pay_text = '订购产品'; //订购产品文字
								
								$is_order = false; // 是否已经下订单
							
								//我的申请得到同意，等待发起人下一步操作
								 switch ((int)$my_app['tca_verify_status']) {
								 case 1:
									$sqing_image = "sq_2.gif";
									$str_class = "myjb_ck_w bt";
									$sq_group_items_text = "申请已同意。发布者下单后，您即可支付！";
									$agree = '已同意';
									$agreeClass = '';
									$pay_true = 'current';
									
						 
									
									
									
									
								 	break;
								case 2:
									$sq_group_items_text = "对方拒绝结伴";
									$sqing_image = "sq_2.gif";
									$str_class = "myjb_ck_w bt";
									$agree = '已拒绝';
									$agreeClass = '';
									break;
								default:
									$sq_group_items_text = "申请已提交，请等待对方处理，若改变主意你可以取消申请。";
									$sqing_image = "sqing_2.gif";
									$str_class = "myjb_ck_w";
									$agree = '等待同意'; 
									$agreeClass = 'gray';
								}
							
							
									if((int)$my_app['orders_id'] && $my_app['tca_verify_status']=="1"){
										#print_r("SELECT orders_travel_companion_id, orders_travel_companion_status FROM `orders_travel_companion` WHERE orders_id >0 and orders_id='".(int)$my_app['orders_id']."' and customers_id='".$customer_id."' limit 1 ");
										//在结伴同游订单看看是否有我的邮箱，如果有才是已经完成订单
										$order_sql = tep_db_query("SELECT orders_travel_companion_id, orders_travel_companion_status FROM `orders_travel_companion` WHERE orders_id >0 and orders_id='".(int)$my_app['orders_id']."' and customers_id='".$customer_id."' limit 1 ");
										$order = tep_db_fetch_array($order_sql);
										if((int)$order['orders_travel_companion_id']){
											#$sqing_image = "sq_3.gif";
											#$str_class = "myjb_ck_w bt";
											$is_order = true; // 发布者已经下订单
											$pay_class = 'gray';
											$pay_text = '订购产品';
											$sq_group_items_text = "欢迎您的加入，预祝我们的旅途愉快，请您尽快完成支付，开始我们的旅途吧。";
											if((int)$my_app['who_payment'] || $order['orders_travel_companion_status']=="2"){
												$sq_group_items_text = "欢迎您的加入，预祝我们的旅途愉快。";
												$pay_class = '';
												$pay_text = '已订购';
											}
										}
									}
							
							
							
							
						     if($my_app['tca_verify_status']!="1"){	 
						   ?>	<button type="button" class="jb_my_canel myjb_content_sq1_button" onclick="remove_app(<?= $my_app['tca_id']?>)">
                                <?= '取消'?>
                                </button>
                                <?php
						   
						  	 }elseif((int)$order['orders_travel_companion_id'] && (int)$my_app['orders_id']){
								$pay_botton_text = "查看订单";
								if(!(int)$my_app['who_payment'] && $order['orders_travel_companion_status']!="2" ){
									$pay_botton_text = "去支付";
								}
						   		?>
                                <button type="button" class="jb_fb_all myjb_content_sq1_button" onclick="location='<?= tep_href_link('orders_travel_companion_info.php','order_id='.(int)$my_app['orders_id'])?>'">
                                <?= $pay_botton_text;?>
                                </button>
                                <?php 						   
							 }else{
								?>
                                <div class="notes_booking" style="padding-top:0;"><?php echo '改变主意？'?><a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="show_site_inner_sms_layer(<?= (int)$my_app['cus_id']?>,'travel_companion', <?= (int)$my_app['t_companion_id']?>)"><?= '给他发信息'?></a></div>
                                <?php
						     }
						   ?></span>申请状态：<?php
							
								 
								
								// 如果发布人已经下单 而显示已下单状态 为空是还没下单
								if ($is_order == false) {
									echo $sq_group_items_text;
								} else {
									echo '申请已同意！并且发布者已下单！';	
								}
								 ?></div>
                            <div class="cont" id="myjb_content_ck_<?php echo $my_app['tca_id']?>">
                            	<ul class="application">
                               	    <li class="s_1">已提交</li>
                                	<li class="s_2 current"></li>
                                	<li class="s_1 <?php echo $agreeClass?>"><?php echo $agree?></li>
                                	<li class="s_2 <?php echo $pay_true;?>"></li>
                                	<li class="s_1 <?php echo $pay_class?>"><?php echo $pay_text?></li>
                              </ul>
                          </div>
                        </div>
                        </div>
                        <?php  $temp_i ++;
							}while($my_app = tep_db_fetch_array($my_app_sql));
						}
						echo db_to_html(ob_get_clean());
						?>
                            <ul>
                                <?php
								/* by lwkai add 注释 下面是原来老程序代码 怕新移动的有误 所以先保留 start {
				if((int)$my_app['tca_id']){
					
					$my_app = tep_db_data_seek($my_app_sql,0);
					$my_app = tep_db_fetch_array($my_app_sql);
					
					do{
				?>
                                <li class="Tab_myjb_item_jbt" id="Tab_myjb_item1_app_<?= $my_app['tca_id']?>">
                                    <div class="Tab_myjb_item1_ll_jbt">
                                        <p><a href="<?= tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$my_app['t_companion_id'])?>" class="t_c1" target="_blank">
                                            <?= db_to_html(tep_db_output($my_app['t_companion_title']))?>
                                            </a><br />
                                            <?php
										   if((int)$my_app['products_id']){
										   ?>
                                            <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $my_app['products_id']);?>" class="col_6" target="_blank">
                                            <?= db_to_html(tep_db_output(tep_get_products_name($my_app['products_id'])));?>
                                            </a>
                                            <?php
											   }
											   ?>
                                        </p>
                                    </div>
                                    <div class="Tab_myjb_item1_rr">
                                        <p>
                                            <?= substr($my_app['add_time'],0,10)?>
                                            <a href="javascript:void(0)" onclick="show_site_inner_sms_layer(<?= $my_app['cus_id']?>,' travel_companion', <?= $my_app['t_companion_id']?>)">
                                            <?= db_to_html(tep_customers_name($my_app['cus_id']).'发布')?>
                                            </a>
                                            <?= db_to_html(tep_get_gender_string(tep_customer_gender($my_app['customers_id']), 1));?>
                                            <?= db_to_html('回复（'.$my_app['reply_num'].'）查看（'.$my_app['click_num'].'）');?>
                                        </p>
                                        <p class="myjb_shenqing" onclick="showHideLyer(this,'myjb_content_ck_<?= $my_app['tca_id']?>','myjb_shenqing_s')">
                                            <?php
						  $hope_total = $my_app['hope_people_man']+$my_app['hope_people_woman']+$my_app['hope_people_child'];
						  $now_total = $my_app['now_people_man']+$my_app['now_people_woman']+$my_app['now_people_child'];
						  echo db_to_html('期望伴友（'.$hope_total.'）已经加入（'.$now_total.'）');
						  ?>
                                        </p>
                                    </div>
                                    <div id="myjb_content_ck_<?= $my_app['tca_id']?>"  class="Tab_myjb_itemsq">
                                        <div class="myjb_content_sq1">
                                            <?php
						   $img_src = "image/tx_n_s.gif";
						   $img_src = tep_customers_face($customer_id, $img_src);
						  ?>
                                            <img src="<?= $img_src;?>" <?= getimgHW3hw($img_src,50,50)?> />
                                            <ul class="sq_group1">
                                                <li class="sq_group_items1"> <img src="image/sq_1.gif" class="myjb_ck_w2" /><span class="myjb_ck_w bt">
                                                    <?= db_to_html("已经提交申请")?>
                                                    </span><img src="image/jiantou_2.gif" class="myjb_ck_w2" />
                                                    <?php
								 $sq_group_items_text = "你提交的申请已经提交给了发布者，请等待对方处理，如果改变主意你现在可以取消。";
								 $sqing_image = "sqing_2.gif";
								 $str_class = "myjb_ck_w";
								 if($my_app['tca_verify_status']=="1"){
									$sqing_image = "sq_2.gif";
									$str_class = "myjb_ck_w bt";
									$sq_group_items_text = "对方已经同意你的申请。";
								 }
								
								if(
								   ($my_app['tca_verify_status']=="0" && preg_match('/^0:/',$my_app['verify_status_sms'])) || 
								   ($my_app['tca_verify_status']=="1" && preg_match('/^1:/',$my_app['verify_status_sms'])) || 
								   ($my_app['tca_verify_status']=="2" && preg_match('/^2:/',$my_app['verify_status_sms']))  
								   
								   ){
									$sq_group_items_text = '<span style="color:#999999">留言：</span>'.tep_db_output(substr($my_app['verify_status_sms'],2));
								}
								
								//第2步文字和图标提示
								$sqing_str = "对方同意结伴";
								if($my_app['tca_verify_status']=="2"){
									$sqing_str = "对方拒绝结伴";
									$sqing_image = "sq_2.gif";
									$str_class = "myjb_ck_w bt";
								}
								?>
                                                    <img src="image/<?= $sqing_image?>" class="myjb_ck_w2" /><span class="<?= $str_class?>">
                                                    <?= db_to_html($sqing_str)?>
                                                    </span><img src="image/jiantou_2.gif" class="myjb_ck_w2" />
                                                    <?php
								 $sqing_image = "sqing_3.gif";
								 $str_class = "myjb_ck_w";
								 if((int)$my_app['orders_id'] && $my_app['tca_verify_status']=="1"){
									//在结伴同游订单看看是否有我的邮箱，如果有才是已经完成订单
									$order_sql = tep_db_query("SELECT orders_travel_companion_id, orders_travel_companion_status FROM `orders_travel_companion` WHERE orders_id >0 and orders_id='".(int)$my_app['orders_id']."' and customers_id='".$customer_id."' limit 1 ");
									$order = tep_db_fetch_array($order_sql);
									if((int)$order['orders_travel_companion_id']){
										$sqing_image = "sq_3.gif";
										$str_class = "myjb_ck_w bt";
										$sq_group_items_text = "欢迎您的加入，预祝我们的旅途愉快，请您尽快完成支付，开始我们的旅途吧。";
										if((int)$my_app['who_payment'] || $order['orders_travel_companion_status']=="2"){
											$sq_group_items_text = "欢迎您的加入，预祝我们的旅途愉快。";
										}
									}
								 }
								 ?>
                                                    <img src="image/<?= $sqing_image?>" class="myjb_ck_w2" /><span class="<?= $str_class?>">
                                                    <?= db_to_html("已经下单");?>
                                                    </span> </li>
                                                <li class="sq_group_items1">
                                                    <?= db_to_html($sq_group_items_text);?>
                                                </li>
                                            </ul>
                                            <div class="sq_cz1">
                                                <?php
						   if($my_app['tca_verify_status']!="1"){
						   ?>
                                                <button type="button" class="jb_my_canel myjb_content_sq1_button" onclick="remove_app(<?= $my_app['tca_id']?>)">
                                                <?= db_to_html('取消')?>
                                                </button>
                                                <?php
						   }elseif((int)$order['orders_travel_companion_id'] && (int)$my_app['orders_id']){ //
								$pay_botton_text = "查看订单";
								if(!(int)$my_app['who_payment'] && $order['orders_travel_companion_status']!="2" ){
									$pay_botton_text = "去支付";
								}
						   ?>
                                                <button type="button" class="jb_fb_all myjb_content_sq1_button" onclick="location='<?= tep_href_link('orders_travel_companion_info.php','order_id='.(int)$my_app['orders_id'])?>'">
                                                <?= db_to_html($pay_botton_text)?>
                                                </button>
                                                <?php 						   }else{?>
                                                <div class="notes_booking"><?php echo db_to_html('发布者下单后，您就能支付了，如果改变主意')?><a href="javascript:void(0)" class="jb_fb_tc_bt_a" onclick="show_site_inner_sms_layer(<?= (int)$my_app['cus_id']?>,'travel_companion', <?= (int)$my_app['t_companion_id']?>)">
                                                    <?= db_to_html('给他发信息')?>
                                                    </a></div>
                                                <?php
                           }
						   ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
					}while($my_app = tep_db_fetch_array($my_app_sql));
				
				}
				//by lwkai add 注释 上面原来的老代码 防新代码有错 而保留 end  }
				*/
				?>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <?php //我申请的结伴 end?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <!--切换标签 JS--> 
            <script type="text/javascript">
<!--
var Global_defaultTab = 0;	//默认显示的Tab
var Global_defaultTabI = Global_defaultTab;
var Global_defaultTabSelectedClass = "TabbedPanelsTabSelected_myjb"; //选中时使用的css

var anchors = window.location.href.split("#")[1];
if(anchors=="my_information" || anchors==""){
	Global_defaultTab = 0;
}
if(anchors=="my_sent"){
	Global_defaultTab = 1;
}
if(anchors=="my_applied"){
	Global_defaultTab = 2;
}

var DisplayStyle = "onclick";	//设置当点击后才切换
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabPanel1");

//window.setTimeout("alert(9)",10000);

function showTimeBefore(TabPanel_id){
	var anchors1 = window.location.href.split("#")[1];
	var div = document.getElementById(TabPanel_id);
	if(div!=null){
		var ul = div.getElementsByTagName("ul");
		var li = ul[0].getElementsByTagName("li");
		for(i=0; i<li.length; i++){
			if(li[i].title==anchors1 && li[i].className.indexOf("TabbedPanelsTabSelected")==-1 ){
				TabbedPanels1.showPanel(i);
				break;
			}else if(typeof(anchors1)=='undefined' && li[Global_defaultTabI].className.indexOf("TabbedPanelsTabSelected")==-1){
				TabbedPanels1.showPanel(Global_defaultTabI);
				break;
			}
		}
	}
	//document.location.hash+="a-9b-10";
}
setInterval('showTimeBefore("TabPanel1")',300);

//-->
</script> 
        </div>
    </div>
</div>
<?php
//载入发站内短信功能块
require_once('ajax_send_site_inner_sms.php');
?>
