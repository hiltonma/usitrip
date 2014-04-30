<?php
/**
* 细节权限分配
* 请注意：
* 会计：$login_groups_id = 11
* 操作员，商务经理 $login_groups_id = 42
* 主管，副主管 $login_groups_id = 5,48
* 普通客服 $login_groups_id = 7
* 5,7,11,42

* 销售(7)：不能发送地接，不能做ID:100002, 100083 ；不能查看发票PDF，不能减价格,不能修改电子凭证行程内容以下的内容。能：os_groups_id=13,os_groups_id=1,os_groups_id=4，os_groups_id=5的100126,os_groups_id=6,os_groups_id=7,os_groups_id=15,os_groups_id=3中的100125

* 销售主管(5)：在销售的基础上+下单给地接os_groups_id=2 ，可以查看发票PDF，可以修改订单路线的游客信息（可以增加人数，修改游客名字），房间数

* OP操作员(42):在销售主管的基础上+1.审核发送电子参团凭证（100002）2.查看地接发送的PDF文档 3.发送行程确认信（100083）4.可以修改step2的状态，能：os_groups_id=12的100096，100097。
* 财务(11)：在op的基础上+1.更改付款状态2.减少费用 3.发送地接商取消信息 以及文件夹8财务取消及换团办理（所有的订单状态都可以设置）
*
* 关于与地接交流的信息框：销售不能发只能看，财务可以使用全部选项，销售主管和OP能用除了"取消"之外的所有选项。
* 产品(20)：
* **********************************************************************************************************关于设置订单状态权限在edit_orders.php页面进行具体设置
*/

//新的权限分配start{

$access_full_edit = 'false';				//订单完全权限
$access_order_cost = 'false';				//修改成本
$allow_cost_gp_on_other_reports = false;	//是否可以查看其它报表的成本
$allow_travle_agency_edit = false;			//是否可以修改供应商资料done
//for edit_orders.php
$allow_message_to_provider = false;			//是否可以发送信息(下单)给地接done
$allow_set_cancel_to_provider = false;		//是否可以给地接发取消下单的信息done
$can_sub_price = false;						//是否可以减少费用done
$can_send_eticket = false;					//是否可以发送电子参团凭证done
$can_view_invoice = false;					//是否可以看发票和pdf文件done
$can_edit_invoice = false;					//是否可以修改发票内容
$can_edit_guest_room = false;				//是否可以修改订单路线的游客信息（可以增加人数，修改游客名字），房间数done
$can_edit_eticket_itinerary = false;		//是否可以修改电子凭证行程内容以下的内容done
$can_delete_orders_products = false;		//是否可以删除订单中的产品done
$can_use_orders_status_groups = array();	//可以用哪些订单状态组，用英文短号隔开
$can_use_orders_status = array();			//可以用哪些订单状态，用英文短号隔开
$can_edit_orders_points = false;			//是否可以修改用户订单的积分赠送
$can_update_price = false;					//是否可以修改产品价格，Sofia要求只有财务人员能改价格（产品经理也可以）
$_tables = ' orders_status ';
$_where = ' WHERE orders_status_display ="1" AND orders_status_id NOT IN(100137) ';	//100137是系统自动催款的状态
$can_check_question_answers = false;
$can_edit_op_special_list = false;			//是否可以编辑 OP需审核的订单列表 | 操作员认为有问题的订单
$can_send_sms_to_customers = false;			//是否可以发送短信给客人
$can_edit_visa_orders = false;				//是否可以编辑签证订单
$can_edit_customers_points = false;			//是否可以编辑客户积分
$can_edit_customers_info = false;			//是否可以编辑客户资料
$can_confirm_departure_location = false;	//是否可以审核上车地址
$can_confirm_order_products_departure_histoty = false;	//是否可以审核订单客人更新的房间人数姓名等信息
$can_view_update_info_payment_method_table = false;		//是否可以查看订单详细页面中的支付方式页面
$can_set_orders_is_blinking = false;					//是否可以把ID闪烁的订单设置为不闪烁
$can_set_orders_accounting_todo_done = false;			//财务可以将订单need_accounting_todo的值设置为已完成处理
$can_send_visa_invitations = false; 					//能否发送邀请函
$can_hide_orders_products = false;						//能否隐藏订单产品
$can_see_non_payment_orders = false;					//能否看未付款的非自己(销售)的订单
$can_copy_sensitive_information = false;				//能否复制订单中的敏感信息
$can_show_warning_op = false;							//是否显示op注意的内容
$can_show_warning_order_up_no_change_status = false;	//是否显示及取消设置未产生费用，有更新的订单
$can_update_orders_price = false;						//是否可以修改订单的价格
$can_charge_captured_information = false;				//是否可以处理Charge Captured Information的信息
$can_see_orders_coupon_code = false;					//是否可以查看折扣券编码
$show_customer_info_permission = false;					//能查看订单详细页中的客户信息
$show_customer_info_email_permission = false;			//能查看订单详细页中的客户信息下面的电子邮箱
$can_see_user_it_cell_phone = false;					//能查看电子凭证用户联系电话
$can_update_question_date=false;						//能否更新问题，回复的时间
$can_edit_allow_pay_min_money = false;					//能否设置订单每次支付的最小金额
$can_see_eticket_providers_info = false;				//能否查看订单电子票编辑界面中的供应商信息
$can_see_customers_email_full_address = false;			//能否查看用户完全的电子邮件地址
$can_see_question_answers_email = false;				//问答中能看用户完全的电子邮箱
$can_edit_jiebantongyou_pay = false;					//是否能编辑结伴同游订单中的各个客户的付款信息
$can_view_invoice_total = false;						//是否能看发票总金额信息
$skip_set_order_up_no_change_price = false;				//是否跳过【未产生费用，有更新的订单】的记录
$can_see_orders_phone_number=false;                    //是否可以查看订单的短信记录手机号码

