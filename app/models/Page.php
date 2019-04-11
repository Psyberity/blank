<?php
namespace App\Models;

class Page extends Base
{
	public $page_id;
    public $page_name;
	public $name;
	public $content;

    public static $dataTablesColumns = [
        ['name' => ['name']],
        ['page_name' => ['page_name']]
    ];
    public static $searchFields = ['name', 'page_name'];
    public static $labels = [
        'index' => 'Список страниц',
        'create' => 'Добавить страницу',
        'edit' => 'Редактировать страницу',
        'created' => 'Страница добавлена',
        'edited' => 'Страница изменена',
        'deleted' => 'Страница удалена',
        'not_found' => 'Страница не найдена'
    ];
}