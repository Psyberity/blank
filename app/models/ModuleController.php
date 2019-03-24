<?php
namespace App\Models;

class ModuleController extends Base
{
	public $module_controller_id;
	public $module_id;
	public $name;
	public $controller_name;

    public static $primary_key = 'module_controller_id';
    public static $datatables_columns = [
        ['name' => ['name']],
        ['controller_name' => ['controller_name']],
        ['module_id' => ['module', 'name']]
    ];
    public static $search_fields = ['name', 'controller_name'];
    public static $labels = [
        'index' => 'Список контроллеров',
        'create' => 'Добавить контроллер',
        'edit' => 'Редактировать контроллер',
        'created' => 'Контроллер добавлен',
        'edited' => 'Контроллер изменен',
        'not_found' => 'Контроллер не найден'
    ];

    public function initialize()
    {
        $this->hasManyToMany(
            'module_controller_id', __NAMESPACE__ . '\ModuleControllerAction', 'module_controller_id',
            'action_id', __NAMESPACE__ . '\Action', 'action_id',
            ['alias' => 'actions']
        );

        $this->hasMany('module_controller_id', __NAMESPACE__ . '\ModuleControllerAction', 'module_controller_id', [
            'alias' => 'controller_actions'
        ]);

        $this->hasOne('module_id', __NAMESPACE__ . '\Module', 'module_id', [
            'alias' => 'module'
        ]);
    }

    public function beforeDelete()
    {
        // TODO: проверять использование контроллера в ACL
        $controller_actions = $this->controller_actions;
        if (!empty($controller_actions)) {
            foreach ($controller_actions as $controller_action) {
                if (!$controller_action->delete()) {
                    foreach ($controller_action->getMessages() as $message) {
                        $this->appendMessage($message);
                        return false;
                    }
                }
            }
        }
    }

    public static function simpleModuleArray($module_id, $zero_value = false)
    {
        $lines = self::findByModuleId($module_id)->toArray();
        return self::simpleDataArray('name', $lines, $zero_value);
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