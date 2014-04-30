<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--
<?php
}
?>

/*出发日期选择框的日历start*/
function L_calendar(){}
L_calendar.prototype={
    HelpMsg:"<?php echo db_to_html('1.蓝色框日期为普通可出发日期;<br>2.粉红背景红框为特别价格日期;<br>3.红色框日期为今日日期;<br>4.灰色日期没有行程安排,不可选;<br>5.被加中划线是已卖完的日期;<br>6.日历底部文字是对日期的说明');?>",/* 在这里添加和修改帮助文档的文字 */

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
    GetDateLayer:function(){
        return window.L_DateLayer;
    },
    L_TheYear: new Date().getFullYear(), /* 定义年的变量的初始值 */
    L_TheMonth: new Date().getMonth()+1,/* 定义月的变量的初始值 */
    L_WDay:new Array(39),/* 定义写日期的数组 */
    MonHead:new Array(31,28,31,30,31,30,31,31,30,31,30,31),/* 定义阳历中每个月的最大天数 */
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
        htmlstr+="<div id=\"L_calendar\">\r\n";
        htmlstr+="<span id=\"SelectYearLayer\" style=\"z-index: 9999;position: absolute;top: 3; left: 45;display: none\"></span>\r\n";
        htmlstr+="<span id=\"SelectMonthLayer\" style=\"z-index: 9999;position: absolute;top: 3; left: 105;display: none\"></span>\r\n";
        htmlstr+="<div id=\"L_calendar-year-month\">\r\n";
        htmlstr+="<div id=\"L_calendar-PrevM\" onclick=\"parent."+this.NewName+".PrevM()\" title=\"<?php echo db_to_html('前一月')?>\"><b>&lt;</b><span id=\"L_calendar-PrevM-text\"></span></div>\r\n";
        htmlstr+="<div id=\"L_calendar-year\" onmouseover=\"style.backgroundColor='#ffeadd'\" onmouseout=\"style.backgroundColor='white'\" onclick=\"parent."+this.NewName+".SelectYearInnerHTML()\"></div>\r\n";
        htmlstr+="<div id=\"L_calendar-month\"  onmouseover=\"style.backgroundColor='#ffeadd'\" onmouseout=\"style.backgroundColor='white'\" onclick=\"parent."+this.NewName+".SelectMonthInnerHTML()\"></div>\r\n";
        htmlstr+="<div id=\"L_calendar-NextM\" onclick=\"parent."+this.NewName+".NextM()\" title=\"<?php echo db_to_html('后一月')?>\"><span id=\"L_calendar-NextM-text\"></span><b>&gt;</b></div>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="<div id=\"L_calendar-week\"><ul  onmouseup=\"StopMove()\"><li><b><?php echo db_to_html('日')?></b></li><li><?php echo db_to_html('一')?></li><li><?php echo db_to_html('二')?></li><li><?php echo db_to_html('三')?></li><li><?php echo db_to_html('四')?></li><li><?php echo db_to_html('五')?></li><li><b><?php echo db_to_html('六')?></b></li></ul></div>\r\n";
        htmlstr+="<div id=\"L_calendar-day\">\r\n";
        htmlstr+="<ul>\r\n";
        for(var i=0;i<this.L_WDay.length;i++){
            htmlstr+="<li id=\"L_calendar-day_"+i+"\" style=\"background:#fff;border:1px solid #6bc4f3\" ></li>";
        }
        htmlstr+="</ul>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="<div>\r\n";
        htmlstr+="<div id=\"L_calendar-help\" onclick='sAlert(\""+this.HelpMsg+"\")'><span><?php echo db_to_html('帮助')?></span></div>\r\n";
        htmlstr+="<div id=\"L_calendar-show-week\"><span>"+this.GetDOWToday()+"</span></div>\r\n";
        htmlstr+="<div id=\"L_calendar-show-info\"><span></span><b id=\"L_calendar-show-price\"></b></div>\r\n";
        htmlstr+="<div id=\"L_calendar-close\" onclick='parent."+this.NewName+".OnClose()'><span><?php echo db_to_html('关闭')?></span></div>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="</div>\r\n";
        htmlstr+="<scr" + "ipt type=\"text/javas" + "cript\">\r\n";
        htmlstr+="var MouseX,MouseY;";
        htmlstr+="var Moveable="+this.Moveable+";\r\n";
        htmlstr+="var MoveaStart=false;\r\n";
        htmlstr+="document.onmousemove=function(e)\r\n";
        htmlstr+="{\r\n";
        htmlstr+="var DateLayer=parent.document.getElementById(\"L_DateLayer\");\r\n";
        htmlstr+="	e = window.event || e;\r\n";
        htmlstr+="var DateLayerLeft=DateLayer.style.posLeft || parseInt(DateLayer.style.left.replace(\"px\",\"\"));\r\n";
        htmlstr+="var DateLayerTop=DateLayer.style.posTop || parseInt(DateLayer.style.top.replace(\"px\",\"\"));\r\n";
        htmlstr+="if(MoveaStart){DateLayer.style.left=(DateLayerLeft+e.clientX-MouseX)+\"px\";DateLayer.style.top=(DateLayerTop+e.clientY-MouseY)+\"px\"};\r\n";
        htmlstr+="}\r\n";

        htmlstr+="document.getElementById(\"L_calendar-week\").onmousedown=function(e){\r\n";
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
        htmlstr+="var parentObj=document.getElementById(\"L_calendar-help\");\r\n";
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
        htmlstr+="title.innerHTML=\"<?php echo db_to_html('关闭')?>\";\r\n";
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
        stylestr+="#L_calendar{border:1px solid #6bc4f3;width:205px;padding:1px;height:245px;z-index:9998;text-align:center;}\r\n";
        stylestr+="#L_calendar-year-month{height:23px;line-height:23px;z-index:9998;border-bottom:1px solid #6bc4f3;}\r\n";
        stylestr+="#L_calendar-year{line-height:20px;width:60px;float:left;z-index:9998;position: absolute;top: 3; left: 45;cursor:default;font-weight:bold;text-align:right;}\r\n";
        stylestr+="#L_calendar-month{line-height:20px;width:45px;float:left;z-index:9998;position: absolute;top: 3; left: 105;cursor:default;font-weight:bold;text-align:left;}\r\n";
        stylestr+="#L_calendar-PrevM{position: absolute;top: 3px; left: 5px;cursor:pointer;color:#f7860f;}\r\n";
        stylestr+="#L_calendar-PrevM-text{margin-left:3px;color:#108bcd;}\r\n";
        stylestr+="#L_calendar-NextM{position: absolute;top: 3px; left:160px;cursor:pointer;color:#f7860f;width:40px;text-align:right;}\r\n";
        stylestr+="#L_calendar-week{height:23px;line-height:23px;z-index:9998;}\r\n";
        stylestr+="#L_calendar-NextM-text{color:#108bcd;}\r\n";
        stylestr+="#L_calendar-day{height:175px;;z-index:9998;}\r\n";
        stylestr+="#L_calendar-week{background:#edf8fe;}\r\n";
        stylestr+="#L_calendar-week ul{cursor:move;list-style:none;margin:0px;padding:0px;margin-left:5px;}\r\n";
        stylestr+="#L_calendar-week li{width:24px !important;width:23px;height:24px !important;height:23px;line-height:23px;float:left;margin:2px;padding:0px;text-align:center;}\r\n";
        stylestr+="#L_calendar-day{background:#edf8fe;border-bottom:1px solid #d5d5d5}\r\n";
        stylestr+="#L_calendar-day ul{list-style:none;margin:0px;padding:0px;margin-left:5px;}\r\n";
        stylestr+="#L_calendar-day li{cursor:pointer;width:22px !important;width:23px;height:22px !important;height:23px;line-height:23px;float:left;;margin:2px;padding:0px;border:1px solid #6bc4f3;}\r\n";
        stylestr+="#L_calendar-help{color:#108bcd;float:left;width:30px;margin-top:8px;cursor:pointer;}\r\n";
        stylestr+="#L_calendar-show-week{float:left;width:60px;margin-top:8px;text-align:right;}\r\n";
        stylestr+="#L_calendar-show-info{float:left;width:85px;*width:80px;margin-top:8px;}\r\n";
        stylestr+="#L_calendar-show-price{color:f7860f;}\r\n";
        stylestr+="#L_calendar-close{color:#108bcd;float:left;width:30px;margin-top:8px;cursor:pointer;}\r\n";
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
        var L_DateLayer=this.GetDateLayer();
        if(L_DateLayer){L_DateLayer.document.getElementById(id).innerHTML=htmlstr;}
    },
    WriteHead:function (yy,mm)  /* 往 head 中写入当前的年与月 */
    {
        this.InsertHTML("L_calendar-year",yy + "<?php echo db_to_html('年')?>");
        this.InsertHTML("L_calendar-month",mm + "<?php echo db_to_html('月')?>");

        mm=Number(mm);
        var prevM=mm==1?12:mm-1;
        var nextM=mm==12?1:mm+1;

        this.InsertHTML("L_calendar-PrevM-text",prevM + "<?php echo db_to_html('月')?>");
        this.InsertHTML("L_calendar-NextM-text",nextM + "<?php echo db_to_html('月')?>");
    },
    IsPinYear:function(year)            /* 判断是否闰平年 */
    {
        if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
    },
    GetMonthCount:function(year,month)  /* 闰年二月为29天 */
    {
        var c=this.MonHead[month-1];if((month==2)&&this.IsPinYear(year)) c++;return c;
    },
    GetDOW:function(day,month,year)     /* 求某天的星期几 */
    {
        var day = new Date(year,month-1,day); /* 将日期值格式化 */
        var today = new Array("<?php echo db_to_html('周日')?>","<?php echo db_to_html('周一')?>","<?php echo db_to_html('周二')?>","<?php echo db_to_html('周三')?>","<?php echo db_to_html('周四')?>","<?php echo db_to_html('周五')?>","<?php echo db_to_html('周六')?>");
        return today[day.getDay()];
    },
    GetDOWToday:function()     /* 求今天的星期几 */
    {
        var day = new Date(new Date().getFullYear(),(new Date().getMonth()+1-1),new Date().getDate()); /* 将日期值格式化 */
        var today = new Array("<?php echo db_to_html('周日')?>","<?php echo db_to_html('周一')?>","<?php echo db_to_html('周二')?>","<?php echo db_to_html('周三')?>","<?php echo db_to_html('周四')?>","<?php echo db_to_html('周五')?>","<?php echo db_to_html('周六')?>");
        return today[day.getDay()];
    },
    GetDateDiff:function (sDate1, sDate2)
    {/*  计算两个日期的间隔天数 */
        /* sDate1和sDate2是2002-12-18格式 */
        var aDate, oDate1, oDate2, iDays;

        aDate = sDate1.split("-");
        oDate1 = new Date(aDate[0],aDate[1]-1,aDate[2],01,00,00);
        aDate = sDate2.split("-");
        oDate2 = new Date(aDate[0],aDate[1]-1,aDate[2],00,00,00);

        iDays = parseInt((oDate1 - oDate2) / 1000 / 60 / 60 /24); /* 把相差的毫秒数转换为天数 */
        return iDays;
    },
    GetText:function(obj){
        if(obj.innerText){return obj.innerText}
        else{return obj.textContent}
    },
    PrevM:function()  /* 往前翻月份 */
    {
        if(this.L_TheMonth>1){this.L_TheMonth--}else{this.L_TheYear--;this.L_TheMonth=12;}
        this.SetDay(this.L_TheYear,this.L_TheMonth);
    },
    NextM:function()  /* 往后翻月份 */
    {
        if(this.L_TheMonth==12){this.L_TheYear++;this.L_TheMonth=1}else{this.L_TheMonth++}
        this.SetDay(this.L_TheYear,this.L_TheMonth);
    },
    SetDay:function (yy,mm)   /* 主要的写程序********** */
    {
		var arr_soldout_dates=Array();
		<?php
			$qry_sold_dates="SELECT * FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." sd WHERE sd.products_id='".$products_id."'";
			$res_sold_dates=tep_db_query($qry_sold_dates);
			$ar_soldout_dates=array();
			while($row_sold_dates=tep_db_fetch_array($res_sold_dates)){
			$cntr++;
		?>
				arr_soldout_dates[<?php echo $cntr;?>] = "<?php echo $row_sold_dates['products_soldout_date'];?>";
		<?php
			}
		?>
        var infoCount=this.DayInfo.length;
        this.WriteHead(yy,mm);
        /* 设置当前年月的公共变量为传入值 */
        this.L_TheYear=yy;
        this.L_TheMonth=mm;
        /* 当页面本身位于框架中时 IE会返回错误的parent */
        if(window.top.location.href!=window.location.href){
            for(var i_f=0;i_f<window.top.frames.length;i_f++){
                    if(window.top.frames[i_f].location.href==window.location.href){L_DateLayer_Parent=window.top.frames[i_f];}
            }
        }
        else{
            L_DateLayer_Parent=window.parent;
        }
        for (var i = 0; i < 39; i++){this.L_WDay[i]=""}  /* 将显示框的内容全部清空 */
        var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();  /* 某月第一天的星期几 */
        for (i=0;i<firstday;i++)this.L_WDay[i]=this.GetMonthCount(mm==1?yy-1:yy,mm==1?12:mm-1)-firstday+i+1	/* 上个月的最后几天 */
        for (i = firstday; day1 < this.GetMonthCount(yy,mm)+1; i++){this.L_WDay[i]=day1;day1++;}
        for (i=firstday+this.GetMonthCount(yy,mm);i<39;i++){this.L_WDay[i]=day2;day2++}
        for (i = 0; i < 39; i++)
        {
			var is_date_sold = 0;
            var da=this.GetDateLayer().document.getElementById("L_calendar-day_"+i+"");
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
                    if(infoCount>=dateDiff+1)
                    {
                        /*if(typeof(this.DayInfo[dateDiff])!="undefined"){
							alert(this.DayInfo[dateDiff][0]);
						}*/
						if(dateDiff>=0&&this.DayInfo[dateDiff][0])
                        {
							if(this.DayInfo[dateDiff][1])
                            {
                                da.style.visibility="visible";
                                da.style.border="1px solid #f7860f";
                                da.style.background='#ffeadd';
                                da.innerHTML="<span style=\"color:#108bcd\">" + this.L_WDay[i] + "</span>";
                                if(document.all){
                                    da.onclick=Function("L_DateLayer_Parent."+this.NewName+".DayClick("+month+","+day+","+this.DayInfo[dateDiff][1]+",'"+this.DayInfo[dateDiff][2]+"',"+this.DayInfo[dateDiff][3]+")");
                                    da.onmouseover=Function("L_DateLayer_Parent."+this.NewName+".OnMouseOverDay(this,"+yy+","+month+","+day+","+dateDiff+")");
                                    da.onmouseout=Function("L_DateLayer_Parent."+this.NewName+".OnMouseOutDay(this,"+dateDiff+","+this.DayInfo[dateDiff][1]+")");
                                }
                                else{
                                    da.setAttribute("onclick","parent."+this.NewName+".DayClick("+month+","+day+","+this.DayInfo[dateDiff][1]+",'"+this.DayInfo[dateDiff][2]+"',"+this.DayInfo[dateDiff][3]+")");
                                    da.setAttribute("onmouseover","parent."+this.NewName+".OnMouseOverDay(this,"+yy+","+month+","+day+","+dateDiff+")");
                                    da.setAttribute("onmouseout","parent."+this.NewName+".OnMouseOutDay(this,"+dateDiff+","+this.DayInfo[dateDiff][1]+")");
                                }
                            }
                            else
                            {
                                da.style.visibility="visible";
                                da.style.border="1px solid #6bc4f3";
                                da.style.background='#fff';
                                da.innerHTML="<span style=\"color:#108bcd\">" + this.L_WDay[i] + "</span>";
                                if(document.all){
                                    da.onclick=Function("L_DateLayer_Parent."+this.NewName+".DayClick("+month+","+day+","+this.DayInfo[dateDiff][1]+",'',"+this.DayInfo[dateDiff][3]+")");
                                    da.onmouseover=Function("L_DateLayer_Parent."+this.NewName+".OnMouseOverDay(this,"+yy+","+month+","+day+","+dateDiff+")");
                                    da.onmouseout=Function("L_DateLayer_Parent."+this.NewName+".OnMouseOutDay(this,"+dateDiff+","+this.DayInfo[dateDiff][1]+")");
                                }
                                else{
                                    da.setAttribute("onclick","parent."+this.NewName+".DayClick("+month+","+day+","+this.DayInfo[dateDiff][1]+",'',"+this.DayInfo[dateDiff][3]+")");
                                    da.setAttribute("onmouseover","parent."+this.NewName+".OnMouseOverDay(this,"+yy+","+month+","+day+","+dateDiff+")");
                                    da.setAttribute("onmouseout","parent."+this.NewName+".OnMouseOutDay(this,"+dateDiff+","+this.DayInfo[dateDiff][1]+")");
                                }
                            }
                            da.style.cursor="pointer";
                        
						}else{

							var sd_yy = str_pad(yy, 4, "0", STR_PAD_LEFT);
							var sd_mm = str_pad(month, 2, "0", STR_PAD_LEFT);
							var sd_dd = str_pad(day, 2, "0", STR_PAD_LEFT);
							var sd_date = sd_yy+"-"+sd_mm+"-"+sd_dd;

							var is_date_sold = 0;
							for (sd_key in arr_soldout_dates){
								if(arr_soldout_dates[sd_key] != ""){
									if(arr_soldout_dates[sd_key] == sd_date){
										is_date_sold = 1;
									}
								}
							}

							if(is_date_sold == 1 && this.L_WDay[i] >= new Date().getDate()){
								da.style.visibility="visible";
								da.style.border="1px solid #d5d5d5";
								da.style.background='#fff';
								da.style.cursor="default";
								da.innerHTML="<span style=\"color:#FF0000; text-decoration:line-through;\">" + this.L_WDay[i] +"</span>";
								if(document.all){
									da.onclick=Function("");
									da.onmouseover=Function("");
									da.onmouseout=Function("");
								}else{
									da.setAttribute("onclick","");
									da.setAttribute("onmouseover","");
									da.setAttribute("onmouseout","");
								}
							}else{
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
							}
                        }
                    }
                    else{
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
                    }
                }
				if(is_date_sold == "1" && this.L_WDay[i] >= new Date().getDate() ){
					da.title="<?php echo db_to_html('已售完');?>";
				}else{
                	da.title=month+" <?php echo db_to_html('月')?>"+day+" <?php echo db_to_html('日')?>";
				}

                if(yy == new Date().getFullYear() && month==new Date().getMonth()+1 && day==new Date().getDate())
                {
                    da.style.border="1px solid #f7860f";
                    da.firstChild.style.color="#f7860f";
                    da.firstChild.style.fontWeight="bold";
                }
            }
        }
    },
    SelectYearInnerHTML:function () /* 年份的下拉框 */
    {
        var DateLayer=this.GetDateLayer();
        var strYear=DateLayer.document.getElementById("L_calendar-year").innerHTML.substr(0,4);
        if(strYear.match(/\D/)!=null){alert("<?php echo db_to_html('年份输入参数不是数字！')?>");return;}

        var m = (strYear) ? strYear : new Date().getFullYear();
        if (m < 1000 || m > 9999) {alert("<?php echo db_to_html('年份值不在 1000 到 9999 之间！')?>");return;}
        var n = m - 10;
        if (n < 1000) n = 1000;
        if (n + 26 > 9999) n = 9974;
        var s = "<select name=\"L_SelectYear\" id=\"L_SelectYear\" style='font-size: 12px' "
        s += "onblur='document.getElementById(\"SelectYearLayer\").style.display=\"none\"' "
        s += "onchange='document.getElementById(\"SelectYearLayer\").style.display=\"none\";"
        s += "parent."+this.NewName+".L_TheYear = this.value; parent."+this.NewName+".SetDay(parent."+this.NewName+".L_TheYear,parent."+this.NewName+".L_TheMonth)'>\r\n";
        var selectInnerHTML = s;
        for (var i = n; i < n + 26; i++)
        {
            if (i == m){
                selectInnerHTML += "<option value='" + i + "' selected>" + i + "<?php echo db_to_html('年')?>" + "</option>\r\n";
            }
            else{
                selectInnerHTML += "<option value='" + i + "'>" + i + "<?php echo db_to_html('年')?>" + "</option>\r\n";
            }
        }
        selectInnerHTML += "</select>";
        DateLayer.document.getElementById("SelectYearLayer").style.display="";
        DateLayer.document.getElementById("SelectYearLayer").innerHTML = selectInnerHTML;
        DateLayer.document.getElementById("L_SelectYear").focus();

    },
    SelectMonthInnerHTML:function () /* 月份的下拉框 */
    {
        var DateLayer=this.GetDateLayer();
        var strMonth=DateLayer.document.getElementById("L_calendar-month").innerHTML.substr(0,2);
        if (strMonth.match(/\D/)!=null){strMonth=strMonth.substr(0,1);}
        if (strMonth.match(/\D/)!=null){alert("<?php echo db_to_html('月份输入参数不是数字！')?>");return;}

        var m = (strMonth) ? strMonth : new Date().getMonth()+1;
        var s = "<select name=\"L_SelectYear\" id=\"L_SelectMonth\" style='font-size: 12px' "
        s += "onblur='document.getElementById(\"SelectMonthLayer\").style.display=\"none\"' "
        s += "onchange='document.getElementById(\"SelectMonthLayer\").style.display=\"none\";"
        s += "parent."+this.NewName+".L_TheMonth = this.value; parent."+this.NewName+".SetDay(parent."+this.NewName+".L_TheYear,parent."+this.NewName+".L_TheMonth)'>\r\n";
        var selectInnerHTML = s;
        for (var i = 1; i < 13; i++)
        {
            if (i == m){
                selectInnerHTML += "<option value='"+i+"' selected>"+i+"<?php echo db_to_html('月')?>"+"</option>\r\n";
            }
            else{
                selectInnerHTML += "<option value='"+i+"'>"+i+"<?php echo db_to_html('月')?>"+"</option>\r\n";
            }
        }
        selectInnerHTML += "</select>";
        DateLayer.document.getElementById("SelectMonthLayer").style.display="";
        DateLayer.document.getElementById("SelectMonthLayer").innerHTML = selectInnerHTML;
        DateLayer.document.getElementById("L_SelectMonth").focus();
    },
    DayClick:function(mm,dd,ynJQ,info,selectIndex)  /* 点击显示框选取日期，主输入函数************* */
    {
        var yy=this.L_TheYear;
        /* 判断月份，并进行对应的处理 */
        if(mm<1){yy--;mm=12+mm;}
        else if(mm>12){yy++;mm=mm-12;}
        if (mm < 10){mm = "0" + mm;}
        if (this.ClickObject){
            if (!dd) {return;}
            if ( dd < 10){dd = "0" + dd;}

            if(ynJQ==false){
                this.InputObject.value= mm+"/"+dd+"/"+yy+" ("+this.GetDOW(dd,mm,yy)+")"; /* 注：在这里你可以输出改成你想要的格式 */
            }
            else{
                if(info=="<?php echo db_to_html('(假日)')?>")
                {
                    this.InputObject.value= mm+"/"+dd+"/"+yy+" ("+this.GetDOW(dd,mm,yy)+") <?php echo db_to_html('(假日价格)')?>";
                }
                else
                {
                    this.InputObject.value= mm+"/"+dd+"/"+yy+" ("+this.GetDOW(dd,mm,yy)+") ("+info+")";
                }
            }
            document.getElementById("availabletourdate").options[selectIndex].selected = true;
            this.CloseLayer();
        	this.InputObject.focus();
		}
        else {this.CloseLayer(); alert("<?php echo db_to_html('您所要输出的控件对象并不存在！')?>");}
    },
    SetDate:function(){
        /* 如果首次使用日历则从页面加载可用日期 */
		
		if(this.DayInfo.length==0){
            var availabletour_date=document.getElementById("availabletourdate");
            if(availabletour_date==null){ alert("<?php echo db_to_html("日期下拉列表不存在！请与管理员联系！");?>"); }
			var aDateCount=availabletour_date.length;
            var nowMonth= new Date().getMonth()+1;
            var todayString=new Date().getFullYear()+"-"+nowMonth+"-"+new Date().getDate();

			if(typeof(availabletour_date.options[1])=="undefined"){ alert("<?php echo db_to_html("无出发日期，该团已经卖完。");?>"); return false; }
			var infoCount=this.GetDateDiff(availabletour_date.options[aDateCount-1].value.substr(0,10),todayString);
            var nowDate=new Date(new Date().getFullYear(),(new Date().getMonth()+1-1),new Date().getDate());

            var dateOption = availabletour_date.options[1].value.substr(0,10);
            var dateOptionArray=dateOption.split("-");
            var dateTemp=new Date(dateOptionArray[0],dateOptionArray[1]-1,dateOptionArray[2]);

            var j=1;
            while(dateTemp-nowDate < 0)
            {
                j++;
                dateOption = availabletour_date.options[j].value.substr(0,10);
                dateOptionArray=dateOption.split("-");
                dateTemp=new Date(dateOptionArray[0],dateOptionArray[1]-1,dateOptionArray[2]);
            }

            for(var i=0;i<=infoCount;i++)
            {
				/* var comp_temp_date = dateTemp.toString().substr(0,15); */
				/* var comp_now_date = nowDate.toString().substr(0,15); */
				var comp_temp_date = dateTemp.getFullYear()+"-"+dateTemp.getMonth()+"-"+dateTemp.getDate();
				var comp_now_date = nowDate.getFullYear()+"-"+nowDate.getMonth()+"-"+nowDate.getDate();
				
				/* alert(comp_temp_date+"
"+comp_now_date); */
				
                if(comp_temp_date==comp_now_date){
                    var dateOptionValue = availabletour_date.options[j].value;
                    if(dateOptionValue.indexOf("!!!")>-1){
                        this.DayInfo[i]=new Array(true,false,'',j);
                    }
                    else{
                        var indexFirst=dateOptionValue.indexOf("(");
                        if(indexFirst>-1)
                        {
                            var price=dateOptionValue.substr(indexFirst+1,1);
                            var indexSecond=dateOptionValue.indexOf("$");
                            var indexEnd=dateOptionValue.indexOf(")");
                            price=price+dateOptionValue.substr(indexSecond,indexEnd-indexSecond);
                            this.DayInfo[i]=new Array(true,true,price,j);
                        }
                        else
                        {
                            this.DayInfo[i]=new Array(true,true,"<?php echo db_to_html('(假日)')?>",j);
                        }
                    }
                    j++;
                    Time_difference = 0;
					if(j==2){	/* 解决客户端与服务器端的时差问题，在第一次取日期时调整最初时间值 */
						<?php
						$time_number = time();
						$utc_date = gmdate("Y-m-d H:i:s", $time_number);
						$server_date = date("Y-m-d H:i:s", $time_number);
						$server_UTC = (strtotime($server_date) - strtotime($utc_date))/(60*60);
						?>
						var dddd = new Date();
						var server_UTC = <?=$server_UTC?>;
						var Client_UTC = dddd.getTimezoneOffset()/60;
						if((server_UTC+Client_UTC)>0){	/* 如果客户端时间比服务器时间慢才调整，如美国时间比中国服务器慢8个时区 */
							Time_difference = (server_UTC+Client_UTC)*3600*1000;
						}						
					}
					nowDate=new Date(nowDate-0+1*86400000+Time_difference);
					if(i<infoCount){
                        if(typeof(availabletour_date.options[j])!="undefined"){
							dateOption = availabletour_date.options[j].value.substr(0,10);
                        	dateOptionArray=dateOption.split("-");
                        	dateTemp=new Date(dateOptionArray[0],dateOptionArray[1]-1,dateOptionArray[2]);
						}
                    }
                
				}else{
					this.DayInfo[i]=new Array(false);
					/* alert(nowDate); */
                    nowDate=new Date(nowDate-0+1*86400000);
                }
            }
        }

        if (arguments.length <  1){alert("<?php echo db_to_html('对不起！传入参数太少！')?>");return;}
        else if (arguments.length >  2){alert("<?php echo db_to_html('对不起！传入参数太多！')?>");return;}
        this.InputObject=(arguments.length==1) ? arguments[0] : arguments[1];
        this.ClickObject=arguments[0];
        var reg = /^(\d+)-(\d{1,2})-(\d{1,2})$/;
        var r = this.InputObject.value.match(reg);
        var atd = document.getElementById("availabletourdate").options[1].value.substr(0,10);
        if(r!=null){
            r[2]=r[2]-1;
            var d= new Date(r[1], r[2],r[3]);
            if(d.getFullYear()==r[1] && d.getMonth()==r[2] && d.getDate()==r[3]){
                    this.InputDate=d;		/* 保存外部传入的日期 */
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
            this.L_TheYear = new Date().getFullYear();
            this.L_TheMonth = new Date().getMonth()+1;
        }
        this.CreateHTML();
        var top=this.GetY();
        var left=this.GetX();
        var DateLayer=document.getElementById("L_DateLayer");
        DateLayer.style.top=top+this.ClickObject.clientHeight+5+"px";
        DateLayer.style.left=left+"px";
        DateLayer.style.display="block";
        if(document.all){
            this.GetDateLayer().document.getElementById("L_calendar").style.width="205px";
            this.GetDateLayer().document.getElementById("L_calendar").style.height="245px"
        }
        else{
            this.GetDateLayer().document.getElementById("L_calendar").style.width="205px";
            this.GetDateLayer().document.getElementById("L_calendar").style.height="245px"
            DateLayer.style.width="220px";
            DateLayer.style.height="250px";
        }
        this.SetDay(this.L_TheYear,this.L_TheMonth);
    },
    CloseLayer:function(){
        try{
            var DateLayer=document.getElementById("L_DateLayer");
            if((DateLayer.style.display=="" || DateLayer.style.display=="block") && arguments[0]!=this.ClickObject && arguments[0]!=this.InputObject){
                    DateLayer.style.display="none";
            }
        }
        catch(e){}
    },
    OnClose:function(){
        var DateLayer=document.getElementById("L_DateLayer");
        DateLayer.style.display="none";
    },
    OnMouseOverDay:function(e,yy,mm,day,dateDiff){
        e.style.border='1px solid #243c6c';
        e.firstChild.style.color='#243c6c';
        this.GetDateLayer().document.getElementById("L_calendar-show-week").innerHTML=this.GetDOW(day,mm,yy);
        if(this.DayInfo[dateDiff][1])
        {
            this.GetDateLayer().document.getElementById("L_calendar-show-price").innerHTML=this.DayInfo[dateDiff][2];
            if(window.ActiveXObject){
                var showinfo =this.GetDateLayer().document.getElementById("L_calendar-show-info");
                showinfo.style.marginTop="6px";
            }
        }
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
        this.GetDateLayer().document.getElementById("L_calendar-show-week").innerHTML=this.GetDOWToday();
        this.GetDateLayer().document.getElementById("L_calendar-show-price").innerHTML="";
        if(window.ActiveXObject){
            var showinfo =this.GetDateLayer().document.getElementById("L_calendar-show-info");
            showinfo.style.marginTop="8px";
        }
    }
}

