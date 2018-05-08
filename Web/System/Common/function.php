<?php

//验证验证码
function check_code($code){
	ob_clean();
	$verify = new \Think\Verify();  
	return $verify->check($code);  
}

//日志
function login_info($info,$tabinfo){
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
	$data['act'] = $info;
	$data['login_tab'] = $tabinfo;
    $Note->add($data);
}

/**
 *核对IP，将存在的锁定
**/
function check_ip(){
	$ip=get_client_ip();
	$lock_ip=M('sys_site')->where('ver=0')->find();
	$allip=explode(",", $lock_ip['lock_ip']);
	if(in_array($ip,$allip)){
		ob_clean();
		header("Content-Type:text/html;charset=utf-8");
		echo '对不起,您的IP已被锁定.若有疑问请联系管理员.';
		session(null);
		exit;
	}
}

/**
 * 获取客户端浏览器类型
 * @param  string $glue 浏览器类型和版本号之间的连接符
 * @return string|array 传递连接符则连接浏览器类型和版本号返回字符串否则直接返回数组 false为未知浏览器类型
 */
function get_client_browser($glue = null) {
    $browser = array();
    $agent = $_SERVER['HTTP_USER_AGENT']; //获取客户端信息
    
    /* 定义浏览器特性正则表达式 */
    $regex = array(
        'ie'      => '/(MSIE) (\d+\.\d)/',
        'chrome'  => '/(Chrome)\/(\d+\.\d+)/',
        'firefox' => '/(Firefox)\/(\d+\.\d+)/',
        'opera'   => '/(Opera)\/(\d+\.\d+)/',
        'safari'  => '/Version\/(\d+\.\d+\.\d) (Safari)/',
    );
    foreach($regex as $type => $reg) {
        preg_match($reg, $agent, $data);
        if(!empty($data) && is_array($data)){
            $browser = $type === 'safari' ? array($data[2], $data[1]) : array($data[1], $data[2]);
            break;
        }
    }
    return empty($browser) ? '未知类型' : (is_null($glue) ? $browser : implode($glue, $browser));
}

/*获取操作系统*/
function getOS(){ 
    $os=''; 
    $Agent=$_SERVER['HTTP_USER_AGENT']; 
    if (eregi('win',$Agent)&&strpos($Agent, '95')){ 
        $os='Windows 95'; 
    }elseif(eregi('win 9x',$Agent)&&strpos($Agent, '4.90')){ 
        $os='Windows ME'; 
    }elseif(eregi('win',$Agent)&&ereg('98',$Agent)){ 
        $os='Windows 98'; 
    }elseif(eregi('win',$Agent)&&eregi('nt 5.0',$Agent)){ 
        $os='Windows 2000'; 
    }elseif(eregi('win',$Agent)&&eregi('nt 6.0',$Agent)){ 
        $os='Windows Vista'; 
    }elseif(eregi('win',$Agent)&&eregi('nt 6.1',$Agent)){ 
        $os='Windows 7'; 
    }elseif(eregi('win',$Agent)&&eregi('nt 5.1',$Agent)){ 
        $os='Windows XP'; 
    }elseif(eregi('win',$Agent)&&eregi('nt',$Agent)){ 
        $os='Windows NT'; 
    }elseif(eregi('win',$Agent)&&ereg('32',$Agent)){ 
        $os='Windows 32'; 
    }elseif(eregi('linux',$Agent)){ 
        $os='Linux'; 
    }elseif(eregi('unix',$Agent)){ 
        $os='Unix'; 
    }else if(eregi('sun',$Agent)&&eregi('os',$Agent)){ 
        $os='SunOS'; 
    }elseif(eregi('ibm',$Agent)&&eregi('os',$Agent)){ 
        $os='IBM OS/2'; 
    }elseif(eregi('Mac',$Agent)&&eregi('PC',$Agent)){ 
        $os='Macintosh'; 
    }elseif(eregi('PowerPC',$Agent)){ 
        $os='PowerPC'; 
    }elseif(eregi('AIX',$Agent)){ 
        $os='AIX'; 
    }elseif(eregi('HPUX',$Agent)){ 
        $os='HPUX'; 
    }elseif(eregi('NetBSD',$Agent)){ 
        $os='NetBSD'; 
    }elseif(eregi('BSD',$Agent)){ 
        $os='BSD'; 
    }elseif(ereg('OSF1',$Agent)){ 
        $os='OSF1'; 
    }elseif(ereg('IRIX',$Agent)){ 
        $os='IRIX'; 
    }elseif(eregi('FreeBSD',$Agent)){ 
        $os='FreeBSD'; 
    }elseif($os==''){ 
        $os='Unknown'; 
    } 
    return $os; 
} 

