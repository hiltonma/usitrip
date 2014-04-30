<?php 
/**
 * 新的友情链接管理
 * 
 * @author Administrator
 * @version $Id$
 */ 
 require('includes/application_top.php');
 // 备注添加删除
 if($_GET['ajax']=="true"){
 	include DIR_FS_CLASSES . 'Remark.class.php';
 	$remark = new Remark('links_manage');
 	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
 }
 // define our link functions
 require(DIR_WS_FUNCTIONS . 'links.php');
 
 /*require(DIR_WS_CLASSES . 'category_tree.php'); 
 $osC_CategoryTree = new osC_CategoryTree; 
 echo $osC_CategoryTree->buildTree();
 echo $osC_CategoryTree->buildCheckBoxOption(0);
 exit;
 */
 //  获取所有产品分类
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.parent_id, c.sort_order, cd.categories_name");
  $data = array();
  while ($categories = tep_db_fetch_array($categories_query)) {
           $data[$categories['parent_id']][$categories['categories_id']] = array('name' => $categories['categories_name'], 'count' => 0, 'pid' => $categories['parent_id']);
  }
  function buildTreeOption($data, $parent_id, $level = 0){
       $root_category_id = 0;
       $max_level = 0;       
       $root_start_string = '';
       $root_end_string = '';
       $parent_start_string = '';
       $parent_end_string = '';
       $spacer_multiplier = 1;
      if (isset($data[$parent_id])) {
       foreach ($data[$parent_id] as $category_id => $category) {
         $category_link = $category_id;      
         
         $result .= '{';
         
         if (isset($data[$category_id])) {
           $result .= $parent_start_string;
         }
    
         if ($level == 0) {
           $result .= $root_start_string;
         }
         $result .= 'id:'.$category_link.', pId:'. $category['pid'] . ', name:"'. $category['name'] .'"';
        
        // $result .= '';

         if ($level == 0) {
           $result .= $root_end_string;
         }

         if (isset($data[$category_id])) {
           $result .= $parent_end_string;
         }
        
        $result .= '},';
        
        

         if (isset($data[$category_id]) && (($max_level == '0') || ($max_level > $level+1))) {
           $result .= buildTreeOption($data ,$category_id, $level+1);
         }
       }
     }
     
     return $result;
      
  }

     
    $search = 'and l.links_status = "2" ';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      if ($keywords != '输入网站名称搜索'){
        $search .= " and ld.links_title like Binary('%" . $keywords . "%')";
      }      
    }
    
    if (isset($_GET['added_start_date']) && tep_not_null($_GET['added_start_date'])){
        $added_start_data = tep_db_input(tep_db_prepare_input($_GET['added_start_date']));
    }    
    if (isset($_GET['added_end_date']) && tep_not_null($_GET['added_end_date'])){
        $added_end_data = tep_db_input(tep_db_prepare_input($_GET['added_end_date']));
        $search .= " AND l.links_date_added >= '". $added_start_data." 00:00:00'  AND l.links_date_added <= '". $added_end_data ." 23:59:59'";
    }
    
    $sortorder = '';
    if($HTTP_GET_VARS['sortorder'] == 'title'){
    $sortorder = ' ld.links_title, l.links_url';
    }else if ($HTTP_GET_VARS['sortorder'] == 'title-desc'){
     $sortorder = ' ld.links_title desc, l.links_url';
    }else {
    $sortorder = ' l.links_id DESC,ld.links_title, l.links_url';
    }

    if(isset($HTTP_GET_VARS['lcPath']) && $HTTP_GET_VARS['lcPath'] !=''){
        $links_query_raw = "select l.links_id, l.links_url, l.links_image_url, l.links_date_added, l.links_last_modified, l.links_status, l.links_clicked, ld.links_title, ld.links_description, l.links_contact_name, l.links_contact_email, l.links_reciprocal_url, l.links_status from " . TABLE_LINKS . " as l, " . TABLE_LINKS_DESCRIPTION . " as ld, " . TABLE_LINKS_TO_LINK_CATEGORIES . " as l2lc  where l.links_id = ld.links_id AND l2lc.links_id = l.links_id AND l2lc.link_categories_id ='".(int)$HTTP_GET_VARS['lcPath']."' AND ld.language_id = '" . (int)$languages_id . "'" . $search . " order by ".$sortorder;

    }else{
        $links_query_raw = "select l.links_id, l.links_url, l.links_image_url, l.links_date_added, l.links_last_modified, l.links_status, l.links_clicked, ld.links_title, ld.links_description, l.links_contact_name, l.links_contact_email, l.links_reciprocal_url, l.links_status from " . TABLE_LINKS . " l left join " . TABLE_LINKS_DESCRIPTION . " ld on l.links_id = ld.links_id where ld.language_id = '" . (int)$languages_id . "'" . $search . " order by ".$sortorder;
    }
   

