{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card" style="padding: 15px 0">
                        <div class="layui-card-header">
                            <div class="layui-tab">
                                {include file="public/admin_items" /}
                            </div>
                        </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('seo_title')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="seo_title" lay-verify="" autocomplete="off" class="layui-input" value="{if isset($list_config.seo_title)}{$list_config.seo_title}{/if}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('seo_keywords')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="seo_keywords" lay-verify="" autocomplete="off" class="layui-input" value="{if isset($list_config.seo_keywords)}{$list_config.seo_keywords}{/if}">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">{:lang('seo_description')}</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="seo_description" lay-verify="" autocomplete="off" class="layui-input" value="{if isset($list_config.seo_description)}{$list_config.seo_description}{/if}">
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
                                    ,url: '{:Url('system/asset/upload')}'
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
