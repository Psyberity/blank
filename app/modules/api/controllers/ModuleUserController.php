<?php
namespace Modules\Api\Controllers;

use App\Models\ModuleUser;

class ModuleUserController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(ModuleUser::class, 'module_user_id');

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

