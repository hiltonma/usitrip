<?php
/*
  $Id: popup_image.php,v 1.1.1.1 2004/03/04 23:38:01 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_POPUP_IMAGE);

  $navigation->remove_current_page();

  $products_query = tep_db_query("select p.products_image,p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4,  pd.products_name from " . TABLE_PRODUCTS . " p,  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $products = tep_db_fetch_array($products_query);
  $countimage = 0;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $products['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="templates/Original/stylesheet.css">
<script language="javascript"><!--
var i=0;
var s=0;
function resize() {
   if (navigator.appName == 'Netscape') i=40;
     if (window.navigator.userAgent.indexOf("SV1") != -1) s=20; //This browser is Internet Explorer in SP2.
      if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i+s);
      self.focus();
    if (document.images[0]) {
    imgHeight = document.images[0].height+120-i;
    imgWidth = document.images[0].width+30;
    var height = screen.height;
    var width = screen.width;
    var leftpos = width / 2 - imgWidth / 2;
    var toppos = height / 2 - imgHeight / 2; 
    window.moveTo(leftpos, toppos);  
    window.resizeTo(imgWidth, imgHeight);
  }
}
//--></script>
<script language="JavaScript">



function preloader() 

{


     // counter
     var i = 0;


     // create object
     imageObj = new Image();


     // set image list
     images = new Array();
	 <?php
		if($products['products_image']  != ''){
		
		
				 if ($products['products_image_med']!='') {
						        
								echo  'images['.$countimage.']="images/'.$products['products_image_med'].'"; ';
								$imagephpstore[$countimage] = '"images/'.$products['products_image_med'].'"';
								$countimage++;
								$first_image = $products['products_image_med'];
								
						  } else {
						        
								echo  'images['.$countimage.']="images/'.$products['products_image'].'"; ';
								$imagephpstore[$countimage] = '"images/'.$products['products_image'].'"';
								$countimage++;
								$first_image = $products['products_image'];
				 		}
		}
	 	if($products['products_image_sm_1']  != ''){
		
		echo  'images['.$countimage.']="images/'.$products['products_image_sm_1'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_sm_1'].'"';
		$countimage++;
		}
		if($products['products_image_xl_1']  != ''){
		
		echo  'images['.$countimage.']="images/'.$products['products_image_xl_1'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_xl_1'].'" ';
		$countimage++;
		}
		if($products['products_image_sm_2']  != ''){
		
		echo  'images['.$countimage.']="images/'.$products['products_image_sm_2'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_sm_2'].'"';
		$countimage++;
		}
		if($products['products_image_xl_2']  != ''){
		
		echo  'images['.$countimage.']="images/'.$products['products_image_xl_2'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_xl_2'].'"';
		$countimage++;
		}
		if($products['products_image_sm_3']  != ''){
		
		echo  'images['.$countimage.']="images/'.$products['products_image_sm_3'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_sm_3'].'"';
		$countimage++;
		}
		if($products['products_image_xl_3']  != ''){
		
		echo  'images['.$countimage.']="'.$products['products_image_xl_3'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_xl_3'].'"';
		$countimage++;
		}
		if($products['products_image_sm_4']  != ''){
		
		echo  'images['.$countimage.']="images/'.$products['products_image_sm_4'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_sm_4'].'"';
		$countimage++;
		}
		if($products['products_image_xl_4']  != ''){		
		echo  'images['.$countimage.']="images/'.$products['products_image_xl_4'].'"; ';
		$imagephpstore[$countimage] = '"images/'.$products['products_image_xl_4'].'"';
		$countimage++;
		}
	 ?>
	
   

     // start preloading
     for(i=0; i<<?php echo $countimage;?>; i++) 
     {
          imageObj.src=images[i];
     }



} 



</script>
<SCRIPT LANGUAGE="JavaScript">

var rotate_delay = 3000; // delay in milliseconds (5000 = 5 secs)
current = 0;
function next() {
if (document.slideform.slide[current+1]) {
document.images.show.src = document.slideform.slide[current+1].value;
document.slideform.slide.selectedIndex = ++current;
   }
else first();
}
function previous() {
if (current-1 >= 0) {
document.images.show.src = document.slideform.slide[current-1].value;
document.slideform.slide.selectedIndex = --current;
   }
else last();
}
function first() {
current = 0;
document.images.show.src = document.slideform.slide[0].value;
document.slideform.slide.selectedIndex = 0;
}
function last() {
current = document.slideform.slide.length-1;
document.images.show.src = document.slideform.slide[current].value;
document.slideform.slide.selectedIndex = current;
}
function ap(text) {
document.slideform.slidebutton.value = (text == "Stop") ? "Start" : "Stop";
rotate();
}
function change() {
current = document.slideform.slide.selectedIndex;
document.images.show.src = document.slideform.slide[current].value;
}
function rotate() {
if (document.slideform.slidebutton.value == "Stop") {
current = (current == document.slideform.slide.length-1) ? 0 : current+1;
document.images.show.src = document.slideform.slide[current].value;
document.slideform.slide.selectedIndex = current;
window.setTimeout("rotate()", rotate_delay);
   }
}


function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

</script>
</head>
<body>



<script language="javascript" type="text/javascript">
/*
normal_image = new Image();
normal_image.src = "images/first1.gif";

mouseover_image = new Image();
mouseover_image.src = "images/first2.gif";

normal_image = new Image();
normal_image.src = "images/previous1.gif";

mouseover_image = new Image();
mouseover_image.src = "images/previous2.gif";

normal_image = new Image();
normal_image.src = "images/next1.gif";

mouseover_image = new Image();
mouseover_image.src = "images/next2.gif";

normal_image = new Image();
normal_image.src = "images/last1.gif";

mouseover_image = new Image();
mouseover_image.src = "images/last2.gif";
*/


function swap(){
if (document.images){
for (var x=0;
x<swap.arguments.length;
x+=2) {
document[swap.arguments[x]].src = eval(swap.arguments[x+1] + ".src");
}
}
}
</script>
<form name=slideform>
<table cellspacing=0 cellpadding=0 width="100%" align="center">
<tr>
								<td ><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
</tr>
<tr  style="display: none">
<td align=center > 
<select name="slide" onChange="change();">
<?php

for ($i1 = 0; $i1 <$countimage; $i1++) {
	if($i1 == 0) {
    echo '<option value='.$imagephpstore[$i1].' selected>';
	}else{
	 echo '<option value='.$imagephpstore[$i1].' >';
	}
}
?>
</select></td>
</tr>
<tr>
<td align=center>
<img src="images/<?php echo $first_image; ?>" name="show" ></td>
</tr>
<tr>
								<td ><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
</tr>
<tr>
<td align=center> 
<a href="javascript:first();"><?php echo IMAGE_TXT_FIRST;?></a> | <a  href="javascript:previous();"><?php echo IMAGE_TXT_PREVIOUS;?></a> | <a href="javascript:next();"><?php echo IMAGE_TXT_NEXT;?></a> | <a  href="javascript:last();"><?php echo IMAGE_TXT_LAST;?></a> | <a href="javascript:;" onclick="javascript:top.window.close();"> <?php echo IMAGE_TXT_CLOSE;?></a>
</table>
</form>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
