<?php
namespace Modules\Api\Controllers;

use App\Models\MenuLine;

class MenuLineController extends ControllerBase
{
    protected $model = MenuLine::class;

    protected function listValueHandler($field, $value)
    {
        switch ($field) {
            case 'parent_id':
            case 'module_controller_id':
            case 'action_id':
            case 'parent_parent_id':
                if ($value === null) $value = 'Нет';
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

