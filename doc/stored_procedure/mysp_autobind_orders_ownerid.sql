
DROP PROCEDURE IF EXISTS mysp_autobind_orders_ownerid;

DELIMITER $$

CREATE  PROCEDURE `mysp_autobind_orders_ownerid`($orders_id INT)
SQL SECURITY INVOKER
COMMENT '针对某一个订单号绑定订单归属'
BEGIN

  DECLARE $owners_id varchar(50) DEFAULT '';/*用来保存最终的订单归属者的id的string*/
  DECLARE $email varchar(50) DEFAULT '';/*email*/
  DECLARE $date_purchased datetime;/*下单时间*/
  DECLARE $agentlink_id int DEFAULT 0;/*销售链接*/
  DECLARE $orders_owner_commission float DEFAULT 0;/*如果是在下单时带的销售链接为1,否则为0.5*/
  DECLARE $code_quantity int DEFAULT 1;/*购买的产品之团号数量统计*/
  DECLARE $new_login_id int DEFAULT 0;/*符合条件的其他销售ID*/
  DECLARE $orders_owners varchar(50); /*订单归属人(字符串,多人以逗号-链接)*/
  DECLARE $start_date datetime;/*限定3个月内添加的资料*/
  
  DECLARE $agentlink_id_jobnumber varchar(50) DEFAULT '';/*销售链接之员工工号*/
  DECLARE $new_login_id_jobnumber varchar(50) DEFAULT '';/*新找到的符合条件之员工工号*/
  
  DECLARE $is_hotel int DEFAULT 0;/*0:有正常产品,1:全部是酒店(判断是否有正常产品,如果有则忽略酒店的团号,否则全部是酒店就按所有团号计算)*/
  
  
  -- SELECT $orders_id;
  SELECT orders_owner_admin_id ,customers_email_address,date_purchased,orders_owner_commission
  INTO $agentlink_id,$email,$date_purchased,$orders_owner_commission
  FROM orders WHERE orders_id=$orders_id;  
  
  SET $start_date = DATE_ADD($date_purchased,INTERVAL -90 day);  
  
  /*抗议!mysql的临时表只能打开一次,fuck!*/
  
  IF CHAR_LENGTH($email)>3 THEN
  
  -- SELECT $agentlink_id,$email,$date_purchased,$orders_owner_commission;
  /*建立临时表*/
  DROP TEMPORARY TABLE IF EXISTS `#tmptb_st_email`;
  CREATE TEMPORARY TABLE `#tmptb_st_email`( salestrack_id int NOT NULL,  email_add_date datetime NOT NULL,  login_id int NOT NULL );
  
  /*判断是否有正常产品,如果有则忽略酒店的团号,否则按所有团号计算(正常线路的数量为0,则肯定全部是hotel)*/
  SELECT CASE COUNT(1) WHEN 0 THEN 1 ELSE 0 END INTO $is_hotel FROM orders_products AS a, products AS b 
  WHERE (a.orders_id=$orders_id) AND (a.products_id=b.products_id) AND (b.is_hotel=0);
  
  
  /*取订单的团号的数量(如果有普通产品,则忽略酒店的团号)*/
  SELECT COUNT(1) INTO $code_quantity FROM(
    SELECT DISTINCT b.products_model AS code FROM orders_products AS a, products AS b 
    WHERE (a.orders_id=$orders_id) AND (a.products_id=b.products_id) AND (b.is_hotel=$is_hotel)
  ) AS a;
  
  -- SELECT $is_hotel,$agentlink_id,$email,$start_date,$date_purchased,$orders_owner_commission,$code_quantity;
  
  IF $orders_owner_commission=1 THEN
  /*下单时带了销售链接的订单,再从销售跟踪中寻找第二个符合条件的人员.最多两个人来分单.*/
  
    /*符合条件的销售跟踪及email添加时间*/
    /*INSERT INTO `#tmptb_st_email`
    SELECT salestrack_id,add_date AS email_add_date,login_id FROM salestrack_email_history 
    WHERE (email=$email) AND (add_date<$date_purchased) AND (add_date>=$start_date) AND (login_id != $agentlink_id);
    */
  
    -- SELECT * FROM `#tmptb_st_email`;     
    
    SELECT a2.login_id INTO $new_login_id FROM(
      /*符合条件的销售跟踪及email添加时间*/
      SELECT salestrack_id,add_date AS email_add_date,login_id FROM salestrack_email_history 
      WHERE (email=$email) AND (add_date<$date_purchased) AND (add_date>=$start_date) AND (login_id != $agentlink_id)
    ) AS a2 INNER JOIN (
    SELECT a1.login_id FROM (
    /*过滤一个团号,同一人重复添加*/
    SELECT DISTINCT b.code,b.login_id /*b.salestrack_id,b.code,b.add_date,b.login_id*/ FROM(
      /*取得订单的团号*/
          SELECT DISTINCT b.products_model AS code FROM orders_products AS a, products AS b 
          WHERE (a.orders_id=$orders_id) AND (a.products_id=b.products_id) AND (b.is_hotel=$is_hotel)
    ) AS a INNER JOIN (
      /*取得添加时间合适,且符合前面查出的销售的销售跟踪的ID的团号记录*/
      SELECT salestrack_id,code,add_date,login_id FROM salestrack_code_history 
      WHERE (add_date<$date_purchased) AND (add_date>=$start_date) AND salestrack_id IN( 
        SELECT salestrack_id FROM (
            SELECT salestrack_id,add_date AS email_add_date,login_id FROM salestrack_email_history 
            WHERE (email=$email) AND (add_date<$date_purchased) AND (add_date>=$start_date) AND (login_id != $agentlink_id)
        ) AS c
      ) AND (login_id != $agentlink_id)
    ) AS b ON a.code=b.code
    ) AS a1 GROUP BY a1.login_id  HAVING COUNT(1)>=$code_quantity
    ) AS b2 ON a2.login_id =b2.login_id ORDER BY a2.email_add_date ASC LIMIT 0,1;  

    -- SELECT $new_login_id;
    /*先更新订单归属明细表*/
    UPDATE orders_owner_detail SET is_deleted='1' WHERE (orders_id = $orders_id) AND (is_deleted= '0');
    /*由ID取得工号*/
    SELECT admin_job_number INTO $agentlink_id_jobnumber FROm `admin` WHERE admin_id = $agentlink_id;
    
    INSERT INTO orders_owner_detail(orders_id,owner_login_id,add_date)
    VALUES($orders_id,$agentlink_id,now());
    
    /*判断是否有新的符合条件的人员*/
    IF $new_login_id>0 THEN
        /*由ID取得工号(第二人)*/
        SELECT admin_job_number INTO $new_login_id_jobnumber FROm `admin` WHERE admin_id = $new_login_id;
        SET $orders_owners = CONCAT($agentlink_id_jobnumber,',',$new_login_id_jobnumber);      
        INSERT INTO orders_owner_detail(orders_id,owner_login_id,add_date)VALUES($orders_id,$new_login_id,now());
    ELSE
      SET $orders_owners = CONCAT($agentlink_id_jobnumber);
    END IF;
    /*更新订单表*/
    -- SELECT $orders_owners;
    UPDATE orders SET orders_owners=$orders_owners WHERE orders_id = $orders_id;
    
  ELSE
  /*=====================================原来没有销售链接的部分======================================*/
  /*最多只取一人,不管后来有没有再添加销售链接的记录.*/

    /*符合条件的销售跟踪及email添加时间*/
    INSERT INTO `#tmptb_st_email`
    SELECT salestrack_id,add_date AS email_add_date,login_id FROM salestrack_email_history
    WHERE (email=$email) ;
    
    SELECT a2.login_id INTO $new_login_id FROM(
      /*符合条件的销售跟踪及email添加时间*/
      SELECT a0.salestrack_id,a0.add_date AS email_add_date,b0.login_id FROM salestrack_email_history AS a0,salestrack AS b0
      WHERE (a0.email=$email) AND (a0.add_date>=$start_date) AND a0.salestrack_id = b0.salestrack_id
    ) AS a2 INNER JOIN (
    
    SELECT a1.login_id FROM (
    /*过滤一个团号,同一人重复添加*/
    SELECT DISTINCT b.code,b.login_id /*b.salestrack_id,b.code,b.add_date,b.login_id*/ FROM(
      /*取得订单的团号*/
          SELECT DISTINCT b.products_model AS code FROM orders_products AS a, products AS b 
          WHERE (a.orders_id=$orders_id) AND (a.products_id=b.products_id) AND (b.is_hotel=$is_hotel)
    ) AS a INNER JOIN (
      /*取得添加时间合适,且符合前面查出的销售的销售跟踪的ID的团号记录*/
      SELECT salestrack_id,code,add_date,login_id FROM salestrack_code_history 
      WHERE  (add_date>=$start_date) AND salestrack_id IN( 
        SELECT salestrack_id FROM `#tmptb_st_email`
      ) 
    ) AS b ON a.code=b.code
    ) AS a1 GROUP BY a1.login_id  HAVING COUNT(1)>=$code_quantity
    
    ) AS b2 ON a2.login_id =b2.login_id ORDER BY a2.email_add_date ASC LIMIT 0,1;
    
    IF $new_login_id>0 THEN
        /*先更新订单归属明细表*/
        UPDATE orders_owner_detail SET is_deleted='1' WHERE (orders_id = $orders_id) AND (is_deleted= '0');
    
        INSERT INTO orders_owner_detail(orders_id,owner_login_id,add_date)
        VALUES($orders_id,$new_login_id,now());
    
        /*权重0.5的会自动添加销售跟踪,只取一人即可*/
        SELECT admin_job_number INTO $new_login_id_jobnumber FROm `admin` WHERE admin_id = $new_login_id;
        SET $orders_owners = CONCAT($new_login_id_jobnumber);

        /*更新订单表*/
        -- SELECT $orders_owners;
        UPDATE orders SET orders_owners=$orders_owners WHERE orders_id = $orders_id;
    END IF;
  
  END IF;
  
  DROP TEMPORARY TABLE IF EXISTS `#tmptb_st_email`;
  
  SELECT $orders_id AS orders_id, $orders_owners AS orders_owners;
  END IF;
  
END
$$

DELIMITER ;

