<?php
namespace Modules\Admin\Controllers;

use App\Models\Action;
use App\Models\ModuleController;
use App\Models\ModuleControllerAction;
use Modules\Admin\Forms\Fields\FieldBase;

class ModuleControllerController extends ModelControllerBase
{
    protected $model = ModuleController::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'module_controller_id', 'ID', [])
            ->registerField(FieldBase::TYPE_SELECT, 'module_id', 'Модуль', [])
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'Название')
            ->registerField(FieldBase::TYPE_TEXT, 'controller_name', 'Имя контроллера');
    }

    protected function setCreateVars()
    {
        parent::setCreateVars();
        $this->view->setVar('actions', Action::find(['order' => 'name']));
    }

    protected function afterCreate()
    {
        $new_action_ids = $this->request->getPost('action_ids');
        if (!empty($new_action_ids)) {
            foreach ($new_action_ids as $new_action_id) {
                $module_controller_action = new ModuleControllerAction();
                $module_controller_action->module_controller_id = $this->item->module_controller_id;
                $module_controller_action->action_id = $new_action_id;
                if (!$module_controller_action->save()) {
                    $this->flashErrors($module_controller_action);
                }
            }
        }
    }

    protected function setEditVars()
    {
        parent::setEditVars();
        $module_controller_actions = $this->item->actions;
        $controller_actions = [];
        if (!empty($module_controller_actions)) {
            foreach ($module_controller_actions as $action) {
                $controller_actions[$action->action_id] = true;
            }
        }
        $this->view->setVar('module_controller_actions', $controller_actions);
        $this->view->setVar('actions', Action::find(['order' => 'name']));
    }

    protected function afterEdit()
    {
        $old_controller_actions_data = $this->item->controller_actions;
        $old_controller_actions = [];
        if (!empty($old_controller_actions_data)) {
            foreach ($old_controller_actions_data as $old_controller_actions_line) {
                $old_controller_actions[$old_controller_actions_line->action_id] = $old_controller_actions_line;
            }
        }
        $new_action_ids = $this->request->getPost('action_ids');
        if (empty($new_action_ids)) $new_action_ids = [];
        $del_actions = [];
        $create_ids = [];
        if (empty($new_action_ids)) {
            $del_actions = $old_controller_actions;
        } else {
            if (empty($old_controller_actions)) {
                $create_ids = $new_action_ids;
            } else {
                foreach ($new_action_ids as $new_action_id) {
                    if (!isset($old_controller_actions[$new_action_id])) {
                        $create_ids[] = $new_action_id;
                    }
                }
                foreach ($old_controller_actions as $old_action_id => $old_controller_action) {
                    if (!in_array($old_action_id, $new_action_ids)) {
                        $del_actions[] = $old_controller_action;
                    }
                }
            }
        }
        if (!empty($del_actions)) {
            foreach ($del_actions as $del_action) {
                if (!$del_action->delete()) {
                    $this->flashErrors($del_action);
                }
            }
        }
        if (!empty($create_ids)) {
            foreach ($create_ids as $create_id) {
                $module_controller_action = new ModuleControllerAction();
                $module_controller_action->module_controller_id = $this->item->module_controller_id;
                $module_controller_action->action_id = $create_id;
                if (!$module_controller_action->save()) {
                    $this->flashErrors($module_controller_action);
                }
            }
        }
    }

    public function indexAction()
    {
        return parent::indexAction();
    }

    public function createAction()
    {
        parent::createAction();
    }

    public function editAction($item_id)
    {
        return parent::editAction($item_id);
    }

    public function deleteAction($item_id)
    {
        return parent::deleteAction($item_id);
    }

    protected function createPost()
    {
        return parent::createPost();
    }

    protected function editPost()
    {
        return parent::editPost();
    }

    public function setCommonVars()
    {
        parent::setCommonVars();
    }

}