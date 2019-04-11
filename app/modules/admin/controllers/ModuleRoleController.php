<?php
namespace Modules\Admin\Controllers;

use App\Models\ModuleController,
    App\Models\Action,
    Phalcon\Acl,
    Phalcon\Acl\Adapter\Memory,
    Phalcon\Acl\Role as PhalconRole,
    Phalcon\Acl\Resource;
use App\Models\ModuleRole;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\SelectField;
use Modules\Admin\Forms\Fields\TextField;

class ModuleRoleController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(ModuleRole::class, 'module_role_id')
            ->registerField(new IdField('module_role_id', 'ID', []))
            ->registerField(new SelectField('module_id', 'Модуль', [], [$this->model]))
            ->registerField(new TextField('name', 'Название'));

        parent::initialize();
    }

    private function createAcl(int $moduleRoleId, array $actions):Memory
    {
        $anonymousAccess = [
            'index' => ['index'],
            'auth' => ['index'],
            'error' => ['show404']
        ];

        $acl = new Memory();
        $acl->setDefaultAction(Acl::DENY);
        $acl->addRole(new PhalconRole($moduleRoleId));
        // TODO разобраться почему не работает setDefaultAction DENY
        $acl->deny($moduleRoleId, '*', '*');
        if ($moduleRoleId == 1 || $moduleRoleId == 5) {
            $acl->allow($moduleRoleId, '*', '*');
        } else if ($moduleRoleId == 2) {
            foreach ($anonymousAccess as $controllerName => $actionNames) {
                $currentController = ModuleController::findFirst("module_id = " . $this->item->module_id . " AND controller_name = '" . $controllerName . "'");
                foreach ($actionNames as $actionName) {
                    $acl->addResource(new Resource($currentController->module_controller_id), [Action::getId($actionName)]);
                    $acl->allow($moduleRoleId, $currentController->module_controller_id, [Action::getId($actionName)]);
                }
            }
        } else {
            $controllers = ModuleController::findByModuleId($this->item->module_id);
            foreach ($controllers as $controller) {
                $actionIds = [];
                foreach ($controller->actions as $action) $actionIds[] = $action->action_id;
                $acl->addResource(new Resource($controller->module_controller_id), $actionIds);
            }
            foreach ($actions as $moduleControllerId => $actionIds) {
                $acl->allow($moduleRoleId, $moduleControllerId, $actionIds);
            }
        }
        return $acl;
    }

    protected function afterEdit():bool
    {
        $controllerActions = $this->request->getPost('action_ids');
        if (empty($controllerActions)) $controllerActions = [];
        $acl = $this->createAcl($this->item->module_role_id, $controllerActions);
        $this->item->acl = serialize($acl);
        if (!$this->item->update()) {
            $this->flashErrors();
            return false;
        }
        return true;
    }

    protected function setEditVars():void
    {
        parent::setEditVars();
        $acl = (empty($this->item->acl) ? null : unserialize($this->item->acl));
        $this->view->setVar('acl', $acl);
        $this->view->setVar('controllers', ModuleController::findByModuleId($this->item->module_id));
    }

    public function deleteAction(int $itemId):bool
    {
        $moduleUser = $this->auth->moduleUser;
        if ($moduleUser->module_role_id == $itemId) {
            $this->flashSession->error($this->labels['delete_self']);
            $this->response->redirect('/' . $this->controller->controller_name);
            return false;
        }
        return parent::deleteAction($itemId);
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

    protected function createPost():bool
    {
        return parent::createPost();
    }

    protected function editPost():bool
    {
        return parent::editPost();
    }

    protected function setCreateVars():void
    {
        parent::setCreateVars();
    }

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }
}

