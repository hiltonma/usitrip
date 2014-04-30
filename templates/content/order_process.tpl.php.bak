<?php ob_start();?>

	<div id="abouts">
    	<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/faq_left.php');
		?>
        <div class="abouts_right" id="right">
        	<div class="aboutsTit">
            	<ul>
                	<li>订购流程</li>
                </ul>
            </div>
            <div class="help_cnt">
                <div class="help_step">
                    <table id="stepTable" class="steptable">
                        <tbody>
                            <tr>
                                <td class="current" id="o0"><div class="con"><b>1</b>网上预订</div></td>
                                <td id="o1"><div class="tri"><div class="con"><b>2</b>需求确定</div></div></td>
                                <td id="o2"><div class="tri"><div class="con"><b>3</b>付款/签约</div></div></td>
                                <td id="o3"><div class="tri"><div class="con"><b>4</b>出团通知</div></div></td>
                                <td id="o4"><div class="tri"><div class="con"><b>5</b>开心游玩</div></div></td>
                                <td id="o5"><div class="tri"><div class="con"><b>6</b>归来点评</div></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="step_notice">
                    <div class="step_button">
                        <span class="prev un">上一步</span>
                        <span class="next on">下一步</span>
                    </div>
                    <strong>搜索旅游线路</strong>
                </div>
                <div class="fix step_content">
                    <div class="help_step_tab">
                        <div class="step_tabitem">
                            <ul>
                                <li class="current"><em><span>(1)</span><span class="txt">搜索旅游线路</span></em></li>
                                <li><em><span>(2)</span><span class="txt">条件筛选行程</span></em></li>
                            </ul>
                        </div>
                    </div>
                    <div class="step_pic fix">
                        <div class="fix">
                            <img src="image/orderprocess/schinese/booking_step00.gif" alt="搜索旅游线路" />
                        </div>
                        <div class="fix hide">
                            <img src="image/orderprocess/schinese/booking_step01.gif" alt="条件筛选行程" />
                        </div>
                    </div>
                </div>
                <div class="fix step_content hide">
                    <div class="help_step_tab">
                        <div class="step_tabitem">
                            <ul>
                                <li class="current"><em><span>(1)</span><span class="txt">输入订单信息</span></em></li>
                                <li><em><span>(2)</span><span class="txt">查看购物车</span></em></li>
                                <li><em><span>(3)</span><span class="txt">填写游客信息</span></em></li>
                            </ul>
                        </div>
                    </div>
                    <div class="step_pic fix">
                        <div class="fix">
                            <img src="image/orderprocess/schinese/booking_step11.gif" alt="输入订单信息" />
                        </div>
                        <div class="fix hide">
                            <img src="image/orderprocess/schinese/booking_step10.gif" alt="查看购物车" />
                        </div>
                        <div class="fix hide">
                            <img src="image/orderprocess/schinese/booking_step12.gif" alt="填写游客信息" />
                        </div>
                    </div>
                </div>
                <div class="fix step_content hide">
                    <div class="help_step_tab">
                        <div class="step_tabitem">
                            <ul>
                                <li class="current"><em><span>(1)</span><span class="txt">付款/签约</span></em></li>
                            </ul>
                        </div>
                    </div>
                    <div class="step_pic fix">
                        <div class="fix">
                            <img src="image/orderprocess/schinese/booking_step20.gif" alt="付款/签约" />
                        </div>
                    </div>
                </div>
                <div class="fix step_content hide">
                    <div class="help_step_tab">
                        <div class="step_tabitem">
                            <ul>
                                <li class="current"><em><span>(1)</span><span class="txt">出团通知</span></em></li>
                            </ul>
                        </div>
                    </div>
                    <div class="step_pic fix">
                        <div class="fix">
                            <img src="image/orderprocess/schinese/booking_step30.gif" alt="出团通知" />
                        </div>
                    </div>
                </div>
                <div class="fix step_content hide">
                    <div class="help_step_tab">
                        <div class="step_tabitem">
                            <ul>
                                <li class="current"><em><span>(1)</span><span class="txt">开心游玩</span></em></li>
                            </ul>
                        </div>
                    </div>
                    <div class="step_pic fix">
                        <div class="fix">
                            <img src="image/orderprocess/schinese/booking_step40.jpg" alt="开心游玩" />
                        </div>
                    </div>
                </div>
                <div class="fix step_content hide">
                    <div class="help_step_tab">
                        <div class="step_tabitem">
                            <ul>
                                <li class="current"><em><span>(1)</span><span class="txt">归来点评</span></em></li>
                            </ul>
                        </div>
                    </div>
                    <div class="step_pic fix">
                        <div class="fix">
                            <img src="image/orderprocess/schinese/booking_step50.gif" alt="归来点评" />
                        </div>
                    </div>
                </div>
            </div>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("div.step_tabitem ul > li").bind("click",function(){
		jQuery(this).parents("div.step_tabitem").find("li").removeClass("current");
		jQuery(this).addClass("current");
		jQuery("div.step_notice").find("strong").html(jQuery(this).find("span.txt").text());
		jQuery(this).parents("div.step_content").find("div.step_pic div").hide();
		jQuery(this).parents("div.step_content").find("div.step_pic div").eq(jQuery(this).parents("div.step_tabitem").find("li").index(this)).show();
	});
	jQuery("div.step_button span.prev").click(function(){
		var curtd = jQuery("div.help_step").find("td.current");
		var steptd = jQuery("div.help_step td");
		var curtd_idx = jQuery("div.help_step td").index(curtd);
		if(curtd_idx == 0){
			jQuery(this).removeClass("on").addClass("un");
			alert("当前已经是第一步");
			return false;
		}
		prev(curtd_idx);
	});
	jQuery("div.step_button span.next").click(function(){
		var curtd = jQuery("div.help_step").find("td.current");
		var steptd = jQuery("div.help_step td");
		var curtd_idx = jQuery("div.help_step td").index(curtd)
		if(curtd_idx == (jQuery("div.help_step td").length-1)){
			alert("当前已经是最后一步");
			return false;
		}
		next(curtd_idx);
	});
	function prev(curtd_idx){
		jQuery("div.step_button span.next").removeClass("un").addClass("on");
		jQuery("div.help_step td").removeClass("current");
		jQuery("div.help_step td").eq(curtd_idx-1).addClass("current");
		jQuery("div.help_cnt div.step_content").hide();
		jQuery("div.help_cnt div.step_content").eq(curtd_idx-1).show();
		if(curtd_idx == 1){
			jQuery("div.step_button span.prev").removeClass("on").addClass("un");
		}
		var showcnt = jQuery("div.help_cnt div.step_content").eq(curtd_idx-1);
		var showitem = jQuery(showcnt).find("li.current");
		var txt = jQuery(showitem).find("span.txt").text();
		jQuery("div.step_notice").find("strong").html(txt);
	}
	function next(curtd_idx){
		jQuery("div.step_button span.prev").removeClass("un").addClass("on");
		jQuery("div.help_step td").removeClass("current");
		jQuery("div.help_step td").eq(curtd_idx+1).addClass("current");
		jQuery("div.help_cnt div.step_content").hide();
		jQuery("div.help_cnt div.step_content").eq(curtd_idx+1).show();
		if(curtd_idx == jQuery("div.help_step").find("td").length - 2){
			jQuery("div.step_button span.next").removeClass("on").addClass("un");
		}
		var showcnt = jQuery("div.help_cnt div.step_content").eq(curtd_idx+1);
		var showitem = jQuery(showcnt).find("li.current");
		var txt = jQuery(showitem).find("span.txt").text();
		jQuery("div.step_notice").find("strong").html(txt);
	}
	var urlstr = window.location.href;
	var ids = urlstr.split("#")[1];
	if(ids){
		if(ids == 0){
			return;
		}
		var ids = ids.replace("o","");
		ids--;
		//alert(ids);
		next(ids);
	}
});
</script> 
        </div>
    </div>

<?php echo  db_to_html(ob_get_clean());?>
