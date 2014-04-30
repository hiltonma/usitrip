// JavaScript Document
function hasClass(ele,cls) {
    return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
function addClass(ele,cls) {
    if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
function removeClass(ele,cls) {
    if (hasClass(ele,cls)) {
    var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
        ele.className=ele.className.replace(reg,' ');
    }
}
function toggleClass(ele,cls) {
    if(hasClass(ele,cls)){
        removeClass(ele,cls);
    }
    else
        addClass(ele,cls);
}
function getStyle(elem,styleName){
    if(elem.style[styleName]){
        return elem.style[styleName];
    }
    else if(elem.currentStyle){//IE
        return elem.currentStyle[styleName];
    }
    else if(document.defaultView && document.defaultView.getComputedStyle){
        styleName = styleName.replace(/([A-Z])/g,'-$1').toLowerCase();
        var s = document.defaultView.getComputedStyle(elem,'');
        return s&&s.getPropertyValue(styleName);
    }
    else{
        return null;
    }
}
	
function showHideLyer(tit,con,cls){
	toggleClass(tit,cls);
	var t = document.getElementById(con);	
	if(t){t.style.display = getStyle(t,'display') == 'none' ? 'block' : 'none';}
}
