<?php

  require_once('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('affiliate_banner_groups');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
	if( $HTTP_GET_VARS['affiliate_banners_group_id'] != ''){
	$affiliate_banners_group_id =  $HTTP_GET_VARS['affiliate_banners_group_id'] ;
	}

	
	
  if ($HTTP_GET_VARS['action']) {

    switch ($HTTP_GET_VARS['action']) {

   
      case 'delete_tour_type_confirm' : //user has confirmed deletion of news article.
	  //amit added to check groups contain any banner or not
	 $duplicate_check_query_check = "select count(*) as total from " . TABLE_AFFILIATE_BANNERS . " where  affiliate_banners_group = '" . tep_db_input($affiliate_banners_group_id) . "'";
	    $duplicate_check_query = tep_db_query($duplicate_check_query_check);
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
		
		if($duplicate_check['total'] < 1){
			if ($HTTP_POST_VARS['affiliate_banners_group_id']) {
          		$affiliate_banners_group_id = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_group_id']);
	          	tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS_GROUPS . " where affiliate_banners_group_id = '" . tep_db_input($affiliate_banners_group_id) . "'");
		   }		
		   tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS)); 
		}else{
        	$messageStack->add_session(ERROR_DELETE_GROUPS_EXIST, 'error');
			tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS,'affiliate_banners_group_id='.$affiliate_banners_group_id));

		}


        //tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS)); 
		
        break;



      case 'addtourproccess': //insert a new news article.

		$check_banner_group_name_query="select * from ". TABLE_AFFILIATE_BANNERS_GROUPS . " where affiliate_banners_group_name='".tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_group_name'])."'";
		$check_banner_group_name=tep_db_query($check_banner_group_name_query);
		if(tep_db_num_rows($check_banner_group_name)==0)
		{
           $sql_data_array = array( 
								  'affiliate_banners_group_name'   => tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_group_name']),

                                  'affiliate_banners_date_added' => 'now()', //uses the inbuilt mysql function 'now' 							 

								  );




          tep_db_perform(TABLE_AFFILIATE_BANNERS_GROUPS, $sql_data_array);

          $affiliate_banners_group_id = tep_db_insert_id(); //not actually used ATM -- just there in case
		}      

        //}



        tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS,'affiliate_banners_group_id='.$affiliate_banners_group_id));
		
		

        break;



      case 'update_que_ans': //user wants to modify a news article.

        if($HTTP_GET_VARS['affiliate_banners_group_id']) {

          $sql_data_array = array(
                                 'affiliate_banners_group_name'   => tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_group_name'])

								  );

                                  

          tep_db_perform(TABLE_AFFILIATE_BANNERS_GROUPS, $sql_data_array, 'update', "affiliate_banners_group_id = '" . tep_db_prepare_input($HTTP_GET_VARS['affiliate_banners_group_id']) . "'");

        }

        

       // tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS));
		tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS,"affiliate_banners_group_id=". tep_db_prepare_input($HTTP_GET_VARS['affiliate_banners_group_id']))); 
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

if(document.product_queston_write.affiliate_banners_group_name.value == ""){
alert("Please enter the value for affiliate banner group name");
document.product_queston_write.affiliate_banners_group_name.focus();
return false;
}


return  true;
 }

