<?php
//上车地址选择框的优化设置 start
//2010-09-01
/*
特别注意：上车地址有_1_H_hotel1，_1_H_hotel2，_1_H_hotel3三个下拉菜单
在09的优化后会根据情况隐藏_1_H_hotel1和_1_H_hotel3，但它们是存在的。
_1_H_hotel1：上车地点所在区域；
_1_H_hotel2：上车地址；
_1_H_hotel3：上车时间。

结合本文件的内容做以下调整：
如果_1_H_hotel1被隐藏，那么BusStopArea也跟着被隐藏

*/

$optimization_location_address_switch = true;
if($optimization_location_address_switch == true){
?>

<div id="optimization_location_address_box">
    <div class="conTitle" id="ConTitle_placePop"><h2>
    <?php 
    if ($product_info['products_type'] == 7) {
		echo db_to_html('演出时间/地点：');
	} else {
		echo db_to_html("乘车地点：");
    }
    ?>
    <a id="ConTitleA_placePop" href="javascript:; " ><?= db_to_html("可选择");?></a>
    <?php
    /*
	$sql = "select count(products_id) as rows from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = '".(int)$_GET['products_id']."'";
    $departure_num = tep_db_get_one($sql);
    $departure_num = intval($departure_num['rows']);
    if($departure_num<=20){
    ?>
    <span class="map"><a href="javascript:;" onclick="javascript:showGMapHelper('<?php echo tep_href_link('gmap.php','products_id='.$_GET['products_id']);?>')" ><?php echo db_to_html('地图上查找')?></a></span>
   
    <?php }*/?>
    </h2>
    <div id="Close_placePop" class="close timeClose" style="display: none;"><a href="javascript:void(0);"></a></div>
        <div id="placePop" class="choosePop placePop">		
          <div class="placePopCon">
            <div id="BusStopAreaDiv">
            </div>
            
            <div id="BusStopAddressDiv"></div>
            
            <div class="submit btnCenter">
              <a class="btn btnOrange" href="javascript:;" onclick="ConfirmBusAddress();"><button type="button"><?= db_to_html("确 定");?></button></a>
              <a class="btn btnGrey" href="javascript:void(0);"><button type="button"><?= db_to_html("取 消");?></button></a>
            </div>
          </div>
        </div>
    </div>
    <div id="TextBox_placePop" class="place" onclick="SetPopBox('placePop');">&nbsp;</div>
    <div class="tip" id="AllAddressBoxtTip" style="display:none"><label><?php #echo db_to_html("我们将按以上时间和地点提供接送服务，请准时到达。");?></label></div>
    
</div>
<iframe id="DivShim" src="javascript:;" scrolling="no"></iframe>  
      
<script type="text/javascript">
<!--
//隐藏旧的html代码同时使用新的html代码
jQuery("#old_shang_che_de_ji").hide();

if(jQuery("#old_shang_che_de_ji").parent().attr('class') == 'conTitle')jQuery("#old_shang_che_de_ji").parent().hide();
if(jQuery("#old_shang_che_de_ji").html()==null){
    jQuery("#optimization_location_address_box").remove();
}


//触发显示隐藏方法 
function DivSetVisible(state){ 
  if(state=='open'){
    jQuery(".choosePop").hide();	
    var DivRef = document.getElementById('placePop'); 
    var IfrRef = document.getElementById('DivShim'); 
    DivRef.style.display = "block"; 
    IfrRef.style.display = "block"; 
    IfrRef.style.width = DivRef.offsetWidth + "px"; 
    IfrRef.style.height = DivRef.offsetHeight + "px"; 
    IfrRef.style.top = getStyle(DivRef,"top"); 
    IfrRef.style.left = getStyle(DivRef,"left"); 
    IfrRef.style.zIndex = getStyle(DivRef,"zIndex")-1;
    jQuery(".conTitle").css({'z-index':'1'});
    jQuery(DivRef).parent().css({'z-index':'1000'});
    
    //jQuery(DivRef).css({'z-index':'1000'});
    
  }else{
    document.getElementById('placePop').style.display= 'none'; 
    document.getElementById('DivShim').style.display= 'none'; 
  }
}
//取样式表里的属性值 方法 
function getStyle(elem, name){
if (elem.style[name])return elem.style[name];
else if (elem.currentStyle)return elem.currentStyle[name]; 
else if (window.getComputedStyle) return document.defaultView.getComputedStyle(elem,null).getPropertyValue(name);else return null; 
}

/////////////////////////////////////////////////////////////////////////////// 优化 start
var LocationAddressTitle = document.getElementById("location_address_title");
var CartQuantity = document.getElementById("cart_quantity");
var Address_1_H_hotel1 = CartQuantity.elements['_1_H_hotel1'];
var Address_1_H_hotel2 = CartQuantity.elements['_1_H_hotel2'];
var Address_1_H_hotel3 = CartQuantity.elements['_1_H_hotel3'];
var BusStopAreaObj = document.getElementById("BusStopAreaDiv");
var BusStopAddressObj = document.getElementById("BusStopAddressDiv");
var max_address_rows = 10;	//每页显示10条地址

//选择BusStopAreaObj内的单选按钮，并触发Address_1_H_hotel1.onchange，最后取得Address_1_H_hotel2的列表到BusStopAddressDiv
function selected_radio_1(a_obj){
    var a_array = BusStopAreaObj.getElementsByTagName('a');
    for(i=0; i<a_array.length; i++){
        if(a_array[i].className=="selected"){
            a_array[i].className = "";
        }
    }
    a_obj.className = "selected";
    var s_radio = a_obj.getElementsByTagName('input');
    for(j=0; j<s_radio.length; j++){
        if(s_radio[j].type=="radio"){
            s_radio[j].checked = true;
            Address_1_H_hotel1.value = s_radio[j].value;
            if ((typeof Address_1_H_hotel1.onchange)=='function'){		//触发Address_1_H_hotel1.onchange
                Address_1_H_hotel1.onchange();
                write_bus_stop_address();
                var TmpInput = document.getElementById("Address_1_H_hotel1_Tmp_Input");
                if(TmpInput!=null){
                    TmpInput.innerHTML = Address_1_H_hotel1.options[Address_1_H_hotel1.selectedIndex].text;
                }
                var TdStopAddress = document.getElementById("td_stop_address");
                if(TdStopAddress!=null){
                    TdStopAddress.innerHTML = '&nbsp;'+Address_1_H_hotel1.options[Address_1_H_hotel1.selectedIndex].text+'<?= db_to_html("的上车时间和地点")?>';
                }
            }
        }
    }
    jQuery(a_obj).parent().parent().children().removeClass("trClick");
    jQuery(a_obj).parent().addClass("trClick");
}
//选择BusStopAddressObj内的单选按钮，并触发Address_1_H_hotel2.onchange
function selected_radio_2(a_obj){
    var a_array = BusStopAddressObj.getElementsByTagName('a');
    for(i=0; i<a_array.length; i++){
        if(a_array[i].className=="selected"){
            a_array[i].className = "";
        }        
    }    
    a_obj.className = "selected";    
    var s_radio = a_obj.getElementsByTagName('input');    
    for(j=0; j<s_radio.length; j++){
        if(s_radio[j].type=="radio"){            
            s_radio[j].checked = true;
            Address_1_H_hotel2.value = s_radio[j].value;            
            if ((typeof Address_1_H_hotel2.onchange)=='function'){		//触发Address_1_H_hotel2.onchange
				Address_1_H_hotel2.onchange();
            }
        }
    }
    
    var AllAddressBoxtHtml = "";
    
    if(jQuery("#Address_1_H_hotel1_Tmp_Input").html()!=null){
        AllAddressBoxtHtml += "["+ jQuery("#Address_1_H_hotel1_Tmp_Input").html()+ "] ";
    }
    
    AllAddressBoxtHtml += jQuery(a_obj).find('span.timeS em').html()+' '+jQuery(a_obj).find('span.placeS').html();
    var FullAddress = '';
    FullAddress += jQuery(a_obj).find('span.timeS em').html() +'::::'+jQuery(a_obj).find('span.placeS').html();
    jQuery("#departurelocation").val(FullAddress);    
    //AllAddressBoxtHtml += jQuery("#fulladdress").html();    
    jQuery("#TextBox_placePop").html(AllAddressBoxtHtml);	//最终要显示的那个框框
    jQuery("#AllAddressBoxtTip").show();
    jQuery(a_obj).parent().parent().children().removeClass("trClick");
    jQuery(a_obj).parent().addClass("trClick");
}

//取得Address_1_H_hotel2的选项值并写到BusStopAddressObj
function write_bus_stop_address(){
    var table = '<table id="BusStopAddressTable" cellspacing="0" cellpadding="0" style="margin: 0px; border-top: 0px none;">';
    //区域头部
    var pre_page_html = '<a id="ChooseTimePopPreAddress" style="display:none" title="<?= db_to_html('上一页')?>" class="pagePre" href="javascript:;" onclick="address_flip(-1)"><?= db_to_html('上一页')?></a>';
    var next_page_html = '<a id="ChooseTimePopNextAddress" style="display:none" title="<?= db_to_html('下一页')?>" class="pageNext" href="javascript:;" onclick="address_flip(1)"><?= db_to_html('下一页')?></a>';
    
    table += '<thead><tr><td id="td_stop_address">&nbsp;<?php
	if ($product_info['products_type'] == 7) {
		echo db_to_html('演出时间/地点：');
	} else {
		echo db_to_html("上车时间和地点：");
    }
	?></td></tr></thead>';
    //主体区域
    table += '<tbody>';
    table += '<tr class="placeListTitle"><td><span><?= db_to_html('时间')?></span><span><?= db_to_html('地点')?></span></td></tr>';
    var next_button_display = "none";
    var default_address = '';
    for(var i=0; i<Address_1_H_hotel2.options.length; i++){
        if(Address_1_H_hotel2.options[i].value.search(/^\d+$/)>-1){
            var time_address = Address_1_H_hotel2.options[i].text;            
            var time = time_address.replace(/ .+/,'');
            var style_display = "";
            if(i>max_address_rows){
                style_display = ' style="display:none" ';
                next_button_display = "";
            }
            
            table += '<tr class="placeList" '+style_display+' onmouseover="jQuery(this).addClass(&quot;trHover&quot;);" onmouseout="jQuery(this).removeClass(&quot;trHover&quot;);" ><td onDblClick="ConfirmBusAddress()" onclick="selected_radio_2(this)"><span class="timeS"><input name="Address_1_H_hotel2_radio" id="departurelocation_'+i+'" value="'+Address_1_H_hotel2.options[i].value+'" type="radio"><em>'+time+'</em></span><span class="placeS">'+time_address.substr(time.length)+'</span></td></tr>';            default_address = time + '::::' + time_address.substr(time.length);
           
        }
    }
    table += '<div style="display:none"><textarea name="departurelocation" id="departurelocation">'+ default_address  +'</textarea></div></tbody>';
    table += '</table>';    
    table += '<div>'+ pre_page_html + next_page_html +'</div>';
    BusStopAddressObj.innerHTML = table;
    BusStopAddressObj.style.display = "";
    //隐藏或显示翻页按钮
    var ChooseTimePopPreAddressObj = document.getElementById("ChooseTimePopPreAddress");
    var ChooseTimePopNextAddressObj = document.getElementById("ChooseTimePopNextAddress");
    ChooseTimePopNextAddressObj.style.display = next_button_display;
}

//上车时间和地点翻页
function address_flip(num){
    var BusStopAddressTableObj = document.getElementById("BusStopAddressTable");
    var ChooseTimePopPreAddressObj = document.getElementById("ChooseTimePopPreAddress");
    var ChooseTimePopNextAddressObj = document.getElementById("ChooseTimePopNextAddress");
    var i_tag = "";
    for(var i=0; i<BusStopAddressTableObj.rows.length; i++){
        //取得已显示的第一行作为翻页标记，并隐藏所有行
        if(BusStopAddressTableObj.rows[i].className=="placeList" && BusStopAddressTableObj.rows[i].style.display!="none"){
            if(i_tag==""){
                i_tag = i;
            }
            BusStopAddressTableObj.rows[i].style.display="none";
        }
    }
    
    var start_row = i_tag+max_address_rows;
    var end_row = Math.min((start_row+max_address_rows),BusStopAddressTableObj.rows.length);
    if(num>0){//下一页
        for(var i= start_row; i<end_row; i++){
            BusStopAddressTableObj.rows[i].style.display="";
            //隐藏下一页按钮
            if((start_row+max_address_rows)>=BusStopAddressTableObj.rows.length){
                ChooseTimePopNextAddressObj.style.display = "none";
            }
            //显示上一页按钮
            if(ChooseTimePopPreAddressObj.style.display == "none"){
                ChooseTimePopPreAddressObj.style.display = "";
            }
        }
    }else if(num<0){//上一页
        start_row = Math.max(2,(i_tag-1-max_address_rows));
        end_row = Math.min((start_row+max_address_rows),BusStopAddressTableObj.rows.length);
        for(var i= start_row; i<end_row; i++){
            BusStopAddressTableObj.rows[i].style.display="";
            //隐藏上一页按钮
            if(start_row<=2){
                ChooseTimePopPreAddressObj.style.display = "none";
            }
            //显示下一页按钮
            if(ChooseTimePopNextAddressObj.style.display == "none"){
                ChooseTimePopNextAddressObj.style.display = "";
            }
        }
    }
}

//确认上车地址
function ConfirmBusAddress(){
    //if(Address_1_H_hotel2.value.search(/^\d+$/)==-1){
    //    alert("<?= db_to_html('请选择上车时间和地点！');?>");
    //    return false;
    //}
    var PlacePop = document.getElementById("placePop");
    PlacePop.style.display="none";
    var Close_placePop = document.getElementById("Close_placePop");
    Close_placePop.style.display="none";
    if(document.getElementById("advice-validate-select-custom-pickup-_1_H_address")!=null){//清除旧的错误提示
        var tmp_obj = document.getElementById("advice-validate-select-custom-pickup-_1_H_address");
        tmp_obj.parentNode.removeChild(tmp_obj);
    }
    jQuery("#ConTitleA_placePop").html('<?php echo db_to_html('可修改');?>');
}
if(LocationAddressTitle!=null && CartQuantity!=null && typeof(Address_1_H_hotel1)=="object" && typeof(Address_1_H_hotel2)=="object" && typeof(Address_1_H_hotel3)=="object"){
    LocationAddressTitle.innerHTML = LocationAddressTitle.innerHTML + ' <a class="choosePlaceA" onclick="DivSetVisible(\'open\')" href="javascript:;"><?= db_to_html("点击选择");?></a>';
    /*初始化上车时间和地点 start*/
    BusStopAddressObj.innerHTML = "";
    /*初始化上车时间和地点 end*/

    /*初始化所在区域 start*/
    BusStopAreaObj.innerHTML = "";
    
    if(Address_1_H_hotel1.options.length<=2){
        BusStopAreaObj.style.display = "none";
        write_bus_stop_address();
    }else{
        var table = '<table cellspacing="0" cellpadding="0">';
        //区域头部
        table += '<thead><tr><td>&nbsp;<?= db_to_html('所在区域')?></td></tr></thead>';
        //主体区域
        table += '<tbody>';
        for(var i=1; i<Address_1_H_hotel1.options.length; i++){
            table += '<tr onmouseover="jQuery(this).addClass(&quot;trHover&quot;);" onmouseout="jQuery(this).removeClass(&quot;trHover&quot;);"><td onclick="selected_radio_1(this)">';
            table += '<span class="timeS"><input name="Address_1_H_hotel1_radio" value="'+Address_1_H_hotel1.options[i].value+'" type="radio"><em>'+Address_1_H_hotel1.options[i].text+'</em></span>';
            table += '</td></tr>';
        }
        /*if(Address_1_H_hotel1.options.length<=2){
            for(var i=(Address_1_H_hotel1.options.length-1); i<2; i++){
                table += '<td>&nbsp;</td>';
            }
        }*/
        table += '</tbody>';
        table += '</table>';
        BusStopAreaObj.innerHTML = table;
    }
    /*初始化所在区域 end*/
    
    /*隐藏原来的下拉菜单*/
    Address_1_H_hotel1.style.display="none";
    Address_1_H_hotel2.style.display="none";
    /*删除原下拉菜单所在单元格的回车符*/
    var ParentObj = Address_1_H_hotel3.parentNode;
    var br = ParentObj.getElementsByTagName("br");
    for(var i=0; i<br.length; i++){
        br[i].parentNode.removeChild(br[i]);
    }
    /*在后添加一个只读属性的输入框以显示区域*/
    if(Address_1_H_hotel1.options.length>2){
        var InputObj = document.createElement('div');
        InputObj.className = 'place';
        InputObj.setAttribute('id','Address_1_H_hotel1_Tmp_Input'); 
        if(document.all){
            InputObj.onclick=Function('DivSetVisible("open")');
        }else{
            InputObj.setAttribute('onclick','DivSetVisible("open")');
        }
        var e = ParentObj.insertBefore(InputObj, Address_1_H_hotel3);
    }

}


/////////////////////////////////////////////////////////////////////////////// 优化 end
//-->
</script>



<!--  
<div class="popup" id="popupMap">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con" style="float:none;margin:0;width:auto;">
  <div class="popupCon" id="popupConMap" style="width:325px;">
    <div class="popupConTop" id="dragMap">
      <h3 style=" padding-left:0px;"><b><?= db_to_html("上车地点选择助手");?></b></h3>
      <span onclick="closePopup('popupMap')"></span>
      <div onclick="minPopup(this)" class="popupMin">-</div>
    </div>
<iframe frameborder="0" src="" width="825" height="460" style="overflow:hidden; display:none;" id="gMapIframe"></iframe>
<div id="gMaptips" style="color:#999"><img src='ajaxtabs/loading.gif' align='absmiddle'><?= db_to_html("正在加载地图，请稍后...");?></div>
</div>
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>-->

</div>

<script type="text/javascript">
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
function showGMapHelper(gmapurl){
    if(gmapurl!=''){
        if(gmapurl != jQuery('#gMapIframe').attr('src')){
            //jQuery('#popupConMap').width(325);
            jQuery('#gMaptips').show();
            jQuery('#gMapIframe').hide();
            jQuery('#gMapIframe').attr('src',gmapurl);
        }
        showPopup('popupMap','popupConMap',true,0,0,'','',true);
    }
}
function minPopup(obj){
    obj = jQuery(obj);
    var t=obj.attr('isMin');
    if(t=='true'){
        obj.attr('isMin','false');
        obj.html('-');
        //jQuery('#popupConMap').width(825);
        jQuery('#gMaptips').hide();
        jQuery('#gMapIframe').show();
        showPopup('popupMap','popupConMap',true,0,0,'','',true);
    }else{
        obj.attr('isMin','true');
        obj.html('+');
        //jQuery('#popupConMap').width(325);
        jQuery('#gMaptips').hide();
        jQuery('#gMapIframe').hide();
        showPopup('popupMap','popupConMap',true,0,0,'','',true);
    }
}

jQuery(document).ready(function(){
    new divDrag([popup('dragMap'),popup('popupMap')]); 
});
</script>

<?php
}
//上车地址选择框的优化设置 end
?>