$products_detail_permissions = array();		//产品页面细节权限
$products_detail_permissions['CategoriesProductsList']['权限'] = '查看';	//产品列表有查看功能
$products_detail_permissions['CategoriesProductsList']['不能操作'] =  false;	//产品列表不能操作右边的按钮
$products_detail_permissions['Categorization']['不能修改产品名称和URL地址'] = false;
$products_detail_permissions['RoomAndPrice']['能看不能编'] = false;	//Room and Price 不能使用更新按钮
$products_detail_permissions['Attribute']['不能更新'] = false;		//不能使用更新按钮
$products_detail_permissions['Attribute']['不能输入价格'] = false;
$products_detail_permissions['Operation']['不能输入价格'] = false;
$products_detail_permissions['Operation']['只能更新发出结束途经城市景点'] = false;
$products_detail_permissions['Content']['不能编辑_显示的类型标签'] = false;
$products_detail_permissions['Content']['只能编辑_显示的类型标签'] = false;
$products_detail_permissions['Eticket']['能看不能编'] = false;
$products_detail_permissions['ImageVideo']['能看不能编'] = false;
$products_detail_permissions['MetaTag']['能看不能编'] = false;
$products_detail_permissions['HotelsNearbyAttractions']['能看不能编'] = false;
$products_detail_permissions['HotelsTransferServices']['能看不能编'] = false;

