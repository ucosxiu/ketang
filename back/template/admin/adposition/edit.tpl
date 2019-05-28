{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li><a href="{:Url('adposition/index')}">{:lang('list')}</a></li>
                                <li class="layui-this">{:lang('edit')}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('position_name')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="position_name" lay-verify="required" autocomplete="off" class="layui-input" value="{$position.position_name|default=''}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('ad_width')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="ad_width" lay-verify="required" autocomplete="off" class="layui-input" value="{$position.ad_width|default=''}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('ad_height')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="ad_height" lay-verify="required" autocomplete="off" class="layui-input" value="{$position.ad_height|default=''}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('position_model')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="position_model" lay-verify="required" autocomplete="off" class="layui-input" value="{$position.position_model|default=''}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('position_desc')}</label>
                                    <div class="layui-input-block">
                                        <textarea name="position_desc" id="position_desc"  class="layui-textarea" >{$position.position_desc}</textarea>
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
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).use(['index', 'admin', 'form'], function(){
                                    var table = layui.table
                                            ,admin = layui.admin
                                            ,form = layui.form;

                                    form.render();

                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div
{/block}
