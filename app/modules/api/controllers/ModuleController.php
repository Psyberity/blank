<?php
namespace Modules\Api\Controllers;

use App\Models\Module;

class ModuleController extends ControllerBase
{
    protected $model = Module::class;

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

