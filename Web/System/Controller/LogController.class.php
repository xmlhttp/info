<?php
namespace System\Controller;
use Think\Controller;
class LogController extends Controller {

    public function index(){
		loadcheck(50);
   		$this->display('ManagerPage:Log');
    }
	
	//查询
	public function paged(){
		$json = array();
		if(!ajaxcheck(50)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);
		if(session("adminclass")==0){
			$count=M('sys_note')->where("login_name='".session("admin")."'")->count();
			$T=M('sys_note')->where("login_name='".session("admin")."'")->order('id desc')->limit($page*$size,$size)->select();
		}else{
			$count=M('sys_note')->count();
			$T=M('sys_note')->order('id desc')->limit($page*$size,$size)->select();
		}
		
		
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
		if(!ajaxcheck(50)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$page=I("post.page",0);
		$size=I("post.size",5);
		if(session("adminclass")==0){
			$count=M('sys_note')->where("login_name='".session("admin")."' and act like '%".I("post.searchtxt",'')."%'")->count();
			$T=M('sys_note')->where("login_name='".session("admin")."' and act like '%".I("post.searchtxt",'')."%'")-> order('id desc')->limit($page*$size,$size)->select();
		}else{
			$count=M('sys_note')->where("act like '%".I("post.searchtxt",'')."%'")->count();
			$T=M('sys_note')->where("act like '%".I("post.searchtxt",'')."%'")-> order('id desc')->limit($page*$size,$size)->select();
		}
		$json['pagecount']=ceil($count/$size);
		$json['pagecurrent']=$page;
		$json['data']['rows']=showitem($T);
		$json['status']['err']=0;
		$json['status']['msg']="请求成功！";
		ob_clean();
		$this->ajaxReturn($json, 'json');
	}
	

	
	//删除
	public function del(){
		$json = array();
		if(!ajaxcheck(50)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		if(session("adminclass")==0){ //权限判断
			$Te=M('sys_note')->where('id in('.I("post.ids","-1").') and login_name<>"'.session('admin').'"')->select();
			if($Te){
				$json['status']['err']=1;
				$json['status']['msg']="您已经退出或权限不够！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}
		}
		
		if(M('sys_note')->where('id in('.I("post.ids","-1").')')->delete()){ //删除成功后刷新数据
			$page=I("post.page",0);
			$size=I("post.size",5);
			if(session("adminclass")==0){
				$count=M('sys_note')->where("login_name='".session("admin")."'")->count();
				$T=M('sys_note')->where("login_name='".session("admin")."'")->order('id desc')->limit($page*$size,$size)->select();
			}else{
				$count=M('sys_note')->count();
				$T=M('sys_note')->order('id desc')->limit($page*$size,$size)->select();
			}
			if($T){ //数据表有数据时
				$json['pagecount']=ceil($count/$size);
				$json['pagecurrent']=$page;
				$json['data']['rows']=showitem($T);
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
					if(session("adminclass")==0){
						$count=M('sys_note')->where("login_name='".session("admin")."'")->count();
						$T=M('sys_note')->where("login_name='".session("admin")."'")->order('id desc')->limit($page*$size,$size)->select();
					}else{
						$count=M('sys_note')->count();
						$T=M('sys_note')->order('id desc')->limit($page*$size,$size)->select();
					}
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
	
	
	//带查询的删除
	public function delsearch(){
		$json = array();
		if(!ajaxcheck(50)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		if(session("adminclass")==0){ //权限判断
			$Te=M('sys_note')->where('id in('.I("post.ids","-1").') and login_name<>"'.session('admin').'"')->select();
			if($Te){
				$json['status']['err']=1;
				$json['status']['msg']="您已经退出或权限不够！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}
		}
		
		if(M('sys_note')->where('id in('.I("post.ids","-1").')')->delete()){ //删除成功后刷新数据
			$page=I("post.page",0);
			$size=I("post.size",5);
			
			if(session("adminclass")==0){
				$count=M('sys_note')->where("login_name='".session("admin")."' and act like '%".I("post.searchtxt",'')."%'")->count();
				$T=M('sys_note')->where("login_name='".session("admin")."' and act like '%".I("post.searchtxt",'')."%'")-> order('id desc')->limit($page*$size,$size)->select();
			}else{
				$count=M('sys_note')->where("act like '%".I("post.searchtxt",'')."%'")->count();
				$T=M('sys_note')->where("act like '%".I("post.searchtxt",'')."%'")-> order('id desc')->limit($page*$size,$size)->select();
			}
			
			if($T){ //数据表有数据时
				$json['pagecount']=ceil($count/$size);
				$json['pagecurrent']=$page;
				$json['data']['rows']=showitem($T);
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
					if(session("adminclass")==0){
						$count=M('sys_note')->where("login_name='".session("admin")."' and act like '%".I("post.searchtxt",'')."%'")->count();
						$T=M('sys_note')->where("login_name='".session("admin")."' and act like '%".I("post.searchtxt",'')."%'")-> order('id desc')->limit($page*$size,$size)->select();
					}else{
						$count=M('sys_note')->where("act like '%".I("post.searchtxt",'')."%'")->count();
						$T=M('sys_note')->where("act like '%".I("post.searchtxt",'')."%'")-> order('id desc')->limit($page*$size,$size)->select();
					}
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
	
	//全部删除
	public function delall(){
		$json = array();
		if(!ajaxcheck(50)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		if(session("adminclass")==0){
			$sql='delete from db_sys_note where login_name="'.session('admin').'"';
		}else{
			$sql='truncate table db_sys_note';	
		}
		if(M()->execute($sql)){
			$json['status']['err']=0;
			$json['status']['msg']="执行成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}else{
			$json['status']['err']=0;
			$json['status']['msg']="数据执行错误！";
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
		$data[$t]["data"][]=$v['login_name'];
		$data[$t]["data"][]=$v['login_ip'];
		$data[$t]["data"][]=$v['login_ie'];
		$data[$t]["data"][]=$v['login_time'];
		$data[$t]["data"][]=$v['act'];
		$data[$t]["data"][]=0;
	}
	return $data;
}
