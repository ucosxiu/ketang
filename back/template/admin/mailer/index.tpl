{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('label')}</li>
                                <li>
                                    <a href="{:Url('mailer/templatelist')}">{:lang('templatelist')}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body ">
                        <style>.layui-form-pane .layui-form-label{width:160px;}.layui-form-pane .layui-input-block{margin-left: 160px;}</style>
                        <form class="layui-form layui-form-pane" action="" method="post" lay-filter="component-form-element" onsubmit="return false">
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('SENDER_NAME')}：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="smtp_from_name" lay-verify="" placeholder="" value="{$option.smtp_from_name|default=''}" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('SENDER_EMAIL_ADDRESS')}：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="smtp_from_to" lay-verify="" placeholder="" value="{$option.smtp_from_to|default=''}" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('SENDER_SMTP_SERVER')}：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="smtp_host" lay-verify="" placeholder="" value="{$option.smtp_host|default=''}" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('SENDER_SMTP_PORT')}：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="smtp_port" lay-verify="" placeholder="默认为25" value="{$option.smtp_port|default=''}" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('smtpsecure')}：</label>
                                    <div class="layui-input-block">
                                        <select name="smtp_secure">
                                            <option value="">默认</option>
                                            <option value="ssl" {if $option.smtp_secure eq 'ssl'}selected{/if}>ssl</option>
                                            <option value="tls" {if $option.smtp_secure eq 'tls'}selected{/if}>tls</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('SMTP_MAIL_ADDRESS')}：</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="smtp_username"  value="{$option.smtp_username|default=''}"  lay-verify="" placeholder="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">{:lang('SMTP_MAIL_PASSWORD')}：</label>
                                    <div class="layui-input-block">
                                        <input type="password" name="smtp_password" value="{$option.smtp_password|default=''}"  lay-verify="" placeholder="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit="" lay-filter="ajax-submit">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary" >重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        $(function(){
                            layui.config({
                                base: '__STATIC__/system/js/' //静态资源所在路径,
                                ,version: '1.2.1'
                            }).use(['index', 'admin', 'table', 'layer'], function(){
                                var admin = layui.admin;

                            })
                        })
                    </script>
                </div>
            </div>
        </div>
    </div
{/block}