document.writeln('<iframe id="L_DateLayer" name="L_DateLayer" frameborder="0" style="position:absolute;width:220px; height:250px;z-index:9998;display:none;"></iframe>');
var L_DateLayer_Parent=null;
var MyCalendar=new L_calendar();
MyCalendar.NewName="MyCalendar";
document.onclick=function(e)
{
    e = window.event || e;
    var srcElement = e.srcElement || e.target;
    MyCalendar.CloseLayer(srcElement);
}

/*出发日期选择框的日历end*/


function AddToCart(){

	validate();	/* 这里是取得接送选项、酒店等信息的 */

	var form = document.getElementById('cart_quantity');
	if(form==null){ 
		alert("no cart_quantity");
		return false;
	}
	if(form.elements["availabletourdate"].value.length < 4 ){

		/* form.elements["availabletourdate"].focus(); */
		/* alert("<?php echo TEXT_SELECT_DEPARTURE_DATE?>");	//选择出发日期 */
		/* return false; */
		if(form.elements["availabletourdate"].type=='select-one'){
			var slt = form.elements["availabletourdate"];
			/* slt.options[slt.options.length-1].selected='selected'; */
			if(typeof(slt.options[1])=="undefined"){
				alert("<?php echo db_to_html("此团已经卖完，无出发日期！");?>");
				return false;
			}else{
				slt.options[1].selected='selected';
			}
			/* alert(slt.value); */
		}

	}


	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('shopping_cart.php','action=add_product&products_id='.(int)$products_id)) ?>");

	var aparams=new Array();  /* 创建一个阵列存表单所有元素和值 */

	for(i=0; i<form.length; i++){
		var sparam=encodeURIComponent(form.elements[i].name);  /* 取得表单元素名 */
		sparam+="=";     /* 名与值之间用"="号连接 */

		if(form.elements[i].type=="radio"){	/* 处理单选按钮值 */
			var a = a;
			if(form.elements[i].checked){
				a = form.elements[i].value;
			}
			sparam+=encodeURIComponent(a);   /* 获得表单元素值 */
		}else if(form.elements[i].type=="checkbox"){	/* 处理单选按钮值 */
			var a = a;
			if(form.elements[i].checked){
				a = form.elements[i].value;
			}else{
				a = '';
			}
			sparam+=encodeURIComponent(a);   /* 获得表单元素值 */
		}else{
			sparam+=encodeURIComponent(form.elements[i].value);   /* 获得表单元素值1 */
		}

		aparams.push(sparam);   /* push是把新元素添加到阵列中去 */
	}
	var post_str = aparams.join("&");		/* 使用&将各个元素连接 */
	post_str += "&ajax=true";


	ajax.open("POST", url, true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send(post_str);

	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200 ) {
			var sex_regxp = /(.*\[Cart_Sum\])|(\[\/Cart_Sum\].*[:space:]*.*)/g;
			var sex_regxp1 = /(.*\[Cart_Total\])|(\[\/Cart_Total\].*[:space:]*.*)/g;

			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(ajax.responseText.replace(error_regxp,''));
			}


			if(ajax.responseText.search(/(\[Cart_Sum\]\d+\[\/Cart_Sum\])/g)!=-1){
				if(document.getElementById('add_cart_msn')!=null){
					var CartSum = ajax.responseText.replace(sex_regxp,'');
					var CartTotal = ajax.responseText.replace(sex_regxp1,'');
					var AddCartMsn = document.getElementById('add_cart_msn');
					AddCartMsn.innerHTML = AddCartMsn.innerHTML.replace(/\[Cart_Sum\]/g, CartSum);
					AddCartMsn.innerHTML = AddCartMsn.innerHTML.replace(/\[Cart_Total\]/g, CartTotal);
					if(document.getElementById('CarSumTop')!=null){
						document.getElementById('CarSumTop').innerHTML = CartSum;
					}
					showPopup('add_cart_msn','add_cart_msn_con');

				}else{
					alert(ajax.responseText);
				}
			}


		}

	}

}

