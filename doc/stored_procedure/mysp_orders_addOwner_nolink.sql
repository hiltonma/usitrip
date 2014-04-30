DROP PROCEDURE IF EXISTS mysp_orders_addowner_nolink;

DELIMITER $$

CREATE PROCEDURE `mysp_orders_addowner_nolink`(pi_orders_id int,pi_login_id int,pi_date datetime)
SQL SECURITY INVOKER
COMMENT '给没有带链接的订单添加订单所属人,权重默认是0.5'
BEGIN
  DECLARE $oid int DEFAULT 0; /*自定义的订单号*/
  DECLARE $customers_id int DEFAULT 0;/*客人ID*/
  DECLARE $name varchar(50) DEFAULT '';/*客人姓名*/
  DECLARE $email varchar(50) DEFAULT '';/*邮箱*/
  DECLARE $code varchar(100) DEFAULT '';/*团号联合体*/
  DECLARE $stid int DEFAULT 0;/*新增的销售跟踪的ID*/
  
  SELECT orders_id INTO $oid FROM orders WHERE orders_id=pi_orders_id;
  IF $oid>0 THEN
    UPDATE orders SET orders_owner_commission=0.5,orders_owner_admin_id=pi_login_id WHERE orders_id=$oid 
    AND (orders_owner_commission=0 OR orders_owner_commission='' OR orders_owner_commission IS NULL);
    /*如果更新了1行数据才进行自动添加销售跟踪的动作*/
    IF ROW_COUNT()=1 THEN
    
      /*取得email和团号链接*/
      SELECT customers_id INTO $customers_id FROM orders WHERE orders_id=$oid;
      
      SELECT customers_email_address,CONCAT(customers_lastname,' ',customers_firstname) AS name INTO $email,$name FROM customers 
      WHERE customers_id=$customers_id;
      
      SELECT GROUP_CONCAT(products_model) INTO $code FROM orders_products WHERE orders_id=$oid;

      /*插入主表,并返回insert_id*/
      INSERT INTO salestrack
      (`add_date`,`customer_name`,`customer_email`,`customer_info`,`login_id`,`orders_id`,`code`,`email_last_update_date`)      
      VALUES(pi_date, $name, $email, 'auto add', pi_login_id, $oid, $code, pi_date);
      
      SET $stid=@@session.identity;
      
      /*插入email历史记录*/
      INSERT INTO salestrack_email_history(salestrack_id,email,add_date,login_id)VALUES($stid,$email,pi_date,pi_login_id);
      
      /*插入code历史记录*/
      INSERT INTO salestrack_code_history(salestrack_id,code,add_date,login_id)
      SELECT $stid AS salestrack_id,products_model AS code,pi_date,pi_login_id AS login_id FROM orders_products WHERE orders_id=$oid;
      
    END IF;
  END IF;
  SELECT $stid AS code;
END $$

DELIMITER ; 