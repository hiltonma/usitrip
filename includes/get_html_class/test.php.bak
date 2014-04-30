<?php
//echo file_get_contents('http://news.sina.com.cn/c/p/2011-09-09/221623134565.shtm');
//exit;
$charset = 'gb2312';
if($_POST['charset']){
	$charset = $_POST['charset'];
}
$t=microtime(true);
ini_set('display_errors', '1'); 
error_reporting(E_ALL & ~E_NOTICE);
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header("Content-type: text/html; charset=".$charset);

include('get_html_class.php');

//抓取网页内容类
if($_POST){
	$error = false;
	$errorMsn = "";
	$filesName = trim($_POST['filesName']);
	$fielsCharset = trim($_POST['fielsCharset']);
	$domGetType = $_POST['domGetType'];
	
	$heardSeparatedTag = $_POST['heardSeparatedTag'];
	$footSeparatedTag = $_POST['footSeparatedTag'];
	
	$tagName = trim($_POST['tagName']);
	$attrName = trim($_POST['attrName']);
	$attrValue = trim($_POST['attrValue']);
	$attrValue = trim($_POST['attrValue']);
	$tagRange = trim($_POST['tagRange']);
	
	$titleTag = trim($_POST['titleTag']);
	$titleAttrName = trim($_POST['titleAttrName']);
	$titleAttrValue = trim($_POST['titleAttrValue']);
	$titleTagRange = trim($_POST['titleTagRange']);
	
	
	$filterTagNames = $_POST['filterTagNames'];
	$filterAttrNames = $_POST['filterAttrNames'];
	$filterAttrValues = $_POST['filterAttrValues'];
	
	if($filesName==""){
		$error = true;
		$errorMsn.= "网页地址：必填！<br />";
	}
	if($fielsCharset==""){
		$error = true;
		$errorMsn.= "网页编码：必填！<br />";
	}
	
	if($tagName==""){
		$error = true;
		$errorMsn.= "内容正文标签：必填！<br />";
	}
	
	$dateTagName=trim($_POST['dateTagName']);
	$dateAttrName=trim($_POST['dateAttrName']);
	$dateAttrValue=trim($_POST['dateAttrValue']);
	$dateTagRange=trim($_POST['dateTagRange']);
	
	$pageTag=trim($_POST['pageTag']);
	$pageTagAttrName=trim($_POST['pageTagAttrName']);
	$pageTagAttrValue=trim($_POST['pageTagAttrValue']);
	$pageTagRange=trim($_POST['pageTagRange']);
	if($error==false){
		$dom = new GetHtml;
		$dom -> getHmtlAllContent($filesName, $fielsCharset, $charset, $domGetType, $heardSeparatedTag, $footSeparatedTag);
		//标题
		if($titleTag!=""){
			$title = $dom->getTags($titleTag,$titleAttrName,$titleAttrValue, $filterTagNames, $filterAttrNames, $filterAttrValues, $titleTagRange);
			echo "<b>标题：</b>".$title;
			echo "<hr />";
		}
		//日期
		if($dateTagName!=""){
			$date = $dom->getTags($dateTagName, $dateAttrName, $dateAttrValue, $filterTagNames, $filterAttrNames, $filterAttrValues, $dateTagRange);
			$dateFormat = $_POST['dateFormat'];
			echo "<b>日期：</b>源数据：".$date.'<br />';
			if(strlen($dateFormat)>2){
				
				$dateFormat=str_replace(array('Y','M','D','H','I','S'),'\d',$dateFormat);
				
				$dateText = preg_replace('@.*('.$dateFormat.').*@si','$1',preg_replace('/[[:space:]]+/',' ',strip_tags($date)));
				$dateText1 = str_replace(array('年','月','日'),array('-','-',' '), $dateText);
				
				echo '提取后['.$dateText.']'.'==>标准日期：'.date('Y-m-d H:i:s',strtotime($dateText1)).'<br />';
			}
			
			echo "<hr />";
		}
		//内容
		if($tagName!=""){
			$content = $dom->getTags($tagName, $attrName, $attrValue, $filterTagNames, $filterAttrNames, $filterAttrValues, $tagRange);
			echo $content;
			echo "<hr />";
		}
		//翻页代码
		if($pageTag!=""){
			$pages = $dom->getTags($pageTag, $pageTagAttrName, $pageTagAttrValue, $filterTagNames, $filterAttrNames, $filterAttrValues, $pageTagRange);
			echo $pages;
			echo "<hr />";
		}
		
		$tt=microtime(true);
		echo "<b>用时：</b>".($tt-$t);
		echo "<hr />";
	}else{
		echo "<div class=error>".$errorMsn."</div>";
		echo "<hr />";
	}
}

