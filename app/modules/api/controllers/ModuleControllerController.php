<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleController;

class ModuleControllerController extends ControllerBase
{
    protected $model = ModuleController::class;

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

