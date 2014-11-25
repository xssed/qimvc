<?php
require ("config.php");
echo "<font color='#009900'>自动监控一次网站打开情况！并记录日志！</font><br/><br/>";
date_default_timezone_set ( 'PRC' );
error_reporting ( E_ALL ^ E_NOTICE );
header ( "Content-Type: text/html; charset=utf-8" );

//监控网站状态
function jiankong($jiankong_url, $duankou) {
	ignore_user_abort (); //关掉浏览器，PHP脚本也可以继续执行. 
	set_time_limit ( 0 ); // 通过set_time_limit(0)可以让程序无限制的执行下去 
	$web_jiankong_log_file = dirname ( __FILE__ ) . '/logs.html';
	$log_file_fp = fopen ( $web_jiankong_log_file, "r" );
	$web_jiankong_log_text = fread ( $log_file_fp, filesize ( $web_jiankong_log_file ) );
	fclose ( $log_file_fp );
	do {
		//循环URL
		foreach ( $jiankong_url as $url_val ) {
			$web_jiankong_log_text .= jiankong_log ( $url_val, $duankou );
		}
		$web_jiankong_log_text .= "<hr /><br />";
		$log_file_fp_w = fopen ( $web_jiankong_log_file, "w" );
		fwrite ( $log_file_fp_w, $web_jiankong_log_text );
		fclose ( $log_file_fp_w );
		sleep ( 3600 ); //休眠
	} while ( true );
}

//记录URL返回数据
function jiankong_log($jiankong_url_link, $duankou) {
	$web_jiankong_log_fp = fsockopen ( $jiankong_url_link, $duankou, $errno, $errstr, 6 );
	if ($web_jiankong_log_fp) {
		$log_text .= $jiankong_url_link . "&nbsp;&nbsp;&nbsp;&nbsp;(" . date ( 'Y-m-d H:i:s' ) . ")<font color='#009900'>&nbsp;&nbsp;&nbsp;&nbsp;网站能够打开!</font><br/><br/>";
	} else {
		$log_text .= $jiankong_url_link . "&nbsp;&nbsp;&nbsp;&nbsp;(" . date ( 'Y-m-d H:i:s' ) . ")<font color='#red'>&nbsp;&nbsp;&nbsp;&nbsp;网站可能有异常!</font><br/><br/>";
		@mail("927935822@qq.com"," error",$jiankong_url_link . "&nbsp;&nbsp;&nbsp;&nbsp;(" . date ( 'Y-m-d H:i:s' ) . ")<font color='#red'>&nbsp;&nbsp;&nbsp;&nbsp;网站可能有异常!</font><br/><br/>");//接收异常邮件
	}
	$web_jiankong_log_fp_two = fsockopen ( "www." . $jiankong_url_link, $duankou, $errno, $errstr, 6 );
	if ($web_jiankong_log_fp_two) {
		$log_text .= "www." . $jiankong_url_link . "&nbsp;&nbsp;&nbsp;&nbsp;(" . date ( 'Y-m-d H:i:s' ) . ")<font color='#009900'>&nbsp;&nbsp;&nbsp;&nbsp;网站能够打开!</font><br/><br/>";
	} else {
		$log_text .= "www." . $jiankong_url_link . "&nbsp;&nbsp;&nbsp;&nbsp;(" . date ( 'Y-m-d H:i:s' ) . ")<font color='#red'>&nbsp;&nbsp;&nbsp;&nbsp;网站可能有异常!</font><br/><br/>";
		@mail("927935822@qq.com"," error","www." .$jiankong_url_link . "&nbsp;&nbsp;&nbsp;&nbsp;(" . date ( 'Y-m-d H:i:s' ) . ")<font color='#red'>&nbsp;&nbsp;&nbsp;&nbsp;网站可能有异常!</font><br/><br/>");//接收异常邮件
	}
	return $log_text;
}
jiankong ( $jiankong_url, $duankou );
?>
 
 