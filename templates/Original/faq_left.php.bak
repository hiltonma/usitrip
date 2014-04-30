<div class="abouts_left" id="left">
        	<div class="column">
           		<div class="tit"><h2>帮助中心</h2></div>
                <div class="cont">
                	<ul id="faqLink">
                    	<li><a href="<?php echo tep_href_link('faq_question.php','','NONSSL',false)?>">常见问题</a></li>
                    	<li><a href="<?php echo tep_href_link('order_process.php','','NONSSL',false)?>">订购流程</a></li>
                    	<li><a href="<?php echo tep_href_link('companions_process.php','','NONSSL',false)?>">结伴同游流程</a></li>
                    	<li><a href="<?php echo tep_href_link('order_agreement.php','','NONSSL',false)?>">订购协议</a></li>
                    	<li><a href="<?php echo tep_href_link('payment.php','','NONSSL',false)?>">支付方式</a></li>
                    	<li><a href="<?php echo tep_href_link('change_plan.php','','NONSSL',false)?>">变更取消和退款条例</a></li>
                    	<li><a href="<?php echo tep_href_link('visa_related.php','','NONSSL',false)?>">签证相关</a></li>
                    	<li><a href="<?php echo tep_href_link('tour_america_need.php','','NONSSL',false)?>">旅美须知</a></li>
                    	<li><a href="<?php echo tep_href_link('insurance.php','','NONSSL',false)?>">旅游保险(强烈建议购买)</a></li>
                        <li><a href="<?php echo tep_href_link('faq_points.php','','NONSSL',false)?>">会员积分</a></li>
                        <li><a href="<?php echo tep_href_link('studytour.php','','NONSSL',false)?>">海外游学</a></li>
                        <li><a href="<?php echo tep_href_link('down_load.php','','NONSSL',false)?>">下载专区</a></li>
                    	<?php /*?><li><a href="#">旅游资讯</a></li><?php */?>
                    </ul>
                </div>
            </div>
        </div>    <script type="text/javascript">
	
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
	</script>