<?php
/**
 * 项目案例相关
 * 作者：刘单风
 * 创建时间：2019-04-27
 * 版权：刘单风
 */
namespace app\api\controller;
use app\common\Codes;
use app\common\Helper;
use think\Controller;

use app\common\model\Cases as CasesModel;
use think\facade\Env;

class Cases extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine case 项目案例
     */


    /**
     * @api {get} /api/case/list/[:typeid] 项目案例数据
     * @apiName caseList
     * @apiGroup case
     * @apiDescription 获取项目案例数据
     * @apiVersion 1.0.0
     *
     * @apiParam {int} typeid 0半导体；1核焊级；2尿素；3脱硫脱硝；4环保
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccess {int} id 合作id
     * @apiSuccess {string} type_name 案例标题
     * @apiSuccess {string} img_url 图片地址
     * @apiSuccess {string} data_info 图文内容
     * @apiSuccess {int} type_id 0半导体；1核焊级；2尿素；3脱硫脱硝；4环保
     * @apiSuccess {int} create_time 发布时间
     * @apiSuccess {int} update_time 更新时间
     *
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": [{
        "id": 1,
        "type_name": "aaa",
        "img_url": null,
        "type_id": "0",
        "data_info": "介绍介绍介绍",
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
    public function caseList($typeid=0)
    {
        $data = CasesModel::where('type_id',$typeid)
            ->select();
        return respondApi($data);
    }

    /**
     * @api {post} /api/case/delete 删除项目案例
     * @apiName caseDelete
     * @apiGroup case
     * @apiDescription 删除某个项目案例
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id 案例id
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
    public function caseDelete()
    {
        $id = input('id', 0);
        if ($id) {
            CasesModel::where('id', $id)
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
     * @api {post} /api/case/update 新增/更新项目案例
     * @apiName caseUpdate
     * @apiGroup case
     * @apiDescription 新增或者更新项目案例
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id [可选]案例id,编辑传id
     * @apiParam {string} typename 案例标题
     * @apiParam {file} img 案例图片【1核焊级；2尿素此参数作为封面图】
     * @apiParam {file} datainfo 案例信息【0半导体；3脱硫脱硝；4环保无此信息】
     * @apiParam {int} typeid 0半导体；1核焊级；2尿素；3脱硫脱硝；4环保
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
    public function caseUpdate()
    {
        $id = input('id', 0);
        $name = input('typename', '');
        $info = input('datainfo', 0);
        $type = input('typeid', 0);
        $filename="";
        if($_FILES['img']){
            //文件上传目录
            $filepath = Env::get('ROOT_PATH') . 'public/static/upload/' . date('Ymd') . "/";
            //上传文件返回文件名
            $filename = Helper::singlefileupload('', $_FILES['img'], $filepath, '/static/upload/' . date('Ymd'.'/'));
        }
        $data = [
            'type_name' => $name,
            'img_url' => $filename,
            'data_info' => $info,
            'type_id' => $type,
            'update_time' => time()
        ];
        if (empty($id)) {
            $data['create_time'] = time();
            CasesModel::create($data);
        } else {
            CasesModel::where('id', $id)
                ->update($data);
        }
        return respondApi();
    }
}
