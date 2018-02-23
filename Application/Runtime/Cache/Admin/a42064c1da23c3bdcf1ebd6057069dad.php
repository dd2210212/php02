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
<div style="font-size: 13px;margin: 10px 5px">
    <form action="/index.php/Admin/Role/tianjia.html" method="post" enctype="multipart/form-data">
        <table border="1" width="100%" class="table_a" id="general-tab-tb">
            <tr>
                <td>名称：</td>
                <td><input type="text" name="role_name" /></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="添加" />
                </td>
        </table>
    </form>

</div>



</body>
</html>