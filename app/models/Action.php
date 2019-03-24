<?php
namespace App\Models;

use Phalcon\Mvc\Model\Message;

class Action extends Base
{
	public $action_id;
	public $name;
	public $action_name;

    public static $primary_key = 'action_id';
    public static $datatables_columns = [
        ['name' => ['name']],
        ['action_name' => ['action_name']]
    ];
    public static $search_fields = ['name', 'action_name'];
    public static $labels = [
        'index' => 'Список экшенов',
        'create' => 'Добавить экшен',
        'edit' => 'Редактировать экшен',
        'created' => 'Экшен добавлен',
        'edited' => 'Экшен изменен',
        'not_found' => 'Экшен не найден'
    ];

    public static function getId($action_name)
    {
        $id = null;
        $action = self::findFirstByActionName($action_name);
        if ($action && count($action) == 1) $id = $action->action_id;
        return $id;
    }

    public function beforeDelete()
    {
        $controller_actions = ModuleControllerAction::findByActionId($this->action_id);
        if ($controller_actions && count($controller_actions) > 0) {
            $message = new Message('There are controllers using this action', 'action_id', 'CantDelete');
            $this->appendMessage($message);
            return false;
        }
        return true;
    }
}