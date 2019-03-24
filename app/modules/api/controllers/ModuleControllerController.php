<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleController;

class ModuleControllerController extends ControllerBase
{
    protected $model = ModuleController::class;

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

