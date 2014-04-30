<?php
require('includes/application_top.php'); 

//条件
$where=" where images_id > 0 ";

if(tep_not_null($_GET['search_type']) && tep_not_null($_GET['keyword'])){
	switch($_GET['search_type']){
		case 'title': $where.=" AND images_name regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
		case 'description': $where.=" AND images_description regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
		case 'upload_people': $where.=" AND images_upload_people regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
		//case 'images_date': $where.=" AND images_date regexp '".tep_db_prepare_input($_GET['keyword'])."' "; break;
	}
}

if($_GET['group_id']>0){
	$where .=" AND group_id='".(int)$_GET['group_id']."' ";
}

if(tep_not_null($_GET['min_date'])){
	$where .= ' AND images_date >= "'.$_GET['min_date'].' 00:00:00" ';
}
if(tep_not_null($_GET['max_date'])){
	$where .= ' AND images_date <= "'.$_GET['max_date'].' 23:59:59" ';
}


//排序
$order_by = " ORDER BY images_date DESC ";

if($_GET['OrderBy']=='date'){
	$order_by = " ORDER BY images_date DESC ";
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
	$maxRows = 2;
	#开始行数
	$startRow=($pageNum-1) * $maxRows;
	//查询数据库
	
	$limit = ' limit '.$startRow.','.$maxRows;
	
	$sql = tep_db_query('SELECT *  FROM `images` '.$where.$order_by.$limit);	
	$row = tep_db_fetch_array($sql);

	//总记录
	
		$sql_total = tep_db_query("select count(*) as c_num from `images` $where ");
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
<script>
function Pause(obj,iMinSecond){
	if (window.eventList==null) window.eventList=new Array();
	var ind=-1;
	for (var i=0;i<window.eventList.length;i++){
		if (window.eventList[i]==null) {
			window.eventList[i]=obj;
			ind=i;
			break;
		}
	}

	if (ind==-1){
		ind=window.eventList.length;
		window.eventList[ind]=obj;
	}
	setTimeout("GoOn(" + ind + ")",iMinSecond);
}

/*
该函数把要暂停的函数放到数组window.eventList里，同时通过setTimeout来调用继续函数。
继续函数如下：
*/

function GoOn(ind){
	var obj=window.eventList[ind];
	window.eventList[ind]=null;
	if (obj.NextStep) obj.NextStep();
	else obj();
}
/*
该函数调用被暂停的函数的NextStep方法，如果没有这个方法则重新调用该函数。
函数编写完毕，我们可以作如下册是：
*/
function Test(){
	alert("hellow");
		Pause(this,3000);//调用暂停函数
	this.NextStep=function(){
		alert("NextStep");
	}
}
</script>
<script type="text/JavaScript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function submit_reviews(from_name, Layer_id){
 	var url = "write_reviews_ajax.php";
	var images_id = from_name.replace(/FormWriteReviews/,"");
	var Submit_id = "Submit_" + images_id;
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
				
				var Layer_look_reviews = "Layer_look_reviews_"+images_id;
				LookReviews(Layer_look_reviews,images_id);
				
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
		//var new_id = id;
		//var new_type_id=reviews_type_id;
		//Pause(this,10000);//调用暂停函数
		//this.NextStep=function(){
			//LookReviews(new_id,new_type_id);
			//alert("NextStep");
		//}
		//return false;
	}
	if(ajax_status==true){
		ajax_status = false;
		var id = document.getElementById(id);
		var url = "look_reviews_ajax.php";
		var post_str="reviews_type=images&reviews_type_id=" + reviews_type_id;
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

//预先载入图像
var preloadImages = new Array();
var images_num = 0;
function auto_load_images_for_relevant(ImagesSrc){
	preloadImages[images_num] = new Image();
	preloadImages[images_num].src = ImagesSrc;
	images_num++;
}


//-->
</script>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" align="center" valign="middle"><span class="STYLE0"><?php echo AnoOne_Tee_Document_Management_System ?></span>&nbsp;<!--<a href="?lang=cn">[中文]</a>&nbsp;<a href="?lang=en">[English]</a>--></td>
  </tr>
  
  <tr>
    <td>
	
	<fieldset style="background-image:url(images/bg.gif)">
	<legend align="left"><span class="STYLE1"><?php echo Navigation_Bar?></span></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><b><?php echo Pictures_Database?></b></td>
    <td align="right"><a href="products.php"><?php echo Product_Database?></a></td>
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
	//--></script>
		</td>
		<td><?php echo Search?></td>
        <td nowrap="nowrap">
<?php
	$option_array = array();
	$option_array[0]['id']='title';
	$option_array[0]['text']=Name;
	$option_array[1]['id']='description';
	$option_array[1]['text']=Description;
	$option_array[2]['id']='upload_people';
	$option_array[2]['text']=Person;
	//$option_array[3]['id']='images_date';
	//$option_array[3]['text']='日期';

	echo tep_draw_pull_down_menu('search_type', $option_array);
	
	echo tep_draw_input_field('keyword', '', ' size="30" ');

?>		  </td>
        <td><?php echo Category?>：
<?php
 	$sql_group = tep_db_query('SELECT *  FROM `images_group` ORDER BY group_id ASC');	
	$row_group = tep_db_fetch_array($sql_group);

	$group_option_array = array();
	$group_option_array[0]['id']='0';
	$group_option_array[0]['text']=All;
	$do_group=1;
	do{
		$group_option_array[$do_group]['id'] = $row_group['group_id'];
		if($_SESSION['lang']=='en' && $row_group['group_name_en']!=""){
			$group_option_array[$do_group]['text'] = $row_group['group_name_en'];
		}else{
			$group_option_array[$do_group]['text'] = $row_group['group_name'];
		}
		$do_group++;
	}while( $row_group = tep_db_fetch_array($sql_group));
	echo tep_draw_pull_down_menu('group_id', $group_option_array, '', ' onchange="form1.submit()" ');

?>		</td>
        <td><input type="submit" name="Submit2" value="<?php echo GO?>" />
        <input name="Submit" type="button" onclick="MM_goToURL('parent','upload.php');return document.MM_returnValue" value="<?php echo Upload_Pic?>" /></td>
      </tr>
    </table>
	</fieldset>
	<fieldset>
	<legend align="left"><span class="STYLE1"><?php echo ORDER_BY?></span></legend>
	<div><?php echo tep_draw_radio_field('OrderBy', 'date','', ' onclick="form1.submit()" ')  .UP_DATE ?>
		<?php echo tep_draw_radio_field('OrderBy', 'reviews','', ' onclick="form1.submit()" ') .COMMENT_NUM ?>
	</div>
	</fieldset>
	</form>
	
	</td>
  </tr>
  <tr>
    <td>
	<fieldset>
	<legend align="left"><span class="STYLE1"><?php echo PIC_LIST?></span></legend>

<?php if($totalRows){?>	
	<table border="0" cellspacing="0" cellpadding="0">
  <tr>

<?php $do=1; do{

//设定选择sm图和原图
$image_sm = tep_db_output($row['images_file_name']);
if(tep_not_null($row['images_file_name_sm'])){
	$image_sm = tep_db_output($row['images_file_name_sm']);
}
?>
    <td align="left" valign="top">
	<img src="zoom.gif" alt="<?php echo nl2br(tep_db_output($row['images_name'])); ?>" name="Layer_dis_pic_<?php echo $row['images_id'] ?>" class="Layer_dis_pic" id="Layer_dis_pic_<?php echo $row['images_id'] ?>" onclick="change_pic_src('Layer_dis_pic_<?php echo $row['images_id'] ?>','<?php echo IMAGES_HTTP_DIR.'images/'.tep_db_output($row['images_file_name']);?>' ,'Layer_load_pic_<?php echo $row['images_id'] ?>')" />
	
	<div id="Layer_load_pic_<?php echo $row['images_id'] ?>" class="Layer_load_pic_none"></div>
	
	<div style="width:152px; height:114px; border: 2px solid #CCCCCC;">
	<a href="<?php echo IMAGES_HTTP_DIR.'images/'.tep_db_output($row['images_file_name']);?>" target="_blank" onmouseover="auto_load_images_for_relevant('<?php echo IMAGES_HTTP_DIR.'images/'.tep_db_output($row['images_file_name']);?>')">
	<img src="<?php echo IMAGES_HTTP_DIR.'images/'.$image_sm;?>" alt="<?php echo tep_db_output($row['images_name']); ?>" border="0" align="absmiddle" <?php echo getimgHW3hw(IMAGES_DIR.'images/'.$image_sm,SM_WIDTH,SM_HEIGHT);?> />	</a>	</div>
	
<?php
/*载入前3条评论，每条一行*/
if($row['reviews_total']){
	$sql_reviews = tep_db_query("select * from `reviews` where  reviews_type='images' AND reviews_type_id='".$row['images_id']."' order by reviews_id DESC limit 3; ");
	$rows_reviews =  tep_db_fetch_array($sql_reviews);
	if($rows_reviews['reviews_id']){
		do{
?>	
<div class="reviews_list0"><?php echo cutword(tep_db_output($rows_reviews['reviews_content']),24) ?></div>	
<?php 
		}while($rows_reviews =  tep_db_fetch_array($sql_reviews));
	}
}
?>	</td>
    <td width="150" align="left" valign="top"><strong><?php echo Name?>：</strong><?php echo tep_db_output($row['images_name']); ?><br />
      <strong><?php echo Description?>：</strong><?php echo nl2br(tep_db_output($row['images_description'])); ?><br />
      <strong><?php echo Person?>：</strong><?php echo tep_db_output($row['images_upload_people']); ?><br />
      <strong><?php echo DATE?>：</strong><?php echo preg_replace('/ .*$/','',$row['images_date']); ?><br />
      <strong><?php echo Category?>：</strong><?php echo tep_db_output(get_image_group_name($row['group_id'])); ?><br />
	  
	  
	  
	  <div id="Layer_look_reviews_<?php echo $row['images_id'] ?>" class="Layer_none">
		</div>
	  
	  
	  
	  <div id="Layer_reviews_<?php echo $row['images_id'] ?>" class="Layer_reviews_none">
	  	<form style="margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px;" action="" method="get" name="FormWriteReviews<?php echo $row['images_id'] ?>" onsubmit="submit_reviews('FormWriteReviews<?php echo $row['images_id'] ?>', 'Layer_reviews_<?php echo $row['images_id'] ?>'); return false;" id="FormWriteReviews<?php echo $row['images_id'] ?>">
		<fieldset>
		<legend align="left"><img onclick="move_action('Layer_reviews_<?php echo $row['images_id'] ?>')" style="margin-right: 5px;" src="move.gif" alt="移动" width="25" height="21" />给 <?php echo tep_db_output($row['images_name']); ?> 评论</legend>
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
		    <td align="left"><input name="Submit_<?php echo $row['images_id'] ?>" type="submit" id="Submit_<?php echo $row['images_id'] ?>" value="<?php echo SUBMIT?>" />
		      <input name="Submit4" type="button" onclick="document.getElementById('Layer_reviews_<?php echo $row['images_id'] ?>').className='Layer_reviews_none'" value="<?php echo CLOSE?>" />
		      <input name="reviews_type_id" type="hidden" id="reviews_type_id" value="<?php echo tep_db_output($row['images_id']); ?>" />
		      <input name="reviews_type" type="hidden" id="reviews_type" value="images" /></td>
		    </tr>
		  <tr>
		    <td align="right">&nbsp;</td>
		    <td align="left">&nbsp;</td>
		    </tr>
		</table>
		</fieldset>
		</form>
		</div>
		
		
		<div id="re_msn_<?php echo $row['images_id']?>" style="display:<?php if(!$row['reviews_total']){?>none<?php }?>"><strong><?php echo COMMENT?>：</strong>
		<?php printf(HOW_HAVE_COMMENT,'re_total_'.$row['images_id'], (int)$row['reviews_total'])?>
		<input onclick="JavaScript:LookReviews('Layer_look_reviews_<?php echo $row['images_id'] ?>','<?php echo $row['images_id'] ?>')" class="link_buttom" name="" type="button" value="<?php echo VIEW_COMMENT?>" /></div>
		
	    <input name="Submit4" type="button" class="link_buttom" onclick="document.getElementById('Layer_reviews_<?php echo $row['images_id'] ?>').className='Layer_reviews'" value="<?php echo SEND_COMMENT?>" />		</td>

  
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
