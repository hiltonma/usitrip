<?php
   // Edit upload location here
   require_once("includes/application_top.php");

   //$destination_path = getcwd().DIRECTORY_SEPARATOR;
   $result = 0;
   $tmp_fold = $_SESSION['prod_temp'];
   
   if(!file_exists(DIR_FS_IMAGES.'reviews/temp'))
	{
		mkdir(DIR_WS_IMAGES.'reviews/temp', 0777);
	}
   if(!file_exists(DIR_FS_IMAGES.'reviews/temp/'.$tmp_fold))
	{
		mkdir(DIR_WS_IMAGES.'reviews/temp/'.$tmp_fold, 0777);
	}
	
	$temp_dir_display_path = DIR_WS_IMAGES."reviews/temp/".$tmp_fold."/";
	
	$temp_dir_path = DIR_WS_IMAGES."reviews/temp/".$tmp_fold."/";
	
   
$image_title = '';
$image_description = '';
  for($i=0; $i<$_POST['total_imgs'] ; $i++){
	if(basename( $_FILES['image_file']['name'][$i])!='')
	{
		//$fname = $temp_dir_path.basename( $_FILES['image_file']['name'][$i]);
		$microtime = microtime(true);	//need use microtime witch file name.
		$expn = preg_replace('/.*\./','',basename( $_FILES['image_file']['name'][$i]));
		
		$fname = $temp_dir_path.$microtime.'.'.$expn;
		$image_title .= tep_db_prepare_input($_POST['image_title'][$i]).'/--title--/';
		$image_description .= tep_db_prepare_input($_POST['image_description'][$i]).'/--desc--/';
		
	  //$target_path = $destination_path . basename( $_FILES['image_file']['name'][$i]);
	   if(@move_uploaded_file($_FILES['image_file']['tmp_name'][$i], $fname)) {
		  $result = 1;
		  
	   }
	   
	   //check images
		$img_info = @getimagesize($fname);
		if($img_info[0] < 1 || $img_info[1] < 1){
			if(@unlink($fname)) {
				$result = 0;
				$imgmax = db_to_html("对不起，你上传的图片无法显示，请上传有效的图片!"); 
				echo "<script>alert('$imgmax')</script>"; 
				//return false;
			}			
		}

		//rw--r--r-- set 0644
		//@chmod($fname,0644);
	
	}else{
		$result = 0;
		$imgmax = db_to_html("对不起，您无上传图片，请上传有效的图片!"); 
		echo "<script>alert('$imgmax')</script>";
		exit;
	}

 }
  
   sleep(1);
$image_title =   preg_replace('/[[:space:]]+/',' ',addslashes($image_title));
$image_description =   preg_replace('/[[:space:]]+/',' ',addslashes($image_description));

?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>,"<?php echo $image_title; ?>","<?php echo $image_description; ?>");</script>   
