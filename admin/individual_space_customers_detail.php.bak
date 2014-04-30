<?php
require('includes/application_top.php');
//$customer_id=(int)$_GET['i_customers_id'];




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
//提交添加/编辑相册
function create_update_album_confirm(form_obj){
	var form = form_obj;
	if(form.elements['create_ro_update'].value == "create"){
		form.elements['photo_books_id'].value = "";
	}

	if(form.elements['photo_books_name'].value.length<2){
		alert('<?= db_to_html("请输入相册名称！");?>');
		return false;
	}
	if(form.elements['photo_books_description'].value.length<2){
		alert('<?= db_to_html("请输入相册描述！");?>');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=process')) ?>");
	var form_id = form.id;
	ajax_post_submit(url,form_id);
}
//编辑相册
function update_album(books_id){
	var form = document.getElementById("create_album_form");
	var h3 = document.getElementById("action_h3");
	form.elements['photo_books_id'].value = books_id;
	form.elements['create_ro_update'].value = "update";
	var Photo_Books_Name = document.getElementById("photo_books_name_"+books_id);
	form.elements['photo_books_name'].value = Photo_Books_Name.innerHTML;
	var Photo_Books_Description = document.getElementById("photo_books_description_"+books_id);
	form.elements['photo_books_description'].value = Photo_Books_Description.innerHTML;
	h3.innerHTML = "<?= db_to_html('编辑相册');?>";
	showDiv('cr_photo_books');
}
//删除相册
function remove_album(books_id, dis_id){
	if(confirm("<?= db_to_html('是否真的删除该相册？该用户相册内的所有相片均被删除。')?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=remove_album')) ?>")+'&photo_books_id='+books_id+'&dis_id='+dis_id;
		ajax_get_submit(url);
		/*var tmp_obj = document.getElementById(dis_id);
		if(tmp_obj!=null){
			tmp_obj.parentNode.removeChild(tmp_obj);
		}*/
	}
}
//删除用户头像
function Del_customers_person_photo(i_c_id){
       if(confirm("<?= db_to_html('你是否真的要删除该用户的头像!')?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=remove_person_photo')) ?>")+'&i_customers_id='+i_c_id;
		ajax_get_submit(url);

       }

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
              <td class="pageHeading"><?php echo db_to_html($_GET['i_custmoers_name']).db_to_html('的个人中心')?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <td>
              <?php
              //$head_img = 'E:/my_work/CH_ShangHai_Server/usitrip_CH/branches/howard-dev/images/face/20100708081228_2137.jpg';
              $head_img = '';
              $head_img = tep_customers_face(db_to_html($_GET['i_custmoers_id']),$head_img);
              //echo $head_img;
             // echo DIR_WS_CATALOG_IMAGES .$head_img;
              $head_img_1 = 'images/' .$head_img;
              $individual_space_links = tep_href_link('individual_space.php','customers_id='.$_GET['i_custmoers_id']);
              //$individual_space_links=' http://localhost//individual_space.php?customers_id=2137';
              //echo  $individual_space_links;
              //$text_img_link = tep_href_link($head_img_1,'');
              //$text_img_link = eregi_replace('/admin/','', $text_img_link);
              $img_link = DIR_FS_CATALOG.$head_img_1;
              //echo $img_link;
              $individual_space_links = eregi_replace('/admin','', $individual_space_links);
              $WH = getimgHW3hw_wh($img_link,117,114);
              //$wh_array=getimagesize("E:/my_work/CH_ShangHai_Server/usitrip_CH/branches/howard-dev/images/face/20100708081228_2137.jpg");
              $wh_array=explode("@",$WH);
              //echo '<pre>';
              //print_r($wh_array);
              //echo '</pre>';

              ?>
              <fieldset>
		  <legend align="left"><?php echo db_to_html('个人头像');?></legend>
                  <table border="0" cellspacing="0" cellpadding="2">
             <tr>
                 <td valign="top">
                    <form action="<?php tep_href_link('individual_space_customers_detail.php','action=Update')?>" method="post" name="edit_form" id="edit_form">
                        <table border="0" cellspacing="1" cellpadding="2">
                            <div>
                          
                          <a href="<?= $individual_space_links?>"><?PHP echo tep_image(DIR_WS_CATALOG_IMAGES .$head_img, '',$wh_array[0],$wh_array[1], 'align="left" hspace="0" vspace="5" id="'.$_GET['i_custmoers_id'].'"')?></a>
                          <td nowrap class="dataTableContent">
		         [<a href="JavaScript:void(0);" onClick="Del_customers_person_photo(<?php echo $_GET['i_custmoers_id']?>); return false;">删除</a>]</td>
                           </div>
                        </table>
	            </form>
                 </td>
           
			
           
			
          </tr>
        </table>
              </fieldset>
              <?php
              /*用户相册部分start*/
              //取得用户的相册
              $where_books_exc = '';
              $photo_books_sql = tep_db_query('SELECT * FROM `photo_books` WHERE customers_id = "'.$_GET['i_custmoers_id'].'" '.$where_books_exc.' Order By photo_sum DESC, photo_books_id DESC');
              $photo_books = tep_db_fetch_array($photo_books_sql);


              ?>
              <fieldset>
		  <legend align="left"><?php echo db_to_html('个人相册');?></legend>
                  <table border="0" cellspacing="0" cellpadding="2">
             <tr>
                 <td valign="top">
                    <form action="<?php tep_href_link('individual_space_customers_detail.php','action=Update')?>" method="post" name="edit_form" id="edit_form">
                        <table border="0" cellspacing="1" cellpadding="2">
                           <div>
                            <?php
                            if(!(int)$photo_books['photo_books_id']){?>
                               <tr><td nowrap class="dataTableContent">该用户暂无创建任何相册</td></tr>
                           <?php
                           }else{?>
                     <div class="jb_grzx_photo">
                     <ul id="photo_books_ul">
          <?php
		 
		  do{
			$books_cover = "";
			if(tep_not_null($photo_books['photo_books_cover'])){
				$books_cover = 'photos/'.$photo_books['photo_books_cover'];
			}
                        $dis_none = '';
		        $photo_list_href_text = db_to_html(tep_db_output($photo_books['photo_books_name']));
                        $photo_list_href = tep_href_link('admin_photo_list.php','photo_books_id='.$photo_books['photo_books_id'].'&i_custmoers_id='.$_GET['i_custmoers_id']);
                        $thumbnails_img = get_thumbnails(DIR_FS_CATALOG.'images/'.$books_cover);
                        $s = strlen(DIR_FS_CATALOG)-1;
                        $l = strlen($thumbnails_img);
                        $thumbnails_img = substr($thumbnails_img,$s, $l-$s);
                        //echo $thumbnails_img;
                        $books_cover_link = DIR_FS_CATALOG.'images/'.$books_cover;
                        $WH = getimgHW3hw_wh($books_cover_link,144,109);
                        $wh_array=explode("@",$WH);

		  ?>
		  <li style="display:<?= $dis_none?>" id="photo_li_<?= $photo_books['photo_books_id']?>"> <a href="<?= $photo_list_href?>" class="jb_photo_a">
            <div class="jb_photo"><?PHP echo tep_image($thumbnails_img, '',$wh_array[0],$wh_array[1])?></div>
            </a>
            <p style="text-align:center" class="jb_photo_p col_5"><a href="<?= $photo_list_href?>" id="photo_books_name_<?= $photo_books['photo_books_id']?>" title="<?= $photo_list_href_text;?>"><?= cutword($photo_list_href_text,20)?></a>
			<br />
              <span><?= db_to_html('共'.$photo_books['photo_sum'].'张')?></span></p>
			  <p id="photo_books_description_<?= $photo_books['photo_books_id']?>" style="display:none"><?= db_to_html(tep_db_output($photo_books['photo_books_description']))?></p>
            	          <p style="text-align:center"><a href="JavaScript:void(0)" onclick="update_album(<?= $photo_books['photo_books_id']?>);" class="jb_fb_tc_bt_a"><?= db_to_html('编辑')?></a>&nbsp;<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="remove_album(<?= $photo_books['photo_books_id']?>, 'photo_li_<?= $photo_books['photo_books_id']?>')"><?= db_to_html('删除')?></a></p>
			
          </li>
          <?php
		  }while($photo_books = tep_db_fetch_array($photo_books_sql));
		  ?>
        </ul>
        
      </div>

                           <?php

                           }
                            ?>
                          
                         
                           </div>
                        </table>
	            </form>
                 </td>




          </tr>
        </table>
              </fieldset>
              <?php
              /*游记部分*/
              
              //取得用户已经完成的订单
                $orders_sql = 'SELECT o.customers_id , op.* FROM `orders` o, `orders_products` op WHERE o.customers_id ="'.$_GET['i_custmoers_id'].'" AND o.orders_status = "100006" AND op.orders_id = o.orders_id Group By op.products_id ';
                $orders_split = new splitPageResults_front($orders_sql, 10);
                $orders_query = tep_db_query($orders_split->sql_query);
                $orders_rows = tep_db_fetch_array($orders_query);

              ?>
              <fieldset>
		  <legend align="left"><?php echo db_to_html('游记');?></legend>
                  <table border="0" cellspacing="0" cellpadding="2">
             <tr>
                 <td valign="top">
                    
                       
                           <?php //用户去过的地方 start?>
	  
        <h4><?= db_to_html($_GET['i_custmoers_name'].'去过的地方')?></h4>
      
     <?php
	 if (!$orders_split->number_of_rows > 0) {	//无结果时

	 ?>
	 <div class="grzx_notes"><span><?= db_to_html('目前该用户还没有去过任何地方？');?></span></div>
	 
	 

	 <?php
		
	 }else{?>
	    <table border="0" width="100%" cellspacing="1" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                        <td class="dataTableHeadingContent" nowrap="nowrap">景点</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">出团时间</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">游记</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
                 </tr>
           
		<?
                 $num_i = 0;
               do{

	 		$products_name = db_to_html(tep_get_products_name($orders_rows['products_id']));
			//统计该用户在该团中发的游记数量
			$photos_sql = tep_db_query('SELECT count(*) as total FROM `travel_notes` WHERE customers_id="'.$_GET['i_custmoers_id'].'" AND products_id ="'.$orders_rows['products_id'].'" ');
			$travel_notes_total = tep_db_fetch_array($photos_sql);
			$travel_notes_total = $travel_notes_total['total'];
                        $products_link = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders_rows['products_id']);
                        $products_link = eregi_replace('/admin','', $products_link);
                        $travel_notes_link = tep_href_link('travel_notes_list.php','products_id='.$orders_rows['products_id'].'&customers_id='.$_GET['i_custmoers_id']);
                        $travel_notes_link = eregi_replace('/admin','',$travel_notes_link);
                        $bg_color = "#ECFFEC";
		        if((int)$num_i %2 ==0){
			   $bg_color = "#F0F0F0";
			}

	 ?>
           
	  
         <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                  <td class="dataTableContent"><a href="<?=$products_link?>" class="col_2" title="<?= $products_name?>"><?php echo cutword($products_name,160)?></a></td>
                  <td class="dataTableContent"><?php echo substr($orders_rows['products_departure_date'],0,10)?></td>
                  <?php
		  if((int)$travel_notes_total){
		  ?>
                  <td class="dataTableContent"><a href="<?=$travel_notes_link?>"><font color="blue"><?= db_to_html('游记（'.$travel_notes_total.'）').db_to_html('[点击去个人中心查看]')?></font></a></td>
                  <td class="dataTableContent"><a href="<?= tep_href_link('admin_travel_notes_list.php','products_id='.$orders_rows['products_id'].'&customers_id='.$_GET['i_custmoers_id']);?>"><?= db_to_html('[点击查看详情]');?></a></td>
		  <?php
                  }else{?>
                  <td class="dataTableContent"><?= db_to_html('游记（'.$travel_notes_total.'）')?></td>
                  <td class="dataTableContent"><a href=""><?= db_to_html('');?></a></td>
		  <?php }?>
                  
         </tr>
     <?php
                $num_i++;
	 	}while($orders_rows = tep_db_fetch_array($orders_query));?>
         </table>
         <?php
	 }
	 ?>
      <div class="jb_fenye line2">
        <div class="jb_fenye_l"><?php if(tep_db_num_rows($orders_query)>10){ echo TEXT_RESULT_PAGE . ' ' . $orders_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'x', 'y'))); }?></div>
      </div>

	  <?php //我去过的地方 end?>
                       
	            
                 </td>




          </tr>
        </table>
              </fieldset>

          </td>
      </tr>
     
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!--弹出层相册 start-->
<div class="center_pop" id="cr_photo_books" style="text-decoration:none; display:none">
  <form method="post" enctype="multipart/form-data" id="create_album_form" onsubmit="create_update_album_confirm(this); return false;">
  <input name="photo_books_id" type="hidden" value="" />
  <input name="create_ro_update" type="hidden" value="create" />
  <div>
     <div class="jb_fb_tc_bt">
       <h3 id="action_h3"><?php echo db_to_html('创建新相册')?></h3>&nbsp;&nbsp;
	   <button type="button" title="<?php echo db_to_html('关闭');?>" onclick="closeDiv('cr_photo_books')" class="icon_fb_bt"/></button>
    </div>
     <div class="jb_fb_tc_tab">
      <table>
	  <tr>
	  <td><?= db_to_html("相册名称：")?>
	  </td>
	  <td><?= tep_draw_input_field('photo_books_name','',' maxlength="98" size="76" ');?>
	  </td>
	  <tr>
	  <tr>
	  <td><?= db_to_html("相册描述：")?>
	  </td>
	  <td><?= tep_draw_textarea_field('photo_books_description','',50,5);?>
	  </td>
	  <tr>
	  <td>&nbsp;
	  </td>
	  <td><button type="submit" class="jb_fb_all myjb_content_sq1_button"><?= db_to_html('确定')?></button>
	  </td>

	  </tr>
	  </table>
     </div>
