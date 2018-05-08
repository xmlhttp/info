<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Present by vmuui.com 管理中心 - 日志管理</title>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<link href="/Web/System/Public/css/main.css" type="text/css" rel="stylesheet">
	<link rel="STYLESHEET" type="text/css" href="/Web/System/Public/Tool/codebase/dhtmlxgrid.css">
	<script  src="/Public/jquery.js"></script>
	<script  src="/Web/System/Public/Tool/codebase/dhtmlxgrid.js"></script>
	<script type="text/javascript" src="/Web/System/Public/Tool/page/jquery.pagination.js"></script>
	<script  src="/Web/System/Public/js/System.js"></script>
</head>
<style>
     body{ overflow-y:hidden}
</style>
<body>
 
<!--顶部导航开始-->
<div class="topnav">
<a href="<?php echo U('/System/ManagerPage/BaseInfo');?>" class="home">首页</a>><a href="<?php echo U('/System/ManagerPage/sitesetup');?>">系统管理</a>><a href="javascript:void(0)">系统日志</a>
</div>
<!--顶部导航结束-->
<div class="cont_info">

<div class="tab_txt">
<div class="tab_tit">系统日志</div>

<div class="meun_tab">

<table  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="30" align="center"><img src="/Web/System/Public/images/icon_search.gif" width="26" height="22"></td>
	<td width="60" align="right">关键字：</td>
	<td style="width:170px; text-align:center">
        <input name="Input" class="input1" id="keywords" onFocus="this.className='input1-bor'" onBlur="this.className='input1'" style="width:150px; height:18px; line-height:18px"/></td>
	<td align="left">
        <input name="button" type="button" class="btn" style="width:55px"  value="查询" id="search" onclick="searchitem()" /></td><td align="right">
        <input name="button" type=button class="btn" onClick="delete_items()" value="删除选中项" style=" width:90px" />
         <input name="button" type=button class="btn" onClick="delete_all()" value="删除全部" style=" width:90px" />
         
        <input name="button" type=button class="btn" onClick="lock()" style="display:none" value="锁定选中的IP"  style=" width:90px"/>
        </td>
    </tr>
  </table>
</div>

<div class="db_div" >
<div id="gridbox"width="100%"></div>
</div>
<div id="setpage" class="paged">

</div>
</div>

<div id="div1" class="info_foot">
  Action Info: <div id='act_info' style="display:inline"></div>
</div>
</div>
<script>
	$("#keywords").val("")
var	myGridnum=6, //选中框所在的下标
	myGridmode='/System.php?s=/System/Log/', //模块地址
	myGrid = new dhtmlXGridObject('gridbox'); //新建列表
	myGrid.setImagePath("/Web/System/Public/Tool/codebase/imgs/dhxgrid_material/");
	myGrid.setHeader("ID,登录名,IP地址,浏览器,登录时间,动作,<a href='javascript:void(0)' onclick='chk_all()'>全选 </a>");
	myGrid.setInitWidths("80,140,100,130,150,*,60");
	myGrid.setColAlign("center,left,center,left,center,left,center");
	myGrid.setColTypes("ro,ro,ro,ro,ro,ro,ch");
	myGrid.setColSorting("int,str,str,str,str,str,str");
	myGrid.attachEvent("onCheckbox",doOnCheckEdit);
	myGrid.init();	
	
	function delete_all(){
		if (confirm("确认要清空所有日志吗？")){
			$("#act_info").html("<span class='msgload'>正在请求中…</span>")
			islink=true;
			$.ajax({
				url:myGridmode+'delall',
				type: 'POST',
				dataType: 'json'
			}).done(function(d) {
				$("#act_info").html("<span class='msgload'>正在处理中…</span>");
				islink=false;
				if(d["status"]["err"]==0){
					myGrid.clearAll();
					//分页
					$("#setpage").pagination(1,{
						num_edge_entries: 2,
    					num_display_entries: 9,
    					current_page:0,
						callback: pageselectCallback
					});
					setTimeout(function(){$("#act_info").html("<span class='msgright'>清理完成！</span>")},200);
				}else if(d["status"]["err"]==1){
					alert(d["status"]["msg"]);
					window.parent.location.href="/System.php"
				}else{
					setTimeout(function(){$("#act_info").html("<span class='msgerr'>"+d["status"]["msg"]+"</span>")},200);
				}
				
			}).fail(function() {
					islink=false;
					$("#act_info").html("<span class='msgerr'>网络错误！</span>")
			})
			
		}			
	}
	
</script>

</body>
</html>