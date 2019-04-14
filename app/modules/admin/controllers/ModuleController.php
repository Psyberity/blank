<?php
namespace Modules\Admin\Controllers;

use App\Models\Module;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\TextField;

class ModuleController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(Module::class, 'module_id')
            ->registerField(new IdField('module_id', 'ID', []))
            ->registerField(new TextField('name', 'Название'))
            ->registerField(new TextField('module_name', 'Имя модуля'));

        parent::initialize();
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

