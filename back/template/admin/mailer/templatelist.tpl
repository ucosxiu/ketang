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
                                <li class="layui-this">{:lang('templatelist')}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-btn-container">
                            <a class="layui-btn layui-btn-sm" href="{:Url('mailer/add')}">添加</a>
                        </div>
                        <div class="layui-form ">
                            <table class="layui-hide" id="list" lay-filter="list"></table>
                            <script type="text/html" id="toolbar">
                                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                            </script>
                        </div>
                        <script>
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).use(['index', 'admin', 'table', 'layer'], function(){
                                    var table = layui.table
                                            ,admin = layui.admin;

                                    table.render({
                                        elem: '#list'
                                        ,url:'{:Url("mailer/templatelist")}'
//                                        ,toolbar: '#toolbar'
                                        ,totalRow: true
                                        ,cols: [[
                                            {field:'template_id', width:80, title: 'ID'}
                                            ,{field:'template_code', width:200, title: '{:lang('template_code')}'}
                                            ,{field:'template_subject', width:200, title: '{:lang('subject')}'}
                                            ,{field:'status', width:100, title: '{:lang('status')}'}
                                            ,{fixed: 'right', title:'操作', toolbar: '#toolbar', width:120}
                                        ]]
                                        ,page: true
                                        ,done: function(res, page, count) {
                                            $("[data-field='status']").children().each(function(){
                                                if($(this).text()=='1'){
                                                    $(this).text('{:lang('open')}')
                                                }else if($(this).text()=='0'){
                                                    $(this).text('{:lang('clse')}')
                                                }
                                            })
                                        }
                                    });

                                    table.on('tool(list)', function(obj){
                                        var data = obj.data,
                                                template_id =  obj.data.template_id
                                        if(obj.event === 'del'){
                                            layer.confirm('真的删除行么', function(index){
                                                admin.req({
                                                    url: '{:Url("manager/mailer/del")}',
                                                    type: "post",
                                                    data: {template_id: template_id},
                                                    done: function(e){
                                                        if (e.code) {
                                                            obj.del();
                                                            layer.close(index);
                                                        } else {
                                                            layui.msg(e.msg)
                                                            layer.close(index);
                                                        }
                                                    }
                                                })
                                            });
                                        } else if(obj.event === 'edit'){
                                            window.location.href =  '{:Url('mailer/edit')}'+'?template_id='+template_id;
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
