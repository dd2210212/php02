<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model
{
    // 字段映射定义
    // 把form表单中自定义字段，变为数据表合法字段
    protected $_map             =   array(
        'name' => 'user_name',
        'password' => 'user_pwd',
        'email' => 'user_email',
    );
    /****自动完成(填充字段信息)****/
    protected $_auto = array(
        array('add_time','time',1,'function'), //添加记录完成add_time的填充
        array('user_pwd','md5',1,'function'), //添加记录完成user_pwd加密的填充

    );
}