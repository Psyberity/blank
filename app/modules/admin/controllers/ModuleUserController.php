<?php
namespace Modules\Admin\Controllers;

use App\Models\ModuleUser;
use Modules\Admin\Forms\Fields\FieldBase;

class ModuleUserController extends ModelControllerBase
{
    protected $model = ModuleUser::class;

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
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'module_user_id', 'ID', [])
            ->registerField(FieldBase::TYPE_SELECT2, 'user_id', 'Пользователь')
            ->registerField(FieldBase::TYPE_SELECT, 'module_role_id', 'Роль', []);
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

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }
}

