<?php
namespace Modules\Admin\Controllers;

use App\Models\User;
use Modules\Admin\Forms\Fields\CheckboxField;
use Modules\Admin\Forms\Fields\ConfirmPasswordField;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\ImageField;
use Modules\Admin\Forms\Fields\TextField;

class UserController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(User::class, 'user_id')
            ->registerField(new IdField('user_id', 'ID', []))
            ->registerField(new TextField('name', 'ФИО'))
            ->registerField(new TextField('email', 'E-mail'))
            ->registerField(new TextField('phone', 'Телефон', []))
            ->registerField(new ConfirmPasswordField('password', 'Пароль', []))
            ->registerField(new ImageField('avatar', 'Аватар', []))
            ->registerField(new CheckboxField('active', 'Активность', []));

        parent::initialize();
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

