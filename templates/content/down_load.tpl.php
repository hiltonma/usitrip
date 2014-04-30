<?php ob_start();?>
<style type="text/css"> body{font-family:Tahoma,SimSun,Arial,Helvetica,sans-serif;} </style>
<link href="templates/Original/page_css/faq_question-min.css?d=20130722" rel="stylesheet" type="text/css" />
<div id="abouts">
		<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/faq_left.php');
		?>  
		<script type="text/javascript">
	
	jQuery(document).ready(function(e) {
        jQuery('.abouts_left').css('height',jQuery('.abouts_right').innerHeight());
    });

	
		jQuery("ul#faqLink li").removeClass("selected");	
		var links = document.getElementById("faqLink").getElementsByTagName("a");
		for(var i=0,len=links.length;i<len;i++){
			var winurl = window.location.href;
			var linkhref = links[i].getAttribute("href");
			if(winurl == linkhref){
				jQuery("ul#faqLink li a[href='" + winurl + "']").parent().addClass("selected");
			}
		}
	</script>        <div class="abouts_right" id="right">
        	<div class="aboutsTit">
            	<ul>
                	<li>下载专区</li>
                </ul>
            </div>	
            <div class="aboutsCont ">
				<style>
					.download_box_list .d_box{ background:#e0f0ff; border:solid 1px #85bbe9; padding:20px; border-radius:5px; color:#2a61b4; margin-bottom:20px;}
				</style>
            	<div class="download_box_list">
				<?php $i=0;foreach($file_array as $key=>$value){?>
					<div class="d_box">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="10%"><img alt="" src="image/download/<?=$value['img']?>" /></td>
								<td width="45%">
									<p><?=$key?></p>
									<p><?=$value['cnName']?></p>
								</td>
								<td width="15%">
									<p>下载次数</p>
									<p ><span id="number_<?=$i?>"><?=$value['downNumber']?></span>次</p>
								</td>
								<td width="30%">
									<a href="down_load.php?down_file=<?=$key?>"><img alt="" src="image/download/down_icon_03.gif" onclick="document.getElementById('number_<?=$i?>').innerHTML=parseInt(document.getElementById('number_<?=$i?>').innerHTML)+1;"/></a>
								</td>
							</tr>
						</table>
					</div>
					<?php $i++; }?>
            	</div>
            </div>
        </div>
    </div>
	
<?php echo  db_to_html(ob_get_clean());?>
