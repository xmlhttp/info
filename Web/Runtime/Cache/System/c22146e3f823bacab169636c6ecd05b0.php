<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Present by vmuui.com 管理中心 - 友链列表</title>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
    <link href="/Web/System/Public/css/main.css" type="text/css" rel="stylesheet">
	<link rel="STYLESHEET" type="text/css" href="/Web/System/Public/Tool/codebase/dhtmlxgrid.css">
	<script  src="/Public/jquery.js"></script>
	<script  src="/Web/System/Public/Tool/codebase/dhtmlxgrid.js"></script>
	<script type="text/javascript" src="/Web/System/Public/Tool/page/jquery.pagination.js"></script>
	<script  src="/Web/System/Public/js/System.js"></script>
<style>
     body{ overflow-y:hidden}
</style>
</head>
<body>
<!--顶部导航开始-->
<div class="topnav">
<a  href="/System.php?s=/System/ManagerPage/BaseInfo.html" class="home">首页</a>><a href="/System.php?s=/System/Link">友链管理</a>><a href="javascript:void(0)">链接列表</a>
</div>
<!--顶部导航结束-->
<div class="cont_info">

<div class="tab_txt">
<div class="tab_tit"><a class="export" href="javascript:alert('功能未开放！')" style="display:none">导出Excel</a>链接列表</div>

<div class="meun_tab">

  <table  width="95%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="30" align="center"><img src="/Web/System/Public/images/icon_search.gif" width="26" height="22"></td>
      <td width="45" align="right">类别：</td>
      <td width="145" align="left"><select name="select" id="menu" style="width:140px; height:28px; line-height:28px; color:#4b4b4b" onchange="searchitem()"><?php echo ($menu); ?></select></td>
	  <td width="60" align="right">关键字：</td>
      <td style="width:170px; text-align:center">
        <input name="Input" class="input1" id="keywords" onFocus="this.className='input1-bor'" onBlur="this.className='input1'" style="width:150px; height:18px; line-height:18px"/></td>
        <td align="left">
        <input name="button" type="button" class="btn" style="width:55px"  value="查询" id="search" onclick="searchitem()" /></td>
      <td  align="right">
      <input name="button" type=button class="btn" onClick="item_up()" value="上移"  style="width:55px"/>&nbsp;<input name="button" type=button class="btn" onClick="item_down()" value="下移"  style="width:55px"/>&nbsp;<input name="button" type=button class="btn" onClick="delete_items()" value="删除选中" /> </td>
    </tr>
  </table>
</div>
<div class="db_div" >
<div id="gridbox" width="100%"></div>
</div>
<div id="setpage" class="paged" >
</div>
</div>
<DIV id="DIV1" class="info_foot">
  Action Info: <span id=act_info style="display:inline"></span>
</DIV>
</div>
<script>
	$("#menu>option:eq(0)").attr("selected","selected")
	$("#keywords").val("")
var	myGridnum=5, //选中框所在的下标
	myGridmode='/System.php?s=/System/Link/', //模块地址
	myGrid = new dhtmlXGridObject('gridbox'); //新建列表
	myGrid.setImagePath("/Web/System/Public/Tool/codebase/imgs/dhxgrid_material/");
	myGrid.setHeader("ID,链接名称,加入时间,启用,编辑,<a href='javascript:void(0)' onclick='chk_all()'>全选 </a>");
	myGrid.setInitWidths("80,*,160,80,80,80");
	myGrid.setColAlign("center,left,center,center,center,center");
	myGrid.setColTypes("ro,ed,ro,ch,link,ch");
	myGrid.setColSorting("int,str,str,str,str,str");
	myGrid.attachEvent("onEditCell",doOnCellEdit);
	myGrid.attachEvent("onCheckbox",doOnCheckEdit);
	myGrid.init();

	
</script>
</body>
</html>