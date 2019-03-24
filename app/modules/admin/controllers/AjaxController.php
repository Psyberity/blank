<?php
namespace Modules\Admin\Controllers;

use Phalcon\Mvc\View;

class AjaxController extends ControllerBase
{
    public function initialize()
    {

    }

    public function apiAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $post_data = $this->request->getPost();
        $api_controller = $post_data['controller'];
        $api_action = $post_data['action'];
        unset($post_data['controller']);
        unset($post_data['action']);

        $this->dispatcher->forward([
            'module' => 'api',
            'namespace' => 'Modules\Api\Controllers',
            'controller' => $api_controller,
            'action' => $api_action,
            'params' => ['token' => $this->auth->user->token]
        ]);
        return true;
    }
}

