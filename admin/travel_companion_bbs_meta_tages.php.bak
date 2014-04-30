<?php
  require('includes/application_top.php');
  
  switch($_GET['action']){
  	case 'confirm_update': 
		if($_GET['ajax']=='true'){
			//print_r($_POST);
			//取得首页的seo信息
			tep_db_query(" TRUNCATE TABLE `travel_companion_seo_tags` ");
			
			foreach($_POST['title_array'] as $key => $val){
				$meta_title = tep_db_prepare_input($_POST['title_array'][$key]);
				$meta_keywords = tep_db_prepare_input($_POST['keywords_array'][$key]);
				$meta_description = tep_db_prepare_input($_POST['description_array'][$key]);
				$sql_data_array = array(
												'categories_id' => $key,
												'meta_title' => ajax_to_general_string($meta_title),
												'meta_keywords' => ajax_to_general_string($meta_keywords),
												'meta_description' => ajax_to_general_string($meta_description)
												);
				tep_db_perform('`travel_companion_seo_tags`', $sql_data_array);
			}
			
			echo iconv('gb2312','utf-8','[SUCCESS]数据更新成功！[/SUCCESS]');
		}
		exit;
	break;
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link href="<? echo HTTP_SERVER.DIR_WS_TEMPLATES . 'Original/page_css/bbs_travel_companion.css'?>" rel="stylesheet" type="text/css" />

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>

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

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

function SubmitForm(){
	var form = document.getElementById('form_index');
	var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('travel_companion_bbs_meta_tages.php','action=confirm_update&ajax=true')) ?>";
	var aparams=new Array();  //创建一个阵列存表单所有元素和值
	for(i=0; i<form.length; i++){
		if(form.elements[i].type=="radio" || form.elements[i].type=="checkbox" ){	//处理单选、复选按钮值
			var a = '';
			if(form.elements[i].checked){
				var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
				sparam+="=";     //名与值之间用"="号连接
				a = form.elements[i].value;
				sparam+=encodeURIComponent(a);   //获得表单元素值
				aparams.push(sparam);   //push是把新元素添加到阵列中去
			}
		}else{
			var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
			sparam+="=";     //名与值之间用"="号连接
			sparam+=encodeURIComponent(form.elements[i].value);   //获得表单元素值1
			aparams.push(sparam);   //push是把新元素添加到阵列中去
		}
	}
	var post_str = aparams.join("&");		//使用&将各个元素连接
	post_str += "&ajax=true";

	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			
			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(ajax.responseText.replace(error_regxp,''));
			}

			var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[SUCCESS\].+\[\/SUCCESS\])/g)!=-1){		
				alert(ajax.responseText.replace(success_regxp,''));
			}
		}
		
	}

	
}
//-->
</script>	  


<style type="text/css">
<!--
ul {list-style-type: none;}
li {list-style-type: none;}
-->
</style>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo db_to_html('结伴同游BBS Meta Tages管理'); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
				  
                  <tr>
                    
                    <td colspan="7" align="left" class="main">
						<?php echo tep_draw_hidden_field('TcPath')?>
						
<div style="overflow: auto;">
<!--所属景点目录start-->
<?php

function get_categories_tree_meta($categories_ids=false, $level=0, $include_self=true){
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
			
			$tags_sql = tep_db_query('SELECT * FROM `travel_companion_seo_tags` WHERE categories_id="'.(int)$rows['categories_id'].'" Limit 1');
			$tags_row = tep_db_fetch_array($tags_sql);
			
			$fields = '';
			//title strart
			$fields .= '&nbsp;&nbsp; title:'.tep_draw_input_field('title_array['.$rows['categories_id'].']',$tags_row['meta_title'], ' style="color: #000000; border: 1px solid #999999;" size="32" title="'.db_to_html('Meta Title').'" ');
			//title end
			//keywords strart
			$fields .= '&nbsp;&nbsp; keywords:'.tep_draw_input_field('keywords_array['.$rows['categories_id'].']',$tags_row['meta_keywords'], ' style="color: #000000; border: 1px solid #999999;" size="32" title="'.db_to_html('Meta keywords').'" ');
			//keywords end
			//Description strart
			$fields .= '&nbsp;&nbsp; description:'.tep_draw_input_field('description_array['.$rows['categories_id'].']',$tags_row['meta_description'], ' style="color: #000000; border: 1px solid #999999;" size="32" title="'.db_to_html('Meta description').'" ');
			//Description end
			$fields .= '&nbsp;<input name="SortSubmit['.$rows['categories_id'].']" type="submit" value="OK">';
			
			$string .= '<li id="li_'.$TcPath.'" >'.str_repeat($spacer_string, $spacer_multiplier * $level).'<a name="'.$a_id.'" id="'.$a_id.'" onClick="ShowChildUl(&quot;'.$a_id.'&quot;,&quot;'.$ul_id.'&quot;)" href="JavaScript:void(0);" >'.$default_symbol.' '.preg_replace('/ .+/','',$rows['categories_name']).'</a> '. $fields.$child_end_string;
		}
		
		$categories_sql1 = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status =1   AND c.parent_id='".$rows['categories_id']."' order by c.sort_order, c.tc_bbs_total desc, c.parent_id, cd.categories_name");
		while($rows1=tep_db_fetch_array($categories_sql1)){
			$string .= get_categories_tree_meta($rows1['categories_id'],($level+1),true);
		}
		$string .= "</ul>\n";
	}
	return $string;
}
?>
	  <?php echo tep_draw_form('form_index', 'travel_companion_bbs_meta_tages.php', tep_get_all_get_params(array('page','y','x', 'action')), 'post', ' onSubmit="SubmitForm(); return false;" id="form_index" '); ?>
	  
	  <ul id="ulUsa">
      <li id="liUsa" style="padding:5px 0px 5px 0px"><a id="Ausa" href="JavaScript:void(0);"  class="text2 dazi cu" onClick="ShowChildUl(&quot;Ausa&quot;,&quot;ulUsa&quot;)">+ <?php echo db_to_html('美国结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree_meta('24,25,33,34'));?>
      </ul>
	  <ul id="ul_54">
      <li id="li_54" style="padding:5px 0px 5px 0px"><a id="a_54" href="JavaScript:void(0);"  class="text2 dazi cu" onClick="ShowChildUl(&quot;a_54&quot;,&quot;ul_54&quot;)">+ <?php echo db_to_html('加拿大结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree_meta('54','0',false));?>
      </ul>
	  
	  <ul id="ul_157">
	  <li id="li_157" style="padding:5px 0px 5px 0px"><a id="a_157" href="JavaScript:void(0);"  class="text2 dazi cu" onClick="ShowChildUl(&quot;a_157&quot;,&quot;ul_157&quot;)">+ <?php echo db_to_html('欧洲结伴')?></a></li>
	  <?php echo db_to_html(get_categories_tree_meta('157','0',false));?>
	  </ul>
  	  <?php echo '</form>';?>

<!--所属景点目录end-->
</div>
						
					</td>
                    </tr>
                  
                </table>
				
				</td>
			  </tr>
			</table>

		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
