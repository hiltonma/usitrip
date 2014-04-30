<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:10px" id="tc">
  <tr>
    <td width="5%">&nbsp;</td>
    <td>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td>
		<form action="" method="get" name="search_form" id="search_form">
		<div class="jiansuo" style="padding-top:0px;">
	 <table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td nowrap="nowrap"><span>&nbsp;<?php echo db_to_html('关键字')?></span>
		<?php echo tep_draw_input_field('tc_keyword','',' class="input_search2 search_tc_x"') ?>
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
			<td align="right">
			<?php echo $rows_count_html_code;?>			</td>
		  </tr>
		</table>

  
		</td>
	  </tr>
	</table>

	
	<table class="infoBox" width="100%" border="0" cellspacing="0" cellpadding="0">
	  
	  <tr>
		<td>
	
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
			   <tr class="moduleRow" bgcolor="#edf8fe">
			     <td class="cu" style="padding:6px; color:#223D6A; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #83CDF5;"><?php echo db_to_html('标题')?></td>
			     <td class="cu" style="padding:6px; color:#223D6A; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #83CDF5;"><?php echo db_to_html('发起人')?></td>
			     <td class="cu" style="padding:6px; color:#223D6A; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #83CDF5;"><?php echo db_to_html('回复/查看')?></td>
			     <td class="cu" style="padding:6px; color:#223D6A; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #83CDF5;"><?php echo db_to_html('最后更新')?></td>
		         <td class="cu" style="padding:6px; color:#223D6A; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #83CDF5;"><?php echo db_to_html('操作')?></td>
		      </tr>

			  <?php
			  if(!count($dates)){
			  ?>
			   <tr>
				 <td colspan="5" class="table_tc_list" >&nbsp;<?php echo db_to_html('暂无相关帖子！');?></td>
			</tr>
			  <?php
			  }
			  ?>
			
			  <?php 
			  // loop $dates list start
			  for($i=0; $i<count($dates); $i++){
			  ?> 
			   <tr class="moduleRow">
				<td width="59%" class="table_tc_list" >
					<?php
					//类型
					$d_type = tep_bbs_type_name($dates[$i]['type']);
					if(tep_not_null($d_type)){
						echo '<span class="jifen_num cu">['.db_to_html($d_type).']</span>'; 
					}
					//标题
					echo '<a id="travel_title_'.(int)$dates[$i]['id'].'" href="'.tep_href_link('new_bbs_travel_companion_content.php','TcPath='.$TcPath.'&t_companion_id='.(int)$dates[$i]['id']).'" target="_blank">'.db_to_html(tep_db_output($dates[$i]['title'])).'</a>';
					//目录
					$c_cate_name = tep_get_categories_name($dates[$i]['categories_id']);
					$c_cate_name = preg_replace('/ .+/','',$c_cate_name);
					if(tep_not_null($c_cate_name)){
						echo '<span class="huise" id="travel_categories_'.(int)$dates[$i]['id'].'">['.db_to_html($c_cate_name).']</span>';
					}
					?>
					
					<?php
					//取得bbs 跟贴页数信息 start
					$reply_sql = tep_db_query('SELECT count(*) as total FROM `travel_companion_reply` WHERE t_companion_id="'.$dates[$i]['id'].'" AND `status`="1" ');
					$reply_row = tep_db_fetch_array($reply_sql);
					$reply_total = (int)$reply_row['total'];
					//$row_max = 3;	//每页显示几行
					$row_max = TRAVEL_LIST_MAX_ROW;
					$reply_total_page = ceil($reply_total/$row_max);
					if($reply_total_page>1){
						$reply_page = '<span id="reply_page_'.$dates[$i]['id'].'"> [ ';
						for($p=1; $p<($reply_total_page+1); $p++){
							if($p<=5 || $p==$reply_total_page){
								$reply_page .= ' <a href="'.tep_href_link('new_bbs_travel_companion_content.php','TcPath='.$TcPath.'&t_companion_id='.(int)$dates[$i]['id'].'&page='.$p).'" target="_blank" >'.$p.'</a> ';
							}else{
								$reply_page .= '...';
							}
						}
						$reply_page = preg_replace('/(\.{3})+/', '...', $reply_page);
						$reply_page .= ' ]</span>';
						echo $reply_page;
					}
					?>				 </td>
				<td width="15%" bgcolor="#F5F5F5" class="table_tc_list">
					<?php 
					echo '<a id="travel_customers_name_'.(int)$dates[$i]['id'].'" href="'.tep_href_link('bbs_travel_companion_rightindex.php','customers_id='.(int)$dates[$i]['customers_id']).'">'.db_to_html(tep_db_output($dates[$i]['name'])).'</a>';
					if($dates[$i]['gender']=='1'){echo db_to_html(' <span id="travel_gender_'.(int)$dates[$i]['id'].'">先生</span>');}
					if($dates[$i]['gender']=='2'){echo db_to_html(' <span id="travel_gender_'.(int)$dates[$i]['id'].'">女士</span>');}
					?>		</td>
				<td width="13%" class="table_tc_list">
					<span><?php echo $dates[$i]['reply']?></span>/<?php echo $dates[$i]['click']?>		</td>
				<td width="13%" bgcolor="#F5F5F5" class="table_tc_list">
					<?php
					//取得最后跟贴的人的姓名并连接到最后一页
					$last_reply_sql = tep_db_query('SELECT customers_name FROM `travel_companion_reply` WHERE t_companion_id="'.$dates[$i]['id'].'" AND `status`="1" ORDER BY t_c_reply_id DESC Limit 1 ');
					$last_reply_row = tep_db_fetch_array($last_reply_sql);
					if(tep_not_null($last_reply_row['customers_name'])){
						echo ' <a href="'.tep_href_link('new_bbs_travel_companion_content.php','TcPath='.$TcPath.'&t_companion_id='.(int)$dates[$i]['id'].'&page='.$reply_total_page).'" target="_blank" >'.db_to_html(tep_db_output($last_reply_row['customers_name'])).'</a> <br />';
					}
					?>
					<?php echo '<font id="travel_time_'.(int)$dates[$i]['id'].'">'.substr($dates[$i]['time'],5,11).'</font>'?>		</td>
			    <td width="13%" nowrap bgcolor="#F5F5F5" class="table_tc_list">[<a href="JavaScript:void(0)" onClick="show_edit(<?=$dates[$i]['id']?>)"><?php echo db_to_html('编辑')?></a>]</td>
		      </tr>
			  <?php
			  }
			  // loop $dates list end
			  ?>
		  </table>		</td>
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
    <td width="5%">&nbsp;</td>
  </tr>
</table>

<?php require_once('travel_companion_edit_tpl.php');?>