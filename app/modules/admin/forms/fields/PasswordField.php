<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Password;

class PasswordField extends FieldBase
{
    public function renderView(Base $item = null, array $params = []):string
    {
        return '';
    }

    public function renderEdit(Base $item = null, array $params = []):string
    {
        $this->compile($item, $params);

        $vars = [
            'field' => $this->field
        ];
        return $this->renderField('default_edit', $vars);
    }

    public function renderCreate(array $params = []):string
    {
        return $this->renderEdit(null, $params);
    }

    protected function compile(Base $item = null, array $params = []):parent
    {
        $this->field = (new Password($this->name, [
            'placeholder' => $this->label,
            'class' => 'form-control'
        ]))
            ->setLabel($this->label);
        return $this;
    }
}