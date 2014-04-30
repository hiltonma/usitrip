<?php
// referer_type 管理页面
require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$search_msn = '';

/**
 * Tree 树型类(无限分类)
 *
 * @author Kvoid
 * @copyright http://kvoid.com
 * @version 1.0
 * @access public
 * @example
 *   $tree= new Tree($result);
 *   $arr=$tree->leaf(0);
 *   $nav=$tree->navi(15);
 */
class Tree {

    private $result;
    private $tmp;
    private $arr;
    private $already = array();

    /**
     * 构造函数
     *
     * @param array $result 树型数据表结果集
     * @param array $fields 树型数据表字段，array(分类id,父id)
     * @param integer $root 顶级分类的父id
     */
    public function __construct($result, $fields = array('id', 'pid'), $root = 0) {
        $this->result = $result;
        $this->fields = $fields;
        $this->root = $root;
        $this->handler();
    }

    /**
     * 树型数据表结果集处理
     */
    private function handler() {
        foreach ($this->result as $node) {
            $tmp[$node[$this->fields[1]]][] = $node;
        }
        krsort($tmp);
        for ($i = count($tmp); $i > 0; $i--) {
            foreach ($tmp as $k => $v) {
                if (!in_array($k, $this->already)) {
                    if (!$this->tmp) {
                        $this->tmp = array($k, $v);
                        $this->already[] = $k;
                        continue;
                    } else {
                        foreach ($v as $key => $value) {
                            if ($value[$this->fields[0]] == $this->tmp[0]) {
                                $tmp[$k][$key]['child'] = $this->tmp[1];
                                $this->tmp = array($k, $tmp[$k]);
                            }
                        }
                    }
                }
            }
            $this->tmp = null;
        }
        $this->tmp = $tmp;
    }

    /**
     * 反向递归
     */
    private function recur_n($arr, $id) {
        foreach ($arr as $v) {
            if ($v[$this->fields[0]] == $id) {
                $this->arr[] = $v;
                if ($v[$this->fields[1]] != $this->root)
                    $this->recur_n($arr, $v[$this->fields[1]]);
            }
        }
    }

    /**
     * 正向递归
     */
    private function recur_p($arr) {
        foreach ($arr as $v) {
            $this->arr[] = $v[$this->fields[0]];
            if ($v['child'])
                $this->recur_p($v['child']);
        }
    }

    /**
     * 菜单 多维数组
     *
     * @param integer $id 分类id
     * @return array 返回分支，默认返回整个树
     */
    public function leaf($id = null) {
        $id = ($id == null) ? $this->root : $id;
        return $this->tmp[$id];
    }

    /**
     * 导航 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回单线分类直到顶级分类
     */
    public function navi($id) {
        $this->arr = null;
        $this->recur_n($this->result, $id);
        krsort($this->arr);
        return $this->arr;
    }

