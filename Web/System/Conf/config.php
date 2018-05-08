<?php
return array(
	//'配置项'=>'配置值'
	'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
	'DEFAULT_FILTER'=>'strip_tags,htmlspecialchars',
	'__GRIDIMG__'=> __ROOT__.'/Web/System/Public/Tool/codebase/imgs/dhxgrid_material/',
	
	'TMPL_PARSE_STRING'=> array(
		'__PUBLIC__' => __ROOT__.'/Web/System/Public',
		'__CSS__' =>__ROOT__.'/Web/System/Public/css',
		'__IMG__' => __ROOT__.'/Web/System/Public/images',
		'__JS__' => __ROOT__.'/Web/System/Public/js',
		'__TOOL__' => __ROOT__.'/Web/System/Public/Tool',
		'__UPLOAD__' => __ROOT__.'/Web/UploadFile',
		'__TREEIMG__'=> __ROOT__.'/Web/System/Public/Tool/codebase/imgs/dhxtree_material/',
		'__GRIDIMG__'=> __ROOT__.'/Web/System/Public/Tool/codebase/imgs/dhxgrid_material/',
		'__BEDITOR__'=> __ROOT__.'/Web/BEditor'
	 )
);
