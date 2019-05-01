<?php
namespace App\Models;

class ModuleUser extends Base
{
	public $module_user_id;
	public $user_id;
	public $module_role_id;

    public static $dataTablesColumns = [
        ['user_id' => ['user', 'name']],
        ['module_role_id' => ['role', 'name']]
    ];
    public static $searchFields = [];
    public static $labels = [
        'index' => 'Список учетных записей',
        'create' => 'Добавить учетную запись',
        'edit' => 'Редактировать учетную запись',
        'created' => 'Учетная запись добавлена',
        'edited' => 'Учетная запись изменена',
        'deleted' => 'Учетная запись удалена',
        'not_found' => 'Учетная запись не найдена',
        'delete_self' => 'Нельзя лишить себя прав'
    ];

    public function initialize()
    {
        parent::initialize();

        $this->hasOne('user_id', __NAMESPACE__ . '\User', 'user_id', [
            'alias' => 'user'
        ]);

        $this->hasOne('module_role_id', __NAMESPACE__ . '\ModuleRole', 'module_role_id', [
            'alias' => 'role'
        ]);
    }

    public static function selectOptions(string $fieldName, array $params = []):array
    {
        switch ($fieldName) {
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