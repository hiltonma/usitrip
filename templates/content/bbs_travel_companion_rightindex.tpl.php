     

<?php //帖子列表start?>	

<?php 
//如果是在某人或产品的帖子列表则不显示以下内容 start
if(!tep_not_null($customers_id) && !(int)$products_id){
?>

<DIV class="tab_prod_b" style=" width:100%; margin-top:10px;">
      <DIV class="tab_prod" >
      <UL>
        <?php
		//要去除的GET参数
		$close_parameters = array('page', 'sort','sort_name', 'x', 'y','cPath');
		
		?>
		<LI id="new" class="<?=$new_tag_class?>" style="WIDTH:90px"><a href="<?php echo tep_href_link('bbs_travel_companion_rightindex.php','sort=d&sort_name=new&'.tep_get_all_get_params($close_parameters))?>" style="padding-top:5px; padding-right:0px; padding-left:0px"><?php echo db_to_html('最 新')?></a></LI>
        <LI id="hit" class="<?=$hit_tag_class?>" style="WIDTH:90px"><a href="<?php echo tep_href_link('bbs_travel_companion_rightindex.php','sort=d&sort_name=hit&'.tep_get_all_get_params($close_parameters))?>" style="padding-top:5px; padding-right:0px; padding-left:0px"><?php echo db_to_html('热 帖')?></a> </LI>
        </UL>
		
		</DIV></DIV>

<?php
}
//如果是在某人或产品的帖子列表则不显示以下内容 end
?>

  <div class="kuai mar-t" id="bbs_top_page_list">
  <?php
  if((int)$rows_count_pages>1){
  	echo '<div class="fabiao_l">'.$rows_page_links_code.'</div>';
	//echo $rows_count;
  }
  ?>
  <p class="fabiao_r"><a href="<?php echo tep_href_link('companions_process.php')?>" target="_blank" class="jifen_num cu"><?php echo db_to_html('结伴同游帮助')?></a><?php echo tep_template_image_submit('activte-button.gif', db_to_html('发帖'), ' style="width:auto;" showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,1);"'); ?></p>
  </div> 
   <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#93CEF3" class="mar-t">
  <tr>
    <td bgcolor="#EDF8FE">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="57%" class="cu" style="padding:6px"><?php echo db_to_html('标题')?></td>
        <td width="15%" class="cu"  style="padding:6px"><?php echo db_to_html('发起人')?></td>
        <td width="13%" class="cu" style="padding:6px"><?php echo db_to_html('回复/查看')?></td>
        <td width="13%" class="cu" style="padding:6px"> <?php echo db_to_html('最后更新')?></td>
      </tr>
    </table>
    </td>
  </tr>  
 

  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <?php
	  if(!count($dates)){
	  ?>
	   <tr>
	     <td colspan="4" class="table_tc_list" >&nbsp;<?php echo db_to_html('暂无相关帖子！');?></td>
        </tr>
      <?php
	  }
	  ?>
	
	  <?php 
	  // loop $dates list start
		//是否显示目录判断，如果当前目录没有子目录的时候则不显示目录内容
		/*$check_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id='" .(int)$Tccurrent_category_id ."' limit 1 ");
		$check_row = tep_db_fetch_array($check_query);*/
		$check_row = MCache::fetch_categories((int)$Tccurrent_category_id);
	  
	  for($i=0; $i<count($dates); $i++){
	  	$class_td = 'table_tc_list';
	  	if(($i+1)%2==0){
			$class_td = 'table_tc_list table_tc_listbg';
		}
	  
	  ?> 
	   <tr>
        <td width="59%" class="<?=$class_td?>" >
			<?php
			//类型
			$d_type = tep_bbs_type_name($dates[$i]['type']);
			if(tep_not_null($d_type)){
				echo '<span class="jifen_num cu">['.db_to_html($d_type).']</span>'; 
			}
			//标题
			$TcPathForTitle = tep_get_category_patch((int)$dates[$i]['categories_id']);
			$TcPaStr = '';
			if(tep_not_null($TcPathForTitle)){
				$TcPaStr = '&TcPath='.$TcPathForTitle;
			}
			echo '<a class="dazi" href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$dates[$i]['id'].$TcPaStr).'" target="_blank">'.db_to_html(tep_db_output($dates[$i]['title'])).'</a>';

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
						$TcPaStr = '';
						if(tep_not_null($TcPath)){
							$TcPaStr = '&TcPath='.$TcPath;
						}
						$reply_page .= ' <a href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$dates[$i]['id'].$TcPaStr.'&page='.$p).'" target="_blank" >'.$p.'</a> ';
					}else{
						$reply_page .= '...';
					}
				}
				$reply_page = preg_replace('/(\.{3})+/', '...', $reply_page);
				$reply_page .= ' ]</span>';
				echo $reply_page;
			}
			//取得bbs 跟贴页数信息 end
			
			//目录
			if((int)$check_row['categories_id'] &&(int)$dates[$i]['categories_id'] && !(int)$products_id){

				$ChildTcPath = tep_get_category_patch((int)$dates[$i]['categories_id']);
				
				$c_cate_name = tep_get_categories_name($dates[$i]['categories_id']);
				$c_cate_name = preg_replace('/ .+/','',$c_cate_name);
				if(tep_not_null($c_cate_name)){
					echo '<span class="huise">[<a href="'.tep_href_link('bbs_travel_companion_rightindex.php','TcPath='.$ChildTcPath).'">'.db_to_html($c_cate_name).'</a>]</span>';
				}
				
			}elseif(!(int)$products_id){	//如果不显示目录就显示当前贴所在的产品
				
				$p_name = tep_get_products_name((int)$dates[$i]['products_id']);
				if(tep_not_null($p_name)){
					$PrcPath = tep_get_category_patch((int)$dates[$i]['categories_id']);
					echo '<br>'.'<p title="'.db_to_html($p_name).'" ><a href="'.tep_href_link('bbs_travel_companion_rightindex.php', 'TcPath='.$PrcPath.'&products_id=' . (int)$dates[$i]['products_id']).'" title="'.db_to_html($p_name).'" >'.cutword(db_to_html($p_name),62,'').'<span>'.db_to_html('同类帖').'</span></a></p>';
				}
			}

			?>
			</td>
        <td width="15%" class="<?=$class_td?>">
			<?php 
			if((int)$dates[$i]['customers_id']){
				echo '<a href="'.tep_href_link('bbs_travel_companion_rightindex.php','customers_id='.(int)$dates[$i]['customers_id']).'">'.db_to_html(tep_db_output($dates[$i]['name'])).'</a>';
				if($dates[$i]['gender']=='1'){echo db_to_html(' 先生');}
				if($dates[$i]['gender']=='2'){echo db_to_html(' 女士');}
			}elseif((int)$dates[$i]['admin_id']){
				echo db_to_html('系统管理员');
			}
			
			
			?>		</td>
        <td width="13%" class="<?=$class_td?>">
			<span><?php echo $dates[$i]['reply']?></span>/<?php echo $dates[$i]['click']?>		</td>
        <td width="13%" class="<?=$class_td?>">
			<?php
			//取得最后跟贴的人的姓名并连接到最后一页
			$last_reply_sql = tep_db_query('SELECT customers_name FROM `travel_companion_reply` WHERE t_companion_id="'.$dates[$i]['id'].'" AND `status`="1" ORDER BY t_c_reply_id DESC Limit 1 ');
			$last_reply_row = tep_db_fetch_array($last_reply_sql);
			if(tep_not_null($last_reply_row['customers_name'])){
				$TcPaStr = '';
				if(tep_not_null($TcPath)){
					$TcPaStr = '&TcPath='.$TcPath;
				}
				echo ' <a href="'.tep_href_link('bbs_travel_companion_content.php','t_companion_id='.(int)$dates[$i]['id'].$TcPaStr.'&page='.$reply_total_page).'" target="_blank" >'.db_to_html(tep_db_output($last_reply_row['customers_name'])).'</a> <br />';
			}
			?>
			<?php echo substr($dates[$i]['time'],5,11)?>		</td>
      </tr>
	  <?php
	  }
	  // loop $dates list end
	  ?>
       
    </table></td>
  </tr>
