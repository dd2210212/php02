--创建数据表tp_goods商品表
create table tp_goods(
    goods_id mediumint unsigned not null  auto_increment comment '主键',
    goods_name varchar(32) not null comment '商品名称',
    goods_price decimal(10,2) not null default 0 comment '市场价格',
    goods_shop_price decimal(10,2) not null default 0 comment '本店价格',
    goods_number smallint not null default 1 comment '商品数量',
    goods_weight smallint not null default 0 comment '商品重量',
    cat_id mediumint not null default 0 comment '商品分类',
    brand_id mediumint not null default 0 comment '商品品牌',
    goods_big_logo char(100) not null default '' comment '商品大图片',
    goods_small_logo char(100) not null default '' comment '商品缩略图',
    goods_introduce text comment '商品介绍',
    is_sale enum('up','lo') not null default 'up' comment '上架，下架',
    is_rec enum('re','no') not null default 'no' comment '推荐与否',
    is_hot enum('se','no') not null default 'no' comment '热销与否',
    is_new enum('new','no') not null default 'no' comment '新品与否',
    add_time int not null comment '添加信息时间',
    upd_time int not null comment '修改信息时间',
    is_del enum('del','no') not null default 'no' comment '删除与否',
    primary key (goods_id),
    unique key (goods_name),
    key (goods_shop_price),
    key (goods_price),
    key (cat_id),
    key (brand_id),
    key (add_time)
)engine=Innodb charset=utf8;

--增加一个"抢购"字段，用于网站首页抢购设置
alter table tp_goods add is_qiang enum('no','yes') not null default 'no' comment '是否抢购' after is_sale;

 alter table tp_goods drop column is_qiang;


--删除索引
alter table tp_goods drop key goods_name;
--修改字段长度为256字节
alter table tp_goods modify goods_name varchar(256) not null  comment '商品名称';

--垂直分表，把“相册”的相关字段通过“独立的表”进行存储
create table tp_goods_pics(
    id int unsigned not null auto_increment comment '主键',
    goods_id mediumint unsigned not null  comment '商品id',
    pics_big char(100) not null comment '相册原图',
    pics_small char(100) not null comment '相册缩略图',
    primary key (id)
)engine=Myisam charset=utf8 comment '商品相册表';




