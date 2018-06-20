<?php
/**
 * 学校账号进行整体流程管理
 * User: Mr.lee
 * Date: 2018/3/13
 * Time: 12:02
 */

namespace app\zhsh\controller;

use think\Db;
use think\Request;
use think\Session;

class Setting
{

    /**
     * 学校院系管理列表
     */
    function orgList()
    {
        if (!Session::has("userKey")){
            return redirect("index/index");
        }

        //获取学校全部专业
        $list = model("organization")->order('org_id','desc')->paginate();
        return view("orgList",["list"=>$list]);
    }

    /**
     * 删除操作
     */
    function delete()
    {
        if (Request::instance()->isAjax()){
            if (!Session::has("userKey")){
                return ["code"=>100,"message"=>"登录超时"];
            }
            $data = Request::instance()->post();
            $base = $data["data"];
            $id = $data["id"];
            $res = Db::name($base)->delete($id);
            if ($res){
                return ["code"=>101];
            }else{
                return ["code"=>10235,"message"=>"操作失败，稍后重试"];
            }
        }
    }

    /**
     * 学校专业管理列表
     */
    function majorList(){
        if (!Session::has("userKey")){
            return redirect("index/index");
        }
        //专业列表
        $list = model("major")->alias("a")
            ->join("__ORGANIZATION__ s","a.org_id = s.org_id")
            ->field("a.*,s.org_name")
            ->paginate();
        return view("majorList",["list"=>$list]);
    }

    /**专业新增/修改
     * @return \think\response\Redirect|\think\response\View|void
     */
    public function majSave()
    {
        if (!Session::has("userKey")){
            return redirect("index/index");
        }
        $param=Request::instance()->param();
        if ($param['type']==='hj3n'){
            //编辑
            if (Request::instance()->isPost()){
                //处理提交数据
                $post=Request::instance()->post();
                //dump($post);die;
                //Ueditor内容处理，html转为字符实体
                $post['maj_desc']=htmlspecialchars($post['maj_desc']);

                $res=model('major')->allowField(true)->save($post,['maj_id'=>$post['maj_id']]);
                return redirect('majorList');
            }
            //展示院系信息
            $maj=model('major')->find($param['mid']);
            $org=model('organization')->field('org_id,org_name')->select();
            return view('majdetail',['org'=>$org,'maj'=>$maj]);
        }
        if ($param['type']==='kha4'){
            //新增
            if (Request::instance()->isPost()){
                //处理提交数据
                $post=Request::instance()->post();
                //dump($post);die;
                //Ueditor内容处理，html转为字符实体
                $post['maj_desc']=htmlspecialchars($post['maj_desc']);

                $res=model('major')->allowField(true)->save($post);
                return redirect('majorList');
            }
            //展示院系信息
            $org=model('organization')->field('org_id,org_name')->select();
            return view('addmaj',['org'=>$org]);
        }

    }
    /**
     * 执行院系新增/修改
     */
    function orgSave()
    {
        if (!Session::has("userKey")){
            return redirect("index/index");
        }
        $param=Request::instance()->param();
        if ($param['type']==='bi3k'){
            //编辑
            if (Request::instance()->isPost()){
                //处理提交数据
                $post=Request::instance()->post();
                //上传图片处理
                if (!empty($_FILES['org_logo']['tmp_name'])){
                    $post['org_logo'] =base64_encode(file_get_contents($_FILES['org_logo']['tmp_name']));
                    $post['img_type'] = $_FILES['org_logo']['type'];//'image/jpeg'
                    $post['org_logo']='data:'.$post['img_type'].';base64,'.$post['org_logo'];
                }
                //Ueditor内容处理，html转为字符实体
                $post['org_desc']=htmlspecialchars($post['org_desc']);

                $res=model('organization')->allowField(true)->save($post,['org_id'=>$post['org_id']]);
                return redirect('orglist');
            }
            //展示编辑的数据
            $info = model('organization')->find($param['oid']);
            return view('orgdetail',['info'=>$info]);
        }
        if ($param['type']==='s46s'){
            //新增
            if (Request::instance()->isPost()){
                $post=Request::instance()->post();

                $post['org_logo'] =base64_encode(file_get_contents($_FILES['org_logo']['tmp_name']));
                $post['img_type'] = $_FILES['org_logo']['type'];//'image/jpeg'
                $post['org_logo']='data:'.$post['img_type'].';base64,'.$post['org_logo'];

                $post['org_desc']=htmlspecialchars($post['org_desc']);
                $res=model('organization')->allowField(true)->save($post);
                return redirect('orglist');
            }
            return view('addorg');
        }
    }




















}