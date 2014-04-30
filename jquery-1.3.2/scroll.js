/**
 * scroll (Version 1.0)
 * http://cn.usitrip.com
 * @author Rocky (296456018@qq.com)
 *
 * Create a scroll
 * @example new Scroll("Scroll");
 * on jQuery
 * date: 2011-4-13
 */

function Scroll(scrollId) {
	this.allHeight = 0;
	this.scrollIndex = 0;
	this.sbArr = new Array();
	this.scroll = jQuery("#" + scrollId);
	this.fixedHeight = 238;//126
	this.nextBtn = jQuery("#NextBtn");
	this.preBtn = jQuery("#PreBtn");
	this.scrollCon = jQuery("#" + scrollId + ">ul");
	this.scrollConLi = jQuery("#" + scrollId + ">ul>li");
	this.init();
	jQuery("#" + scrollId + ">ul>li:first").addClass("on");
	jQuery("#" + scrollId + ">ul>li").hover(function() {
		jQuery("#" + scrollId + ">ul>li").removeClass("on");
		jQuery(this).addClass("on");
	})
}
Scroll.prototype = {
	init: function() {
		var that = this;
		this.scrollConLi.each(function(index) {
			that.sbArr.push(this);
			that.sbArr[that.sbArr.length - 1].scrollFlag = that.allHeight;
			that.allHeight = that.allHeight + that.sbArr[that.sbArr.length - 1].offsetHeight;
		});
		this.nextBtn.click(function(event) {
			that._goNext();
		}),
		this.preBtn.click(function(event) {
			that._goPrevious();
		}),
		this._changeBtn();
	},
	_changeBtn: function() {
		if (this.scrollIndex < 1) {
			this.preBtn.removeClass("leftOn");
		} else {
			this.preBtn.addClass("leftOn");
		}

		if (this.allHeight - this.sbArr[this.scrollIndex].scrollFlag - this.fixedHeight > 0) {
			this.nextBtn.addClass("rightOn");
		} else {
			this.nextBtn.removeClass("rightOn");
		}
	},
	_goNext: function() {
		var that = this;
		if (this.allHeight - this.sbArr[this.scrollIndex].scrollFlag - this.fixedHeight > 0) {
			this.scrollIndex++;
			this._moveBox(this.sbArr[this.scrollIndex].scrollFlag);
		    this._changeBtn();
			this._act();
		};
	},

	_goPrevious: function() {
		if (this.scrollIndex > 0) {
			this.scrollIndex--;
			this._moveBox(this.sbArr[this.scrollIndex].scrollFlag);
			this._changeBtn();
			this._act();
		}
	},
	_moveBox: function(scrollFlag) {
		this.scrollCon.stop().animate({
			top: -scrollFlag
		},
		300);
		this._changeBtn();
	},
	_act: function() {
		var that = this;
		this.scrollConLi.removeClass("on");
		this.scrollConLi.each(function(index) {
			if (index == that.scrollIndex) {
				that.scrollConLi.eq(index).addClass("on");
				jQuery("#BigImage a:eq(" + jQuery("#Scroll li").index(this) + ")").show().siblings("#BigImage a").hide();
			}
		});
	}
}