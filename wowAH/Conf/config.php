<?php
return array(
	// 调试
	'SHOW_PAGE_TRACE' => false, // 显示页面Trace信息

	'TMPL_ENGINE_TYPE' => 'PHP',

	// HOST
	'HOST_URL'        => 'http://eggache.kmdns.net/wowAH',

	/* URL设置 */
    'URL_MODEL'       => 2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：

    /* 路由设置 */
    'URL_ROUTER_ON'   => true, //开启路由
	'URL_ROUTE_RULES' => array( //定义路由规则
	    'Item/:itemId' => array('Item/index')
	),

	// 添加数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'wow', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'w_', // 数据库表前缀

	'DB_CONFIG_3' => array( // 3区黑铁数据库配置
		'DB_TYPE'   => 'mysql', // 数据库类型
		'DB_HOST'   => 'localhost', // 服务器地址
		'DB_NAME'   => 'wow_3', // 数据库名
		'DB_USER'   => 'root', // 用户名
		'DB_PWD'    => '', // 密码
		'DB_PORT'   => 3306, // 端口
		'DB_PREFIX' => 'w_auction_house_darkiron_' // 数据库表前缀
		),

	/* 数据缓存设置 */
    'DATA_CACHE_TIME'       => 0,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   => false,   // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK'      => false,   // 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'     => '',     // 缓存前缀
    'DATA_CACHE_TYPE'       => 'Memcache',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       => TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'     => false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       => 1,        // 子目录缓存级别


    'MEMCACHE_HOST'         => 'localhost', // Memcache缓存服务器地址
    'MEMCACHE_PORT'         => 11211 // Memcache缓存服务器端口

);
?>