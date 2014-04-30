
!function (window, document) {
	
	/**
	 * @class 日历类
	 * @param {object} options 初始化日历的设置选项，
	 *	{string} dateFormat : 日期格式(可选) 默认为YYYY-MM-DD，可选的有MM/DD/YYYY、DD/MM/YYYY、MM-DD-YYYY、DD-MM-YYYY、还有YYYY-MM-DD WEEK带星期的
	 *	{array} weekName : 星期的表示方式数组(可选) 默认为["周日", "周一", "周二", "周三", "周四", "周五", "周六"]
	 * @return {object}
	 */
	var Calendar = window.Calendar_flight = function (options) {
		this.init(options || {});
		this.createFrame();
	},
	
	//默认星期显示的名称
	defaultWeekName = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
	
	noop = function () {};
	
	Calendar.prototype = {
	
		/* @inner 初始化设置 */
		init : function (options) {
			this.datePicker = []; //存放日历选择器的数组
			this._clickEvent = noop;
			
			var dateFormat = this.dateFormat = options.dateFormat || "YYYY-MM-DD",
			oDate = new Date,
			today = oDate.getFullYear() + '-' + (oDate.getMonth() + 1) + '-' + oDate.getDate();
			
			this.options = {
				//acceptDate : [], 具体可以接收哪些日期
				//selectedDate : 默认选中的日期,默认为今天(可选)
				minDate : "1971-1-1", //最小接受的日期(可选)
				maxDate : "2100-1-1", //最大接受的日期(可选)
				dataNum : 2, 	//每次打开日历时显示几个月的数据(选择器个数)，默认为两个(可选)
				today : today, 	//今天日期(可选)
				weekName : options.weekName && options.weekName.length === 7 ? options.weekName : defaultWeekName
			};
		},
		
		/* @inner 创建容器 */
		createFrame : function () {
			var wrap = this.wrapper = document.createElement("div"),
			prev = this.prev = document.createElement("span"),
			next = this.next = document.createElement("span"),
			i;
			
			wrap.className = "cal-wrapper";
			prev.className = "prev";
			next.className = "next";
			
			try {
				i = this.iframe = document.createElement("<iframe src='#' frameborder='0';></iframe>");
				wrap.appendChild(i);
			} catch (e) {}
			
			wrap.appendChild(prev);
			wrap.appendChild(next);
			
			this.addListener();
		},
		
		/* @inner 添加事件监听 */
		addListener : function () {
			var obj = this;
			
			this.wrapper.onclick = function (e) {
				e = e || window.event;
				var src = e.target || e.srcElement;
				
				if (src = obj.canSelect(src)) {
					obj.close().input.value = obj.getValue(src);
					obj._clickEvent();
				}
				
				e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
			};
			
			this.prev.onclick = function () {
				obj.changeDate(-1);
			};
			
			this.next.onclick = function () {
				obj.changeDate(1);
			};
		},
		
		changeDate : function (num) {
			var opts = this.curr,
			date = opts.selectedDate.split('-');
			date[1] = +date[1] + num;
			while (date[1] > 12) {
				date[1] -= 12;
				++date[0];
			}
			while (date[1] < 1) {
				date[1] += 12;
				--date[0];
			}
			opts.selectedDate = date.join('-');
			this.open(this.input, opts);
		},
		
		/* @inner 判断点击的是否是可选择的日期 */
		canSelect : function (src) {
			var els = this.els,
			i = 0,
			el;
			
			while (el = els[i++]) {
				if (src === el || contains(el, src)) return el;
			}
			return false;
		},
		
		/* @inner 获取配置选项 */
		getOptions : function (options) {
			var ret = merge(this.options, options);
			
			if (!ret.selectedDate) {//如果没有默认选中日期 则设为今天
				ret.selectedDate = ret.today;
			}
			if (ret.acceptDate) {//如果有指定日期，判断默认选中日期是指定日期
				var meet = false;
				for (var i = 0, date; date = ret.acceptDate[i++]; ) {
					if (date === ret.selectedDate) {
						meet = true;
						break;
					}
				}
				if (!meet) ret.selectedDate = ret.acceptDate[0]; //如果不符合，则默认为接受日期数组中的第一个日期
			} else {
				//如果超出范围，则取临界点日期
				var d1 = getDateTime(ret.selectedDate),
				d2 = getDateTime(ret.minDate),
				d3 = getDateTime(ret.maxDate);
				
				if (d1 < d2) ret.selectedDate = ret.minDate;
				else if (d1 > d3) ret.selectedDate = ret.maxDate;
			}
			
			return ret;
		},
		
		/* @inner 移除日期选择器DOM节点 */
		delPicker : function () {
			var i = 0,
			el;
			while (el = this.datePicker[i++]) this.wrapper.removeChild(el.box);
			this.datePicker.length = 0;
		},
		
		/* @inner 获取日历选择器 */
		getPicker : function (opts) {
			var num = opts.dataNum,
			wrap = this.wrapper,
			arr = this.datePicker,
			els = [], //可选择日期的节点
			dp;
			
			for (; --num >= 0; ) {
				dp = arr[arr.length] = new DatePicker(opts, num);
				wrap.appendChild(dp.box);
				els = els.concat( this.getAccept(dp.box) );
			}
			this.els = els;
			
			wrap.style.width = opts.dataNum * dp.box.offsetWidth + "px";
			if (this.iframe) this.iframe.style.width = wrap.style.width;
		},
		
		/* @inner 获取可选择的日期的节点 */
		getAccept : function (obj) {
			var ret = [],
			els = obj.getElementsByTagName('a'),
			i = 0,
			el;
			
			while (el = els[i++]) {
				el.className.search(/accept|selected/) !== -1 && ret.push(el);
			}
			return ret;
		},
		
		/* @inner 获取日期的具体值 */
		getValue : function (el) {
			var oEl = el.parentNode.parentNode.parentNode,
			D = el.innerHTML,
			Y = oEl.getAttribute("YY"),
			M = oEl.getAttribute("MM");
			var WEEK = this.options.weekName[(new Date(Y,M,D)).getDay()];
			return getFormatDate(this.dateFormat, {
				"YYYY" : Y,
				"MM" : M,
				"DD" : D,
				"WEEK" : WEEK
			});
		},
		
		/**
		 * @outer 显示日历
		 * @param {DOMelement} obj 保存选中日期值的input框(必须) 将会设置成实例的input属性,可通过实例的["input"]下标访问
		 * @param {object} options 设置选项 &see 32~39行
		 */
		open : function (obj, options) {
			var opts = this.curr = this.getOptions(options);
			this.delPicker();
			this.wrapper.style.display = "block";
			this.getPicker(opts);
			this.input = obj;//保存为实例的input属性
		},
		
		/* @outer 关闭日历 */
		close : function () {
			this.wrapper.style.display = "none";
			return this;
		},
		
		/* @outer 把整个日历控件插入到指定DOM节点中 */
		appendTo : function (target) {
			target = target || document.body;//如果参数为空则默认添加到body中
			target.nodeType === 1 && target.appendChild(this.wrapper);
			return this;
		},
		
		/* @outer 日期点击回调 */
		click : function (callback) {
			if (typeof callback === "function") {
				this._clickEvent = callback;
			}
			return this;
		},
		
		/* @outer 点击指定的DOM节点,日历不会被关闭,参数为节点集合(array，可选) */
		notClickClose : function (nodelist) {
			nodelist = nodelist || [];
			var obj = this;
			
			if (!this._handler) {
				this._handler = function (e) {					
					if (obj.wrapper.style.display == "none") return;
					
					e = e || window.event;
					var src = e.target || e.srcElement,
					i = 0,
					el;
					
					if (src === obj.wrapper || contains(obj.wrapper, src)) {
						return;//如果是点击日历控件，不关闭
					}					
					if (src === obj.input) return;
					
					while (el = nodelist[i++]) {
						if (src === el || contains(el, src)) return;
					}
					
					obj.close();
				}
				addEvent(this._handler);
			}
		},
		
		/* @outer 移除该实例上的DOM */
		remove : function () {
			this.wrapper.parentNode.removeChild(this.wrapper);
			delEvent(this._handler);
		}
	};
	
	/**
	 * @class 日期选择器类
	 */
	var DatePicker = function (opts, index) {
		this.init(opts, index);
		this.createModuels();
	},
	 
	//月份背景图片位置
	monthBg = {
		1 : "50px 60px", 2 : "-130px 60px", 3 : "-300px 60px",
		4 : "50px -100px", 5 : "-130px -100px", 6 : "-300px -100px",
		7 : "50px -250px", 8 : "-130px -250px", 9 : "-300px -250px",
		10 : "50px -410px", 11 : "-130px -410px", 12 : "-300px -410px"
	};
	 
	DatePicker.prototype = {
		init : function (opts, index) {
			var arr = opts.selectedDate.split('-');
			arr[1] = arr[1] - 1 + index;
			arr = getValidDate(arr);
			
			this.Y = arr[0];
			this.M = arr[1];
			
			while (this.M > 11) {
				this.M = this.M - 12;
				this.Y++;
			}
			
			this.minDate = getDateTime(opts.minDate);
			this.maxDate = getDateTime(opts.maxDate);
			this.selected = getDateTime(opts.selectedDate);
			this.weekName = opts.weekName;
			
			if (opts.acceptDate) {
				this.setAcceptDate(opts.acceptDate);
			}
		},
		setAcceptDate : function (arr) {
			var obj = this.acceptDate = {},
			i = 0,
			date;
			
			while (date = arr[i++]) obj[ getDateTime(date) ] = true;
		},
		createModuels : function () {
			var box = this.box = document.createElement("div"),
			html = [
				this.getHeader(),
				this.getTable()
			];
			
			box.className = "date-picker";
			box.innerHTML = html.join('');
			box.style.backgroundPosition = monthBg[ this.M + 1 ];
		},
		getHeader : function () {
			return ["<div class='head'>", this.Y, '/', this.M + 1,"</div>"].join('');
		},
		getTable : function () {
			var thead = ["<thead><tr>"],
			i = 0;
			
			for (; i < 7; i++) {
				thead.push( "<th>" , this.weekName[i] , "</th>" );
			}
			thead[thead.length] = "</tr></thead>";
			
			return "<table>" + thead.join('') + this.getTbody() + "</table>";
		},
		getTbody : function () {
			var tbody = ["<tbody YY='", this.Y,"' MM='", this.M + 1,"'></tr>"],
			start = this.startDate(),
			total = this.totalDate(),
			len = start + total,	//一共往日期块中插入的节点数
			date = '',	//日期值索引
			i = 0;
			
			for (; i < len; i++) {
				var time = 0,
				cls = "";
				
				//达到本月的第一天 ，开始计日期值
				if (i == start) date = 1;
				
				//如果每行添加了7个节点，则换行，保持每行显示7天
				if (i % 7 == 0 && i != 0) tbody[tbody.length] = "</tr><tr>";
				
				if (!!date) time = this.getTime(date);
				
				if (this.acceptDate) {
					cls = time in this.acceptDate ? "accept" : "notallowed";
				} else {
					if (time < this.minDate || time > this.maxDate) {
						cls = "notallowed";
					} else if (time == this.selected) {
						cls = "selected";
					} else {
						cls = "accept";
					}
				}
				
				tbody[tbody.length] = "<td><a hideFocus='true' href='javascript:void(0)' class='" + cls + "'>" + date + "</a></td>";
				
				!!date && date++;//如果已开始计日期值，则自增
			}
			tbody[tbody.length] = "</tr></tbody>";
			
			return tbody.join('');
		},
		
		/* 获取本月第一天是星期几 */
		startDate : function () {
			return new Date(this.Y, this.M, 1).getDay();
		},
		
		/* 获取本月总天数 */
		totalDate : function () {
			return new Date(this.Y, this.M + 1, 0).getDate();
		},
		
		getTime : function (date) {
			return new Date(this.Y, this.M, date).getTime();
		}
	};
	 
	
	/* ******工具方法******* */
	
	/* 获取对应格式化日期字符串 */
	function getFormatDate(dateFormat, oDate) {
		return dateFormat.replace(/\w+/g, function (m) {
			return oDate[m];
		});
	}
	
	/* 合并对象属性 */
	function merge() {
		var ret = {}, i = 0, src, k;
		
		for (; src = arguments[i++]; ) {
			for (k in src) {
				if (src.hasOwnProperty(k)) ret[k] = src[k];
			}
		}
		return ret;
	}
	
	/* 得到合法年月 */
	function getValidDate(arr) {
		while (arr[1] > 11) {
			arr[1] = arr[1] - 12;
			arr[0]++;
		}
		return arr;
	}
	
	/* 根据日期获取毫秒级时间值 */
	function getDateTime(date) {
		var arr = date.split('-');
		return new Date(arr[0], arr[1] - 1, arr[2]).getTime();
	}
	
	var head = document.getElementsByTagName("head")[0],
	
	/* 判断a节点是否包含b节点 */
	contains = head.contains ? function (a, b) {
		return a !== b && a.contains && a.contains(b);
	} : function (a, b) {
		return !!(a.compareDocumentPosition(b) & 16);
	},
	
	add = head.dispatchEvent ? "addEventListener" : "attachEvent",
	del = head.dispatchEvent ? "removeEventListener" : "detachEvent",
	type = head.dispatchEvent ? "mouseup" : "onmouseup",
	fns = [],
	binded,
	
	_handler = function () {
		var fn, i = 0;
		while (fn = fns[i++]) fn.apply(document, arguments);
	},
	
	addEvent = function (handler) {
		fns[fns.length] = handler;
		if (!binded) {
			binded = true;
			document[add](type, _handler, false);
		}
	},
	delEvent = function (handler) {
		var fn, i = 0;
		while (fn = fns[i++]) {
			if (fn === handler) {
				fns.splice(--i, 1);
				break;
			}
		}
		if (!fns.length) document[del](type, _handler, false)
	}

}(this, document);