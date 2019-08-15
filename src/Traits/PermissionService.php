<?php
/** .-------------------------------------------------------------------
 * |      Site: www.hdcms.com  www.houdunren.com
 * |      Date: 2018/7/2 下午2:21
 * |    Author: 向军大叔 <2300071698@qq.com>
 * '-------------------------------------------------------------------*/
namespace Houdunwang\Module\Traits;

use Module;
use Spatie\Permission\Models\Permission;

/**
 * Trait PermissionService
 *
 * @package Houdunwang\Module\Traits
 */
trait PermissionService
{
    public $allPermisssion;
    /**
     * 验证权限
     *
     * @param        $permissions
     * @param string $guard
     *
     * @return bool
     */
    public function hadPermission($permissions, string $guard): bool
    {
        if (auth($guard)->user()->name=='admin') {
            return true;
        }
        $model = $this->getCache($guard);
        $data = [];
        foreach($model as $k=>$v){
            if($v->name==$permissions){
                $data = $permissions;
            }
        }
        return auth()->user()->hasAnyPermission($data);
    }
    //获取缓存数据
    public function getCache($guard){
        $key = 'permission_sliders';
        $data = cache($key);
        if($data){
            return $data;
        }else{
            $res = \DB::table('permissions')->where('guard_name',$guard)->get()->toArray();
            //加入缓存
            cache([$key => $res], now()->addDay());
            return $res;
        }
    }
    /**
     * 站长检测
     *
     * @return bool
     */
    public function isWebMaster($guard = 'admin'): bool
    {
        $relation = auth($guard)->user()->roles();
        $has      = $relation->where('roles.name', config('hd_module.webmaster'))->first();
        return boolval($has);
    }
    /**
     * @param $guard
     *
     * @return array
     */
    public function getPermissionByGuard($guard)
    {
        $modules     = Module::getOrdered();
        $permissions = [];
        foreach ($modules as $module) {
            $ishave = $this->filterByGuard($module, $guard);
            if($ishave){
                $permissions[] = [
                    'module' => $module,
                    'config' => $this->config($module->getName().'.config'),
                    'rules'  => $ishave,
                ];
            }
        }

        return $permissions;
    }

    /**
     * @param $module
     * @param $guard
     *
     * @return mixed
     */
    protected function filterByGuard($module, $guard)
    {
        $data = $config = \HDModule::config($module.'.permission');
        foreach ($config as $k => $group) {
            foreach ($group['permissions'] as $n => $permission) {
                if ($permission['guard'] != $guard) {
                    unset($data[$k]['permissions'][$n]);
                    return false;
                }
            }
        }

        return $data;
    }
}