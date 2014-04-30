<?php

  require('includes/application_top.php');
 
		 $sql_q_all_check = tep_db_query("SELECT * FROM search_queries");
			while ($sql_q_all_result = tep_db_fetch_array($sql_q_all_check))
			 {
							if(!($sql_q_all_result==''))
							{
							$_GET['update'] = BUTTON_UPDATE_WORD_LIST;
							}
			}
		  
		  


  $years_array = array(); 
  $years_query = tep_db_query("select distinct year(search_date) as search_year from search_date_stored"); 
  while ($years = tep_db_fetch_array($years_query)) { 
  $years_array[] = array('id' => $years['search_year'], 
                           'text' => $years['search_year']); 
  } 

  $months_array = array(); 
  for ($i=1; $i<13; $i++) { 
    $months_array[] = array('id' => $i, 
                            'text' => strftime('%b', mktime(0,0,0,$i,15))); 
  } 

  $type_array = array(array('id' => 'today', 
                            'text' => HEADING_TODAY),
					  array('id' => 'yesterday', 
                            'text' => HEADING_YESTERDAY),
					  array('id' => 'lastsevendays', 
                            'text' => HEADING_ONE_WEEK),
					  array('id' => 'monthtodate', 
                            'text' => HEADING_MONTH_DATE),
					  array('id' => 'lastmonth', 
                            'text' => HEADING_LAST_MONTH),
					  array('id' => 'alltime', 
                            'text' => HEADING_All_TIME) );
							
							
$days_array = array(); 

			  for ($i=1; $i<32; $i++) { 
				$days_array[] = array('id' => $i, 
                            'text' => $i); 
                                     }
 	
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1"  class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->

</table></td>
<!-- body_text //-->
    <td valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td colspan="2" class="pageHeading"><?php echo HEADING_TITLE ?></td>
    <td colspan="2" class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
  </tr>
  <tr> 
   <td valign="top" colspan="4" > 
      <table border="0" cellpadding=2 cellspacing=0 width=100%> 
         <tr><td class="main"><b><?php echo HEADING_DATE_RANGE ?></b></td> </tr> <tr>
<!-- body_text //--> 


	<?php 
	$chkyes = "";
	$chkno ="";
	if( $option =="Yes")
	{
		$chkyes ="checked";
	}
	else
	{
		$chkno = "checked";
	} 
	?>

           			 
  <?php 
 
  echo tep_draw_form('year', 'stats_keywords_details.php', '', 'get');  
?>
  <td colspan="3"class="main" align="left"><input type="radio" value="Yes" name="option" <?php echo $chkyes;?>>
			
<?php	 
   echo  ' ' . tep_draw_pull_down_menu('type', $type_array, (($HTTP_GET_VARS['type']) ? $HTTP_GET_VARS['type'] : 'datewise')); ?></td> 
  
			</tr><tr><td><input type="radio" value="No" name="option" <?php echo $chkno;?>>
			<?php
			echo  ''.tep_draw_pull_down_menu('month', $months_array, (($HTTP_GET_VARS['month']) ? $HTTP_GET_VARS['month'] : date('n')));
			echo  ''.tep_draw_pull_down_menu('days', $days_array, (($HTTP_GET_VARS['days']) ? $HTTP_GET_VARS['days'] : 'days'));
			echo  ''.tep_draw_pull_down_menu('year', $years_array, (($HTTP_GET_VARS['year']) ? $HTTP_GET_VARS['year'] : date('Y'))); 
			
			echo ' - ' . tep_draw_pull_down_menu('month1', $months_array, (($HTTP_GET_VARS['month1']) ? $HTTP_GET_VARS['month1'] : date('n')));
			echo '' . tep_draw_pull_down_menu('days1', $days_array, (($HTTP_GET_VARS['days1']) ? $HTTP_GET_VARS['days1'] : 'days')) ;
			echo '' . tep_draw_pull_down_menu('year1', $years_array, (($HTTP_GET_VARS['year1']) ? $HTTP_GET_VARS['year1'] : date('Y'))) . '</td>'; 
			 
			?>
			</tr>

  <td class="main" align="left"><input type="submit" value="<?php echo BUTTON_REFRESH; ?>"></td> 
            </form>
            </tr> 
        </table> 

<?php

