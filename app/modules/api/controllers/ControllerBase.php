<?php
namespace Modules\Api\Controllers;

use App\Models\Base;
use Phalcon\Mvc\Controller,
    Phalcon\Mvc\View;
use App\Traits\ControllerTrait;

class ControllerBase extends Controller
{
    use ControllerTrait;

    public $module;
    public $controller;
    public $action;
    public $functions;
    public $acl;
    public $auth;

    protected $model;
    protected $labels;
    protected $form;
    protected $item;
    protected $primaryKey;
    protected $dataTablesColumns;
    protected $searchFields;
    protected $moduleName = 'api';

    public function initialize()
    {
        if (!empty($this->model)) {
            $modelClass = $this->model;
            $this->labels = $modelClass::$labels;
            $this->dataTablesColumns = $modelClass::$dataTablesColumns;
            $this->searchFields = $modelClass::$searchFields;
        }
    }

    public function indexAction():bool
    {
        return false;
    }

    public function listAction():bool
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $start = (int) $_POST['start'];
        $length = (int) $_POST['length'];
        $draw = (int) $_POST['draw'];
        $column = (int) $_POST['order'][0]['column'];
        $sort = (string) strtoupper($_POST['order'][0]['dir']);
        $search = (string) $_POST['search']['value'];

        if (isset($this->dataTablesColumns[($column - 1)])) {
            $columnData = $this->dataTablesColumns[($column - 1)];
            foreach ($columnData as $field => $valueChain) {
                $column = $field . ' ' . $sort;
            }
        } else {
            $column = $this->primaryKey . ' ASC';
        }

        $params = [
            "order" => $column,
            "limit" => $length,
            "offset" => $start,
            "conditions" => "name LIKE ?1",
            "bind" => [
                1 => "%" . $search . "%"
            ]
        ];

        if (empty($search))
            unset($params['conditions'], $params['bind']);

        $modelClass = $this->model;
        $items = $modelClass::find($params);
        $count = $modelClass::count();

        $tableData = [];

        if (!empty($search)) {
            unset($params['order'], $params['limit'], $params['offset']);
            $countFiltered = $modelClass::find($params);
            $tableData['recordsFiltered'] = $countFiltered->count();
        } else {
            $tableData['recordsFiltered'] = $count;
        }

        if (count($items) == 0) {
            $emptyArray = [];
            for ($i = 0; $i < count($this->dataTablesColumns) + 4; $i++) {
                $emptyArray[] = ($i == 1) ? 'Не найдено' : '';
            }
            $tableData['data'][] = $emptyArray;
            $count = 0;
        }

        $tableData['draw'] = $draw;
        $tableData['recordsTotal'] = $count;

        foreach ($items as $item) {
            $view = '<a href="/' . $this->controller->controller_name . '/view/' . $item->getVal([$this->primaryKey]) . '"><i class="fa fa-eye text-navy"></i></a>';
            $edit = '<a href="/' . $this->controller->controller_name . '/edit/' . $item->getVal([$this->primaryKey]) . '"><i class="fa fa-pencil text-success"></i></a>';
            $delete = '<a title="Удалить"><i class="fa fa-trash text-danger delete-button" link="/' . $this->controller->controller_name . '/delete/' . $item->getVal([$this->primaryKey]) . '"></i></a>';
            $select = '<input type="checkbox" class="check_group" check_group_id="list_check" value="'. $item->getVal([$this->primaryKey]) .'"/>';
            $lineArray = [];
            $lineArray[] = $select;
            foreach ($this->dataTablesColumns as $columnData) {
                $lineArray[] = $this->listColumnHandler($item, $columnData);
            }
            $lineArray[] = $view;
            $lineArray[] = $edit;
            $lineArray[] = $delete;
            $tableData['data'][] = $lineArray;
        }
        // TODO: сделать нормальную отправку хедера
        header('Access-Control-Allow-Origin: *');
        echo json_encode($tableData);
        return true;
    }

    public function selectAction():bool
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $limit = 50;
        $search = (isset($_POST['search'])) ? $_POST['search'] : false;
        $search = str_replace('"', '', $search);
        $response = [];
        $response['total'] = 0;
        $response['items'] = [];
        $modelClass = $this->model;
        $conditions = [];
        if ($search && strlen($search) > 0) {
            if (!empty($this->searchFields)) {
                foreach ($this->searchFields as $searchField) {
                    $conditions[] = $searchField . " LIKE '%" . $search . "%'";
                }
            }
        }
        $items = $modelClass::find([implode(' OR ', $conditions), 'limit' => $limit])->toArray();
        $response['total'] = count($items);
        if ($response['total'] > 0) {
            foreach ($items as $item) {
                $selection = [];
                if (!empty($this->searchFields)) {
                    foreach ($this->searchFields as $searchField) {
                        $selection[] = $item[$searchField];
                    }
                }
                if (empty($selection)) {
                    $selection[] = $item[$this->primaryKey];
                }
                $response['items'][] = [
                    'selection' => implode(' / ', $selection),
                    'id' => $item[$this->primaryKey]
                ];
            }
        }
        header('Access-Control-Allow-Origin: *');
        echo json_encode($response);
        return true;
    }

    protected function listColumnHandler(Base $item, array $columnData)
    {
        $value = '';
        foreach ($columnData as $field => $valueChain) {
            $value = $item;
            $n = 0;
            foreach ($valueChain as $unit) {
                $n++;
                $value = $value->$unit;
                if (empty($value) && $n < count($valueChain)) {
                    $value = null;
                    break;
                }
            }
            $value = $this->listValueHandler($field, $value);
        }
        return $value;
    }

    protected function listValueHandler(string $field, $value)
    {
        return $value;
    }

    public function registerModel(string $modelClass, string $primaryKey):ControllerBase
    {
        $this->model = $modelClass;
        $this->primaryKey = $primaryKey;
        return $this;
    }

}
