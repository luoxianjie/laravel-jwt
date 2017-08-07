<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');

// 接管路由
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Api\V1\Controllers'], function ($api) {

        $api->post('login/third', 'AuthController@thirdLogin');                                                 //第三方登录
        $api->post('login','AuthController@login');                                                             //登录
        $api->get('refresh','AuthController@refresh');                                                          //刷新token

        //获取token后才能访问的接口
        $api->group(['middleware' => ['jwt.auth']], function ($api) {

            $api->get('user', 'AuthController@user');                                                        //获取个人信息



        });
    });
});

