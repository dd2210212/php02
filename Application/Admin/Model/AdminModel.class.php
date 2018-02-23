<?php

//模型

namespace Admin\Model;
use Think\Model;

class AdminModel extends Model
{
    protected $_link = array(
        '关联1' => array(
            '关联属性1' => '定义',
            '关联属性N' => '定义',
        ),
        '关联2' => array(
            '关联属性1' => '定义',
            '关联属性N' => '定义',
        ),
        '关联3' => HAS_ONE, // 快捷定义

);

}
