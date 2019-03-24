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

    public function indexAction()
    {
        return parent::indexAction();
    }

    public function createAction()
    {
        parent::createAction();
    }

    public function editAction($item_id)
    {
        return parent::editAction($item_id);
    }

    public function deleteAction($item_id)
    {
        return parent::deleteAction($item_id);
    }

    protected function createPost()
    {
        return parent::createPost();
    }

    protected function editPost()
    {
        return parent::editPost();
    }

    protected function setCreateVars()
    {
        parent::setCreateVars();
    }

    protected function setEditVars()
    {
        parent::setEditVars();
    }

    public function setCommonVars()
    {
        parent::setCommonVars();
    }
}

