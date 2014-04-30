(function(a) {
	a(function() {
		a("body").append('<style>#tooltip-wrap { width:300px; position:absolute; z-index:10; display:none; } .tooltip-default { border:1px solid #fede77; background-color:#fffdf0; line-height:20px; } #tooltip-arrow { display:block; width:13px; height:8px; overflow:hidden; position:absolute; top:-8px; left:144px; background:url(/image/tip_top.gif) no-repeat; } #tooltip-con { padding:10px; }</style><div id="tooltip-wrap" class="tooltip-default"><span id="tooltip-arrow"></span><div id="tooltip-con"></div></div>');
		var i = a("#tooltip-wrap");
		var c = a("#tooltip-con");
		var j = a("#tooltip-arrow");
		var g = j.width();
		var b;
		var h = a(window);
		var d = i.outerWidth();
		var f = (d - g) / 2;
		j.css({
			left: f
		});
		var e = function() {
			i.fadeOut()
		};
		a(".tooltip").live("mouseenter",
		function() {
			clearTimeout(b);
			var o = a(this);
			var n = o.outerHeight();
			var r = o.outerWidth();
			var u = 0;
			var m = o.attr("tooltip");
			var t = o.find(".tooltipCon");
			if (!m) {
				if (t.text() == "") {
					return
				}
			}
			if (m == "") {
				return
			}
			if (!o.attr("tooltip")) {
				c.html("");
				t.clone().show().appendTo(c)
			} else {
				c.html(m)
			}
			var s = o.offset().left;
			var q = o.offset().top + 10 + n;
			var p = h.width();
			var l = s - (d - r) / 2;
			if (d + l > p) {
				var k = d + l - p;
				i.css({
					left: l - k
				});
				j.css({
					left: f + k + u
				})
			} else {
				if (l < 0) {
					i.css({
						left: 0
					});
					j.css({
						left: f + l + u
					})
				} else {
					i.css({
						left: l
					});
					j.css({
						left: f + u
					})
				}
			}
			i.css({
				top: q
			});
			i.fadeIn()
		}).live("mouseleave",
		function() {
			b = setTimeout(e, 300)
		});
		i.hover(function() {
			clearTimeout(b)
		},
		function() {
			b = setTimeout(e, 300)
		})
	})
})(jQuery);