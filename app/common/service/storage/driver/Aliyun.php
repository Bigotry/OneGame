<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\common\service\storage\driver;

use app\common\service\storage\Driver;
use app\common\service\Storage;
use OSS\OssClient;

/**
 * 阿里云OSS
 */
class Aliyun extends Storage implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '阿里云OSS驱动', 'driver_class' => 'Aliyun', 'driver_describe' => '阿里云存储', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['ak_id' => '阿里云accessKeyId', 'ak_secret' => '阿里云accessKeySecret', 'bucket_name' => '阿里云bucket', 'endpoint' => '阿里云endpoint'];
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Aliyun');
    }
    
    /**
     * 上传图片
     */
    public function uploadPicture($file_id = 0)
    {

        $config = $this->config();
        
        $oss = new OssClient($config['ak_id'], $config['ak_secret'], $config['endpoint']);

        $info = $this->modelPicture->getInfo(['id' => $file_id]);
        
        $path_arr = explode(SYS_DS_PROS, $info['path']); 
  
        $file_path = PATH_PICTURE . $path_arr[0] . DS . $path_arr[1];
        
        $save_path = 'upload' . SYS_DS_PROS . 'picture' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . $path_arr[1];
        
        $result = $oss->uploadFile($config['bucket_name'], $save_path, $file_path);
        
        if (empty($result['info']['url'])) {
            
            return false;
        }
        
        $thumb_file_path = PATH_PICTURE . $path_arr[0] . DS . 'thumb' . DS;
        
        $thumb_save_path = 'upload' . SYS_DS_PROS . 'picture' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . 'thumb' . SYS_DS_PROS;
        
        $oss->uploadFile($config['bucket_name'], $thumb_save_path . 'small_'    . $path_arr[1], $thumb_file_path . 'small_'     . $path_arr[1]);
        $oss->uploadFile($config['bucket_name'], $thumb_save_path . 'medium_'   . $path_arr[1], $thumb_file_path . 'medium_'    . $path_arr[1]);
        $oss->uploadFile($config['bucket_name'], $thumb_save_path . 'big_'      . $path_arr[1], $thumb_file_path . 'big_'       . $path_arr[1]);
        
        return $result['info']['url'];
    }
    
    /**
     * 上传文件
     */
    public function uploadFile($file_id = 0)
    {
        
        $config = $this->config();
        
        $oss = new OssClient($config['ak_id'], $config['ak_secret'], $config['endpoint']);

        $info = $this->modelFile->getInfo(['id' => $file_id]);
        
        $path_arr = explode(SYS_DS_PROS, $info['path']); 
        
        $file_path = PATH_FILE . $path_arr[0] . DS . $path_arr[1];
        
        $save_path = 'upload' . SYS_DS_PROS . 'file' . SYS_DS_PROS . $path_arr[0] . SYS_DS_PROS . $path_arr[1];
        
        $result = $oss->uploadFile($config['bucket_name'], $save_path, $file_path);
        
        if (empty($result['info']['url'])) {
            
            return false;
        }
        
        return $result['info']['url'];
    }
}
