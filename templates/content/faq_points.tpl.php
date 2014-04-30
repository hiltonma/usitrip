<?php ob_start();?>

	<div id="abouts">
    	<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/faq_left.php');
		?>
        <div class="abouts_right" id="right">
        	<div class="aboutsTit">
            	<ul>
                	<li>会员积分</li>
                </ul>
            </div>
            <div class="aboutsCont ">
<div class="ui_rules_faq">
    <ul class="ui_rules_faqlist">
    	<li><a href="<?php echo tep_href_link('faq_points.php')?>#list1" name="list1">什么是积分，有什么用？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list2">获得积分的途径有哪些？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list3">老客户有积分回馈吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list4">积分的现金兑换标准是多少？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list5">积分能享受的消费折扣限额是多少？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list6">我的积分有效期是多久呢？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list7">积分的使用范围有限制吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list8">我已经预订了某条线路，请问积分什么时候会赠送到我的账户？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list9">我的订单改变了，增加（减少）了金额，请问积分是怎么赠送的？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list10">积分能兑现吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list11">我有两个会员账号，积分能转让或合并吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list12">如何查看我的积分及历史记录？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list13">如何兑换/使用积分？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list14">特价团也有积分赠送吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list15">我在使用积分订购时还可以获取积分吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list16">如果订单取消，积分还能获取到吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list17">如果我用积分抵扣了部分订单金额，订单取消后，积分还会还回来吗？</a></li>
        <li><a href="<?php echo tep_href_link('faq_points.php')?>#list18">我可以使用积分和折扣券进行多重打折吗？</a></li>
    </ul>
    <div class="ui_rules_faqcnt">
    	<div id="list1">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>什么是积分，有什么用？</h3>
        	<p>走四方旅游网为感恩答谢一直以来广大客户的支持和厚爱，特推出积分优惠活动，以积分抵扣现金的方式回馈所有新老客户。</p>
            <p>凡是参与走四方旅游网积分活动或订购旅游产品的注册用户均可获取相应积分，并享有在消费时通过积分抵扣对应现金金额的优惠服务，最高优惠折扣可达100%。</p>
        </div>
        <div id="list2">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>获得积分的途径有哪些？</h3>
        	<p>获得积分的途径有：会员注册、邮箱验证、产品预订、点评分享、各类活动等，您可以具体参看<a href='/points_terms.php'>积分规则</a>页面下的“积分赚取规则”相关内容。</p>
        </div>
        <div id="list3">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>老客户有积分回馈吗？</h3>
        	<p>凡新版网站上线之前在走四方旅游网旧版网站注册的老客户，均按照以下规则直接获取对应积分回馈：</p>
            <p>1.在旧版网站上消费过的老用户，走四方旅游网将根据其过往在旧站最后一次已出团的消费金额，直接给予1:1.5的对应积分(1美元=>1.5积分)</p>
            <p>2.在旧版网站上注册但未曾消费，及消费过而所得积分不足200的老客户，都将直接免费获赠200积分。</p>
        </div>
        <div id="list4">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>积分的现金兑换标准是多少？</h3>
        	<p><?php $_yibai =100; $_points_usd = ($_yibai * REDEEM_POINT_VALUE) ;?><?= $_yibai;?>积分=<?= $_points_usd;?>美元，无上限，有多少兑多少，积分越高现金折扣越高，最高优惠100%！</p>
        </div>
        <div id="list5">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>积分能享受的消费折扣限额是多少？</h3>
        	<p>1.客户在首次订购产品时，只能使用<?= FIRST_BOOKING_MIN_POINTS_UP_CAN_USE;?>以上的积分部分。二次及以上的消费，则再无任何限制。</p>
            <p>2.走四方积分的兑换比例为：<?= $_yibai;?>积分=<?= $_points_usd;?>美元，无上限，有多少兑多少，积分越高现金折扣越高，甚至可以免费换取旅行团，完全享受到最高100%的优惠！</p>
        </div>
        <div id="list6">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>我的积分有效期是多久呢？</h3>
        	<p>走四方的积分有效期为<?php echo $_var = round((POINTS_AUTO_EXPIRES/12),2)?>年（即从获得积分开始至<?php echo $_var;?>年后的当日前均有效），逾期则自动作废，若交易时所使用的积分又在有效期之外发生退款，该部分积分不予退还。</p>
        </div>
        <div id="list7">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>积分的使用范围有限制吗？</h3>
        	<p>积分仅限订购旅游线路、游学线路及行程酒店时使用，在出游过程中的消费，如乘车，服务消费，景点门票，均不可使用积分。但积分的使用方式是没有限制的，您可在网站上自由使用，可根据需求选择是否兑换，不受出发城市、目的地、产品类型的限制。</p>
        </div>
        <div id="list8">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>我已经预订了某条线路，请问积分什么时候会赠送到我的账户？</h3>
        	<p>客户所获得的积分与消费金额相对应，成功交易后即会存入会员账户中，实际赠送积分数以最终消费金额为准。同时，客户所获得的积分只能在本轮旅游出团后才能使用，如某一订单有两个以上的团，则以第一次出团的时间为准。</p>
        </div>
        <div id="list9">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>我的订单改变了，增加（减少）了金额，请问积分是怎么赠送的？</h3>
        	<p>客户所获得的积分与消费金额相对应，成功交易后即会存入会员账户中，但若客户进行改单（消费金额有变动），则对应积分随之改变，因此，实际赠送积分数以最终消费金额为准。</p>
        </div>
        <div id="list10">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>积分能兑现吗？</h3>
        	<p>积分不能兑现，不可转让，不能找零，不能开票，只能在走四方旅游网的产品订购中抵扣对应现金金额。</p>
        </div>
        <div id="list11">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>我有两个会员账号，积分能转让或合并吗？？</h3>
        	<p>建议您集中使用其中一个账户进行预订，以免造成积分分散。因为积分不可转让，并且任何两个走四方网的帐户积分都不予以合并或转换。</p>
        </div>
        <div id="list12">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>如何查看我的积分及历史记录？</h3>
        	<p>登录“用户中心”——“积分管理”模块，您可以在“我的积分”菜单中查看积分积累情况，也可在“积分明细”中查看所有的过往积分历史记录。</p>
        </div>
        <div id="list13">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>如何兑换/使用积分？</h3>
        	<p>您在订购产品时，在付款结账页面会有对应的“兑换”的窗口，您可以看到您目前的积分总数、对应现金及本订单可使用的最高积分数等信息。点击“确定兑换”按钮后，系统自动为您计算出折扣金额，展示积分优惠后订单实付的总价，确认抵换“去支付”后，积分即兑换成现金并作相应扣除，您只需直接支付优惠折扣后的余额即可。</p>
        </div>
        <div id="list14">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>特价、团购和使用了折扣券的线路还赠送积分吗？</h3>
        	<p>特价、团购及使用折扣券/优惠码的线路均不参与积分优惠活动，具体可查看每条线路的最终实际赠送积分数额。</p>
        </div>
        <div id="list15">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>我在使用积分订购时还可以获取积分吗？</h3>
        	<p>可以的。您使用积分进行订购后，将按照实际支付的金额进行积分的获取。</p>
        </div>
        <div id="list16">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>如果订单取消，积分还能获取到吗？</h3>
        	<p>若某订单最终确定取消了，其积分是不能获取到的。</p>
        </div>
        <div id="list17">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>如果我用积分抵扣了部分订单金额，订单取消后，积分还会还回来吗？</h3>
        	<p>订单确认取消后，您的积分会自动返还到您对应的账户中。</p>
        </div>
        <div id="list18">
        	<h3><a class="gotop" href="javascript:scroll(0,0)">回顶部</a>我可以使用积分和折扣券进行多重打折吗？</h3>
        	<p>很抱歉，积分默认情况下不可与其他打折优惠活动同时使用，包括特价、团购、折扣券、优惠码或抵用券等。</p>
        </div>
    </div>
</div>
                <script type="text/javascript">
                jQuery(document).ready(function(){
					var $seft = jQuery("#faq_question > ul > li");
					var $seft2 = jQuery(".hidebox >li");
					
						$seft.find(":first").click(function(){
							jQuery(this).parent().addClass("cur").siblings().removeClass("cur");
							jQuery(this).next().slideDown(300).end().parent().siblings().find(".hide").slideUp(300);	
							jQuery(".hidebox >li").find(".hide2").slideUp().end().removeClass("current");
							
						})
						$seft2.find(":first").click(function(){
							jQuery(this).parent().addClass("current").siblings().removeClass("current");
							jQuery(this).next().slideDown(300).end().parent().siblings().find(".hide2").slideUp(300);		
							
						});
				});
				
                </script>
            </div>
        </div>
    </div>

<?php echo  db_to_html(ob_get_clean());?>
