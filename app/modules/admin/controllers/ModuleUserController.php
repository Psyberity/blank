<?php
namespace Modules\Admin\Controllers;

use App\Models\ModuleUser;
use Modules\Admin\Forms\Fields\FieldBase;

class ModuleUserController extends ModelControllerBase
{
    protected $model = ModuleUser::class;

    protected $assets_change = [
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

    protected function setEditVars()
    {
        $user_id = [
            'id' => $this->item->user_id,
            'selection' => $this->item->user->name
        ];

        parent::setEditVars();
        $this->view->setVar('user_id', json_encode($user_id));
    }

    public function deleteAction($item_id)
    {
        $module_user = $this->auth->module_user;
        if ($module_user->model_user_id == $item_id) {
            $this->flashSession->error($this->labels['delete_self']);
            return $this->response->redirect('/' . $this->controller->controller_name);
        }
        return parent::deleteAction($item_id);
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

    public function setCommonVars()
    {
        parent::setCommonVars();
    }
}

