<script type="text/javascript">
function Check_Onfocus(obj){
	if(obj.value=="<?php echo PLX_INPUT_KEY?>"){
		obj.value="";
		obj.className='input_search2';
	}
}
function Check_Onblur(obj){
	if(obj.value=="" || obj.value=="<?php echo PLX_INPUT_KEY?>"){
		obj.value="<?php echo PLX_INPUT_KEY?>";
	}
}
</script>

<table cellSpacing=0 cellPadding=0 width="100%" border=0 style="margin-top:5px;">
              <tbody>
              <tr>
                <td class=main style="BACKGROUND-COLOR: rgb(255,255,255)" 
                vAlign=top width="72%">
                <div class="product_detail_content"  style=" width:98%" >
      <div class="product_tab_b_n" style=" width:97%">
      <div class=tab1 id=tab1>
	  <UL>
        <LI <?= $li_class0?>><a href="<?php echo tep_href_link('travel_companion_list.php','top_class=west')?>"><?php echo USA_WEST?></a></LI>
        <LI <?= $li_class1?>><a href="<?php echo tep_href_link('travel_companion_list.php','top_class=east')?>"><?php echo USA_EAST?></a></LI>
        <LI <?= $li_class2?>><a href="<?php echo tep_href_link('travel_companion_list.php','top_class=hawaii')?>"><?php echo USA_HAWAII?></a></LI>
        <LI <?= $li_class3?>><a href="<?php echo tep_href_link('travel_companion_list.php','top_class=canada')?>"><?php echo USA_CANADA?></a></LI></UL></div></div></div>
                <div class="friend-active-bar">
				<div style="float:left; padding-top:10px;">
				<form action="<?php echo tep_href_link('bbs_travel_companion_content.php')?>" method="get" name="sc_form" id="sc_form">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td valign="middle">&nbsp;</td>
                                <td valign="middle" nowrap="nowrap"><b><?php echo db_to_html('景点')?></b>&nbsp;</td>
                                <td valign="middle">
								<?php
								//
								$sel_option =array();
								$sel_option[0] = array('id'=>'all','text'=>db_to_html('全部'));
								$sel_option[1] = array('id'=>'noid','text'=>db_to_html('原创'));
								$sel_option[2] = array('id'=>'west','text'=>db_to_html('美国西海岸'));
								$sel_option[3] = array('id'=>'east','text'=>db_to_html('美国东海岸'));
								$sel_option[4] = array('id'=>'hawaii','text'=>db_to_html('夏威夷'));
								$sel_option[5] = array('id'=>'canada','text'=>db_to_html('加拿大'));
								echo tep_draw_pull_down_menu('top_class',$sel_option);
								?>
								
								</td>
								<td height="22" align="center">&nbsp;</td>
                                <td align="center">
								<?php
								if(!tep_not_null($_GET['s_c_keyword'])){
									$s_c_keyword = PLX_INPUT_KEY;
								}
								echo tep_draw_input_field('s_c_keyword','','class="input_search2" size="22" onfocus="Check_Onfocus(this)"; onBlur="Check_Onblur(this)" style="margin-top:0px; margin-bottom:0px;" ');
								?>

								</td>
                                <td valign="middle">&nbsp;</td>
                                <td valign="middle"><?php echo tep_image_submit('button_search.gif', db_to_html('搜索')) ?></td>
                                <td valign="middle">&nbsp;</td>
                                <td align="right" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(!(int)$customer_id){ echo db_to_html('要发起活动，请先<a href="'.tep_href_link('login.php').'" class="sp3">登录</a>或<a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');}?>
				 				</td>
				  				<td align="right"><?php echo '<a href="javascript:void(0);" onClick="showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,1);" >' . tep_template_image_button('activte-button.gif', db_to_html('发起活动'),'style="float:left; margin-left:5px;"') . '</a>'; ?>
								&nbsp;<a target="_blank" href="<?php echo tep_href_link('companions_process.php')?>"><img src="image/what.gif" alt="<?php echo db_to_html('帮助')?>" border="0" align="absmiddle" /></a>
								</td>
                              </tr>
                  </table>
				</form>
				</div>
				
                </div>
				
                <div class="friend-active-bar" style="margin-top:0px; margin-bottom:7px; border-bottom:1px solid #93CEF3;"><div style="float:left; font-size:14px;"><b><?php echo html_to_db($categories_name);?></b></div>
                </div>
                
				
				<div class="friend-active-bar" style="margin-top:0px; padding-top:0px;">
<?php			
//$categories_id 
//取得所有帖子的目录
$c_cat_sql_str = 'select cd.categories_id, cd.categories_name, count(ct.categories_id) as total_sum from `travel_companion` ct left join `categories_description` cd on cd.categories_id = ct.categories_id where ct.status=1 and (cd.language_id=1 '.$where_cate_in.' || ct.categories_id=0) group by ct.categories_id order by ct.categories_id desc ';

$travel_split = new splitPageResults($c_cat_sql_str, '5');

$c_cat_sql = tep_db_query($travel_split->sql_query);

