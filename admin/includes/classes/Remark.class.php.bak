<?php
/**
 * 备注类，用于对某个页面进行备注留言用
 * @author lwkai 2013-10-08
 *
 */
class Remark {
	
	/**
	 * 备注类型
	 * @var string
	 */
	private $type = '';
	
	
	/**
	 * 初始化类型
	 * @param string $type 当前操作的分类备注类型
	 */
	public function __construct($type) {
		$this->type = $type;
	}
	
	/**
	 * 保存备注,返回新插入的数据记录ID
	 * @param array $data 要插入的数据
	 * @return int
	 */
	public function add($data){
		$data['type'] = $this->type;
		$data['time'] = date('Y-m-d H:i:s');
		tep_db_fast_insert('remark', $data);
		return tep_db_insert_id();
	}
	
	/**
	 * 取得备注列表
	 * @param string $limit 取多少记录数
	 * @return array
	 */
	public function getList($limit = 'all'){
		$rtn = array();
		$search = isset($_GET['search']) ? tep_db_input($_GET['search']) : '';
		$sql = "select * from remark where type='" . $this->type . "'";
		if ($search != '') {
			$sql .= ' and remark like "%' . $search . '%"';
		}
		$sql .= " order by id desc";
		if ($limit != 'all' && is_numeric($limit)) {
			$sql .= " limit " . $limit;
		}
		$rs = tep_db_query($sql);
		while($result = tep_db_fetch_array($rs)) {
			$result['remark'] = htmlspecialchars($result['remark']);
			$rtn[] = $result; 
		}
		return $rtn;
	}
	
	/**
	 * 删除备注
	 * @param array|string $id
	 */
	public function del($id){
		$ids = '';
		if (is_array($id)) {
			foreach($id as $key => $val) {
				$id = intval($val);
				$ids .= $id > 0 ? $id . ',' : '';
			}
			$ids = trim($ids,',');
		} elseif (is_string($id) || is_numeric($id)) {
			$id = intval($id);
			$ids = $id > 0 ? $id : '';
		}
		if ($ids) {
			$sql = "delete from remark where type='" . $this->type . "' and id in (" . $ids . ")";
			tep_db_query($sql);
		}
	}
	
