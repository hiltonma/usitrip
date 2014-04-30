<?php
require('includes/application_top.php'); 

//条件
$where=" where products_id > 0  ";

if(tep_not_null($_GET['search_type']) && tep_not_null($_GET['keyword'])){
	switch($_GET['search_type']){
		case 'products_NO': $where.=" AND products_NO regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
		case 'products_name': $where.=" AND products_name regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
		case 'upload_people': $where.=" AND products_upload_people regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
		//case 'products_date': $where=" AND products_date regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
	}
}

if((int)$_GET['categories_id0']){
	$_GET['categories_id']=(int)$_GET['categories_id0'];
}
if((int)$_GET['categories_id1']){
	$_GET['categories_id']=(int)$_GET['categories_id1'];
}
if((int)$_GET['categories_id2']){
	$_GET['categories_id']=(int)$_GET['categories_id2'];
}
if($_GET['categories_id']>0){
	$cate_only = false;	
	if($cate_only==true){
		$where .=" AND categories_id='".(int)$_GET['categories_id']."' ";
	}else{
		//查询当前类别包括其子类
		$where .=" AND ( categories_id='".(int)$_GET['categories_id']."' ";
		$cate_array = array();
		tep_get_subcategories($cate_array, (int)$_GET['categories_id']);
		for($i=0; $i<count($cate_array); $i++){
			if((int)$cate_array[$i]){
				$where .=" OR categories_id='".(int)$cate_array[$i]."' ";
			}
		}
		
		$where .=" ) ";
	}
}

if(tep_not_null($_GET['min_date'])){
	$where .= ' AND products_date >= "'.$_GET['min_date'].' 00:00:00" ';
}
if(tep_not_null($_GET['max_date'])){
	$where .= ' AND products_date <= "'.$_GET['max_date'].' 23:59:59" ';
}
if((int)$_GET['search_scope']){
	$where .= ' AND products_scope = '.(int)$_GET['search_scope'].' ';
}

//排序
$order_by = " ORDER BY products_date DESC ";
if($_GET['OrderBy']=='date'){
	$order_by = " ORDER BY products_date DESC ";
}
if($_GET['OrderBy']=='reviews'){
	$order_by = " ORDER BY reviews_total DESC ";
}

//最大记录数

	//当前页数
	$pageNum=1;
	if($_GET['pageNum']>=1){
		$_GET['pageNum'] = intval($_GET['pageNum']);
		$pageNum=$_GET['pageNum'];
	}
	#每页最大显示的记录数
	$maxRows = 36;
	#开始行数
	$startRow=($pageNum-1) * $maxRows;
	//查询数据库
	
	$limit = ' limit '.$startRow.','.$maxRows;
	
	$sql = tep_db_query('SELECT *  FROM `products` '.$where.$order_by.$limit);	
	$row = tep_db_fetch_array($sql);

	//总记录
	
		$sql_total = tep_db_query("select count(*) as c_num from `products` $where ");
		$row_total =  tep_db_fetch_array($sql_total);
		$totalRows = (int)$row_total['c_num'];


	//总页数
	$totalPages = ceil($totalRows/$maxRows);
	//如果当前页大于总页数则立即返回到最后一页
	if($totalRows>0 && ($pageNum > $totalPages) ){
		$_SERVER['QUERY_STRING']=ereg_replace("pageNum=$pageNum","pageNum=$totalPages",$_SERVER['QUERY_STRING']);
		$Chg_page=$_SERVER['SCRIPT_URI']."?".$_SERVER['QUERY_STRING'];
		header("Location: $Chg_page");
		exit;
	}


		//分页管理
		$pageNum_String = "pageNum";		//在url地址上要显示当前页变量的名称
		$totalRows_String = "totalRows";	//在url地址上要显示当总记录数变量的名称
		$totalRows_RecSQL = $totalRows;				//总记录数
		$totalPages = $totalPages;						//总页数
		$dis_sum = 20;							//每显示20个页码数字
		$text_size = "12px";					//设置页码文字大小
		$now_page = intval(min(max(($_GET[$pageNum_String]),1), $totalPages));	//取得当前页的页码。
		
		$page_class = new set_pagination;
		$page_mode = $page_class -> pagination($pageNum_String, $totalRows_String, $totalRows_RecSQL, $totalPages, $dis_sum, $now_page, $text_size);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>图片管理系统</title>
