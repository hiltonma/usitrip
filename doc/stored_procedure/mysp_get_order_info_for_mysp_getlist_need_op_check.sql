DROP PROCEDURE IF EXISTS mysp_get_order_info_for_getlist_need_op_check;

DELIMITER $$
CREATE PROCEDURE mysp_get_order_info_for_getlist_need_op_check($orders_id INT)
SQL SECURITY INVOKER
COMMENT 'OP需审核的订单列表->获取某一个订单之信息'
BEGIN

/*如果地接回复的时间晚于走四方发出的时间,则判断为地接未回复(最后一次未回复)*/
SELECT a6.orders_id, a6.customers_name, a6.customers_email_address, a6.orders_owner_admin_id, a6.date_purchased,a6.last_modified, 
a6.admin_id_orders, a6.next_admin_id, a6.products_id, a6.orders_products_id, a6.products_departure_date, 
a6.provider_tour_code, a6.orders_status_name,
CASE WHEN b6.provider_last_send_time IS NULL THEN NULL ELSE (
    CASE WHEN a6.usitrip_last_send_time > b6.provider_last_send_time THEN NULL ELSE b6.provider_last_send_time END
) END AS provider_last_reply_time
FROM(

SELECT a4.orders_id, a4.customers_id, a4.customers_name, a4.customers_email_address, a4.orders_owner_admin_id, a4.date_purchased,
a4.last_modified, a4.admin_id_orders, a4.next_admin_id, a4.orders_status, a4.orders_status_name,
b4.orders_products_id, b4.products_id, b4.products_departure_date, b4.provider_tour_code, c4.usitrip_last_send_time
FROM(
    /*订单基本信息和状态名称*/
    SELECT a1.orders_id, a1.customers_id, a1.customers_name, a1.customers_email_address, a1.orders_owner_admin_id /*`所属客服*/, a1.date_purchased /*下单日*/,
    a1.last_modified /*最后更新*/, a1.admin_id_orders/*最后更新人*/, a1.next_admin_id /*当前处理人*/, a1.orders_status,
    b1.orders_status_name
    FROM orders AS a1, orders_status AS b1
    WHERE a1.orders_id= $orders_id AND a1.orders_status = b1.orders_status_id
) AS a4,

(
    /*订单之产品信息*/
    SELECT a2.orders_products_id, a2.orders_id, a2.products_id, a2.products_departure_date, b2.provider_tour_code
    FROM orders_products AS a2, products AS b2
    WHERE a2.orders_id= $orders_id AND a2.products_id = b2.products_id
) AS b4,

(
    /*订单之最后发送地接商时间*/
    SELECT a3.orders_products_id, MAX(a3.provider_status_update_date) AS usitrip_last_send_time
    FROM provider_order_products_status_history  AS a3, orders_products AS b3
    WHERE a3.popc_user_type=0 AND b3.orders_id= $orders_id AND a3.orders_products_id = b3.orders_products_id
    GROUP BY a3.orders_products_id
) AS c4
WHERE a4.orders_id = b4.orders_id AND b4.orders_products_id = c4.orders_products_id

) AS a6 LEFT JOIN(/*这里必需用外连接,以确保返回所有数据*/

    /*订单之地接商最后回复时间*/
    SELECT a5.orders_products_id, MAX(a5.provider_status_update_date) AS provider_last_send_time
    FROM provider_order_products_status_history AS a5, orders_products AS b5
    WHERE a5.popc_user_type=1 AND b5.orders_id= $orders_id AND a5.orders_products_id = b5.orders_products_id
    GROUP BY a5.orders_products_id
    
) AS b6 ON a6.orders_products_id = b6.orders_products_id;

END $$

DELIMITER ; 
-- CALL mysp_get_order_info_for_getlist_need_op_check(39654);

