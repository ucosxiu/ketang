{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">修改密码</div>
                    <div class="layui-card-body" pad15="">

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">当前密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="oldPassword" lay-verify="required" lay-vertype="tips" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="password" lay-verify="pass" lay-vertype="tips" autocomplete="off" id="LAY_password" class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">确认新密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" name="repassword" lay-verify="repass" lay-vertype="tips" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit="" lay-filter="setmypass">确认修改</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var upload = '{:Url('account/avatarupload')}';
        layui.config({
            base: '__STATIC__/system/js/' //静态资源所在路径
        }).use(['index', 'jquery', 'form', 'admin'], function(){
            var $ = layui.$
                    ,n = layui.form
                    ,admin = layui.admin;

            n.render();


            n.on('submit(setmypass)', function(obj){
                var field = obj.field;
                if (!field.oldPassword) {
                    return layer.msg('旧密码不能为空');
                }

                //确认密码
                if(field.password !== field.repassword){
                    return layer.msg('两次密码输入不一致');
                }

                //请求接口
                admin.req({
                    url: '{:Url("setting/password")}' //实际使用请改成服务端真实接口
                    ,data: field
                    ,type: 'post'
                    ,done: function(res){
                        if (res.code) {
                            layer.msg(res.msg, {
                                offset: '15px'
                                ,icon: 1
                                ,time: 1000
                            });
                        } else {
                            layer.msg(res.msg, {
                                offset: '15px'
                                ,icon: 0
                                ,time: 1000
                            });
                        }

                    }
                });

                return false;
            })

        })
    </script>
{/block}
