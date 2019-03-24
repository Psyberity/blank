<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;

class ConfirmPasswordField extends FieldBase
{
    private $field1;
    private $field2;

    public function renderView(Base $item = null, $params = [])
    {
        return '';
    }

    public function renderEdit(Base $item = null, $params = [])
    {
        $this->compile($item, $params);

        $vars = [
            'field1' => $this->field1,
            'field2' => $this->field2
        ];
        return $this->renderField('edit_confirm_password', $vars);
    }

    public function renderCreate($params = [])
    {
        return $this->renderEdit(null, $params);
    }

    public function getCompiledFields()
    {
        $this->compile();
        return [$this->field1, $this->field2];
    }

    protected function compile(Base $item = null, $params = [])
    {
        $label1 = 'Пароль';
        $this->field1 = (new Password('password1', [
            'placeholder' => $label1,
            'class' => 'form-control'
        ]))
            ->setLabel($label1);

        $label2 = 'Повторите пароль';
        $this->field2 = (new Password('password2', [
            'placeholder' => $label2,
            'class' => 'form-control'
        ]))
            ->setLabel($label1)
            ->addValidator(new Confirmation([
                'message' => 'Пароли не совпадают', 'with' => 'password1'
            ]));
        return $this;
    }
}