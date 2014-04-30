<?php
$get_intro_category_id = $current_category_id;
/**
 * 邮轮介绍，用于子级目录$cPath=="267_XXX"中介绍邮轮$cPath=="267"。如果$cat_mnu_sel=='introductionCruises'则要重新用267读取邮轮介绍的内容
 */
if($cat_mnu_sel=='introductionCruises'){
	unset($category);
	$get_intro_category_id = 267;
	$category = MCache::fetch_categories($get_intro_category_id);
}
?>

<div class="tab_biaoti"><div class="biaoti5"><h5><?php echo db_to_html($category['categories_name']);?></h5></div></div> 
<div class="tab_video">
    <div class="video">
    <?php
    if(tep_not_null($category['categories_video'])){
        if(eregi("youtube.com",$category['categories_video']) || eregi("youku.com",$category['categories_video'])){ //amit added to check show from youtube
        ?>	<object width="300" height="244">
            <param name="movie" value="<?php echo $category['categories_video'];?>"></param><param name="wmode" value="transparent"></param><embed src="<?php echo $category['categories_video'];?>" type="application/x-shockwave-flash" wmode="transparent" width="300" height="244"></embed>
            </object>
        <?php
        }else { //amit added to check show from youtube

			$fileext = substr(strrchr($category['categories_video'], '.'), 1);											
			if($fileext == "flv"){
			$loadflashplayer = "true";
			}
			if( (substr($category['categories_video'],0,5)) == 'http:'  ){
			//write code to show direct video url
				if($loadflashplayer == "true"){
				//load with flash player
					?>
					<object type="application/x-shockwave-flash" width="300" height="244"
							wmode="transparent" data="flvplayer.swf?file=<?php echo urlencode($category['categories_video']);?>">
							<param name="movie" value="flvplayer.swf?file=<?php echo urlencode($category['categories_video']);?>" />
							<param name="wmode" value="transparent" />
					</object>
					
					<?php														
				}else{
				//load with media palyer
				?>
				<OBJECT id='mediaPlayer'  width="300" height="244"
						classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95'
						codebase='http://activex.microsoft.com/activex/controls/ mplayer/en/nsmp2inf.cab#Version=5,1,52,701'
						standby='Loading Microsoft Windows Media Player components? type='application/x-oleobject'>
						<param name='fileName' value="<?php echo urlencode($category['categories_video']);?>">
						<param name='animationatStart' value='1'>
						<param name='transparentatStart' value='1'>
						<param name='autoStart' value='1'>
						<param name='ShowControls' value='1'>
						<param name='ShowDisplay' value='0'>
						<param name='ShowStatusBar' value='1'>
						<param name='loop' value='0'>
						<EMBED type='application/x-mplayer2'
						pluginspage='http://microsoft.com/windows/mediaplayer/ en/download/'
						id='mediaPlayer' name='mediaPlayer' displaysize='4' autosize='0'
						bgcolor='darkblue' showcontrols='1' showtracker='1'
						showdisplay='0' showstatusbar='1' videoborder3d='0' width="300" height="244"
						src="<?php echo urlencode($category['categories_video']);?>" autostart='1' designtimesp='5311' loop='0'>
						</EMBED>
		</OBJECT>
				<?php
				}
			}else{
			//write code to show video from own server
				if($loadflashplayer == "true"){
				//load with flash player
					?>
					
					<object type="application/x-shockwave-flash" width="300" height="244"
							wmode="transparent" data="flvplayer.swf?file=<?php echo DIR_WS_VIDEO.$category['categories_video'];?>">
							<param name="movie" value="flvplayer.swf?file=<?php echo DIR_WS_VIDEO.$category['categories_video'];?>" />
							<param name="wmode" value="transparent" />
					</object>
																				
					<?php																				
				}else{
				//load with media palyer
				?>
				<OBJECT id='mediaPlayer' width="300" height="244"
						classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95'
						codebase='http://activex.microsoft.com/activex/controls/ mplayer/en/nsmp2inf.cab#Version=5,1,52,701'
						standby='Loading Microsoft Windows Media Player components? type='application/x-oleobject'>
						<param name='fileName' value="<?php echo DIR_WS_VIDEO.$category['categories_video'];?>">
						<param name='animationatStart' value='1'>
						<param name='transparentatStart' value='1'>
						<param name='autoStart' value='1'>
						<param name='ShowControls' value='1'>
						<param name='ShowDisplay' value='0'>
						<param name='ShowStatusBar' value='1'>
						<param name='loop' value='0'>
						<EMBED type='application/x-mplayer2'
						pluginspage='http://microsoft.com/windows/mediaplayer/ en/download/'
						id='mediaPlayer' name='mediaPlayer' displaysize='4' autosize='0'
						bgcolor='darkblue' showcontrols='1' showtracker='1'
						showdisplay='0' showstatusbar='1' videoborder3d='0' width="300" height="244"
						src="<?php echo DIR_WS_VIDEO.$category['categories_video'];?>" autostart='1' designtimesp='5311' loop='0'>
						</EMBED>
	</OBJECT>							
				<?php
				}
        	}

        }//amit added to check show from youtube if end			
        
	}//end of check video available
	?>								
	</div>
    <div class="c_tt">
    <?php echo stripslashes(db_to_html($category['categories_video_description'])); ?>					
    </div>
</div>

<?php

$category_intro_query_sql  = "select *  from " . TABLE_CATEGORIES_DESCRIPTION_INTRODUCTION . " where categories_id = '".$get_intro_category_id."' order by categories_introduction_sort_order";
$category_intro_query = tep_db_query($category_intro_query_sql);

$tt_into_cnt_row = tep_db_num_rows($category_intro_query);

if($tt_into_cnt_row > 0){
	while($category_intro = tep_db_fetch_array($category_intro_query)){
		if($category_intro['categories_introduction_image_alt'] == ''){
			$category_intro['categories_introduction_image_alt'] = TEXT_ALTER_TAG;
		}
	?>
	<div class="tab_xunhuan">
	<?php if($category_intro['categories_introduction_image'] != ''){?>
	<div class="tab_xunhuan_img">								 
	<?php echo tep_image(DIR_WS_IMAGES . $category_intro['categories_introduction_image'],$category_intro['categories_introduction_image_alt']);?>
	</div>
	<?php } ?>
	<div class="tab_xunhuan_tt">
		 <div class="c_tt2">
		 <?php echo db_to_html($category_intro['categories_introduction_image_descirption']);?>
		 </div>
	</div>
	<div class="ladt_tt">
	<a href="javascript:scroll(0,0);" target="_self" class="a_3">TOP</a>
	</div>
	</div>
	
<?php		
	}
}
?>
<div style="width:50px;">&nbsp;</div>