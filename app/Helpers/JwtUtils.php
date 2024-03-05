<?php
// +----------------------------------------------------------------------
// | Author: 艾迪  173970984@qq.com
// +----------------------------------------------------------------------
namespace App\Helpers;


use Illuminate\Support\Facades\Request;

/**
 * Jwt工具类
 * @author 艾迪
 * @since 2021/7/8
 * Class JwtUtils
 * @package App\Helpers
 */
class JwtUtils
{

    /**
     * 获取用户ID
     * @return bool|mixed
     * @since 2021/7/8
     * @author 艾迪
     */
    public static function getUserId()
    {
        // 解析Token获取用户ID
        $token = Request::header("Authorization");
        $token = str_replace("Bearer ", null, $token);
        // JWT解密token
        $jwt = new Jwt();
        $userId = $jwt->verifyToken($token);
        return $userId;
    }

}
