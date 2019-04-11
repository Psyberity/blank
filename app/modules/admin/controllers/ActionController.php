<?php
namespace Modules\Admin\Controllers;

use App\Models\Action;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\TextField;

class ActionController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(Action::class, 'action_id')
            ->registerField(new IdField('action_id', 'ID', []))
            ->registerField(new TextField('name', 'Название'))
            ->registerField(new TextField('action_name', 'Имя экшена'));

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
}

