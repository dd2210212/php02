<?php
namespace Admin\Controller;
use Common\Tools\BackController;
class AdminController extends BackController{
    public function login(){
        layout(false);
        if(IS_POST){
          $name=$_POST['admin_user'];
          $pwd=$_POST['admin_psd'];
          $manager=D('manager');
          $info=$manager->where(array('mg_name'=>$name,'mg_pwd'=>$pwd))->find();
          if($info!=null){
              session('admin_id',$info['mg_id']);
              session('admin_name',$info['mg_name']);
              //页面跳转
              $this->redirect('Index/index');
          }
          $this->error('用户名或密码错误',U('login'),1);
        }else{
            $this->display();
        }

    }

    public function loaout(){
        session(null);
        $this->redirect('login');
    }

    public function verify(){
        $cfg=array(
          'imageH'=>40,
          'imageW'=>100,
          'length'=>2,
          'fontttf'=>'4.ttf',
          'fontsize'=>'13',
        );
        $ver=new \Think\Verify($cfg);
        $ver->entry();
    }
    public function checkCode(){
        $code=I('get.code');
        $vry=new \Think\Verify();
        if($vry->check($code)){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>2));
        }
    }
public function showlist(){
    $bread = array(
        'first' => '权限管理',
        'second' => '权限修改',
        'linkTo' => array(
            '【添加管理员】', U('tianjia')
        ),
    );
    $this->assign('bread', $bread);

    $info = D('Manager')->alias('m')->join('LEFT JOIN __ROLE__ r on m.mg_role_id=r.role_id')->field('m.*,r.role_name')->select();
    //SELECT m.*,r.role_name FROM tp__manager m LEFT JOIN tp__role r on m.mg_role_id=r.role_id
    $this -> assign('info',$info);
    $this->display();
}
public function upd(){
    $model=D('Manager');
    if(IS_POST){
        $data=$model->create();
        if($model->save($data)){
            $this->success('修改管理员成功','showlist');
        }else{
            $this->error('修改管理员失败',U('upd', array('mg_id' => $data['mg_id'])), 1);
        }
    }else{
        //查询被修改的id
        $id=I('get.mg.id');
        $info=$model->find($id);
       $this->assign('info',$info);
        $bread = array(
            'first' => '管理员管理',
            'second' => '管理员修改',
            'linkTo' => array(
                '【返回】', U('showlist')
            ),
        );
        $this->assign('bread',$bread);
        $this->display();
    }
}
public function tianjia(){
    $model=D('Manager');
    if(IS_POST){
        $data=$model->create();
        if($model->add($data)){
            $this->success('添加管理员成功','showlist');
        }else{
            $this->error('添加管理员失败','tianjia');
        }
    }
    $id=I('get.mg_id');
    $info=$model->find($id);
    $this -> assign('info',$info);
    $bread = array(
        'first' => '管理员管理',
        'second' => '管理员添加',
        'linkTo' => array(
            '【返回】', U('showlist')
        ),
    );
    $this->assign('bread',$bread);
    $this->display();
}
public function del(){
    $id=I('get.mg_id');
    $data=D('Manager')->delete($id);
    if($data){
        echo json_encode(array('status'=>1));
    }else{
        echo json_encode(array('status'=>2));
    }
}

}