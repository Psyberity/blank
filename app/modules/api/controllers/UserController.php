<?php
namespace Modules\Api\Controllers;

use App\Models\User;

class UserController extends ControllerBase
{
    protected $model = User::class;

    protected function listValueHandler($field, $value)
    {
        switch ($field) {
            case 'active':
                $value = ($value == 1) ? 'Да' : 'Нет';
                break;
            default:
                break;
        }
        return $value;
    }

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

