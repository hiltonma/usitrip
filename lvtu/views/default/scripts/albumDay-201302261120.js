(function($) {
	$.fn.lazyload = function(options) {
		var settings = {
			threshold: 0,
			failurelimit: 0,
			event: "scroll",
			effect: "show",
			container: window
		};
		if (options) {
			$.extend(settings, options)
		}
		var elements = this;
		if ("scroll" == settings.event) {
			$(settings.container).bind("scroll",
			function(event) {
				var counter = 0;
				elements.each(function() {
					if (!$.belowthefold(this, settings) && !$.rightoffold(this, settings)) {
						$(this).trigger("appear")
					} else {
						if (counter++>settings.failurelimit) {
							return false
						}
					}
				});
				var temp = $.grep(elements,
				function(element) {
					return ! element.loaded
				});
				elements = $(temp)
			})
		}
		return this.each(function() {
			var self = this;
			$(self).attr("original", $(self).attr("src"));
			if ("scroll" != settings.event || $.belowthefold(self, settings) || $.rightoffold(self, settings)) {
				if (settings.placeholder) {
					$(self).attr("src", settings.placeholder)
				} else {
					$(self).removeAttr("src")
				}
				self.loaded = false
			} else {
				self.loaded = true
			}
			$(self).one("appear",
			function() {
				if (!this.loaded) {
					$("<img />").bind("load",
					function() {
						$(self).hide().attr("src", $(self).attr("original"))[settings.effect](settings.effectspeed);
						self.loaded = true
					}).attr("src", $(self).attr("original"))
				}
			});
			if ("scroll" != settings.event) {
				$(self).bind(settings.event,
				function(event) {
					if (!self.loaded) {
						$(self).trigger("appear")
					}
				})
			}
		})
	};
	$.belowthefold = function(element, settings) {
		if (settings.container === undefined || settings.container === window) {
			var fold = $(window).height() + $(window).scrollTop()
		} else {
			var fold = $(settings.container).offset().top + $(settings.container).height()
		}
		return fold <= $(element).offset().top - settings.threshold
	};
	$.rightoffold = function(element, settings) {
		if (settings.container === undefined || settings.container === window) {
			var fold = $(window).width() + $(window).scrollLeft()
		} else {
			var fold = $(settings.container).offset().left + $(settings.container).width()
		}
		return fold <= $(element).offset().left - settings.threshold
	};
	$.extend($.expr[':'], {
		"below-the-fold": "$.belowthefold(a, {threshold : 0, container: window})",
		"above-the-fold": "!$.belowthefold(a, {threshold : 0, container: window})",
		"right-of-fold": "$.rightoffold(a, {threshold : 0, container: window})",
		"left-of-fold": "!$.rightoffold(a, {threshold : 0, container: window})"
	})
})(jQuery);




