<?php
if(!defined('QI_PATH'))exit("error!");
//执行页面
switch ($qi_action){
case "":
include_once(QI_C.'entity/action.index.php');
break;
case "index":
include_once(QI_C.'entity/action.index.php');
break;
case "tpl":
include_once(QI_C.'entity/action.tpl.php');
break;
case "admin":
include_once(QI_C.'entity/action.admin.php');
break;
case "conf":
include_once(QI_C.'entity/action.conf.php');
break;
case "fun_pro":
include_once(QI_C.'entity/action.fun_pro.php');
break;
case "fun_type":
include_once(QI_C.'entity/action.fun_type.php');
break;
case "modual":
include_once(QI_C.'entity/action.modual.php');
break;
case "news":
include_once(QI_C.'entity/action.news.php');
break;
case "news_type":
include_once(QI_C.'entity/action.news_type.php');
break;
case "product":
include_once(QI_C.'entity/action.product.php');
break;
case "product_type":
include_once(QI_C.'entity/action.product_type.php');
break;
case "replyposts":
include_once(QI_C.'entity/action.replyposts.php');
break;
default:
echo "404!";
}
?>
