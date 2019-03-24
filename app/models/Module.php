<?php
namespace App\Models;

use Phalcon\Mvc\Model\Message;

class Module extends Base
{
	public $module_id;
	public $name;
	public $module_name;

    public static $primary_key = 'module_id';
    public static $datatables_columns = [
        ['name' => ['name']],
        ['module_name' => ['module_name']]
    ];
    public static $search_fields = ['name', 'module_name'];
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

    public function getDirs($key = null)
    {
        $dirs = [
            'module_upload' => '/modules/' . $this->module_name . '/upload'
        ];
        return (empty($key) || empty($dirs[$key])) ? $dirs : $dirs[$key];
    }

    public function checkDirs()
    {
        $dirs = $this->getDirs();
        foreach ($dirs as $dir) {
            $this->checkDir($dir);
        }
    }
}