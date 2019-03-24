<?php
namespace App\Models;

class ModuleUser extends Base
{
	public $module_user_id;
	public $user_id;
	public $module_role_id;

    public static $primary_key = 'module_user_id';
    public static $datatables_columns = [
        ['user_id' => ['user', 'name']],
        ['module_role_id' => ['role', 'name']]
    ];
    public static $search_fields = [];
    public static $labels = [
        'index' => 'Список учетных записей',
        'create' => 'Добавить учетную запись',
        'edit' => 'Редактировать учетную запись',
        'created' => 'Учетная запись добавлена',
        'edited' => 'Учетная запись изменена',
        'not_found' => 'Учетная запись не найдена',
        'delete_self' => 'Нельзя лишить себя прав'
    ];

    public function initialize()
    {
        $this->hasOne('user_id', __NAMESPACE__ . '\User', 'user_id', [
            'alias' => 'user'
        ]);

        $this->hasOne('module_role_id', __NAMESPACE__ . '\ModuleRole', 'module_role_id', [
            'alias' => 'role'
        ]);
    }

    public static function selectOptions($field_name, $params = [])
    {
        switch ($field_name) {
            case 'module_role_id':
                $options = ModuleRole::simpleRoleArray();
                break;
            default:
                $options = [];
                break;
        }
        return $options;
    }
}