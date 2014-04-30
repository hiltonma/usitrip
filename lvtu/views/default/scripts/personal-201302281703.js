(function() {
	var a = jQuery.event.special,
	c = "D" + ( + new Date()),
	b = "D" + ( + new Date() + 1);
	a.scrollstart = {
		setup: function() {
			var e, d = function(h) {
				var f = this,
				g = arguments;
				if (e) {
					clearTimeout(e);
				} else {
					h.type = "scrollstart";
					jQuery.event.handle.apply(f, g);
				}
				e = setTimeout(function() {
					e = null;
				},
				a.scrollstop.latency);
			};
			jQuery(this).bind("scroll", d).data(c, d);
		},
		teardown: function() {
			jQuery(this).unbind("scroll", jQuery(this).data(c));
		}
	};
	a.scrollstop = {
		latency: 300,
		setup: function() {
			var e, d = function(h) {
				var f = this,
				g = arguments;
				if (e) {
					clearTimeout(e);
				}
				e = setTimeout(function() {
					e = null;
					h.type = "scrollstop";
					jQuery.event.handle.apply(f, g);
				},
				a.scrollstop.latency);
			};
			jQuery(this).bind("scroll", d).data(b, d);
		},
		teardown: function() {
			jQuery(this).unbind("scroll", jQuery(this).data(b));
		}
	};
})();
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
} ('(5($){$.J.L=5(r){8 1={d:0,A:0,b:"h",v:"N",3:4};6(r){$.D(1,r)}8 m=9;6("h"==1.b){$(1.3).p("h",5(b){8 C=0;m.t(5(){6(!$.k(9,1)&&!$.l(9,1)){$(9).z("o")}j{6(C++>1.A){g B}}});8 w=$.M(m,5(f){g!f.e});m=$(w)})}g 9.t(5(){8 2=9;$(2).c("s",$(2).c("i"));6("h"!=1.b||$.k(2,1)||$.l(2,1)){6(1.u){$(2).c("i",1.u)}j{$(2).K("i")}2.e=B}j{2.e=x}$(2).T("o",5(){6(!9.e){$("<V />").p("X",5(){$(2).Y().c("i",$(2).c("s"))[1.v](1.Z);2.e=x}).c("i",$(2).c("s"))}});6("h"!=1.b){$(2).p(1.b,5(b){6(!2.e){$(2).z("o")}})}})};$.k=5(f,1){6(1.3===E||1.3===4){8 7=$(4).F()+$(4).O()}j{8 7=$(1.3).n().G+$(1.3).F()}g 7<=$(f).n().G-1.d};$.l=5(f,1){6(1.3===E||1.3===4){8 7=$(4).I()+$(4).U()}j{8 7=$(1.3).n().q+$(1.3).I()}g 7<=$(f).n().q-1.d};$.D($.P[\':\'],{"Q-H-7":"$.k(a, {d : 0, 3: 4})","R-H-7":"!$.k(a, {d : 0, 3: 4})","S-y-7":"$.l(a, {d : 0, 3: 4})","q-y-7":"!$.l(a, {d : 0, 3: 4})"})})(W);', 62, 62, "|settings|self|container|window|function|if|fold|var|this||event|attr|threshold|loaded|element|return|scroll|src|else|belowthefold|rightoffold|elements|offset|appear|bind|left|options|original|each|placeholder|effect|temp|true|of|trigger|failurelimit|false|counter|extend|undefined|height|top|the|width|fn|removeAttr|lazyload|grep|show|scrollTop|expr|below|above|right|one|scrollLeft|img|jQuery|load|hide|effectspeed".split("|"), 0, {}));
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
		placeholder: "/lvtu/views/default/image/lvtu_bg_2.jpg",//"http://source.qunar.com/site/images/wap/lvtu/lvtu_bg_2.jpg",
		effect: showeffect,
		failurelimit: 2
	});
}); (function(b, a, c) {
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
								return '<a href="#' + this.value + '" class="next png24">下一页</a>';
							}
							return '<a href="#' + this.value + '" class="next png24">下一页</a>';
						case "prev":
							if (this.active) {
								return '<a href="#' + this.value + '" class="prev png24">前一页</a>';
							}
							return '<a href="#' + this.value + '" class="prev png24">前一页</a>';
						case "first":
							if (this.active) {
								return '<a href="#' + this.value + '" class="first png24">首页</a>';
							}
							return '<a href="#' + this.value + '" class="first png24">首页</a>';
						case "last":
							if (this.active) {
								return '<a href="#' + this.value + '" class="last png24">末页</a>';
							}
							return '<a href="#' + this.value + '" class="last png24">末页</a>';
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
} (jQuery, this));
function focusPerson(a) {
	var b = {
		activityType: a,
		extcs: getExtcsValue(),
		userId: GetQueryString("userId")
	};
	if (a == 0) {
		window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
	} else {
		$.getJSON("web/focusUser.htm", b,
		function(c) {
			if (c.result) {
				var d = "";
				if (a == 1) {
					d += "<a href='javascript:focusPerson(2);'><p class='personal_attention_cancel ml5 mt10 png24'></p></a>";
					$("#fans").text((Number($("#fans").text()) + 1));
				} else {
					if (a == 2) {
						d += "<a href='javascript:focusPerson(1);'><p  class='personal_attention_add ml5 mt10 png24'></p></a>";
						$("#fans").text((Number($("#fans").text()) - 1));
					}
				}
				$("#focus").html("");
				$("#focus").append(d);
			} else {}
		});
	}
}
$(function() {
	var d = $(".personal_main").offset().top;
	$("html,body").animate({
		scrollTop: d
	},
	1);
	$(".J-img").click(function() {
		var e = $(this).find("#personAlbumHref").val();
		window.open(e);
	});
	$(".J-img").hover(function() {
		var f = $(this).find(".J-imgNum").width();
		var e = (600 - f) / 2;
		$(".J-imgNum").css("left", e);
		$(this).find(".personal_pic_bg").stop().animate({
			bottom: "0px"
		});
		$(this).find(".J-imgNum").fadeIn("slow").css("left", e);
	},
	function() {
		$(this).find(".personal_pic_bg").stop().animate({
			bottom: "-400px"
		});
		$(this).find(".J-imgNum").fadeOut("slow");
	});
	$(function() {
		$("img.lazy").lazyload({
			event: "scrollstop",
			effect: "fadeIn",
			threshold: 300
		});
	});
	var b = $("#pageNo").val();
	var c = $("#albumNum").val();
	var a = $("#pageSize").val();
	$("#pagination").paging(c, {
		format: "[<nnnncnnnn>]",
		perpage: a,
		page: b,
		onSelect: function(f) {
			if ($("#isFirstCome").val() == 1) {
				$("#isFirstCome").val(2);
				return;
			} else {
				var e = $("#hostId").val();
				window.location.href = pathUpload + "web/userInfo.htm?userId=" + e + "&page=" + f;
			}
		}
	});
});
$(function() {
	function a() {
		var d = $(window).scrollTop();
		var c = $(".personal_main").offset().top;
		var e = $(".personal_sidebar");
		if (d >= c) {
			e.addClass("sidebar_fixed");
		} else {
			e.removeClass("sidebar_fixed");
		}
	}
	a();
	$(window).scroll(function() {
		a();
	});
	if ($("#currentRight").val() == 1) {} else {
		$("#friendTrends").hover(function() {
			$(this).removeClass("friend_trends mylist_link").addClass("friend_trends_current mylist_hover");
		},
		function() {
			$(this).removeClass("friend_trends_current mylist_hover").addClass("friend_trends mylist_link");
		});
	}
	if ($("#currentRight").val() == 2) {} else {
		$("#myfriends").hover(function() {
			$(this).removeClass("my_friend mylist_link").addClass("my_friend_current mylist_hover");
		},
		function() {
			$(this).removeClass("my_friend_current mylist_hover").addClass("my_friend mylist_link");
		});
	}
	if ($("#currentRight").val() == 4) {} else {
		$("#commentandmessages").hover(function() {
			$(this).removeClass("new_comment mylist_link").addClass("new_comment_current mylist_hover");
		},
		function() {
			$(this).removeClass("new_comment_current mylist_hover").addClass("new_comment mylist_link");
		});
	}
	if ($("#currentRight").val() == 5) {} else {
		$("#accountsets").hover(function() {
			$(this).removeClass("set_accounts mylist_link").addClass("set_accounts_current mylist_hover");
		},
		function() {
			$(this).removeClass("set_accounts_current mylist_hover").addClass("set_accounts mylist_link");
		});
	}
	if ($("#currentRight").val() == 3) {} else {
		$("#mylikes").hover(function() {
			$(this).removeClass("my_like mylist_link").addClass("my_like_current mylist_hover");
		},
		function() {
			$(this).removeClass("my_like_current mylist_hover").addClass("my_like mylist_link");
		});
	}
	if ($("#currentRight").val() == 6) {} else {
		$("#socialBound").hover(function() {
			$(this).removeClass("social_bound mylist_link").addClass("social_bound_current mylist_hover");
		},
		function() {
			$(this).removeClass("social_bound_current mylist_hover").addClass("social_bound mylist_link");
		});
	}
	if ($("#currentRight").val() == 2) {
		$("#myfriends").removeClass("my_friend mylist_link").addClass("my_friend_current mylist_hover");
	} else {
		if ($("#currentRight").val() == 1) {
			$("#friendTrends").removeClass("friend_trends mylist_link").addClass("friend_trends_current mylist_hover");
		} else {
			if ($("#currentRight").val() == 3) {
				$("#mylikes").removeClass("my_like mylist_link").addClass("my_like_current mylist_hover");
			} else {
				if ($("#currentRight").val() == 4) {
					$("#commentandmessages").removeClass("new_comment mylist_link").addClass("new_comment_current mylist_hover");
				} else {
					if ($("#currentRight").val() == 6) {
						$("#socialBound").removeClass("social_bound mylist_link").addClass("social_bound_current mylist_hover");
					}
				}
			}
		}
	}
});