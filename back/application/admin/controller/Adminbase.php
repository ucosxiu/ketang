<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 21:29
 */
namespace app\admin\controller;

//后台公共类
use app\common\model\Adminmenu;
use app\common\model\Menu;
use app\common\model\Rolepriv;
use think\Controller;
use think\facade\Lang;
use think\Request;
use think\View;

class Adminbase extends Controller{
    //初始化
    function __construct(){
        parent::__construct();

        $this->loadLang();
        $this->loadMenu();
    }

    function initialize()
    {
        $this->checkLogin();
        if(!$this->check_access()){
            $this->error("您没有访问权限！");
        }
    }

    //检查权限
    function check_access() {
        //如果用户角色是1，则无需判断
        $uid = session('ADMIN_ID');
        if($uid == 1){
            return true;
        }

        $findArr = [];
        $findArr['m'] = strtolower($this->request->module());
        $findArr['c'] = strtolower($this->request->controller());
        $findArr['a'] = strtolower($this->request->action());

        $admin_menu = Adminmenu::get($findArr);
        if (!$admin_menu || $admin_menu['type'] != 1) {
            return true;
        }



        //不需要验证的路由
        $no_need_check_rules = [
            [
                'm' => 'admin',
                'c' => 'index',
                'a' => 'index'
            ]
        ];
        if (in_array($findArr, $no_need_check_rules)) {
            return true;
        }

        $admin = session('ADMIN');
        //角色
        $roleid = $admin->roleid;

        $findArr['roleid'] = $roleid;

        if (Rolepriv::get($findArr)) {
            return true;
        }

        return false;
    }


    function getUserid() {
        return session('ADMIN_ID');
    }
    //加载菜单
    function loadMenu() {
        $admin = session('ADMIN');
        if (!$admin) {
            return;
        }
        $menus = Menu::load(0)->toArray();
        $uid = session('ADMIN_ID');
        if ($uid != 1) {
            $roleid = $admin->roleid;
            $privs = Rolepriv::field('concat(m, c, a) priv')->all(['roleid' => $roleid])->toArray();
            $_privs = array_column($privs, 'priv');

            foreach ($menus as $key => $menu) {
                if (!in_array(strtolower($menu['m'].$menu['c'].$menu['a']), $_privs)) {
                    unset($menus[$key]);
                } else {
                    if ($menu['children']) {
                        foreach ($menu['children'] as $key1 => $children) {
                            if (!in_array(strtolower($children['m'].$children['c'].$children['a']), $_privs)) {
                                unset($menus[$key]['children'][$key1]);
                            }
                        }
                    }
                }
            }
        }


        $curmenu = $this->request->module().DIRECTORY_SEPARATOR.$this->request->controller().DIRECTORY_SEPARATOR.$this->request->action();
        $this->assign('curmenu', strtolower($curmenu));
        $this->assign('menus', $menus);
    }


    //检查是否登录
    private function checkLogin() {
        if (!session('ADMIN_ID')){
            $this->redirect('sign/login');
            exit;
        }
    }

    //加载扩展语言包
    private function loadLang() {
        //文件地址
        $file = APP_PATH . $this->request->module() .DIRECTORY_SEPARATOR. 'lang' . DIRECTORY_SEPARATOR .  $this->request->langset() . DIRECTORY_SEPARATOR . strtolower( $this->request->controller()) .EXT;
        if (is_file($file)) {
            Lang::load($file);
        }
    }

    //获取当前用户id
    protected function get_current_memberid() {
        return session('ADMIN_ID');
    }

    //获取当前用户
    protected function get_current_member() {
        $admin = session('ADMIN');
        return $admin;
    }

    /**
     *  排序 排序字段为listorders数组 POST 排序字段为：listorder
     */
    protected function _listorders($model) {
        if (!is_object($model)) {
            return false;
        }
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;

            $model->allowField(true)->save($data, ['menuid'=>$key]);
        }
        return true;
    }

    /**
     * @return array
     */
    protected function getAdminItemList( $data = []) {
        return array();
    }


    /**
     * @param string $curitem
     */
    protected function setAdminCurItem($curitem = '', $data=[]) {
        $this->assign('admin_item', $this->getAdminItemList($data));
        $this->assign('curitem', $curitem);
    }

    /**
     * 记录系统日志
     *
     * @param $lang 日志语言包
     * @param $state 1成功0失败null不出现成功失败提示
     * @param $admin_name
     * @param $admin_id
     */
    protected final function log($lang = '', $state = 1, $admin_name = '', $admin_id = 0) {
        if ($admin_name == '') {
            $admin_name = session('ADMIN')['username'];
            $admin_id = session('ADMIN_ID');
        }
        $data = array();
        if (is_null($state)) {
            $state = null;
        } else {
            $state = $state ? '' : lang('ds_fail');
        }
        $data['adminlog_content'] = $lang . $state;
        $data['adminlog_time'] = TIMESTAMP;
        $data['admin_name'] = $admin_name;
        $data['admin_id'] = $admin_id;
        $data['adminlog_ip'] = request()->ip();
        $data['adminlog_url'] = request()->controller() . '&' . request()->action();

        $adminlog_model = model('adminlog');
        return $adminlog_model->addAdminlog($data);
    }
}
