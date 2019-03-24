<?php
namespace Modules\Www\Controllers;

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

    protected $model;
    protected $labels;
    protected $form;
    protected $item;
    protected $primary_key;
    protected $assets_change;

    public function beforeExecuteRoute()
    {
        $module_name = $this->dispatcher->getModuleName();
        $this->module = Module::findFirstByModuleName($module_name);
        $this->module->checkDirs();
        $controller_name = $this->dispatcher->getControllerName();
        $this->controller = ModuleController::findFirst("module_id = " . $this->module->module_id . " AND controller_name = '" . $controller_name . "'");
        $action_name = $this->dispatcher->getActionName();
        $this->action = Action::findFirstByActionName($action_name);
        $this->lang = $this->dispatcher->getParam('lang');
        if (!$this->controller) {
            $this->flashSession->error('Контроллер не найден: ' . $controller_name);
            return $this->response->redirect('');
        }
        if (!$this->action) {
            $this->flashSession->error('Экшен не найден: ' . $action_name);
            return $this->response->redirect('');
        }

        $this->auth = new Auth($this->module, $this->security, $this->config->application->anonymous_role_id);
        $this->acl = $this->auth->acl;

        if (!$this->acl->isAllowed($this->auth->module_user->module_role_id, $this->controller->module_controller_id, $this->action->action_id)) {
            $this->flashSession->error('У Вас нет прав на это действие');
            return $this->response->redirect('');
        }

        $this->functions = new Functions();
    }

    public function initialize()
    {
        $this->setAssets($this->action->action_name);
    }

    public function setCommonVars()
    {
        $this->view->setVar('controllerName', $this->controller->controller_name);
        $this->view->setVar('actionName', $this->action->action_name);
        $this->view->setVar('flashSession', $this->flashSession);
        $this->view->setVar('acl', $this->acl);
        $this->view->setVar('auth', $this->auth);
    }

    public function indexAction()
    {
        $this->setCommonVars();
        return true;
    }

    protected function setAssets($set_name)
    {
        $js_set = $this->config->asset_sets->js->get('_all');
        if (!empty($js_set)) {
            $js_action_set = $this->config->asset_sets->js->get($set_name);
            if (!empty($js_action_set)) {
                $js_set->merge($js_action_set);
            }
            $js_assets = [];
            foreach ($js_set as $asset_name) {
                $js_assets[$asset_name] = true;
            }
            if (!empty($this->assets_change[$set_name]['js'])) {
                foreach ($this->assets_change[$set_name]['js'] as $asset_name => $flag) {
                    $js_assets[$asset_name] = $flag;
                }
            }
            foreach ($js_assets as $asset_name => $flag) {
                if ($flag === true) {
                    $this->assets->collection('footer')->addJs('/modules/' . $this->module->module_name . $this->config->assets->js->get($asset_name));
                }
            }
        }

        $css_set = $this->config->asset_sets->css->get('_all');
        if (!empty($css_set)) {
            $css_action_set = $this->config->asset_sets->css->get($set_name);
            if (!empty($css_action_set)) {
                $css_set->merge($css_action_set);
            }
            $css_assets = [];
            foreach ($css_set as $asset_name) {
                $css_assets[$asset_name] = true;
            }
            if (!empty($this->assets_change[$set_name]['css'])) {
                foreach ($this->assets_change[$set_name]['css'] as $asset_name => $flag) {
                    $css_assets[$asset_name] = $flag;
                }
            }
            foreach ($css_assets as $asset_name => $flag) {
                if ($flag === true) {
                    $this->assets->addCss('/modules/' . $this->module->module_name . $this->config->assets->css->get($asset_name));
                }
            }
        }
    }

    protected function flashErrors($object = null)
    {
        if ($object === null) $object = $this->item;
        foreach ($object->getMessages() as $message) {
            $this->flashSession->error($message->getMessage());
        }
    }
}
