<?php

namespace App\Http\Controllers;
use App\Models\TempFileModel;
use Illuminate\Http\Request;

class UploadController extends Controller
{


    /**
     * 上传前检测已上传了多少
     */
    function beforeUpload(){
        try{
            $params = \request()->all();
            $checkMap = [];
            $checkMap[] = ["file_hash","=",$params["file_hash"]];
            $checkMap[] = ["status","=",0];
            $tempModel = new TempFileModel();
            $checkRow  = $tempModel->where($checkMap)->count();
            return message(MESSAGE_OK, true,$checkRow);

        }catch (\Exception $e){
            return message("查询失败:".$e->getMessage(), false,[],108);

        }
    }

    /**
     * 文件上传
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        try{
            $params = \request()->all();
            //如果已经上传过就不继续上传了
            $checkMap = [];
            $checkMap[] = ["file_hash","=",$params["file_hash"]];
            $checkMap[] = ["chunk_index","=",$params["chunk_index"]];
            $checkMap[] = ["status","=",0];
            $tempModel = new TempFileModel();
            $checkRow  = $tempModel->where($checkMap)->first();
            if($checkRow){
                $checkRow=objToArray($checkRow);
                $data= [
                    "file_size"=>$params["file_size"],
                    "chunk_index"=>$params["chunk_index"],
                    "chunk_count"=>$params["chunk_count"],
                    "file_hash"=>$params["file_hash"],
                    "file_name"=>$params["file_size"],
                    "id"=>$checkRow["id"],
                ];

                return message(MESSAGE_OK, true,$data);
            }

            //文件上传
            $result = upload_blob($request);
            if (!$result['success']) {
                return message($result['msg'], false,[],107);

            }else{
                //添加数据库记录
                $saveData=[
                    "file_hash"=>$params["file_hash"],
                    "file_name"=>$params["file_name"],
                    "file_size"=>$params["file_size"],
                    "chunk_index"=>$params["chunk_index"],
                    "chunk_count"=>$params["chunk_count"],
                ];
                $id = $tempModel->edit($saveData);
                $result["data"]=[
                    "file_size"=>$params["file_size"],
                    "chunk_index"=>$params["chunk_index"],
                    "chunk_count"=>$params["chunk_count"],
                    "file_hash"=>$params["file_hash"],
                    "file_name"=>$params["file_size"],
                    "id"=>$id,
                ];
            }
            return message(MESSAGE_OK, true, $result["data"],0);
        }catch (\Exception $e){
            return message("上传失败:".$e->getMessage(), false,[],106);
        }


    }

    /**
     * 文件合并
     * @return array
     */
    function mergeFile(){
        try{
            //检查是否已经上传完整
            $params = \request()->all();
            if(!$params["file_hash"] ||
                !$params["file_size"] ||
                !$params["file_name"] ||
                !$params["chunk_count"] ){
                return  message("参数缺失",false,[],101);
            }
            $ext  =   pathinfo($params["file_name"]);
            $ext =isset ($ext["extension"])?$ext["extension"]:'';
            $notAllow  = ["php",'bin',"jar"];
            if(in_array($ext,$notAllow)){
                return  message("文件类型不允许",false,[],102);
            }

            //如果已经上传过就不继续上传了
            $checkMap = [];
            $checkMap[] = ["file_hash","=",$params["file_hash"]];
            $checkMap[] = ["status","=",0];
            $tempModel = new TempFileModel();
            $checkRow  = $tempModel->where($checkMap)->count();
            $path =ATTACHMENT_PATH."/temp/".$params["file_hash"];

            //片数和数据库的数量一致就进行合并
            if($checkRow == $params["chunk_count"]){
                $movePath = ATTACHMENT_PATH."/".date("Ymd");
                if (!file_exists($movePath)) {
                    mkdir($movePath, 0777, true);
                }

                $mergeFileName = $movePath."/".$params["file_hash"];
                if($ext){
                    $mergeFileName = $movePath."/".$params["file_hash"].".".$ext;
                }

                @unlink($mergeFileName);
                $handle=fopen($mergeFileName,"a+");
                if(!file_exists($mergeFileName)){
                    return  message("文件不存在，合并失败".$mergeFileName,false,[],103);
                }

//                $handle=fopen($mergeFileName,"a+");
                for($i=0; $i< $params["chunk_count"]; $i++){
//                    file_put_contents($mergeFileName, file_get_contents($path."/".$i), FILE_APPEND);
                    #当前分片数
                    #吧每个块的文件追加到 上传的文件中
                    fwrite($handle,file_get_contents($path."/".$i));
                }
                fclose($handle);
                $file_path =str_replace(ATTACHMENT_PATH, '', $mergeFileName) ;
                //删除临时数据库
                $tempModel->where($checkMap)->update(["status"=>1,"update_time"=>time()]);
                //删除临时文件
                #删除分片
                for($i=0; $i< $params["chunk_count"]; $i++){
                    #吧每个块的文件追加到 上传的文件中
                    @unlink($path."/".$i);
                }
                #删除临时目录
                @rmdir($path);
                return message(MESSAGE_OK, true,['url'=> env("APP_URL")."/uploads".$file_path,"path"=>$file_path]);
            }else {
                return  message("文件不完整合并失败",false,[],104);
            }
        }catch (\Exception $e){
            return message("合并失败:".$e->getMessage()."  line:".$e->getLine(), false,[],105);
        }

    }




}
