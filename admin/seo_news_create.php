<?php
/*
本统计表以订单为中心，可能有重复的客户产生
*/

require('includes/application_top.php');

$error_msn = '';
$action = tep_not_null($_POST['action']) ? $_POST['action'] : $_GET['action'];
$auto_get_submit =  tep_not_null($_POST['auto_get_submit']) ? $_POST['auto_get_submit'] : $_GET['auto_get_submit'];
$url_id =  tep_not_null($_POST['url_id']) ? $_POST['url_id'] : $_GET['url_id'];

if(tep_not_null($_POST['url_key'])){
	$_SESSION['url_key'] = $_POST['url_key'];
}

switch($action){
	case 'create':
		if(tep_not_null($_POST['news_title']) && tep_not_null($_POST['news_description']) ){
			
			$news_title = tep_db_prepare_input($HTTP_POST_VARS['news_title']);
			$news_add_date = tep_db_prepare_input($HTTP_POST_VARS['news_add_date']);
			$news_state = tep_db_prepare_input($HTTP_POST_VARS['news_state']);
			$news_links_ids = tep_db_prepare_input($HTTP_POST_VARS['news_links_ids']);
			$news_description = tep_db_prepare_input($HTTP_POST_VARS['news_description']);
			
			$date_array = array('news_title' => $news_title,
								'news_add_date' => $news_add_date,
								'news_state' => $news_state,
								'news_links_ids' => $news_links_ids
								);
			tep_db_perform('seo_news', $date_array);
			$news_id = tep_db_insert_id();
			
			$date_array = array('news_id' => $news_id,
								'news_description' => $news_description ,
								'meta_title' => tep_db_prepare_input($HTTP_POST_VARS['meta_title']),
								'meta_keywords' => tep_db_prepare_input($HTTP_POST_VARS['meta_keywords']),
								'meta_description' => tep_db_prepare_input($HTTP_POST_VARS['meta_description'])
								);
			tep_db_perform('seo_news_description', $date_array);
			
			//添加类别
			$class_id = (int)$HTTP_POST_VARS['class_id'];
			
			if(!(int)$class_id){
				$class_id = '1';
			}
			tep_db_query('INSERT INTO `seo_news_to_class` ( `news_id` , `class_id` )VALUES ("'.$news_id.'", "'.(int)$class_id.'");');
			
			$_SESSION['msn_str']='<b style="font-size: 16px;font-weight: bold;background-color: #DFFFDF;">数据添加成功！</b>';
		}
		
		if($_POST['added_actoin']=='1'){
			tep_redirect(tep_href_link('seo_news_create.php'));
		}else{
			tep_redirect(tep_href_link('seo_news.php'));
		}
	break;
	
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

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

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
            <td class="pageHeading">添加新内容</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
	 
	 <?php if($auto_get_submit=='AutoGet'){?> 
	  <tr><td>
	  <fieldset>
		  <legend align="left"> 未处理的源内容 </legend>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
				<MYdiv id="GetContent">
				<?php
				
				$where_exc = '';
				if(tep_not_null($url_key)){
					$where_exc .= ' AND url_address like "%'.$url_key.'%" ';
				}
				if((int)$url_id ){
					$where_exc .= ' AND url_id > '.(int)$url_id ;
				}
				
				$sql = tep_db_query('SELECT * FROM `seo_url_tmp` WHERE url_cont_get_finish !="1" '.$where_exc.' order by url_id asc limit 1' );
				while($row = tep_db_fetch_array($sql)){
					$url_id = $row['url_id'];
					$url_domain = $row['url_domain'];
					 //以上变量任何时候都不能删除
					 
					//$url = 'http://usa.bytravel.cn/art/mgh/mghsgjgyts/';
					$url = rawurldecode((string)$row['url_address']);
					//echo '<div>'.$url.'</div>';exit;
					$html=@file_get_contents($url);
					
					if($html==false){
						for($i=0; $i<100; $i++){
							$html=file_get_contents($url);
							if($html!=false){
								break;	
							}
							sleep(2);
						}
						if($html==false){
							echo '无法取得:'.$url.'的内容。';
							exit;
						}
					}
					
					$html = eregi_replace("(\r|\n)", " ", $html);
					$html = preg_replace("/[[:space:]]+/", " ", $html);
					
					
					$setchart_set = preg_replace('/(.*\<meta[^\>]+content=["].+charset=)([\d|\S|\-]{3,6})(["].*\>.*)/i','$2',$html);
					//echo '<div>'.$setchart_set.'</div>';
					//exit;
					if(strlen($setchart_set)>=3 && strlen($setchart_set)<=6 ){
						$setchart = $setchart_set;
					}else{
						$setchart = $row['charset'];
					}
					
					$meta_title = iconv($setchart,'gb2312'.'//IGNORE', preg_replace("/^.*\<title\>(.*)\<\/title\>.*$/Ui", "$1", $html));
					$metas = get_meta_tags($url);
					
					$meta_keywords = iconv($setchart,'gb2312'.'//IGNORE',$metas['keywords']);
					$meta_description = iconv($setchart,'gb2312'.'//IGNORE',$metas['description']);
					
					$html = preg_replace("/.+\<body.*[^\<]\>/Ui", "", $html);
					$html = preg_replace("/\<\/body\>.*/Ui", "", $html);
					
					$html = preg_replace('/\<script/i', '<_script', $html);
					$html = preg_replace('/\<\/script/i', '</_script', $html);

					$html = iconv($setchart,'gb2312'.'//IGNORE',$html);
					echo $html;
				}
				if(!(int)$url_id){
					echo '数据库中的url网址内容已经全部取完！';
					exit;
				}
				?>
				</MYdiv>
				</td>
			  </tr>
			</table>

		  
	  </fieldset>
	  </td></tr>
	  <?php }?>
	  <?php /* 
      <tr>
	  <td>
	  	  <fieldset>
		  <legend align="left"> 子框架 </legend>
		  <iframe id="iframe_1" src="http://usa.bytravel.cn/art/mgh/mghsgjgyts/"></iframe>
	  	  </fieldset>

	  </td>
	  </tr>
	  */?>
	  <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> 添加新内容 </legend>
		  <?php echo tep_draw_form('form_add', 'seo_news_create.php', tep_get_all_get_params(array('page','y','x', 'action')), 'post', 'id="form_add"'); ?>
		  
		  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td height="30" align="right" nowrap class="main">&nbsp;</td>
			    <td colspan="7" align="left" class="main"><?php echo $_SESSION['msn_str']; unset($_SESSION['msn_str']);?>&nbsp;</td>
		      </tr>
			  <tr>
				<td height="30" align="right" nowrap class="main">标题：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('news_title','','size="40"');?></td>
			    <td class="main">&nbsp;</td>
			    <td align="right" nowrap class="main">类别：</td>
			    <td class="main">
				<?php
				$top_class_tree = tep_get_seo_class_tree('0','', '', '', 'true', 'true');
				echo tep_draw_pull_down_menu('class_id', $top_class_tree, $class_id );
				
				?>				</td>
			    <td class="main">&nbsp;</td>
			    <td align="right" nowrap class="main">日期：</td>
			    <td class="main">
				<?php
				$news_add_date = date('Y-m-d H:i:s');
				echo tep_draw_input_field('news_add_date','','size="22"');
				?>				</td>
			  </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">&nbsp;</td>
			    <td align="left" class="main"><span class="cal-Today" style="font-size:12px"><strong><span style="font-size:16px">不能使用繁体字</span>，否则乱码。</strong></span></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">内容：</td>
			    <td colspan="7" align="left" class="main">
				<?php echo tep_draw_textarea_field('news_description', 'virtual', '110', '20', '', 'style="display:none"')?>
			<iframe id="message___Frame" src="FCKeditor/editor/fckeditor.html?InstanceName=news_description&amp;Toolbar=Default" frameborder="no" height="600" scrolling="no" width="100%"></iframe>				</td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">状态：</td>
			    <td align="left" class="main">
				<?php
				$news_state = '1';
				if($news_state == '1'){
					$news_state_checked = 'checked';
					$news_state_checked_0 = '';
				}
				?>
				<input name="news_state" type="radio" value="1" <?=$news_state_checked?>>
				显示
				<input type="radio" name="added_actoin" value="0" <?=$news_state_checked_0?>>
				关闭</td>
			    <td class="main">&nbsp;</td>
			    <td align="right" nowrap class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">相关连的文章IDs：</td>
			    <td colspan="7" align="left" class="main"><?php echo tep_draw_input_field('news_links_ids','','size="100"');?>&nbsp;用英文的逗号来分隔id号</td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">meta title：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('meta_title','','size="40"');?></td>
			    <td class="main">&nbsp;</td>
			    <td align="right" nowrap class="main">meta keywords：</td>
			    <td colspan="4" class="main"><?php echo tep_draw_input_field('meta_keywords','','size="45"');?></td>
		      </tr>
			  <tr>
			    <td height="30" align="right" nowrap class="main">meta description：</td>
			    <td colspan="7" align="left" class="main"><?php echo tep_draw_input_field('meta_description','','size="110"');?>				</td>
		      </tr>
			  <tr>
			    <td class="main"><input name="action" type="hidden" id="action" value="create">
		        <input name="url_id" type="hidden" id="url_id" value="<?php echo (int)$url_id?>">
		        <input name="url_domain" type="hidden" id="url_domain" value="<?php echo $url_domain?>"></td>
			    <td colspan="7" class="main"><input type="submit" name="Submit" value="添加">
			      <label><input type="radio" name="added_actoin" value="0">添加后返回列表</label>
			      <label><input type="radio" name="added_actoin" value="1">断续添加</label>
			      &nbsp;
		        <input type="reset" name="Submit2" value="重置">
		        &nbsp;&nbsp;<input name="backSubmit" type="button" onClick="MM_goToURL('parent','<?php echo tep_href_link('seo_news.php')?>');return document.MM_returnValue" value="返回上一级页面"></td>
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

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
