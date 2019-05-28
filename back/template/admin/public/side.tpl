<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            {foreach name="menus" item="menu" key="k"}
                <li class="layui-nav-item">
                    {empty name="menu.children"}
                        <a href="{:Url($menu['m'].DS.$menu['c'].DS.$menu['a'])}">{$menu.menuname}</a>
                    {else /}
                        <a class="" href="javascript:;">{$menu.menuname}</a>
                        <dl class="layui-nav-child">
                            {foreach name="menu.children" item="menu1"}
                                <dd {if condition="strtolower($menu1['m'].DIRECTORY_SEPARATOR.$menu1['c'].DIRECTORY_SEPARATOR.$menu1['a']) eq $curmenu"}class="layui-this"{/if}><a href="{:Url($menu1['m'].DIRECTORY_SEPARATOR.$menu1['c'].DIRECTORY_SEPARATOR.$menu1['a'])}">{$menu1.menuname}</a></dd>
                            {/foreach}
                        </dl>
                    {/empty}

                </li>
            {/foreach}
        </ul>
    </div>
</div>
<script>
  $(function(){
    $('.layui-this').parents('.layui-nav-item').addClass('layui-nav-itemed')
  })
</script>
