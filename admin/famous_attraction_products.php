<?php

  require_once('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('famous_attraction_products');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
	if( $HTTP_GET_VARS['fam_prod_id'] != ''){
	$fam_prod_id =  $HTTP_GET_VARS['fam_prod_id'] ;
	}

	
	
			$categories_array = array(array('id' => '', 'text' => TEXT_NONE));
		// $categories_query = tep_db_query("select categories_id, categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where order by categories_name");

			$categories_query = tep_db_query("select cd.categories_id, cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " as cd , " . TABLE_CATEGORIES . " as c where cd.categories_id = c.categories_id   AND c.parent_id = '0' order by categories_name");
			while ($categories = tep_db_fetch_array($categories_query)) {
			  $categories_array[] = array('id' => $categories['categories_id'],
											 'text' => $categories['categories_name']);
			}
	
  if ($HTTP_GET_VARS['action']) {

    switch ($HTTP_GET_VARS['action']) {

   
      case 'delete_tour_type_confirm' : //user has confirmed deletion of news article.

        if ($HTTP_POST_VARS['fam_prod_id']) {

          $fam_prod_id = tep_db_prepare_input($HTTP_POST_VARS['fam_prod_id']);

          tep_db_query("delete from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS . " where fam_prod_id = '" . tep_db_input($fam_prod_id) . "'");
	   }



        //tep_redirect(tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS)); 
		tep_redirect(tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS));

        break;



      case 'addtourproccess': //insert a new news article.

		
      

           $sql_data_array = array( 
		   
		    					  'fam_cat_id'   => tep_db_prepare_input($HTTP_POST_VARS['fam_cat_id']),
								  
								  'fam_prod_ids'   => tep_db_prepare_input($HTTP_POST_VARS['fam_prod_ids']),

                                  'date_added' => 'now()', //uses the inbuilt mysql function 'now' 							 

								  );


	 	$findstoreckeckinfo = tep_db_query("select *  from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS . "  where fam_cat_id  = '" .$HTTP_POST_VARS['fam_cat_id']."'");

		  if ($store_check = tep_db_fetch_array($findstoreckeckinfo)) {

			tep_db_perform(TABLE_FAMOUS_ATTRACTION_PRODUCTS, $sql_data_array, 'update', "fam_cat_id = '" .$HTTP_POST_VARS['fam_cat_id'] . "'");
			$fam_prod_id = $HTTP_POST_VARS['fam_cat_id'];
			
		  }else {

		  	 tep_db_perform(TABLE_FAMOUS_ATTRACTION_PRODUCTS, $sql_data_array);
			 $fam_prod_id = tep_db_insert_id();
		  }
         

          //not actually used ATM -- just there in case

        //}



        tep_redirect(tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS,'fam_prod_id='.$fam_prod_id));
		
		

        break;



      case 'update_que_ans': //user wants to modify a news article.

        if($HTTP_GET_VARS['fam_prod_id']) {

          $sql_data_array = array(
                                 'fam_prod_ids'   => tep_db_prepare_input($HTTP_POST_VARS['fam_prod_ids']),
								 'fam_cat_id'   => tep_db_prepare_input($HTTP_POST_VARS['fam_cat_id'])
								  );

                                  

          tep_db_perform(TABLE_FAMOUS_ATTRACTION_PRODUCTS, $sql_data_array, 'update', "fam_prod_id = '" . tep_db_prepare_input($HTTP_GET_VARS['fam_prod_id']) . "'");

        }

        

       // tep_redirect(tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS));
		tep_redirect(tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS,"fam_prod_id=". tep_db_prepare_input($HTTP_GET_VARS['fam_prod_id']))); 
        break;
		
		
		
		
		
		
		
    }
	
	

  }



?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo HEADING_TITLE; ?></title>

<script language="Javascript1.2"><!-- // load htmlarea

// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML - Head

        _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files

          var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);

           if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }

            if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }

             if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }

         <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {

         document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');

         document.write(' language="Javascript1.2"></scr' + 'ipt>');

            } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }

         <?php } else{ ?> if (win_ie_ver >= 5.5) {

         document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');

         document.write(' language="Javascript1.2"></scr' + 'ipt>');

            } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }

         <?php }?>

