<?php
namespace Modules\Api\Controllers;

use App\Models\Page;

class PageController extends ControllerBase
{
    protected $model = Page::class;

    public function listAction()
    {
        return parent::listAction();
    }

    public function selectAction()
    {
        return parent::selectAction();
    }
}

