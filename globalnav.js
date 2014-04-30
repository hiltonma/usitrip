<script language="JavaScript" charset="gb2312"><!--
  var mIndex = -1;
  var lastIndex = -1;
  var isOpen = false;
  document.onclick = doClick;
  var menuCount =3;
  function openLever1() {  //  打开下拉菜单
    var o = getObj("menu"+mIndex);
    if (getObj("submenu"+mIndex)) {
      show("submenu"+mIndex);  // 显示下拉菜单并定位下拉菜单
      getObj("submenu"+mIndex).style.left=o.offsetLeft;
      getObj("submenu"+mIndex).style.top=o.offsetTop + getObj("menubar").offsetHeight - 4;
    }
    isOpen = true;
    lastIndex = mIndex;
    setStyle("menu"+mIndex,getHlightStatus());
  }
  function mouseOverMenu(index) {
    mIndex = index;
    setStyle("menu"+index,getHlightStatus());
    if (isOpen) {
      if (lastIndex>-1)
        hideMenu(lastIndex);
      setStyle("menu"+lastIndex,"none");
      if (getObj("submenu"+index))  openLever1();
    }
  }
  function mouseOutMenu(index) {
    if (!isOpen || !getObj("submenu"+index)) {
      setStyle("menu"+index,"none");
    }
    mIndex = -1;
  }
  function doClick() {
    if (mIndex==-1) {  // not click on the menubar
      for (var i=0; i<menuCount; i++) {
        hideMenu(i);
        eval("setStyle('menu"+i+"','none')");
      }
      isOpen = false;
      lastIndex = -1;
    }
    else { // click on the menubar
      if (isOpen && mIndex==lastIndex) {
        hideMenu(lastIndex);
        isOpen = false;
      }
      else
        openLever1();
      setStyle("menu"+mIndex,getHlightStatus());
    }
  }
  function setStyle(id,style) { 
    if (style=="close_hlight") {
      getObj(id).style.borderTop = "1px solid white";
      getObj(id).style.borderLeft = "1px solid white";
      getObj(id).style.borderRight = "1px solid #999";
      getObj(id).style.borderBottom = "1px solid #999";
    }
    else if (style=="open_hlight") {
      getObj(id).style.borderTop = "1px solid #999";
      getObj(id).style.borderLeft = "1px solid #999";
      getObj(id).style.borderRight = "1px solid white";
      getObj(id).style.borderBottom = "1px solid white";
    }
    else if (style=="none") {
      getObj(id).style.border = "1px solid #eee";
    }
  }
  function getHlightStatus() {
    if (isOpen) return "open_hlight";
    else return "close_hlight";
  }
  function getObj(id) {
    return document.getElementById(id);
  }
  function show(id) {
    if (getObj(id)) 
     getObj(id).style.display="block";
  }
  function hide(id) {
    if (getObj(id)) 
     getObj(id).style.display="none";
  }
  function hideMenu(index) {
    var id = "submenu" + index;
    if (getObj(id)) 
      hide(id);
  }
//-->
</script>