<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Forms\Element\Select;

class SelectField extends FieldBase
{
    private $optionsClass;

    public function __construct(string $name, string $label = '', array $validators = [], string $optionsClass = null)
    {
        parent::__construct($name, $label, $validators);
        $this->optionsClass = $optionsClass;
    }

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
        $className = $this->optionsClass;
        $values = $className::selectOptions($this->name, $params);
        $this->field = (new Select($this->name, $values, [
            'class' => 'form-control'
        ]))
            ->setLabel($this->label);
        if (!empty($item)) $this->field->setDefault($this->getItemVal($item));
        return $this;
    }
}