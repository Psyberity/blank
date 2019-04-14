<?php
namespace Modules\Admin\Controllers;

use App\Models\ModuleUser;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\Select2Field;
use Modules\Admin\Forms\Fields\SelectField;

class ModuleUserController extends ModelControllerBase
{
    protected $assetsChange = [
        'create' => [
            'js' => ['module_user_create' => true]
        ],
        'edit' => [
            'js' => ['module_user_edit' => true]
        ]
    ];

    public function initialize()
    {
        $this->registerModel(ModuleUser::class, 'module_user_id')
            ->registerField(new IdField('module_user_id', 'ID', []))
            ->registerField(new Select2Field('user_id', 'Пользователь'))
            ->registerField(new SelectField('module_role_id', 'Роль', [], [$this->model]));

        parent::initialize();
    }

    protected function setEditVars():void
    {
        $userId = [
            'id' => $this->item->user_id,
            'selection' => $this->item->user->name
        ];

        parent::setEditVars();
        $this->view->setVar('user_id', json_encode($userId));
    }

    public function deleteAction(int $itemId):bool
    {
        $moduleUser = $this->auth->moduleUser;
        if ($moduleUser->model_user_id == $itemId) {
            $this->flashSession->error($this->labels['delete_self']);
            $this->response->redirect('/' . $this->controller->controller_name);
            return false;
        }
        return parent::deleteAction($itemId);
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

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }
}

