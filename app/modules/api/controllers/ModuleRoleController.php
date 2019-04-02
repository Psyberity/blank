<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleRole;

class ModuleRoleController extends ControllerBase
{
    protected $model = ModuleRole::class;

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

