<?php
namespace Modules\Admin\Controllers;

use App\Models\Page;
use Modules\Admin\Forms\Fields\FieldBase;

class PageController extends ModelControllerBase
{
    protected $model = Page::class;

    public function initialize()
    {
        parent::initialize();

        $this->registerField(FieldBase::TYPE_ID, 'page_id', 'ID', [])
            ->registerField(FieldBase::TYPE_TEXT, 'page_name', 'Системное имя')
            ->registerField(FieldBase::TYPE_TEXT, 'name', 'Заголовок')
            ->registerField(FieldBase::TYPE_WYSIWYG, 'content', 'Контент', []);
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

