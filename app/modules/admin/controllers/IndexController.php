<?php
namespace Modules\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->setCommonVars();

        $this->view->setVar('h2', 'Главная');
        return true;
    }
}

