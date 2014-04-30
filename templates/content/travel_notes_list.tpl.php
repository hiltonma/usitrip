<div class="jb_right">
      <div class="right_tb">

        <h3><a href="<?= $p_href?>" title="<?= $p_name;?>"><?= cutword($p_name,76);?></a></h3>
        
      </div>
	  
      <div class="jb_hf">
        <h3><?= $h3_2?></h3>
      </div>
     	  
<?php if ($travel_notes_split->number_of_rows > 0) {
	do{
?>     	  
     	
		<div class="jb_gone_df line1">
        <div class="jb_gone_df_l"><a href="<?= tep_href_link('travel_notes_detail.php','products_id='.(int)$products_id.'&travel_notes_id='.$travel_notes_rows['travel_notes_id'])?>" class="col_2" title="<?= db_to_html(tep_db_output($travel_notes_rows['travel_notes_title']))?>"><?= cutword(db_to_html(tep_db_output($travel_notes_rows['travel_notes_title'])),66)?></a></div>
        <div class="jb_gone_df_r">
          <p>
		  <?= substr($travel_notes_rows['added_time'],0,10)?>&nbsp;<?= db_to_html('ÆÀÂÛ£¨'.$travel_notes_rows['comment_num'].'£©');?>
		  </p>
        </div>
      </div>
<?php
	}while($travel_notes_rows = tep_db_fetch_array($travel_notes_query));
?>      
      <div class="jb_fenye line2">
        <div class="jb_fenye_l"><?php if($travel_notes_split->number_of_rows >'10'){echo TEXT_RESULT_PAGE . ' ' . $travel_notes_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'x', 'y'))); }?></div>
      </div>
<?php
}
?>
      	  	  
    </div>