<!--product_info.js-->

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
function popupWindow1(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=600,height=500,screenX=150,screenY=150,top=150,left=150')
}

var i = 5 ;
function showdiv_deplisting(obj_id,maxdata){
	if(maxdata=='all'){
		var all_div = document.getElementsByTagName("div");
		for(num=0; num < all_div.length; num++ ){
			if(all_div[num].id.indexOf(obj_id)>-1){
				if (all_div[num].style.display=="none"){
					all_div[num].style.display="";
				}else{
					all_div[num].style.display="none";
				}
			}
		}
	}else{
		obj_id = obj_id + "_" + i;
		i = i + 5 ;
		/* var obj = (document.getElementById)? document.getElementById(obj_id) : eval("document.all[obj_id]"); */
		var obj = document.getElementById(obj_id);
		if(obj!=null){
			if (obj.style.display=="none"){
				obj.style.display="";
			}else{
				obj.style.display="none";
			}
			if(maxdata!='' && i > maxdata){
				var obj = (document.getElementById)? document.getElementById('more_div_peparute_link') : eval("document.all['more_div_peparute_link']");
				obj.style.display="none";
			}
		}else{
			alert('no date on showdiv_deplisting("'+obj_id+'","'+maxdata+'")');
		}
	}
}


var defaultAdults="2";
var defaultchildren="0";
var cellStyle=" class='mainblue'";
var childHelp="Please provide the ages of children in each room. Children's ages should be their age at the time of travel.";
var adultHelp="";
var textChildError="Please specify the ages of all children.";
var pad='';
var adultsPerRoom=new Array(defaultAdults);
var childrenPerRoom=new Array(defaultchildren);
var childAgesPerRoom=new Array();
var numRooms=1;	/* 默认的房间数 */
var maxChildren=0;

