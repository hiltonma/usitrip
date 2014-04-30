<div class="abouts_left" id="left">
        	<div class="column">
           		<div class="tit"><h2>走四方旅游网</h2></div>
                <div class="cont">
                	<ul id="aboutLink">
                    	<li><a href="<?php echo tep_href_link('about_us.php','','NONSSL',false)?>">关于走四方</a></li>
                    	<li><a href="<?php echo tep_href_link('team_introduced.php','','NONSSL',false)?>">团队介绍</a></li>
                    	<li><a href="<?php echo tep_href_link(FILENAME_CONTACT_US,'','NONSSL',false)?>"><?php echo html_to_db(BOX_INFORMATION_CONTACT)?></a></li>
                    	<li><a href="<?php echo tep_href_link('privacy-policy.php','','NONSSL',false)?>">隐私&amp;版权声明</a></li>
                    	<li><a href="<?php echo tep_href_link('sinotour.php','','NONSSL',false)?>">景点介绍</a></li>
						<li><a href="<?php echo tep_href_link('links.php','','NONSSL',false)?>">友情链接</a></li>
                    	<li><a href="<?php echo tep_href_link('sitemap.php','','NONSSL',false)?>">站点地图</a></li>
                    	<?php if( strtolower(AFFILIATE_SWITCH) === 'true') {?>
						<li><a href="<?php echo tep_href_link('partner.php','','NONSSL',false)?>">代理加盟</a></li>
                    	<li><a href="<?php echo tep_href_link('recommend.php','','NONSSL',false)?>">推荐给朋友（获3%佣金）</a></li>
                    	<?php } /*?>
                    	<li style="display:none;"><a href="#">调查问卷</a></li><?php */?>
                    	<li><a href="<?php echo tep_href_link('recruitment.php','','NONSSL',false);?>">人才招聘</a></li>
                    </ul>
                </div>
            </div>
        </div>
	<script type="text/javascript">
	
	jQuery(document).ready(function(e) {
        jQuery('.abouts_left').css('height',jQuery('.abouts_right').innerHeight());
	});
	
		jQuery("ul#aboutLink li").removeClass("selected");	
		var links = document.getElementById("aboutLink").getElementsByTagName("a");
		for(var i=0,len=links.length;i<len;i++){
			var winurl = window.location.href;
			var linkhref = links[i].getAttribute("href");
			if(winurl == linkhref){
				jQuery("ul#aboutLink li a[href='" + winurl + "']").parent().addClass("selected");
			}
		}
	</script>