</div>
</form>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?


  class splitPageResults_front {
    var $sql_query, $number_of_rows, $current_page_number, $number_of_pages, $number_of_rows_per_page, $page_name;

/* class constructor */
 function splitPageResults_front($query, $max_rows, $count_key = '*', $page_holder = 'page', $show_old_pagin = 'false') {

		if($show_old_pagin == 'true'){

		    global $HTTP_GET_VARS, $HTTP_POST_VARS;

      $this->sql_query = $query;
      $this->page_name = $page_holder;

      if (isset($HTTP_GET_VARS[$page_holder])) {
        $page = $HTTP_GET_VARS[$page_holder];
      } elseif (isset($HTTP_POST_VARS[$page_holder])) {
        $page = $HTTP_POST_VARS[$page_holder];
      } else {
        $page = '';
      }

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->current_page_number = $page;

      $this->number_of_rows_per_page = $max_rows;

      $pos_to = strlen($this->sql_query);
      $pos_from = strpos($this->sql_query, ' from', 0);

      $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
      if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

      $pos_having = strpos($this->sql_query, ' having', $pos_from);
      if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

      $pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
      if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

      if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
        $count_string = 'distinct ' . tep_db_input($count_key);
      } else {
        $count_string = tep_db_input($count_key);
      }

	  $count_query = tep_db_query("select count(" . $count_string . ") as total " . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from, ($pos_to - $pos_from))));
      $count = tep_db_fetch_array($count_query);

      $this->number_of_rows = $count['total'];

      $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

      if ($this->current_page_number > $this->number_of_pages) {
        $this->current_page_number = $this->number_of_pages;
      }

      $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));
      //newer version of mysql can not handle neg number in limit, temp fix
      if ($offset < '0'){
         $offset = '1';
         }
      $this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;

		}else{

					  global $HTTP_GET_VARS, $HTTP_POST_VARS;

					  $this->sql_query = $query;
					  //EC - added variable to hold lower-case version of passed in query
					  $this->poscheck_query = strtolower($query);
					  $this->page_name = $page_holder;

					  if (isset($HTTP_GET_VARS[$page_holder])) {
						$page = $HTTP_GET_VARS[$page_holder];
					  } elseif (isset($HTTP_POST_VARS[$page_holder])) {
						$page = $HTTP_POST_VARS[$page_holder];
					  } else {
						$page = '';
					  }

					  if (empty($page) || !is_numeric($page)) $page = 1;
					  $this->current_page_number = $page;

					  $this->number_of_rows_per_page = $max_rows;

					  $pos_to = strlen($this->sql_query);
					  //EC - commented out original, replaced with check on lowercase query
					  // $pos_from = strpos($this->sql_query, ' from', 0);
					  $pos_from = strpos($this->poscheck_query, ' from', 0);

					  $pos_group_by = strpos($this->sql_query, ' group by', $pos_from);

					  //EC - commented out original, replaced with check on lowercase query
					  //$pos_group_by = strpos($this->sql_query, ' group by', $pos_from);
					  $pos_group_by = strpos($this->poscheck_query, ' group by', $pos_from);
					  if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;


					  //EC - commented out original, replaced with check on lowercase query
					  //$pos_having = strpos($this->sql_query, ' having', $pos_from);
					  $pos_having = strpos($this->poscheck_query, ' having', $pos_from);
					  if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

					  //EC - commented out original, replaced with check on lowercase query
					  //$pos_order_by = strpos($this->sql_query, ' order by', $pos_from);
					  $pos_order_by = strpos($this->poscheck_query, ' order by', $pos_from);
					  if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;


					  //EC - commented out original, replaced with check on lowercase query
					  //if (strpos($this->sql_query, 'distinct') || strpos($this->sql_query, 'group by')) {
					  // Phocea - Added condition on the count_key since count(distinct *) return an error in mysql!!
						if (strpos($this->poscheck_query, 'distinct') || strpos($this->poscheck_query, 'group by') && $count_key != '*') {
						$count_string = 'distinct ' . tep_db_input($count_key);
					  } else {
						$count_string = tep_db_input($count_key);
					  }

					  // Phocea - IF we have a group by we need to count how many groups are being returned and not simply the individual
					  // rows returned. So we wrap the original query around a count.
					  if (strpos($this->poscheck_query, 'group by')) {
						$count_query = tep_db_query("select count(*) as total from (select count(" . $count_string . ")" . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from)) .") as SubQ1");
					  } else {
						$count_query = tep_db_query("select count(" . $count_string . ") as total " . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from, ($pos_to - $pos_from))));
					  }
					  $count = tep_db_fetch_array($count_query);

					  $this->count_query = "select count(*) from (select count(" . $count_string . ")" . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from)).") as SubQ1";

					  $this->number_of_rows = $count['total'];

					  $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

					  if ($this->current_page_number > $this->number_of_pages) {
						$this->current_page_number = $this->number_of_pages;
					  }

					  $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));
					  if ($offset < 0)
					  {
					$offset = 0 ;
					  }
					  $this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;

		}// end of show new code
    }

