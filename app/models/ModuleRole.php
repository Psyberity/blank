<?php
namespace App\Models;

class ModuleRole extends Base
{
	public $module_role_id;
	public $module_id;
	public $name;
	public $acl;

    public static $primaryKey = 'module_role_id';
    public static $dataTablesColumns = [
        ['name' => ['name']],
        ['module_id' => ['module', 'name']]
    ];
    public static $searchFields = ['name'];
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

    public static function simpleRoleArray(string $zeroValue = null):array
    {
        $tempLines = self::find(['order' => 'module_id']);
        $lines = [];
        foreach ($tempLines as $tempLine) {
            $moduleName = $tempLine->module->name;
            $tempLine = $tempLine->toArray();
            $tempLine['module'] = $moduleName;
            $lines[] = $tempLine;
        }
        return self::simpleDataArray(['module', 'name'], $lines, $zeroValue);
    }

    public static function selectOptions(string $fieldName, array $params = []):array
    {
        switch ($fieldName) {
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