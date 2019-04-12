<?php
namespace Modules\Admin\Controllers;

use App\Classes\ExtensionException;
use App\Classes\FileUpload;
use App\Classes\UnlinkException;
use App\Traits\AdminControllerTrait;
use App\Traits\ControllerTrait;
use App\Models\Base;
use Modules\Admin\Forms\Fields\FieldBase;
use Phalcon\Forms\Form;
use Phalcon\Mvc\Controller;

class ModelControllerBase extends Controller
{
    use ControllerTrait, AdminControllerTrait;

    public $module;
    public $controller;
    public $action;
    public $functions;
    public $acl;
    public $auth;
    public $lang;
    public $apiUrl;

    protected $model;
    protected $labels;
    protected $fileFields;
    protected $fields;
    protected $item;
    protected $primaryKey;
    protected $assetsChange;
    protected $moduleName = 'admin';

    public function initialize()
    {
        if (empty($this->model)) {
            $this->flashSession->error('Класс модели не указан в контроллере: ' . self::class);
            $this->response->redirect('');
            return false;
        }
        $this->setAssets($this->action->action_name);
        $modelClass = $this->model;
        $this->labels = $modelClass::$labels;
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
        if (!empty($this->fileFields)) {
            foreach ($this->fileFields as $fileField) {
                $filename = $this->item->getVal([$this->primaryKey]) . $fileField;
                $uploadDir = $this->item->checkUploadDir($this->module->getDir('module_upload'));
                $fileUpload = new FileUpload($fileField, $filename, $_SERVER['DOCUMENT_ROOT'] . $uploadDir);
                try {
                    if ($fileUpload->upload($this->request)) {
                        $this->item->$fileField = $uploadDir . '/' . $fileUpload->getUploadedName();
                        $this->item->update();
                    }
                } catch (ExtensionException $exception) {
                    $this->flashSession->error($exception->getMessage());
                }
                $deleteFile = $this->request->getPost($fileField . '-delete');
                if (!empty($deleteFile)) {
                    try {
                        $this->item->deleteFile($fileField);
                        $this->flashSession->success('Файл "' . $this->fields[$fileField]->getLabel() . '" удален');
                    } catch (UnlinkException $exception) {
                        $this->flashSession->error($exception->getMessage());
                    }
                }
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
        $this->view->setVar('item_id', $this->item->getVal([$this->primaryKey]));
        $this->view->setVar('item', $this->item);
        $this->view->setVar('h2', $this->labels['edit']);
        $this->view->setVar('submit_label', 'Сохранить');
        $this->view->setVar('render_action', 'edit');
        $this->view->setVar('fields', $this->fields);
        $this->view->setVar('tab', (isset($_GET['tab'])) ? $_GET['tab'] : 'tab-info');
    }

    protected function setViewVars():void
    {
        $this->view->setVar('item_id', $this->item->getVal([$this->primaryKey]));
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
            try {
                if (!$this->item->delete()) {
                    $this->flashErrors();
                } else {
                    $this->flashSession->success($this->labels['deleted']);
                }
            } catch (UnlinkException $exception) {
                $this->flashSession->error($exception->getMessage());
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

    protected function flashErrors($object = null):void
    {
        if ($object === null) $object = $this->item;
        foreach ($object->getMessages() as $message) {
            $this->flashSession->error($message->getMessage());
        }
    }

    public function registerField(FieldBase $fieldObject):ModelControllerBase
    {
        $this->fields[$fieldObject->getName()] = $fieldObject;
        return $this;
    }

    public function registerModel(string $modelClass, string $primaryKey):ModelControllerBase
    {
        $this->model = $modelClass;
        $this->primaryKey = $primaryKey;
        return $this;
    }
}
