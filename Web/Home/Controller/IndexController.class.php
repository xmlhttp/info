<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {    

    /*
     * 主页
     * 
     * return #
    */
    public function index(){
		$info=M('sys_site')->where('ver=0')->find();
		$this->assign('nav',$this->navdata(0));
		$sql="select db_company.*,db_deeptree.content as content from db_company left join db_deeptree on db_company.treeid = CONCAT('-',db_deeptree.id,'-') where db_company.putout=1 and db_company.isdelete=0 order by db_company.addtime desc LIMIT 0,20";
		$list=M()-> query($sql);
		$this->assign('info',$info);
		$this->assign('list',$list);
		$this->assign('rdata',$this->rightdata());
		$this->display();
    }
    
	/*
     * 信息列表
     * 
     * return #
    */
    public function news(){
		$tid=I("get.tid",0,'intval');
		if($tid==0){
			$this->error('参数有误','/',5);	
		}
		$info=M('deeptree')->where('parentid=0 and class="Company" and ver=0 and id='.$tid)->find();
		if($info){
			$this->assign('info',$info);	
		}
		$this->assign('nav',$this->navdata($tid));
		$page=I("get.page",1,'intval')-1;//当前页面
		$size=12;//每页条数
		$count=M('company')->where("isdelete=0 and ver=0 and putout=1 and treeid='-".$tid."-'")->count();
		$T=M('company')->where("isdelete=0 and ver=0 and putout=1 and treeid='-".$tid."-'")-> order('orderid desc')->limit($page*$size,$size)->select();
		$pagecount=ceil($count/$size);//总页数
		if($T){
			$pagecode = new page($count, $size, $page+1,'/index.php?s=/Home/Index/news&tid='.$tid.'&page={page}');
			$this->assign('page',$pagecode->myde_write());
			$this->assign('list',$T);
		}
		$this->assign('rdata',$this->rightdata());
		$this->display("news");
    }
	
	
	/*
     * 详情
    */
    public function info(){
		$id=I("get.id",0,'intval');
		if($id==0){
			$this->error('参数有误','/',5);	
		}
		M('company')->where('id='.$id)->setInc('hit');
		$txt=M('company')->where("isdelete=0 and ver=0 and putout=1 and id=".$id)->find();
		if(!$txt){
			$this->error('参数有误','/',5);	
		}
		$relist=M('comre')->where("isdelete=0 and ver=0 and putout=1 and parentid=".$id)->order('addtime asc')->select();
		for($i=0;$i<count($relist);$i++){
			$relist[$i]["newcontent"]=str_replace(array("  ","　","\r\n","\n","\r"),array("&nbsp;","&nbsp;","</p><p>","</p><p>","</p><p>"),$relist[$i]["newcontent"]);
		}
		$tid=str_replace("-","",$txt['treeid']);
		$this->assign('nav',$this->navdata($tid));	//导航数据
		$this->assign('rdata',$this->rightdata());	//右侧数据
		$this->assign('info',$txt);					//信息详情
		$this->assign('relist',$relist);			//留言
		$this->display("info");
    }
	/*
     * 搜索列表
     * 
     * return #
    */
    public function search(){
		$key=I("get.key","",'strip_tags');
		if($key==""){
			$this->error('参数有误','/',5);	
		}
		$info=M('sys_site')->where('ver=0')->find();
		$this->assign('nav',$this->navdata(0));
		$page=I("get.page",1,'intval')-1;//当前页面
		$size=12;//每页条数
		$count=M('company')->where("isdelete=0 and ver=0 and putout=1 and (newtitle like '%".$key."%' or newcontent like '%".$key."%')")->count();
		$sql="select db_company.*,db_deeptree.content as content from db_company left join db_deeptree on db_company.treeid = CONCAT('-',db_deeptree.id,'-') where db_company.putout=1 and db_company.isdelete=0 and (db_company.newtitle like '%".$key."%' or db_company.newcontent like '%".$key."%') order by db_company.addtime desc LIMIT ".$page*$size.",".$size;
		
		$T=M()-> query($sql);
		$pagecount=ceil($count/$size);//总页数
		if($T){
			$pagecode = new page($count, $size, $page+1,'/index.php?s=/Home/Index/search&key='.$key.'&page={page}');
			$this->assign('page',$pagecode->myde_write());
			$this->assign('list',$T);
		}
		$this->assign('info',$info);	
		$this->assign('rdata',$this->rightdata());
		$this->display("search");
    }
	
	
	/*
     * 提交评论
    */
	public function postrecom(){
		$parentid=I("post.parentid",0,'intval');
		$code=I("post.code","",'strip_tags');
		$txt=I("post.txt","",'strip_tags');
		if($parentid==0||$code==""||str_replace(" ","",$txt)==""){
			$json['status']['err']=1;
			$json['status']['msg']="信息填写不完整！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit;	
		}
		if($this->check_code($code) === false){
			$json['status']['err']=1;
			$json['status']['msg']="验证码错误！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit; 
		}
		$data["parentid"]=$parentid;
		$data["newcontent"]=$txt;
		$data["addtime"]=date('Y-m-d H:i:s',time());
		$data1["posttime"]=date('Y-m-d H:i:s',time());
		M('company')->where('id='.$parentid)->save($data1);
		if($lastInsId =M('comre')->add($data)){
			$json['status']['err']=0;
			$json['status']['msg']="添加成功！";
			ob_clean();
			$this->ajaxReturn($json, 'json');
            exit;
		}
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
	验证验证码
	*/
	
	function check_code($code){
		ob_clean();
		$verify = new \Think\Verify();  
		return $verify->check($code);  
	}
	/*
	 *导航数据
	 *
	 *
	*/
	public function navdata($act){
		$sql="select *,case when id=".$act." then 1 else 0 end as act from db_deeptree where parentid=0 and class='Company' and ver=0 order by orderid asc";
		return M()-> query($sql);
	}
	/*
	 *右侧数据
	 *
	 *
	*/
	public function rightdata(){
		$news = M('company')->where('putout=1 and isdelete=0 and ver=0')->order('addtime desc')->limit(0,5)->select();
		$rdata["news"]=$news;
		$comm = M('company')->where('putout=1 and isdelete=0 and ver=0')->order('posttime desc')->limit(0,5)->select();
		$rdata["comment"]=$comm;
		$link=M('link')->where('putout=1 and isdelete=0 and ver=0')->order('orderid asc')->select();
		$rdata["link"]=$link;
		return $rdata;
	}
	
}
