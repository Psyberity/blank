<?php
namespace App\Models;

use App\Classes\Auth;

class MenuLine extends Base
{
	public $menu_line_id;
	public $name;
	public $parent_id;
	public $module_controller_id;
	public $action_id;

    public static $dataTablesColumns = [
        ['parent_parent_id' => ['parent', 'parent', 'name']],
        ['parent_id' => ['parent', 'name']],
        ['name' => ['name']],
        ['module_controller_id' => ['controller', 'name']],
        ['action_id' => ['action', 'name']]
    ];
    public static $searchFields = ['name'];
    public static $labels = [
        'index' => 'Список строк',
        'create' => 'Добавить строку',
        'edit' => 'Редактировать строку',
        'created' => 'Строка добавлена',
        'edited' => 'Строка изменена',
        'deleted' => 'Строка удалена',
        'not_found' => 'Строка не найдена'
    ];

    public function initialize()
    {
        $this->hasOne('module_controller_id', __NAMESPACE__ . '\ModuleController', 'module_controller_id', [
            'alias' => 'controller'
        ]);

        $this->hasOne('action_id', __NAMESPACE__ . '\Action', 'action_id', [
            'alias' => 'action'
        ]);

        $this->hasOne('parent_id', __NAMESPACE__ . '\MenuLine', 'menu_line_id', [
            'alias' => 'parent'
        ]);

        $this->hasMany('menu_line_id', __NAMESPACE__ . '\MenuLine', 'parent_id', [
            'alias' => 'children'
        ]);
    }

    public static function getMenu(Auth $auth, int $moduleControllerId, int $actionId):array
    {
        $menu = [];
        $menuLines = self::findByParentId(0);
        foreach ($menuLines as $menuLine) {
            $line = ['label' => $menuLine->name];
            $children = $menuLine->children;
            $childrenData = [];
            $add = true;
            $lineActive = false;
            if ($children->valid()) {
                foreach ($children as $child) {
                    $childLine = ['label' => $child->name];
                    $subChildren = $child->children;
                    $subChildrenData = [];
                    $subAdd = true;
                    $childActive = false;
                    if ($subChildren->valid()) {
                        foreach ($subChildren as $subChild) {
                            if (!empty($subChild->module_controller_id) && !empty($subChild->action_id) && $auth->acl->isAllowed($auth->moduleUser->module_role_id, $subChild->controller->module_controller_id, $subChild->action->action_id)) {
                                $actionName = ($subChild->action->action_name == 'index') ? '' : $subChild->action->action_name;
                                $subChildActive = ($subChild->controller->module_controller_id == $moduleControllerId && $subChild->action->action_id == $actionId) ? true : false;
                                $subChildrenData[] = [
                                    'label' => $subChild->name,
                                    'url' => '/' . $subChild->controller->controller_name . '/' . $actionName,
                                    'active' => $subChildActive
                                ];
                                if ($subChildActive || $subChild->controller->module_controller_id == $moduleControllerId) $childActive = true;
                            }
                        }
                        if (empty($subChildrenData)) {
                            $subAdd = false;
                        } else {
                            $childLine['children'] = $subChildrenData;
                        }
                        $childLine['active'] = $childActive;
                    } else {
                        if (!empty($child->module_controller_id) && !empty($child->action_id) && $auth->acl->isAllowed($auth->moduleUser->module_role_id, $child->controller->module_controller_id, $child->action->action_id)) {
                            $actionName = ($child->action->action_name == 'index') ? '' : $child->action->action_name;
                            $childActive = ($child->controller->module_controller_id == $moduleControllerId && $child->action->action_id == $actionId) ? true : false;
                            $childLine['url'] = '/' . $child->controller->controller_name . '/' . $actionName;
                            $childLine['active'] = $childActive;
                        } else {
                            $subAdd = false;
                        }
                    }
                    if ($subAdd) $childrenData[] = $childLine;
                    if ($childActive || $child->module_controller_id == $moduleControllerId) $lineActive = true;
                }
                if (empty($childrenData)) {
                    $add = false;
                } else {
                    $line['children'] = $childrenData;
                }
                $line['active'] = $lineActive;
            } else {
                if (!empty($menuLine->module_controller_id) && !empty($menuLine->action_id) && $auth->acl->isAllowed($auth->moduleUser->module_role_id, $menuLine->controller->module_controller_id, $menuLine->action->action_id)) {
                    $actionName = ($menuLine->action->action_name == 'index') ? '' : $menuLine->action->action_name;
                    $lineActive = ($menuLine->controller->module_controller_id == $moduleControllerId && $menuLine->action->action_id == $actionId) ? true : false;
                    $line['url'] = '/' . $menuLine->controller->controller_name . '/' . $actionName;
                    $line['active'] = $lineActive;
                } else {
                    $add = false;
                }
            }
            if ($add) $menu[] = $line;
        }
        return $menu;
    }

    public static function simpleParentArray(string $zero_value = null):array
    {
        $temp_lines = self::find('module_controller_id = 0 AND action_id = 0');
        $lines = [];
        foreach ($temp_lines as $temp_line) {
            $parent_name = (!empty($temp_line->parent)) ? $temp_line->parent->name : '';
            $temp_line = $temp_line->toArray();
            $temp_line['parent'] = $parent_name;
            $lines[] = $temp_line;
        }
        return self::simpleDataArray('menu_line_id', ['parent', 'name'], $lines, $zero_value);
    }

    public static function selectOptions(string $field_name, array $params = []):array
    {
        switch ($field_name) {
            case 'parent_id':
                $options = self::simpleParentArray('Нет');
                break;
            case 'module_controller_id':
                $options = ModuleController::simpleModuleArray($params['module_id'], 'Нет');
                break;
            case 'action_id':
                $options = Action::simpleDataArray('action_id', ['name', 'action_name'], [], 'Нет');
                break;
            default:
                $options = [];
                break;
        }
        return $options;
    }

}