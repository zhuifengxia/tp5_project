<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if (!function_exists('respondApi')) {
    //接口统一回复
    function respondApi($data=[],$status='',$msg='')
    {
        $status = $status ?: \app\common\Codes::ACTION_SUC;
        $msg = $msg ?: \app\common\Codes::get(\app\common\Codes::ACTION_SUC);
        $arr = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];

        return json($arr);
    }
}
