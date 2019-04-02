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

    protected function afterCreate():bool
    {
        $this->item->password = $this->security->hash($_POST['password1']);
        if (!$this->item->save()) {
            $this->flashErrors();
        }
        return parent::afterCreate();
    }

    protected function afterEdit():bool
    {
        if (!empty($_POST['password1']) && strlen($_POST['password1']) > 0) {
            $this->item->password = $this->security->hash($_POST['password1']);
        }
        if (!$this->item->save()) {
            $this->flashErrors();
        }
        return parent::afterEdit();
    }

    protected function listValueHandler(string $field, $value)
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

