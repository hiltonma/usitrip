
(function(b, a, c) {
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
		"确定": "ok"
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
		"确定": "ok"
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
	close: "关闭",
	ok: "确定",
	yes: "是",
	no: "否",
	cancel: "取消"
};
$.jBox.setDefaults(jBoxConfig); (function(b, a) {
	$window = b(a);
	b.fn.lazyload = function(c) {
		var e = this;
		var d = {
			threshold: 0,
			failure_limit: 0,
			event: "scroll",
			effect: "show",
			container: a,
			data_attribute: "original",
			skip_invisible: true,
			appear: null,
			load: null
		};
		function f() {
			var g = 0;
			e.each(function() {
				var h = b(this);
				if (d.skip_invisible && !h.is(":visible")) {
					return;
				}
				if (b.abovethetop(this, d) || b.leftofbegin(this, d)) {} else {
					if (!b.belowthefold(this, d) && !b.rightoffold(this, d)) {
						h.trigger("appear");
					} else {
						if (++g > d.failure_limit) {
							return false;
						}
					}
				}
			});
		}
		if (c) {
			if (undefined !== c.failurelimit) {
				c.failure_limit = c.failurelimit;
				delete c.failurelimit;
			}
			if (undefined !== c.effectspeed) {
				c.effect_speed = c.effectspeed;
				delete c.effectspeed;
			}
			b.extend(d, c);
		}
		$container = (d.container === undefined || d.container === a) ? $window: b(d.container);
		if (0 === d.event.indexOf("scroll")) {
			$container.bind(d.event,
			function(g) {
				return f();
			});
		}
		this.each(function() {
			var g = this;
			var h = b(g);
			g.loaded = false;
			h.one("appear",
			function() {
				if (!this.loaded) {
					if (d.appear) {
						var i = e.length;
						d.appear.call(g, i, d);
					}
					b("<img />").bind("load",
					function() {
						h.hide().attr("src", h.data(d.data_attribute))[d.effect](d.effect_speed);
						g.loaded = true;
						var j = b.grep(e,
						function(l) {
							return ! l.loaded;
						});
						e = b(j);
						if (d.load) {
							var k = e.length;
							d.load.call(g, k, d);
						}
					}).attr("src", h.data(d.data_attribute));
				}
			});
			if (0 !== d.event.indexOf("scroll")) {
				h.bind(d.event,
				function(i) {
					if (!g.loaded) {
						h.trigger("appear");
					}
				});
			}
		});
		$window.bind("resize",
		function(g) {
			f();
		});
		f();
		return this;
	};
	b.belowthefold = function(d, e) {
		var c;
		if (e.container === undefined || e.container === a) {
			c = $window.height() + $window.scrollTop();
		} else {
			c = $container.offset().top + $container.height();
		}
		return c <= b(d).offset().top - e.threshold;
	};
	b.rightoffold = function(d, e) {
		var c;
		if (e.container === undefined || e.container === a) {
			c = $window.width() + $window.scrollLeft();
		} else {
			c = $container.offset().left + $container.width();
		}
		return c <= b(d).offset().left - e.threshold;
	};
	b.abovethetop = function(d, e) {
		var c;
		if (e.container === undefined || e.container === a) {
			c = $window.scrollTop();
		} else {
			c = $container.offset().top;
		}
		return c >= b(d).offset().top + e.threshold + b(d).height();
	};
	b.leftofbegin = function(d, e) {
		var c;
		if (e.container === undefined || e.container === a) {
			c = $window.scrollLeft();
		} else {
			c = $container.offset().left;
		}
		return c >= b(d).offset().left + e.threshold + b(d).width();
	};
	b.inviewport = function(c, d) {
		return ! b.rightofscreen(c, d) && !b.leftofscreen(c, d) && !b.belowthefold(c, d) && !b.abovethetop(c, d);
	};
	b.extend(b.expr[":"], {
		"below-the-fold": function(c) {
			return b.belowthefold(c, {
				threshold: 0,
				container: a
			});
		},
		"above-the-top": function(c) {
			return ! b.belowthefold(c, {
				threshold: 0,
				container: a
			});
		},
		"right-of-screen": function(c) {
			return b.rightoffold(c, {
				threshold: 0,
				container: a
			});
		},
		"left-of-screen": function(c) {
			return ! b.rightoffold(c, {
				threshold: 0,
				container: a
			});
		},
		"in-viewport": function(c) {
			return ! b.inviewport(c, {
				threshold: 0,
				container: a
			});
		},
		"above-the-fold": function(c) {
			return ! b.belowthefold(c, {
				threshold: 0,
				container: a
			});
		},
		"right-of-fold": function(c) {
			return b.rightoffold(c, {
				threshold: 0,
				container: a
			});
		},
		"left-of-fold": function(c) {
			return ! b.rightoffold(c, {
				threshold: 0,
				container: a
			});
		}
	});
})(jQuery, window);
$(".album_guide_close").click(function() {
	alert("ddfdf");
	$(".album_guide_bg,.album_guide_img").hide();
	var b = $("#userId").val();
	var a = $("#newHandler").val();
	var c = new Date().getTime();
	var d = {
		staus: "00010",
		newHandler: a,
		userid: b,
		random: c
	};
	$.getJSON("web/updateHandlerStatus.htm", d,
	function(e) {});
});
$(window).load(function() {
	var a = $(".lvtu_pic_image_bg").offset().top;
	$("html,body").animate({
		scrollTop: a
	},1);
	return false;
});
function getAddressStr(c) {
	var a = "!";
	for (var b = 0; b < c.length; b++) {
		if (c[b].id != null) {
			a = a + c[b].id + "!";
		}
	}
	return a;
}
var picNumss = function(e) {
	var h = $(window);
	var d = h.width();
	var b = d - 60;
	var k = parseInt(b / 16);
	var g = $(".crumbN_inner").find("li").length;
	var a = k * 16;
	var c = (e + 1) * 16;
	var j = parseInt(c / a);
	var f = c % a;
	if (f > 0) {
		j++;
	}
	return j;
};
var arrowhead = function(a) {
	if (a == 1) {
		$(".carousel_previous").hide();
		$(".carousel_next").show();
	} else {
		$(".carousel_previous,.carousel_next").show();
	}
};
var perPageNum = function() {
	var b = $(window);
	var d = b.width();
	var a = d - 60;
	var c = parseInt(a / 16);
	return c;
};
var pageFlip = function(b) {
	var a = $("#currentDay").val();
	if (a != null && b == a) {
		return;
	}
	var c = perPageNum() * 16;
	var d = $("#crumbN_inner_id");
	if (b > a) {
		d.animate({
			left: "-=" + (b - a) * c
		}),
		"slow";
	} else {
		d.animate({
			left: "+=" + (a - b) * c
		}),
		"slow";
	}
	$("#currentDay").val(b);
	arrowhead(b);
};
var pageZero = function() {
	var a = $("#crumbN_inner_id");
	a.animate({
		left: 0
	},
	"slow");
};
function getCurrentPicId() {
	var a = window.location.href;
	var b = new Array();
	b = a.split("#");
	a = b[1];
	return a;
}
function descLength() {
	var b = "140";
	var a = false;
	var c = $.trim($(this).val()).length;
	if (c > b) {
		a = true;
	}
	return a;
}
function getScrollWidth() {
	var c, a, b = document.createElement("DIV");
	b.style.cssText = "position:absolute; top:-1000px; width:100px; height:100px; overflow:hidden;";
	c = document.body.appendChild(b).clientWidth;
	b.style.overflowY = "scroll";
	a = b.clientWidth;
	document.body.removeChild(b);
	return c - a;
}
function showPic() {
	var C = $(window);
	var l = $(".lvtu_pic_image");
	var B = C.width();
	var i = 0;
	var a = getScrollWidth();
	if ($.browser.msie) {
		i = C.height();
	} else {
		if ($.browser.opera) {
			i = C.height();
		} else {
			if ($.browser.mozilla) {
				i = C.height();
			} else {
				if ($.browser.safari) {
					i = C.height();
				}
			}
		}
	}
	var y = C.scrollTop();
	var m = $("#picwidth").val();
	var t = $("#picheight").val();
	var o = $(".lvtu_pic_image_bg").offset().top;
	var d = m / B;
	var D = t / i;
	var n = -1;
	if (d > 1) {
		if (D > 1) {
			if (d >= D) {
				m = m / d;
				t = t / d;
			} else {
				m = m / D;
				t = t / D;
			}
		} else {
			m = m / d;
			t = t / d;
		}
	} else {
		if (D > 1) {
			m = m / D;
			t = t / D;
		} else {
			m = m;
			t = t;
		}
	}
	var w = m / B;
	var u = t / i;
	if (w > 1 / 2) {
		if (u > 1 / 2) {
			n = 0;
		} else {
			n = 1;
		}
	} else {
		n = 2;
	}
	var e = 0;
	if (B > m) {
		e = (B - m) / 2;
	} else {
		e = (B - m) / 2;
	}
	var c = $(".lvtu_pic_image_prve").height();
	var h = $(".lvtu_pic_image_next").height();
	var v = $(".lvtu_pic_image_again").height();
	$(".lvtu_pic_image_prve").css("top", (i - c) / 2);
	$(".lvtu_pic_image_next").css("top", (i - h) / 2);
	$(".lvtu_pic_image_again").css("top", (i - v) / 2);
	var k = $(".special_next").height();
	$(".special_next").css("top", (i - k) / 2);
	var f = $(".special_list_prev").height();
	$(".special_list_prev").css("top", (620 - 88) / 2);
	$(".special_list_next").css("top", (620 - 88) / 2);
	var A = $(".ui-comment-boxt").width();
	var g = $(".ui-comment-boxt").height();
	var s = $(".ui-comment-boxtg").width();
	var q = $(".ui-comment-boxtg").height();
	var x = $(".lvtu_pic_comment_tips").width();
	$(".lvtu_pic_comment_tips").css("left", (B - x) / 2);
	$(".lvtu_pic_comment_tips_b").css("left", (B - x) / 2);
	if (n == 0) {
		var r = (i - t) / 2;
		l.css({
			left: e,
			top: parseFloat(r) + 40 + 'px',
			width: m,
			height: t - 40
		});
		
		$(".ui-comment-bg").show().css({
			width: A + 30,
			height: g + 30,
			left: 50,
			bottom: 130
		});
		$(".ui-comment-boxt").css({
			left: 65,
			bottom: 145
		});
		$(".J-comment-bg").show().css({
			width: s + 30,
			height: q + 30,
			left: 80,
			top: 80
		});
		$(".J-comment").css({
			left: 95,
			top: 95
		});
	} else {
		if (n == 1) {
			var r = (i - t * 2) / 2;
			$(".ui-comment-bg").hide();
			l.css({
				left: e,
				top: parseFloat(r) + 40 + 'px',
				width: m,
				height: (t * 2) - 40
			});
			var b = (m - A) / 2;
			var j = (t - g) / 2;
			var z = (m - s) / 2;
			var p = (t - q) / 2;
			$(".ui-comment-boxt").css({
				left: b,
				bottom: -(Number(t) - Number(j))
			});
			$(".J-pic").addClass("fn-pa").css("bottom", 0);
			$(".J-comment").css({
				left: z,
				top: Number(p) - Number(t)
			});
		} else {
			if (n == 2) {
				var r = (i - t) / 2;
				$(".ui-comment-bg").hide();
				if (m < 450) {
					m = 450;
					l.css({
						left: (B - 900) / 2,
						top: parseFloat(r) + 40 + 'px',
						width: m * 2,
						height: t - 40
					});
					
				} else {
					l.css({
						left: e - m / 2,
						top: parseFloat(r) + 40 + 'px',
						width: m * 2,
						height: t - 40
					});
					
				}
				var b = (m - A) / 2;
				var j = (t - g) / 2;
				var z = (m - s) / 2;
				var p = (t - q) / 2;
				$(".ui-comment-boxt").css({
					left: Number(b) + Number(m),
					bottom: j
				});
				$(".J-pic img").css("float", "right");
				$(".J-comment").css({
					left: z,
					top: p
				});
			}
		}
	}
	$('.lvtu_pic_image_num').find('a').css('margin-left',$('.lvtu_pic_image_bigpic').width()+$('.lvtu_pic_image_bigpic').offset().left - 100 + 'px');
	$(".lvtu_pic_image_bigpic img").css("height", t-40);
	$(".lvtu_pic_image_load").css({
		width: m,
		height: t - 40
	});
	$(".lvtu_pic_image_bg,.lvtu_pic_details").css("height", i);
	$(".lvtu_pic_comment").css("height", i);
	$(".J-next").click(function() {
		var E = $("#albumTotalNum").val();
		if (E > 0) {
			var F = $(".lvtu_pic_image_bg").offset().top;
			$("html,body").animate({
				scrollTop: F
			},
			1);
			$(".J-list").show();
			$(".J-list").css({
				left: B,
				width: B
			});
			$(".J-list").animate({
				left: 0
			},
			"slow");
			$(".J-list").trigger("scrollstop");
			$(".J-next").animate({
				right: B
			},
			"slow");
			$(".J-comment,.J-comment-bg").fadeOut("slow");
		}
	});
	$(".J-prve").click(function() {
		$(".J-comment").fadeIn("slow");
		$(".J-comment-bg").fadeTo("slow", 0.6);
		$(".J-list").show();
		var E = function() {
			$(".J-list").hide();
		};
		$(".J-list").animate({
			left: B
		},
		"slow", E);
		$(".J-next").animate({
			right: 0
		},
		"slow");
	});
	$(".J-next").hover(function() {
		$(this).removeClass("special_next");
		$(this).addClass("special_next_hover");
	},
	function() {
		$(this).removeClass("special_next_hover");
		$(this).addClass("special_next");
	});
	$(".special_list_item").css("left", (B - 1280) / 2);
	$(".special_list_item").css("top", (i - 680) / 2);
	return false;
}
$(function() {
	showPic();
	$(window).resize(function() {
		showPic();
	});
	$(".lvtu_special_service li a").click(function() {
		$(this).removeClass("service_link");
		$(this).addClass("service_down").parent().siblings().children().removeClass("service_down").addClass("service_link");
	});
	var oldName = $.trim($(".J-album-name").text());
	$("#albumSave").click(function() {
		var curLength = $.trim($("#fdesc textarea").val()).length;
		if (curLength > "140") {
			return false;
		}
		var curNameLength = $.trim($(".nest").val()).length;
		if (oldName != "无主题游记") {
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
		if (oldName == "无主题游记") {
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
							addressText = addressText + addressIdInit[k].name + "、";
						}
					}
					$.jBox.tip("游记编辑成功。", "success");
					var textMn = $(".nest").val();
					var textMs = $(".test").val();
					var textMe = $(".pest").val();
					var textDest = $(".dest").val();
					var pictype = $(".service_down").text();
					$(".J-destination").empty();
					$(".J-destination").text(addressText);
					$(".J-album-name").empty();
					if (oldName == "无主题游记") {
						$(".J-album-name").text(oldName);
					} else {
						$(".J-album-name").text(textMn);
					}
					$(".J-textarea").empty();
					$(".J-textarea").text(textMs);
					$(".J-place").empty();
					$(".J-place").text(textMe);
					$(".J-selected ul a").text(pictype);
					$(".J-selected").show();
					$(".J-select").hide();
					$(".J-input").hide();
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
	$(".J-edit").click(function() {
		var albumId = $("#albumId").val();
		var pidId = getCurrentPicId();
		var params = {
			albumId: albumId
		};
		// 获取游记列表 这东西 用来修改当前图片是归属哪个游记的。。
		$.getJSON(pathUpload + "?module=album&action=getAlbumNameList", params, function(data) {
			if (data.getANLResult == 0) {
				var feedback = "";
				$.each(data.picAlbumList,
				function(index, item) {
					var str = "";
					str += "<li id=" + +item.id + "><a>" + item.name + "</a></li>";
					feedback += str;
				});
				$("#albumList").empty().append(feedback);
				$(".J-picAlbum-list li").click(function() {
					var picAlbumT = $(this).text();
					var picAlbumId = $(this).attr("id");
					$("#albumIdNew").val(picAlbumId);
					$(".picst").text(picAlbumT);
					$(".J-picAlbum-list").hide();
				});
			} else {
				if (data.getANLResult == 8000) {
					window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href) + "#" + picId;
				} else {
					$.jBox.tip("获取游记列表失败。", "success");
				}
			}
		});
		$(".picAlbum_wp").css("width", "258px");
		$(".J-picAlbum-name").css("width", "230px");
		var textMn = $(".J-album-name").text();
		var textPn = $(".J-picAlbum-name").text();
		var textareaH = $(".J-textarea").height();
		var textMs = $(".J-textarea").text();
		var textMe = $(".J-place").text();
		var textDest = $(".J-destination").text();
		$(".J-textarea").empty();
		$(".J-place").empty();
		$(".J-destination").empty();
		if (textMn != "无主题游记") {
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
				$(".textarea_error_album").html("（<em class='orange'>" + eval(parseInt(albumNameL) + parseInt(num)) + "</em>字/" + albumNameL + "字，您已超过 <em class='orange'>" + num + "</em> 字）");
			} else {
				if (curLength == 0) {
					if ($.trim($(this).val()) == "") {} else {}
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
				$(".J-textarea-error").html("（<em class='orange'>" + eval(parseInt(worldlen) + parseInt(num)) + "</em>字/" + worldlen + "字，您已超过 <em class='orange'>" + num + "</em> 字）");
			} else {
				$(".J-textarea-error").hide();
				if (curLength == 0) {
					if ($.trim($(this).val()) == "") {
						$(".J-textarea-error").html("还可以输入<span>" + worldlen + "</span>个字");
					} else {
						$(".J-textarea-error").html("还可以输入<span>" + worldlen - 1 + "</span>个字");
					}
				} else {
					$(".J-textarea-error").hide();
					var num = worldlen - $.trim($(this).val()).length;
					$(".J-textarea-error").html("还可以输入<span>" + num + "</span>个字");
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
			$("#psAddressIdPic").tokenInput(pathUpload + "?module=album&action=searchPOI", {
				theme: "picdetail",
				tokenLimit: 1,
				prePopulate: [{
					id: paId,
					name: paName
				}]
			});
		} else {
			$("#psAddressIdPic").tokenInput(pathUpload + "?module=album&action=searchPOI", {
				theme: "picdetail",
				tokenLimit: 1
			});
		}
		$("#psAddressIdAlbum").tokenInput(pathUpload + "?module=album&action=searchDestin", {
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
	$(".lvtu_pic_image_next,.lvtu_pic_image_again,.lvtu_pic_image_prve").click(function() {
		var addressIdSt = $("#albumPicAddressId").val();
		if (addressIdSt != null && addressIdSt != "") {
			$(".J-place").empty();
			$(".J-place").text($("#albumPicAddressName").val());
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
	
	//cancel editor
	$(".J-cancel").click(function() {
		var addressIdSt = $("#albumPicAddressId").val();
		if (addressIdSt != null && addressIdSt != "") {
			$(".J-place").empty();
			$(".J-place").text($("#albumPicAddressName").val());
		} else {
			$("#faddressName").hide();
		}
		$(".picAlbum_wp").css("width", "360px");
		$(".J-picAlbum-name").css("width", "360px").removeClass("fn-omit");
		$(".J-picAlbum-list").hide();
		$(".J-picAlbum-describe").hide();
		$(".picAlbum_fips").hide();
		$(".J-picAlbum-name").empty();
		$(".J-picAlbum-name").append(jAlbumOldPicName);
		$(".picAlbum_wp").removeClass("picAlbum_border");
		$("#albumIdNew").val("");
		$(".J-album-name").empty();
		$(".J-album-name").text(jAlbumOldName);
		$(".J-textarea").text($(".J-textarea").text());
		$(".J-destination").empty();
		$(".J-destination").text(placeDest);
		$(".J-selected").show();
		$(".J-select").hide();
		$(".textarea_error_album").hide();
		$(".textarea_error_maddressName").hide();
		$(".J-textarea-error").hide();
		$(".J-input").hide();
	});
	$("#fdelAlbum").click(function() {
		/*var id = $("#albumId").val();
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
							window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href);
						} else {
							$.jBox.tip("删除专辑失败。", "success");
						}
					}
				});
			}
		};
		$.jBox.confirm("确定要删除此游记吗？", "旅图", submit, {
			buttons: {
				"确定": true,
				"取消": false
			}
		});*/
	});
	$(".J-cover").hover(function() {
		$(this).removeClass("cover");
		$(this).addClass("cover_hover");
	}, function() {
		$(this).removeClass("cover_hover");
		$(this).addClass("cover");
	});
	$(".J-edit").hover(function() {
		$(this).removeClass("edit");
		$(this).addClass("edit_hover");
	},function() {
		$(this).removeClass("edit_hover");
		$(this).addClass("edit");
	});
	$(".J-delete").hover(function() {
		$(this).removeClass("delete");
		$(this).addClass("delete_hover");
	},
	function() {
		$(this).removeClass("delete_hover");
		$(this).addClass("delete");
	});
	$(".J-big").hover(function() {
		$(this).removeClass("big");
		$(this).addClass("big_hover");
	},
	function() {
		$(this).removeClass("big_hover");
		$(this).addClass("big");
	});
	$("#praiseAction").hover(function() {
		$(this).removeClass("praise");
		$(this).addClass("praise_hover");
	},
	function() {
		var isPraised = $("#currIsPraised").val();
		if (isPraised != 1) {
			$(this).removeClass("praise_hover");
			$(this).addClass("praise");
		}
	});
	$(".J-comt").hover(function() {
		$(this).removeClass("comment");
		$(this).addClass("comment_hover");
	},
	function() {
		$(this).removeClass("comment_hover");
		$(this).addClass("comment");
	});
	$(".J-browse").hover(function() {
		$(this).removeClass("browse");
		$(this).addClass("browse_hover");
	},
	function() {
		$(this).removeClass("browse_hover");
		$(this).addClass("browse");
	});
	$(".J-message").hover(function() {
		$(this).removeClass("message");
		$(this).addClass("message_hover");
		$(".describet").show();
	},
	function() {
		$(this).removeClass("message_hover");
		$(this).addClass("message");
		$(".describet").hide();
	});
	$(".J-add").hover(function() {
		$(this).removeClass("add");
		$(this).addClass("add_hover");
	},
	function() {
		$(this).removeClass("add_hover");
		$(this).addClass("add");
	});
	$(".J-share").hover(function() {
		$(this).removeClass("share");
		$(this).addClass("share_hover");
		$(".describe_share").show();
	},
	function() {
		$(this).removeClass("share_hover");
		$(this).addClass("share");
		$(".describe_share").hide();
	});
	$(".lvtu_pic_shop li").hover(function() {
		$(this).find(".describe").show();
	},
	function() {
		$(this).find(".describe").hide();
	});
});
$(function() {
	$(".special_list_prev").hover(function() {
		$(this).stop().animate({
			left: -2
		},
		"fast");
	},
	function() {
		$(this).stop().animate({
			left: -10
		},
		"fast");
	});
	$(".special_list_next").hover(function() {
		$(this).stop().animate({
			right: -2
		},
		"fast");
	},
	function() {
		$(this).stop().animate({
			right: -10
		},
		"fast");
	});
	$(".special_content_list_img").hover(function() {
		$(this).find(".special_img_bg").stop().animate({
			bottom: "0px"
		});
	},
	function() {
		$(this).find(".special_img_bg").stop().animate({
			bottom: "-320px"
		});
	});
	$(".special_content_list li:nth-child(3n)").css("border-right", "0");
	var b = 1;
	var a = 6;
	$(".special_list_next").click(function() {
		var h = $(".lvtu_pic_image_bg").offset().top;
		$("html,body").animate({
			scrollTop: h
		},
		1);
		var f = $(this).parent(".special_content");
		var g = f.find(".special_content_list");
		var d = f.width();
		var c = g.find("li").length;
		var e = Math.ceil(c / a);
		g.width(d * e);
		if (!g.is(":animated")) {
			if (b == e) {
				g.animate({
					left: "0px"
				},
				"slow");
				b = 1;
			} else {
				g.animate({
					left: "-=" + d
				}),
				"slow";
				b++;
			}
		}
		$(this).trigger("scrollstop");
	});
	$(".special_list_prev").click(function() {
		var h = $(".lvtu_pic_image_bg").offset().top;
		$("html,body").animate({
			scrollTop: h
		},
		1);
		var f = $(this).parent(".special_content");
		var g = f.find(".special_content_list");
		var d = f.width();
		var c = g.find("li").length;
		var e = Math.ceil(c / a);
		g.width(d * e);
		if (!g.is(":animated")) {
			if (b == 1) {} else {
				g.animate({
					left: "+=" + d
				}),
				"slow";
				b--;
			}
		}
		$(this).trigger("scrollstop");
	});
	$(".J-album-name").hover(function() {
		$(this).addClass("t_hover");
	},
	function() {
		$(this).removeClass("t_hover");
	});
	$(".lvtu_footer").css("margin-top", "0px");
	$(".lvtu_top_line_b").remove();
});
$(".J-picAlbum-name,.J-picAlbum-fips").click(function() {
	$(".J-picAlbum-list").show();
});
$(document).mouseup(function(){
	$(".J-picAlbum-list").hide();								
});
$(document).ready(function() {
	var d = $(".J-picAlbum-name a");
	/*var f = 30; //现在我这里不需要上移一定距离了 by lwkai add
	$("html,body").animate({
		scrollTop: f
	},
	1);
	$(".J-tips_b").click(function() {
		$("html,body").animate({
			scrollTop: f
		},
		1000);
	});*/
	o();
	function o() {
		var I = $(window);
		var G = $(".pop_status");
		var F = I.width();
		var B = I.height();
		var H = I.scrollLeft();
		var E = I.scrollTop();
		var C = G.width();
		var K = G.height();
		var D = H + (F - C) / 2;
		var J = E + (B - K) / 2;
		G.css({
			left: D,
			top: J
		});
	}
	$(window).resize(function() {
		o();
	});
	$(".close").click(function() {
		$(".pop_status").hide();
		$(".bg_mask").hide();
		$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
	});
	var x = function(B) {
		var C = 0;
		if (!B) {
			B = window.event;
		}
		if (B.wheelDelta) {
			C = B.wheelDelta / 120;
		} else {
			if (B.detail) {
				C = -B.detail / 3;
			}
		}
		if (C) {
			y(C);
		}
		if (B.preventDefault) {
			B.preventDefault();
		}
		B.returnValue = false;
	};
	if ($.browser.msie) {
		if (document.attachEvent) {
			document.attachEvent("onmousewheel", x);
		}
	} else {
		if ($.browser.opera || $.browser.safari) {
			document.onmousewheel = x;
		} else {
			if ($.browser.mozilla) {
				if (window.addEventListener) {
					window.addEventListener("DOMMouseScroll", x, false);
				}
			}
		}
	}
	var y = function(B) {
		if (B < 0) {
			nextPics();
			return;
		} else {
			beforePics();
			return;
		}
	};
	$(document).keydown(function(B) {
		if (B.keyCode == 37) {
			beforePics();
		} else {
			if (B.keyCode == 39) {
				nextPics();
			} else {
				if (B.keyCode == 40) {
					nextPics();
				}
			}
		}
	});
	$(".J-comt").click(function() {
		var F = $("#visitorId").val();
		if (F == null || F == "") {
			var B = window.location.href;
			window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(B);
		} else {
			var C = window.location.href;
			var E = new Array();
			E = C.split("#");
			var D = E[1];
			$(".bg_mask").show();
			$(".pop_status").show();
			getCommentsByPicId(D, $(this));
		}
	});
	$(".J-tips").hover(function() {
		$(this).addClass("lvtu_pic_comment_tips_hover");
		$(this).removeClass("lvtu_pic_comment_tips");
	},
	function() {
		$(this).addClass("lvtu_pic_comment_tips");
		$(this).removeClass("lvtu_pic_comment_tips_hover");
	});
	$(".J-tips_b").hover(function() {
		$(this).addClass("lvtu_pic_comment_tips_b_hover");
		$(this).removeClass("lvtu_pic_comment_tips_b");
	},
	function() {
		$(this).addClass("lvtu_pic_comment_tips_b");
		$(this).removeClass("lvtu_pic_comment_tips_b_hover");
	});
	$(".J-comment-btn").bind({
		mouseover: function() {
			$(".J-comment-btn").addClass("comment_btn_out").removeClass("comment_btn comment_btn_down");
		},
		mouseout: function() {
			$(".J-comment-btn").addClass("comment_btn").removeClass("comment_btn_out comment_btn_down");
		},
		mousedown: function() {
			$(".J-comment-btn").addClass("comment_btn_down").removeClass("comment_btn comment_btn_out");
		}
	});
	suningImages().init();
	$(".lvtu_pic_image_prve").click(function() {
		beforePics();
	});
	$("#praiseAction").click(function() {
		praisePic();
	});
	var s = $("#fdesc").text();
	$("#feditConcel").click(function() {
		var B = $(".J-textarea").text();
		$(".J-textarea").text(s);
	});
	//完成图片编辑
	$("#feditConfirm").click(function() {
		var N = $.trim($("#fdesc textarea").val()).length;
		if (N > "140") {
			return false;
		}
		var B = window.location.href;
		var J = new Array();
		J = B.split("#");
		B = J[1];
		var M = $("#fdesc textarea").val();
		var E = $(".J-place").text();
		var O = $(".service_down").attr("name");
		var C = $("#albumIdNew").val();
		var L = $("#albumId").val();
		var I = $("#psAddressIdPic").tokenInput("get");
		var H;
		var K = "";
		var D = "";
		var F = "";
		if (I != null) {
			var G = new Date().getTime();
			if (I != "") {
				K = I[0].id;
				F = I[0].name;
			}
			H = {
				picId: B,
				desc: M,
				albumId: C,
				addressId: K,
				type: O,
				random: G
			};
			$.getJSON(pathUpload + "?module=album&action=editWebPic", H, function(S) {
				if (S.editstatus == 0) {
					s = M;
					if (I == "") {
						$("#albumPicAddressId").empty();
						$("#faddressName").hide();
						$("#albumPicAddressId").val("");
						$("#albumPicAddressName").val("");
					} else {
						$("#albumPicAddressId").val(K);
						$(".J-place").empty();
						//这里的URL是点幻灯图的拍摄地，需要跳到哪里去的连接
						//$(".J-place").append("<a href='web/sight.htm?sightId=" + K + "' title='" + F + "'>" + F + "</a>");
						$("#albumPicAddressId").val(K);
						$("#albumPicAddressName").val(F);
					}
					$.jBox.tip("图片编辑成功。", "success");
					$(".J-picAlbum-list").hide();
					var P = $(".picst").text();
					var R = $(".nest").val();
					var Z = $(".test").val();
					var T = $(".pest").val();
					var Y = $(".dest").val();
					var aa = $(".service_down").text();
					var V = parseInt($("#imgindex").val(), 10);
					var Q = $("#picPreList li[title=" + B + "]");
					textOldMs = M;
					if (K != null && F != null) {
						$("#albumPicAddressId").val(K);
						$("#albumPicAddressName").val(F);
					}
					$(Q).find("#desc").text(M);
					$(Q).find("#addressName").text(F);
					$(Q).find("#addressId").text(K);
					$(Q).find("#catagoryId").text(H.type);
					$("#currPicCategoryId").val(H.type);
					$("#albumIdNew").val("");
					$(".J-picAlbum-name").empty();
					$(".J-picAlbum-name").text(P);
					$(".J-album-name").empty();
					$(".J-album-name").text(R);
					$(".picAlbum_wp").css("width", "360px");
					$(".J-picAlbum-name").css("width", "360px").removeClass("fn-omit");
					$(".J-textarea").empty();
					$(".J-textarea").text(Z);
					$(".J-destination").empty();
					$(".J-destination").text(Y);
					$(".J-selected ul a").text(aa);
					$(".J-selected").show();
					$(".J-select").hide();
					$(".J-input").hide();
					$(".J-picAlbum-describe").hide();
					$(".picAlbum_fips").hide();
					$(".picAlbum_wp").removeClass("picAlbum_border");
				} else {
					if (S.editstatus == 6000) {
						var X = $("#scorollimg").find("#miniUrl");
						var U = X.length;
						if (U == 1) {
							var W = $("#albumId").val();
							window.location.href = pathUpload + "album/show/albumId--" + W + ".html";
						} else {
							suningImages().nextPic(B);
						}
					} else {
						if (S.editstatus == 8000) {
							window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href) + "#" + H.picId;
						} else {
							$.jBox.tip("编辑图片失败。", "error");
						}
					}
				}
			});
		}
	});
	$("#textlimit").bind("keyup paste keydown",	function() {
		var C = $.trim($("#textlimit").val()).length;
		if (C > 140) {
			var B = $.trim($("#textlimit").val()).length - 140;
			$("#prompt").html("已经超出<span style='color:#de2c28;font-size:12px;'>" + B + "</span>个字");
		} else {
			if (C == 0) {
				if ($.trim($("#textlimit").val()) == "") {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
				} else {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>139</span>个字");
				}
			} else {
				var B = 140 - $.trim($("#textlimit").val()).length;
				$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + B + "</span>个字");
			}
		}
	});
	$(".lvtu_pic_image_next").click(function() {
		nextPics();
	});
	$(".lvtu_pic_image_again").click(function() {
		$(".J-input").hide();
		$(".J-select").hide();
		$(".J-selected").show();
		$("#faddressName").hide();
		var E = $(".lvtu_pic_image_bg").offset().top;
		$("html,body").animate({
			scrollTop: E
		},
		1);
		var C = $("#scorollimg").find("#miniUrl");
		var B = C.length;
		var D = parseInt($("#imgindex").val(), 10);
		D++;
		if (D >= B) {
			if (B == 2 && D == 2 || B == 1) {
				return;
			} else {
				D = 0;
			}
		}
		suningImages().next(D);
		pageZero();
	});
	$("#textlimit").bind("keyup paste keydown",	function() {
		var C = $.trim($("#textlimit").val()).length;
		if (C > 140) {
			var B = $.trim($("#textlimit").val()).length - 140;
			$("#prompt").html("已经超出<span style='color:#de2c28;font-size:12px;'>" + B + "</span>个字");
		} else {
			if (C == 0) {
				if ($.trim($("#textlimit").val()) == "") {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
				} else {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>139</span>个字");
				}
			} else {
				var B = 140 - $.trim($("#textlimit").val()).length;
				$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + B + "</span>个字");
			}
		}
	});
	$("#fdel").click(function() {
		var B = function(C, E, H) {
			if (C == "ok") {
				var D = window.location.href;
				var G = new Array();
				G = D.split("#");
				D = G[1];
				var F = new Date().getTime();
				var I = {
					picId: D,
					extcs: getExtcsValue(),
					target : '1',
					random: F
				};
				$.getJSON(pathUpload + "?module=album&action=deleteWebPic", I,	function(N) {
					if (N.delstatus == 0) {
						$.jBox.tip("图片删除成功。", "success");
						var K = $("#scorollimg").find("#miniUrl");
						var J = K.length;
						if (J == 1) {
							var M = $("#albumId").val();
							window.location.href = pathUpload + "album/show/albumId--" + M + ".html";
						} else {
							var L = parseInt($("#imgindex").val(), 10);
							L++;
							if (L >= J) {
								if (J == 2 && L == 2 || J == 1) {
									L = 1;
								} else {
									L = 1;
								}
							}
							suningImages().delByPicId(D);
						}
					} else {
						if (N.delstatus == 8000) {
							window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href) + "#" + I.picId;
						} else {
							$.jBox.tip("删除图片失败。", "success");
						}
					}
				});
			} else {
				if (C == "cancel") {}
			}
			return true;
		};
		$.jBox.confirm("确定删除该图片？", "旅图", B);
	});
	// 设置为封面
	$("#fcover").click(function() {
		var F = $("#albumId").val();
		var B = window.location.href;
		var D = new Array();
		D = B.split("#");
		B = D[1];
		var C = new Date().getTime();
		var E = {
			picId: B,
			albumId: F,
			random: C
		};
		$.getJSON(pathUpload + "?module=album&action=saveCover", E,
		function(G) {
			if (G.code == 200) {
				$.jBox.tip("设置游记封面成功", "success");
			} else {
				if (G.code == 8000) {
					window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href);
				} else {
					$.jBox.tip("设置游记封面失败", "error");
				}
			}
		});
	});
	var c = window.location.href;
	var p = new Array();
	p = c.split("=");
	var a = GetQueryString("thirdCompanyType");
	if (a != undefined && a != null || p.length == 2) {
		var r;
		var q;
		var A = c.split("#");
		r = GetQueryString("picId");
		if (A[1] != null) {
			q = A[1];
		} else {
			q = r;
		}
		if (r != null && q != null) {
			if (r != q) {
				var k = true;
				var w = perPageNum();
				var u = $(".crumbN .crumbN_visible .viewport .crumbN_inner ul");
				var l = u.find("li[did=" + r + "]").index() + 1;
				var h = u.find("li[did=" + q + "]").index() + 1;
				var m = h % w;
				var t = parseInt(h / w);
				var v = l % w;
				var z = parseInt(l / w);
				if ((t == z) && (Math.abs(m - v) < w)) {
					k = false;
				}
				if (k) {
					if (l > h) {
						if (m == 0) {
							pageFlip(t);
						} else {
							pageFlip(--t);
						}
					} else {
						if (m == 0) {
							pageFlip(t);
						} else {
							pageFlip(t);
						}
					}
				}
				$(".J-input").hide();
				$(".J-select").hide();
				$(".J-selected").show();
				$("#faddressName").hide();
				var f = $(".lvtu_pic_image_bg").offset().top;
				$("html,body").animate({
					scrollTop: f
				},
				1);
				var i = $("#picPreList li[title=" + q + "]");
				var g = parseInt($(i).find("#index").text(), 10);
				suningImages().next(g);
				return false;
			}
		}
	} else {
		if (p.length == 3) {
			var n = p[1].split("&");
			var j = p[2].split("#");
			var b = n[0];
			var e = j[1];
			if (b != null && e != null) {
				if (b != e) {
					$(".J-input").hide();
					$(".J-select").hide();
					$(".J-selected").show();
					$("#faddressName").hide();
					var f = $(".lvtu_pic_image_bg").offset().top;
					$("html,body").animate({
						scrollTop: f
					},
					1);
					var i = $("#picPreList li[title=" + e + "] span");
					var g = parseInt(i[7].innerText, 10);
					suningImages().next(g);
					return false;
				}
			}
		}
	}
});
function loadImage(b) {
	if (b == null) {
		return;
	}
	var a = new Image();
	a.src = b;
	if (a.complete) {
		return;
	}
	a.onload = function() {};
}
//next pic
function nextPics() {
	var f = perPageNum();
	var e = $(".crumbN_inner").find(".active").index() + 1;
	var h = e % f;
	var c = parseInt(e / f);
	if (h == 0) {
		pageFlip(++c);
	}
	$(".J-cancel").click();
	$(".J-input").hide();
	$(".J-select").hide();
	$(".J-selected").show();
	var g = $(".lvtu_pic_image_bg").offset().top;
	$("html,body").animate({
		scrollTop: g
	},
	1);
	var b = $("#scorollimg").find("#miniUrl");
	var a = b.length;
	var d = parseInt($("#imgindex").val(), 10);
	d++;
	if (d >= a) {
		if (a == 2 && d == 2 || a == 1) {
			return;
		} else {
			d = 0;
		}
	}
	suningImages().next(d);
}
//preview pic
function beforePics() {
	var f = perPageNum();
	var e = $(".crumbN_inner").find(".active").index() + 1;
	var h = e % f;
	var c = parseInt(e / f);
	if (h == 1 && c == 0) {
		return;
	}
	if (h == 1) {
		pageFlip(c);
	}
	$(".J-cancel").click();
	$(".J-input").hide();
	$(".J-select").hide();
	$(".J-selected").show();
	var g = $(".lvtu_pic_image_bg").offset().top;
	$("html,body").animate({
		scrollTop: g
	},
	1);
	var b = $("#scorollimg").find("#miniUrl");
	var a = b.length;
	var d = parseInt($("#imgindex").val(), 10);
	d--;
	if (d < 0) {
		if (a == 2 && d == -1 || a == 1) {
			return;
		} else {
			d = a - 1;
		}
	}
	suningImages().prev(d);
}
function imageCache(b, d, a, c) {
	var h;
	var g;
	var f;
	var e;
	if (d == 2) {
		if (b == 1) {
			h = a.eq(2).attr("href");
			loadImage(h);
		} else {
			h = a.eq(1).attr("href");
		}
		loadImage(h);
		return false;
	} else {
		if (d == 3) {
			if (b == 1) {
				g = a.eq(2).attr("href");
				f = a.eq(3).attr("href");
			} else {
				if (b == 2) {
					g = a.eq(1).attr("href");
					f = a.eq(3).attr("href");
				} else {
					if (b == 3) {
						g = a.eq(b - 1).attr("href");
						f = a.eq(b - 2).attr("href");
					}
				}
			}
			loadImage(g);
			loadImage(f);
			return false;
		}
	}
	if (b == 1) {
		g = a.eq(2).attr("href");
		f = a.eq(3).attr("href");
		e = a.eq(4).attr("href");
	} else {
		if (b == 2) {
			g = a.eq(1).attr("href");
			f = a.eq(3).attr("href");
			e = a.eq(4).attr("href");
		} else {
			if (b + 1 == d) {
				g = a.eq(b + 1).attr("href");
				f = a.eq(b - 1).attr("href");
				e = a.eq(b - 2).attr("href");
			} else {
				if (b + 2 == d) {
					g = a.eq(b - 1).attr("href");
					f = a.eq(b + 1).attr("href");
					e = a.eq(b + 2).attr("href");
				} else {
					if (b == d) {
						g = a.eq(b - 1).attr("href");
						f = a.eq(b - 2).attr("href");
						e = a.eq(b - 3).attr("href");
					} else {
						if (c == 1) {
							g = a.eq(b - 1).attr("href");
							f = a.eq(b + 1).attr("href");
							e = a.eq(b + 2).attr("href");
						} else {
							if (c == 2) {
								g = a.eq(b - 1).attr("href");
								f = a.eq(b - 2).attr("href");
								e = a.eq(b + 1).attr("href");
							}
						}
					}
				}
			}
		}
	}
	loadImage(g);
	loadImage(f);
	loadImage(e);
	return false;
}
function setPoint(b, a, c) {
	if (c) {
		a.eq(b - 1).addClass("active");
	} else {
		a.eq(b - 1).addClass("active").siblings().removeClass("active");
	}
	return false;
}
var picNums = function(e) {
	var h = $(window);
	var d = h.width();
	var b = d - 60;
	var k = parseInt(b / 16);
	var g = $(".crumbN_inner").find("li").length;
	var a = k * 16;
	var c = (e + 1) * 16;
	var j = parseInt(c / a);
	var f = c % a;
	if (f > 0) {
		j = j + 1;
	}
	return j;
};
function pointGet(d) {
	var h = picNums(d);
	var c = $(window);
	var g = c.width();
	var b = g - 60;
	var e = parseInt(b / 16);
	var f = e * 16;
	if (h != 0) {
		var a = (h - 1) * f;
		$(".crumbN_inner").animate({left: "-=" + a}),"slow";
	}
}
function preOrNextChange(a, b) {
	if (a == b) {
		$(".lvtu_pic_image_prve").removeClass("fn-none");
		$(".lvtu_pic_image_again").removeClass("fn-none");
		$(".lvtu_pic_image_next").addClass("fn-none");
	} else {
		if (a == 0) {
			$(".lvtu_pic_image_prve").addClass("fn-none");
			$(".lvtu_pic_image_again").addClass("fn-none");
			$(".lvtu_pic_image_next").removeClass("fn-none");
		} else {
			$(".lvtu_pic_image_prve").removeClass("fn-none");
			$(".lvtu_pic_image_again").addClass("fn-none");
			$(".lvtu_pic_image_next").removeClass("fn-none");
		}
	}
	return false;
}
var suningImages = function() {
	var K = $(".crumbN .crumbN_visible .viewport .crumbN_inner ul");
	var M = K.find("li");
	var i = $("#bigpics");
	var e = $("#pics");
	var N = e.find("li");
	var t = $(".J-big");
	var f = $("#fspraiseNums");
	var H = $("#scorollimg").find("#praiseNum");
	var Z = $("#scorollimg").find("#isPraised");
	var C = $("#fcommentNums");
	var j = $("#scorollimg").find("#commentNum");
	var J = $("#fbrowserNums");
	var o = $("#scorollimg").find("#browserNum");
	var S = $("#fcreateTime");
	var x = $("#scorollimg").find("#createTime");
	var W = $("#fdesc");
	var p = $("#scorollimg").find("#desc");
	var n = $("#picwidth");
	var b = $("#picheight");
	var U = $("#scorollimg").find("#activityId");
	var q = $("#scorollimg").find("#activityName");
	var G = $("#factivity");
	var T = $("#fpicId");
	var z = $("#scorollimg").find("#picId");
	var Y = $("#faddressName");
	var aa = $("#scorollimg").find("#addressName");
	var A = $("#scorollimg").find("#addressId");
	var ac = $("#scorollimg").find("#opentype");
	var F = $("#fuserId");
	var I = $("#scorollimg").find("#picUserIds");
	var ad = $("#fuserNames");
	var V = $("#scorollimg").find("#nickName");
	var L = $("#scorollimg").find("#imageUrl");
	var l = $("#scorollimg").find("#gender");
	var D = $("#scorollimg").find("#isDaren");
	var O = e.find("ul");
	var v = $("#scorollimg").find("#miniUrl");
	var k = $("#scorollimg").find("a");
	var E = $("#scorollimg").find("#picUrl");
	var X = $("#scorollimg").find("#picWidth");
	var P = $("#scorollimg").find("#picLength");
	var s = v.length;
	var a = $("#scorollimg").find("#catagoryId");
	var w = $("#scorollimg").find("#cameraModelL");
	var h = $("#scorollimg").find("#cameraTimeL");
	var c = $("#scorollimg").find("#cameraIsoL");
	var ab = $("#scorollimg").find("#fnumberL");
	var u = $("#scorollimg").find("#exposureTimeL");
	var r = $("#scorollimg").find("#exposureBiasL");
	var R = $("#scorollimg").find("#focalLengthL");
	var B = $("#scorollimg").find("#contentSina");
	var Q = $("#scorollimg").find("#contentQqWeibo");
	var m = $("#scorollimg").find("#contentQzone");
	var y = $("#scorollimg").find("#contentRenRen");
	var d = $("#scorollimg").find("#contentDou");
	var g = $("#scorollimg").find("#picUrlInfoById");
	var href = $("#scorollimg").find("#addressHref");
	return {
		init: function() {
			var ae = parseInt($("#imgindex").val(), 10) + 1;
			$("#photoinfo").html(ae + "/" + s.toString());
			preOrNextChange(ae - 1, s - 1);
			setPoint(ae, M, true);
			imageCache(ae - 1, s, k, 1);
			pointGet(ae - 1);
		},
		loadimg: function(aS, au) {
			var aJ = $("#pic");
			var aq = v.length;
			imageCache(aS, aq, k, au);
			var am = k.eq(aS).attr("href");
			aJ.attr("src", am);
			aJ.attr("original", am);
			$("#currentPicUrl").val(am);
			if ($.browser.msie) {
				if ($.browser.version > 8) {
					aJ.hide();
					aJ.load(function() {
						aJ.fadeIn(300);
					});
				} else {
					if ($.browser.version == 6) {
						aJ.load(function() {
							aJ.hide();
							aJ.fadeIn(300);
						});
					} else {
						aJ.hide();
						aJ.load(function() {
							aJ.fadeIn(300);
						});
					}
				}
			} else {
				aJ.hide();
				aJ.load(function() {
					aJ.fadeIn(300);
				});
			}
			var ah = z.eq(aS).text();
			var aG = H.eq(aS).text();
			var aX = Z.eq(aS).text();
			var aT = j.eq(aS).text();
			var ae = o.eq(aS).text();
			var aj = x.eq(aS).text();
			var aN = p.eq(aS).text();
			var az = U.eq(aS).text();
			var aI = q.eq(aS).text();
			var aV = aa.eq(aS).text();
			var aW = ac.eq(aS).text();
			var ao = I.eq(aS).text();
			var aC = A.eq(aS).text();
			var aQ = V.eq(aS).text();
			var aD = L.eq(aS).text();
			var aU = l.eq(aS).text();
			var ax = D.eq(aS).text();
			var af = E.eq(aS).text();
			var aO = a.eq(aS).text();
			var aE = w.eq(aS).text();
			var aM = h.eq(aS).text();
			var aP = c.eq(aS).text();
			var ak = ab.eq(aS).text();
			var av = u.eq(aS).text();
			var aY = r.eq(aS).text();
			var ay = R.eq(aS).text();
			var aL = B.eq(aS).text();
			var ap = Q.eq(aS).text();
			var aR = m.eq(aS).text();
			var aw = y.eq(aS).text();
			var aB = d.eq(aS).text();
			var aF = g.eq(aS).text();
			$("#cameraModel").text(aE);
			$("#cameraTime").text(aM);
			$("#cameraIso").text(aP);
			$("#fnumber").text(ak);
			$("#exposureTime").text(av);
			$("#exposureBias").text(aY);
			$("#focalLength").text(ay);
			$("#faddressName").show();
			$("#currPicCategoryId").val(aO);
			var aA = X.eq(aS).text();
			var al = P.eq(aS).text();
			$("#albumPicAddressId").val(aC);
			$("#albumPicAddressName").val(aV);
			t.attr("href", af);
			C.text("评论数：" + aT);
			J.text("浏览数：" + ae);
			S.text(aj);
			W.text(aN);
			n.attr("value", aA);
			b.attr("value", al);
			var at = ao;
			newnickName = aQ;
			if (aW != 2 && aV != "" && aV != null && aC != null) {
				//Y.html('<span class="J-place span2 fn-fr"><a href="web/sight.htm?sightId=' + aC + '" title=' + aV + ">" + aV + '</a></span><span class="span1 pl20 fn-fr png24">拍摄地：</span>');
				var url = href.eq(aS).text();
				Y.html('<span class="span1 fn-fl png24">拍摄地:</span><span class="J-place span2 "><a href="' + url + '" title="' + aV + '" targete="_blank">' + aV + '</a></span>');
				document.title = aV + "-图片by" + newnickName + "-旅图-走四方网www.usitrip.com";
			} else {
				$("#faddressName").hide();
				var aK = $("#albumName").val();
				document.title = aK + "-图片by" + newnickName + "-旅图-走四方网www.usitrip.com";
			}
			newimageUrl = aD;
			$("#currIsPraised").val(aX);
			$(".J-selected ul a").text($("#" + aO).val());
			if (aX == 1) {
				f.text("已喜欢：" + aG);
				$("#praiseAction").removeClass("praise");
				$("#praiseAction").addClass("praise_hover");
			} else {
				f.text("喜欢数：" + aG);
				$("#praiseAction").removeClass("praise_hover");
				$("#praiseAction").addClass("praise");
			}
			if (aU == 1) {
				$("#fgender").removeClass("ui-userSidebar-female").addClass("ui-userSidebar-male");
			} else {
				$("#fgender").removeClass("ui-userSidebar-male").addClass("ui-userSidebar-female");
			}
			var ag = window.location.href;
			var ai = ag.substring(ag.indexOf("#") + 1);
			var an = ag.substring(0, ag.indexOf("=") + 1) + ai;
			linkurl = encodeURIComponent(an);
			$("#contentSinaN").val("http://service.weibo.com/share/share.php?c=share&a=index&appkey=&title=" + aL + "&pic=" + am);
			$("#contentQqWeiboN").val("http://v.t.qq.com/share/share.php?url=" + linkurl + "&title=" + ap + "&pic=" + am);
			$("#contentQzoneN").val("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + linkurl + "&title=" + aR + "&pics=" + am);
			$("#renrenN").val("http://widget.renren.com/dialog/share?resourceUrl=" + linkurl + "&title=" + aK + "&content=" + aw + "&pic=" + am);
			$("#doubanN").val("http://www.douban.com/recommend/?url=" + linkurl + "&title=" + aB + "&comment=");
			$("#picUrlInfo").val(aF);
			var ar = new Array();
			ar = ag.split("#");
			ag = ar[0] + "#" + ah;
			window.location.href = ag;
			ad.html('——————<a class="ml15 mr15" href="' + pathUpload + 'album/userInfo/userId--' + at + '.html" title=' + newnickName + ">" + newnickName + "</a>——————");
			if (newimageUrl == null || newimageUrl == "undefined" || newimageUrl == "") {
				F.html('<a href="' + pathUpload + 'album/userInfo/userId--' + at + '.html" title=' + newnickName + '><img src= "' + lwkai.imageUrl + '/lvtu_userhead.gif" alt= ' + newnickName + '  width="70" height="70" /></a>');
			} else {
				F.html('<a href="' + pathUpload + 'album/userInfo/userId--' + at + '.html" title=' + newnickName + "><img src= " + newimageUrl + " alt= " + newnickName + '  width="70" height="70" /></a>');
			}
			newactivityId = az;
			newactivityName = aI;
			var aH = $("#visitorId").val();
			if (aH != undefined) {
				if (aH != null && at != null && aH == at) {
					$("#fedit").removeClass("fn-none");
					$("#fdel").removeClass("fn-none");
				} else {
					$("#fedit").addClass("fn-none");
					$("#fdel").addClass("fn-none");
				}
			} else {
				$("#fedit").addClass("fn-none");
				$("#fdel").addClass("fn-none");
			}
			$("#isFirstCome").val(1);
			$("#currPicId").val(ah);
			$("#loginLine a:eq(1)").attr("href", lwkai.loginUrl + "?ret=" + ag);
			preOrNextChange(aS, aq - 1);
			showPic();
		},
		nextPic: function(ai) {
			var ah = this;
			var ag;
			var ak = $("#picPreList li[title=" + ai + "]");
			var aj = ak.index();
			var ae = parseInt(aj, 10) + 1;
			var af = $("#picPreList li").length;
			var ak = $("#picPreList li[title=" + ai + "]");
			var aj = ak.index();
			var ae = parseInt(aj, 10) + 1;
			if (af == ae) {
				ag = 0;
			} else {
				ag = ae;
			}
			var ai = z.eq(ag).text();
			if (ai != null) {
				window.location.href = pathUpload + "album/picWebDetail/picId--" + ai + ".html#" + ai;
			}
		},
		addbk: function(ae) {
			$("#photoinfo").html((ae + 1).toString() + "/" + s.toString());
			setPoint(ae + 1, M, false);
		},
		next: function(ae) {
			var af = this;
			$("#imgindex").val((ae).toString());
			af.addbk(ae);
			setTimeout(function() {
				af.loadimg(ae, 1);
			},
			0);
		},
		prev: function(ae) {
			var af = this;
			$("#imgindex").val((ae).toString());
			af.addbk(ae);
			setTimeout(function() {
				af.loadimg(ae, 2);
			},
			0);
		},
		delByPicId: function(aj) {
			var ah = this;
			var an = $("#picPreList li").length;
			var am = $("#picPreList li[title=" + aj + "]");
			var ae = am.index();
			var af = parseInt(ae, 10) + 1;
			am.remove();
			var ak = $("#picPreList li").length;
			var al = K.find("li[did=" + aj + "]");
			al.remove();
			var ai = $(".crumbN .crumbN_visible .viewport .crumbN_inner ul");
			var ag = ai.find("li");
			if (an == ae + 1) {
				$("#photoinfo").html("1/" + ak.toString());
				$("#imgindex").val(1);
				setPoint(1, ag);
				setTimeout(function() {
					ah.loadimg(0, 0);
				},
				0);
			} else {
				$("#photoinfo").html((af).toString() + "/" + ak.toString());
				$("#imgindex").val((af - 1).toString());
				if (af == 1) {
					setPoint(af, ag, true);
				} else {
					setPoint(af, ag, false);
				}
				setTimeout(function() {
					ah.loadimg(af, 0);
				},
				0);
			}
		}
	};
};
var objComment;
function getCommentsByPicId(e, b) {
	objComment = b;
	var d = 0;
	var g = $("#visitorId").val();
	var a = new Date();
	var c = a.getTime();
	var f = {
		visitorId: g,
		picId: e,
		random: c,
		target: '1'
	};
	$.getJSON(pathUpload + "?module=album&action=getCommentNum", f, function(h) {
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
			}
		});
	});
}
function fillPage(b) {
	var a = "";
	$.each(b.comments,
	function(c, d) {
		var e = "";
		e += "<li class='userName'><a href='" + pathUpload + "album/userInfo/userId--" + d.userInfo.userId + ".html'>" + d.userInfo.nickName + "</a>： " + d.content + "<em class='darkgray999 ml5'>" + d.createTime + "</em>";
		a += e;
	});
	$("#feedback").empty().append(a);
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
	});
}
function comment() {
	var c = $("#textlimit").val();
	if ($.trim(c).length == 0) {
		$.jBox.tip("您还没有任何输入", "warning");
		return true;
	}
	if ($.trim(c).length > 140) {
		$.jBox.tip("你输入字数过多", "warning");
		return true;
	}
	var a = new Date();
	var b = a.getTime();
	var d = {
		picId: picId,
		content: c,
		targetType: 1,
		extcs: getExtcsValue(),
		random: b
	};
	$("#submit").attr({
		disabled: "disabled"
	});
	$.jBox.tip("正在提交...", "loading");
	$.ajax({
		type: "POST",
		url: pathUpload + "?module=album&action=comment",
		data: d,
		dataType: "json",
		success: function(f) {
			if (f.result) {
				$.jBox.tip("评论成功", "success");
				$("#textlimit").val("");
				$("#prompt").text("还可以输入140个字");
				$("#submit").removeAttr("disabled");
				$(".bg_mask").hide();
				$(".pop_status").hide();
				var g = objComment.find(".describe").text();
				var e = g.split("：");
				e = parseInt(e[1]) + 1;
				objComment.empty();
				objComment.append('<span id="fcommentNums" class="describe fn-pa white pl10 fn-none png24" style="display: none; ">评论数：' + e + "</span>");
			} else {
				if (!f.result) {
					window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href) + "#" + d.picId;
				} else {
					$("#submit").removeAttr("disabled");
					$.jBox.tip("评论失败", "error");
				}
			}
		},
		error: function(e) {
			$("#submit").removeAttr("disabled");
			$.jBox.tip("评论失败", "error");
		}
	});
}
function sharePic(b) {
	/*var a = window.location.href;
	var d = new Array();
	d = a.split("#");
	var f = d[1];
	var c = new Date().getTime();
	var e = {
		type: b,
		picId: f,
		random: c
	};
	$.ajaxSettings.async = false;
	$.getJSON(pathUpload + "web/getShareContent.htm", e,
	function(m) {
		var o = m.shareContent;
		if (o != "") {
			var i = null;
			var n = $("#currentPicUrl").val();
			if (b == 2) {
				var p = m.urlInfo;
				i = "http://service.weibo.com/share/share.php?c=share&a=index&appkey=&title=" + encodeURIComponent(o) + "&pic=" + encodeURIComponent(n) + "&url=" + encodeURIComponent(p);
			} else {
				if (b == 3) {
					var p = m.urlInfo;
					i = "http://v.t.qq.com/share/share.php?url=" + encodeURIComponent(p) + "&title=" + encodeURIComponent(o) + "&pic=" + encodeURIComponent(n);
				} else {
					if (b == 7) {
						var p = m.urlInfo;
						var q = $("#albumName").val();
						var k = $("#metaDesc").val();
						i = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + encodeURIComponent(p) + "&summary=" + k + "&title=" + encodeURIComponent(q) + "&pics=" + encodeURIComponent(n);
					} else {
						if (b == 9) {
							var p = m.urlInfo;
							var q = $("#albumName").val();
							var k = $("#metaDesc").val();
							i = "http://widget.renren.com/dialog/share?link=" + encodeURIComponent(p) + "&title=" + encodeURIComponent(q) + "&description=" + encodeURIComponent(k) + "&pic=" + encodeURIComponent(n);
						} else {
							if (b == 6) {
								var g = document.URL;
								i = "http://www.douban.com/recommend/?url=" + encodeURIComponent(g) + "&title=" + encodeURIComponent(o) + "&image=" + encodeURIComponent(n) + "&v=1";
							}
						}
					}
				}
			}
			if (i != null) {
				var g = window.location.href;
				var h = g.substring(g.indexOf("#") + 1);
				var j = new Date().getTime();
				var l = {
					shareTarget: b,
					type: "record",
					id: h,
					url: g,
					random: j
				};
				$.getJSON(pathUpload + "web/shareLog.htm", l,
				function(r) {});
				window.open(i);
			}
		}
	});*/
}
$(function() {
	$(".crumbN").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	var d = $(window);
	var g = d.width();
	var b = d.height();
	var f = $(".crumbN_inner").find(".active").index();
	var e = picNums(f);
	$("#currentDay").val(e);
	$(".crumbNshow").height(b);
	c();
	function c() {
		var p = $(window);
		var m = p.width();
		var h = p.height();
		var q = $(".crumbN_inner").find("li").length;
		var j = m - 60;
		var i = parseInt(j / 16);
		var l = i * 16;
		$(".crumbN_inner").width(q * 16);
		$(".viewport").width(l);
		var k = q * 16;
		var o = l;
		if (k > o) {
			var n = $("#currentDay").val();
			if (n == 1) {
				$(".carousel_previous").hide();
				$(".carousel_next").show();
			} else {
				$(".carousel_previous,.carousel_next").show();
			}
		} else {
			$(".carousel_previous,.carousel_next").hide();
		}
		$(".crumbN_inner li").hover(function() {
			var w = $(this).parent().find("li").length;
			var s = m - 60;
			var z = parseInt(s / 16);
			var v = z * 16;
			var u = w * 16;
			var A = Math.ceil(u / s);
			var r = $(".crumbN_inner li").index(this);
			var t = (m - l) / 2;
			if (l > q * 16) {
				var y = (l - q * 16) / 2;
			} else {
				var y = 0;
			}
			var x = $("#currentDay").val();
			if (x == 1) {
				$(this).find(".title").addClass("thumb_fixed").css({
					left: r * 16 + Number(t) - 90 + Number(y),
					top: h - 20
				});
				$(this).find(".thumb").addClass("thumb_fixed").css({
					left: r * 16 + Number(t) - 40 + Number(y),
					top: h - 110
				});
			} else {
				if (r >= z) {
					$(this).find(".title").addClass("thumb_fixed").css({
						left: r * 16 - z * 16 * (x - 1) + Number(t) - 90 + Number(y),
						top: h - 20
					});
					$(this).find(".thumb").addClass("thumb_fixed").css({
						left: r * 16 - z * 16 * (x - 1) + Number(t) - 40 + Number(y),
						top: h - 110
					});
				} else {
					$(this).find(".title").addClass("thumb_fixed").css({
						left: r * 16 + Number(t) - 90 + Number(y),
						top: h - 20
					});
					$(this).find(".thumb").addClass("thumb_fixed").css({
						left: r * 16 + Number(t) - 40 + Number(y),
						top: h - 110
					});
				}
			}
			$(this).trigger("scrollstop");
		},
		function() {
			$(this).trigger("scrollstop");
			$(this).find(".title").removeClass("thumb_fixed");
			$(this).find(".thumb").removeClass("thumb_fixed");
		});
	}
	$(window).resize(function() {
		c();
	});
	var a = function() {
		var n = $("#crumbN_inner_id");
		var h = n.find("li").length;
		var i = g - 60;
		var j = parseInt(i / 16);
		var m = h * 16;
		n.width(m);
		var l = Math.ceil(m / i);
		var k = j * 16;
		$(".carousel_previous").show();
		if (!n.is(":animated")) {
			if (e == l) {
				n.animate({
					left: "0px"
				},
				"slow");
				e = 1;
				if ($(".crumbN_inner li:first").hasClass("active")) {
					$(".carousel_previous").hide();
					$(".carousel_next").show();
				} else {
					$(".carousel_previous").show();
					$(".carousel_next").show();
				}
			} else {
				n.animate({
					left: "-=" + k
				}),
				"slow";
				e++;
			}
		}
		$("#currentDay").val(e);
		arrowhead(e);
	};
	$(".carousel_next").click(function() {
		a();
	});
	$(".carousel_previous").click(function() {
		var m = $(this).parent(".crumbN_visible");
		var o = m.find(".crumbN_inner");
		var h = o.find("li").length;
		var i = g - 60;
		var j = parseInt(i / 16);
		var n = h * 16;
		o.width(n);
		var l = Math.ceil(n / i);
		var k = j * 16;
		if (!o.is(":animated")) {
			if (e == 1) {
				o.animate({
					left: "-=" + k * (l - 1)
				},
				"slow");
				e = l;
			} else {
				o.animate({
					left: "+=" + k
				}),
				"slow";
				e--;
			}
		}
		$("#currentDay").val(e);
		arrowhead(e);
	});
});
$(document).ready(function() {
	var a = $("#visitorId").val();
	if (a == null) {
		$("#textlimit").attr({
			disabled: "disabled"
		});
		$("#textlimit").css({
			background: "url(" + lwkai.imageUrl + "lvtu_comment_login_bg_2.jpg) #ededed no-repeat",
			color: "#ededed"
		});
		$("#submit").attr({
			disabled: "disabled"
		});
	}
	var c = $("#currPicId").val();
	if (c != "") {
		getCommentsByPicId(c, $(this));
	}
	$("#textlimit").bind("keyup paste keydown",
	function() {
		var e = $.trim($("#textlimit").val()).length;
		if (e > 140) {
			var d = $.trim($("#textlimit").val()).length - 140;
			$("#prompt").html("已经超出<span style='color:#de2c28;font-size:14px;font-weight:bold'>" + d + "</span>个字");
		} else {
			if (e == 0) {
				if ($.trim($("#textlimit").val()) == "") {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
				} else {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>139</span>个字");
				}
			} else {
				var d = 140 - $.trim($("#textlimit").val()).length;
				$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + d + "</span>个字");
			}
		}
	});
	$("#textlimit").focus(function() {
		$(this).addClass("focus").next().show();
		$(this).css("background-image", "");
		$(this).css("background-color", "#fff");
	});
	$("#textlimit").blur(function() {
		if ($(this).val().length >= 0) {
			$(this).addClass("focus").next().show();
			$(this).css("background-color", "#fff");
		} else {
			$(this).removeClass("focus").next().show();
			$(this).css("background-color", "#fff");
		}
		if ($(this).val() == "") {
			$(this).css("background", "url(" + lwkai.imageUrl + "lvtu_comment_bg.jpg)#fff no-repeat");
		}
	});
	var b = "";
	$("#textlimit").keydown(function(d) {
		ctrlEnter(d);
	});
});
function isKeyTrigger(h, g) {
	var a = isKeyTrigger.arguments;
	var f = isKeyTrigger.arguments.length;
	var i = false;
	if (f > 2) {
		i = a[2];
	}
	var d = false;
	if (f > 3) {
		d = a[3];
	}
	var c = window.Event ? true: false;
	if (typeof h == "undefined") {
		h = event;
	}
	if (i && !((typeof h.ctrlKey != "undefined") ? h.ctrlKey: h.modifiers & Event.CONTROL_MASK > 0)) {
		return false;
	}
	if (d && !((typeof h.altKey != "undefined") ? h.altKey: h.modifiers & Event.ALT_MASK > 0)) {
		return false;
	}
	var b = 0;
	if (c) {
		b = h.which;
	} else {
		if (h.type == "keypress" || h.type == "keydown") {
			b = h.keyCode;
		} else {
			b = h.button;
		}
	}
	return (b == g);
}
function ctrlEnter(a) {
	if ($.browser.msie) {
		if (event.ctrlKey == true && window.event.keyCode == "13") {
			comment();
		}
	} else {
		if (isKeyTrigger(a, 13, true)) {
			comment();
		}
	}
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
		random: c,
		target: '1'
	};
	$.getJSON(pathUpload + "?module=album&action=getCommentNum", f,
	function(h) {
		d = h.totalCommentNum;
		$("#fcommentNums").text("评论数：" + d.toString());
		if (d < 11) {
			$("#pagination").css("display", "none");
		} else {
			$("#pagination").css("display", "block");
		}
		$("#pagination").paging(d, {
			format: "[<nnnncnnnn>]",
			perpage: 10,
			onSelect: function(i) {
				getPageData(i);
				$("#textlimit").val("");
			}
		});
	});
}
var comentId = "";
var userid = "";
var picReplyId = "";
var type = 1;
function fillPage(b) {
	var a = "";
	$.each(b.comments,
	function(d, e) {
		var f = "";
		if (e.userToReply != null && e.userToReply != "") {
			f += "<li class='userName'><a href='" + pathUpload + "album/userInfo/userId--" + e.userInfo.userId + ".html'>" + e.userInfo.nickName + "</a> 回复 <a href='" + pathUpload + "album/userInfo/userId--" + e.userToReply.userId + ".html'>" + e.userToReply.nickName + "</a>";
			f += "：" + e.content + "<em class='darkgray999 ml5 fn-cursor'>" + e.createTime + "</em>";
		} else {
			f += "<li class='userName'><a href='" + pathUpload + "album/userInfo/userId--" + e.userInfo.userId + ".html'>" + e.userInfo.nickName + "</a>： " + e.content + "<em class='darkgray999 ml5'>" + e.createTime + "</em>";
		}
		var c = e.userInfo.nickName;
		c = c.trim();
		f += '<span class=" mt5 deongaree fn-cursor" onclick="reply(\'' + e.userInfo.nickName + "','" + e.userInfo.userId + "','" + e.commentId + "')\">";
		if ($("#visitorId").val() != e.userInfo.userId) {
			f += " 回复</span>";
		} else {
			f += "</span>";
		}
		a += f;
	});
	$("#feedback").empty().append(a);
}
function reply(e, c, b) {
	$("#textlimit").addClass("focus").next().show();
	$("#textlimit").css("background-image", "");
	$("#textlimit").css("background-color", "#fff");
	$("#textlimit").val("回复" + e + "：");
	var a = $("#textlimit").val().length;
	var d = 140 - Number(a);
	$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + d + "</span>个字");
	type = 2;
	comentId = b;
	userId = c;
}
function getPageData(a) {
	var d = $("#currPicId").val();
	var f = $("#visitorId").val();
	var b = new Date();
	var c = b.getTime();
	var e = {
		visitorId: f,
		picId: d,
		pageNo: a,
		random: c,
		target: '1'
	};
	$.getJSON(pathUpload + "?module=album&action=comments", e,	function(g) {
		fillPage(g);
		picReplyId = d;
	});
}
function comment() {
	var d = $("#currPicId").val();
	var c = $("#textlimit").val();
	if ($.trim(c).length == 0) {
		$.jBox.tip("您还没有任何输入", "warning");
		return true;
	}
	if ($.trim(c).length > 140) {
		$.jBox.tip("你输入字数过多", "warning");
		return true;
	}
	var a = new Date();
	var b = a.getTime();
	var e = {
		picId: d,
		content: c,
		targetType: 1,
		extcs: getExtcsValue(),
		random: b
	};
	$("#submit").attr({
		disabled: "disabled"
	});
	$.jBox.tip("正在提交...", "loading");
	if (c.split("：").length == 1) {
		type = 1;
	}
	if (type == 1) {
		$.ajax({
			type: "POST",
			url: pathUpload + "?module=album&action=comment",
			data: e,
			dataType: "json",
			success: function(g) {
				if (g.result) {
					$.jBox.tip("评论成功", "success");
					$("#textlimit").val("");
					$("#prompt").text("还可以输入140个字");
					$("#submit").removeAttr("disabled");
					$(".pop_status").hide();
					$(".bg_mask").hide();
					var h = objComment.find(".describe").text();
					var f = h.split("：");
					f = parseInt(f[1]) + 1;
					objComment.empty();
					objComment.append('<span id="fcommentNums" class="describe fn-pa white pl10 fn-none png24" style="display: none; ">评论数：' + f + "</span>");
				} else {
					if (!g.result) {
						window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href) + "#" + e.picId;
					} else {
						$("#submit").removeAttr("disabled");
						$.jBox.tip("评论失败", "error");
					}
				}
			},
			error: function(f) {
				$("#submit").removeAttr("disabled");
				$.jBox.tip("评论失败", "error");
			}
		});
	} else {
		/*if (type == 2) {
			$.jBox.tip("正在提交...", "loading");
			var c = $("#textlimit").val();
			c = c.split("：")[1];
			e = {
				commentId: comentId,
				content: c,
				picReplyId: picReplyId,
				targetType: 1,
				extcs: getExtcsValue(),
				random: b
			};
			$.getJSON("web/saveReply.htm", e,
			function(g) {
				if (g.status == true) {
					$("#submit").removeAttr("disabled");
					$("#textlimit").val("");
					$(".bg_mask").hide();
					$(".pop_status").hide();
					var h = objComment.find(".describe").text();
					var f = h.split("：");
					f = parseInt(f[1]) + 1;
					objComment.empty();
					objComment.append('<span id="fcommentNums" class="describe fn-pa white pl10 fn-none png24" style="display: none; ">评论数：' + f + "</span>");
					$.jBox.tip("回复成功", "success");
				} else {
					$.jBox.tip("回复失败", "error");
				}
			});
		}*/
	}
}
var first = "";
function praisePic() {
	var k = $("#visitorId").val();
	var b = window.location.href;
	var i = new Array();
	i = b.split("#");
	var e = i[1];
	if (k == null || k == "") {
		var m = window.location.href;
		var h = m.split("=");
		if (h.length == 2) {
			window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(m);
		} else {
			if (h.length == 3) {
				var c = $("#activityId").val();
				window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(h[0] + "=" + h[1] + "=" + h[2]);
			} else {
				window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(m);
			}
		}
	} else {
		var j = e;
		var n = 1;
		var l = $("#currIsPraised").val();
		if (l == 1) {
			n = 2;
		}
		var d = new Date();
		var f = d.getTime();
		var a = $("#newhandler").val();
		var g = {
			visitorId: k,
			picId: j,
			targetType: 1,
			praiseActivityType: n,
			extcs: getExtcsValue(),
			staus: "00001",
			newHandler: a,
			random: f,
			targetType:1
		};
		// 赞一个，也就是喜欢点击事件
		$.getJSON(pathUpload + "?module=album&action=praisePic", g, function(u) {
			if (u.status == 0) {
				var t = parseInt($("#imgindex").val(), 10);
				var p = $("#fspraiseNums");
				$("#currIsPraised").val(n);
				var s = $("#scorollimg").find("#praiseNum");
				var q = $("#scorollimg").find("#isPraised");
				var v = parseInt(s.eq(t).text(), 10);
				if (n == 1) {
					s.eq(t).text((v + 1).toString());
					p.text("已喜欢：" + (v + 1).toString());
					$("#praiseAction").removeClass("praise");
					$("#praiseAction").addClass("praise_hover");
					var o = $("#newhandler").val();
					var r = o.substring(4, 5);
					// 取消掉同步到新浪微博 by lwkai add
					first = -1;
					if (r == 0 && $("#sinaBind").val() == "true" && first == 0) {
						$.jBox.tip("您的喜欢已同步到新浪微博，若您不想自动同步，请在'社交绑定'中取消", "success", {
							timeout: 5000
						});
						first = 1;
					} else {
						$.jBox.tip("称赞成功", "success");
					}
				} else {
					s.eq(t).text((v - 1).toString());
					p.text("喜欢数：" + (v - 1).toString());
					$("#praiseAction").addClass("praise");
					$("#praiseAction").removeClass("praise_hover");
				}
				q.eq(t).text(n);
			} else {
				if (u.status == 618 || u.status == 619) {
					$.jBox.tip("此图片已删除，请欣赏其它图片", "success");
				} else {
					window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href) + "#" + j;
				}
			}
		});
	}
}
function viewPic(b) {
	/*var d = $("#visitorId").val();
	var a = new Date().toString();
	var c = {
		visitUserId: d,
		picId: b,
		visitTime: a
	};
	$.getJSON("pic/uploadPicBrowserRecordFromWeb.htm", c,
	function(e) {});*/
}
function goComment() {
	$("#pl").scrollView();
}
