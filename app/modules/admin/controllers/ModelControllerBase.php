<?php
namespace Modules\Admin\Controllers;

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
    public $api_url;

    protected $model;
    protected $labels;
    protected $file_fields;
    protected $fields;
    protected $item;
    protected $primary_key;
    protected $assets_change;

    public function initialize()
    {
        if (empty($this->model)) {
            $this->flashSession->error('Класс модели не указан в контроллере: ' . self::class);
            return $this->response->redirect('');
        }
        parent::initialize();
        $model_class = $this->model;
        $this->labels = $model_class::$labels;
        $this->primary_key = $model_class::$primary_key;
        $this->file_fields = $model_class::$file_fields;
    }

    public function indexAction()
    {
        $this->setCommonVars();

        $this->view->setVar('h2', $this->labels['index']);
        $this->view->setVar('labels', $this->labels);
        return true;
    }

    protected function handlePost()
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

    protected function afterCreate()
    {
        $this->uploadFiles();
        return true;
    }

    protected function afterEdit()
    {
        $this->uploadFiles();
        return true;
    }

    protected function uploadFiles()
    {
        $files = $this->request->getUploadedFiles(true);
        if (!empty($files)) {
            foreach ($files as $file) {
                $key = $file->getKey();
                $extension = $file->getExtension();
                if (empty($extension)) continue;
                if (!in_array($key, $this->file_fields)) continue;
                $upload_dir = $this->item->checkUploadDir($this->module->getDirs('module_upload'));
                $pk = $this->primary_key;
                $upload_file = $this->item->$pk . $key . '.' . $extension;
                $upload_path = $_SERVER['DOCUMENT_ROOT'] . $upload_dir . '/' . $upload_file;
                $file->moveTo($upload_path);
                $this->item->$key = $upload_dir . '/' . $upload_file;
                $this->item->update();
            }
        }
    }

    protected function createPost()
    {
        if ($this->request->isPost()) {

            $form = new Form();
            foreach ($this->fields as $field) {
                $form_fields = $field->getCompiledFields();
                if (!empty($form_fields)) {
                    foreach ($form_fields as $form_field) {
                        $form->add($form_field);
                    }
                }
            }

            $this->item = new $this->model();
            $form->bind($this->handlePost(), $this->item);
            if ($form->isValid()) {
                if ($this->item->save()) {
                    $this->flashSession->success($this->labels['created']);
                    $this->afterCreate();
                    return $this->response->redirect('/' . $this->controller->controller_name);
                } else {
                    $this->flashErrors();
                }
            } else {
                $this->flashErrors($form);
            }
        }
        return false;
    }

    protected function editPost()
    {
        if ($this->request->isPost()) {

            $form = new Form();
            foreach ($this->fields as $field) {
                $form_fields = $field->getCompiledFields();
                if (!empty($form_fields)) {
                    foreach ($form_fields as $form_field) {
                        $form->add($form_field);
                    }
                }
            }
            $form->bind($this->handlePost(), $this->item);

            if ($form->isValid()) {
                if ($this->item->save()) {
                    $this->flashSession->success($this->labels['edited']);
                    $this->afterEdit();
                    return $this->response->redirect('/' . $this->controller->controller_name);
                } else {
                    $this->flashErrors();
                }
            } else {
                $this->flashErrors($form);
            }
        }
        return false;
    }

    protected function setCreateVars()
    {
        $this->view->setVar('h2', $this->labels['create']);
        $this->view->setVar('submit_label', 'Добавить');
        $this->view->setVar('render_action', 'create');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    protected function setEditVars()
    {
        $primary_key = $this->primary_key;
        $this->view->setVar('item_id', $this->item->$primary_key);
        $this->view->setVar('item', $this->item);
        $this->view->setVar('h2', $this->labels['edit']);
        $this->view->setVar('submit_label', 'Сохранить');
        $this->view->setVar('render_action', 'edit');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    protected function setViewVars()
    {
        $primary_key = $this->primary_key;
        $this->view->setVar('item_id', $this->item->$primary_key);
        $this->view->setVar('item', $this->item);
        $this->view->setVar('h2', $this->labels['edit']);
        $this->view->setVar('render_action', 'view');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    public function createAction()
    {
        $this->setCommonVars();

        $return = $this->createPost();
        if ($return) return $return;

        $this->setCreateVars();
        if (!is_file($this->currentViewPath())) $this->view->pick('partial/ce_container');
        return true;
    }

    public function editAction($item_id)
    {
        $this->setCommonVars();

        $model_class = $this->model;
        $this->item = $model_class::findFirst($item_id);
        if (!$this->item) {
            $this->flashSession->error($this->labels['not_found']);
            return $this->response->redirect('/' . $this->controller->controller_name);
        }

        $return = $this->editPost();
        if ($return) return $return;

        $this->setEditVars();
        if (!is_file($this->currentViewPath())) $this->view->pick('partial/ce_container');
        return true;
    }

    public function viewAction($item_id)
    {
        $this->setCommonVars();

        $model_class = $this->model;
        $this->item = $model_class::findFirst($item_id);
        if (!$this->item) {
            $this->flashSession->error($this->labels['not_found']);
            return $this->response->redirect('/' . $this->controller->controller_name);
        }

        $this->setViewVars();
        if (!is_file($this->currentViewPath())) $this->view->pick('partial/view_container');
        return true;
    }

    public function deleteAction($item_id)
    {
        $model_class = $this->model;
        $this->item = $model_class::findFirst($item_id);
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
        return $this->response->redirect('/' . $this->controller->controller_name);
    }

    protected function listColumnHandler($item, $column_data)
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

    protected function listValueHandler($field, $value)
    {
        return $value;
    }

    protected function flashErrors($object = null)
    {
        if ($object === null) $object = $this->item;
        parent::flashErrors($object);
    }

    public function registerField($field_type, $name, $label = '', $validators = [FieldBase::VALID_PRESENCE])
    {
        switch ($field_type) {
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
