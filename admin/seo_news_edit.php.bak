<?php
require('includes/application_top.php');

$error = false;
$error_msn = '';
$action = tep_not_null($_POST['action']) ? $_POST['action'] : $_GET['action'];
$news_id =  tep_not_null($_POST['news_id']) ? $_POST['news_id'] : $_GET['news_id'];

switch($action){
	case 'UpdateConfirmed':
		$news_title = tep_db_prepare_input($HTTP_POST_VARS['news_title']);
		$news_add_date = tep_db_prepare_input($HTTP_POST_VARS['news_add_date']);
		$news_description = tep_db_prepare_input($HTTP_POST_VARS['news_description']);
		$news_state = tep_db_prepare_input($HTTP_POST_VARS['news_state']);
		$meta_title = tep_db_prepare_input($HTTP_POST_VARS['meta_title']);
		$meta_keywords = tep_db_prepare_input($HTTP_POST_VARS['meta_keywords']);
		$meta_description = tep_db_prepare_input($HTTP_POST_VARS['meta_description']);
		$news_links_ids = tep_db_prepare_input($HTTP_POST_VARS['news_links_ids']);

		if(isset($_POST['AutoOptimization'])){
			//自动优化news_description
			//echo $news_description.'<br>';
	
			$news_description = preg_replace('/script\>/i',"script>\n",$news_description);	//删除js代码
			$news_description = preg_replace('/\<script.+script\>/i',"",$news_description);	
			$news_description = preg_replace('/&lt;_script.+&gt;/i',"",$news_description);
			//删除翻页按钮
			$news_description = preg_replace('/\<div *align="center" *class="f14b"\>.+\<\/div\>/Ui',"",$news_description);
			//删除上下一篇的html代码
			$news_description = eregi_replace("(\r|\n)", " ", $news_description);
			$news_description = preg_replace('/\<div *align="left"\>\<font *class="f14"\>.+\<\/div\>/Ui',"",$news_description);
			
			//删除空白内容
			$news_description = str_replace('>　　 <','><',$news_description);
			$news_description = str_replace('<p><font class="f14"></font></p>','',$news_description);
			$news_description = str_replace('<p>&nbsp;</p>','',$news_description);
			$news_description = str_replace("　","",$news_description);
			
			//echo $news_description;
			//exit;
		}

		if((int)$news_id && (int)$_POST['news_id'] && !isset($_POST['AutoOptimization'])){
			
			$thesaurus_ids = @implode(',',$_POST['thesaurus_ids_array']);
			
			$sql_date_array = array('news_title'=>$news_title,
									'news_add_date'=>$news_add_date,
									'news_state'=>$news_state,
									'news_links_ids'=>$news_links_ids,
									'thesaurus_ids'=>$thesaurus_ids
									);
			tep_db_perform('seo_news', $sql_date_array, 'update', 'news_id="'.(int)$news_id.'" ');
			
			$sql_date_array1 = array(
									'news_description'=>$news_description,
									'meta_title'=>$meta_title,
									'meta_keywords'=>$meta_keywords,
									'meta_description'=>$meta_description
									);
			tep_db_perform('seo_news_description', $sql_date_array1, 'update', 'news_id="'.(int)$news_id.'" ');
			
			//写分类信息
			$class_ids = $_POST['class_box'];
			tep_db_query('DELETE FROM `seo_news_to_class` WHERE `news_id`="'.(int)$news_id.'" ');
			foreach((array)$class_ids as $key => $val){
				tep_db_query('INSERT INTO `seo_news_to_class` ( `news_id` , `class_id` )VALUES ("'.(int)$news_id.'", "'.(int)$val.'"); ');
			}
			if(!(int)count($class_ids)){
				tep_db_query('INSERT INTO `seo_news_to_class` ( `news_id` , `class_id` )VALUES ("1", "'.(int)$val.'"); ');
			}
			//优化表
			tep_db_query('OPTIMIZE TABLE `seo_news` , `seo_news_description` , `seo_news_to_class` ');
			
			echo '<script>alert("数据更新成功！")</script>';
			echo '<script>location="'.tep_href_link('seo_news.php').'"</script>';

		}
	break;
	
}

if(!(int)$news_id){
	die("您没有选择编辑的内容。");
}


