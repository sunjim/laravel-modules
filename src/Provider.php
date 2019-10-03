<?php

namespace Houdunwang\Module;

use Houdunwang\Module\Traits\ConfigService;
use Houdunwang\Module\Traits\MenusService;
use Houdunwang\Module\Traits\ModuleService;
use Houdunwang\Module\Traits\PermissionService;

class Provider
{
    use ConfigService, PermissionService, MenusService,ModuleService;
    //获取导航权限
    public function navigate(){
        $allModules = config('zx_module.allow_navigate');
        if($this->isWebMaster()){
            return $allModules;
        }else{
            $permissions = auth('admin')->user()->getAllPermissions();
            foreach($permissions as $permission){
                $arr[] = lcfirst(explode('\\',$permission->name)[1]);
            }
           return array_intersect(array_unique($arr),config('zx_module.allow_navigate'));
        }
    }
    //定义左侧导航
    public function leftNav(){

        $groups = $this->getMenuByModule();
        if($this->isWebMaster()){
            return $groups;
        }else{
            $permissions = auth('admin')->user()->getAllPermissions()->toArray();
            $arr = array_column($permissions,'name');
            return array_filter($groups,function($item)use($arr){
                foreach($item['permission'] as $v){
                    return in_array($v,$arr);
                }
            });
        }
    }
}
