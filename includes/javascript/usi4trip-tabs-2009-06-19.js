

$=function(id){
    return document.getElementById(id);
};

var oldSelectedTabId = null;

initTab=function(t){
    var tab = $(t);
    if (!tab)return;
    var hs = tab.getElementsByTagName('a');
    var l = hs.length;
    for (var i = 0;i < l ;i++ ){
        var a = hs[i];
        a.onclick=function(ev){
            this.blur();
            toggleTab(this);
        }
        if (hasClass(a.parentNode,"s")){
            toggleTab(a);
        }else{
            addClass(getTabObj(a.id,"content"),"hidden");
            a.href="javascript:void(0)";
        }
    }
    if (!oldSelectedTabId) {
        toggleTab(hs[0]);
    }
};


toggleTab = function(a){
    if (!a)return;
    if (oldSelectedTabId&&oldSelectedTabId==a.id)return;
    else if (oldSelectedTabId){
        removeClass(getTabObj(oldSelectedTabId,"href").parentNode,"s");
        addClass(getTabObj(oldSelectedTabId,"content"),"hidden");
    }
    addClass(a.parentNode, "s");
    oldSelectedTabId = a.id;
    removeClass(getTabObj(a.id,"content"),"hidden");
};


getTabObj=function(id,type){
    var TYPE = {
        content:"c_",
        href:"h_"
    };
    var r = /(c_|h_)/g;
    return $(id.replace(r,TYPE[type]));
};

hasClass = function(obj,className){
    if (!obj||!obj.className)return false;
    return new RegExp("\\b"+className+"\\b","g").test(obj.className);
};


addClass = function(obj,className){
    if (!obj)return false;
    obj.className = obj.className + " " + className+" ";
};


removeClass = function(obj,className){
    if (!obj||!obj.className)return false;
    obj.className = obj.className.replace(new RegExp("\\b"+className+"\\b","g"),"").replace(/^\s*|\s$/g,"");
};

var curObj=null;
function document_onclick() {
    if(window.event.srcElement.tagName=='A'||window.event.srcElement.tagName=='TD'){
        if(curObj!=null)
            curObj.style.background='';
        curObj=window.event.srcElement;
        curObj.style.background='#223C6A';
    }
}


var cur_index=1;
var num=4;
var settime;
function GetObj(objName){
    if(document.getElementById){
        return eval('document.getElementById("' + objName + '")');
    }else if(document.layers){
        return eval("document.layers['" + objName +"']");
    }else{
        return eval('document.all.' + objName);
    }
}
function change_Menu(index){
    for(var i=1;i<=num;i++){/* 最多支持8个标签 */
        if(GetObj("con"+i)&&GetObj("m"+i)){
            GetObj("con"+i).style.display = 'none';
            GetObj("m"+i).className = "menu"+i+"Off";
        }
    }
    if(GetObj("con"+index)&&GetObj("m"+index)){
        GetObj("con"+index).style.display = 'block';
        GetObj("m"+index).className = "menu"+index+"On";
    }
    cur_index=index;
    if(cur_index<num){
        cur_index++;
    } else {
        cur_index=1;
    }

}
function Menu(c_index){
    clearTimeout(settime);
    change_Menu(c_index);
}



function showdh(n){
    for(var i=1;i<=12;i++){
        var un_obj=document.getElementById("dh"+i);
        if(un_obj!=null){
            un_obj.className="";
        }
    }
    var hd = document.getElementById("dh"+n);
    hd.className="sel";
}


function g(o){
    return document.getElementById(o);
}
if (document.attachEvent){
    addEvent = function(o,evn,f) {
        o.attachEvent("on"+evn,f);
    };
} else if (document.addEventListener) {
    addEvent = function(o,evn,f) {
        o.addEventListener(evn,f,false);
    };
}

function initTab1(nid,cid,action,defaultIndex){
    var ls = g(nid).getElementsByTagName('li');
    var cc = g(cid).childNodes;
    var c = [];
    var index = defaultIndex?defaultIndex:0;
    for (var i = 0 ; i < cc.length ; i ++)if(cc[i].nodeType==1)c.push(cc[i]);
    if (ls.length!=c.length)
        throw({
            description:'菜单和内容数量不对应'
        });
    for (var i = 0 ; i < ls.length ; i ++){
        ls[i].index = i;
        if (i==index){
            ls[i].className = 'hovertab';
            c[i].className = 'dis1';
            ls[i].parentNode.last = ls[i];
        }
        addEvent(ls[i],action,function(e){
            var self = window.event?window.event.srcElement:e?e.target:null;
            if (self.parentNode.last){
                self.parentNode.last.className = 'normaltab';
                c[self.parentNode.last.index].className = 'undis1';
            };
            self.className = 'hovertab';
            c[self.index].className = 'dis1';
            self.parentNode.last = self;
        });
    }
}

function show_all(type)
{
    var tmp_arr = document.getElementsByTagName("tr");
    for(var i = 0; i < tmp_arr.length; i++)
    {
        if(tmp_arr[i].className == type)
        {
            tmp_arr[i].style.display = "";
        }
    }
}
//Function to collepse all divs
function hide_all(type)
{
    var tmp_arr = document.getElementsByTagName("tr");
    for(var i = 0; i < tmp_arr.length; i++)
    {
        if(tmp_arr[i].className == type)
        {
            tmp_arr[i].style.display = "none";
        }
    }
}
//Function to toggle a div
function toggel_div(divid)
{
    if(eval("document.getElementById('" +  divid + "').style.display") == '')
        eval("document.getElementById('" +  divid + "').style.display = 'none'");
    else
        eval("document.getElementById('" +  divid + "').style.display = ''");
}

function toggel_div_show(divid)
{
    if(eval("document.getElementById('" +  divid + "').style.display") == 'none'){
        eval("document.getElementById('" +  divid + "').style.display = ''");
    }

}

function toggel_div_hide(divid)
{
	if(eval("document.getElementById('" +  divid + "').style.display") != 'none'){
		eval("document.getElementById('" +  divid + "').style.display = 'none'");
	}
}
/* ajax form js file code start */
var XMLHttpRequestObject = createXMLHttpRequestObject();
function createXMLHttpRequestObject()
{
    var XMLHttpRequestObject = false;

    try
    {
        XMLHttpRequestObject = new XMLHttpRequest();
    }
    catch(e)
    {
        var aryXmlHttp = new Array(
            "MSXML2.XMLHTTP",
            "Microsoft.XMLHTTP",
            "MSXML2.XMLHTTP.6.0",
            "MSXML2.XMLHTTP.5.0",
            "MSXML2.XMLHTTP.4.0",
            "MSXML2.XMLHTTP.3.0"
            );
        for (var i=0; i<aryXmlHttp.length && !XMLHttpRequestObject; i++)
        {
            try
            {
                XMLHttpRequestObject = new ActiveXObject(aryXmlHttp[i]);
            } 
            catch(e){
                document.write("createXMLHttpRequestObject: XMLHttpRequestObject Error");
            }
        }
    }

    if (!XMLHttpRequestObject)
    {
        alert("Error: failed to create the XMLHttpRequest object.");
    }
    else 
    {
        return XMLHttpRequestObject;
    }
}

function checkFormInput(keyEvent, dataSource, idForm)
{
    keyEvent = (keyEvent) ? keyEvent: window.event;
    input = (keyEvent.target) ? keyEvent.target : keyEvent.srcElement;
    IdForm = document.getElementById(idForm);
    if(keyEvent.type == "checkbox")
    {
        keyEvent.value = keyEvent.checked;
    }
    else if(keyEvent.type == "radio")
    {
        keyEvent.value = keyEvent.checked;
        if (keyEvent.value)
        {
            if(IdForm!=null){
                for(i=0; i<IdForm.elements.length - 1; i++)
                {

                    if(IdForm.elements[i].name==keyEvent.name)
                    {

                        IdForm.elements[i].value = IdForm.elements[i].checked;
                    }
                }
            }
        } 
    } 
}

