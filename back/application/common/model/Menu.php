<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 10:15
 */
namespace app\common\model;

use org\Tree;
use think\facade\Lang;
use think\Model;

//小区模型
class Menu extends Common {
    protected $pk = 'menuid';
    protected $name = 'admin_menu';
    protected $resultSetType = 'collection';

    //默认添加排序
    protected $insert = ['menuname', 'm', 'c','a', 'listorder'];

    public function setListorderAttr($value)
    {
        return $value ? $value : 10000;
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     */
    static function load($parentid = 0) {

        $menus = static::all(function($query) use($parentid){
            $query->where(['status'=>1, 'parentid'=>$parentid])->order('listorder', 'asc');
        });
        foreach ($menus as $k  => $menu) {
            $menuid = $menu->menuid;
            $menus[$k]['children'] = static::all(function($query) use($menuid){
                $query->where(['status'=>1, 'parentid'=>$menuid])->order('listorder', 'asc');
            });
        }

        return $menus;
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     */
    static function initTree() {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $menus = static::where([])->field('menuid id,menuname name, parentid, m, c, a, listorder, status')->select()->toArray();

        $_categorys = [];
        $statuses = [0=>Lang::get('hide'), 1=>Lang::get('show')];
        if(!empty($menus)) {
            foreach ($menus as $menu) {
                if (!$menu) {
                    continue;
                }
                $menu['app'] = $menu['m'].DS.$menu['c'].DS.$menu['a'];
                $menu['status'] = $statuses[$menu['status']];
                $menu['str_manage'] = '';
                $menu['str_manage'] .= '<a href="' . Url("menu/add", array("parentid" => $menu['id'])) . '"><i class="layui-icon layui-icon-add-1"></i></a> ';
                $menu['str_manage'] .= '<a href="' . Url("menu/edit", array("id" => $menu['id'])) . '"><i class="layui-icon layui-icon-edit"></i></a> ';
                $menu['str_manage'] .= '<a href="' . Url("menu/del", array("id" => $menu['id'])) . '" class="js-ajax-delete" role="button" data-toggle="modal"><i class="layui-icon layui-icon-delete"></i></a> ';
                $menu['parent_id_node'] = ($menu['parentid']) ? ' class="child-of-node-' . $menu['parentid'] . '"' : '';
                $menu['pid'] = $menu['parentid'];
                $_categorys[$menu['id']] = $menu;
            }
        }

//        $tree->init($_categorys);
//
//        $str  = "<tr id='node-\$id' \$parent_id_node>
//                    <td></td>
//					<td  data-key='\$id' class='listorder'>\$listorder</td>
//					<td>\$id</td>
//					<td>\$app</td>
//					<td >\$spacer\$name</td>
//					<td >\$status</td>
//					<td>\$str_manage</td>
//				</tr>";
//        $categorys = $tree->get_tree(0, $str);
        return $_categorys;
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     */
    static function selectTree($selectid = 0) {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $menus = static::where([])->field('menuid id,menuname name, parentid, m, c, a, listorder, status')->select()->toArray();

        $_categorys = [];
        if(!empty($menus)) {
            foreach ($menus as $menu) {
                if (!$menu) {
                    continue;
                }
                $menu['selected'] = $selectid == $menu['id'] ?  'selected' : '';
                $_categorys[$menu['id']] = $menu;
            }
        }

        $tree->init($_categorys);

        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $options = $tree->get_tree(0, $str);
        return $options;
    }
}
