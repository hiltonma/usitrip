<?php ob_start(); ?>
<script type="text/javascript">
jQuery(document).ready(
	function(){
		//".abouts_left",".abouts_right"
		var h1 = jQuery('.abouts_left').outerHeight();
 		var h2 = jQuery('.abouts_right').outerHeight();
 		var mh = Math.max( h1, h2);//去最大的值
 		jQuery('.abouts_left,.abouts_right').height(mh);
	}
);
</script>
<div id="abouts">
    	<?php
    	require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/about_left.php');
    	?>
        <div class="abouts_right">
        	<div class="aboutsTit">
            	<ul>
                	<li>友情链接</li>
                </ul>
        </div>
            <div class="aboutsCont ">
                <div class="about9_1">
                	<h4 class="font_size14 color_blue"><strong>友情连接(QQ: 2355652791或2355652784)</strong></h4>
                    <div class="cont" style="border-bottom:0;">
                    	<ul>
                        <?php
						//$link_categories_sql = "select lcd.link_categories_id,lcd.language_id,lcd.link_categories_name,lcd.link_categories_description,lc.link_categories_id,lc.link_categories_status from link_categories lc,link_categories_description lcd where lcd.link_categories_id = lc.link_categories_id and lc.link_categories_status = '1'";
        //$link_categories_query = tep_db_query($link_categories_sql);
       // while($link_categories_rows = tep_db_fetch_array($link_categories_query)){

        
                    //$links_sql2 = "select l2lc.links_id,l2lc.link_categories_id,l.links_id,l.links_url,l.links_reciprocal_url,l.links_image_url,l.links_contact_name,l.links_contact_email,l.links_status,l.links_rating,ld.links_id,ld.language_id,ld.links_title,ld.links_description,ls.links_status_id,ls.language_id,ls.links_status_name from links l, links_to_link_categories l2lc, links_description ld, links_status ls where l2lc.link_categories_id = '".$link_categories_rows['link_categories_id']."' and l.links_id=l2lc.links_id and ld.links_id=l.links_id and ls.links_status_name='Approved' and l.links_status=ls.links_status_id order by l2lc.links_id";
                    $links_sql2 = "select l.links_id,l.links_url, ld.links_title,ld.links_description from links l, links_description ld, links_status ls where ld.links_id=l.links_id and ls.links_status_name='Approved' and l.links_status=ls.links_status_id order by l.links_id";
                    $links_query2 = tep_db_query($links_sql2);
                    //display links:
                    $lrow = 0;
                    while($links = tep_db_fetch_array($links_query2)){
                        if($num_row_list_cnt % 4 == 0 && (int)$num_row_list_cnt){
                            echo '</ul><ul >';
                        }
                        $num_row_list_cnt++;
                ?>
                 <li style=" float:left"><a href="<?php echo $links['links_url']?>"  target="_blank" title="<?= $links['links_description']?>"><?php echo $links['links_title']?></a></li>
                
                <?php 
                    }
		
		
       // } ?>

                        </ul>
                        
                    </div>
                </div>              
   	      	</div>
        </div>
    </div>
<?php echo db_to_html(ob_get_clean());?>



<?php  
/*
echo tep_get_design_body_header(TEXT_LINK_TO_US); ?>

<div style="float:left; width:100%; padding:10px 0px 0px 10px;">
<DIV class="product_tab_b_n" style=" width:100%">
      <DIV class=tab1 id=tab1>
      <UL>
        <?php
        $link_categories_sql = "select lcd.link_categories_id,lcd.language_id,lcd.link_categories_name,lcd.link_categories_description,lc.link_categories_id,lc.link_categories_status from link_categories lc,link_categories_description lcd where lcd.link_categories_id = lc.link_categories_id and lc.link_categories_status = '1'";
        $link_categories_query = tep_db_query($link_categories_sql);
        while($link_categories_rows = tep_db_fetch_array($link_categories_query)){
             if($cId==$link_categories_rows['link_categories_id']){
                $Liclass = 'class="s"';
             }else{$Liclass = '';}
        ?>
        <LI <?=$Liclass?>><a href="<?=tep_href_link('links.php','cId='.$link_categories_rows['link_categories_id']);?>"><?php echo db_to_html($link_categories_rows['link_categories_name'])?></a> 
        </LI>
        <?php
        }
        ?>

        </UL>
        </DIV>
        
        <div style="float:right; padding-right:15px;"><?php echo '<a href="' .tep_href_link('applylink.php','cId='.$cId). '">' . tep_template_image_button('link-change.gif', TEXT_LINKS_SUBMIT) . '</a>'; ?></div></DIV>
        
        <div style="float:left; padding:10px 0px 0px 0px; width:97%;" class="side-content11">
             <ul style="padding-bottom:10px; float:left; width:100%">
              <?php
                if($cId){
                    $link_categories_sql = "select lcd.link_categories_id,lcd.language_id,lcd.link_categories_name,lcd.link_categories_description,lc.link_categories_id,lc.link_categories_status from link_categories lc,link_categories_description lcd where lcd.link_categories_id = lc.link_categories_id and lc.link_categories_status = '1' and lcd.link_categories_id='".$cId."'";
                    $link_categories_query = tep_db_query($link_categories_sql);
                    $link_categories = tep_db_fetch_array($link_categories_query);
                    $links_sql = "select l2lc.links_id,l2lc.link_categories_id,l.links_id,l.links_url,l.links_reciprocal_url,l.links_image_url,l.links_contact_name,l.links_contact_email,l.links_status,l.links_rating,ld.links_id,ld.language_id,ld.links_title,ld.links_description,ls.links_status_id,ls.language_id,ls.links_status_name from ". 'links l' . ",". 'links_to_link_categories l2lc' .",".'links_description ld'.",".'links_status ls'." where l2lc.link_categories_id = '".$cId."' and l.links_id=l2lc.links_id and ld.links_id=l.links_id and ls.links_status_name='Approved' and l.links_status=ls.links_status_id order by l2lc.links_id";
                    $links_query = tep_db_query($links_sql);
                    //display links:
                    $lrow = 0;
                    while($links = tep_db_fetch_array($links_query)){
                        if($num_row_list_cnt % 3 == 0 && (int)$num_row_list_cnt){
                            echo '</ul><ul style="padding-bottom:10px; float:left; width:100%">';
                        }
                        $num_row_list_cnt++;
                ?>
                 <li style="margin-left:5px; width:220px; float:left"><span class="dazi cu"><a href="<?php echo $links['links_url']?>"  target="_blank"><?php echo db_to_html($links['links_title'])?></a></span><br>
                 <span class="huise" title="<?= db_to_html($links['links_description'])?>"><?php echo cutword(db_to_html($links['links_description']),30)?></span></li>
                
                <?php 
                    }
                }
                ?>   
                 
             </ul>
         </div></div>


<?php echo tep_get_design_body_footer();  
*/
?>
