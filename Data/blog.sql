/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2018-05-09 11:30:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `db_company`
-- ----------------------------
DROP TABLE IF EXISTS `db_company`;
CREATE TABLE `db_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) DEFAULT NULL,
  `treeid` varchar(100) DEFAULT NULL,
  `newtitle` varchar(100) DEFAULT NULL,
  `newdesc` varchar(300) DEFAULT NULL,
  `newcontent` text,
  `hit` int(11) DEFAULT '1' COMMENT '查看次数',
  `putman` varchar(20) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL COMMENT '回复时间',
  `isdelete` tinyint(1) DEFAULT '0',
  `putout` tinyint(1) DEFAULT '1',
  `ver` tinyint(1) DEFAULT '0',
  `page_tit` varchar(200) DEFAULT NULL,
  `page_key` varchar(400) DEFAULT NULL,
  `page_desc` varchar(800) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_company
-- ----------------------------
INSERT INTO `db_company` VALUES ('1', '1', '-20-', '分享本系统源代码', '一个简单的信息发布平台，免得在重复造轮子，直接把改系统源码分享给大家，一起学习，一起进步。', '<p><strong>一、效果</strong></p><p>效果和现在网站效果一模一样，如果要修改，可以自己修改源码。</p><p><strong>二、安装环境</strong></p><p>该系统用Thinkphp搭建，满足php+Mysql就可以了</p><p>解压后，要修改数据库连接为自己的连接，修改文件为Web/Commom/Conf/config.php<br/></p><div class=\"code\"><p>/* 数据库配置 */</p><p>&#39;DB_TYPE&#39; &nbsp; =&gt; &#39;mysqli&#39;, // 数据库类型</p><p>&#39;DB_HOST&#39; &nbsp; =&gt; &#39;127.0.0.1&#39;, // 服务器地址,修改为自己的数据库连接</p><p>&#39;DB_NAME&#39; &nbsp; =&gt; &#39;blog&#39;, // 数据库名，修改为自己的数据库名</p><p>&#39;DB_USER&#39; &nbsp; =&gt; &#39;root&#39;, // 用户名，修改为自己的数据库登录账号</p><p>&#39;DB_PWD&#39; &nbsp; &nbsp;=&gt; &#39;&#39;, &nbsp;// 密码，修改为自己的数据库登录密码</p><p>&#39;DB_PORT&#39; &nbsp; =&gt; &#39;3306&#39;, // 端口</p><p>&#39;DB_PREFIX&#39; =&gt; &#39;db_&#39;, // 数据库表前缀</p></div><p><strong>三、下载地址</strong></p><p><a href=\"https://github.com/xmlhttp/info\" target=\"_blank\" title=\"源码下载\">https://github.com/xmlhttp/info</a></p>', '33', '三点三伏', '2018-05-09 09:58:00', null, '0', '1', '0', '一个简单的信息发布平台', '一个简单的信息发布平台', '一个简单的信息发布平台');
INSERT INTO `db_company` VALUES ('2', '2', '-9-', '解决百度编辑器会自动过滤div和span的方法', '在使用百度编辑器从源代码切换到可视界面后，发现里面的div、span都会被过滤掉，在网上查找了一下搞定了，分享一下。', '<p>在使用百度编辑器从源代码切换到可视界面后，发现里面的div、span都会被过滤掉，在网上查找了一下搞定了，分享一下。</p><p><strong>1、在ueiter.all.js中找到allowDivTransToP，在9970行</strong></p><div class=\"code\"><p>me.setOpt({</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &#39;allowDivTransToP&#39;:true,</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &#39;disabledTableInTable&#39;:true</p><p>});</p></div><p>把 true改为false</p><div class=\"code\"><p>me.setOpt({</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &#39;allowDivTransToP&#39;:false,</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &#39;disabledTableInTable&#39;:true</p><p>});</p></div><p><strong>2、在ueditor.config.js中找到allowDivTransToP，在343行</strong></p><div class=\"code\"><p>//默认过滤规则相关配置项目</p><p>//,disabledTableInTable:true&nbsp; //禁止表格嵌套</p><p>//,allowDivTransToP:true&nbsp; &nbsp; &nbsp; //允许进入编辑器的div标签自动变成p标签</p><p>//,rgb2Hex:true&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;//默认产出的数据中的color自动从rgb格式变成16进制格式</p></div><p>将true改为false，如下</p><div class=\"code\"><p>//默认过滤规则相关配置项目</p><p>//,disabledTableInTable:true&nbsp; //禁止表格嵌套</p><p>,allowDivTransToP:false&nbsp; &nbsp; &nbsp; //允许进入编辑器的div标签自动变成p标签</p><p>//,rgb2Hex:true&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;//默认产出的数据中的color自动从rgb格式变成16进制格式</p></div><p><strong>3、在ueditor.config.js中找到whitList白名单，在353行</strong></p><div class=\"code\"><p>// xss过滤白名单 名单来源: https://raw.githubusercontent.com/leizongmin/js-xss/master/lib/default.js</p><p>,whitList: {</p><p>&nbsp; &nbsp; a:&nbsp; &nbsp; &nbsp; [&#39;target&#39;, &#39;href&#39;, &#39;title&#39;, &#39;class&#39;, &#39;style&#39;],</p><p>&nbsp; &nbsp; abbr:&nbsp; &nbsp;[&#39;title&#39;, &#39;class&#39;, &#39;style&#39;],</p><p>&nbsp; &nbsp; address: [&#39;class&#39;, &#39;style&#39;],</p><p>&nbsp; &nbsp; area:&nbsp; &nbsp;[&#39;shape&#39;, &#39;coords&#39;, &#39;href&#39;, &#39;alt&#39;],</p></div><p>添加div[] 和span[] ，代码如下：</p><div class=\"code\"><p>// xss过滤白名单 名单来源: https://raw.githubusercontent.com/leizongmin/js-xss/master/lib/default.js</p><p>,whitList: {</p><p>&nbsp; &nbsp; div:<span style=\"white-space:pre\"></span>[&#39;class&#39;, &#39;style&#39;,&#39;id&#39;],</p><p>&nbsp; &nbsp; span:<span style=\"white-space:pre\"></span>[&#39;class&#39;, &#39;style&#39;,&#39;id&#39;],</p><p>&nbsp; &nbsp; a:&nbsp; &nbsp; &nbsp; [&#39;target&#39;, &#39;href&#39;, &#39;title&#39;, &#39;class&#39;, &#39;style&#39;],</p><p>&nbsp; &nbsp; abbr:&nbsp; &nbsp;[&#39;title&#39;, &#39;class&#39;, &#39;style&#39;],</p><p>&nbsp; &nbsp; address: [&#39;class&#39;, &#39;style&#39;],</p><p>&nbsp; &nbsp; area:&nbsp; &nbsp;[&#39;shape&#39;, &#39;coords&#39;, &#39;href&#39;, &#39;alt&#39;],</p></div><p>根据自己的需要在数组中添加需要在div或者span中插入的属性<br/></p><p><strong>4、在ueiter.all.js中找到me.addOutputRule... 在10138行</strong></p><p>将该函数内的部分代码注释掉，改为如下</p><div class=\"code\"><p>&nbsp; &nbsp; //从编辑器出去的内容处理</p><p>&nbsp; &nbsp; me.addOutputRule(function (root) {</p><p><br/></p><p>&nbsp; &nbsp; &nbsp; &nbsp; var val;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; root.traversal(function (node) {</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; if (node.type == &#39;element&#39;) {</p><p><br/></p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;/* if (me.options.autoClearEmptyNode &amp;&amp; dtd.$inline[node.tagName] &amp;&amp; !dtd.$empty[node.tagName] &amp;&amp; (!node.attrs || utils.isEmptyObject(node.attrs))) {</p><p><br/></p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; if (!node.firstChild()) node.parentNode.removeChild(node);</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; else if (node.tagName == &#39;span&#39; &amp;&amp; (!node.attrs || utils.isEmptyObject(node.attrs))) {</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.parentNode.removeChild(node, true)</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; return;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }*/</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; switch (node.tagName) {</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; case &#39;div&#39;:</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; if (val = node.getAttr(&#39;cdata_tag&#39;)) {</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.tagName = val;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.appendChild(UE.uNode.createText(node.getAttr(&#39;cdata_data&#39;)));</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.setAttr({cdata_tag: &#39;&#39;, cdata_data: &#39;&#39;,&#39;_ue_custom_node_&#39;:&#39;&#39;});</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; break;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; case &#39;a&#39;:</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; if (val = node.getAttr(&#39;_href&#39;)) {</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.setAttr({</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#39;href&#39;: utils.html(val),</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#39;_href&#39;: &#39;&#39;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; })</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; break;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; break;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;/* case &#39;span&#39;:</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; val = node.getAttr(&#39;id&#39;);</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; if(val &amp;&amp; /^_baidu_bookmark_/i.test(val)){</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.parentNode.removeChild(node)</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; break;*/</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; case &#39;img&#39;:</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; if (val = node.getAttr(&#39;_src&#39;)) {</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; node.setAttr({</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#39;src&#39;: node.getAttr(&#39;_src&#39;),</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#39;_src&#39;: &#39;&#39;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; })</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p><br/></p><p><br/></p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</p><p><br/></p><p>&nbsp; &nbsp; &nbsp; &nbsp; })</p><p><br/></p><p><br/></p><p>&nbsp; &nbsp; });</p><p>};</p></div><p><strong>5、在ueiter.all.js中找到enterTag: &#39;p&#39;,改为：enterTag: &#39;&#39;，在8059行，改为如下：</strong></p><div class=\"code\"><p>imagePopup: true,</p><p>enterTag: &#39;&#39;,</p><p>customDomain: false,</p></div><p>按照以上修改完成后div和span基本不会被修改，本系统使用的百度编辑器，只修改到了这一步。</p><p>如果想要什么都不带的空白标签不被过滤，还可以把ueiter.all.js的autoClearEmptyNode:true第8054行改成flase试试。</p>', '15', '三点三伏', '2018-05-09 10:30:11', null, '0', '1', '0', '解决百度编辑器会自动过滤div和span的方法', '解决百度编辑器会自动过滤div和span的方法', '解决百度编辑器会自动过滤div和span的方法');

-- ----------------------------
-- Table structure for `db_comre`
-- ----------------------------
DROP TABLE IF EXISTS `db_comre`;
CREATE TABLE `db_comre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) DEFAULT NULL,
  `newcontent` text,
  `addtime` datetime DEFAULT NULL,
  `isdelete` tinyint(1) DEFAULT '0',
  `putout` tinyint(1) DEFAULT '1',
  `ver` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_comre
