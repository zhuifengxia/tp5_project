<?php
/**
 * Description: 用户相关接口.
 * Author: momo
 * Date: 2019-05-20 18:16
 * Copyright: momo
 */


namespace app\api\controller;

use app\common\Codes;
use app\common\model\Users as UserModel;
use think\Controller;

class Users extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine user 用户中心
     */

    /**
     * @api {get} /api/user/login 登录接口
     * @apiName Login
     * @apiGroup user
     * @apiDescription 用户登录
     * @apiVersion 1.0.0
     *
     * @apiParam {string} username 用户名
     * @apiParam {string} userpwd 用户密码
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": []
        }
     * @apiErrorExample {json} Error-Response:
     * {
        "status": -1,
        "msg": "请求失败",
        "data": []
        }
     * @apiError 0 请求成功
     * @apiError -1 请求失败
     */
    public function Login()
    {
        $username=input('username','');
        $userpwd=input('userpwd','');
        if(empty($username)||empty($userpwd)){
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
        $data=UserModel::where(['user_name'=>$username,'user_pwd'=>md5($userpwd)])
            ->find();
        if($data){
            $status = Codes::ACTION_SUC;
            $msg = Codes::get($status);
            return respondApi($data, $status, $msg);
        }else{
            $status = Codes::ACTION_FAL;
            $msg = Codes::get($status);
            return respondApi($data, $status, $msg);
        }
    }

    /**
     * @api {get} /api/user/register 注册接口
     * @apiName Register
     * @apiGroup user
     * @apiDescription 注册接口
     * @apiVersion 1.0.0
     *
     * @apiParam {string} username 用户名
     * @apiParam {string} userpwd 用户密码
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": []
        }
     * @apiErrorExample {json} Error-Response:
     * {
        "status": -1,
        "msg": "请求失败",
        "data": []
        }
     * @apiError 0 请求成功
     * @apiError -1 请求失败
     */
    public function Register()
    {
        $username = input('username', '');
        $userpwd = input('userpwd', '');
        if (empty($username) || empty($userpwd)) {
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
        $data = [
            'user_name' => $username,
            'user_pwd' => md5($userpwd)
        ];
        UserModel::create($data);
        $status = Codes::ACTION_SUC;
        $msg = Codes::get($status);
        return respondApi([], $status, $msg);
    }
}
