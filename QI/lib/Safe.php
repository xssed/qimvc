<?php
// 全局安全模式过滤接收到的数据
if (! get_magic_quotes_gpc ()) {
	! empty ( $_POST ) && Add_S ( $_POST );
	! empty ( $_GET ) && Add_S ( $_GET );
	! empty ( $_COOKIE ) && Add_S ( $_COOKIE );
	! empty ( $_SESSION ) && Add_S ( $_SESSION );
	! empty ( $_REQUEST ) && Add_S ( $_REQUEST );
}
! empty ( $_FILES ) && Add_S ( $_FILES );
/**
 * 全局安全过滤
 */
function Add_S(&$array) {
	if (is_array ( $array )) {
		foreach ( $array as $key => $value ) {
			if (! is_array ( $value )) {
				$array [$key] = addslashes ( $value );
			} else {
				Add_S ( $array [$key] );
			}
		}
	}
}
/**
 * 接受处理数据
 */
function RequestString($Key) {
	if ($_REQUEST [$Key] == null)
		return "";
	else {
		if (! empty ( $_REQUEST [$Key] )) {
			$array = $_REQUEST [$Key];
			if (count ( $array ) > 1)
				$val = implode ( ',', $array );
			else
				$val = $_REQUEST [$Key];
		
		}
		if (is_array ( $val ))
			$val = $val [0];
		$val = trim ( $val );
		$val = filter_sql($val);
		$val = filter_html($val);
		//$val = mysql_escape_string($val);
		return $val;
	}
}
/**
 * 处理数字数据
 */
function RequestInt($Key, $DefalutVal) {
	$val = RequestString ( $Key );
	if (preg_match ( "/^\d*$/", $val ) && $val != "") {
		return $val;
	}
	return $DefalutVal;
}
/**
 * 处理日期数据
 */
function RequestDate($Key, $DefalutVal) {
	$val = RequestString ( $Key );
	if (preg_match ( "/^\d{4}-\d{2}-\d{2}$/", $val )) {
		return $val;
	}
	return $DefalutVal;
}
/**
 * 处理时间数据
 */
function RequestTime($Key, $DefalutVal) {
	$val = RequestString ( $Key );
	if (preg_match ( "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $val )) {
		return $val;
	}
	return $DefalutVal;
}
/**
 * 处理float数据
 */
function RequestFloat($Key) {
	$val = floatval ( RequestString ( $Key ) );
	return $val;
}
/**
 * 处理安全数据
 */
function RequestSafeString($Key) {
	$val = htmlspecialchars ( RequestString ( $Key ) );
	return $val;
}
/**
 * 过滤SQL关键字
 */
function filter_sql($str) {
	
	$str = str_replace ( "'", "’", $str );
	return $str;
}
/**
 * 简单过滤危险HTML
 */
function filter_html($str) {
	$str = preg_replace ( "@<script(.*?)</script>@is", "", $str );
	$str = preg_replace ( "@<iframe(.*?)</iframe>@is", "", $str );
	return $str;
}
?>