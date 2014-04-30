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
                	<li>站点地图</li>
                </ul>
        </div>
            <div class="aboutsCont ">
                <div class="about6_1">
                	<div class="tit"><h3><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_29');?>">美 国</a></h3></div>
                    <div class="cont">
                    	<dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=25_55');?>">美东</a></dt>
                            <dd>
                            	<ul class="color_gray">
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 25);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=25_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_29');?>">美西</a></dt>
                      <dd>
                            	<ul class="color_gray">
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 24);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=24_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>">夏威夷</a></dt>
                      <dd>
                            	<ul class="color_gray">
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 33);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=33_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                                </ul>
                            </dd>
                        </dl>
                        <!--
						<dl>
                        	<dt><a href="#">佛罗里达</a></dt>
                      <dd>
                            	<ul class="color_gray">
                                	<li><a href="#">迈阿密</a></li>
                                </ul>
                            </dd>
                        </dl>
                        -->
						 <dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_29');?>">洛杉矶</a></dt>
                        </dl>
                          <dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_32');?>">拉斯维加斯</a></dt>
                        </dl>
                          <dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24_35');?>">黄石公园</a></dt>
                        </dl>
                    </div>
                </div>
                <div class="about6_1">
                	<div class="tit"><h3>加拿大</h3></div>
                    <div class="cont">
                    	<dl>
                        	<dt>加拿大</dt>
                            <dd>
                            	<ul class="color_gray">
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 54);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=54_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                                </ul>
                            </dd>
                        </dl>
                  </div>
                </div>
                <div class="about6_1">
                	<div class="tit"><h3>亚洲</h3></div>
                    <div class="cont">
                    	<dl>
                        	<dt>亚洲</dt>
                            <dd>
                            	<ul class="color_gray">
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 194);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=193_194_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                        	<dt>欧洲</dt>
                            <dd>
                            	<ul class="color_gray">
                    <?php
					$subcategories_array = array();
					tep_get_subcategories($subcategories_array, 157);
					foreach((array)$subcategories_array as $key => $value){
						echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=157_'.$value).'">'.tep_get_categories_name($value).'</a></li>';
					}
					?>
                                </ul>
                            </dd>
                        </dl>
                  </div>
                </div>
                <div class="about6_1">
                	<div class="tit"><h3>大洋洲</h3></div>
                    <div class="cont">
                    	<dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=186_187');?>">澳大利亚</a></dt>
                        </dl>
                        <dl>
                        	<dt><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=186_188');?>">新西兰</a></dt>
                        </dl>
                  </div>
                </div>
   	      	</div>
        </div>
    </div>
 <?php echo db_to_html(ob_get_clean()) ?>