// --></script>


       
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/general.js"></script>



<script language="javascript"><!--
function validEmail(strEmail)
{	
	
	
    if (strEmail.search(/[A-Za-z0-9\._]+\@[A-Za-z0-9\-]+\.[A-Za-z0-9\.]/gi) != -1)
		return true;
    else
        return false; 
}	

function checkForm() {

if(document.product_question_answer_write.replay_name.value == ""){
alert("Please enter the value for your name");
document.product_question_answer_write.replay_name.focus();
return false;
}

if(document.product_question_answer_write.replay_email.value == ""){
alert("Please enter the value for your email");
document.product_question_answer_write.replay_email.focus();
return false;
}
if (!validEmail(document.product_question_answer_write.replay_email.value))
{
		alert("Please enter a Valid login id Address.");
		document.product_question_answer_write.replay_email.focus();
		return false;
}
if(document.product_question_answer_write.anwers.value == ""){
alert("Please write your answers");
document.product_question_answer_write.anwers.focus();
return false;
}
	
return  true;
 }


function checkForm1() {

if(document.product_queston_write.fam_cat_id.value == ""){
alert("Please select top category");
document.product_queston_write.fam_cat_id.focus();
return false;
}

if(document.product_queston_write.fam_prod_ids.value == ""){
alert("Please enter the value for sub categories ids");
document.product_queston_write.fam_prod_ids.focus();
return false;
}



return  true;
 }

//--></script>


<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('famous_attraction_products');
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

<?php

  if ($HTTP_GET_VARS['action'] == 'edit_tour_type') { //insert or edit a news item

    if ( isset($HTTP_GET_VARS['fam_prod_id']) ) { //editing exsiting news item

      $que_ans_query = tep_db_query("select *  from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS . " where fam_prod_id = '" . $HTTP_GET_VARS['fam_prod_id'] . "'");
		
	  $que_ans = tep_db_fetch_array($que_ans_query);

    } 


?>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

      </tr>

      <tr><?php echo tep_draw_form('edit_quesion', FILENAME_FAMOUS_ATTRACTION_PRODUCTS, isset($HTTP_GET_VARS['fam_prod_id']) ? 'fam_prod_id='.$HTTP_GET_VARS['fam_prod_id'].'&action=update_que_ans' : 'action=insert_stores_deals', 'post', 'enctype="multipart/form-data"'); ?>

        <td><table border="0" cellspacing="0" cellpadding="2">


		  
		    <tr> 
						
			<td width="25%" class="main"><b>Top Categories:</b>&nbsp;<font color="#FF0000">*</font></td>
	
			<td width="75%" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '10'); ?><?php
			echo tep_draw_pull_down_menu('fam_cat_id', $categories_array, $que_ans['fam_cat_id']);
			
			?></td>
	
		  </tr>
		 
		<tr> 
		<td colspan=2>&nbsp;</td>
		</tr>
		
		<tr> 
	
			<td width="25%" class="main"><b>Add Products:</b><br><small>(ex-15,30,45,43)</small> &nbsp;<font color="#FF0000">*</font></td>
	
			<td width="75%" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '10'); ?><?php echo tep_draw_input_field('fam_prod_ids', stripslashes($que_ans['fam_prod_ids'])); ?></td>
	
		  </tr>

		
        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

      </tr>

      <tr>

        <td class="main" align="right">

          <?php

            isset($HTTP_GET_VARS['fam_prod_id']) ? $cancel_button = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS,'fam_prod_id='.$HTTP_GET_VARS['fam_prod_id']). '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' : $cancel_button = '';

            echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . $cancel_button;

          ?>

        </td>

      </form></tr>

