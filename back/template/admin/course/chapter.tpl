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
                                <li><a href="javascript:history.back()">{:lang('back')}</a></li>
                                <li class="layui-this">{:lang('chapter')}</li>
                            </ul>
                        </div>
                    </div>
                    <style>
                        .table_cell{
                            text-align: left;
                            padding-right: 20px;
                        }
                        .red{
                            color: red !important;
                        }
                    </style>
                    <div class="layui-card-body">
                        <div class="layui-form">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item layui-form-text layui-col-lg12">
                                    <label class="layui-form-label">{:lang('chapter')}</label>
                                    <div class="layui-input-block">
                                        <style>
                                            .table_cell{
                                                display: inline-block;
                                            }
                                            .js_field_tr {
                                                padding-right: 20px;
                                                padding-bottom: 10px;
                                            }
                                        </style>
                                        <script type="text/html" id="tr">
                                            <tr class="js_field_item" data-id="0">
                                                <td class="js_field_tr" style="width: 200px">
                                                    <input type="text" class="layui-input layui-col-lg10" name="new_chapter_names[{{d.id}}]" placeholder="课时名称" lay-verify="required">
                                                </td>
                                                <td class="js_field_tr" style="width: 250px">
                                                    <input type="text" class="layui-input layui-col-lg10" name="new_chapter_links[{{d.id}}]" placeholder="链接" lay-verify="required">
                                                    <button type="button" class="layui-btn  js_field_tr_file" id="new_{{ d.id }}">上传视频</button>
                                                </td>
                                                <td class="js_field_tr" style="width: 250px">
                                                    <input type="text" class="layui-input layui-col-lg10" name="new_chapter_attachments[{{d.id}}]" placeholder="附件" lay-verify="">
                                                    <button type="button" class="layui-btn  js_field_tr_file" id="new_attachment_{{ d.id }}">上传附件</button>
                                                </td>
                                                <td class="js_field_tr" style="width: 50px">
                                                       <span class="add_remove_opr_box">
        <a class="add_remove_opr icon_remove js_remove_api_field"></a>
      </span>
                                                </td>
                                            </tr>
                                        </script>
                                        <table class="table  layui-col-lg12" cellspacing="0">
                                            <thead class="thead layui-col-lg12">
                                            <tr class="layui-col-lg12" style="height: 36px;line-height: 36px;">
                                                <th class="table_cell" style="width: 200px">课时名称</th>
                                                <th class="table_cell" style="width: 250px">链接</th>
                                                <th class="table_cell" style="width: 250px">附件</th>
                                                <th class="table_cell" style="width: 50px"></th>
                                            </tr>
                                            </thead>
                                            <tbody class="tbody" id="tbody">
                                            {foreach name="course.chapter" item="item"}
                                                <tr class="js_field_item" data-id="0">
                                                    <td class="js_field_tr" style="width: 200px">
                                                        <input type="text" class="layui-input layui-col-lg10" name="old_chapter_names[{$item.chapter_id}]" placeholder="课时名称" lay-verify="required" value="{$item.chapter_name}">
                                                    </td>
                                                    <td class="js_field_tr" style="width: 250px">
                                                        <input type="text" class="layui-input layui-col-lg10" name="old_chapter_links[{$item.chapter_id}]" placeholder="链接" lay-verify="required" value="{$item.chapter_link}">
                                                        <button type="button" class="layui-btn  js_field_tr_file" id="old_{$item.chapter_id}">上传视频</button>
                                                    </td>
                                                    <td class="js_field_tr" style="width: 250px">
                                                        <input type="text" class="layui-input layui-col-lg10" name="old_chapter_attachments[{$item.chapter_id}]" placeholder="附件" lay-verify="" value="{$item.chapter_attachment}">
                                                        <button type="button" class="layui-btn  js_field_tr_file" id="old_attachment_{$item.chapter_id}">上传附件</button>
                                                    </td>
                                                    <td class="js_field_tr" style="width: 50px">
                                                       <span class="add_remove_opr_box">
        <a class="add_remove_opr icon_remove js_remove_api_field"></a>
      </span>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="layui-form-item layui-form-text layui-col-lg12">
                                        <label class="layui-form-label"></label>
                                        <div class="layui-input-block">
                                            <button type="button" class="layui-btn layui-btn-normal" id="add-input"><i class="layui-icon"></i>添加课时</button>
                                        </div>
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
                            var id = 0
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).extend({
                                }).use(['index', 'admin', 'form', 'upload', 'laytpl'], function(){
                                    var table = layui.table
                                        ,admin = layui.admin
                                        ,form = layui.form
                                        ,upload = layui.upload
                                        ,laytpl = layui.laytpl;
                                    form.render();

                                    function up(id) {
                                        upload.render({
                                            elem: '#' + id
                                            ,url: '{:Url('course/up')}'
                                            ,accept: 'file'
                                            ,before: function(obj){
                                                layer.load(); //上传loading
                                            }
                                            ,done: function(res, index){
                                                layer.closeAll('loading'); //关闭loading
                                                var item = this.item;
                                                //如果上传失败
                                                if(res.code){
                                                    if ( res.data.url) {
                                                        $(item).parents('.js_field_tr').find('input.layui-input').val(res.data.url + '')
                                                    }
                                                } else {
                                                    return layer.msg('上传失败');
                                                }
                                                //上传成功
                                            }
                                            ,error: function(){
                                            }
                                        });
                                    }

                                    $(document).on('click','#add-input',function(){
                                        var tr = $('#tr').html();
                                        ++id;
                                        laytpl(tr).render({
                                            id: id
                                        }, function(string){
                                            $('.tbody').append(string);
                                            up('new_' + id)
                                            up('new_attachment_' + id)
                                        })

                                        $('.add_remove_opr').on('click', function () {
                                            $(this).parents('tr').remove()
                                        })
                                    });

                                    $('.add_remove_opr').on('click', function () {
                                        $(this).parents('tr').remove()
                                    })

                                    $('.js_field_tr_file').each(function (index, ele) {
                                        var id = $(ele).attr('id')
                                        up(id)
                                    })
                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
