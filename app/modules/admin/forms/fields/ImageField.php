<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\File;

class ImageField extends FieldBase
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
        if (!empty($item)) $vars['value'] = $this->getItemVal($item) ?? '';

        return $this->renderField('edit_image', $vars);
    }

    public function renderCreate($params = [])
    {
        return $this->renderEdit(null, $params);
    }

    protected function compile(Base $item = null, $params = [])
    {
        $this->field = (new File($this->name, [
            'placeholder' => $this->label,
            'class' => 'form-control'
        ]))
            ->setLabel($this->label);
        return $this;
    }
}