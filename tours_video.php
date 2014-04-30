<div id="cont_v" style="padding-left:20px;">
<div id="cont_v_1">
	<table width="400" class="video_table" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td>
		

		<?php
				if(tep_not_null($product_info['products_video'])){
						if(eregi("youtube.com",$product_info['products_video']) || eregi("youku.com",$product_info['products_video'])){ //amit added to check show from youtube
						?>										
							<object >
							<param name="movie" value="<?php echo $product_info['products_video'];?>"></param><param name="wmode" value="transparent"></param><embed src="<?php echo $product_info['products_video'];?>" type="application/x-shockwave-flash" wmode="transparent" width="441" height="337"></embed>
							</object>
						<?php
						}else { //amit added to check show from youtube

						$fileext = substr(strrchr($product_info['products_video'], '.'), 1);
						
						if($fileext == "flv"){
						$loadflashplayer = "true";
						}
						if( (substr($product_info['products_video'],0,5)) == 'http:'  ){
						//write code to show direct video url
									if($loadflashplayer == "true"){
									//load with flash player
										?>
										<object type="application/x-shockwave-flash" width="98%" height="250"
												wmode="transparent" data="flvplayer.swf?file=<?php echo urlencode($product_info['products_video']);?>">
												<param name="movie" value="flvplayer.swf?file=<?php echo urlencode($product_info['products_video']);?>" />
												<param name="wmode" value="transparent" />
										</object>
										
										<?php														
									}else{
									//load with media palyer
									?>
									<object id="mediaPlayer" width="98%" height="300"
											classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"
											codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"
											standby="Loading Microsoft Windows Media Player components?" type="application/x-oleobject">
											<param name="fileName" value="<?php echo urlencode($product_info['products_video']);?>">
											<param name="animationatStart" value="1">
											<param name="transparentatStart" value="1">
											<param name="autoStart" value="1">
											<param name="ShowControls" value="1">
											<param name="ShowDisplay" value="0">
											<param name="ShowStatusBar" value="1">
											<param name="loop" value="0">
											<EMBED type="application/x-mplayer2"
											pluginspage="http://microsoft.com/windows/mediaplayer/en/download/"
											id="mediaPlayer" name="mediaPlayer" displaysize="4" autosize="0"
											bgcolor="darkblue" showcontrols="1" showtracker="1"
											showdisplay="0" showstatusbar="1" videoborder3d="0" width="98%" height="300"
											src="<?php echo urlencode($product_info['products_video']);?>" autostart="1" designtimesp="5311" loop="0">
											</EMBED>
  </object>
									<?php
									}
						}else{
						//write code to show video from own server
									if($loadflashplayer == "true"){
									//load with flash player
										?>
										
										<object type="application/x-shockwave-flash" width="98%" height="251"
												wmode="transparent" data="flvplayer.swf?file=<?php echo DIR_WS_VIDEO.$product_info['products_video'];?>">
												<param name="movie" value="flvplayer.swf?file=<?php echo DIR_WS_VIDEO.$product_info['products_video'];?>" />
												<param name="wmode" value="transparent" />
										</object>
																									
										<?php																				
									}else{
									//load with media palyer
									?>
									<object id="mediaPlayer" width="98%" height="300"
											classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"
											codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"
											standby="Loading Microsoft Windows Media Player components?" type="application/x-oleobject">
											<param name="fileName" value="<?php echo DIR_WS_VIDEO.$product_info['products_video'];?>">
											<param name="animationatStart" value="1">
											<param name="transparentatStart" value="1">
											<param name="autoStart" value="1">
											<param name="ShowControls" value="1">
											<param name="ShowDisplay" value="0">
											<param name="ShowStatusBar" value="1">
											<param name="loop" value="0">
											<EMBED type="application/x-mplayer2"
											pluginspage="http://microsoft.com/windows/mediaplayer/en/download/"
											id="mediaPlayer" name="mediaPlayer" displaysize="4" autosize="0"
											bgcolor="darkblue" showcontrols="1" showtracker="1"
											showdisplay="0" showstatusbar="1" videoborder3d="0" width="98%" height="300"
											src="<?php echo DIR_WS_VIDEO.$product_info['products_video'];?>" autostart="1" designtimesp="5311" loop="0">
											</EMBED>
  </object>							
									<?php
									}
						}

						}//amit added to check show from youtube if end			
						
						}else{
						
						 $new_image = 'novideo_large.jpg';
						 $image_width = MEDIUM_IMAGE_WIDTH;
						 $image_height = MEDIUM_IMAGE_HEIGHT;
						echo tep_image(DIR_WS_IMAGES . $new_image, addslashes(db_to_html($product_info['products_name'])), $image_width, $image_height, 'hspace="0" vspace="5"');
						
						}//end of check video available
						?>					

		</td>
	  </tr>
	</table>

</div>


</div>
