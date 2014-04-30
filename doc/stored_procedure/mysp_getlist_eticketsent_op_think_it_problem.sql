DROP PROCEDURE IF EXISTS mysp_getlist_eticketsent_op_think_it_problem;
DELIMITER $$

CREATE PROCEDURE mysp_getlist_eticketsent_op_think_it_problem()
SQL SECURITY INVOKER
COMMENT '操作员认为有问题的订单,根据状态列表来'
BEGIN

SELECT a4.orders_id,a4.customers_name, a4.customers_email_address,a4.orders_owner_admin_id,a4.last_modified,a4.admin_id_orders,a4.next_admin_id,a4.orders_status,
a4.date_purchased,a4.products_departure_date,a4.provider_tour_code, b4.orders_status_name
FROM(
SELECT a3.orders_id,a3.customers_name, a3.customers_email_address,a3.orders_owner_admin_id,a3.last_modified,a3.admin_id_orders,a3.next_admin_id,a3.orders_status,
GROUP_CONCAT(a3.date_purchased) AS date_purchased,
GROUP_CONCAT(a3.products_departure_date) AS products_departure_date,
GROUP_CONCAT(a3.provider_tour_code) AS provider_tour_code FROM(
/*订单基本信息+出团日期+地接商团号*/
SELECT DISTINCT a2.orders_id, a2.customers_name, a2.customers_email_address, a2.orders_owner_admin_id /*`所属客服*/, a2.date_purchased /*下单日*/,
a2.last_modified /*最后更新*/, a2.admin_id_orders/*最后更新人*/, a2.next_admin_id /*当前处理人*/, a2.orders_status,
/*c.orders_products_id, c.products_id,*/ DATE_FORMAT(c2.products_departure_date,'%Y-%m-%d') AS products_departure_date, 
d2.provider_tour_code
FROM orders AS a2,
(
    SELECT a1.orders_id /*,a1.date_add_lastproblem,b1.add_date_lastresolve*/ FROM(
        /*最后一次添加问题的时间*/
        SELECT a.orders_id,MAX(a.date_added) AS date_add_lastproblem 
        FROM orders_status_history AS a,orders_problemcontrol_statusid AS b
        WHERE a.orders_status_id=b.orders_status_id GROUP BY a.orders_id
    ) AS a1 LEFT JOIN(
        /*最后一次处理的时间*/
        SELECT a.orders_id,MAX(a.add_date) AS add_date_lastresolve
        FROM orders_problems_track_history AS a, orders_problemcontrol_statusid AS b
        WHERE b.is_deleted = '0' AND a.orders_status_id=b.orders_status_id AND a.is_finished='1'
        GROUP BY a.orders_id
    ) AS b1 ON a1.orders_id=b1.orders_id 
    WHERE b1.add_date_lastresolve IS NULL OR a1.date_add_lastproblem>b1.add_date_lastresolve 
) AS b2, orders_products AS c2, products AS d2
WHERE a2.orders_id = b2.orders_id AND a2.orders_id = c2.orders_id AND c2.products_id = d2.products_id
) AS a3 GROUP BY a3.orders_id
) AS a4, orders_status AS b4
WHERE b4.language_id=1 AND a4.orders_status=b4.orders_status_id;

END
$$

DELIMITER ;

-- CALL mysp_getlist_eticketsent_op_think_it_problem;