</table>
<div class="kuai mar-t" id="bbs_bottom_page_list"></div>
<script type="text/javascript">
	document.getElementById("bbs_bottom_page_list").innerHTML=document.getElementById("bbs_top_page_list").innerHTML;
</script>


<?php //帖子列表end?>	

<?php 
//如果是在某人的帖子列表则不显示以下内容 start
if(!tep_not_null($customers_id)){
?>
<div class="kuai mar-t shaixuan"><p style="float:left; padding-left:4px;">

<?php
$option_array = array();
$option_array[0]=array('id'=>'','text'=>db_to_html('最近访问的版块'));
if(count($_COOKIE['view_history_bbs'])){
	for($i=(count($_COOKIE['view_history_bbs'])-1); $i>=0; $i--){
		$tmp_array = explode('_',$_COOKIE['view_history_bbs'][$i]['TcPath']);
		$cat_name_string='';
		for($j=0; $j<count($tmp_array); $j++){
			$cat_name_string .= preg_replace('/ .+/','',tep_get_categories_name((int)$tmp_array[$j])).' &gt; ';
		}
		if(tep_not_null($_COOKIE['view_history_bbs'][$i]['TcPath'])){
			$option_array[count($option_array)]=array('id'=>$_COOKIE['view_history_bbs'][$i]['TcPath'],'text'=>db_to_html($cat_name_string));
		}
	}
}else{
	$option_array[0]=array('id'=>'','text'=>db_to_html('最近访问的版块[空]'));
}
echo tep_draw_pull_down_menu('view_history', $option_array, '', 'id="view_history" class="input_search" style="width:auto; height:20px " onchange="MM_jumpMenu_hoistory(this)"');
?>

</p><p style="float:right; padding-right:4px;"> 

<?php
$option_array = array();
$option_array[0]=array('id'=>'','text'=>db_to_html('查看主题时间范围'));
$option_array[1]=array('id'=>'this_month','text'=>db_to_html('本周'));
$option_array[2]=array('id'=>'this_week','text'=>db_to_html('本月'));

echo tep_draw_pull_down_menu('time_frame', $option_array, '', 'id="time_frame" class="input_search" style="width:125px; height:20px " onchange="MM_jumpMenu(this,&quot;'.tep_get_all_get_params(array('page','time_frame', 'x', 'y')).'&quot;)"');
?>

&nbsp;
<?php
echo db_to_html('排序');
$option_array = array();
$option_array[0]=array('id'=>'new','text'=>db_to_html('回复时间'));
$option_array[1]=array('id'=>'reply_num','text'=>db_to_html('回复数'));
$option_array[2]=array('id'=>'hit','text'=>db_to_html('点击数'));

echo tep_draw_pull_down_menu('sort_name', $option_array, '', 'id="sort_name" class="input_search" style="width:125px; height:20px " onchange="MM_jumpMenu(this,&quot;'.tep_get_all_get_params(array('page', 'sort','sort_name', 'x', 'y')).'&quot;)"');
?>

</p></div>

<!--下面这个部分是登录才可以看到的快速发帖 同时必须在有分类页面才能显示-->
<?php if((int)$Tccurrent_category_id ){	?>
<div class="kuai mar-t quick_post"><div class="kuai" style="border-bottom:1px solid #DBDBDB"><p style=" float:left"><b><?php echo db_to_html('快速发新活动')?></b></p><p  style=" float:right"><a><?php echo BBS_REGULATIONS_STRING?></a></p></div>
<form action="" method="post" name="CompanionFormFast" id="CompanionFormFast" onSubmit="Submit_Companion('CompanionFormFast'); return false" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="float:left;">
  <tr>
    <td height="21" colspan="7" align="right">&nbsp;</td>
    </tr>
  <tr>
    <td height="21" align="right"><b><?php echo db_to_html('位置')?></b></td>
    <td>&nbsp;</td>
    <td colspan="4">
<?php
if (isset($TcPath_array) && count($TcPath_array) ) {
	$categories_id = $TcPath_array[count($TcPath_array)-1];
}

if((int)$categories_id){
	$categories_string = preg_replace('/ .+/','',tep_get_categories_name($categories_id,1));
	echo db_to_html($categories_string). ' &gt;&gt; ';
}
if((int)$products_id){
	$products_name = tep_get_products_name($products_id, 1);
	echo '<span title="'.db_to_html($products_name).'">'.cutword(db_to_html($products_name),86-strlen($categories_string)).'</span> &gt;&gt; ';
}
?>
<?php 
echo tep_draw_hidden_field('categories_id');
echo tep_draw_hidden_field('products_id');
?>	
	</td>
    <td width="8%" rowspan="8">&nbsp;</td>
  </tr>
  <tr>
    <td width="15%" height="21" align="right"><b><?php echo db_to_html('姓名')?></b></td>
    <td width="1%">&nbsp;</td>
    <td width="28%"><?php echo tep_draw_input_field('customers_name','','  class="input_search2" title="'.db_to_html('请输入姓名').'"') ?><em>*</em></td>
    <td width="10%" align="right"><b><?php echo db_to_html('主题')?></b></td>
    <td width="1%"></td>
    <td width="37%">
	<?php echo tep_draw_input_field('t_companion_title','','  class="input_search2" title="'.db_to_html('请为活动输入标题').'" ') ?>
	<em>*</em>	</td>
    </tr>
  <tr>
    <td height="21" align="right"><b><?php echo db_to_html('性别')?></b></td>
    <td>&nbsp;</td>
    <td>
	<?php
	//$t_show_email ='0';
	echo tep_draw_radio_field('t_gender', '1','','class="" title="'.db_to_html('请选择您的性别').'" style="width:6%"').db_to_html(' 男');
	echo '&nbsp;&nbsp;';
	echo tep_draw_radio_field('t_gender', '2','','class="" title="'.db_to_html('请选择您的性别').'" style="width:6%"').db_to_html(' 女');
	?>	</td>
    <td align="right"><b><?php echo db_to_html('内容')?></b></td>
    <td>&nbsp;</td>
    <td rowspan="6" valign="top"><label>
	  <?php echo tep_draw_textarea_field('t_companion_content', 'soft', '', '','',' class="input_search2"  style="height: 80px; " id="t_companion_content" title="'.db_to_html('请输入活动内容').'"'); ?>
    </label><em>*</em><p style="float:left; width:80%; padding-top:4px; padding-left:0px;">
    <?php echo db_to_html('同意显示邮箱地址')?>
	<?php
	//$t_show_email ='0';
	echo '&nbsp;'.tep_draw_radio_field('t_show_email', '1','' ,'style="width:6%"').db_to_html(' 是 ');
	echo tep_draw_radio_field('t_show_email', '0','','style="width:6%"').db_to_html(' 否 ');
	?>
	</p>
    <p style="float:left; width:auto; padding-top:0px; padding-left:0px;"><?php echo tep_template_image_submit('activte-button.gif', db_to_html('发帖'), ' style="width:auto;"'); ?></p></td>
    </tr>
  <tr>
    <td height="21" align="right"><b><?php echo db_to_html('联系电话')?></b></td>
    <td>&nbsp;</td>
    <td>
	<?php echo tep_draw_input_field('customers_phone','','  class="input_search2" ') ?>	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
	
  <?php if(!(int)$customer_id){//no login?>
  <tr>
    <td height="21" align="right"><b><?php echo db_to_html('用户名/邮箱')?></b></td>
	<td>&nbsp;</td>
    <td align="left" valign="middle">
	<?php echo tep_draw_input_field('email_address','','class="input_search2" style="width: 110px;" title="'.db_to_html('请输入您的电子邮箱').'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?>
	<?php echo db_to_html('新用户 <a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');?>	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  <tr>
    <td height="21" align="right" valign="top" class="title_line"><b><?php echo db_to_html('密码')?></b></td>
    <td>&nbsp;</td>
	<td align="left" valign="top"><input name="password" type="password" class="input_search2" id="password" title="<?php echo db_to_html('请输入正确的密码')?>" /></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  <?php
  }else{//loging
	  if(!tep_not_null($email_address)){
		$email_address = $customer_email_address;
	  }
  ?>
  <tr>
    <td height="21" align="right" valign="top"><b><?php echo db_to_html('邮箱')?></b></td>
	<td>&nbsp;</td>
    <td align="left" valign="top"><?php echo tep_draw_input_field('email_address','',' readonly="true" class="input_search2" title="'.db_to_html('请输入您的电子邮箱').'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  
  <?php }?>

  <tr>
    <td height="21" align="right"><b><?php echo db_to_html('出行日期')?></b></td>
    <td>&nbsp;</td>
    <td style="padding-left:2px;">
	
	<script type="text/javascript">
	 var Dep_Date1 = new ctlSpiffyCalendarBox("Dep_Date1", "CompanionFormFast", "hope_departure_date","btnDate3CFFast","<?php echo ($hope_departure_date); ?>",scBTNMODE_CUSTOMBLUE);
	 </script>
	 <script language="javascript">Dep_Date1.writeControl(); Dep_Date1.dateFormat="yyyy-MM-dd";</script>	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  
  <tr>
    <td height="21" align="right"><?php echo db_to_html('(50积分/1天)')?><b><?php echo db_to_html('置顶')?></b></td>
    <td>&nbsp;</td>
    <td><?php
	//$t_show_email ='0';
	//echo tep_draw_radio_field('t_top_day', '0','','style="width:6%" ').db_to_html(' 不置顶');
	echo tep_draw_radio_field('t_top_day', '1','','style="width:6%" ').db_to_html(' 1天 ');
	echo tep_draw_radio_field('t_top_day', '2','','style="width:6%" ').db_to_html(' 2天 ');
	echo tep_draw_radio_field('t_top_day', '3','','style="width:6%" ').db_to_html(' 3天 ');
	?>
	<a><?php echo BBS_REGULATIONS_STRING?></a> <a href="<?php tep_href_link('points_terms.php');?>" target="_blank"><?php echo db_to_html('积分规则')?></a>
	
	</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td height="21" colspan="7" align="right">&nbsp;</td>
    </tr>
</table>
</form>
</div>
<?php
	}
}
//如果是在某人的帖子列表则不显示以下内容 end

?>



<script type="text/javascript">
document.domain = "usitrip.com";	//统一域名，解决跨域问题

<?php
//本行以下的所有代码都是为javascript服务
?>
//如果语言改变，同时需要刷新左边的框架start
var parent_cul = self.parent.frames["NavigetionFrame"];
//alert(document.characterSet);
if((typeof document.charset != "undefined" ||typeof document.characterSet != "undefined" ) && parent_cul!=null ){
	if(parent_cul.document.charset != document.charset && typeof document.charset != "undefined" ){
		parent_cul.location.href = "<?php echo tep_href_link('bbs_travel_companion_leftside.php', 'TcPath='.$_GET['TcPath']);?>";
	}else if(parent_cul.document.characterSet != document.characterSet && typeof document.characterSet != "undefined" ){
		parent_cul.location.href = "<?php echo tep_href_link('bbs_travel_companion_leftside.php', 'TcPath='.$_GET['TcPath']);?>";
	}
	
}
//如果语言改变，同时需要刷新左边的框架end

<?php
if(tep_not_null($TcPath)){
?>

function ShowChildUl_P(a_id,ul_id, open_action, open_all) { //v1.0
	var parentf = self.parent.frames["NavigetionFrame"];
	var a_id = parentf.document.getElementById(a_id);
	var ul_id = parentf.document.getElementById(ul_id);
	if(ul_id!=null){
		var UL = ul_id.getElementsByTagName("ul");
		
		for(i=0; i<UL.length; i++){
			var ULi_id = UL[i].id;
			ULi_id_array = ULi_id.split("_");
			var ULid = ul_id.id.split("_");
			//if(UL[i].title=='1' || open_all=='true'){
				if(UL[i].style.display!="none"){
					if(open_action!='open'){
						if(UL[i].id.indexOf('Uall_ul') == -1){
							UL[i].style.display="none";
						}
						if(a_id.innerHTML=='+' || a_id.innerHTML=='-'){
							a_id.innerHTML ='+';
						}
					}
				}else if((ULi_id_array.length==ULid.length || ULi_id_array.length==(ULid.length+1) && ULi_id_array.length>1 ) && (UL[i].title=='1' || open_all=='true') ){
					if(UL[i].id.indexOf('Uall_ul') == -1){
						UL[i].style.display="";
					}
					if(a_id.innerHTML=='+' || a_id.innerHTML=='-'){
						a_id.innerHTML ='-';
					}
				}
			//}
			
		}
	}
	
}
function  set_class_ddd(id){
	var parentf = self.parent.frames["NavigetionFrame"];
	if(parentf!=null){
		var obj = parentf.document.getElementById(id);
		var li = parentf.document.getElementsByTagName("li");
		for(i=0; i<li.length; i++){
			li[i].className='';
		}
		if(obj!=null){
			obj.className = 'ddd';
		}
	}
}


	<?php
	if(preg_match('/^24/',$_GET['TcPath']) || preg_match('/^25/',$_GET['TcPath']) || preg_match('/^33/',$_GET['TcPath'])  || preg_match('/^34/',$_GET['TcPath']) || preg_match('/^104/',$_GET['TcPath']) ){	//展开美国
		echo 'ShowChildUl_P("Ausa","ulUsa", "open");'; 
		
		$ids='';
		foreach((array)$TcPath_array as $key => $val){
			$ids .= $val.'_';
			echo 'ShowChildUl_P("a_'.substr($ids,0,strlen($ids)-1).'","ul_'.substr($ids,0,strlen($ids)-1).'", "open");'; 
		}
	}
	if(preg_match('/^54/',$TcPath)){	//展开加拿大
		echo 'ShowChildUl_P("a_54","ul_54", "open");';
		$ids='';
		foreach((array)$TcPath_array as $key => $val){
			$ids .= $val.'_';
			echo 'ShowChildUl_P("a_'.substr($ids,0,strlen($ids)-1).'","ul_'.substr($ids,0,strlen($ids)-1).'", "open");'; 
		}
	}
	if(preg_match('/^157/',$TcPath)){	//展开欧洲
		echo 'ShowChildUl_P("a_157","ul_157", "open");'; 
		$ids='';
		foreach((array)$TcPath_array as $key => $val){
			$ids .= $val.'_';
			echo 'ShowChildUl_P("a_'.substr($ids,0,strlen($ids)-1).'","ul_'.substr($ids,0,strlen($ids)-1).'", "open");'; 
		}
	}
	?>
	
	//自动根据TcPath的参数来设置标题className
	var TcPath = "<?php echo $TcPath?>";
	set_class_ddd('li_'+TcPath);


<?php
}
?>


</script>
