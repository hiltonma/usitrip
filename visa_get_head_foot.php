<?php
require('includes/application_top.php');
//<link rel="stylesheet" type="text/css" href="visa/css/visa_lujia.css" />
?>
<?php
if ($_GET['position']=='head')
{
	$customer_id = (int)($_GET['UID']);
	$customer = false;
	$sql = 'SELECT customers_firstname FROM customers WHERE customers_id='.$customer_id;
	$sql_query = tep_db_query($sql);
	while($rows = tep_db_fetch_array($sql_query))
	{
		$customer = $rows;
	}
?>
    <div id="topbar">
        <div class="uiWrap">
            <div class="fr">

            	<ul class="fl uhmenu">
                	<li class="freeregister" style="color: #FF6600;">您好，<a href="http://208.109.123.18/account.php"><?php echo $customer['customers_firstname'];?></a><a href="http://208.109.123.18/logoff.php">退出</a></li>
                    <li class="userhome">
                    	<a href="http://208.109.123.18/account.php">用户中心</a>
                    </li>
                </ul>

                <ul class="fl helpmenu">
                	<li class="helpcenter"><a href="http://208.109.123.18/faq_question.php">帮助中心</a></li>
                </ul>
            </div>
            <span class="topwords">USItrip走四方旅游网-Your Trip , Our Care!</span>
        </div>
    </div>
    <div id="head">
    	<div class="hd">
        	<div class="fl logo"><a href="http://208.109.123.18" title="欢迎来到走四方网">走四方网</a></div>
            <div class="fl grade">
            	<a href="http://208.109.123.18">全球华人首选出国旅游网站<br>美国BBB认证最高商誉评级</a>
            </div>
            <div class="fl hdsearch">
            	<form action="http://208.109.123.18/advanced_search_result.php" method="">
                	<div class="fl rel hdsearchIpt">
                    	<input type="text" class="hdsearchWords" placeholder="请输入出发城市或想去的景点" name="w"/>
                    </div>
                    <input type="submit" class="fr hdsearchSubmit" value="搜索" />
                </form>
            </div>
            <div class="fr hdContact">
            	<ul>
                	<li>1-888-887-2816(美加)</li>
                    <li>4006-333-926(中国)</li>
                </ul>
            </div>
        </div>
        <div class="nav">
        	<ul class="navItems">
                <li class="current"><a href="http://208.109.123.18/"><span>首页</span></a></li>
                <li><a href="http://208.109.123.18/meidonglvyou/packages/"><span>美东</span></a></li>
                <li><a href="http://208.109.123.18/meixilvyou/packages/"><span>美西</span></a></li>
                <li><a href="http://208.109.123.18/xiaweiyilvyou/packages/"><span>夏威夷</span></a></li>
                <li><a href="http://208.109.123.18/jianadalvyou/packages/"><span>加拿大</span></a></li>
                <li><a href="http://208.109.123.18/ouzhoulvyou/packages/"><span>欧洲</span></a></li>
                <li><a href="http://208.109.123.18/haiwaiyouxue/packages/"><span>海外游学</span></a></li>
                <li><a href="http://208.109.123.18/jiudianyuding/"><span>酒店预订</span></a></li>
                <li><a href="http://208.109.123.18/qianzheng/"><span>签证</span></a></li>
                <li><a href="http://208.109.123.18/jiebantongyou/"><span>结伴同游</span></a></li>
                <li class="last"><a href="http://208.109.123.18/web_action/show_and_plane/index.html"><span>美国特色游</span></a></li>
            </ul>
            <div class="navbar">
                <dl class="fl navScenic">
                    <dt>热门景点：</dt>
                    <dd>
                        <a href="http://208.109.123.18/huangshigongyuanlvyou/packages/">黄石公园</a>
                        <a href="http://208.109.123.18/luoshanjilvyou/packages/">洛杉矶</a>
                        <a href="http://208.109.123.18/niuyuelvyou/packages/">纽约</a>
                        <a href="http://208.109.123.18/huashengdunlvyou/packages/">华盛顿</a>
                        <a href="http://208.109.123.18/jiujinshanlvyou/packages/">旧金山</a>
                        <a href="http://208.109.123.18/lasiweijiasilvyou/packages/">拉斯维加斯</a>
                        <a href="http://208.109.123.18/zhutileyuanlvyou/packages/">主题乐园</a>
                        <a href="http://208.109.123.18/boshidunlvyou/packages/">波士顿</a>
                        <a href="http://208.109.123.18/tanxiangshanlvyou/packages/">檀香山</a>
                        <a href="http://208.109.123.18/aolanduolvyou/packages/">奥兰多</a>
                    </dd>
                </dl>
 
            </div>
        </div>
    </div>
<?php
}