DROP TABLE IF EXISTS `tp_auth`;
CREATE TABLE `tp_auth` (
  `auth_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `auth_name` varchar(20) NOT NULL COMMENT '名称',
  `auth_pid` smallint(6) unsigned NOT NULL COMMENT '父id',
  `auth_c` varchar(32) NOT NULL DEFAULT '' COMMENT '模块',
  `auth_a` varchar(32) NOT NULL DEFAULT '' COMMENT '操作方法',
  `auth_path` varchar(32) NOT NULL DEFAULT '' COMMENT '全路径',
  `auth_level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '基别',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
INSERT INTO `tp_auth` VALUES
(101,'商品管理',0,'','','101',0),
(102,'订单管理',0,'','','102',0),
(103,'权限管理',0,'','','103',0),
(104,'商品列表',101,'Goods','showlist','101-104',1),
(105,'添加商品',101,'Goods','tianjia','101-105',1),
(106,'商品分类',101,'Goods','category','101-106',1),
(107,'订单列表',102,'Order','showlist','102-107',1),
(108,'查询订单',102,'Order','look','102-108',1),
(109,'订单打印',102,'Order','dayin','102-109',1),
(110,'管理员列表',103,'Manager','showlist','103-110',1),
(111,'角色列表',103,'Role','showlist','103-111',1),
(112,'权限列表',103,'Auth','showlist','103-112',1),
(115,'会员管理',0,'','','115',0),
(116,'会员列表',115,'User','showlist','115-116',1);

DROP TABLE IF EXISTS `tp_manager`;
CREATE TABLE `tp_manager` (
  `mg_id` int(11) NOT NULL AUTO_INCREMENT,
  `mg_name` varchar(32) NOT NULL,
  `mg_pwd` varchar(32) NOT NULL,
  `mg_time` int(10) unsigned NOT NULL COMMENT '时间',
  `mg_role_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`mg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
INSERT INTO `tp_manager` VALUES
(1,'tom','123456',1323212345,50),
(2,'xiaoming','123456',1312345324,51),
(3,'admin','123456',1323456543,0);


DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role` (
  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
  `role_auth_ids` varchar(128) NOT NULL DEFAULT '' COMMENT '权限ids,1,2,5',
  `role_auth_ac` text COMMENT '模块-操作',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;


INSERT INTO `tp_role` VALUES
(50,'主管','101,104','Goods-showlist'),
(51,'经理','101,105,106,102,107,108','Goods-tianjia,Goods-category,Order-showlist,Order-look');


--"类型"数据表
create table tp_type(
    type_id smallint unsigned not null auto_increment comment '主键id',
    type_name varchar(32) not null  comment '类型名称',
    primary key (type_id)
)engine=Myisam charset=utf8 comment '商品类型表';

--"属性"数据表
create table tp_attribute(
    attr_id int unsigned not null auto_increment comment '主键id',
    attr_name varchar(32) not null  comment '属性名称',
    type_id smallint unsigned not null comment '对应类型id',
    attr_is_sel tinyint not null default 0 comment '0:唯一 1:多选',
    attr_write_mod  tinyint not null default 0 comment '0:手工  1:下拉列表选择',
    attr_sel_opt  varchar(100) not null default '' comment '多选情况被选取的项目信息，多个值彼此使用,逗号分隔',
    primary key (attr_id),
    key (type_id)
)engine=Myisam charset=utf8 comment '商品属性表';



--增加一个type_id字段，用于存储商品对应类型
alter table tp_goods add type_id smallint unsigned not null  default 0  comment '类型id' after brand_id;

-- "商品-(多对多)-属性"" 中间联系表
create table tp_goods_attr(
    id mediumint unsigned not null auto_increment comment '主键id',
    goods_id mediumint unsigned not null comment '商品id',
    attr_id mediumint unsigned not null comment '属性id',
    attr_value varchar(64) not null default '' comment '属性对应的值',
    primary key (id),
    key (goods_id),
    key (attr_id)
)engine=Myisam charset=utf8 comment '商品-属性关联表';


--"分类"数据表
drop table if exists tp_category;
create table tp_category(
    cat_id smallint unsigned not null auto_increment comment '主键id',
    cat_name varchar(32) not null  comment '分类名称',
    cat_pid smallint  unsigned not null default 0 comment '上级id',
    cat_path varchar(32) not null default '' comment '全路径',
    cat_level tinyint not null default 0 comment '等级',
    primary key (cat_id),
    key (cat_pid)
)engine=Myisam charset=utf8 comment '商品分类表';


-- "商品-(多对多)-分类"" 中间联系表
drop table if exists tp_goods_cat;
create table tp_goods_cat(
    id mediumint unsigned not null auto_increment comment '主键id',
    goods_id mediumint unsigned not null comment '商品id',
    cat_id mediumint unsigned not null comment '分类id',
    primary key (id),
    key (goods_id),
    key (cat_id)
)engine=Myisam charset=utf8 comment '商品-分类，关联表';

--会员表
DROP TABLE IF EXISTS `tp_user`;
CREATE TABLE `tp_user` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_name` varchar(32) NOT NULL COMMENT '会员名称',
  `user_email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
  `user_pwd` char(32) NOT NULL COMMENT '密码',
  `openid` char(32) NOT NULL DEFAULT '' COMMENT 'qq登录的openid信息',
  `user_sex` enum('男','女','保密') NOT NULL DEFAULT '男' COMMENT '性别',
  `user_weight` smallint(6) NOT NULL DEFAULT '0' COMMENT '体重',
  `user_height` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '身高',
  `user_logo` varchar(128) NOT NULL DEFAULT '' COMMENT '头像',
  `user_tel` char(11) NOT NULL DEFAULT '' COMMENT '手机',
  `user_identify` char(18) NOT NULL DEFAULT '' COMMENT '身份号码',
  `user_check` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否激活, 0:未激活  1:已激活',
  `user_check_code` char(32) NOT NULL DEFAULT '' COMMENT '邮箱验证激活码',
  `add_time` int(11) NOT NULL COMMENT '注册时间',
  `is_del` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否删除, 0:正常  1:被删除',
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `user_tel` (`user_tel`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='会员表';