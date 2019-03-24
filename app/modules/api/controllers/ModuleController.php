<?php
namespace Modules\Api\Controllers;

use App\Models\Module;

class ModuleController extends ControllerBase
{
    protected $model = Module::class;

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

