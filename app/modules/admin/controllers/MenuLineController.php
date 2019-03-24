<?php
namespace Modules\Admin\Controllers;

use App\Models\MenuLine;
use Modules\Admin\Forms\Fields\FieldBase;

class MenuLineController extends ModelControllerBase
{
    protected $model = MenuLine::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'menu_line_id', 'ID', [])
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'Название')
            ->registerField(FieldBase::TYPE_SELECT, 'parent_id', 'Родитель', [])
            ->registerField(FieldBase::TYPE_SELECT, 'module_controller_id', 'Контроллер', [])
            ->registerField(FieldBase::TYPE_SELECT, 'action_id', 'Экшен', []);
    }

    public function indexAction()
    {
        return parent::indexAction();
    }

    public function createAction()
    {
        parent::createAction();
    }

    public function editAction($item_id)
    {
        return parent::editAction($item_id);
    }

    public function deleteAction($item_id)
    {
        return parent::deleteAction($item_id);
    }

    protected function createPost()
    {
        return parent::createPost();
    }

    protected function editPost()
    {
        return parent::editPost();
    }

    protected function setCreateVars()
    {
        parent::setCreateVars();
    }

    protected function setEditVars()
    {
        parent::setEditVars();
    }

    public function setCommonVars()
    {
        parent::setCommonVars();
    }

    protected function listValueHandler($field, $value)
    {
        switch ($field) {
            case 'parent_id':
            case 'module_controller_id':
            case 'action_id':
                if ($value === null) $value = 'Нет';
                break;
            default:
                break;
        }
        return $value;
    }
}

