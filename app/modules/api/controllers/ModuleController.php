<?php
namespace Modules\Api\Controllers;

use App\Models\Module;

class ModuleController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(Module::class, 'module_id');

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

