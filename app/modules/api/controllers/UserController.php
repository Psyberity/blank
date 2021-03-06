<?php
namespace Modules\Api\Controllers;

use App\Models\User;

class UserController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(User::class, 'user_id');

        parent::initialize();
    }

    protected function listValueHandler(string $field, $value)
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

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

