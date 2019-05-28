{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li>
                                    <a href="{:Url('role/index')}">{:lang('list')}</a>
                                </li>
                                <li class="layui-this">
                                    <a href="javascript:void(0);">{:lang('edit')}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('username')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="username" lay-verify="username" autocomplete="off" class="layui-input" value="{$admin.username}" readonly>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('password')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="password" lay-verify="password" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">单行选择框</label>
                                    <div class="layui-input-block">
                                        <select name="roleid" lay-filter="aihao">
                                            {foreach name="roles" item="vo"}
                                                <option value="{$vo.roleid}" {if condition='$vo.roleid eq $admin.roleid'}selected="" {/if}>{$vo.rolename}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">{:lang('email')}</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input"  value="{$admin.email}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('status')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="{:lang('open')}" checked>
                                        {php}$checked = $admin['status'] ? '' : 'checked'{/php}
                                        <input type="radio" name="status" value="0" title="{:lang('close')}" {$checked}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="" lay-filter="demo1">{:lang('submit')}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            layui.config({
                base: '__STATIC__/system/js/' //静态资源所在路径,
                ,version: '1.2.1'
            }).use(['index', 'admin', 'form', 'upload'], function(){
                var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
                form.render();
            });
        })
    </script>
{/block}
