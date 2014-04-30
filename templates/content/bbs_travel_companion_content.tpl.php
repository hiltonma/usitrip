     
  <div class="kuai mar-t" id="bbs_top_page_list"><p style="float:left; padding-top:5px;">
<?php
$TcPaStr = '';
if(tep_not_null($TcPath)){
	$TcPaStr = '&TcPath='.$TcPath;
}
  
  
  if((int)$categories_id){
	echo '<a href="' . tep_href_link('bbs_travel_companion.php','TcPath='.$TcPath) . '" >' .db_to_html(preg_replace('/ .+/','',tep_get_categories_name($categories_id))) . '</a> | ';
  }
  
  //取得上一主题下一主题
  $b_sql = tep_db_query('SELECT t_companion_id FROM `travel_companion` WHERE t_companion_id>'.(int)$rows['t_companion_id'].' AND `status`="1" ORDER BY t_companion_id ASC Limit 1 ');
  $b_row = tep_db_fetch_array($b_sql);
  if((int)$b_row['t_companion_id']){
	echo db_to_html('<a href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$b_row['t_companion_id'].$TcPaStr ).'" >上一主题</a> | ');
  }
  
  $n_sql = tep_db_query('SELECT t_companion_id FROM `travel_companion` WHERE t_companion_id<'.(int)$rows['t_companion_id'].' AND `status`="1" ORDER BY t_companion_id DESC Limit 1 ');
  $n_row = tep_db_fetch_array($n_sql);
  if((int)$n_row['t_companion_id']){
  	echo db_to_html('<a href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$n_row['t_companion_id'].$TcPaStr ).'" >下一主题</a> ');
  }
  if($rows_count_pages>1){
  	echo $rows_page_links_code;
  }
  ?>
  </p>
  <p class="fabiao_r"><a href="<?php echo tep_href_link('companions_process.php')?>" target="_blank"><?php echo db_to_html('结伴同游帮助')?></a><?php echo tep_template_image_submit('huifu.gif', db_to_html('回复'), ' style="width:auto;" onClick="show_and_hidden(&quot;CompanionFormReply&quot;,0);"'); ?>&nbsp;<?php echo tep_template_image_submit('activte-button.gif', db_to_html('发帖'), ' style="width:auto;" showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,1);"'); ?> </p>
  
  </div> 
   
   <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#93CEF3" class="mar-t">
     <tr>
       <td bgcolor="#EDF8FE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="80%" class="cu" style="padding:6px"><?php echo db_to_html(tep_db_output($rows['t_companion_title']))?></td>
             <td width="8%"  style="padding:6px" align="right" nowrap="nowrap">
			 <?php
			 if(tep_not_null($p_model)){
			 	echo '['.db_to_html($p_model).']';
			 }
			 ?>
			 </td>
             <td width="12%"  style="padding:6px" align="right" nowrap="nowrap">
			 <?php if(tep_not_null($p_name)){?>
			 <a href="<?php echo tep_href_link('bbs_travel_companion.php', 'TcPath='.$TcPath.'&products_id=' . (int)$rows['products_id']);?>"><?php echo db_to_html('该产品同类帖')?></a>
			 <?php }?>
			 </td>
           </tr>
       </table></td>
     </tr>
     <tr>
       <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
           <tr>
             <td width="25%" valign="top" bgcolor="#F5F5F5" class="tiezi_leftside" ><p class="cu"><a href="<?php echo tep_href_link('bbs_travel_companion.php','customers_id='.(int)$rows['customers_id'])?>"><?php echo db_to_html(tep_db_output($rows['customers_name']))?></a> 
			<?php 
			$faca_img = 'touxiang_no-sex.gif';
			if((int)$rows['customers_id']){
				if($rows['t_gender']=='1'){
					echo db_to_html(' 先生');
					$faca_img = 'touxiang_boy.gif';
				}
				if($rows['t_gender']=='2'){
					echo db_to_html(' 女士');
					$faca_img = 'touxiang_girl.gif';
				}
			}elseif((int)$rows['admin_id']){
				echo db_to_html('系统管理员');
			}
			
			?>
	 
			  <span class="jifen_num"><?php echo db_to_html('[楼主]')?></span></p>
                 <img src="image/<?php echo $faca_img;?>" />
                 <p>
				<?php
				if((int)$customer_id && $customer_id==$rows['customers_id']){
					echo '<a href="'.tep_href_link('account.php','','SSL').'">'.db_to_html('我的账户').'</a><br />';
				}

				if($rows['hope_departure_date']!="" && (int)$rows['hope_departure_date']){
					echo db_to_html('期望出行时间 ').$rows['hope_departure_date'].'<br />';
				}

				if(tep_not_null($rows['email_address']) && (int)$rows['t_show_email']){
					echo db_to_html('邮箱 ').tep_db_output($rows['email_address']).'<br />';
				}
				
				if(tep_not_null($rows['customers_phone']) && (int)$rows['t_show_phone']){
					echo db_to_html('电话 ').tep_db_output($rows['customers_phone']).'<br />';
				}
				?>
				   
				   </p>
				   
				   </td>
             <td width="75%" valign="top" ><div class="kuai tiezi_r_top"><span class="dazi cu">1#</span>
                   <p style="padding-left:5px"><?php echo db_to_html('发表于：').substr($rows['add_time'],0,16)?> 
				   <!--暂时无法实现此部分内容
				   <a href="JavaScript:void(0)" onclick="quote_bbs('CompanionFormReply',0)"><?php echo db_to_html('引用')?></a> <a href="JavaScript:void(0)" onclick="show_and_hidden('CompanionFormReply',0)"><?php echo db_to_html('回复')?></a>
				   -->
				   | <?php echo db_to_html('回复/查看')?> <?php echo (int)$rows['reply_num']?>/<?php echo (int)$rows['click_num']?>
				   </p>
             </div>
                 <div class="tiezi_post" id="root_tiezi_post"><?php echo db_to_html(nl2br(tep_db_output($rows['t_companion_content'])))?></div>
				 
				 </td>
           </tr>
           <tr>
             <td bgcolor="#F5F5F5" >&nbsp;</td>
             <td valign="top" >
			<?php
			if(tep_not_null($p_name)){
			?>
			 <div class="tiezi_postactions"> <?php echo db_to_html('结伴同游需求来自')?> 
			 	<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$rows['products_id']);?>" title="<?php echo db_to_html($p_name);?>" target="_blank"><?php echo cutword(db_to_html($p_name),68)?></a>
				
			 </div>
			 <?php
			 }
			 ?>
			 
			 </td>
           </tr>
       </table></td>
     </tr>
   </table>
<?php
//跟贴部分
for($i=0; $i<count($dates); $i++){
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#93CEF3" class="mar-t">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr>
         <td width="25%" valign="top" bgcolor="#F5F5F5" class="tiezi_leftside" >
		 <?php
		 echo '<p class="cu"><a href="'.tep_href_link('bbs_travel_companion.php','customers_id='.(int)$dates[$i]['customers_id']).'" >'.db_to_html(tep_db_output($dates[$i]['name'])).'</a>';
		 
		 ?>
		<?php 
		$r_faca_img = 'touxiang_no-sex.gif';
		if($dates[$i]['gender']=='1'){
			echo db_to_html(' 先生');
			$r_faca_img = 'touxiang_boy.gif';
		}
		if($dates[$i]['gender']=='2'){
			echo db_to_html(' 女士');
			$r_faca_img = 'touxiang_girl.gif';
		}
		if($rows['customers_id']==$dates[$i]['customers_id'] && (int)$dates[$i]['customers_id']){
			echo '<span class="jifen_num">'.db_to_html('[楼主]').'</span>';
		}
		?>
		 </p>
		 <img src="image/<?=$r_faca_img?>" />
		 
         <p><?php
				 if((int)$customer_id && $customer_id==$dates[$i]['customers_id']){
				 	echo '<a href="'.tep_href_link('account.php','','SSL').'">'.db_to_html('我的账户').'</a><br />';
				 }
				 ?>
           </p></td>
		   <?php
		   //计算楼层
		   $current_floor = ($current_page * $row_max)+2+$i-$row_max;
		   
		   ?>
        <td width="75%" valign="top" ><div class="kuai tiezi_r_top"><span class="dazi cu"><?php echo $current_floor?>#</span>
            <p style="padding-left:5px"><?php echo db_to_html('发表于：'.substr($dates[$i]['time'],0,16))?> <a href="JavaScript:void(0)" onclick="quote_bbs('CompanionFormReply',<?php echo $dates[$i]['id']?>)"><?php echo db_to_html('引用')?></a> <a href="JavaScript:void(0)" onclick="show_and_hidden('CompanionFormReply', <?php echo $dates[$i]['id']?>)"><?php echo db_to_html('回复')?></a></p></div>
			
			<div class="tiezi_post" id="tiezi_post_<?php echo $dates[$i]['id']?>">
				<?php
				//载入父贴
				//echo (int)$dates[$i]['parent_id'].'&nbsp;&nbsp;'.(int)$dates[$i]['parent_type'];
				
				if((int)$dates[$i]['parent_id'] && !(int)$dates[$i]['parent_type'] ){
					$par_sql = tep_db_query('SELECT * FROM `travel_companion_reply` WHERE t_c_reply_id="'.(int)$dates[$i]['parent_id'].'" and `status`=1 limit 1');
					$par_row = tep_db_fetch_array($par_sql);
					if((int)$par_row['t_c_reply_id']){
				?>
				<div id="parent_bbs_<?php echo $dates[$i]['id']?>" style="color:#666666;border-bottom-style: dashed; border-bottom-width: 1px;">
					<?php echo db_to_html('[回复 '.tep_db_output($par_row['customers_name']).']');?>
					<?php echo nl2br(db_to_html(tep_db_output($par_row['t_c_reply_content'])));?>
				</div>
				<?php
					}
				}else{
					//载入引用贴
					echo db_to_html(get_all_parent_for_quote((int)$dates[$i]['parent_id']));
				}
				?>
				
				<div id="bbs_content_<?php echo $dates[$i]['id']?>"><?php echo nl2br(db_to_html(tep_db_output($dates[$i]['content'])));?></div>
			</div>
			
		  </td>
      </tr>
      <tr>
        <td bgcolor="#F5F5F5" >&nbsp;</td>
        <td valign="top" ><div class="tiezi_postactions"> </div></td>
      </tr>
       
    </table></td>
  </tr>
</table>
<?php
}
?>

<div class="kuai mar-t" id="bbs_bottom_page_list">
</div>
<script type="text/javascript">
	document.getElementById("bbs_bottom_page_list").innerHTML=document.getElementById("bbs_top_page_list").innerHTML;
</script>

<!--这个部分是在客户初次发帖后自己看到的-->
<?php
//取得出发日期与该贴子相近的10-15条贴子
if($send_done=='true' && $rows['hope_departure_date'] > '1970-01-01' ){
	//目录相近最好
	$similar_where .= ' AND hope_departure_date>"1970-01-01" ';
	$date_num = strtotime($rows['hope_departure_date']);
	$date_num_add = $date_num + (5 *24*60*60);
	$date_num_sub = $date_num - (5 *24*60*60);
	$add_final_date = date('Y-m-d', $date_num_add);
	$sub_final_date = date('Y-m-d', $date_num_sub);

	$similar_where .= ' AND hope_departure_date >= "'.$sub_final_date.'" AND  hope_departure_date <= "'.$add_final_date.'" ';
	
	$similar_sql = tep_db_query('SELECT * FROM `travel_companion` WHERE t_companion_id!="'.$rows['t_companion_id'].'" and `status`=1 '.$similar_where.' ORDER BY hope_departure_date LIMIT 10 ');
	$similar_row = tep_db_fetch_array($similar_sql);
	if((int)$similar_row['t_companion_id']){
		$total_sql = tep_db_query('SELECT count(*) as total FROM `travel_companion` WHERE t_companion_id!="'.$rows['t_companion_id'].'" and `status`=1 '.$similar_where.' LIMIT 10 ');
		$total_row = tep_db_fetch_array($total_sql);
?>
<div id="SimilarTcBBS" class="kuai mar-t commmend">
    <p class="cu"><?php echo db_to_html('与您期望出发日期相近的帖子')?>(<span style="color:#F1740E"><?= db_to_html($total_row['total']."个")?></span>)</p><!--条数我觉的就10~15条吧-->
<ul>
    <?php do{?>
	<li>
	<?php echo db_to_html('[出发日期 '. $similar_row['hope_departure_date'].'] '). '<a  href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$similar_row['t_companion_id'] .$TcPaStr).'" target="_blank">'.db_to_html(tep_db_output($similar_row['t_companion_title'])).'</a>';
	//目录
	$c_cate_name = tep_get_categories_name($similar_row['categories_id']);
	$c_cate_name = preg_replace('/ .+/','',$c_cate_name);
	if(tep_not_null($c_cate_name)){
		echo ' <span class="huise">['.db_to_html($c_cate_name).']</span>';
	}
	?>
	</li>
	<?php }while($similar_row = tep_db_fetch_array($similar_sql));?>                             
</ul> 
</div>
<?php
	}
}
?>

<?php //快速回帖start?>
<form action="" method="post" name="CompanionFormReply" id="CompanionFormReply" onsubmit="Submit_Companion_Re('CompanionFormReply'); return false">
<div class="kuai mar-t quick_post"><div class="kuai" style="border-bottom:1px solid #DBDBDB"><p style=" float:left"><b><?php echo db_to_html('快速回帖')?></b></p><p  style=" float:right"><a><?php echo BBS_REGULATIONS_STRING?></a></p></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="float:left; padding-top:10px;">
  <tr>
    <td>
	  <div id="reply_input_<?=(int)$rows['t_companion_id']?>">
          <input name="t_companion_id" type="hidden" id="t_companion_id" value="<?= (int)$rows['t_companion_id']?>" />
	    
	    <input name="parent_id" type="hidden" id="parent_id" value="0" />
	    <input name="parent_type" type="hidden" id="parent_type" value="0">
	    <table width="99%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <td align="right" nowrap="nowrap">&nbsp;</td>
	        <td id="QuoteReply">&nbsp;
				
			</td>
	        </tr>
	      <?php if(!(int)$customer_id){?>
	      <tr>
	        <td height="25" align="right" nowrap="nowrap"><?php echo db_to_html('您未登录');?></td>
	        <td>&nbsp;</td>
	        </tr>
	      <tr>
	        <td height="25" align="right" nowrap="nowrap"><b><?php echo db_to_html('请输入电子邮箱');?></b>&nbsp;</td>
		      <td id="Login_tr_<?=(int)$rows['t_companion_id']?>">
			      <table width="500" border="0" cellpadding="0" cellspacing="0">
			          
			          <tr>
			            <td height="25" align="left" valign="top" class="title_line">
		                <?php echo tep_draw_input_field('email_address','','class="required validate-email" style="width: 160px;" title="'.db_to_html('请输入您的电子邮箱').'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
		    
					    <td height="25" align="right" valign="top" class="title_line"><b><?php echo db_to_html('密码')?></b>&nbsp;<input name="password" type="password" class="required" id="password" title="<?php echo db_to_html('请输入正确的密码')?>" style="width: 160px;" /><span class="inputRequirement"> * </span><?php echo db_to_html('新用户请 <a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');?></td>
				      </tr>
	          </table>							</td>
		    </tr>
	      
	      <?php }?>
	      <tr>
	        <td height="25" align="right"><b><?php echo db_to_html('性别');?></b>&nbsp;</td>
		      <td>
		        <?php
								//$t_show_email ='0';
								echo tep_draw_radio_field('gender', '1','','class="" title="'.db_to_html('请选择您的性别').'" style="width:3%"').db_to_html(' 男');
								echo '&nbsp;&nbsp;';
								echo tep_draw_radio_field('gender', '2','','class="" title="'.db_to_html('请选择您的性别').'" style="width:3%"').db_to_html(' 女');
								?>	          </td>
	        </tr>
	      <tr>
	        <td height="25" align="right"><b><?php echo db_to_html('内容')?></b>&nbsp;</td>
		      <td>
							        
	          <?php echo tep_draw_textarea_field('t_c_reply_content', 'soft', '', '6','',' class="required textarea_bg validation-passed"  style="width:500px;" id="t_companion_content" title="'.db_to_html('请输入回帖内容').'"'); ?>							  </td>
            </tr>
	      
	      <tr>
	        <td align="right">&nbsp;</td>
		      <td height="25" align="left" valign="top" class="title_line"><?php echo tep_template_image_submit('huifu.gif', db_to_html('回复'),'style="width:auto; padding:0px;"'); ?></td>
		    </tr>
	      </table>
	    </div></td>
    </tr>
</table>
</div>
</form>

</div>
<?php //快速回帖end?>

