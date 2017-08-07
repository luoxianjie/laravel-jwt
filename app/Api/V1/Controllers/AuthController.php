<?php

namespace App\Api\V1\Controllers;

use App\Api\ApiCode;
use App\Http\Controllers\Controller;
use App\Model\Third;
use App\Traits\Response;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use Response;

    /**
     * 正常登录
     * @param Request $request
     * @return token
     */
    public function login(Request $request)
    {
        //验证
        $validate = \Validator::make($request->all(),[
            'phone'     => 'required|string|mobile|max:255|exists:users',
            'password'  => 'required|string|min:6'
        ],[],[
            'phone'     => '手机号有错误',
            'password'  => '密码有误'
        ]);

        if($validate->fails()){
            return $this->fail(ApiCode::PARAM_ERROR,$validate->errors()->first());
        }

        //手动登录
        if(\Auth::attempt(['phone'=>$request->get('phone'),'password'=>$request->get('password')])){

            $token = JWTAuth::fromUser(Auth::user());

            return $this->success('登录成功!',['token'=>$token]);
        }else{

            return $this->fail(ApiCode::AUTH_FAIL,'登录失败!');
        }

    }


    /**
     * 第三方授权登录
     * @param Request $request
     * @return token
     */
    public function thirdLogin(Request $request)
    {
        $validate = \Validator::make($request->all(),[
            'openid'    => 'required',
        ],[],[
            'openid'    => '第三方用户唯一标志'
        ]);

        if($validate->fails()){
            return $this->fail(ApiCode::PARAM_ERROR,$validate->errors()->first());
        }

        //验证openid有效性

        //获取第三方表记录
        $third = Third::where(['openid' => $request->get('openid')])->first();

        //若不存在记录
        if (!$third) {

            //创建一个用户
            $user = User::create([
                'name'      => 'test',
                'phone'     => "135".rand(10000,99999).'888',
                'password'  => bcrypt('12345678')
            ]);

            //创建第三方表记录
            Third::create([
                'openid'    => $request->get('openid'),
                'uid'       => $user['id']
            ]);
        }else{

            //获取用户表记录
            $user  =  User::find($third['uid']);
        }

        $token = JWTAuth::fromUser($user);

        return $this->success('请求成功！',['token'=>$token]);
    }


    /**
     * 刷新token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $oldToken = JWTAuth::getToken();

        if(!$oldToken){
            return $this->fail(ApiCode::PARAM_ERROR,'您的token有误!');
        }

        $token = JWTAuth::refresh($oldToken);

        return $this->success('刷新成功!',['token'=>$token]);
    }


    /**
     * 获取用户的信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->fail(ApiCode::NOT_FOUND,'user_not_found');
            }
        } catch (TokenExpiredException $e) {
            return $this->fail($e->getStatusCode(),'token_expired');
        } catch (TokenInvalidException $e) {
            return $this->fail($e->getStatusCode(),'token_invalid');
        } catch (JWTException $e) {
            return $this->fail($e->getStatusCode(),'token_absent');
        }
        return $this->success('请求成功！',['user'=>$user]);
    }

}