<?php
namespace Modules\Admin\Controllers;

use App\Models\User;
use Modules\Admin\Forms\Fields\FieldBase;

class UserController extends ModelControllerBase
{
    protected $model = User::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'user_id', 'ID', [])
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'ФИО')
            ->registerField(FieldBase::TYPE_TEXT, 'email', 'E-mail')
            ->registerField(FieldBase::TYPE_TEXT, 'phone', 'Телефон', [])
            ->registerField(FieldBase::TYPE_CONFIRM_PASSWORD, 'password', 'Пароль', [])
            ->registerField(FieldBase::TYPE_IMAGE, 'avatar', 'Аватар', [])
            ->registerField(FieldBase::TYPE_CHECKBOX, 'active', 'Активность', []);
    }

    protected function afterCreate()
    {
        $this->item->password = $this->security->hash($_POST['password1']);
        if (!$this->item->save()) {
            $this->flashErrors();
        }
        return parent::afterCreate();
    }

    protected function afterEdit()
    {
        if (!empty($_POST['password1']) && strlen($_POST['password1']) > 0) {
            $this->item->password = $this->security->hash($_POST['password1']);
        }
        if (!$this->item->save()) {
            $this->flashErrors();
        }
        return parent::afterEdit();
    }

    protected function listValueHandler($field, $value)
    {
        switch ($field) {
            case 'active':
                $value = ($value == 1) ? 'Да' : 'Нет';
                break;
            default:
                break;
        }
        return $value;
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

