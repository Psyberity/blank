<?php
namespace Modules\Api\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->pick('404/404');
	}

    public function listAction()
    {
        return false;
    }

    public function selectAction()
    {
        return false;
    }
}

