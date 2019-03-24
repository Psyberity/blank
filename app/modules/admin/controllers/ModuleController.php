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

    public function editAction($item_id)
    {
        $this->flashSession->error($this->labels['action_denied']);
        return $this->response->redirect('/' . $this->controller->controller_name);
    }

    public function indexAction()
    {
        return parent::indexAction();
    }

    public function createAction()
    {
        parent::createAction();
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

