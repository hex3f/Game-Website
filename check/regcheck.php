<?php
	header('Content-type:text/html; charset=utf-8');
	
	session_start();
	if (isset($_SESSION['islogin'])) {
		// 若已经登录 跳转到登录成功的首页
		header('location:../index.php');
	}
	header("content-type:text/html;charset=utf-8");
	//加密算法
	function getRStr($len){  
    $chars = array(  
        "a", "b", "c", "d", "e", "f", "0", "1", "2",    
        "3", "4", "5", "6", "7", "8", "9"  
    );  
    $charsLen = count($chars) - 1;  
    shuffle($chars);    // 将数组打乱   
        
    $output = "";  
    for ($i=0; $i<$len; $i++)  
    {  
        $output .= $chars[mt_rand(0, $charsLen)];  
    }  
    return $output;  
	} 
	
	//获取用户ip(外网ip 服务器上可以获取用户外网Ip 本机ip地址只能获取127.0.0.1)
	function getip(){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else if(!empty($_SERVER["REMOTE_ADDR"])){
		$cip = $_SERVER["REMOTE_ADDR"];
		}
		else{
		$cip = '';
		}
		preg_match("/[\d\.]{7,15}/", $cip, $cips);
		$cip = isset($cips[0]) ? $cips[0] : 'unknown';
		unset($cips);
		return $cip;
	}
	
    if(isset($_POST["Submit"]) && $_POST["Submit"] == "注册")  
    {
        $user = $_POST["username"];  
		$Salt =  getRStr(16);
        $psw = $_POST["password"];
        $psw_confirm = $_POST["confirm"];
		$mail = $_POST["mail"];
		
		//获取ip
		$ip=getip();
		
		// 取各个变量长度
		$un_len = strlen($_POST["username"]);
		$pw_len = strlen($_POST["password"]);
		
		// 正则判断各个变量是否符合格式, 不符合格式立即die(); 安全守则: 永远不要相信用户的输入..

		//判断输入是否为空
        if($user == "" || $psw == "" || $psw_confirm == "" || $mail == "")  
        {  
            die('请确认信息完整性.'); 
        }else if ($un_len < 3 || $un_len > 12 || !preg_match("/^[0-9a-zA-Z_]{1,}$/", $user)){
			die('用户名格式错误.');
		}else if ($pw_len < 6 || $pw_len > 16 || !preg_match("/^[\w_-]{6,16}$/", $psw)){
			die('密码格式错误.');
		}else if($psw != $psw_confirm){//确认密码一致
			die('密码不一致.');
        }else if(!preg_match("/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/", $mail)){//校验邮箱格式
			die('邮箱格式错误.');
		}else{
			
			/*-----------------------------开始写入数据库-------------------------------*/
			include("db.php");
			$sql = "select username from authme where username = '$_POST[username]'"; //SQL语句  
			$result = $db->query($sql);    //执行SQL语句  
			$num = mysqli_num_rows($result); //统计执行结果影响的行数  
			if($num)    //如果已经存在该用户  
			{  
				echo "<script>alert('用户名已存在'); history.go(-1);</script>";  
			}  
			else    //不存在当前注册用户名称  
			{  
			
				//写入地理位置数据并写入数据库
				//根据百度地图api得到用户Ip地理经纬度和城市
 
				$url = "http://api.map.baidu.com/location/ip?ak=xxxxxxxxxx&ip=$ip&coor=bd09ll";  //填写你的百度AK号码
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				if(curl_errno($ch))
				{ echo 'CURL ERROR Code: '.curl_errno($ch).', reason: '.curl_error($ch);}
				curl_close($ch);
				$info = json_decode($output, true);
				if($info['status'] == "0"){
					$lotx = $info['content']['point']['y'];
					$loty = $info['content']['point']['x'];
					$citytemp = $info['content']['address_detail']['city'];
					$keywords = explode("市",$citytemp);
					$city = $keywords[0];
				}
				else{//如果数据异常
					$lotx = "34.2597";
					$loty = "108.9471";
					$city = "西安";
				}
				 
				 
				// var_dump($lotx);//x坐标  纬度
				// var_dump($loty);//y坐标  经度
				// var_dump($city);//用户Ip所在城市
				$file = fopen("../position.txt","aw") or exit("不能打开");
				fwrite($file,",[".$loty.",".$lotx."]");
			
			
				$pwd_sha256 = '$SHA$'.$Salt.'$'.hash('sha256', hash('sha256',$_POST['password'], false).$Salt, false);
				$pwd_md5 = hash('md5',$_POST['password']);
				$sql_insert = "insert into authme (username,password,x,y,z,world,email,regip) values('$_POST[username]','$pwd_sha256',0,0,0,'world','$_POST[mail]','$ip')";  
				$res_insert = $db->query($sql_insert);  
				$sql_insert = "insert into web (username,password,email,jing,wei,city,ip) values('$_POST[username]','$pwd_md5','$_POST[mail]','$loty','$lotx','$city','$ip')";  
				$res_insert = $db->query($sql_insert);  
				if($res_insert)  
				{  
					//跳转到注册成功页面
					echo "<meta http-equiv='refresh' content='10; url=../index.php'>";
					echo "<body style='background".":black' >";
					echo "<p style='width: 100%;height: 45px;display: block;line-height: 45px;text-align: center;font-family: 微软雅黑;color:white;'>注册成功 十秒后跳转至首页 请谨记账号密码</p>"; 
					echo "<p style='width: 100%;text-align: center;color:white;'>你好，来自：".$city."的朋友</p>"; 
					echo "<p style='width: 100%;text-align: center;color:white;'>你的账号是".$user."</p>";  
					echo "<p style='width: 100%;text-align: center;color:white;'>你的密码是".$psw."</p>"; 
					echo "<p style='width: 100%;text-align: center;color:white;'>你的邮箱是".$mail."</p>";
					echo "<a style='width: 100%;height: 45px;display: block;line-height: 45px;text-align: center;font-family: 微软雅黑;color:white;' href='../index.php'>直接跳转至首页</a>";
					echo "</body>";
				}  
				else 
				{  
					echo "<script>alert('系统繁忙，请稍候！'); history.go(-1);</script>";  
				}  
			}
			/*-----------------------------结束写入数据库-------------------------------*/
			
		}
    }else{
        echo "<script>alert('提交未成功！'); history.go(-1);</script>";  
    }  
?>