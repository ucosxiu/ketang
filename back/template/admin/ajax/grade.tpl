<style>
    .table_cell{
        display: inline-block;
    }
    .js_field_tr {
        padding-right: 20px;
        padding-bottom: 10px;
    }
</style>
<table class="table  layui-col-lg6" cellspacing="0">
    <thead class="thead layui-col-lg12">
    <tr class="layui-col-lg12">
        <th class="table_cell" style="width: 40%" width="40%;">年级</th>
        <th class="table_cell" width="40%;">费用</th>
        <th class="table_cell" width="20%;"></th>
    </tr>
    </thead>
    <tbody class="tbody" id="tbody">
        {foreach name="grades" item="item"}
            <tr class="js_field_item" data-id="{$item.grade_id}">
                <td class="js_field_tr">
                    {$item.grade_name}
                    <input type="hidden" class="layui-input layui-col-lg10" placeholder="年级" lay-verify="required" value=" {$item.grade_name}">
                </td>
                <td class="js_field_tr">
                    <input type="text" class="layui-input layui-col-lg10" placeholder="费用" lay-verify="required" value="">
                </td>
                <td class="js_field_tr">
                                                       <span class="add_remove_opr_box">
        <a class="add_remove_opr icon_remove js_remove_api_field"></a>
      </span>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
