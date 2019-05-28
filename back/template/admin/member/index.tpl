{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid" style="padding: 0">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('list')}</li>
                            </ul>
                        </div>
                        <div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="app-content-list">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">会员状态 </label>
                                    <div class="layui-input-inline">
                                        <select name="is_vip" id="voucher_id">
                                            <option value="-1">全部</option>
                                            <option value="0">非会员</option>
                                            <option value="1">会员</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">用户名称 </label>
                                    <div class="layui-input-inline">
                                        <input type="tel" name="member_name" lay-verify="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">手机号 </label>
                                    <div class="layui-input-inline">
                                        <input type="tel" name="mobile" lay-verify="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <button class="layui-btn layuiadmin-btn-list" lay-submit="" lay-filter="LAY-app-contlist-search">
                                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form ">
                            <table class="layui-table" lay-filter="demo" id="LAY_table">
                            <script type="text/html" id="address">
                                {{ d.province }}{{ d.city }}
                            </script>
                            <script type="text/html" id="status">
                                <!-- 这里的 checked 的状态只是演示 -->
                                <input type="checkbox" name="lock" value="{{d.member_id}}" title="禁止登录" lay-filter="lockDemo" {{ d.status == 0 ? 'checked' : '' }}>
                            </script>
                            <script type="text/html" id="is_vip">
                                {{ d.is_vip ? '是' : '否'}}
                            </script>
                            <script type="text/html" id="barDemo">
                                <a class="layui-btn layui-btn-xs" lay-event="order_view">{:lang('order_view')}</a>
                                {{# if (d.is_vip) { }}
                                <a class="layui-btn layui-btn-xs" lay-event="unbind">{:lang('unbind')}</a>
                                {{# } }}
                            </script>
                        </div>
                        <script>
                        layui.use(['index', 'admin', 'table', 'form'], function(){
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

                            //方法级渲染
                            table.render({
                                elem: '#LAY_table'
                                ,url: '{:Url('member/index')}'
                                ,cols: [[
                                {field:'member_id', title: 'ID', width:80}
                                ,{field:'member_name', title: '{:lang('member_name')}', width:100}
                                ,{field:'nickname', title: '{:lang('nickname')}', width:100}
                                ,{field:'mobile', title: '{:lang('mobile')}', width:100}
                                ,{templet:'#address', title: '{:lang('address')}', width:150}
                                ,{templet:'#status', title: '{:lang('status')}', width:150}
                                ,{templet:'#is_vip', title: '{:lang('is_vip')}', width:100}
                                ,{fixed: 'right', width:180, title: '{:lang('op')}', toolbar: '#barDemo'}
                                ]]
                                ,id: 'testReload'
                                ,page: true
                                ,treetable: true
                            });
                            //监听锁定操作
                            form.on('checkbox(lockDemo)', function(obj){
                                var data = {}
                                    data.id = obj.value;
                                if (obj.elem.checked) {
                                    data.status = 0
                                } else {
                                    data.status = 1
                                }
                                admin.req({
                                    url: '{:Url("member/status")}',
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
                                } else if (obj.event === 'unbind') {
                                    layer.confirm('确认解绑?',function(index){
                                        admin.req({
                                          url: '{:Url("Member/unbind")}',
                                          type: "post",
                                          data: {id: obj.data.member_id},
                                          done: function(e){
                                              if (e.code) {
                                                  table.reload('testReload')
                                                  layer.close(index);
                                              } else {
                                                  layer.msg(e.msg)
                                                  layer.close(index);
                                              }
                                          }
                                        })
                                    });
                                } else if(obj.event === 'order_view'){
                                    window.location.href =  '{:Url('order/index')}'+'?member_id='+obj.data.member_id;
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
