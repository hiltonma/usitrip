<?php
ob_start();
?>
<div id="abouts">
<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/about_left.php');
		?>
        <div class="abouts_right">
        	<div class="aboutsTit">
            	<ul>
                	<li>景点介绍</li>
                </ul>
        </div>
            <div class="aboutsCont ">
                <div class="about5_1" style="padding-top:0;">
                <div class="imgs"><span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=25_55');?>">美国东海岸</a></span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=25_55');?>"><img src="/image/nav/abouts_img17.jpg" alt="美国东海岸" width="200" height="172" /></a></div>
                <div class="lists">
                    <ul>
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 25);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=25_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                    </ul>
                </div>
                
                
                </div>
                <div class="about5_1 background_f9f9f9">
                <div class="imgs"><span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_29');?>">美国西海岸</a></span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_29');?>"><img src="/image/nav/abouts_img18.jpg" alt="美国西海岸" width="200" height="172" /></a></div>
                <div class="lists">
                    <ul>
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 24);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=24_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                  </ul>
                </div>
                
                
                </div>
                <div class="about5_1">
                <div class="imgs"><span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>">夏威夷</a></span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>"><img src="/image/nav/abouts_img19.jpg" alt="夏威夷" width="200" height="172" /></a></div>
                <div class="lists">
                    <ul>
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 33);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=33_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                    </ul>
                </div>
                
                
                </div>
                <div class="about5_1 background_f9f9f9">
                <div class="imgs"><span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=54_148');?>">加拿大</a></span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=54_148');?>"><img src="/image/nav/abouts_img20.jpg" alt="加拿大" width="200" height="172" /></a></div>
                <div class="lists">
                    <ul>
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 54);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=54_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                    </ul>
                </div>
                
                
                </div>
                <div class="about5_1">
                <div class="imgs"><span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=193_194');?>">中国</a></span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=193_194');?>"><img src="/image/nav/abouts_img21.jpg" alt="中国" width="200" height="172" /></a></div>
                <div class="lists">
                    <ul>
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 194);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=194_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                    </ul>
                </div>
                
                
                </div>
                <div class="about5_1 background_f9f9f9">
                <div class="imgs"><span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=157');?>">欧洲</a></span><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=157');?>"><img src="/image/nav/abouts_img22.jpg" alt="欧洲" width="200" height="172" /></a></div>
                <div class="lists">
                    <ul>
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 157);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=157_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                  </ul>
                </div>
                
                
                </div>
   	      	</div>
        </div>
    </div>
    
    <?php echo db_to_html(ob_get_clean());?>