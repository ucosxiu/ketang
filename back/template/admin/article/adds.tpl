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
                                <li class="layui-this">{:lang('add')}</li>
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
                                        <input type="text" name="article_title" lay-verify="required" autocomplete="off" class="layui-input" value="">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('listorder')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="article_listorder"  autocomplete="off" class="layui-input" value="">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('article_is_full')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="article_is_full" value="1" title="{:lang('yes')}"  checked="">
                                        <input type="radio" name="article_is_full" value="0" title="{:lang('no')}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('article_show')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="article_show" value="1" title="{:lang('yes')}"  checked="">
                                        <input type="radio" name="article_show" value="0" title="{:lang('no')}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">&nbsp;{:lang('article_content')}</label>
                                    <div class="layui-input-block">
                                        <textarea name="article_content" id="template_content" class="editor">{$template.article_content|default=''}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="" lay-filter="ajax-submit1">{:lang('submit')}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script type="text/javascript" src="__STATIC__/system/js/bootstrap-markdown.min.js"></script>
                        <script type="text/javascript" src="__STATIC__/system/js/hyperdown.min.js"></script>
                        <script type="text/javascript" src="__STATIC__/system/js/jquery.pasteupload.js"></script>
                        <script type="text/javascript" src="__STATIC__/ueditor/ueditor.config.js"></script>
                        <script type="text/javascript" src="__STATIC__/ueditor/ueditor.all.min.js"></script>
                        <script type="text/javascript" charset="utf-8" src="__STATIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
                        <script>
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).use(['index', 'admin', 'form', 'upload', 'layedit'], function(){
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


                                    var layedit = layui.layedit;
                                    layedit.set({
                                        uploadImage: {
                                            url: "{:Url('asset/layuiup')}" //接口url
                                            ,type: '' //默认post
                                        }
                                    });
                                    var layeditindex = layedit.build('template_content', {
                                        tool: [
                                            'strong' //加粗
                                            ,'italic' //斜体
                                            ,'underline' //下划线
                                            ,'del' //删除线

                                            ,'|' //分割线

                                            ,'left' //左对齐
                                            ,'center' //居中对齐
                                            ,'right' //右对齐
                                            ,'link' //超链接
                                            ,'unlink' //清除链接
                                            ,'image' //插入图片

                                        ]
                                    }); //建立编辑器
                                    layedit.sync(layeditindex)

                                    if (false) {
                                        var url="{:url('Ueditor/index')}";
                                        var ue = UE.getEditor('template_content',{
                                            serverUrl :url
                                        });

                                        setTimeout(function () {
                                            ue.setHeight(300)
                                        }, 500)
                                    }
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

                                    layui.form.on('submit(ajax-submit1)', function(obj){
                                        var url = obj.form.action;
                                        obj.field.article_content = layedit.getContent(layeditindex)
                                        $.post(url, obj.field, function(data){
                                            if (data.code) {
                                                layer.msg(data.msg, {
                                                    offset: '15px'
                                                    ,icon: 1
                                                    ,time: 1000
                                                }, function(){
                                                    if (data.url) {
                                                        location.href = data.url
                                                    }
                                                });
                                            } else {
                                                layer.msg(data.msg, {
                                                    offset: '15px'
                                                    ,icon: 0
                                                    ,time: 1000
                                                }, function(){
                                                });
                                            }
                                        }, 'json')
                                    })
                                })

                                if ($(".editor", $('.form')).size() > 0) {
                                    $.fn.markdown.messages.zh = {
                                        Bold: "粗体",
                                        Italic: "斜体",
                                        Heading: "标题",
                                        "URL/Link": "链接",
                                        Image: "图片",
                                        List: "列表",
                                        "Unordered List": "无序列表",
                                        "Ordered List": "有序列表",
                                        Code: "代码",
                                        Quote: "引用",
                                        Preview: "预览",
                                        "strong text": "粗体",
                                        "emphasized text": "强调",
                                        "heading text": "标题",
                                        "enter link description here": "输入链接说明",
                                        "Insert Hyperlink": "URL地址",
                                        "enter image description here": "输入图片说明",
                                        "Insert Image Hyperlink": "图片URL地址",
                                        "enter image title here": "在这里输入图片标题",
                                        "list text here": "这里是列表文本",
                                        "code text here": "这里输入代码",
                                        "quote here": "这里输入引用文本"
                                    };
                                    var parser = new HyperDown();
                                    window.marked = function (text) {
                                        return parser.makeHtml(text);
                                    };
                                }
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