	/**
	 * 检测动作是否需要做处理
	 * @param unknown_type $action
	 * @param unknown_type $login_id
	 */
	public function checkAction($action,$login_id){
		switch ($action) {
			case 'G_addremark':
				$data = array();
				$data['remark'] = iconv("utf-8","gb2312",$_POST['G_remark']);
				$data['admin_id'] = $login_id;
				$inserted_id = $this->add($data);
				if ((int)$inserted_id>0)
				{
					echo 'success';
				}else{
					echo 'error: 插入失败';
				}
				exit();
				break;
			case 'G_del':
				$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : false);
				if ($id) {
					$this->del($id);
				}
				tep_redirect($_SERVER['HTTP_REFERER']);
				exit;
				break;
			default:
		}
	}
	
	/**
	 * 显示备注列表HTML
	 */
	public function showRemark($param=''){
		global $can_delete_remark;
		$html = '
		<fieldset>
		<legend style="text-align:left">备注</legend>
		<div>
		<script type="text/javascript">
		/* 添加OP备注按钮 */
		function G_fn_addremark()
		{
			var s=prompt("请输入备注的内容:';
			if (isset($_GET['search']) && $_GET['search']) {
				$html .= '[注意:内容最后别忘记加上你的搜索词(' . $_GET['search'] . ')]';
			}
			$html .= '");
		
			if (s.length==0){ alert("请务必输入备注内容"); return false;}
			//if (s.length>100){ alert("内容长度超过限制"); return false;}';
			if (isset($_GET['search']) && $_GET['search']) {
				$html .= 'if (s.indexOf(\'' . $_GET['search'] . '\') == -1) {
					if(!confirm("\n\n\n\n备注中未发现你加上对应的词！\n\n\n搜索词:' . $_GET['search'] . '\n\n\n你填写的内容："+s+"\n\n\n如果你不加上对应搜索的词，那刷新后就看不到你当前的备注内容。\n\n\n你确定要提交？\n\n\n\n")){
						return false;
					}';
			}
			$html .= '
			//ajax
			var url="?ajax=true&action=G_addremark&sid=" + Math.random() + "&' . $param . '";
		
			jQuery.post(url, {"G_remark": s}, function (data, textStatus){
				data = data.replace(/^\s+/,"").replace(/\s+$/,"");
				if(\'success\' == data ){
					alert("ok");
					window.location.reload();
				}
				else
				{
					alert(data);
				}
			});
			return true;
		}
		
		function G_show_remark(btn){
			var obj = jQuery(\'#remark_table\');
			if(obj.css(\'display\') == \'none\') {
				obj.css(\'display\',\'block\');
				btn.value = "隐藏备注";
			} else {
				obj.css(\'display\',\'none\');
				btn.value = "显示备注";
			}
		}
				
		function unselect(){
			jQuery(\'input[name=\\\'id[]\\\']\').each(function(){
				if($(this).attr(\'checked\')){
					$(this).attr(\'checked\',false);
				} else {
					$(this).attr(\'checked\',true);
				}
			});
		}
		function showall(obj){
			jQuery(\'tr.remark_hide\').toggle();		
		}
		</script>
		<input type="button" value="显示备注" onclick="G_show_remark(this)" /><input type="button" value="添加备注" onclick="G_fn_addremark()"/>
		<div>';
		$list = $this->getList();
		
		$html .= '		<form action="?action=G_del&ajax=true" method="post"><table id="remark_table" style="display:none">
				<tr class="dataTableHeadingRow">
				<td class="dataTableHeadingContent">序号</td>
				<td class="dataTableHeadingContent">备注</td>
				<td class="dataTableHeadingContent">备注工号</td>
				<td class="dataTableHeadingContent">备注时间</td>';
		if ($can_delete_remark) {
			$html .= '<td class="dataTableHeadingContent"><input type="button" value="全选" onClick="jQuery(\'input[name=\\\'id[]\\\']\').attr(\'checked\',true);" />
				<input type="button" value="反选" onClick="unselect()"/></td>';
		}
		$html .= '</tr>';
		$count_i = 0;
		foreach($list as $key => $val) {
			$html .='<tr class="dataTableRow ';
			if ($count_i >= 5) {
				$html .= 'remark_hide" style="display:none"';
			}
			$html .= '">
					<td  class="dataTableContent">' . $val['id'] . '</td>
					<td  class="dataTableContent">' . $val['remark'] . '</td>
					<td  class="dataTableContent">' . tep_get_job_number_from_admin_id($val['admin_id']) . '</td>
					<td  class="dataTableContent">' . $val['time'] . '</td>';
			if ($can_delete_remark) {
				$html .= '<td class="dataTableContent"><input type="checkbox" name="id[]" id="id[]" value="' . $val['id'] . '"/></td>';
			}
			$html .= '</tr>';
			$count_i ++;
		}
		if ($can_delete_remark) {
		$html .='	<tr class="dataTableRow">
				<td class="dataTableContent"></td>
				<td class="dataTableContent"></td>
				<td class="dataTableContent"></td>
				<td class="dataTableContent"></td>
				<td class="dataTableContent"><input type="button"  onClick="if(confirm(\'您确认要批量删除这些备注吗？该操作不可逆！\')){this.form.submit();} else {return false}" value="批量删除"/></td>
				</tr>';
		}
		$html .= '	<tr class="dataTableRow">
				<td class="dataTableContent"><input type="button" onclick="showall(this)" value="显示全部" /></td>
				<td class="dataTableContent"></td>
				<td class="dataTableContent"></td>
				<td class="dataTableContent"></td>
				<td class="dataTableContent"></td>
				</tr>	</table></form>
				</div>
				</div>
		</fieldset>';
		echo $html;
	}
}