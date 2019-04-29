<?php
/**
 * 新闻资讯相关接口
 * 作者：刘单风
 * 创建时间：2019-04-27
 * 版权：刘单风
 */
namespace app\api\controller;
use app\common\Codes;
use app\common\Helper;
use think\Controller;

use app\common\model\News as NewsModel;

class News extends Controller
{
    /**
     * 定义一个变量 用于apiGroup 因为不支持直接输入中文
     * @apiDefine news 新闻中心
     */


    /**
     * @api {get} /api/news/list/[:newstype] 资讯文章列表
     * @apiName newsList
     * @apiGroup news
     * @apiDescription 新闻资讯列表数据
     * @apiVersion 1.0.0
     *
     * @apiParam {int} newstype 新闻类型 0媒体报道；1公司活动；2超希资讯
     *
     * @apiSuccess {int} status 状态值
     * @apiSuccess {string} msg 状态描述
     * @apiSuccess {json} data 数据集合
     * @apiSuccess {int} id 资讯id
     * @apiSuccess {string} news_title 资讯标题
     * @apiSuccess {string} news_info 资讯内容
     * @apiSuccess {string} index_img 封面图地址
     * @apiSuccess {string} news_type 资讯类型0媒体报道；1公司活动；2超希资讯
     * @apiSuccess {int} create_time 发布时间
     * @apiSuccess {int} update_time 更新时间
     *
     * @apiSuccessExample Success-Response:
     * {
        "status": 0,
        "msg": "操作成功",
        "data": [{
        "id": 1,
        "news_title": "新闻资讯测试",
        "news_info": "内容内容",
        "index_img": "http://baidu.com/aaa.jpg",
        "news_type": 0,
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
    public function newsList($newstype=0)
    {
        $news = NewsModel::where('news_type', $newstype)
            ->select();
        return respondApi($news);
    }

    /**
     * @api {post} /api/news/delete 删除资讯
     * @apiName newsDelete
     * @apiGroup news
     * @apiDescription 删除某篇资讯文章
     * @apiVersion 1.0.0
     *
     * @apiParam {int} newsid 资讯文章id
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
    public function newsDelete()
    {
        $newsid = input('newsid', 0);
        if ($newsid) {
            NewsModel::where('id', $newsid)
                ->delete();
            return respondApi();
        } else {
            //必须传新闻id
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }
    }

    /**
     * @api {post} /api/news/update 新增/更新资讯
     * @apiName newsUpdate
     * @apiGroup news
     * @apiDescription 新增资讯或者更新资讯
     * @apiVersion 1.0.0
     *
     * @apiParam {int} id [可选]新闻id,编辑资讯传id
     * @apiParam {string} title 资讯标题
     * @apiParam {string} info 资讯内容
     * @apiParam {file} indeximg 资讯封面图
     * @apiParam {int} type 资讯分类0媒体报道；1公司活动；2超希资讯
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
    public function newsUpdate()
    {
        $id = input('id', 0);
        $title = input('title', '');
        $info = input('info', '');
        $type = input('type', 0);
        if (empty($title) || empty($info)) {
            $status = Codes::PARAM_ERR;
            $msg = Codes::get($status);
            return respondApi([], $status, $msg);
        }


        $filename="";
        if($_FILES['indeximg']){
            //文件上传目录
            $filepath = Env::get('ROOT_PATH') . 'public/static/upload/' . date('Y-m-d') . "/";
            //上传文件返回文件名
            $filename = Helper::singlefileupload('', $_FILES['indeximg'], $filepath, '/static/upload/' . date('Y-m-d'));
        }

        $data = [
            'news_title' => $title,
            'news_info' => $info,
            'news_type' => $type,
            'index_img' => $filename,
            'update_time' => time()
        ];
        if (empty($id)) {
            $data['create_time'] = time();
            NewsModel::create($data);
        } else {
            NewsModel::where('id', $id)
                ->update($data);
        }
        return respondApi();
    }
}
