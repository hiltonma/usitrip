jQuery.fn.extend({
  limittext:function(opt){
	  String.prototype.replaceAll  = function(s1,s2){
		  return this.replace(new RegExp(s1,"gm"),s2);
	  }
	  String.prototype.len=function(){
		  return this.replace(/[^\x00-\xff]/g, "**").length
	  }
	  function lessBody(param,end,maxlen){				
		param = param.replaceAll("</?(IMG|img)[^<>]*/?>","");
		var result = "";
		var n = 0;
		var temp;
		var isCode = false;
		var isHTML = false;
		for (var i = 0; i < param.length; i++) {
			  temp = param.charAt(i);
			  if (temp == '<') {
				isCode = true;
			  }else if (temp == '&') {
				isHTML = true;
			  }else if (temp == '>' && isCode) {
				n = n - 1;
				isCode = false;
			  }else if (temp == ';' && isHTML) {
				isHTML = false;
				n = n + 1;
			  }
			  if (!isCode && !isHTML) {
				n = n + 1;
				if ( (temp + "").len() > 1) {
				  n = n + 1;
				}
			  }
			  result+=temp;
			  if (n >= maxlen) {
				break;
			  }
		}	   
		result+=end;
		
		var temp_result = result.replaceAll("(>)[^<>]*(<?)", "$1$2");
		if(param.length-temp_result.length<maxlen){
			//return param;
		}
		
		temp_result = temp_result.replaceAll("</?(AREA|BASE|BASEFONT|BODY|BR|COL|COLGROUP|DD|DT|FRAME|HEAD|HR|HTML|IMG|INPUT|ISINDEX|LI|LINK|META|OPTION|P|PARAM|TBODY|TD|TFOOT|TH|THEAD|TR|area|base|basefont|body|br|col|colgroup|dd|dt|frame|head|hr|html|img|input|isindex|li|link|meta|option|p|param|tbody|td|tfoot|th|thead|tr)[^<>]*/?>",
											 "");

		temp_result=temp_result.replaceAll("<([a-zA-Z]+)[^<>]*>(.*?)</\\1>","$2");	  	 

		var reg = new RegExp("<([a-zA-Z]+)[^<>]*>","gm");
		var arr;
		if (reg.test(temp_result)) {
		  arr = (temp_result.match(reg));
		}
		if(arr!=null&&arr.length>0){
			for(var j=0;j<arr.length;j++){
				if(arr[j].indexOf(" ")>0)
					result = result+"</"+arr[j].substring(1,arr[j].indexOf(" "))+">";
				else
					result = result+"</"+arr[j].substring(1,arr[j].length-1)+">";
			}
		}
		return result
	  }
	  jQuery(this).each(function(){
			opt=jQuery.extend({
				"limit":30,
				"fill":"..."
			},opt);
			opt.morefn=jQuery.extend({
					"status": false,
					"moretext": "(more)",
					"lesstext":"(less)",
					"cssclass": "limittextclass",
					"lessfn": function(){
					},
					"fullfn": function(){
					}
			},opt.morefn);
			var jQuerythis=jQuery(this);
			var limit_body=jQuerythis.data('limit_body');
			if(limit_body==null){
				limit_body=jQuerythis.html();
				jQuerythis.data('limit_body',limit_body);
			}
			var getbuttom=function(showtext){
				return "&nbsp;<a href='javascript:;' class='"
				+opt.morefn.cssclass+"'>"
				+showtext
				+"<a>";
			}
			jQuerythis.bind('limit',function(event,obj,limit,limit_body){
				var showbody=limit_body;
				if(limit_body.length<=limit||limit=='all'){
					if(limit=='all'){
						showbody=limit_body+(opt.morefn.status?getbuttom(opt.morefn.lesstext):"");
					}else{
						showbody=limit_body;
					}
				}else{
					showbody=lessBody(limit_body,opt.fill,limit);
					//alert('show:\r\n\r\n'+showbody);
					if(!opt.morefn.status){
						showbody=showbody;
					}else{
						if(showbody.length<limit_body.length){
							showbody=showbody+getbuttom(opt.morefn.moretext);
						}
					}
				}
				obj.html(showbody);
			});
			jQuerythis.trigger('limit',[jQuerythis,opt.limit,limit_body]);
			jQuerythis.find("."+opt.morefn.cssclass).live('click',function(){
				if(jQuery(this).html()==opt.morefn.moretext){
					showbody=limit_body+getbuttom(opt.morefn.lesstext);
					jQuerythis.html(showbody);
					opt.morefn.fullfn();
				}else{
					jQuerythis.trigger('limit',[jQuerythis,opt.limit,limit_body]);
					opt.morefn.lessfn();
				}
				return this;
			});
	});
  }
});