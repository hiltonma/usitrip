<?php
/*
  $Id: message_stack.php,v 1.1.1.1 2004/03/04 23:40:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/
//Lango Added for template mod: BOF
  class messageStack extends tableBoxMessagestack {
//Lango Added for template mod: EOF

// class constructor
    function messageStack() {
      global $messageToStack;

      $this->messages = array();

      if (tep_session_is_registered('messageToStack')) {
        for ($i=0, $n=sizeof($messageToStack); $i<$n; $i++) {
          $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        tep_session_unregister('messageToStack');
      }
    }

// class methods
/**
 * 添加一个信息到消息堆栈
 * vincent 修改以支持新的界面要求
 * @param string $class 消息范围
 * @param string $message 消息内容
 * @param string $type 消息类型 error, warning,success默认是错误
 *@param string $uiTarget 要附加该错误到某个UI目标
 * @author vincent
 * @modify by vincent at 2011-5-6 下午01:15:00
 */
    function add($class, $message, $type = 'error',$uiTarget='') {
      if ($type == 'error') {
        $this->messages[] = array('params' => 'class="messageStackError"','type'=>$type,'target'=>$uiTarget, 'class' => $class, 'msg'=>$message ,'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message);
      } elseif ($type == 'warning') {
        $this->messages[] = array('params' => 'class="messageStackWarning"','type'=>$type,'target'=>$uiTarget, 'class' => $class, 'msg'=>$message ,'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message);
      } elseif ($type == 'success') {
        $this->messages[] = array('params' => 'class="messageStackSuccess"','type'=>$type, 'target'=>$uiTarget,'class' => $class,'msg'=>$message , 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message);
      } else {
        $this->messages[] = array('params' => 'class="messageStackError"','type'=>$type, 'target'=>$uiTarget,'class' => $class, 'msg'=>$message ,'text' => $message);
      }
    }

    function add_session($class, $message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);
    }

    function reset() {
      $this->messages = array();
    }

    function output($class, $output_type='html') {
      $this->table_data_parameters = 'class="messageBox"';

      $output = array();
	  for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $output[] = $this->messages[$i];
        }
      }
//Lango Added for template mod: BOF
	  if($output_type=='text'){
	  	$text = "";
		foreach((array)$output as $key => $val){
			$text.= $output[$key]["msg"]."<br />";
		}
		return preg_replace('/\<br \/\>$/','',$text);
	  }
      return $this->tableBoxMessagestack($output);
//Lango Added for template mod: EOF
    }
    /**
     * 获取错误堆栈的消息数组
     * @param unknown_type $class
     * @author vincent
     * @modify by vincent at 2011-5-24 上午09:51:23
     */
    function output_array($class){
   		$output = array();	     
	    for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
	      if ($this->messages[$i]['class'] == $class) {
	          $output[] = $this->messages[$i];
	     }
	    }
	    return $output;	   
    }
   /**
    * 输出错误为新的样式
    * @param string $class 要显示的消息范围
    * @param string $defaultUITarget null 是否强制将错误附加到界面元素,用于和之前的错误消息兼容设置为null则不会修改界面目标
    * @author vincent
    * @modify by vincent at 2011-5-6 下午01:28:41
    */
  function output_newstyle($class,$defaultUITarget = null,$showLastError =false ){
	      $output = array();	     
	      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
	        if ($this->messages[$i]['class'] == $class) {
	          $output[] = $this->messages[$i];
	        }
	      }
	      $outputHtml = '';
	      $outputJs = '';
	      //只显示最后一条错误
	      if($showLastError){
	      	$output = array(array_pop($output));
	      }
	     
	      foreach($output as $msg){
	      	//选择图标
	      	switch($msg['type']){
	      		case 'error':$icon = 'errorTip';break;
	      		case 'warning':$icon = 'alertTip';break;
	      		case 'success':$icon = 'successTip';break;
	      		default: $icon = strtolower($msg['type']);break;
	      	}
	      	$msgText = '<span class="'.$icon.'">'.$msg['msg']."</span>";
	      	if($defaultUITarget !== null && $msg['target'] == '' ) $msg['target'] = $defaultUITarget;
	      	if($msg['target'] == ''){
	      		$outputHtml.= 	$msgText;
	      	}else{
	      		$outputJs.= 'jQuery("#'.$msg['target'].'").append( "'.format_for_js($msgText).'");jQuery("#'.$msg['target'].'").fadeIn("slow");';
	      	}
	      }
	      $return  = '';
	      if($outputHtml != '') $return .= $outputHtml ;
	      if($outputJs != '')$return .= '<script type="text/javascript">'.$outputJs.'</script>';
	      return $return;
  }

    function size($class) {
      $count = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $count++;
        }
      }

      return $count;
    }
  }
?>
