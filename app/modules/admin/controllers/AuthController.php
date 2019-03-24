<?php
namespace Modules\Admin\Controllers;

use Phalcon\Mvc\View;

class AuthController extends ControllerBase
{
    public function indexAction()
    {
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        if ($this->request->isPost()) {
            $login = $this->auth->login($this->request->getPost('email'), $this->request->getPost('password'));
            if ($login) {
                $this->flashSession->success($this->auth->user->name);
                return $this->response->redirect('');
            } else {
                $this->flashSession->error('Неверный пароль либо логин');
                return $this->response->redirect('/auth');
            }
        }
    }

    public function logoutAction()
    {
        $this->auth->closeSession();
        return $this->response->redirect('');
    }
}

