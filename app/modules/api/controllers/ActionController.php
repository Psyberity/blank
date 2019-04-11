<?php
namespace Modules\Api\Controllers;

use App\Models\Action;

class ActionController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(Action::class, 'action_id');

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

