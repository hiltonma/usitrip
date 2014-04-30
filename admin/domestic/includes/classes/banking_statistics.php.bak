<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class bank_statistics {
    public $bank_name;
    public $orders_num;
    public $tours_costs_total;
    public $hotel_costs_total;
    public $other_service_total;
    public $orders_total;
    public $real_charge_total;
    public $bank_array;
    function __construct($bank_name) {
        $this->bank_name =$bank_name;
        $this->orders_num = '';
        $this->tours_costs_total ='';
        $this->hotel_costs_total ='';
        $this->other_service_total ='';
        $this->orders_total ='';
        $this->real_charge_total ='';
        $this->bank_array = array();

        $this->get_bankChineseName($this->bank_name);
    }
     //银行英文缩写转中文简体
    function get_bankChineseName($bank_name){
        switch ($bank_name) {
            case 'ccb':return  $this->bank_name='中国建设银行';$ccb_array='';break;
            case 'icbc':return  $this->bank_name='中国工商银行';$icbc_array='';break;
            case 'boc':return  $this->bank_name='中国银行';$boc_array='';break;
            case 'cmbchina':return $this->bank_name='中国招商银行';$cmbchian_array='';break;
        }

    }
    //统计订单数
    function statistics_orders_num() {
        //$sql = 'SELECT count( orders_id ) AS total_orders FROM `domestic_orders` WHERE bank ="'.get_bankChineseName($this->bank_name).'"';
        $orders_num_query = tep_db_query('SELECT count( orders_id ) AS total_orders FROM `domestic_orders` WHERE bank ="'.$this->bank_name.'"');
        $orders_num_rows = tep_db_fetch_array($orders_num_query);
        $this->orders_num=$orders_num_rows['total_orders'];
        return $this->orders_num;
    }
    //统计团费合计
    function statistics_tours_costs_total(){
        $tours_costs_total_query = tep_db_query('SELECT sum(tours_costs) AS tours_costs_total FROM `domestic_orders` WHERE bank = "'.$this->bank_name.'"');
        $tours_costs_total_rows = tep_db_fetch_array($tours_costs_total_query);
        $this->tours_costs_total = $tours_costs_total_rows['tours_costs_total'];
        return $this->tours_costs_total;
    }
    //酒店合计
    function statistics_hotel_costs_total(){
        $hotel_costs_total_query = tep_db_query('SELECT sum(hotel_costs) AS hotel_total FROM `domestic_orders` WHERE bank = "'.$this->bank_name.'"');
        $hotel_costs_total_rows = tep_db_fetch_array($hotel_costs_total_query);
        $this->hotel_costs_total = $hotel_costs_total_rows['hotel_total'];
        return $this->hotel_costs_total;
    }
    //接送服务合计
    function statistics_other_service(){
        $other_service_1_query = tep_db_query('SELECT sum(pickup_costs) AS cost_1 FROM `domestic_orders` WHERE bank = "'.$this->bank_name.'"');
        $other_service_1_rows = tep_db_fetch_array($other_service_1_query);
        $other_service_2_query = tep_db_query('SELECT sum(leave_costs) AS cost_2 FROM `domestic_orders` WHERE bank = "'.$this->bank_name.'"');
        $other_service_2_rows = tep_db_fetch_array($other_service_2_query);
        $this->other_service_total = $other_service_1_rows['cost_1']+$other_service_1_rows['cost_2'];
        return  $this->other_service_total;
   }
   //应付金额统计
   function statistice_orders_total(){
       $orders_total_query = tep_db_query('SELECT sum(value) AS orders_total FROM `domestic_orders` WHERE bank = "'.$this->bank_name.'"');
       $orders_total_rows = tep_db_fetch_array($orders_total_query);
       $this->orders_total = $orders_total_rows['orders_total'];
       return  $this->orders_total;
   }
   //实收金额统计
   function statistice_real_charge_total(){
       $real_charge_total_query = tep_db_query('SELECT sum(real_charge) AS real_charge_total FROM `domestic_orders` WHERE bank = "'.$this->bank_name.'"');
       $real_charge_total_rows = tep_db_fetch_array($real_charge_total_query);
       $this->real_charge_total = $real_charge_total_rows['real_charge_total'];
       return $this->real_charge_total;
   }
   function statistice_percent($totals,$this_bank_total){
       return $this_bank_total/$totals;
   }
   function statistice_all(){
       $this->statistics_orders_num();
       $this->statistice_real_charge_total();
       $this->statistice_orders_total();
       $this->statistics_other_service();
       $this->statistics_hotel_costs_total();
       $this->statistics_tours_costs_total();
       $this->creatArray();
   }
   function creatArray(){
       $this->bank_array = array('bank_name'=>$this->bank_name,
                                  'orders_num'=>$this->orders_num,
                                  'tours_costs_total'=>$this->tours_costs_total,
                                  'hotel_costs_total'=>$this->hotel_costs_total,
                                  'other_service_total'=>$this->other_service_total ,
                                  'orders_total'=>$this->orders_total,
                                  'real_charge_total'=>$this->real_charge_total);
  }


}

function admin_check($adminid){
    //判断admin id号是否在admin_domestic_orders_manage表里
    $admin_id = (int)$adminid;
    $check_query = tep_db_query('SELECT * FROM admin_domestic_orders_manage WHERE admin_id="'.$admin_id.'"');
    $check_rows = tep_db_fetch_array($check_query);
    if((int)$check_rows['admin_id']){
        if((int)$check_rows['permit_status']=='1'){
             switch((int)$check_rows['manager_group']){
                 case '1':return 'service';break;
                 case '2':return 'accountant';break;
                 case '3';return 'master';break;
             }
        }else{
            return false;
        }
     }else{
         return  false;
     }


}

function creatArray($name,$onum,$ototal,$rctusd,$rctcny){
       $bank_array = array('bank_name'=>$name,
                                  'orders_num'=>$onum,
                                   'orders_total'=>$ototal,
                                  'real_charge_total_usd'=>$rctusd,
                                  'real_charge_total_cny'=>$rctcny);
       return $bank_array;
  }

?>