// Removes leading whitespaces
function LTrim( value ) {

    var re = /\s*((\S+\s*)*)/;
    return value.replace(re, "$1");

}

// Removes ending whitespaces
function RTrim( value ) {

    var re = /((\s*\S+)*)\s*/;
    return value.replace(re, "$1");

}

// Removes leading and ending whitespaces
function trim( value ) {

    return LTrim(RTrim(value));

}

function clearForm(formIdent) 
{ 
    var form, elements, i, elm; 
    form = document.getElementById 
    ? document.getElementById(formIdent) 
    : document.forms[formIdent]; 

    if (document.getElementsByTagName)
    {
        elements = form.getElementsByTagName('input');
        for( i=0, elm; elm=elements.item(i++); )
        {
            if (elm.getAttribute('type') == "text")
            {
                elm.value = '';
            }			
        }


        elements = form.getElementsByTagName('textarea');
        for( i=0, elm; elm=elements.item(i++); )
        {
            elm.innerHTML = '';
        }

    }

    // Actually looking through more elements here
    // but the result is the same.
    else
    {
        elements = form.elements;
        for( i=0, elm; elm=elements[i++]; )
        {
            if (elm.type == "text")
            {
                elm.value ='';
            }
        }
    }
}


function setOpacity(id, level) {            
    var element = document.getElementById(id); 
    element.style.display = 'inline';           
    element.style.zoom = 1;
    element.style.opacity = level;
    element.style.MozOpacity = level;
    element.style.KhtmlOpacity = level;
    element.style.filter = "alpha(opacity=" + (level * 100) + ");";
}

function fadeIn(id, steps, duration, interval, fadeOutSteps, fadeOutDuration){  
    var fadeInComplete;      
    for (i = 0; i <= 1; i += (1 / steps)) {
        setTimeout("setOpacity('" + id + "', " + i + ")", i * duration); 
        fadeInComplete = i * duration;             
    }

    setTimeout("fadeOut('" + id + "', " + fadeOutSteps + ", " + fadeOutDuration + ")", fadeInComplete + interval);           
}

function fadeOut(id, steps, duration) {         
    var fadeOutComplete;       
    for (i = 0; i <= 1; i += (1 / steps)) {
        setTimeout("setOpacity('" + id + "', "  + (1 - i) + ")", i * duration);
        fadeOutComplete = i * duration;
    }      

    setTimeout("fadeHide('" + id + "')", fadeOutComplete);     
}   

function fadeHide(id){
    document.getElementById(id).style.display = 'none';     
}


