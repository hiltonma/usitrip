<?php
//退出时也让老站退出
echo $old->logoff();
?>
<?php //echo tep_get_design_body_header(HEADING_TITLE); ?>
					 <!-- content main body start -->
					 		<table width="970"  border="0" cellspacing="0" cellpadding="0">
														
							  <tr>
								<td> 

    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr style="display:none">
            <td valign="middle" class="pageHeading"><?php echo db_to_html('登出')?></td>
          </tr>
          <tr>
            <td height="150" class="main"  style="padding-bottom:8px; background-repeat:repeat-x; background-position:top; background-color:#FFFFFF" valign="middle"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          
              <tr>
                <td align="center"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td align="center" class="main"><?php echo TEXT_MAIN; ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE,'align="absmiddle"') . '</a>'; ?></td>
              </tr>
              <tr><td align="center" style="color:#f60;font-size:14px;vertical-align:middle;"><span id="timer" style="font-weight:700;color:#c00;">5</span><?php echo db_to_html("之后自动跳转到首页");?></td></tr>
              <script type="text/javascript">
			  var timer = 5;
			  <?php
			  $gourl = tep_href_link('index.php');
			  if (isset($_GET['ret'])) {
				$url = $_GET['ret'];
				$url = explode('/',$url);
				if (isset($url[2]) && $url[2] == $_SERVER['SERVER_NAME']) {
					$gourl = $_GET['ret'];
				}
			  }?>
			  var goUrl = "<?php echo $gourl; ?>";
			  function go(){
			  	if(timer == 0){
					location.href =  goUrl;
				}else{
					timer--;
					jQuery("#timer").html(timer);
					setTimeout("go()",1000);
				}
			  }
			  jQuery(function(){
			  	go();
			  });
			  </script>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>								</td>
							  </tr>
							</table>
					 		<!-- content main body end -->
<?php //echo tep_get_design_body_footer();?>
