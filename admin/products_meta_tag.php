<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
require ('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('products_meta_tag');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require 'includes/classes/ProductList.class.php';
require 'includes/classes/ProductMetaTag.class.php';
require 'includes/classes/GetAgency.class.php';
$meta_tag=new ProductMetaTag;
if(isset($_POST['ajax'])&&$_POST['ajax']){
	$meta_tag->change($_POST['id'], iconv('utf-8', CHARSET,$_POST['value']), $_POST['type']);
	echo true;
	exit;
}
$agency=new getAgency();
$agency_list=$agency->get();
$data_list=$meta_tag->getList($_GET);
$agency_arr=$agency->createOneAgency($agency_list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>产品KDT</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js" ></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js" ></script>
<script type="text/javascript">
function myShow(doc){
	doc=doc.parent('td');
	if(doc.find("#text").is(":hidden")){
	doc.find("#text").show();
	doc.find("#text>input")[0].focus();
	doc.find("#show").hide();
	}	
}
function changeOne(type,value,doc,id){
	var doc_td=doc.parent('span').parent('td');
	
	if(doc_td.find("#show").text()!=value){
		jQuery.post("products_meta_tag.php",{ajax:true,type:type,value:value,id:id},function(result){
		if(result){
			doc_td.find("#text").hide();
			doc_td.find("#show").text(value);
			doc_td.find("#show").show();
		}
	});
	}
}
</script>
<style type="text/css">
#connter {
	width: 960px;
	margin: 0 auto;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

table th,#TableList td {
	border: 1px solid #DCDCDC;
	background: #eee;
	line-height: 25px;
}

#TableList td {
	background: #fff;
	text-align: center;
}
.tr1{
	background: #DCDCDC ;
}
tr{ height:30px}
.contentTable,.contentTable td{ 
pading:5px; border:1px solid #DCDCDC; border-bottom-color:#666; border-right-color:#666;border-left-color:#666; width:300px; border-spacing:0;
}
.ui-helper-hidden-accessible{display:none}
</style>
</head>
<body>

<?php
require (DIR_WS_INCLUDES . 'header.php');
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>
<h1>(产品中心)产品KDT管理</h1>
<br />
<br />
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('products_meta_tag');
$list = $listrs->showRemark();
?>
<fieldset>
<legend style="text-align:left">搜索区域</legend>
<form action="" method="get">
	地接商编号：
	<select name="agency_id">
	<option value="">请选择供应商</option>
	<?php echo $agency->dreawAgencyOption($agency_list,$_GET['agency_id'])?>
	</select>
	产品ID：
	<input type="text" value="<?php echo tep_db_input($_GET['product_id'])?>" name="product_id" />
	title tags：
	<input type="text" value="<?php echo tep_db_input($_GET['tt'])?>" name="tt" />
	desc tags：
	<input type="text" value="<?php echo tep_db_input($_GET['dt'])?>" name="dt" />
	keywords tags：
	<input type="text" value="<?php echo tep_db_input($_GET['kt'])?>" name="kt" />
	<input type="submit" value="搜索" />
	<input type="hidden" name="action" value="search" />
	<a href="products_meta_tag.php">清空搜索结果</a>
</form>
</fieldset>
<br/>
	<br />
	<br />
	<table width="100%" border="1">
		<tr>
			<th>产品ID</th>
			<th>产品名称</th>
			<th>供应商</th>
			<th>title tags</th>
			<th>keywords tags</th>
			<th>desc tags</th>
		</tr>
		
  <?php $i=0;foreach($data_list['info'] as $value){ $i++;?>
  <tr <?php if ($i%2==0) echo 'class="tr1"';?>>
			<td><a target="_blank" href="<?php echo tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['products_id']);?>"><?=$value['products_id']?></a></td>
			<td><a target="_blank" href="<?php echo tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['products_id']);?>"><?=$value['products_name']?></a></td>
			<td><?=$agency_arr[$value['agency_id']]?></td>
			<td >
				<?php if(!$value['products_head_title_tag']){?>
				<input type="button" onclick="myShow(jQuery(this))" value="+++" />
				<?php }?>
				<span id="show" onclick="myShow(jQuery(this))" style="width:600px">
				<?=$value['products_head_title_tag']?>
				</span>
				<span style="display:none" id="text">
				<input type="text" id="tt_<?=$value['products_id']?>" value="<?=$value['products_head_title_tag']?>" onblur="changeOne('tt',this.value,jQuery(this),<?=$value['products_id']?>)" size="100"/>
				</span>
			</td>
			<td>
			<?php if(!$value['products_head_keywords_tag']){?>
				<input type="button" onclick="myShow(jQuery(this))" value="+++" />
				<?php }?>
				<span id="show" onclick="myShow(jQuery(this))">
				<?=$value['products_head_keywords_tag']?>
				</span>
				<span style="display:none" id="text">
				<input type="text" id="kt_<?=$value['products_id']?>" value="<?=$value['products_head_keywords_tag']?>" onblur="changeOne('kt',this.value,jQuery(this),<?=$value['products_id']?>)" size="100"/>
				</span>
			</td>
			<td>
			<?php if(!$value['products_head_desc_tag']){?>
				<input type="button" onclick="myShow(jQuery(this))" value="+++" />
				<?php }?>
				<span id="show" onclick="myShow(jQuery(this))">
				<?=$value['products_head_desc_tag']?>
				</span>
				<span style="display:none" id="text">
				<input type="text" id="dt_<?=$value['products_id']?>" value="<?=$value['products_head_desc_tag']?>" onblur="changeOne('dt',this.value,jQuery(this),<?=$value['products_id']?>)" size="100"/>
				</span>
			</td>
			
  </tr>
  <?php }?>


	</table>
	<table width="100%" border="1">
<td colspan="2" align="left"><?php echo $data_list['b']?></td><td colspan="5" align="right"><?php echo $data_list['a']?></td>
</table>
<br />
<br />
<br />
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>