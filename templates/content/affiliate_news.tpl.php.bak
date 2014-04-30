   <?php
   include(DIR_FS_MODULES . FILENAME_AFFILIATE_NEWS);
   $AffiliateNews = getAffiliateNews();
   $info_box_contents = array();
   /*
   for ($i=0, $n=sizeof($AffiliateNews); $i<$n; $i++) {
      $info_box_contents[$i] = array('align' => 'left',
                                   'params' => 'class="smallText" valign="top"',
                                   'text' => '<b>' . db_to_html($AffiliateNews[$i]['title']) . '</b> - <i>' . tep_date_long($AffiliateNews[$i]['date']) . '</i><br>' . nl2br(db_to_html(tep_db_output($AffiliateNews[$i]['content']))) . '<br>');
    }
   */
   ?>


<?php ob_start();?>
<div class="union">
	  
<!--所有公告开始-->  
      <div class="announcement">
	  
	  <?php for ($i=0, $n=sizeof($AffiliateNews); $i<$n; $i++) {?>
	  <dl class="announcementD">
	  <dt><?= $AffiliateNews[$i]['title'];?></dt>
	  <dd><?= (tep_db_output($AffiliateNews[$i]['content']));?></dd>
	  <dd><i><?= html_to_db(tep_date_long($AffiliateNews[$i]['date']));?></i></dd>
	  </dl>
	  <?php }?>
	  
      </div>
	  
<!--所有公告结束-->
	  
	  
    </div>

<?php echo  db_to_html(ob_get_clean());?>