if (isset($_POST['action']) && $_POST['action'] == 'add_link'){
    if (tep_not_null($_POST['categories_option'])){
        $categories_option = explode(',', tep_db_prepare_input(ajax_to_general_string($_POST['categories_option'])));        
    }
    if (tep_not_null($_POST['mangechk'])){
        $mangechk = explode(',', tep_db_prepare_input(ajax_to_general_string($_POST['mangechk'])));
        $category_ids_array_1 = $mangechk;
    }
    $data_array = array();
    foreach($categories_option as $category){
        foreach($mangechk as $link){            
            $data_array['categories_id'] = $category;
            $data_array['links_id'] = $link;  
            $data_query = tep_db_query("SELECT categories_id,links_id FROM ". TABLE_CATEGORIES_TO_LINKS . " WHERE categories_id='". $category ."' AND links_id='". $link ."'");
            $data_num_rows = tep_db_num_rows($data_query);
            if ($data_num_rows <= 0){
                tep_db_perform(TABLE_CATEGORIES_TO_LINKS, $data_array);
            }
        }
    }        
    echo '1';
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'get_link'){
    if ($_POST['ajax'] == 'true'){
        $category_id = (int)$_POST['category_id'];
        $links_query = tep_db_query("SELECT categories_id,links_id FROM ". TABLE_CATEGORIES_TO_LINKS . " WHERE categories_id='". $category_id ."'");
        $category_links_html = '<ul val="'. $category_id .'" id="ul_'.$category_id.'"><li>'.tep_get_categories_name($category_id).'</li>';
        while($links = tep_db_fetch_array($links_query)){      
            
            if (tep_not_null(tep_get_link_name($links['links_id'], 1))){
                $category_links_html .= '<li><input type="checkbox" id="links_to_category_'.$links['links_id'].'" name="links_to_category[]" value="'.$links['links_id'] .'_' . $links['categories_id'] . '"/>'.tep_get_link_name($links['links_id'], 1).'</li>';  
            }
        }
        
        $category_links_html .='</ul>';
        echo $category_links_html;
    }
    exit;
}


if (isset($_POST['action']) && $_POST['action'] == 'remove_link'){
    if ($_POST['ajax'] == 'true'){
        $category_id = (int)$_POST['category_id'];
        $links = tep_db_prepare_input($_POST['links']);        
        $links = explode(',', $links);
        for($i=0; $i<count($links); $i++){
            $category_to_links = explode('_', $links[$i]);
            tep_db_query("DELETE FROM ". TABLE_CATEGORIES_TO_LINKS ." WHERE `categories_id` = '". $category_to_links[1] ."' AND `links_id` = '". $category_to_links[0] ."'");
        }
        echo $category_id;
        exit;
    }
}
    
    
 ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="JavaScript" src="includes/javascript/calendar.js"></script>
