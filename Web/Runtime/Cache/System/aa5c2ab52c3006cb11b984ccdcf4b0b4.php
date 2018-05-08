<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Present by vmuui.com 管理中心 - 目录结构编辑器</title>
	<link href="/Web/System/Public/css/main.css" type="text/css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="/Web/System/Public/Tool/codebase/dhtmlxtree.css">
    <script  src="/Public/jquery.js"></script>
  	<script  src="/Web/System/Public/Tool/codebase/dhtmlxtree.js"></script>
    <script  src="/Web/System/Public/Tool/pinyin/pinyin.js"></script>
    
	<script>
    function query(tree_name) {
        var str = $("#" + tree_name).val().trim();
		if (str == "") return;
		var arrRslt = makePy(str);
		$("#new_name_en").val(arrRslt);
	}
	</script>
</head>
<body>
<!--顶部导航开始-->
<div class="topnav">
<a href="/System.php?s=/System/ManagerPage/BaseInfo.html" class="home">首页</a>><a href="javascript:void(0)">目录结构编辑器</a>
</div>
<!--顶部导航结束-->
<div class="cont_info">

<div class="tab_txt">
<div class="tab_tit">目录结构管理</div>
<div class="xtree_width">
<div class="xtree_tab">
 <table  border="0" cellspacing="0" cellpadding="0">
 <tr><td align="right" width="50">ID：<span id="s_id">0</span></td><td width="80" align="right">项目名称：</td><td width="140" align="left">&nbsp;<input name="text" type="text" class="input1" id="t_name" onFocus="this.className='input1-bor'" onBlur="this.className='input1';query('t_name');"  /></td><td width="250" align="left">
      <input name="button" type="button" class="btn" id="Button2" onClick="change_name()" value="修改" />
      <input name="button" type="button" class="btn" id="Button4" onClick="del_item()" value="删除" />  </td>
      <td width="80" align="right">添加子项：</td><td align="left" width="140">&nbsp;<input name="text2" type="text" class="input1" id="new_name" onFocus="this.className='input1-bor'" onBlur="this.className='input1';query('new_name');"  /></td><td align="left">
      <input name="button2" type="button" class="btn" id="Button1" onClick="new_item()" value="添加" /></td>
      </tr>
 
<tr style="display:none"><td></td><td align="right">英文名称：</td><td colspan="5" align="left">&nbsp;<input name="text2" type="text" class="input1" id="new_name_en" onFocus="this.className='input1-bor'" onBlur="this.className='input1';"   />
</td></tr>
</table>
</div>
 
 
<div class=list-div id=listDiv>

<table align="center" cellpadding="5" cellspacing="0" class="s_tabel">
  <tr>
    <td width="52%" valign="top" style="border-right:#aaa solid 1px;">
<div id="treeboxbox_tree" style="background:none">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="4%" align="center"><img src="/Web/System/Public/Tool/codebase/imgs/dhxtree_material//folderOpen.gif" width="18" height="18"></td>
        <td width="28%" align="left"><a href ="javascript:void(0);" onClick="document.getElementById('s_id').innerHTML='0'">类别根目录</a></td>
        <td width="68%" align=center><a href="javascript:void(0);" onClick="tree.openAllItems(0);autoheight();">展开</a> | <a  href="javascript:void(0);" onClick="tree.closeAllItems(0);autoheight();">关闭</a> | <a  href="javascript:void(0);" onClick="MoveUp()">上移</a> | <a  href="javascript:void(0);" onClick="MoveDown()">下移</a> | <a  href="#here" onclick="Loct()">属性</a></td>
      </tr>
    </table>
</div>

   </td>
    <td width="48%" align="left" valign="top">
    <div id="msg" style=" height:300px; width:95%; margin-left:auto; margin-right:0; padding-right:10px; overflow:auto; line-height:23px; " ></div></td>
  </tr>
</table>
</div>
</div>
</div>
<div id="footer" class="info_foot">
Action Info: <div id='act_info' style="display:inline"></div>
</div>
</form>
</div>

