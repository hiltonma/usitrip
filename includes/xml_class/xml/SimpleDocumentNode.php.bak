<?php
/**
 *===============================================
 *
 * @author     hahawen(ด๓มไวเฤ๊) 
 * @since      2004-12-04
 * @copyright  Copyright (c) 2004, NxCoder Group
 *
 *===============================================
 */
 /**
 * class SimpleDocumentNode
 * xml Node class, include values/attributes/subnodes.
 * all this pachage's is work for xml file, and method is action as DOM.
 *
 * @package SmartWeb.common.xml
 * @version 1.0
 */
 class SimpleDocumentNode extends SimpleDocumentBase
 {
     private $seq = null;
     private $rootObject = null;
     private $pNodeId = null;
     function __construct($rootObject, $pNodeId, $nodeTag, $seq)
     {
         parent::__construct($nodeTag);
         $this->rootObject = $rootObject;
         $this->pNodeId = $pNodeId;
         $this->seq = $seq;
     }
     public function getPNodeObject()
     {
         return ($this->pNodeId==-1)?
         $this->rootObject:
         $this->rootObject->getNodeById($this->pNodeId);
     }
     public function getSeq(){
         return $this->seq;
     }
     public function createNode($name, $attributes)
     {
         return $this->createNodeByName($this->rootObject,
         $name, $attributes,
         $this->getSeq());
     }
     public function removeNode($name)
     {
         return $this->removeNodeByName($this->rootObject, $name);
     }

     public function getNode($name=null)
     {
         return $this->getNodeByName($this->rootObject,
         $name);
     }
 }
?>