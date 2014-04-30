
DROP procedure IF EXISTS `mysp_autobind_orders_ownerid_batch`;

DELIMITER $$

CREATE PROCEDURE `mysp_autobind_orders_ownerid_batch` ()
SQL SECURITY INVOKER
COMMENT '这个sp用来取得要计算订单归属的订单列表,然后循环调用单个订单的绑定sp.'
BEGIN

  DECLARE done INT DEFAULT 0;
  DECLARE v_orders_id INT DEFAULT 0;
  DECLARE s varchar(200) DEFAULT '';
  DECLARE i_count_aben int default 0;
 
  /*声明cursor*/
  DECLARE cursor1 CURSOR FOR

  SELECT a.orders_id FROM(
    SELECT orders_id,orders_owner_admin_id ,customers_email_address,date_purchased,orders_paid,orders_owners
    FROM orders 
    WHERE (orders_owners IS NULL OR orders_owners='') AND (customers_email_address IS NOT NULL )
  ) AS a, orders_total AS b 
  WHERE b.class='ot_total' AND a.orders_id=b.orders_id AND a.orders_paid>=b.value;

  /*指定需要特殊处理的条件*/
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=1;
  -- DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done=1;

  OPEN cursor1;

  REPEAT
    FETCH cursor1 INTO v_orders_id;
    IF v_orders_id>0 THEN
      CALL mysp_autobind_orders_ownerid(v_orders_id);
    END IF;

  UNTIL done END REPEAT;



  CLOSE cursor1;

END
$$

DELIMITER ;


-- call mysp_autobind_orders_ownerid_batch;



