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

class ModuleRoleController extends ModelControllerBase
{
    protected $model = ModuleRole::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'module_role_id', 'ID', [])
            ->registerField(FieldBase::TYPE_SELECT, 'module_id', 'Модуль', [])
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'Название');
    }

    private function createAcl($module_role_id, $actions)
    {
        $anonymous_access = [
            'index' => ['index'],
            'auth' => ['index'],
            'error' => ['show404']
        ];

        $acl = new Memory();
        $acl->setDefaultAction(Acl::DENY);
        $acl->addRole(new PhalconRole($module_role_id));
        // TODO разобраться почему не работает setDefaultAction DENY
        $acl->deny($module_role_id, '*', '*');
        if ($module_role_id == 1 || $module_role_id == 5) {
            $acl->allow($module_role_id, '*', '*');
        } else if ($module_role_id == 2) {
            foreach ($anonymous_access as $controller_name => $action_names) {
                $current_controller = ModuleController::findFirst("module_id = " . $this->item->module_id . " AND controller_name = '" . $controller_name . "'");
                foreach ($action_names as $action_name) {
                    $acl->addResource(new Resource($current_controller->module_controller_id), [Action::getId($action_name)]);
                    $acl->allow($module_role_id, $current_controller->module_controller_id, [Action::getId($action_name)]);
                }
            }
        } else {
            $controllers = ModuleController::findByModuleId($this->item->module_id);
            foreach ($controllers as $controller) {
                $action_ids = [];
                foreach ($controller->actions as $action) $action_ids[] = $action->action_id;
                $acl->addResource(new Resource($controller->module_controller_id), $action_ids);
            }
            foreach ($actions as $module_controller_id => $action_ids) {
                $acl->allow($module_role_id, $module_controller_id, $action_ids);
            }
        }
        return $acl;
    }

    protected function afterEdit()
    {
        $controller_actions = $this->request->getPost('action_ids');
        if (empty($controller_actions)) $controller_actions = [];
        $acl = $this->createAcl($this->item->module_role_id, $controller_actions);
        $this->item->acl = serialize($acl);
        if (!$this->item->update()) {
            $this->flashErrors();
        }
    }

    protected function setEditVars()
    {
        parent::setEditVars();
        $acl = (empty($this->item->acl) ? null : unserialize($this->item->acl));
        $this->view->setVar('acl', $acl);
        $this->view->setVar('controllers', ModuleController::findByModuleId($this->item->module_id));
    }

    public function deleteAction($item_id)
    {
        $module_user = $this->auth->module_user;
        if ($module_user->module_role_id == $item_id) {
            $this->flashSession->error($this->labels['delete_self']);
            return $this->response->redirect('/' . $this->controller->controller_name);
        }
        return parent::deleteAction($item_id);
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

    protected function createPost()
    {
        return parent::createPost();
    }

    protected function editPost()
    {
        return parent::editPost();
    }

    protected function setCreateVars()
    {
        parent::setCreateVars();
    }

    public function setCommonVars()
    {
        parent::setCommonVars();
    }
}

