<?php
namespace Modules\Admin\Controllers;

use App\Traits\AdminControllerTrait;
use App\Traits\ControllerTrait;
use App\Models\Base;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    use ControllerTrait, AdminControllerTrait;

    public $module;
    public $controller;
    public $action;
    public $functions;
    public $acl;
    public $auth;
    public $lang;
    public $apiUrl;

    protected $assetsChange;
    protected $moduleName = 'admin';

    protected function flashErrors($object):void
    {
        foreach ($object->getMessages() as $message) {
            $this->flashSession->error($message->getMessage());
        }
    }

    public function initialize()
    {
        $this->setAssets($this->action->action_name);
    }
}
