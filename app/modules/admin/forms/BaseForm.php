<?php
namespace Modules\Admin\Forms;

use Phalcon\Forms\Element;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Numeric,
    Phalcon\Forms\Element\TextArea,
    Phalcon\Forms\Element\Password,
    Phalcon\Forms\Element\Submit,
    Phalcon\Validation\Validator\Email,
    Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\Confirmation;

class BaseForm extends Form
{
    protected $messages = [
        'presence_of' => 'Поле {{label}} обязательно к заполнению',
        'email' => 'Поле {{label}} заполнено некорректно'
    ];

    protected function addDefaultSelect(string $name, string $label, array $values):void
    {
        $field = (new Select($name, $values, [
            'class' => 'form-control'
        ]))
            ->setLabel($label);
        $this->add($field);
    }

    protected function addDefaultText(string $name, string $label, array $validators = ['presence_of']):void
    {
        $field = (new Text($name, [
            'placeholder' => $label,
            'class' => 'form-control'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultHidden(string $name, $default = null):void
    {
        $field = new Hidden($name, [
            'field_type' => 'hidden'
        ]);
        if (!empty($default)) $field->setDefault($default);
        $this->add($field);
    }

    protected function addDefaultSelect2(string $name, string $label, array $validators = []):void
    {
        $field = (new Text($name, [
            'placeholder' => $label,
            'style' => 'width: 100%',
            'id' => $name
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultPassword(string $name, string $label = 'Пароль', array $validators = ['presence_of']):void
    {
        $field = (new Password($name, [
            'placeholder' => $label,
            'class' => 'form-control'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addConfirmPassword(
        array $validators = ['presence_of'],
        string $name1 = 'password1',
        string $name2 = 'password2',
        string $label1 = 'Пароль',
        string $label2 = 'Повторите пароль'
    ):void
    {
        $field = (new Password($name1, [
            'placeholder' => $label1,
            'class' => 'form-control'
        ]))
            ->setLabel($label1);
        $field = $this->addValidators($field, $label1, $validators);
        $this->add($field);

        $field = (new Password($name2, [
            'placeholder' => $label2,
            'class' => 'form-control'
        ]))
            ->setLabel($label2);
        $validators[] = ['confirmation', $name1];
        $field = $this->addValidators($field, $label2, $validators);
        $this->add($field);
    }

    protected function addDefaultNumber(string $name, string $label, array $validators = ['presence_of']):void
    {
        $field = (new Numeric($name, [
            'placeholder' => $label,
            'class' => 'form-control'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultTextArea(string $name, string $label, array $validators = ['presence_of']):void
    {
        $field = (new TextArea($name, [
            'placeholder' => $label
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultWysiwyg(string $name, string $label, array $validators = ['presence_of']):void
    {
        $field = (new TextArea($name, [
            'placeholder' => $label,
            'class' => 'summernote'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultFile(string $name, string $label, array $validators = []):void
    {
        $field = (new File($name, [
            'placeholder' => $label,
            'class' => 'form-control'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultImage(string $name, string $label, array $validators = []):void
    {
        $field = (new File($name, [
            'placeholder' => $label,
            'class' => 'form-control',
            'field_type' => 'image'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addDefaultSubmit(string $label):void
    {
        $field = (new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => $label
        ]));
        $this->add($field);
    }

    protected function addDefaultCheckbox(string $name, string $label, array $validators = []):void
    {
        $this->addDefaultHidden('checkboxes[' . $name . ']', 1);
        $field = (new Check($name, [
            'class' => 'onoffswitch-checkbox',
            'id' => $name,
            'field_type' => 'checkbox'
        ]))
            ->setLabel($label);
        $field = $this->addValidators($field, $label, $validators);
        $this->add($field);
    }

    protected function addValidators(Element $field, string $label, array $validators = []):Element
    {
        if (!empty($validators)) {
            foreach ($validators as $validator) {
                $data = [];
                if (is_array($validator)) {
                    $data = $validator[1];
                    $validator = $validator[0];
                }
                switch ($validator) {
                    case 'presence_of':
                        $field->addValidator(new PresenceOf(['message' => $this->message('presence_of', ['label' => $label])]));
                        break;
                    case 'email':
                        $field->addValidator(new Email(['message' => $this->message('email', ['label' => $label])]));
                        break;
                    case 'confirmation':
                        $field->addValidator(new Confirmation(['message' => 'Пароли не совпадают', 'with' => $data]));
                        break;
                    default:
                        break;
                }
            }
        }
        return $field;
    }

    protected function message(string $name, array $vars):string
    {
        $message = $this->messages[$name];
        if (!empty($vars)) {
            foreach ($vars as $key => $val) {
                $message = str_replace('{{' . $key . '}}', $val, $message);
            }
        }
        return $message;
    }
}