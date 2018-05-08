var	islink=false, //数据是否处于请求状态
	issearch=false, //是否处于查找状态
	searchid=0, //查找ID分类为0表示全部
	searchtxt='', //查找字符
	cpage=0, //当前页码
	countpage;//总页码
	$(function(){
		conHei = parseInt(($(window).outerHeight(true) -278)/31)<5?5:parseInt(($(window).outerHeight(true) -278)/31);
		$("#gridbox").height((conHei+1)*31+2); //根据页面高度设置列表
		pageselectCallback(cpage);
	});

//请求数据列表
function pageselectCallback(paged){
	if(islink){
		return;	
	}
	var postdata={"page":paged,"size":conHei},
		posturl=myGridmode+'paged';

	if(typeof(typeid)!="undefined"){
		postdata={"page":paged,"size":conHei,"id":typeid}
	}
	
	if(issearch){ //有查找时分页
		postdata={"page":paged,"size":conHei,"searchid":searchid,"searchtxt":searchtxt};
		posturl=myGridmode+'search';	
	}
	islink=true;
	$("#act_info").html("<span class='msgload'>正在载入……</span>")
	$.ajax({
		url: posturl,
		type: 'POST',
		dataType: 'json',
		data: postdata
	}).done(function(d) {
		islink=false;
		if(d["status"]["err"]==0){
			cpage=paged;
			countpage=d["pagecount"];
			$("#act_info").html("<span class='msgload'>解析数据……</span>")
			//分页
			$("#setpage").pagination(d["pagecount"],{
				num_edge_entries: 2,
    			num_display_entries: 9,
    			current_page: d["pagecurrent"],
				callback: pageselectCallback
			});
			//绑定数据
			myGrid.clearAll();
			myGrid.parse(d["data"],"json");
			setTimeout(function(){$("#act_info").html("<span class='msgright'>处理完成.</span>")},200);
		}else if(d["status"]["err"]==1){
			alert(d["status"]["msg"]);
			window.parent.location.href="/System.php"
		}else{
			$("#act_info").html("<span class='msgerr'>"+d["status"]["msg"]+"</span>")
		}
	}).fail(function() {
		islink=false;
		$("#act_info").html("<span class='msgerr'>网络错误！</span>")
	})
}

//查找
function searchitem(){
	if($("#menu").length>0){	
		if($("#menu").val()==0&&$("#keywords").val().replace(/(^\s*)|(\s*$)/g, "").length==0){
			issearch=false;
			searchid=0;
			searchtxt='';
		}else{
			issearch=true;
			searchid=$("#menu").val();
			searchtxt=$("#keywords").val();		
		}
	}else{
		if($("#keywords").val().replace(/(^\s*)|(\s*$)/g, "").length==0){
			issearch=false;
			searchid=0;
			searchtxt='';
		}else{
			issearch=true;
			searchid=0;
			searchtxt=$("#keywords").val();		
		}	
	}
	pageselectCallback(0);		
}


//点击修改框修改
function doOnCellEdit(stage,rId,cInd,nValue,oValue){
	if(islink){
		return
	}
	if(stage==2){
		$("#act_info").html("<span class='msgload'>正在请求中…</span>")
		if(nValue!=""&&nValue!=undefined){
			if(nValue==oValue){
				setTimeout(function(){$("#act_info").html("<span class='msgright'>本次结果未被修改.</span>")},200);
				return false		
			}else{
				var data={"cInd":cInd,"rId":rId,"nValue":nValue};
				edit(myGridmode,oValue,data);
			}
		}else{
			setTimeout(function(){$("#act_info").html("<span class='msgright'>该项的值不能为空,返回原值.</span>")},200);
			return false
		}
	}
	return true;
}

//点击选择框
function doOnCheckEdit(rId,cInd,state){
	if(islink){
		return;	
	}
	if (cInd!=myGridnum){
		$("#act_info").html("<span class='msgload'>正在请求中…</span>")
		var data={"cInd":cInd,"rId":rId,"nValue":state};
		edit(myGridmode,!state,data);
	}
}

//统一修改方法
function edit(url,oValue,a){
	islink=true;
	$.ajax({
		url: url+'edit',
		type: 'POST',
		dataType: 'json',
		data: a
	}).done(function(d) {
		$("#act_info").html("<span class='msgload'>正在处理中…</span>")
		islink=false;
		if(d["status"]["err"]==0){
			setTimeout(function(){$("#act_info").html(d["status"]["msg"])},200);
		}else if(d["status"]["err"]==1){
			alert(d["status"]["msg"]);
			window.parent.location.href="/System.php"
		}else{
			setTimeout(function(){
				$("#act_info").html("<span class='msgerr'>"+d["status"]["msg"]+"，返回原值</span>");
				myGrid.cellById(a["rId"], a["cInd"]).setValue(oValue)
			},200);
				
		}
	}).fail(function() {
		islink=false;
		myGrid.cellById(a["rId"], a["cInd"]).setValue(oValue)
		$("#act_info").html("<span class='msgerr'>网络错误，返回原值！</span>")
	})	
}

