<?php
/**
 * 品牌/客户合作相关
 * 作者：刘单风
 * 创建时间：2019-04-27
 * 版权：刘单风
 */
namespace app\api\controller;
use app\common\Codes;
use app\common\Helper;
use think\Controller;

use app\common\model\Cooperate as CooperateModel;
use think\facade\Env;

class Cooperate extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine cooperate 企业合作
     */


    /**
     * @api {get} /api/cooperate/list/[:datatype] 合作列表数据
     * @apiName cooperateList
     * @apiGroup cooperate
     * @apiDescription 获取品牌合作或者客户合作列表数据
     * @apiVersion 1.0.0
     *
     * @apiParam {int} datatype 0品牌合作；1客户合作
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccess {int} id 合作id
     * @apiSuccess {string} cooperate_name 合作名称
     * @apiSuccess {string} cooperate_img 合作图片
     * @apiSuccess {string} cooperate_info 介绍
     * @apiSuccess {int} data_type 0品牌合作（带图）；1客户合作（仅文字）
     * @apiSuccess {int} create_time 发布时间
     * @apiSuccess {int} update_time 更新时间
     *
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": [{
        "id": 1,
        "cooperate_name": "长城润滑油",
        "cooperate_img": null,
        "cooperate_info": "介绍介绍介绍",
        "data_type": 0,
        "create_time": 0,
        "update_time": 0
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
    public function cooperateList($datatype=0)
    {
        $data = CooperateModel::where('data_type',$datatype)
            ->select();
        return respondApi($data);
    }

    /**
     * @api {post} /api/cooperate/delete 删除合作信息
     * @apiName cooperateDelete
     * @apiGroup cooperate
     * @apiDescription 删除某个合作信息
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id 合作id
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
    public function cooperateDelete()
    {
        $id = input('id', 0);
        if ($id) {
            CooperateModel::where('id', $id)
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
     * @api {post} /api/cooperate/update 新增/更新合作信息
     * @apiName cooperateUpdate
     * @apiGroup cooperate
     * @apiDescription 新增或者更新合作信息
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id [可选]合作id,编辑传id
     * @apiParam {string} name 合作名称
     * @apiParam {string} info 合作介绍
     * @apiParam {file} img  [可选]品牌合作的时候上传图片
     * @apiParam {int} type 0品牌合作（带图）；1客户合作（仅文字）
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
    public function cooperateUpdate()
    {
        $id = input('id', 0);
        $name = input('name', '');
        $info = input('info', 0);
        $type = input('type', 0);
        if (empty($name)) {
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
        $filename="";
        if($_FILES['img']){
            //文件上传目录
            $filepath = Env::get('ROOT_PATH') . 'public/static/upload/' . date('Ymd') . "/";
            //上传文件返回文件名
            $filename = Helper::singlefileupload('', $_FILES['img'], $filepath, '/static/upload/' . date('Ymd'). "/");
        }
        $data = [
            'cooperate_name' => $name,
            'cooperate_img' => $filename,
            'cooperate_info' => $info,
            'data_type' => $type,
            'update_time' => time()
        ];
        if (empty($id)) {
            $data['create_time'] = time();
            CooperateModel::create($data);
        } else {
            CooperateModel::where('id', $id)
                ->update($data);
        }
        return respondApi();
    }
}
