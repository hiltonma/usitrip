/*  
 * 根据输入的值，查找出类似的内容，列给用户，有点像模拟下拉
 */
;(function($) {
    $.fn.extend({
        autocomplete: function(urlOrData, options) {
            var isUrl = typeof urlOrData == "string";
            options = $.extend({},
            $.Autocompleter.defaults, {
                url: isUrl ? urlOrData: null,
                data: isUrl ? null: urlOrData,
                delay: isUrl ? $.Autocompleter.defaults.delay: 10,
                max: options && !options.scroll ? 10 : 150
            },
            options);
            options.highlight = options.highlight ||
            function(value) {
                return value
            };
            options.formatMatch = options.formatMatch || options.formatItem;
            return this.each(function() {
                new $.Autocompleter(this, options)
            })
        },
        result: function(handler) {
            return this.bind("result", handler)
        },
        search: function(handler) {
            return this.trigger("search", [handler])
        },
        flushCache: function() {
            return this.trigger("flushCache")
        },
        setOptions: function(options) {
            return this.trigger("setOptions", [options])
        },
        unautocomplete: function() {
            return this.trigger("unautocomplete")
        }
    });
    $.Autocompleter = function(input, options) {
        var KEY = {
            UP: 38,
            DOWN: 40,
            DEL: 46,
            TAB: 9,
            RETURN: 13,
            ESC: 27,
            COMMA: 188,
            PAGEUP: 33,
            PAGEDOWN: 34,
            BACKSPACE: 8,
            SPACEKEY: 32
        };
        var $input = $(input).attr("autocomplete", "off").addClass(options.inputClass);
        var dataurl = $(input).attr("dataurl");
        if (dataurl == null || dataurl == '') {
            $(input).attr("dataurl", options.url)
        }
        var timeout;
        var previousValue = "";
        var cache = $.Autocompleter.Cache(options, input);
        var hasFocus = 0;
        var lastKeyPressCode;
        var config = {
            mouseDownOnSelect: false
        };
        var select = $.Autocompleter.Select(options, input, selectCurrent, config);
        var blockSubmit;
        $.browser.opera && $(input.form).bind("submit.autocomplete",
        function() {
            if (blockSubmit) {
                blockSubmit = false;
                return false
            }
        });
        $input.bind(($.browser.opera ? "keypress": "keydown") + ".autocomplete",
        function(event) {
            hasFocus = 1;
            lastKeyPressCode = event.keyCode;
            switch (event.keyCode) {
            case KEY.UP:
                event.preventDefault();
                if (select.visible()) {
                    select.prev()
                } else {
                    onChange(0, true)
                }
                break;
            case KEY.DOWN:
                event.preventDefault();
                if (select.visible()) {
                    select.next()
                } else {
                    onChange(0, true)
                }
                break;
            case KEY.PAGEUP:
                event.preventDefault();
                if (select.visible()) {
                    select.pageUp()
                } else {
                    onChange(0, true)
                }
                break;
            case KEY.PAGEDOWN:
                event.preventDefault();
                if (select.visible()) {
                    select.pageDown()
                } else {
                    onChange(0, true)
                }
                break;
            case options.multiple && $.trim(options.multipleSeparator) == "," && KEY.COMMA: case KEY.TAB:
            case KEY.RETURN:
                if (selectCurrent()) {
                    event.preventDefault();
                    if (this.parentNode.tagName.toLowerCase() == "form") {
                        this.parentNode.submit()
                    }
                    return false
                }
                break;
            case KEY.ESC:
                select.hide();
                break;
            default:
                clearTimeout(timeout);
                timeout = setTimeout(onChange, options.delay);
                break
            }
        }).focus(function() {
            hasFocus++
        }).blur(function() {
            hasFocus = 0;
            if (!config.mouseDownOnSelect) {
                hideResults()
            }
        }).click(function() {
            if (hasFocus++>0 && !select.visible()) {
                onChange(0, true)
            }
        }).bind("search",
        function() {
            var fn = (arguments.length > 1) ? arguments[1] : null;
            function findValueCallback(q, data) {
                var result;
                if (data && data.length) {
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].result.toLowerCase() == q.toLowerCase()) {
                            result = data[i];
                            break
                        }
                    }
                }
                if (typeof fn == "function") fn(result);
                else $input.trigger("result", result && [result.data, result.value])
            }
            $.each(trimWords($input.val()),
            function(i, value) {
                request(value, findValueCallback, findValueCallback)
            })
        }).bind("flushCache",
        function() {
            cache.flush()
        }).bind("setOptions",
        function() {
            $.extend(options, arguments[1]);
            if ("data" in arguments[1]) cache.populate()
        }).bind("unautocomplete",
        function() {
            select.unbind();
            $input.unbind();
            $(input.form).unbind(".autocomplete")
        });
        function selectCurrent() {
            var selected = select.selected();
            if (!selected) return false;
            var v = selected.result;
            previousValue = v;
            if (options.multiple) {
                var words = trimWords($input.val());
                if (words.length > 1) {
                    var seperator = options.multipleSeparator.length;
                    var cursorAt = $(input).selection().start;
                    var wordAt, progress = 0;
                    $.each(words,
                    function(i, word) {
                        progress += word.length;
                        if (cursorAt <= progress) {
                            wordAt = i;
                            return false
                        }
                        progress += seperator
                    });
                    words[wordAt] = v;
                    v = words.join(options.multipleSeparator)
                }
                v += options.multipleSeparator
            }
            $input.val(v);
            hideResultsNow();
            $input.trigger("result", [selected.data, selected.value]);
            return true
        }
        function onChange(crap, skipPrevCheck) {
            if (lastKeyPressCode == KEY.DEL) {
                select.hide();
                return
            }
            var currentValue = $input.val();
            if ((!skipPrevCheck) && currentValue == previousValue) return;
            previousValue = currentValue;
            currentValue = lastWord(currentValue);
            if (currentValue.length >= options.minChars) {
                $input.addClass(options.loadingClass);
                if (!options.matchCase) currentValue = currentValue.toLowerCase();
                request(currentValue, receiveData, hideResultsNow)
            } else {
                stopLoading();
                select.hide()
            }
        };
        function trimWords(value) {
            if (!value) return [""];
            if (!options.multiple) return [$.trim(value)];
            return $.map(value.split(options.multipleSeparator),
            function(word) {
                return $.trim(value).length ? $.trim(word) : null
            })
        }
        function lastWord(value) {
            if (!options.multiple) return value;
            var words = trimWords(value);
            if (words.length == 1) return words[0];
            var cursorAt = $(input).selection().start;
            if (cursorAt == value.length) {
                words = trimWords(value)
            } else {
                words = trimWords(value.replace(value.substring(cursorAt), ""))
            }
            return words[words.length - 1]
        }
        function autoFill(q, sValue) {
            if (options.autoFill && (lastWord($input.val()).toLowerCase() == q.toLowerCase()) && lastKeyPressCode != KEY.BACKSPACE) {
                $input.val($input.val() + sValue.substring(lastWord(previousValue).length));
                $(input).selection(previousValue.length, previousValue.length + sValue.length)
            }
        };
        function hideResults() {
            clearTimeout(timeout);
            timeout = setTimeout(hideResultsNow, 200)
        };
        function hideResultsNow() {
            var wasVisible = select.visible();
            select.hide();
            clearTimeout(timeout);
            stopLoading();
            if (options.mustMatch) {
                $input.search(function(result) {
                    if (!result) {
                        if (options.multiple) {
                            var words = trimWords($input.val()).slice(0, -1);
                            $input.val(words.join(options.multipleSeparator) + (words.length ? options.multipleSeparator: ""))
                        } else {
                            $input.val("");
                            $input.trigger("result", null)
                        }
                    }
                })
            }
        };
        function receiveData(q, data) {
            if (data && data.length && hasFocus) {
                stopLoading();
                select.display(data, q);
                autoFill(q, data[0].value);
                select.show()
            } else {
                hideResultsNow()
            }
        };
        function request(term, success, failure) {
            if (!options.matchCase) term = term.toLowerCase();
            var data = cache.load(term);
            if (data && data.length) {
                success(term, data)
            } else if ((typeof $(input).attr("dataurl") == "string") && ($(input).attr("dataurl").length > 0)) {
                var extraParams = {
                    timestamp: +new Date()
                };
                $.each(options.extraParams,
                function(key, param) {
                    extraParams[key] = typeof param == "function" ? param() : param
                });
                $.ajax({
                    mode: "abort",
                    port: "autocomplete" + input.name,
                    dataType: options.dataType,
                    url: $(input).attr("dataurl"),
                    type: "post",
                    data: $.extend({
                        val: lastWord(term),
                        limit: options.max
                    },
                    extraParams),
                    success: function(data) {
                        var parsed = options.parse && options.parse(data) || parse(data);
                        cache.add(term, parsed);
                        success(term, parsed)
                    }
                })
            } else {
                select.emptyList();
                failure(term)
            }
        };
        function parse(data) {
            var parsed = [];
            var rows = data.split("\n");
            for (var i = 0; i < rows.length; i++) {
                var row = $.trim(rows[i]);
                if (row) {
                    row = row.split("|");
                    parsed[parsed.length] = {
                        data: row,
                        value: row[0],
                        result: options.formatResult && options.formatResult(row, row[0]) || row[0]
                    }
                }
            }
            return parsed
        };
        function stopLoading() {
            $input.removeClass(options.loadingClass)
        }
    };
    $.Autocompleter.defaults = {
        inputClass: "ac_input",
        resultsClass: "ac_results",
        loadingClass: "ac_loading",
        minChars: 0,
        delay: 400,
        matchCase: false,
        matchSubset: true,
        matchContains: false,
        cacheLength: 10,
        max: 100,
        mustMatch: false,
        extraParams: {},
        selectFirst: true,
        formatItem: function(row) {
            return row[0]
        },
        formatMatch: null,
        autoFill: false,
        width: 0,
        multiple: false,
        multipleSeparator: ", ",
        highlight: function(value, term) {
            return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
        },
        scroll: false,
        scrollHeight: 180,
        inputDefaultsVal: false
    };
    $.Autocompleter.Cache = function(options, input) {
        var data = {};
        var length = 0;
        function matchSubset(s, sub) {
            if (!options.matchCase) s = s.toLowerCase();
            var i = s.indexOf(sub);
            if (options.matchContains == "word") {
                i = s.toLowerCase().search("\\b" + sub.toLowerCase());
            }
            if (i == -1) return false;
            return i == 0 || options.matchContains;
        };
        function add(q, value) {
            if (length > options.cacheLength) {
                flush();
            }
            if (!data[q]) {
                length++;
            }
            data[q] = value;
        }
        function populate() {
            if (!options.data) return false;
            var stMatchSets = {},
            nullData = 0;
            if (!$(input).attr("dataurl")) options.cacheLength = 1;
            stMatchSets[""] = [];
            for (var i = 0,
            ol = options.data.length; i < ol; i++) {
                var rawValue = options.data[i];
                rawValue = (typeof rawValue == "string") ? [rawValue] : rawValue;
                var value = options.formatMatch(rawValue, i + 1, options.data.length);
                if (value === false) continue;
                var firstChar = value.charAt(0).toLowerCase();
                if (!stMatchSets[firstChar]) stMatchSets[firstChar] = [];
                var row = {
                    value: value,
                    data: rawValue,
                    result: options.formatResult && options.formatResult(rawValue) || value
                };
                stMatchSets[firstChar].push(row);
                if (nullData++<options.max) {
                    stMatchSets[""].push(row);
                }
            };
            $.each(stMatchSets,
            function(i, value) {
                options.cacheLength++;
                add(i, value);
            });
        }
        setTimeout(populate, 25);
        function flush() {
            data = {};
            length = 0;
        }
        return {
            flush: flush,
            add: add,
            populate: populate,
            load: function(q) {
                if (!options.cacheLength || !length) return null;
                if (!$(input).attr("dataurl") && options.matchContains) {
                    var csub = [];
                    for (var k in data) {
                        if (k.length > 0) {
                            var c = data[k];
                            $.each(c,
                            function(i, x) {
                                if (matchSubset(x.value, q)) {
                                    csub.push(x);
                                }
                            });
                        }
                    }
                    return csub;
                } else if (data[q]) {
                    return data[q];
                } else if (options.matchSubset) {
                    for (var i = q.length - 1; i >= options.minChars; i--) {
                        var c = data[q.substr(0, i)];
                        if (c) {
                            var csub = [];
                            $.each(c,
                            function(i, x) {
                                if (matchSubset(x.value, q)) {
                                    csub[csub.length] = x;
                                }
                            });
                            return csub;
                        }
                    }
                }
                return null;
            }
        };
    };
    $.Autocompleter.Select = function(options, input, select, config) {
        var CLASSES = {
            ACTIVE: "ac_over"
        };
        var listItems, active = -1,
        data, term = "",
        needsInit = true,
        element, list;
        function init() {
            if (!needsInit) return;
            element = $("<div/>").hide().addClass(options.resultsClass).css("position", "absolute").appendTo(document.body);
            list = $("<ul/>").appendTo(element).mouseover(function(event) {
                if (target(event).nodeName && target(event).nodeName.toUpperCase() == 'LI') {
                    active = $("li", list).removeClass(CLASSES.ACTIVE).index(target(event));
                    $(target(event)).addClass(CLASSES.ACTIVE);
                }
            }).click(function(event) {
                $(target(event)).addClass(CLASSES.ACTIVE);
                select();
                input.focus();
                return false;
            }).mousedown(function() {
                config.mouseDownOnSelect = true;
            }).mouseup(function() {
                config.mouseDownOnSelect = false;
            });
            var awidth = $(input).attr("awidth");
            if (awidth == null || awidth == '' || awidth < 1) {
                awidth = $(input).outerWidth(true) - 2;
                $(input).attr("awidth", awidth);
            }
            element.css("width", awidth + 'px');
            if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
                $("<iframe style='position:absolute; display:block; top:0; left:-2px; width:100%; height:100px; z-index:-1; filter:mask();'></iframe>").appendTo(element);
            }
            needsInit = false;
        }
        function target(event) {
            var element = event.target;
            while (element && element.tagName != "LI") element = element.parentNode;
            if (!element) return [];
            return element;
        }
        function moveSelect(step) {
            listItems.slice(active, active + 1).removeClass(CLASSES.ACTIVE);
            movePosition(step);
            var activeItem = listItems.slice(active, active + 1).addClass(CLASSES.ACTIVE);
            if (options.scroll) {
                var offset = 0;
                listItems.slice(0, active).each(function() {
                    offset += this.offsetHeight;
                });
                if ((offset + activeItem[0].offsetHeight - list.scrollTop()) > list[0].clientHeight) {
                    list.scrollTop(offset + activeItem[0].offsetHeight - list.innerHeight());
                } else if (offset < list.scrollTop()) {
                    list.scrollTop(offset);
                }
            }
        };
        function movePosition(step) {
            active += step;
            if (active < 0) {
                active = listItems.size() - 1;
            } else if (active >= listItems.size()) {
                active = 0;
            }
        }
        function limitNumberOfItems(available) {
            return options.max && options.max < available ? options.max: available;
        }
        function fillList() {
            list.empty();
            var max = limitNumberOfItems(data.length);
            for (var i = 0; i < max; i++) {
                if (!data[i]) continue;
                var formatted = options.formatItem(data[i].data, i + 1, max, data[i].value, term);
                if (formatted === false) continue;
                if (typeof options.resultsClass != "ac_results") {
                    var li = $("<li/>").html(options.highlight(formatted, term)).appendTo(list)[0];
                } else {
                    var li = $("<li/>").html(options.highlight(formatted, term)).addClass(i % 2 == 0 ? "ac_even": "ac_odd").appendTo(list)[0];
                }
                $.data(li, "ac_data", data[i]);
            }
            listItems = list.find("li");
            if (options.selectFirst) {
                listItems.slice(0, 1).addClass(CLASSES.ACTIVE);
                active = 0;
            }
            if ($.fn.bgiframe) list.bgiframe();
        }
        return {
            display: function(d, q) {
                init();
                data = d;
                term = q;
                fillList();
            },
            next: function() {
                moveSelect(1);
            },
            prev: function() {
                moveSelect( - 1);
            },
            pageUp: function() {
                if (active != 0 && active - 8 < 0) {
                    moveSelect( - active);
                } else {
                    moveSelect( - 8);
                }
            },
            pageDown: function() {
                if (active != listItems.size() - 1 && active + 8 > listItems.size()) {
                    moveSelect(listItems.size() - 1 - active);
                } else {
                    moveSelect(8);
                }
            },
            hide: function() {
                element && element.hide();
                listItems && listItems.removeClass(CLASSES.ACTIVE);
                active = -1;
            },
            visible: function() {
                return element && element.is(":visible");
            },
            current: function() {
                return this.visible() && (listItems.filter("." + CLASSES.ACTIVE)[0] || options.selectFirst && listItems[0]);
            },
            show: function() {
                var offset = $(input).offset();
                var awidth = $(input).attr("awidth");
                if (awidth == null || awidth == '' || awidth < 1) {
                    awidth = $(input).outerWidth(true) - 2;
                    $(input).attr("awidth", awidth);
                }
                element.css({
                    width: awidth + 'px',
                    top: offset.top + input.offsetHeight,
                    left: offset.left
                }).show();
                if (options.scroll) {
                    list.scrollTop(0);
                    list.css({
                        maxHeight: options.scrollHeight,
                        overflow: 'auto'
                    });
                    if ($.browser.msie && typeof document.body.style.maxHeight === "undefined") {
                        var listHeight = 0;
                        listItems.each(function() {
                            listHeight += this.offsetHeight;
                        });
                        var scrollbarsVisible = listHeight > options.scrollHeight;
                        list.css('height', scrollbarsVisible ? options.scrollHeight: listHeight);
                        if (!scrollbarsVisible) {
                            listItems.width(list.width() - parseInt(listItems.css("padding-left")) - parseInt(listItems.css("padding-right")));
                        }
                    }
                }
            },
            selected: function() {
                var selected = listItems && listItems.filter("." + CLASSES.ACTIVE).removeClass(CLASSES.ACTIVE);
                return selected && selected.length && $.data(selected[0], "ac_data");
            },
            emptyList: function() {
                list && list.empty();
            },
            unbind: function() {
                element && element.remove();
            }
        };
    };
    $.fn.selection = function(start, end) {
        if (start !== undefined) {
            return this.each(function() {
                if (this.createTextRange) {
                    var selRange = this.createTextRange();
                    if (end === undefined || start == end) {
                        selRange.move("character", start);
                        selRange.select();
                    } else {
                        selRange.collapse(true);
                        selRange.moveStart("character", start);
                        selRange.moveEnd("character", end);
                        selRange.select();
                    }
                } else if (this.setSelectionRange) {
                    this.setSelectionRange(start, end);
                } else if (this.selectionStart) {
                    this.selectionStart = start;
                    this.selectionEnd = end;
                }
            });
        }
        var field = this[0];
        if (field.createTextRange) {
            var range = document.selection.createRange(),
            orig = field.value,
            teststring = "<->",
            textLength = range.text.length;
            range.text = teststring;
            var caretAt = field.value.indexOf(teststring);
            field.value = orig;
            this.selection(caretAt, caretAt + textLength);
            return {
                start: caretAt,
                end: caretAt + textLength
            }
        } else if (field.selectionStart !== undefined) {
            return {
                start: field.selectionStart,
                end: field.selectionEnd
            }
        }
    };
})(jQuery); 
(function($) {
    var ajax = $.ajax;
    var pendingRequests = {};
    var synced = [];
    var syncedData = [];
    $.ajax = function(settings) {
        settings = jQuery.extend(settings, jQuery.extend({},
        jQuery.ajaxSettings, settings));
        var port = settings.port;
        switch (settings.mode) {
        case "abort":
            if (pendingRequests[port]) {
                pendingRequests[port].abort();
            }
            return pendingRequests[port] = ajax.apply(this, arguments);
        case "queue":
            var _old = settings.complete;
            settings.complete = function() {
                if (_old) _old.apply(this, arguments);
                jQuery([ajax]).dequeue("ajax" + port);;
            };
            jQuery([ajax]).queue("ajax" + port,
            function() {
                ajax(settings);
            });
            return;
        case "sync":
            var pos = synced.length;
            synced[pos] = {
                error: settings.error,
                success: settings.success,
                complete: settings.complete,
                done: false
            };
            syncedData[pos] = {
                error: [],
                success: [],
                complete: []
            };
            settings.error = function() {
                syncedData[pos].error = arguments
            };
            settings.success = function() {
                syncedData[pos].success = arguments
            };
            settings.complete = function() {
                syncedData[pos].complete = arguments;
                synced[pos].done = true;
                if (pos == 0 || !synced[pos - 1]) for (var i = pos; i < synced.length && synced[i].done; i++) {
                    if (synced[i].error) synced[i].error.apply(jQuery, syncedData[i].error);
                    if (synced[i].success) synced[i].success.apply(jQuery, syncedData[i].success);
                    if (synced[i].complete) synced[i].complete.apply(jQuery, syncedData[i].complete);
                    synced[i] = null;
                    syncedData[i] = null
                }
            }
        }
        return ajax.apply(this, arguments)
    }
})(jQuery);
/*
 * 弹出提示框
 */