$products_attributes_permissions['能看不能编'] = false;		//产品所有属性资料页面权限products_attributes_tour_provider.php
$tour_provider_regions_permissions['能看不能编'] = false;	//接送地点管理权限tour_provider_regions.php
$hotel_permissions['能看不能编'] = false;					//酒店管理权限hotel.php
$buy_two_get_one_permissions['能看不能编'] = false;			//买二送一（买二送二）管理buy_two_get_one.php
$can_top_question_answers = false;							//可以置顶提问回答
$can_copy_question_answers = false;							//可以拷贝提问
$can_top_salestrack = false;								//是否拥有销售跟踪的最高权限
$can_top_sms_contact_guest = false;							//是否拥有查发短信的最高权限
$can_copy_affiliate_sensitive_information = false;			//是否可以复制销售联盟编辑页面的敏感信息
$can_add_score_to_customer_service = false;					//是否可以给客服人员加分，考核用
$can_one_update = false; 									//上车地点一键更新
$can_packet_group = false;									// 是否有权限设置某一个订单为包团订单，即团购订单 by lwkai added 2013-05-15
$can_js_show_customer_payment_history = false;				//是否需要复制一份付款记录到订单详细页面底部
$can_set_step3_set_know = false;							//是否可以设置已经知道某订单产品是step3中添加的，财务有此权限
$can_close_again_paid_orders = false;						//是否可以取消设置“再次付款需更新订单”
$can_edit_orders_products_name = false;						//是否可以修改订单中的产品名称(OP可以)
//===============常见问题========================================
$can_add_problem_score = false;								//是否可以给常见问题提交人加分
$can_add_problem_answer_score = false;						//是否可以给常见问题的回复者加分
$can_edit_problem=false;									//是否可以编辑常见问题
$can_edit_problem_answer=false;								//是否可以编辑常见问题答案
$can_edit_problem_status=false;								//是否可以编辑常见问题的状态
//=============== 专家团的权限配置 start =========================
$can_expert_group_add = false;   							//添加
$can_expert_group_edit = false;								//编辑
$can_expert_group_open = false;								//启用专家
$can_expert_group_del = false;								//删除
// =============== 专家团配置 end ===========================
$can_confirm_cellphone_updated_history = false;				//是否可以审核当日游客手机更新历史
//================ 签证权限 start =================================
$can_only_see_visa = false; 							    //签证只能看自己的订单 by lwkai added 2013-09-03
//================ 签证权限 end  ==================================

//================ 常规产品价格维护 start =========================
$can_edit_regular_price = -1;								//常规产品权限，-1没有任何操作权 1 商品编辑权，2商品经理权 ，2 财务权 by lwkai added 2013-09-11
//================ 常规产品价格维护 end  ==========================

//================ 产品开关权限 start =============================
$can_open_or_close_products = false;						//能否打开/关闭产品
//================ 产品开关权限 end ===============================

//================ 备注模块权限 start =============================
$can_delete_remark = false; 								//能否删除备注
//================ 备注模块权限 end ===============================

