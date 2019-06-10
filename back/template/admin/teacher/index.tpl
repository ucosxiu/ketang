{extend name="public/base" /}
{block name="main-content"}
    <style>
        .layui-table img{
            max-height: 100%;
        }
    </style>
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('list')}</li>
                                <li>
                                    <a href="{:Url('teacher/add')}">{:lang('add')}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="layui-form ">
                            <table class="layui-table" lay-filter="demo" id="LAY_table">
                                <script type="text/html" id="barDemo">
                                    <a class="layui-btn layui-btn-xs" lay-event="edit">{:lang('edit')}</a>
                                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">{:lang('del')}</a>
                                </script>
                        </div>
                        <script>
                            layui.use(['index', 'admin', 'table'], function(){
                                var table = layui.table,
                                    admin = layui.admin;
                                //方法级渲染
                                table.render({
                                    elem: '#LAY_table'
                                    ,url: '{:Url('teacher/index')}'
                                    ,cols: [[
                                        {field:'teacher_id', title: 'ID', width:80}
                                        ,{field:'teacher_name', title: '{:lang('teacher_name')}', width:200}
                                        ,{width:120, title: '{:lang('teacher_pic')}',templet:function(res){
                                            return '<img class="preview" src="__ROOT__/'+res.teacher_pic+'">'
                                            }}
                                        ,{fixed: 'right', width:180, title: '{:lang('op')}', sort: true, toolbar: '#barDemo'}
                                    ]]
                                    ,id: 'testReload'
                                    ,page: true
                                    ,done: function(res, page, count) {
                                        $('.preview').bind('click', function () {
                                            layer.open({
                                                type: 1,
                                                title: false,
                                                closeBtn: 0,
                                                area: ['auto', 'auto'],
                                                shadeClose: true,
                                                content: '<div class="preview-box">'+$(this)[0].outerHTML+'</div>'
                                            });
                                        })
                                    }
                                });
                                table.on('tool(demo)', function(obj){
                                    var data = obj.data;
                                    if (obj.event === 'preview') {
                                        if (data.avatar) {

                                        }
                                    } else if (obj.event === 'del') {
                                        layer.confirm('真的删除行么', function(index){
                                            admin.req({
                                                url: '{:Url("teacher/del")}',
                                                type: "post",
                                                data: {id: obj.data.teacher_id},
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
                                        window.location.href =  '{:Url('teacher/edit')}'+'?id='+obj.data.teacher_id;
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div
{/block}
