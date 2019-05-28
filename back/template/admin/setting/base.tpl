{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <div class="layui-tab">
                                {include file="public/admin_items" /}
                            </div>
                        </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <form class="layui-form" action="" method="post">
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('site_name')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_name" lay-verify="" autocomplete="off" class="layui-input" value="{$list_config.site_name}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('site_logo')}</label>
                                    <div class="layui-input-block">
                                        <div class="layui-upload">
                                            <button type="button" class="layui-btn" id="test1">上传图片</button>
                                            <div class="upload-preview show" id="show" {if condition="$list_config.site_logo eq ''"}style="display: none"{/if}>
                                                <i style="font-size: 30px" class="picture layui-picture layui-icon layui-icon-picture" data-src="__ROOT__/{$list_config.site_logo}"></i>
                                                <input type="hidden" name="site_logo" id="site_logo" value="{$list_config.site_logo}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('icp_number')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="icp_number" lay-verify="" autocomplete="off" class="layui-input" value="{$list_config.icp_number}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('site_phone')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_phone" lay-verify="" autocomplete="off" class="layui-input" value="{$list_config.site_phone}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('site_tel400')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_tel400" lay-verify="" autocomplete="off" class="layui-input" value="{$list_config.site_tel400}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('site_email')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_email" lay-verify="" autocomplete="off" class="layui-input" value="{$list_config.site_email}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('flow_static_code')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="flow_static_code" lay-verify="" autocomplete="off" class="layui-input" value="{$list_config.flow_static_code}">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">{:lang('site_state')}</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="site_state" value="1" title="{:lang('open')}"  {if condition="$list_config.site_state eq 1"}checked=""{/if}>
                                        <input type="radio" name="site_state" value="0" title="{:lang('close')}" {if condition="$list_config.site_state eq 0"}checked=""{/if}>
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
                                }).use(['index', 'admin', 'layer', 'upload'], function(){
                                    var admin = layui.admin;
                                    var upload = layui.upload;

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

                                  upload.render({
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
    </div
{/block}
