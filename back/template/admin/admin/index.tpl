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
                                <li>
                                    <a href="{:Url('admin/add')}">{:lang('add')}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col width="150">
                                    <col width="200">
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{:lang('username')}</th>
                                    <th>{:lang('belong_role')}</th>
                                    <th>{:lang('lastloginip')}</th>
                                    <th>{:lang('lastlogintime')}</th>
                                    <th>{:lang('email')}</th>
                                    <th>{:lang('op')}</th>
                                </tr>
                                </thead>
                                <tbody>
                                {volist name="list" id="vo" empty="暂时没有数据" }
                                    <tr>
                                        <td>{$vo.adminid}</td>
                                        <td>{$vo.username}</td>
                                        <td>{$vo.role.rolename}</td>
                                        <td>{$vo.lastloginip}</td>
                                        <td>{$vo.lastlogintime|date="Y-m-d H:i:s"}</td>
                                        <td>{$vo.email}</td>
                                        <td>
                                            <a href="{:Url('admin/edit', ['id'=>$vo.adminid])}">{:lang('edit')}</a>
                                            <a href="{:Url('admin/del', ['id'=>$vo.adminid])}" class="js-ajax-delete" role="button" data-toggle="modal">{:lang('del')}</a>
                                        </td>
                                    </tr>
                                {/volist}
                                </tbody>
                            </table>
                            {$page}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            layui.config({
                base: '__STATIC__/system/js/' //静态资源所在路径,
                ,version: '1.2.1'
            }).use(['index', 'admin', 'form', 'upload'], function(){
            });
        })
    </script>
{/block}
