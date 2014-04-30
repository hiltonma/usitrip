<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('tour_code_decode');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
if(isset($_GET['action'])&&$_GET['action']=='process')
  {
 		$aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	 
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));   
			//echo "$key=>$value2<br>";  	   
		  }
		}
		
		if($HTTP_POST_VARS['tour_code'] != ''){
		echo "Decoded Tour Code: <font color='#FF0000'><b>".tour_code_decode($HTTP_POST_VARS['tour_code'].'</b><font>');
		}else{
		echo "Please enter encoded tour code";
		}
		
	exit;
	}		

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<style type="text/css">
.highlight_word{
    background-color: pink;
}
</style> 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('tour_code_decode');
$list = $listrs->showRemark();
?>
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right" class="main"><!--Filter by:-->
			<?php  /*
			echo tep_draw_form('frmtourcode',FILENAME_TOUR_CODE_DECODE,'','','id="frmtourcode"'); 
			?>
			
			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="main" nowrap="nowrap">Enter Encoded Tour Code:</td>
				<td><?php echo tep_draw_input_field('tour_code','');?></td>
				<td><input type="hidden" name="sendajax" value="ptest">
 			
			<input type="button" onClick="sendFormData('frmtourcode','tour_code_decode.php?action=process','responsediv','true');" name="Decode" value="Decode">
 			</td>
			  </tr>
			  <tr>
				<td colspan="3" class="main"><div id="responsediv"></div></td>
			  </tr>
			</table>

			<?php
			echo '</form>';
			*/
			?>			
			</td>
          </tr>
		  
		  <tr><td></td><td align="right" class="main">	<?php 
			echo tep_draw_form('frmtourcodesearch',FILENAME_TOUR_CODE_DECODE,'','get'); 
			?>
			
			<table border="0" cellspacing="2" cellpadding="2">
			  <tr>
				 <td  class="smallText"><?php echo 'Provider:'; ?></td>
				 <td class="smallText"><?php
					 
					 $provider_array = array(array('id' => '', 'text' => TEXT_NONE));
					 $provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
					  while($provider_result = tep_db_fetch_array($provider_query))
					  {
					  $provider_array[] = array('id' => $provider_result['agency_id'],
												 'text' => $provider_result['agency_name']);
					  }
					 echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px; " '); ?>
					</td>
				</tr>
			  <tr>
				<td class="smallText" nowrap="nowrap">Search Tour Code:</td>
				<td><?php echo tep_draw_input_field('search');?></td>				
			  </tr>	
			  <tr>
				<td class="smallText" nowrap="nowrap">&nbsp;</td>				
				<td> 			
				<input type="submit" name="Serbtn" value="Search">
				</td>
			  </tr>			 
			</table>

			<?php
			echo '</form>';
			?></td></tr>
		  </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="6" cellpadding="0">
          <tr>
            <td valign="top">
			
			<table border="0" width="100%" cellspacing="0" cellpadding="5">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="50%">
				<?php
				  $HEADING_TORUNAME = 'Tour Name';
				  $HEADING_TORUNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourname&order=ascending'.(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '').'">';
				  $HEADING_TORUNAME .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				  $HEADING_TORUNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourname&order=decending'.(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '').'">';
				  $HEADING_TORUNAME .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
				  echo $HEADING_TORUNAME;
				  ?>
				</td>
                <td class="dataTableHeadingContent" width="25%">
				<?php
				  //$HEADING_TORUCODE = 'Tour Code';
				  $HEADING_TORUCODE = 'System Code';
				  $HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcode&order=ascending'.(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '').'">';
				  $HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				  $HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcode&order=decending'.(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '').'">';
				  $HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
				  echo $HEADING_TORUCODE;
				  ?>
				</td>
                <td class="dataTableHeadingContent" width="25%">
				<?php
				  //$HEADING_TORUCODE = 'Encoded Tour Code';
				  $HEADING_TORUCODE = 'Provider Code';
				  $HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcodeencode&order=ascending'.(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '').'">';
				  $HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				  $HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcodeencode&order=decending'.(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '').'">';
				  $HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
				  echo $HEADING_TORUCODE;
				  ?>
				</td>
              </tr>
