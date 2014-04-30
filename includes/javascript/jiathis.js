;(function(a){
	
	a.jiathis = {};
	a.jiathis.defaults = {
		'webid' : [
			{'name':'email',        'title':'邮件'},//		http://www.jiathis.com
			{'name':'ishare',       'title':'一键分享'},//	http://www.jiathis.com
			{'name':'ujian',        'title':'猜你喜欢'},//	http://ujian.cc
			//{'name':'copy',         'title':'复制网址'},//	---
			//{'name':'fav',          'title':'收藏夹'},//	---
			//{'name':'print',        'title':'打印'},//	---
			{'name':'tsina',        'title':'新浪微博'},//	http://weibo.com
			{'name':'qzone',        'title':'QQ空间'},//	http://qzone.qq.com
			{'name':'feixin',       'title':'飞信'},//	http://feixin.10086.cn
			{'name':'tqq',          'title':'腾讯微博'},//	http://v.t.qq.com/
			{'name':'tieba',        'title':'百度贴吧'},//	http://tieba.baidu.com
			{'name':'tsohu',        'title':'搜狐微博'},//	http://t.sohu.com
			{'name':'kaixin001',    'title':'开心网'},//	http://www.kaixin001.com
			{'name':'weixin',       'title':'微信'},//	http://weixin.qq.com/
			{'name':'renren',       'title':'人人网'},//	http://www.renren.com
			{'name':'douban',       'title':'豆瓣'},//	http://www.douban.com
			{'name':'taobao',       'title':'淘江湖'},//	http://i.taobao.com
			{'name':'cqq',          'title':'QQ好友'},//	http://connect.qq.com/
			{'name':'t163',         'title':'网易微博'},//	http://t.163.com
			{'name':'mogujie',      'title':'蘑菇街'},//	http://www.mogujie.com
			{'name':'meilishuo',    'title':'美丽说'},//	http://www.meilishuo.com
			{'name':'baidu',        'title':'百度搜藏'},//	http://www.baidu.com
			{'name':'xiaoyou',      'title':'朋友网'},//	http://www.pengyou.com/
			{'name':'msn',          'title':'MSN'},//	http://cn.msn.com/
			{'name':'hi',           'title':'百度空间'},//	http://hi.baidu.com
			{'name':'fb',           'title':'Facebook'},//	http://www.facebook.com
			{'name':'tifeng',       'title':'凤凰微博'},//	http://t.ifeng.com
			{'name':'qq',           'title':'QQ收藏'},//	http://shuqian.qq.com/
			{'name':'waakee',       'title':'挖客网'},//	http://www.waakee.com
			{'name':'mop',          'title':'猫扑推客'},//	http://tk.mop.com
			{'name':'twitter',      'title':'Twitter'},//	http://twitter.com
			{'name':'139',          'title':'移动微博'},//	http://weibo.10086.cn/
			{'name':'51',           'title':'51社区'},//	http://www.51.com/
			{'name':'txinhua',      'title':'新华微博'},//	http://t.home.news.cn
			{'name':'tianya',       'title':'天涯社区'},//	http://my.tianya.cn/
			{'name':'tpeople',      'title':'人民微博'},//	http://t.people.com.cn
			{'name':'cnfol',        'title':'中金微博'},//	http://t.cnfol.com
			{'name':'youshi',       'title':'优士网'},//	http://www.ushi.cn/
			{'name':'toeeee',       'title':'南方微博'},//	http://t.oeeee.com
			{'name':'fanfou',       'title':'饭否'},//	http://fanfou.com
			{'name':'xianguo',      'title':'鲜果'},//	http://www.xianguo.com
			{'name':'gmail',        'title':'Gmail邮箱'},//	http://www.gmail.com
			{'name':'tuita',        'title':'推他'},//	http://www.tuita.com
			{'name':'huaban',       'title':'花瓣网'},//	http://huaban.com
			{'name':'189share',     'title':'手机快传'},//	http://s.189share.com
			{'name':'hexun',        'title':'和讯'},//	www.hexun.com
			{'name':'youdao',       'title':'有道书签'},//	http://account.youdao.com
			{'name':'115',          'title':'115收藏'},//	http://fav.115.com
			{'name':'buzz',         'title':'谷歌Buzz'},//	http://www.google.com/reader/
			{'name':'ifensi',       'title':'粉丝网'},//	http://cyworld.ifensi.com
			{'name':'phonefavs',    'title':'PhoneFavs'},//	http://phonefavs.com
			{'name':'baohe',        'title':'宝盒网'},//	http://www.baohe.com/
			{'name':'googleplus',   'title':'Google+'},//	https://plus.google.com
			{'name':'yijee',        'title':'易集网'},//	http://www.yijee.com
			{'name':'renjian',      'title':'人间网'},//	http://www.renjian.com
			{'name':'duitang',      'title':'堆糖'},//	http://www.duitang.com
			{'name':'linkedin',     'title':'LinkedIn'},//	http://www.linkedin.com
			{'name':'leshou',       'title':'乐收'},//	http://leshou.com
			{'name':'fwisp',        'title':'fwisp'},//	http://fwisp.com/
			{'name':'ydnote',       'title':'有道云笔记'},//	http://note.youdao.com
			{'name':'w3c',          'title':'w3c验证'},//	http://validator.w3.org
			{'name':'masar',        'title':'玛撒网'},//	http://www.masar.cn/
			{'name':'funp',         'title':'FunP'},//	http://www.funp.com
			{'name':'stumbleupon',  'title':'StumbleUpon'},//	http://www.stumbleupon.com
			{'name':'douban9dian',  'title':'豆瓣9点'},//	http://9.douban.com/
			{'name':'google',       'title':'谷歌'},//	http://www.google.com
			{'name':'gmw',          'title':'光明网'},//	http://blog.gmw.cn
			{'name':'friendfeed',   'title':'FriendFeed'},//	http://www.friendfeed.com
			{'name':'wong',         'title':'Mister Wong'},//	http://www.mister-wong.cn
			{'name':'wealink',      'title':'若邻网'},//	http://www.wealink.com
			{'name':'digg',         'title':'Digg'},//	http://www.digg.com
			{'name':'99earth',      'title':'救救地球'},//	http://www.99earth.org
			{'name':'pingfm',       'title':'Ping.fm'},//	http://ping.fm
			{'name':'delicious',    'title':'Delicious'},//	http://del.icio.us
			{'name':'hotmail',      'title':'Hotmail邮箱'},//	http://www.hotmail.com
			{'name':'diigo',        'title':'Diigo'},//	http://www.diigo.com
			{'name':'poco',         'title':'Poco'},//	http://www.poco.cn
			{'name':'ganniu',       'title':'赶牛网'},//	http://www.ganniu.com/
			{'name':'miliao',       'title':'米聊'},//	http://www.miliao.com
			{'name':'faxianla',     'title':'发现啦'},//	http://faxianla.com
			{'name':'evernote',     'title':'EverNote'},//	http://www.evernote.com/
			{'name':'dig24',        'title':'递客网'},//	http://www.dig24.cn/
			{'name':'qingbiji',     'title':'轻笔记'},//	http://www.qingbiji.cn
			{'name':'diandian',     'title':'点点网'},//	http://www.diandian.com
			{'name':'bitly',        'title':'Bit.ly'},//	http://bit.ly
			{'name':'plaxo',        'title':'Plaxo'},//	http://www.Plaxo.com
			{'name':'mixx',         'title':'Mixx'},//	http://www.mixx.com
			{'name':'digu',         'title':'嘀咕网'},//	http://www.digu.com
			{'name':'reddit',       'title':'Reddit'},//	http://www.reddit.com
			{'name':'myshare',      'title':'Myshare'},//	http://myshare.url.com.tw/
			{'name':'189cn',        'title':'天翼社区'},//	http://club.189.cn
			{'name':'netlog',       'title':'NetLog'},//	http://www.netlog.com
			{'name':'mailru',       'title':'Mail.ru'},//	http://www.mail.ru
			{'name':'ifengkb',      'title':'凤凰快博'},//	http://k.ifeng.com
			{'name':'pdfonline',    'title':'PDF Online'},//	http://www.pdfonline.com
			{'name':'netvibes',     'title':'Netvibes'},//	http://www.netvibes.com
			{'name':'kansohu',      'title':'搜狐随身看'},//	http://kan.sohu.com
			{'name':'myspace',      'title':'Myspace'},//	http://www.myspace.com
			{'name':'qingsina',     'title':'新浪轻博'},//	http://qing.weibo.com
			{'name':'mingdao',      'title':'明道'},//	http://www.mingdao.com
			{'name':'sdonote',      'title':'麦库'},//	http://note.sdo.com
			{'name':'xqw',          'title':'雪球'},//	http://xueqiu.com
			{'name':'tyaolan',      'title':'摇篮网'},//	http://t.yaolan.com
			{'name':'tumblr',       'title':'Tumblr'},//	http://www.tumblr.com
			{'name':'thexun',       'title':'和讯微博'},//	http://t.hexun.com
			{'name':'printfriendly','title':'PrintFriendly'},//	http://www. printfriendly.com
			{'name':'plurk',        'title':'Plurk'},//	http://www.plurk.com
			{'name':'tianji',       'title':'天际网'},//	http://www.tianji.com
			{'name':'pinterest',    'title':'pinterest'},//	http://pinterest.com
			{'name':'i139',         'title':'爱分享'},//	http://go.10086.cn
			{'name':'caimi',        'title':'财迷'},//	http://t.eastmoney.com
			{'name':'instapaper',   'title':'Instapaper'},//	http://www.instapaper.com
			{'name':'139mail',      'title':'139邮箱'},//	http://mail.10086.cn/
			{'name':'translate',    'title':'谷歌翻译'},//	http://translate.google.com/
			{'name':'189mail',      'title':'189邮箱'},//	http://mail.189.cn
			{'name':'iwo',          'title':'WO+分享'},//	http://i.wo.com.cn/
			{'name':'dream163',     'title':'游戏江湖'},//	http://hi.163.com
			{'name':'chouti',       'title':'抽屉网'},//	http://www.chouti.com/
			{'name':'cyzone',       'title':'创业邦'},//	http://u.cyzone.cn
			{'name':'binzhi',       'title':'宾至网'},//	http://www.binzhi.com
			{'name':'leihou',       'title':'雷猴'},//	http://leihou.com
			{'name':'jcrb',         'title':'法律微博'},//	http://t.jcrb.com
			{'name':'pocket',       'title':'Pocket'},//	http://getpocket.com
			{'name':'gjl',          'title':'逛街啦'},//	http://www.guangjiela.com
			{'name':'ymail',        'title':'Yahoo! mail'},//	http://mail.yahoo.com
			{'name':'zsvs',         'title':'中搜v商'},//	http://www.zhongsou.net
		],
		'uid':'1830335',
		'currentId':''
	};

	/**
	 * 初始化，针对所有对象进行初始化 
	 */
	a.jiathis.init = function(obj){
		init_div();
		obj.each(function(){
			topShare(this);
		});
	}
	function getCookie(c) {
    	var d = document.cookie.split("; ");
    	for (var b = 0; b < d.length; b++) {
	        var a = d[b].split("=");
        	if (a[0] == c) {
	            return unescape(a[1]);
        	}
    	}
    	return '';
	};
	function setCookie(name,value,day) {
		day = day || 0;
		var str = name + '=' + escape(value);
		if (day > 0) {
			var date = new Date();
			var ms = parseFloat(day,10) * 24 * 3600 * 1000;
			date.setTime(date.getTime() + ms);
			str += ";expires=" + date.toGMTString();
		}
		document.cookie = str;
	};
	function updateCookie(name,value,day) {
		var oldval = getCookie(name);
		var reg = new RegExp("(^|,)" + value + "(,|$)");
		reg.global = true;
		reg.ignoreCase = true;
		reg.multiline = true;
		if (reg.test(oldval)) {
			oldval = oldval.replace(reg,'$1');
			oldval = oldval.replace(/,$/,'');
		}
		value = (oldval != '' ? value + ',' + oldval : value);
		value = value.replace(/,,/img,',');
		setCookie(name,value,day);
	}

	function init_div() {
		// 小框
		var html ='<div class="jiathis_style hoverpop" style="position: absolute; z-index: 1000000000; display: none; overflow: auto; top: 14px; left: 1366px;">';
		html += '<div class="jiadiv_01">';
		html += '<div style="width:100%;background:#F2F2F2;border-bottom:1px solid #E5E5E5;line-height:180%;padding-left:5px;font-size:12px">';
		html += '<span style="text-align:left;font-size:14px;font-weight:bold;color:#000000;">分享到</span>';
		html += '</div>';
		html += '<div id="jiathis_sers" style="width:100%;" class="jiadiv_02">';
		var my_c = cookieOrderBy();
		var topShareArr = my_c.topShareArr;
		var oldShareLen = topShareArr.length;
		var myWebid = my_c.myWebid;
		for(var i=0;i<6;i++){
			var stitle = (i<oldShareLen ? topShareArr[i].title : myWebid[i-oldShareLen].title);
			var sname = (i<oldShareLen ? topShareArr[i].name : myWebid[i-oldShareLen].name);
			html += '<a class="jiatitle" title="分享到'+stitle+'" onclick="jiathis.Share(this);return false;" id="' + sname + '" href="javascript:;">';
			html += '<span class="jtico jtico_' + sname +'">' + stitle + '</span></a>';
		}
		html += '<a class="jiatitle" onclick="jiathis.center(this);return false;" href="javascript:;"><span class="jtico jtico_jiathis">查看更多(124)</span></a>';
		html += '<div style="clear:both"></div>';
		html += '</div>';
		html += '</div></div>';
		
		// 大框
		html += '<div class="jiathis_style fullhoverpop" style="position: absolute; z-index: 1000000000; display: none; top: 50%; left: 50%; overflow: auto; ">';
		html += '<div style="border:10px solid #7F7F7F; width:300px;"><div class="jiadiv_01" style="width:300px;">';
		html += '<div style="background:#F2F2F2;line-height:100%;height:30px;overflow:hidden;width:300px;">';
		html += '<img border="0" onclick="jiathis.centerClose();" style="margin:8px 5px 4px 4px;cursor:pointer;float:right;" src="http://v2.jiathis.com/code_mini/images/img_exit.gif">';
		html += '<span style="text-align:left;font-size:12px;display:inline-block;line-height:20px;">分享到各大网站</span></div>';
		html += '<div style="width:300px;height:300px;overflow-y:auto;" class="jiadiv_02" id="jiathis_sharelist">';
		for(var i=0,len=myWebid.length;i<len;i++){
			var stitle = (i<oldShareLen ? topShareArr[i].title : myWebid[i-oldShareLen].title);
			var sname = (i<oldShareLen ? topShareArr[i].name : myWebid[i-oldShareLen].name);
			html += '<a class="jiatitle" title="分享到'+stitle+'" onclick="jiathis.Share(this);return false;" id="' + sname + '" href="javascript:;">';
			html += '<span class="jtico jtico_' + sname +'">' + stitle + '</span></a>';
		}
		html += '</div></div></div></div>';
		a('body').append(html);
		a('div.hoverpop').hover(function(){},function(){
			a(this).css('display','none');
			if (a('div.fullhoverpop').css('display') == 'none') {
				a.jiathis.defaults.currentId = '';
			}
		});
	}
	
	/**
	 *  根据参数跳转到对应的分享 
	 */
	a.jiathis.Share = function(obj){
		obj = a(obj);
		var webid = obj.attr('id');
		updateCookie('usi_share',webid,60);
		var share_obj = a.jiathis.defaults.currentId == '' ? obj.parents("div[id^='ProductsObj_']") : a('#'+a.jiathis.defaults.currentId);
		share_obj_a = share_obj.find('h2').find('a');
		var href = share_obj_a.attr('href');
		var title = share_obj_a.html();
		var pic = share_obj.find('div.proImg').find('img').attr('src');
		var url = "http://www.jiathis.com/send/?webid=" + obj.attr('id') + "&url=" + href + 
			"&title=" + title + "&uid=" + a.jiathis.defaults.uid;
		if (pic != '') {
			url += '&pic=' + pic;
		}
		window.open(url,'_blank');
	};
	
	/**
	 * 根据提供的webid取得对应的名称与ID 
	 */
	function findWebidObjByWebid(webidArr,c_webid){
		var rtn = [];
		for(var i=0,len=webidArr.length;i<len;i++) {
			for(var j=0,jlen=c_webid.length;j<jlen;j++) {
				if (c_webid[j].name == webidArr[i]) {
					rtn[rtn.length] = c_webid[j];
					c_webid.splice(j,1);
					break;
				}
			}
		}
		return rtn;
	}
	
	/**
	 *用户常用的分享 
     * @param Object obj 分享容器对象
	 */
	function topShare(obj){
		//var html_a = obj.getElementsByTagName('a');
		var hover_a = a(obj).find('.jtico_jiathis');
		hover_a.hover(
			function(){
				var pop = a('div.hoverpop');
				var ofs = a(this).offset();
				pop.css({'display':'block','left':ofs.left,'top':ofs.top,'paddingTop':a(this).height()});
				a.jiathis.defaults.currentId = a(this).parents("div[id^='ProductsObj_']").attr('id');
			},
			function(){
				
			}
		);
		// 如果COOKIE没有值 则显示默认的几个
		var topShareClass = 'jiathis_txt jiathis_separator jtico ';
		//html_a = findByClass(html_a);
		var html_a = a(obj).find('a[class^="jiathis_button_tools_"]');
		var my_c = cookieOrderBy();
		var topShareArr = my_c.topShareArr;
		var oldShareLen = topShareArr.length;
		var myWebid = my_c.myWebid;
		html_a.each(function(i){
			var stitle = (i<oldShareLen ? topShareArr[i].title : myWebid[i-oldShareLen].title);
			var sname = (i<oldShareLen ? topShareArr[i].name : myWebid[i-oldShareLen].name);
			a(this).attr('title','分享到'+stitle);
			a(this).attr('id',sname);
			a(this).append('<span class="' + topShareClass + 'jtico_' + sname + '">' + stitle + '</span>');
			a(this).click(function(){
				a.jiathis.Share(this);
			});
			
		});
	};
	
	function cookieOrderBy(){
		var topShareArr = getCookie('usi_share');
		topShareArr = (topShareArr != '' ? topShareArr.split(',') : []);
		var myWebid = a.extend([], a.jiathis.defaults.webid);
		topShareArr = findWebidObjByWebid(topShareArr,myWebid);
		return {'topShareArr':topShareArr,'myWebid':myWebid};
	}
	

	/**
	 * 点击显示更多的时候，显示全部可分享的东西 
 	 * @param object obj 点击调用的那个标准
	 */
	a.jiathis.center = function(obj){
		var els = a('div.fullhoverpop');
		var scrollTop = a(document).scrollTop();
		var top = a(window).height();
		var left = a(window).width();
		var myH = els.height();
		var myW = els.width();
		top = (top - myH)/2 + scrollTop;
		left = (left - myW)/2 + a(document).scrollLeft();
		els.css({'display':'block','top':top,'left':left});
	};
	
	a.jiathis.centerClose = function() {
		a('div.fullhoverpop').css('display','none');
		a.jiathis.defaults.currentId='';
	}
	
	window.jiathis = a.jiathis;
})(jQuery);
