<?php
namespace Modules\Api\Controllers;

use App\Models\Page;

class PageController extends ControllerBase
{
    protected $model = Page::class;

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

