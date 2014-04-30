var popup=function(element){
	if (arguments.length > 1){
		for (var i = 0, elements = [], length = arguments.length; i < length; i++)
		elements.push($(arguments[i]));
		return elements;
	}
	if (typeof element == 'string'){
		if (document.getElementById){
			element = document.getElementById(element);
		}else if (document.all){
			element = document.all[element];
		}else if (document.layers){
			element = document.layers[element];
		}
	}
	return element;
};
Array.prototype.extend = function(C) {
  for (var B = 0, A = C.length; B < A; B++) {
    this.push(C[B]);
  }
  return this;
}
function divDrag() {
  var A, B, popupcn;
  var zIndex = 1000;
  this.dragStart = function(e) {
    e = e || window.event;
    if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
    var pos = this.popuppos;
    popupcn = this.parent || this;
    if (document.defaultView) {
      _top = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("top");
      _left = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("left");
    }
    else {
      if (popupcn.currentStyle) {
        _top = popupcn.currentStyle["top"];
        _left = popupcn.currentStyle["left"];
      }
    }
    pos.ox = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(_left);
    pos.oy = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(_top);
    if ( !! A) {
      if (document.removeEventListener) {
        document.removeEventListener("mousemove", A, false);
        document.removeEventListener("mouseup", B, false);
      }
      else {
        document.detachEvent("onmousemove", A);
        document.detachEvent("onmouseup", B);
      }
    }
    A = this.dragMove.create(this);
    B = this.dragEnd.create(this);
    if (document.addEventListener) {
      document.addEventListener("mousemove", A, false);
      document.addEventListener("mouseup", B, false);
    }
    else {
      document.attachEvent("onmousemove", A);
      document.attachEvent("onmouseup", B);
    }
    popupcn.style.zIndex = (++zIndex);
    this.stop(e);
  }
  this.dragMove = function(e) {
    e = e || window.event;
    var pos = this.popuppos;
    popupcn = this.parent || this;
    popupcn.style.top = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(pos.oy) + 'px';
    popupcn.style.left = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(pos.ox) + 'px';
    this.stop(e);
  }
  this.dragEnd = function(e) {
    var pos = this.popuppos;
    e = e || window.event;
    if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
    popupcn = this.parent || this;
    if ( !! (this.parent)) {
      this.style.backgroundColor = pos.color;
    }
    if (document.removeEventListener) {
      document.removeEventListener("mousemove", A, false);
      document.removeEventListener("mouseup", B, false);
    }
    else {
      document.detachEvent("onmousemove", A);
      document.detachEvent("onmouseup", B);
    }
    A = null;
    B = null;
    popupcn.style.zIndex = (++zIndex);
    this.stop(e);
  }
  this.shiftColor = function() {
    this.style.backgroundColor = "#EEEEEE";
  }
  this.position = function(e) {
    var t = e.offsetTop;
    var l = e.offsetLeft;
    while (e = e.offsetParent) {
      t += e.offsetTop;
      l += e.offsetLeft;
    }
    return {
      x: l,
      y: t,
      ox: 0,
      oy: 0,
      color: null
    }
  }
  this.stop = function(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    } else {
      e.cancelBubble = true;
    }
    if (e.preventDefault) {
      e.preventDefault();
    } else {
      e.returnValue = false;
    }
  }
  this.stop1 = function(e) {
    e = e || window.event;
    if (e.stopPropagation) {
      e.stopPropagation();
    } else {
      e.cancelBubble = true;
    }
  }
  this.create = function(bind) {
    var B = this;
    var A = bind;
    return function(e) {
      return B.apply(A, [e]);
    }
  }
  this.dragStart.create = this.create;
  this.dragMove.create = this.create;
  this.dragEnd.create = this.create;
  this.shiftColor.create = this.create;
  this.initialize = function() {
    for (var A = 0, B = arguments.length; A < B; A++) {
      C = arguments[A];
      if (! (C.push)) {
        C = [C];
      }
      popupC = (typeof(C[0]) == 'object') ? C[0] : (typeof(C[0]) == 'string' ? popup(C[0]) : null);
      if (!popupC) continue;
      popupC.popuppos = this.position(popupC);
      popupC.dragMove = this.dragMove;
      popupC.dragEnd = this.dragEnd;
      popupC.stop = this.stop;
      if ( !! C[1]) {
        popupC.parent = C[1];
        popupC.popuppos.color = popupC.style.backgroundColor;
      }
      if (popupC.addEventListener) {
        popupC.addEventListener("mousedown", this.dragStart.create(popupC), false);
        if ( !! C[1]) {
          popupC.addEventListener("mousedown", this.shiftColor.create(popupC), false);
        }
      }
      else {
        popupC.attachEvent("onmousedown", this.dragStart.create(popupC));
        if ( !! C[1]) {
          popupC.attachEvent("onmousedown", this.shiftColor.create(popupC));
        }
      }
    }
  }
  this.initialize.apply(this, arguments);
}

