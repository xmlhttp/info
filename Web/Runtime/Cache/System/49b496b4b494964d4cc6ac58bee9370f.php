<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Present by vmuui.com 管理中心 - 网站设置</title>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link href="/Web/System/Public/css/main.css" type=text/css rel=stylesheet>
<script  src="/Public/jquery.js"></script>
</head>
<body>
    <!--顶部导航开始-->
<div class="topnav">
<a href="<?php echo U('/System/ManagerPage/BaseInfo');?>" class="home">首页</a>><a href="<?php echo U('/System/ManagerPage/sitesetup');?>">系统管理</a>><a href="javascript:void(0)">系统设置</a>
</div>
<!--顶部导航结束-->
<div class="cont_info">

<div class="tab_txt">
<div class="tab_tit">网站设置</div>
<form  method="post"  id="form1" action="<?php echo U('/System/ManagerPage/sitesetup_updata');?>" >
    <table width="100%" border="0" cellpadding="2" cellspacing="0"  class="siteup_tab">
        
        
        <tr>
          <td width="25%"  align="right" >网站名称：</td>
          <td align="left"><input type="text" class="input1" onFocus="this.className='input1-bor'" onBlur="this.className='input1'"  style="width:240px" id="sitename1" name="sitename1" value="<?php echo ($Site["sitename"]); ?>" /> <span style=" margin-left:5px; color:#F00; display:none" id="sitename1_tip">×不能为空</span>
          </td>
        </tr>
       
       
        <tr>
          <td align="right">网站地址：</td>
          <td align="left"><input type="text" class="input1" onFocus="this.className='input1-bor'" onBlur="this.className='input1'"  style="width:240px" id="siteweb1" name="siteweb1" value="<?php echo ($Site["siteWeb"]); ?>" /><span style="margin-left:15px; color:#00F">*不用http://</span>
			</td>
        </tr>


        <tr>
          <td align="right">网站标题：</td>
          <td align="left"><input type="text" class="input1" style="width:424px" id="page_tit" name="page_tit" value="<?php echo ($Site["page_tit"]); ?>" /></td>
        </tr>
		
		<tr>
          <td align="right">网站关键字：</td>
          <td align="left"><input type="text" class="input1" style="width:424px" id="page_key" name="page_key" value="<?php echo ($Site["page_key"]); ?>" /></td>
        </tr>

		<tr>
          <td align="right">网站描述：</td>
          <td align="left"><textarea class="input1" id="page_desc" name="page_desc" style="width:424px; height:68px" ><?php echo ($Site["page_desc"]); ?></textarea></td>
        </tr>

		<tr>
			<td align="right" class="STYLE2" height="20" >锁定IP ：</td>
			<td align="left"><textarea class="input1" id="lock_ip" name="lock_ip" onFocus="this.className='input1-bor'" onBlur="this.className='input1'"  style="width:424px; height:68px" ><?php echo ($Site["lock_ip"]); ?></textarea><span style="margin-left:15px; color:#00F">*用逗号隔开</span>
			</td>
		</tr>
           
           
                 
        <tr>
          <td height="50" width="20%"></td>
          <td align="left"><input type="button" id="addsave" class="btn" value="更新" style=" width:144px; height:35px" />
          </td>
        </tr>
        
      </table>
      </form>
   </DIV>
<div id="footer" class="info_foot">
   <script>document.write(cmsname)</script>
</div>
</div>
<script>
function toVaild(){
	if($("#sitename1").val().replace(/(^\s*)|(\s*$)/g, "").length==0){
		$("#sitename1_tip").show();
		return false;	
	}	
}

$("#addsave").click(function(){
	$("#sitename1_tip").hide();
	if($("#sitename1").val().replace(/(^\s*)|(\s*$)/g, "").length==0){
		$("#sitename1_tip").show();
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