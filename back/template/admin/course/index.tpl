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
                                    <a href="{:Url('course/add')}">{:lang('add')}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="layui-form ">
                            <table class="layui-table" lay-filter="demo" id="LAY_table">
                                <script type="text/html" id="goods_type">
                                   {{ d.goods_type == 1 ? '视频' : d.goods_type == 2 ? '文档' : d.goods_type == 3 ? '音频' : '报名'}}
                                </script>
                                <script type="text/html" id="is_hot">
                                    <!-- 这里的 checked 的状态只是演示 -->
                                    <input type="checkbox" name="lock" value="{{d.goods_id}}" title="是" lay-filter="lockDemo" {{ d.is_hot == 1 ? 'checked' : '' }}>
                                </script>
                                <script type="text/html" id="is_recommend">
                                    <!-- 这里的 checked 的状态只是演示 -->
                                    <input type="checkbox" name="lock" value="{{d.goods_id}}" title="是" lay-filter="lockDemo1" {{ d.is_recommend == 1 ? 'checked' : '' }}>
                                </script>
                                <script type="text/html" id="goods_price">
                                    {{ d.goods_price ? d.goods_price : '免费'}}
                                </script>
                                <script type="text/html" id="barDemo">
                                    <a class="layui-btn layui-btn-xs" lay-event="edit">{:lang('edit')}</a>
                                    {{# if (d.goods_type !== 0) { }}
                                    <a class="layui-btn layui-btn-xs" lay-event="chapter">{:lang('goods_chapter')}</a>
                                    {{# } }}
                                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">{:lang('del')}</a>
                                </script>
                        </div>
                        <script>
                            layui.use(['index', 'admin', 'table', 'form'], function(){
                                var table = layui.table
                                    ,admin = layui.admin
                                    ,form = layui.form;
                                //方法级渲染
                                table.render({
                                    elem: '#LAY_table'
                                    ,url: '{:Url('course/index')}'
                                    ,cols: [[
                                        {field:'goods_id', title: 'ID', width:80}
                                        ,{field:'goods_name', title: '{:lang('goods_name')}', width:200}
                                        ,{templet:'#goods_type', title: '{:lang('goods_type')}', width:80}
                                        ,{templet:'#goods_price', title: '{:lang('goods_price')}', width:80}
                                        ,{field:'lock', title:'是否热门', width:110, templet: '#is_hot', unresize: true}
                                        ,{field:'lock', title:'是否推荐', width:110, templet: '#is_recommend', unresize: true}
                                        ,{width:120, title: '{:lang('goods_pic')}',templet:function(res){
                                            return '<img class="preview" src="__ROOT__/'+res.goods_pic+'">'
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
                                //监听锁定操作
                                form.on('checkbox(lockDemo)', function(obj){
                                    var data = {}
                                        data.id = obj.value;
                                    if (obj.elem.checked) {
                                        data.is_hot = 1
                                    } else {
                                        data.is_hot = 0
                                    }
                                    admin.req({
                                        url: '{:Url("course/hot")}',
                                        type: "post",
                                        data: data,
                                        done: function(e){
                                            if (e.code) {

                                            } else {
                                                layer.msg(e.msg)
                                                table.reload('testReload')
                                            }
                                        }
                                    })
                                });
                            //监听锁定操作
                            form.on('checkbox(lockDemo1)', function(obj){
                                var data = {}
                                    data.id = obj.value;
                                if (obj.elem.checked) {
                                    data.is_recommend = 1
                                } else {
                                    data.is_recommend = 0
                                }
                                admin.req({
                                    url: '{:Url("course/recommend")}',
                                    type: "post",
                                    data: data,
                                    done: function(e){
                                        if (e.code) {

                                        } else {
                                            layer.msg(e.msg)
                                            table.reload('testReload')
                                        }
                                    }
                                })
                            });
                                table.on('tool(demo)', function(obj){
                                    var data = obj.data;
                                    if (obj.event === 'preview') {
                                        if (data.avatar) {

                                        }
                                    } else if (obj.event === 'del') {
                                        layer.confirm('真的删除行么', function(index){
                                            admin.req({
                                                url: '{:Url("course/del")}',
                                                type: "post",
                                                data: {id: obj.data.goods_id},
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
                                        window.location.href =  '{:Url('course/edit')}'+'?id='+obj.data.goods_id;
                                    } else if(obj.event === 'chapter'){
                                        window.location.href =  '{:Url('course/chapter')}'+'?id='+obj.data.goods_id;
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
