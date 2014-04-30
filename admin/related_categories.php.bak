<?php
/*
  $Id: categories.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
 

if($HTTP_GET_VARS['action'] == 'new_links'){
		 $products_id = $_GET['products_id'];
$append_msg='';
	/*if(isset($_POST['cat_check_array']))		
	{*/
		$exist_cat_ids = $_POST['current_cat_id'].',';
		foreach ($_POST['cat_check_array'] as $key=>$val){
			$categories_id_split = explode('!!###!!',$val);
			//check if exist in table or new record
			$exist_cat_ids .= $categories_id_split[0].',';
			$exist_query = tep_db_query("select categories_id from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id='".$products_id."' and categories_id='".$categories_id_split[0]."'");
			if(tep_db_num_rows($exist_query)==0)
			{
				$sql_data_array_locations = array(		   
											'products_id' => $products_id,
											'categories_id' => $categories_id_split[0]
											);
									  
				
				tep_db_perform(TABLE_PRODUCTS_TO_CATEGORIES, $sql_data_array_locations);													
			}
		}
		$exist_ids = substr($exist_cat_ids,0,-1);
		tep_db_query("delete from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id='".$products_id."' and categories_id not in(".$exist_ids.")");
	//}
				$messageStack->add_session('Selected categories has been linked to your product.<br />'.$append_msg, 'success');	
				
				?>
				<script language="javascript">
					window.close();
					//window.opener.location.reload(true);
					//var url = window.opener.location.href+'&msg=notmoved';
					var url = window.opener.location.href;
					window.opener.location.href = url;
				</script>
				<?php

			
				//make redirect call and session msg  
	//}		  
}
$go_back_to=$REQUEST_URI;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript"  src="datetimepicker.js"></script>
<script type="text/javascript" src="includes/javascript/categories.js"></script>
<?php 
require('includes/javascript/categories.js.php'); ?>

<?php
// WebMakers.com Added: Java Scripts - popup window
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              
			  
			
            </table></td>
          </tr>
		  
		  <?php
	  //like queries of related categories
	  $products_id = $_GET['products_id'];
	  $where = "";
	  $other_where = "";
	  $where .= " and (cd.categories_name=''";
	  $other_where .= " and (cd.categories_name!=''";
	  
	  //tour startcity like category name query
	  $products_startcity_query = tep_db_query("select p.departure_city_id,c.city from ".TABLE_PRODUCTS." p, ".TABLE_TOUR_CITY." c where c.city_id = p.departure_city_id and p.products_id=".$products_id);
	  $prod_start_city = tep_db_fetch_array($products_startcity_query);
	  
	  $where .= " or cd.categories_name like '%".addslashes($prod_start_city['city'])."%'";
	  
//===========//
	  
	  //tour endcity like category name query
	  $products_endcity_query = tep_db_query("select departure_end_city_id from ".TABLE_PRODUCTS." where products_id=".$products_id."");
	  $prod_end_city = tep_db_fetch_array($products_endcity_query);
	  $end_cities = $prod_end_city['departure_end_city_id'];
	  if($end_cities!='')
	  {
		  $end_cities_id = explode(',',$end_cities);
		  $n = sizeof($end_cities_id);
		  //echo $end_cities_id[0];
		  if($n>0)
		  {
			  for($i=0;$i<$n;$i++)
			  {
				$city_name_query = tep_db_query("select city,city_id from ".TABLE_TOUR_CITY." where city_id=".$end_cities_id[$i]);
				$city_name = tep_db_fetch_array($city_name_query);
				
				$where .= " or cd.categories_name like '%".addslashes($city_name['city'])."%'";
			  }
		  }
	  
	  }
	  
	  
	   //tour Attractions(destination city) like category name query
	   $product_attractions_query = tep_db_query("select city_id from ".TABLE_PRODUCTS_DESTINATION." where products_id=".$products_id);
	   if(tep_db_num_rows($product_attractions_query)>0)
	   {
	   while($row_attractions = tep_db_fetch_array($product_attractions_query))
	   {
	   		$att_city_name_query = tep_db_query("select city from ".TABLE_TOUR_CITY." where city_id=".$row_attractions['city_id']);
			$att_city_name = tep_db_fetch_array($att_city_name_query);
			
			$where .= " or cd.categories_name like '%".addslashes($att_city_name['city'])."%'";
	   }
	  }
	  
//==========//

	  //already linked categories query
	  $cat_checked_query = tep_db_query("select categories_id from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id=".$_GET['products_id']."");
	  while($row_cat_checked = tep_db_fetch_array($cat_checked_query))
	  {
		if($row_cat_checked['categories_id']>0)
		{
			$checked_categories .= $row_cat_checked['categories_id'].',';
		}
	  }
	  $checked_categories_list = substr($checked_categories,0,-1);
	  if($checked_categories_list!='')
	  {
	  	$where .= " or c.categories_id in(".$checked_categories_list.")";
	  }
	  
	  $where.=")";

