<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleRole;

class ModuleRoleController extends ControllerBase
{
    protected $model = ModuleRole::class;

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

