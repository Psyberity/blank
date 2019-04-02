<?php
namespace App\Models;

use Phalcon\Mvc\Model\Message;

class Module extends Base
{
	public $module_id;
	public $name;
	public $module_name;

    public static $primaryKey = 'module_id';
    public static $dataTablesColumns = [
        ['name' => ['name']],
        ['module_name' => ['module_name']]
    ];
    public static $searchFields = ['name', 'module_name'];
    public static $labels = [
        'index' => 'Список модулей',
        'create' => 'Добавить модуль',
        'created' => 'Модуль добавлен',
        'not_found' => 'Модуль не найден',
        'action_denied' => 'Действие запрещено'
    ];

    public function beforeDelete()
    {
        if ($this->module_name == 'admin' || $this->module_name == 'api') {
            $message = new Message($this->module_name . ' module can not be deleted', 'module_name', 'CantDelete');
            $this->appendMessage($message);
            return false;
        }
        return true;
    }

    public function getDirs():array
    {
        $dirs = [
            'module_upload' => '/modules/' . $this->module_name . '/upload'
        ];
        return $dirs;
    }

    public function getDir(string $key = null):?string
    {
        $dirs = $this->getDirs();
        return (empty($dirs[$key])) ? null : $dirs[$key];
    }

    public function checkDirs():void
    {
        $dirs = $this->getDirs();
        foreach ($dirs as $dir) {
            $this->checkDir($dir);
        }
    }
}