if ($_GET['position']=='foot')
{
?>
    <div id="foot">
        <div class="uiWrap uifix">
            <div class="partners">
                <h3>合作伙伴</h3>
                <dl>
                    <dt>特价机票</dt>
                    <dd><a href="http://www.jdoqocy.com/click-5516128-10630638">留学生机票 CheapOair</a></dd>
                    <dd><a href="http://www.anrdoezrs.net/click-5516128-10392969">全球特价机票 Priceline</a></dd>
                </dl>
                <dl>
                    <dt>低价酒店</dt>
                    <dd><a href="http://www.booking.com/index.html?aid=336352">世界领先酒店合作伙伴Booking</a></dd>
                    <dd><a href="http://travel.ian.com/index.jsp?cid=232843">美国低价酒店预订 Hotels</a></dd>
                </dl>
                <dl>
                    <dt>保险&amp;租车</dt>
                    <dd><a href="http://208.109.123.18/insurance.php">美国专业保险提供商Travelinsure</a></dd>
                    <dd><a href="http://www.rentalcars.com/Home.do?affiliatecode=usitrip&adplat=footer&adcamp=footer=hk&preflang=zs">全球最大租车平台rentalcars.com</a></dd>
                </dl>
            </div>
            <div class="site_help">
                <ul class="site_helplist">
                    <li>
                        <div class="site_helpitems">
                            <h4 class="title">新手入门</h4>
                            <ul class="getstart">
                                <li><a class="highlight" href="http://208.109.123.18/order_process.php" rel="nofollow">订购流程</a></li>
                                <li><a href="http://208.109.123.18/faq_question.php" rel="nofollow">常见问题</a></li>
                                <li><a href="http://208.109.123.18/payment.php" rel="nofollow">支付方式</a></li>
                                <li><a href="http://208.109.123.18/order_agreement.php" rel="nofollow">订购协议</a></li>
                                <li><a href="http://208.109.123.18/visa_related.php" rel="nofollow">签证相关</a></li>
                                <li><a href="http://208.109.123.18/companions_process.php" rel="nofollow">结伴同游流程</a></li>
                                <li class="nowidth"><a class="highlight" href="http://208.109.123.18/points.php" rel="nofollow">积分豪礼(最高优惠100%)</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="site_helpitems">
                            <h4 class="title">旅美须知</h4>
                            <ul class="getnotes">
                                <li>
								<a href="http://208.109.123.18/tour_america_need.php#a1" rel="nofollow">旅游证件</a>
								<a href="http://208.109.123.18/tour_america_need.php#a2" rel="nofollow">边检</a>
								<a href="http://208.109.123.18/tour_america_need.php#a3" rel="nofollow">时差</a>
								<a href="http://208.109.123.18/tour_america_need.php#a4" rel="nofollow">电压</a>
								</li>
                                <li>
								<a href="http://208.109.123.18/tour_america_need.php#a9" rel="nofollow">消费状况</a>
								<a href="http://208.109.123.18/tour_america_need.php#a5" rel="nofollow">电话</a>
								<a href="http://208.109.123.18/tour_america_need.php#a6" rel="nofollow">气候</a>
								<a href="http://208.109.123.18/tour_america_need.php#a7" rel="nofollow">治安</a>
								</li>
                                <li>
								<a href="http://208.109.123.18/tour_america_need.php#a11" rel="nofollow">美国节日</a>
								<a href="http://208.109.123.18/tour_america_need.php#a8" rel="nofollow">宗教</a>
								<a href="http://208.109.123.18/tour_america_need.php#a10" rel="nofollow">文化</a>
								<a href="http://208.109.123.18/tour_america_need.php#a12" rel="nofollow">美食</a>
								</li>
                                <li>
								<a href="http://208.109.123.18/tour_america_need.php#a16" rel="nofollow">注意事项</a>
								<a href="http://208.109.123.18/tour_america_need.php#a13" rel="nofollow">住宿</a>
								<a href="http://208.109.123.18/tour_america_need.php#a14" rel="nofollow">交通</a>
								<a href="http://208.109.123.18/tour_america_need.php#a15" rel="nofollow">购物</a>
								</li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="site_helpitems">
                            <h4 class="title">会员积分</h4>
                            <ul>
                                <li><a href="http://208.109.123.18/faq_points.php#list1" rel="nofollow">什么是积分，有什么用？</a></li>
                                <li><a href="http://208.109.123.18/faq_points.php#list2" rel="nofollow">获得积分的途径有哪些？</a></li>
                                <li><a href="http://208.109.123.18/faq_points.php#list4" rel="nofollow">积分的现金兑换标准是多少？</a></li>
                                <li><a class="highlight" href="http://208.109.123.18/faq_points.php#list5" rel="nofollow">积分享受的消费折扣限额是？</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="last">
                        <div class="site_helpitems">
                            <h4 class="title">签证相关</h4>
                            <ul class="">
                                <li><a class="highlight" href="http://208.109.123.18/visa_related.php#question_1" rel="nofollow">去美国旅游，如何拿到签证？</a></li>
                                <li><a href="http://208.109.123.18/visa_related.php#question_2" rel="nofollow">加拿大签证怎么办理？</a></li>
                                <li><a href="http://208.109.123.18/visa_related.php#question_27" rel="nofollow">签证官一般会问哪些问题？</a></li>
                                <li><a href="http://208.109.123.18/visa_related.php#question_28" rel="nofollow">什么是签证培训？有何帮助？</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="imagelink uifix">
                <li><a class="link1" href="http://www.bbb.org/baton-rouge/business-reviews/travel-agencies-and-bureaus/unitedstars-international-ltd-in-baton-rouge-la-90012303" rel="nofollow">BBB</a></li>
                <li><a class="link2" href="#" rel="nofollow">rackspace</a></li>
                <li><a class="link3" href="http://208.109.123.18/insurance.php" rel="nofollow">在线投保</a></li>
                <li><a class="link4" href="http://208.109.123.18/payment.php" rel="nofollow">paypal</a></li>
                <li><a class="link5" href="http://cn.unionpay.com/" rel="nofollow">在线支付</a></li>
                <li><a class="link6" href="https://www.alipay.com/s" rel="nofollow">支付宝支付</a></li>
                <li><a class="link7" href="http://t.qq.com/usItrip2012" rel="nofollow">腾讯微博</a></li>
                <li><a class="link8" href="http://weibo.com/usitrip" rel="nofollow">新浪微博</a></li>
            </ul>
            <dl class="hotlinks uifix">
                <dt>出发城市：</dt>
                <dd>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%C2%E5%C9%BC%ED%B6">洛杉矶旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%BE%C9%BD%F0%C9%BD">旧金山旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%C0%AD%CB%B9%CE%AC%BC%D3%CB%B9">拉斯维加斯旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%C5%A6%D4%BC">纽约旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%BB%AA%CA%A2%B6%D9">华盛顿旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%B2%A8%CA%BF%B6%D9">波士顿旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?fcw=%CE%C2%B8%E7%BB%AA">温哥华旅游</a>
				</dd>
            </dl>
            <dl class="hotlinks uifix">
                <dt>热门景点：</dt>
                <dd>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%BB%C6%CA%AF%B9%AB%D4%B0">黄石公园旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%B4%F3%CF%BF%B9%C8">大峡谷旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%D3%C5%CA%A4%C3%C0%B5%D8">优胜美地旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%D6%F7%CC%E2%B9%AB%D4%B0">主题公园旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%C4%E1%D1%C7%BC%D3%C0%AD%C6%D9%B2%BC">尼亚加拉瀑布旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%C2%E4%BB%F9%C9%BD">落基山旅游</a>
				<a href="http://208.109.123.18/advanced_search_result.php?w=%CF%C4%CD%FE%D2%C4">夏威夷旅游</a>
				</dd>
            </dl>
            <dl class="friendlinks uifix">
                <dt>友情链接：</dt>
                <dd>
					<a target="_blank" href="http://www.huangshanzjy.com">黄山旅游攻略</a>
					|
					<a target="_blank" href="http://shenzhen.9chun.com">深圳旅游网</a>
					|
					<a target="_blank" href="http://kunming.lotour.com">昆明旅游网</a>
					|
					<a target="_blank" href="http://www.1988hn.com">海南旅游</a>
					|
					<a target="_blank" href="http://www.ejpw.com">广州机票</a>
					|
					<a target="_blank" href="http://www.9sc.cn">九寨沟旅游</a>
					|
					<a target="_blank" href="http://www.hongluwenquan.com">红栌山庄</a>
					|
					<a target="_blank" href="http://www.ss0773.com">桂林旅游攻略</a>
					|
					<a target="_blank" href="http://www.mfspace.cn">新疆旅游景点</a>
					|
					<a target="_blank" href="http://www.fhlyou.net">凤凰古城</a>
					|
					<a target="_blank" href="http://www.680go.com">南京旅游</a>
					|
					<a target="_blank" href="http://chengdu.lotour.com">成都旅游网</a>
					|
					<a target="_blank" href="http://www.bescn.com">旅游营销</a>
					|
					<a target="_blank" href="http://www.0431ly.com">长春旅行社</a>
					|
					<a target="_blank" href="http://www.yjldp.com">长岛旅游</a>
					|
					<a target="_blank" href="http://www.ehome365.cn">酒店预订</a>
					|
					<a target="_blank" href="http://www.qianu.net">汉庭酒店官网</a>
					|
					<a target="_blank" href="http://www.hntour.net.cn">湖南旅游网</a>
					|
					<a target="_blank" href="http://photo.szooo.com">深圳康辉相册</a>
					|
					<a target="_blank" href="http://www.7blas.com">北戴河旅游网</a>
					|
					<a target="_blank" href="http://www.niuyuelvyou.com">美国旅游网</a>
					|
					<a target="_blank" href="http://www.yabuliskee.org">哈尔滨滑雪场</a>
					|
					<a target="_blank" href="http://www.gowulong.com">武隆自助游</a>
					|
					<a target="_blank" href="http://www.tt-ly.com">深圳康辉旅行社</a>
					|
					<a target="_blank" href="http://www.tmyou.com.cn">西安天马旅行社</a>
					<a class="color_orange" href="http://208.109.123.18/links.html">更多&gt;&gt;</a>
                </dd>
            </dl>
            <div class="copyright">
                <ul>
                    <li><a href="http://208.109.123.18/">网站首页</a>-</li>
                    <li><a href="http://208.109.123.18/about_us.php">关于走四方</a>-</li>
                    <li><a href="http://208.109.123.18/contact_us.php">联系我们</a>-</li>
                    <li><a href="http://208.109.123.18/privacy-policy.php">隐私&amp;版权声明</a>-</li>
                    <li><a href="http://208.109.123.18/links.html">友情链接</a>-</li>
                    <li><a href="http://208.109.123.18/faq_question.php">帮助中心</a>-</li>
                    <li><a href="http://208.109.123.18/sitemap.html">网站地图</a>-</li>
                    <li><a href="http://208.109.123.18/affiliate.php">网站联盟</a></li>
                </ul>
                <p>营业执照 | Copyright&copy;2008-2012 usitrip.com | 走四方旅游网 All rights reserved.</p>
            </div>
        </div>
    </div>
<?php
}
?>
