<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleUser;

class ModuleUserController extends ControllerBase
{
    protected $model = ModuleUser::class;

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

