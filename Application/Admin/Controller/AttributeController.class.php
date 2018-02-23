<?php

namespace Admin\Controller;

use Common\Tools\BackController;

class AttributeController extends BackController
{

    //列表展示
    function showlist(){
        $type_id = I('get.type_id'); //类型id
        //获得属性列表信息
        $info = D('Attribute')->where(array('type_id'=>$type_id))->select();

        /***获得类型信息，给模板实现下拉列表展示***/
        $typeinfo = D('Type')->select();
        $this -> assign('typeinfo',$typeinfo);
        /***获得类型信息，给模板实现下拉列表展示***/

        $bread = array(
            'first' => '属性管理',
            'second' => '属性列表',
            'linkTo' => array(
                '【添加属性】',U('tianjia')
            ),
        );
        $this -> assign('bread',$bread);

        $this -> assign('info',$info);
        $this -> display();
    }

    public function tianjia()
    {
        $model = D('Attribute');
        if (IS_POST) {
            $data = $model->create();
            if ($data) {
                if ($model->add($data)) {
                    $this->success('添加属性成功', U('Type/showlist'), 1);
                } else {
                    $this->error('添加属性失败', U('Attribute/tianjia'), 1);
                }
            } else {
                //验证失败
                $errorinfo=$model->getError();//获得验证信息验证错误信息；
                $this->error($errorinfo,U('tianjia'),1);
            }

        } else {//展示表单

            /********/
            $typeinfo = D('Type')->select();
            $this->assign('typeinfo', $typeinfo);
            /********/
            $bread = array(
                'first' => '属性管理',
                'second' => '属性添加',
                'linkTo' => array(
                    '【返回】', U('type/showlist')
                ),
            );
            $this->assign('bread', $bread);
            $this->display();
        }
    }

            public function getInfoByType(){
                    $type_id=I('get.type_id');
                    $info=D('Attribute')->where(array('type_id'=>$type_id))->select();
                    echo json_encode($info);
            //        $this-type_id
            }
    public function upd()
    {
        $model = D('role');
        if (IS_POST) {
            $data = $model->create();
            if ($model->save($data)) {
                $this->success('修改角色成功', u('showlist'), 1);
            } else
                $this->success('修改角色失败', u('upd', array('role_id' => $data['role_id'])), 1);
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