while($c_cat_rows = tep_db_fetch_array($c_cat_sql)){
?>				
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr><td colspan="4">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DAF0FD" style="border:1px solid #108BCE; border-left:0px; border-right:0px;">
                    <tr>
                      <td width="75%" height="27" class="cu"><p style="padding-left:8px; font-weight:bold;" >
					  <?php
					  if((int)$c_cat_rows['categories_id']){
					  	echo db_to_html($c_cat_rows['categories_name']);
					  }else{
					  	echo db_to_html('原创');
					  }
					  ?>
					  </p></td>
                      <td width="15%"><?php echo db_to_html('主题：'.$c_cat_rows['total_sum'].'个');?>&nbsp;</td>
                      <td width="10%" class="cu"><a href="<?php echo tep_href_link('bbs_travel_companion_content.php','categories_id='.(int)$c_cat_rows['categories_id'])?>"><?php echo db_to_html('查看全部')?></a></td>
                    </tr>
                  </table>
                  </td>
                  </tr>
                  <tr>
				  <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <?php 
				  //取得该目录的前三个最新贴
				  $companion_sql= tep_db_query('SELECT * FROM `travel_companion` WHERE categories_id ="'.$c_cat_rows['categories_id'].'" AND status =1 order by last_time DESC Limit 3');
				  while($companion_rows=tep_db_fetch_array($companion_sql)){
				  ?>  
					<tr>
                    <td width="60%" height="52" bgcolor="#ECECEC" style="border-bottom:1px solid #fff;"><p style="padding-left:8px">
					<?php
					$fr_name = '活动名：';
					if((int)$companion_rows['products_id']){
						$products_name = tep_get_products_name((int)$companion_rows['products_id']);
						$fr_name = '<a href="'.tep_href_link('product_info.php','products_id='.(int)$companion_rows['products_id']).'" title="'.$products_name.'">'.cutword($products_name,34);
						$p_sql = tep_db_query('SELECT products_model FROM `products` WHERE products_id="'.(int)$companion_rows['products_id'].'" limit 1');
						$p_row = tep_db_fetch_array($p_sql);
						$fr_name.=' ['.$p_row['products_model'].']';
						$fr_name.= '</a><br>';
					}
					
					echo db_to_html($fr_name).'<a class="sp3" href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$companion_rows['t_companion_id']).'">'.db_to_html(tep_db_output($companion_rows['t_companion_title'])).'</a>';
					?></p></td>
                    <td nowrap="nowrap" bgcolor="#ECECEC" style="border-bottom:1px solid #fff;">
					<?php echo db_to_html('发起人：'.tep_db_output($companion_rows['customers_name']));?>&nbsp;					</td>
                    <td nowrap="nowrap" bgcolor="#ECECEC" style="border-bottom:1px solid #fff;"><?php echo db_to_html('时间：'.preg_replace('/ .*$/','',$companion_rows['last_time']));?>&nbsp;</td>
                    <td nowrap="nowrap" bgcolor="#ECECEC" style="border-bottom:1px solid #fff;"><?php echo db_to_html('回帖：'.$companion_rows['reply_num']);?>&nbsp;</td>
					<td nowrap="nowrap" bgcolor="#ECECEC" style="border-bottom:1px solid #fff;"><?php echo db_to_html('点击：'.$companion_rows['click_num']);?>&nbsp;</td>
                  </tr>
				  <?php
				  }
				  ?>
				  
                  </table></td>
                  </tr>
				  </table>
<?php
}
?>				  
				  
				<div class="jianyi_title_content page_link" style="margin-left:15px; margin-top:0px; width:650px;">
				
				<?php echo TEXT_RESULT_PAGE . ' ' . $travel_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page'))); ?>
				
				</div>

                </div>

                
				
				<div class="jianyi_title_content page_link" style="margin-left:15px; margin-top:0px; width:650px;"></div>                  </td>
                <td valign="top" bgColor=#e3f5ff><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  
                  <tr>
                    <td>
					<div class="help_phone_call">
					<?php 
					echo db_to_html('有任何问题请拨打<br>
					<span style="font-weight:bold;">1-626-898-7800 888-887-2816</span><br>
					或<span style="font-weight:bold;">0086-4006-333-926</span>或客服邮箱<br>
					<span style="font-weight:bold;">service@usitrip.com</span>
					')?>
					</div></td>
                  </tr>
                  <tr>
                    <td><div style="border:1px solid #FFD011; width:254px; margin-top:10px; margin-left:6px; background:url(image/youshi_bg.gif); background-repeat:repeat-x;"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                          <td height="25" align="center"><img src="image/list_jiantou3.gif"></td>
                          <td width="92%"><a href="<?php echo tep_href_link('our-advantages.php')?>" class="text"><?php echo db_to_html('高品质旅游行程保证')?></a></td>
                          </tr>
                          <tr>
                          <td height="25" align="center"><img src="image/list_jiantou3.gif"></td>
                          <td width="92%"><a href="<?php echo tep_href_link('our-advantages.php')?>" class="text"><?php echo db_to_html('BBB评级将走四方网评定为A-级')?></a>&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="25" align="center"><img src="image/list_jiantou3.gif"></td>
                            <td><a href="<?php echo tep_href_link('our-advantages.php')?>" class="text">GODADDY.COM<?php echo db_to_html('认证的安全可靠网站')?></a></td>
                          </tr>
                          <tr>
                            <td height="25" align="center"><img src="image/list_jiantou3.gif"></td>
                            <td><a href="<?php echo tep_href_link('our-advantages.php')?>" class="text">7/24<?php echo db_to_html('小时安全便捷网站服务')?></a></td>
                          </tr>
                          <tr>
                            <td height="25" align="center"><img src="image/list_jiantou3.gif"></td>
                            <td><a href="<?php echo tep_href_link('our-advantages.php')?>" class="text">1000+<?php echo db_to_html('精选行程适合各种需求')?></a></td>
                          </tr>
                          <tr>
                            <td height="25" align="center"><img src="image/list_jiantou3.gif"></td>
                            <td><a href="<?php echo tep_href_link('our-advantages.php')?>" class="text"><?php echo db_to_html('高度关注客户感受')?></a></td>
                          </tr>  
                      </table>
                    </div>                    </td>
                  </tr>
                </table></td>
                </tr></tbody></table>

<?php require_once('travel_companion_tpl.php');?>