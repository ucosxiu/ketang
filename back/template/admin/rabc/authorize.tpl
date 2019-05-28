{extend name="public/base" /}
{block name="main-content"}
    <link rel="stylesheet" type="text/css" href="__STATIC__/treeTable/treeTable.css">
    <style>
        .layui-form-checkbox{
            height: 16px;
            line-height: 16px;
            padding-right: 16px;
            display: none;
        }
        .layui-form input[type=checkbox]{
            display: inline-block;
        }
        .layui-form-checkbox i {
            width: 16px;
            height: 16px;
            font-size: 13px;
        }
    </style>
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li class="layui-this">
                <a href="javascript:void(0);">{:lang('rabc')}</a>
            </li>
        </ul>
    </div>
    <div class="layui-form">
        <form class="layui-form" action="" method="post" onsubmit="return false">
            <table class="table treeTable">
                <tbody>
                {:$privs}
                </tbody>
            </table>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="ajax-submit">{:lang('submit')}</button>
                </div>
            </div>
        </form>
    </div>
    <script src="__STATIC__/treeTable/treeTable.js"></script>
    <script>
        $(function(){
            layui.config({
                base: '__STATIC__/system/js/' //静态资源所在路径,
                ,version: '1.2.1'
            }).use('index', 'admin', 'form', function(){
                var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
                form.render();
            });

            $(".treeTable").treeTable({
                indent: 20
            });
        })

        function checknode(obj) {
            var chk = $("input[type='checkbox']");
            var count = chk.length;
            var num = chk.index(obj);
            var level_top = level_bottom = chk.eq(num).attr('level')
            for (var i = num; i >= 0; i--) {
                var le = chk.eq(i).attr('level');
                if (eval(le) < eval(level_top)) {
                    chk.eq(i).prop("checked", true);
                    var level_top = level_top - 1;
                }
            }
            for (var j = num + 1; j < count; j++) {
                var le = chk.eq(j).attr('level');
                if (chk.eq(num).prop("checked")) {
                    if (eval(le) > eval(level_bottom)) chk.eq(j).prop("checked", true);
                    else if (eval(le) == eval(level_bottom)) break;
                }
                else {
                    if (eval(le) > eval(level_bottom)) chk.eq(j).prop("checked",false);
                    else if (eval(le) == eval(level_bottom)) break;
                }
            }
        }
    </script>
{/block}