-- ----------------------------

-- ----------------------------
-- Table structure for `db_deeptree`
-- ----------------------------
DROP TABLE IF EXISTS `db_deeptree`;
CREATE TABLE `db_deeptree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `content` varchar(100) NOT NULL,
  `content_en` varchar(100) DEFAULT NULL,
  `class` varchar(10) NOT NULL,
  `ver` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `page_tit` varchar(200) DEFAULT NULL,
  `page_key` varchar(400) DEFAULT NULL,
  `page_desc` varchar(800) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_deeptree
-- ----------------------------
INSERT INTO `db_deeptree` VALUES ('9', '9', '0', '前端', 'qd', 'Company', '0', '2017-03-25 14:55:12', '前端开发技术交流 - 三点三伏', 'html|css|javascript|js|微信小程序|支付宝小程序', '分享和交流html,css,javascript,小程序等技术');
INSERT INTO `db_deeptree` VALUES ('16', '16', '0', 'APP', 'APP', 'Company', '0', '2018-04-27 09:28:14', 'APP开发技术交流 - 三点三伏', 'android|iOS|安卓|苹果', '分享和交流安卓和苹果等技术');
INSERT INTO `db_deeptree` VALUES ('17', '17', '0', '后台', 'ht', 'Company', '0', '2018-04-27 09:31:13', '后端开发技术交流 - 三点三伏', 'C#|VB.NET|PHP', '分享和交流C#,VB.NET,PHP等技术');
INSERT INTO `db_deeptree` VALUES ('18', '18', '0', '嵌入式', 'qrs', 'Company', '0', '2018-04-27 09:31:20', '嵌入式开发技术交流 - 三点三伏', 'STM32|ESP8266', '分享和交流STM32,ESP8266等技术');
INSERT INTO `db_deeptree` VALUES ('19', '19', '0', '教程', 'jc', 'Company', '0', '2018-04-27 09:31:24', '教程分享 - 三点三伏', '教程分享', '教程分享');
INSERT INTO `db_deeptree` VALUES ('20', '20', '0', '其他', 'qt', 'Company', '0', '2018-04-27 09:36:26', '其他软件分享 - 三点三伏', '其他软件分享', '其他软件分享');
INSERT INTO `db_deeptree` VALUES ('21', '21', '0', '友情链接', 'yqlj', 'Link', '0', '2018-04-28 16:38:04', null, null, null);

-- ----------------------------
-- Table structure for `db_link`
-- ----------------------------
DROP TABLE IF EXISTS `db_link`;
CREATE TABLE `db_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) DEFAULT NULL,
  `treeid` varchar(100) DEFAULT NULL,
  `newtitle` varchar(200) DEFAULT NULL,
  `newadd` varchar(200) DEFAULT NULL,
  `newdesc` varchar(400) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `putout` tinyint(1) DEFAULT '1',
  `ver` tinyint(1) DEFAULT '0',
  `isdelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_link
-- ----------------------------
INSERT INTO `db_link` VALUES ('1', '1', '-21-', '三点三伏', 'http://blog.vmuui.com', '本网站', '2018-04-28 16:38:47', '1', '0', '0');
INSERT INTO `db_link` VALUES ('2', '2', '-21-', '网站建设', 'https://www.vmuui.com/index.php?s=/Home/Index/Web.html', '', '2018-04-28 16:39:49', '1', '0', '0');
INSERT INTO `db_link` VALUES ('3', '5', '-21-', 'APP开发', 'https://www.vmuui.com/index.php?s=/Home/Index/App.html', '', '2018-04-28 16:40:21', '1', '0', '0');
INSERT INTO `db_link` VALUES ('4', '3', '-21-', '微信开发', 'https://www.vmuui.com/index.php?s=/Home/Index/Wechat.html', '', '2018-04-28 16:40:33', '1', '0', '0');
INSERT INTO `db_link` VALUES ('5', '4', '-21-', '百度', 'https://www.baidu.com/', '', '2018-04-28 16:41:01', '1', '0', '0');

-- ----------------------------
-- Table structure for `db_sys_admin`
-- ----------------------------
DROP TABLE IF EXISTS `db_sys_admin`;
CREATE TABLE `db_sys_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) DEFAULT NULL,
  `passwords` varchar(80) DEFAULT NULL,
  `adminClass` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `addtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `working` tinyint(4) DEFAULT NULL,
  `ver` varchar(50) DEFAULT '0',
  `parts` longtext,
  `weburl` varchar(100) DEFAULT '#',
  `Anumber` varchar(500) DEFAULT NULL,
  `androidid` varchar(100) DEFAULT NULL,
  `iosid` varchar(100) DEFAULT NULL,
  `money` float(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_sys_admin
-- ----------------------------
INSERT INTO `db_sys_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '99', 'sdfsdf', '2017-01-02 17:08:47', '1', '0', '', '#', '', '', '', '0.00');
INSERT INTO `db_sys_admin` VALUES ('2', 'super admin', '21232f297a57a5a743894a0e4a801fc3', '99', 'uweb', '2016-07-06 14:19:04', '1', '0', '', '#', null, null, null, '0.00');

