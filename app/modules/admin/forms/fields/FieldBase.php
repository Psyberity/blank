<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Di;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

abstract class FieldBase
{
    protected $name;
    protected $label;
    protected $validators;
    protected $field;
    protected $params;

    const VALID_PRESENCE        = 1;
    const VALID_EMAIL           = 2;

    public function __construct(string $name, string $label = '', array $validators = [FieldBase::VALID_PRESENCE], array $params = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->validators = $validators;
        $this->params = $params;
    }

    final public function getName():string
    {
        return $this->name;
    }

    final public function getLabel():string
    {
        return $this->label;
    }

    abstract public function renderView(Base $item = null, array $params = []):string ;

    abstract public function renderEdit(Base $item = null, array $params = []):string ;

    abstract public function renderCreate(array $params = []):string;

    abstract protected function compile(Base $item = null, array $params = []):self ;

    protected function getItemVal(Base $item = null)
    {
        if (empty($item)) return null;
        return $item->getClearVal($this->name);
    }

    protected function renderField(string $viewName, array $vars = []):string
    {
        return Di::getDefault()->get('view')->getPartial('_fields/' . $viewName, $vars);
    }

    protected function appendValidators():self
    {
        if (!empty($this->validators) && ! empty($this->field)) {
            foreach ($this->validators as $validator) {
                switch ($validator) {
                    case self::VALID_PRESENCE:
                        $this->field->addValidator(new PresenceOf([
                            'message' => 'Поле "' . $this->label . '" обязательно к заполнению'
                        ]));
                        break;
                    case self::VALID_EMAIL:
                        $this->field->addValidator(new Email([
                            'message' => 'Поле "' . $this->label . '" заполнено некорректно'
                        ]));
                        break;
                    default:
                        break;
                }
            }
        }
        return $this;
    }

    public function getCompiledFields(array $params = []):array
    {
        $this->compile(null, $params)->appendValidators();
        if (empty($this->field)) return [];
        return [$this->field];
    }

    public function render(string $render_action, Base $item = null, array $params = []):string
    {
        switch ($render_action) {
            case 'view':
                return $this->renderView($item, $params);
            case 'edit':
                return $this->renderEdit($item, $params);
            case 'create':
                return $this->renderCreate($params);
            default:
                return '';
        }
    }
}