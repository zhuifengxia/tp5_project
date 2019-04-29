<?php
/**
 * 招聘相关接口
 * 作者：刘单风
 * 创建时间：2019-04-27
 * 版权：刘单风
 */
namespace app\api\controller;
use app\common\Codes;
use think\Controller;

use app\common\model\Jobs as JobsModel;

class Jobs extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine job 招聘中心
     */


    /**
     * @api {get} /api/job/list 招聘中心列表数据
     * @apiName jobList
     * @apiGroup job
     * @apiDescription 人才招聘列表数据
     * @apiVersion 1.0.0
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccess {int} id 招聘id
     * @apiSuccess {string} job_name 招聘标题
     * @apiSuccess {string} job_duty 岗位职责
     * @apiSuccess {string} job_welfare 薪资福利
     * @apiSuccess {string} job_qualification 任职资格
     * @apiSuccess {int} create_time 发布时间
     * @apiSuccess {int} update_time 更新时间
     *
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": [{
        "id": 1,
        "job_name": "核电采购工程师（高底薪 高提成 好福利）10K-15K\n",
        "job_duty": "1、负责核电客户的维护，了解客户的具体需求，明确客户需求产品的规格参数。\n\n2、开发、评审、管理供应商，并维护与其的关系。具体询比价的沟通。\n\n3、采购合同的签订，跟踪采购产品发货及到货日期。落实具体采购流程。\n\n4、完成采购主管安排的其它工作。\n\n5、积极与各部门进行沟通，较好的控制成本，创造公司最大利润。",
        "job_welfare": "1、本职位每周工作40小时（做五休二），薪资由底薪 提成构成，收入是开放式的。\n\n2、底薪，提成，奖金激励，确保每个员工的付出得到相应的回报。\n\n3、公司缴纳社会保险，并为所有员工购买公积金作为福利。\n\n4、公司每年组织员工旅游及各项文体活动，提供多方位的培训机会。\n\n5、员工享受带薪年假。\n\n6、公司为员工提供舒适的、福利很好的员工住宿条件。\n\n7、员工转正后享受每三个月有一次晋升、加薪机会欢迎励志于从事电力行业的优秀采购工程师和销售工程师们加入！",
        "job_qualification": "1、工科类本科及以上学历，机械、化工、电力、环保、材料等专业优先。\n\n2、有电力、核电、火电、化工油田等行业采购及销售相关经验者优先。\n\n3、熟悉采购流程，良好的沟通能力、谈判能力和成本意识。\n\n4、工作细致认真，责任心强，思维敏捷，具有较强的团队合作精神，英语能力强者优先考虑。\n\n5、有良好的职业道德和素养，能承受一定工作压力。",
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
    public function jobList()
    {
        $data = JobsModel::select();
        return respondApi($data);
    }

    /**
     * @api {post} /api/job/delete 删除招聘
     * @apiName jobDelete
     * @apiGroup job
     * @apiDescription 删除某个招聘信息
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id 招聘id
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
    public function jobDelete()
    {
        $id = input('id', 0);
        if ($id) {
            JobsModel::where('id', $id)
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
     * @api {post} /api/job/update 新增/更新招聘
     * @apiName jobUpdate
     * @apiGroup job
     * @apiDescription 新增或者更新招聘信息
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id [可选]招聘id,编辑传id
     * @apiParam {string} name 招聘标题
     * @apiParam {string} duty 岗位职责
     * @apiParam {string} welfare 薪资福利
     * @apiParam {string} qualification 任职资格
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
    public function jobUpdate()
    {
        $id = input('id', 0);
        $name = input('name', '');
        $duty = input('duty', '');
        $welfare = input('welfare', '');
        $qualification = input('qualification', '');
        if (empty($name) || empty($duty)) {
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
        $data = [
            'job_name' => $name,
            'job_duty' => $duty,
            'job_welfare' => $welfare,
            'job_qualification' => $qualification,
            'update_time' => time()
        ];
        if (empty($id)) {
            $data['create_time'] = time();
            JobsModel::create($data);
        } else {
            JobsModel::where('id', $id)
                ->update($data);
        }
        return respondApi();
    }
}
