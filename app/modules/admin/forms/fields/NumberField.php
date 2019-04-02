<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Numeric;

class NumberField extends FieldBase
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
        $attrs = [
            'placeholder' => $this->label,
            'class' => 'form-control'
        ];
        if (!empty($item)) $attrs['value'] = $this->getItemVal($item);
        $this->field = (new Numeric($this->name, $attrs))
            ->setLabel($this->label);
        return $this;
    }
}