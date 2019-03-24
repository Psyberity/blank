<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\TextArea;

class TextareaField extends FieldBase
{
    public function renderView(Base $item = null, $params = [])
    {
        $vars = [
            'field_name' => $this->name,
            'value' => $this->getItemVal($item)
        ];
        return $this->renderField('default_view', $vars);
    }

    public function renderEdit(Base $item = null, $params = [])
    {
        $this->compile($item, $params);

        $vars = [
            'field' => $this->field
        ];
        return $this->renderField('default_edit', $vars);
    }

    public function renderCreate($params = [])
    {
        return $this->renderEdit(null, $params);
    }

    protected function compile(Base $item = null, $params = [])
    {
        $attrs = [
            'placeholder' => $this->label
        ];
        if (!empty($item)) $attrs['value'] = $this->getItemVal($item);
        $this->field = (new TextArea($this->name, $attrs))
            ->setLabel($this->label);
        return $this;
    }
}