    /**
     * 散落 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回leaf下所有分类id
     */
    public function leafid($id) {
        $this->arr = null;
        $this->arr[] = $id;
        $this->recur_p($this->leaf($id));
        return $this->arr;
    }

}

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
$ref_type_id = intval($_GET['ref_type_id']);
if (tep_not_null($action)) {
    switch ($action) {
        case 'update':
            $ref_type_id = (int) $_POST['ref_type_id'];
            $ref_type_name = tep_db_prepare_input($_POST['ref_type_name']);
            $ref_type_value = tep_db_prepare_input($_POST['ref_type_value']);
            $ref_type_pid = (int) $_POST['ref_type_pid'];
            $sort_num = (int) $_POST['sort_num'];
            if ($ref_type_id) {
                $sql_data_array = array(
                    'customers_ref_type_name' => $ref_type_name,
                    'customers_ref_type_value' => strtolower($ref_type_value),
                    'customers_ref_type_pid' => $ref_type_pid,
                    'sort_num' => $sort_num
                );
                tep_db_perform(TABLE_CUSTOMERS_REF_TYPE2, $sql_data_array, 'update', ' customers_ref_type_id  = "' . $ref_type_id . '"');
                $messageStack->add_session('分类更新成功！', 'success');
                tep_redirect('referer_type.php');
            }
            break;
        case 'insert':

            $ref_type_name = tep_db_prepare_input($_POST['ref_type_name']);
            $ref_type_value = tep_db_prepare_input($_POST['ref_type_value']);
            $ref_type_pid = (int) $_POST['ref_type_pid'];
            $sort_num = (int) $_POST['sort_num'];
            if (tep_not_null($ref_type_name) && tep_not_null($ref_type_value)) {
                $sql_data_array = array(
                    'customers_ref_type_name' => $ref_type_name,
                    'customers_ref_type_value' => strtolower($ref_type_value),
                    'customers_ref_type_pid' => $ref_type_pid,
                    'sort_num' => $sort_num
                );
                tep_db_perform(TABLE_CUSTOMERS_REF_TYPE2, $sql_data_array);
                $messageStack->add_session('分类添加成功!', 'success');
                tep_redirect('referer_type.php');
            }
            break;

        case 'save':
            break;

        case 'deleteconfirm':
            if ((int) $_GET['ref_type_id']) {
                // 删除分类前移动分类下的数据到other项下
                $query = tep_db_query("SELECT customers_id  FROM  " . TABLE_CUSTOMERS . " WHERE 	customers_referer_type=" . (int) $_GET['ref_type_id']);
                while ($customers = tep_db_fetch_array($query)) {
                    $arr[] = $customers['customers_id'];
                }
               
                
                $query = tep_db_query("SELECT customers_ref_type_pid FROM ".TABLE_CUSTOMERS_REF_TYPE2. " WHERE customers_ref_type_id=".(int)$_GET['ref_type_id']);
                $ref_type_pid = tep_db_fetch_array($query);
                $ref_type_pid = $ref_type_pid['customers_ref_type_pid'];
                
                $query = tep_db_query("SELECT customers_ref_type_id FROM ".TABLE_CUSTOMERS_REF_TYPE2. " WHERE customers_ref_type_value='other' AND customers_ref_type_pid='".$ref_type_pid."' LIMIT 1");
                $other = tep_db_fetch_array($query);
                if (!tep_not_null($other)){
                    $query = tep_db_query("SELECT customers_ref_type_id FROM ".TABLE_CUSTOMERS_REF_TYPE2. " WHERE customers_ref_type_value='other' AND customers_ref_type_pid='0' LIMIT 1");
                    $other = tep_db_fetch_array($query);
                }
                if (tep_not_null($other)){
                    $other_id = $other['customers_ref_type_id'];
                    $sql_data_array = array(
                        'customers_referer_type' => $other_id,
                    );
                    for ($i=0;$i<count($arr);$i++){
                        $query = tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', ' customers_id = '.$arr[$i]);
                    }
                }
                tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_REF_TYPE2." WHERE customers_ref_type_id=".(int) $_GET['ref_type_id']);
                $messageStack->add_session('分类删除成功！', 'success');
                tep_redirect(tep_href_link('referer_type.php'));
                
            }
            break;
    }
}

/**
 * 将数组转换为树形结构
 * @param array $arr 需要处理成树形结构的数组
 * @param int $level 遍历的深度，即遍历到第几层结束
 * @param array $parent 处理好之后的数组
 */
function tree($arr, $level = NULL, $parent = NULL) {
    static $i = 0;
    //获取第一层数组
    if ($parent == NULL) {
        foreach ($arr as $key => $value) {
            if ($value['pid'] == 0) {
                $parent[$value['id']] = $value;
                unset($arr[$key]);
            }
        }
        $i++;
    }
    if ($level != NULL && $i == $level) {
        return $parent;
    }

    $arr2 = array();
    //查找当前层的子节点
    foreach ($parent as $k1 => &$v1) {
        foreach ($arr as $k2 => $v2) {
            if ($v1['id'] == $v2['pid']) {
                $v1['child'][$v2['id']] = $v2;
                $arr2[] = &$v1['child'][$v2['id']];
                unset($arr[$k2]);
            }
        }
    }

    //递归调用
    //if(count($arr) > 0) {
    //    $i++;
    tree($arr, $level, $arr2);
    //}

    return $parent;
}

