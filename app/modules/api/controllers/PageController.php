<?php
namespace Modules\Api\Controllers;

use App\Models\Page;

class PageController extends ControllerBase
{
    public function initialize()
    {
        $this->registerModel(Page::class, 'page_id');

        parent::initialize();
    }

    public function listAction():bool
    {
        return parent::listAction();
    }

    public function selectAction():bool
    {
        return parent::selectAction();
    }
}