//--></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('affiliate_banner_groups');
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

  if ($HTTP_GET_VARS['action'] == 'edit_banner_cat') { //insert or edit a news item

    if ( isset($HTTP_GET_VARS['affiliate_banners_group_id']) ) { //editing exsiting news item

      $que_ans_query = tep_db_query("select *  from " . TABLE_AFFILIATE_BANNERS_GROUPS . " where affiliate_banners_group_id = '" . $HTTP_GET_VARS['affiliate_banners_group_id'] . "'");
		
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

      <tr><?php echo tep_draw_form('edit_quesion', FILENAME_AFFILIATE_BANNER_GROUPS, isset($HTTP_GET_VARS['affiliate_banners_group_id']) ? 'affiliate_banners_group_id='.$HTTP_GET_VARS['affiliate_banners_group_id'].'&action=update_que_ans' : 'action=insert_stores_deals', 'post', 'enctype="multipart/form-data"'); ?>

        <td><table border="0" cellspacing="0" cellpadding="2">


		
          
		  <tr>

            <td class="main"><?php echo 'Affiliate Banner Group Name' ?></td>

            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('affiliate_banners_group_name', stripslashes($que_ans['affiliate_banners_group_name'])); ?></td>

          </tr>

		
        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

      </tr>

      <tr>

        <td class="main" align="right">

          <?php

            isset($HTTP_GET_VARS['affiliate_banners_group_id']) ? $cancel_button = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS,'affiliate_banners_group_id='.$HTTP_GET_VARS['affiliate_banners_group_id']). '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' : $cancel_button = '';

            echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . $cancel_button;

          ?>

        </td>

      </form></tr>

<?php

}else if ($HTTP_GET_VARS['action'] == 'view_question') {

//amit added to delete answer start
if(isset($HTTP_GET_VARS['deleteansid']) && $HTTP_GET_VARS['deleteansid'] != ''){
  tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS_GROUPS_ANSWER . " where ans_id = '" . tep_db_input($HTTP_GET_VARS['deleteansid']) . "'");
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
			echo tep_draw_form('product_question_answer_write', FILENAME_AFFILIATE_BANNER_GROUPS, 'action=process&affiliate_banners_group_id='.$affiliate_banners_group_id, 'post', 'onSubmit="return checkForm();"');
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
		
									<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, tep_get_all_get_params(array('cPath','replay','page'))) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
		
								   
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
				
				  $question_query_raw = "select * from " . TABLE_AFFILIATE_BANNERS_GROUPS ." where affiliate_banners_group_id = '" . (int)$HTTP_GET_VARS['affiliate_banners_group_id'] . "'";
				
								
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
				
												  <td width="95%" valign="top" class="main"><?php echo tep_break_string(tep_output_string_protected($question['affiliate_banners_group_name']), 80, '-<br>'); ?></td>
												  <td width="5%" align="right" valign="bottom" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, tep_get_all_get_params(array('page')).'replay=ans','NONSSL',true,true,"qanda") . '">' . tep_image_button('button_reply.gif', 'Reply') . '</a>'; ?></td>
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
										$question_query_answer_raw = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS_GROUPS_ANSWER ." where affiliate_banners_group_id = '" . (int)$question['affiliate_banners_group_id'] . "' order by date desc");
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
												  <td width="5%" align="right" valign="bottom" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, "affiliate_banners_group_id=".$question['affiliate_banners_group_id']."&action=view_question&deleteansid=".$question_ans['ans_id']."") . '">' . tep_image_button('button_delete.gif', 'Reply') . '</a>'; ?></td>
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
						
													<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, tep_get_all_get_params(array('dealscat','replay','page','action','deleteansid'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>
						
													<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
						
												  </tr>
						
												</table>
						
				<?php }?>		

		<!-- view body text end -->
		
		
		</td>

      </tr>

<?php }else  if ($HTTP_GET_VARS['action'] == 'add_banner_cat') {

?>

 	<tr>
 	<td>

          <table border="0" width="100%" cellspacing="0" cellpadding="0">

            <tr>
              <td colspan="2" class="pageHeading">Add Affiliate Banner Group Type</td>
		    </tr>			
				
				<?php 
				echo tep_draw_form('product_queston_write', FILENAME_AFFILIATE_BANNER_GROUPS, 'action=addtourproccess', 'post', 'onSubmit="return checkForm1();"');
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
						
													<td width="25%" class="main"><b>Affiliate Banner Group Name:</b>&nbsp;<font color="#FF0000">(required)</font></td>
						
													<td width="75%" class="main"><?php echo tep_draw_input_field('affiliate_banners_group_name'); ?></td>
						
												  </tr>
												 
												  <tr> 
												<td >&nbsp;
													</td>
											  <td>
											   <input type="submit" name="submit" value="Add Affiliate Banner Group"> 
													</td>
						
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
						
													<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, tep_get_all_get_params(array('cPath','replay','page','action'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>
						
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
			  <td class="dataTableHeadingContent" >#ID</td>
			
                <td class="dataTableHeadingContent" >Affiliate Banner Group Name</td>

                <td class="dataTableHeadingContent" width="10%" align="center">Date<br><?php echo "<a  href=affiliate_banner_groups.php?sortorder=date&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=affiliate_banner_groups.php?sortorder=date-desc&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

				<!--<td class="dataTableHeadingContent" align="center"><?php //echo INDEX_FEATURE_STATUS; ?></td>-->

                <td class="dataTableHeadingContent" width="5%" align="right">Action</td>

              </tr>

<?php

switch (trim($HTTP_GET_VARS['sortorder'])) {
              
			  case "ans":
              $order = "affiliate_banners_group_id ";
              break;
              case "ans-desc":
              $order = "affiliate_banners_group_id DESC";
			   break;
              case "date":
              $order = "affiliate_banners_date_added";
              break;
              case "date-desc":
              $order = "affiliate_banners_date_added DESC";
              break;
              default:
              $order = "affiliate_banners_group_id";
              break;
          
          }


//echo  $order;

    $rows = 0;
	/*
	if(isset($HTTP_GET_VARS['sortorde']) && $HTTP_GET_VARS['sortorde']=''){
	
	}else 
	
	
*/

  //$question_query_raw = "select count(*) as count,q.affiliate_banners_group_id,q.products_id,q.question,q.date from " . TABLE_AFFILIATE_BANNERS_GROUPS_ANSWER . " as qa," . TABLE_AFFILIATE_BANNERS_GROUPS . " as q where q.affiliate_banners_group_id = qa.affiliate_banners_group_id  group by qa.affiliate_banners_group_id order by count";
 	
	
	$question_query_raw = "select *  from " . TABLE_AFFILIATE_BANNERS_GROUPS ." order by $order";

 // $question_split = new splitPageResults($question_query_raw, MAX_DISPLAY_NEW_REVIEWS);
$question_answers_query_numrows = 50;
define('MAX_DISPLAY_NEW_QUEASION','50');
$question_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_NEW_QUEASION, $question_query_raw, $question_answers_query_numrows);
  

  //if ($question_split->number_of_rows > 0) {

   // $question_query = tep_db_query($question_split->sql_query);

	$question_query = tep_db_query($question_query_raw);
    while ($question = tep_db_fetch_array($question_query)) {
	
	 if($rows == 0 && (!$action) && (!$selected_item) && !isset($HTTP_GET_VARS['affiliate_banners_group_id'])){
	 $HTTP_GET_VARS['affiliate_banners_group_id'] = $question['affiliate_banners_group_id'];  
	 }						

					

      $rows++;

      

      if ( ((@$HTTP_GET_VARS['affiliate_banners_group_id'] == $question['affiliate_banners_group_id'])) && (!$selected_item) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {

        $selected_item = $question;

      }

      if ( (is_array($selected_item)) && ($question['affiliate_banners_group_id'] == $selected_item['affiliate_banners_group_id']) ) {

		if(isset($HTTP_GET_VARS['affiliate_banners_group_id']) && $HTTP_GET_VARS['affiliate_banners_group_id'] != ''){
        echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNERS, 'cPath=' . $question['affiliate_banners_group_id']) . '\'">' . "\n";
		}else{
		 echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'affiliate_banners_group_id=' . $question['affiliate_banners_group_id']) . '\'">' . "\n";
		}
      } else {

        echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'affiliate_banners_group_id=' . $question['affiliate_banners_group_id']) . '\'">' . "\n";

      }

?>

 				<td class="dataTableContent"><?php echo $question['affiliate_banners_group_id']; ?></td>
				<td class="dataTableContent">
				<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, 'cPath=' . $question['affiliate_banners_group_id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $question['affiliate_banners_group_name'] . '</b>'; ?></td>
              

				<td class="dataTableContent" align="center">
				
				<?php //echo $question['date']; 
				
				
					$one = $question['affiliate_banners_date_added'];
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
				
                <td class="dataTableContent" align="right"><?php if ($question['affiliate_banners_group_id'] == $HTTP_GET_VARS['affiliate_banners_group_id']) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'affiliate_banners_group_id=' . $question['affiliate_banners_group_id'].'&'.tep_get_all_get_params(array('info', 'x', 'y', 'action','dealscat' , 'affiliate_banners_group_id'))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>

              </tr>

<?php

    } 

?>

              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $question_split->display_count($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, $HTTP_GET_VARS['page'], ''); ?></td>
                    <td class="smallText" align="right"><?php echo $question_split->display_links($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'affiliate_banners_group_id'))); ?></td>
                  </tr>

                </table></td>
              </tr>

            </table></td>