/* class functions */

// display split-page-number-links
    function display_links($max_page_links, $parameters = '') {
      global $PHP_SELF, $request_type;

      $display_links_string = '';

      $class = ' ';
// BOM Mod:allow for a call when there are no rows to be displayed
      if ($this->number_of_pages > 0) {

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

// previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
        } else {
          $display_links_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '"  title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;';

      } else {  // if zero rows, then simply say that
        $display_links_string .= '&nbsp;<b>0</b>&nbsp;';
      }
// EMO Mod
      return $display_links_string;
    }

//amit added to display link for question and answers start

// display split-page-number-links
    function display_links_quesion($max_page_links, $parameters = '') {
      global $PHP_SELF, $request_type;

      $display_links_string = '';

      $class = ' ';
// BOM Mod:allow for a call when there are no rows to be displayed
      if ($this->number_of_pages > 0) {

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

// previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
        } else {
          $display_links_string .= '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '"  title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name .'=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;';

      } else {  // if zero rows, then simply say that
        $display_links_string .= '&nbsp;<b>0</b>&nbsp;';
      }
// EMO Mod
      return $display_links_string;
    }

//amit added to display link for question and answers end


//amit added to ajax base pagging start
    function display_links_ajax($max_page_links, $parameters = '',$link_ajax_pagename='product_listing_index_products_ajax.php',$ajax_frm_name='frm_slippage_ajax_product',$res_destination_div='div_product_listing') {
      global $PHP_SELF, $request_type;

      $display_links_string = '';

      //$class = ' ';
      $class = '';
// BOM Mod:allow for a call when there are no rows to be displayed
      if ($this->number_of_pages > 0) {

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&amp;';

// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number - 1).'&addhash=true\',\''.$res_destination_div.'\',\'true\');" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

// previous window of pages
      if ($cur_window_num > 1) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links).'&addhash=true\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
        } else {
          $display_links_string .= '&nbsp;<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . $jump_to_page.'&addhash=true\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1).'&addhash=true\',\''.$res_destination_div.'\',\'true\');"  title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number + 1).'&addhash=true\',\''.$res_destination_div.'\',\'true\');"   title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;';

      } else {  // if zero rows, then simply say that
        $display_links_string .= '&nbsp;<b>0</b>&nbsp;';
      }
