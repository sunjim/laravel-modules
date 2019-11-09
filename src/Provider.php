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
                if(isset(explode('\\',$permission->name)[1])){
                    $arr[] = lcfirst(explode('\\',$permission->name)[1]);
                }else{
                    return false;
                }
            }
           return array_intersect(config('zx_module.allow_navigate'),array_unique($arr));
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
            $result =  array_filter($groups,function($item)use($arr){
                foreach($item['permission'] as $v){
                    return in_array($v,$arr);
                }
            });
            foreach($result as $it=>$item){
                foreach($item['menus'] as $k=>$v){
                    if(!in_array($v['permission'],$arr)){
                        unset($result[$it]['menus'][$k]);
                    }
                }
            }
            return $result;
        }
    }
}
