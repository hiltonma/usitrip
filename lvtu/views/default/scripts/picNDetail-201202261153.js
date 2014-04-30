(function(e) {
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
					S.html("<p>对不起，不支持该地</p>");
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
} ("(8(a){a.7=8(b,c){c=a.1n({},a.7.17,c);c.1M=c.1V>9;c.1p=c.1p||1q;c.1A=c.1A||1q;5(b==1y){b=''};5(c.W<9){c.W=9};5(c.1t==1y){c.1t='51'+2U.52(2U.3i()*4Z)};6 d=(a.1U.2e&&3u(a.1U.3a)<3r);6 e=a('#'+c.1t);5(e.1I>9){c.1k=a.7.17.1k++;e.16({1k:c.1k});e.10('#4').16({1k:c.1k+1h});1j e};6 f={2g:'',1x:'',1e:'',2j:b.50==53};5(!f.2j){b=b+'';6 N=b.56();5(N.1X('1t:')==9)f.1x='4t';1b 5(N.1X('4C:')==9)f.1x='35';1b 5(N.1X('57:')==9)f.1x='30';1b 5(N.1X('1F:')==9)f.1x='2w';1b 5(N.1X('1e:')==9)f.1x='3h';1b{b='1e:'+b;f.1x='3h'};b=b.54(b.1X(\":\")+1h,b.1I)};5(!c.1p&&!c.1A&&!c.4s){a(a.1U.2e?'1e':'1z').3c('11','2a:2l;1f-27:55;')};6 g=!c.1p&&!(c.1o==1y);6 h=f.1x=='35'||f.1x=='30'||f.1x=='2w';6 i=1L c.14=='36'?(c.14-4Y)+'19':\"4R%\";6 j=[];j.X('<Z 1t=\"'+c.1t+'\" 1a=\"4-'+(c.1p?'1v':(c.1A?'3l':'1z'))+'\">');5(c.1M){5((d&&a('1F').1I>9)||a('4S, 4P').1I>9){j.X('<1F 1t=\"4-24\" 1a=\"4-24\" 3y=\"3M:3X\" 11=\"1D:2R;1s:1B;z-3t:-1;\"></1F>')}1b{5(d){a('3Z').16('3L','2l')};j.X('<Z 1t=\"4-24\" 1a=\"4-24\" 11=\"1s:1B;\"></Z>')}};j.X('<Z 1t=\"4-3m\" 1a=\"4-3m\" 11=\"14:1c;Y:1c;4Q-4x:#4T;1s:1B;z-3t:45;4W:1d;\"></Z>');5(c.1Y){j.X('<Z 1t=\"4-47\" 1a=\"4-47\" 11=\"1s:1B;z-3t:45;1D:1d;\"></Z>')};j.X('<Z 1t=\"4\" 1a=\"4\" 11=\"1s:1B;14:1m;Y:1m;\">');j.X('<Z 1a=\"4-2W-1o 4-1o-23\" 11=\"Y:2s;1D:1d;\"></Z>');j.X('<Z 1a=\"4-2W-1l 4-1l-23\" 11=\"Y:2s;1f:2Z 0 2Z 0;1D:1d;\"></Z>');j.X('<4p W=\"0\" 4X=\"0\" 4U=\"0\" 11=\"1E:1c;1f:1c;W:1d;\">');5(c.W>9){j.X('<2n>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:'+c.W+'19 0 0 0;14:'+c.W+'19;Y:'+c.W+'19;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;Y:'+c.W+'19;2a: 2l;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:0 '+c.W+'19 0 0;14:'+c.W+'19;Y:'+c.W+'19;\"></1u>');j.X('</2n>')};j.X('<2n>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;\"></1u>');j.X('<1u 4V=\"18\" 11=\"1E:1c;1f:1c;W:1d;\">');j.X('<Z 1a=\"4-4v\" 11=\"14:1m; Y:1m;\">');j.X('<a 1a=\"4-1J\" 1o=\"'+a.7.1S.1J+'\" 58=\"$(21).2x(\\'4-1J-2E\\');\" 5l=\"$(21).2I(\\'4-1J-2E\\');\" 11=\"1s:1B; 1D:2R; 4w:5m; 18:'+(4D+c.W)+'19; 27:'+(4D+c.W)+'19; 14:4z; Y:4z;'+(c.34?'':'1D:1d;')+'\"></a>');5(g){j.X('<Z 1a=\"4-1o-23\" 11=\"Y:2s;\">');j.X('<Z 1a=\"4-1o'+(c.2L==1i?' 4-1o-12':(c.2L==1q?'':' '+c.2L))+'\" 11=\"3W:1g; 14:'+i+'; 3j-Y:'+(a.1U.2e?5j:5k)+'19; 1f-1g:'+(c.2L?5n:2X)+'19;2a:2l;1T-2a:5q;5r-1K:1K-5o;\">'+(c.1o==''?'&5p;':c.1o)+'</Z>');j.X('</Z>')};j.X('<Z 1t=\"4-2T\"></Z></Z>');j.X('</Z>');j.X('</1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;\"></1u>');j.X('</2n>');5(c.W>9){j.X('<2n>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:0 0 0 '+c.W+'19; 14:'+c.W+'19; Y:'+c.W+'19;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;Y:'+c.W+'19;2a: 2l;\"></1u>');j.X('<1u 1a=\"4-W\" 11=\"1E:1c;1f:1c;W:1d;W-3d:0 0 '+c.W+'19 0; 14:'+c.W+'19; Y:'+c.W+'19;\"></1u>');j.X('</2n>')};j.X('</4p>');j.X('</Z>');j.X('</Z>');6 k='<1F 2h=\"4-1F\" 1t=\"4-1F\" 14=\"2v%\" Y=\"2v%\" 5i=\"0\" 5b=\"0\" 5c=\"0\" 59=\"'+c.4r+'\"></1F>';6 l=a(2f);6 m=a(1H.1z);6 n=a(j.2m('')).5a(m);6 o=n.2r('#4');6 p=n.2r('#4-24');6 q=n.2r('#4-3m');5(!f.2j){3H(f.1x){1R\"4t\":f.1e=a('#'+b).1e();1K;1R\"35\":1R\"30\":f.1e='';f.2g=b;1K;1R\"3h\":f.1e=b;1K;1R\"2w\":f.1e=k;5(b.1X('#')==-1h){f.2g=b+(b.1X('?')==-1h?'?39':'&39')+2U.3i()}1b{6 N=b.5d('#');f.2g=N[9]+(N[9].1X('?')==-1h?'?39':'&39')+2U.3i()+'#'+N[1h]};1K};b={5g:{13:f.1e,1C:c.1C,2o:c.2o,1W:c.1W}}};6 r=[];6 s=o.10('.4-2W-1o').3N(1i);6 t=o.10('.4-2W-1l').3N(1i);6 u=a.1U.2e?'3j-Y:3V;1f:1c 3O 1c 3O;':'1f:1c 2N 1c 2N;';a.2C(b,8(N,O){5(f.2j){O=a.1n({},a.7.2O,O)};b[N]=O;5(O.1C==1y){O.1C={}};6 P=1q;a.2C(O.1C,8(T,U){P=1i});6 Q='1m';5(1L c.Y=='36'){Q=c.Y;5(g){Q=Q-s};5(P){Q=Q-t};Q=(Q-1h)+'19'};6 R='';6 S='2s';5(!f.2j&&h){6 T=c.Y;5(1L c.Y=='36'){5(g){T=T-s};5(P){T=T-t};S=((T/2X)*1N)+'19';T=(T-1h)+'19'};R=['<Z 1t=\"4-13-2B\" 1a=\"4-13-2B\" 11=\"2P-Y:5h;Y:'+T+'; 1T-2k:42;\">','<Z 1a=\"4-13-2B-5e\" 11=\"1D:2R; 1E:1m; 14:5f; Y:3V; 1f-18: '+S+';\"></Z>','</Z>'].2m('')};r.X('<Z 1t=\"4-1G-'+N+'\" 1a=\"4-1G\" 11=\"1D:1d;\">');r.X('<Z 11=\"2P-14:3o;14:'+(1L c.14=='36'?c.14+'19':'1m')+'; Y:'+Q+';\">'+R+'<Z 1t=\"4-13\" 1a=\"4-13\" 11=\"Y:'+Q+';2a:2l;2a-y:1m;\">'+O.13+'</Z></Z>');r.X('<Z 1a=\"4-1l-23\" 11=\"Y:2s;1f:2Z 0 2Z 0;1T-2k: 27;'+(P?'':'1D:1d;')+'\">');5(!c.1p){r.X('<26 1a=\"4-29-1T\" 11=\"3W:1g;1D:2R;3j-Y:2s;\"></26>')};a.2C(O.1C,8(T,U){r.X('<1l 1a=\"4-1l\" 31=\"'+U+'\" 11=\"'+u+'\">'+T+'</1l>')});r.X('</Z></Z>')});o.10('#4-2T').1e(r.2m('')).2r('.4-1G:3k').16('1D','2R');5(h){6 N=o.10('#4-13').16({1s:(d)?\"1B\":\"32\",1g:-4H})};a.2C(b,8(N,O){6 P=o.10('#4-1G-'+N);P.2r('.4-1l-23').2r('1l').2c(8(){6 Q=P.10('#4-13');6 R=O.1C[a(21).1T()];6 S={};a.2C(o.10('#4-2T :4h').4M(),8(U,V){5(S[V.2h]===1y){S[V.2h]=V.31}1b 5(1L S[V.2h]==4L){S[V.2h].X(V.31)}1b{S[V.2h]=[S[V.2h],V.31]}});6 T=O.1W(R,Q,S);5(T===1y||T){I()}}).1P('2t',8(){a(21).2x('4-1l-3x')}).1P('4A',8(){a(21).2I('4-1l-3x')}).1P('4G',8(){a(21).2x('4-1l-2E')}).1P('4O',8(){a(21).2I('4-1l-3x').2I('4-1l-2E')});P.10('.4-1l-23 1l:2V('+O.2o+')').2x('4-1l-1O')});6 v=8(){n.16({18:l.3e()});5(c.1A){o.16({1s:(d)?\"1B\":\"32\",27:1h,29:1h})}};6 w=8(){6 N=l.14();1j 1H.1z.3I<N?N:1H.1z.3I};6 x=8(){6 N=l.Y();1j 1H.1z.3C<N?N:1H.1z.3C};6 y=8(){5(!c.1M){1j};5(c.4q){6 N=9;n.2x('4-25');6 O=4K(8(){n.4J('4-25');5(N++>1h){4I(O);n.2I('4-25')}},4N)}1b{I()}};6 z=8(N){5(c.1p||c.1A){1j 1q};6 O=(2f.4f)?4f.4g:N.4g;5(O==4F){I()};5(O==5Z){6 P=a(':4h:5Y:2p',n);6 Q=!N.4e&&N.1r==P[P.1I-1h];6 R=N.4e&&N.1r==P[9];5(Q||R){38(8(){5(!P)1j;6 S=P[R===1i?P.1I-1h:9];5(S)S.1O()},2G);1j 1q}}};6 A=8(){5(c.1M){p.16({1s:\"1B\",Y:c.1p?x():l.Y(),14:d?l.14():\"2v%\",18:9,1g:9,27:9,29:9})}};6 B=8(){5(c.1A){o.16({1s:(d)?\"1B\":\"32\",27:1h,29:1h})}1b{q.16({18:c.18});o.16({1s:\"1B\",18:q.3f().18+(c.1p?l.3e():9),1g:((l.14()-o.3S())/1N)})};5((c.1M&&!c.1p)||(!c.1M&&!c.1p&&!c.1A)){n.16({1s:(d)?\"1B\":\"32\",Y:c.1M?l.Y():9,14:\"2v%\",18:(d)?l.3e():9,1g:9,27:9,29:9})};A()};6 C=8(){c.1k=a.7.17.1k++;n.16({1k:c.1k});o.16({1k:c.1k+1h})};6 D=8(){c.1k=a.7.17.1k++;n.16({1k:c.1k});o.16({1D:\"1d\",1k:c.1k+1h});5(c.1M){p.16({1D:\"1d\",1k:c.1k,1V:c.1V})}};6 E=8(N){6 O=N.1w;O.1r.10('1F').2K();5(c.22){O.1r.2u().16({1g:O.1r.16('1g'),18:O.1r.16('18'),61:-1N,60:-1N,14:O.1r.14()+1N,Y:O.1r.Y()+1N}).1Z()};1j 1q};6 F=8(N){6 O=N.1w;6 P=O.49+N.4c-O.43;6 Q=O.4y+N.48-O.4a;5(c.4o){6 R=1h;6 S=1H.46.3C-N.1w.1r.Y()-1h;6 T=1h;6 U=1H.46.3I-N.1w.1r.14()-1h;5(Q<R)Q=R+(c.22?1N:9);5(Q>S)Q=S-(c.22?1N:9);5(P<T)P=T+(c.22?1N:9);5(P>U)P=U-(c.22?1N:9)};5(c.22){O.1r.2u().16({1g:P,18:Q})}1b{O.1r.16({1g:P,18:Q})};1j 1q};6 G=8(N){a(1H).2i('.1Y');5(c.22){6 O=N.1w.1r.2u().2K();N.1w.1r.16({1g:O.16('1g'),18:O.16('18')}).10('1F').1Z()}1b{N.1w.1r.10('1F').1Z()};1j 1q};6 H=8(N){6 O=N.1w.1r.1s();6 P={1r:N.1w.1r,43:N.4c,4a:N.48,49:O.1g,4y:O.18};a(1H).1P('2t.1Y',P,E).1P('5V.1Y',P,F).1P('4A.1Y',P,G)};6 I=8(){5(!c.1p&&!c.1A){5(a('.4-1z').1I==1h){a(a.1U.2e?'1e':'1z').5U('11')};J()}1b{5(c.1p){6 1v=a(1H.1z).1w('1v');5(1v&&1v.2F==1i){q.16('18',1v.33.18);6 N=q.3f().18+l.3e();5(N==o.3f().18){J()}1b{o.10('#4-13').1e(1v.33.13.5X(2X)).5W().16({1g:((l.14()-o.3S())/1N)}).41({18:N,1V:0.1},3J,J)}}1b{o.41({18:'-=62',1V:9},3J,J)}}1b{3H(c.2J){1R'3D':o.4b(c.20,J);1K;1R'24':o.3P(c.20,J);1K;1R'1Z':3R:o.2K(c.20,J);1K}}}};6 J=8(){l.2i('3U',A);5(c.1Y&&!c.1p&&!c.1A){o.10('.4-1o-23').2i('2t',H)};5(f.1x!='2w'){o.10('#4-1F').3c({'3y':'3M:3X'})};o.1e('').3F();5(d&&!c.1p){m.2i('3T',v)};5(c.1M){p.3P('37',8(){p.2i('2c',y).2i('2t',C).1e('').3F()})};n.2i('3Y 3K',z).1e('').3F();5(d&&c.1M){a('3Z').16('3L','2p')};5(1L c.2H=='8'){c.2H()}};6 K=8(){5(c.1Q>9){o.1w('3B',2f.38(I,c.1Q));5(c.1A){o.2E(8(){2f.63(o.1w('3B'))},8(){o.1w('3B',2f.38(I,c.1Q))})}}};6 L=8(){5(1L c.2Y=='8'){c.2Y(o.10('.4-1G:2p').10('.4-13'))}};5(!f.2j){3H(f.1x){1R\"35\":1R\"30\":a.64({1x:f.1x,2g:f.2g,1w:c.3g==1y?{}:c.3g,5B:'1e',5A:1q,2y:8(N,O){o.10('#4-13').16({1s:\"3Q\"}).1e(N).1Z().2u().2K();L()},2z:8(){o.10('#4-13-2B').1e('<Z 11=\"1f-18:3o;1f-29:3o;1T-2k:42;\">5z 5C.</Z>')}});1K;1R\"2w\":o.10('#4-1F').3c({'3y':f.2g}).1P(\"5F\",8(N){a(21).5E().16({1s:\"3Q\"}).1Z().2u().2K();o.10('#4-2T .4-1G:3k .4-1l-1O').1O();L()});1K;3R:o.10('#4-13').1Z();1K}};B();D();5(d&&!c.1p){l.3T(v)};5(c.1M){p.2c(y)};l.3U(A);n.1P('3Y 3K',z);o.10('.4-1J').2c(I);5(c.1M){p.4u('37')};6 M='1Z';5(c.2J=='3D'){M='44'}1b 5(c.2J=='24'){M='4u'};5(c.1A){o[M](c.20,K)}1b{6 1v=a(1H.1z).1w('1v');5(1v&&1v.2F==1i){a(1H.1z).1w('1v',{2F:1q,33:{}});o.16('1D','')}1b{5(!f.2j&&h){o[M](c.20)}1b{o[M](c.20,L);}}};5(!c.1p){o.10('.4-29-1T').1e(c.4E)}1b{o.10('.4-4v,.4-13').2x('4-1v-4x')};5(f.1x!='2w'){o.10('#4-2T .4-1G:3k .4-1l-1O').1O()}1b{o.1O()};5(!c.1A){K()};n.1P('2t',C);5(c.1Y&&!c.1p&&!c.1A){o.10('.4-1o-23').1P('2t',{1r:o},H).16('4w','5D')};1j n};a.7.3a=2.3;a.7.17={1t:3A,18:\"15%\",1k:5u,W:2X,1V:0.1,1Q:9,2J:'24',20:'37',2L:1i,34:1i,1Y:1i,4o:1i,22:1q,4q:1i,4s:1i,3g:{},4r:'1m',1o:'7',14:3p,Y:'1m',4E:'',1C:{'3z':'2b'},2o:9,2Y:8(b){},1W:8(b,c,d){1j 1i},2H:8(){}};a.7.2O={13:'',1C:{'3z':'2b'},2o:9,1W:8(b,c,d){1j 1i}};a.7.2Q={13:'',12:'28',18:'40%',14:'1m',Y:'1m',1V:9,1Q:4B,2H:8(){}};a.7.2A={13:'',1o:'7',12:'1d',14:3p,Y:'1m',1Q:4B,2J:'3D',20:5t,W:9,1C:{},2o:9,2Y:8(){},1W:8(b,c,d){1j 1i},2H:8(){}};a.7.1S={1J:'5s',2b:'3z',3n:'5v',3q:'5y',2S:'5x'};a.7.5w=8(b){a.7.17=a.1n({},a.7.17,b.17);a.7.2O=a.1n({},a.7.2O,b.2O);a.7.2Q=a.1n({},a.7.2Q,b.2Q);a.7.2A=a.1n({},a.7.2A,b.2A);a.7.1S=a.1n({},a.7.1S,b.1S)};a.7.2D=8(){1j a('.4-1z').2V(a('.4-1z').1I-1h)};a.7.5P=8(b){6 c=(1L b=='3v')?a('#'+b):a.7.2D();1j c.10('#4-1F').4C(9)};a.7.5O=8(){1j a.7.3b().10('.4-13').1e()};a.7.5N=8(b){1j a.7.3b().10('.4-13').1e(b)};a.7.3b=8(b){5(b==1y){1j a.7.2D().10('.4-1G:2p')}1b{1j a.7.2D().10('#4-1G-'+b)}};a.7.5Q=8(){1j a.7.3b().3c('1t').5T('4-1G-','')};a.7.3w=8(b,c){6 d=a.7.2D();5(d!=1y&&d!=3A){6 e;b=b||1q;d.10('.4-1G').4b('37');5(1L b=='3v'){e=d.10('#4-1G-'+b)}1b{e=b?d.10('.4-1G:2p').2F():d.10('.4-1G:2p').2u()};e.44(3p,8(){2f.38(8(){e.10('.4-1l-1O').1O();5(c!=1y){e.10('.4-13').1e(c)}},5S)})}};a.7.5R=8(b){a.7.3w(1i,b)};a.7.5I=8(b){a.7.3w(1q,b)};a.7.1J=8(b,c){b=b||1q;c=c||'1z';5(1L b=='3v'){a('#'+b).10('.4-1J').2c()}1b{6 d=a('.4-'+c);5(b){5H(6 e=9,l=d.1I;e<l;++e){d.2V(e).10('.4-1J').2c()}}1b{5(d.1I>9){d.2V(d.1I-1h).10('.4-1J').2c()}}}};a.7.5G=8(b,c,d,e,f){6 17={13:b,1o:c,14:d,Y:e};f=a.1n({},17,f);f=a.1n({},a.7.17,f);a.7(f.13,f)};a.7.2d=8(b,c,d,e){6 17={13:b,1o:c,12:d,1C:3s('({ \"'+a.7.1S.2b+'\": \"2b\" })')};e=a.1n({},17,e);e=a.1n({},a.7.17,e);5(e.W<9){e.W=9};5(e.12!='28'&&e.12!='25'&&e.12!='2y'&&e.12!='2z'&&e.12!='3G'){1f='';e.12='1d'};6 f=e.1o==1y?2G:4j;6 g=e.12=='1d'?'Y:1m;':'2P-Y:2M;'+((a.1U.2e&&3u(a.1U.3a)<3r)?'Y:1m !4l;Y:2v%;4n:2M;':'Y:1m;');6 h=[];h.X('1e:');h.X('<Z 11=\"1E:2N;'+g+'1f-1g:'+(e.12=='1d'?9:4m)+'19;1T-2k:1g;\">');h.X('<26 1a=\"4-12 4-12-'+e.12+'\" 11=\"1s:1B; 18:'+(f+e.W)+'19;1g:'+(2G+e.W)+'19; 14:2q; Y:2q;\"></26>');h.X(e.13);h.X('</Z>');e.13=h.2m('');a.7(e.13,e)};a.7.5J=8(b,c,d){a.7.2d(b,c,'1d',d)};a.7.28=8(b,c,d){a.7.2d(b,c,'28',d)};a.7.2y=8(b,c,d){a.7.2d(b,c,'2y',d)};a.7.2z=8(b,c,d){a.7.2d(b,c,'2z',d)};a.7.5M=8(b,c,d,e){6 17={1C:3s('({ \"'+a.7.1S.2b+'\": \"2b\", \"'+a.7.1S.2S+'\": \"2S\" })')};5(d!=1y&&1L d=='8'){17.1W=d}1b{17.1W=8(f,g,h){1j 1i}};e=a.1n({},17,e);a.7.2d(b,c,'3G',e)};a.7.25=8(b,c,d,e){6 17={1C:3s('({ \"'+a.7.1S.3n+'\": \"3n\", \"'+a.7.1S.3q+'\": \"3q\", \"'+a.7.1S.2S+'\": \"2S\" })')};5(d!=1y&&1L d=='8'){17.1W=d}1b{17.1W=8(f,g,h){1j 1i}};e=a.1n({},17,e);a.7.2d(b,c,'25',e)};a.7.1v=8(b,c,d){6 17={13:b,12:c,1V:9,W:9,34:1q,1C:{},1p:1i};5(17.12=='2B'){17.1Q=9;17.1V=0.1};d=a.1n({},17,d);d=a.1n({},a.7.2Q,d);d=a.1n({},a.7.17,d);5(d.1Q<9){d.1Q=9};5(d.W<9){d.W=9};5(d.12!='28'&&d.12!='25'&&d.12!='2y'&&d.12!='2z'&&d.12!='2B'){d.12='28'};6 e=[];e.X('1e:');e.X('<Z 11=\"2P-Y:5L;Y:1m;1E:2N;1f-1g:2M;1f-18:1c;1T-2k:1g;\">');e.X('<26 1a=\"4-12 4-12-'+d.12+'\" 11=\"1s:1B;18:'+(4d+d.W)+'19;1g:'+(4d+d.W)+'19; 14:2q; Y:2q;\"></26>');e.X(d.13);e.X('</Z>');d.13=e.2m('');5(a('.4-1v').1I>9){a(1H.1z).1w('1v',{2F:1i,33:d});a.7.4k()};5(d.3E!=1y){a('#'+d.3E).1O();18.$('#'+d.3E).1O()};a.7(d.13,d)};a.7.4k=8(){a.7.1J(1q,'1v')};a.7.3l=8(b,c,d,e){a.7.4i();6 17={13:b,1o:c,1Q:(d==1y?a.7.2A.1Q:d),1V:9,34:1i,1Y:1q,1A:1i};e=a.1n({},17,e);e=a.1n({},a.7.2A,e);6 f=a.1n({},a.7.17,{});f.1o=3A;e=a.1n({},f,e);5(e.W<9){e.W=9};5(e.12!='28'&&e.12!='25'&&e.12!='2y'&&e.12!='2z'&&e.12!='3G'){1f='';e.12='1d'};6 g=e.1o==1y?2G:4j;6 h=e.12=='1d'?'Y:1m;':'2P-Y:2M;'+((a.1U.2e&&3u(a.1U.3a)<3r)?'Y:1m !4l;Y:2v%;4n:2M;':'Y:1m;');6 i=[];i.X('1e:');i.X('<Z 11=\"1E:2N;'+h+'1f-1g:'+(e.12=='1d'?9:4m)+'19;1T-2k:1g;\">');i.X('<26 1a=\"4-12 4-12-'+e.12+'\" 11=\"1s:1B; 18:'+(g+e.W)+'19;1g:'+(2G+e.W)+'19; 14:2q; Y:2q;\"></26>');i.X(e.13);i.X('</Z>');e.13=i.2m('');a.7(e.13,e)};a.7.4i=8(){a.7.1J(1q,'3l')};2f.7=a.7})(5K);", 62, 377, "||||jbox|if|var|jBox|function|0x0|||||||||||||||||||||||||||||||||||||||||||||||||border|push|height|div|find|style|icon|content|width||css|defaults|top|px|class|else|0px|none|html|padding|left|0x1|true|return|zIndex|button|auto|extend|title|isTip|false|target|position|id|td|tip|data|type|undefined|body|isMessager|absolute|buttons|display|margin|iframe|state|document|length|close|break|typeof|showFade|0x2|focus|bind|timeout|case|languageDefaults|text|browser|opacity|submit|indexOf|draggable|show|showSpeed|this|dragClone|panel|fade|warning|span|right|info|bottom|overflow|ok|click|prompt|msie|window|url|name|unbind|isObject|align|hidden|join|tr|buttonsFocus|visible|32px|children|25px|mousedown|prev|100|IFRAME|addClass|success|error|messagerDefaults|loading|each|getBox|hover|next|0xa|closed|removeClass|showType|hide|showIcon|30px|10px|stateDefaults|min|tipDefaults|block|cancel|states|Math|eq|help|0x5|loaded|5px|POST|value|fixed|options|showClose|GET|number|fast|setTimeout|___t|version|getState|attr|radius|scrollTop|offset|ajaxData|HTML|random|line|first|messager|temp|yes|50px|0x15e|no|0x7|eval|index|parseInt|string|goToState|active|src|确定|null|autoClosing|clientHeight|slide|focusId|remove|question|switch|clientWidth|0x1f4|keypress|visibility|about|outerHeight|6px|fadeOut|static|default|outerWidth|scroll|resize|19px|float|blank|keydown|select||animate|center|startX|slideDown|1984|documentElement|drag|pageY|startLeft|startY|slideUp|pageX|0x4|shiftKey|event|keyCode|input|closeMessager|0x23|closeTip|important|0x28|_height|dragLimit|table|persistent|iframeScrolling|showScrolling|ID|fadeIn|container|cursor|color|startTop|15px|mouseup|0xbb8|get|0x6|bottomText|0x1b|mouseover|0x2710|clearInterval|toggleClass|setInterval|Array|serializeArray|0x64|mouseout|applet|background|90|object|ff3300|cellspacing|valign|fdisplay|cellpadding|0x32|0xf4240|constructor|jBox_|floor|Object|substring|17px|toLowerCase|post|onmouseover|scrolling|appendTo|marginwidth|frameborder|split|image|220px|state0|70px|marginheight|0x19|0x18|onmouseout|pointer|0x12|all|nbsp|ellipsis|word|关闭|0x258|0x7c0|是|setDefaults|取消|否|Loading|cache|dataType|Error|move|parent|load|open|for|prevState|alert|jQuery|18px|confirm|setContent|getContent|getIframe|getStateName|nextState|0x14|replace|removeAttr|mousemove|end|substr|enabled|0x9|marginTop|marginLeft|200|clearTimeout|ajax".split("|"), 0, {}));
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
	},
	1);
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
			top: r,
			width: m,
			height: t
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
				top: r,
				width: m,
				height: t * 2
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
						top: r,
						width: m * 2,
						height: t
					});
				} else {
					l.css({
						left: e - m / 2,
						top: r,
						width: m * 2,
						height: t
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
	$(".lvtu_pic_image_bigpic img").css("height", t);
	$(".lvtu_pic_image_load").css({
		width: m,
		height: t
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
		$.getJSON("web/getAlbumNameList.htm", params,
		function(data) {
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
					$(".J-picAlbum-list").hide();
					var picAlbumT = $(this).text();
					var picAlbumId = $(this).attr("id");
					$("#albumIdNew").val(picAlbumId);
					$(".picst").text(picAlbumT);
				});
			} else {
				if (data.getANLResult == 8000) {
					window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + picId;
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
		});
	});
	$(".J-cover").hover(function() {
		$(this).removeClass("cover");
		$(this).addClass("cover_hover");
	},
	function() {
		$(this).removeClass("cover_hover");
		$(this).addClass("cover");
	});
	$(".J-edit").hover(function() {
		$(this).removeClass("edit");
		$(this).addClass("edit_hover");
	},
	function() {
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
$(".J-picAlbum-fips").click(function() {
	$(".J-picAlbum-list").show();
});
$(document).ready(function() {
	var d = $(".J-picAlbum-name a");
	var f = 30;
	$("html,body").animate({
		scrollTop: f
	},
	1);
	$(".J-tips_b").click(function() {
		$("html,body").animate({
			scrollTop: f
		},
		1000);
	});
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
			window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(B);
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
			$.getJSON("web/editWebPic.htm", H,
			function(S) {
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
						$(".J-place").append("<a href='web/sight.htm?sightId=" + K + "' title='" + F + "'>" + F + "</a>");
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
							window.location.href = pathUpload + "web/album.htm?albumId=" + W;
						} else {
							suningImages().nextPic(B);
						}
					} else {
						if (S.editstatus == 8000) {
							window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + H.picId;
						} else {
							$.jBox.tip("编辑图片失败。", "success");
						}
					}
				}
			});
		}
	});
	$("#textlimit").bind("keyup paste keydown",
	function() {
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
	$("#textlimit").bind("keyup paste keydown",
	function() {
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
					random: F
				};
				$.getJSON("web/deleteWebPic.htm", I,
				function(N) {
					if (N.delstatus == 0) {
						$.jBox.tip("图片删除成功。", "success");
						var K = $("#scorollimg").find("#miniUrl");
						var J = K.length;
						if (J == 1) {
							var M = $("#albumId").val();
							window.location.href = pathUpload + "web/album.htm?albumId=" + M;
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
							window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + I.picId;
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
		$.getJSON("web/saveCover.htm", E,
		function(G) {
			if (G.code == 200) {
				$.jBox.tip("设置游记封面成功", "success");
			} else {
				if (G.code == 8000) {
					window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href);
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
function nextPics() {
	var f = perPageNum();
	var e = $(".crumbN_inner").find(".active").index() + 1;
	var h = e % f;
	var c = parseInt(e / f);
	if (h == 0) {
		pageFlip(++c);
	}
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
		$(".crumbN_inner").animate({
			left: "-=" + a
		}),
		"slow";
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
				Y.html('<span class="J-place span2 fn-fr"><a href="web/sight.htm?sightId=' + aC + '" title=' + aV + ">" + aV + '</a></span><span class="span1 pl20 fn-fr png24">拍摄地：</span>');
				document.title = aV + "-图片by" + newnickName + "-旅图-去哪儿网Qunar.com";
			} else {
				$("#faddressName").hide();
				var aK = $("#albumName").val();
				document.title = aK + "-图片by" + newnickName + "-旅图-去哪儿网Qunar.com";
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
			ad.html('——————<a class="ml15 mr15" href="web/userInfo.htm?userId=' + at + '" title=' + newnickName + ">" + newnickName + "</a>——————");
			if (newimageUrl == null || newimageUrl == "undefined" || newimageUrl == "") {
				F.html('<a href="web/userInfo.htm?userId=' + at + '" title=' + newnickName + '><img src= "http://source.qunar.com/site/images/wap/lvtu/lvtu_userhead.gif" alt= ' + newnickName + '  width="70" height="70" /></a>');
			} else {
				F.html('<a href="web/userInfo.htm?userId=' + at + '" title=' + newnickName + "><img src= " + newimageUrl + " alt= " + newnickName + '  width="70" height="70" /></a>');
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
			$("#loginLine a:eq(1)").attr("href", "http://user.qunar.com/logout.jsp?ret=" + ag);
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
				window.location.href = pathUpload + "web/picWebDetail.htm?picId=" + ai + "#" + ai;
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
			}
		});
	});
}
function fillPage(b) {
	var a = "";
	$.each(b.comments,
	function(c, d) {
		var e = "";
		e += "<li class='userName'><a href='web/userInfo.htm?userId=" + d.userInfo.userId + "'>" + d.userInfo.nickName + "</a>： " + d.content + "<em class='darkgray999 ml5'>" + d.createTime + "</em>";
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
		url: "web/comment.htm",
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
					window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + d.picId;
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
	var a = window.location.href;
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
	});
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
			background: "url(http://source.qunar.com/site/images/wap/lvtu/lvtu_comment_login_bg_2.jpg) #ededed no-repeat",
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
			$(this).css("background", "url(http://source.qunar.com/site/images/wap/lvtu/lvtu_comment_bg.jpg)#fff no-repeat");
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
		random: c
	};
	$.getJSON("web/getCommentNum.htm", f,
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
			f += "<li class='userName'><a href='web/userInfo.htm?userId=" + e.userInfo.userId + "'>" + e.userInfo.nickName + "</a> 回复 <a href='web/userInfo.htm?userId=" + e.userToReply.userId + "'>" + e.userToReply.nickName + "</a>";
			f += "：" + e.content + "<em class='darkgray999 ml5 fn-cursor'>" + e.createTime + "</em>";
		} else {
			f += "<li class='userName'><a href='web/userInfo.htm?userId=" + e.userInfo.userId + "'>" + e.userInfo.nickName + "</a>： " + e.content + "<em class='darkgray999 ml5'>" + e.createTime + "</em>";
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
		random: c
	};
	$.getJSON("web/comments.htm", e,
	function(g) {
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
			url: "web/comment.htm",
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
						window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + e.picId;
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
		if (type == 2) {
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
		}
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
			window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(m);
		} else {
			if (h.length == 3) {
				var c = $("#activityId").val();
				window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(h[0] + "=" + h[1] + "=" + h[2]);
			} else {
				window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(m);
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
			random: f
		};
		$.getJSON("web/praisePic.htm", g,
		function(u) {
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
					window.location.href = "http://user.qunar.com/login.jsp?ret=" + encodeURIComponent(window.location.href) + "#" + j;
				}
			}
		});
	}
}
function viewPic(b) {
	var d = $("#visitorId").val();
	var a = new Date().toString();
	var c = {
		visitUserId: d,
		picId: b,
		visitTime: a
	};
	$.getJSON("pic/uploadPicBrowserRecordFromWeb.htm", c,
	function(e) {});
}
function goComment() {
	$("#pl").scrollView();
}