//统一全选
function chk_all(){
	var maxcell=myGrid.getColumnCount()
	for (var i=0;i<= myGrid.getRowsNum()-1;i++){
		if (myGrid.cells2(i,maxcell-1).getValue()==0){
			myGrid.cells2(i,maxcell-1).setValue("1")
		}else{
			myGrid.cells2(i,maxcell-1).setValue("0")
		}
	}
}

//点击删除
function delete_items(){
	if (confirm("确认删除这些选中的文件吗？")){
		var del_ids="-1"
		for (var i=0;i<= myGrid.getRowsNum()-1;i++){
			if (myGrid.cells2(i ,myGridnum).getValue()==1){
				del_ids=del_ids+","+myGrid.getRowId(i)
			}
		}
		if (del_ids!=-1){
			$("#act_info").html("<span class='msgload'>正在请求中…</span>")
			var posturl= myGridmode+'del',
				postdata={"ids":del_ids,"page":cpage,"size":conHei};
			if(issearch){
				posturl=myGridmode+'delsearch'
				postdata={"ids":del_ids,"page":cpage,"size":conHei,"searchid":searchid,"searchtxt":searchtxt};
			}
				
			if(typeof(typeid)!="undefined"){
				postdata={"ids":del_ids,"page":cpage,"size":conHei,"id":typeid};
			}
			
			del(posturl,postdata);
		}
	}else return false
}

