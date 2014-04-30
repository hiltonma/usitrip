		<?php
		//记录最新访问结伴同游个人中心的客人
		if((int)$customer_id && $customer_id!=$customers_id && (int)$customers_id){
			tep_db_query('INSERT INTO `travel_companion_guest_history` ( `guest_customes_id`, `customes_id`, `add_date` ) VALUES ("'.$customer_id.'", "'.$customers_id.'", NOW());');
			
		}
		if(!tep_not_null($customers_id)){
			$customers_id = $customer_id;
		}
		$sql = tep_db_query('SELECT * FROM `travel_companion_guest_history` WHERE customes_id = "'.$customers_id.'" Group By guest_customes_id Order By tcgh_id DESC limit 10 ');
		$rows_guest = tep_db_fetch_array($sql);
		if((int)$rows_guest['tcgh_id']){
		?>
		<div class="box2">
	 	<img src="image/box2_bg_l.jpg" class="box_f_l">
        <h3><?php echo db_to_html('最新访客')?></h3>
		<img src="image/box2_bg_r.jpg" class="box_f_r">
        </div>
        <div class="item_left_sec1" style="width: 163px;">
          <ul>
		
		<?php 
			do{
				$guest_gender = tep_customer_gender($rows_guest['guest_customes_id']);	
				$guest_gender_str = '';
				$face_img_name = 'image/tx_b_s.gif';
				if(strtolower($guest_gender)=='f'){
					$guest_gender_str = '女士';
					$face_img_name = 'image/tx_g_s.gif';
				}
				if(strtolower($guest_gender)=='m'){
					$guest_gender_str = '先生';
					$face_img_name = 'image/tx_b_s.gif';
				}
				$face_img_name = tep_customers_face($rows_guest['guest_customes_id'], $face_img_name);
				
		?>
              <li><div class="item_left_sec1_img"><div class="item_left_sec1_img_m"><a href="<?= tep_href_link('individual_space.php','customers_id='.$rows_guest['guest_customes_id'])?>"><img  src="<?= $face_img_name?>" <?php echo getimgHW3hw($face_img_name,50,50)?> /></a></div></div>
              <p class="item_left_sec1_p">
			  <a href="<?= tep_href_link('individual_space.php','customers_id='.$rows_guest['guest_customes_id'])?>" class="t_c"><?php echo db_to_html(tep_customers_name($rows_guest['guest_customes_id']))?></a> <?= db_to_html($guest_gender_str);?><br />
                <?php if(strtr($rows_guest['add_date'],array('-'=>'', ' '=>'',':'=>''))>0){ echo substr($rows_guest['add_date'],0,10);}?></p>
            </li>
		
		<?php
			}while($rows_guest = tep_db_fetch_array($sql));
		?>
          </ul>
        </div>
		
		<?php
		}
		?>
