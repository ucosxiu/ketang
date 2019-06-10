{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li><a href="{:Url('teacher/index')}">{:lang('list')}</a></li>
                                <li class="layui-this">{:lang('edit')}</li>
                            </ul>
                        </div>
                    </div>
                    <style>
                        .layui-upload-img {
                            width: 92px;
                            height: 92px;
                            margin: 0 10px 10px 0;
                        }
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
                    </style>
                    <div class="layui-card-body">
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('teacher_name')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="teacher_name" lay-verify="required" autocomplete="off" class="layui-input" value="{$teacher.teacher_name}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('teacher_pic')}</label>
                                    <div class="layui-inline">
                                        <input type="text" name="teacher_pic" lay-verify="required" class="layui-input" id="teacher_pic" value="{$teacher.teacher_pic}" readonly>

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
                                            <img class="layui-upload-img" src="__ROOT__/{$teacher.teacher_pic}" id="demo1">
                                            <p id="demoText"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('teacher_intro')}</label>
                                    <div class="layui-input-block">
                                        <textarea id="demo" name="teacher_intro" class="layui-textarea">{$teacher.teacher_intro}</textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('teacher_state')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="teacher_state" value="1" title="{:lang('open')}"  checked="">
                                        {php}$checked = $teacher['teacher_state'] ? '' : 'checked';{/php}
                                        <input type="radio" name="teacher_state" value="0" title="{:lang('close')}" {$checked}>
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
                                }).use(['index', 'admin', 'form', 'upload', 'layedit'], function(){
                                    var table = layui.table
                                        ,admin = layui.admin
                                        ,form = layui.form
                                        ,upload = layui.upload;
                                    form.render();

                                    // var layedit = layui.layedit;
                                    // var layeditindex = layedit.build('demo', {
                                    //     tool: [
                                    //
                                    //     ]
                                    // }); //建立编辑器
                                    // layedit.sync(layeditindex)

                                    var uploadInst = upload.render({
                                        elem: '#test1'
                                        ,url: '{:Url('asset/upload')}'
                                        ,before: function(obj){
                                        }
                                        ,done: function(res){
                                            //上传成功
                                            //如果上传失败
                                            if(res.code){
                                                $('#demo1').attr('src', res.data.link);
                                                $('#teacher_pic').val(res.data.url);
                                            } else {
                                                return layer.msg('上传失败');
                                            }
                                        }
                                        ,error: function(){
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
