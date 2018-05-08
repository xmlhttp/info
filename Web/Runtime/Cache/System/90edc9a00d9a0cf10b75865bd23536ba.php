<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Present by vmuui.com 管理中心  - 修改密码</title>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link href="/Web/System/Public/css/main.css" type=text/css rel=stylesheet>
<script  src="/Public/jquery.js"></script>
</head>
<body>
    <!--顶部导航开始-->
<div class="topnav">
<a href="<?php echo U('/System/ManagerPage/BaseInfo');?>" class="home">首页</a>><a href="<?php echo U('/System/ManagerPage/sitesetup');?>">系统管理</a>><a href="javascript:void(0)">修改密码</a>
</div>
<!--顶部导航结束-->
<div class="cont_info">

<div class="tab_txt">
<div class="tab_tit">修改密码</div>
<form  method="post"  id="form1" action="<?php echo U('/System/ManagerPage/ChangPwdSave');?>">
     <table width="100%" border="0" cellpadding="4" cellspacing="0" class="info_tab">
          <tr>
            <td width="30%" height="22" align="right">原始密码：</td>
            <td align="left">                    
                 <input type="password" id="oldpwd" name="oldpwd" class="input1" onFocus="this.className='input1-bor'" onBlur="this.className='input1'"  style="width:240px"  /><span style=" margin-left:5px; color:#F00; display:none" id="oldpwd_tip">×原始密码不能为空</span>   
               </td>
          </tr>
          <tr>
            <td height="22" align="right">新&nbsp;密&nbsp;码：</td>
            <td  align="left">
				<input type="password" id="pwd" name="pwd" class="input1" onFocus="this.className='input1-bor'" onBlur="this.className='input1'"  style="width:240px"  /><span style=" margin-left:5px; color:#F00; display:none" id="pwd_tip">×新密码不能为空</span>  
			</td>
          </tr>
          <tr>
            <td height="22" align="right">
                确认密码：</td>
            <td  align="left">
              <input type="password" id="repwd" name="repwd" class="input1" onFocus="this.className='input1-bor'" onBlur="this.className='input1'"  style="width:240px"  /><span style=" margin-left:5px; color:#F00; display:none" id="repwd_tip">×两次输入的密码不同</span>        
            </td>
          </tr>
          <tr><td></td>
            <td height="50"  align="left">
             <input type="button" id="addsave" class="btn" value="修改密码" style=" width:144px; height:30px" /></td>
          </tr>
      </table>
      </form>
   </div>
<div id="footer" class="info_foot">
   <script>document.write(cmsname)</script>
</div>
</div>
<script>
$("#addsave").click(function(){
	$("#oldpwd_tip,#pwd_tip,#repwd_tip").hide();
	if($("#oldpwd").val().replace(/(^\s*)|(\s*$)/g, "").length==0){
		$("#oldpwd_tip").show();
		return false;	
	}
	if($("#pwd").val().replace(/(^\s*)|(\s*$)/g, "").length==0){
		$("#pwd_tip").show();
		return false;	
	}
	if($("#pwd").val()!=$("#repwd").val()){
		$("#repwd_tip").show();
		return false;	
	}
	var postdata=$("#form1").serialize();
	var url=$("#form1").attr("action");
	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data:postdata
	}).done(function(d) {
		if(d["status"]["err"]==0){
			alert("修改成功！");
		}else if(d["status"]["err"]==1){
			alert(d["status"]["msg"]);
			window.parent.location.href="<?php echo U('/System/Index');?>"
		}else{
			alert(d["status"]["msg"])	
		}
		}).fail(function() {
			alert("网络连接错误，请稍后再试！")
		})
		return false
	})
	
</script>

</body>
</html>