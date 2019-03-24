<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleUser;

class ModuleUserController extends ControllerBase
{
    protected $model = ModuleUser::class;

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

