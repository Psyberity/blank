<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleRole;

class ModuleRoleController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(ModuleRole::class, 'module_role_id');

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

