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

    protected function createPost(array $params = []):bool
    {
        return parent::createPost($params);
    }

    protected function editPost(array $params = []):bool
    {
        return parent::editPost($params);
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

