<?php
namespace Modules\Admin\Controllers;

use App\Models\MenuLine;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\SelectField;
use Modules\Admin\Forms\Fields\TextField;

class MenuLineController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(MenuLine::class, 'menu_line_id')
            ->registerField(new IdField('menu_line_id', 'ID', []))
            ->registerField(new TextField('name', 'Название'))
            ->registerField(new SelectField('parent_id', 'Родитель', [], [$this->model]))
            ->registerField(new SelectField('module_controller_id', 'Контроллер', [], [$this->model]))
            ->registerField(new SelectField('action_id', 'Экшен', [], [$this->model]));

        parent::initialize();
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

