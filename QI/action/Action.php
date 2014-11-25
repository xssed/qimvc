<?php
if (! defined ( 'QI_PATH' ))exit ( "error!" );
//判断是否开启MVC URL路由模式  OFF关闭   ON开启    //动作指令值将被重新赋值
if (QI_MVC_URL) {
	include_once(QI_LIB . "Url.php");
	//动作指令值
	$qi_action = empty ( $arr_url ['action'] ) ? '' : trim ( $arr_url ['action'] ); //get action值
	$qi_do = empty ( $arr_url ['do'] ) ? '' : trim ( $arr_url ['do'] ); //get do值
	$qi_id = empty ( $arr_url ['parms']['id'] ) ? '' : safe_int ( $arr_url ['parms']['id'] ); //get id值 这里的id必须是数字对应数据表里的ID,强转
	$qi_page = empty ( $arr_url ['parms']['page'] ) ? '1' : safe_int ( $arr_url ['parms']['page'] ); //get page值  必须是数字对应数据表里的ID,强转
}else{
	//动作指令值
	$qi_action = empty ( $_GET['action'] ) ? '' : trim ( $_GET['action'] ); //get action值
	$qi_do = empty ( $_GET['do'] ) ? '' : trim ( $_GET['do'] ); //get do值
	$qi_id = empty ( $_GET['id'] ) ? '' : safe_int ($_GET['id'] ); //get id值 这里的id必须是数字对应数据表里的ID,强转
	$qi_page = empty ( $_GET['page'] ) ? '1' : safe_int ( $_GET['page'] ); //get page值  必须是数字对应数据表里的ID,强转
}
//加载action
include_once (QI_C . "entity/Actions.php");
?>