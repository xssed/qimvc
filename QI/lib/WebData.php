<?php
/*格式化代码*/
function FTXT($str, $i = 0) {
	for($j = 0; $j < $i; $j ++) {
		$left .= "  ";
	}
	return $left . $str . "\n";
}
/*安全转换数字*/
function safe_int($param) {
	return intval ( trim ( $param ) );
}
/*格式化输出数据*/
function format_object($str) {
	return json_decode ( json_encode ( $str ), true );
}
/*alert*/
function alert($str,$url)
{
	echo "<script language='javascript'>alert('" . $str . "');";
	if(strlen($url)>0)
	{
		echo "window.location='".$url."';</script>";
	}
	else
	{
		echo "history.go(-1);</script>";
	}
}
?>
