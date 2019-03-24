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
}

