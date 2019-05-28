{if $admin_item}
<ul class="layui-tab-title">
    {foreach name="admin_item" item="item" }
    <li {if condition="$item.name eq $curitem"}class="layui-this"{/if}>
        <a href="{$item.url}" ><span>{$item.text}</span></a>
    </li>
    {/foreach}
</ul>
{/if}

