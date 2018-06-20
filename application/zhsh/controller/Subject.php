<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2018/5/22
 * Time: 10:03
 */

namespace app\zhsh\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;

class Subject extends Controller
{
    public function upload($name){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.$name.DS);
            //$info = $file->move('zhsh/'.$name.'/');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                $ext=$info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $path=$info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $fname=$info->getFilename();
                return [$path,$fname];
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    /**添加专题页面展示
     * @return \think\response\Redirect|\think\response\View|void
     */
    public function addsub()
    {
        $sub=Db::name('subject')->select();
        return view('',['sub'=>$sub]);
    }

    /**新增专题
     * @return \think\response\Redirect|\think\response\View|void
     */
    public function enrollAdd()
    {
        if (!Session::has("userKey")){
            return redirect("index/index");
        }
        //处理表单数据
        if (Request::instance()->isPost()){
            $post=Request::instance()->post();
            $post['enr_time']=time();//发布时间
            $post['enr_content']=htmlspecialchars($post['enr_content']);

            //dump($post);die;
            $res=model('sub_enroll')->allowField(true)->save($post);
            $mes = $res ? "<script>alert('已添加')</script>" : "<script>alert('添加失败')</script>";

            $sub=Db::name('subject')->select();

            return view("addsub",['sub'=>$sub,"msg"=>$mes]);
        }
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
     * 编辑专题
     * 区分专题分类
     */
    public function subdetail()
    {
        $id=Request::instance()->param('eid');
        $html=Request::instance()->param('data');
        //dump($html);die;

        $sub=Db::table('zs_sub_enroll')->alias('e')
            ->join('subject s','e.sub_id=s.sub_id')
            //->join('file f','f.enr_id=e.enr_id')
            ->find($id);
        $sub['file']=model('file')->where('enr_id',$id)->field('file_id,file_path')->select()->toArray();
        //dump($sub);die;
        $sub['enr_content']=htmlspecialchars_decode($sub['enr_content']);
        return view('',['sub'=>$sub,'html'=>$html]);
    }
    function upFiles($name) {
        $typename = $name;
        $imgs = request()->file();
        if ($imgs){//判断有上传文件时
            //$path = ROOT_PATH.DS."public".DS."upload".DS.$typename.DS;
            $path = ROOT_PATH."public".DS."uploads".DS.$typename.DS;
            $pam = [];
            foreach ($imgs as $k=>$h){
                if (is_array($h)){
                    foreach ($h as $c=>$v){
                        $info = $h[$c]->move($path);
                        if ($info){
                            $pam[$c] = str_replace("\\","/",$typename.DS.$info->getSaveName());
                        }
                    }
                }else{
                    $info = $imgs[$k]->move($path);
                    if ($info){
                        $path_d = str_replace("\\","/",$typename.DS.$info->getSaveName());
                    }
                }
            }
            $msg = json_encode(["success"=>true,"file_path"=>"/uploads/$path_d"]);
//        file_put_contents("D:/log.txt","$msg \n  ".\request()->isAjax(),FILE_APPEND);
            //die($msg);
            return $msg;
        }

    }
    public function edit()
    {
        $post=Request::instance()->post();
        $post['enr_time']=time();//发布时间
        $post['enr_content']=htmlspecialchars($post['enr_content']);

//        //上传文件处理
//        $file=$this->upFiles($post['base']);
//        $f['file_path']=json_decode($file)['file_path'];
//        $f['sub_id']=$post['sub_id'];
//        $f['enr_id']=$post['enr_id'];
//        $s=model('file')->allowField(true)->save($f);

        //dump($post);die;
        $res=model('sub_enroll')->allowField(true)->save($post,['enr_id'=>$post['enr_id']]);

        $this->assign('html',$post['base']);
        return redirect($post['base']);

    }
    /**招生简章列表
     * @return \think\response\View
     */
    public function zlist()
    {
        $action = Request::instance()->action();
        $list = model("sub_enroll")->where("sub_id","5")->paginate();
        return view("",["list"=>$list,'ac'=>$action]);
    }

    /**招生行程列表
     * @return \think\response\View
     */
    public function slist()
    {
        $action = Request::instance()->action();
        $list = model("sub_enroll")->where("sub_id","6")->paginate();
        return view("",["list"=>$list,'ac'=>$action]);
    }
    /**录取查询列表
     * @return \think\response\View
     */
    public function sclist()
    {
        $action = Request::instance()->action();
        $list = model("sub_enroll")->where("sub_id","1")->paginate();
        return view("",["list"=>$list,'ac'=>$action]);
    }
}