<?php
/*
  $Id: providers_users.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id'))
{
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
$prov_id=$_SESSION['providers_id'];
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_USERS);

function duplicate_email($email, $user_id="")//$check_type 0=duplicate email, 1=recore exists
{
	$cond="";
	if(tep_not_null($user_id))
		$cond=" AND providers_id!='".$user_id."'";
	
	$qry_email="SELECT providers_id FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_email_address='".$email."'".$cond;
	$res_email=tep_db_query($qry_email);
	if(tep_db_num_rows($res_email)>0)
		return tep_db_num_rows($res_email);
	else
		return 0;
}

function error_message($error) {
  global $warning;
  switch ($error) {
		case "40":return "<tr class=messageStackError><td>$warning " . ERROR_40_INFORMATION . "</td></tr>";break;
		case "50":return "<tr class=messageStackError><td>$warning " . ERROR_50_INFORMATION . "</td></tr>";break;
    case "80":return "<tr class=messageStackError><td>$warning " . ERROR_80_INFORMATION . "</td></tr>";break;
    default:return $error;
  }
}

// Sets the status of a provider
function tep_set_providers_status($providers_id, $status)
{
	if ($status == '0' || $status == '1')
	{
		return tep_db_query("update " . TABLE_PROVIDERS_LOGIN . " set providers_status = '".$status."' where providers_id = '" . (int)$providers_id . "'");
	}
	else
		return -1;
}

function update_agency_info($arr_values)
{
	if($arr_values['edit_agency_detail']=="1")
	{
		$agency_name=tep_db_input($arr_values["agency_name"]);
		$agency_code=tep_db_input($arr_values["agency_code"]);
		$contactperson=tep_db_input($arr_values["contactperson"]);
		$default_max_allow_child_age=tep_db_input($arr_values["default_max_allow_child_age"]);
		$default_transaction_fee=tep_db_input($arr_values["default_transaction_fee"]);
		
		$arr_sql_data=array('agency_name' => $agency_name,
							'agency_code' => $agency_code,
							'contactperson' => $contactperson,
							'default_max_allow_child_age' => $default_max_allow_child_age,
							'default_transaction_fee' => $default_transaction_fee);
		tep_db_perform(TABLE_TRAVEL_AGENCY, $arr_sql_data, 'update', "agency_id='".tep_db_input($_SESSION["providers_agency_id"])."'");
	}
}

if(tep_not_null($_REQUEST['action']))
{
	$prov_firstname=tep_db_input($_POST['providers_firstname']);
	$prov_lastname=tep_db_input($_POST['providers_lastname']);
	$prov_email_address=tep_db_input($_POST['providers_email_address']);
	$prov_password=$_POST['providers_password'];
	$prov_password_conf=$_POST['providers_password_conf'];
	$prov_password_md5=md5($_POST['providers_password']);
	if($_POST['providers_email_notification']=='on'){
		$providers_email_notification = '1';
	}else{
		$providers_email_notification = '0';
	}
	
	switch($_REQUEST['action'])
	{
		case 'add_user':
			if(tep_not_null($_POST['btnSubmit']))
			{
				if(tep_count_providers_users($_SESSION['providers_agency_id']) >= MAX_PROVIDERS_USERS_LIMIT){
					$error="40";
				}else{
					if ($prov_firstname && $prov_lastname && $prov_email_address && $prov_password && ($prov_password==$prov_password_conf))
					{
						if(duplicate_email($prov_email_address))
						{
							$error="50";
						}
						else
						{
							$prov_status=1;
							$sql_data_array = array('parent_providers_id' => $prov_id,
													'providers_agency_id' => $_SESSION['providers_agency_id'],
													'providers_firstname' => $prov_firstname,
													'providers_lastname' => $prov_lastname,
													'providers_email_address' => $prov_email_address,
													'providers_password' => $prov_password_md5,
													'providers_status' => $prov_status,
													'providers_email_notification' => $providers_email_notification);
							tep_db_perform(TABLE_PROVIDERS_LOGIN, $sql_data_array);
							update_agency_info($_POST);
							tep_mail($prov_email_address, $prov_email_address, PROVIDER_EMAIL_SUBJECT, sprintf(PROVIDER_EMAIL_TEXT, $prov_firstname, HTTP_SERVER ."/". DIR_WS_PROVIDERS, $prov_email_address, $prov_password, STORE_OWNER), PROVIDER_MAIL_FROM, STORE_OWNER_EMAIL_ADDRESS);
							tep_redirect(FILENAME_PROVIDERS_USERS."?".tep_get_all_get_params(array("uID", "action"))."msg=1");
						}
					//} else {$error="20";}
					} else {$error="80";}
				}
			}
			if($error)
			{
				$_GET['action']='add_user';
			}
		break;
		case 'edit_user':
			if(tep_not_null($_POST['btnSubmit']))
			{
				// && tep_validate_email($prov_email_address)
				if($prov_firstname && $prov_lastname && $prov_email_address)
				{
					if(tep_not_null($prov_password) && ($prov_password!=$prov_password_conf))
					{$error="80";}
					else
					{
						if(duplicate_email($prov_email_address, $_POST['uID']))
						{
							$error="50";
						}
						else
						{
							$sql_data_array = array('providers_firstname' => $prov_firstname,
																			'providers_lastname' => $prov_lastname,
																			'providers_email_address' => $prov_email_address,
																			'providers_email_notification' => $providers_email_notification);	
							if(tep_not_null($prov_password))
							{
								$sql_data_array['providers_password'] = $prov_password_md5;
							}
							
							tep_db_perform(TABLE_PROVIDERS_LOGIN, $sql_data_array, 'update', "providers_id='".$_POST['uID']."'");
							update_agency_info($_POST);
							tep_mail($prov_email_address, $prov_email_address, PROVIDER_EMAIL_EDIT_SUBJECT, sprintf(PROVIDER_EMAIL_EDIT_TEXT, $prov_firstname, HTTP_SERVER ."/". DIR_WS_PROVIDERS, $prov_email_address, $prov_password, STORE_OWNER), PROVIDER_MAIL_FROM, STORE_OWNER_EMAIL_ADDRESS);
							tep_redirect(FILENAME_PROVIDERS_USERS."?".tep_get_all_get_params(array("uID", "action"))."msg=1");
						}
					}
				} else {$error="80";}
			}
			if($error)
			{
				$_GET['action']='edit_user';
				$_GET['uID']=$_REQUEST['uID'];
			}
		break;
		case 'delete_user':
			if (isset($_GET['uID']) && $_GET['uID']>0 && $_SESSION['parent_providers_id']=="0") {
				$qry_delete_providers="DELETE FROM ".TABLE_PROVIDERS_LOGIN." WHERE parent_providers_id!=0 AND providers_id='".tep_db_input($_GET['uID'])."'";
				$res_delete_providers=tep_db_query($qry_delete_providers);
				
				tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params(array("uID", "action"))."&msg=2"));
			}
		break;
		case 'setflag':
			if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
			  if (isset($_GET['uID'])) {
				tep_set_providers_status($_GET['uID'], $_GET['flag']);
			  }
			}
			tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params(array("uID", "action"))));
		break;
	}
}

require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);

if(!tep_not_null($_SESSION['providers_agency_id']))
	$_SESSION['providers_agency_id']=0;

if($_SESSION['parent_providers_id']!="0" && $uID!=$_SESSION['providers_id'])
{
	$_GET['action']="";
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr valign="top">
		<td colspan="2" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr valign="top">
			<td class="login_heading" valign="top">&nbsp;
				<b><?php echo HEADING_TEXT; ?></b>
				<?php 
					if($_GET['action']==""){
						echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, 'action=add_user', "SSL").'" style="float:right; font-weight:bold;">'.TEXT_ADD_NEW.'</a>';
					}
				?>
			</td>
		</tr>
<?php 
	if($_GET['msg']=='1')	{
		$succ_msg=MSG_SUCCESS;
	}
	elseif($_GET['msg']=='2')	{
		$succ_msg=MSG_DELETED;
	}
	if(tep_not_null($succ_msg)){?>
		<tr valign="top">
			<td class="successMsg" valign="top" align="center">&nbsp;<?php echo $succ_msg; ?></td>
		</tr>
<?php }
	if(!tep_not_null($error) && $_GET['action']=='add_user'){
		if(tep_count_providers_users($_SESSION['providers_agency_id']) >= MAX_PROVIDERS_USERS_LIMIT){
			$error="40";
		}
	}
	if($error)
	{
		$content=error_message($error);
		echo $content;
	}
	
	if($_GET['action']!='edit_user')
	{
		echo tep_draw_form('search_orders', FILENAME_PROVIDERS_USERS.'?'.tep_get_all_get_params(array("uID", "action", "search", "btnok")), 'get');?>
		<tr class="login">
			<td align="right">
				<?php echo TEXT_SEARCH." ".tep_draw_input_field('search', tep_db_output($_GET['search']));?>
				<?php echo tep_draw_input_field('btnok', TEXT_OK, '', 'submit');?>
			</td>
		</tr>
		</form>
<?php }?>
		<tr valign="top">
        	<td height="100%" valign="top" align="center">
            	<table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#666666" class="login">
				<tr><td>
			<?php 
		$uID=tep_db_input($_GET['uID']);
		if(($_GET['action']=='edit_user' && $uID!="") || $_GET['action']=='add_user')
		{
		if($_GET['action']=='edit_user'){
		$select_provider_info_query = "select * from ".TABLE_PROVIDERS_LOGIN." as p where p.providers_agency_id IN (".$_SESSION['providers_agency_id'].") AND p.providers_id='".$uID."'";
		$select_provider_info_query_res = tep_db_query($select_provider_info_query);
			if(tep_db_num_rows($select_provider_info_query_res) == 0 ){
				tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params(array("uID", "action"))));
			}
		}

			echo tep_draw_form('providers_users', FILENAME_PROVIDERS_USERS.'?'.tep_get_all_get_params(array("uID", "action")), 'post');
			echo tep_draw_hidden_field('uID', $uID);
			if($_REQUEST['action']=="")
				$_REQUEST['action']="add_user";
			
			echo tep_draw_hidden_field('action', $_REQUEST['action']);
			
			$qry_orders_detail = "SELECT p.* FROM ".TABLE_PROVIDERS_LOGIN." p WHERE p.providers_agency_id IN (".$_SESSION['providers_agency_id'].") AND p.providers_id='".$uID."'";
			
			$res_orders_detail=tep_db_query($qry_orders_detail);
			$row_orders_detail=tep_db_fetch_array($res_orders_detail);
			
			$prov_firstname=tep_db_output($row_orders_detail["providers_firstname"]);
			$prov_lastname=tep_db_output($row_orders_detail["providers_lastname"]);
			$prov_email_address=tep_db_output($row_orders_detail["providers_email_address"]);
			
			if($row_orders_detail["providers_email_notification"]=='1' || $row_orders_detail["providers_email_notification"]==''){$email_notification='true';}else{$email_notification='';}			
			$size='size="27"';
			?>
				<table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF" class="login">
					<tr>
						<td width="20%"><strong><?php echo TEXT_USER_FIRSTNAME;?></strong></td>
						<td><?php echo tep_draw_input_field("providers_firstname", $prov_firstname, $size, 'text', false);?></td>
					</tr>
					<tr>
						<td width="20%"><strong><?php echo TEXT_USER_LASTNAME;?></strong></td>
						<td><?php echo tep_draw_input_field("providers_lastname", $prov_lastname, $size, 'text', false);?></td>
					</tr>
					<tr>
						<td width="20%"><strong><?php echo TEXT_USER_EMAIL;?></strong></td>
						<td><?php echo tep_draw_input_field("providers_email_address", $prov_email_address, $size, 'text', false);?></td>
					</tr>
					<tr>
						<td><strong><?php echo TEXT_PROVIDERS_PASSWORD;?></strong></td>
						<td><?php echo tep_draw_input_field("providers_password", "", $size, "password", false);?></td>
					</tr>
					<tr>
						<td><strong><?php echo TEXT_PROVIDERS_PASSWORD_CONF;?></strong></td>
						<td><?php echo tep_draw_input_field("providers_password_conf", "", $size, 'password', false);?></td>
					</tr>
					<tr>
  						<td width="30%"><strong><?php echo TXT_EMAIL_NOTICE;?></strong></td>
						<td><?php echo tep_draw_checkbox_field('providers_email_notification','',$email_notification);?></td>
                    </tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo tep_draw_input_field('btnSubmit', 'Submit', '', 'submit');?>&nbsp;<?php echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params(array("action", "msg", "flag", "btnok", "uID")), "SSL").'">'.TEXT_BACK.'</a>';?></td>
					</tr>
				</table>
			</form>
			<?php
		}else{?>
				<table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF" class="login">
<?php
$exclude_sorting_params=array("sort", "action", "msg", "order", "flag", "btnok");
$HEADING_USER_ID=TABLE_HEADING_USER_ID;
$HEADING_USER_ID.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_id&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_USER_ID.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_id&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_HEADING_NAME=TABLE_HEADING_NAME;
$HEADING_HEADING_NAME.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_name&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_HEADING_NAME.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_name&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_HEADING_EMAIL=TABLE_HEADING_EMAIL;
$HEADING_HEADING_EMAIL.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_email_address&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_HEADING_EMAIL.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_email_address&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_LOGNUM = TABLE_HEADING_LOGNUM;
$HEADING_LOGNUM.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_lognum&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_LOGNUM.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=providers_lognum&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_USERS_STATUS=TABLE_HEADING_USERS_STATUS;
$HEADING_USERS_STATUS.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=nm_providers_status&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_USERS_STATUS.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params($exclude_sorting_params).'&sort=nm_providers_status&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_ACTION=TABLE_HEADING_ACTION;
$HEADING_FLIGHT_INFO=TABLE_FLIGHT_INFO;
?>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent"><?php echo $HEADING_USER_ID; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_HEADING_NAME; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_HEADING_EMAIL; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_LOGNUM; ?></td>
                        <td width="13%" class="dataTableHeadingContent"><?php echo TXT_EMAIL_NOTICE; ?></td>                        
					<?php 
					if($_SESSION['parent_providers_id']=="0")
					{?>
						<td class="dataTableHeadingContent" width="110"><?php echo $HEADING_USERS_STATUS; ?></td>
					<?php }?>
						<td class="dataTableHeadingContent" width="60"><?php echo $HEADING_ACTION; ?></td>
					</tr>
<?php
$qry_providers_users="SELECT p.*, CASE p.providers_status WHEN 1 THEN 'Active' ELSE 'Inactive' END as nm_providers_status, concat_ws(' ', p.providers_firstname, p.providers_lastname) as providers_name FROM ".TABLE_PROVIDERS_LOGIN." p WHERE p.providers_agency_id IN (".$_SESSION['providers_agency_id'].")";

$txt_search=tep_db_input($_GET['search']);
$condition="";
if($_SESSION['parent_providers_id']!="0")
{
	$condition.=" AND p.providers_id='".$_SESSION['providers_id']."'";
}
if(tep_not_null($txt_search))
{
	$condition.=" AND (p.providers_id LIKE '%".$txt_search."%' OR p.providers_firstname LIKE '%".$txt_search."%' OR p.providers_lastname LIKE '%".$txt_search."%' OR p.providers_email_address LIKE '%".$txt_search."%')";
}

$order_by=" ORDER BY ";
if($_GET["sort"]!="")
	$order_by.=$_GET["sort"];
else
	$order_by.="p.providers_id";

if($_GET["order"]!="")
	$order_by.=" ".$_GET["order"];
else
	$order_by.=" DESC";

	if($condition!="")
		$qry_providers_users.= $condition;
	
	$qry_providers_users.=$order_by;
	$db_providers_users = new splitPageResults($qry_providers_users, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, 'p.providers_id', 'page');
	$res_providers_users = tep_db_query($db_providers_users->sql_query);
	while ($row_providers_users = tep_db_fetch_array($res_providers_users))
	{
		echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
		if($row_providers_users["providers_email_notification"]=='1'){$email_notification_list='Yes';}else{$email_notification_list='No';}		
		?>
				<td><?php echo tep_db_output($row_providers_users['providers_id']);?></td>
				<td><?php echo tep_db_output($row_providers_users['providers_name']);?></td>
				<td><?php echo tep_db_output($row_providers_users['providers_email_address']);?></td>
				<td><?php echo tep_db_output($row_providers_users['providers_lognum']);?></td>
				<td align="center"><?php echo $email_notification_list;?></td>                
			<?php 
			if($_SESSION['parent_providers_id']=="0")
			{
			?>
				<td  class="dataTableContent" align="center">
<?php 
		if ($row_providers_users['providers_status'] == '1') {
        	echo tep_image(DIR_WS_ICONS . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, 'action=setflag&flag=0&uID=' . $row_providers_users['providers_id']) . '">' . tep_image(DIR_WS_ICONS . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
	        echo '<a href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, 'action=setflag&flag=1&uID=' . $row_providers_users['providers_id']) . '">' . tep_image(DIR_WS_ICONS . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_ICONS . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }?></td>
			<?php }?>
				<td>
					<?php echo '<a href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params(array("uID", "action", "msg")).'&uID=' . $row_providers_users['providers_id'] . '&action=edit_user', "SSL") . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT, 12, 19) . '</a>'; ?>&nbsp;
					<?php 
					if($row_providers_users['parent_providers_id']!="0" && $_SESSION['parent_providers_id']=="0"){
						echo '<a href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, tep_get_all_get_params(array("uID", "action", "msg")).'&uID=' . $row_providers_users['providers_id'] . '&action=delete_user', "SSL") . '" onclick="return confirm(\''.sprintf(MSG_CONFIRM_DELETED, $row_providers_users['providers_name']).'\');">' . tep_image(DIR_WS_ICONS . 'cross.gif', ICON_DELETE, 16, 16) . '</a>';
					}?>
				</td>
			</tr>
<?php }?>
		<?php 
		if($_SESSION['parent_providers_id']=="0")
		{?>
			<tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="login" valign="top"><?php 
					echo $db_providers_users->display_links_providers($db_providers_users->number_of_rows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], $parameters = '', $page_name = 'page')
					?></td>
					<td align="right">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
		<?php }?>
				</table>
	<?php }?>
				</td>
				</tr>
                </table>
    		</td>
 		</tr>
		</table>
		</td>
	</tr>
</table>
<?php require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_FOOTER);?>