function jQuery_SendFormData(formid,success_func,start_func){
	var form = jQuery('#'+formid);
	if(!form.is('form')){return false;}
	var action = form.attr('action');
	if(action == null || action==''){return false;}
	var method = form.attr('method');
	if(method==null){
		method='GET';
	}
	method = method.toUpperCase();
	if(method!='POST'){
		method='GET';
	}
	var postdata='jQueryAjaxPost=true';
	form.find(':input').each(function(){
		var element = jQuery(this);
		var type = element.attr('type');
		var name = element.attr('name');
		var val = element.val();
		if(val==null){val='';}
		val = encodeURIComponent(val);
		if(type == 'text' || type == 'password' || type == 'hidden' || type == 'textarea' || type == 'select-one'){
			postdata += '&'+name+'='+val;
		}else if(type == 'radio' || type == 'checkbox'){
			var checked = element.attr('checked');
			if(checked){
				if(type == 'radio'){
					postdata += '&'+name+'='+val;
				}else{
					var thischeckbox = form.find(':checkbox[name="'+name+'"]');
					if(thischeckbox.length>1){
						var i = thischeckbox.data('index');
						if(i==null || isNaN(parseInt(i))){
							i=0;
						}else{
							i=i+1;
						}
						name = name.replace(/\[\]/g,'');
						postdata += '&'+name+'['+i+']='+val;
						thischeckbox.data('index',i);
					}else{
						postdata += '&'+name+'='+val;
					}
				}
			}
		}else if(type == 'select-multiple'){
			var x=0;
			var thisselect = form.find('select[name="'+name+'"]');
			name = name.replace(/\[\]/g,'');
			if(thisselect.length>1){
				var i = thisselect.data('index');
				if(i==null || isNaN(parseInt(i))){
					i=0;
				}else{
					i=i+1;
				}
				name = name +'['+i+']';
				thisselect.data('index',i);
			}
			element.find("option:selected").each(function(){
				postdata += '&'+name+'['+x+']='+encodeURIComponent(jQuery(this).val());
				x++;
			});
		}
	});
	//alert(postdata);
	start_func(formid);
	jQuery.ajax({
		global: false,
		type: method,
		url: action,
		data: postdata,
		cache: false,
		processData :false,
		dataType: 'html',
		success: function(data){
			success_func(data);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Ajax Error!Refresh Please!');
		}
	});
	return false;
}

