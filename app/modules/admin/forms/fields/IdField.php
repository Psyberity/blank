<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;

class IdField extends FieldBase
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
        return '';
    }

    public function renderCreate($params = [])
    {
        return '';
    }

    protected function compile(Base $item = null, $params = [])
    {
        return $this;
    }
}