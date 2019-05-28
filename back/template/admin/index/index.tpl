{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid" style="padding: 0">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-form ">
                        </div>
                        <script>
                          layui.use(['index', 'admin', 'table'], function(){
                          });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div
{/block}
