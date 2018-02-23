<?php
namespace Admin\Controller;
use Common\Tools\BackController;
class AuthController extends BackController{

    public function showlist()
    {
        $info = D('auth')->order('auth_path')->select();
        $bread = array(
            'first' => '权限管理',
            'second' => '权限列表',
            'linkTo' => array(
                '【添加权限】', U('tianjia')
            ),
        );
        $this->assign('bread', $bread);
        $this->assign('info', $info);
        $this->display();
    }
    public function tianjia(){
        $model=D('auth');
        if(IS_POST){
            $data=$model->create();
            if ($model->add($data)) {
                $this->success('权限添加成功', U('showlist'));
                exit;
            } else {
                $this->error('权限添加失败', U('tianjia'), '1');
            }

    }else{/************获取可供选取的上级权限***********/
            $pinfo=D('auth')->where(array('auth_level'=>array('in','0,1')))->order('auth_path')->select();
            $this->assign('pinfo',$pinfo);
            /************获取可供选取的上级权限***********/
            $bread = array(
                'first' => '权限管理',
                'second' => '权限添加',
                'linkTo' => array(
                    '【返回】', U('showlist')
                ),
            );
            $this->assign('bread', $bread);
            $this->display();
        }

    }
    public function del(){
        $id=I('get.auth_id');
        $model=D('auth');
        $res=$model->delete($id);
        if($res){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>2));
        }

    }
    public function upd(){
        $model=D('Auth');
        if(IS_POST){
         $data=$model->create();
         if($model->save($data)){
             $this->success('修改成功',U('showlist'));
         }else{
             $this->error('修改失败',U('upd'));
         }
        }else{
            $id=I('get.auth_id');
            //查询被修改的角色
            $info=$model->find($id);
            $this->assign('info',$info);
            $bread = array(
                'first' => '权限管理',
                'second' => '权限修改',
                'linkTo' => array(
                    '【返回】', U('showlist')
                ),
            );
            $this->assign('bread', $bread);
            $this->display();
        }
}

}