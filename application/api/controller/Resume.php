<?php
/**
 * 投递简历相关接口
 * 作者：刘单风
 * 创建时间：2019-04-28
 * 版权：刘单风
 */
namespace app\api\controller;
use app\common\Codes;
use app\common\Helper;
use think\Controller;

use app\common\model\Resume as ResumeModel;
use think\facade\Env;

class Resume extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine resume 投递简历
     */


    /**
     * @api {get} /api/resume/list 简历列表
     * @apiName resumeList
     * @apiGroup resume
     * @apiDescription 投递的简历列表数据
     * @apiVersion 1.0.0
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccess {int} id 简历id
     * @apiSuccess {int} job_id 招聘id
     * @apiSuccess {string} job_name 招聘标题
     * @apiSuccess {string} resume_file 简历文件地址
     * @apiSuccess {int} create_time 发布时间
     * @apiSuccess {int} update_time 更新时间
     *
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": [{
        "id": 1,
        "job_id": 1,
        "job_name": "核电采购工程师（高底薪 高提成 好福利）10K-15K",
        "resume_file": "http://baidu.com/file.docx",
        "create_time": 1556419505,
        "update_time": 1556419505
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
    public function resumeList()
    {
        $data = ResumeModel::select();
        return respondApi($data);
    }

    /**
     * @api {post} /api/resume/delete 删除简历
     * @apiName resumeDelete
     * @apiGroup resume
     * @apiDescription 删除某个投递简历
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id 简历id
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
    public function resumeDelete()
    {
        $id = input('id', 0);
        if ($id) {
            ResumeModel::where('id', $id)
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
     * @api {post} /api/resume/update 新增/更新简历
     * @apiName resumeUpdate
     * @apiGroup resume
     * @apiDescription 新增或者更新投递简历的信息
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id [可选]简历id,编辑传id
     * @apiParam {string} name 招聘标题
     * @apiParam {string} jobid 招聘id
     * @apiParam {file} resume 简历文件
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
    public function resumeUpdate()
    {
        $id = input('id', 0);
        $name = input('name', '');
        $jobid = input('jobid', '');
        if (empty($name)) {
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }


        $filename="";
        if($_FILES['resume']){
            //文件上传目录
            $filepath = Env::get('ROOT_PATH') . 'public/static/upload/' . date('Y-m-d') . "/";
            //上传文件返回文件名
            $filename = Helper::singlefileupload(1, $_FILES['resume'], $filepath, '/static/upload/' . date('Y-m-d'));
        }

        $data = [
            'job_name' => $name,
            'job_id' => $jobid,
            'resume_file' => $filename,
            'update_time' => time()
        ];
        if (empty($id)) {
            $data['create_time'] = time();
            ResumeModel::create($data);
        } else {
            ResumeModel::where('id', $id)
                ->update($data);
        }
        return respondApi();
    }
}
