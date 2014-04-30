<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>

<?php
if ( file_exists(DIR_FS_INCLUDES . 'header_tags.php') ) {
  require(DIR_FS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE ?></title>
<?php
}
?>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link href="<?php echo TEMPLATE_STYLE;?>" rel="stylesheet" type="text/css" />
<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/page_css/bbs_travel_companion.css'?>" rel="stylesheet" type="text/css" />

<script type="text/JavaScript">
<!--
document.domain = "usitrip.com";	//统一域名，解决跨域问题

function show_for_display_all_buttom(obj){
	if(obj!=''){
		var ul = obj.getElementsByTagName("ul");
	}else{
		var ul = document.getElementsByTagName("ul");
	}
	for(i=0; i<ul.length; i++){
		var parent_id = ul[i].id.replace(/\_(\d+)$/,'');
		Uall = document.getElementById('Uall_'+parent_id);
		if(ul[i].title=='0'){
			ul[i].style.display = 'none';
			//将上一级的Uall_***的innerHTML设置为显示全部
			if(Uall!=null){
				Uall.style.display = '';
			}
		}
	}
}


function ShowChildUl(a_id,ul_id, open_action, open_all) { //v1.0
  var a_id = document.getElementById(a_id);
  var Uall = document.getElementById('Uall_'+ul_id);
  var ul_id = document.getElementById(ul_id);
  
  if(ul_id!=null){

		//取得与本ul级别相同的其它ul，并隐藏之 start
		ul_id_id = ul_id.id;
		var ul_id_id_array = ul_id_id.split("_");
		
		if(ul_id_id=='ulUsa' || ul_id_id=='ul_54' || ul_id_id=='ul_157' ){	//顶级的处理
			
			var ulUsa = document.getElementById('ulUsa');
			var Ausa = document.getElementById('Ausa');
			if(ulUsa!=null && ul_id_id!='ulUsa'){	//隐藏加拿大子级项目
				var ulc = ulUsa.getElementsByTagName("ul");
				for(cnum=0; cnum<ulc.length; cnum++){
					ulc[cnum].style.display='none';
				}
				if(Ausa!=null && Ausa.innerHTML=='-'){	Ausa.innerHTML='+';}
			}
			
			var ul_54 = document.getElementById('ul_54');
			var a_54 = document.getElementById('a_54');
			if(ul_54!=null && ul_id_id!='ul_54'){	//隐藏加拿大子级项目
				var ulc = ul_54.getElementsByTagName("ul");
				for(cnum=0; cnum<ulc.length; cnum++){
					ulc[cnum].style.display='none';
				}
				if(a_54!=null && a_54.innerHTML=='-'){	a_54.innerHTML='+';}
			}
			
			var ul_157 = document.getElementById('ul_157');
			var a_157 = document.getElementById('a_157');
			if(ul_157!=null && ul_id_id!='ul_157'){	//隐藏欧洲子级项目
				var ulc = ul_157.getElementsByTagName("ul");
				for(cnum=0; cnum<ulc.length; cnum++){
					ulc[cnum].style.display='none';
				}
				if(a_157!=null && a_157.innerHTML=='-'){	a_157.innerHTML='+';}
			}
			
		}else if(ul_id_id_array.length>=2){	//二级以下的级别处理:西海岸、东海岸、夏威夷、佛罗里达的处理
			var us_ul_child_array = new Array();
			us_ul_child_array[0] = 'ul_24';
			us_ul_child_array[1] = 'ul_25';
			us_ul_child_array[2] = 'ul_33';
			us_ul_child_array[3] = 'ul_34';
			
			
			for(us_num=0; us_num<us_ul_child_array.length; us_num++){
				if(us_ul_child_array[us_num] == ul_id_id ){
					for(xc=0; xc<us_ul_child_array.length; xc++){
						if(us_ul_child_array[xc]!= ul_id_id ){
							var loop_id = document.getElementById(us_ul_child_array[xc]);
							var ulc = loop_id.getElementsByTagName("ul");
							for(cnum=0; cnum<ulc.length; cnum++){
								ulc[cnum].style.display='none';
							}
							var a_ul_id = document.getElementById(us_ul_child_array[xc].replace(/^ul/,'a'));
							//alert(a_ul_id.innerHTML);
							if(a_ul_id!=null && a_ul_id.innerHTML =='-'){
								a_ul_id.innerHTML ='+';
							}
						}
					}
					
				}
			}
		}
		//取得与本ul级别相同的其它ul，并隐藏之 end

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
						if(a_id.innerHTML=='+' || a_id.innerHTML=='-'){
							a_id.innerHTML ='+';
						}
					}
				}
			}else if((ULi_id_array.length==ULid.length || ULi_id_array.length==(ULid.length+1) && ULi_id_array.length>1 ) && (UL[i].title=='1' || open_all=='true') ){
				if(UL[i].id.indexOf('Uall_ul') == -1){
					UL[i].style.display="";
					if(a_id.innerHTML=='+' || a_id.innerHTML=='-'){
						a_id.innerHTML ='-';
					}
				}

			}
		
		//}
	  }
	
	
	if(open_all!="true" && ul_id!=null && open_action!='open'){
		show_for_display_all_buttom(ul_id);
	}
	
  }
  
}