(function(a) {
    a.jBox = function(b, c) {
        c = a.extend({}, a.jBox.defaults, c);
        c.showFade = c.opacity > 0x0;
        c.isTip = c.isTip || false;
        c.isMessager = c.isMessager || false;
        if (b == undefined) {
            b = ''
        };
        if (c.border < 0x0) {
            c.border = 0x0
        };
        if (c.id == undefined) {
            c.id = 'jBox_' + Math.floor(Math.random() * 0xf4240)
        };
        var d = (a.browser.msie && parseInt(a.browser.version) < 0x7);
        var e = a('#' + c.id);
        if (e.length > 0x0) {
            c.zIndex = a.jBox.defaults.zIndex++;
            e.css({zIndex: c.zIndex});
            e.find('#jbox').css({zIndex: c.zIndex + 0x1});
            return e
        };
        var f = {
            url: '',
            type: '',
            html: '',
            isObject: b.constructor == Object
        };
        if (!f.isObject) {
            b = b + '';
            var N = b.toLowerCase();
            if (N.indexOf('id:') == 0x0) f.type = 'ID';
            else if (N.indexOf('get:') == 0x0) f.type = 'GET';
            else if (N.indexOf('post:') == 0x0) f.type = 'POST';
            else if (N.indexOf('iframe:') == 0x0) f.type = 'IFRAME';
            else if (N.indexOf('html:') == 0x0) f.type = 'HTML';
            else {
                b = 'html:' + b;
                f.type = 'HTML'
            };
            b = b.substring(b.indexOf(":") + 0x1, b.length)
        };
        if (!c.isTip && !c.isMessager && !c.showScrolling) {
            a(a.browser.msie ? 'html': 'body').attr('style', 'overflow:hidden;padding-right:17px;')
        };
        var g = !c.isTip && !(c.title == undefined);
        var h = f.type == 'GET' || f.type == 'POST' || f.type == 'IFRAME';
        var i = typeof c.width == 'number' ? (c.width - 0x32) + 'px': "90%";
        var j = [];
        j.push('<div id="' + c.id + '" class="jbox-' + (c.isTip ? 'tip': (c.isMessager ? 'messager': 'body')) + '">');
        if (c.showFade) {
            if ((d && a('iframe').length > 0x0) || a('object, applet').length > 0x0) {
                j.push('<iframe id="jbox-fade" class="jbox-fade" src="about:blank" style="display:block;position:absolute;z-index:-1;"></iframe>')
            } else {
                if (d) {
                    a('select').css('visibility', 'hidden')
                };
                j.push('<div id="jbox-fade" class="jbox-fade" style="position:absolute;"></div>')
            }
        };
        j.push('<div id="jbox-temp" class="jbox-temp" style="width:0px;height:0px;background-color:#ff3300;position:absolute;z-index:1984;fdisplay:none;"></div>');
        if (c.draggable) {
            j.push('<div id="jbox-drag" class="jbox-drag" style="position:absolute;z-index:1984;display:none;"></div>')
        };
        
        j.push('<div id="jbox" class="jbox" style="position:absolute;width:auto;height:auto;">');
        j.push('<div class="jbox-help-title jbox-title-panel" style="height:25px;display:none;"></div>');
        j.push('<div class="jbox-help-button jbox-button-panel" style="height:25px;padding:5px 0 5px 0;display:none;"></div>');
        j.push('<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:none;">');
        if (c.border > 0x0) {
            j.push('<tr>');
            j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;border-radius:' + c.border + 'px 0 0 0;width:' + c.border + 'px;height:' + c.border + 'px;"></td>');
            j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;height:' + c.border + 'px;overflow: hidden;"></td>');
            j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;border-radius:0 ' + c.border + 'px 0 0;width:' + c.border + 'px;height:' + c.border + 'px;"></td>');
            j.push('</tr>')
        };
        j.push('<tr>');
        j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;"></td>');
        j.push('<td valign="top" style="margin:0px;padding:0px;border:none;">');
        j.push('<div class="jbox-container" style="width:auto;height:auto;">');
        j.push('<a class="jbox-close" title="' + a.jBox.languageDefaults.close + '" onmouseover="$(this).addClass(\'jbox-close-hover\');" onmouseout="$(this).removeClass(\'jbox-close-hover\');" style="position:absolute; display:block; cursor:pointer; top:' + (0x6 + c.border) + 'px; right:' + (0x6 + c.border) + 'px; width:15px; height:15px;' + (c.showClose ? '': 'display:none;') + '"></a>');
        if (g) {
            j.push('<div class="jbox-title-panel" style="height:25px;">');
            j.push('<div class="jbox-title' + (c.showIcon == true ? ' jbox-title-icon': (c.showIcon == false ? '': ' ' + c.showIcon)) + '" style="float:left; width:' + i + '; line-height:' + (a.browser.msie ? 0x19: 0x18) + 'px; padding-left:' + (c.showIcon ? 0x12: 0x5) + 'px;overflow:hidden;text-overflow:ellipsis;word-break:break-all;">' + (c.title == '' ? '&nbsp;': c.title) + '</div>');
            j.push('</div>')
        };
        j.push('<div id="jbox-states"></div></div>');
        j.push('</div>');
        j.push('</td>');
        j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;"></td>');
        j.push('</tr>');
        if (c.border > 0x0) {
            j.push('<tr>');
            j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;border-radius:0 0 0 ' + c.border + 'px; width:' + c.border + 'px; height:' + c.border + 'px;"></td>');
            j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;height:' + c.border + 'px;overflow: hidden;"></td>');
            j.push('<td class="jbox-border" style="margin:0px;padding:0px;border:none;border-radius:0 0 ' + c.border + 'px 0; width:' + c.border + 'px; height:' + c.border + 'px;"></td>');
            j.push('</tr>')
        };
        j.push('</table>');
        j.push('</div>');
        j.push('</div>');
        var k = '<iframe name="jbox-iframe" id="jbox-iframe" width="100%" height="100%" marginheight="0" marginwidth="0" frameborder="0" scrolling="' + c.iframeScrolling + '"></iframe>';
        var l = a(window);
        var m = a(document.body);
        var n = a(j.join('')).appendTo(m);
        var o = n.children('#jbox');
        var p = n.children('#jbox-fade');
        var q = n.children('#jbox-temp');
        if (!f.isObject) {
            switch (f.type) {
            case "ID":
                f.html = a('#' + b).html();
                break;
            case "GET":
            case "POST":
                f.html = '';
                f.url = b;
                break;
            case "HTML":
                f.html = b;
                break;
            case "IFRAME":
                f.html = k;
                if (b.indexOf('#') == -0x1) {
                    f.url = b + (b.indexOf('?') == -0x1 ? '?___t': '&___t') + Math.random()
                } else {
                    var N = b.split('#');
                    f.url = N[0x0] + (N[0x0].indexOf('?') == -0x1 ? '?___t': '&___t') + Math.random() + '#' + N[0x1]
                };
                break
            };
            b = {
                state0: {
                    content: f.html,
                    buttons: c.buttons,
                    buttonsFocus: c.buttonsFocus,
                    submit: c.submit
                }
            }
        };
        var r = [];
        var s = o.find('.jbox-help-title').outerHeight(true);
        var t = o.find('.jbox-help-button').outerHeight(true);
        var u = a.browser.msie ? 'line-height:19px;padding:0px 6px 0px 6px;': 'padding:0px 10px 0px 10px;';
        a.each(b,function(N, O) {
            if (f.isObject) {
                O = a.extend({},
                a.jBox.stateDefaults, O)
            };
            b[N] = O;
            if (O.buttons == undefined) {
                O.buttons = {}
            };
            var P = false;
            a.each(O.buttons,
            function(T, U) {
                P = true
            });
            var Q = 'auto';
            if (typeof c.height == 'number') {
                Q = c.height;
                if (g) {
                    Q = Q - s
                };
                if (P) {
                    Q = Q - t
                };
                Q = (Q - 0x1) + 'px'
            };
            var R = '';
            var S = '25px';
            if (!f.isObject && h) {
                var T = c.height;
                if (typeof c.height == 'number') {
                    if (g) {
                        T = T - s
                    };
                    if (P) {
                        T = T - t
                    };
                    S = ((T / 0x5) * 0x2) + 'px';
                    T = (T - 0x1) + 'px'
                };
                R = ['<div id="jbox-content-loading" class="jbox-content-loading" style="min-height:70px;height:' + T + '; text-align:center;">', '<div class="jbox-content-loading-image" style="display:block; margin:auto; width:220px; height:19px; padding-top: ' + S + ';"></div>', '</div>'].join('')
            };
            r.push('<div id="jbox-state-' + N + '" class="jbox-state" style="display:none;">');
            r.push('<div style="min-width:50px;width:' + (typeof c.width == 'number' ? c.width + 'px': 'auto') + '; height:' + Q + ';">' + R + '<div id="jbox-content" class="jbox-content" style="height:' + Q + ';overflow:hidden;overflow-y:auto;">' + O.content + '</div></div>');
            r.push('<div class="jbox-button-panel" style="height:25px;padding:5px 0 5px 0;text-align: right;' + (P ? '': 'display:none;') + '">');
            if (!c.isTip) {
                r.push('<span class="jbox-bottom-text" style="float:left;display:block;line-height:25px;"></span>')
            };
            a.each(O.buttons,
            function(T, U) {
                r.push('<button class="jbox-button" value="' + U + '" style="' + u + '">' + T + '</button>')
            });
            r.push('</div></div>')
        });
        o.find('#jbox-states').html(r.join('')).children('.jbox-state:first').css('display', 'block');
        if (h) {
            var N = o.find('#jbox-content').css({
                position: (d) ? "absolute": "fixed",
                left: -0x2710
            })
        };
        a.each(b,function(N, O) {
            var P = o.find('#jbox-state-' + N);
            P.children('.jbox-button-panel').children('button').click(function() {
                var Q = P.find('#jbox-content');
                var R = O.buttons[a(this).text()];
                var S = {};
                a.each(o.find('#jbox-states :input').serializeArray(),
                function(U, V) {
                    if (S[V.name] === undefined) {
                        S[V.name] = V.value
                    } else if (typeof S[V.name] == Array) {
                        S[V.name].push(V.value)
                    } else {
                        S[V.name] = [S[V.name], V.value]
                    }
                });
                var T = O.submit(R, Q, S);
                if (T === undefined || T) {
                    I()
                }
            }).bind('mousedown',
            function() {
                a(this).addClass('jbox-button-active')
            }).bind('mouseup',
            function() {
                a(this).removeClass('jbox-button-active')
            }).bind('mouseover',
            function() {
                a(this).addClass('jbox-button-hover')
            }).bind('mouseout',
            function() {
                a(this).removeClass('jbox-button-active').removeClass('jbox-button-hover')
            });
            P.find('.jbox-button-panel button:eq(' + O.buttonsFocus + ')').addClass('jbox-button-focus')
        });
        var v = function() {
            n.css({
                top: l.scrollTop()
            });
            if (c.isMessager) {
                o.css({
                    position: (d) ? "absolute": "fixed",
                    right: 0x1,
                    bottom: 0x1
                })
            }
        };
        var w = function() {
            var N = l.width();
            return document.body.clientWidth < N ? N: document.body.clientWidth
        };
        var x = function() {
            var N = l.height();
            return document.body.clientHeight < N ? N: document.body.clientHeight
        };
        var y = function() {
            if (!c.showFade) {
                return
            };
            if (c.persistent) {
                var N = 0x0;
                n.addClass('jbox-warning');
                var O = setInterval(function() {
                    n.toggleClass('jbox-warning');
                    if (N++>0x1) {
                        clearInterval(O);
                        n.removeClass('jbox-warning')
                    }
                },
                0x64)
            } else {
                I()
            }
        };
        var z = function(N) {
            if (c.isTip || c.isMessager) {
                return false
            };
            var O = (window.event) ? event.keyCode: N.keyCode;
            if (O == 0x1b) {
                I()
            };
            if (O == 0x9) {
                var P = a(':input:enabled:visible', n);
                var Q = !N.shiftKey && N.target == P[P.length - 0x1];
                var R = N.shiftKey && N.target == P[0x0];
                if (Q || R) {
                    setTimeout(function() {
                        if (!P) return;
                        var S = P[R === true ? P.length - 0x1: 0x0];
                        if (S) S.focus()
                    },
                    0xa);
                    return false
                }
            }
        };
		var _dh = a(document).height();
		var _wh = a(window).height(), _ww = a(window).width(),j_h = a('.jbox').height();

		//上传图片时遮罩层
        var A = function() {
            if (c.showFade) {
                p.css({
                    position: "absolute",
                    height: _dh,
                    width: d ? l.width() : "100%",
                    top: 0x0,
                    left: 0x0,
                    right: 0x0,
                    bottom: 0x0
                })
            }
        };
        var B = function() {
            if (c.isMessager) {
                o.css({
                    position: (d) ? "absolute": "fixed",
                    right: 0x1,
                    bottom: 0x1
                })
            } else {
            	//弹出层的top值
                q.css({
                    top:(_wh-j_h)/2
                });
                o.css({
                    position: "absolute",
                    top: q.offset().top + (c.isTip ? l.scrollTop() : 0x0),
                    left: ((l.width() - o.outerWidth()) / 0x2)
                })
            };
            if ((c.showFade && !c.isTip) || (!c.showFade && !c.isTip && !c.isMessager)) {
                n.css({
                    position: (d) ? "absolute": "fixed",
                    height: c.showFade ? l.height() : 0x0,
                    width: "100%",
                    top: (d) ? l.scrollTop() : 0x0,
                    left: 0x0,
                    right: 0x0,
                    bottom: 0x0
                })
            };
            A()
        };
        var C = function() {
            c.zIndex = a.jBox.defaults.zIndex++;
            n.css({
                zIndex: c.zIndex
            });
            o.css({
                zIndex: c.zIndex + 0x1
            })
        };
        var D = function() {
            c.zIndex = a.jBox.defaults.zIndex++;
            n.css({
                zIndex: c.zIndex
            });
            o.css({
                display: "none",
                zIndex: c.zIndex + 0x1
            });
            if (c.showFade) {
                p.css({
                    display: "none",
                    zIndex: c.zIndex,
                    opacity: c.opacity
                })
            }
        };
        var E = function(N) {
            var O = N.data;
            O.target.find('iframe').hide();
            if (c.dragClone) {
                O.target.prev().css({
                    left: O.target.css('left'),
                    top: O.target.css('top'),
                    marginLeft: -0x2,
                    marginTop: -0x2,
                    width: O.target.width() + 0x2,
                    height: O.target.height() + 0x2
                }).show()
            };
            return false
        };
        var F = function(N) {
            var O = N.data;
            var P = O.startLeft + N.pageX - O.startX;
            var Q = O.startTop + N.pageY - O.startY;
            if (c.dragLimit) {
                var R = 0x1;
                var S = document.documentElement.clientHeight - N.data.target.height() - 0x1;
                var T = 0x1;
                var U = document.documentElement.clientWidth - N.data.target.width() - 0x1;
                if (Q < R) Q = R + (c.dragClone ? 0x2: 0x0);
                if (Q > S) Q = S - (c.dragClone ? 0x2: 0x0);
                if (P < T) P = T + (c.dragClone ? 0x2: 0x0);
                if (P > U) P = U - (c.dragClone ? 0x2: 0x0)
            };
            if (c.dragClone) {
                O.target.prev().css({
                    left: P,
                    top: Q
                })
            } else {
                O.target.css({
                    left: P,
                    top: Q
                })
            };
            return false
        };
        var G = function(N) {
            a(document).unbind('.draggable');
            if (c.dragClone) {
                var O = N.data.target.prev().hide();
                N.data.target.css({
                    left: O.css('left'),
                    top: O.css('top')
                }).find('iframe').show()
            } else {
                N.data.target.find('iframe').show()
            };
            return false
        };
        var H = function(N) {
            var O = N.data.target.position();
            var P = {
                target: N.data.target,
                startX: N.pageX,
                startY: N.pageY,
                startLeft: O.left,
                startTop: O.top
            };
            a(document).bind('mousedown.draggable', P, E).bind('mousemove.draggable', P, F).bind('mouseup.draggable', P, G)
        };
        var I = function() {
            if (!c.isTip && !c.isMessager) {
                if (a('.jbox-body').length == 0x1) {
                    a(a.browser.msie ? 'html': 'body').removeAttr('style')
                };
                J()
            } else {
                if (c.isTip) {
                    var tip = a(document.body).data('tip');
                    if (tip && tip.next == true) {
                        q.css('top', tip.options.top);
                        var N = q.offset().top + l.scrollTop();
                        if (N == o.offset().top) {
                            J()
                        } else {
                            o.find('#jbox-content').html(tip.options.content.substr(0x5)).end().css({
                                left: ((l.width() - o.outerWidth()) / 0x2)
                            }).animate({
                                top: N,
                                opacity: 0.1
                            },
                            0x1f4, J)
                        }
                    } else {
                        o.animate({
                            top: '-=200',
                            opacity: 0x0
                        },
                        0x1f4, J)
                    }
                } else {
                    switch (c.showType) {
                    case 'slide':
                        o.slideUp(c.showSpeed, J);
                        break;
                    case 'fade':
                        o.fadeOut(c.showSpeed, J);
                        break;
                    case 'show':
                    default:
                        o.hide(c.showSpeed, J);
                        break
                    }
                }
            }
        };
        var J = function() {
            l.unbind('resize', A);
            if (c.draggable && !c.isTip && !c.isMessager) {
                o.find('.jbox-title-panel').unbind('mousedown', H)
            };
            if (f.type != 'IFRAME') {
                o.find('#jbox-iframe').attr({
                    'src': 'about:blank'
                })
            };
            o.html('').remove();
            if (d && !c.isTip) {
                m.unbind('scroll', v)
            };
            if (c.showFade) {
                p.fadeOut('fast',
                function() {
                    p.unbind('click', y).unbind('mousedown', C).html('').remove()
                })
            };
            n.unbind('keydown keypress', z).html('').remove();
            if (d && c.showFade) {
                a('select').css('visibility', 'visible')
            };
            if (typeof c.closed == 'function') {
                c.closed()
            }
        };
        var K = function() {
            if (c.timeout > 0x0) {
                o.data('autoClosing', window.setTimeout(I, c.timeout));
                if (c.isMessager) {
                    o.hover(function() {
                        window.clearTimeout(o.data('autoClosing'))
                    },
                    function() {
                        o.data('autoClosing', window.setTimeout(I, c.timeout))
                    })
                }
            }
        };
        var L = function() {
            if (typeof c.loaded == 'function') {
                c.loaded(o.find('.jbox-state:visible').find('.jbox-content'))
            }
        };
        var M = function() {
            p.css({
                top: l.scrollTop()
            })
        };
        if (!f.isObject) {
            switch (f.type) {
            case "GET":
            case "POST":
                a.ajax({
                    type:
                    f.type,
                    url: f.url,
                    data: c.ajaxData == undefined ? {}: c.ajaxData,
                    dataType: 'html',
                    cache: false,
                    success: function(N, O) {
                        o.find('#jbox-content').css({
                            position: "static"
                        }).html(N).show().prev().hide();
                        L()
                    },
                    error: function() {
                        o.find('#jbox-content-loading').html('<div style="padding-top:50px;padding-bottom:50px;text-align:center;">Loading Error.</div>')
                    }
                });
                break;
            case "IFRAME":
                o.find('#jbox-iframe').attr({
                    'src':
                    f.url
                }).bind("load",
                function(N) {
                    a(this).parent().css({
                        position: "static"
                    }).show().prev().hide();
                    o.find('#jbox-states .jbox-state:first .jbox-button-focus').focus();
                    L()
                });
                break;
            default:
                o.find('#jbox-content').show();
                break
            }
        };
        B();
        D();
        if (d && !c.isTip) {
            l.scroll(v)
        };
        if (c.showFade) {
            p.click(y)
        };
        l.resize(A);
        //l.scroll(M);
        n.bind('keydown keypress', z);
        o.find('.jbox-close').click(I);
        if (c.showFade) {
            p.fadeIn('fast')
        };
        var M = 'show';
        if (c.showType == 'slide') {
            M = 'slideDown'
        } else if (c.showType == 'fade') {
            M = 'fadeIn'
        };
        if (c.isMessager) {
            o[M](c.showSpeed, K)
        } else {
            var tip = a(document.body).data('tip');
            if (tip && tip.next == true) {
                a(document.body).data('tip', {
                    next: false,
                    options: {}
                });
                o.css('display', '')
            } else {
                if (!f.isObject && h) {
                    o[M](c.showSpeed)
                } else {
                    o[M](c.showSpeed, L)
                }
            }
        };
        if (!c.isTip) {
            o.find('.jbox-bottom-text').html(c.bottomText)
        } else {
            o.find('.jbox-container,.jbox-content').addClass('jbox-tip-color')
        };
        if (f.type != 'IFRAME') {
            o.find('#jbox-states .jbox-state:first .jbox-button-focus').focus()
        } else {
            o.focus()
        };
        if (!c.isMessager) {
            K()
        };
        n.bind('mousedown', C);
        if (c.draggable && !c.isTip && !c.isMessager) {
            o.find('.jbox-title-panel').bind('mousedown', {
                target: o
            },
            H).css('cursor', 'move')
        };
        return n
    };
    a.jBox.version = 2.3;
    a.jBox.defaults = {
        id: null,
        top: "15%",
        zIndex: 0x7c0,
        border: 0x5,
        opacity: 0.1,
        timeout: 0x0,
        showType: 'fade',
        showSpeed: 'fast',
        showIcon: true,
        showClose: true,
        draggable: true,
        dragLimit: true,
        dragClone: false,
        persistent: true,
        showScrolling: true,
        ajaxData: {},
        iframeScrolling: 'auto',
        title: 'jBox',
        width: 0x15e,
        height: 'auto',
        bottomText: '',
        buttons: {
            '确定': 'ok'
        },
        buttonsFocus: 0x0,
        loaded: function(b) {},
        submit: function(b, c, d) {
            return true
        },
        closed: function() {}
    };
    a.jBox.stateDefaults = {
        content: '',
        buttons: {
            '确定': 'ok'
        },
        buttonsFocus: 0x0,
        submit: function(b, c, d) {
            return true
        }
    };
    a.jBox.tipDefaults = {
        content: '',
        icon: 'info',
        top: '40%',
        width: 'auto',
        height: 'auto',
        opacity: 0x0,
        timeout: 0xbb8,
        closed: function() {}
    };
    a.jBox.messagerDefaults = {
        content: '',
        title: 'jBox',
        icon: 'none',
        width: 0x15e,
        height: 'auto',
        timeout: 0xbb8,
        showType: 'slide',
        showSpeed: 0x258,
        border: 0x0,
        buttons: {},
        buttonsFocus: 0x0,
        loaded: function() {},
        submit: function(b, c, d) {
            return true
        },
        closed: function() {}
    };
    a.jBox.languageDefaults = {
        close: '关闭',
        ok: '确定',
        yes: '是',
        no: '否',
        cancel: '取消'
    };
    a.jBox.setDefaults = function(b) {
        a.jBox.defaults = a.extend({},
        a.jBox.defaults, b.defaults);
        a.jBox.stateDefaults = a.extend({},
        a.jBox.stateDefaults, b.stateDefaults);
        a.jBox.tipDefaults = a.extend({},
        a.jBox.tipDefaults, b.tipDefaults);
        a.jBox.messagerDefaults = a.extend({},
        a.jBox.messagerDefaults, b.messagerDefaults);
        a.jBox.languageDefaults = a.extend({},
        a.jBox.languageDefaults, b.languageDefaults)
    };
    a.jBox.getBox = function() {
        return a('.jbox-body').eq(a('.jbox-body').length - 0x1)
    };
    a.jBox.getIframe = function(b) {
        var c = (typeof b == 'string') ? a('#' + b) : a.jBox.getBox();
        return c.find('#jbox-iframe').get(0x0)
    };
    a.jBox.getContent = function() {
        return a.jBox.getState().find('.jbox-content').html()
    };
    a.jBox.setContent = function(b) {
        return a.jBox.getState().find('.jbox-content').html(b)
    };
    a.jBox.getState = function(b) {
        if (b == undefined) {
            return a.jBox.getBox().find('.jbox-state:visible')
        } else {
            return a.jBox.getBox().find('#jbox-state-' + b)
        }
    };
    a.jBox.getStateName = function() {
        return a.jBox.getState().attr('id').replace('jbox-state-', '')
    };
    a.jBox.goToState = function(b, c) {
        var d = a.jBox.getBox();
        if (d != undefined && d != null) {
            var e;
            b = b || false;
            d.find('.jbox-state').slideUp('fast');
            if (typeof b == 'string') {
                e = d.find('#jbox-state-' + b)
            } else {
                e = b ? d.find('.jbox-state:visible').next() : d.find('.jbox-state:visible').prev()
            };
            e.slideDown(0x15e,
            function() {
                window.setTimeout(function() {
                    e.find('.jbox-button-focus').focus();
                    if (c != undefined) {
                        e.find('.jbox-content').html(c)
                    }
                },
                0x14)
            })
        }
    };
    a.jBox.nextState = function(b) {
        a.jBox.goToState(true, b)
    };
    a.jBox.prevState = function(b) {
        a.jBox.goToState(false, b)
    };
    a.jBox.close = function(b, c) {
        b = b || false;
        c = c || 'body';
        if (typeof b == 'string') {
            a('#' + b).find('.jbox-close').click()
        } else {
            var d = a('.jbox-' + c);
            if (b) {
                for (var e = 0x0,
                l = d.length; e < l; ++e) {
                    d.eq(e).find('.jbox-close').click()
                }
            } else {
                if (d.length > 0x0) {
                    d.eq(d.length - 0x1).find('.jbox-close').click()
                }
            }
        }
    };
    a.jBox.open = function(b, c, d, e, f) {
        var defaults = {
            content: b,
            title: c,
            width: d,
            height: e
        };
        f = a.extend({},
        defaults, f);
        f = a.extend({},
        a.jBox.defaults, f);
        a.jBox(f.content, f)
    };
    a.jBox.prompt = function(b, c, d, e) {
        var defaults = {
            content: b,
            title: c,
            icon: d,
            buttons: eval('({ "' + a.jBox.languageDefaults.ok + '": "ok" })')
        };
        e = a.extend({}, defaults, e);
        e = a.extend({}, a.jBox.defaults, e);
        if (e.border < 0x0) {
            e.border = 0x0
        };
        if (e.icon != 'info' && e.icon != 'warning' && e.icon != 'success' && e.icon != 'error' && e.icon != 'question') {
            padding = '';
            e.icon = 'none'
        };
        var f = e.title == undefined ? 0xa: 0x23;
        var g = e.icon == 'none' ? 'height:auto;': 'min-height:30px;' + ((a.browser.msie && parseInt(a.browser.version) < 0x7) ? 'height:auto !important;height:100%;_height:30px;': 'height:auto;');
        var h = [];
        h.push('html:');
        h.push('<div style="margin:10px;' + g + 'padding-left:' + (e.icon == 'none' ? 0x0: 0x28) + 'px;text-align:left;">');
        h.push('<span class="jbox-icon jbox-icon-' + e.icon + '" style="position:absolute; top:' + (f + e.border) + 'px;left:' + (0xa + e.border) + 'px; width:32px; height:32px;"></span>');
        h.push(e.content);
        h.push('</div>');
        e.content = h.join('');
        a.jBox(e.content, e)
    };
    a.jBox.alert = function(b, c, d) {
        a.jBox.prompt(b, c, 'none', d)
    };
    a.jBox.info = function(b, c, d) {
        a.jBox.prompt(b, c, 'info', d)
    };
    a.jBox.success = function(b, c, d) {
        a.jBox.prompt(b, c, 'success', d)
    };
    a.jBox.error = function(b, c, d) {
        a.jBox.prompt(b, c, 'error', d)
    };
    a.jBox.confirm = function(b, c, d, e) {
        var defaults = {
            buttons: eval('({ "' + a.jBox.languageDefaults.ok + '": "ok", "' + a.jBox.languageDefaults.cancel + '": "cancel" })')
        };
        if (d != undefined && typeof d == 'function') {
            defaults.submit = d
        } else {
            defaults.submit = function(f, g, h) {
                return true
            }
        };
        e = a.extend({}, defaults, e);
        a.jBox.prompt(b, c, 'question', e)
    };
    a.jBox.warning = function(b, c, d, e) {
        var defaults = {
            buttons: eval('({ "' + a.jBox.languageDefaults.yes + '": "yes", "' + a.jBox.languageDefaults.no + '": "no", "' + a.jBox.languageDefaults.cancel + '": "cancel" })')
        };
        if (d != undefined && typeof d == 'function') {
            defaults.submit = d
        } else {
            defaults.submit = function(f, g, h) {
                return true
            }
        };
        e = a.extend({},
        defaults, e);
        a.jBox.prompt(b, c, 'warning', e)
    };
    a.jBox.tip = function(b, c, d) {
        var defaults = {
            content: b,
            icon: c,
            opacity: 0x0,
            border: 0x0,
            showClose: false,
            buttons: {},
            isTip: true
        };
        if (defaults.icon == 'loading') {
            defaults.timeout = 0x0;
            defaults.opacity = 0.1
        };
        d = a.extend({},
        defaults, d);
        d = a.extend({},
        a.jBox.tipDefaults, d);
        d = a.extend({},
        a.jBox.defaults, d);
        if (d.timeout < 0x0) {
            d.timeout = 0x0
        };
        if (d.border < 0x0) {
            d.border = 0x0
        };
        if (d.icon != 'info' && d.icon != 'warning' && d.icon != 'success' && d.icon != 'error' && d.icon != 'loading') {
            d.icon = 'info'
        };
        var e = [];
        e.push('html:');
        e.push('<div style="min-height:18px;height:auto;margin:10px;padding-left:30px;padding-top:0px;text-align:left;">');
        e.push('<span class="jbox-icon jbox-icon-' + d.icon + '" style="position:absolute;top:' + (0x4 + d.border) + 'px;left:' + (0x4 + d.border) + 'px; width:32px; height:32px;"></span>');
        e.push(d.content);
        e.push('</div>');
        d.content = e.join('');
        if (a('.jbox-tip').length > 0x0) {
            a(document.body).data('tip', {
                next: true,
                options: d
            });
            a.jBox.closeTip()
        };
        if (d.focusId != undefined) {
            a('#' + d.focusId).focus();
            top.$('#' + d.focusId).focus()
        };
        a.jBox(d.content, d)
    };
    a.jBox.closeTip = function() {
        a.jBox.close(false, 'tip')
    };
    a.jBox.messager = function(b, c, d, e) {
        a.jBox.closeMessager();
        var defaults = {
            content: b,
            title: c,
            timeout: (d == undefined ? a.jBox.messagerDefaults.timeout: d),
            opacity: 0x0,
            showClose: true,
            draggable: false,
            isMessager: true
        };
        e = a.extend({},
        defaults, e);
        e = a.extend({},
        a.jBox.messagerDefaults, e);
        var f = a.extend({},
        a.jBox.defaults, {});
        f.title = null;
        e = a.extend({},
        f, e);
        if (e.border < 0x0) {
            e.border = 0x0
        };
        if (e.icon != 'info' && e.icon != 'warning' && e.icon != 'success' && e.icon != 'error' && e.icon != 'question') {
            padding = '';
            e.icon = 'none'
        };
        var g = e.title == undefined ? 0xa: 0x23;
        var h = e.icon == 'none' ? 'height:auto;': 'min-height:30px;' + ((a.browser.msie && parseInt(a.browser.version) < 0x7) ? 'height:auto !important;height:100%;_height:30px;': 'height:auto;');
        var i = [];
        i.push('html:');
        i.push('<div style="margin:10px;' + h + 'padding-left:' + (e.icon == 'none' ? 0x0: 0x28) + 'px;text-align:left;">');
        i.push('<span class="jbox-icon jbox-icon-' + e.icon + '" style="position:absolute; top:' + (g + e.border) + 'px;left:' + (0xa + e.border) + 'px; width:32px; height:32px;"></span>');
        i.push(e.content);
        i.push('</div>');
        e.content = i.join('');
        a.jBox(e.content, e)
    };
    a.jBox.closeMessager = function() {
        a.jBox.close(false, 'messager')
    };
    window.jBox = a.jBox
})(jQuery);


