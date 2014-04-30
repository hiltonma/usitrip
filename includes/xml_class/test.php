<?php
require_once "xml/SimpleDocumentParser.php";
require_once "xml/SimpleDocumentBase.php";
require_once "xml/SimpleDocumentRoot.php";
require_once "xml/SimpleDocumentNode.php";
$test = new SimpleDocumentParser();
$test->parse("test.xml");
$dom = $test->getSimpleDocument();
echo "<pre>";
echo "<hr><font color=red>";
echo "下面是通过函数getSaveData()返回的整个xml数据的数组";
echo "</font><hr>";
print_r($dom->getSaveData());
echo "<hr><font color=red>";
echo "下面是通过setValue()函数，给给根节点添加信息，添加后显示出结果xml文件的内容";
echo "</font><hr>";
$dom->setValue("telphone", "123456789");
echo htmlspecialchars($dom->getSaveXml());
echo "<hr><font color=red>";
echo "下面是通过getNode()函数，返回某一个分类下的所有商品的信息";
echo "</font><hr>";
$obj = $dom->getNode("cat_food");
$nodeList = $obj->getNode();
foreach($nodeList as $node){
    $data = $node->getValue();
    echo "<font color=red>商品名：".$data[name]."</font><br>";
    print_R($data);
    print_R($node->getAttribute());
}
echo "<hr><font color=red>";
echo "下面是通过findNodeByPath()函数，返回某一商品的信息";
echo "</font><hr>";
$obj = $dom->findNodeByPath("cat_food|goods_food11");
if(!is_object($obj)){
    echo "该商品不存在";
}else{
    $data = $obj->getValue();
    echo "<font color=red>商品名：".$data[name]."</font><br>";
    print_R($data);
    print_R($obj->getAttribute());
}
echo "<hr><font color=red>";
echo "下面是通过setValue()函数，给商品\"food11\"添加属性, 然后显示添加后的结果";
echo "</font><hr>";
$obj = $dom->findNodeByPath("cat_food|goods_food11");
$obj->setValue("leaveword", array("value"=>"这个商品不错", "attrs"=>array("author"=>"hahawen", "date"=>date('Y-m-d'))));
echo htmlspecialchars($dom->getSaveXml());
echo "<hr><font color=red>";
echo "下面是通过removeValue()/removeAttribute()函数，给商品\"food11\"改变和删除属性, 然后显示操作后的结果";
echo "</font><hr>";
$obj = $dom->findNodeByPath("cat_food|goods_food12");
$obj->setValue("name", "new food12");
$obj->removeValue("desc");
echo htmlspecialchars($dom->getSaveXml());
echo "<hr><font color=red>";
echo "下面是通过createNode()函数，添加商品, 然后显示添加后的结果";
echo "</font><hr>";
$obj = $dom->findNodeByPath("cat_food");
$newObj = $obj->createNode("goods", array("id"=>"food13"));
$newObj->setValue("name", "food13");
$newObj->setValue("price", 100);
echo htmlspecialchars($dom->getSaveXml());
echo "<hr><font color=red>";
echo "下面是通过removeNode()函数，删除商品, 然后显示删除后的结果";
echo "</font><hr>";
$obj = $dom->findNodeByPath("cat_food");
$obj->removeNode("goods_food12");
echo htmlspecialchars($dom->getSaveXml());

?>