<div id="mudidi">
   <div class="leftside otherpage">
    <div class="nei-leftside " >
        <div class="nei-leftside-top" ><b></b><span></span></div>     
        <div class="leftside-box">
	    <h3><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'档案')?></h3>
	    <div class="chufa-city">
       <ul class="mudidi-doc">
       <?php if(tep_not_null($rows['overview_info'])){?>
	   <li <?=$overview_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','field=overview&dg_categories_id='.$rows['dg_categories_id']);?>"><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'概况')?></a></li>
       <?php }?>
	   <?php if(tep_not_null($rows['map_image'])){?>
	   <li <?=$map_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.$rows['dg_categories_id'].'&field=map');?>"><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'地图')?></a></li>
       <?php }?>
	   <?php if(tep_not_null($rows['lodging_info'])){?>
	   <li <?=$lodging_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.$rows['dg_categories_id'].'&field=lodging');?>"><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'住宿')?></a></li>
	   <?php }?>
	   <?php if(tep_not_null($rows['traffic_info'])){?>
	   <li <?=$traffic_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.$rows['dg_categories_id'].'&field=traffic');?>"><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'交通')?></a></li>
	   <?php }?>
	   <?php if(tep_not_null($rows['shopping_info'])){?>
	   <li <?=$shopping_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.$rows['dg_categories_id'].'&field=shopping');?>"><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'购物')?></a></li>
	   <?php }?>
	   <?php if(tep_not_null($rows['food_info'])){?>
	   <li <?=$food_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.$rows['dg_categories_id'].'&field=food');?>"><?php echo db_to_html(tep_db_output($rows['dg_categories_name']).'美食')?></a></li>
	   <?php }?>
	   <?php if(tep_not_null($rows['local_features'])){?>
	   <li <?=$features_class?>><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.$rows['dg_categories_id'].'&field=features');?>"><?php echo db_to_html('当地特色')?></a></li>
	   <?php }?>
	   </ul>
        </div>
		
       <?php
	   if(tep_not_null($rows['weather'])){ //天气预报
	   ?>
	   <h3 class="left-side-title"><?php echo db_to_html('天气情况')?></h3>
       <div class="citycity">
	   <?php echo $rows['weather']?>
	   </div>
       <?php
	   }
	   ?>
	   
	   <?php
	   //热门景点
	   //说明如果当前目录没有热门景点就选上一级的目录的热门景点，如果上一级没有就继续向上直到有为止
		if(tep_not_null(trim($rows['hot_dg_categories_ids']))){
			$hot_dg_categories_ids = explode(',',trim($rows['hot_dg_categories_ids']));
		}else{
			$parent_c_ids = array();
			tep_get_parent_dg_categories($parent_c_ids,$rows['dg_categories_id']);
			$hot_dg_categories_ids = array();
			for($i=0; $i<count($parent_c_ids); $i++){
				$tmp_array = decide_hot_dg_categories_ids($parent_c_ids[$i]);
				if(is_array($tmp_array)){
					$hot_dg_categories_ids = array_merge($hot_dg_categories_ids, $tmp_array);
					//break;
				}
			}
		}
		
		if((int)count($hot_dg_categories_ids)){
			$d_ids = array();
			foreach((array)$hot_dg_categories_ids as $key => $val){
				if((int)$val && $val!= $rows['dg_categories_id']){
					$d_ids[] = (int)$val;
				}
			}
			$d_ids_str = implode(',',$d_ids);
		}
		

	   if(tep_not_null($d_ids_str)){
			$hot_cate_sql = tep_db_query('SELECT dg_categories_id, dg_categories_name FROM `destination_guide_categories` WHERE dg_categories_id in('.$d_ids_str.') AND dg_categories_state ORDER BY sort_order ASC, dg_categories_id DESC Limit 24 ');
			
	   ?>
	      <h3 class="left-side-title"><?php echo db_to_html('热门景点')?></h3>
	      <div class="chufa-city">
           <ul>
                <?php
				while($hot_cate_rows = tep_db_fetch_array($hot_cate_sql)){
				?>
				<li><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.(int)$hot_cate_rows['dg_categories_id'])?>" class="lanzi4"><?php echo db_to_html(tep_db_output($hot_cate_rows['dg_categories_name']));?></a></li>
                <?php
				}
				?>
            </ul>
            </div>
       <?php
	   }
	   ?>
	   
	    </div>
    <div class="nei-leftside-bottom"><b></b><span></span></div>
   <div class="clear"></div></div>
  </div>
  <div class="rightcontent" style=" width:712px;">
 
    <div class="illustrations" <?php echo $e_style?>>	
<h2>
<?php echo db_to_html($text_title);?>
</h2>

<p class="dazi" id="text_conttent"><?php echo db_to_html($text_conttent);?></p>
<p class="dazi" id="full_text_conttent" style="display:none"><?php echo db_to_html($full_text_conttent);?><br>
<a href="javascript:void(0)" onClick="show_all_text(1)" style="color:#3299DB"><?php echo db_to_html('收缩')?></a></p>

<script type="text/javascript">
function show_all_text(num){
	var text_con = document.getElementById('text_conttent');
	var full_text_con = document.getElementById('full_text_conttent');
	if(text_con!=null && full_text_con!=null){
		if(num==0){
			text_con.style.display ='none';
			full_text_con.style.display ='';
		}
		if(num==1){
			text_con.style.display ='';
			full_text_con.style.display ='none';
		}
		
	}
}
</script>
</div>

