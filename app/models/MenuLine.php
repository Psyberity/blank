<?php
namespace App\Models;

class MenuLine extends Base
{
	public $menu_line_id;
	public $name;
	public $parent_id;
	public $module_controller_id;
	public $action_id;

    public static $primary_key = 'menu_line_id';
    public static $datatables_columns = [
        ['parent_parent_id' => ['parent', 'parent', 'name']],
        ['parent_id' => ['parent', 'name']],
        ['name' => ['name']],
        ['module_controller_id' => ['controller', 'name']],
        ['action_id' => ['action', 'name']]
    ];
    public static $search_fields = ['name'];
    public static $labels = [
        'index' => 'Список строк',
        'create' => 'Добавить строку',
        'edit' => 'Редактировать строку',
        'created' => 'Строка добавлена',
        'edited' => 'Строка изменена',
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

    public static function getMenu($auth, $module_controller_id, $action_id)
    {
        $menu = [];
        $menu_lines = self::findByParentId(0);
        foreach ($menu_lines as $menu_line) {
            $line = ['label' => $menu_line->name];
            $children = $menu_line->children;
            $children_data = [];
            $add = true;
            $line_active = false;
            if ($children->valid()) {
                foreach ($children as $child) {
                    $child_line = ['label' => $child->name];
                    $sub_children = $child->children;
                    $sub_children_data = [];
                    $sub_add = true;
                    $child_active = false;
                    if ($sub_children->valid()) {
                        foreach ($sub_children as $sub_child) {
                            if (!empty($sub_child->module_controller_id) && !empty($sub_child->action_id) && $auth->acl->isAllowed($auth->module_user->module_role_id, $sub_child->controller->module_controller_id, $sub_child->action->action_id)) {
                                $action_name = ($sub_child->action->action_name == 'index') ? '' : $sub_child->action->action_name;
                                $sub_child_active = ($sub_child->controller->module_controller_id == $module_controller_id && $sub_child->action->action_id == $action_id) ? true : false;
                                $sub_children_data[] = [
                                    'label' => $sub_child->name,
                                    'url' => '/' . $sub_child->controller->controller_name . '/' . $action_name,
                                    'active' => $sub_child_active
                                ];
                                if ($sub_child_active || $sub_child->controller->module_controller_id == $module_controller_id) $child_active = true;
                            }
                        }
                        if (empty($sub_children_data)) {
                            $sub_add = false;
                        } else {
                            $child_line['children'] = $sub_children_data;
                        }
                        $child_line['active'] = $child_active;
                    } else {
                        if (!empty($child->module_controller_id) && !empty($child->action_id) && $auth->acl->isAllowed($auth->module_user->module_role_id, $child->controller->module_controller_id, $child->action->action_id)) {
                            $action_name = ($child->action->action_name == 'index') ? '' : $child->action->action_name;
                            $child_active = ($child->controller->module_controller_id == $module_controller_id && $child->action->action_id == $action_id) ? true : false;
                            $child_line['url'] = '/' . $child->controller->controller_name . '/' . $action_name;
                            $child_line['active'] = $child_active;
                        } else {
                            $sub_add = false;
                        }
                    }
                    if ($sub_add) $children_data[] = $child_line;
                    if ($child_active || $child->module_controller_id == $module_controller_id) $line_active = true;
                }
                if (empty($children_data)) {
                    $add = false;
                } else {
                    $line['children'] = $children_data;
                }
                $line['active'] = $line_active;
            } else {
                if (!empty($menu_line->module_controller_id) && !empty($menu_line->action_id) && $auth->acl->isAllowed($auth->module_user->module_role_id, $menu_line->controller->module_controller_id, $menu_line->action->action_id)) {
                    $action_name = ($menu_line->action->action_name == 'index') ? '' : $menu_line->action->action_name;
                    $line_active = ($menu_line->controller->module_controller_id == $module_controller_id && $menu_line->action->action_id == $action_id) ? true : false;
                    $line['url'] = '/' . $menu_line->controller->controller_name . '/' . $action_name;
                    $line['active'] = $line_active;
                } else {
                    $add = false;
                }
            }
            if ($add) $menu[] = $line;
        }
        return $menu;
    }

    public static function simpleParentArray($zero_value = false)
    {
        $temp_lines = self::find('module_controller_id = 0 AND action_id = 0');
        $lines = [];
        foreach ($temp_lines as $temp_line) {
            $parent_name = (!empty($temp_line->parent)) ? $temp_line->parent->name : '';
            $temp_line = $temp_line->toArray();
            $temp_line['parent'] = $parent_name;
            $lines[] = $temp_line;
        }
        return self::simpleDataArray(['parent', 'name'], $lines, $zero_value);
    }

    public static function selectOptions($field_name, $params = [])
    {
        switch ($field_name) {
            case 'parent_id':
                $options = self::simpleParentArray('Нет');
                break;
            case 'module_controller_id':
                $options = ModuleController::simpleModuleArray($params['module_id'], 'Нет');
                break;
            case 'action_id':
                $options = Action::simpleDataArray(['name', 'action_name'], false, 'Нет');
                break;
            default:
                $options = [];
                break;
        }
        return $options;
    }

}