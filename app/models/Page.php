<?php
namespace App\Models;

class Page extends Base
{
	public $page_id;
    public $page_name;
	public $name;
	public $content;

    public static $primary_key = 'page_id';
    public static $datatables_columns = [
        ['name' => ['name']],
        ['page_name' => ['page_name']]
    ];
    public static $search_fields = ['name', 'page_name'];
    public static $labels = [
        'index' => 'Список страниц',
        'create' => 'Добавить страницу',
        'edit' => 'Редактировать страницу',
        'created' => 'Страница добавлена',
        'edited' => 'Страница изменена',
        'not_found' => 'Страница не найдена'
    ];
}