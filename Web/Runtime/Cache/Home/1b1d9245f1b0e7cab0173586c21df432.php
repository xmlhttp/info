<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title><?php echo ($info["page_tit"]); ?></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><meta name="copyright" content="Copyright 1999-2018 - 568615539"><meta name="Author" content="old-Year"><meta name="Robots" content="all"><meta name="Keywords" content="<?php echo ($info["page_key"]); ?>"><meta name="Description" content="<?php echo ($info["page_desc"]); ?>"><meta http-equiv="Page-Enter" content="blendTrans(Duration=0.5)" /><meta http-equiv="Page-Exit" content="blendTrans(Duration=0.5)" /><link href="favicon.ico" rel="shortcut icon" type="image/x-icon" /><link rel="stylesheet" href="/Web/Home/Public/css/static.css" /><script>var isIE6 = false</script><!--[if IE 6]><script type="text/javascript">isIE6=true</script><![endif]--><script src="/public/jquery.js" type="text/javascript"></script></head><body><div class="topbg"><div class="topinfo"><a class="logo" href="/" title="三点三伏个人博客"><img src="/Web/Home/Public/images/logo.png"  title="三点三伏个人博客" alt="三点三伏个人博客"/></a><div class="topnav"><?php if(0 == 1): ?><a href="/" title="首页" class="active">首页</a><?php else: ?><a href="/" title="首页">首页</a><?php endif; if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('/Home/Index/news',array('tid'=>$vo['id']));?>" title="<?php echo ($vo["content"]); ?>" <?php if(1 == $vo['act']): ?>class="active"<?php endif; ?>><?php echo ($vo["content"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></div></div></div>
<!--中间开始-->
<div class="midinfo fixed" style="padding-bottom:15px">
<!--左侧开始-->

<div class="midleft1">
<div class="midbg">
<div class="mlinfotit"><?php echo ($info["newtitle"]); ?></div>
<div class="mltip mltip1"><span class="mlman"><?php echo ($info["putman"]); ?></span><span class="mltime"><?php echo (date('Y年m月d日',strtotime($info["addtime"]))); ?></span><span class="mlhit">浏览<font style=" color:#f33"><?php echo ($info["hit"]); ?></font>次</span></div>
<div class="mlview">
<?php echo ($info["newcontent"]); ?>

</div>

<div class="idesc">
信息来源：三点三伏<br>
版权所有：<a>http://blog.vmuui.com</a>转载说明出处
</div>
</div>
<!------------------>
<div class="midbg midtxt">
<textarea class="midarea" id="midarea"></textarea>
<div class="midmsg fixed">
<div class="msgtip" id="msgtip"></div>
<div class="msgtool">
<img src="<?php echo U('/Home/Index/verify');?>" id="codeimg" class="msgewm" title="点击刷新" />
<input type="text" class="msginput" id="msginput" />
<input type="button" class="msgbtn" id="msgbtn" value="发表评论" />
</div>
</div>
</div>
<!------------------>
<div class="midbg midtxt" style="padding-top:10px;">

<?php if(is_array($relist)): $i = 0; $__LIST__ = $relist;if( count($__LIST__)==0 ) : echo "暂无评论" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="qutop">
<span class="qulou">[<?php echo ($i); ?>楼]</span><span class="qutime"><?php echo (date('Y年m月d日 H:i',strtotime($vo['addtime']))); ?></span>
</div>
<div class="qutxt">
<p><?php echo ($vo['newcontent']); ?></p>
</div><?php endforeach; endif; else: echo "暂无评论" ;endif; ?>


</div>

</div>
<!--左侧结束-->
<!--右侧开始-->
<div class="midright">

