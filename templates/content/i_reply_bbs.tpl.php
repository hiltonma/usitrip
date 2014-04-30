<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:10px;border:1px solid #AED5FF;" id="tc">
  <tr>
    <td style="padding:8px;">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td>
		<form action="" method="get" name="search_form" id="search_form">
		<div class="jiansuo" style="padding-top:0px;">
	 <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td nowrap="nowrap"><span>&nbsp;<?php echo db_to_html('关键字')?></span>
		<?php echo tep_draw_input_field('tc_keyword','','style="border:1px solid #D5D5D5;height:16px;" class="input_search2 search_tc_x"') ?>
		<?php echo tep_template_image_submit('sousuo_tc.gif', db_to_html('搜索'), 'style="padding-top:2px; padding-left:4px;"'); ?>		</td>
	  </tr>
	</table>
	 </div>

		</form>
		</td>
	  </tr>
	</table>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td id="bbs_top_page_list">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="left">
  <?php
  if((int)$rows_count_pages>1){
  	echo $rows_page_links_code;
  }
  ?>			</td>
			<td align="right" style="height:30px;">
			<?php echo $rows_count_html_code;?>			</td>
		  </tr>
		</table>

  
		</td>
	  </tr>
	</table>

	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  
	  <tr class="infoBoxContents">
		<td>
	<table class="ui-reply-tb">
        <thead>
            <tr>
                <th class="ui-reply-cnt"><?php echo db_to_html('回复内容')?></th>
                <th class="ui-from-article"><?php echo db_to_html('来自帖子')?></th>
                <th class="ui-last-post"><?php echo db_to_html('最后发表')?></th>
                <th class="ui-reply-handle"><?php echo db_to_html('操作')?></th>
            </tr>
        </thead>
        <tbody>
        <?php
			  if(!count($dates)){
			  ?>
			   <tr>
				 <td colspan="4" class="ui-reply-title" style="border:0;">&nbsp;<?php echo db_to_html('暂无相关帖子！');?></td>
			</tr>
			  <?php
			  }
			  ?>
              <?php 
			  // loop $dates list start
			  for($i=0; $i<count($dates); $i++){
			  ?> 
            <tr>
                <td class="ui-reply-title"><div id="reply_content_<?php echo $dates[$i]['id'];?>" >
					<?php
					echo nl2br(db_to_html(tep_db_output($dates[$i]['content'])));
					?>
					</div>
					<form onSubmit="SubmitReply(this); return false;" method="get" name="form_reply_<?php echo $dates[$i]['id'];?>" id="form_reply_<?php echo $dates[$i]['id'];?>" style="display:<?= 'none'?>">
					  <?php echo tep_draw_textarea_field('t_c_reply_content', 'virtual', '30', '5', db_to_html(tep_db_output($dates[$i]['content'])));?>
					  <br>
					  <input name="submit" type="submit" id="submit" value="<?php echo db_to_html(' 确定 ')?>">
					  <input type="reset" value="<?php echo db_to_html(' 重置 ')?>">
			          <input name="t_c_reply_id" type="hidden" id="t_c_reply_id" value="<?php echo $dates[$i]['id'];?>">
			      </form></td>
                <td class="ui-reply-title"><?php 
					//取得源贴标题
					$sql_t = tep_db_query('SELECT t_companion_title FROM `travel_companion` WHERE t_companion_id="'.(int)$dates[$i]['t_companion_id'].'" limit 1 ');
					$row_t = tep_db_fetch_array($sql_t);
					echo '<p><a  href="'.tep_href_link('new_bbs_travel_companion_content.php','TcPath='.$TcPath.'&t_companion_id='.(int)$dates[$i]['t_companion_id']).'" target="_blank">'.db_to_html(tep_db_output($row_t['t_companion_title'])).'</a></p>';
					
					?></td>
                <td class="ui-post-author"><p>by <?php 
			//取得最后跟贴的人的姓名并连接到最后一页
			$last_reply_sql = tep_db_query('SELECT customers_name FROM `travel_companion_reply` WHERE t_companion_id="'.$dates[$i]['t_companion_id'].'" AND `status`="1" ORDER BY t_c_reply_id DESC Limit 1 ');
			$last_reply_row = tep_db_fetch_array($last_reply_sql);
			if(tep_not_null($last_reply_row['customers_name'])){
				$TcPaStr = '';
				if(tep_not_null($TcPath)){
					$TcPaStr = '&TcPath='.$TcPath;
				}
				$ccut_name = db_to_html(tep_db_output($last_reply_row['customers_name']));
				
				echo ' <a class="t_c" href="'.tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$dates[$i]['id'].$TcPaStr.'&page='.$reply_total_page).'" title="'.$ccut_name.'">'.cutword($ccut_name,8,'').'</a>';
			}
			?></p></td>
                <td class="ui-toview"><p><a href="<?php echo tep_href_link('new_bbs_travel_companion_content.php','TcPath='.$TcPath.'&t_companion_id='.(int)$dates[$i]['t_companion_id'])?>" target="_blank" class="ui-viewbtn"><?php echo db_to_html('去看看')?></a><br/><a href="JavaScript:void(0)" onClick="show_edit(form_reply_<?php echo $dates[$i]['id'];?>, document.getElementById('reply_content_<?php echo $dates[$i]['id'];?>'))"><?php echo db_to_html('编辑')?></a></p></td>
            </tr>
			<?php
			  }
			  // loop $dates list end
			  ?>
        </tbody>
    </table>
		
		<?php /*?><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#dcdcdc">
			   <tr class="moduleRow" bgcolor="#edf8fe">
			     <td align="center" class="cu" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif) repeat-x;height:33px;padding:6px;"><?php echo db_to_html('回贴内容')?></td>
			     <td align="center" class="cu" style="padding:6px;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif) repeat-x; "><?php echo db_to_html('源贴标题')?></td>
			     <td align="center" class="cu" style="padding:6px;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif) repeat-x;"><?php echo db_to_html('最后更新')?></td>
		         <td align="center" class="cu" style="padding:6px;background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif) repeat-x;"><?php echo db_to_html('操作')?></td>
		      </tr>

			  <?php
			  if(!count($dates)){
			  ?>
			   <tr>
				 <td colspan="4" class="table_tc_list" style="border:0;" bgcolor="#fff" >&nbsp;<?php echo db_to_html('暂无相关贴子！');?></td>
			</tr>
			  <?php
			  }
			  ?>
			
			  <?php 
			  // loop $dates list start
			  for($i=0; $i<count($dates); $i++){
			  ?> 
			   <tr class="moduleRow">
				<td class="table_tc_list">
					<div id="reply_content_<?php echo $dates[$i]['id'];?>" >
					<?php
					echo nl2br(db_to_html(tep_db_output($dates[$i]['content'])));
					?>
					</div>
					<form onSubmit="SubmitReply(this); return false;" method="get" name="form_reply_<?php echo $dates[$i]['id'];?>" id="form_reply_<?php echo $dates[$i]['id'];?>" style="display:<?= 'none'?>">
					  <?php echo tep_draw_textarea_field('t_c_reply_content', 'virtual', '30', '5', db_to_html(tep_db_output($dates[$i]['content'])));?>
					  <br>
					  <input name="submit" type="submit" id="submit" value="<?php echo db_to_html(' 确定 ')?>">
					  <input type="reset" value="<?php echo db_to_html(' 重置 ')?>">
			          <input name="t_c_reply_id" type="hidden" id="t_c_reply_id" value="<?php echo $dates[$i]['id'];?>">
			      </form>
					</td>
				<td bgcolor="#F5F5F5" class="table_tc_list">
					<?php 
					//取得源贴标题
					$sql_t = tep_db_query('SELECT t_companion_title FROM `travel_companion` WHERE t_companion_id="'.(int)$dates[$i]['t_companion_id'].'" limit 1 ');
					$row_t = tep_db_fetch_array($sql_t);
					echo '<a  href="'.tep_href_link('new_bbs_travel_companion_content.php','TcPath='.$TcPath.'&t_companion_id='.(int)$dates[$i]['t_companion_id']).'" target="_blank">'.db_to_html(tep_db_output($row_t['t_companion_title'])).'</a>';
					
					?>					</td>
				<td width="13%" class="table_tc_list">
					
					<?php echo substr($dates[$i]['time'],5,11)?>		</td>
			    <td width="13%" nowrap bgcolor="#F5F5F5" class="table_tc_list">[<a href="JavaScript:void(0)" onClick="show_edit(form_reply_<?php echo $dates[$i]['id'];?>, document.getElementById('reply_content_<?php echo $dates[$i]['id'];?>'))"><?php echo db_to_html('编辑')?></a>]</td>
		      </tr>
			  <?php
			  }
			  // loop $dates list end
			  ?>
		  </table><?php */?>		</td>
	  </tr>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td  id="bbs_bottom_page_list">
		<script type="text/javascript">
			document.getElementById("bbs_bottom_page_list").innerHTML=document.getElementById("bbs_top_page_list").innerHTML;
		</script>
		</td>
	  </tr>
	</table>

	</td>
  </tr>
</table>