if( $option =="Yes")
 {
  $stdate = date("Y-m-d ");
         
	if(isset($HTTP_GET_VARS['type'])){
       switch ($HTTP_GET_VARS['type']) { 
       
	   case 'today':
		$order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$stdate."')  AND TO_DAYS(search_date) <= TO_DAYS('" .$stdate."')  group by search_text  ORDER BY "; 
		$order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$stdate."')  AND TO_DAYS(search_date) <= TO_DAYS('".$stdate."') "; 
      break;
	  
	  case 'yesterday':
	  	$yesterday  = mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") - 1, date("Y"));
		$yesterday = date ("Y-m-d", $yesterday); 
		
		$order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$yesterday."')  AND TO_DAYS(search_date) <= TO_DAYS('" .$stdate."') - 1 group by search_text  ORDER BY "; 
		$order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$yesterday."')  AND TO_DAYS(search_date) <= TO_DAYS('".$stdate."') - 1"; 
      break; 
	  
	  case 'lastsevendays': 
	  	$yesterday  = mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") - 7, date("Y"));
		$yesterday = date ("Y-m-d", $yesterday); 
		
		$order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$yesterday."')  AND TO_DAYS(search_date) <= TO_DAYS('" .$stdate."') - 1 group by search_text  ORDER BY "; 
		$order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$yesterday."')  AND TO_DAYS(search_date) <= TO_DAYS('".$stdate."') - 1"; 
      break;
	  
	  case 'monthtodate': 
	  	$start_year = date(y);
		$start_month = date(m); 
         $order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" . $start_year . "-" . $start_month . "-01')  AND TO_DAYS(search_date) <= TO_DAYS('".$stdate."') group by search_text  ORDER BY "; 
		 $order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" . $start_year . "-" . $start_month . "-01')  AND TO_DAYS(search_date) <= TO_DAYS('".$stdate."') "; 
      break; 
	  
	  case 'lastmonth':
	  /*
	    $yesterday  = mktime (date ("H"), date ("i"), date ("s"), date("m")-1, date ("d"), date("Y"));
		$yesterday = date ("Y-m-d", $yesterday); 
		$order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$yesterday."')  AND TO_DAYS(search_date) <= TO_DAYS('" .$stdate."')  group by search_text  ORDER BY "; 
		$order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" .$yesterday."')  AND TO_DAYS(search_date) <= TO_DAYS('".$stdate."') "; 
       */
	   
	  	$start_year = date(y);
		$end_year = date(y);
         $start_month = date(m)-1; 
         $end_month = date(m);
		 $order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" . $start_year . "-" . $start_month . "-01')  AND TO_DAYS(search_date) <= TO_DAYS('" . $end_year . "-" . $end_month . "-01') - 1 group by search_text  ORDER BY "; 
		 $order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" . $start_year . "-" . $start_month . "-01')  AND TO_DAYS(search_date) <= TO_DAYS('" . $end_year . "-" . $end_month . "-01') - 1"; 
		
      break; 
	  case 'alltime':
		$order =    "group by search_text  ORDER BY "; 
		$order1 =  " "; 
      break;
	  
	  
 
  } //switch
  
  
}//end if

}//if
else
{
 	     $start_days = $HTTP_GET_VARS['days'];
         $start_year = $HTTP_GET_VARS['year']; 
         $start_month = $HTTP_GET_VARS['month']; 
		 $end_days1 = $HTTP_GET_VARS['days1'];
         $end_year1 = $HTTP_GET_VARS['year1']; 
         $end_month1 = $HTTP_GET_VARS['month1']; 
         if ($end_month == '13') {$end_month = '1';} 
         $order =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" . $start_year . "-" . $start_month . "-".$start_days."')  AND TO_DAYS(search_date) <= TO_DAYS('" . $end_year1 . "-" . $end_month1 . "-".$end_days1."') group by search_text  ORDER BY "; 
         $order1 =  " WHERE TO_DAYS(search_date) >= TO_DAYS('" . $start_year . "-" . $start_month . "-".$start_days."')  AND TO_DAYS(search_date) <= TO_DAYS('" . $end_year1 . "-" . $end_month1 . "-".$end_days1."') "; 

}


switch($_GET['sortorder']){
  case BUTTON_SORT_NAME:
       $pw_word_sql = "SELECT distinct(search_text) as search_text1, count(*) as ct from search_date_stored $order"."search_text  ASC";
  break;
  case BUTTON_SORT_TOTAL:
   	  	$pw_word_sql = "SELECT distinct(search_text) as search_text1, count(*) as ct from search_date_stored $order ct  DESC";
  break;
  default:
   $pw_word_sql = "SELECT distinct(search_text) as search_text1, count(*) as ct from search_date_stored $order"."search_text  ASC";
   break;
}
// 	echo $pw_word_sql;
 
