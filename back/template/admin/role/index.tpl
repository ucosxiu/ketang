{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card"  style="padding: 15px 0">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('list')}</li>
                                <li>
                                    <a href="{:Url('role/add')}">{:lang('add')}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="layui-form ">
                            <table class="layui-table" lay-filter="list" id="LAY_table">
                            <script type="text/html" id="barDemo">
                                <a class="layui-btn layui-btn-xs" lay-event="rabc">{:lang('rabc')}</a>
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
                                    ,url: '{:Url('role/index')}'
                                    ,cols: [[
                                        {field:'roleid', title: '{:lang('ID')}', width:80},
                                        {field:'rolename', title: '{:lang('rolename')}', width:120}
                                        ,{field:'description', title: '{:lang('description')}', width:200}
                                        ,{fixed: 'right', width:180, title: '{:lang('op')}', sort: true, toolbar: '#barDemo'}
                                    ]]
                                    ,id: 'testReload',
                                    treetable: true
                                });
                                table.on('tool(list)', function(obj){
                                    var data = obj.data,
                                        roleid =  obj.data.roleid
                                    if(obj.event === 'del'){
                                        layer.confirm('真的删除行么', function(index){
                                            admin.req({
                                                url: '{:Url("role/del")}',
                                                type: "post",
                                                data: {id: roleid},
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
                                        window.location.href =  '{:Url('role/edit')}'+'?id='+roleid;
                                    } else if(obj.event === 'rabc'){
                                        window.location.href =  '{:Url('rabc/authorize')}'+'?id='+roleid;
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
