<?php
namespace Modules\Api\Controllers;

use App\Models\Module;
use App\Models\ModuleController;
use App\Classes\Auth,
    App\Classes\Functions,
    App\Models\Action,
    Phalcon\Mvc\Controller as PhalconController,
    Phalcon\Mvc\View;

class ControllerBase extends PhalconController
{
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
    protected $primary_key;
    protected $datatables_columns;
    protected $search_fields;
    protected $assets_change;

    public function beforeExecuteRoute()
    {
        // TODO: подумать над этим
        //$module_name = $this->dispatcher->getModuleName();
        $module_name = $this->config->module_api;
        $this->module = Module::findFirstByModuleName($module_name);
        $controller_name = $this->dispatcher->getControllerName();
        $this->controller = ModuleController::findFirst("module_id = " . $this->module->module_id . " AND controller_name = '" . $controller_name . "'");
        $action_name = $this->dispatcher->getActionName();
        $this->action = Action::findFirstByActionName($action_name);
        if (!$this->controller) {
            $this->flashSession->error('Контроллер не найден: ' . $controller_name);
            return $this->response->redirect('');
        }
        if (!$this->action) {
            $this->flashSession->error('Экшен не найден: ' . $action_name);
            return $this->response->redirect('');
        }

        $this->auth = new Auth($this->module, $this->security, $this->config->application->anonymous_role_id);
        $token = $this->request->get('token');
        if (empty($token)) $token = $this->dispatcher->getParam('token');
        if (!empty($token)) $this->auth->tokenLogin($token);
        $this->acl = $this->auth->acl;

        if (!$this->acl->isAllowed($this->auth->module_user->module_role_id, $this->controller->module_controller_id, $this->action->action_id)) {
            $this->flashSession->error('У Вас нет прав на это действие');
            return $this->response->redirect('');
        }

        $this->functions = new Functions();
    }

    public function initialize()
    {
        if (!empty($this->model)) {
            $model_class = $this->model;
            $this->labels = $model_class::$labels;
            $this->primary_key = $model_class::$primary_key;
            $this->datatables_columns = $model_class::$datatables_columns;
            $this->search_fields = $model_class::$search_fields;
        }
    }

    public function indexAction()
    {
        return false;
    }

    public function listAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $start = (int) $_POST['start'];
        $length = (int) $_POST['length'];
        $draw = (int) $_POST['draw'];
        $column = (int) $_POST['order'][0]['column'];
        $sort = (string) strtoupper($_POST['order'][0]['dir']);
        $search = (string) $_POST['search']['value'];

        if (isset($this->datatables_columns[($column - 1)])) {
            $column_data = $this->datatables_columns[($column - 1)];
            foreach ($column_data as $field => $value_chain) {
                $column = $field . ' ' . $sort;
            }
        } else {
            $column = $this->primary_key . ' ASC';
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

        $model_class = $this->model;
        $items = $model_class::find($params);
        $count = $model_class::count();

        $table_data = [];

        if (!empty($search)) {
            unset($params['order'], $params['limit'], $params['offset']);
            $countFiltered = $model_class::find($params);
            $table_data['recordsFiltered'] = $countFiltered->count();
        } else {
            $table_data['recordsFiltered'] = $count;
        }

        if (count($items) == 0) {
            $empty_array = [];
            for ($i = 0; $i < count($this->datatables_columns) + 2; $i++) {
                $empty_array[] = ($i == 1) ? 'Не найдено' : '';
            }
            $table_data['data'][] = $empty_array;
            $count = 0;
        }

        $table_data['draw'] = $draw;
        $table_data['recordsTotal'] = $count;

        $primary_key = $this->primary_key;
        foreach ($items as $item) {
            $view = '<a href="/' . $this->controller->controller_name . '/view/' . $item->$primary_key . '"><i class="fa fa-eye text-navy"></i></a>';
            $edit = '<a href="/' . $this->controller->controller_name . '/edit/' . $item->$primary_key . '"><i class="fa fa-pencil text-success"></i></a>';
            $delete = '<a title="Удалить"><i class="fa fa-trash text-danger delete-button" link="/' . $this->controller->controller_name . '/delete/' . $item->$primary_key . '"></i></a>';
            $select = '<input type="checkbox" class="check_group" check_group_id="list_check" value="'. $item->$primary_key .'"/>';
            $line_array = [];
            $line_array[] = $select;
            foreach ($this->datatables_columns as $column_data) {
                $line_array[] = $this->listColumnHandler($item, $column_data);
            }
            $line_array[] = $view;
            $line_array[] = $edit;
            $line_array[] = $delete;
            $table_data['data'][] = $line_array;
        }
        // TODO: сделать нормальную отправку хедера
        header('Access-Control-Allow-Origin: *');
        echo json_encode($table_data);
        return true;
    }

    public function selectAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $limit = 50;
        $search = (isset($_POST['search'])) ? $_POST['search'] : false;
        $search = str_replace('"', '', $search);
        $response = [];
        $response['total'] = 0;
        $response['items'] = [];
        $model_class = $this->model;
        $conditions = [];
        if ($search && strlen($search) > 0) {
            if (!empty($this->search_fields)) {
                foreach ($this->search_fields as $search_field) {
                    $conditions[] = $search_field . " LIKE '%" . $search . "%'";
                }
            }
        }
        $items = $model_class::find([implode(' OR ', $conditions), 'limit' => $limit])->toArray();
        $response['total'] = count($items);
        if ($response['total'] > 0) {
            foreach ($items as $item) {
                $selection = [];
                if (!empty($this->search_fields)) {
                    foreach ($this->search_fields as $search_field) {
                        $selection[] = $item[$search_field];
                    }
                }
                if (empty($selection)) {
                    $selection[] = $item[$this->primary_key];
                }
                $response['items'][] = [
                    'selection' => implode(' / ', $selection),
                    'id' => $item[$this->primary_key]
                ];
            }
        }
        header('Access-Control-Allow-Origin: *');
        echo json_encode($response);
        return true;
    }

    protected function listColumnHandler($item, $column_data)
    {
        $value = '';
        foreach ($column_data as $field => $value_chain) {
            $value = $item;
            $n = 0;
            foreach ($value_chain as $unit) {
                $n++;
                $value = $value->$unit;
                if (empty($value) && $n < count($value_chain)) {
                    $value = null;
                    break;
                }
            }
            $value = $this->listValueHandler($field, $value);
        }
        return $value;
    }

    protected function listValueHandler($field, $value)
    {
        return $value;
    }

}