/**
 * 输入提示框 
 */
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
				console.log(Q.classes.selectedDropdownItem);
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

/**
 * 重定向URL
 * @param string a 要重定向到的地址
 * @param boolean b 是否重定向到本域名还是主域名 true 为重定向到主域名
 */
function redirectUrl(a, b) {
    if (b) {
        window.location.href = lwkai.loginUrl + "?ret=" + encodeURIComponent(a);
    } else {
        window.location.href = a;
    }
}
function msgEscape(a) {
    if (typeof(a) == "undefined" || !a || a == "") {
        return;
    }
    a = a.replace(/%/g, "");
    a = a.replace(/\n/g, " ");
    a = a.replace(/\r/g, " ");
    a = a.replace(/ /g, " ");
    a = a.replace(/\"/g, " ");
    a = a.replace(/#/g, " ");
    a = a.replace(/\$/g, " ");
    a = a.replace(/&/g, " ");
    a = a.replace(/\(/g, " ");
    a = a.replace(/\)/g, " ");
    a = a.replace(/\+/g, " ");
    a = a.replace(/,/g, " ");
    a = a.replace(/\//g, " ");
    a = a.replace(/\:/g, " ");
    a = a.replace(/\;/g, " ");
    a = a.replace(/</g, " ");
    a = a.replace(/=/g, " ");
    a = a.replace(/>/g, " ");
    a = a.replace(/@/g, " ");
    return a;
}

function escapeHTML(a) {
    return String(a).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;").replace(/\//g, "&#x2F;").replace(/ /g, "&nbsp;");
}

function htmlspecialchars_decode(a) {
    return String(a).replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&#39;/g, "'").replace(/&#x2F;/g, "/").replace(/&nbsp;/g, " ");
}

function getCookie(c) {
    var d = document.cookie.split("; ");
    for (var b = 0; b < d.length; b++) {
        var a = d[b].split("=");
        if (a[0] == c) {
            return (a[1]);
        }
    }
}

function getExtcsValue() {
    return getCookie("csrfToken");
}

if (!lwkai) {
    var lwkai = {};
};
/**
 *取得URL上的参数 
 */
lwkai.getUrlParam = function(name){
	var url = window.location.href;
	if (url.indexOf('?') != -1) {
		var b = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
	    var c = window.location.search.substr(1).match(b);
        if (c != null) {
            return unescape(c[2]);
        }
	} else {
		var b = window.location.href.split('/');
	    for(var key in b) {
	        if (b[key].indexOf('--') != -1) {
	            var _arr = b[key].split('--');
	            for(var i = 0, len = _arr.length; i < len; i=i+2) {
	                if (_arr[i].toLowerCase() == name.toLowerCase()) {
	                    return _arr[i+1].replace(/\.html$/ig,'');
	                }
	            }
	        }
	    }
	}
}

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

$(document).ready(function(){
    //topbar menu
    $('li.userhome').hover(function(){
        $(this).addClass('uhover');
        $(this).find('.uhmenutree').stop(true,true).slideDown(300);
    },function(){
        $(this).removeClass('uhover');
        $(this).find('.uhmenutree').stop(true,true).hide();
    });
    
    $("#persons,.myhome").click(function() { /*   我的主页点击事件   */
        var a = $("#userid").val();
        if (a == "" || a == "undefined") {
            redirectUrl(pathUpload + "album/userInfo.html", true);
        } else {
            window.location.href = pathUpload + "album/userInfo/userId--" + a + ".html";
        }
        return false;
    });
    $(".J-publish").click(function() {/* 我的旅图菜单下 发表游记点击事件  */
        if ($("#userid").val() == "" || $("#userid").val() == "undefined") {
            redirectUrl(pathUpload + "album/add.html", true);
        } else {
            redirectUrl(pathUpload + "album/add.html", false);
        }
        return false;
    });
    /* 顶部搜索框 */
    jQuery("input.autocomplete_input").autocomplete('', {
        minChars: 1,
        resultsClass: "recommend",
        selectFirst: false,
        matchContains: "word",
        autoFill: false,
        dataType: 'json',
        max: 10,
        scroll: true,
        scrollHeight: 280,
        inputDefaultsVal: function(row) {
            return row[0] + " (<strong>id: " + row[1] + "</strong>)";
        },
        formatResult:function(row) {
            return row[0].replace(/(<.+?>)/gi, '');
        },
        parse:function(data) {
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                var row = $.trim(data[i]['name']);
                if (row) {
                    row = row.split("|");
                    parsed[parsed.length] = {
                        data: row,
                        value: row[0],
                        result: row[0]
                    };
                }
            }
            return parsed;
        }
    });

});