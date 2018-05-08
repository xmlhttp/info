<?php
namespace System\Controller;
use Think\Controller;
class ManagerPageController extends Controller {

    public function index(){
		If(!session("?admin")){
			 redirect('/System.php');
		}     
		if(I('get.ver', '')!=''){
			session('ver',I('get.ver'));
		}
		$T=M('sys_site')->where('ver=0')->find();
		$this->assign('web',$T["siteWeb"]);
		$this->assign('tree',show(0));
    	$this->display('Index:ManagerPage');
    }
	//退出
	public function loginout(){
		$ie=get_client_browser('');
		$os=getOS();
		$ip=get_client_ip();
		if(session('?admin')){
			if(session('admin')=='super admin'){
				$username = '--';
			}else{
				$username =	session('admin');
			}
		}else{
			$username = '-';	
		}
		$Note=M("sys_note");
		$data['login_name'] = $username;
   		$data['login_ip'] = $ip;
		$data['login_os'] = $os;
		$data['login_ie'] = $ie;
		$data['act'] = "【退出】退出系统";
		$data['login_tab'] = '';
   		$Note->add($data);
		session(null);
		redirect('/System.php');
	}
	
	//首页，版权
	public function BaseInfo(){
		$this->assign('name',gethostbyname($_SERVER['SERVER_NAME']));
		$ctime=ini_get('max_execution_time').'秒';
		$this->assign('ctime',$ctime);
		$this->assign('os',getOS());
		$this->display('ManagerPage:BaseInfo');
	}
	//网站设置
	public function sitesetup(){
		loadcheck(47); 
		$Site=M('sys_site')->where('ver="'.session("ver").'"')->find();
		$this->assign('Site',$Site);	
		$this->display('ManagerPage:sitesetup');
	}
	
