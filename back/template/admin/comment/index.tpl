{extend name="public/base" /}
{block name="main-content"}
    <style>
        .hide{display:none}
        .listorder{
            position: absolute;
            width: 100%;
            height: 100%;
            top:0;
            left: 0;
            line-height: 40px;
            padding-left: 10px;
            box-sizing: border-box;
        }
    </style>
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('list')}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <table class="layui-table" lay-filter="demo" id="LAY_table"></table>
                            <script type="text/html" id="toolbar">

                            </script>
                            <script type="text/html" id="activity_name">
                                {{ d.activity.activity_name }}
                            </script>
                            <script type="text/html" id="school_name">
                                {{ d.school.school_name }}
                            </script>
                            <script type="text/html" id="grade_name">
                                {{ d.grade.grade_name }}
                            </script>
                            <script type="text/html" id="start_time">
                                {{ layui.util.toDateString(d.start_time * 1000, 'yyyy-MM-dd') }}
                            </script>
                            <script type="text/html" id="end_time">
                                {{ layui.util.toDateString(d.end_time * 1000, 'yyyy-MM-dd') }}
                            </script>
                            <script type="text/html" id="departure_time">
                                {{ layui.util.toDateString(d.departure_time * 1000, 'yyyy-MM-dd HH:mm:ss') }}
                            </script>
                            <script type="text/html" id="status">
                                {{ d.order_state == 0 ? '已取消' : (d.order_state == 0 ? '<span style="color: red;">待支付</span>' : '<span style="color: green;">已完成</span>') }}
                            </script>
                            <script type="text/html" id="recommend">
                                <!-- 这里的 checked 的状态只是演示 -->
                                <input type="checkbox" name="recommend" value="{{d.id}}" title="推荐" lay-filter="recommend" {{ d.id == 10006 ? 'checked' : '' }}>
                            </script>
                        </div>
                        <script>
                            layui.config({
                              base: '__STATIC__/system/js/' //静态资源所在路径,
                              ,version: '1.2.1'
                            }).use(['jquery', 'index', 'admin', 'table', 'form', 'util'], function(){
                                var table = layui.table
                                    ,admin = layui.admin
                                    ,form = layui.form;
                                //方法级渲染
                                form.render(null, 'app-content-list');
                                //监听搜索
                                form.on('submit(LAY-app-contlist-search)', function(data){
                                    var field = data.field;

                                    //执行重载
                                    table.reload('testReload', {
                                        where: field
                                        ,page: {
                                            curr: 1 //重新从第 1 页开始
                                        }
                                    });
                                });
                                table.render({
                                    elem: '#LAY_table'
                                    ,url:'{:Url("comment/index")}'
                                    // ,toolbar: '#toolbar'
                                    ,totalRow: true
                                    ,cols: [[
                                        {field:'comment_id', width:60, title: 'ID'}
                                        ,{field:'mobile', width:120, title: '{:lang('手机号码')}'}
                                        ,{field:'content', width:300, title: '{:lang('留言内容')}'}
                                        ,{fixed: 'right', width:200, title:'操作', toolbar: '#toolbar'}
                                    ]]
                                    ,id: 'testReload'
                                    ,page: true
                                    ,done: function(res, page, count) {
                                    }
                                });
                                table.on('tool(demo)', function(obj){
                                    var order_id =  obj.data.order_id
                                    if(obj.event === 'view'){
                                        window.location.href = '{:Url('order/view')}' + '?id=' + order_id
                                    }
                                });
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div
{/block}
