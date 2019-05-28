{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('list')}</li>
                                <li ><a href="{:Url('adposition/add')}">{:lang('add')}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <table class="layui-hide" id="list" lay-filter="list"></table>
                            <script type="text/html" id="toolbar">
                                <a class="layui-btn layui-btn-xs" lay-event="view">{:lang('view')}</a>
                                <a class="layui-btn layui-btn-xs" lay-event="edit">{:lang('edit')}</a>
                                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">{:lang('del')}</a>
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
                                        ,url:'{:Url("adposition/index")}'
//                                        ,toolbar: '#toolbar'
                                        ,totalRow: true
                                        ,cols: [[
                                            {field:'position_id', width:80, title: 'ID'}
                                            ,{field:'position_name', width:200, title: '{:lang('position_name')}'}
                                            ,{field:'position_model', width:200, title: '{:lang('position_model')}'}
                                            ,{field:'ad_width', width:100, title: '{:lang('ad_width')}'}
                                            ,{field:'ad_height', width:100, title: '{:lang('ad_height')}'}
                                            ,{fixed: 'right', title:'操作', toolbar: '#toolbar', width:180}
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
                                                position_id =  obj.data.position_id
                                        if(obj.event === 'del'){
                                            layer.confirm('真的删除行么', function(index){
                                                admin.req({
                                                    url: '{:Url("adposition/del")}',
                                                    type: "post",
                                                    data: {position_id: position_id},
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
                                            window.location.href =  '{:Url('adposition/edit')}'+'?position_id='+position_id;
                                        } else if (obj.event === 'view') {
                                            window.location.href =  '{:Url('ad/index')}'+'?position_id='+position_id;
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
