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

namespace app\admin\controller;
use app\common\logic\File as LogicFile;

/**
 * 文件控制器
 */
class File extends AdminBase
{
    
    /**
     * 图片上传
     */
    public function pictureUpload()
    {
        
        $result = $this->logicFile->pictureUpload();

        return json($result);
    }
    
    /**
     * 文件上传
     */
    public function fileUpload()
    {
        
        $result = $this->logicFile->fileUpload();

        return json($result);
    }
    
    /**
     * 编辑器图片上传
     */
    public function editorPictureUpload()
    {
        
        $result = get_sington_object('fileLogic', LogicFile::class)->pictureUpload('imgFile');
        
        $data  = false === $result ? [RESULT_ERROR => DATA_NORMAL, RESULT_MESSAGE => '文件上传失败'] : [RESULT_ERROR => DATA_DISABLE, RESULT_URL => get_picture_url($result['id'])];
        
        return throw_response_exception($data);
    }
}
