<?php
require('includes/application_top.php');
//用户名称
$customers_name = tep_customers_name($_GET['customers_id']);
//用户id
$customers_id = (int)$_GET['customers_id'];
//产品id
$products_id = (int)$_GET['products_id'];
$p_name = db_to_html(tep_get_products_name($products_id));
//取得用户在该产品内的游记列表
$travel_notes_sql = 'SELECT * FROM `travel_notes` WHERE products_id="'.$products_id.'" AND customers_id="'.$customers_id.'" Order By travel_notes_id DESC ';
/*
$travel_notes_split = new splitPageResults_front($travel_notes_sql, 40);
$travel_notes_query = tep_db_query($travel_notes_split->sql_query);
$travel_notes_rows = tep_db_fetch_array($travel_notes_query);*/
$companion_query_numrows = 0;
$companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $travel_notes_sql, $companion_query_numrows);
$travel_notes_query = tep_db_query($travel_notes_sql);
$travel_notes_rows = tep_db_fetch_array($travel_notes_query);








?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<link rel="stylesheet" type="text/css" href="includes/new_travel_companion_index.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/usitrip-tabs-2009-06-19.js"></script>
<script type="text/javascript" src="includes/javascript/menujs-2008-04-15-min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/ajx.js"></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript">
<!--
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
function del_travel_notes(notte_id){	//删除游记
	if(confirm("<?php echo db_to_html("删除这个游记，将会同时删除该游记下面的所有评论，确定删除吗？");?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=del_travel_notes_list')) ?>");
		url += "&travel_notes_id="+notte_id;
		ajax_get_submit(url);
	}
	return false;
}


//-->
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

    <!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
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
              <td class="pageHeading"><?php echo db_to_html($customers_name.'的游记')?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <td>
              <fieldset>
		  <legend align="left" title="<?= $p_name;?>"><?= cutword($p_name,140);?></legend>
                  <table border="0" width="100%" cellspacing="1" cellpadding="2">
                      <tr class="dataTableHeadingRow">
                        <td class="dataTableHeadingContent" nowrap="nowrap">游记题目</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">发表时间</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">评论</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
                    </tr>
              <?php 
               $num_i = 0;
              if (1 > 0) {
                        
                    do{
                        $bg_color = "#ECFFEC";
		        if((int)$num_i %2 ==0){
			   $bg_color = "#F0F0F0";
			}
                        $admin_travel_notes_detail_link = tep_href_link('admin_travel_notes_detail.php','travel_notes_id='.$travel_notes_rows['travel_notes_id']);


            ?>


                    <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                        <td class="dataTableContent"><a href="<?=$admin_travel_notes_detail_link?>" class="col_2" title="<?= db_to_html(tep_db_output($travel_notes_rows['travel_notes_title']))?>"><?= cutword(db_to_html(tep_db_output($travel_notes_rows['travel_notes_title'])),100)?></a></td>
                        <td class="dataTableContent"><?= substr($travel_notes_rows['added_time'],0,10)?></td>
                        <td class="dataTableContent"><?= db_to_html('评论（'.$travel_notes_rows['comment_num'].'）');?></td>
                        <td class="dataTableContent"><a href="<?=$admin_travel_notes_detail_link?>"><font color="blue"><?= db_to_html('[查看详情]');?></font></a>&nbsp;<a href="JavaScript:void(0)" onclick="del_travel_notes(<?= (int)$travel_notes_rows['travel_notes_id']?>);"><font color="blue"><?php echo db_to_html('[删除]');?></font></a></td>
                    </tr>
            <?php
                    $num_i++;
                    }while($travel_notes_rows = tep_db_fetch_array($travel_notes_query));
            ?>
                   <tr>

                                                    <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                            <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                          </tr>
                                        </table></td>
                                      </tr>
            <?php
            }
            ?>
                  </table>
                 
              </fieldset>
          </td>
      </tr>




    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<div id="EditPotoDiv" class="center_pop" style="display:none">
		<form id="EditPotoForm" action="" method="post" enctype="multipart/form-data">
		<div>
		<div class="jb_fb_tc_bt"><h3 id="action_h3"><?= db_to_html('编辑');?></h3><button class="icon_fb_bt" onclick="closeDiv('EditPotoDiv')" title="<?= db_to_html('关闭')?>" type="button"></button></div>
		<div class="jb_fb_tc_tab">
		<div class="sc_none">
			<div class="sc_item">
				<?= tep_draw_hidden_field('photo_name',$photos[$i_now]['name'],' id="photo_name" title="'.db_to_html('请上传相片').'" ');?>
				</div>


		</div>
				   <div class="sc_text">
					<div class="sc_item"><p><?= db_to_html('标题：')?><?= tep_draw_input_field('photo_title',strip_tags($photos[$i_now]['title']),' class="text5" title="'.db_to_html('请输入相片标题').'" ');?></p></div>
					<div class="sc_item"><p><span class="v_top"><?= db_to_html('内容：')?></span><?= tep_draw_textarea_field('photo_content','virtual',50,5,strip_tags($photos[$i_now]['content']),' class="textarea4" title="'.db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。').'"');?>
					<br />
					<?php echo tep_draw_hidden_field('update_photo_action',"true");?>
					<?php echo tep_draw_hidden_field('photo_books_id',$photo_books_id);?>
					<?php echo tep_draw_hidden_field('photo_id',$photo_id);?>

					<button class="jb_fb_all" id="submit_photo_button" type="submit" style="margin-left:36px; margin-top:10px;"><?php echo db_to_html('确定')?></button>
					<img style="display: none;" src="image/snake_transparent.gif" id="load_icon">
					</p></div>
				</div>
			</div>
		</div>
		</form>
		</div>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');


?>
<script type="text/JavaScript">
<!--

-->
</script>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>