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

    public function indexAction():bool
    {
        return parent::indexAction();
    }

    public function createAction():bool
    {
        return parent::createAction();
    }

    public function editAction(int $itemId):bool
    {
        return parent::editAction($itemId);
    }

    public function deleteAction(int $itemId):bool
    {
        return parent::deleteAction($itemId);
    }

    protected function createPost():bool
    {
        return parent::createPost();
    }

    protected function editPost():bool
    {
        return parent::editPost();
    }

    protected function setCreateVars():void
    {
        parent::setCreateVars();
    }

    protected function setEditVars():void
    {
        parent::setEditVars();
    }

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }

    protected function listValueHandler(string $field, $value)
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

