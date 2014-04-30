
<div class="jb_grzx_yj_bt">
    <h3><?= db_to_html("大家关注的相册");?></h3>
 </div>
 <div class="jb_grzx_bt1"><span class="guanzhu2"><?= db_to_html("最受关注的相册");?></span></div>
 <div class="jb_grzx_photo">
	 <ul>
        <?php
		if($hot_photo_books['photo_books_id']){
			$do_loop = 0;
			do{
				$books_cover = "image/photo_1.gif";
				if(tep_not_null($hot_photo_books['photo_books_cover'])){
					$books_cover = 'images/photos/'.$hot_photo_books['photo_books_cover'];
				}
				$href_a_j = tep_href_link('photo_list.php','photo_books_id='.$hot_photo_books['photo_books_id']);
				$href_a_t = db_to_html(tep_db_output($hot_photo_books['photo_books_name']));
				if((int)$do_loop && $do_loop%5==0){ echo '</ul><ul>';}
				
				//$photo_sum = get_photo_books_sum($hot_photo_books['photo_books_id']);
				//tep_db_query('update photo_books SET `photo_sum` = "'.(int)$photo_sum.'" WHERE photo_books_id="'.(int)$hot_photo_books['photo_books_id'].'"');
		?>
		 <li> <a href="<?= $href_a_j;?>" class="jb_photo_a">

            <div class="jb_photo"><img src="<?= get_thumbnails($books_cover);?>" <?php echo getimgHW3hw($books_cover,145,109)?> /></div>
            </a>
            <p class="jb_photo_p col_5"><a href="<?= $href_a_j;?>" title="<?= $href_a_t;?>"><?= cutword($href_a_t,20)?></a><br />
             <span><?= db_to_html('共'.$hot_photo_books['photo_sum'].'张')?></span></p>
         </li>
         <?php
			$do_loop++;
			}while($hot_photo_books = tep_db_fetch_array($hot_books_query));
		}
		 ?>
     </ul>
        
  </div>
       <div class="jb_grzx_bt1"><span class="guanzhu2"><?= db_to_html("全部相册");?></span></div>
 <div class="jb_grzx_photo">
     
	 <ul>
        <?php
		if($all_photo_books['photo_books_id']){
			$do_loop = 0;
			do{
				$books_cover_0 = "image/photo_1.gif";
				if(tep_not_null($all_photo_books['photo_books_cover'])){
					$books_cover_0 = 'images/photos/'.$all_photo_books['photo_books_cover'];
				}
				$href_a_j = tep_href_link('photo_list.php','photo_books_id='.$all_photo_books['photo_books_id']);
				$href_a_t = db_to_html(tep_db_output($all_photo_books['photo_books_name']));
				if((int)$do_loop && $do_loop%5==0){ echo '</ul><ul>';}
				
				$photo_sum = get_photo_books_sum($all_photo_books['photo_books_id']);
				tep_db_query('update photo_books SET `photo_sum` = "'.(int)$photo_sum.'" WHERE photo_books_id="'.(int)$all_photo_books['photo_books_id'].'"');

		?>
		 <li> <a href="<?= $href_a_j;?>" class="jb_photo_a">

            <div class="jb_photo"><img src="<?= get_thumbnails($books_cover_0);?>" <?php echo getimgHW3hw($books_cover_0,145,109)?> /></div>
            </a>
            <p class="jb_photo_p col_5"><a href="<?= $href_a_j;?>" title="<?= $href_a_t;?>"><?= cutword($href_a_t,20)?></a><br />
             <span><?= db_to_html('共'.$all_photo_books['photo_sum'].'张')?></span></p>
         </li>
         <?php
			$do_loop++;
			}while($all_photo_books = tep_db_fetch_array($all_books_query));
		}
		 ?>
     </ul>
     
     
  </div>
<div class="jb_fenye">
    <div class="jb_fenye_l">
	<?php if($all_books_split->number_of_pages >1 ){ echo TEXT_RESULT_PAGE . ' ' . $all_books_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'x', 'y'))); }?>
	</div>

  </div>