switch($login_groups_id){
	//顶级管理员 {
	case '43':
	case '1':
		$can_see_orders_phone_number=true;
		$access_full_edit = 'true';
		$access_order_cost = 'true';
		$allow_travle_agency_edit = true;
		$allow_message_to_provider = true;
		$allow_set_cancel_to_provider = true;
		$can_sub_price = true;
		$can_send_eticket = true;
		$can_view_invoice = true;
		$can_edit_invoice = true;
		$can_edit_guest_room = true;
		$can_edit_eticket_itinerary = true;
		$can_delete_orders_products = true;
		$can_edit_orders_points = true;
		$can_update_price = true;
		for($i=1; $i< 20; $i++){
			$can_use_orders_status_groups[]= $i;			
		}
		$can_check_question_answers = true;
		$products_detail_permissions['CategoriesProductsList']['权限'] = 'all';
		$can_edit_op_special_list = true;
		$can_send_sms_to_customers = true;
		$can_edit_visa_orders = true;
		$can_edit_customers_points = true;
		$can_edit_customers_info = true;
		$can_confirm_departure_location = true;
		$can_confirm_order_products_departure_histoty = true;
		$can_view_update_info_payment_method_table = true;
		$can_set_orders_accounting_todo_done = true;
		$can_send_visa_invitations = true;
		$can_hide_orders_products = true;
		$can_see_non_payment_orders = true;
		$can_copy_sensitive_information = true;
		$can_show_warning_op = true;
		$can_show_warning_order_up_no_change_status = true;
		$can_update_orders_price = true;
		$can_charge_captured_information = true;
		$can_see_orders_coupon_code = true;
		$show_customer_info_permission=true;
		$show_customer_info_email_permission = true;
		$can_see_user_it_cell_phone=true;
		$can_update_question_date=true;
		$can_edit_allow_pay_min_money = true;
		$can_see_eticket_providers_info = true;
		$can_see_customers_email_full_address = true;
		$can_top_salestrack = true;
		$can_top_sms_contact_guest = true;
		$can_copy_affiliate_sensitive_information = true;
		$can_see_question_answers_email = true;
		$can_add_score_to_customer_service = true;
		$can_one_update = true;
		$can_edit_jiebantongyou_pay = true;
		$can_packet_group = true;
		$can_js_show_customer_payment_history = true;
		$can_set_step3_set_know = true;
		$can_close_again_paid_orders = true;
		$can_edit_orders_products_name = true;
		$can_expert_group_add = true;   							//添加
		$can_expert_group_edit = true;								//编辑
		$can_expert_group_open = true;								//启用专家
		$can_expert_group_del = true;
		$can_confirm_cellphone_updated_history = true;
		$can_view_invoice_total = true;
		$can_open_or_close_products = true;
		$can_delete_remark = true;
		$can_edit_problem=true;$can_edit_problem_answer=true;$can_edit_problem_status=true;
		$can_add_problem_score = true;
		$can_add_problem_answer_score = true;
	break;
	//顶级管理员 }
	//财务 {
	case '11':
		$access_order_cost = 'true';
		$allow_message_to_provider = true;
		$allow_set_cancel_to_provider = true;
		$allow_travle_agency_edit = true;
		$can_sub_price = true;
		$can_send_eticket = true;
		$can_view_invoice = true;
		$can_edit_invoice = true;
		$can_edit_guest_room = true;
		$can_edit_eticket_itinerary = true;
		$can_edit_orders_points = true;
		$can_update_price = true;
		$access_full_edit = 'true';
		for($i=1; $i< 20; $i++){
			$can_use_orders_status_groups[]= $i;			
		}
		$_where .= ' AND os_groups_id in('.implode(',', $can_use_orders_status_groups).') ';
		$can_edit_visa_orders = true;
		$can_edit_customers_points = true;
		$can_view_update_info_payment_method_table = true;
		$can_set_orders_accounting_todo_done = true;
		$can_send_visa_invitations = true;
		$can_hide_orders_products = true;
		$can_see_non_payment_orders = true;
		$can_update_orders_price = true;
		$can_charge_captured_information = true;
		$can_see_orders_coupon_code = true;
		$can_copy_sensitive_information = true;
		$show_customer_info_permission=true;
		$show_customer_info_email_permission = true;
		$can_edit_allow_pay_min_money = true;
		$can_show_warning_op = true;
		$can_show_warning_order_up_no_change_status = true;
		$can_see_eticket_providers_info = true;
		$can_top_salestrack = true;
		$can_copy_affiliate_sensitive_information = true;
		$can_edit_op_special_list = true;
		$can_confirm_departure_location = true;
		$can_edit_jiebantongyou_pay = true;
		$can_packet_group = true;
		$can_js_show_customer_payment_history = true;
		$can_see_user_it_cell_phone = true;
		$can_add_score_to_customer_service = true;		
		$allow_message_to_provider = true;
		$can_confirm_order_products_departure_histoty = true;
		$can_set_step3_set_know = true;
		$can_close_again_paid_orders = true;
		$can_confirm_cellphone_updated_history = true;
		$can_view_invoice_total = true;
		$can_edit_regular_price = 2;
		$skip_set_order_up_no_change_price = true;
		$can_edit_problem=true;$can_edit_problem_answer=true;$can_edit_problem_status=true;
		$can_add_problem_score = true;
		$can_add_problem_answer_score = true;
	break;
	//财务 }
	//财务助理2 {
	case '53':	//此组有OP的所有权限以及一小部分的财务权限。Sofia的原文如下：“把Message to Provider:中的“请取消”与Group:中的“9.财务取消及换团办理” 及10， 都给这个财务2开通权限，其他的财务权限全部取消，只保留OP权限，谢谢！”
		$access_order_cost = 'true';
		$can_sub_price = true;
		$allow_set_cancel_to_provider = true;
		$can_use_orders_status_groups = array(8,12);
	//财务助理2 }
	//OP操作员 {
	case '42':
		$access_order_cost = 'true';
		$access_full_edit = 'false';
		$allow_message_to_provider = true;
		$can_send_eticket = true;
		$can_view_invoice = true;
		$can_edit_guest_room = true;
		$can_edit_eticket_itinerary = true;
		$can_use_orders_status_groups = array_merge($can_use_orders_status_groups, array(1,2,4,6,7,13,14,15));
		$can_use_orders_status_groups[] = 3;
		$can_use_orders_status_groups[] = 5;
		$_where .= ' AND (os_groups_id in('.implode(',', $can_use_orders_status_groups).') || orders_status_id in(100096,100097)) ';
		$can_use_orders_status_groups[] = 12;	//组能看但不能使用里面的状态，所以排到这里
		$can_edit_op_special_list = true;
		$can_edit_visa_orders = true;
		$can_confirm_departure_location = true;
		$can_confirm_order_products_departure_histoty = true;
		$can_view_update_info_payment_method_table = true;
		$can_set_orders_is_blinking = true;
		$can_see_non_payment_orders = true;
		$can_show_warning_op = true;
		$can_show_warning_order_up_no_change_status = true;
		$can_update_orders_price = true;
		$can_see_user_it_cell_phone=true;
		$show_customer_info_permission=true;
		$can_see_eticket_providers_info = true;
		if($login_id == 222 ){	//只有吕姐有此权限
			$can_top_salestrack = true;
		}
		$can_edit_jiebantongyou_pay = true;
		$can_packet_group = true;
		$can_close_again_paid_orders = true;
		$can_edit_orders_products_name = true;
		$can_confirm_cellphone_updated_history = true;
		$skip_set_order_up_no_change_price = true;
		$can_edit_problem=true;$can_edit_problem_answer=true;$can_edit_problem_status=true;
		$can_add_score_to_customer_service=true;
		$can_add_problem_score = true;
		$can_add_problem_answer_score = true;
	break;
	//OP操作员 }
	//销售主管 {
	case '5':	//销售主管
		$can_send_eticket = true;					//主管在2013-04-01后也可以发电子参团凭证了
		$can_see_non_payment_orders = true;
		$show_customer_info_permission = true;
		$show_customer_info_email_permission = true;
		$can_see_user_it_cell_phone=true;
		$can_see_eticket_providers_info = true;
		$can_use_orders_status_groups = array(15);	//主管比副主管多一个这个状态
		$can_add_score_to_customer_service = true;	//销售主管可以给副主管、普通客服等加分
		$can_edit_jiebantongyou_pay = true;
		$can_packet_group = true;
		$can_close_again_paid_orders = true;
		$can_confirm_cellphone_updated_history = true;
		$can_show_warning_order_up_no_change_status = true;
		$can_edit_problem=true;$can_edit_problem_answer=true;$can_edit_problem_status=true;
		$can_add_problem_score = true;
		$can_add_problem_answer_score = true;
	case '48':	//销售副主管。注意：副主管和主管的权限有很大差别哟，一定注意呀！！！
		$allow_message_to_provider = true;
		$can_edit_guest_room = true;
		$can_edit_eticket_itinerary = true;
		$can_use_orders_status_groups=array_merge($can_use_orders_status_groups,array(1,2,4,6,7,13));
		//unset($can_use_orders_status_groups[6]);//2013.3.4 WTJ取消掉编号15  8.未付款订单需撤销
		$_where5 =  ' AND (os_groups_id in('.implode(',', $can_use_orders_status_groups).') || orders_status_id in(100125,100126,100141)) ';
		$_where48 =  ' AND (os_groups_id in('.implode(',', $can_use_orders_status_groups).') || orders_status_id in(100125,100126)) ';
		
		$can_use_orders_status_groups[] = 3;	//组能看但不能使用里面的状态，所以排到这里
		$can_use_orders_status_groups[] = 5;
		$can_use_orders_status_groups[] = 8;		
		$can_edit_visa_orders = true;
		$can_confirm_departure_location = true;
		$can_confirm_order_products_departure_histoty = true;
		$can_update_orders_price = true;
		if($login_groups_id == '48'){
			$can_see_non_payment_orders = false;	//这里与主管有区别
			$show_customer_info_permission = false;
			$can_see_user_it_cell_phone = false;
			$can_see_eticket_providers_info = false;
			$_where .= $_where48;
		}else{
			$_where .= $_where5;
		}
		$can_close_again_paid_orders = true;
		$can_confirm_cellphone_updated_history = true;
		
	break;
	//销售副主管 }
	//销售 {
	case '56':
	case '7':
		//$can_edit_guest_room = true;
		//$can_sub_price = true;
		//os_groups_id=13,os_groups_id=1,os_groups_id=4，os_groups_id=5的100126,os_groups_id=6,os_groups_id=7,os_groups_id=15,os_groups_id=3中的100125
		$can_use_orders_status_groups = array(1,4,6,7,13);//2013.3.4 WTJ取消掉编号15  8.未付款订单需撤销
		$_where .= ' AND (os_groups_id in('.implode(',', $can_use_orders_status_groups).') || orders_status_id in(100125,100126)) ';
		$can_use_orders_status_groups[] = 3;	//组能看但不能使用里面的状态，所以排到这里
		$can_use_orders_status_groups[] = 5;
		$can_see_non_payment_orders = false;
		$can_update_orders_price = true;
		$can_edit_guest_room = true;
	break;
	//销售 }
	// 签证专员 {
	case '47':
		//$can_edit_guest_room = true;
		//$can_sub_price = true;
		//os_groups_id=13,os_groups_id=1,os_groups_id=4，os_groups_id=5的100126,os_groups_id=6,os_groups_id=7,os_groups_id=15,os_groups_id=3中的100125
		$can_use_orders_status_groups = array(1,4,6,7,13);	//签证专员没有15的功能
		$_where .= ' AND (os_groups_id in('.implode(',', $can_use_orders_status_groups).') || orders_status_id in(100125,100126)) ';
		$can_use_orders_status_groups[] = 3;	//组能看但不能使用里面的状态，所以排到这里
		$can_use_orders_status_groups[] = 5;
		$can_edit_visa_orders=true;
		$can_send_visa_invitations = true;
		$can_only_see_visa = true; // 签证只查看自己的订单
	break;
	//签证专员 }
	//产品维护专员(贾颖...)
	case '20': 	
		$allow_travle_agency_edit = true;
		$access_full_edit = 'true';	//在产品页面必须有此功能才能编辑或显示成本(如果设置了['不能输入价格']选项的话产品人员不一定能编辑)
		$_where .= ' AND os_groups_id in(0) ';	//产品基本不能使用任何订单状态
		//$products_detail_permissions['CategoriesProductsList']['不能操作'] = true;
		$products_detail_permissions['CategoriesProductsList']['权限'] = 'all';
		//$products_detail_permissions['RoomAndPrice']['能看不能编'] = true;
		//$products_detail_permissions['Attribute']['不能更新'] = true;
		$products_detail_permissions['Operation']['不能输入价格'] = true;
		//$can_update_price = true;
		//$products_detail_permissions['Operation']['只能更新发出结束途经城市景点'] = true;
		//$products_detail_permissions['Content']['只能编辑_显示的类型标签'] = true;
		//$products_detail_permissions['Eticket']['能看不能编'] = true;
		//$products_detail_permissions['ImageVideo']['能看不能编'] = true;
		//$products_detail_permissions['HotelsNearbyAttractions']['能看不能编'] = true;
		//$products_detail_permissions['HotelsTransferServices']['能看不能编'] = true;
		//$products_attributes_permissions['能看不能编'] = true;
		//$tour_provider_regions_permissions['能看不能编'] = true;
		//$hotel_permissions['能看不能编'] = true;
	break;
	//产品编辑
	case '45': 	
		$allow_travle_agency_edit = true;
		$access_full_edit = 'true';				//在产品页面必须有此功能才能编辑或显示成本(如果设置了['不能输入价格']选项的话产品人员不一定能编辑)
		$_where .= ' AND os_groups_id in(0) ';	//产品基本不能使用任何订单状态
		$products_detail_permissions['CategoriesProductsList']['权限'] = 'all';		
		$products_detail_permissions['Categorization']['不能修改产品名称和URL地址'] = true;
		$products_detail_permissions['Attribute']['不能输入价格'] = true;
		//$can_update_price = true;
		$products_detail_permissions['Operation']['不能输入价格'] = false;
		$products_detail_permissions['Content']['不能编辑_显示的类型标签'] = true;
		//$products_detail_permissions['ImageVideo']['能看不能编'] = true;
		$products_detail_permissions['MetaTag']['能看不能编'] = true;
		$products_detail_permissions['HotelsNearbyAttractions']['能看不能编'] = true;
		//$products_attributes_permissions['能看不能编'] = true;
		$buy_two_get_one_permissions['能看不能编'] = true;
		$can_update_question_date=true;
		$can_edit_regular_price = 1;
	break;
	//产品经理
	case '44': 	
		$allow_travle_agency_edit = true;
		$access_full_edit = 'true';				//在产品页面必须有此功能才能编辑或显示成本(如果设置了['不能输入价格']选项的话产品人员不一定能编辑)
		$_where .= ' AND os_groups_id in(0) ';	//产品基本不能使用任何订单状态
		$can_check_question_answers = true; 	//能审核客户咨询的回复问题
		$products_detail_permissions['CategoriesProductsList']['权限'] = 'all';
		$can_update_price = true;
		$can_update_question_date=true;
		$can_top_question_answers = true;                   
		$can_copy_question_answers=true;
		$can_see_question_answers_email = true;
		$can_one_update = true;
		$can_edit_regular_price = 2;
		$can_open_or_close_products = true;
		$can_delete_remark = true;
		$can_see_non_payment_orders = true;
		$can_edit_problem=true;$can_edit_problem_answer=true;$can_edit_problem_status=true;
		$can_add_problem_score = true;
		$can_add_problem_answer_score = true;
	break;
	// 市场部经理
	case '4':
		$can_expert_group_add = true;   							//添加
		$can_expert_group_edit = true;								//编辑
		$can_expert_group_open = true;								//启用专家
		$can_expert_group_del = true;
	break;
	// 市场部普通员工
	case '46':
	case '52':
		$can_expert_group_add = true;   							//添加
		$can_expert_group_edit = true;								//编辑
		$can_expert_group_open = true;								//启用专家
	break;
}

$_sql = tep_db_query('SELECT orders_status_id FROM '.$_tables.$_where);
while($_rows = tep_db_fetch_array($_sql)){
	$can_use_orders_status[] = $_rows['orders_status_id'];
}

/**
* 根据用户的权限过滤订单状态数组
* 
**/
function tep_filter_orders_statuses_from_can_use_orders_status($orders_statuses, $can_use_orders_status, $default_id = 0){
	$new_orders_statuses = false;
	foreach($orders_statuses as $_m => $_v){
		if(in_array($_v['id'],$can_use_orders_status) || $_v['id']==$default_id){	//权限过滤
			$new_orders_statuses[] = $_v;
		}
	}
	return $new_orders_statuses;
}
//print_r($can_use_orders_status);

//价格输入框权限设置 start {
$price_input_readonly = '';
$price_button = '';
if($can_update_price !== true){
	$price_input_readonly = ' readonly="readonly" ';
	$price_button = ' disabled="disabled" ';
}
//价格输入框权限设置 end }

//新的权限分配end}
?>