<script type="text/javascript">
	var cls= '<?php echo ($cls); ?>',draged=0,
	tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",0);
	tree.setImagePath("/Web/System/Public/Tool/codebase/imgs/dhxtree_material/");
	tree.enableDragAndDrop(true);
	tree.setOnOpenHandler(tonopen); 
	tree.setOnClickHandler(tonclick);
	tree.setDragHandler(tondrag);
	tree.loadXML("<?php echo U('/System/ManagerPage/xtreeMenu',array('class'=>I('get.class')));?>");
    
	//选中节点
	function tonclick(id) {
		$("#t_name").val(tree.getItemText(id));
		$("#s_id").text(tree.getSelectedItemId())
		//请求英文
		var postdata={"sid": tree.getSelectedItemId()};
		$.ajax({
			url: "<?php echo U('/System/ManagerPage/get_encont');?>",
			type: 'POST',
			dataType: 'json',
			data:postdata
		}).done(function(d) {
			if(d["status"]["err"]==0){
				$("#new_name_en").val(d["txt"])
			}else if(d["status"]["err"]==1){
				window.parent.location.href="<?php echo U('/System/Index');?>"	
			}else{
				$("#new_name_en").val('');
			}
		})
	}
	
	//拖动
	function tondrag(id,id2){
		if (tree.getParentId(id) != id2) {
			$("#act_info").html("正在请求中…");
			var postdata={"id":id,"id2":id2,"class":cls};
			$.ajax({
				url: "<?php echo U('/System/ManagerPage/tondrag');?>",
				type: 'POST',
				dataType: 'json',
				async:false,
				data:postdata
			}).done(function(d) {
				if(d["status"]["err"]==0){
					$("#act_info").html("请求成功！");
					$("#msg").append("[<font color=#660099>拖动</font>]: ID为 <font color=darkgreen>"+id+"</font> 的项拖入ID为 <font color=darkgreen>"+id2+"</font> 的项成功.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
				}else if(d["status"]["err"]==1){
					window.parent.location.href="<?php echo U('/System/Index');?>"
				}else{
					$("#act_info").html(d["status"]["msg"]);
					alert(d["status"]["msg"]);
				
				}
			}).fail(function() {
            	alert("没有返回任何数据,可能您的操作没有得到处理")
				window.location.reload()
			})
			draged = 1;
		}
		return true;	
		
		
	}
	
	//展开自动设置高
	function tonopen(id,mode){
		setTimeout("autoheight()",20)
		return true
	}
	//点击属性
    function Loct() {
        if ($('#s_id').text() == 0) {
            alert('请选择目录');
            return false;
        }
       location.href = "/System.php?s=/System/ManagerPage/EditRead&id="+$('#s_id').text();
    }
	//计算提示框高度
    function autoheight(){
		$("#msg").height(($("#treeboxbox_tree").outerHeight(true)-10)<300?300:($("#treeboxbox_tree").outerHeight(true)-10))
	}
	//添加项
	function new_item(){
		$('#act_info').html("");
		if ($("#new_name").val() == "") {
			$('#act_info').html("<font color=red>项目的名字不能为空</font>");
		}else if ($("#new_name_en").val() == "") {
			$('#act_info').html("<font color=red>项目英文名不能为空</font>");
		}else{
			var postdata={"class":cls,"content":$("#new_name").val(),"parentid":$("#s_id").text(),"content_en":$("#new_name_en").val()};
			$.ajax({
				url: "<?php echo U('/System/ManagerPage/AddItem');?>",
				type: 'POST',
				dataType: 'json',
				data:postdata
			}).done(function(d) {
				if(d["status"]["err"]==0){
					$("#msg").append("[<font color=#660099>新建</font>]: ID为 <font color=darkgreen>"+d["cid"]+"</font> 的项已经建立.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
					tree.insertNewItem($('#s_id').text(),d["cid"], $('#new_name').val(), 0, 0, 0, 0, '');
				}else if(d["status"]["err"]==1){
					window.parent.location.href="<?php echo U('/System/Index');?>"
				}else{
					alert(d["status"]["msg"])
				}
			}).fail(function() {
            	alert("没有返回任何数据,可能您的操作没有得到处理")
			})
		}
	}

	//修改名称
	function change_name(){
		 $('#act_info').html("");
		if ($("#s_id").text() != "0") {
	        if ($("#t_name").value == "") {
	            $('#act_info').html("<font color='red'>项目的名字不能为空</font>");
	        }else if ($("#new_name_en").val() == "") {
	            $('#act_info').html("<font color=red>项目英文名称不能为空 </font>");
	        }else {
				var postdata={"cid":tree.getSelectedItemId(),"class":cls,"content":$("#t_name").val(),"content_en":$("#new_name_en").val()}
				$.ajax({
					url: "<?php echo U('/System/ManagerPage/ChangeName');?>",
					type: 'POST',
					dataType: 'json',
					data:postdata
				}).done(function(d) {
					if(d["status"]["err"]==0){
						tree.setItemText(tree.getSelectedItemId(),$("#t_name").val());
						$("#msg").append("[<font color=#0000ff>修改</font>]: ID为 <font color=darkgreen>"+d["cid"]+"</font> 的项修改成功.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
						
					}else if(d["status"]["err"]==1){
						window.parent.location.href="<?php echo U('/System/Index');?>"
					}else{
						alert(d["status"]["msg"])
					}
				}).fail(function() {
            		alert("没有返回任何数据,可能您的操作没有得到处理")
				})
	        }
	    }else{
			$('#act_info').html("<font color='red'>根目录名字不能更改</font>");
	    }
	}
	//删除选中项
	function del_item(){
		if ($("#s_id").text()=="0"){
			$('#act_info').innerHTML="<font color=red>根目录不能删除</font>"
		}else{
			if(confirm("警告，此操作会彻底删除这个目录以及它的所有子目录和文件。且无法还原")){ 
				var postdata={"cid":tree.getSelectedItemId(),"class":cls}
				$.ajax({
					url: "<?php echo U('/System/ManagerPage/DelItem');?>",
					type: 'POST',
					dataType: 'json',
					data:postdata
				}).done(function(d) {
					if(d["status"]["err"]==0){
						tree.deleteItem(tree.getSelectedItemId(),true);
						$('#s_id').text(0);
						$("#t_name").val('');
						$("#msg").append("[<font color=#ff0000>删除</font>]: ID为 <font color=darkgreen>"+d["cid"]+"</font> 的项及子项全部删除.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
					}else if(d["status"]["err"]==1){
						window.parent.location.href="<?php echo U('/System/Index');?>"
					}else{
						alert(d["status"]["msg"])
					}
				}).fail(function() {
            		alert("没有返回任何数据,可能您的操作没有得到处理")
				})
				
    		}else return false
		}
	}
	//上移
	function MoveUp(){
		if (draged==1){
			if (confirm("拖拽操作禁用了即时排序,现在重载并开启此功能吗？")){
				window.location.reload()
			}else{
				return
			}
		}
		var myid=$("#s_id").text();
		if (myid !="0"){
			var postdata={"cid":myid,"class":cls}
			$.ajax({
				url: "<?php echo U('/System/ManagerPage/MoveUp');?>",
				type: 'POST',
				dataType: 'json',
				data:postdata
			}).done(function(d) {
				if(d["status"]["err"]==0){
					tree.moveItem(myid,'up_strict');
					tree.selectItem(myid)
					$("#msg").append("[<font color=blue>上移</font>]: ID为 <font color=darkgreen>"+myid+"</font> 的项上移了一层.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
				}else if(d["status"]["err"]==1){
					window.parent.location.href="<?php echo U('/System/Index');?>"
				}else if(d["status"]["err"]==3){
					$("#msg").append("[<font color=blue>上移</font>]:移动失败,已经到达该层级顶端.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
					alert(d["status"]["msg"]);
				}else if(d["status"]["err"]==4){
					alert(d["status"]["msg"]);
					window.location.reload();
				}else{
					alert(d["status"]["msg"])
				}
			}).fail(function() {
            	alert("没有返回任何数据,可能您的操作没有得到处理")
			})
		}
	}
	
	//下移
	function MoveDown(){
		if (draged==1){
			if (confirm("拖拽操作禁用了即时排序,现在重载并开启此功能吗？")){
				window.location.reload()
			}else{
				return
			}
		}
		var myid=$("#s_id").text();
		if (myid !="0"){
			var postdata={"cid":myid,"class":cls}
			$.ajax({
				url: "<?php echo U('/System/ManagerPage/MoveDown');?>",
				type: 'POST',
				dataType: 'json',
				data:postdata
			}).done(function(d) {
				if(d["status"]["err"]==0){
					tree.moveItem(myid,'down_strict');
					tree.selectItem(myid)
					$("#msg").append("[<font color=blue>下移</font>]: ID为 <font color=darkgreen>"+myid+"</font> 的项下移了一层.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
				}else if(d["status"]["err"]==1){
					window.parent.location.href="<?php echo U('/System/Index');?>"
				}else if(d["status"]["err"]==3){
					$("#msg").append("[<font color=blue>下移</font>]:移动失败,已经到达该层级底端.[<font color=#C44F00>"+d["time"]+"</font>]<br>")
					alert(d["status"]["msg"]);
				}else if(d["status"]["err"]==4){
					alert(d["status"]["msg"]);
					window.location.reload();
				}else{
					alert(d["status"]["msg"])
				}
			}).fail(function() {
            	alert("没有返回任何数据,可能您的操作没有得到处理")
			})
		}
	}
</script>
</body>
</html>