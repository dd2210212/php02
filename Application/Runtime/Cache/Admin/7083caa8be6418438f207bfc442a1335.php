<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title>会员列表</title>

    <link href="/Public/Admin/css/mine.css" type="text/css" rel="stylesheet" />
    <script src="/Public/Js/jquery-3.3.1.min.js"></script>
</head>
<body>
<style>
    .tr_color{background-color: #9F88FF}
</style>
<div class="div_head">
            <span>
                <span style="float: left;">当前位置是：<?php echo ($bread["first"]); ?>-》<?php echo ($bread["second"]); ?></span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo ($bread["linkTo"]["1"]); ?>"><?php echo ($bread["linkTo"]["0"]); ?></a>
                </span>
            </span>
</div>
<div style="font-size: 13px; margin: 10px 5px;">
    <table class="table_a" border="1" width="100%">
        <tbody><tr style="font-weight: bold;">
            <td>序号</td><td>名称</td>
            <td>权限ids</td>
            <td align="center" colspan='3'>操作</td>
        </tr>
        <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr id="product_<?php echo ($v["role_id"]); ?>">
                <td><?php echo ($v["role_id"]); ?></td>
                <td><a href="#"><?php echo ($v["role_name"]); ?></a></td>
                <td><?php echo ($v["role_auth_ids"]); ?></td>

                <td><a href="<?php echo U('distribute',array('role_id'=>$v['role_id']));?>" >分配权限</a></td>

                <td><a href="<?php echo U('upd',array('role_id'=>$v['role_id']));?>" >修改</a></td>
                <td><a href="javascript:;" onclick="if(confirm('确认要删除该商品么？')){del_role(<?php echo ($v["role_id"]); ?>)}">删除</a></td>
            </tr><?php endforeach; endif; ?>
        <script type="text/javascript">
            function del_role(role_id){
                //利用ajax去服务器删除数据库记录信息
                $.ajax({
                    url:"<?php echo U('del');?>",
                    data:{'role_id':role_id},
                    dataType:'json',
                    type:'get',
                    success:function(msg){
                        if(msg.status==1){
                            $('#product_'+role_id).remove();
                        }
                    }
                });
            }
        </script>
        </tbody>
    </table>
</div>

<tr>
                        <td colspan="20" style="text-align: center;">
                            <?php echo ($pagelist); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


</body>
</html>