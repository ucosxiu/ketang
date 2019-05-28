<html>
<head>
    <meta charset="utf-8" />
    <title>layuiAdmin pro - 通用后台管理模板系统（单页面专业版）</title>
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    {css href="__STATIC__/layui/css/layui.css,__STATIC__/layui/css/modules/layer/default/layer.css,__STATIC__/system/css/admin.css,__STATIC__/system/css/login.css" /}
    {js href="__STATIC__/layui/layui.js" /}
</head>
<body layadmin-themealias="default" class="layui-layout-body">
<div id="LAY_app" class="layadmin-tabspage-none">
    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>tuAdmin</h2>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input" />
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input" />
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="LAY-user-login-submit">登 入</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    layui.config({
        base: '__STATIC__/system/js/'
        ,version: '1.2.1'
    }).use(['index', 'admin', 'form', 'user'], function(){
        var $ = layui.$
            ,setter = layui.setter
            ,admin = layui.admin
            ,form = layui.form
            ,router = layui.router()
            ,search = router.search;

        form.render();

        //提交
        form.on('submit(LAY-user-login-submit)', function(obj){

        //请求登入接口
        admin.req({
            url: '{:Url('sign/dologin')}' //实际使用请改成服务端真实接口
            ,data: obj.field
            ,done: function(res){

            //请求成功后，写入 access_token
            layui.data(setter.tableName, {
                key: setter.request.tokenName
                ,value: res.data.access_token
            });

            if (res.code) {
                //登入成功的提示与跳转
                layer.msg('登入成功', {
                    offset: '15px'
                    ,icon: 1
                    ,time: 1000
                }, function(){
                    location.href = res.url
                });
            } else {
                layer.msg(res.msg, {
                    offset: '15px'
                        ,icon: 1
                        ,time: 1000
                    }, function(){
                    });
                }

            }
            });
        });
    });
    </script>
</div>
</body>
</html>
