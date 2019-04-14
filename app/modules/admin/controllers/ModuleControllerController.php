<?php
namespace Modules\Admin\Controllers;

use App\Models\Action;
use App\Models\ModuleController;
use App\Models\ModuleControllerAction;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\SelectField;
use Modules\Admin\Forms\Fields\TextField;

class ModuleControllerController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(ModuleController::class, 'module_controller_id')
            ->registerField(new IdField('module_controller_id', 'ID', []))
            ->registerField(new SelectField('module_id', 'Модуль', [], [$this->model]))
            ->registerField(new TextField('name', 'Название'))
            ->registerField(new TextField('controller_name', 'Имя контроллера'));

        parent::initialize();
    }

    protected function setCreateVars():void
    {
        parent::setCreateVars();
        $this->view->setVar('actions', Action::find(['order' => 'name']));
    }

    protected function afterCreate():bool
    {
        $newActionIds = $this->request->getPost('action_ids');
        if (!empty($newActionIds)) {
            foreach ($newActionIds as $newActionId) {
                $ModuleControllerAction = new ModuleControllerAction();
                $ModuleControllerAction->module_controller_id = $this->item->module_controller_id;
                $ModuleControllerAction->action_id = $newActionId;
                if (!$ModuleControllerAction->save()) {
                    $this->flashErrors($ModuleControllerAction);
                }
            }
        }
        return true;
    }

    protected function setEditVars():void
    {
        parent::setEditVars();
        $moduleControllerActions = $this->item->actions;
        $controllerActions = [];
        if (!empty($moduleControllerActions)) {
            foreach ($moduleControllerActions as $action) {
                $controllerActions[$action->action_id] = true;
            }
        }
        $this->view->setVar('module_controller_actions', $controllerActions);
        $this->view->setVar('actions', Action::find(['order' => 'name']));
    }

    protected function afterEdit():bool
    {
        $oldControllerActionsData = $this->item->controller_actions;
        $oldControllerActions = [];
        if (!empty($oldControllerActionsData)) {
            foreach ($oldControllerActionsData as $oldControllerActionsLine) {
                $oldControllerActions[$oldControllerActionsLine->action_id] = $oldControllerActionsLine;
            }
        }
        $newActionIds = $this->request->getPost('action_ids');
        if (empty($newActionIds)) $newActionIds = [];
        $delActions = [];
        $createIds = [];
        if (empty($newActionIds)) {
            $delActions = $oldControllerActions;
        } else {
            if (empty($oldControllerActions)) {
                $createIds = $newActionIds;
            } else {
                foreach ($newActionIds as $newActionId) {
                    if (!isset($oldControllerActions[$newActionId])) {
                        $createIds[] = $newActionId;
                    }
                }
                foreach ($oldControllerActions as $oldActionId => $oldControllerAction) {
                    if (!in_array($oldActionId, $newActionIds)) {
                        $delActions[] = $oldControllerAction;
                    }
                }
            }
        }
        if (!empty($delActions)) {
            foreach ($delActions as $delAction) {
                if (!$delAction->delete()) {
                    $this->flashErrors($delAction);
                }
            }
        }
        if (!empty($createIds)) {
            foreach ($createIds as $createId) {
                $ModuleControllerAction = new ModuleControllerAction();
                $ModuleControllerAction->module_controller_id = $this->item->module_controller_id;
                $ModuleControllerAction->action_id = $createId;
                if (!$ModuleControllerAction->save()) {
                    $this->flashErrors($ModuleControllerAction);
                }
            }
        }
        return true;
    }

    public function indexAction():bool
    {
        return parent::indexAction();
    }

    public function createAction():bool
    {
        return parent::createAction();
    }

    public function editAction(int $itemId):bool
    {
        return parent::editAction($itemId);
    }

    public function deleteAction(int $itemId):bool
    {
        return parent::deleteAction($itemId);
    }

    protected function createPost(array $params = []):bool
    {
        return parent::createPost($params);
    }

    protected function editPost(array $params = []):bool
    {
        return parent::editPost($params);
    }

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }

}