<div class="mrsearch">
<input type="text" placeholder="搜索..." id="search" />
</div>
<?php if(1 == 1): ?><div class="mrbg">
<div class="mrtit">最新文章</div>
<ul class="mrlist">
<?php if(is_array($rdata["news"])): $i = 0; $__LIST__ = $rdata["news"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('/Home/Index/info',array('id'=>$vo['id']));?>" title="<?php echo ($vo["newtitle"]); ?>" ><?php echo ($vo["newtitle"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
</div><?php endif; ?> 

<div class="mrbg">
<div class="mrtit">最新评论</div>
<ul class="mrlist">
<?php if(is_array($rdata["comment"])): $i = 0; $__LIST__ = $rdata["comment"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('/Home/Index/info',array('id'=>$vo['id']));?>" title="<?php echo ($vo["newtitle"]); ?>" ><?php echo ($vo["newtitle"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
</div>

<div class="mrbg">
<div class="mrtit">联系作者</div>
<div class="mrconenct">
<div><a href='http://wpa.qq.com/msgrd?v=1&uin=469100943&site=qq&menu=no' target='qqifr' title="联系QQ" style=" padding:6px 22px">联系QQ</a>
<a href='//shang.qq.com/wpa/qunwpa?idkey=8eee13c1d759e2617cb3de5498c6f34f817d4a54740decf2c5b83ec48e18ba6f' target='qqifr' title="STM32交流群">STM32交流群</a>
</div>
<div style="padding-top:15px"><a href="//shang.qq.com/wpa/qunwpa?idkey=c8841592ec83ded54df60142143f896c7eb1da31686a48eb85dbd24a22c49c02" title="Thinkphp群" target='qqifr'>Thinkphp群</a>
<a href="//shang.qq.com/wpa/qunwpa?idkey=0d6f955c457a6f5e6c3ae891792ed6cb4af1af6ceddda178848f992a31a62ea9" title="小程序群" target='qqifr'>小程序群</a>
</div>


</div>
</div>

<div class="mrbg">
<div class="mrtit">打赏作者</div>
<div class="mrdas">
<img src="/Web/Home/Public/images/ewm.png" />
</div>
</div>

<div class="mrbg">
<div class="mrtit">友情链接</div>
<div class="mrlink fixed">
<?php if(is_array($rdata["link"])): $i = 0; $__LIST__ = $rdata["link"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["newadd"]); ?>" title="<?php echo ($vo["newtitle"]); ?>" target="_blank"><?php echo ($vo["newtitle"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
</div>

</div>
<iframe name="qqifr" style="display:none" id="qqiframe"></iframe>
<!--右侧结束-->
</div>
<!--中间结束-->
<div class="foot">技术支持：<a title="三点三伏" href="/">三点三伏</a><span>&copy;</span>版权所有<span style="padding:0 10px"></span>备案号：<a target="_blank" href="http://www.miitbeian.gov.cn" title="工业和信息化部ICP/IP地址/域名信息备案管理系统">粤ICP备18026606号-1</a></div></body></html><script>$(function(){$("#newslist>li").click(function(){window.location.href="/index.php?s=/Home/Index/info/id/"+$(this).attr("rel")+".html"});$('#search').keyup(function(e){if(e.which==13){window.location.href="/index.php?s=/Home/Index/search/key/"+$(this).val()+".html"}})})</script>
<script>
$(function(){
	$("#msgbtn").click(function(){
		$("#msgtip").html("<span class='t_load'>评论提交中...</span>")
		var txt=$("#midarea").val(),
			code=$("#msginput").val();
		if(txt==""||code==""){
			setTimeout(function(){
				$("#msgtip").html("<span class='t_err'>提交信息不完整</span>")	
			},200)
			return
		}
		var PostData={
			parentid:<?php echo ($_GET['id']); ?> ,
			code:code,
			txt:txt	
		}
		$.ajax({
            url: "<?php echo U('/Home/Index/postrecom');?>",
            type: 'POST',
            dataType: 'json',
            data: PostData
        }).done(function(d) {
            if (d["status"]["err"] == 0) {
				setTimeout(function(){
                	$("#msgtip").html("<span class='t_right'>评论提交成功</span>")	
					$("#midarea").val("");
					$("#msginput").val("");
					 window.location.reload();
				},200)
            } else {
				setTimeout(function(){
					$("#msgtip").html("<span class='t_err'>"+d["status"]["msg"]+"</span>")	
					$("#codeimg").attr("src", '/index.php?s=/Home/Index/verify.html&'+Math.random());
				},200)
            }
        }).fail(function() {
			setTimeout(function(){
				$("#msgtip").html("<span class='t_err'>网络有误</span>")	
				$("#codeimg").attr("src", '/index.php?s=/Home/Index/verify.html&'+Math.random());
			},200)
		})
		
	})
	$("#codeimg").click(function(){
		$(this).attr("src", '/index.php?s=/Home/Index/verify.html&'+Math.random());  
	})
})
</script>