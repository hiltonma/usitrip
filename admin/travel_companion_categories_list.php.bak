<link href="<? echo HTTP_SERVER.DIR_WS_TEMPLATES . 'Original/page_css/bbs_travel_companion.css'?>" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
ul {list-style-type: none;}
li {list-style-type: none;}
-->
</style>

<script type="text/JavaScript">
<!--
function ShowChildUl(a_id,ul_id, open_action) { //v1.0
  var a_id = document.getElementById(a_id);
  var ul_id = document.getElementById(ul_id);
  if(ul_id!=null){

	  var UL = ul_id.getElementsByTagName("ul");
	  for(i=0; i<UL.length; i++){
		var ULi_id = UL[i].id;
		ULi_id_array = ULi_id.split("_");
		var ULid = ul_id.id.split("_");
		if(UL[i].style.display!="none"){
			if(open_action!='open'){
				UL[i].style.display="none";
				if(a_id.innerHTML=='+' || a_id.innerHTML=='-'){
					a_id.innerHTML ='+';
				}
			}
		}else if(ULi_id_array.length==ULid.length || ULi_id_array.length==(ULid.length+1) && ULi_id_array.length>1 ){
			UL[i].style.display="";
			if(a_id.innerHTML=='+' || a_id.innerHTML=='-'){
				a_id.innerHTML ='-';
			}
		}
	  }
  }
  
}

function  set_class_ddd(id){
	var obj = document.getElementById(id);
	var li = document.getElementsByTagName("li");
	for(i=0; i<li.length; i++){
		li[i].className='';
	}
	if(obj!=null){
		obj.className = 'ddd';
	}
}

//-->
</script>	  

<div style="height: 200px;	overflow: auto; border:solid #CCCCCC medium">
<!--所属景点目录start-->
<?php

