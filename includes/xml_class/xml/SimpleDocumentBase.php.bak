<?php
/**
 *=================================================
 *
 * @author     hahawen(´óÁäÇàÄê) 
 * @since      2004-12-04
 * @copyright  Copyright (c) 2004, NxCoder Group
 *
 *=================================================
 */
 /**
 * abstract class SimpleDocumentBase
 * base class for xml file parse
 * all this pachage's is work for xml file, and method is action as DOM.
 *
 * 1\ add/update/remove data of xml file.
 * 2\ explode data to array.
 * 3\ rebuild xml file
 *
 * @package SmartWeb.common.xml
 * @abstract
 * @version 1.0
 */
 abstract class SimpleDocumentBase
 {
     private $nodeTag = null;
     private $attributes = array();
     private $values =
     array();
     private $nodes = array();
     function __construct($nodeTag)
     {
         $this->nodeTag = $nodeTag;
     }
     public function getNodeTag()
     {
         return $this->nodeTag;
     }
     public function setValues($values){
         $this->values = $values;
     }
     public function setValue($name, $value)
     {
         $this->values[$name] = $value;
     }
     public function getValue($name=null)
     {
         return $name==null?
         $this->values: $this->values[$name];
     }
 
     public function removeValue($name)
     {
         unset($this->values["$name"]);
     }
     public function setAttributes($attributes){
         $this->attributes = $attributes;
     }
     public function setAttribute($name, $value)
     {
         $this->attributes[$name] = $value;
     }
     public function getAttribute($name=null)
     {
         return $name==null? $this->attributes:
         $this->attributes[$name];
     }
     public function removeAttribute($name)
     {
         unset($this->attributes["$name"]);
     }
     public function getNodesSize()
     {
         return sizeof($this->nodes);
     }
     protected function setNode($name, $nodeId)
     {
         $this->nodes[$name]
         = $nodeId;
     }
     public abstract function createNode($name, $attributes);
     public abstract function removeNode($name);
     public abstract function getNode($name=null);
     protected function getNodeId($name=null)
     {
         return $name==null? $this->nodes: $this->nodes[$name];
     }
     protected function createNodeByName($rootNodeObj, $name, $attributes, $pId)
     {
         $tmpObject = $rootNodeObj->createNodeObject($pId, $name, $attributes);
         $key = isset($attributes[id])?
         $name.'_'.$attributes[id]: $name.'_'.$this->getNodesSize();
         $this->setNode($key, $tmpObject->getSeq());
         return $tmpObject;
     }
     protected function removeNodeByName($rootNodeObj, $name)
     {
         $rootNodeObj->removeNodeById($this->getNodeId($name));
         if(sizeof($this->nodes)==1)
         $this->nodes = array();
         else
         unset($this->nodes[$name]);
     }
     protected function getNodeByName($rootNodeObj, $name=null)
     {
         if($name==null)
         {
             $tmpList = array();
             $tmpIds = $this->getNodeId();
             foreach($tmpIds as $key=>$id)
             $tmpList[$key] = $rootNodeObj->getNodeById($id);
             return $tmpList;
         }
         else
         {
             $id = $this->getNodeId($name);
             if($id===null)
             {
                 $tmpIds = $this->getNodeId();
                 foreach($tmpIds as $tkey=>$tid)
                 {
                     if(strpos($key, $name)==0)
                     {
                         $id = $tid;
                         break;
                     }
                 }
             }
             return $rootNodeObj->getNodeById($id);
         }
     }
     public function findNodeByPath($path)
     {
         $pos = strpos($path, '|');
         if($pos<=0)
         {
             return $this->getNode($path);

         }
         else
         {
             $tmpObj = $this->getNode(substr($path, 0,
             $pos));
             return is_object($tmpObj)?
             $tmpObj->findNodeByPath(substr($path,
             $pos+1)):
             null;
         }
     }
     public function getSaveData()
     {
         $data = $this->values;
         if(sizeof($this->attributes)>0)
         $data[attrs] = $this->attributes;
         $nodeList = $this->getNode();

         if($nodeList==null)
         return $data;
         foreach($nodeList as $key=>$node)
         {
             $data[$key] = $node->getSaveData();
         }
         return $data;
     }

     public function getSaveXml($level=0)
     {
         $prefixSpace
         = str_pad("",
         $level, "\t");
         $str = "$prefixSpace<$this->nodeTag";
 
         foreach($this->attributes as $key=>$value)
         $str .= " $key=\"$value\"";
         $str .= ">\r\n";

         foreach($this->values as $key=>$value){
             if(is_array($value))
             {
                 $str .= "$prefixSpace\t<$key";
                 foreach($value[attrs] as $attkey=>$attvalue)
                 $str .= " $attkey=\"$attvalue\"";
                 $tmpStr = $value[value];

             }
             else
             {
                 $str .= "$prefixSpace\t<$key";
                 $tmpStr = $value;
             }
             $tmpStr = trim(trim($tmpStr, "\r\n"));
             $str .= ($tmpStr===null || $tmpStr==="")? " />\r\n": ">$tmpStr</$key>\r\n";
         }
         foreach($this->getNode() as $node)
         $str .= $node->getSaveXml($level+1)."\r\n";

         $str .= "$prefixSpace</$this->nodeTag>";
         return $str;
     }
 
     function __destruct()
     {
         unset($this->nodes, $this->attributes, $this->values);
     }
 }
?>