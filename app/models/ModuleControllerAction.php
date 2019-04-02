<?php
namespace App\Models;

class ModuleControllerAction extends Base
{
	public $module_controller_action_id;
	public $module_controller_id;
	public $action_id;

    public static $primaryKey = 'module_controller_action_id';
    public static $dataTablesColumns = [];
    public static $searchFields = [];
    public static $labels = [
        'index' => 'Список экшенов контроллера',
        'create' => 'Добавить экшен контроллера',
        'edit' => 'Редактировать экшен контроллера',
        'created' => 'Экшен контроллера добавлен',
        'edited' => 'Экшен контроллера изменен',
        'not_found' => 'Экшен контроллера не найден'
    ];

    public function initialize()
    {
        $this->hasOne('action_id', __NAMESPACE__ . '\Action', 'action_id', [
            'alias' => 'action'
        ]);

        $this->hasOne('module_controller_id', __NAMESPACE__ . '\ModuleController', 'module_controller_id', [
            'alias' => 'controller'
        ]);
    }
}