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
        if ($this->isWebMaster()) {
            return true;
        }
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        return auth()->user()->hasAnyPermission($permissions);
    }

    /**
     * 站长检测
     *
     * @return bool
     */
    public function isWebMaster($guard = 'admin'): bool
    {
        $has = cache()->remember(
            'isWebMaster'.auth($guard)->user()->id,
            7200,
            function()use($guard){
                $relation = auth($guard)->user()->roles();
                return $relation->where('roles.name', config('zx_module.webmaster'))->first();
            }
        );
        return boolval($has);
    }

    /**
     * @param $guard
     *
     * @return array
     */
    public function getPermissionByGuard($guard)
    {
        $modules     = Module::toCollection()->toArray();
        $permissions = [];
        foreach ($modules as $module) {
            $ishave = $this->filterByGuard($module, $guard);
            if($ishave){
                $permissions[] = [
                    'module' => $module,
                    'config' => $this->config($module['alias'].'.config'),
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
        $data = $config = \HDModule::config($module['alias'].'.permission');
        if(is_array($config)){
            foreach ($config as $k => $group) {
                foreach ($group['permissions'] as $n => $permission) {
                    if ($permission['guard'] != $guard) {
                        unset($data[$k]['permissions'][$n]);
                        return false;
                    }
                }
            }
            return $data;
        }else{
            return [];
        }

    }
}