// EMO Mod
      return $display_links_string;
    }


    function display_links_ajax_nextprev_only($max_page_links, $parameters = '',$link_ajax_pagename='product_listing_index_products_ajax.php',$ajax_frm_name='frm_slippage_ajax_product',$res_destination_div='div_product_listing') {
      global $PHP_SELF, $request_type;

      $display_links_string = '';

      $class = ' ';
// BOM Mod:allow for a call when there are no rows to be displayed
      if ($this->number_of_pages > 0) {

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&amp;';

// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number - 1).'\',\''.$res_destination_div.'\',\'true\');" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . '<img src="image/pre_wht.gif" />' . '</a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

//no need of this links to traveler photos page -- condition
/*
// previous window of pages

	  if ($cur_window_num > 1) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
        } else {
          $display_links_string .= '&nbsp;<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . $jump_to_page.'\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

	  //}//no need of this links to traveler photos page -- condition end*/

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . '<img src="image/next_wht.gif" />' . '</a>&nbsp;';

      } else {  // if zero rows, then simply say that
        $display_links_string .= '&nbsp;<b>0</b>&nbsp;';
      }
// EMO Mod
//echo $display_links_string;
      return $display_links_string;
    }


//amit added to ajax base pagging end


//amit added for paggin without hash link start
 function display_links_ajax_withouthash($max_page_links, $parameters = '',$link_ajax_pagename='product_listing_index_products_ajax.php',$ajax_frm_name='frm_slippage_ajax_product',$res_destination_div='div_product_listing') {
      global $PHP_SELF, $request_type;

      $display_links_string = '';

      $class = ' ';
// BOM Mod:allow for a call when there are no rows to be displayed
      if ($this->number_of_pages > 0) {

      if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&amp;';

// previous button - not displayed on first page
      if ($this->current_page_number > 1) $display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number - 1).'\',\''.$res_destination_div.'\',\'true\');" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

// check if number_of_pages > $max_page_links
      $cur_window_num = intval($this->current_page_number / $max_page_links);
      if ($this->current_page_number % $max_page_links) $cur_window_num++;

      $max_window_num = intval($this->number_of_pages / $max_page_links);
      if ($this->number_of_pages % $max_page_links) $max_window_num++;

//no need of this links to traveler photos page -- condition

// previous window of pages

	  if ($cur_window_num > 1) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

// page nn button
      for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
        if ($jump_to_page == $this->current_page_number) {
          $display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
        } else {
          $display_links_string .= '&nbsp;<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . $jump_to_page.'\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
        }
      }

