<?php
namespace Admin\Controller;
use Common\Tools\BackController;
class RoleController extends BackController{

    public function showlist()
    {
        $info = D('Role')->select();
        $bread = array(
            'first' => '角色管理',
            'second' => '角色列表',
            'linkTo' => array(
                '【添加角色】', U('Role/tianjia')
            ),
        );
        $this->assign('bread', $bread);
        $this->assign('info', $info);
        $this->display();
    }

//给角色分配权限
    function distribute()
    {
        //两个逻辑：展示、收集
        $role = D('Role');
        if (IS_POST) {
            //通过“瞻前顾后”机制实现数据制作role_auth_ids/role_auth_ac(权限分配)
            $data = $role->create();
            if ($role->save($data)) {
                $this->success('分配权限成功', U('showlist'), 1);
            } else {
                $this->error('分配权限失败', U('distribute', array('role_id', I('get.role_id'))), 1);
            }
        } else {
            $role_id = I('get.role_id');
            //获得"被分配权限"的角色信息
            $roleinfo = $role->find($role_id);

            $this->assign('roleinfo', $roleinfo);

            /*****获得被分配的权限****/
            $auth_infoA = D('Auth')->where("auth_level=0")->select();//顶级权限
            $auth_infoB = D('Auth')->where("auth_level=1")->select();//次顶级权限
            $this->assign('auth_infoA', $auth_infoA);
            $this->assign('auth_infoB', $auth_infoB);
            /*****获得被分配的权限****/

            //设置面包屑
            $bread = array(
                'first' => '角色管理',
                'second' => '分配权限',
                'linkTo' => array(
                    '返回', U('showlist')
                ),
            );
            $this->assign('bread', $bread);
            $this->display();
        }
    }

    public function tianjia()
    {
        $model = D('role');
        if (IS_POST) {
            $data = $model->create();
//            dump($data);
//            exit;
            if ($model->add($data)) {
                $this->success('添加角色成功', U('showlist'), 1);
            } else {
                $this->error('添加角色失败', U('tianjia'), 1);
            }
        } else {//展示表单
            $bread = array(
                'first' => '角色管理',
                'second' => '商品添加',
                'linkTo' => array(
                    '【返回】', U('role/showlist')
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