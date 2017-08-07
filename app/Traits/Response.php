<?php
namespace App\Traits;
use App\Api\ApiCode;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/31
 * Time: 16:59
 */

Trait Response
{

    //状态吗
    protected $state = 200;

    //提示信息
    protected $msg   = "请求成功！";

    /**
     * 设置状态吗
     * @param $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * 获取状态吗
     * @return int
     */
    public function getState(){
        return $this->state;
    }

    /**
     * 设置提示信息
     * @param $msg
     * @return $this
     */
    public function setMsg($msg){
        $this->msg = $msg;
        return $this;
    }


    /**
     * 获取提示信息
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }


    /**
     * 成功
     */
    public function success($msg,$data)
    {
        return $this->setState(ApiCode::STATE_OK)
            ->setMsg($msg)
            ->rsp($data);
    }


    /**
     * 失败
     */
    public function fail($code = 404,$msg)
    {
        return $this->setState($code)
            ->setMsg($msg)
            ->rsp();
    }


    /**
     * 输出
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function rsp($data = [])
    {
        return response()->json([
            'state' =>$this->state,
            'msg'   =>$this->msg,
            'data'  =>$data
        ]);
    }


}