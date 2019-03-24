<?php
namespace Modules\Www\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->pick('404/404');
	}

    public function indexAction()
    {
        return false;
    }

    public function createAction()
    {
        return false;
    }

    public function editAction($item_id)
    {
        return false;
    }

    public function deleteAction($item_id)
    {
        return false;
    }

    public function setCommonVars()
    {
        return false;
    }
}