//===========================// where queries end	  
	  
	  //echo "select c.categories_status,c.categories_feature_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' ".$where." order by c.sort_order, cd.categories_name";

	 
	  $categories_query = tep_db_query("select c.categories_status,c.categories_feature_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' ".$where." order by c.sort_order, cd.categories_name");// c.parent_id = '" . (int)$current_category_id . "' and
if(tep_db_num_rows($categories_query)>0)
{
    while ($categories = tep_db_fetch_array($categories_query)) 
	{
		$cat_checked_query = "select categories_id from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id=".$_GET['products_id']." and categories_id=".$categories['categories_id']."";
		$res_cat_checked = tep_db_query($cat_checked_query);
		if(tep_db_num_rows($res_cat_checked)>0)
		{
			//$checked = ' checked="checked"';
			$existing_cat_ids[] = $categories['categories_id'];
			$existing_categoires[] = array('id' => $categories['categories_id'],
									   'text' => $categories['categories_name']);
		}
		else
		{
		//chack if the category is a leaf category then only listed here
		//$check_leaf_query = tep_db_query("select parent_id from ".TABLE_CATEGORIES." where parent_id=".$categories['categories_id']);
			if(is_leaf_category($categories['categories_id'])==1)
			{
			//$checked = '';
				$suggested_categoires[] = array('id' => $categories['categories_id'],
									   			'text' => $categories['categories_name']);
			}
		}
		
	}
	
	
	
?>
	  <tr><td height="10"></td></tr>
	  <tr>
		  	<td>
			<?php   echo tep_draw_form('category_checked', FILENAME_RELATED_CATEGORIES, 'products_id=' . $HTTP_GET_VARS['products_id'] . '&action=new_links', 'post'); 
	 ?>
				<table width="100%" align="center" cellpadding="1" cellspacing="3" border="0">
				  <tr>
				  
				  <td class="dataTableContent" width="30%"><b>All Categories</b></td>
				  <td width="5%"></td>
				  <?php	if(sizeof($suggested_categoires)>0){ ?><td class="dataTableContent" width="30%"><b>Suggested Categories</b></td><?php } ?>
				  </tr>
				  <tr>
										
										<td>
<?php
  /*$info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CATEGORIES
                              );*/
 // new infoBoxHeading($info_box_contents, true, false);

    $number_top_levels = 0;
    $categories_string='';
    $number_top_levels = build_menus(0,'','',0);


    $currentCPath = $HTTP_GET_VARS['cat_id'];
    if (! isset($currentCPath)) {
        if (isset($HTTP_GET_VARS['products_id'])) {
            $cPathQuery = tep_db_query("select c.categories_id, c.parent_id  from " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p2c.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and c.categories_id = p2c.categories_id");
            if ($cp = tep_db_fetch_array($cPathQuery))  {
                $currentCPath = $cp['parent_id'] . "_" . $cp['categories_id'];
            }
        }
    }

    echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/javascript/jscooktree/JSCookTree.js\"></SCRIPT>\n";
    echo "<LINK REL=\"stylesheet\" HREF=\"includes/javascript/jscooktree/ThemeXP/theme.css\" TYPE=\"text/css\">\n";
    echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/javascript/jscooktree/ThemeXP/theme.js\"></SCRIPT>\n";

?>
<?php

   $tabletext .= "<div id=\"myID\"></div>\n";
   $tabletext .= "<script type=\"text/javascript\"><!--
        var catTree =
        [\n";
   $tabletext .= $categories_string;
   $tabletext .= "];
     --></script>\n";

    /*$info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $tabletext);
    new infoBox($info_box_contents);*/
	
	print_r ($tabletext);

    echo "<script type=\"text/javascript\"><!--\n";
    echo "    var treeIndex = ctDraw ('myID', catTree, ctThemeXP1, 'ThemeXP', 0, 0);\n";
    if (isset($currentCPath)) {
     foreach($existing_cat_ids as $key=>$val)   {
		echo "    var treeItem = ctExposeItem (0, '" . $val . "'); \n";
        echo "    ctOpenFolder (treeItem);\n";
	}
    }
    echo "--></script>\n";

?>
           </td>
		   <?php
		   	if(sizeof($suggested_categoires)>0){
		   ?>
		   <td align="center" valign="top" style="padding-top:50px;"><!--<INPUT onClick="moveOptionstree(this.form.elements['cat_check_array[]'],this.form.elements['category_existing_array[]']);" type=button value="->"><BR>--><INPUT onClick="moveOptionsRight(this.form.elements['category_suggested_array[]'],this.form.elements['cat_check_array[]']);" type=button value="<--"></td>
										<td align="left" valign="top"><?php echo  tep_draw_pull_down_menu('category_suggested_array[]', $suggested_categoires, '',' id="category_suggested_array[]" multiple="multiple" size="15" style="width:280px;"'); ?></td>
			<?php
			 }
			?>						
		  </tr>
		   <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          
	<?php
										echo tep_draw_hidden_field('current_cat_id',$_GET['cat_id']);
										?>		  

<tr class="dataTableHeadingRow">
  <td class="dataTableHeadingContent"  align="center"><input type="submit" name="submit" value="Save" onClick="return check_confirm();">&nbsp;<input type="button" name="back" value="Cancel" onClick="window.close();" > </td>					
</tr>
<?php
}
else
{
	?>
		<tr><td colspan="2" align="center" class="dataTableContent"><b>No any related Category found!!!</b></td></tr>
		<tr class="dataTableHeadingRow">
		  <td class="dataTableHeadingContent" ><input type="button" name="back" value="Cancel" onClick="window.close();" > </td>					
		</tr>
	<?php
}
?>
</form>
				 
				</table>
			</td>
		  </tr>
        </table></td>
      </tr>
     

              
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>

<script><!--
function check_confirm()
{
	if(confirm('Are you sure you want to link selected categories for this product?'))
	{
		
		/*if(document.getElementById('category_existing_array[]').options.length == 0 ){
		alert("Please select at least one category.");
		return false;
		}	
		if(document.getElementById('category_existing_array[]').options.length > 0 ){
		selectAllOptions('category_existing_array[]');
		}*/
		
		return true;
	}
	else
	{
		return false;
	}
}


function selectAllOptions(selStr)
{
  var selObj = document.getElementById(selStr);
  for (var i=0; i<selObj.options.length; i++) {
    selObj.options[i].selected = true;
  }
}

//--></script>

<?php 
function build_menus($currentParID,$menustr,$catstr, $indent) {
//echo $currentCPath;
    global $categories_string, $id, $languages_id;
    $tmpCount;

    $tmpCount = 0;
    $haschildren = 0; //default

    $categories_query_catmenu = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $currentParID . "' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
    $numberOfRows = tep_db_num_rows($categories_query_catmenu);
    $currentRow = 0;
    while ($categories = tep_db_fetch_array($categories_query_catmenu))  {
        $currentRow ++;
        $catName = addslashes($categories['categories_name']);
        $tmpCount += 1;
        $haschildren = tep_has_category_subcategories_tow($categories['categories_id']);

        if (SHOW_COUNTS == 'true') {
            $products_in_category = tep_count_products_in_category($categories['categories_id']);
            if ($products_in_category > 0) {
                $catName .= ' (' . $products_in_category . ')';
            }
        }

        if($catstr != ''){
            $cPath_new = 'cPath=' . $catstr . '_' . $categories['categories_id'];
        } else {
            $indent = 0;
            $cPath_new = 'cPath=' . $categories['categories_id'];
        }

        if($menustr != ''){
            $menu_tmp = $menustr . '_' . $tmpCount;
        } else {
            $menu_tmp = $tmpCount;
        }

        $indentStr="";
        for($i=0; $i<$indent; $i++) {
            $indentStr .= "   ";
        }

	if ($haschildren) {		
		 $categories_string .=  $indentStr . "[null, '" . $catName ."','".$categories['categories_id']."','_self','". $tmpString ."'";
		
		}else{

$cat_checked_query = "select categories_id from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id=".$_GET['products_id']." and categories_id=".$categories['categories_id']."";
		if(tep_db_num_rows(tep_db_query($cat_checked_query))>0){
			$checked = ' checked="checked"';
		}
		else
		{
			$checked = '';
		}
        $categories_string .=  $indentStr . "['<input type=checkbox name=cat_check_array[] value=\"".$categories['categories_id'].'!!###!!'.$catName."\" ".$checked.">', '" . $catName ."','".$categories['categories_id']."','_self','". $tmpString ."'";
		
		}
        if ($haschildren) {
            $indent += 1;
            $categories_string .= ",\n";
            if($menustr != ''){
                $menu_tmp = $menustr . '_' . $tmpCount;
            } else {
                $menu_tmp = $tmpCount;
            }
            if($catstr != ''){
                $cat_tmp = $catstr . '_' . $categories['categories_id'];
            } else {
                $cat_tmp = $categories['categories_id'];
            }
            $NumChildren = build_menus($categories['categories_id'], $menu_tmp, $cat_tmp, $indent);
            if ($currentRow < $numberOfRows) {
                $categories_string .= $indentStr . "],\n";
            } else {
                $categories_string .= $indentStr . "]\n";
            }
        } else {
            if ($currentRow < $numberOfRows) {
                $categories_string .= "],\n";
            } else {
                $categories_string .= "]\n";
            }
            $NumChildren = 0;
        }
    }
    return $tmpCount;
}

require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<br>
</body>
</html>