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
                                {if condition="$Think.get.position_id"}
                                <li><a href="{:Url('ad/add', ['position_id'=>$position_id])}">{:lang('add')}</a></li>
                                {/if}
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <table class="layui-hide" id="list" lay-filter="list"></table>
                            <script type="text/html" id="toolbar">
                                <a class="layui-btn layui-btn-xs" lay-event="edit">{:lang('edit')}</a>
                                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">{:lang('del')}</a>
                            </script>
                            <script type="text/html" id="checkboxTpl">
                                <input type="checkbox" name="status" value="{{d.ad_id}}" title="{:lang('open')}" lay-filter="lockDemo" {{ d.status == 1 ? 'checked' : '' }}>
                            </script>
                        </div>
                        <script>
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).use(['index', 'admin', 'table', 'layer', 'form'], function(){
                                    var table = layui.table
                                            ,form = layui.form
                                            ,admin = layui.admin;

                                    table.render({
                                        elem: '#list'
                                        ,url:'{:Url("ad/index", ['position_id'=>$position_id])}'
                                        // ,toolbar: '#toolbar'
                                        ,totalRow: true
                                        ,cols: [[
                                            {field:'ad_id', width:80, title: 'ID'}
                                            ,{field:'listorder', width:80, title: '{:lang('listorder')}', edit: 'number'}
                                            ,{field:'position_name', width:120, title: '{:lang('position_name')}'}
                                            ,{field:'ad_title', width:200, title: '{:lang('ad_title')}'}
                                            ,{field:'status', width:120,  templet: '#checkboxTpl', title: '{:lang('status')}'}
                                            ,{fixed: 'right', title:'操作', toolbar: '#toolbar', width:120}
                                        ]]
                                        ,page: true
                                        ,done: function(res, page, count) {
                                        }
                                    });

                                    table.on('edit(list)', function(obj){
                                        var $this = $(this);
                                        var o = [];
                                        o[obj.field]= obj.value;
                                        var data = Object.assign({}, o)
                                        data.ad_id = obj.data.ad_id
                                        admin.req({
                                            url: '{:Url("ad/ajaxedit")}',
                                            type: "post",
                                            data: data,
                                            done: function(e){
                                                if (!e.code) {
                                                    layer.msg(e.msg)
                                                }
                                            }
                                        })
                                    });
                                    //监听锁定操作
                                    form.on('checkbox(lockDemo)', function(obj){
                                        var data = {ad_id: obj.value};
                                            data.status = obj.elem.checked ? 1 : 0

                                        admin.req({
                                            url: '{:Url("ad/ajaxedit")}',
                                            type: "post",
                                            data: data,
                                            done: function(e){
                                                layer.msg(e.msg)
                                            }
                                        })
                                    });
                                    table.on('tool(list)', function(obj){
                                        var data = obj.data,
                                                ad_id =  obj.data.ad_id
                                        if(obj.event === 'del'){
                                            layer.confirm('真的删除行么', function(index){
                                                admin.req({
                                                    url: '{:Url("ad/del")}',
                                                    type: "post",
                                                    data: {ad_id: ad_id},
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
                                            window.location.href =  '{:Url('ad/edit')}'+'?ad_id='+ad_id;
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
