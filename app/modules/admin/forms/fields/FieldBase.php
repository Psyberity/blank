<?php
namespace Modules\Admin\Forms\Fields;

use App\Models\Base;
use Phalcon\Di;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class FieldBase implements FieldInterface
{
    protected $name;
    protected $label;
    protected $validators;
    protected $field;

    const TYPE_TEXT             = 1;
    const TYPE_SELECT           = 2;
    const TYPE_HIDDEN           = 3;
    const TYPE_SELECT2          = 4;
    const TYPE_PASSWORD         = 5;
    const TYPE_CONFIRM_PASSWORD = 6;
    const TYPE_NUMBER           = 7;
    const TYPE_TEXTAREA         = 8;
    const TYPE_WYSIWYG          = 9;
    const TYPE_FILE             = 10;
    const TYPE_IMAGE            = 11;
    const TYPE_CHECKBOX         = 12;
    const TYPE_ID               = 13;

    const VALID_PRESENCE        = 1;
    const VALID_EMAIL           = 2;

    public function __construct($name, $label = '', $validators = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->validators = $validators;
    }

    public function render($render_action, Base $item = null, $params = [])
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

    public function renderView(Base $item = null, $params = [])
    {
        return null;
    }

    public function renderEdit(Base $item = null, $params = [])
    {
        return null;
    }

    public function renderCreate($params = [])
    {
        return null;
    }

    protected function getItemVal(Base $item = null)
    {
        if (empty($item)) return null;
        return $item->getClearVal($this->name);
    }

    protected function renderField($view_name, $vars = [])
    {
        return Di::getDefault()->get('view')->getPartial('_fields/' . $view_name, $vars);
    }

    protected function appendValidators()
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

    protected function compile(Base $item = null, $params = [])
    {
        return $this;
    }

    public function getCompiledFields()
    {
        $this->compile()->appendValidators();
        if (empty($this->field)) return [];
        return [$this->field];
    }
}