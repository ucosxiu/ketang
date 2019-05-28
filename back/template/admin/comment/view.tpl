{extend name="public/base" /}
{block name="main-content"}
    <div class="layui-fluid">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div class="layui-card" style="padding: 15px 0">
                    <div class="layui-card-header">
                        <div class="layui-tab">
                            <ul class="layui-tab-title">
                                <li><a href="javascript:history.back(-1);">{:lang('back')}</a></li>
                                <li class="layui-this">{:lang('view')}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-form ">
                            <form class="layui-form" action="" method="post" onsubmit="return false">
                                <div class="layui-form-item layui-form-text">
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('order_sn')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.order_sn}</div>
                                        </div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('order_state')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux" style="color: red !important;">
                                                {switch order.order_state }
                                                {case 0 }已取消{/case}
                                                {case 10}待支付{/case}
                                                {case 20}已完成{/case}
                                                {default /}
                                                {/switch}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('activity_name')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.activity.activity_name}</div>
                                        </div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('price')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.price}元</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">

                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('school_name')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.school.school_name}</div>
                                        </div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('grade_id')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.grade.grade_name}</div>
                                        </div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('class_name')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.class_name}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('realname')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.realname}</div>
                                        </div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('idcard')}</label>
                                        <div class="layui-input-block">
                                            <div class="layui-form-mid layui-word-aux">{$order.idcard}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('guardian')}</label>
                                        <div class="layui-form-mid layui-word-aux">{$order.guardian}</div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('guardian_tel')}</label>
                                        <div class="layui-form-mid layui-word-aux">{$order.guardian_tel}</div>
                                    </div>

                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('is_allergy')}</label>
                                        <div class="layui-form-mid layui-word-aux"></div>
                                    </div>
                                    <div class="layui-inline layui-col-lg3">
                                        <label class="layui-form-label">{:lang('guardian_tel')}</label>
                                        <div class="layui-form-mid layui-word-aux">{$order.guardian_tel}</div>
                                    </div>

                                </div>
                                <div class="layui-form-item layui-form-text layui-col-lg6">
                                    <label class="layui-form-label">{:lang('is_allergy')}</label>
                                    <div class="layui-form-mid layui-word-aux">
                                        {switch order.is_allergy }
                                        {case 0 }无{/case}
                                        {case 1}有{/case}
                                        {default /}
                                        {/switch}

                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <div class="layui-input-block">

                                    </div>
                                </div>
                            </form>
                        </div>
                        <script>
                            $(function(){
                                layui.config({
                                    base: '__STATIC__/system/js/' //静态资源所在路径,
                                    ,version: '1.2.1'
                                }).extend({
                                    formSelects: 'formSelects-v4'
                                }).use(['index', 'admin', 'form', 'formSelects'], function(){
                                    var table = layui.table
                                        ,admin = layui.admin
                                        ,form = layui.form;
                                    var formSelects = layui.formSelects;
                                    $('.readonly').css("pointer-events","none");
                                    form.render();
                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

{/block}