//echo $order;
 // $pw_word_sql = "SELECT distinct(search_text) as search_text1, count(*) as ct from search_date_stored $order"."search_text"; 
  




// for deletion and updation of the record


if ($_GET['action'] == 'Delete') {
	if(isset($HTTP_GET_VARS['type'])){
	   $del_sql_q = tep_db_query("SELECT DISTINCT * FROM search_date_stored $order1 ");
	  
				 			   
				       $sql_q1 = tep_db_query("SELECT DISTINCT search_text, COUNT(*) AS ct FROM search_date_stored $order1 GROUP BY search_text");
					   while ($sql_q_result1 = tep_db_fetch_array($sql_q1)) {     
					   $update_q1 = tep_db_query("select search_text, search_count from search_queries_sorted where search_text = '" . $sql_q_result1['search_text'] . "'");
						   $update_q_result1 = tep_db_fetch_array($update_q1);
						   $count1 =  $update_q_result1['search_count'] - $sql_q_result1['ct'] ; 
								   if($count1 == 0)
								   {
								   tep_db_query("delete from search_queries_sorted where search_text='".$sql_q_result1['search_text']."'");
								   }
								   else
								   {
								   tep_db_query("update search_queries_sorted set search_count = '" . $count1 . "' where search_text = '" . $sql_q_result1['search_text'] . "'");
								   }
						  }//while
		
				  	
				tep_db_query("delete from search_date_stored $order1");
				
		}// end of 	if(isset($HTTP_GET_VARS['type'])){
	elseif(isset($_GET['search_text'])){ 
	tep_db_query("delete from search_queries_sorted where search_text = '".$_GET['search_text']."'");
	tep_db_query("delete from search_date_stored where search_text = '".$_GET['search_text']."'");
	} //else if 
} // delete db
  
  
  
$sql_date = tep_db_query("SELECT DISTINCT search_date FROM search_queries");
$sql_date_result = tep_db_fetch_array($sql_date);
$stdate = $sql_date_result['search_date'];


  
if ($_GET['update'] == BUTTON_UPDATE_WORD_LIST) {
		
//transfer data 	
	$sql_q_all = tep_db_query("SELECT * FROM search_queries");
	while ($sql_q_all_result = tep_db_fetch_array($sql_q_all)) {     
	tep_db_query("insert into search_date_stored ( search_text, search_date) values ('" . $sql_q_all_result['search_text'] . "','" . $sql_q_all_result['search_date'] . "')");
	}
	
	
    $sql_q = tep_db_query("SELECT DISTINCT search_text, COUNT(*) AS ct FROM search_queries GROUP BY search_text");

       while ($sql_q_result = tep_db_fetch_array($sql_q)) {     
	   $update_q = tep_db_query("select search_text, search_count from search_queries_sorted where search_text = '" . $sql_q_result['search_text'] . "'");
           $update_q_result = tep_db_fetch_array($update_q);
           $count = $sql_q_result['ct'] + $update_q_result['search_count']; 
		   
		    if ($update_q_result['search_count'] != '') {
	       		tep_db_query("update search_queries_sorted set search_count = '" . $count . "' where search_text = '" . $sql_q_result['search_text'] . "'");
		 
		     } else {
                tep_db_query("insert into search_queries_sorted (search_text, search_count) values ('" . $sql_q_result['search_text'] . "'," . $count . ")");
		 
	         } // search_count
			 
			 //insert into the table for date 
			
         
        } // while
		  tep_db_query("delete from search_queries");
 } // updatedb

?>
<tr>      <td class="smallText" align="left"> 
       <?php //echo RANGE_FROM . ' ' . date("d-M-Y", mktime(0,0,0,$start_month  ,1,$start_year)); ?>&nbsp;&nbsp;<?php // echo RANGE_TO . ' ' . date("d-M-Y", mktime(0,0,0,$end_month  ,0,$end_year)); ?> 
    </td> </tr>
  <tr>
    <td class="main" colspan="3">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr class="dataTableHeadingRow">
    <td  class="dataTableHeadingContent" width="40%"><?php echo KEYWORD_TITLE ?></td>
  	<td  colspan="2" class="dataTableHeadingContent"><?php echo KEYWORD_TITLE2 ?></td>
  </tr>

