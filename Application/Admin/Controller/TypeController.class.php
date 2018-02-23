<?php
namespace Admin\Controller;
use Common\Tools\BackController;
class TypeController extends BackController{

    public function showlist()
    {
        $info = D('Type')->select();
        $bread = array(
            'first' => '类型管理',
            'second' => '类型列表',
            'linkTo' => array(
                '【添加类型】', U('Type/tianjia')
            ),
        );
        $this->assign('bread', $bread);
        $this->assign('info', $info);
        $this->display();
    }

    public function tianjia()
    {
        $model = D('Type');
        if (IS_POST) {
            $data = $model->create();
            if ($model->add($data)) {
                $this->success('添加类型成功', U('showlist'), 1);
            } else {
                $this->error('添加类型失败', U('tianjia'), 1);
            }
        } else {//展示表单
            $bread = array(
                'first' => '角色管理',
                'second' => '商品添加',
                'linkTo' => array(
                    '【返回】', U('Type/showlist')
                ),
            );
            $this->assign('bread', $bread);
            $this->display();
        }
    }





    public function upd()
    {
        $model = D('role');
        if (IS_POST) {
            $data = $model->create();
            if ($model->save($data)) {
                $this->success('修改角色成功', u('showlist'), 1);
            } else
                $this->success('修改角色失败', u('upd',array('role_id'=>$data['role_id'])), 1);
        } else {
            $role_id = I('get.role_id');
            //查询被修改的角色id
            $info = $model->find($role_id);
            $bread = array(
                'first' => '角色管理',
                'second' => '角色修改',
                'linkTo' => array(
                    '【返回】', U('showlist')
                ),
            );
            $this->assign('bread', $bread);
            $this->assign('info', $info);
            $this->display();
        }

    }

    public function del()
    {
        $id = I('get.role_id');  //获得被删除商品的id信息
        $model = D('role');
        $res = $model->delete($id);
        if ($res) {
            echo json_encode(array('status' => 1)); //ok  99%
        } else {
            echo json_encode(array('status' => 2)); //fail 1%
        }
    }
}