<?php
 
  $extra_search_query = '';
   if (isset($HTTP_GET_VARS['search']) && $HTTP_GET_VARS['search'] != '') {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);	 	  
	  $extra_search_query = "and (pd.products_name like '%" . tep_db_input($search) . "%'  or p.products_model like '%" . tep_db_input($search) . "%'or p.provider_tour_code like '%" . tep_db_input($search) . "%'  or p.provider_tour_code like '%" . tour_code_decode(tep_db_input($search)) . "%')"; 
   }  
 
 if(isset($_GET['provider']) && $_GET['provider'] != '') {
		$extra_search_query .= " and p.agency_id='".$_GET['provider']."'";
	}
  $sortorder = 'order by products_model';
  
 
  $select_products_query = "SELECT pd.products_name, p.products_id, p.products_model, p.provider_tour_code FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where  p.products_id=pd.products_id and  pd.language_id = '" . (int)$languages_id . "' and p.agency_id!='".GLOBUS_AGENCY_ID."' and pd.products_name != '' ".$extra_search_query."  ".$sortorder."  ";
  $select_products_row = tep_db_query($select_products_query);
 
 
  while($products = tep_db_fetch_array($select_products_row)){     
  $data[] = array(
				'products_id' => tep_db_input($products['products_id']), 
				'products_name' => tep_db_input($products['products_name']), 
				'products_model' => tep_db_input($products['products_model']), 
				'products_model_encoded' => tep_db_input($products['provider_tour_code'])
				);
  }
 
	
 if(is_array($data)) { 
 
 foreach ($data as $key => $row) {
    $array_products_id[$key]  = $row['products_id'];
    $array_products_name[$key] = $row['products_name'];
    $array_products_model[$key] = $row['products_model'];
    $array_products_model_encoded[$key] = $row['products_model_encoded'];
  }
  
	if($_GET["order"]=='decending') {
		  $sortorder_direction = SORT_DESC;
	} else {
		  $sortorder_direction = SORT_ASC;
	}
  $array_by_sort_name = $array_products_model;  
  switch ($_GET["sort"]) {
      case 'tourname':       
		 array_multisort(array_map('strtolower',$array_products_name), $sortorder_direction, SORT_STRING, $data);
 	  break;
      case 'tourcode':       
		 array_multisort(array_map('strtolower',$array_products_model), $sortorder_direction, SORT_STRING, $data);
       break;
	   case 'tourcodeencode':       
		 array_multisort(array_map('strtolower',$array_products_model_encoded), $sortorder_direction, SORT_STRING, $data);
       break;
      default:        
      	 array_multisort(array_map('strtolower',$array_products_model), $sortorder_direction, SORT_STRING, $data);
      break;
    } 
 
 
 	$iic = 0;
 	foreach($data as $key => $val){
	
		
				  if($iic%2 ==0){
				  $calss_row = '';
				  }else{
				  $calss_row = 'class="dataTableRow"';
				  }
				?>
								<tr <?php echo $calss_row;?>>
								<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $data[$key]['products_id']) . '">' . highlightWords(tep_db_prepare_input($data[$key]['products_name']),$search).'</a>';?>
								</td>
								<td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($data[$key]['products_id']) . '&pID=' . $data[$key]['products_id'].'&action=new_product') . '">' . highlightWords($data[$key]['products_model'],$search) . '</a>' ?>
								</td>
								<td class="dataTableContent">
								<?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($data[$key]['products_id']) . '&pID=' . $data[$key]['products_id'].'&action=new_product') . '">' .  highlightWords($data[$key]['products_model_encoded'],$search) . '</a>' ?>
								</td>               
							  </tr>
				<?php
				$iic++;
				
  		
	}//foreach
  
  }
 // echo $iic++;
?>
              
            </table></td>

          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

