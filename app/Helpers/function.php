<?php
// +----------------------------------------------------------------------
// | Author: 艾迪 173970984@qq.com
// +----------------------------------------------------------------------
// 为方便系统核心模块升级，二次开发中需要用到的公共函数请写在此文件中，不允许修改系统核心公共函数common.php文件

//对象转数组
function objToArray($data)
{

    $data = json_encode($data);

    return json_decode($data, true);
}

function toJsonCode($arr)
{
    return json_encode($arr);
}

function p($arr)
{
    echo "<pre>";
    var_dump($arr);
    echo "</pre>";

}



//切片上传

if (!function_exists('upload_blob')) {

    /**
     * 上传图片
     * @param $request 网络请求
     * @param string $form_name 文件表单名
     * @return array 返回结果
     * @author 艾迪
     * @date 2019/5/23
     */
    function upload_blob($request, $form_name = 'file')
    {
        // 检测请求中是否包含name=$form_name的上传文件
        if (!$request->hasFile($form_name)) {
            return message("请上传文件", false);
        }
        // 文件对象
        $file = $request->file($form_name);

        // 判断图片上传是否错误
        if (!$file->isValid()) {
            // 文件上传失败
            return message("上传文件验证失败", false);
        }

        // 文件原名
        $original_name = $file->getClientOriginalName();
        // 文件扩展名(文件后缀)
        $ext = $file->getClientOriginalExtension();
        // 临时文件的绝对路径
        $real_path = $file->getRealPath();
        // 文件类型
        $type = $file->getClientMimeType();
        // 文件大小
        $size = $file->getSize();

        $params = request()->all();
        //没有后缀的才能上传
        if(!$ext){
            // 文件大小校验
            if ($size > 5 * 1024 * 1024) {
                return message("文件大小超过了5M", false);
            }

            // 文件路径
            $file_dir = UPLOAD_TEMP_PATH . "/" .$params['file_hash'];

            // 检测文件路径是否存在,不存在则创建
            if (!file_exists($file_dir)) {
                mkdir($file_dir, 0777, true);
            }

            // 文件名称 当前块数为名称
            $file_name = $params['chunk_index'];
            // 重命名保存
            $path = $file->move($file_dir, $file_name);
            // 文件临时路径
            $file_path = str_replace(ATTACHMENT_PATH, '', $file_dir) . '/' . $file_name;
            // 返回结果
            $result = [
                'img_original_name' => $original_name,
                'img_ext' => $ext,
                'img_real_path' => $real_path,
                'img_type' => $type,
                'img_size' => $size,
                'img_name' => $file_name,
                'img_path' => $file_path,
            ];
            return message(MESSAGE_OK, true, $result);
        }else{
            return message("文件格式不合法", false);
        }
    }
}

