<?php
//URL路由模块
//$_UrlPath = explode(".php",$_SERVER["REQUEST_URI"]);
//$_AppPathArr = explode ( "/", $_UrlPath );
$_UrlPath = explode(".php",$_SERVER["REQUEST_URI"]);
$_AppPathArr = explode ( "/", "/QIMVC".$_UrlPath[1] );
$_AppPathArr_Count = count($_AppPathArr );
$url_parms_arr=array();
if ($_AppPathArr_Count > 3 ){
	for($i = 4; $i < $_AppPathArr_Count; $i+=2){
		$url_parms_arr[$_AppPathArr[$i]]=$_AppPathArr[$i+1];
	}
}
$arr_url = array (
           'action' => '', 
           'do' => '', 
           'parms' => $url_parms_arr//把URL里其它的参数的值传给parms 
);
$arr_url['action'] = $_AppPathArr [2];
$arr_url['do'] = $_AppPathArr [3];
//循环赋值到$_REQUEST  $_GET   $_POST
foreach ($arr_url ['parms']  as $key=>$valve){
	$_REQUEST[$key]=$valve;
}
$url_module_name = $arr_url ['action'];
$url_method_name = $arr_url ['do'];
$url_parms_attay = $arr_url ['parms'];
//print_r($arr_url);
?>