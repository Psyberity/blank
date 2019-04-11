<?php
namespace App\Traits;

use App\Models\Module,
    App\Models\ModuleController,
    App\Models\Action,
    App\Classes\Auth,
    App\Classes\Functions;

trait ControllerTrait
{
    public function beforeExecuteRoute()
    {
        $this->module = Module::findFirstByModuleName($this->moduleName);
        $this->module->checkDirs();
        $controllerName = $this->dispatcher->getControllerName();
        $this->controller = ModuleController::findFirst("module_id = " . $this->module->module_id . " AND controller_name = '" . $controllerName . "'");
        $actionName = $this->dispatcher->getActionName();
        $this->action = Action::findFirstByActionName($actionName);
        $this->lang = $this->dispatcher->getParam('lang');
        if (!$this->controller) {
            $this->flashSession->error('Контроллер не найден: ' . $controllerName);
            $this->response->redirect('');
            return false;
        }
        if (!$this->action) {
            $this->flashSession->error('Экшен не найден: ' . $actionName);
            $this->response->redirect('');
            return false;
        }

        $this->auth = new Auth($this->module, $this->security, $this->config->application->anonymous_role_id);
        if($this->moduleName == 'api') {
            $token = $this->request->get('token');
            if (empty($token)) $token = $this->dispatcher->getParam('token');
            if (!empty($token)) $this->auth->tokenLogin($token);
        }
        $this->acl = $this->auth->acl;

        if($this->moduleName == 'admin') {
            if ($this->auth->moduleUser->module_role_id == $this->config->application->anonymous_role_id && $this->controller->controller_name !== 'auth') {
                if ($this->controller->controller_name !== 'index') {
                    $this->flashSession->error('Вы не авторизованы');
                }
                $this->response->redirect('auth');
                return false;
            }
        }

        if (!$this->acl->isAllowed($this->auth->moduleUser->module_role_id, $this->controller->module_controller_id, $this->action->action_id)) {
            $this->flashSession->error('У Вас нет прав на это действие');
            $this->response->redirect('');
            return false;
        }

        $this->functions = new Functions();
        if($this->moduleName == 'admin') {
            $this->apiUrl = $this->config->modules->get($this->config->module_api)->subDomains[0] . '.' . $this->config->domain;
        }
    }
}