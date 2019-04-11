<?php
namespace App\Traits;

use App\Models\MenuLine;

trait AdminControllerTrait
{
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

    public function currentViewPath():string
    {
        return $this->view->getViewsDir() . $this->controller->controller_name . '/' . $this->action->action_name . '.volt';
    }
}