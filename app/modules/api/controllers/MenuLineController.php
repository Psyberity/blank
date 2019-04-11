<?php
namespace Modules\Api\Controllers;

use App\Models\MenuLine;

class MenuLineController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(MenuLine::class, 'menu_line_id');

        parent::initialize();
    }

    protected function listValueHandler(string $field, $value)
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

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