if($error==false){
	$news_sql = tep_db_query('SELECT * FROM `seo_news` i, `seo_news_description` id WHERE i.news_id = id.news_id AND i.news_id="'.(int)$news_id.'" ');
	$news_row = tep_db_fetch_array($news_sql);
	$news = new objectInfo($news_row);	
	$news_title = $news->news_title;
	$news_add_date = $news->news_add_date;
	if(!isset($_POST['AutoOptimization'])){
		$news_description = $news->news_description;
	}
	$news_state = $news->news_state;
	$meta_title = $news->meta_title;
	$meta_keywords = $news->meta_keywords;
	$meta_description = $news->meta_description;
	$news_id = $news->news_id;
	$news_links_ids = $news->news_links_ids;
	$thesaurus_ids = $news->thesaurus_ids;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

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
function submit_check(){
	var class_box = document.getElementById("class_box");
	for(i=0; i<class_box.length; i++){
		class_box.options[i].selected = true;
	}
	
	document.getElementById("entries_form").submit();
}

function add_to_class(){
	var all_class_box = document.getElementById("all_class_box");
	var class_box = document.getElementById("class_box");
	var s = class_box.length;
	var ready_add_value = all_class_box.value;
	var ready_add_text = all_class_box.options[all_class_box.selectedIndex].text;
	var add_action = true;
	for(i=0; i<class_box.length; i++){
		class_box.options[i].selected = true;
		if(ready_add_value == class_box.options[i].value){
			add_action = false; 
		}
	}
	if(add_action==true && ready_add_value>0){
		class_box.options[s] = new Option(ready_add_text, ready_add_value);
		class_box.options[s].selected = true;
	}
}

function move_form_categories(){
	var class_box = document.getElementById("class_box");
	for(i=0; i<class_box.length; i++){
		if( class_box.options[i].selected ){
			class_box.remove(i);
			break;
		}
	}

}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>


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
            <td class="pageHeading">编辑内容</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
	 
	  <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> <?php echo $news_title?> </legend>
		  <?php echo tep_draw_form('form_add', 'seo_news_edit.php', tep_get_all_get_params(array('page','y','x', 'action')), 'post', 'id="form_add" onSubmit="submit_check()" '); ?>
		  
		  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td height="30" align="right" nowrap class="main">标题：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('news_title','','size="100"');?></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">日期：</td>
			    <td align="left" class="main"><?php
				echo tep_draw_input_field('news_add_date','','size="22"');
				?></td>
		      </tr>
			  
			  <tr>
			    <td height="30" align="right" nowrap class="main">&nbsp;</td>
			    <td align="left" class="main"><span class="cal-Today" style="font-size:12px"><strong><span style="font-size:16px">不能使用繁体字</span>，否则乱码。</strong></span></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">内容：		          </td>
			    <td align="left" class="main">
			<?php echo tep_draw_textarea_field('news_description', 'virtual', '110', '20', '', 'style="display:none"')?>
			<iframe id="message___Frame" src="FCKeditor/editor/fckeditor.html?InstanceName=news_description&amp;Toolbar=Default" frameborder="no" height="600" scrolling="no" width="100%"></iframe></td>
		      </tr>
			  <?php
				//文章中的词库关键词
			  $thesaurus_text_list = get_thesaurus_checkbox($thesaurus_ids, $news_description , 'thesaurus_ids_array' );
			  if(tep_not_null($thesaurus_text_list)){
			  ?>
			  <tr>
			    <td height="30" align="right" nowrap class="main">文章中的词库关键词：</td>
			    <td align="left" class="main">
				<?php
				echo db_to_html($thesaurus_text_list);
				?>
				<br>
				<span style="color:#FF0000; font-size:12px">提示：可选择关键词用于文章内链，如果不选则默认为全部 <a href="<?= tep_href_link('thesaurus.php')?>" target="_blank">到词库添加新词</a></span>
				</td>
		      </tr>
			  <?php
			  }
			  ?>
			  
			  <tr>
			    <td height="30" align="right" nowrap class="main">状态：</td>
			    <td align="left" class="main">
				<?php
				if($news_state == '1'){
					$news_state_checked = 'checked';
					$news_state_checked_0 = '';
				}else{
					$news_state_checked = '';
					$news_state_checked_0 = 'checked';
				}
				?>
				<input name="news_state" type="radio" value="1" <?=$news_state_checked?>>
				显示
				<input type="radio" name="news_state" value="0" <?=$news_state_checked_0?>>关闭</td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">相关连的文章IDs：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('news_links_ids','','size="100"');?>&nbsp;用英文的逗号来分隔id号</td>
		      </tr>
			  
			  <tr>
			    <td height="30" align="right" nowrap class="main">meta title：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('meta_title','','size="90"');?></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">meta keywords：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('meta_keywords','','size="100"');?></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">meta description：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('meta_description','','size="150"');?></td>
		      </tr>
			  <tr>
			    <td class="main"><input name="action" type="hidden" id="action" value="UpdateConfirmed">
		        <input name="news_id" type="hidden" id="news_id" value="<?php echo (int)$news_id?>"></td>
			    <td class="main"><input name="AutoOptimization" type="submit" id="AutoOptimization" value="自动优化">
			      &nbsp;
		          <input type="submit" name="Submit" value="确认修改">
			      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		        <input name="Submit2" type="button" onClick="MM_goToURL('parent','<?php echo tep_href_link('seo_news_edit.php','news_id='.(int)$news_id)?>');return document.MM_returnValue" value="重置">
		        &nbsp;&nbsp;<input name="backSubmit" type="button" onClick="MM_goToURL('parent','<?php echo tep_href_link('seo_news.php')?>');return document.MM_returnValue" value="返回上一级页面"> 
		        <a href="./../article_news_content.php?news_id=<?= (int)$news_id?>" target="_blank">查看前台页面</a></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">所属分类：</td>
			    <td align="left" class="main">
<?php 
//类别选择器
  
  $top_class_tree = tep_get_seo_class_tree('0','', '', '', 'true', 'true');
  $all_class_box = '<select name="all_class_box"  id="all_class_box" style="color:#999999" size="10" ondblclick="add_to_class()" >';
  foreach((array)$top_class_tree as $key){
  	if((int)$key['id']){
	    $top_calss_style = '';
	    if(!preg_match('/^&nbsp;/',$key['text'])){
			$top_calss_style = ' style="background-color: #E8E8E8; font-weight: bold; padding-top:3px; padding-bottom:3px;" ';
		}
		$all_class_box.='<option value="'.$key['id'].'" '.$top_calss_style.'>'.$key['text'].'</option>';
	}
  }
  $all_class_box .= '</select>';
  
  //$all_class_box = tep_draw_pull_down_menu('all_class_box', $top_class_tree, '' ,' id="all_class_box" style="color:#999999" size="10" ondblclick="add_to_class()" ' );
  
  
  $class_sql = tep_db_query('SELECT * FROM `seo_news_to_class` itc, `seo_class` c WHERE itc.news_id = "'.(int)$news_id.'" AND itc.class_id = c.class_id Group By c.class_id ');
  $tmp_str ='';
  while($class_rows = tep_db_fetch_array($class_sql)){
	$now_class = tep_get_seo_top_to_now_class($class_rows['class_id']);
	$option_text = '';
	for($i=(int)(count($now_class)-1); $i>=0; $i--){
		$option_text .= $now_class[$i]['text'].'&gt;&gt; ';
	}
	$option_text = preg_replace('/^(.*&gt;&gt;)|(&gt;&gt; )$/Ui','',$option_text);
	$tmp_str .= '<option value="'.$class_rows['class_id'].'" selected="selected" >'.$option_text.'</option>';
  }
  $class_selected_box = '<select name="class_box[]" size="10" multiple="multiple" id="class_box">'.$tmp_str.'</select>';
  $html_str ='
  			<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" class="newsBoxContent">已选类别</td>
                <td valign="top" class="newsBoxContent">&nbsp;</td>
                <td valign="top" class="newsBoxContent">所有类别</td>
              </tr>
              <tr>
                <td valign="top" class="newsBoxContent">'.$class_selected_box.'</td>
                <td valign="top" class="newsBoxContent">
				<input name="Submit" type="button" title="增加到分类" onclick="add_to_class()" value=" &lt;&lt; " /><br /><br />
<input type="button" title="撤销分类" name="Submit" onclick="move_form_categories()" value=" &gt;&gt; " /></td>
                <td valign="top" class="newsBoxContent">'.$all_class_box.'</td>
              </tr>
            </table>
  ';
echo $html_str;
?>				</td>
		      </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->		  </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<script type="text/javascript">
var GetContent = document.getElementById('GetContent');
var h1_ = GetContent.getElementsByTagName("h1");	//文章标题，如果h1个数!=1则弹出自已设置。
var td_ = GetContent.getElementsByTagName("td");	//文章内容，
var form = document.getElementById('form_add');

var div = document.getElementsByTagName("div");
form.elements["news_title"].value = '';
form.elements["news_description"].value = '';

var autoget = true;

if(h1_.length!=1){
	//alert('<h1> 元素不等於1,需要自己手动抓取。');
	autoget = false;
}else{
	form.elements["news_title"].value = h1_[0].innerHTML;
}

for(i=0; i<td_.length; i++){
	if(td_[i].className=='f14'){
		form.elements["news_description"].value = td_[i].innerHTML;
		//收集td_内的图片
		var img = td_[i].getElementsByTagName("img");
		var img_src ='';
		for(B=0; B<img.length; B++){
			if(img[B].src!=''){
				img_src += img[B].src +'<;;>' ;
			}
		}
		form.elements["img_srcs"].value = img_src;
		break;
	}
}


form.elements["news_description"].focus();
if(form.elements["news_description"].value!='' && autoget == true){
	form.submit();
}else{
	//alert('无法取得页面内容。');
	form.submit();
}
</script>


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
