<?php
//配置文件
return [
    ///设置返回值类型
//    'default_return_type'=>'json',
    'default_ajax_return'    => 'json',
    'app_trace'              => true,
    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__CSS__' => '/static/base',
        '__IMG__' => '/static/base/img',
        '__JS__' => '/static/base/js',
    ],

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think_2zs',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
        ///过期时间
        'expire' =>3600
    ],
    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => 'zspt',
        // cookie 保存时间
        'expire'    => 36000,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => 'true',
        // 是否使用 setcookie
        'setcookie' => true,
    ],
    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 8,
    ],

];