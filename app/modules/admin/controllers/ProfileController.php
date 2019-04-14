<?php
namespace Modules\Admin\Controllers;

use App\Models\User;
use Modules\Admin\Forms\Fields\ConfirmPasswordField;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\ImageField;
use Modules\Admin\Forms\Fields\TextField;

class ProfileController extends ModelControllerBase // TODO: перенести на ControllerBase
{
    public function initialize()
    {
        $this->registerModel(User::class, 'user_id')
            ->registerField(new TextField('name', 'ФИО'))
            ->registerField(new TextField('email', 'E-mail'))
            ->registerField(new TextField('phone', 'Телефон'))
            ->registerField(new ConfirmPasswordField('password', 'Пароль'))
            ->registerField(new ImageField('avatar', 'Аватар'));

        parent::initialize();
    }

    public function indexAction():bool
    {
        $this->labels['edit'] = 'Профиль';
        return parent::editAction($this->auth->user->user_id);
    }

    protected function editPost(array $params = []):bool
    {
        return parent::editPost($params);
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

