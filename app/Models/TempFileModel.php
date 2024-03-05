<?php
// +----------------------------------------------------------------------
// | Author: 艾迪 173970984@qq.com
// +----------------------------------------------------------------------
namespace App\Models;

use App\Helpers\Jwt;
use App\Helpers\JwtUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 * 临时文件管理
 * Class TempFileModel
 * @package App\Models
 */
class TempFileModel extends  Model
{
    // 设置数据表
    protected $table = "temp_file";
    // 创建时间
    const CREATED_AT = 'create_time';
    // 更新时间
    const UPDATED_AT = 'update_time';
    // // 删除时间
    // const DELETED_AT = 'delete_time';
    // 默认使用时间戳戳功能
    public $timestamps = true;
    // 时间
    public $time;

    public  function edit($data){

       return  $this->insertGetId($data);

    }

}
