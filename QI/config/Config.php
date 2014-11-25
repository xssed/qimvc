<?php
//当前模式   FALSE为上线模式  TRUE为开发者模式(开启调试模式)
define(CREATE_WEB,TRUE);
//QI常量设置
define(WEB_PATH,$_SERVER["DOCUMENT_ROOT"]."/");
define(QI_PATH,WEB_PATH."QI/");
define(QI_LIB,WEB_PATH."QI/lib/");
define(QI_M,WEB_PATH."QI/model/");
define(QI_V,WEB_PATH."QI/view/");
define(QI_C,WEB_PATH."QI/action/");
define(QI_LOG,WEB_PATH."QI/log/");
//MVC调试模式  FALSE关闭    TRUE开启
if(CREATE_WEB){
//加载调试类
include_once (QI_LIB . "Debug.class.php");
Debug::start();
}
//MVC模式配置  FALSE关闭    TRUE开启
define(QI_MVC_ON,TRUE);
//MVC URL路由模式  FALSE关闭    TRUE开启    路由模式和url正常模式不能共存，安全需要。
define(QI_MVC_URL,TRUE);
//=============================Model配置==============================
date_default_timezone_set("Asia/Shanghai");
define('DB_Host', '127.0.0.1');
define('DB_User', 'root');
define('DB_PWD', '');
define('DB_Name', '201411_aojian');

$mysql_link = mysql_connect(DB_Host, DB_User, DB_PWD);
if ($mysql_link) {
    mysql_query("set names 'UTF8'");
    mysql_select_db(DB_Name, $mysql_link);
    //mysql_db_query();//可选择不同数据库查询
}

$memcache = null; //没有安装Memcache服务，请将$memcache设置为null
//$memcache = new Memcache;
//$memcache->connect('192.168.1.11', 11211) or die('连接缓存服务器失败');

//设置系统需要的参数
$qi_PageSize_news=3;//新闻类显示条数
$qi_PageSize_product=2;//产品类显示条数

?>