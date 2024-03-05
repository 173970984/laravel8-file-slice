<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public  function __construct()
    {
        // 项目根目录
        defined('ROOT_PATH') or define('ROOT_PATH', base_path());

        // 文件上传目录
        defined('ATTACHMENT_PATH') or define('ATTACHMENT_PATH', base_path('public/uploads'));

        // 图片上传目录
        defined('IMG_PATH') or define('IMG_PATH', base_path('public/uploads/images'));

        // 临时存放目录
        defined('UPLOAD_TEMP_PATH') or define('UPLOAD_TEMP_PATH', ATTACHMENT_PATH . "/temp");

        // 定义普通图片域名
        defined('IMG_URL') or define('IMG_URL', env('IMG_URL'));

        // 数据表前缀
        defined('DB_PREFIX') or define('DB_PREFIX', DB::connection()->getTablePrefix());

        // 数据库名
        defined('DB_NAME') or define('DB_NAME', DB::connection()->getDatabaseName());


        // 系统全称
        define('SITE_NAME', env('SITE_NAME'));
        // 系统简称
        define('NICK_NAME', env('NICK_NAME'));
        // 系统版本号
        define('VERSION', env('VERSION'));


        define('MESSAGE_OK', '操作成功');
        define('MESSAGE_FAILED', '操作失败');
        define('MESSAGE_SYSTEM_ERROR', '系统繁忙，请稍后再试');
        define('MESSAGE_PARAMETER_MISSING', '参数丢失');
        define('MESSAGE_PARAMETER_ERROR', '参数错误');
        define('MESSAGE_PERMISSON_DENIED', '没有权限');
        define('MESSAGE_INTERNAL_ERROR', '服务器繁忙，请稍后再试');
        define('MESSAGE_NEEDLOGIN', '请登录');
    }
}
