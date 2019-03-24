<?php
namespace App\Classes;

use App\Models\ModuleUser;
use Phalcon\Di;

class Auth
{
    private $security;
    private $module;
    private $user;
    private $module_user;
    private $acl;

    public function __construct($module, $security, $anonymous_role_id)
    {
        $this->module = $module;
        $this->security = $security;

        $is_auth = $this->isAuth();
        $session = true;
        if ($is_auth) {
            $module_user = ModuleUser::findFirst($is_auth['module_user_id']);
        } else {
            $module_user = ModuleUser::findFirstByModuleRoleId($anonymous_role_id);
            $session = false;
        }
        $this->authorize($module_user, $session);
    }

    public function __get($key)
    {
        if ($key == 'module_user' || $key == 'user' || $key == 'acl') return $this->$key;
        if ($key == 'token') return $this->module_user->user->token;
        return null;
    }

    public function login($email, $password)
    {
        $email = trim($email);
        $password = trim($password);
        $builder = Di::getDefault()->get('modelsManager')->createBuilder()
            ->from(['mu' => 'App\Models\ModuleUser'])
            ->join('App\Models\User', 'u.user_id = mu.user_id', 'u')
            ->join('App\Models\ModuleRole', 'mr.module_id = ' . $this->module->module_id . ' AND mr.module_role_id = mu.module_role_id', 'mr')
            ->where('u.active = 1 AND u.email = :email:', ['email' => $email])
            ->limit(1);
        $module_user = $builder->getQuery()->execute();
        if (!$module_user || count($module_user) != 1) return false;
        if ($this->security->checkHash($password, $module_user[0]->user->password)) {
            $this->authorize($module_user[0]);
            $this->user->updateLogin();
            return true;
        }
        return false;
    }

    public function tokenLogin($token)
    {
        if (empty($token)) return false;
        $builder = Di::getDefault()->get('modelsManager')->createBuilder()
            ->from(['mu' => 'App\Models\ModuleUser'])
            ->join('App\Models\User', 'u.user_id = mu.user_id', 'u')
            ->join('App\Models\ModuleRole', 'mr.module_id = ' . $this->module->module_id . ' AND mr.module_role_id = mu.module_role_id', 'mr')
            ->where('u.active = 1 AND u.token = :token:', ['token' => $token])
            ->limit(1);
        $module_user = $builder->getQuery()->execute();
        if (!$module_user || count($module_user) != 1) return false;
        $this->authorize($module_user[0], false);
        return true;
    }

    private function isAuth()
    {
        $auth_session = Di::getDefault()->getSession()->get($this->module->module_name);
        return !empty($auth_session) ? $auth_session : null;
    }

    private function authorize($module_user, $session = true)
    {
        $this->module_user = $module_user;
        $this->user = $module_user->user;
        $this->acl = unserialize($module_user->role->acl);
        if ($session) $this->startSession();
    }

    private function startSession()
    {
        Di::getDefault()->getSession()->set($this->module->module_name, $this->module_user->toArray());
    }

    public function closeSession()
    {
        Di::getDefault()->getSession()->remove($this->module->module_name);
        Di::getDefault()->getSession()->destroy();
    }

}