<?php
namespace Modules\Admin\Controllers;

use Phalcon\Mvc\View;

class AjaxController extends ControllerBase
{
    public function initialize()
    {

    }

    public function apiAction():bool
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $postData = $this->request->getPost();
        $apiController = $postData['controller'];
        $apiAction = $postData['action'];
        unset($postData['controller']);
        unset($postData['action']);

        $this->dispatcher->forward([
            'module' => 'api',
            'namespace' => 'Modules\Api\Controllers',
            'controller' => $apiController,
            'action' => $apiAction,
            'params' => ['token' => $this->auth->user->token]
        ]);
        return true;
    }
}

