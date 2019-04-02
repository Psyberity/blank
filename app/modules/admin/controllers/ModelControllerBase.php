<?php
namespace Modules\Admin\Controllers;

use App\Models\Base;
use Modules\Admin\Forms\Fields\CheckboxField;
use Modules\Admin\Forms\Fields\ConfirmPasswordField;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\FileField;
use Modules\Admin\Forms\Fields\HiddenField;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\ImageField;
use Modules\Admin\Forms\Fields\NumberField;
use Modules\Admin\Forms\Fields\PasswordField;
use Modules\Admin\Forms\Fields\Select2Field;
use Modules\Admin\Forms\Fields\SelectField;
use Modules\Admin\Forms\Fields\TextareaField;
use Modules\Admin\Forms\Fields\TextField;
use Modules\Admin\Forms\Fields\WysiwygField;
use Phalcon\Forms\Form;
use Phalcon\Mvc\Controller as PhalconController;

class ModelControllerBase extends ControllerBase
{
    public $module;
    public $controller;
    public $action;
    public $functions;
    public $acl;
    public $auth;
    public $lang;

    protected $model;
    protected $labels;
    protected $fileFields;
    protected $fields;
    protected $item;
    protected $primaryKey;

    public function initialize()
    {
        if (empty($this->model)) {
            $this->flashSession->error('Класс модели не указан в контроллере: ' . self::class);
            $this->response->redirect('');
            return false;
        }
        parent::initialize();
        $modelClass = $this->model;
        $this->labels = $modelClass::$labels;
        $this->primaryKey = $modelClass::$primaryKey;
        $this->fileFields = $modelClass::$fileFields;
    }

    public function indexAction():bool
    {
        $this->setCommonVars();

        $this->view->setVar('h2', $this->labels['index']);
        $this->view->setVar('labels', $this->labels);
        return true;
    }

    protected function handlePost():?array
    {
        $checkboxes = $this->request->getPost('checkboxes');
        if (!empty($checkboxes) && is_array($checkboxes)) {
            foreach ($checkboxes as $field => $nm) {
                $value = ($this->request->hasPost($field)) ? 1 : 0;
                $_POST[$field] = $value;
            }
        }
        return $_POST;
    }

    protected function afterCreate():bool
    {
        $this->uploadFiles();
        return true;
    }

    protected function afterEdit():bool
    {
        $this->uploadFiles();
        return true;
    }

    protected function uploadFiles():void
    {
        $files = $this->request->getUploadedFiles(true);
        if (!empty($files)) {
            foreach ($files as $file) {
                $key = $file->getKey();
                $extension = $file->getExtension();
                if (empty($extension)) continue;
                if (!in_array($key, $this->fileFields)) continue;
                $uploadDir = $this->item->checkUploadDir($this->module->getDir('module_upload'));
                $pk = $this->primaryKey;
                $uploadFile = $this->item->$pk . $key . '.' . $extension;
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir . '/' . $uploadFile;
                $file->moveTo($uploadPath);
                $this->item->$key = $uploadDir . '/' . $uploadFile;
                $this->item->update();
            }
        }
    }

    protected function createPost():bool
    {
        if ($this->request->isPost()) {

            $form = new Form();
            foreach ($this->fields as $field) {
                $formFields = $field->getCompiledFields();
                if (!empty($formFields)) {
                    foreach ($formFields as $formField) {
                        $form->add($formField);
                    }
                }
            }

            $this->item = new $this->model();
            $form->bind($this->handlePost(), $this->item);
            if ($form->isValid()) {
                if ($this->item->save()) {
                    $this->flashSession->success($this->labels['created']);
                    $this->afterCreate();
                    $this->response->redirect('/' . $this->controller->controller_name);
                    return true;
                } else {
                    $this->flashErrors();
                }
            } else {
                $this->flashErrors($form);
            }
        }
        return false;
    }

    protected function editPost():bool
    {
        if ($this->request->isPost()) {

            $form = new Form();
            foreach ($this->fields as $field) {
                $formFields = $field->getCompiledFields();
                if (!empty($formFields)) {
                    foreach ($formFields as $formField) {
                        $form->add($formField);
                    }
                }
            }
            $form->bind($this->handlePost(), $this->item);

            if ($form->isValid()) {
                if ($this->item->save()) {
                    $this->flashSession->success($this->labels['edited']);
                    $this->afterEdit();
                    $this->response->redirect('/' . $this->controller->controller_name);
                    return true;
                } else {
                    $this->flashErrors();
                }
            } else {
                $this->flashErrors($form);
            }
        }
        return false;
    }

