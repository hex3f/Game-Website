<?php
	header('Content-type:text/html; charset=utf-8');
	// 注销后的操作
	
	$host = "";
	$dbusername = "";
	$dbpassword = "";
	$dbname = "";

	$db = new mysqli($host, $dbusername, $dbpassword, $dbname);
	$db->query("SET NAMES UTF-8");

	if ($db->connect_errno) {
		die("连接数据库失败");
	}
?>