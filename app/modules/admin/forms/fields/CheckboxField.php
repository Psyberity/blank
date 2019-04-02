<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;

class CheckboxField extends FieldBase
{
    public function renderView(Base $item = null, array $params = []):string
    {
        $vars = [
            'field_name' => $this->name,
            'value' => $this->getItemVal($item)
        ];
        return $this->renderField('default_view', $vars);
    }

    public function renderEdit(Base $item = null, array $params = []):string
    {
        $hidden = (new Hidden('checkboxes[' . $this->name . ']'))
            ->setDefault(1);
        $this->compile($item, $params);
        $vars = [
            'hidden' => $hidden,
            'field' => $this->field,
            'name' => $this->name
        ];
        return $this->renderField('edit_checkbox', $vars);
    }

    public function renderCreate(array $params = []):string
    {
        return $this->renderEdit(null, $params);
    }

    protected function compile(Base $item = null, array $params = []):parent
    {
        $attrs = [
            'class' => 'onoffswitch-checkbox',
            'id' => $this->name
        ];
        if (!empty($item) && $this->getItemVal($item) == 1) {
            $attrs['checked'] = 'checked';
        }
        $this->field = (new Check($this->name, $attrs))
            ->setLabel($this->label);
        return $this;
    }
}