<?php

    $heading = array();

    $contents = array();

    switch ($HTTP_GET_VARS['action']) {

      case 'delete_banner_cat': //generate box for confirming a news article deletion

        $heading[] = array('text'   => '<b>Delete Affiliate Banner Group</b>');

        

        $contents = array('form'    => tep_draw_form('news', FILENAME_AFFILIATE_BANNER_GROUPS, '&action=delete_tour_type_confirm') . tep_draw_hidden_field('affiliate_banners_group_id', $HTTP_GET_VARS['affiliate_banners_group_id']));

       // $contents[] = array('text'  => TEXT_DELETE_ITEM_INTRO);

        $contents[] = array('text'  => '<br><b>' . $selected_item['affiliate_banners_group_name'] . '</b>');

        

        $contents[] = array('align' => 'center',

                            'text'  => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'affiliate_banners_group_id=' . $selected_item['affiliate_banners_group_id']).'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;



      default:

        if ($rows > 0) {

          if (is_array($selected_item)) { //an item is selected, so make the side box

            $heading[] = array('text' => '');



            $contents[] = array('align' => 'center', 

                                'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'action=add_banner_cat') . '">' . tep_image_button('button_new.gif', IMAGE_ADD) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'affiliate_banners_group_id=' . $selected_item['affiliate_banners_group_id'] . '&action=edit_banner_cat') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'affiliate_banners_group_id=' . $selected_item['affiliate_banners_group_id'] . '&action=delete_banner_cat') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a></br>');
	            $contents[] = array('text' => '<br>' . $selected_item['content']);

          }

        } else { // create category/product info

          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');



          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));

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