<?php

}else if ($HTTP_GET_VARS['action'] == 'view_question') {

//amit added to delete answer start
if(isset($HTTP_GET_VARS['deleteansid']) && $HTTP_GET_VARS['deleteansid'] != ''){
  tep_db_query("delete from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS_ANSWER . " where ans_id = '" . tep_db_input($HTTP_GET_VARS['deleteansid']) . "'");
}
//amit added to delete answer end

?>
		<tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

      </tr>
	  <tr>

        <td>
		<!--  view body text start  -->
		
		
			<?php 
			
			if(isset($HTTP_GET_VARS['replay']) && $HTTP_GET_VARS['replay']=='ans'){?>
			
			
			<?php 
			echo tep_draw_form('product_question_answer_write', FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'action=process&fam_prod_id='.$fam_prod_id, 'post', 'onSubmit="return checkForm();"');
			?>
			<table border="0" width="100%" cellspacing="2" cellpadding="2" class="unnamed1">
						
                          <?php 
							if($success == true){
							?>
							<tr>
				
							<td  colspan="3" class="main"><b>Your replay send successfully.</b></td>
				
							</tr>
							<?php
							}else{
							
							?>
						 
						  <tr> 

                            <td width="23%" class="main"><b>Your Name:</b>&nbsp;<font color="#FF0000">(required)</font></td>

                            <td colspan="2" class="main"><?php echo tep_draw_input_field('replay_name',STORE_OWNER); ?></td>

                          </tr>

                          <tr> 

                            <td class="main"><b>Your E-mail:</b>&nbsp;<font color="#FF0000">(required)</font></td>

                            <td colspan="2" class="main"><?php echo tep_draw_input_field('replay_email',STORE_OWNER_EMAIL_ADDRESS); ?></td>

                          </tr>

                         
                          <tr> 

                            <td class="main"><b>Your Answers:</b>&nbsp;<font color="#FF0000">(required)</font></td>

                            <td width="63%"  class="main"><?php echo tep_draw_textarea_field('anwers', 'soft', 45, 10); ?></td>

                            <td width="14%"  class="main">&nbsp;</td>
                          </tr>

                        
					
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                          <tr> 

                            <td class="main">&nbsp;</td>

                            <td class="main"><input type="submit" name="submit" value="submit"></td>
                            <td  class="main">&nbsp;</td>
                          </tr>
						   <tr> 

						   <td colspan="3">
									<table border="0" width="100%" cellspacing="0" cellpadding="2">
		
								  <tr> 
		
									<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		
									<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, tep_get_all_get_params(array('cPath','replay','page'))) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
		
								   
									<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		
								  </tr>

                        			</table>
						
						</td>

                    	</tr>
						  
							<?php
							}
							?>
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                        </table>
						</form>
			
			<?php }else{ ?>
			<table border="0" width="100%" cellspacing="0" cellpadding="2" class="unnamed1" >
				<?php 
				
				  $question_query_raw = "select * from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS ." where fam_prod_id = '" . (int)$HTTP_GET_VARS['fam_prod_id'] . "'";
				
								
					$question_query = tep_db_query($question_query_raw);
				
					while ($question = tep_db_fetch_array($question_query)) {
				
				?>
				
									<tr> 
				
									  <td width="100%" valign="top">
				
											<table border="0" width="100%" cellspacing="0" cellpadding="2">
				
																			  
				
												<tr bgcolor="#99D2ED"> 
				
												  <td colspan="2" class="main" >by:&nbsp;
													<?php 
				
											  $question['customers_name'] = ucfirst(substr($question['customers_name'], 0, 1)).substr($question['customers_name'], 1, strlen($question['customers_name']));
											  echo "<b>".$question['customers_name']."</b></br>";
											  echo "at:&nbsp;".sprintf(tep_date_long($question['date']));
				
											  ?>
												 </td>
				
												</tr>
												<tr bgcolor="#99D2ED"> 
				
												  <td width="95%" valign="top" class="main"><?php echo tep_break_string(tep_output_string_protected($question['fam_prod_ids']), 80, '-<br>'); ?></td>
												  <td width="5%" align="right" valign="bottom" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, tep_get_all_get_params(array('page')).'replay=ans','NONSSL',true,true,"qanda") . '">' . tep_image_button('button_reply.gif', 'Reply') . '</a>'; ?></td>
												</tr>
											<!--
										  <tr> 
				
											<td colspan="2"><hr size="1"></td>
				
										  </tr>
										  -->
									   </table>
									  
								      </td> 
				
			  </tr>  
									 
									  
				
									  <!--  check for answer of the question BOF--->
									  <?php 
										$question_query_answer_raw = tep_db_query("select * from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS_ANSWER ." where fam_prod_id = '" . (int)$question['fam_prod_id'] . "' order by date desc");
										while ($question_ans = tep_db_fetch_array($question_query_answer_raw)) {
										
										?>
									<tr> 
										
									  <td width="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
										  <td width="3%">&nbsp;</td>
										  <td width="97%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
				
																			  
				
												<tr bgcolor="#DCF4FE"> 
				
												  <td colspan="2" class="main" >Re by:&nbsp;
													<?php 
				
											   $question_ans['replay_name']= ucfirst(substr($question_ans['replay_name'], 0, 1)).substr($question_ans['replay_name'], 1, strlen($question_ans['replay_name']));
											
											  echo "<b>".$question_ans['replay_name']."</b></br>";
											  echo "at:&nbsp;".sprintf(tep_date_long($question_ans['date']));
				
											  ?>
												 </td>
				
												</tr>
												<tr bgcolor="#DCF4FE"> 
				
												  <td width="95%" valign="top" class="main"><?php echo tep_break_string(tep_output_string_protected($question_ans['ans']), 80, '-<br>'); ?></td>
												  <td width="5%" align="right" valign="bottom" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, "fam_prod_id=".$question['fam_prod_id']."&action=view_question&deleteansid=".$question_ans['ans_id']."") . '">' . tep_image_button('button_delete.gif', 'Reply') . '</a>'; ?></td>
												</tr>
											<!--
										  <tr> 
				
											<td colspan="2"><hr size="1"></td>
				
										  </tr>
										  -->
									   </table>
										  </td>
										</tr>
									  </table>
				
									  </td> 
				
			  </tr>  
										
									<?
										}  
									 ?>
														 
									  <!--  check for answer of the question EOF--->
									  
											 
											  
											  
						<?php
						}  //end of while loop
						?>
						
						
			</table>
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
						
												  <tr> 
						
													<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						
													<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, tep_get_all_get_params(array('dealscat','replay','page','action','deleteansid'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>
						
													<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						
												  </tr>
						
												</table>
						
				<?php }?>		

		<!-- view body text end -->
		
		
		</td>

      </tr>

<?php }else  if ($HTTP_GET_VARS['action'] == 'addtourtype') {

?>

 	<tr>
 	<td>

          <table border="0" width="100%" cellspacing="0" cellpadding="0">

            <tr>
              <td colspan="2" class="pageHeading">Add Tour Category Type</td>
		    </tr>			
				
				<?php 
				echo tep_draw_form('product_queston_write', FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'action=addtourproccess', 'post', 'onSubmit="return checkForm1();"');
				?>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
						 						
										
							  <tr>
						
								<td>
								</br>
								<table width="100%" border="0" cellspacing="0" cellpadding="2">
																
									
									  <tr> 
						
										<td align="center" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
						
											<tr> 
						
											  <td></br><table border="0" width="100%" cellspacing="2" cellpadding="2" class="unnamed1" >
													
												  <tr> 
						
													<td width="25%" class="main"><b>Top Categories:</b>&nbsp;<font color="#FF0000">*</font></td>

						
													<td width="75%" class="main"><?php
													echo tep_draw_pull_down_menu('fam_cat_id', $categories_array);
													
													?></td>
						
												  </tr>
												 
												<tr> 
												<td colspan=2>&nbsp;</td>
												</tr>
											  	
												<tr> 
						
													<td width="25%" class="main"><b>Add Products:</b><br><small>(ex-15,30,45,43)</small> &nbsp;<font color="#FF0000">*</font></td>
						
													<td width="75%" class="main"><?php echo tep_draw_input_field('fam_prod_ids'); ?></td>
						
												  </tr>
												 
												<tr> 
												<td >&nbsp;</td>	
												<td><input type="submit" name="submit" value="Add Attractions"> </td>
						
											</tr>
						
												  <?php // BOF: WebMakers.com Added: Split to two rows ?>
						
												</table></td>
						
																
											
						
										  </table></td>
						
									</table></td>
						
							  </tr>
						
							</table></form>
				
			<tr> 
						<td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
						
												  <tr> 
						
													<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						
													<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, tep_get_all_get_params(array('cPath','replay','page','action'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>
						
													<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						
												  </tr>
						
												</table></td>
						
											</tr>
			
			
		</table>
	</td>
	</tr>	

<?php
}else {

?>

      <tr>

        <td>

          <table border="0" width="100%" cellspacing="0" cellpadding="0">

            <tr>

              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

              <td align="right"><?php // echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?>
			   
			  
			  </td>

            </tr>

          </table>

        </td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr class="dataTableHeadingRow">
			  <td class="dataTableHeadingContent">#ID</td>

                <td class="dataTableHeadingContent">Top Category</td>
				
				 <td class="dataTableHeadingContent">Show Products</td>

                <td class="dataTableHeadingContent" width="10%" align="center">Date<br><?php echo "<a  href=product_category_type.php?sortorder=date&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=product_category_type.php?sortorder=date-desc&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

				<!--<td class="dataTableHeadingContent" align="center"><?php //echo INDEX_FEATURE_STATUS; ?></td>-->

                <td class="dataTableHeadingContent" width="5%" align="right">Action</td>

              </tr>

<?php

switch (trim($HTTP_GET_VARS['sortorder'])) {
              
			  case "ans":
              $order = "fam_prod_id ";
              break;
              case "ans-desc":
              $order = "fam_prod_id DESC";
			   break;
              case "date":
              $order = "date_added";
              break;
              case "date-desc":
              $order = "date_added DESC";
              break;
              default:
              $order = "fam_prod_id";
              break;
          
          }


//echo  $order;

    $rows = 0;
	/*
	if(isset($HTTP_GET_VARS['sortorde']) && $HTTP_GET_VARS['sortorde']=''){
	
	}else 
	
	
*/

  //$question_query_raw = "select count(*) as count,q.fam_prod_id,q.products_id,q.question,q.date from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS_ANSWER . " as qa," . TABLE_FAMOUS_ATTRACTION_PRODUCTS . " as q where q.fam_prod_id = qa.fam_prod_id  group by qa.fam_prod_id order by count";
 	
	
	$question_query_raw = "select *  from " . TABLE_FAMOUS_ATTRACTION_PRODUCTS ." order by $order";

 // $question_split = new splitPageResults($question_query_raw, MAX_DISPLAY_NEW_REVIEWS);
$question_answers_query_numrows = 20;
define('MAX_DISPLAY_NEW_QUEASION','20');
$question_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_NEW_QUEASION, $question_query_raw, $question_answers_query_numrows);
  

  //if ($question_split->number_of_rows > 0) {

   // $question_query = tep_db_query($question_split->sql_query);

	$question_query = tep_db_query($question_query_raw);
    while ($question = tep_db_fetch_array($question_query)) {
	
	 if($rows == 0 && (!$action) && (!$selected_item) && !isset($HTTP_GET_VARS['fam_prod_id'])){
	 $HTTP_GET_VARS['fam_prod_id'] = $question['fam_prod_id'];  
	 }						

					

      $rows++;

      

      if ( ((@$HTTP_GET_VARS['fam_prod_id'] == $question['fam_prod_id'])) && (!$selected_item) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {

        $selected_item = $question;

      }

      if ( (is_array($selected_item)) && ($question['fam_prod_id'] == $selected_item['fam_prod_id']) ) {

        echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'fam_prod_id=' . $question['fam_prod_id']) . '\'">' . "\n";

      } else {

        echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'fam_prod_id=' . $question['fam_prod_id']) . '\'">' . "\n";

      }

?>

 				<td class="dataTableContent"><?php echo $question['fam_prod_id']; ?>
               <td class="dataTableContent"><?php echo tep_get_categories_name($question['fam_cat_id']); ?>
			   </td>
				 <td class="dataTableContent"><?php echo $question['fam_prod_ids']; ?>
			   </td>
				<td class="dataTableContent" align="center">
				
				<?php //echo $question['date']; 
				
				
					$one = $question['date_added'];
				$date = ereg_replace('[^0-9]', '', $one);
				$date_year = substr($one,0,4);
				$date_month    = substr($one,5,2);
				$date_day = substr($one,8,2);
				$date_hour = substr($one,11,2);
				$date_minute = substr($one,14,2);
				$date_second = substr($one,17,2);
				
				$questiondate = str_replace('-','/',date('Y-m-d', mktime($date_hour, $date_minute, $date_second, $date_month, $date_day, $date_year)));
				echo $questiondate;
				
				?>
				
				
				
				
				</td>				
				
                <td class="dataTableContent" align="right"><?php if ($question['fam_prod_id'] == $HTTP_GET_VARS['fam_prod_id']) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'fam_prod_id=' . $question['fam_prod_id'].'&'.tep_get_all_get_params(array('info', 'x', 'y', 'action','dealscat' , 'fam_prod_id'))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>

              </tr>

<?php

    } 

?>

              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $question_split->display_count($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, $HTTP_GET_VARS['page'], ''); ?></td>
                    <td class="smallText" align="right"><?php echo $question_split->display_links($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'fam_prod_id'))); ?></td>
                  </tr>

                </table></td>
              </tr>

            </table></td>