// next window of pages
      if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

	  //}//no need of this links to traveler photos page -- condition end

// next button
      if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;';

      } else {  // if zero rows, then simply say that
        $display_links_string .= '&nbsp;<b>0</b>&nbsp;';
      }
// EMO Mod
//echo $display_links_string;
      return $display_links_string;
    }

//amit added for paggin without hash link end

// display number of total products found
    function display_count($text_output) {
      $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
      if ($to_num > $this->number_of_rows) $to_num = $this->number_of_rows;

      $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

      if ($to_num == 0) {
        $from_num = 0;
      } else {
        $from_num++;
      }

      return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
    }

		//Added to display providers listing ------- Start
function display_links_providers($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page') {
      global $PHP_SELF;
	  $action_page=DIR_WS_PROVIDERS.basename($PHP_SELF);

      if ( tep_not_null($parameters) && (substr($parameters, -1) != '&') ) $parameters .= '&';

// calculate number of pages needing links
      $num_pages = ceil($query_numrows / $max_rows_per_page);

      $pages_array = array();
      for ($i=1; $i<=$num_pages; $i++) {
        $pages_array[] = array('id' => $i, 'text' => $i);
      }

      if ($num_pages > 1) {
        $display_links = tep_draw_form('pages', basename($PHP_SELF), '', 'get');

        if ($current_page_number > 1) {
          $display_links .= '<a href="' . tep_href_link($action_page, $parameters . $page_name . '=' . ($current_page_number - 1), 'SSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
        } else {
          $display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
        }

        $display_links .= sprintf(TEXT_RESULT_PAGE_PROVIDERS, tep_draw_pull_down_menu($page_name, $pages_array, $current_page_number, 'onChange="this.form.submit();"'), $num_pages);

		if(!tep_not_null($current_page_number))
			$current_page_number=1;

        if (($current_page_number < $num_pages) && ($num_pages != 1)) {
          $display_links .= '&nbsp;&nbsp;<a href="' . tep_href_link($action_page, $parameters . $page_name . '=' . ($current_page_number + 1), 'SSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
        } else {
          $display_links .= '&nbsp;&nbsp;' . PREVNEXT_BUTTON_NEXT;
        }

        if ($parameters != '') {
          if (substr($parameters, -1) == '&') $parameters = substr($parameters, 0, -1);
          $pairs = explode('&', $parameters);
          while (list(, $pair) = each($pairs)) {
            list($key,$value) = explode('=', $pair);
            $display_links .= tep_draw_hidden_field(rawurldecode($key), rawurldecode($value));
          }
        }

        if (SID) $display_links .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

        $display_links .= '</form>';
      } else {
        $display_links = sprintf(TEXT_RESULT_PAGE_PROVIDERS, $num_pages, $num_pages);
      }
      return $display_links;
    }
		//Added to display providers listion ------- End
  }


##名称：通用分页类
##功能：显示如 |< << [1] 2 [3] [4] [5] [6] [7] [8] [9] [10] ... >> >|之类的页码，并可单击页数打开页面。
//new set_pagination($from,$where,$totalRows_String,$now_page,$pageNum_String);
define('FIRST_PAGE','首页');
define('PREVIOUS_PAGE','上一页');
define('NEXT_PAGE','下一页');
define('FINAL_PAGE','尾页');
define('QUICK_TO','快速到');
define('TO_PAGE','页');

class set_pagination
{
	//总页数
	var $totalPages;
	//总记录数
	var $totalRows;
	//当前页是第几页默认为第1页
	var $now_page;
	//在url显示的当前页变数的名称
	var $pageNum_String;
	//SQL查询的开始行数
	var $startRow=0;
	//当页结束行数
	var $endRow = 0;
	//到达页的表单名称
	var $form_name_and_id;
	//每屏显示几个页码数位，默认为6，必须是偶数
	var $dis_sum;
	//设置页码文字大小默认为12px;
	var $text_size;
	//非简化方式显示开关，1为完全显示，0为以简化方式显示
	var $full;
	//当前页面档案名
	var $PhpSelf;

	function set_pagination($from,$where, $now_page='1',$pageNum_String='page',$form_name_and_id='form_page',$dis_sum='6',$text_size='12px',$full=1,$maxRows = MAX_DISPLAY_SEARCH_RESULTS){
		$this->now_page= abs(intval($now_page));
		if($this->now_page < 1){ $this->now_page=1;}
		$this->pageNum_String=(string)$pageNum_String;
		$this->form_name_and_id=$form_name_and_id;
		$this->dis_sum=(int)$dis_sum;
		if($this->dis_sum % 2!=0 || $this->dis_sum <2 ){ $this->dis_sum=20; }
		$this->text_size=$text_size;
		$this->full=$full;
		$this->PhpSelf = preg_replace('/.*\//','/',$_SERVER['SCRIPT_FILENAME']);
		//$this->PhpSelf = preg_replace('/\.php/','.html',$this->PhpSelf);

		//总记录数
        $count_query = tep_db_query("select count(*) as total from $from $where ");
		$this->totalRows = tep_db_result($count_query,"0","total");
		//if($this->totalRows<1){ echo "总记录为0"; }
		//总页数
		$this->totalPages=ceil(($this->totalRows) / $maxRows );
		//SQL查询的开始行数
		$this->startRow= ($this->now_page -1) * $maxRows;
		//结束行数
		$this->endRow= min(( $this->startRow + $maxRows ),$this->totalRows);

		//如果当前页大於总页数则立即返回到最后一页
		if($now_page > $this->totalPages && $this->totalRows>0){
			$m=$pageNum_String.'='.$now_page;
			$r=$pageNum_String.'='.$this->totalPages;
			$_SERVER['QUERY_STRING']=ereg_replace($m,$r,$_SERVER['QUERY_STRING']);
			$Chg_page = $this->PhpSelf."?".$_SERVER['QUERY_STRING'];
			header("Location: $Chg_page");
			exit;
		}

	}


	//替换从地址栏传来的有关页&&总记录$_GET变数
	function queryString()
	{
		$queryString_RecSQL = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
		  $params = explode("&", $_SERVER['QUERY_STRING']);
		  $newParams = array();
		  foreach ($params as $param) {
			if (stristr($param, $this -> pageNum_String ) == false ) {
			  array_push($newParams, $param);
			}
		  }
		  if (count($newParams) != 0) {
			$queryString_RecSQL = "&amp;" . implode("&", $newParams);
		  }
		}
		//$queryString_RecSQL ="&amp;".$this -> totalRows_String."=".$this -> totalRows . $queryString_RecSQL;
		return $queryString_RecSQL;
	}

	//分页函数
	function pagination()
	{
		//如果$full=0以最简化的方式显示
		$page = $this -> now_page;


		$table_top = '<table border="0" cellspacing="0" cellpadding="0" style="margin:0px;"><tr><td valign="bottom"><dl>';
		for($I=1; $I<=min(($this -> totalPages + 1 - ($this -> now_page-1) + (($this -> dis_sum/2)-1) ),$this -> dis_sum); $I++)
		//for($I=1; $I<=100; $I++)
		{
			if($page >=( ($this -> dis_sum/2)+1) )
			{
				if(($page - ($this -> dis_sum/2))== $this -> now_page )
				{
					$display_pages .= '<dd><span style="font-size:' . $this -> text_size . '; background-color:#FF6600; color:#FFFFFF">'.($page - ($this -> dis_sum/2)).'</span></dd> ';
				}else
				{
					$to1page = $this->PhpSelf."?".$this -> pageNum_String."=".($page-($this -> dis_sum/2)) . $this-> queryString();
					//$display_pages .= '<a class="font_14" href="'.$to1page.'">['.($page-($this -> dis_sum/2)).']</a> ';
					$display_pages .= '<dd><a class="font_14" href="'.$to1page.'">'.($page-($this -> dis_sum/2)).'</a></dd> ';
				}
			}
			$page++;
		}
		//if($page-($this -> dis_sum/2)<= $this -> totalPages ) { $display_pages = $display_pages.'<span style="font-size:' . $this -> text_size . '">...</span> ';}

		$first_href = $this->PhpSelf."?".$this -> pageNum_String."=1" . $this-> queryString();
		$back_href = $this->PhpSelf."?".$this -> pageNum_String."=" . max(0, ($this -> now_page - 1)) . $this-> queryString();
		$next_href = $this->PhpSelf."?".$this -> pageNum_String."=" . min(($this -> totalPages), $this -> now_page+1) . $this-> queryString();
		$end_href =  $this->PhpSelf."?".$this -> pageNum_String."=" . ($this -> totalPages) . $this-> queryString();
		$go_1 = '<dd><a class="font_14" href="' . $first_href .'"><span  style="font-size:' . $this -> text_size . '" title="First">'.FIRST_PAGE.'</span></a></dd> <dd><a class="font_14" href="' . $back_href .'"><span  style="font-size:' . $this -> text_size . '" title="Previous">'.PREVIOUS_PAGE.'</span></a> </dd>';
		$go_2 = '<dd><a class="font_14" href="' . $next_href .'"><span  style="font-size:' . $this -> text_size . '" title="Next">'.NEXT_PAGE.'</span></a></dd> <dd><a class="font_14" href="' . $end_href .'"><span  style="font-size:' . $this -> text_size . '" title="Final">'.FINAL_PAGE.'</span></a></dd>';
		$go_page_from = '<form action="'.$this->PhpSelf.'" method="get" name="'.$this -> form_name_and_id.'" id="'.$this -> form_name_and_id.'" style=" margin:0px; padding:0px;">';

		foreach($_GET as $b => $c){
			if($b!="GO" && $b!=$this -> pageNum_String && $b!=""){
			$input.= '<input name="'.$b.'" type="hidden" value="'.$c.'" />';
			}
		}

		$go_page_from1 = '
</dl>
</td>

<!--隐藏快速到的输入框
<td valign="bottom">
&nbsp;'.QUICK_TO.'
</td>
<td valign="bottom">
<input name="'.$this -> pageNum_String.'" type="text" size="3" class="text_1_sel text_border" style="font-size:12px; ime-mode:disabled; "  value="'.$this -> now_page.'" maxlength="10" />
'.$input.'
</td>
<td valign="bottom">
'.TO_PAGE.'
</td>
<td valign="bottom">
<input name="button" class="pl_button1" value="GO" type="submit" title="GO">
</td>

<td valign="bottom">
<input name="GO" type="hidden" id="GO" value="GO" />

</td>
-->

</tr>
</table>
</form>';
		if($this ->full ==0){ $go_page_from=""; $go_page_from1="</td></tr></table>"; $display_pages=""; }

		if($this -> now_page > 1 ){ $display_pages = $go_1 . $display_pages;}
		if(($this -> now_page-1) < $this -> totalPages-1 ){ $display_pages .= $go_2;}
		//echo $go_page_from . $display_pages . $go_page_from1;
		//echo $display_pages;
		return ($go_page_from . $table_top . $display_pages . $go_page_from1);
	}
}
?>

