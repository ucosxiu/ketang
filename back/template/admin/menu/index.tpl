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
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li class="layui-this">{:lang('list')}</li>
                                <li>
                                    <a href="{:Url('menu/add')}">{:lang('add')}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="layui-form ">
                            <table class="layui-table" lay-filter="demo" id="LAY_table">
                                <table class="layui-table" lay-filter="demo" id="tree_table">
                            <script type="text/html" id="barDemo">
                                <a class="layui-btn layui-btn-xs" lay-event="add">{:lang('add')}</a>
                                <a class="layui-btn layui-btn-xs" lay-event="edit">{:lang('edit')}</a>
                                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">{:lang('del')}</a>
                            </script>
                        </div>
                        <script>
                          layui.use(['jquery', 'index', 'admin', 'table', 'treetable'], function(){
                            var table = layui.table,
                              admin = layui.admin
                              ,treetable = layui.treetable;
                            //方法级渲染
                            table.render({
                              elem: '#LAY_table'
                              ,url: '{:Url('menu/index')}'
                              ,cols: [
                              ]
                              ,done: function(res, page, count) {
                                var data = res.data
                                treetable.render({
                                  elem: '#tree_table',
                                  data: data,
                                  field: 'id',
                                  space: 4,
                                  cols: [
                                      {field:'listorder', title: '{:lang('listorder')}', width:80, fixed: true,
                                      template: function(item) {
                                        return '<span data-key="'+item.id+'"class="listorder">'+item.listorder+'</span>';
                                      }}
                                    ,{field:'id', title: 'ID', width:80,fixed: true}
                                    ,{field:'name', title: '{:lang('menuname')}'}
                                    ,{field:'app', title: '{:lang('app')}'}
                                    ,{field:'status', title: '{:lang('status')}', width:80}
                                    ,{field: 'actions',title: '操作', width: 180,
                                      template: function(item) {
                                        var tem = [];
                                        tem.push('<a class="add-child" lay-filter="add">添加子级</a>');
                                        tem.push('<a lay-filter="edit">编辑</a>');
                                        tem.push('<a lay-filter="del">删除</a>');
                                        return tem.join(' ')
                                      },
                                    },
                                  ]
                                });
                                treetable.on('treetable(add)',function(data){
                                  window.location.href = '{:Url('menu/add')}'+'?parentid='+data.item.id
                                })

                                treetable.on('treetable(edit)',function(data){
                                  window.location.href = '{:Url('menu/edit')}'+'?id='+data.item.id
                                })
                                treetable.on('treetable(del)',function(data){
                                  layer.confirm('真的删除行么', function(index){
                                    admin.req({
                                      url: '{:Url("menu/del")}',
                                      type: "post",
                                      data: {id: data.item.id},
                                      done: function(e){
                                        if (e) {
                                          $('*[data-id="'+data.item.id+'"]').remove();
                                        } else {
                                          layer.msg('保存失败')
                                        }

                                      }
                                    })
                                    layer.close(index);
                                  });
                                })
                                $('.listorder').on('click', function() {
                                  var parent = $(this);
                                  var id = $(this).data('key'),
                                    oldval = $(this).text();
                                  if (!$(this).find('.layui-table-edit').length) {
                                    $(this).append('<input class="layui-table-edit" type="number" min="0" max="10000"value="" style="padding: 0">').find('.layui-table-edit').focus().val(oldval).on('keyup', function() {
                                      var val = $(this).val();
                                      if (val < 0) {
                                        $(this).val(0)
                                      }
                                      if (val > 10000) {
                                        $(this).val(10000)
                                      }
                                    }).on('blur', function(){
                                      var $this = this,
                                        val = $(this).val();
                                      admin.req({
                                        url: '{:Url("menu/listorders")}',
                                        type: "get",
                                        data: {id: id, val: val},
                                        done: function(e){
                                          $this.remove();
                                          if (e) {
                                            parent.text(val)
                                          } else {
                                            parent.text(oldval);
                                            layer.msg('保存失败')
                                          }

                                        }
                                      })
                                    })
                                  }
                                });
                              }
                            })
                          });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
