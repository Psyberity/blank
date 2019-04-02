<?php
namespace Modules\Admin\Controllers;

use App\Models\Action;
use Modules\Admin\Forms\Fields\FieldBase;

class ActionController extends ModelControllerBase
{
    protected $model = Action::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'action_id', 'ID', [])
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'Название')
            ->registerField(FieldBase::TYPE_TEXT, 'action_name', 'Имя экшена');
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

