/* Start - Javascript string pad */
/* 注意在引用时用gb2312的格式引用*/
var STR_PAD_LEFT = 1;
var STR_PAD_RIGHT = 2;
var STR_PAD_BOTH = 3;
function str_pad(str, len, pad, dir) {
	if (typeof(len) == "undefined") { var len = 0; }
	if (typeof(pad) == "undefined") { var pad = ' '; }
	if (typeof(dir) == "undefined") { var dir = STR_PAD_RIGHT; }
	str=str.toString();

	if (len + 1 >= str.length) {
		switch (dir){
			case STR_PAD_LEFT:
				str = Array(len + 1 - str.length).join(pad) + str;
			break;
			case STR_PAD_BOTH:
				var right = Math.ceil((padlen = len - str.length) / 2);
				var left = padlen - right;
				str = Array(left+1).join(pad) + str + Array(right+1).join(pad);
			break;
			default:
				str = str + Array(len + 1 - str.length).join(pad);
			break;
		} // switch
	}
	return str;
}
/* End - Javascript string pad */

/*普通日历start*/
function G_calendar(){}
G_calendar.prototype={
    Unlimited: false,	//日期取消限制，如果为true则所有日期均可使用
	HelpMsg:"1.点击年可选择年份;<br>2.点击月可选择月份;<br>3.点击&gt;或&lt;可改变月份;<br>4.日历底部文字是对日期的说明",//在这里添加和修改帮助文档的文字

    DayInfo:new Array(),
    Moveable:false,
    NewName:"",
    insertId:"",
    ClickObject:null,
    InputObject:null,
    InputDate:null,
    IsOpen:false,
    MouseX:0,
    MouseY:0,
	OutPutType : 'y-m-d', //设置显示方式
    GetDateLayer:function(){
        return window.G_DateLayer;
    },
    L_TheYear:new Date().getFullYear(), //定义年的变量的初始值
    L_TheMonth:new Date().getMonth()+1,//定义月的变量的初始值
    L_WDay:new Array(39),//定义写日期的数组
    MonHead:new Array(31,28,31,30,31,30,31,31,30,31,30,31),//定义阳历中每个月的最大天数
    GetY:function(){
        var obj;
        if (arguments.length > 0){
            obj==arguments[0];
        }
        else{
            obj=this.ClickObject;
        }
        if(obj!=null){
            var y = obj.offsetTop;
            while (obj = obj.offsetParent) y += obj.offsetTop;
            return y;
        }
        else{return 0;}
    },
    GetX:function(){
        var obj;
        if (arguments.length > 0){
            obj==arguments[0];
        }
        else{
            obj=this.ClickObject;
        }
        if(obj!=null){
            var y = obj.offsetLeft;
            while (obj = obj.offsetParent) y += obj.offsetLeft;
            return y;
        }
        else{return 0;}
    },
    CreateHTML:function(){
        var htmlstr="";
        htmlstr+="<div id=\"G_calendar\">\r\n";
        htmlstr+="<span id=\"SelectYearLayer\" style=\"z-index: 9999;position: absolute;top: 3; left: 45;display: none\"></span>\r\n";
        htmlstr+="<span id=\"SelectMonthLayer\" style=\"z-index: 9999;position: absolute;top: 3; left: 105;display: none\"></span>\r\n";
        htmlstr+="<div id=\"G_calendar-year-month\">\r\n";
        htmlstr+="<div id=\"G_calendar-PrevM\" onclick=\"parent."+this.NewName+".PrevM()\" title=\"前一月\"><b>&lt;</b><span id=\"G_calendar-PrevM-text\"></span></div>\r\n";
        htmlstr+="<div id=\"G_calendar-year\" onmouseover=\"style.backgroundColor='#ffeadd'\" onmouseout=\"style.backgroundColor='white'\" onclick=\"parent."+this.NewName+".SelectYearInnerHTML()\"></div>\r\n";
        htmlstr+="<div id=\"G_calendar-month\"  onmouseover=\"style.backgroundColor='#ffeadd'\" onmouseout=\"style.backgroundColor='white'\" onclick=\"parent."+this.NewName+".SelectMonthInnerHTML()\"></div>\r\n";
        htmlstr+="<div id=\"G_calendar-NextM\" onclick=\"parent."+this.NewName+".NextM()\" title=\"后一月\"><span id=\"G_calendar-NextM-text\"></span><b>&gt;</b></div>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="<div id=\"G_calendar-week\"><ul  onmouseup=\"StopMove()\"><li><b>日</b></li><li>一</li><li>二</li><li>三</li><li>四</li><li>五</li><li><b>六</b></li></ul></div>\r\n";
        htmlstr+="<div id=\"G_calendar-day\">\r\n";
        htmlstr+="<ul>\r\n";
        for(var i=0;i<this.L_WDay.length;i++){
            htmlstr+="<li id=\"G_calendar-day_"+i+"\" style=\"background:#fff;border:1px solid #6bc4f3\" ></li>";
        }
        htmlstr+="</ul>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="<div>\r\n";
        htmlstr+="<div id=\"G_calendar-help\" onclick='sAlert(\""+this.HelpMsg+"\")'><span>帮助</span></div>\r\n";
        htmlstr+="<div id=\"G_calendar-show-week\"><span>"+this.GetDOWToday()+"</span></div>\r\n";
        htmlstr+="<div id=\"G_calendar-show-info\"><span></span><b id=\"G_calendar-show-price\"></b>";
        htmlstr+="<span id='btnClose' style='' onclick='parent."+this.NewName+".clearInput()'>清除</span></div>\r\n";
        
        htmlstr+="<div id=\"G_calendar-close\" onclick='parent."+this.NewName+".OnClose()'><span>关闭</span></div>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="<scr" + "ipt type=\"text/javas" + "cript\">\r\n";
        htmlstr+="var MouseX,MouseY;";
        htmlstr+="var Moveable="+this.Moveable+";\r\n";
        htmlstr+="var MoveaStart=false;\r\n";
        htmlstr+="document.onmousemove=function(e)\r\n";
        htmlstr+="{\r\n";
        htmlstr+="var DateLayer=parent.document.getElementById(\"G_DateLayer\");\r\n";
        htmlstr+="	e = window.event || e;\r\n";
        htmlstr+="var DateLayerLeft=DateLayer.style.posLeft || parseInt(DateLayer.style.left.replace(\"px\",\"\"));\r\n";
        htmlstr+="var DateLayerTop=DateLayer.style.posTop || parseInt(DateLayer.style.top.replace(\"px\",\"\"));\r\n";
        htmlstr+="if(MoveaStart){DateLayer.style.left=(DateLayerLeft+e.clientX-MouseX)+\"px\";DateLayer.style.top=(DateLayerTop+e.clientY-MouseY)+\"px\"};\r\n";
        htmlstr+="}\r\n";

        htmlstr+="document.getElementById(\"G_calendar-week\").onmousedown=function(e){\r\n";
        htmlstr+="if(Moveable){MoveaStart=true;}\r\n";
        htmlstr+="	e = window.event || e;\r\n";
        htmlstr+="  MouseX = e.clientX;\r\n";
        htmlstr+="  MouseY = e.clientY;\r\n";
        htmlstr+="	}\r\n";
        htmlstr+="function StopMove(){\r\n";
        htmlstr+="MoveaStart=false;\r\n";
        htmlstr+="	}\r\n";
        htmlstr+="function sAlert(str){\r\n";
        htmlstr+="var msgw,msgh,bordercolor;\r\n";
        htmlstr+="msgw=200;\r\n";
        htmlstr+="msgh=180;\r\n";
        htmlstr+="titleheight=25;\r\n";
        htmlstr+="bordercolor=\"#336699\";\r\n";
        htmlstr+="titlecolor=\"#99CCFF\";\r\n";
        htmlstr+="var msgObj=document.createElement(\"div\");\r\n";
        htmlstr+="msgObj.setAttribute(\"id\",\"msgDiv\"); \r\n";
        htmlstr+="msgObj.setAttribute(\"align\",\"center\"); \r\n";
        htmlstr+="msgObj.style.background=\"white\"; \r\n";
        htmlstr+="msgObj.style.border=\"1px solid \" + bordercolor; \r\n";
        htmlstr+="msgObj.style.position = \"absolute\";\r\n";
        htmlstr+="var parentObj=document.getElementById(\"G_calendar-help\");\r\n";
        htmlstr+="msgObj.style.left = parentObj.offsetLeft; \r\n";
        htmlstr+="msgObj.style.top = parentObj.offsetTop-msgh+14; \r\n";
        htmlstr+="msgObj.style.font=\"12px/1.6em Verdana, Geneva, Arial, Helvetica, sans-serif\";\r\n";
        htmlstr+="msgObj.style.width = msgw + \"px\"; \r\n";
        htmlstr+="msgObj.style.height =msgh + \"px\"; \r\n";
        htmlstr+="msgObj.style.textAlign = \"left\"; \r\n";
        htmlstr+="msgObj.style.lineHeight =\"22px\"; \r\n";
        htmlstr+="msgObj.style.zIndex = \"10001\"; \r\n";
        htmlstr+="var title=document.createElement(\"h4\"); \r\n";
        htmlstr+="title.setAttribute(\"id\",\"msgTitle\"); \r\n";
        htmlstr+="title.setAttribute(\"align\",\"right\"); \r\n";
        htmlstr+="title.style.margin=\"0\"; \r\n";
        htmlstr+="title.style.padding=\"3px\"; \r\n";
        htmlstr+="title.style.background=bordercolor;\r\n";
        htmlstr+="title.style.border=\"1px solid \" + bordercolor; \r\n";
        htmlstr+="title.style.height=\"18px\";\r\n";
        htmlstr+="title.style.font=\"12px Verdana, Geneva, Arial, Helvetica, sans-serif\";\r\n";
        htmlstr+="title.style.color=\"white\";\r\n";
        htmlstr+="title.style.cursor=\"pointer\";\r\n";
        htmlstr+="title.innerHTML=\"关闭\";\r\n";
        htmlstr+="title.onclick=function(){\r\n";
        htmlstr+="document.getElementById(\"msgDiv\").removeChild(title);\r\n";
        htmlstr+="document.body.removeChild(msgObj);\r\n";
        htmlstr+="}\r\n";
        htmlstr+="document.body.appendChild(msgObj);\r\n";
        htmlstr+="document.getElementById(\"msgDiv\").appendChild(title);\r\n";
        htmlstr+="var txt=document.createElement(\"p\"); \r\n";
        htmlstr+="txt.style.margin=\"1em 0\";\r\n";
        htmlstr+="txt.setAttribute(\"id\",\"msgTxt\");\r\n";
        htmlstr+="txt.innerHTML=str;\r\n";
        htmlstr+="document.getElementById(\"msgDiv\").appendChild(txt);\r\n";
        htmlstr+="}\r\n";
        htmlstr+="</scr"+"ipt>\r\n";
        var stylestr="";
        stylestr+="<style type=\"text/css\">";
        stylestr+="body{background:#fff;font-size:12px;margin:0px;padding:0px;text-align:left;position:relative;}\r\n";
        stylestr+="#G_calendar{border:1px solid #6bc4f3;width:205px;padding:1px;height:245px;z-index:9998;text-align:center;}\r\n";
        stylestr+="#G_calendar-year-month{height:23px;line-height:23px;z-index:9998;border-bottom:1px solid #6bc4f3;}\r\n";
        stylestr+="#G_calendar-year{line-height:20px;width:60px;float:left;z-index:9998;position: absolute;top: 3; left: 45;cursor:default;font-weight:bold;text-align:right;}\r\n";
        stylestr+="#G_calendar-month{line-height:20px;width:45px;float:left;z-index:9998;position: absolute;top: 3; left: 105;cursor:default;font-weight:bold;text-align:left;}\r\n";
        stylestr+="#G_calendar-PrevM{position: absolute;top: 3px; left: 5px;cursor:pointer;color:#f7860f;}\r\n";
        stylestr+="#G_calendar-PrevM-text{margin-left:3px;color:#108bcd;}\r\n";
        stylestr+="#G_calendar-NextM{position: absolute;top: 3px; left:160px;cursor:pointer;color:#f7860f;width:40px;text-align:right;}\r\n";
        stylestr+="#G_calendar-week{height:23px;line-height:23px;z-index:9998;}\r\n";
        stylestr+="#G_calendar-NextM-text{color:#108bcd;}\r\n";
        stylestr+="#G_calendar-day{height:175px;;z-index:9998;}\r\n";
        stylestr+="#G_calendar-week{background:#edf8fe;}\r\n";
        stylestr+="#G_calendar-week ul{cursor:move;list-style:none;margin:0px;padding:0px;margin-left:5px;}\r\n";
        stylestr+="#G_calendar-week li{width:24px !important;width:23px;height:24px !important;height:23px;line-height:23px;float:left;margin:2px;padding:0px;text-align:center;}\r\n";
        stylestr+="#G_calendar-day{background:#edf8fe;border-bottom:1px solid #d5d5d5}\r\n";
        stylestr+="#G_calendar-day ul{list-style:none;margin:0px;padding:0px;margin-left:5px;}\r\n";
        stylestr+="#G_calendar-day li{cursor:pointer;width:22px !important;width:23px;height:22px !important;height:23px;line-height:23px;float:left;;margin:2px;padding:0px;border:1px solid #6bc4f3;}\r\n";
        stylestr+="#G_calendar-help{color:#108bcd;float:left;width:30px;margin-top:8px;cursor:pointer;}\r\n";
        stylestr+="#G_calendar-show-week{float:left;width:60px;margin-top:8px;text-align:right;}\r\n";
        stylestr+="#G_calendar-show-info{float:left;width:85px;*width:80px;margin-top:8px;}\r\n";
        stylestr+="#G_calendar-show-price{color:f7860f;}\r\n";
        stylestr+="#G_calendar-close{color:#108bcd;float:left;width:30px;margin-top:8px;cursor:pointer;}\r\n";
        stylestr+="#btnClose{cursor:pointer; color:#FF0000;}";
        stylestr+="</style>";
        var TempLateContent="<html>\r\n";
        TempLateContent+="<head>\r\n";
        TempLateContent+="<title></title>\r\n";
        TempLateContent+=stylestr;
        TempLateContent+="</head>\r\n";
        TempLateContent+="<body>\r\n";
        TempLateContent+=htmlstr;
        TempLateContent+="</body>\r\n";
        TempLateContent+="</html>\r\n";
        this.GetDateLayer().document.writeln(TempLateContent);
        this.GetDateLayer().document.close();
    },
    InsertHTML:function(id,htmlstr){
        var G_DateLayer=this.GetDateLayer();
        if(G_DateLayer){G_DateLayer.document.getElementById(id).innerHTML=htmlstr;}
    },
    WriteHead:function (yy,mm)  //往 head 中写入当前的年与月
    {
        this.InsertHTML("G_calendar-year",yy + "年");
        this.InsertHTML("G_calendar-month",mm + "月");

        mm=Number(mm);
        var prevM=mm==1?12:mm-1;
        var nextM=mm==12?1:mm+1;

        this.InsertHTML("G_calendar-PrevM-text",prevM + "月");
        this.InsertHTML("G_calendar-NextM-text",nextM + "月");
    },
    IsPinYear:function(year)            //判断是否闰平年
    {
        if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
    },
    GetMonthCount:function(year,month)  //闰年二月为29天
    {
        var c=this.MonHead[month-1];if((month==2)&&this.IsPinYear(year)) c++;return c;
    },
    GetDOW:function(day,month,year)     //求某天的星期几
    {
        var day = new Date(year,month-1,day); //将日期值格式化
        var today = new Array("周日","周一","周二","周三","周四","周五","周六");
        return today[day.getDay()];
    },
    GetDOWToday:function()     //求某天的星期几
    {
        var day = new Date(new Date().getFullYear(),new Date().getMonth(),new Date().getDate()); //将日期值格式化
        var today = new Array("周日","周一","周二","周三","周四","周五","周六");
        return today[day.getDay()];
    },
    GetDateDiff:function (sDate1, sDate2)
    {// 计算两个日期的间隔天数
        //sDate1和sDate2是2002-12-18格式
        var aDate, oDate1, oDate2, iDays;

        aDate = sDate1.split("-");
        oDate1 = new Date(aDate[0],aDate[1]-1,aDate[2],01,00,00);
        aDate = sDate2.split("-");
        oDate2 = new Date(aDate[0],aDate[1]-1,aDate[2],00,00,00);

        iDays = parseInt((oDate1 - oDate2) / 1000 / 60 / 60 /24); //把相差的毫秒数转换为天数
        return iDays;
    },
    GetText:function(obj){
        if(obj.innerText){return obj.innerText}
        else{return obj.textContent}
    },
    PrevM:function()  //往前翻月份
    {
        if(this.L_TheMonth>1){this.L_TheMonth--}else{this.L_TheYear--;this.L_TheMonth=12;}
        this.SetDay(this.L_TheYear,this.L_TheMonth);
    },
    NextM:function()  //往后翻月份
    {
        if(this.L_TheMonth==12){this.L_TheYear++;this.L_TheMonth=1}else{this.L_TheMonth++}
        this.SetDay(this.L_TheYear,this.L_TheMonth);
    },
    SetDay:function (yy,mm)   //主要的写程序**********
    {
		var arr_soldout_dates=Array();
        var infoCount=this.DayInfo.length;
        this.WriteHead(yy,mm);
        //设置当前年月的公共变量为传入值
        this.L_TheYear=yy;
        this.L_TheMonth=mm;
        //当页面本身位于框架中时 IE会返回错误的parent
        if(window.top.location.href!=window.location.href){
            for(var i_f=0;i_f<window.top.frames.length;i_f++){
                    if(window.top.frames[i_f].location.href==window.location.href){G_DateLayer_Parent=window.top.frames[i_f];}
            }
        }
        else{
            G_DateLayer_Parent=window.parent;
        }
        for (var i = 0; i < 39; i++){this.L_WDay[i]=""}  //将显示框的内容全部清空
        var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();  //某月第一天的星期几
        for (i=0;i<firstday;i++)this.L_WDay[i]=this.GetMonthCount(mm==1?yy-1:yy,mm==1?12:mm-1)-firstday+i+1;	//上个月的最后几天
        for (i = firstday; day1 < this.GetMonthCount(yy,mm)+1; i++){this.L_WDay[i]=day1;day1++;}
        for (i=firstday+this.GetMonthCount(yy,mm);i<39;i++){this.L_WDay[i]=day2;day2++}
        for (i = 0; i < 39; i++)
        {
            var da=this.GetDateLayer().document.getElementById("G_calendar-day_"+i+"");
            var month,day;
            if (this.L_WDay[i]!="")
            {
                if(i<firstday){
                    da.style.border="1px solid #6bc4f3";
                    da.style.visibility="hidden";
                    da.innerHTML="<span style=\"color:gray\">" + this.L_WDay[i] + "</span>";
                    month=(mm==1?12:mm-1);
                    day=this.L_WDay[i];
                }
                else if(i>=firstday+this.GetMonthCount(yy,mm)){
                    da.style.visibility="hidden";
                    da.style.border="1px solid #6bc4f3";
                    da.innerHTML="<span style=\"color:gray\">" + this.L_WDay[i] + "</span>";
                    month=(mm==12?1:mm+1);
                    day=this.L_WDay[i];
                }
                else{
                    month=mm;
                    day=this.L_WDay[i];
                    var monthNow=new Date().getMonth()+1;
                    var dateDiff=this.GetDateDiff(yy+"-"+month+"-"+day,new Date().getFullYear()+"-"+monthNow+"-"+new Date().getDate());
					if(infoCount>=dateDiff-1 && this.Unlimited == false)
                    {	//不可用的日期
						da.style.visibility="visible";
						da.style.border="1px solid #d5d5d5";
						da.style.background='#fff';
						da.style.cursor="default";
						da.innerHTML="<span style=\"color:#d5d5d5\">" + this.L_WDay[i] + "</span>";
						if(document.all){
							da.onclick=Function("");
							da.onmouseover=Function("");
							da.onmouseout=Function("");
						}
						else{
							da.setAttribute("onclick","");
							da.setAttribute("onmouseover","");
							da.setAttribute("onmouseout","");
						}
                    }else{//可用的日期
                        da.style.visibility="visible";
                        da.style.border="1px solid #d5d5d5";
                        da.style.background='#fff';
                        da.style.cursor="default";
                        da.innerHTML="<span style=\"color:#d5d5d5\">" + this.L_WDay[i] + "</span>";
                                
								da.style.visibility="visible";
                                da.style.border="1px solid #6bc4f3";
                                da.style.background='#fff';
                                da.innerHTML="<span style=\"color:#108bcd\">" + this.L_WDay[i] + "</span>";
								
                        if(document.all){
							da.onclick=Function("G_DateLayer_Parent."+this.NewName+".DayClick("+month+","+day+","+"false"+",'',"+"3"+")");
							da.onmouseover=Function("G_DateLayer_Parent."+this.NewName+".OnMouseOverDay(this,"+yy+","+month+","+day+","+dateDiff+")");
							da.onmouseout=Function("G_DateLayer_Parent."+this.NewName+".OnMouseOutDay(this,"+dateDiff+","+"false"+")");
                        }
                        else{
							da.setAttribute("onclick","parent."+this.NewName+".DayClick("+month+","+day+","+"false"+",'"+""+"',"+"3"+")");
							da.setAttribute("onmouseover","parent."+this.NewName+".OnMouseOverDay(this,"+yy+","+month+","+day+","+dateDiff+")");
							da.setAttribute("onmouseout","parent."+this.NewName+".OnMouseOutDay(this,"+dateDiff+","+"false"+")");
                        }
						da.style.cursor="pointer";
                    }
                }
                
				da.title=month+" 月"+day+" 日";

                if(yy == new Date().getFullYear()&&month==new Date().getMonth()+1&&day==new Date().getDate())
                {
                    da.style.border="1px solid #f7860f";
                    da.firstChild.style.color="#f7860f";
                    da.firstChild.style.fontWeight="bold";
                }
            }
        }
    },
    SelectYearInnerHTML:function () //年份的下拉框
    {
        var DateLayer=this.GetDateLayer();
        var strYear=DateLayer.document.getElementById("G_calendar-year").innerHTML.substr(0,4);
        if(strYear.match(/\D/)!=null){alert("年份输入参数不是数字！");return;}

        var m = (strYear) ? strYear : new Date().getFullYear();
        if (m < 1000 || m > 9999) {alert("年份值不在 1000 到 9999 之间！");return;}
        var n = m - 10;
        if (n < 1000) n = 1000;
        if (n + 26 > 9999) n = 9974;
        var s = "<select name=\"L_SelectYear\" id=\"L_SelectYear\" style='font-size: 12px' ";
        s += "onblur='document.getElementById(\"SelectYearLayer\").style.display=\"none\"' ";
        s += "onchange='document.getElementById(\"SelectYearLayer\").style.display=\"none\"; ";
        s += "parent."+this.NewName+".L_TheYear = this.value; parent."+this.NewName+".SetDay(parent."+this.NewName+".L_TheYear,parent."+this.NewName+".L_TheMonth)'>\r\n";
        var selectInnerHTML = s;
        for (var i = n; i < n + 26; i++)
        {
            if (i == m){
                selectInnerHTML += "<option value='" + i + "' selected>" + i + "年" + "</option>\r\n";
            }
            else{
                selectInnerHTML += "<option value='" + i + "'>" + i + "年" + "</option>\r\n";
            }
        }
        selectInnerHTML += "</select>";
        DateLayer.document.getElementById("SelectYearLayer").style.display="";
        DateLayer.document.getElementById("SelectYearLayer").innerHTML = selectInnerHTML;
        DateLayer.document.getElementById("L_SelectYear").focus();

    },
    SelectMonthInnerHTML:function () //月份的下拉框
    {
        var DateLayer=this.GetDateLayer();
        var strMonth=DateLayer.document.getElementById("G_calendar-month").innerHTML.substr(0,2);
        if (strMonth.match(/\D/)!=null){strMonth=strMonth.substr(0,1);}
        if (strMonth.match(/\D/)!=null){alert("月份输入参数不是数字！");return;}

        var m = (strMonth) ? strMonth : new Date().getMonth() + 1;
        var s = "<select name=\"L_SelectYear\" id=\"L_SelectMonth\" style='font-size: 12px' ";
        s += "onblur='document.getElementById(\"SelectMonthLayer\").style.display=\"none\"' ";
        s += "onchange='document.getElementById(\"SelectMonthLayer\").style.display=\"none\"; ";
        s += "parent."+this.NewName+".L_TheMonth = this.value; parent."+this.NewName+".SetDay(parent."+this.NewName+".L_TheYear,parent."+this.NewName+".L_TheMonth)'>\r\n";
        var selectInnerHTML = s;
        for (var i = 1; i < 13; i++)
        {
            if (i == m){
                selectInnerHTML += "<option value='"+i+"' selected>"+i+"月"+"</option>\r\n";
            }
            else{
                selectInnerHTML += "<option value='"+i+"'>"+i+"月"+"</option>\r\n";
            }
        }
        selectInnerHTML += "</select>";
        DateLayer.document.getElementById("SelectMonthLayer").style.display="";
        DateLayer.document.getElementById("SelectMonthLayer").innerHTML = selectInnerHTML;
        DateLayer.document.getElementById("L_SelectMonth").focus();
    },
    DayClick:function(mm,dd,ynJQ,info,selectIndex)  //点击显示框选取日期，主输入函数*************
    {
		
        var yy=this.L_TheYear;
        //判断月份，并进行对应的处理
        if(mm<1){yy--;mm=12+mm;}
        else if(mm>12){yy++;mm=mm-12;}
        if (mm < 10){mm = "0" + mm;}
        if (this.ClickObject){
            if (!dd) {return;}
            if ( dd < 10){dd = "0" + dd;}

            if(ynJQ==false){
                //this.InputObject.value= mm+"/"+dd+"/"+yy+" ("+this.GetDOW(dd,mm,yy)+")"; //注：在这里你可以输出改成你想要的格式
           		this.InputObject.value= this.OutPutType.replace(/y/,yy).replace(/m/,mm).replace(/d/,dd); //注：在这里你可以输出改成你想要的格式
            }
            else{
                this.InputObject.value= mm+"/"+dd+"/"+yy+" ("+this.GetDOW(dd,mm,yy)+") ("+info+")";
            }
            
            this.CloseLayer();
        	this.InputObject.focus();
		}
        else {this.CloseLayer(); alert("您所要输出的控件对象并不存在！");}
    },
    SetUnlimited:function(val){	//如果val为1则不限制日期，所有日期可用
		if(val==1){ this.Unlimited = true;	}else{ this.Unlimited = false;}
	},
	SetDate:function(){

        if (arguments.length <  1){alert("对不起！传入参数太少！");return;}
        else if (arguments.length >  2){alert("对不起！传入参数太多！");return;}
        this.InputObject=(arguments.length==1) ? arguments[0] : arguments[1];
        this.ClickObject=arguments[0];
        var reg = /^(\d+)-(\d{1,2})-(\d{1,2})$/;
        var r = this.InputObject.value.match(reg);
        var atd = "";
        if(r!=null){
            r[2]=r[2]-1;
            var d= new Date(r[1], r[2],r[3]);
            if(d.getFullYear()==r[1] && d.getMonth()==r[2] && d.getDate()==r[3]){
                    this.InputDate=d;		//保存外部传入的日期
            }
            else this.InputDate="";
            this.L_TheYear=r[1];
            this.L_TheMonth=r[2]+1;
        }
        else if(atd){
            atdArray=atd.split("-");
            this.L_TheYear=atdArray[0];
            this.L_TheMonth=atdArray[1];
        }
        else{
            this.L_TheYear=new Date().getFullYear();
            this.L_TheMonth=new Date().getMonth() + 1
        }
        this.CreateHTML();
        var top=this.GetY();
        var left=this.GetX();
        var DateLayer=document.getElementById("G_DateLayer");
        DateLayer.style.top=top+this.ClickObject.clientHeight+5+"px";
        DateLayer.style.left=left+"px";
        DateLayer.style.display="block";
        if(document.all){
            this.GetDateLayer().document.getElementById("G_calendar").style.width="205px";
            this.GetDateLayer().document.getElementById("G_calendar").style.height="245px"
        }
        else{
            this.GetDateLayer().document.getElementById("G_calendar").style.width="205px";
            this.GetDateLayer().document.getElementById("G_calendar").style.height="245px"
            DateLayer.style.width="220px";
            DateLayer.style.height="250px";
        }
        this.SetDay(this.L_TheYear,this.L_TheMonth);
    },
    CloseLayer:function(){
        try{
            var DateLayer=document.getElementById("G_DateLayer");
            if((DateLayer.style.display=="" || DateLayer.style.display=="block") && arguments[0]!=this.ClickObject && arguments[0]!=this.InputObject){
                    DateLayer.style.display="none";
            }
        }
        catch(e){}
    },
    OnClose:function(){
        var DateLayer=document.getElementById("G_DateLayer");
        DateLayer.style.display="none";
    },
    clearInput:function(){
    	this.InputObject.value="";
    	this.CloseLayer();
    },
    OnMouseOverDay:function(e,yy,mm,day,dateDiff){
        e.style.border='1px solid #243c6c';
        e.firstChild.style.color='#243c6c';
        this.GetDateLayer().document.getElementById("G_calendar-show-week").innerHTML=this.GetDOW(day,mm,yy);
    },
    OnMouseOutDay:function(e,dateDiff,ynJQ){
        if(ynJQ)
        {
            e.style.border="1px solid #f7860f";
            e.style.background='#ffeadd';
        }
        else
        {
            e.style.background='#fff';
            e.style.border='1px solid #6bc4f3';
        }
        e.firstChild.style.color='#108bcd';
        if(dateDiff==0)
        {
            e.style.border="1px solid #f7860f";
            e.firstChild.style.color='#f7860f';
        }
        this.GetDateLayer().document.getElementById("G_calendar-show-week").innerHTML=this.GetDOWToday();
        this.GetDateLayer().document.getElementById("G_calendar-show-price").innerHTML="";
        if(window.ActiveXObject){
            var showinfo =this.GetDateLayer().document.getElementById("G_calendar-show-info");
            showinfo.style.marginTop="8px";
        }
    }
};

document.writeln('<iframe id="G_DateLayer" name="G_DateLayer" frameborder="0" style="position:absolute;width:220px; height:250px;z-index:1410065407;display:none;"></iframe>'); 
var G_DateLayer_Parent=null;
var GeCalendar=new G_calendar();
GeCalendar.NewName="GeCalendar";

document.onclick=function(e)
{
    e = window.event || e;
    var srcElement = e.srcElement || e.target;
    GeCalendar.CloseLayer(srcElement);
}
