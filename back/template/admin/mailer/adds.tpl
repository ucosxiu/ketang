{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li><a href="{:Url('mailer/index')}">{:lang('label')}</a></li>
                                <li><a href="{:Url('mailer/templatelist')}">{:lang('templatelist')}</a></li>
                                <li class="layui-this">
                                    {empty name="template"}{:lang('add')}{else /}{:lang('edit')}{/empty}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item layui-col-lg6">
                                    <label class="layui-form-label">{:lang('template_code')}</label>
                                    <div class="layui-input-block">
                                        <select name="template_code" id='template_code' class="form-control" model="select" lay-filter="template_code">
                                            <option value="">≡ 选择模板 ≡</option>
                                            {foreach name="tpls" item="item"}
                                                <option value="{$item.template_code}" {if condition="!empty($template) && $item.template_code eq $template.template_code"}selected{/if}>{$item.template_subject}[{$item.template_code}]</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('subject')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="template_subject" lay-verify="template_subject" autocomplete="off" class="layui-input" value="{$template.template_subject|default=''}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('status')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="{:lang('open')}" checked>
                                        {if condition="!empty($template) && $template.status eq 0"}
                                        <input type="radio" name="status" value="0" title="{:lang('close')}" checked>
                                        {else /}
                                        <input type="radio" name="status" value="0" title="{:lang('close')}">
                                        {/if}
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('type')}</label>
                                    <div class="layui-input-block">
                                        {if condition="!empty($template) && $template.is_html"}
                                            <input type="radio" name="is_html" value="1" title="{:lang('html')}"  checked="">
                                        {else /}
                                            <input type="radio" name="is_html" value="1" title="{:lang('html')}">
                                        {/if}
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg10">
                                    <label class="layui-form-label">&nbsp;</label>
                                    <div class="layui-input-block">
                                        <textarea name="template_content" id="template_content">{$template.template_content|default=''}</textarea>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        {notempty name="template"}
                                            <input type="hidden" name="template_id" value="{$template.template_id}">
                                        {/notempty}
                                        <button class="layui-btn" lay-submit="" lay-filter="ajax-submit">{:lang('submit')}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <script type="text/javascript" src="__STATIC__/ueditor/ueditor.config.js"></script>
                        <script type="text/javascript" src="__STATIC__/ueditor/ueditor.all.min.js"></script>
                        <script type="text/javascript" charset="utf-8" src="__STATIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
                        <script>
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).use(['index', 'admin', 'table', 'form'], function(){
                                    var table = layui.table
                                            ,admin = layui.admin
                                            ,form = layui.form;

                                    var url="{:url('Ueditor/index')}";
                                    var ue = UE.getEditor('template_content',{
                                        serverUrl :url
                                    });

                                    form.render();
                                    form.on('select(template_code)', function(data){
                                        var val = data.value
                                        if (val) {
                                            admin.req({
                                                url: '{:Url("mailer/load_template")}',
                                                type: "post",
                                                data: {tpl: val},
                                                done: function(e){
                                                    if (e.code) {
                                                        var template_content = e.data.template_content;
                                                        ue.execCommand('insertHtml', $(template_content).html());
                                                        $('input[name="template_subject"]').val(e.data.template_subject)
                                                        $('input[name="is_html"][value="' + e.data.is_html + '"]').attr("checked", "checked")
                                                        form.render();

                                                    }
                                                }
                                            })
                                        }
                                    });

                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div
{/block}