//输入框字符串处理函数 {
/**
 * 格式化数据为输入数据库作准备
 *
 * @param unknown_type $string
 * @return unknown
 */
  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes2($string));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

function stripslashes2($string) {
  
   $string = str_replace("\\\"", "\"", $string);
  
   $string = str_replace("\\'", "'", $string);
  if(eregi("\\\\",$string)){
   $string = str_replace("\\\\", "\\", $string);
   }
   return $string;
}

function tep_output_string($string, $translate = false, $protected = false) {
	if ($protected == true) {
		return tep_htmlspecialchars($string);
	} else {
		if ($translate == false) {
			return tep_parse_input_field_data($string, array('"' => '&quot;'));
		} else {
			return tep_parse_input_field_data($string, $translate);
		}
	}
}

function tep_parse_input_field_data($data, $parse) {
	return strtr(trim($data), $parse);
}

function tep_htmlspecialchars($str){
	//return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($str));
	return preg_replace("/&amp;/", "&", htmlspecialchars($str,ENT_QUOTES));
}

//输入框字符串处理函数 }

foreach((array)$_POST as $key => $val){
	if(is_string($val)){
		$$key = tep_output_string(tep_db_prepare_input($val));
	}
}
?>
<style type="text/css">
<!--
label {
	display: block;
	height:30px;
	clear:both;
}