//统一删除
function del(url,a){
	islink=true;
	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data: a
	}).done(function(d) {
		$("#act_info").html("<span class='msgload'>正在处理中…</span>")
		islink=false;
		if(d["status"]["err"]==0){
			cpage=d["pagecurrent"];
			countpage=d["pagecount"];
			$("#act_info").html("<span class='msgload'>解析数据……</span>")
			//分页
			$("#setpage").pagination(d["pagecount"],{
				num_edge_entries: 2,
    			num_display_entries: 9,
    			current_page: d["pagecurrent"],
				callback: pageselectCallback
			});
			//绑定数据
			myGrid.clearAll();
			myGrid.parse(d["data"],"json");		
			setTimeout(function(){$("#act_info").html("<span class='msgright'>"+d["status"]["msg"]+"，ID为："+a["ids"].replace("-1,","")+"的项已经删除</span>")},200);
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

//上移
function item_up(){
	$("#act_info").html("<span class='msgload'>正在处理中…</span>")
	if(myGrid.getSelectedId()==null){
		setTimeout(function(){$("#act_info").html("<span class='msgerr'>您没有选中任何要移动的项，请“单击”选择.</span>")},200);
	}else{
		if(myGrid.getRowId(myGrid.getRowIndex(myGrid.getSelectedId())-1)){ //正常上移
			islink=true;
			var postdata={"cid":myGrid.getSelectedId(),"pid":myGrid.getRowId(myGrid.getRowIndex(myGrid.getSelectedId())-1)}
			$.ajax({
				url: myGridmode+'up',
				type: 'POST',
				dataType: 'json',
				data: postdata
			}).done(function(d) {
				islink=false;
				if(d["status"]["err"]==0){
					myGrid.moveRowUp(myGrid.getSelectedId())
					setTimeout(function(){$("#act_info").html("<span class='msgright'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+postdata["cid"]+"</font>的项上移了一行.</span>")},200);
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
		}else{
			if(cpage==0){
				setTimeout(function(){$("#act_info").html("<span class='msgerr'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+myGrid.getSelectedId()+"</font>的项已经移动到了最前面，上移失败！</span>")},200);
				return;	
			}
			if(issearch){ //查询上移翻页
				var postdata={"cid":myGrid.getSelectedId(),"page":cpage,"size":conHei,"searchid":searchid,"searchtxt":searchtxt};
				islink=true;
				$.ajax({
					url: myGridmode+'searchup',
					type: 'POST',
					dataType: 'json',
					data: postdata
				}).done(function(d) {
					islink=false;
					if(d["status"]["err"]==0){
						cpage=d["pagecurrent"];
						countpage=d["pagecount"]
						$("#act_info").html("<span class='msgload'>解析数据……</span>")
						//分页
						$("#setpage").pagination(d["pagecount"],{
							num_edge_entries: 2,
    						num_display_entries: 9,
    						current_page: d["pagecurrent"],
							callback: pageselectCallback
						});
						//绑定数据
						myGrid.clearAll();
						myGrid.parse(d["data"],"json");
						myGrid.selectRowById(postdata["cid"]);
						setTimeout(function(){$("#act_info").html("<span class='msgright'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+postdata["cid"]+"</font>的项上移了一行，上移达到顶部系统自动上翻一页.</span>")},200);
						
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
	
			}else{ //普通上移翻页
				var postdata={"cid":myGrid.getSelectedId(),"page":cpage,"size":conHei};
				islink=true;
				$.ajax({
					url: myGridmode+'pageup',
					type: 'POST',
					dataType: 'json',
					data: postdata
				}).done(function(d) {
					$("#act_info").html("<span class='msgload'>正在处理中…</span>")
					islink=false;
					if(d["status"]["err"]==0){
						cpage=d["pagecurrent"];
						countpage=d["pagecount"];
						$("#act_info").html("<span class='msgload'>解析数据……</span>")
						//分页
						$("#setpage").pagination(d["pagecount"],{
							num_edge_entries: 2,
    						num_display_entries: 9,
    						current_page: d["pagecurrent"],
							callback: pageselectCallback
						});
						//绑定数据
						myGrid.clearAll();
						myGrid.parse(d["data"],"json");
						myGrid.selectRowById(postdata["cid"]);
						setTimeout(function(){$("#act_info").html("<span class='msgright'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+postdata["cid"]+"</font>的项上移了一行，上移达到顶部系统自动上翻一页.</span>")},200);
						
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
	}
}


//下移
function item_down(){
	$("#act_info").html("<span class='msgload'>正在处理中…</span>")
	if(myGrid.getSelectedId()==null){
		setTimeout(function(){$("#act_info").html("<span class='msgerr'>您没有选中任何要移动的项，请“单击”选择.</span>")},200);
	}else{
		if(myGrid.getRowId(myGrid.getRowIndex(myGrid.getSelectedId())+1)){ //正常下移
			islink=true;
			var postdata={"cid":myGrid.getSelectedId(),"pid":myGrid.getRowId(myGrid.getRowIndex(myGrid.getSelectedId())+1)}
			$.ajax({
				url: myGridmode+'down',
				type: 'POST',
				dataType: 'json',
				data: postdata
			}).done(function(d) {
				islink=false;
				if(d["status"]["err"]==0){
					myGrid.moveRowDown(myGrid.getSelectedId())
					setTimeout(function(){$("#act_info").html("<span class='msgright'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+postdata["cid"]+"</font>的项下移了一行.</span>")},200);
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
		}else{
			if(cpage==countpage-1){
				setTimeout(function(){$("#act_info").html("<span class='msgerr'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+myGrid.getSelectedId()+"</font>的项已经移动到了最后面，下移失败！</span>")},200);
				return;	
			}
			if(issearch){ //查询下移翻页
				var postdata={"cid":myGrid.getSelectedId(),"page":cpage,"size":conHei,"searchid":searchid,"searchtxt":searchtxt};
				islink=true;
				$.ajax({
					url: myGridmode+'searchdown',
					type: 'POST',
					dataType: 'json',
					data: postdata
				}).done(function(d) {
					islink=false;
					if(d["status"]["err"]==0){
						cpage=d["pagecurrent"];
						countpage=d["pagecount"];
						$("#act_info").html("<span class='msgload'>解析数据……</span>")
						//分页
						$("#setpage").pagination(d["pagecount"],{
							num_edge_entries: 2,
    						num_display_entries: 9,
    						current_page: d["pagecurrent"],
							callback: pageselectCallback
						});
						//绑定数据
						myGrid.clearAll();
						myGrid.parse(d["data"],"json");
						myGrid.selectRowById(postdata["cid"]);
						setTimeout(function(){$("#act_info").html("<span class='msgright'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+postdata["cid"]+"</font>的项下移了一行，下移达到底部系统自动下翻一页.</span>")},200);
						
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
	
			}else{ //普通上移翻页
				var postdata={"cid":myGrid.getSelectedId(),"page":cpage,"size":conHei};
				islink=true;
				$.ajax({
					url: myGridmode+'pagedown',
					type: 'POST',
					dataType: 'json',
					data: postdata
				}).done(function(d) {
					$("#act_info").html("<span class='msgload'>正在处理中…</span>")
					islink=false;
					if(d["status"]["err"]==0){
						cpage=d["pagecurrent"];
						countpage=d["pagecount"];
						$("#act_info").html("<span class='msgload'>解析数据……</span>")
						//分页
						$("#setpage").pagination(d["pagecount"],{
							num_edge_entries: 2,
    						num_display_entries: 9,
    						current_page: d["pagecurrent"],
							callback: pageselectCallback
						});
						//绑定数据
						myGrid.clearAll();
						myGrid.parse(d["data"],"json");
						myGrid.selectRowById(postdata["cid"]);
						setTimeout(function(){$("#act_info").html("<span class='msgright'>ID为<font style='margin-left:1px; font-size:13px; margin-right:2px'>"+postdata["cid"]+"</font>的项下移了一行，下移达到顶部系统自动下翻一页.</span>")},200);
						
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
	}
}
