<?php
namespace Modules\Admin\Controllers;

use App\Models\Base;
use App\Models\MenuLine;
use App\Models\Module;
use App\Models\ModuleController;
use App\Classes\Auth,
    App\Classes\Functions,
    App\Models\Action,
    Phalcon\Mvc\Controller as PhalconController;

class ControllerBase extends PhalconController
{
    public $module;
    public $controller;
    public $action;
    public $functions;
    public $acl;
    public $auth;
    public $lang;
    public $apiUrl;

    protected $assetsChange;

    public function beforeExecuteRoute()
    {
        $moduleName = $this->dispatcher->getModuleName();
        $this->module = Module::findFirstByModuleName($moduleName);
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
        $this->acl = $this->auth->acl;

        if ($this->auth->moduleUser->module_role_id == $this->config->application->anonymous_role_id && $this->controller->controller_name !== 'auth') {
            if ($this->controller->controller_name !== 'index') {
                $this->flashSession->error('Вы не авторизованы');
            }
            $this->response->redirect('auth');
            return false;
        }

        if (!$this->acl->isAllowed($this->auth->moduleUser->module_role_id, $this->controller->module_controller_id, $this->action->action_id)) {
            $this->flashSession->error('У Вас нет прав на это действие');
            $this->response->redirect('');
            return false;
        }

        $this->functions = new Functions();
        $this->apiUrl = $this->config->modules->get($this->config->module_api)->subDomains[0] . '.' . $this->config->domain;
    }

    public function initialize()
    {
        $this->setAssets($this->action->action_name);
    }

    public function setCommonVars():void
    {
        $menu = MenuLine::getMenu($this->auth, $this->controller->module_controller_id, $this->action->action_id);
        $this->view->setVar('menu', $menu);
        $this->view->setVar('moduleId', $this->module->module_id);
        $this->view->setVar('controllerName', $this->controller->controller_name);
        $this->view->setVar('actionName', $this->action->action_name);
        $this->view->setVar('flashSession', $this->flashSession);
        $this->view->setVar('acl', $this->acl);
        $this->view->setVar('auth', $this->auth);
        $this->view->setVar('api_url', $this->apiUrl);
    }

    protected function setAssets(string $setName):void
    {
        $jsSet = $this->config->asset_sets->js->get('_all');
        if (!empty($jsSet)) {
            $jsActionSet = $this->config->asset_sets->js->get($setName);
            if (!empty($jsActionSet)) {
                $jsSet->merge($jsActionSet);
            }
            $jsAssets = [];
            foreach ($jsSet as $assetName) {
                $jsAssets[$assetName] = true;
            }
            if (!empty($this->assetsChange[$setName]['js'])) {
                foreach ($this->assetsChange[$setName]['js'] as $assetName => $flag) {
                    $jsAssets[$assetName] = $flag;
                }
            }
            foreach ($jsAssets as $assetName => $flag) {
                if ($flag === true) {
                    $this->assets->collection('footer')->addJs('/modules/' . $this->module->module_name . $this->config->assets->js->get($assetName));
                }
            }
        }

        $cssSet = $this->config->asset_sets->css->get('_all');
        if (!empty($cssSet)) {
            $cssActionSet = $this->config->asset_sets->css->get($setName);
            if (!empty($cssActionSet)) {
                $cssSet->merge($cssActionSet);
            }
            $cssAssets = [];
            foreach ($cssSet as $assetName) {
                $cssAssets[$assetName] = true;
            }
            if (!empty($this->assetsChange[$setName]['css'])) {
                foreach ($this->assetsChange[$setName]['css'] as $assetName => $flag) {
                    $cssAssets[$assetName] = $flag;
                }
            }
            foreach ($cssAssets as $assetName => $flag) {
                if ($flag === true) {
                    $this->assets->addCss('/modules/' . $this->module->module_name . $this->config->assets->css->get($assetName));
                }
            }
        }
    }

    protected function flashErrors(Base $object):void
    {
        foreach ($object->getMessages() as $message) {
            $this->flashSession->error($message->getMessage());
        }
    }

    public function currentViewPath():string
    {
        return $this->view->getViewsDir() . $this->controller->controller_name . '/' . $this->action->action_name . '.volt';
    }
}
