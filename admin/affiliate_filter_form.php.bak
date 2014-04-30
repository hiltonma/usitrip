<?php
	  $Affi_statuses = array(); 
	   $Affi_statuses[] = array('id' => 1,
                                 'text' => 'Acquired Affiliates');
	
	 echo tep_draw_form('aff_filter_frm', basename($PHP_SELF), tep_get_all_get_params(array('page','acID','aff_filter')), 'get'); ?>
	<?php echo 'Affiliate Filter:&nbsp;' . ' ' . tep_draw_pull_down_menu('aff_filter', array_merge(array(array('id' => '0', 'text' => 'All Affiliates')), $Affi_statuses), '', 'onChange="this.form.submit();"'); ?>
	 </form>