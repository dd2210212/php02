<?php
namespace Admin\Controller;
use Common\Tools\BackController;
class GoodsController extends BackController
{
    public function showlist()
    {
        $model = D('goods');
        $nowinfo = $model->fetchData();
        $info = $nowinfo['pageinfo'];
        $pagelist = $nowinfo['pagelist'];

        $bread = array(
            'first' => '商品管理',
            'second' => '商品列表',
            'linkTo' => array(
                '【添加商品】',U('Goods/tianjia')
            ),
        );
$this->assign('bread',$bread);
        $this->assign('info', $info);
        $this->assign('pagelist', $pagelist);
        $this->display();
    }

    public function tianjia()
    {
        $model = D('goods');
        //收集表单
        if (IS_POST) {
            $data = $model->create();
            //htmlpurifier过滤，在添加数据前过滤
            $data['goods_introduce'] = \fanXSS($_POST['goods_introduce']);
            if ($model->add($data)) {
                $this->success('商品添加成功', U('showlist'));
                exit;
            } else {
                $this->error('商品添加失败', U('tianjia'), '1');
            }
        } else {//展示表单逻辑

            /****获得"商品类型"信息****/
            $typeinfo = D('Type')->select();
            $this -> assign('typeinfo',$typeinfo);
            /****获得"商品类型"信息****/

            /****获取分类并传递显示****/
            $catinfo = D('Category')->order('cat_path')->select();
            $this -> assign('catinfo',$catinfo);
            /****获取分类并传递显示****/

            $bread = array(
                'first' => '商品管理',
                'second' => '商品添加',
                'linkTo' => array(
                    '【返回】',U('Goods/showlist')
                ),
            );
            $this -> assign('bread',$bread);
            $this->display();
        }
    }

    //修改商品
    function upd(){
        //两个逻辑：展示、收集
        $goods = D('Goods');
        if(IS_POST){
            $data = $goods -> create();
            if($goods -> save($data)){
                $this -> success('修改商品成功', U('showlist'), 2);
            }else{
                $this -> error('修改商品失败', U('upd',array('goods_id'=>$data['goods_id'])), 2);
            }
        }else{
            $goods_id = I('get.goods_id'); //接收被修改商品的goods_id
            $info = $goods->find($goods_id);//查询被修改商品的信息

            /*************获得相册信息*************/
            $picsinfo = D('GoodsPics')->where(array('goods_id'=>$goods_id))->select();
            if(!empty($picsinfo))
                $this -> assign('picsinfo',$picsinfo);
            /*************获得相册信息*************/

            /****获得"商品类型"信息****/
            $typeinfo = D('Type')->select();
            $this -> assign('typeinfo',$typeinfo);
            /****获得"商品类型"信息****/

            /****获取主分类并传递显示****/
            $catinfo = D('Category')->order('cat_path')->select();
            $this -> assign('catinfo',$catinfo);
            /****获取主分类并传递显示****/

            /********获得商品的扩展分类信息(tp__goods_cat)********/
            $catextinfo = D('GoodsCat')->where(array('goods_id'=>$goods_id))->select();
            $this -> assign('catextinfo',$catextinfo);
            /********获得商品的扩展分类信息(tp__goods_cat)********/

            $bread = array(
                'first' => '商品管理',
                'second' => '商品修改',
                'linkTo' => array(
                    '【返回】',U('Goods/showlist')
                ),
            );
            $this -> assign('bread',$bread);

            $this -> assign('info',$info);
            $this -> display();
        }
    }

    //删除单个相册图片
    public function delPics()
    {
        $model = D('goods_pics');
        $pics_id = I('get.pics_id');
        //查询图片并删除
        $info = $model->find($pics_id);
        //删除相册图片物理删除
        unlink($info['pics_big']);
        unlink($info['pics_small']);
        //删除数据记录信息
        $z = $model->delete($pics_id);
        if ($z) {
            echo '删除成功';
        }
    }

    //删除商品
    function delGoods(){

        $goods_id = I('get.goods_id');  //获得被删除商品的id信息
        $goods = D('Goods');
        $z = $goods -> delete($goods_id);
        //setField()内部有调用save()方法，
        if($z){
            echo json_encode(array('status'=>1)); //ok  99%
        }else{
            echo json_encode(array('status'=>2)); //fail 1%
        }
    }
    //根据type_id获得对应的属性信息
    function getAttributeByType(){
        $type_id = I('get.type_id');
        $attrinfo = D('Attribute')->where(array('type_id'=>$type_id))->select();
        echo json_encode($attrinfo);
    }
    //修改商品，根据"type_id/goods_id"获得类型对应的属性信息
    function getAttributeByTypeUpd(){
        $type_id = I('get.type_id');
        $goods_id = I('get.goods_id');
        //判断当前选取的type_id和本身商品的是否一致
        $goodsinfo = D('Goods')->find($goods_id);
        if($type_id!== $goodsinfo['type_id']){

            $attrinfo = D('Attribute')->where(array('type_id'=>$type_id))->select();
            $info['mark'] = 1; ////其他类型对应的属性信息
            $info[1] = $attrinfo;
            //$info是三维数组
            echo json_encode($info);
        }else{
            //数据表：goods_attr  attribute
            $attrinfo = D('GoodsAttr')
                ->alias('g')
                ->join('__ATTRIBUTE__ a on g.attr_id=a.attr_id')
                ->where(array('goods_id'=>$goods_id))
                ->field('g.attr_id,g.attr_value,a.attr_name,a.attr_is_sel,a.attr_sel_opt')
                ->select();
            //dump($attrinfo);//普通的二维数组信息

            $tmp = array();
            foreach($attrinfo as $k => $v){
                if(!empty($tmp[$v['attr_id']]) || $v['attr_is_sel']==1){
                    //多选属性整合
                    $tmp[$v['attr_id']]['attr_id'] = $v['attr_id'];
                    $tmp[$v['attr_id']]['attr_name'] = $v['attr_name'];
                    $tmp[$v['attr_id']]['attr_is_sel'] = $v['attr_is_sel'];
                    $tmp[$v['attr_id']]['attr_sel_opt'] = $v['attr_sel_opt'];
                    $tmp[$v['attr_id']]['attr_value'][] = $v['attr_value'];
                }else{
                    //单选属性整合
                    $tmp[$v['attr_id']] = $v;
                }
            }
            //dump($tmp);//整合后的三维数组信息
            $info['mark'] = 2; //商品本身拥有的的属性信息
            $info[1] = $tmp;
            //$info变为一个四维数组
            echo json_encode($info);
        }
    }
}