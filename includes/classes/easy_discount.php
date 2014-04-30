<?php
  class easy_discount {
    var $discounts;

    function easy_discount () {
      $this->discounts = array();
    }

    function reset() {
      $this->discounts = array();
    }

    function set($type,$description, $amount) {
     $this->discounts[$type] = array('description' => $description, 'amount' => $amount);
    }

    function add($type,$description, $amount) { // obsolete
     $this->discounts[$type] = array('description' => $description, 'amount' => $amount);
    }

    function clear($type) {
      if (isset($this->discounts[$type])) unset($this->discounts[$type]);
    }

    function remove_type($type) { // obsolete
      if (isset($this->discounts[$type])) unset($this->discounts[$type]);
    }

    function count() { 
      return sizeof($this->discounts);
    }

    function get($type) { 
      return $this->discounts[$type];
    }

    function total() {
      reset($this->discounts);
      $total = 0;
      while (list($type, ) = each($this->discounts)) {
       $total = $total + $this->discounts[$type]['amount'];
      }
      return $total;
    }

    function get_all() {
      $discounts_array = array();
      reset($this->discounts);
      while (list($type, ) = each($this->discounts)) {
          $discounts_array[] = array('description' => $this->discounts[$type]['description'],
                                     'amount' => $this->discounts[$type]['amount']);
      }
      return $discounts_array;
    }
  }
?>