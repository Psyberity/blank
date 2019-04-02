<?php
namespace Modules\Api\Controllers;

use App\Models\Action;

class ActionController extends ControllerBase
{
    protected $model = Action::class;

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

