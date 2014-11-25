<?php
class Check {
	/*打印友好的数组形式*/
	static function dump($array) {
		echo "<pre>";
		print_r ( $array );
		echo "<pre>";
	}
	
	//中文截取
	static function cnString($text, $length) {
		if (strlen ( $text ) <= $length) {
			return $text;
		}
		$str = substr ( $text, 0, $length ) . chr ( 0 );
		return $str;
	}
	
	//读取目录所有的文件名
	static function myreaddir($dir) {
		$handle = opendir ( $dir );
		$i = 0;
		while ( $file = readdir ( $handle ) ) {
			if ($file != "." && $file != ".." && ! is_dir ( $file )) {
				$list [$i] = $file;
				$i = $i + 1;
			}
		}
		closedir ( $handle );
		rsort ( $list );
		return $list;
	}
	/*得到当前URL地址*/
	static function get_Url() {
		return 'http://' . $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	}
}
?>