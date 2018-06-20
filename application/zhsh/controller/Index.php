<?php
namespace app\zhsh\controller;

use app\index\model\School;
use think\Request;
use think\Session;

class Index
{
    public function index()
    {
        if (!Session::has("userKey")){
            return redirect("index/login");
        }
        return view();
    }

    /**
     * 渲染登录页面
     */
    function login(){
        return view("index/login");
    }

    /**
     * 处理用户登录操作[ajax]
     * base/js/action/sign-zy.js sign.js文件中的ajax
     */
    function signTo(){
        if (Request::instance()->isAjax()){
            $posts = Request::instance()->post();

            //根据用户登录信息获取对应的详情
            $post["ad_pass"] = md5($posts["user_pwd"]);
            $post["ad_name"] = trim($posts["user_name"]);
            $res = model("admin")->where($post)->find();
            //dump($res);die;
            if ($res){
                Session::set("userKey",$res['ad_id']);
                Session::set("userName",$res['ad_name']);
                return ["code"=>101,"message"=>"登录成功!"];
            }else{
                return ["code"=>10036,"message"=>"用户名或密码错误！"];
            }
        }
    }

    /**
     * 用户退出登录
     */
    function loginout(){
        Session::clear();
        return redirect('/zhsh');
    }
















}
