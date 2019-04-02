<?php
namespace Modules\Api\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action():bool
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->pick('404/404');
        return true;
	}

    public function listAction():bool
    {
        return false;
    }

    public function selectAction():bool
    {
        return false;
    }
}