<?php
/*
请对我们目前房间最大人数做一个程序上的优化。
对于一天以上有住宿的团，美国及加拿大团美国房间的最大人数限定为4人。也就是当客人选了一个房间时，那成人的下拉菜单，最多只显示到4。如果成人选择了4，那小孩的下拉菜单则为0；如果成人2，小孩下拉菜单可以有0，1，2的选择，确保可选的最多人说为4人。

另外还房间最多人数还需要按照地区，国家做限制，目前具体为下：
美国，加拿大，澳大利亚团：每个房间最多人数：4人。
夏威夷，欧洲：3人。
补充一点：夏威夷买二送二团每个房间最多人数为：4人。团号：733，727，728，731，732。

//豪华补充，以上问题在后台设置maximum_no_of_guest的值即可
*/
$maxPerRoomPeopleNum = 4;
$sql = tep_db_query('SELECT maximum_no_of_guest FROM '.TABLE_PRODUCTS.' WHERE products_id ="'.(int)$products_id.'" ');
$rows = tep_db_fetch_array($sql);
if((int)$rows['maximum_no_of_guest']){
	$maxPerRoomPeopleNum = (int)$rows['maximum_no_of_guest'];
}elseif(preg_match('/^33/',$cPath)){	/* 由于夏威夷自助游free_tours.php文件也用到本文的js代码并且最大人数不超过3个，所以这里要为夏威夷作专门设置 */
	//$maxPerRoomPeopleNum = 3;
}
?>
<?php
/* 删除没有价格的房间选项 start */
$adult_options_filter = "";	/* 过滤成人房间选项 */
$child_options_filter = "";	/* 过滤儿童选项 */
$price_ckeck_sql = tep_db_query('SELECT products_id,products_single,products_double,products_triple,products_quadr,products_kids FROM '.TABLE_PRODUCTS.' WHERE products_id ="'.(int)$products_id.'" AND products_durations>1 AND products_durations_type="0" ');
$price_ckeck_row = tep_db_fetch_array($price_ckeck_sql);
if((int)$price_ckeck_row['products_id']){
	if($price_ckeck_row['products_single']<1){
		$adult_options_filter .= "\n
			for(j=(adult_options.length-1); j>=0; j--){
				if(adult_options[j].value==1){
					adult_select.remove(j);
				}
			}
		\n";
	}
	if($price_ckeck_row['products_double']<1){
		$adult_options_filter .=  "\n
			for(j=(adult_options.length-1); j>=0; j--){
				if(adult_options[j].value==2){
					adult_select.remove(j);
				}
			}
		\n";
	}
	if($price_ckeck_row['products_triple']<1){
		$adult_options_filter .=  "\n
			for(j=(adult_options.length-1); j>=0; j--){
				if(adult_options[j].value==3){
					adult_select.remove(j);
				}
			}
		\n";
	}
	if($price_ckeck_row['products_quadr']<1){
		$adult_options_filter .=  "\n
			for(j=(adult_options.length-1); j>=0; j--){
				if(adult_options[j].value==4){
					adult_select.remove(j);
				}
			}
		\n";
	}
	if($price_ckeck_row['products_kids']<1){
		$child_options_filter .=  "\n
			for(j=(child_options.length-1); j>=0; j--){
				child_select.remove(j);
			}
			child_options[0] = new Option(0,0);
		\n";
	}
}
/* 删除没有价格的房间选项 end */
?>

