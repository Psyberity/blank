<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleController;

class ModuleControllerController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(ModuleController::class, 'module_controller_id');

        parent::initialize();
    }

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

