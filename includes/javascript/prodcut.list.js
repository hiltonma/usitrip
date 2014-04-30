var cookieSet = {path:'/'};
function jQuery_ProdcutDB(id,name,no,url){
	var obj = jQuery('#pdb_chk_'+id);
	var checked = obj.attr('ischecked');
	if(checked!='true'){
		_ProdcutDB_Num++;
		if(_ProdcutDB_Num>maxChecked){
			_ProdcutDB_Num--;
			showPopup('popupTip','popupConCompare','off','','','fixedTop','pdb_chk_'+id);
			obj.attr('checked',false);
		}else{
			addProdcutItem(id,name,no,url);
		}
	}else{
		delProdcutItem(id);
	}
}
function addProdcutItem(id,name,no,url){
	var obj = jQuery('#pdb_chk_'+id);
	var prodcutPanel = jQuery('#proListCon_'+id);
	prodcutPanel.addClass('proListConSelected');
	jQuery.cookie('prodcutDB['+id+'][name]',name,cookieSet);
	jQuery.cookie('prodcutDB['+id+'][no]',no,cookieSet);
	obj.attr('ischecked','true');
	jQuery('#compareBar').find('ul').append('<li onmouseout="this.className=\'\'" onmouseover="this.className=\'over\'" pdbid=\''+id+'\'><p><a href="'+url+'" title="'+name+'" target="product_win'+id+'">'+no+'</a></p><span><a href="javascript:void(0);" onclick="delProdcutItem(\''+id+'\');"><img src="image/icons/icon_del_red.gif"/></a></span></li>');
	jQuery('#popupTip').find('ul').append('<li onmouseout="this.className=\'\'" onmouseover="this.className=\'over\'" pdbid=\''+id+'\'><p>'+name+'</p><span><a href="javascript:void(0);" onclick="delProdcutItem(\''+id+'\');"><img src="image/icons/icon_del_red.gif"/></a></span></li>');
	is_ShowcompareBar();
}
function delProdcutItem(id){
	_ProdcutDB_Num--;
	var obj = jQuery('#pdb_chk_'+id);
	var prodcutPanel = jQuery('#proListCon_'+id);
	prodcutPanel.removeClass('proListConSelected');
	jQuery.cookie('prodcutDB['+id+'][name]','',cookieSet);
	jQuery.cookie('prodcutDB['+id+'][no]','',cookieSet);
	obj.attr('ischecked','false');
	obj.attr('checked',false);
	jQuery('#compareBar').find('ul').find("li[pdbid='"+id+"']").remove();
	jQuery('#popupTip').find('ul').find("li[pdbid='"+id+"']").remove();
	is_ShowcompareBar();
}
is_ShowcompareBar();
function is_ShowcompareBar(){
	
	var compareBar_li_len = jQuery('#compareBar').find('ul').find("li").length;
	if(compareBar_li_len>0){
		//visibility
		jQuery('#compareBar').css("visibility","visible");
		jQuery('#compareBar').fadeIn("slow");
		if(compareBar_li_len>1){
			jQuery('#compareBar').find('.con').find('a.btnCompareSmall').show();
			jQuery(".proListCon").not(".proListConSelected").find('li.compare').find('a.btnCompareSmall').fadeOut("slow");
			jQuery(".proListConSelected").find('li.compare').find('a.btnCompareSmall').fadeIn("slow");
		}else{
			jQuery('#compareBar').find('.con').find('a.btnCompareSmall').hide();
			jQuery(".proListCon").find('li.compare').find('a.btnCompareSmall').fadeOut("slow");
		}
	}else{
		jQuery('#compareBar').css("visibility","hidden");
		jQuery('#compareBar').fadeOut("slow");
	}
}


	var FixedBox=function(el){
		this.element=el;
		this.BoxY=getXY(this.element).y;
	}

    
	FixedBox.prototype={
	    setCss:function(){
	        var windowST=(document.compatMode && document.compatMode!="CSS1Compat")? document.body.scrollTop:document.documentElement.scrollTop||window.pageYOffset;
            if(windowST>this.BoxY){
              var scrollTop = document.documentElement.scrollTop + document.body.scrollTop;
              var offsetTop = document.getElementById("proListTop").offsetTop;
              this.element.style.top = scrollTop - offsetTop + 20 +"px";
			}else{
			  this.element.style.top = "80px";
			}
		}
	};
	
	function addEvent(elm, evType, fn, useCapture) {
		if (elm.addEventListener) {
			elm.addEventListener(evType, fn, useCapture);
		return true;
		}else if (elm.attachEvent) {
			var r = elm.attachEvent('on' + evType, fn);
			return r;
		}
		else {
			elm['on' + evType] = fn;
		}
	}

	function getXY(el) {
        return document.documentElement.getBoundingClientRect && (function() {
            var pos = el.getBoundingClientRect();
            return { x: pos.left + document.documentElement.scrollLeft, y: pos.top + document.documentElement.scrollTop };
        })() || (function() {
            var _x = 0, _y = 0;
            do {
                _x += el.offsetLeft;
                _y += el.offsetTop;
            } while (el = el.offsetParent);
            return { x: _x, y: _y };
        })();
    }

	var divA=new FixedBox(document.getElementById("compareBar"));
   	addEvent(window,"scroll",function(){
	  divA.setCss();
	});
	window.onresize = function(){
      divA.setCss();
    }
	
	
