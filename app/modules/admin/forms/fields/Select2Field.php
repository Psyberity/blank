<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Text;

class Select2Field extends FieldBase
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
        $this->field = (new Text($this->name, [
            'placeholder' => $this->label,
            'style' => 'width: 100%',
            'id' => $this->name
        ]))
            ->setLabel($this->label);
        return $this;
    }
}