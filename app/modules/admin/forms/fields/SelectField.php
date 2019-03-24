<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Select;

class SelectField extends FieldBase
{
    private $options_class;

    public function __construct($name, $label = '', $validators = [], $options_class = null)
    {
        parent::__construct($name, $label, $validators);
        $this->options_class = $options_class;
    }

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
        $class_name = $this->options_class;
        $values = $class_name::selectOptions($this->name, $params);
        $this->field = (new Select($this->name, $values, [
            'class' => 'form-control'
        ]))
            ->setLabel($this->label);
        if (!empty($item)) $this->field->setDefault($this->getItemVal($item));
        return $this;
    }
}