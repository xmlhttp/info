$(function(){

	adjust()
	$(window).resize(function() {adjust()});
	var isloading=false;
	$("#msg").html('');
	//登录按钮
	$("#Button1").click(function(){
		if(isloading){
			return;	
		}
		$("#msg").html('<span class="msgload">登录中...</span>');
		var username=$("#user_name").val();
		var userpwd=$("#user_pwd").val();
		var usercode=$("#vcode").val();
		if(username.length==0){
			setTimeout(function(){$("#msg").html('<span class="msgerr">用户名不能为空</span>');},200)
			return;	
		}
		
		if(userpwd.length==0){
			setTimeout(function(){$("#msg").html('<span class="msgerr">密码不能为空</span>');},200)
			return;	
		}
		
		if(usercode.length==0){
			setTimeout(function(){$("#msg").html('<span class="msgerr">验证码不能为空</span>');},200)
			return;	
		}
		var da={"uname":username,"upwd":userpwd,"ucode":usercode};
		isloading=true;
		$.ajax({
			url: "/System.php?s=/System/Index/Login",
			type: 'POST',
            dataType: 'json',
            data: da
        }).done(function(d) {
			isloading=false;
            if (d["status"]["err"] == 0) {
				window.location.href="/System.php?s=/System/ManagerPage"
			}else{
				setTimeout(function(){
					$("#msg").html('<span class="msgerr">'+d["status"]["msg"]+'</span>');
					$("#codeimg").attr("src", '/System.php?s=/System/Index/verify.html&'+Math.random());
				 },200)
			}
		}).fail(function() {
			isloading=false;
            setTimeout(function() {
				$("#ts1").html('<span class="msgerr">服务器错误！</span>');
				$("#codeimg").attr("src", '/System.php?s=/System/Index/verify.html&'+Math.random());
			}, 200);
        })
	})
	//验证码点击
	$("#codeimg").click(function(){
		$(this).attr("src", '/System.php?s=/System/Index/verify.html&'+Math.random());  
	})
	
	
})
function adjust(){
	var conHei = $(window).outerHeight(true) - 93
	$("#logmid").height(conHei);
}