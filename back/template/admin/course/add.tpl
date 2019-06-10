{extend name="public/base" /}
{block name="main-content"}
    <link rel="stylesheet" href="__STATIC__/system/css/formSelects-v4.css" />
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li><a href="{:Url('course/index')}">{:lang('list')}</a></li>
                                <li class="layui-this">{:lang('add')}</li>
                            </ul>
                        </div>
                    </div>
                    <style>
                        .layui-upload-img {
                            width: 92px;
                            height: 92px;
                            margin: 0 10px 10px 0;
                        }
                        #steps {
                            padding: 20px 50px;
                        }

                        .step-item {
                            display: inline-block;
                            line-height: 26px;
                            position: relative;
                            background: #ffffff;
                        }

                        .step-item-tail {
                            width: 100%;
                            padding: 0 10px;
                            position: absolute;
                            left: 0;
                            top: 13px;
                        }

                        .step-item-tail i {
                            display: inline-block;
                            width: 100%;
                            height: 1px;
                            vertical-align: top;
                            background: #c2c2c2;
                            position: relative;
                        }
                        .step-item-tail-done {
                            background: #009688 !important;
                        }

                        .step-item-head {
                            position: relative;
                            display: inline-block;
                            height: 26px;
                            width: 26px;
                            text-align: center;
                            vertical-align: top;
                            color: #009688;
                            border: 1px solid #009688;
                            border-radius: 50%;
                            background: #ffffff;
                        }
                        .step-item-head{
                            background: #009688;
                            color: #ffffff;
                        }
                        .step-item-head.step-item-head-active {
                            background: #ffffff;
                            color: #009688;
                        }

                        .step-item-main {
                            background: #ffffff;
                            display: block;
                            position: relative;
                        }

                        .step-item-main-title {
                            font-weight: bolder;
                            color: #555555;
                        }
                        .step-item-main-desc {
                            color: #aaaaaa;
                        }
                        .red{
                            color: red !important;
                        }
                    </style>
                    <div class="layui-card-body">
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('cc_id')}</label>
                                    <div class="layui-input-block">
                                        <select name="cc_id" class="form-control">
                                            <option value="0">≡ 请选择 ≡</option>
                                            {foreach name="courseclasss" item="category" key="k"}
                                                <option value="{$category.cc_id}" {if condition="$pid eq $category.cc_id"}selected{/if}>{$category.cc_name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('goods_name')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="goods_name" lay-verify="required" autocomplete="off" class="layui-input" value="">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('goods_type')}</label>
                                    <div class="layui-input-inline">
                                        <input type="radio" name="goods_type" value="0" title="{:lang('baoming')}"  checked="">
                                        <input type="radio" name="goods_type" value="1" title="{:lang('video')}">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux red">保存不能修改</div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('goods_price')}</label>
                                    <div class="layui-inline">
                                        <input type="radio" name="is_free" value="1" title="{:lang('free')}"  lay-filter="is_free"  checked="">
                                        <input type="radio" name="is_free" value="0" title="{:lang('charge')}" lay-filter="is_free">
                                    </div>
                                    <div class="layui-inline goods_price" style="display: none">
                                        <input type="number" name="goods_price" autocomplete="off" class="layui-input" value="" id="goods_price" lay-verify="">

                                    </div>
                                    <div class="layui-inline goods_price" style="display: none">
                                        <div class="layui-form-mid layui-word-aux">元 </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('goods_pic')}</label>
                                    <div class="layui-inline">
                                        <input type="text" name="goods_pic" lay-verify="required" class="layui-input" id="goods_pic" readonly>

                                    </div>
                                    <div class="layui-inline">
                                        <div class="layui-upload">
                                            <button type="button" class="layui-btn" id="test1">上传图片</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label"></label>
                                    <div class="layui-input-block">
                                        <div class="layui-upload-list">
                                            <img class="layui-upload-img" id="demo1">
                                            <p id="demoText"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('goods_teacher')}</label>
                                    <div class="layui-input-block">
                                        <select name="teacher_ids" lay-verify="required" class="form-control" xm-select="select1">
                                            <option value="">≡ 请选择老师 ≡</option>
                                            {foreach name="teacheres" item="item" key="k"}
                                                <option value="{$item.teacher_id}">{$item.teacher_name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">&nbsp;{:lang('goods_intro')}</label>
                                    <div class="layui-input-block">
                                        <textarea name="goods_intro" id="goods_intro" class="editor" lay-verify="goods_intro"></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('goods_state')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="goods_state" value="1" title="{:lang('open')}"  checked="">
                                        <input type="radio" name="goods_state" value="0" title="{:lang('close')}">
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
                                }).extend({
                                    formSelects: 'formSelects-v4'
                                }).use(['index', 'admin', 'form', 'upload', 'formSelects', 'layedit'], function(){
                                    var table = layui.table
                                        ,admin = layui.admin
                                        ,form = layui.form
                                        ,upload = layui.upload;
                                    var formSelects = layui.formSelects;
                                    form.render();

                                    form.on('radio(is_free)', function (data) {
                                        if (data.value == 0) {
                                            $('.goods_price').show();
                                            $('#goods_price').attr('lay-verify', 'required')
                                        } else {
                                            $('.goods_price').hide();
                                            $('#goods_price').attr('lay-verify', '')
                                        }
                                        form.render();
                                    });

                                    var layedit = layui.layedit;
                                    layedit.set({
                                        uploadImage: {
                                            url: "{:Url('asset/layuiup')}" //接口url
                                            ,type: '' //默认post
                                        }
                                    });
                                    var layeditindex = layedit.build('goods_intro', {
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
                                    form.verify({
                                        goods_intro: function(value){
                                            layedit.sync(layeditindex);
                                        }
                                    });
                                    layedit.sync(layeditindex)

                                    var uploadInst = upload.render({
                                        elem: '#test1'
                                        ,url: '{:Url('asset/upload')}'
                                        ,before: function(obj){
                                        }
                                        ,done: function(res){
                                            //如果上传失败
                                            if(res.code){
                                                if(res.code){
                                                    $('#demo1').attr('src', res.data.link);
                                                    $('#goods_pic').val(res.data.url);
                                                } else {
                                                    return layer.msg('上传失败');
                                                }
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
