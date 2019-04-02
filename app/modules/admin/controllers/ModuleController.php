<?php
namespace Modules\Admin\Controllers;

use App\Models\Module;
use Modules\Admin\Forms\Fields\FieldBase;

class ModuleController extends ModelControllerBase
{
    protected $model = Module::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'module_id', 'ID', [])
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'Название')
            ->registerField(FieldBase::TYPE_TEXT, 'module_name', 'Имя модуля');
    }

    public function editAction(int $itemId):bool
    {
        $this->flashSession->error($this->labels['action_denied']);
        $this->response->redirect('/' . $this->controller->controller_name);
        return true;
    }

    public function indexAction():bool
    {
        return parent::indexAction();
    }

    public function createAction():bool
    {
        return parent::createAction();
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

