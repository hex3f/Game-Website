var isCheck_name = false;
function check_securityCode(){
	var securityCode = document.getElementById('securityCode').value; 
	var show = document.querySelector('#securityCode_text'); 
	reg=/^\d{6}$/;
	if (securityCode.length == 6 && reg.test(securityCode)){
		show.style.display='none';
		return true;
	} else{
		show.style.display='block';
		show.innerHTML='格式错误';
		return false;
	}
}
function check_name(){
	var btn = document.getElementById('embed-submit'); 
	var ajax = new XMLHttpRequest();
	ajax.open('post','./pair/pair_name.php');
	ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.send('Name='+document.querySelector('#user').value);
	ajax.onreadystatechange = function(){
	if (ajax.readyState ==4&&ajax.status==200) {
		var show = document.querySelector('#user_text');
			show.style.display='block';
		if (ajax.responseText =="not OK") {
			show.innerHTML='该用户已经注册了！';
			return isCheck_name = false;
		}else if (ajax.responseText =="not"){
			show.disabled='';
			show.innerHTML='格式错误！';
			return isCheck_name = false;
		}else if(ajax.responseText =="OK"){
			show.style.display='none';
			return isCheck_name = true;
		}
		}
	}
}
function check_pwd(){
	var pwd = document.getElementById('password').value; 
	var show = document.querySelector('#password_text'); 
        reg=/^[\w_-]{6,16}$/;
        if (pwd.length < 6 || pwd.length > 16 || !reg.test(pwd)){
			show.style.display='block';
			show.innerHTML='格式错误';
			return false;
		} else{
			show.style.display='none';
			return true;
        }    
}
function check_confirm(){
	var pwd = document.getElementById('password').value; 
	var cpwd = document.getElementById('confirm').value; 
	var show = document.querySelector('#confirm_text'); 
	if(pwd!=cpwd){
		show.style.display='block';
		show.innerHTML='密码不一致';
		return false;
	}else{
		show.style.display='none';
		return true;
	}
}
function check_mail(){
	var mail = document.getElementById('mail').value; 
	var show = document.querySelector('#mail_text'); 
        reg=/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/;
        if (!reg.test(mail)){
			show.style.display='block';
			show.innerHTML='格式错误';
			return false;
		} else{
			show.style.display='none';
			return true;
        }    
}
function check_all(){
	var show = document.getElementById('embed-submit'); 
	
	if (!isCheck_name){
		check_name();
		return false;
	}else if(!check_pwd()){
		return false;
	}else if(!check_confirm()){
		return false;
	}else if(!check_mail()){
		return false;
	}else{
		return true;
	}
}
function check_login(){
	var user = document.getElementById('username').value;
	var show_user = document.getElementById('login_user');
	var pwd = document.getElementById('password').value;
	var show_pwd = document.getElementById('login_pwd');
	if(user == ""){
		show_user.style = 'block';
		show_user.innerHTML = '游戏名称不能为空';
		return false;
		
	}else if(pwd == ""){
		show_pwd.style = 'block';
		show_pwd.innerHTML = '密码不能为空';
		return false;
	}else{
		return true;
	}
}