<?php
if($field=='overview'){
	$imges_sql = tep_db_query('SELECT * FROM `destination_guide_categories_images` WHERE dg_categories_id="'.(int)$rows['dg_categories_id'].'" ORDER BY `images_sort_order` ASC, images_id DESC ');
	$images_row = tep_db_fetch_array($imges_sql);
	if((int)$images_row['images_id']){
	if(preg_match('/^http:\/\//',$no1_image)){
		$no1_image = $images_row['images_name'];
	}else{
		$no1_image = 'images/destination_guide/'.$images_row['images_name'];
	}
?>    
	<DIV id="m_zhanshi_pic" style=" width:355px;">
                        <DIV id="show"><IMG id="showpic" src="<?=$no1_image?>" style="width:auto; margin-top:0px;"></DIV>
                        <DIV id="box">
                        <DIV id="box_l"><IMG src="image/jiantou1_l.gif"></DIV>
                        <DIV id="box_c">
                        <UL>
                          <?php
						  
						  $d_loop =0;
						  do{
						  	if(tep_not_null($images_row['images_name'])){
								if(preg_match('/^http:\/\//',$imges_name)){
									$imges_name = $images_row['images_name'];
								}else{
									$imges_name = 'images/destination_guide/'.$images_row['images_name'];
								}
						  ?>
						  <li><a href="javascript:void(0);"><img alt="" src="<?php echo $imges_name?>"></a></li>
                          <?php
						  		$d_loop++;
							}
						  }while($images_row = tep_db_fetch_array($imges_sql));
						  ?>
                        </UL>
                        </DIV>
                        <DIV id="box_r"><IMG src="image/jiantou1_r.gif"></DIV></DIV>
                        <DIV class="none" id="sorry" style="DISPLAY: none"></DIV>
                        <SCRIPT type=text/javascript>
											
							var box = document.getElementById("box_c"); 
							var boxl = document.getElementById("box_l"); 
							var boxr = document.getElementById("box_r"); 
							var ts = document.getElementById("sorry"); 
							i = 0; 
							boxl.onmouseover = function(){t=setInterval(scrollFunRight,60);boxl.className="down"} 
							boxl.onmouseout = function() {clearInterval(t);i=0;boxl.className="";ts.className="";} 
							boxr.onmouseover = function(){t=setInterval(scrollFunLeft,60);boxr.className="down";} 
							boxr.onmouseout = function() {clearInterval(t);i=0;boxr.className="";ts.className="";} 
							/**/ 
							var pic = document.getElementById("showpic"); 
							var imgs = document.getElementById("box_c").getElementsByTagName("img"); 
							for(i=0;i<imgs.length;i++) 
							{ //window.onload = function() {pic.src= imgs[0].src;} 
							imgs[i].onmouseover = function() {pic.src = this.getAttribute('src');} 
							imgs[i].onclick = function() {window.open(this.getAttribute('src'));} 
							pic.onclick = function() {window.open(this.getAttribute('src'));} 
							} 
						</SCRIPT>
                      </DIV>
<?php
	}
}
?>

   <?php
   //推荐产品 start
   
	if(tep_not_null(trim($rows['recommend_products_ids']))){
	
		$recommend_products_sql =  tep_db_query("select p.products_id, p.products_model, p.products_price, p.products_tax_class_id, pd.products_name  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd  where p.products_status = '1' and p.products_id = pd.products_id  and pd.language_id=1 limit 10 ");
	
   ?>
    <div class="mudidi-content">
    <h2><?php echo db_to_html('推荐线路')?></h2>
    <ul>
    <?php
	$r_loop = 0;
	while($recommend_products_rows = tep_db_fetch_array($recommend_products_sql)){
		if($r_loop%5==0 && (int)$r_loop){
			echo '</ul><ul class="mdd-produvt-list-r">';
		}
		$r_loop++;
	
	?>
	<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $recommend_products_rows['products_id']);?>" title="<?=db_to_html(tep_db_output($recommend_products_rows['products_name']))?>"><?php echo '['.$recommend_products_rows['products_model'].'] '.db_to_html(tep_db_output($recommend_products_rows['products_name']))?></a><span class="sp1"><?php echo $currencies->display_price($recommend_products_rows['products_price'],tep_get_tax_rate($recommend_products_rows['products_tax_class_id']))?></span></li>
    <?php
	}
	?>
    </ul>
    </div> 
   <?php
	}
   //推荐产品 end
   ?>
   
    <?php
	//旅游攻略 start
	$tours_guide_sql = tep_db_query('SELECT * FROM `tours_experience` te, `tours_experience_to_guide_categories` tetgc WHERE te.tours_experience_id = tetgc.tours_experience_id AND te.tours_experience_status="1" AND  tetgc.dg_categories_id = "'.$rows['dg_categories_id'].'" Group BY te.tours_experience_id ORDER BY te.sort_order, te.tours_experience_id DESC Limit 10 ');
	
	?>
	<div class="mudidi-content mudidi-gl">
    <h2><?php echo db_to_html('旅游攻略')?></h2>
    <ul>
    <?php
	$t_loop = 0;
	while($tours_guide_rows=tep_db_fetch_array($tours_guide_sql)){
		if($t_loop%5==0 && (int)$t_loop){
			echo '</ul><ul class="mdd-produvt-list-r">';
		}
		$t_loop++;
	?>
	<li><a href="<?php echo tep_href_link('tours-experience.php','tours_experience_id='.(int)$tours_guide_rows['tours_experience_id']) ?>"><?php echo db_to_html(tep_db_output($tours_guide_rows['tours_experience_title']));?></a></li>
    <?php
	}
	?>
    </ul>
    </div>
    <?php
	//旅游攻略 end
	
	?>

  </div>
  
</div>