<?php

    $heading = array();

    $contents = array();

    switch ($HTTP_GET_VARS['action']) {

      case 'delete_tour_type': //generate box for confirming a news article deletion

        $heading[] = array('text'   => '<b>Delete</b>');

        

        $contents = array('form'    => tep_draw_form('news', FILENAME_FAMOUS_ATTRACTION_PRODUCTS, '&action=delete_tour_type_confirm') . tep_draw_hidden_field('fam_prod_id', $HTTP_GET_VARS['fam_prod_id']));

       // $contents[] = array('text'  => TEXT_DELETE_ITEM_INTRO);

        $contents[] = array('text'  => '<br><b>' . $selected_item['fam_prod_ids'] . '</b>');

        

        $contents[] = array('align' => 'center',

                            'text'  => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'fam_prod_id=' . $selected_item['fam_prod_id']).'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;



      default:

        if ($rows > 0) {

          if (is_array($selected_item)) { //an item is selected, so make the side box

            $heading[] = array('text' => '');



            $contents[] = array('align' => 'center', 

                                'text' => '<a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'action=addtourtype') . '">' . tep_image_button('button_new.gif', IMAGE_ADD) . '</a> <a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'fam_prod_id=' . $selected_item['fam_prod_id'] . '&action=edit_tour_type') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'fam_prod_id=' . $selected_item['fam_prod_id'] . '&action=delete_tour_type') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a></br>');
	            $contents[] = array('text' => '<br>' . $selected_item['content']);

          }

        } else { // create category/product info

          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');



          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name) .'<br><a href="' . tep_href_link(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, 'action=addtourtype') . '">' . tep_image_button('button_new.gif', IMAGE_ADD) . '</a>');

        }

        break;

    }



    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {

      echo '            <td width="25%" valign="top">' . "\n";



      $box = new box;

      echo $box->infoBox($heading, $contents);



      echo '            </td>' . "\n";

    }

?>

          </tr>

        </table></td>

      </tr>

<?php

  }

?>

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