<?php

namespace App\Helpers;

use App\Entities\Admin\LGeoDistrict;
use App\Entities\Ams\LPriorityType;
use App\Entities\Ams\OperatorMapping;
use App\Entities\Security\Menu;
use App\Enums\ModuleInfo;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CcmsClass
{

    public $id;
    public $links;

    /**
     * @return mixed
     */
    public static function menuSetup()
    {
        if (Auth::user()->hasGrantAll()) {
            $moduleId = ModuleInfo::MODULE_ID;
            $menus = Menu::where('module_id', $moduleId)->orderBy('menu_order_no')->get();

            return $menus;
        } else {
            $allMenus = Auth::user()->getRoleMenus();
            $menus = [];

            if ($allMenus) {
                foreach ($allMenus as $menu) {
                    if ($menu->module_id == ModuleInfo::MODULE_ID) {
                        $menus[] = $menu;
                    }
                }
            }

            return $menus;
        };
    }

    public static function getActiveRouteNameWrapping($routeName)
    {//dd($routeName);
        if (in_array($routeName, ['equip-activities-edit'])) {
            return 'equip-activities-index';
        } else if (in_array($routeName, ['equip-assign-edit'])) {
            return 'equip-assign-index';
        } else if (in_array($routeName, ['equip-request-approval-edit'])) {
            return 'equip-request-approval-index';
        } else if (in_array($routeName, ['equipment-request-edit'])) {
            return 'equipment-request-index';
        } else if (in_array($routeName, ['duty-roster-edit'])) {
            return 'duty-roster-index';
        } else if (in_array($routeName, ['add-equipment-edit'])) {
            return 'add-equipment-index';
        } else if (in_array($routeName, ['berth-operator-edit'])) {
            return 'berth-operator-index';
        } else if (in_array($routeName, ['repair-request-edit'])) {
            return 'repair-request-index';
        } else if (in_array($routeName, ['repair-diagnosis-edit'])) {
            return 'repair-diagnosis-index';
        } else if (in_array($routeName, ['spare-parts-request-edit'])) {
            return 'spare-parts-request';
        } else if (in_array($routeName, ['equipment-service-edit'])) {
            return 'equipment-service-index';
        } else if (in_array($routeName, ['repair-part-request-edit'])) {
            return 'repair-part-request-index';
        } else if (in_array($routeName, ['parts-entry-edit'])) {
            return 'parts-entry-index';
        } else if (in_array($routeName, ['parts-stock-edit'])) {
            return 'parts-stock-index';
        } else if (in_array($routeName, ['workshop-team-edit'])) {
            return 'workshop-team-entry-index';
        }else if (in_array($routeName, ['repair-request-approval-edit'])) {
            return 'repair-request-approval-index';
        } else {
            return [
                [
                    'submenu_name' => $routeName,
                ]
            ];
        }
    }

    public static function activeMenus($routeName)
    {
        //$menus = [];
        try {
            $authorizationManager = new AuthorizationManager();
            $menus[] = $getRouteMenuId = $authorizationManager->findSubMenuId(self::getActiveRouteNameWrapping($routeName));

            if ($getRouteMenuId && !empty($getRouteMenuId)) {
                $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                $menus[] = $bm['parent_submenu_id'];
                if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                    $m = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    if (!empty($m['submenu_id'])) {
                        $menus[] = $m['submenu_id'];
                    }
                }
            }
        } catch (\Exception $e) {
            $menus = [];
        }
        return is_array($menus) ? $menus : false;
    }

    public static function hasChildMenu($routeName)
    {
        $authorizationManager = new AuthorizationManager();
        $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
        return $authorizationManager->hasChildMenu($getRouteMenuId);
    }
}
