<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

//新闻资讯相关
Route::group('api/news',[
    //新闻资讯列表数据
    'list/[:newstype]'=>['api/News/newsList',['method' => 'get']],
    //删除资讯
    'delete'=>['api/News/newsDelete',['method' => 'post']],
    //修改资讯/新增资讯
    'update'=>['api/News/newsUpdate',['method' => 'post']],
]);

//申请合作相关
Route::group('api/apply',[
    //列表数据
    'list'=>['api/Applys/applyList',['method' => 'get']],
    //删除
    'delete'=>['api/Applys/applyDelete',['method' => 'post']],
    //修改/新增
    'update'=>['api/Applys/applyUpdate',['method' => 'post']],
]);

//品牌/客户合作相关
Route::group('api/cooperate',[
    //列表数据
    'list/[:datatype]'=>['api/Cooperate/cooperateList',['method' => 'get']],
    //删除
    'delete'=>['api/Cooperate/cooperateDelete',['method' => 'post']],
    //修改/新增
    'update'=>['api/Cooperate/cooperateUpdate',['method' => 'post']],
]);


//项目案例相关
Route::group('api/case',[
    //列表数据
    'list/[:datatype]'=>['api/Cases/caseList',['method' => 'get']],
    //删除
    'delete'=>['api/Cases/caseDelete',['method' => 'post']],
    //修改/新增
    'update'=>['api/Cases/caseUpdate',['method' => 'post']],
]);


//人才招聘相关
Route::group('api/job',[
    //列表数据
    'list'=>['api/Jobs/jobList',['method' => 'get']],
    //删除
    'delete'=>['api/Jobs/jobDelete',['method' => 'post']],
    //修改/新增
    'update'=>['api/Jobs/jobUpdate',['method' => 'post']],
    //发送简历
    'send'=>['api/Jobs/sendResume',['method' => 'post']],
]);

//投递简历相关
Route::group('api/resume',[
    //列表数据
    'list'=>['api/Resume/resumeList',['method' => 'get']],
    //删除
    'delete'=>['api/Resume/resumeDelete',['method' => 'post']],
    //修改/新增
    'update'=>['api/Resume/resumeUpdate',['method' => 'post']],
]);


return [

];
