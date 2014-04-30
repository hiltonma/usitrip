var popIndex = 100;
var winW = $(window).width();
var winH = $(window).height();
(function($){
$.fn.dialog = function(options){
    var objW = options.width ? options.width : 500;
    var objH = options.height ? options.height : 300;
    var mouseEndX = 0;
    var mouseEndY = 0;

    var defaults = {
        pTitle : "",
		pMain: "",
		ajaxURL: "",
        width : 500,
        height : 300,
        posx : (winW - objW - 24)/2,
        posy : (winH - objH - 40)/2,
		onDragBegin: null,
		onDragEnd: null,
		onResizeBegin: null,
		onResizeEnd: null,
		onAjaxContentLoaded: null,
        pFoot: true,
		pMin: true,
		pMinText: "pull",
		pMax: false,
		pMaxText: "max&min",
		pClose: true,
		pCloseText: "close",
		draggable: true,
		resizeable: true,
		resizeIcon: "resize",
		windowType: "standard"
    };

    var options = $.extend(defaults, options);

    $popWrap = $('<div class="ui_popWrap"></div>');
	if($.browser.version == '6.0'){
		$('<iframe frameborder="0" border="0" style="width:' + options.width + ';height:' + options.height + ';position:absolute;left:0;top:0;filter:Alpha(opacity=0);"></iframe>').insertBefore('.ui_popHead');
	}
    $popTitle = $('<div class="ui_popHead"></div>');
    $popMin = $('<div class="ui_popMin"></div>');
	$popMax = $('<div class="ui_popMax"></div>');
	$popClose = $('<div class="ui_popClose"></div>');
	$popMain = $('<div class="ui_popMain"></div>');
	$popFoot = $('<div class="ui_popFoot"></div>');
	$popResize = $('<div class="ui_popResize"></div>');

	if(options.windowType=="video" || options.windowType=="iframe")
	  $popMain.css("overflow","hidden");

	var setFocus = function($obj){
	    $obj.css("z-index",popIndex++);
	}

	var resize = function($obj, width, height){

		width = parseInt(width);
		height = parseInt(height);

		$obj.attr("lastWidth",width)
		    .attr("lastHeight",height);

		width = width+"px";
		height = height+"px";

		$obj.css("width", width)
	        .css("height", height);

		if(options.windowType=="video"){
		  $obj.children(".ui_popMain").children("embed").css("width", width)
	               .css("height", height);
		  $obj.children(".ui_popMain").children("object").css("width", width)
	               .css("height", height);
		  $obj.children(".ui_popMain").children().children("embed").css("width", width)
	               .css("height", height);
		  $obj.children(".ui_popMain").children().children("object").css("width", width)
	               .css("height", height);
		}

        if(options.windowType=="iframe")
	      $obj.children(".ui_popMain").children("iframe").css("width", width)
	               .css("height", height);

	}

	var move = function($obj, x, y){

		x = parseInt(x);
		y = parseInt(y);

		$obj.attr("lastX",x)
		    .attr("lastY",y);

        x = x+"px";
		y = y+"px";

		$obj.css("left", x)
	        .css("top", y);
	}

	var dragging = function(e, $obj){
	    if(options.draggable){
		e = e ? e : window.event;
	    var newx = parseInt($obj.css("left")) + (e.clientX - mouseEndX);
        var newy = parseInt($obj.css("top")) + (e.clientY - mouseEndY);
	    mouseEndX = e.clientX;
	    mouseEndY = e.clientY;

	    move($obj,newx,newy);
		}
	};

	var resizing = function(e, $obj){

	  e = e ? e : window.event;
	  var w = parseInt($obj.css("width"));
	  var h = parseInt($obj.css("height"));
	  w = w<100 ? 100 : w;
	  h = h<50 ? 50 : h;
	  var neww = w + (e.clientX - mouseEndX);
      var newh = h + (e.clientY - mouseEndY);
	  mouseEndX = e.clientX;
	  mouseEndY = e.clientY;

	  resize($obj, neww, newh);
	};

	$popTitle.bind('mousedown', function(e){
	    $obj = $(e.target).parent();
		setFocus($obj);

	    if($obj.attr("state") == "normal"){
	        e = e ? e : window.event;
		    mouseEndX = e.clientX;
		    mouseEndY = e.clientY;

		    $(document).bind('mousemove', function(e){
			    dragging(e, $obj);
		    });


		    $(document).bind('mouseup', function(e){
				if(options.onDragEnd != null)options.onDragEnd();
				$(document).unbind('mousemove');
				$(document).unbind('mouseup');
		    });

			if(options.onDragBegin != null)options.onDragBegin();
	    }
    });

	$popResize.bind('mousedown', function(e){
		$obj = $(e.target).parent().parent();
		setFocus($obj);

		if($obj.attr("state") == "normal"){
			e = e ? e : window.event;
			mouseEndX = e.clientX;
			mouseEndY = e.clientY;

			$(document).bind('mousemove', function(e){
				resizing(e, $obj);
			});

			$(document).bind('mouseup', function(e){
				if(options.onResizeEnd != null)options.onResizeEnd();
				$(document).unbind('mousemove');
				$(document).unbind('mouseup');
			});

			if(options.onResizeBegin != null)options.onResizeBegin();
		}

    });

	$popMin.bind('click', function(e){
	    $obj = $(e.target).parent().parent();
		setFocus($obj);
		if($obj.attr("state") == "normal"){
	        $(e.target).parent().next().slideToggle(300);
		}
    });

	$popMax.bind('click', function(e){
	  $obj = $(e.target).parent().parent();
	  setFocus($obj);
	  if($obj.attr("state") == "normal"){
		  if(options.windowType=="standard"){
		    $obj.animate({
		      top: "0",
			  left: "0",
			  width: $(window).width()-12,
			  height: $(window).height()-40
		    },300);
		  }
		  else{
			tmpx = $obj.attr("lastX");
		    tmpy = $obj.attr("lastY");
			tmpwidth = $obj.attr("lastWidth");
		    tmpheight = $obj.attr("lastHeight");
			move($obj, 0, 0);
		    resize($obj,$(window).width()-12,$(window).height()-64);
			$obj.attr("lastX",tmpx);
		    $obj.attr("lastY",tmpy);
			$obj.attr("lastWidth",tmpwidth);
		    $obj.attr("lastHeight",tmpheight);
		  }
		  $obj.attr("state","maximized")
	  }
	  else if($obj.attr("state") == "maximized"){
	    if(options.windowType=="standard"){
		  $obj.animate({
		    top: $obj.attr("lastY"),
			left: $obj.attr("lastX"),
			width: $obj.attr("lastWidth"),
			height: $obj.attr("lastHeight")
		  },300);
		}
		else{
		  resize($obj,$obj.attr("lastWidth"),$obj.attr("lastHeight"));
		  move($obj,$obj.attr("lastX"),$obj.attr("lastY"));
		}
		$obj.attr("state","normal")
	  }

    });

	$popClose.bind('click', function(e){
	  $(e.target).parent().parent().fadeOut();
	  $(e.target).parent().parent().children(".ui_popMain").html("");
    });

	$popMain.click(function(e){
      setFocus($(e.target).parent());
    });
	$popFoot.click(function(e){
      setFocus($(e.target).parent());
    });

	move($popWrap,options.posx,options.posy);
	resize($popWrap,options.width,options.height);
	$popWrap.attr("state","normal");
	$popTitle.append(options.pTitle);

	if(options.pMin)
	    $popTitle.append($popMin)
	if(options.pMax)
	    $popTitle.append($popMax)
	if(options.pClose)
	    $popTitle.append($popClose);

	if(options.resizeable)
	    $popFoot.append($popResize);

	$popWrap.append($popTitle)
	$popWrap.append($popMain)

	if(options.pFoot){
	    $popWrap.append($popFoot);
	}else{
		$popMain.append($popResize);
		$popMain.css({"borderBottom":"6px solid #82A1C0","borderRadius":"0 0 5px 5px"})
	}

	$popWrap.css("display","none");

	return this.each(function(index) {
		var $this = $(this);

		$popMin.html(options.pMinText);
		$popMax.html(options.pMaxText);
		$popClose.html(options.pCloseText);
		$popResize.html(options.resizeIcon);

		$this.data("window",$popWrap);
		$('body').append($popWrap);

		$this.click(function(event){
			event.preventDefault();
			$window = $this.data("window")
			if(options.ajaxURL != ""){

				 $.ajax({
				   type: "GET",
				   url: options.ajaxURL,
				   dataType: "html",
				   //data: "header=variable",
				   success: function(data){
					 $window.children(".ui_popMain").html(data);
					 if(options.onAjaxContentLoaded != null) options.onAjaxContentLoaded();
				   }
				 });

			}
			else $window.children(".ui_popMain").html(options.pMain);
			if(!options.draggable)
			    $window.children(".ui_popHead").css("cursor","default");
			setFocus($window);
            $window.fadeIn();
		});
	});


}
})(jQuery);
