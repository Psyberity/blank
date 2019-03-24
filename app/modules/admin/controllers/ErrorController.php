<?php
namespace Modules\Admin\Controllers;

class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        $this->setCommonVars();
        $this->response->setStatusCode(404, 'Not Found');

        return true;
	}
}

