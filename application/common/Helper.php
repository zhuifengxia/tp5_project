<?php

namespace app\common;

/**
 * 静态调用系列函数.
 *
 *
 */
class Helper
{

    /**
     * 单个文件上传
     * @param $typelist 可以上传的文件类型
     * @param $file 文件内容
     * @param $filepath 文件上传路径
     * @param $viewpath 外部访问地址
     */
    public static function singlefileupload($typelist,$file,$filepath,$viewpath)
    {
        $resultpath = "";
        if (empty($typelist)) {
            //定义可以上传的文件类型
            $typelist = array("image/gif", "image/jpg", "image/jpeg", "image/png");
            $typecheck=in_array($file['type'], $typelist);
        }elseif($typelist==1){
            //任何文件类型
            $typecheck=1;
        }
        if (is_uploaded_file($file['tmp_name']) && $typecheck) {
            //获取文件扩展名
            $exten_name = pathinfo($file['name'], PATHINFO_EXTENSION);
            //重新命名图片名称
            $picname = Helper::custom_mt_uniqid() . "." . $exten_name;//重新命名文件名
            $fpath = $filepath;
            //路径是否存在，不存在则创建
            if (!is_dir($fpath)) mkdir($fpath, 0777);
            //调用move_uploaded_file（）函数，进行文件转移
            $path = $fpath . $picname;
            if (move_uploaded_file($file['tmp_name'], $path)) {
                $resultpath = $viewpath . $picname;
            }
        }
        return $resultpath;
    }

}
