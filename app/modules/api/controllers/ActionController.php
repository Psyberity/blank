<?php
namespace Modules\Api\Controllers;

use App\Models\Action;

class ActionController extends ControllerBase
{
    protected $model = Action::class;

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