function expertsAjaxSuccessFunc(data){
	if(getAjaxData(data,'closepop')!='1'){
		closePopup('popupMainPanel');
	}
	var error = getAjaxData(data,'Error');
	if(error==''){
		var action = getAjaxData(data,'ACTION');
		var customer_id = getAjaxData(data,'customer_id');
		var tmod = getAjaxData(data,'TMOD');
		if(action=='remarks'){
			var html = getAjaxData(data,'name')+'&nbsp;'+getAjaxData(data,'sex')+'&nbsp;'+getAjaxData(data,'remarks');
			jQuery('#remarks_'+customer_id).html(html);
			jQuery('#remarks_'+customer_id).data('limit_body',html);
			expert_limittext('#remarks_'+customer_id);
		}else if(action=='add_writings_type'){
			if(tmod=='addWritings'){
				var option = jQuery("<option value='"+getAjaxData(data,'tid')+"' group_id='"+getAjaxData(data,'group_id')+"'>-&nbsp;"+getAjaxData(data,'name')+"</option>");
				var optgroup = jQuery('#writings_type_'+customer_id).find("optgroup[group_id='"+getAjaxData(data,'group_id')+"']");
				if(!optgroup.is('optgroup')){
					optgroup = jQuery("<optgroup label='"+getAjaxData(data,'group_name')+"' group_id='"+getAjaxData(data,'group_id')+"'></optgroup>");
					jQuery('#writings_type_'+customer_id).append(optgroup);
				}
				optgroup.after(option);
				jQuery('#writings_type_'+customer_id).val(getAjaxData(data,'tid'));
			}else{
				jQuery('#writings_type_'+customer_id+'_'+getAjaxData(data,'group_id')).append(jQuery('<a href="'+getAjaxData(data,'url')+'" id="writings_type_item_'+getAjaxData(data,'tid')+'">'+getAjaxData(data,'name')+'</a>'));
			}
		}else if(action=='edit_writings_type'){
			var option = jQuery('#writings_type_'+customer_id).find("option:selected");
			var text = getAjaxData(data,'name');
			var group_id = getAjaxData(data,'group_id');
			option.html('-&nbsp;'+text);
			if(group_id != option.attr('group_id')){
				var optgroup = jQuery('#writings_type_'+customer_id).find("optgroup[group_id='"+group_id+"']");
				if(!optgroup.is('optgroup')){
					optgroup = jQuery("<optgroup label='"+getAjaxData(data,'group_name')+"' group_id='"+group_id+"'></optgroup>");
					jQuery('#writings_type_'+customer_id).append(optgroup);
				}
				optgroup.after(option);
			}
		}else if(action=='del_writings_type'){
			var option = jQuery('#writings_type_'+customer_id).find("option:selected");
			option.remove();
		}else if(action=='photo'){
			var photosrc = getAjaxData(data,'photosrc');
			jQuery('#photo_'+customer_id).attr('src',photosrc);
		}else if(action=='delwritings'){
			var aid = getAjaxData(data,'aid');
			var tid = getAjaxData(data,'tid');
			if(tmod=='info'){
				var href = jQuery('#writingstype_'+tid).attr('href');
				window.location.href = href;
			}else{
				jQuery('#wn_'+tid+'_'+aid).remove();
				var num = jQuery('#writingsnum_'+customer_id).html();
				num = parseInt(num);
				if(isNaN(num)){
					num=0;
				}else{
					num = num-1;
					num = num<0?0:num;
				}
				jQuery('#writingsnum_'+customer_id).html(num);
			}
		}else if(action == 'writings_type'){
			var job = getAjaxData(data,'job');
			var gid = getAjaxData(data,'gid');
			if(job=='move'){
				var tids = getAjaxData(data,'tids');
				tids = tids.split('/');
				for(var i=0;i<tids.length;i++){
					jQuery('#writings_type_item_'+tids[i]).appendTo('#writings_type_'+customer_id+'_'+gid);
				}
			}else if(job=='update'){
				var tids = getAjaxData(data,'tids');
				tids = tids.split('/');
				for(var i=0;i<tids.length;i++){
					jQuery('#writings_type_item_'+tids[i]).html(getAjaxData(data,'tid_'+tids[i]));
				}
			}else if(job=='del'){
				jQuery('#writings_type_item_'+getAjaxData(data,'tid')).remove();
				jQuery('#del_wgt_'+getAjaxData(data,'tid')).remove();
			}
		}else if(action == 'delanswers'){
			var qid = getAjaxData(data,'qid');
			var aid = getAjaxData(data,'aid');
			jQuery('#AnswersL_'+aid).remove();
			if(getAjaxData(data,'iscount')=='1'){
				var num = jQuery('#answersnum_'+customer_id).html();
				num = parseInt(num);
				if(isNaN(num)){
					num=0;
				}else{
					num = num-1;
					num = num<0?0:num;
				}
				jQuery('#answersnum_'+customer_id).html(num);
				
				var nonum = jQuery('#noanswersnum_'+customer_id).html();
				nonum = parseInt(nonum);
				if(isNaN(nonum)){
					nonum=0;
				}else{
					nonum = nonum+1;
					nonum= nonum<0?0:nonum;
				}
				jQuery('#noanswersnum_'+customer_id).html(nonum);
			}
		}else if(action == 'delquestion'){
			var qid = getAjaxData(data,'qid');
			jQuery('#QuestionL_'+qid).remove();
			jQuery('.QuestionLChild_'+qid).remove();
			var answered = getAjaxData(data,'answered');
			if(answered==1){
				var num = jQuery('#answersnum_'+customer_id).html();
				num = parseInt(num);
				if(isNaN(num)){
					num=0;
				}else{
					num = num-1;
					num = num<0?0:num;
				}
				jQuery('#answersnum_'+customer_id).html(num);
			}else{
				var nonum = jQuery('#noanswersnum_'+customer_id).html();
				nonum = parseInt(nonum);
				if(isNaN(nonum)){
					nonum=0;
				}else{
					nonum = nonum-1;
					nonum= nonum<0?0:nonum;
				}
				jQuery('#noanswersnum_'+customer_id).html(nonum);
			}
		}else if(action=='add_answer'){
			var q_id = getAjaxData(data,'q_id');
			var a_id = getAjaxData(data,'a_id');
			var time = getAjaxData(data,'time');
			var a_content = getAjaxData(data,'a_content');
			var answer_tile = getAjaxData(data,'answer_tile');
			var Useful = getAjaxData(data,'Useful');
			var Useless = getAjaxData(data,'Useless');
			var expert_name = getAjaxData(data,'expert_name');
			var expert_name_url = getAjaxData(data,'expert_name_url');
			var delanswers_text = getAjaxData(data,'delanswers_text');
			var delanswers_href = getAjaxData(data,'delanswers_href');
			var add_html = '<dl class="reply"><dt>'+answer_tile+'</dt><dd>'+a_content+'</dd><dd><span>'+time+'</span><span><a href="'+expert_name_url+'">'+expert_name+'</a></span><span class="tip" id="faq_a_'+a_id+'"><font>'+getAjaxData(data,'tipname')+'</font></span><span><a href="javascript:void(0);" onclick="SetUseful('+a_id+')">'+Useful+'(<font id="useful_'+a_id+'">0</font>)</a></span><span><a href="javascript:void(0);" onclick="SetUseless('+a_id+')">'+Useless+'(<font id="useless_'+a_id+'">0</font>)</a></span><span class="r"><a href="'+delanswers_href+'" rel="expert_ajax" onclick="return false;" >'+delanswers_text+'</a></span></dd></dl>';
			jQuery('#QuestionL_'+q_id).after(add_html);
			
			if(getAjaxData(data,'iscount')=='1'){
				var num = jQuery('#answersnum_'+customer_id).html();
				num = parseInt(num);
				if(isNaN(num)){
					num=0;
				}else{
					num = num+1;
					num = num<0?0:num;
				}
				jQuery('#answersnum_'+customer_id).html(num);
				
				var nonum = jQuery('#noanswersnum_'+customer_id).html();
				nonum = parseInt(nonum);
				if(isNaN(nonum)){
					nonum=0;
				}else{
					nonum = nonum-1;
					nonum= nonum<0?0:nonum;
				}
				jQuery('#noanswersnum_'+customer_id).html(nonum);
			}
		}
	}else{
		alert(error);
	}
}
function expertsAjaxStartFunc(formid){
	var loadobject = "<img src='ajaxtabs/loading.gif' align='absmiddle'>";
	jQuery('#popupAjaxTitle').html("Submitting... ...");
	jQuery('#popupAjaxLabel').html('');
	jQuery('#popupAjaxContent').html(loadobject);
}
function getAjaxData(data,regxp){
	if(regxp!=null && regxp!=''){
		eval("var regxpObj = /^[\\s\\S]*\\["+regxp+"\\]([\\s\\S]*?)\\[\\/"+regxp+"\\][\\s\\S]*$/ig");
		if(data.search(regxpObj)!=-1){
			return data.replace(regxpObj,"$1");
		}else{
			return '';	
		}
	}else{
		return false;	
	}
}
var isShowPopup = true;

