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

    public static $primary_key = 'user_id';
    public static $datatables_columns = [
        ['name' => ['name']],
        ['email' => ['email']],
        ['phone' => ['phone']],
        ['active' => ['active']]
    ];
    public static $search_fields = ['name', 'email'];
    public static $file_fields = ['avatar'];
    public static $labels = [
        'index' => 'Список пользователей',
        'create' => 'Добавить пользователя',
        'edit' => 'Редактировать пользователя',
        'created' => 'Пользователь добавлен',
        'edited' => 'Пользователь изменен',
        'not_found' => 'Пользователь не найден'
    ];

    public function beforeValidationOnCreate()
    {
        $now = date('Y-m-d H:i:s');
        $this->genToken($now);
    }

    public function updateLogin()
    {
        $now = date('Y-m-d H:i:s');
        $this->last_login = $now;
        $this->genToken($now);
        $this->update();
    }

    private function genToken($timestamp)
    {
        $this->token = md5($this->email . $this->password . $timestamp);
    }

    public function getAvatar()
    {
        if (!empty($this->avatar)) return $this->avatar;
        return '/modules/admin/img/default_avatar.png';
    }
}