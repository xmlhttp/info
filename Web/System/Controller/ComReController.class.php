<?php
namespace System\Controller;
use Think\Controller;
class ComReController extends Controller {
    public function index(){
		loadcheck(10);
		
		if(I('get.id',0)==0){
			ob_clean();
			header("Content-Type:text/html;charset=utf-8");
			echo "信息有误!";
			exit;	
		}
		
		$T=M('company')->where("id=".I('get.id',0))->find();
		if(!$T){
			ob_clean();
			header("Content-Type:text/html;charset=utf-8");
			echo "信息有误!";
			exit;	
		}
		$this->assign('T',$T);
    	$this->display('Index:comRe');
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

		$count=M('comre')->where("isdelete=0 and ver=".session("ver")." and parentid=".I("post.id",0))->count();
		$T=M('comre')->where("isdelete=0 and ver=".session("ver")." and parentid=".I("post.id",0))-> order('addtime desc')->limit($page*$size,$size)->select();		
		$json['pagecount']=ceil($count/$size);
		$json['pagecurrent']=$page;
		$json['data']['rows']=showitem($T);
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
				$field="newcontent";
				break;
			case 2:
				break;
			case 3:
				$field="putout";
				$v=$v=="true"?1:0;
				break;
		}
		$T=M('comre');
		if($T){
			$data[$field] = $v;
			$T->where('id='.I("post.rId",0).' and isdelete=0')->save($data);  	
			login_info("【回复主题】 信息ID为[".I("post.rId",0). "] 更新[".$field."]成功", "Company");
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
		if(M('comre')->where('id in('.I("post.ids","-1").')')->save($data)){ //删除成功后刷新数据
			$page=I("post.page",0);
			$size=I("post.size",5);
			$count=M('comre')->where("isdelete=0 and ver=".session("ver")." and parentid=".I("post.id",0))->count();
			$T=M('comre')->where("isdelete=0 and ver=".session("ver")." and parentid=".I("post.id",0))-> order('addtime desc')->limit($page*$size,$size)->select();	
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
					$count=M('comre')->where("isdelete=0 and ver=".session("ver")." and parentid=".I("post.id",0))->count();
					$T=M('comre')->where("isdelete=0 and ver=".session("ver")." and parentid=".I("post.id",0))-> order('addtime desc')->limit($page*$size,$size)->select();
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
	

	
}

//输出列表
function showitem($T){
	$data=array();
	foreach($T as $t=>$v){
		$data[$t]["id"]=$v['id'];
		$data[$t]["data"][]=$v['id'];
		$data[$t]["data"][]=$v['newcontent'];
		$data[$t]["data"][]=$v['addtime'];
		$data[$t]["data"][]=$v['putout'];
		$data[$t]["data"][]=0;
	}
	return $data;
}