var maxPerRoomPeopleNum = <?=$maxPerRoomPeopleNum?>;	/* 该产品的每个房间最大人数 */
/*  去掉成人数和儿童数的多余的选项 */
function sub_rooms_people_num(){
	var cart_quantity = document.getElementById("cart_quantity");
	if(cart_quantity==null){
		return false;
	}

	var numberOfRooms = cart_quantity.elements['numberOfRooms'];
	if(numberOfRooms!=null){
		var room_num = cart_quantity.elements['numberOfRooms'].value;
		for(i=0; i<room_num; i++){
			var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
			var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
			/* 删除4以后的选项 成人 */
			var adult_options = adult_select.options;
			for(j=(adult_options.length-1); j>=maxPerRoomPeopleNum; j--){
				/* alert(adult_options.length); */
				adult_select.remove(j);
				/* adult_options.selectedIndex = (adult_options.length-1); */
			}

			/* 重新整理儿童的选项 */
			var child_options = child_select.options;
			for(j=(child_options.length-1); j>=maxPerRoomPeopleNum; j--){
				child_select.remove(j);
			}

			<?php
			echo $adult_options_filter;
			echo $child_options_filter;
			?>

			if(room_num==16){
				/* 如果是结伴拼房则还要把1人的选项去掉 */
				if(adult_options[0].value=="1"){
					adult_select.remove(0);
				}
				break;
			}
		}
	}
}

