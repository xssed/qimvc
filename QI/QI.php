<?php
//设置输出编码
header ( "Content-type: text/html; charset=utf-8" );
//屏蔽系统错误
//error_reporting(0);
//加载配置文件
include_once ('config/Config.php');
//检测是否系统参数配置成功
if (! defined ( 'QI_PATH' ))exit ( "error!" );
//加载函数库
include_once (QI_LIB . "WebData.php");
include_once (QI_LIB . "Safe.php");
//加载类库
include_once (QI_LIB . "DB.class.php"); //必须的不可删除
include_once (QI_LIB . "Check.class.php"); //必须的不可删除
include_once (QI_LIB . "CreateHtml.class.php");
//判断网站模式    FALSE为上线模式  TRUE为开发者模式   默认上线模式
if (CREATE_WEB) {
	//检查模型和控制器是否存在，不存在则创建
	if (! file_exists ( QI_M . "entity/Models.php" )) {
		include_once QI_M . 'CreatModel.php';
	}
	if (! file_exists ( QI_C . "entity/Actions.php" )) {
		include_once QI_C . 'CreatAction.php';
	}
}
//加载核心
include_once (QI_M . "entity/Models.php");
//判断是否开启MVC模式    FALSE关闭    TRUE开启
if (QI_MVC_ON){
	include_once (QI_V . "View.php");
	include_once (QI_C . "Action.php");
}
?>