<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>2z2x</title>
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>2z2x</title>
    {css href="__STATIC__/layui/css/layui.css,__STATIC__/system/css/admin.css,__STATIC__/layui/css/modules/layer/default/layer.css"/}
    {js href="__STATIC__/system/js/jquery-1.11.1.min.js,__STATIC__/layui/layui.js" /}
    <script>
        layui.config({
            base: '__STATIC__/system/js/' //静态资源所在路径,
            ,version: '1.2.1'
        })
    </script>
</head>
<body layadmin-themealias="default" class="layui-layout-body">
<div id="LAY_app" class="layadmin-tabspage-none">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-body">
            <div class="layadmin-tabsbody-item layui-show">
                {block name="main-content"}
                {/block}
            </div>
        </div>
    </div>
</div>
</body>
</html>