//目录树方法
function show_kid($parent){
	$menu = M('sys_menu')->where('putout=1 and menu_parent='.$parent)->order('orderid asc')->select();
	if($menu){
		for($i=0;$i<count($menu);$i++){
			if(session('adminclass')=="99"){
				$tmp .= "<a href='".$menu[$i]['menu_url']. "' target='frame_right'><span style='background-position:left ". (3 - 30 * ($i % 10)) ."px;'>".$menu[$i]['menu_name']. "</span></a>";
			}else if(strpos(session("parts"),',' . $menu[$i]['id']. ',')!==false){
				 $tmp .= "<a href='".$menu[$i]['menu_url']."' target='frame_right'><span style='background-position:left " .(3 - 30 * ($i % 10)) . "px;'>" .$menu[$i]['menu_name']."</span></a>";
				
			}	
		}	
	}
	return $tmp;
}

//查询导航
function show($parent){
	$menu = M('sys_menu')->where('putout=1 and menu_parent=0')->order('orderid asc')->select();
	if($menu){
		
		for($i=0;$i<count($menu);$i++){
			
			$tmp = show_kid($menu[$i]["id"]);
			if($tmp!=""){
				if($i==0){
					 $tree .= "<a href='javascript:void(0)' class='cura'><i class='left_tico'></i>".$menu[$i]['menu_name']."</a><div class='left_sub'><div class='left_sub1'>".$tmp."</div></div>";
				}else{
					 $tree .= "<a href='javascript:void(0)'><i class='left_tico'></i>".$menu[$i]['menu_name']."</a><div class='left_sub' style='display:none'><div class='left_sub1'>".$tmp."</div></div>";
				}
			}
		}
	}
	return $tree;
}

//核对权限
function loadcheck($ids){
	If(!session("?admin")||!session("?ver")){
		ob_clean();
		header("Content-Type:text/html;charset=utf-8");
		echo "<script>alert('登陆超时,请重新登陆!');window.parent.location='/System.php';</script>";
		exit;
	}
	
	if(session("adminclass")==0){
		if(strpos(session("parts"),',' . $ids. ',')===false){
			ob_clean();
			header("Content-Type:text/html;charset=utf-8");
			echo "你无权访问本页!";
			exit;		
		}
	}
	
}

//ajax核对权限
function ajaxcheck($ids){
	If(!session("?admin")||!session("?ver")){
		return false;
	}
	
	if(session("adminclass")==0){
		if(strpos(session("parts"),',' . $ids. ',')===false){
			return false;		
		}
	}
	return true;
}

//xml字符串
function show_xml($parentid){
	$menu= M('sys_menu')->where('putout=1 and menu_parent='.$parentid)->order('orderid asc')->select();
	if($menu){
		foreach($menu as $k=>$v){
			$tree .= '<item text="' . $v["menu_name"] . '" id="'.$v["id"].'" db_id="'.$v["id"].'">'. show_xml($v["id"]).'</item>';
		}
	}
	return $tree;
}

//xml字符串
function show_xml1($parentid,$parts){
	$menu= M('sys_menu')->where('putout=1 and menu_parent='.$parentid)->order('orderid asc')->select();
	if($menu){
		foreach($menu as $k=>$v){	
			if(strpos($parts,',' . $v["id"]. ',')!== false){
				if($parentid!=0){
					$checked = "checked='0'";
				}
				else{
					$checked ="";	
				}
			
			}else{
				$checked ="";
			}		
			$tree .= '<item text="' . $v["menu_name"] .'" '.$checked.' id="'.$v["id"].'" db_id="'.$v["id"].'">'. show_xml1($v["id"],$parts).'</item>';
		}
	}
	return $tree;
}
