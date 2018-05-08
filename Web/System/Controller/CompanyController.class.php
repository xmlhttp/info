<?php
namespace System\Controller;
use Think\Controller;
class CompanyController extends Controller {
    public function index(){
		loadcheck(10);
		$this->assign('menu',"<option value='0'>请选择子项目</option>".showmenu(0,1));
    	$this->display('Index:companyall');
    }
	
	//查询
	public function paged(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);

		$count=M('Company')->where("isdelete=0 and ver=".session("ver"))->count();
		$T=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit($page*$size,$size)->select();
		$json['pagecount']=ceil($count/$size);
		$json['pagecurrent']=$page;
		$json['data']['rows']=showitem($T);
		$json['status']['err']=0;
		$json['status']['msg']="请求成功！";
		ob_clean();
		$this->ajaxReturn($json, 'json');
	}
	
	//搜索
	public function search(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);
		if(I("post.searchid",0)!=0){
			$str =" and treeid like '%-".I("post.searchid",0)."-%'";
		}
		$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
		$T=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit($page*$size,$size)->select();	
		$json['pagecount']=ceil($count/$size);
		$json['pagecurrent']=$page;
		$json['data']['rows']=showitem($T);;
		$json['status']['err']=0;
		$json['status']['msg']="请求成功！";
		ob_clean();
		$this->ajaxReturn($json, 'json');
	}

	//编辑
	public function edit(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$v=I("post.nValue","");
		switch (I("post.cInd",0)){
			case 0:
				break;
			case 1:
				$field="newtitle";
				break;
			case 2:
				break;
			case 3:
				$field="putout";
				$v=$v=="true"?1:0;
				break;
		}
		$T=M('Company');
		if($T){
			$data[$field] = $v;
			$T->where('id='.I("post.rId",0).' and isdelete=0')->save($data);  	
			login_info("【企业信息】 信息ID为[".I("post.rId",0). "] 更新[".$field."]成功", "Company");
			$json['status']['err']=0;
			$json['status']['msg']="<span class='msgright'>ID为<font style='padding-left:2px; padding-right:2px; font-size:13px'>".I("post.rId",0)."</font>的第<font  style='padding-left:2px; padding-right:2px; font-size:13px'>".(I("post.cInd",0)+1)."</font>列的数据已经更新为:".I("post.nValue","")."</span>";	
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="数据连接错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;		
		}
		
	}
	
	//删除
	public function del(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$data["isdelete"]=1;
		if(M('Company')->where('id in('.I("post.ids","-1").')')->save($data)){ //删除成功后刷新数据
			$page=I("post.page",0);
			$size=I("post.size",5);
			$count=M('Company')->where("isdelete=0 and ver=".session("ver"))->count();
			$T=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit($page*$size,$size)->select();	
			if($T){ //数据表有数据时
				$json['pagecount']=ceil($count/$size);
				$json['pagecurrent']=$page;
				$json['data']['rows']=showitem($T);;
				$json['status']['err']=0;
				$json['status']['msg']="请求成功";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}else{ //查询结果为空自动返回上一页
				if($page==0){
					$json['pagecount']=0;
					$json['pagecurrent']=0;
					$json['data']['rows']=array();
					$json['status']['err']=0;
					$json['status']['msg']="请求成功，数据已被清空";
					ob_clean();
					$this->ajaxReturn($json, 'json');
					exit;	
				}else{
					$page=I("post.page",0)-1;	
					$count=M('Company')->where("isdelete=0 and ver=".session("ver"))->count();
					$T=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit($page*$size,$size)->select();
					$json['pagecount']=ceil($count/$size);
					$json['pagecurrent']=$page;
					$json['data']['rows']=showitem($T);;
					$json['status']['err']=0;
					$json['status']['msg']="请求成功，当前页面没有数据系统自动向上翻页";
					ob_clean();
					$this->ajaxReturn($json, 'json');
					exit;
				}
			}	
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="命令执行错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
	}
	
	
	//带查询的删除
	public function delsearch(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}		
		$data["isdelete"]=1;
		if(M('Company')->where('id in('.I("post.ids","-1").')')->save($data)){ //删除成功后刷新数据
			$page=I("post.page",0);
			$size=I("post.size",5);
			if(I("post.searchid",0)!=0){
				$str =" and treeid like '%-".I("post.searchid",0)."-%'";
			}			
			$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
			$T=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit($page*$size,$size)->select();	
			
			
			if($T){ //数据表有数据时
				$json['pagecount']=ceil($count/$size);
				$json['pagecurrent']=$page;
				$json['data']['rows']=showitem($T);;
				$json['status']['err']=0;
				$json['status']['msg']="请求成功";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}else{ //查询结果为空自动返回上一页
				if($page==0){
					$json['pagecount']=0;
					$json['pagecurrent']=0;
					$json['data']['rows']=array();
					$json['status']['err']=0;
					$json['status']['msg']="请求成功，数据已被清空";
					ob_clean();
					$this->ajaxReturn($json, 'json');
					exit;	
				}else{
					$page=I("post.page",0)-1;
					$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
					$T=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit($page*$size,$size)->select();	
					
					$json['pagecount']=ceil($count/$size);
					$json['pagecurrent']=$page;
					$json['data']['rows']=showitem($T);
					$json['status']['err']=0;
					$json['status']['msg']="请求成功，当前页面没有数据系统自动向上翻页";
					ob_clean();
					$this->ajaxReturn($json, 'json');
					exit;
				}
			}	
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="命令执行错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
	}
	
	//上移
	public function up(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}

		$T=M('Company')->where("id=".I("post.cid",0)." and ver=".session("ver"))->find();
		$T1=M('Company')->where("id=".I("post.pid",0)." and ver=".session("ver"))->find();	
		if(!$T||!$T1){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$S=M('Company');
		$data["orderid"]=$T1["orderid"];
		$data1["orderid"]=$T["orderid"];
		$S->startTrans();
		if(M('Company')->where('id ='.$T["id"])->save($data) && M('Company')->where('id ='.$T1["id"])->save($data1)){
			$S->commit();
			$json['status']['err']=0;
			$json['status']['msg']="上移成功";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}else{
			$S->rollback();
			$json['status']['err']=2;
			$json['status']['msg']="执行错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	
	}

	//普通上移上翻页
	public function pageup(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		$T=M('Company')->where("id=".I("post.cid",0)." and ver=".session("ver"))->find();
		if(!$T){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);
		$count=M('Company')->where("isdelete=0 and ver=".session("ver"))->count();
		$T1=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit($page*$size-1,1)->select();
		if($count==0){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		$S=M('Company');
		$data["orderid"]=$T1[0]["orderid"];
		$data1["orderid"]=$T["orderid"];
		$S->startTrans();
		if(M('Company')->where('id ='.$T["id"])->save($data) && M('Company')->where('id ='.$T1[0]["id"])->save($data1)){
			$S->commit();
			$T2=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit(($page-1)*$size,$size)->select();
			$json['pagecount']=ceil($count/$size);
			$json['pagecurrent']=$page-1;
			$json['data']['rows']=showitem($T2);
			$json['status']['err']=0;
			$json['status']['msg']="请求成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
				
		}else{
			$S->rollback();
			$json['status']['err']=2;
			$json['status']['msg']="执行错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	}
	
	//带查询的上移上翻
	public function searchup(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$S=M('Company');
		$T=M('Company')->where("id=".I("post.cid",0)." and ver=".session("ver"))->find();
		if(!$T){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);
		if(I("post.searchid",0)!=0){
			$str =" and treeid like '%-".I("post.searchid",0)."-%'";
		}		
		$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
		$T1=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit($page*$size-1,1)->select();
		if($count==0){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$data["orderid"]=$T1[0]["orderid"];
		$data1["orderid"]=$T["orderid"];
		$S->startTrans();
		if(M('Company')->where('id ='.$T["id"])->save($data) && M('Company')->where('id ='.$T1[0]["id"])->save($data1)){
			$S->commit();
			$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
			$T2=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit(($page-1)*$size,$size)->select();	
			$json['pagecount']=ceil($count/$size);
			$json['pagecurrent']=$page-1;
			$json['data']['rows']=showitem($T2);;
			$json['status']['err']=0;
			$json['status']['msg']="请求成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}else{
			$S->rollback();
			$json['status']['err']=2;
			$json['status']['msg']="上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
	}

	//下移
	public function down(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$S=M('Company');
		$T=M('Company')->where("id=".I("post.cid",0)." and ver=".session("ver"))->find();
		$T1=M('Company')->where("id=".I("post.pid",0)." and ver=".session("ver"))->find();
		if(!$T||!$T1){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$data["orderid"]=$T1["orderid"];
		$data1["orderid"]=$T["orderid"];
		$S->startTrans();
		if(M('Company')->where('id ='.$T["id"])->save($data) && M('Company')->where('id ='.$T1["id"])->save($data1)){
			$S->commit();
			$json['status']['err']=0;
			$json['status']['msg']="下移成功";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}else{
			$S->rollback();
			$json['status']['err']=2;
			$json['status']['msg']="执行错误，下移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	}
	
	
	//普通下移下翻页
	public function pagedown(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$S=M('Company');
		$T=M('Company')->where("id=".I("post.cid",0)." and ver=".session("ver"))->find();
		if(!$T){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}		
		$page=I("post.page",0);
		$size=I("post.size",5);
		$count=M('Company')->where("isdelete=0 and ver=".session("ver"))->count();
		$T1=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit(($page+1)*$size,1)->select();
		if($count==0){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		$data["orderid"]=$T1[0]["orderid"];
		$data1["orderid"]=$T["orderid"];
		$S->startTrans();
		if(M('Company')->where('id ='.$T["id"])->save($data) && M('Company')->where('id ='.$T1[0]["id"])->save($data1)){
			$S->commit();
			$T2=M('Company')->where("isdelete=0 and ver=".session("ver"))-> order('orderid desc')->limit(($page+1)*$size,$size)->select();
			$json['pagecount']=ceil($count/$size);
			$json['pagecurrent']=$page+1;
			$json['data']['rows']=showitem($T2);
			$json['status']['err']=0;
			$json['status']['msg']="请求成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
				
		}else{
			$S->rollback();
			$json['status']['err']=2;
			$json['status']['msg']="执行错误，下移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	}
	
	//带查询的下移下翻
	public function searchdown(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$S=M('Company');
		$T=M('Company')->where("id=".I("post.cid",0)." and ver=".session("ver"))->find();
		if(!$T){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);
		if(I("post.searchid",0)!=0){
			$str =" and treeid like '%-".I("post.searchid",0)."-%'";
		}		
		$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
		if($count==0){
			$json['status']['err']=2;
			$json['status']['msg']="数据错误，上移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$T1=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit(($page+1)*$size,1)->select();	
		$data["orderid"]=$T1[0]["orderid"];
		$data1["orderid"]=$T["orderid"];
		$S->startTrans();
		if(M('Company')->where('id ='.$T["id"])->save($data) && M('Company')->where('id ='.$T1[0]["id"])->save($data1)){
			$S->commit();
			$count=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))->count();
			$T2=M('Company')->where("isdelete=0".$str." and newtitle like '%".I("post.searchtxt",'')."%' and ver=".session("ver"))-> order('orderid desc')->limit(($page+1)*$size,$size)->select();	
			$json['pagecount']=ceil($count/$size);
			$json['pagecurrent']=$page+1;
			$json['data']['rows']=showitem($T2);;
			$json['status']['err']=0;
			$json['status']['msg']="请求成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');	
		}else{
			$S->rollback();
			$json['status']['err']=2;
			$json['status']['msg']="下移失败.";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
	}

	//站点-显示
	 public function AddRead(){
		loadcheck(11);
		$option=showmenu(0,1);
		$this->assign('option',$option);
    	$this->display('Index:companyAdd');
    }

	//站点-添加
	public function AddSave(){
		$json = array();
		if(!ajaxcheck(11)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		if(I('post.newtitle', '') == ""){
			$json['status']['err']=2;
			$json['status']['msg']="标题不能为空！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		
		$data["newtitle"]=I('post.newtitle', '');
		$data["newdesc"]=I('post.newdesc', '');
		$data["newcontent"]=$_POST["Newcontent"];
		$data["addtime"]=I('post.addtime', '');
		$data["putman"]=I('post.putman', '');
		$data["putout"]=I('post.putout', '');
		$data["ver"]=session("ver");
		$data["treeid"]=showtree(I('post.list1', '')).'-';
		$data["page_tit"]=I('post.page_tit', '');
		$data["page_key"]=I('post.page_key', '');
		$data["page_desc"]=I('post.page_desc', '');
		if($lastInsId =M('Company')->add($data)){
			$data['orderid']=$lastInsId;
			if(M('Company')->where('id='.$lastInsId)->save($data)){
				login_info("【企业信息】 信息ID为[".$lastInsId."]的项添加成功", "Company");
				$json['status']['err']=0;
				$json['status']['msg']="添加成功！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}else{
				$json['status']['err']=2;
				$json['status']['msg']="写入数据库失败！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="写入数据库失败！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
	}

	//站点-读取
	public function EditRead(){
		loadcheck(10);
		$info=M('Company')->where('id='.I("get.id",0).' and isdelete=0 and ver='.session("ver"))->find();
		if(!$info){
			header("Content-Type:text/html;charset=utf-8");
			echo "信息不存在!";
			exit;	
		}
		$arr = explode("-",$info["treeid"]);
		$pid=$arr[count($arr)-2];
		$pcontent=M('deeptree')->where('id='.$pid.' and ver='.session("ver"))->find();
		if(!$pcontent){
			header("Content-Type:text/html;charset=utf-8");
			echo "信息不存在!";
			exit;	
		}
		$option="<option value='0'>※".$pcontent["content"]."</option>".showmenu(0,1);
		$this->assign('option',$option);
		$this->assign('info',$info);	
		$this->display('Index:companyUpdata');
	}
	
	//站点-修改
	public function EditSave(){
		$json = array();
		if(!ajaxcheck(10)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		if(I('get.id',0)==0){
			$json['status']['err']=2;
			$json['status']['msg']="信息提交有误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		if(I('post.newtitle', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="广告标题不能为空！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$ret=M('Company')->where('id='.I('get.id',0).' and isdelete=0 and ver='.session("ver"))->find();
		if(!$ret){
			$json['status']['err']=2;
			$json['status']['msg']="信息提交有误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}

		if(I('post.list1', 0)!=0){
			$data["treeid"]=showtree(I('post.list1', '')).'-';	
		}
		$data["newtitle"]=I('post.newtitle', '');
		$data["newdesc"]=I('post.newdesc', '');
		$data["newcontent"]=$_POST["Newcontent"];
		$data["addtime"]=I('post.addtime', '');
		$data["putman"]=I('post.putman', '');
		$data["putout"]=I('post.putout', '');
		$data["ver"]=session("ver");
		$data["page_tit"]=I('post.page_tit', '');
		$data["page_key"]=I('post.page_key', '');
		$data["page_desc"]=I('post.page_desc', '');
		$result=M('Company')->where('id='.I('get.id',0).' and isdelete=0 and ver='.session("ver"))->save($data);
		if($result||$result===0){
			login_info("【企业信息】 信息ID为[".I('get.id',0)."]的项修改成功", "Company");
			$json['status']['err']=0;
			$json['status']['msg']="修改成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="修改数据失败！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	}
	
	
	
	
	
}

//输出列表
function showitem($T){
	$data=array();
	foreach($T as $t=>$v){
		$data[$t]["id"]=$v['id'];
		$data[$t]["data"][]=$v['id'];
		$data[$t]["data"][]=$v['newtitle'];
		$data[$t]["data"][]=$v['addtime'];
		$data[$t]["data"][]=$v['putout'];	
		$data[$t]["data"][]="查看^/System.php?s=/System/ComRe/index&id=".$v['id']."^_self";
		$data[$t]["data"][]="编辑^/System.php?s=/System/Company/EditRead&id=".$v['id']."^_self";
	
		$data[$t]["data"][]=0;
	}
	return $data;
}

//输出目录结构
function showmenu($rid,$temp){
	$ret=M('deeptree')->where('parentid='.$rid.' and class="Company" and ver='.session("ver"))->order('orderid asc')->select();
	if(count($ret)){
		foreach($ret as $k=>$v){
			$str="";
			for($i=0;$i<$temp;$i++){
				$str.="&nbsp;&nbsp;&nbsp;";	
			}
			$menu.="<option value=".$v["id"].">".$str."|-&nbsp;".$v["content"]."</option>".showmenu($v["id"],$temp+1);
		}
	}
	return $menu;
}

//获取目录树
function showtree($rid){
	$ret=M('deeptree')->where('id='.$rid.' and class="Company" and ver='.session("ver"))->find();
	if($ret["parentid"]==0){
		return "-".$ret["id"];	
	}else{
		return showtree($ret["parentid"])."-".$ret["id"];
	}
}