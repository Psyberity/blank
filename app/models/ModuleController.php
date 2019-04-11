<?php
namespace App\Models;

class ModuleController extends Base
{
	public $module_controller_id;
	public $module_id;
	public $name;
	public $controller_name;

    public static $dataTablesColumns = [
        ['name' => ['name']],
        ['controller_name' => ['controller_name']],
        ['module_id' => ['module', 'name']]
    ];
    public static $searchFields = ['name', 'controller_name'];
    public static $labels = [
        'index' => 'Список контроллеров',
        'create' => 'Добавить контроллер',
        'edit' => 'Редактировать контроллер',
        'created' => 'Контроллер добавлен',
        'edited' => 'Контроллер изменен',
        'deleted' => 'Контроллер удален',
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

    public function beforeDelete():bool
    {
        // TODO: проверять использование контроллера в ACL
        $controllerActions = $this->controller_actions;
        if (!empty($controllerActions)) {
            foreach ($controllerActions as $controllerAction) {
                if (!$controllerAction->delete()) {
                    foreach ($controllerAction->getMessages() as $message) {
                        $this->appendMessage($message);
                        return false;
                    }
                }
            }
        }
        return parent::beforeDelete();
    }

    public static function simpleModuleArray(int $moduleId, string $zeroValue = null):array
    {
        $lines = self::findByModuleId($moduleId)->toArray();
        return self::simpleDataArray('module_controller_id', ['name'], $lines, $zeroValue);
    }

    public static function selectOptions(string $fieldName, array $params = []):array
    {
        switch ($fieldName) {
            case 'module_id':
                $options = Module::simpleDataArray('module_id');
                break;
            default:
                $options = [];
                break;
        }
        return $options;
    }
}