	//网站设设置——信息修改
	public function sitesetup_updata(){
		$json = array();		
		if(!ajaxcheck(47)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}

		if(I('post.sitename1', '')!=""){		
			$data['sitename']=I('post.sitename1', '');
			$data['siteWeb']=I('post.siteweb1', '');
			$data['lock_ip']=I('post.lock_ip', '');
			$data['page_tit']=I('post.page_tit', '');
			$data['page_key']=I('post.page_key', '');
			$data['page_desc']=I('post.page_desc', '');
			$result=M('sys_site')->where('ver=0')->save($data);
			if($result||$result===0){
				login_info("【系统设置】 系统设置修改成功", "sys_site");
				$json['status']['err']=0;
				$json['status']['msg']="更新成功！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}else{
				$json['status']['err']=2;
				$json['status']['msg']="修改数据失败或数据没有修改！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}			
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="站点名称不能为空！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
			
		}
	}

	//修改密码
	public function ChangePwd(){
		loadcheck(51); 
		$this->display('ManagerPage:ChangePwd');
	}
	
	//保存密码
	public function ChangPwdSave(){
		$json = array();
		if(!ajaxcheck(51)){
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		if(I('post.oldpwd', '')=="" || I('post.pwd', '')=="" || I('post.repwd', '')==""){
			
			$json['status']['err']=2;
			$json['status']['msg']="数据填写有误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		
		$T=M('sys_admin')->where('id='.session("uid").' and working=1')->select();
		if(count($T)==1){
			if($T[0]["passwords"]==md5(I('post.oldpwd'))){
				$data['passwords']=md5(I('post.pwd'));
				$result=M('sys_admin')->where('id='.session("uid").' and working=1')->save($data);
				if($result||$result===0){
					$json['status']['err']=0;
					$json['status']['msg']="修改成功！";
					login_info("【修改】管理员[".session("admin")."]修改密码成功", "sys_admin");
					ob_clean();
					$this->ajaxReturn($json, 'json');
					exit;
				}else{
					$json['status']['err']=2;
					$json['status']['msg']="修改数据失败或数据没有修改！";
					ob_clean();
					$this->ajaxReturn($json, 'json');
					exit;
				}
				
				
			}else{
				$json['status']['err']=2;
				$json['status']['msg']="旧密码不正确！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}
		}else{
			$json['status']['err']=1;
			$json['status']['msg']="您已经退出或权限不够！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
	}
	//目录结构编辑器
	public function xtree(){
		if(I('get.class', '')==""){
			header("Content-Type:text/html;charset=utf-8");
			echo "你无权访问本页!";
			exit;
		}
		$cls=I('get.class', '');
		$this->assign('cls',$cls);
		$this->display('ManagerPage:xtree');
	}
	//目录请求
	public function xtreeMenu(){
	
		if(I('get.class', '')==""){
			header("Content-Type:text/html;charset=utf-8");
			echo "你无权访问本页!";
			exit;
		}
		header("Content-type:text/xml");
		echo "<?xml version='1.0' encoding='utf-8'?><tree id='0'>" .show_xtree(0,I('get.class', ''))."</tree>";
			
	}
	//请求英文说明
	public function get_encont(){
		if(I('post.sid', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$en=M('deeptree')->where('id='.I('post.sid',0))->find();
		if($en){
			$json['txt']=$en["content_en"];
			$json['status']['err']=0;
			$json['status']['msg']="执行错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="执行错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}			
	}
	
	//添加子项
	public function AddItem(){
			if(I('post.class', '')==""||I('post.content', '')==""||I('post.parentid', '')==""||I('post.content_en', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$T=M('deeptree')->where('parentid='.I('post.parentid', '').' and class="'.I('post.class', '').'" and ver='.session("ver").' and content="'.I('post.content', '').'"')->select();
		if(count($T)>0){
			$json['status']['err']=2;
			$json['status']['msg']="信息有重复！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
		$en=preg_replace($regex,"",I('post.content_en', ''));
		$cen=chken($en);
		$data['parentid']=I('post.parentid', '');
		$data['content']=I('post.content', '');
		$data['content_en']=$cen;
		$data['class']=I('post.class', '');
		$data['addtime']=date('Y-m-d H:i:s');		
		if($lastInsId =M('deeptree')->add($data)){
			$da['orderid']=$lastInsId;
			if(M('deeptree')->where('id='.$lastInsId)->save($da)){
				$json['time']=date('Y-m-d H:i:s');
				$json['cid']=$lastInsId;
				$json['status']['err']=0;
				$json['status']['msg']="添加成功！";
				login_info("【新建】 信息ID为[".$lastInsId."]的项添加成功", "sys_menu");
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
	//修改名称
	public function ChangeName(){
		if(I('post.class', '')==""||I('post.content', '')==""||I('post.cid', '')==""||I('post.content_en', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		if($Item=M('deeptree')->where('id='.I('post.cid',0).' and class="'.I('post.class', '').'"')->find()){
			$T=M('deeptree')->where('parentid='.$Item["parentid"].' and class="'.I('post.class', '').'" and ver='.session("ver").' and content="'.I('post.content', '').'"')->select();
			if(count($T)>0){
				$json['status']['err']=2;
				$json['status']['msg']="信息有重复！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}

			$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
			$en=preg_replace($regex,"",I('post.content_en', ''));
			$cen=chken($en,I('post.cid',0));
			$data["content"]=I('post.content', '');
			$data["content_en"]=$cen;
			if(M('deeptree')->where('id='.I('post.cid',0))->save($data)){
				$json['time']=date('Y-m-d H:i:s');
				$json['cid']=I('post.cid',0);
				$json['status']['err']=0;
				$json['status']['msg']="修改成功！";
				login_info("【更新】 信息ID为[".I('post.cid',0)."]的项修改成功", "sys_menu");
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}else{
				$json['status']['err']=2;
				$json['status']['msg']="内容填写有误或者没有修改！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}	
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="数据查询错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}	
		
	}
	//删除节点
	public function DelItem(){
		if(I('post.class', '')==""||I('post.cid', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		$ids=del_kid(I('post.cid', ''));
		$ids=substr($ids,0,strlen($ids)-1); 
		M("deeptree")->delete($ids);
		$ct=M(I('post.class', ''))->where('treeid like "%-'.I('post.cid', '').'%-"')->select();
		if(count($ct)==0){
			$json['time']=date('Y-m-d H:i:s');
			$json['cid']=I('post.cid', '');
			$json['status']['err']=0;
			$json['status']['msg']="删除成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		
		$data["treeid"]='none';
		if(M(I('post.class', ''))->where('treeid like "%-'.I('post.cid', '').'%-"')->save($data)){
			$json['time']=date('Y-m-d H:i:s');
			$json['cid']=I('post.cid', '');
			$json['status']['err']=0;
			$json['status']['msg']="删除成功！";
			login_info("【删除】 信息ID为[".I('post.cid',0)."]的项删除成功", "sys_menu");
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		
	}
	
	//移动节点
	public function tondrag(){
		if(I('post.class', '')==""||I('post.id', '')==""||I('post.id2', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		$T=M('deeptree')->where('id='.I('post.id', '').' and class="'.I('post.class', '').'" and ver='.session("ver"))->find();
		$T1=M('deeptree')->where('parentid='.I('post.id2', '').' and class="'.I('post.class', '').'" and content="'.$T["content"].'" and ver='.session("ver"))->select();
			if(count($T1)>0){
				$json['status']['err']=2;
				$json['status']['msg']="信息有重复！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;	
			}

		$ctreeid=Porder(I('post.id', 0)).'-';
		$etreeid=Porder(I('post.id2', 0)).'-'.I('post.id', 0).'-';
		$T2=M(I('post.class',''))->where("treeid like '%-".I('post.id', 0)."-%'")->select();
		if(count($T2)==0||$T2){
			$data['parentid']=I('post.id2',0);
			if(M('deeptree')->where('id='.I('post.id',0).' and ver='.session("ver"))->save($data)){
				$json['status']['err']=0;
				$json['status']['msg']="修改成功！";
				$json['time']=date('Y-m-d H:i:s');
				login_info("【拖动】 信息ID为[".I('post.id', '')."]的项拖动到[".I('post.id2', '')."]成功", "sys_menu");
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}else{
				$json['status']['err']=2;
				$json['status']['msg']="内容填写有误或者没有修改1！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}
		}else{
			$sql="update db_".I('post.class', '')." set treeid=REPLACE (treeid,'".$ctreeid."','".$etreeid."') where treeid like '%-".I('post.id', '')."-%'";
			$data['parentid']=I('post.id2',0);
			if(M('deeptree')->where('id='.I('post.id',0).' and ver='.session("ver"))->save($data)&&M()-> query($sql)){
				$json['status']['err']=0;
				$json['status']['msg']="修改成功！";
				$json['time']=date('Y-m-d H:i:s');
				login_info("【拖动】 信息ID为[".I('post.id', '')."]的项拖动到[".I('post.id2', '')."]成功", "sys_menu");
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}else{
				$json['status']['err']=2;
				$json['status']['msg']="内容填写有误或者没有修改2！";
				ob_clean();
				$this->ajaxReturn($json, 'json');
				exit;
			}
		}		
	}
	
	//同级上移
	public function MoveUp(){
		if(I('post.class', '')==""||I('post.cid', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		$T=M('deeptree')->where('id='.I('post.cid', '').' and class="'.I('post.class', '').'" and ver='.session("ver"))->find();
		if(!$T){
			$json['status']['err']=4;
			$json['status']['msg']="数据有误，需要重新加载！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$T1=M('deeptree')->where('parentid='.$T['parentid'].' and orderid < '.$T['orderid'].' and class="'.I('post.class', '').'" and ver='.session("ver"))->order('orderid desc')->limit(1)->select();
		if(count($T1)!=1){
			$json['time']=date('Y-m-d H:i:s');
			$json['cid']=I('post.cid', '');
			$json['status']['err']=3;
			$json['status']['msg']="移动失败,已经到达该层级顶端！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$data["orderid"]=$T["orderid"];
		$data1["orderid"]=$T1[0]["orderid"];
		if(M('deeptree')->where('id='.I('post.cid', ''))->save($data1)&&M('deeptree')->where('id='.$T1[0]["id"])->save($data)){
			$json['time']=date('Y-m-d H:i:s');
			$json['cid']=I('post.cid', '');
			$json['status']['err']=0;
			$json['status']['msg']="移动成功！";
			login_info("【上移】 信息ID为[".I('post.cid', '')."]的项上移成功", "sys_menu");
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="内容填写有误或者没有修改！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	}
	
	//同级下移
	public function MoveDown(){
		if(I('post.class', '')==""||I('post.cid', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="参数不正确！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
		$T=M('deeptree')->where('id='.I('post.cid', '').' and class="'.I('post.class', '').'" and ver='.session("ver"))->find();
		if(!$T){
			$json['status']['err']=4;
			$json['status']['msg']="数据有误，需要重新加载！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$T1=M('deeptree')->where('parentid='.$T['parentid'].' and orderid > '.$T['orderid'].' and class="'.I('post.class', '').'" and ver='.session("ver"))->order('orderid asc')->limit(1)->select();
		if(count($T1)!=1){
			$json['time']=date('Y-m-d H:i:s');
			$json['cid']=I('post.cid', '');
			$json['status']['err']=3;
			$json['status']['msg']="移动失败,已经到达该层级顶端！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$data["orderid"]=$T["orderid"];
		$data1["orderid"]=$T1[0]["orderid"];
		if(M('deeptree')->where('id='.I('post.cid', ''))->save($data1)&&M('deeptree')->where('id='.$T1[0]["id"])->save($data)){
			$json['time']=date('Y-m-d H:i:s');
			$json['cid']=I('post.cid', '');
			$json['status']['err']=0;
			$json['status']['msg']="移动成功！";
			login_info("【下移】 信息ID为[".I('post.cid', '')."]的项下移成功", "sys_menu");
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="内容填写有误或者没有修改！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
	}
	
	//修改结构-读取
	public function EditRead(){
		if(I("get.id",0)==0){
			header("Content-Type:text/html;charset=utf-8");
			echo "你无权访问本页!";
			exit;
		}
		$Te=M('deeptree')->where('id='.I("get.id"),0)->select();
		if(count($Te)!=1){
			header("Content-Type:text/html;charset=utf-8");
			echo "你无权访问本页!";
			exit;
		}
		$this->assign('xtree',$Te[0]);	
		$this->display('ManagerPage:xtreeUpdata');
	}
	//修改结构-修改
	public function EditSave(){	
		$json = array();
		if(I('get.id', 0)==0||I('post.content', '')==""){
			$json['status']['err']=2;
			$json['status']['msg']="信息填写有误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$T=M('deeptree')->where('id='.I('get.id', ''))->select();
		if(count($T)!=1){
			$json['status']['err']=2;
			$json['status']['msg']="数据查询错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$T1=M('deeptree')->where('parentid='.$T[0]["parentid"].' and content="'.I('post.content', '').'" and id<>'.I('get.id', ''))->select();
		if(count($T1)>0){
			$json['status']['err']=2;
			$json['status']['msg']="目录名称重复！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;	
		}
		$data['content']=I('post.content', '');
		if(I('post.page_tit', '')!=""){
			$data['page_tit']=I('post.page_tit', '');
		}
		if(I('post.page_key', '')!=""){
			$data['page_key']=I('post.page_key', '');
		}
		if(I('post.page_desc', '')!=""){
			$data['page_desc']=I('post.page_desc', '');
		}
		if(M('deeptree')->where('id='.I('get.id', ''))->save($data)){
			$json['status']['err']=0;
			$json['status']['msg']="修改成功！";
			login_info("【修改】 信息ID为[".I('get.id', '')."]的项信息修改成功", "sys_menu");
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}else{
			$json['status']['err']=2;
			$json['status']['msg']="内容填写有误或者没有修改！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
			exit;
		}
		
	}
	
}

//目录结构列表
function show_xtree($parentid,$cls){
	$menu= M('deeptree')->where('parentid='.$parentid.' and class="'.$cls.'" and ver='.session("ver"))->order('orderid asc')->select();
	if($menu){
		foreach($menu as $k=>$v){
			$tree.='<item text="'.$v["content"].'" id="'.$v["id"].'" db_id="'.$v["id"].'">'.show_xtree($v["id"],$cls).'</item>';
		}
	}
	return $tree;
}

//防止英文重复
function chken($en){
	$T=M('deeptree')->where('content_en="'.$en.'"')->select();
	if(count($T)>0){
		$temp=$en.rand(pow(10,1), pow(10,2)-1);
		return chken($temp);
	}else{
		return $en;
	}
}

//修改防止英文重复
function chken1($en,$sid){
	$T=M('deeptree')->where('content_en="'.$en.'" and id<>'.$sid)->select();
	if(count($T)>0){
		$temp=$en.rand(pow(10,1), pow(10,2)-1);
		return chken1($temp,$sid);
	}else{
		return $en;
	}
}

//删除节点
function del_kid($pid){
	$root= M('deeptree')->where('parentid='.$pid.' and ver='.session("ver"))->order('orderid asc')->select();
	$ids.=$pid.",";
	if($root){
		foreach($root as $k=>$v){
			$ids.=del_kid($v["id"]);
		}
	}
	return $ids;
}
//节点顺序
function Porder($id){
	$treeids='-'.$id;
	$child=M('deeptree')->where('id='.$id.' and ver='.session("ver"))->find();
	if($child){
		if($child['parentid']!=0){
			$treeids=Porder($child['parentid']).$treeids;	
		}	
	}
	return $treeids;
	
}
