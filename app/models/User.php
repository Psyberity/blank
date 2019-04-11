<?php
namespace App\Models;

class User extends Base
{
	public $user_id;
	public $name;
	public $email;
	public $phone;
	public $password;
	public $avatar;
	public $active;
    public $last_login;
    public $token;

    public static $dataTablesColumns = [
        ['name' => ['name']],
        ['email' => ['email']],
        ['phone' => ['phone']],
        ['active' => ['active']]
    ];
    public static $searchFields = ['name', 'email'];
    public static $fileFields = ['avatar'];
    public static $labels = [
        'index' => 'Список пользователей',
        'create' => 'Добавить пользователя',
        'edit' => 'Редактировать пользователя',
        'created' => 'Пользователь добавлен',
        'edited' => 'Пользователь изменен',
        'deleted' => 'Пользователь удален',
        'not_found' => 'Пользователь не найден'
    ];

    public function beforeValidationOnCreate()
    {
        $now = date('Y-m-d H:i:s');
        $this->genToken($now);
    }

    public function updateLogin():void
    {
        $now = date('Y-m-d H:i:s');
        $this->last_login = $now;
        $this->genToken($now);
        $this->update();
    }

    private function genToken(string $timestamp):void
    {
        $this->token = md5($this->email . $this->password . $timestamp);
    }

    public function getAvatar():string
    {
        if (!empty($this->avatar)) return $this->avatar;
        return '/modules/admin/img/default_avatar.png';
    }
}