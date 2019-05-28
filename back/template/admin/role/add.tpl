{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card"  style="padding: 15px 0">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li>
                                    <a href="{:Url('role/index')}">{:lang('list')}</a>
                                </li>
                                <li class="layui-this">
                                    <a href="javascript:void(0);">{:lang('add')}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('rolename')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="rolename" lay-verify="title" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('description')}</label>
                                    <div class="layui-input-block">
                                        <textarea placeholder="请输入内容" class="layui-textarea" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('status')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="{:lang('open')}"  checked="">
                                        <input type="radio" name="status" value="0" title="{:lang('close')}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="" lay-filter="ajax-submit">{:lang('submit')}</button>
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
            }).use(['index', 'admin', 'form'], function(){
                var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
                form.render();
            })

        })
    </script>
{/block}
