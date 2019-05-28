{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li>
                                    <a href="{:Url('menu/index')}">{:lang('list')}</a>
                                </li>
                                <li class="layui-this">
                                    <a href="javascript:void(0);">{:lang('edit')}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('parent')}</label>
                                    <div class="layui-input-block">
                                        <select name="parentid" class="form-control">
                                            <option value="0">≡ 作为一级菜单 ≡</option>
                                            {:$options}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('menuname')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="menuname" lay-verify="menuname" autocomplete="off" class="layui-input" value="{$_menu.menuname}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('module')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="m" lay-verify="m" autocomplete="off" class="layui-input" value="{$_menu.m}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('controller')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="c" lay-verify="c" autocomplete="off" class="layui-input" value="{$_menu.c}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('action')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="a" lay-verify="a" autocomplete="off" class="layui-input" value="{$_menu.a}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('parameter')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="parameter" lay-verify="parameter" autocomplete="off" class="layui-input" value="{$_menu.parameter}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('icon')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="icon" lay-verify="icon" autocomplete="off" class="layui-input" value="{$_menu.icon}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('status')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="{:lang('open')}"  checked="">
                                        {php}$checked = $_menu['status'] ? '' : 'checked'{/php}
                                        <input type="radio" name="status" value="0" title="{:lang('close')}" {$checked}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('type')}</label>
                                    <div class="layui-input-block">
                                        <select name="type" class="form-control">
                                            <option value="1">{:lang('type1')}</option>
                                            {php}$selected = $_menu['type'] ? '' : 'selected'{/php}
                                            <option value="0" {$selected}>{:lang('type0')}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="" lay-filter="ajax-submit">{:lang('submit')}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script>
                            $(function(){
                                layui.use('index', 'admin', 'form', function(){
                                    var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
                                    form.render();
                                });
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