function adv_search_seo_url(fname,defaultUrl){


    var qstring = defaultUrl ;
    var argList = "";


    // Gather form elements
    for (i = 0; i < document.forms[fname].elements.length; i++) {

        if ((null != document.forms[fname]) &&
            (null != document.forms[fname].elements[i]) &&
            ("" != document.forms[fname].elements[i].name) &&
            ("" != document.forms[fname].elements[i].value) ) {
            // argList = argList + '&' + document.forms[fname].elements[i].name + '=' +
            argList = argList + '/' + document.forms[fname].elements[i].name + '/' +
            document.forms[fname].elements[i].value.replace(/\//g,"slash");
        }
    }


    // fix date
    if (argList.match(/\//g)) {
    //argList = argList.replace(/\//g,"%2F");
    }


    qstring = qstring + argList;

    // Looks for "?&" within qstring and if found, replaces with "?"
    if (qstring.match(/\?\&/g)) {
        qstring = qstring.replace(/\?\&/g,"?");
        qstring = qstring.replace(/\?\&/g,"?");
    }

    //alert(qstring);
    // Submit request
    //openWindow(qstring);
    window.location = qstring;


    return false;
}
/* ajax form js file code end */
/* display map tooltip */
function showToolTip(e,text){
    if(document.all)e = event;

    var obj = document.getElementById('bubble_tooltip');
    var obj2 = document.getElementById('bubble_tooltip_content');
    obj2.innerHTML = text;
    obj.style.display = 'block';
    var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
    if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0; 
    var leftPos = e.clientX - 100;
    if(leftPos<0)leftPos = 0;
    obj.style.left = leftPos + 'px';
    obj.style.top = e.clientY - obj.offsetHeight -1 + st + 'px';
}	

function hideToolTip()
{
    document.getElementById('bubble_tooltip').style.display = 'none';

}
/* display map tooltip  end*/
function Depature_JumpRedictUrl(selObj){	
    window.location = selObj.options[selObj.selectedIndex].value;
}

/* history and ajax back button start */

if (!window.unFocus) var unFocus = {};

unFocus.EventManager = function() {
    this._listeners = {};
    for (var i = 0; i < arguments.length; i++) {
        this._listeners[arguments[i]] = [];
    }
};

unFocus.EventManager.prototype = {
    addEventListener: function($name, $listener) {
        // check that listener is not in list
        for (var i = 0; i < this._listeners[$name].length; i++)
            if (this._listeners[$name][i] == $listener) return;
        // add listener to appropriate list
        this._listeners[$name].push($listener);
    },
    removeEventListener: function($name, $listener) {
        // search for the listener method
        for (var i = 0; i < this._listeners[$name].length; i++) {
            if (this._listeners[$name][i] == $listener) {
                this._listeners.splice(i,1);
                return;
            }
        }
    },
    notifyListeners: function($name, $data) {
        for (var i = 0; i < this._listeners[$name].length; i++)
            this._listeners[$name][i]($data);
    }
};


unFocus.History = (function() {

    function Keeper() {
        var _this = this,
        _pollInterval = 200, _intervalID,
        _currentHash;

        var _getHash = function() {
            return location.hash.substring(1);
        };
        _currentHash = _getHash();


        var _setHash = function($newHash) {
            window.location.hash = $newHash;
        };


        function _watchHash() {
            var $newHash = _getHash();
            if (_currentHash != $newHash) {
                _currentHash = $newHash;
                _this.notifyListeners("historyChange", $newHash);
            }
        }
        // set the interval
        //if (setInterval) _intervalID = setInterval(_watchHash, _pollInterval);

        function _createAnchor($newHash) {
            if (!_checkAnchorExists($newHash)) {
                var $anchor;
                if (/MSIE/.test(navigator.userAgent) && !window.opera)
                    $anchor = document.createElement('<a name="'+$newHash+'">'+$newHash+"</a>");
                else
                    $anchor = document.createElement("a");
                $anchor.setAttribute("name", $newHash);
                with ($anchor.style) {
                    position = "absolute";
                    display = "block";
                    top = getScrollY()+"px";
                    left = getScrollX()+"px";
                    }
                document.body.insertBefore($anchor,document.body.firstChild);
            }
        }
        function _checkAnchorExists($name) {
            if (document.getElementsByName($name).length > 0)
                return true;
        }
        if (typeof self.pageYOffset == "number") {
            function getScrollY() {
                return self.pageYOffset;
            }
        } else if (document.documentElement && document.documentElement.scrollTop) {
            function getScrollY() {
                return document.documentElement.scrollTop;
            }
        } else if (document.body) {
            function getScrollY() {
                return document.body.scrollTop;
            }
        }
        eval(String(getScrollY).toString().replace(/Top/g,"Left").replace(/Y/g,"X"));

        _this.getCurrent = function() {
            return _currentHash;
        };


        function addHistory($newHash) {
            if (_currentHash != $newHash) {
                _createAnchor($newHash);
                _currentHash = $newHash;
                _setHash($newHash);
                _this.notifyListeners("historyChange",$newHash);
            }
            return true;
        }
        _this.addHistory = function($newHash) { // adds history and bookmark hash
            _createAnchor(_currentHash);
            // replace with slimmer versions...
            _this.addHistory = addHistory;
            // ...do first call
            return _this.addHistory($newHash);
        };


        if (/WebKit\/\d+/.test(navigator.appVersion) && navigator.appVersion.match(/WebKit\/(\d+)/)[1] < 420) {
            var _unFocusHistoryLength = history.length,
            _historyStates = {}, _form,
            _recentlyAdded = false;

            function _createSafariSetHashForm() {
                _form = document.createElement("form");
                _form.id = "unFocusHistoryForm";
                _form.method = "get";
                document.body.insertBefore(_form,document.body.firstChild);
            }

            _setHash = function($newHash) {
                _historyStates[_unFocusHistoryLength] = $newHash;
                _form.action = "#" + _getHash();
                _form.submit();
            };

            _getHash = function() {
                return _historyStates[_unFocusHistoryLength];
            };

            _historyStates[_unFocusHistoryLength] = _currentHash;

            function addHistorySafari($newHash) {
                if (_currentHash != $newHash) {
                    _createAnchor($newHash);
                    _currentHash = $newHash;
                    _unFocusHistoryLength = history.length+1;
                    _recentlyAdded = true;
                    _setHash($newHash);
                    _this.notifyListeners("historyChange",$newHash);
                    _recentlyAdded = false;
                }
                return true;
            }

            _this.addHistory = function($newHash) { // adds history and bookmark hash
                _createAnchor(_currentHash);
                _createSafariSetHashForm();

                _this.addHistory = addHistorySafari;

                return _this.addHistory($newHash);
            };
            function _watchHistoryLength() {
                if (!_recentlyAdded) {
                    var _historyLength = history.length;
                    if (_historyLength != _unFocusHistoryLength) {
                        _unFocusHistoryLength = _historyLength;

                        var $newHash = _getHash();
                        if (_currentHash != $newHash) {
                            _currentHash = $newHash;
                            _this.notifyListeners("historyChange", $newHash);
                        }
                    }
                }
            };

        //clearInterval(_intervalID);
        //_intervalID = setInterval(_watchHistoryLength, _pollInterval);

        } else if (typeof ActiveXObject != "undefined" && window.print && 
            !window.opera && navigator.userAgent.match(/MSIE (\d\.\d)/)[1] >= 5.5) {
            var _historyFrameObj, _historyFrameRef;


            function _createHistoryFrame() {
                var $historyFrameName = "unFocusHistoryFrame";
                _historyFrameObj = document.createElement("iframe");
                _historyFrameObj.setAttribute("name", $historyFrameName);
                _historyFrameObj.setAttribute("id", $historyFrameName);
                _historyFrameObj.setAttribute("src", 'javascript:;');
                _historyFrameObj.style.position = "absolute";
                _historyFrameObj.style.top = "-900px";
                document.body.insertBefore(_historyFrameObj,document.body.firstChild);

                _historyFrameRef = frames[$historyFrameName];

                _createHistoryHTML(_currentHash, true);
            }


            function _createHistoryHTML($newHash) {
                with (_historyFrameRef.document) {
                    open("text/html");
                    write("<html><head></head><body onl",
                        'oad="parent.unFocus.History._updateFromHistory(\''+$newHash+'\');">',
                        $newHash+"</body></html>");
                    close();
                    }
            }


            function updateFromHistory($hash) {
                _currentHash = $hash;
                _this.notifyListeners("historyChange", $hash);
            }
            _this._updateFromHistory = function() {
                _this._updateFromHistory = updateFromHistory;
            };
            function addHistoryIE($newHash) { 
                if (_currentHash != $newHash) {
                    _currentHash = $newHash;
                    _createHistoryHTML($newHash);
                }
                return true;
            };
            _this.addHistory = function($newHash) {
                _createHistoryFrame();

                _this.addHistory = addHistoryIE;
                return _this.addHistory($newHash);
            };
            _this.addEventListener("historyChange", function($hash) {
                _setHash($hash)
            });

        }
    }
    Keeper.prototype = new unFocus.EventManager("historyChange");

    return new Keeper();

})();



function sendFormData(idForm, dataSource, divID, ifLoading)
{
    var postData='';
    var strReplaceTemp;

    //amit added for check history end
    var addhistoryhash = false;
    if(dataSource.match("addhash=true") != null){
        addhistoryhash = true;
    }

    if(addhistoryhash == true){
        var hashString = '';
        var qur_array = new Array();
        urlquerystring = dataSource.split("?");
        if(urlquerystring[1]!=''){
            urlparameters = urlquerystring[1].split("&");
            for (i=0;i<urlparameters.length;i++) {
                urlft = urlparameters[i].split("=");
                if(urlft[0]=='sort'){
                    urlft[0] = 'sort2';
                }
                qur_array[urlft[0]] = urlft[1];
            }

            if(qur_array['page'] != '' && qur_array['page'] != null){
                hashString += 'p-'+qur_array['page']+'/';
            }
            if(qur_array['rn'] != '' && qur_array['rn'] != null){
                hashString += 'rn-'+qur_array['rn']+'/';
            }
            if(qur_array['page1'] != '' && qur_array['page1'] != null){
                hashString += 'vp-'+qur_array['page1']+'/';
            }
            if(qur_array['tours_type'] != '' && qur_array['tours_type'] != null){
                hashString += 'tt-'+qur_array['tours_type']+'/';
            }
            if(qur_array['tours_type1'] != '' && qur_array['tours_type1'] != null){
                hashString += 'vtt-'+qur_array['tours_type1']+'/';
            }
            if(qur_array['products_durations'] != '' && qur_array['products_durations'] != null){
                hashString += 'dn-'+qur_array['products_durations']+'/';
            }
            if(qur_array['products_durations1'] != '' && qur_array['products_durations1'] != null){
                hashString += 'vdn-'+qur_array['products_durations1']+'/';
            }
            if(qur_array['departure_city_id'] != '' && qur_array['departure_city_id'] != null){
                hashString += 'dc-'+qur_array['departure_city_id']+'/';
            }
            if(qur_array['departure_city_id1'] != '' && qur_array['departure_city_id1'] != null){
                hashString += 'vdc-'+qur_array['departure_city_id1']+'/';
            }
            if(qur_array['top_attractions'] != '' && qur_array['top_attractions'] != null){
                hashString += 'at-'+qur_array['top_attractions']+'/';
            }
            if(qur_array['top_attractions1'] != '' && qur_array['top_attractions1'] != null){
                hashString += 'vat-'+qur_array['top_attractions1']+'/';
            }
            if(qur_array['sort2'] != '' && qur_array['sort2'] != null){
                hashString += 's-'+qur_array['sort2']+'/';
            }
            if(qur_array['sort1'] != '' && qur_array['sort1'] != null){
                hashString += 'vs-'+qur_array['sort1']+'/';
            }
            if(qur_array['products_id'] != '' && qur_array['products_id'] != null && qur_array['products_id'] != 0){
                hashString += 'pid-'+qur_array['products_id']+'/';
            }			 
            if(qur_array['cPath'] != '' && qur_array['cPath'] != null && qur_array['cPath'] != 0){
                cpath_all_root_array = qur_array['cPath'].split("_");
                hashString += 'cp-'+cpath_all_root_array[cpath_all_root_array.length-1]+'/';
            }
        }
    }
    //amit added for check history end

    if(XMLHttpRequestObject)
    {
        XMLHttpRequestObject.open("POST", dataSource);
        XMLHttpRequestObject.setRequestHeader("Method", "POST " + dataSource + " HTTP/1.1");
        XMLHttpRequestObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        XMLHttpRequestObject.onreadystatechange = function()
        {
            if (XMLHttpRequestObject.readyState == 4 &&
                XMLHttpRequestObject.status == 200)
                {
                try
                {
                    var objDiv = document.getElementById(divID);

                    var response = XMLHttpRequestObject.responseText;
                    //alert(response);
                    splt=response.split("|###|");

                    if(trim(splt[0])=="review_new_added"){			
                        document.getElementById(trim(splt[0])).innerHTML = splt[1] +  document.getElementById(trim(splt[0])).innerHTML;
                        jQuery(objDiv).html(splt[2]);
                        if(trim(splt[3]) == "success"){
                            jQuery(objDiv).html(splt[2]);
                            fadeOut('success_review_fad_out_id',20,5000);				
                            document.getElementById("write_review_form_id").style.display="none";				
                        }else{
                            jQuery(objDiv).html(splt[2]);
                        }			
                        clearForm('product_reviews_write');
                        try{
                            document.getElementById("noreview_id_div").style.display="none";
                        }catch(e){
                        }

                    }else if(trim(splt[0])=="question_new_added"){
                        document.getElementById(trim(splt[0])).innerHTML = splt[1] + document.getElementById(trim(splt[0])).innerHTML;			 
                        if(trim(splt[3]) == "success"){
                            jQuery(objDiv).html(splt[2]);
                            fadeOut('success_qa_fad_out_id',20,5000);				
                            document.getElementById("ask_question_form_id").style.display="none";				
                        }else{
                            jQuery(objDiv).html(splt[2]);
                        }			
                        // document.getElementById(trim(splt[3])).innerHTML = splt[4] + document.getElementById(trim(splt[3])).innerHTML;
                        clearForm('product_queston_write');
                        try{
                            document.getElementById("noquestion_id_div").style.display="none";
                        }catch(e){
                        }
                    }else if(trim(splt[0])=="question_answer_new_added"){
                        fadeOut('success_qa_ans_fad_out_id',20,5000);				
                        jQuery(objDiv).html(splt[1]);

                    }else if(trim(splt[0])=="successtoggeldiv"){	
                        jQuery(objDiv).html(splt[2]);		
                        document.getElementById(trim(splt[1])).style.display="";
                        document.getElementById("div_singin_or_without_signin").style.display="none";			
                    }else{
                        jQuery(objDiv).html(XMLHttpRequestObject.responseText);
                    }
                }
                catch(e){
                //document.write("sendFormData: getElementById(divID) Error");
                }
            }
            else
            {
                if(ifLoading)
                {
                    try
                    {
                        var objDiv = document.getElementById(divID);
                        objDiv.innerHTML = "<span style='margin-left:15px;'><img src=ajaxtabs/loading.gif></span> <span class=sp6>载入中，请稍侯……</span>";


                    }
                    catch(e){
                    //document.write("sendFormData->ifLoading: getElementById(divID) Error");
                    }
                }
            }
        }

        IdForm = document.getElementById(idForm);
        if(IdForm!=null){

            for(i=0; i<IdForm.elements.length - 1; i++)
            {
                strReplaceTemp = IdForm.elements[i].name.replace(/\[\]/i, "");
                //amit added for add history add	  
                if(addhistoryhash == true){
                    if(IdForm.elements[i].value != ''){
                        if(strReplaceTemp == 'page'){
                            if(hashString.search("/p-")=='-1' && hashString.search("/#p-")=='-1'){
                                hashString += 'p-'+IdForm.elements[i].value+'/';
                            }
                        }else if(strReplaceTemp == 'rn'){
                            if(hashString.search("/rn-")=='-1' && hashString.search("/#rn-")=='-1'){
                                hashString += 'p-'+IdForm.elements[i].value+'/';
                            }
                        }else if(strReplaceTemp == 'page1'){
                            if(hashString.search("/vp-")=='-1' && hashString.search("/#vp-")=='-1'){
                                hashString += 'vp-'+IdForm.elements[i].value+'/';
                            }
                        }else if(strReplaceTemp == 'tours_type'){
                            if(hashString.search("/tt-")=='-1' && hashString.search("/#tt-")=='-1'){
                                hashString += 'tt-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'tours_type1'){
                            if(hashString.search("/vtt-")=='-1' && hashString.search("/#vtt-")=='-1'){
                                hashString += 'vtt-'+IdForm.elements[i].value+'/';  
                            }

                        }else if(strReplaceTemp == 'products_durations'){
                            if(hashString.search("/dn-")=='-1' && hashString.search("/#dn-")=='-1'){
                                hashString += 'dn-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'products_durations1'){
                            if(hashString.search("/vdn-")=='-1' && hashString.search("/#vdn-")=='-1'){
                                hashString += 'vdn-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'departure_city_id'){
                            if(hashString.search("/dc-")=='-1' && hashString.search("/#dc-")=='-1'){
                                hashString += 'dc-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'departure_city_id1'){
                            if(hashString.search("/vdc-")=='-1' && hashString.search("/#vdc-")=='-1'){
                                hashString += 'vdc-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'top_attractions'){
                            if(hashString.search("/at-")=='-1' && hashString.search("/#at-")=='-1'){
                                hashString += 'at-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'top_attractions1'){
                            if(hashString.search("/vat-")=='-1' && hashString.search("/#vat-")=='-1'){
                                hashString += 'vat-'+IdForm.elements[i].value+'/';  
                            }	
                        }else if(strReplaceTemp == 'sort'){
                            if(hashString.search("/s-")=='-1' && hashString.search("/#s-")=='-1'){
                                hashString += 's-'+IdForm.elements[i].value+'/';  
                            }
                        }else if(strReplaceTemp == 'sort1'){
                            if(hashString.search("/vs-")=='-1' && hashString.search("/#vs-")=='-1'){
                                hashString += 'vs-'+IdForm.elements[i].value+'/';  
                            }
                        }
                    }
                }
                //amit added for check history end	
                //howard fixed
                if( ((IdForm.elements[i].type=='checkbox' || IdForm.elements[i].type=='radio') && IdForm.elements[i].checked==true) || (IdForm.elements[i].type!='checkbox' && IdForm.elements[i].type!='radio' )){
                    postData += "&aryFormData["+strReplaceTemp+"][]="+IdForm.elements[i].value.replace(/&/g, "@@amp;");
                }
            //howard fixed end
            }
        }

        postData += "&parm="+new Date().getTime();

        //amit added for add history add	  
        if(addhistoryhash == true){
            if(hashString != ''){
                unFocus.History.addHistory(hashString);
                if(hashString != ''){			
                    document.getElementById('last_ajax_hash').innerHTML = hashString;
                }
            }
        }
        //amit added for check history end

        try
        {
            XMLHttpRequestObject.send(postData);
            ifLoading = false;
        }
        catch(e){
            document.write("sendFormData: XMLHttpRequestObject.send Error");
        }
    }
}

function jQuery_ChangeOption(sch_id){
    var _search_option=jQuery('#'+sch_id+'_option');
    var a = jQuery('#'+sch_id+'_option_btnshow');
    if(_search_option.attr('isHidden') == "true"){
        _search_option.slideDown();
        _search_option.attr('isHidden','');
        a.removeClass('hidden');
		/* 写cookie记录操作状态 */
		setCookie(sch_id+'_option', 'show', 1);
		jQuery("#MoreOptsA").show();
    }else{
        _search_option.attr('isHidden','true');
        _search_option.slideUp();
        a.addClass('hidden');
		/* 写cookie记录操作状态 */
		setCookie(sch_id+'_option', 'hide', 1);
		jQuery("#MoreOptsA").hide();
    }
}

function jQuery_Change_Sort(obj){
    if(jQuery(obj).attr('ajax')!='true'){
        var href = jQuery(obj).val();
        window.location.href=href;
    }
}

function jQuery_Change_Word(obj){
    if(jQuery(obj).attr('ajax')!='true'){
        var val = jQuery(obj).val();
        if(val==null){
            val='';
        }
        val = val.replace(/\s+/g,'');
        if(val!=jQuery(obj).attr('val')){
            val = encodeURIComponent(val);
            pageUrl=window.location.href;
            pageUrl_arr=pageUrl.split('?');
            pageUrl=pageUrl_arr[0];
            pageQuery=pageUrl_arr[1];
            if(pageQuery==null||pageQuery==''){
                pageQuery='';
            }else{
                pageQuery='?'+pageQuery;
            }
            pageUrl = pageUrl.replace(/\/w-[^\/]*\//g,'/');
            if(val!=''){
                pageUrl = pageUrl+'w-'+val+'/';
            }
            window.location.href = pageUrl+pageQuery;
        }
    }
}

function jQuery_Search_Init(sch_id){
    var schUL=jQuery('#'+sch_id+'_option');
    schUL.find('#'+sch_id+'_option_item').each(function() {
        var mod = jQuery(this).attr('mod');
        jQuery(this).find('a').each(function(){
            if(jQuery(this).attr('mod')!=null && jQuery(this).attr('mod')!='' && jQuery(this).attr('mod')!=mod){
                jQuery(this).click(function(){
                    jQuery_Search_setValue(jQuery(this),sch_id);
                    return false;
                });
            }else{
                jQuery(this).attr('mod',mod);
                jQuery(this).click(function(){
                    jQuery_Search_click(sch_id,jQuery(this));
                    return false;
                });	
            }
        });
        jQuery(this).find(':text').each(function(){
            jQuery(this).attr('mod',mod);
            jQuery(this).attr('ajax','true');
            jQuery(this).blur(function(){
                var val = jQuery(this).val();
                if(val==null){
                    val='';
                }
                val = jQuery.trim(val);
                if(val!=jQuery(this).attr('val')){
                    jQuery(this).attr('val',val);
                    jQuery_Search_setValue(jQuery(this),sch_id);
                    return false;
                }
            });	
        });
    });
}

function jQuery_Search_Init_Other(sch_id){
    var optionOther=jQuery('.'+sch_id+'_option_other');
    optionOther.find('.'+sch_id+'_ajax').each(function (){
        if(jQuery(this).is('select')){
            jQuery(this).attr('ajax','true');
            jQuery(this).change(function(){
                jQuery_Search_setValue(jQuery(this),sch_id);
                return false;
            });
        }else{
            jQuery(this).click(function(){
                jQuery_Search_setValue(jQuery(this),sch_id);
                return false;
            });
        }
    });
}

function jQuery_Search_click(sch_id,_schObj){
    var mod = _schObj.attr('mod');
    var val = _schObj.attr('val');
    _schObj.closest('div').find('a.selected').removeClass('selected');
    _schObj.addClass('selected');
    var span_mod = jQuery('#'+sch_id+'_form').find('span.'+sch_id+'_mod_'+mod);
    jQuery('#'+sch_id+'_linked_'+mod).find('a.selected').removeClass('selected');
    if(val!=null && val!=''){
        if(!span_mod.is('span')){
            span_mod = jQuery('<span class="'+sch_id+'_mod_'+mod+'"><i style="cursor:default"></i><a href="javascript:void(0);"><img src="image/icons/icon_del.gif" /></a></span>');
            span_mod.appendTo('#'+sch_id+'_form');
            span_mod.find('a').click(function(){
                var option_div = jQuery('#'+sch_id+'_option').find("li[mod='"+mod+"']");
                if(option_div.is('li')){
                    option_div.find('a').eq(0).click();
                }else{
                    jQuery('#'+sch_id+'_linked_'+mod).find('a.selected').removeClass('selected');
                    jQuery('#'+sch_id+'_form').find("input[name='"+mod+"']").remove();
                    jQuery('#'+sch_id+'_form').find('span.'+sch_id+'_mod_'+mod).remove();
                    jQuery_Search_sendFormData(sch_id);
                }
            });
            var title = jQuery('#'+sch_id+'_option').find('li[mod="'+mod+'"]').find('b').html();
            if(title!=null)span_mod.attr('title',title.replace('：',''));
        }
        span_mod.find('i').html(_schObj.html());
        jQuery('#'+sch_id+'_linked_'+mod).find('a[val="'+val+'"]').addClass('selected');
    }else{
        span_mod.remove();
    }
    jQuery_Search_setValue(_schObj,sch_id);
    return false;
}

function jQuery_Search_setValue(_schObj,sch_id){
    var val = _schObj.attr('val');
    if(_schObj.is('select')){
        val = _schObj.find("option:selected").attr('val');
    }
    var mod = _schObj.attr('mod');
    var sch_form = jQuery('#'+sch_id+'_form');
    var input_hidmod = sch_form.find("input[name='"+mod+"']");
    if(val!=null && val!=''){
        if(!input_hidmod.is('input')){
            input_hidmod=jQuery('<input type="hidden" jsadd="true" name="'+mod+'" />');
            sch_form.append(input_hidmod);
        }
        input_hidmod.val(val);
    }else{
        input_hidmod.remove();
    }
    if(mod!='pp'){
        sch_form.find("input[name='pp']").val('');
    }
    jQuery_Search_sendFormData(sch_id);
    return false;
}

function jQuery_Search_showPanel(obj,xobj){
    if(xobj.attr('hidden') == "true"){
        obj.hide();
    }else{
        obj.width(xobj.width());
        obj.height(xobj.height());
        var offset=xobj.offset();
        obj.css('left',offset.left+'px');
        obj.css('top',offset.top+'px');
        obj.css('background-color','#fff');
        obj.css('position','absolute');
        obj.css({
            opacity:0
        });
        obj.show();
    }
}

function jQuery_Search_sendFormData(sch_id){
    var idForm = sch_id+'_form';
    var toDiv  = sch_id+'_ResultPanel';
    var hashString = '';
    pageUrl=jQuery("#"+idForm).attr('action');
    pageUrl_arr=pageUrl.split('?');
    pageUrl=pageUrl_arr[0];
    pageQuery=pageUrl_arr[1];
    if(pageQuery==null||pageQuery==''){
        pageQuery='?ajax=true';
    }else{
        pageQuery='?'+pageQuery+'&ajax=true';
    }
    var hashString='';
    jQuery("#"+idForm).find('input').each(function(){
        var name = jQuery(this).attr('name');
        var val = jQuery(this).val();
        if(name=='w'){
            val=encodeURIComponent(val);
        }
        if(val!=null && val!=''){
            pageUrl += name+'-'+val+'/';
            hashString += name+'-'+val+'/';
        }
    });
    pageUrl = pageUrl+pageQuery;
    var loadobject = "<img src='ajaxtabs/loading.gif' align='absmiddle'>";
    var after_load_panel = jQuery(".after_"+sch_id+"_load");
    if(after_load_panel.is('div')){
        after_load_panel.append(jQuery(loadobject));
    }else{
        jQuery(loadobject).prependTo('#'+toDiv);
    }
    var sch_id_option=jQuery('#'+sch_id+'_option');
    if(sch_id_option.is('ul')){
        var sch_id_form_panel=jQuery('#'+sch_id+'_form_panel');
        var _sch_option_topdiv=jQuery('#'+sch_id+'_option_topdiv');
        var _sch_option_topdiv1=jQuery('#'+sch_id+'_option_topdiv1');
        if(!_sch_option_topdiv.is('div')){
            _sch_option_topdiv=jQuery('<div id="'+sch_id+'_option_topdiv"></div>');
            _sch_option_topdiv.appendTo(jQuery(document.body));
            _sch_option_topdiv1=jQuery('<div id="'+sch_id+'_option_topdiv1"></div>');
            _sch_option_topdiv1.appendTo(jQuery(document.body));

        }
        jQuery_Search_showPanel(_sch_option_topdiv,sch_id_option);
        jQuery_Search_showPanel(_sch_option_topdiv1,sch_id_form_panel);
    }
    jQuery.ajax({
        global: false,
        url: pageUrl,
        cache: false,
        dataType: 'html',
        success: function(data){
            jQuery("#"+toDiv).html(data);
        },
        complete: function(XMLHttpRequest, textStatus){
            if(sch_id_option.is('ul')){
                _sch_option_topdiv.hide();
                _sch_option_topdiv1.hide();
            }
            if(is_jQueryAutoLoad==1){
                is_jQueryAutoLoad=0;
                jQueryAutoLoad_ShowOption(sch_id);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Ajax Error!Refresh Please!');
        },
        dataFilter: function (data, type){
            splt=data.split("|###|");
            if(splt[1]==null || splt[2]==null || splt[2]=='')return splt[0];
            jQuery("#"+idForm+'_num').html(splt[0]);
            if(splt[1]!='')jQuery('#'+sch_id+'_option').html(splt[1]);
            jQuery_Search_Init(sch_id);
            return splt[2];
        } 
    });   
    jQuery("#"+idForm).attr('lasthash',hashString);
    unFocus.History.addHistory(hashString);
    return false;
}

var is_jQueryAutoLoad = 0;
function jQueryAutoLoad_ShowOption(sch_id){
    var htmlString='';
    var schUL=jQuery('#'+sch_id+'_option');
    var form = jQuery('#'+sch_id+'_form');
    var lasthash = form.attr('lasthash');
    var lasthash_arr = lasthash.split('/');
    form.find('span').remove();
    jQuery.each( lasthash_arr, function(i, hashitem){
        if(hashitem!=''){
            hashitem = hashitem.split('-');
            var mod = hashitem[0];
            var val = hashitem[1];
            var name = '';
            var this_a = schUL.find('#'+sch_id+'_option_item[mod="'+mod+'"]').find('a[val="'+val+'"]');
            if(!this_a.is('a')){
                this_a = jQuery('#'+sch_id+'_linked_'+mod).find('a[val="'+val+'"]');
                this_a.addClass('selected');
            }
            var name=this_a.html();
            if(name!=null && name!=''){
                htmlString += '<span class="'+sch_id+'_mod_'+mod+'"><i style="cursor:default">'+name+'</i><a href="javascript:void(0);" onclick="return jQuery_Search_click(\''+sch_id+'\',jQuery(this));" mod="'+mod+'"><img src="image/icons/icon_del.gif" /></a></span>';
            }
        }
    });
    form.html(form.html()+htmlString);
}
function jQueryAutoLoad(schid){
    var hash = window.location.hash;
    hash = hash.replace('#','');
    var form = jQuery('#'+schid+'_form');
    var lasthash=form.attr('lasthash');
    var nulllasthash=false;
    if(lasthash==null||lasthash==''){
        nulllasthash=true;
        lasthash='';
    }
    if(hash==''){
        form.find("input").each(function (){
            var _jsadd=jQuery(this).attr('jsadd');
            var _name = jQuery(this).attr('name');
            var _val = jQuery(this).val();
            if(_jsadd==null || _jsadd!='true'){
                if(_val!=''){
                    hash += _name+'-'+_val+'/';
                    if(nulllasthash)lasthash += _name+'-'+_val+'/';
                }
            }
        });
    }
    if(lasthash!=hash){
        form.attr('lasthash',hash);
        var hash_arr = hash.split('/');
        var mod='';
        var val='';
        var htmlString='';
        form.find('input[jsadd="true"]').remove();
        jQuery.each( hash_arr, function(i, hashitem){
            if(hashitem!=''){
                hashitem = hashitem.split('-');
                mod = hashitem[0];
                val = hashitem[1];
                var input_hidmod = form.find("input[name='"+mod+"']");
                if(val!=null && val!=''){
                    if(!input_hidmod.is('input')){
                        htmlString += '<input type="hidden" jsadd="true" name="'+mod+'" value="'+val+'" />';
                    }else{
                        input_hidmod.val(val);
                    }
                }else{
                    input_hidmod.remove();
                }
            }
        });
        form.html(form.html()+htmlString);
        is_jQueryAutoLoad = 1;
        jQuery_Search_sendFormData(schid);
    }
    setTimeout("jQueryAutoLoad('"+schid+"')",300);
}

function request(paras){
    var url = window.location.href;
    url = url.split("#");
    url = url[0];
    var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
    var paraObj = {}
    for (i=0; j=paraString[i]; i++){
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if(typeof(returnValue)=="undefined"){
        return "";
    }else{
        return returnValue;
    }
}

function jQueryAddFavorites(pid){
    if(typeof(pid)=="undefined"){
        alert("No product_id");
        return false;
    }else{
        var pageurl = 'ajax_favorites.php?ajax=true&action=add_favorites&product_id='+pid;
        var osCsid = request('osCsid');
        if(osCsid!=null && osCsid!=''){
            pageurl += '&osCsid='+osCsid;
        }
        var language = request('language');
        if(language!=null && language!=''){
            pageurl += '&language='+language;
        }
        jQuery.ajax({
            global: false,
            url: pageurl,
            type: 'GET',
            cache: false,
            dataType: 'html',
            success: function(data){
                var suces_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
                var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
                var Favorites_Content = jQuery('#addToFavoritesPanel #Favorites_Content');
                var Success = jQuery('#addToFavoritesPanel .successTip');
                var Content = '';
                if(data.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
                    Success.hide();
                    Content = data.replace(error_regxp,'');
                }
                if(data.search(/(\[SUCCESS\].+\[\/SUCCESS\])/g)!=-1){
                    Success.show();
                    Content = data.replace(suces_regxp,'');

                    var Cart_PObject = jQuery('#proListCon_'+pid+' h2').find('a');
                    if(!Cart_PObject.is('a')){
                        Success.find('#Favorites_Pname').html(jQuery('div.topTitle h1').html());
                        Success.find('#Favorites_Pname').attr('href',window.location.href);
                    }else{
                        Success.find('#Favorites_Pname').html(Cart_PObject.html());
                        Success.find('#Favorites_Pname').attr('href',Cart_PObject.attr('href'));
                    }
                }
                Favorites_Content.html(Content);
                showPopup('addToFavorites','addToFavoritesPanel','off','','','fixedTop','add_favorites_a_link_'+pid);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Ajax Error!Refresh Please!');
            }
        }); 
    }
}

function jQueryAddCart(pid,room,minnum){
    var pageurl = 'shopping_cart.php?action=add_product&products_id='+pid;
    var osCsid = request('osCsid');
    if(osCsid!=null && osCsid!=''){
        pageurl += '&osCsid='+osCsid;
    }
    var language = request('language');
    if(language!=null && language!=''){
        pageurl += '&language='+language;
    }
    var dateobj = new Date();
    if(serverDateTime==null || serverDateTime==''){
        var serverDate = serverDateTime.split(' ');
        serverDate = serverDate[0];
        serverDate = serverDate.split('-');
        dateobj = new Date(serverDate[1],serverDate[2],serverDate[0]);
    }
    var date = dateobj.getFullYear()+'-'+(dateobj.getMonth()+1)+'-'+dateobj.getDate()+'::##';
    var date1 = (dateobj.getMonth()+1)+'/'+dateobj.getDate()+'/'+dateobj.getFullYear();
    var postdata = 'ajax=true&products_id='+pid;
    postdata += '&numberOfRooms='+room;
    postdata += '&room-0-adult-total='+minnum;
    postdata += '&room-0-child-total=0';
    postdata += '&time1='+date1;
    postdata += '&travel_comp=0';
    postdata += '&id[74]=461';
    postdata += '&id[81]=495';
    postdata += '&departurelocation=default';
    postdata += '&tmp_show_time=default';
    postdata += '&availabletourdate='+date;

    jQuery.ajax({
        global: false,
        url: pageurl,
        type: 'POST',
        data: postdata,
        cache: false,
        dataType: 'html',
        success: function(data){
            var sex_regxp = /(.*\[Cart_Sum\])|(\[\/Cart_Sum\].*[:space:]*.*)/g;
            var sex_regxp1 = /(.*\[Cart_Total\])|(\[\/Cart_Total\].*[:space:]*.*)/g;
            var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
            var Error = jQuery('#addToCartPanel .errorTip');
            var Success = jQuery('#addToCartPanel .successTip');
            if(data.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
                Error.show();
                Success.hide();
                Error.html(data.replace(error_regxp,''));
            }
            if(data.search(/(\[Cart_Sum\]\d+\[\/Cart_Sum\])/g)!=-1){
                Success.show();
                Error.hide();
                var CartSum = data.replace(sex_regxp,'');
                var CartTotal = data.replace(sex_regxp1,'');
                jQuery('#CarSumTop').html(CartSum);
                Success.find('#Cart_Sum').html(CartSum);
                Success.find('#CartTotal').html(CartTotal);
                var Cart_PObject = jQuery('#proListCon_'+pid+' h2').find('a');
                Success.find('#Cart_Pname').html(Cart_PObject.html());
                Success.find('#Cart_Pname').attr('href',Cart_PObject.attr('href'));
            }
            showPopup('addToCart','addToCartPanel','off','','','fixedTop','add_cart_a_link_'+pid);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Ajax Error!Refresh Please!');
        }
    }); 	
}

/* history and ajax back button end */
function initTab_show(nid,cid,action,defaultIndex){
    var ls=g(nid).getElementsByTagName('li');
    var cc=g(cid).childNodes;
    var c=[];
    var index=defaultIndex?defaultIndex:0;
    for(var i=0;i<cc.length;i++)if(cc[i].nodeType==1)c.push(cc[i]);if(ls.length!=c.length)
        throw({
            description:'菜单和内容数量不对应'
        });
    for(var i=0;i<ls.length;i++){
        ls[i].index=i;
        if(i==index){
            ls[i].className='show_hovertab';
            c[i].className='show_dis';
            ls[i].parentNode.last=ls[i];
        }
        addEvent(ls[i],action,function(e){
            var self=window.event?window.event.srcElement:e?e.target:null;
            if(self.parentNode.last){
                self.parentNode.last.className='show_normaltab';
                c[self.parentNode.last.index].className='show_undis';
            };
            
            self.className='show_hovertab';
            c[self.index].className='show_dis';
            self.parentNode.last=self;
        });
    }
}

/*zhenghua added*/
function initTab_search(nid,cid,action,defaultIndex){
    if(g(nid)==null || g(cid)==null){
        return;
    }
    var ls = g(nid).getElementsByTagName('li');
    var cc = g(cid).childNodes;
    var c = [];
    var index = defaultIndex?defaultIndex:0;
    for (var i = 0 ; i < cc.length ; i ++)if(cc[i].nodeType==1)c.push(cc[i]);
    if (ls.length!=c.length)
        for (var i = 0 ; i < ls.length ; i ++){
            ls[i].index = i;
            if (i==index){
                ls[i].className = 'hovertabx';
                c[i].className = 'disx'
                ls[i].parentNode.last = ls[i];
            }
            addEvent(ls[i],action,function(e){
                var self = window.event?window.event.srcElement:e?e.target:null;
                if (self.parentNode.last){
                    self.parentNode.last.className = 'normaltabx';
                    c[self.parentNode.last.index].className = 'undisx';
                };
                self.className = 'hovertabx';
                c[self.index].className = 'disx';
                self.parentNode.last = self;
            });
        }
}

/* spry_panels.js ---- start */

var Spry;
if (!Spry) Spry = {};
if (!Spry.Widget) Spry.Widget = {};

Spry.Widget.TabbedPanels = function(element, opts)
{
    this.element = this.getElement(element);

    if(typeof(Global_defaultTab)!='undefined'){
        this.defaultTab = Global_defaultTab; 
    }else{
        this.defaultTab = 0; 
    }

    if(typeof(Global_defaultTabSelectedClass)!='undefined'){
        this.tabSelectedClass = Global_defaultTabSelectedClass;
    }else{
        this.tabSelectedClass = "TabbedPanelsTabSelected";
    }

    this.bindings = [];
    this.tabHoverClass = "TabbedPanelsTabHover";
    this.tabFocusedClass = "TabbedPanelsTabFocused";
    this.panelVisibleClass = "TabbedPanelsContentVisible";
    this.focusElement = null;
    this.hasFocus = false;
    this.currentTabIndex = 0;
    this.enableKeyboardNavigation = true;

    Spry.Widget.TabbedPanels.setOptions(this, opts);

    if (typeof (this.defaultTab) == "number")
    {
        if (this.defaultTab < 0)
            this.defaultTab = 0;
        else
        {
            var count = this.getTabbedPanelCount();
            if (this.defaultTab >= count)
                this.defaultTab = (count > 1) ? (count - 1) : 0;
        }

        this.defaultTab = this.getTabs()[this.defaultTab];
    }

    if (this.defaultTab)
        this.defaultTab = this.getElement(this.defaultTab);

    this.attachBehaviors();
};

Spry.Widget.TabbedPanels.prototype.getElement = function(ele)
{
    if (ele && typeof ele == "string")
        return document.getElementById(ele);
    return ele;
}

Spry.Widget.TabbedPanels.prototype.getElementChildren = function(element)
{
    var children = [];
    var child = element.firstChild;
    while (child)
    {
        if (child.nodeType == 1 /* Node.ELEMENT_NODE */)
            children.push(child);
        child = child.nextSibling;
    }
    return children;
};

Spry.Widget.TabbedPanels.prototype.addClassName = function(ele, className)
{
    if (!ele || !className || (ele.className && ele.className.search(new RegExp("\\b" + className + "\\b")) != -1))
        return;
    ele.className += (ele.className ? " " : "") + className;
};

Spry.Widget.TabbedPanels.prototype.removeClassName = function(ele, className)
{
    if (!ele || !className || (ele.className && ele.className.search(new RegExp("\\b" + className + "\\b")) == -1))
        return;
    ele.className = ele.className.replace(new RegExp("\\s*\\b" + className + "\\b", "g"), "");
};

Spry.Widget.TabbedPanels.setOptions = function(obj, optionsObj, ignoreUndefinedProps)
{
    if (!optionsObj)
        return;
    for (var optionName in optionsObj)
    {
        if (ignoreUndefinedProps && optionsObj[optionName] == undefined)
            continue;
        obj[optionName] = optionsObj[optionName];
    }
};

Spry.Widget.TabbedPanels.prototype.getTabGroup = function()
{
    if (this.element)
    {
        var children = this.getElementChildren(this.element);
        if (children.length)
            return children[0];
    }
    return null;
};

Spry.Widget.TabbedPanels.prototype.getTabs = function()
{
    var tabs = [];
    var tg = this.getTabGroup();
    if (tg)
        tabs = this.getElementChildren(tg);
    return tabs;
};

Spry.Widget.TabbedPanels.prototype.getContentPanelGroup = function()
{
    if (this.element)
    {
        var children = this.getElementChildren(this.element);
        if (children.length > 1)
            return children[1];
    }
    return null;
};

Spry.Widget.TabbedPanels.prototype.getContentPanels = function()
{
    var panels = [];
    var pg = this.getContentPanelGroup();
    if (pg)
        panels = this.getElementChildren(pg);
    return panels;
};

Spry.Widget.TabbedPanels.prototype.getIndex = function(ele, arr)
{
    ele = this.getElement(ele);
    if (ele && arr && arr.length)
    {
        for (var i = 0; i < arr.length; i++)
        {
            if (ele == arr[i])
                return i;
        }
    }
    return -1;
};

Spry.Widget.TabbedPanels.prototype.getTabIndex = function(ele)
{
    var i = this.getIndex(ele, this.getTabs());
    if (i < 0)
        i = this.getIndex(ele, this.getContentPanels());
    return i;
};

Spry.Widget.TabbedPanels.prototype.getCurrentTabIndex = function()
{
    return this.currentTabIndex;
};

Spry.Widget.TabbedPanels.prototype.getTabbedPanelCount = function(ele)
{
    return Math.min(this.getTabs().length, this.getContentPanels().length);
};

Spry.Widget.TabbedPanels.addEventListener = function(element, eventType, handler, capture)
{
    try
    {
        if (element.addEventListener)
            element.addEventListener(eventType, handler, capture);
        else if (element.attachEvent)
            element.attachEvent("on" + eventType, handler);
    }
    catch (e) {}
};

Spry.Widget.TabbedPanels.prototype.onTabClick = function(e, tab)
{
    this.showPanel(tab);
};

Spry.Widget.TabbedPanels.prototype.onTabMouseOver = function(e, tab)
{
    this.addClassName(tab, this.tabHoverClass);
};

Spry.Widget.TabbedPanels.prototype.onTabMouseOut = function(e, tab)
{
    this.removeClassName(tab, this.tabHoverClass);
};

Spry.Widget.TabbedPanels.prototype.onTabFocus = function(e, tab)
{
    this.hasFocus = true;
    this.addClassName(this.element, this.tabFocusedClass);
};

Spry.Widget.TabbedPanels.prototype.onTabBlur = function(e, tab)
{
    this.hasFocus = false;
    this.removeClassName(this.element, this.tabFocusedClass);
};

Spry.Widget.TabbedPanels.ENTER_KEY = 13;
Spry.Widget.TabbedPanels.SPACE_KEY = 32;

Spry.Widget.TabbedPanels.prototype.onTabKeyDown = function(e, tab)
{
    var key = e.keyCode;
    if (!this.hasFocus || (key != Spry.Widget.TabbedPanels.ENTER_KEY && key != Spry.Widget.TabbedPanels.SPACE_KEY))
        return true;

    this.showPanel(tab);

    if (e.stopPropagation)
        e.stopPropagation();
    if (e.preventDefault)
        e.preventDefault();

    return false;
};

Spry.Widget.TabbedPanels.prototype.preorderTraversal = function(root, func)
{
    var stopTraversal = false;
    if (root)
    {
        stopTraversal = func(root);
        if (root.hasChildNodes())
        {
            var child = root.firstChild;
            while (!stopTraversal && child)
            {
                stopTraversal = this.preorderTraversal(child, func);
                try {
                    child = child.nextSibling;
                } catch (e) {
                    child = null;
                }
            }
        }
    }
    return stopTraversal;
};

Spry.Widget.TabbedPanels.prototype.addPanelEventListeners = function(tab, panel)
{
    var self = this;
    if(typeof(DisplayStyle)!='undefined' && DisplayStyle == "onclick"){
        Spry.Widget.TabbedPanels.addEventListener(tab, "click", function(e) {
				 
            if(tab.title!=""){
                document.location.hash = tab.title;
            //var the_url = window.location.href.split("#")[0];
            //the_url+= "#"+tab.title;
            //window.location.href = the_url;
            } 

            return self.onTabClick(e, tab);
				 
        }, false
        );

        Spry.Widget.TabbedPanels.addEventListener(tab, "mouseover", function(e) {
            return self.onTabMouseOver(e, tab);
        }, false);
        Spry.Widget.TabbedPanels.addEventListener(tab, "mouseout", function(e) {
            return self.onTabMouseOut(e, tab);
        }, false);
    }else{
        Spry.Widget.TabbedPanels.addEventListener(tab, "mouseover", function(e) {
            return self.onTabClick(e, tab);
        }, false);
    }

    if (this.enableKeyboardNavigation)
    {
        var tabIndexEle = null;
        var tabAnchorEle = null;

        this.preorderTraversal(tab, function(node) {
            if (node.nodeType == 1 /* NODE.ELEMENT_NODE */)
            {
                var tabIndexAttr = tab.attributes.getNamedItem("tabindex");
                if (tabIndexAttr)
                {
                    tabIndexEle = node;
                    return true;
                }
                if (!tabAnchorEle && node.nodeName.toLowerCase() == "a")
                    tabAnchorEle = node;
            }
            return false;
        });

        if (tabIndexEle)
            this.focusElement = tabIndexEle;
        else if (tabAnchorEle)
            this.focusElement = tabAnchorEle;

        if (this.focusElement)
        {
            Spry.Widget.TabbedPanels.addEventListener(this.focusElement, "focus", function(e) {
                return self.onTabFocus(e, tab);
            }, false);
            Spry.Widget.TabbedPanels.addEventListener(this.focusElement, "blur", function(e) {
                return self.onTabBlur(e, tab);
            }, false);
            Spry.Widget.TabbedPanels.addEventListener(this.focusElement, "keydown", function(e) {
                return self.onTabKeyDown(e, tab);
            }, false);
        }
    }
};

Spry.Widget.TabbedPanels.prototype.showPanel = function(elementOrIndex)
{
    var tpIndex = -1;

    if (typeof elementOrIndex == "number")
        tpIndex = elementOrIndex;
    else // Must be the element for the tab or content panel.
        tpIndex = this.getTabIndex(elementOrIndex);

    if (!tpIndex < 0 || tpIndex >= this.getTabbedPanelCount())
        return;

    var tabs = this.getTabs();
    var panels = this.getContentPanels();

    var numTabbedPanels = Math.max(tabs.length, panels.length);

    for (var i = 0; i < numTabbedPanels; i++)
    {
        if (i != tpIndex)
        {
            if (tabs[i])
                this.removeClassName(tabs[i], this.tabSelectedClass);
            if (panels[i])
            {
                this.removeClassName(panels[i], this.panelVisibleClass);
                panels[i].style.display = "none";
            }
        }
    }

    this.addClassName(tabs[tpIndex], this.tabSelectedClass);
    this.addClassName(panels[tpIndex], this.panelVisibleClass);
    panels[tpIndex].style.display = "block";

    this.currentTabIndex = tpIndex;
};

Spry.Widget.TabbedPanels.prototype.attachBehaviors = function(element)
{
    var tabs = this.getTabs();
    var panels = this.getContentPanels();
    var panelCount = this.getTabbedPanelCount();

    for (var i = 0; i < panelCount; i++)
        this.addPanelEventListeners(tabs[i], panels[i]);

    this.showPanel(this.defaultTab);
};

/* spry_panels.js ---- end */

function pointerX(){   
    if(document.all){
        return (event.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft)); 
    }
}

function pointerY(){   
    if(document.all){
        return (event.clientY + (document.documentElement.scrollTop || document.body.scrollTop));
    }
}

function showDiv(id, pointer){	//pointer可为空
    var div = document.getElementsByTagName('div');
    for(i=0; i<div.length; i++){
        if(div[i].className == "center_pop" || div[i].className == "jb_fb_tc" ||div[i].className == "jb_fb_tcAddXx"){
            div[i].style.display='none';
        }
    }
    var ID = document.getElementById(id);
    ID.style.display='block';
    if(document.getElementById('bg')!=null){
        document.getElementById('bg').style.display='block';
    }
    if(document.all){
        //隐藏页面的下拉菜单
        var select_ = document.getElementsByTagName("select");
        for(i=0; i<select_.length; i++){
            select_[i].className += " hidden_select"; 
        }
        //恢复当前对象的下拉菜单
        var obj_select = ID.getElementsByTagName("select");
        for(i=0; i<obj_select.length; i++){
            obj_select[i].className = obj_select[i].className.replace(/hidden_select/,''); 
        }
    }
    if(typeof(pointer)=='undefined'){
        ID.style.top = pointerY()+"px";
    }
}


function closeDiv(id){
    var ID = document.getElementById(id);
    ID.style.display='none';
    if(document.getElementById('bg')!=null){
        document.getElementById('bg').style.display='none';
    }
    if(document.all){
        //还原页面的下拉菜单
        var select_ = document.getElementsByTagName("select");
        for(i=0; i<select_.length; i++){
            select_[i].className = select_[i].className.replace(/hidden_select/,''); 
        }
    }
}
function showDivS(id){
    var id = document.getElementById(id);
    id.style.display='block';
}

function closeDivS(id){
    var id = document.getElementById(id);
    id.style.display='none';
}

var curObj= null; 
function document_onclick() { 
    if(window.event.srcElement.tagName=='A'||window.event.srcElement.tagName=='TD'){ 
        if(curObj!=null) 
            curObj.style.background=''; 
        curObj=window.event.srcElement;
        curObj.style.background='#223C6A'; 
    } 
} 

function MM_effectSlide(targetElement, duration, from, to, toggle)
{
    Spry.Effect.DoSlide(targetElement, {
        duration: duration, 
        from: from, 
        to: to, 
        toggle: toggle
    })
}