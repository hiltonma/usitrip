/**
 * Scroll (Version 1.0)
 * @author Rocky (296456018@qq.com)
 *
 * Create a Scroll
 * @example 
    $("#Scroll").Scroll({
        scroll           : "#Scroll",
        nextBtn          : "#NextBtn",
        preBtn           : "#PreBtn",
        scrollCon        : "#Scroll>ul",
        scrollConLi      : "#Scroll>ul>li"
    });
 * on Jquery
 * date : 2011-3-25
 */


(function($){
	$.fn.Scroll = function(options){
	    var deflunt={
		    allWidth       : 0,
            scrollIndex    : 0,
            sbArr          : new Array(),
            scroll         : "",
            nextBtn        : "",
            preBtn         : "",
            scrollCon      : "",
            scrollConLi    : ""
	    };
	    
	    var opt = $.extend({},deflunt,options);

	    var _changeBtn = function(){
            if(opt.scrollIndex == 0){
                $(opt.preBtn).removeClass("preBtnActive");
            }else{
                $(opt.preBtn).addClass("preBtnActive");
            }

            if(opt.scrollIndex == opt.sbArr.length-1){
                $(opt.nextBtn).removeClass("nextBtnActive");
            }else{
                $(opt.nextBtn).addClass("nextBtnActive");
            }
        };
        
        var _goNext = function(){
            if(opt.scrollIndex < opt.sbArr.length-1){
                ++opt.scrollIndex;
                _moveBox(opt.sbArr[opt.scrollIndex].scrollFlag);
                _changeBtn();
            }
        };

        var _goPrevious = function(){
            if(opt.scrollIndex>0){
                --opt.scrollIndex;
                _moveBox(opt.sbArr[opt.scrollIndex].scrollFlag);
                _changeBtn();
            }
        };
     
        var _moveBox = function(moveWidth){
	            $(opt.scrollCon).stop().animate({ 
	                left:-moveWidth
	            }, 300 );
        };

		return this.each(function() {

		    $(opt.scrollConLi).each(function(){
                opt.sbArr.push(this);
                opt.sbArr[opt.sbArr.length-1].scrollFlag = opt.allWidth;
                opt.allWidth = opt.allWidth+opt.sbArr[opt.sbArr.length-1].offsetWidth;
            });
            
            $(opt.nextBtn).click(function(){
                _goNext();
            }),
            
            $(opt.preBtn).click(function(){
                _goPrevious();
            }),
            
            $(opt.scrollCon).width(opt.allWidth);
            _changeBtn();
		});
	};
	
	
})(jQuery);