function checkbrowse() {
	var b = navigator.userAgent.toLowerCase();
	var d = (b.match(/\b(chrome|opera|safari|msie|firefox)\b/) || ["", "mozilla"])[1];
	var c = "(?:" + d + "|version)[\\/: ]([\\d.]+)";
	var a = (b.match(new RegExp(c)) || [])[1];
	jQuery.browser.is = d;
	jQuery.browser.ver = a;
	return {
		is: jQuery.browser.is,
		ver: jQuery.browser.ver
	};
}
var publicCheck = checkbrowse();
var showeffect = "";
if ((publicCheck.is == "msie" && publicCheck.ver < 8)) {
	showeffect = "show";
} else {
	showeffect = "fadeIn";
}
jQuery(document).ready(function(a) {
	a("img.qpic").lazyload({
		placeholder: "../image/lvtu_bg_2.jpg",//"http://source.qunar.com/site/images/wap/lvtu/lvtu_bg_2.jpg",
		effect: showeffect,
		failurelimit: 2
	});
}); (function(a) {
	a.scrollBtn = function(e) {
		var k = a.extend({},
		a.scrollBtn.defaults, e);
		var r = a("<div></div>").css({
			bottom: k.bottom + "px",
			right: "50%"
		}).addClass("scroll-up").attr("title", k.title).click(function() {
			a("html, body").animate({
				scrollTop: 0
			},
			k.duration);
		}).appendTo("body");
		var f = a("#contentSina").val();
		var b = a("#contentQqWeibo").val();
		var l = a("#contentQzone").val();
		var j = a("#contentKaixin").val();
		var h = a("#contentRenRen").val();
		var t = a("#contentRenRen").val();
		var v = encodeURIComponent(a("#picUrlAlbum").val());
		var d = a("#albumName").val();
		var n = a("#metaDesc").val();
		var g = document.URL;
		g = encodeURIComponent(g);
		var p = encodeURIComponent(d + "������ȥ�Ķ���ͼ��");
		var u = encodeURIComponent(n);
		var i = encodeURIComponent(d + "������ȥ�Ķ���ͼ��");
		var s = a("<div class='album_bind_nav'><ul><li class='album_bind_sina'><a onclick=shareAlbum(2,'" + v + "') href='http://service.weibo.com/share/share.php?c=share&a=index&appkey=&title=" + encodeURIComponent(f) + "&pic=" + v + "&url=" + g + "' target='_blank' title='���?����΢��'>����΢��</a></li><li class='album_bind_qqlg'><a  onclick=shareAlbum(3,'" + v + "') href='http://v.t.qq.com/share/share.php?title=" + encodeURIComponent(b) + "&pic=" + v + "' target='_blank' title='���?��Ѷ΢��'>��Ѷ΢��</a></li><li class='album_bind_qzone'><a onclick=shareAlbum(7,'" + v + "') href='http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + g + "&title=" + encodeURIComponent(d) + "������ȥ�Ķ���ͼ��&pics=" + v + "&summary=" + n + "' target='_blank' title='���?QQ�ռ�'>QQ�ռ�</a></li><li class='album_bind_renren'><a  onclick=shareAlbum(9,'" + v + "') href='http://widget.renren.com/dialog/share?link=" + g + "&title=" + p + "&description=" + u + "&pic=" + v + "' target='_blank' title='���?������'>������</a></li><li class='album_bind_douban'><a  onclick=shareAlbum(6,'" + v + "') href='http://www.douban.com/recommend/?url=" + g + "&image=" + v + "&title=" + i + "&comment= ' target='_blank' title='���?������'>������</a></li></ul></div>").css({
			bottom: k.bottom + 50 + "px",
			right: "50%",
			display: "block"
		}).appendTo("body");
		if (q()) {
			var c = a(document).scrollTop(),
			o = a(window).height();
			var m = o + c - r.outerHeight() - k.bottom;
			r.css("top", m + "px");
			s.css("top", m - 118 + "px");
		}
		a(window).bind("scroll",
		function() {
			var y = a(document).scrollTop(),
			w = a(window).height();
			if (y <= k.showScale) {
				if (r.is(":visible")) {
					r.fadeOut(500);
				}
			} else {
				if (r.is(":hidden")) {
					r.fadeIn(500);
				}
			}
			if (q()) {
				var x = w + y - r.outerHeight() - k.bottom;
				r.css("top", x + "px");
				s.css("top", x - 118 + "px");
			}
		});
		function q() {
			if (a.browser.msie) {
				if (a.browser.version == "6.0") {
					return true;
				}
			}
		}
	};
	a.scrollBtn.defaults = {
		showScale: 100,
		right: 10,
		bottom: 10,
		duration: 200,
		title: "���ض���"
	};
})(jQuery);
$.scrollBtn({
	showScale: 200,
	bottom: 122,
	right: 20
});
function shareAlbum(c, b) {
	var a = $("#albumId").val();
	var d = {
		shareTarget: c,
		type: "album",
		id: a,
		url: b
	};
	$.getJSON(pathUpload + "web/shareLog.htm", d,
	function(e) {});
}
eval(function(h, b, j, f, g, i) {
	g = function(a) {
		return (a < b ? "": g(parseInt(a / b))) + ((a = a % b) > 35 ? String.fromCharCode(a + 29) : a.toString(36));
	};
	if (!"".replace(/^/, String)) {
		while (j--) {
			i[g(j)] = f[j] || g(j);
		}
		f = [function(a) {
			return i[a];
		}];
		g = function() {
			return "\\w+";
		};
		j = 1;
	}
	while (j--) {
		if (f[j]) {
			h = h.replace(new RegExp("\\b" + g(j) + "\\b", "g"), f[j]);
		}
	}
	return h;
} ("(8(a){a.7=8(b,c){c=a.1n({},a.7.17,c);c.1M=c.1V>9;c.1p=c.1p||1q;c.1A=c.1A||1q;5(b==1y){b=''};5(c.W<9){c.W=9};5(c.1t==1y){c.1t='51'+2U.52(2U.3i()*4Z)};6 d=(a.1U.2e&&3u(a.1U.3a)<3r);6 e=a('#'+c.1t);5(e.1I>9){c.1k=a.7.17.1k++;e.16({1k:c.1k});e.10('#4').16({1k:c.1k+1h});1j e};6 f={2g:'',1x:'',1e:'',2j:b.50==53};5(!f.2j){b=b+'';6 N=b.56();5(N.1X('1t:')==9)f.1x='4t';1b 5(N.1X('4C:')==9)f.1x='35';1b 5(N.1X('57:')==9)f.1x='30';1b 5(N.1X('1F:')==9)f.1x='2w';1b 5(N.1X('1e:')==9)f.1x='3h';1b{b='1e:'+b;f.1x='3h'};b=b.54(b.1X(\":\")+1h,b.1I)};5(!c.1p&&!c.1A&&!c.4s){a(a.1U.2e?'1e':'1z').3c('11','2a:2l;1f-27:55;')};6 g=!c.1p&&!(c.1o==1y);6 h=f.1x=='35'||f.1x=='30'||f.1x=='2w';6 i=1L c.14=='36'?(c.14-4Y)+'19':\"4R%\";6 j=[];j.X('<Z 1t=\"'+c.1t+'\" 1a=\"4-'+(c.1p?'1v':(c.1A?'3l':'1z'))+'\">');5(c.1M){5((d&&a('1F').1I>9)||a('4S, 4P').1I>9){j.X('<1F 1t=\"4-24\" 1a=\"4-24\" 3y=\"3M:3X\" 11=\"1D:2R;1s:1B;z-3t:-1;\"></1F>')}1b{5(d){a('3Z').16('3L','2l')};j.X('<Z 1t=\"4-24\" 1a=\"4-24\" 11=\"1s:1B;\"></Z>')}};j.X('<Z 1t=\"4-3m\" 1a=\"4-3m\" 11=\"14:1c;Y:1c;4Q-4x:#4T;1s:1B;z-3t:45;4W:1d;\"></Z>');5(c.1Y){j.X('<Z 1t=\"4-47\" 1a=\"4-47\" 11=\"1s:1B;z-3t:45;1D:1d;\"></Z>')};j.X('<Z 1t=\"4\" 1a=\"4\" 11=\"1s:1B;14:1m;Y:1m;\">');j.X('<Z 1a=\"4-2W-1o 4-1o-23\" 11=\"Y:2s;1D:1d;\"></Z>');j.X('<Z 1a=\"4-2W-1l 4-1l-23\" 11=\"Y:2s;1f:2Z 0 2Z 0;1D:1d;\"></Z>');j.X('<4p W=\"0\" 4X=\"0\" 4U=\"0\" 11=\"1E:1c;1f:1c;W:1d;\">');5(c.W>9){j.X('<2n>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:'+c.W+'19 0 0 0;14:'+c.W+'19;Y:'+c.W+'19;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;Y:'+c.W+'19;2a: 2l;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:0 '+c.W+'19 0 0;14:'+c.W+'19;Y:'+c.W+'19;\"></1u>');j.X('</2n>')};j.X('<2n>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;\"></1u>');j.X('<1u 4V=\"18\" 11=\"1E:1c;1f:1c;W:1d;\">');j.X('<Z 1a=\"4-4v\" 11=\"14:1m; Y:1m;\">');j.X('<a 1a=\"4-1J\" 1o=\"'+a.7.1S.1J+'\" 58=\"$(21).2x(\\'4-1J-2E\\');\" 5l=\"$(21).2I(\\'4-1J-2E\\');\" 11=\"1s:1B; 1D:2R; 4w:5m; 18:'+(4D+c.W)+'19; 27:'+(4D+c.W)+'19; 14:4z; Y:4z;'+(c.34?'':'1D:1d;')+'\"></a>');5(g){j.X('<Z 1a=\"4-1o-23\" 11=\"Y:2s;\">');j.X('<Z 1a=\"4-1o'+(c.2L==1i?' 4-1o-12':(c.2L==1q?'':' '+c.2L))+'\" 11=\"3W:1g; 14:'+i+'; 3j-Y:'+(a.1U.2e?5j:5k)+'19; 1f-1g:'+(c.2L?5n:2X)+'19;2a:2l;1T-2a:5q;5r-1K:1K-5o;\">'+(c.1o==''?'&5p;':c.1o)+'</Z>');j.X('</Z>')};j.X('<Z 1t=\"4-2T\"></Z></Z>');j.X('</Z>');j.X('</1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;\"></1u>');j.X('</2n>');5(c.W>9){j.X('<2n>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:0 0 0 '+c.W+'19; 14:'+c.W+'19; Y:'+c.W+'19;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;Y:'+c.W+'19;2a: 2l;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:0 0 '+c.W+'19 0; 14:'+c.W+'19; Y:'+c.W+'19;\"></1u>');j.X('</2n>')};j.X('</4p>');j.X('</Z>');j.X('</Z>');6 k='<1F 2h=\"4-1F\" 1t=\"4-1F\" 14=\"2v%\" Y=\"2v%\" 5i=\"0\" 5b=\"0\" 5c=\"0\" 59=\"'+c.4r+'\"></1F>';6 l=a(2f);6 m=a(1H.1z);6 n=a(j.2m('')).5a(m);6 o=n.2r('#4');6 p=n.2r('#4-24');6 q=n.2r('#4-3m');5(!f.2j){3H(f.1x){1R\"4t\":f.1e=a('#'+b).1e();1K;1R\"35\":1R\"30\":f.1e='';f.2g=b;1K;1R\"3h\":f.1e=b;1K;1R\"2w\":f.1e=k;5(b.1X('#')==-1h){f.2g=b+(b.1X('?')==-1h?'?39':'&39')+2U.3i()}1b{6 N=b.5d('#');f.2g=N[9]+(N[9].1X('?')==-1h?'?39':'&39')+2U.3i()+'#'+N[1h]};1K};b={5g:{13:f.1e,1C:c.1C,2o:c.2o,1W:c.1W}}};6 r=[];6 s=o.10('.4-2W-1o').3N(1i);6 t=o.10('.4-2W-1l').3N(1i);6 u=a.1U.2e?'3j-Y:3V;1f:1c 3O 1c 3O;':'1f:1c 2N 1c 2N;';a.2C(b,8(N,O){5(f.2j){O=a.1n({},a.7.2O,O)};b[N]=O;5(O.1C==1y){O.1C={}};6 P=1q;a.2C(O.1C,8(T,U){P=1i});6 Q='1m';5(1L c.Y=='36'){Q=c.Y;5(g){Q=Q-s};5(P){Q=Q-t};Q=(Q-1h)+'19'};6 R='';6 S='2s';5(!f.2j&&h){6 T=c.Y;5(1L c.Y=='36'){5(g){T=T-s};5(P){T=T-t};S=((T/2X)*1N)+'19';T=(T-1h)+'19'};R=['<Z 1t=\"4-13-2B\" 1a=\"4-13-2B\" 11=\"2P-Y:5h;Y:'+T+'; 1T-2k:42;\">','<Z 1a=\"4-13-2B-5e\" 11=\"1D:2R; 1E:1m; 14:5f; Y:3V; 1f-18: '+S+';\"></Z>','</Z>'].2m('')};r.X('<Z 1t=\"4-1G-'+N+'\" 1a=\"4-1G\" 11=\"1D:1d;\">');r.X('<Z 11=\"2P-14:3o;14:'+(1L c.14=='36'?c.14+'19':'1m')+'; Y:'+Q+';\">'+R+'<Z 1t=\"4-13\" 1a=\"4-13\" 11=\"Y:'+Q+';2a:2l;2a-y:1m;\">'+O.13+'</Z></Z>');r.X('<Z 1a=\"4-1l-23\" 11=\"Y:2s;1f:2Z 0 2Z 0;1T-2k: 27;'+(P?'':'1D:1d;')+'\">');5(!c.1p){r.X('<26 1a=\"4-29-1T\" 11=\"3W:1g;1D:2R;3j-Y:2s;\"></26>')};a.2C(O.1C,8(T,U){r.X('<1l 1a=\"4-1l\" 31=\"'+U+'\" 11=\"'+u+'\">'+T+'</1l>')});r.X('</Z></Z>')});o.10('#4-2T').1e(r.2m('')).2r('.4-1G:3k').16('1D','2R');5(h){6 N=o.10('#4-13').16({1s:(d)?\"1B\":\"32\",1g:-4H})};a.2C(b,8(N,O){6 P=o.10('#4-1G-'+N);P.2r('.4-1l-23').2r('1l').2c(8(){6 Q=P.10('#4-13');6 R=O.1C[a(21).1T()];6 S={};a.2C(o.10('#4-2T :4h').4M(),8(U,V){5(S[V.2h]===1y){S[V.2h]=V.31}1b 5(1L S[V.2h]==4L){S[V.2h].X(V.31)}1b{S[V.2h]=[S[V.2h],V.31]}});6 T=O.1W(R,Q,S);5(T===1y||T){I()}}).1P('2t',8(){a(21).2x('4-1l-3x')}).1P('4A',8(){a(21).2I('4-1l-3x')}).1P('4G',8(){a(21).2x('4-1l-2E')}).1P('4O',8(){a(21).2I('4-1l-3x').2I('4-1l-2E')});P.10('.4-1l-23 1l:2V('+O.2o+')').2x('4-1l-1O')});6 v=8(){n.16({18:l.3e()});5(c.1A){o.16({1s:(d)?\"1B\":\"32\",27:1h,29:1h})}};6 w=8(){6 N=l.14();1j 1H.1z.3I<N?N:1H.1z.3I};6 x=8(){6 N=l.Y();1j 1H.1z.3C<N?N:1H.1z.3C};6 y=8(){5(!c.1M){1j};5(c.4q){6 N=9;n.2x('4-25');6 O=4K(8(){n.4J('4-25');5(N++>1h){4I(O);n.2I('4-25')}},4N)}1b{I()}};6 z=8(N){5(c.1p||c.1A){1j 1q};6 O=(2f.4f)?4f.4g:N.4g;5(O==4F){I()};5(O==5Z){6 P=a(':4h:5Y:2p',n);6 Q=!N.4e&&N.1r==P[P.1I-1h];6 R=N.4e&&N.1r==P[9];5(Q||R){38(8(){5(!P)1j;6 S=P[R===1i?P.1I-1h:9];5(S)S.1O()},2G);1j 1q}}};6 A=8(){5(c.1M){p.16({1s:\"1B\",Y:c.1p?x():l.Y(),14:d?l.14():\"2v%\",18:9,1g:9,27:9,29:9})}};6 B=8(){5(c.1A){o.16({1s:(d)?\"1B\":\"32\",27:1h,29:1h})}1b{q.16({18:c.18});o.16({1s:\"1B\",18:q.3f().18+(c.1p?l.3e():9),1g:((l.14()-o.3S())/1N)})};5((c.1M&&!c.1p)||(!c.1M&&!c.1p&&!c.1A)){n.16({1s:(d)?\"1B\":\"32\",Y:c.1M?l.Y():9,14:\"2v%\",18:(d)?l.3e():9,1g:9,27:9,29:9})};A()};6 C=8(){c.1k=a.7.17.1k++;n.16({1k:c.1k});o.16({1k:c.1k+1h})};6 D=8(){c.1k=a.7.17.1k++;n.16({1k:c.1k});o.16({1D:\"1d\",1k:c.1k+1h});5(c.1M){p.16({1D:\"1d\",1k:c.1k,1V:c.1V})}};6 E=8(N){6 O=N.1w;O.1r.10('1F').2K();5(c.22){O.1r.2u().16({1g:O.1r.16('1g'),18:O.1r.16('18'),61:-1N,60:-1N,14:O.1r.14()+1N,Y:O.1r.Y()+1N}).1Z()};1j 1q};6 F=8(N){6 O=N.1w;6 P=O.49+N.4c-O.43;6 Q=O.4y+N.48-O.4a;5(c.4o){6 R=1h;6 S=1H.46.3C-N.1w.1r.Y()-1h;6 T=1h;6 U=1H.46.3I-N.1w.1r.14()-1h;5(Q<R)Q=R+(c.22?1N:9);5(Q>S)Q=S-(c.22?1N:9);5(P<T)P=T+(c.22?1N:9);5(P>U)P=U-(c.22?1N:9)};5(c.22){O.1r.2u().16({1g:P,18:Q})}1b{O.1r.16({1g:P,18:Q})};1j 1q};6 G=8(N){a(1H).2i('.1Y');5(c.22){6 O=N.1w.1r.2u().2K();N.1w.1r.16({1g:O.16('1g'),18:O.16('18')}).10('1F').1Z()}1b{N.1w.1r.10('1F').1Z()};1j 1q};6 H=8(N){6 O=N.1w.1r.1s();6 P={1r:N.1w.1r,43:N.4c,4a:N.48,49:O.1g,4y:O.18};a(1H).1P('2t.1Y',P,E).1P('5V.1Y',P,F).1P('4A.1Y',P,G)};6 I=8(){5(!c.1p&&!c.1A){5(a('.4-1z').1I==1h){a(a.1U.2e?'1e':'1z').5U('11')};J()}1b{5(c.1p){6 1v=a(1H.1z).1w('1v');5(1v&&1v.2F==1i){q.16('18',1v.33.18);6 N=q.3f().18+l.3e();5(N==o.3f().18){J()}1b{o.10('#4-13').1e(1v.33.13.5X(2X)).5W().16({1g:((l.14()-o.3S())/1N)}).41({18:N,1V:0.1},3J,J)}}1b{o.41({18:'-=62',1V:9},3J,J)}}1b{3H(c.2J){1R'3D':o.4b(c.20,J);1K;1R'24':o.3P(c.20,J);1K;1R'1Z':3R:o.2K(c.20,J);1K}}}};6 J=8(){l.2i('3U',A);5(c.1Y&&!c.1p&&!c.1A){o.10('.4-1o-23').2i('2t',H)};5(f.1x!='2w'){o.10('#4-1F').3c({'3y':'3M:3X'})};o.1e('').3F();5(d&&!c.1p){m.2i('3T',v)};5(c.1M){p.3P('37',8(){p.2i('2c',y).2i('2t',C).1e('').3F()})};n.2i('3Y 3K',z).1e('').3F();5(d&&c.1M){a('3Z').16('3L','2p')};5(1L c.2H=='8'){c.2H()}};6 K=8(){5(c.1Q>9){o.1w('3B',2f.38(I,c.1Q));5(c.1A){o.2E(8(){2f.63(o.1w('3B'))},8(){o.1w('3B',2f.38(I,c.1Q))})}}};6 L=8(){5(1L c.2Y=='8'){c.2Y(o.10('.4-1G:2p').10('.4-13'))}};5(!f.2j){3H(f.1x){1R\"35\":1R\"30\":a.64({1x:f.1x,2g:f.2g,1w:c.3g==1y?{}:c.3g,5B:'1e',5A:1q,2y:8(N,O){o.10('#4-13').16({1s:\"3Q\"}).1e(N).1Z().2u().2K();L()},2z:8(){o.10('#4-13-2B').1e('<Z 11=\"1f-18:3o;1f-29:3o;1T-2k:42;\">5z 5C.</Z>')}});1K;1R\"2w\":o.10('#4-1F').3c({'3y':f.2g}).1P(\"5F\",8(N){a(21).5E().16({1s:\"3Q\"}).1Z().2u().2K();o.10('#4-2T .4-1G:3k .4-1l-1O').1O();L()});1K;3R:o.10('#4-13').1Z();1K}};B();D();5(d&&!c.1p){l.3T(v)};5(c.1M){p.2c(y)};l.3U(A);n.1P('3Y 3K',z);o.10('.4-1J').2c(I);5(c.1M){p.4u('37')};6 M='1Z';5(c.2J=='3D'){M='44'}1b 5(c.2J=='24'){M='4u'};5(c.1A){o[M](c.20,K)}1b{6 1v=a(1H.1z).1w('1v');5(1v&&1v.2F==1i){a(1H.1z).1w('1v',{2F:1q,33:{}});o.16('1D','')}1b{5(!f.2j&&h){o[M](c.20)}1b{o[M](c.20,L);}}};5(!c.1p){o.10('.4-29-1T').1e(c.4E)}1b{o.10('.4-4v,.4-13').2x('4-1v-4x')};5(f.1x!='2w'){o.10('#4-2T .4-1G:3k .4-1l-1O').1O()}1b{o.1O()};5(!c.1A){K()};n.1P('2t',C);5(c.1Y&&!c.1p&&!c.1A){o.10('.4-1o-23').1P('2t',{1r:o},H).16('4w','5D')};1j n};a.7.3a=2.3;a.7.17={1t:3A,18:\"15%\",1k:5u,W:2X,1V:0.1,1Q:9,2J:'24',20:'37',2L:1i,34:1i,1Y:1i,4o:1i,22:1q,4q:1i,4s:1i,3g:{},4r:'1m',1o:'7',14:3p,Y:'1m',4E:'',1C:{'3z':'2b'},2o:9,2Y:8(b){},1W:8(b,c,d){1j 1i},2H:8(){}};a.7.2O={13:'',1C:{'3z':'2b'},2o:9,1W:8(b,c,d){1j 1i}};a.7.2Q={13:'',12:'28',18:'40%',14:'1m',Y:'1m',1V:9,1Q:4B,2H:8(){}};a.7.2A={13:'',1o:'7',12:'1d',14:3p,Y:'1m',1Q:4B,2J:'3D',20:5t,W:9,1C:{},2o:9,2Y:8(){},1W:8(b,c,d){1j 1i},2H:8(){}};a.7.1S={1J:'5s',2b:'3z',3n:'5v',3q:'5y',2S:'5x'};a.7.5w=8(b){a.7.17=a.1n({},a.7.17,b.17);a.7.2O=a.1n({},a.7.2O,b.2O);a.7.2Q=a.1n({},a.7.2Q,b.2Q);a.7.2A=a.1n({},a.7.2A,b.2A);a.7.1S=a.1n({},a.7.1S,b.1S)};a.7.2D=8(){1j a('.4-1z').2V(a('.4-1z').1I-1h)};a.7.5P=8(b){6 c=(1L b=='3v')?a('#'+b):a.7.2D();1j c.10('#4-1F').4C(9)};a.7.5O=8(){1j a.7.3b().10('.4-13').1e()};a.7.5N=8(b){1j a.7.3b().10('.4-13').1e(b)};a.7.3b=8(b){5(b==1y){1j a.7.2D().10('.4-1G:2p')}1b{1j a.7.2D().10('#4-1G-'+b)}};a.7.5Q=8(){1j a.7.3b().3c('1t').5T('4-1G-','')};a.7.3w=8(b,c){6 d=a.7.2D();5(d!=1y&&d!=3A){6 e;b=b||1q;d.10('.4-1G').4b('37');5(1L b=='3v'){e=d.10('#4-1G-'+b)}1b{e=b?d.10('.4-1G:2p').2F():d.10('.4-1G:2p').2u()};e.44(3p,8(){2f.38(8(){e.10('.4-1l-1O').1O();5(c!=1y){e.10('.4-13').1e(c)}},5S)})}};a.7.5R=8(b){a.7.3w(1i,b)};a.7.5I=8(b){a.7.3w(1q,b)};a.7.1J=8(b,c){b=b||1q;c=c||'1z';5(1L b=='3v'){a('#'+b).10('.4-1J').2c()}1b{6 d=a('.4-'+c);5(b){5H(6 e=9,l=d.1I;e<l;++e){d.2V(e).10('.4-1J').2c()}}1b{5(d.1I>9){d.2V(d.1I-1h).10('.4-1J').2c()}}}};a.7.5G=8(b,c,d,e,f){6 17={13:b,1o:c,14:d,Y:e};f=a.1n({},17,f);f=a.1n({},a.7.17,f);a.7(f.13,f)};a.7.2d=8(b,c,d,e){6 17={13:b,1o:c,12:d,1C:3s('({ \"'+a.7.1S.2b+'\": \"2b\" })')};e=a.1n({},17,e);e=a.1n({},a.7.17,e);5(e.W<9){e.W=9};5(e.12!='28'&&e.12!='25'&&e.12!='2y'&&e.12!='2z'&&e.12!='3G'){1f='';e.12='1d'};6 f=e.1o==1y?2G:4j;6 g=e.12=='1d'?'Y:1m;':'2P-Y:2M;'+((a.1U.2e&&3u(a.1U.3a)<3r)?'Y:1m !4l;Y:2v%;4n:2M;':'Y:1m;');6 h=[];h.X('1e:');h.X('<Z 11=\"1E:2N;'+g+'1f-1g:'+(e.12=='1d'?9:4m)+'19;1T-2k:1g;\">');h.X('<26 1a=\"4-12 4-12-'+e.12+'\" 11=\"1s:1B; 18:'+(f+e.W)+'19;1g:'+(2G+e.W)+'19; 14:2q; Y:2q;\"></26>');h.X(e.13);h.X('</Z>');e.13=h.2m('');a.7(e.13,e)};a.7.5J=8(b,c,d){a.7.2d(b,c,'1d',d)};a.7.28=8(b,c,d){a.7.2d(b,c,'28',d)};a.7.2y=8(b,c,d){a.7.2d(b,c,'2y',d)};a.7.2z=8(b,c,d){a.7.2d(b,c,'2z',d)};a.7.5M=8(b,c,d,e){6 17={1C:3s('({ \"'+a.7.1S.2b+'\": \"2b\", \"'+a.7.1S.2S+'\": \"2S\" })')};5(d!=1y&&1L d=='8'){17.1W=d}1b{17.1W=8(f,g,h){1j 1i}};e=a.1n({},17,e);a.7.2d(b,c,'3G',e)};a.7.25=8(b,c,d,e){6 17={1C:3s('({ \"'+a.7.1S.3n+'\": \"3n\", \"'+a.7.1S.3q+'\": \"3q\", \"'+a.7.1S.2S+'\": \"2S\" })')};5(d!=1y&&1L d=='8'){17.1W=d}1b{17.1W=8(f,g,h){1j 1i}};e=a.1n({},17,e);a.7.2d(b,c,'25',e)};a.7.1v=8(b,c,d){6 17={13:b,12:c,1V:9,W:9,34:1q,1C:{},1p:1i};5(17.12=='2B'){17.1Q=9;17.1V=0.1};d=a.1n({},17,d);d=a.1n({},a.7.2Q,d);d=a.1n({},a.7.17,d);5(d.1Q<9){d.1Q=9};5(d.W<9){d.W=9};5(d.12!='28'&&d.12!='25'&&d.12!='2y'&&d.12!='2z'&&d.12!='2B'){d.12='28'};6 e=[];e.X('1e:');e.X('<Z 11=\"2P-Y:5L;Y:1m;1E:2N;1f-1g:2M;1f-18:1c;1T-2k:1g;\">');e.X('<26 1a=\"4-12 4-12-'+d.12+'\" 11=\"1s:1B;18:'+(4d+d.W)+'19;1g:'+(4d+d.W)+'19; 14:2q; Y:2q;\"></26>');e.X(d.13);e.X('</Z>');d.13=e.2m('');5(a('.4-1v').1I>9){a(1H.1z).1w('1v',{2F:1i,33:d});a.7.4k()};5(d.3E!=1y){a('#'+d.3E).1O();18.$('#'+d.3E).1O()};a.7(d.13,d)};a.7.4k=8(){a.7.1J(1q,'1v')};a.7.3l=8(b,c,d,e){a.7.4i();6 17={13:b,1o:c,1Q:(d==1y?a.7.2A.1Q:d),1V:9,34:1i,1Y:1q,1A:1i};e=a.1n({},17,e);e=a.1n({},a.7.2A,e);6 f=a.1n({},a.7.17,{});f.1o=3A;e=a.1n({},f,e);5(e.W<9){e.W=9};5(e.12!='28'&&e.12!='25'&&e.12!='2y'&&e.12!='2z'&&e.12!='3G'){1f='';e.12='1d'};6 g=e.1o==1y?2G:4j;6 h=e.12=='1d'?'Y:1m;':'2P-Y:2M;'+((a.1U.2e&&3u(a.1U.3a)<3r)?'Y:1m !4l;Y:2v%;4n:2M;':'Y:1m;');6 i=[];i.X('1e:');i.X('<Z 11=\"1E:2N;'+h+'1f-1g:'+(e.12=='1d'?9:4m)+'19;1T-2k:1g;\">');i.X('<26 1a=\"4-12 4-12-'+e.12+'\" 11=\"1s:1B; 18:'+(g+e.W)+'19;1g:'+(2G+e.W)+'19; 14:2q; Y:2q;\"></26>');i.X(e.13);i.X('</Z>');e.13=i.2m('');a.7(e.13,e)};a.7.4i=8(){a.7.1J(1q,'3l')};2f.7=a.7})(5K);", 62, 377, "||||jbox|if|var|jBox|function|0x0|||||||||||||||||||||||||||||||||||||||||||||||||border|push|height|div|find|style|icon|content|width||css|defaults|top|px|class|else|0px|none|html|padding|left|0x1|true|return|zIndex|button|auto|extend|title|isTip|false|target|position|id|td|tip|data|type|undefined|body|isMessager|absolute|buttons|display|margin|iframe|state|document|length|close|break|typeof|showFade|0x2|focus|bind|timeout|case|languageDefaults|text|browser|opacity|submit|indexOf|draggable|show|showSpeed|this|dragClone|panel|fade|warning|span|right|info|bottom|overflow|ok|click|prompt|msie|window|url|name|unbind|isObject|align|hidden|join|tr|buttonsFocus|visible|32px|children|25px|mousedown|prev|100|IFRAME|addClass|success|error|messagerDefaults|loading|each|getBox|hover|next|0xa|closed|removeClass|showType|hide|showIcon|30px|10px|stateDefaults|min|tipDefaults|block|cancel|states|Math|eq|help|0x5|loaded|5px|POST|value|fixed|options|showClose|GET|number|fast|setTimeout|___t|version|getState|attr|radius|scrollTop|offset|ajaxData|HTML|random|line|first|messager|temp|yes|50px|0x15e|no|0x7|eval|index|parseInt|string|goToState|active|src|ȷ��|null|autoClosing|clientHeight|slide|focusId|remove|question|switch|clientWidth|0x1f4|keypress|visibility|about|outerHeight|6px|fadeOut|static|default|outerWidth|scroll|resize|19px|float|blank|keydown|select||animate|center|startX|slideDown|1984|documentElement|drag|pageY|startLeft|startY|slideUp|pageX|0x4|shiftKey|event|keyCode|input|closeMessager|0x23|closeTip|important|0x28|_height|dragLimit|table|persistent|iframeScrolling|showScrolling|ID|fadeIn|container|cursor|color|startTop|15px|mouseup|0xbb8|get|0x6|bottomText|0x1b|mouseover|0x2710|clearInterval|toggleClass|setInterval|Array|serializeArray|0x64|mouseout|applet|background|90|object|ff3300|cellspacing|valign|fdisplay|cellpadding|0x32|0xf4240|constructor|jBox_|floor|Object|substring|17px|toLowerCase|post|onmouseover|scrolling|appendTo|marginwidth|frameborder|split|image|220px|state0|70px|marginheight|0x19|0x18|onmouseout|pointer|0x12|all|nbsp|ellipsis|word|�ر�|0x258|0x7c0|��|setDefaults|ȡ��|��|Loading|cache|dataType|Error|move|parent|load|open|for|prevState|alert|jQuery|18px|confirm|setContent|getContent|getIframe|getStateName|nextState|0x14|replace|removeAttr|mousemove|end|substr|enabled|0x9|marginTop|marginLeft|200|clearTimeout|ajax".split("|"), 0, {}));
var jBoxConfig = {};
var popBox = function() {
	var d = $(window);
	var b = $(".jbox");
	var a = d.height();
	var c = d.scrollLeft();
	var f = d.scrollTop();
	var e = b.height();
	var g = f + (a - e) / 2;
	return g;
};
jBoxConfig.defaults = {
	id: null,
	top: popBox(),
	border: 5,
	opacity: 0.1,
	timeout: 0,
	showType: "fade",
	showSpeed: "fast",
	showIcon: true,
	showClose: true,
	draggable: true,
	dragLimit: true,
	dragClone: false,
	persistent: true,
	showScrolling: true,
	ajaxData: {},
	iframeScrolling: "auto",
	title: "jBox",
	width: 350,
	height: "auto",
	bottomText: "",
	buttons: {
		"ȷ��": "ok"
	},
	buttonsFocus: 0,
	loaded: function(a) {},
	submit: function(a, b, c) {
		return true;
	},
	closed: function() {}
};
jBoxConfig.stateDefaults = {
	content: "",
	buttons: {
		"ȷ��": "ok"
	},
	buttonsFocus: 0,
	submit: function(a, b, c) {
		return true;
	}
};
jBoxConfig.tipDefaults = {
	content: "",
	icon: "info",
	top: popBox(),
	width: "auto",
	height: "auto",
	opacity: 0,
	timeout: 1500,
	closed: function() {}
};
jBoxConfig.messagerDefaults = {
	content: "",
	title: "jBox",
	icon: "none",
	width: 350,
	height: "auto",
	timeout: 3000,
	showType: "slide",
	showSpeed: 600,
	border: 0,
	buttons: {},
	buttonsFocus: 0,
	loaded: function(a) {},
	submit: function(a, b, c) {
		return true;
	},
	closed: function() {}
};
jBoxConfig.languageDefaults = {
	close: "�ر�",
	ok: "ȷ��",
	yes: "��",
	no: "��",
	cancel: "ȡ��"
};
$.jBox.setDefaults(jBoxConfig);
var GetQueryString = function(a) {
	var b = new RegExp("(^|&)" + a + "=([^&]*)(&|$)");
	var c = window.location.search.substr(1).match(b);
	if (c != null) {
		return unescape(c[2]);
	}
	return null;
};
jQuery(function() {
	var id = GetQueryString("im");
	if (id != null) {
		var pos = $("#" + id).offset().top;
		$("html,body").animate({
			scrollTop: pos
		},
		1);
	}
	$(".add_guide_close").click(function() {
		var newHandler = $("#newHandler").val();
		$(".guide_bg,.guide_cot").hide();
		var userid = $("#userId").val();
		var random = new Date().getTime();
		var params = {
			staus: "00010",
			newHandler: newHandler,
			userid: userid,
			random: random
		};
		$.getJSON("web/updateHandlerStatus.htm", params,
		function(info) {});
		$(".add_guide_bg,.add_guide_img").hide();
	});
	var lent = $(".shortcut-nav").find("li").length;
	if (lent > 8) {
		$(".shorcut_prev,.shorcut_next").css("display", "block");
	} else {
		$(".shorcut_prev,.shorcut_next").css("display", "none");
	}
	$(".J-mode-pic").hover(function() {
		$(this).removeClass("travels_mode_pic");
		$(this).addClass("travels_mode_pic_hover");
	},
	function() {
		$(this).removeClass("travels_mode_pic_hover");
		$(this).addClass("travels_mode_pic");
	});
	$(".album_total").hover(function() {
		$(this).removeClass("mood_approve");
		$(this).addClass("mood_approve_hover");
	},
	function() {
		var isPraised = $("#albumIsPraised").val();
		if (isPraised != 1) {
			$(this).removeClass("mood_approve_hover");
			$(this).addClass("mood_approve");
		}
	});
	$(".J-approve").hover(function() {
		$(this).removeClass("travels_approve");
		$(this).addClass("travels_approve_hover");
	},
	function() {
		var isPraised = $(this).parent().parent().parent().parent().find("input[name='currIsPraised']").val();
		if (isPraised != 1) {
			$(this).removeClass("travels_approve_hover");
			$(this).addClass("travels_approve");
		}
	});
	$(".J-comment").hover(function() {
		$(this).removeClass("travels_comment");
		$(this).addClass("travels_comment_hover");
	},
	function() {
		$(this).removeClass("travels_comment_hover");
		$(this).addClass("travels_comment");
	});
	$(".J-edit,.J-mood-edit,.J-pic-edit").hover(function() {
		$(this).removeClass("edit");
		$(this).addClass("edit_hover");
	},
	function() {
		$(this).removeClass("edit_hover");
		$(this).addClass("edit");
	});
	$(".travels_title_delete,.travels_mood_add_delete,.travels_right_cont_delete").hover(function() {
		$(this).removeClass("delete");
		$(this).addClass("delete_hover");
	},
	function() {
		$(this).removeClass("delete_hover");
		$(this).addClass("delete");
	});
	$(".travels_title_upload").hover(function() {
		$(this).removeClass("upload");
		$(this).addClass("upload_hover");
	},
	function() {
		$(this).removeClass("upload_hover");
		$(this).addClass("upload");
	});
	$(".J-opText").hover(function() {
		$(this).removeClass("travels_op_text");
		$(this).addClass("travels_op_text_hover");
	},
	function() {
		$(this).removeClass("travels_op_text_hover");
		$(this).addClass("travels_op_text");
	});
	$(".J-opPic").hover(function() {
		$(this).removeClass("travels_op_pic");
		$(this).addClass("travels_op_pic_hover");
	},
	function() {
		$(this).removeClass("travels_op_pic_hover");
		$(this).addClass("travels_op_pic");
	});
	if ($("#userId").val() == $("#visitorId").val() && $("#currentDayInfo").val() != "0") {
		$(".J-hidden").hover(function() {
			$(this).find(".travels_op_add").show();
		},
		function() {
			$(this).find(".travels_op_add").hide();
		});
		$(".travels_mood_add").mouseover(function() {
			$(this).prev(".J-hidden").children(".travels_op_add").show();
			$(this).next(".J-hidden").children(".travels_op_add").show();
			$(this).find(".travels_mood_add_edit,.travels_mood_add_delete").css("display", "inline-block");
			return false;
		});
		$(".travels_mood_add").mousemove(function() {
			$(this).prev(".J-hidden").children(".travels_op_add").show();
			$(this).next(".J-hidden").children(".travels_op_add").show();
			$(this).find(".travels_mood_add_edit,.travels_mood_add_delete").css("display", "inline-block");
		});
		$(".travels_mood_add").mouseout(function() {
			$(this).prev(".J-hidden").children(".travels_op_add").hide();
			$(this).next(".J-hidden").children(".travels_op_add").hide();
			$(this).find(".travels_mood_add_edit,.travels_mood_add_delete").css("display", "none");
			return false;
		});
	}
	if ($("#userId").val() == $("#visitorId").val() && $("#currentDayInfo").val() != "0") {
		$(".travels_right_cont").mouseover(function() {
			$(this).prev(".J-hidden").children(".travels_op_add").show();
			$(this).next(".J-hidden").children(".travels_op_add").show();
			$(this).find(".travels_right_cont_delete,.travels_describe_edit").css("display", "inline-block");
			return false;
		});
		$(".travels_right_cont").mousemove(function() {
			$(this).prev(".J-hidden").children(".travels_op_add").show();
			$(this).next(".J-hidden").children(".travels_op_add").show();
			$(this).find(".travels_right_cont_delete,.travels_describe_edit").css("display", "inline-block");
		});
		$(".travels_right_cont").mouseout(function() {
			$(this).prev(".J-hidden").children(".travels_op_add").hide();
			$(this).next(".J-hidden").children(".travels_op_add").hide();
			$(this).find(".travels_right_cont_delete,.travels_describe_edit").css("display", "none");
			return false;
		});
	}
	getDay();
	scroll_personal();
	function scroll_personal() {
		var j = $(window).scrollTop();
		var b = $(".travels_main").offset().top;
		var i = $(".travels_left");
		var f = window.screen.height;
		var viewHeight = $(window).height();
		var travelsH = $(".travels_left").height();
		var travelsB = $(".travels_left").offset().top;
		function isIE6() {
			if ($.browser.msie) {
				if ($.browser.version == "6.0") {
					return true;
				}
			}
		}
		if (isIE6()) {
			var top = viewHeight - 480 + j - b;
			if (j >= b) {
				if (f == 768) {
					$(".travels_left").css("top", top - 140 + "px");
				} else {
					if (f == 800) {
						$(".travels_left").css("top", top - 170 + "px");
					} else {
						if (f == 864) {
							$(".travels_left").css("top", top - 220 + "px");
						} else {
							if (f == 900) {
								$(".travels_left").css("top", top - 270 + "px");
							} else {
								if (f == 960) {
									$(".travels_left").css("top", top - 330 + "px");
								} else {
									if (f == 1024) {
										$(".travels_left").css("top", top - 400 + "px");
									} else {
										if (f == 1050) {
											$(".travels_left").css("top", top - 420 + "px");
										} else {
											if (f == 1080) {
												$(".travels_left").css("top", top - 450 + "px");
											} else {
												$(".travels_left").css("top", 0 + "px");
											}
										}
									}
								}
							}
						}
					}
				}
			} else {
				$(".travels_left").css("top", "0px");
			}
		} else {
			if (j >= b) {
				i.addClass("sidebar_fixed");
				i.css("top", "0");
			} else {
				i.removeClass("sidebar_fixed");
				i.css("top", b - j);
			}
		}
	}
	$(window).scroll(function() {
		scroll_personal();
	});
	var page = getDays();
	var i = 8;
	$(".shorcut_next").click(function() {
		var $parent = $(this).siblings(".shorcut-nav-main");
		var $special_show = $parent.find(".shortcut-nav");
		var special_height = $parent.height();
		var len = $special_show.find("li").length;
		var page_count = Math.ceil(len / i);
		$special_show.height(special_height * page_count);
		if (!$special_show.is(":animated")) {
			if (page == page_count) {
				$special_show.animate({
					top: "0px"
				},
				"slow");
				page = 1;
			} else {
				$special_show.animate({
					top: "-=" + special_height
				}),
				"slow";
				page++;
			}
		}
	});
	$(".moodcancel").click(function() {
		$(this).parent().hide();
		$(this).parent().parent().find(".J-address").css("display", "block");
		var mood_cancel = $(this).parent().siblings().children(".J-mood-content");
		mood_cancel.empty();
		mood_cancel.html("<span>" + mood_content + "</span>");
		mood_cancel.append("<span class='J-mood-edit travels_mood_add_edit edit'></span>");
		moodEdit();
	});
	var mood_content;
	var mood_edit_content;
	moodEdit();
	function moodEdit() {
		$(".J-mood-edit").unbind("click");
		$(".J-mood-edit").click(function() {
			$(this).parent().parent().siblings(".travels_mood_buttom").show();
			$(this).parent().parent().find(".J-address").hide();
			var J_mood_content = $(this).parent();
			var contentHtml = J_mood_content.find("span:first").html();
			mood_content = contentHtml;
			mood_edit_content = J_mood_content.children("span");
			J_mood_content.empty();
			$("<textarea/>").appendTo(J_mood_content).text(contentHtml).addClass("mood_content");
			$(".mood_content").autoTextarea({
				maxHeight: 400,
				minHeight: 68
			});
			J_mood_content.append("<div id='promote1' class='textarea_error_mood darkgray666 fn-pa fn-tl fn-none'></div>");
			$(".mood_content").bind("keyup paste keydown",
			function() {
				$(this).siblings("#promote1").css("display", "block");
				var curLength = $.trim($(this).val()).length;
				if (curLength > 1000) {
					var num = $.trim($(this).val()).length - 1000;
					$(this).siblings("#promote1").html("�Ѿ�����<span style='color:#de2c28;font-size:12px;'>" + num + "</span>����");
				} else {
					if (curLength == 0) {
						if ($.trim($(this).val()) == "") {
							$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>1000</span>����");
						} else {
							$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>999</span>����");
						}
					} else {
						var num = 1000 - $.trim($(this).val()).length;
						$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>" + num + "</span>����");
					}
				}
			});
		});
	}
	$(".moodSave").click(function() {
		$(this).parent().parent().find(".J-address").css("display", "block");
		var mood_complete = $(this).parent().parent().find(".J-mood-content");
		var mood_text = mood_complete.children("textarea").val();
		$(this).parent().hide();
		var picId = $(this).parent().siblings("input[name=picId]").val();
		if ($.trim(mood_text).length == 0) {
			$.jBox.tip("��ʲô��ûд��", "warning");
			$(this).parent().show();
			$(this).parent().parent().find(".J-address").hide();
			return false;
		}
		if (mood_text.length > 1000) {
			$.jBox.tip("�����������", "success");
			$(this).parent().show();
			$(this).parent().parent().find(".J-address").hide();
			return false;
		}
		var random = new Date().getTime();
		var data = {
			picTextId: picId,
			mood_text: mood_text,
			random: random
		};
		$.getJSON("web/updateFeel.htm", data,
		function(info) {
			if (info != null && info.result != null) {
				if (info.result) {
					$.jBox.tip("���ֱ༭�ɹ���", "success");
					mood_complete.empty();
					mood_complete.html("<span>" + escapeHTML(mood_text) + "</span>");
					mood_complete.append("<span class='J-mood-edit travels_mood_add_edit edit'></span>");
					$(this).parent().parent().hide();
					$(this).parent().parent().find(".J-address").css("display", "block");
					moodEdit();
				} else {
					$.jBox.tip("���ֱ༭ʧ�ܡ�", "success");
				}
			} else {
				window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
			}
		});
	});
	picEdit();
	var pic_content;
	var pic_edit_content;
	var pic_place;
	function picEdit() {
		$(".J-pic-edit").unbind("click");
		$(".J-pic-edit").click(function() {
			var picWholeContent = $(this).parent().parent().parent();
			$(this).parent().parent().siblings(".travels_describe_buttom").show();
			$(this).parent().parent().find(".J-address").hide();
			var J_pic_content = $(this).parent();
			var textMn = $(this).parent().text();
			if (textMn == "��ɶ��û��д��д�����") {
				textMn = "";
			}
			pic_content = textMn;
			pic_edit_content = J_pic_content.children("span");
			J_pic_content.empty();
			$("<textarea/>").appendTo(J_pic_content).text(textMn).addClass("pic_content");
			$(".pic_content").autoTextarea({
				maxHeight: 220,
				minHeight: 53
			});
			J_pic_content.append("<div id='promote1' class='textarea_error_pic darkgray666 fn-pa fn-tl fn-none'></div>");
			var placeDe = picWholeContent.find(".J-places");
			var textMe = placeDe.text();
			pic_place = textMe;
			placeDe.empty();
			$("<input id='psAddressIdAlbumPic' name='text2' type='text'>").appendTo(placeDe).val(textMe).addClass("pest");
			var paId = picWholeContent.find("input[name=addressId]").val();
			var paName = picWholeContent.find("input[name=addressName]").val();
			if (paId != null && paName != null && paName != "") {
				picWholeContent.find("#psAddressIdAlbumPic").tokenInput("web/searchPOI.htm", {
					theme: "albumpicdetail",
					tokenLimit: 1,
					prePopulate: [{
						id: paId,
						name: paName
					}]
				});
			} else {
				picWholeContent.find("#psAddressIdAlbumPic").tokenInput("web/searchPOI.htm", {
					theme: "albumpicdetail",
					tokenLimit: 1
				});
			}
			picWholeContent.find("#faddressName").show();
			$(".pic_content").bind("keyup paste keydown",
			function() {
				$(this).siblings("#promote1").css("display", "block");
				var curLength = $.trim($(this).val()).length;
				if (curLength > 140) {
					var num = $.trim($(this).val()).length - 140;
					$(this).siblings("#promote1").html("�Ѿ�����<span style='color:#de2c28;font-size:12px;'>" + num + "</span>����");
				} else {
					if (curLength == 0) {
						if ($.trim($(this).val()) == "") {
							$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>140</span>����");
						} else {
							$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>139</span>����");
						}
					} else {
						var num = 140 - $.trim($(this).val()).length;
						$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>" + num + "</span>����");
					}
				}
			});
		});
	}
	$(".piccancel").click(function() {
		$(this).parent().hide();
		$(this).parent().siblings(".travels_describe").find(".J-address").show();
		var pic_cancel = $(this).parent().siblings(".travels_describe").children(".J-pic-content ");
		var pic_detail = $(this).parent().siblings(".travels_describe").find(".J-place");
		pic_cancel.empty();
		if (pic_content == "") {
			pic_content = "��ɶ��û��д��д�����";
		}
		pic_cancel.text(pic_content);
		pic_cancel.append("<span class='J-pic-edit travels_describe_edit edit'></span>");
		pic_detail.empty();
		pic_detail.text(pic_place);
		var addressName = $(this).parent().siblings("input[name=addressName]").val();
		if (addressName != null && addressName != "") {
			$(this).parent().siblings(".travels_describe").find(".J-places").empty();
			$(this).parent().siblings(".travels_describe").find(".J-places").text(addressName);
		} else {
			$(this).parent().parent().find("#faddressName").hide();
		}
		$(this).parent().hide();
		picEdit();
	});
	$(".picSave").click(function() {
		var pic_complete = $(this).parent().siblings(".travels_describe").children(".J-pic-content ");
		var desc = pic_complete.children("textarea").val();
		var wholeContent = $(this).parent().parent();
		var pic_places = $(this).parent().siblings(".travels_describe").find(".J-places");
		if (desc.length > 140) {
			$.jBox.tip("������������", "warning");
			return false;
		}
		$(this).parent().hide();
		$(this).parent().siblings(".travels_describe").find(".J-address").show();
		var picId = $(this).parent().siblings("input[name=picId]").val();
		var addressIdInit = wholeContent.find("#psAddressIdAlbumPic").tokenInput("get");
		var isaddress = "";
		var isAlbumId = "";
		if (addressIdInit != "") {
			isaddress = addressIdInit[0].id;
			isaddressName = addressIdInit[0].name;
		}
		var random = new Date().getTime();
		var data = {
			picId: picId,
			desc: desc,
			addressId: isaddress,
			random: random
		};
		$.getJSON("web/editWebPic.htm", data,
		function(info) {
			if (info != null && info.editstatus != null) {
				if (info.editstatus == 0) {
					$.jBox.tip("ͼƬ�༭�ɹ���", "success");
					pic_complete.empty();
					pic_complete.text(desc);
					pic_complete.append("<span class='J-pic-edit travels_describe_edit edit'></span>");
					$(this).parent().hide();
					if (isaddress != "" && isaddressName != "") {
						pic_places.empty();
						pic_places.append("<a href='web/sight.htm?sightId=" + isaddress + "' title='" + isaddressName + "'>" + isaddressName + "</a>");
						wholeContent.find("input[name=addressId]").val(isaddress);
						wholeContent.find("input[name=addressName]").val(isaddressName);
					} else {
						wholeContent.find("input[name=addressId]").val("");
						wholeContent.find("input[name=addressName]").val("");
						wholeContent.find("#faddressName").hide();
					}
					picEdit();
				} else {
					if (info.editstatus == 8000) {
						window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
					} else {
						$.jBox.tip("ͼƬ�༭ʧ�ܡ�", "success");
					}
				}
			} else {
				$.jBox.tip("ͼƬ�༭ʧ�ܡ�", "success");
			}
		});
	});
	$(".shorcut_prev").click(function() {
		var $parent = $(this).siblings(".shorcut-nav-main");
		var $special_show = $parent.find(".shortcut-nav");
		var special_height = $parent.height();
		var len = $special_show.find("li").length;
		var page_count = Math.ceil(len / i);
		$special_show.height(special_height * page_count);
		if (!$special_show.is(":animated")) {
			if (page == 1) {
				$special_show.animate({
					top: "-=" + special_height * (page_count - 1)
				},
				"slow");
				page = page_count;
			} else {
				$special_show.animate({
					top: "+=" + special_height
				}),
				"slow";
				page--;
			}
		}
	});
	$(function() {
		var oldName = $.trim($(".J-album-name").text());
		$("#albumSave").click(function() {
			var curLength = $.trim($("#fdesc textarea").val()).length;
			if (curLength > "140") {
				return false;
			}
			var curNameLength = $.trim($(".nest").val()).length;
			if (oldName != "�������μ�") {
				if (curNameLength > "40") {
					return false;
				} else {
					if (curNameLength == "0") {
						$("#lsAlbumNameHas").show();
						$(".nest").focus(function() {
							$("#lsAlbumNameHas").hide();
						});
						return false;
					}
				}
			}
			var addressIdInit = $("#psAddressIdAlbum").tokenInput("get");
			if (addressIdInit == null || addressIdInit == "") {
				$("#albumDescError").show();
				$("#token-input-psAddressIdAlbum").focus(function() {
					$("#albumDescError").hide();
				});
				return false;
			}
			var albumIdNew = $("#albumId").val();
			var paName = $.trim($(".nest").val());
			var random = new Date().getTime();
			var params = {
				name: paName,
				albumId: albumIdNew,
				random: random
			};
			var dubble = false;
			if (oldName != paName) {
				$.ajax({
					type: "POST",
					async: false,
					url: "web/checkalbum.htm",
					data: params,
					dataType: "json",
					success: function(data) {
						if (data.isReName == false) {
							dubble = true;
						}
					}
				});
			}
			if (dubble == true) {
				$("#lsAlbumHas").show();
				return false;
			}
			var desc = $("#fdesc textarea").val();
			var destination = $(".J-destination").val();
			var destination = getAddressStr(addressIdInit);
			var desListUl = $("#albumOrPicDesListUl");
			var paName = $("#albumPicAddressName").val();
			var nameAlbum = $.trim($(".nest").val());
			if (oldName == "�������μ�") {
				nameAlbum = oldName;
			}
			var id = $("#albumId").val();
			if (destination != null) {
				var random = new Date().getTime();
				var data = {
					desc: desc,
					id: id,
					destination: destination,
					paName: nameAlbum,
					random: random
				};
				$.getJSON("web/saveMinAlbum.htm", data,
				function(info) {
					var map = $("#map").val();
					if (info.isInner == false && map == "true") {
						$(".travels_mode_map").css("display", "block");
					} else {
						$(".travels_mode_map").css("display", "none");
					}
					if (info != null && info.albumId != null) {
						oldName = nameAlbum;
						var addressText = "";
						desListUl.empty();
						for (var k = 0; k < addressIdInit.length; k++) {
							desListUl.append("<li>");
							desListUl.append("<span id='albumOrPicDesId'>" + addressIdInit[k].id + "</span>");
							desListUl.append("<span id='albumOrPicDesName'>" + addressIdInit[k].name + "</span>");
							desListUl.append("</li>");
							if (k == addressIdInit.length - 1) {
								addressText = addressText + addressIdInit[k].name;
							} else {
								addressText = addressText + addressIdInit[k].name + "��";
							}
						}
						$.jBox.tip("�μǱ༭�ɹ���", "success");
						var textMn = $(".nest").val();
						var textMs = $(".test").val();
						var textMe = $(".pest").val();
						var textDest = $(".dest").val();
						var pictype = $(".service_down").text();
						$(".J-destination").empty();
						$(".J-destination").text(addressText);
						$(".J-album-name").empty();
						if (oldName == "�������μ�") {
							$(".J-album-name").text(oldName);
						} else {
							$(".J-album-name").text(textMn);
							$(".J-album-name").append("<span class='J-edit travels_title_edit edit'></span>");
							editTitle();
						}
						$(".J-textarea").empty();
						$(".J-textarea").text(textMs);
						$(".J-place").empty();
						$(".J-place").text(textMe);
						$(".J-selected ul a").text(pictype);
						$(".J-selected").show();
						$(".J-select").hide();
						$(".J-input").hide();
						$(".mood_content_address").show();
						$("#lsAlbum").hide();
					} else {}
				});
			}
		});
		var placeT = $("#albumPicAddressName").val();
		var jAlbumOldName = $.trim($(".J-album-name").text());
		var jAlbumOldPicName = $.trim($(".J-picAlbum-name").text());
		var placeDest = $(".J-destination").text();
		var albumAhref = $(".J-picAlbum-name a");
		var textOldMs = $(".J-textarea").text();
		$(".lvtu_pic_image_next,.lvtu_pic_image_prve").click(function() {
			var addressIdSt = $("#albumPicAddressId").val();
			if (addressIdSt != null && addressIdSt != "") {
				$(".J-place").empty();
				$(".J-place").text(placeT);
			} else {
				$("#faddressName").hide();
			}
			$(".picAlbum_wp").css("width", "360px");
			$(".J-picAlbum-name").css("width", "360px").removeClass("fn-omit");
			$(".J-picAlbum-list").hide();
			$(".J-picAlbum-describe").hide();
			$(".picAlbum_fips").hide();
			$(".J-picAlbum-name").empty();
			$(".J-picAlbum-name").append(albumAhref);
			$(".picAlbum_wp").removeClass("picAlbum_border");
			$(".J-album-name").empty();
			$(".J-album-name").text(jAlbumOldName);
			var textMs = $(".J-textarea").text();
			$(".J-textarea").text(textMs);
			$(".J-destination").empty();
			$(".J-destination").text(placeDest);
			$(".J-selected").show();
			$(".J-select").hide();
			$(".textarea_error_album").hide();
			$(".textarea_error_maddressName").hide();
			$(".J-textarea-error").hide();
			$(".J-input").hide();
		});
		editTitle();
		function editTitle() {
			$(".J-edit").click(function() {
				$(".mood_content_address").hide();
				$(".J-input").show();
				var textMn = $(".J-album-name").text();
				var textPn = $(".J-picAlbum-name").text();
				var textareaH = $(".J-textarea").height();
				var textMs = $(".J-textarea").text();
				var textMe = $(".J-place").text();
				var textDest = $(".J-destination").text();
				$(".J-textarea").empty();
				$(".J-place").empty();
				$(".J-destination").empty();
				if (textMn != "�������μ�") {
					$(".J-album-name").empty();
					$("<input name='text' type='text'>").appendTo(".J-album-name").val(textMn).addClass("nest");
				}
				$(".J-picAlbum-name").empty().addClass("fn-omit");
				$(".picAlbum_wp").addClass("picAlbum_border");
				$(".J-picAlbum-describe").show();
				$(".picAlbum_fips").show();
				$("<span></span>").appendTo(".J-picAlbum-name").text(textPn).addClass("picst darkgray");
				$("<textarea/>").appendTo(".J-textarea").text(textMs).addClass("test");
				$("<input id='psAddressIdPic' name='text' type='text'>").appendTo(".J-place").val(textMe).addClass("pest");
				$("<input id='psAddressIdAlbum' name='text' type='text'>").appendTo(".J-destination").val(textDest).addClass("dest");
				var albumNameL = "40";
				$(".J-album-name input").bind("keyup paste keydown",
				function() {
					var curLength = $.trim($(this).val()).length;
					if (curLength > albumNameL) {
						$(".textarea_error_album").show();
						var num = $.trim($(this).val()).length - albumNameL;
						$("#lsAlbumNameHas").html("��<em class='orange'>" + eval(parseInt(albumNameL) + parseInt(num)) + "</em>��/" + albumNameL + "�֣����ѳ��� <em class='orange'>" + num + "</em> �֣�");
					} else {
						if (curLength == 0) {
							if ($.trim($(this).val()) == "") {} else {
								$(".textarea_error_album").html("����������<span>" + albumNameL - 1 + "</span>����");
							}
						} else {
							$(".textarea_error_album").hide();
						}
					}
				});
				var worldlen = "140";
				$(".J-textarea textarea").bind("keyup paste keydown",
				function() {
					var curLength = $.trim($(this).val()).length;
					if (curLength > worldlen) {
						$(".J-textarea-error").show();
						var num = $.trim($(this).val()).length - worldlen;
						$(".J-textarea-error").show();
						$(".J-textarea-error").html("��<em class='orange'>" + eval(parseInt(worldlen) + parseInt(num)) + "</em>��/" + worldlen + "�֣����ѳ��� <em class='orange'>" + num + "</em> �֣�");
					} else {
						$(".J-textarea-error").hide();
						if (curLength == 0) {
							if ($.trim($(this).val()) == "") {
								$(".J-textarea-error").html("����������<span>" + worldlen + "</span>����");
							} else {
								$(".J-textarea-error").html("����������<span>" + worldlen - 1 + "</span>����");
							}
						} else {
							$(".J-textarea-error").hide();
							var num = worldlen - $.trim($(this).val()).length;
							$(".J-textarea-error").html("����������<span>" + num + "</span>����");
						}
					}
				});
				var paId = $("#albumPicAddressId").val();
				var paName = $("#albumPicAddressName").val();
				var sAlbumOrPicDesId = $("#albumOrPicDesList").find("#albumOrPicDesId");
				var sAlbumOrPicDesName = $("#albumOrPicDesList").find("#albumOrPicDesName");
				var initData = new Array();
				for (var i = 0; i < sAlbumOrPicDesId.length; i++) {
					initData[i] = {
						id: sAlbumOrPicDesId.eq(i).text(),
						name: sAlbumOrPicDesName.eq(i).text()
					};
				}
				if (paId != null && paName != null && paName != "") {
					$("#psAddressIdPic").tokenInput("web/searchPOI.htm", {
						theme: "picdetail",
						tokenLimit: 1,
						prePopulate: [{
							id: paId,
							name: paName
						}]
					});
				} else {
					$("#psAddressIdPic").tokenInput("web/searchPOI.htm", {
						theme: "picdetail",
						tokenLimit: 1
					});
				}
				$("#psAddressIdAlbum").tokenInput("web/searchDestin.htm", {
					theme: "albumdetail",
					prePopulate: initData
				});
				$(".J-textarea textarea").height(textareaH);
				$(".J-select").show();
				var nameCa = $("#currPicCategoryId").val();
				$(".J-select ul li a[name=" + nameCa + "]").removeClass("service_link").addClass("service_down").parent().siblings().children().removeClass("service_down").addClass("service_link");
				$(".J-selected").hide();
				$(".J-input").show();
				$("#faddressName").show();
				$("#maddressName").removeClass("fn-hidden");
				$("#faddressName").show();
			});
		}
		$(".J-cancel").click(function() {
			$(".mood_content_address").show();
			var addressIdSt = $("#albumPicAddressId").val();
			if (addressIdSt != null && addressIdSt != "") {
				$(".J-place").empty();
				$(".J-place").text(placeT);
			} else {
				$("#faddressName").hide();
			}
			$(".J-album-name").empty();
			$(".J-album-name").text(oldName);
			$(".J-textarea").text(textOldMs);
			$(".J-album-name").append("<span class='J-edit travels_title_edit edit'></span>");
			$(".J-destination").empty();
			$(".J-destination").text(placeDest);
			$(".J-selected").show();
			$(".J-select").hide();
			$(".textarea_error_album").hide();
			$(".textarea_error_maddressName").hide();
			$(".J-textarea-error").hide();
			$(".J-input").hide();
			editTitle();
		});
		$("#fdelAlbum").click(function() {
			var id = $("#albumId").val();
			var data = {
				id: id,
				extcs: getExtcsValue()
			};
			var submit = function(v, h, f) {
				if (v == true) {
					$.getJSON("web/deleteAlbum.htm", data,
					function(info) {
						if (info.delAlbumFlag == 0) {
							window.location.href = pathUpload + "web/userInfo.htm?userId=" + info.userLoginId;
						} else {
							if (info.delAlbumFlag == 8000) {
								window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
							} else {
								$.jBox.tip("ɾ��ר��ʧ�ܡ�", "success");
							}
						}
					});
				}
			};
			$.jBox.confirm("ȷ��Ҫɾ����μ���", "��ͼ", submit, {
				buttons: {
					"ȷ��": true,
					"ȡ��": false
				}
			});
		});
		$(".lvtu_pic_shop li").hover(function() {
			$(this).find(".describe").show();
		},
		function() {
			$(this).find(".describe").hide();
		});
	});
	function popBox() {
		var objW = $(window);
		var objC = $(".pop_status");
		var brsW = objW.width();
		var brsH = objW.height();
		var sclL = objW.scrollLeft();
		var sclT = objW.scrollTop();
		var curW = objC.width();
		var curH = objC.height();
		var left = sclL + (brsW - curW) / 2;
		var top = sclT + (brsH - curH) / 2;
		objC.css({
			left: left,
			top: top
		});
	}
	$(window).resize(function() {
		popBox();
	});
	$(".close").click(function() {
		$(".pop_status").hide();
		$(".bg_mask").hide();
		$("textlimit").val("");
		$("#prompt").html("����������<span style='color:#0069ca;font-size:12px;'>140</span>����");
	});
	$("#Button3").click(function() {
		$(".pop_status").hide();
		$(".bg_mask").hide();
	});
	$(".travels_prveDay").click(function() {
		var albumId = $("#albumId").val();
		var dayNum = $("#dayId").val();
		dayNum = eval(Number(dayNum) - 2);
		var random = new Date().getTime();
		var data = {
			albumId: albumId,
			dayNum: dayNum,
			random: random
		};
		window.location.href = pathUpload + "web/album.htm?albumId=" + albumId + "&dayNum=" + dayNum;
	});
	$(".travels_nextDay").click(function() {
		var albumId = $("#albumId").val();
		var dayNum = $("#dayId").val();
		var random = new Date().getTime();
		var data = {
			albumId: albumId,
			dayNum: dayNum,
			random: random
		};
		window.location.href = pathUpload + "web/album.htm?albumId=" + albumId + "&dayNum=" + dayNum;
	});
	$(".travels_again").click(function() {
		var albumId = $("#albumId").val();
		var dayNum = $("#dayId").val();
		dayNum = 0;
		var random = new Date().getTime();
		var data = {
			albumId: albumId,
			dayNum: dayNum,
			random: random
		};
		window.location.href = pathUpload + "web/album.htm?albumId=" + albumId + "&dayNum=" + dayNum;
	});
	$(".travels_title_delete").click(function() {
		var id = $("#albumId").val();
		var data = {
			id: id,
			extcs: getExtcsValue()
		};
		var submit = function(v, h, f) {
			if (v == true) {
				$.getJSON("web/deleteAlbum.htm", data,
				function(info) {
					if (info.delAlbumFlag == 0) {
						window.location.href = pathUpload + "web/userInfo.htm?userId=" + info.userLoginId;
					} else {
						if (info.delAlbumFlag == 8000) {
							window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
						} else {
							$.jBox.tip("ɾ��ר��ʧ�ܡ�", "success");
						}
					}
				});
			}
		};
		$.jBox.confirm("ȷ��Ҫɾ����μ���", "��ͼ", submit, {
			buttons: {
				"ȷ��": true,
				"ȡ��": false
			}
		});
	});
	$(".travels_title_upload").click(function() {
		var id = $("#albumIdInfo").val();
		if (id != null && id != "") {
			window.location.href = pathUpload + "web/addPicAlbum.htm?id=" + id;
		} else {
			$.jBox.tip("�����μ�ͼƬ�쳣��������ˢ��ҳ�档", "success");
		}
	});
	$(".travels_pic_add_delete ,.travels_mood_add_delete, .travels_right_cont_delete").click(function() {
		var picId = $(this).parent().find("input[name='picId']").val();
		var id = $("#albumId").val();
		var url = window.location.href;
		var strs = new Array();
		var after;
		strs = url.split("&im=");
		if (strs != null) {
			after = strs[0];
		}
		var submit = function(v, h, f) {
			if (v == "ok") {
				var params = {
					picId: picId,
					extcs: getExtcsValue()
				};
				$.getJSON("web/deleteWebPic.htm", params,
				function(data) {
					if (data.delstatus == 0) {
						$.jBox.tip("ɾ��ɹ���", "success");
						if (after == "undefined" || typeof(after) == undefined || typeof(after) == "undefined") {
							window.location.href = pathUpload + "web/album.htm?albumId=" + id;
						} else {
							window.location.href = after;
						}
					} else {
						if (data.delstatus == 8000) {
							window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + params.picId;
						} else {
							$.jBox.tip("ɾ��ʧ�ܡ�", "success");
						}
					}
				});
			} else {
				if (v == "cancel") {}
			}
			return true;
		};
		$.jBox.confirm("ȷ��ɾ��", "��ͼ", submit);
	});
	$(".travels_op_pic").click(function() {
		var albumId = $("#albumIdInfo").val();
		var dayId = GetQueryString("dayId");
		if (dayId == null) {
			dayId = $("#firstDay").val();
		}
		var before = $(this).parent().find("input[name='before']").val();
		var after = $(this).parent().find("input[name='after']").val();
		window.location.href = pathUpload + "web/addPicAlbum.htm?id=" + albumId + "&before=" + before + "&after=" + after + "&type=1&dayId=" + dayId;
	});
	$(".travels_op_text").click(function() {
		$(this).parent().parent().parent().parent().find(".travels_mood_textarea").remove();
		var id = $("#albumIdInfo").val();
		var before = $(this).parent().find("input[name='before']").val();
		if (before == "") {
			before = new Date().getTime();
		}
		var after = $(this).parent().find("input[name='after']").val();
		var desc = $(this).parent().find("input[name='before']").val();
		$(this).parent().parent().parent().clone(true).hide().insertAfter($(this).parent().parent().parent());
		$(this).parent().parent().parent().after("<div class='J-mood-all travels_mood_textarea fn-tc'><div class='travels_describe fn-pr'><textarea id='desc' class='mood_desc' /><div id='promote1' class='fn-pa textarea_error_mood fn-tl fn-none'></div></div><p class='mt20 fn-tr'><input class='J-mood-text cancel mr5' name='' type='button' value='' ><input class='confirm' type='button' value='' onclick='saveFeel(" + id + "," + before + "," + after + ");'/></p></div>");
		$(".mood_desc").autoTextarea({
			maxHeight: 400,
			minHeight: 68
		});
		$(".mood_desc").bind("keyup paste keydown",
		function() {
			$(this).siblings("#promote1").css("display", "block");
			var curLength = $.trim($(this).val()).length;
			if (curLength > 1000) {
				var num = $.trim($(this).val()).length - 1000;
				$(this).siblings("#promote1").html("�Ѿ�����<span style='color:#de2c28;font-size:12px;'>" + num + "</span>����");
			} else {
				if (curLength == 0) {
					if ($.trim($(this).val()) == "") {
						$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>1000</span>����");
					} else {
						$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>999</span>����");
					}
				} else {
					var num = 1000 - $.trim($(this).val()).length;
					$(this).siblings("#promote1").html("����������<span style='color:#0069ca;font-size:12px;'>" + num + "</span>����");
				}
			}
		});
		cancelMood();
	});
	$(".travels_comment").click(function() {
		var visitorId = $("#visitorId").val();
		if (visitorId == null || visitorId == "") {
			var temp = window.location.href;
			window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(temp);
		} else {
			var picContent = $(this).parent().parent().parent().parent();
			picId = picContent.find("input[name='picId']").val();
			$("#opType").val(picContent.find("input[name='targeType']").val());
			$(".bg_mask").show();
			popBox();
			$(".pop_status").show();
			getCommentsByPicId(picId, $(this));
		}
	});
	$(".travels_approve,.travels_approve_hover,.album_total").click(function() {
		var userId = $("#userid").val();
		if (userId != null && userId != "") {
			praisePic($(this));
		} else {
			var albumId = GetQueryString("albumId");
			if (albumId != null && albumId != "") {
				redirectUrl(pathUpload + "web/album.htm?albumId=" + albumId, true);
			}
		}
		return false;
	});
	$("#textlimit").bind("keyup paste keydown",
	function() {
		var curLength = $.trim($("#textlimit").val()).length;
		if (curLength > 140) {
			var num = $.trim($("#textlimit").val()).length - 140;
			$("#prompt").html("�Ѿ�����<span style='color:#de2c28;font-size:12px;'>" + num + "</span>����");
		} else {
			if (curLength == 0) {
				if ($.trim($("#textlimit").val()) == "") {
					$("#prompt").html("����������<span style='color:#0069ca;font-size:12px;'>140</span>����");
				} else {
					$("#prompt").html("����������<span style='color:#0069ca;font-size:12px;'>139</span>����");
				}
			} else {
				var num = 140 - $.trim($("#textlimit").val()).length;
				$("#prompt").html("����������<span style='color:#0069ca;font-size:12px;'>" + num + "</span>����");
			}
		}
	});
	$(".travels_mode_pic").click(function() {
		var firstPicId = $("#firstPicId").val();
		if (firstPicId == null || firstPicId == "") {
			//window.location.href = pathUpload + "web/index.htm";
			window.location.href = pathUpload + 'album/picWebDetail/picId--' + firstPicId + '.html#' + firstPicId;
		} else {
			//window.location.href = pathUpload + "web/picWebDetail.htm?picId=" + firstPicId + "#" + firstPicId;
			window.location.href = pathUpload + 'album/picWebDetail/picId--' + firstPicId + '.html#' + firstPicId;
		}
		return false;
	});
	$(".travels_mode_map").click(function() {
		var albumId = $("#albumId").val();
		if (albumId == null || albumId == "") {
			window.location.href = pathUpload + "web/index.htm";
		} else {
			window.location.href = pathUpload + "web/albumMap.htm?albumId=" + albumId;
		}
		return false;
	});
});
var getDays = function() {
	var c = 8;
	var b = $("#dayId").val();
	var a = parseInt(b / c);
	var d = b % c;
	if (d > 0) {
		a = a + 1;
	}
	return a;
};
function getDay() {
	var b = getDays();
	if (b == 0 || isNaN(b)) {
		return false;
	}
	var a = (b - 1) * 368;
	$(".shortcut-nav").animate({
		top: "-=" + a
	}),
	"slow";
}
function getAddressStr(c) {
	var a = "!";
	for (var b = 0; b < c.length; b++) {
		if (c[b].id != null) {
			a = a + c[b].id + "!";
		}
	}
	return a;
}
var objComment = "";
var picId = "";
function saveFeel(g, d, f, b) {
	var a = window.location.href.split("&im=");
	var e = $("#desc").val();
	if ($.trim(e).length == 0) {
		$.jBox.tip("��ʲô��ûд��", "warning");
		return false;
	}
	if (e.length > 1000) {
		$.jBox.tip("�����������", "warning");
		return false;
	}
	var c = {
		albumId: g,
		before: d,
		after: f,
		desc: e
	};
	$.jBox.tip("�����ύ...", "loading");
	$.getJSON("web/addFeel.htm", c,
	function(h) {
		if (h.code == 0) {
			$.jBox.tip("�������ɹ�", "success");
			if (h.id != -1) {
				window.location.href = a[0] + "&im=" + h.id;
			}
		} else {
			if (h.code == -1) {
				window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
			}
		}
	});
}
cancelMood();
function cancelMood() {
	$(".J-mood-text").click(function() {
		var a = $(this).parent().parent();
		a.next("div").remove();
		a.remove();
	});
}
function getCommentsByPicId(e, b) {
	objComment = b;
	var d = 0;
	var g = $("#visitorId").val();
	var a = new Date();
	var c = a.getTime();
	var f = {
		visitorId: g,
		picId: e,
		random: c
	};
	$.getJSON("web/getCommentNum.htm", f,
	function(h) {
		d = h.totalCommentNum;
		if (d < 11) {
			$("#pagination").css("display", "none");
		} else {
			$("#pagination").css("display", "block");
		}
		$("#pagination").paging(d, {
			format: "[<nnnncnnnn>]",
			perpage: 10,
			onSelect: function(i) {
				getPageData(i, e);
				$("#textlimit").val("");
			}
		});
	});
}
var comentId = "";
var userId = "";
var picReplyId = "";
var type = 1;
function fillPage(b) {
	var a = "";
	$.each(b.comments,
	function(c, d) {
		var e = "";
		if (d.userToReply != null && d.userToReply != "") {
			e += "<li class='userName'><a href='web/userInfo.htm?userId=" + d.userInfo.userId + "'>" + d.userInfo.nickName + "</a> �ظ� <a href='web/userInfo.htm?userId=" + d.userToReply.userId + "'>" + d.userToReply.nickName + "</a>";
			e += "��" + d.content + "<em class='darkgray999 ml5 fn-cursor'>" + d.createTime + "</em>";
		} else {
			e += "<li class='userName'><a href='web/userInfo.htm?userId=" + d.userInfo.userId + "'>" + d.userInfo.nickName + "</a>�� " + d.content + "<em class='darkgray999 ml5'>" + d.createTime + "</em>";
		}
		e += "<span class=' mt5 deongaree fn-cursor' onclick=reply('" + d.userInfo.nickName + "','" + d.userInfo.userId + "','" + d.commentId + "')>";
		if ($("#visitorId").val() != d.userInfo.userId) {
			e += " �ظ�</span>";
		} else {
			e += "</span>";
		}
		a += e;
	});
	$("#feedback").empty().append(a);
}
function reply(e, c, b) {
	$("#textlimit").addClass("focus").next().show();
	$("#textlimit").css("background-image", "");
	$("#textlimit").css("background-color", "#fff");
	$("#textlimit").val("�ظ�" + e + "��");
	var a = $("#textlimit").val().length;
	var d = 140 - Number(a);
	$("#prompt").html("����������<span style='color:#0069ca;font-size:12px;'>" + d + "</span>����");
	type = 2;
	comentId = b;
	userId = c;
}
function getPageData(a, d) {
	var f = $("#visitorId").val();
	var b = new Date();
	var c = b.getTime();
	var e = {
		visitorId: f,
		picId: d,
		pageNo: a,
		random: c
	};
	$.getJSON("web/comments.htm", e,
	function(g) {
		fillPage(g);
		picReplyId = d;
	});
}
function comment() {
	var c = $("#textlimit").val();
	var d = $("#opType").val();
	if ($.trim(c).length == 0) {
		$.jBox.tip("��û���κ�����", "warning");
		return true;
	}
	if ($.trim(c).length > 140) {
		$.jBox.tip("������������", "warning");
		return true;
	}
	var a = new Date();
	var b = a.getTime();
	var e = {
		picId: picId,
		content: c,
		targetType: d,
		extcs: getExtcsValue(),
		random: b
	};
	$("#submit").attr({
		disabled: "disabled"
	});
	$.jBox.tip("�����ύ...", "loading");
	if (c.split("��").length == 1) {
		type = 1;
	}
	if (type == 1) {
		$.ajax({
			type: "POST",
			url: "web/comment.htm",
			data: e,
			dataType: "json",
			success: function(h) {
				if (h.result) {
					$.jBox.tip("���۳ɹ�", "success");
					$("#textlimit").val("");
					$("#prompt").text("����������140����");
					$("#submit").removeAttr("disabled");
					$(".bg_mask").hide();
					$(".pop_status").hide();
					var f = parseInt(objComment.find(".pic_commentnum").text());
					objComment.find(".pic_commentnum").text((f + 1).toString());
					var i = parseInt(objComment.find(".mood_commentnum").text());
					objComment.find(".mood_commentnum").text((i + 1).toString());
					var g = parseInt($(".album_comments").text());
					$(".album_comments").text((g + 1).toString());
				} else {
					if (!h.result) {
						window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + e.picId;
					} else {
						$("#submit").removeAttr("disabled");
						$.jBox.tip("����ʧ��", "error");
					}
				}
			},
			error: function(f) {
				$("#submit").removeAttr("disabled");
				$.jBox.tip("����ʧ��", "error");
			}
		});
	} else {
		if (type == 2) {
			$.jBox.tip("�����ύ...", "loading");
			var c = $("#textlimit").val();
			c = c.split("��")[1];
			e = {
				commentId: comentId,
				content: c,
				picReplyId: picReplyId,
				targetType: d,
				extcs: getExtcsValue(),
				random: b
			};
			$.getJSON("web/saveReply.htm", e,
			function(h) {
				if (h.status == true) {
					$("#submit").removeAttr("disabled");
					$("#textlimit").val("");
					$(".bg_mask").hide();
					$(".pop_status").hide();
					var f = parseInt(objComment.find(".pic_commentnum").text());
					objComment.find(".pic_commentnum").text((f + 1).toString());
					var i = parseInt(objComment.find(".mood_commentnum").text());
					objComment.find(".mood_commentnum").text((i + 1).toString());
					var g = parseInt($(".album_comments").text());
					$(".album_comments").text((g + 1).toString());
					$.jBox.tip("�ظ��ɹ�", "success");
				} else {
					$.jBox.tip("�ظ�ʧ��", "error");
				}
			});
		}
	}
}
var first = 0;
function praisePic(l) {
	var i = $("#visitorId").val();
	var c = window.location.href;
	var g = new Array();
	g = c.split("#");
	if (i == null) {
		var k = window.location.href;
		window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(k);
	} else {
		var h;
		var m = 1;
		var j;
		var b;
		if (l.hasClass("album_total")) {
			h = $("#albumIdInfo").val();
			j = $("#albumIsPraised").val();
			b = 3;
		} else {
			h = l.parent().parent().parent().parent().find("input[name='picId']").val();
			j = l.parent().parent().parent().parent().find("input[name='currIsPraised']").val();
			b = l.parent().parent().parent().parent().find("input[name='targeType']").val();
		}
		if (j == 1) {
			m = 2;
		}
		var d = new Date();
		var e = d.getTime();
		var a = $("#newhandler").val();
		var f = {
			visitorId: i,
			picId: h,
			targetType: b,
			praiseActivityType: m,
			extcs: getExtcsValue(),
			staus: "00001",
			newHandler: a,
			random: e
		};
		$.getJSON("web/praisePic.htm", f,
		function(s) {
			if (s.status == 0) {
				if (b == 1) {
					var q = l.find(".pic_prai");
					var n = l.find(".like");
					var w = parseInt(q.text());
					var v = parseInt($(".album_total").text());
					if (m == 1) {
						q.text((w + 1).toString());
						$(".album_total").text((v + 1).toString());
						q.parent().attr("title", "��ϲ��");
						n.text("��ϲ��");
						q.parent().removeClass("travels_approve");
						q.parent().addClass("travels_approve_hover");
						var o = $("#newhandler").val();
						var t = o.substring(4, 5);
						if (t == 0 && $("#sinaBind").val() == "true" && first == 0) {
							$.jBox.tip("���ϲ����ͬ��������΢�����������Զ�ͬ��������'�罻��'��ȡ��", "success", {
								timeout: 5000
							});
							first = 1;
						} else {
							$.jBox.tip("���޳ɹ�", "success");
						}
					} else {
						q.text((w - 1).toString());
						$(".album_total").text((v - 1).toString());
						q.parent().attr("title", "�Һ�ϲ����֧��һ��");
						n.text("ϲ��");
						q.parent().removeClass("travels_approve_hover");
						q.parent().addClass("travels_approve");
					}
					l.parent().parent().parent().parent().find("input[name='currIsPraised']").val(m);
				} else {
					if (b == 2) {
						var v = parseInt($(".album_total").text());
						var u = l.find(".mood_prai");
						var r = l.find(".likemood");
						var w = parseInt(u.text());
						if (m == 1) {
							u.text((w + 1).toString());
							$(".album_total").text((v + 1).toString());
							u.parent().attr("title", "��ϲ��");
							r.text("��ϲ��");
							u.parent().removeClass("travels_approve");
							u.parent().addClass("travels_approve_hover");
							var o = $("#newhandler").val();
							var t = o.substring(4, 5);
							if (t == 0 && $("#sinaBind").val() == "true" && first == 0) {
								$.jBox.tip("���ϲ����ͬ��������΢�����������Զ�ͬ��������'�罻��'��ȡ��", "success", {
									timeout: 5000
								});
								first = 1;
							} else {
								$.jBox.tip("���޳ɹ�", "success");
							}
						} else {
							u.text((w - 1).toString());
							$(".album_total").text((v - 1).toString());
							u.parent().attr("title", "�Һ�ϲ����֧��һ��");
							r.text("ϲ��");
							u.parent().removeClass("travels_approve_hover");
							u.parent().addClass("travels_approve");
						}
						l.parent().parent().parent().parent().find("input[name='currIsPraised']").val(m);
					} else {
						if (b == 3) {
							var v = parseInt($(".album_total").text());
							var p = l;
							if (m == 1) {
								p.text((v + 1).toString());
								p.attr("title", "��ϲ��");
								var o = $("#newhandler").val();
								var t = o.substring(4, 5);
								if (t == 0 && $("#sinaBind").val() == "true" && first == 0) {
									$.jBox.tip("���ϲ����ͬ��������΢�����������Զ�ͬ��������'�罻��'��ȡ��", "success", {
										timeout: 5000
									});
									first = 1;
								} else {
									$.jBox.tip("�μǳ��޳ɹ�", "success");
								}
							} else {
								p.text((v - 1).toString());
								p.attr("title", "�Һ�ϲ����֧��һ��");
							}
							$("#albumIsPraised").val(m);
						}
					}
				}
			}
		});
	}
} (function(e) {
	var c = {
		method: "GET",
		contentType: "json",
		queryParam: "q",
		searchDelay: 300,
		minChars: 1,
		propertyToSearch: "name",
		jsonContainer: null,
		hintText: "Type in a search term",
		noResultsText: "No results",
		searchingText: "Searching...",
		deleteText: "&times;",
		animateDropdown: true,
		tokenLimit: null,
		tokenDelimiter: ",",
		preventDuplicates: false,
		tokenValue: "id",
		prePopulate: null,
		processPrePopulate: false,
		idPrefix: "token-input-",
		resultsFormatter: function(g) {
			return "<li>" + g[this.propertyToSearch] + "</li>";
		},
		tokenFormatter: function(g) {
			return "<li><p>" + g[this.propertyToSearch] + "</p></li>";
		},
		onResult: null,
		onAdd: null,
		onDelete: null,
		onReady: null
	};
	var f = {
		tokenList: "token-input-list",
		token: "token-input-token",
		tokenDelete: "token-input-delete-token",
		selectedToken: "token-input-selected-token",
		highlightedToken: "token-input-highlighted-token",
		dropdown: "token-input-dropdown",
		dropdownItem: "token-input-dropdown-item",
		dropdownItem2: "token-input-dropdown-item2",
		selectedDropdownItem: "token-input-selected-dropdown-item",
		inputToken: "token-input-input-token"
	};
	var d = {
		BEFORE: 0,
		AFTER: 1,
		END: 2
	};
	var a = {
		BACKSPACE: 8,
		TAB: 9,
		ENTER: 13,
		ESCAPE: 27,
		SPACE: 32,
		PAGE_UP: 33,
		PAGE_DOWN: 34,
		END: 35,
		HOME: 36,
		LEFT: 37,
		UP: 38,
		RIGHT: 39,
		DOWN: 40,
		NUMPAD_ENTER: 108,
		COMMA: 188
	};
	var b = {
		init: function(g, h) {
			var i = e.extend({},
			c, h || {});
			return this.each(function() {
				e(this).data("tokenInputObject", new e.TokenList(this, g, i));
			});
		},
		clear: function() {
			this.data("tokenInputObject").clear();
			return this;
		},
		add: function(g) {
			this.data("tokenInputObject").add(g);
			return this;
		},
		remove: function(g) {
			this.data("tokenInputObject").remove(g);
			return this;
		},
		get: function() {
			return this.data("tokenInputObject").getTokens();
		}
	};
	e.fn.tokenInput = function(g) {
		if (b[g]) {
			return b[g].apply(this, Array.prototype.slice.call(arguments, 1));
		} else {
			return b.init.apply(this, arguments);
		}
	};
	e.TokenList = function(i, s, Q) {
		if (e.type(s) === "string" || e.type(s) === "function") {
			Q.url = s;
			var m = x();
			if (Q.crossDomain === undefined) {
				if (m.indexOf("://") === -1) {
					Q.crossDomain = false;
				} else {
					Q.crossDomain = (location.href.split(/\/+/g)[1] !== m.split(/\/+/g)[1]);
				}
			}
		} else {
			if (typeof(s) === "object") {
				Q.local_data = s;
			}
		}
		if (Q.classes) {
			Q.classes = e.extend({},
			f, Q.classes);
		} else {
			if (Q.theme) {
				Q.classes = {};
				e.each(f,
				function(V, W) {
					Q.classes[V] = W + "-" + Q.theme;
				});
			} else {
				Q.classes = f;
			}
		}
		var E = [];
		var v = 0;
		var r = new e.TokenList.Cache();
		var O;
		var L;
		var z = e('<input type="text"  autocomplete="off">').css({
			outline: "none"
		}).attr("id", Q.idPrefix + i.id).focus(function() {
			if (Q.tokenLimit === null || Q.tokenLimit !== v) {
				l();
			}
		}).blur(function() {
			F();
			e(this).val("");
		}).bind("keyup keydown blur update", g).keyup(function(W) {
			var Y;
			var V;
			switch (W.keyCode) {
			case a.LEFT:
			case a.RIGHT:
			case a.UP:
			case a.DOWN:
				if (!e(this).val()) {
					Y = n.prev();
					V = n.next();
					if ((Y.length && Y.get(0) === C) || (V.length && V.get(0) === C)) {
						if (W.keyCode === a.LEFT || W.keyCode === a.UP) {
							I(e(C), d.BEFORE);
						} else {
							I(e(C), d.AFTER);
						}
					} else {
						if ((W.keyCode === a.LEFT || W.keyCode === a.UP) && Y.length) {
							R(e(Y.get(0)));
						} else {
							if ((W.keyCode === a.RIGHT || W.keyCode === a.DOWN) && V.length) {
								R(e(V.get(0)));
							}
						}
					}
				} else {
					var X = null;
					if (W.keyCode === a.DOWN || W.keyCode === a.RIGHT) {
						X = e(N).next();
					} else {
						X = e(N).prev();
					}
					if (X.length) {
						U(X);
					}
					return false;
				}
				break;
			case a.BACKSPACE:
				Y = n.prev();
				if (!e(this).val().length) {
					if (C) {
						k(e(C));
						D.change();
					} else {
						if (Y.length) {
							R(e(Y.get(0)));
						}
					}
					return false;
				} else {
					if (e(this).val().length === 1) {
						F();
					} else {
						setTimeout(function() {
							B();
						},
						5);
					}
				}
				break;
			case a.TAB:
			case a.ENTER:
			case a.NUMPAD_ENTER:
			case a.COMMA:
				if (N) {
					K(e(N).data("tokeninput"));
					D.change();
					return false;
				}
				break;
			case a.ESCAPE:
				F();
				return true;
			default:
				if (String.fromCharCode(W.which)) {
					setTimeout(function() {
						B();
					},
					5);
				}
				break;
			}
		});
		var D = e(i).hide().val("").focus(function() {
			z.focus();
		}).blur(function() {
			z.blur();
		});
		var C = null;
		var G = 0;
		var N = null;
		var p = e("<ul />").addClass(Q.classes.tokenList).click(function(W) {
			var V = e(W.target).closest("li");
			if (V && V.get(0) && e.data(V.get(0), "tokeninput")) {
				T(V);
			} else {
				if (C) {
					I(e(C), d.END);
				}
				z.focus();
			}
		}).mouseover(function(W) {
			var V = e(W.target).closest("li");
			if (V && C !== this) {
				V.addClass(Q.classes.highlightedToken);
			}
		}).mouseout(function(W) {
			var V = e(W.target).closest("li");
			if (V && C !== this) {
				V.removeClass(Q.classes.highlightedToken);
			}
		}).insertBefore(D);
		var n = e("<li />").addClass(Q.classes.inputToken).appendTo(p).append(z);
		var S = e("<div>").addClass(Q.classes.dropdown).appendTo("body").hide();
		var J = e("<tester/>").insertAfter(z).css({
			position: "absolute",
			top: -9999,
			left: -9999,
			width: "auto",
			fontSize: z.css("fontSize"),
			fontFamily: z.css("fontFamily"),
			fontWeight: z.css("fontWeight"),
			letterSpacing: z.css("letterSpacing"),
			whiteSpace: "nowrap"
		});
		D.val("");
		var y = Q.prePopulate || D.data("pre");
		if (Q.processPrePopulate && e.isFunction(Q.onResult)) {
			y = Q.onResult.call(D, y);
		}
		if (y && y.length) {
			e.each(y,
			function(V, W) {
				j(W);
				H();
			});
		}
		if (e.isFunction(Q.onReady)) {
			Q.onReady.call();
		}
		this.clear = function() {
			p.children("li").each(function() {
				if (e(this).children("input").length === 0) {
					k(e(this));
				}
			});
		};
		this.add = function(V) {
			K(V);
		};
		this.remove = function(V) {
			p.children("li").each(function() {
				if (e(this).children("input").length === 0) {
					var Y = e(this).data("tokeninput");
					var W = true;
					for (var X in V) {
						if (V[X] !== Y[X]) {
							W = false;
							break;
						}
					}
					if (W) {
						k(e(this));
					}
				}
			});
		};
		this.getTokens = function() {
			return E;
		};
		function H() {
			if (Q.tokenLimit !== null && v >= Q.tokenLimit) {
				z.hide();
				F();
				return;
			}
		}
		function g() {
			if (L === (L = z.val())) {
				return;
			}
			var V = L.replace(/&/g, "&amp;").replace(/\s/g, " ").replace(/</g, "&lt;").replace(/>/g, "&gt;");
			J.html(V);
			z.width(J.width() + 30);
		}
		function P(V) {
			return ((V >= 48 && V <= 90) || (V >= 96 && V <= 111) || (V >= 186 && V <= 192) || (V >= 219 && V <= 222));
		}
		function j(V) {
			var X = Q.tokenFormatter(V);
			X = e(X).addClass(Q.classes.token).insertBefore(n);
			e("<span>" + Q.deleteText + "</span>").addClass(Q.classes.tokenDelete).appendTo(X).click(function() {
				k(e(this).parent());
				D.change();
				return false;
			});
			var W = {
				id: V.id
			};
			W[Q.propertyToSearch] = V[Q.propertyToSearch];
			e.data(X.get(0), "tokeninput", V);
			E = E.slice(0, G).concat([W]).concat(E.slice(G));
			G++;
			w(E, D);
			v += 1;
			if (Q.tokenLimit !== null && v >= Q.tokenLimit) {
				z.hide();
				F();
			}
			return X;
		}
		function K(V) {
			var X = Q.onAdd;
			if (v > 0 && Q.preventDuplicates) {
				var W = null;
				p.children().each(function() {
					var Z = e(this);
					var Y = e.data(Z.get(0), "tokeninput");
					if (Y && Y.id === V.id) {
						W = Z;
						return false;
					}
				});
				if (W) {
					R(W);
					n.insertAfter(W);
					z.focus();
					return;
				}
			}
			if (Q.tokenLimit == null || v < Q.tokenLimit) {
				j(V);
				H();
			}
			z.val("");
			F();
			if (e.isFunction(X)) {
				X.call(D, V);
			}
		}
		function R(V) {
			V.addClass(Q.classes.selectedToken);
			C = V.get(0);
			z.val("");
			F();
		}
		function I(W, V) {
			W.removeClass(Q.classes.selectedToken);
			C = null;
			if (V === d.BEFORE) {
				n.insertBefore(W);
				G--;
			} else {
				if (V === d.AFTER) {
					n.insertAfter(W);
					G++;
				} else {
					n.appendTo(p);
					G = v;
				}
			}
			z.focus();
		}
		function T(W) {
			var V = C;
			if (C) {
				I(e(C), d.END);
			}
			if (V === W.get(0)) {
				I(W, d.END);
			} else {
				R(W);
			}
		}
		function k(W) {
			var X = e.data(W.get(0), "tokeninput");
			var Y = Q.onDelete;
			var V = W.prevAll().length;
			if (V > G) {
				V--;
			}
			W.remove();
			C = null;
			z.focus();
			E = E.slice(0, V).concat(E.slice(V + 1));
			if (V < G) {
				G--;
			}
			w(E, D);
			v -= 1;
			if (Q.tokenLimit !== null) {
				z.show().val("").focus();
			}
			if (e.isFunction(Y)) {
				Y.call(D, X);
			}
		}
		function w(X, V) {
			var W = e.map(X,
			function(Y) {
				return Y[Q.tokenValue];
			});
			V.val(W.join(Q.tokenDelimiter));
		}
		function F() {
			S.hide().empty();
			N = null;
		}
		function q() {
			S.css({
				position: "absolute",
				top: e(p).offset().top + e(p).outerHeight(),
				left: e(p).offset().left,
				zindex: 999
			}).show();
		}
		function o() {
			if (Q.searchingText) {
				q();
			}
		}
		function l() {
			if (Q.hintText) {
				F();
			}
		}
		function u(W, V) {
			return W.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + V + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<b>$1</b>");
		}
		function A(W, X, V) {
			return W.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + X + ")(?![^<>]*>)(?![^&;]+;)", "g"), u(X, V));
		}
		function M(X, V) {
			if (V && V.length) {
				S.empty();
				var W = e("<ul>").appendTo(S).mouseover(function(Y) {
					U(e(Y.target).closest("li"));
				}).mousedown(function(Y) {
					K(e(Y.target).closest("li").data("tokeninput"));
					D.change();
					return false;
				}).hide();
				e.each(V,
				function(Y, Z) {
					var aa = Q.resultsFormatter(Z);
					aa = A(aa, Z[Q.propertyToSearch], X);
					aa = e(aa).appendTo(W);
					if (Y % 2) {
						aa.addClass(Q.classes.dropdownItem);
					} else {
						aa.addClass(Q.classes.dropdownItem2);
					}
					if (Y === 0) {
						U(aa);
					}
					e.data(aa.get(0), "tokeninput", Z);
				});
				q();
				if (Q.animateDropdown) {
					W.slideDown("fast");
				} else {
					W.show();
				}
			} else {
				if (Q.noResultsText) {
					S.html("<p>�Բ��𣬲�֧�ָõ�</p>");
					q();
				}
			}
		}
		function U(V) {
			if (V) {
				if (N) {
					h(e(N));
				}
				V.addClass(Q.classes.selectedDropdownItem);
				N = V.get(0);
			}
		}
		function h(V) {
			V.removeClass(Q.classes.selectedDropdownItem);
			N = null;
		}
		function B() {
			var V = z.val().toLowerCase();
			if (V && V.length) {
				if (C) {
					I(e(C), d.AFTER);
				}
				if (V.length >= Q.minChars) {
					o();
					clearTimeout(O);
					O = setTimeout(function() {
						t(V);
					},
					Q.searchDelay);
				} else {
					F();
				}
			}
		}
		function t(ab) {
			var X = ab + x();
			var V = r.get(X);
			if (V) {
				M(ab, V);
			} else {
				if (Q.url) {
					var Z = x();
					var Y = {};
					Y.data = {};
					if (Z.indexOf("?") > -1) {
						var ac = Z.split("?");
						Y.url = ac[0];
						var W = ac[1].split("&");
						e.each(W,
						function(ad, af) {
							var ae = af.split("=");
							Y.data[ae[0]] = ae[1];
						});
					} else {
						Y.url = Z;
					}
					Y.data[Q.queryParam] = ab;
					Y.type = Q.method;
					Y.dataType = Q.contentType;
					if (Q.crossDomain) {
						Y.dataType = "jsonp";
					}
					Y.success = function(ad) {
						if (e.isFunction(Q.onResult)) {
							ad = Q.onResult.call(D, ad);
						}
						r.add(X, Q.jsonContainer ? ad[Q.jsonContainer] : ad);
						if (z.val().toLowerCase() === ab) {
							M(ab, Q.jsonContainer ? ad[Q.jsonContainer] : ad);
						}
					};
					e.ajax(Y);
				} else {
					if (Q.local_data) {
						var aa = e.grep(Q.local_data,
						function(ad) {
							return ad[Q.propertyToSearch].toLowerCase().indexOf(ab.toLowerCase()) > -1;
						});
						if (e.isFunction(Q.onResult)) {
							aa = Q.onResult.call(D, aa);
						}
						r.add(X, aa);
						M(ab, aa);
					}
				}
			}
		}
		function x() {
			var V = Q.url;
			if (typeof Q.url == "function") {
				V = Q.url.call();
			}
			return V;
		}
	};
	e.TokenList.Cache = function(h) {
		var j = e.extend({
			max_size: 500
		},
		h);
		var k = {};
		var i = 0;
		var g = function() {
			k = {};
			i = 0;
		};
		this.add = function(m, l) {
			if (i > j.max_size) {
				g();
			}
			if (!k[m]) {
				i += 1;
			}
			k[m] = l;
		};
		this.get = function(l) {
			return k[l];
		};
	};
} (jQuery)); (function(b, a, c) {
	b.fn["paging"] = function(h, g) {
		var e = this,
		d = {
			setOptions: function(j) {
				function i(p) {
					var k = 0,
					q = 0,
					l = 1,
					o = {
						fstack: [],
						asterisk: 0,
						inactive: 0,
						blockwide: 5,
						current: 3,
						rights: 0,
						lefts: 0
					},
					s,
					n = /[*<>pq\[\]().-]|[nc]+!?/g;
					var r = {
						"[": "first",
						"]": "last",
						"<": "prev",
						">": "next",
						q: "left",
						p: "right",
						"-": "fill",
						".": "leap"
					},
					m = {};
					while ((s = n.exec(p))) {
						s = ("" + s);
						if (c === r[s]) {
							if ("(" === s) {
								q = ++k;
							} else {
								if (")" === s) {
									q = 0;
								} else {
									if (l) {
										if ("*" === s) {
											o.asterisk = 1;
											o.inactive = 0;
										} else {
											o.asterisk = 0;
											o.inactive = "!" === s.charAt(s.length - 1);
											o.blockwide = s.length - o.inactive;
											if (! (o.current = 1 + s.indexOf("c"))) {
												o.current = (1 + o.blockwide) >> 1;
											}
										}
										o.fstack[o.fstack.length] = ({
											ftype: "block",
											group: 0,
											pos: 0
										});
										l = 0;
									}
								}
							}
						} else {
							o.fstack[o.fstack.length] = ({
								ftype: r[s],
								group: q,
								pos: c === m[s] ? m[s] = 1 : ++m[s]
							});
							if ("q" === s) {++o.lefts;
							} else {
								if ("p" === s) {++o.rights;
								}
							}
						}
					}
					return o;
				}
				this.opts = b.extend(this.opts || {
					lapping: 0,
					perpage: 10,
					page: 1,
					refresh: {
						interval: 10,
						url: null
					},
					format: "",
					onFormat: function(k) {
						switch (k) {
						case "block":
							if (!this.active) {
								return '<span class="disabled">' + this.value + "</span>";
							} else {
								if (this.value != this.page) {
									return '<em><a href="#' + this.value + '">' + this.value + "</a></em>";
								}
							}
							return '<span class="current">' + this.value + "</span>";
						case "next":
							if (this.active) {
								return '<a href="#' + this.value + '" class="next png24">��һҳ</a>';
							}
							return '<a href="#' + this.value + '" class="next png24">��һҳ</a>';
						case "prev":
							if (this.active) {
								return '<a href="#' + this.value + '" class="prev png24">ǰһҳ</a>';
							}
							return '<a href="#' + this.value + '" class="prev png24">ǰһҳ</a>';
						case "first":
							if (this.active) {
								return '<a href="#' + this.value + '" class="first png24">��ҳ</a>';
							}
							return '<a href="#' + this.value + '" class="first png24">��ҳ</a>';
						case "last":
							if (this.active) {
								return '<a href="#' + this.value + '" class="last png24">ĩҳ</a>';
							}
							return '<a href="#' + this.value + '" class="last png24">ĩҳ</a>';
						case "right":
						case "left":
							if (!this.active) {
								return "";
							}
							return '<a href="#' + this.value + '">' + this.value + "</a>";
						case "leap":
							if (this.active) {
								return "   ";
							}
							return "";
						case "fill":
							if (this.active) {
								return "...";
							}
							return "";
						}
					},
					onSelect: function(k) {
						return true;
					},
					onRefresh: function(k) {}
				},
				j || {});
				if (this.opts.perpage < 1) {
					this.opts.perpage = 10;
				}
				if (this.opts.refresh["url"]) {
					if (this.interval) {
						a.clearInterval(this.interval);
					}
					this.interval = a.setInterval(function(l, k) {
						k.ajax({
							url: l.opts.refresh["url"],
							success: function(n) {
								try {
									var m = k.parseJSON(n);
								} catch(p) {
									return;
								}
								p.opts.onRefresh(m);
							}
						});
					},
					1000 * this.opts.refresh["interval"], this, b);
				}
				this.format = i(this.opts.format);
				return this;
			},
			setNumber: function(i) {
				this.number = (c === i || i < 0) ? -1 : i;
				return this;
			},
			setPage: function(v) {
				if (c === v) {
					if (v = this.opts.page, null === v) {
						return this;
					}
				} else {
					if (this.opts.page == v) {
						return this;
					}
				}
				this.opts.page = (v |= 0);
				var p = this.number;
				var j = this.opts;
				var x, k;
				var l, q;
				var m = 1,
				w = this.format;
				var t, s, n, o;
				var u = w.fstack.length,
				r = u;
				if (j.perpage <= j.lapping) {
					j.lapping = j.perpage - 1;
				}
				o = p <= j.lapping ? 0 : j.lapping | 0;
				if (p < 0) {
					p = -1;
					l = -1;
					x = Math.max(1, v - w.current + 1 - o);
					k = x + w.blockwide;
				} else {
					l = 1 + Math.ceil((p - j.perpage) / (j.perpage - o));
					v = Math.max(1, Math.min(v < 0 ? 1 + l + v: v, l));
					if (w.asterisk) {
						x = 1;
						k = 1 + l;
						w.current = v;
						w.blockwide = l;
					} else {
						x = Math.max(1, Math.min(v - w.current, l - w.blockwide) + 1);
						k = w.inactive ? x + w.blockwide: Math.min(x + w.blockwide, 1 + l);
					}
				}
				while (r--) {
					s = 0;
					n = w.fstack[r];
					switch (n.ftype) {
					case "left":
						s = (n.pos < x);
						break;
					case "right":
						s = (k <= l - w.rights + n.pos);
						break;
					case "first":
						s = (w.current < v);
						break;
					case "last":
						s = (w.blockwide < w.current + l - v);
						break;
					case "prev":
						s = (1 < v);
						break;
					case "next":
						s = (v < l);
						break;
					}
					m |= s << n.group;
				}
				t = {
					number: p,
					lapping: o,
					pages: l,
					perpage: j.perpage,
					page: v,
					slice: [(s = v * (j.perpage - o) + o) - j.perpage, Math.min(s, p)]
				};
				q = b(document.createElement("div"));
				while (++r < u) {
					n = w.fstack[r];
					s = (m >> n.group & 1);
					switch (n.ftype) {
					case "block":
						for (; x < k; ++x) {
							t.value = x;
							t.pos = 1 + w.blockwide - k + x;
							t.active = x <= l || p < 0;
							t.first = 1 === x;
							t.last = x == l && 0 < p;
							s = document.createElement("div");
							s.innerHTML = j.onFormat.call(t, n.ftype);
							b("a", s = b(s)).data("page", t.value).click(f);
							q.append(s.contents());
						}
						continue;
					case "left":
						t.value = t.pos = n.pos;
						t.active = n.pos < x;
						break;
					case "right":
						t.value = l - w.rights + n.pos;
						t.pos = n.pos;
						t.active = k <= t.value;
						break;
					case "first":
						t.value = 1;
						t.pos = n.pos;
						t.active = s && w.current < v;
						break;
					case "last":
						t.value = l;
						t.pos = n.pos;
						t.active = s && w.blockwide < w.current + l - v;
						break;
					case "prev":
						t.value = Math.max(1, v - 1);
						t.pos = n.pos;
						t.active = s && 1 < v;
						break;
					case "next":
						t.pos = n.pos;
						if ((t.active = (p < 0))) {
							t.value = 1 + v;
						} else {
							t.value = Math.min(1 + v, l);
							t.active = s && v < l;
						}
						break;
					case "leap":
					case "fill":
						t.pos = n.pos;
						t.active = s;
						q.append(j.onFormat.call(t, n.ftype));
						continue;
					}
					t.last = t.first = c;
					s = document.createElement("div");
					s.innerHTML = j.onFormat.call(t, n.ftype);
					b("a", s = b(s)).data("page", t.value).click(f);
					q.append(s.contents());
				}
				e.html(q.contents());
				this.locate = j.onSelect.call({
					number: p,
					lapping: o,
					pages: l,
					slice: t.slice
				},
				v);
				return this;
			}
		};
		function f(i) {
			i.preventDefault();
			var j = i.target;
			do {
				if ("a" === j.nodeName.toLowerCase()) {
					break;
				}
			} while (( j = j . parentNode ));
			d.setPage(b.data(j, "page"));
			if (d.locate) {
				a.location = j.href;
			}
		}
		return d.setNumber(h)["setOptions"](g)["setPage"]();
	};
} (jQuery, this)); (function(a) {
	a.fn.autoTextarea = function(b) {
		var d = {
			maxHeight: null,
			minHeight: a(this).height()
		};
		var c = a.extend({},
		d, b);
		return a(this).each(function() {
			if (a(this).html() != "") {
				a(this).height(this.scrollHeight);
			} else {}
			a(this).bind("paste cut keydown keyup focus blur",
			function() {
				var e, f = this.style;
				this.style.height = c.minHeight + "px";
				if (this.scrollHeight > c.minHeight) {
					if (c.maxHeight && this.scrollHeight > c.maxHeight) {
						e = c.maxHeight;
						f.overflowY = "scroll";
					} else {
						e = this.scrollHeight;
						f.overflowY = "hidden";
					}
					f.height = e + "px";
				}
			});
		});
	};
})(jQuery);