/* 根据当前房间人数判断是否显示房型选择框的king大床等选项 */
function set_bed_option(){
	var cart_quantity = document.getElementById("cart_quantity");
	if(cart_quantity==null){
		return false;
	}
	var numberOfRooms = cart_quantity.elements['numberOfRooms'];
	if(numberOfRooms!==undefined){
		var room_num = numberOfRooms.value;
		for(i=0; i<room_num; i++){
			var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
			var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
			var the_room_per_num = (Number(adult_select.value) + Number(child_select.value));
			/* 成人数+儿童数如果==2就显示大床选项，否则只保留标准型 */
			var bed_select = cart_quantity.elements['room-'+ i +'-bed'];
			if(bed_select===undefined){

			}else{
				var bed_selects = bed_select.options;
				var bed_value = bed_select.value;
				for(j=(bed_selects.length-1); j>=0; j--){
					bed_select.remove(j);
				}

				var options_array = new Array();
				options_array[0] = new Array(0,'<?php echo TEXT_BED_STANDARD;?>');
				options_array[1] = new Array(1,'<?php echo TEXT_BED_KING;?>');
				options_array[2] = new Array(2,'<?php echo TEXT_BED_QUEEN;?>');
				if(the_room_per_num==2 && room_num<16){
					for(n=0; n<options_array.length; n++){
						bed_select[n] = new Option(options_array[n][1], options_array[n][0]);
						if(bed_value==options_array[n][0]){
							bed_select.value = options_array[n][0];
						}
					}
				}else{
					bed_select[0] = new Option(options_array[0][1], options_array[0][0]);
				}
			}

			if(room_num==16){ break;}
		}
	}

}


/* 根据选择的成人数处理儿童选项是否需要减少选项 */
function set_child_option(){
	var cart_quantity = document.getElementById("cart_quantity");
	if(cart_quantity==null){
		return false;
	}

	var numberOfRooms = cart_quantity.elements['numberOfRooms'];
	if(numberOfRooms!=null){
		var room_num = cart_quantity.elements['numberOfRooms'].value;
		for(i=0; i<room_num; i++){
			var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
			var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
			/* 成人数+儿童数 <=4 */

			/* alert(adult_select.value); */
			/* 重新整理儿童的选项(先清空选项再添加选项) */
			var child_options = child_select.options;
			var child_value = child_select.value;
			for(j=(child_options.length-1); j>=0; j--){
				child_select.remove(j);
			}
			for(n=0; n<(maxPerRoomPeopleNum-adult_select.value)+1; n++){
				child_options[n] = new Option(n, n);
				if(child_value==n){
					child_select.value = n;
				}
			}
			<?php
			echo $child_options_filter;
			?>

			if(room_num==16){
				break;
			}
		}
	}
}

function setChildAge(room, child, age) {
	if (childAgesPerRoom[room] == null) {
		childAgesPerRoom[room] = new Array();
	}
	childAgesPerRoom[room][child] = age;
}

function setNumAdults(room, numAdults) {
	adultsPerRoom[room] = numAdults;
	set_child_option();
	set_bed_option();
}

function setNumChildren(room, numChildren) {
	childrenPerRoom[room] = numChildren;
	set_bed_option();
	/* refresh(); */
}

function setNumRooms(x) {
	numRooms = x;
	for (i = 0; i < x; i++) {
		if (adultsPerRoom[i] == null) {
			adultsPerRoom[i] = 2;
		}
		if (childrenPerRoom[i] == null) {
			childrenPerRoom[i] = 0;
		}
	}
	refresh();
	set_bed_option();
}

function renderRoomSelect() {
	var x = '';
	x += '<select class="sel2" style="width:70px;" name="numberOfRooms" onchange="setNumRooms(this.options[this.selectedIndex].value);">'; /*  id="numberOfRooms" */
	for (var i = 1; i < 17; i++) {
		if(i==16){
			<?php
			/* 结伴同游选项 */
			if(TRAVEL_COMPANION_OFF_ON=='true'){
			?>
			x += '<option value="'+i+'"'+(numRooms == i ? ' selected' : '')+'>' + '<?php echo SHARE_ROOM_WITH_TRAVEL_COMPANION?>';
			<?php }?>
		}else{
			x += '<option value="'+i+'"'+(numRooms == i ? ' selected' : '')+'>' + i;
		}
	}
	x += '</select>';
	/* alert(1); */
	return x;
}



function buildSelect(name, onchange, min, max, selected) {
	var x = '<select class="sel2" name="' + name + '"';
	if (onchange != null) {
		x += ' onchange="' + onchange + '"';
	}
	x +='>\n';
	for (var i = min; i <= max; i++) {
		x += '<option value="' + i + '"';
		if (i == selected) {
			x += ' selected';
		}

		x += '>' + i + '\n';
	}
	x += '</select>';
	return x;
}

function buildStrSelect(name, onchange, option_array, max_n, selected) {
	var option_array = option_array;
	var x = '<select class="sel2" name="' + name + '"';
	if (onchange != null) {
		x += ' onchange="' + onchange + '"';
	}
	x +='>\n';


	for (var i = 0; i < max_n; i++) {
		x += '<option value="' + option_array[i][0] + '"';
		if (option_array[i][0] == selected) {
			x += ' selected';
		}

		x += '>' + option_array[i][1] + '\n';
	}

	x += '</select>';
	return x;
}

