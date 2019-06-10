/** layuiAdmin.pro-v1.2.1 LPPL License By http://www.layui.com/admin/ */
;
layui.extend({
  setter: "config",
  admin: "admin",
  view: "view"
}).define(["setter", "admin", 'form'],
  function(e) {
    var a = layui.setter,
      n = layui.element,
      i = layui.admin,
      t = i.tabsPage,
      l = layui.view,
      o = function() {
        var e = layui.router(),
          r = e.path,
          y = i.correctRouter(e.path.join("/"));
        r.length || (r = [""]),
        "" === r[r.length - 1] && (r[r.length - 1] = a.entry);
        var h = function(e) {
          o.haveInit && d(".layui-layer").each(function() {
            var e = d(this),
              a = e.attr("times");
            e.hasClass("layui-layim") || layer.close(a)
          }),
            o.haveInit = !0,
            d(s).scrollTop(0),
            delete t.type
        };
        return "tab" === t.type && ("/" !== y || "/" === y && i.tabsBody().html()) ? (i.tabsBodyChange(t.index), h(t.type)) : (l().render(r.join("/")).then(function(l) {
          var o, r = d("#LAY_app_tabsheader>li");
          r.each(function(e) {
            var a = d(this),
              n = a.attr("lay-id");
            n === y && (o = !0, t.index = e)
          }),
          a.pageTabs && "/" !== y && (o || (d(s).append('<div class="layadmin-tabsbody-item layui-show"></div>'), t.index = r.length, n.tabAdd(u, {
            title: "<span>" + (l.title || "新标签页") + "</span>",
            id: y,
            attr: e.href
          }))),
            this.container = i.tabsBody(t.index),
          a.pageTabs || this.container.scrollTop(0),
            n.tabChange(u, y),
            i.tabsBodyChange(t.index)
        }).done(function() {
          layui.use("common", layui.cache.callback.common),
            c.on("resize", layui.data.resize),
            n.render("breadcrumb", "breadcrumb"),
            i.tabsBody(t.index).on("scroll",
              function() {
                var e = d(this),
                  a = d(".layui-laydate"),
                  n = d(".layui-layer")[0];
                a[0] && (a.each(function() {
                  var e = d(this);
                  e.hasClass("layui-laydate-static") || e.remove()
                }), e.find("input").blur()),
                n && layer.closeAll("tips")
              })
        }), void h())
      },
      s = "#LAY_app_body",
      u = "layadmin-layout-tabs",
      d = layui.$,
      c = d(window);
      window.onhashchange = function() {
          layui.event.call(this, a.MOD_NAME, "hash({*})", layui.router())
      },
      layui.each(a.extend,
        function(e, n) {
          var i = {};
          i[n] = "{/}" + a.base + "lib/extend/" + n,
            layui.extend(i)
        }),
        layui.form.on('submit(ajax-submit)', function(obj){
          var url = obj.form.action;
          $.post(url, obj.field, function(data){
            if (data.code) {
              layer.msg(data.msg, {
                offset: '15px'
                ,icon: 1
                ,time: 1000
              }, function(){
                if (data.url) {
                  location.href = data.url
                }
              });
            } else {
              layer.msg(data.msg, {
                offset: '15px'
                ,icon: 0
                ,time: 1000
              }, function(){
              });
            }
          }, 'json')
        }),
      e("index", {
        render: o
      })
  });
