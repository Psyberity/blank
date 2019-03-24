<?php
namespace App\Models;

class ModuleRole extends Base
{
	public $module_role_id;
	public $module_id;
	public $name;
	public $acl;

    public static $primary_key = 'module_role_id';
    public static $datatables_columns = [
        ['name' => ['name']],
        ['module_id' => ['module', 'name']]
    ];
    public static $search_fields = ['name'];
    public static $labels = [
        'index' => 'Список ролей',
        'create' => 'Создать роль',
        'edit' => 'Редактировать роль',
        'created' => 'Роль создана',
        'edited' => 'Роль изменена',
        'not_found' => 'Роль не найдена',
        'delete_self' => 'Нельзя удалить свою роль'
    ];

    public function initialize()
    {
        $this->hasOne('module_id', __NAMESPACE__ . '\Module', 'module_id', [
            'alias' => 'module'
        ]);
    }

    public static function simpleRoleArray($zero_value = false)
    {
        $temp_lines = self::find(['order' => 'module_id']);
        $lines = [];
        foreach ($temp_lines as $temp_line) {
            $module_name = $temp_line->module->name;
            $temp_line = $temp_line->toArray();
            $temp_line['module'] = $module_name;
            $lines[] = $temp_line;
        }
        return self::simpleDataArray(['module', 'name'], $lines, $zero_value);
    }

    public static function selectOptions($field_name, $params = [])
    {
        switch ($field_name) {
            case 'module_id':
                $options = Module::simpleDataArray();
                break;
            default:
                $options = [];
                break;
        }
        return $options;
    }

}