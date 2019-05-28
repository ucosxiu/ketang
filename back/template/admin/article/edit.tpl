{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid" style="padding: 0">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li><a href="{:Url('article/index')}">{:lang('list')}</a></li>
                                <li class="layui-this">{:lang('edit')}</li>
                            </ul>
                        </div>
                    </div>
                    <style>
                        .show{
                            display: inline-block;
                            position: relative;
                            width: 38px;
                            margin-left: 20px;
                        }
                        .show i{
                            position: absolute;
                            left: 0;
                            top: -15px;
                        }
                        .layui-form-select dl{
                            max-height: 200px;
                        }
                    </style>
                    <div class="layui-card-body">
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('article_cate')}</label>
                                    <div class="layui-input-block">
                                        <select name="ac_id" class="form-control" lay-verify="required">
                                            <option value="">≡ 请选择  ≡</option>
                                            {foreach name="classes" item="item" key="k"}
                                                <option value="{$item.ac_id}" {if condition="$pid eq $item.ac_id"}selected{/if}>{$item.ac_name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('article_title')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="article_title" lay-verify="required" autocomplete="off" class="layui-input" value="{$article.article_title}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('listorder')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="article_listorder" lay-verify="required" autocomplete="off" class="layui-input" value="{$article.article_listorder}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('article_is_full')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="article_is_full" value="1" title="{:lang('yes')}"  checked="">
                                        {php}$checked1 = $article->article_is_full ? '' : 'checked';{/php}
                                        <input type="radio" name="article_is_full" value="0" title="{:lang('no')}" {$checked1}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('article_show')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="article_show" value="1" title="{:lang('yes')}"  checked="">
                                        {php}$checked = $article->article_show ? '' : 'checked';{/php}
                                        <input type="radio" name="article_show" value="0" title="{:lang('no')}" {$checked}>
                                    </div>
                                </div>
                                <div class="layui-form-item ">
                                    <label class="layui-form-label">&nbsp;{:lang('article_content')}</label>
                                    <div class="layui-input-block">
                                        <textarea name="article_content" id="template_content">{$article.article_content|default=''}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="" lay-filter="ajax-submit">{:lang('submit')}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script type="text/javascript" src="__STATIC__/ueditor/ueditor.config.js"></script>
                        <script type="text/javascript" src="__STATIC__/ueditor/ueditor.all.js"></script>
                        <script type="text/javascript" charset="utf-8" src="__STATIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
                        <script>
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).use(['index', 'admin', 'form', 'upload'], function(){
                                    var table = layui.table
                                        ,admin = layui.admin
                                        ,form = layui.form
                                        ,upload = layui.upload;
                                    form.render();
                                    var preview = null
                                    $('.layui-picture').mousedown(function() {
                                        var img = "<img class='img_msg' src='"+$(this).attr('data-src')+"' style='max-width: 270px'/>"
                                        preview = layer.tips(img, this,{
                                            tips:[2, 'rgba(41,41,41,1)'],
                                            time: 1000000,
                                            maxWidth: 300,
                                            area: 'auto'
                                        });
                                    }).mouseout(function() {
                                        layer.close(preview)
                                    })

                                    var url="{:url('Ueditor/index')}";
                                    var ue = UE.getEditor('template_content',{
                                        serverUrl :url
                                    });
                                    setTimeout(function () {
                                        ue.setHeight(300)
                                    }, 500)

                                    var uploadInst = upload.render({
                                        elem: '#test1'
                                        ,url: '{:Url('asset/upload')}'
                                        ,before: function(obj){
                                        }
                                        ,done: function(res){
                                            //如果上传失败
                                            if(res.code){
                                                $('#show').show();
                                                $('#show i').attr('data-src', res.data.link);

                                                $('#show input').val(res.data.url);
                                            } else {
                                                return layer.msg('上传失败');
                                            }
                                            //上传成功
                                        }
                                        ,error: function(){
                                            //演示失败状态，并实现重传
                                            var demoText = $('#demoText');
                                            demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                                            demoText.find('.demo-reload').on('click', function(){
                                                uploadInst.upload();
                                            });
                                        }
                                    });

                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
