<?php
namespace Modules\Api\Controllers;

use App\Models\Base;
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
    protected $primaryKey;
    protected $dataTablesColumns;
    protected $searchFields;

    public function beforeExecuteRoute()
    {
        // TODO: подумать над этим
        //$moduleName = $this->dispatcher->getModuleName();
        $moduleName = $this->config->module_api;
        $this->module = Module::findFirstByModuleName($moduleName);
        $controllerName = $this->dispatcher->getControllerName();
        $this->controller = ModuleController::findFirst("module_id = " . $this->module->module_id . " AND controller_name = '" . $controllerName . "'");
        $actionName = $this->dispatcher->getActionName();
        $this->action = Action::findFirstByActionName($actionName);
        if (!$this->controller) {
            $this->flashSession->error('Контроллер не найден: ' . $controllerName);
            $this->response->redirect('');
            return false;
        }
        if (!$this->action) {
            $this->flashSession->error('Экшен не найден: ' . $actionName);
            $this->response->redirect('');
            return false;
        }

        $this->auth = new Auth($this->module, $this->security, $this->config->application->anonymous_role_id);
        $token = $this->request->get('token');
        if (empty($token)) $token = $this->dispatcher->getParam('token');
        if (!empty($token)) $this->auth->tokenLogin($token);
        $this->acl = $this->auth->acl;

        if (!$this->acl->isAllowed($this->auth->moduleUser->module_role_id, $this->controller->module_controller_id, $this->action->action_id)) {
            $this->flashSession->error('У Вас нет прав на это действие');
            $this->response->redirect('');
            return false;
        }

        $this->functions = new Functions();
    }

    public function initialize()
    {
        if (!empty($this->model)) {
            $modelClass = $this->model;
            $this->labels = $modelClass::$labels;
            $this->primaryKey = $modelClass::$primaryKey;
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
            for ($i = 0; $i < count($this->dataTablesColumns) + 2; $i++) {
                $emptyArray[] = ($i == 1) ? 'Не найдено' : '';
            }
            $tableData['data'][] = $emptyArray;
            $count = 0;
        }

        $tableData['draw'] = $draw;
        $tableData['recordsTotal'] = $count;

        $primaryKey = $this->primaryKey;
        foreach ($items as $item) {
            $view = '<a href="/' . $this->controller->controller_name . '/view/' . $item->$primaryKey . '"><i class="fa fa-eye text-navy"></i></a>';
            $edit = '<a href="/' . $this->controller->controller_name . '/edit/' . $item->$primaryKey . '"><i class="fa fa-pencil text-success"></i></a>';
            $delete = '<a title="Удалить"><i class="fa fa-trash text-danger delete-button" link="/' . $this->controller->controller_name . '/delete/' . $item->$primaryKey . '"></i></a>';
            $select = '<input type="checkbox" class="check_group" check_group_id="list_check" value="'. $item->$primaryKey .'"/>';
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

}
