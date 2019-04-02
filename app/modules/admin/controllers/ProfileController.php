<?php
namespace Modules\Admin\Controllers;

use App\Models\User;
use Modules\Admin\Forms\Fields\FieldBase;

class ProfileController extends ModelControllerBase // TODO: перенести на ControllerBase
{
    protected $model = User::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_TEXT, 'name', 'ФИО')
            ->registerField(FieldBase::TYPE_TEXT, 'email', 'E-mail')
            ->registerField(FieldBase::TYPE_TEXT, 'phone', 'Телефон')
            ->registerField(FieldBase::TYPE_CONFIRM_PASSWORD, 'password', 'Пароль')
            ->registerField(FieldBase::TYPE_IMAGE, 'avatar', 'Аватар');
    }

    public function indexAction():bool
    {
        $this->labels['edit'] = 'Профиль';
        return parent::editAction($this->auth->user->user_id);
    }

    protected function editPost():bool
    {
        return parent::editPost();
    }

    protected function setEditVars():void
    {
        parent::setEditVars();
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

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }
}

