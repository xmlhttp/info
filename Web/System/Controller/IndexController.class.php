<?php
namespace System\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
		check_ip();
		$this->display();
    }	
	/*
	验证码
	*/
	public function verify(){
		ob_clean();
		$Verify = new \Think\Verify();  
   	 	$Verify->length = 4;
     	$Verify->entry();
	}
	
	/*
	验证登录
	*/
	public function Login(){
		check_ip();
		$uname=I('post.uname', ''); 
		$upwd=I('post.upwd', ''); 
		$ucode=I('post.ucode', ''); 
		$json = array();
		if(!$uname||!$upwd||!$ucode){
			login_info("【登录】信息填写不完整", "sys_admin");
			$json['status']['err']=1;
			$json['status']['msg']="信息填写不完整！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit;	
		}
		if(check_code($ucode) === false){
			login_info("【登录】验证码错误", "sys_admin");
			$json['status']['err']=1;
			$json['status']['msg']="验证码错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit; 
		}
	 	$User=M('sys_admin')->where('username="'.$uname.'" and working=1')->select();
		if (!$User) {
			login_info("【登录】不存在的用户", "sys_admin");
			$json['status']['err']=1;
			$json['status']['msg']="用户名或密码错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit; 
		}
		if(count($User)!=1){
			login_info("【登录】用户信息异常", "sys_admin");
			$json['status']['err']=1;
			$json['status']['msg']="用户信息异常！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit;
		}
		
		$cpwd=md5($upwd);
		if($cpwd==$User[0]['passwords']){
			session("uid", $User[0]["id"]);
			session("admin", $User[0]["username"]);
            session("adminclass",$User[0]["adminClass"]);
            session("parts", ','.$User[0]["parts"].',');
            session("ver", 0);
			login_info("【登录】登入成功", "sys_admin");
			$json['status']['err']=0;
			$json['status']['msg']="登录成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit;
		}else{
			login_info("【登录】密码错误", "sys_admin");
			$json['status']['err']=1;
			$json['status']['msg']="用户名或密码错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit;	
		}
	}
}
