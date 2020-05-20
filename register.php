<?php 
header('Content-type:text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-cn" >
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
	<meta charset="utf-8">
	<title>镜花水月贰 | 账号注册</title>
	<link rel="stylesheet" media="screen" href="css/register.css" />
	<style>
	#embed-captcha {
		width: 300px;
		margin: 0 auto;
	}
	.show {
		display: block;
	}
	.hide {
		display: none;
	}
	#notice {
		color: red;
	}
	</style>
	
	<!-- Favicons
	================================================== -->
	<link rel="icon" type="image/png" href="favicon.ico">
	
</head>
<body>
	<div style="text-align:center;clear:both" style="position: fixed;top: 0px;"><div>
	<form id="msform" action="check/regcheck.php" method="post" onsubmit="return check_all()">
		<!-- progressbar -->
		<ul id="progressbar" style="font-family:微软雅黑;font-size:12px">
			<li class="active"><font style="font-size:14px;">取名字</font></li>
			<li><font style="font-size:14px;">加特技</font></li>
			<li><font style="font-size:14px;">Duang</font></li>
		</ul>
		<!-- fieldsets -->
		<fieldset>
			<h2 class="fs-title">给你的角色取一个名字吧！</h2>
			<h3 class="fs-subtitle">第一步</h3>
			<div class="ui">
				<input type="text"  placeholder="角色名" style="font-size:16px;" class="ui-input" name="username" id="user" onkeyup="check_name()"/>
			</div>
			<span id="user_text" style="display:none;color:red;"></span>
			<input type="button" name="next" class="nextName action-button" value="下一步" id="nextTime"/>
			<h4>用户名要求:</h4>
			<h4>开头必须是字母</h4>
			<h4>只能包含字母和数字</h4>
			<h4>最短4位，最长12位</h4>
		</fieldset>
		<fieldset>
			<h2 class="fs-title">给你的账号一个超强的盾牌吧！</h2>
			<h3 class="fs-subtitle">第二步</h3>
			<input type="password" placeholder="密码" name="password" id="password" onkeyup="check_pwd()"/>
			<span id="password_text" style="display:none;color:red;padding-bottom:10px;"></span>
			
			<input type="password" placeholder="确认密码" name="confirm" id="confirm" onkeyup="check_confirm()"/>
			<span id="confirm_text" style="display:none;color:red;padding-bottom:10px;"></span>
			
			<input type="text" placeholder="邮箱" name="mail" id="mail" onkeyup="check_mail()"/>
			<span id="mail_text" style="display:none;color:red;padding-bottom:10px;"></span>
			
			<input type="button" name="previous" class="previous action-button" value="上一步" />
			<input type="button" name="next" class="nextPassword action-button" value="下一步" />
			<h4>密码要求:</h4>
			<h4>最短6位，最长16位</h4>
			<h4>可以包含大小写字母或数字</h4>
			<h4>可以包含下划线 [ _ ] 和减号 [ - ]</h4>
			<h4 style="color:red;">认认真真填写邮箱，不然以后会找不回密码的噢！</h4>
		</fieldset>
		<fieldset>
			<h2 class="fs-title">安全校验！</h2>
			<h3 class="fs-subtitle">最后一步咯</h3>
			<!-- 安全校验 -->
			<div class="contact-icon" style="display:flex">
				<div style="margin:0 auto;text-align:center;">
					<div id="embed-captcha"></div>
					<p id="wait" class="show">正在加载验证码......</p>
					<p id="notice" class="hide">请先完成验证</p>
				</div> 
			</div>
			<input type="button" name="previous" class="previous action-button" value="上一步" />
			<!-- 提交按钮 -->
			<input type="submit" class="action-button" name="Submit" value="注册" id="embed-submit"/>													
		</fieldset>
	
	</form>
	<canvas class="canvas" style="position: fixed;bottom: 0px;left:0;height:40%"></canvas>
	
	<!-- 注册页面 -->
	<script src="js/register/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="js/register/jquery.easing.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/register/register.js"></script>
	
	<!-- 校验 -->
	<script type="text/javascript" src="js/check.js"></script>
	<script src="static/gt.js"></script>
	<script>
		var handlerEmbed = function (captchaObj) {
			$("#embed-submit").click(function (e) {
				var validate = captchaObj.getValidate();
				if (!validate) {
					$("#notice")[0].className = "show";
					setTimeout(function () {
						$("#notice")[0].className = "hide";
					}, 2000);
					e.preventDefault();
				}
			});
			captchaObj.appendTo("#embed-captcha");
			captchaObj.onReady(function () {
				$("#wait")[0].className = "hide";
			});
		};
		$.ajax({
			url: "web/StartCaptchaServlet.php?t=" + (new Date()).getTime(),
			type: "get",
			dataType: "json",
			success: function (data) {
				initGeetest({
					gt: data.gt,
					challenge: data.challenge,
					new_captcha: data.new_captcha,
					product: "embed",
					offline: !data.success
				}, handlerEmbed);
			}
		});
	</script>
</body>
</html>
