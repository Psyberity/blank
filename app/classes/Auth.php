<?php
namespace App\Classes;

use App\Models\Module;
use App\Models\ModuleUser;
use Phalcon\Di;
use Phalcon\Security;

class Auth
{
    private $security;
    private $module;
    private $user;
    private $moduleUser;
    private $acl;

    public function __construct(Module $module, Security $security, int $anonymousRoleId)
    {
        $this->module = $module;
        $this->security = $security;

        $isAuth = $this->isAuth();
        $session = true;
        if ($isAuth) {
            $moduleUser = ModuleUser::findFirst($isAuth['module_user_id']);
        } else {
            $moduleUser = ModuleUser::findFirstByModuleRoleId($anonymousRoleId);
            $session = false;
        }
        $this->authorize($moduleUser, $session);
    }

    public function __get(string $key)
    {
        if ($key == 'moduleUser' || $key == 'user' || $key == 'acl') return $this->$key;
        if ($key == 'token') return $this->moduleUser->user->token;
        return null;
    }

    public function login(string $email, string $password):bool
    {
        $email = trim($email);
        $password = trim($password);
        $builder = Di::getDefault()->get('modelsManager')->createBuilder()
            ->from(['mu' => 'App\Models\ModuleUser'])
            ->join('App\Models\User', 'u.user_id = mu.user_id', 'u')
            ->join('App\Models\ModuleRole', 'mr.module_id = ' . $this->module->module_id . ' AND mr.module_role_id = mu.module_role_id', 'mr')
            ->where('u.active = 1 AND u.email = :email:', ['email' => $email])
            ->limit(1);
        $moduleUser = $builder->getQuery()->execute();
        if (!$moduleUser || count($moduleUser) != 1) return false;
        if ($this->security->checkHash($password, $moduleUser[0]->user->password)) {
            $this->authorize($moduleUser[0]);
            $this->user->updateLogin();
            return true;
        }
        return false;
    }

    public function tokenLogin(string $token):bool
    {
        if (empty($token)) return false;
        $builder = Di::getDefault()->get('modelsManager')->createBuilder()
            ->from(['mu' => 'App\Models\ModuleUser'])
            ->join('App\Models\User', 'u.user_id = mu.user_id', 'u')
            ->join('App\Models\ModuleRole', 'mr.module_id = ' . $this->module->module_id . ' AND mr.module_role_id = mu.module_role_id', 'mr')
            ->where('u.active = 1 AND u.token = :token:', ['token' => $token])
            ->limit(1);
        $moduleUser = $builder->getQuery()->execute();
        if (!$moduleUser || count($moduleUser) != 1) return false;
        $this->authorize($moduleUser[0], false);
        return true;
    }

    private function isAuth():?array
    {
        $authSession = Di::getDefault()->getSession()->get($this->module->module_name);
        return !empty($authSession) ? $authSession : null;
    }

    private function authorize(ModuleUser $moduleUser, bool $session = true):void
    {
        $this->moduleUser = $moduleUser;
        $this->user = $moduleUser->user;
        $this->acl = unserialize($moduleUser->role->acl);
        if ($session) $this->startSession();
    }

    private function startSession():void
    {
        Di::getDefault()->getSession()->set($this->module->module_name, $this->moduleUser->toArray());
    }

    public function closeSession():void
    {
        Di::getDefault()->getSession()->remove($this->module->module_name);
        Di::getDefault()->getSession()->destroy();
    }

}