<base href="<?php echo WEB_DIR?>" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="spiffyCal/spiffyCal_v2_1.css" rel="stylesheet" type="text/css" />
<script src="spiffyCal/spiffyCal_v2_1_2008_04_01.js" type="text/javascript"></script>

<script type="text/JavaScript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function submit_reviews(from_name, Layer_id){
 	var url = "write_reviews_ajax.php";
	var products_id = from_name.replace(/FormWriteReviews/,"");
	var Submit_id = "Submit_" + products_id;
	var DG_Submit_id = document.getElementById(Submit_id);
	
	if(document.all){	/*IE*/
		var form = document.all(from_name);
		if(form.elements["reviews_content"].value=="" || form.elements["reviews_people_name"].value==""){
			alert("评论内容 和 您的姓名 必须填写！");
			return false;
		}
		
		var aparams=new Array();  //创建一个数组存表单所有元素和值
		for(i=0; i<form.length; i++){
			var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
			sparam+="=";     //名与值之间用"="号连接
			sparam+=encodeURIComponent(form.elements[i].value);   //获得表单元素值
			aparams.push(sparam);   //push是把新元素添加到数组中去
		}
		var post_str=aparams.join("&");		//使用&将各个元素连接
		
		//alert(post_str);
		
	}else{	//其它浏览器
		var form = document.getElementsByTagName("*");
		for(var i=0;i <form.length;i++) { 
			if(form[i].name==null) continue; 
			if(form[i].name== from_name){ 
				if(form[i].reviews_content.value=="" || form[i].reviews_people_name.value==""){
					alert("评论内容 和 您的姓名 必须填写！");
					return false;
				}
				var aparams=new Array();  //创建一个数组存表单所有元素和值
				for(j=0; j<form[i].length; j++){
					var sparam=encodeURIComponent(form[i].elements[j].name);  //取得表单元素名
					sparam+="=";     //名与值之间用"="号连接
					sparam+=encodeURIComponent(form[i].elements[j].value);   //获得表单元素值
					aparams.push(sparam);   //push是把新元素添加到数组中去
				}
				var post_str=aparams.join("&");		//使用&将各个元素连接
				
				form[i].reviews_content.value="";
				//alert(post_str);
			} 
		}
	}
	
	DG_Submit_id.value = " 发送中……";
	DG_Submit_id.className = "submit_buutom";
	DG_Submit_id.disabled = true;
	
	if(window.XMLHttpRequest) {
		 AJAX = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		try {
				AJAX = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
		try {
				AJAX = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!AJAX) {
		 window.alert("不能创建XMLHttpRequest对象实例.");
		 return false;
	}
	
	AJAX.open("POST", url, true); 
	AJAX.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	AJAX.send(post_str);
	//获取执行状态
	AJAX.onreadystatechange = function() { 
		if (AJAX.readyState == 4 && AJAX.status == 200 ) { 
			if(AJAX.responseText.search(/\[OK\]1\[\/OK\]/g)!=-1){		
				document.getElementById(Layer_id).className = "Layer_reviews_none";
				if(document.all){
					form.elements["reviews_content"].value="";
				}
				//alert("评论发表成功!");
				
				var Layer_look_reviews = "Layer_look_reviews_"+products_id;
				LookReviews(Layer_look_reviews,products_id);
				
				DG_Submit_id.value = "<?php echo SUBMIT?>";
				DG_Submit_id.className = "";
				DG_Submit_id.disabled = false;
			}else{
				alert(AJAX.responseText);
			}
		}
		
	}
	
}

var ajax_status = true; 
function LookReviews(id,reviews_type_id){
	if(ajax_status==false){
		var idid = document.getElementById(id);
		idid.className = "Layer";
		idid.innerHTML="此进程被其它进程使用。<a href=\"JavaScript:LookReviews('"+id+"','"+ reviews_type_id +"')\" title=\"重试\">[重试]</a><a href=\"JavaScript:CloseReviews('Layer_look_reviews_"+ reviews_type_id +"')\" title=\"关闭\">[×]</a>";
	}
	if(ajax_status==true){
		ajax_status = false;
		var id = document.getElementById(id);
		var url = "look_reviews_ajax.php";
		var post_str="reviews_type=products&reviews_type_id=" + reviews_type_id;
		id.className = "Layer";
		id.innerHTML="<img src='loading_16x16.gif' align='absmiddle' />&nbsp;正在载入评论……<a href=\"JavaScript:CloseReviews('Layer_look_reviews_"+ reviews_type_id +"')\" title=\"关闭\">[×]</a>";
		
		if(window.XMLHttpRequest) {
			 AJAX = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) {
			try {
					AJAX = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
			try {
					AJAX = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
		if (!AJAX) {
			 window.alert("不能创建XMLHttpRequest对象实例.");
			 return false;
		}
		AJAX.open("POST", url, true); 
		AJAX.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		AJAX.send(post_str);
		//获取执行状态
		AJAX.onreadystatechange = function() { 
			if (AJAX.readyState == 4 && AJAX.status == 200 ) { 
				id.innerHTML = AJAX.responseText;
				ajax_status = true;
			}
			
		}
	}

}

function CloseReviews(id){
	var id = document.getElementById(id);
	id.className = "Layer_none";
}

function OpenWriteReviews(id){
	document.getElementById(id).className='Layer_reviews';
}

function change_pic_src(id,new_src,load_pic_id){
	var id = document.getElementById(id);
	var Layer_load_pic = document.getElementById(load_pic_id);
	if(id.src.search(/zoom.gif$/) !=-1 ){
		Layer_load_pic.innerHTML = "<img src='loading_16x16.gif' align='absmiddle' />";
		Layer_load_pic.className = "Layer_load_pic";
		id.src = new_src;
		id.className = "Layer_dis_pic_max";
	}else{
		Layer_load_pic.className = "Layer_load_pic_none";
		id.src = "zoom.gif";
		id.className = "Layer_dis_pic";
	}
}

/*移动对像*/
var X;
var Y;
var Act = false;
var move_table_id ="";
var showMouseXY = function(e){
	var e = window.event || e;
		X = e.clientX + document.documentElement.scrollLeft;
		Y = e.clientY + document.documentElement.scrollTop;
	if(Act==true && move_table_id!=""){
		document.getElementById( move_table_id).style.top = Y-13;
		document.getElementById( move_table_id).style.left = X-18;
	}

}

document.onmousemove = showMouseXY;	

function move_action(table_id){
	move_table_id = table_id;
	if(Act == false){
		Act = true;
	}else{
		Act = false;
	}
}

//复制内容到粘贴板
function CopyUrl(obj_name){
	var obj = document.getElementById(obj_name);
	copyToClipboard(obj.value);
}

function  copyToClipboard(txt){   
	   if(window.clipboardData) {   
               window.clipboardData.clearData();   
               window.clipboardData.setData("Text", txt);
             alert("复制成功！\n\n"+ txt +"\n\n在需要添加图片地址的地方粘贴即可！");   
       } else if(navigator.userAgent.indexOf("Opera") != -1) {   
            window.location = txt;   
       } else if (window.netscape) {   
            try {   
                 netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");   
            } catch (e) {   
                 alert("<?php echo JS_MSN_FIREFOX_WRING ?>");   
            }   
            var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);   
            if (!clip)   
                 return;   
            var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);   
            if (!trans)   
                 return;   
            trans.addDataFlavor('text/unicode');   
            var str = new Object();   
            var len = new Object();   
            var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);   
            var copytext = txt;   
            str.data = copytext;   
            trans.setTransferData("text/unicode",str,copytext.length*2);   
            var clipid = Components.interfaces.nsIClipboard;   
            if (!clip)   
                 return false;   
            clip.setData(trans,null,clipid.kGlobalClipboard);   
            alert("复制成功！\n\n"+ txt +"\n\n在需要添加图片地址的地方粘贴即可！");  
       }   
} 

//-->
</script>
<?php require(PATH_DIR.'sel_categories.php'); ?>

</head>

<body>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
    <td height="30" align="center" valign="middle"><span class="STYLE0"><?php echo AnoOne_Tee_Document_Management_System ?></span>&nbsp;<!--<a href="?lang=cn">[中文]</a>&nbsp;<a href="?lang=en">[English]</a>--></td>
  </tr>
  <tr>
    <td>
	<fieldset style="background-image:url(products/bg.gif); ">
	<legend align="left"><span class="STYLE1"><?php echo Navigation_Bar?></span></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><a href="index.php"><?php echo Pictures_Database?></a></td>
    <td align="right"><b><?php echo Product_Database?></b></td>
    <td align="right"><a href="creative.php"><?php echo DesignDraft_Database?></a></td>
    <td align="right">&nbsp;</td>
  </tr>
</table>

	</fieldset>

	</td>
  </tr>
  <tr>
    <td>
	<div id="spiffycalendar"  style="z-index:2000;margin-left:21px;"></div>
	<form id="form1" name="form1" method="get" action="">
	<fieldset>
	<legend align="left"><span class="STYLE1"><?php echo Search?></span></legend>
	<table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo DATE?>：</td>
        <td><?php echo FROM?>
		<script type="text/javascript"><!--
	var MIN_date = new ctlSpiffyCalendarBox("MIN_date", "form1", "min_date","btnDate","<?php echo $_GET['min_date']?>",scBTNMODE_CALBTN);
	MIN_date.writeControl(); MIN_date.dateFormat="yyyy-MM-dd";
	//--></script>
		
		<?php echo TO?>
		<script type="text/javascript"><!--
	var MAX_date = new ctlSpiffyCalendarBox("MAX_date", "form1", "max_date","btnDate","<?php echo $_GET['max_date']?>",scBTNMODE_CALBTN);
	MAX_date.writeControl(); MAX_date.dateFormat="yyyy-MM-dd";
	//--></script>		</td>
		</tr>
      <tr>
        <td><?php echo Search?></td>
        <td><?php
	$option_array = array();
	$option_array[0]['id']='products_name';
	$option_array[0]['text']=Name;
	$option_array[1]['id']='products_NO';
	$option_array[1]['text']=NO_CODE;
	$option_array[2]['id']='upload_people';
	$option_array[2]['text']=Person;
	//$option_array[3]['id']='products_date';
	//$option_array[3]['text']='日期';

	echo tep_draw_pull_down_menu('search_type', $option_array);
	
	echo tep_draw_input_field('keyword', '', ' size="30" ');

?></td>
      </tr>
      <tr>
        <td><?php echo Category?>：</td>
        <td><?php
 	//一级类
	$sql_group = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE parent_id =0 AND c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	
	$row_group = tep_db_fetch_array($sql_group);

	$group_option_array = array();
	$group_option_array[0]['id']='0';
	$group_option_array[0]['text']=All;
	$do_group=1;
	do{
		$group_option_array[$do_group]['id'] = $row_group['categories_id'];
		if($_SESSION['lang']=='en' && $row_group['group_name_en']!=""){
			$group_option_array[$do_group]['text'] = $row_group['group_name_en'];
		}else{
			$group_option_array[$do_group]['text'] = $row_group['categories_name'];
		}		
		$do_group++;
	}while( $row_group = tep_db_fetch_array($sql_group));
	echo tep_draw_pull_down_menu('categories_id0', $group_option_array, '', 'onchange="SelChange1(categories_id0);SelChange1(categories_id1);" ');
	
	//二级类
	if((int)$categories_id0){
		$sql_cate = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE parent_id ='.(int)$categories_id0.' AND c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	
	
		$state_select="";
		$option_array = array();
		$option_array[0]['id']='';
		$option_array[0]['text']='--选择类别--';
		$do_cate=1;
		while( $row_cate = tep_db_fetch_array($sql_cate)){
			$option_array[$do_cate]['id'] = $row_cate['categories_id'];
			$option_array[$do_cate]['text'] = $row_cate['categories_name'];
			$do_cate++;
		}
		if(count($option_array)>1){
			echo tep_draw_pull_down_menu('categories_id1', $option_array, '', '  onchange="SelChange1(categories_id1);SelChange1(categories_id2);" ');
		}else{
			echo '<select style="display:none" name="categories_id1" id="categories_id1" onchange="SelChange1(categories_id1);SelChange1(categories_id2);"></select>';
		}
	}else{
		echo '<select style="display:none" name="categories_id1" id="categories_id1" onchange="SelChange1(categories_id1);SelChange1(categories_id2);"></select>';
	}
	//三级类	
	if((int)$categories_id1){
		$sql_cate = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE parent_id ='.(int)$categories_id1.' AND c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	

		$state_select="";
		$option_array = array();
		$option_array[0]['id']='0';
		$option_array[0]['text']='--选择类别--';
		$do_cate=1;
		while( $row_cate = tep_db_fetch_array($sql_cate)){
			$option_array[$do_cate]['id'] = $row_cate['categories_id'];
			$option_array[$do_cate]['text'] = $row_cate['categories_name'];
			$do_cate++;
		}
		if(count($option_array)>1){
			echo tep_draw_pull_down_menu('categories_id2', $option_array, '', ' onchange="form1.submit()" ');
		}else{
			echo '<select style="display:none" name="categories_id2" id="categories_id2"></select>';
		}
	}else{
		echo '<select style="display:none" name="categories_id2" id="categories_id2"></select>';
	}


?></td>
        </tr>
      <tr>
        <td>性质：</td>
        <td>
		  <?php $search_scope = (int)$search_scope;?>
		  <label><?php echo tep_draw_radio_field('search_scope', '1', '')?>行程</label>
		  <label><?php echo tep_draw_radio_field('search_scope', '2', '')?>组图</label>
		  <label><?php echo tep_draw_radio_field('search_scope', '99', '')?>其它</label>
		  <label><?php echo tep_draw_radio_field('search_scope', '0', '')?>不限</label>
		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="Submit2" value="<?php echo GO?>" />
          <input name="Submit" type="button" onclick="MM_goToURL('parent','upload_products.php');return document.MM_returnValue" value="<?php echo Upload_Product?>" /></td>
      </tr>
    </table>
	</fieldset>
	<fieldset>
	<legend align="left"><span class="STYLE1"><?php echo ORDER_BY?></span></legend>
	<div>
		<?php echo tep_draw_radio_field('OrderBy', 'date','', ' onclick="form1.submit()" ')  .UP_DATE ?>
		<?php echo tep_draw_radio_field('OrderBy', 'reviews','', ' onclick="form1.submit()" ') .COMMENT_NUM ?>
	</div>
	</fieldset>
    </form>
	
	</td>
  </tr>
  <tr>
    <td>
	<fieldset>
	<legend align="left"><span class="STYLE1"><?php echo PRODUCTS_LIST?></span></legend>

<?php if($totalRows){?>	
	<table border="0" cellspacing="0" cellpadding="0">
  <tr>

<?php $do=1; do{

//设定选择sm图和原图
$image_sm = tep_db_output($row['products_file_name']);
if(tep_not_null($row['products_file_name_sm'])){
	$image_sm = tep_db_output($row['products_file_name_sm']);
}
?>
    <td align="left" valign="top">
	  <img src="zoom.gif" alt="<?php echo nl2br(tep_db_output($row['products_name'])); ?>" name="Layer_dis_pic_<?php echo $row['products_id'] ?>" class="Layer_dis_pic" id="Layer_dis_pic_<?php echo $row['products_id'] ?>" onclick="change_pic_src('Layer_dis_pic_<?php echo $row['products_id'] ?>',&quot;<?php echo IMAGES_HTTP_DIR.'products/'.tep_db_output($row['products_file_name']);?>&quot; ,'Layer_load_pic_<?php echo $row['products_id'] ?>')" />
	
	<div id="Layer_load_pic_<?php echo $row['products_id'] ?>" class="Layer_load_pic_none"></div>
	
	
	<div style="width:152px; height:114px; border: 2px solid #CCCCCC;">
	<a href="<?php echo IMAGES_HTTP_DIR.'products/'.tep_db_output($row['products_file_name']);?>" target="_blank">
	<img src="<?php echo IMAGES_HTTP_DIR.'products/'.$image_sm;?>" alt="<?php echo tep_db_output($row['products_NO']); ?>" <?php echo getimgHW3hw(IMAGES_DIR.'products/'.$image_sm,SM_WIDTH,SM_HEIGHT);?> border="0" />	</a>	</div>
	
<?php
/*载入前3条评论，每条一行*/
if($row['reviews_total']){
	$sql_reviews = tep_db_query("select * from `reviews` where  reviews_type='products' AND reviews_type_id='".$row['products_id']."' order by reviews_id DESC limit 3; ");
	$rows_reviews =  tep_db_fetch_array($sql_reviews);
	if($rows_reviews['reviews_id']){
		do{
?>	
<div class="reviews_list0"><?php echo cutword(tep_db_output($rows_reviews['reviews_content']),24) ?></div>	
<?php 
		}while($rows_reviews =  tep_db_fetch_array($sql_reviews));
	}
}
?>
	
	</td>
    <td width="150" align="left" valign="top"><strong><?php echo NO_CODE ?>：</strong><?php echo tep_db_output($row['products_NO']); ?><br />
      <strong><?php echo Name?>：</strong><?php echo nl2br(tep_db_output($row['products_name'])); ?><br />
      <strong><?php echo Person?>：</strong><?php echo tep_db_output($row['products_upload_people']); ?><br />
      <strong><?php echo DATE?>：</strong><?php echo preg_replace('/ .*$/','',$row['products_date']); ?><br />
	  <strong><?php echo Category?>：</strong>
	  <?php 
	  $cate_str = '';
	  $cate_str = tep_db_output(get_product_group_name($cate_str, $row['categories_id']));
	  echo $cate_str;
	  ?>
	  <br />
	  <div><input style="display:none" name="url_addrss_<?php echo $row['products_id'] ?>" type="text" id="url_addrss_<?php echo $row['products_id'] ?>" value="<?php echo IMAGES_HTTP_DIR.'products/'.tep_db_output($row['products_file_name']);?>" />
	  <input name="" type="button" onclick="CopyUrl('url_addrss_<?php echo $row['products_id'] ?>')" value="复制图片地址" />
	  </div>
	  <div id="Layer_look_reviews_<?php echo $row['products_id'] ?>" class="Layer_none">
		</div>
	  
	  
	  
	  <div id="Layer_reviews_<?php echo $row['products_id'] ?>" class="Layer_reviews_none">
	  	<form action="" method="get" name="FormWriteReviews<?php echo $row['products_id'] ?>" onsubmit="submit_reviews('FormWriteReviews<?php echo $row['products_id'] ?>', 'Layer_reviews_<?php echo $row['products_id'] ?>'); return false;" id="FormWriteReviews<?php echo $row['products_id'] ?>">
		<fieldset>
		<legend align="left"><img onclick="move_action('Layer_reviews_<?php echo $row['products_id'] ?>')" style="margin-right: 5px; margin-bottom:0px;" src="move.gif" alt="移动" width="25" height="21" />给 <?php echo tep_db_output($row['products_name']); ?> 评论</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right" valign="middle"><?php echo COMMENT_CONTENT?>：</td>
			<td align="left" valign="middle"><textarea name="reviews_content" cols="30" rows="3" wrap="physical" id="reviews_content"></textarea></td>
		  </tr>
		  <tr>
		    <td align="right"><?php echo YOUR_NAME?>：</td>
		    <td align="left"><input name="reviews_people_name" type="text" id="reviews_people_name" size="32" /></td>
		    </tr>
		  <tr>
		    <td align="right">&nbsp;</td>
		    <td align="left"><input name="Submit_<?php echo $row['products_id'] ?>" type="submit" id="Submit_<?php echo $row['products_id'] ?>" value="<?php echo SUBMIT?>" />
		      <input name="Submit4" type="button" onclick="document.getElementById('Layer_reviews_<?php echo $row['products_id'] ?>').className='Layer_reviews_none'" value="<?php echo CLOSE?>" />
		      <input name="reviews_type_id" type="hidden" id="reviews_type_id" value="<?php echo tep_db_output($row['products_id']); ?>" />
		      <input name="reviews_type" type="hidden" id="reviews_type" value="products" /></td>
		    </tr>
		  <tr>
		    <td align="right">&nbsp;</td>
		    <td align="left">&nbsp;</td>
		    </tr>
		</table>
		</fieldset>
		</form>
		</div>
		
		
		<div id="re_msn_<?php echo $row['products_id']?>" style="display:<?php if(!$row['reviews_total']){?>none<?php }?>"><strong><?php echo COMMENT?>：</strong>
		<?php printf(HOW_HAVE_COMMENT,'re_total_'.$row['products_id'], (int)$row['reviews_total'])?>
		<input onclick="JavaScript:LookReviews('Layer_look_reviews_<?php echo $row['products_id'] ?>','<?php echo $row['products_id'] ?>')" class="link_buttom" name="" type="button" value="<?php echo VIEW_COMMENT?>" /></div>
		
	    <input name="Submit4" type="button" class="link_buttom" onclick="document.getElementById('Layer_reviews_<?php echo $row['products_id'] ?>').className='Layer_reviews'" value="<?php echo SEND_COMMENT?>" />		</td>

	<?php if($do%3==0 && $do>0){?>
  </tr>
  <tr>
    <td height="30" colspan="6" align="center" valign="top">&nbsp;</td>
    </tr>
  <tr>
	<?php }?>	  
<?php $do++; }while( $row = tep_db_fetch_array($sql));?>
</table>
<?php }?>
	</fieldset>	</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="middle">总记录：<?php echo $totalRows?>　当前显示第<?php echo ($startRow+1)?>至第<?php echo min($totalRows,($startRow+$maxRows))?>条记录</td>
    <td align="right" valign="middle"><?php echo $page_mode ?></td>
  </tr>
</table></td>
  </tr>
</table>
</body>
</html>