-- ----------------------------
-- Table structure for `db_sys_menu`
-- ----------------------------
DROP TABLE IF EXISTS `db_sys_menu`;
CREATE TABLE `db_sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(100) DEFAULT NULL,
  `menu_parent` int(11) DEFAULT NULL,
  `menu_url` varchar(100) DEFAULT NULL,
  `orderid` int(11) DEFAULT NULL,
  `putout` tinyint(4) DEFAULT NULL,
  `side` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_sys_menu
-- ----------------------------
INSERT INTO `db_sys_menu` VALUES ('8', '信息管理', '0', '', '8', '1', null);
INSERT INTO `db_sys_menu` VALUES ('9', '目录结构', '8', '/System.php?s=/System/ManagerPage/xtree&class=Company', '9', '1', null);
INSERT INTO `db_sys_menu` VALUES ('10', '信息列表', '8', '/System.php?s=/System/Company', '10', '1', null);
INSERT INTO `db_sys_menu` VALUES ('11', '添加信息', '8', '/System.php?s=/System/Company/AddRead', '11', '1', null);
INSERT INTO `db_sys_menu` VALUES ('42', '友情链接', '0', null, '42', '1', null);
INSERT INTO `db_sys_menu` VALUES ('43', '目录结构', '42', '/System.php?s=/System/ManagerPage/xtree&class=Link', '43', '1', null);
INSERT INTO `db_sys_menu` VALUES ('44', '链接列表', '42', '/System.php?s=/System/Link', '44', '1', null);
INSERT INTO `db_sys_menu` VALUES ('45', '添加友链', '42', '/System.php?s=/System/Link/AddRead', '45', '1', null);
INSERT INTO `db_sys_menu` VALUES ('46', '系统管理', '0', null, '46', '1', null);
INSERT INTO `db_sys_menu` VALUES ('47', '系统设置', '46', '/System.php?s=/System/ManagerPage/sitesetup', '47', '1', null);
INSERT INTO `db_sys_menu` VALUES ('49', '管理员管理', '46', '/System.php?s=/System/AdminAll', '49', '1', null);
INSERT INTO `db_sys_menu` VALUES ('50', '日志管理', '46', '/System.php?s=/System/Log', '50', '1', null);
INSERT INTO `db_sys_menu` VALUES ('51', '修改密码', '46', '/System.php?s=/System/ManagerPage/ChangePwd', '51', '1', null);

-- ----------------------------
-- Table structure for `db_sys_note`
-- ----------------------------
DROP TABLE IF EXISTS `db_sys_note`;
CREATE TABLE `db_sys_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(50) DEFAULT NULL,
  `login_ip` varchar(50) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login_os` varchar(50) DEFAULT NULL,
  `login_ie` varchar(50) DEFAULT NULL,
  `act` varchar(255) DEFAULT NULL,
  `login_tab` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_sys_note
-- ----------------------------
INSERT INTO `db_sys_note` VALUES ('1', 'admin', '172.16.88.66', '2018-05-02 09:36:19', 'Windows 7', 'Firefox59.0', '【登录】登入成功', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('2', 'admin', '172.16.88.66', '2018-05-02 09:36:42', 'Windows 7', 'Firefox59.0', '【系统设置】 系统设置修改成功', 'sys_site');
INSERT INTO `db_sys_note` VALUES ('3', 'admin', '172.16.88.66', '2018-05-02 09:36:55', 'Windows 7', 'Firefox59.0', '【系统设置】 系统设置修改成功', 'sys_site');
INSERT INTO `db_sys_note` VALUES ('4', '-', '172.16.88.66', '2018-05-08 16:31:26', 'Windows 7', 'Chrome66.0', '【登录】密码错误', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('5', 'admin', '172.16.88.66', '2018-05-08 16:31:33', 'Windows 7', 'Chrome66.0', '【登录】登入成功', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('6', '-', '172.16.88.66', '2018-05-08 20:23:58', 'Windows 7', 'Chrome66.0', '【登录】密码错误', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('7', '-', '172.16.88.66', '2018-05-08 20:24:13', 'Windows 7', 'Chrome66.0', '【登录】密码错误', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('8', 'admin', '172.16.88.66', '2018-05-08 20:24:21', 'Windows 7', 'Chrome66.0', '【登录】登入成功', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('9', 'admin', '172.16.88.66', '2018-05-08 20:34:43', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项添加成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('10', '-', '172.16.88.66', '2018-05-09 09:40:56', 'Windows 7', 'Chrome66.0', '【登录】验证码错误', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('11', 'admin', '172.16.88.66', '2018-05-09 09:41:03', 'Windows 7', 'Chrome66.0', '【登录】登入成功', 'sys_admin');
INSERT INTO `db_sys_note` VALUES ('12', 'admin', '172.16.88.66', '2018-05-09 09:50:07', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('13', 'admin', '172.16.88.66', '2018-05-09 09:53:40', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('14', 'admin', '172.16.88.66', '2018-05-09 09:54:53', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('15', 'admin', '172.16.88.66', '2018-05-09 09:58:51', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项添加成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('16', 'admin', '172.16.88.66', '2018-05-09 10:19:46', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('17', 'admin', '172.16.88.66', '2018-05-09 10:21:37', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('18', 'admin', '172.16.88.66', '2018-05-09 10:24:10', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('19', 'admin', '172.16.88.66', '2018-05-09 10:25:36', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('20', 'admin', '172.16.88.66', '2018-05-09 10:27:11', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('21', 'admin', '172.16.88.66', '2018-05-09 10:27:32', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[1]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('22', 'admin', '172.16.88.66', '2018-05-09 11:12:59', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[2]的项添加成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('23', 'admin', '172.16.88.66', '2018-05-09 11:15:12', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[2]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('24', 'admin', '172.16.88.66', '2018-05-09 11:20:26', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[2]的项修改成功', 'Company');
INSERT INTO `db_sys_note` VALUES ('25', 'admin', '172.16.88.66', '2018-05-09 11:24:56', 'Windows 7', 'Chrome66.0', '【企业信息】 信息ID为[2]的项修改成功', 'Company');

-- ----------------------------
-- Table structure for `db_sys_site`
-- ----------------------------
DROP TABLE IF EXISTS `db_sys_site`;
CREATE TABLE `db_sys_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(200) DEFAULT NULL,
  `siteWeb` varchar(100) DEFAULT NULL,
  `ver` int(11) DEFAULT NULL,
  `lock_ip` longtext,
  `page_tit` varchar(200) DEFAULT NULL,
  `page_key` varchar(400) DEFAULT NULL,
  `page_desc` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of db_sys_site
-- ----------------------------
INSERT INTO `db_sys_site` VALUES ('4', '三点三伏', 'blog.vmuui.com', '0', '172.16.88.61', '三点三伏 - 专注于Thinkphp|小程序|安卓开发|iOS开发|STM32|ESP8266等技术的分享和交流平台', 'Thinkphp|小程序|安卓开发|iOS开发|嵌入式|STM32|ESP8266|云平台', '分享交流自己的技术，记录自己的学习历程。');

-- ----------------------------
-- Table structure for `db_tc`
-- ----------------------------
DROP TABLE IF EXISTS `db_tc`;
CREATE TABLE `db_tc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `txt` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf32;

-- ----------------------------
-- Records of db_tc
-- ----------------------------
INSERT INTO `db_tc` VALUES ('1', '2');
INSERT INTO `db_tc` VALUES ('2', '3');
