<?php
namespace Modules\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction():bool
    {
        $this->setCommonVars();

        $this->view->setVar('h2', 'Главная');
        return true;
    }
}