function get_categories_tree($categories_ids=false, $level=0, $include_self=true){
	global $languages_id;
	//$child_start_string = '<li onclick="set_class_ddd(this)">';
	$child_end_string = '</li>';
	$spacer_string = '&nbsp;';
	$spacer_multiplier = 2;
	$max_fonts_size = 14;
	
	$default_style = ' style="display:none" ';
	$default_symbol = '+';
	
	$where_cat_ids='';
	if($categories_ids!=false && strlen($categories_ids)){
		$tmp_array = explode(',',$categories_ids);
		$count = count($tmp_array);
		if( $count > 1 ){
			$where_cat_ids =" AND c.categories_id in(".$categories_ids.") ";
		}else{
			$where_cat_ids =" AND c.categories_id ='".$categories_ids."' ";
		}
	}
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id, c.categories_status_for_tc_bbs,c.sort_order,categories_status_for_tc_bbs_display from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status =1   ".$where_cat_ids." order by  c.sort_order, c.tc_bbs_total desc, c.parent_id, cd.categories_name");
	$loop_bin = 0;
	while($rows = tep_db_fetch_array($categories_query)){
		$loop_bin++;
		$TcPath = tep_get_category_patch($rows['categories_id']);
		
		$ul_id = 'ul_'.$TcPath;
		$a_id = 'a_'.$TcPath;
		$string .= '<ul id="'.$ul_id.'" '.$default_style.'>';
		
		$check_sql = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c where c.parent_id='".$rows['categories_id']."' and c.categories_status =1   limit 1 ");
		$check_row = tep_db_fetch_array($check_sql);
		if(!(int)$check_row['categories_id']){
			$default_symbol = '<span style="font-size:7px;">&#8226;</span>';
		}else{
			$default_symbol = '+';
		}

		if($include_self==true){
			
			//目录状态图标 start
			if ($rows['categories_status_for_tc_bbs'] == '1') {
				$c_status = tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('travel_companion.php', 'TcPath='.$TcPath.'&action=setstate_bbs&bbs_status=0&categories_id=' . $rows['categories_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>&nbsp;';
			} else {
				$c_status = '<a href="' . tep_href_link('travel_companion.php', 'TcPath='.$TcPath.'&action=setstate_bbs&bbs_status=1&categories_id=' . $rows['categories_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10).'&nbsp;';
			}
			//目录状态图标 end
			
			//目录状态1图标 start
			//该图标只是控制在前台显示默认状态display:none
			if ($rows['categories_status_for_tc_bbs_display'] == '1') {
				$d_status = tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', db_to_html('设置默认为显示'), 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('travel_companion.php', 'TcPath='.$TcPath.'&action=setstate_bbs_display&bbs_display_status=0&categories_id=' . $rows['categories_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', db_to_html('设置默认为 隐藏'), 10, 10) . '</a>&nbsp;';
			} else {
				$d_status = '<a href="' . tep_href_link('travel_companion.php', 'TcPath='.$TcPath.'&action=setstate_bbs_display&bbs_display_status=1&categories_id=' . $rows['categories_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', db_to_html('设置默认为显示'), 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', db_to_html('设置默认为 隐藏'), 10, 10).'&nbsp;';
			}
			//目录状态1图标 end
			
			//序号 strart
			
			$now_sort = $rows['sort_order'];
			
			$sort_button = '&nbsp;&nbsp;'.tep_draw_input_field('sort_order_array['.$rows['categories_id'].']',$rows['sort_order'], ' style="color: #000000; border: 1px solid #999999;" size="2" title="'.db_to_html('排序序号').'" ').'&nbsp;<input name="SortSubmit['.$rows['categories_id'].']" type="submit" value="OK">';
			//序号 end
			
			$string .= '<li id="li_'.$TcPath.'" >'.$c_status.$sort_button.$d_status.str_repeat($spacer_string, $spacer_multiplier * $level).'<a class="text2" name="'.$a_id.'" id="'.$a_id.'" onClick="ShowChildUl(&quot;'.$a_id.'&quot;,&quot;'.$ul_id.'&quot;)" href="JavaScript:void(0);" >'.$default_symbol.'</a> <a onClick="set_class_ddd(&quot;li_'.$TcPath.'&quot;); ShowChildUl(&quot;'.$a_id.'&quot;,&quot;'.$ul_id.'&quot;)" href="'.tep_href_link('travel_companion.php','TcPath='.$TcPath).'"  class="text2" style="font-size:'.max(($max_fonts_size-$spacer_multiplier*$level),12).'px; ">'.preg_replace('/ .+/','',$rows['categories_name']).'</a> '. $child_end_string;
		}
		
		$categories_sql1 = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status =1   AND c.parent_id='".$rows['categories_id']."' order by c.sort_order, c.tc_bbs_total desc, c.parent_id, cd.categories_name");
		while($rows1=tep_db_fetch_array($categories_sql1)){
			$string .= get_categories_tree($rows1['categories_id'],($level+1),true);
		}
		$string .= "</ul>\n";
	}
	return $string;
}
?>
	  <ul id="ulUsa">
      <li id="liUsa" style="padding:5px 0px 5px 0px"><a id="Ausa" href="JavaScript:void(0);"  class="text2 dazi cu" onClick="ShowChildUl(&quot;Ausa&quot;,&quot;ulUsa&quot;)">+</a> <a href="<?php echo tep_href_link('travel_companion.php')?>"  class="text2 dazi cu" onClick="set_class_ddd(&quot;liUsa&quot;); ShowChildUl(&quot;Ausa&quot;,&quot;ulUsa&quot;)"><?php echo db_to_html('美国结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree('24,25,33,34'));?>
      </ul>
	  <ul id="ul_54">
      <li id="li_54" style="padding:5px 0px 5px 0px"><a id="a_54" href="JavaScript:void(0);"  class="text2 dazi cu" onClick="ShowChildUl(&quot;a_54&quot;,&quot;ul_54&quot;)">+</a> <a href="<?php echo tep_href_link('travel_companion.php','TcPath=54')?>"  class="text2 dazi cu" onClick="set_class_ddd(&quot;li_54&quot;); ShowChildUl(&quot;a_54&quot;,&quot;ul_54&quot;)"><?php echo db_to_html('加拿大结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree('54','0',false));?>
      </ul>
	  
	  <ul id="ul_157">
	  <li id="li_157" style="padding:5px 0px 5px 0px"><a id="a_157" href="JavaScript:void(0);"  class="text2 dazi cu" onClick="ShowChildUl(&quot;a_157&quot;,&quot;ul_157&quot;)">+</a> <a href="<?php echo tep_href_link('travel_companion.php','TcPath=157')?>"  class="text2 dazi cu" onClick="set_class_ddd(&quot;li_157&quot;); ShowChildUl(&quot;a_157&quot;,&quot;ul_157&quot;)"><?php echo db_to_html('欧洲结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree('157','0',false));?>
	  </ul>
<!--所属景点目录end-->
</div>



<?php
if($_GET['TcPath']!=''){
	$TcPath = $_GET['TcPath'];
	if (tep_not_null($TcPath)) {
		$TcPath_array = tep_parse_category_path($TcPath);
		$TcPath = implode('_', $TcPath_array);
		$Tccurrent_category_id = $TcPath_array[(sizeof($TcPath_array)-1)];
	} else {
		$Tccurrent_category_id = 0;
	}
?>
<script type="text/javascript">
	<?php
	if(preg_match('/^24/',$_GET['TcPath']) || preg_match('/^25/',$_GET['TcPath']) || preg_match('/^33/',$_GET['TcPath']) || preg_match('/^34/',$_GET['TcPath']) || preg_match('/^104/',$_GET['TcPath']) ){	//展开美国
		echo 'ShowChildUl("Ausa","ulUsa", "open");';
		$ids='';
		foreach((array)$TcPath_array as $key => $val){
			$ids .= $val.'_';
			echo 'ShowChildUl("a_'.substr($ids,0,strlen($ids)-1).'","ul_'.substr($ids,0,strlen($ids)-1).'", "open");'; 
		}
	}
	if(preg_match('/^54/',$_GET['TcPath'])){	//展开加拿大
		echo 'ShowChildUl("a_54","ul_54", "open");';
		$ids='';
		foreach((array)$TcPath_array as $key => $val){
			$ids .= $val.'_';
			echo 'ShowChildUl("a_'.substr($ids,0,strlen($ids)-1).'","ul_'.substr($ids,0,strlen($ids)-1).'", "open");'; 
		}
	}
	if(preg_match('/^157/',$_GET['TcPath'])){	//展开欧洲
		echo 'ShowChildUl("a_157","ul_157", "open");'; 
		$ids='';
		foreach((array)$TcPath_array as $key => $val){
			$ids .= $val.'_';
			echo 'ShowChildUl("a_'.substr($ids,0,strlen($ids)-1).'","ul_'.substr($ids,0,strlen($ids)-1).'", "open");'; 
		}
	}
	?>
	
	//自动根据TcPath的参数来设置标题className
	var TcPath = "<?php echo $_GET['TcPath']?>";
	if(TcPath!="" && document.getElementById('li_'+TcPath)!=null){
		document.getElementById('li_'+TcPath).className = 'ddd';
	}
	
</script>
<?php
}
?>