function displayArray2($data, $level, $ref_type_id,$pid) {
    if (is_array($data)) {
        foreach ($data as $arr) {
            
            if ($pid == $arr['id']) {
                $selected = ' selected';
            } else {
                $selected = '';
            }
            if ($level == 0) {

                $select = "<option value='" . $arr['id'] . "' " . $selected . ">" . str_repeat('-', $level) . $i . $arr['name'] . '</option>';
            } else {
                $select = '<option value="' . $arr['id'] . '" ' . $selected . '>|' . str_repeat('-', $level) . $arr['name'] . '</option>';
            }

            echo $select;
            displayArray2($arr['child'], $level + 1, $ref_type_id, $pid);
        }
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

        <script type="text/javascript" src="includes/menu.js"></script>
        <script type="text/javascript" src="includes/big5_gb-min.js"></script>
        <script type="text/javascript" src="includes/general.js"></script>
        <script type="text/javascript">
            //创建ajax对象
            var ajax = false;
            if(window.XMLHttpRequest) {
                ajax = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                try {
                    ajax = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        ajax = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {}
                }
            }
            if (!ajax) {
                window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.') ?>");
            }
  
        </script>
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->

        <!-- body //-->
        <?php
        $query = tep_db_query('SELECT `customers_ref_type_id` , `customers_ref_type_value` , `customers_ref_type_name` , `customers_ref_type_pid`  FROM ' . TABLE_CUSTOMERS_REF_TYPE2);
        $n = 0;
        while ($cate = tep_db_fetch_array($query)) {
            $arr[$n]['id'] = $cate['customers_ref_type_id'];
            $arr[$n]['name'] = $cate['customers_ref_type_name'];
            $arr[$n]['value'] = $cate['customers_ref_type_value'];
            $arr[$n]['pid'] = $cate['customers_ref_type_pid'];
            $n++;
        }
        if (is_array($arr)){           
        
            $tree = new Tree($arr);        
            $trees = $tree->leaf();
        }
        ?>
        <!--<table width="100%" cellspacing="2" cellpadding="2" border="0">
          <tbody>
            <tr>
                <td width="BOX_WIDTH" valign="top">
                    <table width="" cellspacing="1" cellpadding="1" border="0" class="columnLeft"></table>
                </td> 
                <td width="100%" valign="top"><table width="100%" cellspacing="0" cellpadding="2" border="0">
                      <tbody>
                          <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td class="pageHeading">分类管理</td>
                                            <td align="right" class="pageHeading"><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" align="center" cellpadding="1" cellspacing="3" border="0">
                                                  <tr>
                                                    <td class="dataTableContent" width="30%"><b>All Categories</b></td>
                                                    <td width="5%"></td>                                            
                                                  </tr>
                                                  <tr>                                              				
                                                    <td>
<?php
echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/jquery-1.3.2/jquery.cookie.js\"></SCRIPT>\n";
echo "<LINK REL=\"stylesheet\" HREF=\"includes/jquery-1.3.2/jquery.treeview.css\" TYPE=\"text/css\">\n";
echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/jquery-1.3.2/jquery.treeview.js\"></SCRIPT>\n";
?>
                                                      <script type="text/javascript">
                                                        jQuery(function() {
                                                            jQuery("#tree").treeview({
                                                                collapsed: true,
                                                                animated: "medium",
                                                                control:"#sidetreecontrol",
                                                                persist: "location"
                                                            });
                                                        })
        
                                                    </script>
                                                    <div class="treeview">
                                                        <ul id="tree">
                                                            <li><a href="?/index.cfm"><strong>Home</strong></a>
                                                            <ul>
                                                                <li><a href="?/enewsletters/index.cfm">Airdrie eNewsletters </a></li>
                                                                <li><a href="?/index.cfm">Airdrie Directories</a></li>
                                                                <li><a href="?/economic_development/video/index.cfm">Airdrie Life Video</a></li>
        
                                                                <li><a href="?/index.cfm">Airdrie News</a></li>
                                                                <li><a href="?/index.cfm">Airdrie Quick Links</a></li>
                                                                <li><a href="?http://weather.ibegin.com/ca/ab/airdrie/">Airdrie Weather</a></li>
                                                                <li><a href="?/human_resources/index.cfm">Careers</a> | <a href="?/contact_us/index.cfm">Contact Us</a> | <a href="?/site_map/index.cfm">Site Map</a> | <a href="?/links/index.cfm">Links</a></li>
        
                                                                <li><a href="?/calendars/index.cfm">Community Calendar </a></li>
                                                                <li><a href="?/conditions_of_use/index.cfm">Conditions of Use and Privacy Statement</a></li>
                                                                <li><a href="?/index.cfm">I'd like to find out about... </a></li>
                                                                <li><a href="?/index.cfm">Opportunities</a></li>
                                                                <li><a href="?/links/index.cfm">Resource Links</a></li>
                                                                <li><a href="?/index.cfm">Special Notices</a></li>
        
                                                            </ul>
                                                        </ul>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                           </tr>
               </td>
               </tr>
              <tr>
                <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
              </tr>
              <tr>
                <td>
                    <form id="new_category" enctype="multipart/form-data" method="post" action="http://localhost/admin/categories.php?cPath=&amp;cID=&amp;action=new_category_preview" name="new_category"><table cellspacing="0" cellpadding="2" border="0">
               
                   <tbody>
                   <tr>
                    <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                   </tr>
                    <tr>
                        <td class="main">Category Status:</td>
                        <td class="main"><img width="24" height="15" border="0" alt="" src="images/pixel_trans.gif">&nbsp;<input type="radio" checked="" value="1" name="categories_status">&nbsp;Active&nbsp;<input type="radio" value="0" name="categories_status">&nbsp;In Active</td>
                    </tr>
                    <tr>
                        <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                    </tr>
               
                  <tr>
                    <td class="main">Category Name:</td>
                    <td class="main"><img border="0" title=" chinese_tw " alt="chinese_tw" src="/includes/languages/tchinese/images/icon.gif">&nbsp;<input type="text" name="categories_name[1]" onblur="this.value = simplized(this.value);"></td>
                  </tr>
                  <tr>
                    <td colspan="2"><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                  </tr>
                  <tr>
                    <td class="main">Category heading title:</td>
                    <td class="main"><img border="0" title=" chinese_tw " alt="chinese_tw" src="/includes/languages/tchinese/images/icon.gif">&nbsp;<input type="text" name="categories_heading_title[1]" onblur="this.value = simplized(this.value);"></td>
                  </tr>
                  <tr>
                    <td colspan="2"><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                  </tr>
                  
                  <tr>
                    <td class="main">Category URLname:</td>
                    <td class="main">
                                <img border="0" title=" chinese_tw " alt="chinese_tw" src="/includes/languages/tchinese/images/icon.gif">&nbsp;<input type="text" size="50" name="categories_urlname" onblur="this.value = simplized(this.value);">			</td>
                  </tr>
                  <tr>
                    <td colspan="2"><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                  </tr>
                   </tbody>
                  </form>
                </td>
              </tr>
        </table>-->

        <table width="100%" cellspacing="2" cellpadding="2" border="0">
            <tbody><tr>
                    <td width="BOX_WIDTH" valign="top"><table width="" cellspacing="1" cellpadding="1" border="0" class="columnLeft">
                            <!-- left_navigation //-->
                            <!-- left_navigation_eof //-->
                        </table></td>
                    <!-- body_text //-->
                    <td width="100%" valign="top"><table width="100%" cellspacing="0" cellpadding="2" border="0">
                            <tbody><tr>
                                    <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tbody><tr>
                                                    <td class="pageHeading">客户来源 (ref URL type) 分类</td>
                                                    <td align="right" class="pageHeading"><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
                                                </tr>
                                            </tbody></table></td>
                                </tr>
                                <tr>
                                    <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tbody><tr>
                                                    <td valign="top"><table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                            <tbody><tr class="dataTableHeadingRow">
                                                                    <td class="dataTableHeadingContent">Title</td>
                                                                    <td class="dataTableHeadingContent">Value</td>
                                                                    <td align="right" class="dataTableHeadingContent">Action&nbsp;</td>
                                                                </tr>
                                                                <?php

                                                                function displayArray($data, $level, $id) {

                                                                    if (is_array($data)) {
                                                                        foreach ($data as $arr) {
                                                                            $tr = "<tr onclick=\"document.location.href='" . tep_href_link('referer_type.php', 'ref_type_id=' . $arr['id'] . '&action=edit') . "'\" onmouseout=\"rowOutEffect(this)\" onmouseover=\"rowOverEffect(this)\" class=\"";
                                                                            if ($id == $arr['id']) {
                                                                                $tr .= 'dataTableRow';
                                                                                $img = '<a href="' . tep_href_link('referer_type.php', 'ref_type_id=' . $arr['id'] . '&action=edit') . '">
                                <img border="0" src="images/icons/edit.gif" alt="编辑" title="编辑"></a>
                                <img border="0" title=" Info " alt="Info" src="images/icon_arrow_right.gif">';
                                                                            } else {
                                                                                $tr .= 'dataTableRowSelected';
                                                                                $img = '<a href="' . tep_href_link('referer_type.php', 'ref_type_id=' . $arr['id'] . '&action=edit') . '">
                                <img border="0" src="images/icons/edit.gif" alt="编辑" title="编辑"></a>
                                <img border="0" title=" Info " alt="Info" src="images/icon_info.gif">';
                                                                            }
                                                                            $tr .= "\"><td class=\"dataTableContent\">";

                                                                            if ($level == 0) {
                                                                                $tr .= str_repeat('-', $level) . $i . $arr['name'];
                                                                            } else {
                                                                                $tr .= '|' . str_repeat('-', $level) . $i . $arr['name'];
                                                                            }

                                                                            $tr .= '</td><td class="dataTableContent">' . $arr['value'] . '</td><td align="right" class="dataTableContent"><a href="' . tep_href_link('referer_type.php', 'ref_type_id=' . $arr['id'] . '&action=edit') . '">' . $img . '</a>&nbsp;</td>
              </tr>';
                                                                            echo $tr;
                                                                            displayArray($arr['child'], $level + 1, $id);
                                                                        }
                                                                    }
                                                                }

                                                                displayArray($trees, 0, $ref_type_id);
                                                                ?>
                                                                <tr>
                                                                
                                                                    <td colspan="3"><table width="100%" cellspacing="0" cellpadding="2" border="0">        

                                                                            <tr>
                                                                                <td align="right" colspan="2">
                                                                                    <br/>
                                                                                    <a href="<?php echo tep_href_link('referer_type.php', 'action=insert') ?>">
                                                                                        <img border="0" title=" New Language " alt="New Language" src="includes/languages/schinese/images/buttons/button_new.gif">
                                                                                    </a>
                                                                                </td>
                                                                            </tr>

                                                                        </table></td>

                                                                </tr>
                                                            </tbody></table></td>
                                                    <td width="25%" valign="top">
                                                        <?php
                                                        if (tep_not_null($action)) {
                                                            switch ($action) {
                                                                case 'edit':
                                                                    $ref_type_id = intval($_GET['ref_type_id']);
                                                                    $ref_query_raw = tep_db_query("select * from " . TABLE_CUSTOMERS_REF_TYPE2 . " where customers_ref_type_id = $ref_type_id");
                                                                    $ref_type_array = tep_db_fetch_array($ref_query_raw);
                                                                    echo tep_draw_form('ref_type_form', 'referer_type.php', 'action=update', 'post');
                                                                    ?>
                                                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                                        <tbody><tr class="infoBoxHeading">
                                                                                <td class="infoBoxHeading"><b>修改</b></td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    分类名称:
            <?php
            echo tep_draw_input_field('ref_type_name', $ref_type_array['customers_ref_type_name']);
            echo tep_draw_hidden_field('ref_type_id', $ref_type_array['customers_ref_type_id']);
            ?>                           
                                                                                </td>
                                                                            </tr>   
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    判断参数值：
            <?php echo tep_draw_input_field('ref_type_value', $ref_type_array['customers_ref_type_value']) ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    分类：
                                                                                    <select name="ref_type_pid">
                                                                                        <option value='0'>添加一级分类</option>
            <?php displayArray2($trees, 0, $ref_type_id, $ref_type_array['customers_ref_type_pid']); ?>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    排序：
            <?php echo tep_draw_input_field('sort_num', $ref_type_array['sort_num']) ?>                           
                                                                                </td>
                                                                            </tr>
                                                                            <tr>                  
                                                                                <td align="center" class="infoBoxContent"><br/><input type="image" border="0" title=" Update " alt="Update" src="includes/languages/schinese/images/buttons/button_update.gif">
                                                                                    <a href="<?php echo tep_href_link('referer_type.php', 'ref_type_id=' . $ref_type_id . '&action=delete') ?>"><img border="0" title=" Delete " alt="Delete" src="includes/languages/schinese/images/buttons/button_delete.gif"></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent"><br>提示信息</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent"><br>Date Added: </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </form>
            <?php
            break;
        case 'insert':
            echo tep_draw_form('ref_type_form', 'referer_type.php', 'action=insert', 'post');
            ?>
                                                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                                        <tbody><tr class="infoBoxHeading">
                                                                                <td class="infoBoxHeading"><b>增加分类</b></td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    分类名称:
            <?php echo tep_draw_input_field('ref_type_name'); ?>                           
                                                                                </td>
                                                                            </tr>   
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    判断参数值：
            <?php echo tep_draw_input_field('ref_type_value', $ref_type_array['customers_ref_type_value']) ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    分类：
                                                                                    <select name="ref_type_pid">
                                                                                        <option value="0">添加一级分类</option>
            <?php displayArray2($trees, 0, 0,0); ?>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    <br/>
                                                                                    排序：
            <?php echo tep_draw_input_field('sort_num') ?>                           
                                                                                </td>
                                                                            </tr>
                                                                            <tr>                  
                                                                                <td align="center" class="infoBoxContent"><br/><input type="image" border="0" title=" Update " alt="Update" src="includes/languages/schinese/images/buttons/button_insert.gif"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent"><br>提示信息</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="infoBoxContent"><br>Date Added: </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </form>
            <?php
            break;
        case 'delete':
            $ref_type_id = (int) $_GET['ref_type_id'];
            ?>
                                                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                                        <tbody><tr class="infoBoxHeading">
                                                                                <td class="infoBoxHeading"><b>删除分类</b></td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="infoBoxContent">
                                                                                    你确定要删除此分类?                        
                                                                                </td>
                                                                            </tr>   
                                                                            <tr>                  
                                                                                <td align="center" class="infoBoxContent"><br/>
                                                                                    <a href="<?php echo tep_href_link('referer_type.php', 'ref_type_id=' . $ref_type_id . '&action=deleteconfirm') ?>"><img border="0" title=" Delete " alt="Delete" src="includes/languages/schinese/images/buttons/button_delete.gif"></a>
                                                                                </td>
                                                                            </tr>



                                                                        </tbody></table>
            <?php
            break;
    }
}
?>
                                                    </td>
                                                </tr>
                                            </tbody></table></td>
                                </tr>
                            </tbody></table></td>
                    <!-- body_text_eof //-->
                </tr>
            </tbody></table>


        <!-- body_eof //-->

        <!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
        <!-- footer_eof //-->
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