function validateGuests(form) {
	if (numRooms < 18) {
		var missingAge = false;
		for (var i = 0; i < numRooms; i++) {
			var numChildren = childrenPerRoom[i];
			if (numChildren != null && numChildren > 0) {
				for (var j = 0; j < numChildren; j++) {
					if (childAgesPerRoom[i] == null || childAgesPerRoom[i][j] == null || childAgesPerRoom[i][j] == -1) {
						missingAge = true;
					}
				}
			}
		}

		if (missingAge) {
			alert(textChildError);
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}

function submitGuestInfoForm(form) {
	if (!validateGuests(form)) {
		return false;
	}

	return true;
}

function getValue(str, val) {
	return str.replace(/\?/g, val);
}



var initial_count = new Array();
var rows_limit = 0; /*  Set to 0 to disable limitation */

function addRow(table_id,text_value,textarea_value,photo_text,language)
{
  var tbl = document.getElementById(table_id);
  if(tbl==null){
  	alert('You need reload the page.');
	location.reload();
	/* location.href = location.href; */
	/* alert('no '+table_id); */
	return false;
  }
  /*  counting rows in table */
  var rows_count = tbl.rows.length;
  if (initial_count[table_id] == undefined)
  {
    /*  if it is first adding in this table setting initial rows count */
    initial_count[table_id] = rows_count;
  }
  /*  determining real count of added fields */
  if(language=='schinese'){
		var onBlur = 'onBlur="this.value = simplized(this.value)"';
	}else{
		var onBlur = 'onBlur="this.value = traditionalized(this.value)"';
	}

  var tFielsNum =  (rows_count - initial_count[table_id])+2;
  if (rows_limit!=0 && tFielsNum >= rows_limit) return false;
  var text = photo_text+' '+tFielsNum+':';
  var count = rows_count+1;
  var input = '<br><input type="file" name="image_file[]" id="image_file'+count+'" onchange="check_extension(this.value,this.id);"/><br /><br style="line-height:5px;" /><input type="text" '+onBlur+' name="image_title[]" value="'+text_value+'" id="image_title'+count+'" onfocus="value_null(this.id,\''+text_value+'\');" style="width:300px;"/><br /><br style="line-height:5px;" /><textarea name="image_description[]" '+onBlur+' id="image_description'+count+'" style="width:300px;"  onfocus="value_null(this.id,\''+textarea_value+'\');">'+textarea_value+'</textarea>';

  /* alert(input); */
  document.getElementById('total_imgs').value=rows_count+1;
  /* var remove= '<input type="button" value="X" onclick="removeRow(\''+table_id+'\',this.parentNode.parentNode)" style="width:100%;"/>'; */
   var remove ='';
  try {
    var newRow = tbl.insertRow(rows_count);
    var newCell = newRow.insertCell(0);
    newCell.innerHTML = text;
    var newCell = newRow.insertCell(1);
    newCell.innerHTML = input;
    /*var newCell = newRow.insertCell(2);
    newCell.innerHTML = remove; */
  } catch (ex) {
    /* if exception occurs */
    alert(ex);
  }
}

function removeRow(tbl,row)
{
  var table = document.getElementById(tbl);
  try {
    table.deleteRow(row.rowIndex);
  } catch (ex) {
    alert(ex);
  }

}
function value_null(val,originaltext)
{
	if(document.getElementById(val).value==originaltext || document.getElementById(val).value==originaltext)
	{
		document.getElementById(val).value='';
	}
}

function check_extension(val,id,error)
{
var filename = val;
	filename.lastIndexOf(".");
	var front_ext =  filename.substring(filename.lastIndexOf(".")+1,filename.length);
	front_ext  = front_ext.toLowerCase() ;

	if(front_ext == "jpeg" || front_ext == "jpg" || front_ext == "png" || front_ext == "gif" || front_ext == "bmp")
	{
		return true;
	}
	else
	{
		alert(error);
		document.getElementById(id).value= "";
		return false;
	}
}
function upload_submit()
{
	/* alert('here'); */
	document.frmupload.submit();

}
function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function createRequestObjectEndDateAjax(){
		var request_;
		var browser = navigator.appName;
		if(browser == "Microsoft Internet Explorer"){
		 request_ = new ActiveXObject("Microsoft.XMLHTTP");
		}else{
		 request_ = new XMLHttpRequest();
		}
		return request_;
}

var httpobjajaxenddate = createRequestObjectEndDateAjax();

function search_tour_end_date_ajax(nodays,changedate){


			date_final_split_array = changedate.split("::");
			if(date_final_split_array[0] == ''){
				document.getElementById("div_display_departure_end_date").style.display="none";
				return true;
			}else{
				httpobjajaxenddate.open('get', 'product_info.php?action=calculate&addnofodays='+nodays+'&selecteddate='+date_final_split_array[0]+'');
				httpobjajaxenddate.onreadystatechange = hendleInfo_search_tour_end_date_ajax;
				httpobjajaxenddate.send(null);
			}


}

function hendleInfo_search_tour_end_date_ajax(){

	if(httpobjajaxenddate.readyState == 4)
	{
	 var response_enddate = httpobjajaxenddate.responseText;
		 if(response_enddate != ''){
			document.getElementById("final_dep_date_div").innerHTML="<b>"+response_enddate+"</b>";
			document.getElementById("div_display_departure_end_date").style.display="";
		}else{
			document.getElementById("div_display_departure_end_date").style.display="none";
		}

	}
}

/* today added */
function scrollFunLeft()
{
i++;
if(box.scrollLeft<386)
{
box.scrollLeft+=i;
ts.className="";
}
else
{
clearInterval(t);
i=0;
ts.className="alert";
}
}

function scrollFunRight()
{
i++;
if(box.scrollLeft>0)
{
box.scrollLeft-=i;
ts.className="";
}
else
{
clearInterval(t);
i=0;
ts.className="alert";
}
}
 /* today added */

function closeDivPopSubmit(){
closePopup('popDiv');

var validPopup = new Validation('cart_quantity', {immediate : true,useTitles:true, onFormValidate : formCallback});
var result_check_pop = validPopup.validate();
	if(result_check_pop == true){
		validate();
		document.cart_quantity.submit()
	}
}

/* js 方式提交Booking 表单 */
function SubmitCartQuantityFrom(){
	var Valid = new Validation('cart_quantity', {immediate : true,useTitles:true, onFormValidate : formCallback});
	var Result = Valid.validate();
	if(Result == true){
		validate();
		document.cart_quantity.submit()
	}
}

/* Start - Javascript string pad */
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
		} /*  switch */
	}
	return str;
}
/* End - Javascript string pad */

/* 回访记录的显示详细内容功能 */
function show_full_reviews(reviews_id){
	var reviews_sub = document.getElementById('reviews_sub_'+ reviews_id);
	var reviews_all = document.getElementById('reviews_all_'+ reviews_id);
	if(reviews_sub!=null){
		if(reviews_sub.style.display == ""){
			reviews_sub.style.display = "none";
		}else{
			reviews_sub.style.display = "";
		}
	}

	if(reviews_all!=null){
		if(reviews_all.style.display == "none"){
			reviews_all.style.display = "";
		}else{
			reviews_all.style.display = "none";
		}
	}
}


/* 检查用户预定人数给予提示信息 */

function check_remaining_seats(){
	
    
	var old_time = new Date().getTime();
	
	var form_obj = document.getElementById("cart_quantity");
    var msg_check_submit = document.getElementById("check_remaining_seats_td");

	var room_num = form_obj.elements["numberOfRooms"];
	if(typeof(room_num)=="undefined"){
		return false;
	}
	loop_num = room_num.value;
	if(room_num.value==16){	/* 结伴同游处理 */
		loop_num = 1;
	}
	var total_adult =0;
	for(var i=0;i<loop_num;i++){
		total_adult=total_adult+Number(form_obj.elements['room-' + i + '-adult-total'].value);

	}
	for(var j=0;j<loop_num;j++){
		total_adult=total_adult+Number(form_obj.elements['room-' + j + '-child-total'].value);

	}
	var products_id = form_obj.elements["products_id"].value;
	var departure_time = document.getElementById("availabletourdate").value;
	departure_time=departure_time.split("::");
	var departure_date=departure_time[0];
	if(departure_date!=''){
		var CheckRemainingSeatsBuy = document.getElementById("check_remaining_seats_buy");
		if(document.all){
			CheckRemainingSeatsBuy.onmousemove=Function('');
		}else{
			CheckRemainingSeatsBuy.setAttribute('onmousemove','');
		}
		var url ="check_neworder_remaining_seats.php?total_adult="+total_adult+"&products_id="+products_id+"&departure_date="+departure_date;
		ajax.open('GET',url,true);
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 4 && ajax.status == 200){
				var new_time = new Date().getTime();
				/* alert(new_time-old_time); */
				if(ajax.responseText.search(/book_now_out/)!=-1){
					var error_msn = "<?php echo db_to_html('该团剩余座位小于订购人数,请选其它日期的团');?>";
									document.getElementById("notice_remaining_seats_div").innerHTML ="<b>"+error_msn+"</b>";
									document.getElementById("div_display_notice_remaining_seats").style.display="";
								   
									msg_check_submit.innerHTML = ajax.responseText;
									if(document.all){
										document.getElementById("check_remaining_seats_cart").onclick=Function('');
									}else{
										document.getElementById("check_remaining_seats_cart").setAttribute('onclick','');
									}
								   
								   /*  document.getElementById("check_remaining_seats_cart_img").setAttribute('src','image/buttons/tchinese/shopping-cart-button-out.gif'); */
	
							}else{
	
									document.getElementById("div_display_notice_remaining_seats").style.display="none";
									msg_check_submit.innerHTML = ajax.responseText;
									
									if(document.all){
										document.getElementById("check_remaining_seats_cart").onclick=Function('AddToCart()');
									}else{
										document.getElementById("check_remaining_seats_cart").setAttribute('onclick','AddToCart()');
									}
									/* document.getElementById("check_remaining_seats_cart_img").setAttribute('src','image/buttons/tchinese/shopping-cart-button.gif'); */
				}
			}
	
		}
	ajax.send(null);
    }
}

