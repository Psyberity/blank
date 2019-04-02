<?php
namespace Modules\Admin\Controllers;

use Phalcon\Mvc\View;

class AuthController extends ControllerBase
{
    public function indexAction():bool
    {
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        if ($this->request->isPost()) {
            $login = $this->auth->login($this->request->getPost('email'), $this->request->getPost('password'));
            if ($login) {
                $this->flashSession->success($this->auth->user->name);
                $this->response->redirect('');
            } else {
                $this->flashSession->error('Неверный пароль либо логин');
                $this->response->redirect('/auth');
            }
        }
        return true;
    }

    public function logoutAction():bool
    {
        $this->auth->closeSession();
        $this->response->redirect('');
        return true;
    }
}

