<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;

interface FieldInterface
{
    public function render($render_action, Base $item, $params);
    public function renderView(Base $item, $params);
    public function renderEdit(Base $item, $params);
    public function renderCreate($params);
}