<?php

if(isset($_GET['search_text'])){
$pw_word_sql = "SELECT distinct(search_text) as search_text1, count(*) as ct from search_date_stored   where search_text = '".$_GET['search_text']."' group by search_text ORDER BY search_date ASC" ;
} else if ( (!isset($_GET['search_text'])) && (!isset($HTTP_GET_VARS['type']))) {
$pw_word_sql = "SELECT * FROM search_date_stored  ORDER BY search_date ASC" ;
}
$pw_words = tep_db_query($pw_word_sql);
    while ($pw_words_result = tep_db_fetch_array($pw_words)) { 
?>
  <tr class="dataTableRow"  >
    <td class="dataTableContent"><a target="_blank" href="<?php echo tep_href_link( '../advanced_search_result.php', 'keywords=' . urlencode($pw_words_result['search_text1']). '&search_in_description=1' ); ?>"><?php echo tep_db_prepare_input($pw_words_result['search_text1']); ?></a></td>  
	<td class="dataTableContent"><?php echo $pw_words_result['ct']; ?></td>
	 <!--<td class="dataTableContent"><?php //echo $pw_words_result['search_date']; ?></td>-->
	 <td class="dataTableContent">
	 <?php if(isset($HTTP_GET_VARS['type'])){ 
	 ?>
			<a href="stats_keywords_details.php?search_text=<?php echo urlencode($pw_words_result['search_text']); ?>"><?php //echo KEYWORD_VIEW; ?></a>
			
			<? } 
			?>
	</td>  
  </tr>
<?php }?>
<tr>
<td colspan="4"> 
<?php echo '<a href="' . tep_href_link('stats_keywords.php') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>
</td>
</tr>

    </table>
    </td>
  </tr>
 </table>
    </td>
<!-- body_eof //-->
<!-- right_column_bof //-->
<td valign="top" width="25%">
<?php echo tep_draw_form('delete', 'stats_keywords_details.php', '', 'get'); ?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td class="pageHeading" align="right">&nbsp;</td>
  </tr><tr>
    <td>
<?php
    $heading = array();
    $contents = array();

    $heading[]  = array('text'  => '<b>' . SIDEBAR_HEADING . '</b>');
	/*
    $contents[] = array('text'  => '<br>' . SIDEBAR_INFO_1);
    $contents[] = array('text'  => '<input type="submit" name="update" value="' . BUTTON_UPDATE_WORD_LIST . '">');
    $contents[] = array('text'  =>  tep_draw_separator());
	*/
	
    $contents[] = array('text'  => '<br><input type="submit" name="sortorder" value="' . BUTTON_SORT_NAME . '"><br><input type="submit" name="sortorder" value="' . BUTTON_SORT_TOTAL . '">');
    $contents[] = array('text'  =>  tep_draw_separator());
	
    $contents[] = array('text'  => '<br>' . SIDEBAR_INFO_2);
    $contents[] = array('text'  => '<input type="submit" value="' . BUTTON_DELETE . '" name="action">');
	

    $contents[] = array('text'  =>  tep_draw_separator());
	
	if(isset($HTTP_GET_VARS['type'])){
		if(($HTTP_GET_VARS['type'])!= '')
	    $contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['type'] . '" name="type">');
		if(($HTTP_GET_VARS['month'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['month'] . '" name="month">');
		if(($HTTP_GET_VARS['year'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['year'] . '" name="year">');
        if(($HTTP_GET_VARS['days'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['days'] . '" name="days">');
		
		
		if(($HTTP_GET_VARS['month1'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['month1'] . '" name="month1">');
		if(($HTTP_GET_VARS['year1'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['year1'] . '" name="year1">');
        if(($HTTP_GET_VARS['days1'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['days1'] . '" name="days1">');

	}
	elseif(isset($_GET['search_text']) && ($_GET['search_text'] != '' )){
	$contents[] = array('text'  => '<input type="hidden" value="' . $_GET['search_text'] . '" name="search_text">');
    }
	if(($HTTP_GET_VARS['option'])!= '')
		$contents[] = array('text'  => '<input type="hidden" value="' . $HTTP_GET_VARS['option'] . '" name="option">');
	
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {

    $box = new box;
    echo $box->infoBox($heading, $contents);
  } ?>    
</td></tr></table></form>
</td>
  </tr>
</table>  
<!-- right_column_eof //-->
            
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>         
</html>       
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
          
        