function All_ShowChildUl(obj_id,a_id,ul_id){
	var obj = document.getElementById(obj_id);
	if(obj!=null){
		ShowChildUl(a_id,ul_id, 'open', 'true');
		obj.style.display ='none';
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

</head>

<body id="tc_l">
<div class="top_bar">
      <?php
	  //登录前
	  if (!tep_session_is_registered('customer_id')) {
	  ?>
	  <p class="dazi"><a class="text2" target="_top" href="<?php echo tep_href_link("login.php","", "SSL") ?>"><?php echo LOGIN_STRING?></a> | <a class="text2" target="_top" href="<?php echo tep_href_link("create_account.php","", "SSL") ?>"><?php echo REG_STRING?></a>&nbsp;&nbsp;&nbsp;</p>
	  <?php
	  //登录后
	  }else{
	  ?>
      <p><?php echo sprintf(WELCOME_FOR_YOU,db_to_html($first_or_last_name))?></p>
      <p class="dazi"><a class="text2" target="_top" href="<?php echo tep_href_link(FILENAME_ACCOUNT,"", "SSL") ?>"><?php echo MY_ACCOUNT?></a></p>
      <p class="dazi"><a class="sp3" target="_blank" href="<?php echo tep_href_link('i_sent_bbs.php',"", "SSL") ?>"><?php echo I_SENT_BBS?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="sp3" target="_blank" href="<?php echo tep_href_link('i_reply_bbs.php',"", "SSL") ?>"><?php echo I_REPLAY_BBS?></a>  </p>
	  <?php
	  }
	  ?>
      <p class="dazi"><a target="_blank" class="text2" href="<?php echo tep_href_link('companions_process.php')?>"><?php echo TRAVEL_COMPANION_HELP_STRING?></a>&nbsp;&nbsp;<a class="text2"><?php echo BBS_REGULATIONS_STRING?></a></p>

	  
</div>

<div class="jingdian-list">

<?php


//美国的目录24/25/33/34
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
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id, c.categories_status_for_tc_bbs_display as bbs_status from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status =1  and c.categories_status_for_tc_bbs =1 ".$where_cat_ids." ORDER BY  bbs_status DESC, c.sort_order, c.tc_bbs_total desc, c.parent_id, cd.categories_name");
	
	while($rows = tep_db_fetch_array($categories_query)){
		
		$TcPath = tep_get_category_patch($rows['categories_id']);
		
		$ul_id = 'ul_'.$TcPath;
		$a_id = 'a_'.$TcPath;
		$string .= '<ul id="'.$ul_id.'" '.$default_style.' title="'.(int)$rows['bbs_status'].'" >';
		
		$check_sql = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c where c.parent_id='".$rows['categories_id']."' and c.categories_status =1  and c.categories_status_for_tc_bbs =1 limit 1 ");
		$check_row = tep_db_fetch_array($check_sql);
		if(!(int)$check_row['categories_id']){
			$default_symbol = '<span style="font-size:7px;">&#8226;</span>';
		}else{
			$default_symbol = '+';
		}

		if($include_self==true){
			
			$string .= '<li id="li_'.$TcPath.'" >'.str_repeat($spacer_string, $spacer_multiplier * $level).'<a class="text2" name="'.$a_id.'" id="'.$a_id.'" onClick="ShowChildUl(&quot;'.$a_id.'&quot;,&quot;'.$ul_id.'&quot;)" href="JavaScript:void(0);" target="_self">'.$default_symbol.'</a> <a onClick="set_class_ddd(&quot;li_'.$TcPath.'&quot;); ShowChildUl(&quot;'.$a_id.'&quot;,&quot;'.$ul_id.'&quot;)" href="'.tep_href_link('bbs_travel_companion_rightindex.php','TcPath='.$TcPath).'" target="ContentFrame" class="text2" style="font-size:'.max(($max_fonts_size-$spacer_multiplier*$level),12).'px; ">'.preg_replace('/ .+/','',$rows['categories_name']).'</a>'.$child_end_string;
		}
		
		$categories_sql1 = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id, c.categories_status_for_tc_bbs_display as bbs_status  from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status =1  and c.categories_status_for_tc_bbs =1 AND c.parent_id='".$rows['categories_id']."' ORDER BY bbs_status DESC, c.sort_order, c.tc_bbs_total desc, c.parent_id, cd.categories_name");
		$loop_b = 0;
		while($rows1=tep_db_fetch_array($categories_sql1)){
			$loop_b++;
			$string .= get_categories_tree($rows1['categories_id'],($level+1),true);
		}
		
		if((int)$loop_b){	//在每个级别列表最后添加“显示全部”按钮
			$string .= '<ul id="Uall_ul_'.$TcPath.'" title="1" style="padding-left:20px; display: none;"><li><a href="javascript:void(0)" onClick="All_ShowChildUl(&quot;Uall_ul_'.$TcPath.'&quot;,&quot;'.$a_id.'&quot;,&quot;'.$ul_id.'&quot;)" id="Aall_ul_'.$TcPath.'">显示全部</a></li></ul>';
		}
		
		$string .= "</ul>\n";
	}
	return $string;
}

?>
	  <div>
	  <?php
	  $homepage_class='';
	  if(!tep_not_null($TcPath)){
	  	$homepage_class = 'class="ddd"';
	  }
	  ?>
	  <ul id="homepage">
 	  <li id="lihomepage" <?=$homepage_class?> style="padding:5px 0px 5px 0px" ><a onClick="set_class_ddd(&quot;lihomepage&quot;);" href="<?php echo tep_href_link('bbs_travel_companion_rightindex.php')?>" target="ContentFrame" class="text2 dazi cu" ><?php echo db_to_html('结伴同游首页')?></a></li>
      </ul>
	  
	  <ul id="ulUsa">
      <li id="liUsa" style="padding:5px 0px 5px 0px"><a id="Ausa" href="JavaScript:void(0);" target="_self" class="text2 dazi cu" onClick="ShowChildUl(&quot;Ausa&quot;,&quot;ulUsa&quot;)">+</a> <a href="<?php echo tep_href_link('bbs_travel_companion_rightindex.php')?>" target="ContentFrame" class="text2 dazi cu" onClick="set_class_ddd(&quot;liUsa&quot;); ShowChildUl(&quot;Ausa&quot;,&quot;ulUsa&quot;)"><?php echo db_to_html('美国结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree('24,25,33,34'));?>
      </ul>
	  <ul id="ul_54">
      <li id="li_54" style="padding:5px 0px 5px 0px"><a id="a_54" href="JavaScript:void(0);" target="_self" class="text2 dazi cu" onClick="ShowChildUl(&quot;a_54&quot;,&quot;ul_54&quot;)">+</a> <a href="<?php echo tep_href_link('bbs_travel_companion_rightindex.php','TcPath=54')?>" target="ContentFrame" class="text2 dazi cu" onClick="set_class_ddd(&quot;li_54&quot;); ShowChildUl(&quot;a_54&quot;,&quot;ul_54&quot;)"><?php echo db_to_html('加拿大结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree('54','0',false));?>
      </ul>
	  
	  <ul id="ul_157">
	  <li id="li_157" style="padding:5px 0px 5px 0px"><a id="a_157" href="JavaScript:void(0);" target="_self" class="text2 dazi cu" onClick="ShowChildUl(&quot;a_157&quot;,&quot;ul_157&quot;)">+</a> <a href="<?php echo tep_href_link('bbs_travel_companion_rightindex.php','TcPath=157')?>" target="ContentFrame" class="text2 dazi cu" onClick="set_class_ddd(&quot;li_157&quot;); ShowChildUl(&quot;a_157&quot;,&quot;ul_157&quot;)"><?php echo db_to_html('欧洲结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree('157','0',false));?>
	  </ul>
     
	  </div>
</div>



</body>
</html>

<?php
if($_GET['TcPath']!=''){
?>
<script type="text/javascript">
	<?php
	if(preg_match('/^24/',$_GET['TcPath']) || preg_match('/^25/',$_GET['TcPath']) || preg_match('/^33/',$_GET['TcPath'])  || preg_match('/^34/',$_GET['TcPath']) || preg_match('/^104/',$_GET['TcPath']) ){	//展开美国
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
}else{
?>

<script type="text/javascript">
	<!--默认展开美国西海岸的目录-->
	ShowChildUl("Ausa","ulUsa", "open"); 
	ShowChildUl("a_24","ul_24"); 
</script>

<?php
}
?>