jQuery('a[rel="expert_ajax"]').live('click',function(){
	showPopup('popupMainPanel','popupConPanel','',200,80);
	var loadobject = "<img src='ajaxtabs/loading.gif' align='absmiddle'>";
	jQuery('#popupAjaxTitle').html("Loading... ...");
	jQuery('#popupAjaxLabel').html('');
	jQuery('#popupAjaxContent').html(loadobject);
	var href = jQuery(this).attr('href');
	href = href.split('?');
	if(href.length<2){href[1]="";}
	href = href[0]+'?'+href[1]+'&expertsAjax=true';
	isShowPopup = true;
	jQuery.ajax({
		global: false,
		url: href,
		cache: false,
		dataType: 'html',
		success: function(data){
			var title = getAjaxData(data,'Title');
			var Label = getAjaxData(data,'Label');
			var content = getAjaxData(data,'Content');
			jQuery('#popupAjaxTitle').html(title);
			jQuery('#popupAjaxLabel').html(Label);
			jQuery('#popupAjaxContent').html(content);
			if(isShowPopup){
				showPopup('popupMainPanel','popupConPanel');
			}
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Ajax Error!Refresh Please!');
			closePopup('popupMainPanel');
		}
	});
	return false;
});
function expert_closePopup(){
	closePopup('popupMainPanel');	
}