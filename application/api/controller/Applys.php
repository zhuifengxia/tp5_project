<?php
/**
 * 申请合作相关接口
 * 作者：刘单风
 * 创建时间：2019-04-27
 * 版权：刘单风
 */
namespace app\api\controller;
use app\common\Codes;
use think\Controller;

use app\common\model\Applys as ApplysModel;

class Applys extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine apply 申请合作
     */


    /**
     * @api {get} /api/apply/list 申请合作列表数据
     * @apiName applyList
     * @apiGroup apply
     * @apiDescription 前台提交过来的申请合作的列表数据
     * @apiVersion 1.0.0
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccess {int} id 申请合作id
     * @apiSuccess {string} user_name 姓名
     * @apiSuccess {string} user_city 所在城市
     * @apiSuccess {string} user_phone 手机号
     * @apiSuccess {string} user_company 公司名
     * @apiSuccess {string} user_tel 电话
     * @apiSuccess {string} user_email 邮箱
     * @apiSuccess {string} user_wechat 微信号
     * @apiSuccess {string} company_core 公司核心业务
     * @apiSuccess {string} company_brand 公司经营的品牌和市场状况
     * @apiSuccess {string} basic_require 合作要求
     * @apiSuccess {int} create_time 发布时间
     * @apiSuccess {int} update_time 更新时间
     *
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": [{
        "id": 1,
        "user_name": "momo",
        "user_city": "江苏无信息",
        "user_phone": "1111111",
        "user_company": "阿里巴巴",
        "user_tel": "12121121",
        "user_email": "ali@163.com",
        "user_wechat": "alibaba",
        "company_core": "品牌品牌",
        "company_brand": "测试测试",
        "basic_require": "测试测试",
        "create_time": 1556349437,
        "update_time": 1556349437
        }]
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
    public function applyList()
    {
        $data = ApplysModel::select();
        return respondApi($data);
    }

    /**
     * @api {post} /api/apply/delete 删除申请合作
     * @apiName applyDelete
     * @apiGroup apply
     * @apiDescription 删除某个申请合作的数据
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id 申请合作id
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
        "status": 1004,
        "msg": "参数错误",
        "data": []
        }
     * @apiError 0 请求成功
     * @apiError -1 请求失败
     * @apiError 1004 缺少参数
     */
    public function applyDelete()
    {
        $id = input('id', 0);
        if ($id) {
            ApplysModel::where('id', $id)
                ->delete();
            return respondApi();
        } else {
            //必须传id
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
    }

    /**
     * @api {post} /api/apply/update 新增/更新申请合作
     * @apiName applyUpdate
     * @apiGroup apply
     * @apiDescription 新增或者更新申请合作信息
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id [可选]申请合作id,编辑传id
     * @apiParam {string} name 姓名
     * @apiParam {string} city 所在城市
     * @apiParam {string} phone 手机号
     * @apiParam {string} company 公司名
     * @apiParam {string} tel 电话
     * @apiParam {string} email 邮箱
     * @apiParam {string} wechat 微信号
     * @apiParam {string} core 公司核心业务
     * @apiParam {string} brand 公司经营的品牌和市场状况
     * @apiParam {string} require 合作基本要求
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
     * @apiError 1004 缺少参数
     */
    public function applyUpdate()
    {
        $id = input('id', 0);
        $name = input('name', '');
        $city = input('city', '');
        $phone = input('phone', '');
        $company = input('company', '');
        $tel = input('tel', '');
        $email = input('email', '');
        $wechat = input('wechat', '');
        $core = input('core', '');
        $brand = input('brand', '');
        $require = input('require', '');
        if (empty($name) || empty($phone)) {
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
        $data = [
            'user_name' => $name,
            'user_city' => $city,
            'user_phone' => $phone,
            'user_company' => $company,
            'user_tel' => $tel,
            'user_email' => $email,
            'user_wechat' => $wechat,
            'company_core' => $core,
            'company_brand' => $brand,
            'basic_require' => $require,
            'update_time' => time()
        ];
        if (empty($id)) {
            $data['create_time'] = time();
            ApplysModel::create($data);
        } else {
            ApplysModel::where('id', $id)
                ->update($data);
        }
        return respondApi();
    }
}
