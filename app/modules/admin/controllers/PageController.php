<?php
namespace Modules\Admin\Controllers;

use App\Models\Page;
use Modules\Admin\Forms\Fields\FieldBase;
use Modules\Admin\Forms\Fields\IdField;
use Modules\Admin\Forms\Fields\TextField;
use Modules\Admin\Forms\Fields\WysiwygField;

class PageController extends ModelControllerBase
{
    public function initialize()
    {
        $this->registerModel(Page::class, 'page_id')
            ->registerField(new IdField('page_id', 'ID', []))
            ->registerField(new TextField('page_name', 'Системное имя'))
            ->registerField(new TextField('name', 'Заголовок'))
            ->registerField(new WysiwygField('content', 'Контент', []));

        parent::initialize();
    }

    public function indexAction():bool
    {
        return parent::indexAction();
    }

    public function createAction():bool
    {
        return parent::createAction();
    }

    public function editAction(int $itemId):bool
    {
        return parent::editAction($itemId);
    }

    public function deleteAction(int $itemId):bool
    {
        return parent::deleteAction($itemId);
    }

    protected function createPost():bool
    {
        return parent::createPost();
    }

    protected function editPost():bool
    {
        return parent::editPost();
    }

    protected function setCreateVars():void
    {
        parent::setCreateVars();
    }

    protected function setEditVars():void
    {
        parent::setEditVars();
    }

    public function setCommonVars():void
    {
        parent::setCommonVars();
    }
}

