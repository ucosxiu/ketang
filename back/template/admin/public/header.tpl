<div class="layui-header">
    <!-- 头部区域（可配合layui已有的水平导航） -->
     <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item layadmin-flexible" lay-unselect="">
          <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
            <i class="layui-icon layui-icon-spread-left" id="LAY_app_flexible"></i>
          </a>
        </li>
    </ul>
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;">
                <img src="__STATIC__/system/images/avatar.jpg" class="layui-nav-img">
                {$Think.session.ADMIN.username}
            </a>
            <dl class="layui-nav-child">
                <dd><a href="{:Url('account/userinfo')}">基本资料</a></dd>
                <dd><a href="{:Url('setting/password')}">修改密码</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="{:Url('sign/lagout')}">退了</a></li>
    </ul>
</div>
