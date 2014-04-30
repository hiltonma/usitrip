<?php 
require('includes/application_top.php'); 

$message = '';
$error = false;
if($_POST['action']=='upload'){
	//设置最终的目录
	$_POST['categories_id']="";
	for($i=0; $i<3; $i++){
		if((int)$_POST['categories_id'.$i]){
			$_POST['categories_id'] = $_POST['categories_id'.$i];
		}
	}
	//上传图片，检查大小
	if($_FILES['image_file']['name']!=""){
		//echo "yes have images.";
		//$pic_name = date('YmdHis');
		$pic_name = preg_replace('/\.\S+$/','',$_FILES['image_file']['name']);
		$pic_name = preg_replace('/\'|\"/','',$pic_name);
		$pic_name = preg_replace('/ +/','_',$pic_name);
		$pic_name = preg_replace('/　+/','_',$pic_name);
		
		$tmp = false;
		$products_file_name_sm="";
		$_POST['products_name'] = $pic_name;
		if(strlen(trim($_POST['products_name'])) < 1){
			$_POST['products_name'] = $_POST['products_NO'];
		}

		if(!tep_not_null($_POST['products_NO'])){
			$error = true;
			$message .= '<div class="warning">请填写产品编号。</div>';
		}
		if(!tep_not_null($_POST['products_upload_people'])){
			$error = true;
			$message .= '<div class="warning">请填写您的姓名。</div>';
		}else{
			$products_upload_people = $_SESSION['products_upload_people'] = $_POST['products_upload_people'];
		}
		if($_POST['categories_id']<1){
			$error = true;
			$message .= '<div class="warning">请选择产品类别。</div>';
		}
		if($error==false){	//规定产品编号和名称都不能与数据库中的相同
			$sql = tep_db_query('SELECT *  FROM `products` where products_NO="'.tep_db_prepare_input($_POST['products_NO']).'" || products_name="'.tep_db_prepare_input($_POST['products_name']).'" limit 1;');	
			$row = tep_db_fetch_array($sql);
			if($row['products_id']){
				$error = true;
				if($row['products_NO']==tep_db_prepare_input($_POST['products_NO'])){
					$message .= '<div class="warning">产品编号重复。</div>';
				}
				if($row['products_name']==tep_db_prepare_input($_POST['products_name'])){
					$message .= '<div class="warning">产品名称重复。</div>';
				}
			}

		}


		if($error==false){
			$tmp = up_file('gif,jpg,jpeg,png', 1024*1024, IMAGES_DIR.'products/' , $pic_name ,'image_file','Y');
			if($tmp!=false){
				$pic_name = $tmp;
				$input_file = IMAGES_DIR.'products/'.$pic_name;
				$out_file = IMAGES_DIR.'products/'.preg_replace("/^(.*)\./","$1_sm.",$pic_name);
				$max_width = SM_WIDTH;
				$max_height = SM_HEIGHT;
				//生成缩略图
				if(out_thumbnails($input_file, $out_file, $max_width, $max_height)){
					$products_file_name_sm = basename($out_file);
				}
				//echo $pic_name;
			}else{
				$error = true;
				$message .= '<div class="warning">上传失败，请检查你的图片不能大于1M，类型必须是gif,jpg,jpeg,png的有效图片。</div>';
			}
		}
		
		
		if($error == false){
			$sql_data_array = array('products_file_name' => tep_db_prepare_input($pic_name),
									'products_file_name_sm' => tep_db_prepare_input($products_file_name_sm),
									'products_NO' => tep_db_prepare_input($_POST['products_NO']),
									'products_name' => tep_db_prepare_input($_POST['products_name']),
									'products_date' => date('Y-m-d H:i:s'),
									'products_upload_people' => tep_db_prepare_input($_POST['products_upload_people']),
									'categories_id' => (int)$_POST['categories_id'],
									'products_scope' => (int)$_POST['products_scope']
									);
			tep_db_perform('products', $sql_data_array);
			header('Location: products.php');
			exit;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>上传产品</title>
<base href="<?php echo WEB_DIR?>" />
<link href="style.css" rel="stylesheet" type="text/css" />
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
	window.alert("<?php echo ('不能创建XMLHttpRequest对象实例.')?>");
}
</script>

<script type="text/JavaScript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function CheckSubmit(){
	if(document.getElementById('categories_id0').value < 1){
		alert('请至少选择一个类别!');
		return false;
	}else{
		document.getElementById('form1').submit();
	}
}
//-->
</script>

<?php require(PATH_DIR.'sel_categories.php'); ?>

</head>

<body>
<fieldset>
<legend align="left"><span class="STYLE1">上传景点图片</span></legend>

<table border="0" cellspacing="0" cellpadding="0">
  
  <tr>
    <td><form action="" method="post" enctype="multipart/form-data" onsubmit="CheckSubmit(); return false" name="form1" id="form1">
      <table border="0" cellspacing="2" cellpadding="0">
        <tr>
          <td align="right">&nbsp;</td>
          <td align="left"><?php echo $message ?></td>
        </tr>

		<tr>
          <td align="right">图片性质：</td>
          <td align="left">
		  <?php if(!tep_not_null($products_scope) || (int)$products_scope==0){ $products_scope=1; }?>
		  <label><?php echo tep_draw_radio_field('products_scope', '1', '')?>行程</label>
		  <label><?php echo tep_draw_radio_field('products_scope', '2', '')?>组图</label>
		  <label><?php echo tep_draw_radio_field('products_scope', '99', '')?>其它</label>
		  
		  </td>
		</tr>
		
		<tr>
          <td align="right">类别：</td>
          <td align="left">
<?php
	$sql = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE parent_id =0 AND c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	
	$row = tep_db_fetch_array($sql);
	$state_select="";
	$option_array = array();
	$option_array[0]['id']='0';
	$option_array[0]['text']='--选择类别--';
	$do=1;
	do{
		$option_array[$do]['id'] = $row['categories_id'];
		$option_array[$do]['text'] = $row['categories_name'];
		$do++;
	}while( $row = tep_db_fetch_array($sql));
	echo '<div id="categories_0">'.tep_draw_pull_down_menu('categories_id0', $option_array,'',' onchange="SelChange1(categories_id0);SelChange1(categories_id1);" ').'</div>';
	
	if((int)$categories_id0){
		$sql = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE parent_id ='.(int)$categories_id0.' AND c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	

		$state_select="";
		$option_array = array();
		$option_array[0]['id']='';
		$option_array[0]['text']='--选择类别--';
		$do=1;
		while( $row = tep_db_fetch_array($sql)){
			$option_array[$do]['id'] = $row['categories_id'];
			$option_array[$do]['text'] = $row['categories_name'];
			$do++;
		}
		if(count($option_array)>1){
			echo '<div id="categories_1">'.tep_draw_pull_down_menu('categories_id1', $option_array,'',' onchange="SelChange1(categories_id1);SelChange1(categories_id2);" ').'</div>';
		}else{
			echo '<div id="categories_1"><select style="display:none" name="categories_id1" id="categories_id1" onchange="SelChange1(categories_id1);SelChange1(categories_id2);"></select></div>';
		}
	}else{
		echo '<div id="categories_1"><select style="display:none" name="categories_id1" id="categories_id1" onchange="SelChange1(categories_id1);SelChange1(categories_id2);"></select></div>';
	}

	if((int)$categories_id1){
		$sql = tep_db_query('SELECT *  FROM `categories` c, `categories_description` cd WHERE parent_id ='.(int)$categories_id1.' AND c.categories_id=cd.categories_id Group By c.categories_id ORDER BY c.categories_id ASC');	

		$state_select="";
		$option_array = array();
		$option_array[0]['id']='0';
		$option_array[0]['text']='--选择类别--';
		$do=1;
		while( $row = tep_db_fetch_array($sql)){
			$option_array[$do]['id'] = $row['categories_id'];
			$option_array[$do]['text'] = $row['categories_name'];
			$do++;
		}
		if(count($option_array)>1){
			echo '<div id="categories_2">'.tep_draw_pull_down_menu('categories_id2', $option_array).'</div>';
		}else{
			echo '<div id="categories_1"><select style="display:none" name="categories_id2" id="categories_id2"></select></div>';
		}
	}else{
		echo '<div id="categories_1"><select style="display:none" name="categories_id2" id="categories_id2"></select></div>';
	}
?>			</td>
        </tr>
        <tr>
          <td align="right">编号：</td>
          <td align="left">
		  <?php
		  if(!tep_not_null($products_NO)){
		  	$products_NO = date('YmdHis');
		  }
		  echo tep_draw_input_field('products_NO', '', ' size="30" ')
		  ?>
            <span class="warning">*</span></td>
        </tr>
        
        <tr>
          <td align="right">名称：</td>
          <td align="left">
		  <?php
		  if(!tep_not_null($products_name)){
		  	$products_name = $products_NO;
		  }
		  echo tep_draw_input_field('products_name', '', ' size="30" ')
		  ?>
		  <?php //echo tep_draw_textarea_field('products_name', 'virtual', 30, 5) ?>		  </td>
        </tr>
		<tr>
          <td align="right">图片：</td>
          <td align="left"><input name="image_file" type="file" id="image_file" onchange="show_image.src=this.value" size="30" /></td>
        </tr>
		<tr>
          <td align="right"><?php echo YOUR_NAME?>：</td>
          <td align="left">
		  <?php
		  if(!tep_not_null($products_upload_people)){
		  	$products_upload_people = '系统管理员';
		  }
		  echo tep_draw_input_field('products_upload_people', '', ' size="20" ')
		  ?>
            <span class="warning">*</span></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="left"><input type="submit" name="Submit" value="提交" />
            <input name="Submit2" type="button" onclick="MM_goToURL('parent','products.php');return document.MM_returnValue" value="返回景点库" />
            <input name="action" type="hidden" id="action" value="upload" /></td>
        </tr>
      </table>
        </form>    </td>
    <td><img id="show_image" src="images/boy.gif" width="144" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</fieldset>
</body>
</html>