    protected function setCreateVars():void
    {
        $this->view->setVar('h2', $this->labels['create']);
        $this->view->setVar('submit_label', 'Добавить');
        $this->view->setVar('render_action', 'create');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    protected function setEditVars():void
    {
        $primaryKey = $this->primaryKey;
        $this->view->setVar('item_id', $this->item->$primaryKey);
        $this->view->setVar('item', $this->item);
        $this->view->setVar('h2', $this->labels['edit']);
        $this->view->setVar('submit_label', 'Сохранить');
        $this->view->setVar('render_action', 'edit');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    protected function setViewVars():void
    {
        $primaryKey = $this->primaryKey;
        $this->view->setVar('item_id', $this->item->$primaryKey);
        $this->view->setVar('item', $this->item);
        $this->view->setVar('h2', $this->labels['edit']);
        $this->view->setVar('render_action', 'view');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    public function createAction():bool
    {
        $this->setCommonVars();

        $return = $this->createPost();
        if ($return) return $return;

        $this->setCreateVars();
        if (!is_file($this->currentViewPath())) $this->view->pick('partial/ce_container');
        return true;
    }

    public function editAction(int $itemId):bool
    {
        $this->setCommonVars();

        $modelClass = $this->model;
        $this->item = $modelClass::findFirst($itemId);
        if (!$this->item) {
            $this->flashSession->error($this->labels['not_found']);
            $this->response->redirect('/' . $this->controller->controller_name);
            return false;
        }

        $return = $this->editPost();
        if ($return) return $return;

        $this->setEditVars();
        if (!is_file($this->currentViewPath())) $this->view->pick('partial/ce_container');
        return true;
    }

    public function viewAction(int $itemId):bool
    {
        $this->setCommonVars();

        $modelClass = $this->model;
        $this->item = $modelClass::findFirst($itemId);
        if (!$this->item) {
            $this->flashSession->error($this->labels['not_found']);
            $this->response->redirect('/' . $this->controller->controller_name);
            return false;
        }

        $this->setViewVars();
        if (!is_file($this->currentViewPath())) $this->view->pick('partial/view_container');
        return true;
    }

    public function deleteAction(int $itemId):bool
    {
        $modelClass = $this->model;
        $this->item = $modelClass::findFirst($itemId);
        if ($this->item) {
            if (!$this->item->delete()) {
                $this->flashErrors();
            } else {
                // TODO: перенести в labels
                $this->flashSession->success('Запись успешно удалена');
            }
        } else {
            $this->flashSession->error($this->labels['not_found']);
        }
        $this->response->redirect('/' . $this->controller->controller_name);
        return true;
    }

    protected function listColumnHandler(Base $item, array $column_data)
    {
        $value = '';
        foreach ($column_data as $field => $value_chain) {
            $value = $item;
            $n = 0;
            foreach ($value_chain as $unit) {
                $n++;
                $value = $value->$unit;
                if (empty($value) && $n < count($value_chain)) {
                    $value = null;
                    break;
                }
            }
            $value = $this->listValueHandler($field, $value);
        }
        return $value;
    }

    protected function listValueHandler(string $field, $value)
    {
        return $value;
    }

    protected function flashErrors(Base $object = null):void
    {
        if ($object === null) $object = $this->item;
        parent::flashErrors($object);
    }

    public function registerField(int $fieldType, string $name, string $label = '', array $validators = [FieldBase::VALID_PRESENCE]):ControllerBase
    {
        switch ($fieldType) {
            case FieldBase::TYPE_TEXT:
                $field = new TextField($name, $label, $validators);
                break;
            case FieldBase::TYPE_SELECT:
                $field = new SelectField($name, $label, $validators, $this->model);
                break;
            case FieldBase::TYPE_HIDDEN:
                $field = new HiddenField($name, $label, $validators);
                break;
            case FieldBase::TYPE_SELECT2:
                $field = new Select2Field($name, $label, $validators);
                break;
            case FieldBase::TYPE_PASSWORD:
                $field = new PasswordField($name, $label, $validators);
                break;
            case FieldBase::TYPE_CONFIRM_PASSWORD:
                $field = new ConfirmPasswordField($name, $label, $validators);
                break;
            case FieldBase::TYPE_NUMBER:
                $field = new NumberField($name, $label, $validators);
                break;
            case FieldBase::TYPE_TEXTAREA:
                $field = new TextareaField($name, $label, $validators);
                break;
            case FieldBase::TYPE_WYSIWYG:
                $field = new WysiwygField($name, $label, $validators);
                break;
            case FieldBase::TYPE_FILE:
                $field = new FileField($name, $label, $validators);
                break;
            case FieldBase::TYPE_IMAGE:
                $field = new ImageField($name, $label, $validators);
                break;
            case FieldBase::TYPE_CHECKBOX:
                $field = new CheckboxField($name, $label, $validators);
                break;
            case FieldBase::TYPE_ID:
                $field = new IdField($name, $label, $validators);
                break;
            default:
                $field = null;
                break;
        }
        $this->fields[$name] = $field;
        return $this;
    }
}