label span{
	width:175px;
	float:left;
}
label input, select{
	float:left;
}
label i{
	margin-left:5px;
	color:#666;
	font-size:12px;
	font-style: normal;
	float:left;
}
.error {color:#F00; size:24px;}
-->
</style>


<form action="" method="post" enctype="multipart/form-data" name="form" target="_self">
	<label>
		<span>抓取方式：</span>
		<?php
		$dom_selected = '';
		$curl_selected = 'selected="selected"';
		if($domGetType=="dom"){
			$dom_selected = 'selected="selected"';
			$curl_selected = '';
		}
		?>
		<select name="domGetType">
			<option value="curl" <?= $curl_selected?> >curl</option>
			<option value="dom" <?= $dom_selected?> >dom</option>
		</select>
		<i>如：新浪网建议dom，QQ网建议curl</i>
	</label>
	<label>
		<span>网页地址：</span><input name="filesName" type="text" id="filesName" size="60" value="<?= $filesName?>" /><i>如：http://news.sina.com.cn/c/p/2011-09-09/221623134565.shtml</i>
	</label>
	<label>
		<span>网页源编码：</span><input name="fielsCharset" type="text" id="fielsCharset" size="10" value="<?= $fielsCharset?>" /><i>如：gb2312</i>
	</label>
	<label>
		<span>目标编码：</span>
		<?php
		if($charset!=""){
			$varName = str_replace('-','_',$charset).'_selected';
			$$varName = 'selected="selected"';
		}
		?>
		<select name="charset">
			<option value="gb2312" <?= $gb2312_selected?> >gb2312</option>
			<option value="big5" <?= $big5_selected?> >big5</option>
			<option value="gbk" <?= $gbk_selected?> >gbk</option>
			<option value="utf-8" <?= $utf_8_selected?> >utf-8</option>
		</select>
		<i>目标编码：是指用什么编码输出，如big5、gbk、utf-8或gb2312等</i>
	</label>
	<label>
	<span>内容开始点：</span><input name="heardSeparatedTag" type="text" id="heardSeparatedTag" size="60" value="<?= $heardSeparatedTag;?>" />
	<i>如：body&gt;</i>
	</label>
	<label>
	<span>内容结束点：</span><input name="footSeparatedTag" type="text" id="footSeparatedTag" size="60" value="<?= $footSeparatedTag;?>" />
	<i>如：&lt;/body</i>
	</label>
	<label>
	<strong>标题</strong> </label>
	
	<label>
		<span>标题标签：</span><input name="titleTag" type="text" id="titleTag" size="10" value="<?= $titleTag?>" /><i>如：h2</i>
	</label>
	<label>
		<span>标题标签属性：</span><input name="titleAttrName" type="text" id="titleAttrName" size="20" value="<?= $titleAttrName?>" /><i>如：class</i>
	</label>
	<label>
		<span>标题标签属性值：</span><input name="titleAttrValue" type="text" id="titleAttrValue" size="50" value="<?= $titleAttrValue?>" /><i>如：className</i>
	</label>
	<label><span>范围：</span><input name="titleTagRange" type="text" id="titleTagRange" size="10" value="<?= $titleTagRange?>" /><i>如果出现重复的标签，你可在这里指定抓取的范围，如："1,4-6"，代表抓取第1次和第4、5、6次出现的标签内容；如果此值为0或空则采集所有。</i></label>
	<label>
	<strong>抓取发表日期</strong> </label>
	<label>
		<span>发表日期标签：</span><input name="dateTagName" type="text" id="dateTagName" size="10" value="<?= $dateTagName?>" /><i>如：div</i>
	</label>
	<label>
		<span>发表日期标签属性：</span><input name="dateAttrName" type="text" id="dateAttrName" size="20" value="<?= $dateAttrName?>" /><i>如：id</i>
	</label>
	<label>
		<span>发表日期标签属性值：</span><input name="dateAttrValue" type="text" id="dateAttrValue" size="50" value="<?= $dateAttrValue?>" /><i>如：idVal</i>
	</label>
	<label><span>范围：</span><input name="dateTagRange" type="text" id="dateTagRange" size="10" value="<?= $dateTagRange?>" /><i>如果出现重复的标签，你可在这里指定抓取的范围，如："1,4-6"，代表抓取第1次和第4、5、6次出现的标签内容；如果此值为0或空则采集所有。</i></label>
	<label><span>日期格式：</span><input name="dateFormat" type="text" id="dateFormat" size="10" value="<?= $dateFormat?>" /><i>如果日期标签的内容过多，你可在此定义日期的格式如:YYYY年MM月DD日HH:II:SS</i></label>

	<label>
	<strong>内容正文</strong> </label>

	<label>
		<span>内容正文标签：</span><input name="tagName" type="text" id="tagName" size="10" value="<?= $tagName?>" /><i>如：div</i>
	</label>
	<label>
		<span>内容正文标签属性：</span><input name="attrName" type="text" id="attrName" size="20" value="<?= $attrName?>" /><i>如：id</i>
	</label>
	<label>
		<span>内容正文标签属性值：</span><input name="attrValue" type="text" id="attrValue" size="50" value="<?= $attrValue?>" /><i>如：idVal</i>
	</label>
	<label><span>范围：</span><input name="tagRange" type="text" id="tagRange" size="10" value="<?= $tagRange?>" /><i>如果出现重复的标签，你可在这里指定抓取的范围，如："1,4-6"，代表抓取第1次和第4、5、6次出现的标签内容；如果此值为0或空则采集所有。</i></label>
	<label>
	<strong>是否要过滤抓取结果的一些内容？</strong> </label>
	<?php for($i=0; $i<5; $i++){ $n=$i+1;?>
	<label>
		<span>要过滤的标签<?=$n?>：</span><input name="filterTagNames[<?=$i?>]" type="text" size="10"  value="<?= $filterTagNames[$i]?>" />
	</label>
	<label>
		<span>要过滤的标签属性<?=$n?>：</span><input name="filterAttrNames[<?=$i?>]" type="text" size="20"  value="<?= $filterAttrNames[$i]?>" />
	</label>
	<label>
		<span>要过滤的标签属性值<?=$n?>：</span><input name="filterAttrValues[<?=$i?>]" type="text" size="20"  value="<?= $filterAttrValues[$i]?>" />
	</label>
	<?php
	}
	?>
	
	<label><strong>如有翻页请写出开始标签和结束标签</strong></label>
	<label><span>翻页标签：</span><input name="pageTag" type="text" id="pageTag" size="10" value="<?= $pageTag?>" /></label>
	<label><span>翻页标签属性：</span><input name="pageTagAttrName" type="text" id="pageTagAttrName" size="20" value="<?= $pageTagAttrName?>" /></label>
	<label><span>翻页标签属性值：</span><input name="pageTagAttrValue" type="text" id="pageTagAttrValue" size="20" value="<?= $pageTagAttrValue?>" /></label>
	<label><span>范围：</span><input name="pageTagRange" type="text" id="pageTagRange" size="10" value="<?= $pageTagRange?>" /><i>如果出现重复的标签，你可在这里指定抓取的范围，如："1,4-6"，代表抓取第1次和第4、5、6次出现的标签内容；如果此值为0或空则采集所有。</i></label>
	<label>
		<span>&nbsp;</span><button type="submit">测试抓取</button>
	</label>
</form>