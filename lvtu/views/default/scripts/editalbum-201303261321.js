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
var swfobject = function() {
    var l = "undefined",
    T = "object",
    A = "Shockwave Flash",
    e = "ShockwaveFlash.ShockwaveFlash",
    S = "application/x-shockwave-flash",
    z = "SWFObjectExprInst",
    f = "onreadystatechange",
    w = window,
    L = document,
    V = navigator,
    B = false,
    a = [J],
    Q = [],
    v = [],
    q = [],
    N,
    y,
    m,
    j,
    r = false,
    C = false,
    P,
    o,
    O = true,
    u = function() {
        var ah = typeof L.getElementById != l && typeof L.getElementsByTagName != l && typeof L.createElement != l,
        ad = V.userAgent.toLowerCase(),
        af = V.platform.toLowerCase(),
        aa = af ? /win/.test(af) : /win/.test(ad),
        Y = af ? /mac/.test(af) : /mac/.test(ad),
        ab = /webkit/.test(ad) ? parseFloat(ad.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : false,
        ae = !+"\v1",
        ac = [0, 0, 0],
        X = null;
        if (typeof V.plugins != l && typeof V.plugins[A] == T) {
            X = V.plugins[A].description;
            if (X && !(typeof V.mimeTypes != l && V.mimeTypes[S] && !V.mimeTypes[S].enabledPlugin)) {
                B = true;
                ae = false;
                X = X.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
                ac[0] = parseInt(X.replace(/^(.*)\..*$/, "$1"), 10);
                ac[1] = parseInt(X.replace(/^.*\.(.*)\s.*$/, "$1"), 10);
                ac[2] = /[a-zA-Z]/.test(X) ? parseInt(X.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0;
            }
        } else {
            if (typeof w.ActiveXObject != l) {
                try {
                    var Z = new ActiveXObject(e);
                    if (Z) {
                        X = Z.GetVariable("$version");
                        if (X) {
                            ae = true;
                            X = X.split(" ")[1].split(",");
                            ac = [parseInt(X[0], 10), parseInt(X[1], 10), parseInt(X[2], 10)];
                        }
                    }
                } catch(ag) {}
            }
        }
        return {
            w3: ah,
            pv: ac,
            wk: ab,
            ie: ae,
            win: aa,
            mac: Y
        };
    } (),
    M = function() {
        if (!u.w3) {
            return;
        }
        if ((typeof L.readyState != l && L.readyState == "complete") || (typeof L.readyState == l && (L.getElementsByTagName("body")[0] || L.body))) {
            H();
        }
        if (!r) {
            if (typeof L.addEventListener != l) {
                L.addEventListener("DOMContentLoaded", H, false);
            }
            if (u.ie && u.win) {
                L.attachEvent(f,
                function() {
                    if (L.readyState == "complete") {
                        L.detachEvent(f, arguments.callee);
                        H();
                    }
                });
                if (w == top) { (function() {
                        if (r) {
                            return;
                        }
                        try {
                            L.documentElement.doScroll("left");
                        } catch(X) {
                            setTimeout(arguments.callee, 0);
                            return;
                        }
                        H();
                    })();
                }
            }
            if (u.wk) { (function() {
                    if (r) {
                        return;
                    }
                    if (!/loaded|complete/.test(L.readyState)) {
                        setTimeout(arguments.callee, 0);
                        return;
                    }
                    H();
                })();
            }
            U(H);
        }
    } ();
    function H() {
        if (r) {
            return;
        }
        try {
            var X = L.getElementsByTagName("body")[0].appendChild(k("span"));
            X.parentNode.removeChild(X);
        } catch(Y) {
            return;
        }
        r = true;
        var Z = a.length;
        for (var aa = 0; aa < Z; aa++) {
            a[aa]();
        }
    }
    function s(X) {
        if (r) {
            X();
        } else {
            a[a.length] = X;
        }
    }
    function U(Y) {
        if (typeof w.addEventListener != l) {
            w.addEventListener("load", Y, false);
        } else {
            if (typeof L.addEventListener != l) {
                L.addEventListener("load", Y, false);
            } else {
                if (typeof w.attachEvent != l) {
                    K(w, "onload", Y);
                } else {
                    if (typeof w.onload == "function") {
                        var X = w.onload;
                        w.onload = function() {
                            X();
                            Y();
                        };
                    } else {
                        w.onload = Y;
                    }
                }
            }
        }
    }
    function J() {
        if (B) {
            c();
        } else {
            p();
        }
    }
    function c() {
        var Z = L.getElementsByTagName("body")[0];
        var X = k(T);
        X.setAttribute("type", S);
        var Y = Z.appendChild(X);
        if (Y) {
            var aa = 0; (function() {
                if (typeof Y.GetVariable != l) {
                    var ab = Y.GetVariable("$version");
                    if (ab) {
                        ab = ab.split(" ")[1].split(",");
                        u.pv = [parseInt(ab[0], 10), parseInt(ab[1], 10), parseInt(ab[2], 10)];
                    }
                } else {
                    if (aa < 10) {
                        aa++;
                        setTimeout(arguments.callee, 10);
                        return;
                    }
                }
                Z.removeChild(X);
                Y = null;
                p();
            })();
        } else {
            p();
        }
    }
    function p() {
        var ac = Q.length;
        if (ac > 0) {
            for (var ab = 0; ab < ac; ab++) {
                var ag = Q[ab].id;
                var X = Q[ab].callbackFn;
                var ai = {
                    success: false,
                    id: ag
                };
                if (u.pv[0] > 0) {
                    var aa = E(ag);
                    if (aa) {
                        if (n(Q[ab].swfVersion) && !(u.wk && u.wk < 312)) {
                            d(ag, true);
                            if (X) {
                                ai.success = true;
                                ai.ref = h(ag);
                                X(ai);
                            }
                        } else {
                            if (Q[ab].expressInstall && i()) {
                                var ae = {};
                                ae.data = Q[ab].expressInstall;
                                ae.width = aa.getAttribute("width") || "0";
                                ae.height = aa.getAttribute("height") || "0";
                                if (aa.getAttribute("class")) {
                                    ae.styleclass = aa.getAttribute("class");
                                }
                                if (aa.getAttribute("align")) {
                                    ae.align = aa.getAttribute("align");
                                }
                                var ad = {};
                                var af = aa.getElementsByTagName("param");
                                var Y = af.length;
                                for (var Z = 0; Z < Y; Z++) {
                                    if (af[Z].getAttribute("name").toLowerCase() != "movie") {
                                        ad[af[Z].getAttribute("name")] = af[Z].getAttribute("value");
                                    }
                                }
                                x(ae, ad, ag, X);
                            } else {
                                R(aa);
                                if (X) {
                                    X(ai);
                                }
                            }
                        }
                    }
                } else {
                    d(ag, true);
                    if (X) {
                        var ah = h(ag);
                        if (ah && typeof ah.SetVariable != l) {
                            ai.success = true;
                            ai.ref = ah;
                        }
                        X(ai);
                    }
                }
            }
        }
    }
    function h(X) {
        var Z = null;
        var aa = E(X);
        if (aa && aa.nodeName == "OBJECT") {
            if (typeof aa.SetVariable != l) {
                Z = aa;
            } else {
                var Y = aa.getElementsByTagName(T)[0];
                if (Y) {
                    Z = Y;
                }
            }
        }
        return Z;
    }
    function i() {
        return ! C && n("6.0.65") && (u.win || u.mac) && !(u.wk && u.wk < 312);
    }
    function x(ab, ad, Z, ac) {
        C = true;
        m = ac || null;
        j = {
            success: false,
            id: Z
        };
        var Y = E(Z);
        if (Y) {
            if (Y.nodeName == "OBJECT") {
                N = I(Y);
                y = null;
            } else {
                N = Y;
                y = Z;
            }
            ab.id = z;
            if (typeof ab.width == l || (!/%$/.test(ab.width) && parseInt(ab.width, 10) < 310)) {
                ab.width = "310";
            }
            if (typeof ab.height == l || (!/%$/.test(ab.height) && parseInt(ab.height, 10) < 137)) {
                ab.height = "137";
            }
            L.title = L.title.slice(0, 47) + " - Flash Player Installation";
            var X = u.ie && u.win ? "ActiveX": "PlugIn",
            ae = "MMredirectURL=" + w.location.toString().replace(/&/g, "%26") + "&MMplayerType=" + X + "&MMdoctitle=" + L.title;
            if (typeof ad.flashvars != l) {
                ad.flashvars += "&" + ae;
            } else {
                ad.flashvars = ae;
            }
            if (u.ie && u.win && Y.readyState != 4) {
                var aa = k("div");
                Z += "SWFObjectNew";
                aa.setAttribute("id", Z);
                Y.parentNode.insertBefore(aa, Y);
                Y.style.display = "none"; (function() {
                    if (Y.readyState == 4) {
                        Y.parentNode.removeChild(Y);
                    } else {
                        setTimeout(arguments.callee, 10);
                    }
                })();
            }
            W(ab, ad, Z);
        }
    }
    function R(Y) {
        if (u.ie && u.win && Y.readyState != 4) {
            var X = k("div");
            Y.parentNode.insertBefore(X, Y);
            X.parentNode.replaceChild(I(Y), X);
            Y.style.display = "none"; (function() {
                if (Y.readyState == 4) {
                    Y.parentNode.removeChild(Y);
                } else {
                    setTimeout(arguments.callee, 10);
                }
            })();
        } else {
            Y.parentNode.replaceChild(I(Y), Y);
        }
    }
    function I(X) {
        var ab = k("div");
        if (u.win && u.ie) {
            ab.innerHTML = X.innerHTML;
        } else {
            var aa = X.getElementsByTagName(T)[0];
            if (aa) {
                var Y = aa.childNodes;
                if (Y) {
                    var Z = Y.length;
                    for (var ac = 0; ac < Z; ac++) {
                        if (! (Y[ac].nodeType == 1 && Y[ac].nodeName == "PARAM") && !(Y[ac].nodeType == 8)) {
                            ab.appendChild(Y[ac].cloneNode(true));
                        }
                    }
                }
            }
        }
        return ab;
    }
    function W(ae, ac, ag) {
        var af, ai = E(ag);
        if (u.wk && u.wk < 312) {
            return af;
        }
        if (ai) {
            if (typeof ae.id == l) {
                ae.id = ag;
            }
            if (u.ie && u.win) {
                var ad = "";
                for (var aa in ae) {
                    if (ae[aa] != Object.prototype[aa]) {
                        if (aa.toLowerCase() == "data") {
                            ac.movie = ae[aa];
                        } else {
                            if (aa.toLowerCase() == "styleclass") {
                                ad += ' class="' + ae[aa] + '"';
                            } else {
                                if (aa.toLowerCase() != "classid") {
                                    ad += " " + aa + '="' + ae[aa] + '"';
                                }
                            }
                        }
                    }
                }
                var ab = "";
                for (var Z in ac) {
                    if (ac[Z] != Object.prototype[Z]) {
                        ab += '<param name="' + Z + '" value="' + ac[Z] + '" />';
                    }
                }
                ai.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + ad + ">" + ab + "</object>";
                v[v.length] = ae.id;
                af = E(ae.id);
            } else {
                var ah = k(T);
                ah.setAttribute("type", S);
                for (var Y in ae) {
                    if (ae[Y] != Object.prototype[Y]) {
                        if (Y.toLowerCase() == "styleclass") {
                            ah.setAttribute("class", ae[Y]);
                        } else {
                            if (Y.toLowerCase() != "classid") {
                                ah.setAttribute(Y, ae[Y]);
                            }
                        }
                    }
                }
                for (var X in ac) {
                    if (ac[X] != Object.prototype[X] && X.toLowerCase() != "movie") {
                        G(ah, X, ac[X]);
                    }
                }
                ai.parentNode.replaceChild(ah, ai);
                af = ah;
            }
        }
        return af;
    }
    function G(X, Z, aa) {
        var Y = k("param");
        Y.setAttribute("name", Z);
        Y.setAttribute("value", aa);
        X.appendChild(Y);
    }
    function g(Y) {
        var X = E(Y);
        if (X && X.nodeName == "OBJECT") {
            if (u.ie && u.win) {
                X.style.display = "none"; (function() {
                    if (X.readyState == 4) {
                        D(Y);
                    } else {
                        setTimeout(arguments.callee, 10);
                    }
                })();
            } else {
                X.parentNode.removeChild(X);
            }
        }
    }
    function D(Y) {
        var X = E(Y);
        if (X) {
            for (var Z in X) {
                if (typeof X[Z] == "function") {
                    X[Z] = null;
                }
            }
            X.parentNode.removeChild(X);
        }
    }
    function E(Y) {
        var Z = null;
        try {
            Z = L.getElementById(Y);
        } catch(X) {}
        return Z;
    }
    function k(X) {
        return L.createElement(X);
    }
    function K(Y, Z, X) {
        Y.attachEvent(Z, X);
        q[q.length] = [Y, Z, X];
    }
    function n(Y) {
        var X = u.pv,
        Z = Y.split(".");
        Z[0] = parseInt(Z[0], 10);
        Z[1] = parseInt(Z[1], 10) || 0;
        Z[2] = parseInt(Z[2], 10) || 0;
        return (X[0] > Z[0] || (X[0] == Z[0] && X[1] > Z[1]) || (X[0] == Z[0] && X[1] == Z[1] && X[2] >= Z[2])) ? true: false;
    }
    function b(X, aa, Y, ad) {
        if (u.ie && u.mac) {
            return;
        }
        var ab = L.getElementsByTagName("head")[0];
        if (!ab) {
            return;
        }
        var Z = (Y && typeof Y == "string") ? Y: "screen";
        if (ad) {
            P = null;
            o = null;
        }
        if (!P || o != Z) {
            var ac = k("style");
            ac.setAttribute("type", "text/css");
            ac.setAttribute("media", Z);
            P = ab.appendChild(ac);
            if (u.ie && u.win && typeof L.styleSheets != l && L.styleSheets.length > 0) {
                P = L.styleSheets[L.styleSheets.length - 1];
            }
            o = Z;
        }
        if (u.ie && u.win) {
            if (P && typeof P.addRule == T) {
                P.addRule(X, aa);
            }
        } else {
            if (P && typeof L.createTextNode != l) {
                P.appendChild(L.createTextNode(X + " {" + aa + "}"));
            }
        }
    }
    function d(Y, Z) {
        if (!O) {
            return;
        }
        var X = Z ? "visible": "hidden";
        if (r && E(Y)) {
            E(Y).style.visibility = X;
        } else {
            b("#" + Y, "visibility:" + X);
        }
    }
    function t(X) {
        var Y = /[\\\"<>\.;]/;
        var Z = Y.exec(X) != null;
        return Z && typeof encodeURIComponent != l ? encodeURIComponent(X) : X;
    }
    var F = function() {
        if (u.ie && u.win) {
            window.attachEvent("onunload",
            function() {
                var Y = q.length;
                for (var X = 0; X < Y; X++) {
                    q[X][0].detachEvent(q[X][1], q[X][2]);
                }
                var ab = v.length;
                for (var ac = 0; ac < ab; ac++) {
                    g(v[ac]);
                }
                for (var aa in u) {
                    u[aa] = null;
                }
                u = null;
                for (var Z in swfobject) {
                    swfobject[Z] = null;
                }
                swfobject = null;
            });
        }
    } ();
    return {
        registerObject: function(Y, Z, ab, X) {
            if (u.w3 && Y && Z) {
                var aa = {};
                aa.id = Y;
                aa.swfVersion = Z;
                aa.expressInstall = ab;
                aa.callbackFn = X;
                Q[Q.length] = aa;
                d(Y, false);
            } else {
                if (X) {
                    X({
                        success: false,
                        id: Y
                    });
                }
            }
        },
        getObjectById: function(X) {
            if (u.w3) {
                return h(X);
            }
        },
        embedSWF: function(X, ad, aa, ac, af, ah, ag, Z, ab, Y) {
            var ae = {
                success: false,
                id: ad
            };
            if (u.w3 && !(u.wk && u.wk < 312) && X && ad && aa && ac && af) {
                d(ad, false);
                s(function() {
                    aa += "";
                    ac += "";
                    var ak = {};
                    if (ab && typeof ab === T) {
                        for (var am in ab) {
                            ak[am] = ab[am];
                        }
                    }
                    ak.data = X;
                    ak.width = aa;
                    ak.height = ac;
                    var an = {};
                    if (Z && typeof Z === T) {
                        for (var al in Z) {
                            an[al] = Z[al];
                        }
                    }
                    if (ag && typeof ag === T) {
                        for (var aj in ag) {
                            if (typeof an.flashvars != l) {
                                an.flashvars += "&" + aj + "=" + ag[aj];
                            } else {
                                an.flashvars = aj + "=" + ag[aj];
                            }
                        }
                    }
                    if (n(af)) {
                        var ai = W(ak, an, ad);
                        if (ak.id == ad) {
                            d(ad, true);
                        }
                        ae.success = true;
                        ae.ref = ai;
                    } else {
                        if (ah && i()) {
                            ak.data = ah;
                            x(ak, an, ad, Y);
                            return;
                        } else {
                            d(ad, true);
                        }
                    }
                    if (Y) {
                        Y(ae);
                    }
                });
            } else {
                if (Y) {
                    Y(ae);
                }
            }
        },
        switchOffAutoHideShow: function() {
            O = false;
        },
        ua: u,
        getFlashPlayerVersion: function() {
            return {
                major: u.pv[0],
                minor: u.pv[1],
                release: u.pv[2]
            };
        },
        hasFlashPlayerVersion: n,
        createSWF: function(Y, X, Z) {
            if (u.w3) {
                return W(Y, X, Z);
            } else {
                return undefined;
            }
        },
        showExpressInstall: function(X, Y, Z, aa) {
            if (u.w3 && i()) {
                x(X, Y, Z, aa);
            }
        },
        removeSWF: function(X) {
            if (u.w3) {
                g(X);
            }
        },
        createCSS: function(X, Y, aa, Z) {
            if (u.w3) {
                b(X, Y, aa, Z);
            }
        },
        addDomLoadEvent: s,
        addLoadEvent: U,
        getQueryParamValue: function(X) {
            var Y = L.location.search || L.location.hash;
            if (Y) {
                if (/\?/.test(Y)) {
                    Y = Y.split("?")[1];
                }
                if (X == null) {
                    return t(Y);
                }
                var aa = Y.split("&");
                for (var Z = 0; Z < aa.length; Z++) {
                    if (aa[Z].substring(0, aa[Z].indexOf("=")) == X) {
                        return t(aa[Z].substring((aa[Z].indexOf("=") + 1)));
                    }
                }
            }
            return "";
        },
        expressInstallCallback: function() {
            if (C) {
                var X = E(z);
                if (X && N) {
                    X.parentNode.replaceChild(N, X);
                    if (y) {
                        d(y, true);
                        if (u.ie && u.win) {
                            N.style.display = "block";
                        }
                    }
                    if (m) {
                        m(j);
                    }
                }
                C = false;
            }
        }
    };
} ();
var SWFUpload;
if (SWFUpload == undefined) {
    SWFUpload = function(a) {
        this.initSWFUpload(a);
    };
}
SWFUpload.prototype.initSWFUpload = function(b) {
    try {
        this.customSettings = {};
        this.settings = b;
        this.eventQueue = [];
        this.movieName = "SWFUpload_" + SWFUpload.movieCount++;
        this.movieElement = null;
        SWFUpload.instances[this.movieName] = this;
        this.initSettings();
        this.loadFlash();
        this.displayDebugInfo();
    } catch(a) {
        delete SWFUpload.instances[this.movieName];
        throw a;
    }
};
SWFUpload.instances = {};
SWFUpload.movieCount = 0;
SWFUpload.version = "2.2.0 2009-03-25";
SWFUpload.QUEUE_ERROR = {
    QUEUE_LIMIT_EXCEEDED: -100,
    FILE_EXCEEDS_SIZE_LIMIT: -110,
    ZERO_BYTE_FILE: -120,
    INVALID_FILETYPE: -130
};
SWFUpload.UPLOAD_ERROR = {
    HTTP_ERROR: -200,
    MISSING_UPLOAD_URL: -210,
    IO_ERROR: -220,
    SECURITY_ERROR: -230,
    UPLOAD_LIMIT_EXCEEDED: -240,
    UPLOAD_FAILED: -250,
    SPECIFIED_FILE_ID_NOT_FOUND: -260,
    FILE_VALIDATION_FAILED: -270,
    FILE_CANCELLED: -280,
    UPLOAD_STOPPED: -290
};
SWFUpload.FILE_STATUS = {
    QUEUED: -1,
    IN_PROGRESS: -2,
    ERROR: -3,
    COMPLETE: -4,
    CANCELLED: -5
};
SWFUpload.BUTTON_ACTION = {
    SELECT_FILE: -100,
    SELECT_FILES: -110,
    START_UPLOAD: -120
};
SWFUpload.CURSOR = {
    ARROW: -1,
    HAND: -2
};
SWFUpload.WINDOW_MODE = {
    WINDOW: "window",
    TRANSPARENT: "transparent",
    OPAQUE: "opaque"
};
SWFUpload.completeURL = function(b) {
    if (typeof(b) !== "string" || b.match(/^https?:\/\//i) || b.match(/^\//)) {
        return b;
    }
    var a = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port: "");
    var c = window.location.pathname.lastIndexOf("/");
    if (c <= 0) {
        path = "/";
    } else {
        path = window.location.pathname.substr(0, c) + "/";
    }
    return path + b;
};
SWFUpload.prototype.initSettings = function() {
    this.ensureDefault = function(b, a) {
        this.settings[b] = (this.settings[b] == undefined) ? a: this.settings[b];
    };
    this.ensureDefault("upload_url", "");
    this.ensureDefault("preserve_relative_urls", false);
    this.ensureDefault("file_post_name", "Filedata");
    this.ensureDefault("post_params", {});
    this.ensureDefault("use_query_string", false);
    this.ensureDefault("requeue_on_error", false);
    this.ensureDefault("http_success", []);
    this.ensureDefault("assume_success_timeout", 0);
    this.ensureDefault("file_types", "*.*");
    this.ensureDefault("file_types_description", "All Files");
    this.ensureDefault("file_size_limit", 0);
    this.ensureDefault("file_upload_limit", 0);
    this.ensureDefault("file_queue_limit", 0);
    this.ensureDefault("flash_url", "swfupload.swf");
    this.ensureDefault("prevent_swf_caching", true);
    this.ensureDefault("button_image_url", "");
    this.ensureDefault("button_width", 1);
    this.ensureDefault("button_height", 1);
    this.ensureDefault("button_text", "");
    this.ensureDefault("button_text_style", "color: #000000; font-size: 16pt;");
    this.ensureDefault("button_text_top_padding", 0);
    this.ensureDefault("button_text_left_padding", 0);
    this.ensureDefault("button_action", SWFUpload.BUTTON_ACTION.SELECT_FILES);
    this.ensureDefault("button_disabled", false);
    this.ensureDefault("button_placeholder_id", "");
    this.ensureDefault("button_placeholder", null);
    this.ensureDefault("button_cursor", SWFUpload.CURSOR.ARROW);
    this.ensureDefault("button_window_mode", SWFUpload.WINDOW_MODE.WINDOW);
    this.ensureDefault("debug", false);
    this.settings.debug_enabled = this.settings.debug;
    this.settings.return_upload_start_handler = this.returnUploadStart;
    this.ensureDefault("swfupload_loaded_handler", null);
    this.ensureDefault("file_dialog_start_handler", null);
    this.ensureDefault("file_queued_handler", null);
    this.ensureDefault("file_queue_error_handler", null);
    this.ensureDefault("file_dialog_complete_handler", null);
    this.ensureDefault("upload_start_handler", null);
    this.ensureDefault("upload_progress_handler", null);
    this.ensureDefault("upload_error_handler", null);
    this.ensureDefault("upload_success_handler", null);
    this.ensureDefault("upload_complete_handler", null);
    this.ensureDefault("debug_handler", this.debugMessage);
    this.ensureDefault("custom_settings", {});
    this.customSettings = this.settings.custom_settings;
    if ( !! this.settings.prevent_swf_caching) {
        this.settings.flash_url = this.settings.flash_url + (this.settings.flash_url.indexOf("?") < 0 ? "?": "&") + "preventswfcaching=" + new Date().getTime();
    }
    if (!this.settings.preserve_relative_urls) {
        this.settings.upload_url = SWFUpload.completeURL(this.settings.upload_url);
    }
    delete this.ensureDefault;
};
SWFUpload.prototype.loadFlash = function() {
    var a, b;
    if (document.getElementById(this.movieName) !== null) {
        throw "ID " + this.movieName + " is already in use. The Flash Object could not be added";
    }
    a = document.getElementById(this.settings.button_placeholder_id) || this.settings.button_placeholder;
    if (a == undefined) {
        throw "Could not find the placeholder element: " + this.settings.button_placeholder_id;
    }
    b = document.createElement("div");
    b.innerHTML = this.getFlashHTML();
    a.parentNode.replaceChild(b.firstChild, a);
    if (window[this.movieName] == undefined) {
        window[this.movieName] = this.getMovieElement();
    }
};
SWFUpload.prototype.getFlashHTML = function() {
    return ['<object id="', this.movieName, '" type="application/x-shockwave-flash" data="', this.settings.flash_url, '" width="', this.settings.button_width, '" height="', this.settings.button_height, '" class="swfupload">', '<param name="wmode" value="', this.settings.button_window_mode, '" />', '<param name="movie" value="', this.settings.flash_url, '" />', '<param name="quality" value="high" />', '<param name="menu" value="false" />', '<param name="allowScriptAccess" value="always" />', '<param name="flashvars" value="' + this.getFlashVars() + '" />', "</object>"].join("");
};
SWFUpload.prototype.getFlashVars = function() {
    var b = this.buildParamString();
    var a = this.settings.http_success.join(",");
    return ["movieName=", encodeURIComponent(this.movieName), "&amp;uploadURL=", encodeURIComponent(this.settings.upload_url), "&amp;useQueryString=", encodeURIComponent(this.settings.use_query_string), "&amp;requeueOnError=", encodeURIComponent(this.settings.requeue_on_error), "&amp;httpSuccess=", encodeURIComponent(a), "&amp;assumeSuccessTimeout=", encodeURIComponent(this.settings.assume_success_timeout), "&amp;params=", encodeURIComponent(b), "&amp;filePostName=", encodeURIComponent(this.settings.file_post_name), "&amp;fileTypes=", encodeURIComponent(this.settings.file_types), "&amp;fileTypesDescription=", encodeURIComponent(this.settings.file_types_description), "&amp;fileSizeLimit=", encodeURIComponent(this.settings.file_size_limit), "&amp;fileUploadLimit=", encodeURIComponent(this.settings.file_upload_limit), "&amp;fileQueueLimit=", encodeURIComponent(this.settings.file_queue_limit), "&amp;debugEnabled=", encodeURIComponent(this.settings.debug_enabled), "&amp;buttonImageURL=", encodeURIComponent(this.settings.button_image_url), "&amp;buttonWidth=", encodeURIComponent(this.settings.button_width), "&amp;buttonHeight=", encodeURIComponent(this.settings.button_height), "&amp;buttonText=", encodeURIComponent(this.settings.button_text), "&amp;buttonTextTopPadding=", encodeURIComponent(this.settings.button_text_top_padding), "&amp;buttonTextLeftPadding=", encodeURIComponent(this.settings.button_text_left_padding), "&amp;buttonTextStyle=", encodeURIComponent(this.settings.button_text_style), "&amp;buttonAction=", encodeURIComponent(this.settings.button_action), "&amp;buttonDisabled=", encodeURIComponent(this.settings.button_disabled), "&amp;buttonCursor=", encodeURIComponent(this.settings.button_cursor)].join("");
};
SWFUpload.prototype.getMovieElement = function() {
    if (this.movieElement == undefined) {
        this.movieElement = document.getElementById(this.movieName);
    }
    if (this.movieElement === null) {
        throw "Could not find Flash element";
    }
    return this.movieElement;
};
SWFUpload.prototype.buildParamString = function() {
    var a = this.settings.post_params;
    var c = [];
    if (typeof(a) === "object") {
        for (var b in a) {
            if (a.hasOwnProperty(b)) {
                c.push(encodeURIComponent(b.toString()) + "=" + encodeURIComponent(a[b].toString()));
            }
        }
    }
    return c.join("&amp;");
};
SWFUpload.prototype.destroy = function() {
    try {
        this.cancelUpload(null, false);
        var d = null;
        d = this.getMovieElement();
        if (d && typeof(d.CallFunction) === "unknown") {
            for (var a in d) {
                try {
                    if (typeof(d[a]) === "function") {
                        d[a] = null;
                    }
                } catch(c) {}
            }
            try {
                d.parentNode.removeChild(d);
            } catch(e) {}
        }
        window[this.movieName] = null;
        SWFUpload.instances[this.movieName] = null;
        delete SWFUpload.instances[this.movieName];
        this.movieElement = null;
        this.settings = null;
        this.customSettings = null;
        this.eventQueue = null;
        this.movieName = null;
        return true;
    } catch(b) {
        return false;
    }
};
SWFUpload.prototype.displayDebugInfo = function() {
    this.debug(["---SWFUpload Instance Info---\n", "Version: ", SWFUpload.version, "\n", "Movie Name: ", this.movieName, "\n", "Settings:\n", "\t", "upload_url:               ", this.settings.upload_url, "\n", "\t", "flash_url:                ", this.settings.flash_url, "\n", "\t", "use_query_string:         ", this.settings.use_query_string.toString(), "\n", "\t", "requeue_on_error:         ", this.settings.requeue_on_error.toString(), "\n", "\t", "http_success:             ", this.settings.http_success.join(", "), "\n", "\t", "assume_success_timeout:   ", this.settings.assume_success_timeout, "\n", "\t", "file_post_name:           ", this.settings.file_post_name, "\n", "\t", "post_params:              ", this.settings.post_params.toString(), "\n", "\t", "file_types:               ", this.settings.file_types, "\n", "\t", "file_types_description:   ", this.settings.file_types_description, "\n", "\t", "file_size_limit:          ", this.settings.file_size_limit, "\n", "\t", "file_upload_limit:        ", this.settings.file_upload_limit, "\n", "\t", "file_queue_limit:         ", this.settings.file_queue_limit, "\n", "\t", "debug:                    ", this.settings.debug.toString(), "\n", "\t", "prevent_swf_caching:      ", this.settings.prevent_swf_caching.toString(), "\n", "\t", "button_placeholder_id:    ", this.settings.button_placeholder_id.toString(), "\n", "\t", "button_placeholder:       ", (this.settings.button_placeholder ? "Set": "Not Set"), "\n", "\t", "button_image_url:         ", this.settings.button_image_url.toString(), "\n", "\t", "button_width:             ", this.settings.button_width.toString(), "\n", "\t", "button_height:            ", this.settings.button_height.toString(), "\n", "\t", "button_text:              ", this.settings.button_text.toString(), "\n", "\t", "button_text_style:        ", this.settings.button_text_style.toString(), "\n", "\t", "button_text_top_padding:  ", this.settings.button_text_top_padding.toString(), "\n", "\t", "button_text_left_padding: ", this.settings.button_text_left_padding.toString(), "\n", "\t", "button_action:            ", this.settings.button_action.toString(), "\n", "\t", "button_disabled:          ", this.settings.button_disabled.toString(), "\n", "\t", "custom_settings:          ", this.settings.custom_settings.toString(), "\n", "Event Handlers:\n", "\t", "swfupload_loaded_handler assigned:  ", (typeof this.settings.swfupload_loaded_handler === "function").toString(), "\n", "\t", "file_dialog_start_handler assigned: ", (typeof this.settings.file_dialog_start_handler === "function").toString(), "\n", "\t", "file_queued_handler assigned:       ", (typeof this.settings.file_queued_handler === "function").toString(), "\n", "\t", "file_queue_error_handler assigned:  ", (typeof this.settings.file_queue_error_handler === "function").toString(), "\n", "\t", "upload_start_handler assigned:      ", (typeof this.settings.upload_start_handler === "function").toString(), "\n", "\t", "upload_progress_handler assigned:   ", (typeof this.settings.upload_progress_handler === "function").toString(), "\n", "\t", "upload_error_handler assigned:      ", (typeof this.settings.upload_error_handler === "function").toString(), "\n", "\t", "upload_success_handler assigned:    ", (typeof this.settings.upload_success_handler === "function").toString(), "\n", "\t", "upload_complete_handler assigned:   ", (typeof this.settings.upload_complete_handler === "function").toString(), "\n", "\t", "debug_handler assigned:             ", (typeof this.settings.debug_handler === "function").toString(), "\n"].join(""));
};
SWFUpload.prototype.addSetting = function(c, a, b) {
    if (a == undefined) {
        return (this.settings[c] = b);
    } else {
        return (this.settings[c] = a);
    }
};
SWFUpload.prototype.getSetting = function(a) {
    if (this.settings[a] != undefined) {
        return this.settings[a];
    }
    return "";
};
SWFUpload.prototype.callFlash = function(functionName, argumentArray) {
    argumentArray = argumentArray || [];
    var movieElement = this.getMovieElement();
    var returnValue, returnString;
    try {
        returnString = movieElement.CallFunction('<invoke name="' + functionName + '" returntype="javascript">' + __flash__argumentsToXML(argumentArray, 0) + "</invoke>");
        returnValue = eval(returnString);
    } catch(ex) {
        throw "Call to " + functionName + " failed";
    }
    if (returnValue != undefined && typeof returnValue.post === "object") {
        returnValue = this.unescapeFilePostParams(returnValue);
    }
    return returnValue;
};
SWFUpload.prototype.selectFile = function() {
    this.callFlash("SelectFile");
};
SWFUpload.prototype.selectFiles = function() {
    this.callFlash("SelectFiles");
};
SWFUpload.prototype.startUpload = function(a) {
    this.callFlash("StartUpload", [a]);
};
SWFUpload.prototype.cancelUpload = function(a, b) {
    if (b !== false) {
        b = true;
    }
    this.callFlash("CancelUpload", [a, b]);
};
SWFUpload.prototype.stopUpload = function() {
    this.callFlash("StopUpload");
};
SWFUpload.prototype.getStats = function() {
    return this.callFlash("GetStats");
};
SWFUpload.prototype.setStats = function(a) {
    this.callFlash("SetStats", [a]);
};
SWFUpload.prototype.getFile = function(a) {
    if (typeof(a) === "number") {
        return this.callFlash("GetFileByIndex", [a]);
    } else {
        return this.callFlash("GetFile", [a]);
    }
};
SWFUpload.prototype.addFileParam = function(b, c, a) {
    return this.callFlash("AddFileParam", [b, c, a]);
};
SWFUpload.prototype.removeFileParam = function(a, b) {
    this.callFlash("RemoveFileParam", [a, b]);
};
SWFUpload.prototype.setUploadURL = function(a) {
    this.settings.upload_url = a.toString();
    this.callFlash("SetUploadURL", [a]);
};
SWFUpload.prototype.setPostParams = function(a) {
    this.settings.post_params = a;
    this.callFlash("SetPostParams", [a]);
};
SWFUpload.prototype.addPostParam = function(a, b) {
    this.settings.post_params[a] = b;
    this.callFlash("SetPostParams", [this.settings.post_params]);
};
SWFUpload.prototype.removePostParam = function(a) {
    delete this.settings.post_params[a];
    this.callFlash("SetPostParams", [this.settings.post_params]);
};
SWFUpload.prototype.setFileTypes = function(a, b) {
    this.settings.file_types = a;
    this.settings.file_types_description = b;
    this.callFlash("SetFileTypes", [a, b]);
};
SWFUpload.prototype.setFileSizeLimit = function(a) {
    this.settings.file_size_limit = a;
    this.callFlash("SetFileSizeLimit", [a]);
};
SWFUpload.prototype.setFileUploadLimit = function(a) {
    this.settings.file_upload_limit = a;
    this.callFlash("SetFileUploadLimit", [a]);
};
SWFUpload.prototype.setFileQueueLimit = function(a) {
    this.settings.file_queue_limit = a;
    this.callFlash("SetFileQueueLimit", [a]);
};
SWFUpload.prototype.setFilePostName = function(a) {
    this.settings.file_post_name = a;
    this.callFlash("SetFilePostName", [a]);
};
SWFUpload.prototype.setUseQueryString = function(a) {
    this.settings.use_query_string = a;
    this.callFlash("SetUseQueryString", [a]);
};
SWFUpload.prototype.setRequeueOnError = function(a) {
    this.settings.requeue_on_error = a;
    this.callFlash("SetRequeueOnError", [a]);
};
SWFUpload.prototype.setHTTPSuccess = function(a) {
    if (typeof a === "string") {
        a = a.replace(" ", "").split(",");
    }
    this.settings.http_success = a;
    this.callFlash("SetHTTPSuccess", [a]);
};
SWFUpload.prototype.setAssumeSuccessTimeout = function(a) {
    this.settings.assume_success_timeout = a;
    this.callFlash("SetAssumeSuccessTimeout", [a]);
};
SWFUpload.prototype.setDebugEnabled = function(a) {
    this.settings.debug_enabled = a;
    this.callFlash("SetDebugEnabled", [a]);
};
SWFUpload.prototype.setButtonImageURL = function(a) {
    if (a == undefined) {
        a = "";
    }
    this.settings.button_image_url = a;
    this.callFlash("SetButtonImageURL", [a]);
};
SWFUpload.prototype.setButtonDimensions = function(a, b) {
    this.settings.button_width = a;
    this.settings.button_height = b;
    var c = this.getMovieElement();
    if (c != undefined) {
        c.style.width = a + "px";
        c.style.height = b + "px";
    }
    this.callFlash("SetButtonDimensions", [a, b]);
};
SWFUpload.prototype.setButtonText = function(a) {
    this.settings.button_text = a;
    this.callFlash("SetButtonText", [a]);
};
SWFUpload.prototype.setButtonTextPadding = function(b, a) {
    this.settings.button_text_top_padding = a;
    this.settings.button_text_left_padding = b;
    this.callFlash("SetButtonTextPadding", [b, a]);
};
SWFUpload.prototype.setButtonTextStyle = function(a) {
    this.settings.button_text_style = a;
    this.callFlash("SetButtonTextStyle", [a]);
};
SWFUpload.prototype.setButtonDisabled = function(a) {
    this.settings.button_disabled = a;
    this.callFlash("SetButtonDisabled", [a]);
};
SWFUpload.prototype.setButtonAction = function(a) {
    this.settings.button_action = a;
    this.callFlash("SetButtonAction", [a]);
};
SWFUpload.prototype.setButtonCursor = function(a) {
    this.settings.button_cursor = a;
    this.callFlash("SetButtonCursor", [a]);
};
SWFUpload.prototype.queueEvent = function(c, a) {
    if (a == undefined) {
        a = [];
    } else {
        if (! (a instanceof Array)) {
            a = [a];
        }
    }
    var b = this;
    if (typeof this.settings[c] === "function") {
        this.eventQueue.push(function() {
            this.settings[c].apply(this, a);
        });
        setTimeout(function() {
            b.executeNextEvent();
        },
        0);
    } else {
        if (this.settings[c] !== null) {
            throw "Event handler " + c + " is unknown or is not a function";
        }
    }
};
SWFUpload.prototype.executeNextEvent = function() {
    var a = this.eventQueue ? this.eventQueue.shift() : null;
    if (typeof(a) === "function") {
        a.apply(this);
    }
};
SWFUpload.prototype.unescapeFilePostParams = function(a) {
    var c = /[$]([0-9a-f]{4})/i;
    var d = {};
    var b;
    if (a != undefined) {
        for (var e in a.post) {
            if (a.post.hasOwnProperty(e)) {
                b = e;
                var f;
                while ((f = c.exec(b)) !== null) {
                    b = b.replace(f[0], String.fromCharCode(parseInt("0x" + f[1], 16)));
                }
                d[b] = a.post[e];
            }
        }
        a.post = d;
    }
    return a;
};
SWFUpload.prototype.testExternalInterface = function() {
    try {
        return this.callFlash("TestExternalInterface");
    } catch(a) {
        return false;
    }
};
SWFUpload.prototype.flashReady = function() {
    var a = this.getMovieElement();
    if (!a) {
        this.debug("Flash called back ready but the flash movie can't be found.");
        return;
    }
    this.cleanUp(a);
    this.queueEvent("swfupload_loaded_handler");
};
SWFUpload.prototype.cleanUp = function(c) {
    try {
        if (this.movieElement && typeof(c.CallFunction) === "unknown") {
            this.debug("Removing Flash functions hooks (this should only run in IE and should prevent memory leaks)");
            for (var a in c) {
                try {
                    if (typeof(c[a]) === "function") {
                        c[a] = null;
                    }
                } catch(d) {}
            }
        }
    } catch(b) {}
    window.__flash__removeCallback = function(g, e) {
        try {
            if (g) {
                g[e] = null;
            }
        } catch(f) {}
    };
};
SWFUpload.prototype.fileDialogStart = function() {
    this.queueEvent("file_dialog_start_handler");
};
SWFUpload.prototype.fileQueued = function(a) {
    a = this.unescapeFilePostParams(a);
    this.queueEvent("file_queued_handler", a);
};
SWFUpload.prototype.fileQueueError = function(b, a, c) {
    b = this.unescapeFilePostParams(b);
    this.queueEvent("file_queue_error_handler", [b, a, c]);
};
SWFUpload.prototype.fileDialogComplete = function(c, a, b) {
    this.queueEvent("file_dialog_complete_handler", [c, a, b]);
};
SWFUpload.prototype.uploadStart = function(a) {
    a = this.unescapeFilePostParams(a);
    this.queueEvent("return_upload_start_handler", a);
};
SWFUpload.prototype.returnUploadStart = function(a) {
    var b;
    if (typeof this.settings.upload_start_handler === "function") {
        a = this.unescapeFilePostParams(a);
        b = this.settings.upload_start_handler.call(this, a);
    } else {
        if (this.settings.upload_start_handler != undefined) {
            throw "upload_start_handler must be a function";
        }
    }
    if (b === undefined) {
        b = true;
    }
    b = !!b;
    this.callFlash("ReturnUploadStart", [b]);
};
SWFUpload.prototype.uploadProgress = function(b, a, c) {
    b = this.unescapeFilePostParams(b);
    this.queueEvent("upload_progress_handler", [b, a, c]);
};
SWFUpload.prototype.uploadError = function(b, a, c) {
    b = this.unescapeFilePostParams(b);
    this.queueEvent("upload_error_handler", [b, a, c]);
};
SWFUpload.prototype.uploadSuccess = function(c, b, a) {
    c = this.unescapeFilePostParams(c);
    this.queueEvent("upload_success_handler", [c, b, a]);
};
SWFUpload.prototype.uploadComplete = function(a) {
    a = this.unescapeFilePostParams(a);
    this.queueEvent("upload_complete_handler", a);
};
SWFUpload.prototype.debug = function(a) {
    this.queueEvent("debug_handler", a);
};
SWFUpload.prototype.debugMessage = function(a) {
    if (this.settings.debug) {
        var c, b = [];
        if (typeof a === "object" && typeof a.name === "string" && typeof a.message === "string") {
            for (var d in a) {
                if (a.hasOwnProperty(d)) {
                    b.push(d + ": " + a[d]);
                }
            }
            c = b.join("\n") || "";
            b = c.split("\n");
            c = "EXCEPTION: " + b.join("\nEXCEPTION: ");
            SWFUpload.Console.writeLine(c);
        } else {
            SWFUpload.Console.writeLine(a);
        }
    }
};
SWFUpload.Console = {};
SWFUpload.Console.writeLine = function(b) {
    var d, c;
    try {
        d = document.getElementById("SWFUpload_Console");
        if (!d) {
            c = document.createElement("form");
            document.getElementsByTagName("body")[0].appendChild(c);
            d = document.createElement("textarea");
            d.id = "SWFUpload_Console";
            d.style.fontFamily = "monospace";
            d.setAttribute("wrap", "off");
            d.wrap = "off";
            d.style.overflow = "auto";
            d.style.width = "700px";
            d.style.height = "350px";
            d.style.margin = "5px";
            c.appendChild(d);
        }
        d.value += b + "\n";
        d.scrollTop = d.scrollHeight - d.clientHeight;
    } catch(a) {
        alert("Exception: " + a.name + " Message: " + a.message);
    }
}; 
(function(f) {
    var d = {
        init: function(b, a) {
            return this.each(function() {
                var p = f(this);
                var q = p.clone();
                var t = f.extend({
                    id: p.attr("id"),
                    swf: "uploadify.swf",
                    uploader: "uploadify.php",
                    auto: true,
                    buttonClass: "",
                    buttonCursor: "hand",
                    buttonImage: null,
                    buttonText: "",
                    checkExisting: false,
                    debug: false,
                    fileObjName: "Filedata",
                    fileSizeLimit: 0,
                    fileTypeDesc: "All Files",
                    fileTypeExts: "*.*",
                    height: 30,
                    method: "post",
                    multi: true,
                    formData: {},
                    preventCaching: true,
                    progressData: "percentage",
                    queueID: false,
                    queueSizeLimit: 999,
                    removeCompleted: true,
                    removeTimeout: 3,
                    requeueErrors: false,
                    successTimeout: 30,
                    uploadLimit: 0,
                    width: 120,
                    overrideEvents: []
                },
                b);
                var w = {
                    assume_success_timeout: t.successTimeout,
                    button_placeholder_id: t.id,
                    button_width: t.width,
                    button_height: t.height,
                    button_text: null,
                    button_text_style: null,
                    button_text_top_padding: 0,
                    button_text_left_padding: 0,
                    button_action: (t.multi ? SWFUpload.BUTTON_ACTION.SELECT_FILES: SWFUpload.BUTTON_ACTION.SELECT_FILE),
                    button_disabled: false,
                    button_cursor: (t.buttonCursor == "arrow" ? SWFUpload.CURSOR.ARROW: SWFUpload.CURSOR.HAND),
                    button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
                    debug: t.debug,
                    requeue_on_error: t.requeueErrors,
                    file_post_name: t.fileObjName,
                    file_size_limit: t.fileSizeLimit,
                    file_types: t.fileTypeExts,
                    file_types_description: t.fileTypeDesc,
                    file_queue_limit: t.queueSizeLimit,
                    file_upload_limit: t.uploadLimit,
                    flash_url: t.swf,
                    prevent_swf_caching: t.preventCaching,
                    post_params: t.formData,
                    upload_url: t.uploader,
                    use_query_string: (t.method == "get"),
                    file_dialog_complete_handler: e.onDialogClose,
                    file_dialog_start_handler: e.onDialogOpen,
                    file_queued_handler: e.onSelect,
                    file_queue_error_handler: e.onSelectError,
                    swfupload_loaded_handler: t.onSWFReady,
                    upload_complete_handler: e.onUploadComplete,
                    upload_error_handler: e.onUploadError,
                    upload_progress_handler: e.onUploadProgress,
                    upload_start_handler: e.onUploadStart,
                    upload_success_handler: e.onUploadSuccess
                };
                if (a) {
                    w = f.extend(w, a);
                }
                w = f.extend(w, t);
                var c = swfobject.getFlashPlayerVersion();
                var v = (c.major >= 9);
                if (v) {
                    window["uploadify_" + t.id] = new SWFUpload(w);
                    var u = window["uploadify_" + t.id];
                    p.data("uploadify", u);
                    var r = f("<div />", {
                        id: t.id,
                        "class": "uploadify",
                        css: {
                            height: t.height + "px",
                            width: t.width + "px"
                        }
                    });
                    f("#" + u.movieName).wrap(r);
                    r = f("#" + t.id);
                    r.data("uploadify", u);
                    var x = f("<div />", {
                        id: t.id + "-button",
                        "class": "uploadify-button " + t.buttonClass
                    });
                    if (t.buttonImage) {
                        x.css({
                            "background-image": "url('" + t.buttonImage + "')",
                            "text-indent": "-9999px"
                        });
                    }
                    x.html('<span class="uploadify-button-text">' + t.buttonText + "</span>").css({
                        height: t.height + "px",
                        "line-height": t.height + "px",
                        width: t.width + "px"
                    });
                    r.append(x);
                    f("#" + u.movieName).css({
                        position: "absolute",
                        "z-index": 1
                    });
                    if (!t.queueID) {
                        var s = f("<div />", {
                            id: t.id + "-queue",
                            "class": "uploadify-queue"
                        });
                        r.after(s);
                        u.settings.queueID = t.id + "-queue";
                        u.settings.defaultQueue = true;
                    }
                    u.queueData = {
                        files: {},
                        filesSelected: 0,
                        filesQueued: 0,
                        filesReplaced: 0,
                        filesCancelled: 0,
                        filesErrored: 0,
                        uploadsSuccessful: 0,
                        uploadsErrored: 0,
                        averageSpeed: 0,
                        queueLength: 0,
                        queueSize: 0,
                        uploadSize: 0,
                        queueBytesUploaded: 0,
                        uploadQueue: [],
                        errorMsg: "文件添加失败:"
                    };
                    u.original = q;
                    u.wrapper = r;
                    u.button = x;
                    u.queue = s;
                    if (t.onInit) {
                        t.onInit.call(p, u);
                    }
                } else {
                    if (t.onFallback) {
                        t.onFallback.call(p);
                    }
                }
            });
        },
        cancel: function(c, a) {
            var b = arguments;
            this.each(function() {
                var o = f(this),
                r = o.data("uploadify"),
                q = r.settings,
                s = -1;
                if (b[0]) {
                    if (b[0] == "*") {
                        var t = r.queueData.queueLength;
                        f("#" + q.queueID).find(".uploadify-queue-item").each(function() {
                            s++;
                            if (b[1] === true) {
                                r.cancelUpload(f(this).attr("id"), false);
                            } else {
                                r.cancelUpload(f(this).attr("id"));
                            }
                            f(this).find(".data").removeClass("data").html(" - Cancelled");
                            f(this).find(".uploadify-progress-bar").remove();
                            f(this).delay(1000 + 100 * s).fadeOut(500,
                            function() {
                                f(this).remove();
                            });
                        });
                        r.queueData.queueSize = 0;
                        if (q.onClearQueue) {
                            q.onClearQueue.call(o, t);
                        }
                    } else {
                        for (var n = 0; n < b.length; n++) {
                            r.cancelUpload(b[n]);
                            f("#" + b[n]).find(".data").removeClass("data").html(" - Cancelled");
                            f("#" + b[n]).find(".uploadify-progress-bar").remove();
                            f("#" + b[n]).delay(1000 + 100 * n).fadeOut(500,
                            function() {
                                f(this).remove();
                            });
                        }
                    }
                } else {
                    var p = f("#" + q.queueID).find(".uploadify-queue-item").get(0);
                    $item = f(p);
                    r.cancelUpload($item.attr("id"));
                    $item.find(".data").removeClass("data").html(" - Cancelled");
                    $item.find(".uploadify-progress-bar").remove();
                    $item.delay(1000).fadeOut(500,
                    function() {
                        f(this).remove();
                    });
                }
            });
        },
        destroy: function() {
            this.each(function() {
                var a = f(this),
                c = a.data("uploadify"),
                b = c.settings;
                c.destroy();
                if (b.defaultQueue) {
                    f("#" + b.queueID).remove();
                }
                f("#" + b.id).replaceWith(c.original);
                if (b.onDestroy) {
                    b.onDestroy.call(this);
                }
                delete c;
            });
        },
        disable: function(a) {
            this.each(function() {
                var b = f(this),
                h = b.data("uploadify"),
                c = h.settings;
                if (a) {
                    h.button.addClass("disabled");
                    if (c.onDisable) {
                        c.onDisable.call(this);
                    }
                } else {
                    h.button.removeClass("disabled");
                    if (c.onEnable) {
                        c.onEnable.call(this);
                    }
                }
                h.setButtonDisabled(a);
            });
        },
        settings: function(i, b, a) {
            var j = arguments;
            var c = b;
            this.each(function() {
                var h = f(this),
                n = h.data("uploadify"),
                m = n.settings;
                if (typeof(j[0]) == "object") {
                    for (var g in b) {
                        setData(g, b[g]);
                    }
                }
                if (j.length === 1) {
                    c = m[i];
                } else {
                    switch (i) {
                    case "uploader":
                        n.setUploadURL(b);
                        break;
                    case "formData":
                        if (!a) {
                            b = f.extend(m.formData, b);
                        }
                        n.setPostParams(m.formData);
                        break;
                    case "method":
                        if (b == "get") {
                            n.setUseQueryString(true);
                        } else {
                            n.setUseQueryString(false);
                        }
                        break;
                    case "fileObjName":
                        n.setFilePostName(b);
                        break;
                    case "fileTypeExts":
                        n.setFileTypes(b, m.fileTypeDesc);
                        break;
                    case "fileTypeDesc":
                        n.setFileTypes(m.fileTypeExts, b);
                        break;
                    case "fileSizeLimit":
                        n.setFileSizeLimit(b);
                        break;
                    case "uploadLimit":
                        n.setFileUploadLimit(b);
                        break;
                    case "queueSizeLimit":
                        n.setFileQueueLimit(b);
                        break;
                    case "buttonImage":
                        n.button.css("background-image", settingValue);
                        break;
                    case "buttonCursor":
                        if (b == "arrow") {
                            n.setButtonCursor(SWFUpload.CURSOR.ARROW);
                        } else {
                            n.setButtonCursor(SWFUpload.CURSOR.HAND);
                        }
                        break;
                    case "buttonText":
                        f("#" + m.id + "-button").find(".uploadify-button-text").html(b);
                        break;
                    case "width":
                        n.setButtonDimensions(b, m.height);
                        break;
                    case "height":
                        n.setButtonDimensions(m.width, b);
                        break;
                    case "multi":
                        if (b) {
                            n.setButtonAction(SWFUpload.BUTTON_ACTION.SELECT_FILES);
                        } else {
                            n.setButtonAction(SWFUpload.BUTTON_ACTION.SELECT_FILE);
                        }
                        break;
                    }
                    m[i] = b;
                }
            });
            if (j.length === 1) {
                return c;
            }
        },
        stop: function() {
            this.each(function() {
                var a = f(this),
                b = a.data("uploadify");
                b.queueData.averageSpeed = 0;
                b.queueData.uploadSize = 0;
                b.queueData.bytesUploaded = 0;
                b.queueData.uploadQueue = [];
                b.stopUpload();
            });
        },
        upload: function() {
            var a = arguments;
            this.each(function() {
                var c = f(this),
                h = c.data("uploadify");
                h.queueData.averageSpeed = 0;
                h.queueData.uploadSize = 0;
                h.queueData.bytesUploaded = 0;
                h.queueData.uploadQueue = [];
                if (a[0]) {
                    if (a[0] == "*") {
                        h.queueData.uploadSize = h.queueData.queueSize;
                        h.queueData.uploadQueue.push("*");
                        h.startUpload();
                    } else {
                        for (var b = 0; b < a.length; b++) {
                            h.queueData.uploadSize += h.queueData.files[a[b]].size;
                            h.queueData.uploadQueue.push(a[b]);
                        }
                        h.startUpload(h.queueData.uploadQueue.shift());
                    }
                } else {
                    h.startUpload();
                }
            });
        }
    };
    var e = {
        onDialogOpen: function() {
            var a = this.settings;
            this.queueData.errorMsg = "";
            this.queueData.filesReplaced = 0;
            this.queueData.filesCancelled = 0;
            if (a.onDialogOpen) {
                a.onDialogOpen.call(this);
            }
        },
        onDialogClose: function(h, b, a) {
            var c = this.settings;
            this.queueData.filesErrored = h - b;
            this.queueData.filesSelected = h;
            this.queueData.filesQueued = b - this.queueData.filesCancelled;
            this.queueData.queueLength = a;
            if (f.inArray("onDialogClose", c.overrideEvents) < 0) {
                if (this.queueData.filesErrored > 0) {
                    $.jBox.tip(this.queueData.errorMsg, "warning");
                }
            }
            if (c.onDialogClose) {
                c.onDialogClose.call(this, this.queueData);
            }
            if (c.auto) {
                f("#" + c.id).uploadify("upload", "*");
            }
        },
        onSelect: function(o) {
            var n = this.settings;
            var q = {};
            for (var p in this.queueData.files) {
                q = this.queueData.files[p];
                if (q.uploaded != true && q.name == o.name) {
                    this.queueData.filesReplaced++;
                }
            }
            var m = Math.round(o.size / 1024);
            var a = "KB";
            if (m > 1000) {
                m = Math.round(m / 1000);
                a = "MB";
            }
            var b = m.toString().split(".");
            m = b[0];
            if (b.length > 1) {
                m += "." + b[1].substr(0, 2);
            }
            m += a;
            var c = o.name;
            if (c.length > 25) {
                c = c.substr(0, 25) + "...";
            }
            if (f.inArray("onSelect", n.overrideEvents) < 0) {
                f("#" + n.queueID).append('<div id="' + o.id + '" class="uploadify-queue-item">                 <div class="cancel">                        <a href="javascript:$(\'#' + n.id + "').uploadify('cancel', '" + o.id + '\')">X</a>                 </div>                  <span class="fileName">' + c + " (" + m + ')</span><span class="data"></span>                   <div class="uploadify-progress">                        <div class="uploadify-progress-bar"><!--Progress Bar--></div>                   </div>              </div>');
            }
            this.queueData.queueSize += o.size;
            this.queueData.files[o.id] = o;
            if (n.onSelect) {
                n.onSelect.apply(this, arguments);
            }
        },
        onSelectError: function(h, a, b) {
            var c = this.settings;
            if (f.inArray("onSelectError", c.overrideEvents) < 0) {
                switch (a) {
                case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                    if (c.queueSizeLimit > b) {
                        this.queueData.errorMsg += "\n 上传的图片不能超过 (" + b + ").";
                    } else {
                        this.queueData.errorMsg += "\n为了保证上传速度，一次最多选" + c.queueSizeLimit + "张，超出的稍后再选";
                    }
                    break;
                case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                    this.queueData.errorMsg += '\n图片"' + h.name + '" 大小超过了14M，请压缩一下';
                    break;
                case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                    this.queueData.errorMsg += '\nThe file "' + h.name + '" is empty.';
                    break;
                case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                    this.queueData.errorMsg += '\n图片格式"' + h.name + '" 不是可接受的格式 (' + c.fileTypeDesc + ").";
                    break;
                }
            }
            if (a != SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
                delete this.queueData.files[h.id];
            }
            if (c.onSelectError) {
                c.onSelectError.apply(this, arguments);
            }
        },
        onQueueComplete: function() {
            if (this.settings.onQueueComplete) {
                this.settings.onQueueComplete.call(this, this.settings.queueData);
            }
        },
        onUploadComplete: function(b) {
            var a = this.settings,
            h = this;
            var c = this.getStats();
            this.queueData.queueLength = c.files_queued;
            if (this.queueData.uploadQueue[0] == "*") {
                if (this.queueData.queueLength > 0) {
                    this.startUpload();
                } else {
                    this.queueData.uploadQueue = [];
                    if (a.onQueueComplete) {
                        a.onQueueComplete.call(this, this.queueData);
                    }
                }
            } else {
                if (this.queueData.uploadQueue.length > 0) {
                    this.startUpload(this.queueData.uploadQueue.shift());
                } else {
                    this.queueData.uploadQueue = [];
                    if (a.onQueueComplete) {
                        a.onQueueComplete.call(this, this.queueData);
                    }
                }
            }
            if (f.inArray("onUploadComplete", a.overrideEvents) < 0) {
                if (a.removeCompleted) {
                    switch (b.filestatus) {
                    case SWFUpload.FILE_STATUS.COMPLETE:
                        setTimeout(function() {
                            if (f("#" + b.id)) {
                                h.queueData.queueSize -= b.size;
                                delete h.queueData.files[b.id];
                                f("#" + b.id).fadeOut(500,
                                function() {
                                    f(this).remove();
                                });
                            }
                        },
                        a.removeTimeout * 1000);
                        break;
                    case SWFUpload.FILE_STATUS.ERROR:
                        if (!a.requeueErrors) {
                            setTimeout(function() {
                                if (f("#" + b.id)) {
                                    h.queueData.queueSize -= b.size;
                                    delete h.queueData.files[b.id];
                                    f("#" + b.id).fadeOut(500,
                                    function() {
                                        f(this).remove();
                                    });
                                }
                            },
                            a.removeTimeout * 1000);
                        }
                        break;
                    }
                } else {
                    b.uploaded = true;
                }
            }
            if (a.onUploadComplete) {
                a.onUploadComplete.call(this, b);
            }
        },
        onUploadError: function(k, a, b) {
            var j = this.settings;
            var c = "Error";
            switch (a) {
            case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                c = "HTTP Error (" + b + ")";
                break;
            case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
                c = "Missing Upload URL";
                break;
            case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                c = "IO Error";
                break;
            case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                c = "Security Error";
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                alert("The upload limit has been reached (" + b + ").");
                c = "Exceeds Upload Limit";
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                c = "Failed";
                break;
            case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                c = "Validation Error";
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                c = "Cancelled";
                this.queueData.queueSize -= k.size;
                if (k.status == SWFUpload.FILE_STATUS.IN_PROGRESS || f.inArray(k.id, this.queueData.uploadQueue) >= 0) {
                    this.queueData.uploadSize -= k.size;
                }
                if (j.onCancel) {
                    j.onCancel.call(this, k);
                }
                delete this.queueData.files[k.id];
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                c = "Stopped";
                break;
            }
            if (f.inArray("onUploadError", j.overrideEvents) < 0) {
                if (a != SWFUpload.UPLOAD_ERROR.FILE_CANCELLED && a != SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED) {
                    f("#" + k.id).addClass("uploadify-error");
                }
                f("#" + k.id).find(".uploadify-progress-bar").css("width", "1px");
                if (a != SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND && k.status != SWFUpload.FILE_STATUS.COMPLETE) {
                    f("#" + k.id).find(".data").html(" - " + c);
                }
            }
            var l = this.getStats();
            this.queueData.uploadsErrored = l.upload_errors;
            if (j.onUploadError) {
                j.onUploadError.call(this, k, a, b, c);
            }
        },
        onUploadProgress: function(w, q, t) {
            var v = this.settings;
            var y = new Date();
            var c = y.getTime();
            var s = c - this.timer;
            if (s > 500) {
                this.timer = c;
            }
            var u = q - this.bytesLoaded;
            this.bytesLoaded = q;
            var z = this.queueData.queueBytesUploaded + q;
            var a = Math.round(q / t * 100);
            var b = "KB/s";
            var r = 0;
            var x = (u / 1024) / (s / 1000);
            x = Math.floor(x * 10) / 10;
            if (this.queueData.averageSpeed > 0) {
                this.queueData.averageSpeed = Math.floor((this.queueData.averageSpeed + x) / 2);
            } else {
                this.queueData.averageSpeed = Math.floor(x);
            }
            if (x > 1000) {
                r = (x * 0.001);
                this.queueData.averageSpeed = Math.floor(r);
                b = "MB/s";
            }
            if (f.inArray("onUploadProgress", v.overrideEvents) < 0) {
                if (v.progressData == "percentage") {
                    f("#" + w.id).find(".data").html(" - " + a + "%");
                } else {
                    if (v.progressData == "speed" && s > 500) {
                        f("#" + w.id).find(".data").html(" - " + this.queueData.averageSpeed + b);
                    }
                }
                f("#" + w.id).find(".uploadify-progress-bar").css("width", a + "%");
            }
            if (v.onUploadProgress) {
                v.onUploadProgress.call(this, w, q, t, z, this.queueData.uploadSize);
            }
        },
        onUploadStart: function(c) {
            var b = this.settings;
            var a = new Date();
            this.timer = a.getTime();
            this.bytesLoaded = 0;
            if (this.queueData.uploadQueue.length == 0) {
                this.queueData.uploadSize = c.size;
            }
            if (b.checkExisting) {
                f.ajax({
                    type: "POST",
                    async: false,
                    url: b.checkExisting,
                    data: {
                        filename: c.name
                    },
                    success: function(i) {
                        if (i == 1) {
                            var j = confirm('图片 "' + c.name + '" 已经存在.\n 你想覆盖这张图片吗?');
                            if (!j) {
                                this.cancelUpload(c.id);
                                f("#" + c.id).remove();
                                if (this.queueData.uploadQueue.length > 0 && this.queueData.queueLength > 0) {
                                    if (this.queueData.uploadQueue[0] == "*") {
                                        this.startUpload();
                                    } else {
                                        this.startUpload(this.queueData.uploadQueue.shift());
                                    }
                                }
                            }
                        }
                    }
                });
            }
            if (b.onUploadStart) {
                b.onUploadStart.call(this, c);
            }
        },
        onUploadSuccess: function(c, a, j) {
            var b = this.settings;
            var i = this.getStats();
            this.queueData.uploadsSuccessful = i.successful_uploads;
            this.queueData.queueBytesUploaded += c.size;
            if (f.inArray("onUploadSuccess", b.overrideEvents) < 0) {
                f("#" + c.id).find(".data").html(" - Complete");
            }
            if (b.onUploadSuccess) {
                b.onUploadSuccess.call(this, c, a, j);
            }
        }
    };
    f.fn.uploadify = function(a) {
        if (d[a]) {
            return d[a].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            if (typeof a === "object" || !a) {
                return d.init.apply(this, arguments);
            } else {
                f.error("The method " + a + " does not exist in $.uploadify");
            }
        }
    };
})($);

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
$.jBox.setDefaults(jBoxConfig); 
(function($) {
    var supportedCSS, styles = document.getElementsByTagName("head")[0].style,
    toCheck = "transformProperty WebkitTransform OTransform msTransform MozTransform".split(" ");
    for (var a = 0; a < toCheck.length; a++) {
        if (styles[toCheck[a]] !== undefined) {
            supportedCSS = toCheck[a];
        }
    }
    var IE = eval('"v"=="\v"');
    jQuery.fn.extend({
        rotate: function(parameters) {
            if (this.length === 0 || typeof parameters == "undefined") {
                return;
            }
            if (typeof parameters == "number") {
                parameters = {
                    angle: parameters
                };
            }
            var returned = [];
            for (var i = 0,
            i0 = this.length; i < i0; i++) {
                var element = this.get(i);
                if (!element.Wilq32 || !element.Wilq32.PhotoEffect) {
                    var paramClone = $.extend(true, {},
                    parameters);
                    var newRotObject = new Wilq32.PhotoEffect(element, paramClone)._rootObj;
                    returned.push($(newRotObject));
                } else {
                    element.Wilq32.PhotoEffect._handleRotation(parameters);
                }
            }
            return returned;
        },
        getRotateAngle: function() {
            var ret = [];
            for (var i = 0,
            i0 = this.length; i < i0; i++) {
                var element = this.get(i);
                if (element.Wilq32 && element.Wilq32.PhotoEffect) {
                    ret[i] = element.Wilq32.PhotoEffect._angle;
                }
            }
            return ret;
        },
        stopRotate: function() {
            for (var i = 0,
            i0 = this.length; i < i0; i++) {
                var element = this.get(i);
                if (element.Wilq32 && element.Wilq32.PhotoEffect) {
                    clearTimeout(element.Wilq32.PhotoEffect._timer);
                }
            }
        }
    });
    Wilq32 = window.Wilq32 || {};
    Wilq32.PhotoEffect = (function() {
        if (supportedCSS) {
            return function(img, parameters) {
                img.Wilq32 = {
                    PhotoEffect: this
                };
                this._img = this._rootObj = this._eventObj = img;
                this._handleRotation(parameters);
            };
        } else {
            return function(img, parameters) {
                this._img = img;
                this._rootObj = document.createElement("span");
                this._rootObj.style.display = "inline-block";
                this._rootObj.Wilq32 = {
                    PhotoEffect: this
                };
                img.parentNode.insertBefore(this._rootObj, img);
                if (img.complete) {
                    this._Loader(parameters);
                } else {
                    var self = this;
                    jQuery(this._img).bind("load",
                    function() {
                        self._Loader(parameters);
                    });
                }
            };
        }
    })();
    Wilq32.PhotoEffect.prototype = {
        _setupParameters: function(parameters) {
            this._parameters = this._parameters || {};
            if (typeof this._angle !== "number") {
                this._angle = 0;
            }
            if (typeof parameters.angle === "number") {
                this._angle = parameters.angle;
            }
            this._parameters.animateTo = (typeof parameters.animateTo === "number") ? (parameters.animateTo) : (this._angle);
            this._parameters.step = parameters.step || this._parameters.step || null;
            this._parameters.easing = parameters.easing || this._parameters.easing ||
            function(x, t, b, c, d) {
                return - c * ((t = t / d - 1) * t * t * t - 1) + b;
            };
            this._parameters.duration = parameters.duration || this._parameters.duration || 1000;
            this._parameters.callback = parameters.callback || this._parameters.callback ||
            function() {};
            if (parameters.bind && parameters.bind != this._parameters.bind) {
                this._BindEvents(parameters.bind);
            }
        },
        _handleRotation: function(parameters) {
            this._setupParameters(parameters);
            if (this._angle == this._parameters.animateTo) {
                this._rotate(this._angle);
            } else {
                this._animateStart();
            }
        },
        _BindEvents: function(events) {
            if (events && this._eventObj) {
                if (this._parameters.bind) {
                    var oldEvents = this._parameters.bind;
                    for (var a in oldEvents) {
                        if (oldEvents.hasOwnProperty(a)) {
                            jQuery(this._eventObj).unbind(a, oldEvents[a]);
                        }
                    }
                }
                this._parameters.bind = events;
                for (var a in events) {
                    if (events.hasOwnProperty(a)) {
                        jQuery(this._eventObj).bind(a, events[a]);
                    }
                }
            }
        },
        _Loader: (function() {
            if (IE) {
                return function(parameters) {
                    var width = this._img.width;
                    var height = this._img.height;
                    this._img.parentNode.removeChild(this._img);
                    this._vimage = this.createVMLNode("image");
                    this._vimage.src = this._img.src;
                    this._vimage.style.height = height + "px";
                    this._vimage.style.width = width + "px";
                    this._vimage.style.position = "absolute";
                    this._vimage.style.top = "0px";
                    this._vimage.style.left = "0px";
                    this._container = this.createVMLNode("group");
                    this._container.style.width = width;
                    this._container.style.height = height;
                    this._container.style.position = "absolute";
                    this._container.setAttribute("coordsize", width - 1 + "," + (height - 1));
                    this._container.appendChild(this._vimage);
                    this._rootObj.appendChild(this._container);
                    this._rootObj.style.position = "relative";
                    this._rootObj.style.width = width + "px";
                    this._rootObj.style.height = height + "px";
                    this._rootObj.setAttribute("id", this._img.getAttribute("id"));
                    this._rootObj.className = this._img.className;
                    this._eventObj = this._rootObj;
                    this._handleRotation(parameters);
                };
            } else {
                return function(parameters) {
                    this._rootObj.setAttribute("id", this._img.getAttribute("id"));
                    this._rootObj.className = this._img.className;
                    this._width = this._img.width;
                    this._height = this._img.height;
                    this._widthHalf = this._width / 2;
                    this._heightHalf = this._height / 2;
                    var _widthMax = Math.sqrt((this._height) * (this._height) + (this._width) * (this._width));
                    this._widthAdd = _widthMax - this._width;
                    this._heightAdd = _widthMax - this._height;
                    this._widthAddHalf = this._widthAdd / 2;
                    this._heightAddHalf = this._heightAdd / 2;
                    this._img.parentNode.removeChild(this._img);
                    this._aspectW = ((parseInt(this._img.style.width, 10)) || this._width) / this._img.width;
                    this._aspectH = ((parseInt(this._img.style.height, 10)) || this._height) / this._img.height;
                    this._canvas = document.createElement("canvas");
                    this._canvas.setAttribute("width", this._width);
                    this._canvas.style.position = "relative";
                    this._canvas.style.left = -this._widthAddHalf + "px";
                    this._canvas.style.top = -this._heightAddHalf + "px";
                    this._canvas.Wilq32 = this._rootObj.Wilq32;
                    this._rootObj.appendChild(this._canvas);
                    this._rootObj.style.width = this._width + "px";
                    this._rootObj.style.height = this._height + "px";
                    this._eventObj = this._canvas;
                    this._cnv = this._canvas.getContext("2d");
                    this._handleRotation(parameters);
                };
            }
        })(),
        _animateStart: function() {
            if (this._timer) {
                clearTimeout(this._timer);
            }
            this._animateStartTime = +new Date;
            this._animateStartAngle = this._angle;
            this._animate();
        },
        _animate: function() {
            var actualTime = +new Date;
            var checkEnd = actualTime - this._animateStartTime > this._parameters.duration;
            if (checkEnd && !this._parameters.animatedGif) {
                clearTimeout(this._timer);
            } else {
                if (this._canvas || this._vimage || this._img) {
                    var angle = this._parameters.easing(0, actualTime - this._animateStartTime, this._animateStartAngle, this._parameters.animateTo - this._animateStartAngle, this._parameters.duration);
                    this._rotate((~~ (angle * 10)) / 10);
                }
                if (this._parameters.step) {
                    this._parameters.step(this._angle);
                }
                var self = this;
                this._timer = setTimeout(function() {
                    self._animate.call(self);
                },
                10);
            }
            if (this._parameters.callback && checkEnd) {
                this._angle = this._parameters.animateTo;
                this._rotate(this._angle);
                this._parameters.callback.call(this._rootObj);
            }
        },
        _rotate: (function() {
            var rad = Math.PI / 180;
            if (IE) {
                return function(angle) {
                    this._angle = angle;
                    this._container.style.rotation = (angle % 360) + "deg";
                };
            } else {
                if (supportedCSS) {
                    return function(angle) {
                        this._angle = angle;
                        this._img.style[supportedCSS] = "rotate(" + (angle % 360) + "deg)";
                    };
                } else {
                    return function(angle) {
                        this._angle = angle;
                        angle = (angle % 360) * rad;
                        this._canvas.width = this._width + this._widthAdd;
                        this._canvas.height = this._height + this._heightAdd;
                        this._cnv.translate(this._widthAddHalf, this._heightAddHalf);
                        this._cnv.translate(this._widthHalf, this._heightHalf);
                        this._cnv.rotate(angle);
                        this._cnv.translate( - this._widthHalf, -this._heightHalf);
                        this._cnv.scale(this._aspectW, this._aspectH);
                        this._cnv.drawImage(this._img, 0, 0);
                    };
                }
            }
        })()
    };
    if (IE) {
        Wilq32.PhotoEffect.prototype.createVMLNode = (function() {
            document.createStyleSheet().addRule(".rvml", "behavior:url(#default#VML)");
            try { ! document.namespaces.rvml && document.namespaces.add("rvml", "urn:schemas-microsoft-com:vml");
                return function(tagName) {
                    return document.createElement("<rvml:" + tagName + ' class="rvml">');
                };
            } catch(e) {
                return function(tagName) {
                    return document.createElement("<" + tagName + ' xmlns="urn:schemas-microsoft.com:vml" class="rvml">');
                };
            }
        })();
    }
})(jQuery);
var shareBindList = new Array();
function getShareListStr() {
    var a = new Array();
    if ($("#bind_sina").hasClass("bind_sina")) {
        a.push(2);
    }
    if ($("#bind_qqlg").hasClass("bind_qqlg")) {
        a.push(3);
    }
    if ($("#bind_qzone").hasClass("bind_qzone")) {
        a.push(7);
    }
    return a.toString();
}
$(function() {
    var b = $("#shareList").val();
    if (b != null) {
        b = b.split("");
        for (var a = 0; a < b.length; a++) {
            if (b[a] != null) {
                if (b[a] == 2) {
                    shareBindList.push(2);
                    $("#bind_sina").removeClass("bind_sina_cancel");
                    $("#bind_sina").addClass("bind_sina");
                } else {
                    if (b[a] == 3) {
                        $("#bind_qqlg").removeClass("bind_qqlg_cancel");
                        $("#bind_qqlg").addClass("bind_qqlg");
                        shareBindList.push(3);
                    } else {
                        if (b[a] == 7) {
                            $("#bind_qzone").removeClass("bind_qzone_cancel");
                            $("#bind_qzone").addClass("bind_qzone");
                            shareBindList.push(7);
                        }
                    }
                }
            }
        }
    }
    $("#bind_sina").click(function() {
        if ($("#bind_sina").hasClass("bind_sina")) {
            $("#bind_sina").removeClass("bind_sina");
            $("#bind_sina").addClass("bind_sina_cancel");
        } else {
            if ($("#bind_sina").hasClass("bind_sina_cancel")) {
                if (!shareBindList.contains(2)) {
                    var d = $("#userid").val();
                    var c = "http://oauth.qunar.com/oauth-client/sina/login?appname=lvtu&reference=ext&method=login&ret=" + encodeURIComponent(pathUpload + "web/oauthbind.htm?lvtuUserId=" + d) + "&vistor=" + encodeURIComponent("http://user.qunar.com/oauth/login.jsp");
                    window.open(c);
                }
                $("#bind_sina").addClass("bind_sina");
                $("#bind_sina").removeClass("bind_sina_cancel");
            }
        }
    });
    $("#bind_qqlg").click(function() {
        if ($("#bind_qqlg").hasClass("bind_qqlg")) {
            $("#bind_qqlg").removeClass("bind_qqlg");
            $("#bind_qqlg").addClass("bind_qqlg_cancel");
        } else {
            if ($("#bind_qqlg").hasClass("bind_qqlg_cancel")) {
                if (!shareBindList.contains(3)) {
                    var d = $("#userid").val();
                    var c = "http://oauth.qunar.com/oauth-client/qq/login?appname=www&reference=ext&method=login&ret=" + encodeURIComponent(pathUpload + "web/oauthbind.htm?lvtuUserId=" + d) + "&vistor=" + encodeURIComponent("http://user.qunar.com/oauth/login.jsp");
                    window.open(c);
                }
                $("#bind_qqlg").removeClass("bind_qqlg_cancel");
                $("#bind_qqlg").addClass("bind_qqlg");
            }
        }
    });
    $("#bind_qzone").click(function() {
        if ($("#bind_qzone").hasClass("bind_qzone")) {
            $("#bind_qzone").removeClass("bind_qzone");
            $("#bind_qzone").addClass("bind_qzone_cancel");
        } else {
            if ($("#bind_qzone").hasClass("bind_qzone_cancel")) {
                if (!shareBindList.contains(7)) {
                    var d = $("#userid").val();
                    var c = "http://oauth.qunar.com/oauth-client/qq/login?appname=www&reference=ext&method=login&ret=" + encodeURIComponent(pathUpload + "web/oauthbind.htm?lvtuUserId=" + d) + "&vistor=" + encodeURIComponent("http://user.qunar.com/oauth/login.jsp");
                    window.open(c);
                }
                $("#bind_qzone").removeClass("bind_qzone_cancel");
                $("#bind_qzone").addClass("bind_qzone");
            }
        }
    });
});
var timer;
$(function() {
    $("#editAlbumF").tokenInput("web/searchPOI.htm", {
        theme: "facebook",
        tokenLimit: 1
    });
    $(".J-specialk-btn").bind({
        mouseover: function() {
            $(".J-specialk-btn").addClass("specialk_btn_out").removeClass("specialk_btn specialk_btn_down");
        },
        mouseout: function() {
            $(".J-specialk-btn").addClass("specialk_btn").removeClass("specialk_btn_out specialk_btn_down");
        },
        mousedown: function() {
            $(".J-specialk-btn").addClass("specialk_btn_down").removeClass("specialk_btn specialk_btn_out");
        }
    });
});
var id = 0;
var templateId = 0;
var settingFirstCover = false;
var queueLen = 0;
var isUploading = false;
function getAddressStr(c) {
    var a = "!";
    for (var b = 0; b < c.length; b++) {
        if (c[b].id != null) {
            a = a + c[b].id + "!";
        }
    }
    return a;
}
$(document).ready(function() {
    $("#id_upload").uploadify({
        height: 30,
        swf: pathUpload + "views/new/image/uploadify.swf",
        uploader: pathUpload + "?module=album&action=upimg",
        buttonImage: "lvtu/views/new/image/add_one_03.jpg",
        fileTypeExts: "*.jpg; *.png; *.jpeg;",
        fileObjName: "myfile",
        width: 156,
        queueSizeLimit: 30,
        queueID: "query0",
        fileSizeLimit: "14336KB",
        removeCompleted: false,
        onInit: function(a) {
            isUploading = false;
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        },
        onUploadProgress: function(b, e, d, a, c) {
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        },
        onDialogClose: function(a) {
            queueLen = a.filesQueued;
        },
        onSelect: function(b) {
            $.jBox.tip("正在分析照片信息...", "loading");
            isUploading = true;
            var a = $("#query").children().length;
            if (a > 2) {
                return false;
            }
        },
        onFallback: function() {
            $("#uploadFlash").empty();
            $("#uploadFlash").html('<a target="_blank" href="http://www.adobe.com/cn/products/flashplayer/" id="downloadflash">请下载最新Flash插件</a>');
        },
        onSelectError: function() {
            id++;
        },
        onUploadError: function() {
            id++;
            close();
            printNum();
            uploadFilter();
        },
        onQueueComplete: function(a) {
            if ($("#imageId ul li img").length < 10) {
                $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
                $("#finish").removeAttr("disabled");
                $(".jk_upload_add").css("visibility", "visible");
            } else {
                if ($("#imageId ul li img").length == 10) {
                    $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
                    $("#finish").removeAttr("disabled");
                    $(".jk_upload_add").css("visibility", "hidden");
                } else {
                    if ($("#imageId ul li img").length > 10) {
                        $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
                        $("#finish").attr("disabled", true);
                        $(".jk_upload_add").css("visibility", "hidden");
                    }
                }
            }
        },
        onUploadSuccess: function(b, d, c) {
            id++;
            $(".lvtu_specialadd_content_pic,.lvtu_special_shoot").css("display", "block");
            var d = jQuery.parseJSON(d);
            if (d == null) {
                var e = "";
                e += "<li class='fn-pr' id=" + id + ">";
                e += "<span class='close jk_close fn-pa'></span>";
                e += "<span  style='width:186px;height: 124px;display:block;background-color: #F6F6F6;'>图片上传失败</span>";
                e += "</li>";
                $("#imageId ul").append(e);
                close();
                printNum();
                uploadFilter();
                queueLen = queueLen - 1;
                if (queueLen == 0) {
                    isUploading = false;
                    $("#imageId ul").append("<li><div><input type='file'   id='id_upload0' name='id_upload0'/></span></div></li>");
                    upload1(0, 0);
                }
            } else {
                var a = false;
                $("#id_upload-button").attr("style", "");
                $(".pictip").addClass("fn-none");
                var e = "";
                e += "<li class='fn-pr' id=" + id + ">";
                $.each(d.activity,
                function(j, k) {
                    settingFirstCover = $("#hasAlbumCover").val();
                    e += "<span class='fn-pa'></span>";
                    e += "<span class='lvtu_special_text fn-pa f12'></span>";
                    e += "<div class='lvtu_special_tips fn-pa white fn-none'>";
                    e += "<span class='tips_f fn-fl cover'><a class='png24' href='javascript:void(0)' >封面</a></span>";
                    e += "<span class='tips_s fn-fl close'><a class='png24' href='javascript:void(0)'  >删除</a></span>";
                    e += "<span class='tips_x fn-fl rotate'><a class='png24' href='javascript:void(0)' >旋转</a></span>";
                    e += "</div>";
                    e += "<div class='lvtu_special_service fn-pa  fn-none'>";
                    e += $("#category").html();
                    e += "</div>";
                    e += "<div class='special_imgc'><div class='imgp'><img id='imgget' name=" + k.src;
                    e += " src='" + k.url;
                    e += "'  width='255' height='186'/></div></div>";
                    if (k.width < 400) {
                        a = true;
                        window.setTimeout(function() {
                            $.jBox.tip("抱歉，暂不支持400*400以下尺寸", "warning");
                        },
                        2000);
                        return;
                    }
                    if (k.length < 400) {
                        a = true;
                        window.setTimeout(function() {
                            $.jBox.tip("抱歉，暂不支持400*400以下尺寸,无法上传", "warning");
                        },
                        2000);
                        return;
                    }
                    e += "<p><textarea class='border-ca gray' name='desc' cols='' rows=''>图片描述（可不填写）</textarea></p>";
                    e += "<input type='hidden' name='picWidth' value= '" + k.width + "' />";
                    e += "<input type='hidden' name='picLength' value= '" + k.length + "' />";
                });
                e += "<input type='hidden' name='names' value= '" + d.exif.name + "' />";
                e += "<input type='hidden' name='camera_time' value= '" + d.exif.Camera_Time + "' />";
                e += "<input type='hidden' name='camera_model' value= '" + d.exif.Camera_Model + "' />";
                e += "<input type='hidden' name='fnumber' value= '" + d.exif.Fnumber + "'  />";
                e += "<input type='hidden' name='camera_iso' value= '" + d.exif.Camera_ISO + "' />";
                e += "<input type='hidden' name='exposure_time' value= '" + d.exif.Exposure_Time + "' />";
                e += "<input type='hidden' name='exposure_bias' value= '" + d.exif.Exposure_Bias + "' />";
                e += "<input type='hidden' name='focal_length' value= '" + d.exif.Focal_Length + "' />";
                e += "<input type='hidden' name='lon' value= '" + d.exif.GpsLongitude + "' />";
                e += "<input type='hidden' name='lat' value= '" + d.exif.GpsLatitude + "' />";
                e += "</li>";
                if (a == false) {
                    $("#imageId ul").eq(0).append(e);
                } else {
                    if (a == true) {
                        var e = "";
                        e += "<li class='fn-pr fn-none' id=" + id + ">";
                        e += "<span class='close jk_close fn-pa'></span>";
                        e += "<span style='width:186px;height: 124px;display:block;background-color: #F6F6F6;'>抱歉，暂不支持400*400以下尺寸的照片</span>";
                        e += "</li>";
                        $("#imageId ul").eq(0).append(e);
                        var g = $("#imageId ul li:last").index();
                        $("#query0 .uploadify-queue-item").eq(g).remove();
                        $("#imageId ul li:eq(" + g + ")").remove();
                        uploadFilter();
                    }
                }
                if (templateId == 0) {
                    templateId++;
                    var i = $("#uploadTemplate").html();
                    i = i.replace("id_upload", "id_upload1").replace("batch", "batch1");
                    $("#nextPic").append(i);
                    var f = $("#PicTemplate").html().replace("query", "query" + templateId);
                    f = f.replace("block", "none");
                    $("#batch1").append(f);
                    upload(templateId);
                }
                close();
                printNum();
                queueLen = queueLen - 1;
                $("#batch" + templateId + " .lvtu_specialadd_content_pic").css("display", "none");
                $("#batch" + templateId + " .lvtu_special_shoot").css("display", "none");
                if (queueLen == 0) {
                    isUploading = false;
                    $("#imageId ul").append("<li><div><input type='file'   id='id_upload0' name='id_upload0'/></span></div></li>");
                    upload1(0, 0);
                    $(" #id_upload").css("visibility", "hidden");
                    $(" #id_upload").parent().next().remove();
                    var h = $(".lvtu_special_button").offset().top;
                    $("html,body").animate({
                        scrollTop: h
                    },
                    1);
                }
            }
            uploadFilter();
            $.jBox.closeTip();
        }
    });
});
var batch = 0;
var upload1PicNum = 0;
function upload1(a, b) {
    $("#id_upload" + a).uploadify({
        height: 124,
        swf: pathUpload + "image/uploadify.swf",
        uploader: pathUpload + "web/uploadAlbum.htm",
        buttonImage: "http://source.qunar.com/site/images/wap/lvtu/zj/1/special_add.jpg",
        fileTypeExts: "*.jpg; *.png; *.jpeg;",
        fileObjName: "myfile",
        width: 186,
        queueSizeLimit: 30,
        queueID: "query" + b,
        fileSizeLimit: "14336KB",
        removeCompleted: false,
        onInit: function(c) {
            $("#id_upload" + a).css("z-index", "30");
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        },
        onUploadProgress: function(d, g, f, c, e) {
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        },
        onDialogClose: function(c) {
            upload1PicNum = c.filesQueued;
        },
        onSelect: function(d) {
            $.jBox.tip("正在分析照片信息...", "loading");
            isUploading = true;
            var c = $("#query").children().length;
            if (c > 2) {
                return false;
            }
        },
        onFallback: function() {
            $("#addwork").html('<a target="_blank" href="http://www.adobe.com/cn/products/flashplayer/" id="downloadflash">请下载最新Flash插件</a>');
        },
        onSelectError: function() {
            b++;
        },
        onUploadError: function() {
            b++;
            close();
            printNum();
            uploadFilter();
        },
        onQueueComplete: function(c) {
            if ($("#imageId ul li img").length < 10) {
                $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
                $("#finish").removeAttr("disabled");
                $(".jk_upload_add").css("visibility", "visible");
            } else {
                if ($("#imageId ul li img").length == 10) {
                    $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
                    $("#finish").removeAttr("disabled");
                    $(".jk_upload_add").css("visibility", "hidden");
                } else {
                    if ($("#imageId ul li img").length > 10) {
                        $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
                        $("#finish").attr("disabled", true);
                        $(".jk_upload_add").css("visibility", "hidden");
                    }
                }
            }
        },
        onUploadStart: function(c) {
            $("#id_upload" + a).css("z-index", "-100");
        },
        onUploadSuccess: function(d, f, e) {
            $.jBox.closeTip();
            $(".lvtu_specialadd_content_pic,.lvtu_special_shoot").css("display", "block");
            var f = jQuery.parseJSON(f);
            if (f == null) {
                var g = "";
                g += "<li class='fn-pr' id=" + b + ">";
                g += "<span class='close jk_close fn-pa'></span>";
                g += "<span  style='width:186px;height: 124px;display:block;background-color: #F6F6F6;'>图片上传失败</span>";
                g += "</li>";
                close();
                printNum();
                uploadFilter();
                if (a == 0) {
                    var j = $("#imageId ul li").length;
                    j = j - 1;
                    $("#imageId ul li:eq(" + j + ")").before(g);
                } else {
                    var k;
                    if (a.indexOf("_") != -1) {
                        k = a.substring(0, a.length - 1);
                    }
                    var j = $("#batch" + k + " .imageIdX ul li").length;
                    j = j;
                    $("#batch" + k + " ul li:eq(" + j + ")").before(g);
                }
                $("#batch" + templateId + " .lvtu_specialadd_content_pic").css("display", "none");
            } else {
                var c = false;
                var g = "";
                g += "<li class='fn-pr' id=" + b + ">";
                $.each(f.activity,
                function(n, o) {
                    settingFirstCover = $("#hasAlbumCover").val();
                    g += "<span class='fn-pa'></span>";
                    g += "<span class='lvtu_special_text fn-pa f12'></span>";
                    g += "<div class='lvtu_special_tips fn-pa white fn-none'>";
                    g += "<span class='tips_f fn-fl cover'><a class='png24' href='javascript:void(0)' >封面</a></span>";
                    g += "<span class='tips_s fn-fl close'><a class='png24' href='javascript:void(0)'  >删除</a></span>";
                    g += "<span class='tips_x fn-fl rotate'><a class='png24' href='javascript:void(0)' >旋转</a></span>";
                    g += "</div>";
                    g += "<div class='lvtu_special_service fn-pa  fn-none'>";
                    g += $("#category").html();
                    g += "</div>";
                    g += "<div class='special_imgc'><div class='imgp'><img id='imgget' name=" + o.src;
                    g += " src='" + o.url;
                    g += "'  width='255' height='186'/></div></div>";
                    if (o.width < 400) {
                        c = true;
                        window.setTimeout(function() {
                            $.jBox.tip("抱歉，暂不支持400*400以下尺寸", "warning");
                        },
                        2000);
                        return;
                    }
                    if (o.length < 400) {
                        c = true;
                        window.setTimeout(function() {
                            $.jBox.tip("抱歉，暂不支持400*400以下尺寸,无法上传", "warning");
                        },
                        2000);
                        return;
                    }
                    g += "<p><textarea class='border-ca gray' name='desc' cols='' rows=''>图片描述（可不填写）</textarea></p>";
                    g += "<input type='hidden' name='picWidth' value= '" + o.width + "' />";
                    g += "<input type='hidden' name='picLength' value= '" + o.length + "' />";
                });
                g += "<input type='hidden' name='names' value= '" + f.exif.name + "' />";
                g += "<input type='hidden' name='camera_time' value= '" + f.exif.Camera_Time + "' />";
                g += "<input type='hidden' name='camera_model' value= '" + f.exif.Camera_Model + "' />";
                g += "<input type='hidden' name='fnumber' value= '" + f.exif.Fnumber + "'  />";
                g += "<input type='hidden' name='camera_iso' value= '" + f.exif.Camera_ISO + "' />";
                g += "<input type='hidden' name='exposure_time' value= '" + f.exif.Exposure_Time + "' />";
                g += "<input type='hidden' name='exposure_bias' value= '" + f.exif.Exposure_Bias + "' />";
                g += "<input type='hidden' name='focal_length' value= '" + f.exif.Focal_Length + "' />";
                g += "<input type='hidden' name='lon' value= '" + f.exif.GpsLongitude + "' />";
                g += "<input type='hidden' name='lat' value= '" + f.exif.GpsLatitude + "' />";
                g += "</li>";
                if (a == 0) {
                    if (c == false) {
                        var j = $("#imageId ul li").length;
                        j = j - 1;
                        $("#imageId ul li:eq(" + j + ")").before(g);
                    } else {
                        if (c == true) {
                            var g = "";
                            g += "<li class='fn-pr fn-none' id=" + b + ">";
                            g += "<span class='close jk_close fn-pa'></span>";
                            g += "<span style='width:186px;height: 124px;background-color: #F6F6F6;display:block;'>抱歉，暂不支持400*400以下尺寸的照片</span>";
                            g += "</li>";
                            j = j - 1;
                            $("#imageId ul li:eq(" + j + ")").before(g);
                            var i = $("#imageId ul li:last").index();
                            i = i - 1;
                            $("#query0 .uploadify-queue-item").eq(i).remove();
                            $("#imageId ul li:eq(" + j + ")").remove();
                            uploadFilter();
                        }
                    }
                } else {
                    var k;
                    if (a.indexOf("_") != -1) {
                        k = a.substring(0, a.length - 1);
                    }
                    if (c == false) {
                        var j = $("#batch" + k + " .imageIdX ul li").length;
                        j = j;
                        $("#batch" + k + " ul li:eq(" + j + ")").before(g);
                    } else {
                        if (c == true) {
                            var g = "";
                            g += "<li class='fn-pr fn-none' id=" + b + ">";
                            g += "<span class='close jk_close fn-pa'></span>";
                            g += "<span style='width:186px;height: 124px;display:block;background-color: #F6F6F6;'>抱歉，暂不支持400*400以下尺寸的照片</span>";
                            g += "</li>";
                            var j = $("#batch" + k + " .imageIdX ul li").length;
                            j = j;
                            j = j - 1;
                            $("#batch" + k + " ul li:eq(" + j + ")").before(g);
                            var i = $("#batch" + k + " ul li:eq(" + j + ") :last").index();
                            i = i - 1;
                            $("#query" + k + " .uploadify-queue-item").eq(i).remove();
                            $("#batch" + k + "  ul li:eq(" + j + ")").remove();
                            uploadFilter();
                        }
                    }
                }
                if (templateId == 0) {
                    templateId++;
                    var m = $("#uploadTemplate").html();
                    m = m.replace("id_upload", "id_upload1").replace("batch", "batch1");
                    $("#nextPic").append(m);
                    var h = $("#PicTemplate").html().replace("query", "query" + templateId);
                    h = h.replace("block", "none");
                    $("#batch1").append(h);
                    upload(templateId);
                }
                close();
                printNum();
                uploadFilter();
                $("#batch" + templateId + " .lvtu_specialadd_content_pic").css("display", "none");
                $("#batch" + templateId + "  .lvtu_special_shoot ").css("display", "none");
                $("#id_upload" + a).css("z-index", "30");
            }
            $("#id_upload" + a).css("visibility", "visible");
            upload1PicNum = upload1PicNum - 1;
            if (upload1PicNum == 0) {
                isUploading = false;
            }
            var l = $(".lvtu_special_button").offset().top;
            $("html,body").animate({
                scrollTop: l
            },
            1);
        }
    });
}
var picNum = 0;
function upload(a) {
    $("#id_upload" + a).uploadify({
        height: 30,
        swf: pathUpload + "image/uploadify.swf",
        uploader: pathUpload + "web/uploadAlbum.htm",
        buttonImage: "http://source.qunar.com/site/images/wap/lvtu/zj/1/specialadd_next.jpg",
        fileTypeExts: "*.jpg; *.png; *.jpeg;",
        fileObjName: "myfile",
        width: 126,
        queueID: "query" + a,
        queueSizeLimit: 30,
        fileSizeLimit: "14336KB",
        removeCompleted: false,
        onInit: function(b) {
            $("#batch" + a).find("input[name='batchdes']").tokenInput("web/searchPOI.htm", {
                theme: "facebook",
                tokenLimit: 1
            });
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        },
        onUploadProgress: function(c, f, e, b, d) {
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        },
        onDialogClose: function(b) {
            picNum = b.filesQueued;
        },
        onSelect: function(c) {
            $.jBox.tip("正在分析照片信息...", "loading");
            isUploading = true;
            var b = $("#query").children().length;
            if (b > 2) {
                return false;
            }
        },
        onFallback: function() {
            $("#addwork").html('<a target="_blank" href="http://www.adobe.com/cn/products/flashplayer/" id="downloadflash">请下载最新Flash插件</a>');
        },
        onSelectError: function() {},
        onUploadError: function() {
            a++;
            close();
            printNum();
            uploadFilter();
        },
        onQueueComplete: function(b) {
            if ($("#imageId ul li img").length < 10) {
                $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
                $("#finish").removeAttr("disabled");
                $(".jk_upload_add").css("visibility", "visible");
            } else {
                if ($("#imageId ul li img").length == 10) {
                    $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
                    $("#finish").removeAttr("disabled");
                    $(".jk_upload_add").css("visibility", "hidden");
                } else {
                    if ($("#imageId ul li img").length > 10) {
                        $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
                        $("#finish").attr("disabled", true);
                        $(".jk_upload_add").css("visibility", "hidden");
                    }
                }
            }
        },
        onUploadSuccess: function(c, e, d) {
            $(".lvtu_specialadd_content_pic,.lvtu_special_shoot").css("display", "block");
            var e = jQuery.parseJSON(e);
            if (e == null) {
                var f = "";
                f += "<li class='fn-pr' id=" + a + ">";
                f += "<span class='close jk_close fn-pa'></span>";
                f += "<span  style='width:186px;height: 124px;display:block;background-color: #F6F6F6;'>图片上传失败</span>";
                f += "</li>";
                $("#imageId ul").append(f);
                close();
                printNum();
                uploadFilter();
            } else {
                var b = false;
                $("#id_upload" + a + "-button").attr("style", "");
                $(".pictip").addClass("fn-none");
                var f = "";
                f += "<li class='fn-pr' id=" + a + ">";
                $.each(e.activity,
                function(m, n) {
                    f += "<span class=' fn-pa'></span>";
                    f += "<span class='lvtu_special_text fn-pa f12'></span>";
                    f += "<div class='lvtu_special_tips fn-pa white fn-none'>";
                    f += "<span class='tips_f fn-fl cover'><a class='png24' href='javascript:void(0)'  >封面</a></span>";
                    f += "<span class='tips_s fn-fl close'><a class='png24' href='javascript:void(0)'  >删除</a></span>";
                    f += "<span class='tips_x fn-fl rotate'><a class='png24' href='javascript:void(0)' >旋转</a></span>";
                    f += "</div>";
                    f += "<div class='lvtu_special_service fn-pa  fn-none'>";
                    f += $("#category").html();
                    f += "</div>";
                    f += "<div class='special_imgc'><div class='imgp'><img id='imgget' name=" + n.src;
                    f += " src='" + n.url;
                    f += "'  width='255' height='186'/></div></div>";
                    if (n.width < 400) {
                        b = true;
                        window.setTimeout(function() {
                            $.jBox.tip("抱歉，暂不支持400*400以下尺寸", "warning");
                        },
                        2000);
                        return;
                    }
                    if (n.length < 400) {
                        b = true;
                        window.setTimeout(function() {
                            $.jBox.tip("抱歉，暂不支持400*400以下尺寸,无法上传", "warning");
                        },
                        2000);
                        return;
                    }
                    f += "<p><textarea class='border-ca gray'  name='desc' cols='' rows=''>图片描述（可不填写）</textarea></p>";
                    f += "<input type='hidden' name='picWidth' value= '" + n.width + "' />";
                    f += "<input type='hidden' name='picLength' value= '" + n.length + "' />";
                });
                f += "<input type='hidden' name='names' value= '" + e.exif.name + "' />";
                f += "<input type='hidden' name='camera_time' value= '" + e.exif.Camera_Time + "' />";
                f += "<input type='hidden' name='camera_model' value= '" + e.exif.Camera_Model + "' />";
                f += "<input type='hidden' name='fnumber' value= '" + e.exif.Fnumber + "'  />";
                f += "<input type='hidden' name='camera_iso' value= '" + e.exif.Camera_ISO + "' />";
                f += "<input type='hidden' name='exposure_time' value= '" + e.exif.Exposure_Time + "' />";
                f += "<input type='hidden' name='exposure_bias' value= '" + e.exif.Exposure_Bias + "' />";
                f += "<input type='hidden' name='focal_length' value= '" + e.exif.Focal_Length + "' />";
                f += "<input type='hidden' name='lon' value= '" + e.exif.GpsLongitude + "' />";
                f += "<input type='hidden' name='lat' value= '" + e.exif.GpsLatitude + "' />";
                f += "</li>";
                if (b == false) {
                    $("#batch" + a + " .lvtu_special_pic").find("ul").eq(0).append(f);
                } else {
                    if (b == true) {
                        var f = "";
                        f += "<li class='fn-pr fn-none' id=" + a + ">";
                        f += "<span class='close jk_close fn-pa'></span>";
                        f += "<span style='width:186px;height: 124px;display:block;background-color: #F6F6F6;'>抱歉，暂不支持400*400以下尺寸的照片</span>";
                        f += "</li>";
                        $("#batch" + a + " .lvtu_special_pic").find("ul").not(0).append(f);
                        var h = $("#batch" + a + " .lvtu_special_pic ul  li:last").index();
                        $("#query" + a + " .uploadify-queue-item").eq(h).remove();
                        $("#batch" + a + " .imageIdX ul li:eq(" + h + ")").remove();
                        uploadFilter();
                    }
                }
                if (templateId == a) {
                    templateId++;
                    var j = $("#uploadTemplate").html();
                    j = j.replace("id_upload", "id_upload" + templateId).replace("batch", "batch" + templateId);
                    $("#nextPic").append(j);
                    var g = $("#PicTemplate").html().replace("query", "query" + templateId);
                    g = g.replace("block", "none");
                    $("#batch" + templateId).append(g);
                    upload(templateId);
                }
                close();
                printNum();
                picNum = picNum - 1;
                if (picNum == 0) {
                    isUploading = false;
                    $("#id_upload" + a).css("visibility", "hidden");
                    $("#id_upload" + a).parent().next().css("visibility", "hidden");
                    var l = a + 1;
                    var k = a + "_";
                    $("#batch" + a + " .lvtu_special_pic").find("ul").eq(0).append("<li><div><input type='file'   id='id_upload" + k + "' ></span></div></li>");
                    upload1(k, a);
                    var i = $(".lvtu_special_button").offset().top;
                    $("html,body").animate({
                        scrollTop: i
                    },
                    1);
                }
                $("#batch" + templateId + "  .lvtu_specialadd_content_pic").css("display", "none");
                $("#batch" + templateId + "  .lvtu_special_shoot ").css("display", "none");
                uploadFilter();
                $.jBox.closeTip();
            }
        }
    });
}
function close() {
    $(".close").unbind("click");
    $(".close").click(function() {
        var a = $(this).parent().parent().index();
        $(this).parent().parent().parent().next().parent().find(".uploadify-queue-item").eq(a).remove();
        var b = "SWFUpload_0_" + a;
        $(this).parent().parent().remove();
        if ($("#query").children().length == 0) {
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
        }
        num();
    });
    $(".cover").unbind("click");
    $(".cover").click(function() {
        $(".lvtu_special_cover").removeClass("lvtu_special_cover");
        $(this).parent().parent().find("span").eq(0).toggleClass("lvtu_special_cover");
    });
    $(".rotate").unbind("click");
    $(".rotate").click(function() {
        var e = 90;
        var b = $(this).parent().parent().find("#imgget");
        var d = b.getRotateAngle();
        if (d != null && d != "" && d != undefined) {
            var a = parseInt(d / 90);
            var f = d % 90;
            if (f > 0) {
                e = e + (a + 1) * 90;
            } else {
                e = e + a * 90;
            }
        }
        function c() {
            if (timer != null && timer != undefined && timer != "") {
                clearTimeout(timer);
            }
            timer = window.setTimeout(function() {
                var l = b.getRotateAngle();
                var g = parseInt(l / 90);
                var n = g % 4;
                if (n != 0) {
                    var k = b.parent().parent().parent();
                    var h = k.find("input[name='names']").val();
                    var i = n * 90;
                    if (h != null && $.trim(h) != "" && i != null && $.trim(i) != "") {
                        var j = new Date().getTime();
                        var m = {
                            name: h,
                            degree: i,
                            random: j
                        };
                        $.getJSON("web/rotate.htm", m,
                        function(o) {
                            if (o.picrotate != null) {
                                if ($.browser.msie && $.browser.version < 9) {
                                    b.parent().find("#imgget").attr("name", o.picrotate.src);
                                } else {
                                    b.attr("name", o.picrotate.src);
                                }
                                k.find("input[name='picWidth']").val(o.picrotate.width);
                                k.find("input[name='picLength']").val(o.picrotate.length);
                            }
                        });
                    }
                }
            },
            100);
        }
        b.rotate({
            animateTo: e,
            callback: c
        });
    });
}
var defaultword = "图片描述（可不填写）";
var worldlen = "140";
function printNum() {
    $("#imageId ul li textarea").each(function() {
        if ($.trim($(this).val()) == defaultword) {
            $(this).val(defaultword).css("color", "#ccc");
        }
        $(this).focusin(function() {
            if ($.trim($(this).val()) != defaultword && $.trim($(this).val()).length > 0) {} else {
                $(this).val("").css("color", "#000");
            }
        }).focusout(function() {
            if ($.trim($(this).val()).length == 0) {
                $(this).val(defaultword).css("color", "#ccc");
            }
        });
        $(this).bind("keyup paste keydown",
        function() {
            var curLength = $.trim($(this).val()).length;
            if (curLength > worldlen) {
                var num = $.trim($(this).val()).length - worldlen;
                $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>(<em class='orange'>" + eval(parseInt(worldlen) + parseInt(num)) + "</em>字/" + worldlen + "字,您已超过<em class='orange'>" + num + "</em>字)");
            } else {
                if (curLength == 0) {
                    if ($.trim($(this).val()) == "") {
                        $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>还可以输入<span>" + worldlen + "</span>个字</div>");
                    } else {
                        $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>还可以输入<span>" + worldlen - 1 + "</span>个字</div>");
                    }
                } else {
                    var num = worldlen - $.trim($(this).val()).length;
                    $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>还可以输入<span>" + num + "</span>个字</div>");
                }
            }
            setState();
        });
    });
    $(".imageIdX ul li textarea").each(function() {
        if ($.trim($(this).val()) == defaultword) {
            $(this).val(defaultword).css("color", "#ccc");
        }
        $(this).focusin(function() {
            if ($.trim($(this).val()) != defaultword && $.trim($(this).val()).length > 0) {} else {
                $(this).val("").css("color", "#000");
            }
        }).focusout(function() {
            if ($.trim($(this).val()).length == 0) {
                $(this).val(defaultword).css("color", "#ccc");
            }
        });
        $(this).bind("keyup paste keydown",
        function() {
            var curLength = $.trim($(this).val()).length;
            if (curLength > worldlen) {
                var num = $.trim($(this).val()).length - worldlen;
                $(this).parent().parent().find(".lvtu_special_text").html("<p class='jk_error f12 pl5 fn-pa'>(" + eval(parseInt(worldlen) + parseInt(num)) + "字/" + worldlen + "字，您已超过" + num + "字)</p>");
                $(this).parent().parent().find(".lvtu_special_text").html("<div class='r fn-fl'>(<em class='orange'>" + worldlen + "</em>字/" + eval(parseInt(worldlen) + parseInt(num)) + "字,您已超过<em class='orange'>" + num + "</em>字)</div>");
            } else {
                if (curLength == 0) {
                    if ($.trim($(this).val()) == "") {
                        $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>还可以输入<span>" + worldlen + "</span>个字</div>");
                    } else {
                        $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>还可以输入<span>" + worldlen - 1 + "</span>个字</div>");
                    }
                } else {
                    var num = worldlen - $.trim($(this).val()).length;
                    $(this).parent().parent().find(".lvtu_special_text").html("<div class='lvtu_special_text_bg fn-fl'>还可以输入<span>" + num + "</span>个字</div>");
                }
            }
            setState();
        });
    });
}
function num() {
    var a = $("#imageId ul li textarea").length;
    if ($("#imageId ul li img").length < 10 && a <= 10) {
        setState();
        $(".jk_upload_add").css("visibility", "visible");
    } else {
        if ($("#imageId ul li img").length == 10 && a <= 10) {
            setState();
            $(".jk_upload_add").css("display", "hidden");
        } else {
            if ($("#imageId ul li img").length > 10 || a > 10) {
                setState();
                $(".jk_upload_add").css("display", "hidden");
            }
        }
    }
    if ($("#query").children().length == 0 && $("#imageId ul li img").length < 10) {
        setState();
        $(".jk_upload_add").css("visibility", "visible");
    }
}
function setState() {
    $("#imageId ul li textarea").each(function() {
        var b = $("#imageId ul li textarea").length;
        var a = $.trim($(this).val()).length;
        if (a > worldlen || b > 10) {
            $("#finish").attr("class", "jk_upload_undone ml15 mb15 mr15 fn-tc");
            $("#finish").attr("disabled", true);
            return false;
        } else {
            $("#finish").attr("class", "jk_upload_achieve ml15 mb15 mr15 fn-tc");
            $("#finish").removeAttr("disabled");
        }
    });
}
function uploadFilter() {
    $(".lvtu_special_pic li:not(:last)").hover(function() {
        $(this).addClass("background-e");
        $(this).find(".lvtu_special_tips").fadeIn();
    },
    function() {
        $(this).removeClass("background-e");
        $(this).find(".lvtu_special_tips").fadeOut();
    });
    $(".lvtu_special_pic textarea").focus(function() {
        if ($(this).val() == "图片描述（可不填写）") {
            $(this).val("").removeClass("gray");
        }
    }).blur(function() {
        if ($(this).val().length == 0) {
            $(this).val("图片描述（可不填写）").addClass("gray");
        }
    });
    $("input[type=text],textarea").focus(function() {
        $(this).removeClass("border-ca");
        $(this).addClass("border-blue");
    }).blur(function() {
        $(this).removeClass("border-blue");
        $(this).addClass("border-ca");
    });
    $(".lvtu_special_service span a").click(function() {
        $(this).removeClass("service_link");
        $(this).addClass("service_down").parent().siblings().children().removeClass("service_down").addClass("service_link");
    });
    $(".lvtu_special_service span a").click(function() {
        $(this).removeClass("service_link");
        $(this).addClass("service_down").parent().siblings().children().removeClass("service_down").addClass("service_link");
    });
}
$(".J-specialk-btn").click(function() {
    var h = window.location.href;
    var l = new Array();
    l = h.split("&");
    var k;
    var e;
    var f;
    var o;
    if (l != null && l.length > 1) {
        k = l[1].split("=")[1];
        e = l[2].split("=")[1];
        f = l[3].split("=")[1];
        o = l[4];
    }
    var b = true;
    if (isUploading == true) {
        $.jBox.tip("亲，图片还没传完呢，急啥嘛", "loading");
    } else {
        if (isUploading == false) {
            var a = new Date().getTime();
            var c = $("#albumName").val();
            var m = $("#description").val();
            var q = $("#albumId").val();
            var s = $("#editAlbumF").tokenInput("get");
            var r = getAddressStr(s);
            var d = false;
            if (b == false) {
                $.jBox.tip("您的信息填写不完善啊，请完善后再创建", "warning");
                return false;
            }
            if ($("#query0").html() == "") {
                $.jBox.tip("请至少添加一张图片", "warning");
                return false;
            }
            var j = "<xml>";
            j += "      <id>" + q + "</id>";
            j += "      <userId>255</userId>";
            j += "      <shareList>" + getShareListStr() + "</shareList>";
            j += "      <batchList>";
            j += "          <batch>";
            j += "               <batchdes>" + r + "</batchdes>";
            j += "               <batchPicList>";
            $("#imageId  ul  li").each(function() {
                var w = $(this).find("#imgget").attr("name");
                var B = $(this).find("textarea[name='desc']").val();
                var y = $(this).find("input[name='camera_time']").val();
                var z = $(this).find("input[name='camera_model']").val();
                var C = $(this).find("input[name='fnumber']").val();
                var F = $(this).find("input[name='camera_iso']").val();
                var G = $(this).find("input[name='exposure_time']").val();
                var x = $(this).find("input[name='exposure_bias']").val();
                var v = $(this).find("input[name='focal_length']").val();
                var u = $(this).find("input[name='picWidth']").val();
                var A = $(this).find("input[name='picLength']").val();
                var i = $(this).find("input[name='lon']").val();
                var D = $(this).find("input[name='lat']").val();
                var E = $(this).find(".service_down").attr("id");
                var H = $(this).find(".lvtu_special_cover").html();
                if (H != null) {
                    H = true;
                } else {
                    H = false;
                }
                if (B == "图片描述（可不填写）") {
                    B = "";
                }
                if (B != null && B.length > 140) {
                    d = true;
                }
                z = msgEscape(z);
                F = msgEscape(F);
                B = msgEscape(B);
                if (B == "undefined" || B == undefined || B == null) {
                    B = "";
                }
                if (w != null) {
                    j += "                   <batchPic>";
                    j += "                        <url>" + w + "</url>";
                    j += "                        <category>" + E + "</category>";
                    j += "                        <desc>" + B + "</desc>";
                    j += "                        <isCover>" + H + "</isCover>";
                    j += "                        <picWidth>" + u + "</picWidth>";
                    j += "                        <picLength>" + A + "</picLength>";
                    j += "                        <exif>";
                    j += "                        <Camera_Time>" + y + "</Camera_Time>";
                    j += "                        <Camera_Model>" + z + "</Camera_Model>";
                    j += "                        <Fnumber>" + C + "</Fnumber>";
                    j += "                        <Camera_ISO>" + F + "</Camera_ISO>";
                    j += "                        <Exposure_Time>" + G + "</Exposure_Time>";
                    j += "                        <Exposure_Bias>" + x + "</Exposure_Bias>";
                    j += "                        <Focal_Length>" + v + "</Focal_Length>";
                    j += "                        <GpsLongitude>" + i + "</GpsLongitude>";
                    j += "                        <GpsLatitude>" + D + "</GpsLatitude>";
                    j += "                        </exif>";
                    j += "                   </batchPic>";
                }
            });
            j += "               </batchPicList>";
            j += "          </batch>";
            for (var n = 1; n < templateId; n++) {
                var p = $("#batch" + n).find("input[name='batchdes']").tokenInput("get");
                var g = getAddressStr(p);
                j += "          <batch>";
                j += "               <batchdes>" + g + "</batchdes>";
                j += "               <batchPicList>";
                $("#batch" + n + " ul li").each(function() {
                    var w = $(this).find("#imgget").attr("name");
                    var B = $(this).find("textarea[name='desc']").val();
                    var y = $(this).find("input[name='camera_time']").val();
                    var z = $(this).find("input[name='camera_model']").val();
                    var C = $(this).find("input[name='fnumber']").val();
                    var F = $(this).find("input[name='camera_iso']").val();
                    var G = $(this).find("input[name='exposure_time']").val();
                    var x = $(this).find("input[name='exposure_bias']").val();
                    var v = $(this).find("input[name='focal_length']").val();
                    var u = $(this).find("input[name='picWidth']").val();
                    var A = $(this).find("input[name='picLength']").val();
                    var i = $(this).find("input[name='lon']").val();
                    var D = $(this).find("input[name='lat']").val();
                    var E = $(this).find(".service_down").attr("id");
                    var H = $(this).find(".lvtu_special_cover").html();
                    B = msgEscape(B);
                    if (H != null) {
                        H = true;
                    } else {
                        H = false;
                    }
                    if (B == "图片描述（可不填写）") {
                        B = "";
                    }
                    if (B != null && B.length > 140) {
                        d = true;
                    }
                    z = msgEscape(z);
                    F = msgEscape(F);
                    if (B == "undefined" || B == undefined || B == null) {
                        B = "";
                    }
                    if (w != null) {
                        j += "                   <batchPic>";
                        j += "                        <url>" + w + "</url>";
                        j += "                        <category>" + E + "</category>";
                        j += "                        <desc>" + B + "</desc>";
                        j += "                        <isCover>" + H + "</isCover>";
                        j += "                        <picWidth>" + u + "</picWidth>";
                        j += "                        <picLength>" + A + "</picLength>";
                        j += "                        <exif>";
                        j += "                       <Camera_Time>" + y + "</Camera_Time>";
                        j += "                        <Camera_Model>" + z + "</Camera_Model>";
                        j += "                        <Fnumber>" + C + "</Fnumber>";
                        j += "                        <Camera_ISO>" + F + "</Camera_ISO>";
                        j += "                       <Exposure_Time>" + G + "</Exposure_Time>";
                        j += "                       <Exposure_Bias>" + x + "</Exposure_Bias>";
                        j += "                        <Focal_Length>" + v + "</Focal_Length>";
                        j += "                        <GpsLongitude>" + i + "</GpsLongitude>";
                        j += "                        <GpsLatitude>" + D + "</GpsLatitude>";
                        j += "                        </exif>";
                        j += "                   </batchPic>";
                    }
                });
                j += "               </batchPicList>";
                j += "          </batch>";
            }
            j += "       </batchList>";
            j += "</xml>";
            if (d == true) {
                $.jBox.tip("图片描述长度不能超过140个字符", "warning");
                return false;
            }
            $.jBox.tip("正在提交图片信息...", "loading");
            if (k == "undefined") {
                k = null;
            }
            if (e == "undefined") {
                e = null;
            }
            var t = {
                param: j,
                userId: 255,
                before: k,
                after: e,
                type: f,
                random: a
            };
            if (f == 1) {
                $.ajax({
                    type: "POST",
                    url: pathUpload + "web/editAlbumDay.htm",
                    data: t,
                    dataType: "json",
                    success: function(i) {
                        if (i != null && i.albumId != null) {
                            $.jBox.tip("添加成功", "success");
                            if (i.id != null) {
                                window.location.href = pathUpload + "web/album.htm?albumId=" + i.albumId + "&" + o + "&im=" + i.id;
                            } else {
                                window.location.href = pathUpload + "web/album.htm?albumId=" + i.albumId + "&" + o;
                            }
                        } else {}
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: pathUpload + "web/editAlbum.htm",
                    data: t,
                    dataType: "json",
                    success: function(i) {
                        if (i != null && i.albumId != null) {
                            $.jBox.tip("添加成功", "success");
                            window.location.href = pathUpload + "web/album.htm?albumId=" + i.albumId;
                        } else {}
                    }
                });
            }
        }
    }
});