/* 评论是否有用的投票 */
function SetAsGoodComment(reviews_id, good_or_bad){
	if(reviews_id<1 || (good_or_bad!="good" && good_or_bad!="bad") ){ return false;}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('product_reviews_tabs_ajax.php','ajax=true&action=SetAsGoodComment')) ?>&reviews_id="+reviews_id+"&good_or_bad="+good_or_bad);
	ajax_get_submit(url,"","","");
}

	/*产品选项选择器*/
	function SetShowSteps2(products_options_id){
		var From = document.getElementById("cart_quantity");
		if(From==null){ alert("cart_quantity no find.");}
		var box_html = "";
		if(typeof(From.elements["id[" +products_options_id+ "]"])!="undefined" && From.elements["id[" +products_options_id+ "]"].length){
			var _radios = From.elements["id[" +products_options_id+ "]"];
			for(var i=0; i< _radios.length; i++){
				if(_radios[i].type=="radio" && _radios[i].checked == true){
					var em_id = "#id_"+products_options_id+'__'+ _radios[i].value;
					box_html = jQuery(em_id).html();
					break;
				}
			}
		}else if(From.elements["id[" +products_options_id+ "]"].type=="select-one"){
			box_html = jQuery(From.elements["id[" +products_options_id+ "]"]).find("option:selected").text();
		}else if(From.elements["id[" +products_options_id+ "]"].type=="radio"){
			From.elements["id[" +products_options_id+ "]"].checked = true;
			var em_id = "#id_"+products_options_id+'__'+ From.elements["id[" +products_options_id+ "]"].value;
			box_html = jQuery(em_id).html();
		}
		
		if(box_html!=""){
			jQuery("#TextBox_ProductsOptions"+products_options_id).html(box_html);
		}
		jQuery("#ProductsOptions_"+products_options_id).hide();
		alert("#ConTitleA_"+products_options_id);
		jQuery("#ConTitleA_ProductsOptions"+products_options_id).html('<?php echo db_to_html("可修改");?>');
		
		auto_update_budget();
	}
	function SetShowDepartureLocationsBox(){
		var From = document.getElementById("cart_quantity");
		if(From==null){ alert("cart_quantity no find.");}
		if(typeof(From.elements["departurelocation"])!="undefined" && From.elements["departurelocation"].length){
			var _radios = From.elements["departurelocation"];
			box_html = "";
			for(var i=0; i< _radios.length; i++){
				if(_radios[i].type=="radio" && _radios[i].checked == true){
					var em_id = '#departurelocation_em_'+ (i+1);
					box_html = jQuery(em_id).html();
					break;
				}
			}
			
		}else if(From.elements["departurelocation"].type=="select-one"){
			box_html = jQuery(_radios).find("option:selected").text();
		}else if(From.elements["departurelocation"].type=="radio"){
			From.elements["departurelocation"].checked = true;
			var em_id = '#departurelocation_em_'+ 1;
			box_html = jQuery(em_id).html();
		}

		if(box_html!=""){
			jQuery("#TextBox_departurelocations").html(box_html);
		}
		jQuery("#departurelocations").hide();
		jQuery("#ConTitleA_departurelocations").html('<?php echo db_to_html("可修改");?>');
		auto_update_budget();
		
	}
	/*显示房间信息到ShowSteps3*/
	function SetShowSteps3(){
		var From = document.getElementById("cart_quantity");
		if(From==null){ alert("cart_quantity no find.");}
		var numberOfRoomsSelect = From.elements["numberOfRooms"];
		var loop_num = 1;
		if( typeof(numberOfRoomsSelect)!="undefined"  && numberOfRoomsSelect!=null){
			loop_num = numberOfRoomsSelect.value;
			if(numberOfRoomsSelect.value==16){
				loop_num = 1;
			}
		}
		var Steps3Html = '';
		for(var i=0; i<loop_num; i++){

			Steps3Html +='<dl onclick="SetPopBox(&quot;hot-search-params&quot;);">';
			Steps3Html +='<dt>'+ jQuery("#room-"+i+"-left-title").html() +'</dt>';
			Steps3Html +='<dd >';
			Steps3Html +='<span class="adult"><label><?= db_to_html("成人: ")?></label>'+ From.elements["room-" +i+ "-adult-total"].value +'<?= db_to_html("人")?></span>';
			Steps3Html +='</dd >';
			/* if(From.elements["room-" +i+ "-child-total"].value >0){ */
				Steps3Html +='<dd >';
				Steps3Html +='<span class="children"><label><?= db_to_html("小孩: ")?></label>'+ From.elements["room-" +i+ "-child-total"].value +'<?= db_to_html("人")?></span>';
				Steps3Html +='</dd >';
			/* } */
			if(typeof(From.elements["room-" +i+ "-bed"])!="undefined"){
				Steps3Html +='<dd >';
				Steps3Html +='<span><label><?= db_to_html("床型: ")?></label>'+ jQuery(From.elements["room-" +i+ "-bed"]).find("option:selected").text() +'</span>';
				Steps3Html +='</dd >';
			}
			/* <div class="price"><label>金额: </label><span>$150.00</span></div> */
			//Steps3Html +='</dd>';
			Steps3Html +='</dl>';
		}
		
		jQuery("#ShowSteps3").html(Steps3Html);
		jQuery("#ConTitleA_hot-search-params").html('<?php echo db_to_html("可修改");?>');
		jQuery("#hot-search-params").hide();
		auto_update_budget();
	}
	
	/* 自动更新预算价格信息 */
	function auto_update_budget(){
		
		jQuery("#price_ajax_response").fadeIn(400);
		return sendFormData('cart_quantity','<?php echo tep_href_link('budget_calculation_ajax.php', 'action_calculate_price=true&products_id=' . $products_id);?>','price_ajax_response','true');
	}
	
    function getValueChinese(str, val) {

<?php
for ($i = 1; $i <= 15; $i++) {
    if (defined('HEDING_TEXT_ROOM_NUMBER_' . $i)) {
        echo '
if(parseInt(val) == ' . $i . '){
str = "' . constant('HEDING_TEXT_ROOM_NUMBER_' . $i) . '";
}' . "\n";
    }
}
?>
        return str.replace(/\?/g, val);
    }

/* //改变产品选项弹出层的选项样式 */
function onclick_products_options(obj){
	/* jQuery(".choosePop tr").removeClass("trClick"); */
	jQuery(obj).parent().find("tr").removeClass("trClick");
	jQuery(obj).addClass("trClick");
}
function onclick_set_p_class(obj){
	jQuery(obj).parent().parent().parent().find("p").removeClass("pClick");
	jQuery(obj).addClass("pClick");
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>

<!--product_info.js end-->