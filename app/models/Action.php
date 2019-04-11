<?php
namespace App\Models;

use Phalcon\Mvc\Model\Message;

class Action extends Base
{
	public $action_id;
	public $name;
	public $action_name;

    public static $dataTablesColumns = [
        ['name' => ['name']],
        ['action_name' => ['action_name']]
    ];
    public static $searchFields = ['name', 'action_name'];
    public static $labels = [
        'index' => 'Список экшенов',
        'create' => 'Добавить экшен',
        'edit' => 'Редактировать экшен',
        'created' => 'Экшен добавлен',
        'edited' => 'Экшен изменен',
        'deleted' => 'Экшен удален',
        'not_found' => 'Экшен не найден'
    ];

    public static function getId(string $actionName):?int
    {
        $id = null;
        $action = self::findFirstByActionName($actionName);
        if ($action && count($action) == 1) $id = $action->action_id;
        return $id;
    }

    public function beforeDelete():bool
    {
        $controllerActions = ModuleControllerAction::findByActionId($this->action_id);
        if ($controllerActions && count($controllerActions) > 0) {
            $message = new Message('There are controllers using this action', 'action_id', 'CantDelete');
            $this->appendMessage($message);
            return false;
        }
        return parent::beforeDelete();
    }
}