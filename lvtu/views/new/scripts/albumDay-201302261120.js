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
		placeholder: pathUpload + "/views/new/image/lvtu_bg_2.jpg",//"http://source.qunar.com/site/images/wap/lvtu/lvtu_bg_2.jpg",
		effect: showeffect,
		failurelimit: 2
	});
}); 
(function(a) {
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
		var p = encodeURIComponent(d + "（来自走四方旅图）");
		var u = encodeURIComponent(n);
		var i = encodeURIComponent(d + "（来自走四方旅图）");
		var s = '';
		//s原值
		/*s = a("<div class='album_bind_nav'><ul><li class='album_bind_sina'><a onclick=shareAlbum(2,'" + v + "') href='http://service.weibo.com/share/share.php?c=share&a=index&appkey=&title=" + encodeURIComponent(f) + "&pic=" + v + "&url=" + g + "' target='_blank' title='分享到新浪微博'>新浪微博</a></li><li class='album_bind_qqlg'><a  onclick=shareAlbum(3,'" + v + "') href='http://v.t.qq.com/share/share.php?title=" + encodeURIComponent(b) + "&pic=" + v + "' target='_blank' title='分享到腾讯微博'>腾讯微博</a></li><li class='album_bind_qzone'><a onclick=shareAlbum(7,'" + v + "') href='http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + g + "&title=" + encodeURIComponent(d) + "（来自走四方旅图）&pics=" + v + "&summary=" + n + "' target='_blank' title='分享到QQ空间'>QQ空间</a></li><li class='album_bind_renren'><a  onclick=shareAlbum(9,'" + v + "') href='http://widget.renren.com/dialog/share?link=" + g + "&title=" + p + "&description=" + u + "&pic=" + v + "' target='_blank' title='分享到人人网'>人人网</a></li><li class='album_bind_douban'><a  onclick=shareAlbum(6,'" + v + "') href='http://www.douban.com/recommend/?url=" + g + "&image=" + v + "&title=" + i + "&comment= ' target='_blank' title='分享到豆瓣网'>豆瓣网</a></li></ul></div>").css({
			bottom: k.bottom + 50 + "px",
			right: "50%",
			display: "block"
		}).appendTo("body")*/
		if (q()) {
			var c = a(document).scrollTop(),
			o = a(window).height();
			var m = o + c - r.outerHeight() - k.bottom;
			r.css("top", m + "px");
			//s.css("top", m - 118 + "px");
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
				//s.css("top", x - 118 + "px");
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
		title: "返回顶部"
	};
})(jQuery);
$.scrollBtn({
	showScale: 200,
	bottom: 100,
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
	isTip: false,
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

$.jBox.setDefaults(jBoxConfig);
var GetQueryString = function(a,_case) {
    _case = !!_case;
    if (_case) {
        var b = window.location.href.split('/');
        for(var key in b) {
            if (b[key].indexOf('--') != -1) {
                var _arr = b[key].split('--');
                for(var i = 0, len = _arr.length; i < len; i++) {
                    if (_arr[i].toLowerCase() == a.toLowerCase()) {
                        return _arr[i+1].replace(/\.html$/ig,'');
                    }
                }
            }
        }
    } else { 
	    var b = new RegExp("(^|&)" + a + "=([^&]*)(&|$)");
	    var c = window.location.search.substr(1).match(b);
        if (c != null) {
            return unescape(c[2]);
        }
        return null;
	}
	
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
		/*$.getJSON("web/updateHandlerStatus.htm", params,
		function(info) {});*/
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
		
		var h = $(".travels_main").innerHeight();
		var i = $(".travels_left");
		var fh = $(".travels_left").innerHeight();
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
			/*if (j >= b ) {
				i.addClass("sidebar_fixed");
				i.css("top", "0");
			} else {
				i.removeClass("sidebar_fixed");
				i.css("top", b - j);
			}*/
			var _h = j-b;
			if (_h<0){
				_h=0;
			}
			if (j+fh > b+h) {
				_h = h - fh -15;
			}
			i.css({"top":_h,"position":"absolute"});
			/*if (j > b) {
				var xh = j - b + 15;
				if (xh > (h+b)) {
					xh = (h-fh)+b;
				}
				i.css({"top":xh,"position":"absolute"});
			} else if (j > b) {
				//i.removeClass("sidebar_fixed");
				i.css({"top":j-b,"position":"absolute"});//(j-(h-fh))-b
			} */
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
	/*  编辑心情 */
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
			$("<textarea/>").appendTo(J_mood_content).text(htmlspecialchars_decode(contentHtml)).addClass("mood_content");
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
					$(this).siblings("#promote1").html("已经超出<span style='color:#de2c28;font-size:12px;'>" + num + "</span>个字");
				} else {
					if (curLength == 0) {
						if ($.trim($(this).val()) == "") {
							$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>1000</span>个字");
						} else {
							$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>999</span>个字");
						}
					} else {
						var num = 1000 - $.trim($(this).val()).length;
						$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + num + "</span>个字");
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
			$.jBox.tip("您还什么都没写呢", "warning");
			$(this).parent().show();
			$(this).parent().parent().find(".J-address").hide();
			return false;
		}
		if (mood_text.length > 1000) {
			$.jBox.tip("您输入心情过长", "success");
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
		$.getJSON(pathUpload + "?module=album&action=updatefeel", data,
		function(info) {
			if (info != null && info.result != null) {
				if (info.result) {
					$.jBox.tip("文字编辑成功。", "success");
					mood_complete.empty();
					mood_complete.html("<span>" + escapeHTML(mood_text) + "</span>");
					mood_complete.append("<span class='J-mood-edit travels_mood_add_edit edit'></span>");
					$(this).parent().parent().hide();
					$(this).parent().parent().find(".J-address").css("display", "block");
					moodEdit();
				} else {
					$.jBox.tip("文字编辑失败。", "success");
				}
			} else {
			    var url = window.location.href;
			    url = url.replace(/\/oscsid--.+\.html/i,'.html');
				window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(url);
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
			if (textMn == "您啥都没有写，写点吧亲") {
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
				picWholeContent.find("#psAddressIdAlbumPic").tokenInput(pathUpload + "?module=album&action=searchPOI", {
					theme: "albumpicdetail",
					tokenLimit: 1,
					prePopulate: [{
						id: paId,
						name: paName
					}]
				});
			} else {
				picWholeContent.find("#psAddressIdAlbumPic").tokenInput(pathUpload + "?module=album&action=searchPOI", {
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
					$(this).siblings("#promote1").html("已经超出<span style='color:#de2c28;font-size:12px;'>" + num + "</span>个字");
				} else {
					if (curLength == 0) {
						if ($.trim($(this).val()) == "") {
							$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
						} else {
							$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>139</span>个字");
						}
					} else {
						var num = 140 - $.trim($(this).val()).length;
						$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + num + "</span>个字");
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
			pic_content = "您啥都没有写，写点吧亲";
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
			$.jBox.tip("您输入描述过长", "warning");
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
		$.getJSON("lvtu/?module=album&action=editwebpic", data,function(info) {
			if (info != null && info.editstatus != null) {
				if (info.editstatus == 0) {
					$.jBox.tip("图片编辑成功。", "success");
					pic_complete.empty();
					pic_complete.text(desc);
					pic_complete.append("<span class='J-pic-edit travels_describe_edit edit'></span>");
					$(this).parent().hide();
					if (isaddress != "" && isaddressName != "") {
						pic_places.empty();
						//pic_places.append("<a href='web/sight.htm?sightId=" + isaddress + "' title='" + isaddressName + "'>" + isaddressName + "</a>");
						pic_places.append("<a href='" + info.href + "'>" + isaddressName + "</a>");
						
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
						window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(window.location.href);
					} else {
						$.jBox.tip("图片编辑失败。", "success");
						$(".piccancel").click();
					}
				}
			} else {
				$.jBox.tip("图片编辑失败。", "success");
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
					url: pathUpload + "?module=album&action=check",
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
				$.getJSON(pathUpload + "?module=album&action=saveMinAlbum", data,
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
					} else {
						
					}
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
						$("#lsAlbumNameHas").html("（<em class='orange'>" + eval(parseInt(albumNameL) + parseInt(num)) + "</em>字/" + albumNameL + "字，您已超过 <em class='orange'>" + num + "</em> 字）");
					} else {
						if (curLength == 0) {
							if ($.trim($(this).val()) == "") {} else {
								$(".textarea_error_album").html("还可以输入<span>" + albumNameL - 1 + "</span>个字");
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
							window.location.href = pathUpload + "album/userInfo/userId--" + info.userLoginId + '.html';
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
		$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
	});
	$("#Button3").click(function() {
		$(".pop_status").hide();
		$(".bg_mask").hide();
	});
	// 前一天按钮事件
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
		window.location.href = pathUpload + "album/show/albumid--" + albumId + "--dayid--" + dayNum + '.html';
	});
	// 后一天按钮事件
	$(".travels_nextDay").click(function() {
		var albumId = $("#albumId").val();
		var dayNum = $("#dayId").val();
		var random = new Date().getTime();
		var data = {
			albumId: albumId,
			dayNum: dayNum,
			random: random
		};
		window.location.href = pathUpload + "album/show/albumid--" + albumId + "--dayid--" + dayNum + ".html";
	});
	// 再看一次
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
		window.location.href = pathUpload + "album/show/albumId--" + albumId + ".html";
	});
	// 删除游记
	$(".travels_title_delete").click(function() {
		var id = $("#albumId").val();
		var data = {
			id: id,
			extcs: getExtcsValue()
		};
		
		var submit = function(v, h, f) {
			if (v == true) {
				$.getJSON(pathUpload + "?module=album&action=deletealbum", data, function(info) {
					if (info.delAlbumFlag == 0) {
						window.location.href = pathUpload + "album/userInfo/userId--" + info.userLoginId + ".html";
					} else {
						if (info.delAlbumFlag == 8000) {
							var temp = window.location.href;
							temp = temp.replace(/\/oscsid--.+\.html/i,'.html');
							window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(temp);
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
	$(".travels_title_upload").click(function() {
		var id = $("#albumIdInfo").val();
		if (id != null && id != "") {
			window.location.href = pathUpload + "album/addPicAlbum/id--" + id + '.html';
		} else {
			$.jBox.tip("增加游记图片异常，请重新刷新页面。", "success");
		}
	});
	// 删除图片，心情。
	$(".travels_pic_add_delete ,.travels_mood_add_delete, .travels_right_cont_delete").click(function() {
		var picId = $(this).parent().find("input[name='picId']").val();
		var id = $("#albumId").val();
		// 删除是的图片，还是心情
		var targetType = $(this).parent().find("input=[name='targeType']").val();
		var url = window.location.href;
		var strs = new Array();
		var after;
		strs = url.split("&im=");
		if (strs != null) {
			after = strs[0];
		}
		var _submit = function(v, h, f) {
			if (v == "ok") {
				var params = {
					picId: picId,
					extcs: getExtcsValue(),
					target: targetType
				};
				// 所有的删除动作都在这里
				$.getJSON("lvtu/?module=album&action=deletewebpic", params, function(data) {
					if (data.delstatus == 0) {
						$.jBox.tip("删除成功。", "success");
						if (after == "undefined" || typeof(after) == undefined || typeof(after) == "undefined") {
							window.location.href = pathUpload + "album/show/albumid--" + id + ".html";
						} else {
							window.location.href = after;
						}
					} else {
						if (data.delstatus == 8000) {
							var temp = window.location.href;
							temp = temp.replace(/\/oscsid--.+\.html/i,'.html');
							window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(temp) + "#" + params.picId;
						} else {
							$.jBox.tip("删除失败。", "success");
						}
					}
				});
			} else {
				if (v == "cancel") {}
			}
			return true;
		};
		$.jBox.confirm("确定删除？", "旅图", _submit);
	});
	$(".travels_op_pic").click(function() {
		var albumId = $("#albumIdInfo").val();
		var dayId = GetQueryString("dayId");
		if (dayId == null) {
			dayId = $("#firstDay").val();
		}
		var before = $(this).parent().find("input[name='before']").val();
		var after = $(this).parent().find("input[name='after']").val();
		var url = pathUpload + "album/addpicalbum/id--" + albumId;
		if (before != null && before != undefined && before != '') {
		    url += "--before--" + before;
		}
		if (after != null && after != undefined && after != '') {
		    url += "--after--" + after;
		}
		url += "--type--1--dayId--" + dayId + ".html";
		window.location.href = url;
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
		$(".mood_desc").bind("keyup paste keydown",	function() {
			$(this).siblings("#promote1").css("display", "block");
			var curLength = $.trim($(this).val()).length;
			if (curLength > 1000) {
				var num = $.trim($(this).val()).length - 1000;
				$(this).siblings("#promote1").html("已经超出<span style='color:#de2c28;font-size:12px;'>" + num + "</span>个字");
			} else {
				if (curLength == 0) {
					if ($.trim($(this).val()) == "") {
						$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>1000</span>个字");
					} else {
						$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>999</span>个字");
					}
				} else {
					var num = 1000 - $.trim($(this).val()).length;
					$(this).siblings("#promote1").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + num + "</span>个字");
				}
			}
		});
		cancelMood();
	});
	// 评论操作事件
	$(".travels_comment").click(function() {
		var visitorId = $("#visitorId").val();
		if (visitorId == null || visitorId == "") {
			var temp = window.location.href;
			temp = temp.replace(/\/oscsid--.+\.html/i,'.html');
			window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(temp);
		} else {
			var picContent = $(this).parent().parent().parent().parent();
			picId = picContent.find("input[name='picId']").val();
			$("#opType").val(picContent.find("input[name='targeType']").val());
			$(".bg_mask").css({'height':$(window).height(),'top':$(window).scrollTop()}).show();
			$(window).scroll(function() {
				$(".bg_mask").css({'position':'absolute','top':$(window).scrollTop()});
				popBox();
			});
			popBox();
			$(".pop_status").show();
			getCommentsByPicId(picId, $(this), $(this).parent().parent().parent().parent().find("input[name='targeType']").val());
		}
	});
	// 喜欢操作事件
	$(".travels_approve,.travels_approve_hover,.album_total").click(function() {
		var userId = $("#userid").val();
		if (userId != null && userId != "") {
			praisePic($(this)); // 调用添加喜欢
		} else {
			var albumId = GetQueryString("albumId",true); 
			if (albumId != null && albumId != "") {
				redirectUrl(pathUpload + "album/show/albumid--" + albumId + '.html', true);
			}
		}
		return false;
	});
	
	$("#textlimit").bind("keyup paste keydown", function() {
		var curLength = $.trim($("#textlimit").val()).length;
		if (curLength > 140) {
			var num = $.trim($("#textlimit").val()).length - 140;
			$("#prompt").html("已经超出<span style='color:#de2c28;font-size:12px;'>" + num + "</span>个字");
		} else {
			if (curLength == 0) {
				if ($.trim($("#textlimit").val()) == "") {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>140</span>个字");
				} else {
					$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>139</span>个字");
				}
			} else {
				var num = 140 - $.trim($("#textlimit").val()).length;
				$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + num + "</span>个字");
			}
		}
	});
	$(".travels_mode_pic a").click(function() {
		var firstPicId = $("#firstPicId").val();
		var albumid = $('#albumId').val();
		if (firstPicId == null || firstPicId == "" || albumid == null || albumid == '') {
			//window.location.href = pathUpload + "web/index.htm";
			window.location.href = pathUpload;
		} else {
			//window.location.href = pathUpload + "web/picWebDetail.htm?picId=" + firstPicId + "#" + firstPicId;
			window.location.href = pathUpload + 'album/picWebDetail/albumid--' + albumid + '--picId--' + firstPicId + '.html#' + firstPicId;
		}
		return false;
	});
	$(".travels_mode_map a").click(function() {
		var albumId = $("#albumId").val();
		var productId = $("#productId").val();
		if (albumId == null || albumId == "" || productId == null || productId == "") {
			window.location.href = pathUpload;// + "web/index.htm";
		} else {
			window.location.href = pathUpload + "index/index/productid--" + productId + ".html";
		}
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
		$.jBox.tip("您还什么都没写呢", "warning");
		return false;
	}
	if (e.length > 1000) {
		$.jBox.tip("您输入心情过长", "warning");
		return false;
	}
	var c = {
		albumId: g,
		before: d,
		after: f,
		desc: e,
		day: $('#albumCurrentDayTime').val() //by lwkai add
	};
	$.jBox.tip("正在提交...", "loading");
	$.getJSON("lvtu/?module=album&action=addfeel", c, function(h) {
		if (h.code == 0) {
			$.jBox.tip("添加心情成功", "success");
			if (h.id != -1) {
				//window.location.href = a[0] + "&im=" + h.id;
				window.location.reload();
			}
		} else {
			if (h.code == -1) {
			    var url = window.location.href;
			    url = url.replace(/\/oscsid--.+\.html/i,'.html');
				window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(url);
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
function getCommentsByPicId(e, b, t) {
	objComment = b;
	var d = 0;
	var g = $("#visitorId").val();
	var a = new Date();
	var c = a.getTime();
	var f = {
		visitorId: g,
		picId: e,
		random: c,
		target: t
	};
	// 取得评论总数，用在评论翻页位置
	$.getJSON("lvtu/?module=album&action=getcommentnum", f,	function(h) {
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
				getPageData(i, e, t);
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
	$.each(b.comments,function(c, d) {
		var e = "";
		if (d.userToReply != null && d.userToReply != "") {
			e += "<li class='userName'><a href='"+ pathUpload +"album/userInfo/userId--" + d.userInfo.userId + ".html'>" + d.userInfo.nickName + "</a> 回复 <a href='"+ pathUpload +"album/userInfo/userId--" + d.userToReply.userId + ".html'>" + d.userToReply.nickName + "</a>";
			e += "：" + d.content + "<em class='darkgray999 ml5 fn-cursor'>" + d.createTime + "</em>";
		} else {
			e += "<li class='userName'><a href='"+ pathUpload +"album/userInfo/userId--" + d.userInfo.userId + ".html'>" + d.userInfo.nickName + "</a>： " + d.content + "<em class='darkgray999 ml5'>" + d.createTime + "</em>";
		}
		e += "<span class=' mt5 deongaree fn-cursor' onclick=reply('" + d.userInfo.nickName + "','" + d.userInfo.userId + "','" + d.commentId + "')>";
		if ($("#visitorId").val() != d.userInfo.userId) {
			e += " 回复</span>";
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
	$("#textlimit").val("回复" + e + "：");
	var a = $("#textlimit").val().length;
	var d = 140 - Number(a);
	$("#prompt").html("还可以输入<span style='color:#0069ca;font-size:12px;'>" + d + "</span>个字");
	type = 2;
	comentId = b;
	userId = c;
}
function getPageData(a, d, t) {
	var f = $("#visitorId").val();
	var b = new Date();
	var c = b.getTime();
	var e = {
		visitorId: f,
		picId: d,
		pageNo: a,
		random: c,
		target: t
	};
	// 取得
	$.getJSON("lvtu/?module=album&action=comments", e, function(g) {
		fillPage(g);
		picReplyId = d;
	});
}
function comment() {
	var c = $("#textlimit").val();
	var d = $("#opType").val();
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
		picId: picId,
		content: c,
		targetType: d,
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
			url: "lvtu/?module=album&action=comment",
			data: e,
			dataType: "json",
			success: function(h) {
				if (h.result) {
					$.jBox.tip("评论成功", "success");
					$("#textlimit").val("");
					$("#prompt").text("还可以输入140个字");
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
		if (type == 2) {
			$.jBox.tip("正在提交...", "loading");
			var c = $("#textlimit").val();
			c = c.split("：")[1];
			e = {
				commentId: comentId,
				content: c,
				picReplyId: picReplyId,
				targetType: d,
				extcs: getExtcsValue(),
				random: b
			};
			$.getJSON("lvtu?module=album&action=savereply", e,
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
					$.jBox.tip("回复成功", "success");
				} else {
					$.jBox.tip("回复失败", "error");
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
		var url = k.replace(/\/oscsid--.+\.html/i,'.html');
		window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(url);
	} else {
		var h;
		var m = 1;
		var j;
		var b;
		if (l.hasClass("album_total")) { //这里添加是的对整个游记的喜欢
			h = $("#albumIdInfo").val();
			j = $("#albumIsPraised").val();
			b = 3;
		} else { // 这里才是具体某个照片的喜欢
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
			visitorId: i, // 用户ID
			picId: h, //游记ID
			targetType: b, //是对哪个标记喜欢 3 是游记 1 是游记中的某个图
			praiseActivityType: m, //喜欢动作类型    m为2则取消赞
			extcs: getExtcsValue(),
			staus: "00001",
			newHandler: a, // 具体的不清楚 这个值是干什么的
			random: e
		};
		// like
		setTimeout(function(){$.getJSON("lvtu/?module=album&action=praisepic", f,function(s) {
			if (s.status == 0) {
				if (b == 1) {
					var q = l.find(".pic_prai");
					var n = l.find(".like");
					var w = parseInt(q.text());
					var v = parseInt($(".album_total").text());
					if (m == 1) {
						q.text((w + 1).toString());
						$(".album_total").text((v + 1).toString());
						q.parent().attr("title", "已喜欢");
						n.text("已喜欢");
						q.parent().removeClass("travels_approve");
						q.parent().addClass("travels_approve_hover");
						var o = $("#newhandler").val();
						var t = o.substring(4, 5);
						if (t == 0 && $("#sinaBind").val() == "true" && first == 0) {
							$.jBox.tip("您的喜欢已同步到新浪微博，若您不想自动同步，请在'社交绑定'中取消", "success", {
								timeout: 5000
							});
							first = 1;
						} else {
							$.jBox.tip("称赞成功", "success");
						}
					} else {
						q.text((w - 1).toString());
						$(".album_total").text((v - 1).toString());
						q.parent().attr("title", "我很喜欢，支持一下");
						n.text("喜欢");
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
							u.parent().attr("title", "已喜欢");
							r.text("已喜欢");
							u.parent().removeClass("travels_approve");
							u.parent().addClass("travels_approve_hover");
							var o = $("#newhandler").val();
							var t = o.substring(4, 5);
							if (t == 0 && $("#sinaBind").val() == "true" && first == 0) {
								$.jBox.tip("您的喜欢已同步到新浪微博，若您不想自动同步，请在'社交绑定'中取消", "success", {
									timeout: 5000
								});
								first = 1;
							} else {
								$.jBox.tip("称赞成功", "success");
							}
						} else {
							u.text((w - 1).toString());
							$(".album_total").text((v - 1).toString());
							u.parent().attr("title", "我很喜欢，支持一下");
							r.text("喜欢");
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
								p.attr("title", "已喜欢");
								var o = $("#newhandler").val();
								var t = o.substring(4, 5);
								if (t == 0 && $("#sinaBind").val() == "true" && first == 0) {
									$.jBox.tip("您的喜欢已同步到新浪微博，若您不想自动同步，请在'社交绑定'中取消", "success", {
										timeout: 5000
									});
									first = 1;
								} else {
									$.jBox.tip("游记称赞成功", "success");
								}
							} else {
								p.text((v - 1).toString());
								p.attr("title", "我很喜欢，支持一下");
							}
							$("#albumIsPraised").val(m);
						}
					}
				}
			}
		});},300);
	}
}; 
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
} (jQuery)); 
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

(function(a) {
	a.fn.autoTextarea = function(b) {
		var d = {
			maxHeight: null,
			minHeight: a(this).height()
		};
		var c = a.extend({},d, b);
		return a(this).each(function() {
			if (a(this).html() != "") {
				a(this).height(this.scrollHeight);
			} else {}
			a(this).bind("paste cut keydown keyup focus blur",function() {
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