<script language="javascript" src="js2/jquery.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script>
function url_ssl(url){
    var SSL_ = false;
    if(document.URL.search(/^https:\/\//)>-1){
        SSL_ = true;
    }
    var new_url = url;
    if(SSL_==true){
        new_url = url.replace(/^http:\/\//,"https://");
    }else{
        new_url = url.replace(/^https:\/\//,"http://");
    }
    return new_url;
}

</script>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- body //-->
<div style="margin:0 auto; width:1003px;">
    <div>
        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->
    </div>
    <?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('links_manage');
$list = $listrs->showRemark();
?>
    <div class="linksManage">
        <h1>友情链接管理<span>双击友情链接添加到“需要管理的友情链接”模块</span></h1>
        <?php echo tep_draw_form('form_search', 'links_manage.php', tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'get', "id='form_search'"); ?>
        <div class="search">
            <label>Date Added:</label>
            <input type="text" class="time" name="added_start_date"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;" value="<?php echo $added_start_data ?>" />
            至
            <input type="text" class="time" name="added_end_date"  style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"  value="<?php echo $added_end_data ?>"/>					
            <input type="text" class="text"  name="search" onkeydown="this.style.color='#333'" onblur="if(this.value==''){this.value='输入网站名称搜索';this.style.color='#777'}" onfocus="if(this.value!='输入网站名称搜索'){this.style.color='#333'}else{this.value='';this.style.color='#333'}" value="输入网站名称搜索" style="color: #777;"/>
            <input type="submit" class="btn btnGrey" value="搜 索" />
        </div>
        </form>
        <div class="siteList" id="SiteList">
            <ul>
            <?php 
            $links_split = new splitPageResults($HTTP_GET_VARS['page'], 60, $links_query_raw, $links_query_numrows);
            $links_query = tep_db_query($links_query_raw);
            while ($links = tep_db_fetch_array($links_query)) {                
            
            ?>
                <li val="<?php echo $links['links_id'] ?>" title="<?php echo $links['links_title']; ?>"><?php echo cutword($links['links_title'], 26, ' '); ?></li>
            <?php 
            }
            ?>
            </ul>
        </div>
        
        <div class="page">
            <span class="left">
            <?php           
            echo $links_split->display_count($links_query_numrows, 60, $HTTP_GET_VARS['page'], 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> links)'); ?>
            </span>
            <span class="right">
            <?php echo $links_split->display_links($links_query_numrows, 60, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'lID'))); ?>
            </span>
        </div>
        <form name="linkform" id="linkform" action="links_manage.php" method="post">
        <div class="linksChoose">
            <div class="left">
                <h2>需要管理的友情链接</h2>
                <div class="mainCon">
                    <div class="top">
                        <div class="">选择：<a href="javascript:checkAll()">全部</a><a href="javascript:unCheckAll()">无</a></div>
                    </div>
                    <ul id="manage_links">
                                             
                    </ul>
                </div>
                
                <div class="cancel"><input onclick="removeMangeLinks();" id="removeManageLinks" type="button" class="btn btnGrey" value="移 除" /></div>
            </div>
            
            <div class="mid">
                <input type="button" class="btn btnOrange" value="添加>>" onclick="add_links_to_category('linkform');" />
                <p>将选中的链接<br/>添加到<br/>选中的类别中</p>
            </div>
            <div class="right">
                <div class="chooseSort">
                    <h2>网站类别<span>选中类别察看类别下的链接</span></h2>
                    <div class="sortList" id="SortList_For_Category">
                        <dl>
                        <ul id="treeDemo" class="ztree"></ul>
                         <?php //echo $category_checkbox_option; ?>                          
                        </dl>
                    </div>
                </div>
                <div class="choosed">
                    <h2>所选类别下的友情链接</h2>
                    <div class="mainCon">
                        <div class="top">
                            <div class="">选择：<a href="javascript:checkAllR()">全部</a><a href="javascript:unCheckAllR()">无</a></div>
                        </div>
                        <div id="nowlinks">
                        
                        </div>
                    </div>
                    
                    <div class="cancel"><input type="button" class="btn btnGrey" value="移 除" id="removelinks"  onclick="removeLinks();"/></div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div>
        <!-- footer //-->
        <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
        <!-- footer_eof //-->
    </div>
                
</div>
<!-- body_eof //-->
<link rel="stylesheet" href="includes/jquery-1.3.2/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.ztree.core-3.0.min.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.ztree.excheck-3.0.min.js"></script>
<script type="text/javascript">
    
    
        var setting = {
            check: {
                enable: true,
                chkStyle: "checkbox",
                chkboxType: { "Y": "s", "N": "s" }  
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                //beforeClick: beforeClick,
                onClick: onClick
            }

        };

        var zNodes =[
            <?php 
            echo buildTreeOption($data, 0);
            ?>
        ];
        
        var code;
        
        function setCheck() {
            var zTree = jQuery.fn.zTree.getZTreeObj("treeDemo");        
            py = "p";
            sy = "s";
            pn = "p";
            sn = "s";
            type = { "Y":py + sy, "N":pn + sn};
            zTree.setting.check.chkboxType = type;
            showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
        }
        function showCode(str) {
            if (!code) code = jQuery("#code");
            code.empty();
            code.append("<li>"+str+"</li>");
        }
        function onClick(event, treeId, treeNode, clickFlag) {
            var category_id = treeNode.id;
            get_links_by_category(category_id);
        }
        jQuery(document).ready(function(){
            jQuery.fn.zTree.init(jQuery("#treeDemo"), setting, zNodes);
        
        });
        



    jQuery("#SiteList li").hover(function(){
        jQuery(this).addClass("hover");
    },function(){
        jQuery(this).removeClass("hover");
    });
    
    jQuery("#SiteList li").click(function(){
        jQuery("#SiteList li").removeClass("click");
        jQuery(this).addClass("click");
    });
    
    
    jQuery("#SortList_For_Category li").hover(function(event){
        jQuery(this).addClass("hover");
        event.stopPropagation();
    },function(event){
        jQuery(this).removeClass("hover");
        //event.stopPropagation()
    });
    
    jQuery("#SortList_For_Category label").click(function(){
        if(jQuery(this).hasClass("click")){
            //jQuery(this).removeClass("click");              
            //jQuery(this).removeClass("hover");
            return false;
        }else{
            //jQuery(this).addClass("hover"); 
            jQuery("#SortList_For_Category label").filter('.click').removeClass('click');
            jQuery(this).addClass("click");  
        }
    });    
    
    function add_links_to_category(form_id){
        
        var treeObj = jQuery.fn.zTree.getZTreeObj("treeDemo");
        var from = document.getElementById(form_id);
        var links_id = jQuery("input[name='mangechk[]']:checked");
        var category_ids = treeObj.getCheckedNodes(true);   
        if (links_id.length == 0){
            alert('请勾选要添加的友情链接');
            return false;
        }
        if (category_ids.length == 0){
            alert('请勾选要添加友情链接的产品分类!');
            return false;
        }
        var links_id_str = '';
        for (var i=0; i<links_id.length; i++){
            if (i == links_id.length - 1){
                links_id_str += links_id[i].value; 
            }else{
                links_id_str += links_id[i].value + ',';
            }
        }
        var category_id_str = '';
        for (var i=0; i<category_ids.length; i++){
            if (i == category_ids.length - 1){
                category_id_str += category_ids[i].id; 
            }else{
                category_id_str += category_ids[i].id + ',';
            }
        }
        jQuery.ajax({
                url: url_ssl("<?php echo tep_href_link('links_manage.php') ?>"),
                data: "action=add_link&ajax=true&categories_option="+category_id_str+"&mangechk="+links_id_str,
                type: "POST",
                cache: false,
                dataType: "html",
                success: function (data){
                    if (data == 1){
                        alert('编辑成功!');
                    }
                },
                error: function (msg){
                    alert(msg);
                }
                
        })
    }
    
    
    
    jQuery(document).ready(function(){  
        jQuery("#SiteList li").bind("dblclick",function(){  
            
            var links_id = jQuery(this).attr('val');
            var links_title = jQuery(this).attr('title');                        
            var listr = "<li><label><input type='checkbox' name='mangechk[]' id='mange_links_"+ links_id +"' value='"+ links_id +"'/>"+ links_title +"</label></li>";jQuery("#manage_links").append(listr);            
        })  
    });  
    
    
    function get_links_by_category(category_id){
        if (category_id == ''){
            return false;
        }
        
        //var category = category_id.value;
        var category = category_id;
        //if (jQuery(category_id).attr('checked') === true){
            jQuery.ajax({
                url: url_ssl("<?php echo tep_href_link('links_manage.php') ?>"),
                data: "action=get_link&ajax=true&category_id="+category,
                type: "POST",
                cache: false,
                dataType: "html",
                success: function (data){
                    jQuery("#nowlinks").html(data);
                },
                error: function (msg){
                    alert(msg);
                }
                
            })
        //}else{
        //    jQuery("#ul_"+category).remove();
        //}
    }
    
    function removeLinks(){
        var linksArray =  jQuery("input[name='links_to_category[]']:checked");
        var links = '';
        if (linksArray.length > 0){
            for(var i=0; i<linksArray.length; i++){
                if (i == linksArray.length - 1){
                    links += linksArray[i].value;
                }else{
                    links += linksArray[i].value + ',';
                }
            }
            var category_id = jQuery("#nowlinks ul:first").attr('val');
            jQuery.ajax({
                    url: url_ssl("<?php echo tep_href_link('links_manage.php') ?>"),
                    data: "action=remove_link&ajax=true&category_id="+category_id+"&links="+links,
                    type: "POST",
                    cache: false,
                    dataType: "html",
                    success: function (data){
                        if (data > 0){
                            alert('移除成功!');
                            jQuery.ajax({
                                url: url_ssl("<?php echo tep_href_link('links_manage.php') ?>"),
                                data: "action=get_link&ajax=true&category_id="+data,
                                type: "POST",
                                cache: false,
                                dataType: "html",
                                success: function (data){
                                    jQuery("#nowlinks").html(data);
                                },
                                error: function (msg){
                                    alert(msg);
                                }
                    
                            })
                        }
                    },
                    error: function (msg){
                        alert(msg);
                    }
                    
                })
        }
        
    }
    
    function removeMangeLinks(){
        var linksArray =  jQuery("input[name='mangechk[]']:checked");
        for (var i=0; i<linksArray.length; i++){
            var links_id = 'mange_links_' + linksArray[i].value;
            jQuery("#" + links_id).parent().parent().remove();
        }
    }
    
    function checkAll(){
        jQuery("input[name='mangechk[]']").attr("checked", true);
    }
    function checkAllR(){
        jQuery("input[name='links_to_category[]']").attr("checked", true);
    }
    function unCheckAll(){
        jQuery("input[name='mangechk[]']").attr("checked", false);
    }
    function unCheckAllR(){
        jQuery("input[name='links_to_category[]']").attr("checked", false);
    }
</script>


</body>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
</html>
