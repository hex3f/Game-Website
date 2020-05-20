<?php
	header('Content-type:text/html; charset=utf-8');
	// 注销后的操作
	session_start();
	if (isset($_SESSION['islogin'])) {
		// 若已经登录 跳转到登录成功的首页
		header('location:../index.php');
	}

    header('content-type:text/html;charset=utf-8');
    $name=$_POST['Name'];
	
    //对比是数据可以通过数据库获取，并验证
	include("../check/db.php");   //连接数据库   
	$sql = "select username from authme where username = '{$_POST['Name']}'"; //SQL语句  
	$result = $db->query($sql);    //执行SQL语句  
	$num = mysqli_num_rows($result); //统计执行结果影响的行数  
	
	$un_len = strlen($name);
    // 返回判定值给调用者
    if(!$num){
		if ($un_len <= 3 || $un_len > 12 || !preg_match("/^[0-9a-zA-Z_]{1,}$/", $_POST['Name'])){
			echo "not";
			die();
		}else{
			echo "OK";
			die();
		}
    } else{
        echo "not OK";
		die();
    }
?>