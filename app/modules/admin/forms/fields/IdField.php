<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;

class IdField extends FieldBase
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
        return '';
    }

    public function renderCreate(array $params = []):string
    {
        return '';
    }

    protected function compile(Base $item = null, array $params = []):parent
    {
        return $this;
    }
}