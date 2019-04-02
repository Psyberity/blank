<?php
namespace Modules\Www\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action():bool
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->pick('404/404');
        return true;
	}

    public function indexAction():bool
    {
        return false;
    }

    public function createAction():bool
    {
        return false;
    }

    public function editAction(int $itemId):bool
    {
        return false;
    }

    public function deleteAction(int $itemId):bool
    {
        return false;
    }

    public function setCommonVars():void
    {

    }
}

